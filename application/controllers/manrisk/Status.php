<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Status Manajemen Risiko per UPK.
 *
 * Menampilkan matriks tahap 0-8 untuk setiap unit. Angka pada sel adalah
 * jumlah butir/data yang terpenuhi dibandingkan penyebutnya, dihitung dari
 * model checklist yang sama dengan halaman Checklist & Target.
 */
class Status extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_checklist');
    }

    public function index()
    {
        $tahun = $this->tahunAktif((int) date('Y') + 1);

        $status = $this->teks('status');
        if (!in_array($status, ['', 'Semua status', 'Selesai', 'Dalam proses', 'Belum mulai'], true)) {
            $status = '';
        }

        $tampil = $this->teks('tampil');
        if (!in_array($tampil, ['semua', 'perencanaan', 'pelaksanaan'], true)) {
            $tampil = 'semua';
        }

        $f = [
            'q'      => $this->teks('q'),
            'status' => $status,
            'tampil' => $tampil,
        ];

        $paksa = $this->unitPaksa();

        $data = [
            'title'        => 'STATUS MANAJEMEN RISIKO PER UPK',
            'tahun'        => $tahun,
            'filter'       => $f,
            'paksa'        => $paksa,
            'akses_semua'  => $this->aksesSemuaUnit(),
            'daftar_tahun' => $this->Model_mri_checklist->daftarTahun($tahun),
            'matriks'      => $this->Model_mri_checklist->matriks($tahun, $f, $paksa),
        ];

        $this->render('manrisk/view_status', $data);
    }
}
