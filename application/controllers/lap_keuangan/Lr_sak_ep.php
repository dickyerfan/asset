<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lr_sak_ep extends CI_Controller
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

        $data['title'] = 'LAPORAN LABA - RUGI DAN PENGHASILAN KOMPREHENSIP LAIN';
        $data['lr_sak_ep'] = $this->Model_labarugi->get_all_sak_ep($tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/view_lr_sak_ep', $data);
        $this->load->view('templates/footer');
    }

    public function lr_sak_ep_cetak()
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

        $data['title'] = 'LAPORAN LABA - RUGI DAN PENGHASILAN KOMPREHENSIP LAIN';
        $data['lr_sak_ep'] = $this->Model_lap_keuangan->get_all_lr_sak_ep($tahun);

        $this->pdf->setPaper('folio', 'portrait');
        $this->pdf->filename = "lr_sak_ep-{$tahun}.pdf";
        $this->pdf->generate('cetakan_lap_keuangan/lr_sak_ep_pdf', $data);
    }

    public function edit_audited($id_lr_sak_ep)
    {
        $tahun = $this->input->get('tahun');
        $data['lr_sak_ep'] = $this->db->get_where('lr_sak_ep', ['id_lr_sak_ep' => $id_lr_sak_ep])->row();

        if (!$data['lr_sak_ep']) {
            show_404(); // Jika data tidak ditemukan, tampilkan halaman 404
        }
        $data['title'] = 'FORM EDIT LAPORAN LABA - RUGI DAN PENGHASILAN KOMPREHENSIP LAIN AUDITED';
        $data['tahun'] = $tahun;
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/view_edit_lr_sak_ep', $data);
        $this->load->view('templates/footer');
    }

    public function update_audited()
    {
        date_default_timezone_set('Asia/Jakarta');
        $id_lr_sak_ep = $this->input->post('id_lr_sak_ep');
        // $nilai_lr_sak_ep_audited = $this->input->post('nilai_lr_sak_ep_audited');
        $nilai_baru = floatval(str_replace('.', '', $this->input->post('nilai_lr_sak_ep_audited')));
        $tahun = $this->input->get('tahun');

        // Ambil nilai lama dari database
        $lr_sak_ep = $this->db->get_where('lr_sak_ep', ['id_lr_sak_ep' => $id_lr_sak_ep])->row();

        if ($lr_sak_ep) {
            $nilai_lama = floatval($lr_sak_ep->nilai_lr_sak_ep_audited);

            // Jika nilai yang diinput sama dengan nilai lama, tampilkan peringatan
            if ($nilai_baru == $nilai_lama) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Gagal Update,</strong> Nilai lr_sak_ep Audited tidak berubah.!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            </button>
                          </div>'
                );
                redirect('lap_keuangan/lr_sak_ep?tahun=' . $tahun);
                return;
            }
            $data = [
                'nilai_lr_sak_ep_audited' => $nilai_baru,
                'status' => 0,
                'modified_at' => date('Y-m-d H:i:s'),
                'modified_by' => $this->session->userdata('nama_lengkap')
            ];
            $this->db->where('id_lr_sak_ep', $id_lr_sak_ep);
            $this->db->update('lr_sak_ep', $data);

            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Nilai lr_sak_ep Audited berhasil di update!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/lr_sak_ep?tahun=' . $tahun);
        }
    }

    public function input_pktb($tahun, $total_pktb_tahun_ini)
    {
        date_default_timezone_set('Asia/Jakarta');

        if ($total_pktb_tahun_ini == 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/lr_sak_ep');
            return;
        }

        // Cek apakah data sudah ada di database
        $this->db->where('tahun_neraca', $tahun);
        $this->db->where('kategori', 'Ekuitas');
        $this->db->where('akun', 'Laba Rugi Tahun Berjalan');
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
            redirect('lap_keuangan/lr_sak_ep');
        } else {
            // Jika belum ada, lakukan insert
            $data = [
                'tahun_neraca' => $tahun,
                'kategori' => 'Ekuitas',
                'akun' => 'Laba Rugi Tahun Berjalan',
                'nilai_neraca' => $total_pktb_tahun_ini,
                'nilai_neraca_audited' => $total_pktb_tahun_ini,
                'posisi' => 35,
                'no_neraca' => '5.5',
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
        redirect('lap_keuangan/neraca');
    }
}
