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
}
