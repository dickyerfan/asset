<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Perubahan_ekuitas extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_penyesuaian_ekuitas');
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
        $tanggal = $this->input->get('tahun');
        $tahun = substr($tanggal, 0, 4);

        if (empty($tanggal)) {
            $tanggal = date('Y-m-d');
            $bulan = date('m');
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun', $tanggal);
        }

        $data['tahun_lap'] = $tahun;
        $data['tahun_lalu'] = $tahun - 1;
        $data['dua_tahun_lalu'] = $tahun - 2;

        $data['title'] = 'LAPORAN PERUBAHAN EKUITAS (Unaudited)';
        $data['title2'] = 'LAPORAN PERUBAHAN EKUITAS (audited)';
        $data['ekuitas_dua_tahun_lalu'] = $this->Model_penyesuaian_ekuitas->get_by_year($tahun - 2);
        $data['ekuitas_tahun_lalu'] = $this->Model_penyesuaian_ekuitas->get_by_year($tahun - 1);
        $data['ekuitas_tahun_ini'] = $this->Model_penyesuaian_ekuitas->get_by_year($tahun);
        $data['ekuitas_dua_tahun_lalu_audited'] = $this->Model_penyesuaian_ekuitas->get_by_year_audited($tahun - 2);
        $data['ekuitas_tahun_lalu_audited'] = $this->Model_penyesuaian_ekuitas->get_by_year_audited($tahun - 1);
        $data['ekuitas_tahun_ini_audited'] = $this->Model_penyesuaian_ekuitas->get_by_year_audited($tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/view_perubahan_ekuitas', $data);
        $this->load->view('templates/footer');
    }
}
