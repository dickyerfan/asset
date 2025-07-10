<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aspek_koordinasi extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_evaluasi_upk');
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
        $data['title'] = 'Penilaian Kinerja Berdasarkan Aspek Koordinasi';
        $data['unit_list'] = $this->Model_evaluasi_upk->get_unit_list(); // ambil list unit
        $id_upk = $this->input->get('id_upk');

        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');
        // Jika filter tidak ada, gunakan default (bulan/tahun sekarang, UPK semua)
        if ($bulan === null || $bulan === '') {
            $bulan_sekarang = (int)date('m');
            if ($bulan_sekarang === 1) {
                $bulan = 12; // Jika bulan sekarang adalah Januari, set bulan ke 12
                $tahun = (int)date('Y') - 1; // Kurangi tahun
            } else {
                $bulan = $bulan_sekarang - 1; // Kurangi bulan
                $tahun = (int)date('Y'); // Tahun tetap sama
            }
        } else {
            $bulan = (int)$bulan;
        }

        if ($tahun === null || $tahun === '') {
            $tahun = (int)date('Y');
        } else {
            $tahun = (int)$tahun;
        }

        // if ($id_upk === null || $id_upk === '') {
        //     $id_upk = 7;
        // } else {
        //     $id_upk = $id_upk;
        // }

        if ($id_upk && $bulan && $tahun) {
            $data['koordinasi_list'] = $this->Model_evaluasi_upk->get_koordinasi($id_upk, $bulan, $tahun);
            $data['nama_upk'] = $this->Model_evaluasi_upk->get_nama_upk($id_upk);
            $data['filter'] = [
                'id_upk' => $id_upk,
                'bulan' => $bulan,
                'tahun' => $tahun
            ];
        } else {
            $data['koordinasi_list'] = []; // kosong jika belum difilter
            $data['filter'] = null;
            $data['nama_upk'] = null;
        }

        $data['nama_bulan_lengkap'] = array(
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        );

        $data['nama_bulan_terpilih'] = $data['nama_bulan_lengkap'][$bulan];
        $data['tahun'] = $tahun;
        $data['bulan'] = $bulan;

        if ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('spi/view_aspek_koordinasi', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('spi/view_aspek_koordinasi', $data);
            $this->load->view('templates/footer');
        }
    }

    public function input_koordinasi()
    {
        $tahun = $this->input->post('tahun_data');
        date_default_timezone_set('Asia/Jakarta');
        $this->form_validation->set_rules('id_upk', 'Nama UPK', 'required|trim');
        $this->form_validation->set_rules('bulan', 'Bulan', 'required|trim|numeric');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim|numeric');
        $this->form_validation->set_rules('indikator[]', 'Indikator', 'required');
        $this->form_validation->set_rules('hasil[]', 'Hasil', 'required');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'INPUT SKOR KOORDINASI';
            $data['unit_list'] = $this->Model_evaluasi_upk->get_unit_list();
            if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('spi/view_input_aspek_koordinasi', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Administrator') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('spi/view_input_aspek_koordinasi', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $id_upk = $this->input->post('id_upk');
            $bulan = $this->input->post('bulan');
            $tahun = $this->input->post('tahun');
            $indikator = $this->input->post('indikator');
            $hasil = $this->input->post('hasil');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            // Cek apakah data untuk UPK-bulan-tahun sudah ada
            $this->db->where('id_upk', $id_upk);
            $this->db->where('bulan', $bulan);
            $this->db->where('tahun', $tahun);
            $query = $this->db->get('eu_koordinasi');

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> Daftar sudah ada.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>'
                );
                redirect('spi/aspek_koordinasi');
                return false;
            }

            foreach ($indikator as $i => $nama_indikator) {
                $hasil_value = $hasil[$i];
                $skor = 1;

                switch ($nama_indikator) {
                    case 'Kehadiran & Dokumen Manual':
                        if ($hasil_value == 'Hadir & Lengkap') $skor = 3;
                        elseif ($hasil_value == 'Hadir & Tidak Lengkap') $skor = 2;
                        break;
                    case 'Memberikan Narasi Unit':
                        if ($hasil_value == 'Narasi Lengkap') $skor = 3;
                        elseif ($hasil_value == 'Narasi Sebagian') $skor = 2;
                        break;
                    case 'Tanggapan terhadap Temuan Bulan Lalu':
                        if ($hasil_value == 'Tanggapan Lengkap') $skor = 3;
                        elseif ($hasil_value == 'Sebagian') $skor = 2;
                        break;
                    case 'Aktif Menyampaikan Inisiatif / Usulan':
                        if ($hasil_value == 'Usulan Lengkap') $skor = 3;
                        elseif ($hasil_value == 'Sebagian') $skor = 2;
                        break;
                    case 'Pencocokan Data Aplikasi vs Buku Manual':
                        if ($hasil_value == 'Cocok Semua') $skor = 3;
                        elseif ($hasil_value == 'Sebagian') $skor = 2;
                        break;
                }

                $data = [
                    'id_upk' => $id_upk,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'indikator' => $nama_indikator,
                    'hasil' => $hasil_value,
                    'skor' => $skor,
                    'created_by' => $created_by,
                    'created_at' => $created_at
                ];

                $this->Model_evaluasi_upk->insert_koordinasi($data);
            }

            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
        <strong>Sukses!</strong> Daftar skor baru berhasil ditambahkan.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>'
            );
            redirect('spi/aspek_koordinasi?id_upk=' . $id_upk . '&bulan=' . $bulan . '&tahun=' . $tahun);
        }
    }

    public function edit_koordinasi()
    {
        $id_upk = $this->input->get('id_upk');
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        $created_at = $this->Model_evaluasi_upk->get_created_at('eu_teknis', $id_upk, $bulan, $tahun);

        if ($created_at) {
            $created_month = (int)date('m', strtotime($created_at));
            $created_year  = (int)date('Y', strtotime($created_at));

            $current_month = (int)date('m');
            $current_year  = (int)date('Y');

            // Cek apakah created_at sudah beda bulan/tahun dari bulan sekarang
            if ($created_month !== $current_month || $created_year !== $current_year) {
                $this->session->set_flashdata('info', '<div class="alert alert-danger">Data ini tidak dapat diedit karena periode input sudah berakhir.</div>');
                redirect('spi/aspek_teknik');
                return;
            }
        }

        $data['title'] = 'Form Edit Aspek Koordinasi';
        $data['unit_list'] = $this->Model_evaluasi_upk->get_unit_list();
        $data['id_upk'] = $id_upk;
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        $data['data_koordinasi'] = $this->Model_evaluasi_upk->get_koordinasi_by_upk_bulan_tahun($id_upk, $bulan, $tahun);

        if ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('spi/view_edit_aspek_koordinasi', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('spi/view_edit_aspek_koordinasi', $data);
            $this->load->view('templates/footer');
        }
    }

    public function update_koordinasi()
    {
        $id_upk = $this->input->post('id_upk');
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $indikator = $this->input->post('indikator');
        $hasil = $this->input->post('hasil');
        $user = $this->session->userdata('nama_lengkap');

        // Mapping nilai hasil menjadi skor per indikator
        $skor_mapping = [
            "Kehadiran & Dokumen Manual" => [
                "Hadir & Lengkap" => 3,
                "Hadir & Tidak Lengkap" => 2,
                "Tidak Hadir" => 1
            ],
            "Memberikan Narasi Unit" => [
                "Narasi Lengkap" => 3,
                "Narasi Sebagian" => 2,
                "Tidak Ada" => 1
            ],
            "Tanggapan terhadap Temuan Bulan Lalu" => [
                "Tanggapan Lengkap" => 3,
                "Sebagian" => 2,
                "Tidak Lengkap" => 1
            ],
            "Aktif Menyampaikan Inisiatif / Usulan" => [
                "Usulan Lengkap" => 3,
                "Sebagian" => 2,
                "Tidak Ada Usulan" => 1
            ],
            "Pencocokan Data Aplikasi vs Buku Manual" => [
                "Cocok Semua" => 3,
                "Sebagian" => 2,
                "Tidak Cocok" => 1
            ]
        ];

        foreach ($indikator as $i => $nama_indikator) {
            $hasil_pilihan = $hasil[$i];

            // Cari skor dari mapping
            $skor = isset($skor_mapping[$nama_indikator][$hasil_pilihan]) ?
                $skor_mapping[$nama_indikator][$hasil_pilihan] : 1;

            // Lakukan upsert ke DB
            $this->Model_evaluasi_upk->upsert_koordinasi(
                $id_upk,
                $bulan,
                $tahun,
                $nama_indikator,
                $hasil_pilihan,
                $skor,
                $user
            );
        }

        $this->session->set_flashdata('info', '<div class="alert alert-success">Data berhasil diperbarui.</div>');
        redirect('spi/aspek_koordinasi?id_upk=' . $id_upk . '&bulan=' . $bulan . '&tahun=' . $tahun);
    }
}
