<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Proteksi: Jika belum login, tendang ke halaman login
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Sesi Anda berakhir, silakan login kembali.');
            redirect('auth');
        }
        // Load model khusus dashboard SIBERES
        $this->load->model('M_dashboard');
    }

    public function index() {
        $data['title'] = "Dashboard " . ucfirst($this->session->userdata('level'));

        // --- DATA STATISTIK PENGADUAN SARANA ---
        $data['total_sarana'] = $this->M_dashboard->get_total_sarana();
        $data['laporan_baru'] = $this->M_dashboard->get_count_pengaduan('0');
        $data['diproses']     = $this->M_dashboard->get_count_pengaduan('proses');
        $data['selesai']      = $this->M_dashboard->get_count_pengaduan('selesai');

        // Load View (Pastikan urutannya Header -> Sidebar -> Konten -> Footer)
        $this->load->view('layout/header', $data);
        $this->load->view('dashboard_v', $data); 
        $this->load->view('layout/footer');
    }
}
