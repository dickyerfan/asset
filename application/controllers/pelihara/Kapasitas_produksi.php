<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kapasitas_produksi extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_pelihara');
        $this->load->model('Model_evkin');
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
        if ($bagian != 'Pemeliharaan' && $bagian != 'Publik' && $bagian != 'Administrator' && $bagian != 'Keuangan') {
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
        $data['title'] = 'LAPORAN PRODUKSI';
        $data['kapasitas_produksi'] = $this->Model_pelihara->get_kapasitas_produksi($tahun);

        $total_kap_pasang = 0;
        $total_terpasang = 0;
        $total_tidak_manfaat = 0;
        $total_volume_produksi = 0;
        $total_kap_riil = 0;
        $total_kap_menganggur = 0;
        foreach ($data['kapasitas_produksi'] as $row) {
            $kap_pasang = $row->kap_pasang;
            $terpasang = $row->terpasang;
            $tidak_manfaat = $row->tidak_manfaat;
            $volume_produksi = $row->volume_produksi;
            $kap_riil = $terpasang - $tidak_manfaat;
            $kap_menganggur = $kap_riil - $volume_produksi;

            $total_kap_pasang += $kap_pasang;
            $total_terpasang += $terpasang;
            $total_tidak_manfaat += $tidak_manfaat;
            $total_volume_produksi += $volume_produksi;
            $total_kap_riil += $kap_riil;
            $total_kap_menganggur += $kap_menganggur;
        }

        $data['kap_pasang'] = $total_kap_pasang;
        $data['terpasang'] = $total_terpasang;
        $data['tidak_manfaat'] = $total_tidak_manfaat;
        $data['volume_produksi'] = $total_volume_produksi;
        $data['kap_riil'] = $total_kap_riil;
        $data['kap_menganggur'] = $total_kap_menganggur;

        $pendapatan = $this->Model_evkin->hitung_pendapatan($tahun);
        $data['total_vol'] = $pendapatan['total_vol'];

        $data['total_nrw_air'] = $data['volume_produksi'] - $data['total_vol'];

        if ($data['volume_produksi'] != 0) {
            $data['persen_nrw_air'] = $data['total_nrw_air'] / $data['volume_produksi'] * 100;
        } else {
            $data['persen_nrw_air'] = 0; // Atau nilai lain yang sesuai
        }


        if ($this->session->userdata('bagian') == 'Pemeliharaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('pelihara/view_kapasitas_produksi', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('pelihara/view_kapasitas_produksi', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('pelihara/view_kapasitas_produksi', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('pelihara/view_kapasitas_produksi', $data);
            $this->load->view('templates/footer');
        }
    }

    public function cetak_kapasitas_produksi()
    {
        $tahun = $this->session->userdata('tahun_session');

        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'KAPASITAS PRODUKSI';
        $data['kapasitas_produksi'] = $this->Model_pelihara->get_kapasitas_produksi($tahun);

        $this->pdf->setPaper('folio', 'portrait');
        $this->pdf->filename = "kapasitas_prod-{$tahun}.pdf";
        $this->pdf->generate('pelihara/cetak_kapasitas_prod_pdf', $data);
    }


    public function input_kp()
    {
        $tahun = $this->input->post('tahun_kp');
        date_default_timezone_set('Asia/Jakarta');
        $this->form_validation->set_rules('id_bagian', 'Nama UPK', 'required|trim');
        $this->form_validation->set_rules('kap_pasang', 'Kapasitas Terpasang', 'required|trim|numeric');
        $this->form_validation->set_rules('terpasang', 'Terpasang', 'required|trim');
        $this->form_validation->set_rules('tidak_manfaat', 'Tidak Dimanfaatkan', 'required|trim');
        $this->form_validation->set_rules('volume_produksi', 'Volume Produksi', 'required|trim');
        $this->form_validation->set_rules('tahun_kp', 'Tahun', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Data Tekanan Air';
            $data['bagian'] = $this->Model_pelihara->get_bagian();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('pelihara/view_input_kapasitas_produksi', $data);
            $this->load->view('templates/footer');
        } else {
            $id_bagian = $this->input->post('id_bagian');
            $kap_pasang = $this->input->post('kap_pasang');
            $tidak_manfaat = $this->input->post('tidak_manfaat');
            $terpasang = $this->input->post('terpasang');
            $volume_produksi = $this->input->post('volume_produksi');
            $tahun_kp = $this->input->post('tahun_kp');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_kp = [
                'id_bagian' => $id_bagian,
                'tahun_kp' => $tahun_kp,
                'kap_pasang' => $kap_pasang,
                'tidak_manfaat' => $tidak_manfaat,
                'terpasang' => $terpasang,
                'volume_produksi' => $volume_produksi,
                'created_by' => $created_by,
                'created_at' => $created_at
            ];

            // Cek apakah tahun dan id bagian sudah ada di database
            $this->db->where('tahun_kp', $tahun);
            $this->db->where('id_bagian', $id_bagian);
            $query = $this->db->get('ek_kapasitas_prod');

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Data kapasitas Produksi sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('pelihara/kapasitas_produksi?tahun=' . $tahun);
                return false;
            } else {
                $this->Model_pelihara->input_kp('ek_kapasitas_prod', $data_kp);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Data kapasitas Produksi berhasil ditambahkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('pelihara/kapasitas_produksi?tahun=' . $tahun);
            }
        }
    }

    public function edit_kp($id_ek_kp)
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');
        $data['title'] = 'Edit Data Kapasitas Produksi';
        $data['kapasitas_edit'] = $this->Model_pelihara->get_kapasitas_by_id($id_ek_kp);
        $data['bagian_upk'] = $this->Model_pelihara->get_bagian();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar_pelihara');
        $this->load->view('pelihara/view_edit_kapasitas_produksi', $data);
        $this->load->view('templates/footer');
    }

    public function update_kp()
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');

        $id_ek_kp = $this->input->post('id_ek_kp');
        $id_bagian = $this->input->post('id_bagian');
        $kap_pasang = $this->input->post('kap_pasang');
        $terpasang = $this->input->post('terpasang');
        $tidak_manfaat = $this->input->post('tidak_manfaat');
        $volume_produksi = $this->input->post('volume_produksi');
        $tahun_kp = $this->input->post('tahun_kp');
        $modified_by = $this->session->userdata('nama_lengkap');
        $modified_at = date('Y-m-d H:i:s');

        $data_kp = [
            'id_bagian' => $id_bagian,
            'tahun_kp' => $tahun_kp,
            'kap_pasang' => $kap_pasang,
            'terpasang' => $terpasang,
            'tidak_manfaat' => $tidak_manfaat,
            'volume_produksi' => $volume_produksi,
            'modified_by' => $modified_by,
            'modified_at' => $modified_at
        ];

        $this->Model_pelihara->update_kapasitas($id_ek_kp, $data_kp);
        $this->session->set_flashdata(
            'info',
            '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Data Kapasitas Produksi berhasil diedit.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>'
        );
        redirect('pelihara/kapasitas_produksi?tahun=' . $tahun);
    }
}
