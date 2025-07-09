<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengaturan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_evaluasi_upk');
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
        $bagian = $this->session->userdata('bagian');
        if ($bagian != 'Publik' && $bagian != 'Administrator') {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Maaf,</strong> Anda tidak memiliki hak akses untuk halaman ini...
                  </div>'
            );
            redirect('auth');
        }
    }

    public function index()
    {
        $data['title'] = 'Pengaturan Indikator Evaluasi UPK';
        // Ambil semua aspek
        $data['aspek_list'] = $this->Model_evaluasi_upk->get_all_aspek();

        // Ambil semua indikator per aspek
        $data['indikator_list'] = [];
        foreach ($data['aspek_list'] as $aspek) {
            $data['indikator_list'][$aspek->id_aspek] = $this->Model_evaluasi_upk->get_indikator_by_aspek($aspek->id_aspek);
        }

        // Ambil semua opsi per indikator
        $data['opsi_list'] = [];
        foreach ($data['indikator_list'] as $indikators) {
            foreach ($indikators as $indikator) {
                $data['opsi_list'][$indikator->id_indikator] = $this->Model_evaluasi_upk->get_opsi_by_indikator($indikator->id_indikator);
            }
        }

        if ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('spi/view_pengaturan', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('spi/view_pengaturan', $data);
            $this->load->view('templates/footer');
        }
    }

    public function tambah_aspek()
    {
        $nama_aspek = $this->input->post('nama_aspek');
        $this->Model_evaluasi_upk->insert_aspek($nama_aspek);
        $this->session->set_flashdata('info', '<div class="alert alert-success">Aspek berhasil ditambahkan.</div>');
        redirect('spi/pengaturan');
    }

    public function tambah_indikator()
    {
        $id_aspek = $this->input->post('id_aspek');
        $nama_indikator = $this->input->post('nama_indikator');
        $this->Model_evaluasi_upk->insert_indikator($id_aspek, $nama_indikator);
        $this->session->set_flashdata('info', '<div class="alert alert-success">Indikator berhasil ditambahkan.</div>');
        redirect('spi/pengaturan');
    }

    public function tambah_opsi()
    {
        $id_indikator = $this->input->post('id_indikator');
        $nama_opsi = $this->input->post('nama_opsi');
        $skor = $this->input->post('skor');
        $this->Model_evaluasi_upk->insert_opsi($id_indikator, $nama_opsi, $skor);
        $this->session->set_flashdata('info', '<div class="alert alert-success">Opsi berhasil ditambahkan.</div>');
        redirect('spi/pengaturan');
    }
}
