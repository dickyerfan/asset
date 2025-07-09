<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Modal_ybds extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_keuangan');
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
        if ($bagian != 'Publik' && $bagian != 'Administrator' && $bagian != 'Keuangan') {
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
        $data['title'] = 'PENYERTAAN PEMERINTAH YANG BELUM DITETAPKAN STATUSNYA ' . $tahun;
        $data['modal_ybds'] = $this->Model_keuangan->get_modal_ybds($tahun);

        if ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('keuangan/view_modal_ybds', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('keuangan/view_modal_ybds', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('keuangan/view_modal_ybds', $data);
            $this->load->view('templates/footer');
        }
    }

    public function cetak_modal_ybds()
    {
        $tahun = $this->session->userdata('tahun_session');

        if (empty($tahun)) {
            $this->session->unset_userdata('tahun_session');
            $tahun = date('Y');
        }

        $data['tahun_lap'] = $tahun;
        $data['title'] = 'PENYERTAAN PEMERINTAH KABUPATEN BONDOWOSO PER 31 DESEMBER ' . $tahun;
        $data['modal_ybds'] = $this->Model_keuangan->get_modal_ybds($tahun);

        $this->pdf->setPaper('folio', 'portrait');
        $this->pdf->filename = "modal_ybds-{$tahun}.pdf";
        $this->pdf->generate('keuangan/cetak_modal_ybds_pdf', $data);
    }

    public function input_modal_ybds()
    {
        $tahun = $this->input->post('tahun_data');
        date_default_timezone_set('Asia/Jakarta');
        $this->form_validation->set_rules('jenis_asset', 'Jenis Asset', 'required|trim');
        $this->form_validation->set_rules('rupiah', 'rupiah', 'required|trim|numeric');
        $this->form_validation->set_rules('id_kec', 'Nama Kecamatan', 'required|trim|numeric');
        $this->form_validation->set_rules('tahun_data', 'Tahun jenis_asset', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'INPUT PENYERTAAN PEMERINTAH KABUPATEN BONDOWOSO';
            $data['kec'] = $this->Model_keuangan->get_kec();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('keuangan/view_input_modal_ybds', $data);
            $this->load->view('templates/footer');
        } else {
            $jenis_asset = $this->input->post('jenis_asset');
            $id_kec = $this->input->post('id_kec');
            $rupiah = $this->input->post('rupiah');
            $sumber_dana = $this->input->post('sumber_dana');
            $unit_pemberi = $this->input->post('unit_pemberi');
            $tahun_data = $this->input->post('tahun_data');
            $keterangan = $this->input->post('keterangan');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_modal_ybds = [
                'jenis_asset' => $jenis_asset,
                'id_kec' => $id_kec,
                'rupiah' => $rupiah,
                'sumber_dana' => $sumber_dana,
                'unit_pemberi' => $unit_pemberi,
                'tahun_data' => $tahun_data,
                'keterangan' => $keterangan,
                'created_by' => $created_by,
                'created_at' => $created_at
            ];

            // Cek apakah tahun dan id bagian sudah ada di database
            $this->db->where('id_kec', $id_kec);
            $this->db->where('jenis_asset', $jenis_asset);
            $this->db->where('tahun_data', $tahun_data);
            $query = $this->db->get('ek_modal_ybds');

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Daftar Modal YBDS sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('keuangan/modal_ybds');
                return false;
            } else {
                $this->Model_keuangan->input_modal_ybds('ek_modal_ybds', $data_modal_ybds);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Daftar Modal YBDS baru berhasil ditambahkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('keuangan/modal_ybds');
            }
        }
    }

    public function input_keluar_modal_ybds()
    {
        $tahun = $this->input->post('tahun_data');
        date_default_timezone_set('Asia/Jakarta');
        $this->form_validation->set_rules('jenis_asset', 'Jenis Asset', 'required|trim');
        $this->form_validation->set_rules('rupiah', 'rupiah', 'required|trim|numeric');
        $this->form_validation->set_rules('id_kec', 'Nama Kecamatan', 'required|trim|numeric');
        $this->form_validation->set_rules('tahun_data', 'Tahun jenis_asset', 'required|trim');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'INPUT PENYERTAAN PEMERINTAH KABUPATEN BONDOWOSO';
            $data['kec'] = $this->Model_keuangan->get_kec();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('keuangan/view_input_modal_ybds', $data);
            $this->load->view('templates/footer');
        } else {
            $jenis_asset = $this->input->post('jenis_asset');
            $id_kec = $this->input->post('id_kec');
            $rupiah = $this->input->post('rupiah');
            $sumber_dana = $this->input->post('sumber_dana');
            $unit_pemberi = $this->input->post('unit_pemberi');
            $tahun_data = $this->input->post('tahun_data');
            $keterangan = $this->input->post('keterangan');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_modal_ybds = [
                'jenis_asset' => $jenis_asset,
                'id_kec' => $id_kec,
                'rupiah' => $rupiah * -1,
                'sumber_dana' => $sumber_dana,
                'unit_pemberi' => $unit_pemberi,
                'tahun_data' => $tahun_data,
                'keterangan' => $keterangan,
                'created_by' => $created_by,
                'created_at' => $created_at
            ];

            // Cek apakah tahun dan id bagian sudah ada di database
            $this->db->where('id_kec', $id_kec);
            $this->db->where('jenis_asset', $jenis_asset);
            $this->db->where('tahun_data', $tahun_data);
            $query = $this->db->get('ek_modal_ybds');

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Daftar Modal YBDS sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('keuangan/modal_ybds');
                return false;
            } else {
                $this->Model_keuangan->input_modal_ybds('ek_modal_ybds', $data_modal_ybds);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Daftar Modal YBDS baru berhasil ditambahkan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('keuangan/modal_ybds');
            }
        }
    }

    public function edit_modal_pemda($id_modal_pemda)
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');
        $data['title'] = 'Edit modal_pemda PDAM';
        $data['modal_pemda'] = $this->Model_keuangan->get_id_modal_pemda($id_modal_pemda);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('keuangan/view_edit_modal_pemda', $data);
        $this->load->view('templates/footer');
    }

    public function update_modal_pemda()
    {
        $tahun = $this->session->userdata('tahun_session');
        date_default_timezone_set('Asia/Jakarta');

        $id_modal_pemda = $this->input->post('id_modal_pemda');
        $keterangan = $this->input->post('keterangan');
        $kejadian = $this->input->post('kejadian');
        $tahun_data = $this->input->post('tahun_data');
        $modified_by = $this->session->userdata('nama_lengkap');
        $modified_at = date('Y-m-d H:i:s');

        $data_tka = [
            'tahun_data' => $tahun_data,
            'keterangan' => $keterangan,
            'kejadian' => $kejadian,
            'modified_by' => $modified_by,
            'modified_at' => $modified_at
        ];

        $this->Model_keuangan->update_modal_pemda($id_modal_pemda, $data_tka);
        $this->session->set_flashdata(
            'info',
            '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Data Kejadian Penting berhasil diedit.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>'
        );
        redirect('keuangan/modal_pemda?tahun=' . $tahun);
    }
}
