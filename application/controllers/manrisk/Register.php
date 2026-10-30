<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Risk Register — identifikasi risiko (ISO 31000:2018 Klausa 6.2 - 6.3).
 *
 * Menangani daftar, detail (dengan tahapan siklus), formulir tambah/ubah,
 * serta penonaktifan. Analisis, evaluasi, perlakuan, dan monitoring
 * dipegang controller masing-masing agar satu tanggung jawab per berkas.
 */
class Register extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_register');
        $this->load->model('Model_mri_master');
        $this->load->model('Model_mri_lingkup');
        $this->load->model('Model_mri_analisa');
        $this->load->model('Model_mri_evaluasi');
        $this->load->model('Model_mri_perlakuan');
        $this->load->model('Model_mri_pengaturan');
        $this->load->library('form_validation');
    }

    /** Route tujuan bila ditolak akses. */
    private $tujuan = 'manrisk/register';

    /** Filter daftar dari request. */
    private function filter()
    {
        $f = [];
        $id_upk = $this->angka('id_upk');
        if (!$this->aksesSemuaUnit()) {
            $id_upk = $this->unitPaksa();
        }
        if ($id_upk) {
            $f['id_upk'] = $id_upk;
        }
        foreach (['id_kategori', 'triwulan'] as $k) {
            $v = $this->angka($k);
            if ($v) {
                $f[$k] = $v;
            }
        }
        foreach (['tipe_risiko', 'status_risiko', 'status_kendali', 'q'] as $k) {
            $v = $this->teks($k);
            if ($v !== '') {
                $f[$k] = $v;
            }
        }
        if ($this->angka('id_proses')) {
            $f['id_proses'] = $this->angka('id_proses');
        }
        if ($this->angka('id_objektif')) {
            $f['id_objektif'] = $this->angka('id_objektif');
        }
        $f['tahun'] = $this->tahunAktif(2027);
        return $f;
    }

    /**
     * Kode risiko berikutnya untuk sebuah segmen (dipakai form via XHR).
     * Mengembalikan teks polos agar bisa dibaca langsung oleh JavaScript.
     */
    public function saranKode($segmen = 'OPR')
    {
        $this->output
            ->set_content_type('text/plain', 'utf-8')
            ->set_output($this->Model_mri_register->kodeBerikut($segmen));
    }

    // ------------------------------------------------------------------
    // DAFTAR
    // ------------------------------------------------------------------
    public function index()
    {
        $f = $this->filter();

        $data = [
            'title'          => 'RISK REGISTER',
            'tahun'          => $f['tahun'],
            'filter'         => $f,
            'daftar'         => $this->Model_mri_register->getListDenganSkor($f),
            'unit_list'      => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
            'kategori_list'  => $this->Model_mri_master->getKategori(),
            'proses_list'    => $this->Model_mri_master->getProses(),
            'objektif_list'  => $this->Model_mri_lingkup->getObjektif($f['tahun']),
            'tingkat'        => $this->Model_mri_master->getTingkat(),
            'akses_semua'    => $this->aksesSemuaUnit(),
            'boleh_tulis'    => $this->Model_mri_pengaturan->bolehUbah('input_risiko'),
        ];

        $this->render('manrisk/view_register', $data);
    }

    // ------------------------------------------------------------------
    // DETAIL — identifikasi + ringkasan seluruh tahapan siklus
    // ------------------------------------------------------------------
    public function detail($id = null)
    {
        $r = $this->Model_mri_register->getById($id);
        if (!$r) {
            $this->pesan('Risiko tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->tolakBilaBukanUnit($r->id_upk, $this->tujuan)) {
            return;
        }

        $data = [
            'title'      => 'DETAIL RISIKO ' . $r->kode_risiko,
            'risiko'     => $r,
            'akibat'     => $this->Model_mri_register->getAkibat($r->id_register),
            'proses'     => $this->Model_mri_register->getProses($r->id_register),
            'objektif'   => $this->Model_mri_register->getObjektifTerkait($r->id_register),
            'analisa'    => $this->Model_mri_analisa->getByRegister($r->id_register),
            'evaluasi'   => $this->Model_mri_evaluasi->getRiwayatEvaluasi($r->id_register),
            'perlakuan'  => $this->Model_mri_perlakuan->getByRegister($r->id_register),
            'monitoring' => $this->Model_mri_perlakuan->getMonitorByRegister($r->id_register),
            'riwayat'    => $this->Model_mri_evaluasi->getRiwayatSkor($r->id_register),
            'tingkat'    => $this->Model_mri_master->getTingkat(),
            'owner_list' => $this->db->from('mri_risk_owner')->where('status', 1)->order_by('id_risk_owner', 'ASC')->get()->result(),
            'pic_list'   => $this->db->from('mri_pic')->where('status', 1)->order_by('nama_pic', 'ASC')->get()->result(),
            'boleh_tulis' => $this->Model_mri_pengaturan->bolehUbah('input_risiko'),
        ];

        $this->render('manrisk/view_register_detail', $data);
    }

    // ------------------------------------------------------------------
    // FORM TAMBAH / UBAH
    // ------------------------------------------------------------------
    public function form($id = null)
    {
        $risiko = null;
        if ($id) {
            $risiko = $this->Model_mri_register->getById($id);
            if (!$risiko) {
                $this->pesan('Risiko tidak ditemukan.', 'warning');
                redirect($this->tujuan);
                return;
            }
            if (!$this->tolakBilaBukanUnit($risiko->id_upk, $this->tujuan)) {
                return;
            }
        }

        $boleh = $this->Model_mri_pengaturan->bolehUbah('input_risiko');
        if (!$boleh) {
            $this->pesan('Register risiko sedang dikunci. Buka kunci pada Pengaturan terlebih dahulu.', 'warning');
            redirect($id ? 'manrisk/register/detail/' . $id : $this->tujuan);
            return;
        }
        // role terbatas hanya boleh mengelola unitnya sendiri
        if (!$this->aksesSemuaUnit() && $id === null) {
            if ($this->unitPaksa() === null) {
                $this->pesan('Unit Anda belum terpetakan, hubungi Administrator.', 'danger');
                redirect($this->tujuan);
                return;
            }
        }

        $aturan = [
            ['kode_risiko', 'Kode Risiko', 'required|trim|max_length[30]'],
            ['tahun', 'Tahun', 'required|integer'],
            ['id_upk', 'Unit / Bagian', 'required|integer'],
            ['id_kategori', 'Kategori', 'required|integer'],
            ['tipe_risiko', 'Tipe Risiko', 'required|in_list[Ancaman,Peluang]'],
            ['risiko', 'Rumusan Risiko', 'required|trim|min_length[5]'],
            ['penyebab', 'Penyebab', 'required|trim'],
            ['peristiwa_risiko', 'Peristiwa Risiko', 'required|trim'],
            ['sasaran_kpi', 'Sasaran / KPI', 'required|trim'],
        ];
        foreach ($aturan as $a) {
            $this->form_validation->set_rules($a[0], $a[1], $a[2]);
        }
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('in_list', '%s tidak valid');

        if ($this->diposting() && $this->form_validation->run() === true) {
            $unit = (int) $this->input->post('id_upk', true);
            if (!$this->tolakBilaBukanUnit($unit, $this->tujuan)) {
                return;
            }

            $kodeBaru = $this->teks('kode_risiko');
            if (!$this->Model_mri_register->kodeTersedia($kodeBaru, $id)) {
                $this->pesan('<strong>Kode risiko</strong> sudah dipakai risiko lain.', 'danger');
                redirect('manrisk/register/form' . ($id ? '/' . $id : ''));
                return;
            }

            $d = [
                'kode_risiko'      => strtoupper($kodeBaru),
                'tahun'            => (int) $this->input->post('tahun', true),
                'triwulan_awal'    => (int) $this->input->post('triwulan_awal', true) ?: 1,
                'id_upk'           => $unit,
                'id_lingkup'       => $this->angka('id_lingkup') ?: null,
                'id_kategori'      => (int) $this->input->post('id_kategori', true),
                'tipe_risiko'      => $this->input->post('tipe_risiko', true),
                'kegiatan'         => $this->teks('kegiatan'),
                'sasaran_kpi'      => $this->teks('sasaran_kpi'),
                'sumber_risiko'    => $this->teks('sumber_risiko'),
                'risiko'           => $this->teks('risiko'),
                'penyebab'         => $this->teks('penyebab'),
                'peristiwa_risiko' => $this->teks('peristiwa_risiko'),
                'indikator_emerging' => $this->teks('indikator_emerging'),
                'asumsi_bias'      => $this->teks('asumsi_bias'),
                'status_kendali'   => in_array($this->teks('status_kendali'), ['C', 'UC'], true) ? $this->teks('status_kendali') : 'UC',
                'status_risiko'    => in_array($this->teks('status_risiko'), ['AKTIF', 'SELESAI', 'NONAKTIF'], true) ? $this->teks('status_risiko') : 'AKTIF',
                'keterangan'       => $this->teks('keterangan'),
            ];
            if ($d['kegiatan'] === '') {
                $d['kegiatan'] = $d['sasaran_kpi'];
            }

            // akibat: baris dari textarea satu per baris, opsional "Jenis|Teks"
            $akibat = [];
            foreach (explode("\n", (string) $this->input->post('akibat_baris', true)) as $i => $baris) {
                $baris = trim($baris);
                if ($baris === '') {
                    continue;
                }
                $jenis = 'Non-Finansial';
                $teksAkibat = $baris;
                if (strpos($baris, '|') !== false) {
                    list($j, $teksAkibat) = array_map('trim', explode('|', $baris, 2));
                    if (in_array($j, ['Finansial', 'Non-Finansial'], true)) {
                        $jenis = $j;
                    }
                }
                $akibat[] = ['akibat' => $teksAkibat, 'jenis' => $jenis];
            }

            $proses = $this->input->post('proses', true);
            $objektif = $this->input->post('objektif', true);

            $baru = $this->Model_mri_register->simpan($id, $d, $akibat, (array) $proses, (array) $objektif);

            if ($baru) {
                $this->pesan('Risiko <strong>' . html_escape($d['kode_risiko']) . '</strong> berhasil disimpan.');
                redirect('manrisk/register/detail/' . $baru);
                return;
            }
            $this->pesan('Gagal menyimpan risiko.', 'danger');
            redirect('manrisk/register/form' . ($id ? '/' . $id : ''));
            return;
        }

        $data = [
            'title'          => $id ? 'UBAH RISIKO' : 'TAMBAH RISIKO',
            'risiko'         => $risiko,
            'akibat'         => $id ? $this->Model_mri_register->getAkibat($id) : [],
            'proses_dipilih' => $id ? array_column($this->Model_mri_register->getProses($id), 'id_proses') : [],
            'objektif_dipilih' => $id ? array_column($this->Model_mri_register->getObjektifTerkait($id), 'id_objektif') : [],
            'unit_list'      => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
            'kategori_list'  => $this->Model_mri_master->getKategori(),
            'proses_list'    => $this->Model_mri_master->getProses(),
            'objektif_list'  => $this->Model_mri_lingkup->getObjektif($this->tahunAktif(2027)),
            'lingkup_list'   => $this->Model_mri_lingkup->getLingkup($this->tahunAktif(2027)),
            'saran_kode'     => $id && $risiko ? $risiko->kode_risiko
                : $this->Model_mri_register->kodeBerikut('OPR'),
            'segmen_list'    => $this->Model_mri_register->daftarSegmen(),
            'tingkat'        => $this->Model_mri_master->getTingkat(),
            'akses_semua'    => $this->aksesSemuaUnit(),
        ];

        $this->render('manrisk/view_register_form', $data);
    }

    // ------------------------------------------------------------------
    // PENONAKTIFAN (tidak menghapus fisik agar jejak audit terjaga)
    // ------------------------------------------------------------------
    public function hapus($id = null)
    {
        $r = $this->Model_mri_register->getById($id);
        if (!$r) {
            $this->pesan('Risiko tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->tolakBilaBukanUnit($r->id_upk, $this->tujuan)) {
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('input_risiko')) {
            $this->pesan('Register sedang dikunci.', 'warning');
            redirect('manrisk/register/detail/' . $id);
            return;
        }

        $this->Model_mri_register->nonaktifkan($id);
        $this->pesan('Risiko <strong>' . html_escape($r->kode_risiko) . '</strong> dinonaktifkan. Data tetap tersimpan sebagai arsip.');
        redirect($this->tujuan);
    }
}
