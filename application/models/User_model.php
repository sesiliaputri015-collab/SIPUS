<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    protected $table = 'users';

    public function get_all($search = '', $role = '') {
        if ($search) {
            $this->db->group_start();
            $this->db->like('nama', $search);
            $this->db->or_like('username', $search);
            $this->db->group_end();
        }
        if ($role) $this->db->where('role', $role);
        return $this->db->order_by('id_user', 'DESC')->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id_user' => $id])->row();
    }

    public function get_by_username($username) {
        return $this->db->get_where($this->table, ['username' => $username, 'status' => 'aktif'])->row();
    }

    public function insert($data) {
        $data['password']   = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']);
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id_user', $id)->update($this->table, $data);
    }

    public function delete($id) {
        return $this->db->where('id_user', $id)->delete($this->table);
    }

    public function username_exists($username, $exclude_id = null) {
        $this->db->where('username', $username);
        if ($exclude_id) $this->db->where('id_user !=', $exclude_id);
        return $this->db->count_all_results($this->table) > 0;
    }

    public function count_by_role($role) {
        return $this->db->where('role', $role)->where('status','aktif')->count_all_results($this->table);
    }

    public function update_profil($id, $data) {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']);
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id_user', $id)->update($this->table, $data);
    }
}
