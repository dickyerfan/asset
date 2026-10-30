<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Analisis risiko (ISO 31000:2018 Klausa 6.4) — mri_analisa.
 *
 * Perhitungan skor selalu L x D (skala 1..5, hasil 1..25).
 * Level diambil dari mri_tingkat_risiko, TIDAK di-hardcode di sini.
 *
 * Model ini menjamin dua invarian setiap kali menulis:
 *   1. nilai_inherent  = inherent_l  * inherent_d
 *   2. nilai_residual  = residual_l  * residual_d
 *   3. level_*          = band di mri_tingkat_risiko yang memuat nilai_*
 */
class Model_mri_analisa extends CI_Model
{
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
        return $this->db->get_where('mri_analisa', ['id_analisa' => $id])->row();
    }

    public function getByRegister($id_register)
    {
        $this->db->where('id_register', $id_register);
        return $this->db->get('mri_analisa')->row();
    }

    /**
     * Nilai inherent + residual untuk sekumpulan id_register.
     * @return array [id_register => row]
     */
    public function getBanyak($id_register)
    {
        $out = [];
        if (!$id_register) {
            return $out;
        }
        $this->db->where_in('id_register', $id_register);
        foreach ($this->db->get('mri_analisa')->result() as $r) {
            $out[$r->id_register] = $r;
        }
        return $out;
    }

    /** Hitung level dari sebuah skor memakai tabel tingkat risiko. */
    private function level($skor)
    {
        $t = $this->Model_mri_master->levelDariSkor($skor);
        return $t ? $t->nama_tingkat : null;
    }

    /**
     * Simpan analisa untuk satu risiko.
     *
     * @param int $id_register
     * @param array $d minimal: inherent_l, inherent_d, residual_l, residual_d
     *                 opsional: kontrol_uraian, kontrol_ada, memadai, ringkasan, id_risk_owner
     * @return int id_analisa
     */
    public function simpan($id_register, $d)
    {
        $il = max(1, min(5, (int) (isset($d['inherent_l']) ? $d['inherent_l'] : 1)));
        $id = max(1, min(5, (int) (isset($d['inherent_d']) ? $d['inherent_d'] : 1)));
        $rl = max(1, min(5, (int) (isset($d['residual_l']) ? $d['residual_l'] : 1)));
        $rd = max(1, min(5, (int) (isset($d['residual_d']) ? $d['residual_d'] : 1)));

        $nilai_inherent = $il * $id;
        $nilai_residual = $rl * $rd;

        $row = [
            'id_register'      => $id_register,
            'inherent_l'       => $il,
            'inherent_d'       => $id,
            'nilai_inherent'   => $nilai_inherent,
            'level_inherent'   => $this->level($nilai_inherent),
            'residual_l'       => $rl,
            'residual_d'       => $rd,
            'nilai_residual'   => $nilai_residual,
            'level_residual'   => $this->level($nilai_residual),
            'kontrol_uraian'   => isset($d['kontrol_uraian']) ? $d['kontrol_uraian'] : null,
            'kontrol_ada'      => !empty($d['kontrol_ada']) ? 'Ada' : 'Tidak',
            'memadai'          => !empty($d['memadai']) ? 'Memadai' : 'Belum Memadai',
            'ringkasan'        => isset($d['ringkasan']) ? $d['ringkasan'] : null,
            'id_risk_owner'    => isset($d['id_risk_owner']) && $d['id_risk_owner'] !== '' ? $d['id_risk_owner'] : null,
            'modified_at'      => date('Y-m-d H:i:s'),
            'modified_by'      => $this->user(),
        ];

        $existing = $this->getByRegister($id_register);
        if ($existing) {
            $this->db->where('id_analisa', $existing->id_analisa);
            $this->db->update('mri_analisa', $row);
            return $existing->id_analisa;
        }

        $row['created_at'] = date('Y-m-d H:i:s');
        $row['created_by'] = $this->user();
        $this->db->insert('mri_analisa', $row);
        return $this->db->insert_id();
    }

    /**
     * Verifikasi seluruh baris mri_analisa terhadap dua invarian.
     * Dipakai sebagai pemeriksaan pascapenulisan, bukan hanya saat runtime.
     *
     * @return array daftar pelanggaran: [['tipe', 'id_analisa', 'detail']]
     */
    public function verifikasiInvarian()
    {
        $this->load->model('Model_mri_master');
        $tingkat = $this->db->order_by('urut_tampil', 'ASC')->get('mri_tingkat_risiko')->result();
        $baik = [];
        foreach ($tingkat as $t) {
            $baik[] = $t;
        }

        $pelanggaran = [];
        foreach ($this->db->get('mri_analisa')->result() as $r) {
            $hi = $r->inherent_l * $r->inherent_d;
            $hr = $r->residual_l * $r->residual_d;

            if ($hi !== (int) $r->nilai_inherent) {
                $pelanggaran[] = ['LxD inherent', $r->id_analisa,
                    "dihitung {$hi}, tersimpan {$r->nilai_inherent}"];
            }
            if ($hr !== (int) $r->nilai_residual) {
                $pelanggaran[] = ['LxD residual', $r->id_analisa,
                    "dihitung {$hr}, tersimpan {$r->nilai_residual}"];
            }

            foreach ([[$hi, $r->level_inherent, 'level_inherent'], [$hr, $r->level_residual, 'level_residual']] as $c) {
                list($skor, $level, $kolom) = $c;
                $cocok = null;
                foreach ($tingkat as $t) {
                    if ($skor >= $t->skor_min && $skor <= $t->skor_max) {
                        $cocok = $t->nama_tingkat;
                        break;
                    }
                }
                if ($cocok !== null && $cocok !== $level) {
                    $pelanggaran[] = [$kolom, $r->id_analisa,
                        "skor {$skor} seharusnya '{$cocok}', tersimpan '{$level}'"];
                }
            }
        }
        return $pelanggaran;
    }
}
