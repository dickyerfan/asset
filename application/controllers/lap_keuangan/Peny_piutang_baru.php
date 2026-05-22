<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Peny_piutang_baru extends CI_Controller
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
        $tahun = $this->input->get('tahun');
        $tahun = empty($tahun) ? date('Y') : (int) substr($tahun, 0, 4);

        $data['title'] = 'Perhitungan Penyisihan Piutang Air';
        $data['tahun_lap'] = $tahun;
        $data['piutang'] = $this->prepare_rows($this->Model_lap_keuangan->get_peny_piutang_baru($tahun));
        $data['totals'] = $this->calculate_totals($data['piutang']);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/view_peny_piutang_baru', $data);
        $this->load->view('templates/footer');
    }

    public function import()
    {
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim|numeric');
        $this->form_validation->set_rules('data_excel', 'Data Excel', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Import Penyisihan Piutang Baru';
            $data['tahun_lap'] = date('Y');

            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_import_peny_piutang_baru', $data);
            $this->load->view('templates/footer');
            return;
        }

        $tahun = (int) $this->input->post('tahun', true);
        $data_excel = $this->input->post('data_excel', false);
        $result = $this->import_paste_data($tahun, $data_excel);

        if (!empty($result['errors'])) {
            $message = implode('<br>', $result['errors']);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Perhatian,</strong><br>' . $message . '
                  </div>'
            );
        } else {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>Sukses,</strong> ' . $result['success'] . ' baris data berhasil diimport.
                  </div>'
            );
        }

        redirect('lap_keuangan/peny_piutang_baru?tahun=' . $tahun);
    }

    public function input_piutang_usaha($tahun, $total_piutang_air)
    {
        date_default_timezone_set('Asia/Jakarta');
        $total_piutang_air = (float) $total_piutang_air;

        if ($total_piutang_air == 0) {
            $this->set_neraca_flashdata('danger', 'Gagal,', 'Data Belum ada! Tidak dapat menambahkan data.');
            redirect('lap_keuangan/peny_piutang_baru?tahun=' . $tahun);
            return;
        }

        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Aset Lancar');
        $this->db->where('akun', 'Piutang Usaha');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            $this->set_neraca_flashdata('danger', 'Gagal,', 'Data sudah ada! Tidak dapat menambahkan data yang sama.');
        } else {
            $data = [
                'tahun_neraca' => $tahun,
                'kategori' => 'Aset Lancar',
                'akun' => 'Piutang Usaha',
                'nilai_neraca' => $total_piutang_air,
                'nilai_neraca_audited' => $total_piutang_air,
                'posisi' => 3,
                'no_neraca' => '1.2',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama_lengkap')
            ];

            $this->db->insert('neraca', $data);
            $this->set_neraca_flashdata('primary', 'Sukses,', 'Data berhasil disimpan ke Neraca!');
        }

        redirect('lap_keuangan/peny_piutang_baru?tahun=' . $tahun);
    }

    public function input_akm_piutang_usaha($tahun, $total_penyisihan)
    {
        date_default_timezone_set('Asia/Jakarta');
        $total_penyisihan = (float) $total_penyisihan;

        if ($total_penyisihan == 0) {
            $this->set_neraca_flashdata('danger', 'Gagal,', 'Data Belum ada! Tidak dapat menambahkan data.');
            redirect('lap_keuangan/peny_piutang_baru?tahun=' . $tahun);
            return;
        }

        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Aset Lancar');
        $this->db->where('akun', 'Akm Kerugian Piutang Usaha');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            $this->set_neraca_flashdata('danger', 'Gagal,', 'Data sudah ada! Tidak dapat menambahkan data yang sama.');
        } else {
            $nilai_neraca = $total_penyisihan * -1;
            $data = [
                'tahun_neraca' => $tahun,
                'kategori' => 'Aset Lancar',
                'akun' => 'Akm Kerugian Piutang Usaha',
                'nilai_neraca' => $nilai_neraca,
                'nilai_neraca_audited' => $nilai_neraca,
                'posisi' => 4,
                'no_neraca' => '1.3',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama_lengkap')
            ];

            $this->db->insert('neraca', $data);
            $this->set_neraca_flashdata('primary', 'Sukses,', 'Data berhasil disimpan ke Neraca!');
        }

        redirect('lap_keuangan/peny_piutang_baru?tahun=' . $tahun);
    }

    public function input_peny_piutang_lain()
    {
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim|numeric');
        $this->form_validation->set_rules('nilai_neraca', 'Nilai Penyisihan Piutang Lain-lain', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Penyisihan Piutang Lain-lain';

            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_peny_piutang_lain', $data);
            $this->load->view('templates/footer');
            return;
        }

        date_default_timezone_set('Asia/Jakarta');
        $tahun = $this->input->post('tahun', true);
        $nilai_neraca = (float) $this->input->post('nilai_neraca', true);

        if ($nilai_neraca == 0) {
            $this->set_neraca_flashdata('danger', 'Gagal,', 'Nilai tidak boleh 0.');
            redirect('lap_keuangan/peny_piutang_baru/input_peny_piutang_lain');
            return;
        }

        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Aset Lancar');
        $this->db->where('akun', 'Penyisihan Piutang Lain-lain');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            $this->set_neraca_flashdata('danger', 'Gagal,', 'Data sudah ada! Tidak dapat menambahkan data yang sama.');
        } else {
            $nilai_minus = abs($nilai_neraca) * -1;
            $data = [
                'tahun_neraca' => $tahun,
                'kategori' => 'Aset Lancar',
                'akun' => 'Penyisihan Piutang Lain-lain',
                'nilai_neraca' => $nilai_minus,
                'nilai_neraca_audited' => $nilai_minus,
                'posisi' => 9,
                'no_neraca' => '1.8',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama_lengkap')
            ];

            $this->db->insert('neraca', $data);
            $this->set_neraca_flashdata('primary', 'Sukses,', 'Data berhasil disimpan ke Neraca!');
        }

        redirect('lap_keuangan/peny_piutang_baru?tahun=' . $tahun);
    }

    private function import_paste_data($tahun, $data_excel)
    {
        $kel_tarif_map = $this->Model_lap_keuangan->get_kel_tarif_map_piutang_baru();
        $lines = preg_split('/\r\n|\r|\n/', trim($data_excel));
        $success = 0;
        $errors = [];

        foreach ($lines as $index => $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $columns = preg_split('/\t+/', $line);
            if (count($columns) < 7) {
                $errors[] = 'Baris ' . ($index + 1) . ' dilewati: jumlah kolom kurang dari 7.';
                continue;
            }

            $nama_kelompok = trim($columns[0]);
            if ($this->Model_lap_keuangan->normalize_peny_piutang_baru_key($nama_kelompok) === 'jumlah') {
                continue;
            }

            $key = $this->Model_lap_keuangan->normalize_peny_piutang_baru_key($nama_kelompok);
            if (!isset($kel_tarif_map[$key])) {
                $errors[] = 'Baris ' . ($index + 1) . ' dilewati: kelompok "' . html_escape($nama_kelompok) . '" tidak ditemukan di kel_tarif.';
                continue;
            }

            $values = [
                'piutang_1_3_bulan' => $this->clean_number($columns[1]),
                'piutang_4_6_bulan' => $this->clean_number($columns[2]),
                'piutang_6_12_bulan' => $this->clean_number($columns[3]),
                'piutang_12_18_bulan' => $this->clean_number($columns[4]),
                'piutang_18_24_bulan' => $this->clean_number($columns[5]),
                'piutang_24_bulan_keatas' => $this->clean_number($columns[6]),
            ];

            $this->Model_lap_keuangan->save_peny_piutang_baru_import_row($tahun, $kel_tarif_map[$key]->id_kel_tarif, $values);
            $success++;
        }

        return [
            'success' => $success,
            'errors' => $errors,
        ];
    }

    private function clean_number($value)
    {
        $value = trim((string) $value);
        if ($value === '-' || $value === '') {
            return 0;
        }

        return (int) preg_replace('/[^0-9-]/', '', $value);
    }

    private function set_neraca_flashdata($type, $strong, $message)
    {
        $this->session->set_flashdata(
            'info',
            '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
                <strong>' . $strong . '</strong> ' . $message . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
              </div>'
        );
    }

    private function prepare_rows($rows)
    {
        $prepared = [];
        foreach ($rows as $row) {
            $prepared[] = $this->calculate_row($row);
        }

        return $prepared;
    }

    private function calculate_row($row)
    {
        $piutang_1_3 = (int) $row->piutang_1_3_bulan;
        $piutang_4_6 = (int) $row->piutang_4_6_bulan;
        $piutang_6_12 = (int) $row->piutang_6_12_bulan;
        $piutang_12_18 = (int) $row->piutang_12_18_bulan;
        $piutang_18_24 = (int) $row->piutang_18_24_bulan;
        $piutang_24_keatas = (int) $row->piutang_24_bulan_keatas;

        $row->penyisihan_4_6_bulan = $piutang_4_6 * 0.25;
        $row->penyisihan_6_12_bulan = $piutang_6_12 * 0.50;
        $row->penyisihan_12_18_bulan = $piutang_12_18 * 0.75;
        $row->penyisihan_18_24_bulan = $piutang_18_24;
        $row->penyisihan_24_bulan_keatas = $piutang_24_keatas;
        $row->jumlah_piutang = $piutang_1_3 + $piutang_4_6 + $piutang_6_12 + $piutang_12_18 + $piutang_18_24 + $piutang_24_keatas;
        $row->jumlah_penyisihan = $row->penyisihan_4_6_bulan + $row->penyisihan_6_12_bulan + $row->penyisihan_12_18_bulan + $row->penyisihan_18_24_bulan + $row->penyisihan_24_bulan_keatas;

        return $row;
    }

    private function calculate_totals($rows)
    {
        $totals = [
            'piutang_1_3_bulan' => 0,
            'piutang_4_6_bulan' => 0,
            'penyisihan_4_6_bulan' => 0,
            'piutang_6_12_bulan' => 0,
            'penyisihan_6_12_bulan' => 0,
            'piutang_12_18_bulan' => 0,
            'penyisihan_12_18_bulan' => 0,
            'piutang_18_24_bulan' => 0,
            'penyisihan_18_24_bulan' => 0,
            'piutang_24_bulan_keatas' => 0,
            'penyisihan_24_bulan_keatas' => 0,
            'jumlah_piutang' => 0,
            'jumlah_penyisihan' => 0,
        ];

        foreach ($rows as $row) {
            foreach ($totals as $key => $value) {
                $totals[$key] += (float) $row->$key;
            }
        }

        return $totals;
    }
}
