<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_auth extends CI_Model
{

    public function registrasi()
    {
        $data = [
            'nama_pengguna' => $this->input->post('nama_pengguna', true),
            'nama_lengkap' => $this->input->post('nama_lengkap', true),
            'email' => $this->input->post('email', true),
            'password' => password_hash($this->input->post('password', true), PASSWORD_DEFAULT),
            'level' => $this->input->post('level', true),
        ];

        return $this->db->insert('user', $data);
    }
}
