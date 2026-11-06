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

    /**
     * Panduan pengisian langkah demi langkah, dari Sasaran/Objektif
     * hingga Laporan + PDF (dokumen PANDUAN_PENGISIAN_MANAJEMEN_RISIKO.md).
     *
     * Halaman bacaan-saja; berisi konten dan progres ringkas dari database.
     */
    public function pengisian()
    {
        $tahun = $this->tahunAktif(2027);

        // progres ringkas tiap tahap agar panduan terasa "hidup"
        $alur = $this->Model_mri_dashboard->ringkasAlur($tahun);

        $rekap = [
            'objektif_total' => (int) $this->db->where('tahun', $tahun)->count_all_results('mri_objektif'),
            'objektif_belum' => (int) $this->db->where('tahun', $tahun)
                ->where('status_rekonsiliasi', 'BELUM')->count_all_results('mri_objektif'),
            'lingkup_final'  => (int) $this->db->where('tahun', $tahun)
                ->where('status', 'FINAL')->count_all_results('mri_lingkup'),
            'lingkup_total'  => (int) $this->db->where('tahun', $tahun)->count_all_results('mri_lingkup'),
            'risiko'         => (int) $this->db->where('tahun', $tahun)->count_all_results('mri_register'),
            'evaluasi'       => (int) $this->db->from('mri_evaluasi e')
                ->join('mri_register r', 'r.id_register = e.id_register')
                ->where('r.tahun', $tahun)->count_all_results(),
            'perlu_review'   => (int) $this->db->from('mri_evaluasi e')
                ->join('mri_register r', 'r.id_register = e.id_register')
                ->where('r.tahun', $tahun)
                ->where('e.status_review', 'PERLU_REVIEW')->count_all_results(),
            'perlakuan'      => (int) $this->db->from('mri_perlakuan p')
                ->join('mri_register r', 'r.id_register = p.id_register')
                ->where('r.tahun', $tahun)->count_all_results(),
            'perlakuan_belum' => (int) $this->db->from('mri_perlakuan p')
                ->join('mri_register r', 'r.id_register = p.id_register')
                ->where('r.tahun', $tahun)
                ->where('p.status_laksana', 'BELUM')->count_all_results(),
            'monitoring'     => (int) $this->db->where('tahun', $tahun)->count_all_results('mri_monitoring'),
            'laporan'        => (int) $this->db->where('tahun', $tahun)->count_all_results('mri_laporan'),
        ];

        $this->render('manrisk/view_panduan_pengisian', [
            'title'   => 'PANDUAN PENGISIAN LANGKAH DEMI LANGKAH',
            'tahun'   => $tahun,
            'alur'    => $alur,
            'rekap'   => $rekap,
            'tingkat' => $this->Model_mri_master->getTingkat(),
        ]);
    }
}
