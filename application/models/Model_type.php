<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_type extends CI_Model
{
    public function getAll()
    {
        return $this->db->get('type')->result();
    }

    public function tambahData()
    {
        $data = [
            'nama_type' => $this->input->post('nama_type', true),
        ];
        $this->db->insert('type', $data);
    }

    public function getIdType($id)
    {
        return $this->db->get_where('type', ['id_type' => $id])->row();
    }

    public function updateData()
    {
        $data = [
            'nama_type' => $this->input->post('nama_type', true),
        ];
        $this->db->where('id_type', $this->input->post('id'));
        $this->db->update('type', $data);
    }

    public function hapusData($id)
    {
        $this->db->where('id_type', $id);
        $this->db->delete('type');
    }
}
