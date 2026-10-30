<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Baca-only atas data legacy 2025-2026 (mr_profil_risiko, mr_analisa_risiko,
 * mr_tingkat_risiko, mr_kategori_risiko).
 *
 * FUNGSI: bukti historis yang ditampilkan sebagai rujukan read-only di form
 * modul manrisk (keputusan #9), supaya pengisi tahun 2027 dapat melihat
 * kondisi risiko tahun lalu tanpa mengubahnya.
 *
 * PERINGATAN MODEL INI:
 *  - TIDAK memiliki method tulis apa pun. Jangan ditambahi.
 *  - Matriks legacy memakai skala 5 level yang berbeda dari modul baru
 *    (mri_tingkat_risiko). Jangan mencampur angkanya.
 *  - Skor legacy tidak selalu sama dengan probabilitas x dampak; lihat
 *    catatan pada getMatriksLegacy().
 */
class Model_mri_bukti_legacy extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Daftar risiko legacy + analisanya untuk satu tahun (default 2025/2026).
     *
     * @param int $tahun
     * @param int|null $id_upk
     * @return array
     */
    public function getLegacy($tahun, $id_upk = null)
    {
        $this->db->select('p.*, a.id_analisa, a.kendali_uraian, a.desain, a.efektifitas,
                           a.probabilitas, a.dampak, a.tingkat_risiko, a.peringkat_risiko,
                           a.pemilik_risiko, k.nama_kr AS nama_kategori');
        $this->db->from('mr_profil_risiko p');
        $this->db->join('mr_analisa_risiko a', 'a.id_risiko = p.id_risiko', 'left');
        $this->db->join('mr_kategori_risiko k', 'k.kategori_kr = p.kategori', 'left');
        $this->db->where('p.tahun', $tahun);
        if ($id_upk) {
            $this->db->where('p.id_upk', $id_upk);
        }
        $this->db->order_by('p.kode_risiko', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Satu baris legacy berdasar id_risiko.
     */
    public function getLegacyById($id_risiko)
    {
        $this->db->select('p.*, a.id_analisa, a.kendali_uraian, a.desain, a.efektifitas,
                           a.probabilitas, a.dampak, a.tingkat_risiko, a.peringkat_risiko,
                           a.pemilik_risiko');
        $this->db->from('mr_profil_risiko p');
        $this->db->join('mr_analisa_risiko a', 'a.id_risiko = p.id_risiko', 'left');
        $this->db->where('p.id_risiko', $id_risiko);
        return $this->db->get()->row();
    }

    /**
     * Tahun yang tersedia pada data legacy.
     * @return array daftar int tahun
     */
    public function tahunTersedia()
    {
        $this->db->select('tahun');
        $this->db->distinct();
        $this->db->from('mr_profil_risiko');
        $this->db->order_by('tahun', 'DESC');
        $out = [];
        foreach ($this->db->get()->result() as $r) {
            $out[] = (int) $r->tahun;
        }
        return $out;
    }

    /**
     * Daftar unit yang punya risiko legacy pada tahun tertentu.
     */
    public function unitLegacy($tahun)
    {
        $this->db->select('p.id_upk, b.nama_bagian, COUNT(*) AS jumlah', false);
        $this->db->from('mr_profil_risiko p');
        $this->db->join('bagian_upk b', 'b.id_bagian = p.id_upk', 'left');
        $this->db->where('p.tahun', $tahun);
        $this->db->group_by('p.id_upk, b.nama_bagian');
        $this->db->order_by('b.nama_bagian', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Band level legacy (5 level) — HANYA untuk disclaimer di tampilan.
     * Jangan dipakai menghitung sesuatu.
     */
    public function bandLegacy()
    {
        $this->db->order_by('skor_min', 'ASC');
        return $this->db->get('mr_tingkat_risiko')->result();
    }

    /**
     * Matriks legacy 5x5 berdasar probabilitas x dampak yang tercatat.
     *
     * CATATAN PENTING: pada data legacy, nilai `tingkat_risiko` kadang tidak
     * konsisten dengan perkalian `probabilitas * dampak` (lihat pembuktian:
     * hanya 3 dari 25 sel yang selaras). Method ini sengaja menampilkan dua
     * hal terpisah agar pembaca tidak salah menganggapnya konsisten:
     *   - 'jumlah'  : sebaran pasangan probabilitas x dampak yang tercatat
     *   - 'label'   : label tingkat yang tercatat pada kolom tingkat_risiko
     */
    public function getMatriksLegacy($tahun)
    {
        $this->db->select('a.probabilitas, a.dampak, a.tingkat_risiko');
        $this->db->from('mr_analisa_risiko a');
        $this->db->join('mr_profil_risiko p', 'p.id_risiko = a.id_risiko', 'left');
        $this->db->where('p.tahun', $tahun);
        $rows = $this->db->get()->result();

        $jumlah = [];
        $label = [];
        for ($p = 1; $p <= 5; $p++) {
            for ($d = 1; $d <= 5; $d++) {
                $jumlah[$p][$d] = 0;
                $label[$p][$d] = [];
            }
        }
        foreach ($rows as $r) {
            $p = (int) $r->probabilitas;
            $d = (int) $r->dampak;
            if ($p < 1 || $p > 5 || $d < 1 || $d > 5) {
                continue;
            }
            $jumlah[$p][$d]++;
            $t = (string) $r->tingkat_risiko;
            if (!isset($label[$p][$d][$t])) {
                $label[$p][$d][$t] = 0;
            }
            $label[$p][$d][$t]++;
        }
        return ['jumlah' => $jumlah, 'label' => $label, 'total' => count($rows)];
    }

    /**
     * Ringkasan sebaran level legacy untuk panel perbandingan.
     */
    public function rekapLevelLegacy($tahun)
    {
        $this->db->select('a.tingkat_risiko, COUNT(*) AS jml', false);
        $this->db->from('mr_analisa_risiko a');
        $this->db->join('mr_profil_risiko p', 'p.id_risiko = a.id_risiko', 'left');
        $this->db->where('p.tahun', $tahun);
        $this->db->group_by('a.tingkat_risiko');
        $this->db->order_by('a.tingkat_risiko', 'ASC');
        return $this->db->get()->result();
    }
}
