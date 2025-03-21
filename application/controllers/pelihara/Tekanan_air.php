<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tekanan_air extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_pelihara');
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
        if ($bagian != 'Pemeliharaan' && $bagian != 'Publik' && $bagian != 'Administrator' && $bagian != 'Keuangan') {
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
        $data['title'] = 'DATA TEKANAN AIR';
        $data['tekanan_air'] = $this->Model_pelihara->get_tekanan_air($tahun);

        if ($this->session->userdata('bagian') == 'Pemeliharaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('pelihara/view_tekanan_air', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('pelihara/view_tekanan_air', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('pelihara/view_tekanan_air', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('pelihara/view_tekanan_air', $data);
            $this->load->view('templates/footer');
        }
    }

    public function cetak_tekanan_air()
    {
        $tahun = $this->session->userdata('tahun_session');

        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'DATA TEKANAN AIR';
        $data['tekanan_air'] = $this->Model_pelihara->get_tekanan_air($tahun);

        $this->pdf->setPaper('folio', 'portrait');
        $this->pdf->filename = "tekanan_air-{$tahun}.pdf";
        $this->pdf->generate('pelihara/cetak_tekanan_air_pdf', $data);
    }

    public function input_tka()
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');
        $this->form_validation->set_rules('id_bagian', 'Nama UPK', 'required|trim');
        $this->form_validation->set_rules('jumlah_sr', 'Jumlah SR', 'required|trim');
        $this->form_validation->set_rules('jumlah_cek', 'Jumlah yang Dicek', 'required|trim');
        $this->form_validation->set_rules('jumlah_07', 'Jumlah diatas 07', 'required|trim');
        $this->form_validation->set_rules('jumlah_sr_70', 'Jumlah SR diatas 07', 'required|trim');
        $this->form_validation->set_rules('tahun_tka', 'Tahun', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Data Tekanan Air';
            $data['bagian'] = $this->Model_pelihara->get_bagian();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('pelihara/view_input_tka', $data);
            $this->load->view('templates/footer');
        } else {
            $id_bagian = $this->input->post('id_bagian');
            $jumlah_sr = $this->input->post('jumlah_sr');
            $jumlah_07 = $this->input->post('jumlah_07');
            $jumlah_cek = $this->input->post('jumlah_cek');
            $jumlah_sr_70 = $this->input->post('jumlah_sr_70');
            $tahun_tka = $this->input->post('tahun_tka');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_tka = [
                'id_bagian' => $id_bagian,
                'tahun_tka' => $tahun_tka,
                'jumlah_sr' => $jumlah_sr,
                'jumlah_07' => $jumlah_07,
                'jumlah_cek' => $jumlah_cek,
                'jumlah_sr_70' => $jumlah_sr_70,
                'created_by' => $created_by,
                'created_at' => $created_at
            ];

            // Cek apakah tahun dan id bagian sudah ada di database
            $this->db->where('tahun_tka', $tahun);
            $this->db->where('id_bagian', $id_bagian);
            $query = $this->db->get('ek_tekanan_air');

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Data Tekanan Air sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('pelihara/tekanan_air?tahun=' . $tahun);
                return false;
            } else {
                $this->Model_pelihara->input_tka('ek_tekanan_air', $data_tka);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Data Tekanan Air berhasil ditambahkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('pelihara/tekanan_air?tahun=' . $tahun);
            }
        }
    }

    public function edit_tka($id_ek_tka)
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');
        $data['title'] = 'Edit Data Tekanan Air';
        $data['tekanan_air'] = $this->Model_pelihara->get_tekanan_air_by_id($id_ek_tka);
        $data['bagian_upk'] = $this->Model_pelihara->get_bagian();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar_pelihara');
        $this->load->view('pelihara/view_edit_tka', $data);
        $this->load->view('templates/footer');
    }

    public function update_tka()
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');

        $id_ek_tka = $this->input->post('id_ek_tka');
        $id_bagian = $this->input->post('id_bagian');
        $jumlah_sr = $this->input->post('jumlah_sr');
        $jumlah_07 = $this->input->post('jumlah_07');
        $jumlah_cek = $this->input->post('jumlah_cek');
        $jumlah_sr_70 = $this->input->post('jumlah_sr_70');
        $tahun_tka = $this->input->post('tahun_tka');
        $modified_by = $this->session->userdata('nama_lengkap');
        $modified_at = date('Y-m-d H:i:s');

        $data_tka = [
            'id_bagian' => $id_bagian,
            'tahun_tka' => $tahun_tka,
            'jumlah_sr' => $jumlah_sr,
            'jumlah_07' => $jumlah_07,
            'jumlah_cek' => $jumlah_cek,
            'jumlah_sr_70' => $jumlah_sr_70,
            'modified_by' => $modified_by,
            'modified_at' => $modified_at
        ];

        $this->Model_pelihara->update_tekanan_air($id_ek_tka, $data_tka);
        $this->session->set_flashdata(
            'info',
            '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Data Tera Meter berhasil diedit.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>'
        );
        redirect('pelihara/tekanan_air?tahun=' . $tahun);
    }
}
