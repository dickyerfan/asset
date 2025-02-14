<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_lap_keuangan extends CI_Model
{
    // Penjelasan neraca
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

    public function get_bank_input($tahun)
    {
        $tahun_lalu = $tahun - 1;

        $this->db->select('
        bank.nama_bank, 
        SUM(CASE WHEN YEAR(tgl_bank) = ' . $tahun . ' THEN bank_input.jumlah_bank ELSE 0 END) as jumlah_bank_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_bank) = ' . $tahun_lalu . ' THEN bank_input.jumlah_bank ELSE 0 END) as jumlah_bank_tahun_lalu
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

    public function get_kas_input($tahun)
    {
        $tahun_lalu = $tahun - 1;

        $this->db->select('
        kas.nama_kas, 
        SUM(CASE WHEN YEAR(tgl_kas) = ' . $tahun . ' THEN kas_input.jumlah_kas ELSE 0 END) as jumlah_kas_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_kas) = ' . $tahun_lalu . ' THEN kas_input.jumlah_kas ELSE 0 END) as jumlah_kas_tahun_lalu
    ');
        $this->db->from('kas_input');
        $this->db->join('kas', 'kas_input.id_kas = kas.id_kas', 'left');
        $this->db->where('YEAR(tgl_kas) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('kas.nama_kas');

        return $this->db->get()->result();
    }

    public function input_deposito()
    {
        $tahun = $this->input->post('tahun', true);
        $nilai_neraca = $this->input->post('nilai_neraca', true);

        // Cek apakah tahun sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('akun', 'Deposito');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            return false; // Tahun sudah ada, tidak boleh insert
        }

        // Jika belum ada, lakukan insert
        $data = [
            'tahun_neraca' => $tahun,
            'kategori' => 'Aset Lancar',
            'akun' => 'Deposito',
            'nilai_neraca' => $nilai_neraca,
            'posisi' => 2,
            'no_neraca' => '1.1.1',
            'status' => 1
        ];
        $this->db->insert('neraca', $data);
        return true;
    }

    // public function input_pbt()
    // {
    //     date_default_timezone_set('Asia/Jakarta');
    //     $tahun = $this->input->post('tgl_pbt', true);
    //     $nama_pbt = $this->input->post('nama_pbt', true);
    //     $jumlah_pbt = $this->input->post('jumlah_pbt', true);

    //     // Cek apakah tahun sudah ada di database
    //     $this->db->where('tgl_pbt', $tahun);
    //     $this->db->where('nama_pbt', $nama_pbt);
    //     $query = $this->db->get('pbt_input');

    //     if ($query->num_rows() > 0) {
    //         return false;
    //     }

    //     // Data yang akan dimasukkan ke database
    //     $data = [
    //         'nama_pbt' => $nama_pbt,
    //         'tgl_pbt' => $tahun,
    //         'jumlah_pbt' => $jumlah_pbt,
    //         'created_at' => date('Y-m-d H:i:s'),
    //         'created_by' => $this->session->userdata('nama_lengkap')
    //     ];
    //     // Insert data ke tabel
    //     $this->db->insert('pbt_input', $data);
    // }

    public function input_pbt()
    {
        date_default_timezone_set('Asia/Jakarta');

        $tahun = $this->input->post('tgl_pbt', true); // Tahun sudah dalam format YYYY
        $nama_pbt = $this->input->post('nama_pbt', true);
        $jumlah_pbt = $this->input->post('jumlah_pbt', true);

        // Cek apakah kombinasi tahun dan nama_pbt sudah ada di database
        $this->db->where('tgl_pbt', $tahun);
        $this->db->where('nama_pbt', $nama_pbt);
        $query = $this->db->get('pbt_input');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_pbt' => $nama_pbt,
            'tgl_pbt' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_pbt' => $jumlah_pbt,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('pbt_input', $data);
    }
    public function input_pdm()
    {
        date_default_timezone_set('Asia/Jakarta');

        $tahun = $this->input->post('tgl_pdm', true); // Tahun sudah dalam format YYYY
        $nama_pdm = $this->input->post('nama_pdm', true);
        $jumlah_pdm = $this->input->post('jumlah_pdm', true);

        // Cek apakah kombinasi tahun dan nama_pdm sudah ada di database
        $this->db->where('tgl_pdm', $tahun);
        $this->db->where('nama_pdm', $nama_pdm);
        $query = $this->db->get('pdm_input');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_pdm' => $nama_pdm,
            'tgl_pdm' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_pdm' => $jumlah_pdm,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('pdm_input', $data);
    }


    public function get_pbt_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_pbt) = ' . $tahun . ' THEN pbt_input.jumlah_pbt ELSE 0 END) as jumlah_pbt_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_pbt) = ' . $tahun_lalu . ' THEN pbt_input.jumlah_pbt ELSE 0 END) as jumlah_pbt_tahun_lalu
    ');
        $this->db->from('pbt_input');
        $this->db->where('YEAR(tgl_pbt) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('pbt_input.nama_pbt');
        $this->db->order_by('pbt_input.id_pbt', 'ASC');
        return $this->db->get()->result();
    }
    public function get_pdm_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_pdm) = ' . $tahun . ' THEN pdm_input.jumlah_pdm ELSE 0 END) as jumlah_pdm_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_pdm) = ' . $tahun_lalu . ' THEN pdm_input.jumlah_pdm ELSE 0 END) as jumlah_pdm_tahun_lalu
    ');
        $this->db->from('pdm_input');
        $this->db->where('YEAR(tgl_pdm) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('pdm_input.nama_pdm');
        $this->db->order_by('pdm_input.id_pdm', 'ASC');
        return $this->db->get()->result();
    }

    // akhir kode untuk penjelasan neraca

    // kode untuk neraca
    public function get_all_neraca($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('*');
        $this->db->from('neraca');
        $this->db->where('tahun_neraca IN(' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->order_by('neraca.posisi', 'ASC');
        return $this->db->get()->result();
    }

    // akhir kode untuk neraca

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

    // kode untuk persediaan

    public function get_persediaan($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('*');
        $this->db->from('persediaan');
        // $this->db->where('tahun_persediaan IN(' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->where('tahun_persediaan', $tahun);
        // $this->db->order_by('neraca.posisi', 'ASC');
        return $this->db->get()->result();
    }
    public function input_persediaan()
    {
        $nama_persediaan = $this->input->post('nama_persediaan', true);
        $tahun_persediaan = $this->input->post('tahun_persediaan', true);
        $harga_perolehan = (float) $this->input->post('harga_perolehan', true);
        $nilai_penurunan = (float) $this->input->post('nilai_penurunan', true);

        $nilai_buku = $harga_perolehan - $nilai_penurunan;

        $this->db->where('tahun_persediaan', $tahun_persediaan);
        $this->db->where('nama_persediaan', $nama_persediaan);
        $query = $this->db->get('persediaan');

        if ($query->num_rows() > 0) {
            return false;
        }

        $data = [
            'tahun_persediaan' => $tahun_persediaan,
            'nama_persediaan' => $nama_persediaan,
            'harga_perolehan' => $harga_perolehan,
            'nilai_penurunan' => $nilai_penurunan,
            'nilai_buku' => $nilai_buku,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];
        return $this->db->insert('persediaan', $data);
    }

    // akhir kode untuk persediaan
}
