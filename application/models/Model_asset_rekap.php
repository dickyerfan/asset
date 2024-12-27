<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_asset_rekap extends CI_Model
{
    public function get_asset_by_grand_id($tahun, $grand_id)
    {
        $this->db->select(
            '*, 
        (SELECT SUM(rupiah) 
         FROM daftar_asset 
         WHERE daftar_asset.grand_id = ' . $grand_id . ' 
           AND daftar_asset.status = 1 
           AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 1);
        $this->db->where('daftar_asset.grand_id', $grand_id);
        $this->db->order_by('daftar_asset.tanggal');
        return $this->db->get()->result();
    }

    public function get_asset_by_grand_id_kurang($tahun, $grand_id)
    {
        $this->db->select(
            '*, 
        (SELECT SUM(rupiah) 
         FROM daftar_asset 
         WHERE daftar_asset.grand_id = ' . $grand_id . ' 
           AND daftar_asset.status = 2 
           AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 2);
        $this->db->where('daftar_asset.grand_id', $grand_id);
        $this->db->order_by('daftar_asset.tanggal');
        return $this->db->get()->result();
    }

    public function get_sr_baru($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 226 AND kode_sr = 1 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.grand_id', 226);
        $this->db->where('kode_sr', 1);
        return $this->db->get()->result();
    }
    public function get_sr_baru_rekap($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 226 AND kode_sr = 1 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah,
        (SELECT SUM(jumlah) FROM daftar_asset where daftar_asset.grand_id = 226 AND kode_sr = 1 AND YEAR(tanggal) = ' . $tahun . ') AS total_jumlah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.grand_id', 226);
        $this->db->where('kode_sr', 1);
        return $this->db->get()->result();
    }
    public function get_ganti_wm($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 226 AND kode_sr = 2 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.grand_id', 226);
        $this->db->where('kode_sr', 2);
        return $this->db->get()->result();
    }
    public function get_ganti_wm_rekap($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 226 AND kode_sr = 2 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah,
        (SELECT SUM(jumlah) FROM daftar_asset where daftar_asset.grand_id = 226 AND kode_sr = 2 AND YEAR(tanggal) = ' . $tahun . ') AS total_jumlah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.grand_id', 226);
        $this->db->where('kode_sr', 2);
        return $this->db->get()->result();
    }
    public function get_lainnya($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 226 AND kode_sr = 0 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.grand_id', 226);
        $this->db->where('kode_sr', 0);
        return $this->db->get()->result();
    }

    public function get_penyusutan($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 251 AND daftar_asset.status = 1 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 1);
        $this->db->where('daftar_asset.grand_id', 251);
        return $this->db->get()->result();
    }
}
