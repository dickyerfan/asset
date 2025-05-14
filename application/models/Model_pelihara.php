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
    // akhir tekanan air

    // jam ops
    public function get_jam_ops($tahun)
    {
        $this->db->select('
        ek_sb_mag.id_sb_mag,
        ek_sb_mag.nama_sb_mag, 
        ek_sb_mag.mulai_ops, 
        ek_jam_ops.tgl_jam_ops, 
        ek_jam_ops.id_ek_jam_ops, 
        bagian_upk.nama_bagian, 
        SUM(CASE WHEN MONTH(ek_jam_ops.tgl_jam_ops) = 1 THEN ek_jam_ops.jumlah_jam_ops ELSE 0 END) AS jan,
        SUM(CASE WHEN MONTH(ek_jam_ops.tgl_jam_ops) = 2 THEN ek_jam_ops.jumlah_jam_ops ELSE 0 END) AS feb,
        SUM(CASE WHEN MONTH(ek_jam_ops.tgl_jam_ops) = 3 THEN ek_jam_ops.jumlah_jam_ops ELSE 0 END) AS mar,
        SUM(CASE WHEN MONTH(ek_jam_ops.tgl_jam_ops) = 4 THEN ek_jam_ops.jumlah_jam_ops ELSE 0 END) AS apr,
        SUM(CASE WHEN MONTH(ek_jam_ops.tgl_jam_ops) = 5 THEN ek_jam_ops.jumlah_jam_ops ELSE 0 END) AS mei,
        SUM(CASE WHEN MONTH(ek_jam_ops.tgl_jam_ops) = 6 THEN ek_jam_ops.jumlah_jam_ops ELSE 0 END) AS jun,
        SUM(CASE WHEN MONTH(ek_jam_ops.tgl_jam_ops) = 7 THEN ek_jam_ops.jumlah_jam_ops ELSE 0 END) AS jul,
        SUM(CASE WHEN MONTH(ek_jam_ops.tgl_jam_ops) = 8 THEN ek_jam_ops.jumlah_jam_ops ELSE 0 END) AS agu,
        SUM(CASE WHEN MONTH(ek_jam_ops.tgl_jam_ops) = 9 THEN ek_jam_ops.jumlah_jam_ops ELSE 0 END) AS sep,
        SUM(CASE WHEN MONTH(ek_jam_ops.tgl_jam_ops) = 10 THEN ek_jam_ops.jumlah_jam_ops ELSE 0 END) AS okt,
        SUM(CASE WHEN MONTH(ek_jam_ops.tgl_jam_ops) = 11 THEN ek_jam_ops.jumlah_jam_ops ELSE 0 END) AS nov,
        SUM(CASE WHEN MONTH(ek_jam_ops.tgl_jam_ops) = 12 THEN ek_jam_ops.jumlah_jam_ops ELSE 0 END) AS des,
        COALESCE(SUM(ek_jam_ops.jumlah_jam_ops), 0) AS total
    ');

        $this->db->from('ek_jam_ops');
        $this->db->join('ek_sb_mag', 'ek_jam_ops.id_sb_mag = ek_sb_mag.id_sb_mag', 'left');
        $this->db->join('bagian_upk', 'bagian_upk.id_bagian = ek_sb_mag.id_bagian', 'left');
        $this->db->where('YEAR(ek_jam_ops.tgl_jam_ops)', $tahun);
        // Tambahkan GROUP BY untuk memisahkan data berdasarkan id_sb_mag
        $this->db->group_by('ek_sb_mag.id_sb_mag, bagian_upk.nama_bagian, ek_sb_mag.nama_sb_mag');
        $this->db->order_by('ek_sb_mag.id_sb_mag');

        return $this->db->get()->result();
    }

    public function get_sb_mag()
    {
        $this->db->select('*');
        $this->db->from('ek_sb_mag');
        $this->db->join('bagian_upk', 'ek_sb_mag.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->order_by('ek_sb_mag.id_bagian');
        return $this->db->get()->result();
    }

    public function input_jam_ops($table, $data)
    {
        if (!empty($data)) {
            $this->db->insert_batch($table, $data);
        }
    }
    public function input_sb_mag($table, $data)
    {
        if (!empty($data)) {
            $this->db->insert($table, $data);
        }
    }

    public function cek_duplikasi_jam_ops($bulan, $tahun, $id_sb_mag)
    {
        $this->db->where('MONTH(tgl_jam_ops)', $bulan); // Ambil bulan dari tanggal di database
        $this->db->where('YEAR(tgl_jam_ops)', $tahun); // Ambil tahun dari tanggal di database
        $this->db->where('id_sb_mag', $id_sb_mag);
        $query = $this->db->get('ek_jam_ops');

        return $query->num_rows() > 0;
    }

    public function update_jo($id_ek_jam_ops, $tgl_jam_ops, $data)
    {
        $this->db->where('id_ek_jam_ops', $id_ek_jam_ops);
        $this->db->where('tgl_jam_ops', $tgl_jam_ops);
        $this->db->update('ek_jam_ops', $data);

        return $this->db->affected_rows(); // Mengembalikan jumlah baris yang terupdate
    }

    public function getByIdTgl_jam_ops($id_sb_mag, $tgl_jam_ops)
    {
        $this->db->select('*');
        $this->db->from('ek_jam_ops');
        $this->db->where('id_sb_mag', $id_sb_mag);
        $this->db->where('tgl_jam_ops', $tgl_jam_ops);
        return $this->db->get()->row(); // Ambil satu baris data
    }

    // akhir jam ops


    public function get_kualitas_air($tahun)
    {
        $this->db->select('tahun_ka,parameter,id_ek_ka, MONTHNAME(tahun_ka) AS bulan, jumlah_sample_int, jumlah_sample_eks, jumlah_terambil, jumlah_sample_oke_ya, jumlah_sample_oke_tidak, tempat_uji');
        $this->db->from('ek_kualitas_air');
        $this->db->where('YEAR(tahun_ka)', $tahun);
        $this->db->order_by('parameter', 'ASC');
        $this->db->order_by('MONTH(tahun_ka)', 'ASC'); // Urutkan berdasarkan parameter lalu bulan
        return $this->db->get()->result();
    }

    public function input_kualitas_air($table, $data_kualitas_air)
    {
        if (!empty($data_kualitas_air)) {
            return $this->db->insert($table, $data_kualitas_air);
        }
    }

    public function get_kualitas_air_by_id($id)
    {
        return $this->db->get_where('ek_kualitas_air', ['id_ek_ka' => $id])->row();
    }

    public function update_kualitas_air($id, $data_ka)
    {
        $this->db->where('id_ek_ka', $id);
        return $this->db->update('ek_kualitas_air', $data_ka);
    }

    public function get_sample_uji($tahun)
    {
        $this->db->select("
            MONTHNAME(tahun_ka) AS bulan, 
            SUM(CASE WHEN parameter = 'FISIK' THEN jumlah_sample_int ELSE 0 END) AS fisika,
            SUM(CASE WHEN parameter = 'MIKROBIOLOGI' THEN jumlah_sample_int ELSE 0 END) AS mikrobiologi,
            SUM(CASE WHEN parameter = 'SISA CHLOR' THEN jumlah_sample_int ELSE 0 END) AS sisa_chlor,
            SUM(CASE WHEN parameter = 'KIMIA WAJIB' THEN jumlah_sample_int ELSE 0 END) AS kimia_wajib,
            SUM(CASE WHEN parameter = 'KIMIA TAMBAHAN' THEN jumlah_sample_int ELSE 0 END) AS kimia_tambahan
        ");
        $this->db->from("ek_kualitas_air");
        $this->db->where("YEAR(tahun_ka)", $tahun);
        $this->db->group_by("MONTH(tahun_ka)");
        $this->db->order_by("MONTH(tahun_ka)", "ASC");

        return $this->db->get()->result_array();
    }

    public function get_uji_syarat($tahun)
    {
        $this->db->select("
            MONTHNAME(tahun_ka) AS bulan, 
            SUM(CASE WHEN parameter = 'FISIK' THEN jumlah_sample_int ELSE 0 END) AS fisika,
            SUM(CASE WHEN parameter = 'MIKROBIOLOGI' THEN jumlah_sample_int ELSE 0 END) AS mikrobiologi,
            SUM(CASE WHEN parameter = 'SISA CHLOR' THEN jumlah_sample_int ELSE 0 END) AS sisa_chlor,
            SUM(CASE WHEN parameter = 'KIMIA WAJIB' THEN jumlah_sample_int ELSE 0 END) AS kimia_wajib,
            SUM(CASE WHEN parameter = 'KIMIA TAMBAHAN' THEN jumlah_sample_int ELSE 0 END) AS kimia_tambahan,
            SUM(CASE WHEN parameter = 'FISIK' THEN jumlah_sample_eks ELSE 0 END) AS fisika_eks,
            SUM(CASE WHEN parameter = 'MIKROBIOLOGI' THEN jumlah_sample_eks ELSE 0 END) AS mikrobiologi_eks,
            SUM(CASE WHEN parameter = 'SISA CHLOR' THEN jumlah_sample_eks ELSE 0 END) AS sisa_chlor_eks,
            SUM(CASE WHEN parameter = 'KIMIA WAJIB' THEN jumlah_sample_eks ELSE 0 END) AS kimia_wajib_eks,
            SUM(CASE WHEN parameter = 'KIMIA TAMBAHAN' THEN jumlah_sample_eks ELSE 0 END) AS kimia_tambahan_eks,
            SUM(CASE WHEN parameter = 'FISIK' THEN jumlah_terambil ELSE 0 END) AS jumlah_terambil,
            SUM(CASE WHEN parameter = 'FISIK' THEN jumlah_sample_eks ELSE 0 END) AS jumlah_terambil_eks,
            SUM(CASE WHEN parameter = 'FISIK' THEN jumlah_sample_oke_ya ELSE 0 END) AS jumlah_syarat,
            
        ");
        $this->db->from("ek_kualitas_air");
        $this->db->where("YEAR(tahun_ka)", $tahun);
        $this->db->group_by("MONTH(tahun_ka)");
        $this->db->order_by("MONTH(tahun_ka)", "ASC");

        return $this->db->get()->result_array();
    }

    // akhir kualitas air

    // kapasitas produksi

    public function get_kapasitas_produksi($tahun)
    {
        $this->db->select('*');
        $this->db->from('ek_kapasitas_prod');
        $this->db->join('bagian_upk', 'ek_kapasitas_prod.id_bagian = bagian_upk.id_bagian', 'left');
        $this->db->where('YEAR(ek_kapasitas_prod.tahun_kp)', $tahun);
        $this->db->order_by('bagian_upk.id_bagian');
        return $this->db->get()->result();
    }

    public function input_kp($table, $data)
    {
        if (!empty($data)) {
            $this->db->insert($table, $data);
        }
    }

    public function get_kapasitas_by_id($id)
    {
        return $this->db->get_where('ek_kapasitas_prod', ['id_ek_kp' => $id])->row();
    }

    public function update_kapasitas($id, $data)
    {
        $this->db->where('id_ek_kp', $id);
        return $this->db->update('ek_kapasitas_prod', $data);
    }
    // akhir kapasitas produksi
}
