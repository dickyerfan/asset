<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pendapatan extends CI_Controller
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

        $data['title'] = 'Pendapatan Penjualan Air';
        $data['title2'] = 'Pendapatan Penjualan Non Air';
        $data['title3'] = 'Pendapatan Kemitraan';
        $data['title4'] = 'Pendapatan Lain-lain';

        $data['ppa_input'] = $this->Model_labarugi->get_ppa_input($tahun);
        $data['ppna_input'] = $this->Model_labarugi->get_ppna_input($tahun);
        $data['pk_input'] = $this->Model_labarugi->get_pk_input($tahun);
        $data['pll_input'] = $this->Model_labarugi->get_pll_input($tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/laba_rugi/view_pendapatan', $data);
        $this->load->view('templates/footer');
    }

    public function input_ppa()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_ppa', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('tgl_ppa', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_ppa', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Pendapatan Penjualan Air';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/laba_rugi/view_upload_ppa', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_labarugi->input_ppa();
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
                    <strong>Sukses!</strong> Data input Pendapatan Penjualan Air berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/pendapatan');
        }
    }

    public function input_ppa_neraca($tahun, $total_seluruh_ppa_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');

        if ($total_seluruh_ppa_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/pendapatan');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_lr_sak_ep', $tahun);
        $this->db->where('kategori', 'Pendapatan Usaha');
        $this->db->where('akun', 'a. Pendapatan Penjualan Air');
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
                'kategori' => 'Pendapatan Usaha',
                'akun' => 'a. Pendapatan Penjualan Air',
                'nilai_lr_sak_ep' => $total_seluruh_ppa_tahun_ini,
                'nilai_lr_sak_ep_audited' => $total_seluruh_ppa_tahun_ini,
                'posisi' => 1,
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
        redirect('lap_keuangan/pendapatan');
    }

    public function input_ppna()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_ppna', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('tgl_ppna', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_ppna', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Pendapatan Penjualan Non Air';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/laba_rugi/view_upload_ppna', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_labarugi->input_ppna();
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
                    <strong>Sukses!</strong> Data input Pendapatan Penjualan Non Air berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/pendapatan');
        }
    }

    public function input_ppna_neraca($tahun, $total_seluruh_ppna_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($total_seluruh_ppna_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/pendapatan');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_lr_sak_ep', $tahun);
        $this->db->where('kategori', 'Pendapatan Usaha');
        $this->db->where('akun', 'b. Pendapatan Non Air');
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
                'kategori' => 'Pendapatan Usaha',
                'akun' => 'b. Pendapatan Non Air',
                'nilai_lr_sak_ep' => $total_seluruh_ppna_tahun_ini,
                'nilai_lr_sak_ep_audited' => $total_seluruh_ppna_tahun_ini,
                'posisi' => 2,
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
        redirect('lap_keuangan/pendapatan');
    }

    public function input_pk()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_pk', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('tgl_pk', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_pk', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Pendapatan Kemitraan';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/laba_rugi/view_upload_pk', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_labarugi->input_pk();
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
                    <strong>Sukses!</strong> Data input Pendapatan Kemitraan berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/pendapatan');
        }
    }

    public function input_pk_neraca($tahun, $total_seluruh_pk_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun_ini = date('Y');
        if ($total_seluruh_pk_tahun_ini == 0 && $tahun == $tahun_ini) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/pendapatan');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_lr_sak_ep', $tahun);
        $this->db->where('kategori', 'Pendapatan Usaha');
        $this->db->where('akun', 'c. Pendapatan Kemitraan');
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
                'kategori' => 'Pendapatan Usaha',
                'akun' => 'c. Pendapatan Kemitraan',
                'nilai_lr_sak_ep' => $total_seluruh_pk_tahun_ini,
                'nilai_lr_sak_ep_audited' => $total_seluruh_pk_tahun_ini,
                'posisi' => 3,
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
        redirect('lap_keuangan/pendapatan');
    }

    public function input_pll()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_pll', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('jenis_pll', 'Jenis', 'required|trim');
        $this->form_validation->set_rules('tgl_pll', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_pll', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Pendapatan Lain-lain';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/laba_rugi/view_upload_pll', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_labarugi->input_pll();
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
                    <strong>Sukses!</strong> Data input Pendapatan Lain-lain berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/pendapatan');
        }
    }

    public function input_pll_neraca($tahun, $total_seluruh_pll_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($total_seluruh_pll_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/pendapatan');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_lr_sak_ep', $tahun);
        $this->db->where('kategori', 'Pendapatan - Beban Lain-lain');
        $this->db->where('akun', 'Pendapatan Lain-lain');
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
                'akun' => 'Pendapatan Lain-lain',
                'nilai_lr_sak_ep' => $total_seluruh_pll_tahun_ini,
                'nilai_lr_sak_ep_audited' => $total_seluruh_pll_tahun_ini,
                'posisi' => 9,
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
        redirect('lap_keuangan/pendapatan');
    }
}
