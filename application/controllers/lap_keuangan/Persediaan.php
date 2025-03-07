<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Persediaan extends CI_Controller
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

        $data['title'] = 'PERSEDIAAN';
        $data['persediaan'] = $this->Model_lap_keuangan->get_persediaan($tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/view_persediaan', $data);
        $this->load->view('templates/footer');
    }

    public function persediaan_cetak()
    {
        $tahun = $this->session->userdata('tahun');
        $tahun = substr($tahun, 0, 4);

        if (empty($tahun)) {
            $tahun = date('Y-m-d');
            $bulan = date('m');
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun', $tahun);
        }

        $data['tahun_lap'] = $tahun;
        $data['tahun_lalu'] = $tahun - 1;

        $data['title'] = 'FORM INPUT PERSEDIAAN';
        $data['persediaan'] = $this->Model_lap_keuangan->get_persediaan($tahun);

        $this->pdf->setPaper('folio', 'portrait');
        $this->pdf->filename = "persediaan-{$tahun}.pdf";
        $this->pdf->generate('cetakan_lap_keuangan/persediaan_pdf', $data);
    }

    public function input_persediaan()
    {
        $tanggal = $this->session->userdata('tanggal');
        $this->form_validation->set_rules('tahun_persediaan', 'Tahun Persediaan', 'required|trim');
        $this->form_validation->set_rules('nama_persediaan', 'Nama Persediaan', 'required|trim');
        $this->form_validation->set_rules('harga_perolehan', 'Harga Perolehan', 'required|trim|numeric');
        $this->form_validation->set_rules('nilai_penurunan', 'Nilai Penurunan', 'required|trim|numeric');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Penyisihan Piutang';
            $data['kel_tarif'] = $this->Model_lap_keuangan->get_kel_tarif();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('lap_keuangan/view_upload_persediaan', $data);
            $this->load->view('templates/footer');
        } else {
            $persediaan = $this->Model_lap_keuangan->input_persediaan();
            if ($persediaan) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Data Persediaan berhasil ditambahkan.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>'
                );
            } else {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Gagal!</strong> Data dengan nama dan tahun yang sama sudah ada.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>'
                );
            }
            redirect('lap_keuangan/persediaan');
        }
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
            redirect('lap_keuangan/persediaan');
            return;
        }

        // Cek apakah data sudah ada di tabel neraca
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Aset Lancar');
        $this->db->where('akun', 'Persediaan');
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
                'kategori' => 'Aset Lancar',
                'akun' => 'Persediaan',
                'nilai_neraca' => $total_harga_perolehan,
                'nilai_neraca_audited' => $total_harga_perolehan,
                'posisi' => 6,
                'no_neraca' => '1.5',
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
        redirect('lap_keuangan/persediaan');
    }

    public function input_nilai_penurunan($tahun, $total_nilai_penurunan)
    {
        date_default_timezone_set('Asia/Jakarta');
        if ($total_nilai_penurunan == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                  </div>'
            );
            redirect('lap_keuangan/persediaan');
            return;
        }

        // Cek apakah data sudah ada di tabel neraca
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Aset Lancar');
        $this->db->where('akun', 'Penurunan Nilai Persediaan');
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
                'kategori' => 'Aset Lancar',
                'akun' => 'Penurunan Nilai Persediaan',
                'nilai_neraca' => $total_nilai_penurunan * -1,
                'nilai_neraca_audited' => $total_nilai_penurunan * -1,
                'posisi' => 7,
                'no_neraca' => '1.5.1',
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
        redirect('lap_keuangan/persediaan');
    }
}
