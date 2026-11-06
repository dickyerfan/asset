<?php

/**
 * Risk Assessment — rekap 14 kolom contoh CRMO Perpamsi halaman 37,
 * ditambah kolom Keputusan dari tahap Evaluasi.
 *
 * Halaman hanya-baca. Seluruh isi berasal dari Register, Analisis,
 * Evaluasi, dan Perlakuan; tidak ada penyimpanan baru di sini.
 */
$warnaLevel = [];
foreach ($tingkat as $t) {
    $warnaLevel[$t->nama_tingkat] = $t->warna;
}

$badge = function ($level) use ($warnaLevel) {
    if ($level === null || $level === '') {
        return '<span class="badge badge-secondary">belum</span>';
    }
    $w = isset($warnaLevel[$level]) ? $warnaLevel[$level] : '#6c757d';
    return '<span class="badge" style="background-color:' . $w . ';color:#fff">' . html_escape($level) . '</span>';
};

$badgeKeputusan = function ($k) {
    $peta = [
        'Accept'   => 'badge-success',
        'Monitor'  => 'badge-info',
        'Treat'    => 'badge-warning',
        'Escalate' => 'badge-danger',
    ];
    if ($k === null || $k === '') {
        return '<span class="text-muted">&mdash;</span>';
    }
    $kelas = isset($peta[$k]) ? $peta[$k] : 'badge-secondary';
    return '<span class="badge ' . $kelas . '">' . html_escape($k) . '</span>';
};

$barisTeks = function ($teks) {
    $teks = trim((string) $teks);
    if ($teks === '') {
        return '<span class="text-muted">&mdash;</span>';
    }
    return str_replace('; ', '<br>', html_escape($teks));
};

$rp = function ($angka) {
    if ($angka === null || $angka === '') {
        return '<span class="text-muted">&mdash;</span>';
    }
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
};

$bersih = array_filter($filter, function ($v) {
    return $v !== '' && $v !== null;
});
$qs = http_build_query($bersih);
$cetakUrl = base_url('manrisk/assessment/cetak') . ($qs !== '' ? '?' . $qs : '');
$eksporUrl = base_url('manrisk/assessment/ekspor') . ($qs !== '' ? '?' . $qs : '');
?>
<section class="content">
    <div class="container-fluid">

        <!-- ============== FILTER ============== -->
        <div class="card card-outline card-primary">
            <div class="card-header">
                <form method="get" action="<?= base_url('manrisk/assessment') ?>" class="form-inline">
                    <?php if ($akses_semua) : ?>
                        <div class="form-group mr-2 mb-2">
                            <label for="f_upk" class="mr-2">Unit</label>
                            <select name="id_upk" id="f_upk" class="form-control">
                                <option value="">Semua Unit</option>
                                <?php foreach ($unit_list as $u) : ?>
                                    <option value="<?= (int) $u->id_bagian ?>" <?= isset($filter['id_upk']) && (string) $filter['id_upk'] === (string) $u->id_bagian ? 'selected' : '' ?>>
                                        <?= html_escape($u->nama_bagian) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="form-group mr-2 mb-2">
                        <label for="f_level" class="mr-2">Tingkat</label>
                        <select name="level" id="f_level" class="form-control">
                            <option value="">Semua</option>
                            <?php foreach ($tingkat as $t) : ?>
                                <option value="<?= html_escape($t->nama_tingkat) ?>" <?= isset($filter['level']) && $filter['level'] === $t->nama_tingkat ? 'selected' : '' ?>>
                                    <?= html_escape($t->nama_tingkat) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mr-2 mb-2">
                        <label for="f_q" class="mr-2">Cari</label>
                        <input type="text" name="q" id="f_q" class="form-control" placeholder="kode / risiko / KPI / penyebab" value="<?= html_escape(isset($filter['q']) ? $filter['q'] : '') ?>">
                    </div>

                    <div class="form-group mr-2 mb-2">
                        <label for="f_thn" class="mr-2">Tahun</label>
                        <input type="number" name="tahun" id="f_thn" class="form-control" style="width:100px" value="<?= (int) $tahun ?>">
                    </div>

                    <button type="submit" class="neumorphic-button mb-2">
                        <i class="fas fa-filter"></i> Tampilkan
                    </button>
                    <a href="<?= base_url('manrisk/assessment') ?>" class="neumorphic-button mb-2 ml-2">
                        <i class="fas fa-undo"></i> Reset
                    </a>
                </form>
            </div>
        </div>

        <!-- ============== JUDUL + AKSI ============== -->
        <div class="row align-items-center mb-2">
            <div class="col-md-8">
                <h4 class="mb-0"><?= strtoupper($title) ?> <?= (int) $tahun ?></h4>
                <small class="text-muted">
                    <?= count($daftar) ?> risiko &middot; <?= html_escape($nama_unit) ?>
                    <!-- &middot; total biaya mitigasi <strong><?= $rp($total_biaya) ?></strong> -->
                </small>
                <!-- <small class="text-muted">
                    <?= count($daftar) ?> risiko &middot; <?= html_escape($nama_unit) ?>
                    &middot; total biaya mitigasi <strong><?= $rp($total_biaya) ?></strong>
                </small> -->
            </div>
            <div class="col-md-4 text-md-right">
                <a href="<?= $cetakUrl ?>" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-print"></i> Cetak / PDF
                </a>
                <a href="<?= $eksporUrl ?>" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-file-csv"></i> Ekspor Excel
                </a>
            </div>
        </div>

        <?php if ($tanpa_unit) : ?>
            <div class="row mb-2">
                <div class="col-12">
                    <div class="border rounded p-3" style="border-color:#ffc107 !important;background:#fff8e1">
                        <strong><i class="fas fa-exclamation-triangle"></i> Akun belum terhubung ke unit.</strong>
                        <p class="mb-0 mt-1 small">
                            Hubungi Administrator agar akun Anda dipetakan ke unit, sehingga data assessment
                            yang tampil sesuai kewenangan.
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- ============== KETERANGAN ============== -->
        <div class="row mb-2">
            <div class="col-12">
                <div class="border rounded p-2 small" style="border-color:#dee2e6 !important;background:#f8f9fa">
                    <i class="fas fa-info-circle text-primary"></i>
                    Tabel ini merangkum <strong>Identifikasi &rarr; Analisis &rarr; Evaluasi &rarr; Perlakuan</strong>
                    dalam satu tampilan
                    <strong>(ubah data melalui menu tahapannya)</strong>;
                </div>
            </div>
        </div>

        <!-- ============== TABEL ============== -->
        <div class="card card-outline card-primary">
            <div class="card-body">
                <table class="table table-bordered table-sm table-hover" id="tabel_assessment" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="min-width:38px">No</th>
                            <th style="min-width:105px">No. ID Risiko</th>
                            <th style="min-width:170px">Sasaran / KPI</th>
                            <th style="min-width:180px">Risiko</th>
                            <th style="min-width:150px">Sumber</th>
                            <th style="min-width:160px">Penyebab</th>
                            <th style="min-width:170px">Dampak</th>
                            <th style="min-width:210px">Rencana Pengendalian &amp; Penanganan</th>
                            <th style="min-width:150px">Deskripsi Biaya Mitigasi</th>
                            <th style="min-width:130px">PIC</th>
                            <th class="text-center" style="min-width:36px">K</th>
                            <th class="text-center" style="min-width:36px">D</th>
                            <th class="text-center" style="min-width:48px">Nilai</th>
                            <th class="text-center" style="min-width:95px">Tingkat Risiko</th>
                            <th class="text-center" style="min-width:90px">Keputusan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$daftar) : ?>
                            <tr>
                                <td colspan="15" class="text-center text-muted py-4">
                                    Tidak ada risiko yang cocok dengan filter.
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php $no = 0;
                        foreach ($daftar as $r) : $no++; ?>
                            <tr>
                                <td class="text-center"><?= $no ?></td>
                                <td class="text-nowrap">
                                    <a href="<?= base_url('manrisk/register/detail/' . (int) $r->id_register) ?>">
                                        <strong><?= html_escape($r->kode_risiko) ?></strong>
                                    </a>
                                </td>
                                <td>
                                    <?= $r->sasaran_kpi ? html_escape($r->sasaran_kpi) : '<span class="text-muted">&mdash;</span>' ?>
                                    <?php if ($r->objektif !== '') : ?>
                                        <br><small class="text-muted">Objektif: <?= $barisTeks($r->objektif) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= html_escape($r->risiko) ?></td>
                                <td><small><?= $barisTeks($r->sumber_risiko) ?></small></td>
                                <td><small><?= $barisTeks($r->penyebab) ?></small></td>
                                <td><small><?= $barisTeks($r->dampak) ?></small></td>
                                <td>
                                    <?php if (trim((string) $r->kontrol_uraian) !== '') : ?>
                                        <small class="text-muted d-block">Kontrol saat ini:</small>
                                        <small><?= $barisTeks($r->kontrol_uraian) ?></small>
                                    <?php endif; ?>
                                    <?php if (trim((string) $r->uraian_tindakan) !== '') : ?>
                                        <small class="text-muted d-block <?= trim((string) $r->kontrol_uraian) !== '' ? 'mt-1' : '' ?>">Rencana:</small>
                                        <small><?= $barisTeks($r->uraian_tindakan) ?></small>
                                    <?php endif; ?>
                                    <?php if (trim((string) $r->kontrol_uraian) === '' && trim((string) $r->uraian_tindakan) === '') : ?>
                                        <span class="text-muted">&mdash;</span>
                                    <?php endif; ?>
                                </td>
                                <td><small><?= $rp($r->anggaran_biaya) ?></small></td>
                                <td>
                                    <?php if ($r->nama_pic) : ?>
                                        <?= html_escape($r->nama_pic) ?>
                                        <?php if ($r->jabatan_pic) : ?>
                                            <br><small class="text-muted"><?= html_escape($r->jabatan_pic) ?></small>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <span class="text-muted">&mdash;</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= $r->inherent_l !== null ? (int) $r->inherent_l : '<span class="text-muted">&mdash;</span>' ?></td>
                                <td class="text-center"><?= $r->inherent_d !== null ? (int) $r->inherent_d : '<span class="text-muted">&mdash;</span>' ?></td>
                                <td class="text-center"><strong><?= $r->nilai_inherent !== null ? (int) $r->nilai_inherent : '<span class="text-muted">&mdash;</span>' ?></strong></td>
                                <td class="text-center"><?= $badge($r->level_inherent) ?></td>
                                <td class="text-center"><?= $badgeKeputusan($r->keputusan) ?></td>
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
    document.addEventListener('DOMContentLoaded', function() {
        $('#tabel_assessment').DataTable({
            scrollX: true,
            autoWidth: false,
            pageLength: 10,
            order: [
                [0, 'asc']
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_",
                infoEmpty: "Belum ada data",
                infoFiltered: "(disaring dari _MAX_)",
                zeroRecords: "Tidak ada data yang cocok",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "&raquo;",
                    previous: "&laquo;"
                }
            }
        });
    });
</script>