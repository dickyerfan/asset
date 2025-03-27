<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kualitas_air extends CI_Controller
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
        $data['title'] = 'DATA UJI KUALITAS DAN TEMPAT UJI KUALITAS AIR';
        $data['kualitas_air'] = $this->Model_pelihara->get_kualitas_air($tahun);

        if ($this->session->userdata('bagian') == 'Pemeliharaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('pelihara/view_kualitas_air', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('pelihara/view_kualitas_air', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('pelihara/view_kualitas_air', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('pelihara/view_kualitas_air', $data);
            $this->load->view('templates/footer');
        }
    }

    public function cetak_kualitas_air()
    {
        $tahun = $this->session->userdata('tahun_session');

        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'DATA UJI KUALITAS DAN TEMPAT UJI KUALITAS AIR';
        $data['kualitas_air'] = $this->Model_pelihara->get_kualitas_air($tahun);

        $this->pdf->setPaper('folio', 'portrait');
        $this->pdf->filename = "kualitas_air-{$tahun}.pdf";
        $this->pdf->generate('pelihara/cetak_kualitas_air_pdf', $data);
    }

    public function input_kualitas_air()
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');
        $this->form_validation->set_rules('parameter', 'Parameter', 'required|trim');
        $this->form_validation->set_rules('jumlah_sample_int', 'Jumlah Sample Int', 'required|trim');
        $this->form_validation->set_rules('jumlah_terambil', 'Jumlah Terambil', 'required|trim');
        $this->form_validation->set_rules('jumlah_sample_oke_ya', 'Jumlah Sample Memenuhi Syarat', 'required|trim');
        $this->form_validation->set_rules('tempat_uji', 'Tempat Uji', 'required|trim');
        $this->form_validation->set_rules('tahun_ka', 'Tahun', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Kualitas Air';
            // $data['bagian'] = $this->Model_pelihara->get_bagian();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('pelihara/view_input_kualitas_air', $data);
            $this->load->view('templates/footer');
        } else {
            $parameter = $this->input->post('parameter');
            $jumlah_sample_int = $this->input->post('jumlah_sample_int');
            $jumlah_sample_eks = $this->input->post('jumlah_sample_eks');
            $jumlah_terambil = $this->input->post('jumlah_terambil');
            $jumlah_sample_oke_ya = $this->input->post('jumlah_sample_oke_ya');
            $jumlah_sample_oke_tidak = $this->input->post('jumlah_sample_oke_tidak');
            $tempat_uji = $this->input->post('tempat_uji');
            $tahun_ka = $this->input->post('tahun_ka');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_kualitas_air = [
                'parameter' => $parameter,
                'jumlah_sample_int' => $jumlah_sample_int,
                'jumlah_sample_eks' => $jumlah_sample_eks,
                'jumlah_terambil' => $jumlah_terambil,
                'jumlah_sample_oke_ya' => $jumlah_sample_oke_ya,
                'jumlah_sample_oke_tidak' => $jumlah_sample_oke_tidak,
                'tempat_uji' => $tempat_uji,
                'tahun_ka' => $tahun_ka,
                'created_by' => $created_by,
                'created_at' => $created_at
            ];

            // Cek apakah bulan dan tahun dari tahun_ka serta parameter sudah ada di database
            $this->db->where('parameter', $parameter);
            $this->db->where('YEAR(tahun_ka)', date('Y', strtotime($tahun_ka)), false);
            $this->db->where('MONTH(tahun_ka)', date('m', strtotime($tahun_ka)), false);
            $query = $this->db->get('ek_kualitas_air');


            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> daftar Sumber sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('pelihara/kualitas_air?tahun=' . $tahun);
                return false;
            } else {
                $this->Model_pelihara->input_kualitas_air('ek_kualitas_air', $data_kualitas_air);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Daftar Sumber baru berhasil ditambahkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('pelihara/kualitas_air?tahun=' . $tahun);
            }
        }
    }


    public function edit_ka($id_ek_ka)
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');
        $data['title'] = 'Edit Data Kualitas Air';
        $data['kualitas_air'] = $this->Model_pelihara->get_kualitas_air_by_id($id_ek_ka);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar_pelihara');
        $this->load->view('pelihara/view_edit_ka', $data);
        $this->load->view('templates/footer');
    }

    public function update_ka()
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');

        $id_ek_ka = $this->input->post('id_ek_ka');
        $parameter = $this->input->post('parameter');
        $jumlah_sample_int = $this->input->post('jumlah_sample_int');
        $jumlah_sample_eks = $this->input->post('jumlah_sample_eks');
        $jumlah_terambil = $this->input->post('jumlah_terambil');
        $jumlah_sample_oke_ya = $this->input->post('jumlah_sample_oke_ya');
        $jumlah_sample_oke_tidak = $this->input->post('jumlah_sample_oke_tidak');
        $tempat_uji = $this->input->post('tempat_uji');
        $tahun_ka = $this->input->post('tahun_ka');
        $modified_by = $this->session->userdata('nama_lengkap');
        $modified_at = date('Y-m-d H:i:s');

        $data_ka = [
            'id_ek_ka' => $id_ek_ka,
            'parameter' => $parameter,
            'jumlah_sample_int' => $jumlah_sample_int,
            'jumlah_sample_eks' => $jumlah_sample_eks,
            'jumlah_terambil' => $jumlah_terambil,
            'jumlah_sample_oke_ya' => $jumlah_sample_oke_ya,
            'jumlah_sample_oke_tidak' => $jumlah_sample_oke_tidak,
            'tempat_uji' => $tempat_uji,
            'tahun_ka' => $tahun_ka,
            'modified_by' => $modified_by,
            'modified_at' => $modified_at
        ];

        $this->Model_pelihara->update_kualitas_air($id_ek_ka, $data_ka);
        $this->session->set_flashdata(
            'info',
            '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Data Kualitas Air berhasil diedit.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>'
        );
        redirect('pelihara/kualitas_air?tahun=' . $tahun);
    }

    public function jumlah_sample_uji()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_su', $get_tahun);
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'Jumlah Sampel Pengawasan Eksternal Kualitas Air Minum';
        $data['sample_uji'] = $this->Model_pelihara->get_sample_uji($tahun);

        if ($this->session->userdata('bagian') == 'Pemeliharaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('pelihara/view_jumlah_sample_uji', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('pelihara/view_jumlah_sample_uji', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('pelihara/view_jumlah_sample_uji', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('pelihara/view_jumlah_sample_uji', $data);
            $this->load->view('templates/footer');
        }
    }

    public function cetak_jumlah_sample_uji()
    {
        $tahun = $this->session->userdata('tahun_session_su');

        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session_su');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'Jumlah Sampel Pengawasan Eksternal Kualitas Air Minum';
        $data['sample_uji'] = $this->Model_pelihara->get_sample_uji($tahun);

        $this->pdf->setPaper('folio', 'portrait');
        $this->pdf->filename = "jumlah_sample_uji-{$tahun}.pdf";
        $this->pdf->generate('pelihara/cetak_jumlah_sample_uji_pdf', $data);
    }

    public function uji_syarat()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_us', $get_tahun);
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'RESUME PENGUJIAN KUALITAS AIR';
        $data['uji_syarat'] = $this->Model_pelihara->get_uji_syarat($tahun);

        if ($this->session->userdata('bagian') == 'Pemeliharaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('pelihara/view_uji_syarat', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('pelihara/view_uji_syarat', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('pelihara/view_uji_syarat', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('pelihara/view_uji_syarat', $data);
            $this->load->view('templates/footer');
        }
    }

    public function cetak_uji_syarat()
    {
        $tahun = $this->session->userdata('tahun_session_us');

        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session_us');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'RESUME PENGUJIAN KUALITAS AIR';
        $data['uji_syarat'] = $this->Model_pelihara->get_uji_syarat($tahun);

        $this->pdf->setPaper('folio', 'landscape');
        $this->pdf->filename = "uji_syarat-{$tahun}.pdf";
        $this->pdf->generate('pelihara/cetak_uji_syarat_pdf', $data);
    }
}
