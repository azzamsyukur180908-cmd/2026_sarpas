<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pengaduan extends CI_Model {

    // Mengambil data berdasarkan status (0 = masuk, proses, selesai)
    public function get_by_status($status) {
        $this->db->select('pengaduan.*, users.nama as nama_pelapor, sarana.nama_sarana, sarana.lokasi');
        $this->db->from('pengaduan');
        $this->db->join('users', 'users.id_user = pengaduan.id_user', 'left');
        $this->db->join('sarana', 'sarana.id_sarana = pengaduan.id_sarana', 'left');
        $this->db->where('pengaduan.status', $status);
        $this->db->order_by('pengaduan.id_pengaduan', 'DESC');
        return $this->db->get()->result();
    }

    // Mengambil detail 1 laporan berdasarkan ID
    public function get_by_id($id) {
        $this->db->select('pengaduan.*, users.nama as nama_pelapor, sarana.nama_sarana, sarana.lokasi');
        $this->db->from('pengaduan');
        $this->db->join('users', 'users.id_user = pengaduan.id_user', 'left');
        $this->db->join('sarana', 'sarana.id_sarana = pengaduan.id_sarana', 'left');
        $this->db->where('pengaduan.id_pengaduan', $id);
        return $this->db->get()->row();
    }

    // Mengubah status laporan
    public function update_status($id, $status) {
        $this->db->where('id_pengaduan', $id);
        $this->db->update('pengaduan', ['status' => $status]);
    }
	// Menyimpan tanggapan/tindakan yang dilakukan petugas
    public function insert_tanggapan($data) {
        $this->db->insert('tanggapan', $data);
    }
	// Mengambil detail tanggapan berdasarkan ID Pengaduan
    public function get_tanggapan($id_pengaduan) {
        $this->db->select('tanggapan.*, users.nama as nama_petugas');
        $this->db->from('tanggapan');
        // Join ke tabel users untuk mengetahui siapa petugas yang menanggapi
        $this->db->join('users', 'users.id_user = tanggapan.id_user', 'left');
        $this->db->where('id_pengaduan', $id_pengaduan);
        return $this->db->get()->row();
    }
	// Menyimpan pengaduan baru dari pelapor
    public function insert($data) {
        $this->db->insert('pengaduan', $data);
    }

    // Mengambil riwayat pengaduan khusus milik user yang sedang login
    public function get_by_user($id_user) {
        $this->db->select('pengaduan.*, sarana.nama_sarana, sarana.lokasi');
        $this->db->from('pengaduan');
        $this->db->join('sarana', 'sarana.id_sarana = pengaduan.id_sarana', 'left');
        $this->db->where('pengaduan.id_user', $id_user);
        $this->db->order_by('pengaduan.id_pengaduan', 'DESC');
        return $this->db->get()->result();
    }
}
