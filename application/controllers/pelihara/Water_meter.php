<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Water_meter extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_pelihara');
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
        if ($bagian != 'Pemeliharaan' && $bagian != 'Publik' && $bagian != 'Administrator' && $bagian != 'Keuangan') {
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
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'Data Tera Meter';
        $data['title2'] = 'Data Penggantian Water Meter';
        $data['tera_meter'] = $this->Model_pelihara->get_tera_meter($tahun);
        $data['ganti_meter'] = $this->Model_pelihara->get_ganti_meter($tahun);

        if ($this->session->userdata('bagian') == 'Pemeliharaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('pelihara/view_water_meter', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('pelihara/view_water_meter', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('pelihara/view_water_meter', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('pelihara/view_water_meter', $data);
            $this->load->view('templates/footer');
        }
    }


    public function cetak_water_meter()
    {
        $tahun = $this->session->userdata('tahun_session');

        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'Data Tera Meter';
        $data['title2'] = 'Data Penggantian Water Meter';
        $data['tera_meter'] = $this->Model_pelihara->get_tera_meter($tahun);
        $data['ganti_meter'] = $this->Model_pelihara->get_ganti_meter($tahun);

        $this->pdf->setPaper('folio', 'landscape');
        $this->pdf->filename = "water_meter-{$tahun}.pdf";
        $this->pdf->generate('pelihara/cetak_water_meter_pdf', $data);
    }


    public function input_tm()
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');
        $this->form_validation->set_rules('id_bagian[]', 'Nama Bagian', 'required', [
            'required' => 'Harap pilih minimal satu %s.'
        ]);
        $this->form_validation->set_rules('tgl_tm', 'Tanggal Tera Meter', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Data Tera Meter';
            $data['bagian'] = $this->Model_pelihara->get_bagian();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('pelihara/view_input_tm', $data);
            $this->load->view('templates/footer');
        } else {
            $tgl_tm = $this->input->post('tgl_tm');
            $id_bagian = $this->input->post('id_bagian'); // Array dari checkbox
            $jumlah_tm = $this->input->post('jumlah_tm'); // Array dari input jumlah
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_tera_meter = [];

            foreach ($id_bagian as $bagian) {
                if (!empty($jumlah_tm[$bagian])) { // Pastikan jumlah ada
                    $data_tera_meter[] = [
                        'id_bagian' => $bagian,
                        'tgl_tm' => $tgl_tm,
                        'jumlah_tm' => $jumlah_tm[$bagian],
                        'created_by' => $created_by,
                        'created_at' => $created_at
                    ];
                }
            }

            if (!empty($data_tera_meter)) {
                $this->Model_pelihara->input_tm('ek_tera_meter', $data_tera_meter);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Data Tera Meter berhasil ditambahkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
            } else {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Pastikan jumlah Tera Meter diisi untuk setiap bagian yang dipilih.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
            }
            $alamat = 'pelihara/water_meter?tahun=' . $tahun;
            redirect($alamat);
            // redirect('pelihara/water_meter');
        }
    }

    public function input_gm()
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');
        $this->form_validation->set_rules('id_bagian[]', 'Nama Bagian', 'required', [
            'required' => 'Harap pilih minimal satu %s.'
        ]);
        $this->form_validation->set_rules('tgl_gm', 'Tanggal Ganti Meter', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Data Ganti Meter';
            $data['bagian'] = $this->Model_pelihara->get_bagian();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('pelihara/view_input_gm', $data);
            $this->load->view('templates/footer');
        } else {
            $tgl_gm = $this->input->post('tgl_gm');
            $id_bagian = $this->input->post('id_bagian'); // Array dari checkbox
            $jumlah_gm = $this->input->post('jumlah_gm'); // Array dari input jumlah
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_ganti_meter = [];

            foreach ($id_bagian as $bagian) {
                if (!empty($jumlah_gm[$bagian])) { // Pastikan jumlah ada
                    $data_ganti_meter[] = [
                        'id_bagian' => $bagian,
                        'tgl_gm' => $tgl_gm,
                        'jumlah_gm' => $jumlah_gm[$bagian],
                        'created_by' => $created_by,
                        'created_at' => $created_at
                    ];
                }
            }

            if (!empty($data_ganti_meter)) {
                $this->Model_pelihara->input_gm('ek_ganti_meter', $data_ganti_meter);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Data Ganti Meter berhasil ditambahkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
            } else {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Pastikan jumlah Ganti Meter diisi untuk setiap bagian yang dipilih.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
            }
            $alamat = 'pelihara/water_meter?tahun=' . $tahun;
            redirect($alamat);
            // redirect('pelihara/water_meter');
        }
    }

    public function edit_tm($id_bagian, $tgl_tm)
    {
        $data['title'] = 'Edit Data Tera Meter';
        $data['row'] = $this->Model_pelihara->getByIdTgl_tm($id_bagian, $tgl_tm);

        if (!$data['row']) {
            show_404(); // Jika data tidak ditemukan, tampilkan halaman 404
        }

        $data['id_bagian'] = $id_bagian;
        $data['tgl_tm'] = $tgl_tm;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar_pelihara');
        $this->load->view('pelihara/view_edit_tera_meter', $data);
        $this->load->view('templates/footer');
    }

    public function update_tm()
    {
        $id_bagian = $this->input->post('id_bagian');
        $tgl_tm = $this->input->post('tgl_tm');
        $field = $this->input->post('field'); // Nama kolom yang akan diubah
        $value = $this->input->post('value');

        // Cek apakah semua input ada
        if (empty($id_bagian) || empty($tgl_tm) || empty($field) || empty($value)) {
            $this->session->set_flashdata('error', 'Data tidak valid!');
            $tahun = date('Y', strtotime($tgl_tm));
            redirect('pelihara/water_meter?tahun=' . $tahun);
        }

        $data = [
            $field => $value,
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $this->session->userdata('nama_lengkap')
        ];

        if ($this->Model_pelihara->update_tm($id_bagian, $tgl_tm, $data)) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data Tera Meter berhasil diedit.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
            );
        } else {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Data Tera Meter berhasil diedit.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
            );
        }

        $tahun = date('Y', strtotime($tgl_tm));
        redirect('pelihara/water_meter?tahun=' . $tahun);
    }

    public function edit_gm($id_bagian, $tgl_gm)
    {
        $data['title'] = 'Edit Data Tera Meter';
        $data['row'] = $this->Model_pelihara->getByIdTgl_gm($id_bagian, $tgl_gm);

        if (!$data['row']) {
            show_404(); // Jika data tidak ditemukan, tampilkan halaman 404
        }

        $data['id_bagian'] = $id_bagian;
        $data['tgl_gm'] = $tgl_gm;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar_pelihara');
        $this->load->view('pelihara/view_edit_ganti_meter', $data);
        $this->load->view('templates/footer');
    }

    public function update_gm()
    {
        $id_bagian = $this->input->post('id_bagian');
        $tgl_gm = $this->input->post('tgl_gm');
        $field = $this->input->post('field'); // Nama kolom yang akan diubah
        $value = $this->input->post('value');

        // Cek apakah semua input ada
        if (empty($id_bagian) || empty($tgl_gm) || empty($field) || empty($value)) {
            $this->session->set_flashdata('error', 'Data tidak valid!');
            $tahun = date('Y', strtotime($tgl_gm));
            redirect('pelihara/water_meter?tahun=' . $tahun);
        }

        $data = [
            $field => $value,
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $this->session->userdata('nama_lengkap')
        ];

        if ($this->Model_pelihara->update_gm($id_bagian, $tgl_gm, $data)) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data Tera Meter berhasil diedit.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
            );
        } else {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Data Tera Meter berhasil diedit.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
            );
        }

        $tahun = date('Y', strtotime($tgl_gm));
        redirect('pelihara/water_meter?tahun=' . $tahun);
    }
}
