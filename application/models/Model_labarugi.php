<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_labarugi extends CI_Model
{

    // kode untuk laba rugi
    public function get_all_sak_ep($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('*');
        $this->db->from('lr_sak_ep');
        $this->db->where('tahun_lr_sak_ep IN(' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->order_by('lr_sak_ep.posisi', 'ASC');
        return $this->db->get()->result();
    }

    // akhir kode untuk laba rugi

    //kode untuk pendapatan

    public function input_ppa()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_ppa', true); // Tahun sudah dalam format YYYY
        $nama_ppa = $this->input->post('nama_ppa', true);
        $jumlah_ppa = $this->input->post('jumlah_ppa', true);

        // Cek apakah kombinasi tahun dan nama_ppa sudah ada di database
        $this->db->where('tgl_ppa', $tahun);
        $this->db->where('nama_ppa', $nama_ppa);
        $query = $this->db->get('lr_ppa');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_ppa' => $nama_ppa,
            'tgl_ppa' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_ppa' => $jumlah_ppa,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('lr_ppa', $data);
    }

    public function get_ppa_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_ppa) = ' . $tahun . ' THEN lr_ppa.jumlah_ppa ELSE 0 END) as jumlah_ppa_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_ppa) = ' . $tahun_lalu . ' THEN lr_ppa.jumlah_ppa ELSE 0 END) as jumlah_ppa_tahun_lalu
    ');
        $this->db->from('lr_ppa');
        $this->db->where('YEAR(tgl_ppa) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('lr_ppa.nama_ppa');
        $this->db->order_by('lr_ppa.id_ppa', 'ASC');
        return $this->db->get()->result();
    }
    public function input_ppna()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_ppna', true); // Tahun sudah dalam format YYYY
        $nama_ppna = $this->input->post('nama_ppna', true);
        $jumlah_ppna = $this->input->post('jumlah_ppna', true);

        // Cek apakah kombinasi tahun dan nama_ppna sudah ada di database
        $this->db->where('tgl_ppna', $tahun);
        $this->db->where('nama_ppna', $nama_ppna);
        $query = $this->db->get('lr_ppna');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_ppna' => $nama_ppna,
            'tgl_ppna' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_ppna' => $jumlah_ppna,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('lr_ppna', $data);
    }

    public function get_ppna_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_ppna) = ' . $tahun . ' THEN lr_ppna.jumlah_ppna ELSE 0 END) as jumlah_ppna_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_ppna) = ' . $tahun_lalu . ' THEN lr_ppna.jumlah_ppna ELSE 0 END) as jumlah_ppna_tahun_lalu
    ');
        $this->db->from('lr_ppna');
        $this->db->where('YEAR(tgl_ppna) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('lr_ppna.nama_ppna');
        $this->db->order_by('lr_ppna.id_ppna', 'ASC');
        return $this->db->get()->result();
    }

    public function input_pk()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_pk', true); // Tahun sudah dalam format YYYY
        $nama_pk = $this->input->post('nama_pk', true);
        $jumlah_pk = $this->input->post('jumlah_pk', true);

        // Cek apakah kombinasi tahun dan nama_pk sudah ada di database
        $this->db->where('tgl_pk', $tahun);
        $this->db->where('nama_pk', $nama_pk);
        $query = $this->db->get('lr_pk');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_pk' => $nama_pk,
            'tgl_pk' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_pk' => $jumlah_pk,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('lr_pk', $data);
    }

    public function get_pk_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_pk) = ' . $tahun . ' THEN lr_pk.jumlah_pk ELSE 0 END) as jumlah_pk_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_pk) = ' . $tahun_lalu . ' THEN lr_pk.jumlah_pk ELSE 0 END) as jumlah_pk_tahun_lalu
    ');
        $this->db->from('lr_pk');
        $this->db->where('YEAR(tgl_pk) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('lr_pk.nama_pk');
        $this->db->order_by('lr_pk.id_pk', 'ASC');
        return $this->db->get()->result();
    }

    public function input_pll()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_pll', true); // Tahun sudah dalam format YYYY
        $nama_pll = $this->input->post('nama_pll', true);
        $jenis_pll = $this->input->post('jenis_pll', true);
        $jumlah_pll = $this->input->post('jumlah_pll', true);

        // Cek apakah kombinasi tahun dan nama_pll sudah ada di database
        $this->db->where('tgl_pll', $tahun);
        $this->db->where('nama_pll', $nama_pll);
        $query = $this->db->get('lr_pll');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_pll' => $nama_pll,
            'jenis_pll' => $jenis_pll,
            'tgl_pll' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_pll' => $jumlah_pll,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('lr_pll', $data);
    }

    public function get_pll_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_pll) = ' . $tahun . ' THEN lr_pll.jumlah_pll ELSE 0 END) as jumlah_pll_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_pll) = ' . $tahun_lalu . ' THEN lr_pll.jumlah_pll ELSE 0 END) as jumlah_pll_tahun_lalu
    ');
        $this->db->from('lr_pll');
        $this->db->where('YEAR(tgl_pll) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('lr_pll.nama_pll');
        $this->db->order_by('lr_pll.id_pll', 'ASC');
        return $this->db->get()->result();
    }
    // kode akhir untuk pendapatan

    //  kode untuk beban

    public function input_bop()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_bop', true); // Tahun sudah dalam format YYYY
        $nama_bop = $this->input->post('nama_bop', true);
        $jenis_bop = $this->input->post('jenis_bop', true);
        $jumlah_bop = $this->input->post('jumlah_bop', true);

        // Cek apakah kombinasi tahun dan nama_bop sudah ada di database
        $this->db->where('tgl_bop', $tahun);
        $this->db->where('nama_bop', $nama_bop);
        $query = $this->db->get('lr_bop');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_bop' => $nama_bop,
            'jenis_bop' => $jenis_bop,
            'tgl_bop' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_bop' => $jumlah_bop,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('lr_bop', $data);
    }

    public function get_bop_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_bop) = ' . $tahun . ' THEN lr_bop.jumlah_bop ELSE 0 END) as jumlah_bop_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_bop) = ' . $tahun_lalu . ' THEN lr_bop.jumlah_bop ELSE 0 END) as jumlah_bop_tahun_lalu
    ');
        $this->db->from('lr_bop');
        $this->db->where('YEAR(tgl_bop) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('lr_bop.nama_bop');
        $this->db->order_by('lr_bop.id_bop', 'ASC');
        return $this->db->get()->result();
    }

    public function input_bpa()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_bpa', true); // Tahun sudah dalam format YYYY
        $nama_bpa = $this->input->post('nama_bpa', true);
        $jenis_bpa = $this->input->post('jenis_bpa', true);
        $jumlah_bpa = $this->input->post('jumlah_bpa', true);

        // Cek apakah kombinasi tahun dan nama_bpa sudah ada di database
        $this->db->where('tgl_bpa', $tahun);
        $this->db->where('nama_bpa', $nama_bpa);
        $query = $this->db->get('lr_bpa');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_bpa' => $nama_bpa,
            'jenis_bpa' => $jenis_bpa,
            'tgl_bpa' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_bpa' => $jumlah_bpa,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('lr_bpa', $data);
    }

    public function get_bpa_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_bpa) = ' . $tahun . ' THEN lr_bpa.jumlah_bpa ELSE 0 END) as jumlah_bpa_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_bpa) = ' . $tahun_lalu . ' THEN lr_bpa.jumlah_bpa ELSE 0 END) as jumlah_bpa_tahun_lalu
    ');
        $this->db->from('lr_bpa');
        $this->db->where('YEAR(tgl_bpa) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('lr_bpa.nama_bpa');
        $this->db->order_by('lr_bpa.id_bpa', 'ASC');
        return $this->db->get()->result();
    }

    public function input_btd()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_btd', true); // Tahun sudah dalam format YYYY
        $nama_btd = $this->input->post('nama_btd', true);
        $jenis_btd = $this->input->post('jenis_btd', true);
        $jumlah_btd = $this->input->post('jumlah_btd', true);

        // Cek apakah kombinasi tahun dan nama_btd sudah ada di database
        $this->db->where('tgl_btd', $tahun);
        $this->db->where('nama_btd', $nama_btd);
        $query = $this->db->get('lr_btd');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_btd' => $nama_btd,
            'jenis_btd' => $jenis_btd,
            'tgl_btd' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_btd' => $jumlah_btd,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('lr_btd', $data);
    }

    public function get_btd_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_btd) = ' . $tahun . ' THEN lr_btd.jumlah_btd ELSE 0 END) as jumlah_btd_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_btd) = ' . $tahun_lalu . ' THEN lr_btd.jumlah_btd ELSE 0 END) as jumlah_btd_tahun_lalu
    ');
        $this->db->from('lr_btd');
        $this->db->where('YEAR(tgl_btd) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('lr_btd.nama_btd');
        $this->db->order_by('lr_btd.id_btd', 'ASC');
        return $this->db->get()->result();
    }

    public function input_bsb()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_bsb', true); // Tahun sudah dalam format YYYY
        $nama_bsb = $this->input->post('nama_bsb', true);
        $jenis_bsb = $this->input->post('jenis_bsb', true);
        $jumlah_bsb = $this->input->post('jumlah_bsb', true);

        // Cek apakah kombinasi tahun dan nama_bsb sudah ada di database
        $this->db->where('tgl_bsb', $tahun);
        $this->db->where('nama_bsb', $nama_bsb);
        $query = $this->db->get('lr_bsb');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_bsb' => $nama_bsb,
            'jenis_bsb' => $jenis_bsb,
            'tgl_bsb' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_bsb' => $jumlah_bsb,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('lr_bsb', $data);
    }

    public function get_bsb_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_bsb) = ' . $tahun . ' THEN lr_bsb.jumlah_bsb ELSE 0 END) as jumlah_bsb_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_bsb) = ' . $tahun_lalu . ' THEN lr_bsb.jumlah_bsb ELSE 0 END) as jumlah_bsb_tahun_lalu
    ');
        $this->db->from('lr_bsb');
        $this->db->where('YEAR(tgl_bsb) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('lr_bsb.nama_bsb');
        $this->db->order_by('lr_bsb.id_bsb', 'ASC');
        return $this->db->get()->result();
    }

    public function input_bua()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_bua', true); // Tahun sudah dalam format YYYY
        $nama_bua = $this->input->post('nama_bua', true);
        $jenis_bua = $this->input->post('jenis_bua', true);
        $jumlah_bua = $this->input->post('jumlah_bua', true);

        // Cek apakah kombinasi tahun dan nama_bua sudah ada di database
        $this->db->where('tgl_bua', $tahun);
        $this->db->where('nama_bua', $nama_bua);
        $query = $this->db->get('lr_bua');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_bua' => $nama_bua,
            'jenis_bua' => $jenis_bua,
            'tgl_bua' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_bua' => $jumlah_bua,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('lr_bua', $data);
    }

    public function get_bua_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_bua) = ' . $tahun . ' THEN lr_bua.jumlah_bua ELSE 0 END) as jumlah_bua_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_bua) = ' . $tahun_lalu . ' THEN lr_bua.jumlah_bua ELSE 0 END) as jumlah_bua_tahun_lalu
    ');
        $this->db->from('lr_bua');
        $this->db->where('YEAR(tgl_bua) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('lr_bua.nama_bua');
        $this->db->order_by('lr_bua.id_bua', 'ASC');
        return $this->db->get()->result();
    }

    public function input_bll()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_bll', true); // Tahun sudah dalam format YYYY
        $nama_bll = $this->input->post('nama_bll', true);
        $jenis_bll = $this->input->post('jenis_bll', true);
        $jumlah_bll = $this->input->post('jumlah_bll', true);

        // Cek apakah kombinasi tahun dan nama_bll sudah ada di database
        $this->db->where('tgl_bll', $tahun);
        $this->db->where('nama_bll', $nama_bll);
        $query = $this->db->get('lr_bll');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_bll' => $nama_bll,
            'jenis_bll' => $jenis_bll,
            'tgl_bll' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_bll' => $jumlah_bll,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('lr_bll', $data);
    }

    public function get_bll_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
        *, 
        SUM(CASE WHEN YEAR(tgl_bll) = ' . $tahun . ' THEN lr_bll.jumlah_bll ELSE 0 END) as jumlah_bll_tahun_ini,
        SUM(CASE WHEN YEAR(tgl_bll) = ' . $tahun_lalu . ' THEN lr_bll.jumlah_bll ELSE 0 END) as jumlah_bll_tahun_lalu
    ');
        $this->db->from('lr_bll');
        $this->db->where('YEAR(tgl_bll) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->group_by('lr_bll.nama_bll');
        $this->db->order_by('lr_bll.id_bll', 'ASC');
        return $this->db->get()->result();
    }
    //  kode akhir untuk beban

    // kode untuk beban pajak
    public function input_bpk()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tgl_bpk', true); // Tahun sudah dalam format YYYY
        $nama_bpk = $this->input->post('nama_bpk', true);
        $jenis_bpk = $this->input->post('jenis_bpk', true);
        $jumlah_bpk = $this->input->post('jumlah_bpk', true);

        // Cek apakah kombinasi tahun dan nama_bpk sudah ada di database
        $this->db->where('tgl_bpk', $tahun);
        $this->db->where('nama_bpk', $nama_bpk);
        $query = $this->db->get('lr_bpk');

        if ($query->num_rows() > 0) {
            return false; // Data sudah ada, return false
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'nama_bpk' => $nama_bpk,
            'jenis_bpk' => $jenis_bpk,
            'tgl_bpk' => $tahun, // Pastikan ini hanya tahun (YYYY)
            'jumlah_bpk' => $jumlah_bpk,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama_lengkap')
        ];

        // Insert data ke tabel dan kembalikan statusnya
        return $this->db->insert('lr_bpk', $data);
    }

    public function get_bpk_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
            *, 
            SUM(CASE WHEN YEAR(tgl_bpk) = ' . $tahun . ' THEN lr_bpk.jumlah_bpk ELSE 0 END) as jumlah_bpk_tahun_ini,
            SUM(CASE WHEN YEAR(tgl_bpk) = ' . $tahun . ' AND lr_bpk.jenis_bpk = "Koreksi Fiskal Negatif" THEN lr_bpk.jumlah_bpk ELSE 0 END) as jumlah_bpk_kurang_tahun_ini,
            SUM(CASE WHEN YEAR(tgl_bpk) = ' . $tahun_lalu . ' THEN lr_bpk.jumlah_bpk ELSE 0 END) as jumlah_bpk_tahun_lalu,
            SUM(CASE WHEN YEAR(tgl_bpk) = ' . $tahun_lalu . ' AND lr_bpk.jenis_bpk = "Koreksi Fiskal Negatif" THEN lr_bpk.jumlah_bpk ELSE 0 END) as jumlah_bpk_kurang_tahun_lalu
        ');
        $this->db->from('lr_bpk');
        $this->db->where('YEAR(tgl_bpk) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->where('lr_bpk.jenis_bpk !=', 'Koreksi Fiskal Negatif'); // Tambahan ini
        $this->db->group_by('lr_bpk.nama_bpk');
        $this->db->order_by('lr_bpk.id_bpk', 'ASC');
        return $this->db->get()->result();
    }
    public function get_bpk_kurang_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
            *, 
            SUM(CASE WHEN YEAR(tgl_bpk) = ' . $tahun . ' AND lr_bpk.jenis_bpk = "Koreksi Fiskal Negatif" THEN lr_bpk.jumlah_bpk ELSE 0 END) as jumlah_bpk_kurang_tahun_ini,
            SUM(CASE WHEN YEAR(tgl_bpk) = ' . $tahun_lalu . ' AND lr_bpk.jenis_bpk = "Koreksi Fiskal Negatif" THEN lr_bpk.jumlah_bpk ELSE 0 END) as jumlah_bpk_kurang_tahun_lalu
        ');
        $this->db->from('lr_bpk');
        $this->db->where('YEAR(tgl_bpk) IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->where('lr_bpk.jenis_bpk', 'Koreksi Fiskal Negatif');
        $this->db->group_by('lr_bpk.nama_bpk');
        $this->db->order_by('lr_bpk.id_bpk', 'ASC');
        return $this->db->get()->result();
    }

    public function get_pendapatan_usaha_input($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $this->db->select('
            *, 
            SUM(CASE WHEN tahun_lr_sak_ep = ' . $tahun . '  THEN lr_sak_ep.nilai_lr_sak_ep ELSE 0 END) as jumlah_pendapatan_usaha_tahun_ini,
            SUM(CASE WHEN tahun_lr_sak_ep = ' . $tahun_lalu . '  THEN lr_sak_ep.nilai_lr_sak_ep ELSE 0 END) as jumlah_pendapatan_usaha_tahun_lalu
        ');
        $this->db->from('lr_sak_ep');
        $this->db->where('tahun_lr_sak_ep IN (' . $tahun . ', ' . $tahun_lalu . ')');
        $this->db->where_in('lr_sak_ep.posisi', [1, 2, 3, 9]);

        // $this->db->group_by('lr_sak_ep.nama_bpk');
        // $this->db->order_by('lr_sak_ep.id_bpk', 'ASC');
        return $this->db->get()->result();
    }

    // public function get_bpk_input($tahun)
    // {
    //     $tahun_lalu = $tahun - 1;
    //     $this->db->select('
    //     lr_bpk.nama_bpk, 
    //     SUM(CASE 
    //         WHEN YEAR(tgl_bpk) = ' . $tahun . ' AND lr_bpk.jenis_bpk != "Koreksi Fiskal Negatif" 
    //         THEN lr_bpk.jumlah_bpk ELSE 0 
    //     END) as jumlah_bpk_tahun_ini,

    //     SUM(CASE 
    //         WHEN YEAR(tgl_bpk) = ' . $tahun . ' AND lr_bpk.jenis_bpk = "Koreksi Fiskal Negatif" 
    //         THEN lr_bpk.jumlah_bpk ELSE 0 
    //     END) as jumlah_bpk_kurang_tahun_ini,

    //     SUM(CASE 
    //         WHEN YEAR(tgl_bpk) = ' . $tahun_lalu . ' AND lr_bpk.jenis_bpk != "Koreksi Fiskal Negatif" 
    //         THEN lr_bpk.jumlah_bpk ELSE 0 
    //     END) as jumlah_bpk_tahun_lalu,

    //     SUM(CASE 
    //         WHEN YEAR(tgl_bpk) = ' . $tahun_lalu . ' AND lr_bpk.jenis_bpk = "Koreksi Fiskal Negatif" 
    //         THEN lr_bpk.jumlah_bpk ELSE 0 
    //     END) as jumlah_bpk_kurang_tahun_lalu
    // ');
    //     $this->db->from('lr_bpk');
    //     $this->db->where('YEAR(tgl_bpk) IN (' . $tahun . ', ' . $tahun_lalu . ')');
    //     $this->db->group_by('lr_bpk.nama_bpk');
    //     $this->db->order_by('lr_bpk.id_bpk', 'ASC');

    //     return $this->db->get()->result();
    // }




    // kode akhir untuk beban pajak
}
