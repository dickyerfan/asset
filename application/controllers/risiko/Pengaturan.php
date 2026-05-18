<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengaturan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Model_risiko');
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
    }

    public function index()
    {
        $data['title'] = 'PENGATURAN MANAJEMEN RISIKO';
        $data['matrik'] = $this->Model_risiko->getAllMatrik();
        $data['tingkat'] = $this->Model_risiko->getAllTingkat();
        $data['kategori'] = $this->Model_risiko->getAllKategori();
        $data['bagian'] = $this->Model_risiko->getAllBagian();
        $data['pemilik'] = $this->Model_risiko->getAllPemilik();
        $data['petugas_ttd'] = $this->Model_risiko->getAllPetugasTtd();
        $data['pengaturan_kunci'] = $this->Model_risiko->getAllPengaturanKunci();
        if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('risiko/view_pengaturan_risiko', $data);
            $this->load->view('templates/footer');
        } else if ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('risiko/view_pengaturan_risiko', $data);
            $this->load->view('templates/footer');
        }
    }

    public function toggle_kunci($kode_kunci)
    {
        if ($this->session->userdata('bagian') != 'Administrator') {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Ditolak!</strong> Pengaturan kunci hanya dapat diakses oleh Administrator.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
            );
            redirect('risiko/pengaturan');
            return;
        }

        $kunci = $this->Model_risiko->getPengaturanKunciByKode($kode_kunci);
        if (!$kunci) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Pengaturan kunci tidak ditemukan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
            );
            redirect('risiko/pengaturan');
            return;
        }

        $status_baru = (int)$kunci->status_kunci === 1 ? 0 : 1;
        $input = [
            'status_kunci' => $status_baru,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($status_baru === 1) {
            $input['locked_by'] = $this->session->userdata('nama_lengkap');
            $input['locked_at'] = date('Y-m-d H:i:s');
        } else {
            $input['unlocked_by'] = $this->session->userdata('nama_lengkap');
            $input['unlocked_at'] = date('Y-m-d H:i:s');
        }

        $this->Model_risiko->updatePengaturanKunci($kode_kunci, $input);
        $status_teks = $status_baru === 1 ? 'dikunci' : 'dibuka';
        $this->session->set_flashdata(
            'info',
            '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> ' . $kunci->nama_kunci . ' berhasil ' . $status_teks . '.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>'
        );
        redirect('risiko/pengaturan');
    }

    // Input matrik Risiko
    public function input_matrik()
    {
        $this->form_validation->set_rules('probabilitas', 'Probabilitas', 'required|integer');
        $this->form_validation->set_rules('dampak', 'Dampak', 'required|integer');
        $this->form_validation->set_rules('skor', 'Skor', 'required|integer');
        $this->form_validation->set_rules('level_risiko', 'Level Risiko', 'required|integer');
        $this->form_validation->set_rules('nama_level', 'Nama Level', 'required');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('integer', '%s harus berupa angka');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Input Matrik Risiko';
            if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('risiko/view_input_matrik_risiko', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('risiko/view_input_matrik_risiko', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $input = $this->input->post();
            // Cek duplikasi data
            $cek = $this->db->get_where('mr_matrik_risiko', [
                'probabilitas' => $input['probabilitas'],
                'dampak' => $input['dampak'],
                'skor' => $input['skor'],
                'level_risiko' => $input['level_risiko'],
                'nama_level' => $input['nama_level']
            ])->row();
            if ($cek) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Data sudah ada di database.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/pengaturan/input_matrik');
            } else {
                $input['created_by'] = $this->session->userdata('nama_lengkap');
                $input['created_at'] = date('Y-m-d H:i:s');
                $this->Model_risiko->insertmatrik($input);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Daftar matrik baru berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/pengaturan');
            }
        }
    }

    // Edit matrik Risiko
    public function edit_matrik($id)
    {
        $data['matrik'] = $this->Model_risiko->getmatrikById($id);
        $data['title'] = 'Edit Matrik Risiko';
        $this->form_validation->set_rules('probabilitas', 'Probabilitas', 'required|integer');
        $this->form_validation->set_rules('dampak', 'Dampak', 'required|integer');
        $this->form_validation->set_rules('skor', 'Skor', 'required|integer');
        $this->form_validation->set_rules('level_risiko', 'Level Risiko', 'required|integer');
        $this->form_validation->set_rules('nama_level', 'Nama Level', 'required');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('integer', '%s harus berupa angka');
        if ($this->form_validation->run() == FALSE) {
            if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('risiko/view_edit_matrik_risiko', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('risiko/view_edit_matrik_risiko', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $input = $this->input->post();
            $matrik_lama = $this->Model_risiko->getmatrikById($id);
            $is_same = ($input['probabilitas'] == $matrik_lama->probabilitas &&
                $input['dampak'] == $matrik_lama->dampak &&
                $input['skor'] == $matrik_lama->skor &&
                $input['level_risiko'] == $matrik_lama->level_risiko &&
                $input['nama_level'] == $matrik_lama->nama_level);
            if ($is_same) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Perhatian!</strong> Tidak ada perubahan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/pengaturan');
            } else {
                $input['modified_by'] = $this->session->userdata('nama_lengkap');
                $input['modified_at'] = date('Y-m-d H:i:s');
                $this->Model_risiko->updatematrik($id, $input);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Daftar Matrik risiko berhasil diupdate.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>'
                );
                redirect('risiko/pengaturan');
            }
        }
    }

    // Input Tingkat Risiko
    public function input_tingkat()
    {
        $this->form_validation->set_rules('level_tr', 'Level', 'required|integer');
        $this->form_validation->set_rules('nama_tr', 'Nama Level', 'required');
        $this->form_validation->set_rules('skor_min', 'Skor Min', 'required|integer');
        $this->form_validation->set_rules('skor_max', 'Skor Max', 'required|integer');
        $this->form_validation->set_rules('status_tr', 'Status', 'required');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('integer', '%s harus berupa angka');
        $data['title'] = 'Input Tingkat Risiko';
        if ($this->form_validation->run() == FALSE) {
            if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('risiko/view_input_tingkat_risiko', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('risiko/view_input_tingkat_risiko', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $input = $this->input->post();
            // Cek duplikasi data
            $cek = $this->db->get_where('mr_tingkat_risiko', [
                'level_tr' => $input['level_tr'],
                'nama_tr' => $input['nama_tr'],
                'skor_min' => $input['skor_min'],
                'skor_max' => $input['skor_max'],
                'status_tr' => $input['status_tr']
            ])->row();
            if ($cek) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Data sudah ada di database.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/pengaturan/input_tingkat');
            } else {
                $input['created_by'] = $this->session->userdata('nama_lengkap');
                $input['created_at'] = date('Y-m-d H:i:s');
                $this->Model_risiko->insertTingkat($input);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Daftar tingkat risiko baru berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/pengaturan');
            }
        }
    }

    // Edit Tingkat Risiko
    public function edit_tingkat($id)
    {
        $data['tingkat'] = $this->Model_risiko->getTingkatById($id);
        $data['title'] = 'Edit Tingkat Risiko';
        $this->form_validation->set_rules('level_tr', 'Level', 'required|integer');
        $this->form_validation->set_rules('nama_tr', 'Nama Level', 'required');
        $this->form_validation->set_rules('skor_min', 'Skor Min', 'required|integer');
        $this->form_validation->set_rules('skor_max', 'Skor Max', 'required|integer');
        $this->form_validation->set_rules('status_tr', 'Status', 'required');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('integer', '%s harus berupa angka');
        if ($this->form_validation->run() == FALSE) {
            if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('risiko/view_edit_tingkat_risiko', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('risiko/view_edit_tingkat_risiko', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $input = $this->input->post();
            $tingkat_lama = $this->Model_risiko->getTingkatById($id);
            $is_same = ($input['level_tr'] == $tingkat_lama->level_tr &&
                $input['nama_tr'] == $tingkat_lama->nama_tr &&
                $input['skor_min'] == $tingkat_lama->skor_min &&
                $input['skor_max'] == $tingkat_lama->skor_max &&
                $input['status_tr'] == $tingkat_lama->status_tr);
            if ($is_same) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Perhatian!</strong> Tidak ada perubahan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/pengaturan');
            } else {
                $input['modified_by'] = $this->session->userdata('nama_lengkap');
                $input['modified_at'] = date('Y-m-d H:i:s');
                $this->Model_risiko->updateTingkat($id, $input);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Daftar tingkat risiko berhasil diupdate.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>'
                );
                redirect('risiko/pengaturan');
            }
        }
    }

    public function input_kategori()
    {
        $this->form_validation->set_rules('kategori_kr', 'Kategori', 'required|integer');
        $this->form_validation->set_rules('nama_kr', 'Nama', 'required');
        $this->form_validation->set_rules('tipe_kr', 'Tipe', 'required');
        $this->form_validation->set_rules('status_kr', 'Status', 'required|integer');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('integer', '%s harus berupa angka');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Input Kategori Risiko';
            if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('risiko/view_input_kategori_risiko', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('risiko/view_input_kategori_risiko', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $input = $this->input->post();
            // Cek duplikasi data
            $cek = $this->db->get_where('mr_kategori_risiko', [
                'kategori_kr' => $input['kategori_kr'],
                'nama_kr' => $input['nama_kr'],
                'tipe_kr' => $input['tipe_kr'],
                'status_kr' => $input['status_kr']
            ])->row();
            if ($cek) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Data sudah ada di database.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/pengaturan/input_matrik');
            } else {
                $input['created_by'] = $this->session->userdata('nama_lengkap');
                $input['created_at'] = date('Y-m-d H:i:s');
                $this->Model_risiko->insertkategori($input);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Daftar kategori baru berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/pengaturan');
            }
        }
    }

    public function edit_kategori($id)
    {
        $data['kategori'] = $this->Model_risiko->getKategoriById($id);
        $data['title'] = 'Edit Kategori Risiko';
        $this->form_validation->set_rules('kategori_kr', 'Kategori', 'required|integer');
        $this->form_validation->set_rules('nama_kr', 'Nama', 'required');
        $this->form_validation->set_rules('tipe_kr', 'Tipe', 'required');
        $this->form_validation->set_rules('status_kr', 'Status', 'required|integer');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('integer', '%s harus berupa angka');

        if ($this->form_validation->run() == FALSE) {
            if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('risiko/view_edit_kategori_risiko', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('risiko/view_edit_kategori_risiko', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $input = $this->input->post();
            $kategori_lama = $this->Model_risiko->getKategoriById($id);
            $is_same = ($input['kategori_kr'] == $kategori_lama->kategori_kr &&
                $input['nama_kr'] == $kategori_lama->nama_kr &&
                $input['tipe_kr'] == $kategori_lama->tipe_kr &&
                $input['status_kr'] == $kategori_lama->status_kr);
            if ($is_same) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Perhatian!</strong> Tidak ada perubahan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/pengaturan');
            } else {
                $input['modified_by'] = $this->session->userdata('nama_lengkap');
                $input['modified_at'] = date('Y-m-d H:i:s');
                $this->Model_risiko->updateKategori($id, $input);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Daftar kategori risiko berhasil diupdate.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>'
                );
                redirect('risiko/pengaturan');
            }
        }
    }

    public function input_pemilik()
    {
        $this->form_validation->set_rules('nama_pemilik', 'Nama Pemilik', 'required');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Input Pemilik Risiko';
            if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('risiko/view_input_pemilik_risiko', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('risiko/view_input_pemilik_risiko', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $input = $this->input->post();
            $cek = $this->db->get_where('mr_pemilik_risiko', [
                'nama_pemilik' => $input['nama_pemilik']
            ])->row();
            if ($cek) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Data sudah ada di database.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/pengaturan/input_pemilik');
            } else {
                $input['created_by'] = $this->session->userdata('nama_lengkap');
                $input['created_at'] = date('Y-m-d H:i:s');
                $this->Model_risiko->insertPemilik($input);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Daftar Pemilik baru berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/pengaturan');
            }
        }
    }

    public function edit_pemilik($id)
    {
        $data['pemilik'] = $this->Model_risiko->getPemilikById($id);
        $data['title'] = 'Edit Pemilik Risiko';
        $this->form_validation->set_rules('nama_pemilik', 'Nama Pemilik', 'required');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == FALSE) {
            if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('risiko/view_edit_pemilik_risiko', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('risiko/view_edit_pemilik_risiko', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $input = $this->input->post();
            $pemilik_lama = $this->Model_risiko->getPemilikById($id);
            $is_same = ($input['nama_pemilik'] == $pemilik_lama->nama_pemilik &&
                $input['status_pemilik'] == $pemilik_lama->status_pemilik);
            if ($is_same) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Perhatian!</strong> Tidak ada perubahan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/pengaturan');
            } else {
                $input['modified_by'] = $this->session->userdata('nama_lengkap');
                $input['modified_at'] = date('Y-m-d H:i:s');
                $this->Model_risiko->updatePemilik($id, $input);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Daftar Pemilik risiko berhasil diupdate.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>'
                );
                redirect('risiko/pengaturan');
            }
        }
    }

    public function input_ttd()
    {
        $data['title'] = 'Input Petugas TTD';
        $data['bagian'] = $this->Model_risiko->getAllBagian();

        $this->form_validation->set_rules('kode_ttd', 'Kode TTD', 'required');
        $this->form_validation->set_rules('jabatan_ttd', 'Jabatan TTD', 'required');
        $this->form_validation->set_rules('nama_petugas', 'Nama Petugas', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required|integer');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('integer', '%s harus berupa angka');

        if ($this->form_validation->run() == FALSE) {
            if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('risiko/view_input_petugas_ttd', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('risiko/view_input_petugas_ttd', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $kode_ttd = $this->input->post('kode_ttd');
            $id_bagian = $this->input->post('id_bagian');

            if ($kode_ttd == 'pemilik_risiko' && empty($id_bagian)) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Perhatian!</strong> Bagian / UPK wajib dipilih untuk Pemilik Risiko.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/pengaturan/input_ttd');
                return;
            }

            $input = [
                'kode_ttd' => $kode_ttd,
                'jabatan_ttd' => $this->input->post('jabatan_ttd'),
                'id_bagian' => ($kode_ttd == 'pemilik_risiko') ? $id_bagian : null,
                'nama_petugas' => $this->input->post('nama_petugas'),
                'nik' => $this->input->post('nik'),
                'status' => $this->input->post('status'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            $cek = $this->db->get_where('mr_petugas_ttd', [
                'kode_ttd' => $input['kode_ttd'],
                'id_bagian' => $input['id_bagian'],
                'status' => 1
            ])->row();

            if ($input['status'] == 1 && $cek) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Petugas TTD aktif untuk posisi tersebut sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/pengaturan/input_ttd');
                return;
            }

            $this->Model_risiko->insertPetugasTtd($input);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Petugas TTD berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
            );
            redirect('risiko/pengaturan');
        }
    }

    public function edit_ttd($id_ttd)
    {
        $data['title'] = 'Edit Petugas TTD';
        $data['bagian'] = $this->Model_risiko->getAllBagian();
        $data['ttd'] = $this->Model_risiko->getPetugasTtdById($id_ttd);

        if (!$data['ttd']) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Data petugas TTD tidak ditemukan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
            );
            redirect('risiko/pengaturan');
            return;
        }

        $this->form_validation->set_rules('kode_ttd', 'Kode TTD', 'required');
        $this->form_validation->set_rules('jabatan_ttd', 'Jabatan TTD', 'required');
        $this->form_validation->set_rules('nama_petugas', 'Nama Petugas', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required|integer');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('integer', '%s harus berupa angka');

        if ($this->form_validation->run() == FALSE) {
            if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('risiko/view_edit_petugas_ttd', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('risiko/view_edit_petugas_ttd', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $kode_ttd = $this->input->post('kode_ttd');
            $id_bagian = $this->input->post('id_bagian');

            if ($kode_ttd == 'pemilik_risiko' && empty($id_bagian)) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Perhatian!</strong> Bagian / UPK wajib dipilih untuk Pemilik Risiko.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/pengaturan/edit_ttd/' . $id_ttd);
                return;
            }

            $input = [
                'kode_ttd' => $kode_ttd,
                'jabatan_ttd' => $this->input->post('jabatan_ttd'),
                'id_bagian' => ($kode_ttd == 'pemilik_risiko') ? $id_bagian : null,
                'nama_petugas' => $this->input->post('nama_petugas'),
                'nik' => $this->input->post('nik'),
                'status' => $this->input->post('status'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $ttd_lama = $data['ttd'];
            $is_same = ($input['kode_ttd'] == $ttd_lama->kode_ttd &&
                $input['jabatan_ttd'] == $ttd_lama->jabatan_ttd &&
                (string)$input['id_bagian'] == (string)$ttd_lama->id_bagian &&
                $input['nama_petugas'] == $ttd_lama->nama_petugas &&
                $input['nik'] == $ttd_lama->nik &&
                $input['status'] == $ttd_lama->status);

            if ($is_same) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Perhatian!</strong> Tidak ada perubahan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/pengaturan');
                return;
            }

            if ($input['status'] == 1) {
                $this->db->where('kode_ttd', $input['kode_ttd']);
                if ($input['id_bagian'] === null) {
                    $this->db->where('id_bagian IS NULL', null, false);
                } else {
                    $this->db->where('id_bagian', $input['id_bagian']);
                }
                $this->db->where('status', 1);
                $this->db->where('id_ttd !=', $id_ttd);
                $cek = $this->db->get('mr_petugas_ttd')->row();

                if ($cek) {
                    $this->session->set_flashdata(
                        'info',
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Gagal!</strong> Petugas TTD aktif untuk posisi tersebut sudah ada.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>'
                    );
                    redirect('risiko/pengaturan/edit_ttd/' . $id_ttd);
                    return;
                }
            }

            $this->Model_risiko->updatePetugasTtd($id_ttd, $input);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Petugas TTD berhasil diupdate.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
            );
            redirect('risiko/pengaturan');
        }
    }
}
