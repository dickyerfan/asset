<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penghasilan_komp_lain extends CI_Controller
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

        $data['title'] = 'Surplus Revaluasi Tanah/Aset Tidak Lancar';
        $data['title2'] = 'Pengukuran Kembali Atas Program Imbalan Pasti';
        $data['title3'] = 'Beban Pajak Penghasilan Terkait';
        $data['title4'] = 'Penghasilan Komprehensif Lain Tahun Berjalan';

        $data['srt_input'] = $this->Model_labarugi->get_srt_input($tahun);
        $data['pkapip_input'] = $this->Model_labarugi->get_pkapip_input($tahun);
        $data['bppt_input'] = $this->Model_labarugi->get_bppt_input($tahun);
        $data['pkltb_input'] = $this->Model_labarugi->get_pkltb_input($tahun);


        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/laba_rugi/view_penghasilan_komp_lain', $data);
        $this->load->view('templates/footer');
    }

    public function input_srt()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_srt', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('jenis_srt', 'Jenis', 'required|trim');
        $this->form_validation->set_rules('tgl_srt', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_srt', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Surplus Revaluasi Tanah/Aset Tidak Lancar';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/laba_rugi/view_upload_srt', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_labarugi->input_srt();
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
                    <strong>Sukses!</strong> Data input Surplus Revaluasi Tanah/Aset Tidak Lancar baru berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/penghasilan_komp_lain');
        }
    }

    public function input_srt_lr($tahun, $total_seluruh_srt_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun_ini = date('Y');
        if ($total_seluruh_srt_tahun_ini == 0 && $tahun == $tahun_ini) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/penghasilan_komp_lain');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_lr_sak_ep', $tahun);
        $this->db->where('kategori', '(Kerugian) Penghasilan Komprehensip Lain');
        $this->db->where('akun', 'Surplus Revaluasi Tanah/Aset Tidak Lancar');
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
                'kategori' => '(Kerugian) Penghasilan Komprehensip Lain',
                'akun' => 'Surplus Revaluasi Tanah/Aset Tidak Lancar',
                'nilai_lr_sak_ep' => $total_seluruh_srt_tahun_ini,
                'nilai_lr_sak_ep_audited' => $total_seluruh_srt_tahun_ini,
                'posisi' => 13,
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
        redirect('lap_keuangan/penghasilan_komp_lain');
    }

    public function input_pkapip()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_pkapip', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('jenis_pkapip', 'Jenis', 'required|trim');
        $this->form_validation->set_rules('tgl_pkapip', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_pkapip', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Pengukuran Kembali Atas Program Imbalan Pasti';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/laba_rugi/view_upload_pkapip', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_labarugi->input_pkapip();
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
                    <strong>Sukses!</strong> Data input Pengukuran Kembali Atas Program Imbalan Pasti baru berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/penghasilan_komp_lain');
        }
    }

    public function input_pkapip_lr($tahun, $total_seluruh_pkapip_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun_ini = date('Y');
        if ($total_seluruh_pkapip_tahun_ini == 0 && $tahun == $tahun_ini) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/penghasilan_komp_lain');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_lr_sak_ep', $tahun);
        $this->db->where('kategori', '(Kerugian) Penghasilan Komprehensip Lain');
        $this->db->where('akun', 'Pengukuran Kembali Atas Program Imbalan Pasti');
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
                'kategori' => '(Kerugian) Penghasilan Komprehensip Lain',
                'akun' => 'Pengukuran Kembali Atas Program Imbalan Pasti',
                'nilai_lr_sak_ep' => $total_seluruh_pkapip_tahun_ini,
                'nilai_lr_sak_ep_audited' => $total_seluruh_pkapip_tahun_ini,
                'posisi' => 14,
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
        redirect('lap_keuangan/penghasilan_komp_lain');
    }

    public function input_bppt()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_bppt', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('jenis_bppt', 'Jenis', 'required|trim');
        $this->form_validation->set_rules('tgl_bppt', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_bppt', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Beban Pengolah Air';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/laba_rugi/view_upload_bppt', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_labarugi->input_bppt();
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
                    <strong>Sukses!</strong> Data input Beban Pajak Penghasilan Terkait baru berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/penghasilan_komp_lain');
        }
    }

    public function input_bppt_lr($tahun, $total_seluruh_bppt_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun_ini = date('Y');
        if ($total_seluruh_bppt_tahun_ini == 0 && $tahun == $tahun_ini) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/penghasilan_komp_lain');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_lr_sak_ep', $tahun);
        $this->db->where('kategori', '(Kerugian) Penghasilan Komprehensip Lain');
        $this->db->where('akun', 'Beban Pajak Penghasilan Terkait');
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
                'kategori' => '(Kerugian) Penghasilan Komprehensip Lain',
                'akun' => 'Beban Pajak Penghasilan Terkait',
                'nilai_lr_sak_ep' => $total_seluruh_bppt_tahun_ini,
                'nilai_lr_sak_ep_audited' => $total_seluruh_bppt_tahun_ini,
                'posisi' => 15,
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
        redirect('lap_keuangan/penghasilan_komp_lain');
    }

    public function input_pkltb()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_pkltb', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('jenis_pkltb', 'Jenis', 'required|trim');
        $this->form_validation->set_rules('tgl_pkltb', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_pkltb', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Beban Pengolah Air';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/laba_rugi/view_upload_pkltb', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_labarugi->input_pkltb();
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
                    <strong>Sukses!</strong> Data input Penghasilan Komprehensif Lain Tahun Berjalan baru berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>'
                );
            }
            redirect('lap_keuangan/penghasilan_komp_lain');
        }
    }

    public function input_pkltb_lr($tahun, $total_seluruh_pkltb_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        $tahun_ini = date('Y');
        if ($total_seluruh_pkltb_tahun_ini == 0 && $tahun == $tahun_ini) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/penghasilan_komp_lain');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_lr_sak_ep', $tahun);
        $this->db->where('kategori', '(Kerugian) Penghasilan Komprehensip Lain');
        $this->db->where('akun', 'Penghasilan Komprehensif Lain Tahun Berjalan');
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
                'kategori' => '(Kerugian) Penghasilan Komprehensip Lain',
                'akun' => 'Penghasilan Komprehensif Lain Tahun Berjalan',
                'nilai_lr_sak_ep' => $total_seluruh_pkltb_tahun_ini,
                'nilai_lr_sak_ep_audited' => $total_seluruh_pkltb_tahun_ini,
                'posisi' => 16,
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
        redirect('lap_keuangan/penghasilan_komp_lain');
    }
}
