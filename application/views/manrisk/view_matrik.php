<?php
/**
 * Matriks risiko 5 x 5 — inherent dan residual.
 *
 * Warna sel diambil dari warna level mri_tingkat_risiko untuk skor L x D,
 * sehingga warna matriks identik dengan badge pada Risk Register.
 */
$skorWarna = function ($l, $d) use ($warnaSkor) {
    return isset($warnaSkor[$l * $d]) ? $warnaSkor[$l * $d] : '#e9ecef';
};
$barisLabel = [1 => '1 — sangat jarang', 2 => '2 — jarang', 3 => '3 — kadang', 4 => '4 — sering', 5 => '5 — sangat sering'];
$kolomLabel = [1 => '1 — dapat diabaikan', 2 => '2 — kecil', 3 => '3 — sedang', 4 => '4 — besar', 5 => '5 — sangat besar'];

$render = function ($judul, $sel, $kelas) use ($barisLabel, $kolomLabel, $skorWarna) {
    $total = 0;
    foreach ($sel as $l => $row) {
        foreach ($row as $c) {
            $total += $c;
        }
    }
    $html = '<div class="card card-outline card-primary">';
    $html .= '<div class="card-header"><strong>' . $judul . '</strong>'
        . ' <span class="badge badge-light">' . $total . ' risiko</span></div>';
    $html .= '<div class="card-body table-responsive"><table class="table table-bordered text-center mb-0">';
    $html .= '<thead class="thead-light"><tr><th rowspan="2" style="vertical-align:middle">Likelihood &darr;<br>Dampak &rarr;</th>';
    foreach ($kolomLabel as $c) {
        $html .= '<th>' . htmlspecialchars($c) . '</th>';
    }
    $html .= '</tr><tr>';
    foreach ($kolomLabel as $c) {
        $html .= '<th><small>skor</small></th>';
    }
    $html .= '</tr></thead><tbody>';

    for ($l = 5; $l >= 1; $l--) {
        $html .= '<tr><th class="text-left"><small>' . htmlspecialchars($barisLabel[$l]) . '</small></th>';
        for ($d = 1; $d <= 5; $d++) {
            $n = isset($sel[$l][$d]) ? $sel[$l][$d] : 0;
            $warna = $skorWarna($l, $d);
            if ($n > 0) {
                $html .= '<td class="' . $kelas . '" style="background-color:' . $warna . ';color:#fff;font-weight:700;font-size:1.15rem">'
                    . $n . '<br><small>' . ($l * $d) . '</small></td>';
            } else {
                $html .= '<td class="' . $kelas . '" style="background-color:' . $warna . ';opacity:.35;color:#343a40">'
                    . '<small>&middot;</small></td>';
            }
        }
        $html .= '</tr>';
    }
    $html .= '</tbody></table></div></div>';
    return $html;
};
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <!-- ============== FILTER ============== -->
        <div class="card card-outline card-primary">
            <div class="card-header">
                <form method="get" action="<?= base_url('manrisk/matrik') ?>" class="form-inline">
                    <?php if ($akses_semua) : ?>
                        <div class="form-group mr-2 mb-2">
                            <label for="f_upk" class="mr-2">Unit</label>
                            <select name="id_upk" id="f_upk" class="form-control">
                                <option value="">Semua</option>
                                <?php foreach ($unit_list as $u) : ?>
                                    <option value="<?= $u->id_bagian ?>" <?= isset($filter['id_upk']) && (string) $filter['id_upk'] === (string) $u->id_bagian ? 'selected' : '' ?>>
                                        <?= html_escape($u->nama_bagian) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="form-group mr-2 mb-2">
                        <label for="f_kat" class="mr-2">Kategori</label>
                        <select name="id_kategori" id="f_kat" class="form-control">
                            <option value="">Semua</option>
                            <?php foreach ($kategori_list as $k) : ?>
                                <option value="<?= $k->id_kategori ?>" <?= isset($filter['id_kategori']) && (string) $filter['id_kategori'] === (string) $k->id_kategori ? 'selected' : '' ?>>
                                    <?= html_escape($k->nama_kat) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="neumorphic-button mb-2"><i class="fas fa-filter"></i> Tampilkan</button>
                    <a href="<?= base_url('manrisk/matrik') ?>" class="neumorphic-button mb-2 ml-2"><i class="fas fa-undo"></i> Reset</a>
                </form>
            </div>
        </div>

        <div class="row align-items-center mb-2">
            <div class="col-md-8">
                <h4 class="mb-0"><?= strtoupper($title) ?> <?= $tahun ?></h4>
                <small class="text-muted">
                    Dihitung dari <code>mri_analisa</code> — bukan dari data legacy.
                    Matriks legacy 2025&ndash;2026 dapat dilihat pada
                    <a href="<?= base_url('risiko/matrik_risiko') ?>">modul lama</a>.
                </small>
            </div>
            <div class="col-md-4 text-md-right">
                <a href="<?= base_url('manrisk/register') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-list"></i> Register
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <?= $render('SEBELUM KONTROL — Inherent', $inherent, 'cell-inherent') ?>
            </div>
            <div class="col-lg-6">
                <?= $render('SETELAH KONTROL — Residual', $residual, 'cell-residual') ?>
            </div>
        </div>

        <!-- ============== LEGENDA ============== -->
        <div class="card card-outline card-info">
            <div class="card-header"><strong>Legenda Level</strong></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-7">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-light">
                                <tr><th>Level</th><th>Skor L&times;D</th><th>Aksi</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tingkat as $t) : ?>
                                    <tr>
                                        <td><span class="badge" style="background-color:<?= $t->warna ?>;color:#fff"><?= html_escape($t->nama_tingkat) ?></span></td>
                                        <td><?= (int) $t->skor_min ?>&ndash;<?= (int) $t->skor_max ?></td>
                                        <td><small><?= html_escape($t->aksi) ?></small></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-5">
                        <p class="mb-1"><strong>Cara membaca</strong></p>
                        <ul class="small mb-0">
                            <li>Sumbu datar = Dampak (1&ndash;5), sumbu tegak = Likelihood (1&ndash;5).</li>
                            <li>Angka besar = jumlah risiko pada sel tersebut; angka kecil = skor L&times;D sel itu.</li>
                            <li>Sel berwarna tanpa angka berarti tidak ada risiko pada sel tersebut.</li>
                            <li>Membandingkan kedua matriks menunjukkan efektivitas kontrol.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
</div>
