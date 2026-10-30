<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Evaluasi risiko (ISO 31000:2018 Klausa 6.6) — membandingkan hasil analisis
 * terhadap kriteria risiko (tingkat risiko + risk appetite) lalu memutuskan
 * keputusan perlakuan: Accept, Monitor, Treat, atau Escalate.
 */
class Evaluasi extends MY_Controller
{
    private $tujuan = 'manrisk/evaluasi';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_register');
        $this->load->model('Model_mri_analisa');
        $this->load->model('Model_mri_evaluasi');
        $this->load->model('Model_mri_master');
        $this->load->model('Model_mri_pengaturan');
        $this->load->library('form_validation');
    }

    // ------------------------------------------------------------------
    // DAFTAR + REKAP KEPUTUSAN
    // ------------------------------------------------------------------
    public function index()
    {
        $tahun = $this->tahunAktif(2027);
        $f = ['tahun' => $tahun];
        if (!$this->aksesSemuaUnit()) {
            $f['id_upk'] = $this->unitPaksa();
        } elseif ($this->angka('id_upk')) {
            $f['id_upk'] = $this->angka('id_upk');
        }
        if ($this->teks('keputusan') !== '') {
            $f['keputusan'] = $this->teks('keputusan');
        }

        $daftar = $this->Model_mri_register->getListDenganSkor($f);
        $evals  = [];
        foreach ($daftar as $r) {
            $evals[$r->id_register] = $this->Model_mri_evaluasi->getByRegister($r->id_register);
        }

        $rekap = [];
        foreach ($this->Model_mri_evaluasi->rekapKeputusan($tahun) as $row) {
            $rekap[$row->keputusan] = (int) $row->jml;
        }
        $eskalasi = count($this->Model_mri_evaluasi->perluEskalasi($tahun));

        $this->render('manrisk/view_evaluasi', [
            'title'        => 'EVALUASI RISIKO',
            'tahun'        => $tahun,
            'filter'       => $f,
            'daftar'       => $daftar,
            'evals'        => $evals,
            'rekap'        => $rekap,
            'eskalasi'     => $eskalasi,
            'keputusan'    => $this->Model_mri_evaluasi::KEPUTUSAN,
            'unit_list'    => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
            'tingkat'      => $this->Model_mri_master->getTingkat(),
            'akses_semua'  => $this->aksesSemuaUnit(),
            'boleh_tulis'  => $this->Model_mri_pengaturan->bolehUbah('analisa_risiko'),
        ]);
    }

    // ------------------------------------------------------------------
    // FORMULIR
    // ------------------------------------------------------------------
    public function form($id_register = null)
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
            $this->pesan('Evaluasi risiko sedang dikunci. Buka kunci pada Pengaturan terlebih dahulu.', 'warning');
            redirect('manrisk/register/detail/' . $r->id_register);
            return;
        }
        $analisa = $this->Model_mri_analisa->getByRegister($r->id_register);
        if (!$analisa) {
            $this->pesan('Risiko ini belum dianalisis. Selesaikan <strong>Analisa</strong> terlebih dahulu.', 'warning');
            redirect('manrisk/analisa/isi/' . $r->id_register);
            return;
        }

        $aturan = [
            ['nilai_evaluasi', 'Nilai Evaluasi', 'required|integer|greater_than_equal_to[1]|less_than_equal_to[25]'],
            ['keputusan', 'Keputusan', 'required|in_list[Accept,Monitor,Treat,Escalate]'],
            ['triwulan', 'Triwulan', 'required|integer|greater_than_equal_to[1]|less_than_equal_to[4]'],
        ];
        foreach ($aturan as $a) {
            $this->form_validation->set_rules($a[0], $a[1], $a[2]);
        }
        $this->form_validation->set_message('greater_than_equal_to', '%s minimal %s');
        $this->form_validation->set_message('less_than_equal_to', '%s maksimal %s');
        $this->form_validation->set_message('in_list', '%s tidak valid');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->diposting() && $this->form_validation->run() === true) {
            $d = [
                'nilai_evaluasi'   => (int) $this->input->post('nilai_evaluasi', true),
                'keputusan'        => $this->teks('keputusan'),
                'alasan_evaluasi'  => $this->teks('alasan_evaluasi'),
                'kriteria_rujukan' => $this->teks('kriteria_rujukan') ?: 'mri_tingkat_risiko + mri_appetite',
                'perlu_eskalasi'   => $this->teks('perlu_eskalasi') === 'YA',
                'eskalasi_ke'      => $this->teks('eskalasi_ke'),
                'tanggal_evaluasi' => $this->teks('tanggal_evaluasi') ?: date('Y-m-d'),
                'triwulan'         => (int) $this->input->post('triwulan', true),
                'id_analisa'       => $analisa->id_analisa,
            ];

            $this->Model_mri_evaluasi->simpan($r->id_register, $d);

            $this->pesan('Evaluasi <strong>' . html_escape($r->kode_risiko) . '</strong> disimpan dengan keputusan '
                . '<strong>' . html_escape($d['keputusan']) . '</strong>.');
            redirect('manrisk/register/detail/' . $r->id_register);
            return;
        }

        $this->render('manrisk/view_evaluasi_form', [
            'title'     => 'EVALUASI ' . $r->kode_risiko,
            'risiko'    => $r,
            'analisa'   => $analisa,
            'evaluasi'  => $this->Model_mri_evaluasi->getByRegister($r->id_register),
            'riwayat'   => $this->Model_mri_evaluasi->getRiwayatEvaluasi($r->id_register),
            'tingkat'   => $this->Model_mri_master->getTingkat(),
            'appetite'  => $this->Model_mri_pengaturan->getAppetite((int) $r->tahun),
        ]);
    }
}
