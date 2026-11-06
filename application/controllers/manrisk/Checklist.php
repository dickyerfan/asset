<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Checklist & Target Manajemen Risiko.
 *
 * Halaman ini dipakai untuk semua tahun. Template butir tetap, sedangkan
 * target dan centang manual disimpan per tahun (dan opsional per unit).
 */
class Checklist extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_checklist');
        $this->load->model('Model_mri_dashboard');
    }

    public function index()
    {
        $tahun  = $this->tahunAktif((int) date('Y') + 1);
        $id_upk = (int) $this->angka('id_upk', 0);

        // Akun unit hanya boleh melihat unitnya sendiri.
        if (!$this->aksesSemuaUnit()) {
            $paksa  = $this->unitPaksa();
            $id_upk = $paksa !== null ? (int) $paksa : 0;
        }

        $data = [
            'title'        => 'CHECKLIST & TARGET MANAJEMEN RISIKO',
            'tahun'        => $tahun,
            'id_upk'       => $id_upk,
            'unit_list'    => $this->daftarUnit(),
            'akses_semua'  => $this->aksesSemuaUnit(),
            'daftar_tahun' => $this->Model_mri_checklist->daftarTahun($tahun),
            'ringkas'      => $this->Model_mri_checklist->ringkas($tahun, $id_upk ?: null),
            'tahapan'      => $this->Model_mri_checklist->tahapan(),
            'nama_unit'    => $this->Model_mri_checklist->namaUnit($id_upk),
            'boleh_centang'=> $id_upk > 0,
            'boleh_simpan' => $this->aksesSemuaUnit() || $id_upk > 0,
        ];

        $this->render('manrisk/view_checklist', $data);
    }

    /** Simpan target tahunan (tahap + butir) untuk global atau satu unit. */
    public function simpan()
    {
        if (!$this->diposting()) {
            redirect('manrisk/checklist');
        }

        $tahun = (int) $this->input->post('tahun');
        if ($tahun <= 0) {
            $tahun = (int) date('Y') + 1;
        }

        $id_upk = (int) $this->input->post('id_upk');
        if (!$this->aksesSemuaUnit()) {
            $paksa  = $this->unitPaksa();
            $id_upk = $paksa !== null ? (int) $paksa : 0;
        }

        // Akun unit tanpa unit yang jelas tidak boleh menulis target global.
        if ($id_upk <= 0 && !$this->aksesSemuaUnit()) {
            $this->pesan('<strong>Gagal.</strong> Akun Anda belum terhubung ke unit UPK. Hubungi Administrator.', 'danger');
            redirect('manrisk/checklist?' . http_build_query(['tahun' => $tahun]));
        }

        $tahap = $this->input->post('tahap');
        $item  = $this->input->post('item');

        $ok = $this->Model_mri_checklist->simpanTarget(
            $tahun,
            $id_upk,
            is_array($tahap) ? $tahap : [],
            is_array($item) ? $item : [],
            (string) $this->session->userdata('nama_pengguna')
        );

        if ($ok) {
            $this->pesan('<strong>Berhasil.</strong> Target tahun ' . $tahun . ' sudah disimpan.');
        } else {
            $this->pesan('<strong>Gagal.</strong> Tabel checklist belum tersedia. Jalankan migrasi <code>13_create_mri_checklist.sql</code>.', 'danger');
        }

        redirect('manrisk/checklist?' . http_build_query([
            'tahun'  => $tahun,
            'id_upk' => $id_upk,
        ]));
    }

    /** Centang manual (AJAX) untuk satu butir. */
    public function centang()
    {
        if (!$this->diposting()) {
            return $this->_json(['ok' => false, 'pesan' => 'Metode tidak diizinkan.'], 405);
        }

        $tahun  = (int) $this->input->post('tahun');
        $id_upk = (int) $this->input->post('id_upk');

        if (!$this->aksesSemuaUnit()) {
            $paksa  = $this->unitPaksa();
            $id_upk = $paksa !== null ? (int) $paksa : 0;
        }

        $kode    = trim((string) $this->input->post('kode_item', true));
        $selesai = (int) $this->input->post('selesai') === 1 ? 1 : 0;

        if ($tahun <= 0 || $id_upk <= 0 || $kode === '') {
            return $this->_json(['ok' => false, 'pesan' => 'Data centang tidak lengkap. Pilih unit terlebih dahulu.'], 422);
        }

        $ok = $this->Model_mri_checklist->simpanCentang(
            $tahun,
            $id_upk,
            $kode,
            $selesai,
            '',
            (string) $this->session->userdata('nama_pengguna')
        );

        if (!$ok) {
            return $this->_json(['ok' => false, 'pesan' => 'Butir ini tidak dapat dicentang manual.'], 422);
        }

        if ($this->input->is_ajax_request()) {
            return $this->_json(['ok' => true, 'selesai' => $selesai]);
        }

        redirect('manrisk/checklist?' . http_build_query([
            'tahun'  => $tahun,
            'id_upk' => $id_upk,
        ]));
    }

    /** Hapus seluruh override target unit (kembali memakai target global). */
    public function hapus_override()
    {
        if (!$this->diposting()) {
            redirect('manrisk/checklist');
        }

        $tahun  = (int) $this->input->post('tahun');
        $id_upk = (int) $this->input->post('id_upk');

        if (!$this->aksesSemuaUnit()) {
            $paksa  = $this->unitPaksa();
            $id_upk = $paksa !== null ? (int) $paksa : 0;
        }

        if ($tahun > 0 && $id_upk > 0) {
            $this->Model_mri_checklist->hapusTargetUnit($tahun, $id_upk);
            $this->pesan('<strong>Berhasil.</strong> Override target unit dihapus. Target global kembali dipakai.');
        } else {
            $this->pesan('<strong>Gagal.</strong> Pilih unit terlebih dahulu.', 'warning');
        }

        redirect('manrisk/checklist?' . http_build_query([
            'tahun'  => $tahun ?: ((int) date('Y') + 1),
            'id_upk' => $id_upk,
        ]));
    }

    /** Unit untuk dropdown; akun unit hanya menerima unitnya sendiri. */
    private function daftarUnit()
    {
        $unit = $this->Model_mri_dashboard->daftarUnit();

        if ($this->aksesSemuaUnit()) {
            return $unit;
        }

        $paksa = $this->unitPaksa();
        if ($paksa === null) {
            return [];
        }

        return array_values(array_filter($unit, function ($u) use ($paksa) {
            return (int) $u->id_bagian === (int) $paksa;
        }));
    }

    /** Kirim respons JSON. */
    private function _json($data, $status = 200)
    {
        $this->output
            ->set_status_header($status)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data));
    }
}
