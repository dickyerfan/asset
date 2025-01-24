<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_penyusutan extends CI_Model
{
    public function get_all($tahun_lap)
    {
        $this->db->select('
            penyusutan.*, 
            daftar_asset.*, 
            no_per.*, 
            daftar_asset.status AS status_penyusutan');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
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

    public function get_all_kurang($tahun_lap)
    {
        $this->db->select('
            penyusutan.*, 
            daftar_asset.*, 
            no_per.*, 
            daftar_asset.status AS status_penyusutan');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('penyusutan.tahun_persediaan', $tahun_lap);
        $this->db->where('daftar_asset.status', 2);
        // $this->db->where('daftar_asset.id_bagian !=', 23);
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

    // public function get_all($tahun_lap)
    // {
    //     $this->db->select('*');
    //     $this->db->from('penyusutan');
    //     $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
    //     $this->db->where('penyusutan.tahun <=', $tahun_lap);
    //     // $this->db->order_by('grand_id', 'ASC');
    //     // $this->db->order_by('tanggal', 'ASC');
    //     // $this->db->order_by('id_penyusutan', 'ASC');
    //     $this->db->order_by('id_no_per', 'ASC');
    //     $this->db->order_by('daftar_asset.id_asset', 'ASC');
    //     // $this->db->order_by('tanggal', 'ASC');

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
    //     $parent_ids_bangunan = [1569, 1907, 2104, 2255, 2671, 2676, 2678, 2680];

    //     foreach ($results as &$row) {
    //         $row->penambahan_penyusutan = 0;
    //         $row->akm_thn_lalu = 0;
    //         $row->akm_thn_ini = 0;
    //         $row->nilai_buku_lalu = 0;
    //         $row->nilai_buku_final = 0;
    //         // $row->penambahan_penyusutan = ($row->persen_susut / 100) * $row->rupiah;
    //         $umur_tahun = $tahun - $row->tahun;
    //         $is_bangunan = in_array($row->parent_id, $parent_ids_bangunan);

    //         // Tentukan dasar penyusutan
    //         if ($is_bangunan) {
    //             // Penyusutan berdasarkan harga perolehan
    //             $row->penambahan_penyusutan = ($row->persen_susut / 100) * $row->rupiah;
    //         } else {
    //             // Penyusutan berdasarkan nilai buku tahun lalu
    //             $row->penambahan_penyusutan = ($row->persen_susut / 100) * ($row->rupiah - ($umur_tahun - 1) * $row->penambahan_penyusutan);
    //         }

    //         // menghitung jika umur aset melebihi umur aset/nilai buku = 0
    //         if ($umur_tahun > $row->umur) {
    //             $row->penambahan_penyusutan = 0;
    //             $row->akm_thn_lalu = $row->rupiah;
    //             $row->akm_thn_ini = $row->rupiah;
    //             $row->nilai_buku_lalu = 0;
    //         } else {
    //             // Hitung akumulasi penyusutan tahun lalu dan tahun ini/nilai buku masih ada
    //             $row->akm_thn_ini = $umur_tahun * $row->penambahan_penyusutan;
    //             $row->akm_thn_lalu = ($umur_tahun - 1) * $row->penambahan_penyusutan;
    //             $row->nilai_buku_lalu = $row->rupiah - $row->akm_thn_lalu;
    //         }

    //         // hitung nilai buku tahun ini
    //         if ($row->status == 1) {
    //             $row->nilai_buku_final = $row->rupiah - $row->akm_thn_ini;
    //             if ($row->nilai_buku_final == 0 || $umur_tahun > $row->umur) {
    //                 $row->nilai_buku_final = 1;
    //             }
    //         } else {
    //             $row->nilai_buku_final = -1;
    //         }

    //         // menghitung untuk nilai tahun saat ini
    //         if ($row->tahun == $tahun) {
    //             $row->akm_thn_lalu = 0;
    //             $row->nilai_buku = 0;
    //             $row->penambahan_penyusutan = 0;
    //             $row->nilai_buku_lalu = $row->nilai_buku;
    //             $row->akm_thn_ini = 0;
    //         } else {
    //             $row->penambahan = 0;
    //             $row->pengurangan = 0;
    //         }

    //         // menghitung nilai untuk tanah
    //         if ($row->grand_id == 218) {
    //             $row->akm_thn_lalu = 0;
    //             $row->akm_thn_ini = 0;
    //             $row->nilai_buku_lalu = 0;
    //             $row->nilai_buku_final = $row->rupiah;
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
    //         'totals' => [
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

    // public function get_total_semua($tahun_lap)
    // {
    //     // $this->db->select('*');
    //     $this->db->select('penyusutan.*, daftar_asset.*, no_per.name');
    //     $this->db->from('penyusutan');
    //     $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
    //     $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
    //     $this->db->where('penyusutan.tahun <=', $tahun_lap);

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
    //     $parent_ids_bangunan = [1569, 1907, 2104, 2255, 2671, 2676, 2678, 2680];

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
    //             $row->nilai_buku_lalu = $nilai_buku_final;
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
    //                 if (in_array($row->parent_id, $parent_ids_bangunan)) {
    //                     $penambahan_penyusutan = ($row->persen_susut / 100) * $nilai_buku_awal;
    //                 } else {
    //                     $penambahan_penyusutan = ($row->persen_susut / 100) * $nilai_buku_lalu;
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

    //         // Kondisi khusus untuk tanah
    //         if ($row->grand_id == 218) {
    //             $row->akm_thn_lalu = 0;
    //             $row->akm_thn_ini = 0;
    //             $row->nilai_buku_lalu = $row->nilai_buku;
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
    //         'total_semua' => [
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

    public function get_tanah($tahun_lap)
    {
        $this->db->select('
        penyusutan.*, 
        daftar_asset.*, 
        no_per.*, 
        daftar_asset.status AS status_penyusutan');

        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.grand_id', 218);
        $this->db->order_by('tanggal', 'ASC');
        $this->db->order_by('id_no_per', 'ASC');
        $this->db->order_by('daftar_asset.id_asset', 'ASC');

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

            if ($row->status == 2) {
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
            'total_tanah' => [
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

    public function getUpkById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('no_per'); // Ganti dengan nama tabel yang sesuai
        return $query->row(); // Mengembalikan satu baris hasil
    }

    public function update_persediaan($table, $data, $id_asset)
    {
        $this->db->where('id_asset', $id_asset);
        $this->db->update($table, $data);
    }

    public function get_id_asset($id_asset)
    {
        $this->db->where('id_asset', $id_asset);
        return $this->db->get('daftar_asset')->row();
    }

    public function insert_persediaan($table, $data)
    {
        $this->db->insert($table, $data);
    }
}
