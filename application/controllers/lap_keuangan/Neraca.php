<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Neraca extends CI_Controller
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

        $data['title'] = 'LAPORAN POSISI KEUANGAN';
        $data['neraca'] = $this->Model_lap_keuangan->get_all_neraca($tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/view_neraca', $data);
        $this->load->view('templates/footer');
    }

    public function neraca_cetak()
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

        $data['title'] = 'LAPORAN POSISI KEUANGAN';
        $data['neraca'] = $this->Model_lap_keuangan->get_all_neraca($tahun);

        $this->pdf->setPaper('folio', 'portrait');
        $this->pdf->filename = "neraca-{$tahun}.pdf";
        $this->pdf->generate('cetakan_lap_keuangan/neraca_pdf', $data);
    }

    public function edit_audited($id_neraca)
    {
        $tahun = $this->input->get('tahun');
        $data['neraca'] = $this->db->get_where('neraca', ['id_neraca' => $id_neraca])->row();

        if (!$data['neraca']) {
            show_404(); // Jika data tidak ditemukan, tampilkan halaman 404
        }
        $data['title'] = 'FORM EDIT LAPORAN POSISI KEUANGAN AUDITED';
        $data['tahun'] = $tahun;
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('lap_keuangan/view_edit_neraca', $data);
        $this->load->view('templates/footer');
    }

    public function update_audited()
    {
        date_default_timezone_set('Asia/Jakarta');
        $id_neraca = $this->input->post('id_neraca');
        // $nilai_neraca_audited = $this->input->post('nilai_neraca_audited');
        $nilai_baru = floatval(str_replace('.', '', $this->input->post('nilai_neraca_audited')));
        $tahun = $this->input->get('tahun');

        // Ambil nilai lama dari database
        $neraca = $this->db->get_where('neraca', ['id_neraca' => $id_neraca])->row();

        if ($neraca) {
            $nilai_lama = floatval($neraca->nilai_neraca_audited);

            // Jika nilai yang diinput sama dengan nilai lama, tampilkan peringatan
            if ($nilai_baru == $nilai_lama) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Gagal Update,</strong> Nilai Neraca Audited tidak berubah.!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            </button>
                          </div>'
                );
                redirect('lap_keuangan/neraca?tahun=' . $tahun);
                return;
            }
            $data = [
                'nilai_neraca_audited' => $nilai_baru,
                'status' => 0,
                'modified_at' => date('Y-m-d H:i:s'),
                'modified_by' => $this->session->userdata('nama_lengkap')
            ];
            $this->db->where('id_neraca', $id_neraca);
            $this->db->update('neraca', $data);

            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Nilai Neraca Audited berhasil di update!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
            );
            redirect('lap_keuangan/neraca?tahun=' . $tahun);
        }
    }
}
