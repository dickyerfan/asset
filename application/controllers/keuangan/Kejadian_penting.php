<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kejadian_penting extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_keuangan');
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
        if ($bagian != 'Publik' && $bagian != 'Administrator' && $bagian != 'Keuangan') {
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
        $data['title'] = 'INFORMASI KEJADIAN PENTING ' . $tahun;
        $data['kej_pen'] = $this->Model_keuangan->get_kej_pen($tahun);

        if ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('keuangan/view_kej_pen', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('keuangan/view_kej_pen', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('keuangan/view_kej_pen', $data);
            $this->load->view('templates/footer');
        }
    }

    public function cetak_kej_pen()
    {
        $tahun = $this->session->userdata('tahun_session');

        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'INFORMASI KEJADIAN PENTING ' . $tahun;
        $data['kej_pen'] = $this->Model_keuangan->get_kej_pen($tahun);

        $this->pdf->setPaper('folio', 'portrait');
        $this->pdf->filename = "kej_pen-{$tahun}.pdf";
        $this->pdf->generate('keuangan/cetak_kej_pen_pdf', $data);
    }

    public function input_kej_pen()
    {
        $tahun = $this->input->post('tahun_kej_pen');
        date_default_timezone_set('Asia/Jakarta');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required|trim');
        $this->form_validation->set_rules('kejadian', 'Kejadian', 'required|trim');
        $this->form_validation->set_rules('tahun_kej_pen', 'Tahun Kejadian', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input kej_pen PDAM';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('keuangan/view_input_kej_pen', $data);
            $this->load->view('templates/footer');
        } else {
            $keterangan = $this->input->post('keterangan');
            $kejadian = $this->input->post('kejadian');
            $tahun_kej_pen = $this->input->post('tahun_kej_pen');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_kej_pen = [
                'keterangan' => $keterangan,
                'kejadian' => $kejadian,
                'tahun_kej_pen' => $tahun_kej_pen,
                'created_by' => $created_by,
                'created_at' => $created_at
            ];

            // Cek apakah tahun dan id bagian sudah ada di database
            $this->db->where('kejadian', $kejadian);
            $this->db->where('tahun_kej_pen', $tahun_kej_pen);
            $this->db->where('keterangan', $keterangan);
            $query = $this->db->get('ek_kej_pen');

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Daftar Kejadian sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('keuangan/kejadian_penting?tahun=' . $tahun);
                return false;
            } else {
                $this->Model_keuangan->input_kej_pen('ek_kej_pen', $data_kej_pen);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Daftar kejadian baru berhasil ditambahkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('keuangan/kejadian_penting?tahun=' . $tahun);
            }
        }
    }

    public function edit_kej_pen($id_kej_pen)
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');
        $data['title'] = 'Edit kej_pen PDAM';
        $data['kej_pen'] = $this->Model_keuangan->get_id_kej_pen($id_kej_pen);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('keuangan/view_edit_kej_pen', $data);
        $this->load->view('templates/footer');
    }

    public function update_kej_pen()
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');

        $id_kej_pen = $this->input->post('id_kej_pen');
        $keterangan = $this->input->post('keterangan');
        $kejadian = $this->input->post('kejadian');
        $tahun_kej_pen = $this->input->post('tahun_kej_pen');
        $modified_by = $this->session->userdata('nama_lengkap');
        $modified_at = date('Y-m-d H:i:s');

        $data_tka = [
            'tahun_kej_pen' => $tahun_kej_pen,
            'keterangan' => $keterangan,
            'kejadian' => $kejadian,
            'modified_by' => $modified_by,
            'modified_at' => $modified_at
        ];

        $this->Model_keuangan->update_kej_pen($id_kej_pen, $data_tka);
        $this->session->set_flashdata(
            'info',
            '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Data Kejadian Penting berhasil diedit.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>'
        );
        redirect('keuangan/kejadian_penting?tahun=' . $tahun);
    }
}
