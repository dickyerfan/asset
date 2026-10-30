<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ISO 31000:2018 Klausa 5.3 — Lingkup, konteks & kriteria (mri_lingkup)
 * serta Sasaran organisasi / objektif risiko (mri_objektif, mri_objektif_revisi).
 */
class Model_mri_lingkup extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function user()
    {
        $u = $this->session->userdata('nama_pengguna');
        return $u ?: 'sistem';
    }

    // ---------------- mri_lingkup ----------------

    public function getLingkup($tahun = null, $id_upk = null)
    {
        $this->db->from('mri_lingkup');
        if ($tahun) {
            $this->db->where('tahun', $tahun);
        }
        if ($id_upk) {
            $this->db->where('id_upk', $id_upk);
        }
        $this->db->order_by('id_lingkup', 'DESC');
        return $this->db->get()->result();
    }

    public function getLingkupById($id)
    {
        return $this->db->get_where('mri_lingkup', ['id_lingkup' => $id])->row();
    }

    public function getLingkupAktif($tahun = null)
    {
        $tahun = $tahun ?: (int) date('Y');
        $this->db->where('tahun', $tahun);
        $this->db->order_by('id_lingkup', 'DESC');
        return $this->db->get('mri_lingkup')->row();
    }

    public function simpanLingkup($id, $data)
    {
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = $this->user();
        if ($id) {
            $this->db->where('id_lingkup', $id);
            return $this->db->update('mri_lingkup', $data);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->user();
        return $this->db->insert('mri_lingkup', $data);
    }

    // ---------------- mri_objektif ----------------

    /**
     * @param int $tahun      filter tahun (opsional)
     * @param int $id_lingkup filter ikat ke lingkup tertentu (opsional)
     * @param string $status  filter status ('','SELESAI','BELUM')
     */
    public function getObjektif($tahun = null, $id_lingkup = null, $status = null)
    {
        $this->db->select('o.*, p.nama_proses, p.kode_proses, b.nama_bagian');
        $this->db->from('mri_objektif o');
        $this->db->join('mri_proses p', 'p.id_proses = o.id_proses', 'left');
        $this->db->join('bagian_upk b', 'b.id_bagian = o.id_bagian', 'left');
        if ($tahun) {
            $this->db->where('o.tahun', $tahun);
        }
        if ($id_lingkup) {
            $this->db->where('o.id_lingkup', $id_lingkup);
        }
        if ($status !== null && $status !== '') {
            $this->db->where('o.status_rekonsiliasi', $status);
        }
        $this->db->order_by('o.id_bagian', 'ASC');
        $this->db->order_by('o.id_objektif', 'ASC');
        return $this->db->get()->result();
    }

    public function getObjektifById($id)
    {
        return $this->db->get_where('mri_objektif', ['id_objektif' => $id])->row();
    }

    /**
     * Ringkasan rekonsiliasi: jumlah SELESAI / BELUM per tahun.
     */
    public function rekapRekonsiliasi($tahun)
    {
        $this->db->select('status_rekonsiliasi, COUNT(*) AS jml');
        $this->db->where('tahun', $tahun);
        $this->db->group_by('status_rekonsiliasi');
        $hasil = ['SELESAI' => 0, 'BELUM' => 0];
        foreach ($this->db->get('mri_objektif')->result() as $r) {
            $hasil[$r->status_rekonsiliasi] = (int) $r->jml;
        }
        return $hasil;
    }

    public function simpanObjektif($id, $data)
    {
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = $this->user();
        if ($id) {
            $this->db->where('id_objektif', $id);
            return $this->db->update('mri_objektif', $data);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->user();
        return $this->db->insert('mri_objektif', $data);
    }

    /**
     * Rekonsiliasi nilai objektif: nilai_lama -> nilai_baru, dicatat ke
     * mri_objektif_revisi agar jejak audit perubahan angka RKAP terjaga.
     * Status rekonsiliasi ikut disimpan (SELESAI = angka sudah dikonfirmasi,
     * BELUM = masih menunggu konfirmasi / terdapat selisih).
     *
     * @param string $status 'BELUM' | 'SELESAI'
     */
    public function rekonsiliasiObjektif($id_objektif, $nilai_baru, $alasan, $status = 'SELESAI')
    {
        $lama = $this->getObjektifById($id_objektif);
        if (!$lama) {
            return false;
        }
        $nilai_baru = str_replace(',', '.', (string) $nilai_baru);
        if (!in_array($status, ['BELUM', 'SELESAI'], true)) {
            $status = 'SELESAI';
        }

        $this->catatRevisi($id_objektif, $lama->nilai_rkap, $nilai_baru, $alasan);

        return $this->simpanObjektif($id_objektif, [
            'nilai_rkap'            => $nilai_baru,
            'status_rekonsiliasi'   => $status,
            'catatan_rekonsiliasi'  => $alasan,
        ]);
    }

    /**
     * Catat perubahan nilai RKAP ke jejak audit (mri_objektif_revisi).
     *
     * Tidak mencatat bila nilai lama & baru sama.
     *
     * @param string|null $nilai_lama nilai sebelum diubah
     * @param string|null $nilai_baru nilai sesudah diubah
     * @param string      $alasan     dasar perubahan
     * @return bool
     */
    public function catatRevisi($id_objektif, $nilai_lama, $nilai_baru, $alasan)
    {
        if ((float) $nilai_lama === (float) $nilai_baru) {
            return false;
        }
        return $this->db->insert('mri_objektif_revisi', [
            'id_objektif' => $id_objektif,
            'nilai_lama'  => $nilai_lama,
            'nilai_baru'  => $nilai_baru,
            'alasan'      => $alasan ?: 'Perubahan nilai RKAP',
            'tanggal'     => date('Y-m-d'),
            'oleh'        => $this->user(),
        ]);
    }

    /**
     * Riwayat revisi nilai suatu objektif.
     */
    public function getRevisi($id_objektif)
    {
        $this->db->where('id_objektif', $id_objektif);
        $this->db->order_by('id_revisi', 'DESC');
        return $this->db->get('mri_objektif_revisi')->result();
    }
}
