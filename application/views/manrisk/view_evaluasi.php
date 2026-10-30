<?php
/**
 * Daftar evaluasi risiko + rekap keputusan (ISO 31000 Klausa 6.6).
 */
$warna = [];
foreach ($tingkat as $t) {
    $warna[$t->nama_tingkat] = $t->warna;
}
$badge = function ($level) use ($warna) {
    if ($level === null || $level === '') {
        return '<span class="badge badge-secondary">—</span>';
    }
    $w = isset($warna[$level]) ? $warna[$level] : '#6c757d';
    return '<span class="badge" style="background-color:' . $w . ';color:#fff">' . html_escape($level) . '</span>';
};
$warnaKeputusan = [
    'Accept'  => 'secondary',
    'Monitor' => 'info',
    'Treat'   => 'warning',
    'Escalate' => 'danger',
];
$total = count($daftar);
$terevaluasi = 0;
foreach ($daftar as $r) {
    if (!empty($evals[$r->id_register])) {
        $terevaluasi++;
    }
}
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <!-- ============== REKAP ============== -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner"><h3><?= $terevaluasi ?>/<?= $total ?></h3><p>Sudah Dievaluasi</p></div>
                    <div class="icon"><i class="fas fa-balance-scale"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= isset($rekap['Escalate']) ? $rekap['Escalate'] : 0 ?></h3>
                        <p>Perlu Eskalasi</p>
                    </div>
                    <div class="icon"><i class="fas fa-arrow-up"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= isset($rekap['Treat']) ? $rekap['Treat'] : 0 ?></h3>
                        <p>Perlu Ditangani</p>
                    </div>
                    <div class="icon"><i class="fas fa-tools"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= (isset($rekap['Accept']) ? $rekap['Accept'] : 0) + (isset($rekap['Monitor']) ? $rekap['Monitor'] : 0) ?></h3>
                        <p>Diterima / Dipantau</p>
                    </div>
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>
        </div>

        <!-- ============== FILTER ============== -->
        <div class="card card-outline card-primary">
            <div class="card-header">
                <form method="get" action="<?= base_url('manrisk/evaluasi') ?>" class="form-inline">
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
                        <label for="f_kep" class="mr-2">Keputusan</label>
                        <select name="keputusan" id="f_kep" class="form-control">
                            <option value="">Semua</option>
                            <?php foreach ($keputusan as $k) : ?>
                                <option value="<?= $k ?>" <?= isset($filter['keputusan']) && $filter['keputusan'] === $k ? 'selected' : '' ?>><?= $k ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="neumorphic-button mb-2"><i class="fas fa-filter"></i> Tampilkan</button>
                    <a href="<?= base_url('manrisk/evaluasi') ?>" class="neumorphic-button mb-2 ml-2"><i class="fas fa-undo"></i> Reset</a>
                </form>
            </div>
        </div>

        <div class="row align-items-center mb-2">
            <div class="col-md-8">
                <h4 class="mb-0"><?= strtoupper($title) ?> <?= $tahun ?></h4>
                <small class="text-muted">
                    Perbandingan hasil analisis terhadap <strong>kriteria risiko</strong>
                    (tingkat risiko &amp; risk appetite) &rarr; keputusan Accept / Monitor / Treat / Escalate.
                </small>
            </div>
            <div class="col-md-4 text-md-right">
                <a href="<?= base_url('manrisk/analisa') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-calculator"></i> Analisa
                </a>
                <a href="<?= base_url('manrisk/register') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-list"></i> Register
                </a>
            </div>
        </div>

        <!-- ============== TABEL ============== -->
        <div class="card card-outline card-primary">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover" id="tabel_evaluasi" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode</th>
                            <th>Risiko</th>
                            <th>Unit</th>
                            <th class="text-center">Skor Residual</th>
                            <th class="text-center">Level</th>
                            <th class="text-center">Nilai Evaluasi</th>
                            <th>Level Evaluasi</th>
                            <th class="text-center">Keputusan</th>
                            <th class="text-center">TW</th>
                            <th class="text-center">Eskalasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$daftar) : ?>
                            <tr><td colspan="11" class="text-center text-muted py-4">Tidak ada risiko sesuai filter.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($daftar as $r) :
                            $e = isset($evals[$r->id_register]) ? $evals[$r->id_register] : null;
                        ?>
                            <tr>
                                <td><a href="<?= base_url('manrisk/register/detail/' . $r->id_register) ?>"><strong><?= html_escape($r->kode_risiko) ?></strong></a></td>
                                <td><?= html_escape($r->risiko) ?></td>
                                <td><small><?= html_escape($r->nama_bagian ?: '-') ?></small></td>
                                <td class="text-center">
                                    <?= $r->nilai_residual !== null ? '<strong>' . (int) $r->nilai_residual . '</strong>' : '<span class="text-muted">—</span>' ?>
                                </td>
                                <td class="text-center"><?= $badge($r->level_residual) ?></td>
                                <td class="text-center">
                                    <?= $e ? '<strong>' . (int) $e->nilai_evaluasi . '</strong>' : '<span class="text-muted">—</span>' ?>
                                </td>
                                <td><?= $e ? $badge($e->level_evaluasi) : '<span class="text-muted">belum</span>' ?></td>
                                <td class="text-center">
                                    <?php if ($e) : ?>
                                        <span class="badge badge-<?= isset($warnaKeputusan[$e->keputusan]) ? $warnaKeputusan[$e->keputusan] : 'secondary' ?>">
                                            <?= html_escape($e->keputusan) ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= $e ? 'TW' . (int) $e->triwulan : '—' ?></td>
                                <td class="text-center">
                                    <?php if ($e && $e->perlu_eskalasi) : ?>
                                        <span class="badge badge-danger"><?= html_escape($e->eskalasi_ke ?: 'ya') ?></span>
                                    <?php else : ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('manrisk/evaluasi/form/' . $r->id_register) ?>"
                                       class="btn btn-sm btn-<?= $e ? 'outline-secondary' : 'primary' ?>">
                                        <i class="fas fa-<?= $e ? 'edit' : 'plus' ?>"></i>
                                        <?= $e ? 'Ubah' : 'Evaluasi' ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============== ARTI KEPUTUSAN ============== -->
        <div class="card card-outline card-info">
            <div class="card-header"><strong>Arti Keputusan</strong></div>
            <div class="card-body py-2">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="thead-light">
                        <tr><th style="width:120px">Keputusan</th><th>Arti</th></tr>
                    </thead>
                    <tbody>
                        <tr><td><span class="badge badge-secondary">Accept</span></td>
                            <td><small>Risiko dalam batas appetite — diterima dengan pemantauan rutin.</small></td></tr>
                        <tr><td><span class="badge badge-info">Monitor</span></td>
                            <td><small>Risiko dipantau berkala; bila naik, dinaikkan statusnya.</small></td></tr>
                        <tr><td><span class="badge badge-warning">Treat</span></td>
                            <td><small>Risiko wajib ditangani — susun rencana perlakuan (mitigasi).</small></td></tr>
                        <tr><td><span class="badge badge-danger">Escalate</span></td>
                            <td><small>Melebihi appetite / level tertinggi — eskalasi ke direksi atau unit di atasnya.</small></td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#tabel_evaluasi').DataTable({
        responsive: true, pageLength: 25, order: [[0, 'asc']],
        language: {
            search: "Cari:", lengthMenu: "Tampilkan _MENU_",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_",
            infoEmpty: "Belum ada data", infoFiltered: "(disaring dari _MAX_)",
            zeroRecords: "Tidak ada data yang cocok",
            paginate: {first: "Awal", last: "Akhir", next: "&raquo;", previous: "&laquo;"}
        }
    });
});
</script>
