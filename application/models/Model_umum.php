<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_umum extends CI_Model
{

    public function get_kerjasama($tahun)
    {
        $this->db->select('*');
        $this->db->from('ek_kerjasama');
        $this->db->where('tahun_perjanjian', $tahun);
        return $this->db->get()->result();
    }

    public function input_kerjasama($table, $data)
    {
        if (!empty($data)) {
            $this->db->insert($table, $data);
        }
    }

    public function get_id_kerjasama($id_kerjasama)
    {
        return $this->db->get_where('ek_kerjasama', ['id_kerjasama' => $id_kerjasama])->row();
    }

    public function update_kerjasama($id_kerjasama, $data)
    {
        $this->db->where('id_kerjasama', $id_kerjasama);
        return $this->db->update('ek_kerjasama', $data);
    }




    public function get_bagian()
    {
        $this->db->select('*');
        $this->db->from('bagian_upk');
        $this->db->where('status_evkin', 1);
        return $this->db->get()->result();
    }

    public function get_kec()
    {
        $this->db->select('*');
        $this->db->from('ek_kecamatan');
        $this->db->where('status', 0);
        $this->db->order_by('nama_kec', 'ASC');
        return $this->db->get()->result();
    }


    // tambah sr
    public function get_tambah_sr($tahun)
    {
        $this->db->select('
        ek_tambah_sr.tgl_sr, 
        ek_tambah_sr.id_bagian, 
        ek_tambah_sr.status, 
        bagian_upk.nama_bagian, 
        SUM(CASE WHEN MONTH(ek_tambah_sr.tgl_sr) = 1 THEN ek_tambah_sr.jumlah_sr ELSE 0 END) AS jan,
        SUM(CASE WHEN MONTH(ek_tambah_sr.tgl_sr) = 2 THEN ek_tambah_sr.jumlah_sr ELSE 0 END) AS feb,
        SUM(CASE WHEN MONTH(ek_tambah_sr.tgl_sr) = 3 THEN ek_tambah_sr.jumlah_sr ELSE 0 END) AS mar,
        SUM(CASE WHEN MONTH(ek_tambah_sr.tgl_sr) = 4 THEN ek_tambah_sr.jumlah_sr ELSE 0 END) AS apr,
        SUM(CASE WHEN MONTH(ek_tambah_sr.tgl_sr) = 5 THEN ek_tambah_sr.jumlah_sr ELSE 0 END) AS mei,
        SUM(CASE WHEN MONTH(ek_tambah_sr.tgl_sr) = 6 THEN ek_tambah_sr.jumlah_sr ELSE 0 END) AS jun,
        SUM(CASE WHEN MONTH(ek_tambah_sr.tgl_sr) = 7 THEN ek_tambah_sr.jumlah_sr ELSE 0 END) AS jul,
        SUM(CASE WHEN MONTH(ek_tambah_sr.tgl_sr) = 8 THEN ek_tambah_sr.jumlah_sr ELSE 0 END) AS agu,
        SUM(CASE WHEN MONTH(ek_tambah_sr.tgl_sr) = 9 THEN ek_tambah_sr.jumlah_sr ELSE 0 END) AS sep,
        SUM(CASE WHEN MONTH(ek_tambah_sr.tgl_sr) = 10 THEN ek_tambah_sr.jumlah_sr ELSE 0 END) AS okt,
        SUM(CASE WHEN MONTH(ek_tambah_sr.tgl_sr) = 11 THEN ek_tambah_sr.jumlah_sr ELSE 0 END) AS nov,
        SUM(CASE WHEN MONTH(ek_tambah_sr.tgl_sr) = 12 THEN ek_tambah_sr.jumlah_sr ELSE 0 END) AS des,
        COALESCE(SUM(ek_tambah_sr.jumlah_sr), 0) AS total
    ');

        $this->db->from('ek_tambah_sr');
        $this->db->join('bagian_upk', 'bagian_upk.id_bagian = ek_tambah_sr.id_bagian', 'left');
        $this->db->where('YEAR(ek_tambah_sr.tgl_sr)', $tahun);
        $this->db->group_by('bagian_upk.nama_bagian');
        $this->db->order_by('bagian_upk.id_bagian');
        return $this->db->get()->result();
    }

    public function input_tambah_sr($table, $data)
    {
        if (!empty($data)) {
            $this->db->insert_batch($table, $data);
        }
    }

    public function cek_duplikasi_sr($bulan, $tahun, $id_bagian)
    {
        $this->db->where('MONTH(tgl_sr)', $bulan); // Ambil bulan dari tanggal di database
        $this->db->where('YEAR(tgl_sr)', $tahun); // Ambil tahun dari tanggal di database
        $this->db->where('id_bagian', $id_bagian);
        $query = $this->db->get('ek_tambah_sr');

        return $query->num_rows() > 0;
    }

    public function update_sr($id_bagian, $tgl_sr, $data)
    {
        $this->db->where('id_bagian', $id_bagian);
        $this->db->where('tgl_sr', $tgl_sr);
        $this->db->update('ek_tambah_sr', $data);
        return $this->db->affected_rows(); // Mengembalikan jumlah baris yang terupdate
    }

    public function getByIdTgl_sr($id_bagian, $tgl_sr)
    {
        $this->db->select('*');
        $this->db->from('ek_tambah_sr');
        $this->db->where('id_bagian', $id_bagian);
        $this->db->where('tgl_sr', $tgl_sr);
        return $this->db->get()->row(); // Ambil satu baris data
    }

    // akhir tambah sr

    // pengaduan

    public function get_pengaduan($tahun)
    {
        $this->db->select("*,
            DATE_FORMAT(tgl_aduan, '%M') AS bulan,
            jenis_aduan,
            SUM(CASE WHEN jenis_aduan = 'Teknis' THEN 1 ELSE 0 END) AS jumlah_teknis,
            SUM(CASE WHEN jenis_aduan = 'Pelayanan' THEN 1 ELSE 0 END) AS jumlah_pelayanan,
            SUM(CASE WHEN jenis_aduan = 'Rekening_air' THEN 1 ELSE 0 END) AS jumlah_rekening_air
        ");
        $this->db->from('ek_pengaduan');
        $this->db->where('YEAR(tgl_aduan)', $tahun);
        $this->db->group_by(['MONTH(tgl_aduan)', 'jenis_aduan']);
        $this->db->order_by('MONTH(tgl_aduan)', 'ASC');
        return $this->db->get()->result();
    }

    public function input_tambah_aduan($table, $data)
    {
        if (!empty($data)) {
            $this->db->insert_batch($table, $data);
        }
    }

    public function cek_duplikasi_aduan($bulan, $tahun, $aduan)
    {
        $this->db->where('MONTH(tgl_aduan)', $bulan); // Ambil bulan dari tanggal di database
        $this->db->where('YEAR(tgl_aduan)', $tahun); // Ambil tahun dari tanggal di database
        $this->db->where('jenis_aduan', $aduan);
        $query = $this->db->get('ek_pengaduan');

        return $query->num_rows() > 0;
    }

    public function get_id_pengaduan($id_ek_aduan)
    {
        return $this->db->get_where('ek_pengaduan', ['id_ek_aduan' => $id_ek_aduan])->row();
    }

    public function update_aduan($id_ek_aduan, $data)
    {
        $this->db->where('id_ek_aduan', $id_ek_aduan);
        return $this->db->update('ek_pengaduan', $data);
    }

    // akhir pengaduan

    // cakupan layanan
    public function get_data_penduduk($tahun)
    {
        $this->db->select("*");
        $this->db->from('ek_data_penduduk');
        $this->db->join('ek_kecamatan', 'ek_kecamatan.id_kec = ek_data_penduduk.id_kec', 'left');
        $this->db->where('tahun_data', $tahun);
        $this->db->order_by('ek_kecamatan.id_kec');
        return $this->db->get()->result();
    }



    public function input_data_penduduk($table, $data)
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


    public function get_data_pelanggan($tahun)
    {
        $this->db->select("*");
        $this->db->from('ek_data_pelanggan');
        $this->db->join('ek_kecamatan', 'ek_kecamatan.id_kec = ek_data_pelanggan.id_kec', 'left');
        $this->db->where('tahun_data', $tahun);
        $this->db->order_by('ek_kecamatan.id_kec');
        return $this->db->get()->result();
    }

    public function input_data_pelanggan($table, $data)
    {
        if (!empty($data)) {
            $this->db->insert($table, $data);
        }
    }

    // akhir cakupan layanan

    // rincian Pendapatan kecamatan

    public function get_rincian($tahun)
    {
        $this->db->select("*");
        $this->db->from('ek_rincian_pendapatan');
        $this->db->join('ek_kecamatan', 'ek_kecamatan.id_kec = ek_rincian_pendapatan.id_kec', 'left');
        $this->db->join('kel_tarif', 'kel_tarif.id_kel_tarif = ek_rincian_pendapatan.id_kel_tarif', 'left');
        $this->db->where('tahun_data', $tahun);
        // $this->db->order_by('ek_kecamatan.nama_kec', 'ASC');
        return $this->db->get()->result();
    }

    public function input_rincian($table, $data)
    {
        if (!empty($data)) {
            $this->db->insert($table, $data);
        }
    }

    // akhir rincian Pendapatan kecamatan

    // efek tagih
    public function get_efek_tagih($tahun)
    {
        $this->db->select("*");
        $this->db->from('ek_efek_tagih');
        $this->db->join('bagian_upk', 'bagian_upk.id_bagian = ek_efek_tagih.id_bagian', 'left');
        $this->db->where('tahun_data', $tahun);
        $this->db->where('bagian_upk.status_evkin', 1);
        return $this->db->get()->result();
    }

    public function input_efek_tagih($table, $data)
    {
        if (!empty($data)) {
            $this->db->insert($table, $data);
        }
    }
    public function input_sisa_piu($table, $data)
    {
        if (!empty($data)) {
            $this->db->insert($table, $data);
        }
    }

    public function get_sisa_piu($tahun)
    {
        $this->db->select("*");
        $this->db->from('ek_sisa_piutang');
        $this->db->where('tahun_data', $tahun);
        return $this->db->get()->result();
    }

    // akhir efek tagih

    // pendapatan
    public function get_pendapatan($tahun)
    {
        $this->db->select("*");
        $this->db->from('ek_pendapatan');
        $this->db->join('kel_tarif', 'kel_tarif.id_kel_tarif = ek_pendapatan.id_kel_tarif', 'left');
        $this->db->where('tahun_data', $tahun);
        return $this->db->get()->result();
    }

    public function input_pendapatan($table, $data)
    {
        if (!empty($data)) {
            $this->db->insert($table, $data);
        }
    }

    // akhir pendapatan


}
