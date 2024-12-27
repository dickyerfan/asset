<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Asset_rekap extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_asset_rekap');
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
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'REKAP PENAMBAHAN TANAH DAN PENYEMPURNAAN TANAH';
        $grand_id_tanah = 218;
        $data['tanah'] = $this->Model_asset_rekap->get_asset_by_grand_id($tahun, $grand_id_tanah);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_tanah', $data);
        $this->load->view('templates/footer');
    }

    public function sumber()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'REKAP PENAMBAHAN INSTALASI SUMBER';
        $grand_id_sumber = 220; // grand_id untuk sumber
        $data['sumber'] = $this->Model_asset_rekap->get_asset_by_grand_id($tahun, $grand_id_sumber);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_sumber', $data);
        $this->load->view('templates/footer');
    }

    public function sumber_kurang()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_kurang', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'REKAP PENGURANGAN INSTALASI SUMBER';
        $grand_id_sumber = 220; // grand_id untuk sumber
        $data['sumber'] = $this->Model_asset_rekap->get_asset_by_grand_id_kurang($tahun, $grand_id_sumber);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_sumber_kurang', $data);
        $this->load->view('templates/footer');
    }

    public function pompa()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'REKAP PENAMBAHAN INSTALASI POMPA';
        $grand_id_pompa = 222; // grand_id untuk pompa
        $data['pompa'] = $this->Model_asset_rekap->get_asset_by_grand_id($tahun, $grand_id_pompa);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_pompa', $data);
        $this->load->view('templates/footer');
    }

    public function pompa_kurang()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_kurang', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'REKAP PENGURANGAN INSTALASI POMPA';
        $grand_id_pompa = 222; // grand_id untuk pompa
        $data['pompa'] = $this->Model_asset_rekap->get_asset_by_grand_id_kurang($tahun, $grand_id_pompa);
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_pompa_kurang', $data);
        $this->load->view('templates/footer');
    }

    public function olah_air()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'REKAP PENAMBAHAN INSTALASI PENGOLAHAN AIR';
        $grand_id_olah_air = 224; // grand_id untuk olah_air
        $data['olah_air'] = $this->Model_asset_rekap->get_asset_by_grand_id($tahun, $grand_id_olah_air);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_olah_air', $data);
        $this->load->view('templates/footer');
    }

    public function olah_air_kurang()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'REKAP PENGURANGAN INSTALASI PENGOLAHAN AIR';
        $grand_id_olah_air = 224; // grand_id untuk olah_air
        $data['olah_air'] = $this->Model_asset_rekap->get_asset_by_grand_id_kurang($tahun, $grand_id_olah_air);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_olah_air_kurang', $data);
        $this->load->view('templates/footer');
    }

    public function bangunan()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'REKAP PENAMBAHAN BANGUNAN';
        $grand_id_bangunan = 228; // grand_id untuk bangunan
        $data['bangunan'] = $this->Model_asset_rekap->get_asset_by_grand_id($tahun, $grand_id_bangunan);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_bangunan', $data);
        $this->load->view('templates/footer');
    }

    public function bangunan_kurang()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'REKAP PENGURANGAN BANGUNAN';
        $grand_id_bangunan = 228; // grand_id untuk bangunan
        $data['bangunan'] = $this->Model_asset_rekap->get_asset_by_grand_id_kurang($tahun, $grand_id_bangunan);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_bangunan_kurang', $data);
        $this->load->view('templates/footer');
    }

    public function peralatan()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'REKAP PENAMBAHAN PERALATAN DAN PERLENGKAPAN';
        $grand_id_peralatan = 244; // grand_id untuk peralatan
        $data['peralatan'] = $this->Model_asset_rekap->get_asset_by_grand_id($tahun, $grand_id_peralatan);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_peralatan', $data);
        $this->load->view('templates/footer');
    }

    public function peralatan_kurang()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'REKAP PENGURANGAN PERALATAN DAN PERLENGKAPAN';
        $grand_id_peralatan = 244; // grand_id untuk peralatan
        $data['peralatan'] = $this->Model_asset_rekap->get_asset_by_grand_id_kurang($tahun, $grand_id_peralatan);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_peralatan_kurang', $data);
        $this->load->view('templates/footer');
    }

    public function kendaraan()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'REKAP PENAMBAHAN KENDARAAN / ALAT ANGKUT';
        $grand_id_kendaraan = 246; // grand_id untuk kendaraan
        $data['kendaraan'] = $this->Model_asset_rekap->get_asset_by_grand_id($tahun, $grand_id_kendaraan);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_kendaraan', $data);
        $this->load->view('templates/footer');
    }

    public function kendaraan_kurang()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'REKAP PENGURANGAN KENDARAAN / ALAT ANGKUT';
        $grand_id_kendaraan = 246; // grand_id untuk kendaraan
        $data['kendaraan'] = $this->Model_asset_rekap->get_asset_by_grand_id_kurang($tahun, $grand_id_kendaraan);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_kendaraan_kurang', $data);
        $this->load->view('templates/footer');
    }

    public function inventaris()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'REKAP PENAMBAHAN INVENTARIS / PERABOTAN KANTOR';
        $grand_id_inventaris = 248; // grand_id untuk inventaris
        $data['inventaris'] = $this->Model_asset_rekap->get_asset_by_grand_id($tahun, $grand_id_inventaris);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_inventaris', $data);
        $this->load->view('templates/footer');
    }

    public function inventaris_kurang()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_kurang', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'REKAP PENGURANGAN INVENTARIS / PERABOTAN KANTOR';
        $grand_id_inventaris = 248; // grand_id untuk inventaris
        $data['inventaris'] = $this->Model_asset_rekap->get_asset_by_grand_id_kurang($tahun, $grand_id_inventaris);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_inventaris_kurang', $data);
        $this->load->view('templates/footer');
    }

    public function penyusutan()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
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

    public function trans_dist()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'REKAP PENAMBAHAN INSTALASI TRANSMISI DAN DISTRIBUSI';
        $grand_id_trans_dist = 226; // grand_id untuk trans_dist
        $data['trans_dist'] = $this->Model_asset_rekap->get_asset_by_grand_id($tahun, $grand_id_trans_dist);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_trans_dist', $data);
        $this->load->view('templates/footer');
    }

    public function trans_dist_kurang()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'REKAP PENGURANGAN INSTALASI TRANSMISI DAN DISTRIBUSI';
        $grand_id_trans_dist = 226; // grand_id untuk trans_dist
        $data['trans_dist'] = $this->Model_asset_rekap->get_asset_by_grand_id_kurang($tahun, $grand_id_trans_dist);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_trans_dist_kurang', $data);
        $this->load->view('templates/footer');
    }

    public function sr_baru()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_sr_baru', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'PEMASANGAN SR BARU';
        $data['sr_baru'] = $this->Model_asset_rekap->get_sr_baru($tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_trans_dist_sr_baru', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_sr_baru()
    {

        $get_tahun = $this->session->userdata('tahun_session_sr_baru');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'PEMASANGAN SR BARU';
        $data['sr_baru'] = $this->Model_asset_rekap->get_sr_baru($tahun);

        // Set paper size and orientation
        $this->pdf->setPaper('folio', 'portrait');

        $this->pdf->filename = "sr_baru-{$tahun}.pdf";
        $this->pdf->generate('cetakan_asset/sr_baru_pdf', $data);
    }
    public function sr_baru_rekap()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_sr_baru_rekap', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'REKAP SR BARU PER UPK';
        $data['sr_baru'] = $this->Model_asset_rekap->get_sr_baru_rekap($tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_trans_dist_sr_baru_rekap', $data);
        $this->load->view('templates/footer');
    }
    public function ganti_wm()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_ganti_wm', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'PENGGANTIAN WATER METER';
        $data['ganti_wm'] = $this->Model_asset_rekap->get_ganti_wm($tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_trans_dist_ganti_wm', $data);
        $this->load->view('templates/footer');
    }
    public function ganti_wm_rekap()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_ganti_wm_rekap', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'REKAP PENGGANTIAN WATER METER PER UPK';
        $data['ganti_wm'] = $this->Model_asset_rekap->get_ganti_wm_rekap($tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_trans_dist_ganti_wm_rekap', $data);
        $this->load->view('templates/footer');
    }
    public function lainnya()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_lainnya', $get_tahun);
        }
        $data['tahun_lap'] = $tahun;

        $data['title'] = 'INSTALASI TRANSMISI & DISTRIBUSI LAINNYA';
        $data['lainnya'] = $this->Model_asset_rekap->get_lainnya($tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('asset_rekap/view_asset_trans_dist_lainnya', $data);
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
