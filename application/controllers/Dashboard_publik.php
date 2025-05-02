<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_publik extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_labarugi');
        $this->load->model('Model_lap_keuangan');
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
        $data['tahun_lalu'] = $tahun - 1;
        $data['title'] = 'Penilaian Tingkat Kesehatan Tahun ' . $tahun . ' menurut indikator KemenPUPR';

        $data['lr_sak_ep'] = $this->Model_labarugi->get_all_sak_ep($tahun);
        $data['neraca'] = $this->Model_lap_keuangan->get_all_neraca($tahun);

        // perhitungan laba rugi
        $total_pendapatan_usaha = $total_beban_usaha = 0;
        $total_beban_umum_administrasi = $total_pendapatan_beban_lain = $total_beban_pajak_penghasilan = $total_penghasilan_komprehensif_lain = 0;
        $total_labarugi_operasional = 0;
        $total_pendapatan_usaha_audited = $total_beban_usaha_audited = 0;
        $total_beban_umum_administrasi_audited = $total_pendapatan_beban_lain_audited = $total_beban_pajak_penghasilan_audited = $total_penghasilan_komprehensif_lain_audited = 0;
        $total_labarugi_operasional_audited = 0;
        $total_pendapatan_usaha_lalu = $total_beban_usaha_lalu = 0;
        $total_beban_umum_administrasi_lalu = $total_pendapatan_beban_lain_lalu = $total_beban_pajak_penghasilan_lalu = $total_penghasilan_komprehensif_lain_lalu = 0;
        $total_labarugi_operasional_lalu = 0;

        $data_tahun_lalu = [];
        $data_tahun_sekarang = [];
        $data_tahun_sekarang_audited = [];

        foreach ($data['lr_sak_ep'] as $row) {
            if ($row->tahun_lr_sak_ep == $data['tahun_lalu']) {
                $data_tahun_lalu[$row->akun] = $row->nilai_lr_sak_ep;
            }
            if ($row->tahun_lr_sak_ep == $data['tahun_lap']) {
                $data_tahun_sekarang[] = $row;
                $data_tahun_sekarang_audited[] = $row;
            }
        }

        foreach ($data_tahun_sekarang as $row) {
            $nilai_tahun_lalu = isset($data_tahun_lalu[$row->akun]) ? $data_tahun_lalu[$row->akun] : 0;

            if ($row->kategori == 'Pendapatan Usaha') {
                $total_pendapatan_usaha += $row->nilai_lr_sak_ep ?? 0;
                $total_pendapatan_usaha_audited += $row->nilai_lr_sak_ep_audited ?? 0;
                $total_pendapatan_usaha_lalu += $nilai_tahun_lalu ?? 0;
            } elseif ($row->kategori == 'Beban Usaha') {
                $total_beban_usaha += $row->nilai_lr_sak_ep ?? 0;
                $total_beban_usaha_audited += $row->nilai_lr_sak_ep_audited ?? 0;
                $total_beban_usaha_lalu += $nilai_tahun_lalu ?? 0;
            } elseif ($row->kategori == 'Beban Umum Dan Administrasi') {
                $total_beban_umum_administrasi += $row->nilai_lr_sak_ep ?? 0;
                $total_beban_umum_administrasi_audited += $row->nilai_lr_sak_ep_audited ?? 0;
                $total_beban_umum_administrasi_lalu += $nilai_tahun_lalu ?? 0;
            } elseif ($row->kategori == 'Pendapatan - Beban Lain-lain') {
                $total_pendapatan_beban_lain += $row->nilai_lr_sak_ep ?? 0;
                $total_pendapatan_beban_lain_audited += $row->nilai_lr_sak_ep_audited ?? 0;
                $total_pendapatan_beban_lain_lalu += $nilai_tahun_lalu ?? 0;
            } elseif ($row->kategori == 'Beban Pajak Penghasilan') {
                $total_beban_pajak_penghasilan += $row->nilai_lr_sak_ep ?? 0;
                $total_beban_pajak_penghasilan_audited += $row->nilai_lr_sak_ep_audited ?? 0;
                $total_beban_pajak_penghasilan_lalu += $nilai_tahun_lalu ?? 0;
            } elseif ($row->kategori == '(Kerugian) Penghasilan Komprehensip Lain') {
                $total_penghasilan_komprehensif_lain += $row->nilai_lr_sak_ep ?? 0;
                $total_penghasilan_komprehensif_lain_audited += $row->nilai_lr_sak_ep_audited ?? 0;
                $total_penghasilan_komprehensif_lain_lalu += $nilai_tahun_lalu ?? 0;
            }
        }

        $total_labarugi_kotor_audited = $total_pendapatan_usaha_audited - $total_beban_usaha_audited;
        $total_labarugi_operasional_audited = $total_labarugi_kotor_audited - $total_beban_umum_administrasi_audited;
        $total_labarugi_bersih_Sebelum_pajak_audited = $total_labarugi_operasional_audited + $total_pendapatan_beban_lain_audited;
        $total_penghasilan_komprehensif_tahun_berjalan_audited = $total_labarugi_bersih_Sebelum_pajak_audited - ($total_beban_pajak_penghasilan_audited + $total_penghasilan_komprehensif_lain_audited);
        $data['laba_rugi_bersih'] = $total_penghasilan_komprehensif_tahun_berjalan_audited;

        // perhitungan ekuitas di neraca
        $total_aset_lancar = $total_aset_tidak_lancar = 0;
        $total_liabilitas_jangka_pendek = $total_liabilitas_jangka_panjang = $total_ekuitas = 0;
        $total_aset_lancar_audited = $total_aset_tidak_lancar_audited = 0;
        $total_liabilitas_jangka_pendek_audited = $total_liabilitas_jangka_panjang_audited = $total_ekuitas_audited = 0;
        $total_aset_lancar_lalu = $total_aset_tidak_lancar_lalu = 0;
        $total_liabilitas_jangka_pendek_lalu = $total_liabilitas_jangka_panjang_lalu = $total_ekuitas_lalu = 0;

        $data_tahun_lalu = [];
        $data_tahun_sekarang = [];
        $data_tahun_sekarang_audited = [];

        foreach ($data['neraca'] as $row) {
            if ($row->tahun_neraca == $data['tahun_lalu']) {
                $data_tahun_lalu[$row->akun] = $row->nilai_neraca;
            }
            if ($row->tahun_neraca == $data['tahun_lap']) {
                $data_tahun_sekarang[] = $row;
                $data_tahun_sekarang_audited[] = $row;
            }
        }

        foreach ($data_tahun_sekarang as $row) {
            $nilai_tahun_lalu = isset($data_tahun_lalu[$row->akun]) ? $data_tahun_lalu[$row->akun] : 0;

            if ($row->kategori == 'Aset Lancar') {
                $total_aset_lancar += $row->nilai_neraca ?? 0;
                $total_aset_lancar_audited += $row->nilai_neraca_audited ?? 0;
                $total_aset_lancar_lalu += $nilai_tahun_lalu ?? 0;
            } elseif ($row->kategori == 'Aset Tidak Lancar') {
                $total_aset_tidak_lancar += $row->nilai_neraca ?? 0;
                $total_aset_tidak_lancar_audited += $row->nilai_neraca_audited ?? 0;
                $total_aset_tidak_lancar_lalu += $nilai_tahun_lalu ?? 0;
            } elseif ($row->kategori == 'Liabilitas Jangka Pendek') {
                $total_liabilitas_jangka_pendek += $row->nilai_neraca ?? 0;
                $total_liabilitas_jangka_pendek_audited += $row->nilai_neraca_audited ?? 0;
                $total_liabilitas_jangka_pendek_lalu += $nilai_tahun_lalu ?? 0;
            } elseif ($row->kategori == 'Liabilitas Jangka Panjang') {
                $total_liabilitas_jangka_panjang += $row->nilai_neraca ?? 0;
                $total_liabilitas_jangka_panjang_audited += $row->nilai_neraca_audited ?? 0;
                $total_liabilitas_jangka_panjang_lalu += $nilai_tahun_lalu ?? 0;
            } elseif ($row->kategori == 'Ekuitas') {
                $total_ekuitas += $row->nilai_neraca ?? 0;
                $total_ekuitas_audited += $row->nilai_neraca_audited ?? 0;
                $total_ekuitas_lalu += $nilai_tahun_lalu ?? 0;
            }
        }
        $data['total_ekuitas_audited'] = $total_ekuitas_audited;
        if (isset($data['total_ekuitas_audited']) && $data['total_ekuitas_audited'] != 0) {
            $data['persen_roe'] = $data['laba_rugi_bersih'] / $data['total_ekuitas_audited'] * 100;
        } else {
            $data['persen_roe'] = 0;
        }
        $persen_roe = $data['persen_roe'];

        $hasil_perhitungan_roe = 0;
        if ($persen_roe < 0) {
            $hasil_perhitungan_roe = 1;
        } elseif ($persen_roe <= 3) {
            $hasil_perhitungan_roe = 2;
        } elseif ($persen_roe <= 7) {
            $hasil_perhitungan_roe = 3;
        } elseif ($persen_roe <= 10) {
            $hasil_perhitungan_roe = 4;
        } else {
            $hasil_perhitungan_roe = 5;
        }
        $data['hasil_perhitungan_roe'] = $hasil_perhitungan_roe;
        $bobot = 0.055;
        $data['hasil_roe'] = $hasil_perhitungan_roe * $bobot;

        // hitung rasio operasi


        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar_publik');
        $this->load->view('dashboard/view_dashboard_publik', $data);
        $this->load->view('templates/footer');
    }
}
