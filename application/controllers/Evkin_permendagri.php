<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Evkin_permendagri extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_evkin_dagri');
        $this->load->model('Model_evkin_dagri_ops');
        $this->load->model('Model_evkin_dagri_adm');
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
        $data['title'] = 'Perhitungan Penilaian Kinerja Tahun ' . $tahun . ' Berdasarkan Permendagri No 47 Th 1999';

        // aspek keuangan

        $hitung_laba_rugi = $this->Model_evkin_dagri->hitung_laba_rugi($tahun);
        $lr_sebelum_pajak_ini = $hitung_laba_rugi['tahun_ini']['labarugi_bersih_sebelum_pajak'];
        $lr_sebelum_pajak_lalu = $hitung_laba_rugi['tahun_lalu']['labarugi_bersih_sebelum_pajak'];
        $lr_sebelum_pajak_2_lalu = $hitung_laba_rugi['2tahun_lalu']['labarugi_bersih_sebelum_pajak'];
        $total_pendapatan_usaha_audited_ini = $hitung_laba_rugi['tahun_ini']['total_pendapatan_usaha_audited'];
        $total_pendapatan_usaha_audited_lalu = $hitung_laba_rugi['tahun_lalu']['total_pendapatan_usaha_audited'];
        $total_pendapatan_usaha_audited_2_lalu = $hitung_laba_rugi['2tahun_lalu']['total_pendapatan_usaha_audited'];
        $total_beban_operasi_ini = $hitung_laba_rugi['tahun_ini']['total_beban_operasi'];
        $total_beban_operasi_lalu = $hitung_laba_rugi['tahun_lalu']['total_beban_operasi'];
        $total_beban_operasi_2_lalu = $hitung_laba_rugi['2tahun_lalu']['total_beban_operasi'];

        $hitung_aktiva_produktif = $this->Model_evkin_dagri->hitung_aktiva_produktif($tahun);
        $aktiva_produktif_ini = $hitung_aktiva_produktif['tahun_ini']['aktiva_produktif'];
        $aktiva_produktif_lalu = $hitung_aktiva_produktif['tahun_lalu']['aktiva_produktif'];
        $aktiva_produktif_2_lalu = $hitung_aktiva_produktif['2tahun_lalu']['aktiva_produktif'];

        $hitung_neraca = $this->Model_evkin_dagri->hitung_neraca($tahun);
        $total_aktiva_lancar_audited_ini = $hitung_neraca['tahun_ini']['total_aset_lancar_audited'];
        $total_aktiva_lancar_audited_lalu = $hitung_neraca['tahun_lalu']['total_aset_lancar_audited'];
        $total_aktiva_lancar_audited_2_lalu = $hitung_neraca['2tahun_lalu']['total_aset_lancar_audited'];

        $total_aktiva_ini = $hitung_neraca['tahun_ini']['total_asset'];
        $total_aktiva_lalu = $hitung_neraca['tahun_lalu']['total_asset'];
        $total_aktiva_2_lalu = $hitung_neraca['2tahun_lalu']['total_asset'];

        $total_hutang_ini = $hitung_neraca['tahun_ini']['total_hutang'];
        $total_hutang_lalu = $hitung_neraca['tahun_lalu']['total_hutang'];
        $total_hutang_2_lalu = $hitung_neraca['2tahun_lalu']['total_hutang'];

        $total_liabilitas_jangka_pendek_audited_ini = $hitung_neraca['tahun_ini']['total_liabilitas_jangka_pendek_audited'];
        $total_liabilitas_jangka_pendek_audited_lalu = $hitung_neraca['tahun_lalu']['total_liabilitas_jangka_pendek_audited'];
        $total_liabilitas_jangka_pendek_audited_2_lalu = $hitung_neraca['2tahun_lalu']['total_liabilitas_jangka_pendek_audited'];

        $total_ekuitas_audited_ini = $hitung_neraca['tahun_ini']['total_ekuitas_audited'];
        $total_ekuitas_audited_lalu = $hitung_neraca['tahun_lalu']['total_ekuitas_audited'];
        $total_ekuitas_audited_2_lalu = $hitung_neraca['2tahun_lalu']['total_ekuitas_audited'];

        $hitung_hutang_jk_panjang = $this->Model_evkin_dagri->hitung_hutang_jk_panjang($tahun);
        $total_hutang_jk_panjang_ini = $hitung_hutang_jk_panjang['tahun_ini']['hutang_jk_panjang'];
        $total_hutang_jk_panjang_lalu = $hitung_hutang_jk_panjang['tahun_lalu']['hutang_jk_panjang'];

        $hitung_pendapatan_air_ini = $this->Model_evkin_dagri->hitung_pendapatan_air($tahun);
        $pendapatan_air_ini = $hitung_pendapatan_air_ini['pendapatan_air_tahun_ini'];
        $pendapatan_air_lalu = $hitung_pendapatan_air_ini['pendapatan_air_tahun_lalu'];

        $piutang_usaha = $this->Model_evkin_dagri->hitung_piutang_usaha($tahun);
        $piutang_usaha_ini = $piutang_usaha['tahun_ini']['piutang_usaha'];
        $piutang_usaha_lalu = $piutang_usaha['tahun_lalu']['piutang_usaha'];

        $efektifitas = $this->Model_evkin_dagri->hitung_efektifitas($tahun);
        $rek_tagih_ini = $efektifitas['tahun_ini']['rek_tagih'];
        $rek_tagih_lalu = $efektifitas['tahun_lalu']['rek_tagih'];
        $total_pa_tahun_ini = $efektifitas['tahun_ini']['total_pa'];
        $total_pa_tahun_lalu = $efektifitas['tahun_lalu']['total_pa'];
        $persen_efek_ini = $efektifitas['tahun_ini']['persen_efek'];
        $persen_efek_lalu = $efektifitas['tahun_lalu']['persen_efek'];
        $hasil_efek_ini = $efektifitas['tahun_ini']['hasil_efek'];
        $hasil_efek_lalu = $efektifitas['tahun_lalu']['hasil_efek'];

        $lr_sbl_pajak = $this->Model_evkin_dagri->hitung_lr_sbl_susut($tahun);
        $total_laba_sebelum_susut_ini =  $lr_sbl_pajak['tahun_ini']['total_laba_sebelum_susut'];
        $total_laba_sebelum_susut_lalu =  $lr_sbl_pajak['tahun_lalu']['total_laba_sebelum_susut'];
        $rasio_laba_sbl_susut_ini =  $lr_sbl_pajak['tahun_ini']['rasio_laba_sbl_susut'];
        $rasio_laba_sbl_susut_lalu =  $lr_sbl_pajak['tahun_lalu']['rasio_laba_sbl_susut'];
        $hasil_perhitungan_rasio_laba_sbl_susut_ini =  $lr_sbl_pajak['tahun_ini']['hasil_perhitungan_rasio_laba_sbl_susut'];
        $hasil_perhitungan_rasio_laba_sbl_susut_lalu =  $lr_sbl_pajak['tahun_lalu']['hasil_perhitungan_rasio_laba_sbl_susut'];


        $data['lr_sebelum_pajak_ini'] = $hitung_laba_rugi['tahun_ini']['labarugi_bersih_sebelum_pajak'];
        $data['lr_sebelum_pajak_lalu'] = $hitung_laba_rugi['tahun_lalu']['labarugi_bersih_sebelum_pajak'];
        $data['aktiva_produktif_ini'] = $hitung_aktiva_produktif['tahun_ini']['aktiva_produktif'];
        $data['aktiva_produktif_lalu'] = $hitung_aktiva_produktif['tahun_lalu']['aktiva_produktif'];

        $data['rasio_laba_ini'] = $this->Model_evkin_dagri->hitung_rasio_laba($tahun)['tahun_ini']['rasio_laba_ini'];
        $data['rasio_laba_lalu'] = $this->Model_evkin_dagri->hitung_rasio_laba($tahun)['tahun_lalu']['rasio_laba_lalu'];
        $data['rasio_laba_2_lalu'] = $this->Model_evkin_dagri->hitung_rasio_laba($tahun)['tahun_2_lalu']['rasio_laba_2_lalu'];

        $data['hasil_perhitungan_rasio_laba_ini'] = $this->Model_evkin_dagri->hitung_rasio_laba($tahun)['tahun_ini']['hasil_perhitungan_rasio_laba_ini'];
        $data['hasil_perhitungan_rasio_laba_lalu'] = $this->Model_evkin_dagri->hitung_rasio_laba($tahun)['tahun_lalu']['hasil_perhitungan_rasio_laba_lalu'];

        $data['bonus_rasio_laba_ini'] = $this->Model_evkin_dagri->hitung_bonus_rasio_laba($tahun)['tahun_ini']['bonus_rasio_laba_ini'];
        $data['bonus_rasio_laba_lalu'] = $this->Model_evkin_dagri->hitung_bonus_rasio_laba($tahun)['tahun_lalu']['bonus_rasio_laba_lalu'];
        $data['hasil_perhitungan_bonus_rasio_laba_ini'] = $this->Model_evkin_dagri->hitung_bonus_rasio_laba($tahun)['tahun_ini']['hasil_perhitungan_bonus_rasio_laba_ini'];
        $data['hasil_perhitungan_bonus_rasio_laba_lalu'] = $this->Model_evkin_dagri->hitung_bonus_rasio_laba($tahun)['tahun_lalu']['hasil_perhitungan_bonus_rasio_laba_lalu'];

        $data['total_pendapatan_usaha_audited_ini'] = $hitung_laba_rugi['tahun_ini']['total_pendapatan_usaha_audited'];
        $data['total_pendapatan_usaha_audited_lalu'] = $hitung_laba_rugi['tahun_lalu']['total_pendapatan_usaha_audited'];
        $data['hasil_perhitungan_rasio_laba_jual_ini'] = $this->Model_evkin_dagri->hitung_rasio_laba_jual($tahun)['tahun_ini']['hasil_perhitungan_rasio_laba_jual_ini'];
        $data['hasil_perhitungan_rasio_laba_jual_lalu'] = $this->Model_evkin_dagri->hitung_rasio_laba_jual($tahun)['tahun_lalu']['hasil_perhitungan_rasio_laba_jual_lalu'];

        $data['rasio_laba_jual_ini'] = $this->Model_evkin_dagri->hitung_rasio_laba_jual($tahun)['tahun_ini']['rasio_laba_jual_ini'];
        $data['rasio_laba_jual_lalu'] = $this->Model_evkin_dagri->hitung_rasio_laba_jual($tahun)['tahun_lalu']['rasio_laba_jual_lalu'];
        $data['rasio_laba_jual_2_lalu'] = $this->Model_evkin_dagri->hitung_rasio_laba_jual($tahun)['tahun_2_lalu']['rasio_laba_jual_2_lalu'];

        $data['bonus_rasio_laba_jual_ini'] = $this->Model_evkin_dagri->hitung_bonus_rasio_laba_jual($tahun)['tahun_ini']['bonus_rasio_laba_jual_ini'];
        $data['bonus_rasio_laba_jual_lalu'] = $this->Model_evkin_dagri->hitung_bonus_rasio_laba_jual($tahun)['tahun_lalu']['bonus_rasio_laba_jual_lalu'];
        $data['hasil_perhitungan_bonus_rasio_laba_jual_ini'] = $this->Model_evkin_dagri->hitung_bonus_rasio_laba_jual($tahun)['tahun_ini']['hasil_perhitungan_bonus_rasio_laba_jual_ini'];
        $data['hasil_perhitungan_bonus_rasio_laba_jual_lalu'] = $this->Model_evkin_dagri->hitung_bonus_rasio_laba_jual($tahun)['tahun_lalu']['hasil_perhitungan_bonus_rasio_laba_jual_lalu'];

        $data['total_aset_lancar_audited_ini'] = $hitung_neraca['tahun_ini']['total_aset_lancar_audited'];
        $data['total_aset_lancar_audited_lalu'] = $hitung_neraca['tahun_lalu']['total_aset_lancar_audited'];
        $data['total_liabilitas_jangka_pendek_audited_ini'] = $hitung_neraca['tahun_ini']['total_liabilitas_jangka_pendek_audited'];
        $data['total_liabilitas_jangka_pendek_audited_lalu'] = $hitung_neraca['tahun_lalu']['total_liabilitas_jangka_pendek_audited'];
        $data['rasio_aktiva_lancar_ini'] = $this->Model_evkin_dagri->hitung_rasio_aktiva_lancar($tahun)['tahun_ini']['rasio_aktiva_lancar_ini'];
        $data['rasio_aktiva_lancar_lalu'] = $this->Model_evkin_dagri->hitung_rasio_aktiva_lancar($tahun)['tahun_lalu']['rasio_aktiva_lancar_lalu'];
        $data['hasil_perhitungan_rasio_aktiva_lancar_ini'] = $this->Model_evkin_dagri->hitung_rasio_aktiva_lancar($tahun)['tahun_ini']['hasil_perhitungan_rasio_aktiva_lancar_ini'];
        $data['hasil_perhitungan_rasio_aktiva_lancar_lalu'] = $this->Model_evkin_dagri->hitung_rasio_aktiva_lancar($tahun)['tahun_lalu']['hasil_perhitungan_rasio_aktiva_lancar_lalu'];
        $data['total_ekuitas_audited_ini'] = $hitung_neraca['tahun_ini']['total_ekuitas_audited'];
        $data['total_ekuitas_audited_lalu'] = $hitung_neraca['tahun_lalu']['total_ekuitas_audited'];
        $data['total_hutang_jk_panjang_ini'] = $hitung_hutang_jk_panjang['tahun_ini']['hutang_jk_panjang'];
        $data['total_hutang_jk_panjang_lalu'] = $hitung_hutang_jk_panjang['tahun_lalu']['hutang_jk_panjang'];
        $data['rasio_hutang_jk_panjang_ini'] = $this->Model_evkin_dagri->hitung_rasio_hutang_jk_panjang($tahun)['tahun_ini']['rasio_hutang_jk_panjang'];
        $data['rasio_hutang_jk_panjang_lalu'] = $this->Model_evkin_dagri->hitung_rasio_hutang_jk_panjang($tahun)['tahun_lalu']['rasio_hutang_jk_panjang'];
        $data['hasil_Perhitungan_rasio_hutang_jk_panjang_ini'] = $this->Model_evkin_dagri->hitung_rasio_hutang_jk_panjang($tahun)['tahun_ini']['hasil_Perhitungan_rasio_hutang_jk_panjang'];
        $data['hasil_Perhitungan_rasio_hutang_jk_panjang_lalu'] = $this->Model_evkin_dagri->hitung_rasio_hutang_jk_panjang($tahun)['tahun_lalu']['hasil_Perhitungan_rasio_hutang_jk_panjang'];
        $data['total_aktiva_ini'] = $total_aktiva_ini;
        $data['total_aktiva_lalu'] = $total_aktiva_lalu;
        $data['total_hutang_ini'] = $total_hutang_ini;
        $data['total_hutang_lalu'] = $total_hutang_lalu;
        $data['rasio_aktiva_ini'] = $this->Model_evkin_dagri->hitung_rasio_aktiva($tahun)['tahun_ini']['rasio_aktiva'];
        $data['rasio_aktiva_lalu'] = $this->Model_evkin_dagri->hitung_rasio_aktiva($tahun)['tahun_lalu']['rasio_aktiva'];
        $data['hasil_perhitungan_rasio_aktiva_ini'] = $this->Model_evkin_dagri->hitung_rasio_aktiva($tahun)['tahun_ini']['hasil_perhitungan_rasio_aktiva'];
        $data['hasil_perhitungan_rasio_aktiva_lalu'] = $this->Model_evkin_dagri->hitung_rasio_aktiva($tahun)['tahun_lalu']['hasil_perhitungan_rasio_aktiva'];
        $data['total_beban_operasi_ini'] = $total_beban_operasi_ini;
        $data['total_beban_operasi_lalu'] = $total_beban_operasi_lalu;
        $data['rasio_beban_operasi_ini'] = $this->Model_evkin_dagri->hitung_rasio_beban_operasi($tahun)['tahun_ini']['rasio_beban_operasi'];
        $data['rasio_beban_operasi_lalu'] = $this->Model_evkin_dagri->hitung_rasio_beban_operasi($tahun)['tahun_lalu']['rasio_beban_operasi'];
        $data['hasil_perhitungan_rasio_beban_operasi_ini'] = $this->Model_evkin_dagri->hitung_rasio_beban_operasi($tahun)['tahun_ini']['hasil_perhitungan_rasio_beban_operasi'];
        $data['hasil_perhitungan_rasio_beban_operasi_lalu'] = $this->Model_evkin_dagri->hitung_rasio_beban_operasi($tahun)['tahun_lalu']['hasil_perhitungan_rasio_beban_operasi'];
        $data['pendapatan_air_ini'] = $pendapatan_air_ini;
        $data['pendapatan_air_lalu'] = $pendapatan_air_lalu;
        $data['rasio_pendapatan_air_ini'] = $this->Model_evkin_dagri->hitung_rasio_aktiva_penjualan($tahun)['tahun_ini']['rasio_aktiva_produktif_ini'];
        $data['rasio_pendapatan_air_lalu'] = $this->Model_evkin_dagri->hitung_rasio_aktiva_penjualan($tahun)['tahun_lalu']['rasio_aktiva_produktif_lalu'];
        $data['hasil_perhitungan_rasio_aktiva_produktif_ini'] = $this->Model_evkin_dagri->hitung_rasio_aktiva_penjualan($tahun)['tahun_ini']['hasil_perhitungan_rasio_aktiva_produktif_ini'];
        $data['hasil_perhitungan_rasio_aktiva_produktif_lalu'] = $this->Model_evkin_dagri->hitung_rasio_aktiva_penjualan($tahun)['tahun_lalu']['hasil_perhitungan_rasio_aktiva_produktif_lalu'];
        $data['piutang_usaha_ini'] = $piutang_usaha_ini;
        $data['piutang_usaha_lalu'] = $piutang_usaha_lalu;
        $data['jangka_waktu_tagih_ini'] = $this->Model_evkin_dagri->hitung_jangka_waktu_tagih($tahun)['tahun_ini']['jangka_waktu_tagih'];
        $data['jangka_waktu_tagih_lalu'] = $this->Model_evkin_dagri->hitung_jangka_waktu_tagih($tahun)['tahun_lalu']['jangka_waktu_tagih'];
        $data['hasil_perhitungan_penagihan_ini'] = $this->Model_evkin_dagri->hitung_jangka_waktu_tagih($tahun)['tahun_ini']['hasil_perhitungan_penagihan'];
        $data['hasil_perhitungan_penagihan_lalu'] = $this->Model_evkin_dagri->hitung_jangka_waktu_tagih($tahun)['tahun_lalu']['hasil_perhitungan_penagihan'];
        $data['persen_efek_ini'] = $persen_efek_ini;
        $data['persen_efek_lalu'] = $persen_efek_lalu;
        $data['hasil_efek_ini'] = $hasil_efek_ini;
        $data['hasil_efek_lalu'] = $hasil_efek_lalu;
        $data['rek_tagih_ini'] = $rek_tagih_ini;
        $data['rek_tagih_lalu'] = $rek_tagih_lalu;
        $data['total_pa_tahun_ini'] = $total_pa_tahun_ini;
        $data['total_pa_tahun_lalu'] = $total_pa_tahun_lalu;

        $data['total_laba_sebelum_susut_ini'] = $total_laba_sebelum_susut_ini;
        $data['total_laba_sebelum_susut_lalu'] = $total_laba_sebelum_susut_lalu;
        $data['rasio_laba_sbl_susut_ini'] = $rasio_laba_sbl_susut_ini;
        $data['rasio_laba_sbl_susut_lalu'] = $rasio_laba_sbl_susut_lalu;
        $data['hasil_perhitungan_rasio_laba_sbl_susut_ini'] = $hasil_perhitungan_rasio_laba_sbl_susut_ini;
        $data['hasil_perhitungan_rasio_laba_sbl_susut_lalu'] = $hasil_perhitungan_rasio_laba_sbl_susut_lalu;

        $data['total_hasil_keuangan_ini'] = $this->Model_evkin_dagri->hitung_nilai_keuangan($tahun)['tahun_ini']['total_hasil_keuangan_ini'];
        $data['total_hasil_keuangan_lalu'] = $this->Model_evkin_dagri->hitung_nilai_keuangan($tahun)['tahun_lalu']['total_hasil_keuangan_lalu'];

        $data['nilai_kinerja_keuangan_ini'] = $this->Model_evkin_dagri->hitung_nilai_keuangan($tahun)['tahun_ini']['nilai_kinerja_keuangan_ini'];
        $data['nilai_kinerja_keuangan_lalu'] = $this->Model_evkin_dagri->hitung_nilai_keuangan($tahun)['tahun_lalu']['nilai_kinerja_keuangan_lalu'];

        // akhir aspek keuangan

        // aspek operasional
        $data['jumlah_penduduk_ini'] = $this->Model_evkin_dagri_ops->hitung_cak_layanan($tahun)['tahun_ini']['jumlah_penduduk'];
        $data['jumlah_penduduk_lalu'] = $this->Model_evkin_dagri_ops->hitung_cak_layanan($tahun)['tahun_lalu']['jumlah_penduduk'];
        $data['jml_plgn_terlayani_ini'] = $this->Model_evkin_dagri_ops->hitung_cak_layanan($tahun)['tahun_ini']['jml_plgn_terlayani'];
        $data['jml_plgn_terlayani_lalu'] = $this->Model_evkin_dagri_ops->hitung_cak_layanan($tahun)['tahun_lalu']['jml_plgn_terlayani'];
        $data['rasio_cak_layanan_ini'] = $this->Model_evkin_dagri_ops->hitung_cak_layanan($tahun)['tahun_ini']['rasio_cak_layanan'];
        $data['rasio_cak_layanan_lalu'] = $this->Model_evkin_dagri_ops->hitung_cak_layanan($tahun)['tahun_lalu']['rasio_cak_layanan'];
        $data['rasio_cak_layanan_2_lalu'] = $this->Model_evkin_dagri_ops->hitung_cak_layanan($tahun)['tahun_2_lalu']['rasio_cak_layanan'];
        $data['hasil_perhitungan_rasio_cak_layanan_ini'] = $this->Model_evkin_dagri_ops->hitung_cak_layanan($tahun)['tahun_ini']['hasil_perhitungan_rasio_cak_layanan'];
        $data['hasil_perhitungan_rasio_cak_layanan_lalu'] = $this->Model_evkin_dagri_ops->hitung_cak_layanan($tahun)['tahun_lalu']['hasil_perhitungan_rasio_cak_layanan'];
        $data['hasil_perhitungan_rasio_cak_layanan_2_lalu'] = $this->Model_evkin_dagri_ops->hitung_cak_layanan($tahun)['tahun_2_lalu']['hasil_perhitungan_rasio_cak_layanan'];
        $data['bonus_rasio_cak_layanan_ini'] = $this->Model_evkin_dagri_ops->hitung_cak_layanan($tahun)['tahun_ini']['bonus_rasio_cak_layanan'];
        $data['bonus_rasio_cak_layanan_lalu'] = $this->Model_evkin_dagri_ops->hitung_cak_layanan($tahun)['tahun_lalu']['bonus_rasio_cak_layanan'];
        $data['hasil_bonus_perhitungan_rasio_cak_layanan_ini'] = $this->Model_evkin_dagri_ops->hitung_cak_layanan($tahun)['tahun_ini']['hasil_bonus_perhitungan_rasio_cak_layanan'];
        $data['hasil_bonus_perhitungan_rasio_cak_layanan_lalu'] = $this->Model_evkin_dagri_ops->hitung_cak_layanan($tahun)['tahun_lalu']['hasil_bonus_perhitungan_rasio_cak_layanan'];
        $kualitas_air = $this->Model_evkin_dagri_ops->hitung_kualitas_air($tahun);
        $data['kualitas_air_ini'] = $kualitas_air['tahun_ini']['kualitas_air'];
        $data['kualitas_air_lalu'] = $kualitas_air['tahun_lalu']['kualitas_air'];
        $data['hasil_kualitas_air_ini'] = $kualitas_air['tahun_ini']['hasil_kualitas_air'];
        $data['hasil_kualitas_air_lalu'] = $kualitas_air['tahun_lalu']['hasil_kualitas_air'];
        $data['kontinuitas_air_ini'] = $this->Model_evkin_dagri_ops->hitung_kontinuitas_air($tahun)['tahun_ini']['kontinuitas_air'];
        $data['kontinuitas_air_lalu'] = $this->Model_evkin_dagri_ops->hitung_kontinuitas_air($tahun)['tahun_lalu']['kontinuitas_air'];
        $data['hasil_kontinuitas_air_ini'] = $this->Model_evkin_dagri_ops->hitung_kontinuitas_air($tahun)['tahun_ini']['hasil_kontinuitas_air'];
        $data['hasil_kontinuitas_air_lalu'] = $this->Model_evkin_dagri_ops->hitung_kontinuitas_air($tahun)['tahun_lalu']['hasil_kontinuitas_air'];
        $data['service_point_ini'] = $this->Model_evkin_dagri_ops->hitung_service_point($tahun)['tahun_ini']['service_point'];
        $data['service_point_lalu'] = $this->Model_evkin_dagri_ops->hitung_service_point($tahun)['tahun_lalu']['service_point'];
        $data['hasil_service_point_ini'] = $this->Model_evkin_dagri_ops->hitung_service_point($tahun)['tahun_ini']['hasil_service_point'];
        $data['hasil_service_point_lalu'] = $this->Model_evkin_dagri_ops->hitung_service_point($tahun)['tahun_lalu']['hasil_service_point'];
        $data['sr_baru_ini'] = $this->Model_evkin_dagri_ops->hitung_sr_baru($tahun)['tahun_ini']['sr_baru'];
        $data['sr_baru_lalu'] = $this->Model_evkin_dagri_ops->hitung_sr_baru($tahun)['tahun_lalu']['sr_baru'];
        $data['hasil_sr_baru_ini'] = $this->Model_evkin_dagri_ops->hitung_sr_baru($tahun)['tahun_ini']['hasil_sr_baru'];
        $data['hasil_sr_baru_lalu'] = $this->Model_evkin_dagri_ops->hitung_sr_baru($tahun)['tahun_lalu']['hasil_sr_baru'];

        $data['total_nrw_ini'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_ini']['total_nrw'];
        $data['total_nrw_lalu'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_lalu']['total_nrw'];
        $data['total_volume_produksi_ini'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_ini']['total_volume_produksi'];
        $data['total_volume_produksi_lalu'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_lalu']['total_volume_produksi'];
        $data['nilai_nrw_ini'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_ini']['nilai_nrw'];
        $data['nilai_nrw_lalu'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_lalu']['nilai_nrw'];
        $data['hasil_perhitungan_nilai_nrw_ini'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_ini']['hasil_perhitungan_nilai_nrw'];
        $data['hasil_perhitungan_nilai_nrw_lalu'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_lalu']['hasil_perhitungan_nilai_nrw'];
        $data['kap_riil_ini'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_ini']['kap_riil'];
        $data['kap_riil_lalu'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_lalu']['kap_riil'];
        $data['terpasang_ini'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_ini']['terpasang'];
        $data['terpasang_lalu'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_lalu']['terpasang'];
        $data['nilai_kapasitas_ini'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_ini']['nilai_kapasitas'];
        $data['nilai_kapasitas_lalu'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_lalu']['nilai_kapasitas'];
        $data['hasil_perhitungan_nilai_kapasitas_ini'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_ini']['hasil_perhitungan_nilai_kapasitas'];
        $data['hasil_perhitungan_nilai_kapasitas_lalu'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_lalu']['hasil_perhitungan_nilai_kapasitas'];
        $data['water_meter_ini'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_ini']['water_meter'];
        $data['water_meter_lalu'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_lalu']['water_meter'];
        $data['total_pelanggan_ini'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_ini']['total_pelanggan'];
        $data['total_pelanggan_lalu'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_lalu']['total_pelanggan'];
        $data['nilai_peneraan_wm_ini'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_ini']['nilai_peneraan_wm'];
        $data['nilai_peneraan_wm_lalu'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_lalu']['nilai_peneraan_wm'];
        $data['hasil_perhitungan_nilai_peneraan_wm_ini'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_ini']['hasil_perhitungan_nilai_peneraan_wm'];
        $data['hasil_perhitungan_nilai_peneraan_wm_lalu'] = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_lalu']['hasil_perhitungan_nilai_peneraan_wm'];
        $data['total_aduan_ini'] = $this->Model_evkin_dagri_ops->hitung_pengaduan($tahun)['tahun_ini']['total_aduan'];
        $data['total_aduan_lalu'] = $this->Model_evkin_dagri_ops->hitung_pengaduan($tahun)['tahun_lalu']['total_aduan'];
        $data['total_aduan_ya_ini'] = $this->Model_evkin_dagri_ops->hitung_pengaduan($tahun)['tahun_ini']['total_aduan_ya'];
        $data['total_aduan_ya_lalu'] = $this->Model_evkin_dagri_ops->hitung_pengaduan($tahun)['tahun_lalu']['total_aduan_ya'];
        $data['nilai_aduan_ini'] = $this->Model_evkin_dagri_ops->hitung_pengaduan($tahun)['tahun_ini']['nilai_aduan'];
        $data['nilai_aduan_lalu'] = $this->Model_evkin_dagri_ops->hitung_pengaduan($tahun)['tahun_lalu']['nilai_aduan'];
        $data['hasil_perhitungan_nilai_aduan_ini'] = $this->Model_evkin_dagri_ops->hitung_pengaduan($tahun)['tahun_ini']['hasil_perhitungan_nilai_aduan'];
        $data['hasil_perhitungan_nilai_aduan_lalu'] = $this->Model_evkin_dagri_ops->hitung_pengaduan($tahun)['tahun_lalu']['hasil_perhitungan_nilai_aduan'];
        $data['total_pelanggan_aktif_ini'] = $this->Model_evkin_dagri_ops->hitung_rasio_karyawan($tahun)['tahun_ini']['total_pelanggan_aktif'];
        $data['total_pelanggan_aktif_lalu'] = $this->Model_evkin_dagri_ops->hitung_rasio_karyawan($tahun)['tahun_lalu']['total_pelanggan_aktif'];
        $data['nilai_rasio_kyw_ini'] = $this->Model_evkin_dagri_ops->hitung_rasio_karyawan($tahun)['tahun_ini']['nilai_rasio_kyw'];
        $data['nilai_rasio_kyw_lalu'] = $this->Model_evkin_dagri_ops->hitung_rasio_karyawan($tahun)['tahun_lalu']['nilai_rasio_kyw'];
        $data['hasil_perhitungan_nilai_rasio_kyw_ini'] = $this->Model_evkin_dagri_ops->hitung_rasio_karyawan($tahun)['tahun_ini']['hasil_perhitungan_nilai_rasio_kyw'];
        $data['hasil_perhitungan_nilai_rasio_kyw_lalu'] = $this->Model_evkin_dagri_ops->hitung_rasio_karyawan($tahun)['tahun_lalu']['hasil_perhitungan_nilai_rasio_kyw'];
        $data['total_aspek_ops_ini'] = $this->Model_evkin_dagri_ops->hitung_aspek_ops($tahun)['tahun_ini']['total_aspek_ops'];
        $data['total_aspek_ops_lalu'] = $this->Model_evkin_dagri_ops->hitung_aspek_ops($tahun)['tahun_lalu']['total_aspek_ops'];
        $data['nilai_kinerja_aspek_ops_ini'] = $this->Model_evkin_dagri_ops->hitung_aspek_ops($tahun)['tahun_ini']['nilai_kinerja_aspek_ops'];
        $data['nilai_kinerja_aspek_ops_lalu'] = $this->Model_evkin_dagri_ops->hitung_aspek_ops($tahun)['tahun_lalu']['nilai_kinerja_aspek_ops'];
        // akhir aspek operasional

        // aspek administrasi
        $data['rjp_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['rjp'];
        $data['rjp_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['rjp'];
        $data['hasil_rjp_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['hasil_rjp'];
        $data['hasil_rjp_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['hasil_rjp'];
        $data['rodut_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['rodut'];
        $data['rodut_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['rodut'];
        $data['hasil_rodut_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['hasil_rodut'];
        $data['hasil_rodut_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['hasil_rodut'];
        $data['pos_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['pos'];
        $data['pos_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['pos'];
        $data['hasil_pos_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['hasil_pos'];
        $data['hasil_pos_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['hasil_pos'];
        $data['gnl_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['gnl'];
        $data['gnl_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['gnl'];
        $data['hasil_gnl_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['hasil_gnl'];
        $data['hasil_gnl_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['hasil_gnl'];
        $data['ppkk_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['ppkk'];
        $data['ppkk_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['ppkk'];
        $data['hasil_ppkk_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['hasil_ppkk'];
        $data['hasil_ppkk_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['hasil_ppkk'];
        $data['rkdap_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['rkdap'];
        $data['rkdap_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['rkdap'];
        $data['hasil_rkdap_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['hasil_rkdap'];
        $data['hasil_rkdap_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['hasil_rkdap'];
        $data['tli_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['tli'];
        $data['tli_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['tli'];
        $data['hasil_tli_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['hasil_tli'];
        $data['hasil_tli_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['hasil_tli'];
        $data['tle_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['tle'];
        $data['tle_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['tle'];
        $data['hasil_tle_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['hasil_tle'];
        $data['hasil_tle_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['hasil_tle'];
        $data['oai_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['oai'];
        $data['oai_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['oai'];
        $data['hasil_oai_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['hasil_oai'];
        $data['hasil_oai_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['hasil_oai'];
        $data['tlhptt_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['tlhptt'];
        $data['tlhptt_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['tlhptt'];
        $data['hasil_tlhptt_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['hasil_tlhptt'];
        $data['hasil_tlhptt_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['hasil_tlhptt'];
        $data['total_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['total_nilai'];
        $data['total_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['total_nilai'];
        $data['nilai_kinerja_adm_ini'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_ini']['nilai_kinerja'];
        $data['nilai_kinerja_adm_lalu'] = $this->Model_evkin_dagri_adm->hitung_aspek_adm($tahun)['tahun_lalu']['nilai_kinerja'];

        // akhir aspek administrasi


        if ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('dashboard/view_evkin_permendagri', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('dashboard/view_evkin_permendagri', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Perencanaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_rencana');
            $this->load->view('dashboard/view_evkin_permendagri', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Pemeliharaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('dashboard/view_evkin_permendagri', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Umum') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_umum');
            $this->load->view('dashboard/view_evkin_permendagri', $data);
            $this->load->view('templates/footer');
        } else {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('dashboard/view_evkin_permendagri', $data);
            $this->load->view('templates/footer');
        }
    }
}
