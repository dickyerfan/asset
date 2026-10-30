<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Objektif RKAP 2027 & rekonsiliasi (ISO 31000 Klausa 6.3 — sasaran organisasi).
 *
 * Objektif adalah acuan "kenapa risiko ini penting"; status rekonsiliasi
 * menandai apakah keterkaitan risiko -> objektif sudah dikonfirmasi.
 */
class Objektif extends MY_Controller
{
    private $tujuan = 'manrisk/objektif';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_lingkup');
        $this->load->model('Model_mri_register');
        $this->load->model('Model_mri_pengaturan');
    }

    public function index()
    {
        $tahun = $this->tahunAktif(2027);
        $f = ['tahun' => $tahun];
        if (!$this->aksesSemuaUnit()) {
            $f['id_upk'] = $this->unitPaksa();
        }

        $daftar = $this->Model_mri_lingkup->getObjektif(
            $tahun,
            null,
            $this->teks('status_rekonsiliasi') !== '' ? $this->teks('status_rekonsiliasi') : null
        );
        if (!$this->aksesSemuaUnit()) {
            $paks = $this->unitPaksa();
            $daftar = array_values(array_filter($daftar, function ($o) use ($paks) {
                return (int) $o->id_upk === (int) $paks || $o->id_upk === null;
            }));
        }

        // jumlah risiko terikat per objektif
        $terikat = [];
        foreach ($this->db->select('id_objektif, COUNT(*) AS jml', false)
            ->group_by('id_objektif')
            ->get('mri_risiko_objektif')->result() as $row) {
            $terikat[(int) $row->id_objektif] = (int) $row->jml;
        }

        $rekap = $this->Model_mri_lingkup->rekapRekonsiliasi($tahun);

        $this->render('manrisk/view_objektif', [
            'title'       => 'OBJEKTIF RKAP',
            'tahun'       => $tahun,
            'filter'      => $f,
            'daftar'      => $daftar,
            'terikat'     => $terikat,
            'rekap'       => $rekap,
            'lingkup_list' => $this->Model_mri_lingkup->getLingkup($tahun),
            'akses_semua' => $this->aksesSemuaUnit(),
            'boleh_tulis' => $this->Model_mri_pengaturan->bolehUbah('objektif_risiko'),
        ]);
    }

    /** Rekonsiliasi satu objektif (POST). */
    public function rekonsiliasi($id = null)
    {
        if (!$this->diposting()) {
            redirect($this->tujuan);
            return;
        }
        $o = $this->Model_mri_lingkup->getObjektifById($id);
        if (!$o) {
            $this->pesan('Objektif tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->aksesSemuaUnit() && $o->id_upk !== null && (int) $o->id_upk !== (int) $this->unitPaksa()) {
            $this->pesan('Objektif berada di luar unit Anda.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('objektif_risiko')) {
            $this->pesan('Objektif RKAP sedang dikunci.', 'warning');
            redirect($this->tujuan);
            return;
        }

        $nilai = $this->teks('nilai_realisasi');
        if ($nilai === '') {
            $this->pesan('Nilai realisasi wajib diisi untuk merekonsiliasi.', 'warning');
            redirect($this->tujuan);
            return;
        }

        $status = $this->teks('status_rekonsiliasi');
        if (!in_array($status, ['BELUM', 'SELESAI'], true)) {
            $status = $o->status_rekonsiliasi;
        }

        $ok = $this->Model_mri_lingkup->rekonsiliasiObjektif($id, $nilai, $this->teks('alasan_rekonsiliasi'), $status);
        $this->pesan(
            $ok
                ? 'Objektif <strong>' . html_escape($o->indikator) . '</strong> ditandai <strong>' . html_escape($status) . '</strong>.'
                : 'Gagal menyimpan rekonsiliasi objektif.',
            $ok ? 'success' : 'danger'
        );
        redirect($this->tujuan);
    }

    /** Form tambah objektif baru (GET). */
    public function tambah()
    {
        if (!$this->kunciTerbuka()) {
            return;
        }
        $this->renderForm(null);
    }

    /** Form ubah objektif (GET). */
    public function ubah($id = null)
    {
        if (!$this->kunciTerbuka()) {
            return;
        }

        $o = $this->Model_mri_lingkup->getObjektifById($id);
        if (!$o) {
            $this->pesan('Objektif tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->unitBoleh($o)) {
            $this->pesan('Objektif berada di luar unit Anda.', 'warning');
            redirect($this->tujuan);
            return;
        }

        $this->renderForm($o);
    }

    /**
     * Simpan objektif: tambah baru bila id_objektif kosong, ubah bila terisi.
     * Perubahan nilai RKAP dicatat ke mri_objektif_revisi sebagai jejak audit.
     */
    public function simpan()
    {
        if (!$this->diposting()) {
            redirect($this->tujuan);
            return;
        }
        if (!$this->kunciTerbuka()) {
            return;
        }

        $id     = $this->angka('id_objektif');
        $tujuan = $id ? 'manrisk/objektif/ubah/' . $id : 'manrisk/objektif/tambah';

        $lama = null;
        if ($id) {
            $lama = $this->Model_mri_lingkup->getObjektifById($id);
            if (!$lama) {
                $this->pesan('Objektif tidak ditemukan.', 'warning');
                redirect($this->tujuan);
                return;
            }
            if (!$this->unitBoleh($lama)) {
                $this->pesan('Objektif berada di luar unit Anda.', 'warning');
                redirect($this->tujuan);
                return;
            }
        }

        $indikator = $this->teks('indikator');
        if ($indikator === '') {
            $this->pesan('Indikator objektif wajib diisi.', 'warning');
            redirect($tujuan);
            return;
        }

        $target = $this->teks('nilai_rkap');
        $target = $target === '' ? null : $this->angkaDesimal($target);
        $baseline = $this->teks('baseline_2026');
        $baseline = $baseline === '' ? null : $this->angkaDesimal($baseline);
        if (($this->teks('nilai_rkap') !== '' && $target === null)
            || ($this->teks('baseline_2026') !== '' && $baseline === null)
        ) {
            $this->pesan('Target RKAP / Baseline harus berupa angka yang valid.', 'warning');
            redirect($tujuan);
            return;
        }

        $status = $this->teks('status_rekonsiliasi');

        $data = [
            'tahun'                => (int) $this->teks('tahun') ?: $this->tahunAktif(2027),
            'indikator'            => $indikator,
            'nilai_rkap'           => $target,
            'baseline_2026'        => $baseline,
            'satuan'               => $this->teks('satuan') ?: null,
            'sumber_nilai'         => $this->teks('sumber_nilai') ?: null,
            'id_bagian'            => $this->angka('id_bagian'),
            'id_proses'            => $this->angka('id_proses'),
            'id_lingkup'           => $this->angka('id_lingkup'),
            'status_rekonsiliasi'  => in_array($status, ['BELUM', 'SELESAI'], true) ? $status : 'BELUM',
            'catatan_rekonsiliasi' => $this->teks('catatan_rekonsiliasi') ?: null,
            'status'               => 1,
            'id_upk'               => $this->aksesSemuaUnit() ? $this->angka('id_upk') : $this->unitPaksa(),
        ];

        $ok = $this->Model_mri_lingkup->simpanObjektif($id, $data);

        if ($ok && $lama) {
            $this->Model_mri_lingkup->catatRevisi(
                $id,
                $lama->nilai_rkap,
                $target,
                $this->teks('alasan_ubah')
            );
        }

        $this->pesan(
            $ok
                ? 'Objektif <strong>' . html_escape($indikator) . '</strong> '
                  . ($id ? 'berhasil diubah.' : 'berhasil ditambahkan.')
                : 'Gagal menyimpan objektif.',
            $ok ? 'success' : 'danger'
        );
        redirect($this->tujuan);
    }

    /** Render form tambah / ubah objektif. */
    private function renderForm($o)
    {
        $tahun = $o ? (int) $o->tahun : $this->tahunAktif(2027);

        $this->render('manrisk/view_objektif_tambah', [
            'title'        => $o ? 'UBAH OBJEKTF RKAP' : 'TAMBAH OBJEKTF RKAP',
            'objektif'     => $o,
            'tahun'        => $tahun,
            'unit_list'    => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
            'proses_list'  => $this->db->from('mri_proses')->order_by('kode_proses', 'ASC')->get()->result(),
            'lingkup_list' => $this->Model_mri_lingkup->getLingkup($tahun),
            'boleh_tulis'  => true,
        ]);
    }

    /** @return bool false bila terkunci (sudah mengirim pesan & redirect). */
    private function kunciTerbuka()
    {
        if ($this->Model_mri_pengaturan->bolehUbah('objektif_risiko')) {
            return true;
        }
        $this->pesan('Objektif RKAP sedang dikunci.', 'warning');
        redirect($this->tujuan);
        return false;
    }

    /** @return bool true bila objektif boleh disentuh unit pengguna. */
    private function unitBoleh($o)
    {
        if ($this->aksesSemuaUnit()) {
            return true;
        }
        return $o->id_upk === null || (int) $o->id_upk === (int) $this->unitPaksa();
    }

    /**
     * Ubah input angka pengguna menjadi string desimal untuk kolom DECIMAL.
     * Mendukung format Indonesia ("10.613.952.106" / "157.800.000," / "500.000")
     * maupun format titik desimal ("8964652306.00").
     *
     * @return string|null null bila bukan angka
     */
    private function angkaDesimal($nilai)
    {
        $nilai = str_replace([' ', "\t"], '', (string) $nilai);
        $titik = strrpos($nilai, '.');
        $koma  = strrpos($nilai, ',');

        if (substr_count($nilai, '.') > 1 && $koma === false) {
            // "10.613.952.106" -> pemisah ribuan
            $nilai = str_replace('.', '', $nilai);
        } elseif ($koma !== false && ($titik === false || $koma > $titik)) {
            // "1.500.000,50" -> format Indonesia
            $nilai = str_replace('.', '', $nilai);
            $nilai = str_replace(',', '.', $nilai);
        } elseif ($koma === false && substr_count($nilai, '.') === 1
            && preg_match('/^\d{1,3}\.\d{3}$/', $nilai)
        ) {
            // "500.000" / "1.000" -> ribuan, bukan desimal
            $nilai = str_replace('.', '', $nilai);
        } else {
            $nilai = str_replace(',', '', $nilai);
        }

        return is_numeric($nilai) ? $nilai : null;
    }
}
