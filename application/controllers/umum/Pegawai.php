<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai extends CI_Controller
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
        $data['title'] = 'DATA PEGAWAI PDAM ' . $tahun;
        $data['karyawan'] = $this->Model_umum->get_all_karyawan($tahun);

        if ($this->session->userdata('bagian') == 'Umum') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_umum');
            $this->load->view('umum/view_pegawai', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('umum/view_pegawai', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('umum/view_pegawai', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('umum/view_pegawai', $data);
            $this->load->view('templates/footer');
        }
    }

    // public function cetak_pegawai()
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

    public function input_pegawai()
    {
        $tahun = $this->input->post('tahun_perjanjian');
        date_default_timezone_set('Asia/Jakarta');
        $data['bagian'] = $this->db->get('bagian')->result();
        $data['subag'] = $this->db->get('subag')->result();
        $data['jabatan'] = $this->db->get('jabatan')->result();
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
        $this->form_validation->set_rules('nik', 'NIK', 'is_unique[pegawai.nik]');
        $this->form_validation->set_rules('no_hp', 'NO HP', 'trim|min_length[10]');
        $this->form_validation->set_rules('agama', 'Agama', 'required|trim');
        $this->form_validation->set_rules('tmp_lahir', 'Tempat Lahir', 'required|trim');
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required|trim');
        // $this->form_validation->set_rules('tgl_masuk', 'Tanggal Masuk', 'required|trim');
        $this->form_validation->set_rules('id_bagian', 'Bagian', 'required|trim');
        $this->form_validation->set_rules('id_subag', 'Sub Bagian', 'required|trim');
        $this->form_validation->set_rules('id_jabatan', 'Jabatan', 'required|trim');
        $this->form_validation->set_rules('status_pegawai', 'Status Pegawai', 'required|trim');
        $this->form_validation->set_rules('jenkel', 'Jenis Kelamin', 'required|trim');
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_message('is_unique', '%s sudah terdaftar');
        $this->form_validation->set_message('min_length', '%s Minimal 10 digit');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Form Tambah Karyawan';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_umum');
            $this->load->view('umum/view_input_pegawai', $data);
            $this->load->view('templates/footer');
        } else {
            $nama = ucwords(strtolower($this->input->post('nama', true)));
            $data_pegawai = [
                'nama' => $nama,
                'alamat' => $this->input->post('alamat', true),
                'nik' => $this->input->post('nik', true),
                'no_hp' => $this->input->post('no_hp', true),
                'agama' => $this->input->post('agama', true),
                'status_pegawai' => $this->input->post('status_pegawai', true),
                'jenkel' => $this->input->post('jenkel', true),
                'tmp_lahir' => $this->input->post('tmp_lahir', true),
                'tgl_lahir' => $this->input->post('tgl_lahir', true),
                'tgl_masuk' => $this->input->post('tgl_masuk', true),
                'id_bagian' => $this->input->post('id_bagian', true),
                'id_subag' => $this->input->post('id_subag', true),
                'id_jabatan' => $this->input->post('id_jabatan', true),
                'created_by' => $created_by,
                'created_at' => $created_at
            ];
            // Cek apakah nama sudah ada di database
            $this->db->where('nama', $nama);
            // $this->db->where('tahun_perjanjian', $tahun_perjanjian);
            $query = $this->db->get('pegawai');

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Daftar karyawan sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('umum/pegawai?tahun=' . $tahun);
                return false;
            } else {
                $this->Model_umum->input_pegawai('pegawai', $data_pegawai);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Daftar karyawan baru berhasil ditambahkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('umum/pegawai?tahun=' . $tahun);
            }
        }
    }

    public function edit_pegawai($id)
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');
        $data['title'] = 'Form Edit Karyawan';
        $data['karyawan'] = $this->Model_umum->getIdKaryawan($id);
        $data['bagian'] = $this->db->get('bagian')->result();
        $data['subag'] = $this->db->get('subag')->result();
        $data['jabatan'] = $this->db->get('jabatan')->result();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar_umum');
        $this->load->view('umum/view_edit_pegawai', $data);
        $this->load->view('templates/footer');
    }

    public function update_pegawai()
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');

        $modified_by = $this->session->userdata('nama_lengkap');
        $modified_at = date('Y-m-d H:i:s');

        $id = $this->input->post('id', true);
        $data_pegawai = [
            'nama' => $this->input->post('nama', true),
            'alamat' => $this->input->post('alamat', true),
            'nik' => $this->input->post('nik', true),
            'no_hp' => $this->input->post('no_hp', true),
            'agama' => $this->input->post('agama', true),
            'status_pegawai' => $this->input->post('status_pegawai', true),
            'jenkel' => $this->input->post('jenkel', true),
            'tmp_lahir' => $this->input->post('tmp_lahir', true),
            'tgl_lahir' => $this->input->post('tgl_lahir', true),
            'tgl_masuk' => $this->input->post('tgl_masuk', true),
            'id_bagian' => $this->input->post('id_bagian', true),
            'id_subag' => $this->input->post('id_subag', true),
            'id_jabatan' => $this->input->post('id_jabatan', true),
            'tahun_keluar' => $this->input->post('tahun_keluar', true),
            // 'aktif' => $this->input->post('aktif', true),
            'modified_by' => $modified_by,
            'modified_at' => $modified_at
        ];

        $this->Model_umum->update_pegawai($id, $data_pegawai);
        $this->session->set_flashdata(
            'info',
            '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Data Karyawan berhasil diedit.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>'
        );
        redirect('umum/pegawai?tahun=' . $tahun);
    }
}
