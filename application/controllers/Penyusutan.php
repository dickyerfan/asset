<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penyusutan extends CI_Controller
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

    public function cetak_tanah()
    {
        $tahun = $this->session->userdata('tahun_session_tanah');

        // Hapus session jika tidak ada tahun atau tahun tidak valid
        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session_tanah');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'Daftar Penyusutan Tanah';
        $penyusutan_data = $this->Model_penyusutan->get_tanah($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');
        $this->pdf->filename = "tanah-{$tahun}.pdf";
        $this->pdf->generate('cetakan/tanah_pdf', $data);
    }

    public function bangunan()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            // Hapus session jika tidak ada tahun yang dipilih
            $this->session->unset_userdata('tahun_session_bangunan');
        } else {
            $this->session->set_userdata('tahun_session_bangunan', $get_tahun);
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'Daftar Penyusutan Bangunan';
        $penyusutan_data = $this->Model_penyusutan_bangunan->get_bangunan($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_bangunan', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_bangunan()
    {
        $tahun = $this->session->userdata('tahun_session_bangunan');

        // Hapus session jika tidak ada tahun atau tahun tidak valid
        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session_bangunan');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'Daftar Penyusutan Bangunan';
        $penyusutan_data = $this->Model_penyusutan_bangunan->get_bangunan($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');
        $this->pdf->filename = "bangunan-{$tahun}.pdf";
        $this->pdf->generate('cetakan/bangunan_pdf', $data);
    }

    public function bangunan_kantor()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_bangunan_ktr');
        } else {
            $this->session->set_userdata('tahun_session_bangunan_ktr', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_bangunan->get_bangunan_kantor_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_bangunan->get_bangunan_kantor($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_bangunan->get_unit_bangunan_kantor();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_bangunan_kantor', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_bangunan_kantor()
    {
        $get_tahun = $this->session->userdata('tahun_session_bangunan_ktr');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_bangunan_ktr');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_bangunan->get_bangunan_kantor_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_bangunan->get_bangunan_kantor($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_bangunan->get_unit_bangunan_kantor();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "bangunan-{$tahun}.pdf";
        $this->pdf->generate('cetakan/bangunan_kantor_pdf', $data);
    }

    public function bangunan_laboratorium()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_bangunan_lab', $get_tahun);
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

        if (empty($get_tahun) && empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_bangunan->get_bangunan_lab_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_bangunan->get_bangunan_lab($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_bangunan->get_unit_bangunan_lab();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_bangunan_lab', $data);
        $this->load->view('templates/footer');
    }

    public function bangunan_peralatan()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_bangunan_alat', $get_tahun);
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

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_bangunan->get_bangunan_alat_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_bangunan->get_bangunan_alat($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_bangunan->get_unit_bangunan_alat();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_bangunan_alat', $data);
        $this->load->view('templates/footer');
    }

    public function bangunan_bengkel()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_bangunan_bengkel', $get_tahun);
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

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_bangunan->get_bangunan_bengkel_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_bangunan->get_bangunan_bengkel($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_bangunan->get_unit_bangunan_bengkel();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_bangunan_bengkel', $data);
        $this->load->view('templates/footer');
    }

    public function bangunan_inst_lain()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_bangunan_inst', $get_tahun);
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

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_bangunan->get_bangunan_inst_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_bangunan->get_bangunan_inst($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_bangunan->get_unit_bangunan_inst();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_bangunan_inst', $data);
        $this->load->view('templates/footer');
    }

    public function sumber()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_sumber');
        } else {
            $this->session->set_userdata('tahun_session_sumber', $get_tahun);
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'Daftar Penyusutan Instalasi Sumber';
        $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_sumber', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_sumber()
    {
        $tahun = $this->session->userdata('tahun_session_sumber');

        // Hapus session jika tidak ada tahun atau tahun tidak valid
        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session_sumber');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'Daftar Penyusutan Instalasi Sumber';
        $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');
        $this->pdf->filename = "sumber-{$tahun}.pdf";
        $this->pdf->generate('cetakan/sumber_pdf', $data);
    }

    public function sumber_bangunan()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_sumber_bang');
        } else {
            $this->session->set_userdata('tahun_session_sumber_bang', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_bangunan_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_bangunan($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_sumber->get_unit_sumber_bangunan();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_sumber_bangunan', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_sumber_bangunan()
    {
        $get_tahun = $this->session->userdata('tahun_session_sumber_bang');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_sumber_bang');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_bangunan_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_bangunan($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_sumber->get_unit_sumber_bangunan();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "sumber_bangunan-{$tahun}.pdf";
        $this->pdf->generate('cetakan/sumber_bangunan_pdf', $data);
    }
    public function sumber_reservoir()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_sumber_reservoir');
        } else {
            $this->session->set_userdata('tahun_session_sumber_reservoir', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_reservoir_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_reservoir($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_sumber->get_unit_sumber_reservoir();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_sumber_reservoir', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_sumber_reservoir()
    {
        $get_tahun = $this->session->userdata('tahun_session_sumber_reservoir');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_sumber_reservoir');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_reservoir_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_reservoir($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_sumber->get_unit_sumber_reservoir();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "sumber_reservoir-{$tahun}.pdf";
        $this->pdf->generate('cetakan/sumber_reservoir_pdf', $data);
    }

    public function sumber_sumur()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_sumber_sumur');
        } else {
            $this->session->set_userdata('tahun_session_sumber_sumur', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_sumur_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_sumur($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_sumber->get_unit_sumber_sumur();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_sumber_sumur', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_sumber_sumur()
    {
        $get_tahun = $this->session->userdata('tahun_session_sumber_sumur');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_sumber_sumur');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_sumur_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_sumur($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_sumber->get_unit_sumber_sumur();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "sumber_sumur-{$tahun}.pdf";
        $this->pdf->generate('cetakan/sumber_sumur_pdf', $data);
    }

    public function sumber_pipa()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_sumber_pipa');
        } else {
            $this->session->set_userdata('tahun_session_sumber_pipa', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_pipa_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_pipa($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_sumber->get_unit_sumber_pipa();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_sumber_pipa', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_sumber_pipa()
    {
        $get_tahun = $this->session->userdata('tahun_session_sumber_pipa');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_sumber_pipa');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_pipa_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_pipa($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_sumber->get_unit_sumber_pipa();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "sumber_pipa-{$tahun}.pdf";
        $this->pdf->generate('cetakan/sumber_pipa_pdf', $data);
    }

    public function sumber_inst_lain()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_sumber_inst_lain');
        } else {
            $this->session->set_userdata('tahun_session_sumber_inst_lain', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_inst_lain_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_inst_lain($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_sumber->get_unit_sumber_inst_lain();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_sumber_inst_lain', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_sumber_inst_lain()
    {
        $get_tahun = $this->session->userdata('tahun_session_sumber_inst_lain');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_sumber_inst_lain');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_inst_lain_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_sumber->get_sumber_inst_lain($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_sumber->get_unit_sumber_inst_lain();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "sumber_inst_lain-{$tahun}.pdf";
        $this->pdf->generate('cetakan/sumber_inst_lain_pdf', $data);
    }

    public function pompa()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_pompa');
        } else {
            $this->session->set_userdata('tahun_session_pompa', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;
        $data['title'] = 'Daftar Penyusutan Instalasi Pompa';
        $penyusutan_data = $this->Model_penyusutan_pompa->get_pompa($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_pompa', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_pompa()
    {
        $tahun = $this->session->userdata('tahun_session_pompa');

        // Hapus session jika tidak ada tahun atau tahun tidak valid
        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session_pompa');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'Daftar Penyusutan Instalasi Pompa';
        $penyusutan_data = $this->Model_penyusutan_pompa->get_pompa($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');
        $this->pdf->filename = "pompa-{$tahun}.pdf";
        $this->pdf->generate('cetakan/pompa_pdf', $data);
    }

    public function pompa_bangunan()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_pompa_bang');
        } else {
            $this->session->set_userdata('tahun_session_pompa_bang', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_pompa->get_pompa_bangunan_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_pompa->get_pompa_bangunan($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_pompa->get_unit_pompa_bangunan();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_pompa_bangunan', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_pompa_bangunan()
    {
        $get_tahun = $this->session->userdata('tahun_session_pompa_bang');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_pompa_bang');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_pompa->get_pompa_bangunan_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_pompa->get_pompa_bangunan($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_pompa->get_unit_pompa_bangunan();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "pompa_bangunan-{$tahun}.pdf";
        $this->pdf->generate('cetakan/pompa_bangunan_pdf', $data);
    }

    public function pompa_listrik()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_pompa_listrik');
        } else {
            $this->session->set_userdata('tahun_session_pompa_listrik', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_pompa->get_pompa_listrik_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_pompa->get_pompa_listrik($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_pompa->get_unit_pompa_listrik();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_pompa_listrik', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_pompa_listrik()
    {
        $get_tahun = $this->session->userdata('tahun_session_pompa_listrik');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_pompa_listrik');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_pompa->get_pompa_listrik_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_pompa->get_pompa_listrik($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_pompa->get_unit_pompa_listrik();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "pompa_listrik-{$tahun}.pdf";
        $this->pdf->generate('cetakan/pompa_listrik_pdf', $data);
    }

    public function pompa_alat()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_pompa_alat');
        } else {
            $this->session->set_userdata('tahun_session_pompa_alat', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_pompa->get_pompa_alat_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_pompa->get_pompa_alat($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_pompa->get_unit_pompa_alat();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_pompa_alat', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_pompa_alat()
    {
        $get_tahun = $this->session->userdata('tahun_session_pompa_alat');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_pompa_bang');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_pompa->get_pompa_alat_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_pompa->get_pompa_alat($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_pompa->get_unit_pompa_alat();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "pompa_alat-{$tahun}.pdf";
        $this->pdf->generate('cetakan/pompa_alat_pdf', $data);
    }

    public function pompa_inst_lain()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_pompa_inst_lain');
        } else {
            $this->session->set_userdata('tahun_session_pompa_inst_lain', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_pompa->get_pompa_inst_lain_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_pompa->get_pompa_inst_lain($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_pompa->get_unit_pompa_inst_lain();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_pompa_inst_lain', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_pompa_inst_lain()
    {
        $get_tahun = $this->session->userdata('tahun_session_pompa_inst_lain');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_pompa_bang');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_pompa->get_pompa_inst_lain_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_pompa->get_pompa_inst_lain($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_pompa->get_unit_pompa_inst_lain();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "pompa_inst_lain-{$tahun}.pdf";
        $this->pdf->generate('cetakan/pompa_inst_lain_pdf', $data);
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
        $penyusutan_data = $this->Model_penyusutan_olah_air->get_olah_air($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_olah_air', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_olah_air()
    {
        $tahun = $this->session->userdata('tahun_session_olah_air');

        // Hapus session jika tidak ada tahun atau tahun tidak valid
        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session_olah_air');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'Daftar Penyusutan Pengolahan Air';
        $penyusutan_data = $this->Model_penyusutan_olah_air->get_olah_air($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');
        $this->pdf->filename = "olah_air-{$tahun}.pdf";
        $this->pdf->generate('cetakan/olah_air_pdf', $data);
    }

    public function olah_air_bangunan()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_olah_air_bang');
        } else {
            $this->session->set_userdata('tahun_session_olah_air_bang', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_olah_air->get_olah_air_bangunan_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_olah_air->get_olah_air_bangunan($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_olah_air->get_unit_olah_air_bangunan();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_olah_air_bangunan', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_olah_air_bangunan()
    {
        $get_tahun = $this->session->userdata('tahun_session_olah_air_bang');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_olah_air_bang');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_olah_air->get_olah_air_bangunan_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_olah_air->get_olah_air_bangunan($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_olah_air->get_unit_olah_air_bangunan();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "olah_air_bangunan-{$tahun}.pdf";
        $this->pdf->generate('cetakan/olah_air_bangunan_pdf', $data);
    }

    public function olah_air_alat()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_olah_air_alat');
        } else {
            $this->session->set_userdata('tahun_session_olah_air_alat', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_olah_air->get_olah_air_alat_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_olah_air->get_olah_air_alat($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_olah_air->get_unit_olah_air_alat();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_olah_air_alat', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_olah_air_alat()
    {
        $get_tahun = $this->session->userdata('tahun_session_olah_air_bang');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_olah_air_alat');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_olah_air->get_olah_air_alat_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_olah_air->get_olah_air_alat($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_olah_air->get_unit_olah_air_alat();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "olah_air_alat-{$tahun}.pdf";
        $this->pdf->generate('cetakan/olah_air_alat_pdf', $data);
    }

    public function olah_air_reservoir()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_olah_air_reservoir');
        } else {
            $this->session->set_userdata('tahun_session_olah_air_reservoir', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_olah_air->get_olah_air_reservoir_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_olah_air->get_olah_air_reservoir($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_olah_air->get_unit_olah_air_reservoir();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_olah_air_reservoir', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_olah_air_reservoir()
    {
        $get_tahun = $this->session->userdata('tahun_session_olah_air_reservoir');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_olah_air_reservoir');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_olah_air->get_olah_air_reservoir_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_olah_air->get_olah_air_reservoir($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_olah_air->get_unit_olah_air_reservoir();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "olah_air_reservoir-{$tahun}.pdf";
        $this->pdf->generate('cetakan/olah_air_reservoir_pdf', $data);
    }

    public function olah_air_inst_lain()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_olah_air_inst_lain');
        } else {
            $this->session->set_userdata('tahun_session_olah_air_inst_lain', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_olah_air->get_olah_air_inst_lain_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_olah_air->get_olah_air_inst_lain($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_olah_air->get_unit_olah_air_inst_lain();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_olah_air_inst_lain', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_olah_air_inst_lain()
    {
        $get_tahun = $this->session->userdata('tahun_session_olah_air_inst_lain');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_olah_air_inst_lain');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_olah_air->get_olah_air_inst_lain_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_olah_air->get_olah_air_inst_lain($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_olah_air->get_unit_olah_air_inst_lain();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "olah_air_inst_lain-{$tahun}.pdf";
        $this->pdf->generate('cetakan/olah_air_inst_lain_pdf', $data);
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

        $data['title'] = 'Daftar Penyusutan Transmisi & Distribusi';
        $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_trans_dist', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_trans_dist()
    {
        $tahun = $this->session->userdata('tahun_session_trans_dist');

        // Hapus session jika tidak ada tahun atau tahun tidak valid
        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session_trans_dist');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'Daftar Penyusutan Transmisi & Distribusi';
        $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');
        $this->pdf->filename = "trans_dist-{$tahun}.pdf";
        $this->pdf->generate('cetakan/trans_dist_pdf', $data);
    }

    public function trans_dist_bangunan()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_trans_dist_bang');
        } else {
            $this->session->set_userdata('tahun_session_trans_dist_bang', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_bangunan_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_bangunan($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_trans_dist->get_unit_trans_dist_bangunan();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_trans_dist_bangunan', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_trans_dist_bangunan()
    {
        $get_tahun = $this->session->userdata('tahun_session_trans_dist_bang');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_trans_dist_bang');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_bangunan_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_bangunan($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_trans_dist->get_unit_trans_dist_bangunan();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "trans_dist_bangunan-{$tahun}.pdf";
        $this->pdf->generate('cetakan/trans_dist_bangunan_pdf', $data);
    }

    public function trans_dist_reservoir()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_trans_dist_reservoir');
        } else {
            $this->session->set_userdata('tahun_session_trans_dist_reservoir', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_reservoir_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_reservoir($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_trans_dist->get_unit_trans_dist_reservoir();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_trans_dist_reservoir', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_trans_dist_reservoir()
    {
        $get_tahun = $this->session->userdata('tahun_session_trans_dist_reservoir');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_trans_dist_reservoir');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_reservoir_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_reservoir($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_trans_dist->get_unit_trans_dist_reservoir();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "trans_dist_reservoir-{$tahun}.pdf";
        $this->pdf->generate('cetakan/trans_dist_reservoir_pdf', $data);
    }

    public function trans_dist_pipa_trans()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_trans_dist_pipa_trans');
        } else {
            $this->session->set_userdata('tahun_session_trans_dist_pipa_trans', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_pipa_trans_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_pipa_trans($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_trans_dist->get_unit_trans_dist_pipa_trans();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_trans_dist_pipa_trans', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_trans_dist_pipa_trans()
    {
        $get_tahun = $this->session->userdata('tahun_session_trans_dist_pipa_trans');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_trans_dist_pipa_trans');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_pipa_trans_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_pipa_trans($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_trans_dist->get_unit_trans_dist_pipa_trans();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "trans_dist_pipa_trans-{$tahun}.pdf";
        $this->pdf->generate('cetakan/trans_dist_pipa_trans_pdf', $data);
    }

    public function trans_dist_meter()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_trans_dist_meter');
        } else {
            $this->session->set_userdata('tahun_session_trans_dist_meter', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_meter_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_meter($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_trans_dist->get_unit_trans_dist_meter();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_trans_dist_meter', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_trans_dist_meter()
    {
        $get_tahun = $this->session->userdata('tahun_session_trans_dist_meter');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_trans_dist_meter');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_meter_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_meter($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_trans_dist->get_unit_trans_dist_meter();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "trans_dist_meter-{$tahun}.pdf";
        $this->pdf->generate('cetakan/trans_dist_meter_pdf', $data);
    }

    public function trans_dist_ledeng()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_trans_dist_ledeng');
        } else {
            $this->session->set_userdata('tahun_session_trans_dist_ledeng', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_ledeng_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_ledeng($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_trans_dist->get_unit_trans_dist_ledeng();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_trans_dist_ledeng', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_trans_dist_ledeng()
    {
        $get_tahun = $this->session->userdata('tahun_session_trans_dist_ledeng');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_trans_dist_ledeng');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_ledeng_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_ledeng($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_trans_dist->get_unit_trans_dist_ledeng();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "trans_dist_ledeng-{$tahun}.pdf";
        $this->pdf->generate('cetakan/trans_dist_ledeng_pdf', $data);
    }

    public function trans_dist_pemadam()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_trans_dist_pemadam');
        } else {
            $this->session->set_userdata('tahun_session_trans_dist_pemadam', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_pemadam_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_pemadam($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_trans_dist->get_unit_trans_dist_pemadam();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_trans_dist_pemadam', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_trans_dist_pemadam()
    {
        $get_tahun = $this->session->userdata('tahun_session_trans_dist_pemadam');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_trans_dist_pemadam');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_pemadam_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_pemadam($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_trans_dist->get_unit_trans_dist_pemadam();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "trans_dist_pemadam-{$tahun}.pdf";
        $this->pdf->generate('cetakan/trans_dist_pemadam_pdf', $data);
    }

    public function trans_dist_jembatan()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_trans_dist_jembatan');
        } else {
            $this->session->set_userdata('tahun_session_trans_dist_jembatan', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_jembatan_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_jembatan($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_trans_dist->get_unit_trans_dist_jembatan();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_trans_dist_jembatan', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_trans_dist_jembatan()
    {
        $get_tahun = $this->session->userdata('tahun_session_trans_dist_jembatan');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_trans_dist_jembatan');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_jembatan_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_jembatan($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_trans_dist->get_unit_trans_dist_jembatan();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "trans_dist_jembatan-{$tahun}.pdf";
        $this->pdf->generate('cetakan/trans_dist_jembatan_pdf', $data);
    }

    public function trans_dist_inst_lain()
    {
        $get_tahun = $this->input->get('tahun');
        $upk_bagian = $this->input->get('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
            $this->session->unset_userdata('tahun_session_trans_dist_inst_lain');
        } else {
            $this->session->set_userdata('tahun_session_trans_dist_inst_lain', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        if (!empty($upk_bagian)) {
            $this->session->set_userdata('upk_bagian', $upk_bagian);
        } else {
            $this->session->unset_userdata('upk_bagian', $upk_bagian);
        }

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_inst_lain_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_inst_lain($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_trans_dist->get_unit_trans_dist_inst_lain();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_trans_dist_inst_lain', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_trans_dist_inst_lain()
    {
        $get_tahun = $this->session->userdata('tahun_session_trans_dist_inst_lain');
        $upk_bagian = $this->session->userdata('upk_bagian');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        if ($upk_bagian) {
            $data['selected_upk'] = $this->Model_penyusutan->getUpkById($upk_bagian);
        } else {
            $data['selected_upk'] = null;
        }

        if (empty($get_tahun) || empty($upk_bagian)) {
            // Jika tidak ada filter, ambil semua data
            $this->session->unset_userdata('tahun_session_trans_dist_inst_lain');
            $this->session->unset_userdata('upk_bagian');
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_inst_lain_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan_trans_dist->get_trans_dist_inst_lain($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan_trans_dist->get_unit_trans_dist_inst_lain();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'landscape');

        $this->pdf->filename = "trans_dist_inst_lain-{$tahun}.pdf";
        $this->pdf->generate('cetakan/trans_dist_inst_lain_pdf', $data);
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
