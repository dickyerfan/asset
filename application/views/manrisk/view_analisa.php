<?php
/**
 * Daftar status analisis L x D seluruh risiko pada tahun berjalan.
 */
$warna = [];
foreach ($tingkat as $t) {
    $warna[$t->nama_tingkat] = $t->warna;
}
$skalaTeks = [];
foreach ($tingkat as $t) {
    $skalaTeks[] = $t->nama_tingkat . ' ' . $t->skor_min . '-' . $t->skor_max;
}
$skalaTeks = implode(', ', $skalaTeks);
$badge = function ($level) use ($warna) {
    if ($level === null || $level === '') {
        return '<span class="badge badge-secondary">belum</span>';
    }
    $w = isset($warna[$level]) ? $warna[$level] : '#6c757d';
    return '<span class="badge" style="background-color:' . $w . ';color:#fff">' . html_escape($level) . '</span>';
};
$total = count($daftar);
$belum = $total - $teranalisa;
$persen = $total ? round($teranalisa * 100 / $total) : 0;
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <!-- ============== RINGKASAN ============== -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner"><h3><?= $total ?></h3><p>Total Risiko</p></div>
                    <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner"><h3><?= $teranalisa ?></h3><p>Sudah Dianalisis</p></div>
                    <div class="icon"><i class="fas fa-check-double"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner"><h3><?= $belum ?></h3><p>Belum Dianalisis</p></div>
                    <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner"><h3><?= $persen ?>%</h3><p>Kelengkapan Analisa</p></div>
                    <div class="icon"><i class="fas fa-chart-line"></i></div>
                </div>
            </div>
        </div>

        <!-- ============== FILTER ============== -->
        <div class="card card-outline card-primary">
            <div class="card-header">
                <form method="get" action="<?= base_url('manrisk/analisa') ?>" class="form-inline">
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

                    <div class="form-group mr-2 mb-2">
                        <label for="f_sts" class="mr-2">Status</label>
                        <select name="status_risiko" id="f_sts" class="form-control">
                            <option value="">Semua</option>
                            <?php foreach (['AKTIF', 'SELESAI', 'NONAKTIF'] as $s) : ?>
                                <option value="<?= $s ?>" <?= isset($filter['status_risiko']) && $filter['status_risiko'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mr-2 mb-2">
                        <input type="text" name="q" class="form-control" placeholder="cari kode / risiko"
                               value="<?= html_escape(isset($filter['q']) ? $filter['q'] : '') ?>">
                    </div>

                    <button type="submit" class="neumorphic-button mb-2"><i class="fas fa-filter"></i> Tampilkan</button>
                    <a href="<?= base_url('manrisk/analisa') ?>" class="neumorphic-button mb-2 ml-2"><i class="fas fa-undo"></i> Reset</a>

                    <?php if ($akses_semua && $boleh_tulis) : ?>
                        <a href="<?= base_url('manrisk/analisa/periksa') ?>" class="neumorphic-button mb-2 ml-2"
                           onclick="return confirm('Jalankan pemeriksaan invarian L×D pada seluruh analisa?');">
                            <i class="fas fa-clipboard-check"></i> Periksa Invarian
                        </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- ============== JUDUL ============== -->
        <div class="row align-items-center mb-2">
            <div class="col-md-8">
                <h4 class="mb-0"><?= strtoupper($title) ?> <?= $tahun ?></h4>
                <small class="text-muted">
                    Skor <strong>L &times; D</strong> (1&ndash;5) &rarr; 1&ndash;25 &middot;
                    <?= count($tingkat) ?> level: <?= html_escape($skalaTeks) ?>.
                </small>
            </div>
            <div class="col-md-4 text-md-right">
                <a href="<?= base_url('manrisk/register') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-list"></i> Risk Register
                </a>
            </div>
        </div>

        <!-- ============== TABEL ============== -->
        <div class="card card-outline card-primary">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover" id="tabel_analisa" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode</th>
                            <th>Risiko</th>
                            <th>Unit</th>
                            <th class="text-center">Inherent L&times;D</th>
                            <th class="text-center">Skor</th>
                            <th class="text-center">Level</th>
                            <th class="text-center">Residual L&times;D</th>
                            <th class="text-center">Skor</th>
                            <th class="text-center">Level</th>
                            <th class="text-center">Kendali</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$daftar) : ?>
                            <tr><td colspan="11" class="text-center text-muted py-4">Tidak ada risiko sesuai filter.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($daftar as $r) : ?>
                            <tr>
                                <td><a href="<?= base_url('manrisk/register/detail/' . $r->id_register) ?>"><strong><?= html_escape($r->kode_risiko) ?></strong></a></td>
                                <td><?= html_escape($r->risiko) ?></td>
                                <td><small><?= html_escape($r->nama_bagian ?: '-') ?></small></td>
                                <?php if ($r->nilai_inherent !== null) : ?>
                                    <td class="text-center"><?= (int) $r->inherent_l ?> &times; <?= (int) $r->inherent_d ?></td>
                                    <td class="text-center"><strong><?= (int) $r->nilai_inherent ?></strong></td>
                                    <td class="text-center"><?= $badge($r->level_inherent) ?></td>
                                    <td class="text-center"><?= (int) $r->residual_l ?> &times; <?= (int) $r->residual_d ?></td>
                                    <td class="text-center"><strong><?= (int) $r->nilai_residual ?></strong></td>
                                    <td class="text-center"><?= $badge($r->level_residual) ?></td>
                                <?php else : ?>
                                    <td class="text-center" colspan="5">
                                        <span class="text-muted">Belum dianalisis</span>
                                    </td>
                                <?php endif; ?>
                                <td class="text-center">
                                    <span class="badge <?= $r->status_kendali === 'C' ? 'badge-success' : 'badge-warning' ?>">
                                        <?= html_escape($r->status_kendali) ?>
                                    </span>
                                </td>
                                <td class="text-center text-nowrap">
                                    <a href="<?= base_url('manrisk/analisa/isi/' . $r->id_register) ?>"
                                       class="btn btn-sm btn-<?= $r->nilai_inherent === null ? 'primary' : 'outline-secondary' ?>">
                                        <i class="fas fa-<?= $r->nilai_inherent === null ? 'plus' : 'edit' ?>"></i>
                                        <?= $r->nilai_inherent === null ? 'Analisa' : 'Ubah' ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#tabel_analisa').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'asc']],
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
