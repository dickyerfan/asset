<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penyusutan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_penyusutan');
        $this->load->library('form_validation');
    }
    public function index()
    {
        $tanggal = $this->input->get('tanggal');
        $tahun = substr($tanggal, 0, 4);

        if (empty($tanggal)) {
            $tanggal = date('Y-m-d');
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tanggal', $tanggal);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'Daftar Penyusutan Asset';
        $data['susut'] = $this->Model_penyusutan->get_all($tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('penyusutan/view_penyusutan', $data);
        $this->load->view('templates/footer');
    }

    // public function upload()
    // {
    //     $tanggal = $this->session->userdata('tanggal');
    //     $this->form_validation->set_rules('nama_asset', 'Nama Asset', 'required|trim');
    //     $this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
    //     $this->form_validation->set_rules('id_no_per', 'No Perkiraan', 'required|trim');
    //     $this->form_validation->set_rules('id_bagian', 'Bagian/UPK', 'required|trim');
    //     $this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|numeric');
    //     $this->form_validation->set_rules('rupiah', 'Rupiah', 'required|trim|numeric');
    //     $this->form_validation->set_rules('umur', 'Umur Asset', 'trim|numeric');
    //     $this->form_validation->set_rules('persen_susut', 'Persen Penyusutan', 'trim|numeric');
    //     $this->form_validation->set_message('required', '%s masih kosong');
    //     $this->form_validation->set_message('numeric', '%s harus berupa angka');

    //     if ($this->form_validation->run() == false) {
    //         $data['title'] = 'Upload Asset Baru';
    //         $data['bagian'] = $this->Model_asset->get_bagian();
    //         $data['perkiraan'] = $this->Model_asset->get_perkiraan();

    //         $this->load->view('templates/header', $data);
    //         $this->load->view('templates/navbar');
    //         $this->load->view('templates/sidebar');
    //         $this->load->view('asset/view_upload_asset', $data);
    //         $this->load->view('templates/footer');
    //     } else {
    //         $data['asset'] = $this->Model_asset->tambah_asset();
    //         $this->session->set_flashdata(
    //             'info',
    //             '<div class="alert alert-primary alert-dismissible fade show" role="alert">
    //                     <strong>Sukses,</strong> Data Asset baru berhasil di tambah
    //                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
    //                     </button>
    //                   </div>'
    //         );
    //         $alamat = 'asset?tanggal=' . $tanggal;
    //         redirect($alamat);
    //         // redirect('asset');
    //     }
    // }
}
