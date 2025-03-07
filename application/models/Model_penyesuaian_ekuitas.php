<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_penyesuaian_ekuitas extends CI_Model
{
    // public function get_all($tahun_lap)
    // {
    //     $dua_tahun_lalu = $tahun_lap - 2;
    //     $tahun_lalu = $tahun_lap - 1;

    //     $this->db->select('*');
    //     $this->db->from('neraca');
    //     $this->db->where('kategori', 'Ekuitas');
    //     $this->db->where_in('tahun_neraca', [$dua_tahun_lalu, $tahun_lalu, $tahun_lap]); // Ambil tiga tahun sekaligus
    //     return $this->db->get()->result();
    // }

    public function get_by_year($tahun)
    {
        $this->db->select('tahun_neraca, akun, nilai_neraca');
        $this->db->from('neraca');
        $this->db->where('kategori', 'Ekuitas');
        $this->db->where('tahun_neraca', $tahun);
        return $this->db->get()->result();
    }
}
