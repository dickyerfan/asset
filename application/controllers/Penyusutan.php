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
            // Hapus session jika tidak ada tahun yang dipilih
            $this->session->unset_userdata('tahun_session_bangunan');
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
        $data['upk_bagian'] = $this->Model_penyusutan->get_upk_bagian();
        $penyusutan_data = $this->Model_penyusutan->get_bangunan($tahun);
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
            $penyusutan_data = $this->Model_penyusutan->get_bangunan_kantor_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan->get_bangunan_kantor($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan->get_upk_bagian();
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
            $penyusutan_data = $this->Model_penyusutan->get_bangunan_kantor_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan->get_bangunan_kantor($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan->get_upk_bagian();
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
            $penyusutan_data = $this->Model_penyusutan->get_bangunan_lab_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan->get_bangunan_lab($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan->get_unit_bangunan_lab();
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
            $penyusutan_data = $this->Model_penyusutan->get_bangunan_alat_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan->get_bangunan_alat($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan->get_unit_bangunan_alat();
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
            $penyusutan_data = $this->Model_penyusutan->get_bangunan_bengkel_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan->get_bangunan_bengkel($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan->get_unit_bangunan_bengkel();
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
            $penyusutan_data = $this->Model_penyusutan->get_bangunan_inst_total($tahun);
        } else {
            // Jika ada filter, ambil data berdasarkan filter
            $penyusutan_data = $this->Model_penyusutan->get_bangunan_inst($tahun, $upk_bagian);
        }

        $data['title'] = 'Daftar Penyusutan';
        $data['upk_bagian'] = $this->Model_penyusutan->get_unit_bangunan_inst();
        $data['susut'] = $penyusutan_data['results'];
        $data['totals'] = $penyusutan_data['totals'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan_bangunan_inst', $data);
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
