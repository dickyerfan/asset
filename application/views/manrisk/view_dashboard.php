<?php

/**
 * Dashboard modul manrisk (SNI ISO 31000:2018).
 *
 * Sumber data: $dashboard dari Model_mri_dashboard::rangkum(),
 * plus $matriks / $matriks_res (sel 5x5).
 *
 * Catatan skala: seluruh level, rentang skor, warna, dan aksi dibaca dari
 * mri_tingkat_risiko (lihat Model_mri_master::levelDariSkor()) sehingga
 * mengikuti tanpa perlu menyunting berkas ini.
 * Modul legacy memakai skala berbeda; keduanya tidak boleh dicampur.
 *
 * Tata letak: setiap baris grid selalu berjumlah 12 kolom dan kartu diberi
 * h-100 agar tidak menyisakan ruang kosong di sisi kanan maupun di dalam
 * kartu yang lebih pendek.
 */
$d = $dashboard;
$band = $d['tingkat'];

/** Teks legenda "Nama min-maks" untuk tiap band, urut seperti di tabel. */
$skalaTeks = implode(', ', array_map(function ($t) {
    return $t->nama_tingkat . ' ' . $t->skor_min . '-' . $t->skor_max;
}, $band));

/** Band paling parah (urut_tampil ASC) — dipakai kartu ringkasan. */
$bandPuncak = end($band) ?: null;
$skalaPuncak = $bandPuncak
    ? (int) (isset($d['level_inherent'][$bandPuncak->nama_tingkat])
        ? $d['level_inherent'][$bandPuncak->nama_tingkat] : 0)
    : 0;

$total = (int) $d['total_risiko'];
$persen = function ($nilai) use ($total) {
    return $total > 0 ? round(($nilai / $total) * 100, 1) : 0;
};

/** Warna bawaan level dari mri_tingkat_risiko. */
$warna = [];
foreach ($d['tingkat'] as $t) {
    $warna[$t->nama_tingkat] = $t->warna;
}
$fungsiWarna = function ($level) use ($warna) {
    return isset($warna[$level]) ? $warna[$level] : '#9ca3af';
};

/** Label aksi bawaan level. */
$aksi = [];
foreach ($d['tingkat'] as $t) {
    $aksi[$t->nama_tingkat] = $t->aksi;
}

/**
 * Sel matriks 5x5 -> warna level dari perkalian L x D.
 * levelDariSkor() mengembalikan objek baris (atau null), bukan nama level.
 */
$warnaSel = function ($l, $c) {
    $level = $this->Model_mri_master->levelDariSkor($l * $c);
    return $level ? $level->warna : '#9ca3af';
};

/* ---- Angka ringkas yang dipakai berulang di halaman ---- */
$tanpaAnalisa   = (int) $d['tanpa_analisa'];
$jumlahEskalasi = count($d['eskalasi']);
$perlakuanTotal = array_sum($d['progres']);
$perlakuanSelesai = isset($d['progres']['SELESAI']) ? (int) $d['progres']['SELESAI'] : 0;
$monitoringTercatat = isset($d['monitoring']) ? (int) $d['monitoring'] : 0;
$komunikasiTercatat = isset($komunikasi['total']) ? (int) $komunikasi['total'] : 0;
$komunikasiSelesai = isset($komunikasi['selesai']) ? (int) $komunikasi['selesai'] : 0;
$perluReview    = isset($rekap_review['PERLU_REVIEW']) ? (int) $rekap_review['PERLU_REVIEW'] : 0;

$rekSelesai = (int) $d['rekonsiliasi']['SELESAI'];
$rekBelum   = (int) $d['rekonsiliasi']['BELUM'];
$rekTotal   = $rekSelesai + $rekBelum;
$rekPersen  = $rekTotal > 0 ? round($rekSelesai / $rekTotal * 100, 1) : 0;

/** Tinggi chart per unit mengikuti jumlah unit agar label tidak berdesakan. */
$tinggiUnit = max(220, count($d['per_unit']) * 30);
?>
<style>
    /* Gaya lokal dashboard manrisk — tidak mengubah halaman lain. */
    .mri-dash .card-header {
        padding: .6rem 1rem;
    }

    .mri-dash .card-body {
        padding: 1rem;
    }

    .mri-dash .mri-table-sm>thead>tr>th,
    .mri-dash .mri-table-sm>tbody>tr>th,
    .mri-dash .mri-table-sm>tbody>tr>td {
        padding: .38rem .45rem;
        vertical-align: middle;
        font-size: .85rem;
    }

    .mri-dash .chart-box {
        position: relative;
        width: 100%;
    }

    .mri-dash .small-box .inner h3 {
        font-size: 1.65rem;
        margin-bottom: .1rem;
    }

    .mri-dash .small-box .inner p {
        font-size: .8rem;
        margin-bottom: 0;
    }

    .mri-dash .small-box-footer {
        font-size: .78rem;
        padding: .3rem 0;
    }

    .mri-dash .matrix-cell {
        font-weight: 700;
        line-height: 1.15;
    }

    .mri-dash .matrix-cell small {
        font-weight: 400;
        font-size: .68rem;
        opacity: .85;
    }

    .mri-dash .alur-item {
        flex: 1 1 0;
        min-width: 170px;
    }
</style>

<section class="content">
    <div class="container-fluid mri-dash">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <!-- ================= JUDUL + FILTER ================= -->
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div class="mb-2 mb-md-0">
                        <h5 class="mb-0 font-weight-bold">
                            <i class="fas fa-shield-alt text-primary mr-1"></i>
                            <?= strtoupper($title) ?> <?= $tahun ?>
                        </h5>
                        <?php if ($lingkup) : ?>
                            <small class="text-muted">
                                Lingkup: <strong><?= html_escape($lingkup->nama_lingkup) ?></strong>
                                &middot; status <strong><?= html_escape($lingkup->status) ?></strong>
                            </small>
                        <?php else : ?>
                            <small class="text-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Belum ada lingkup/konteks yang ditetapkan untuk tahun ini.
                            </small>
                        <?php endif; ?>
                    </div>
                    <div class="mb-2 mb-md-0">
                        <a href="<?= base_url('manrisk/panduan/pengisian') ?>" class="neumorphic-button">
                            <i class="fas fa-clipboard-list"></i> Panduan Pengisian
                        </a>
                        <a href="<?= base_url('manrisk/panduan') ?>" class="neumorphic-button ml-1">
                            <i class="fas fa-book"></i> Petunjuk Penggunaan
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body py-2">
                <form method="get" action="<?= base_url('manrisk/dashboard') ?>" id="form_tahun" class="form-inline">
                    <?php if ($akses_semua) : ?>
                        <div class="form-group mr-3 mb-2">
                            <label for="id_upk" class="mr-2 mb-0">Bagian / UPK</label>
                            <select name="id_upk" id="id_upk" class="form-control" onchange="document.getElementById('form_tahun').submit()">
                                <option value="">Semua Bagian / UPK</option>
                                <?php foreach ($unit_list as $unit) : ?>
                                    <option value="<?= $unit->id_bagian ?>" <?= isset($filter['id_upk']) && (string) $filter['id_upk'] === (string)$unit->id_bagian ? 'selected' : '' ?>>
                                        <?= html_escape($unit->nama_bagian) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="form-group mr-3 mb-2">
                        <label for="id_kategori" class="mr-2 mb-0">Kategori</label>
                        <select name="id_kategori" id="id_kategori" class="form-control" onchange="document.getElementById('form_tahun').submit()">
                            <option value="">Semua Kategori</option>
                            <?php foreach ($kategori_list as $k) : ?>
                                <option value="<?= $k->id_kategori ?>" <?= isset($filter['id_kategori']) && (string) $filter['id_kategori'] === (string)$k->id_kategori ? 'selected' : '' ?>>
                                    <?= html_escape($k->nama_kat) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mr-3 mb-2">
                        <label for="tipe_risiko" class="mr-2 mb-0">Tipe</label>
                        <select name="tipe_risiko" id="tipe_risiko" class="form-control" onchange="document.getElementById('form_tahun').submit()">
                            <option value="">Semua</option>
                            <option value="Ancaman" <?= isset($filter['tipe_risiko']) && $filter['tipe_risiko'] === 'Ancaman' ? 'selected' : '' ?>>Ancaman</option>
                            <option value="Peluang" <?= isset($filter['tipe_risiko']) && $filter['tipe_risiko'] === 'Peluang' ? 'selected' : '' ?>>Peluang</option>
                        </select>
                    </div>

                    <div class="form-group mr-3 mb-2">
                        <label for="tahun" class="mr-2 mb-0">Tahun</label>
                        <select name="tahun" id="tahun" class="form-control">
                            <?php foreach ($daftar_tahun as $t) : ?>
                                <option value="<?= $t ?>" <?= (int) $tahun === $t ? 'selected' : '' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mr-3 mb-2">
                        <label for="q" class="mr-2 mb-0">Cari</label>
                        <input type="text" name="q" id="q" class="form-control" placeholder="kode / risiko / KPI" value="<?= html_escape(isset($filter['q']) ? $filter['q'] : '') ?>">
                    </div>

                    <button type="submit" class="neumorphic-button mb-2"><i class="fas fa-filter"></i> Tampilkan</button>
                    <a href="<?= base_url('manrisk/dashboard') ?>" class="neumorphic-button mb-2 ml-2"><i class="fas fa-undo"></i> Reset</a>
                </form>
            </div>
        </div>

        <!-- ================= KPI (6 kartu = 12 kolom) ================= -->
        <div class="row">
            <div class="col-xl-2 col-md-4 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3><?= $total ?></h3>
                        <p>Total Risiko Tercatat</p>
                    </div>
                    <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                    <a href="<?= base_url('manrisk/register') ?>" class="small-box-footer">
                        Risk Register <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <a href="<?= base_url('manrisk/evaluasi?review=PERLU_REVIEW') ?>" class="d-block text-decoration-none">
                    <div class="small-box" style="background:#6f42c1;color:#fff;">
                        <div class="inner">
                            <h3><?= $perluReview ?></h3>
                            <p>Evaluasi Perlu Review</p>
                        </div>
                        <div class="icon"><i class="fas fa-flag"></i></div>
                        <div class="small-box-footer" style="background:rgba(0,0,0,.15);color:#fff;">
                            Antrean review <i class="fas fa-arrow-circle-right"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= $skalaPuncak ?></h3>
                        <p>Inherent <?= html_escape($bandPuncak ? $bandPuncak->nama_tingkat : '-') ?></p>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
                    <a href="<?= base_url('manrisk/matrik') ?>" class="small-box-footer">
                        Lihat Matriks <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= $rekSelesai ?>/<?= $rekTotal ?></h3>
                        <p>Objektif RKAP Rekonsiliasi</p>
                    </div>
                    <div class="icon"><i class="fas fa-bullseye"></i></div>
                    <a href="<?= base_url('manrisk/objektif') ?>" class="small-box-footer">
                        Kelola Objektif <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $perlakuanSelesai ?>/<?= $perlakuanTotal ?></h3>
                        <p>Perlakuan Selesai</p>
                    </div>
                    <div class="icon"><i class="fas fa-tasks"></i></div>
                    <a href="<?= base_url('manrisk/perlakuan') ?>" class="small-box-footer">
                        Kelola Perlakuan <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6">
                <div class="small-box <?= $tanpaAnalisa > 0 ? 'bg-danger' : 'bg-success' ?>">
                    <div class="inner">
                        <h3><?= $tanpaAnalisa ?></h3>
                        <p><?= $tanpaAnalisa > 0 ? 'Risiko Belum Dianalisis' : 'Semua Risiko Dianalisis' ?></p>
                    </div>
                    <div class="icon"><i class="fas fa-search"></i></div>
                    <a href="<?= base_url('manrisk/analisa') ?>" class="small-box-footer">
                        Lengkapi Analisa <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- ================= SEBARAN LEVEL (chart + tabel) ================= -->
        <div class="row">
            <div class="col-12 mb-3">
                <div class="card card-outline card-primary h-100 mb-0">
                    <div class="card-header">
                        <strong>Sebaran Level Risiko</strong>
                        <span class="float-right small text-muted">Inherent &rarr; Residual</span>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-lg-7 mb-3 mb-lg-0">
                                <div class="chart-box" style="height:250px">
                                    <canvas id="chartLevel"></canvas>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered mri-table-sm mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Level</th>
                                                <th class="text-center">Rentang</th>
                                                <th class="text-center">Inherent</th>
                                                <th class="text-center">Residual</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($d['tingkat'] as $t) :
                                                $ih = isset($d['level_inherent'][$t->nama_tingkat]) ? (int) $d['level_inherent'][$t->nama_tingkat] : 0;
                                                $rs = isset($d['level_residual'][$t->nama_tingkat]) ? (int) $d['level_residual'][$t->nama_tingkat] : 0;
                                            ?>
                                                <tr>
                                                    <td>
                                                        <span class="badge" style="background-color:<?= $t->warna ?>;color:#fff">
                                                            <?= html_escape($t->nama_tingkat) ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center"><?= (int) $t->skor_min ?>-<?= (int) $t->skor_max ?></td>
                                                    <td class="text-center"><strong><?= $ih ?></strong></td>
                                                    <td class="text-center"><?= $rs ?></td>
                                                    <td><small class="text-muted"><?= html_escape($t->aksi) ?></small></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    Skala: <?= html_escape($skalaTeks) ?>.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= MATRIKS 5x5 (inherent | residual) ================= -->
        <div class="row">
            <div class="col-12 mb-3">
                <div class="card card-outline card-primary h-100 mb-0">
                    <div class="card-header">
                        <strong>Matriks Risiko 5 &times; 5</strong>
                        <span class="float-right small text-muted">Likelihood &times; Dampak</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 mb-3 mb-lg-0">
                                <h6 class="text-center font-weight-bold">Inherent</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm text-center mri-table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" style="vertical-align:middle;background-color:#b6e8faff">LIKELIHOOD</th>
                                                <th rowspan="2" style="vertical-align:middle;background-color:#f6e79dff">L</th>
                                                <th colspan="5">DAMPAK</th>
                                            </tr>
                                            <tr style="background-color:#f6e79dff">
                                                <th>1</th>
                                                <th>2</th>
                                                <th>3</th>
                                                <th>4</th>
                                                <th>5</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for ($l = 5; $l >= 1; $l--) : ?>
                                                <tr>
                                                    <th style="background-color:#b6e8faff"><?= ['1' => 'Sangat jarang', '2' => 'Jarang', '3' => 'Kadang', '4' => 'Sering', '5' => 'Hampir pasti'][$l] ?></th>
                                                    <th style="background-color:#f6e79dff"><?= $l ?></th>
                                                    <?php for ($c = 1; $c <= 5; $c++) :
                                                        $isi = isset($matriks[$l][$c]) ? (int) $matriks[$l][$c] : 0;
                                                    ?>
                                                        <td class="matrix-cell" style="background-color:<?= $warnaSel($l, $c) ?>">
                                                            <?= $isi > 0 ? $isi : '&ndash;' ?>
                                                            <small class="d-block"><?= $l * $c ?></small>
                                                        </td>
                                                    <?php endfor; ?>
                                                </tr>
                                            <?php endfor; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <h6 class="text-center font-weight-bold">Residual</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm text-center mri-table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" style="vertical-align:middle;background-color:#b6e8faff">LIKELIHOOD</th>
                                                <th rowspan="2" style="vertical-align:middle;background-color:#f6e79dff">L</th>
                                                <th colspan="5">DAMPAK</th>
                                            </tr>
                                            <tr style="background-color:#f6e79dff">
                                                <th>1</th>
                                                <th>2</th>
                                                <th>3</th>
                                                <th>4</th>
                                                <th>5</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for ($l = 5; $l >= 1; $l--) : ?>
                                                <tr>
                                                    <th style="background-color:#b6e8faff"><?= ['1' => 'Sangat jarang', '2' => 'Jarang', '3' => 'Kadang', '4' => 'Sering', '5' => 'Hampir pasti'][$l] ?></th>
                                                    <th style="background-color:#f6e79dff"><?= $l ?></th>
                                                    <?php for ($c = 1; $c <= 5; $c++) :
                                                        $isi = isset($matriks_res[$l][$c]) ? (int) $matriks_res[$l][$c] : 0;
                                                    ?>
                                                        <td class="matrix-cell" style="background-color:<?= $warnaSel($l, $c) ?>">
                                                            <?= $isi > 0 ? $isi : '&ndash;' ?>
                                                            <small class="d-block"><?= $l * $c ?></small>
                                                        </td>
                                                    <?php endfor; ?>
                                                </tr>
                                            <?php endfor; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">
                            Angka besar = jumlah risiko; angka kecil = skor L &times; D. Warna mengikuti
                            <code>mri_tingkat_risiko</code> (<?= html_escape($skalaTeks) ?>).
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= RISIKO PER UNIT (chart + tabel) ================= -->
        <div class="row">
            <div class="col-12 mb-3">
                <div class="card card-outline card-primary h-100 mb-0">
                    <div class="card-header">
                        <strong>Risiko per Unit</strong>
                        <span class="float-right small text-muted">Jumlah risiko per bagian / UPK</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-5 mb-3 mb-lg-0">
                                <div class="chart-box" style="height:<?= (int) $tinggiUnit ?>px">
                                    <canvas id="chartUnit"></canvas>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-hover mri-table-sm mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Unit</th>
                                                <th class="text-center">Total</th>
                                                <?php foreach ($band as $t) : ?>
                                                    <th class="text-center" title="<?= html_escape($t->nama_tingkat . ' ' . $t->skor_min . '-' . $t->skor_max) ?>">
                                                        <?= html_escape($t->nama_tingkat) ?>
                                                    </th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!$d['per_unit']) : ?>
                                                <tr>
                                                    <td colspan="<?= 2 + count($band) ?>" class="text-center text-muted">Belum ada data unit.</td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php foreach ($d['per_unit'] as $u) : ?>
                                                <tr>
                                                    <td><?= html_escape($u['nama']) ?></td>
                                                    <td class="text-center"><strong><?= (int) $u['total'] ?></strong></td>
                                                    <?php foreach ($band as $t) :
                                                        $jml = isset($u['band'][$t->nama_tingkat]) ? (int) $u['band'][$t->nama_tingkat] : 0;
                                                    ?>
                                                        <td class="text-center">
                                                            <?= $jml > 0
                                                                ? '<span class="badge" style="background-color:' . $t->warna . ';color:#fff">' . $jml . '</span>'
                                                                : '<span class="text-muted">&ndash;</span>' ?>
                                                        </td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= PRIORITAS + KATEGORI/TIPE ================= -->
        <div class="row">
            <div class="col-lg-7 mb-3">
                <div class="card card-outline card-primary h-100 mb-0">
                    <div class="card-header">
                        <strong>10 Risiko Prioritas</strong>
                        <span class="float-right small text-muted">Residual tertinggi</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mri-table-sm mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Risiko</th>
                                        <th>Unit</th>
                                        <th class="text-center">Inherent</th>
                                        <th class="text-center">Residual</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!$d['prioritas']) : ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3">Belum ada risiko yang dianalisis.</td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php foreach ($d['prioritas'] as $r) : ?>
                                        <tr>
                                            <td>
                                                <a href="<?= base_url('manrisk/register/detail/' . $r->id_register) ?>">
                                                    <?= html_escape($r->kode_risiko) ?>
                                                </a>
                                            </td>
                                            <td><?= html_escape($r->risiko) ?></td>
                                            <td><small><?= html_escape($r->nama_bagian) ?></small></td>
                                            <td class="text-center">
                                                <span class="badge" style="background-color:<?= $fungsiWarna($r->level_inherent) ?>;color:#fff">
                                                    <?= (int) $r->nilai_inherent ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge" style="background-color:<?= $fungsiWarna($r->level_residual) ?>;color:#fff">
                                                    <?= (int) $r->nilai_residual ?>
                                                </span>
                                            </td>
                                            <td><small class="text-muted"><?= html_escape($aksi[$r->level_residual]) ?></small></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 mb-3">
                <div class="card card-outline card-primary h-100 mb-0">
                    <div class="card-header">
                        <strong>Kategori &amp; Tipe</strong>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mri-table-sm mb-3">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Kategori</th>
                                        <th class="text-center">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!$d['per_kategori']) : ?>
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">Belum ada data kategori.</td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php foreach ($d['per_kategori'] as $k) : ?>
                                        <tr>
                                            <td><small class="text-muted"><?= html_escape($k['kode']) ?></small> &mdash; <?= html_escape($k['nama']) ?></td>
                                            <td class="text-center"><strong><?= (int) $k['total'] ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <h4 class="mb-0 text-danger"><?= (int) $d['tipe']['Ancaman'] ?></h4>
                                    <small>Ancaman</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <h4 class="mb-0 text-success"><?= (int) $d['tipe']['Peluang'] ?></h4>
                                    <small>Peluang</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= STATUS PERLAKUAN + KEPUTUSAN + ESKALASI ================= -->
        <div class="row">
            <div class="col-lg-8 mb-3">
                <div class="card card-outline card-primary h-100 mb-0">
                    <div class="card-header">
                        <strong>Status Perlakuan &amp; Keputusan Evaluasi</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <h6 class="text-center font-weight-bold">Progres Perlakuan</h6>
                                <div class="chart-box" style="height:180px">
                                    <canvas id="chartPerlakuan"></canvas>
                                </div>
                                <table class="table table-sm table-bordered mri-table-sm mt-2 mb-0">
                                    <tbody>
                                        <tr>
                                            <td>Belum Mulai</td>
                                            <td class="text-center"><strong><?= (int) $d['progres']['BELUM'] ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td>Dalam Proses</td>
                                            <td class="text-center"><strong><?= (int) $d['progres']['DALAM_PROSES'] ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td>Selesai</td>
                                            <td class="text-center"><strong><?= (int) $d['progres']['SELESAI'] ?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-center font-weight-bold">Keputusan Evaluasi</h6>
                                <div class="chart-box" style="height:180px">
                                    <canvas id="chartKeputusan"></canvas>
                                </div>
                                <table class="table table-sm table-bordered mri-table-sm mt-2 mb-0">
                                    <tbody>
                                        <?php foreach ($d['keputusan'] as $k => $v) : ?>
                                            <tr>
                                                <td><?= html_escape($k) ?></td>
                                                <td class="text-center"><strong><?= (int) $v ?></strong></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-3">
                <div class="card card-outline card-danger h-100 mb-0">
                    <div class="card-header">
                        <strong>Risiko Perlu Eskalasi</strong>
                        <span class="float-right badge badge-danger"><?= $jumlahEskalasi ?></span>
                    </div>
                    <div class="card-body">
                        <?php if (!$d['eskalasi']) : ?>
                            <p class="text-muted mb-0">
                                <i class="fas fa-check-circle text-success"></i>
                                Tidak ada risiko yang ditandai perlu eskalasi.
                            </p>
                        <?php else : ?>
                            <ul class="list-unstyled mb-0">
                                <?php foreach ($d['eskalasi'] as $e) : ?>
                                    <li class="mb-2 pb-2 border-bottom">
                                        <strong><?= html_escape($e->kode_risiko) ?></strong>
                                        <span class="badge badge-danger ml-1"><?= (int) $e->nilai_residual ?></span><br>
                                        <small><?= html_escape($e->risiko) ?></small><br>
                                        <small class="text-muted">Eskalasi ke: <?= html_escape($e->eskalasi_ke ?: '-') ?></small>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= REKONSILIASI + PERLAKUAN ================= -->
        <div class="row">
            <div class="col-lg-6 mb-3">
                <div class="card card-outline card-primary h-100 mb-0">
                    <div class="card-header">
                        <strong>Rekonsiliasi Objektif RKAP <?= $tahun ?></strong>
                    </div>
                    <div class="card-body">
                        <div class="progress mb-2" style="height: 22px;">
                            <div class="progress-bar bg-success" style="width: <?= $rekPersen ?>%"><?= $rekPersen ?>% Selesai</div>
                        </div>
                        <p class="mb-1"><strong><?= $rekSelesai ?></strong> objektif sudah direkonsiliasi, <strong><?= $rekBelum ?></strong> belum.</p>
                        <p class="text-muted mb-0">
                            <small>
                                Objektif ber-NULL tidak dianggap selesai. Buka
                                <a href="<?= base_url('manrisk/objektif') ?>">Kelola Objektif</a>
                                untuk melihat catatan selisih antarslide paparan RKAP.
                            </small>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-3">
                <div class="card card-outline card-primary h-100 mb-0">
                    <div class="card-header">
                        <strong>Progres Perlakuan &amp; Monitoring</strong>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mri-table-sm mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Status Perlakuan</th>
                                        <th class="text-center">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Belum dimulai</td>
                                        <td class="text-center"><strong><?= isset($d['progres']['BELUM']) ? (int) $d['progres']['BELUM'] : 0 ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Dalam proses</td>
                                        <td class="text-center"><strong><?= isset($d['progres']['DALAM_PROSES']) ? (int) $d['progres']['DALAM_PROSES'] : 0 ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Selesai</td>
                                        <td class="text-center"><strong><?= isset($d['progres']['SELESAI']) ? (int) $d['progres']['SELESAI'] : 0 ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-clipboard-check"></i>
                            <?= $monitoringTercatat ?> catatan monitoring tercatat tahun ini.
                            <a href="<?= base_url('manrisk/monitoring') ?>">Buka Monitoring</a>
                        </small>
                        <small class="text-muted d-block mt-1">
                            <i class="fas fa-comments"></i>
                            <?= $komunikasiTercatat ?> sesi komunikasi tercatat
                            (<?= $komunikasiSelesai ?> selesai).
                            <a href="<?= base_url('manrisk/komunikasi') ?>">Buka Komunikasi</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= ALUR 8 TAHAP (materi 5.1) ================= -->
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-info h-100 mb-0">
                    <div class="card-header">
                        <strong>Alur 8 Tahap &mdash; SNI ISO 31000:2018</strong>
                    </div>
                    <div class="card-body py-2">
                        <div class="d-flex flex-nowrap align-items-stretch overflow-auto pb-1">
                            <?php foreach ($alur as $i => $t) :
                                $badgeStatus = $t['status'] === 'Selesai' ? 'badge-success'
                                    : ($t['status'] === 'Sebagian' ? 'badge-warning' : 'badge-secondary');
                            ?>
                                <?php if ($i > 0) : ?>
                                    <span class="align-self-center text-muted mx-1 flex-shrink-0">
                                        <i class="fas fa-chevron-right small"></i>
                                    </span>
                                <?php endif; ?>
                                <a href="<?= base_url($t['rute']) ?>" class="alur-item text-decoration-none small rounded border p-2" style="<?= $t['status'] === 'Belum' ? 'opacity:.6;' : '' ?>">
                                    <span class="badge <?= $badgeStatus ?>">
                                        <?= (int) $t['no'] ?>
                                    </span>
                                    <strong class="text-body"><?= html_escape($t['judul']) ?></strong>
                                    <div class="mt-1 mb-0">
                                        <span class="badge badge-light border"><?= (int) $t['x'] ?>/<?= (int) $t['y'] ?></span>
                                        <small class="text-muted"><?= (int) $t['persen'] ?>%</small>
                                    </div>
                                    <div class="progress progress-xs mt-1 mb-0" style="height:4px">
                                        <div class="progress-bar <?= $t['status'] === 'Selesai' ? 'bg-success' : ($t['status'] === 'Sebagian' ? 'bg-warning' : 'bg-secondary') ?>" style="width:<?= (int) $t['persen'] ?>%"></div>
                                    </div>
                                    <small class="text-muted d-block mt-1"><?= html_escape($t['status']) ?></small>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        'use strict';

        var level = <?= json_encode(array_map(function ($t) {
                        return $t->nama_tingkat;
                    }, $d['tingkat'])) ?>;
        var inherent = <?= json_encode(array_map(function ($t) use ($d) {
                            return isset($d['level_inherent'][$t->nama_tingkat]) ? (int) $d['level_inherent'][$t->nama_tingkat] : 0;
                        }, $d['tingkat'])) ?>;
        var residual = <?= json_encode(array_map(function ($t) use ($d) {
                            return isset($d['level_residual'][$t->nama_tingkat]) ? (int) $d['level_residual'][$t->nama_tingkat] : 0;
                        }, $d['tingkat'])) ?>;

        /* ---- Sebaran level: inherent vs residual ---- */
        new Chart(document.getElementById('chartLevel'), {
            type: 'bar',
            data: {
                labels: level,
                datasets: [{
                        label: 'Inherent',
                        data: inherent,
                        backgroundColor: '#3c8dbc'
                    },
                    {
                        label: 'Residual',
                        data: residual,
                        backgroundColor: '#17a2b8'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'bottom'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0,
                            suggestedMax: 5
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                }
            }
        });

        /* ---- Risiko per unit: batang horizontal agar nama unit terbaca ---- */
        var unitNama = <?= json_encode(array_column($d['per_unit'], 'nama')) ?>;
        var unitTotal = <?= json_encode(array_map('intval', array_column($d['per_unit'], 'total'))) ?>;
        new Chart(document.getElementById('chartUnit'), {
            type: 'horizontalBar',
            data: {
                labels: unitNama,
                datasets: [{
                    label: 'Jumlah risiko',
                    data: unitTotal,
                    backgroundColor: '#3c8dbc'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                }
            }
        });

        /* ---- Progres perlakuan ---- */
        new Chart(document.getElementById('chartPerlakuan'), {
            type: 'doughnut',
            data: {
                labels: ['Belum Mulai', 'Dalam Proses', 'Selesai'],
                datasets: [{
                    data: [<?= (int) $d['progres']['BELUM'] ?>, <?= (int) $d['progres']['DALAM_PROSES'] ?>, <?= (int) $d['progres']['SELESAI'] ?>],
                    backgroundColor: ['#dc3545', '#ffc107', '#28a745']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutoutPercentage: 62,
                legend: {
                    position: 'bottom'
                }
            }
        });

        /* ---- Keputusan evaluasi ---- */
        new Chart(document.getElementById('chartKeputusan'), {
            type: 'doughnut',
            data: {
                labels: ['Accept', 'Monitor', 'Treat', 'Escalate'],
                datasets: [{
                    data: [<?= (int) $d['keputusan']['Accept'] ?>, <?= (int) $d['keputusan']['Monitor'] ?>, <?= (int) $d['keputusan']['Treat'] ?>, <?= (int) $d['keputusan']['Escalate'] ?>],
                    backgroundColor: ['#28a745', '#ffc107', '#fd7e14', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutoutPercentage: 62,
                legend: {
                    position: 'bottom'
                }
            }
        });
    });
</script>