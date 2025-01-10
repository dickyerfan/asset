<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jurnal extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Model_jurnal');
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
        if ($bagian != 'Keuangan' && $bagian != 'Administrator' && $bagian != 'Auditor') {
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
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            // Hapus session jika tidak ada tahun yang dipilih
            $this->session->unset_userdata('tahun_session');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;
        $data['title'] = 'Perhitungan Jurnal Umum';
        $penyusutan_data = $this->Model_jurnal->get_all($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('jurnal/view_jurnal', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_jurnal()
    {
        $tahun = $this->session->userdata('tahun_session');
        // Hapus session jika tidak ada tahun atau tahun tidak valid
        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'Perhitungan Jurnal Umum';
        $penyusutan_data = $this->Model_jurnal->get_all($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'portrait');
        $this->pdf->filename = "jurnal-{$tahun}.pdf";
        $this->pdf->generate('cetakan_jurnal/jurnal_pdf', $data);
    }

    // public function bangunan()
    // {
    //     $get_tahun = $this->input->get('tahun');
    //     $tahun = substr($get_tahun, 0, 4);

    //     if (empty($get_tahun)) {
    //         $tahun = date('Y');
    //         // Hapus session jika tidak ada tahun yang dipilih
    //         $this->session->unset_userdata('tahun_session_bangunan');
    //     } else {
    //         $this->session->set_userdata('tahun_session_bangunan', $get_tahun);
    //     }

    //     $data['tahun_lap'] = $tahun;
    //     $data['title'] = 'Perhitungan Jurnal Umum Bangunan';
    //     $penyusutan_data = $this->Model_jurnal->get_bangunan($tahun);
    //     $data['susut'] = $penyusutan_data['results'];
    //     $data['total_bangunan'] = $penyusutan_data['total_bangunan'];

    //     $this->load->view('templates/header', $data);
    //     $this->load->view('templates/navbar');
    //     $this->load->view('templates/sidebar');
    //     $this->load->view('jurnal/view_jurnal_bangunan', $data);
    //     $this->load->view('templates/footer');
    // }

    // public function cetak_bangunan()
    // {
    //     $tahun = $this->session->userdata('tahun_session_bangunan');
    //     // Hapus session jika tidak ada tahun atau tahun tidak valid
    //     if (empty($tahun)) {
    //         $this->session->unset_userdata('tahun_session_bangunan');
    //         $tahun = date('Y');
    //     }

    //     $data['tahun_lap'] = $tahun;
    //     $data['title'] = 'Perhitungan Jurnal Umum Bangunan';
    //     $penyusutan_data = $this->Model_jurnal->get_bangunan($tahun);
    //     $data['susut'] = $penyusutan_data['results'];
    //     $data['total_bangunan'] = $penyusutan_data['total_bangunan'];

    //     // Set paper size and orientation
    //     $this->pdf->setPaper('folio', 'landscape');
    //     $this->pdf->filename = "bangunan-{$tahun}.pdf";
    //     $this->pdf->generate('cetakan_jurnal/bangunan_pdf', $data);
    // }
}
