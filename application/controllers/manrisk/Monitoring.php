<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Monitoring & review risiko (ISO 31000:2018 Klausa 6.7) — memantau apakah
 * kontrol masih efektif, skor berubah, dan apakah risiko perlu dievaluasi
 * ulang pada triwulan berikutnya.
 */
class Monitoring extends MY_Controller
{
    private $tujuan = 'manrisk/monitoring';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_register');
        $this->load->model('Model_mri_analisa');
        $this->load->model('Model_mri_perlakuan');
        $this->load->model('Model_mri_pengaturan');
        $this->load->library('form_validation');
    }

    // ------------------------------------------------------------------
    // DAFTAR CATATAN MONITORING
    // ------------------------------------------------------------------
    public function index($id_register = null)
    {
        if ($id_register !== null) {
            $this->form($id_register);
            return;
        }

        $tahun = $this->tahunAktif(2027);
        $f = ['tahun' => $tahun];
        if (!$this->aksesSemuaUnit()) {
            $f['id_upk'] = $this->unitPaksa();
        } elseif ($this->angka('id_upk')) {
            $f['id_upk'] = $this->angka('id_upk');
        }

        $risiko = $this->Model_mri_register->getListDenganSkor($f);
        $baris  = [];
        foreach ($risiko as $r) {
            $terakhir = $this->Model_mri_perlakuan->getMonitorTerakhir($r->id_register);
            $baris[] = (object) [
                'risiko'    => $r,
                'terakhir'  => $terakhir,
                'riwayat'   => $this->Model_mri_perlakuan->getMonitorByRegister($r->id_register),
            ];
        }

        $this->render('manrisk/view_monitoring', [
            'title'       => 'MONITORING RISIKO',
            'tahun'       => $tahun,
            'filter'      => $f,
            'baris'       => $baris,
            'unit_list'   => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
            'akses_semua' => $this->aksesSemuaUnit(),
            'boleh_tulis' => $this->Model_mri_pengaturan->bolehUbah('monitoring_risiko'),
        ]);
    }

    // ------------------------------------------------------------------
    // FORMULIR CATATAN MONITORING
    // ------------------------------------------------------------------
    public function form($id_register = null)
    {
        $r = $this->Model_mri_register->getById($id_register);
        if (!$r) {
            $this->pesan('Risiko tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->tolakBilaBukanUnit($r->id_upk, $this->tujuan)) {
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('monitoring_risiko')) {
            $this->pesan('Monitoring risiko sedang dikunci. Buka kunci pada Pengaturan terlebih dahulu.', 'warning');
            redirect('manrisk/register/detail/' . $r->id_register);
            return;
        }

        $aturan = [
            ['triwulan', 'Triwulan', 'required|integer|greater_than_equal_to[1]|less_than_equal_to[4]'],
            ['hasil_monitoring', 'Hasil Monitoring', 'trim'],
            ['kontrol_masih_efektif', 'Kontrol Masih Efektif', 'in_list[YA,TIDAK,SEBAGIAN]'],
        ];
        foreach ($aturan as $a) {
            $this->form_validation->set_rules($a[0], $a[1], $a[2]);
        }
        $this->form_validation->set_message('greater_than_equal_to', '%s minimal %s');
        $this->form_validation->set_message('less_than_equal_to', '%s maksimal %s');
        $this->form_validation->set_message('in_list', '%s tidak valid');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->diposting() && $this->form_validation->run() === true) {
            $d = [
                'id_register'         => $r->id_register,
                'tahun'               => (int) $this->input->post('tahun', true) ?: $this->tahunAktif(2027),
                'triwulan'            => (int) $this->input->post('triwulan', true),
                'status_tindakan'     => $this->teks('status_tindakan'),
                'hasil_monitoring'    => $this->teks('hasil_monitoring'),
                'kontrol_masih_efektif' => $this->teks('kontrol_masih_efektif') ?: 'YA',
                'nilai_lama'          => $this->teks('nilai_lama'),
                'nilai_baru'          => $this->teks('nilai_baru'),
                'perubahan'           => $this->teks('perubahan'),
                'sebab_perubahan'     => $this->teks('sebab_perubahan'),
                'perlu_reassess'      => $this->teks('perlu_reassess') === 'YA',
                'tanggal_review'      => $this->teks('tanggal_review') ?: date('Y-m-d'),
            ];

            $id = $this->input->post('id_monitoring', true);
            $this->Model_mri_perlakuan->simpanMonitor($d, $id ? (int) $id : null);

            $this->pesan('Catatan monitoring <strong>' . html_escape($r->kode_risiko) . '</strong> disimpan.');
            redirect('manrisk/monitoring');
            return;
        }

        $edit = null;
        if ($this->angka('id_monitoring')) {
            $edit = $this->Model_mri_perlakuan->getMonitorById($this->angka('id_monitoring'));
            if (!$edit || (int) $edit->id_register !== (int) $r->id_register) {
                $this->pesan('Catatan monitoring tidak ditemukan.', 'warning');
                redirect('manrisk/monitoring');
                return;
            }
        }

        $this->render('manrisk/view_monitoring_form', [
            'title'     => 'MONITORING ' . $r->kode_risiko,
            'risiko'    => $r,
            'catatan'   => $edit,
            'riwayat'   => $this->Model_mri_perlakuan->getMonitorByRegister($r->id_register),
            'analisa'   => $this->Model_mri_analisa->getByRegister($r->id_register),
        ]);
    }

    // ------------------------------------------------------------------
    // HAPUS CATATAN (monitoring salah catat boleh dihapus)
    // ------------------------------------------------------------------
    public function hapus($id = null)
    {
        $m = $this->Model_mri_perlakuan->getMonitorById($id);
        if (!$m) {
            $this->pesan('Catatan monitoring tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        $r = $this->Model_mri_register->getById($m->id_register);
        if (!$r || !$this->tolakBilaBukanUnit($r->id_upk, $this->tujuan)) {
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('monitoring_risiko')) {
            $this->pesan('Monitoring risiko sedang dikunci.', 'warning');
            redirect($this->tujuan);
            return;
        }

        $this->Model_mri_perlakuan->deleteMonitor($id);
        $this->pesan('Catatan monitoring <strong>' . html_escape($r->kode_risiko) . '</strong> dihapus.');
        redirect($this->tujuan);
    }
}
