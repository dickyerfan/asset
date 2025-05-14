<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_publik extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_evkin');
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
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;
        $data['title'] = 'Penilaian Kinerja Tahun ' . $tahun . ' <br> Berdasarkan indikator KemenPUPR';
        $data['title2'] = 'Penilaian Kinerja Tahun ' . $tahun . ' <br> Berdasarkan Kepmendagri No. 47 Tahun 1999';

        $laba_rugi = $this->Model_evkin->hitung_laba_rugi_bersih($tahun);
        $data['total_hasil_keuangan'] = $laba_rugi['total_hasil_keuangan'];

        $pelayanan = $this->Model_evkin->hitung_pelayanan($tahun);
        $pengaduan = $this->Model_evkin->hitung_pengaduan($tahun);
        $kualitas_air = $this->Model_evkin->hitung_kualitas_air($tahun);
        $pelanggan = $this->Model_evkin->hitung_pelanggan($tahun);
        $air_dom = $this->Model_evkin->hitung_air_domestik($tahun);

        $total_pelayanan = $pelayanan['hasil_cak_teknis'];
        $total_pengaduan = $pengaduan['hasil_pengaduan'];
        $total_kualitas_air = $kualitas_air['hasil_kualitas'];
        $total_pelanggan = $pelanggan['hasil_pelanggan'];
        $total_air_domestik = $air_dom['hasil_air_dom'];
        $data['total_hasil_pelayanan'] = $total_pelayanan + $total_pengaduan + $total_kualitas_air + $total_pelanggan + $total_air_domestik;

        $kap_prod = $this->Model_evkin->hitung_efisiensi_prod($tahun);
        $tekanan_air = $this->Model_evkin->hitung_tekanan_air($tahun);
        $ganti_meter = $this->Model_evkin->hitung_ganti_meter($tahun);
        $pendapatan = $this->Model_evkin->hitung_pendapatan($tahun);
        $jam_ops = $this->Model_evkin->hitung_jam_ops($tahun);

        $total_kap_prod = $kap_prod['hasil_kap_prod'];
        $total_tekanan_air = $tekanan_air['hasil_tekanan_air'];
        $total_ganti_meter = $ganti_meter['hasil_ganti_meter'];
        $total_pendapatan = $pendapatan['hasil_nrw'];
        $total_jam_ops = $jam_ops['hasil_jam_ops'];

        $data['total_hasil_operasional'] = $total_kap_prod + $total_tekanan_air + $total_ganti_meter + $total_pendapatan + $total_jam_ops;

        if ($tahun == 2024) {
            $data['total_hasil_sdm'] = 0.55;
        } else {
            $data['total_hasil_sdm'] = 0;
        }

        $data['total'] = $data['total_hasil_keuangan'] + $data['total_hasil_pelayanan'] + $data['total_hasil_operasional'] + $data['total_hasil_sdm'];

        $total_pupr = $data['total'];

        if ($total_pupr > 2.8) {
            $data['kategori_pupr'] = 'SEHAT';
        } elseif ($total_pupr >= 2.2 && $total_pupr <= 2.8) {
            $data['kategori_pupr'] = 'KURANG SEHAT';
        } elseif ($total_pupr < 2.2 && $total_pupr > 0) {
            $data['kategori_pupr'] = 'SAKIT';
        } elseif ($total_pupr == 0) {
            $data['kategori_pupr'] = 'NO DATA';
        }
        // Kepmendagri
        if ($tahun == 2024) {
            $data['total_keuangan_kepmen'] = 30.75;
            $data['total_operasional_kepmen'] = 22.98;
            $data['total_administrasi_kepmen'] = 13.75;
            $data['total_kepmen'] = 67.48;
        } else {
            $data['total_kepmen'] = 0;
            $data['total_keuangan_kepmen'] = 0;
            $data['total_operasional_kepmen'] = 0;
            $data['total_administrasi_kepmen'] = 0;
        }
        $total_kepmen = $data['total_kepmen'];
        if ($total_kepmen > 75) {
            $data['kategori_kepmen'] = 'BAIK SEKALI';
        } elseif ($total_kepmen >= 60 && $total_kepmen <= 75) {
            $data['kategori_kepmen'] = 'BAIK';
        } elseif ($total_kepmen >= 45 && $total_kepmen <= 60) {
            $data['kategori_kepmen'] = 'CUKUP';
        } elseif ($total_kepmen >= 30 && $total_kepmen <= 45) {
            $data['kategori_kepmen'] = 'KURANG';
        } elseif ($total_kepmen > 0 && $total_kepmen <= 30) {
            $data['kategori_kepmen'] = 'TIDAK BAIK';
        } elseif ($total_kepmen == 0) {
            $data['kategori_kepmen'] = 'NO DATA';
        }

        if ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('dashboard/view_dashboard_publik', $data);
            $this->load->view('templates/footer');
        } else {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('dashboard/view_dashboard_publik', $data);
            $this->load->view('templates/footer');
        }
    }
}
