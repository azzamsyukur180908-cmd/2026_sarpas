<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaduan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Proteksi login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        $this->load->model('M_pengaduan');
    }

    // ===========================================
    // MODUL: LAPORAN MASUK (Khusus Admin/Petugas)
    // ===========================================
    public function masuk() {
        if ($this->session->userdata('level') == 'pelapor') {
            redirect('dashboard');
        }
        
        $this->load->view('layout/header');
        $this->load->view('v_pengaduan_masuk');
        $this->load->view('layout/footer');
    }

    // Data API untuk tabel Laporan Masuk (Status '0')
    public function get_data_masuk() {
        echo json_encode($this->M_pengaduan->get_by_status('0'));
    }

    // Mengambil Detail Laporan (Untuk Modal View)
    public function get_detail($id) {
        echo json_encode($this->M_pengaduan->get_by_id($id));
    }

    // Proses pindah status dari '0' (Masuk) menjadi 'proses'
    public function proses_laporan($id) {
        $this->M_pengaduan->update_status($id, 'proses');
        echo json_encode(['status' => 'success', 'pesan' => 'Laporan berhasil diproses dan dipindahkan ke menu Laporan Diproses!']);
    }
	// ===========================================
    // MODUL: LAPORAN DIPROSES (Khusus Admin/Petugas)
    // ===========================================
    public function proses() {
        if ($this->session->userdata('level') == 'pelapor') {
            redirect('dashboard');
        }
        
        $this->load->view('layout/header');
        $this->load->view('v_pengaduan_proses');
        $this->load->view('layout/footer');
    }

    // Data API untuk tabel Laporan Diproses (Status 'proses')
    public function get_data_proses() {
        echo json_encode($this->M_pengaduan->get_by_status('proses'));
    }

    // Proses pindah status menjadi 'selesai' beserta tanggapan
    public function selesai_laporan() {
        $id_pengaduan = $this->input->post('id_pengaduan');
        $tanggapan_teks = $this->input->post('tanggapan', TRUE);

        // 1. Update status di tabel pengaduan
        $this->M_pengaduan->update_status($id_pengaduan, 'selesai');

        // 2. Insert data ke tabel tanggapan
        $data_tanggapan = [
            'id_pengaduan'  => $id_pengaduan,
            'tgl_tanggapan' => date('Y-m-d'),
            'tanggapan'     => $tanggapan_teks,
            'id_user'       => $this->session->userdata('id_user') // Petugas yang menanggapi
        ];
        $this->M_pengaduan->insert_tanggapan($data_tanggapan);

        echo json_encode(['status' => 'success', 'pesan' => 'Laporan berhasil diselesaikan dan tanggapan telah disimpan!']);
    }
	// ===========================================
    // MODUL: LAPORAN SELESAI (Khusus Admin/Petugas)
    // ===========================================
    public function selesai() {
        if ($this->session->userdata('level') == 'pelapor') {
            redirect('dashboard');
        }
        
        $this->load->view('layout/header');
        $this->load->view('v_pengaduan_selesai');
        $this->load->view('layout/footer');
    }

    // Data API untuk tabel Laporan Selesai (Status 'selesai')
    public function get_data_selesai() {
        echo json_encode($this->M_pengaduan->get_by_status('selesai'));
    }

    // Mengambil Detail Laporan + Tanggapannya
    public function get_detail_selesai($id) {
        // Ambil data laporan
        $laporan = $this->M_pengaduan->get_by_id($id);
        // Ambil data tanggapan
        $tanggapan = $this->M_pengaduan->get_tanggapan($id);
        
        // Gabungkan dalam satu array JSON
        echo json_encode([
            'laporan' => $laporan,
            'tanggapan' => $tanggapan
        ]);
    }
	// ===========================================
    // MODUL: PELAPOR (Siswa / Guru / Staff)
    // ===========================================
    
    // 1. Halaman Form Tambah Laporan
    public function buat() {
        // Ambil data sarana untuk ditampilkan di dropdown pilihan (Select)
        $this->load->model('M_sarana');
        $data['sarana'] = $this->M_sarana->get_all();

        $this->load->view('layout/header');
        $this->load->view('v_pengaduan_tambah', $data);
        $this->load->view('layout/footer');
    }

    // 2. Proses Simpan Laporan (dengan Upload Foto) via AJAX
    // 2. Proses Simpan Laporan (dengan Upload Foto) via AJAX
    public function simpan_pengaduan() {
        $id_sarana = $this->input->post('id_sarana', TRUE);
        $deskripsi = $this->input->post('deskripsi_laporan', TRUE);
        $id_user   = $this->session->userdata('id_user');

        // --- PERBAIKAN: OTOMATIS BUAT FOLDER JIKA BELUM ADA ---
        $upload_path = './assets/img/pengaduan/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, TRUE);
        }
        // ------------------------------------------------------

        // Konfigurasi Upload File
        $config['upload_path']   = $upload_path;              // Folder tujuan
        $config['allowed_types'] = 'gif|jpg|png|jpeg';        // Format diizinkan
        $config['max_size']      = 2048;                      // Maksimal 2 MB
        $config['encrypt_name']  = TRUE;                      // Enkripsi nama file agar tidak bentrok

        $this->load->library('upload', $config);

        $foto = '';
        // Cek apakah user mengupload foto
        if (!empty($_FILES['foto']['name'])) {
            if ($this->upload->do_upload('foto')) {
                $gbr = $this->upload->data();
                $foto = $gbr['file_name'];
            } else {
                // Jika gagal upload (Hapus tag HTML bawaan CI agar SweetAlert rapi)
                $error_msg = strip_tags($this->upload->display_errors());
                echo json_encode(['status' => 'error', 'pesan' => $error_msg]);
                return;
            }
        }

        // Susun data untuk disimpan ke database
        $data = [
            'id_user'           => $id_user,
            'id_sarana'         => $id_sarana,
            'tgl_pengaduan'     => date('Y-m-d'),
            'deskripsi_laporan' => $deskripsi,
            'foto'              => $foto,
            'status'            => '0' // '0' artinya laporan baru masuk
        ];

        $this->M_pengaduan->insert($data);
        echo json_encode(['status' => 'success', 'pesan' => 'Laporan kerusakan berhasil dikirim!']);
    }

    // 3. Halaman Riwayat Laporan Saya
    public function riwayat() {
        $this->load->view('layout/header');
        $this->load->view('v_pengaduan_riwayat');
        $this->load->view('layout/footer');
    }

    // 4. Data API untuk tabel Riwayat Laporan
    public function get_data_riwayat() {
        $id_user = $this->session->userdata('id_user');
        echo json_encode($this->M_pengaduan->get_by_user($id_user));
    }
}
