<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Lingkup penerapan manajemen risiko (ISO 31000 Klausa 6.3).
 *
 * Menentukan organisasi, unit, tahun berjalan, dan objektif yang dicakup
 * sebelum identifikasi risiko dimulai.
 */
class Lingkup extends MY_Controller
{
    private $tujuan = 'manrisk/lingkup';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_lingkup');
        $this->load->model('Model_mri_register');
        $this->load->model('Model_mri_pengaturan');
        $this->load->library('form_validation');
    }

    public function index($id = null)
    {
        if ($id !== null) {
            $this->form($id);
            return;
        }
        $tahun = $this->tahunAktif(2027);

        $this->render('manrisk/view_lingkup', [
            'title'       => 'LINGKUP PENERAPAN',
            'tahun'       => $tahun,
            'daftar'      => $this->Model_mri_lingkup->getLingkup($tahun),
            'objektif'    => $this->Model_mri_lingkup->getObjektif($tahun),
            'rekap'       => $this->Model_mri_lingkup->rekapRekonsiliasi($tahun),
            'unit_list'   => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
            'risiko'      => $this->Model_mri_register->getListDenganSkor(['tahun' => $tahun]),
            'akses_semua' => $this->aksesSemuaUnit(),
            'boleh_tulis' => $this->Model_mri_pengaturan->bolehUbah('input_risiko'),
        ]);
    }

    /** Tambah / ubah satu dokumen lingkup (form yang sama). */
    public function form($id = null)
    {
        $l = $id ? $this->Model_mri_lingkup->getLingkupById($id) : null;
        if ($id && !$l) {
            $this->pesan('Dokumen lingkup tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('input_risiko')) {
            $this->pesan('Lingkup penerapan sedang dikunci.', 'warning');
            redirect($this->tujuan);
            return;
        }

        $this->form_validation->set_rules('tahun', 'Tahun', 'required|integer');
        $this->form_validation->set_rules('nama_lingkup', 'Nama Lingkup', 'required|trim|max_length[200]');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->diposting() && $this->form_validation->run() === true) {
            $d = [
                'tahun'           => (int) $this->input->post('tahun', true),
                'triwulan'        => max(1, min(4, (int) $this->input->post('triwulan', true))),
                'nama_lingkup'    => $this->teks('nama_lingkup'),
                'objek_evaluasi'  => $this->teks('objek_evaluasi'),
                'batas_evaluasi'  => $this->teks('batas_evaluasi'),
                'konteks_internal' => $this->teks('konteks_internal'),
                'konteks_eksternal' => $this->teks('konteks_eksternal'),
                'kriteria_acuan'  => $this->teks('kriteria_acuan'),
                'tanggal_konsultasi' => $this->teks('tanggal_konsultasi') ?: null,
                'peserta'         => $this->teks('peserta'),
                'q_sasaran'       => $this->teks('q_sasaran'),
                'q_risiko'        => $this->teks('q_risiko'),
                'q_kontrol'       => $this->teks('q_kontrol'),
                'q_tindakan'      => $this->teks('q_tindakan'),
                'hasil_konsultasi' => $this->teks('hasil_konsultasi'),
                'pic_konsultasi'  => $this->teks('pic_konsultasi'),
                'id_upk'          => $this->angka('id_upk'),
                'status'          => $this->teks('status') === 'FINAL' ? 'FINAL' : 'DRAFT',
            ];
            $this->Model_mri_lingkup->simpanLingkup($id, $d);
            $this->pesan('Dokumen lingkup <strong>' . html_escape($d['nama_lingkup']) . '</strong> disimpan.');
            redirect($this->tujuan);
            return;
        }

        $this->render('manrisk/view_lingkup_form', [
            'title'       => $id ? 'UBAH LINGKUP' : 'TAMBAH LINGKUP',
            'lingkup'     => $l,
            'unit_list'   => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
            'tahun'       => $this->tahunAktif(2027),
            'akses_semua' => $this->aksesSemuaUnit(),
        ]);
    }
}
