<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->cek_login();
        $this->load->model(['Buku_model','Anggota_model','Peminjaman_model','Denda_model','Kategori_model','User_model']);
        // Update status terlambat setiap kali dashboard dibuka
        $this->Peminjaman_model->update_status_terlambat();
    }

    public function index() {
        switch ($this->role) {
            case 'admin':    $this->_dashboard_admin();    break;
            case 'operator': $this->_dashboard_operator(); break;
            case 'anggota':  $this->_dashboard_anggota();  break;
            default: redirect('auth/logout');
        }
    }

    /* ── ADMIN DASHBOARD ─────────────────────────────────── */
    private function _dashboard_admin() {
        $data['title']            = 'Dashboard Admin';
        $data['total_buku']       = $this->Buku_model->count_all();
        $data['buku_tersedia']    = $this->Buku_model->count_tersedia();
        $data['anggota_aktif']    = $this->Anggota_model->count_aktif();
        $data['pinjam_aktif']     = $this->Peminjaman_model->count_aktif();
        $data['pinjam_menunggu']  = $this->Peminjaman_model->count_menunggu();
        $data['pinjam_terlambat'] = $this->Peminjaman_model->count_terlambat();
        $data['total_denda']      = $this->Denda_model->total_belum_bayar();
        $data['tagihan_denda']    = $this->Denda_model->count_belum_bayar();
        $data['total_admin']      = $this->User_model->count_by_role('admin');
        $data['total_operator']   = $this->User_model->count_by_role('operator');
        $data['total_user_anggota']= $this->User_model->count_by_role('anggota');

        // Distribusi kategori
        $data['kategori_list']    = $this->Kategori_model->get_all();
        $distrib = [];
        foreach ($data['kategori_list'] as $k) {
            $distrib[$k->nama] = $this->Kategori_model->count_buku($k->nama);
        }
        $data['kategori_distrib'] = $distrib;

        // Peminjaman menunggu persetujuan
        $data['pinjam_menunggu_list']      = $this->Peminjaman_model->get_all('','menunggu');
        $data['pinjam_menunggu_kembali']   = $this->Peminjaman_model->count_menunggu_kembali();

        $this->render('dashboard/admin', $data);
    }

    /* ── OPERATOR DASHBOARD ──────────────────────────────── */
    private function _dashboard_operator() {
        $data['title']                   = 'Dashboard Operator';
        $data['total_buku']              = $this->Buku_model->count_all();
        $data['buku_tersedia']           = $this->Buku_model->count_tersedia();
        $data['anggota_aktif']           = $this->Anggota_model->count_aktif();
        $data['pinjam_aktif']            = $this->Peminjaman_model->count_aktif();
        $data['pinjam_menunggu']         = $this->Peminjaman_model->count_menunggu();
        $data['pinjam_menunggu_kembali'] = $this->Peminjaman_model->count_menunggu_kembali();
        $data['pinjam_terlambat']        = $this->Peminjaman_model->count_terlambat();
        $data['total_denda']             = $this->Denda_model->total_belum_bayar();

        // Antrian yang perlu diproses operator
        $data['antrian_pinjam']  = $this->Peminjaman_model->get_all('','menunggu');
        $data['antrian_kembali'] = $this->Peminjaman_model->get_all('','menunggu_kembali');
        $data['pinjam_terlambat_list'] = $this->Peminjaman_model->get_all('','terlambat');

        $this->render('dashboard/operator', $data);
    }

    /* ── ANGGOTA DASHBOARD ───────────────────────────────── */
    private function _dashboard_anggota() {
        $id_anggota = $this->session->userdata('id_anggota');
        $data['title']        = 'Dashboard Saya';
        $data['anggota']      = $this->Anggota_model->get_by_id($id_anggota);
        $data['pinjam_aktif'] = $this->Peminjaman_model->get_by_anggota($id_anggota, 'dipinjam');
        $data['menunggu']     = $this->Peminjaman_model->get_by_anggota($id_anggota, 'menunggu');
        $data['terlambat']    = $this->Peminjaman_model->get_by_anggota($id_anggota, 'terlambat');
        $data['denda_aktif']  = $this->Denda_model->get_by_anggota($id_anggota);
        $data['total_denda']  = $this->Denda_model->total_belum_bayar($id_anggota);
        $data['katalog']      = $this->Buku_model->get_tersedia('');

        $this->render('dashboard/anggota', $data);
    }
}
