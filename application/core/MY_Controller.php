<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Base controller modul manrisk (SNI ISO 31000:2018).
 *
 * Modul baru ini berdiri sendiri: prefix tabel mri_, folder controllers/
 * manrisk, folder views/manrisk. Tidak ada satu pun berkas legacy yang
 * diubah untuk membangunnya.
 *
 * Yang diwarisi dari pola legacy (agar tampilan & hak akses konsisten):
 *   - cek login memakai session 'nama_pengguna'
 *   - pemilihan sidebar berdasar session 'bagian'
 *   - akses menyeluruh atas unit untuk Administrator/Keuangan/Publik/Auditor
 *
 * Yang berbeda dari legacy (perbaikan sadar, tidak mengubah perilaku lama):
 *   - pemilihan sidebar dipusatkan di satu tempat; legacy menuliskan
 *     percabangan if/else yang sama pada hampir setiap method controller
 *   - header hanya dimuat sekali (pada cabang Administrator di controller
 *     legacy header dimuat dua kali)
 */
class MY_Controller extends CI_Controller
{
    /** Role yang boleh melihat seluruh unit. */
    protected $role_semua_unit = ['Administrator', 'Keuangan', 'Publik', 'Auditor'];

    /** Pemetaan role -> berkas sidebar. */
    protected $sidebar = [
        'Administrator'  => 'templates/sidebar',
        'Keuangan'       => 'templates/sidebar',
        'Auditor'        => 'templates/sidebar',
        'Publik'         => 'templates/sidebar_publik',
        'Umum'           => 'templates/sidebar_umum',
        'Langgan'        => 'templates/sidebar_langgan',
        'Perencanaan'    => 'templates/sidebar_rencana',
        'Pemeliharaan'   => 'templates/sidebar_pelihara',
        'UPK'            => 'templates/sidebar_upk',
    ];

    public function __construct()
    {
        parent::__construct();

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

    /** Session 'bagian' (role) pengguna aktif. */
    protected function bagian()
    {
        return (string) $this->session->userdata('bagian');
    }

    /** Apakah role ini boleh melihat seluruh unit? */
    protected function aksesSemuaUnit()
    {
        return in_array($this->bagian(), $this->role_semua_unit, true);
    }

    /**
     * Unit yang dipaksa pada query. null = tanpa batasan (akses menyeluruh).
     * @return int|null
     */
    protected function unitPaksa()
    {
        if ($this->aksesSemuaUnit()) {
            return null;
        }
        $id = $this->session->userdata('id_bagian');
        return $id !== null && $id !== '' ? (int) $id : null;
    }

    /** Nama berkas sidebar untuk role aktif. */
    protected function namaSidebar()
    {
        $b = $this->bagian();
        return isset($this->sidebar[$b]) ? $this->sidebar[$b] : 'templates/sidebar';
    }

    /**
     * Susun blok header + navbar + sidebar + isi + footer.
     *
     * @param string $view isi, mis. 'manrisk/view_dashboard'
     * @param array  $data variabel untuk view
     * @param string $title judul tab (opsional, mengisi $data['title'])
     */
    protected function render($view, $data = [], $title = null)
    {
        if ($title !== null) {
            $data['title'] = $title;
        }
        if (!isset($data['title'])) {
            $data['title'] = 'MANAJEMEN RISIKO';
        }

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view($this->namaSidebar());
        $this->load->view($view, $data);
        $this->load->view('templates/footer');
    }

    /**
     * Pesan sukses/galat yang tampil di halaman berikutnya.
     * @param string $teks isi pesan (boleh HTML)
     * @param string $jenis 'success' | 'danger' | 'warning' | 'info'
     */
    protected function pesan($teks, $jenis = 'success')
    {
        $this->session->set_flashdata(
            'info',
            '<div class="alert alert-' . $jenis . ' alert-dismissible fade show" role="alert">'
            . $teks .
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button></div>'
        );
    }

    /**
     * Tahun berjalan untuk filter: ?tahun= atau default 2027
     * (modul ini khusus risiko 2027 ke atas; 2025-2026 tetap di legacy).
     * @return int
     */
    protected function tahunAktif($default = 2027)
    {
        $t = (int) $this->input->get('tahun');
        return $t ?: $default;
    }

    /**
     * Nilai integer aman dari request.
     * @param string $kunci nama kunci GET/POST
     * @param int|null $default
     * @return int|null
     */
    protected function angka($kunci, $default = null)
    {
        $sumber = $this->input->post($kunci, true);
        if ($sumber === null) {
            $sumber = $this->input->get($kunci);
        }
        if ($sumber === null || $sumber === '') {
            return $default;
        }
        return (int) $sumber;
    }

    /**
     * Teks bersih dari request.
     * @param string $kunci
     * @return string
     */
    protected function teks($kunci)
    {
        $sumber = $this->input->post($kunci, true);
        if ($sumber === null) {
            $sumber = $this->input->get($kunci);
        }
        return is_string($sumber) ? trim($sumber) : '';
    }

    /**
     * Apakah permintaan ini POST?
     * @return bool
     */
    protected function diposting()
    {
        return $this->input->method(true) === 'POST';
    }

    /**
     * Kirim user kembali ke daftar dengan pesan bila tidak berhak.
     *
     * @param int|null $id_upk unit pemilik data
     * @param string   $tujuan route tujuan bila ditolak
     * @return bool true = boleh lanjut, false = sudah dialihkan
     */
    protected function tolakBilaBukanUnit($id_upk, $tujuan)
    {
        if ($this->aksesSemuaUnit()) {
            return true;
        }
        $paks = $this->unitPaksa();
        if ($paks !== null && $id_upk !== null && (int) $id_upk !== $paks) {
            $this->pesan('<strong>Maaf,</strong> Anda hanya dapat mengelola data unit sendiri.', 'danger');
            redirect($tujuan);
            return false;
        }
        return true;
    }
}
