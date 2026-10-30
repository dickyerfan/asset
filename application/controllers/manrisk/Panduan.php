<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Petunjuk penggunaan modul manrisk.
 *
 * Halaman bacaan-saja — tidak ada satu pun method tulis. Isinya adalah
 * panduan kerja yang selama ini hanya hidup di dokumen kertas
 * (D:\MR 2026\Ringkasan_MR_Perumdam_Ijen_Tirta.md), dipindahkan ke
 * dalam aplikasi agar pengguna tidak perlu keluar aplikasi untuk
 * mengetahui alur, skala, kriteria, dan istilah yang dipakai.
 *
 * Segmen yang mengikuti data (8 tahap, skala level, matriks, kategori)
 * diambil langsung dari mri_tingkat_risiko / mri_kategori_risiko dan
 * Model_mri_master::tahapan(), sehingga ikut berubah bila Pengaturan
 * menyuntingnya — tanpa perlu menyunting berkas ini.
 */
class Panduan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_master');
        $this->load->model('Model_mri_dashboard');
    }

    public function index()
    {
        $tahun = $this->tahunAktif(2027);

        $this->render('manrisk/view_panduan', [
            'title'   => 'PETUNJUK PENGGUNAAN',
            'tahun'   => $tahun,
            'tingkat' => $this->Model_mri_master->getTingkat(),
            'kategori' => $this->Model_mri_master->getKategori(),
            'tahapan' => $this->Model_mri_dashboard->ringkasAlur($tahun),
        ]);
    }
}
