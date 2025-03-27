<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tambah_sr extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_langgan');
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
        if ($bagian != 'Langgan' && $bagian != 'Publik' && $bagian != 'Administrator' && $bagian != 'Keuangan') {
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
        $data['title'] = 'REALISASI PEMASANGAN SR BARU';
        $data['tambah_sr'] = $this->Model_langgan->get_tambah_sr($tahun);

        if ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('langganan/view_tambah_sr', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('langganan/view_tambah_sr', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('langganan/view_tambah_sr', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('langganan/view_tambah_sr', $data);
            $this->load->view('templates/footer');
        }
    }

    public function cetak_sr()
    {
        $tahun = $this->session->userdata('tahun_session');

        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'REALISASI PEMASANGAN SR BARU';
        $data['tambah_sr'] = $this->Model_langgan->get_tambah_sr($tahun);

        $this->pdf->setPaper('folio', 'landscape');
        $this->pdf->filename = "tambah_sr-{$tahun}.pdf";
        $this->pdf->generate('langgan/cetak_tambah_sr_pdf', $data);
    }


    public function input_sr()
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');

        $this->form_validation->set_rules('id_bagian[]', 'nama_bagian', 'required', [
            'required' => 'Harap pilih minimal satu %s.'
        ]);
        $this->form_validation->set_rules('tgl_sr', 'Tanggal Penambahan SR', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Data Penambahan SR';
            $data['bagian'] = $this->Model_langgan->get_bagian();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('langganan/view_input_tambah_sr', $data);
            $this->load->view('templates/footer');
        } else {
            $tgl_sr = $this->input->post('tgl_sr');
            $bulan = date('m', strtotime($tgl_sr)); // Ambil bulan dari tanggal
            $tahun_input = date('Y', strtotime($tgl_sr)); // Ambil tahun dari tanggal
            $id_bagian = $this->input->post('id_bagian'); // Array dari checkbox
            $jumlah_sr = $this->input->post('jumlah_sr'); // Array dari input jumlah
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_sr = [];
            $duplikasi_terdeteksi = false;

            foreach ($id_bagian as $bagian) {
                if (!empty($jumlah_sr[$bagian])) {
                    // **Cek apakah data dengan tgl_sr dan id_bagian sudah ada**
                    $cek_duplikasi = $this->Model_langgan->cek_duplikasi_sr($bulan, $tahun_input, $bagian);

                    if ($cek_duplikasi) {
                        $duplikasi_terdeteksi = true;
                        break;
                    }

                    $data_sr[] = [
                        'id_bagian' => $bagian,
                        'tgl_sr' => $tgl_sr,
                        'jumlah_sr' => $jumlah_sr[$bagian],
                        'created_by' => $created_by,
                        'created_at' => $created_at
                    ];
                }
            }

            if ($duplikasi_terdeteksi) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Data untuk tanggal dan bagian yang dipilih sudah ada di database.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
                );
            } elseif (!empty($data_sr)) {
                $this->Model_langgan->input_tambah_sr('ek_tambah_sr', $data_sr);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data penambahan SR berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
                );
            } else {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Pastikan jumlah penambahan SR diisi untuk setiap bagian yang dipilih.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
                );
            }

            $alamat = 'langganan/tambah_sr?tahun=' . $tahun;
            redirect($alamat);
        }
    }


    public function edit_sr($id_bagian, $tgl_sr)
    {
        $data['title'] = 'Edit Data penambahan SR';
        $data['row'] = $this->Model_langgan->getByIdTgl_sr($id_bagian, $tgl_sr);

        if (!$data['row']) {
            show_404(); // Jika data tidak ditemukan, tampilkan halaman 404
        }

        $data['id_bagian'] = $id_bagian;
        $data['tgl_sr'] = $tgl_sr;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar_pelihara');
        $this->load->view('langganan/view_edit_tambah_sr', $data);
        $this->load->view('templates/footer');
    }

    public function update_sr()
    {
        date_default_timezone_set('Asia/Jakarta');
        $id_bagian = $this->input->post('id_bagian');
        $tgl_sr = $this->input->post('tgl_sr');
        $field = $this->input->post('field'); // Nama kolom yang akan diubah
        $value = $this->input->post('value');

        // Cek apakah semua input ada
        if (empty($id_bagian) || empty($tgl_sr) || empty($field) || empty($value)) {
            $this->session->set_flashdata('error', 'Data tidak valid!');
            $tahun = date('Y', strtotime($tgl_sr));
            redirect('langganan/tambah_sr?tahun=' . $tahun);
        }

        $data = [
            $field => $value,
            'status' => 1,
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $this->session->userdata('nama_lengkap')
        ];

        if ($this->Model_langgan->update_sr($id_bagian, $tgl_sr, $data)) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data penambahan SR berhasil diedit.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
            );
        } else {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Data penambahan SR berhasil diedit.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
            );
        }

        $tahun = date('Y', strtotime($tgl_sr));
        redirect('langganan/tambah_sr?tahun=' . $tahun);
    }
}
