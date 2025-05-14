<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Evkin_pupr extends CI_Controller
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
        $data['tahun_lalu'] = $tahun - 1;
        $data['title'] = 'Penilaian Tingkat Kesehatan Tahun ' . $tahun . ' menurut indikator KemenPUPR';

        // aspek keuangan
        $laba_rugi = $this->Model_evkin->hitung_laba_rugi_bersih($tahun);
        $data['laba_rugi_bersih'] = $laba_rugi['laba_rugi_bersih'];
        $data['pendapatan_usaha'] = $laba_rugi['pendapatan_usaha'];
        $data['beban_usaha'] = $laba_rugi['beban_usaha'];
        $data['persen_rasio_ops'] = $laba_rugi['persen_rasio_ops'];
        $data['hasil_perhitungan_rasio_ops'] = $laba_rugi['hasil_perhitungan_rasio_ops'];
        $data['hasil_rasio_ops'] = $laba_rugi['hasil_rasio_ops'];
        $data['total_ekuitas_audited'] = $laba_rugi['total_ekuitas_audited'];
        $data['persen_roe'] = $laba_rugi['persen_roe'];
        $data['hasil_perhitungan_roe'] = $laba_rugi['hasil_perhitungan_roe'];
        $data['hasil_roe'] = $laba_rugi['hasil_roe'];
        $data['persen_cash_rasio'] = $laba_rugi['persen_cash_rasio'];
        $data['hasil_perhitungan_cash_rasio'] = $laba_rugi['hasil_perhitungan_cash_rasio'];
        $data['hasil_cash_rasio'] = $laba_rugi['hasil_cash_rasio'];
        $data['total_kas_bank'] = $laba_rugi['total_kas_bank'];
        $data['hutang_lancar'] = $laba_rugi['hutang_lancar'];
        $data['persen_efek'] = $laba_rugi['persen_efek'];
        $data['hasil_perhitungan_efek'] = $laba_rugi['hasil_perhitungan_efek'];
        $data['hasil_efek'] = $laba_rugi['hasil_efek'];
        $data['persen_solva'] = $laba_rugi['persen_solva'];
        $data['hasil_perhitungan_solva'] = $laba_rugi['hasil_perhitungan_solva'];
        $data['hasil_solva'] = $laba_rugi['hasil_solva'];
        $data['rek_tagih'] = $laba_rugi['rek_tagih'];
        $data['total_pa_tahun_ini'] = $laba_rugi['total_pa_tahun_ini'];
        $data['total_asset'] = $laba_rugi['total_asset'];
        $data['total_utang'] = $laba_rugi['total_utang'];
        $data['total_hasil_keuangan'] = $laba_rugi['total_hasil_keuangan'];
        // akhir aspek keuangan

        // aspek pelayanan
        // cakupan teknis
        $pelayanan = $this->Model_evkin->hitung_pelayanan($tahun);
        $data['total_jiwa_terlayani2'] = $pelayanan['total_jiwa_terlayani2'];
        $data['total_wil_layan'] = $pelayanan['total_wil_layan'];
        $data['persen_cak_teknis'] = $pelayanan['persen_cak_teknis'];
        $data['hasil_perhitungan_cak_teknis'] = $pelayanan['hasil_perhitungan_cak_teknis'];
        $data['hasil_cak_teknis'] = $pelayanan['hasil_cak_teknis'];

        // pengaduan
        $pengaduan = $this->Model_evkin->hitung_pengaduan($tahun);
        $data['jumlah_keluhan_selesai'] = $pengaduan['jumlah_keluhan_selesai'];
        $data['jumlah_keluhan'] = $pengaduan['jumlah_keluhan'];
        $data['persen_pengaduan'] = $pengaduan['persen_pengaduan'];
        $data['hasil_perhitungan_pengaduan'] = $pengaduan['hasil_perhitungan_pengaduan'];
        $data['hasil_pengaduan'] = $pengaduan['hasil_pengaduan'];

        // kualitas air
        $kualitas = $this->Model_evkin->hitung_kualitas_air($tahun);
        $data['total_jumlah_syarat'] = $kualitas['total_jumlah_syarat'];
        $data['total_jumlah_terambil'] = $kualitas['total_jumlah_terambil'];
        $data['persen_kualitas'] = $kualitas['persen_kualitas'];
        $data['hasil_perhitungan_kualitas'] = $kualitas['hasil_perhitungan_kualitas'];
        $data['hasil_kualitas'] = $kualitas['hasil_kualitas'];

        // jumlah pelanggan
        $pelanggan = $this->Model_evkin->hitung_pelanggan($tahun);
        $data['jumlah_pelanggan'] = $pelanggan['jumlah_pelanggan'];
        $data['jumlah_pelanggan_tahun_lalu'] = $pelanggan['jumlah_pelanggan_tahun_lalu'];
        $data['persen_pelanggan'] = $pelanggan['persen_pelanggan'];
        $data['hasil_perhitungan_pelanggan'] = $pelanggan['hasil_perhitungan_pelanggan'];
        $data['hasil_pelanggan'] = $pelanggan['hasil_pelanggan'];
        $data['total_pelanggan_tahun_ini'] = $pelanggan['total_pelanggan_tahun_ini'];

        // jumlah air domestik
        $air_dom = $this->Model_evkin->hitung_air_domestik($tahun);
        $data['jumlah_air_terjual'] = $air_dom['jumlah_air_terjual'];
        $data['jumlah_pelanggan_dom'] = $air_dom['jumlah_pelanggan_dom'];
        $data['persen_air_dom'] = $air_dom['persen_air_dom'];
        $data['hasil_perhitungan_air_dom'] = $air_dom['hasil_perhitungan_air_dom'];
        $data['hasil_air_dom'] = $air_dom['hasil_air_dom'];

        $data['total_hasil_pelayanan'] = $data['hasil_air_dom'] + $data['hasil_pelanggan'] + $data['hasil_kualitas'] + $data['hasil_pengaduan'] + $data['hasil_cak_teknis'];
        // akhir aspek pelayanan

        // aspek operasioanl
        // efisiensi produksi
        $kap_prod = $this->Model_evkin->hitung_efisiensi_prod($tahun);
        $data['total_volume_produksi'] = $kap_prod['total_volume_produksi'];
        $data['total_terpasang'] = $kap_prod['total_terpasang'];
        $data['persen_kap_prod'] = $kap_prod['persen_kap_prod'];
        $data['hasil_kap_prod'] = $kap_prod['hasil_kap_prod'];
        $data['hasil_perhitungan_kap_prod'] = $kap_prod['hasil_perhitungan_kap_prod'];

        // tekanan air
        $tekanan_air = $this->Model_evkin->hitung_tekanan_air($tahun);
        $data['jumlah_pelanggan_dilayani'] = $tekanan_air['jumlah_pelanggan_dilayani'];
        $data['total_pelanggan'] = $tekanan_air['total_pelanggan'];
        $data['persen_tekanan_air'] = $tekanan_air['persen_tekanan_air'];
        $data['hasil_tekanan_air'] = $tekanan_air['hasil_tekanan_air'];
        $data['hasil_perhitungan_tekanan_air'] = $tekanan_air['hasil_perhitungan_tekanan_air'];

        // ganti meter
        $ganti_meter = $this->Model_evkin->hitung_ganti_meter($tahun);
        $data['total_semua_meter'] = $ganti_meter['total_semua_meter'];
        $data['total_pelanggan'] = $ganti_meter['total_pelanggan'];
        $data['persen_ganti_meter'] = $ganti_meter['persen_ganti_meter'];
        $data['hasil_ganti_meter'] = $ganti_meter['hasil_ganti_meter'];
        $data['hasil_perhitungan_ganti_meter'] = $ganti_meter['hasil_perhitungan_ganti_meter'];

        // pendapatan
        $pendapatan = $this->Model_evkin->hitung_pendapatan($tahun);
        $data['total_vol'] = $pendapatan['total_vol'];
        $data['volume_produksi'] = $pendapatan['volume_produksi'];
        $data['total_dis_vol_prod'] = $pendapatan['total_dis_vol_prod'];
        $data['persen_nrw'] = $pendapatan['persen_nrw'];
        $data['hasil_nrw'] = $pendapatan['hasil_nrw'];
        $data['hasil_perhitungan_nrw'] = $pendapatan['hasil_perhitungan_nrw'];

        // jam operasional
        $jam_ops = $this->Model_evkin->hitung_jam_ops($tahun);
        $data['total_jam_ops'] = $jam_ops['total_jam_ops'];
        $data['jam_ops_setahun'] = $jam_ops['jam_ops_setahun'];
        $data['persen_jam_ops'] = $jam_ops['persen_jam_ops'];
        $data['hasil_jam_ops'] = $jam_ops['hasil_jam_ops'];
        $data['hasil_perhitungan_jam_ops'] = $jam_ops['hasil_perhitungan_jam_ops'];

        $data['total_hasil_operasional'] = $data['hasil_kap_prod'] + $data['hasil_tekanan_air'] + $data['hasil_ganti_meter'] + $data['hasil_nrw'] + $data['hasil_jam_ops'];
        // akhir aspek operasional

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
