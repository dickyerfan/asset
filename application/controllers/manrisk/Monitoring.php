<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Monitoring & review risiko (ISO 31000:2018 Klausa 6.7) — memantau apakah
 * kontrol masih efektif, skor berubah, dan apakah risiko perlu dievaluasi
 * ulang pada triwulan berikutnya.
 */
class Monitoring extends MY_Controller
{
    private $tujuan = 'manrisk/monitoring';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_register');
        $this->load->model('Model_mri_analisa');
        $this->load->model('Model_mri_perlakuan');
        $this->load->model('Model_mri_pengaturan');
        $this->load->model('Model_mri_master');
        $this->load->library('form_validation');
    }

    // ------------------------------------------------------------------
    // DAFTAR CATATAN MONITORING
    // ------------------------------------------------------------------
    public function index($id_register = null)
    {
        if ($id_register !== null) {
            $this->form($id_register);
            return;
        }

        $tahun = $this->tahunAktif(2027);
        $f = ['tahun' => $tahun];
        if (!$this->aksesSemuaUnit()) {
            $f['id_upk'] = $this->unitPaksa();
        } elseif ($this->angka('id_upk')) {
            $f['id_upk'] = $this->angka('id_upk');
        }

        $risiko = $this->Model_mri_register->getListDenganSkor($f);
        $baris  = [];
        foreach ($risiko as $r) {
            $terakhir = $this->Model_mri_perlakuan->getMonitorTerakhir($r->id_register);
            $baris[] = (object) [
                'risiko'    => $r,
                'terakhir'  => $terakhir,
                'riwayat'   => $this->Model_mri_perlakuan->getMonitorByRegister($r->id_register),
            ];
        }

        $this->render('manrisk/view_monitoring', [
            'title'       => 'MONITORING RISIKO',
            'tahun'       => $tahun,
            'filter'      => $f,
            'baris'       => $baris,
            'unit_list'   => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
            'akses_semua' => $this->aksesSemuaUnit(),
            'boleh_tulis' => $this->Model_mri_pengaturan->bolehUbah('monitoring_risiko'),
        ]);
    }

    // ------------------------------------------------------------------
    // FORMULIR CATATAN MONITORING
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
        if (!$this->Model_mri_pengaturan->bolehUbah('monitoring_risiko')) {
            $this->pesan('Monitoring risiko sedang dikunci. Buka kunci pada Pengaturan terlebih dahulu.', 'warning');
            redirect($this->tujuan);
            return;
        }

        $aturan = [
            ['triwulan', 'Triwulan', 'required|integer|greater_than_equal_to[1]|less_than_equal_to[4]'],
            ['hasil_monitoring', 'Hasil Monitoring', 'trim'],
            ['kontrol_masih_efektif', 'Kontrol Masih Efektif', 'in_list[YA,TIDAK,SEBAGIAN]'],
            // Skor Baru diisi lewat L dan D (1-5) agar selalu hasil L x D.
            ['baru_l', 'L Skor Baru', 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]'],
            ['baru_d', 'D Skor Baru', 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]'],
        ];
        foreach ($aturan as $a) {
            $this->form_validation->set_rules($a[0], $a[1], $a[2]);
        }
        $this->form_validation->set_message('greater_than_equal_to', '%s minimal %s');
        $this->form_validation->set_message('less_than_equal_to', '%s maksimal %s');
        $this->form_validation->set_message('in_list', '%s tidak valid');
        $this->form_validation->set_message('required', '%s masih kosong');

        // Catatan yang akan diubah (bila ada).
        $edit = null;
        if ($this->angka('id_monitoring')) {
            $edit = $this->Model_mri_perlakuan->getMonitorById($this->angka('id_monitoring'));
            if (!$edit || (int) $edit->id_register !== (int) $r->id_register) {
                $this->pesan('Catatan monitoring tidak ditemukan.', 'warning');
                redirect('manrisk/monitoring');
                return;
            }
        }

        $analisa = $this->Model_mri_analisa->getByRegister($r->id_register);
        $riwayat = $this->Model_mri_perlakuan->getMonitorByRegister($r->id_register);
        // Skor Lama dikunci; Skor Baru diisi lewat L x D (lihat skorMonitoring()).
        $skor    = $this->skorMonitoring(
            $analisa,
            $riwayat,
            $edit,
            $this->diposting() ? $this->angka('baru_l') : null,
            $this->diposting() ? $this->angka('baru_d') : null
        );

        // Sinkronisasi ke Analisa hanya dari catatan terbaru, bukan koreksi catatan lama.
        $catatanTerbaru = isset($riwayat[0]) ? $riwayat[0] : null;
        $analisaTerbuka = $this->Model_mri_pengaturan->bolehUbah('analisa_risiko');
        $bolehSinkron   = $analisaTerbuka
            && $analisa
            && $skor['baru'] !== null
            && $skor['l'] !== null
            && $skor['d'] !== null
            && (!$edit || ($catatanTerbaru && (int) $edit->id_monitoring === (int) $catatanTerbaru->id_monitoring));

        // Default: centang bila Skor Baru memang berbeda dari residual Analisa.
        $sinkronDefault = $this->diposting()
            ? ($this->teks('sinkron_analisa') === 'YA')
            : ($bolehSinkron && (int) $skor['baru'] !== (int) $skor['residual']);

        if ($this->diposting() && $this->form_validation->run() === true) {
            $d = [
                'id_register'         => $r->id_register,
                'tahun'               => (int) $this->input->post('tahun', true) ?: $this->tahunAktif(2027),
                'triwulan'            => (int) $this->input->post('triwulan', true),
                'status_tindakan'     => $this->teks('status_tindakan'),
                'hasil_monitoring'    => $this->teks('hasil_monitoring'),
                'kontrol_masih_efektif' => $this->teks('kontrol_masih_efektif') ?: 'YA',
                // nilai_lama/perubahan tidak pernah diambil dari POST.
                // nilai_baru = hasil L x D yang dipilih di form.
                'nilai_lama'          => $skor['lama'],
                'nilai_baru'          => $skor['baru'],
                'perubahan'           => $skor['perubahan'],
                'sebab_perubahan'     => $this->teks('sebab_perubahan'),
                'perlu_reassess'      => $this->teks('perlu_reassess') === 'YA',
                'tanggal_review'      => $this->teks('tanggal_review') ?: date('Y-m-d'),
            ];

            $id = $this->input->post('id_monitoring', true);
            $simpan = $this->Model_mri_perlakuan->simpanMonitor($d, $id ? (int) $id : null);

            $pesan = $simpan
                ? 'Catatan monitoring <strong>' . html_escape($r->kode_risiko) . '</strong> disimpan.'
                : 'Catatan monitoring <strong>' . html_escape($r->kode_risiko) . '</strong> gagal disimpan &mdash; '
                    . 'kemungkinan TW tersebut sudah punya catatan. Periksa riwayat di bawah.';
            if ($simpan && $bolehSinkron && $this->teks('sinkron_analisa') === 'YA') {
                // Sinkronkan Skor Baru menjadi residual resmi di Analisa.
                $levelSebelum = $analisa->level_residual;
                $ok = $this->Model_mri_analisa->perbaruiResidual($r->id_register, $skor['l'], $skor['d']);
                if ($ok) {
                    $t = $this->Model_mri_master->levelDariSkor($skor['baru']);
                    $levelSesudah = $t ? $t->nama_tingkat : null;
                    $pesan .= ' Residual di <strong>Analisa</strong> diperbarui menjadi '
                        . (int) $skor['l'] . ' &times; ' . (int) $skor['d'] . ' = <strong>' . (int) $skor['baru'] . '</strong>.';
                    if ($levelSesudah && $levelSesudah !== $levelSebelum) {
                        $pesan .= ' Level berubah dari <strong>' . html_escape($levelSebelum ?: '-') . '</strong> ke <strong>'
                            . html_escape($levelSesudah) . '</strong> &mdash; tinjau <strong>Evaluasi</strong>.';
                    }
                } else {
                    $pesan .= ' Sinkronisasi ke Analisa dilewati karena data Analisa belum ada.';
                }
            }

            // Flashdata khusus tanpa kelas .alert agar tidak dihapus templates/footer.php.
            $this->session->set_flashdata(
                'info_monitoring',
                '<div class="border ' . ($simpan ? 'border-success' : 'border-danger') . ' rounded bg-white p-2 mb-3">'
                . $pesan . '</div>'
            );
            // Kembali ke daftar Monitoring pada tahun catatan, bukan ke Register.
            redirect('manrisk/monitoring?tahun=' . (int) $d['tahun']);
            return;
        }

        $this->render('manrisk/view_monitoring_form', [
            'title'     => 'MONITORING ' . $r->kode_risiko,
            'risiko'    => $r,
            'catatan'   => $edit,
            'riwayat'   => $riwayat,
            'analisa'   => $analisa,
            'skor'      => $skor,
            'tingkat'   => $this->Model_mri_master->getTingkat(),
            'boleh_sinkron'  => $bolehSinkron,
            'sinkron_default' => $sinkronDefault,
            'analisa_terbuka' => $analisaTerbuka,
        ]);
    }

    // ------------------------------------------------------------------
    // SKOR MONITORING
    // ------------------------------------------------------------------
    /**
     * Skor Lama dikunci (snapshot), Skor Baru diisi lewat L x D.
     *
     * - Skor Lama : catatan baru  = skor catatan monitoring terakhir;
     *               catatan lama  = nilai tersimpan (histori utuh).
     * - Skor Baru : L dan D pilihan user (1-5), skor = L x D. Defaultnya
     *               mengikuti residual Analisa; pada catatan lama dipakai
     *               pasangan faktor dari skor tersimpan.
     * Arah perubahan selalu dihitung dari kedua skor.
     *
     * @return array{lama: int|null, baru: int|null, l: int|null, d: int|null, perubahan: string|null, residual: int|null}
     */
    private function skorMonitoring($analisa, $riwayat, $edit, $postL = null, $postD = null)
    {
        $residual = ($analisa && $analisa->nilai_residual !== null && $analisa->nilai_residual !== '')
            ? (int) $analisa->nilai_residual
            : null;
        $resL = ($analisa && $analisa->residual_l !== null && $analisa->residual_l !== '')
            ? (int) $analisa->residual_l
            : null;
        $resD = ($analisa && $analisa->residual_d !== null && $analisa->residual_d !== '')
            ? (int) $analisa->residual_d
            : null;

        if ($edit) {
            $lama = ($edit->nilai_lama !== null && $edit->nilai_lama !== '') ? (int) $edit->nilai_lama : $residual;
            $baru = ($edit->nilai_baru !== null && $edit->nilai_baru !== '') ? (int) $edit->nilai_baru : $residual;

            if ($baru !== null && $baru === $residual && $resL !== null && $resD !== null) {
                list($l, $d) = [$resL, $resD];
            } else {
                list($l, $d) = $this->faktorSkor($baru);
            }
        } else {
            // Catatan baru: skor lama = catatan terakhir yang punya nilai.
            $lama = null;
            foreach ($riwayat as $h) {
                if ($h->nilai_baru !== null && $h->nilai_baru !== '') {
                    $lama = (int) $h->nilai_baru;
                    break;
                }
            }
            if ($lama === null) {
                $lama = $residual;
            }

            $baru = $residual;
            if ($resL !== null && $resD !== null) {
                list($l, $d) = [$resL, $resD];
            } else {
                list($l, $d) = $this->faktorSkor($baru);
            }
        }

        // Pilihan L/D di form menentukan Skor Baru (sudah divalidasi 1-5).
        if ($postL !== null && $postD !== null && $postL >= 1 && $postL <= 5 && $postD >= 1 && $postD <= 5) {
            $l    = (int) $postL;
            $d    = (int) $postD;
            $baru = $l * $d;
        }

        return [
            'lama'      => $lama,
            'baru'      => $baru,
            'l'         => $l,
            'd'         => $d,
            'perubahan' => $this->arahPerubahan($lama, $baru),
            'residual'  => $residual,
        ];
    }

    /**
     * Pecah skor menjadi pasangan L x D (1-5) paling seimbang.
     * Dipakai saat mengubah catatan lama yang hanya menyimpan skor.
     */
    private function faktorSkor($nilai)
    {
        if ($nilai === null || (int) $nilai < 1) {
            return [null, null];
        }
        $nilai   = (int) $nilai;
        $pilih   = [null, null];
        $selisih = 99;
        for ($l = 1; $l <= 5; $l++) {
            if ($nilai % $l !== 0) {
                continue;
            }
            $d = (int) ($nilai / $l);
            if ($d < 1 || $d > 5) {
                continue;
            }
            if (abs($l - $d) < $selisih) {
                $selisih = abs($l - $d);
                $pilih   = [$l, $d];
            }
        }
        return $pilih;
    }

    private function arahPerubahan($lama, $baru)
    {
        if ($lama === null || $baru === null) {
            return null;
        }
        if ($baru > $lama) {
            return 'Naik';
        }
        if ($baru < $lama) {
            return 'Turun';
        }
        return 'Tetap';
    }

    // ------------------------------------------------------------------
    // HAPUS CATATAN (monitoring salah catat boleh dihapus)
    // ------------------------------------------------------------------
    public function hapus($id = null)
    {
        $m = $this->Model_mri_perlakuan->getMonitorById($id);
        if (!$m) {
            $this->pesan('Catatan monitoring tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        $r = $this->Model_mri_register->getById($m->id_register);
        if (!$r || !$this->tolakBilaBukanUnit($r->id_upk, $this->tujuan)) {
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('monitoring_risiko')) {
            $this->pesan('Monitoring risiko sedang dikunci.', 'warning');
            redirect($this->tujuan);
            return;
        }

        $this->Model_mri_perlakuan->deleteMonitor($id);
        $this->pesan('Catatan monitoring <strong>' . html_escape($r->kode_risiko) . '</strong> dihapus.');
        redirect($this->tujuan);
    }
}
