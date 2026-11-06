<?php
/**
 * Status Manajemen Risiko per UPK.
 * Matriks tahap 0-8 x unit, dihitung dari model checklist.
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

$warnaSel = function ($persen) {
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

$warnaTotal = function ($persen) {
    if ($persen >= 90) {
        return 'success';
    }
    if ($persen >= 10) {
        return 'warning';
    }
    return 'secondary';
};

$ringkasan = $matriks['ringkasan'];
$tahapTampil = $matriks['tahap_tampil'];
?>

<section class="content">
    <div class="container-fluid mri-status">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <!-- ================= JUDUL + FILTER TAHUN ================= -->
        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
            <div class="mb-2">
                <h4 class="mb-0 font-weight-bold">
                    <i class="fas fa-users text-primary mr-1"></i>
                    <?= strtoupper($title) ?>
                </h4>
                <small class="text-muted">
                    Matriks tahap per unit. Klik ikon <i class="fas fa-search"></i> untuk melihat ringkasan unit
                    dan membuka checklist-nya.
                </small>
            </div>
            <div class="mt-1 mt-md-0">
                <form method="get" action="<?= base_url('manrisk/status') ?>" class="form-inline d-inline-flex flex-wrap">
                    <label class="mr-1 mb-1 small text-muted">Tahun</label>
                    <select name="tahun" class="form-control form-control-sm mr-2 mb-1" onchange="this.form.submit()">
                        <?php foreach ($daftar_tahun as $th) : ?>
                            <option value="<?= (int) $th ?>" <?= (int) $th === (int) $tahun ? 'selected' : '' ?>>
                                <?= (int) $th ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="q" value="<?= html_escape($filter['q']) ?>">
                    <input type="hidden" name="status" value="<?= html_escape($filter['status']) ?>">
                    <input type="hidden" name="tampil" value="<?= html_escape($filter['tampil']) ?>">
                    <noscript><button type="submit" class="btn btn-sm btn-outline-secondary mb-1">Terapkan</button></noscript>
                </form>
                <a href="<?= base_url('manrisk/checklist?' . http_build_query(['tahun' => $tahun])) ?>" class="btn btn-primary btn-sm mb-1">
                    <i class="fas fa-clipboard-list"></i> Buka Checklist
                </a>
                <button type="button" class="btn btn-outline-secondary btn-sm mb-1 no-print" onclick="window.print()">
                    <i class="fas fa-print"></i> Cetak
                </button>
            </div>
        </div>

        <!-- ================= RINGKASAN ================= -->
        <div class="row mb-3">
            <div class="col-6 col-md-3 mb-2">
                <div class="stat">
                    <div class="lbl">Unit selesai</div>
                    <div class="val text-success"><?= (int) $ringkasan['selesai'] ?></div>
                    <div class="sub">&ge; 90% butir terpenuhi</div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="stat">
                    <div class="lbl">Dalam proses</div>
                    <div class="val text-warning"><?= (int) $ringkasan['proses'] ?></div>
                    <div class="sub">10% &ndash; 89% butir terpenuhi</div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="stat">
                    <div class="lbl">Belum mulai</div>
                    <div class="val text-secondary"><?= (int) $ringkasan['belum'] ?></div>
                    <div class="sub">&lt; 10% butir terpenuhi</div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="stat">
                    <div class="lbl">Rata-rata progres</div>
                    <div class="val text-primary"><?= (int) $ringkasan['rata'] ?>%</div>
                    <div class="progress mt-2">
                        <div class="progress-bar bg-primary" style="width:<?= (int) $ringkasan['rata'] ?>%"></div>
                    </div>
                    <div class="sub mt-1"><?= (int) $ringkasan['total_unit'] ?> unit ditampilkan</div>
                </div>
            </div>
        </div>

        <!-- ================= FILTER ================= -->
        <div class="card card-outline card-primary mb-3 no-print">
            <div class="card-body py-2">
                <form method="get" action="<?= base_url('manrisk/status') ?>" class="form-inline flex-wrap">
                    <input type="hidden" name="tahun" value="<?= (int) $tahun ?>">

                    <label class="mr-2 mb-2">Cari unit</label>
                    <input type="text" name="q" value="<?= html_escape($filter['q']) ?>"
                        class="form-control form-control-sm mr-4 mb-2" placeholder="nama UPK / bagian" style="width:230px">

                    <label class="mr-2 mb-2">Status</label>
                    <select name="status" class="form-control form-control-sm mr-4 mb-2" style="width:170px" onchange="this.form.submit()">
                        <?php foreach (['Semua status', 'Selesai', 'Dalam proses', 'Belum mulai'] as $s) : ?>
                            <option value="<?= html_escape($s) ?>" <?= $filter['status'] === $s ? 'selected' : '' ?>>
                                <?= html_escape($s) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label class="mr-2 mb-2">Tampilkan tahap</label>
                    <select name="tampil" class="form-control form-control-sm mb-2" style="width:210px" onchange="this.form.submit()">
                        <option value="semua" <?= $filter['tampil'] === 'semua' ? 'selected' : '' ?>>Semua tahap (0&ndash;8)</option>
                        <option value="perencanaan" <?= $filter['tampil'] === 'perencanaan' ? 'selected' : '' ?>>Perencanaan (0&ndash;6)</option>
                        <option value="pelaksanaan" <?= $filter['tampil'] === 'pelaksanaan' ? 'selected' : '' ?>>Pelaksanaan (7&ndash;8)</option>
                    </select>

                    <button type="submit" class="btn btn-outline-primary btn-sm mb-2 ml-2">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="<?= base_url('manrisk/status?tahun=' . (int) $tahun) ?>" class="btn btn-link btn-sm mb-2">Reset</a>
                </form>
                <?php if ($paksa !== null) : ?>
                    <small class="text-muted d-block">
                        <i class="fas fa-lock"></i> Anda hanya dapat melihat unit sendiri.
                    </small>
                <?php endif; ?>
                <small class="text-muted d-block">
                    <i class="fas fa-info-circle"></i>
                    Data organisasi (id_upk 0 / kosong) hanya dihitung untuk unit yang sudah memiliki risiko
                    pada tahun ini. Rekap seluruh organisasi ada pada mode <strong>Semua UPK</strong> di
                    halaman Checklist.
                </small>
            </div>
        </div>

        <!-- ================= MATRIKS ================= -->
        <div class="card card-outline card-info">
            <div class="card-header py-2 d-flex justify-content-between align-items-center flex-wrap">
                <strong><i class="fas fa-table text-info"></i> Matriks Tahap per Unit &mdash; Tahun <?= (int) $tahun ?></strong>
                <small class="text-muted">Angka = terpenuhi / penyebut. Warna = status tahap.</small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0 matrix">
                        <thead>
                            <tr>
                                <th class="unit" style="min-width:210px">Unit / Bagian</th>
                                <?php foreach ($tahapTampil as $t) : ?>
                                    <th class="text-center">
                                        <?= (int) $t['no'] ?>
                                        <div class="font-weight-normal" style="font-size:.65rem;color:#6c757d">
                                            <?= html_escape($t['judul']) ?>
                                        </div>
                                    </th>
                                <?php endforeach; ?>
                                <th style="min-width:86px" class="text-center">Total</th>
                                <th style="min-width:70px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$matriks['unit']) : ?>
                                <tr>
                                    <td colspan="<?= count($tahapTampil) + 3 ?>" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        Tidak ada unit yang sesuai filter.
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <?php foreach ($matriks['unit'] as $u) : ?>
                                <tr>
                                    <td class="unit">
                                        <span class="avatar"><?= html_escape($u['inisial']) ?></span>
                                        <?= html_escape($u['nama']) ?>
                                    </td>

                                    <?php foreach ($tahapTampil as $t) :
                                        $no = (int) $t['no'];
                                        $c  = isset($u['tahap'][$no]) ? $u['tahap'][$no] : ['num' => 0, 'den' => 0, 'persen' => 0, 'item_selesai' => 0, 'item_total' => 0];
                                        $w  = $warnaSel((int) $c['persen']);
                                    ?>
                                        <td class="text-center">
                                            <div class="cell-prog">
                                                <span class="num text-<?= $w ?>">
                                                    <?= (int) $c['num'] ?>/<?= (int) $c['den'] ?>
                                                </span>
                                                <span class="pct"><?= (int) $c['persen'] ?>%</span>
                                                <div class="progress">
                                                    <div class="progress-bar bg-<?= $w ?>" style="width:<?= (int) $c['persen'] ?>%"></div>
                                                </div>
                                                <span class="mini text-muted"><?= (int) $c['item_selesai'] ?>/<?= (int) $c['item_total'] ?> butir</span>
                                            </div>
                                        </td>
                                    <?php endforeach; ?>

                                    <td class="total text-center">
                                        <span class="badge badge-<?= $warnaTotal((int) $u['persen']) ?>"><?= (int) $u['persen'] ?>%</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary py-0 btn-detail"
                                            title="Lihat detail unit"
                                            data-nama="<?= html_escape($u['nama']) ?>"
                                            data-persen="<?= (int) $u['persen'] ?>"
                                            data-risiko="<?= (int) $u['risiko'] ?>"
                                            data-evaluasi="<?= (int) $u['evaluasi'] ?>"
                                            data-perlakuan="<?= (int) $u['perlakuan'] ?>"
                                            data-item-selesai="<?= (int) $u['item_selesai'] ?>"
                                            data-item-total="<?= (int) $u['item_total'] ?>"
                                            data-num="<?= (int) $u['num'] ?>"
                                            data-den="<?= (int) $u['den'] ?>"
                                            data-checklist="<?= base_url('manrisk/checklist?' . http_build_query(['tahun' => $tahun, 'id_upk' => $u['id_upk']])) ?>">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="thead-light">
                            <tr>
                                <th class="unit">Total / Rata-rata</th>
                                <?php foreach ($tahapTampil as $t) :
                                    $no  = (int) $t['no'];
                                    $tot = isset($matriks['total_tahap'][$no]) ? $matriks['total_tahap'][$no] : ['num' => 0, 'den' => 0, 'persen' => 0];
                                ?>
                                    <th class="text-center">
                                        <?= (int) $tot['num'] ?>/<?= (int) $tot['den'] ?>
                                        <div class="mini font-weight-normal"><?= (int) $tot['persen'] ?>%</div>
                                    </th>
                                <?php endforeach; ?>
                                <th class="text-center">
                                    <span class="badge badge-<?= $warnaTotal((int) $matriks['persen']) ?>"><?= (int) $matriks['persen'] ?>%</span>
                                </th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer py-2 d-flex justify-content-between flex-wrap align-items-center">
                <div class="legend small text-muted">
                    <span class="dot" style="background:#28a745"></span>Selesai (100%)
                    <span class="dot ml-2" style="background:#ffc107"></span>Sebagian (50&ndash;99%)
                    <span class="dot ml-2" style="background:#17a2b8"></span>Berjalan (&lt;50%)
                    <span class="dot ml-2" style="background:#6c757d"></span>Belum (0%)
                </div>
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i>
                    Tahap 0 = pengaturan &amp; sasaran, tahap 1&ndash;8 mengikuti alur ISO 31000.
                </small>
            </div>
        </div>

        <!-- ================= DETAIL UNIT ================= -->
        <div class="card card-outline card-secondary" id="cardDetail" style="display:none">
            <div class="card-header py-2 d-flex justify-content-between align-items-center flex-wrap">
                <strong>
                    <i class="fas fa-user-check text-secondary"></i>
                    Detail Unit &mdash; <span id="detailNama">-</span>
                </strong>
                <div>
                    <span class="badge badge-warning ml-2" id="detailPersen">0%</span>
                    <a href="#" id="detailChecklist" class="btn btn-sm btn-outline-primary py-0 ml-2">
                        <i class="fas fa-clipboard-list"></i> Buka Checklist
                    </a>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <small class="text-muted d-block">Risiko terdaftar</small>
                        <strong id="detailRisiko">0 risiko</strong>
                    </div>
                    <div class="col-md-3 mb-2">
                        <small class="text-muted d-block">Sudah dievaluasi</small>
                        <strong id="detailEvaluasi">0 risiko</strong>
                    </div>
                    <div class="col-md-3 mb-2">
                        <small class="text-muted d-block">Rencana perlakuan</small>
                        <strong id="detailPerlakuan">0 rencana</strong>
                    </div>
                    <div class="col-md-3 mb-2">
                        <small class="text-muted d-block">Butir checklist</small>
                        <strong id="detailButir">0 / 0 butir</strong>
                    </div>
                </div>
                <div class="progress mt-2" style="height:8px">
                    <div class="progress-bar bg-warning" id="detailBar" style="width:0%"></div>
                </div>
                <small class="text-muted d-block mt-1" id="detailKeterangan">0 dari 0 data terpenuhi.</small>
            </div>
        </div>

    </div>
</section>

<style>
    .mri-status .stat {
        border-radius: 8px;
        background: #fff;
        border: 1px solid #e3e6ea;
        padding: 12px 16px;
        height: 100%;
    }
    .mri-status .stat .lbl {
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #6c757d;
    }
    .mri-status .stat .val {
        font-size: 1.6rem;
        font-weight: 700;
        line-height: 1.1;
    }
    .mri-status .stat .sub {
        font-size: .72rem;
        color: #6c757d;
    }
    .mri-status .matrix th,
    .mri-status .matrix td {
        vertical-align: middle;
        white-space: nowrap;
    }
    .mri-status .matrix thead th {
        font-size: .72rem;
        text-align: center;
        background: #f4f6f9;
    }
    .mri-status .matrix thead th.unit {
        text-align: left;
    }
    .mri-status .matrix tbody td {
        font-size: .78rem;
    }
    .mri-status .matrix tbody tr:hover {
        background: #f8fbff;
    }
    .mri-status .matrix td.unit,
    .mri-status .matrix th.unit {
        font-weight: 600;
        position: sticky;
        left: 0;
        background: #fff;
        z-index: 2;
    }
    .mri-status .matrix tbody tr:hover td.unit {
        background: #f8fbff;
    }
    .mri-status .matrix tfoot th.unit {
        background: #f4f6f9;
    }
    .mri-status .cell-prog {
        min-width: 74px;
    }
    .mri-status .cell-prog .progress {
        height: 4px;
        margin-top: 3px;
        background: #e9ecef;
    }
    .mri-status .cell-prog .num {
        font-weight: 600;
    }
    .mri-status .cell-prog .pct {
        font-size: .68rem;
        color: #6c757d;
    }
    .mri-status .mini {
        font-size: .68rem;
    }
    .mri-status .total {
        font-weight: 700;
    }
    .mri-status .legend span.dot {
        display: inline-block;
        width: 9px;
        height: 9px;
        border-radius: 50%;
        margin-right: 4px;
    }
    .mri-status .avatar {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #e8f0fb;
        color: #2266aa;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .68rem;
        font-weight: 700;
        margin-right: 6px;
    }
    @media print {
        .main-sidebar, .main-header, .main-footer, .no-print, .btn, .content-header {
            display: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
        }
        .mri-status .card {
            break-inside: avoid;
        }
    }
</style>

<script>
    (function () {
        'use strict';

        document.addEventListener('click', function (e) {
            var btn = e.target.closest ? e.target.closest('.btn-detail') : null;
            if (!btn) {
                return;
            }

            var d = btn.dataset;
            var persen = parseInt(d.persen || '0', 10);
            var selesai = parseInt(d.itemSelesai || '0', 10);
            var total = parseInt(d.itemTotal || '0', 10);
            var num = parseInt(d.num || '0', 10);
            var den = parseInt(d.den || '0', 10);

            document.getElementById('detailNama').textContent = d.nama || '-';
            document.getElementById('detailPersen').textContent = persen + '%';
            document.getElementById('detailRisiko').textContent = (d.risiko || '0') + ' risiko';
            document.getElementById('detailEvaluasi').textContent = (d.evaluasi || '0') + ' risiko';
            document.getElementById('detailPerlakuan').textContent = (d.perlakuan || '0') + ' rencana';
            document.getElementById('detailButir').textContent = selesai + ' / ' + total + ' butir';
            document.getElementById('detailKeterangan').textContent = num + ' dari ' + den + ' data terpenuhi (' + persen + '%).';

            var bar = document.getElementById('detailBar');
            bar.style.width = persen + '%';
            bar.className = 'progress-bar ' + (persen >= 90 ? 'bg-success' : (persen >= 10 ? 'bg-warning' : 'bg-secondary'));

            document.getElementById('detailChecklist').setAttribute('href', d.checklist || '#');

            var card = document.getElementById('cardDetail');
            card.style.display = '';
            card.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    })();
</script>
