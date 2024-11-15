<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_asset extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_penyusutan');
        $this->load->model('Model_penyusutan_bangunan');
        $this->load->model('Model_penyusutan_sumber');
        $this->load->model('Model_penyusutan_pompa');
        $this->load->model('Model_penyusutan_olah_air');
        $this->load->model('Model_penyusutan_trans_dist');
        $this->load->model('Model_penyusutan_peralatan');
        $this->load->model('Model_penyusutan_kendaraan');
        $this->load->model('Model_penyusutan_inventaris');
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

        $level_pengguna = $this->session->userdata('level');
        if ($level_pengguna != 'Admin') {
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
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'Rekap Penyusutan Asset';
        $tanah = $this->Model_penyusutan->get_tanah($tahun);
        $data['total_tanah'] = $tanah['total_tanah'];
        $bangunan = $this->Model_penyusutan_bangunan->get_bangunan($tahun);
        $data['total_bangunan'] = $bangunan['total_bangunan'];
        $sumber = $this->Model_penyusutan_sumber->get_sumber($tahun);
        $data['total_sumber'] = $sumber['total_sumber'];
        $pompa = $this->Model_penyusutan_pompa->get_pompa($tahun);
        $data['total_pompa'] = $pompa['total_pompa'];
        $olah_air = $this->Model_penyusutan_olah_air->get_olah_air($tahun);
        $data['total_olah_air'] = $olah_air['total_olah_air'];
        $trans_dist = $this->Model_penyusutan_trans_dist->get_trans_dist($tahun);
        $data['total_trans_dist'] = $trans_dist['total_trans_dist'];
        $peralatan = $this->Model_penyusutan_peralatan->get_peralatan($tahun);
        $data['total_peralatan'] = $peralatan['total_peralatan'];
        $kendaraan = $this->Model_penyusutan_kendaraan->get_kendaraan($tahun);
        $data['total_kendaraan'] = $kendaraan['total_kendaraan'];
        $inventaris = $this->Model_penyusutan_inventaris->get_inventaris($tahun);
        $data['total_inventaris'] = $inventaris['total_inventaris'];

        $total_semua = $this->Model_penyusutan->get_total_semua($tahun);
        $data['total_semua'] = $total_semua['total_semua'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('dashboard/view_dashboard_asset', $data);
        $this->load->view('templates/footer');
    }
}
