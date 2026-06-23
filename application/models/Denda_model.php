<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Denda_model extends CI_Model {

    protected $table = 'denda';

    private function _base_query() {
        return $this->db
            ->select('denda.*, anggota.nama AS nama_anggota, anggota.nim')
            ->from($this->table)
            ->join('anggota', 'anggota.id = denda.anggota_id');
    }

    public function get_all($search = '') {
        $this->_base_query();
        if ($search) {
            $this->db->group_start();
            $this->db->like('anggota.nama',  $search);
            $this->db->or_like('denda.keterangan', $search);
            $this->db->group_end();
        }
        return $this->db->order_by('denda.id', 'DESC')->get()->result();
    }

    public function get_by_anggota($anggota_id) {
        $this->_base_query();
        return $this->db->where('denda.anggota_id', $anggota_id)
                        ->order_by('denda.id', 'DESC')->get()->result();
    }

    public function get_by_id($id) {
        $this->_base_query();
        return $this->db->where('denda.id', $id)->get()->row();
    }

    public function insert($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function bayar($id) {
        return $this->db->where('id', $id)->update($this->table, [
            'status'     => 'Lunas',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function delete($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function total_belum_bayar($anggota_id = null) {
        if ($anggota_id) $this->db->where('anggota_id', $anggota_id);
        $row = $this->db->select_sum('jumlah')->where('status','Belum Dibayar')->get($this->table)->row();
        return $row ? (int)$row->jumlah : 0;
    }

    public function count_belum_bayar($anggota_id = null) {
        if ($anggota_id) $this->db->where('anggota_id', $anggota_id);
        return $this->db->where('status','Belum Dibayar')->count_all_results($this->table);
    }
}
