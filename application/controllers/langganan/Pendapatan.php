<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pendapatan extends CI_Controller
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
        $data['title'] = 'PENDAPATAN, TARIF DAN HARGA POKOK';
        $data['pendapatan'] = $this->Model_langgan->get_pendapatan($tahun);
        $data['kel_tarif'] = $this->db->get('kel_tarif')->result();

        $total_sr = 0;
        $total_vol = 0;
        $total_by_admin = 0;
        $total_by_peml = 0;
        $total_harga_air = 0;
        $total_tagihan = 0;
        $pendapatan_air_lainnya = 0;
        $rata = 0;

        foreach ($data['pendapatan'] as $row) {
            $total_sr += $row->rek_air;
            $total_vol += $row->volume;
            $total_by_admin += $row->by_admin;
            $total_by_peml += $row->jas_pem;
            if ($row->id_kel_tarif != '12' && !is_null($row->id_kel_tarif)) {
                $total_harga_air += $row->harga_air;
            } else {
                $pendapatan_air_lainnya += $row->harga_air;
            }

            $tagihan = $row->by_admin + $row->jas_pem + $row->harga_air;
            $total_tagihan += $tagihan;

            $rata = $total_tagihan / $total_vol;
        }

        $data['total_sr'] = $total_sr;
        $data['total_vol'] = $total_vol;
        $data['total_by_admin'] = $total_by_admin;
        $data['total_by_peml'] = $total_by_peml;
        $data['total_harga_air'] = $total_harga_air;
        $data['total_tagihan'] = $total_tagihan;
        $data['pendapatan_air_lainnya'] = $pendapatan_air_lainnya;
        $data['rata'] = $rata;

        if ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('langganan/view_pendapatan', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('langganan/view_pendapatan', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('langganan/view_pendapatan', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('langganan/view_pendapatan', $data);
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


    public function input_tangki_air()
    {
        $tahun = $this->input->post('tahun_data');
        date_default_timezone_set('Asia/Jakarta');

        $this->form_validation->set_rules('tahun_data', 'Tahun Data', 'required|trim');
        $this->form_validation->set_rules('harga_air', 'Harga Air', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Tangki Air';
            $data['kel_tarif'] = $this->Model_langgan->get_tarif();
            if ($this->session->userdata('bagian') == 'Langgan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_langgan');
                $this->load->view('langganan/view_input_tangki_air)', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Administrator') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('langganan/view_input_tangki_air)', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('langganan/view_input_tangki_air)', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $harga_air = $this->input->post('harga_air');
            $tahun_data = $this->input->post('tahun_data');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_pendapatan = [
                'id_kel_tarif' => 12,
                'tahun_data' => $tahun_data,
                'harga_air' => $harga_air,
                'created_by' => $created_by,
                'created_at' => $created_at
            ];

            // Cek apakah tahun dan id kec dan id kelompok tarif sudah ada di database
            $this->db->where('tahun_data', $tahun);
            $this->db->where('id_kel_tarif', $id_kel_tarif);
            $query = $this->db->get('ek_pendapatan');

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Data Tangki Air sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('langganan/pendapatan?tahun=' . $tahun);
                return false;
            } else {
                $this->Model_langgan->input_pendapatan('ek_pendapatan', $data_pendapatan);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Data Tangki Air berhasil ditambahkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('langganan/pendapatan?tahun=' . $tahun);
            }
        }
    }
    public function input_pendapatan()
    {
        $tahun = $this->input->post('tahun_data');
        date_default_timezone_set('Asia/Jakarta');

        $this->form_validation->set_rules('id_kel_tarif', 'Kelompok Tarif', 'required|trim');
        $this->form_validation->set_rules('tahun_data', 'Tahun Data', 'required|trim');
        $this->form_validation->set_rules('rek_air', 'Jumlah Rekening', 'required|trim');
        $this->form_validation->set_rules('volume', 'Volume', 'required|trim');
        $this->form_validation->set_rules('jas_pem', 'Jasa Pemeliharaan', 'required|trim');
        $this->form_validation->set_rules('by_admin', 'Biaya Admin', 'required|trim');
        $this->form_validation->set_rules('harga_air', 'Harga Air', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Pendapatan ';
            $data['kel_tarif'] = $this->Model_langgan->get_tarif();
            if ($this->session->userdata('bagian') == 'Langgan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_langgan');
                $this->load->view('langganan/view_input_pendapatan', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Administrator') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('langganan/view_input_pendapatan', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('langganan/view_input_pendapatan', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $id_kel_tarif = $this->input->post('id_kel_tarif');
            $rek_air = $this->input->post('rek_air');
            $volume = $this->input->post('volume');
            $jas_pem = $this->input->post('jas_pem');
            $by_admin = $this->input->post('by_admin');
            $harga_air = $this->input->post('harga_air');
            $tahun_data = $this->input->post('tahun_data');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_pendapatan = [
                'id_kel_tarif' => $id_kel_tarif,
                'tahun_data' => $tahun_data,
                'rek_air' => $rek_air,
                'volume' => $volume,
                'jas_pem' => $jas_pem,
                'by_admin' => $by_admin,
                'harga_air' => $harga_air,
                'created_by' => $created_by,
                'created_at' => $created_at
            ];

            // Cek apakah tahun dan id kec dan id kelompok tarif sudah ada di database
            $this->db->where('tahun_data', $tahun);
            $this->db->where('id_kel_tarif', $id_kel_tarif);
            $query = $this->db->get('ek_pendapatan');

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Data pendapatan sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('langganan/pendapatan?tahun=' . $tahun);
                return false;
            } else {
                $this->Model_langgan->input_pendapatan('ek_pendapatan', $data_pendapatan);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Data pendapatan berhasil ditambahkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('langganan/pendapatan?tahun=' . $tahun);
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
