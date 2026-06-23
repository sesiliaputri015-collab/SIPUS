<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Controller — Base Controller SIPUS Multi-User
 * Semua controller protected mewarisi class ini.
 */
class MY_Controller extends CI_Controller {

    protected $role;
    protected $user;

    public function __construct() {
        parent::__construct();
        $this->role = $this->session->userdata('role');
        $this->user = [
            'id_user'    => $this->session->userdata('id_user'),
            'nama'       => $this->session->userdata('nama'),
            'username'   => $this->session->userdata('username'),
            'role'       => $this->session->userdata('role'),
            'id_anggota' => $this->session->userdata('id_anggota'),
        ];
    }

    /** Paksa login — redirect ke login jika belum ada session */
    protected function cek_login() {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu.');
            redirect('auth/login');
        }
    }

    /**
     * Cek role — redirect ke dashboard jika role tidak diizinkan
     * @param array|string $roles  Misal: ['admin'] atau ['admin','operator']
     */
    protected function cek_role($roles) {
        if (!is_array($roles)) $roles = [$roles];
        if (!in_array($this->role, $roles)) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            redirect('dashboard');
        }
    }

    /**
     * Render view dengan layout penuh (header + sidebar + content + footer)
     */
    protected function render($view, $data = []) {
        $data['user']  = (object) $this->user;
        $data['role']  = $this->role;
        $data['title'] = isset($data['title']) ? $data['title'] : 'SIPUS';
        $this->load->view('layouts/header',  $data);
        $this->load->view('layouts/sidebar', $data);
        $this->load->view($view,             $data);
        $this->load->view('layouts/footer',  $data);
    }
}
