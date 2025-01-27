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

    public function get_all_tahun($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset WHERE YEAR(daftar_asset.tanggal) = "' . $tahun . '" AND daftar_asset.status = 1  ) AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 1);
        $this->db->group_by('daftar_asset.id_no_per');
        $this->db->order_by('daftar_asset.id_no_per');
        $this->db->order_by('daftar_asset.tanggal');
        return $this->db->get()->result();
    }

    public function get_all_tahun_cetak($tahun)
    {
        $this->db->select('
        no_per.id AS grand_id,
        no_per.name AS name,
        daftar_asset.id_asset AS id_asset,
        daftar_asset.nama_asset,
        daftar_asset.tanggal,
        daftar_asset.no_bukti_vch,
        daftar_asset.rupiah,
        bagian_upk.nama_bagian,
        bagian_upk.id_bagian,
        (SELECT SUM(rupiah) FROM daftar_asset WHERE YEAR(daftar_asset.tanggal) = "' . $tahun . '" AND daftar_asset.status = 1 ) AS total_rupiah
    ');
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.grand_id = no_per.id', 'left');
        $this->db->where('YEAR(daftar_asset.tanggal)', $tahun);
        $this->db->where('daftar_asset.status', 1);
        $this->db->order_by('daftar_asset.grand_id, daftar_asset.tanggal');
        $query = $this->db->get();
        $result = $query->result();

        // Kelompokkan data berdasarkan grand_id dan hitung total rupiah per grup
        $grouped = [];
        foreach ($result as $row) {
            if (!isset($grouped[$row->grand_id])) {
                $grouped[$row->grand_id] = [
                    'name' => $row->name,
                    'items' => [],
                    'total_rupiah_perkiraan' => 0, // Inisialisasi total rupiah
                ];
            }
            $grouped[$row->grand_id]['items'][] = $row;
            $grouped[$row->grand_id]['total_rupiah_perkiraan'] += $row->rupiah; // Akumulasi total rupiah
        }
        return $grouped;
    }

    public function get_all_tahun_perkiraan($tahun, $no_per)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset WHERE YEAR(daftar_asset.tanggal) = "' . $tahun . '" AND daftar_asset.grand_id = "' . $no_per . '" AND daftar_asset.status = 1 ) AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('daftar_asset.grand_id', $no_per);
        $this->db->where('daftar_asset.status', 1);
        $this->db->order_by('daftar_asset.id_no_per');
        $this->db->order_by('daftar_asset.tanggal');
        return $this->db->get()->result();
    }

    public function get_all_kurang_tahun($tahun)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset WHERE YEAR(daftar_asset.tanggal_persediaan) = "' . $tahun . '" AND daftar_asset.status = 2) AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal_persediaan)', $tahun);
        $this->db->where('daftar_asset.status', 2);
        $this->db->order_by('daftar_asset.id_no_per');
        $this->db->order_by('daftar_asset.tanggal');
        return $this->db->get()->result();
    }

    public function get_all_tahun_kurang_cetak($tahun)
    {
        $this->db->select('
        no_per.id AS grand_id,
        no_per.name AS name,
        daftar_asset.id_asset AS id_asset,
        daftar_asset.nama_asset,
        daftar_asset.tanggal,
        daftar_asset.no_bukti_vch,
        daftar_asset.rupiah,
        bagian_upk.nama_bagian,
        bagian_upk.id_bagian,
        (SELECT SUM(rupiah) FROM daftar_asset WHERE YEAR(daftar_asset.tanggal_persediaan) = "' . $tahun . '" AND daftar_asset.status = 2 ) AS total_rupiah
    ');
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.grand_id = no_per.id', 'left');
        $this->db->where('YEAR(daftar_asset.tanggal_persediaan)', $tahun);
        $this->db->where('daftar_asset.status', 2);
        $this->db->order_by('daftar_asset.grand_id, daftar_asset.tanggal');
        $query = $this->db->get();
        $result = $query->result();

        // Kelompokkan data berdasarkan grand_id dan hitung total rupiah per grup
        $grouped = [];
        foreach ($result as $row) {
            if (!isset($grouped[$row->grand_id])) {
                $grouped[$row->grand_id] = [
                    'name' => $row->name,
                    'items' => [],
                    'total_rupiah_perkiraan' => 0, // Inisialisasi total rupiah
                ];
            }
            $grouped[$row->grand_id]['items'][] = $row;
            $grouped[$row->grand_id]['total_rupiah_perkiraan'] += $row->rupiah; // Akumulasi total rupiah
        }
        return $grouped;
    }

    public function get_all_kurang_tahun_perkiraan($tahun, $no_per)
    {
        $this->db->select(
            '*,
        (SELECT SUM(rupiah) FROM daftar_asset WHERE YEAR(daftar_asset.tanggal_persediaan) = "' . $tahun . '" AND daftar_asset.grand_id = "' . $no_per . '" AND daftar_asset.status = 2) AS total_rupiah'
        );
        $this->db->from('daftar_asset');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('YEAR(tanggal_persediaan)', $tahun);
        $this->db->where('daftar_asset.grand_id', $no_per);
        $this->db->where('daftar_asset.status', 2);
        $this->db->order_by('daftar_asset.id_no_per');
        $this->db->order_by('daftar_asset.tanggal');
        return $this->db->get()->result();
    }

    public function get_all_kurang_akm($tahun_lap)
    {
        $this->db->select('
        penyusutan.*, 
        daftar_asset.*, 
        no_per.*, 
        daftar_asset.status AS status_penyusutan');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->join('bagian_upk', 'daftar_asset.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('penyusutan.tahun_persediaan', $tahun_lap);
        $this->db->where('daftar_asset.status', 2);
        $this->db->where('daftar_asset.grand_id !=', 218);
        $this->db->order_by('id_no_per', 'ASC');
        $this->db->order_by('daftar_asset.id_asset', 'ASC');
        $this->db->order_by('tanggal', 'ASC');

        $query = $this->db->get();
        $results = $query->result();

        $tahun = $tahun_lap;
        if (empty($tahun)) {
            $tahun = date('Y');
        }

        // Inisialisasi variabel untuk menyimpan total
        $total_rupiah = 0;
        $total_nilai_buku = 0;
        $total_penambahan = 0;
        $total_pengurangan = 0;
        $total_akm_thn_lalu = 0;
        $total_nilai_buku_lalu = 0;
        $total_penyusutan = 0;
        $total_akm_thn_ini = 0;
        $total_nilai_buku_final = 0;

        // Daftar ID parent untuk bangunan
        $parent_ids_bangunan = [1569, 1907, 2104, 2255, 2671, 2676, 2678, 2680];

        foreach ($results as &$row) {
            $umur_tahun = $tahun - $row->tahun;
            $nilai_buku_awal = $row->rupiah; // Nilai awal aset
            $akm_thn_ini = 0;                 // Akumulasi penyusutan tahun ini
            $nilai_buku_final = $nilai_buku_awal; // Nilai buku final untuk tahun berjalan

            if ($umur_tahun == 0) {
                // Kondisi untuk umur_tahun = 0
                $row->akm_thn_lalu = 0;
                $row->nilai_buku = 0;
                $row->penambahan_penyusutan = 0;
                $row->nilai_buku_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_final = $nilai_buku_awal;
            } else {
                $row->pengurangan = 0;
                $row->penambahan = 0;
                // Perhitungan bertahap untuk setiap tahun sejak tahun pertama
                for ($i = 1; $i <= $umur_tahun; $i++) {
                    if ($i == 1) {
                        // Tahun pertama
                        $akm_thn_lalu = 0;
                        $nilai_buku_lalu = $nilai_buku_awal;
                    } else {
                        // Update nilai buku dan akumulasi penyusutan dari tahun sebelumnya
                        $akm_thn_lalu = $akm_thn_ini;
                        $nilai_buku_lalu = $nilai_buku_final;
                    }

                    // Hitung penyusutan berdasarkan kategori aset
                    if (in_array($row->parent_id, $parent_ids_bangunan)) {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
                    } else {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
                    }

                    // Update akumulasi penyusutan dan nilai buku akhir
                    $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
                    $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

                    // Jika umur_tahun sudah mencapai umur aset, set nilai buku final menjadi 0
                    if ($i > $row->umur) {
                        $akm_thn_ini = $row->rupiah;
                        $akm_thn_lalu = $row->rupiah;
                        $nilai_buku_final = 1;
                        $penambahan_penyusutan = 0;
                        $row->penambahan = 0;
                        $nilai_buku_lalu = 0;
                        if ($row->status_penyusutan == 1) {
                            $nilai_buku_final = $row->rupiah - $akm_thn_ini;
                            if ($nilai_buku_final == 0 || $umur_tahun > $row->umur) {
                                $nilai_buku_final = 1;
                                $akm_thn_ini = $akm_thn_ini - 1;
                            }
                        } else {
                            $akm_thn_ini = $akm_thn_ini + 1;
                            $nilai_buku_final = -1;
                        }
                        break;
                    }
                }

                // Set hasil akhir setelah loop tahun selesai
                $row->akm_thn_lalu = $akm_thn_lalu;
                $row->nilai_buku_lalu = $nilai_buku_lalu;
                $row->penambahan_penyusutan = $penambahan_penyusutan;
                $row->akm_thn_ini = $akm_thn_ini;
                $row->nilai_buku_final = $nilai_buku_final;
            }

            if ($row->status_penyusutan == 2) {
                $umur_tahun = $tahun - $row->tahun_persediaan;
                $umur_tahun_kurang = $tahun - $row->tahun;
                if ($umur_tahun == 0) {
                    $row->nilai_buku = 0;
                    $row->pengurangan = $row->rupiah * -1;
                    $row->nilai_buku_lalu = 0;
                    $row->akm_thn_lalu = 0;
                    $row->penambahan_penyusutan = 0;
                    $row->nilai_buku_final = $nilai_buku_final;
                } else {
                    for ($i = 1; $i <= $umur_tahun_kurang; $i++) {
                        $row->pengurangan = 0;
                        $row->penambahan = 0;
                        $akm_thn_lalu = $akm_thn_ini;
                        $nilai_buku_lalu = $nilai_buku_final;
                    }
                    if (in_array($row->parent_id, $parent_ids_bangunan)) {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
                    } else {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
                    }
                    $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
                    $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

                    if ($i > $row->umur) {
                        $row->pengurangan = 0;
                        $row->penambahan = 0;
                        $akm_thn_lalu = $row->rupiah;
                        $nilai_buku_lalu = 0;
                        $penambahan_penyusutan = 0;
                    }
                }
            }


            // Kondisi khusus untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = 0;
                $row->nilai_buku_final = $row->rupiah;
            }

            // Akumulasi total dari setiap kolom
            $total_rupiah += $row->rupiah;
            $total_nilai_buku += $row->nilai_buku;
            $total_penambahan += $row->penambahan;
            $total_pengurangan += $row->pengurangan;
            $total_akm_thn_lalu += $row->akm_thn_lalu;
            $total_nilai_buku_lalu += $row->nilai_buku_lalu;
            $total_penyusutan += $row->penambahan_penyusutan;
            $total_akm_thn_ini += $row->akm_thn_ini;
            $total_nilai_buku_final += $row->nilai_buku_final;
        }

        // Return data beserta total
        return [
            'results' => $results,
            'totals' => [
                'total_rupiah' => $total_rupiah,
                'total_nilai_buku' => $total_nilai_buku,
                'total_penambahan' => $total_penambahan,
                'total_pengurangan' => $total_pengurangan,
                'total_akm_thn_lalu' => $total_akm_thn_lalu,
                'total_nilai_buku_lalu' => $total_nilai_buku_lalu,
                'total_penyusutan' => $total_penyusutan,
                'total_akm_thn_ini' => $total_akm_thn_ini,
                'total_nilai_buku_final' => $total_nilai_buku_final
            ]
        ];
    }

    public function get_no_per()
    {
        $ids = [218, 220, 222, 224, 226, 228, 244, 246, 248];
        $this->db->select('*');
        $this->db->from('no_per');
        $this->db->where_in('id', $ids);
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
