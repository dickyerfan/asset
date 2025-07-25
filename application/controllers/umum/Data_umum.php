<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data_umum extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_umum');
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
        if ($bagian != 'Umum' && $bagian != 'Publik' && $bagian != 'Administrator' && $bagian != 'Keuangan') {
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
        $data['title'] = 'DATA UMUM ' . $tahun;
        $data['umum'] = $this->Model_umum->get_data_umum($tahun);

        if ($this->session->userdata('bagian') == 'Umum') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_umum');
            $this->load->view('umum/view_data_umum', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('umum/view_data_umum', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('umum/view_data_umum', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('umum/view_data_umum', $data);
            $this->load->view('templates/footer');
        }
    }

    // public function cetak_kerjasama()
    // {
    //     $tahun = $this->session->userdata('tahun_session');

    //     if (empty($tahun)) {
    //         $this->session->unset_userdata('tahun_session');
    //         $tahun = date('Y');
    //     }

    //     $data['tahun_lap'] = $tahun;
    //     $data['title'] = 'PERJANJIAN KERJASAMA PDAM ' . $tahun;
    //     $data['kerjasama'] = $this->Model_umum->get_kerjasama($tahun);

    //     $this->pdf->setPaper('folio', 'portrait');
    //     $this->pdf->filename = "kerjasama-{$tahun}.pdf";
    //     $this->pdf->generate('umum/cetak_kerjasama_pdf', $data);
    // }

    public function input_data_umum()
    {
        $tahun = $this->input->post('tahun');
        date_default_timezone_set('Asia/Jakarta');
        $this->form_validation->set_rules('uraian', 'Uraian', 'required|trim');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|trim');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Data Umum PDAM';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_umum');
            $this->load->view('umum/view_input_data_umum', $data);
            $this->load->view('templates/footer');
        } else {
            $uraian = $this->input->post('uraian');
            $jumlah = $this->input->post('jumlah');
            $tahun = $this->input->post('tahun');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_data_umum = [
                'uraian' => $uraian,
                'jumlah' => $jumlah,
                'tahun' => $tahun,
                'created_by' => $created_by,
                'created_at' => $created_at
            ];

            // Cek apakah tahun dan id bagian sudah ada di database
            $this->db->where('uraian', $uraian);
            $this->db->where('tahun', $tahun);
            $query = $this->db->get('ek_data_umum');

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> daftar data umum sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('umum/data_umum?tahun=' . $tahun);
                return false;
            } else {
                $this->Model_umum->input_data_umum('ek_data_umum', $data_data_umum);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Daftar data umum baru berhasil ditambahkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('umum/data_umum?tahun=' . $tahun);
            }
        }
    }

    public function edit_data_umum($id_data_umum)
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');
        $data['title'] = 'Edit Data Umum PDAM';
        $data['data_umum'] = $this->Model_umum->get_id_data_umum($id_data_umum);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar_umum');
        $this->load->view('umum/view_edit_data_umum', $data);
        $this->load->view('templates/footer');
    }

    public function update_data_umum()
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');

        $id_data_umum = $this->input->post('id_data_umum');
        // $uraian = $this->input->post('uraian');
        // $tahun = $this->input->post('tahun');
        $jumlah = $this->input->post('jumlah');
        $modified_by = $this->session->userdata('nama_lengkap');
        $modified_at = date('Y-m-d H:i:s');

        $data_umum = [
            // 'tahun' => $tahun,
            // 'uraian' => $uraian,
            'jumlah' => $jumlah,
            'modified_by' => $modified_by,
            'modified_at' => $modified_at
        ];

        $this->Model_umum->update_data_umum($id_data_umum, $data_umum);
        $this->session->set_flashdata(
            'info',
            '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Data umum berhasil diedit.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>'
        );
        redirect('umum/data_umum?tahun=' . $tahun);
    }
}
