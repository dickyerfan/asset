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
        // $data['cak_layanan'] = $this->Model_langgan->get_cak_layanan($tahun);

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

    public function cetak_aduan()
    {
        $tahun = $this->session->userdata('tahun_session');

        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'DATA PENGADUAN';
        $data['pengaduan'] = $this->Model_langgan->get_pengaduan($tahun);

        $this->pdf->setPaper('folio', 'portrait');
        $this->pdf->filename = "pengaduan-{$tahun}.pdf";
        $this->pdf->generate('langganan/cetak_pengaduan_pdf', $data);
    }


    public function input_aduan()
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');

        $this->form_validation->set_rules('jenis_aduan[]', 'Jenis Aduan', 'required', [
            'required' => 'Harap pilih minimal satu %s.'
        ]);
        $this->form_validation->set_rules('tgl_aduan', 'Tanggal pengaduan', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Data Pengaduan';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('langganan/view_input_aduan', $data);
            $this->load->view('templates/footer');
        } else {
            $tgl_aduan = $this->input->post('tgl_aduan');
            $bulan = date('m', strtotime($tgl_aduan)); // Ambil bulan dari tanggal
            $tahun_input = date('Y', strtotime($tgl_aduan)); // Ambil tahun dari tanggal
            $jenis_aduan = $this->input->post('jenis_aduan'); // Array dari checkbox
            $jumlah_aduan = $this->input->post('jumlah_aduan'); // Array dari input jumlah
            $jumlah_aduan_ya = $this->input->post('jumlah_aduan_ya'); // Array dari input jumlah
            $jumlah_aduan_tidak = $this->input->post('jumlah_aduan_tidak'); // Array dari input jumlah
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_aduan = [];
            $duplikasi_terdeteksi = false;

            $map_aduan = [
                'teknis' => 'Teknis',
                'pelayanan' => 'Pelayanan',
                'rekening_air' => 'Rekening Air'
            ];

            foreach ($jenis_aduan as $aduan_slug) {
                $aduan_label = $map_aduan[$aduan_slug] ?? $aduan_slug;

                if (isset($jumlah_aduan[$aduan_slug]) && $jumlah_aduan[$aduan_slug] !== '') {
                    $cek_duplikasi = $this->Model_langgan->cek_duplikasi_aduan($bulan, $tahun_input, $aduan_label);

                    if ($cek_duplikasi) {
                        $duplikasi_terdeteksi = true;
                        break;
                    }

                    $data_aduan[] = [
                        'jenis_aduan' => $aduan_label,
                        'tgl_aduan' => $tgl_aduan,
                        'jumlah_aduan' => $jumlah_aduan[$aduan_slug],
                        'jumlah_aduan_ya' => $jumlah_aduan_ya[$aduan_slug] ?? 0,
                        'jumlah_aduan_tidak' => $jumlah_aduan_tidak[$aduan_slug] ?? 0,
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
            } elseif (!empty($data_aduan)) {
                $this->Model_langgan->input_tambah_aduan('ek_pengaduan', $data_aduan);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data penambahan Pengaduan berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
                );
            } else {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Pastikan jumlah penambahan Pengaduan diisi untuk setiap bagian yang dipilih.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
                );
            }

            $alamat = 'langganan/data_pengaduan?tahun=' . $tahun;
            redirect($alamat);
        }
    }

    public function edit_aduan($id_ek_aduan)
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');
        $data['title'] = 'Edit Data Pengaduan';
        $data['aduan'] = $this->Model_langgan->get_id_pengaduan($id_ek_aduan);

        if (!$data['aduan']) {
            show_404(); // Jika data tidak ditemukan, tampilkan halaman 404
        }

        if ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('langganan/view_edit_aduan', $data);
            $this->load->view('templates/footer');
        } else {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('langganan/view_edit_aduan', $data);
            $this->load->view('templates/footer');
        }
    }

    public function update_aduan()
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');

        $id_ek_aduan = $this->input->post('id_ek_aduan');
        $jumlah_aduan = $this->input->post('jumlah_aduan');
        $jumlah_aduan_ya = $this->input->post('jumlah_aduan_ya');
        $jumlah_aduan_tidak = $this->input->post('jumlah_aduan_tidak');
        $modified_by = $this->session->userdata('nama_lengkap');
        $modified_at = date('Y-m-d H:i:s');

        $data_aduan = [
            'jumlah_aduan' => $jumlah_aduan,
            'jumlah_aduan_ya' => $jumlah_aduan_ya,
            'jumlah_aduan_tidak' => $jumlah_aduan_tidak,
            'modified_by' => $modified_by,
            'modified_at' => $modified_at
        ];

        $this->Model_langgan->update_aduan($id_ek_aduan, $data_aduan);
        $this->session->set_flashdata(
            'info',
            '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Data Pengaduan berhasil diedit.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>'
        );
        $alamat = 'langganan/data_pengaduan?tahun=' . $tahun;
        redirect($alamat);
    }
}
