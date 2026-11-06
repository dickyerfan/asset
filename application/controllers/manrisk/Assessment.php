<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Risk Assessment — rekap Identifikasi, Analisis, Evaluasi, dan Perlakuan
 * dalam satu tabel (contoh CRMO Perpamsi halaman 37).
 *
 * Halaman ini hanya-baca: tidak membuat/mengubah data. Semua angka diambil
 * dari Register, Analisis, Evaluasi, dan Perlakuan agar tidak ada duplikasi.
 */
class Assessment extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_assessment');
        $this->load->model('Model_mri_master');
    }

    public function index()
    {
        $data = $this->kumpulkan();
        $data['title'] = 'RISK ASSESSMENT';
        $this->render('manrisk/view_assessment', $data);
    }

    /** Tampilan cetak / simpan PDF (tanpa sidebar). */
    public function cetak()
    {
        $this->load->view('manrisk/cetak_assessment', $this->kumpulkan());
    }

    /** Ekspor CSV yang dapat dibuka langsung di Excel. */
    public function ekspor()
    {
        $data = $this->kumpulkan();

        $nama = 'risk_assessment_' . (int) $data['tahun'];
        if (!empty($data['filter']['id_upk'])) {
            $nama .= '_unit_' . (int) $data['filter']['id_upk'];
        }
        $nama .= '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $nama . '"');
        header('Cache-Control: no-store, no-cache, must-revalidate');

        $out = fopen('php://output', 'w');
        // BOM agar huruf beraksen tampil benar di Excel.
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, [
            'NO',
            'NO. ID RISIKO',
            'SASARAN/KPI',
            'RISIKO',
            'SUMBER',
            'PENYEBAB',
            'DAMPAK',
            'RENCANA PENGENDALIAN & PENANGANAN',
            'DESKRIPSI BIAYA MITIGASI',
            'PIC',
            'K',
            'D',
            'NILAI',
            'TINGKAT RISIKO',
            'KEPUTUSAN',
        ]);

        $no = 0;
        foreach ($data['daftar'] as $r) {
            $no++;
            fputcsv($out, [
                $no,
                $r->kode_risiko,
                $this->gabung($r->sasaran_kpi, $r->objektif),
                $r->risiko,
                $r->sumber_risiko,
                $r->penyebab,
                $r->dampak,
                $this->gabung($r->kontrol_uraian, $r->uraian_tindakan),
                $r->anggaran_biaya !== null ? (float) $r->anggaran_biaya : '',
                $this->gabung($r->nama_pic, $r->jabatan_pic),
                $r->inherent_l,
                $r->inherent_d,
                $r->nilai_inherent,
                $r->level_inherent,
                $r->keputusan,
            ]);
        }

        fclose($out);
        exit;
    }

    /** Kumpulkan filter + data untuk index, cetak, dan ekspor. */
    private function kumpulkan()
    {
        $f = $this->filter();
        $daftar = $this->Model_mri_assessment->getRekap($f);

        $totalBiaya = 0.0;
        foreach ($daftar as $r) {
            if ($r->anggaran_biaya !== null) {
                $totalBiaya += (float) $r->anggaran_biaya;
            }
        }

        $unitList = $this->db->from('bagian_upk')
            ->order_by('id_bagian', 'ASC')
            ->get()->result();

        $namaUnit = 'Semua Unit';
        if (!empty($f['id_upk'])) {
            foreach ($unitList as $u) {
                if ((int) $u->id_bagian === (int) $f['id_upk']) {
                    $namaUnit = $u->nama_bagian;
                    break;
                }
            }
        }

        return [
            'tahun'       => $f['tahun'],
            'filter'      => $f,
            'daftar'      => $daftar,
            'total_biaya' => $totalBiaya,
            'unit_list'   => $unitList,
            'nama_unit'   => $namaUnit,
            'tingkat'     => $this->Model_mri_master->getTingkat(),
            'akses_semua' => $this->aksesSemuaUnit(),
            'tanpa_unit'  => !$this->aksesSemuaUnit() && $this->unitPaksa() === null,
        ];
    }

    /** Filter halaman: tahun, unit, tingkat, dan kata kunci. */
    private function filter()
    {
        $f = ['tahun' => $this->tahunAktif(2027)];

        if (!$this->aksesSemuaUnit()) {
            $unit = $this->unitPaksa();
            if ($unit !== null) {
                $f['id_upk'] = (int) $unit;
            }
        } elseif ($this->angka('id_upk')) {
            $f['id_upk'] = $this->angka('id_upk');
        }

        $level = $this->teks('level');
        if ($level !== '') {
            $f['level'] = $level;
        }

        $q = $this->teks('q');
        if ($q !== '') {
            $f['q'] = $q;
        }

        return $f;
    }

    /** Gabungkan dua teks tanpa pemisah ganda. */
    private function gabung($a, $b)
    {
        $a = trim((string) $a);
        $b = trim((string) $b);
        if ($a === '') {
            return $b;
        }
        if ($b === '' || strpos($a, $b) !== false) {
            return $a;
        }
        return $a . '; ' . $b;
    }
}
