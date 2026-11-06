<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Data agregat untuk dashboard manrisk.
 *
 * Semua perhitungan mengambil dari tabel mri_* saja.
 * Tabel legacy mr_* sengaja TIDAK dipakai agar modul baru terisolasi;
 * bukti historis 2025-2026 diambil terpisah oleh Model_mri_bukti_legacy.
 */
class Model_mri_dashboard extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_mri_register');
        $this->load->model('Model_mri_master');
    }

    /**
     * Susun seluruh data dashboard untuk satu tahun.
     *
     * @param int   $tahun
     * @param array $f filter tambahan (id_upk, id_kategori, tipe_risiko, q)
     * @return array
     */
    public function rangkum($tahun, $f = [])
    {
        $dasar = $f;
        $dasar['tahun'] = $tahun;

        $tingkat = $this->Model_mri_master->getTingkat();

        $daftar = $this->Model_mri_register->getListDenganSkor($dasar);

        // sebaran level inherent / residual
        $inherent = [];
        $residual = [];
        foreach ($tingkat as $t) {
            $inherent[$t->nama_tingkat] = 0;
            $residual[$t->nama_tingkat] = 0;
        }
        $tanpaAnalisa = 0;
        foreach ($daftar as $r) {
            if ($r->level_inherent === null) {
                $tanpaAnalisa++;
                continue;
            }
            if (isset($inherent[$r->level_inherent])) {
                $inherent[$r->level_inherent]++;
            }
            if ($r->level_residual !== null && isset($residual[$r->level_residual])) {
                $residual[$r->level_residual]++;
            }
        }

        // sebaran per unit, dihitung per band dari mri_tingkat_risiko
        $bandKosong = [];
        foreach ($tingkat as $t) {
            $bandKosong[$t->nama_tingkat] = 0;
        }
        $perUnit = [];
        foreach ($daftar as $r) {
            $kunci = $r->id_upk;
            if (!isset($perUnit[$kunci])) {
                $perUnit[$kunci] = [
                    'id_upk' => $kunci,
                    'nama'   => $r->nama_bagian ?: 'Tanpa unit',
                    'total'  => 0,
                    'band'   => $bandKosong,
                ];
            }
            $perUnit[$kunci]['total']++;
            if ($r->level_inherent !== null && isset($perUnit[$kunci]['band'][$r->level_inherent])) {
                $perUnit[$kunci]['band'][$r->level_inherent]++;
            }
        }
        uasort($perUnit, function ($a, $b) {
            return $b['total'] - $a['total'];
        });

        // sebaran per kategori
        $perKategori = [];
        foreach ($daftar as $r) {
            $kunci = $r->id_kategori;
            if (!isset($perKategori[$kunci])) {
                $perKategori[$kunci] = [
                    'id_kategori' => $kunci,
                    'kode'        => $r->kode_kat,
                    'nama'        => $r->nama_kat,
                    'total'       => 0,
                ];
            }
            $perKategori[$kunci]['total']++;
        }
        uasort($perKategori, function ($a, $b) {
            return $b['total'] - $a['total'];
        });

        // tipe risiko (Ancaman vs Peluang) — ISO 6.4.2
        $tipe = ['Ancaman' => 0, 'Peluang' => 0];
        foreach ($daftar as $r) {
            if (isset($tipe[$r->tipe_risiko])) {
                $tipe[$r->tipe_risiko]++;
            }
        }

        // progres perlakuan
        $this->db->select('p.status_laksana, COUNT(*) AS jml', false);
        $this->db->from('mri_perlakuan p');
        $this->db->join('mri_register r', 'r.id_register = p.id_register', 'left');
        $this->db->where('r.tahun', $tahun);
        if (!empty($f['id_upk'])) {
            $this->db->where('r.id_upk', $f['id_upk']);
        }
        $this->db->group_by('p.status_laksana');
        $progres = ['BELUM' => 0, 'DALAM_PROSES' => 0, 'SELESAI' => 0];
        foreach ($this->db->get()->result() as $r) {
            $progres[$r->status_laksana] = (int) $r->jml;
        }

        // keputusan evaluasi
        $this->db->select('e.keputusan, COUNT(*) AS jml', false);
        $this->db->from('mri_evaluasi e');
        $this->db->join('mri_register r', 'r.id_register = e.id_register', 'left');
        $this->db->where('r.tahun', $tahun);
        if (!empty($f['id_upk'])) {
            $this->db->where('r.id_upk', $f['id_upk']);
        }
        $this->db->group_by('e.keputusan');
        $keputusan = ['Accept' => 0, 'Monitor' => 0, 'Treat' => 0, 'Escalate' => 0];
        foreach ($this->db->get()->result() as $r) {
            $keputusan[$r->keputusan] = (int) $r->jml;
        }

        // rekonsiliasi objektif
        $this->db->select('status_rekonsiliasi, COUNT(*) AS jml', false);
        $this->db->where('tahun', $tahun);
        $this->db->group_by('status_rekonsiliasi');
        $rekonsiliasi = ['SELESAI' => 0, 'BELUM' => 0];
        foreach ($this->db->get('mri_objektif')->result() as $r) {
            $rekonsiliasi[$r->status_rekonsiliasi] = (int) $r->jml;
        }

        // monitoring tahun ini
        $this->db->from('mri_monitoring m');
        $this->db->join('mri_register r', 'r.id_register = m.id_register');
        $this->db->where('r.tahun', $tahun);
        if (!empty($f['id_upk'])) {
            $this->db->where('r.id_upk', $f['id_upk']);
        }
        $monitoring = (int) $this->db->count_all_results();

        // daftar risiko prioritas: skor residual tertinggi
        $prioritas = [];
        foreach ($daftar as $r) {
            if ($r->nilai_residual !== null) {
                $prioritas[] = $r;
            }
        }
        usort($prioritas, function ($a, $b) {
            return $b->nilai_residual - $a->nilai_residual;
        });
        $prioritas = array_slice($prioritas, 0, 10);

        // eskalasi
        $this->db
            ->select('r.kode_risiko, r.risiko, r.sasaran_kpi, e.eskalasi_ke, a.nilai_residual, a.level_residual')
            ->join('mri_register r', 'r.id_register = e.id_register', 'left')
            ->join('mri_analisa a', 'a.id_register = e.id_register', 'left')
            ->where('r.tahun', $tahun)
            ->where('e.perlu_eskalasi', 1);
        if (!empty($f['id_upk'])) {
            $this->db->where('r.id_upk', $f['id_upk']);
        }
        $eskalasi = $this->db
            ->order_by('a.nilai_residual', 'DESC')
            ->get('mri_evaluasi e')
            ->result();

        // daftar tahun yang tersedia untuk filter
        $tahun_list = [];
        foreach ($this->db->select('tahun')->distinct()->order_by('tahun', 'DESC')->get('mri_register')->result() as $t) {
            $tahun_list[] = $t->tahun;
        }

        return [
            'tahun'          => (int) $tahun,
            'filter'         => $f,
            'tingkat'        => $tingkat,
            'total_risiko'   => count($daftar),
            'tanpa_analisa'  => $tanpaAnalisa,
            'level_inherent' => $inherent,
            'level_residual' => $residual,
            'per_unit'       => array_values($perUnit),
            'per_kategori'   => array_values($perKategori),
            'tipe'           => $tipe,
            'progres'        => $progres,
            'keputusan'      => $keputusan,
            'rekonsiliasi'   => $rekonsiliasi,
            'monitoring'     => $monitoring,
            'prioritas'      => $prioritas,
            'eskalasi'       => $eskalasi,
            'daftar'         => $daftar,
            'tahun_list'     => $tahun_list,
        ];
    }

    /**
     * Matriks 5x5 (Likelihood x Dampak) untuk tampilan heat-map.
     * Sel berisi jumlah risiko dengan pasangan L/D tersebut.
     *
     * @return array [likelihood][dampak] => jumlah
     */
    public function matriks($tahun, $f = [])
    {
        $sel = [];
        for ($l = 1; $l <= 5; $l++) {
            for ($d = 1; $d <= 5; $d++) {
                $sel[$l][$d] = 0;
            }
        }

        $dasar = $f;
        $dasar['tahun'] = $tahun;
        foreach ($this->Model_mri_register->getListDenganSkor($dasar) as $r) {
            if ($r->inherent_l === null) {
                continue;
            }
            $l = (int) $r->inherent_l;
            $d = (int) $r->inherent_d;
            if ($l >= 1 && $l <= 5 && $d >= 1 && $d <= 5) {
                $sel[$l][$d]++;
            }
        }
        return $sel;
    }

    /**
     * Matriks residual 5x5.
     */
    public function matriksResidual($tahun, $f = [])
    {
        $sel = [];
        for ($l = 1; $l <= 5; $l++) {
            for ($d = 1; $d <= 5; $d++) {
                $sel[$l][$d] = 0;
            }
        }
        $dasar = $f;
        $dasar['tahun'] = $tahun;
        foreach ($this->Model_mri_register->getListDenganSkor($dasar) as $r) {
            if ($r->residual_l === null) {
                continue;
            }
            $l = (int) $r->residual_l;
            $d = (int) $r->residual_d;
            if ($l >= 1 && $l <= 5 && $d >= 1 && $d <= 5) {
                $sel[$l][$d]++;
            }
        }
        return $sel;
    }

    /**
     * Daftar unit untuk dropdown filter (pakai bagian_upk, bukan tabel legacy).
     * @param bool $hanya_upk bila true hanya unit berstatus_upk = 1
     */
    public function daftarUnit($hanya_upk = false)
    {
        $this->db->from('bagian_upk');
        if ($hanya_upk) {
            $this->db->where('status_upk', 1);
        }
        $this->db->order_by('id_bagian', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Alur 8 tahap ISO 31000 disertai jumlah catatan yang sudah terisi.
     *
     * Tahapan tetap diambil dari Model_mri_master::tahapan() (materi §5.1);
     * yang dihitung di sini hanya seberapa jauh tiap tahap sudah dikerjakan
     * pada tahun berjalan, supaya strip di dashboard sekaligus berfungsi
     * sebagai penanda tahap mana yang belum disentuh.
     *
     * @param int $tahun
     * @return array entri tahapan + jumlah, satuan, terisi
     */
    public function ringkasAlur($tahun)
    {
        $tahun = (int) $tahun;

        // penyebut bersama: total risiko tahun ini
        $this->db->where('tahun', $tahun);
        $totalRisiko = (int) $this->db->count_all_results('mri_register');

        $x = []; // numerator (yang sudah terpenuhi)
        $y = []; // penyebut (target tahap)

        // 1 — sesi komunikasi yang sudah tuntas (ISO 6.2)
        if ($this->db->table_exists('mri_komunikasi')) {
            $this->db->where('tahun', $tahun);
            $this->db->where('status', 'SELESAI');
            $x[1] = (int) $this->db->count_all_results('mri_komunikasi');
            $this->db->where('tahun', $tahun);
            $y[1] = max(1, (int) $this->db->count_all_results('mri_komunikasi'));
        } else {
            $x[1] = 0;
            $y[1] = 1;
        }

        // 2 — lingkup berstatus FINAL
        $this->db->where('tahun', $tahun);
        $this->db->where('status', 'FINAL');
        $x[2] = (int) $this->db->count_all_results('mri_lingkup');
        $this->db->where('tahun', $tahun);
        $y[2] = max(1, (int) $this->db->count_all_results('mri_lingkup'));

        // 3 — risiko teridentifikasi (target = minimal satu risiko tercatat)
        $x[3] = $totalRisiko;
        $y[3] = max(1, $totalRisiko);

        // 4 — risiko yang sudah mempunyai skor inherent
        $this->db->from('mri_analisa a');
        $this->db->join('mri_register r', 'r.id_register = a.id_register');
        $this->db->where('r.tahun', $tahun);
        $this->db->where('a.nilai_inherent IS NOT NULL', null, false);
        $x[4] = (int) $this->db->count_all_results();
        $y[4] = max(1, $totalRisiko);

        // 5 — risiko yang sudah diberi keputusan evaluasi
        $this->db->from('mri_evaluasi e');
        $this->db->join('mri_register r', 'r.id_register = e.id_register');
        $this->db->where('r.tahun', $tahun);
        $x[5] = (int) $this->db->count_all_results();
        $y[5] = max(1, $totalRisiko);

        // 6 — rencana perlakuan tersusun (terhadap risiko yang sudah dievaluasi)
        $this->db->from('mri_perlakuan p');
        $this->db->join('mri_register r', 'r.id_register = p.id_register');
        $this->db->where('r.tahun', $tahun);
        $x[6] = (int) $this->db->count_all_results();
        $this->db->from('mri_evaluasi e');
        $this->db->join('mri_register r', 'r.id_register = e.id_register');
        $this->db->where('r.tahun', $tahun);
        $y[6] = max(1, (int) $this->db->count_all_results());

        // 7 — risiko yang punya minimal satu catatan monitoring
        $this->db->select('COUNT(DISTINCT m.id_register) AS jml', false);
        $this->db->from('mri_monitoring m');
        $this->db->join('mri_register r', 'r.id_register = m.id_register');
        $this->db->where('r.tahun', $tahun);
        $x[7] = (int) $this->db->get()->row()->jml;
        $y[7] = max(1, $totalRisiko);

        // 8 — jenis laporan tersedia (dari 4 jenis baku)
        $this->db->where('tahun', $tahun);
        $x[8] = (int) $this->db->count_all_results('mri_laporan');
        $y[8] = 4;

        $satuan = [
            1 => 'sesi selesai',
            2 => 'dok. lingkup',
            3 => 'risiko',
            4 => 'risiko dianalisa',
            5 => 'risiko dievaluasi',
            6 => 'rencana',
            7 => 'risiko dimonitor',
            8 => 'laporan',
        ];

        $out = [];
        foreach ($this->Model_mri_master->tahapan() as $t) {
            $n = (int) $t['no'];
            $num = isset($x[$n]) ? (int) $x[$n] : 0;
            $den = isset($y[$n]) ? (int) $y[$n] : 0;
            $pct = $den > 0 ? min(100, round(($num / $den) * 100)) : 0;

            if ($num <= 0) {
                $status = 'Belum';
            } elseif ($pct >= 100) {
                $status = 'Selesai';
            } else {
                $status = 'Sebagian';
            }

            $t['jumlah']  = $num;   // kompatibilitas tampilan lama
            $t['satuan']  = $satuan[$n];
            $t['terisi']  = $num > 0;
            $t['x']       = $num;
            $t['y']       = $den;
            $t['persen']  = $pct;
            $t['status']  = $status;
            $out[] = $t;
        }
        return $out;
    }
}
