<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_penyusutan_kendaraan extends CI_Model
{

    public function get_kendaraan($tahun_lap)
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
        $this->db->where('daftar_asset.grand_id', 246);
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
            'total_kendaraan' => [
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

    public function get_kendaraan_penumpang_total($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2850);
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

            if ($row->status == 2) {
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

    public function get_kendaraan_penumpang($tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        // $this->db->where('daftar_asset.grand_id', 228);
        $this->db->where('daftar_asset.parent_id', 2850);
        $this->db->where('daftar_asset.id_no_per', $upk_bagian);
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

            if ($row->status == 2) {
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

    public function get_kendaraan_angkut_total($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2852);
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

            if ($row->status == 2) {
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

    public function get_kendaraan_angkut($tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2852);
        $this->db->where('daftar_asset.id_no_per', $upk_bagian);
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

            if ($row->status == 2) {
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

    public function get_kendaraan_tangki_total($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2854);
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

            if ($row->status == 2) {
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

    public function get_kendaraan_tangki($tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2854);
        $this->db->where('daftar_asset.id_no_per', $upk_bagian);
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

            if ($row->status == 2) {
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

    public function get_kendaraan_roda_dua_total($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2855);
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

            if ($row->status == 2) {
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

    public function get_kendaraan_roda_dua($tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2855);
        $this->db->where('daftar_asset.id_no_per', $upk_bagian);
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

            if ($row->status == 2) {
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

    public function get_unit_kendaraan_penumpang()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 2850);
        return $this->db->get()->result();
    }
    public function get_unit_kendaraan_angkut()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 2852);
        return $this->db->get()->result();
    }
    public function get_unit_kendaraan_tangki()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 2854);
        return $this->db->get()->result();
    }

    public function get_unit_kendaraan_roda_dua()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 2844);
        return $this->db->get()->result();
    }
}
