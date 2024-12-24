<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_amortisasi extends CI_Model
{

    public function get_amortisasi($tahun_lap)
    {
        $this->db->select('
        penyusutan_amortisasi.*, 
        amortisasi.*, 
        no_per.*, 
        amortisasi.status AS status_penyusutan');
        $this->db->from('penyusutan_amortisasi');
        $this->db->join('amortisasi', 'amortisasi.id_amortisasi = penyusutan_amortisasi.id_amortisasi', 'left');
        $this->db->join('no_per', 'amortisasi.id_no_per = no_per.id', 'left');
        $this->db->where('penyusutan_amortisasi.tahun <=', $tahun_lap);
        $this->db->where('amortisasi.grand_id', 393);
        $this->db->order_by('tanggal', 'ASC');
        $this->db->order_by('id_no_per', 'ASC');
        $this->db->order_by('amortisasi.id_amortisasi', 'ASC');

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
        $grand_ids = [393];

        foreach ($results as &$row) {
            $umur_tahun = $tahun - $row->tahun + 1;
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
                    if (in_array($row->grand_id, $grand_ids)) {
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
                        if ($row->status == 1) {
                            $nilai_buku_final = $row->rupiah - $akm_thn_ini;
                            if ($nilai_buku_final == 0 || $umur_tahun > $row->umur) {
                                $nilai_buku_final = 1;
                                $akm_thn_ini = $akm_thn_ini - 1;
                            }
                        } else {
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
                    $row->akm_thn_ini = 0;
                    $row->nilai_buku_final = $row->rupiah;
                } elseif ($umur_tahun_kurang > $row->umur) {
                    $row->nilai_buku_final = 0;
                    $row->nilai_buku_lalu = 0;
                    $row->akm_thn_lalu = $row->rupiah * 1;
                    $row->akm_thn_ini = $row->rupiah * 1;
                } else {
                    $row->pengurangan = 0;
                    $row->penambahan = 0;
                    $row->akm_thn_lalu = 0;
                    $row->akm_thn_ini = 0;
                    $row->nilai_buku_lalu = $row->rupiah * -1;
                    $row->penambahan_penyusutan = 0;
                    $row->nilai_buku_final = $row->rupiah;
                }
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
            'total_amortisasi' => [
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

    // public function get_amortisasi($tahun_lap)
    // {
    //     $this->db->select('
    //     penyusutan_amortisasi.*, 
    //     amortisasi.*, 
    //     no_per.*, 
    //     amortisasi.status AS status_penyusutan');
    //     $this->db->from('penyusutan_amortisasi');
    //     $this->db->join('amortisasi', 'amortisasi.id_amortisasi = penyusutan_amortisasi.id_amortisasi', 'left');
    //     $this->db->join('no_per', 'amortisasi.id_no_per = no_per.id', 'left');
    //     $this->db->where('penyusutan_amortisasi.tahun <=', $tahun_lap);
    //     $this->db->where('amortisasi.grand_id', 393);
    //     $this->db->order_by('tanggal', 'ASC');
    //     $this->db->order_by('id_no_per', 'ASC');
    //     $this->db->order_by('amortisasi.id_amortisasi', 'ASC');

    //     $query = $this->db->get();
    //     $results = $query->result();

    //     $tahun = $tahun_lap;
    //     if (empty($tahun)) {
    //         $tahun = date('Y');
    //     }

    //     // Inisialisasi variabel untuk menyimpan total
    //     $total_rupiah = 0;
    //     $total_nilai_buku = 0;
    //     $total_penambahan = 0;
    //     $total_pengurangan = 0;
    //     $total_akm_thn_lalu = 0;
    //     $total_nilai_buku_lalu = 0;
    //     $total_penyusutan = 0;
    //     $total_akm_thn_ini = 0;
    //     $total_nilai_buku_final = 0;

    //     // Daftar ID parent untuk bangunan
    //     $grand_ids = [393];

    //     foreach ($results as &$row) {
    //         $umur_tahun = $tahun - $row->tahun;
    //         $nilai_buku_awal = $row->rupiah; // Nilai awal aset
    //         $akm_thn_ini = 0;                 // Akumulasi penyusutan tahun ini
    //         $nilai_buku_final = $nilai_buku_awal; // Nilai buku final untuk tahun berjalan

    //         if ($umur_tahun == 0) {
    //             // Kondisi untuk umur_tahun = 0
    //             $row->akm_thn_lalu = 0;
    //             $row->nilai_buku = 0;
    //             $row->penambahan_penyusutan = 0;
    //             $row->nilai_buku_lalu = 0;
    //             $row->akm_thn_ini = 0;
    //             $row->nilai_buku_final = $nilai_buku_awal;
    //         } else {
    //             $row->pengurangan = 0;
    //             $row->penambahan = 0;
    //             // Perhitungan bertahap untuk setiap tahun sejak tahun pertama
    //             for ($i = 1; $i <= $umur_tahun; $i++) {
    //                 if ($i == 1) {
    //                     // Tahun pertama
    //                     $akm_thn_lalu = 0;
    //                     $nilai_buku_lalu = $nilai_buku_awal;
    //                 } else {
    //                     // Update nilai buku dan akumulasi penyusutan dari tahun sebelumnya
    //                     $akm_thn_lalu = $akm_thn_ini;
    //                     $nilai_buku_lalu = $nilai_buku_final;
    //                 }

    //                 // Hitung penyusutan berdasarkan kategori aset
    //                 if (in_array($row->grand_id, $grand_ids)) {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
    //                 } else {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
    //                 }

    //                 // Update akumulasi penyusutan dan nilai buku akhir
    //                 $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
    //                 $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

    //                 // Jika umur_tahun sudah mencapai umur aset, set nilai buku final menjadi 0
    //                 if ($i > $row->umur) {
    //                     $akm_thn_ini = $row->rupiah;
    //                     $akm_thn_lalu = $row->rupiah;
    //                     $nilai_buku_final = 1;
    //                     $penambahan_penyusutan = 0;
    //                     $row->penambahan = 0;
    //                     $nilai_buku_lalu = 0;
    //                     if ($row->status == 1) {
    //                         $nilai_buku_final = $row->rupiah - $akm_thn_ini;
    //                         if ($nilai_buku_final == 0 || $umur_tahun > $row->umur) {
    //                             $nilai_buku_final = 1;
    //                             $akm_thn_ini = $akm_thn_ini - 1;
    //                         }
    //                     } else {
    //                         $nilai_buku_final = -1;
    //                     }
    //                     break;
    //                 }
    //             }

    //             // Set hasil akhir setelah loop tahun selesai
    //             $row->akm_thn_lalu = $akm_thn_lalu;
    //             $row->nilai_buku_lalu = $nilai_buku_lalu;
    //             $row->penambahan_penyusutan = $penambahan_penyusutan;
    //             $row->akm_thn_ini = $akm_thn_ini;
    //             $row->nilai_buku_final = $nilai_buku_final;
    //         }

    //         if ($row->status_penyusutan == 2) {
    //             $umur_tahun = $tahun - $row->tahun_persediaan;
    //             $umur_tahun_kurang = $tahun - $row->tahun;
    //             if ($umur_tahun == 0) {
    //                 $row->nilai_buku = 0;
    //                 $row->pengurangan = $row->rupiah * -1;
    //                 $row->nilai_buku_lalu = 0;
    //                 $row->akm_thn_lalu = 0;
    //                 $row->penambahan_penyusutan = 0;
    //                 $row->akm_thn_ini = 0;
    //                 $row->nilai_buku_final = $row->rupiah;
    //             } elseif ($umur_tahun_kurang > $row->umur) {
    //                 $row->nilai_buku_final = 0;
    //                 $row->nilai_buku_lalu = 0;
    //                 $row->akm_thn_lalu = $row->rupiah * 1;
    //                 $row->akm_thn_ini = $row->rupiah * 1;
    //             } else {
    //                 $row->pengurangan = 0;
    //                 $row->penambahan = 0;
    //                 $row->akm_thn_lalu = 0;
    //                 $row->akm_thn_ini = 0;
    //                 $row->nilai_buku_lalu = $row->rupiah * -1;
    //                 $row->penambahan_penyusutan = 0;
    //                 $row->nilai_buku_final = $row->rupiah;
    //             }
    //         }

    //         // Akumulasi total dari setiap kolom
    //         $total_rupiah += $row->rupiah;
    //         $total_nilai_buku += $row->nilai_buku;
    //         $total_penambahan += $row->penambahan;
    //         $total_pengurangan += $row->pengurangan;
    //         $total_akm_thn_lalu += $row->akm_thn_lalu;
    //         $total_nilai_buku_lalu += $row->nilai_buku_lalu;
    //         $total_penyusutan += $row->penambahan_penyusutan;
    //         $total_akm_thn_ini += $row->akm_thn_ini;
    //         $total_nilai_buku_final += $row->nilai_buku_final;
    //     }

    //     // Return data beserta total
    //     return [
    //         'results' => $results,
    //         'total_amortisasi' => [
    //             'total_rupiah' => $total_rupiah,
    //             'total_nilai_buku' => $total_nilai_buku,
    //             'total_penambahan' => $total_penambahan,
    //             'total_pengurangan' => $total_pengurangan,
    //             'total_akm_thn_lalu' => $total_akm_thn_lalu,
    //             'total_nilai_buku_lalu' => $total_nilai_buku_lalu,
    //             'total_penyusutan' => $total_penyusutan,
    //             'total_akm_thn_ini' => $total_akm_thn_ini,
    //             'total_nilai_buku_final' => $total_nilai_buku_final
    //         ]
    //     ];
    // }

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
            'nama_amortisasi' => $this->input->post('nama_amortisasi', true),
            'id_bagian' => "Kantor Pusat",
            'id_no_per' => $id, // Hanya simpan id pertama
            'parent_id' => $parent_id, // Simpan parent_id
            'grand_id' => $grand_id, // Simpan grand_id
            'jenis_id' => $jenis_id, // Simpan jenis_id
            'tanggal' => $this->input->post('tanggal', true),
            'no_bukti_gd' => $this->input->post('no_bukti_gd', true),
            'no_bukti_vch' => $this->input->post('no_bukti_vch', true),
            'rupiah' => $rupiah,
            'tanggal_input' => date('Y-m-d H:i:s'),
            'input_amortisasi' => $this->session->userdata('nama_lengkap'),
            'status' => $this->input->post('status', true),
            'umur' => 5,
            'persen_susut' => 20,
            'tanggal_persediaan' => $tanggal_persediaan
        ];
        $this->db->insert('amortisasi', $data);

        // Dapatkan id_asset yang baru saja dimasukkan
        $id_amortisasi = $this->db->insert_id();

        // Tahun berjalan (tahun perolehan amortisasi)
        $tanggal_input = $this->input->post('tanggal', true);
        $tahun_amortisasi = date('Y', strtotime($tanggal_input));

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

        // Masukkan data ke tabel penyusutan_amortisasi
        $data_penyusutan = [
            'id_amortisasi' => $id_amortisasi,
            'tahun' => $tahun_amortisasi,
            'tahun_persediaan' => $tahun_persediaan,
            'penambahan' => $penambahan,  // Nilai penambahan berdasarkan status
            'penyusutan_tahun_ini' => 0,  // Tidak ada penyusutan di tahun pertama
            'akumulasi_penyusutan' => 0,  // Tidak ada akumulasi penyusutan di tahun pertama
            'nilai_buku' => $nilai_buku,  // Nilai buku sama dengan nilai perolehan di tahun pertama
            'pengurangan' => $pengurangan,  // Nilai pengurangan berdasarkan status
            'tanggal_update' => date('Y-m-d H:i:s'),
            'input_update' => $this->session->userdata('nama_lengkap')
        ];

        // Insert ke tabel penyusutan_amortisasi
        $this->db->insert('penyusutan_amortisasi', $data_penyusutan);
        return $id_amortisasi;
    }

    // public function get_bagian()
    // {
    //     $this->db->select('*');
    //     $this->db->from('bagian_upk');
    //     $this->db->where('status', 1);
    //     return $this->db->get()->result();
    // }

    public function get_perkiraan()
    {
        $this->db->select('*');
        $this->db->from('no_per');
        $this->db->where('grand_id', 393);
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

    // public function get_all($bulan, $tahun)
    // {
    //     $this->db->select(
    //         '*,
    //     (SELECT SUM(rupiah) FROM amortisasi WHERE MONTH(amortisasi.tanggal) = "' . $bulan . '" AND YEAR(amortisasi.tanggal) = "' . $tahun . '" ) AS total_rupiah'
    //     );
    //     $this->db->from('amortisasi');
    //     $this->db->join('no_per', 'amortisasi.id_no_per = no_per.id', 'left');
    //     $this->db->where('MONTH(tanggal)', $bulan);
    //     $this->db->where('YEAR(tanggal)', $tahun);
    //     return $this->db->get()->result();
    // }
}
