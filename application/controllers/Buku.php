<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buku extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->cek_login();
        $this->load->model(['Buku_model','Kategori_model']);
    }

    public function index() {
        $search = $this->input->get('search');
        $data['title']  = 'Data Buku';
        $data['buku']   = $this->Buku_model->get_all($search);
        $data['search'] = $search;
        $this->render('buku/index', $data);
    }

    public function tambah() {
        $this->cek_role(['admin','operator']);
        $data['title']         = 'Tambah Buku';
        $data['kategori_list'] = $this->Kategori_model->get_list_nama();
        $this->render('buku/tambah', $data);
    }

    public function simpan() {
        $this->cek_role(['admin','operator']);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('judul',   'Judul',   'required|trim');
        $this->form_validation->set_rules('penulis', 'Penulis', 'required|trim');
        $this->form_validation->set_rules('stok',    'Stok',    'required|integer|greater_than_equal_to[0]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('<li>','</li>'));
            redirect('buku/tambah');
            return;
        }
        $stok = (int)$this->input->post('stok');
        $data = [
            'judul'    => $this->input->post('judul'),
            'penulis'  => $this->input->post('penulis'),
            'isbn'     => $this->input->post('isbn'),
            'kategori' => $this->input->post('kategori'),
            'tahun'    => $this->input->post('tahun'),
            'stok'     => $stok,
            'tersedia' => $stok,
        ];
        $this->Buku_model->insert($data);
        $this->session->set_flashdata('sukses', 'Buku berhasil ditambahkan.');
        redirect('buku');
    }

    public function edit($id) {
        $this->cek_role(['admin','operator']);
        $data['title']         = 'Edit Buku';
        $data['buku']          = $this->Buku_model->get_by_id($id);
        $data['kategori_list'] = $this->Kategori_model->get_list_nama();
        if (!$data['buku']) { show_404(); return; }
        $this->render('buku/edit', $data);
    }

    public function update($id) {
        $this->cek_role(['admin','operator']);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('judul',   'Judul',   'required|trim');
        $this->form_validation->set_rules('penulis', 'Penulis', 'required|trim');
        $this->form_validation->set_rules('stok',    'Stok',    'required|integer|greater_than_equal_to[0]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('<li>','</li>'));
            redirect('buku/edit/'.$id);
            return;
        }
        $buku_lama = $this->Buku_model->get_by_id($id);
        $stok_baru = (int)$this->input->post('stok');
        $selisih   = $stok_baru - (int)$buku_lama->stok;
        $data = [
            'judul'    => $this->input->post('judul'),
            'penulis'  => $this->input->post('penulis'),
            'isbn'     => $this->input->post('isbn'),
            'kategori' => $this->input->post('kategori'),
            'tahun'    => $this->input->post('tahun'),
            'stok'     => $stok_baru,
            'tersedia' => max(0, (int)$buku_lama->tersedia + $selisih),
        ];
        $this->Buku_model->update($id, $data);
        $this->session->set_flashdata('sukses', 'Buku berhasil diperbarui.');
        redirect('buku');
    }

    public function hapus($id) {
        $this->cek_role(['admin','operator']);
        $this->Buku_model->delete($id);
        $this->session->set_flashdata('sukses', 'Buku berhasil dihapus.');
        redirect('buku');
    }
}
