<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ekuitas extends CI_Controller
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

        $data['title'] = 'Penyertaan Pemda Yang Dipisahkan';
        $data['title2'] = 'Penyertaan Pemerintah Yang Belum Ditetapkan Statusnya';
        $data['title3'] = 'Modal Hibah';
        $data['title4'] = 'Cadangan Umum';
        $data['title5'] = 'Cadangan Bertujuan';
        $data['title6'] = 'Pengukuran Kembali Imbalan Paska Kerja';
        $data['title7'] = 'Akm Kerugian Tahun Lalu';
        $data['title8'] = 'Laba Rugi Tahun Berjalan';
        $data['ppyd_input'] = $this->Model_lap_keuangan->get_ppyd_input($tahun);
        $data['ppybds_input'] = $this->Model_lap_keuangan->get_ppybds_input($tahun);
        $data['mh_input'] = $this->Model_lap_keuangan->get_mh_input($tahun);
        $data['cu_input'] = $this->Model_lap_keuangan->get_cu_input($tahun);
        $data['aktl_input'] = $this->Model_lap_keuangan->get_aktl_input($tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/view_ekuitas', $data);
        $this->load->view('templates/footer');
    }

    public function input_ppyd()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_ppyd', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('tgl_ppyd', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_ppyd', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Penerimaan Belum Diterima';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_ppyd', $data);
            $this->load->view('templates/footer');
        } else {
            $input_ppyd = $this->Model_lap_keuangan->input_ppyd();

            if ($input_ppyd) {
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

            redirect('lap_keuangan/ekuitas');
        }
    }

    public function input_ppyd_neraca($tahun, $total_seluruh_ppyd_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($total_seluruh_ppyd_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/ekuitas');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Ekuitas');
        $this->db->where('akun', 'Penyertaan Pemda Yang Dipisahkan');
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
                'kategori' => 'Ekuitas',
                'akun' => 'Penyertaan Pemda Yang Dipisahkan',
                'nilai_neraca' => $total_seluruh_ppyd_tahun_ini,
                'posisi' => 28,
                'no_neraca' => '5.1',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama_lengkap')
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
        redirect('lap_keuangan/ekuitas');
    }

    public function input_ppybds()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_ppybds', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('tgl_ppybds', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_ppybds', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Penerimaan Belum Diterima';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_ppybds', $data);
            $this->load->view('templates/footer');
        } else {
            $input_ppybds = $this->Model_lap_keuangan->input_ppybds();

            if ($input_ppybds) {
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

            redirect('lap_keuangan/ekuitas');
        }
    }

    public function input_ppybds_neraca($tahun, $total_seluruh_ppybds_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($total_seluruh_ppybds_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/ekuitas');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Ekuitas');
        $this->db->where('akun', 'Penyertaan Pemerintah Yang Belum Ditetapkan Status');
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
                'kategori' => 'Ekuitas',
                'akun' => 'Penyertaan Pemerintah Yang Belum Ditetapkan Status',
                'nilai_neraca' => $total_seluruh_ppybds_tahun_ini,
                'posisi' => 29,
                'no_neraca' => '5.2',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama_lengkap')
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
        redirect('lap_keuangan/ekuitas');
    }

    public function input_mh()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_mh', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('tgl_mh', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_mh', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Penerimaan Belum Diterima';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_mh', $data);
            $this->load->view('templates/footer');
        } else {
            $input_mh = $this->Model_lap_keuangan->input_mh();

            if ($input_mh) {
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

            redirect('lap_keuangan/ekuitas');
        }
    }

    public function input_mh_neraca($tahun, $total_seluruh_mh_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($total_seluruh_mh_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/ekuitas');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Ekuitas');
        $this->db->where('akun', 'Modal Hibah');
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
                'kategori' => 'Ekuitas',
                'akun' => 'Modal Hibah',
                'nilai_neraca' => $total_seluruh_mh_tahun_ini,
                'posisi' => 30,
                'no_neraca' => '5.2.1',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama_lengkap')
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
        redirect('lap_keuangan/ekuitas');
    }

    public function input_cu()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_cu', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('tgl_cu', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_cu', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Penerimaan Belum Diterima';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_cu', $data);
            $this->load->view('templates/footer');
        } else {
            $input_cu = $this->Model_lap_keuangan->input_cu();

            if ($input_cu) {
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

            redirect('lap_keuangan/ekuitas');
        }
    }

    public function input_cu_neraca($tahun, $total_seluruh_cu_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($total_seluruh_cu_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/ekuitas');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Ekuitas');
        $this->db->where('akun', 'Cadangan Umum');
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
                'kategori' => 'Ekuitas',
                'akun' => 'Cadangan Umum',
                'nilai_neraca' => $total_seluruh_cu_tahun_ini,
                'posisi' => 31,
                'no_neraca' => '5.3',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama_lengkap')
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
        redirect('lap_keuangan/ekuitas');
    }

    public function input_cb()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('nilai_neraca', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Kewajiban Lain-lain';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_cb', $data);
            $this->load->view('templates/footer');
        } else {
            $input_cb = $this->Model_lap_keuangan->input_cb();

            if ($input_cb) {
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
            redirect('lap_keuangan/ekuitas');
        }
    }

    public function input_pkipk()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('nilai_neraca', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Kewajiban Lain-lain';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_pkipk', $data);
            $this->load->view('templates/footer');
        } else {
            $input_pkipk = $this->Model_lap_keuangan->input_pkipk();

            if ($input_pkipk) {
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
            redirect('lap_keuangan/ekuitas');
        }
    }

    public function input_aktl()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_aktl', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('tgl_aktl', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_aktl', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Penerimaan Belum Diterima';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_aktl', $data);
            $this->load->view('templates/footer');
        } else {
            $input_aktl = $this->Model_lap_keuangan->input_aktl();

            if ($input_aktl) {
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

            redirect('lap_keuangan/ekuitas');
        }
    }

    public function input_aktl_neraca($tahun, $total_seluruh_aktl_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($total_seluruh_aktl_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/ekuitas');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Ekuitas');
        $this->db->where('akun', 'Akm Kerugian Tahun Lalu');
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
                'kategori' => 'Ekuitas',
                'akun' => 'Akm Kerugian Tahun Lalu',
                'nilai_neraca' => $total_seluruh_aktl_tahun_ini * -1,
                'posisi' => 34,
                'no_neraca' => '5.4.2',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama_lengkap')
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
        redirect('lap_keuangan/ekuitas');
    }
}
