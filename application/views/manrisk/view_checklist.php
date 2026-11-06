<?php
/**
 * Checklist & Target Manajemen Risiko.
 * Template butir tetap; target dan centang manual disimpan per tahun.
 */
$bulan = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

$tglIndo = function ($tgl) use ($bulan) {
    if (!$tgl) {
        return '-';
    }
    $ts = strtotime($tgl);
    if (!$ts) {
        return '-';
    }
    return date('d', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
};

$badgeStatus = function ($status) {
    if ($status === 'Selesai') {
        return 'success';
    }
    if ($status === 'Sebagian') {
        return 'warning';
    }
    return 'secondary';
};

$warnaPersen = function ($persen) {
    if ($persen >= 100) {
        return 'success';
    }
    if ($persen >= 50) {
        return 'warning';
    }
    if ($persen > 0) {
        return 'info';
    }
    return 'secondary';
};

$ringkas = $ringkas;
$terdekat = $ringkas['target_terdekat'];
$unitLabel = $id_upk > 0 ? $nama_unit : 'Semua UPK / Global';
?>

<section class="content">
    <div class="container-fluid mri-checklist">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <!-- ================= JUDUL + FILTER ================= -->
        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
            <div class="mb-2">
                <h4 class="mb-0 font-weight-bold">
                    <i class="fas fa-clipboard-list text-primary mr-1"></i>
                    <?= strtoupper($title) ?>
                </h4>
                <small class="text-muted">
                    Template butir tetap untuk semua tahun &middot; target &amp; centang disimpan per tahun
                    (opsional berbeda per unit).
                </small>
            </div>
            <div class="mt-1 mt-md-0">
                <form method="get" action="<?= base_url('manrisk/checklist') ?>" class="form-inline d-inline-flex flex-wrap">
                    <label class="mr-1 mb-1 small text-muted">Tahun</label>
                    <select name="tahun" class="form-control form-control-sm mr-2 mb-1" onchange="this.form.submit()">
                        <?php foreach ($daftar_tahun as $th) : ?>
                            <option value="<?= (int) $th ?>" <?= (int) $th === (int) $tahun ? 'selected' : '' ?>>
                                <?= (int) $th ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <?php if ($akses_semua) : ?>
                        <label class="mr-1 mb-1 small text-muted">Unit</label>
                        <select name="id_upk" class="form-control form-control-sm mb-1" style="min-width:190px" onchange="this.form.submit()">
                            <option value="0" <?= $id_upk === 0 ? 'selected' : '' ?>>Semua UPK / Global</option>
                            <?php foreach ($unit_list as $u) : ?>
                                <option value="<?= (int) $u->id_bagian ?>" <?= (int) $u->id_bagian === (int) $id_upk ? 'selected' : '' ?>>
                                    <?= html_escape($u->nama_bagian) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php else : ?>
                        <input type="hidden" name="id_upk" value="<?= (int) $id_upk ?>">
                        <span class="badge badge-light border mb-1 mr-2"><?= html_escape($unitLabel) ?></span>
                    <?php endif; ?>

                    <noscript><button type="submit" class="btn btn-sm btn-outline-secondary mb-1">Terapkan</button></noscript>
                </form>

                <?php if ($boleh_simpan) : ?>
                    <button type="submit" form="formTarget" class="btn btn-primary btn-sm mb-1">
                        <i class="fas fa-save"></i> Simpan Target
                    </button>
                <?php endif; ?>
                <button type="button" class="btn btn-outline-secondary btn-sm mb-1 no-print" onclick="window.print()">
                    <i class="fas fa-print"></i> Cetak
                </button>
            </div>
        </div>

        <!-- ================= RINGKASAN ================= -->
        <div class="row mb-3">
            <div class="col-6 col-md-3 mb-2">
                <div class="stat">
                    <div class="lbl">Item selesai</div>
                    <div class="val text-success">
                        <?= (int) $ringkas['item_selesai'] ?>
                        <small class="text-muted" style="font-size:.8rem">/ <?= (int) $ringkas['item_total'] ?></small>
                    </div>
                    <div class="progress mt-2">
                        <div class="progress-bar bg-success" style="width:<?= $ringkas['item_total'] > 0 ? round($ringkas['item_selesai'] / $ringkas['item_total'] * 100) : 0 ?>%"></div>
                    </div>
                    <div class="sub mt-1">
                        <?= $ringkas['item_total'] > 0 ? round($ringkas['item_selesai'] / $ringkas['item_total'] * 100) : 0 ?>%
                        dari seluruh checklist
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="stat">
                    <div class="lbl">Tahap selesai</div>
                    <div class="val text-primary">
                        <?= (int) $ringkas['tahap_selesai'] ?>
                        <small class="text-muted" style="font-size:.8rem">/ <?= (int) $ringkas['tahap_total'] ?></small>
                    </div>
                    <div class="sub mt-2">
                        <?= (int) $ringkas['tahap_selesai'] ?> tahap sudah 100%, sisanya masih berjalan.
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="stat">
                    <div class="lbl">Target terdekat</div>
                    <?php if ($terdekat) : ?>
                        <div class="val" style="font-size:1.05rem"><?= html_escape($terdekat['judul']) ?></div>
                        <div class="sub mt-1">
                            <i class="far fa-calendar-alt"></i> <?= $tglIndo($terdekat['tanggal']) ?>
                            &middot;
                            <?php if ((int) $terdekat['hari'] >= 0) : ?>
                                <span class="badge badge-warning"><?= (int) $terdekat['hari'] ?> hari lagi</span>
                            <?php else : ?>
                                <span class="badge badge-danger">lewat <?= abs((int) $terdekat['hari']) ?> hari</span>
                            <?php endif; ?>
                        </div>
                        <div class="sub mt-1"><?= html_escape($terdekat['jenis']) ?></div>
                    <?php else : ?>
                        <div class="val" style="font-size:1.05rem">Belum diisi</div>
                        <div class="sub mt-1">Isi target pada tabel di bawah.</div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="stat">
                    <div class="lbl">Status keseluruhan</div>
                    <div class="val" style="font-size:1.05rem">
                        <span class="badge badge-<?= $ringkas['persen'] >= 100 ? 'success' : ($ringkas['persen'] > 0 ? 'warning' : 'secondary') ?>">
                            <?= strtoupper($ringkas['status']) ?>
                        </span>
                    </div>
                    <div class="sub mt-1">
                        Periode <?= (int) $tahun ?> &middot; <?= html_escape($unitLabel) ?>
                    </div>
                    <div class="progress mt-2">
                        <div class="progress-bar bg-<?= $warnaPersen($ringkas['persen']) ?>" style="width:<?= (int) $ringkas['persen'] ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= FORM TARGET + CHECKLIST ================= -->
        <form id="formTarget" method="post" action="<?= base_url('manrisk/checklist/simpan') ?>">
            <input type="hidden" name="tahun" value="<?= (int) $tahun ?>">
            <input type="hidden" name="id_upk" value="<?= (int) $id_upk ?>">

            <!-- ===== TARGET TAHUNAN ===== -->
            <div class="card card-outline card-primary">
                <div class="card-header py-2 d-flex justify-content-between align-items-center flex-wrap">
                    <strong><i class="fas fa-bullseye text-primary"></i> Target Tahunan per Tahap</strong>
                    <small class="text-muted">
                        <?= $id_upk > 0 ? 'Target unit ini (boleh berbeda dari global).' : 'Target global, berlaku untuk semua unit yang tidak punya override.' ?>
                    </small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width:42px" class="text-center">#</th>
                                    <th>Tahap</th>
                                    <th style="width:150px">Target Mulai</th>
                                    <th style="width:150px">Target Selesai</th>
                                    <th style="width:180px">PIC / Penanggung Jawab</th>
                                    <th style="width:150px" class="text-center">Progres</th>
                                    <th style="width:110px" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ringkas['tahap'] as $t) :
                                    $warna          = $warnaPersen($t['persen']);
                                    $isUnitOverride = $t['target_scope'] === 'unit';
                                    $pakaiNilai     = $t['target'] && ($id_upk === 0 || $isUnitOverride);
                                    $nilaiMulai     = $pakaiNilai ? $t['target']->target_mulai : '';
                                    $nilaiSelesai   = $pakaiNilai ? $t['target']->target_selesai : '';
                                    $nilaiPic       = $pakaiNilai ? $t['target']->pic : '';
                                    $hintGlobal     = $id_upk > 0 && $t['target'] && $t['target_scope'] === 'global';
                                ?>
                                    <tr>
                                        <td class="text-center"><?= (int) $t['no'] ?></td>
                                        <td>
                                            <?= html_escape($t['judul']) ?>
                                            <?php if ($t['target_scope'] === 'unit') : ?>
                                                <span class="badge badge-info ml-1">override unit</span>
                                            <?php elseif ($t['target_scope'] === 'global' && $id_upk > 0) : ?>
                                                <span class="badge badge-light border ml-1">mengikuti global</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <input type="date" class="form-control form-control-sm"
                                                name="tahap[<?= (int) $t['no'] ?>][mulai]"
                                                value="<?= $nilaiMulai ? html_escape($nilaiMulai) : '' ?>">
                                            <?php if ($hintGlobal && $t['target']->target_mulai) : ?>
                                                <div class="mini text-muted mt-1">global: <?= $tglIndo($t['target']->target_mulai) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <input type="date" class="form-control form-control-sm"
                                                name="tahap[<?= (int) $t['no'] ?>][selesai]"
                                                value="<?= $nilaiSelesai ? html_escape($nilaiSelesai) : '' ?>">
                                            <?php if ($hintGlobal && $t['target']->target_selesai) : ?>
                                                <div class="mini text-muted mt-1">global: <?= $tglIndo($t['target']->target_selesai) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm"
                                                name="tahap[<?= (int) $t['no'] ?>][pic]"
                                                placeholder="mis. Kepala UPK"
                                                value="<?= $nilaiPic ? html_escape($nilaiPic) : '' ?>">
                                            <?php if ($hintGlobal && $t['target']->pic) : ?>
                                                <div class="mini text-muted mt-1">global: <?= html_escape($t['target']->pic) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-light border"><?= (int) $t['num'] ?>/<?= (int) $t['den'] ?></span>
                                            <small class="text-<?= $warna ?>"><?= (int) $t['persen'] ?>%</small>
                                            <div class="progress mt-1">
                                                <div class="progress-bar bg-<?= $warna ?>" style="width:<?= (int) $t['persen'] ?>%"></div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-<?= $badgeStatus($t['status']) ?>">
                                                <?= strtoupper($t['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-2 d-flex justify-content-between align-items-center flex-wrap">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Target tahap dipakai di halaman <strong>Status per UPK</strong>.
                        Tahun berikutnya cukup ganti pilihan tahun.
                    </small>
                    <?php if ($id_upk > 0) : ?>
                        <button type="submit" form="formHapusOverride" class="btn btn-sm btn-outline-danger no-print"
                            onclick="return confirm('Hapus seluruh target khusus unit ini? Target global akan dipakai kembali.');">
                            <i class="fas fa-undo"></i> Hapus override unit
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ===== CHECKLIST DETAIL ===== -->
            <div class="card card-outline card-info mt-3">
                <div class="card-header py-2">
                    <strong><i class="fas fa-check-square text-info"></i> Checklist per Tahap</strong>
                    <span class="badge badge-light border ml-2">Tahun <?= (int) $tahun ?></span>
                    <span class="badge badge-light border ml-1"><?= html_escape($unitLabel) ?></span>
                    <?php if (!$boleh_centang) : ?>
                        <span class="badge badge-warning ml-1">Pilih unit untuk mencentang butir manual</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="accordion" id="accChecklist">
                        <?php
                        $sudahBuka = false;
                        foreach ($ringkas['tahap'] as $t) :
                            $buka = false;
                            if (!$sudahBuka && (int) $t['persen'] < 100) {
                                $buka = true;
                                $sudahBuka = true;
                            }
                            $warna = $warnaPersen($t['persen']);
                        ?>
                            <div class="card border mb-2">
                                <div class="card-header py-2 bg-white">
                                    <div class="tahap-head" data-toggle="collapse" data-target="#t<?= (int) $t['no'] ?>" role="button" aria-expanded="<?= $buka ? 'true' : 'false' ?>">
                                        <span class="badge badge-primary"><?= (int) $t['no'] ?></span>
                                        <p class="judul mb-0">
                                            <?= html_escape($t['judul']) ?>
                                            <small class="text-muted">(<?= (int) $t['item_selesai'] ?>/<?= (int) $t['item_total'] ?> butir)</small>
                                        </p>
                                        <span class="badge badge-<?= $badgeStatus($t['status']) ?>">
                                            <?= strtoupper($t['status']) ?> <?= (int) $t['num'] ?>/<?= (int) $t['den'] ?>
                                        </span>
                                        <span class="tag-target">
                                            <i class="far fa-calendar-check"></i>
                                            Target:
                                            <?= ($t['target'] && $t['target']->target_selesai) ? $tglIndo($t['target']->target_selesai) : 'belum diisi' ?>
                                        </span>
                                        <span class="ml-auto small text-muted">
                                            <i class="fas fa-chevron-<?= $buka ? 'up' : 'down' ?>"></i>
                                        </span>
                                    </div>
                                </div>
                                <div id="t<?= (int) $t['no'] ?>" class="collapse <?= $buka ? 'show' : '' ?>">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th style="width:44px" class="text-center"><i class="fas fa-check"></i></th>
                                                        <th>Item Checklist</th>
                                                        <th style="width:120px" class="text-center">Cek Otomatis</th>
                                                        <th style="width:155px">Target Item</th>
                                                        <th style="width:70px" class="text-center">Menu</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($t['items'] as $it) :
                                                        $warnaItem = $warnaPersen($it->_persen);
                                                        $ruteItem  = $it->rute ? $it->rute : $t['rute'];
                                                    ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <?php if ($it->_manual) : ?>
                                                                    <input type="checkbox"
                                                                        class="item-check centang-manual"
                                                                        data-tahun="<?= (int) $tahun ?>"
                                                                        data-upk="<?= (int) $id_upk ?>"
                                                                        data-kode="<?= html_escape($it->kode_item) ?>"
                                                                        data-tahap="<?= (int) $t['no'] ?>"
                                                                        <?= $it->_checked ? 'checked' : '' ?>
                                                                        <?= $boleh_centang ? '' : 'disabled' ?>
                                                                        title="<?= $boleh_centang ? 'Centang bila butir ini benar-benar sudah dikerjakan' : 'Pilih unit terlebih dahulu' ?>">
                                                                <?php else : ?>
                                                                    <input type="checkbox" class="item-check"
                                                                        <?= $it->_checked ? 'checked' : '' ?> disabled
                                                                        title="Dihitung otomatis dari data">
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <strong><?= html_escape($it->judul) ?></strong>
                                                                <?php if ($it->keterangan) : ?>
                                                                    <div class="mini text-muted"><?= html_escape($it->keterangan) ?></div>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php if ($it->auto_metric !== null && $it->auto_metric !== '') : ?>
                                                                    <span class="badge badge-<?= $warnaItem ?>"><?= (int) $it->_num ?>/<?= (int) $it->_den ?></span>
                                                                    <div class="mini text-muted">otomatis</div>
                                                                <?php else : ?>
                                                                    <span class="badge badge-light border">manual</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $pakaiItem = $it->_target && ($id_upk === 0 || $it->_target_scope === 'unit');
                                                                $nilaiItem = $pakaiItem ? $it->_target->target_selesai : '';
                                                                ?>
                                                                <input type="date" class="form-control form-control-sm"
                                                                    name="item[<?= html_escape($it->kode_item) ?>][selesai]"
                                                                    value="<?= $nilaiItem ? html_escape($nilaiItem) : '' ?>">
                                                                <?php if ($id_upk > 0 && $it->_target && $it->_target_scope === 'global' && $it->_target->target_selesai) : ?>
                                                                    <div class="mini text-muted mt-1">global: <?= $tglIndo($it->_target->target_selesai) ?></div>
                                                                <?php endif; ?>
                                                                <?php if ($it->_target_scope === 'unit') : ?>
                                                                    <div class="mini text-info mt-1">override unit</div>
                                                                <?php elseif ($it->_target_scope === 'global' && $id_upk > 0) : ?>
                                                                    <div class="mini text-muted mt-1">mengikuti global</div>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="<?= base_url($ruteItem) ?>?tahun=<?= (int) $tahun ?>"
                                                                    class="btn btn-sm btn-outline-primary py-0" title="Buka menu terkait">
                                                                    <i class="fas fa-arrow-right"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="card-footer py-2 d-flex justify-content-between flex-wrap">
                    <small class="text-muted">
                        <i class="fas fa-lightbulb"></i>
                        Kolom <strong>Cek Otomatis</strong> dihitung dari data yang sudah ada.
                        Centang manual hanya untuk butir yang tidak bisa dinilai sistem.
                    </small>
                    <div class="legend small text-muted">
                        <span class="dot" style="background:#28a745"></span>Selesai
                        <span class="dot ml-2" style="background:#ffc107"></span>Sebagian
                        <span class="dot ml-2" style="background:#6c757d"></span>Belum
                    </div>
                </div>
            </div>
        </form>

        <?php if ($id_upk > 0) : ?>
            <form id="formHapusOverride" method="post" action="<?= base_url('manrisk/checklist/hapus_override') ?>">
                <input type="hidden" name="tahun" value="<?= (int) $tahun ?>">
                <input type="hidden" name="id_upk" value="<?= (int) $id_upk ?>">
            </form>
        <?php endif; ?>

    </div>
</section>

<style>
    .mri-checklist .stat {
        border-radius: 8px;
        background: #fff;
        border: 1px solid #e3e6ea;
        padding: 12px 14px;
        height: 100%;
    }
    .mri-checklist .stat .lbl {
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #6c757d;
    }
    .mri-checklist .stat .val {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1.1;
    }
    .mri-checklist .stat .sub {
        font-size: .72rem;
        color: #6c757d;
    }
    .mri-checklist .tahap-head {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        cursor: pointer;
    }
    .mri-checklist .tahap-head .judul {
        font-weight: 700;
        margin: 0;
    }
    .mri-checklist .progress {
        height: 6px;
        background: #e9ecef;
    }
    .mri-checklist .item-check {
        width: 18px;
        height: 18px;
        margin-top: 2px;
    }
    .mri-checklist .table-sm td,
    .mri-checklist .table-sm th {
        vertical-align: middle;
    }
    .mri-checklist .mini {
        font-size: .72rem;
    }
    .mri-checklist .tag-target {
        background: #eef5ff;
        border: 1px dashed #8ab6e6;
        border-radius: 5px;
        padding: 2px 7px;
        font-size: .72rem;
        white-space: nowrap;
    }
    .mri-checklist .accordion .card {
        box-shadow: none;
    }
    .mri-checklist .legend span.dot {
        display: inline-block;
        width: 9px;
        height: 9px;
        border-radius: 50%;
        margin-right: 4px;
    }
    @media print {
        .main-sidebar, .main-header, .main-footer, .no-print, .btn, .content-header {
            display: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
        }
        .mri-checklist .card {
            break-inside: avoid;
        }
    }
</style>

<script>
    (function () {
        'use strict';

        var urlCentang = <?= json_encode(base_url('manrisk/checklist/centang')) ?>;

        // Buka accordion tahap yang dituju setelah reload dari centang manual.
        document.addEventListener('DOMContentLoaded', function () {
            if (!window.location.hash) {
                return;
            }
            var target = document.querySelector(window.location.hash);
            if (target && target.classList.contains('collapse') && window.jQuery && jQuery.fn.collapse) {
                jQuery(target).collapse('show');
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });

        document.addEventListener('change', function (e) {
            var el = e.target;
            if (!el || !el.classList || !el.classList.contains('centang-manual')) {
                return;
            }

            var body = new URLSearchParams();
            body.append('tahun', el.getAttribute('data-tahun') || '');
            body.append('id_upk', el.getAttribute('data-upk') || '');
            body.append('kode_item', el.getAttribute('data-kode') || '');
            body.append('selesai', el.checked ? '1' : '0');

            el.disabled = true;

            fetch(urlCentang, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: body.toString(),
                credentials: 'same-origin'
            })
                .then(function (r) { return r.json(); })
                .then(function (j) {
                    if (!j || !j.ok) {
                        el.checked = !el.checked;
                        el.disabled = false;
                        alert((j && j.pesan) ? j.pesan : 'Gagal menyimpan centang.');
                        return;
                    }
                    var url = new URL(window.location.href);
                    url.hash = 't' + (el.getAttribute('data-tahap') || '');
                    window.location.href = url.toString();
                })
                .catch(function () {
                    el.checked = !el.checked;
                    el.disabled = false;
                    alert('Gagal menyimpan centang. Periksa koneksi lalu coba lagi.');
                });
        });
    })();
</script>
