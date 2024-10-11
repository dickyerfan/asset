<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Asset_rekap extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_asset_rekap');
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

        $data['title'] = 'TANAH DAN PENYEMPURNAAN TANAH';
        $data['tanah'] = $this->Model_asset_rekap->get_tanah();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_tanah', $data);
        $this->load->view('templates/footer');
    }
    public function sumber()
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

        $data['title'] = 'INSTALASI SUMBER';
        $data['sumber'] = $this->Model_asset_rekap->get_sumber();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_sumber', $data);
        $this->load->view('templates/footer');
    }
    public function pompa()
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

        $data['title'] = 'INSTALASI POMPA';
        $data['pompa'] = $this->Model_asset_rekap->get_pompa();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_pompa', $data);
        $this->load->view('templates/footer');
    }
    public function olah_air()
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

        $data['title'] = 'INSTALASI PENGOLAHAN AIR';
        $data['olah_air'] = $this->Model_asset_rekap->get_olah_air();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_olah_air', $data);
        $this->load->view('templates/footer');
    }
    public function trans_dist()
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

        $data['title'] = 'INSTALASI TRANSMISI DAN DISTRIBUSI';
        $data['trans_dist'] = $this->Model_asset_rekap->get_trans_dist();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_trans_dist', $data);
        $this->load->view('templates/footer');
    }
    public function sr_baru()
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

        $data['title'] = 'PEMASANGAN SR BARU';
        $data['sr_baru'] = $this->Model_asset_rekap->get_sr_baru();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_trans_dist_sr_baru', $data);
        $this->load->view('templates/footer');
    }
    public function ganti_wm()
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

        $data['title'] = 'PEMASANGAN SR BARU';
        $data['ganti_wm'] = $this->Model_asset_rekap->get_ganti_wm();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_trans_dist_ganti_wm', $data);
        $this->load->view('templates/footer');
    }
    public function lainnya()
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

        $data['title'] = 'PEMASANGAN SR BARU';
        $data['lainnya'] = $this->Model_asset_rekap->get_lainnya();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_trans_dist_lainnya', $data);
        $this->load->view('templates/footer');
    }
    public function bangunan()
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

        $data['title'] = 'INSTALASI TRANSMISI DAN DISTRIBUSI';
        $data['bangunan'] = $this->Model_asset_rekap->get_bangunan();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_bangunan', $data);
        $this->load->view('templates/footer');
    }
    public function peralatan()
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

        $data['title'] = 'PERALATAN DAN PERLENGKAPAN';
        $data['peralatan'] = $this->Model_asset_rekap->get_peralatan();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_peralatan', $data);
        $this->load->view('templates/footer');
    }
    public function kendaraan()
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

        $data['title'] = 'KENDARAAN / ALAT ANGKUT';
        $data['kendaraan'] = $this->Model_asset_rekap->get_kendaraan();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_kendaraan', $data);
        $this->load->view('templates/footer');
    }
    public function inventaris()
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

        $data['title'] = 'INVENTARIS / PERABOTAN KANTOR';
        $data['inventaris'] = $this->Model_asset_rekap->get_inventaris();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_inventaris', $data);
        $this->load->view('templates/footer');
    }
    public function penyusutan()
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

        $data['title'] = 'AKUMULASI PENYUSUTAN';
        $data['penyusutan'] = $this->Model_asset_rekap->get_penyusutan();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_penyusutan', $data);
        $this->load->view('templates/footer');
    }

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
