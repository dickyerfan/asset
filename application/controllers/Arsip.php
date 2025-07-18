<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Arsip extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_arsip');
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
        $data['sk'] = $this->db->get_where('arsip', [
            'jenis' => 'Surat Keputusan',
        ])->num_rows();
        $data['per'] = $this->db->get_where('arsip', [
            'jenis' => 'Peraturan',
        ])->num_rows();
        $data['bk'] = $this->db->get_where('arsip', [
            'jenis' => 'Berkas Kerja',
        ])->num_rows();
        $data['dk'] = $this->db->get_where('arsip', [
            'jenis' => 'Dokumen',
        ])->num_rows();

        $folder = $this->input->get('folder');
        $tahun  = $this->input->get('tahun');

        $this->db->select('arsip.*, arsip_folder.nama_folder');
        $this->db->from('arsip');
        $this->db->join('arsip_folder', 'arsip.id_folder = arsip_folder.id_folder');
        if (!empty($folder)) {
            $this->db->where('nama_folder', $folder);
        }
        if (!empty($tahun)) {
            $this->db->where('tahun', $tahun);
        }
        $data['arsip'] = $this->db->get()->result();
        $data['title'] = 'Ruang Arsip PDAM';
        // $data['arsip'] = $this->Model_arsip->getAll();
        $data['folder_list'] = $this->Model_arsip->getFolder();
        $data['daftarEska'] = $this->Model_arsip->getModalEska();
        $data['daftarPer'] = $this->Model_arsip->getModalPer();
        $data['daftarBer'] = $this->Model_arsip->getModalBer();
        $data['daftarDok'] = $this->Model_arsip->getModalDok();


        if ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('arsip/view_arsip', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('arsip/view_arsip', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Umum') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_umum');
            $this->load->view('arsip/view_arsip', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Pemeliharaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('arsip/view_arsip', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Perencanaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_rencana');
            $this->load->view('arsip/view_arsip', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('arsip/view_arsip', $data);
            $this->load->view('templates/footer');
        } else {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('arsip/view_arsip', $data);
            $this->load->view('templates/footer');
        }
    }

    public function tambah()
    {
        $this->form_validation->set_rules('jenis', 'Jenis', 'required|trim');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim|min_length[2]|max_length[4]|numeric');
        $this->form_validation->set_rules('nama_dokumen', 'Nama Dokumen', 'required|trim|is_unique[arsip.nama_dokumen]');
        $this->form_validation->set_rules('tentang', 'Tentang', 'required|trim');
        // $this->form_validation->set_rules('nama_file', 'Nama File', 'required|trim');
        // $this->form_validation->set_rules('tgl_upload', 'Tanggal Upload', 'required|trim');
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_message('is_unique', '%s sudah terdaftar');
        $this->form_validation->set_message('min_length', '%s Minimal 2 digit');
        $this->form_validation->set_message('max_length', '%s Maksimal 4 digit');
        $this->form_validation->set_message('numeric', '%s harus di isi angka');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Arsip / Berkas Baru';
            $data['folder_list'] = $this->Model_arsip->getFolder();
            if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('arsip/view_tambah_arsip', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Langgan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_langgan');
                $this->load->view('arsip/view_tambah_arsip', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Umum') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_umum');
                $this->load->view('arsip/view_tambah_arsip', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Pemeliharaan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_pelihara');
                $this->load->view('arsip/view_tambah_arsip', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Perencanaan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_rencana');
                $this->load->view('arsip/view_tambah_arsip', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('arsip/view_tambah_arsip', $data);
                $this->load->view('templates/footer');
            } else {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('arsip/view_tambah_arsip', $data);
                $this->load->view('templates/footer');
            }
        } else {
            // Cek apakah ada file yang diupload
            if (!empty($_FILES['nama_file']['name'])) {
                $file_name = $_FILES['nama_file']['name'];

                // Menyimpan file dengan nama yang sesuai
                $config['upload_path']   = './uploads/arsip/';
                $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx';
                $config['max_size']      = 20000;
                $config['file_name']     = $file_name;
                $config['overwrite']     = true; // Mengizinkan penggantian file yang ada dengan nama yang sama
                $config['encrypt_name']  = false; // Menonaktifkan enkripsi nama file

                $this->load->library('upload', $config);
                if ($this->upload->do_upload('nama_file')) {
                    $data['nama_file'] = $this->upload->data("file_name");
                    $data['nama_dokumen'] = $this->input->post('nama_dokumen');
                    $data['jenis'] = $this->input->post('jenis');
                    $data['tahun'] = $this->input->post('tahun');
                    $data['tentang'] = ucwords(strtolower($this->input->post('tentang')));
                    $data['tgl_upload'] = date('Y-m-d');
                    $data['tgl_dokumen'] = $this->input->post('tgl_dokumen');
                    $data['id_folder'] = $this->input->post('folder');
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['created_by'] = $this->session->userdata('nama_lengkap');

                    // Simpan data ke dalam database
                    $this->db->insert('arsip', $data);

                    $this->session->set_flashdata(
                        'info',
                        '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                            <strong>Sukses,</strong> Data berhasil di tambahkan
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>'
                    );
                    redirect('arsip');
                } else {
                    // Jika proses upload gagal
                    $error_msg = $this->upload->display_errors();
                    $this->session->set_flashdata('info', $error_msg);
                    redirect('arsip');
                }
            } else {
                // Tampilkan pesan kesalahan jika tidak ada file yang diunggah
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal,</strong> Silakan masukkan file arsip
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>'
                );
                redirect('arsip');
            }
        }
    }



    public function edit($id_arsip)
    {
        date_default_timezone_set('Asia/Jakarta');
        $data['arsip'] = $this->db->get_where('arsip', ['id_arsip' => $id_arsip])->row();
        $data['title'] = 'Form Edit Arsip / Berkas';
        $data['folder_list'] = $this->Model_arsip->getFolder();

        if ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('arsip/view_edit_arsip', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('arsip/view_edit_arsip', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Umum') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_umum');
            $this->load->view('arsip/view_edit_arsip', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Pemeliharaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('arsip/view_edit_arsip', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Perencanaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_rencana');
            $this->load->view('arsip/view_edit_arsip', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('arsip/view_edit_arsip', $data);
            $this->load->view('templates/footer');
        } else {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('arsip/view_edit_arsip', $data);
            $this->load->view('templates/footer');
        }
    }

    public function update()
    {
        date_default_timezone_set('Asia/Jakarta');
        $data = [
            'nama_dokumen' => $this->input->post('nama_dokumen'),
            'jenis' => $this->input->post('jenis'),
            'tahun' => $this->input->post('tahun'),
            'tentang' => $this->input->post('tentang'),
            'tgl_dokumen' => $this->input->post('tgl_dokumen'),
            'id_folder' => $this->input->post('folder'),
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $this->session->userdata('nama_lengkap')
        ];

        $this->db->where('id_arsip', $this->input->post('id_arsip'));
        $this->db->update('arsip', $data);

        if ($this->db->affected_rows() <= 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Maaf,</strong> tidak ada perubahan data
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  </button>
                </div>'
            );
            redirect('arsip');
        } else {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Sukses,</strong> Data berhasil di update
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
              </div>'
            );
            redirect('arsip');
        }
    }

    public function hapus($id_arsip)
    {
        if ($this->session->userdata('bagian') != 'Administrator') {
            $this->session->set_flashdata('info', 'Anda tidak berhak menghapus file.');
            redirect('arsip');
        }
        $cekFileLama = $this->db->get_where('arsip', ['id_arsip' => $id_arsip])->row();

        if (isset($cekFileLama->nama_file)) {
            unlink('uploads/arsip/' . $cekFileLama->nama_file);
        }

        $this->db->where('id_arsip', $id_arsip);
        $this->db->delete('arsip');

        $this->session->set_flashdata(
            'info',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Sukses,</strong> File/Dokumen berhasil di hapus
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
          </div>'
        );
        redirect('arsip');
    }

    public function baca($id_arsip)
    {
        $data = $this->db->get_where('arsip', ['id_arsip' => $id_arsip])->row();
        header("content-type: application/pdf");
        readfile('uploads/arsip/' . $data->nama_file);
    }

    public function download($id_arsip)
    {
        $data = $this->db->get_where('arsip', ['id_arsip' => $id_arsip])->row();
        force_download('uploads/arsip/' . $data->nama_file, null);
    }

    public function detail($id)
    {
        $data['title'] = 'Data Detail Arsip / Berkas';
        $data['detail_arsip'] = $this->Model_arsip->getDetail($id);

        if ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('arsip/view_detail_arsip', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('arsip/view_detail_arsip', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Umum') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_umum');
            $this->load->view('arsip/view_detail_arsip', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Pemeliharaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('arsip/view_detail_arsip', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Perencanaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_rencana');
            $this->load->view('arsip/view_detail_arsip', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('arsip/view_detail_arsip', $data);
            $this->load->view('templates/footer');
        } else {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('arsip/view_detail_arsip', $data);
            $this->load->view('templates/footer');
        }
    }

    public function folder()
    {
        $data['title'] = 'Daftar Folder Arsip PDAM';
        $data['folder_list'] = $this->Model_arsip->getFolder();

        if ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('arsip/view_folder', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('arsip/view_folder', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Umum') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_umum');
            $this->load->view('arsip/view_folder', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Pemeliharaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('arsip/view_folder', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Perencanaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_rencana');
            $this->load->view('arsip/view_folder', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('arsip/view_folder', $data);
            $this->load->view('templates/footer');
        } else {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('arsip/view_folder', $data);
            $this->load->view('templates/footer');
        }
    }

    public function tambah_folder()
    {
        $this->form_validation->set_rules('nama_folder', 'Nama Folder', 'required|trim|is_unique[arsip_folder.nama_folder]');
        $this->form_validation->set_message('required', '%s harus di isi');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Upload Folder Arsip / Berkas Baru';
            if ($this->session->userdata('bagian') == 'Publik') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_publik');
                $this->load->view('arsip/view_tambah_folder', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Langgan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_langgan');
                $this->load->view('arsip/view_tambah_folder', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Umum') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_umum');
                $this->load->view('arsip/view_tambah_folder', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Pemeliharaan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_pelihara');
                $this->load->view('arsip/view_tambah_folder', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Perencanaan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar_rencana');
                $this->load->view('arsip/view_tambah_folder', $data);
                $this->load->view('templates/footer');
            } elseif ($this->session->userdata('bagian') == 'Keuangan') {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('arsip/view_tambah_folder', $data);
                $this->load->view('templates/footer');
            } else {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/navbar');
                $this->load->view('templates/sidebar');
                $this->load->view('arsip/view_tambah_folder', $data);
                $this->load->view('templates/footer');
            }
        } else {
            $nama_folder = $this->input->post('nama_folder');
            $created_by = $this->session->userdata('nama_lengkap');
            $created_at = date('Y-m-d H:i:s');

            $this->db->where('nama_folder', $nama_folder);
            $query = $this->db->get('arsip_folder');

            if ($query->num_rows() > 0) {
                $this->session->set_flashdata(
                    'info',
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> Daftar folder sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'
                );
                redirect('arsip/folder');
                return false;
            }
            $data_folder = [
                'nama_folder' => $this->input->post('nama_folder'),
                'created_by' => $this->session->userdata('nama_lengkap'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->db->insert('arsip_folder', $data_folder);
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data Folder Baru berhasil disimpan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
            );
            redirect('arsip/folder');
        }
    }

    public function edit_folder($id_folder)
    {
        date_default_timezone_set('Asia/Jakarta');
        $data['folder'] = $this->db->get_where('arsip_folder', ['id_folder' => $id_folder])->row();
        $data['title'] = 'Form Edit Folder Arsip / Berkas';

        if ($this->session->userdata('bagian') == 'Publik') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_publik');
            $this->load->view('arsip/view_edit_folder', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Langgan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_langgan');
            $this->load->view('arsip/view_edit_folder', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Umum') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_umum');
            $this->load->view('arsip/view_edit_folder', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Pemeliharaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_pelihara');
            $this->load->view('arsip/view_edit_folder', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Perencanaan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar_rencana');
            $this->load->view('arsip/view_edit_folder', $data);
            $this->load->view('templates/footer');
        } elseif ($this->session->userdata('bagian') == 'Keuangan') {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('arsip/view_edit_folder', $data);
            $this->load->view('templates/footer');
        } else {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view('arsip/view_edit_folder', $data);
            $this->load->view('templates/footer');
        }
    }

    public function update_folder()
    {
        date_default_timezone_set('Asia/Jakarta');
        $data = [
            'nama_folder' => $this->input->post('nama_folder'),
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $this->session->userdata('nama_lengkap')
        ];

        $this->db->where('id_folder', $this->input->post('id_folder'));
        $this->db->update('arsip_folder', $data);

        if ($this->db->affected_rows() <= 0) {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Maaf,</strong> tidak ada perubahan data
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  </button>
                </div>'
            );
            redirect('arsip/folder');
        } else {
            $this->session->set_flashdata(
                'info',
                '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Sukses,</strong> Data berhasil di update
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
              </div>'
            );
            redirect('arsip/folder');
        }
    }
}
