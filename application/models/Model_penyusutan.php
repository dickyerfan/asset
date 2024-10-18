<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_penyusutan extends CI_Model
{

    public function get_all($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->order_by('tanggal', 'ASC');

        $query = $this->db->get();
        $results = $query->result();

        $tahun = $tahun_lap;
        if (empty($tahun)) {
            $tahun = date('Y');
        }
        foreach ($results as &$row) {
            $row->penambahan_penyusutan = ($row->persen_susut / 100) * $row->rupiah;
            $umur_tahun = $tahun - $row->tahun;

            if ($umur_tahun > $row->umur) {
                $row->akm_thn_ini = $row->umur * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = $row->rupiah;
                $row->nilai_buku_lalu = $row->nilai_buku;
            } else {
                $row->akm_thn_ini = ($tahun - $row->tahun) * $row->penambahan_penyusutan;
                $row->akm_thn_lalu =  (($tahun - $row->tahun) * $row->penambahan_penyusutan) - $row->penambahan_penyusutan;
                $row->nilai_buku_lalu = $row->nilai_buku - $row->akm_thn_lalu;
            }

            if ($row->tahun == $tahun) {
                $row->akm_thn_lalu = 0;
                $row->nilai_buku = 0;
                $row->penambahan_penyusutan = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
            } else {
                $row->penambahan = 0;
            }
            // $row->nilai_buku_lalu = $row->rupiah - $row->akm_thn_lalu;
            $row->nilai_buku_final = $row->rupiah - $row->akm_thn_ini;

            if ($row->nilai_buku_final == 0) {
                $row->nilai_buku_final = 1;
            }
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
            }
        }
        return $results;
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
            'umur' => $this->input->post('umur', true),
            'persen_susut' => $this->input->post('persen_susut', true),
        ];
        $this->db->insert('daftar_asset', $data);

        // Dapatkan id_asset yang baru saja dimasukkan
        $id_asset = $this->db->insert_id();

        // Tahun berjalan (tahun perolehan aset)
        $tahun_ini = date('Y');

        // Masukkan data ke tabel penyusutan_asset
        $data_penyusutan = [
            'id_asset' => $id_asset,
            'tahun' => $tahun_ini,
            // 'persen_susut' => $this->input->post('persen_susut'),
            'penambahan' => $this->input->post('rupiah'),  // Penambahan adalah nilai perolehan aset
            'penyusutan_tahun_ini' => 0,  // Tidak ada penyusutan di tahun pertama
            'akumulasi_penyusutan' => 0,  // Tidak ada akumulasi penyusutan di tahun pertama
            'nilai_buku' => $this->input->post('rupiah'),  // Nilai buku sama dengan nilai perolehan di tahun pertama
            'pengurangan' => 0  // Tidak ada pengurangan di tahun pertama
        ];

        // Insert ke tabel penyusutan_asset
        $this->db->insert('penyusutan', $data_penyusutan);
        return $id_asset;
    }


    public function get_bagian()
    {
        $this->db->select('*');
        $this->db->from('bagian_upk');
        $this->db->where('status', 1);
        return $this->db->get()->result();
    }

    public function get_perkiraan()
    {
        $this->db->select('*');
        $this->db->from('no_per');
        $this->db->order_by('kode', 'ASC');
        return $this->db->get()->result();
    }
}
