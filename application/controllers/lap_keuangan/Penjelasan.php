<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjelasan extends CI_Controller
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

        $data['title'] = 'Penjelasan Neraca';
        $data['bank_input'] = $this->Model_lap_keuangan->get_bank_input($tahun);
        $data['kas_input'] = $this->Model_lap_keuangan->get_kas_input($tahun);
        $data['pbt_input'] = $this->Model_lap_keuangan->get_pbt_input($tahun);
        $data['pdm_input'] = $this->Model_lap_keuangan->get_pdm_input($tahun);

        // Hitung total Bank
        $total_bank_tahun_ini = 0;
        $total_bank_tahun_lalu = 0;
        foreach ($data['bank_input'] as $bank) {
            $total_bank_tahun_ini += !empty($bank->jumlah_bank_tahun_ini) ? $bank->jumlah_bank_tahun_ini : 0;
            $total_bank_tahun_lalu += !empty($bank->jumlah_bank_tahun_lalu) ? $bank->jumlah_bank_tahun_lalu : 0;
        }

        // Hitung total Kas
        $total_kas_tahun_ini = 0;
        $total_kas_tahun_lalu = 0;
        foreach ($data['kas_input'] as $kas) {
            $total_kas_tahun_ini += !empty($kas->jumlah_kas_tahun_ini) ? $kas->jumlah_kas_tahun_ini : 0;
            $total_kas_tahun_lalu += !empty($kas->jumlah_kas_tahun_lalu) ? $kas->jumlah_kas_tahun_lalu : 0;
        }

        // Total keseluruhan (Kas + Bank)
        $data['total_tahun_ini'] = $total_bank_tahun_ini + $total_kas_tahun_ini;
        $data['total_tahun_lalu'] = $total_bank_tahun_lalu + $total_kas_tahun_lalu;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/view_penjelasan', $data);
        $this->load->view('templates/footer');
    }


    public function input_bank()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('id_bank', 'Nama Bank', 'required|trim');
        $this->form_validation->set_rules('tgl_bank', 'Tanggal', 'required|trim');
        $this->form_validation->set_rules('jumlah_bank', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Bank';
            $data['bank'] = $this->Model_lap_keuangan->get_bank();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_bank', $data);
            $this->load->view('templates/footer');
        } else {
            $this->Model_lap_keuangan->input_bank();
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Data input bank baru berhasil di tambah
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/penjelasan?tahun=' . $tahun);
        }
    }

    public function input_kas()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('id_kas', 'Nama Kas', 'required|trim');
        $this->form_validation->set_rules('tgl_kas', 'Tanggal', 'required|trim');
        $this->form_validation->set_rules('jumlah_kas', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Kas';
            $data['kas'] = $this->Model_lap_keuangan->get_kas();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_kas', $data);
            $this->load->view('templates/footer');
        } else {
            $data['kas'] = $this->Model_lap_keuangan->input_kas();
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Data input kas baru berhasil di tambah
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/penjelasan?tahun=' . $tahun);
        }
    }

    public function input_deposito()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('nilai_neraca', 'Jumlah Deposito', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Deposito';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_deposito', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_lap_keuangan->input_deposito();

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
            redirect('lap_keuangan/penjelasan?tahun=' . $tahun);
        }
    }

    public function input_kas_bank($tahun, $total_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($total_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/penjelasan?tahun=' . $tahun);
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Aset Lancar');
        $this->db->where('akun', 'Kas dan Bank');
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
            redirect('lap_keuangan/penjelasan?tahun=' . $tahun);
        } else {
            // Jika belum ada, lakukan insert
            $data = [
                'tahun_neraca' => $tahun,
                'kategori' => 'Aset Lancar',
                'akun' => 'Kas dan Bank',
                'nilai_neraca' => $total_tahun_ini,
                'nilai_neraca_audited' => $total_tahun_ini,
                'posisi' => 1,
                'no_neraca' => '1.1',
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
        redirect('lap_keuangan/penjelasan?tahun=' . $tahun);
    }

    public function input_pend_blm_terima()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_pbt', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('tgl_pbt', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_pbt', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Penerimaan Belum Diterima';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_pbt', $data);
            $this->load->view('templates/footer');
        } else {
            $input_pbt = $this->Model_lap_keuangan->input_pbt();

            if ($input_pbt) {
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

            redirect('lap_keuangan/penjelasan?tahun=' . $tahun);
        }
    }


    public function input_piutang_non_usaha($tahun, $total_pnu)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($total_pnu == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/penjelasan?tahun=' . $tahun);
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Aset Lancar');
        $this->db->where('akun', 'Piutang Non Usaha');
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
                'kategori' => 'Aset Lancar',
                'akun' => 'Piutang Non Usaha',
                'nilai_neraca' => $total_pnu,
                'nilai_neraca_audited' => $total_pnu,
                'posisi' => 5,
                'no_neraca' => '1.4',
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
        redirect('lap_keuangan/penjelasan?tahun=' . $tahun);
    }

    public function input_pembayaran_dimuka()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_pdm', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('tgl_pdm', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_pdm', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Penerimaan Belum Diterima';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_pdm', $data);
            $this->load->view('templates/footer');
        } else {
            $input_pdm = $this->Model_lap_keuangan->input_pdm();

            if ($input_pdm) {
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

            redirect('lap_keuangan/penjelasan?tahun=' . $tahun);
        }
    }


    public function input_pdm($tahun, $total_pdm_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($total_pdm_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/penjelasan?tahun=' . $tahun);
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Aset Lancar');
        $this->db->where('akun', 'Pembayaran Dimuka');
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
                'kategori' => 'Aset Lancar',
                'akun' => 'Pembayaran Dimuka',
                'nilai_neraca' => $total_pdm_tahun_ini,
                'nilai_neraca_audited' => $total_pdm_tahun_ini,
                'posisi' => 8,
                'no_neraca' => '1.7',
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
        redirect('lap_keuangan/penjelasan?tahun=' . $tahun);
    }

    public function input_pajak_pnd()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('nilai_neraca', 'Jumlah Pajak Dimuka', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Deposito';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_pajak_pnd', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_lap_keuangan->input_pajak_pnd();

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
                    <strong>Sukses!</strong> Data input Pajak Dimuka berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/penjelasan?tahun=' . $tahun);
        }
    }
}
