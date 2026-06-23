<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->cek_login();
        $this->cek_role('admin');
        $this->load->model('Kategori_model');
    }

    public function index() {
        $data['title']    = 'Kategori Buku';
        $data['kategori'] = $this->Kategori_model->get_all();
        $this->render('kategori/index', $data);
    }

    public function tambah() {
        $data['title'] = 'Tambah Kategori';
        $this->render('kategori/tambah', $data);
    }

    public function simpan() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama', 'Nama Kategori', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('<li>','</li>'));
            redirect('kategori/tambah'); return;
        }
        $this->Kategori_model->insert([
            'nama'      => $this->input->post('nama'),
            'deskripsi' => $this->input->post('deskripsi'),
            'ikon'      => $this->input->post('ikon') ?: 'menu_book',
            'warna'     => $this->input->post('warna') ?: 'blue',
        ]);
        $this->session->set_flashdata('sukses', 'Kategori berhasil ditambahkan.');
        redirect('kategori');
    }

    public function edit($id) {
        $data['title']    = 'Edit Kategori';
        $data['kategori'] = $this->Kategori_model->get_by_id($id);
        if (!$data['kategori']) { show_404(); return; }
        $this->render('kategori/edit', $data);
    }

    public function update($id) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama', 'Nama Kategori', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('<li>','</li>'));
            redirect('kategori/edit/'.$id); return;
        }
        $this->Kategori_model->update($id, [
            'nama'      => $this->input->post('nama'),
            'deskripsi' => $this->input->post('deskripsi'),
            'ikon'      => $this->input->post('ikon') ?: 'menu_book',
            'warna'     => $this->input->post('warna') ?: 'blue',
        ]);
        $this->session->set_flashdata('sukses', 'Kategori berhasil diperbarui.');
        redirect('kategori');
    }

    public function hapus($id) {
        $jumlah = $this->Kategori_model->count_buku($this->Kategori_model->get_by_id($id)->nama);
        if ($jumlah > 0) {
            $this->session->set_flashdata('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh '.$jumlah.' buku.');
            redirect('kategori'); return;
        }
        $this->Kategori_model->delete($id);
        $this->session->set_flashdata('sukses', 'Kategori berhasil dihapus.');
        redirect('kategori');
    }
}
