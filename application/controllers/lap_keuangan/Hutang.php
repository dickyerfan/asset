<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hutang extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_lap_keuangan');
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

        $data['title'] = 'Hutang Non Usaha';
        $data['title2'] = 'Penerimaaan Diterima Dimuka';
        $data['title3'] = 'Uang Titipan SR';
        $data['title4'] = 'Hutang Non Usaha Lainnya';
        $data['title5'] = 'Beban Yang Masih Harus Dibayar';
        $data['title6'] = 'Utang Pajak';
        $data['title7'] = 'Kewajiban Lain-lain';
        $data['hnu_input'] = $this->Model_lap_keuangan->get_hnu_input($tahun);
        $data['pdd_input'] = $this->Model_lap_keuangan->get_pdd_input($tahun);
        $data['utsr_input'] = $this->Model_lap_keuangan->get_utsr_input($tahun);
        $data['hnu_lain_input'] = $this->Model_lap_keuangan->get_hnu_lain_input($tahun);
        $data['bymhd_input'] = $this->Model_lap_keuangan->get_bymhd_input($tahun);
        $data['up_input'] = $this->Model_lap_keuangan->get_up_input($tahun);
        $data['kll_input'] = $this->Model_lap_keuangan->get_kll_input($tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/view_hutang', $data);
        $this->load->view('templates/footer');
    }

    public function input_hutang_usaha()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('nilai_neraca', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Deposito';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_hutang_usaha', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_lap_keuangan->input_hutang_usaha();
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
                    <strong>Sukses!</strong> Data input deposito berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/hutang');
        }
    }

    public function input_hutang_non_usaha()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_hnu', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('tgl_hnu', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_hnu', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Penerimaan Belum Diterima';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_hnu', $data);
            $this->load->view('templates/footer');
        } else {
            $input_hnu = $this->Model_lap_keuangan->input_hnu();

            if ($input_hnu) {
                // Jika sukses insert
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Data berhasil ditambahkan.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>'
                );
            } else {
                // Jika gagal karena data sudah ada
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Gagal!</strong> Data dengan nama dan tahun yang sama sudah ada.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>'
                );
            }

            redirect('lap_keuangan/hutang');
        }
    }

    public function input_pdd()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_pdd', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('tgl_pdd', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_pdd', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Penerimaaan Diterima Dimuka';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_pdd', $data);
            $this->load->view('templates/footer');
        } else {
            $input_pdd = $this->Model_lap_keuangan->input_pdd();

            if ($input_pdd) {
                // Jika sukses insert
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Data berhasil ditambahkan.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>'
                );
            } else {
                // Jika gagal karena data sudah ada
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Gagal!</strong> Data dengan nama dan tahun yang sama sudah ada.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>'
                );
            }

            redirect('lap_keuangan/hutang');
        }
    }

    public function input_utsr()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_utsr', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('tgl_utsr', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_utsr', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Penerimaaan Diterima Dimuka';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_utsr', $data);
            $this->load->view('templates/footer');
        } else {
            $input_utsr = $this->Model_lap_keuangan->input_utsr();

            if ($input_utsr) {
                // Jika sukses insert
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Data berhasil ditambahkan.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>'
                );
            } else {
                // Jika gagal karena data sudah ada
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Gagal!</strong> Data dengan nama dan tahun yang sama sudah ada.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>'
                );
            }

            redirect('lap_keuangan/hutang');
        }
    }

    public function input_hnu_lain()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_hnu_lain', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('tgl_hnu_lain', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_hnu_lain', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Penerimaan Belum Diterima';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_hnu_lain', $data);
            $this->load->view('templates/footer');
        } else {
            $input_hnu_lain = $this->Model_lap_keuangan->input_hnu_lain();

            if ($input_hnu_lain) {
                // Jika sukses insert
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Data berhasil ditambahkan.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>'
                );
            } else {
                // Jika gagal karena data sudah ada
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Gagal!</strong> Data dengan nama dan tahun yang sama sudah ada.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>'
                );
            }

            redirect('lap_keuangan/hutang');
        }
    }

    public function input_hnu_neraca($tahun, $total_seluruh_hnu_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($total_seluruh_hnu_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/hutang');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Liabilitas Jangka Pendek');
        $this->db->where('akun', 'Utang Non Usaha');
        $query = $this->db->get('neraca');

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
                'tahun_neraca' => $tahun,
                'kategori' => 'Liabilitas Jangka Pendek',
                'akun' => 'Utang Non Usaha',
                'nilai_neraca' => $total_seluruh_hnu_tahun_ini,
                'posisi' => 18,
                'no_neraca' => '3.2',
                'status' => 1
            ];

            $this->db->insert('neraca', $data);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Data berhasil disimpan ke Neraca!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        }
        redirect('lap_keuangan/hutang');
    }

    public function input_bymhd()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_bymhd', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('tgl_bymhd', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_bymhd', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Penerimaan Belum Diterima';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_bymhd', $data);
            $this->load->view('templates/footer');
        } else {
            $input_bymhd = $this->Model_lap_keuangan->input_bymhd();

            if ($input_bymhd) {
                // Jika sukses insert
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Data berhasil ditambahkan.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>'
                );
            } else {
                // Jika gagal karena data sudah ada
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Gagal!</strong> Data dengan nama dan tahun yang sama sudah ada.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>'
                );
            }
            redirect('lap_keuangan/hutang');
        }
    }

    public function input_bymhd_neraca($tahun, $total_bymhd_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($total_bymhd_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/hutang');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Liabilitas Jangka Pendek');
        $this->db->where('akun', 'Biaya Yang Masih Harus Dibayar');
        $query = $this->db->get('neraca');

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
                'tahun_neraca' => $tahun,
                'kategori' => 'Liabilitas Jangka Pendek',
                'akun' => 'Biaya Yang Masih Harus Dibayar',
                'nilai_neraca' => $total_bymhd_tahun_ini,
                'posisi' => 19,
                'no_neraca' => '3.3',
                'status' => 1
            ];

            $this->db->insert('neraca', $data);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Data berhasil disimpan ke Neraca!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        }
        redirect('lap_keuangan/hutang');
    }

    public function input_up()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_up', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('tgl_up', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_up', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Penerimaan Belum Diterima';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_up', $data);
            $this->load->view('templates/footer');
        } else {
            $input_up = $this->Model_lap_keuangan->input_up();

            if ($input_up) {
                // Jika sukses insert
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Data berhasil ditambahkan.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>'
                );
            } else {
                // Jika gagal karena data sudah ada
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Gagal!</strong> Data dengan nama dan tahun yang sama sudah ada.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>'
                );
            }
            redirect('lap_keuangan/hutang');
        }
    }

    public function input_up_neraca($tahun, $total_up_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($total_up_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/hutang');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Liabilitas Jangka Pendek');
        $this->db->where('akun', 'Utang Pajak');
        $query = $this->db->get('neraca');

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
                'tahun_neraca' => $tahun,
                'kategori' => 'Liabilitas Jangka Pendek',
                'akun' => 'Utang Pajak',
                'nilai_neraca' => $total_up_tahun_ini,
                'posisi' => 20,
                'no_neraca' => '3.4',
                'status' => 1
            ];

            $this->db->insert('neraca', $data);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Data berhasil disimpan ke Neraca!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        }
        redirect('lap_keuangan/hutang');
    }

    public function input_lipkd()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('nilai_neraca', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Liabilitas Imbalan Pasca Kerja Dapenma';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_lipkd', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_lap_keuangan->input_lipkd();
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
                    <strong>Sukses!</strong> Data input deposito berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/hutang');
        }
    }

    public function input_lipk()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('nilai_neraca', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Liabilitas Imbalan Pasca Kerja';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_lipk', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_lap_keuangan->input_lipk();
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
                    <strong>Sukses!</strong> Data input deposito berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/hutang');
        }
    }

    public function input_ujpl()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('nilai_neraca', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Utang Jangka Pendek Lainnya';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_ujpl', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_lap_keuangan->input_ujpl();
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
                    <strong>Sukses!</strong> Data input deposito berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/hutang');
        }
    }

    public function input_lipkdpj()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('nilai_neraca', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Liabilitas Imbalan Pasca Kerja Dapenma (pj)';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_lipkdpj', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_lap_keuangan->input_lipkdpj();
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
                    <strong>Sukses!</strong> Data input deposito berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/hutang');
        }
    }

    public function input_lipkpj()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('nilai_neraca', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Liabilitas Imbalan Pasca Kerja (pj)';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_lipkpj', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_lap_keuangan->input_lipkpj();
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
                    <strong>Sukses!</strong> Data input deposito berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/hutang');
        }
    }

    public function input_lpt()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('nilai_neraca', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Liabilitas pajak Tangguhan';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_lpt', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_lap_keuangan->input_lpt();
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
                    <strong>Sukses!</strong> Data input deposito berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/hutang');
        }
    }

    public function input_kll()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_kll', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('tgl_kll', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_kll', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Kewajiban Lain-lain';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_kll', $data);
            $this->load->view('templates/footer');
        } else {
            $input_up = $this->Model_lap_keuangan->input_kll();

            if ($input_up) {
                // Jika sukses insert
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Data berhasil ditambahkan.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>'
                );
            } else {
                // Jika gagal karena data sudah ada
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Gagal!</strong> Data dengan nama dan tahun yang sama sudah ada.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>'
                );
            }
            redirect('lap_keuangan/hutang');
        }
    }

    public function input_kll_neraca($tahun, $total_kll_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($total_kll_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/hutang');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Liabilitas Jangka Panjang');
        $this->db->where('akun', 'Kewajiban Lain-lain');
        $query = $this->db->get('neraca');

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
                'tahun_neraca' => $tahun,
                'kategori' => 'Liabilitas Jangka Panjang',
                'akun' => 'Kewajiban Lain-lain',
                'nilai_neraca' => $total_kll_tahun_ini,
                'posisi' => 27,
                'no_neraca' => '4.3.1',
                'status' => 1
            ];

            $this->db->insert('neraca', $data);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Data berhasil disimpan ke Neraca!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        }
        redirect('lap_keuangan/hutang');
    }
}
