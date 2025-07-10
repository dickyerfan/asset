<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tindak_lanjut extends CI_Controller
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
        $data['title'] = 'Tindak Lanjut Evaluasi UPK';
        $bulan_filter = $this->input->get('bulan');
        $tahun_filter = $this->input->get('tahun');
        $id_upk_filter = $this->input->get('id_upk');

        // Jika filter tidak ada, gunakan default (bulan/tahun sekarang, UPK semua)
        if ($bulan_filter === null || $bulan_filter === '') {
            $bulan_sekarang = (int)date('m');
            if ($bulan_sekarang === 1) {
                $bulan_filter = 12; // Jika bulan sekarang adalah Januari, set bulan ke 12
                $tahun_filter = (int)date('Y') - 1; // Kurangi tahun
            } else {
                $bulan_filter = $bulan_sekarang - 1; // Kurangi bulan
                $tahun_filter = (int)date('Y'); // Tahun tetap sama
            }
        } else {
            $bulan_filter = (int)$bulan_filter;
        }

        if ($tahun_filter === null || $tahun_filter === '') {
            $tahun_filter = (int)date('Y');
        } else {
            $tahun_filter = (int)$tahun_filter;
        }

        // Simpan filter yang aktif ke data untuk view
        $data['bulan_selected'] = $bulan_filter;
        $data['tahun_selected'] = $tahun_filter;
        $data['id_upk_selected'] = ($id_upk_filter !== null && $id_upk_filter !== '') ? (int)$id_upk_filter : '';
        $data['nama_upk'] = $this->Model_evaluasi_upk->get_nama_upk($id_upk_filter);

        // Ambil data tindak lanjut dengan filter
        $data['tindak_lanjut_data'] = $this->Model_evaluasi_upk->get_tindak_lanjut(
            $data['bulan_selected'],
            $data['tahun_selected'],
            $data['id_upk_selected']
        );

        // Ambil daftar semua UPK untuk dropdown filter
        $data['all_upk'] = $this->Model_evaluasi_upk->get_unit_list();

        // Array nama bulan untuk dropdown
        $data['nama_bulan_lengkap'] = array(
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        );

        // Load views
        if ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('spi/view_tindak_lanjut', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('spi/view_tindak_lanjut', $data);
            $this->load->view('templates/footer');
        }
    }

    public function input_tl()
    {
        $data['title'] = 'Input Tindak Lanjut';
        date_default_timezone_set('Asia/Jakarta');
        $data['all_upk'] = $this->Model_evaluasi_upk->get_unit_list();

        $nama_bulan_array = array(
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        );
        $data['nama_bulan_lengkap'] = $nama_bulan_array;

        $this->form_validation->set_rules('id_upk', 'UPK', 'required|integer');
        $this->form_validation->set_rules('bulan', 'Bulan', 'required|integer|less_than_equal_to[12]|greater_than_equal_to[1]');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|integer|min_length[4]|max_length[4]');
        $this->form_validation->set_rules('temuan', 'Temuan', 'required');
        // $this->form_validation->set_rules('rekomendasi', 'Rekomendasi', 'required');
        // $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == FALSE) {
            if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('spi/view_input_tindak_lanjut', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Administrator') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('spi/view_input_tindak_lanjut', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $id_upk = $this->input->post('id_upk');
            $bulan = $this->input->post('bulan');
            $tahun = $this->input->post('tahun');
            $temuan = $this->input->post('temuan');
            $rekomendasi = $this->input->post('rekomendasi');
            $keterangan = $this->input->post('keterangan');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');


            $this->db->where('id_upk', $id_upk);
            $this->db->where('bulan', $bulan);
            $this->db->where('tahun', $tahun);
            $this->db->where('temuan', $temuan);
            $query = $this->db->get('eu_tindak_lanjut');

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Daftar Tindak Lanjut sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('spi/tindak_lanjut');
                return false;
            }
            $data_tl = [
                'id_upk' => $this->input->post('id_upk'),
                'bulan' => $this->input->post('bulan'),
                'tahun' => $this->input->post('tahun'),
                'temuan' => $this->input->post('temuan'),
                'rekomendasi' => $this->input->post('rekomendasi'),
                'keterangan' => $this->input->post('keterangan'),
                'created_by' => $this->session->userdata('nama_lengkap'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->Model_evaluasi_upk->insert_tl($data_tl);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data Tindak Lanjut berhasil disimpan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
            );
            redirect('spi/tindak_lanjut?id_upk=' . $id_upk . '&bulan=' . $bulan . '&tahun=' . $tahun);
        }
    }

    // public function edit($id)
    // {
    //     $data['title'] = 'Edit Tindak Lanjut';
    //     date_default_timezone_set('Asia/Jakarta');
    //     $data['tindak_lanjut_data'] = $this->Model_evaluasi_upk->get_tindak_lanjut_by_id($id);
    //     $data['all_upk'] = $this->Model_evaluasi_upk->get_unit_list();
    //     $nama_bulan_array = array(
    //         1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    //         5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    //         9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    //     );
    //     $data['nama_bulan_lengkap'] = $nama_bulan_array;

    //     if (empty($data['tindak_lanjut_data'])) {
    //         $this->session->set_flashdata('info', '<div class="alert alert-danger" role="alert">Data tindak lanjut tidak ditemukan.</div>');
    //         redirect('tindak_lanjut');
    //     }

    //     $this->form_validation->set_rules('id_upk', 'UPK', 'required|integer');
    //     $this->form_validation->set_rules('bulan', 'Bulan', 'required|integer|less_than_equal_to[12]|greater_than_equal_to[1]');
    //     $this->form_validation->set_rules('tahun', 'Tahun', 'required|integer|min_length[4]|max_length[4]');
    //     $this->form_validation->set_rules('temuan', 'Temuan', 'required');
    //     $this->form_validation->set_message('required', '%s masih kosong');
    //     $this->form_validation->set_message('numeric', '%s harus berupa angka');

    //     if ($this->form_validation->run() == FALSE) {
    //         if ($this->session->userdata('bagian') == 'Publik') {
    //             $this->load->view('templates/header', $data);
    //             $this->load->view('templates/navbar');
    //             $this->load->view('templates/sidebar_publik');
    //             $this->load->view('spi/view_edit_lanjut_lanjut', $data); // Gunakan form yang sama
    //             $this->load->view('templates/footer');
    //         } elseif ($this->session->userdata('bagian') == 'Administrator') {
    //             $this->load->view('templates/header', $data);
    //             $this->load->view('templates/navbar');
    //             $this->load->view('templates/sidebar');
    //             $this->load->view('spi/view_edit_lanjut_lanjut', $data);
    //             $this->load->view('templates/footer');
    //         }
    //     } else {
    //         $data_update = [
    //             'id_upk' => $this->input->post('id_upk'),
    //             'bulan' => $this->input->post('bulan'),
    //             'tahun' => $this->input->post('tahun'),
    //             'temuan' => $this->input->post('temuan'),
    //             'rekomendasi' => $this->input->post('rekomendasi'),
    //             'keterangan' => $this->input->post('keterangan'),
    //             'modified_by' => $this->session->userdata('nama_lengkap'),
    //             'modified_at' => date('Y-m-d H:i:s')
    //         ];

    //         $this->Model_evaluasi_upk->update_tl($id, $data_update);
    //         $this->session->set_flashdata(
    //             'info',
    //             '<div class="alert alert-primary alert-dismissible fade show" role="alert">
    //                 <strong>Sukses!</strong> Data Tindak Lanjut berhasil di update.
    //                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    //             </div>'
    //         );
    //         redirect('spi/tindak_lanjut');
    //     }
    // }

    public function edit($id)
    {
        $data['title'] = 'Edit Tindak Lanjut';
        date_default_timezone_set('Asia/Jakarta');

        // Ambil data tindak lanjut berdasarkan ID
        $data['tindak_lanjut_data'] = $this->Model_evaluasi_upk->get_tindak_lanjut_by_id($id);
        $data['all_upk'] = $this->Model_evaluasi_upk->get_unit_list();

        $nama_bulan_array = array(
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        );
        $data['nama_bulan_lengkap'] = $nama_bulan_array;

        // ✅ Validasi jika data tidak ditemukan
        if (empty($data['tindak_lanjut_data'])) {
            $this->session->set_flashdata('info', '<div class="alert alert-danger" role="alert">Data tindak lanjut tidak ditemukan.</div>');
            redirect('spi/tindak_lanjut');
        }

        // ✅ Ambil dan cek created_at untuk validasi waktu edit
        $created_at = $data['tindak_lanjut_data']->created_at;
        $created_month = (int)date('m', strtotime($created_at));
        $created_year  = (int)date('Y', strtotime($created_at));
        $current_month = (int)date('m');
        $current_year  = (int)date('Y');

        if ($created_month !== $current_month || $created_year !== $current_year) {
            $this->session->set_flashdata('info', '<div class="alert alert-danger">Data ini tidak dapat diedit karena periode input sudah berakhir.</div>');
            redirect('spi/tindak_lanjut');
            return;
        }

        // ✅ Form validation rules
        $this->form_validation->set_rules('id_upk', 'UPK', 'required|integer');
        $this->form_validation->set_rules('bulan', 'Bulan', 'required|integer|less_than_equal_to[12]|greater_than_equal_to[1]');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|integer|min_length[4]|max_length[4]');
        $this->form_validation->set_rules('temuan', 'Temuan', 'required');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == FALSE) {
            if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('spi/view_edit_lanjut_lanjut', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Administrator') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('spi/view_edit_lanjut_lanjut', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $data_update = [
                'id_upk' => $this->input->post('id_upk'),
                'bulan' => $this->input->post('bulan'),
                'tahun' => $this->input->post('tahun'),
                'temuan' => $this->input->post('temuan'),
                'rekomendasi' => $this->input->post('rekomendasi'),
                'keterangan' => $this->input->post('keterangan'),
                'modified_by' => $this->session->userdata('nama_lengkap'),
                'modified_at' => date('Y-m-d H:i:s')
            ];

            $this->Model_evaluasi_upk->update_tl($id, $data_update);

            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Data Tindak Lanjut berhasil di update.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>'
            );
            redirect('spi/tindak_lanjut');
        }
    }


    // public function hapus($id)
    // {
    //     if ($this->Model_evaluasi_upk->delete_tl($id)) {
    //         $this->session->set_flashdata('info', '<div class="alert alert-success" role="alert">Data tindak lanjut berhasil dihapus!</div>');
    //     } else {
    //         $this->session->set_flashdata('info', '<div class="alert alert-danger" role="alert">Gagal menghapus data tindak lanjut.</div>');
    //     }
    //     redirect('spi/tindak_lanjut');
    // }

    public function hapus($id)
    {
        // Ambil data berdasarkan ID
        $data = $this->Model_evaluasi_upk->get_tindak_lanjut_by_id($id);

        if (!$data) {
            $this->session->set_flashdata('info', '<div class="alert alert-danger" role="alert">Data tidak ditemukan.</div>');
            redirect('spi/tindak_lanjut');
            return;
        }

        // Validasi created_at
        $created_at = $data->created_at;
        $created_month = (int)date('m', strtotime($created_at));
        $created_year  = (int)date('Y', strtotime($created_at));

        $current_month = (int)date('m');
        $current_year  = (int)date('Y');

        if ($created_month !== $current_month || $created_year !== $current_year) {
            $this->session->set_flashdata('info', '<div class="alert alert-danger" role="alert">Data tidak dapat dihapus karena periode input sudah berakhir.</div>');
            redirect('spi/tindak_lanjut');
            return;
        }

        // Lanjut hapus jika masih dalam bulan yang sama
        if ($this->Model_evaluasi_upk->delete_tl($id)) {
            $this->session->set_flashdata('info', '<div class="alert alert-success" role="alert">Data tindak lanjut berhasil dihapus!</div>');
        } else {
            $this->session->set_flashdata('info', '<div class="alert alert-danger" role="alert">Gagal menghapus data tindak lanjut.</div>');
        }
        redirect('spi/tindak_lanjut');
    }
}
