<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buku_model extends CI_Model {

    protected $table = 'buku';

    public function get_all($search = '') {
        if ($search) {
            $this->db->group_start();
            $this->db->like('judul',   $search);
            $this->db->or_like('penulis',  $search);
            $this->db->or_like('kategori', $search);
            $this->db->or_like('isbn',     $search);
            $this->db->group_end();
        }
        return $this->db->order_by('id', 'DESC')->get($this->table)->result();
    }

    public function get_tersedia($search = '') {
        $this->db->where('tersedia >', 0);
        if ($search) {
            $this->db->group_start();
            $this->db->like('judul', $search);
            $this->db->or_like('penulis', $search);
            $this->db->or_like('kategori', $search);
            $this->db->group_end();
        }
        return $this->db->order_by('judul')->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
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

    public function kurangi_tersedia($id) {
        return $this->db->where('id', $id)->where('tersedia >', 0)
                        ->set('tersedia', 'tersedia - 1', FALSE)->update($this->table);
    }

    public function tambah_tersedia($id) {
        return $this->db->where('id', $id)
                        ->set('tersedia', 'tersedia + 1', FALSE)->update($this->table);
    }

    public function count_all() {
        return $this->db->count_all($this->table);
    }

    public function count_tersedia() {
        return $this->db->where('tersedia >', 0)->count_all_results($this->table);
    }
}
