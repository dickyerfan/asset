<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Key Risk Indicator (KRI) — pengukuran risiko sesuai §10.2 dokumentasi MR.
 *
 * Status dihitung dari nilai aktual terhadap target; nilai yang belum diisi
 * tetap "Belum Diukur" dan TIDAK dinilai gagal.
 */
class Kri extends MY_Controller
{
    private $tujuan = 'manrisk/kri';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_kri');
        $this->load->model('Model_mri_register');
        $this->load->model('Model_mri_pengaturan');
        $this->load->library('form_validation');
    }

    // ------------------------------------------------------------------
    // DAFTAR
    // ------------------------------------------------------------------
    public function index()
    {
        $tahun = $this->tahunAktif(2027);
        $idUpk = null;
        if (!$this->aksesSemuaUnit()) {
            $idUpk = $this->unitPaksa();
        } elseif ($this->angka('id_upk')) {
            $idUpk = $this->angka('id_upk');
        }

        $this->render('manrisk/view_kri', [
            'title'       => 'KEY RISK INDICATOR',
            'tahun'       => $tahun,
            'daftar'      => $this->Model_mri_kri->getKri($tahun, $idUpk),
            'rekap'       => $this->Model_mri_kri->rekapStatus($tahun),
            'unit_list'   => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
            'risiko_list' => $this->Model_mri_register->getListDenganSkor(['tahun' => $tahun]),
            'akses_semua' => $this->aksesSemuaUnit(),
            'boleh_tulis' => $this->Model_mri_pengaturan->bolehUbah('input_risiko'),
        ]);
    }

    // ------------------------------------------------------------------
    // FORMULIR KRI (tambah / ubah definisi)
    // ------------------------------------------------------------------
    public function form($id = null)
    {
        $k = $id ? $this->Model_mri_kri->getKriById($id) : null;
        if ($id && !$k) {
            $this->pesan('KRI tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('input_risiko')) {
            $this->pesan('Data KRI sedang dikunci.', 'warning');
            redirect($this->tujuan);
            return;
        }

        $this->form_validation->set_rules('nama_kri', 'Nama KRI', 'required|trim|max_length[200]');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|integer');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->diposting() && $this->form_validation->run() === true) {
            $d = [
                'tahun'       => (int) $this->input->post('tahun', true),
                'triwulan'    => max(1, min(4, (int) $this->input->post('triwulan', true))),
                'id_register' => $this->angka('id_register'),
                'id_upk'      => $this->angka('id_upk') ?: ($this->aksesSemuaUnit() ? null : $this->unitPaksa()),
                'nama_kri'    => $this->teks('nama_kri'),
                'target'      => $this->teks('target') === '' ? null : str_replace(',', '.', $this->teks('target')),
                'satuan'      => $this->teks('satuan'),
                'frekuensi'   => $this->teks('frekuensi'),
                'keterangan'  => $this->teks('keterangan'),
            ];
            if ($id) {
                // nilai_aktual sengaja TIDAK disentuh di sini — diubah lewat "Catat Nilai"
                unset($d['nilai_aktual']);
            }
            $this->Model_mri_kri->simpanKri($id, $d);
            $this->pesan('KRI <strong>' . html_escape($d['nama_kri']) . '</strong> disimpan.');
            redirect($this->tujuan);
            return;
        }

        $this->render('manrisk/view_kri_form', [
            'title'       => $id ? 'UBAH KRI' : 'TAMBAH KRI',
            'kri'         => $k,
            'tahun'       => $this->tahunAktif(2027),
            'risiko_list' => $this->Model_mri_register->getListDenganSkor(['tahun' => $this->tahunAktif(2027)]),
            'unit_list'   => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
            'akses_semua' => $this->aksesSemuaUnit(),
        ]);
    }

    // ------------------------------------------------------------------
    // CATAT NILAI AKTUAL (status dihitung ulang oleh model)
    // ------------------------------------------------------------------
    public function catat($id = null)
    {
        if (!$this->diposting()) {
            redirect($this->tujuan);
            return;
        }
        $k = $this->Model_mri_kri->getKriById($id);
        if (!$k) {
            $this->pesan('KRI tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->aksesSemuaUnit() && (int) $k->id_upk !== (int) $this->unitPaksa()) {
            $this->pesan('KRI berada di luar unit Anda.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('monitoring_risiko')) {
            $this->pesan('Monitoring KRI sedang dikunci.', 'warning');
            redirect($this->tujuan);
            return;
        }

        $nilai = $this->teks('nilai_aktual');
        $ok = $this->Model_mri_kri->catatNilai($id, $nilai, $this->teks('keterangan'));
        $status = $ok ? $this->Model_mri_kri->hitungStatus($this->Model_mri_kri->getKriById($id)) : '-';

        $this->pesan(
            $ok
                ? 'Nilai KRI <strong>' . html_escape($k->nama_kri) . '</strong> dicatat — status <strong>' . html_escape($status) . '</strong>.'
                : 'Gagal mencatat nilai KRI.',
            $ok ? 'success' : 'danger'
        );
        redirect($this->tujuan);
    }

    /** Hapus definisi KRI (tidak menyentuh register). */
    public function hapus($id = null)
    {
        $k = $this->Model_mri_kri->getKriById($id);
        if (!$k) {
            $this->pesan('KRI tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('input_risiko')) {
            $this->pesan('Data KRI sedang dikunci.', 'warning');
            redirect($this->tujuan);
            return;
        }
        $this->db->where('id_kri', $id)->delete('mri_kri');
        $this->pesan('KRI <strong>' . html_escape($k->nama_kri) . '</strong> dihapus.');
        redirect($this->tujuan);
    }
}
