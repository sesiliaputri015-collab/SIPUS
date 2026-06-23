<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_model extends CI_Model {

    protected $table = 'kategori';

    public function get_all() {
        return $this->db->order_by('nama')->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_list_nama() {
        $rows = $this->db->select('nama')->order_by('nama')->get($this->table)->result();
        $list = [];
        foreach ($rows as $r) $list[$r->nama] = $r->nama;
        return $list;
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

    public function delete($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function count_buku($nama) {
        return $this->db->where('kategori', $nama)->count_all_results('buku');
    }
}
