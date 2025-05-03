<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Evkin_pupr extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_labarugi');
        $this->load->model('Model_lap_keuangan');
        $this->load->model('Model_langgan');
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

        $bobot = 0.055;
        $bobot_solva = 0.030;
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

        // hitung rasio operasi
        $data['pendapatan_usaha'] = $total_pendapatan_usaha_audited;
        $data['beban_usaha'] = $total_beban_usaha_audited + $total_beban_umum_administrasi_audited;



        if (isset($data['beban_usaha']) && isset($data['pendapatan_usaha']) && $data['beban_usaha'] != 0 && $data['pendapatan_usaha'] != 0) {
            $data['persen_rasio_ops'] = $data['beban_usaha'] / $data['pendapatan_usaha'];
        } else {
            $data['persen_rasio_ops'] = 0;
        }

        $persen_rasio_ops = $data['persen_rasio_ops'];

        $hasil_perhitungan_rasio_ops = 0;
        if ($persen_rasio_ops < 0) {
            $hasil_perhitungan_rasio_ops = 1;
        } elseif ($persen_rasio_ops <= 3) {
            $hasil_perhitungan_rasio_ops = 2;
        } elseif ($persen_rasio_ops <= 7) {
            $hasil_perhitungan_rasio_ops = 3;
        } elseif ($persen_rasio_ops <= 10) {
            $hasil_perhitungan_rasio_ops = 4;
        } else {
            $hasil_perhitungan_rasio_ops = 5;
        }
        $data['hasil_perhitungan_rasio_ops'] = $hasil_perhitungan_rasio_ops;
        $data['hasil_rasio_ops'] = $hasil_perhitungan_rasio_ops * $bobot;


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
        $data['hasil_roe'] = $hasil_perhitungan_roe * $bobot;

        // hitung cash Rasio
        $data['hutang_lancar'] = $total_liabilitas_jangka_pendek_audited;
        $data['total_kas_bank'] = $this->Model_lap_keuangan->get_kas_dan_bank_by_tahun($tahun);

        if (isset($data['total_kas_bank']) && isset($data['hutang_lancar']) && $data['total_kas_bank'] != 0 && $data['hutang_lancar'] != 0) {
            $data['persen_cash_rasio'] = $data['total_kas_bank'] / $data['hutang_lancar'] * 100;
        } else {
            $data['persen_cash_rasio'] = 0;
        }
        $persen_cash_rasio = $data['persen_cash_rasio'];

        $hasil_perhitungan_cash_rasio = 0;
        if ($persen_cash_rasio < 40) {
            $hasil_perhitungan_cash_rasio = 1;
        } elseif ($persen_cash_rasio >= 40 && $persen_cash_rasio < 60) {
            $hasil_perhitungan_cash_rasio = 2;
        } elseif ($persen_cash_rasio >= 60 && $persen_cash_rasio < 80) {
            $hasil_perhitungan_cash_rasio = 3;
        } elseif ($persen_cash_rasio >= 80 && $persen_cash_rasio < 100) {
            $hasil_perhitungan_cash_rasio = 4;
        } else {
            $hasil_perhitungan_cash_rasio = 5;
        }
        $data['hasil_perhitungan_cash_rasio'] = $hasil_perhitungan_cash_rasio;
        $data['hasil_cash_rasio'] = $hasil_perhitungan_cash_rasio * $bobot;

        // hitung efektifitas penagihan
        $data['efek'] = $this->Model_langgan->get_efek_tagih($tahun);
        $data['sisa_piu'] = $this->Model_langgan->get_sisa_piu($tahun);
        $data['bagian_upk'] = $this->db->where('status_evkin', 1)->get('bagian_upk')->result();

        $kategori = [
            '1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr',
            '5' => 'Mei', '6' => 'Jun', '7' => 'Jul', '8' => 'Ags',
            '9' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
        ];

        $data_rinci = [];
        foreach ($data['bagian_upk'] as $upk) {
            $nama_upk = $upk->nama_bagian;
            $data_rinci[$nama_upk] = [];
            foreach (array_keys($kategori) as $bulan) {
                $data_rinci[$nama_upk][$bulan] = ['sr' => 0, 'rp' => 0];
            }
        }

        foreach ($data['efek'] as $row) {
            $upk = $row->nama_bagian;
            $bulan = (string) $row->bulan_data;

            if (isset($data_rinci[$upk][$bulan])) {
                $data_rinci[$upk][$bulan]['sr'] += $row->jumlah_sr;
                $data_rinci[$upk][$bulan]['rp'] += $row->rupiah;
            }
        }

        $total_keseluruhan = [];
        foreach (array_keys($kategori) as $bulan) {
            $total_keseluruhan[$bulan] = ['sr' => 0, 'rp' => 0];
        }
        $total_keseluruhan['JUMLAH'] = ['sr' => 0, 'rp' => 0];

        foreach ($data_rinci as $item) {
            foreach (array_keys($kategori) as $bulan) {
                $total_keseluruhan[$bulan]['sr'] += $item[$bulan]['sr'];
                $total_keseluruhan[$bulan]['rp'] += $item[$bulan]['rp'];
            }
        }

        foreach (array_keys($kategori) as $bulan) {
            $total_keseluruhan['JUMLAH']['sr'] += $total_keseluruhan[$bulan]['sr'];
            $total_keseluruhan['JUMLAH']['rp'] += $total_keseluruhan[$bulan]['rp'];
        }
        $data['total_rp'] = $total_keseluruhan['JUMLAH']['rp'];

        $ppa_input = $this->Model_labarugi->get_ppa_input($tahun);
        $data['ppa_input'] = $ppa_input;

        $total_pa_tahun_ini = 0;

        if (!empty($ppa_input)) {
            foreach ($ppa_input as $row) {
                $total_pa_tahun_ini += $row->jumlah_pa_tahun_ini;
            }
        }

        $data_piu = [
            '1 Bulan' => 0,
            '2 Bulan' => 0,
            '3 Bulan' => 0,
            '4 Bulan - 1 Tahun' => 0,
        ];

        foreach ($data['sisa_piu'] as $row) {
            if (isset($data_piu[$row->uraian])) {
                $data_piu[$row->uraian] = $row->rupiah;
            }
        }
        $total = array_sum($data_piu);

        $data['total_pa_tahun_ini'] = $total_pa_tahun_ini;
        $data['sisa_rek'] = $total;
        $data['rek_tagih'] = $data['total_pa_tahun_ini'] - $data['sisa_rek'] + $data['total_rp'];
        $data['persen_efek'] = ($data['total_pa_tahun_ini'] == 0) ? 0 : round(($data['rek_tagih'] / $data['total_pa_tahun_ini']) * 100, 2);

        $persen_efek = $data['persen_efek'];

        $hasil_perhitungan_efek = 0;
        if ($persen_efek <= 75) {
            $hasil_perhitungan_efek = 1;
        } elseif ($persen_efek > 75 && $persen_efek <= 80) {
            $hasil_perhitungan_efek = 2;
        } elseif ($persen_efek > 80 && $persen_efek <= 85) {
            $hasil_perhitungan_efek = 3;
        } elseif ($persen_efek > 85 && $persen_efek <= 90) {
            $hasil_perhitungan_efek = 4;
        } elseif ($persen_efek > 90) {
            $hasil_perhitungan_efek = 5;
        }
        $data['hasil_perhitungan_efek'] = $hasil_perhitungan_efek;
        $data['hasil_efek'] = $hasil_perhitungan_efek * $bobot;

        // hitung solvabilitas
        $data['total_asset'] = $total_aset_lancar_audited + $total_aset_tidak_lancar_audited;
        $data['total_utang'] = $total_liabilitas_jangka_pendek_audited + $total_liabilitas_jangka_panjang_audited;

        if (isset($data['total_asset']) && isset($data['total_utang']) && $data['total_asset'] != 0 && $data['total_utang'] != 0) {
            $data['persen_solva'] = $data['total_asset'] / $data['total_utang'] * 100;
        } else {
            $data['persen_solva'] = 0;
        }
        $persen_solva = $data['persen_solva'];

        $hasil_perhitungan_solva = 0;
        if ($persen_solva < 100) {
            $hasil_perhitungan_solva = 1;
        } elseif ($persen_solva >= 100 && $persen_solva < 135) {
            $hasil_perhitungan_solva = 2;
        } elseif ($persen_solva >= 135 && $persen_solva < 170) {
            $hasil_perhitungan_solva = 3;
        } elseif ($persen_solva >= 170 && $persen_solva < 200) {
            $hasil_perhitungan_solva = 4;
        } elseif ($persen_solva >= 200) {
            $hasil_perhitungan_solva = 5;
        }
        $data['hasil_perhitungan_solva'] = $hasil_perhitungan_solva;
        $data['hasil_solva'] = $hasil_perhitungan_solva * $bobot_solva;
        $data['total_hasil_keuangan'] = $data['hasil_roe'] + $data['hasil_rasio_ops'] + $data['hasil_cash_rasio'] + $data['hasil_efek'] + $data['hasil_solva'];

        if ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('dashboard/view_evkin_pupr', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('dashboard/view_evkin_pupr', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Perencanaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_rencana');
            $this->load->view('dashboard/view_evkin_pupr', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Pemeliharaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('dashboard/view_evkin_pupr', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Umum') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_umum');
            $this->load->view('dashboard/view_evkin_pupr', $data);
            $this->load->view('templates/footer');
        } else {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('dashboard/view_evkin_pupr', $data);
            $this->load->view('templates/footer');
        }
    }
}
