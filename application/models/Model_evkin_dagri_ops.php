<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_evkin_dagri_ops extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_labarugi');
        $this->load->model('Model_lap_keuangan');
        $this->load->model('Model_langgan');
        $this->load->model('Model_pelihara');
        $this->load->model('Model_umum');
        $this->load->model('Model_penyusutan');
        $this->load->model('Model_amortisasi');
        $this->load->model('Model_evkin');
    }

    public function hitung_cak_layanan($tahun)
    {
        $data_penduduk_ini = $this->Model_langgan->get_data_penduduk($tahun);
        $data_penduduk_lalu = $this->Model_langgan->get_data_penduduk($tahun - 1);
        $data_penduduk_2_lalu = $this->Model_langgan->get_data_penduduk($tahun - 2);

        $jumlah_penduduk_ini = 0;
        $total_kk_ini  = 0;
        foreach ($data_penduduk_ini as $dp) {
            $jumlah_penduduk_ini += $dp->jumlah_penduduk;
            $total_kk_ini += (int) $dp->jumlah_kk;
        }
        $rata_jiwa_kk_ini = $total_kk_ini != 0 ? $jumlah_penduduk_ini / $total_kk_ini : 0;

        $jumlah_penduduk_lalu = 0;
        $total_kk_lalu  = 0;
        foreach ($data_penduduk_lalu as $dp) {
            $jumlah_penduduk_lalu += $dp->jumlah_penduduk;
            $total_kk_lalu += (int) $dp->jumlah_kk;
        }
        $rata_jiwa_kk_lalu = $total_kk_lalu != 0 ? $jumlah_penduduk_lalu / $total_kk_lalu : 0;

        $jumlah_penduduk_2_lalu = 0;
        $total_kk_2_lalu  = 0;
        foreach ($data_penduduk_2_lalu as $dp) {
            $jumlah_penduduk_2_lalu += $dp->jumlah_penduduk;
            $total_kk_2_lalu += (int) $dp->jumlah_kk;
        }
        $rata_jiwa_kk_2_lalu = $total_kk_2_lalu != 0 ? $jumlah_penduduk_2_lalu / $total_kk_2_lalu : 0;

        // data pelanggan
        $data_pelanggan_ini = $this->Model_langgan->get_data_pelanggan($tahun);
        $total_rt_dom_ini = 0;
        $total_niaga_dom_ini = 0;
        $total_sl_hu_dom_ini = 0;
        $total_n_aktif_dom_ini = 0;

        foreach ($data_pelanggan_ini as $dl) {
            $total_rt_dom_ini += (int) $dl->rt_dom;
            $total_niaga_dom_ini += (int) $dl->niaga_dom;
            $total_sl_hu_dom_ini += (int) $dl->sl_hu_dom;
            $total_n_aktif_dom_ini += (int) $dl->n_aktif_dom;
        }

        $pelanggan_ini = [
            'total_rt_dom_ini' => $total_rt_dom_ini,
            'total_niaga_dom_ini' => $total_niaga_dom_ini,
            'total_sl_hu_dom_ini' => $total_sl_hu_dom_ini,
            'total_n_aktif_dom_ini' => $total_n_aktif_dom_ini
        ];

        $total_pelanggan_ini =
            ($pelanggan_ini['total_rt_dom_ini'] ?? 0) +
            ($pelanggan_ini['total_niaga_dom_ini'] ?? 0) +
            ($pelanggan_ini['total_sl_hu_dom_ini'] ?? 0) +
            ($pelanggan_ini['total_n_aktif_dom_ini'] ?? 0);

        $total_jiwa_terlayani_ini = $pelanggan_ini['total_rt_dom_ini'] * $rata_jiwa_kk_ini + $pelanggan_ini['total_niaga_dom_ini'] * $rata_jiwa_kk_ini + $pelanggan_ini['total_sl_hu_dom_ini'] * 100 + $pelanggan_ini['total_n_aktif_dom_ini'] * $rata_jiwa_kk_ini;

        $data_pelanggan_lalu = $this->Model_langgan->get_data_pelanggan($tahun - 1);
        $total_rt_dom_lalu = 0;
        $total_niaga_dom_lalu = 0;
        $total_sl_hu_dom_lalu = 0;
        $total_n_aktif_dom_lalu = 0;

        foreach ($data_pelanggan_lalu as $dl) {
            $total_rt_dom_lalu += (int) $dl->rt_dom;
            $total_niaga_dom_lalu += (int) $dl->niaga_dom;
            $total_sl_hu_dom_lalu += (int) $dl->sl_hu_dom;
            $total_n_aktif_dom_lalu += (int) $dl->n_aktif_dom;
        }

        $pelanggan_lalu = [
            'total_rt_dom_lalu' => $total_rt_dom_lalu,
            'total_niaga_dom_lalu' => $total_niaga_dom_lalu,
            'total_sl_hu_dom_lalu' => $total_sl_hu_dom_lalu,
            'total_n_aktif_dom_lalu' => $total_n_aktif_dom_lalu
        ];

        $total_pelanggan_lalu =
            ($pelanggan_lalu['total_rt_dom_lalu'] ?? 0) +
            ($pelanggan_lalu['total_niaga_dom_lalu'] ?? 0) +
            ($pelanggan_lalu['total_sl_hu_dom_lalu'] ?? 0) +
            ($pelanggan_lalu['total_n_aktif_dom_lalu'] ?? 0);

        $total_jiwa_terlayani_lalu = $pelanggan_lalu['total_rt_dom_lalu'] * $rata_jiwa_kk_lalu + $pelanggan_lalu['total_niaga_dom_lalu'] * $rata_jiwa_kk_lalu + $pelanggan_lalu['total_sl_hu_dom_lalu'] * 100 + $pelanggan_lalu['total_n_aktif_dom_lalu'] * $rata_jiwa_kk_lalu;

        $data_pelanggan_2_lalu = $this->Model_langgan->get_data_pelanggan($tahun - 1);
        $total_rt_dom_2_lalu = 0;
        $total_niaga_dom_2_lalu = 0;
        $total_sl_hu_dom_2_lalu = 0;
        $total_n_aktif_dom_2_lalu = 0;

        foreach ($data_pelanggan_2_lalu as $dl) {
            $total_rt_dom_2_lalu += (int) $dl->rt_dom;
            $total_niaga_dom_2_lalu += (int) $dl->niaga_dom;
            $total_sl_hu_dom_2_lalu += (int) $dl->sl_hu_dom;
            $total_n_aktif_dom_2_lalu += (int) $dl->n_aktif_dom;
        }

        $pelanggan_2_lalu = [
            'total_rt_dom_2_lalu' => $total_rt_dom_2_lalu,
            'total_niaga_dom_2_lalu' => $total_niaga_dom_2_lalu,
            'total_sl_hu_dom_2_lalu' => $total_sl_hu_dom_2_lalu,
            'total_n_aktif_dom_2_lalu' => $total_n_aktif_dom_2_lalu
        ];

        $total_pelanggan_2_lalu =
            ($pelanggan_2_lalu['total_rt_dom_2_lalu'] ?? 0) +
            ($pelanggan_2_lalu['total_niaga_dom_2_lalu'] ?? 0) +
            ($pelanggan_2_lalu['total_sl_hu_dom_2_lalu'] ?? 0) +
            ($pelanggan_2_lalu['total_n_aktif_dom_2_lalu'] ?? 0);

        $total_jiwa_terlayani_2_lalu = $pelanggan_2_lalu['total_rt_dom_2_lalu'] * $rata_jiwa_kk_2_lalu + $pelanggan_2_lalu['total_niaga_dom_2_lalu'] * $rata_jiwa_kk_2_lalu + $pelanggan_2_lalu['total_sl_hu_dom_2_lalu'] * 100 + $pelanggan_2_lalu['total_n_aktif_dom_2_lalu'] * $rata_jiwa_kk_lalu;

        $rasio_cak_layanan_ini = $jumlah_penduduk_ini != 0 ? $total_jiwa_terlayani_ini / $jumlah_penduduk_ini * 100 : 0;

        if ($rasio_cak_layanan_ini == 0) {
            $hasil_perhitungan_rasio_cak_layanan_ini = 0;
        } elseif ($rasio_cak_layanan_ini <= 15) {
            $hasil_perhitungan_rasio_cak_layanan_ini = 1;
        } elseif ($rasio_cak_layanan_ini > 15 && $rasio_cak_layanan_ini <= 30) {
            $hasil_perhitungan_rasio_cak_layanan_ini = 2;
        } elseif ($rasio_cak_layanan_ini > 30 && $rasio_cak_layanan_ini <= 45) {
            $hasil_perhitungan_rasio_cak_layanan_ini = 3;
        } elseif ($rasio_cak_layanan_ini > 45 && $rasio_cak_layanan_ini <= 60) {
            $hasil_perhitungan_rasio_cak_layanan_ini = 4;
        } elseif ($rasio_cak_layanan_ini > 60) {
            $hasil_perhitungan_rasio_cak_layanan_ini = 5;
        }

        $rasio_cak_layanan_lalu = $jumlah_penduduk_lalu != 0 ? $total_jiwa_terlayani_lalu / $jumlah_penduduk_lalu * 100 : 0;

        if ($rasio_cak_layanan_lalu == 0) {
            $hasil_perhitungan_rasio_cak_layanan_lalu = 0;
        } elseif ($rasio_cak_layanan_lalu <= 15) {
            $hasil_perhitungan_rasio_cak_layanan_lalu = 1;
        } elseif ($rasio_cak_layanan_lalu > 15 && $rasio_cak_layanan_lalu <= 30) {
            $hasil_perhitungan_rasio_cak_layanan_lalu = 2;
        } elseif ($rasio_cak_layanan_lalu > 30 && $rasio_cak_layanan_lalu <= 45) {
            $hasil_perhitungan_rasio_cak_layanan_lalu = 3;
        } elseif ($rasio_cak_layanan_lalu > 45 && $rasio_cak_layanan_lalu <= 60) {
            $hasil_perhitungan_rasio_cak_layanan_lalu = 4;
        } elseif ($rasio_cak_layanan_lalu > 60) {
            $hasil_perhitungan_rasio_cak_layanan_lalu = 5;
        }

        $rasio_cak_layanan_2_lalu = $jumlah_penduduk_2_lalu != 0 ? $total_jiwa_terlayani_2_lalu / $jumlah_penduduk_2_lalu * 100 : 0;

        if ($rasio_cak_layanan_2_lalu == 0) {
            $hasil_perhitungan_rasio_cak_layanan_2_lalu = 0;
        } elseif ($rasio_cak_layanan_2_lalu <= 15) {
            $hasil_perhitungan_rasio_cak_layanan_2_lalu = 1;
        } elseif ($rasio_cak_layanan_2_lalu > 15 && $rasio_cak_layanan_2_lalu <= 30) {
            $hasil_perhitungan_rasio_cak_layanan_2_lalu = 2;
        } elseif ($rasio_cak_layanan_2_lalu > 30 && $rasio_cak_layanan_2_lalu <= 45) {
            $hasil_perhitungan_rasio_cak_layanan_2_lalu = 3;
        } elseif ($rasio_cak_layanan_2_lalu > 45 && $rasio_cak_layanan_2_lalu <= 60) {
            $hasil_perhitungan_rasio_cak_layanan_2_lalu = 4;
        } elseif ($rasio_cak_layanan_2_lalu > 60) {
            $hasil_perhitungan_rasio_cak_layanan_2_lalu = 5;
        }

        if ($rasio_cak_layanan_ini == 0) {
            $bonus_rasio_cak_layanan_ini = 0;
        } else {
            $bonus_rasio_cak_layanan_ini = $rasio_cak_layanan_ini - $rasio_cak_layanan_lalu;
        }

        if ($bonus_rasio_cak_layanan_ini == 0) {
            $hasil_bonus_perhitungan_rasio_cak_layanan_ini = 0;
        } elseif ($bonus_rasio_cak_layanan_ini <= 2) {
            $hasil_bonus_perhitungan_rasio_cak_layanan_ini = 1;
        } elseif ($bonus_rasio_cak_layanan_ini > 2 && $bonus_rasio_cak_layanan_ini <= 4) {
            $hasil_bonus_perhitungan_rasio_cak_layanan_ini = 2;
        } elseif ($bonus_rasio_cak_layanan_ini > 4 && $bonus_rasio_cak_layanan_ini <= 6) {
            $hasil_bonus_perhitungan_rasio_cak_layanan_ini = 3;
        } elseif ($bonus_rasio_cak_layanan_ini > 6 && $bonus_rasio_cak_layanan_ini <= 8) {
            $hasil_bonus_perhitungan_rasio_cak_layanan_ini = 4;
        } elseif ($bonus_rasio_cak_layanan_ini > 8) {
            $hasil_bonus_perhitungan_rasio_cak_layanan_ini = 5;
        }

        $bonus_rasio_cak_layanan_lalu = $rasio_cak_layanan_lalu - $rasio_cak_layanan_2_lalu;
        if ($bonus_rasio_cak_layanan_lalu == 0) {
            $hasil_bonus_perhitungan_rasio_cak_layanan_lalu = 0;
        } elseif ($bonus_rasio_cak_layanan_lalu <= 2) {
            $hasil_bonus_perhitungan_rasio_cak_layanan_lalu = 1;
        } elseif ($bonus_rasio_cak_layanan_lalu > 2 && $bonus_rasio_cak_layanan_lalu <= 4) {
            $hasil_bonus_perhitungan_rasio_cak_layanan_lalu = 2;
        } elseif ($bonus_rasio_cak_layanan_lalu > 4 && $bonus_rasio_cak_layanan_lalu <= 6) {
            $hasil_bonus_perhitungan_rasio_cak_layanan_lalu = 3;
        } elseif ($bonus_rasio_cak_layanan_lalu > 6 && $bonus_rasio_cak_layanan_lalu <= 8) {
            $hasil_bonus_perhitungan_rasio_cak_layanan_lalu = 4;
        } elseif ($bonus_rasio_cak_layanan_lalu > 8) {
            $hasil_bonus_perhitungan_rasio_cak_layanan_lalu = 5;
        }

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'jumlah_penduduk' => $jumlah_penduduk_ini,
                'jml_plgn_terlayani' => $total_jiwa_terlayani_ini,
                'rasio_cak_layanan' => $rasio_cak_layanan_ini,
                'hasil_perhitungan_rasio_cak_layanan' => $hasil_perhitungan_rasio_cak_layanan_ini,
                'bonus_rasio_cak_layanan' => $bonus_rasio_cak_layanan_ini,
                'hasil_bonus_perhitungan_rasio_cak_layanan' => $hasil_bonus_perhitungan_rasio_cak_layanan_ini
            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'jumlah_penduduk' => $jumlah_penduduk_lalu,
                'jml_plgn_terlayani' => $total_jiwa_terlayani_lalu,
                'rasio_cak_layanan' => $rasio_cak_layanan_lalu,
                'hasil_perhitungan_rasio_cak_layanan' => $hasil_perhitungan_rasio_cak_layanan_lalu,
                'bonus_rasio_cak_layanan' => $bonus_rasio_cak_layanan_lalu,
                'hasil_bonus_perhitungan_rasio_cak_layanan' => $hasil_bonus_perhitungan_rasio_cak_layanan_lalu
            ],
            'tahun_2_lalu' => [
                'tahun' => $tahun - 2,
                'jumlah_penduduk' => $jumlah_penduduk_2_lalu,
                'jml_plgn_terlayani' => $total_jiwa_terlayani_2_lalu,
                'rasio_cak_layanan' => $rasio_cak_layanan_2_lalu,
                'hasil_perhitungan_rasio_cak_layanan' => $hasil_perhitungan_rasio_cak_layanan_2_lalu
            ]
        ];
    }

    public function hitung_kualitas_air($tahun)
    {
        $kualitas_air_ini = $this->getKualitasAir($tahun);
        $hasil_kualitas_air_ini = $this->nilaiKualitasAir($kualitas_air_ini);

        $kualitas_air_lalu = $this->getKualitasAir($tahun - 1);
        $hasil_kualitas_air_lalu = $this->nilaiKualitasAir($kualitas_air_lalu);

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'kualitas_air' => $kualitas_air_ini,
                'hasil_kualitas_air' => $hasil_kualitas_air_ini
            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'kualitas_air' => $kualitas_air_lalu,
                'hasil_kualitas_air' => $hasil_kualitas_air_lalu
            ]
        ];
    }

    private function getKualitasAir($tahun)
    {
        $query = $this->db->select('hasil')
            ->from('ek_aspek_ops_dagri')
            ->where('tahun_aspek', $tahun)
            ->where('penilaian', 'Kualitas Air Distribusi')
            ->get()
            ->row();

        return $query ? $query->hasil : '-'; // tampilkan strip jika tidak ada
    }

    private function nilaiKualitasAir($hasil)
    {
        if ($hasil === 'Memenuhi syarat air minum') {
            return 3;
        } elseif ($hasil === 'Memenuhi syarat air bersih') {
            return 2;
        } elseif ($hasil === 'Tidak memenuhi syarat') {
            return 1;
        }

        return 0; // default jika tidak dikenal
    }

    public function hitung_kontinuitas_air($tahun)
    {
        $kontinuitas_air_ini = $this->getKontinuitasAir($tahun);
        $hasil_kontinuitas_air_ini = $this->nilaiKontinuitasAir($kontinuitas_air_ini);

        $kontinuitas_air_lalu = $this->getKontinuitasAir($tahun - 1);
        $hasil_kontinuitas_air_lalu = $this->nilaiKontinuitasAir($kontinuitas_air_lalu);

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'kontinuitas_air' => $kontinuitas_air_ini,
                'hasil_kontinuitas_air' => $hasil_kontinuitas_air_ini
            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'kontinuitas_air' => $kontinuitas_air_lalu,
                'hasil_kontinuitas_air' => $hasil_kontinuitas_air_lalu
            ]
        ];
    }

    private function getKontinuitasAir($tahun)
    {
        $query = $this->db->select('hasil')
            ->from('ek_aspek_ops_dagri')
            ->where('tahun_aspek', $tahun)
            ->where('penilaian', 'Kontinuitas Air')
            ->get()
            ->row();

        return $query ? $query->hasil : '-';
    }

    private function nilaiKontinuitasAir($hasil)
    {
        if ($hasil === 'semua pelanggan mendapat aliran air 24 jam') {
            return 2;
        } elseif ($hasil === 'belum semua pelanggan mendapat aliran air 24 jam') {
            return 1;
        }

        return 0; // default jika tidak dikenal
    }

    public function hitung_service_point($tahun)
    {
        $service_point_ini = $this->getServicePoint($tahun);
        $hasil_service_point_ini = $this->nilaiServicePoint($service_point_ini);

        $service_point_lalu = $this->getServicePoint($tahun - 1);
        $hasil_service_point_lalu = $this->nilaiServicePoint($service_point_lalu);

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'service_point' => $service_point_ini,
                'hasil_service_point' => $hasil_service_point_ini
            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'service_point' => $service_point_lalu,
                'hasil_service_point' => $hasil_service_point_lalu
            ]
        ];
    }

    private function getServicePoint($tahun)
    {
        $query = $this->db->select('hasil')
            ->from('ek_aspek_ops_dagri')
            ->where('tahun_aspek', $tahun)
            ->where('penilaian', 'Service Point')
            ->get()
            ->row();

        return $query ? $query->hasil : '-';
    }

    private function nilaiServicePoint($hasil)
    {
        if ($hasil === 'tersedia service point') {
            return 2;
        } elseif ($hasil === 'tidak tersedia service point') {
            return 1;
        }

        return 0; // default jika tidak dikenal
    }

    public function hitung_sr_baru($tahun)
    {
        $sr_baru_ini = $this->getSrBaru($tahun);
        $hasil_sr_baru_ini = $this->nilaiSrBaru($sr_baru_ini);

        $sr_baru_lalu = $this->getSrBaru($tahun - 1);
        $hasil_sr_baru_lalu = $this->nilaiSrBaru($sr_baru_lalu);

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'sr_baru' => $sr_baru_ini,
                'hasil_sr_baru' => $hasil_sr_baru_ini
            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'sr_baru' => $sr_baru_lalu,
                'hasil_sr_baru' => $hasil_sr_baru_lalu
            ]
        ];
    }

    private function getSrBaru($tahun)
    {
        $query = $this->db->select('hasil')
            ->from('ek_aspek_ops_dagri')
            ->where('tahun_aspek', $tahun)
            ->where('penilaian', 'Kecepatan Penyambungan Baru')
            ->get()
            ->row();

        return $query ? $query->hasil : '-';
    }

    private function nilaiSrBaru($hasil)
    {
        if ($hasil === '<= 6 hari kerja') {
            return 2;
        } elseif ($hasil === '> 6 hari kerja') {
            return 1;
        }

        return 0; // default jika tidak dikenal
    }

    public function hitung_nrw($tahun)
    {
        $kapasitas_produksi_ini = $this->Model_pelihara->get_kapasitas_produksi($tahun);
        $total_kap_pasang_ini = 0;
        $total_terpasang_ini = 0;
        $total_tidak_manfaat_ini = 0;
        $total_volume_produksi_ini = 0;
        $total_kap_riil_ini = 0;
        $total_kap_menganggur_ini = 0;

        foreach ($kapasitas_produksi_ini as $row) {
            $kap_pasang_ini = $row->kap_pasang;
            $terpasang_ini = $row->terpasang;
            $tidak_manfaat_ini = $row->tidak_manfaat;
            $volume_produksi_ini = $row->volume_produksi;
            $kap_riil_ini = $terpasang_ini - $tidak_manfaat_ini;
            $kap_menganggur_ini = $kap_riil_ini - $volume_produksi_ini;


            $total_kap_pasang_ini += $kap_pasang_ini;
            $total_terpasang_ini += $terpasang_ini;
            $total_tidak_manfaat_ini += $tidak_manfaat_ini;
            $total_volume_produksi_ini += $volume_produksi_ini;
            $total_kap_riil_ini += $kap_riil_ini;
            $total_kap_menganggur_ini += $kap_menganggur_ini;
        }

        $kapasitas_produksi_lalu = $this->Model_pelihara->get_kapasitas_produksi($tahun - 1);
        $total_kap_pasang_lalu = 0;
        $total_terpasang_lalu = 0;
        $total_tidak_manfaat_lalu = 0;
        $total_volume_produksi_lalu = 0;
        $total_kap_riil_lalu = 0;
        $total_kap_menganggur_lalu = 0;

        foreach ($kapasitas_produksi_lalu as $row) {
            $kap_pasang_lalu = $row->kap_pasang;
            $terpasang_lalu = $row->terpasang;
            $tidak_manfaat_lalu = $row->tidak_manfaat;
            $volume_produksi_lalu = $row->volume_produksi;
            $kap_riil_lalu = $terpasang_lalu - $tidak_manfaat_lalu;
            $kap_menganggur_lalu = $kap_riil_lalu - $volume_produksi_lalu;

            $total_kap_pasang_lalu += $kap_pasang_lalu;
            $total_terpasang_lalu += $terpasang_lalu;
            $total_tidak_manfaat_lalu += $tidak_manfaat_lalu;
            $total_volume_produksi_lalu += $volume_produksi_lalu;
            $total_kap_riil_lalu += $kap_riil_lalu;
            $total_kap_menganggur_lalu += $kap_menganggur_lalu;
        }

        $pendapatan_ini = $this->Model_evkin->hitung_pendapatan($tahun);
        $total_vol_ini = $pendapatan_ini['total_vol'];

        $pendapatan_lalu = $this->Model_evkin->hitung_pendapatan($tahun - 1);
        $total_vol_lalu = $pendapatan_lalu['total_vol'];

        $total_nrw_ini = $total_volume_produksi_ini - $total_vol_ini;
        $total_nrw_lalu = $total_volume_produksi_lalu - $total_vol_lalu;

        $nilai_nrw_ini = $total_nrw_ini != 0 ? $total_nrw_ini / $total_volume_produksi_ini * 100 : 0;
        if ($nilai_nrw_ini == 0) {
            $hasil_perhitungan_nilai_nrw_ini = 0;
        } elseif ($nilai_nrw_ini <= 20) {
            $hasil_perhitungan_nilai_nrw_ini = 4;
        } elseif ($nilai_nrw_ini > 20 && $nilai_nrw_ini <= 30) {
            $hasil_perhitungan_nilai_nrw_ini = 3;
        } elseif ($nilai_nrw_ini > 30 && $nilai_nrw_ini <= 40) {
            $hasil_perhitungan_nilai_nrw_ini = 2;
        } elseif ($nilai_nrw_ini > 40) {
            $hasil_perhitungan_nilai_nrw_ini = 1;
        }

        $nilai_nrw_lalu = $total_nrw_lalu != 0 ? $total_nrw_lalu / $total_volume_produksi_lalu * 100 : 0;

        if ($nilai_nrw_lalu == 0) {
            $hasil_perhitungan_nilai_nrw_lalu = 0;
        } elseif ($nilai_nrw_lalu <= 20) {
            $hasil_perhitungan_nilai_nrw_lalu = 4;
        } elseif ($nilai_nrw_lalu > 20 && $nilai_nrw_lalu <= 30) {
            $hasil_perhitungan_nilai_nrw_lalu = 3;
        } elseif ($nilai_nrw_lalu > 30 && $nilai_nrw_lalu <= 40) {
            $hasil_perhitungan_nilai_nrw_lalu = 2;
        } elseif ($nilai_nrw_lalu > 40) {
            $hasil_perhitungan_nilai_nrw_lalu = 1;
        }


        $kap_riil_ini = $total_kap_riil_ini;
        $kap_riil_lalu = $total_kap_riil_lalu;
        $terpasang_ini = $total_terpasang_ini;
        $terpasang_lalu = $total_terpasang_lalu;

        $nilai_kapasitas_ini = $terpasang_ini != 0 ? $kap_riil_ini / $terpasang_ini * 100 : 0;
        if ($nilai_kapasitas_ini == 0) {
            $hasil_perhitungan_nilai_kapasitas_ini = 0;
        } elseif ($nilai_kapasitas_ini > 90) {
            $hasil_perhitungan_nilai_kapasitas_ini = 4;
        } elseif ($nilai_kapasitas_ini > 80 && $nilai_kapasitas_ini <= 90) {
            $hasil_perhitungan_nilai_kapasitas_ini = 3;
        } elseif ($nilai_kapasitas_ini > 70 && $nilai_kapasitas_ini <= 80) {
            $hasil_perhitungan_nilai_kapasitas_ini = 2;
        } elseif ($nilai_kapasitas_ini <= 70) {
            $hasil_perhitungan_nilai_kapasitas_ini = 1;
        }

        $nilai_kapasitas_lalu = $terpasang_lalu != 0 ? $kap_riil_lalu / $terpasang_lalu * 100 : 0;
        if ($nilai_kapasitas_lalu == 0) {
            $hasil_perhitungan_nilai_kapasitas_lalu = 0;
        } elseif ($nilai_kapasitas_lalu > 90) {
            $hasil_perhitungan_nilai_kapasitas_lalu = 4;
        } elseif ($nilai_kapasitas_lalu > 80 && $nilai_kapasitas_lalu <= 90) {
            $hasil_perhitungan_nilai_kapasitas_lalu = 3;
        } elseif ($nilai_kapasitas_lalu > 70 && $nilai_kapasitas_lalu <= 80) {
            $hasil_perhitungan_nilai_kapasitas_lalu = 2;
        } elseif ($nilai_kapasitas_lalu <= 70) {
            $hasil_perhitungan_nilai_kapasitas_lalu = 1;
        }

        $tera_meter_ini = $this->Model_pelihara->get_tera_meter($tahun);
        $total_tera_meter_ini = 0;
        foreach ($tera_meter_ini as $row) {
            $total_tera_meter_ini += $row->total;
        }

        $tera_meter_lalu = $this->Model_pelihara->get_tera_meter($tahun - 1);
        $total_tera_meter_lalu = 0;
        foreach ($tera_meter_lalu as $row) {
            $total_tera_meter_lalu += $row->total;
        }

        $ganti_meter_ini = $this->Model_pelihara->get_ganti_meter($tahun);
        $total_ganti_meter_ini = 0;
        foreach ($ganti_meter_ini as $row) {
            $total_ganti_meter_ini += $row->total;
        }

        $ganti_meter_lalu = $this->Model_pelihara->get_ganti_meter($tahun - 1);
        $total_ganti_meter_lalu = 0;
        foreach ($ganti_meter_lalu as $row) {
            $total_ganti_meter_lalu += $row->total;
        }

        $water_meter_ini = $total_tera_meter_ini + $total_ganti_meter_ini;
        $water_meter_lalu = $total_tera_meter_lalu + $total_ganti_meter_lalu;

        $data_pelanggan_ini = $this->Model_langgan->get_data_pelanggan($tahun);
        $total_pelanggan_ini = 0;
        foreach ($data_pelanggan_ini as $data) {
            $baris_total =
                ($data->n_aktif_dom ?? 0) +
                ($data->rt_dom ?? 0) +
                ($data->niaga_dom ?? 0) +
                ($data->sl_kom_dom ?? 0) +
                ($data->unit_kom_dom ?? 0) +
                ($data->sl_hu_dom ?? 0) +
                ($data->n_aktif_n_dom ?? 0) +
                ($data->sosial_n_dom ?? 0) +
                ($data->niaga_n_dom ?? 0) +
                ($data->ind_n_dom ?? 0) +
                ($data->inst_n_dom ?? 0) +
                ($data->k2_n_dom ?? 0) +
                ($data->lain_n_dom ?? 0);

            $total_pelanggan_ini += $baris_total;
        }

        $data_pelanggan_lalu = $this->Model_langgan->get_data_pelanggan($tahun - 1);
        $total_pelanggan_lalu = 0;
        foreach ($data_pelanggan_lalu as $data) {
            $baris_total =
                ($data->n_aktif_dom ?? 0) +
                ($data->rt_dom ?? 0) +
                ($data->niaga_dom ?? 0) +
                ($data->sl_kom_dom ?? 0) +
                ($data->unit_kom_dom ?? 0) +
                ($data->sl_hu_dom ?? 0) +
                ($data->n_aktif_n_dom ?? 0) +
                ($data->sosial_n_dom ?? 0) +
                ($data->niaga_n_dom ?? 0) +
                ($data->ind_n_dom ?? 0) +
                ($data->inst_n_dom ?? 0) +
                ($data->k2_n_dom ?? 0) +
                ($data->lain_n_dom ?? 0);

            $total_pelanggan_lalu += $baris_total;
        }

        $nilai_peneraan_wm_ini = $total_pelanggan_ini != 0 ? $water_meter_ini / $total_pelanggan_ini * 100 : 0;

        if ($nilai_peneraan_wm_ini == 0) {
            $hasil_perhitungan_nilai_peneraan_wm_ini = 0;
        } elseif ($nilai_peneraan_wm_ini > 20) {
            $hasil_perhitungan_nilai_peneraan_wm_ini = 3;
        } elseif ($nilai_peneraan_wm_ini > 10 && $nilai_peneraan_wm_ini <= 20) {
            $hasil_perhitungan_nilai_peneraan_wm_ini = 2;
        } elseif ($nilai_peneraan_wm_ini < 0 && $nilai_peneraan_wm_ini <= 10) {
            $hasil_perhitungan_nilai_peneraan_wm_ini = 1;
        }

        $nilai_peneraan_wm_lalu = $total_pelanggan_lalu != 0 ? $water_meter_lalu / $total_pelanggan_lalu * 100 : 0;

        if ($nilai_peneraan_wm_lalu == 0) {
            $hasil_perhitungan_nilai_peneraan_wm_lalu = 0;
        } elseif ($nilai_peneraan_wm_lalu > 20) {
            $hasil_perhitungan_nilai_peneraan_wm_lalu = 3;
        } elseif ($nilai_peneraan_wm_lalu > 10 && $nilai_peneraan_wm_lalu <= 20) {
            $hasil_perhitungan_nilai_peneraan_wm_lalu = 2;
        } elseif ($nilai_peneraan_wm_lalu < 0 && $nilai_peneraan_wm_lalu <= 10) {
            $hasil_perhitungan_nilai_peneraan_wm_lalu = 1;
        }

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'total_volume_produksi' => $total_volume_produksi_ini,
                'total_nrw' => $total_nrw_ini,
                'nilai_nrw' => $nilai_nrw_ini,
                'hasil_perhitungan_nilai_nrw' => $hasil_perhitungan_nilai_nrw_ini,
                'kap_riil' => $kap_riil_ini,
                'terpasang' => $terpasang_ini,
                'nilai_kapasitas' => $nilai_kapasitas_ini,
                'hasil_perhitungan_nilai_kapasitas' => $hasil_perhitungan_nilai_kapasitas_ini,
                'water_meter' => $water_meter_ini,
                'total_pelanggan' => $total_pelanggan_ini,
                'nilai_peneraan_wm' => $nilai_peneraan_wm_ini,
                'hasil_perhitungan_nilai_peneraan_wm' => $hasil_perhitungan_nilai_peneraan_wm_ini
            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'total_volume_produksi' => $total_volume_produksi_lalu,
                'total_nrw' => $total_nrw_lalu,
                'nilai_nrw' => $nilai_nrw_lalu,
                'hasil_perhitungan_nilai_nrw' => $hasil_perhitungan_nilai_nrw_lalu,
                'kap_riil' => $kap_riil_lalu,
                'terpasang' => $terpasang_lalu,
                'nilai_kapasitas' => $nilai_kapasitas_lalu,
                'hasil_perhitungan_nilai_kapasitas' => $hasil_perhitungan_nilai_kapasitas_lalu,
                'water_meter' => $water_meter_lalu,
                'total_pelanggan' => $total_pelanggan_lalu,
                'nilai_peneraan_wm' => $nilai_peneraan_wm_lalu,
                'hasil_perhitungan_nilai_peneraan_wm' => $hasil_perhitungan_nilai_peneraan_wm_lalu
            ]

        ];
    }

    public function hitung_pengaduan($tahun)
    {
        $pengaduan_ini = $this->Model_langgan->get_pengaduan($tahun);
        $total_aduan_ini = 0;
        $total_aduan_ya_ini = 0;
        foreach ($pengaduan_ini as $data) {
            $total_aduan_ini += $data->jumlah_aduan ?? 0;
            $total_aduan_ya_ini += $data->jumlah_aduan_ya ?? 0;
        }

        $pengaduan_lalu = $this->Model_langgan->get_pengaduan($tahun - 1);
        $total_aduan_lalu = 0;
        $total_aduan_ya_lalu = 0;
        foreach ($pengaduan_lalu as $data) {
            $total_aduan_lalu += $data->jumlah_aduan ?? 0;
            $total_aduan_ya_lalu += $data->jumlah_aduan_ya ?? 0;
        }

        $nilai_aduan_ini = $total_aduan_ini != 0 ? $total_aduan_ini / $total_aduan_ya_ini * 100 : 0;

        if ($nilai_aduan_ini == 0) {
            $hasil_perhitungan_nilai_aduan_ini = 0;
        } elseif ($nilai_aduan_ini >= 80) {
            $hasil_perhitungan_nilai_aduan_ini = 2;
        } elseif ($nilai_aduan_ini < 80) {
            $hasil_perhitungan_nilai_aduan_ini = 1;
        }

        $nilai_aduan_lalu = $total_aduan_lalu != 0 ? $total_aduan_lalu / $total_aduan_ya_lalu * 100 : 0;

        if ($nilai_aduan_lalu == 0) {
            $hasil_perhitungan_nilai_aduan_lalu = 0;
        } elseif ($nilai_aduan_lalu >= 80) {
            $hasil_perhitungan_nilai_aduan_lalu = 2;
        } elseif ($nilai_aduan_lalu < 80) {
            $hasil_perhitungan_nilai_aduan_lalu = 1;
        }


        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'total_aduan' => $total_aduan_ini,
                'total_aduan_ya' => $total_aduan_ya_ini,
                'nilai_aduan' => $nilai_aduan_ini,
                'hasil_perhitungan_nilai_aduan' => $hasil_perhitungan_nilai_aduan_ini,

            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'total_aduan' => $total_aduan_lalu,
                'total_aduan_ya' => $total_aduan_ya_lalu,
                'nilai_aduan' => $nilai_aduan_lalu,
                'hasil_perhitungan_nilai_aduan' => $hasil_perhitungan_nilai_aduan_lalu,
            ]

        ];
    }

    public function hitung_rasio_karyawan($tahun)
    {
        $data_pelanggan_ini = $this->Model_langgan->get_data_pelanggan($tahun);
        $total_pelanggan_aktif_ini = 0;
        foreach ($data_pelanggan_ini as $data) {
            $total_aktif =
                ($data->rt_dom ?? 0) +
                ($data->niaga_dom ?? 0) +
                ($data->sl_kom_dom ?? 0) +
                ($data->unit_kom_dom ?? 0) +
                ($data->sl_hu_dom ?? 0) +
                ($data->sosial_n_dom ?? 0) +
                ($data->niaga_n_dom ?? 0) +
                ($data->ind_n_dom ?? 0) +
                ($data->inst_n_dom ?? 0) +
                ($data->k2_n_dom ?? 0) +
                ($data->lain_n_dom ?? 0);

            $total_pelanggan_aktif_ini += $total_aktif;
        }

        $data_pelanggan_lalu = $this->Model_langgan->get_data_pelanggan($tahun - 1);
        $total_pelanggan_aktif_lalu = 0;
        foreach ($data_pelanggan_lalu as $data) {
            $total_aktif =
                ($data->rt_dom ?? 0) +
                ($data->niaga_dom ?? 0) +
                ($data->sl_kom_dom ?? 0) +
                ($data->unit_kom_dom ?? 0) +
                ($data->sl_hu_dom ?? 0) +
                ($data->sosial_n_dom ?? 0) +
                ($data->niaga_n_dom ?? 0) +
                ($data->ind_n_dom ?? 0) +
                ($data->inst_n_dom ?? 0) +
                ($data->k2_n_dom ?? 0) +
                ($data->lain_n_dom ?? 0);

            $total_pelanggan_aktif_lalu += $total_aktif;
        }

        $nilai_rasio_kyw_ini = $total_pelanggan_aktif_ini != 0 ? 163 / $total_pelanggan_aktif_ini * 1000 : 0;

        if ($nilai_rasio_kyw_ini == 0) {
            $hasil_perhitungan_nilai_rasio_kyw_ini = 0;
        } elseif ($nilai_rasio_kyw_ini <= 8) {
            $hasil_perhitungan_nilai_rasio_kyw_ini = 5;
        } elseif ($nilai_rasio_kyw_ini > 8 && $nilai_rasio_kyw_ini <= 11) {
            $hasil_perhitungan_nilai_rasio_kyw_ini = 4;
        } elseif ($nilai_rasio_kyw_ini > 11 && $nilai_rasio_kyw_ini <= 15) {
            $hasil_perhitungan_nilai_rasio_kyw_ini = 3;
        } elseif ($nilai_rasio_kyw_ini > 15 && $nilai_rasio_kyw_ini <= 18) {
            $hasil_perhitungan_nilai_rasio_kyw_ini = 2;
        } elseif ($nilai_rasio_kyw_ini > 18) {
            $hasil_perhitungan_nilai_rasio_kyw_ini = 1;
        }

        $nilai_rasio_kyw_lalu = $total_pelanggan_aktif_lalu != 0 ? 162 / $total_pelanggan_aktif_lalu * 1000 : 0;

        if ($nilai_rasio_kyw_lalu == 0) {
            $hasil_perhitungan_nilai_rasio_kyw_lalu = 0;
        } elseif ($nilai_rasio_kyw_lalu <= 8) {
            $hasil_perhitungan_nilai_rasio_kyw_lalu = 5;
        } elseif ($nilai_rasio_kyw_lalu > 8 && $nilai_rasio_kyw_lalu <= 11) {
            $hasil_perhitungan_nilai_rasio_kyw_lalu = 4;
        } elseif ($nilai_rasio_kyw_lalu > 11 && $nilai_rasio_kyw_lalu <= 15) {
            $hasil_perhitungan_nilai_rasio_kyw_lalu = 3;
        } elseif ($nilai_rasio_kyw_lalu > 15 && $nilai_rasio_kyw_lalu <= 18) {
            $hasil_perhitungan_nilai_rasio_kyw_lalu = 2;
        } elseif ($nilai_rasio_kyw_lalu > 18) {
            $hasil_perhitungan_nilai_rasio_kyw_lalu = 1;
        }

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'total_pelanggan_aktif' => $total_pelanggan_aktif_ini,
                'nilai_rasio_kyw' => $nilai_rasio_kyw_ini,
                'hasil_perhitungan_nilai_rasio_kyw' => $hasil_perhitungan_nilai_rasio_kyw_ini

            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'total_pelanggan_aktif' => $total_pelanggan_aktif_lalu,
                'nilai_rasio_kyw' => $nilai_rasio_kyw_lalu,
                'hasil_perhitungan_nilai_rasio_kyw' => $hasil_perhitungan_nilai_rasio_kyw_lalu
            ]

        ];
    }

    public function hitung_aspek_ops($tahun)
    {

        $hasil_perhitungan_rasio_cak_layanan_ini = $this->Model_evkin_dagri_ops->hitung_cak_layanan($tahun)['tahun_ini']['hasil_perhitungan_rasio_cak_layanan'];
        $hasil_bonus_perhitungan_rasio_cak_layanan_ini = $this->Model_evkin_dagri_ops->hitung_cak_layanan($tahun)['tahun_ini']['hasil_bonus_perhitungan_rasio_cak_layanan'];
        $hasil_kualitas_air_ini = $this->Model_evkin_dagri_ops->hitung_kualitas_air($tahun)['tahun_ini']['hasil_kualitas_air'];
        $hasil_kontinuitas_air_ini = $this->Model_evkin_dagri_ops->hitung_kontinuitas_air($tahun)['tahun_ini']['hasil_kontinuitas_air'];
        $hasil_perhitungan_nilai_kapasitas_ini = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_ini']['hasil_perhitungan_nilai_kapasitas'];
        $hasil_perhitungan_nilai_nrw_ini = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_ini']['hasil_perhitungan_nilai_nrw'];
        $hasil_perhitungan_nilai_peneraan_wm_ini = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_ini']['hasil_perhitungan_nilai_peneraan_wm'];
        $hasil_sr_baru_ini = $this->Model_evkin_dagri_ops->hitung_sr_baru($tahun)['tahun_ini']['hasil_sr_baru'];
        $hasil_perhitungan_nilai_aduan_ini = $this->Model_evkin_dagri_ops->hitung_pengaduan($tahun)['tahun_ini']['hasil_perhitungan_nilai_aduan'];
        $hasil_service_point_ini = $this->Model_evkin_dagri_ops->hitung_service_point($tahun)['tahun_ini']['hasil_service_point'];
        $hasil_perhitungan_nilai_rasio_kyw_ini = $this->Model_evkin_dagri_ops->hitung_rasio_karyawan($tahun)['tahun_ini']['hasil_perhitungan_nilai_rasio_kyw'];

        $total_aspek_ops_ini = $hasil_perhitungan_rasio_cak_layanan_ini + $hasil_bonus_perhitungan_rasio_cak_layanan_ini + $hasil_kualitas_air_ini + $hasil_kontinuitas_air_ini + $hasil_perhitungan_nilai_kapasitas_ini + $hasil_perhitungan_nilai_nrw_ini + $hasil_perhitungan_nilai_peneraan_wm_ini + $hasil_sr_baru_ini + $hasil_perhitungan_nilai_aduan_ini + $hasil_service_point_ini + $hasil_perhitungan_nilai_rasio_kyw_ini;

        $nilai_kinerja_aspek_ops_ini = $total_aspek_ops_ini * 40 / 47;

        $hasil_perhitungan_rasio_cak_layanan_lalu = $this->Model_evkin_dagri_ops->hitung_cak_layanan($tahun)['tahun_lalu']['hasil_perhitungan_rasio_cak_layanan'];
        $hasil_bonus_perhitungan_rasio_cak_layanan_lalu = $this->Model_evkin_dagri_ops->hitung_cak_layanan($tahun)['tahun_lalu']['hasil_bonus_perhitungan_rasio_cak_layanan'];
        $hasil_kualitas_air_lalu = $this->Model_evkin_dagri_ops->hitung_kualitas_air($tahun)['tahun_lalu']['hasil_kualitas_air'];
        $hasil_kontinuitas_air_lalu = $this->Model_evkin_dagri_ops->hitung_kontinuitas_air($tahun)['tahun_lalu']['hasil_kontinuitas_air'];
        $hasil_perhitungan_nilai_kapasitas_lalu = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_lalu']['hasil_perhitungan_nilai_kapasitas'];
        $hasil_perhitungan_nilai_nrw_lalu = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_lalu']['hasil_perhitungan_nilai_nrw'];
        $hasil_perhitungan_nilai_peneraan_wm_lalu = $this->Model_evkin_dagri_ops->hitung_nrw($tahun)['tahun_lalu']['hasil_perhitungan_nilai_peneraan_wm'];
        $hasil_sr_baru_lalu = $this->Model_evkin_dagri_ops->hitung_sr_baru($tahun)['tahun_lalu']['hasil_sr_baru'];
        $hasil_perhitungan_nilai_aduan_lalu = $this->Model_evkin_dagri_ops->hitung_pengaduan($tahun)['tahun_lalu']['hasil_perhitungan_nilai_aduan'];
        $hasil_service_point_lalu = $this->Model_evkin_dagri_ops->hitung_service_point($tahun)['tahun_lalu']['hasil_service_point'];
        $hasil_perhitungan_nilai_rasio_kyw_lalu = $this->Model_evkin_dagri_ops->hitung_rasio_karyawan($tahun)['tahun_lalu']['hasil_perhitungan_nilai_rasio_kyw'];

        $total_aspek_ops_lalu = $hasil_perhitungan_rasio_cak_layanan_lalu + $hasil_bonus_perhitungan_rasio_cak_layanan_lalu + $hasil_kualitas_air_lalu + $hasil_kontinuitas_air_lalu + $hasil_perhitungan_nilai_kapasitas_lalu + $hasil_perhitungan_nilai_nrw_lalu + $hasil_perhitungan_nilai_peneraan_wm_lalu + $hasil_sr_baru_lalu + $hasil_perhitungan_nilai_aduan_lalu + $hasil_service_point_lalu + $hasil_perhitungan_nilai_rasio_kyw_lalu;

        $nilai_kinerja_aspek_ops_lalu = $total_aspek_ops_lalu * 40 / 47;

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'total_aspek_ops' => $total_aspek_ops_ini,
                'nilai_kinerja_aspek_ops' => $nilai_kinerja_aspek_ops_ini
            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'total_aspek_ops' => $total_aspek_ops_lalu,
                'nilai_kinerja_aspek_ops' => $nilai_kinerja_aspek_ops_lalu
            ]
        ];
    }
}
