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

        // Filter status review: ?review=PERLU_REVIEW | SESUAI | DITINJAU
        $statusReview = strtoupper($this->teks('review'));
        if (!in_array($statusReview, ['SESUAI', 'PERLU_REVIEW', 'DITINJAU'], true)) {
            $statusReview = '';
        }

        $daftar = $this->Model_mri_register->getListDenganSkor($f);
        $evals  = [];
        foreach ($daftar as $id => $r) {
            $evals[$r->id_register] = $this->Model_mri_evaluasi->getByRegister($r->id_register);
        }
        if ($statusReview !== '') {
            $daftar = array_values(array_filter($daftar, function ($r) use ($evals, $statusReview) {
                $e = isset($evals[$r->id_register]) ? $evals[$r->id_register] : null;
                return $e && $e->status_review === $statusReview;
            }));
        }

        $rekap = [];
        foreach ($this->Model_mri_evaluasi->rekapKeputusan($tahun) as $row) {
            $rekap[$row->keputusan] = (int) $row->jml;
        }
        $rekapReview = $this->Model_mri_evaluasi->rekapStatusReview($tahun);
        $eskalasi = count($this->Model_mri_evaluasi->perluEskalasi($tahun));

        $this->render('manrisk/view_evaluasi', [
            'title'         => 'EVALUASI RISIKO',
            'tahun'         => $tahun,
            'filter'        => $f,
            'filter_review' => $statusReview,
            'daftar'        => $daftar,
            'evals'         => $evals,
            'rekap'         => $rekap,
            'rekap_review'  => $rekapReview,
            'eskalasi'      => $eskalasi,
            'keputusan'     => $this->Model_mri_evaluasi::KEPUTUSAN,
            'unit_list'     => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
            'tingkat'       => $this->Model_mri_master->getTingkat(),
            'akses_semua'   => $this->aksesSemuaUnit(),
            'boleh_tulis'   => $this->Model_mri_pengaturan->bolehUbah('analisa_risiko'),
            'boleh_review'  => $this->bolehReview(),
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

        // Guardrail QRMO slide 39: evaluasi SELALU memakai skor residual.
        // Input nilai_evaluasi tidak lagi bebas — server mengabaikan POST-nya.
        $nilaiResidual = (int) $analisa->nilai_residual;
        $band = $this->Model_mri_evaluasi->aksiBandDariSkor($nilaiResidual);

        $aturan = [
            ['keputusan', 'Keputusan', 'required|in_list[Accept,Monitor,Treat,Escalate]'],
            ['alasan_evaluasi', 'Alasan / Dasar Penilaian', 'required'],
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
            $keputusan = $this->teks('keputusan');
            $alasan    = $this->teks('alasan_evaluasi');
            $override  = $band !== null && $keputusan !== $band;

            // Snapshot kriteria mengikuti skala aktif (bukan teks bebas).
            $kriteria = sprintf(
                'Residual %d (%s) vs band %s; skala aktif: %s',
                $nilaiResidual,
                $analisa->level_residual,
                $band ?: '-',
                $this->ringkasSkala()
            );

            $d = [
                'nilai_evaluasi'   => $nilaiResidual,
                'keputusan'        => $keputusan,
                'keputusan_band'   => $band,
                'status_review'    => $override ? 'PERLU_REVIEW' : 'SESUAI',
                'alasan_evaluasi'  => $alasan,
                'kriteria_rujukan' => $kriteria,
                'perlu_eskalasi'   => $this->teks('perlu_eskalasi') === 'YA',
                'eskalasi_ke'      => $this->teks('eskalasi_ke'),
                'tanggal_evaluasi' => $this->teks('tanggal_evaluasi') ?: date('Y-m-d'),
                'triwulan'         => (int) $this->input->post('triwulan', true),
                'id_analisa'       => $analisa->id_analisa,
            ];

            $this->Model_mri_evaluasi->simpan($r->id_register, $d);

            $pesan = 'Evaluasi <strong>' . html_escape($r->kode_risiko) . '</strong> disimpan dengan keputusan '
                . '<strong>' . html_escape($keputusan) . '</strong> (skor residual ' . $nilaiResidual . ').';
            if ($override) {
                $pesan .= ' Keputusan berbeda dari rekomendasi band <strong>' . html_escape($band)
                    . '</strong> — masuk antrean <strong>Perlu Review</strong>.';
            }
            $this->pesan($pesan, $override ? 'warning' : 'success');
            // Setelah simpan, kembali ke daftar Evaluasi (bukan detail Register)
            // agar hasilnya langsung terlihat pada tabel beserta status review-nya.
            redirect('manrisk/evaluasi?tahun=' . (int) $r->tahun);
            return;
        }

        $this->render('manrisk/view_evaluasi_form', [
            'title'      => 'EVALUASI ' . $r->kode_risiko,
            'risiko'     => $r,
            'analisa'    => $analisa,
            'evaluasi'   => $this->Model_mri_evaluasi->getByRegister($r->id_register),
            'riwayat'    => $this->Model_mri_evaluasi->getRiwayatEvaluasi($r->id_register),
            'tingkat'    => $this->Model_mri_master->getTingkat(),
            'appetite'   => $this->Model_mri_pengaturan->getAppetite((int) $r->tahun),
            'band'       => $band,
            'skala_teks' => $this->ringkasSkala(),
        ]);
    }

    // ------------------------------------------------------------------
    // REVIEW KEPUTUSAN (override band) — N7
    // ------------------------------------------------------------------
    public function review($id_evaluasi = null)
    {
        $e = $id_evaluasi ? $this->Model_mri_evaluasi->getById($id_evaluasi) : null;
        if (!$e) {
            $this->pesan('Evaluasi tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        $r = $this->Model_mri_register->getById($e->id_register);
        if (!$r || !$this->tolakBilaBukanUnit($r->id_upk, $this->tujuan)) {
            return;
        }
        if (!$this->bolehReview()) {
            $this->pesan('<strong>Maaf,</strong> peninjauan override hanya untuk Administrator &amp; Auditor.', 'danger');
            redirect($this->tujuan);
            return;
        }

        if ($this->diposting()) {
            $catatan = $this->teks('catatan_review');
            if ($catatan === '') {
                $this->pesan('Catatan review wajib diisi.', 'warning');
                redirect('manrisk/evaluasi/review/' . (int) $e->id_evaluasi);
                return;
            }
            $this->Model_mri_evaluasi->tandaiDitinjau(
                (int) $e->id_evaluasi,
                $catatan,
                $this->session->userdata('nama_pengguna') ?: 'sistem'
            );
            $this->pesan('Evaluasi <strong>' . html_escape($r->kode_risiko) . '</strong> ditandai sudah ditinjau.');
            redirect($this->tujuan . '?review=PERLU_REVIEW');
            return;
        }

        $this->render('manrisk/view_evaluasi_review', [
            'title'     => 'REVIEW EVALUASI ' . $r->kode_risiko,
            'risiko'    => $r,
            'evaluasi'  => $e,
            'tingkat'   => $this->Model_mri_master->getTingkat(),
        ]);
    }

    /** Hanya Administrator & Auditor yang boleh menandai review. */
    private function bolehReview()
    {
        return in_array($this->bagian(), ['Administrator', 'Auditor'], true);
    }

    /** Ringkasan skala band aktif, mis. "Rendah 1-10; Sedang 11-15; Tinggi 16-25". */
    private function ringkasSkala()
    {
        $parts = [];
        foreach ($this->Model_mri_master->getTingkat() as $t) {
            $parts[] = $t->nama_tingkat . ' ' . (int) $t->skor_min . '-' . (int) $t->skor_max;
        }
        return implode('; ', $parts);
    }
}
