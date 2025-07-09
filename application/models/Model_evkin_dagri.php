<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_evkin_dagri extends CI_Model
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
    }

    // aspek keuangan

    public function hitung_laba_rugi($tahun)
    {
        $hasil_tahun_ini = $this->_proses_laba_rugi($tahun);
        // Hitung tahun sebelumnya
        $tahun_sebelumnya = $tahun - 1;
        $hasil_tahun_lalu = $this->_proses_laba_rugi($tahun_sebelumnya);
        // Kembalikan keduanya
        $hasil_2tahun_lalu = $this->_proses_laba_rugi($tahun_sebelumnya - 1);

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'laba_rugi_bersih' => $hasil_tahun_ini['laba_rugi_bersih'],
                'labarugi_bersih_sebelum_pajak' => $hasil_tahun_ini['labarugi_bersih_sebelum_pajak'],
                'total_pendapatan_usaha_audited' => $hasil_tahun_ini['total_pendapatan_usaha_audited'],
                'total_beban_operasi' => $hasil_tahun_ini['total_beban_operasi'],
            ],
            'tahun_lalu' => [
                'tahun' => $tahun_sebelumnya,
                'laba_rugi_bersih' => $hasil_tahun_lalu['laba_rugi_bersih'],
                'labarugi_bersih_sebelum_pajak' => $hasil_tahun_lalu['labarugi_bersih_sebelum_pajak'],
                'total_pendapatan_usaha_audited' => $hasil_tahun_lalu['total_pendapatan_usaha_audited'],
                'total_beban_operasi' => $hasil_tahun_lalu['total_beban_operasi'],
            ],
            '2tahun_lalu' => [
                'tahun' => $tahun_sebelumnya - 1,
                'laba_rugi_bersih' => $hasil_2tahun_lalu['laba_rugi_bersih'],
                'labarugi_bersih_sebelum_pajak' => $hasil_2tahun_lalu['labarugi_bersih_sebelum_pajak'],
                'total_pendapatan_usaha_audited' => $hasil_2tahun_lalu['total_pendapatan_usaha_audited'],
                'total_beban_operasi' => $hasil_2tahun_lalu['total_beban_operasi'],
            ]
        ];
    }

    public function hitung_lr_sbl_susut($tahun)
    {
        $hasil_tahun_ini = $this->Model_penyusutan->get_all_non_amdk($tahun);
        $tahun_sebelumnya = $tahun - 1;
        $hasil_tahun_lalu = $this->Model_penyusutan->get_all_non_amdk($tahun - 1);

        $penyusutan_data_ini = $this->Model_amortisasi->get_amortisasi($tahun);
        $penyusutan_data_lalu = $this->Model_amortisasi->get_amortisasi($tahun - 1);
        $total_amortisasi_ini = $penyusutan_data_ini['total_amortisasi'];
        $total_amortisasi_lalu = $penyusutan_data_lalu['total_amortisasi'];

        $hasil = $this->hitung_laba_rugi($tahun);
        $lr_sbl_pajak_ini = $hasil['tahun_ini']['labarugi_bersih_sebelum_pajak'];
        $lr_sbl_pajak_lalu = $hasil['tahun_lalu']['labarugi_bersih_sebelum_pajak'];

        $total_laba_sebelum_susut_ini = $lr_sbl_pajak_ini + $total_amortisasi_ini['total_penyusutan'] + $hasil_tahun_ini['totals_non_amdk']['total_penyusutan'];
        $total_laba_sebelum_susut_lalu = $lr_sbl_pajak_lalu + $total_amortisasi_lalu['total_penyusutan'] + $hasil_tahun_lalu['totals_non_amdk']['total_penyusutan'];
        $angsuran_pokok_bunga_ini = 0;
        $angsuran_pokok_bunga_lalu = 0;


        $rasio_laba_sbl_susut_ini = ($angsuran_pokok_bunga_ini != 0) ? ($total_laba_sebelum_susut_ini / $angsuran_pokok_bunga_ini) : 0;
        $rasio_laba_sbl_susut_lalu = ($angsuran_pokok_bunga_lalu != 0) ? ($total_laba_sebelum_susut_lalu / $angsuran_pokok_bunga_lalu) : 0;

        $hasil_Perhitungan_rasio_laba_sbl_susut_ini = 0;
        $hasil_Perhitungan_rasio_laba_sbl_susut_lalu = 0;

        if ($angsuran_pokok_bunga_ini == 0) {
            $hasil_Perhitungan_rasio_laba_sbl_susut_ini = 5;
        } else {
            $rasio_laba_sbl_susut_ini = ($angsuran_pokok_bunga_ini != 0) ? ($total_laba_sebelum_susut_ini / $angsuran_pokok_bunga_ini) : 0;
            if ($rasio_laba_sbl_susut_ini <= 1.0) {
                $hasil_Perhitungan_rasio_laba_sbl_susut_ini = 1;
            } elseif ($rasio_laba_sbl_susut_ini > 1.0 && $rasio_laba_sbl_susut_ini <= 1.3) {
                $hasil_Perhitungan_rasio_laba_sbl_susut_ini = 2;
            } elseif ($rasio_laba_sbl_susut_ini > 1.3 && $rasio_laba_sbl_susut_ini <= 1.7) {
                $hasil_Perhitungan_rasio_laba_sbl_susut_ini = 3;
            } elseif ($rasio_laba_sbl_susut_ini > 1.7 && $rasio_laba_sbl_susut_ini <= 2.0) {
                $hasil_Perhitungan_rasio_laba_sbl_susut_ini = 4;
            } elseif ($rasio_laba_sbl_susut_ini > 2) {
                $hasil_Perhitungan_rasio_laba_sbl_susut_ini = 5;
            }
        }

        if ($angsuran_pokok_bunga_lalu == 0) {
            $hasil_Perhitungan_rasio_laba_sbl_susut_lalu = 5;
        } else {
            $rasio_laba_sbl_susut_lalu = ($angsuran_pokok_bunga_lalu != 0) ? ($total_laba_sebelum_susut_lalu / $angsuran_pokok_bunga_lalu) : 0;
            if ($rasio_laba_sbl_susut_lalu <= 1.0) {
                $hasil_Perhitungan_rasio_laba_sbl_susut_lalu = 1;
            } elseif ($rasio_laba_sbl_susut_lalu > 1.0 && $rasio_laba_sbl_susut_lalu <= 1.3) {
                $hasil_Perhitungan_rasio_laba_sbl_susut_lalu = 2;
            } elseif ($rasio_laba_sbl_susut_lalu > 1.3 && $rasio_laba_sbl_susut_lalu <= 1.7) {
                $hasil_Perhitungan_rasio_laba_sbl_susut_lalu = 3;
            } elseif ($rasio_laba_sbl_susut_lalu > 1.7 && $rasio_laba_sbl_susut_lalu <= 2.0) {
                $hasil_Perhitungan_rasio_laba_sbl_susut_lalu = 4;
            } elseif ($rasio_laba_sbl_susut_lalu > 2) {
                $hasil_Perhitungan_rasio_laba_sbl_susut_lalu = 5;
            }
        }

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'lr_sbl_pajak' => $lr_sbl_pajak_ini,
                'total_asset' => $hasil_tahun_ini['totals_non_amdk']['total_penyusutan'],
                'total_amortisasi' => $total_amortisasi_ini['total_penyusutan'],
                'total_laba_sebelum_susut' => $total_laba_sebelum_susut_ini,
                'rasio_laba_sbl_susut' => $rasio_laba_sbl_susut_ini,
                'hasil_perhitungan_rasio_laba_sbl_susut' => $hasil_Perhitungan_rasio_laba_sbl_susut_ini
            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'lr_sbl_pajak' => $lr_sbl_pajak_lalu,
                'total_asset' => $hasil_tahun_lalu['totals_non_amdk']['total_penyusutan'],
                'total_amortisasi' => $total_amortisasi_lalu['total_penyusutan'],
                'total_laba_sebelum_susut' => $total_laba_sebelum_susut_lalu,
                'rasio_laba_sbl_susut' => $rasio_laba_sbl_susut_lalu,
                'hasil_perhitungan_rasio_laba_sbl_susut' => $hasil_Perhitungan_rasio_laba_sbl_susut_lalu
            ]
        ];
    }

    public function hitung_neraca($tahun)
    {
        $hasil_tahun_ini = $this->_proses_neraca($tahun);
        $tahun_sebelumnya = $tahun - 1;
        $hasil_tahun_lalu = $this->_proses_neraca($tahun_sebelumnya);
        $hasil_2tahun_lalu = $this->_proses_neraca($tahun_sebelumnya - 1);

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'total_aset_lancar_audited' => $hasil_tahun_ini['total_aset_lancar_audited'],
                'total_liabilitas_jangka_pendek_audited' => $hasil_tahun_ini['total_liabilitas_jangka_pendek_audited'],
                'total_ekuitas_audited' => $hasil_tahun_ini['total_ekuitas_audited'],
                'total_asset' => $hasil_tahun_ini['total_asset'],
                'total_hutang' => $hasil_tahun_ini['total_hutang'],
            ],
            'tahun_lalu' => [
                'tahun' => $tahun_sebelumnya,
                'total_aset_lancar_audited' => $hasil_tahun_lalu['total_aset_lancar_audited'],
                'total_liabilitas_jangka_pendek_audited' => $hasil_tahun_lalu['total_liabilitas_jangka_pendek_audited'],
                'total_ekuitas_audited' => $hasil_tahun_lalu['total_ekuitas_audited'],
                'total_asset' => $hasil_tahun_lalu['total_asset'],
                'total_hutang' => $hasil_tahun_lalu['total_hutang'],
            ],
            '2tahun_lalu' => [
                'tahun' => $tahun_sebelumnya - 1,
                'total_aset_lancar_audited' => $hasil_2tahun_lalu['total_aset_lancar_audited'],
                'total_liabilitas_jangka_pendek_audited' => $hasil_2tahun_lalu['total_liabilitas_jangka_pendek_audited'],
                'total_ekuitas_audited' => $hasil_2tahun_lalu['total_ekuitas_audited'],
                'total_asset' => $hasil_2tahun_lalu['total_asset'],
                'total_hutang' => $hasil_2tahun_lalu['total_hutang'],
            ]
        ];
    }

    public function hitung_aktiva_tidak_lancar($tahun)
    {
        $hasil_tahun_ini = $this->_aktiva_tidak_lancar($tahun);
        $tahun_sebelumnya = $tahun - 1;
        $hasil_tahun_lalu = $this->_aktiva_tidak_lancar($tahun_sebelumnya);
        $hasil_2tahun_lalu = $this->_aktiva_tidak_lancar($tahun_sebelumnya - 1);

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'aktiva_tidak_lancar' => $hasil_tahun_ini,
            ],
            'tahun_lalu' => [
                'tahun' => $tahun_sebelumnya,
                'aktiva_tidak_lancar' => $hasil_tahun_lalu,
            ],
            '2tahun_lalu' => [
                'tahun' => $tahun_sebelumnya - 1,
                'aktiva_tidak_lancar' => $hasil_2tahun_lalu,
            ]
        ];
    }
    public function hitung_aktiva_produktif($tahun)
    {
        $aktiva_lancar = $this->hitung_neraca($tahun);
        $aktiva_tidak_lancar = $this->hitung_aktiva_tidak_lancar($tahun);

        $aktiva_produktif_ini = $aktiva_lancar['tahun_ini']['total_aset_lancar_audited'] + $aktiva_tidak_lancar['tahun_ini']['aktiva_tidak_lancar'];
        $aktiva_produktif_lalu = $aktiva_lancar['tahun_lalu']['total_aset_lancar_audited'] + $aktiva_tidak_lancar['tahun_lalu']['aktiva_tidak_lancar'];
        $aktiva_produktif_2_lalu = $aktiva_lancar['2tahun_lalu']['total_aset_lancar_audited'] + $aktiva_tidak_lancar['2tahun_lalu']['aktiva_tidak_lancar'];

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'aktiva_produktif' => $aktiva_produktif_ini,
            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'aktiva_produktif' => $aktiva_produktif_lalu,
            ],
            '2tahun_lalu' => [
                'tahun' => $tahun - 2,
                'aktiva_produktif' => $aktiva_produktif_2_lalu,
            ]
        ];
    }

    public function hitung_rasio_laba($tahun)
    {
        $labarugi_bersih_sebelum_pajak_ini = $this->hitung_laba_rugi($tahun)['tahun_ini']['labarugi_bersih_sebelum_pajak'];
        $labarugi_bersih_sebelum_pajak_lalu = $this->hitung_laba_rugi($tahun)['tahun_lalu']['labarugi_bersih_sebelum_pajak'];
        $labarugi_bersih_sebelum_pajak_2_lalu = $this->hitung_laba_rugi($tahun)['2tahun_lalu']['labarugi_bersih_sebelum_pajak'];

        $aktiva_produktif_ini = $this->hitung_aktiva_produktif($tahun)['tahun_ini']['aktiva_produktif'];
        $aktiva_produktif_lalu = $this->hitung_aktiva_produktif($tahun)['tahun_lalu']['aktiva_produktif'];
        $aktiva_produktif_2_lalu = $this->hitung_aktiva_produktif($tahun)['2tahun_lalu']['aktiva_produktif'];

        $rasio_laba_ini = ($aktiva_produktif_ini != 0)
            ? ($labarugi_bersih_sebelum_pajak_ini / $aktiva_produktif_ini * 100)
            : 0;

        $rasio_laba_lalu = ($aktiva_produktif_lalu != 0)
            ? ($labarugi_bersih_sebelum_pajak_lalu / $aktiva_produktif_lalu * 100)
            : 0;

        $rasio_laba_2_lalu = ($aktiva_produktif_2_lalu != 0)
            ? ($labarugi_bersih_sebelum_pajak_2_lalu / $aktiva_produktif_2_lalu * 100)
            : 0;

        $hasil_perhitungan_rasio_laba_ini = 0;
        $hasil_perhitungan_rasio_laba_lalu = 0;
        $hasil_perhitungan_rasio_laba_2_lalu = 0;

        if ($rasio_laba_ini < 0) {
            $hasil_perhitungan_rasio_laba_ini = 1;
        } elseif ($rasio_laba_ini > 0 && $rasio_laba_ini <= 3) {
            $hasil_perhitungan_rasio_laba_ini = 2;
        } elseif ($rasio_laba_ini > 3 && $rasio_laba_ini <= 7) {
            $hasil_perhitungan_rasio_laba_ini = 3;
        } elseif ($rasio_laba_ini > 7 && $rasio_laba_ini <= 10) {
            $hasil_perhitungan_rasio_laba_ini = 4;
        } elseif ($rasio_laba_ini > 10) {
            $hasil_perhitungan_rasio_laba_ini = 5;
        }

        if ($rasio_laba_lalu < 0) {
            $hasil_perhitungan_rasio_laba_lalu = 1;
        } elseif ($rasio_laba_lalu > 0 && $rasio_laba_lalu <= 3) {
            $hasil_perhitungan_rasio_laba_lalu = 2;
        } elseif ($rasio_laba_lalu > 3 && $rasio_laba_lalu <= 7) {
            $hasil_perhitungan_rasio_laba_lalu = 3;
        } elseif ($rasio_laba_lalu > 7 && $rasio_laba_lalu <= 10) {
            $hasil_perhitungan_rasio_laba_lalu = 4;
        } elseif ($rasio_laba_lalu > 10) {
            $hasil_perhitungan_rasio_laba_lalu = 5;
        }

        if ($rasio_laba_2_lalu < 0) {
            $hasil_perhitungan_rasio_laba_2_lalu = 1;
        } elseif ($rasio_laba_2_lalu > 0 && $rasio_laba_2_lalu <= 3) {
            $hasil_perhitungan_rasio_laba_2_lalu = 2;
        } elseif ($rasio_laba_2_lalu > 3 && $rasio_laba_2_lalu <= 7) {
            $hasil_perhitungan_rasio_laba_2_lalu = 3;
        } elseif ($rasio_laba_2_lalu > 7 && $rasio_laba_2_lalu <= 10) {
            $hasil_perhitungan_rasio_laba_2_lalu = 4;
        } elseif ($rasio_laba_2_lalu > 10) {
            $hasil_perhitungan_rasio_laba_2_lalu = 5;
        }

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'rasio_laba_ini' => $rasio_laba_ini,
                'hasil_perhitungan_rasio_laba_ini' => $hasil_perhitungan_rasio_laba_ini
            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'rasio_laba_lalu' => $rasio_laba_lalu,
                'hasil_perhitungan_rasio_laba_lalu' => $hasil_perhitungan_rasio_laba_lalu
            ],
            'tahun_2_lalu' => [
                'tahun' => $tahun - 2,
                'rasio_laba_2_lalu' => $rasio_laba_2_lalu,
                'hasil_perhitungan_rasio_laba_2_lalu' => $hasil_perhitungan_rasio_laba_2_lalu
            ]
        ];
    }

    public function hitung_bonus_rasio_laba($tahun)
    {
        $rasio_laba_ini = $this->hitung_rasio_laba($tahun)['tahun_ini']['rasio_laba_ini'];
        $rasio_laba_lalu = $this->hitung_rasio_laba($tahun)['tahun_lalu']['rasio_laba_lalu'];
        $rasio_laba_2_lalu = $this->hitung_rasio_laba($tahun)['tahun_2_lalu']['rasio_laba_2_lalu'];

        $bonus_rasio_laba_ini = $rasio_laba_ini - $rasio_laba_lalu;
        $bonus_rasio_laba_lalu = $rasio_laba_lalu - $rasio_laba_2_lalu;

        $hasil_perhitungan_bonus_rasio_laba_ini = 0;
        if ($bonus_rasio_laba_ini > 12) {
            $hasil_perhitungan_bonus_rasio_laba_ini = 5;
        } elseif ($bonus_rasio_laba_ini > 9 && $bonus_rasio_laba_ini <= 12) {
            $hasil_perhitungan_bonus_rasio_laba_ini = 4;
        } elseif ($bonus_rasio_laba_ini > 6 && $bonus_rasio_laba_ini <= 9) {
            $hasil_perhitungan_bonus_rasio_laba_ini = 3;
        } elseif ($bonus_rasio_laba_ini > 3 && $bonus_rasio_laba_ini <= 6) {
            $hasil_perhitungan_bonus_rasio_laba_ini = 2;
        } elseif ($bonus_rasio_laba_ini > 0 && $bonus_rasio_laba_ini <= 3) {
            $hasil_perhitungan_bonus_rasio_laba_ini = 1;
        }
        $hasil_perhitungan_bonus_rasio_laba_lalu = 0;
        if ($bonus_rasio_laba_lalu > 12) {
            $hasil_perhitungan_bonus_rasio_laba_lalu = 5;
        } elseif ($bonus_rasio_laba_lalu > 9 && $bonus_rasio_laba_lalu <= 12) {
            $hasil_perhitungan_bonus_rasio_laba_lalu = 4;
        } elseif ($bonus_rasio_laba_lalu > 6 && $bonus_rasio_laba_lalu <= 9) {
            $hasil_perhitungan_bonus_rasio_laba_lalu = 3;
        } elseif ($bonus_rasio_laba_lalu > 3 && $bonus_rasio_laba_lalu <= 6) {
            $hasil_perhitungan_bonus_rasio_laba_lalu = 2;
        } elseif ($bonus_rasio_laba_lalu > 0 && $bonus_rasio_laba_lalu <= 3) {
            $hasil_perhitungan_bonus_rasio_laba_lalu = 1;
        }

        return [
            'tahun_ini' => [
                'bonus_rasio_laba_ini' => $bonus_rasio_laba_ini,
                'hasil_perhitungan_bonus_rasio_laba_ini' => $hasil_perhitungan_bonus_rasio_laba_ini
            ],
            'tahun_lalu' => [
                'bonus_rasio_laba_lalu' => $bonus_rasio_laba_lalu,
                'hasil_perhitungan_bonus_rasio_laba_lalu' => $hasil_perhitungan_bonus_rasio_laba_lalu
            ]
        ];
    }

    public function hitung_rasio_laba_jual($tahun)
    {
        $labarugi_bersih_sebelum_pajak_ini = $this->hitung_laba_rugi($tahun)['tahun_ini']['labarugi_bersih_sebelum_pajak'];
        $labarugi_bersih_sebelum_pajak_lalu = $this->hitung_laba_rugi($tahun)['tahun_lalu']['labarugi_bersih_sebelum_pajak'];
        $labarugi_bersih_sebelum_pajak_2_lalu = $this->hitung_laba_rugi($tahun)['2tahun_lalu']['labarugi_bersih_sebelum_pajak'];
        $total_pendapatan_usaha_audited_ini = $this->hitung_laba_rugi($tahun)['tahun_ini']['total_pendapatan_usaha_audited'];
        $total_pendapatan_usaha_audited_lalu = $this->hitung_laba_rugi($tahun)['tahun_lalu']['total_pendapatan_usaha_audited'];
        $total_pendapatan_usaha_audited_2_lalu = $this->hitung_laba_rugi($tahun)['2tahun_lalu']['total_pendapatan_usaha_audited'];

        $rasio_laba_jual_ini = ($total_pendapatan_usaha_audited_ini != 0)
            ? ($labarugi_bersih_sebelum_pajak_ini / $total_pendapatan_usaha_audited_ini * 100)
            : 0;

        $rasio_laba_jual_lalu = ($total_pendapatan_usaha_audited_lalu != 0)
            ? ($labarugi_bersih_sebelum_pajak_lalu / $total_pendapatan_usaha_audited_lalu * 100)
            : 0;

        $rasio_laba_jual_2_lalu = ($total_pendapatan_usaha_audited_2_lalu != 0)
            ? ($labarugi_bersih_sebelum_pajak_2_lalu / $total_pendapatan_usaha_audited_2_lalu * 100)
            : 0;

        $hasil_perhitungan_rasio_laba_jual_ini = 0;
        $hasil_perhitungan_rasio_laba_jual_lalu = 0;
        $hasil_perhitungan_rasio_laba_jual_2_lalu = 0;

        if ($rasio_laba_jual_ini == 0) {
            $hasil_perhitungan_rasio_laba_jual_ini = 0;
        } elseif ($rasio_laba_jual_ini < 0) {
            $hasil_perhitungan_rasio_laba_jual_ini = 1;
        } elseif ($rasio_laba_jual_ini > 0 && $rasio_laba_jual_ini <= 6) {
            $hasil_perhitungan_rasio_laba_jual_ini = 2;
        } elseif ($rasio_laba_jual_ini > 6 && $rasio_laba_jual_ini <= 14) {
            $hasil_perhitungan_rasio_laba_jual_ini = 3;
        } elseif ($rasio_laba_jual_ini > 14 && $rasio_laba_jual_ini <= 20) {
            $hasil_perhitungan_rasio_laba_jual_ini = 4;
        } elseif ($rasio_laba_jual_ini > 20) {
            $hasil_perhitungan_rasio_laba_jual_ini = 5;
        }

        if ($rasio_laba_jual_lalu == 0) {
            $hasil_perhitungan_rasio_laba_jual_lalu = 0;
        } elseif ($rasio_laba_jual_lalu <= 0) {
            $hasil_perhitungan_rasio_laba_jual_lalu = 1;
        } elseif ($rasio_laba_jual_lalu > 0 && $rasio_laba_jual_lalu <= 6) {
            $hasil_perhitungan_rasio_laba_jual_lalu = 2;
        } elseif ($rasio_laba_jual_lalu > 6 && $rasio_laba_jual_lalu <= 14) {
            $hasil_perhitungan_rasio_laba_jual_lalu = 3;
        } elseif ($rasio_laba_jual_lalu > 14 && $rasio_laba_jual_lalu <= 20) {
            $hasil_perhitungan_rasio_laba_jual_lalu = 4;
        } elseif ($rasio_laba_jual_lalu > 20) {
            $hasil_perhitungan_rasio_laba_jual_lalu = 5;
        }

        if ($rasio_laba_jual_2_lalu <= 0) {
            $hasil_perhitungan_rasio_laba_jual_2_lalu = 1;
        } elseif ($rasio_laba_jual_2_lalu > 0 && $rasio_laba_jual_2_lalu <= 6) {
            $hasil_perhitungan_rasio_laba_jual_2_lalu = 2;
        } elseif ($rasio_laba_jual_2_lalu > 6 && $rasio_laba_jual_2_lalu <= 14) {
            $hasil_perhitungan_rasio_laba_jual_2_lalu = 3;
        } elseif ($rasio_laba_jual_2_lalu > 14 && $rasio_laba_jual_2_lalu <= 20) {
            $hasil_perhitungan_rasio_laba_jual_2_lalu = 4;
        } elseif ($rasio_laba_jual_2_lalu > 20) {
            $hasil_perhitungan_rasio_laba_jual_2_lalu = 5;
        }

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'rasio_laba_jual_ini' => $rasio_laba_jual_ini,
                'hasil_perhitungan_rasio_laba_jual_ini' => $hasil_perhitungan_rasio_laba_jual_ini
            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'rasio_laba_jual_lalu' => $rasio_laba_jual_lalu,
                'hasil_perhitungan_rasio_laba_jual_lalu' => $hasil_perhitungan_rasio_laba_jual_lalu
            ],
            'tahun_2_lalu' => [
                'tahun' => $tahun - 2,
                'rasio_laba_jual_2_lalu' => $rasio_laba_jual_2_lalu,
                'hasil_perhitungan_rasio_laba_jual_2_lalu' => $hasil_perhitungan_rasio_laba_jual_2_lalu
            ]
        ];
    }

    public function hitung_bonus_rasio_laba_jual($tahun)
    {
        $rasio_laba_jual_ini = $this->hitung_rasio_laba_jual($tahun)['tahun_ini']['rasio_laba_jual_ini'];
        $rasio_laba_jual_lalu = $this->hitung_rasio_laba_jual($tahun)['tahun_lalu']['rasio_laba_jual_lalu'];
        $rasio_laba_jual_2_lalu = $this->hitung_rasio_laba_jual($tahun)['tahun_2_lalu']['rasio_laba_jual_2_lalu'];

        $bonus_rasio_laba_jual_ini = $rasio_laba_jual_ini - $rasio_laba_jual_lalu;
        $bonus_rasio_laba_jual_lalu = $rasio_laba_jual_lalu - $rasio_laba_jual_2_lalu;

        $hasil_perhitungan_bonus_rasio_laba_jual_ini = 0;
        if ($bonus_rasio_laba_jual_ini > 12) {
            $hasil_perhitungan_bonus_rasio_laba_jual_ini = 5;
        } elseif ($bonus_rasio_laba_jual_ini > 9 && $bonus_rasio_laba_jual_ini <= 12) {
            $hasil_perhitungan_bonus_rasio_laba_jual_ini = 4;
        } elseif ($bonus_rasio_laba_jual_ini > 6 && $bonus_rasio_laba_jual_ini <= 9) {
            $hasil_perhitungan_bonus_rasio_laba_jual_ini = 3;
        } elseif ($bonus_rasio_laba_jual_ini > 3 && $bonus_rasio_laba_jual_ini <= 6) {
            $hasil_perhitungan_bonus_rasio_laba_jual_ini = 2;
        } elseif ($bonus_rasio_laba_jual_ini > 0 && $bonus_rasio_laba_jual_ini <= 3) {
            $hasil_perhitungan_bonus_rasio_laba_jual_ini = 1;
        }

        $hasil_perhitungan_bonus_rasio_laba_jual_lalu = 0;
        if ($bonus_rasio_laba_jual_lalu > 12) {
            $hasil_perhitungan_bonus_rasio_laba_jual_lalu = 5;
        } elseif ($bonus_rasio_laba_jual_lalu > 9 && $bonus_rasio_laba_jual_lalu <= 12) {
            $hasil_perhitungan_bonus_rasio_laba_jual_lalu = 4;
        } elseif ($bonus_rasio_laba_jual_lalu > 6 && $bonus_rasio_laba_jual_lalu <= 9) {
            $hasil_perhitungan_bonus_rasio_laba_jual_lalu = 3;
        } elseif ($bonus_rasio_laba_jual_lalu > 3 && $bonus_rasio_laba_jual_lalu <= 6) {
            $hasil_perhitungan_bonus_rasio_laba_jual_lalu = 2;
        } elseif ($bonus_rasio_laba_jual_lalu > 0 && $bonus_rasio_laba_jual_lalu <= 3) {
            $hasil_perhitungan_bonus_rasio_laba_jual_lalu = 1;
        }

        return [
            'tahun_ini' => [
                'bonus_rasio_laba_jual_ini' => $bonus_rasio_laba_jual_ini,
                'hasil_perhitungan_bonus_rasio_laba_jual_ini' => $hasil_perhitungan_bonus_rasio_laba_jual_ini
            ],
            'tahun_lalu' => [
                'bonus_rasio_laba_jual_lalu' => $bonus_rasio_laba_jual_lalu,
                'hasil_perhitungan_bonus_rasio_laba_jual_lalu' => $hasil_perhitungan_bonus_rasio_laba_jual_lalu
            ]
        ];
    }

    public function hitung_rasio_aktiva_lancar($tahun)
    {
        $total_aset_lancar_audited_ini = $this->hitung_neraca($tahun)['tahun_ini']['total_aset_lancar_audited'];
        $total_aset_lancar_audited_lalu = $this->hitung_neraca($tahun)['tahun_lalu']['total_aset_lancar_audited'];
        $total_aset_lancar_audited_2_lalu = $this->hitung_neraca($tahun)['2tahun_lalu']['total_aset_lancar_audited'];
        $total_liabilitas_jangka_pendek_audited_ini = $this->hitung_neraca($tahun)['tahun_ini']['total_liabilitas_jangka_pendek_audited'];
        $total_liabilitas_jangka_pendek_audited_lalu = $this->hitung_neraca($tahun)['tahun_lalu']['total_liabilitas_jangka_pendek_audited'];
        $total_liabilitas_jangka_pendek_audited_2_lalu = $this->hitung_neraca($tahun)['2tahun_lalu']['total_liabilitas_jangka_pendek_audited'];

        $rasio_aktiva_lancar_ini = ($total_aset_lancar_audited_ini != 0)
            ? ($total_aset_lancar_audited_ini / $total_liabilitas_jangka_pendek_audited_ini)
            : 0;

        $rasio_aktiva_lancar_lalu = ($total_aset_lancar_audited_lalu != 0)
            ? ($total_aset_lancar_audited_lalu / $total_liabilitas_jangka_pendek_audited_lalu)
            : 0;

        $rasio_aktiva_lancar_2_lalu = ($total_aset_lancar_audited_2_lalu != 0)
            ? ($total_aset_lancar_audited_2_lalu / $total_liabilitas_jangka_pendek_audited_2_lalu)
            : 0;

        $hasil_perhitungan_rasio_aktiva_lancar_ini = 0;
        $hasil_perhitungan_rasio_aktiva_lancar_lalu = 0;
        $hasil_perhitungan_rasio_aktiva_lancar_2_lalu = 0;

        if ($rasio_aktiva_lancar_ini == 0) {
            $hasil_perhitungan_rasio_aktiva_lancar_ini = 0;
        } elseif ($rasio_aktiva_lancar_ini <= 1.00 || $rasio_aktiva_lancar_ini > 3.00) {
            $hasil_perhitungan_rasio_aktiva_lancar_ini = 1;
        } elseif (($rasio_aktiva_lancar_ini > 1.00 && $rasio_aktiva_lancar_ini <= 1.25) ||
            ($rasio_aktiva_lancar_ini > 2.70 && $rasio_aktiva_lancar_ini <= 3.00)
        ) {
            $hasil_perhitungan_rasio_aktiva_lancar_ini = 2;
        } elseif (($rasio_aktiva_lancar_ini > 1.25 && $rasio_aktiva_lancar_ini <= 1.50) ||
            ($rasio_aktiva_lancar_ini > 2.30 && $rasio_aktiva_lancar_ini <= 2.70)
        ) {
            $hasil_perhitungan_rasio_aktiva_lancar_ini = 3;
        } elseif (($rasio_aktiva_lancar_ini > 1.50 && $rasio_aktiva_lancar_ini <= 1.75) ||
            ($rasio_aktiva_lancar_ini > 2.00 && $rasio_aktiva_lancar_ini <= 2.30)
        ) {
            $hasil_perhitungan_rasio_aktiva_lancar_ini = 4;
        } elseif ($rasio_aktiva_lancar_ini > 1.75 && $rasio_aktiva_lancar_ini <= 2.00) {
            $hasil_perhitungan_rasio_aktiva_lancar_ini = 5;
        }

        if ($rasio_aktiva_lancar_lalu == 0) {
            $hasil_perhitungan_rasio_aktiva_lancar_lalu = 0;
        } elseif ($rasio_aktiva_lancar_lalu <= 1.00 || $rasio_aktiva_lancar_lalu > 3.00) {
            $hasil_perhitungan_rasio_aktiva_lancar_lalu = 1;
        } elseif (($rasio_aktiva_lancar_lalu > 1.00 && $rasio_aktiva_lancar_lalu <= 1.25) ||
            ($rasio_aktiva_lancar_lalu > 2.70 && $rasio_aktiva_lancar_lalu <= 3.00)
        ) {
            $hasil_perhitungan_rasio_aktiva_lancar_lalu = 2;
        } elseif (($rasio_aktiva_lancar_lalu > 1.25 && $rasio_aktiva_lancar_lalu <= 1.50) ||
            ($rasio_aktiva_lancar_lalu > 2.30 && $rasio_aktiva_lancar_lalu <= 2.70)
        ) {
            $hasil_perhitungan_rasio_aktiva_lancar_lalu = 3;
        } elseif (($rasio_aktiva_lancar_lalu > 1.50 && $rasio_aktiva_lancar_lalu <= 1.75) ||
            ($rasio_aktiva_lancar_lalu > 2.00 && $rasio_aktiva_lancar_lalu <= 2.30)
        ) {
            $hasil_perhitungan_rasio_aktiva_lancar_lalu = 4;
        } elseif ($rasio_aktiva_lancar_lalu > 1.75 && $rasio_aktiva_lancar_lalu <= 2.00) {
            $hasil_perhitungan_rasio_aktiva_lancar_lalu = 5;
        }

        if ($rasio_aktiva_lancar_2_lalu == 0) {
            $hasil_perhitungan_rasio_aktiva_lancar_2_lalu = 0;
        } elseif ($rasio_aktiva_lancar_2_lalu <= 1.00 || $rasio_aktiva_lancar_2_lalu > 3.00) {
            $hasil_perhitungan_rasio_aktiva_lancar_2_lalu = 1;
        } elseif (($rasio_aktiva_lancar_2_lalu > 1.00 && $rasio_aktiva_lancar_2_lalu <= 1.25) ||
            ($rasio_aktiva_lancar_2_lalu > 2.70 && $rasio_aktiva_lancar_2_lalu <= 3.00)
        ) {
            $hasil_perhitungan_rasio_aktiva_lancar_2_lalu = 2;
        } elseif (($rasio_aktiva_lancar_2_lalu > 1.25 && $rasio_aktiva_lancar_2_lalu <= 1.50) ||
            ($rasio_aktiva_lancar_2_lalu > 2.30 && $rasio_aktiva_lancar_2_lalu <= 2.70)
        ) {
            $hasil_perhitungan_rasio_aktiva_lancar_2_lalu = 3;
        } elseif (($rasio_aktiva_lancar_2_lalu > 1.50 && $rasio_aktiva_lancar_2_lalu <= 1.75) ||
            ($rasio_aktiva_lancar_2_lalu > 2.00 && $rasio_aktiva_lancar_2_lalu <= 2.30)
        ) {
            $hasil_perhitungan_rasio_aktiva_lancar_2_lalu = 4;
        } elseif ($rasio_aktiva_lancar_2_lalu > 1.75 && $rasio_aktiva_lancar_2_lalu <= 2.00) {
            $hasil_perhitungan_rasio_aktiva_lancar_2_lalu = 5;
        }



        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'rasio_aktiva_lancar_ini' => $rasio_aktiva_lancar_ini,
                'hasil_perhitungan_rasio_aktiva_lancar_ini' => $hasil_perhitungan_rasio_aktiva_lancar_ini
            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'rasio_aktiva_lancar_lalu' => $rasio_aktiva_lancar_lalu,
                'hasil_perhitungan_rasio_aktiva_lancar_lalu' => $hasil_perhitungan_rasio_aktiva_lancar_lalu
            ],
            'tahun_2_lalu' => [
                'tahun' => $tahun - 2,
                'rasio_aktiva_lancar_2_lalu' => $rasio_aktiva_lancar_2_lalu,
                'hasil_perhitungan_rasio_aktiva_lancar_2_lalu' => $hasil_perhitungan_rasio_aktiva_lancar_2_lalu
            ]
        ];
    }

    public function hitung_hutang_jk_panjang($tahun)
    {
        $hasil_tahun_ini = $this->_proses_hutang_jk_panjang($tahun);
        $tahun_sebelumnya = $tahun - 1;
        $hasil_tahun_lalu = $this->_proses_hutang_jk_panjang($tahun_sebelumnya);
        $hasil_2tahun_lalu = $this->_proses_hutang_jk_panjang($tahun_sebelumnya - 1);

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'hutang_jk_panjang' => $hasil_tahun_ini,
            ],
            'tahun_lalu' => [
                'tahun' => $tahun_sebelumnya,
                'hutang_jk_panjang' => $hasil_tahun_lalu,
            ],
            '2tahun_lalu' => [
                'tahun' => $tahun_sebelumnya - 1,
                'hutang_jk_panjang' => $hasil_2tahun_lalu,
            ]
        ];
    }

    public function hitung_rasio_hutang_jk_panjang($tahun)
    {
        $hutang_jk_panjang = $this->hitung_hutang_jk_panjang($tahun);
        $hutang_jk_panjang_ini = $hutang_jk_panjang['tahun_ini']['hutang_jk_panjang'];
        $hutang_jk_panjang_lalu = $hutang_jk_panjang['tahun_lalu']['hutang_jk_panjang'];

        $total_ekuitas_audited = $this->hitung_neraca($tahun);
        $total_ekuitas_audited_ini = $total_ekuitas_audited['tahun_ini']['total_ekuitas_audited'];
        $total_ekuitas_audited_lalu = $total_ekuitas_audited['tahun_lalu']['total_ekuitas_audited'];


        $rasio_hutang_jk_panjang_ini = ($total_ekuitas_audited_ini != 0)
            ? ($hutang_jk_panjang_ini / $total_ekuitas_audited_ini)
            : 0;

        $rasio_hutang_jk_panjang_lalu = ($total_ekuitas_audited_lalu != 0)
            ? ($hutang_jk_panjang_lalu / $total_ekuitas_audited_lalu)
            : 0;

        $hasil_Perhitungan_rasio_hutang_jk_panjang_ini = 0;
        $hasil_Perhitungan_rasio_hutang_jk_panjang_lalu = 0;

        if ($rasio_hutang_jk_panjang_ini > 1.0) {
            $hasil_Perhitungan_rasio_hutang_jk_panjang_ini = 1;
        } elseif ($rasio_hutang_jk_panjang_ini > 0.8 && $rasio_hutang_jk_panjang_ini <= 1.0) {
            $hasil_Perhitungan_rasio_hutang_jk_panjang_ini = 2;
        } elseif ($rasio_hutang_jk_panjang_ini > 0.7 && $rasio_hutang_jk_panjang_ini <= 0.8) {
            $hasil_Perhitungan_rasio_hutang_jk_panjang_ini = 3;
        } elseif ($rasio_hutang_jk_panjang_ini > 0.5 && $rasio_hutang_jk_panjang_ini <= 0.7) {
            $hasil_Perhitungan_rasio_hutang_jk_panjang_ini = 4;
        } elseif ($rasio_hutang_jk_panjang_ini <= 0.5) {
            $hasil_Perhitungan_rasio_hutang_jk_panjang_ini = 5;
        }

        if ($rasio_hutang_jk_panjang_lalu > 1.0) {
            $hasil_Perhitungan_rasio_hutang_jk_panjang_lalu = 1;
        } elseif ($rasio_hutang_jk_panjang_lalu > 0.8 && $rasio_hutang_jk_panjang_lalu <= 1.0) {
            $hasil_Perhitungan_rasio_hutang_jk_panjang_lalu = 2;
        } elseif ($rasio_hutang_jk_panjang_lalu > 0.7 && $rasio_hutang_jk_panjang_lalu <= 0.8) {
            $hasil_Perhitungan_rasio_hutang_jk_panjang_lalu = 3;
        } elseif ($rasio_hutang_jk_panjang_lalu > 0.5 && $rasio_hutang_jk_panjang_lalu <= 0.7) {
            $hasil_Perhitungan_rasio_hutang_jk_panjang_lalu = 4;
        } elseif ($rasio_hutang_jk_panjang_lalu <= 0.5) {
            $hasil_Perhitungan_rasio_hutang_jk_panjang_lalu = 5;
        }

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'rasio_hutang_jk_panjang' => $rasio_hutang_jk_panjang_ini,
                'hasil_Perhitungan_rasio_hutang_jk_panjang' => $hasil_Perhitungan_rasio_hutang_jk_panjang_ini
            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'rasio_hutang_jk_panjang' => $rasio_hutang_jk_panjang_lalu,
                'hasil_Perhitungan_rasio_hutang_jk_panjang' => $hasil_Perhitungan_rasio_hutang_jk_panjang_lalu
            ],
        ];
    }

    public function hitung_rasio_aktiva($tahun)
    {
        $total_asset = $this->hitung_neraca($tahun);
        $total_asset_ini = $total_asset['tahun_ini']['total_asset'];
        $total_asset_lalu = $total_asset['tahun_lalu']['total_asset'];

        $total_hutang = $this->hitung_neraca($tahun);
        $total_hutang_ini = $total_hutang['tahun_ini']['total_hutang'];
        $total_hutang_lalu = $total_hutang['tahun_lalu']['total_hutang'];


        $rasio_aktiva_ini = ($total_asset_ini != 0)
            ? ($total_asset_ini / $total_hutang_ini)
            : 0;

        $rasio_aktiva_lalu = ($total_asset_lalu != 0)
            ? ($total_asset_lalu / $total_hutang_lalu)
            : 0;

        $hasil_perhitungan_rasio_aktiva_ini = 0;
        $hasil_perhitungan_rasio_aktiva_lalu = 0;

        if ($rasio_aktiva_ini == 0) {
            $hasil_perhitungan_rasio_aktiva_ini = 0;
        } elseif ($rasio_aktiva_ini <= 1.0) {
            $hasil_perhitungan_rasio_aktiva_ini = 1;
        } elseif ($rasio_aktiva_ini > 1.0 && $rasio_aktiva_ini <= 1.3) {
            $hasil_perhitungan_rasio_aktiva_ini = 2;
        } elseif ($rasio_aktiva_ini > 1.3 && $rasio_aktiva_ini <= 1.7) {
            $hasil_perhitungan_rasio_aktiva_ini = 3;
        } elseif ($rasio_aktiva_ini > 1.7 && $rasio_aktiva_ini <= 2.0) {
            $hasil_perhitungan_rasio_aktiva_ini = 4;
        } elseif ($rasio_aktiva_ini > 2.0) {
            $hasil_perhitungan_rasio_aktiva_ini = 5;
        }

        if ($rasio_aktiva_lalu == 0) {
            $hasil_perhitungan_rasio_aktiva_lalu = 0;
        } elseif ($rasio_aktiva_lalu <= 1.0) {
            $hasil_perhitungan_rasio_aktiva_lalu = 1;
        } elseif ($rasio_aktiva_lalu > 1.0 && $rasio_aktiva_lalu <= 1.3) {
            $hasil_perhitungan_rasio_aktiva_lalu = 2;
        } elseif ($rasio_aktiva_lalu > 1.3 && $rasio_aktiva_lalu <= 1.7) {
            $hasil_perhitungan_rasio_aktiva_lalu = 3;
        } elseif ($rasio_aktiva_lalu > 1.7 && $rasio_aktiva_lalu <= 2.0) {
            $hasil_perhitungan_rasio_aktiva_lalu = 4;
        } elseif ($rasio_aktiva_lalu > 2.0) {
            $hasil_perhitungan_rasio_aktiva_lalu = 5;
        }

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'rasio_aktiva' => $rasio_aktiva_ini,
                'hasil_perhitungan_rasio_aktiva' => $hasil_perhitungan_rasio_aktiva_ini
            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'rasio_aktiva' => $rasio_aktiva_lalu,
                'hasil_perhitungan_rasio_aktiva' => $hasil_perhitungan_rasio_aktiva_lalu
            ],
        ];
    }

    public function hitung_rasio_beban_operasi($tahun)
    {
        $total_beban_operasi = $this->hitung_laba_rugi($tahun);
        $total_beban_operasi_ini = $total_beban_operasi['tahun_ini']['total_beban_operasi'];
        $total_beban_operasi_lalu = $total_beban_operasi['tahun_lalu']['total_beban_operasi'];

        $total_pendapatan_usaha_audited = $this->hitung_laba_rugi($tahun);
        $total_pendapatan_usaha_audited_ini = $total_pendapatan_usaha_audited['tahun_ini']['total_pendapatan_usaha_audited'];
        $total_pendapatan_usaha_audited_lalu = $total_pendapatan_usaha_audited['tahun_lalu']['total_pendapatan_usaha_audited'];

        $rasio_beban_operasi_ini = ($total_beban_operasi_ini != 0)
            ? ($total_beban_operasi_ini / $total_pendapatan_usaha_audited_ini)
            : 0;

        $rasio_beban_operasi_lalu = ($total_beban_operasi_lalu != 0)
            ? ($total_beban_operasi_lalu / $total_pendapatan_usaha_audited_lalu)
            : 0;

        $hasil_perhitungan_rasio_beban_operasi_ini = 0;
        $hasil_perhitungan_rasio_beban_operasi_lalu = 0;

        if ($rasio_beban_operasi_ini == 0) {
            $hasil_perhitungan_rasio_beban_operasi_ini = 0;
        } elseif ($rasio_beban_operasi_ini > 1.0) {
            $hasil_perhitungan_rasio_beban_operasi_ini = 1;
        } elseif ($rasio_beban_operasi_ini > 0.85 && $rasio_beban_operasi_ini <= 1.0) {
            $hasil_perhitungan_rasio_beban_operasi_ini = 2;
        } elseif ($rasio_beban_operasi_ini > 0.65 && $rasio_beban_operasi_ini <= 0.85) {
            $hasil_perhitungan_rasio_beban_operasi_ini = 3;
        } elseif ($rasio_beban_operasi_ini > 0.50 && $rasio_beban_operasi_ini <= 0.65) {
            $hasil_perhitungan_rasio_beban_operasi_ini = 4;
        } elseif ($rasio_beban_operasi_ini <= 0.50) {
            $hasil_perhitungan_rasio_beban_operasi_ini = 5;
        }

        if ($rasio_beban_operasi_lalu == 0) {
            $hasil_perhitungan_rasio_beban_operasi_lalu = 0;
        } elseif ($rasio_beban_operasi_lalu > 1.0) {
            $hasil_perhitungan_rasio_beban_operasi_lalu = 1;
        } elseif ($rasio_beban_operasi_lalu > 0.85 && $rasio_beban_operasi_lalu <= 1.0) {
            $hasil_perhitungan_rasio_beban_operasi_lalu = 2;
        } elseif ($rasio_beban_operasi_lalu > 0.65 && $rasio_beban_operasi_lalu <= 0.85) {
            $hasil_perhitungan_rasio_beban_operasi_lalu = 3;
        } elseif ($rasio_beban_operasi_lalu > 0.50 && $rasio_beban_operasi_lalu <= 0.65) {
            $hasil_perhitungan_rasio_beban_operasi_lalu = 4;
        } elseif ($rasio_beban_operasi_lalu <= 0.50) {
            $hasil_perhitungan_rasio_beban_operasi_lalu = 5;
        }

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'rasio_beban_operasi' => $rasio_beban_operasi_ini,
                'hasil_perhitungan_rasio_beban_operasi' => $hasil_perhitungan_rasio_beban_operasi_ini
            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'rasio_beban_operasi' => $rasio_beban_operasi_lalu,
                'hasil_perhitungan_rasio_beban_operasi' => $hasil_perhitungan_rasio_beban_operasi_lalu
            ],
        ];
    }

    public function hitung_pendapatan_air($tahun)
    {
        $data_pendapatan_ini = $this->Model_langgan->get_pendapatan($tahun);
        $pendapatan_air_ini = 0;
        foreach ($data_pendapatan_ini as $row) {
            $tagihan = $row->by_admin + $row->jas_pem + $row->harga_air;
            $pendapatan_air_ini += $tagihan;
        }

        $data_pendapatan_lalu = $this->Model_langgan->get_pendapatan($tahun - 1);
        $pendapatan_air_lalu = 0;
        foreach ($data_pendapatan_lalu as $row) {
            $tagihan = $row->by_admin + $row->jas_pem + $row->harga_air;
            $pendapatan_air_lalu += $tagihan;
        }

        // kode ini ambil di laporan laba rugi

        // $pendapatan_air = $this->Model_labarugi->get_ppa_input($tahun);
        // $pendapatan_air_ini = 0;
        // $pendapatan_air_lalu = 0;

        // foreach ($pendapatan_air as $row) {
        //     $pendapatan_air_ini += $row->jumlah_pa_tahun_ini ?? 0;
        //     $pendapatan_air_lalu += $row->jumlah_pa_tahun_lalu ?? 0;
        // }
        return [
            'pendapatan_air_tahun_ini' => $pendapatan_air_ini,
            'pendapatan_air_tahun_lalu' => $pendapatan_air_lalu
        ];
    }

    public function hitung_rasio_aktiva_penjualan($tahun)
    {
        $pendapatan_air_ini = $this->hitung_pendapatan_air($tahun)['pendapatan_air_tahun_ini'];
        $pendapatan_air_lalu = $this->hitung_pendapatan_air($tahun)['pendapatan_air_tahun_lalu'];

        $aktiva_produktif_ini = $this->hitung_aktiva_produktif($tahun)['tahun_ini']['aktiva_produktif'];
        $aktiva_produktif_lalu = $this->hitung_aktiva_produktif($tahun)['tahun_lalu']['aktiva_produktif'];

        $rasio_aktiva_produktif_ini = ($pendapatan_air_ini != 0)
            ? ($aktiva_produktif_ini / $pendapatan_air_ini)
            : 0;

        $rasio_aktiva_produktif_lalu = ($pendapatan_air_lalu != 0)
            ? ($aktiva_produktif_lalu / $pendapatan_air_lalu)
            : 0;

        $hasil_perhitungan_rasio_aktiva_produktif_ini = 0;
        $hasil_perhitungan_rasio_aktiva_produktif_lalu = 0;

        if ($rasio_aktiva_produktif_ini == 0) {
            $hasil_perhitungan_rasio_aktiva_produktif_ini = 0;
        } elseif ($rasio_aktiva_produktif_ini > 8) {
            $hasil_perhitungan_rasio_aktiva_produktif_ini = 1;
        } elseif ($rasio_aktiva_produktif_ini > 6 && $rasio_aktiva_produktif_ini <= 8) {
            $hasil_perhitungan_rasio_aktiva_produktif_ini = 2;
        } elseif ($rasio_aktiva_produktif_ini > 4 && $rasio_aktiva_produktif_ini <= 6) {
            $hasil_perhitungan_rasio_aktiva_produktif_ini = 3;
        } elseif ($rasio_aktiva_produktif_ini > 2 && $rasio_aktiva_produktif_ini <= 4) {
            $hasil_perhitungan_rasio_aktiva_produktif_ini = 4;
        } elseif ($rasio_aktiva_produktif_ini <= 2) {
            $hasil_perhitungan_rasio_aktiva_produktif_ini = 5;
        }

        if ($rasio_aktiva_produktif_lalu > 8) {
            $hasil_perhitungan_rasio_aktiva_produktif_lalu = 1;
        } elseif ($rasio_aktiva_produktif_lalu > 6 && $rasio_aktiva_produktif_lalu <= 8) {
            $hasil_perhitungan_rasio_aktiva_produktif_lalu = 2;
        } elseif ($rasio_aktiva_produktif_lalu > 4 && $rasio_aktiva_produktif_lalu <= 6) {
            $hasil_perhitungan_rasio_aktiva_produktif_lalu = 3;
        } elseif ($rasio_aktiva_produktif_lalu > 2 && $rasio_aktiva_produktif_lalu <= 4) {
            $hasil_perhitungan_rasio_aktiva_produktif_lalu = 4;
        } elseif ($rasio_aktiva_produktif_lalu <= 2) {
            $hasil_perhitungan_rasio_aktiva_produktif_lalu = 5;
        }


        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'rasio_aktiva_produktif_ini' => $rasio_aktiva_produktif_ini,
                'hasil_perhitungan_rasio_aktiva_produktif_ini' => $hasil_perhitungan_rasio_aktiva_produktif_ini
            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'rasio_aktiva_produktif_lalu' => $rasio_aktiva_produktif_lalu,
                'hasil_perhitungan_rasio_aktiva_produktif_lalu' => $hasil_perhitungan_rasio_aktiva_produktif_lalu
            ]
        ];
    }

    public function hitung_piutang_usaha($tahun)
    {
        $hasil_tahun_ini = $this->_proses_piutang_usaha($tahun);
        $tahun_sebelumnya = $tahun - 1;
        $hasil_tahun_lalu = $this->_proses_piutang_usaha($tahun_sebelumnya);

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'piutang_usaha' => $hasil_tahun_ini,
            ],
            'tahun_lalu' => [
                'tahun' => $tahun_sebelumnya,
                'piutang_usaha' => $hasil_tahun_lalu,
            ]
        ];
    }

    public function hitung_jangka_waktu_tagih($tahun)
    {
        $piutang_usaha = $this->hitung_piutang_usaha($tahun);
        $piutang_usaha_ini = $piutang_usaha['tahun_ini']['piutang_usaha'];
        $piutang_usaha_lalu = $piutang_usaha['tahun_lalu']['piutang_usaha'];

        $jumlah_penjualan_setahun = $this->hitung_laba_rugi($tahun);
        $total_pendapatan_usaha_audited_ini = $jumlah_penjualan_setahun['tahun_ini']['total_pendapatan_usaha_audited'];
        $total_pendapatan_usaha_audited_lalu = $jumlah_penjualan_setahun['tahun_lalu']['total_pendapatan_usaha_audited'];

        $total_penjualan_perhari_ini = $total_pendapatan_usaha_audited_ini / 365;
        $total_penjualan_perhari_lalu = $total_pendapatan_usaha_audited_lalu / 365;


        $jangka_waktu_tagih_ini = ($piutang_usaha_ini != 0)
            ? ($piutang_usaha_ini / $total_penjualan_perhari_ini)
            : 0;

        $jangka_waktu_tagih_lalu = ($piutang_usaha_lalu != 0)
            ? ($piutang_usaha_lalu / $total_penjualan_perhari_lalu)
            : 0;

        $hasil_perhitungan_penagihan_ini = 0;
        $hasil_perhitungan_penagihan_lalu = 0;

        if ($jangka_waktu_tagih_ini == 0) {
            $hasil_perhitungan_penagihan_ini = 0;
        } elseif ($jangka_waktu_tagih_ini > 180) {
            $hasil_perhitungan_penagihan_ini = 1;
        } elseif ($jangka_waktu_tagih_ini > 150 && $jangka_waktu_tagih_ini <= 180) {
            $hasil_perhitungan_penagihan_ini = 2;
        } elseif ($jangka_waktu_tagih_ini > 90 && $jangka_waktu_tagih_ini <= 150) {
            $hasil_perhitungan_penagihan_ini = 3;
        } elseif ($jangka_waktu_tagih_ini > 60 && $jangka_waktu_tagih_ini <= 90) {
            $hasil_perhitungan_penagihan_ini = 4;
        } elseif ($jangka_waktu_tagih_ini <= 60) {
            $hasil_perhitungan_penagihan_ini = 5;
        }

        if ($jangka_waktu_tagih_lalu > 180) {
            $hasil_perhitungan_penagihan_lalu = 1;
        } elseif ($jangka_waktu_tagih_lalu > 150 && $jangka_waktu_tagih_lalu <= 180) {
            $hasil_perhitungan_penagihan_lalu = 2;
        } elseif ($jangka_waktu_tagih_lalu > 90 && $jangka_waktu_tagih_lalu <= 150) {
            $hasil_perhitungan_penagihan_lalu = 3;
        } elseif ($jangka_waktu_tagih_lalu > 60 && $jangka_waktu_tagih_lalu <= 90) {
            $hasil_perhitungan_penagihan_lalu = 4;
        } elseif ($jangka_waktu_tagih_lalu <= 60) {
            $hasil_perhitungan_penagihan_lalu = 5;
        }

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'jangka_waktu_tagih' => $jangka_waktu_tagih_ini,
                'hasil_perhitungan_penagihan' => $hasil_perhitungan_penagihan_ini
            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'jangka_waktu_tagih' => $jangka_waktu_tagih_lalu,
                'hasil_perhitungan_penagihan' => $hasil_perhitungan_penagihan_lalu
            ]
        ];
    }

    public function hitung_efektifitas($tahun)
    {
        $hasil_tahun_ini = $this->_proses_efektifitas($tahun);
        $tahun_sebelumnya = $tahun - 1;
        $hasil_tahun_lalu = $this->_proses_efektifitas($tahun_sebelumnya);

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'rek_tagih' => $hasil_tahun_ini['rek_tagih'],
                'total_pa' => $hasil_tahun_ini['total_pa'],
                'hasil_efek' => $hasil_tahun_ini['hasil_efek'],
                'persen_efek' => $hasil_tahun_ini['persen_efek']
            ],
            'tahun_lalu' => [
                'tahun' => $tahun_sebelumnya,
                'rek_tagih' => $hasil_tahun_lalu['rek_tagih'],
                'total_pa' => $hasil_tahun_lalu['total_pa'],
                'hasil_efek' => $hasil_tahun_lalu['hasil_efek'],
                'persen_efek' => $hasil_tahun_lalu['persen_efek']
            ]

        ];
    }

    public function hitung_nilai_keuangan($tahun)
    {
        $hasil_perhitungan_rasio_laba_ini = $this->hitung_rasio_laba($tahun)['tahun_ini']['hasil_perhitungan_rasio_laba_ini'];
        $hasil_perhitungan_rasio_laba_lalu = $this->hitung_rasio_laba($tahun)['tahun_lalu']['hasil_perhitungan_rasio_laba_lalu'];

        $hasil_perhitungan_bonus_rasio_laba_ini = $this->hitung_bonus_rasio_laba($tahun)['tahun_ini']['hasil_perhitungan_bonus_rasio_laba_ini'];
        $hasil_perhitungan_bonus_rasio_laba_lalu = $this->hitung_bonus_rasio_laba($tahun)['tahun_lalu']['hasil_perhitungan_bonus_rasio_laba_lalu'];

        $hasil_perhitungan_rasio_laba_jual_ini = $this->hitung_rasio_laba_jual($tahun)['tahun_ini']['hasil_perhitungan_rasio_laba_jual_ini'];
        $hasil_perhitungan_rasio_laba_jual_lalu = $this->hitung_rasio_laba_jual($tahun)['tahun_lalu']['hasil_perhitungan_rasio_laba_jual_lalu'];

        $hasil_perhitungan_bonus_rasio_laba_jual_ini = $this->hitung_bonus_rasio_laba_jual($tahun)['tahun_ini']['hasil_perhitungan_bonus_rasio_laba_jual_ini'];
        $hasil_perhitungan_bonus_rasio_laba_jual_lalu = $this->hitung_bonus_rasio_laba_jual($tahun)['tahun_lalu']['hasil_perhitungan_bonus_rasio_laba_jual_lalu'];

        $hasil_perhitungan_rasio_aktiva_lancar_ini = $this->hitung_rasio_aktiva_lancar($tahun)['tahun_ini']['hasil_perhitungan_rasio_aktiva_lancar_ini'];
        $hasil_perhitungan_rasio_aktiva_lancar_lalu = $this->hitung_rasio_aktiva_lancar($tahun)['tahun_lalu']['hasil_perhitungan_rasio_aktiva_lancar_lalu'];

        $hasil_Perhitungan_rasio_hutang_jk_panjang_ini = $this->hitung_rasio_hutang_jk_panjang($tahun)['tahun_ini']['hasil_Perhitungan_rasio_hutang_jk_panjang'];
        $hasil_Perhitungan_rasio_hutang_jk_panjang_lalu = $this->hitung_rasio_hutang_jk_panjang($tahun)['tahun_lalu']['hasil_Perhitungan_rasio_hutang_jk_panjang'];

        $hasil_perhitungan_rasio_aktiva_ini = $this->hitung_rasio_aktiva($tahun)['tahun_ini']['hasil_perhitungan_rasio_aktiva'];
        $hasil_perhitungan_rasio_aktiva_lalu = $this->hitung_rasio_aktiva($tahun)['tahun_lalu']['hasil_perhitungan_rasio_aktiva'];

        $hasil_perhitungan_rasio_beban_operasi_ini = $this->hitung_rasio_beban_operasi($tahun)['tahun_ini']['hasil_perhitungan_rasio_beban_operasi'];
        $hasil_perhitungan_rasio_beban_operasi_lalu = $this->hitung_rasio_beban_operasi($tahun)['tahun_lalu']['hasil_perhitungan_rasio_beban_operasi'];

        $hasil_perhitungan_rasio_laba_sbl_susut_ini = $this->hitung_lr_sbl_susut($tahun)['tahun_ini']['hasil_perhitungan_rasio_laba_sbl_susut'];
        $hasil_perhitungan_rasio_laba_sbl_susut_lalu = $this->hitung_lr_sbl_susut($tahun)['tahun_lalu']['hasil_perhitungan_rasio_laba_sbl_susut'];

        $hasil_perhitungan_rasio_aktiva_produktif_ini = $this->hitung_rasio_aktiva_penjualan($tahun)['tahun_ini']['hasil_perhitungan_rasio_aktiva_produktif_ini'];
        $hasil_perhitungan_rasio_aktiva_produktif_lalu = $this->hitung_rasio_aktiva_penjualan($tahun)['tahun_lalu']['hasil_perhitungan_rasio_aktiva_produktif_lalu'];

        $hasil_perhitungan_penagihan_ini = $this->hitung_jangka_waktu_tagih($tahun)['tahun_ini']['hasil_perhitungan_penagihan'];
        $hasil_perhitungan_penagihan_lalu = $this->hitung_jangka_waktu_tagih($tahun)['tahun_lalu']['hasil_perhitungan_penagihan'];

        $hasil_efek_ini = $this->hitung_efektifitas($tahun)['tahun_ini']['hasil_efek'];
        $hasil_efek_lalu = $this->hitung_efektifitas($tahun)['tahun_lalu']['hasil_efek'];

        $total_hasil_keuangan_ini = $hasil_perhitungan_rasio_laba_ini + $hasil_perhitungan_bonus_rasio_laba_ini + $hasil_perhitungan_rasio_laba_jual_ini + $hasil_perhitungan_bonus_rasio_laba_jual_ini + $hasil_perhitungan_rasio_aktiva_lancar_ini + $hasil_Perhitungan_rasio_hutang_jk_panjang_ini + $hasil_perhitungan_rasio_aktiva_ini + $hasil_perhitungan_rasio_beban_operasi_ini + $hasil_perhitungan_rasio_laba_sbl_susut_ini +  $hasil_perhitungan_rasio_aktiva_produktif_ini + $hasil_perhitungan_penagihan_ini + $hasil_efek_ini;

        $total_hasil_keuangan_lalu = $hasil_perhitungan_rasio_laba_lalu + $hasil_perhitungan_bonus_rasio_laba_lalu + $hasil_perhitungan_rasio_laba_jual_lalu + $hasil_perhitungan_bonus_rasio_laba_jual_lalu + $hasil_perhitungan_rasio_aktiva_lancar_lalu + $hasil_Perhitungan_rasio_hutang_jk_panjang_lalu + $hasil_perhitungan_rasio_aktiva_lalu + $hasil_perhitungan_rasio_beban_operasi_lalu + $hasil_perhitungan_rasio_laba_sbl_susut_lalu +  $hasil_perhitungan_rasio_aktiva_produktif_lalu + $hasil_perhitungan_penagihan_lalu + $hasil_efek_lalu;

        $nilai_kinerja_keuangan_ini = $total_hasil_keuangan_ini * 45 / 60;
        $nilai_kinerja_keuangan_lalu = $total_hasil_keuangan_lalu * 45 / 60;

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'total_hasil_keuangan_ini' => $total_hasil_keuangan_ini,
                'nilai_kinerja_keuangan_ini' => $nilai_kinerja_keuangan_ini,
            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'total_hasil_keuangan_lalu' => $total_hasil_keuangan_lalu,
                'nilai_kinerja_keuangan_lalu' => $nilai_kinerja_keuangan_lalu,
            ],

        ];
    }

    // fungsi privat
    private function _aktiva_tidak_lancar($tahun)
    {
        $this->db->select('akun, nilai_neraca_audited');
        $this->db->from('neraca');
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where_in('akun', ['Aset Tetap', 'Akm Depresiasi Aset Tetap']);
        $query = $this->db->get();
        $result = $query->result();

        $aset_tetap = 0;
        $akm_depresiasi = 0;

        foreach ($result as $row) {
            if ($row->akun == 'Aset Tetap') {
                $aset_tetap = $row->nilai_neraca_audited ?? 0;
            } elseif ($row->akun == 'Akm Depresiasi Aset Tetap') {
                $akm_depresiasi = $row->nilai_neraca_audited ?? 0;
            }
        }

        return $aset_tetap + $akm_depresiasi;
    }

    private function _proses_laba_rugi($tahun)
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
        $total_pendapatan_usaha_audited = $total_pendapatan_usaha_audited;
        $total_beban_operasi = $total_beban_usaha_audited + $total_beban_umum_administrasi_audited;

        return [
            'laba_rugi_bersih' => $laba_rugi_bersih,
            'labarugi_bersih_sebelum_pajak' => $labarugi_bersih_sebelum_pajak,
            'total_pendapatan_usaha_audited' => $total_pendapatan_usaha_audited,
            'total_beban_operasi' => $total_beban_operasi
        ];
    }

    private function _proses_neraca($tahun)
    {
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
        $total_aset_lancar_audited = $total_aset_lancar_audited;
        $total_asset = $total_aset_lancar_audited + $total_aset_tidak_lancar_audited;
        return [
            'total_aset_lancar_audited' => $total_aset_lancar_audited,
            'total_liabilitas_jangka_pendek_audited' => $total_liabilitas_jangka_pendek_audited,
            'total_ekuitas_audited' => $total_ekuitas_audited,
            'total_asset' => $total_asset,
            'total_hutang' => $total_liabilitas_jangka_pendek_audited + $total_liabilitas_jangka_panjang_audited
        ];
    }

    private function _proses_hutang_jk_panjang($tahun)
    {
        $this->db->select('akun, nilai_neraca_audited');
        $this->db->from('neraca');
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where_in('akun', ['Liabilitas Pajak Tanggguhan', 'Liabilitas Imbalan Pasca Kerja (pj)', 'Liabilitas Imbalan Pasca Kerja Dapenma (pj)']);
        $query = $this->db->get();
        $result = $query->result();

        $hutang_jk_panjang = 0;

        foreach ($result as $row) {
            $hutang_jk_panjang += (float) ($row->nilai_neraca_audited ?? 0);
        }

        return $hutang_jk_panjang;
    }

    private function _proses_piutang_usaha($tahun)
    {
        $this->db->select('akun, nilai_neraca_audited');
        $this->db->from('neraca');
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where_in('akun', ['Piutang Usaha', 'Akm Kerugian Piutang Usaha']);
        $query = $this->db->get();
        $result = $query->result();

        $piutang_usaha = 0;
        $akm_kerugian_piutang = 0;

        foreach ($result as $row) {
            if ($row->akun == 'Piutang Usaha') {
                $piutang_usaha = $row->nilai_neraca_audited ?? 0;
            } elseif ($row->akun == 'Akm Kerugian Piutang Usaha') {
                $akm_kerugian_piutang = $row->nilai_neraca_audited ?? 0;
            }
        }

        return $piutang_usaha + $akm_kerugian_piutang;
    }

    private function _proses_efektifitas($tahun)
    {
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

        // $ppa_input = $this->Model_labarugi->get_ppa_input($tahun);
        // $total_pa_tahun_ini = 0;
        // if (!empty($ppa_input)) {
        //     foreach ($ppa_input as $row) {
        //         $total_pa_tahun_ini += $row->jumlah_pa_tahun_ini;
        //     }
        // }

        $pendapatan = $this->Model_langgan->get_pendapatan($tahun);
        $total_pa_tahun_ini = 0;
        foreach ($pendapatan as $row) {
            $tagihan = $row->by_admin + $row->jas_pem + $row->harga_air;
            $total_pa_tahun_ini += $tagihan;
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

        return [
            'persen_efek' => $persen_efek,
            'hasil_efek' => $hasil_perhitungan_efek,
            'rek_tagih' => $rek_tagih,
            'total_pa' => $total_pa_tahun_ini
        ];
    }
}
