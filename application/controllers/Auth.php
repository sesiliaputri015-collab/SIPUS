<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    /** Redirect jika sudah login */
    private function _redirect_if_logged()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
    }

    /** GET: Halaman login */
    public function login()
    {
        $this->_redirect_if_logged();
        $data['title'] = 'Login — SIPUS';
        $this->load->view('auth/login', $data);
    }

    /** POST: Proses login */
    public function proses_login()
    {
        $this->_redirect_if_logged();

        $username = trim($this->input->post('username'));
        $password = $this->input->post('password');

        if (empty($username) || empty($password)) {
            $this->session->set_flashdata('error', 'Username dan password wajib diisi.');
            redirect('auth/login');
            return;
        }

        $user = $this->User_model->get_by_username($username);

        if (!$user || !password_verify($password, $user->password)) {
            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('auth/login');
            return;
        }

        // Set session
        $this->session->set_userdata([
            'logged_in' => TRUE,
            'id_user' => $user->id_user,
            'nama' => $user->nama,
            'username' => $user->username,
            'role' => $user->role,
            'id_anggota' => $user->id_anggota,
        ]);

        redirect('dashboard');
    }

    /** Logout */
    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('sukses', 'Anda telah berhasil logout.');
        redirect('auth/login');
    }

    /**
     * HANYA UNTUK DEVELOPMENT
     * Akses: http://localhost/sipus_ci3/auth/reset_hash
     * Mereset password semua akun default agar bisa login.
     */
    public function reset_hash()
    {
        if (ENVIRONMENT !== 'development') {
            show_404();
            return;
        }
        $accounts = [
            ['username' => 'admin', 'password' => 'admin123'],
            ['username' => 'operator', 'password' => 'operator123'],
            ['username' => 'anggota', 'password' => 'anggota123'],
        ];
        foreach ($accounts as $acc) {
            $hash = password_hash($acc['password'], PASSWORD_BCRYPT);
            $this->db->where('username', $acc['username'])
                ->update('users', ['password' => $hash]);
        }
        echo '<pre style="font-family:monospace;padding:2rem">';
        echo '<b>✅ Password berhasil direset:</b>' . PHP_EOL . PHP_EOL;
        foreach ($accounts as $acc) {
            echo "  username: {$acc['username']}  |  password: {$acc['password']}" . PHP_EOL;
        }
        echo PHP_EOL . '<a href="' . base_url('auth/login') . '">→ Ke halaman Login</a>';
        echo '</pre>';
    }
}