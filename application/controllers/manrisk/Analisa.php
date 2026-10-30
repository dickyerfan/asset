<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Analisis risiko (ISO 31000:2018 Klausa 6.4) — skor L x D.
 *
 * Tanpa $id : daftar risiko beserta status analisisnya.
 * Dengan $id: formulir analisis satu risiko (dipakai juga dari halaman detail).
 */
class Analisa extends MY_Controller
{
    private $tujuan = 'manrisk/analisa';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_register');
        $this->load->model('Model_mri_analisa');
        $this->load->model('Model_mri_master');
        $this->load->model('Model_mri_pengaturan');
        $this->load->model('Model_mri_risk_owner');
        $this->load->library('form_validation');
    }

    /** Filter daftar risiko (tanpa id) dari request. */
    private function filter()
    {
        $f = [];
        if (!$this->aksesSemuaUnit()) {
            $f['id_upk'] = $this->unitPaksa();
        } elseif ($this->angka('id_upk')) {
            $f['id_upk'] = $this->angka('id_upk');
        }
        if ($this->angka('id_kategori')) {
            $f['id_kategori'] = $this->angka('id_kategori');
        }
        if ($this->teks('status_risiko') !== '') {
            $f['status_risiko'] = $this->teks('status_risiko');
        }
        if ($this->teks('q') !== '') {
            $f['q'] = $this->teks('q');
        }
        $f['tahun'] = $this->tahunAktif(2027);
        return $f;
    }

    // ------------------------------------------------------------------
    // DAFTAR
    // ------------------------------------------------------------------
    public function index($id = null)
    {
        if ($id !== null) {
            $this->isi($id);
            return;
        }

        $f = $this->filter();
        $semua = $this->Model_mri_register->getListDenganSkor($f);

        $teranalisa = 0;
        foreach ($semua as $r) {
            if ($r->nilai_inherent !== null) {
                $teranalisa++;
            }
        }

        $this->render('manrisk/view_analisa', [
            'title'          => 'ANALISIS RISIKO',
            'tahun'          => $f['tahun'],
            'filter'         => $f,
            'daftar'         => $semua,
            'unit_list'      => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
            'kategori_list'  => $this->Model_mri_master->getKategori(),
            'tingkat'        => $this->Model_mri_master->getTingkat(),
            'teranalisa'     => $teranalisa,
            'akses_semua'    => $this->aksesSemuaUnit(),
            'boleh_tulis'    => $this->Model_mri_pengaturan->bolehUbah('analisa_risiko'),
        ]);
    }

    // ------------------------------------------------------------------
    // FORMULIR
    // ------------------------------------------------------------------
    public function isi($id_register = null)
    {
        $r = $this->Model_mri_register->getById($id_register);
        if (!$r) {
            $this->pesan('Risiko tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->tolakBilaBukanUnit($r->id_upk, $this->tujuan)) {
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('analisa_risiko')) {
            $this->pesan('Analisa risiko sedang dikunci. Buka kunci pada Pengaturan terlebih dahulu.', 'warning');
            redirect('manrisk/register/detail/' . $r->id_register);
            return;
        }

        $aturan = [
            ['inherent_l', 'Likelihood Inherent', 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]'],
            ['inherent_d', 'Dampak Inherent', 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]'],
            ['residual_l', 'Likelihood Residual', 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]'],
            ['residual_d', 'Dampak Residual', 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]'],
        ];
        foreach ($aturan as $a) {
            $this->form_validation->set_rules($a[0], $a[1], $a[2]);
        }
        $this->form_validation->set_message('greater_than_equal_to', '%s minimal %s');
        $this->form_validation->set_message('less_than_equal_to', '%s maksimal %s');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->diposting() && $this->form_validation->run() === true) {
            $d = [
                'inherent_l'     => (int) $this->input->post('inherent_l', true),
                'inherent_d'     => (int) $this->input->post('inherent_d', true),
                'residual_l'     => (int) $this->input->post('residual_l', true),
                'residual_d'     => (int) $this->input->post('residual_d', true),
                'kontrol_uraian' => $this->teks('kontrol_uraian'),
                'kontrol_ada'    => $this->teks('kontrol_ada') === 'Ada' ? 'Ada' : 'Tidak',
                'memadai'        => $this->teks('memadai') === 'Memadai' ? 'Memadai' : 'Belum Memadai',
                'ringkasan'      => $this->teks('ringkasan'),
                'id_risk_owner'  => $this->angka('id_risk_owner'),
            ];

            $this->Model_mri_analisa->simpan($r->id_register, $d);

            // status kendali mengikuti ketersediaan & kelayakan kontrol
            $status = ($d['kontrol_ada'] === 'Ada' && $d['memadai'] === 'Memadai') ? 'C' : 'UC';
            $this->db->where('id_register', $r->id_register);
            $this->db->update('mri_register', [
                'status_kendali' => $status,
                'modified_at'    => date('Y-m-d H:i:s'),
                'modified_by'    => $this->session->userdata('nama_pengguna') ?: 'sistem',
            ]);

            $this->pesan('Analisa <strong>' . html_escape($r->kode_risiko) . '</strong> disimpan. '
                . 'Kendali ditetapkan <strong>' . $status . '</strong>.');
            redirect('manrisk/register/detail/' . $r->id_register);
            return;
        }

        $this->render('manrisk/view_analisa_form', [
            'title'      => 'ANALISA ' . $r->kode_risiko,
            'risiko'     => $r,
            'analisa'    => $this->Model_mri_analisa->getByRegister($r->id_register),
            'tingkat'    => $this->Model_mri_master->getTingkat(),
            'owner_list' => $this->Model_mri_risk_owner->getOwner(),
            'peta'       => $this->Model_mri_master->petaLevel(),
        ]);
    }

    // ------------------------------------------------------------------
    // PERIKSA INVARIAN (alat pemeriksaan, tidak mengubah data)
    // ------------------------------------------------------------------
    public function periksa()
    {
        if ($this->aksesSemuaUnit()) {
            $pelanggaran = $this->Model_mri_analisa->verifikasiInvarian();
            if (!$pelanggaran) {
                $this->pesan('Seluruh analisa memenuhi invarian L&times;D dan band level. <strong>Tidak ada pelanggaran.</strong>');
            } else {
                $this->pesan('Ditemukan <strong>' . count($pelanggaran) . '</strong> pelanggaran invarian.', 'danger');
            }
        }
        redirect($this->tujuan);
    }
}
