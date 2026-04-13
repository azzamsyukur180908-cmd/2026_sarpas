<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_dashboard extends CI_Model {

    // Menghitung total data sarana yang ada di sekolah
    public function get_total_sarana() {
        return $this->db->count_all('sarana');
    }

    // Menghitung jumlah laporan berdasarkan status (0 = baru, proses, selesai)
    public function get_count_pengaduan($status) {
        $this->db->where('status', $status);
        return $this->db->get('pengaduan')->num_rows();
    }

}
