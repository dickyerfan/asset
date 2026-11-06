<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Laporan manajemen risiko (§10.1 dokumentasi MR):
 * Operasional, Manajemen, Dewan Pengawas, Tahunan.
 *
 * Isi laporan disusun dari data proses (Register → Analisis → Evaluasi →
 * Perlakuan → Monitoring). Tombol "Susun Laporan Otomatis" membuat draf
 * lengkap; pengguna hanya perlu meninjau dan mengubah bagian yang memang
 * membutuhkan penilaian manusia.
 */
class Laporan extends MY_Controller
{
    private $tujuan = 'manrisk/laporan';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_laporan');
        $this->load->model('Model_mri_dashboard');
        $this->load->model('Model_mri_master');
        $this->load->model('Model_mri_pengaturan');
        $this->load->model('Model_mri_register');
        $this->load->model('Model_mri_perlakuan');
        $this->load->model('Model_mri_lingkup');
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

        $f = $idUpk ? ['id_upk' => $idUpk] : [];

        $this->render('manrisk/view_laporan', [
            'title'       => 'LAPORAN MANAJEMEN RISIKO',
            'tahun'       => $tahun,
            'daftar'      => $this->Model_mri_laporan->getLaporan($tahun, null, $idUpk),
            'ringkasan'   => $this->Model_mri_dashboard->rangkum($tahun, $f),
            'tingkat'     => $this->Model_mri_master->getTingkat(),
            'unit_list'   => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
            'jenis'       => Model_mri_laporan::JENIS_LAPORAN,
            'akses_semua' => $this->aksesSemuaUnit(),
            'boleh_tulis' => $this->Model_mri_pengaturan->bolehUbah('laporan_risiko'),
            'tw_aktif'    => (int) ceil((int) date('n') / 3),
        ]);
    }

    /** Tambah / ubah laporan. */
    public function form($id = null)
    {
        $l = $id ? $this->Model_mri_laporan->getLaporanById($id) : null;
        if ($id && !$l) {
            $this->pesanLaporan('Laporan tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        // Penegakan akses unit: pengguna unit hanya boleh menyentuh laporannya.
        if ($l && !$this->aksesSemuaUnit() && (int) $l->id_upk !== (int) $this->unitPaksa()) {
            $this->pesanLaporan('Anda tidak punya akses ke laporan unit tersebut.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('laporan_risiko')) {
            $this->pesanLaporan('Laporan sedang dikunci. Buka kunci pada Pengaturan.', 'warning');
            redirect($this->tujuan);
            return;
        }

        // Satu periode + jenis + cakupan hanya boleh punya satu laporan.
        // Cegah error duplicate key: arahkan ke laporan yang sudah ada.
        if ($this->diposting()) {
            $idUpkPost = $this->aksesSemuaUnit()
                ? (int) $this->angka('id_upk')
                : (int) $this->unitPaksa();
            $lain = $this->Model_mri_laporan->getLaporanByPeriode(
                (int) $this->input->post('tahun', true),
                max(1, min(4, (int) $this->input->post('triwulan', true))),
                $this->teks('jenis_laporan'),
                $idUpkPost
            );
            if ($lain && (!$id || (int) $lain->id_laporan !== (int) $id)) {
                $this->pesanLaporan(
                    'Laporan untuk periode dan cakupan ini sudah ada. '
                    . 'Gunakan <strong>Susun Ulang dari Data</strong> pada laporan tersebut '
                    . 'agar tidak membuat baris ganda.',
                    'warning'
                );
                redirect($this->tujuan . '/form/' . $lain->id_laporan);
                return;
            }
        }

        $this->form_validation->set_rules('jenis_laporan', 'Jenis Laporan',
            'required|in_list[Operasional,Manajemen,Dewan Pengawas,Tahunan]');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|integer');
        $this->form_validation->set_message('in_list', '%s tidak valid');
        $this->form_validation->set_message('required', '%s masih kosong');

        if ($this->diposting() && $this->form_validation->run() === true) {
            $idUpk = (int) $this->angka('id_upk');
            if (!$this->aksesSemuaUnit()) {
                $idUpk = (int) $this->unitPaksa(); // unit pengguna dipaksa
            }
            $d = [
                'tahun'                 => (int) $this->input->post('tahun', true),
                'triwulan'              => max(1, min(4, (int) $this->input->post('triwulan', true))),
                'jenis_laporan'         => $this->teks('jenis_laporan'),
                'id_upk'                => $idUpk,
                'ringkasan_eksekutif'   => $this->teks('ringkasan_eksekutif'),
                'latar_belakang'        => $this->teks('latar_belakang'),
                'tujuan_laporan'        => $this->teks('tujuan_laporan'),
                'ruang_lingkup'         => $this->teks('ruang_lingkup'),
                'rekap_inherent_residual' => $this->teks('rekap_inherent_residual'),
                'catatan_peta_risiko'   => $this->teks('catatan_peta_risiko'),
                'rekap_peluang'         => $this->teks('rekap_peluang'),
                'evaluasi_tindak_lanjut' => $this->teks('evaluasi_tindak_lanjut'),
                'kesimpulan'            => $this->teks('kesimpulan'),
                'rencana_tindak_lanjut' => $this->teks('rencana_tindak_lanjut'),
                'sumber_catatan'        => $this->teks('sumber_catatan'),
                'status'                => $this->teks('status') === 'FINAL' ? 'FINAL' : 'DRAFT',
            ];
            if ($d['status'] === 'FINAL') {
                $d['disahkan_oleh'] = $l && $l->disahkan_oleh
                    ? $l->disahkan_oleh
                    : ($this->session->userdata('nama_lengkap') ?: $this->session->userdata('nama_pengguna'));
                $d['tanggal_sahkan'] = $l && $l->tanggal_sahkan ? $l->tanggal_sahkan : date('Y-m-d');
            }
            $this->Model_mri_laporan->simpanLaporan($id, $d);
            $this->pesanLaporan('Laporan <strong>' . html_escape($d['jenis_laporan']) . '</strong> disimpan.');
            redirect($this->tujuan);
            return;
        }

        $this->render('manrisk/view_laporan_form', [
            'title'     => $id ? 'UBAH LAPORAN' : 'TAMBAH LAPORAN',
            'laporan'   => $l,
            'tahun'     => $this->tahunAktif(2027),
            'jenis'     => Model_mri_laporan::JENIS_LAPORAN,
            'unit_list' => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
        ]);
    }

    /**
     * Susun laporan lengkap dari data (satu klik).
     *
     * Klik berulang memperbarui draf yang sama (tahun + TW + jenis + unit),
     * bukan membuat baris ganda. Laporan FINAL tidak ditimpa.
     */
    public function otomatis()
    {
        if (!$this->diposting()) {
            redirect($this->tujuan);
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('laporan_risiko')) {
            $this->pesanLaporan('Laporan sedang dikunci.', 'warning');
            redirect($this->tujuan);
            return;
        }

        $tahun    = max(2027, (int) $this->input->post('tahun', true));
        $triwulan = max(1, min(4, (int) $this->input->post('triwulan', true)));
        $jenis    = $this->teks('jenis_laporan');
        if (!in_array($jenis, Model_mri_laporan::JENIS_LAPORAN, true)) {
            $jenis = 'Operasional';
        }
        $idUpk = $this->aksesSemuaUnit()
            ? (int) $this->angka('id_upk')
            : (int) $this->unitPaksa();

        $lama = $this->Model_mri_laporan->getLaporanByPeriode($tahun, $triwulan, $jenis, $idUpk);
        if ($lama && $lama->status === 'FINAL') {
            $this->pesanLaporan(
                'Laporan <strong>' . html_escape($jenis) . ' TW' . $triwulan . ' ' . $tahun
                . '</strong> sudah <strong>FINAL</strong>. Ubah statusnya menjadi DRAFT terlebih dahulu '
                . 'bila ingin menyusun ulang.',
                'warning'
            );
            redirect($this->tujuan . '/form/' . $lama->id_laporan);
            return;
        }

        $d = $this->susunLaporanOtomatis($tahun, $triwulan, $jenis, $idUpk);
        $d['tahun']         = $tahun;
        $d['triwulan']      = $triwulan;
        $d['jenis_laporan'] = $jenis;
        $d['id_upk']        = $idUpk;
        $d['status']        = 'DRAFT';

        if ($lama) {
            $this->Model_mri_laporan->simpanLaporan($lama->id_laporan, $d);
            $id = $lama->id_laporan;
            $aksi = 'diperbarui';
        } else {
            $id = $this->Model_mri_laporan->simpanLaporan(null, $d);
            if (!$id) {
                $this->pesanLaporan('Gagal menyimpan draf laporan. Silakan coba lagi.', 'danger');
                redirect($this->tujuan);
                return;
            }
            $aksi = 'dibuat';
        }

        $this->pesanLaporan(
            'Draf laporan <strong>' . html_escape($jenis) . ' TW' . $triwulan . ' ' . $tahun
            . '</strong> ' . $aksi . ' otomatis dari data Register sampai Monitoring. '
            . 'Tinjau, ubah bila perlu, lalu sahkan.'
        );
        redirect($this->tujuan . '/form/' . $id);
    }

    /**
     * Isi otomatis bagian laporan yang masih KOSONG.
     *
     * Teks yang sudah ditulis pengguna tidak pernah ditimpa. Untuk menyusun
     * ulang seluruhnya, gunakan tombol "Susun Ulang dari Data".
     */
    public function isi_dari_data($id = null)
    {
        $l = $id ? $this->Model_mri_laporan->getLaporanById($id) : null;
        if (!$l) {
            $this->pesanLaporan('Laporan tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->aksesSemuaUnit() && (int) $l->id_upk !== (int) $this->unitPaksa()) {
            $this->pesanLaporan('Anda tidak punya akses ke laporan unit tersebut.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('laporan_risiko')) {
            $this->pesanLaporan('Laporan sedang dikunci.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if ($l->status === 'FINAL') {
            $this->pesanLaporan(
                'Laporan ini sudah <strong>FINAL</strong>. Ubah statusnya menjadi DRAFT '
                . 'terlebih dahulu bila ingin mengisi dari data.',
                'warning'
            );
            redirect($this->tujuan . '/form/' . $id);
            return;
        }
        if (!$this->diposting()) {
            redirect($this->tujuan . '/form/' . $id);
            return;
        }

        $auto = $this->susunLaporanOtomatis(
            (int) $l->tahun,
            (int) $l->triwulan,
            $l->jenis_laporan,
            (int) $l->id_upk
        );

        $isi = [];
        foreach ($auto as $kolom => $teks) {
            if (trim((string) $l->{$kolom}) === '' && trim((string) $teks) !== '') {
                $isi[$kolom] = $teks;
            }
        }

        if ($isi) {
            $this->Model_mri_laporan->simpanLaporan($id, $isi);
        }

        $this->pesanLaporan($isi
            ? '<strong>' . count($isi) . '</strong> bagian kosong terisi otomatis dari data. Teks yang sudah ada tidak diubah.'
            : 'Tidak ada bagian kosong — semua teks sudah terisi dan tidak akan ditimpa.');
        redirect($this->tujuan . '/form/' . $id);
    }

    /** Cetak laporan ke PDF (dompdf) tanpa mengubah status. */
    public function cetak_pdf($id = null)
    {
        $l = $id ? $this->Model_mri_laporan->getLaporanById($id) : null;
        if (!$l) {
            $this->pesanLaporan('Laporan tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->aksesSemuaUnit() && (int) $l->id_upk !== (int) $this->unitPaksa()) {
            $this->pesanLaporan('Anda tidak punya akses ke laporan unit tersebut.', 'warning');
            redirect($this->tujuan);
            return;
        }

        $idUpk = (int) $l->id_upk;
        $namaUnit = $this->namaCakupanById($idUpk);
        $f = $idUpk ? ['id_upk' => $idUpk] : [];

        $perlakuan = $this->Model_mri_perlakuan->getListPerlakuan((int) $l->tahun, $idUpk ?: null);
        $daftar = $this->Model_mri_register->getListDenganSkor(
            array_merge(['tahun' => (int) $l->tahun], $f)
        );
        $daftar = $this->lengkapiDaftarLaporan($daftar, $perlakuan);

        $this->load->library('Pdf');
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->filename = 'Laporan_MR_' . preg_replace('/\s+/', '_', $l->jenis_laporan)
            . '_TW' . (int) $l->triwulan . '_' . (int) $l->tahun . '.pdf';

        $this->pdf->generate('manrisk/cetak_laporan_pdf', [
            'title'     => 'LAPORAN ' . strtoupper($l->jenis_laporan) . ' MANAJEMEN RISIKO',
            'laporan'   => $l,
            'nama_unit' => $namaUnit,
            'triwulan'  => 'TW' . (int) $l->triwulan,
            'data'      => [
                'ringkasan'  => $this->Model_mri_dashboard->rangkum((int) $l->tahun, $f),
                'daftar'     => $daftar,
                'perlakuan'  => $perlakuan,
                'monitoring' => $this->ambilMonitoring((int) $l->tahun, (int) $l->triwulan, $idUpk),
            ],
        ]);
    }

    /** Pengesahan laporan (menjadikan FINAL). */
    public function sahkan($id = null)
    {
        $l = $this->Model_mri_laporan->getLaporanById($id);
        if (!$l) {
            $this->pesanLaporan('Laporan tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->aksesSemuaUnit() && (int) $l->id_upk !== (int) $this->unitPaksa()) {
            $this->pesanLaporan('Anda tidak punya akses ke laporan unit tersebut.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('laporan_risiko')) {
            $this->pesanLaporan('Laporan sedang dikunci.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if ($this->diposting()) {
            $this->Model_mri_laporan->setujuiLaporan(
                $id,
                $this->teks('disahkan_oleh') ?: $this->session->userdata('nama_lengkap')
            );
            $this->pesanLaporan('Laporan <strong>' . html_escape($l->jenis_laporan) . ' ' . $l->tahun . '</strong> disahkan.');
        }
        redirect($this->tujuan);
    }

    // ---------------------------------------------------------------
    //  Pribadi
    // ---------------------------------------------------------------

    /** Pesan persisten tanpa kelas .alert (footer menghapus .alert otomatis). */
    private function pesanLaporan($teks, $jenis = 'success')
    {
        $kelas = $jenis === 'danger'
            ? 'border-danger'
            : ($jenis === 'warning' ? 'border-warning' : 'border-success');
        $this->session->set_flashdata(
            'info_laporan',
            '<div class="border ' . $kelas . ' rounded bg-white p-2 mb-3">' . $teks . '</div>'
        );
    }

    /** Catatan monitoring satu triwulan (opsional difilter unit). */
    private function ambilMonitoring($tahun, $triwulan, $idUpk = 0)
    {
        $this->db->select('r.kode_risiko, r.risiko, m.*');
        $this->db->from('mri_monitoring m');
        $this->db->join('mri_register r', 'r.id_register = m.id_register');
        $this->db->where('m.tahun', $tahun);
        $this->db->where('m.triwulan', $triwulan);
        if ($idUpk) {
            $this->db->where('r.id_upk', $idUpk);
        }
        $this->db->order_by('m.id_monitoring', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Tempelkan keputusan evaluasi & status perlakuan ke baris register
     * agar lampiran PDF tidak perlu query per baris.
     */
    private function lengkapiDaftarLaporan($daftar, $perlakuan)
    {
        $evaluasi = [];
        foreach ($this->db
            ->select('id_register, keputusan, level_evaluasi, nilai_evaluasi')
            ->get('mri_evaluasi')->result() as $e) {
            $evaluasi[$e->id_register] = $e;
        }
        $perl = [];
        foreach ($perlakuan as $p) {
            $perl[$p->id_register] = $p;
        }

        foreach ($daftar as $r) {
            $e = isset($evaluasi[$r->id_register]) ? $evaluasi[$r->id_register] : null;
            $p = isset($perl[$r->id_register]) ? $perl[$r->id_register] : null;
            $r->keputusan       = $e ? $e->keputusan : null;
            $r->level_evaluasi  = $e ? $e->level_evaluasi : null;
            $r->perlakuan_status = $p ? $p->status_laksana : null;
            $r->perlakuan_uraian = $p ? $p->uraian_tindakan : null;
            $r->perlakuan_pic    = $p ? $p->nama_pic : null;
            $r->perlakuan_jadwal = $p ? $p->jadwal_selesai : null;
        }
        return $daftar;
    }

    /**
     * Susun SELURUH bagian laporan dari data proses.
     *
     * @return array kolom mri_laporan => teks
     */
    private function susunLaporanOtomatis($tahun, $triwulan, $jenis, $idUpk)
    {
        $tahun    = (int) $tahun;
        $triwulan = (int) $triwulan;
        $idUpk    = (int) $idUpk;
        $tw       = 'TW' . $triwulan;
        $f        = $idUpk ? ['id_upk' => $idUpk] : [];

        $ringkasan     = $this->Model_mri_dashboard->rangkum($tahun, $f);
        $monitoring    = $this->ambilMonitoring($tahun, $triwulan, $idUpk);
        $perlakuan     = $this->Model_mri_perlakuan->getListPerlakuan($tahun, $idUpk ?: null);
        $tanpaPerlakuan = $this->Model_mri_perlakuan->getRisikoTanpaPerlakuan($tahun, $idUpk ?: null);
        $lingkup       = $this->lingkupUntuk($tahun, $idUpk);
        $namaUnit      = $this->namaCakupanById($idUpk);

        $total   = (int) $ringkasan['total_risiko'];
        $lvlRes  = $ringkasan['level_residual'];
        $lvlInh  = $ringkasan['level_inherent'];
        $kep     = $ringkasan['keputusan'];
        $prog    = $ringkasan['progres'];

        $totalPerlakuan = array_sum($prog);
        $selesai        = isset($prog['SELESAI']) ? (int) $prog['SELESAI'] : 0;
        $proses         = isset($prog['DALAM_PROSES']) ? (int) $prog['DALAM_PROSES'] : 0;
        $belum          = isset($prog['BELUM']) ? (int) $prog['BELUM'] : 0;

        $naik = $turun = $tetap = 0;
        foreach ($monitoring as $m) {
            if ($m->perubahan === 'Naik') {
                $naik++;
            } elseif ($m->perubahan === 'Turun') {
                $turun++;
            } elseif ($m->perubahan === 'Tetap') {
                $tetap++;
            }
        }

        $overdue = [];
        $hariIni = date('Y-m-d');
        foreach ($perlakuan as $p) {
            if ($p->status_laksana !== 'SELESAI'
                && !empty($p->jadwal_selesai)
                && $p->jadwal_selesai < $hariIni) {
                $overdue[] = $p;
            }
        }

        $auto = [];

        // 1) Ringkasan eksekutif
        if ($total > 0) {
            $auto['ringkasan_eksekutif'] = sprintf(
                "Pada %s tahun %d, cakupan %s mencatat %d risiko. "
                . "Sebaran residual: Tinggi %d, Sedang %d, Rendah %d. "
                . "Keputusan evaluasi: Accept %d, Monitor %d, Treat %d, Escalate %d. "
                . "Progres perlakuan: selesai %d dari %d rencana, dalam proses %d, belum dimulai %d. "
                . "Pada periode ini tercatat %d catatan monitoring: %d naik, %d turun, %d tetap.",
                $tw,
                $tahun,
                $namaUnit,
                $total,
                $this->angkaLevel($lvlRes, 'Tinggi'),
                $this->angkaLevel($lvlRes, 'Sedang'),
                $this->angkaLevel($lvlRes, 'Rendah'),
                isset($kep['Accept']) ? (int) $kep['Accept'] : 0,
                isset($kep['Monitor']) ? (int) $kep['Monitor'] : 0,
                isset($kep['Treat']) ? (int) $kep['Treat'] : 0,
                isset($kep['Escalate']) ? (int) $kep['Escalate'] : 0,
                $selesai,
                $totalPerlakuan,
                $proses,
                $belum,
                count($monitoring),
                $naik,
                $turun,
                $tetap
            );
        } else {
            $auto['ringkasan_eksekutif'] = sprintf(
                "Pada %s tahun %d, belum ada risiko yang tercatat pada cakupan %s.",
                $tw,
                $tahun,
                $namaUnit
            );
        }

        // 2) Latar belakang — dari dokumen Lingkup
        if ($lingkup) {
            $auto['latar_belakang'] = sprintf(
                "Laporan ini disusun sebagai bagian dari penerapan SNI ISO 31000:2018 pada Perumdam Ijen Tirta. "
                . "Lingkup %s tahun %d menetapkan objek evaluasi: %s. Batas evaluasi: %s. "
                . "Konteks internal: %s. Konteks eksternal: %s. Kriteria acuan: %s.",
                $this->ringkasTeks($lingkup->nama_lingkup, 200) ?: '-',
                $tahun,
                $this->ringkasTeks($lingkup->objek_evaluasi, 500) ?: '-',
                $this->ringkasTeks($lingkup->batas_evaluasi, 500) ?: '-',
                $this->ringkasTeks($lingkup->konteks_internal, 500) ?: '-',
                $this->ringkasTeks($lingkup->konteks_eksternal, 500) ?: '-',
                $this->ringkasTeks($lingkup->kriteria_acuan, 500) ?: '-'
            );
        } else {
            $auto['latar_belakang'] = sprintf(
                "Laporan ini disusun sebagai bagian dari penerapan SNI ISO 31000:2018 pada Perumdam Ijen Tirta. "
                . "Dokumen lingkup/konteks untuk tahun %d belum ditetapkan, sehingga laporan disusun berdasarkan "
                . "data Risk Register dan proses manajemen risiko yang tercatat.",
                $tahun
            );
        }

        // 3) Tujuan laporan — mengikuti jenis laporan
        $tujuan = [
            'Operasional' => 'Memberikan informasi berkala kepada pengelola risiko dan unit kerja mengenai perkembangan risiko, efektivitas kontrol, serta tindak lanjut pada triwulan yang dilaporkan.',
            'Manajemen'   => 'Menjadi dasar pengambilan keputusan manajemen mengenai prioritas risiko, alokasi sumber daya, dan percepatan tindak lanjut.',
            'Dewan Pengawas' => 'Memberikan gambaran kepada Dewan Pengawas mengenai profil risiko, efektivitas pengelolaan risiko, dan kepatuhan terhadap kebijakan manajemen risiko.',
            'Tahunan'     => 'Menyajikan laporan tahunan manajemen risiko sebagai bentuk akuntabilitas penerapan SNI ISO 31000:2018.',
        ];
        $auto['tujuan_laporan'] = isset($tujuan[$jenis]) ? $tujuan[$jenis] : $tujuan['Operasional'];

        // 4) Ruang lingkup
        $kategori = [];
        foreach ($ringkasan['per_kategori'] as $k) {
            $kategori[] = $k['nama'] . ' (' . (int) $k['total'] . ')';
        }
        $auto['ruang_lingkup'] = sprintf(
            "Laporan mencakup %d risiko pada %s untuk tahun %d, periode %s. Kategori risiko: %s. "
            . "Tipe risiko: Ancaman %d, Peluang %d. Data bersumber dari Risk Register, Analisis Risiko, "
            . "Evaluasi Risiko, Perlakuan Risiko, dan Monitoring.",
            $total,
            $namaUnit,
            $tahun,
            $tw,
            $kategori ? implode(', ', $kategori) : '-',
            isset($ringkasan['tipe']['Ancaman']) ? (int) $ringkasan['tipe']['Ancaman'] : 0,
            isset($ringkasan['tipe']['Peluang']) ? (int) $ringkasan['tipe']['Peluang'] : 0
        );

        // 5) Rekap inherent & residual
        $auto['rekap_inherent_residual'] = $this->barisSebaran('Inherent', $lvlInh, $total)
            . "\n" . $this->barisSebaran('Residual', $lvlRes, $total);

        // 6) Catatan peta risiko — top 5 residual
        $top = $ringkasan['prioritas'];
        if ($top) {
            $baris = [];
            foreach (array_slice($top, 0, 5) as $i => $r) {
                $baris[] = sprintf(
                    '%d. %s — %s (residual %d, %s)',
                    $i + 1,
                    $r->kode_risiko,
                    $this->ringkasTeks($r->risiko, 90),
                    (int) $r->nilai_residual,
                    (string) $r->level_residual
                );
            }
            $auto['catatan_peta_risiko'] = "Prioritas risiko residual tertinggi:\n" . implode("\n", $baris);
        } else {
            $auto['catatan_peta_risiko'] = 'Belum ada risiko dengan skor residual.';
        }

        // 7) Rekap peluang
        $peluang = [];
        foreach ($ringkasan['daftar'] as $r) {
            if ($r->tipe_risiko === 'Peluang') {
                $peluang[] = $r;
            }
        }
        if ($peluang) {
            $baris = [];
            foreach (array_slice($peluang, 0, 10) as $i => $r) {
                $baris[] = sprintf(
                    '%d. %s — %s (residual %s)',
                    $i + 1,
                    $r->kode_risiko,
                    $this->ringkasTeks($r->risiko, 80),
                    $r->nilai_residual !== null ? $r->nilai_residual : '-'
                );
            }
            $auto['rekap_peluang'] = sprintf(
                "Risiko bertipe Peluang: %d dari %d total risiko.\n%s",
                count($peluang),
                $total,
                implode("\n", $baris)
            );
        } else {
            $auto['rekap_peluang'] = sprintf(
                'Tidak ada risiko bertipe Peluang pada cakupan ini (%d risiko).',
                $total
            );
        }

        // 8) Evaluasi & tindak lanjut
        $baris = [];
        $baris[] = sprintf(
            'Keputusan evaluasi: Accept %d, Monitor %d, Treat %d, Escalate %d.',
            isset($kep['Accept']) ? (int) $kep['Accept'] : 0,
            isset($kep['Monitor']) ? (int) $kep['Monitor'] : 0,
            isset($kep['Treat']) ? (int) $kep['Treat'] : 0,
            isset($kep['Escalate']) ? (int) $kep['Escalate'] : 0
        );
        $baris[] = sprintf(
            'Progres perlakuan: belum dimulai %d, dalam proses %d, selesai %d dari %d rencana.',
            $belum,
            $proses,
            $selesai,
            $totalPerlakuan
        );
        if ($tanpaPerlakuan) {
            $baris[] = sprintf(
                'Risiko yang sudah dievaluasi tetapi belum memiliki rencana perlakuan: %d.',
                count($tanpaPerlakuan)
            );
        }
        if ($overdue) {
            $baris[] = sprintf('Perlakuan melewati jadwal: %d.', count($overdue));
        }
        $auto['evaluasi_tindak_lanjut'] = implode("\n", $baris);

        // 9) Kesimpulan
        $tinggi = $this->angkaLevel($lvlRes, 'Tinggi');
        $sedang = $this->angkaLevel($lvlRes, 'Sedang');
        $kesimpulan = sprintf(
            'Dari %d risiko pada %s, %d berada pada level residual Tinggi dan %d Sedang. ',
            $total,
            $namaUnit,
            $tinggi,
            $sedang
        );
        if ($totalPerlakuan > 0) {
            $kesimpulan .= sprintf(
                'Perlakuan selesai %d dari %d rencana; %d belum dimulai. ',
                $selesai,
                $totalPerlakuan,
                $belum
            );
        }
        if ($overdue) {
            $kesimpulan .= sprintf(
                'Terdapat %d perlakuan melewati jadwal yang memerlukan perhatian. ',
                count($overdue)
            );
        }
        if ($tinggi > 0) {
            $kesimpulan .= 'Risiko level Tinggi perlu menjadi prioritas tindak lanjut dan pemantauan pada triwulan berikutnya.';
        } elseif ($total > 0) {
            $kesimpulan .= 'Tidak ada risiko residual pada level Tinggi; pemantauan tetap dilakukan agar level risiko tidak meningkat.';
        } else {
            $kesimpulan .= 'Belum ada risiko yang dapat disimpulkan pada periode ini.';
        }
        $auto['kesimpulan'] = $kesimpulan;

        // 10) Rencana tindak lanjut — perlakuan yang masih terbuka
        $baris = [];
        $n = 0;
        foreach ($perlakuan as $p) {
            if ($p->status_laksana === 'SELESAI') {
                continue;
            }
            $n++;
            $baris[] = sprintf(
                '%d. %s — %s (PIC: %s; jadwal: %s; status: %s)',
                $n,
                $p->kode_risiko,
                $this->ringkasTeks($p->uraian_tindakan ?: '-', 120),
                $p->nama_pic ?: '-',
                $p->jadwal_selesai ?: '-',
                $this->labelStatus($p->status_laksana)
            );
        }
        if ($tanpaPerlakuan) {
            $baris[] = 'Risiko tanpa rencana perlakuan:';
            foreach (array_slice($tanpaPerlakuan, 0, 10) as $r) {
                $baris[] = sprintf(
                    '- %s — %s (keputusan: %s)',
                    $r->kode_risiko,
                    $this->ringkasTeks($r->risiko, 90),
                    $r->keputusan ?: '-'
                );
            }
        }
        $auto['rencana_tindak_lanjut'] = $baris
            ? implode("\n", $baris)
            : 'Seluruh rencana perlakuan sudah selesai atau belum ada rencana perlakuan yang terbuka.';

        // 11) Sumber catatan
        $auto['sumber_catatan'] = sprintf(
            'Sumber data: Risk Register, Analisis Risiko, Evaluasi Risiko, Perlakuan Risiko, dan Monitoring. '
            . 'Disusun otomatis dari Sistem Manajemen Risiko Perumdam Ijen Tirta pada %s. '
            . 'Laporan ini merupakan draf yang perlu ditinjau sebelum disahkan.',
            date('d-m-Y H:i')
        );

        return $auto;
    }

    /** Lingkup aktif untuk satu unit (atau konsolidasi). */
    private function lingkupUntuk($tahun, $idUpk)
    {
        if ($idUpk) {
            $rows = $this->Model_mri_lingkup->getLingkup($tahun, $idUpk);
            return $rows ? $rows[0] : null;
        }
        return $this->Model_mri_lingkup->getLingkupAktif($tahun);
    }

    /** Nama cakupan laporan. */
    private function namaCakupanById($idUpk)
    {
        if (!$idUpk) {
            return 'seluruh organisasi';
        }
        $row = $this->db->select('nama_bagian')->get_where('bagian_upk', ['id_bagian' => $idUpk])->row();
        return $row ? $row->nama_bagian : 'unit #' . (int) $idUpk;
    }

    /** Sebaran level menjadi teks baris. */
    private function barisSebaran($label, $sebaran, $total)
    {
        $baris = [];
        foreach ($sebaran as $nama => $n) {
            $baris[] = '  - ' . $nama . ': ' . (int) $n;
        }
        return $label . ' (' . (int) $total . " risiko):\n" . implode("\n", $baris);
    }

    /** Angka satu band level dari sebaran. */
    private function angkaLevel($sebaran, $nama)
    {
        return isset($sebaran[$nama]) ? (int) $sebaran[$nama] : 0;
    }

    /** Ringkas teks panjang menjadi satu paragraf pendek. */
    private function ringkasTeks($teks, $maks = 500)
    {
        $teks = trim(preg_replace('/\s+/', ' ', (string) $teks));
        return mb_strlen($teks) > $maks ? mb_substr($teks, 0, $maks) . '…' : $teks;
    }

    /** Label status perlakuan. */
    private function labelStatus($status)
    {
        $label = [
            'BELUM'        => 'belum dimulai',
            'DALAM_PROSES' => 'dalam proses',
            'SELESAI'      => 'selesai',
        ];
        return isset($label[$status]) ? $label[$status] : (string) $status;
    }
}
