<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_pelihara extends CI_Model
{

    public function get_bagian()
    {
        $this->db->select('*');
        $this->db->from('bagian_upk');
        $this->db->where('status_evkin', 1);
        return $this->db->get()->result();
    }

    // water meter

    public function get_tera_meter($tahun)
    {
        $this->db->select('b.nama_bagian, b.id_bagian, tm.id_ek_tm, tm.tgl_tm,
            SUM(CASE WHEN MONTH(tm.tgl_tm) = 1 THEN tm.jumlah_tm ELSE 0 END) AS jan,
            SUM(CASE WHEN MONTH(tm.tgl_tm) = 2 THEN tm.jumlah_tm ELSE 0 END) AS feb,
            SUM(CASE WHEN MONTH(tm.tgl_tm) = 3 THEN tm.jumlah_tm ELSE 0 END) AS mar,
            SUM(CASE WHEN MONTH(tm.tgl_tm) = 4 THEN tm.jumlah_tm ELSE 0 END) AS apr,
            SUM(CASE WHEN MONTH(tm.tgl_tm) = 5 THEN tm.jumlah_tm ELSE 0 END) AS mei,
            SUM(CASE WHEN MONTH(tm.tgl_tm) = 6 THEN tm.jumlah_tm ELSE 0 END) AS jun,
            SUM(CASE WHEN MONTH(tm.tgl_tm) = 7 THEN tm.jumlah_tm ELSE 0 END) AS jul,
            SUM(CASE WHEN MONTH(tm.tgl_tm) = 8 THEN tm.jumlah_tm ELSE 0 END) AS agu,
            SUM(CASE WHEN MONTH(tm.tgl_tm) = 9 THEN tm.jumlah_tm ELSE 0 END) AS sep,
            SUM(CASE WHEN MONTH(tm.tgl_tm) = 10 THEN tm.jumlah_tm ELSE 0 END) AS okt,
            SUM(CASE WHEN MONTH(tm.tgl_tm) = 11 THEN tm.jumlah_tm ELSE 0 END) AS nov,
            SUM(CASE WHEN MONTH(tm.tgl_tm) = 12 THEN tm.jumlah_tm ELSE 0 END) AS des,
            COALESCE(SUM(tm.jumlah_tm), 0) AS total');

        $this->db->from('bagian_upk b');
        $this->db->join('ek_tera_meter tm', 'b.id_bagian = tm.id_bagian', 'LEFT'); // Tetap LEFT JOIN untuk menampilkan semua bagian
        $this->db->where('b.status_evkin', 1); // Hanya ambil bagian dengan status_evkin = 1
        $this->db->where('(tm.tgl_tm IS NULL OR YEAR(tm.tgl_tm) = ' . $this->db->escape($tahun) . ')'); // Filter tahun tetap
        $this->db->group_by('b.nama_bagian');
        $this->db->order_by('b.id_bagian');
        return $this->db->get()->result();
    }

    public function get_ganti_meter($tahun)
    {
        $this->db->select('b.nama_bagian, b.id_bagian, gm.id_ek_gm, gm.tgl_gm, 
            SUM(CASE WHEN MONTH(gm.tgl_gm) = 1 THEN gm.jumlah_gm ELSE 0 END) AS jan,
            SUM(CASE WHEN MONTH(gm.tgl_gm) = 2 THEN gm.jumlah_gm ELSE 0 END) AS feb,
            SUM(CASE WHEN MONTH(gm.tgl_gm) = 3 THEN gm.jumlah_gm ELSE 0 END) AS mar,
            SUM(CASE WHEN MONTH(gm.tgl_gm) = 4 THEN gm.jumlah_gm ELSE 0 END) AS apr,
            SUM(CASE WHEN MONTH(gm.tgl_gm) = 5 THEN gm.jumlah_gm ELSE 0 END) AS mei,
            SUM(CASE WHEN MONTH(gm.tgl_gm) = 6 THEN gm.jumlah_gm ELSE 0 END) AS jun,
            SUM(CASE WHEN MONTH(gm.tgl_gm) = 7 THEN gm.jumlah_gm ELSE 0 END) AS jul,
            SUM(CASE WHEN MONTH(gm.tgl_gm) = 8 THEN gm.jumlah_gm ELSE 0 END) AS agu,
            SUM(CASE WHEN MONTH(gm.tgl_gm) = 9 THEN gm.jumlah_gm ELSE 0 END) AS sep,
            SUM(CASE WHEN MONTH(gm.tgl_gm) = 10 THEN gm.jumlah_gm ELSE 0 END) AS okt,
            SUM(CASE WHEN MONTH(gm.tgl_gm) = 11 THEN gm.jumlah_gm ELSE 0 END) AS nov,
            SUM(CASE WHEN MONTH(gm.tgl_gm) = 12 THEN gm.jumlah_gm ELSE 0 END) AS des,
            COALESCE(SUM(gm.jumlah_gm), 0) AS total');

        $this->db->from('bagian_upk b');
        $this->db->join('ek_ganti_meter gm', 'b.id_bagian = gm.id_bagian', 'LEFT'); // Tetap LEFT JOIN untuk menampilkan semua bagian
        $this->db->where('b.status_evkin', 1); // Hanya ambil bagian dengan status_evkin = 1
        $this->db->where('(gm.tgl_gm IS NULL OR YEAR(gm.tgl_gm) = ' . $this->db->escape($tahun) . ')'); // Filter tahun tetap
        $this->db->group_by('b.nama_bagian');
        $this->db->order_by('b.id_bagian');
        return $this->db->get()->result();
    }

    public function input_tm($table, $data)
    {
        if (!empty($data)) {
            $this->db->insert_batch($table, $data); // Insert banyak data sekaligus
        }
    }
    public function input_gm($table, $data)
    {
        if (!empty($data)) {
            $this->db->insert_batch($table, $data); // Insert banyak data sekaligus
        }
    }


    public function getByIdTgl_tm($id_bagian, $tgl_tm)
    {
        $this->db->select('*');
        $this->db->from('ek_tera_meter');
        $this->db->where('id_bagian', $id_bagian);
        $this->db->where('tgl_tm', $tgl_tm);
        return $this->db->get()->row(); // Ambil satu baris data
    }

    public function update_tm($id_bagian, $tgl_tm, $data)
    {
        $this->db->where('id_bagian', $id_bagian);
        $this->db->where('tgl_tm', $tgl_tm);
        $this->db->update('ek_tera_meter', $data);

        return $this->db->affected_rows(); // Mengembalikan jumlah baris yang terupdate
    }

    public function getByIdTgl_gm($id_bagian, $tgl_gm)
    {
        $this->db->select('*');
        $this->db->from('ek_ganti_meter');
        $this->db->where('id_bagian', $id_bagian);
        $this->db->where('tgl_gm', $tgl_gm);
        return $this->db->get()->row(); // Ambil satu baris data
    }

    public function update_gm($id_bagian, $tgl_gm, $data)
    {
        $this->db->where('id_bagian', $id_bagian);
        $this->db->where('tgl_gm', $tgl_gm);
        $this->db->update('ek_ganti_meter', $data);

        return $this->db->affected_rows(); // Mengembalikan jumlah baris yang terupdate
    }
    // akhir water meter

    // tekanan  air

    public function get_tekanan_air($tahun)
    {
        $this->db->select('*');
        $this->db->from('ek_tekanan_air');
        $this->db->join('bagian_upk', 'ek_tekanan_air.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->where('YEAR(ek_tekanan_air.tahun_tka)', $tahun);
        $this->db->order_by('bagian_upk.id_bagian');
        return $this->db->get()->result();
    }

    public function input_tka($table, $data)
    {
        if (!empty($data)) {
            $this->db->insert($table, $data);
        }
    }

    public function get_tekanan_air_by_id($id)
    {
        return $this->db->get_where('ek_tekanan_air', ['id_ek_tka' => $id])->row();
    }

    public function update_tekanan_air($id, $data)
    {
        $this->db->where('id_ek_tka', $id);
        return $this->db->update('ek_tekanan_air', $data);
    }
}
