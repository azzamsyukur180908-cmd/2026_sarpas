<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_users extends CI_Model {

    // Mengambil semua data user
    public function get_all() {
        $this->db->order_by('id_user', 'DESC');
        return $this->db->get('users')->result();
    }

    // Mengambil data user berdasarkan ID
    public function get_by_id($id) {
        $this->db->where('id_user', $id);
        return $this->db->get('users')->row();
    }

    // Menyimpan data user baru
    public function insert($data) {
        $this->db->insert('users', $data);
    }

    // Mengupdate data user
    public function update($id, $data) {
        $this->db->where('id_user', $id);
        $this->db->update('users', $data);
    }

    // Menghapus data user
    public function delete($id) {
        $this->db->where('id_user', $id);
        $this->db->delete('users');
    }
}
