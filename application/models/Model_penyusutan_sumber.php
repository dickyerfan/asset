<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_penyusutan_sumber extends CI_Model
{
    public function get_sumber($tahun_lap)
    {
        // $this->db->select('*');
        $this->db->select('penyusutan.*, daftar_asset.*, no_per.name');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.grand_id', 220);
        // $this->db->where('daftar_asset.parent_id', 2671);
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
    public function get_sumber_bangunan_total($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 1569);
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

    public function get_sumber_bangunan($tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        // $this->db->where('daftar_asset.grand_id', 228);
        $this->db->where('daftar_asset.parent_id', 1569);
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

    public function get_sumber_reservoir_total($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 1571);
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

    public function get_sumber_reservoir($tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 1571);
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
    public function get_sumber_danau_total($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 1572);
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

    public function get_sumber_danau($tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 1572);
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
    public function get_sumber_mata_air_total($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 1575);
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

    public function get_sumber_mata_air($tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 1575);
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
    public function get_sumber_sumur_total($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 1576);
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

    public function get_sumber_sumur($tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 1576);
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
    public function get_sumber_pipa_total($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 1577);
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

    public function get_sumber_pipa($tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 1577);
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

    public function get_sumber_inst_lain_total($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 1579);
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

    public function get_sumber_inst_lain($tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->where('penyusutan.tahun <=', $tahun_lap);
        $this->db->where('daftar_asset.parent_id', 1579);
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

    public function get_unit_sumber_bangunan()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 1569);
        return $this->db->get()->result();
    }
    public function get_unit_sumber_reservoir()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 1571);
        return $this->db->get()->result();
    }
    public function get_unit_sumber_danau()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 1572);
        return $this->db->get()->result();
    }

    public function get_unit_sumber_mata_air()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 1575);
        return $this->db->get()->result();
    }
    public function get_unit_sumber_sumur()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 1576);
        return $this->db->get()->result();
    }
    public function get_unit_sumber_pipa()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 1577);
        return $this->db->get()->result();
    }
    public function get_unit_sumber_inst_lain()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 1579);
        return $this->db->get()->result();
    }
}
