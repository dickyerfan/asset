<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profil_risiko extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_risiko');
        $this->load->library('form_validation');
        date_default_timezone_set('Asia/Jakarta');
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
        $data['title'] = 'PROFIL RISIKO';
        $data['title2'] = 'ANALISA RISIKO';
        $data['title3'] = 'PENANGANAN RISIKO';
        $data['title4'] = 'MONITORING RISIKO';
        $data['unit_list'] = $this->Model_risiko->get_unit_list();
        $id_upk = $this->input->get('id_upk');
        $tahun = $this->input->get('tahun');

        if ($tahun === null || $tahun === '') {
            $tahun = (int)date('Y');
        } else {
            $tahun = (int)$tahun;
        }
        $data['tahun'] = $tahun;
        $data['filter'] = [
            'id_upk' => $id_upk,
            'tahun' => $tahun
        ];
        $data['profil_risiko'] = $this->Model_risiko->getProfilRisiko($id_upk, $tahun);
        $data['analisa_risiko'] = $this->Model_risiko->getAnalisaRisikoByProfil($id_upk, $tahun);
        $data['penanganan_risiko'] = $this->Model_risiko->getPenangananRisikoByProfil($id_upk, $tahun);
        $data['monitoring_risiko'] = $this->Model_risiko->getMonitoringRisikoByProfil($id_upk, $tahun);

        if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('risiko/view_profil_risiko', $data);
            $this->load->view('templates/footer');
        } else if ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('risiko/view_profil_risiko', $data);
            $this->load->view('templates/footer');
        } else if ($this->session->userdata('bagian') == 'Umum') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_umum');
            $this->load->view('risiko/view_profil_risiko', $data);
            $this->load->view('templates/footer');
        } else if ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('risiko/view_profil_risiko', $data);
            $this->load->view('templates/footer');
        } else if ($this->session->userdata('bagian') == 'Perencanaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_rencana');
            $this->load->view('risiko/view_profil_risiko', $data);
            $this->load->view('templates/footer');
        } else if ($this->session->userdata('bagian') == 'Pemeliharaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('risiko/view_profil_risiko', $data);
            $this->load->view('templates/footer');
        } else if ($this->session->userdata('bagian') == 'UPK') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_upk');
            $this->load->view('risiko/view_profil_risiko', $data);
            $this->load->view('templates/footer');
        }
    }

    public function input_risiko()
    {
        $data['title'] = 'Input Profil Risiko';
        $data['unit_list'] = $this->Model_risiko->get_unit_list();

        $this->form_validation->set_rules('id_upk', 'UPK', 'required');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|numeric');
        $this->form_validation->set_rules('kegiatan', 'Kegiatan', 'required');
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == false) {
            // Tampilkan form input
            if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('risiko/view_input_profil_risiko', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('risiko/view_input_profil_risiko', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Umum') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_umum');
                $this->load->view('risiko/view_input_profil_risiko', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Langgan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_langgan');
                $this->load->view('risiko/view_input_profil_risiko', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Perencanaan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_rencana');
                $this->load->view('risiko/view_input_profil_risiko', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Pemeliharaan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_pelihara');
                $this->load->view('risiko/view_input_profil_risiko', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'UPK') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_upk');
                $this->load->view('risiko/view_input_profil_risiko', $data);
                $this->load->view('templates/footer');
            }
        } else {
            // Cek duplikasi kegiatan dan tahun
            $id_upk = $this->input->post('id_upk');
            $tahun = $this->input->post('tahun');
            $kegiatan = $this->input->post('kegiatan');
            $cek = $this->db->get_where('mr_profil_risiko', [
                'id_upk' => $id_upk,
                'tahun' => $tahun,
                'kegiatan' => $kegiatan
            ])->num_rows();
            if ($cek > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Data dengan kegiatan dan tahun tersebut sudah ada.<br>Silakan cek kembali.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/profil_risiko/input_risiko?id_upk=' . $id_upk . '&tahun=' . $tahun);
                return;
            }
            // Proses simpan data
            $input = [
                'id_upk' => $id_upk,
                'tahun' => $tahun,
                'kegiatan' => $kegiatan,
                'tujuan' => $this->input->post('tujuan'),
                'kode_risiko' => $this->input->post('kode_risiko'),
                'pernyataan' => $this->input->post('pernyataan'),
                'sebab' => $this->input->post('sebab'),
                'kategori' => $this->input->post('kategori'),
                'dampak' => $this->input->post('dampak'),
                'created_by' => $this->session->userdata('nama_lengkap'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->Model_risiko->insertProfilRisiko($input);
            // Ambil id_risiko yang baru saja diinsert
            $id_risiko = $this->db->insert_id();
            // Insert ke mr_analisa_risiko dan mr_monitoring_risiko
            $this->Model_risiko->insertAnalisaRisiko($id_risiko);
            $this->Model_risiko->insertPenangananRisiko($id_risiko);
            $this->Model_risiko->insertMonitoringRisiko($id_risiko);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Daftar profil baru berhasil ditambahkan.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
            );
            redirect('risiko/profil_risiko?id_upk=' . $id_upk . '&tahun=' . $tahun);
        }
    }

    public function edit($id_risiko)
    {
        $data['title'] = 'Edit Profil Risiko';
        $data['tahun'] = $this->input->get('tahun') ? $this->input->get('tahun') : date('Y');
        $data['unit_list'] = $this->Model_risiko->get_unit_list();
        $data['profil'] = $this->Model_risiko->getProfilRisikoById($id_risiko);
        $data['kode_risiko'] = $this->Model_risiko->getAllKodeRisiko();

        $this->form_validation->set_rules('kegiatan', 'Kegiatan', 'required');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'required');
        $this->form_validation->set_rules('kode_risiko', 'Kode Risiko', 'required');
        $this->form_validation->set_rules('pernyataan', 'Pernyataan', 'required');
        $this->form_validation->set_rules('sebab', 'Sebab', 'required');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('dampak', 'Dampak', 'required');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == FALSE) {
            if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('risiko/view_edit_profil_risiko', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('risiko/view_edit_profil_risiko', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $input = $this->input->post();
            $profil_lama = $this->Model_risiko->getProfilRisikoById($id_risiko);
            $is_same = ($input['profil'] == $profil_lama->probabilitas &&
                $input['kegiatan'] == $profil_lama->kegiatan &&
                $input['tujuan'] == $profil_lama->tujuan &&
                $input['kode_risiko'] == $profil_lama->kode_risiko &&
                $input['pernyataan'] == $profil_lama->pernyataan &&
                $input['sebab'] == $profil_lama->sebab &&
                $input['kategori'] == $profil_lama->kategori &&
                $input['dampak'] == $profil_lama->dampak);
            if ($is_same) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Perhatian!</strong> Tidak ada perubahan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/profil_risiko');
            } else {
                $tipe_kr = $this->input->post('kode_risiko');
                list($tingkat, $kategori) = explode('-', $tipe_kr);
                $bagian = $profil_lama->id_upk;
                $tahun_full = $this->input->get('tahun');
                if (!$tahun_full) {
                    $tahun_full = date('Y');
                }
                $tahun = substr($tahun_full, -2);
                // Ambil nomor urut terakhir berdasarkan tipe_kr (tingkat)
                $this->db->select('kode_risiko');
                $this->db->from('mr_profil_risiko');
                $this->db->like('kode_risiko', "{$tingkat}.");
                $this->db->order_by('kode_risiko', 'DESC');
                $this->db->limit(1);
                $last = $this->db->get()->row();
                if ($last) {
                    $parts = explode('.', $last->kode_risiko);
                    $nomor = isset($parts[4]) ? ((int)$parts[4] + 1) : 1;
                } else {
                    $nomor = 1;
                }
                $input['kode_risiko'] = "{$tingkat}.{$tahun}.{$kategori}.{$bagian}.{$nomor}";
                $input['modified_by'] = $this->session->userdata('nama_lengkap');
                $input['modified_at'] = date('Y-m-d H:i:s');
                $this->Model_risiko->updateProfilRisiko($id_risiko, $input);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Data profil risiko berhasil diupdate.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>'
                );
                redirect('risiko/profil_risiko');
            }
        }
    }

    public function edit_analisa($id_analisa)
    {
        $data['title'] = 'Edit Analisa Risiko';
        $data['tahun'] = $this->input->get('tahun') ? $this->input->get('tahun') : date('Y');
        $data['unit_list'] = $this->Model_risiko->get_unit_list();
        $data['analisa'] = $this->Model_risiko->getAnalisaRisikoById($id_analisa);
        $data['pemilik_risiko'] = $this->Model_risiko->getAllPemilikRisiko();

        $this->form_validation->set_rules('kendali_uraian', 'Uraian', 'required');
        $this->form_validation->set_rules('desain', 'Desain', 'required');
        $this->form_validation->set_rules('efektifitas', 'Efektifitas', 'required');
        $this->form_validation->set_rules('probabilitas', 'Probabilitas', 'required');
        $this->form_validation->set_rules('dampak', 'Dampak', 'required');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == FALSE) {
            if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('risiko/view_edit_analisa_risiko', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('risiko/view_edit_analisa_risiko', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $input = $this->input->post();
            $analisa_lama = $data['analisa'];
            $is_same = ($input['analisa'] == $analisa_lama->kendali_uraian &&
                $input['desain'] == $analisa_lama->desain &&
                $input['efektifitas'] == $analisa_lama->efektifitas &&
                $input['probabilitas'] == $analisa_lama->probabilitas &&
                $input['dampak'] == $analisa_lama->dampak &&
                $input['tingkat_risiko'] == $analisa_lama->tingkat_risiko &&
                $input['peringkat_risiko'] == $analisa_lama->peringkat_risiko &&
                $input['pemilik_risiko'] == $analisa_lama->pemilik_risiko);
            if ($is_same) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Perhatian!</strong> Tidak ada perubahan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/profil_risiko');
            } else {
                $probabilitas = $this->input->post('probabilitas');
                $dampak = $this->input->post('dampak');
                $tingkat = $this->Model_risiko->getTingkatRisiko($probabilitas, $dampak);

                $input['tingkat_risiko'] = $tingkat ? $tingkat->skor : 0;
                $input['peringkat_risiko'] = $tingkat ? $tingkat->nama_level : 0;

                $input['modified_by'] = $this->session->userdata('nama_lengkap');
                $input['modified_at'] = date('Y-m-d H:i:s');
                $this->Model_risiko->updateAnalisaRisiko($id_analisa, $input);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Data profil risiko berhasil diupdate.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>'
                );
                redirect('risiko/profil_risiko');
            }
        }
    }

    public function edit_penanganan($id_penanganan)
    {
        $data['title'] = 'Edit Penanganan Risiko';
        $data['tahun'] = $this->input->get('tahun') ? $this->input->get('tahun') : date('Y');
        $data['unit_list'] = $this->Model_risiko->get_unit_list();
        $data['penanganan'] = $this->Model_risiko->getPenangananRisikoById($id_penanganan);
        $data['pemilik_risiko'] = $this->Model_risiko->getAllPemilikRisiko();

        $this->form_validation->set_rules('uraian', 'Uraian', 'required');
        $this->form_validation->set_rules('jadwal', 'Jadwal', 'required');
        $this->form_validation->set_rules('hasil', 'Hasil', 'required');
        $this->form_validation->set_rules('pj_tl', 'Penanggung Jawab', 'required');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == FALSE) {
            if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('risiko/view_edit_penanganan_risiko', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('risiko/view_edit_penanganan_risiko', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $input = $this->input->post();
            $penanganan_lama = $data['penanganan'];
            $is_same = ($input['penanganan'] == $penanganan_lama->uraian &&
                $input['jadwal'] == $penanganan_lama->jadwal &&
                $input['hasil'] == $penanganan_lama->hasil &&
                $input['pj_tl'] == $penanganan_lama->pj_tl);
            if ($is_same) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Perhatian!</strong> Tidak ada perubahan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/profil_risiko');
            } else {
                $input['modified_by'] = $this->session->userdata('nama_lengkap');
                $input['modified_at'] = date('Y-m-d H:i:s');
                $this->Model_risiko->updatePenangananRisiko($id_penanganan, $input);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Data Penanganan risiko berhasil diupdate.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>'
                );
                redirect('risiko/profil_risiko');
            }
        }
    }

    public function edit_monitoring($id_monitoring)
    {
        $data['title'] = 'Edit Monitoring Risiko';
        $data['tahun'] = $this->input->get('tahun') ? $this->input->get('tahun') : date('Y');
        $data['unit_list'] = $this->Model_risiko->get_unit_list();
        $data['monitoring'] = $this->Model_risiko->getMonitoringRisikoById($id_monitoring);

        $this->form_validation->set_rules('rtp', 'RTP', 'required');
        $this->form_validation->set_rules('jadwal', 'Jadwal', 'required');
        $this->form_validation->set_rules('hasil', 'Hasil', 'required');
        $this->form_validation->set_rules('prob_setelah', 'Probabilitas', 'required');
        $this->form_validation->set_rules('dampak_setelah', 'Dampak', 'required');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == FALSE) {
            if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('risiko/view_edit_monitoring_risiko', $data);
                $this->load->view('templates/footer');
            } else if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('risiko/view_edit_monitoring_risiko', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $input = $this->input->post();
            $monitoring_lama = $data['monitoring'];
            $is_same = ($input['monitoring'] == $monitoring_lama->kendali_uraian &&
                $input['rtp'] == $monitoring_lama->rtp &&
                $input['jadwal'] == $monitoring_lama->jadwal &&
                $input['hasil'] == $monitoring_lama->hasil &&
                $input['keterangan'] == $monitoring_lama->keterangan &&
                $input['prob_setelah'] == $monitoring_lama->prob_setelah &&
                $input['dampak_setelah'] == $monitoring_lama->dampak_setelah &&
                $input['tingkat_setelah'] == $monitoring_lama->tingkat_setelah &&
                $input['peringkat_setelah'] == $monitoring_lama->peringkat_setelah);
            if ($is_same) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Perhatian!</strong> Tidak ada perubahan data.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('risiko/profil_risiko');
            } else {
                $prob_setelah = $this->input->post('prob_setelah');
                $dampak_setelah = $this->input->post('dampak_setelah');
                $tingkat = $this->Model_risiko->getTingkatRisiko($prob_setelah, $dampak_setelah);

                $input['tingkat_setelah'] = $tingkat ? $tingkat->skor : 0;
                $input['peringkat_setelah'] = $tingkat ? $tingkat->nama_level : 0;

                $input['modified_by'] = $this->session->userdata('nama_lengkap');
                $input['modified_at'] = date('Y-m-d H:i:s');
                $this->Model_risiko->updateMonitoringRisiko($id_monitoring, $input);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> Data monitoring risiko berhasil diupdate.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>'
                );
                redirect('risiko/profil_risiko');
            }
        }
    }
}
