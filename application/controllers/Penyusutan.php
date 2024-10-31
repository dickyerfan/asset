<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penyusutan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_penyusutan');
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

        $data['title'] = 'Daftar Penyusutan Asset';
        $penyusutan_data = $this->Model_penyusutan->get_all($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan', $data);
        $this->load->view('templates/footer');
    }

    public function tanah()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_tanah', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'Daftar Penyusutan Tanah';
        $penyusutan_data = $this->Model_penyusutan->get_tanah($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_tanah', $data);
        $this->load->view('templates/footer');
    }

    public function sumber()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_sumber', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'Daftar Penyusutan Sumber';
        $penyusutan_data = $this->Model_penyusutan->get_sumber($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_sumber', $data);
        $this->load->view('templates/footer');
    }

    public function pompa()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_pompa', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'Daftar Penyusutan Pompa';
        $penyusutan_data = $this->Model_penyusutan->get_pompa($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_pompa', $data);
        $this->load->view('templates/footer');
    }

    public function olah_air()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_olah_air', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'Daftar Penyusutan Pengolahan Air';
        $penyusutan_data = $this->Model_penyusutan->get_olah_air($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_olah_air', $data);
        $this->load->view('templates/footer');
    }

    public function trans_dist()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_trans_dist', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'Daftar Penyusutan Transmisi & Distibusi';
        $penyusutan_data = $this->Model_penyusutan->get_trans_dist($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_trans_dist', $data);
        $this->load->view('templates/footer');
    }

    public function bangunan()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_bangunan', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'Daftar Penyusutan Bangunan';
        $data['upk_bagian'] = $this->Model_penyusutan->get_upk_bagian();
        $penyusutan_data = $this->Model_penyusutan->get_bangunan($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_bangunan', $data);
        $this->load->view('templates/footer');
    }

    public function bangunan_kantor()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_bangunan', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan->get_upk_bagian();
        $penyusutan_data = $this->Model_penyusutan->get_bangunan_kantor($tahun, $upk_bagian);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_bangunan_kantor', $data);
        $this->load->view('templates/footer');
    }

    public function peralatan()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_peralatan', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'Daftar Penyusutan Peralatan';
        $penyusutan_data = $this->Model_penyusutan->get_peralatan($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_peralatan', $data);
        $this->load->view('templates/footer');
    }

    public function kendaraan()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_kendaraan', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'Daftar Penyusutan Kendaraan';
        $penyusutan_data = $this->Model_penyusutan->get_kendaraan($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_kendaraan', $data);
        $this->load->view('templates/footer');
    }

    public function inventaris()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_inventaris', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'Daftar Penyusutan Inventaris';
        $penyusutan_data = $this->Model_penyusutan->get_inventaris($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_inventaris', $data);
        $this->load->view('templates/footer');
    }
}
