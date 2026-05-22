<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Asset_tetap_baru extends CI_Controller
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

        $data['title'] = 'Asset Tetap Baru';
        $data['title2'] = 'Perhitungan Asset Tetap Dalam Penyelesaian';
        $data['title3'] = 'Daftar Asset Tidak Berwujud';
        $data['tahun_lap'] = $tahun;
        $data['tahun_lalu'] = $tahun - 1;
        $data['asset_tetap'] = $this->prepare_rows($this->Model_lap_keuangan->get_asset_tetap_baru($tahun));
        $data['totals'] = $this->calculate_totals($data['asset_tetap']);
        $data['atdp_input'] = $this->Model_lap_keuangan->get_atdp_input($tahun);
        $data['atb_input'] = $this->Model_lap_keuangan->get_atb_input($tahun);
        $data['total_atb'] = $this->calculate_atb_totals($data['atb_input']);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/view_asset_tetap_baru', $data);
        $this->load->view('templates/footer');
    }

    public function import()
    {
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim|numeric');
        $this->form_validation->set_rules('data_excel', 'Data Excel', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Import Asset Tetap Baru';
            $data['tahun_lap'] = date('Y');

            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_import_asset_tetap_baru', $data);
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

        redirect('lap_keuangan/asset_tetap_baru?tahun=' . $tahun);
    }

    public function import_atdp()
    {
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim|numeric');
        $this->form_validation->set_rules('data_excel', 'Data Excel', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Import Asset Tetap Dalam Penyelesaian';
            $data['tahun_lap'] = date('Y');

            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_import_atdp_baru', $data);
            $this->load->view('templates/footer');
            return;
        }

        $tahun = (int) $this->input->post('tahun', true);
        $data_excel = $this->input->post('data_excel', false);
        $result = $this->import_atdp_paste_data($tahun, $data_excel);

        $this->set_import_flashdata($result);
        redirect('lap_keuangan/asset_tetap_baru?tahun=' . $tahun);
    }

    public function import_atb()
    {
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim|numeric');
        $this->form_validation->set_rules('data_excel', 'Data Excel', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Import Asset Tidak Berwujud';
            $data['tahun_lap'] = date('Y');

            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_import_atb_baru', $data);
            $this->load->view('templates/footer');
            return;
        }

        $tahun = (int) $this->input->post('tahun', true);
        $data_excel = $this->input->post('data_excel', false);
        $result = $this->import_atb_paste_data($tahun, $data_excel);

        $this->set_import_flashdata($result);
        redirect('lap_keuangan/asset_tetap_baru?tahun=' . $tahun);
    }

    public function input_apt()
    {
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim|numeric');
        $this->form_validation->set_rules('nilai_neraca', 'Nilai Asset Pajak Tangguhan', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Asset Pajak Tangguhan';

            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_apt_baru', $data);
            $this->load->view('templates/footer');
            return;
        }

        $tahun = $this->input->post('tahun', true);
        $insert = $this->Model_lap_keuangan->input_apt();

        if (!$insert) {
            $this->set_neraca_flashdata('danger', 'Gagal,', 'Data untuk tahun tersebut sudah ada di Neraca.');
        } else {
            $this->set_neraca_flashdata('primary', 'Sukses,', 'Data asset pajak tangguhan berhasil disimpan ke Neraca.');
        }

        redirect('lap_keuangan/asset_tetap_baru?tahun=' . $tahun);
    }

    public function input_aset_tetap($tahun, $total_aset_tetap)
    {
        date_default_timezone_set('Asia/Jakarta');
        $total_aset_tetap = (float) $total_aset_tetap;

        if ($total_aset_tetap == 0) {
            $this->set_neraca_flashdata('danger', 'Gagal,', 'Data Belum ada! Tidak dapat menambahkan data.');
            redirect('lap_keuangan/asset_tetap_baru?tahun=' . $tahun);
            return;
        }

        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Aset Tidak Lancar');
        $this->db->where('akun', 'Aset Tetap');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            $this->set_neraca_flashdata('danger', 'Gagal,', 'Data sudah ada! Tidak dapat menambahkan data yang sama.');
        } else {
            $data = [
                'tahun_neraca' => $tahun,
                'kategori' => 'Aset Tidak Lancar',
                'akun' => 'Aset Tetap',
                'nilai_neraca' => $total_aset_tetap,
                'nilai_neraca_audited' => $total_aset_tetap,
                'posisi' => 10,
                'no_neraca' => '2.1',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama_lengkap')
            ];

            $this->db->insert('neraca', $data);
            $this->set_neraca_flashdata('primary', 'Sukses,', 'Data berhasil disimpan ke Neraca!');
        }

        redirect('lap_keuangan/asset_tetap_baru?tahun=' . $tahun);
    }

    public function input_akm_aset_tetap($tahun, $total_akm_penyusutan)
    {
        date_default_timezone_set('Asia/Jakarta');
        $total_akm_penyusutan = (float) $total_akm_penyusutan;

        if ($total_akm_penyusutan == 0) {
            $this->set_neraca_flashdata('danger', 'Gagal,', 'Data Belum ada! Tidak dapat menambahkan data.');
            redirect('lap_keuangan/asset_tetap_baru?tahun=' . $tahun);
            return;
        }

        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Aset Tidak Lancar');
        $this->db->where('akun', 'Akm Depresiasi Aset Tetap');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            $this->set_neraca_flashdata('danger', 'Gagal,', 'Data sudah ada! Tidak dapat menambahkan data yang sama.');
        } else {
            $nilai_neraca = $total_akm_penyusutan * -1;
            $data = [
                'tahun_neraca' => $tahun,
                'kategori' => 'Aset Tidak Lancar',
                'akun' => 'Akm Depresiasi Aset Tetap',
                'nilai_neraca' => $nilai_neraca,
                'nilai_neraca_audited' => $nilai_neraca,
                'posisi' => 11,
                'no_neraca' => '2.2',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama_lengkap')
            ];

            $this->db->insert('neraca', $data);
            $this->set_neraca_flashdata('primary', 'Sukses,', 'Data berhasil disimpan ke Neraca!');
        }

        redirect('lap_keuangan/asset_tetap_baru?tahun=' . $tahun);
    }

    public function input_atdp_neraca($tahun, $total_atdp)
    {
        date_default_timezone_set('Asia/Jakarta');
        $total_atdp = (float) $total_atdp;

        if ($total_atdp == 0) {
            $this->set_neraca_flashdata('danger', 'Gagal,', 'Data Belum ada! Tidak dapat menambahkan data.');
            redirect('lap_keuangan/asset_tetap_baru?tahun=' . $tahun);
            return;
        }

        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Aset Tidak Lancar');
        $this->db->where('akun', 'Aset Tetap Dalam Penyelesaian');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            $this->set_neraca_flashdata('danger', 'Gagal,', 'Data sudah ada! Tidak dapat menambahkan data yang sama.');
        } else {
            $data = [
                'tahun_neraca' => $tahun,
                'kategori' => 'Aset Tidak Lancar',
                'akun' => 'Aset Tetap Dalam Penyelesaian',
                'nilai_neraca' => $total_atdp,
                'nilai_neraca_audited' => $total_atdp,
                'posisi' => 13,
                'no_neraca' => '2.4',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama_lengkap')
            ];

            $this->db->insert('neraca', $data);
            $this->set_neraca_flashdata('primary', 'Sukses,', 'Data berhasil disimpan ke Neraca!');
        }

        redirect('lap_keuangan/asset_tetap_baru?tahun=' . $tahun);
    }

    public function input_atb($tahun, $total_atb)
    {
        date_default_timezone_set('Asia/Jakarta');
        $total_atb = (float) $total_atb;

        if ($total_atb == 0) {
            $this->set_neraca_flashdata('danger', 'Gagal,', 'Data Belum ada! Tidak dapat menambahkan data.');
            redirect('lap_keuangan/asset_tetap_baru?tahun=' . $tahun);
            return;
        }

        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Aset Tidak Lancar');
        $this->db->where('akun', 'Aset Tidak Berwujud');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
            $this->set_neraca_flashdata('danger', 'Gagal,', 'Data sudah ada! Tidak dapat menambahkan data yang sama.');
        } else {
            $nilai_neraca = $total_atb * 1;
            $data = [
                'tahun_neraca' => $tahun,
                'kategori' => 'Aset Tidak Lancar',
                'akun' => 'Aset Tidak Berwujud',
                'nilai_neraca' => $nilai_neraca,
                'nilai_neraca_audited' => $nilai_neraca,
                'posisi' => 14,
                'no_neraca' => '2.5',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama_lengkap')
            ];

            $this->db->insert('neraca', $data);
            $this->set_neraca_flashdata('primary', 'Sukses,', 'Data berhasil disimpan ke Neraca!');
        }

        redirect('lap_keuangan/asset_tetap_baru?tahun=' . $tahun);
    }

    private function import_paste_data($tahun, $data_excel)
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($data_excel));
        $success = 0;
        $errors = [];
        $no_urut = 1;

        foreach ($lines as $index => $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $columns = preg_split('/\t+/', $line);
            if (count($columns) < 3) {
                $errors[] = 'Baris ' . ($index + 1) . ' dilewati: jumlah kolom kurang dari 3.';
                continue;
            }

            $nama_asset = trim($columns[0]);
            if ($this->normalize_key($nama_asset) === 'total' || $this->normalize_key($nama_asset) === 'jumlah') {
                continue;
            }

            $values = [
                'nama_asset' => $nama_asset,
                'harga_perolehan' => $this->clean_number($columns[1]),
                'akm_penyusutan' => $this->clean_number($columns[2]),
            ];

            $this->Model_lap_keuangan->save_asset_tetap_baru_import_row($tahun, $no_urut, $values);
            $success++;
            $no_urut++;
        }

        return [
            'success' => $success,
            'errors' => $errors,
        ];
    }

    private function import_atdp_paste_data($tahun, $data_excel)
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($data_excel));
        $success = 0;
        $errors = [];

        foreach ($lines as $index => $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $columns = preg_split('/\t+/', $line);
            if (count($columns) < 2) {
                $errors[] = 'Baris ' . ($index + 1) . ' dilewati: jumlah kolom kurang dari 2.';
                continue;
            }

            $nama_atdp = trim($columns[0]);
            if ($this->normalize_key($nama_atdp) === 'total' || $this->normalize_key($nama_atdp) === 'jumlah') {
                continue;
            }

            $this->Model_lap_keuangan->save_atdp_import_row($tahun, [
                'nama_atdp' => $nama_atdp,
                'jumlah_atdp' => $this->clean_number($columns[1]),
            ]);
            $success++;
        }

        return [
            'success' => $success,
            'errors' => $errors,
        ];
    }

    private function import_atb_paste_data($tahun, $data_excel)
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($data_excel));
        $success = 0;
        $errors = [];
        $no_urut = 1;

        foreach ($lines as $index => $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $columns = preg_split('/\t+/', $line);
            if (count($columns) < 5) {
                $errors[] = 'Baris ' . ($index + 1) . ' dilewati: jumlah kolom kurang dari 5.';
                continue;
            }

            $nama_atb = trim($columns[0]);
            if ($this->normalize_key($nama_atb) === 'total' || $this->normalize_key($nama_atb) === 'jumlah') {
                continue;
            }

            $tanggal_perolehan = $this->clean_date($columns[1]);
            if ($tanggal_perolehan === null) {
                $errors[] = 'Baris ' . ($index + 1) . ' dilewati: format tanggal tidak valid.';
                continue;
            }

            $this->Model_lap_keuangan->save_atb_import_row($tahun, $no_urut, [
                'nama_atb' => $nama_atb,
                'tanggal_perolehan' => $tanggal_perolehan,
                'harga_perolehan' => $this->clean_number($columns[2]),
                'akm_amortisasi' => $this->clean_number($columns[3]),
                'nilai_buku' => $this->clean_number($columns[4]),
            ]);
            $success++;
            $no_urut++;
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

    private function clean_date($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        if (preg_match('/^(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})$/', $value, $matches)) {
            return $matches[3] . '-' . str_pad($matches[2], 2, '0', STR_PAD_LEFT) . '-' . str_pad($matches[1], 2, '0', STR_PAD_LEFT);
        }

        if (preg_match('/^\d{4}$/', $value)) {
            return $value . '-01-01';
        }

        $timestamp = strtotime($value);
        return $timestamp ? date('Y-m-d', $timestamp) : null;
    }

    private function normalize_key($value)
    {
        $value = strtolower(trim((string) $value));
        $value = preg_replace('/[^a-z0-9]+/', '', $value);
        return $value;
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

    private function set_import_flashdata($result)
    {
        if (!empty($result['errors'])) {
            $message = implode('<br>', $result['errors']);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Perhatian,</strong><br>' . $message . '
                  </div>'
            );
            return;
        }

        $this->session->set_flashdata(
            'info',
            '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses,</strong> ' . $result['success'] . ' baris data berhasil diimport.
              </div>'
        );
    }

    private function prepare_rows($rows)
    {
        foreach ($rows as $row) {
            $row->nilai_buku = (int) $row->harga_perolehan - (int) $row->akm_penyusutan;
        }

        return $rows;
    }

    private function calculate_totals($rows)
    {
        $totals = [
            'harga_perolehan' => 0,
            'akm_penyusutan' => 0,
            'nilai_buku' => 0,
        ];

        foreach ($rows as $row) {
            $totals['harga_perolehan'] += (float) $row->harga_perolehan;
            $totals['akm_penyusutan'] += (float) $row->akm_penyusutan;
            $totals['nilai_buku'] += (float) $row->nilai_buku;
        }

        return $totals;
    }

    private function calculate_atb_totals($rows)
    {
        $totals = [
            'harga_perolehan' => 0,
            'akm_amortisasi' => 0,
            'nilai_buku' => 0,
        ];

        foreach ($rows as $row) {
            $totals['harga_perolehan'] += (float) $row->harga_perolehan;
            $totals['akm_amortisasi'] += (float) $row->akm_amortisasi;
            $totals['nilai_buku'] += (float) $row->nilai_buku;
        }

        return $totals;
    }
}
