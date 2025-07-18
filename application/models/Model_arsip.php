<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_arsip extends CI_Model
{
    // public function getAll()
    // {
    //     $this->db->select('*');
    //     $this->db->from('arsip');
    //     $this->db->join('arsip_folder', 'arsip.id_folder = arsip_folder.id_folder');
    //     return $this->db->get()->result();
    // }

    public function getdetail($id)
    {
        $this->db->select('*');
        $this->db->from('arsip');
        $this->db->join('arsip_folder', 'arsip.id_folder = arsip_folder.id_folder');
        $this->db->where('id_arsip', $id);
        return $this->db->get()->row();
    }

    public function getFolder()
    {
        $this->db->select('*');
        $this->db->from('arsip_folder');
        return $this->db->get()->result();
    }

    public function getModalEska()
    {
        $this->db->select('nama_dokumen');
        $this->db->from('arsip');
        $this->db->where('jenis', 'Surat Keputusan');
        return $this->db->get()->result();
    }
    public function getModalPer()
    {
        $this->db->select('nama_dokumen');
        $this->db->from('arsip');
        $this->db->where('jenis', 'Peraturan');
        return $this->db->get()->result();
    }
    public function getModalBer()
    {
        $this->db->select('nama_dokumen');
        $this->db->from('arsip');
        $this->db->where('jenis', 'Berkas Kerja');
        return $this->db->get()->result();
    }
    public function getModalDok()
    {
        $this->db->select('nama_dokumen');
        $this->db->from('arsip');
        $this->db->where('jenis', 'Dokumen');
        return $this->db->get()->result();
    }

    public function update($data, $id_arsip)
    {
        $this->db->where('id_arsip', $id_arsip);
        return $this->db->update('arsip', $data);
    }
}
