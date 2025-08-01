<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_risiko extends CI_Model
{
    // Ambil data analisa risiko berdasarkan id_risiko
    public function getAnalisaRisikoByProfil($id_upk = null, $tahun = null)
    {
        $this->db->select('*');
        $this->db->from('mr_profil_risiko');
        $this->db->join('mr_analisa_risiko', 'mr_analisa_risiko.id_risiko = mr_profil_risiko.id_risiko');
        if ($id_upk) {
            $this->db->where('mr_profil_risiko.id_upk', $id_upk);
        }
        if ($tahun) {
            $this->db->where('mr_profil_risiko.tahun', $tahun);
        }
        return $this->db->get()->result();
    }

    // Ambil data monitoring risiko berdasarkan id_risiko
    public function getMonitoringRisikoByProfil($id_upk = null, $tahun = null)
    {
        $this->db->select('*');
        $this->db->from('mr_profil_risiko');
        $this->db->join('mr_monitoring_risiko', 'mr_monitoring_risiko.id_risiko = mr_profil_risiko.id_risiko');
        if ($id_upk) {
            $this->db->where('id_upk', $id_upk);
        }
        if ($tahun) {
            $this->db->where('tahun', $tahun);
        }
        return $this->db->get()->result();
    }
    public function getPenangananRisikoByProfil($id_upk = null, $tahun = null)
    {
        $this->db->select('*');
        $this->db->from('mr_profil_risiko');
        $this->db->join('mr_penanganan_risiko', 'mr_penanganan_risiko.id_risiko = mr_profil_risiko.id_risiko');
        if ($id_upk) {
            $this->db->where('id_upk', $id_upk);
        }
        if ($tahun) {
            $this->db->where('tahun', $tahun);
        }
        return $this->db->get()->result();
    }
    // Insert data penangananrisiko
    public function insertPenangananRisiko($id_risiko)
    {
        $data = [
            'id_risiko' => $id_risiko
        ];
        return $this->db->insert('mr_penanganan_risiko', $data);
    }
    // Insert data analisa risiko
    public function insertAnalisaRisiko($id_risiko)
    {
        $data = [
            'id_risiko' => $id_risiko
        ];
        return $this->db->insert('mr_analisa_risiko', $data);
    }

    // Insert data monitoring risiko
    public function insertMonitoringRisiko($id_risiko)
    {
        $data = [
            'id_risiko' => $id_risiko
        ];
        return $this->db->insert('mr_monitoring_risiko', $data);
    }

    // Insert data profil risiko
    public function insertProfilRisiko($data)
    {
        return $this->db->insert('mr_profil_risiko', $data);
    }


    // Ambil data profil risiko
    public function getProfilRisiko($id_upk = null, $tahun = null)
    {
        $this->db->from('mr_profil_risiko');
        if ($id_upk) {
            $this->db->where('id_upk', $id_upk);
        }
        if ($tahun) {
            $this->db->where('tahun', $tahun);
        }
        return $this->db->get()->result();
    }
    public function get_unit_list()
    {
        $this->db->select('*');
        $this->db->from('bagian_upk');
        $this->db->where('status', 1);
        return $this->db->get()->result();
    }
    public function getAllKodeRisiko()
    {
        $this->db->select('*');
        $this->db->from('mr_kategori_risiko');
        $this->db->where('status_kr', 1);
        return $this->db->get()->result();
    }
    public function getAllBagian()
    {
        $this->db->select('*');
        $this->db->from('bagian_upk');
        $this->db->where('status', 1);
        return $this->db->get()->result();
    }
    public function getAllPemilik()
    {
        $this->db->select('*');
        $this->db->from('mr_pemilik_risiko');
        $this->db->where('status_pemilik', 1);
        return $this->db->get()->result();
    }

    public function getProfilRisikoById($id_risiko)
    {
        return $this->db->get_where('mr_profil_risiko', ['id_risiko' => $id_risiko])->row();
    }

    public function getAnalisaRisikoById($id_analisa)
    {
        return $this->db->get_where('mr_analisa_risiko', ['id_analisa' => $id_analisa])->row();
    }
    public function getPenangananRisikoById($id_penanganan)
    {
        return $this->db->get_where('mr_penanganan_risiko', ['id_penanganan' => $id_penanganan])->row();
    }
    public function getMonitoringRisikoById($id_monitoring)
    {
        return $this->db->get_where('mr_monitoring_risiko', ['id_monitoring' => $id_monitoring])->row();
    }

    public function updateProfilRisiko($id_risiko, $data)
    {
        $this->db->where('id_risiko', $id_risiko);
        return $this->db->update('mr_profil_risiko', $data);
    }
    public function updateAnalisaRisiko($id_analisa, $data)
    {
        $this->db->where('id_analisa', $id_analisa);
        return $this->db->update('mr_analisa_risiko', $data);
    }

    public function updatePenangananRisiko($id_penanganan, $data)
    {
        $this->db->where('id_penanganan', $id_penanganan);
        return $this->db->update('mr_penanganan_risiko', $data);
    }

    public function updateMonitoringRisiko($id_monitoring, $data)
    {
        $this->db->where('id_monitoring', $id_monitoring);
        return $this->db->update('mr_monitoring_risiko', $data);
    }

    // CRUD mr_matrik_risiko
    public function getAllMatrik()
    {
        return $this->db->get('mr_matrik_risiko')->result();
    }
    public function insertmatrik($data)
    {
        return $this->db->insert('mr_matrik_risiko', $data);
    }
    public function getmatrikById($id)
    {
        return $this->db->get_where('mr_matrik_risiko', ['id_mr' => $id])->row();
    }
    public function updatematrik($id, $data)
    {
        return $this->db->update('mr_matrik_risiko', $data, ['id_mr' => $id]);
    }

    // CRUD mr_tingkat_risiko
    public function getAllTingkat()
    {
        return $this->db->get('mr_tingkat_risiko')->result();
    }
    public function insertTingkat($data)
    {
        return $this->db->insert('mr_tingkat_risiko', $data);
    }
    public function getTingkatById($id)
    {
        return $this->db->get_where('mr_tingkat_risiko', ['id_tr' => $id])->row();
    }
    public function updateTingkat($id, $data)
    {
        return $this->db->update('mr_tingkat_risiko', $data, ['id_tr' => $id]);
    }

    // CRUD mr_kategori_risiko
    public function getAllKategori()
    {
        return $this->db->get('mr_kategori_risiko')->result();
    }
    public function insertkategori($data)
    {
        return $this->db->insert('mr_kategori_risiko', $data);
    }
    public function getKategoriById($id)
    {
        return $this->db->get_where('mr_kategori_risiko', ['id_kr' => $id])->row();
    }
    public function updateKategori($id, $data)
    {
        return $this->db->update('mr_kategori_risiko', $data, ['id_kr' => $id]);
    }

    public function getTingkatRisiko($probabilitas, $dampak)
    {
        $this->db->select('*');
        $this->db->from('mr_matrik_risiko');
        $this->db->where('probabilitas', $probabilitas);
        $this->db->where('dampak', $dampak);
        return $this->db->get()->row();
    }

    public function insertPemilik($data)
    {
        return $this->db->insert('mr_pemilik_risiko', $data);
    }

    public function getPemilikById($id)
    {
        return $this->db->get_where('mr_pemilik_risiko', ['id_pemilik' => $id])->row();
    }

    public function updatePemilik($id, $data)
    {
        return $this->db->update('mr_pemilik_risiko', $data, ['id_pemilik' => $id]);
    }

    public function getAllPemilikRisiko()
    {
        $this->db->select('*');
        $this->db->from('mr_pemilik_risiko');
        $this->db->where('status_pemilik', 1);
        return $this->db->get()->result();
    }
}
