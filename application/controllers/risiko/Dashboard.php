<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
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
        $data['title'] = 'DASHBOARD RISIKO';
        $tahun = (int)$this->input->get('tahun');
        if (!$tahun) {
            $tahun = (int)date('Y');
        }

        $bagian = $this->session->userdata('bagian');
        $id_upk = $this->input->get('id_upk');
        $akses_semua_upk = in_array($bagian, ['Administrator', 'Keuangan', 'Publik', 'Auditor']);

        if (!$akses_semua_upk) {
            $id_upk = $this->session->userdata('id_bagian');
        }

        $data['tahun'] = $tahun;
        $data['unit_list'] = $this->Model_risiko->get_unit_list();
        $data['tahun_list'] = $this->Model_risiko->getTahunRisiko();
        $data['filter'] = [
            'id_upk' => $id_upk,
            'tahun' => $tahun
        ];
        $data['akses_semua_upk'] = $akses_semua_upk;
        $data['dashboard'] = $this->buildDashboardData(
            $this->Model_risiko->getDashboardRisikoData($id_upk, $tahun)
        );

        if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan' || $this->session->userdata('bagian') == 'Auditor') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('risiko/view_dashboard', $data);
            $this->load->view('templates/footer');
        } else if ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('risiko/view_dashboard', $data);
            $this->load->view('templates/footer');
        } else if ($this->session->userdata('bagian') == 'Umum') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_umum');
            $this->load->view('risiko/view_dashboard', $data);
            $this->load->view('templates/footer');
        } else if ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('risiko/view_dashboard', $data);
            $this->load->view('templates/footer');
        } else if ($this->session->userdata('bagian') == 'Perencanaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_rencana');
            $this->load->view('risiko/view_dashboard', $data);
            $this->load->view('templates/footer');
        } else if ($this->session->userdata('bagian') == 'Pemeliharaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('risiko/view_dashboard', $data);
            $this->load->view('templates/footer');
        } else if ($this->session->userdata('bagian') == 'UPK') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_upk');
            $this->load->view('risiko/view_dashboard', $data);
            $this->load->view('templates/footer');
        }
    }

    private function buildDashboardData($rows)
    {
        $peringkat = ['Sangat Tinggi', 'Tinggi', 'Moderat', 'Rendah'];
        $hasil = [
            'rows' => $rows,
            'total' => count($rows),
            'analisa_lengkap' => 0,
            'rtp_lengkap' => 0,
            'monitoring_lengkap' => 0,
            'risiko_menurun' => 0,
            'risiko_tetap' => 0,
            'risiko_meningkat' => 0,
            'risiko_prioritas' => 0,
            'dokumen_lengkap' => 0,
            'peringkat_awal' => array_fill_keys($peringkat, 0),
            'peringkat_residual' => array_fill_keys($peringkat, 0),
            'per_upk' => []
        ];

        foreach ($rows as $row) {
            $analisa_lengkap = $this->isFilled($row->probabilitas) && $this->isFilled($row->dampak_analisa);
            $rtp_lengkap = $this->isFilled($row->uraian_penanganan)
                && $this->isFilled($row->jadwal_penanganan)
                && $this->isFilled($row->hasil_penanganan)
                && $this->isFilled($row->pj_tl)
                && $this->isFilled($row->file_penanganan_document);
            $monitoring_lengkap = $this->isFilled($row->rtp)
                && $this->isFilled($row->hasil_monitoring)
                && $this->isFilled($row->prob_setelah)
                && $this->isFilled($row->dampak_setelah);
            // && $this->isFilled($row->file_monitoring_document);

            $hasil['analisa_lengkap'] += $analisa_lengkap ? 1 : 0;
            $hasil['rtp_lengkap'] += $rtp_lengkap ? 1 : 0;
            $hasil['monitoring_lengkap'] += $monitoring_lengkap ? 1 : 0;
            $hasil['dokumen_lengkap'] += (
                ($this->isFilled($row->file_penanganan_image) || $this->isFilled($row->file_penanganan_document))
                && $this->isFilled($row->file_monitoring_document)) ? 1 : 0;

            if (isset($hasil['peringkat_awal'][$row->peringkat_risiko])) {
                $hasil['peringkat_awal'][$row->peringkat_risiko]++;
            }
            if (isset($hasil['peringkat_residual'][$row->peringkat_setelah])) {
                $hasil['peringkat_residual'][$row->peringkat_setelah]++;
            }

            if (in_array($row->peringkat_risiko, ['Sangat Tinggi', 'Tinggi'])) {
                $hasil['risiko_prioritas']++;
            }

            if ($analisa_lengkap && $monitoring_lengkap) {
                $awal = (float)$row->tingkat_risiko;
                $residual = (float)$row->tingkat_setelah;
                if ($residual < $awal) {
                    $hasil['risiko_menurun']++;
                } elseif ($residual > $awal) {
                    $hasil['risiko_meningkat']++;
                } else {
                    $hasil['risiko_tetap']++;
                }
            }

            $nama_upk = $row->nama_bagian ?: 'Tanpa UPK';
            if (!isset($hasil['per_upk'][$nama_upk])) {
                $hasil['per_upk'][$nama_upk] = 0;
            }
            $hasil['per_upk'][$nama_upk]++;
        }

        arsort($hasil['per_upk']);
        return $hasil;
    }

    private function isFilled($value)
    {
        return $value !== null && trim((string)$value) !== '';
    }
}
