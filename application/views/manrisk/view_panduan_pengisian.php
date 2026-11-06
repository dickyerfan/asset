<?php

/**
 * Panduan pengisian langkah demi langkah (Tahap 0 s/d 8).
 *
 * Sumber konten: PANDUAN_PENGISIAN_MANAJEMEN_RISIKO.md
 * Progres hidup: $alur  = Model_mri_dashboard::ringkasAlur()  (8 tahap ISO)
 *                $rekap = hitungan ringkas dari Panduan::pengisian()
 * Skala level   : $tingkat (mri_tingkat_risiko)
 *
 * Halaman bacaan-saja — tidak ada method tulis pada controller-nya.
 */

$band = $tingkat;
$skalaTeks = implode(', ', array_map(function ($t) {
    return $t->nama_tingkat . ' ' . $t->skor_min . '-' . $t->skor_max;
}, $band));

/** Progres per tahap ISO (index 0 = tahap 1) untuk strip ringkas. */
$alurNo = [];
foreach ($alur as $t) {
    $alurNo[(int) $t['no']] = $t;
}

/**
 * Badge status live untuk sebuah tahap panduan.
 *
 * @param string $status 'ok' | 'partial' | 'todo'
 * @return string HTML badge
 */
$badge = function ($status) {
    $map = [
        'ok'      => ['badge-success', 'Selesai'],
        'partial' => ['badge-warning', 'Sebagian'],
        'todo'    => ['badge-secondary', 'Belum'],
    ];
    list($cls, $label) = $map[$status];
    return '<span class="badge ' . $cls . '">' . $label . '</span>';
};

/** Status live tahap 1 — objektif (bukan bagian 8 tahap ISO; dari $rekap). */
$stObjektif = ($rekap['objektif_total'] > 0 && $rekap['objektif_belum'] === 0) ? 'ok'
    : ($rekap['objektif_total'] > 0 ? 'partial' : 'todo');

/** Status live tahap 2 — lingkup FINAL (komunikasi dihitung terpisah di tahap 1B). */
$stLingkup = ($rekap['lingkup_total'] > 0 && $rekap['lingkup_final'] === $rekap['lingkup_total']) ? 'ok'
    : ($rekap['lingkup_total'] > 0 ? 'partial' : 'todo');

/** Status live tahap 3–8 dari alur ISO. */
$stRegister  = isset($alurNo[3]) ? $alurNo[3]['status'] : 'Belum';
$stAnalisa   = isset($alurNo[4]) ? $alurNo[4]['status'] : 'Belum';
$stEvaluasi  = isset($alurNo[5]) ? $alurNo[5]['status'] : 'Belum';
$stPerlakuan = isset($alurNo[6]) ? $alurNo[6]['status'] : 'Belum';
$stMonitor   = isset($alurNo[7]) ? $alurNo[7]['status'] : 'Belum';
$stLaporan   = isset($alurNo[8]) ? $alurNo[8]['status'] : 'Belum';

/** Mapping status 'Selesai'/'Sebagian'/'Belum' → kunci badge. */
$norm = function ($s) {
    if ($s === 'Selesai') {
        return 'ok';
    }
    return $s === 'Sebagian' ? 'partial' : 'todo';
};
$stRegister  = $norm($stRegister);
$stAnalisa   = $norm($stAnalisa);
$stEvaluasi  = $norm($stEvaluasi);
$stPerlakuan = $norm($stPerlakuan);
$stMonitor   = $norm($stMonitor);
$stLaporan   = $norm($stLaporan);
$stKomunikasi = isset($alurNo[1]) ? $norm($alurNo[1]['status']) : 'todo';

/** Daftar isi. */
$daftarIsi = [
    'ringkas'   => 'Ringkasan Progres',
    'tahap0'    => '0 &bull; Pengaturan',
    'tahap1'    => '1 &bull; Sasaran / Objektif',
    'tahap1b'   => '1B &bull; Komunikasi &amp; Konsultasi',
    'tahap2'    => '2 &bull; Lingkup, Konteks &amp; Kriteria',
    'tahap3'    => '3 &bull; Identifikasi Risiko',
    'tahap4'    => '4 &bull; Analisis Risiko',
    'tahap5'    => '5 &bull; Evaluasi Risiko',
    'tahap6'    => '6 &bull; Perlakuan Risiko',
    'contoh37'  => 'Contoh &bull; Risk Assessment (hal. 37)',
    'tahap7'    => '7 &bull; Monitoring',
    'tahap8'    => '8 &bull; Pencatatan &amp; Pelaporan',
    'checklist' => 'Checklist Akhir',
    'masalah'   => 'Masalah Umum',
];

/** Strip tahap ISO untuk ringkasan atas. */
$strip = $alur;
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <div class="row align-items-center mb-3">
            <div class="col-md-7">
                <h4 class="mb-0"><?= strtoupper($title) ?></h4>
                <small class="text-muted">
                    Dari Sasaran hingga Laporan
                    &middot; skala aktif: <?= html_escape($skalaTeks) ?>
                </small>
            </div>
            <div class="col-md-5 text-md-right">
                <a href="<?= base_url('manrisk/dashboard') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="<?= base_url('manrisk/panduan') ?>" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-book"></i> Petunjuk
                </a>
                <a href="<?= base_url('manrisk/objektif') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-play"></i> Mulai dari Sasaran
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

                <!-- ============ RINGKASAN ============ -->
                <div class="card card-outline card-primary" id="ringkas">
                    <div class="card-header"><strong>Ringkasan Progres (live dari data)</strong></div>
                    <div class="card-body small">
                        <p class="text-muted mb-2">
                            Prinsip Utama: <em>&ldquo;Kuncinya: selalu mulai dari SASARAN.&rdquo;</em>
                            Risiko = efek ketidakpastian terhadap sasaran, jadi sasaran wajib ditetapkan
                            dulu sebelum risiko diidentifikasi.
                        </p>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:44px" class="text-center">No</th>
                                        <th>Tahap</th>
                                        <th>Menu</th>
                                        <th class="text-center" style="width:100px">Progres</th>
                                        <th class="text-center" style="width:110px">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Tahap 0 — Pengaturan (manual, di luar alur ISO) -->
                                    <tr>
                                        <td class="text-center"><strong>0</strong></td>
                                        <td>Pengaturan</td>
                                        <td><a href="<?= base_url('manrisk/pengaturan') ?>"><i class="fas fa-arrow-circle-right"></i> Pengaturan</a></td>
                                        <td class="text-center text-muted">&mdash;</td>
                                        <td class="text-center"><span class="badge badge-info">Cek awal</span></td>
                                    </tr>

                                    <!-- Tahap 1 — Objektif (dihitung dari $rekap) -->
                                    <tr>
                                        <td class="text-center"><strong>1</strong></td>
                                        <td>Sasaran / Objektif</td>
                                        <td><a href="<?= base_url('manrisk/objektif') ?>"><i class="fas fa-arrow-circle-right"></i> Objektif &amp; RKAP</a></td>
                                        <td class="text-center">
                                            <strong><?= (int) ($rekap['objektif_total'] - $rekap['objektif_belum']) ?>/<?= (int) $rekap['objektif_total'] ?></strong>
                                            <small class="d-block text-muted">sasaran rekonsiliasi</small>
                                        </td>
                                        <td class="text-center"><?= $badge($stObjektif) ?></td>
                                    </tr>

                                    <!-- Tahap 1B — Komunikasi & Konsultasi (tahap 1 ISO) -->
                                    <?php if (isset($alurNo[1])) :
                                        $tKom = $alurNo[1];
                                        $badgeKom = $tKom['status'] === 'Selesai' ? 'badge-success'
                                            : ($tKom['status'] === 'Sebagian' ? 'badge-warning' : 'badge-secondary');
                                    ?>
                                        <tr>
                                            <td class="text-center"><strong>1B</strong></td>
                                            <td>Komunikasi &amp; Konsultasi</td>
                                            <td>
                                                <a href="<?= base_url('manrisk/komunikasi') ?>">
                                                    <i class="fas fa-arrow-circle-right"></i> Komunikasi
                                                </a>
                                                <br><small class="text-muted">sesi berstatus SELESAI</small>
                                            </td>
                                            <td class="text-center">
                                                <strong><?= (int) $tKom['x'] ?>/<?= (int) $tKom['y'] ?></strong>
                                                <small class="d-block"><?= (int) $tKom['persen'] ?>%</small>
                                                <div class="progress progress-xs mt-1" style="height:5px">
                                                    <div class="progress-bar <?= $badgeKom ?>" style="width:<?= (int) $tKom['persen'] ?>%"></div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge <?= $badgeKom ?>"><?= html_escape($tKom['status']) ?></span>
                                            </td>
                                        </tr>
                                    <?php endif; ?>

                                    <!-- Tahap 2–8 — dari alur ISO -->
                                    <?php
                                    /** Menu panduan per nomor tahap ISO (2..8). */
                                    $menuPanduan = [
                                        2 => ['manrisk/lingkup', 'Lingkup', 'dok. lingkup FINAL'],
                                        3 => ['manrisk/register', 'Risk Register', 'risiko terdaftar'],
                                        4 => ['manrisk/analisa', 'Analisis', 'risiko dianalisa'],
                                        5 => ['manrisk/evaluasi', 'Evaluasi', 'risiko dievaluasi'],
                                        6 => ['manrisk/perlakuan', 'Perlakuan', 'rencana perlakuan'],
                                        7 => ['manrisk/monitoring', 'Monitoring', 'risiko dimonitor'],
                                        8 => ['manrisk/laporan', 'Laporan', 'laporan'],
                                    ];
                                    $noPanduan = [2, 3, 4, 5, 6, 7, 8];
                                    foreach ($noPanduan as $n) :
                                        if (!isset($alurNo[$n])) {
                                            continue;
                                        }
                                        $t = $alurNo[$n];
                                        $m = $menuPanduan[$n];
                                        $badgeStatus = $t['status'] === 'Selesai' ? 'badge-success'
                                            : ($t['status'] === 'Sebagian' ? 'badge-warning' : 'badge-secondary');
                                    ?>
                                        <tr>
                                            <td class="text-center"><strong><?= (int) $n ?></strong></td>
                                            <td>
                                                <?= html_escape($t['judul']) ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url($m[0]) ?>">
                                                    <i class="fas fa-arrow-circle-right"></i> <?= html_escape($m[1]) ?>
                                                </a>
                                                <br><small class="text-muted"><?= html_escape($m[2]) ?></small>
                                            </td>
                                            <td class="text-center">
                                                <strong><?= (int) $t['x'] ?>/<?= (int) $t['y'] ?></strong>
                                                <small class="d-block"><?= (int) $t['persen'] ?>%</small>
                                                <div class="progress progress-xs mt-1" style="height:5px">
                                                    <div class="progress-bar <?= $badgeStatus ?>" style="width:<?= (int) $t['persen'] ?>%"></div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge <?= $badgeStatus ?>"><?= html_escape($t['status']) ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3 col-6 text-center">
                                <h4 class="mb-0"><?= (int) $rekap['risiko'] ?></h4>
                                <small class="text-muted">Risiko terdaftar</small>
                            </div>
                            <div class="col-md-3 col-6 text-center">
                                <h4 class="mb-0 <?= $rekap['perlu_review'] > 0 ? 'text-warning' : '' ?>">
                                    <?= (int) $rekap['perlu_review'] ?>
                                </h4>
                                <small class="text-muted">Perlu review</small>
                            </div>
                            <div class="col-md-3 col-6 text-center">
                                <h4 class="mb-0 <?= $rekap['perlakuan_belum'] > 0 ? 'text-warning' : '' ?>">
                                    <?= (int) $rekap['perlakuan_belum'] ?>
                                </h4>
                                <small class="text-muted">Perlakuan masih BELUM</small>
                            </div>
                            <div class="col-md-3 col-6 text-center">
                                <h4 class="mb-0"><?= (int) $rekap['laporan'] ?></h4>
                                <small class="text-muted">Laporan tersedia</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============ TAHAP 0 ============ -->
                <div class="card card-outline card-secondary" id="tahap0">
                    <div class="card-header">
                        <strong>Tahap 0 &mdash; Pengaturan (cek awal)</strong>
                        <span class="float-right"><?= $badge('ok') ?></span>
                    </div>
                    <div class="card-body small">
                        <p class="mb-2">Sebelum mengisi data, pastikan:</p>
                        <ul class="mb-2">
                            <li>Skala level = <strong>3 level</strong> (Rendah/Sedang/Tinggi) dengan rentang sesuai kebijakan
                                &mdash; saat ini: <em><?= html_escape($skalaTeks) ?></em></li>
                            <li>Risk appetite/tolerance per kategori sudah diisi (bila dipakai)</li>
                            <li>Kunci periode <strong>terbuka</strong> selama pengisian (kunci lagi setelah final)</li>
                        </ul>
                        <a href="<?= base_url('manrisk/pengaturan') ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-cog"></i> Buka Pengaturan
                        </a>
                    </div>
                </div>

                <!-- ============ TAHAP 1 ============ -->
                <div class="card card-outline card-primary" id="tahap1">
                    <div class="card-header">
                        <strong>Tahap 1 &mdash; Sasaran / Objektif (MULAI DARI SINI)</strong>
                        <span class="float-right"><?= $badge($stObjektif) ?></span>
                    </div>
                    <div class="card-body small">
                        <p class="mb-2">
                            Sasaran harus <strong>SMART</strong>: Specific, Measurable, Attainable, Relevant, Time-bound.
                            Klik <strong>Tambah Objektif</strong> di menu
                            <a href="<?= base_url('manrisk/objektif') ?>">Objektif &amp; RKAP</a>.
                        </p>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-2">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:160px">Field</th>
                                        <th>Contoh</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Indikator</td>
                                        <td>Penurunan NRW per zona sumur bor</td>
                                        <td>Nama sasaran (wajib)</td>
                                    </tr>
                                    <tr>
                                        <td>Baseline 2026</td>
                                        <td>18,5</td>
                                        <td>Angka awal/realisasi tahun lalu</td>
                                    </tr>
                                    <tr>
                                        <td>Nilai RKAP</td>
                                        <td>15</td>
                                        <td><strong>Target 2027</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Satuan</td>
                                        <td>persen</td>
                                        <td>Rp, %, m3, jam, dll</td>
                                    </tr>
                                    <tr>
                                        <td>Sumber Nilai</td>
                                        <td>Usulan User / RKAP 2027</td>
                                        <td>Asal angka</td>
                                    </tr>
                                    <tr>
                                        <td>Unit / Bagian</td>
                                        <td>UPK Bondowoso</td>
                                        <td>Pemilik sasaran</td>
                                    </tr>
                                    <tr>
                                        <td>Proses / Lingkup</td>
                                        <td>Distribusi</td>
                                        <td>Hubungkan ke konteks</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <p class="mb-1"><strong>Rekonsiliasi</strong> = konfirmasi keterkaitan sasaran &harr; risiko sudah benar.
                            Isi <strong>Nilai Realisasi</strong> &rarr; status <strong>BELUM &rarr; SELESAI</strong>.</p>
                        <ul class="mb-2">
                            <li>Seluruh sasaran penting sudah terdaftar</li>
                            <li>Status rekonsiliasi semua sasaran = <strong>SELESAI</strong>
                                (saat ini: <?= (int) ($rekap['objektif_total'] - $rekap['objektif_belum']) ?>/<?= (int) $rekap['objektif_total'] ?> selesai,
                                <?= (int) $rekap['objektif_belum'] ?> masih BELUM)</li>
                        </ul>
                        <a href="<?= base_url('manrisk/objektif') ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-bullseye"></i> Buka Objektif &amp; RKAP
                        </a>
                    </div>
                </div>

                <!-- ============ TAHAP 1B ============ -->
                <div class="card card-outline card-primary" id="tahap1b">
                    <div class="card-header">
                        <strong>Tahap 1B &mdash; Komunikasi &amp; Konsultasi</strong>
                        <span class="float-right"><?= $badge($stKomunikasi) ?></span>
                    </div>
                    <div class="card-body small">
                        <p class="mb-2">
                            Klausa 6.2 ISO 31000. Catat sesi dengan pihak relevan di menu
                            <a href="<?= base_url('manrisk/komunikasi') ?>">Komunikasi &amp; Konsultasi</a>.
                            Satu dokumen lingkup boleh memiliki beberapa sesi (rapat, FGD, wawancara,
                            sosialisasi). Tujuannya menyamakan pemahaman sasaran, risiko, dan kontrol
                            &mdash; bukan sekadar notulen rapat.
                        </p>

                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <strong>Tiga langkah</strong>
                                <ol class="pl-3 mb-0">
                                    <li>Dengarkan &mdash; kumpulkan informasi peserta.</li>
                                    <li>Selaraskan &mdash; samakan pemahaman sasaran, risiko, kontrol.</li>
                                    <li>Uji &amp; konfirmasi &mdash; klarifikasi asumsi dan bukti.</li>
                                </ol>
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>Empat pertanyaan kunci</strong>
                                <ol class="pl-3 mb-0">
                                    <li>Apa sasarannya?</li>
                                    <li>Apa risikonya?</li>
                                    <li>Kontrol apa yang sudah ada?</li>
                                    <li>Apa tindakan selanjutnya?</li>
                                </ol>
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>Lima hasil yang diharapkan</strong>
                                <ol class="pl-3 mb-0">
                                    <li>Pemahaman sasaran yang sama</li>
                                    <li>Risiko &amp; prioritas teridentifikasi</li>
                                    <li>Penyebab &amp; dampak tervalidasi</li>
                                    <li>Mitigasi &amp; kontrol disepakati</li>
                                    <li>PIC &amp; tindak lanjut jelas</li>
                                </ol>
                            </div>
                        </div>

                        <p class="mb-2">
                            Tutup dengan ringkasan hasil, tindak lanjut, PIC, dan target selesai.
                            Ubah <strong>Status Sesi</strong> menjadi <strong>SELESAI</strong> agar
                            terhitung pada dashboard
                            (saat ini: <?= isset($alurNo[1]) ? (int) $alurNo[1]['x'] : 0 ?>/<?= isset($alurNo[1]) ? (int) $alurNo[1]['y'] : 0 ?> sesi selesai).
                        </p>
                        <a href="<?= base_url('manrisk/komunikasi') ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-comments"></i> Buka Komunikasi &amp; Konsultasi
                        </a>
                    </div>
                </div>

                <!-- ============ TAHAP 2 ============ -->
                <div class="card card-outline card-primary" id="tahap2">
                    <div class="card-header">
                        <strong>Tahap 2 &mdash; Lingkup, Konteks &amp; Kriteria</strong>
                        <span class="float-right"><?= $badge($stLingkup) ?></span>
                    </div>
                    <div class="card-body small">
                        <p class="mb-2">
                            Dokumen ini merangkum <strong>Klausa 6.3</strong> ISO 31000
                            (apa yang dinilai, kondisinya, ukurannya) di menu
                            <a href="<?= base_url('manrisk/lingkup') ?>">Lingkup</a>.
                        </p>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-2">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:200px">Field</th>
                                        <th>Isi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Nama Lingkup</td>
                                        <td>Evaluasi Manajemen Risiko 2027</td>
                                    </tr>
                                    <tr>
                                        <td>Objek Evaluasi</td>
                                        <td>proses/bisnis yang dinilai, mis. pengadaan &rarr; kontrak &rarr; distribusi</td>
                                    </tr>
                                    <tr>
                                        <td>Batas Evaluasi</td>
                                        <td>wilayah, unit, periode</td>
                                    </tr>
                                    <tr>
                                        <td>Konteks Internal</td>
                                        <td>SOP, SDM, gudang, anggaran, sistem</td>
                                    </tr>
                                    <tr>
                                        <td>Konteks Eksternal</td>
                                        <td>harga pasar, pemasok, regulasi, logistik</td>
                                    </tr>
                                    <tr>
                                        <td>Kriteria Acuan</td>
                                        <td>skala likelihood 1&ndash;5, consequence 1&ndash;5, L&times;C, appetite organisasi</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Konsultasi</td>
                                        <td>tanggal rapat awal</td>
                                    </tr>
                                    <tr>
                                        <td>Peserta</td>
                                        <td>nama &amp; jabatan yang terlibat</td>
                                    </tr>
                                    <tr>
                                        <td>4 Pertanyaan Kunci</td>
                                        <td>
                                            <strong>q_sasaran</strong> (apa sasarannya?) &middot;
                                            <strong>q_risiko</strong> (apa risikonya?) &middot;
                                            <strong>q_kontrol</strong> (kontrol apa yang sudah ada?) &middot;
                                            <strong>q_tindakan</strong> (aksi selanjutnya?)
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Hasil Konsultasi</td>
                                        <td>kesimpulan bersama setelah 4 pertanyaan terjawab</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <p class="text-muted mb-2">
                            <i class="fas fa-info-circle"></i>
                            Kolom konsultasi di form ini adalah <strong>ringkasan dokumen</strong>.
                            Detail tiap sesi (tiga langkah, empat pertanyaan, lima hasil, tindak lanjut)
                            dicatat di menu
                            <a href="<?= base_url('manrisk/komunikasi') ?>">Komunikasi &amp; Konsultasi</a>.
                        </p>

                        <ul class="mb-2">
                            <li>Semua field terisi, hasil konsultasi ada</li>
                            <li>Status = <strong>FINAL</strong>
                                (saat ini: <?= (int) $rekap['lingkup_final'] ?>/<?= (int) $rekap['lingkup_total'] ?> dokumen FINAL)</li>
                        </ul>
                        <a href="<?= base_url('manrisk/lingkup') ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-expand-arrows-alt"></i> Buka Lingkup
                        </a>
                    </div>
                </div>

                <!-- ============ TAHAP 3 ============ -->
                <div class="card card-outline card-primary" id="tahap3">
                    <div class="card-header">
                        <strong>Tahap 3 &mdash; Identifikasi Risiko (Risk Register)</strong>
                        <span class="float-right"><?= $badge($stRegister) ?></span>
                    </div>
                    <div class="card-body small">
                        <p class="mb-2">
                            Rumusan risiko harus menghubungkan:
                            <strong>SASARAN &rarr; PENYEBAB &rarr; PERISTIWA &rarr; AKIBAT</strong>.
                            Menu: <a href="<?= base_url('manrisk/register') ?>">Risk Register &rarr; Tambah</a>.
                        </p>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-2">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:160px">Field</th>
                                        <th>Contoh (UPK Bondowoso)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Kode Risiko</td>
                                        <td><code>IJT-UPB-01</code> (otomatis dari segmen + urut)</td>
                                    </tr>
                                    <tr>
                                        <td>Tahun / TW Awal</td>
                                        <td>2027 / TW1</td>
                                    </tr>
                                    <tr>
                                        <td>Unit (UPK)</td>
                                        <td>Bondowoso</td>
                                    </tr>
                                    <tr>
                                        <td>Kategori</td>
                                        <td>
                                            Pilih kode kategori, mis. <code>OPE — Operational</code>,
                                            <code>FIN — Finance</code>, <code>STR — Strategic</code>.
                                            Penjelasan singkat + contohnya muncul otomatis di bawah dropdown.
                                            <em>Beda dengan segmen kode.</em>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tipe Risiko</td>
                                        <td>Ancaman / Peluang</td>
                                    </tr>
                                    <tr>
                                        <td>Status Kendali</td>
                                        <td>C (Controlled) / UC (Uncontrolled)</td>
                                    </tr>
                                    <tr>
                                        <td>Risiko (wajib)</td>
                                        <td>&ldquo;Gangguan perpipaan distribusi menyebabkan tekanan air turun sehingga kontinuitas layanan terganggu&rdquo;</td>
                                    </tr>
                                    <tr>
                                        <td>Peristiwa (wajib)</td>
                                        <td>&ldquo;Pipa bocor/pecah di jalur distribusi utama&rdquo;</td>
                                    </tr>
                                    <tr>
                                        <td>Penyebab (wajib)</td>
                                        <td>&ldquo;Pipa tua, tekanan tidak terkendali, inspeksi belum rutin&rdquo;</td>
                                    </tr>
                                    <tr>
                                        <td>Sasaran/KPI (wajib)</td>
                                        <td>
                                            Pilih dari <strong>dropdown Objektif RKAP</strong> (mis. <em>Volume Air</em>)
                                            &mdash; teksnya terisi otomatis dan boleh disunting. Bila KPI belum ada
                                            di daftar, pilih <em>&ldquo;tulis sendiri&rdquo;</em>.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Sumber Risiko Tambahan</td>
                                        <td>Bukti/rujukan: &ldquo;Pemadaman listrik 3&times; sebulan terakhir&rdquo;</td>
                                    </tr>
                                    <tr>
                                        <td>Indikator Emerging</td>
                                        <td>
                                            Sinyal awal/peringatan dini yang <em>belum</em> jadi risiko, mis.
                                            &ldquo;Rencana perubahan tarif&rdquo;, &ldquo;Kemarau diprediksi lebih panjang&rdquo;.
                                            Kosongkan bila belum ada.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Asumsi &amp; Bias</td>
                                        <td>&ldquo;Genset diasumsikan selalu siap pakai&rdquo; (asumsi) &middot; &ldquo;proyeksi pendapatan terlalu optimistis&rdquo; (bias)</td>
                                    </tr>
                                    <tr>
                                        <td>Kegiatan / Proses</td>
                                        <td>&ldquo;Produksi &amp; distribusi air dari 25 sumur bor&rdquo; (kosong = diisi otomatis dari Sasaran/KPI)</td>
                                    </tr>
                                    <tr>
                                        <td>Daftar Akibat</td>
                                        <td><code>Finansial|Kerugian sampai Rp 500 juta</code> (satu akibat per baris)</td>
                                    </tr>
                                    <tr>
                                        <td>Proses</td>
                                        <td>&#9745; proses terkait</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Objektif</strong></td>
                                        <td>&#9745; <strong>sasaran yang terpengaruh</strong> (WAJIB dicentang &mdash; inilah kaitan risiko &harr; sasaran)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h6 class="text-primary border-bottom pb-1 mb-2">
                            <i class="fas fa-barcode"></i> Arti kode risiko: <code>IJT-SEG-NN</code>
                        </h6>
                        <p class="mb-2">
                            <code>IJT</code> = <strong>Ijen Tirta</strong> (penanda organisasi, selalu tetap) &middot;
                            <code>SEG</code> = segmen/tema risiko &middot;
                            <code>NN</code> = nomor urut 2 digit per segmen.
                            <strong>Segmen bukan turunan Kategori</strong> &mdash; pilih sesuai tema isi risiko,
                            bukan berdasarkan dropdown Kategori.
                        </p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-2">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:90px">Segmen</th>
                                        <th style="width:200px">Arti</th>
                                        <th>Contoh isi risiko</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>OPR</code></td>
                                        <td>Operasional</td>
                                        <td>gangguan produksi/distribusi, NRW, kontinuitas layanan</td>
                                    </tr>
                                    <tr>
                                        <td><code>KEU</code></td>
                                        <td>Keuangan</td>
                                        <td>piutang tak tertagih, likuiditas, penyalahgunaan anggaran</td>
                                    </tr>
                                    <tr>
                                        <td><code>GOV</code></td>
                                        <td>Governance / tata kelola</td>
                                        <td>konflik kepentingan, fraud, kelemahan pengawasan</td>
                                    </tr>
                                    <tr>
                                        <td><code>HR</code></td>
                                        <td>SDM / Human Resources</td>
                                        <td>kekurangan tenaga ahli, turnover, kompetensi</td>
                                    </tr>
                                    <tr>
                                        <td><code>IT</code></td>
                                        <td>Teknologi Informasi</td>
                                        <td>gangguan sistem billing, keamanan data, server down</td>
                                    </tr>
                                    <tr>
                                        <td><code>K3</code></td>
                                        <td>Keselamatan &amp; Kesehatan Kerja</td>
                                        <td>kecelakaan kerja, paparan bahan kimia, APD</td>
                                    </tr>
                                    <tr>
                                        <td><code>EXT</code></td>
                                        <td>Eksternal</td>
                                        <td>cuaca ekstrem, pemasok, kebijakan pemerintah</td>
                                    </tr>
                                    <tr>
                                        <td><code>PEL</code></td>
                                        <td>Pelanggan</td>
                                        <td>keluhan, pengaduan, kepuasan layanan</td>
                                    </tr>
                                    <tr>
                                        <td><code>REG</code></td>
                                        <td>Regulasi / kepatuhan</td>
                                        <td>perubahan aturan, sanksi, izin usaha</td>
                                    </tr>
                                    <tr>
                                        <td><code>AMDK</code></td>
                                        <td>Unit usaha AMDK</td>
                                        <td>risiko khusus lini Air Minum Dalam Kemasan</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-muted mb-2">
                            Kode dipakai sebagai ID risiko di analisis, evaluasi, perlakuan, monitoring,
                            dan laporan. Kode <strong>tidak</strong> memengaruhi perhitungan skor/level.
                            Nomor urut dihitung per segmen, jadi <code>IJT-OPR-02</code> dan
                            <code>IJT-KEU-01</code> bisa berdampingan.
                        </p>

                        <ul class="mb-2">
                            <li>Setiap risiko <strong>menyentuh minimal 1 objektif</strong> (centang di bagian Objektif)</li>
                            <li>Rumusan memuat event + dampak terhadap sasaran (bukan sekadar &ldquo;pompa rusak&rdquo;)</li>
                            <li>Saat ini: <strong><?= (int) $rekap['risiko'] ?></strong> risiko terdaftar</li>
                        </ul>
                        <a href="<?= base_url('manrisk/register') ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-list"></i> Buka Risk Register
                        </a>
                    </div>
                </div>

                <!-- ============ TAHAP 4 ============ -->
                <div class="card card-outline card-primary" id="tahap4">
                    <div class="card-header">
                        <strong>Tahap 4 &mdash; Analisis Risiko</strong>
                        <span class="float-right"><?= $badge($stAnalisa) ?></span>
                    </div>
                    <div class="card-body small">
                        <p class="mb-2">
                            Menu: <a href="<?= base_url('manrisk/analisa') ?>">Analisis</a> &rarr; buka risiko &rarr;
                            <strong>Isi Skor</strong>.
                        </p>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-2">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:220px">Bagian</th>
                                        <th>Isi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Inherent</strong> (sebelum kontrol)</td>
                                        <td>Likelihood 1&ndash;5 &times; Consequence 1&ndash;5 &rarr; nilai terisi otomatis</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Residual</strong> (sesudah kontrol)</td>
                                        <td>Likelihood &times; Consequence sesudah kontrol</td>
                                    </tr>
                                    <tr>
                                        <td>Kontrol uraian</td>
                                        <td>kontrol yang sudah berjalan</td>
                                    </tr>
                                    <tr>
                                        <td>Kontrol ada / memadai</td>
                                        <td>Ada&ndash;Tidak / Memadai&ndash;Belum</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Risk Owner</strong></td>
                                        <td>pihak yang <strong>berwenang &amp; akuntabel</strong> atas risiko
                                            (Kepala UPK/Pimpinan unit), bukan sekadar pelaksana</td>
                                    </tr>
                                    <tr>
                                        <td>Ringkasan</td>
                                        <td>simpulan analisis</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <ul class="mb-2">
                            <li>Skor <strong>residual &le; inherent</strong> (kontrol menurunkan risiko)</li>
                            <li>Risk Owner terisi (bukan kosong) &mdash; diisi di <strong>Analisis</strong>, ditampilkan di Risk Register</li>
                        </ul>
                        <a href="<?= base_url('manrisk/analisa') ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-calculator"></i> Buka Analisis
                        </a>
                    </div>
                </div>

                <!-- ============ TAHAP 5 ============ -->
                <div class="card card-outline card-primary" id="tahap5">
                    <div class="card-header">
                        <strong>Tahap 5 &mdash; Evaluasi Risiko</strong>
                        <span class="float-right"><?= $badge($stEvaluasi) ?></span>
                    </div>
                    <div class="card-body small">
                        <p class="mb-2">
                            Menu: <a href="<?= base_url('manrisk/evaluasi') ?>">Evaluasi</a> &rarr; klik
                            <strong>Evaluasi</strong> pada baris risiko. Pengisian otomatis/guardrail:
                        </p>
                        <ol class="mb-2">
                            <li><strong>Nilai Evaluasi = skor RESIDUAL</strong> (tidak bisa diisi bebas &mdash; sesuai QRMO slide 39)</li>
                            <li>
                                <strong>Level &amp; rekomendasi aksi (band)</strong> dihitung dari skala aktif:
                                <ul class="mb-1">
                                    <?php foreach ($band as $b) : ?>
                                        <li>
                                            <strong><?= html_escape($b->nama_tingkat) ?></strong>
                                            (<?= (int) $b->skor_min ?>&ndash;<?= (int) $b->skor_max ?>)
                                            &rarr; <code><?= html_escape($b->aksi) ?></code>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                (Rendah &rarr; Accept, Sedang &rarr; Monitor, Tinggi &rarr; Treat pada skala baku)
                            </li>
                            <li>
                                <strong>Keputusan</strong>: pilih sesuai rekomendasi, atau pilih lain &rarr;
                                <strong>wajib tulis Alasan</strong>.
                                Jika beda dari rekomendasi &rarr; otomatis ditandai <strong>PERLU_REVIEW</strong>
                                (lihat tabel arti keputusan di bawah).
                            </li>
                            <li>Eskalasi: centang bila perlu eskalasi ke pimpinan, pilih <code>eskalasi_ke</code></li>
                            <li>TW &amp; tanggal evaluasi &rarr; <strong>Simpan</strong></li>
                        </ol>

                        <p class="mb-1"><strong>Arti keputusan</strong> &mdash; pilih yang paling sesuai, jangan sekadar mengikuti band:</p>
                        <div class="table-responsive mb-2">
                            <table class="table table-sm table-bordered bg-white mb-1">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:90px">Keputusan</th>
                                        <th style="width:22%">Artinya</th>
                                        <th style="width:30%">Kapan dipilih</th>
                                        <th>Akibatnya</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="badge badge-secondary">Accept</span></td>
                                        <td>Risiko diterima apa adanya; sisa risiko masih dalam batas appetite.</td>
                                        <td>Skor residual &le; appetite kategori, dampak kecil, kontrol memadai.
                                            <em>Contoh: skor 6, appetite 10.</em>
                                        </td>
                                        <td>Tidak wajib rencana perlakuan; cukup pemantauan rutin.</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge badge-info">Monitor</span></td>
                                        <td>Belum perlu ditangani sekarang, tetapi harus diawasi.</td>
                                        <td>Masih dalam appetite namun mendekati batas, atau level menengah dengan kontrol berjalan.
                                            <em>Contoh: skor 12, appetite 15.</em>
                                        </td>
                                        <td>Dicatat di Monitoring tiap triwulan; bila tren naik, ubah ke <code>Treat</code>.</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge badge-warning">Treat</span></td>
                                        <td>Risiko wajib diturunkan dengan tindakan nyata (mitigasi).</td>
                                        <td>Skor melebihi appetite kategori atau level Tinggi dan berdampak pada sasaran/KPI.
                                            <em>Contoh: skor 18, appetite 15.</em>
                                        </td>
                                        <td>Wajib disusun di menu <strong>Perlakuan</strong> (strategi, PIC, jadwal, anggaran);
                                            terhitung &ldquo;perlu ditindaklanjuti&rdquo; di Laporan.</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge badge-danger">Escalate</span></td>
                                        <td>Di luar kewenangan unit &mdash; keputusan diserahkan ke atasan.</td>
                                        <td>Skor melewati batas eskalasi kategori, berdampak lintas unit/strategis,
                                            atau butuh keputusan Direksi/Komite. <em>Contoh: skor 22, batas 15.</em></td>
                                        <td>Centang <strong>Perlu Eskalasi</strong> + isi <strong>Eskalasi ke</strong> agar muncul di
                                            kartu &ldquo;Risiko Perlu Eskalasi&rdquo; dashboard; rencana perlakuan tetap disusun.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="mb-2">
                            <strong>Band hanya saran otomatis</strong> dari skor residual; keputusan boleh berbeda
                            asalkan alasannya jelas. <strong>Alasan wajib diisi</strong> &mdash; mis.
                            <em>&ldquo;Skor 18 melebihi appetite 15 dan menyangkut 25 sumur bor.&rdquo;</em>
                            <code>Escalate</code> tanpa mencentang <strong>Perlu Eskalasi</strong> tidak muncul di dashboard.
                        </p>

                        <p class="mb-1"><strong>Review override</strong> (khusus Administrator/Auditor):</p>
                        <p class="mb-2">
                            Daftar evaluasi &rarr; filter <strong>Status Review = Perlu Review</strong> &rarr;
                            klik <strong>Review</strong> &rarr; beri catatan &rarr; <strong>Tandai Ditinjau</strong>.
                            Saat ini <strong><?= (int) $rekap['perlu_review'] ?></strong> menunggu tinjauan
                            (dari <?= (int) $rekap['evaluasi'] ?> evaluasi).
                        </p>
                        <a href="<?= base_url('manrisk/evaluasi') ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-balance-scale"></i> Buka Evaluasi
                        </a>
                    </div>
                </div>

                <!-- ============ TAHAP 6 ============ -->
                <div class="card card-outline card-primary" id="tahap6">
                    <div class="card-header">
                        <strong>Tahap 6 &mdash; Perlakuan Risiko</strong>
                        <span class="float-right"><?= $badge($stPerlakuan) ?></span>
                    </div>
                    <div class="card-body small">
                        <p class="mb-2">
                            Menu: <a href="<?= base_url('manrisk/perlakuan') ?>">Perlakuan &rarr; Tambah</a>.
                            Wajib untuk risiko berkeputusan Treat/Escalate.
                        </p>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-2">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:180px">Field</th>
                                        <th>Isi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Strategi</td>
                                        <td><strong>Avoid / Reduce / Share / Accept</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Alasan Strategi</td>
                                        <td>kenapa strategi itu dipilih</td>
                                    </tr>
                                    <tr>
                                        <td>Uraian Tindakan</td>
                                        <td>aksi konkret, mis. &ldquo;preventive maintenance pipa + pemasangan pressure sensor&rdquo;</td>
                                    </tr>
                                    <tr>
                                        <td>Indikator Sukses</td>
                                        <td>ukuran keberhasilan</td>
                                    </tr>
                                    <tr>
                                        <td>Sumber Daya</td>
                                        <td>SDM, alat, anggaran</td>
                                    </tr>
                                    <tr>
                                        <td>Anggaran Biaya</td>
                                        <td>Rp</td>
                                    </tr>
                                    <tr>
                                        <td>Jadwal</td>
                                        <td>Mulai &ndash; Selesai</td>
                                    </tr>
                                    <tr>
                                        <td>PIC</td>
                                        <td>pelaksana</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status Laksana</strong></td>
                                        <td><strong>BELUM &rarr; DALAM_PROSES &rarr; SELESAI</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <ul class="mb-2">
                            <li>Setiap risiko berkeputusan Treat/Escalate punya rencana perlakuan</li>
                            <li>Status diperbarui berkala (bukan selamanya BELUM)</li>
                            <li>Saat ini: <?= (int) $rekap['perlakuan'] ?> perlakuan,
                                <strong><?= (int) $rekap['perlakuan_belum'] ?> masih status BELUM</strong>
                            </li>
                        </ul>
                        <a href="<?= base_url('manrisk/perlakuan') ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-tasks"></i> Buka Perlakuan
                        </a>
                    </div>
                </div>

                <!-- ============ CONTOH RISK ASSESSMENT (CRMO hal. 37) ============ -->
                <div class="card card-outline card-dark" id="contoh37">
                    <div class="card-header">
                        <strong>Contoh &mdash; Risk Assessment (Identifikasi, Analisis, dan Evaluasi Risiko)</strong>
                    </div>
                    <div class="card-body small">
                        <p class="mb-2">
                            Bentuk akhir yang diharapkan: satu tabel yang merangkum
                            <strong>Identifikasi &rarr; Analisis &rarr; Evaluasi &rarr; Perlakuan</strong>.
                            Contoh di bawah diambil dari materi <em>CRMO Perpamsi halaman 37</em>.
                            Data nyata dapat dilihat di menu
                            <a href="<?= base_url('manrisk/assessment') ?>">Risk Assessment</a>.
                        </p>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-2" style="font-size:11px">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>No. ID Risiko</th>
                                        <th>Sasaran / KPI</th>
                                        <th>Risiko</th>
                                        <th>Sumber</th>
                                        <th>Penyebab</th>
                                        <th>Dampak</th>
                                        <th>Rencana Pengendalian &amp; Penanganan</th>
                                        <th>Deskripsi Biaya Mitigasi</th>
                                        <th>PIC</th>
                                        <th class="text-center">K</th>
                                        <th class="text-center">D</th>
                                        <th class="text-center">Nilai</th>
                                        <th class="text-center">Tingkat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td class="text-nowrap"><strong>PDAM-OPR-01-2024</strong></td>
                                        <td>1. Menurunkan tingkat kehilangan air (NRW)<br>2. Menjaga kontinuitas distribusi air</td>
                                        <td>Kebocoran pipa distribusi menyebabkan kehilangan air tinggi</td>
                                        <td>Infrastruktur pipa tua dan tekanan distribusi tinggi</td>
                                        <td>Usia pipa melebihi umur ekonomis dan kurangnya monitoring jaringan</td>
                                        <td>1. Kehilangan pendapatan<br>2. Gangguan suplai air pelanggan<br>3. Peningkatan biaya operasional</td>
                                        <td>1. Penggantian pipa bertahap<br>2. Pemasangan sensor kebocoran<br>3. Monitoring tekanan jaringan secara berkala</td>
                                        <td>Biaya penggantian pipa distribusi dan pemasangan alat monitoring kebocoran</td>
                                        <td>Kepala Divisi Teknik</td>
                                        <td class="text-center">4</td>
                                        <td class="text-center">5</td>
                                        <td class="text-center"><strong>20</strong></td>
                                        <td class="text-center"><span class="badge" style="background:#dc3545;color:#fff">TINGGI</span></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td class="text-nowrap"><strong>PDAM-OPR-02-2024</strong></td>
                                        <td>1. Menjaga kualitas air sesuai standar<br>2. Menjamin kepuasan pelanggan</td>
                                        <td>Kualitas air tidak memenuhi standar kualitas air bersih</td>
                                        <td>Pencemaran sumber air baku dan gangguan proses treatment</td>
                                        <td>Curah hujan tinggi, sedimentasi, dan gangguan instalasi pengolahan</td>
                                        <td>1. Keluhan pelanggan<br>2. Risiko kesehatan masyarakat<br>3. Penurunan reputasi PDAM</td>
                                        <td>1. Pengawasan kualitas air berkala<br>2. Optimalisasi proses treatment<br>3. Penambahan buffer reservoir</td>
                                        <td>Biaya pengujian laboratorium dan peningkatan instalasi treatment</td>
                                        <td>Kepala Produksi &amp; Laboratorium</td>
                                        <td class="text-center">5</td>
                                        <td class="text-center">4</td>
                                        <td class="text-center"><strong>20</strong></td>
                                        <td class="text-center"><span class="badge" style="background:#dc3545;color:#fff">TINGGI</span></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td class="text-nowrap"><strong>PDAM-KEU-01-2024</strong></td>
                                        <td>1. Menjaga cash flow perusahaan<br>2. Meningkatkan pendapatan perusahaan</td>
                                        <td>Tingginya tunggakan pembayaran pelanggan</td>
                                        <td>Kondisi ekonomi pelanggan dan lemahnya penagihan</td>
                                        <td>Sistem penagihan belum optimal dan keterlambatan pembayaran pelanggan</td>
                                        <td>1. Penurunan pendapatan<br>2. Gangguan operasional<br>3. Cash flow terganggu</td>
                                        <td>1. Sistem reminder pembayaran digital<br>2. Program cicilan tunggakan<br>3. Penagihan aktif pelanggan</td>
                                        <td>Biaya implementasi sistem penagihan digital dan sosialisasi pelanggan</td>
                                        <td>Kepala Divisi Keuangan</td>
                                        <td class="text-center">5</td>
                                        <td class="text-center">4</td>
                                        <td class="text-center"><strong>20</strong></td>
                                        <td class="text-center"><span class="badge" style="background:#dc3545;color:#fff">TINGGI</span></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td class="text-nowrap"><strong>PDAM-IT-01-2024</strong></td>
                                        <td>1. Menjamin kelancaran layanan digital<br>2. Menjaga keamanan data pelanggan</td>
                                        <td>Gangguan sistem billing dan kehilangan data pelanggan</td>
                                        <td>Kegagalan server dan serangan siber</td>
                                        <td>Backup data tidak optimal dan lemahnya keamanan sistem</td>
                                        <td>1. Gangguan penagihan<br>2. Kehilangan data pelanggan<br>3. Gangguan pelayanan</td>
                                        <td>1. Backup server berkala<br>2. Firewall dan antivirus<br>3. Monitoring keamanan sistem</td>
                                        <td>Biaya upgrade server dan keamanan sistem IT</td>
                                        <td>Kepala Divisi IT</td>
                                        <td class="text-center">3</td>
                                        <td class="text-center">4</td>
                                        <td class="text-center"><strong>12</strong></td>
                                        <td class="text-center"><span class="badge" style="background:#ffc107;color:#212529">SEDANG</span></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td class="text-nowrap"><strong>PDAM-OPS-03-2024</strong></td>
                                        <td>1. Menjaga kontinuitas pelayanan air<br>2. Menekan downtime operasional</td>
                                        <td>Gangguan operasional pompa distribusi air</td>
                                        <td>Kerusakan mesin pompa dan gangguan listrik</td>
                                        <td>Kurangnya preventive maintenance dan usia alat</td>
                                        <td>1. Distribusi air terganggu<br>2. Keluhan pelanggan<br>3. Biaya perbaikan meningkat</td>
                                        <td>1. Preventive maintenance berkala<br>2. Penyediaan genset cadangan<br>3. Penggantian pompa bertahap</td>
                                        <td>Biaya maintenance pompa dan pengadaan genset</td>
                                        <td>Kepala Operasional</td>
                                        <td class="text-center">4</td>
                                        <td class="text-center">4</td>
                                        <td class="text-center"><strong>16</strong></td>
                                        <td class="text-center"><span class="badge" style="background:#dc3545;color:#fff">TINGGI</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <p class="mb-2">
                            Di aplikasi, kolom <strong>Sasaran/KPI</strong> diambil dari Register dan objektif yang
                            dicentang, <strong>Dampak</strong> dari daftar akibat, <strong>Rencana</strong> dari
                            kontrol + uraian tindakan, <strong>Biaya</strong> dari anggaran perlakuan, dan
                            <strong>PIC</strong> dari master PIC. Kolom <strong>Keputusan</strong> (Accept/Monitor/
                            Treat/Escalate) ditambahkan dari tahap Evaluasi.
                        </p>

                        <a href="<?= base_url('manrisk/assessment') ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-table"></i> Buka Risk Assessment
                        </a>
                    </div>
                </div>

                <!-- ============ TAHAP 7 ============ -->
                <div class="card card-outline card-primary" id="tahap7">
                    <div class="card-header">
                        <strong>Tahap 7 &mdash; Pemantauan &amp; Peninjauan</strong>
                        <span class="float-right"><?= $badge($stMonitor) ?></span>
                    </div>
                    <div class="card-body small">
                        <h6 class="mb-2">7a. Monitoring (tiap triwulan)</h6>
                        <p class="mb-2">Menu: <a href="<?= base_url('manrisk/monitoring') ?>">Monitoring &rarr; Tambah</a></p>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-2">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:200px">Field</th>
                                        <th>Isi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Tahun / TW</td>
                                        <td>2027 / TW1&ndash;TW4</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Review</td>
                                        <td>akhir triwulan</td>
                                    </tr>
                                    <tr>
                                        <td>Status Tindakan</td>
                                        <td>status pelaksanaan perlakuan</td>
                                    </tr>
                                    <tr>
                                        <td>Hasil Monitoring</td>
                                        <td>apa yang terjadi &amp; apakah kontrol berjalan</td>
                                    </tr>
                                    <tr>
                                        <td>Kontrol Masih Efektif</td>
                                        <td>YA / TIDAK / SEBAGIAN</td>
                                    </tr>
                                    <tr>
                                        <td>Skor Lama</td>
                                        <td><strong>otomatis &amp; terkunci</strong>: skor catatan monitoring terakhir (tidak bisa diubah)</td>
                                    </tr>
                                    <tr>
                                        <td>Skor Baru</td>
                                        <td>diisi <strong>L &times; D</strong> (1&ndash;5) di form Monitoring; hasilnya otomatis, mis. <code>2 &times; 3 = 6</code></td>
                                    </tr>
                                    <tr>
                                        <td>Arah Perubahan</td>
                                        <td><strong>otomatis</strong> dihitung: Naik / Turun / Tetap (dari selisih Skor Lama &rarr; Baru)</td>
                                    </tr>
                                    <tr>
                                        <td>Sebab Perubahan</td>
                                        <td>alasan skor berubah, mis. kontrol tambahan / regulasi baru</td>
                                    </tr>
                                    <tr>
                                        <td>&#9744; Perbarui Analisa juga</td>
                                        <td>centang agar residual di <strong>Analisa</strong> ikut menjadi Skor Baru (otomatis aktif bila skor berbeda)</td>
                                    </tr>
                                    <tr>
                                        <td>&#9744; Perlu Reassess</td>
                                        <td>centang bila risiko harus dinilai ulang (kontrol tidak efektif / skor naik / konteks berubah) &rarr; lanjut perbarui <strong>Analisis</strong> lalu <strong>Evaluasi</strong>. Nilainya manual, bukan hitungan otomatis.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="mb-2">
                            <strong>Skor:</strong> Skor Lama <em>terkunci</em> (snapshot catatan sebelumnya).
                            Skor Baru diisi <strong>L &times; D</strong> langsung di form Monitoring &mdash;
                            hasilnya dihitung otomatis dan Arah Perubahan mengikuti. Bila Skor Baru berbeda
                            dari residual <strong>Analisa</strong>, sebaiknya perbarui Analisa juga agar
                            keduanya konsisten.
                        </p>
                        <p class="mb-2">
                            <strong>Sinkronisasi:</strong> centang <strong>Perbarui Analisa juga</strong>
                            (otomatis aktif bila skor berbeda) agar residual L &times; D, nilai, dan level di
                            Analisa ikut diperbarui. Keputusan <strong>Evaluasi</strong> tidak diubah otomatis;
                            bila level berubah, tinjau Evaluasi.
                        </p>
                        <p class="mb-2">
                            <strong>Mengoreksi catatan:</strong> di daftar Monitoring, tombol
                            <strong>Ubah</strong> membuka catatan <em>terakhir</em>; untuk catatan lama,
                            klik angka pada kolom <strong>Jumlah Catatan</strong> &rarr; ikon pensil di
                            tabel <strong>Riwayat Catatan Monitoring</strong> (bisa juga dihapus di sana).
                        </p>

                        <ul class="mb-2">
                            <li>Monitoring TW berjalan untuk semua risiko AKTIF
                                (saat ini <?= (int) $rekap['monitoring'] ?> catatan monitoring)</li>
                            <li>Skor Baru dicatat lewat <strong>L &times; D</strong>; centang
                                <strong>Perbarui Analisa juga</strong> agar residual Analisa ikut diperbarui.
                            </li>
                        </ul>
                        <a href="<?= base_url('manrisk/monitoring') ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i> Monitoring
                        </a>
                    </div>
                </div>

                <!-- ============ TAHAP 8 ============ -->
                <div class="card card-outline card-primary" id="tahap8">
                    <div class="card-header">
                        <strong>Tahap 8 &mdash; Pencatatan &amp; Pelaporan</strong>
                        <span class="float-right"><?= $badge($stLaporan) ?></span>
                    </div>
                    <div class="card-body small">
                        <p class="mb-2">
                            Menu: <a href="<?= base_url('manrisk/laporan') ?>">Laporan</a>
                            &mdash; 4 jenis: Operasional, Manajemen, Dewan Pengawas, Tahunan.
                        </p>

                        <p class="mb-1"><strong>Langkah cetak laporan:</strong></p>
                        <ol class="mb-2">
                            <li>Di halaman <strong>Laporan</strong>, pilih Tahun, Triwulan, Jenis, dan Unit
                                (atau <em>seluruh organisasi</em>), lalu klik
                                <strong>Susun Laporan Otomatis</strong>
                            </li>
                            <li>Seluruh bagian terisi otomatis dari data Register sampai Monitoring:
                                ringkasan eksekutif, latar belakang, tujuan, ruang lingkup,
                                rekap inherent/residual, peta risiko, rekap peluang,
                                evaluasi &amp; tindak lanjut, kesimpulan, rencana tindak lanjut,
                                dan sumber catatan</li>
                            <li>Tinjau dan ubah bagian yang butuh penilaian manusia
                                (mis. <strong>Kesimpulan</strong> atau <strong>Rencana Tindak Lanjut</strong>)</li>
                            <li><strong>Simpan</strong></li>
                            <li><strong>Sahkan</strong> (status DRAFT &rarr; FINAL, tercatat siapa &amp; kapan)</li>
                            <li>Klik <strong>PDF</strong> untuk cetak/unduh</li>
                        </ol>

                        <p class="mb-2">
                            Laporan unit (pilih UPK) dan konsolidasi (seluruh organisasi) bisa berdampingan.
                            Saat ini: <strong><?= (int) $rekap['laporan'] ?></strong> laporan tercatat
                            (target 4 jenis).
                        </p>
                        <a href="<?= base_url('manrisk/laporan') ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-file-pdf"></i> Buka Laporan
                        </a>
                    </div>
                </div>

                <!-- ============ CHECKLIST AKHIR ============ -->
                <div class="card card-outline card-success" id="checklist">
                    <div class="card-header"><strong>Checklist Akhir (sebelum laporan dianggap sah)</strong></div>
                    <div class="card-body small">
                        <ul class="mb-2" style="list-style:none;padding-left:0">
                            <li>&#9744; <strong>Objektif</strong>: semua sasaran rekonsiliasi <strong>SELESAI</strong>
                                (<?= (int) ($rekap['objektif_total'] - $rekap['objektif_belum']) ?>/<?= (int) $rekap['objektif_total'] ?>)</li>
                            <li>&#9744; <strong>Komunikasi</strong>: minimal satu sesi <strong>SELESAI</strong>,
                                empat pertanyaan terjawab, tindak lanjut punya PIC
                                (<?= isset($alurNo[1]) ? (int) $alurNo[1]['x'] : 0 ?>/<?= isset($alurNo[1]) ? (int) $alurNo[1]['y'] : 0 ?> sesi selesai)</li>
                            <li>&#9744; <strong>Lingkup</strong>: status <strong>FINAL</strong>, hasil konsultasi terisi
                                (<?= (int) $rekap['lingkup_final'] ?>/<?= (int) $rekap['lingkup_total'] ?> FINAL)</li>
                            <li>&#9744; <strong>Register</strong>: setiap risiko terhubung ke minimal 1 objektif</li>
                            <li>&#9744; <strong>Analisis</strong>: skor inherent + residual terisi, Risk Owner ada</li>
                            <li>&#9744; <strong>Evaluasi</strong>: nilai = residual;
                                override sudah ditinjau Admin/Auditor
                                (<?= (int) $rekap['perlu_review'] ?> masih perlu review)</li>
                            <li>&#9744; <strong>Perlakuan</strong>: status tidak masih semua BELUM
                                (<?= (int) $rekap['perlakuan_belum'] ?>/<?= (int) $rekap['perlakuan'] ?> masih BELUM)</li>
                            <li>&#9744; <strong>Monitoring</strong>: catatan triwulan terisi
                                (<?= (int) $rekap['monitoring'] ?> catatan)</li>
                            <li>&#9744; <strong>Laporan</strong>: Susun Laporan Otomatis &rarr; tinjau &rarr; Sahkan &rarr; PDF</li>
                        </ul>
                        <p class="text-muted mb-0">
                            Progres 8 tahap dapat dipantau real-time di
                            <a href="<?= base_url('manrisk/dashboard') ?>">Dashboard</a>
                            (kartu <code>x/y</code> + persen per tahap).
                        </p>
                    </div>
                </div>

                <!-- ============ MASALAH UMUM ============ -->
                <div class="card card-outline card-warning" id="masalah">
                    <div class="card-header"><strong>Masalah Umum</strong></div>
                    <div class="card-body small">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:30%">Gejala</th>
                                        <th style="width:30%">Penyebab</th>
                                        <th>Solusi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Tahap 1 di dashboard &ldquo;Belum&rdquo;</td>
                                        <td>Belum ada sesi komunikasi berstatus SELESAI</td>
                                        <td>Catat sesi di <a href="<?= base_url('manrisk/komunikasi') ?>">Komunikasi &amp; Konsultasi</a></td>
                                    </tr>
                                    <tr>
                                        <td>Tahap 2 di dashboard &ldquo;Belum&rdquo;</td>
                                        <td>Lingkup masih DRAFT</td>
                                        <td>Lengkapi &amp; FINALkan <a href="<?= base_url('manrisk/lingkup') ?>">Lingkup</a></td>
                                    </tr>
                                    <tr>
                                        <td>Level risiko berubah sendiri</td>
                                        <td>Level dihitung ulang dari skala</td>
                                        <td>Cek skala di <a href="<?= base_url('manrisk/pengaturan') ?>">Pengaturan</a></td>
                                    </tr>
                                    <tr>
                                        <td>Evaluasi masuk &ldquo;Perlu Review&rdquo;</td>
                                        <td>Keputusan beda dari band residual</td>
                                        <td>Ditinjau Admin/Auditor lewat menu
                                            <a href="<?= base_url('manrisk/evaluasi') ?>">Evaluasi</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&ldquo;Isi dari Data&rdquo; tidak mengubah teks</td>
                                        <td>Field sudah terisi</td>
                                        <td>Hapus dulu teks lama, baru klik lagi</td>
                                    </tr>
                                    <tr>
                                        <td>Laporan unit bentrok</td>
                                        <td>unique key lama</td>
                                        <td>Sudah diperbaiki (<code>id_upk</code> ikut kunci)</td>
                                    </tr>
                                    <tr>
                                        <td>Data 2025&ndash;2026 tidak ada di sini</td>
                                        <td>Modul legacy terpisah</td>
                                        <td>Buka menu <strong>Risiko</strong> (legacy)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>
</div>