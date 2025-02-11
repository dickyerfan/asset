<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjelasan extends CI_Controller
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
        $data['tahun_lalu'] = $tahun - 1;

        $data['title'] = 'Penjelasan Neraca';
        $data['bank_input'] = $this->Model_lap_keuangan->get_bank_input($tahun);
        $data['kas_input'] = $this->Model_lap_keuangan->get_kas_input($tahun);

        // Hitung total Bank
        $total_bank_tahun_ini = 0;
        $total_bank_tahun_lalu = 0;
        foreach ($data['bank_input'] as $bank) {
            $total_bank_tahun_ini += !empty($bank->jumlah_bank_tahun_ini) ? $bank->jumlah_bank_tahun_ini : 0;
            $total_bank_tahun_lalu += !empty($bank->jumlah_bank_tahun_lalu) ? $bank->jumlah_bank_tahun_lalu : 0;
        }

        // Hitung total Kas
        $total_kas_tahun_ini = 0;
        $total_kas_tahun_lalu = 0;
        foreach ($data['kas_input'] as $kas) {
            $total_kas_tahun_ini += !empty($kas->jumlah_kas_tahun_ini) ? $kas->jumlah_kas_tahun_ini : 0;
            $total_kas_tahun_lalu += !empty($kas->jumlah_kas_tahun_lalu) ? $kas->jumlah_kas_tahun_lalu : 0;
        }

        // Total keseluruhan (Kas + Bank)
        $data['total_tahun_ini'] = $total_bank_tahun_ini + $total_kas_tahun_ini;
        $data['total_tahun_lalu'] = $total_bank_tahun_lalu + $total_kas_tahun_lalu;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/view_penjelasan', $data);
        $this->load->view('templates/footer');
    }


    public function input_bank()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('id_bank', 'Nama Bank', 'required|trim');
        $this->form_validation->set_rules('tgl_bank', 'Tanggal', 'required|trim');
        $this->form_validation->set_rules('jumlah_bank', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Bank';
            $data['bank'] = $this->Model_lap_keuangan->get_bank();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_bank', $data);
            $this->load->view('templates/footer');
        } else {
            $this->Model_lap_keuangan->input_bank();
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Data input bank baru berhasil di tambah
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/penjelasan');
        }
    }

    public function input_kas()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('id_kas', 'Nama Kas', 'required|trim');
        $this->form_validation->set_rules('tgl_kas', 'Tanggal', 'required|trim');
        $this->form_validation->set_rules('jumlah_kas', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Kas';
            $data['kas'] = $this->Model_lap_keuangan->get_kas();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_kas', $data);
            $this->load->view('templates/footer');
        } else {
            $data['kas'] = $this->Model_lap_keuangan->input_kas();
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Data input kas baru berhasil di tambah
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/penjelasan');
        }
    }
}
