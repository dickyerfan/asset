<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_lap_keuangan extends CI_Model
{
    // kode untuk penyisihan piutang
    public function get_all($tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('peny_piutang');
        $this->db->join('kel_tarif', 'peny_piutang.id_kel_tarif = kel_tarif.id_kel_tarif', 'left');
        $this->db->where('YEAR(peny_piutang.tgl_piutang) =', $tahun_lap);
        $this->db->order_by('peny_piutang.tgl_piutang', 'ASC');
        $this->db->order_by('kel_tarif.id_kel_tarif', 'ASC');
        return $this->db->get()->result();
    }

    public function get_all_tahun_range($start_year, $end_year)
    {
        $this->db->select('*');
        $this->db->from('peny_piutang');
        $this->db->join('kel_tarif', 'peny_piutang.id_kel_tarif = kel_tarif.id_kel_tarif', 'left');
        $this->db->where('YEAR(tgl_piutang) >=', $start_year); // Tahun mulai
        $this->db->where('YEAR(tgl_piutang) <=', $end_year);   // Tahun akhir
        $this->db->order_by('peny_piutang.tgl_piutang', 'ASC');
        $this->db->order_by('kel_tarif.id_kel_tarif', 'ASC');
        return $this->db->get()->result();
    }

    public function get_total_by_year_range($start_year, $end_year)
    {
        $this->db->select("YEAR(tgl_piutang) as year, 
                       SUM(saldo_awal) as total_saldo_awal, 
                       SUM(tambah) as total_tambah, 
                       SUM(kurang) as total_kurang, 
                       SUM(saldo_akhir) as total_saldo_akhir, 
                       AVG(persen_tagih) as rata_rata_persen_tagih");
        $this->db->where('YEAR(tgl_piutang) >=', $start_year); // Tahun mulai
        $this->db->where('YEAR(tgl_piutang) <=', $end_year);   // Tahun akhir
        $this->db->group_by("YEAR(tgl_piutang)");
        $this->db->order_by("year", "ASC");
        return $this->db->get('peny_piutang')->result_array();
    }

    public function tambah()
    {
        // Ambil nilai input
        $saldo_awal = (float) $this->input->post('saldo_awal', true);
        $tambah = (float) $this->input->post('tambah', true);
        $kurang = (float) $this->input->post('kurang', true);

        // Hitung nilai saldo_akhir
        $saldo_akhir = $saldo_awal + $tambah - $kurang;

        // Hitung nilai persen_tagih
        $total_awal_tambah = $saldo_awal + $tambah;
        $persen_tagih = $total_awal_tambah > 0 ? ($saldo_akhir / $total_awal_tambah) * 100 : 0;

        // Data yang akan dimasukkan ke database
        $data = [
            'id_kel_tarif' => $this->input->post('id_kel_tarif', true),
            'tgl_piutang' => $this->input->post('tgl_piutang', true),
            'saldo_awal' => $saldo_awal,
            'tambah' => $tambah,
            'kurang' => $kurang,
            'saldo_akhir' => $saldo_akhir,
            'persen_tagih' => $persen_tagih,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];
        // Insert data ke tabel
        $this->db->insert('peny_piutang', $data);
    }

    public function get_kel_tarif()
    {
        $this->db->select('*');
        $this->db->from('kel_tarif');
        return $this->db->get()->result();
    }
    public function get_bank()
    {
        $this->db->select('*');
        $this->db->from('bank');
        return $this->db->get()->result();
    }

    // public function get_bank_input($tahun)
    // {
    //     $this->db->select('*');
    //     $this->db->from('bank_input');
    //     $this->db->join('bank', 'bank_input.id_bank = bank.id_bank', 'left');
    //     $this->db->where('YEAR(tgl_bank) =', $tahun);
    //     return $this->db->get()->result();
    // }

    public function get_bank_input($tahun)
    {
        $tahun_lalu = $tahun - 1;

        $this->db->select('
        bank.nama_bank, 
        SUM(CASE WHEN YEAR(tgl_bank) = ' . $tahun . ' THEN bank_input.jumlah_bank ELSE 0 END) as jumlah_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_bank) = ' . $tahun_lalu . ' THEN bank_input.jumlah_bank ELSE 0 END) as jumlah_tahun_lalu
    ');
        $this->db->from('bank_input');
        $this->db->join('bank', 'bank_input.id_bank = bank.id_bank', 'left');
        $this->db->where('YEAR(tgl_bank) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('bank.nama_bank');

        return $this->db->get()->result();
    }


    public function input_bank()
    {
        date_default_timezone_set('Asia/Jakarta');
        // Ambil nilai input
        $id_bank = $this->input->post('id_bank', true);
        $tgl_bank = $this->input->post('tgl_bank', true);
        $jumlah_bank = (float) $this->input->post('jumlah_bank', true);


        // Data yang akan dimasukkan ke database
        $data = [
            'id_bank' => $this->input->post('id_bank', true),
            'tgl_bank' => $this->input->post('tgl_bank', true),
            'jumlah_bank' => $this->input->post('jumlah_bank', true),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];
        // Insert data ke tabel
        $this->db->insert('bank_input', $data);
    }

    public function get_kas()
    {
        $this->db->select('*');
        $this->db->from('kas');
        return $this->db->get()->result();
    }

    public function input_kas()
    {
        date_default_timezone_set('Asia/Jakarta');
        // Ambil nilai input
        $id_kas = $this->input->post('id_kas', true);
        $tgl_kas = $this->input->post('tgl_kas', true);
        $jumlah_kas = (float) $this->input->post('jumlah_kas', true);


        // Data yang akan dimasukkan ke database
        $data = [
            'id_kas' => $this->input->post('id_kas', true),
            'tgl_kas' => $this->input->post('tgl_kas', true),
            'jumlah_kas' => $this->input->post('jumlah_kas', true),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];
        // Insert data ke tabel
        $this->db->insert('kas_input', $data);
    }

    public function get_hitung_piutang($tahun_lap)
    {
        $this->db->select('*, YEAR(peny_piutang.tgl_piutang) as tahun'); // Tambahkan alias "tahun"
        $this->db->from('peny_piutang');
        $this->db->join('kel_tarif', 'peny_piutang.id_kel_tarif = kel_tarif.id_kel_tarif', 'left');
        if (is_array($tahun_lap)) {
            $this->db->where_in('YEAR(peny_piutang.tgl_piutang)', $tahun_lap);
        } else {
            $this->db->where('YEAR(peny_piutang.tgl_piutang)', $tahun_lap);
        }
        $this->db->order_by('peny_piutang.tgl_piutang', 'ASC');
        $this->db->order_by('kel_tarif.id_kel_tarif', 'ASC');
        return $this->db->get()->result();
    }



    // akhir kode untuk penyisihan piutang
}
