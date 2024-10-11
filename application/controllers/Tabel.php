<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tabel extends CI_Controller
{

    public function index()
    {
        $data['title'] = 'Tabel';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('tabel');
        $this->load->view('templates/footer');
    }
}
