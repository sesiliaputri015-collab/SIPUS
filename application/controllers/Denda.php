<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Denda extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->cek_login();
        $this->load->model(['Denda_model', 'Anggota_model']);
    }

    public function index()
    {
        $search = $this->input->get('search');
        if ($this->role === 'anggota') {
            $id_anggota = $this->session->userdata('id_anggota');
            $data['denda'] = $this->Denda_model->get_by_anggota($id_anggota);
            $data['title'] = 'Denda Saya';
        } else {
            $this->cek_role(['admin', 'operator']);
            $data['denda'] = $this->Denda_model->get_all($search);
            $data['title'] = 'Data Denda';
            $data['search'] = $search;
        }
        $this->render('denda/index', $data);
    }

    public function tambah()
    {
        $this->cek_role(['admin', 'operator']);
        $data['title'] = 'Tambah Denda';
        $data['anggota'] = $this->Anggota_model->get_aktif();
        $this->render('denda/tambah', $data);
    }

    public function simpan()
    {
        $this->cek_role(['admin', 'operator']);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('anggota_id', 'Anggota', 'required|integer');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('<li>', '</li>'));
            redirect('denda/tambah');
            return;
        }
        $this->Denda_model->insert([
            'anggota_id' => $this->input->post('anggota_id'),
            'jumlah' => $this->input->post('jumlah'),
            'tanggal' => $this->input->post('tanggal'),
            'status' => 'Belum Dibayar',
            'keterangan' => $this->input->post('keterangan'),
        ]);
        $this->session->set_flashdata('sukses', 'Denda berhasil ditambahkan.');
        redirect('denda');
    }

    public function edit($id)
    {
        $this->cek_role(['admin', 'operator']);
        $data['title'] = 'Edit Denda';
        $data['denda'] = $this->Denda_model->get_by_id($id);
        $data['anggota'] = $this->Anggota_model->get_aktif();
        if (!$data['denda']) {
            show_404();
            return;
        }
        $this->render('denda/edit', $data);
    }

    public function update($id)
    {
        $this->cek_role(['admin', 'operator']);
        $this->Denda_model->update($id, [
            'jumlah' => $this->input->post('jumlah'),
            'tanggal' => $this->input->post('tanggal'),
            'status' => $this->input->post('status'),
            'keterangan' => $this->input->post('keterangan'),
        ]);
        $this->session->set_flashdata('sukses', 'Denda berhasil diperbarui.');
        redirect('denda');
    }

    public function bayar($id)
    {
        $this->cek_role(['admin', 'operator']);
        $this->Denda_model->bayar($id);
        $this->session->set_flashdata('sukses', 'Denda berhasil ditandai Lunas.');
        redirect('denda');
    }

    public function hapus($id)
    {
        $this->cek_role('admin');
        $this->Denda_model->delete($id);
        $this->session->set_flashdata('sukses', 'Data denda berhasil dihapus.');
        redirect('denda');
    }

}
