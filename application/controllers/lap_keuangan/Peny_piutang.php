<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Peny_piutang extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_lap_keuangan');
        $this->load->library('form_validation');
        if (!$this->session->userdata('nama_pengguna')) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Maaf,</strong> Anda harus login untuk akses halaman ini...
                      </div>'
            );
            redirect('auth');
        }

        $bagian = $this->session->userdata('bagian');
        if ($bagian != 'Keuangan' && $bagian != 'Administrator' && $bagian != 'Auditor') {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Maaf,</strong> Anda tidak memiliki hak akses untuk halaman ini...
                  </div>'
            );
            redirect('auth');
        }
    }
    public function index()
    {
        $tanggal = $this->input->get('tahun');
        $tahun = substr($tanggal, 0, 4);

        if (empty($tanggal)) {
            $tanggal = date('Y-m-d');
            $bulan = date('m');
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun', $tanggal);
        }

        $data['tahun_lap'] = $tahun;

        $data['title'] = 'Perhitungan Penyisihan Kerugian Piutang';
        $data['piutang'] = $this->Model_lap_keuangan->get_all($tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/view_peny_piutang', $data);
        $this->load->view('templates/footer');
    }

    public function data_total()
    {
        $tanggal = $this->input->get('tahun') ?? date('Y');
        $tahun = (int)substr($tanggal, 0, 4);

        if (empty($tanggal)) {
            $tanggal = date('Y-m-d');
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun', $tanggal);
        }

        $start_year = $tahun - 2; // Tahun mulai (3 tahun terakhir)
        $end_year = $tahun;      // Tahun akhir

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'Perhitungan Penyisihan Kerugian Piutang Daftar Mutasi Piutang Air 3 Tahun Terakhir';
        $piutang = $this->Model_lap_keuangan->get_all_tahun_range($start_year, $end_year); // Data sesuai range tahun
        $totals = $this->Model_lap_keuangan->get_total_by_year_range($start_year, $end_year); // Total berdasarkan range tahun

        $grouped_data = [];
        foreach ($piutang as $row) {
            $year = date('Y', strtotime($row->tgl_piutang));
            $grouped_data[$year][] = $row;
        }

        $data = [
            'title' => 'Penyisihan Piutang',
            'tahun_lap' => $tahun,
            'piutang' => $grouped_data,
            'totals' => array_column($totals, null, 'year'), // Total berdasarkan tahun sebagai indeks
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/view_peny_piutang_total', $data);
        $this->load->view('templates/footer');
    }

    public function data_total_cetak()
    {
        $tahun = $this->session->userdata('tahun');

        if (empty($tahun)) {
            $this->session->unset_userdata('tahun');
            $tahun = date('Y');
        }
        // $data['tahun_lap'] = $tahun;
        $start_year = $tahun - 2;
        $end_year = $tahun;

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'Perhitungan Penyisihan Kerugian Piutang Daftar Mutasi Piutang Air 3 Tahun Terakhir';
        $piutang = $this->Model_lap_keuangan->get_all_tahun_range($start_year, $end_year);
        $totals = $this->Model_lap_keuangan->get_total_by_year_range($start_year, $end_year);

        $grouped_data = [];
        foreach ($piutang as $row) {
            $year = date('Y', strtotime($row->tgl_piutang));
            $grouped_data[$year][] = $row;
        }

        $data = [
            'title' => 'Penyisihan Piutang',
            'tahun_lap' => $tahun,
            'piutang' => $grouped_data,
            'totals' => array_column($totals, null, 'year'),
        ];

        $this->pdf->setPaper('folio', 'portrait');
        $this->pdf->filename = "peny_piutang-{$tahun}.pdf";
        $this->pdf->generate('cetakan_lap_keuangan/peny_piutang_pdf', $data);
    }

    public function tambah()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('id_kel_tarif', 'Kelompok Tarif', 'required|trim');
        $this->form_validation->set_rules('tgl_piutang', 'Tanggal', 'required|trim');
        $this->form_validation->set_rules('saldo_awal', 'Saldo Awal', 'required|trim|numeric');
        $this->form_validation->set_rules('tambah', 'Tambah', 'required|trim|numeric');
        $this->form_validation->set_rules('kurang', 'Kurang', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Penyisihan Piutang';
            $data['kel_tarif'] = $this->Model_lap_keuangan->get_kel_tarif();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_peny_piutang', $data);
            $this->load->view('templates/footer');
        } else {
            $data['piutang'] = $this->Model_lap_keuangan->tambah();
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Data Penyisihan Piutang baru berhasil di tambah
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/peny_piutang');
        }
    }

    public function hitung_piutang()
    {
        $tanggal = $this->input->get('tahun') ?? date('Y');
        $tahun = (int)substr($tanggal, 0, 4);

        $start_year = $tahun - 2; // 2 tahun lalu
        $end_year = $tahun;      // Tahun ini

        $data['tahun_lap'] = $tahun;
        $data['dua_tahun_lalu'] = $start_year;
        $data['tahun_lalu'] = $tahun - 1;
        $data['title'] = 'Perhitungan Penyisihan Piutang';
        $piutang = $this->Model_lap_keuangan->get_all_tahun_range($start_year, $end_year);
        $totals = $this->Model_lap_keuangan->get_total_by_year_range($start_year, $end_year);

        // Group data by `kel_tarif_ket` and year
        $grouped_data = [];
        foreach ($piutang as $row) {
            $year = date('Y', strtotime($row->tgl_piutang));
            $grouped_data[$row->kel_tarif_ket][$year] = $row;
        }

        $final_data = [];
        $totals = [
            '2_years_ago' => 0,
            'last_year' => 0,
            'this_year' => 0,
            'average' => 0,
            'saldo_this_year' => 0,
            'adjusted_piutang' => 0,
        ];
        foreach ($grouped_data as $uraian => $years) {
            $data_2_years_ago = isset($years[$start_year]->persen_tagih)
                ? round($years[$start_year]->persen_tagih, 8)
                : 0;

            $data_last_year = isset($years[$start_year + 1]->persen_tagih)
                ? round($years[$start_year + 1]->persen_tagih, 8)
                : 0;

            $data_this_year = isset($years[$end_year]->persen_tagih)
                ? round($years[$end_year]->persen_tagih, 8)
                : 0;
            $saldo_this_year = $years[$end_year]->saldo_akhir ?? 0;

            $average_persen = ($data_2_years_ago + $data_last_year + $data_this_year) / 3;
            $average_decimal = round($average_persen / 100, 5);
            $adjusted_piutang = $average_decimal * $saldo_this_year;

            $final_data[] = [
                'uraian' => $uraian,
                '2_years_ago' => $data_2_years_ago,
                'last_year' => $data_last_year,
                'this_year' => $data_this_year,
                'average' => $average_persen,
                'saldo_this_year' => $saldo_this_year,
                'adjusted_piutang' => $adjusted_piutang,
            ];
            // Update totals
            $totals['2_years_ago'] += $data_2_years_ago;
            $totals['last_year'] += $data_last_year;
            $totals['this_year'] += $data_this_year;
            $totals['average'] += $average_persen;
            $totals['saldo_this_year'] += $saldo_this_year;
            $totals['adjusted_piutang'] += $adjusted_piutang;
        }

        $data['hitung_piutang'] = $final_data;
        $data['totals'] = $totals;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/view_hitung_piutang', $data);
        $this->load->view('templates/footer');
    }

    public function input_piutang_usaha($tahun, $total_piu_usaha_tahun_ini)
    {
        if ($total_piu_usaha_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/peny_piutang/hitung_piutang');
            return;
        }
        // Cek apakah data sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Aset Lancar');
        $this->db->where('akun', 'Piutang Usaha');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            // Jika data sudah ada, tampilkan pesan peringatan
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data sudah ada! Tidak dapat menambahkan data yang sama.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        } else {
            // Jika belum ada, lakukan insert
            $data = [
                'tahun_neraca' => $tahun,
                'kategori' => 'Aset Lancar',
                'akun' => 'Piutang Usaha',
                'nilai_neraca' => $total_piu_usaha_tahun_ini,
                'posisi' => 3,
                'no_neraca' => '1.2',
                'status' => 1
            ];
            $this->db->insert('neraca', $data);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Data berhasil disimpan ke Neraca!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        }
        redirect('lap_keuangan/peny_piutang/hitung_piutang');
    }

    public function input_akm_piutang_usaha($tahun, $total_akm_piu_usaha_tahun_ini)
    {
        if ($total_akm_piu_usaha_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/peny_piutang/hitung_piutang');
            return;
        }
        // Cek apakah data sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Aset Lancar');
        $this->db->where('akun', 'Akm Kerugian Piutang Usaha');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            // Jika data sudah ada, tampilkan pesan peringatan
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data sudah ada! Tidak dapat menambahkan data yang sama.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        } else {
            // Jika belum ada, lakukan insert
            $data = [
                'tahun_neraca' => $tahun,
                'kategori' => 'Aset Lancar',
                'akun' => 'Akm Kerugian Piutang Usaha',
                'nilai_neraca' => $total_akm_piu_usaha_tahun_ini * -1,
                'posisi' => 4,
                'no_neraca' => '1.3',
                'status' => 1
            ];

            $this->db->insert('neraca', $data);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Data berhasil disimpan ke Neraca!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        }
        redirect('lap_keuangan/peny_piutang/hitung_piutang');
    }
}
