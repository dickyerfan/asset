<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Rekap Risk Assessment — gabungan empat tahap dalam satu tabel.
 *
 * Sumber kolom mengikuti contoh CRMO Perpamsi halaman 37:
 *   Identifikasi (mri_register + mri_akibat + mri_objektif)
 *   Analisis     (mri_analisa: K, D, nilai, tingkat)
 *   Evaluasi     (mri_evaluasi: keputusan)
 *   Perlakuan    (mri_perlakuan + mri_pic: rencana, biaya, PIC)
 *
 * Tidak ada tabel baru: seluruhnya query baca dari data yang sudah ada,
 * sehingga angka pada halaman ini selalu sama dengan Register/Analisis.
 */
class Model_mri_assessment extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Daftar risiko lengkap untuk tabel Risk Assessment.
     *
     * @param array $f tahun, id_upk, level, q
     * @return array baris objek dengan seluruh kolom gabungan
     */
    public function getRekap($f = [])
    {
        $this->db->select('r.*, k.nama_kat, b.nama_bagian');
        $this->db->from('mri_register r');
        $this->db->join('mri_kategori_risiko k', 'k.id_kategori = r.id_kategori', 'left');
        $this->db->join('bagian_upk b', 'b.id_bagian = r.id_upk', 'left');

        if (!empty($f['tahun'])) {
            $this->db->where('r.tahun', (int) $f['tahun']);
        }
        if (!empty($f['id_upk'])) {
            $this->db->where('r.id_upk', (int) $f['id_upk']);
        }
        if (!empty($f['status_risiko'])) {
            $this->db->where('r.status_risiko', $f['status_risiko']);
        }
        if (!empty($f['q'])) {
            $this->db->group_start();
            $this->db->like('r.kode_risiko', $f['q']);
            $this->db->or_like('r.risiko', $f['q']);
            $this->db->or_like('r.sasaran_kpi', $f['q']);
            $this->db->or_like('r.penyebab', $f['q']);
            $this->db->group_end();
        }

        $this->db->order_by('r.kode_risiko', 'ASC');
        $rows = $this->db->get()->result();
        if (!$rows) {
            return [];
        }

        $ids = [];
        foreach ($rows as $r) {
            $ids[] = (int) $r->id_register;
        }

        $analisa   = $this->petaPerRegister('mri_analisa', $ids);
        $evaluasi  = $this->petaPerRegister('mri_evaluasi', $ids);
        $perlakuan = $this->petaPerlakuan($ids);
        $akibat    = $this->petaAkibat($ids);
        $objektif  = $this->petaObjektif($ids);

        $idPic = [];
        foreach ($perlakuan as $p) {
            if (!empty($p->id_pic)) {
                $idPic[] = (int) $p->id_pic;
            }
        }
        $pic = $this->petaPic($idPic);

        $levelFilter = isset($f['level']) ? (string) $f['level'] : '';
        $hasil = [];

        foreach ($rows as $r) {
            $id = (int) $r->id_register;
            $a  = isset($analisa[$id]) ? $analisa[$id] : null;
            $e  = isset($evaluasi[$id]) ? $evaluasi[$id] : null;
            $p  = isset($perlakuan[$id]) ? $perlakuan[$id] : null;
            $pc = $p && isset($pic[(int) $p->id_pic]) ? $pic[(int) $p->id_pic] : null;

            $r->inherent_l      = $a ? (int) $a->inherent_l : null;
            $r->inherent_d      = $a ? (int) $a->inherent_d : null;
            $r->nilai_inherent  = $a ? (int) $a->nilai_inherent : null;
            $r->level_inherent  = $a ? $a->level_inherent : null;
            $r->kontrol_uraian  = $a ? $a->kontrol_uraian : null;

            $r->keputusan       = $e ? $e->keputusan : null;
            $r->nilai_evaluasi  = $e ? $e->nilai_evaluasi : null;
            $r->level_evaluasi  = $e ? $e->level_evaluasi : null;

            $r->strategi        = $p ? $p->strategi : null;
            $r->uraian_tindakan = $p ? $p->uraian_tindakan : null;
            $r->anggaran_biaya  = $p ? $p->anggaran_biaya : null;
            $r->status_laksana  = $p ? $p->status_laksana : null;
            $r->nama_pic        = $pc ? $pc->nama_pic : null;
            $r->jabatan_pic     = $pc ? $pc->jabatan_pic : null;

            $r->dampak   = isset($akibat[$id]) ? $akibat[$id] : '';
            $r->objektif = isset($objektif[$id]) ? $objektif[$id] : '';

            if ($levelFilter !== '' && (string) $r->level_inherent !== $levelFilter) {
                continue;
            }
            $hasil[] = $r;
        }

        return $hasil;
    }

    /** [id_register => row] untuk tabel yang satu baris per register. */
    private function petaPerRegister($tabel, $ids)
    {
        $out = [];
        if (!$ids) {
            return $out;
        }
        $this->db->where_in('id_register', $ids);
        foreach ($this->db->get($tabel)->result() as $row) {
            $out[(int) $row->id_register] = $row;
        }
        return $out;
    }

    /** [id_register => row perlakuan terbaru]. */
    private function petaPerlakuan($ids)
    {
        $out = [];
        if (!$ids) {
            return $out;
        }
        $this->db->where_in('id_register', $ids);
        $this->db->order_by('id_perlakuan', 'ASC');
        foreach ($this->db->get('mri_perlakuan')->result() as $row) {
            // Baris terakhir menang, sehingga yang dipakai adalah perlakuan terbaru.
            $out[(int) $row->id_register] = $row;
        }
        return $out;
    }

    /** [id_register => 'dampak 1; dampak 2']. */
    private function petaAkibat($ids)
    {
        $out = [];
        if (!$ids) {
            return $out;
        }
        $this->db->select('id_register, akibat');
        $this->db->where_in('id_register', $ids);
        $this->db->order_by('urutan', 'ASC');
        foreach ($this->db->get('mri_akibat')->result() as $row) {
            $id = (int) $row->id_register;
            $teks = trim((string) $row->akibat);
            if ($teks === '') {
                continue;
            }
            $out[$id] = isset($out[$id]) ? $out[$id] . '; ' . $teks : $teks;
        }
        return $out;
    }

    /** [id_register => 'Sasaran A; Sasaran B'] dari objektif yang dicentang. */
    private function petaObjektif($ids)
    {
        $out = [];
        if (!$ids) {
            return $out;
        }
        $this->db->select('ro.id_register, o.indikator');
        $this->db->from('mri_risiko_objektif ro');
        $this->db->join('mri_objektif o', 'o.id_objektif = ro.id_objektif', 'left');
        $this->db->where_in('ro.id_register', $ids);
        $this->db->order_by('o.id_objektif', 'ASC');
        foreach ($this->db->get()->result() as $row) {
            $id = (int) $row->id_register;
            $teks = trim((string) $row->indikator);
            if ($teks === '') {
                continue;
            }
            if (!isset($out[$id])) {
                $out[$id] = $teks;
            } elseif (strpos($out[$id], $teks) === false) {
                $out[$id] .= '; ' . $teks;
            }
        }
        return $out;
    }

    /** [id_pic => row]. */
    private function petaPic($ids)
    {
        $out = [];
        if (!$ids) {
            return $out;
        }
        $this->db->where_in('id_pic', array_unique($ids));
        foreach ($this->db->get('mri_pic')->result() as $row) {
            $out[(int) $row->id_pic] = $row;
        }
        return $out;
    }
}
