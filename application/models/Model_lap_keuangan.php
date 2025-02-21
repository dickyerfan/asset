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
        date_default_timezone_set('Asia/Jakarta');
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
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];
        $this->db->insert('neraca', $data);
        return true;
    }

    public function input_atd()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tahun', true);
        $nilai_neraca = $this->input->post('nilai_neraca', true);

        // Cek apakah tahun sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('akun', 'Aset Tetap Dikerjasamakan');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            return false; // Tahun sudah ada, tidak boleh insert
        }

        // Jika belum ada, lakukan insert
        $data = [
            'tahun_neraca' => $tahun,
            'kategori' => 'Aset Tidak Lancar',
            'akun' => 'Aset Tetap Dikerjasamakan',
            'nilai_neraca' => $nilai_neraca,
            'posisi' => 12,
            'no_neraca' => '2.3',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];
        $this->db->insert('neraca', $data);
        return true;
    }

    public function input_aaatb()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tahun', true);
        $nilai_neraca = $this->input->post('nilai_neraca', true);

        // Cek apakah tahun sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('akun', 'Akm Amortisasi Aset Tidak Berwujud');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            return false;
        }

        $data = [
            'tahun_neraca' => $tahun,
            'kategori' => 'Aset Tidak Lancar',
            'akun' => 'Akm Amortisasi Aset Tidak Berwujud',
            'nilai_neraca' => $nilai_neraca,
            'posisi' => 15,
            'no_neraca' => '2.5.1',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];
        $this->db->insert('neraca', $data);
        return true;
    }

    public function input_apt()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tahun', true);
        $nilai_neraca = $this->input->post('nilai_neraca', true);

        // Cek apakah tahun sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('akun', 'Aset Pajak Tangguhan');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            return false;
        }

        $data = [
            'tahun_neraca' => $tahun,
            'kategori' => 'Aset Tidak Lancar',
            'akun' => 'Aset Pajak Tangguhan',
            'nilai_neraca' => $nilai_neraca,
            'posisi' => 16,
            'no_neraca' => '2.6',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];
        $this->db->insert('neraca', $data);
        return true;
    }

    public function input_pajak_pnd()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tahun', true);
        $nilai_neraca = $this->input->post('nilai_neraca', true);

        // Cek apakah tahun sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('akun', 'Pajak Pertambahan Nilai Dimuka');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            return false; // Tahun sudah ada, tidak boleh insert
        }

        // Jika belum ada, lakukan insert
        $data = [
            'tahun_neraca' => $tahun,
            'kategori' => 'Aset Lancar',
            'akun' => 'Pajak Pertambahan Nilai Dimuka',
            'nilai_neraca' => $nilai_neraca,
            'posisi' => 9,
            'no_neraca' => '1.8',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];
        $this->db->insert('neraca', $data);
        return true;
    }

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

    public function input_atdp()
    {
        date_default_timezone_set('Asia/Jakarta');

        $tahun = $this->input->post('tgl_atdp', true);
        $nama_atdp = $this->input->post('nama_atdp', true);
        $jumlah_atdp = $this->input->post('jumlah_atdp', true);

        $this->db->where('tgl_atdp', $tahun);
        $this->db->where('nama_atdp', $nama_atdp);
        $query = $this->db->get('atdp_input');

        if ($query->num_rows() > 0) {
            return false;
        }

        $data = [
            'nama_atdp' => $nama_atdp,
            'tgl_atdp' => $tahun,
            'jumlah_atdp' => $jumlah_atdp,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];
        return $this->db->insert('atdp_input', $data);
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

    public function get_atdp_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_atdp) = ' . $tahun . ' THEN atdp_input.jumlah_atdp ELSE 0 END) as jumlah_atdp_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_atdp) = ' . $tahun_lalu . ' THEN atdp_input.jumlah_atdp ELSE 0 END) as jumlah_atdp_tahun_lalu
    ');
        $this->db->from('atdp_input');
        $this->db->where('YEAR(tgl_atdp) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('atdp_input.nama_atdp');
        $this->db->order_by('atdp_input.id_atdp', 'ASC');
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
        // $this->db->order_by('neraca.no_neraca', 'ASC');
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
        date_default_timezone_set('Asia/Jakarta');
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
        date_default_timezone_set('Asia/Jakarta');
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

    //kode untuk hutang
    public function input_hutang_usaha()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tahun', true);
        $nilai_neraca = $this->input->post('nilai_neraca', true);

        // Cek apakah tahun sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('akun', 'Utang Usaha');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            return false; // Tahun sudah ada, tidak boleh insert
        }

        // Jika belum ada, lakukan insert
        $data = [
            'tahun_neraca' => $tahun,
            'kategori' => 'Liabilitas Jangka Pendek',
            'akun' => 'Utang Usaha',
            'nilai_neraca' => $nilai_neraca,
            'posisi' => 17,
            'no_neraca' => '3.1',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];
        $this->db->insert('neraca', $data);
        return true;
    }

    public function input_hnu()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_hnu', true); // Tahun sudah dalam format YYYY
        $nama_hnu = $this->input->post('nama_hnu', true);
        $jumlah_hnu = $this->input->post('jumlah_hnu', true);

        // Cek apakah kombinasi tahun dan nama_hnu sudah ada di database
        $this->db->where('tgl_hnu', $tahun);
        $this->db->where('nama_hnu', $nama_hnu);
        $query = $this->db->get('hnu_input');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_hnu' => $nama_hnu,
            'tgl_hnu' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_hnu' => $jumlah_hnu,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('hnu_input', $data);
    }

    public function get_hnu_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_hnu) = ' . $tahun . ' THEN hnu_input.jumlah_hnu ELSE 0 END) as jumlah_hnu_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_hnu) = ' . $tahun_lalu . ' THEN hnu_input.jumlah_hnu ELSE 0 END) as jumlah_hnu_tahun_lalu
    ');
        $this->db->from('hnu_input');
        $this->db->where('YEAR(tgl_hnu) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('hnu_input.nama_hnu');
        $this->db->order_by('hnu_input.id_hnu', 'ASC');
        return $this->db->get()->result();
    }

    public function input_pdd()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_pdd', true); // Tahun sudah dalam format YYYY
        $nama_pdd = $this->input->post('nama_pdd', true);
        $jumlah_pdd = $this->input->post('jumlah_pdd', true);

        // Cek apakah kombinasi tahun dan nama_pdd sudah ada di database
        $this->db->where('tgl_pdd', $tahun);
        $this->db->where('nama_pdd', $nama_pdd);
        $query = $this->db->get('pdd_input');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_pdd' => $nama_pdd,
            'tgl_pdd' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_pdd' => $jumlah_pdd,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('pdd_input', $data);
    }

    public function get_pdd_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_pdd) = ' . $tahun . ' THEN pdd_input.jumlah_pdd ELSE 0 END) as jumlah_pdd_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_pdd) = ' . $tahun_lalu . ' THEN pdd_input.jumlah_pdd ELSE 0 END) as jumlah_pdd_tahun_lalu
    ');
        $this->db->from('pdd_input');
        $this->db->where('YEAR(tgl_pdd) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('pdd_input.nama_pdd');
        $this->db->order_by('pdd_input.id_pdd', 'ASC');
        return $this->db->get()->result();
    }

    public function input_utsr()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_utsr', true); // Tahun sudah dalam format YYYY
        $nama_utsr = $this->input->post('nama_utsr', true);
        $jumlah_utsr = $this->input->post('jumlah_utsr', true);

        // Cek apakah kombinasi tahun dan nama_utsr sudah ada di database
        $this->db->where('tgl_utsr', $tahun);
        $this->db->where('nama_utsr', $nama_utsr);
        $query = $this->db->get('utsr_input');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_utsr' => $nama_utsr,
            'tgl_utsr' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_utsr' => $jumlah_utsr,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('utsr_input', $data);
    }

    public function get_utsr_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_utsr) = ' . $tahun . ' THEN utsr_input.jumlah_utsr ELSE 0 END) as jumlah_utsr_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_utsr) = ' . $tahun_lalu . ' THEN utsr_input.jumlah_utsr ELSE 0 END) as jumlah_utsr_tahun_lalu
    ');
        $this->db->from('utsr_input');
        $this->db->where('YEAR(tgl_utsr) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('utsr_input.nama_utsr');
        $this->db->order_by('utsr_input.id_utsr', 'ASC');
        return $this->db->get()->result();
    }

    public function input_hnu_lain()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_hnu_lain', true); // Tahun sudah dalam format YYYY
        $nama_hnu_lain = $this->input->post('nama_hnu_lain', true);
        $jumlah_hnu_lain = $this->input->post('jumlah_hnu_lain', true);

        // Cek apakah kombinasi tahun dan nama_hnu_lain sudah ada di database
        $this->db->where('tgl_hnu_lain', $tahun);
        $this->db->where('nama_hnu_lain', $nama_hnu_lain);
        $query = $this->db->get('hnu_lain_input');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_hnu_lain' => $nama_hnu_lain,
            'tgl_hnu_lain' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_hnu_lain' => $jumlah_hnu_lain,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('hnu_lain_input', $data);
    }

    public function get_hnu_lain_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_hnu_lain) = ' . $tahun . ' THEN hnu_lain_input.jumlah_hnu_lain ELSE 0 END) as jumlah_hnu_lain_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_hnu_lain) = ' . $tahun_lalu . ' THEN hnu_lain_input.jumlah_hnu_lain ELSE 0 END) as jumlah_hnu_lain_tahun_lalu
    ');
        $this->db->from('hnu_lain_input');
        $this->db->where('YEAR(tgl_hnu_lain) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('hnu_lain_input.nama_hnu_lain');
        $this->db->order_by('hnu_lain_input.id_hnu_lain', 'ASC');
        return $this->db->get()->result();
    }

    public function input_bymhd()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_bymhd', true); // Tahun sudah dalam format YYYY
        $nama_bymhd = $this->input->post('nama_bymhd', true);
        $jumlah_bymhd = $this->input->post('jumlah_bymhd', true);

        // Cek apakah kombinasi tahun dan nama_bymhd sudah ada di database
        $this->db->where('tgl_bymhd', $tahun);
        $this->db->where('nama_bymhd', $nama_bymhd);
        $query = $this->db->get('bymhd_input');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_bymhd' => $nama_bymhd,
            'tgl_bymhd' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_bymhd' => $jumlah_bymhd,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('bymhd_input', $data);
    }

    public function get_bymhd_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_bymhd) = ' . $tahun . ' THEN bymhd_input.jumlah_bymhd ELSE 0 END) as jumlah_bymhd_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_bymhd) = ' . $tahun_lalu . ' THEN bymhd_input.jumlah_bymhd ELSE 0 END) as jumlah_bymhd_tahun_lalu
    ');
        $this->db->from('bymhd_input');
        $this->db->where('YEAR(tgl_bymhd) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('bymhd_input.nama_bymhd');
        $this->db->order_by('bymhd_input.id_bymhd', 'ASC');
        return $this->db->get()->result();
    }

    public function input_up()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_up', true); // Tahun sudah dalam format YYYY
        $nama_up = $this->input->post('nama_up', true);
        $jumlah_up = $this->input->post('jumlah_up', true);

        // Cek apakah kombinasi tahun dan nama_up sudah ada di database
        $this->db->where('tgl_up', $tahun);
        $this->db->where('nama_up', $nama_up);
        $query = $this->db->get('up_input');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_up' => $nama_up,
            'tgl_up' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_up' => $jumlah_up,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('up_input', $data);
    }

    public function get_up_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_up) = ' . $tahun . ' THEN up_input.jumlah_up ELSE 0 END) as jumlah_up_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_up) = ' . $tahun_lalu . ' THEN up_input.jumlah_up ELSE 0 END) as jumlah_up_tahun_lalu
    ');
        $this->db->from('up_input');
        $this->db->where('YEAR(tgl_up) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('up_input.nama_up');
        $this->db->order_by('up_input.id_up', 'ASC');
        return $this->db->get()->result();
    }

    public function input_lipkd()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tahun', true);
        $nilai_neraca = $this->input->post('nilai_neraca', true);

        // Cek apakah tahun sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('akun', 'Liabilitas Imbalan Pasca Kerja Dapenma');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            return false; // Tahun sudah ada, tidak boleh insert
        }

        // Jika belum ada, lakukan insert
        $data = [
            'tahun_neraca' => $tahun,
            'kategori' => 'Liabilitas Jangka Pendek',
            'akun' => 'Liabilitas Imbalan Pasca Kerja Dapenma',
            'nilai_neraca' => $nilai_neraca,
            'posisi' => 21,
            'no_neraca' => '3.5',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];
        $this->db->insert('neraca', $data);
        return true;
    }
    public function input_lipk()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tahun', true);
        $nilai_neraca = $this->input->post('nilai_neraca', true);

        // Cek apakah tahun sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('akun', 'Liabilitas Imbalan Pasca Kerja');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            return false; // Tahun sudah ada, tidak boleh insert
        }

        // Jika belum ada, lakukan insert
        $data = [
            'tahun_neraca' => $tahun,
            'kategori' => 'Liabilitas Jangka Pendek',
            'akun' => 'Liabilitas Imbalan Pasca Kerja',
            'nilai_neraca' => $nilai_neraca,
            'posisi' => 22,
            'no_neraca' => '3.6',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];
        $this->db->insert('neraca', $data);
        return true;
    }
    public function input_ujpl()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tahun', true);
        $nilai_neraca = $this->input->post('nilai_neraca', true);

        // Cek apakah tahun sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('akun', 'Utang Jangka Pendek Lainnya');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            return false; // Tahun sudah ada, tidak boleh insert
        }

        // Jika belum ada, lakukan insert
        $data = [
            'tahun_neraca' => $tahun,
            'kategori' => 'Liabilitas Jangka Pendek',
            'akun' => 'Utang Jangka Pendek Lainnya',
            'nilai_neraca' => $nilai_neraca,
            'posisi' => 23,
            'no_neraca' => '3.7',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];
        $this->db->insert('neraca', $data);
        return true;
    }

    public function input_lipkdpj()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tahun', true);
        $nilai_neraca = $this->input->post('nilai_neraca', true);

        // Cek apakah tahun sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('akun', 'Liabilitas Imbalan Pasca Kerja Dapenma (pj)');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            return false; // Tahun sudah ada, tidak boleh insert
        }

        // Jika belum ada, lakukan insert
        $data = [
            'tahun_neraca' => $tahun,
            'kategori' => 'Liabilitas Jangka Panjang',
            'akun' => 'Liabilitas Imbalan Pasca Kerja Dapenma (pj)',
            'nilai_neraca' => $nilai_neraca,
            'posisi' => 24,
            'no_neraca' => '4.1',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];
        $this->db->insert('neraca', $data);
        return true;
    }
    public function input_lipkpj()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tahun', true);
        $nilai_neraca = $this->input->post('nilai_neraca', true);

        // Cek apakah tahun sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('akun', 'Liabilitas Imbalan Pasca Kerja (pj)');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            return false; // Tahun sudah ada, tidak boleh insert
        }

        // Jika belum ada, lakukan insert
        $data = [
            'tahun_neraca' => $tahun,
            'kategori' => 'Liabilitas Jangka Panjang',
            'akun' => 'Liabilitas Imbalan Pasca Kerja (pj)',
            'nilai_neraca' => $nilai_neraca,
            'posisi' => 25,
            'no_neraca' => '4.2',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];
        $this->db->insert('neraca', $data);
        return true;
    }

    public function input_lpt()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tahun', true);
        $nilai_neraca = $this->input->post('nilai_neraca', true);

        // Cek apakah tahun sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('akun', 'Liabilitas Pajak Tanggguhan');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            return false; // Tahun sudah ada, tidak boleh insert
        }

        // Jika belum ada, lakukan insert
        $data = [
            'tahun_neraca' => $tahun,
            'kategori' => 'Liabilitas Jangka Panjang',
            'akun' => 'Liabilitas Pajak Tanggguhan',
            'nilai_neraca' => $nilai_neraca,
            'posisi' => 26,
            'no_neraca' => '4.3',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];
        $this->db->insert('neraca', $data);
        return true;
    }

    public function input_kll()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_kll', true); // Tahun sudah dalam format YYYY
        $nama_kll = $this->input->post('nama_kll', true);
        $jumlah_kll = $this->input->post('jumlah_kll', true);

        // Cek apakah kombinasi tahun dan nama_up sudah ada di database
        $this->db->where('tgl_kll', $tahun);
        $this->db->where('nama_kll', $nama_up);
        $query = $this->db->get('kll_input');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_kll' => $nama_kll,
            'tgl_kll' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_kll' => $jumlah_kll,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('kll_input', $data);
    }

    public function get_kll_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_kll) = ' . $tahun . ' THEN kll_input.jumlah_kll ELSE 0 END) as jumlah_kll_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_kll) = ' . $tahun_lalu . ' THEN kll_input.jumlah_kll ELSE 0 END) as jumlah_kll_tahun_lalu
    ');
        $this->db->from('kll_input');
        $this->db->where('YEAR(tgl_kll) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('kll_input.nama_kll');
        $this->db->order_by('kll_input.id_kll', 'ASC');
        return $this->db->get()->result();
    }

    // akhir kode untuk hutang

    // ekuitas
    public function input_mh()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_mh', true); // Tahun sudah dalam format YYYY
        $nama_mh = $this->input->post('nama_mh', true);
        $jumlah_mh = $this->input->post('jumlah_mh', true);

        // Cek apakah kombinasi tahun dan nama_up sudah ada di database
        $this->db->where('tgl_mh', $tahun);
        $this->db->where('nama_mh', $nama_up);
        $query = $this->db->get('mh_input');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_mh' => $nama_mh,
            'tgl_mh' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_mh' => $jumlah_mh,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('mh_input', $data);
    }

    public function get_mh_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_mh) = ' . $tahun . ' THEN mh_input.jumlah_mh ELSE 0 END) as jumlah_mh_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_mh) = ' . $tahun_lalu . ' THEN mh_input.jumlah_mh ELSE 0 END) as jumlah_mh_tahun_lalu
    ');
        $this->db->from('mh_input');
        $this->db->where('YEAR(tgl_mh) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('mh_input.nama_mh');
        $this->db->order_by('mh_input.id_mh', 'ASC');
        return $this->db->get()->result();
    }

    public function input_cu()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_cu', true); // Tahun sudah dalam format YYYY
        $nama_cu = $this->input->post('nama_cu', true);
        $jumlah_cu = $this->input->post('jumlah_cu', true);

        // Cek apakah kombinasi tahun dan nama_up sudah ada di database
        $this->db->where('tgl_cu', $tahun);
        $this->db->where('nama_cu', $nama_up);
        $query = $this->db->get('cu_input');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_cu' => $nama_cu,
            'tgl_cu' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_cu' => $jumlah_cu,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('cu_input', $data);
    }

    public function get_cu_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_cu) = ' . $tahun . ' THEN cu_input.jumlah_cu ELSE 0 END) as jumlah_cu_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_cu) = ' . $tahun_lalu . ' THEN cu_input.jumlah_cu ELSE 0 END) as jumlah_cu_tahun_lalu
    ');
        $this->db->from('cu_input');
        $this->db->where('YEAR(tgl_cu) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('cu_input.nama_cu');
        $this->db->order_by('cu_input.id_cu', 'ASC');
        return $this->db->get()->result();
    }

    public function input_cb()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tahun', true);
        $nilai_neraca = $this->input->post('nilai_neraca', true);

        // Cek apakah tahun sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('akun', 'Cadangan Bertujuan');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            return false; // Tahun sudah ada, tidak boleh insert
        }

        // Jika belum ada, lakukan insert
        $data = [
            'tahun_neraca' => $tahun,
            'kategori' => 'Ekuitas',
            'akun' => 'Cadangan Bertujuan',
            'nilai_neraca' => $nilai_neraca,
            'posisi' => 32,
            'no_neraca' => '5.4',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];
        $this->db->insert('neraca', $data);
        return true;
    }

    public function input_pkipk()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tahun', true);
        $nilai_neraca = $this->input->post('nilai_neraca', true);

        // Cek apakah tahun sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('akun', 'Pengukuran Kembali Imbalan Paska Kerja');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            return false; // Tahun sudah ada, tidak boleh insert
        }

        // Jika belum ada, lakukan insert
        $data = [
            'tahun_neraca' => $tahun,
            'kategori' => 'Ekuitas',
            'akun' => 'Pengukuran Kembali Imbalan Paska Kerja',
            'nilai_neraca' => $nilai_neraca * -1,
            'posisi' => 33,
            'no_neraca' => '5.4.1',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];
        $this->db->insert('neraca', $data);
        return true;
    }

    public function input_aktl()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_aktl', true); // Tahun sudah dalam format YYYY
        $nama_aktl = $this->input->post('nama_aktl', true);
        $jumlah_aktl = $this->input->post('jumlah_aktl', true);

        // Cek apakah kombinasi tahun dan nama_up sudah ada di database
        $this->db->where('tgl_aktl', $tahun);
        $this->db->where('nama_aktl', $nama_up);
        $query = $this->db->get('aktl_input');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_aktl' => $nama_aktl,
            'tgl_aktl' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_aktl' => $jumlah_aktl,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('aktl_input', $data);
    }

    public function get_aktl_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_aktl) = ' . $tahun . ' THEN aktl_input.jumlah_aktl ELSE 0 END) as jumlah_aktl_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_aktl) = ' . $tahun_lalu . ' THEN aktl_input.jumlah_aktl ELSE 0 END) as jumlah_aktl_tahun_lalu
    ');
        $this->db->from('aktl_input');
        $this->db->where('YEAR(tgl_aktl) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('aktl_input.nama_aktl');
        $this->db->order_by('aktl_input.id_aktl', 'ASC');
        return $this->db->get()->result();
    }
    // akhir ekuitas
}
