<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_sarana extends CI_Model {

    // Mengambil semua data sarana
    public function get_all() {
        $this->db->order_by('id_sarana', 'DESC');
        return $this->db->get('sarana')->result();
    }

    // Menyimpan data baru
    public function insert($data) {
        $this->db->insert('sarana', $data);
    }

    // Mengambil data berdasarkan ID (untuk diedit)
    public function get_by_id($id) {
        $this->db->where('id_sarana', $id);
        return $this->db->get('sarana')->row();
    }

    // Mengupdate data
    public function update($id, $data) {
        $this->db->where('id_sarana', $id);
        $this->db->update('sarana', $data);
    }

    // Menghapus data
    public function delete($id) {
        $this->db->where('id_sarana', $id);
        $this->db->delete('sarana');
    }
}
