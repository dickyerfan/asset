<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rincian_pendapatan extends CI_Controller
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
        $data['title'] = 'RINCIAN PENDAPATAN PER KECAMATAN DOMESTIK';
        $data['rincian'] = $this->Model_langgan->get_rincian($tahun);
        $data['kecamatan'] = $this->db->order_by('nama_kec', 'ASC')->get('ek_kecamatan')->result();


        // DOMESTIK: Kategori yang termasuk domestik
        $kategori_domestik = ['SOSIAL A', 'RUMAH TANGGA A', 'RUMAH TANGGA B', 'RUMAH TANGGA C', 'NIAGA A'];
        $data['total_domestik'] = $this->hitung_total_rincian($data['rincian'], $kategori_domestik);

        // Total Non Domestik (ambil dari database juga)
        $rincian_all = $this->Model_langgan->get_rincian($tahun); // jika perlu panggil ulang
        $kategori_non_domestik = ['SOSIAL B', 'INSTANSI PEM DESA', 'TNI/POLRI', 'INSTANSI PEM KAB', 'KHUSUS', 'NIAGA B']; // sesuaikan ini
        $data['total_non_domestik'] = $this->hitung_total_rincian($rincian_all, $kategori_non_domestik);

        // Total Domestik
        $data['domestik'] = [
            'sr' => $data['total_domestik']['sr'],
            'vol' => $data['total_domestik']['vol'],
            'rp' => $data['total_domestik']['rp'],
        ];

        // Total Non Domestik
        $data['non_domestik'] = [
            'sr' => $data['total_non_domestik']['sr'],
            'vol' => $data['total_non_domestik']['vol'],
            'rp' => $data['total_non_domestik']['rp'],
        ];

        // Total gabungan
        $data['total_semua'] = [
            'sr' => $data['total_domestik']['sr'] + $data['total_non_domestik']['sr'],
            'vol' => $data['total_domestik']['vol'] + $data['total_non_domestik']['vol'],
            'rp' => $data['total_domestik']['rp'] + $data['total_non_domestik']['rp'],
        ];

        if ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('langganan/view_rincian_pend', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('langganan/view_rincian_pend', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('langganan/view_rincian_pend', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('langganan/view_rincian_pend', $data);
            $this->load->view('templates/footer');
        }
    }

    public function cetak_rincian()
    {
        $tahun = $this->session->userdata('tahun_session');

        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'RINCIAN PENDAPATAN PER KECAMATAN';
        $data['rincian'] = $this->Model_langgan->get_rincian($tahun);
        $data['kecamatan'] = $this->db->order_by('nama_kec', 'ASC')->get('ek_kecamatan')->result();

        $this->pdf->setPaper('folio', 'landscape');
        $this->pdf->filename = "rincian-{$tahun}.pdf";
        $this->pdf->generate('langganan/cetak_rincian_pdf', $data);
    }


    public function input_rincian_dom()
    {
        $tahun = $this->input->post('tahun_data');
        date_default_timezone_set('Asia/Jakarta');

        $this->form_validation->set_rules('id_kec', 'Nama Kecamatan', 'required|trim');
        $this->form_validation->set_rules('id_kel_tarif', 'Kelompok Tarif', 'required|trim');
        $this->form_validation->set_rules('tahun_data', 'Tahun Data', 'required|trim');
        $this->form_validation->set_rules('jumlah_sr', 'Jumlah SR', 'required|trim');
        $this->form_validation->set_rules('volume', 'Volume', 'required|trim');
        $this->form_validation->set_rules('rupiah', 'Rupiah', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Data Penduduk';
            $data['kec'] = $this->Model_langgan->get_kec();
            $data['tarif_dom'] = $this->Model_langgan->get_tarif_dom();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('langganan/view_input_rincian_dom', $data);
            $this->load->view('templates/footer');
        } else {
            $id_kec = $this->input->post('id_kec');
            $id_kel_tarif = $this->input->post('id_kel_tarif');
            $jumlah_sr = $this->input->post('jumlah_sr');
            $volume = $this->input->post('volume');
            $rupiah = $this->input->post('rupiah');
            $tahun_data = $this->input->post('tahun_data');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_rincian = [
                'id_kec' => $id_kec,
                'id_kel_tarif' => $id_kel_tarif,
                'tahun_data' => $tahun_data,
                'jumlah_sr' => $jumlah_sr,
                'volume' => $volume,
                'rupiah' => $rupiah,
                'created_by' => $created_by,
                'created_at' => $created_at
            ];

            // Cek apakah tahun dan id kec dan id kelompok tarif sudah ada di database
            $this->db->where('tahun_data', $tahun);
            $this->db->where('id_kec', $id_kec);
            $this->db->where('id_kel_tarif', $id_kel_tarif);
            $query = $this->db->get('ek_rincian_pendapatan');

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Data Rincian Pendapatan sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('langganan/rincian_pendapatan?tahun=' . $tahun);
                return false;
            } else {
                $this->Model_langgan->input_rincian('ek_rincian_pendapatan', $data_rincian);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Data Rincian Pendapatan berhasil ditambahkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('langganan/rincian_pendapatan?tahun=' . $tahun);
            }
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


    public function non_domestik()
    {
        $get_tahun = $this->input->get('tahun');
        $tahun = substr($get_tahun, 0, 4);

        if (empty($get_tahun)) {
            $tahun = date('Y');
        } else {
            $this->session->set_userdata('tahun_session_n_dom', $get_tahun);
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'RINCIAN PENDAPATAN PER KECAMATAN NON DOMESTIK';
        $data['rincian'] = $this->Model_langgan->get_rincian($tahun);
        $data['kecamatan'] = $this->db->order_by('nama_kec', 'ASC')->get('ek_kecamatan')->result();

        // DOMESTIK: Kategori yang termasuk domestik
        $kategori_domestik = ['SOSIAL A', 'RUMAH TANGGA A', 'RUMAH TANGGA B', 'RUMAH TANGGA C', 'NIAGA A'];
        $data['total_domestik'] = $this->hitung_total_rincian($data['rincian'], $kategori_domestik);

        // Total Non Domestik (ambil dari database juga)
        $rincian_all = $this->Model_langgan->get_rincian($tahun); // jika perlu panggil ulang
        $kategori_non_domestik = ['SOSIAL B', 'INSTANSI PEM DESA', 'TNI/POLRI', 'INSTANSI PEM KAB', 'KHUSUS', 'NIAGA B']; // sesuaikan ini
        $data['total_non_domestik'] = $this->hitung_total_rincian($rincian_all, $kategori_non_domestik);

        // Total Domestik
        $data['domestik'] = [
            'sr' => $data['total_domestik']['sr'],
            'vol' => $data['total_domestik']['vol'],
            'rp' => $data['total_domestik']['rp'],
        ];

        // Total Non Domestik
        $data['non_domestik'] = [
            'sr' => $data['total_non_domestik']['sr'],
            'vol' => $data['total_non_domestik']['vol'],
            'rp' => $data['total_non_domestik']['rp'],
        ];

        // Total gabungan
        $data['total_semua'] = [
            'sr' => $data['total_domestik']['sr'] + $data['total_non_domestik']['sr'],
            'vol' => $data['total_domestik']['vol'] + $data['total_non_domestik']['vol'],
            'rp' => $data['total_domestik']['rp'] + $data['total_non_domestik']['rp'],
        ];

        if ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('langganan/view_rincian_pend_n_dom', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('langganan/view_rincian_pend_n_dom', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('langganan/view_rincian_pend_n_dom', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('langganan/view_rincian_pend_n_dom', $data);
            $this->load->view('templates/footer');
        }
    }

    public function cetak_rincian_n_dom()
    {
        $tahun = $this->session->userdata('tahun_session_n_dom');

        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session_n_dom');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'RINCIAN PENDAPATAN PER KECAMATAN NON DOMESTIK';
        $data['rincian'] = $this->Model_langgan->get_rincian($tahun);
        $data['kecamatan'] = $this->db->order_by('nama_kec', 'ASC')->get('ek_kecamatan')->result();

        $this->pdf->setPaper('folio', 'landscape');
        $this->pdf->filename = "rincian_n_dom-{$tahun}.pdf";
        $this->pdf->generate('langganan/cetak_rincian_n_dom_pdf', $data);
    }

    public function input_rincian_n_dom()
    {
        $tahun = $this->input->post('tahun_data');
        date_default_timezone_set('Asia/Jakarta');

        $this->form_validation->set_rules('id_kec', 'Nama Kecamatan', 'required|trim');
        $this->form_validation->set_rules('id_kel_tarif', 'Kelompok Tarif', 'required|trim');
        $this->form_validation->set_rules('tahun_data', 'Tahun Data', 'required|trim');
        $this->form_validation->set_rules('jumlah_sr', 'Jumlah SR', 'required|trim');
        $this->form_validation->set_rules('volume', 'Volume', 'required|trim');
        $this->form_validation->set_rules('rupiah', 'Rupiah', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Data Penduduk';
            $data['kec'] = $this->Model_langgan->get_kec();
            $data['tarif_n_dom'] = $this->Model_langgan->get_tarif_n_dom();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('langganan/view_input_rincian_n_dom', $data);
            $this->load->view('templates/footer');
        } else {
            $id_kec = $this->input->post('id_kec');
            $id_kel_tarif = $this->input->post('id_kel_tarif');
            $jumlah_sr = $this->input->post('jumlah_sr');
            $volume = $this->input->post('volume');
            $rupiah = $this->input->post('rupiah');
            $tahun_data = $this->input->post('tahun_data');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_rincian = [
                'id_kec' => $id_kec,
                'id_kel_tarif' => $id_kel_tarif,
                'tahun_data' => $tahun_data,
                'jumlah_sr' => $jumlah_sr,
                'volume' => $volume,
                'rupiah' => $rupiah,
                'created_by' => $created_by,
                'created_at' => $created_at
            ];

            // Cek apakah tahun dan id kec dan id kelompok tarif sudah ada di database
            $this->db->where('tahun_data', $tahun);
            $this->db->where('id_kec', $id_kec);
            $this->db->where('id_kel_tarif', $id_kel_tarif);
            $query = $this->db->get('ek_rincian_pendapatan');

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Data Rincian Pendapatan sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('langganan/rincian_pendapatan/non_domestik?tahun=' . $tahun);
                return false;
            } else {
                $this->Model_langgan->input_rincian('ek_rincian_pendapatan', $data_rincian);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Data Rincian Pendapatan berhasil ditambahkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('langganan/rincian_pendapatan/non_domestik?tahun=' . $tahun);
            }
        }
    }

    private function hitung_total_rincian($rincian, $kategori_terpakai)
    {
        $total = ['sr' => 0, 'vol' => 0, 'rp' => 0];

        foreach ($rincian as $row) {
            if (in_array($row->kel_tarif, $kategori_terpakai)) {
                $total['sr'] += $row->jumlah_sr;
                $total['vol'] += $row->volume;
                $total['rp'] += $row->rupiah;
            }
        }

        return $total;
    }
}
