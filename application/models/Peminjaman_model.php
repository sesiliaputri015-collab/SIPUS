<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman_model extends CI_Model {

    protected $table = 'peminjaman';

    private function _base_query() {
        return $this->db
            ->select('peminjaman.*, anggota.nama AS nama_anggota, anggota.nim,
                      buku.judul AS judul_buku, buku.penulis')
            ->from($this->table)
            ->join('anggota', 'anggota.id = peminjaman.anggota_id')
            ->join('buku',    'buku.id = peminjaman.buku_id');
    }

    public function get_all($search = '', $status = '') {
        $this->_base_query();
        if ($search) {
            $this->db->group_start();
            $this->db->like('anggota.nama',  $search);
            $this->db->or_like('buku.judul', $search);
            $this->db->or_like('anggota.nim',$search);
            $this->db->group_end();
        }
        if ($status && $status !== 'semua') {
            $map = [
                'menunggu'         => 'Menunggu',
                'dipinjam'         => 'Dipinjam',
                'menunggu_kembali' => 'Menunggu Kembali',
                'terlambat'        => 'Terlambat',
                'dikembalikan'     => 'Dikembalikan',
            ];
            if (isset($map[$status])) {
                $this->db->where('peminjaman.status', $map[$status]);
            }
        }
        return $this->db->order_by('peminjaman.id', 'DESC')->get()->result();
    }

    /** Ambil peminjaman milik anggota tertentu */
    public function get_by_anggota($anggota_id, $status = '') {
        $this->_base_query();
        $this->db->where('peminjaman.anggota_id', $anggota_id);
        if ($status && $status !== 'semua') {
            $map = [
                'menunggu'         => 'Menunggu',
                'dipinjam'         => 'Dipinjam',
                'menunggu_kembali' => 'Menunggu Kembali',
                'terlambat'        => 'Terlambat',
                'dikembalikan'     => 'Dikembalikan',
            ];
            if (isset($map[$status])) {
                $this->db->where('peminjaman.status', $map[$status]);
            }
        }
        return $this->db->order_by('peminjaman.id', 'DESC')->get()->result();
    }

    public function get_by_id($id) {
        $this->_base_query();
        return $this->db->where('peminjaman.id', $id)->get()->row();
    }

    public function insert($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    /** Operator: setujui pengajuan peminjaman → status Dipinjam */
    public function setujui($id) {
        return $this->db->where('id', $id)->update($this->table, [
            'status'     => 'Dipinjam',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /** Operator: tolak pengajuan */
    public function tolak($id) {
        return $this->db->where('id', $id)->update($this->table, [
            'status'     => 'Dikembalikan',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /** Operator: verifikasi & kembalikan buku — hitung denda otomatis */
    public function kembalikan($id) {
        $p = $this->get_by_id($id);
        $today = date('Y-m-d');
        $denda = 0;
        if ($today > $p->tanggal_kembali) {
            $selisih = (strtotime($today) - strtotime($p->tanggal_kembali)) / 86400;
            $denda   = (int)$selisih * 1000; // Rp1.000/hari
        }
        return $this->db->where('id', $id)->update($this->table, [
            'status'        => 'Dikembalikan',
            'tanggal_aktual'=> $today,
            'denda'         => $denda,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
    }

    public function delete($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function count_aktif() {
        return $this->db->where_in('status', ['Dipinjam','Terlambat'])
                        ->count_all_results($this->table);
    }

    public function count_menunggu() {
        return $this->db->where('status', 'Menunggu')->count_all_results($this->table);
    }

    public function count_menunggu_kembali() {
        return $this->db->where('status', 'Menunggu Kembali')->count_all_results($this->table);
    }

    public function count_terlambat() {
        return $this->db->where('status', 'Terlambat')->count_all_results($this->table);
    }

    /** Update status menjadi Terlambat jika melewati tenggat */
    public function update_status_terlambat() {
        return $this->db
            ->where('status', 'Dipinjam')
            ->where('tanggal_kembali <', date('Y-m-d'))
            ->update($this->table, ['status' => 'Terlambat', 'updated_at' => date('Y-m-d H:i:s')]);
    }

    public function count_tab($anggota_id = null) {
        $statuses = ['Menunggu','Dipinjam','Menunggu Kembali','Terlambat','Dikembalikan'];
        $counts = ['semua' => 0];
        foreach ($statuses as $s) {
            $q = $this->db->where('status', $s);
            if ($anggota_id) $q = $this->db->where('anggota_id', $anggota_id);
            $n = $this->db->count_all_results($this->table);
            $counts[strtolower(str_replace(' ','_',$s))] = $n;
            $counts['semua'] += $n;
        }
        return $counts;
    }
}
