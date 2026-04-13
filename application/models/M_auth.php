<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_auth extends CI_Model {

    public function cek_login($username, $password) {
        $this->db->where('username', $username);
        $this->db->where('password', md5($password)); // Mencocokkan MD5
        return $this->db->get('users');
    }

}
