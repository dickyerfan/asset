<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Laporan manajemen risiko (§10.1 dokumentasi MR):
 * Operasional, Manajemen, Dewan Pengawas, Tahunan.
 */
class Laporan extends MY_Controller
{
    private $tujuan = 'manrisk/laporan';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_kri');
        $this->load->model('Model_mri_dashboard');
        $this->load->model('Model_mri_master');
        $this->load->model('Model_mri_pengaturan');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $tahun = $this->tahunAktif(2027);
        $idUpk = null;
        if (!$this->aksesSemuaUnit()) {
            $idUpk = $this->unitPaksa();
        } elseif ($this->angka('id_upk')) {
            $idUpk = $this->angka('id_upk');
        }

        $this->render('manrisk/view_laporan', [
            'title'       => 'LAPORAN MANAJEMEN RISIKO',
            'tahun'       => $tahun,
            'daftar'      => $this->Model_mri_kri->getLaporan($tahun, null, $idUpk),
            'ringkasan'   => $this->Model_mri_dashboard->rangkum($tahun, $idUpk ? ['id_upk' => $idUpk] : []),
            'rekapKri'    => $this->Model_mri_kri->rekapStatus($tahun),
            'tingkat'     => $this->Model_mri_master->getTingkat(),
            'unit_list'   => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
            'jenis'       => ['Operasional', 'Manajemen', 'Dewan Pengawas', 'Tahunan'],
            'akses_semua' => $this->aksesSemuaUnit(),
            'boleh_tulis' => $this->Model_mri_pengaturan->bolehUbah('laporan_risiko'),
        ]);
    }

    /** Tambah / ubah laporan. */
    public function form($id = null)
    {
        $l = $id ? $this->Model_mri_kri->getLaporanById($id) : null;
        if ($id && !$l) {
            $this->pesan('Laporan tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('laporan_risiko')) {
            $this->pesan('Laporan sedang dikunci. Buka kunci pada Pengaturan.', 'warning');
            redirect($this->tujuan);
            return;
        }

        $this->form_validation->set_rules('jenis_laporan', 'Jenis Laporan',
            'required|in_list[Operasional,Manajemen,Dewan Pengawas,Tahunan]');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|integer');
        $this->form_validation->set_message('in_list', '%s tidak valid');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->diposting() && $this->form_validation->run() === true) {
            $d = [
                'tahun'                 => (int) $this->input->post('tahun', true),
                'triwulan'              => max(1, min(4, (int) $this->input->post('triwulan', true))),
                'jenis_laporan'         => $this->teks('jenis_laporan'),
                'id_upk'                => $this->angka('id_upk'),
                'ringkasan_eksekutif'   => $this->teks('ringkasan_eksekutif'),
                'latar_belakang'        => $this->teks('latar_belakang'),
                'tujuan_laporan'        => $this->teks('tujuan_laporan'),
                'ruang_lingkup'         => $this->teks('ruang_lingkup'),
                'rekap_inherent_residual' => $this->teks('rekap_inherent_residual'),
                'catatan_peta_risiko'   => $this->teks('catatan_peta_risiko'),
                'rekap_peluang'         => $this->teks('rekap_peluang'),
                'hasil_kri'             => $this->teks('hasil_kri'),
                'evaluasi_tindak_lanjut' => $this->teks('evaluasi_tindak_lanjut'),
                'kesimpulan'            => $this->teks('kesimpulan'),
                'rencana_tindak_lanjut' => $this->teks('rencana_tindak_lanjut'),
                'sumber_catatan'        => $this->teks('sumber_catatan'),
                'status'                => $this->teks('status') === 'FINAL' ? 'FINAL' : 'DRAFT',
            ];
            $this->Model_mri_kri->simpanLaporan($id, $d);
            $this->pesan('Laporan <strong>' . html_escape($d['jenis_laporan']) . '</strong> disimpan.');
            redirect($this->tujuan);
            return;
        }

        $this->render('manrisk/view_laporan_form', [
            'title'     => $id ? 'UBAH LAPORAN' : 'TAMBAH LAPORAN',
            'laporan'   => $l,
            'tahun'     => $this->tahunAktif(2027),
            'jenis'     => ['Operasional', 'Manajemen', 'Dewan Pengawas', 'Tahunan'],
            'unit_list' => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
        ]);
    }

    /** Pengesahan laporan (menjadikan FINAL). */
    public function sahkan($id = null)
    {
        $l = $this->Model_mri_kri->getLaporanById($id);
        if (!$l) {
            $this->pesan('Laporan tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('laporan_risiko')) {
            $this->pesan('Laporan sedang dikunci.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if ($this->diposting()) {
            $this->Model_mri_kri->setujuiLaporan($id, $this->teks('disahkan_oleh') ?: $this->session->userdata('nama_lengkap'));
            $this->pesan('Laporan <strong>' . html_escape($l->jenis_laporan) . ' ' . $l->tahun . '</strong> disahkan.');
        }
        redirect($this->tujuan);
    }
}
