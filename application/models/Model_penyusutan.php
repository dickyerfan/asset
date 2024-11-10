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
        // $this->db->order_by('grand_id', 'ASC');
        // $this->db->order_by('tanggal', 'ASC');
        // $this->db->order_by('id_penyusutan', 'ASC');

        $this->db->order_by('id_no_per', 'ASC');
        $this->db->order_by('daftar_asset.id_asset', 'ASC');
        // $this->db->order_by('tanggal', 'ASC');


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
            $row->penambahan_penyusutan = 0;
            $row->akm_thn_lalu = 0;
            $row->akm_thn_ini = 0;
            $row->nilai_buku_lalu = 0;
            $row->nilai_buku_final = 0;
            // $row->penambahan_penyusutan = ($row->persen_susut / 100) * $row->rupiah;
            $umur_tahun = $tahun - $row->tahun;
            $is_bangunan = in_array($row->parent_id, $parent_ids_bangunan);

            // Tentukan dasar penyusutan
            if ($is_bangunan) {
                // Penyusutan berdasarkan harga perolehan
                $row->penambahan_penyusutan = ($row->persen_susut / 100) * $row->rupiah;
            } else {
                // Penyusutan berdasarkan nilai buku tahun lalu
                $row->penambahan_penyusutan = ($row->persen_susut / 100) * ($row->rupiah - ($umur_tahun - 1) * $row->penambahan_penyusutan);
            }

            // menghitung jika umur aset melebihi umur aset/nilai buku = 0
            if ($umur_tahun > $row->umur) {
                $row->penambahan_penyusutan = 0;
                $row->akm_thn_lalu = $row->rupiah;
                $row->akm_thn_ini = $row->rupiah;
                $row->nilai_buku_lalu = 0;
            } else {
                // Hitung akumulasi penyusutan tahun lalu dan tahun ini/nilai buku masih ada
                $row->akm_thn_ini = $umur_tahun * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = ($umur_tahun - 1) * $row->penambahan_penyusutan;
                $row->nilai_buku_lalu = $row->rupiah - $row->akm_thn_lalu;
            }

            // hitung nilai buku tahun ini
            if ($row->status == 1) {
                $row->nilai_buku_final = $row->rupiah - $row->akm_thn_ini;
                if ($row->nilai_buku_final == 0 || $umur_tahun > $row->umur) {
                    $row->nilai_buku_final = 1;
                }
            } else {
                $row->nilai_buku_final = -1;
            }

            // menghitung untuk nilai tahun saat ini
            if ($row->tahun == $tahun) {
                $row->akm_thn_lalu = 0;
                $row->nilai_buku = 0;
                $row->penambahan_penyusutan = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
                $row->akm_thn_ini = 0;
            } else {
                $row->penambahan = 0;
                $row->pengurangan = 0;
            }

            // menghitung nilai untuk tanah
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
    //     $this->db->order_by('id_penyusutan', 'ASC');
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

    //     foreach ($results as &$row) {
    //         $row->penambahan_penyusutan = ($row->persen_susut / 100) * $row->rupiah;
    //         $umur_tahun = $tahun - $row->tahun;

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
    //             $row->akm_thn_ini = $row->rupiah;
    //         } else {
    //             $row->penambahan = 0;
    //             $row->pengurangan = 0;
    //         }

    //         // menghitung nilai untuk tanah
    //         if ($row->grand_id == 218) {
    //             $row->akm_thn_lalu = 0;
    //             $row->akm_thn_ini = 0;
    //             $row->nilai_buku_lalu = $row->nilai_buku;
    //             $row->nilai_buku_final = $row->nilai_buku;
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

    public function get_tanah($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.grand_id', 218);
        $this->db->order_by('tanggal', 'ASC');
        $this->db->order_by('id_penyusutan', 'ASC');

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

        foreach ($results as &$row) {
            $row->penambahan_penyusutan = ($row->persen_susut / 100) * $row->rupiah;
            $umur_tahun = $tahun - $row->tahun;

            // menghitung jika umur aset melebihi umur aset/nilai buku = 0
            if ($umur_tahun > $row->umur) {
                $row->penambahan_penyusutan = 0;
                $row->akm_thn_lalu = $row->rupiah;
                $row->akm_thn_ini = $row->rupiah;
                $row->nilai_buku_lalu = 0;
            } else {
                // Hitung akumulasi penyusutan tahun lalu dan tahun ini/nilai buku masih ada
                $row->akm_thn_ini = $umur_tahun * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = ($umur_tahun - 1) * $row->penambahan_penyusutan;
                $row->nilai_buku_lalu = $row->rupiah - $row->akm_thn_lalu;
            }

            // hitung nilai buku tahun ini
            if ($row->status == 1) {
                $row->nilai_buku_final = $row->rupiah - $row->akm_thn_ini;
                if ($row->nilai_buku_final == 0 || $umur_tahun > $row->umur) {
                    $row->nilai_buku_final = 1;
                }
            } else {
                $row->nilai_buku_final = -1;
            }

            // menghitung untuk nilai tahun saat ini
            if ($row->tahun == $tahun) {
                $row->akm_thn_lalu = 0;
                $row->nilai_buku = 0;
                $row->penambahan_penyusutan = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
                $row->akm_thn_ini = $row->rupiah;
            } else {
                $row->penambahan = 0;
                $row->pengurangan = 0;
            }

            // menghitung nilai untuk tanah
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

    public function getUpkById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('no_per'); // Ganti dengan nama tabel yang sesuai
        return $query->row(); // Mengembalikan satu baris hasil
    }
}
