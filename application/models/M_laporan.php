<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_laporan extends CI_Model {

    public function get_laporan($tgl_awal = null, $tgl_akhir = null, $status = null) {
        $this->db->select('
            p.id_pengaduan, p.tgl_pengaduan, p.deskripsi_laporan, p.status,
            u.nama as nama_pelapor, 
            s.nama_sarana, s.lokasi,
            t.tanggapan, t.tgl_tanggapan,
            pt.nama as nama_petugas
        ');
        $this->db->from('pengaduan p');
        $this->db->join('users u', 'u.id_user = p.id_user', 'left'); // Info Pelapor
        $this->db->join('sarana s', 's.id_sarana = p.id_sarana', 'left'); // Info Sarana
        $this->db->join('tanggapan t', 't.id_pengaduan = p.id_pengaduan', 'left'); // Info Tanggapan
        $this->db->join('users pt', 'pt.id_user = t.id_user', 'left'); // Info Petugas yang menanggapi

        // Filter Tanggal
        if ($tgl_awal && $tgl_akhir) {
            $this->db->where('p.tgl_pengaduan >=', $tgl_awal);
            $this->db->where('p.tgl_pengaduan <=', $tgl_akhir);
        }
        
        // Filter Status
        if ($status && $status != 'semua') {
            $this->db->where('p.status', $status);
        }

        $this->db->order_by('p.tgl_pengaduan', 'DESC');
        return $this->db->get()->result();
    }
}
