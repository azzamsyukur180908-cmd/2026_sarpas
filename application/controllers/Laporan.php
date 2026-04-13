<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Proteksi: Hanya Admin yang bisa mencetak rekap laporan
        if ($this->session->userdata('level') != 'admin') {
            redirect('dashboard');
        }
        $this->load->model('M_laporan');
    }

    public function index() {
        // Menangkap data dari form filter (jika ada)
        $tgl_awal  = $this->input->post('tgl_awal');
        $tgl_akhir = $this->input->post('tgl_akhir');
        $status    = $this->input->post('status');

        // Set default filter: Tampilkan laporan bulan ini jika form kosong
        if (empty($tgl_awal) || empty($tgl_akhir)) {
            $tgl_awal  = date('Y-m-01'); // Tanggal 1 bulan ini
            $tgl_akhir = date('Y-m-t');  // Tanggal akhir bulan ini
            $status    = 'semua';
        }

        // Simpan variabel untuk ditampilkan di View agar form tidak reset
        $data['tgl_awal']  = $tgl_awal;
        $data['tgl_akhir'] = $tgl_akhir;
        $data['status']    = $status;

        // Ambil data laporan dari Model
        $data['laporan'] = $this->M_laporan->get_laporan($tgl_awal, $tgl_akhir, $status);

        $this->load->view('layout/header');
        $this->load->view('v_laporan', $data);
        $this->load->view('layout/footer');
    }
}
