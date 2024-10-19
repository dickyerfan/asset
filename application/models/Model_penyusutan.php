<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_penyusutan extends CI_Model
{

    // public function get_all($tahun_lap)
    // {
    //     $this->db->select('*');
    //     $this->db->from('penyusutan');
    //     $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
    //     $this->db->where('penyusutan.tahun <=', $tahun_lap);
    //     $this->db->order_by('tanggal', 'ASC');

    //     $query = $this->db->get();
    //     $results = $query->result();

    //     $tahun = $tahun_lap;
    //     if (empty($tahun)) {
    //         $tahun = date('Y');
    //     }
    //     foreach ($results as &$row) {
    //         $row->penambahan_penyusutan = ($row->persen_susut / 100) * $row->rupiah;
    //         $umur_tahun = $tahun - $row->tahun;

    //         if ($umur_tahun > $row->umur) {
    //             $row->akm_thn_ini = $row->umur * $row->penambahan_penyusutan;
    //             $row->akm_thn_lalu = $row->rupiah;
    //             $row->nilai_buku_lalu = $row->nilai_buku;
    //         } else {
    //             $row->akm_thn_ini = ($tahun - $row->tahun) * $row->penambahan_penyusutan;
    //             $row->akm_thn_lalu =  (($tahun - $row->tahun) * $row->penambahan_penyusutan) - $row->penambahan_penyusutan;
    //             $row->nilai_buku_lalu = $row->nilai_buku - $row->akm_thn_lalu;
    //         }

    //         if ($row->tahun == $tahun) {
    //             $row->akm_thn_lalu = 0;
    //             $row->nilai_buku = 0;
    //             $row->penambahan_penyusutan = 0;
    //             $row->nilai_buku_lalu = $row->nilai_buku;
    //         } else {
    //             $row->penambahan = 0;
    //         }
    //         // $row->nilai_buku_lalu = $row->rupiah - $row->akm_thn_lalu;
    //         $row->nilai_buku_final = $row->rupiah - $row->akm_thn_ini;

    //         if ($row->nilai_buku_final == 0) {
    //             $row->nilai_buku_final = 1;
    //         }
    //         if ($row->grand_id == 218) {
    //             $row->akm_thn_lalu = 0;
    //             $row->nilai_buku_lalu = $row->nilai_buku;
    //         }
    //     }
    //     return $results;
    // }


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

        // Inisialisasi variabel untuk menyimpan total
        $total_rupiah = 0;
        $total_penambahan = 0;
        $total_akm_thn_lalu = 0;
        $total_nilai_buku_lalu = 0;
        $total_penyusutan = 0;
        $total_akm_thn_ini = 0;
        $total_nilai_buku_final = 0;

        foreach ($results as &$row) {
            $row->penambahan_penyusutan = ($row->persen_susut / 100) * $row->rupiah;
            $umur_tahun = $tahun - $row->tahun;

            if ($umur_tahun > $row->umur) {
                $row->akm_thn_ini = $row->umur * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = $row->rupiah;
                $row->nilai_buku_lalu = $row->nilai_buku;
            } else {
                $row->akm_thn_ini = ($tahun - $row->tahun) * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = (($tahun - $row->tahun) * $row->penambahan_penyusutan) - $row->penambahan_penyusutan;
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

            $row->nilai_buku_final = $row->rupiah - $row->akm_thn_ini;

            if ($row->nilai_buku_final == 0) {
                $row->nilai_buku_final = 1;
            }

            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
            }

            // Akumulasi total dari setiap kolom
            $total_rupiah += $row->rupiah;
            $total_penambahan += $row->penambahan;
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
                'total_penambahan' => $total_penambahan,
                'total_akm_thn_lalu' => $total_akm_thn_lalu,
                'total_nilai_buku_lalu' => $total_nilai_buku_lalu,
                'total_penyusutan' => $total_penyusutan,
                'total_akm_thn_ini' => $total_akm_thn_ini,
                'total_nilai_buku_final' => $total_nilai_buku_final
            ]
        ];
    }

    public function get_tanah($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.grand_id', 218);
        $this->db->order_by('tanggal', 'ASC');

        $query = $this->db->get();
        $results = $query->result();

        $tahun = $tahun_lap;
        if (empty($tahun)) {
            $tahun = date('Y');
        }

        // Inisialisasi variabel untuk menyimpan total
        $total_rupiah = 0;
        $total_penambahan = 0;
        $total_akm_thn_lalu = 0;
        $total_nilai_buku_lalu = 0;
        $total_penyusutan = 0;
        $total_akm_thn_ini = 0;
        $total_nilai_buku_final = 0;

        foreach ($results as &$row) {
            $row->penambahan_penyusutan = ($row->persen_susut / 100) * $row->rupiah;
            $umur_tahun = $tahun - $row->tahun;

            if ($umur_tahun > $row->umur) {
                $row->akm_thn_ini = $row->umur * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = $row->rupiah;
                $row->nilai_buku_lalu = $row->nilai_buku;
            } else {
                $row->akm_thn_ini = ($tahun - $row->tahun) * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = (($tahun - $row->tahun) * $row->penambahan_penyusutan) - $row->penambahan_penyusutan;
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

            $row->nilai_buku_final = $row->rupiah - $row->akm_thn_ini;

            if ($row->nilai_buku_final == 0) {
                $row->nilai_buku_final = 1;
            }

            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
            }

            // Akumulasi total dari setiap kolom
            $total_rupiah += $row->rupiah;
            $total_penambahan += $row->penambahan;
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
                'total_penambahan' => $total_penambahan,
                'total_akm_thn_lalu' => $total_akm_thn_lalu,
                'total_nilai_buku_lalu' => $total_nilai_buku_lalu,
                'total_penyusutan' => $total_penyusutan,
                'total_akm_thn_ini' => $total_akm_thn_ini,
                'total_nilai_buku_final' => $total_nilai_buku_final
            ]
        ];
    }
    public function get_sumber($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.grand_id', 220);
        $this->db->order_by('tanggal', 'ASC');

        $query = $this->db->get();
        $results = $query->result();

        $tahun = $tahun_lap;
        if (empty($tahun)) {
            $tahun = date('Y');
        }

        // Inisialisasi variabel untuk menyimpan total
        $total_rupiah = 0;
        $total_penambahan = 0;
        $total_akm_thn_lalu = 0;
        $total_nilai_buku_lalu = 0;
        $total_penyusutan = 0;
        $total_akm_thn_ini = 0;
        $total_nilai_buku_final = 0;

        foreach ($results as &$row) {
            $row->penambahan_penyusutan = ($row->persen_susut / 100) * $row->rupiah;
            $umur_tahun = $tahun - $row->tahun;

            if ($umur_tahun > $row->umur) {
                $row->akm_thn_ini = $row->umur * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = $row->rupiah;
                $row->nilai_buku_lalu = $row->nilai_buku;
            } else {
                $row->akm_thn_ini = ($tahun - $row->tahun) * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = (($tahun - $row->tahun) * $row->penambahan_penyusutan) - $row->penambahan_penyusutan;
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

            $row->nilai_buku_final = $row->rupiah - $row->akm_thn_ini;

            if ($row->nilai_buku_final == 0) {
                $row->nilai_buku_final = 1;
            }

            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
            }

            // Akumulasi total dari setiap kolom
            $total_rupiah += $row->rupiah;
            $total_penambahan += $row->penambahan;
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
                'total_penambahan' => $total_penambahan,
                'total_akm_thn_lalu' => $total_akm_thn_lalu,
                'total_nilai_buku_lalu' => $total_nilai_buku_lalu,
                'total_penyusutan' => $total_penyusutan,
                'total_akm_thn_ini' => $total_akm_thn_ini,
                'total_nilai_buku_final' => $total_nilai_buku_final
            ]
        ];
    }
    public function get_pompa($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.grand_id', 222);
        $this->db->order_by('tanggal', 'ASC');

        $query = $this->db->get();
        $results = $query->result();

        $tahun = $tahun_lap;
        if (empty($tahun)) {
            $tahun = date('Y');
        }

        // Inisialisasi variabel untuk menyimpan total
        $total_rupiah = 0;
        $total_penambahan = 0;
        $total_akm_thn_lalu = 0;
        $total_nilai_buku_lalu = 0;
        $total_penyusutan = 0;
        $total_akm_thn_ini = 0;
        $total_nilai_buku_final = 0;

        foreach ($results as &$row) {
            $row->penambahan_penyusutan = ($row->persen_susut / 100) * $row->rupiah;
            $umur_tahun = $tahun - $row->tahun;

            if ($umur_tahun > $row->umur) {
                $row->akm_thn_ini = $row->umur * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = $row->rupiah;
                $row->nilai_buku_lalu = $row->nilai_buku;
            } else {
                $row->akm_thn_ini = ($tahun - $row->tahun) * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = (($tahun - $row->tahun) * $row->penambahan_penyusutan) - $row->penambahan_penyusutan;
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

            $row->nilai_buku_final = $row->rupiah - $row->akm_thn_ini;

            if ($row->nilai_buku_final == 0) {
                $row->nilai_buku_final = 1;
            }

            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
            }

            // Akumulasi total dari setiap kolom
            $total_rupiah += $row->rupiah;
            $total_penambahan += $row->penambahan;
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
                'total_penambahan' => $total_penambahan,
                'total_akm_thn_lalu' => $total_akm_thn_lalu,
                'total_nilai_buku_lalu' => $total_nilai_buku_lalu,
                'total_penyusutan' => $total_penyusutan,
                'total_akm_thn_ini' => $total_akm_thn_ini,
                'total_nilai_buku_final' => $total_nilai_buku_final
            ]
        ];
    }
    public function get_olah_air($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.grand_id', 222);
        $this->db->order_by('tanggal', 'ASC');

        $query = $this->db->get();
        $results = $query->result();

        $tahun = $tahun_lap;
        if (empty($tahun)) {
            $tahun = date('Y');
        }

        // Inisialisasi variabel untuk menyimpan total
        $total_rupiah = 0;
        $total_penambahan = 0;
        $total_akm_thn_lalu = 0;
        $total_nilai_buku_lalu = 0;
        $total_penyusutan = 0;
        $total_akm_thn_ini = 0;
        $total_nilai_buku_final = 0;

        foreach ($results as &$row) {
            $row->penambahan_penyusutan = ($row->persen_susut / 100) * $row->rupiah;
            $umur_tahun = $tahun - $row->tahun;

            if ($umur_tahun > $row->umur) {
                $row->akm_thn_ini = $row->umur * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = $row->rupiah;
                $row->nilai_buku_lalu = $row->nilai_buku;
            } else {
                $row->akm_thn_ini = ($tahun - $row->tahun) * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = (($tahun - $row->tahun) * $row->penambahan_penyusutan) - $row->penambahan_penyusutan;
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

            $row->nilai_buku_final = $row->rupiah - $row->akm_thn_ini;

            if ($row->nilai_buku_final == 0) {
                $row->nilai_buku_final = 1;
            }

            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
            }

            // Akumulasi total dari setiap kolom
            $total_rupiah += $row->rupiah;
            $total_penambahan += $row->penambahan;
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
                'total_penambahan' => $total_penambahan,
                'total_akm_thn_lalu' => $total_akm_thn_lalu,
                'total_nilai_buku_lalu' => $total_nilai_buku_lalu,
                'total_penyusutan' => $total_penyusutan,
                'total_akm_thn_ini' => $total_akm_thn_ini,
                'total_nilai_buku_final' => $total_nilai_buku_final
            ]
        ];
    }
    public function get_trans_dist($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.grand_id', 226);
        $this->db->order_by('tanggal', 'ASC');

        $query = $this->db->get();
        $results = $query->result();

        $tahun = $tahun_lap;
        if (empty($tahun)) {
            $tahun = date('Y');
        }

        // Inisialisasi variabel untuk menyimpan total
        $total_rupiah = 0;
        $total_penambahan = 0;
        $total_akm_thn_lalu = 0;
        $total_nilai_buku_lalu = 0;
        $total_penyusutan = 0;
        $total_akm_thn_ini = 0;
        $total_nilai_buku_final = 0;

        foreach ($results as &$row) {
            $row->penambahan_penyusutan = ($row->persen_susut / 100) * $row->rupiah;
            $umur_tahun = $tahun - $row->tahun;

            if ($umur_tahun > $row->umur) {
                $row->akm_thn_ini = $row->umur * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = $row->rupiah;
                $row->nilai_buku_lalu = $row->nilai_buku;
            } else {
                $row->akm_thn_ini = ($tahun - $row->tahun) * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = (($tahun - $row->tahun) * $row->penambahan_penyusutan) - $row->penambahan_penyusutan;
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

            $row->nilai_buku_final = $row->rupiah - $row->akm_thn_ini;

            if ($row->nilai_buku_final == 0) {
                $row->nilai_buku_final = 1;
            }

            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
            }

            // Akumulasi total dari setiap kolom
            $total_rupiah += $row->rupiah;
            $total_penambahan += $row->penambahan;
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
                'total_penambahan' => $total_penambahan,
                'total_akm_thn_lalu' => $total_akm_thn_lalu,
                'total_nilai_buku_lalu' => $total_nilai_buku_lalu,
                'total_penyusutan' => $total_penyusutan,
                'total_akm_thn_ini' => $total_akm_thn_ini,
                'total_nilai_buku_final' => $total_nilai_buku_final
            ]
        ];
    }
    public function get_bangunan($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.grand_id', 228);
        $this->db->order_by('tanggal', 'ASC');

        $query = $this->db->get();
        $results = $query->result();

        $tahun = $tahun_lap;
        if (empty($tahun)) {
            $tahun = date('Y');
        }

        // Inisialisasi variabel untuk menyimpan total
        $total_rupiah = 0;
        $total_penambahan = 0;
        $total_akm_thn_lalu = 0;
        $total_nilai_buku_lalu = 0;
        $total_penyusutan = 0;
        $total_akm_thn_ini = 0;
        $total_nilai_buku_final = 0;

        foreach ($results as &$row) {
            $row->penambahan_penyusutan = ($row->persen_susut / 100) * $row->rupiah;
            $umur_tahun = $tahun - $row->tahun;

            if ($umur_tahun > $row->umur) {
                $row->akm_thn_ini = $row->umur * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = $row->rupiah;
                $row->nilai_buku_lalu = $row->nilai_buku;
            } else {
                $row->akm_thn_ini = ($tahun - $row->tahun) * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = (($tahun - $row->tahun) * $row->penambahan_penyusutan) - $row->penambahan_penyusutan;
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

            $row->nilai_buku_final = $row->rupiah - $row->akm_thn_ini;

            if ($row->nilai_buku_final == 0) {
                $row->nilai_buku_final = 1;
            }

            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
            }

            // Akumulasi total dari setiap kolom
            $total_rupiah += $row->rupiah;
            $total_penambahan += $row->penambahan;
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
                'total_penambahan' => $total_penambahan,
                'total_akm_thn_lalu' => $total_akm_thn_lalu,
                'total_nilai_buku_lalu' => $total_nilai_buku_lalu,
                'total_penyusutan' => $total_penyusutan,
                'total_akm_thn_ini' => $total_akm_thn_ini,
                'total_nilai_buku_final' => $total_nilai_buku_final
            ]
        ];
    }
    public function get_peralatan($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.grand_id', 244);
        $this->db->order_by('tanggal', 'ASC');

        $query = $this->db->get();
        $results = $query->result();

        $tahun = $tahun_lap;
        if (empty($tahun)) {
            $tahun = date('Y');
        }

        // Inisialisasi variabel untuk menyimpan total
        $total_rupiah = 0;
        $total_penambahan = 0;
        $total_akm_thn_lalu = 0;
        $total_nilai_buku_lalu = 0;
        $total_penyusutan = 0;
        $total_akm_thn_ini = 0;
        $total_nilai_buku_final = 0;

        foreach ($results as &$row) {
            $row->penambahan_penyusutan = ($row->persen_susut / 100) * $row->rupiah;
            $umur_tahun = $tahun - $row->tahun;

            if ($umur_tahun > $row->umur) {
                $row->akm_thn_ini = $row->umur * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = $row->rupiah;
                $row->nilai_buku_lalu = $row->nilai_buku;
            } else {
                $row->akm_thn_ini = ($tahun - $row->tahun) * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = (($tahun - $row->tahun) * $row->penambahan_penyusutan) - $row->penambahan_penyusutan;
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

            $row->nilai_buku_final = $row->rupiah - $row->akm_thn_ini;

            if ($row->nilai_buku_final == 0) {
                $row->nilai_buku_final = 1;
            }

            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
            }

            // Akumulasi total dari setiap kolom
            $total_rupiah += $row->rupiah;
            $total_penambahan += $row->penambahan;
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
                'total_penambahan' => $total_penambahan,
                'total_akm_thn_lalu' => $total_akm_thn_lalu,
                'total_nilai_buku_lalu' => $total_nilai_buku_lalu,
                'total_penyusutan' => $total_penyusutan,
                'total_akm_thn_ini' => $total_akm_thn_ini,
                'total_nilai_buku_final' => $total_nilai_buku_final
            ]
        ];
    }
    public function get_kendaraan($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.grand_id', 246);
        $this->db->order_by('tanggal', 'ASC');

        $query = $this->db->get();
        $results = $query->result();

        $tahun = $tahun_lap;
        if (empty($tahun)) {
            $tahun = date('Y');
        }

        // Inisialisasi variabel untuk menyimpan total
        $total_rupiah = 0;
        $total_penambahan = 0;
        $total_akm_thn_lalu = 0;
        $total_nilai_buku_lalu = 0;
        $total_penyusutan = 0;
        $total_akm_thn_ini = 0;
        $total_nilai_buku_final = 0;

        foreach ($results as &$row) {
            $row->penambahan_penyusutan = ($row->persen_susut / 100) * $row->rupiah;
            $umur_tahun = $tahun - $row->tahun;

            if ($umur_tahun > $row->umur) {
                $row->akm_thn_ini = $row->umur * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = $row->rupiah;
                $row->nilai_buku_lalu = $row->nilai_buku;
            } else {
                $row->akm_thn_ini = ($tahun - $row->tahun) * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = (($tahun - $row->tahun) * $row->penambahan_penyusutan) - $row->penambahan_penyusutan;
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

            $row->nilai_buku_final = $row->rupiah - $row->akm_thn_ini;

            if ($row->nilai_buku_final == 0) {
                $row->nilai_buku_final = 1;
            }

            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
            }

            // Akumulasi total dari setiap kolom
            $total_rupiah += $row->rupiah;
            $total_penambahan += $row->penambahan;
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
                'total_penambahan' => $total_penambahan,
                'total_akm_thn_lalu' => $total_akm_thn_lalu,
                'total_nilai_buku_lalu' => $total_nilai_buku_lalu,
                'total_penyusutan' => $total_penyusutan,
                'total_akm_thn_ini' => $total_akm_thn_ini,
                'total_nilai_buku_final' => $total_nilai_buku_final
            ]
        ];
    }
    public function get_inventaris($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.grand_id', 248);
        $this->db->order_by('tanggal', 'ASC');

        $query = $this->db->get();
        $results = $query->result();

        $tahun = $tahun_lap;
        if (empty($tahun)) {
            $tahun = date('Y');
        }

        // Inisialisasi variabel untuk menyimpan total
        $total_rupiah = 0;
        $total_penambahan = 0;
        $total_akm_thn_lalu = 0;
        $total_nilai_buku_lalu = 0;
        $total_penyusutan = 0;
        $total_akm_thn_ini = 0;
        $total_nilai_buku_final = 0;

        foreach ($results as &$row) {
            $row->penambahan_penyusutan = ($row->persen_susut / 100) * $row->rupiah;
            $umur_tahun = $tahun - $row->tahun;

            if ($umur_tahun > $row->umur) {
                $row->akm_thn_ini = $row->umur * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = $row->rupiah;
                $row->nilai_buku_lalu = $row->nilai_buku;
            } else {
                $row->akm_thn_ini = ($tahun - $row->tahun) * $row->penambahan_penyusutan;
                $row->akm_thn_lalu = (($tahun - $row->tahun) * $row->penambahan_penyusutan) - $row->penambahan_penyusutan;
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

            $row->nilai_buku_final = $row->rupiah - $row->akm_thn_ini;

            if ($row->nilai_buku_final == 0) {
                $row->nilai_buku_final = 1;
            }

            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
            }

            // Akumulasi total dari setiap kolom
            $total_rupiah += $row->rupiah;
            $total_penambahan += $row->penambahan;
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
                'total_penambahan' => $total_penambahan,
                'total_akm_thn_lalu' => $total_akm_thn_lalu,
                'total_nilai_buku_lalu' => $total_nilai_buku_lalu,
                'total_penyusutan' => $total_penyusutan,
                'total_akm_thn_ini' => $total_akm_thn_ini,
                'total_nilai_buku_final' => $total_nilai_buku_final
            ]
        ];
    }
}
