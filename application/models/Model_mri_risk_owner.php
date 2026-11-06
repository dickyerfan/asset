<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Orang & peran dalam modul manrisk:
 *  - mri_risk_owner  : pemilik risiko per unit (sumber: mr_petugas_ttd legacy, read-only)
 *  - mri_pic         : pelaksana tindakan (sumber: mr_pemilik_risiko legacy)
 *  - mri_petugas_ttd : penandatangan laporan
 */
class Model_mri_risk_owner extends CI_Model
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

    // ---------------- mri_risk_owner ----------------

    public function getOwner($aktif_saja = true)
    {
        $this->db->select('o.*, b.nama_bagian');
        $this->db->from('mri_risk_owner o');
        $this->db->join('bagian_upk b', 'b.id_bagian = o.id_bagian', 'left');
        if ($aktif_saja) {
            $this->db->where('o.status', 1);
        }
        $this->db->order_by('b.id_bagian', 'ASC');
        return $this->db->get()->result();
    }

    public function getOwnerById($id)
    {
        return $this->db->get_where('mri_risk_owner', ['id_risk_owner' => $id])->row();
    }

    public function getOwnerByBagian($id_bagian)
    {
        $this->db->where('id_bagian', $id_bagian);
        $this->db->where('status', 1);
        return $this->db->get('mri_risk_owner')->result();
    }

    public function simpanOwner($id, $data)
    {
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = $this->user();
        if ($id) {
            $this->db->where('id_risk_owner', $id);
            return $this->db->update('mri_risk_owner', $data);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->user();
        return $this->db->insert('mri_risk_owner', $data);
    }

    /**
     * Apakah owner masih dipakai pada analisa risiko?
     * mri_analisa.id_risk_owner tidak memakai FK, jadi dijaga di sini.
     * @return int jumlah analisa yang memakai owner tersebut
     */
    public function jumlahAnalisaOwner($id_owner)
    {
        return (int) $this->db->where('id_risk_owner', (int) $id_owner)
            ->count_all_results('mri_analisa');
    }

    public function hapusOwner($id)
    {
        $this->db->where('id_risk_owner', (int) $id);
        return $this->db->delete('mri_risk_owner');
    }

    // ---------------- mri_pic ----------------

    public function getPic($aktif_saja = true)
    {
        $this->db->select('p.*, b.nama_bagian');
        $this->db->from('mri_pic p');
        $this->db->join('bagian_upk b', 'b.id_bagian = p.id_bagian', 'left');
        if ($aktif_saja) {
            $this->db->where('p.status', 1);
        }
        $this->db->order_by('p.nama_pic', 'ASC');
        return $this->db->get()->result();
    }

    public function getPicById($id)
    {
        return $this->db->get_where('mri_pic', ['id_pic' => $id])->row();
    }

    public function simpanPic($id, $data)
    {
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = $this->user();
        if ($id) {
            $this->db->where('id_pic', $id);
            return $this->db->update('mri_pic', $data);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->user();
        return $this->db->insert('mri_pic', $data);
    }

    /**
     * Apakah PIC ini masih dipakai sebagai pelaksana tindakan?
     * mri_perlakuan.id_pic tidak memakai FK, jadi penghapusan harus dijaga di sini.
     * @return int jumlah perlakuan yang memakai PIC tersebut
     */
    public function jumlahPerlakuanPic($id_pic)
    {
        return (int) $this->db->where('id_pic', (int) $id_pic)
            ->count_all_results('mri_perlakuan');
    }

    public function hapusPic($id)
    {
        $this->db->where('id_pic', (int) $id);
        return $this->db->delete('mri_pic');
    }

    // ---------------- mri_petugas_ttd ----------------

    public function getPetugasTtd($aktif_saja = true)
    {
        $this->db->select('t.*, b.nama_bagian');
        $this->db->from('mri_petugas_ttd t');
        $this->db->join('bagian_upk b', 'b.id_bagian = t.id_bagian', 'left');
        if ($aktif_saja) {
            $this->db->where('t.status', 1);
        }
        $this->db->order_by('t.kode_ttd', 'ASC');
        return $this->db->get()->result();
    }

    public function getTtdByKode($kode)
    {
        return $this->db->get_where('mri_petugas_ttd', ['kode_ttd' => $kode])->row();
    }

    public function simpanTtd($id, $data)
    {
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = $this->user();
        if ($id) {
            $this->db->where('id_ttd', $id);
            return $this->db->update('mri_petugas_ttd', $data);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->user();
        return $this->db->insert('mri_petugas_ttd', $data);
    }

    public function getTtdById($id)
    {
        return $this->db->get_where('mri_petugas_ttd', ['id_ttd' => (int) $id])->row();
    }

    /**
     * Cek kode TTD sudah dipakai baris lain (kode_ttd UNIQUE).
     * @param string   $kode
     * @param int|null $kecuali_id id_ttd yang sedang diubah
     * @return bool true = sudah dipakai
     */
    public function kodeTtdDipakai($kode, $kecuali_id = null)
    {
        $this->db->where('kode_ttd', $kode);
        if ($kecuali_id) {
            $this->db->where('id_ttd !=', (int) $kecuali_id);
        }
        return $this->db->count_all_results('mri_petugas_ttd') > 0;
    }

    public function hapusTtd($id)
    {
        $this->db->where('id_ttd', (int) $id);
        return $this->db->delete('mri_petugas_ttd');
    }
}
