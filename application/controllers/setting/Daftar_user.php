<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Daftar_user extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_user');
        $this->load->library('form_validation');
        if (!$this->session->userdata('nama_pengguna')) {
            redirect('auth');
        }
    }

    public function index()
    {
        $data['title'] = "Daftar Pengguna";
        $data['user'] = $this->model_user->getAll();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('setting/view_admin', $data);
        $this->load->view('templates/footer');
    }

    public function tambah()
    {
        $data['title'] = "Tambah User/Admin";

        $this->form_validation->set_rules('nama_pengguna', 'Nama Pengguna', 'required|trim|min_length[4]|is_unique[user.nama_pengguna]');
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('bagian', 'Bagian', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[4]');

        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('is_unique', '%s Sudah terdaftar, Ganti yang lain');
        $this->form_validation->set_message('min_length', '%s Minimal 4 karakter');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('setting/view_adminTambah', $data);
            $this->load->view('templates/footer');
        } else {
            $data['user'] = $this->model_user->tambahData();
            redirect('setting/daftar_user');
        }
    }

    public function edit($id)
    {
        $data['title'] = "Form Edit User";
        $data['judul'] = "Form Edit User";
        $data['user'] = $this->db->get_where('user', ['id' => $id])->row();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('setting/view_adminEdit', $data);
        $this->load->view('templates/footer');
    }

    public function update()
    {
        $this->model_user->updateData();
        if ($this->db->affected_rows() <= 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width:50%;">
                        <strong>Maaf,</strong> tidak ada perubahan data
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>'
            );
            redirect('setting/daftar_user');
        } else {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-success alert-dismissible fade show" role="alert" style="width:50%;">
                        <strong>Sukses,</strong> Data berhasil di update
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>'
            );
            redirect('setting/daftar_user');
        }
    }

    public function hapus($id)
    {
        $this->model_user->hapusData($id);
        redirect('setting/daftar_user');
    }
}
