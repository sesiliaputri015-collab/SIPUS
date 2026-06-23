<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anggota extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->cek_login();
        $this->cek_role(['admin','operator']);
        $this->load->model('Anggota_model');
    }

    public function index() {
        $search = $this->input->get('search');
        $data['title']   = 'Data Anggota';
        $data['anggota'] = $this->Anggota_model->get_all($search);
        $data['search']  = $search;
        $this->render('anggota/index', $data);
    }

    public function tambah() {
        $data['title'] = 'Tambah Anggota';
        $this->render('anggota/tambah', $data);
    }

    public function simpan() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama', 'Nama',  'required|trim');
        $this->form_validation->set_rules('nim',  'NIM',   'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('<li>','</li>'));
            redirect('anggota/tambah'); return;
        }
        if ($this->Anggota_model->nim_exists($this->input->post('nim'))) {
            $this->session->set_flashdata('error', 'NIM sudah terdaftar.');
            redirect('anggota/tambah'); return;
        }
        $this->Anggota_model->insert([
            'nama'     => $this->input->post('nama'),
            'nim'      => $this->input->post('nim'),
            'email'    => $this->input->post('email'),
            'prodi'    => $this->input->post('prodi'),
            'angkatan' => $this->input->post('angkatan'),
            'status'   => $this->input->post('status') ?: 'Aktif',
        ]);
        $this->session->set_flashdata('sukses', 'Anggota berhasil ditambahkan.');
        redirect('anggota');
    }

    public function edit($id) {
        $data['title']   = 'Edit Anggota';
        $data['anggota'] = $this->Anggota_model->get_by_id($id);
        if (!$data['anggota']) { show_404(); return; }
        $this->render('anggota/edit', $data);
    }

    public function update($id) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('nim',  'NIM',  'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('<li>','</li>'));
            redirect('anggota/edit/'.$id); return;
        }
        if ($this->Anggota_model->nim_exists($this->input->post('nim'), $id)) {
            $this->session->set_flashdata('error', 'NIM sudah terdaftar.');
            redirect('anggota/edit/'.$id); return;
        }
        $this->Anggota_model->update($id, [
            'nama'     => $this->input->post('nama'),
            'nim'      => $this->input->post('nim'),
            'email'    => $this->input->post('email'),
            'prodi'    => $this->input->post('prodi'),
            'angkatan' => $this->input->post('angkatan'),
            'status'   => $this->input->post('status'),
        ]);
        $this->session->set_flashdata('sukses', 'Anggota berhasil diperbarui.');
        redirect('anggota');
    }

    public function hapus($id) {
        $this->cek_role('admin');
        $this->Anggota_model->delete($id);
        $this->session->set_flashdata('sukses', 'Anggota berhasil dihapus.');
        redirect('anggota');
    }
}
