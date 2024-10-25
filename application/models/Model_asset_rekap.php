<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_asset_rekap extends CI_Model
{

    public function get_tanah($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 218 AND daftar_asset.status = 1 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 1);
        $this->db->where('daftar_asset.grand_id', 218);
        return $this->db->get()->result();
    }

    public function get_sumber($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 220 AND daftar_asset.status = 1 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 1);
        $this->db->where('daftar_asset.grand_id', 220);
        return $this->db->get()->result();
    }

    public function get_sumber_kurang($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 220 AND daftar_asset.status = 2 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 2);
        $this->db->where('daftar_asset.grand_id', 220);
        return $this->db->get()->result();
    }

    public function get_pompa($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 222 AND daftar_asset.status = 1 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 1);
        $this->db->where('daftar_asset.grand_id', 222);
        return $this->db->get()->result();
    }

    public function get_pompa_kurang($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 222 AND daftar_asset.status = 2 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 2);
        $this->db->where('daftar_asset.grand_id', 222);
        return $this->db->get()->result();
    }

    public function get_olah_air($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 224 AND daftar_asset.status = 1 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 1);
        $this->db->where('daftar_asset.grand_id', 224);
        return $this->db->get()->result();
    }

    public function get_olah_air_kurang($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 224 AND daftar_asset.status = 2 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 2);
        $this->db->where('daftar_asset.grand_id', 224);
        return $this->db->get()->result();
    }

    public function get_bangunan($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 228 AND daftar_asset.status = 1 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 1);
        $this->db->where('daftar_asset.grand_id', 228);
        return $this->db->get()->result();
    }

    public function get_bangunan_kurang($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 228 AND daftar_asset.status = 2 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 2);
        $this->db->where('daftar_asset.grand_id', 228);
        return $this->db->get()->result();
    }

    public function get_peralatan($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 244 AND daftar_asset.status = 1 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 1);
        $this->db->where('daftar_asset.grand_id', 244);
        return $this->db->get()->result();
    }

    public function get_peralatan_kurang($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 244 AND daftar_asset.status = 2 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 2);
        $this->db->where('daftar_asset.grand_id', 244);
        return $this->db->get()->result();
    }

    public function get_kendaraan($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 246 AND daftar_asset.status = 1 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 1);
        $this->db->where('daftar_asset.grand_id', 246);
        return $this->db->get()->result();
    }

    public function get_kendaraan_kurang($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 246 AND daftar_asset.status = 2 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 2);
        $this->db->where('daftar_asset.grand_id', 246);
        return $this->db->get()->result();
    }

    public function get_inventaris($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 248 AND daftar_asset.status = 1 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.grand_id', 248);
        $this->db->where('daftar_asset.status', 1);
        return $this->db->get()->result();
    }
    public function get_inventaris_kurang($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 248 AND daftar_asset.status = 2 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.grand_id', 248);
        $this->db->where('daftar_asset.status', 2);
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
    public function get_trans_dist($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.grand_id = 226 AND daftar_asset.status = 1 AND YEAR(tanggal) = ' . $tahun . ') AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 1);
        $this->db->where('daftar_asset.grand_id', 226);
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

    // public function tambah_asset()
    // {

    //     // Ambil value dari 'id_no_per' dan pisahkan berdasarkan koma
    //     $id_no_per = $this->input->post('id_no_per', true);
    //     $id_no_per_parts = explode(',', $id_no_per);

    //     // Pastikan ada 4 bagian dari value yang dipisahkan
    //     if (count($id_no_per_parts) == 4) {
    //         $id = $id_no_per_parts[0];          // Ambil id pertama
    //         $parent_id = $id_no_per_parts[1];    // Ambil parent_id kedua
    //         $grand_id = $id_no_per_parts[2];     // Ambil grand_id ketiga
    //         $jenis_id = $id_no_per_parts[3];     // Ambil jenis_id keempat
    //     } else {
    //         // Atur nilai default jika value tidak sesuai
    //         $id = $parent_id = $grand_id = $jenis_id = null;
    //     }

    //     $data = [
    //         'nama_asset' => $this->input->post('nama_asset', true),
    //         'id_bagian' => $this->input->post('id_bagian', true),
    //         'id_no_per' => $id, // Hanya simpan id pertama
    //         'parent_id' => $parent_id, // Simpan parent_id
    //         'grand_id' => $grand_id, // Simpan grand_id
    //         'jenis_id' => $jenis_id, // Simpan jenis_id
    //         'tanggal' => $this->input->post('tanggal', true),
    //         'no_bukti_gd' => $this->input->post('no_bukti_gd', true),
    //         'no_bukti_vch' => $this->input->post('no_bukti_vch', true),
    //         'rupiah' => $this->input->post('rupiah', true),
    //         'jumlah' => $this->input->post('jumlah', true),
    //         'tanggal_input' => date('Y-m-d H:i:s'),
    //         'input_asset' => $this->session->userdata('nama_lengkap'),
    //         'kode_sr' => $this->input->post('kode_sr', true),
    //     ];
    //     $this->db->insert('daftar_asset', $data);
    // }

    // public function get_bagian()
    // {
    //     $this->db->select('*');
    //     $this->db->from('bagian_upk');
    //     return $this->db->get()->result();
    // }

    // public function get_perkiraan()
    // {
    //     $this->db->select('*');
    //     $this->db->from('no_per');
    //     $this->db->order_by('kode', 'ASC');
    //     return $this->db->get()->result();
    // }

}
