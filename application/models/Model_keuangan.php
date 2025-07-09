<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_keuangan extends CI_Model
{
    // kejadian penting
    public function get_kej_pen($tahun)
    {
        $this->db->select('*');
        $this->db->from('ek_kej_pen');
        $this->db->where('tahun_kej_pen', $tahun);
        return $this->db->get()->result();
    }

    public function input_kej_pen($table, $data)
    {
        if (!empty($data)) {
            $this->db->insert($table, $data);
        }
    }

    public function get_id_kej_pen($id_kej_pen)
    {
        return $this->db->get_where('ek_kej_pen', ['id_kej_pen' => $id_kej_pen])->row();
    }

    public function update_kej_pen($id_kej_pen, $data)
    {
        $this->db->where('id_kej_pen', $id_kej_pen);
        return $this->db->update('ek_kej_pen', $data);
    }
    // akhir kejadian penting

    // modal pemda
    public function get_modal_pemda($tahun)
    {
        $this->db->select('*');
        $this->db->from('ek_modal_pemda');
        $this->db->join('ek_kecamatan', 'ek_modal_pemda.id_kec = ek_kecamatan.id_kec', 'left');
        $this->db->where('tahun_data <=', $tahun);
        return $this->db->get()->result();
    }

    public function input_modal_pemda($table, $data)
    {
        if (!empty($data)) {
            $this->db->insert($table, $data);
        }
    }

    public function get_kec()
    {
        $this->db->select('*');
        $this->db->from('ek_kecamatan');
        $this->db->where('status', 0);
        $this->db->order_by('nama_kec', 'ASC');
        return $this->db->get()->result();
    }
    // akhir modal pemda

    // hibah
    public function get_hibah($tahun)
    {
        $this->db->select('*');
        $this->db->from('ek_hibah');
        $this->db->join('ek_kecamatan', 'ek_hibah.id_kec = ek_kecamatan.id_kec', 'left');
        $this->db->where('tahun_data <=', $tahun);
        return $this->db->get()->result();
    }

    public function input_hibah($table, $data)
    {
        if (!empty($data)) {
            $this->db->insert($table, $data);
        }
    }
    // akhir hibah

    // modal ybds
    public function get_modal_ybds($tahun)
    {
        $this->db->select('*');
        $this->db->from('ek_modal_ybds');
        $this->db->join('ek_kecamatan', 'ek_modal_ybds.id_kec = ek_kecamatan.id_kec', 'left');
        $this->db->where('tahun_data <=', $tahun);
        return $this->db->get()->result();
    }

    public function input_modal_ybds($table, $data)
    {
        if (!empty($data)) {
            $this->db->insert($table, $data);
        }
    }

    // akhir modal ybds

    // aspek operasional dagri
    public function get_as_ops($tahun)
    {
        $this->db->select('*');
        $this->db->from('ek_aspek_ops_dagri');
        $this->db->where('tahun_aspek', $tahun);
        return $this->db->get()->result();
    }

    // public function input_as_ops($table, $data)
    // {
    //     if (!empty($data)) {
    //         return  $this->db->insert($table, $data);
    //     }
    // }

    public function input_as_ops_batch($table, $data)
    {
        return $this->db->insert_batch($table, $data);
    }

    public function cek_duplikasi($tahun, $label_penilaian)
    {
        $this->db->where('tahun_aspek', $tahun);
        $this->db->where('penilaian', $label_penilaian);
        $query = $this->db->get('ek_aspek_ops_dagri');

        return $query->num_rows() > 0;
    }

    public function get_as_adm($tahun)
    {
        $this->db->select('*');
        $this->db->from('ek_aspek_adm_dagri');
        $this->db->where('tahun_aspek', $tahun);
        return $this->db->get()->result();
    }

    public function input_as_adm_batch($table, $data)
    {
        return $this->db->insert_batch($table, $data);
    }

    public function cek_duplikasi_adm($tahun, $label_penilaian)
    {
        $this->db->where('tahun_aspek', $tahun);
        $this->db->where('penilaian', $label_penilaian);
        $query = $this->db->get('ek_aspek_adm_dagri');

        return $query->num_rows() > 0;
    }

    // akhir aspek operasional dagri
}
