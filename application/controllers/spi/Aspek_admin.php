<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aspek_admin extends CI_Controller
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
        $data['title'] = 'Penilaian Kinerja Berdasarkan Aspek Administrasi';
        $data['unit_list'] = $this->Model_evaluasi_upk->get_unit_list();
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
            $data['admin_list'] = $this->Model_evaluasi_upk->get_admin($id_upk, $bulan, $tahun);
            $data['nama_upk'] = $this->Model_evaluasi_upk->get_nama_upk($id_upk);
            $data['filter'] = [
                'id_upk' => $id_upk,
                'bulan' => $bulan,
                'tahun' => $tahun
            ];
        } else {
            $data['admin_list'] = []; // kosong jika belum difilter
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
            $this->load->view('spi/view_aspek_admin', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('spi/view_aspek_admin', $data);
            $this->load->view('templates/footer');
        }
    }

    public function input_admin()
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
            $data['title'] = 'INPUT SKOR ADMINISTRASI';
            $data['unit_list'] = $this->Model_evaluasi_upk->get_unit_list();
            if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('spi/view_input_aspek_admin', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Administrator') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('spi/view_input_aspek_admin', $data);
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
            $query = $this->db->get('eu_admin');

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> Daftar sudah ada.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>'
                );
                redirect('spi/aspek_admin');
                return false;
            }

            // Jika belum ada, baru lakukan insert semua indikator
            foreach ($indikator as $i => $nama_indikator) {
                $skor = 1;
                if ($hasil[$i] == 'Lengkap & Sesuai') {
                    $skor = 3;
                } elseif ($hasil[$i] == 'Sebagian') {
                    $skor = 2;
                }

                $data = [
                    'id_upk' => $id_upk,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'indikator' => $nama_indikator,
                    'hasil' => $hasil[$i],
                    'skor' => $skor,
                    'created_by' => $created_by,
                    'created_at' => $created_at
                ];
                $this->Model_evaluasi_upk->insert_admin($data);
            }

            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
        <strong>Sukses!</strong> Daftar skor baru berhasil ditambahkan.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>'
            );
            redirect('spi/aspek_admin?id_upk=' . $id_upk . '&bulan=' . $bulan . '&tahun=' . $tahun);
        }
    }

    public function edit_admin()
    {
        $id_upk = $this->input->get('id_upk');
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        $data['title'] = 'Form Edit Aspek Administrasi';
        $data['unit_list'] = $this->Model_evaluasi_upk->get_unit_list();
        $data['id_upk'] = $id_upk;
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        $data['data_admin'] = $this->Model_evaluasi_upk->get_admin_by_upk_bulan_tahun($id_upk, $bulan, $tahun);

        if ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('spi/view_edit_aspek_admin', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('spi/view_edit_aspek_admin', $data);
            $this->load->view('templates/footer');
        }
    }

    public function update_admin()
    {
        $id_upk = $this->input->post('id_upk');
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $indikator = $this->input->post('indikator');
        $hasil = $this->input->post('hasil');
        $user = $this->session->userdata('nama_lengkap');

        foreach ($indikator as $i => $nama_indikator) {
            $skor = 1;
            if ($hasil[$i] == 'Lengkap & Sesuai') {
                $skor = 3;
            } elseif ($hasil[$i] == 'Sebagian') {
                $skor = 2;
            }


            $this->Model_evaluasi_upk->upsert_admin(
                $id_upk,
                $bulan,
                $tahun,
                $nama_indikator,
                $hasil[$i],
                $skor,
                $user
            );
        }

        $this->session->set_flashdata('info', '<div class="alert alert-success">Data berhasil diperbarui.</div>');
        redirect('spi/aspek_admin?id_upk=' . $id_upk . '&bulan=' . $bulan . '&tahun=' . $tahun);
    }
}
