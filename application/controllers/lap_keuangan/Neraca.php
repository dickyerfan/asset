<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Neraca extends CI_Controller
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

        $data['title'] = 'LAPORAN POSISI KEUANGAN';
        $data['neraca'] = $this->Model_lap_keuangan->get_all_neraca($tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/view_neraca', $data);
        $this->load->view('templates/footer');
    }

    public function neraca_cetak()
    {
        $tahun = $this->session->userdata('tahun');
        $tahun = substr($tahun, 0, 4);

        if (empty($tahun)) {
            $tahun = date('Y-m-d');
            $bulan = date('m');
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun', $tahun);
        }

        $data['tahun_lap'] = $tahun;
        $data['tahun_lalu'] = $tahun - 1;

        $data['title'] = 'LAPORAN POSISI KEUANGAN';
        $data['neraca'] = $this->Model_lap_keuangan->get_all_neraca($tahun);

        $this->pdf->setPaper('folio', 'portrait');
        $this->pdf->filename = "neraca-{$tahun}.pdf";
        $this->pdf->generate('cetakan_lap_keuangan/neraca_pdf', $data);
    }

    // public function tambah()
    // {
    //     $tanggal = $this->session->userdata('tanggal');
    //     $this->form_validation->set_rules('id_kel_tarif', 'Kelompok Tarif', 'required|trim');
    //     $this->form_validation->set_rules('tgl_piutang', 'Tanggal', 'required|trim');
    //     $this->form_validation->set_rules('saldo_awal', 'Saldo Awal', 'required|trim|numeric');
    //     $this->form_validation->set_rules('tambah', 'Tambah', 'required|trim|numeric');
    //     $this->form_validation->set_rules('kurang', 'Kurang', 'required|trim|numeric');
    //     $this->form_validation->set_message('required', '%s masih kosong');
    //     $this->form_validation->set_message('numeric', '%s harus berupa angka');

    //     if ($this->form_validation->run() == false) {
    //         $data['title'] = 'Upload Penyisihan Piutang';
    //         $data['kel_tarif'] = $this->Model_lap_keuangan->get_kel_tarif();
    //         $this->load->view('templates/header', $data);
    //         $this->load->view('templates/navbar');
    //         $this->load->view('templates/sidebar');
    //         $this->load->view('lap_keuangan/view_upload_peny_piutang', $data);
    //         $this->load->view('templates/footer');
    //     } else {
    //         $data['piutang'] = $this->Model_lap_keuangan->tambah();
    //         $this->session->set_flashdata(
    //             'info',
    //             '<div class="alert alert-primary alert-dismissible fade show" role="alert">
    //                     <strong>Sukses,</strong> Data Penyisihan Piutang baru berhasil di tambah
    //                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
    //                     </button>
    //                   </div>'
    //         );
    //         redirect('lap_keuangan/peny_piutang');
    //     }
    // }


}
