<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_auth');
    }

    public function index() {
        // Jika sudah login, langsung lempar ke dashboard
        if($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        // Pastikan nama file view-nya benar (v_login.php)
        $this->load->view('v_login');
    }

    public function login_aksi() {
        $user = $this->input->post('username', TRUE);
        $pass = $this->input->post('password', TRUE);

        $cek = $this->M_auth->cek_login($user, $pass);

        if ($cek->num_rows() > 0) {
            $data = $cek->row();
            
            // PERBAIKAN: Sesuaikan dengan nama kolom di database
            $session_data = [
                'id_user'   => $data->id_user,
                'username'  => $data->username,
                'nama'      => $data->nama,       // Sebelumnya nama_lengkap
                'level'     => $data->level,      // Sebelumnya role
                'logged_in' => TRUE
            ];
            
            $this->session->set_userdata($session_data);

            // TAMPILKAN NOTIFIKASI BERHASIL
            $this->session->set_flashdata('success', 'Selamat Datang, ' . $data->nama . '!');

            // REDIRECT KE DASHBOARD
            redirect('dashboard');

        } else {
            // TAMPILKAN NOTIFIKASI GAGAL
            $this->session->set_flashdata('error', 'Username atau Password Salah!');
            redirect('auth');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        // Beri notifikasi setelah logout
        $this->session->set_flashdata('success', 'Anda telah berhasil keluar sistem.');
        redirect('auth');
    }
}
