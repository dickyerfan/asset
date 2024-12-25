<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_asset extends CI_Model
{

    public function get_all($bulan, $tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset WHERE MONTH(daftar_asset.tanggal) = "' . $bulan . '" AND YEAR(daftar_asset.tanggal) = "' . $tahun . '" ) AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 1);
        $this->db->order_by('daftar_asset.id_bagian');
        return $this->db->get()->result();
    }
    public function get_kurang($bulan, $tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset WHERE MONTH(daftar_asset.tanggal) = "' . $bulan . '" AND YEAR(daftar_asset.tanggal) = "' . $tahun . '" ) AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 2);
        return $this->db->get()->result();
    }
    public function get_semua_asset()
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset  ) AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->order_by('daftar_asset.tanggal');
        // $this->db->where('daftar_asset.status', 1);
        return $this->db->get()->result();
    }
    public function get_semua_asset_kurang()
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset where daftar_asset.status = 2 ) AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('daftar_asset.status', 2);
        return $this->db->get()->result();
    }

    public function tambah_asset()
    {
        date_default_timezone_set('Asia/Jakarta');
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

        // Nilai rupiah yang akan digunakan untuk penambahan/pengurangan
        $rupiah = $this->input->post('rupiah', true);
        $status = $this->input->post('status', true);

        // Tentukan nilai $rupiah
        if ($status == 2) {
            // Jika status adalah 2, nilai rupiah minus
            $rupiah = $rupiah * -1;
        } else {
            // Jika status adalah 1 (atau lainnya),nilai rupiah plus
            $rupiah = $rupiah;
        }

        $tanggal_persediaan = $this->input->post('tanggal_persediaan', true);
        if (!empty($tanggal_persediaan)) {
            $tanggal_persediaan = $this->input->post('tanggal_persediaan', true);
        } else {
            $tanggal_persediaan = null;
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
            'rupiah' => $rupiah,
            'jumlah' => $this->input->post('jumlah', true),
            'tanggal_input' => date('Y-m-d H:i:s'),
            'input_asset' => $this->session->userdata('nama_lengkap'),
            'kode_sr' => $this->input->post('kode_sr', true),
            'umur' => $this->input->post('umur', true),
            'status' => $this->input->post('status', true),
            'persen_susut' => $this->input->post('persen_susut', true),
            'tanggal_persediaan' => $tanggal_persediaan
        ];
        $this->db->insert('daftar_asset', $data);

        // Dapatkan id_asset yang baru saja dimasukkan
        $id_asset = $this->db->insert_id();

        // Tahun berjalan (tahun perolehan aset)
        $tanggal_input = $this->input->post('tanggal', true);
        $tahun_asset = date('Y', strtotime($tanggal_input));

        $tanggal_persediaan = $this->input->post('tanggal_persediaan', true);
        if (!empty($tanggal_persediaan)) {
            $tahun_persediaan = date('Y', strtotime($tanggal_persediaan));
        } else {
            $tahun_persediaan = null; // Jika tanggal_persediaan kosong, tahun_persediaan menjadi null
        }

        // Tentukan nilai penambahan dan pengurangan berdasarkan status
        if ($status == 2) {
            // Jika status adalah 2, maka penambahan adalah 0, dan pengurangan adalah nilai rupiah
            $penambahan = 0;
            $pengurangan = $rupiah * -1;
            $nilai_buku = $rupiah;
        } else {
            // Jika status adalah 1 (atau lainnya), penambahan adalah nilai rupiah, dan pengurangan adalah 0
            $penambahan = $rupiah;
            $pengurangan = 0;
            $nilai_buku = $rupiah;
        }

        // Masukkan data ke tabel penyusutan_asset
        $data_penyusutan = [
            'id_asset' => $id_asset,
            'tahun' => $tahun_asset,
            'tahun_persediaan' => $tahun_persediaan,
            'penambahan' => $penambahan,  // Nilai penambahan berdasarkan status
            'penyusutan_tahun_ini' => 0,  // Tidak ada penyusutan di tahun pertama
            'akumulasi_penyusutan' => 0,  // Tidak ada akumulasi penyusutan di tahun pertama
            'nilai_buku' => $nilai_buku,  // Nilai buku sama dengan nilai perolehan di tahun pertama
            'pengurangan' => $pengurangan,  // Nilai pengurangan berdasarkan status
            'tanggal_update' => date('Y-m-d H:i:s'),
            'input_update' => $this->session->userdata('nama_lengkap')
        ];

        // Insert ke tabel penyusutan_asset
        $this->db->insert('penyusutan', $data_penyusutan);
        return $id_asset;
    }
    // public function kurang_asset()
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
    //         'umur' => $this->input->post('umur', true),
    //         'persen_susut' => $this->input->post('persen_susut', true),
    //     ];
    //     $this->db->insert('daftar_asset', $data);

    //     // Dapatkan id_asset yang baru saja dimasukkan
    //     $id_asset = $this->db->insert_id();

    //     // Tahun berjalan (tahun perolehan aset)
    //     $tanggal_input = $this->input->post('tanggal', true);
    //     $tahun_asset = date('Y', strtotime($tanggal_input));

    //     // Masukkan data ke tabel penyusutan_asset
    //     $data_penyusutan = [
    //         'id_asset' => $id_asset,
    //         'tahun' => $tahun_asset,
    //         'penambahan' => $this->input->post('rupiah'),  // Penambahan adalah nilai perolehan aset
    //         'penyusutan_tahun_ini' => 0,  // Tidak ada penyusutan di tahun pertama
    //         'akumulasi_penyusutan' => 0,  // Tidak ada akumulasi penyusutan di tahun pertama
    //         'nilai_buku' => $this->input->post('rupiah'),  // Nilai buku sama dengan nilai perolehan di tahun pertama
    //         'pengurangan' => 0  // Tidak ada pengurangan di tahun pertama
    //     ];

    //     // Insert ke tabel penyusutan_asset
    //     $this->db->insert('penyusutan', $data_penyusutan);
    //     return $id_asset;
    // }


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

    public function update_umur_dan_persen_susut($id_asset)
    {
        date_default_timezone_set('Asia/Jakarta');
        $data = [
            'umur' => 0,
            'persen_susut' => 0,
            'tanggal_input' => date('Y-m-d H:i:s'),
            'input_asset' => $this->session->userdata('nama_lengkap'),
            'status_update' => 1
        ];
        $this->db->where('id_asset', $id_asset);
        $this->db->update('daftar_asset', $data);
    }

    public function get_asset_by_id($id_asset)
    {
        return $this->db->get_where('daftar_asset', ['id_asset' => $id_asset])->row();
    }

    // public function hapusData($id)
    // {
    //     $this->db->where('id_jabatan', $id);
    //     $this->db->delete('jabatan');
    // }
}
