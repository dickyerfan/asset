<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Beban_pajak extends CI_Controller
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

        $data['title'] = 'Pajak Kini';
        $data['title2'] = 'Beban Pajak Ditangguhkan';

        $data['bpk_input'] = $this->Model_labarugi->get_bpk_input($tahun);
        $data['bpk_kurang_input'] = $this->Model_labarugi->get_bpk_kurang_input($tahun);
        $data['pendapatan_usaha'] = $this->Model_labarugi->get_pendapatan_usaha_input($tahun);
        $data['bpd_input'] = $this->Model_labarugi->get_bpd_input($tahun);


        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/laba_rugi/view_beban_pajak', $data);
        $this->load->view('templates/footer');
    }

    public function input_bpk()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_bpk', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('jenis_bpk', 'Jenis', 'required|trim');
        $this->form_validation->set_rules('tgl_bpk', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_bpk', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Beban Operasi';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/laba_rugi/view_upload_bpk', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_labarugi->input_bpk();
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
            redirect('lap_keuangan/beban_pajak');
        }
    }

    public function input_bppt_lr($tahun, $total_bppt_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');

        if ($total_bppt_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/beban_pajak');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_lr_sak_ep', $tahun);
        $this->db->where('kategori', 'Beban Pajak Penghasilan');
        $this->db->where('akun', 'Pajak Kini');
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
                'kategori' => 'Beban Pajak Penghasilan',
                'akun' => 'Pajak Kini',
                'nilai_lr_sak_ep' => $total_bppt_tahun_ini,
                'posisi' => 11,
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
        redirect('lap_keuangan/beban_pajak');
    }

    public function input_lrbsp($tahun, $total_lrbsp_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');

        if ($total_lrbsp_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/beban_pajak');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tgl_bpk', $tahun);
        $this->db->where('nama_bpk', 'Laba Rugi Sebelum Pajak');
        $query = $this->db->get('lr_bpk');

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
                'tgl_bpk' => $tahun,
                'jenis_bpk' => 'Laba Rugi Sebelum Pajak',
                'nama_bpk' => 'Laba Rugi Sebelum Pajak',
                'jumlah_bpk' => $total_lrbsp_tahun_ini,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama_lengkap')
            ];

            $this->db->insert('lr_bpk', $data);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Data berhasil disimpan!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
        }
        redirect('lap_keuangan/beban_pajak');
    }

    public function input_bpd()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_bpd', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('jenis_bpd', 'Jenis', 'required|trim');
        $this->form_validation->set_rules('tgl_bpd', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_bpd', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Beban Pengolah Air';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/laba_rugi/view_upload_bpd', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_labarugi->input_bpd();
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
            redirect('lap_keuangan/beban_pajak');
        }
    }

    public function input_bpd_lr($tahun, $total_seluruh_bpd_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun_ini = date('Y');
        if ($total_seluruh_bpd_tahun_ini == 0 && $tahun == $tahun_ini) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/beban_pajak');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_lr_sak_ep', $tahun);
        $this->db->where('kategori', 'Beban Pajak Penghasilan');
        $this->db->where('akun', 'Beban Pajak Ditangguhkan');
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
                'kategori' => 'Beban Pajak Penghasilan',
                'akun' => 'Beban Pajak Ditangguhkan',
                'nilai_lr_sak_ep' => $total_seluruh_bpd_tahun_ini,
                'posisi' => 12,
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
        redirect('lap_keuangan/beban_pajak');
    }
}
