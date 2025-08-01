<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Matrik_risiko extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_risiko');
        $this->load->library('form_validation');
        if (!$this->session->userdata('nama_pengguna')) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Maaf,</strong> Anda harus login untuk akses halaman ini...
                      </div>'
            );
            redirect('auth');
        }
    }

    public function index()
    {
        $data['title'] = 'SELERA RISIKO';
        $data['title2'] = 'STATUS RISIKO';
        $query = $this->db->get('mr_matrik_risiko')->result();

        // Buat array 2 dimensi berdasarkan kombinasi [probabilitas][dampak]
        $matrik = [];
        foreach ($query as $row) {
            $matrik[$row->probabilitas][$row->dampak] = $row->skor;
        }
        $data['matrik'] = $matrik;
        $data['tingkat_risiko'] = $this->db->order_by('level_tr', 'DESC')->get('mr_tingkat_risiko')->result();

        if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('risiko/view_matrik_risiko', $data);
            $this->load->view('templates/footer');
        } else if ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('risiko/view_matrik_risiko', $data);
            $this->load->view('templates/footer');
        } else if ($this->session->userdata('bagian') == 'Umum') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_umum');
            $this->load->view('risiko/view_matrik_risiko', $data);
            $this->load->view('templates/footer');
        } else if ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('risiko/view_matrik_risiko', $data);
            $this->load->view('templates/footer');
        } else if ($this->session->userdata('bagian') == 'Perencanaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_rencana');
            $this->load->view('risiko/view_matrik_risiko', $data);
            $this->load->view('templates/footer');
        } else if ($this->session->userdata('bagian') == 'Pemeliharaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('risiko/view_matrik_risiko', $data);
            $this->load->view('templates/footer');
        } else if ($this->session->userdata('bagian') == 'UPK') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_upk');
            $this->load->view('risiko/view_matrik_risiko', $data);
            $this->load->view('templates/footer');
        }
    }
}
