<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Impor Risk Register dari CSV (template 18 kolom, pemisah titik-koma).
 *
 * Alur tiga langkah agar pengguna dapat memeriksa hasil pemetaan sebelum
 * data masuk ke basis data:
 *   1. upload  -> baca & petakan          (index, proses 'baca')
 *   2. preview -> tampilkan baris + peringatan
 *   3. impor   -> tulis transaksional      (proses 'impor')
 */
class Import extends MY_Controller
{
    private $tujuan = 'manrisk/import';
    private $kunciHasil = 'manrisk_import_hasil';
    private $kunciOpsi  = 'manrisk_import_opsi';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_import');
        $this->load->model('Model_mri_master');
        $this->load->model('Model_mri_lingkup');
        $this->load->model('Model_mri_register');
        $this->load->model('Model_mri_pengaturan');
    }

    public function index()
    {
        $hasil = $this->session->userdata($this->kunciHasil);
        $opsi  = $this->session->userdata($this->kunciOpsi);

        $this->render('manrisk/view_import', [
            'title'         => 'IMPOR RISK REGISTER (CSV)',
            'hasil'         => $hasil,
            'opsi'          => is_array($opsi) ? $opsi : [],
            'unit_list'     => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
            'kategori_list' => $this->Model_mri_master->getKategori(),
            'lingkup_list'  => $this->Model_mri_lingkup->getLingkup($this->tahunAktif(2027)),
            'tingkat'       => $this->Model_mri_master->getTingkat(),
            'tahun'         => $this->tahunAktif(2027),
            'akses_semua'   => $this->aksesSemuaUnit(),
            'boleh_tulis'   => $this->Model_mri_pengaturan->bolehUbah('input_risiko'),
        ]);
    }

    /** Langkah 1 + 2: baca file lalu tampilkan pratinjau. */
    public function baca()
    {
        if (!$this->diposting()) {
            redirect($this->tujuan);
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('input_risiko')) {
            $this->pesan('Register sedang dikunci — impor dinonaktifkan.', 'warning');
            redirect($this->tujuan);
            return;
        }

        $this->load->library('upload');
        $konfig = [
            'upload_path'   => './assets/uploads/manrisk/',
            'allowed_types' => 'csv|txt',
            'max_size'      => 2048,
            'encrypt_name'  => TRUE,
        ];
        $this->upload->initialize($konfig);

        if (!is_dir($konfig['upload_path'])) {
            @mkdir($konfig['upload_path'], 0777, true);
        }

        if (!$this->upload->do_upload('file_csv')) {
            $this->pesan('Gagal mengunggah: ' . $this->upload->display_errors('', ''), 'danger');
            redirect($this->tujuan);
            return;
        }

        $info  = $this->upload->data();
        $hasil = $this->Model_mri_import->baca($info['full_path']);
        @unlink($info['full_path']); // file hanya diproses sekali, tidak disimpan

        $opsi = [
            'tahun'               => (int) $this->input->post('tahun', true),
            'id_upk'              => $this->angka('id_upk'),
            'triwulan'            => max(1, min(4, (int) $this->input->post('triwulan', true))),
            'id_lingkup'          => $this->angka('id_lingkup'),
            'id_kategori_default' => $this->angka('id_kategori_default'),
            'timpa'               => $this->teks('timpa') === 'YA',
        ];
        if (!$opsi['id_upk'] && !$this->aksesSemuaUnit()) {
            $opsi['id_upk'] = $this->unitPaksa();
        }

        $this->session->set_userdata($this->kunciHasil, $hasil);
        $this->session->set_userdata($this->kunciOpsi, $opsi);

        $n = count($hasil['rows']);
        if ($n) {
            $this->pesan('Berhasil membaca <strong>' . $n . '</strong> baris data. Periksa pratinjau sebelum mengimpor.');
        } else {
            $this->pesan('Tidak ada baris data yang dapat dibaca.', 'warning');
        }
        redirect($this->tujuan);
    }

    /** Langkah 3: tulis pratinjau ke basis data. */
    public function proses()
    {
        if (!$this->diposting()) {
            redirect($this->tujuan);
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('input_risiko')) {
            $this->pesan('Register sedang dikunci — impor dinonaktifkan.', 'warning');
            redirect($this->tujuan);
            return;
        }

        $hasil = $this->session->userdata($this->kunciHasil);
        $opsi  = $this->session->userdata($this->kunciOpsi);

        if (!$hasil || empty($hasil['rows'])) {
            $this->pesan('Tidak ada data pratinjau. Unggah file terlebih dahulu.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (empty($opsi['id_upk'])) {
            $this->pesan('Unit (id_upk) wajib dipilih sebelum mengimpor.', 'warning');
            redirect($this->tujuan);
            return;
        }

        $lapor = $this->Model_mri_import->impor($hasil, $opsi);
        $this->session->unset_userdata([$this->kunciHasil, $this->kunciOpsi]);

        $pesan = 'Impor selesai: <strong>' . (int) $lapor['impan'] . '</strong> tersimpan, '
            . '<strong>' . (int) $lapor['lewati'] . '</strong> dilewati, '
            . '<strong>' . (int) $lapor['gagal'] . '</strong> gagal.';
        $this->session->set_userdata('manrisk_import_lapor', $lapor);
        $this->pesan($pesan, $lapor['gagal'] ? 'warning' : 'success');
        redirect($this->tujuan);
    }

    /** Bersihkan sesi pratinjau. */
    public function batal()
    {
        $this->session->unset_userdata([$this->kunciHasil, $this->kunciOpsi]);
        $this->pesan('Pratinjau dibatalkan.');
        redirect($this->tujuan);
    }
}
