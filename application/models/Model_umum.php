<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_umum extends CI_Model
{
    // kerjasama
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
    // akhir kerjasama

    // data umum
    // public function get_data_umum($tahun)
    // {
    //     $this->db->select('*');
    //     $this->db->from('ek_data_umum');
    //     $this->db->where('tahun', $tahun);
    //     return $this->db->get()->result();
    // }
    public function get_data_umum($tahun, $uraian = null)
    {
        $this->db->select('*');
        $this->db->from('ek_data_umum');
        $this->db->where('tahun', $tahun);

        if ($uraian !== null) {
            $this->db->where('uraian', $uraian); // filter kalau ada uraian
        }

        return $this->db->get()->result();
    }

    public function input_data_umum($table, $data)
    {
        if (!empty($data)) {
            $this->db->insert($table, $data);
        }
    }

    public function get_id_data_umum($id_data_umum)
    {
        return $this->db->get_where('ek_data_umum', ['id_data_umum' => $id_data_umum])->row();
    }

    public function update_data_umum($id_data_umum, $data)
    {
        $this->db->where('id_data_umum', $id_data_umum);
        return $this->db->update('ek_data_umum', $data);
    }

    // akhir data umum

    // data karyawan
    public function get_all_karyawan($tahun)
    {
        $this->db->select('*');
        $this->db->from('pegawai');
        $this->db->join('bagian', 'bagian.id_bagian = pegawai.id_bagian');
        $this->db->join('subag', 'subag.id_subag = pegawai.id_subag');
        $this->db->join('jabatan', 'jabatan.id_jabatan = pegawai.id_jabatan');
        $this->db->order_by('id', 'ASC');
        // $this->db->where('aktif', '1');
        $this->db->where('YEAR(tgl_masuk) <=', $tahun);
        $this->db->where_in('status_pegawai', ['Karyawan Tetap', 'Karyawan Kontrak']);
        $this->db->where('(tahun_keluar IS NULL OR tahun_keluar > ' . $this->db->escape($tahun) . ')');
        return $this->db->get()->result();
    }

    public function getIdKaryawan($id)
    {
        return $this->db->get_where('pegawai', ['id' => $id])->row();
    }
    public function update_pegawai($id, $data_pegawai)
    {
        $this->db->where('id', $id);
        return $this->db->update('pegawai', $data_pegawai);
    }

    public function get_jumlah_pegawai($tahun)
    {
        $this->db->select('COUNT(*) as total');
        $this->db->from('pegawai');
        $this->db->join('bagian', 'bagian.id_bagian = pegawai.id_bagian');
        $this->db->join('subag', 'subag.id_subag = pegawai.id_subag');
        $this->db->join('jabatan', 'jabatan.id_jabatan = pegawai.id_jabatan');
        // Filter tahun masuk <= tahun yang dipilih
        $this->db->where('YEAR(tgl_masuk) <=', $tahun);
        // Filter status pegawai
        $this->db->where_in('status_pegawai', ['Karyawan Tetap', 'Karyawan Kontrak']);
        // Pegawai belum keluar atau keluar setelah tahun yang dipilih
        $this->db->where('(tahun_keluar IS NULL OR tahun_keluar > ' . $this->db->escape($tahun) . ')');
        // Eksekusi dan ambil hasil
        $query = $this->db->get();
        $result = $query->row();

        return $result ? $result->total : 0;
    }

    // akhir data karyawan
}
