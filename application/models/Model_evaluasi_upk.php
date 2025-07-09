<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_evaluasi_upk extends CI_Model
{

    public function get_teknis($id_upk, $bulan, $tahun)
    {
        $this->db->select('eu_teknis.*, bagian_upk.nama_bagian, bagian_upk.id_bagian');
        $this->db->from('eu_teknis');
        $this->db->join('bagian_upk', 'bagian_upk.id_bagian = eu_teknis.id_upk');
        $this->db->where('eu_teknis.id_upk', $id_upk);
        $this->db->where('eu_teknis.bulan', $bulan);
        $this->db->where('eu_teknis.tahun', $tahun);
        return $this->db->get()->result();
    }
    public function insert_teknis($data)
    {
        return $this->db->insert('eu_teknis', $data);
    }

    public function get_teknis_by_upk_bulan_tahun($id_upk, $bulan, $tahun)
    {
        $this->db->where('id_upk', $id_upk);
        $this->db->where('bulan', $bulan);
        $this->db->where('tahun', $tahun);
        return $this->db->get('eu_teknis')->result();
    }

    public function upsert_teknis($id_upk, $bulan, $tahun, $indikator, $keberadaan, $kondisi, $skor, $user)
    {
        date_default_timezone_set('Asia/Jakarta');
        // Cek apakah data sudah ada
        $this->db->where([
            'id_upk' => $id_upk,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'indikator' => $indikator
        ]);
        $query = $this->db->get('eu_teknis');

        if ($query->num_rows() > 0) {
            // UPDATE
            $this->db->where([
                'id_upk' => $id_upk,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'indikator' => $indikator
            ]);
            return $this->db->update('eu_teknis', [
                'keberadaan' => $keberadaan,
                'kondisi' => $kondisi,
                'skor' => $skor,
                'modified_by' => $user,
                'modified_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            // INSERT
            return $this->db->insert('eu_teknis', [
                'id_upk' => $id_upk,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'indikator' => $indikator,
                'keberadaan' => $keberadaan,
                'kondisi' => $kondisi,
                'skor' => $skor,
                'created_by' => $user,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }


    public function get_admin($id_upk, $bulan, $tahun)
    {
        $this->db->select('eu_admin.*, bagian_upk.nama_bagian');
        $this->db->from('eu_admin');
        $this->db->join('bagian_upk', 'bagian_upk.id_bagian = eu_admin.id_upk');
        $this->db->where('eu_admin.id_upk', $id_upk);
        $this->db->where('eu_admin.bulan', $bulan);
        $this->db->where('eu_admin.tahun', $tahun);
        return $this->db->get()->result();
    }
    public function insert_admin($data)
    {
        return $this->db->insert('eu_admin', $data);
    }

    public function get_admin_by_upk_bulan_tahun($id_upk, $bulan, $tahun)
    {
        $this->db->where('id_upk', $id_upk);
        $this->db->where('bulan', $bulan);
        $this->db->where('tahun', $tahun);
        return $this->db->get('eu_admin')->result();
    }

    public function upsert_admin($id_upk, $bulan, $tahun, $indikator, $hasil, $skor, $user)
    {
        date_default_timezone_set('Asia/Jakarta');
        // Cek apakah data sudah ada
        $this->db->where([
            'id_upk' => $id_upk,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'indikator' => $indikator
        ]);
        $query = $this->db->get('eu_admin');

        if ($query->num_rows() > 0) {
            // UPDATE
            $this->db->where([
                'id_upk' => $id_upk,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'indikator' => $indikator
            ]);
            return $this->db->update('eu_admin', [
                'hasil' => $hasil,
                'skor' => $skor,
                'modified_by' => $user,
                'modified_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            // INSERT
            return $this->db->insert('eu_admin', [
                'id_upk' => $id_upk,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'indikator' => $indikator,
                'hasil' => $hasil,
                'skor' => $skor,
                'created_by' => $user,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function get_koordinasi($id_upk, $bulan, $tahun)
    {
        $this->db->select('eu_koordinasi.*, bagian_upk.nama_bagian');
        $this->db->from('eu_koordinasi');
        $this->db->join('bagian_upk', 'bagian_upk.id_bagian = eu_koordinasi.id_upk');
        $this->db->where('eu_koordinasi.id_upk', $id_upk);
        $this->db->where('eu_koordinasi.bulan', $bulan);
        $this->db->where('eu_koordinasi.tahun', $tahun);
        return $this->db->get()->result();
    }
    public function insert_koordinasi($data)
    {
        return $this->db->insert('eu_koordinasi', $data);
    }

    public function get_koordinasi_by_upk_bulan_tahun($id_upk, $bulan, $tahun)
    {
        $this->db->where('id_upk', $id_upk);
        $this->db->where('bulan', $bulan);
        $this->db->where('tahun', $tahun);
        $query = $this->db->get('eu_koordinasi');

        $result = [];
        foreach ($query->result() as $row) {
            $result[$row->indikator] = $row->hasil;
        }
        return $result;
    }


    public function upsert_koordinasi($id_upk, $bulan, $tahun, $indikator, $hasil, $skor, $user)
    {
        date_default_timezone_set('Asia/Jakarta');
        // Cek apakah data sudah ada
        $this->db->where([
            'id_upk' => $id_upk,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'indikator' => $indikator
        ]);
        $query = $this->db->get('eu_koordinasi');

        if ($query->num_rows() > 0) {
            // UPDATE
            $this->db->where([
                'id_upk' => $id_upk,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'indikator' => $indikator
            ]);
            return $this->db->update('eu_koordinasi', [
                'hasil' => $hasil,
                'skor' => $skor,
                'modified_by' => $user,
                'modified_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            // INSERT
            return $this->db->insert('eu_koordinasi', [
                'id_upk' => $id_upk,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'indikator' => $indikator,
                'hasil' => $hasil,
                'skor' => $skor,
                'created_by' => $user,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function get_unit_list()
    {
        $this->db->select('*');
        $this->db->from('bagian_upk');
        $this->db->where('status_evkin', 1);
        return $this->db->get()->result();
    }


    public function get_nama_upk($id_upk)
    {
        return $this->db->get_where('bagian_upk', ['id_bagian' => $id_upk])->row();
    }

    // public function get_skor_total_per_upk($bulan, $tahun)
    // {
    //     // Query untuk data teknis
    //     $this->db->select('bagian_upk.nama_bagian, SUM(eu_teknis.skor) as total_skor_teknis, eu_teknis.id_upk');
    //     $this->db->from('eu_teknis');
    //     $this->db->join('bagian_upk', 'bagian_upk.id_bagian = eu_teknis.id_upk');
    //     $this->db->where('eu_teknis.bulan', $bulan);
    //     $this->db->where('eu_teknis.tahun', $tahun);
    //     $this->db->group_by('bagian_upk.nama_bagian, eu_teknis.id_upk');
    //     $query_teknis = $this->db->get()->result_array();

    //     // Query untuk data admin
    //     $this->db->select('bagian_upk.nama_bagian, SUM(eu_admin.skor) as total_skor_admin, eu_admin.id_upk');
    //     $this->db->from('eu_admin');
    //     $this->db->join('bagian_upk', 'bagian_upk.id_bagian = eu_admin.id_upk');
    //     $this->db->where('eu_admin.bulan', $bulan);
    //     $this->db->where('eu_admin.tahun', $tahun);
    //     $this->db->group_by('bagian_upk.nama_bagian, eu_admin.id_upk');
    //     $query_admin = $this->db->get()->result_array();

    //     // Query untuk data koordinasi
    //     $this->db->select('bagian_upk.nama_bagian, SUM(eu_koordinasi.skor) as total_skor_koordinasi, eu_koordinasi.id_upk');
    //     $this->db->from('eu_koordinasi');
    //     $this->db->join('bagian_upk', 'bagian_upk.id_bagian = eu_koordinasi.id_upk');
    //     $this->db->where('eu_koordinasi.bulan', $bulan);
    //     $this->db->where('eu_koordinasi.tahun', $tahun);
    //     $this->db->group_by('bagian_upk.nama_bagian, eu_koordinasi.id_upk');
    //     $query_koordinasi = $this->db->get()->result_array();

    //     // Gabungkan hasil dan hitung total skor
    //     $skor_data_per_upk = [];

    //     // Helper function to process query results
    //     $process_query_results = function ($results, &$target_array, $score_key) {
    //         foreach ($results as $item) {
    //             $nama_upk = $item['nama_bagian'];
    //             $id_upk = $item['id_upk'];
    //             if (!isset($target_array[$id_upk])) { // Gunakan id_upk sebagai kunci utama
    //                 $target_array[$id_upk] = [
    //                     'id_upk' => $id_upk, // Simpan id_upk
    //                     'nama_bagian' => $nama_upk,
    //                     'total_skor_teknis' => 0,
    //                     'total_skor_admin' => 0,
    //                     'total_skor_koordinasi' => 0,
    //                     'grand_total_skor' => 0
    //                 ];
    //             }
    //             $target_array[$id_upk][$score_key] = $item[$score_key] ?? 0;
    //             $target_array[$id_upk]['grand_total_skor'] += $item[$score_key] ?? 0;
    //         }
    //     };

    //     $process_query_results($query_teknis, $skor_data_per_upk, 'total_skor_teknis');
    //     $process_query_results($query_admin, $skor_data_per_upk, 'total_skor_admin');
    //     $process_query_results($query_koordinasi, $skor_data_per_upk, 'total_skor_koordinasi');

    //     // Ubah kembali ke array indeks numerik
    //     return array_values($skor_data_per_upk);
    // }

    public function get_skor_total_per_upk($bulan, $tahun)
    {
        // Query untuk data teknis
        $this->db->select('bagian_upk.nama_bagian, SUM(eu_teknis.skor) as total_skor_teknis, eu_teknis.id_upk');
        $this->db->from('eu_teknis');
        $this->db->join('bagian_upk', 'bagian_upk.id_bagian = eu_teknis.id_upk');
        $this->db->where('eu_teknis.bulan', $bulan);
        $this->db->where('eu_teknis.tahun', $tahun);
        $this->db->where('bagian_upk.status_evkin', 1); // filter status_evkin
        $this->db->group_by('eu_teknis.id_upk');
        $this->db->order_by('eu_teknis.id_upk', 'ASC');
        $query_teknis = $this->db->get()->result_array();

        // Query untuk data admin
        $this->db->select('bagian_upk.nama_bagian, SUM(eu_admin.skor) as total_skor_admin, eu_admin.id_upk');
        $this->db->from('eu_admin');
        $this->db->join('bagian_upk', 'bagian_upk.id_bagian = eu_admin.id_upk');
        $this->db->where('eu_admin.bulan', $bulan);
        $this->db->where('eu_admin.tahun', $tahun);
        $this->db->where('bagian_upk.status_evkin', 1);
        $this->db->group_by('eu_admin.id_upk');
        $this->db->order_by('eu_admin.id_upk', 'ASC');
        $query_admin = $this->db->get()->result_array();

        // Query untuk data koordinasi
        $this->db->select('bagian_upk.nama_bagian, SUM(eu_koordinasi.skor) as total_skor_koordinasi, eu_koordinasi.id_upk');
        $this->db->from('eu_koordinasi');
        $this->db->join('bagian_upk', 'bagian_upk.id_bagian = eu_koordinasi.id_upk');
        $this->db->where('eu_koordinasi.bulan', $bulan);
        $this->db->where('eu_koordinasi.tahun', $tahun);
        $this->db->where('bagian_upk.status_evkin', 1);
        $this->db->group_by('eu_koordinasi.id_upk');
        $this->db->order_by('eu_koordinasi.id_upk', 'ASC');
        $query_koordinasi = $this->db->get()->result_array();

        // Gabungkan hasil
        $skor_data_per_upk = [];

        // Fungsi bantu gabung data berdasarkan id_upk
        $process_query_results = function ($results, &$target_array, $score_key) {
            foreach ($results as $item) {
                $id_upk = $item['id_upk'];
                if (!isset($target_array[$id_upk])) {
                    $target_array[$id_upk] = [
                        'id_upk' => $id_upk,
                        'nama_bagian' => $item['nama_bagian'],
                        'total_skor_teknis' => 0,
                        'total_skor_admin' => 0,
                        'total_skor_koordinasi' => 0,
                        'grand_total_skor' => 0
                    ];
                }

                $target_array[$id_upk][$score_key] = (int) $item[$score_key];
                $target_array[$id_upk]['grand_total_skor'] += (int) $item[$score_key];
            }
        };

        $process_query_results($query_teknis, $skor_data_per_upk, 'total_skor_teknis');
        $process_query_results($query_admin, $skor_data_per_upk, 'total_skor_admin');
        $process_query_results($query_koordinasi, $skor_data_per_upk, 'total_skor_koordinasi');

        return array_values($skor_data_per_upk);
    }



    // kode tindak lanjut
    public function get_tindak_lanjut($bulan = null, $tahun = null, $id_upk = null)
    {
        $this->db->select('
            tl.*,
            bu.nama_bagian
        ');
        $this->db->from('eu_tindak_lanjut tl');
        $this->db->join('bagian_upk bu', 'bu.id_bagian = tl.id_upk', 'left');

        if ($bulan !== null && $bulan !== '') {
            $this->db->where('tl.bulan', $bulan);
        }
        if ($tahun !== null && $tahun !== '') {
            $this->db->where('tl.tahun', $tahun);
        }
        if ($id_upk !== null && $id_upk !== '') {
            $this->db->where('tl.id_upk', $id_upk);
        }
        $this->db->where('tl.is_active', 1);
        $this->db->order_by('tl.tahun', 'DESC');
        $this->db->order_by('tl.bulan', 'DESC');
        $this->db->order_by('bu.nama_bagian', 'ASC');
        return $this->db->get()->result();
    }

    public function get_all_upk($id_upk = null)
    {
        return $this->db->get_where('bagian_upk', ['id_bagian' => $id_upk])->row();
    }

    public function insert_tl($data)
    {
        return $this->db->insert('eu_tindak_lanjut', $data);
    }

    public function get_tindak_lanjut_by_id($id)
    {
        $this->db->select('tl.*, bu.nama_bagian');
        $this->db->from('eu_tindak_lanjut tl');
        $this->db->join('bagian_upk bu', 'bu.id_bagian = tl.id_upk', 'left');
        $this->db->where('tl.id_tl', $id);
        return $this->db->get()->row();
    }

    public function update_tl($id, $data)
    {
        $this->db->where('id_tl', $id);
        return $this->db->update('eu_tindak_lanjut', $data);
    }

    public function delete_tl($id)
    {
        $this->db->where('id_tl', $id);
        return $this->db->update('eu_tindak_lanjut', ['is_active' => 0]);
    }
    // akhir tindak lanjut




    // kode untuk pengaturan
    public function get_all_aspek()
    {
        return $this->db->get('eu_aspek')->result();
    }

    // Tambah aspek baru
    public function insert_aspek($data)
    {
        return $this->db->insert('eu_aspek', $data);
    }

    // Ambil semua indikator berdasarkan id_aspek
    public function get_indikator_by_aspek($id_aspek)
    {
        $this->db->where('id_aspek', $id_aspek);
        return $this->db->get('eu_indikator')->result();
    }

    // Tambah indikator
    public function insert_indikator($data)
    {
        return $this->db->insert('eu_indikator', $data);
    }

    // Ambil semua opsi berdasarkan id_indikator
    public function get_opsi_by_indikator($id_indikator)
    {
        $this->db->where('id_indikator', $id_indikator);
        return $this->db->get('eu_opsi')->result();
    }

    // Tambah opsi
    public function insert_opsi($data)
    {
        return $this->db->insert('eu_opsi', $data);
    }

    // Untuk kebutuhan dropdown aspek
    public function get_dropdown_aspek()
    {
        $result = $this->db->get('eu_aspek')->result();
        $dropdown = [];
        foreach ($result as $r) {
            $dropdown[$r->id_aspek] = $r->nama_aspek;
        }
        return $dropdown;
    }
    // akhir kode pengaturan
}
