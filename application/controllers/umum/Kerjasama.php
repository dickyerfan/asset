<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kerjasama extends CI_Controller
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
        $data['title'] = 'PERJANJIAN KERJASAMA PDAM ' . $tahun;
        $data['kerjasama'] = $this->Model_umum->get_kerjasama($tahun);

        if ($this->session->userdata('bagian') == 'Umum') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_umum');
            $this->load->view('umum/view_kerjasama', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('umum/view_kerjasama', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('umum/view_kerjasama', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('umum/view_kerjasama', $data);
            $this->load->view('templates/footer');
        }
    }

    public function cetak_kerjasama()
    {
        $tahun = $this->session->userdata('tahun_session');

        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'PERJANJIAN KERJASAMA PDAM ' . $tahun;
        $data['kerjasama'] = $this->Model_umum->get_kerjasama($tahun);

        $this->pdf->setPaper('folio', 'portrait');
        $this->pdf->filename = "kerjasama-{$tahun}.pdf";
        $this->pdf->generate('umum/cetak_kerjasama_pdf', $data);
    }

    public function input_kerjasama()
    {
        $tahun = $this->input->post('tahun_perjanjian');
        date_default_timezone_set('Asia/Jakarta');
        $this->form_validation->set_rules('no_perjanjian', 'No Perjanjian', 'required|trim');
        $this->form_validation->set_rules('tentang_perjanjian', 'Tentang Perjanjian', 'required|trim');
        $this->form_validation->set_rules('tahun_perjanjian', 'Tahun Perjanjian', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Kerjasama PDAM';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_umum');
            $this->load->view('umum/view_input_kerjasama', $data);
            $this->load->view('templates/footer');
        } else {
            $no_perjanjian = $this->input->post('no_perjanjian');
            $tentang_perjanjian = $this->input->post('tentang_perjanjian');
            $tahun_perjanjian = $this->input->post('tahun_perjanjian');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_kerjasama = [
                'no_perjanjian' => $no_perjanjian,
                'tentang_perjanjian' => $tentang_perjanjian,
                'tahun_perjanjian' => $tahun_perjanjian,
                'created_by' => $created_by,
                'created_at' => $created_at
            ];

            // Cek apakah tahun dan id bagian sudah ada di database
            $this->db->where('no_perjanjian', $no_perjanjian);
            $this->db->where('tahun_perjanjian', $tahun_perjanjian);
            $query = $this->db->get('ek_kerjasama');

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> daftar Kerjasama sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('umum/kerjasama?tahun=' . $tahun);
                return false;
            } else {
                $this->Model_umum->input_kerjasama('ek_kerjasama', $data_kerjasama);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Daftar Kerjasama baru berhasil ditambahkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('umum/kerjasama?tahun=' . $tahun);
            }
        }
    }

    public function edit_kerjasama($id_kerjasama)
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');
        $data['title'] = 'Edit Kerjasama PDAM';
        $data['kerjasama'] = $this->Model_umum->get_id_kerjasama($id_kerjasama);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar_umum');
        $this->load->view('umum/view_edit_kerjasama', $data);
        $this->load->view('templates/footer');
    }

    public function update_kerjasama()
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');

        $id_kerjasama = $this->input->post('id_kerjasama');
        $no_perjanjian = $this->input->post('no_perjanjian');
        $tentang_perjanjian = $this->input->post('tentang_perjanjian');
        $tahun_perjanjian = $this->input->post('tahun_perjanjian');
        $modified_by = $this->session->userdata('nama_lengkap');
        $modified_at = date('Y-m-d H:i:s');

        $data_tka = [
            'tahun_perjanjian' => $tahun_perjanjian,
            'no_perjanjian' => $no_perjanjian,
            'tentang_perjanjian' => $tentang_perjanjian,
            'modified_by' => $modified_by,
            'modified_at' => $modified_at
        ];

        $this->Model_umum->update_kerjasama($id_kerjasama, $data_tka);
        $this->session->set_flashdata(
            'info',
            '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Data Kerjasama berhasil diedit.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>'
        );
        redirect('umum/kerjasama?tahun=' . $tahun);
    }
}
