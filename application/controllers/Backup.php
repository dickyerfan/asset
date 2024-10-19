<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Backup extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_backup');
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

        $level_pengguna = $this->session->userdata('level');
        if ($level_pengguna != 'Admin') {
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
        $data['title'] = 'Backup dan Restore';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('backup', $data);
        $this->load->view('templates/footer');
    }

    public function backup()
    {
        $this->load->dbutil();
        $tanggal = date('Ymd-His');
        $config = array(
            'format' => 'zip',
            'filename' => 'pdambws_' . $tanggal . '_db.sql',
            'add_drop' => true,
            'add_insert' => true,
            'newline' => "\n",
            'foreign_key_check' => false,
        );
        $backup = &$this->dbutil->backup($config);

        $nama_file = 'pdambws_' . $tanggal . '.zip';
        $this->load->helper('download');
        force_download($nama_file, $backup);
    }

    public function restore()
    {
        $this->model_backup->droptabel();

        $fileinput = $_FILES['datafile'];
        $nama = $_FILES['datafile']['name'];

        if (isset($fileinput)) {
            $lokasi_file = $fileinput['tmp_name'];
            $direktori = "backupdb/$nama";
            move_uploaded_file($lokasi_file, "$direktori");
        }

        $isi_file = file_get_contents($direktori);
        $string_query = rtrim($isi_file, "\n;");
        $array_query = explode(";", $string_query);

        foreach ($array_query as $query) {
            $this->db->query($query);
        }
        unlink($direktori);

        echo "<script>alert('Restore Sukses')</script>";
        redirect('backup', 'refresh');
    }
}
