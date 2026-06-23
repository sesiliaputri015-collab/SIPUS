<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anggota_model extends CI_Model {

    protected $table = 'anggota';

    public function get_all($search = '') {
        if ($search) {
            $this->db->group_start();
            $this->db->like('nama',  $search);
            $this->db->or_like('nim',   $search);
            $this->db->or_like('prodi', $search);
            $this->db->or_like('email', $search);
            $this->db->group_end();
        }
        return $this->db->order_by('id', 'DESC')->get($this->table)->result();
    }

    public function get_aktif() {
        return $this->db->where('status', 'Aktif')->order_by('nama')->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function insert($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    public function get_insert_id() {
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function increment_pinjam($id) {
        return $this->db->where('id', $id)->set('total_pinjam', 'total_pinjam + 1', FALSE)->update($this->table);
    }

    public function nim_exists($nim, $exclude_id = null) {
        $this->db->where('nim', $nim);
        if ($exclude_id) $this->db->where('id !=', $exclude_id);
        return $this->db->count_all_results($this->table) > 0;
    }

    public function count_aktif() {
        return $this->db->where('status', 'Aktif')->count_all_results($this->table);
    }
}
