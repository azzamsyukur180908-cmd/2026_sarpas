<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Hanya Admin yang boleh masuk ke Manajemen User
        if ($this->session->userdata('level') != 'admin') {
            redirect('dashboard');
        }
        $this->load->model('M_users');
    }

    // Menampilkan halaman utama
    public function index() {
        $this->load->view('layout/header');
        $this->load->view('v_users');
        $this->load->view('layout/footer');
    }

    // Mengirim data ke DataTables (JSON)
    public function get_data() {
        echo json_encode($this->M_users->get_all());
    }

    // Mengirim 1 data spesifik ke form Edit Modal (JSON)
    public function get_by_id($id) {
        echo json_encode($this->M_users->get_by_id($id));
    }

    // Proses Tambah dan Update
    public function simpan() {
        $id = $this->input->post('id_user');
        $password_input = $this->input->post('password', TRUE);
        
        $data = [
            'nama'     => $this->input->post('nama', TRUE),
            'username' => $this->input->post('username', TRUE),
            'level'    => $this->input->post('level', TRUE)
        ];

        // Jika ID kosong = Tambah User Baru
        if(empty($id)) {
            // Wajib isi password saat tambah baru
            $data['password'] = md5($password_input);
            $this->M_users->insert($data);
            echo json_encode(['status' => 'success', 'pesan' => 'Data user berhasil ditambahkan!']);
        } 
        // Jika ID ada = Update User
        else {
            // Jika password diisi, maka update passwordnya. Jika kosong, biarkan password lama.
            if(!empty($password_input)) {
                $data['password'] = md5($password_input);
            }
            $this->M_users->update($id, $data);
            echo json_encode(['status' => 'success', 'pesan' => 'Data user berhasil diupdate!']);
        }
    }

    // Proses Hapus Data
    public function hapus($id) {
        // Mencegah admin menghapus dirinya sendiri yang sedang login
        if($id == $this->session->userdata('id_user')) {
            echo json_encode(['status' => 'error', 'pesan' => 'Anda tidak bisa menghapus akun Anda sendiri saat sedang login!']);
        } else {
            $this->M_users->delete($id);
            echo json_encode(['status' => 'success', 'pesan' => 'Data user berhasil dihapus!']);
        }
    }
}
