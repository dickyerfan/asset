<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cak_layanan extends CI_Controller
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
        $data['title'] = 'PERHITUNGAN CAKUPAN PELAYANAN';
        $data_penduduk = $this->Model_langgan->get_data_penduduk($tahun);
        $data_pelanggan = $this->Model_langgan->get_data_pelanggan($tahun);

        // Hitung total nilai
        $total_penduduk = 0;
        $total_kk = 0;
        $total_wil_layan = 0;
        $total_kk_layan = 0;
        $jumlah_wil_layan_ya = 0;
        $total_wil_layan_semua = 0;

        foreach ($data_penduduk as $dp) {
            $total_penduduk += (int) $dp->jumlah_penduduk;
            $total_kk += (int) $dp->jumlah_kk;
            $total_wil_layan += (int) $dp->jumlah_wil_layan;
            $total_kk_layan += (int) $dp->jumlah_kk_layan;

            if (strtoupper($dp->wil_layan) === 'YA') {
                $jumlah_wil_layan_ya++;
            }
            if (!empty($dp->wil_layan)) {
                $total_wil_layan_semua++;
            }
        }

        $data['cakupan'] = [
            'total_penduduk' => $total_penduduk,
            'total_kk' => $total_kk,
            'rata_jiwa_kk' => $total_kk != 0 ? $total_penduduk / $total_kk : 0,
            'rata_jiwa_kk2' => $total_wil_layan != 0 ? $total_wil_layan / $total_kk_layan : 0,
            'total_wil_layan' => $total_wil_layan,
            'total_kk_layan' => $total_kk_layan,
            'jumlah_wil_layan_ya' => $jumlah_wil_layan_ya,
            'total_wil_layan_semua' => $total_wil_layan_semua
        ];
        $data['data_penduduk'] = $data_penduduk;

        $total_rt_dom = 0;
        $total_niaga_dom = 0;
        $total_sl_hu_dom = 0;
        $total_n_aktif_dom = 0;

        foreach ($data_pelanggan as $dl) {
            $total_rt_dom += (int) $dl->rt_dom;
            $total_niaga_dom += (int) $dl->niaga_dom;
            $total_sl_hu_dom += (int) $dl->sl_hu_dom;
            $total_n_aktif_dom += (int) $dl->n_aktif_dom;
        }

        $data['pelanggan'] = [
            'total_rt_dom' => $total_rt_dom,
            'total_niaga_dom' => $total_niaga_dom,
            'total_sl_hu_dom' => $total_sl_hu_dom,
            'total_n_aktif_dom' => $total_n_aktif_dom
        ];

        $data['data_pelanggan'] = $data_pelanggan;

        if ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('langganan/view_cak_layanan', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('langganan/view_cak_layanan', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('langganan/view_cak_layanan', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('langganan/view_cak_layanan', $data);
            $this->load->view('templates/footer');
        }
    }

    public function cetak_cak_layanan()
    {
        $tahun = $this->session->userdata('tahun_session');

        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'PERHITUNGAN CAKUPAN PELAYANAN';
        $data_penduduk = $this->Model_langgan->get_data_penduduk($tahun);
        $data_pelanggan = $this->Model_langgan->get_data_pelanggan($tahun);

        // Hitung total nilai
        $total_penduduk = 0;
        $total_kk = 0;
        $total_wil_layan = 0;
        $total_kk_layan = 0;
        $jumlah_wil_layan_ya = 0;
        $total_wil_layan_semua = 0;

        foreach ($data_penduduk as $dp) {
            $total_penduduk += (int) $dp->jumlah_penduduk;
            $total_kk += (int) $dp->jumlah_kk;
            $total_wil_layan += (int) $dp->jumlah_wil_layan;
            $total_kk_layan += (int) $dp->jumlah_kk_layan;

            if (strtoupper($dp->wil_layan) === 'YA') {
                $jumlah_wil_layan_ya++;
            }
            if (!empty($dp->wil_layan)) {
                $total_wil_layan_semua++;
            }
        }

        $data['cakupan'] = [
            'total_penduduk' => $total_penduduk,
            'total_kk' => $total_kk,
            'rata_jiwa_kk' => $total_kk != 0 ? $total_penduduk / $total_kk : 0,
            'rata_jiwa_kk2' => $total_wil_layan != 0 ? $total_wil_layan / $total_kk_layan : 0,
            'total_wil_layan' => $total_wil_layan,
            'total_kk_layan' => $total_kk_layan,
            'jumlah_wil_layan_ya' => $jumlah_wil_layan_ya,
            'total_wil_layan_semua' => $total_wil_layan_semua
        ];
        $data['data_penduduk'] = $data_penduduk;

        $total_rt_dom = 0;
        $total_niaga_dom = 0;
        $total_sl_hu_dom = 0;
        $total_n_aktif_dom = 0;

        foreach ($data_pelanggan as $dl) {
            $total_rt_dom += (int) $dl->rt_dom;
            $total_niaga_dom += (int) $dl->niaga_dom;
            $total_sl_hu_dom += (int) $dl->sl_hu_dom;
            $total_n_aktif_dom += (int) $dl->n_aktif_dom;
        }

        $data['pelanggan'] = [
            'total_rt_dom' => $total_rt_dom,
            'total_niaga_dom' => $total_niaga_dom,
            'total_sl_hu_dom' => $total_sl_hu_dom,
            'total_n_aktif_dom' => $total_n_aktif_dom
        ];

        $data['data_pelanggan'] = $data_pelanggan;

        $this->pdf->setPaper('folio', 'portrait');
        $this->pdf->filename = "cak_layanan-{$tahun}.pdf";
        $this->pdf->generate('langganan/cetak_cak_layanan_pdf', $data);
    }


    // data penduduk
    public function data_penduduk()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'DATA PENDUDUK';
        $data['data_penduduk'] = $this->Model_langgan->get_data_penduduk($tahun);

        if ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('langganan/view_data_penduduk', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('langganan/view_data_penduduk', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('langganan/view_data_penduduk', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('langganan/view_data_penduduk', $data);
            $this->load->view('templates/footer');
        }
    }

    public function input_data_penduduk()
    {
        // $tahun = $this->session->userdata('tahun_session');
        $tahun = $this->input->post('tahun_data');
        date_default_timezone_set('Asia/Jakarta');
        $this->form_validation->set_rules('id_kec', 'Nama Kecamatan', 'required|trim');
        $this->form_validation->set_rules('wil_layan', 'Wil Layanan', 'required|trim');
        $this->form_validation->set_rules('wil_adm', 'Wil Administrasi', 'required|trim');
        $this->form_validation->set_rules('jumlah_penduduk', 'Jumlah Penduduk', 'required|trim');
        $this->form_validation->set_rules('jumlah_kk', 'Jumlah KK', 'required|trim');
        $this->form_validation->set_rules('tahun_data', 'Tahun Data', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Data Penduduk';
            $data['kec'] = $this->Model_langgan->get_kec();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('langganan/view_input_penduduk', $data);
            $this->load->view('templates/footer');
        } else {
            $id_kec = $this->input->post('id_kec');
            $wil_layan = $this->input->post('wil_layan');
            $wil_adm = $this->input->post('wil_adm');
            $jumlah_penduduk = $this->input->post('jumlah_penduduk');
            $jumlah_kk = $this->input->post('jumlah_kk');

            // Set nilai jumlah_wil_layan berdasarkan pilihan wil_layan
            if ($wil_layan == 'YA') {
                $jumlah_wil_layan = $jumlah_penduduk;
            } else {
                $jumlah_wil_layan = $this->input->post('jumlah_wil_layan');
            }

            // Set nilai jumlah_kk_layan berdasarkan pilihan wil_adm
            if ($wil_adm == 'YA') {
                $jumlah_kk_layan = $jumlah_kk;
            } else {
                $jumlah_kk_layan = $this->input->post('jumlah_kk_layan');
            }

            $tahun_data = $this->input->post('tahun_data');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_penduduk = [
                'id_kec' => $id_kec,
                'tahun_data' => $tahun_data,
                'wil_layan' => $wil_layan,
                'wil_adm' => $wil_adm,
                'jumlah_penduduk' => $jumlah_penduduk,
                'jumlah_kk' => $jumlah_kk,
                'jumlah_wil_layan' => $jumlah_wil_layan,
                'jumlah_kk_layan' => $jumlah_kk_layan,
                'created_by' => $created_by,
                'created_at' => $created_at
            ];

            // Cek apakah tahun dan id kec sudah ada di database
            $this->db->where('tahun_data', $tahun);
            $this->db->where('id_kec', $id_kec);
            $query = $this->db->get('ek_data_penduduk');

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Data Penduduk sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('langganan/cak_layanan/data_penduduk?tahun=' . $tahun);
                return false;
            } else {
                $this->Model_langgan->input_data_penduduk('ek_data_penduduk', $data_penduduk);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Data Penduduk berhasil ditambahkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('langganan/cak_layanan/data_penduduk?tahun=' . $tahun);
            }
        }
    }

    // public function input_jml_pddk($tahun, $total_penduduk)
    // {
    //     date_default_timezone_set('Asia/Jakarta');
    //     if ($total_penduduk == 0) {
    //         $this->session->set_flashdata(
    //             'info',
    //             '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    //                     <strong>Gagal,</strong> Data Belum ada! Tidak dapat menambahkan data.
    //                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
    //                     </button>
    //                   </div>'
    //         );
    //         redirect('langganan/cak_layanan/data_penduduk?tahun=' . $tahun);
    //         return;
    //     }

    //     // Cek apakah data sudah ada di database
    //     $this->db->where('tahun_data', $tahun);
    //     $this->db->where('nama_layanan', 'Jumlah Penduduk');
    //     $query = $this->db->get('ek_cak_layanan');

    //     if ($query->num_rows() > 0) {
    //         // Jika data sudah ada, tampilkan pesan peringatan
    //         $this->session->set_flashdata(
    //             'info',
    //             '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    //                     <strong>Gagal,</strong> Data sudah ada! Tidak dapat menambahkan data yang sama.
    //                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
    //                     </button>
    //                   </div>'
    //         );
    //         redirect('langganan/cak_layanan/data_penduduk?tahun=' . $tahun);
    //     } else {
    //         // Jika belum ada, lakukan insert
    //         $data = [
    //             'tahun_data' => $tahun,
    //             'nama_layanan' => 'Jumlah Penduduk',
    //             'jumlah_layanan' => $total_penduduk,
    //             'created_at' => date('Y-m-d H:i:s'),
    //             'created_by' => $this->session->userdata('nama_lengkap')
    //         ];

    //         $this->db->insert('ek_cak_layanan', $data);
    //         $this->session->set_flashdata(
    //             'info',
    //             '<div class="alert alert-primary alert-dismissible fade show" role="alert">
    //                     <strong>Sukses,</strong> Data berhasil disimpan ke Sistem!
    //                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
    //                     </button>
    //                   </div>'
    //         );
    //     }
    //     redirect('langganan/cak_layanan/data_penduduk?tahun=' . $tahun);
    // }

    // akhir data penduduk

    // data pelanggan
    public function data_pelanggan()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session', $get_tahun);
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'DATA PELANGGAN AKTIF DAN NON AKTIF';
        $data['data_pelanggan'] = $this->Model_langgan->get_data_pelanggan($tahun);

        if ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('langganan/view_data_pelanggan', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('langganan/view_data_pelanggan', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('langganan/view_data_pelanggan', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('langganan/view_data_pelanggan', $data);
            $this->load->view('templates/footer');
        }
    }

    public function input_data_pelanggan()
    {
        // $tahun = $this->session->userdata('tahun_session');
        $tahun = $this->input->post('tahun_data');
        date_default_timezone_set('Asia/Jakarta');
        $this->form_validation->set_rules('id_kec', 'Nama Kecamatan', 'required|trim');
        $this->form_validation->set_rules('n_aktif_dom', 'Tidak Aktif Domestik', 'required|trim');
        $this->form_validation->set_rules('n_aktif_n_dom', 'Tidak Aktif Non Domestik', 'required|trim');
        $this->form_validation->set_rules('rt_dom', 'Pelanggan Rumah Tangga', 'required|trim');
        $this->form_validation->set_rules('sosial_n_dom', 'Pelanggan Sosial', 'required|trim');
        $this->form_validation->set_rules('inst_n_dom', 'Pelanggan Instansi', 'required|trim');
        // $this->form_validation->set_rules('k2_n_dom', 'Pelanggan Khusus', 'required|trim');
        $this->form_validation->set_rules('niaga_dom', 'Pelanggan Niaga Domestik', 'required|trim');
        $this->form_validation->set_rules('niaga_n_dom', 'Pelanggan Niaga Non Domestik', 'required|trim');
        $this->form_validation->set_rules('tahun_data', 'Tahun Data', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Data Pelanggan';
            $data['kec'] = $this->Model_langgan->get_kec();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('langganan/view_input_pelanggan', $data);
            $this->load->view('templates/footer');
        } else {
            $id_kec = $this->input->post('id_kec');
            $n_aktif_dom = $this->input->post('n_aktif_dom');
            $rt_dom = $this->input->post('rt_dom');
            $niaga_dom = $this->input->post('niaga_dom');
            $sl_kom_dom = $this->input->post('sl_kom_dom');
            $unit_kom_dom = $this->input->post('unit_kom_dom');
            $sl_hu_dom = $this->input->post('sl_hu_dom');
            $n_aktif_n_dom = $this->input->post('n_aktif_n_dom');
            $sosial_n_dom = $this->input->post('sosial_n_dom');
            $niaga_n_dom = $this->input->post('niaga_n_dom');
            $ind_n_dom = $this->input->post('ind_n_dom');
            $inst_n_dom = $this->input->post('inst_n_dom');
            $k2_n_dom = $this->input->post('k2_n_dom');
            $lain_n_dom = $this->input->post('lain_n_dom');
            $tahun_data = $this->input->post('tahun_data');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_pelanggan = [
                'id_kec' => $id_kec,
                'tahun_data' => $tahun_data,
                'n_aktif_dom' => $n_aktif_dom,
                'rt_dom' => $rt_dom,
                'niaga_dom' => $niaga_dom,
                'sl_kom_dom' => $sl_kom_dom,
                'unit_kom_dom' => $unit_kom_dom,
                'sl_hu_dom' => $sl_hu_dom,
                'jiwa_dom' => intval($sl_hu_dom) * 100,
                'n_aktif_n_dom' => $n_aktif_n_dom,
                'sosial_n_dom' => $sosial_n_dom,
                'niaga_n_dom' => $niaga_n_dom,
                'ind_n_dom' => $ind_n_dom,
                'inst_n_dom' => $inst_n_dom,
                'k2_n_dom' => $k2_n_dom,
                'lain_n_dom' => $lain_n_dom,
                'created_by' => $created_by,
                'created_at' => $created_at
            ];

            $query = $this->db->get_where('ek_data_pelanggan', [
                'tahun_data' => $tahun,
                'id_kec'  => $id_kec
            ]);

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Data Pelanggan sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('langganan/cak_layanan/data_pelanggan?tahun=' . $tahun);
                return false;
            } else {
                $this->Model_langgan->input_data_pelanggan('ek_data_pelanggan', $data_pelanggan);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Data Penduduk berhasil ditambahkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('langganan/cak_layanan/data_pelanggan?tahun=' . $tahun);
            }
        }
    }

    // akhir data pelanggan


    // public function edit_aduan($id_ek_aduan)
    // {
    //     $tahun = $this->session->userdata('tahun_session');
    //     date_default_timezone_set('Asia/Jakarta');
    //     $data['title'] = 'Edit Data Pengaduan';
    //     $data['aduan'] = $this->Model_langgan->get_id_pengaduan($id_ek_aduan);

    //     if (!$data['aduan']) {
    //         show_404(); // Jika data tidak ditemukan, tampilkan halaman 404
    //     }

    //     if ($this->session->userdata('bagian') == 'Langgan') {
    //         $this->load->view('templates/header', $data);
    //         $this->load->view('templates/navbar');
    //         $this->load->view('templates/sidebar_pelihara');
    //         $this->load->view('langganan/view_edit_aduan', $data);
    //         $this->load->view('templates/footer');
    //     } else {
    //         $this->load->view('templates/header', $data);
    //         $this->load->view('templates/navbar');
    //         $this->load->view('templates/sidebar');
    //         $this->load->view('langganan/view_edit_aduan', $data);
    //         $this->load->view('templates/footer');
    //     }
    // }

    // public function update_aduan()
    // {
    //     $tahun = $this->session->userdata('tahun_session');
    //     date_default_timezone_set('Asia/Jakarta');

    //     $id_ek_aduan = $this->input->post('id_ek_aduan');
    //     $jumlah_aduan = $this->input->post('jumlah_aduan');
    //     $jumlah_aduan_ya = $this->input->post('jumlah_aduan_ya');
    //     $jumlah_aduan_tidak = $this->input->post('jumlah_aduan_tidak');
    //     $modified_by = $this->session->userdata('nama_lengkap');
    //     $modified_at = date('Y-m-d H:i:s');

    //     $data_aduan = [
    //         'jumlah_aduan' => $jumlah_aduan,
    //         'jumlah_aduan_ya' => $jumlah_aduan_ya,
    //         'jumlah_aduan_tidak' => $jumlah_aduan_tidak,
    //         'modified_by' => $modified_by,
    //         'modified_at' => $modified_at
    //     ];

    //     $this->Model_langgan->update_aduan($id_ek_aduan, $data_aduan);
    //     $this->session->set_flashdata(
    //         'info',
    //         '<div class="alert alert-primary alert-dismissible fade show" role="alert">
    //             <strong>Sukses!</strong> Data Pengaduan berhasil diedit.
    //             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    //         </div>'
    //     );
    //     $alamat = 'langganan/data_pengaduan?tahun=' . $tahun;
    //     redirect($alamat);
    // }
}
