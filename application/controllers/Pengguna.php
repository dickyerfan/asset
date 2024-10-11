<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengguna extends CI_Controller
{

    public function index()
    {
        if ($this->session->userdata('level') != 'Pengguna') {
            redirect('auth');
        }
        $data['title'] = 'Halaman Pengguna';
        $this->load->view('pengguna/view_pengguna', $data);
    }
}
