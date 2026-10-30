<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Perlakuan risiko (ISO 31000:2018 Klausa 6.7) — memilih strategi
 * AVOID / REDUCE / SHARE / ACCEPT, menyusun rencana tindakan,
 * anggaran, jadwal, dan PIC.
 */
class Perlakuan extends MY_Controller
{
    private $tujuan = 'manrisk/perlakuan';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_register');
        $this->load->model('Model_mri_evaluasi');
        $this->load->model('Model_mri_perlakuan');
        $this->load->model('Model_mri_pengaturan');
        $this->load->model('Model_mri_risk_owner');
        $this->load->library('form_validation');
    }

    // ------------------------------------------------------------------
    // DAFTAR RENCANA PERLAKUAN
    // ------------------------------------------------------------------
    public function index()
    {
        $tahun = $this->tahunAktif(2027);
        $f = ['tahun' => $tahun];
        if (!$this->aksesSemuaUnit()) {
            $f['id_upk'] = $this->unitPaksa();
        } elseif ($this->angka('id_upk')) {
            $f['id_upk'] = $this->angka('id_upk');
        }

        $status = $this->teks('status_laksana');
        $daftar = $this->Model_mri_perlakuan->getListPerlakuan(
            $tahun,
            isset($f['id_upk']) ? $f['id_upk'] : null,
            $status
        );

        // tahan akses unit
        if (!$this->aksesSemuaUnit()) {
            $paks = $this->unitPaksa();
            $daftar = array_values(array_filter($daftar, function ($row) use ($paks) {
                return (int) $row->id_upk === (int) $paks;
            }));
        }

        $rekap = [];
        foreach ($this->Model_mri_perlakuan->rekapStatus($tahun) as $row) {
            $rekap[$row->status_laksana] = (int) $row->jml;
        }

        $this->render('manrisk/view_perlakuan', [
            'title'       => 'PERLAKUAN RISIKO',
            'tahun'       => $tahun,
            'filter'      => $f + ['status_laksana' => $status],
            'daftar'      => $daftar,
            'rekap'       => $rekap,
            'strategi'    => $this->Model_mri_perlakuan::LABEL_STRATEGI,
            'unit_list'   => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
            'akses_semua' => $this->aksesSemuaUnit(),
            'boleh_tulis' => $this->Model_mri_pengaturan->bolehUbah('penanganan_risiko'),
        ]);
    }

    // ------------------------------------------------------------------
    // FORMULIR
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
        if (!$this->Model_mri_pengaturan->bolehUbah('penanganan_risiko')) {
            $this->pesan('Perlakuan risiko sedang dikunci. Buka kunci pada Pengaturan terlebih dahulu.', 'warning');
            redirect('manrisk/register/detail/' . $r->id_register);
            return;
        }

        $aturan = [
            ['strategi', 'Strategi', 'required|in_list[AVOID,REDUCE,SHARE,ACCEPT]'],
            ['status_laksana', 'Status Pelaksanaan', 'required|in_list[BELUM,DALAM_PROSES,SELESAI]'],
            ['uraian_tindakan', 'Uraian Tindakan', 'trim'],
        ];
        foreach ($aturan as $a) {
            $this->form_validation->set_rules($a[0], $a[1], $a[2]);
        }
        $this->form_validation->set_message('in_list', '%s tidak valid');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->diposting() && $this->form_validation->run() === true) {
            $eval = $this->Model_mri_evaluasi->getByRegister($r->id_register);
            $d = [
                'id_evaluasi'     => $eval ? $eval->id_evaluasi : null,
                'strategi'        => $this->teks('strategi'),
                'alasan_strategi' => $this->teks('alasan_strategi'),
                'uraian_tindakan' => $this->teks('uraian_tindakan'),
                'indikator_sukses' => $this->teks('indikator_sukses'),
                'anggaran_biaya'  => $this->teks('anggaran_biaya'),
                'sumber_daya'     => $this->teks('sumber_daya'),
                'jadwal_mulai'    => $this->teks('jadwal_mulai'),
                'jadwal_selesai'  => $this->teks('jadwal_selesai'),
                'id_pic'          => $this->angka('id_pic'),
                'status_laksana'  => $this->teks('status_laksana'),
            ];

            $this->Model_mri_perlakuan->simpan($r->id_register, $d);

            $this->pesan('Rencana perlakuan <strong>' . html_escape($r->kode_risiko) . '</strong> disimpan.');
            redirect('manrisk/register/detail/' . $r->id_register);
            return;
        }

        $this->render('manrisk/view_perlakuan_form', [
            'title'     => 'PERLAKUAN ' . $r->kode_risiko,
            'risiko'    => $r,
            'perlakuan' => $this->Model_mri_perlakuan->getByRegister($r->id_register),
            'evaluasi'  => $this->Model_mri_evaluasi->getByRegister($r->id_register),
            'pic_list'  => $this->Model_mri_risk_owner->getPic(),
            'strategi'  => $this->Model_mri_perlakuan::LABEL_STRATEGI,
            'status_laksana' => $this->Model_mri_perlakuan::STATUS_LAKSANA,
            'monitoring' => $this->Model_mri_perlakuan->getMonitorByRegister($r->id_register),
        ]);
    }
}
