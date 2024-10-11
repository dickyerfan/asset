<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Chart extends CI_Controller
{

    public function index()
    {
        $data['title'] = 'Chart';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('chart');
        $this->load->view('templates/footer');
    }
}
