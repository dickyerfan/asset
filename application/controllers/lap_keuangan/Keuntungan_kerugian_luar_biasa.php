<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Keuntungan_kerugian_luar_biasa extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_labarugi');
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

        $data['title'] = 'Keuntungan (Kerugian) Luar Biasa';

        $data['klb_input'] = $this->Model_labarugi->get_klb_input($tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/laba_rugi/view_keuntungan_kerugian_luar_biasa', $data);
        $this->load->view('templates/footer');
    }

    public function input_klb()
    {
        $tanggal = $this->session->userdata('tanggal');
        // $this->form_validation->set_rules('nama_klb', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('jenis_klb', 'Jenis', 'required|trim');
        $this->form_validation->set_rules('tgl_klb', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_klb', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Keuntungan (Kerugian) Luar Biasa';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/laba_rugi/view_upload_klb', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_labarugi->input_klb();
            if (!$insert) {
                // Jika gagal insert karena tahun sudah ada
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Data untuk tahun tersebut sudah ada di database.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            } else {
                // Jika sukses insert
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data input Keuntungan (Kerugian) Luar Biasa berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/keuntungan_kerugian_luar_biasa');
        }
    }

    // public function input_klb_lr($tahun, $total_seluruh_klb_tahun_ini)
    // {
    //     date_default_timezone_set('Asia/Jakarta');
    //     $tahun_ini = date('Y');
    //     if ($total_seluruh_klb_tahun_ini == 0 && $tahun == $tahun_ini) {
    //         $this->session->set_flashdata(
    //             'info',
    //             '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    //                     <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
    //                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
    //                     </button>
    //                   </div>'
    //         );
    //         redirect('lap_keuangan/keuntungan_kerugian_luar_biasa');
    //         return;
    //     }

    //     // Cek apakah data sudah ada di database
    //     $this->db->where('tahun_lr_sak_ep', $tahun);
    //     $this->db->where('kategori', 'Keuntungan (Kerugian) Luar Biasa');
    //     $this->db->where('akun', 'Keuntungan (Kerugian) Luar Biasa');
    //     $query = $this->db->get('lr_sak_ep');

    //     if ($query->num_rows() > 0) {
    //         // Jika data sudah ada, tampilkan pesan peringatan
    //         $this->session->set_flashdata(
    //             'info',
    //             '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    //                     <strong>Gagal,</strong> Data sudah ada! Tidak dapat menambahkan data yang sama.
    //                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
    //                     </button>
    //                   </div>'
    //         );
    //     } else {
    //         // Jika belum ada, lakukan insert
    //         $data = [
    //             'tahun_lr_sak_ep' => $tahun,
    //             'kategori' => 'Keuntungan (Kerugian) Luar Biasa',
    //             'akun' => 'Keuntungan (Kerugian) Luar Biasa',
    //             'nilai_lr_sak_ep' => $total_seluruh_klb_tahun_ini,
    //             'nilai_lr_sak_ep_audited' => $total_seluruh_klb_tahun_ini,
    //             'posisi' => 11,
    //             'status' => 1,
    //             'created_at' => date('Y-m-d H:i:s'),
    //             'created_by' => $this->session->userdata('nama_lengkap')
    //         ];

    //         $this->db->insert('lr_sak_ep', $data);
    //         $this->session->set_flashdata(
    //             'info',
    //             '<div class="alert alert-primary alert-dismissible fade show" role="alert">
    //                     <strong>Sukses,</strong> Data berhasil disimpan ke Laba Rugi!
    //                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
    //                     </button>
    //                   </div>'
    //         );
    //     }
    //     redirect('lap_keuangan/keuntungan_kerugian_luar_biasa');
    // }
}
