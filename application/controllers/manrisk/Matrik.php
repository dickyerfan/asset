<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Matriks risiko 5 x 5 (Likelihood vs Dampak), inherent dan residual.
 *
 * Penempatan seluruh berasal dari mri_analisa.inherent_l/d dan residual_l/d,
 * sehingga matriks tidak pernah berbeda dengan angka pada Risk Register.
 */
class Matrik extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_master');
        $this->load->model('Model_mri_dashboard');
        $this->load->model('Model_mri_register');
    }

    public function index()
    {
        $tahun = $this->tahunAktif(2027);
        $f = [];
        if (!$this->aksesSemuaUnit()) {
            $f['id_upk'] = $this->unitPaksa();
        } elseif ($this->angka('id_upk')) {
            $f['id_upk'] = $this->angka('id_upk');
        }
        if ($this->angka('id_kategori')) {
            $f['id_kategori'] = $this->angka('id_kategori');
        }

        $inherent = $this->Model_mri_dashboard->matriks($tahun, $f);
        $residual = $this->Model_mri_dashboard->matriksResidual($tahun, $f);
        $tingkat  = $this->Model_mri_master->getTingkat();

        // level untuk tiap skor 1..25 agar warna sel konsisten dengan register
        $warnaSkor = [];
        for ($s = 1; $s <= 25; $s++) {
            $t = $this->Model_mri_master->levelDariSkor($s);
            $warnaSkor[$s] = $t ? $t->warna : '#e9ecef';
        }

        $this->render('manrisk/view_matrik', [
            'title'       => 'MATRIKS RISIKO',
            'tahun'       => $tahun,
            'filter'      => $f,
            'inherent'    => $inherent,
            'residual'    => $residual,
            'tingkat'     => $tingkat,
            'warnaSkor'   => $warnaSkor,
            'unit_list'   => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
            'kategori_list' => $this->Model_mri_master->getKategori(),
            'akses_semua' => $this->aksesSemuaUnit(),
        ]);
    }
}
