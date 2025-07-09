<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_evkin extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_labarugi');
        $this->load->model('Model_lap_keuangan');
        $this->load->model('Model_langgan');
        $this->load->model('Model_pelihara');
        $this->load->model('Model_umum');
    }

    // aspek keuangan
    public function hitung_laba_rugi_bersih($tahun)
    {
        $lr_sak_ep = $this->Model_labarugi->get_all_sak_ep($tahun);

        $total_pendapatan_usaha_audited = 0;
        $total_beban_usaha_audited = 0;
        $total_beban_umum_administrasi_audited = 0;
        $total_pendapatan_beban_lain_audited = 0;
        $total_beban_pajak_penghasilan_audited = 0;
        $total_penghasilan_komprehensif_lain_audited = 0;

        foreach ($lr_sak_ep as $row) {
            if ($row->tahun_lr_sak_ep == $tahun) {
                switch ($row->kategori) {
                    case 'Pendapatan Usaha':
                        $total_pendapatan_usaha_audited += $row->nilai_lr_sak_ep_audited ?? 0;
                        break;
                    case 'Beban Usaha':
                        $total_beban_usaha_audited += $row->nilai_lr_sak_ep_audited ?? 0;
                        break;
                    case 'Beban Umum Dan Administrasi':
                        $total_beban_umum_administrasi_audited += $row->nilai_lr_sak_ep_audited ?? 0;
                        break;
                    case 'Pendapatan - Beban Lain-lain':
                        $total_pendapatan_beban_lain_audited += $row->nilai_lr_sak_ep_audited ?? 0;
                        break;
                    case 'Beban Pajak Penghasilan':
                        $total_beban_pajak_penghasilan_audited += $row->nilai_lr_sak_ep_audited ?? 0;
                        break;
                    case '(Kerugian) Penghasilan Komprehensip Lain':
                        $total_penghasilan_komprehensif_lain_audited += $row->nilai_lr_sak_ep_audited ?? 0;
                        break;
                }
            }
        }

        // Hitung berurutan
        $labarugi_kotor = $total_pendapatan_usaha_audited - $total_beban_usaha_audited;
        $labarugi_operasional = $labarugi_kotor - $total_beban_umum_administrasi_audited;
        $labarugi_bersih_sebelum_pajak = $labarugi_operasional + $total_pendapatan_beban_lain_audited;
        $laba_rugi_bersih = $labarugi_bersih_sebelum_pajak - ($total_beban_pajak_penghasilan_audited + $total_penghasilan_komprehensif_lain_audited);

        // Tambahan untuk rasio operasional
        $bobot = 0.055;
        $bobot_solva = 0.030;

        $total_beban = $total_beban_usaha_audited + $total_beban_umum_administrasi_audited;

        if ($total_beban != 0 && $total_pendapatan_usaha_audited != 0) {
            $persen_rasio_ops = $total_beban / $total_pendapatan_usaha_audited;
        } else {
            $persen_rasio_ops = 0;
        }

        if ($persen_rasio_ops == 0) {
            $hasil_perhitungan_rasio_ops = 0;
        } elseif ($persen_rasio_ops < 0) {
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

        $hasil_rasio_ops = $hasil_perhitungan_rasio_ops * $bobot;

        // hitung neraca
        $neraca = $this->Model_lap_keuangan->get_all_neraca($tahun);
        $total_aset_lancar_audited = $total_aset_tidak_lancar_audited = 0;
        $total_liabilitas_jangka_pendek_audited = $total_liabilitas_jangka_panjang_audited = $total_ekuitas_audited = 0;

        foreach ($neraca as $row) {
            if ($row->tahun_neraca == $tahun) {
                switch ($row->kategori) {
                    case 'Aset Lancar':
                        $total_aset_lancar_audited += $row->nilai_neraca_audited ?? 0;
                        break;
                    case 'Aset Tidak Lancar':
                        $total_aset_tidak_lancar_audited += $row->nilai_neraca_audited ?? 0;
                        break;
                    case 'Liabilitas Jangka Pendek':
                        $total_liabilitas_jangka_pendek_audited += $row->nilai_neraca_audited ?? 0;
                        break;
                    case 'Liabilitas Jangka Panjang':
                        $total_liabilitas_jangka_panjang_audited += $row->nilai_neraca_audited ?? 0;
                        break;
                    case 'Ekuitas':
                        $total_ekuitas_audited += $row->nilai_neraca_audited ?? 0;
                        break;
                }
            }
        }
        $total_ekuitas_audited = $total_ekuitas_audited;

        if (isset($total_ekuitas_audited) && $total_ekuitas_audited != 0) {
            $persen_roe = $laba_rugi_bersih / $total_ekuitas_audited * 100;
        } else {
            $persen_roe = 0;
        }
        $persen_roe = $persen_roe;

        $hasil_perhitungan_roe = 0;

        if ($persen_roe === 0) {
            $hasil_perhitungan_roe = 0;
        } elseif ($persen_roe < 0) {
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

        $hasil_roe = $hasil_perhitungan_roe * $bobot;

        // hitung cash Rasio
        $hutang_lancar = $total_liabilitas_jangka_pendek_audited;
        $total_kas_bank = $this->Model_lap_keuangan->get_kas_dan_bank_by_tahun($tahun);

        if (isset($total_kas_bank) && isset($hutang_lancar) && $total_kas_bank != 0 && $hutang_lancar != 0) {
            $persen_cash_rasio = $total_kas_bank / $hutang_lancar * 100;
        } else {
            $persen_cash_rasio = 0;
        }


        $hasil_perhitungan_cash_rasio = 0;
        if ($persen_cash_rasio === 0) {
            $hasil_perhitungan_cash_rasio = 0;
        } elseif ($persen_cash_rasio < 40) {
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

        $hasil_cash_rasio = $hasil_perhitungan_cash_rasio * $bobot;

        // hitung efektifitas penagihan
        $efek = $this->Model_langgan->get_efek_tagih($tahun);
        $sisa_piu = $this->Model_langgan->get_sisa_piu($tahun);
        $bagian_upk = $this->db->where('status_evkin', 1)->get('bagian_upk')->result();

        $kategori = [
            '1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr',
            '5' => 'Mei', '6' => 'Jun', '7' => 'Jul', '8' => 'Ags',
            '9' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
        ];

        $data_rinci = [];
        foreach ($bagian_upk as $upk) {
            $nama_upk = $upk->nama_bagian;
            $data_rinci[$nama_upk] = [];
            foreach (array_keys($kategori) as $bulan) {
                $data_rinci[$nama_upk][$bulan] = ['sr' => 0, 'rp' => 0];
            }
        }

        foreach ($efek as $row) {
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

        $total_rp = $total_keseluruhan['JUMLAH']['rp'];
        $ppa_input = $this->Model_labarugi->get_ppa_input($tahun);

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

        foreach ($sisa_piu as $row) {
            if (isset($data_piu[$row->uraian])) {
                $data_piu[$row->uraian] = $row->rupiah;
            }
        }

        $total = array_sum($data_piu);
        $total_pa_tahun_ini = $total_pa_tahun_ini;
        $sisa_rek = $total;
        $rek_tagih = $total_pa_tahun_ini - $sisa_rek + $total_rp;
        $persen_efek = ($total_pa_tahun_ini == 0) ? 0 : round(($rek_tagih / $total_pa_tahun_ini) * 100, 2);

        $hasil_perhitungan_efek = 0;
        if ($persen_efek == 0) {
            $hasil_perhitungan_efek = 0;
        } elseif ($persen_efek <= 75) {
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

        $hasil_efek = $hasil_perhitungan_efek * $bobot;

        // hitung solvabilitas
        $total_asset = $total_aset_lancar_audited + $total_aset_tidak_lancar_audited;
        $total_utang = $total_liabilitas_jangka_pendek_audited + $total_liabilitas_jangka_panjang_audited;

        $persen_solva = ($total_asset == 0) ? 0 : round(($total_asset / $total_utang) * 100, 2);


        $hasil_perhitungan_solva = 0;
        if ($persen_solva == 0) {
            $hasil_perhitungan_solva = 0;
        } elseif ($persen_solva < 100) {
            $hasil_perhitungan_solva = 1;
        } elseif ($persen_solva >= 100 && $persen_solva < 135) {
            $hasil_perhitungan_solva = 2;
        } elseif ($persen_solva >= 135 && $persen_solva < 170) {
            $hasil_perhitungan_solva = 3;
        } elseif ($persen_solva >= 170 && $persen_solva < 200) {
            $hasil_perhitungan_solva = 4;
        } elseif ($persen_solva > 200) {
            $hasil_perhitungan_solva = 5;
        }

        $hasil_solva = $hasil_perhitungan_solva * $bobot_solva;

        $total_hasil_keuangan = $hasil_roe + $hasil_rasio_ops + $hasil_cash_rasio + $hasil_efek + $hasil_solva;

        return [
            'laba_rugi_bersih' => $laba_rugi_bersih,
            'pendapatan_usaha' => $total_pendapatan_usaha_audited,
            'beban_usaha' => $total_beban,
            'persen_rasio_ops' => $persen_rasio_ops,
            'hasil_perhitungan_rasio_ops' => $hasil_perhitungan_rasio_ops,
            'hasil_rasio_ops' => $hasil_rasio_ops,
            'total_aset_lancar_audited' => $total_aset_lancar_audited,
            'total_aset_tidak_lancar_audited' => $total_aset_tidak_lancar_audited,
            'total_liabilitas_jangka_pendek_audited' => $total_liabilitas_jangka_pendek_audited,
            'total_liabilitas_jangka_panjang_audited' => $total_liabilitas_jangka_panjang_audited,
            'total_ekuitas_audited' => $total_ekuitas_audited,
            'persen_roe' => $persen_roe,
            'hasil_perhitungan_roe' => $hasil_perhitungan_roe,
            'hasil_roe' => $hasil_roe,
            'persen_cash_rasio' => $persen_cash_rasio,
            'hasil_perhitungan_cash_rasio' => $hasil_perhitungan_cash_rasio,
            'hasil_cash_rasio' => $hasil_cash_rasio,
            'total_kas_bank' => $total_kas_bank,
            'hutang_lancar' => $hutang_lancar,
            'persen_efek' => $persen_efek,
            'hasil_perhitungan_efek' => $hasil_perhitungan_efek,
            'hasil_efek' => $hasil_efek,
            'persen_solva' => $persen_solva,
            'hasil_perhitungan_solva' => $hasil_perhitungan_solva,
            'hasil_solva' => $hasil_solva,
            'total_hasil_keuangan' => $total_hasil_keuangan,
            'rek_tagih' => $rek_tagih,
            'total_pa_tahun_ini' => $total_pa_tahun_ini,
            'total_asset' => $total_asset,
            'total_utang' => $total_utang
        ];
    }
    // akhir aspek keuangan
    // aspek pelayanan
    public function hitung_pelayanan($tahun)
    {
        $data_penduduk = $this->Model_langgan->get_data_penduduk($tahun);
        $data_pelanggan = $this->Model_langgan->get_data_pelanggan($tahun);
        $bobot_cak_teknis = 0.05;

        // Hitung total nilai
        $total_penduduk = 0;
        $total_kk = 0;
        $total_wil_layan = 0;
        $total_kk_layan = 0;
        $jumlah_wil_layan_ya = 0;
        $total_wil_layan_semua = 0;

        foreach ($data_penduduk as $dp) {
            $total_penduduk += (int) $dp->jumlah_penduduk;
            $total_kk += (int) $dp->jumlah_kk;
            $total_wil_layan += (int) $dp->jumlah_wil_layan;
            $total_kk_layan += (int) $dp->jumlah_kk_layan;

            if (strtoupper($dp->wil_layan) === 'YA') {
                $jumlah_wil_layan_ya++;
            }
            if (!empty($dp->wil_layan)) {
                $total_wil_layan_semua++;
            }
        }

        $cakupan = [
            'total_penduduk' => $total_penduduk,
            'total_kk' => $total_kk,
            'rata_jiwa_kk' => $total_kk != 0 ? $total_penduduk / $total_kk : 0,
            'rata_jiwa_kk2' => $total_wil_layan != 0 ? $total_wil_layan / $total_kk_layan : 0,
            'total_wil_layan' => $total_wil_layan,
            'total_kk_layan' => $total_kk_layan,
            'jumlah_wil_layan_ya' => $jumlah_wil_layan_ya,
            'total_wil_layan_semua' => $total_wil_layan_semua
        ];

        $total_rt_dom = 0;
        $total_niaga_dom = 0;
        $total_sl_hu_dom = 0;
        $total_n_aktif_dom = 0;

        foreach ($data_pelanggan as $dl) {
            $total_rt_dom += (int) $dl->rt_dom;
            $total_niaga_dom += (int) $dl->niaga_dom;
            $total_sl_hu_dom += (int) $dl->sl_hu_dom;
            $total_n_aktif_dom += (int) $dl->n_aktif_dom;
        }

        $pelanggan = [
            'total_rt_dom' => $total_rt_dom,
            'total_niaga_dom' => $total_niaga_dom,
            'total_sl_hu_dom' => $total_sl_hu_dom,
            'total_n_aktif_dom' => $total_n_aktif_dom
        ];

        $total_pelanggan =
            ($pelanggan['total_rt_dom'] ?? 0) +
            ($pelanggan['total_niaga_dom'] ?? 0) +
            ($pelanggan['total_sl_hu_dom'] ?? 0) +
            ($pelanggan['total_n_aktif_dom'] ?? 0);

        $rata_jiwa = $cakupan['rata_jiwa_kk'] ?? 0;
        $total_jiwa_terlayani = $pelanggan['total_rt_dom'] * $rata_jiwa + $pelanggan['total_niaga_dom'] * $rata_jiwa + $pelanggan['total_sl_hu_dom'] * 100 + $pelanggan['total_n_aktif_dom'] * $rata_jiwa;
        $cakupan_admin = ($cakupan['total_penduduk'] ?? 0) > 0
            ? ($total_jiwa_terlayani / $cakupan['total_penduduk']) * 100
            : 0;

        $rata_jiwa2 = $cakupan['rata_jiwa_kk2'] ?? 0;
        $total_jiwa_terlayani2 = $pelanggan['total_rt_dom'] * $rata_jiwa2 + $pelanggan['total_niaga_dom'] * $rata_jiwa2 + $pelanggan['total_sl_hu_dom'] * 100 + $pelanggan['total_n_aktif_dom'] * $rata_jiwa2;

        $cakupan_teknis = ($cakupan['total_penduduk'] ?? 0) > 0 ? ($total_jiwa_terlayani2 / $cakupan['total_wil_layan']) * 100 : 0;
        $persen_cak_teknis = ($cakupan['total_wil_layan'] ?? 0) > 0 ? ($total_jiwa_terlayani2 / $cakupan['total_wil_layan']) * 100 : 0;

        $hasil_perhitungan_cak_teknis = 0;
        if ($persen_cak_teknis === 0) {
            $hasil_perhitungan_cak_teknis = 0;
        } elseif ($persen_cak_teknis <= 20) {
            $hasil_perhitungan_cak_teknis = 1;
        } elseif ($persen_cak_teknis > 20 && $persen_cak_teknis <= 40) {
            $hasil_perhitungan_cak_teknis = 2;
        } elseif ($persen_cak_teknis > 40 && $persen_cak_teknis <= 60) {
            $hasil_perhitungan_cak_teknis = 3;
        } elseif ($persen_cak_teknis > 60 && $persen_cak_teknis <= 80) {
            $hasil_perhitungan_cak_teknis = 4;
        } elseif ($persen_cak_teknis > 80) {
            $hasil_perhitungan_cak_teknis = 5;
        }

        $hasil_cak_teknis = $hasil_perhitungan_cak_teknis * $bobot_cak_teknis;

        return [
            'total_jiwa_terlayani2' => $total_jiwa_terlayani2,
            'cakupan_teknis' => $cakupan_teknis,
            'total_wil_layan' => $total_wil_layan,
            'persen_cak_teknis' => $persen_cak_teknis,
            'hasil_perhitungan_cak_teknis' => $hasil_perhitungan_cak_teknis,
            'hasil_cak_teknis' => $hasil_cak_teknis
        ];
    }

    public function hitung_pengaduan($tahun)
    {
        $bobot_pengaduan = 0.025;
        $pengaduan = $this->Model_langgan->get_pengaduan($tahun);
        $total_aduan = 0;
        $total_aduan_ya = 0;
        $total_aduan_tidak = 0;
        $bulan_sebelumnya = null;
        $total_per_jenis = [];

        foreach ($pengaduan as $data) {
            $total_aduan += $data->jumlah_aduan ?? 0;
            $total_aduan_ya += $data->jumlah_aduan_ya ?? 0;
            $total_aduan_tidak += $data->jumlah_aduan_tidak ?? 0;

            // Hitung total per jenis aduan
            // $jenis = $data->jenis_aduan;
            // if (!isset($total_per_jenis[$jenis])) {
            //     $total_per_jenis[$jenis] = [
            //         'jumlah' => 0,
            //         'ya' => 0,
            //         'tidak' => 0
            //     ];
            // }

            // $total_per_jenis[$jenis]['jumlah'] += $data->jumlah_aduan ?? 0;
            // $total_per_jenis[$jenis]['ya'] += $data->jumlah_aduan_ya ?? 0;
            // $total_per_jenis[$jenis]['tidak'] += $data->jumlah_aduan_tidak ?? 0;

            // foreach ($total_per_jenis as $jenis => $total) {
            //     $jumlah_keluhan = $total['jumlah'];
            //     $jumlah_keluhan_selesai = $total['ya'];
            // }
        }

        $persen_pengaduan = ($total_aduan ?? 0) > 0 ? ($total_aduan_ya / $total_aduan) * 100 : 0;

        $hasil_perhitungan_pengaduan = 0;
        if ($persen_pengaduan === 0) {
            $hasil_perhitungan_pengaduan = 0;
        } elseif ($persen_pengaduan <= 20) {
            $hasil_perhitungan_pengaduan = 1;
        } elseif ($persen_pengaduan > 20 && $persen_pengaduan <= 40) {
            $hasil_perhitungan_pengaduan = 2;
        } elseif ($persen_pengaduan > 40 && $persen_pengaduan <= 60) {
            $hasil_perhitungan_pengaduan = 3;
        } elseif ($persen_pengaduan > 60 && $persen_pengaduan <= 80) {
            $hasil_perhitungan_pengaduan = 4;
        } elseif ($persen_pengaduan > 80) {
            $hasil_perhitungan_pengaduan = 5;
        }

        $hasil_pengaduan = $hasil_perhitungan_pengaduan * $bobot_pengaduan;

        return [
            'jumlah_keluhan_selesai' => $total_aduan_ya,
            'jumlah_keluhan' => $total_aduan,
            'persen_pengaduan' => $persen_pengaduan,
            'hasil_perhitungan_pengaduan' => $hasil_perhitungan_pengaduan,
            'hasil_pengaduan' => $hasil_pengaduan
        ];
    }

    public function hitung_kualitas_air($tahun)
    {
        $bobot_kualitas = 0.075;
        $uji_syarat = $this->Model_pelihara->get_uji_syarat($tahun);
        $total_fisika = $total_mikro = $total_sisa = $total_kimia_wajib = $total_kimia_tambahan = $total_jumlah_terambil = 0;
        $total_fisika_eks = $total_mikro_eks = $total_sisa_eks = $total_kimia_wajib_eks = $total_kimia_tambahan_eks = $total_jumlah_terambil_eks = $total_jumlah_syarat = 0;

        $bulan_list = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        $data_sample = [];
        foreach ($uji_syarat as $row) {
            $data_sample[$row['bulan']] = $row;
        }
        foreach ($bulan_list as $bulan) {

            $jumlah_terambil = isset($data_sample[$bulan]) ? $data_sample[$bulan]['jumlah_terambil'] : 0;
            $jumlah_syarat = isset($data_sample[$bulan]) ? $data_sample[$bulan]['jumlah_syarat'] : 0;

            // Menjumlahkan total per kategori
            $total_jumlah_terambil += $jumlah_terambil;
            $total_jumlah_syarat += $jumlah_syarat;
        }
        $persen_kualitas = ($total_jumlah_terambil ?? 0) > 0 ? ($total_jumlah_syarat / $total_jumlah_terambil) * 100 : 0;

        $hasil_perhitungan_kualitas = 0;
        if ($persen_kualitas === 0) {
            $hasil_perhitungan_kualitas = 0;
        } elseif ($persen_kualitas <= 20) {
            $hasil_perhitungan_kualitas = 1;
        } elseif ($persen_kualitas > 20 && $persen_kualitas <= 40) {
            $hasil_perhitungan_kualitas = 2;
        } elseif ($persen_kualitas > 40 && $persen_kualitas <= 60) {
            $hasil_perhitungan_kualitas = 3;
        } elseif ($persen_kualitas > 60 && $persen_kualitas <= 80) {
            $hasil_perhitungan_kualitas = 4;
        } elseif ($persen_kualitas > 80) {
            $hasil_perhitungan_kualitas = 5;
        }

        $hasil_kualitas = $hasil_perhitungan_kualitas * $bobot_kualitas;

        return [
            'total_jumlah_terambil' => $total_jumlah_terambil,
            'total_jumlah_syarat' => $total_jumlah_syarat,
            'persen_kualitas' => $persen_kualitas,
            'hasil_perhitungan_kualitas' => $hasil_perhitungan_kualitas,
            'hasil_kualitas' => $hasil_kualitas
        ];
    }

    public function hitung_pelanggan($tahun)
    {

        $bobot_pelanggan = 0.05;
        $data_pelanggan_tahun_ini = $this->Model_langgan->get_data_pelanggan($tahun);
        $data_pelanggan_tahun_lalu = $this->Model_langgan->get_data_pelanggan($tahun - 1);

        $total_tahun_ini = 0;
        $semua_pelanggan_tahun_ini = 0;
        foreach ($data_pelanggan_tahun_ini as $data) {
            $baris_total =
                ($data->n_aktif_dom ?? 0) + ($data->rt_dom ?? 0) + ($data->niaga_dom ?? 0) + ($data->sl_kom_dom ?? 0) + ($data->unit_kom_dom ?? 0) + ($data->sl_hu_dom ?? 0) + ($data->n_aktif_n_dom ?? 0) + ($data->sosial_n_dom ?? 0) + ($data->niaga_n_dom ?? 0) + ($data->ind_n_dom ?? 0) + ($data->inst_n_dom ?? 0) + ($data->k2_n_dom ?? 0) + ($data->lain_n_dom ?? 0);

            $total_aktif =
                ($data->rt_dom ?? 0) + ($data->niaga_dom ?? 0) + ($data->sl_kom_dom ?? 0) + ($data->unit_kom_dom ?? 0) + ($data->sl_hu_dom ?? 0) + ($data->sosial_n_dom ?? 0) + ($data->niaga_n_dom ?? 0) + ($data->ind_n_dom ?? 0) + ($data->inst_n_dom ?? 0) + ($data->k2_n_dom ?? 0) + ($data->lain_n_dom ?? 0);

            $total_tahun_ini += $total_aktif;
            $semua_pelanggan_tahun_ini += $baris_total;
        }

        $total_tahun_lalu = 0;
        foreach ($data_pelanggan_tahun_lalu as $data) {
            $baris_total =
                ($data->n_aktif_dom ?? 0) + ($data->rt_dom ?? 0) + ($data->niaga_dom ?? 0) + ($data->sl_kom_dom ?? 0) + ($data->unit_kom_dom ?? 0) + ($data->sl_hu_dom ?? 0) + ($data->n_aktif_n_dom ?? 0) + ($data->sosial_n_dom ?? 0) + ($data->niaga_n_dom ?? 0) + ($data->ind_n_dom ?? 0) + ($data->inst_n_dom ?? 0) + ($data->k2_n_dom ?? 0) + ($data->lain_n_dom ?? 0);

            $total_aktif =
                ($data->rt_dom ?? 0) + ($data->niaga_dom ?? 0) + ($data->sl_kom_dom ?? 0) + ($data->unit_kom_dom ?? 0) + ($data->sl_hu_dom ?? 0) + ($data->sosial_n_dom ?? 0) + ($data->niaga_n_dom ?? 0) + ($data->ind_n_dom ?? 0) + ($data->inst_n_dom ?? 0) + ($data->k2_n_dom ?? 0) + ($data->lain_n_dom ?? 0);

            $total_tahun_lalu += $total_aktif;
        }

        $total_tahun_ini = $total_tahun_ini ?? 0;
        $total_tahun_lalu = $total_tahun_lalu ?? 0;

        $persen_pelanggan = ($total_tahun_lalu > 0)
            ? (($total_tahun_ini - $total_tahun_lalu) / $total_tahun_lalu) * 100
            : 0;

        $jumlah_pelanggan = $total_tahun_ini - $total_tahun_lalu;
        $hasil_perhitungan_pelanggan = 0;
        if ($persen_pelanggan <= -100) {
            $hasil_perhitungan_pelanggan = 0;
        } elseif ($persen_pelanggan > -100 && $persen_pelanggan < 4) {
            $hasil_perhitungan_pelanggan = 1;
        } elseif ($persen_pelanggan >= 4 && $persen_pelanggan < 6) {
            $hasil_perhitungan_pelanggan = 2;
        } elseif ($persen_pelanggan >= 6 && $persen_pelanggan < 8) {
            $hasil_perhitungan_pelanggan = 3;
        } elseif ($persen_pelanggan >= 8 && $persen_pelanggan < 10) {
            $hasil_perhitungan_pelanggan = 4;
        } elseif ($persen_pelanggan > 10) {
            $hasil_perhitungan_pelanggan = 5;
        }
        $hasil_pelanggan = $hasil_perhitungan_pelanggan * $bobot_pelanggan;

        return [
            'jumlah_pelanggan' => $jumlah_pelanggan,
            'jumlah_pelanggan_tahun_ini' => $semua_pelanggan_tahun_ini,
            'jumlah_pelanggan_tahun_lalu' => $total_tahun_lalu,
            'persen_pelanggan' => $persen_pelanggan,
            'hasil_perhitungan_pelanggan' => $hasil_perhitungan_pelanggan,
            'hasil_pelanggan' => $hasil_pelanggan,
            'total_pelanggan_tahun_ini' => $total_tahun_ini
        ];
    }

    public function hitung_air_domestik($tahun)
    {

        $bobot_air_dom = 0.05;
        $rincian_dom = $this->Model_langgan->get_rincian($tahun);

        // DOMESTIK: Kategori yang termasuk domestik
        $kategori_domestik = ['SOSIAL A', 'RUMAH TANGGA A', 'RUMAH TANGGA B', 'RUMAH TANGGA C', 'NIAGA A'];
        $data['total_domestik'] = $this->hitung_total_rincian($rincian_dom, $kategori_domestik);

        // Total Non Domestik (ambil dari database juga)
        $rincian_n_dom = $this->Model_langgan->get_rincian($tahun); // jika perlu panggil ulang
        $kategori_non_domestik = ['SOSIAL B', 'INSTANSI PEM DESA', 'TNI/POLRI', 'INSTANSI PEM KAB', 'KHUSUS', 'NIAGA B']; // sesuaikan ini
        $data['total_non_domestik'] = $this->hitung_total_rincian($rincian_n_dom, $kategori_non_domestik);

        // Total Domestik
        $data['domestik'] = [
            'sr' => $data['total_domestik']['sr'],
            'vol' => $data['total_domestik']['vol'],
            'rp' => $data['total_domestik']['rp'],
        ];

        // Total Non Domestik
        $data['non_domestik'] = [
            'sr' => $data['total_non_domestik']['sr'],
            'vol' => $data['total_non_domestik']['vol'],
            'rp' => $data['total_non_domestik']['rp'],
        ];

        // Total gabungan
        $data['total_semua'] = [
            'sr' => $data['total_domestik']['sr'] + $data['total_non_domestik']['sr'],
            'vol' => $data['total_domestik']['vol'] + $data['total_non_domestik']['vol'],
            'rp' => $data['total_domestik']['rp'] + $data['total_non_domestik']['rp'],
        ];

        $jumlah_air_terjual = $data['total_domestik']['vol'];
        $jumlah_pelanggan_dom = $data['total_domestik']['sr'];

        $persen_air_dom = ($jumlah_air_terjual ?? 0) > 0 ? ($jumlah_air_terjual / $jumlah_pelanggan_dom) / 12 : 0;

        $hasil_perhitungan_air_dom = 0;
        if ($persen_air_dom <= 0) {
            $hasil_perhitungan_air_dom = 0;
        } elseif ($persen_air_dom < 15) {
            $hasil_perhitungan_air_dom = 1;
        } elseif ($persen_air_dom >= 15 && $persen_air_dom < 20) {
            $hasil_perhitungan_air_dom = 2;
        } elseif ($persen_air_dom >= 20 && $persen_air_dom < 25) {
            $hasil_perhitungan_air_dom = 3;
        } elseif ($persen_air_dom >= 25 && $persen_air_dom < 30) {
            $hasil_perhitungan_air_dom = 4;
        } elseif ($persen_air_dom > 30) {
            $hasil_perhitungan_air_dom = 5;
        }
        $hasil_air_dom = $hasil_perhitungan_air_dom * $bobot_air_dom;

        return [
            'jumlah_air_terjual' => $jumlah_air_terjual,
            'jumlah_pelanggan_dom' => $jumlah_pelanggan_dom,
            'persen_air_dom' => $persen_air_dom,
            'hasil_perhitungan_air_dom' => $hasil_perhitungan_air_dom,
            'hasil_air_dom' => $hasil_air_dom
        ];
    }

    private function hitung_total_rincian($rincian, $kategori_terpakai)
    {
        $total = ['sr' => 0, 'vol' => 0, 'rp' => 0];

        foreach ($rincian as $row) {
            if (in_array($row->kel_tarif, $kategori_terpakai)) {
                $total['sr'] += $row->jumlah_sr;
                $total['vol'] += $row->volume;
                $total['rp'] += $row->rupiah;
            }
        }
        return $total;
    }
    // akhir aspek layanan

    // aspek operasional
    public function hitung_efisiensi_prod($tahun)
    {
        $bobot_kap_prod = 0.07;
        $data['kapasitas_produksi'] = $this->Model_pelihara->get_kapasitas_produksi($tahun);

        $total_kap_pasang = 0;
        $total_terpasang = 0;
        $total_tidak_manfaat = 0;
        $total_volume_produksi = 0;
        $total_kap_riil = 0;
        $total_kap_menganggur = 0;
        foreach ($data['kapasitas_produksi'] as $row) {
            $kap_pasang = $row->kap_pasang;
            $terpasang = $row->terpasang;
            $tidak_manfaat = $row->tidak_manfaat;
            $volume_produksi = $row->volume_produksi;
            $kap_riil = $terpasang - $tidak_manfaat;
            $kap_menganggur = $kap_riil - $volume_produksi;

            $total_kap_pasang += $kap_pasang;
            $total_terpasang += $terpasang;
            $total_tidak_manfaat += $tidak_manfaat;
            $total_volume_produksi += $volume_produksi;
            $total_kap_riil += $kap_riil;
            $total_kap_menganggur += $kap_menganggur;
        }

        $persen_kap_prod = ($total_volume_produksi ?? 0) > 0 ? ($total_volume_produksi / $total_terpasang) * 100 : 0;

        $hasil_perhitungan_kap_prod = 0;
        if ($persen_kap_prod <= 50) {
            $hasil_perhitungan_kap_prod = 0;
        } elseif ($persen_kap_prod <= 60) {
            $hasil_perhitungan_kap_prod = 1;
        } elseif ($persen_kap_prod > 60 && $persen_kap_prod <= 70) {
            $hasil_perhitungan_kap_prod = 2;
        } elseif ($persen_kap_prod > 70 && $persen_kap_prod <= 80) {
            $hasil_perhitungan_kap_prod = 3;
        } elseif ($persen_kap_prod > 80 && $persen_kap_prod <= 90) {
            $hasil_perhitungan_kap_prod = 4;
        } elseif ($persen_kap_prod > 90) {
            $hasil_perhitungan_kap_prod = 5;
        }

        $hasil_kap_prod = $hasil_perhitungan_kap_prod * $bobot_kap_prod;

        return [
            'total_volume_produksi' => $total_volume_produksi,
            'total_terpasang' => $total_terpasang,
            // 'tidak_manfaat' => $tidak_manfaat,
            // 'kap_riil' => $kap_riil,
            // 'kap_menganggur' => $kap_menganggur,
            'persen_kap_prod' => $persen_kap_prod,
            'hasil_perhitungan_kap_prod' => $hasil_perhitungan_kap_prod,
            'hasil_kap_prod' => $hasil_kap_prod
        ];
    }

    public function hitung_tekanan_air($tahun)
    {
        $bobot_tekanan_air = 0.065;
        $tekanan_air = $this->Model_pelihara->get_tekanan_air($tahun);
        $total_sr = 0;
        $total_cek = 0;
        $total_07 = 0;
        $total_persentase = 0;
        $total_sr_70 = 0;

        foreach ($tekanan_air as $row) {
            $jumlah_cek = $row->jumlah_cek;
            $jumlah_07 = $row->jumlah_07;
            $persentase = ($jumlah_07 / $jumlah_cek) * 100;
            $persentase = number_format($persentase, 2);

            $total_sr += $row->jumlah_sr;
            $total_cek += $row->jumlah_cek;
            $total_07 += $row->jumlah_07;
            $total_persentase += $persentase;
            $total_sr_70 += $row->jumlah_sr_70;
        }

        $pelanggan = $this->hitung_pelanggan($tahun);
        $total_pelanggan = $pelanggan['total_pelanggan_tahun_ini'];

        $persen_tekanan_air = ($total_pelanggan ?? 0) > 0 ? ($total_sr_70 / $total_pelanggan) * 100 : 0;

        $hasil_perhitungan_tekanan_air = 0;
        if ($persen_tekanan_air === 0) {
            $hasil_perhitungan_tekanan_air = 0;
        } elseif ($persen_tekanan_air <= 20) {
            $hasil_perhitungan_tekanan_air = 1;
        } elseif ($persen_tekanan_air > 20 && $persen_tekanan_air <= 40) {
            $hasil_perhitungan_tekanan_air = 2;
        } elseif ($persen_tekanan_air > 40 && $persen_tekanan_air <= 60) {
            $hasil_perhitungan_tekanan_air = 3;
        } elseif ($persen_tekanan_air > 60 && $persen_tekanan_air <= 80) {
            $hasil_perhitungan_tekanan_air = 4;
        } elseif ($persen_tekanan_air > 80) {
            $hasil_perhitungan_tekanan_air = 5;
        }
        $hasil_tekanan_air = $hasil_perhitungan_tekanan_air * $bobot_tekanan_air;

        return [
            'jumlah_pelanggan_dilayani' => $total_sr_70,
            'total_pelanggan' => $total_pelanggan,
            'persen_tekanan_air' => $persen_tekanan_air,
            'hasil_perhitungan_tekanan_air' => $hasil_perhitungan_tekanan_air,
            'hasil_tekanan_air' => $hasil_tekanan_air
        ];
    }

    public function hitung_ganti_meter($tahun)
    {
        $bobot_ganti_meter = 0.065;
        $tera_meter = $this->Model_pelihara->get_tera_meter($tahun);
        $total_jan = $total_feb = $total_mar = $total_apr = 0;
        $total_mei = $total_jun = $total_jul = $total_agu = 0;
        $total_sep = $total_okt = $total_nov = $total_des = $total_semua_tm = 0;
        foreach ($tera_meter as $row) {
            $total_jan += $row->jan;
            $total_feb += $row->feb;
            $total_mar += $row->mar;
            $total_apr += $row->apr;
            $total_mei += $row->mei;
            $total_jun += $row->jun;
            $total_jul += $row->jul;
            $total_agu += $row->agu;
            $total_sep += $row->sep;
            $total_okt += $row->okt;
            $total_nov += $row->nov;
            $total_des += $row->des;
            $total_semua_tm += $row->total;
        }

        $ganti_meter = $this->Model_pelihara->get_ganti_meter($tahun);
        $total_jan = $total_feb = $total_mar = $total_apr = 0;
        $total_mei = $total_jun = $total_jul = $total_agu = 0;
        $total_sep = $total_okt = $total_nov = $total_des = $total_semua_gm = 0;
        foreach ($ganti_meter as $row) {
            $total_jan += $row->jan;
            $total_feb += $row->feb;
            $total_mar += $row->mar;
            $total_apr += $row->apr;
            $total_mei += $row->mei;
            $total_jun += $row->jun;
            $total_jul += $row->jul;
            $total_agu += $row->agu;
            $total_sep += $row->sep;
            $total_okt += $row->okt;
            $total_nov += $row->nov;
            $total_des += $row->des;
            $total_semua_gm += $row->total;
        }

        $total_semua_meter = $total_semua_tm + $total_semua_gm;
        $pelanggan = $this->hitung_pelanggan($tahun);
        $total_pelanggan = $pelanggan['total_pelanggan_tahun_ini'];

        $persen_ganti_meter = ($total_pelanggan ?? 0) > 0 ? ($total_semua_meter / $total_pelanggan) * 100 : 0;

        $hasil_perhitungan_ganti_meter = 0;
        if ($persen_ganti_meter === 0) {
            $hasil_perhitungan_ganti_meter = 0;
        } elseif ($persen_ganti_meter <= 5) {
            $hasil_perhitungan_ganti_meter = 1;
        } elseif ($persen_ganti_meter > 5 && $persen_ganti_meter <= 10) {
            $hasil_perhitungan_ganti_meter = 2;
        } elseif ($persen_ganti_meter > 10 && $persen_ganti_meter <= 15) {
            $hasil_perhitungan_ganti_meter = 3;
        } elseif ($persen_ganti_meter > 15 && $persen_ganti_meter <= 20) {
            $hasil_perhitungan_ganti_meter = 4;
        } elseif ($persen_ganti_meter > 20) {
            $hasil_perhitungan_ganti_meter = 5;
        }
        $hasil_ganti_meter = $hasil_perhitungan_ganti_meter * $bobot_ganti_meter;

        return [
            'total_semua_meter' => $total_semua_meter,
            'total_pelanggan' => $total_pelanggan,
            'persen_ganti_meter' => $persen_ganti_meter,
            'hasil_perhitungan_ganti_meter' => $hasil_perhitungan_ganti_meter,
            'hasil_ganti_meter' => $hasil_ganti_meter
        ];
    }

    public function hitung_pendapatan($tahun)
    {
        $bobot_nrw = 0.07;
        $pendapatan = $this->Model_langgan->get_pendapatan($tahun);
        $total_sr = 0;
        $total_vol = 0;
        $total_by_admin = 0;
        $total_by_peml = 0;
        $total_harga_air = 0;
        $total_tagihan = 0;
        $pendapatan_air_lainnya = 0;

        foreach ($pendapatan as $row) {
            $total_sr += $row->rek_air;
            $total_vol += $row->volume;
            $total_by_admin += $row->by_admin;
            $total_by_peml += $row->jas_pem;
            if ($row->id_kel_tarif != '12' && !is_null($row->id_kel_tarif)) {
                $total_harga_air += $row->harga_air;
            } else {
                $pendapatan_air_lainnya += $row->harga_air;
            }

            $tagihan = $row->by_admin + $row->jas_pem + $row->harga_air;
            $total_tagihan += $tagihan;
            // $rata = $total_tagihan / $total_vol;
        }
        $volume_produksi = $this->hitung_efisiensi_prod($tahun)['total_volume_produksi'];
        $total_dis_vol_prod = $volume_produksi - $total_vol;
        $persen_nrw = ($volume_produksi ?? 0) > 0 ? ($total_dis_vol_prod / $volume_produksi) * 100 : 0;

        $hasil_perhitungan_nrw = 0;
        if ($persen_nrw === 0) {
            $hasil_perhitungan_nrw = 0;
        } elseif ($persen_nrw <= 25) {
            $hasil_perhitungan_nrw = 5;
        } elseif ($persen_nrw > 25 && $persen_nrw <= 30) {
            $hasil_perhitungan_nrw = 4;
        } elseif ($persen_nrw > 30 && $persen_nrw <= 35) {
            $hasil_perhitungan_nrw = 3;
        } elseif ($persen_nrw > 35 && $persen_nrw <= 40) {
            $hasil_perhitungan_nrw = 2;
        } elseif ($persen_nrw > 40) {
            $hasil_perhitungan_nrw = 1;
        }
        $hasil_nrw = $hasil_perhitungan_nrw * $bobot_nrw;

        return [
            'total_sr' => $total_sr,
            'total_vol' => $total_vol,
            'total_by_admin' => $total_by_admin,
            'total_by_peml' => $total_by_peml,
            'total_harga_air' => $total_harga_air,
            'total_tagihan' => $total_tagihan,
            'pendapatan_air_lainnya' => $pendapatan_air_lainnya,
            'volume_produksi' => $volume_produksi,
            'total_dis_vol_prod' => $total_dis_vol_prod,
            'persen_nrw' => $persen_nrw,
            'hasil_perhitungan_nrw' => $hasil_perhitungan_nrw,
            'hasil_nrw' => $hasil_nrw
            // 'rata' => $rata
        ];
    }

    public function hitung_jam_ops($tahun)
    {
        $bobot_jam_ops = 0.08;
        $jam_ops = $this->Model_pelihara->get_jam_ops($tahun);
        $total_jan = $total_feb = $total_mar = $total_apr = 0;
        $total_mei = $total_jun = $total_jul = $total_agu = 0;
        $total_sep = $total_okt = $total_nov = $total_des = $total_semua =  $total_rata_rata = 0;
        $tahun_pilihan = $tahun;
        foreach ($jam_ops as $row) {
            // Ambil tahun dan bulan dari mulai_ops
            $mulai_ops_tahun = date('Y', strtotime($row->mulai_ops));
            $mulai_ops_bulan = date('m', strtotime($row->mulai_ops));
            // Tentukan jumlah bulan aktif dalam tahun yang dipilih
            if ($mulai_ops_tahun < $tahun_pilihan) {
                $jumlah_bulan_aktif = 12;
            } elseif ($mulai_ops_tahun == $tahun_pilihan) {
                $jumlah_bulan_aktif = 13 - $mulai_ops_bulan; // Misalnya mulai di Agustus (bulan 8), maka 12 - 8 + 1 = 5
            } else {
                $jumlah_bulan_aktif = 0; // Jika mulai_ops di tahun setelahnya, berarti tidak aktif
            }
            // Hitung rata-rata hanya jika ada bulan aktif
            $rata_rata = ($jumlah_bulan_aktif > 0) ? round($row->total / $jumlah_bulan_aktif, 2) : 0;

            $total_jan += $row->jan;
            $total_feb += $row->feb;
            $total_mar += $row->mar;
            $total_apr += $row->apr;
            $total_mei += $row->mei;
            $total_jun += $row->jun;
            $total_jul += $row->jul;
            $total_agu += $row->agu;
            $total_sep += $row->sep;
            $total_okt += $row->okt;
            $total_nov += $row->nov;
            $total_des += $row->des;
            $total_semua += $row->total;
            $total_rata_rata += $rata_rata;
        }

        $query = $this->db->query("SELECT COUNT(*) AS total FROM ek_sb_mag WHERE status_sb_mag = 1");
        $row = $query->row();
        $jumlah_sb_mag = $row->total;

        // $jam_ops_setahun = $total_semua / 33;
        $jam_ops_setahun = $total_semua / $jumlah_sb_mag;
        $persen_jam_ops = ($total_semua > 0) ? $jam_ops_setahun / 365 : 0;
        $hasil_perhitungan_jam_ops = 0;
        if ($persen_jam_ops === 0) {
            $hasil_perhitungan_jam_ops = 0;
        } elseif ($persen_jam_ops >= 21 && $persen_jam_ops <= 24) {
            $hasil_perhitungan_jam_ops = 5;
        } elseif ($persen_jam_ops >= 18 && $persen_jam_ops < 21) {
            $hasil_perhitungan_jam_ops = 4;
        } elseif ($persen_jam_ops >= 15 && $persen_jam_ops < 18) {
            $hasil_perhitungan_jam_ops = 3;
        } elseif ($persen_jam_ops >= 12 && $persen_jam_ops < 15) {
            $hasil_perhitungan_jam_ops = 2;
        } elseif ($persen_jam_ops < 12) {
            $hasil_perhitungan_jam_ops = 1;
        }
        $hasil_jam_ops = $hasil_perhitungan_jam_ops * $bobot_jam_ops;

        return [
            'total_jam_ops' => $total_semua,
            'jam_ops_setahun' => $jam_ops_setahun,
            'persen_jam_ops' => $persen_jam_ops,
            'hasil_jam_ops' => $hasil_jam_ops,
            'hasil_perhitungan_jam_ops' => $hasil_perhitungan_jam_ops
        ];
    }
    // akhir aspek operasional

    // aspek sdm
    public function hitung_jumlah_pegawai($tahun)
    {
        $bobot_pegawai = 0.07;
        // $jumlah_pegawai = $this->Model_umum->get_jumlah_pegawai($tahun);
        if ($tahun == 2025) {
            $jumlah_pegawai = 0;
        } elseif ($tahun == 2024) {
            $jumlah_pegawai = 163;
        } else {
            $jumlah_pegawai = $this->Model_umum->get_jumlah_pegawai($tahun);
        }
        $pelanggan = $this->hitung_pelanggan($tahun);
        $jumlah_pelanggan_tahun_ini = $pelanggan['jumlah_pelanggan_tahun_ini'];

        $persen_pegawai = ($jumlah_pelanggan_tahun_ini > 0) ? $jumlah_pegawai / $jumlah_pelanggan_tahun_ini * 1000 : 0;
        $hasil_perhitungan_pegawai = 0;

        if ($persen_pegawai === 0) {
            $hasil_perhitungan_pegawai = 0;
        } elseif ($persen_pegawai < 7) {
            $hasil_perhitungan_pegawai = 5;
        } elseif ($persen_pegawai >= 7 && $persen_pegawai < 9) {
            $hasil_perhitungan_pegawai = 4;
        } elseif ($persen_pegawai >= 9 && $persen_pegawai < 11) {
            $hasil_perhitungan_pegawai = 3;
        } elseif ($persen_pegawai >= 11 && $persen_pegawai <= 12) {
            $hasil_perhitungan_pegawai = 2;
        } elseif ($persen_pegawai > 12) {
            $hasil_perhitungan_pegawai = 1;
        }

        $hasil_pegawai = $hasil_perhitungan_pegawai * $bobot_pegawai;

        return [
            'jumlah_pegawai' => $jumlah_pegawai,
            'jumlah_pelanggan_tahun_ini' => $jumlah_pelanggan_tahun_ini,
            'persen_pegawai' => $persen_pegawai,
            'hasil_pegawai' => $hasil_pegawai,
            'hasil_perhitungan_pegawai' => $hasil_perhitungan_pegawai
        ];
    }

    public function hitung_diklat_pegawai($tahun)
    {
        $bobot_diklat = 0.04;
        if ($tahun == 2025) {
            $jumlah_diklat = 0;
            $jumlah_pegawai = 0;
            $biaya_diklat = 0;
            $biaya_pegawai = 0;
        } elseif ($tahun == 2024) {
            $jumlah_diklat = 163;
            $jumlah_pegawai = 163;
            $biaya_diklat = 112100400;
            $biaya_pegawai =  10081291961;
        } else {
            $jumlah_diklat = 162;
            $jumlah_pegawai = 162;
            $biaya_diklat = 113090800;
            $biaya_pegawai =  10248847850;
        }

        $persen_diklat = ($jumlah_diklat > 0) ? $jumlah_diklat / $jumlah_pegawai * 100 : 0;
        $hasil_perhitungan_diklat = 0;

        if ($persen_diklat === 0) {
            $hasil_perhitungan_diklat = 0;
        } elseif ($persen_diklat > 80) {
            $hasil_perhitungan_diklat = 5;
        } elseif ($persen_diklat >= 60 && $persen_diklat < 80) {
            $hasil_perhitungan_diklat = 4;
        } elseif ($persen_diklat >= 40 && $persen_diklat < 60) {
            $hasil_perhitungan_diklat = 3;
        } elseif ($persen_diklat >= 20 && $persen_diklat < 40) {
            $hasil_perhitungan_diklat = 2;
        } elseif ($persen_diklat < 20) {
            $hasil_perhitungan_diklat = 1;
        }

        $hasil_diklat = $hasil_perhitungan_diklat * $bobot_diklat;

        $persen_biaya_diklat = ($biaya_diklat > 0) ? $biaya_diklat / $biaya_pegawai * 100 : 0;
        $hasil_perhitungan_biaya_diklat = 0;

        if ($persen_biaya_diklat === 0) {
            $hasil_perhitungan_biaya_diklat = 0;
        } elseif ($persen_biaya_diklat > 10) {
            $hasil_perhitungan_biaya_diklat = 5;
        } elseif ($persen_biaya_diklat >= 7.5 && $persen_biaya_diklat < 10) {
            $hasil_perhitungan_biaya_diklat = 4;
        } elseif ($persen_biaya_diklat >= 5 && $persen_biaya_diklat < 7.5) {
            $hasil_perhitungan_biaya_diklat = 3;
        } elseif ($persen_biaya_diklat >= 2.5 && $persen_biaya_diklat < 5) {
            $hasil_perhitungan_biaya_diklat = 2;
        } elseif ($persen_biaya_diklat < 2.5) {
            $hasil_perhitungan_biaya_diklat = 1;
        }

        $hasil_biaya_diklat = $hasil_perhitungan_biaya_diklat * $bobot_diklat;

        return [
            'jumlah_diklat' => $jumlah_diklat,
            'jumlah_pegawai' => $jumlah_pegawai,
            'persen_diklat' => $persen_diklat,
            'hasil_diklat' => $hasil_diklat,
            'hasil_perhitungan_diklat' => $hasil_perhitungan_diklat,
            'biaya_diklat' => $biaya_diklat,
            'biaya_pegawai' => $biaya_pegawai,
            'persen_biaya_diklat' => $persen_biaya_diklat,
            'hasil_perhitungan_biaya_diklat' => $hasil_perhitungan_biaya_diklat,
            'hasil_biaya_diklat' => $hasil_biaya_diklat
        ];
    }

    // akhir aspek sdm
}
