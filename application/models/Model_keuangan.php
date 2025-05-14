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
}
