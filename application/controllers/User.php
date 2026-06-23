<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->cek_login();
        $this->cek_role('admin');
        $this->load->model('User_model');
        $this->load->model('Anggota_model');
    }

    public function index() {
        $search = $this->input->get('search');
        $role   = $this->input->get('role');
        $data['title']  = 'Manajemen User';
        $data['users']  = $this->User_model->get_all($search, $role);
        $data['search'] = $search;
        $data['filter_role'] = $role;
        $this->render('user/index', $data);
    }

    public function tambah() {
        $data['title']        = 'Tambah User';
        $data['anggota_list'] = $this->Anggota_model->get_aktif();
        $this->render('user/tambah', $data);
    }

    public function simpan() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama',     'Nama',     'required|trim');
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('role',     'Role',     'required|in_list[admin,operator,anggota]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('<li>','</li>'));
            redirect('user/tambah');
            return;
        }

        $username = $this->input->post('username');
        if ($this->User_model->username_exists($username)) {
            $this->session->set_flashdata('error', 'Username sudah digunakan, pilih username lain.');
            redirect('user/tambah');
            return;
        }

        $data = [
            'nama'       => $this->input->post('nama'),
            'username'   => $username,
            'password'   => $this->input->post('password'),
            'role'       => $this->input->post('role'),
            'status'     => $this->input->post('status') ?: 'aktif',
            'id_anggota' => $this->input->post('id_anggota') ?: NULL,
        ];
        $this->User_model->insert($data);
        $this->session->set_flashdata('sukses', 'User berhasil ditambahkan.');
        redirect('user');
    }

    public function edit($id) {
        $data['title']        = 'Edit User';
        $data['u']            = $this->User_model->get_by_id($id);
        $data['anggota_list'] = $this->Anggota_model->get_aktif();
        if (!$data['u']) { show_404(); return; }
        $this->render('user/edit', $data);
    }

    public function update($id) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama',   'Nama',   'required|trim');
        $this->form_validation->set_rules('role',   'Role',   'required|in_list[admin,operator,anggota]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('<li>','</li>'));
            redirect('user/edit/'.$id);
            return;
        }

        $username = $this->input->post('username');
        if ($this->User_model->username_exists($username, $id)) {
            $this->session->set_flashdata('error', 'Username sudah digunakan.');
            redirect('user/edit/'.$id);
            return;
        }

        $data = [
            'nama'       => $this->input->post('nama'),
            'username'   => $username,
            'password'   => $this->input->post('password'),
            'role'       => $this->input->post('role'),
            'status'     => $this->input->post('status'),
            'id_anggota' => $this->input->post('id_anggota') ?: NULL,
        ];
        $this->User_model->update($id, $data);
        $this->session->set_flashdata('sukses', 'User berhasil diperbarui.');
        redirect('user');
    }

    public function hapus($id) {
        // Jangan hapus diri sendiri
        if ($id == $this->user['id_user']) {
            $this->session->set_flashdata('error', 'Tidak dapat menghapus akun sendiri.');
            redirect('user');
            return;
        }
        $this->User_model->delete($id);
        $this->session->set_flashdata('sukses', 'User berhasil dihapus.');
        redirect('user');
    }
}
