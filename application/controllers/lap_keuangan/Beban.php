<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Beban extends CI_Controller
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

        $data['title'] = 'Beban Operasi';
        $data['title2'] = 'Beban Pengolahan Air';
        $data['title3'] = 'Beban Transmisi Dan Distribusi';
        $data['title4'] = 'Beban (HPP) Sambungan Baru';
        $data['title5'] = 'Beban Umum Dan Adminstrasi';
        $data['title6'] = 'Beban Lain-lain';

        $data['bop_input'] = $this->Model_labarugi->get_bop_input($tahun);
        $data['bpa_input'] = $this->Model_labarugi->get_bpa_input($tahun);
        $data['btd_input'] = $this->Model_labarugi->get_btd_input($tahun);
        $data['bsb_input'] = $this->Model_labarugi->get_bsb_input($tahun);
        $data['bua_input'] = $this->Model_labarugi->get_bua_input($tahun);
        $data['bll_input'] = $this->Model_labarugi->get_bll_input($tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/laba_rugi/view_beban', $data);
        $this->load->view('templates/footer');
    }

    public function input_bop()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_bop', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('jenis_bop', 'Jenis', 'required|trim');
        $this->form_validation->set_rules('tgl_bop', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_bop', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Beban Operasi';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/laba_rugi/view_upload_bop', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_labarugi->input_bop();
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
                    <strong>Sukses!</strong> Data input Beban Operasi baru berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/beban');
        }
    }

    public function input_bop_neraca($tahun, $total_seluruh_bop_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');

        if ($total_seluruh_bop_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/beban');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_lr_sak_ep', $tahun);
        $this->db->where('kategori', 'Beban Usaha');
        $this->db->where('akun', 'a. Beban Operasi');
        $query = $this->db->get('lr_sak_ep');

        if ($query->num_rows() > 0) {
            // Jika data sudah ada, tampilkan pesan peringatan
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data sudah ada! Tidak dapat menambahkan data yang sama.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        } else {
            // Jika belum ada, lakukan insert
            $data = [
                'tahun_lr_sak_ep' => $tahun,
                'kategori' => 'Beban Usaha',
                'akun' => 'a. Beban Operasi',
                'nilai_lr_sak_ep' => $total_seluruh_bop_tahun_ini,
                'nilai_lr_sak_ep_audited' => $total_seluruh_bop_tahun_ini,
                'posisi' => 4,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama_lengkap')
            ];

            $this->db->insert('lr_sak_ep', $data);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Data berhasil disimpan ke Laba Rugi!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        }
        redirect('lap_keuangan/beban');
    }

    public function input_bpa()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_bpa', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('jenis_bpa', 'Jenis', 'required|trim');
        $this->form_validation->set_rules('tgl_bpa', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_bpa', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Beban Pengolah Air';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/laba_rugi/view_upload_bpa', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_labarugi->input_bpa();
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
                    <strong>Sukses!</strong> Data input Beban Pengolah Air baru berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/beban');
        }
    }

    public function input_bpa_neraca($tahun, $total_seluruh_bpa_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');

        if ($total_seluruh_bpa_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/beban');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_lr_sak_ep', $tahun);
        $this->db->where('kategori', 'Beban Usaha');
        $this->db->where('akun', 'b. Beban Pengolahan Air');
        $query = $this->db->get('lr_sak_ep');

        if ($query->num_rows() > 0) {
            // Jika data sudah ada, tampilkan pesan peringatan
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data sudah ada! Tidak dapat menambahkan data yang sama.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        } else {
            // Jika belum ada, lakukan insert
            $data = [
                'tahun_lr_sak_ep' => $tahun,
                'kategori' => 'Beban Usaha',
                'akun' => 'b. Beban Pengolahan Air',
                'nilai_lr_sak_ep' => $total_seluruh_bpa_tahun_ini,
                'nilai_lr_sak_ep_audited' => $total_seluruh_bpa_tahun_ini,
                'posisi' => 5,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama_lengkap')
            ];

            $this->db->insert('lr_sak_ep', $data);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Data berhasil disimpan ke Laba Rugi!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        }
        redirect('lap_keuangan/beban');
    }

    public function input_btd()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_btd', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('jenis_btd', 'Jenis', 'required|trim');
        $this->form_validation->set_rules('tgl_btd', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_btd', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Beban Transmisi Dan Distribusi';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/laba_rugi/view_upload_btd', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_labarugi->input_btd();
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
                    <strong>Sukses!</strong> Data input Beban Transmisi Dan Distribusi baru berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/beban');
        }
    }

    public function input_btd_neraca($tahun, $total_seluruh_btd_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');

        if ($total_seluruh_btd_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/beban');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_lr_sak_ep', $tahun);
        $this->db->where('kategori', 'Beban Usaha');
        $this->db->where('akun', 'c. Beban Transmisi dan Distribusi');
        $query = $this->db->get('lr_sak_ep');

        if ($query->num_rows() > 0) {
            // Jika data sudah ada, tampilkan pesan peringatan
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data sudah ada! Tidak dapat menambahkan data yang sama.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        } else {
            // Jika belum ada, lakukan insert
            $data = [
                'tahun_lr_sak_ep' => $tahun,
                'kategori' => 'Beban Usaha',
                'akun' => 'c. Beban Transmisi dan Distribusi',
                'nilai_lr_sak_ep' => $total_seluruh_btd_tahun_ini,
                'nilai_lr_sak_ep_audited' => $total_seluruh_btd_tahun_ini,
                'posisi' => 6,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama_lengkap')
            ];

            $this->db->insert('lr_sak_ep', $data);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Data berhasil disimpan ke Laba Rugi!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        }
        redirect('lap_keuangan/beban');
    }

    public function input_bsb()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_bsb', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('jenis_bsb', 'Jenis', 'required|trim');
        $this->form_validation->set_rules('tgl_bsb', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_bsb', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Beban (HPP) Sambungan Baru';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/laba_rugi/view_upload_bsb', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_labarugi->input_bsb();
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
                    <strong>Sukses!</strong> Data input Beban (HPP) Sambungan Baru baru berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/beban');
        }
    }

    public function input_bsb_neraca($tahun, $total_seluruh_bsb_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun_ini = date('Y');
        if ($total_seluruh_bsb_tahun_ini == 0 && $tahun == $tahun_ini) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/beban');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_lr_sak_ep', $tahun);
        $this->db->where('kategori', 'Beban Usaha');
        $this->db->where('akun', 'd. Beban (HPP) Sambungan Baru');
        $query = $this->db->get('lr_sak_ep');

        if ($query->num_rows() > 0) {
            // Jika data sudah ada, tampilkan pesan peringatan
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data sudah ada! Tidak dapat menambahkan data yang sama.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        } else {
            // Jika belum ada, lakukan insert
            $data = [
                'tahun_lr_sak_ep' => $tahun,
                'kategori' => 'Beban Usaha',
                'akun' => 'd. Beban (HPP) Sambungan Baru',
                'nilai_lr_sak_ep' => $total_seluruh_bsb_tahun_ini,
                'nilai_lr_sak_ep_audited' => $total_seluruh_bsb_tahun_ini,
                'posisi' => 7,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama_lengkap')
            ];

            $this->db->insert('lr_sak_ep', $data);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Data berhasil disimpan ke Laba Rugi!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        }
        redirect('lap_keuangan/beban');
    }

    public function input_bua()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_bua', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('jenis_bua', 'Jenis', 'required|trim');
        $this->form_validation->set_rules('tgl_bua', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_bua', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Beban (HPP) Sambungan Baru';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/laba_rugi/view_upload_bua', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_labarugi->input_bua();
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
                    <strong>Sukses!</strong> Data input Beban (HPP) Sambungan Baru baru berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/beban');
        }
    }

    public function input_bua_neraca($tahun, $total_seluruh_bua_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun_ini = date('Y');
        if ($total_seluruh_bua_tahun_ini == 0 && $tahun == $tahun_ini) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/beban');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_lr_sak_ep', $tahun);
        $this->db->where('kategori', 'Beban Umum Dan Administrasi');
        $this->db->where('akun', 'Beban Umum Dan Administrasi');
        $query = $this->db->get('lr_sak_ep');

        if ($query->num_rows() > 0) {
            // Jika data sudah ada, tampilkan pesan peringatan
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data sudah ada! Tidak dapat menambahkan data yang sama.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        } else {
            // Jika belum ada, lakukan insert
            $data = [
                'tahun_lr_sak_ep' => $tahun,
                'kategori' => 'Beban Umum Dan Administrasi',
                'akun' => 'Beban Umum Dan Administrasi',
                'nilai_lr_sak_ep' => $total_seluruh_bua_tahun_ini,
                'nilai_lr_sak_ep_audited' => $total_seluruh_bua_tahun_ini,
                'posisi' => 8,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama_lengkap')
            ];

            $this->db->insert('lr_sak_ep', $data);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Data berhasil disimpan ke Laba Rugi!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        }
        redirect('lap_keuangan/beban');
    }

    public function input_bll()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_bll', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('jenis_bll', 'Jenis', 'required|trim');
        $this->form_validation->set_rules('tgl_bll', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_bll', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Beban (HPP) Sambungan Baru';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/laba_rugi/view_upload_bll', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_labarugi->input_bll();
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
                    <strong>Sukses!</strong> Data input Beban (HPP) Sambungan Baru baru berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/beban');
        }
    }

    public function input_bll_neraca($tahun, $total_seluruh_bll_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun_ini = date('Y');
        if ($total_seluruh_bll_tahun_ini == 0 && $tahun == $tahun_ini) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/beban');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_lr_sak_ep', $tahun);
        $this->db->where('kategori', 'Pendapatan - Beban Lain-lain');
        $this->db->where('akun', 'Beban Lain-lain');
        $query = $this->db->get('lr_sak_ep');

        if ($query->num_rows() > 0) {
            // Jika data sudah ada, tampilkan pesan peringatan
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data sudah ada! Tidak dapat menambahkan data yang sama.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        } else {
            // Jika belum ada, lakukan insert
            $data = [
                'tahun_lr_sak_ep' => $tahun,
                'kategori' => 'Pendapatan - Beban Lain-lain',
                'akun' => 'Beban Lain-lain',
                'nilai_lr_sak_ep' => $total_seluruh_bll_tahun_ini * -1,
                'nilai_lr_sak_ep_audited' => $total_seluruh_bll_tahun_ini * -1,
                'posisi' => 10,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama_lengkap')
            ];

            $this->db->insert('lr_sak_ep', $data);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Data berhasil disimpan ke Laba Rugi!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        }
        redirect('lap_keuangan/beban');
    }
}
