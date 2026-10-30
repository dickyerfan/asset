<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Pengaturan modul manrisk:
 *  - mri_kunci      : kunci transaksi (mirip legacy mr_pengaturan_kunci)
 *  - mri_appetite   : risk appetite / tolerance / capacity per kategori per tahun
 *
 * Status kunci: 0 = terbuka (bisa edit), 1 = terkunci (read-only).
 * Nilai default 0 mengikuti konvensi legacy.
 */
class Model_mri_pengaturan extends CI_Model
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

    // ---------------- mri_kunci ----------------

    public function getKunci()
    {
        $this->db->order_by('id_kunci', 'ASC');
        return $this->db->get('mri_kunci')->result();
    }

    public function getKunciByKode($kode)
    {
        return $this->db->get_where('mri_kunci', ['kode_kunci' => $kode])->row();
    }

    /**
     * Apakah transaksi terkait kode kunci ini masih boleh diubah?
     * @return bool true = boleh edit
     */
    public function bolehUbah($kode)
    {
        $k = $this->getKunciByKode($kode);
        if (!$k) {
            return true; // belum ada kunci = terbuka
        }
        return ((int) $k->status_kunci) === 0;
    }

    public function setKunci($kode, $kunci)
    {
        $kunci = (int) $kunci;
        $now = date('Y-m-d H:i:s');
        $data = ['status_kunci' => $kunci, 'updated_at' => $now];
        if ($kunci === 1) {
            $data['locked_at'] = $now;
            $data['locked_by'] = $this->user();
            $data['unlocked_at'] = null;
            $data['unlocked_by'] = null;
        } else {
            $data['unlocked_at'] = $now;
            $data['unlocked_by'] = $this->user();
        }
        $this->db->where('kode_kunci', $kode);
        return $this->db->update('mri_kunci', $data);
    }

    // ---------------- mri_appetite ----------------

    public function getAppetite($tahun)
    {
        $this->db->select('a.*, k.kode_kat, k.nama_kat');
        $this->db->from('mri_appetite a');
        $this->db->join('mri_kategori_risiko k', 'k.id_kategori = a.id_kategori', 'left');
        $this->db->where('a.tahun', $tahun);
        $this->db->order_by('k.urut', 'ASC');
        return $this->db->get()->result();
    }

    public function getAppetiteById($id)
    {
        return $this->db->get_where('mri_appetite', ['id_appetite' => $id])->row();
    }

    public function simpanAppetite($id, $data)
    {
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = $this->user();
        if ($id) {
            $this->db->where('id_appetite', $id);
            return $this->db->update('mri_appetite', $data);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->user();
        return $this->db->insert('mri_appetite', $data);
    }

    /**
     * Daftar tahun yang sudah punya data appetite (untup dropdown filter).
     */
    public function getTahunAppetite()
    {
        $this->db->select('tahun');
        $this->db->distinct();
        $this->db->from('mri_appetite');
        $this->db->order_by('tahun', 'DESC');
        return $this->db->get()->result();
    }
}
