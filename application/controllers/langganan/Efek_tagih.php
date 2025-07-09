<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Efek_tagih extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_langgan');
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
        $data['title'] = 'PERHITUNGAN EFEKTIVITAS PENAGIHAN';
        $data['title2'] = 'DAFTAR EFEKTIVITAS PENAGIHAN';
        $data['title3'] = 'DAFTAR SISA PIUTANG';
        $data['efek'] = $this->Model_langgan->get_efek_tagih($tahun);
        $data['sisa_piu'] = $this->Model_langgan->get_sisa_piu($tahun);
        $data['bagian_upk'] = $this->db->where('status_evkin', 1)->get('bagian_upk')->result();

        // hitung nilai penerimaan
        $kategori = [
            '1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr',
            '5' => 'Mei', '6' => 'Jun', '7' => 'Jul', '8' => 'Ags',
            '9' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
        ];

        $data_rinci = [];
        foreach ($data['bagian_upk'] as $upk) {
            $nama_upk = $upk->nama_bagian;
            $data_rinci[$nama_upk] = [];
            foreach (array_keys($kategori) as $bulan) {
                $data_rinci[$nama_upk][$bulan] = ['sr' => 0, 'rp' => 0];
            }
        }

        foreach ($data['efek'] as $row) {
            $upk = $row->nama_bagian;
            $bulan = (string) $row->bulan_data;

            if (isset($data_rinci[$upk][$bulan])) {
                $data_rinci[$upk][$bulan]['sr'] += $row->jumlah_sr;
                $data_rinci[$upk][$bulan]['rp'] += $row->rupiah;
            }
        }

        $total_keseluruhan = [];
        foreach (array_keys($kategori) as $bulan) {
            $total_keseluruhan[$bulan] = ['sr' => 0, 'rp' => 0];
        }
        $total_keseluruhan['JUMLAH'] = ['sr' => 0, 'rp' => 0];

        foreach ($data_rinci as $item) {
            foreach (array_keys($kategori) as $bulan) {
                $total_keseluruhan[$bulan]['sr'] += $item[$bulan]['sr'];
                $total_keseluruhan[$bulan]['rp'] += $item[$bulan]['rp'];
            }
        }

        foreach (array_keys($kategori) as $bulan) {
            $total_keseluruhan['JUMLAH']['sr'] += $total_keseluruhan[$bulan]['sr'];
            $total_keseluruhan['JUMLAH']['rp'] += $total_keseluruhan[$bulan]['rp'];
        }
        $data['total_rp'] = $total_keseluruhan['JUMLAH']['rp'];

        // hitung drd
        $pendapatan = $this->Model_langgan->get_pendapatan($tahun);
        $total_harga_air = 0;
        $total_pa_tahun_ini = 0;
        $pendapatan_air_lainnya = 0;
        foreach ($pendapatan as $row) {
            if ($row->id_kel_tarif != '12' && !is_null($row->id_kel_tarif)) {
                $total_harga_air += $row->harga_air;
            } else {
                $pendapatan_air_lainnya += $row->harga_air;
            }
            $tagihan = $row->by_admin + $row->jas_pem + $row->harga_air;
            $total_pa_tahun_ini += $tagihan;
        }

        // hitung sisa piutang
        $data_piu = [
            '1 Bulan' => 0,
            '2 Bulan' => 0,
            '3 Bulan' => 0,
            '4 Bulan - 1 Tahun' => 0,
        ];

        foreach ($data['sisa_piu'] as $row) {
            if (isset($data_piu[$row->uraian])) {
                $data_piu[$row->uraian] = $row->rupiah;
            }
        }
        $total = array_sum($data_piu);

        $data['total_pa_tahun_ini'] = $total_pa_tahun_ini;
        $data['sisa_rek'] = $total;
        $data['rek_tagih'] = $data['total_pa_tahun_ini'] - $data['sisa_rek'] + $data['total_rp'];
        $data['persen'] = ($data['total_pa_tahun_ini'] == 0) ? 0 : round(($data['rek_tagih'] / $data['total_pa_tahun_ini']) * 100, 2);

        if ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('langganan/view_efek_tagih', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('langganan/view_efek_tagih', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('langganan/view_efek_tagih', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('langganan/view_efek_tagih', $data);
            $this->load->view('templates/footer');
        }
    }

    // public function cetak_rincian()
    // {
    //     $tahun = $this->session->userdata('tahun_session');

    //     if (empty($tahun)) {
    //         $this->session->unset_userdata('tahun_session');
    //         $tahun = date('Y');
    //     }

    //     $data['tahun_lap'] = $tahun;
    //     $data['title'] = 'RINCIAN PENDAPATAN PER KECAMATAN';
    //     $data['rincian'] = $this->Model_langgan->get_rincian($tahun);
    //     $data['kecamatan'] = $this->db->order_by('nama_kec', 'ASC')->get('ek_kecamatan')->result();

    //     $this->pdf->setPaper('folio', 'landscape');
    //     $this->pdf->filename = "rincian-{$tahun}.pdf";
    //     $this->pdf->generate('langganan/cetak_rincian_pdf', $data);
    // }


    public function input_efek_tagih()
    {
        $tahun = $this->input->post('tahun_data');
        $bulan = $this->input->post('bulan_data');
        date_default_timezone_set('Asia/Jakarta');

        $this->form_validation->set_rules('id_bagian', 'Nama UPK', 'required|trim');
        $this->form_validation->set_rules('tahun_data', 'Tahun Data', 'required|trim');
        $this->form_validation->set_rules('bulan_data', 'Bulan Data', 'required|trim');
        $this->form_validation->set_rules('jumlah_sr', 'Jumlah SR', 'required|trim');
        $this->form_validation->set_rules('rupiah', 'Rupiah', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Efektivitas Penagihan';
            $data['upk'] = $this->Model_langgan->get_bagian();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('langganan/view_input_efek_tagih', $data);
            $this->load->view('templates/footer');
        } else {
            $id_bagian = $this->input->post('id_bagian');
            $jumlah_sr = $this->input->post('jumlah_sr');
            $rupiah = $this->input->post('rupiah');
            $bulan_data = $this->input->post('bulan_data');
            $tahun_data = $this->input->post('tahun_data');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_rincian = [
                'id_bagian' => $id_bagian,
                'tahun_data' => $tahun_data,
                'bulan_data' => $bulan_data,
                'jumlah_sr' => $jumlah_sr,
                'rupiah' => $rupiah,
                'created_by' => $created_by,
                'created_at' => $created_at
            ];

            // Cek apakah tahun dan id kec dan id kelompok tarif sudah ada di database
            $this->db->where('tahun_data', $tahun);
            $this->db->where('bulan_data', $bulan);
            $this->db->where('id_bagian', $id_bagian);
            $query = $this->db->get('ek_efek_tagih');

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Data Efektifitas Penagihan sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('langganan/efek_tagih?tahun=' . $tahun);
                return false;
            } else {
                $this->Model_langgan->input_efek_tagih('ek_efek_tagih', $data_rincian);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Data Efektifitas Penagihan berhasil ditambahkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('langganan/efek_tagih?tahun=' . $tahun);
            }
        }
    }

    public function input_sisa_piu()
    {
        $tahun = $this->input->post('tahun_data');
        date_default_timezone_set('Asia/Jakarta');

        $this->form_validation->set_rules('tahun_data', 'Tahun Data', 'required|trim');
        $this->form_validation->set_rules('jumlah_sr', 'Jumlah SR', 'required|trim');
        $this->form_validation->set_rules('rupiah', 'Rupiah', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Sisa Piutang';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('langganan/view_input_sisa_piu', $data);
            $this->load->view('templates/footer');
        } else {
            $uraian = $this->input->post('uraian');
            $jumlah_sr = $this->input->post('jumlah_sr');
            $rupiah = $this->input->post('rupiah');
            $tahun_data = $this->input->post('tahun_data');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_rincian = [
                'uraian' => $uraian,
                'tahun_data' => $tahun_data,
                'jumlah_sr' => $jumlah_sr,
                'rupiah' => $rupiah,
                'created_by' => $created_by,
                'created_at' => $created_at
            ];

            // Cek apakah tahun dan id kec dan id kelompok tarif sudah ada di database
            $this->db->where('tahun_data', $tahun);
            $this->db->where('uraian', $uraian);
            $query = $this->db->get('ek_sisa_piutang');

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Data Sisa Piutang sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('langganan/efek_tagih?tahun=' . $tahun);
                return false;
            } else {
                $this->Model_langgan->input_sisa_piu('ek_sisa_piutang', $data_rincian);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Data Sisa Piutang berhasil ditambahkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('langganan/efek_tagih?tahun=' . $tahun);
            }
        }
    }

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
