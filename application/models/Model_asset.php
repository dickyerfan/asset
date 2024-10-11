<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_asset extends CI_Model
{

    public function get_all($bulan, $tahun)
    {
        // $this->db->select('*');
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset WHERE MONTH(daftar_asset.tanggal) = "' . $bulan . '" AND YEAR(daftar_asset.tanggal) = "' . $tahun . '" ) AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        return $this->db->get()->result();
    }

    public function tambah_asset()
    {

        // Ambil value dari 'id_no_per' dan pisahkan berdasarkan koma
        $id_no_per = $this->input->post('id_no_per', true);
        $id_no_per_parts = explode(',', $id_no_per);

        // Pastikan ada 4 bagian dari value yang dipisahkan
        if (count($id_no_per_parts) == 4) {
            $id = $id_no_per_parts[0];          // Ambil id pertama
            $parent_id = $id_no_per_parts[1];    // Ambil parent_id kedua
            $grand_id = $id_no_per_parts[2];     // Ambil grand_id ketiga
            $jenis_id = $id_no_per_parts[3];     // Ambil jenis_id keempat
        } else {
            // Atur nilai default jika value tidak sesuai
            $id = $parent_id = $grand_id = $jenis_id = null;
        }

        $data = [
            'nama_asset' => $this->input->post('nama_asset', true),
            'id_bagian' => $this->input->post('id_bagian', true),
            'id_no_per' => $id, // Hanya simpan id pertama
            'parent_id' => $parent_id, // Simpan parent_id
            'grand_id' => $grand_id, // Simpan grand_id
            'jenis_id' => $jenis_id, // Simpan jenis_id
            'tanggal' => $this->input->post('tanggal', true),
            'no_bukti_gd' => $this->input->post('no_bukti_gd', true),
            'no_bukti_vch' => $this->input->post('no_bukti_vch', true),
            'rupiah' => $this->input->post('rupiah', true),
            'jumlah' => $this->input->post('jumlah', true),
            'tanggal_input' => date('Y-m-d H:i:s'),
            'input_asset' => $this->session->userdata('nama_lengkap'),
            'kode_sr' => $this->input->post('kode_sr', true),
        ];
        $this->db->insert('daftar_asset', $data);
    }


    public function get_bagian()
    {
        $this->db->select('*');
        $this->db->from('bagian_upk');
        return $this->db->get()->result();
    }

    public function get_perkiraan()
    {
        $this->db->select('*');
        $this->db->from('no_per');
        $this->db->order_by('kode', 'ASC');
        return $this->db->get()->result();
    }

    // public function updateData()
    // {

    //     $data = [
    //         "nama_jabatan" => $this->input->post('nama_jabatan', true),
    //     ];
    //     $this->db->where('id_jabatan', $this->input->post('id'));
    //     $this->db->update('jabatan', $data);
    // }

    // public function hapusData($id)
    // {
    //     $this->db->where('id_jabatan', $id);
    //     $this->db->delete('jabatan');
    // }
}
