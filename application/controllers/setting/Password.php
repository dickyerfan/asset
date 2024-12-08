<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Password extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
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
    }

    public function index()
    {
        $data['title'] = "Ganti password";

        $this->form_validation->set_rules('passLama', 'Password Lama', 'required|trim');
        $this->form_validation->set_rules('passBaru', 'Password Baru', 'required|trim|min_length[4]');
        $this->form_validation->set_rules('passConf', 'Password Konfirmasi', 'required|trim|matches[passBaru]');
        $this->form_validation->set_message('required', '%s Harus di isi');
        $this->form_validation->set_message('min_length', '%s Minimal 5 karakter');
        $this->form_validation->set_message('matches', '%s harus sama dengan password baru');

        if ($this->form_validation->run() == false) {
            if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan' || $this->session->userdata('bagian') == 'Auditor') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('setting/view_gantiPassword', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('setting/view_gantiPassword', $data);
                $this->load->view('templates/footer_baku');
            } else if ($this->session->userdata('bagian') == 'Umum') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_umum');
                $this->load->view('setting/view_gantiPassword', $data);
                $this->load->view('templates/footer_produksi');
            }
        } else {
            $cek_pass = $this->db->get_where('user', ['nama_pengguna' => $this->session->userdata('nama_pengguna')])->row();
            $passwordLama = $this->input->post('passLama');
            $passwordBaru = $this->input->post('passBaru');

            if (!password_verify($passwordLama, $cek_pass->password)) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width:50%;">
							<strong>Maaf,</strong> Password saat ini salah
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
							</button>
						  </div>'
                );
                redirect('password');
            } else {
                if ($passwordLama == $passwordBaru) {
                    $this->session->set_flashdata(
                        'info',
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width:50%;">
								<strong>Maaf,</strong> Password lama tidak boleh sama dengan password baru
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
								</button>
							  </div>'
                    );
                    redirect('password');
                } else {
                    $passwordHash = password_hash($passwordBaru, PASSWORD_DEFAULT);
                    $this->db->set('password', $passwordHash);
                    $this->db->where('nama_pengguna', $this->session->userdata('nama_pengguna'));
                    $this->db->update('user');

                    $this->session->set_flashdata(
                        'info',
                        '<div class="alert alert-success alert-dismissible fade show" role="alert" style="width:50%;">
								<strong>Selamat,</strong> Password berhasil di ganti
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
								</button>
							  </div>'
                    );
                    redirect('auth/logout');
                }
            }
        }
    }

    // public function profil()
    // {
    // 	$data['title'] = "Profil";
    // 	if ($this->session->userdata('level') == 'Admin') {
    // 		$this->load->view('templates/header', $data);
    // 		$this->load->view('templates/navbar');
    // 		$this->load->view('templates/sidebar');
    // 		$this->load->view('view_profil', $data);
    // 		$this->load->view('templates/footer');
    // 	} else if ($this->session->userdata('upk_bagian') == 'baku') {
    // 		$this->load->view('templates/pengguna/header', $data);
    // 		$this->load->view('templates/pengguna/navbar_baku');
    // 		$this->load->view('templates/pengguna/sidebar_baku');
    // 		$this->load->view('view_profil', $data);
    // 		$this->load->view('templates/pengguna/footer_baku');
    // 	} else if ($this->session->userdata('upk_bagian') == 'produksi') {
    // 		$this->load->view('templates/pengguna/header', $data);
    // 		$this->load->view('templates/pengguna/navbar_produksi');
    // 		$this->load->view('templates/pengguna/sidebar_produksi');
    // 		$this->load->view('view_profil', $data);
    // 		$this->load->view('templates/pengguna/footer_produksi');
    // 	} else if ($this->session->userdata('upk_bagian') == 'jadi') {
    // 		$this->load->view('templates/pengguna/header', $data);
    // 		$this->load->view('templates/pengguna/navbar_jadi');
    // 		$this->load->view('templates/pengguna/sidebar_jadi');
    // 		$this->load->view('view_profil', $data);
    // 		$this->load->view('templates/pengguna/footer_jadi');
    // 	} else if ($this->session->userdata('upk_bagian') == 'pasar') {
    // 		$this->load->view('templates/pengguna/header', $data);
    // 		$this->load->view('templates/pengguna/navbar_pasar');
    // 		$this->load->view('templates/pengguna/sidebar_pasar');
    // 		$this->load->view('view_profil', $data);
    // 		$this->load->view('templates/pengguna/footer');
    // 	} else if ($this->session->userdata('upk_bagian') == 'uang') {
    // 		$this->load->view('templates/pengguna/header', $data);
    // 		$this->load->view('templates/pengguna/navbar_uang');
    // 		$this->load->view('templates/pengguna/sidebar_uang');
    // 		$this->load->view('view_profil', $data);
    // 		$this->load->view('templates/pengguna/footer');
    // 	}
    // }
}
