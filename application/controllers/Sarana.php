<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sarana extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('level') != 'admin') {
            redirect('dashboard');
        }
        $this->load->model('M_sarana');
    }

    // 1. Menampilkan Halaman Utama
    public function index() {
        $this->load->view('layout/header');
        $this->load->view('v_sarana'); // Tidak perlu melempar $data dari sini lagi
        $this->load->view('layout/footer');
    }

    // 2. Mengambil semua data untuk DataTables (Format JSON)
    public function get_data() {
        $data = $this->M_sarana->get_all();
        echo json_encode($data);
    }

    // 3. Mengambil 1 data berdasarkan ID untuk form Edit Modal (Format JSON)
    public function get_by_id($id) {
        $data = $this->M_sarana->get_by_id($id);
        echo json_encode($data);
    }

    // 4. Proses Tambah & Update Data via AJAX
    public function simpan() {
        $id = $this->input->post('id_sarana');
        
        $data = [
            'nama_sarana'      => $this->input->post('nama_sarana', TRUE),
            'lokasi'           => $this->input->post('lokasi', TRUE),
            'kondisi_saat_ini' => $this->input->post('kondisi', TRUE)
        ];

        // Jika ID kosong = Tambah Data Baru
        if(empty($id)) {
            $this->M_sarana->insert($data);
            echo json_encode(['status' => 'success', 'pesan' => 'Data sarana berhasil ditambahkan!']);
        } 
        // Jika ID ada = Update Data
        else {
            $this->M_sarana->update($id, $data);
            echo json_encode(['status' => 'success', 'pesan' => 'Data sarana berhasil diupdate!']);
        }
    }

    // 5. Proses Hapus Data via AJAX
    public function hapus($id) {
        $this->M_sarana->delete($id);
        echo json_encode(['status' => 'success', 'pesan' => 'Data sarana berhasil dihapus!']);
    }
}
