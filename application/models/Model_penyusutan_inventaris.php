<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_penyusutan_inventaris extends CI_Model
{

    public function get_inventaris($tahun_lap)
    {
        $this->db->select('
        penyusutan.*, 
        daftar_asset.*, 
        no_per.*, 
        bagian_upk.*,
        daftar_asset.status AS status_penyusutan');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->join('bagian_upk', 'bagian_upk.id_bagian = daftar_asset.id_bagian', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.grand_id', 248);
        $this->db->order_by('bagian_upk.id_bagian', 'ASC');
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

                        // --- LOGIKA UNTUK TAHUN PELAPORAN SAAT INI ($tahun_lap) SAJA ---
                        // Pastikan finalisasi dan break hanya terjadi di iterasi terakhir (tahun laporan saat ini)
                        if ($i == $umur_tahun) {
                            // *** CATATAN: akm_thn_lalu dan nilai_buku_lalu sudah benar terisi dari tahun sebelumnya (2024)

                            $akm_thn_ini = $row->rupiah; // Set akumulasi awal
                            $nilai_buku_final = 1;      // Set nilai buku default
                            $penambahan_penyusutan = 0;
                            $row->penambahan = 0;
                            // Hapus baris yang menimpa akm_thn_lalu dan nilai_buku_lalu (sesuai perbaikan sebelumnya)

                            if ($row->status == 1) {
                                $nilai_buku_final = $row->rupiah - $akm_thn_ini;
                                if ($nilai_buku_final == 0 || $umur_tahun > $row->umur) {
                                    $nilai_buku_final = 1; // Nilai Buku Final = 1
                                    $akm_thn_ini = $akm_thn_ini - 1; // Akm Thn Ini = Rupiah - 1
                                }
                            } else {
                                $akm_thn_ini = $akm_thn_ini + 1;
                                $nilai_buku_final = -1;
                            }

                            break; // Hentikan loop HANYA di tahun pelaporan
                        }

                        // --- LOGIKA UNTUK TAHUN HISTORIS SEBELUM TAHUN LAPORAN (i > umur TAPI i < umur_tahun) ---
                        // Jika aset sudah habis umur, tetapi belum mencapai tahun laporan, kunci nilainya.
                        $penambahan_penyusutan = 0;

                        // Kunci akumulasi dan nilai buku (asumsi nilai buku residu 1)
                        $akm_thn_ini = $row->rupiah - 1;
                        $nilai_buku_final = 1;

                        // TIDAK ADA 'break;' di sini. Loop akan terus berjalan hingga i mencapai umur_tahun.
                    }
                }

                // Set hasil akhir setelah loop tahun selesai
                $row->akm_thn_lalu = $akm_thn_lalu;
                $row->nilai_buku_lalu = $nilai_buku_lalu;
                $row->penambahan_penyusutan = $penambahan_penyusutan;
                $row->akm_thn_ini = $akm_thn_ini;
                $row->nilai_buku_final = $nilai_buku_final;
            }

            // if ($row->status == 2) {

            //     // Tahun aset mulai dihentikan penyusutannya
            //     $tahun_hapus = (int)$row->tahun_persediaan;

            //     // Jika tahun_persediaan di DB kosong/null → fallback ke tahun perolehan
            //     if (!$tahun_hapus || $tahun_hapus == 0) {
            //         $tahun_hapus = (int)$row->tahun;
            //     }

            //     // RAPEL: Tahun laporan (input)
            //     $tahun_lap = (int)$tahun;

            //     // NILAI AWAL
            //     $rupiah = $row->rupiah;

            //     // -------------------------------------------------------
            //     // 1. TAHUN SEBELUM PENGHAPUSAN → PERHITUNGAN NORMAL
            //     // (jangan ganggu, biarkan logika normal yang sudah Anda buat)
            //     // -------------------------------------------------------
            //     if ($tahun_lap < $tahun_hapus) {
            //         // Perhitungan normal tetap jalan → TIDAK menimpa hasil
            //         continue;
            //     }

            //     // -------------------------------------------------------
            //     // 2. TAHUN PENGHAPUSAN (tahun_lap == tahun_persediaan)
            //     // -------------------------------------------------------
            //     if ($tahun_lap == $tahun_hapus) {

            //         $row->akm_thn_lalu = 0;             // sebelum dihapus
            //         $row->nilai_buku_lalu = $rupiah;    // NB sblm dihapus
            //         $row->penambahan_penyusutan = 0;
            //         $row->pengurangan = -$rupiah;       // penghapusan aset
            //         $row->akm_thn_ini = $rupiah;        // akm penuh
            //         $row->nilai_buku_final = 0;         // NB habis
            //         $row->penambahan = 0;

            //         continue;
            //     }

            //     // -------------------------------------------------------
            //     // 3. TAHUN SETELAH PENGHAPUSAN (tahun_lap > tahun_hapus)
            //     // FIX: TIDAK ADA BLOK NEGATIF, TIDAK BERTAMBAH LAGI
            //     // -------------------------------------------------------
            //     if ($tahun_lap > $tahun_hapus) {

            //         $row->akm_thn_lalu = $rupiah + 1;       // tetap (tidak berubah)
            //         $row->nilai_buku_lalu = -1;
            //         $row->penambahan_penyusutan = 0;
            //         $row->pengurangan = 0;              // penghapusan hanya sekali!
            //         $row->akm_thn_ini = $rupiah + 1;        // tetap (tidak berubah)
            //         $row->nilai_buku_final = -1;         // NB tetap 0
            //         $row->penambahan = 0;

            //         continue;
            //     }
            // }

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
            'total_inventaris' => [
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

    public function get_inventaris_non_amdk($tahun_lap)
    {
        $this->db->select('
        penyusutan.*, 
        daftar_asset.*, 
        no_per.*, 
        bagian_upk.*,
        daftar_asset.status AS status_penyusutan');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->join('bagian_upk', 'bagian_upk.id_bagian = daftar_asset.id_bagian', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.grand_id', 248);
        $this->db->where('daftar_asset.id_bagian !=', 23);
        $this->db->order_by('bagian_upk.id_bagian', 'ASC');
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

                    if ($i > $row->umur) {

                        // --- LOGIKA UNTUK TAHUN PELAPORAN SAAT INI ($tahun_lap) SAJA ---
                        // Pastikan finalisasi dan break hanya terjadi di iterasi terakhir (tahun laporan saat ini)
                        if ($i == $umur_tahun) {
                            // *** CATATAN: akm_thn_lalu dan nilai_buku_lalu sudah benar terisi dari tahun sebelumnya (2024)

                            $akm_thn_ini = $row->rupiah; // Set akumulasi awal
                            $nilai_buku_final = 1;      // Set nilai buku default
                            $penambahan_penyusutan = 0;
                            $row->penambahan = 0;
                            // Hapus baris yang menimpa akm_thn_lalu dan nilai_buku_lalu (sesuai perbaikan sebelumnya)

                            if ($row->status == 1) {
                                $nilai_buku_final = $row->rupiah - $akm_thn_ini;
                                if ($nilai_buku_final == 0 || $umur_tahun > $row->umur) {
                                    $nilai_buku_final = 1; // Nilai Buku Final = 1
                                    $akm_thn_ini = $akm_thn_ini - 1; // Akm Thn Ini = Rupiah - 1
                                }
                            } else {
                                $akm_thn_ini = $akm_thn_ini + 1;
                                $nilai_buku_final = -1;
                            }

                            break; // Hentikan loop HANYA di tahun pelaporan
                        }

                        // --- LOGIKA UNTUK TAHUN HISTORIS SEBELUM TAHUN LAPORAN (i > umur TAPI i < umur_tahun) ---
                        // Jika aset sudah habis umur, tetapi belum mencapai tahun laporan, kunci nilainya.
                        $penambahan_penyusutan = 0;

                        // Kunci akumulasi dan nilai buku (asumsi nilai buku residu 1)
                        $akm_thn_ini = $row->rupiah - 1;
                        $nilai_buku_final = 1;

                        // TIDAK ADA 'break;' di sini. Loop akan terus berjalan hingga i mencapai umur_tahun.
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
            'total_inventaris_non_amdk' => [
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

    public function get_inventaris_amdk($tahun_lap)
    {
        $this->db->select('
        penyusutan.*, 
        daftar_asset.*, 
        no_per.*, 
        bagian_upk.*,
        daftar_asset.status AS status_penyusutan');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->join('bagian_upk', 'bagian_upk.id_bagian = daftar_asset.id_bagian', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.grand_id', 248);
        $this->db->where('daftar_asset.id_bagian', 23);
        $this->db->order_by('bagian_upk.id_bagian', 'ASC');
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

                    if ($i > $row->umur) {

                        // --- LOGIKA UNTUK TAHUN PELAPORAN SAAT INI ($tahun_lap) SAJA ---
                        // Pastikan finalisasi dan break hanya terjadi di iterasi terakhir (tahun laporan saat ini)
                        if ($i == $umur_tahun) {
                            // *** CATATAN: akm_thn_lalu dan nilai_buku_lalu sudah benar terisi dari tahun sebelumnya (2024)

                            $akm_thn_ini = $row->rupiah; // Set akumulasi awal
                            $nilai_buku_final = 1;      // Set nilai buku default
                            $penambahan_penyusutan = 0;
                            $row->penambahan = 0;
                            // Hapus baris yang menimpa akm_thn_lalu dan nilai_buku_lalu (sesuai perbaikan sebelumnya)

                            if ($row->status == 1) {
                                $nilai_buku_final = $row->rupiah - $akm_thn_ini;
                                if ($nilai_buku_final == 0 || $umur_tahun > $row->umur) {
                                    $nilai_buku_final = 1; // Nilai Buku Final = 1
                                    $akm_thn_ini = $akm_thn_ini - 1; // Akm Thn Ini = Rupiah - 1
                                }
                            } else {
                                $akm_thn_ini = $akm_thn_ini + 1;
                                $nilai_buku_final = -1;
                            }

                            break; // Hentikan loop HANYA di tahun pelaporan
                        }

                        // --- LOGIKA UNTUK TAHUN HISTORIS SEBELUM TAHUN LAPORAN (i > umur TAPI i < umur_tahun) ---
                        // Jika aset sudah habis umur, tetapi belum mencapai tahun laporan, kunci nilainya.
                        $penambahan_penyusutan = 0;

                        // Kunci akumulasi dan nilai buku (asumsi nilai buku residu 1)
                        $akm_thn_ini = $row->rupiah - 1;
                        $nilai_buku_final = 1;

                        // TIDAK ADA 'break;' di sini. Loop akan terus berjalan hingga i mencapai umur_tahun.
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
            'total_inventaris_amdk' => [
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

    public function get_inventaris_bagian($parent_id, $tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', $parent_id);
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

                    if ($i > $row->umur) {

                        // --- LOGIKA UNTUK TAHUN PELAPORAN SAAT INI ($tahun_lap) SAJA ---
                        // Pastikan finalisasi dan break hanya terjadi di iterasi terakhir (tahun laporan saat ini)
                        if ($i == $umur_tahun) {
                            // *** CATATAN: akm_thn_lalu dan nilai_buku_lalu sudah benar terisi dari tahun sebelumnya (2024)

                            $akm_thn_ini = $row->rupiah; // Set akumulasi awal
                            $nilai_buku_final = 1;      // Set nilai buku default
                            $penambahan_penyusutan = 0;
                            $row->penambahan = 0;
                            // Hapus baris yang menimpa akm_thn_lalu dan nilai_buku_lalu (sesuai perbaikan sebelumnya)

                            if ($row->status == 1) {
                                $nilai_buku_final = $row->rupiah - $akm_thn_ini;
                                if ($nilai_buku_final == 0 || $umur_tahun > $row->umur) {
                                    $nilai_buku_final = 1; // Nilai Buku Final = 1
                                    $akm_thn_ini = $akm_thn_ini - 1; // Akm Thn Ini = Rupiah - 1
                                }
                            } else {
                                $akm_thn_ini = $akm_thn_ini + 1;
                                $nilai_buku_final = -1;
                            }

                            break; // Hentikan loop HANYA di tahun pelaporan
                        }

                        // --- LOGIKA UNTUK TAHUN HISTORIS SEBELUM TAHUN LAPORAN (i > umur TAPI i < umur_tahun) ---
                        // Jika aset sudah habis umur, tetapi belum mencapai tahun laporan, kunci nilainya.
                        $penambahan_penyusutan = 0;

                        // Kunci akumulasi dan nilai buku (asumsi nilai buku residu 1)
                        $akm_thn_ini = $row->rupiah - 1;
                        $nilai_buku_final = 1;

                        // TIDAK ADA 'break;' di sini. Loop akan terus berjalan hingga i mencapai umur_tahun.
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

                // Tahun aset mulai dihentikan penyusutannya
                $tahun_hapus = (int)$row->tahun_persediaan;

                // Jika tahun_persediaan di DB kosong/null → fallback ke tahun perolehan
                if (!$tahun_hapus || $tahun_hapus == 0) {
                    $tahun_hapus = (int)$row->tahun;
                }

                // RAPEL: Tahun laporan (input)
                $tahun_lap = (int)$tahun;

                // NILAI AWAL
                $rupiah = $row->rupiah;

                // -------------------------------------------------------
                // 1. TAHUN SEBELUM PENGHAPUSAN → PERHITUNGAN NORMAL
                // (jangan ganggu, biarkan logika normal yang sudah Anda buat)
                // -------------------------------------------------------
                if ($tahun_lap < $tahun_hapus) {
                    // Perhitungan normal tetap jalan → TIDAK menimpa hasil
                    continue;
                }

                // -------------------------------------------------------
                // 2. TAHUN PENGHAPUSAN (tahun_lap == tahun_persediaan)
                // -------------------------------------------------------
                if ($tahun_lap == $tahun_hapus) {

                    $row->akm_thn_lalu = 0;             // sebelum dihapus
                    $row->nilai_buku_lalu = $rupiah;    // NB sblm dihapus
                    $row->penambahan_penyusutan = 0;
                    $row->pengurangan = -$rupiah;       // penghapusan aset
                    $row->akm_thn_ini = $rupiah;        // akm penuh
                    $row->nilai_buku_final = 0;         // NB habis
                    $row->penambahan = 0;

                    continue;
                }

                // -------------------------------------------------------
                // 3. TAHUN SETELAH PENGHAPUSAN (tahun_lap > tahun_hapus)
                // FIX: TIDAK ADA BLOK NEGATIF, TIDAK BERTAMBAH LAGI
                // -------------------------------------------------------
                if ($tahun_lap > $tahun_hapus) {

                    $row->akm_thn_lalu = $rupiah + 1;       // tetap (tidak berubah)
                    $row->nilai_buku_lalu = -1;
                    $row->penambahan_penyusutan = 0;
                    $row->pengurangan = 0;              // penghapusan hanya sekali!
                    $row->akm_thn_ini = $rupiah + 1;        // tetap (tidak berubah)
                    $row->nilai_buku_final = -1;         // NB tetap 0
                    $row->penambahan = 0;

                    continue;
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

    public function get_inventaris_bagian_total($parent_id, $tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', $parent_id);
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

                    if ($i > $row->umur) {

                        // --- LOGIKA UNTUK TAHUN PELAPORAN SAAT INI ($tahun_lap) SAJA ---
                        // Pastikan finalisasi dan break hanya terjadi di iterasi terakhir (tahun laporan saat ini)
                        if ($i == $umur_tahun) {
                            // *** CATATAN: akm_thn_lalu dan nilai_buku_lalu sudah benar terisi dari tahun sebelumnya (2024)

                            $akm_thn_ini = $row->rupiah; // Set akumulasi awal
                            $nilai_buku_final = 1;      // Set nilai buku default
                            $penambahan_penyusutan = 0;
                            $row->penambahan = 0;
                            // Hapus baris yang menimpa akm_thn_lalu dan nilai_buku_lalu (sesuai perbaikan sebelumnya)

                            if ($row->status == 1) {
                                $nilai_buku_final = $row->rupiah - $akm_thn_ini;
                                if ($nilai_buku_final == 0 || $umur_tahun > $row->umur) {
                                    $nilai_buku_final = 1; // Nilai Buku Final = 1
                                    $akm_thn_ini = $akm_thn_ini - 1; // Akm Thn Ini = Rupiah - 1
                                }
                            } else {
                                $akm_thn_ini = $akm_thn_ini + 1;
                                $nilai_buku_final = -1;
                            }

                            break; // Hentikan loop HANYA di tahun pelaporan
                        }

                        // --- LOGIKA UNTUK TAHUN HISTORIS SEBELUM TAHUN LAPORAN (i > umur TAPI i < umur_tahun) ---
                        // Jika aset sudah habis umur, tetapi belum mencapai tahun laporan, kunci nilainya.
                        $penambahan_penyusutan = 0;

                        // Kunci akumulasi dan nilai buku (asumsi nilai buku residu 1)
                        $akm_thn_ini = $row->rupiah - 1;
                        $nilai_buku_final = 1;

                        // TIDAK ADA 'break;' di sini. Loop akan terus berjalan hingga i mencapai umur_tahun.
                    }
                }

                // Set hasil akhir setelah loop tahun selesai
                $row->akm_thn_lalu = $akm_thn_lalu;
                $row->nilai_buku_lalu = $nilai_buku_lalu;
                $row->penambahan_penyusutan = $penambahan_penyusutan;
                $row->akm_thn_ini = $akm_thn_ini;
                $row->nilai_buku_final = $nilai_buku_final;
            }

            // ==============================
            // LOGIKA PENGHAPUSAN (status = 2)
            // ==============================
            if ($row->status == 2) {

                // Tahun aset mulai dihentikan penyusutannya
                $tahun_hapus = (int)$row->tahun_persediaan;

                // Jika tahun_persediaan di DB kosong/null → fallback ke tahun perolehan
                if (!$tahun_hapus || $tahun_hapus == 0) {
                    $tahun_hapus = (int)$row->tahun;
                }

                // RAPEL: Tahun laporan (input)
                $tahun_lap = (int)$tahun;

                // NILAI AWAL
                $rupiah = $row->rupiah;

                // -------------------------------------------------------
                // 1. TAHUN SEBELUM PENGHAPUSAN → PERHITUNGAN NORMAL
                // (jangan ganggu, biarkan logika normal yang sudah Anda buat)
                // -------------------------------------------------------
                if ($tahun_lap < $tahun_hapus) {
                    // Perhitungan normal tetap jalan → TIDAK menimpa hasil
                    continue;
                }

                // -------------------------------------------------------
                // 2. TAHUN PENGHAPUSAN (tahun_lap == tahun_persediaan)
                // -------------------------------------------------------
                if ($tahun_lap == $tahun_hapus) {

                    $row->akm_thn_lalu = 0;             // sebelum dihapus
                    $row->nilai_buku_lalu = $rupiah;    // NB sblm dihapus
                    $row->penambahan_penyusutan = 0;
                    $row->pengurangan = -$rupiah;       // penghapusan aset
                    $row->akm_thn_ini = $rupiah;        // akm penuh
                    $row->nilai_buku_final = 0;         // NB habis
                    $row->penambahan = 0;

                    continue;
                }

                // -------------------------------------------------------
                // 3. TAHUN SETELAH PENGHAPUSAN (tahun_lap > tahun_hapus)
                // FIX: TIDAK ADA BLOK NEGATIF, TIDAK BERTAMBAH LAGI
                // -------------------------------------------------------
                if ($tahun_lap > $tahun_hapus) {

                    $row->akm_thn_lalu = $rupiah + 1;       // tetap (tidak berubah)
                    $row->nilai_buku_lalu = -1;
                    $row->penambahan_penyusutan = 0;
                    $row->pengurangan = 0;              // penghapusan hanya sekali!
                    $row->akm_thn_ini = $rupiah + 1;        // tetap (tidak berubah)
                    $row->nilai_buku_final = -1;         // NB tetap 0
                    $row->penambahan = 0;

                    continue;
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

    // public function get_inventaris_meubelair_total($tahun_lap)
    // {
    //     $this->db->select('*');
    //     $this->db->from('penyusutan');
    //     $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
    //     $this->db->where('penyusutan.tahun <=', $tahun_lap);
    //     $this->db->where('daftar_asset.parent_id', 2844);
    //     $this->db->order_by('id_no_per', 'ASC');
    //     $this->db->order_by('daftar_asset.id_asset', 'ASC');
    //     $this->db->order_by('tanggal', 'ASC');

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
    //                 if (in_array($row->parent_id, $parent_ids_bangunan)) {
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
    //                         $akm_thn_ini = $akm_thn_ini + 1;
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

    //         if ($row->status == 2) {
    //             $umur_tahun = $tahun - $row->tahun_persediaan;
    //             $umur_tahun_kurang = $tahun - $row->tahun;
    //             if ($umur_tahun == 0) {
    //                 $row->nilai_buku = 0;
    //                 $row->pengurangan = $row->rupiah * -1;
    //                 $row->nilai_buku_lalu = 0;
    //                 $row->akm_thn_lalu = 0;
    //                 $row->penambahan_penyusutan = 0;
    //                 $row->nilai_buku_final = $nilai_buku_final;
    //             } else {
    //                 for ($i = 1; $i <= $umur_tahun_kurang; $i++) {
    //                     $row->pengurangan = 0;
    //                     $row->penambahan = 0;
    //                     $akm_thn_lalu = $akm_thn_ini;
    //                     $nilai_buku_lalu = $nilai_buku_final;
    //                 }
    //                 if (in_array($row->parent_id, $parent_ids_bangunan)) {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
    //                 } else {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
    //                 }
    //                 $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
    //                 $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

    //                 if ($i > $row->umur) {
    //                     $row->pengurangan = 0;
    //                     $row->penambahan = 0;
    //                     $akm_thn_lalu = $row->rupiah;
    //                     $nilai_buku_lalu = 0;
    //                     $penambahan_penyusutan = 0;
    //                 }
    //             }
    //         }

    //         // Kondisi khusus untuk tanah
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

    // public function get_inventaris_meubelair($tahun_lap, $upk_bagian)
    // {
    //     $this->db->select('*');
    //     $this->db->from('penyusutan');
    //     $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
    //     $this->db->where('penyusutan.tahun <=', $tahun_lap);
    //     // $this->db->where('daftar_asset.grand_id', 228);
    //     $this->db->where('daftar_asset.parent_id', 2844);
    //     $this->db->where('daftar_asset.id_no_per', $upk_bagian);
    //     $this->db->order_by('id_no_per', 'ASC');
    //     $this->db->order_by('daftar_asset.id_asset', 'ASC');
    //     $this->db->order_by('tanggal', 'ASC');

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
    //                 if (in_array($row->parent_id, $parent_ids_bangunan)) {
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
    //                         $akm_thn_ini = $akm_thn_ini + 1;
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

    //         if ($row->status == 2) {
    //             $umur_tahun = $tahun - $row->tahun_persediaan;
    //             $umur_tahun_kurang = $tahun - $row->tahun;
    //             if ($umur_tahun == 0) {
    //                 $row->nilai_buku = 0;
    //                 $row->pengurangan = $row->rupiah * -1;
    //                 $row->nilai_buku_lalu = 0;
    //                 $row->akm_thn_lalu = 0;
    //                 $row->penambahan_penyusutan = 0;
    //                 $row->nilai_buku_final = $nilai_buku_final;
    //             } else {
    //                 for ($i = 1; $i <= $umur_tahun_kurang; $i++) {
    //                     $row->pengurangan = 0;
    //                     $row->penambahan = 0;
    //                     $akm_thn_lalu = $akm_thn_ini;
    //                     $nilai_buku_lalu = $nilai_buku_final;
    //                 }
    //                 if (in_array($row->parent_id, $parent_ids_bangunan)) {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
    //                 } else {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
    //                 }
    //                 $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
    //                 $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

    //                 if ($i > $row->umur) {
    //                     $row->pengurangan = 0;
    //                     $row->penambahan = 0;
    //                     $akm_thn_lalu = $row->rupiah;
    //                     $nilai_buku_lalu = 0;
    //                     $penambahan_penyusutan = 0;
    //                 }
    //             }
    //         }

    //         // Kondisi khusus untuk tanah
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

    // public function get_inventaris_mesin_total($tahun_lap)
    // {
    //     $this->db->select('*');
    //     $this->db->from('penyusutan');
    //     $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
    //     $this->db->where('penyusutan.tahun <=', $tahun_lap);
    //     $this->db->where('daftar_asset.parent_id', 2846);
    //     $this->db->order_by('id_no_per', 'ASC');
    //     $this->db->order_by('daftar_asset.id_asset', 'ASC');
    //     $this->db->order_by('tanggal', 'ASC');

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
    //                 if (in_array($row->parent_id, $parent_ids_bangunan)) {
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
    //                         $akm_thn_ini = $akm_thn_ini + 1;
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

    //         if ($row->status == 2) {
    //             $umur_tahun = $tahun - $row->tahun_persediaan;
    //             $umur_tahun_kurang = $tahun - $row->tahun;
    //             if ($umur_tahun == 0) {
    //                 $row->nilai_buku = 0;
    //                 $row->pengurangan = $row->rupiah * -1;
    //                 $row->nilai_buku_lalu = 0;
    //                 $row->akm_thn_lalu = 0;
    //                 $row->penambahan_penyusutan = 0;
    //                 $row->nilai_buku_final = $nilai_buku_final;
    //             } else {
    //                 for ($i = 1; $i <= $umur_tahun_kurang; $i++) {
    //                     $row->pengurangan = 0;
    //                     $row->penambahan = 0;
    //                     $akm_thn_lalu = $akm_thn_ini;
    //                     $nilai_buku_lalu = $nilai_buku_final;
    //                 }
    //                 if (in_array($row->parent_id, $parent_ids_bangunan)) {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
    //                 } else {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
    //                 }
    //                 $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
    //                 $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

    //                 if ($i > $row->umur) {
    //                     $row->pengurangan = 0;
    //                     $row->penambahan = 0;
    //                     $akm_thn_lalu = $row->rupiah;
    //                     $nilai_buku_lalu = 0;
    //                     $penambahan_penyusutan = 0;
    //                 }
    //             }
    //         }

    //         // Kondisi khusus untuk tanah
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

    // public function get_inventaris_mesin($tahun_lap, $upk_bagian)
    // {
    //     $this->db->select('*');
    //     $this->db->from('penyusutan');
    //     $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
    //     $this->db->where('penyusutan.tahun <=', $tahun_lap);
    //     $this->db->where('daftar_asset.parent_id', 2846);
    //     $this->db->where('daftar_asset.id_no_per', $upk_bagian);
    //     $this->db->order_by('id_no_per', 'ASC');
    //     $this->db->order_by('daftar_asset.id_asset', 'ASC');
    //     $this->db->order_by('tanggal', 'ASC');

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
    //                 if (in_array($row->parent_id, $parent_ids_bangunan)) {
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
    //                         $akm_thn_ini = $akm_thn_ini + 1;
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

    //         if ($row->status == 2) {
    //             $umur_tahun = $tahun - $row->tahun_persediaan;
    //             $umur_tahun_kurang = $tahun - $row->tahun;
    //             if ($umur_tahun == 0) {
    //                 $row->nilai_buku = 0;
    //                 $row->pengurangan = $row->rupiah * -1;
    //                 $row->nilai_buku_lalu = 0;
    //                 $row->akm_thn_lalu = 0;
    //                 $row->penambahan_penyusutan = 0;
    //                 $row->nilai_buku_final = $nilai_buku_final;
    //             } else {
    //                 for ($i = 1; $i <= $umur_tahun_kurang; $i++) {
    //                     $row->pengurangan = 0;
    //                     $row->penambahan = 0;
    //                     $akm_thn_lalu = $akm_thn_ini;
    //                     $nilai_buku_lalu = $nilai_buku_final;
    //                 }
    //                 if (in_array($row->parent_id, $parent_ids_bangunan)) {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
    //                 } else {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
    //                 }
    //                 $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
    //                 $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

    //                 if ($i > $row->umur) {
    //                     $row->pengurangan = 0;
    //                     $row->penambahan = 0;
    //                     $akm_thn_lalu = $row->rupiah;
    //                     $nilai_buku_lalu = 0;
    //                     $penambahan_penyusutan = 0;
    //                 }
    //             }
    //         }

    //         // Kondisi khusus untuk tanah
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

    // public function get_inventaris_rupa_total($tahun_lap)
    // {
    //     $this->db->select('*');
    //     $this->db->from('penyusutan');
    //     $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
    //     $this->db->where('penyusutan.tahun <=', $tahun_lap);
    //     $this->db->where('daftar_asset.parent_id', 2848);
    //     $this->db->order_by('id_no_per', 'ASC');
    //     $this->db->order_by('daftar_asset.id_asset', 'ASC');
    //     $this->db->order_by('tanggal', 'ASC');

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
    //                 if (in_array($row->parent_id, $parent_ids_bangunan)) {
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
    //                         $akm_thn_ini = $akm_thn_ini + 1;
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

    //         if ($row->status == 2) {
    //             $umur_tahun = $tahun - $row->tahun_persediaan;
    //             $umur_tahun_kurang = $tahun - $row->tahun;
    //             if ($umur_tahun == 0) {
    //                 $row->nilai_buku = 0;
    //                 $row->pengurangan = $row->rupiah * -1;
    //                 $row->nilai_buku_lalu = 0;
    //                 $row->akm_thn_lalu = 0;
    //                 $row->penambahan_penyusutan = 0;
    //                 $row->nilai_buku_final = $nilai_buku_final;
    //             } else {
    //                 for ($i = 1; $i <= $umur_tahun_kurang; $i++) {
    //                     $row->pengurangan = 0;
    //                     $row->penambahan = 0;
    //                     $akm_thn_lalu = $akm_thn_ini;
    //                     $nilai_buku_lalu = $nilai_buku_final;
    //                 }
    //                 if (in_array($row->parent_id, $parent_ids_bangunan)) {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
    //                 } else {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
    //                 }
    //                 $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
    //                 $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

    //                 if ($i > $row->umur) {
    //                     $row->pengurangan = 0;
    //                     $row->penambahan = 0;
    //                     $akm_thn_lalu = $row->rupiah;
    //                     $nilai_buku_lalu = 0;
    //                     $penambahan_penyusutan = 0;
    //                 }
    //             }
    //         }

    //         // Kondisi khusus untuk tanah
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

    // public function get_inventaris_rupa($tahun_lap, $upk_bagian)
    // {
    //     $this->db->select('*');
    //     $this->db->from('penyusutan');
    //     $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
    //     $this->db->where('penyusutan.tahun <=', $tahun_lap);
    //     $this->db->where('daftar_asset.parent_id', 2848);
    //     $this->db->where('daftar_asset.id_no_per', $upk_bagian);
    //     $this->db->order_by('id_no_per', 'ASC');
    //     $this->db->order_by('daftar_asset.id_asset', 'ASC');
    //     $this->db->order_by('tanggal', 'ASC');

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
    //                 if (in_array($row->parent_id, $parent_ids_bangunan)) {
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
    //                         $akm_thn_ini = $akm_thn_ini + 1;
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

    //         if ($row->status == 2) {
    //             $umur_tahun = $tahun - $row->tahun_persediaan;
    //             $umur_tahun_kurang = $tahun - $row->tahun;
    //             if ($umur_tahun == 0) {
    //                 $row->nilai_buku = 0;
    //                 $row->pengurangan = $row->rupiah * -1;
    //                 $row->nilai_buku_lalu = 0;
    //                 $row->akm_thn_lalu = 0;
    //                 $row->penambahan_penyusutan = 0;
    //                 $row->nilai_buku_final = $nilai_buku_final;
    //             } else {
    //                 for ($i = 1; $i <= $umur_tahun_kurang; $i++) {
    //                     $row->pengurangan = 0;
    //                     $row->penambahan = 0;
    //                     $akm_thn_lalu = $akm_thn_ini;
    //                     $nilai_buku_lalu = $nilai_buku_final;
    //                 }
    //                 if (in_array($row->parent_id, $parent_ids_bangunan)) {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
    //                 } else {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
    //                 }
    //                 $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
    //                 $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

    //                 if ($i > $row->umur) {
    //                     $row->pengurangan = 0;
    //                     $row->penambahan = 0;
    //                     $akm_thn_lalu = $row->rupiah;
    //                     $nilai_buku_lalu = 0;
    //                     $penambahan_penyusutan = 0;
    //                 }
    //             }
    //         }

    //         // Kondisi khusus untuk tanah
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


    public function get_unit_inventaris_meubelair()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 2844);
        return $this->db->get()->result();
    }
    public function get_unit_inventaris_mesin()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 2846);
        return $this->db->get()->result();
    }
    public function get_unit_inventaris_rupa()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 2848);
        return $this->db->get()->result();
    }
}
