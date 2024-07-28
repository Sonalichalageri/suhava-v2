<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function login($username, $password) {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        if ($query->num_rows() == 1) {
            $user = $query->row();
            // if (password_verify($password, $user->password)) {
            //     return $user;
            // }
            return $user;
        }
        return false;
    }
}
?>
