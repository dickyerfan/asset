<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Asset_kurang extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_asset');
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

        $level_pengguna = $this->session->userdata('level');
        if ($level_pengguna != 'Admin') {
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
        $tanggal = $this->input->get('tanggal');

        $bulan = substr($tanggal, 5, 2);
        $tahun = substr($tanggal, 0, 4);

        if (empty($tanggal)) {
            $tanggal = date('Y-m-d');
            $bulan = date('m');
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tanggal', $tanggal);
        }
        $data['bulan_lap'] = $bulan;
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'Pengurangan Asset Tahun';
        $data['asset'] = $this->Model_asset->get_kurang($bulan, $tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_kurang/view_asset_kurang', $data);
        $this->load->view('templates/footer');
    }

    // public function all_asset()
    // {

    //     $data['title'] = 'Daftar Semua Asset';
    //     $data['asset'] = $this->Model_asset->get_all_kurang();

    //     $this->load->view('templates/header', $data);
    //     $this->load->view('templates/navbar');
    //     $this->load->view('templates/sidebar');
    //     $this->load->view('asset_kurang/view_asset_all_kurang', $data);
    //     $this->load->view('templates/footer');
    // }

    // public function upload()
    // {
    //     $tanggal = $this->session->userdata('tanggal');
    //     $this->form_validation->set_rules('nama_asset', 'Nama Asset', 'required|trim');
    //     $this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
    //     $this->form_validation->set_rules('id_no_per', 'No Perkiraan', 'required|trim');
    //     $this->form_validation->set_rules('id_bagian', 'Bagian/UPK', 'required|trim');
    //     // $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|trim|numeric');
    //     $this->form_validation->set_rules('rupiah', 'Rupiah', 'required|trim|numeric');
    //     $this->form_validation->set_message('required', '%s masih kosong');
    //     $this->form_validation->set_message('numeric', '%s harus berupa angka');

    //     if ($this->form_validation->run() == false) {
    //         $data['title'] = 'Upload Asset Baru';
    //         $data['bagian'] = $this->Model_asset->get_bagian();
    //         $data['perkiraan'] = $this->Model_asset->get_perkiraan();

    //         $this->load->view('templates/header', $data);
    //         $this->load->view('templates/navbar');
    //         $this->load->view('templates/sidebar');
    //         $this->load->view('asset/view_upload_asset_kurang', $data);
    //         $this->load->view('templates/footer');
    //     } else {
    //         $data['asset'] = $this->Model_asset->kurang_asset();
    //         $this->session->set_flashdata(
    //             'info',
    //             '<div class="alert alert-primary alert-dismissible fade show" role="alert">
    //                     <strong>Sukses,</strong> Data Asset baru berhasil di kurangi
    //                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
    //                     </button>
    //                   </div>'
    //         );
    //         $alamat = 'asset_kurang?tanggal=' . $tanggal;
    //         redirect($alamat);
    //         // redirect('asset');
    //     }
    // }
}
