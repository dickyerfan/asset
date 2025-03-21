<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jam_ops extends CI_Controller
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
        $data['title'] = 'DATA JAM OPERASIONAL';
        $data['jam_ops'] = $this->Model_pelihara->get_jam_ops($tahun);

        if ($this->session->userdata('bagian') == 'Pemeliharaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('pelihara/view_jam_ops', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('pelihara/view_jam_ops', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('pelihara/view_jam_ops', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('pelihara/view_jam_ops', $data);
            $this->load->view('templates/footer');
        }
    }

    public function cetak_jam_ops()
    {
        $tahun = $this->session->userdata('tahun_session');

        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'DATA JAM OPERASIONAL';
        $data['jam_ops'] = $this->Model_pelihara->get_jam_ops($tahun);

        $this->pdf->setPaper('folio', 'landscape');
        $this->pdf->filename = "jam_ops-{$tahun}.pdf";
        $this->pdf->generate('pelihara/cetak_jam_ops_pdf', $data);
    }

    // public function input_jam_ops()
    // {
    //     $tahun = $this->session->userdata('tahun_session');
    //     date_default_timezone_set('Asia/Jakarta');
    //     $this->form_validation->set_rules('id_sb_mag[]', 'Nama Sumber', 'required', [
    //         'required' => 'Harap pilih minimal satu %s.'
    //     ]);
    //     $this->form_validation->set_rules('tgl_jam_ops', 'Tanggal Jam Ops', 'required|trim');
    //     $this->form_validation->set_message('required', '%s masih kosong');

    //     if ($this->form_validation->run() == false) {
    //         $data['title'] = 'Input Data Jam Operasional';
    //         $data['sb_mag'] = $this->Model_pelihara->get_sb_mag();
    //         $this->load->view('templates/header', $data);
    //         $this->load->view('templates/navbar');
    //         $this->load->view('templates/sidebar_pelihara');
    //         $this->load->view('pelihara/view_input_jam_ops', $data);
    //         $this->load->view('templates/footer');
    //     } else {
    //         $tgl_jam_ops = $this->input->post('tgl_jam_ops');
    //         $id_sb_mag = $this->input->post('id_sb_mag'); // Array dari checkbox
    //         $jumlah_jam_ops = $this->input->post('jumlah_jam_ops'); // Array dari input jumlah
    //         $created_by = $this->session->userdata('nama_lengkap');
    //         $created_at = date('Y-m-d H:i:s');

    //         $data_jam_ops = [];

    //         foreach ($id_sb_mag as $sb_mag) {
    //             if (!empty($jumlah_jam_ops[$sb_mag])) { // Pastikan jumlah ada
    //                 $data_jam_ops[] = [
    //                     'id_sb_mag' => $sb_mag,
    //                     'tgl_jam_ops' => $tgl_jam_ops,
    //                     'jumlah_jam_ops' => $jumlah_jam_ops[$sb_mag],
    //                     'created_by' => $created_by,
    //                     'created_at' => $created_at
    //                 ];
    //             }
    //         }

    //         if (!empty($data_jam_ops)) {
    //             $this->Model_pelihara->input_jam_ops('ek_jam_ops', $data_jam_ops);
    //             $this->session->set_flashdata(
    //                 'info',
    //                 '<div class="alert alert-primary alert-dismissible fade show" role="alert">
    //                     <strong>Sukses!</strong> Data Jam Operasional berhasil ditambahkan.
    //                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    //                 </div>'
    //             );
    //         } else {
    //             $this->session->set_flashdata(
    //                 'info',
    //                 '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    //                     <strong>Gagal!</strong> Pastikan jumlah jam operasional diisi untuk setiap bagian yang dipilih.
    //                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    //                 </div>'
    //             );
    //         }
    //         $alamat = 'pelihara/jam_ops?tahun=' . $tahun;
    //         redirect($alamat);
    //         // redirect('pelihara/jam_ops');
    //     }
    // }

    public function input_jam_ops()
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');
        $this->form_validation->set_rules('id_sb_mag[]', 'Nama Sumber', 'required', [
            'required' => 'Harap pilih minimal satu %s.'
        ]);
        $this->form_validation->set_rules('tgl_jam_ops', 'Tanggal Jam Ops', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Data Jam Operasional';
            $data['sb_mag'] = $this->Model_pelihara->get_sb_mag();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('pelihara/view_input_jam_ops', $data);
            $this->load->view('templates/footer');
        } else {
            $tgl_jam_ops = $this->input->post('tgl_jam_ops');
            $id_sb_mag = $this->input->post('id_sb_mag'); // Array dari checkbox
            $jumlah_jam_ops = $this->input->post('jumlah_jam_ops'); // Array dari input jumlah
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_jam_ops = [];
            $duplikasi_terdeteksi = false;

            foreach ($id_sb_mag as $sb_mag) {
                if (!empty($jumlah_jam_ops[$sb_mag])) {
                    // **Cek apakah data dengan tgl_jam_ops dan id_sb_mag sudah ada**
                    $cek_duplikasi = $this->Model_pelihara->cek_duplikasi_jam_ops($tgl_jam_ops, $sb_mag);

                    if ($cek_duplikasi) {
                        $duplikasi_terdeteksi = true;
                        break;
                    }

                    $data_jam_ops[] = [
                        'id_sb_mag' => $sb_mag,
                        'tgl_jam_ops' => $tgl_jam_ops,
                        'jumlah_jam_ops' => $jumlah_jam_ops[$sb_mag],
                        'created_by' => $created_by,
                        'created_at' => $created_at
                    ];
                }
            }

            if ($duplikasi_terdeteksi) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Data untuk tanggal dan sumber yang dipilih sudah ada di database.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
                );
            } elseif (!empty($data_jam_ops)) {
                $this->Model_pelihara->input_jam_ops('ek_jam_ops', $data_jam_ops);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data Jam Operasional berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
                );
            } else {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Pastikan jumlah jam operasional diisi untuk setiap bagian yang dipilih.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
                );
            }

            $alamat = 'pelihara/jam_ops?tahun=' . $tahun;
            redirect($alamat);
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
}
