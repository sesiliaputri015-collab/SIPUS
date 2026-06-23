<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->cek_login();
        $this->load->model(['User_model','Anggota_model']);
    }

    public function index() {
        $id = $this->user['id_user'];
        $data['title']   = 'Profil Saya';
        $data['profil']  = $this->User_model->get_by_id($id);
        $data['anggota'] = $this->role === 'anggota'
            ? $this->Anggota_model->get_by_id($this->user['id_anggota'])
            : null;
        $this->render('profil/index', $data);
    }

    public function update() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('<li>','</li>'));
            redirect('profil'); return;
        }

        $pass_baru = $this->input->post('password_baru');
        $pass_lama = $this->input->post('password_lama');

        if (!empty($pass_baru)) {
            $user = $this->User_model->get_by_id($this->user['id_user']);
            if (!password_verify($pass_lama, $user->password)) {
                $this->session->set_flashdata('error', 'Password lama tidak cocok.');
                redirect('profil'); return;
            }
            if (strlen($pass_baru) < 6) {
                $this->session->set_flashdata('error', 'Password baru minimal 6 karakter.');
                redirect('profil'); return;
            }
        }

        $data_update = ['nama' => $this->input->post('nama')];
        if (!empty($pass_baru)) $data_update['password'] = $pass_baru;

        $this->User_model->update_profil($this->user['id_user'], $data_update);

        // Update session nama
        $this->session->set_userdata('nama', $this->input->post('nama'));

        // Jika anggota, update data anggota juga
        if ($this->role === 'anggota' && $this->user['id_anggota']) {
            $this->Anggota_model->update($this->user['id_anggota'], [
                'nama'  => $this->input->post('nama'),
                'email' => $this->input->post('email'),
            ]);
        }

        $this->session->set_flashdata('sukses', 'Profil berhasil diperbarui.');
        redirect('profil');
    }
}
