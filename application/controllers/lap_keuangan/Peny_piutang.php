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

    // public function hitung_piutang()
    // {
    //     $tanggal = $this->input->get('tahun');
    //     $tahun = substr($tanggal, 0, 4);

    //     if (empty($tanggal)) {
    //         $tanggal = date('Y-m-d');
    //         $bulan = date('m');
    //         $tahun = date('Y');
    //     } else {
    //         $this->session->set_userdata('tahun', $tanggal);
    //     }

    //     $data['tahun_lap'] = $tahun;
    //     $data['title'] = 'Perhitungan Penyisihan Piutang Air';
    //     $data['piutang'] = $this->Model_lap_keuangan->get_hitung_piutang($tahun);

    //     $this->load->view('templates/header', $data);
    //     $this->load->view('templates/navbar');
    //     $this->load->view('templates/sidebar');
    //     $this->load->view('lap_keuangan/view_hitung_piutang', $data);
    //     $this->load->view('templates/footer');
    // }

    public function hitung_piutang()
    {
        $tanggal = $this->input->get('tahun');
        $tahun = (int) substr($tanggal, 0, 4);

        if (empty($tanggal)) {
            $tanggal = date('Y-m-d');
            $bulan = date('m');
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun', $tanggal);
        }

        // Inisialisasi data tahun yang digunakan
        $tahun_2022 = $tahun - 2;
        $tahun_2023 = $tahun - 1;
        $tahun_2024 = $tahun;

        // Ambil data persen_tagih dan saldo_akhir
        $piutang_data = $this->Model_lap_keuangan->get_hitung_piutang([$tahun_2022, $tahun_2023, $tahun_2024]);

        // Proses data
        $data_table = [];
        $rata_rata = 0;
        $saldo_akhir_2024 = 0;

        foreach ($piutang_data as $row) {
            $tahun_row = (int) $row->tahun;
            if ($tahun_row === $tahun_2022) {
                $data_table['persen_tagih_2022'] = $row->persen_tagih;
            } elseif ($tahun_row === $tahun_2023) {
                $data_table['persen_tagih_2023'] = $row->persen_tagih;
            } elseif ($tahun_row === $tahun_2024) {
                $data_table['persen_tagih_2024'] = $row->persen_tagih;
                $saldo_akhir_2024 = $row->saldo_akhir;
            }
        }

        // Hitung rata-rata dan Peny. Piutang Air
        $data_table['rata_rata'] = round((
            ($data_table['persen_tagih_2022'] ?? 0) +
            ($data_table['persen_tagih_2023'] ?? 0) +
            ($data_table['persen_tagih_2024'] ?? 0)) / 3, 2);

        $data_table['saldo_akhir'] = $saldo_akhir_2024;
        $data_table['peny_piutang_air'] = round($data_table['rata_rata'] * $saldo_akhir_2024 / 100, 2);

        // Kirim data ke view
        $data['tahun_lap'] = $tahun;
        $data['title'] = 'Perhitungan Penyisihan Piutang Air';
        $data['piutang'] = $data_table;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/view_hitung_piutang', $data);
        $this->load->view('templates/footer');
    }
}
