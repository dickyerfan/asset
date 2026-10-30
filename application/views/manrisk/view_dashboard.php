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
 */
$warnaSel = function ($l, $c) use ($fungsiWarna) {
    return $fungsiWarna($this->Model_mri_master->levelDariSkor($l * $c));
};
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <!-- ================= FILTER ================= -->
        <div class="card card-outline card-primary">
            <div class="card-header">
                <form method="get" action="<?= base_url('manrisk/dashboard') ?>" class="form-inline">
                    <?php if ($akses_semua) : ?>
                        <div class="form-group mr-2 mb-2">
                            <label for="id_upk" class="mr-2">Bagian / UPK</label>
                            <select name="id_upk" id="id_upk" class="form-control">
                                <option value="">Semua Bagian / UPK</option>
                                <?php foreach ($unit_list as $unit) : ?>
                                    <option value="<?= $unit->id_bagian ?>" <?= isset($filter['id_upk']) && (string) $filter['id_upk'] === (string)$unit->id_bagian ? 'selected' : '' ?>>
                                        <?= html_escape($unit->nama_bagian) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="form-group mr-2 mb-2">
                        <label for="id_kategori" class="mr-2">Kategori</label>
                        <select name="id_kategori" id="id_kategori" class="form-control">
                            <option value="">Semua Kategori</option>
                            <?php foreach ($kategori_list as $k) : ?>
                                <option value="<?= $k->id_kategori ?>" <?= isset($filter['id_kategori']) && (string) $filter['id_kategori'] === (string)$k->id_kategori ? 'selected' : '' ?>>
                                    <?= html_escape($k->nama_kat) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mr-2 mb-2">
                        <label for="tipe_risiko" class="mr-2">Tipe</label>
                        <select name="tipe_risiko" id="tipe_risiko" class="form-control">
                            <option value="">Semua</option>
                            <option value="Ancaman" <?= isset($filter['tipe_risiko']) && $filter['tipe_risiko'] === 'Ancaman' ? 'selected' : '' ?>>Ancaman</option>
                            <option value="Peluang" <?= isset($filter['tipe_risiko']) && $filter['tipe_risiko'] === 'Peluang' ? 'selected' : '' ?>>Peluang</option>
                        </select>
                    </div>

                    <div class="form-group mr-2 mb-2">
                        <label for="tahun" class="mr-2">Tahun</label>
                        <select name="tahun" id="tahun" class="form-control">
                            <?php foreach ($daftar_tahun as $t) : ?>
                                <option value="<?= $t ?>" <?= (int) $tahun === $t ? 'selected' : '' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mr-2 mb-2">
                        <label for="q" class="mr-2">Cari</label>
                        <input type="text" name="q" id="q" class="form-control" placeholder="kode / risiko / KPI" value="<?= html_escape(isset($filter['q']) ? $filter['q'] : '') ?>">
                    </div>

                    <button type="submit" class="neumorphic-button mb-2"><i class="fas fa-filter"></i> Tampilkan</button>
                    <a href="<?= base_url('manrisk/dashboard') ?>" class="neumorphic-button mb-2 ml-2"><i class="fas fa-undo"></i> Reset</a>
                </form>
            </div>
        </div>

        <div class="row justify-content-center mb-2">
            <div class="col-lg-8 text-center">
                <h2><?= strtoupper($title); ?> <?= $tahun; ?></h2>
                <?php if ($lingkup) : ?>
                    <p class="text-muted mb-0">
                        Lingkup: <?= html_escape($lingkup->nama_lingkup) ?>
                        &middot; status <strong><?= html_escape($lingkup->status) ?></strong>
                    </p>
                <?php else : ?>
                    <p class="text-warning mb-0">Belum ada lingkup/konteks yang ditetapkan untuk tahun ini.</p>
                <?php endif; ?>
                <?php if ($d['tanpa_analisa'] > 0) : ?>
                    <p class="text-danger mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?= (int) $d['tanpa_analisa'] ?> risiko belum dianalisis (L x D belum diisi).
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- ============ ALUR 8 TAHAP (materi 5.1) ============ -->
        <div class="card card-outline card-info">
            <div class="card-header">
                <strong>Alur 8 Tahap &mdash; SNI ISO 31000:2018</strong>
                <a href="<?= base_url('manrisk/panduan') ?>" class="float-right small">
                    <i class="fas fa-book"></i> Petunjuk Penggunaan
                </a>
            </div>
            <div class="card-body py-2">
                <div class="d-flex flex-nowrap align-items-stretch overflow-auto pb-1">
                    <?php foreach ($alur as $i => $t) : ?>
                        <?php if ($i > 0) : ?>
                            <span class="align-self-center text-muted mx-1 flex-shrink-0">
                                <i class="fas fa-chevron-right small"></i>
                            </span>
                        <?php endif; ?>
                        <a href="<?= base_url($t['rute']) ?>"
                           class="flex-shrink-0 text-decoration-none small rounded border p-2"
                           style="min-width:168px;<?= $t['terisi'] ? '' : 'opacity:.6;' ?>">
                            <span class="badge <?= $t['terisi'] ? 'badge-success' : 'badge-secondary' ?>">
                                <?= (int) $t['no'] ?>
                            </span>
                            <strong class="text-body"><?= html_escape($t['judul']) ?></strong>
                            <div class="text-muted mt-1 mb-0">
                                <?= (int) $t['jumlah'] ?> <?= html_escape($t['satuan']) ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <p class="small text-muted mt-1 mb-0">
                    Tahap 1 dan 2 sama-sama dicatat pada halaman Lingkup &middot;
                    KPI/KRI adalah bagian dari tahap 7 &middot;
                    urutan tahap mengikuti Ringkasan MR &sect;5.1.
                </p>
            </div>
        </div>

        <!-- ================= KPI ================= -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3><?= $total ?></h3>
                        <p>Total Risiko Tercatat</p>
                    </div>
                    <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                    <a href="<?= base_url('manrisk/register') ?>" class="small-box-footer">
                        Buka Risk Register <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
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

            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= (int) $d['rekonsiliasi']['SELESAI'] ?>/<?= (int) $d['rekonsiliasi']['SELESAI'] + (int) $d['rekonsiliasi']['BELUM'] ?></h3>
                        <p>Objektif RKAP Rekonsiliasi</p>
                    </div>
                    <div class="icon"><i class="fas fa-bullseye"></i></div>
                    <a href="<?= base_url('manrisk/objektif') ?>" class="small-box-footer">
                        Kelola Objektif <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= (int) $d['kri']['Belum Diukur'] ?>/<?= array_sum($d['kri']) ?></h3>
                        <p>KRI Belum Diukur</p>
                    </div>
                    <div class="icon"><i class="fas fa-tachometer-alt"></i></div>
                    <a href="<?= base_url('manrisk/kri') ?>" class="small-box-footer">
                        Kelola KRI <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- ============ SEBARAN LEVEL ============ -->
            <div class="col-lg-4">
                <div class="card card-outline card-primary">
                    <div class="card-header"><strong>Sebaran Level Risiko</strong></div>
                    <div class="card-body">
                        <canvas id="chartLevel" height="220"></canvas>

                        <table class="table table-sm table-bordered mt-3 mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Level</th>
                                    <th class="text-center">Rentang</th>
                                    <th class="text-center">Inherent</th>
                                    <th class="text-center">Residual</th>
                                    <th class="text-center">Aksi</th>
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
                                        <td class="text-center"><small><?= html_escape($t->aksi) ?></small></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ============ MATRIKS 5x5 ============ -->
            <div class="col-lg-8">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <strong>Matriks Risiko 5 &times; 5 (Likelihood &times; Dampak)</strong>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center mb-0">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="vertical-align:middle;background-color:#b6e8faff">LIKELIHOOD</th>
                                        <th rowspan="2" style="vertical-align:middle;background-color:#f6e79dff">L</th>
                                        <th colspan="5">DAMPAK / CONSEQUENCE</th>
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
                                                <td style="background-color:<?= $this->Model_mri_master->levelDariSkor($l * $c)->warna ?>;font-weight:bold">
                                                    <?= $isi > 0 ? $isi : '&ndash;' ?>
                                                    <small class="d-block" style="font-weight:normal"><?= $l * $c ?></small>
                                                </td>
                                            <?php endfor; ?>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>

                        <hr>
                        <strong>Matriks Residual (setelah kontrol &amp; perlakuan)</strong>
                        <div class="table-responsive mt-2">
                            <table class="table table-bordered text-center mb-0">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="vertical-align:middle;background-color:#b6e8faff">LIKELIHOOD</th>
                                        <th rowspan="2" style="vertical-align:middle;background-color:#f6e79dff">L</th>
                                        <th colspan="5">DAMPAK / CONSEQUENCE</th>
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
                                                <td style="background-color:<?= $this->Model_mri_master->levelDariSkor($l * $c)->warna ?>;font-weight:bold">
                                                    <?= $isi > 0 ? $isi : '&ndash;' ?>
                                                    <small class="d-block" style="font-weight:normal"><?= $l * $c ?></small>
                                                </td>
                                            <?php endfor; ?>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted d-block mt-2">
                            Angka besar = jumlah risiko; angka kecil = skor L &times; D. Warna mengikuti
                            <code>mri_tingkat_risiko</code> (<?= html_escape($skalaTeks) ?>).
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- ============ PRIORITAS ============ -->
            <div class="col-lg-7">
                <div class="card card-outline card-primary">
                    <div class="card-header"><strong>10 Risiko Prioritas (Residual Tertinggi)</strong></div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-hover" id="tabelPrioritas">
                            <thead class="thead-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Risiko</th>
                                    <th>Unit</th>
                                    <th class="text-center">Inherent</th>
                                    <th class="text-center">Residual</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!$d['prioritas']) : ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada risiko yang dianalisis.</td>
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
                                        <td><?= html_escape($r->nama_bagian) ?></td>
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
                                        <td class="text-center"><small><?= html_escape($aksi[$r->level_residual]) ?></small></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ============ PER UNIT + KATEGORI + TIPE ============ -->
            <div class="col-lg-5">
                <div class="card card-outline card-primary">
                    <div class="card-header"><strong>Risiko per Unit</strong></div>
                    <div class="card-body">
                        <canvas id="chartUnit" height="200"></canvas>
                        <table class="table table-sm table-bordered mt-3 mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Unit</th>
                                    <th class="text-center">Total</th>
                                    <?php foreach ($band as $t) : ?>
                                        <th class="text-center"
                                            title="<?= html_escape($t->nama_tingkat . ' ' . $t->skor_min . '-' . $t->skor_max) ?>">
                                            <?= html_escape($t->nama_tingkat) ?>
                                        </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($d['per_unit'] as $u) : ?>
                                    <tr>
                                        <td><?= html_escape($u['nama']) ?></td>
                                        <td class="text-center"><?= (int) $u['total'] ?></td>
                                        <?php foreach ($band as $t) :
                                            $jml = isset($u['band'][$t->nama_tingkat]) ? (int) $u['band'][$t->nama_tingkat] : 0;
                                        ?>
                                            <td class="text-center">
                                                <?= $jml > 0
                                                    ? '<span class="badge" style="background-color:' . $t->warna . ';color:#fff">' . $jml . '</span>'
                                                    : '-' ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card card-outline card-primary">
                    <div class="card-header"><strong>Per Kategori &amp; Tipe</strong></div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered mb-3">
                            <thead class="thead-light">
                                <tr>
                                    <th>Kategori</th>
                                    <th class="text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($d['per_kategori'] as $k) : ?>
                                    <tr>
                                        <td><small><?= html_escape($k['kode']) ?></small> — <?= html_escape($k['nama']) ?></td>
                                        <td class="text-center"><?= (int) $k['total'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

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

        <div class="row">
            <!-- ============ PROGRES PERLAKUAN ============ -->
            <div class="col-lg-4">
                <div class="card card-outline card-primary">
                    <div class="card-header"><strong>Progres Perlakuan Risiko</strong></div>
                    <div class="card-body">
                        <canvas id="chartPerlakuan" height="180"></canvas>
                        <table class="table table-sm table-bordered mt-3 mb-0">
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
                </div>
            </div>

            <!-- ============ KEPUTUSAN EVALUASI ============ -->
            <div class="col-lg-4">
                <div class="card card-outline card-primary">
                    <div class="card-header"><strong>Keputusan Evaluasi</strong></div>
                    <div class="card-body">
                        <canvas id="chartKeputusan" height="180"></canvas>
                        <table class="table table-sm table-bordered mt-3 mb-0">
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

            <!-- ============ ESKALASI ============ -->
            <div class="col-lg-4">
                <div class="card card-outline card-danger">
                    <div class="card-header"><strong>Risiko Perlu Eskalasi</strong></div>
                    <div class="card-body">
                        <?php if (!$d['eskalasi']) : ?>
                            <p class="text-muted mb-0">Tidak ada risiko yang ditandai perlu eskalasi.</p>
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

        <div class="row">
            <!-- ============ EKSEKUTIF ============ -->
            <div class="col-lg-6">
                <div class="card card-outline card-primary">
                    <div class="card-header"><strong>Rekonsiliasi Objektif RKAP <?= $tahun ?></strong></div>
                    <div class="card-body">
                        <?php
                        $selesai = (int) $d['rekonsiliasi']['SELESAI'];
                        $belum = (int) $d['rekonsiliasi']['BELUM'];
                        $jml = $selesai + $belum;
                        $ps = $jml > 0 ? round($selesai / $jml * 100, 1) : 0;
                        ?>
                        <div class="progress mb-2" style="height: 22px;">
                            <div class="progress-bar bg-success" style="width: <?= $ps ?>%"><?= $ps ?>% Selesai</div>
                        </div>
                        <p class="mb-1"><strong><?= $selesai ?></strong> objektif sudah direkonsiliasi, <strong><?= $belum ?></strong> belum.</p>
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

            <div class="col-lg-6">
                <div class="card card-outline card-primary">
                    <div class="card-header"><strong>Key Risk Indicator</strong></div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Status</th>
                                    <th class="text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($kri_rekap as $st => $jml) : ?>
                                    <tr>
                                        <td><?= html_escape($st) ?></td>
                                        <td class="text-center"><strong><?= (int) $jml ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <small class="text-muted d-block mt-2">
                            Tahun anggaran belum berjalan, sehingga seluruh KRI berstatus
                            <em>Belum Diukur</em> — bukan "Tidak Tercapai".
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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
                legend: {
                    position: 'bottom'
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }]
                }
            }
        });

        var unitNama = <?= json_encode(array_column($d['per_unit'], 'nama')) ?>;
        var unitTotal = <?= json_encode(array_map('intval', array_column($d['per_unit'], 'total'))) ?>;
        new Chart(document.getElementById('chartUnit'), {
            type: 'bar',
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
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }]
                }
            }
        });

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
                legend: {
                    position: 'bottom'
                }
            }
        });

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
                legend: {
                    position: 'bottom'
                }
            }
        });
    });
</script>