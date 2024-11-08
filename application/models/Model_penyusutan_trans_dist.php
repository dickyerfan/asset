<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_penyusutan_trans_dist extends CI_Model
{
    public function get_trans_dist($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.grand_id', 226);
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
                $row->akm_thn_ini = 0;
            } else {
                $row->penambahan = 0;
                $row->pengurangan = 0;
            }

            // menghitung nilai untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
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
    public function get_trans_dist_bangunan_total($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2255);
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
                $row->akm_thn_ini = 0;
            } else {
                $row->penambahan = 0;
                $row->pengurangan = 0;
            }

            // menghitung nilai untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
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

    public function get_trans_dist_bangunan($tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        // $this->db->where('daftar_asset.grand_id', 228);
        $this->db->where('daftar_asset.parent_id', 2255);
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
                $row->akm_thn_ini = 0;
            } else {
                $row->penambahan = 0;
                $row->pengurangan = 0;
            }

            // menghitung nilai untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
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

    public function get_trans_dist_reservoir_total($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2258);
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
                $row->akm_thn_ini = 0;
            } else {
                $row->penambahan = 0;
                $row->pengurangan = 0;
            }

            // menghitung nilai untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
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

    public function get_trans_dist_reservoir($tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2258);
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
                $row->akm_thn_ini = 0;
            } else {
                $row->penambahan = 0;
                $row->pengurangan = 0;
            }

            // menghitung nilai untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
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
    public function get_trans_dist_pipa_trans_total($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2261);
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
                $row->akm_thn_ini = 0;
            } else {
                $row->penambahan = 0;
                $row->pengurangan = 0;
            }

            // menghitung nilai untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
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

    public function get_trans_dist_pipa_trans($tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2261);
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
                $row->akm_thn_ini = 0;
            } else {
                $row->penambahan = 0;
                $row->pengurangan = 0;
            }

            // menghitung nilai untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
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
    public function get_trans_dist_meter_total($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2263);
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
                $row->akm_thn_ini = 0;
            } else {
                $row->penambahan = 0;
                $row->pengurangan = 0;
            }

            // menghitung nilai untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
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

    public function get_trans_dist_meter($tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2263);
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
                $row->akm_thn_ini = 0;
            } else {
                $row->penambahan = 0;
                $row->pengurangan = 0;
            }

            // menghitung nilai untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
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

    public function get_trans_dist_ledeng_total($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2264);
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
                $row->akm_thn_ini = 0;
            } else {
                $row->penambahan = 0;
                $row->pengurangan = 0;
            }

            // menghitung nilai untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
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

    public function get_trans_dist_ledeng($tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2264);
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
                $row->akm_thn_ini = 0;
            } else {
                $row->penambahan = 0;
                $row->pengurangan = 0;
            }

            // menghitung nilai untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
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

    public function get_trans_dist_pemadam_total($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2548);
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
                $row->akm_thn_ini = 0;
            } else {
                $row->penambahan = 0;
                $row->pengurangan = 0;
            }

            // menghitung nilai untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
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

    public function get_trans_dist_pemadam($tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2548);
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
                $row->akm_thn_ini = 0;
            } else {
                $row->penambahan = 0;
                $row->pengurangan = 0;
            }

            // menghitung nilai untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
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

    public function get_trans_dist_jembatan_total($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2550);
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
                $row->akm_thn_ini = 0;
            } else {
                $row->penambahan = 0;
                $row->pengurangan = 0;
            }

            // menghitung nilai untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
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

    public function get_trans_dist_jembatan($tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2550);
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
                $row->akm_thn_ini = 0;
            } else {
                $row->penambahan = 0;
                $row->pengurangan = 0;
            }

            // menghitung nilai untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
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

    public function get_trans_dist_inst_lain_total($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2552);
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
                $row->akm_thn_ini = 0;
            } else {
                $row->penambahan = 0;
                $row->pengurangan = 0;
            }

            // menghitung nilai untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
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

    public function get_trans_dist_inst_lain($tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 2552);
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
                $row->akm_thn_ini = 0;
            } else {
                $row->penambahan = 0;
                $row->pengurangan = 0;
            }

            // menghitung nilai untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = $row->nilai_buku;
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

    public function get_unit_trans_dist_bangunan()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 2255);
        return $this->db->get()->result();
    }
    public function get_unit_trans_dist_reservoir()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 2258);
        return $this->db->get()->result();
    }
    public function get_unit_trans_dist_pipa_trans()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 2261);
        return $this->db->get()->result();
    }
    public function get_unit_trans_dist_meter()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 2263);
        return $this->db->get()->result();
    }
    public function get_unit_trans_dist_ledeng()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 2264);
        return $this->db->get()->result();
    }
    public function get_unit_trans_dist_pemadam()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 2548);
        return $this->db->get()->result();
    }
    public function get_unit_trans_dist_jembatan()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 2550);
        return $this->db->get()->result();
    }
    public function get_unit_trans_dist_inst_lain()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 2552);
        return $this->db->get()->result();
    }
}
