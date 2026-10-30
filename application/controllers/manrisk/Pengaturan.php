<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Pengaturan modul manrisk:
 *  - kunci transaksi (mri_kunci) — mengunci/membuka tulisan per bagian
 *  - risk appetite per kategori per tahun (mri_appetite)
 *
 * Seluruh perubahan dicatat siapa dan kapan (audit trail).
 */
class Pengaturan extends MY_Controller
{
    private $tujuan = 'manrisk/pengaturan';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_pengaturan');
        $this->load->model('Model_mri_master');
    }

    public function index()
    {
        $tahun = $this->angka('tahun') ?: (int) date('Y');

        $this->render('manrisk/view_pengaturan', [
            'title'         => 'PENGATURAN MODUL',
            'tahun'         => $tahun,
            'kunci'         => $this->Model_mri_pengaturan->getKunci(),
            'appetite'      => $this->Model_mri_pengaturan->getAppetite($tahun),
            'tahun_appetite' => $this->Model_mri_pengaturan->getTahunAppetite(),
            'kategori_list' => $this->Model_mri_master->getKategori(),
            'tingkat'       => $this->Model_mri_master->getTingkat(),
            'akses_semua'   => $this->aksesSemuaUnit(),
        ]);
    }

    /** Kunci / buka satu kunci transaksi. */
    public function kunci($kode = null)
    {
        if (!$this->diposting()) {
            redirect($this->tujuan);
            return;
        }
        if (!$this->aksesSemuaUnit()) {
            $this->pesan('Hanya Administrator, Keuangan, Publik, atau Auditor yang boleh mengubah kunci.', 'danger');
            redirect($this->tujuan);
            return;
        }
        $k = $this->Model_mri_pengaturan->getKunciByKode($kode);
        if (!$k) {
            $this->pesan('Kunci tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }

        $baru = ((int) $k->status_kunci) === 1 ? 0 : 1;
        $this->Model_mri_pengaturan->setKunci($kode, $baru);

        $this->pesan(
            'Kunci <strong>' . html_escape($k->nama_kunci) . '</strong> '
            . ($baru ? 'diaktifkan — modul dikunci.' : 'dinonaktifkan — modul dapat diubah.')
        );
        redirect($this->tujuan);
    }

    /** Simpan / ubah risk appetite satu kategori. */
    public function appetite($id = null)
    {
        if (!$this->diposting()) {
            redirect($this->tujuan);
            return;
        }
        if (!$this->aksesSemuaUnit()) {
            $this->pesan('Anda tidak berhak mengubah risk appetite.', 'danger');
            redirect($this->tujuan);
            return;
        }

        $tahun = (int) $this->input->post('tahun', true) ?: (int) date('Y');
        $data = [
            'tahun'           => $tahun,
            'id_kategori'     => $this->angka('id_kategori'),
            'risk_appetite'   => $this->teks('risk_appetite'),
            'risk_tolerance'  => $this->teks('risk_tolerance'),
            'risk_capacity'   => $this->teks('risk_capacity'),
            'batas_eskalasi'  => $this->teks('batas_eskalasi'),
            'status'          => $this->teks('status') === '1' ? 1 : 0,
        ];

        if (!$data['id_kategori']) {
            $this->pesan('Kategori wajib dipilih.', 'warning');
            redirect($this->tujuan);
            return;
        }

        // pakai id lama bila sudah ada untuk kategori+tahun yang sama
        if (!$id) {
            $ada = $this->db->get_where('mri_appetite', [
                'tahun' => $data['tahun'],
                'id_kategori' => $data['id_kategori'],
            ])->row();
            if ($ada) {
                $id = $ada->id_appetite;
            }
        }

        $this->Model_mri_pengaturan->simpanAppetite($id, $data);
        $this->pesan('Risk appetite kategori <strong>' . html_escape($data['id_kategori']) . '</strong> disimpan.');
        redirect($this->tujuan);
    }
}
