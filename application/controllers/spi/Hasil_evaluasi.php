<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hasil_evaluasi extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_evaluasi_upk');
        $this->load->library('form_validation');
        $this->load->helper('date');
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
        if ($bagian != 'Publik' && $bagian != 'Administrator') {
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
        $data['title'] = 'Hasil Penilaian Evaluasi Kinerja UPK ';
        // $bulan_input = $this->input->get('bulan') ? (int)$this->input->get('bulan') : (int)date('m');
        // $tahun_input = $this->input->get('tahun') ? (int)$this->input->get('tahun') : (int)date('Y');
        $bulan_filter = $this->input->get('bulan');
        $tahun_filter = $this->input->get('tahun');


        // Jika filter tidak ada, gunakan default (bulan/tahun sekarang, UPK semua)
        if ($bulan_filter === null || $bulan_filter === '') {
            $bulan_sekarang = (int)date('m');
            if ($bulan_sekarang === 1) {
                $bulan_filter = 12; // Jika bulan sekarang adalah Januari, set bulan ke 12
                $tahun_filter = (int)date('Y') - 1; // Kurangi tahun
            } else {
                $bulan_filter = $bulan_sekarang - 1; // Kurangi bulan
                $tahun_filter = (int)date('Y'); // Tahun tetap sama
            }
        } else {
            $bulan_filter = (int)$bulan_filter;
        }

        if ($tahun_filter === null || $tahun_filter === '') {
            $tahun_filter = (int)date('Y');
        } else {
            $tahun_filter = (int)$tahun_filter;
        }

        $data['rekap'] = $this->Model_evaluasi_upk->get_skor_total_per_upk($bulan_filter, $tahun_filter);
        $data['bulan_selected'] = $bulan_filter;
        $data['tahun_selected'] = $tahun_filter;

        $data['filter'] = [
            'bulan' => $bulan_filter,
            'tahun' => $tahun_filter
        ];

        $nama_bulan = array(
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        );

        $data['nama_bulan_terpilih'] = $nama_bulan[$bulan_filter];

        if ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('spi/view_hasil_evaluasi', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('spi/view_hasil_evaluasi', $data);
            $this->load->view('templates/footer');
        }
    }

    public function detail($id_upk, $bulan, $tahun)
    {
        // Validasi input jika diperlukan (misal: is_numeric)
        if (!is_numeric($id_upk) || !is_numeric($bulan) || !is_numeric($tahun)) {
            show_404(); // Atau redirect ke halaman error
        }

        // --- SIMPAN DATA KE SESSION DI SINI ---
        $this->session->set_userdata('current_upk_detail', [
            'id_upk' => (int)$id_upk,
            'bulan' => (int)$bulan,
            'tahun' => (int)$tahun
        ]);
        // --- AKHIR SIMPAN DATA KE SESSION ---

        // Ambil nama UPK untuk judul halaman detail
        $this->db->select('nama_bagian');
        $this->db->where('id_bagian', $id_upk);
        $query_upk = $this->db->get('bagian_upk')->row();
        $nama_upk = $query_upk ? $query_upk->nama_bagian : 'Tidak Ditemukan';


        // Panggil fungsi-fungsi model yang sudah ada untuk mendapatkan detail
        $data['detail_teknis'] = $this->Model_evaluasi_upk->get_teknis($id_upk, $bulan, $tahun);
        $data['detail_admin'] = $this->Model_evaluasi_upk->get_admin($id_upk, $bulan, $tahun);
        $data['detail_koordinasi'] = $this->Model_evaluasi_upk->get_koordinasi($id_upk, $bulan, $tahun);
        $data['detail_tindak_lanjut'] = $this->Model_evaluasi_upk->get_tindak_lanjut($bulan, $tahun, $id_upk);

        // --- TAMBAHAN UNTUK MENGHITUNG TOTAL SKOR PER ASPEK ---
        $total_skor_teknis = 0;
        foreach ($data['detail_teknis'] as $teknis) {
            $total_skor_teknis += (float)$teknis->skor;
        }
        $data['total_skor_teknis'] = $total_skor_teknis;

        $total_skor_admin = 0;
        foreach ($data['detail_admin'] as $admin) {
            $total_skor_admin += (float)$admin->skor;
        }
        $data['total_skor_admin'] = $total_skor_admin;

        $total_skor_koordinasi = 0;
        foreach ($data['detail_koordinasi'] as $koordinasi) {
            $total_skor_koordinasi += (float)$koordinasi->skor;
        }
        $data['total_skor_koordinasi'] = $total_skor_koordinasi;

        // Data tambahan untuk view
        $data['id_upk_selected'] = $id_upk;
        $data['bulan_selected'] = $bulan;
        $data['tahun_selected'] = $tahun;
        $data['nama_upk_selected'] = $nama_upk;

        // Untuk nama bulan di judul detail
        $nama_bulan_array = array(
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        );
        $data['nama_bulan_terpilih'] = $nama_bulan_array[(int)$bulan];

        $data['title'] = 'HASIL EVALUASI KINERJA UPK ' . $nama_upk . ' BULAN ' . $data['nama_bulan_terpilih'] . ' TAHUN ' . $tahun;

        if ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('spi/view_detail_evaluasi', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('spi/view_detail_evaluasi', $data);
            $this->load->view('templates/footer');
        }
    }

    public function cetak_hasil()
    {
        // Ambil data dari session
        $current_upk_detail = $this->session->userdata('current_upk_detail');

        // Pastikan data ada di session
        if (!$current_upk_detail || !isset($current_upk_detail['id_upk'])) {
            // Redirect atau tampilkan pesan error jika session kosong
            $this->session->set_flashdata('info', '<div class="alert alert-danger" role="alert">Data detail tidak ditemukan untuk dicetak. Silakan pilih UPK terlebih dahulu.</div>');
            redirect('spi/index');
            return;
        }

        $id_upk = $current_upk_detail['id_upk'];
        $bulan = $current_upk_detail['bulan'];
        $tahun = $current_upk_detail['tahun'];

        // Ambil nama UPK untuk judul halaman detail
        $this->db->select('nama_bagian');
        $this->db->where('id_bagian', $id_upk);
        $query_upk = $this->db->get('bagian_upk')->row();
        $nama_upk = $query_upk ? $query_upk->nama_bagian : 'Tidak Ditemukan';

        // Panggil fungsi-fungsi model yang sudah ada untuk mendapatkan detail
        $data['detail_teknis'] = $this->Model_evaluasi_upk->get_teknis($id_upk, $bulan, $tahun);
        $data['detail_admin'] = $this->Model_evaluasi_upk->get_admin($id_upk, $bulan, $tahun);
        $data['detail_koordinasi'] = $this->Model_evaluasi_upk->get_koordinasi($id_upk, $bulan, $tahun);
        $data['detail_tindak_lanjut'] = $this->Model_evaluasi_upk->get_tindak_lanjut($bulan, $tahun, $id_upk);

        // --- HITUNG ULANG TOTAL SKOR PER ASPEK (HARUS SAMA DENGAN DETAIL_UPK) ---
        $total_skor_teknis = 0;
        foreach ($data['detail_teknis'] as $teknis) {
            $total_skor_teknis += (float)$teknis->skor;
        }
        $data['total_skor_teknis'] = $total_skor_teknis;

        $total_skor_admin = 0;
        foreach ($data['detail_admin'] as $admin) {
            $total_skor_admin += (float)$admin->skor;
        }
        $data['total_skor_admin'] = $total_skor_admin;

        $total_skor_koordinasi = 0;
        foreach ($data['detail_koordinasi'] as $koordinasi) {
            $total_skor_koordinasi += (float)$koordinasi->skor;
        }
        $data['total_skor_koordinasi'] = $total_skor_koordinasi;

        // Hitung Grand Total Skor (dari ketiga aspek)
        $data['grand_total_skor_detail'] = $total_skor_teknis + $total_skor_admin + $total_skor_koordinasi;
        // --- AKHIR HITUNG ULANG ---

        // Data tambahan untuk view PDF
        $data['id_upk_selected'] = $id_upk;
        $data['bulan_selected'] = $bulan;
        $data['tahun_selected'] = $tahun;
        $data['nama_upk_selected'] = $nama_upk;

        $nama_bulan_array = array(
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        );
        $data['nama_bulan_terpilih'] = $nama_bulan_array[(int)$bulan];

        $data['title'] = 'HASIL EVALUASI KINERJA UPK ' . $nama_upk . ' BULAN ' . $data['nama_bulan_terpilih'] . ' TAHUN ' . $tahun;

        // Atur paper dan filename PDF
        $this->pdf->setPaper('folio', 'portrait');
        $this->pdf->filename = "hasil_evaluasi_kinerja_upk-{$nama_upk}-{$data['nama_bulan_terpilih']}-{$tahun}.pdf";

        // Generate PDF dari view khusus untuk PDF
        $this->pdf->generate('spi/cetak_hasil_evaluasi_pdf', $data);
    }
}
