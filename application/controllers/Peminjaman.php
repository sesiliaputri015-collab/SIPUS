<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->cek_login();
        $this->load->model(['Peminjaman_model','Buku_model','Anggota_model','Denda_model']);
        $this->Peminjaman_model->update_status_terlambat();
    }

    /* ══════════════════════════════════════════════════════════
     * INDEX — tampil berbeda per role
     * ══════════════════════════════════════════════════════════ */
    public function index() {
        $search = $this->input->get('search');
        $status = $this->input->get('status') ?: 'semua';

        if ($this->role === 'anggota') {
            $id_anggota = $this->session->userdata('id_anggota');
            $data['peminjaman'] = $this->Peminjaman_model->get_by_anggota($id_anggota, $status);
            $data['title']      = 'Riwayat Peminjaman Saya';
        } else {
            $data['peminjaman'] = $this->Peminjaman_model->get_all($search, $status);
            $data['title']      = 'Data Peminjaman';
            $data['search']     = $search;
        }
        $data['status_aktif'] = $status;
        $this->render('peminjaman/index', $data);
    }

    /* ══════════════════════════════════════════════════════════
     * ANGGOTA: Ajukan Peminjaman
     * ══════════════════════════════════════════════════════════ */
    public function ajukan() {
        $this->cek_role('anggota');
        $search = $this->input->get('search');
        $data['title']  = 'Ajukan Peminjaman Buku';
        $data['buku']   = $this->Buku_model->get_tersedia($search);
        $data['search'] = $search;
        $this->render('peminjaman/ajukan', $data);
    }

    public function kirim_ajuan($buku_id) {
        $this->cek_role('anggota');
        $id_anggota = $this->session->userdata('id_anggota');

        if (!$id_anggota) {
            $this->session->set_flashdata('error', 'Akun Anda belum terhubung ke data anggota. Hubungi admin.');
            redirect('peminjaman'); return;
        }

        $buku = $this->Buku_model->get_by_id($buku_id);
        if (!$buku || $buku->tersedia < 1) {
            $this->session->set_flashdata('error', 'Buku tidak tersedia.');
            redirect('peminjaman/ajukan'); return;
        }

        $tgl_pinjam   = date('Y-m-d');
        $tgl_kembali  = date('Y-m-d', strtotime('+7 days'));

        $this->Peminjaman_model->insert([
            'anggota_id'      => $id_anggota,
            'buku_id'         => $buku_id,
            'tanggal_pinjam'  => $tgl_pinjam,
            'tanggal_kembali' => $tgl_kembali,
            'status'          => 'Menunggu',
        ]);
        // Kurangi stok sementara saat pengajuan
        $this->Buku_model->kurangi_tersedia($buku_id);

        $this->session->set_flashdata('sukses', 'Pengajuan peminjaman berhasil dikirim. Tunggu persetujuan operator.');
        redirect('peminjaman');
    }

    /* ══════════════════════════════════════════════════════════
     * ANGGOTA: Ajukan Pengembalian
     * ══════════════════════════════════════════════════════════ */
    public function ajukan_kembali($id) {
        $this->cek_role('anggota');
        $p = $this->Peminjaman_model->get_by_id($id);
        if (!$p || !in_array($p->status, ['Dipinjam','Terlambat'])) {
            $this->session->set_flashdata('error', 'Data peminjaman tidak valid.');
            redirect('peminjaman'); return;
        }
        // Pastikan milik anggota ini
        if ($p->anggota_id != $this->session->userdata('id_anggota')) {
            $this->session->set_flashdata('error', 'Akses ditolak.');
            redirect('peminjaman'); return;
        }
        $this->Peminjaman_model->update($id, ['status' => 'Menunggu Kembali']);
        $this->session->set_flashdata('sukses', 'Pengajuan pengembalian berhasil. Tunggu verifikasi operator.');
        redirect('peminjaman');
    }

    /* ══════════════════════════════════════════════════════════
     * OPERATOR/ADMIN: Setujui Peminjaman
     * ══════════════════════════════════════════════════════════ */
    public function setujui($id) {
        $this->cek_role(['admin','operator']);
        $p = $this->Peminjaman_model->get_by_id($id);
        if (!$p || $p->status !== 'Menunggu') {
            $this->session->set_flashdata('error', 'Status peminjaman tidak valid untuk disetujui.');
            redirect('peminjaman'); return;
        }
        $this->Peminjaman_model->setujui($id);
        $this->Anggota_model->increment_pinjam($p->anggota_id);
        $this->session->set_flashdata('sukses', 'Peminjaman disetujui. Status berubah menjadi Dipinjam.');
        redirect('peminjaman');
    }

    /* ══════════════════════════════════════════════════════════
     * OPERATOR/ADMIN: Tolak Peminjaman (kembalikan stok)
     * ══════════════════════════════════════════════════════════ */
    public function tolak($id) {
        $this->cek_role(['admin','operator']);
        $p = $this->Peminjaman_model->get_by_id($id);
        if (!$p || $p->status !== 'Menunggu') {
            $this->session->set_flashdata('error', 'Status peminjaman tidak valid untuk ditolak.');
            redirect('peminjaman'); return;
        }
        $this->Peminjaman_model->tolak($id);
        $this->Buku_model->tambah_tersedia($p->buku_id);
        $this->session->set_flashdata('sukses', 'Peminjaman ditolak dan stok buku dikembalikan.');
        redirect('peminjaman');
    }

    /* ══════════════════════════════════════════════════════════
     * OPERATOR/ADMIN: Verifikasi Pengembalian + Denda Otomatis
     * ══════════════════════════════════════════════════════════ */
    public function kembalikan($id) {
        $this->cek_role(['admin','operator']);
        $p = $this->Peminjaman_model->get_by_id($id);
        if (!$p || !in_array($p->status, ['Menunggu Kembali','Dipinjam','Terlambat'])) {
            $this->session->set_flashdata('error', 'Status peminjaman tidak valid untuk dikembalikan.');
            redirect('peminjaman'); return;
        }

        // Hitung denda
        $today   = date('Y-m-d');
        $denda   = 0;
        if ($today > $p->tanggal_kembali) {
            $hari  = (int)ceil((strtotime($today) - strtotime($p->tanggal_kembali)) / 86400);
            $denda = $hari * 1000;
        }

        // Update status peminjaman
        $this->Peminjaman_model->update($id, [
            'status'         => 'Dikembalikan',
            'tanggal_aktual' => $today,
            'denda'          => $denda,
        ]);

        // Tambah stok buku
        $this->Buku_model->tambah_tersedia($p->buku_id);

        // Buat catatan denda jika ada
        if ($denda > 0) {
            $hari = (int)ceil((strtotime($today) - strtotime($p->tanggal_kembali)) / 86400);
            $this->Denda_model->insert([
                'anggota_id'  => $p->anggota_id,
                'pinjam_id'   => $id,
                'jumlah'      => $denda,
                'tanggal'     => $today,
                'status'      => 'Belum Dibayar',
                'keterangan'  => 'Terlambat '.$hari.' hari — Buku: '.$p->judul_buku,
            ]);
            $this->session->set_flashdata('sukses', 'Buku berhasil dikembalikan. Denda terlambat: Rp '.number_format($denda,0,',','.').'.');
        } else {
            $this->session->set_flashdata('sukses', 'Buku berhasil dikembalikan tepat waktu. Tidak ada denda.');
        }
        redirect('peminjaman');
    }

    /* ══════════════════════════════════════════════════════════
     * ADMIN/OPERATOR: Tambah Manual
     * ══════════════════════════════════════════════════════════ */
    public function tambah() {
        $this->cek_role(['admin','operator']);
        $data['title']   = 'Tambah Peminjaman';
        $data['anggota'] = $this->Anggota_model->get_aktif();
        $data['buku']    = $this->Buku_model->get_tersedia();
        $this->render('peminjaman/tambah', $data);
    }

    public function simpan() {
        $this->cek_role(['admin','operator']);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('anggota_id',      'Anggota',         'required|integer');
        $this->form_validation->set_rules('buku_id',         'Buku',            'required|integer');
        $this->form_validation->set_rules('tanggal_pinjam',  'Tanggal Pinjam',  'required');
        $this->form_validation->set_rules('tanggal_kembali', 'Tanggal Kembali', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('<li>','</li>'));
            redirect('peminjaman/tambah'); return;
        }

        $buku_id = $this->input->post('buku_id');
        $buku    = $this->Buku_model->get_by_id($buku_id);
        if (!$buku || $buku->tersedia < 1) {
            $this->session->set_flashdata('error', 'Buku tidak tersedia saat ini.');
            redirect('peminjaman/tambah'); return;
        }

        $this->Peminjaman_model->insert([
            'anggota_id'      => $this->input->post('anggota_id'),
            'buku_id'         => $buku_id,
            'tanggal_pinjam'  => $this->input->post('tanggal_pinjam'),
            'tanggal_kembali' => $this->input->post('tanggal_kembali'),
            'status'          => 'Dipinjam',
            'catatan'         => $this->input->post('catatan'),
        ]);
        $this->Buku_model->kurangi_tersedia($buku_id);
        $this->Anggota_model->increment_pinjam($this->input->post('anggota_id'));
        $this->session->set_flashdata('sukses', 'Peminjaman berhasil dicatat.');
        redirect('peminjaman');
    }

    public function hapus($id) {
        $this->cek_role('admin');
        $p = $this->Peminjaman_model->get_by_id($id);
        if ($p && in_array($p->status, ['Menunggu','Dipinjam','Terlambat','Menunggu Kembali'])) {
            $this->Buku_model->tambah_tersedia($p->buku_id);
        }
        $this->Peminjaman_model->delete($id);
        $this->session->set_flashdata('sukses', 'Data peminjaman berhasil dihapus.');
        redirect('peminjaman');
    }
}
