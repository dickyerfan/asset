<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aspek_ops extends CI_Controller
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

        // $bagian = $this->session->userdata('bagian');
        // if ($bagian != 'Publik' && $bagian != 'Administrator' && $bagian != 'Keuangan') {
        //     $this->session->set_flashdata(
        //         'info',
        //         '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        //             <strong>Maaf,</strong> Anda tidak memiliki hak akses untuk halaman ini...
        //           </div>'
        //     );
        //     redirect('auth');
        // }
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
        $data['title'] = 'DAFTAR ISIAN ASPEK OPERASIONAL ' . $tahun;
        $data['title2'] = 'DAFTAR ISIAN ASPEK ADMINISTRASI ' . $tahun;
        $data['as_ops'] = $this->Model_keuangan->get_as_ops($tahun);
        $data['as_adm'] = $this->Model_keuangan->get_as_adm($tahun);

        if ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('keuangan/view_as_ops', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('keuangan/view_as_ops', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Administrator') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('keuangan/view_as_ops', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Perencanaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_rencana');
            $this->load->view('keuangan/view_as_ops', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Pemeliharaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('keuangan/view_as_ops', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Umum') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_umum');
            $this->load->view('keuangan/view_as_ops', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('keuangan/view_as_ops', $data);
            $this->load->view('templates/footer');
        }
    }

    // public function cetak_kej_pen()
    // {
    //     $tahun = $this->session->userdata('tahun_session');

    //     if (empty($tahun)) {
    //         $this->session->unset_userdata('tahun_session');
    //         $tahun = date('Y');
    //     }

    //     $data['tahun_lap'] = $tahun;
    //     $data['title'] = 'INFORMASI KEJADIAN PENTING ' . $tahun;
    //     $data['kej_pen'] = $this->Model_keuangan->get_kej_pen($tahun);

    //     $this->pdf->setPaper('folio', 'portrait');
    //     $this->pdf->filename = "kej_pen-{$tahun}.pdf";
    //     $this->pdf->generate('keuangan/cetak_kej_pen_pdf', $data);
    // }

    public function input_as_ops()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->form_validation->set_rules('tahun_aspek', 'Tahun Aspek', 'required');
        $this->form_validation->set_rules('penilaian[]', 'Penilaian', 'required');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Isian Aspek Operasional PDAM';
            if ($this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('keuangan/view_input_as_ops', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('keuangan/view_input_as_ops', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Administrator') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('keuangan/view_input_as_ops', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Perencanaan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_rencana');
                $this->load->view('keuangan/view_input_as_ops', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Pemeliharaan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_pelihara');
                $this->load->view('keuangan/view_input_as_ops', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Umum') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_umum');
                $this->load->view('keuangan/view_input_as_ops', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Langgan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_langgan');
                $this->load->view('keuangan/view_input_as_ops', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $tahun_aspek = $this->input->post('tahun_aspek');
            $penilaian = $this->input->post('penilaian');
            $hasil = $this->input->post('hasil');
            $keterangan = $this->input->post('keterangan');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_as_ops = [];
            $duplikasi_terdeteksi = false;

            $hasil_options = [
                'kualitas_air_distribusi' => [
                    'label' => 'Kualitas Air Distribusi',
                    'options' => [
                        'Memenuhi syarat air minum',
                        'Memenuhi syarat air bersih',
                        'Tidak memenuhi syarat',
                    ],
                ],
                'kontinuitas_air' => [
                    'label' => 'Kontinuitas Air',
                    'options' => [
                        'semua pelanggan mendapat aliran air 24 jam',
                        'belum semua pelanggan mendapat aliran air 24 jam',
                    ],
                ],
                'kecepatan_penyambungan_baru' => [
                    'label' => 'Kecepatan Penyambungan Baru',
                    'options' => [
                        '<= 6 hari kerja',
                        '> 6 hari kerja',
                    ],
                ],
                'service_point' => [
                    'label' => 'Service Point',
                    'options' => [
                        'tersedia service point',
                        'tidak tersedia service point',
                    ],
                ],
            ];

            foreach ($penilaian as $slug) {
                $nilai_hasil = isset($hasil[$slug]) ? $hasil[$slug] : '-';
                $label_penilaian = isset($hasil_options[$slug]['label']) ? $hasil_options[$slug]['label'] : $slug;

                $cek_duplikasi = $this->Model_keuangan->cek_duplikasi($tahun_aspek, $label_penilaian);

                if ($cek_duplikasi) {
                    $duplikasi_terdeteksi = true;
                    break;
                }

                $data_as_ops[] = [
                    'penilaian'   => $label_penilaian,
                    'hasil'       => $nilai_hasil,
                    'tahun_aspek' => $tahun_aspek,
                    'created_by'  => $created_by,
                    'created_at'  => $created_at
                ];
            }

            if ($duplikasi_terdeteksi) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Data Penilaian yang dipilih sudah ada di database.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
                );
            } elseif (!empty($data_as_ops)) {
                $this->Model_keuangan->input_as_ops_batch('ek_aspek_ops_dagri', $data_as_ops);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data penilaian berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
                );
            } else {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Pastikan penilaian diisi untuk setiap pilihan
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
                );
            }
            redirect('keuangan/aspek_ops?tahun=' . $tahun_aspek);
        }
    }

    // public function input_as_ops()
    // {
    //     $tahun = $this->input->post('tahun_aspek');
    //     date_default_timezone_set('Asia/Jakarta');
    //     $this->form_validation->set_rules('hasil', 'Hasil', 'required|trim');
    //     $this->form_validation->set_rules('tahun_aspek', 'Tahun Aspek', 'required|trim');
    //     $this->form_validation->set_message('required', '%s masih kosong');

    //     if ($this->form_validation->run() == false) {
    //         $data['title'] = 'Input Isian Aspek Operasional PDAM';
    //         if ($this->session->userdata('bagian') == 'Keuangan') {
    //             $this->load->view('templates/header', $data);
    //             $this->load->view('templates/navbar');
    //             $this->load->view('templates/sidebar');
    //             $this->load->view('keuangan/view_input_as_ops', $data);
    //             $this->load->view('templates/footer');
    //         } elseif ($this->session->userdata('bagian') == 'Publik') {
    //             $this->load->view('templates/header', $data);
    //             $this->load->view('templates/navbar');
    //             $this->load->view('templates/sidebar_publik');
    //             $this->load->view('keuangan/view_input_as_ops', $data);
    //             $this->load->view('templates/footer');
    //         } elseif ($this->session->userdata('bagian') == 'Administrator') {
    //             $this->load->view('templates/header', $data);
    //             $this->load->view('templates/navbar');
    //             $this->load->view('templates/sidebar');
    //             $this->load->view('keuangan/view_input_as_ops', $data);
    //             $this->load->view('templates/footer');
    //         } elseif ($this->session->userdata('bagian') == 'Perencanaan') {
    //             $this->load->view('templates/header', $data);
    //             $this->load->view('templates/navbar');
    //             $this->load->view('templates/sidebar_rencana');
    //             $this->load->view('keuangan/view_input_as_ops', $data);
    //             $this->load->view('templates/footer');
    //         } elseif ($this->session->userdata('bagian') == 'Pemeliharaan') {
    //             $this->load->view('templates/header', $data);
    //             $this->load->view('templates/navbar');
    //             $this->load->view('templates/sidebar_pelihara');
    //             $this->load->view('keuangan/view_input_as_ops', $data);
    //             $this->load->view('templates/footer');
    //         } elseif ($this->session->userdata('bagian') == 'Umum') {
    //             $this->load->view('templates/header', $data);
    //             $this->load->view('templates/navbar');
    //             $this->load->view('templates/sidebar_umum');
    //             $this->load->view('keuangan/view_input_as_ops', $data);
    //             $this->load->view('templates/footer');
    //         } elseif ($this->session->userdata('bagian') == 'Langgan') {
    //             $this->load->view('templates/header', $data);
    //             $this->load->view('templates/navbar');
    //             $this->load->view('templates/sidebar_langgan');
    //             $this->load->view('keuangan/view_input_as_ops', $data);
    //             $this->load->view('templates/footer');
    //         }
    //     } else {
    //         $tahun_aspek = $this->input->post('tahun_aspek');
    //         $penilaians = $this->input->post('penilaian');
    //         $hasil_array = $this->input->post('hasil');
    //         $keterangan_array = $this->input->post('keterangan');
    //         $created_by = $this->session->userdata('nama_lengkap');
    //         $created_at = date('Y-m-d H:i:s');

    //         for ($i = 0; $i < count($penilaians); $i++) {
    //             $data = [
    //                 'penilaian' => $penilaians[$i],
    //                 'hasil' => $hasil_array[$i],
    //                 'tahun_aspek' => $tahun_aspek,
    //                 'keterangan' => $keterangan_array[$i] ?? '',
    //                 'created_by' => $created_by,
    //                 'created_at' => $created_at
    //             ];

    //             // Cek apakah data dengan kombinasi ini sudah ada
    //             $cek = $this->db->get_where('ek_aspek_ops_dagri', [
    //                 'penilaian' => $penilaian,
    //                 'hasil' => $hasil_array[$penilaian],
    //                 'tahun_aspek' => $tahun_aspek
    //             ]);


    //             if ($cek->num_rows() == 0) {
    //                 $this->Model_keuangan->input_as_ops('ek_aspek_ops_dagri', $data);
    //             }
    //         }

    //         $this->session->set_flashdata(
    //             'info',
    //             '<div class="alert alert-success alert-dismissible fade show" role="alert">
    //         <strong>Sukses!</strong> Data berhasil disimpan.
    //         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    //     </div>'
    //         );
    //         redirect('keuangan/aspek_ops?tahun=' . $tahun_aspek);
    //     }
    // }

    // public function edit_kej_pen($id_kej_pen)
    // {
    //     $tahun = $this->session->userdata('tahun_session');
    //     date_default_timezone_set('Asia/Jakarta');
    //     $data['title'] = 'Edit kej_pen PDAM';
    //     $data['kej_pen'] = $this->Model_keuangan->get_id_kej_pen($id_kej_pen);

    //     $this->load->view('templates/header', $data);
    //     $this->load->view('templates/navbar');
    //     $this->load->view('templates/sidebar');
    //     $this->load->view('keuangan/view_edit_kej_pen', $data);
    //     $this->load->view('templates/footer');
    // }

    // public function update_kej_pen()
    // {
    //     $tahun = $this->session->userdata('tahun_session');
    //     date_default_timezone_set('Asia/Jakarta');

    //     $id_kej_pen = $this->input->post('id_kej_pen');
    //     $keterangan = $this->input->post('keterangan');
    //     $kejadian = $this->input->post('kejadian');
    //     $tahun_kej_pen = $this->input->post('tahun_kej_pen');
    //     $modified_by = $this->session->userdata('nama_lengkap');
    //     $modified_at = date('Y-m-d H:i:s');

    //     $data_tka = [
    //         'tahun_kej_pen' => $tahun_kej_pen,
    //         'keterangan' => $keterangan,
    //         'kejadian' => $kejadian,
    //         'modified_by' => $modified_by,
    //         'modified_at' => $modified_at
    //     ];

    //     $this->Model_keuangan->update_kej_pen($id_kej_pen, $data_tka);
    //     $this->session->set_flashdata(
    //         'info',
    //         '<div class="alert alert-primary alert-dismissible fade show" role="alert">
    //             <strong>Sukses!</strong> Data Kejadian Penting berhasil diedit.
    //             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    //         </div>'
    //     );
    //     redirect('keuangan/kejadian_penting?tahun=' . $tahun);
    // }

    public function input_as_adm()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->form_validation->set_rules('tahun_aspek', 'Tahun Aspek', 'required');
        $this->form_validation->set_rules('penilaian[]', 'Penilaian', 'required');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Input Isian Aspek Administrasi PDAM';
            if ($this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('keuangan/view_input_as_adm', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('keuangan/view_input_as_adm', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Administrator') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('keuangan/view_input_as_adm', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Perencanaan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_rencana');
                $this->load->view('keuangan/view_input_as_adm', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Pemeliharaan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_pelihara');
                $this->load->view('keuangan/view_input_as_adm', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Umum') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_umum');
                $this->load->view('keuangan/view_input_as_adm', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Langgan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_langgan');
                $this->load->view('keuangan/view_input_as_adm', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $tahun_aspek = $this->input->post('tahun_aspek');
            $penilaian = $this->input->post('penilaian');
            $hasil = $this->input->post('hasil');
            $keterangan = $this->input->post('keterangan');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $data_as_adm = [];
            $duplikasi_terdeteksi = false;

            $hasil_options = [
                'rencana_jangka_panjang' => [
                    'label' => ' Rencana Jangka Panjang',
                    'options' => [
                        'Sepenuhnya dipedomani',
                        'Dipedomani sebagian',
                        'Memiliki, belum dipedomani',
                        'Tidak memiliki',
                    ],
                ],
                'rencana_organisasi_dan_uraian_tugas' => [
                    'label' => ' Rencana Organisasi dan Uraian Tugas',
                    'options' => [
                        'Sepenuhnya dipedomani',
                        'Dipedomani sebagian',
                        'Memiliki, belum dipedomani',
                        'Tidak memiliki',
                    ],
                ],
                'prosedur_operasi_standar' => [
                    'label' => 'Prosedur Operasi Standar',
                    'options' => [
                        'Sepenuhnya dipedomani',
                        'Dipedomani sebagian',
                        'Memiliki, belum dipedomani',
                        'Tidak memiliki',
                    ],
                ],
                'gambar_nyata_laksana' => [
                    'label' => 'Gambar Nyata Laksana',
                    'options' => [
                        'Sepenuhnya dipedomani',
                        'Dipedomani sebagian',
                        'Memiliki, belum dipedomani',
                        'Tidak memiliki',
                    ],
                ],
                'pedoman_penilaian_kerja_karyawan' => [
                    'label' => 'Pedoman Penilaian Kerja Karyawan',
                    'options' => [
                        'Sepenuhnya dipedomani',
                        'Dipedomani sebagian',
                        'Memiliki, belum dipedomani',
                        'Tidak memiliki',
                    ],
                ],
                'rencana_kerja_dan_anggaran_perusahaan' => [
                    'label' => 'Rencana Kerja dan Anggaran Perusahaan',
                    'options' => [
                        'Sepenuhnya dipedomani',
                        'Dipedomani sebagian',
                        'Memiliki, belum dipedomani',
                        'Tidak memiliki',
                    ],
                ],
                'tertib_laporan_internal' => [
                    'label' => 'Tertib Laporan Internal',
                    'options' => [
                        'Dibuat tepat waktu',
                        'Tidak tepat waktu',
                    ],
                ],
                'tertib_laporan_eksternal' => [
                    'label' => 'Tertib Laporan Eksternal',
                    'options' => [
                        'Dibuat tepat waktu',
                        'Tidak tepat waktu',
                    ],
                ],
                'opini_auditor_independen' => [
                    'label' => 'Opini Auditor Independen',
                    'options' => [
                        'Wajar tanpa pengecualian',
                        'Wajar dengan pengecualian',
                        'Tidak memberikan pendapat',
                        'Pendapat tidak wajar',
                    ],
                ],
                'tidak_lanjut_hasil_pemeriksaan_tahun_terakhir' => [
                    'label' => 'Tidak Lanjut Hasil Pemeriksaan tahun terakhir',
                    'options' => [
                        'Tidak ada temuan',
                        'Ditindaklanjuti, seluruhnya selesai',
                        'Ditindaklanjuti, sebagian selesai',
                        'Tidak ditindaklanjuti',
                    ],
                ],
            ];

            foreach ($penilaian as $slug) {
                $nilai_hasil = isset($hasil[$slug]) ? $hasil[$slug] : '-';
                $label_penilaian = isset($hasil_options[$slug]['label']) ? $hasil_options[$slug]['label'] : $slug;

                $cek_duplikasi = $this->Model_keuangan->cek_duplikasi($tahun_aspek, $label_penilaian);

                if ($cek_duplikasi) {
                    $duplikasi_terdeteksi = true;
                    break;
                }

                $data_as_adm[] = [
                    'penilaian'   => $label_penilaian,
                    'hasil'       => $nilai_hasil,
                    'tahun_aspek' => $tahun_aspek,
                    'created_by'  => $created_by,
                    'created_at'  => $created_at
                ];
            }

            if ($duplikasi_terdeteksi) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Data Penilaian yang dipilih sudah ada di database.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
                );
            } elseif (!empty($data_as_adm)) {
                $this->Model_keuangan->input_as_adm_batch('ek_aspek_adm_dagri', $data_as_adm);
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data penilaian berhasil ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
                );
            } else {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Pastikan penilaian diisi untuk setiap pilihan
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
                );
            }
            redirect('keuangan/aspek_ops?tahun=' . $tahun_aspek);
        }
    }
}
