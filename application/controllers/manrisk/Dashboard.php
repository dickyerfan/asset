<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Dashboard manrisk — ringkasan risiko 2027+ berdasar SNI ISO 31000:2018.
 *
 * Terpisah dari risiko/dashboard yang melayani data legacy 2025-2026.
 */
class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_dashboard');
        $this->load->model('Model_mri_register');
        $this->load->model('Model_mri_lingkup');
        $this->load->model('Model_mri_kri');
        $this->load->model('Model_mri_master');
    }

    public function index()
    {
        $tahun = $this->tahunAktif(date('Y') + 1); // default tahun aktif, jika tidak ada filter

        $f = [];
        $id_upk = $this->angka('id_upk');
        $id_kategori = $this->angka('id_kategori');
        $tipe = $this->teks('tipe_risiko');
        $q = $this->teks('q');

        if (!$this->aksesSemuaUnit()) {
            $id_upk = $this->unitPaksa(); // paksa pada unit pengguna
        }
        if ($id_upk) {
            $f['id_upk'] = $id_upk;
        }
        if ($id_kategori) {
            $f['id_kategori'] = $id_kategori;
        }
        if ($tipe && in_array($tipe, ['Ancaman', 'Peluang'], true)) {
            $f['tipe_risiko'] = $tipe;
        }
        if ($q !== '') {
            $f['q'] = $q;
        }

        $data = [
            'title'       => 'DASHBOARD MANAJEMEN RISIKO',
            'tahun'       => $tahun,
            'filter'      => $f,
            'dashboard'   => $this->Model_mri_dashboard->rangkum($tahun, $f),
            'unit_list'   => $this->Model_mri_dashboard->daftarUnit(),
            'kategori_list' => $this->Model_mri_master->getKategori(),
            'lingkup'     => $this->Model_mri_lingkup->getLingkupAktif($tahun),
            'akses_semua' => $this->aksesSemuaUnit(),
            'daftar_tahun' => $this->daftarTahun(),
            'kri_rekap'   => $this->Model_mri_kri->rekapStatus($tahun),
            'matriks'       => $this->Model_mri_dashboard->matriks($tahun, $f),
            'matriks_res'   => $this->Model_mri_dashboard->matriksResidual($tahun, $f),
            'alur'          => $this->Model_mri_dashboard->ringkasAlur($tahun),
        ];

        $this->render('manrisk/view_dashboard', $data);
    }

    /**
     * Daftar tahun yang punya data register (untuk dropdown filter).
     * @return array
     */
    private function daftarTahun()
    {
        $tahun = $this->db->select('tahun')->distinct()->order_by('tahun', 'DESC')->get('mri_register')->result();
        $out = [];
        foreach ($tahun as $t) {
            $out[] = (int) $t->tahun;
        }
        if (!in_array(2027, $out, true)) {
            $out[] = 2027;
            sort($out);
        }
        return $out;
    }
}
