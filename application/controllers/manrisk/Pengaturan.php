<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Pengaturan modul manrisk:
 *  - kunci transaksi (mri_kunci) — mengunci/membuka tulisan per bagian
 *  - risk appetite per kategori per tahun (mri_appetite)
 *  - data petugas: PIC (mri_pic), petugas TTD (mri_petugas_ttd), risk owner (mri_risk_owner)
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
        $this->load->model('Model_mri_risk_owner');
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

    // =================================================================
    // Data petugas: PIC, Petugas TTD, dan Risk Owner
    // Hanya role lintas unit yang boleh mengubah master ini.
    // =================================================================

    /** Daftar unit (bagian_upk) untuk dropdown. */
    private function unitList()
    {
        return $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result();
    }

    /** Tolak bila role tidak berhak mengubah master petugas. */
    private function tolakPetugas($aksi)
    {
        if ($this->aksesSemuaUnit()) {
            return true;
        }
        $this->pesan('Hanya Administrator, Keuangan, Publik, atau Auditor yang boleh ' . $aksi . '.', 'danger');
        return false;
    }

    // ---------------- mri_pic ----------------

    public function pic()
    {
        $this->render('manrisk/view_pengaturan_pic', [
            'title'        => 'PENGATURAN · PIC',
            'pic_list'     => $this->Model_mri_risk_owner->getPic(false),
            'unit_list'    => $this->unitList(),
            'akses_semua'  => $this->aksesSemuaUnit(),
        ]);
    }

    public function pic_simpan()
    {
        if (!$this->diposting()) {
            redirect($this->tujuan . '/pic');
            return;
        }
        if (!$this->tolakPetugas('mengubah data PIC')) {
            redirect($this->tujuan . '/pic');
            return;
        }

        $id   = $this->angka('id_pic');
        $nama = $this->teks('nama_pic');
        if ($nama === '') {
            $this->pesan('Nama PIC wajib diisi.', 'warning');
            redirect($this->tujuan . '/pic');
            return;
        }

        $data = [
            'nama_pic'    => $nama,
            'jabatan_pic' => $this->teks('jabatan_pic') ?: null,
            'id_bagian'   => $this->angka('id_bagian') ?: null,
            'kompetensi'  => $this->teks('kompetensi') ?: null,
            'status'      => $this->teks('status') === '1' ? 1 : 0,
        ];

        $ok = $this->Model_mri_risk_owner->simpanPic($id, $data);
        $this->pesan(
            $ok
                ? 'PIC <strong>' . html_escape($nama) . '</strong> ' . ($id ? 'berhasil diubah.' : 'berhasil ditambahkan.')
                : 'Gagal menyimpan PIC.',
            $ok ? 'success' : 'danger'
        );
        redirect($this->tujuan . '/pic');
    }

    public function pic_hapus($id = null)
    {
        if (!$this->tolakPetugas('menghapus data PIC')) {
            redirect($this->tujuan . '/pic');
            return;
        }
        $pic = $this->Model_mri_risk_owner->getPicById((int) $id);
        if (!$pic) {
            $this->pesan('PIC tidak ditemukan.', 'warning');
            redirect($this->tujuan . '/pic');
            return;
        }

        $dipakai = $this->Model_mri_risk_owner->jumlahPerlakuanPic((int) $id);
        if ($dipakai > 0) {
            $this->pesan(
                'PIC <strong>' . html_escape($pic->nama_pic) . '</strong> tidak dapat dihapus karena masih dipakai pada '
                . $dipakai . ' perlakuan risiko. Nonaktifkan saja bila tidak lagi dipakai.',
                'warning'
            );
            redirect($this->tujuan . '/pic');
            return;
        }

        $ok = $this->Model_mri_risk_owner->hapusPic((int) $id);
        $this->pesan(
            $ok ? 'PIC <strong>' . html_escape($pic->nama_pic) . '</strong> dihapus.' : 'Gagal menghapus PIC.',
            $ok ? 'success' : 'danger'
        );
        redirect($this->tujuan . '/pic');
    }

    // ---------------- mri_petugas_ttd ----------------

    public function ttd()
    {
        $this->render('manrisk/view_pengaturan_ttd', [
            'title'       => 'PENGATURAN · PETUGAS TTD',
            'ttd_list'    => $this->Model_mri_risk_owner->getPetugasTtd(false),
            'unit_list'   => $this->unitList(),
            'akses_semua' => $this->aksesSemuaUnit(),
        ]);
    }

    public function ttd_simpan()
    {
        if (!$this->diposting()) {
            redirect($this->tujuan . '/ttd');
            return;
        }
        if (!$this->tolakPetugas('mengubah petugas tanda tangan')) {
            redirect($this->tujuan . '/ttd');
            return;
        }

        $id   = $this->angka('id_ttd');
        $kode = strtoupper(preg_replace('/[^A-Za-z0-9_]/', '_', $this->teks('kode_ttd')));
        $kode = trim($kode, '_');
        $jabatan = $this->teks('jabatan_ttd');

        if ($kode === '') {
            $this->pesan('Kode TTD wajib diisi (huruf/angka/garis bawah).', 'warning');
            redirect($this->tujuan . '/ttd');
            return;
        }
        if ($jabatan === '') {
            $this->pesan('Jabatan TTD wajib diisi.', 'warning');
            redirect($this->tujuan . '/ttd');
            return;
        }
        if ($this->Model_mri_risk_owner->kodeTtdDipakai($kode, $id)) {
            $this->pesan('Kode TTD <strong>' . html_escape($kode) . '</strong> sudah dipakai baris lain.', 'warning');
            redirect($this->tujuan . '/ttd');
            return;
        }

        $data = [
            'kode_ttd'     => $kode,
            'jabatan_ttd'  => $jabatan,
            'id_bagian'    => $this->angka('id_bagian') ?: null,
            'nama_petugas' => $this->teks('nama_petugas') ?: null,
            'nik'          => $this->teks('nik') ?: null,
            'status'       => $this->teks('status') === '1' ? 1 : 0,
        ];

        $ok = $this->Model_mri_risk_owner->simpanTtd($id, $data);
        $this->pesan(
            $ok
                ? 'Petugas TTD <strong>' . html_escape($jabatan) . '</strong> ' . ($id ? 'berhasil diubah.' : 'berhasil ditambahkan.')
                : 'Gagal menyimpan petugas TTD.',
            $ok ? 'success' : 'danger'
        );
        redirect($this->tujuan . '/ttd');
    }

    public function ttd_hapus($id = null)
    {
        if (!$this->tolakPetugas('menghapus petugas tanda tangan')) {
            redirect($this->tujuan . '/ttd');
            return;
        }
        $ttd = $this->Model_mri_risk_owner->getTtdById((int) $id);
        if (!$ttd) {
            $this->pesan('Petugas TTD tidak ditemukan.', 'warning');
            redirect($this->tujuan . '/ttd');
            return;
        }

        $ok = $this->Model_mri_risk_owner->hapusTtd((int) $id);
        $this->pesan(
            $ok ? 'Petugas TTD <strong>' . html_escape($ttd->jabatan_ttd) . '</strong> dihapus.' : 'Gagal menghapus petugas TTD.',
            $ok ? 'success' : 'danger'
        );
        redirect($this->tujuan . '/ttd');
    }

    // ---------------- mri_risk_owner ----------------

    public function owner()
    {
        $this->render('manrisk/view_pengaturan_owner', [
            'title'       => 'PENGATURAN · RISK OWNER',
            'owner_list'  => $this->Model_mri_risk_owner->getOwner(false),
            'unit_list'   => $this->unitList(),
            'akses_semua' => $this->aksesSemuaUnit(),
        ]);
    }

    public function owner_simpan()
    {
        if (!$this->diposting()) {
            redirect($this->tujuan . '/owner');
            return;
        }
        if (!$this->tolakPetugas('mengubah data risk owner')) {
            redirect($this->tujuan . '/owner');
            return;
        }

        $id   = $this->angka('id_risk_owner');
        $nama = $this->teks('nama_owner');
        if ($nama === '') {
            $this->pesan('Nama risk owner wajib diisi.', 'warning');
            redirect($this->tujuan . '/owner');
            return;
        }

        $data = [
            'nama_owner'    => $nama,
            'nik'           => $this->teks('nik') ?: null,
            'jabatan_owner' => $this->teks('jabatan_owner') ?: null,
            'id_bagian'     => $this->angka('id_bagian') ?: null,
            'kewenangan'    => $this->teks('kewenangan') ?: null,
            'status'        => $this->teks('status') === '1' ? 1 : 0,
        ];

        $ok = $this->Model_mri_risk_owner->simpanOwner($id, $data);
        $this->pesan(
            $ok
                ? 'Risk owner <strong>' . html_escape($nama) . '</strong> ' . ($id ? 'berhasil diubah.' : 'berhasil ditambahkan.')
                : 'Gagal menyimpan risk owner.',
            $ok ? 'success' : 'danger'
        );
        redirect($this->tujuan . '/owner');
    }

    public function owner_hapus($id = null)
    {
        if (!$this->tolakPetugas('menghapus data risk owner')) {
            redirect($this->tujuan . '/owner');
            return;
        }
        $owner = $this->Model_mri_risk_owner->getOwnerById((int) $id);
        if (!$owner) {
            $this->pesan('Risk owner tidak ditemukan.', 'warning');
            redirect($this->tujuan . '/owner');
            return;
        }

        $dipakai = $this->Model_mri_risk_owner->jumlahAnalisaOwner((int) $id);
        if ($dipakai > 0) {
            $this->pesan(
                'Risk owner <strong>' . html_escape($owner->nama_owner) . '</strong> tidak dapat dihapus karena masih dipakai pada '
                . $dipakai . ' analisa risiko. Nonaktifkan saja bila tidak lagi dipakai.',
                'warning'
            );
            redirect($this->tujuan . '/owner');
            return;
        }

        $ok = $this->Model_mri_risk_owner->hapusOwner((int) $id);
        $this->pesan(
            $ok ? 'Risk owner <strong>' . html_escape($owner->nama_owner) . '</strong> dihapus.' : 'Gagal menghapus risk owner.',
            $ok ? 'success' : 'danger'
        );
        redirect($this->tujuan . '/owner');
    }

    // =================================================================
    // Master risiko: proses (risk universe), kategori, skala level
    // =================================================================

    // ---------------- mri_proses ----------------

    public function proses()
    {
        $this->render('manrisk/view_pengaturan_proses', [
            'title'       => 'PENGATURAN · PROSES (RISK UNIVERSE)',
            'proses_list' => $this->Model_mri_master->getProses(false),
            'unit_list'   => $this->unitList(),
            'akses_semua' => $this->aksesSemuaUnit(),
        ]);
    }

    public function proses_simpan()
    {
        if (!$this->diposting()) {
            redirect($this->tujuan . '/proses');
            return;
        }
        if (!$this->tolakPetugas('mengubah master proses')) {
            redirect($this->tujuan . '/proses');
            return;
        }

        $id   = $this->angka('id_proses');
        $kode = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $this->teks('kode_proses')));
        $nama = $this->teks('nama_proses');

        if ($kode === '') {
            $this->pesan('Kode proses wajib diisi (huruf/angka, maksimal 5 karakter).', 'warning');
            redirect($this->tujuan . '/proses');
            return;
        }
        if (mb_strlen($kode) > 5) {
            $this->pesan('Kode proses maksimal 5 karakter.', 'warning');
            redirect($this->tujuan . '/proses');
            return;
        }
        if ($nama === '') {
            $this->pesan('Nama proses wajib diisi.', 'warning');
            redirect($this->tujuan . '/proses');
            return;
        }
        if ($this->Model_mri_master->kodeProsesDipakai($kode, $id)) {
            $this->pesan('Kode proses <strong>' . html_escape($kode) . '</strong> sudah dipakai.', 'warning');
            redirect($this->tujuan . '/proses');
            return;
        }

        $data = [
            'kode_proses'          => $kode,
            'nama_proses'          => $nama,
            'grup_tema'            => $this->teks('grup_tema') ?: null,
            'id_bagian_penanggung' => $this->angka('id_bagian_penanggung') ?: null,
            'deskripsi'            => $this->teks('deskripsi') ?: null,
            'urut'                 => (int) $this->angka('urut', 0),
            'status'               => $this->teks('status') === '1' ? 1 : 0,
        ];

        $ok = $this->Model_mri_master->simpanProses($id, $data);
        $this->pesan(
            $ok
                ? 'Proses <strong>' . html_escape($kode . ' · ' . $nama) . '</strong> ' . ($id ? 'berhasil diubah.' : 'berhasil ditambahkan.')
                : 'Gagal menyimpan proses.',
            $ok ? 'success' : 'danger'
        );
        redirect($this->tujuan . '/proses');
    }

    public function proses_hapus($id = null)
    {
        if (!$this->tolakPetugas('menghapus master proses')) {
            redirect($this->tujuan . '/proses');
            return;
        }
        $row = $this->Model_mri_master->getProsesById((int) $id);
        if (!$row) {
            $this->pesan('Proses tidak ditemukan.', 'warning');
            redirect($this->tujuan . '/proses');
            return;
        }

        $pakai = $this->Model_mri_master->jumlahPemakaianProses((int) $id);
        if ($pakai['objektif'] > 0 || $pakai['risiko'] > 0) {
            $this->pesan(
                'Proses <strong>' . html_escape($row->kode_proses . ' · ' . $row->nama_proses) . '</strong> tidak dapat dihapus: dipakai oleh '
                . $pakai['objektif'] . ' objektif dan ' . $pakai['risiko'] . ' pemetaan risiko proses. Nonaktifkan saja bila tidak dipakai lagi.',
                'warning'
            );
            redirect($this->tujuan . '/proses');
            return;
        }

        $ok = $this->Model_mri_master->hapusProses((int) $id);
        $this->pesan(
            $ok ? 'Proses <strong>' . html_escape($row->nama_proses) . '</strong> dihapus.' : 'Gagal menghapus proses.',
            $ok ? 'success' : 'danger'
        );
        redirect($this->tujuan . '/proses');
    }

    // ---------------- mri_kategori_risiko ----------------

    public function kategori()
    {
        $this->render('manrisk/view_pengaturan_kategori', [
            'title'         => 'PENGATURAN · KATEGORI RISIKO',
            'kategori_list' => $this->Model_mri_master->getKategori(false),
            'akses_semua'   => $this->aksesSemuaUnit(),
        ]);
    }

    public function kategori_simpan()
    {
        if (!$this->diposting()) {
            redirect($this->tujuan . '/kategori');
            return;
        }
        if (!$this->tolakPetugas('mengubah kategori risiko')) {
            redirect($this->tujuan . '/kategori');
            return;
        }

        $id   = $this->angka('id_kategori');
        $kode = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $this->teks('kode_kat')));
        $nama = $this->teks('nama_kat');

        if ($kode === '' || mb_strlen($kode) > 10) {
            $this->pesan('Kode kategori wajib diisi, maksimal 10 karakter huruf/angka.', 'warning');
            redirect($this->tujuan . '/kategori');
            return;
        }
        if ($nama === '') {
            $this->pesan('Nama kategori wajib diisi.', 'warning');
            redirect($this->tujuan . '/kategori');
            return;
        }
        if ($this->Model_mri_master->kodeKategoriDipakai($kode, $id)) {
            $this->pesan('Kode kategori <strong>' . html_escape($kode) . '</strong> sudah dipakai.', 'warning');
            redirect($this->tujuan . '/kategori');
            return;
        }

        $alias = $this->teks('nama_alias');
        $data = [
            'kode_kat'      => $kode,
            'nama_kat'      => $nama,
            'nama_alias'    => $alias !== '' ? $alias : $nama,
            'kode_legacy'   => $this->teks('kode_legacy') ?: null,
            'deskripsi_kat' => $this->teks('deskripsi_kat') ?: null,
            'contoh_kat'    => $this->teks('contoh_kat') ?: null,
            'urut'          => (int) $this->angka('urut', 0),
            'status'        => $this->teks('status') === '1' ? 1 : 0,
        ];

        $ok = $this->Model_mri_master->simpanKategori($data, $id);
        $this->pesan(
            $ok
                ? 'Kategori <strong>' . html_escape($kode . ' · ' . $nama) . '</strong> ' . ($id ? 'berhasil diubah.' : 'berhasil ditambahkan.')
                : 'Gagal menyimpan kategori.',
            $ok ? 'success' : 'danger'
        );
        redirect($this->tujuan . '/kategori');
    }

    public function kategori_hapus($id = null)
    {
        if (!$this->tolakPetugas('menghapus kategori risiko')) {
            redirect($this->tujuan . '/kategori');
            return;
        }
        $row = $this->Model_mri_master->getKategoriById((int) $id);
        if (!$row) {
            $this->pesan('Kategori tidak ditemukan.', 'warning');
            redirect($this->tujuan . '/kategori');
            return;
        }

        $pakai = $this->Model_mri_master->jumlahPemakaianKategori((int) $id);
        if ($pakai['register'] > 0 || $pakai['appetite'] > 0) {
            $this->pesan(
                'Kategori <strong>' . html_escape($row->kode_kat . ' · ' . $row->nama_kat) . '</strong> tidak dapat dihapus: dipakai oleh '
                . $pakai['register'] . ' risiko dan ' . $pakai['appetite'] . ' risk appetite. Nonaktifkan saja bila tidak dipakai lagi.',
                'warning'
            );
            redirect($this->tujuan . '/kategori');
            return;
        }

        $ok = $this->Model_mri_master->hapusKategori((int) $id);
        $this->pesan(
            $ok ? 'Kategori <strong>' . html_escape($row->nama_kat) . '</strong> dihapus.' : 'Gagal menghapus kategori.',
            $ok ? 'success' : 'danger'
        );
        redirect($this->tujuan . '/kategori');
    }

    // ---------------- mri_tingkat_risiko ----------------

    public function tingkat()
    {
        $this->render('manrisk/view_pengaturan_tingkat', [
            'title'       => 'PENGATURAN · SKALA LEVEL RISIKO',
            'tingkat'     => $this->Model_mri_master->getTingkat(false),
            'akses_semua' => $this->aksesSemuaUnit(),
        ]);
    }

    public function tingkat_simpan()
    {
        if (!$this->diposting()) {
            redirect($this->tujuan . '/tingkat');
            return;
        }
        if (!$this->tolakPetugas('mengubah skala level risiko')) {
            redirect($this->tujuan . '/tingkat');
            return;
        }

        $id    = $this->angka('id_tingkat');
        $nama  = $this->teks('nama_tingkat');
        $min   = (int) $this->angka('skor_min', 0);
        $max   = (int) $this->angka('skor_max', 0);
        $aksi  = $this->teks('aksi');
        $warna = $this->teks('warna');

        if ($nama === '') {
            $this->pesan('Nama level wajib diisi.', 'warning');
            redirect($this->tujuan . '/tingkat');
            return;
        }
        if ($min <= 0 || $max <= 0 || $min > $max) {
            $this->pesan('Skor minimal/maksimal tidak valid (minimal 1 dan min tidak boleh lebih besar dari maksimal).', 'warning');
            redirect($this->tujuan . '/tingkat');
            return;
        }
        if (!in_array($aksi, ['Accept', 'Monitor', 'Treat', 'Escalate'], true)) {
            $this->pesan('Aksi level harus salah satu: Accept, Monitor, Treat, Escalate.', 'warning');
            redirect($this->tujuan . '/tingkat');
            return;
        }
        if ($this->Model_mri_master->rentangSkorBentrok($min, $max, $id)) {
            $this->pesan('Rentang skor ' . $min . '–' . $max . ' tumpang tindih dengan level lain.', 'warning');
            redirect($this->tujuan . '/tingkat');
            return;
        }

        $data = [
            'urut_tampil'  => (int) $this->angka('urut_tampil', $min),
            'nama_tingkat' => $nama,
            'skor_min'     => $min,
            'skor_max'     => $max,
            'warna'        => $warna !== '' ? $warna : null,
            'aksi'         => $aksi,
            'deskripsi'    => $this->teks('deskripsi') ?: null,
            'status'       => $this->teks('status') === '1' ? 1 : 0,
        ];

        $ok = $this->Model_mri_master->simpanTingkat($id, $data);
        $this->pesan(
            $ok
                ? 'Level <strong>' . html_escape($nama) . '</strong> ' . ($id ? 'berhasil diubah.' : 'berhasil ditambahkan.')
                : 'Gagal menyimpan level.',
            $ok ? 'success' : 'danger'
        );
        redirect($this->tujuan . '/tingkat');
    }

    public function tingkat_hapus($id = null)
    {
        if (!$this->tolakPetugas('menghapus skala level risiko')) {
            redirect($this->tujuan . '/tingkat');
            return;
        }
        $row = $this->Model_mri_master->getTingkatById((int) $id);
        if (!$row) {
            $this->pesan('Level tidak ditemukan.', 'warning');
            redirect($this->tujuan . '/tingkat');
            return;
        }

        $dipakai = $this->Model_mri_master->jumlahPemakaianTingkat($row->nama_tingkat);
        if ($dipakai > 0) {
            $this->pesan(
                'Level <strong>' . html_escape($row->nama_tingkat) . '</strong> tidak dapat dihapus karena masih dipakai pada '
                . $dipakai . ' analisa/riwayat skor. Nonaktifkan saja bila tidak dipakai lagi.',
                'warning'
            );
            redirect($this->tujuan . '/tingkat');
            return;
        }

        $ok = $this->Model_mri_master->hapusTingkat((int) $id);
        $this->pesan(
            $ok ? 'Level <strong>' . html_escape($row->nama_tingkat) . '</strong> dihapus.' : 'Gagal menghapus level.',
            $ok ? 'success' : 'danger'
        );
        redirect($this->tujuan . '/tingkat');
    }
}
