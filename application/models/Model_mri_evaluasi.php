<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Evaluasi risiko (ISO 31000:2018 Klausa 6.5) — mri_evaluasi
 * serta jejak perubahan skor antartiwulan — mri_riwayat_skor.
 *
 * Evaluasi memutuskan apakah risiko perlu perlakuan lebih lanjut,
 * diterima sesuai appetite, atau dieskalasi.
 */
class Model_mri_evaluasi extends CI_Model
{
    /** Keputusan evaluasi — harus persis dengan enum mri_evaluasi.keputusan. */
    const KEPUTUSAN = ['Accept', 'Monitor', 'Treat', 'Escalate'];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_mri_master');
    }

    private function user()
    {
        $u = $this->session->userdata('nama_pengguna');
        return $u ?: 'sistem';
    }

    public function getById($id)
    {
        return $this->db->get_where('mri_evaluasi', ['id_evaluasi' => $id])->row();
    }

    public function getByRegister($id_register)
    {
        $this->db->where('id_register', $id_register);
        $this->db->order_by('id_evaluasi', 'DESC');
        return $this->db->get('mri_evaluasi')->row();
    }

    public function getRiwayatEvaluasi($id_register)
    {
        $this->db->where('id_register', $id_register);
        $this->db->order_by('id_evaluasi', 'DESC');
        return $this->db->get('mri_evaluasi')->result();
    }

    public function level($skor)
    {
        $t = $this->Model_mri_master->levelDariSkor($skor);
        return $t ? $t->nama_tingkat : null;
    }

    /**
     * Simpan evaluasi. Nilai evaluasi memakai skala yang sama (1..25)
     * dan levelnya dihitung ulang dari mri_tingkat_risiko.
     */
    public function simpan($id_register, $d)
    {
        $skor = max(1, min(25, (int) (isset($d['nilai_evaluasi']) ? $d['nilai_evaluasi'] : 1)));

        $row = [
            'id_register'      => $id_register,
            'id_analisa'       => isset($d['id_analisa']) ? $d['id_analisa'] : null,
            'nilai_evaluasi'   => $skor,
            'level_evaluasi'   => $this->level($skor),
            'kriteria_rujukan' => isset($d['kriteria_rujukan']) ? $d['kriteria_rujukan'] : 'mri_tingkat_risiko + mri_appetite',
            'keputusan'        => isset($d['keputusan']) && in_array($d['keputusan'], self::KEPUTUSAN, true)
                ? $d['keputusan'] : 'Monitor',
            'alasan_evaluasi'  => isset($d['alasan_evaluasi']) ? $d['alasan_evaluasi'] : null,
            'perlu_eskalasi'   => !empty($d['perlu_eskalasi']) ? 1 : 0,
            'eskalasi_ke'      => !empty($d['eskalasi_ke']) ? $d['eskalasi_ke'] : null,
            'tanggal_evaluasi' => isset($d['tanggal_evaluasi']) && $d['tanggal_evaluasi'] ? $d['tanggal_evaluasi'] : date('Y-m-d'),
            'triwulan'         => isset($d['triwulan']) && $d['triwulan'] ? (int) $d['triwulan'] : 1,
            'oleh'             => $this->user(),
            'modified_at'      => date('Y-m-d H:i:s'),
            'modified_by'      => $this->user(),
        ];

        // Band & status review dihitung pemanggil (controller) agar
        // selaras dengan aksi mri_tingkat_risiko atas skor residual.
        if (isset($d['keputusan_band'])) {
            $row['keputusan_band'] = $d['keputusan_band'];
        }
        if (isset($d['status_review']) && in_array($d['status_review'], ['SESUAI', 'PERLU_REVIEW', 'DITINJAU'], true)) {
            $row['status_review'] = $d['status_review'];
        }

        $existing = $this->getByRegister($id_register);
        if ($existing) {
            $this->db->where('id_evaluasi', $existing->id_evaluasi);
            $this->db->update('mri_evaluasi', $row);
            $id = $existing->id_evaluasi;
        } else {
            $row['created_at'] = date('Y-m-d H:i:s');
            $row['created_by'] = $this->user();
            $this->db->insert('mri_evaluasi', $row);
            $id = $this->db->insert_id();
        }
        // Snapshot skor dicatat pada cabang manapun; catatRiwayat menolak
        // baris yang identik sehingga riwayat tidak bertambah tanpa perubahan.
        $this->catatRiwayat($id_register, $d);
        return $id;
    }

    /**
     * Catat snapshot skor ke mri_riwayat_skor (audit trail perubahan inherent
     * dan residual antartiwulan).
     */
    public function catatRiwayat($id_register, $d)
    {
        $an = $this->db->get_where('mri_analisa', ['id_register' => $id_register])->row();
        if (!$an) {
            return;
        }
        $il = isset($d['inherent_l']) ? $d['inherent_l'] : $an->inherent_l;
        $idl = isset($d['inherent_d']) ? $d['inherent_d'] : $an->inherent_d;
        $rl = isset($d['residual_l']) ? $d['residual_l'] : $an->residual_l;
        $rd = isset($d['residual_d']) ? $d['residual_d'] : $an->residual_d;

        $ni = (int) $il * (int) $idl;
        $nr = (int) $rl * (int) $rd;

        // lewati jika identik dengan snapshot terakhir
        $terakhir = $this->db->where('id_register', $id_register)
            ->order_by('id_riwayat', 'DESC')
            ->get('mri_riwayat_skor')
            ->row();
        if ($terakhir && (int) $terakhir->nilai_inherent === $ni && (int) $terakhir->nilai_residual === $nr) {
            return;
        }

        $this->db->insert('mri_riwayat_skor', [
            'id_register'     => $id_register,
            // Tahun wajib dari register risiko (2027+), bukan date('Y'):
            // snapshot November 2026 untuk risiko 2027 salah catat sebagai 2026.
            'tahun'           => $this->tahunRegister($id_register),
            'triwulan'        => isset($d['triwulan']) && $d['triwulan'] ? (int) $d['triwulan'] : 1,
            'nilai_inherent'  => $ni,
            'nilai_residual'  => $nr,
            'level_inherent'  => $this->level($ni),
            'level_residual'  => $this->level($nr),
            'recorded_at'     => date('Y-m-d H:i:s'),
        ]);
    }

    /** Tahun risiko dari mri_register; fallback ke tahun berjalan bila tak ada. */
    private function tahunRegister($id_register)
    {
        $th = $this->db->select('tahun')
            ->where('id_register', $id_register)
            ->get('mri_register')
            ->row();
        return $th ? (int) $th->tahun : (int) date('Y');
    }

    public function getRiwayatSkor($id_register)
    {
        $this->db->where('id_register', $id_register);
        $this->db->order_by('id_riwayat', 'ASC');
        return $this->db->get('mri_riwayat_skor')->result();
    }

    /**
     * Rekap jumlah risiko per keputusan evaluasi untuk dashboard/laporan.
     */
    public function rekapKeputusan($tahun)
    {
        $this->db->select('e.keputusan, COUNT(*) AS jml', false);
        $this->db->from('mri_evaluasi e');
        $this->db->join('mri_register r', 'r.id_register = e.id_register', 'left');
        $this->db->where('r.tahun', $tahun);
        $this->db->group_by('e.keputusan');
        return $this->db->get()->result();
    }

    /**
     * Daftar risiko yang wajib dieskalasi (melebihi batas appetite / level tertinggi).
     */
    public function perluEskalasi($tahun)
    {
        $this->db->select('r.kode_risiko, r.risiko, r.sasaran_kpi, e.keputusan, e.alasan_evaluasi, e.eskalasi_ke, a.nilai_residual, a.level_residual');
        $this->db->from('mri_evaluasi e');
        $this->db->join('mri_register r', 'r.id_register = e.id_register', 'left');
        $this->db->join('mri_analisa a', 'a.id_register = e.id_register', 'left');
        $this->db->where('r.tahun', $tahun);
        $this->db->where('e.perlu_eskalasi', 1);
        $this->db->order_by('a.nilai_residual', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Tandai satu evaluasi sudah ditinjau Administrator/Auditor.
     * Menutup antrean PERLU_REVIEW tanpa mengubah keputusan/apapun kolom lain.
     */
    public function tandaiDitinjau($id_evaluasi, $catatan, $oleh)
    {
        $row = [
            'status_review'  => 'DITINJAU',
            'catatan_review' => $catatan,
            'reviewed_at'    => date('Y-m-d H:i:s'),
            'reviewed_by'    => $oleh,
            'modified_at'    => date('Y-m-d H:i:s'),
            'modified_by'    => $oleh,
        ];
        $this->db->where('id_evaluasi', $id_evaluasi);
        return $this->db->update('mri_evaluasi', $row);
    }

    /**
     * Rekap jumlah evaluasi per status_review (SESUAI / PERLU_REVIEW / DITINJAU)
     * untuk filter & kartu di daftar evaluasi.
     * @return array ['SESUAI' => n, 'PERLU_REVIEW' => n, 'DITINJAU' => n]
     */
    public function rekapStatusReview($tahun)
    {
        $this->db->select('e.status_review, COUNT(*) AS jml', false);
        $this->db->from('mri_evaluasi e');
        $this->db->join('mri_register r', 'r.id_register = e.id_register', 'left');
        $this->db->where('r.tahun', $tahun);
        $this->db->group_by('e.status_review');
        $out = ['SESUAI' => 0, 'PERLU_REVIEW' => 0, 'DITINJAU' => 0];
        foreach ($this->db->get()->result() as $row) {
            $out[$row->status_review] = (int) $row->jml;
        }
        return $out;
    }

    /**
     * Aksi band (rekomendasi) untuk sebuah skor residual dari mri_tingkat_risiko.
     * @return string|null Accept|Monitor|Treat|Escalate
     */
    public function aksiBandDariSkor($skor)
    {
        $t = $this->Model_mri_master->levelDariSkor($skor);
        return $t ? $t->aksi : null;
    }
}
