<?php

/**
 * Petunjuk penggunaan modul manrisk.
 *
 * Sumber isi: D:\MR 2026\Ringkasan_MR_Perumdam_Ijen_Tirta.md
 *   §5.1 alur 8 tahap, §5.2 skala & matriks, §5.3 jenis perlakuan,
 *   §7.2 tanggung jawab risk owner, §8.1 kategori, §9.1 risk register,
 *   §10.1/10.2/10.3 laporan-KRI-monitoring, §11.1 objektif RKAP.
 *
 * Segmen dinamis (alur, matriks, skala level, kategori) dibangun dari
 * tabel mri_* sehingga mengikuti Pengaturan tanpa menyunting berkas ini.
 */

$band = $tingkat;
$skalaTeks = implode(', ', array_map(function ($t) {
    return $t->nama_tingkat . ' ' . $t->skor_min . '-' . $t->skor_max;
}, $band));

/** Skala 1-5 sesuai materi §5.2. */
$likelihood = [
    1 => ['Sangat Jarang', 'Hampir tidak pernah terjadi'],
    2 => ['Jarang', 'Dapat terjadi, tetapi tidak sering'],
    3 => ['Mungkin', 'Dapat terjadi sesekali'],
    4 => ['Sering', 'Cukup sering terjadi'],
    5 => ['Hampir Pasti', 'Sangat mungkin / berulang terjadi'],
];
$consequence = [
    1 => ['Tidak Signifikan', 'Dampak sangat kecil; target tetap tercapai'],
    2 => ['Minor', 'Dampak kecil; gangguan dapat segera dipulihkan'],
    3 => ['Moderat', 'Dampak sedang; perlu tindakan korektif'],
    4 => ['Signifikan', 'Dampak besar; target/kinerja terganggu'],
    5 => ['Sangat Signifikan', 'Dampak sangat besar; target utama dapat gagal'],
];

/** Jenis perlakuan sesuai materi §5.3. */
$perlakuan = [
    [
        'AVOID', 'Menghindari', 'Menghentikan aktivitas yang menghasilkan risiko.',
        'Menunda pekerjaan jaringan bila kondisi keselamatan belum memadai'
    ],
    [
        'REDUCE', 'Mengurangi', 'Menurunkan kemungkinan dan/atau dampak.',
        'Preventive maintenance pompa, standby pump, monitoring tekanan'
    ],
    [
        'SHARE', 'Berbagi', 'Mengalihkan melalui kontrak / asuransi.',
        'Klausul kontrak dengan pemasok bahan kimia, SLA vendor'
    ],
    [
        'ACCEPT', 'Menerima', 'Mempertahankan risiko dalam tolerance.',
        'Risiko residual dalam batas yang dapat diterima'
    ],
];

/** Tanggung jawab risk owner sesuai materi §7.2. */
$tanggungJawab = [
    'Memastikan risiko diidentifikasi',
    'Menilai &amp; menetapkan prioritas risiko',
    'Memastikan kontrol / mitigasi berjalan efektif',
    'Menyediakan / mengusulkan sumber daya',
    'Memantau &amp; melaporkan perkembangan risiko',
];

/** Jenis laporan sesuai materi §10.1. */
$jenisLaporan = [
    ['Laporan Operasional', 'Mingguan / Bulanan', 'Update risiko harian / unit'],
    ['Laporan Manajemen', 'Bulanan / Triwulan', 'Input keputusan Direksi'],
    ['Laporan Dewan Pengawas', 'Triwulanan', 'Oversight dan challenge'],
    ['Laporan Tahunan', 'Tahunan', 'Evaluasi kinerja Manajemen Risiko'],
];

/** Daftar isi. */
$daftarIsi = [
    'tentang'    => 'Tentang Panduan Ini',
    'alur'       => '1 &bull; Alur Proses 8 Tahap',
    'likelihood' => '2 &bull; Skala Likelihood',
    'consequence' => '3 &bull; Skala Consequence',
    'matriks'    => '4 &bull; Matriks Risiko 5&times;5',
    'kriteria'   => '5 &bull; Kriteria Evaluasi',
    'perlakuan'  => '6 &bull; Jenis Perlakuan Risiko',
    'owner'      => '7 &bull; Risk Owner',
    'kategori'   => '8 &bull; Kategori Risiko',
    'register'   => '9 &bull; Risk Register',
    'laporan'    => '10 &bull; Laporan, Monitoring &amp; KRI',
    'rkap'       => '11 &bull; Integrasi RKAP 2027',
    'faq'        => '12 &bull; Pertanyaan Umum',
];
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <div class="row align-items-center mb-3">
            <div class="col-md-8">
                <h4 class="mb-0"><?= strtoupper($title) ?></h4>
                <small class="text-muted">
                    SNI ISO 31000:2018 &amp; RKAP <?= (int) $tahun ?>
                    &middot; sumber: Ringkasan Manajemen Risiko Perumdam Ijen Tirta
                </small>
            </div>
            <div class="col-md-4 text-md-right">
                <a href="<?= base_url('manrisk/dashboard') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="<?= base_url('manrisk/register') ?>" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-list"></i> Risk Register
                </a>
            </div>
        </div>

        <div class="row">
            <!-- ================= DAFTAR ISI ================= -->
            <div class="col-lg-3">
                <div class="card card-outline card-info">
                    <div class="card-header"><strong>Daftar Isi</strong></div>
                    <div class="card-body py-2">
                        <ul class="nav nav-pills nav-stacked" style="list-style:none;padding-left:0;margin-bottom:0">
                            <?php foreach ($daftarIsi as $anchor => $judul) : ?>
                                <li class="nav-item">
                                    <a class="nav-link py-1 px-2" href="#<?= $anchor ?>"><?= $judul ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- ================= ISI ================= -->
            <div class="col-lg-9">

                <!-- ============ TENTANG ============ -->
                <div class="card card-outline card-primary" id="tentang">
                    <div class="card-header"><strong>Tentang Panduan Ini</strong></div>
                    <div class="card-body small">
                        <p class="mb-2">
                            Modul <strong>Man. Risiko 2027</strong> menerapkan SNI ISO 31000:2018 khusus untuk
                            tahun berjalan ke atas. Data risiko <strong>2025&ndash;2026</strong> tetap berada pada
                            modul legacy dan tidak pernah disalin atau dicampur ke sini.
                        </p>
                        <ul class="mb-2">
                            <li>Segmen bernomor 1&ndash;11 mengikuti isi Ringkasan Manajemen Risiko &mdash;
                                tidak ada yang dikarang sendiri.</li>
                            <li>Bagian yang terikat data (alur, matriks, skala level, kategori) diambil langsung
                                dari basis data sehingga ikut berubah saat <em>Pengaturan</em> disunting.</li>
                            <li>Setiap bagian dapat dibuka langsung lewat daftar isi di samping kiri.</li>
                        </ul>
                        <!-- <p class="mb-0 text-muted">
                            Penulisan ulang dokumen panduan ke format MD/DOCX tersedia pada folder
                            <code>D:\MR 2026\</code>.
                        </p> -->
                    </div>
                </div>

                <!-- ============ 1. ALUR ============ -->
                <div class="card card-outline card-primary" id="alur">
                    <div class="card-header"><strong>1 &bull; Alur Proses Manajemen Risiko (8 Tahap)</strong></div>
                    <div class="card-body">
                        <p class="small text-muted mb-3">
                            Urutan dan judul tahap persis seperti materi &sect;5.1. Kolom terakhir menunjukkan
                            berapa catatan tahun <strong><?= (int) $tahun ?></strong> yang sudah ada, sehingga
                            langsung terlihat tahap mana yang belum dikerjakan.
                        </p>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:44px" class="text-center">No</th>
                                        <th>Tahap</th>
                                        <th>Halaman di Aplikasi</th>
                                        <th class="text-center" style="width:150px">Sudah Tercatat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tahapan as $t) : ?>
                                        <tr>
                                            <td class="text-center"><strong><?= (int) $t['no'] ?></strong></td>
                                            <td>
                                                <?= html_escape($t['judul']) ?>
                                                <?php if ($t['no'] <= 2) : ?>
                                                    <br><small class="text-muted"><?= $t['no'] === 1
                                                                                        ? 'Berjalan menyilang di seluruh tahap.'
                                                                                        : 'Menyusun konteks, kriteria &amp; batas evaluasi.' ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url($t['rute']) ?>">
                                                    <i class="fas fa-arrow-circle-right"></i>
                                                    <?= html_escape($t['halaman']) ?>
                                                </a>
                                                <br><small class="text-muted"><?= html_escape($t['catatan']) ?></small>
                                            </td>
                                            <td class="text-center">
                                                <strong><?= (int) $t['jumlah'] ?></strong>
                                                <small class="d-block"><?= html_escape($t['satuan']) ?></small>
                                                <span class="badge <?= $t['terisi'] ? 'badge-success' : 'badge-secondary' ?>">
                                                    <?= $t['terisi'] ? 'sudah' : 'kosong' ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <p class="small text-muted mt-3 mb-0">
                            <strong>Catatan:</strong> materi memisahkan <em>Komunikasi &amp; Konsultasi</em> (tahap 1)
                            dan <em>Lingkup, Konteks &amp; Kriteria</em> (tahap 2), tetapi keduanya dicatat pada halaman
                            <a href="<?= base_url('manrisk/lingkup') ?>">Lingkup</a> karena isinya satu dokumen.
                            <strong>KPI / KRI</strong> bukan tahap tersendiri &mdash; ia milik tahap 7
                            (<a href="<?= base_url('manrisk/kri') ?>">Pemantauan KRI</a>).
                        </p>
                    </div>
                </div>

                <!-- ============ 2. LIKELIHOOD ============ -->
                <div class="card card-outline card-primary" id="likelihood">
                    <div class="card-header"><strong>2 &bull; Skala Likelihood (Kemungkinan)</strong></div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width:70px" class="text-center">Skor</th>
                                    <th style="width:180px">Level</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($likelihood as $skor => [$level, $deskripsi]) : ?>
                                    <tr>
                                        <td class="text-center"><strong><?= $skor ?></strong></td>
                                        <td><span class="badge badge-dark border"><?= html_escape($level) ?></span></td>
                                        <td><small><?= html_escape($deskripsi) ?></small></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ============ 3. CONSEQUENCE ============ -->
                <div class="card card-outline card-primary" id="consequence">
                    <div class="card-header"><strong>3 &bull; Skala Consequence (Dampak)</strong></div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width:70px" class="text-center">Skor</th>
                                    <th style="width:180px">Level</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($consequence as $skor => [$level, $deskripsi]) : ?>
                                    <tr>
                                        <td class="text-center"><strong><?= $skor ?></strong></td>
                                        <td><span class="badge badge-dark border"><?= html_escape($level) ?></span></td>
                                        <td><small><?= html_escape($deskripsi) ?></small></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <p class="small text-muted mt-2 mb-0">
                            <strong>Nilai = Likelihood &times; Consequence</strong>, rentang 1&ndash;25.
                            Di aplikasi kolom ini disebut <em>inherent</em> (sebelum kontrol) dan
                            <em>residual</em> (sesudah kontrol).
                        </p>
                    </div>
                </div>

                <!-- ============ 4. MATRIKS ============ -->
                <div class="card card-outline card-primary" id="matriks">
                    <div class="card-header"><strong>4 &bull; Matriks Risiko (Likelihood &times; Consequence)</strong></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center mb-0" style="max-width:560px">
                                <thead class="thead-light">
                                    <tr>
                                        <th colspan="2"></th>
                                        <th colspan="5">LIKELIHOOD</th>
                                    </tr>
                                    <tr>
                                        <th colspan="2"></th>
                                        <?php for ($l = 1; $l <= 5; $l++) : ?>
                                            <th><?= $l ?></th>
                                        <?php endfor; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($c = 5; $c >= 1; $c--) : ?>
                                        <?php if ($c === 5) : ?>
                                            <tr>
                                                <th rowspan="6" style="writing-mode:vertical-rl;transform:rotate(180deg)">
                                                    CONSEQUENCE
                                                </th>
                                                <th class="align-middle"><?= $c ?></th>
                                                <?php for ($l = 1; $l <= 5; $l++) :
                                                    $t = $this->Model_mri_master->levelDariSkor($l * $c);
                                                    $w = $t ? $t->warna : '#9ca3af';
                                                ?>
                                                    <td style="background-color:<?= $w ?>;color:#fff">
                                                        <?= $l * $c ?>
                                                    </td>
                                                <?php endfor; ?>
                                            </tr>
                                        <?php else : ?>
                                            <tr>
                                                <th><?= $c ?></th>
                                                <?php for ($l = 1; $l <= 5; $l++) :
                                                    $t = $this->Model_mri_master->levelDariSkor($l * $c);
                                                    $w = $t ? $t->warna : '#9ca3af';
                                                ?>
                                                    <td style="background-color:<?= $w ?>;color:#fff">
                                                        <?= $l * $c ?>
                                                    </td>
                                                <?php endfor; ?>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                        <p class="small text-muted mt-2 mb-0">
                            Warna sel mengikuti <code>mri_tingkat_risiko</code>, jadi otomatis berubah bila
                            rentang level disunting pada menu <a href="<?= base_url('manrisk/pengaturan') ?>">Pengaturan</a>.
                        </p>
                    </div>
                </div>

                <!-- ============ 5. KRITERIA ============ -->
                <div class="card card-outline card-primary" id="kriteria">
                    <div class="card-header"><strong>5 &bull; Kriteria Evaluasi &mdash; <?= count($band) ?> Level Skala</strong></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:60px" class="text-center">No</th>
                                        <th style="width:140px">Level</th>
                                        <th style="width:130px" class="text-center">Rentang Skor</th>
                                        <th>Tindakan</th>
                                        <th style="width:110px" class="text-center">Aksi Bawaan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($band as $i => $t) : ?>
                                        <tr>
                                            <td class="text-center"><?= (int) $t->urut_tampil ?></td>
                                            <td>
                                                <span class="badge" style="background-color:<?= $t->warna ?>;color:#fff">
                                                    <?= html_escape($t->nama_tingkat) ?>
                                                </span>
                                            </td>
                                            <td class="text-center"><?= (int) $t->skor_min ?>&ndash;<?= (int) $t->skor_max ?></td>
                                            <td><small><?= html_escape(
                                                            preg_replace('/^\d+-[^:]*:\s*/', '', (string) $t->deskripsi)
                                                        ) ?: '-' ?></small></td>
                                            <td class="text-center"><small><code><?= html_escape($t->aksi) ?></code></small></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <p class="small text-muted mt-2 mb-0">
                            <strong><?= html_escape($skalaTeks) ?></strong> &mdash; rentang ini dapat disesuaikan pada
                            <a href="<?= base_url('manrisk/pengaturan') ?>">Pengaturan</a>; seluruh badge, warna, dan
                            peringkat otomatis mengikuti. <code>Escalate</code> tidak lagi menjadi nama level, tetapi
                            tetap tersedia sebagai <em>keputusan manual</em> pada form Evaluasi.
                        </p>
                    </div>
                </div>

                <!-- ============ 6. PERLAKUAN ============ -->
                <div class="card card-outline card-primary" id="perlakuan">
                    <div class="card-header"><strong>6 &bull; Jenis Perlakuan Risiko (Risk Treatment)</strong></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:100px">Kode</th>
                                        <th style="width:140px">Treatment</th>
                                        <th>Deskripsi</th>
                                        <th>Contoh di Perumdam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($perlakuan as $p) : ?>
                                        <tr>
                                            <td><code><strong><?= html_escape($p[0]) ?></strong></code></td>
                                            <td><?= html_escape($p[1]) ?></td>
                                            <td><small><?= html_escape($p[2]) ?></small></td>
                                            <td><small class="text-muted"><?= html_escape($p[3]) ?></small></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <p class="small text-muted mt-2 mb-0">
                            Keputusan dicatat pada <a href="<?= base_url('manrisk/evaluasi') ?>">Evaluasi</a>,
                            rencana pelaksanaannya pada <a href="<?= base_url('manrisk/perlakuan') ?>">Perlakuan</a>.
                        </p>
                    </div>
                </div>

                <!-- ============ 7. RISK OWNER ============ -->
                <div class="card card-outline card-primary" id="owner">
                    <div class="card-header"><strong>7 &bull; Risk Owner &amp; Tanggung Jawabnya</strong></div>
                    <div class="card-body small">
                        <p class="mb-2">
                            <strong>Risk Owner</strong> adalah pihak yang memiliki akuntabilitas mengelola risiko
                            &mdash; bukan sekadar nama yang diisi pada kolom PIC. Daftar petugas tiap unit dikelola
                            lewat menu <a href="<?= base_url('manrisk/register') ?>">Risk Register</a>.
                        </p>
                        <ol class="mb-2">
                            <?php foreach ($tanggungJawab as $tj) : ?>
                                <li><?= $tj ?></li>
                            <?php endforeach; ?>
                        </ol>
                        <p class="mb-0 text-muted">
                            Daftar area risiko beserta risk owner masing-masing unit tercantum pada materi &sect;7.1.
                        </p>
                    </div>
                </div>

                <!-- ============ 8. KATEGORI ============ -->
                <div class="card card-outline card-primary" id="kategori">
                    <div class="card-header"><strong>8 &bull; Kategori Risiko</strong></div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap">
                            <?php foreach ($kategori as $k) : ?>
                                <span class="badge badge-secondary border mr-1 mb-1" style="font-size:.82rem;padding:.45em .7em">
                                    <?= html_escape($k->kode_kat) ?> &middot; <?= html_escape($k->nama_kat) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                        <p class="small text-muted mt-2 mb-0">
                            Modul aplikasi memakai
                            <strong><?= count($kategori) ?> kategori</strong> yang dapat disunting pada
                            <a href="<?= base_url('manrisk/pengaturan') ?>">Pengaturan</a>.
                            Kategori dipakai untuk mengelompokkan risiko pada filter dashboard dan laporan.
                        </p>
                    </div>
                </div>

                <!-- ============ 9. RISK REGISTER ============ -->
                <div class="card card-outline card-primary" id="register">
                    <div class="card-header"><strong>9 &bull; Risk Register &mdash; Kolom &amp; Cara Mengisi</strong></div>
                    <div class="card-body small">
                        <p class="mb-2">Urutan kerja pada modul ini:</p>
                        <ol class="mb-3">
                            <li>Daftarkan risiko di <a href="<?= base_url('manrisk/register/form') ?>">Risk Register &rarr; Tambah</a>
                                &mdash; peristiwa, penyebab, akibat, sasaran terpengaruh, serta tipe
                                <em>Ancaman</em> / <em>Peluang</em>.
                            </li>
                            <li>Beri skor di <a href="<?= base_url('manrisk/analisa') ?>">Analisis</a>:
                                Likelihood &amp; Consequence masing-masing untuk inherent dan residual.
                                Nilai &amp; level terisi otomatis.</li>
                            <li>Simpan keputusan di <a href="<?= base_url('manrisk/evaluasi') ?>">Evaluasi</a>:
                                <code>Accept</code>, <code>Monitor</code>, <code>Treat</code>, atau <code>Escalate</code>.
                            </li>
                            <li>Susun rencananya di <a href="<?= base_url('manrisk/perlakuan') ?>">Perlakuan</a>:
                                treatment, PIC, jadwal, anggaran, dan status progres.</li>
                            <li>Catat kemajuannya tiap triwulan di <a href="<?= base_url('manrisk/monitoring') ?>">Monitoring</a>.</li>
                            <li>Rangkum menjadi <a href="<?= base_url('manrisk/laporan') ?>">Laporan</a>.</li>
                        </ol>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-2">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:50px" class="text-center">No</th>
                                        <th>Kolom Template</th>
                                        <th>Isi di Modul</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td><strong>ID RISIKO</strong></td>
                                        <td><code>kode_risiko</code> (mis. IJT-OPR-01)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">2</td>
                                        <td><strong>SASARAN / KPI</strong></td>
                                        <td>sasaran terpengaruh &amp; KPI pada Risk Register</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">3</td>
                                        <td><strong>RISIKO</strong></td>
                                        <td>uraian peristiwa + penyebab + akibat</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">4</td>
                                        <td><strong>K, D, NILAI</strong></td>
                                        <td>kolom L &amp; C pada Analisis (nilai terhitung otomatis)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">5</td>
                                        <td><strong>RENCANA PENGENDALIAN</strong></td>
                                        <td>kolom tindakan pada Perlakuan</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">6</td>
                                        <td><strong>PIC</strong></td>
                                        <td>Risk Owner pada Risk Register</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <p class="mb-0 text-muted">
                            Data lama dapat dimasukkan lewat <a href="<?= base_url('manrisk/import') ?>">Impor CSV</a>.
                            Baris footer pada template (termasuk legenda level lama) otomatis dilewati &mdash;
                            level dihitung ulang dengan skala aplikasi.
                        </p>
                    </div>
                </div>

                <!-- ============ 10. LAPORAN ============ -->
                <div class="card card-outline card-primary" id="laporan">
                    <div class="card-header"><strong>10 &bull; Laporan, Monitoring &amp; KRI</strong></div>
                    <div class="card-body small">
                        <p class="mb-2"><strong>Jenis laporan</strong> </p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-3">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Jenis Laporan</th>
                                        <th style="width:170px">Frekuensi</th>
                                        <th>Tujuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($jenisLaporan as $jl) : ?>
                                        <tr>
                                            <td><strong><?= html_escape($jl[0]) ?></strong></td>
                                            <td><?= html_escape($jl[1]) ?></td>
                                            <td><?= html_escape($jl[2]) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <p class="mb-1"><strong>KPI / KRI</strong> dicatat per triwulan pada
                            <a href="<?= base_url('manrisk/kri') ?>">menu KRI</a>: nama indikator, target, frekuensi,
                            nilai aktual, lalu status <em>Tercapai</em> / <em>Perlu Perhatian</em> /
                            <em>Tidak Tercapai</em> / <em>Belum Diukur</em>.
                        </p>

                        <p class="mb-1"><strong>Alur monitoring</strong></p>
                        <div class="d-flex flex-wrap align-items-center small">
                            <?php $alur = [
                                'Monitoring', 'Update Risk Register', 'Laporan Risiko', 'Keputusan / Tindak Lanjut',
                                'Implementasi Treatment', 'Evaluasi Efektivitas', 'Perbaikan Berkelanjutan'
                            ]; ?>
                            <?php foreach ($alur as $i => $a) : ?>
                                <span class="badge badge-dark border mr-1 mb-1"><?= html_escape($a) ?></span>
                                <?php if ($i < count($alur) - 1) : ?><i class="fas fa-arrow-right text-muted mr-1 mb-1"></i><?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- ============ 11. RKAP ============ -->
                <div class="card card-outline card-primary" id="rkap">
                    <div class="card-header"><strong>11 &bull; Integrasi dengan RKAP</strong></div>
                    <div class="card-body small">
                        <p class="mb-2">
                            Risiko tidak berdiri sendiri: tiap risiko dihubungkan ke sasaran organisasi lewat menu
                            <a href="<?= base_url('manrisk/objektif') ?>">Objektif &amp; RKAP</a>, sehingga
                            jawaban atas pertanyaan <em>"kenapa risiko ini penting"</em> selalu dapat ditunjukkan.
                        </p>
                        <ul class="mb-2">
                            <li>Objektif menyimpan sasaran RKAP 2027 beserta pemiliknya, lalu direkonsiliasi
                                statusnya (<em>SELESAI</em> / <em>BELUM</em>).</li>
                            <li>Relasi risiko &rarr; objektif tercatat pada penyuntingan Risk Register.</li>
                            <li>Program kerja yang sudah terhubung ke risiko dapat dipantau pada laporan triwulan.</li>
                        </ul>
                        <!-- <p class="mb-0 text-muted">
                            Rincian matriks program kerja &times; risiko &times; mitigasi terdapat pada materi &sect;11.1,
                            dan action plan implementasi pada materi &sect;11.2.
                        </p> -->
                    </div>
                </div>

                <!-- ============ 12. FAQ ============ -->
                <div class="card card-outline card-warning" id="faq">
                    <div class="card-header"><strong>12 &bull; Pertanyaan Umum</strong></div>
                    <div class="card-body small">
                        <dl class="mb-0">
                            <!-- <dt>Mengapa menu Risiko 2025&ndash;2026 berbeda?</dt>
                            <dd class="mb-3">Itu modul legacy. Data &amp; skala lamanya sengaja tidak disentuh agar
                                arsip sebelumnya utuh. Keduanya tidak pernah mencampur label level maupun angkanya.</dd> -->

                            <dt>Kenapa tingkat risiko saya berubah setelah disimpan?</dt>
                            <dd class="mb-3">Level selalu dihitung ulang dari skor lewat
                                <code>mri_tingkat_risiko</code>. Ubah rentangnya di
                                <a href="<?= base_url('manrisk/pengaturan') ?>">Pengaturan</a> dan seluruh halaman ikut menyesuaikan.
                            </dd>

                            <dt>Di mana skor inherent dan residual ditentukan?</dt>
                            <dd class="mb-3">Pada <a href="<?= base_url('manrisk/analisa') ?>">Analisis</a>. Bukan di
                                form Risk Register &mdash; isilah L &amp; C di sana, bukan angkanya di register.</dd>

                            <dt>Kenapa impor CSV menolak file saya?</dt>
                            <dd class="mb-3">Harus 18 kolom, pemisah titik-koma <code>;</code>, encoding UTF-8, dan
                                ID risiko diawali <code>IJT-</code>. Baris tanpa ID dianggap footer dan dilewati.
                                Lihat <a href="<?= base_url('manrisk/import') ?>">panduan format</a> pada halaman impor.</dd>

                            <dt>Bolehkah risiko saya tidak dianalisis?</dt>
                            <dd class="mb-0">Boleh pada tahap identifikasi, tetapi dashboard menandainya sebagai
                                <em>tanpa analisa</em> dan skornya kosong. Analisis wajib sebelum evaluasi dan perlakuan.
                            </dd>
                        </dl>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>
</div>