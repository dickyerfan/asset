<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Asset_tetap extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_penyusutan');
        $this->load->model('Model_penyusutan_bangunan');
        $this->load->model('Model_penyusutan_sumber');
        $this->load->model('Model_penyusutan_pompa');
        $this->load->model('Model_penyusutan_olah_air');
        $this->load->model('Model_penyusutan_trans_dist');
        $this->load->model('Model_penyusutan_peralatan');
        $this->load->model('Model_penyusutan_kendaraan');
        $this->load->model('Model_penyusutan_inventaris');
        $this->load->model('Model_lap_keuangan');
        $this->load->model('Model_amortisasi');
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

        $data['title'] = 'Perhitungan Asset Tetap';
        $tanah = $this->Model_penyusutan->get_tanah($tahun);
        $data['total_tanah'] = $tanah['total_tanah'];

        $tanah_amdk = $this->Model_penyusutan->get_tanah_amdk($tahun);
        $data['total_tanah_amdk'] = $tanah_amdk['total_tanah_amdk'];

        $tanah_non_amdk = $this->Model_penyusutan->get_tanah_non_amdk($tahun);
        $data['total_tanah_non_amdk'] = $tanah_non_amdk['total_tanah_non_amdk'];

        $bangunan = $this->Model_penyusutan_bangunan->get_bangunan($tahun);
        $data['total_bangunan'] = $bangunan['total_bangunan'];

        $bangunan_amdk = $this->Model_penyusutan_bangunan->get_bangunan_amdk($tahun);
        $data['total_bangunan_amdk'] = $bangunan_amdk['total_bangunan_amdk'];

        $bangunan_non_amdk = $this->Model_penyusutan_bangunan->get_bangunan_non_amdk($tahun);
        $data['total_bangunan_non_amdk'] = $bangunan_non_amdk['total_bangunan_non_amdk'];

        $sumber = $this->Model_penyusutan_sumber->get_sumber($tahun);
        $data['total_sumber'] = $sumber['total_sumber'];

        $sumber_amdk = $this->Model_penyusutan_sumber->get_sumber_amdk($tahun);
        $data['total_sumber_amdk'] = $sumber_amdk['total_sumber_amdk'];

        $sumber_non_amdk = $this->Model_penyusutan_sumber->get_sumber_non_amdk($tahun);
        $data['total_sumber_non_amdk'] = $sumber_non_amdk['total_sumber_non_amdk'];

        $pompa = $this->Model_penyusutan_pompa->get_pompa($tahun);
        $data['total_pompa'] = $pompa['total_pompa'];

        $pompa_amdk = $this->Model_penyusutan_pompa->get_pompa_amdk($tahun);
        $data['total_pompa_amdk'] = $pompa_amdk['total_pompa_amdk'];

        $pompa_non_amdk = $this->Model_penyusutan_pompa->get_pompa_non_amdk($tahun);
        $data['total_pompa_non_amdk'] = $pompa_non_amdk['total_pompa_non_amdk'];

        $olah_air = $this->Model_penyusutan_olah_air->get_olah_air($tahun);
        $data['total_olah_air'] = $olah_air['total_olah_air'];

        $olah_air_amdk = $this->Model_penyusutan_olah_air->get_olah_air_amdk($tahun);
        $data['total_olah_air_amdk'] = $olah_air_amdk['total_olah_air_amdk'];

        $olah_air_non_amdk = $this->Model_penyusutan_olah_air->get_olah_air_non_amdk($tahun);
        $data['total_olah_air_non_amdk'] = $olah_air_non_amdk['total_olah_air_non_amdk'];

        $trans_dist = $this->Model_penyusutan_trans_dist->get_trans_dist($tahun);
        $data['total_trans_dist'] = $trans_dist['total_trans_dist'];

        $trans_dist_amdk = $this->Model_penyusutan_trans_dist->get_trans_dist_amdk($tahun);
        $data['total_trans_dist_amdk'] = $trans_dist_amdk['total_trans_dist_amdk'];

        $trans_dist_non_amdk = $this->Model_penyusutan_trans_dist->get_trans_dist_non_amdk($tahun);
        $data['total_trans_dist_non_amdk'] = $trans_dist_non_amdk['total_trans_dist_non_amdk'];

        $peralatan = $this->Model_penyusutan_peralatan->get_peralatan($tahun);
        $data['total_peralatan'] = $peralatan['total_peralatan'];

        $peralatan_amdk = $this->Model_penyusutan_peralatan->get_peralatan_amdk($tahun);
        $data['total_peralatan_amdk'] = $peralatan_amdk['total_peralatan_amdk'];

        $peralatan_non_amdk = $this->Model_penyusutan_peralatan->get_peralatan_non_amdk($tahun);
        $data['total_peralatan_non_amdk'] = $peralatan_non_amdk['total_peralatan_non_amdk'];

        $kendaraan = $this->Model_penyusutan_kendaraan->get_kendaraan($tahun);
        $data['total_kendaraan'] = $kendaraan['total_kendaraan'];

        $kendaraan_amdk = $this->Model_penyusutan_kendaraan->get_kendaraan_amdk($tahun);
        $data['total_kendaraan_amdk'] = $kendaraan_amdk['total_kendaraan_amdk'];

        $kendaraan_non_amdk = $this->Model_penyusutan_kendaraan->get_kendaraan_non_amdk($tahun);
        $data['total_kendaraan_non_amdk'] = $kendaraan_non_amdk['total_kendaraan_non_amdk'];

        $inventaris = $this->Model_penyusutan_inventaris->get_inventaris($tahun);
        $data['total_inventaris'] = $inventaris['total_inventaris'];

        $inventaris_amdk = $this->Model_penyusutan_inventaris->get_inventaris_amdk($tahun);
        $data['total_inventaris_amdk'] = $inventaris_amdk['total_inventaris_amdk'];

        $inventaris_non_amdk = $this->Model_penyusutan_inventaris->get_inventaris_non_amdk($tahun);
        $data['total_inventaris_non_amdk'] = $inventaris_non_amdk['total_inventaris_non_amdk'];

        $totals = $this->Model_penyusutan->get_all($tahun);
        $data['totals'] = $totals['totals'];

        $data['title2'] = 'Perhitungan Asset Tetap Dalam Penyelesaian';
        $data['atdp_input'] = $this->Model_lap_keuangan->get_atdp_input($tahun);

        $data['title3'] = 'Daftar Asset Tidak Berwujud';
        $penyusutan_data = $this->Model_amortisasi->get_amortisasi($tahun);
        $data['susut'] = $penyusutan_data['results'];
        $data['total_amortisasi'] = $penyusutan_data['total_amortisasi'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/view_asset_tetap', $data);
        $this->load->view('templates/footer');
    }

    public function input_harga_perolehan($tahun, $total_harga_perolehan)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($total_harga_perolehan == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                  </div>'
            );
            redirect('lap_keuangan/asset_tetap');
            return;
        }

        // Cek apakah data sudah ada di tabel neraca
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Aset Tidak Lancar');
        $this->db->where('akun', 'Aset Tetap');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
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
                'kategori' => 'Aset Tidak Lancar',
                'akun' => 'Aset Tetap',
                'nilai_neraca' => $total_harga_perolehan,
                'posisi' => 10,
                'no_neraca' => '2.1',
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
        redirect('lap_keuangan/asset_tetap');
    }

    public function input_akm_thn_ini($tahun, $total_akm_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($total_akm_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                  </div>'
            );
            redirect('lap_keuangan/asset_tetap');
            return;
        }

        // Cek apakah data sudah ada di tabel neraca
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Aset Tidak Lancar');
        $this->db->where('akun', 'Akm Depresiasi Aset Tetap');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
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
                'kategori' => 'Aset Tidak Lancar',
                'akun' => 'Akm Depresiasi Aset Tetap',
                'nilai_neraca' => $total_akm_tahun_ini * -1,
                'posisi' => 11,
                'no_neraca' => '2.2',
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
        redirect('lap_keuangan/asset_tetap');
    }

    public function input_atd()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('nilai_neraca', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Asset Tetap Dikerjasamakan';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_atd', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_lap_keuangan->input_atd();
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
            redirect('lap_keuangan/asset_tetap');
        }
    }

    public function input_aaatb()
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
            $this->load->view('lap_keuangan/view_upload_aaatb', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_lap_keuangan->input_aaatb();
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
            redirect('lap_keuangan/asset_tetap');
        }
    }

    public function input_apt()
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
            $this->load->view('lap_keuangan/view_upload_apt', $data);
            $this->load->view('templates/footer');
        } else {
            $insert = $this->Model_lap_keuangan->input_apt();
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
            redirect('lap_keuangan/asset_tetap');
        }
    }

    public function input_atdp()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('nama_atdp', 'Nama / Uraian', 'required|trim');
        $this->form_validation->set_rules('tgl_atdp', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('jumlah_atdp', 'Jumlah', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Penerimaan Belum Diterima';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_atdp', $data);
            $this->load->view('templates/footer');
        } else {
            $input_atdp = $this->Model_lap_keuangan->input_atdp();

            if ($input_atdp) {
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

            redirect('lap_keuangan/asset_tetap');
        }
    }

    public function input_atdp_neraca($tahun, $total_atdp)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($total_atdp == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                  </div>'
            );
            redirect('lap_keuangan/asset_tetap');
            return;
        }

        // Cek apakah data sudah ada di tabel neraca
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Aset Tidak Lancar');
        $this->db->where('akun', 'Aset Tetap Dalam Penyelesaian');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
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
                'kategori' => 'Aset Tidak Lancar',
                'akun' => 'Aset Tetap Dalam Penyelesaian',
                'nilai_neraca' => $total_atdp,
                'posisi' => 13,
                'no_neraca' => '2.4',
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
        redirect('lap_keuangan/asset_tetap');
    }

    public function input_atb($tahun, $total_atb)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($total_atb == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                  </div>'
            );
            redirect('lap_keuangan/asset_tetap');
            return;
        }

        // Cek apakah data sudah ada di tabel neraca
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Aset Tidak Lancar');
        $this->db->where('akun', 'Aset Tidak Berwujud');
        $query = $this->db->get('neraca');

        if ($query->num_rows() > 0) {
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
                'kategori' => 'Aset Tidak Lancar',
                'akun' => 'Aset Tidak Berwujud',
                'nilai_neraca' => $total_atb * -1,
                'posisi' => 14,
                'no_neraca' => '2.5',
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
        redirect('lap_keuangan/asset_tetap');
    }
}
