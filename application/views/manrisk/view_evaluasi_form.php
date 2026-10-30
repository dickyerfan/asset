<?php
/**
 * Formulir evaluasi risiko (ISO 31000 Klausa 6.6).
 */
$e = $evaluasi;
$isEdit = $e !== null;

$warna = [];
foreach ($tingkat as $t) {
    $warna[$t->nama_tingkat] = $t->warna;
}

$warnaKeputusan = [
    'Accept'  => 'secondary',
    'Monitor' => 'info',
    'Treat'   => 'warning',
    'Escalate' => 'danger',
];

$val = function ($k, $default = '') use ($e) {
    if ($e && isset($e->{$k}) && $e->{$k} !== null) {
        return set_value($k, $e->{$k});
    }
    return set_value($k, $default);
};

$katJson = [];
foreach ($tingkat as $t) {
    $katJson[] = ['nama' => $t->nama_tingkat, 'min' => (int) $t->skor_min, 'max' => (int) $t->skor_max, 'warna' => $t->warna];
}
?>
<section class="content">
    <div class="container-fluid">

        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-balance-scale"></i> <?= strtoupper(html_escape($title)) ?></h5>
                <a href="<?= base_url('manrisk/register/detail/' . $risiko->id_register) ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <?= form_open('manrisk/evaluasi/form/' . $risiko->id_register) ?>

            <?php if (validation_errors()) : ?>
                <div class="card-body pb-0"><div class="alert alert-danger mb-0"><?= validation_errors() ?></div></div>
            <?php endif; ?>

            <div class="card-body">

                <!-- ============ KONTEKS ============ -->
                <div class="row">
                    <div class="col-md-5">
                        <div class="border rounded p-3 h-100">
                            <h6 class="text-muted">Hasil Analisa (Residual)</h6>
                            <h2 class="mb-1">
                                <?= (int) $analisa->residual_l ?> &times; <?= (int) $analisa->residual_d ?>
                                = <strong><?= (int) $analisa->nilai_residual ?></strong>
                            </h2>
                            <span class="badge" style="background-color:<?= isset($warna[$analisa->level_residual]) ? $warna[$analisa->level_residual] : '#6c757d' ?>;color:#fff">
                                <?= html_escape($analisa->level_residual) ?>
                            </span>
                            <p class="mb-0 mt-2"><small><?= html_escape($risiko->kode_risiko) ?> — <?= html_escape($risiko->risiko) ?></small></p>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="border rounded p-3 h-100">
                            <h6 class="text-muted">Risk Appetite Kategori</h6>
                            <?php if (!$appetite) : ?>
                                <p class="text-muted mb-0"><small>Belum ada risk appetite yang ditetapkan untuk tahun ini.</small></p>
                            <?php else : ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead class="thead-light">
                                            <tr><th>Kategori</th><th>Appetite</th><th>Toleransi</th><th>Batas Eskalasi</th></tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($appetite as $ap) : ?>
                                                <tr>
                                                    <td><small><?= html_escape($ap->nama_kat) ?></small></td>
                                                    <td><small><?= html_escape($ap->risk_appetite ?: '-') ?></small></td>
                                                    <td><small><?= html_escape($ap->risk_tolerance ?: '-') ?></small></td>
                                                    <td><small><?= html_escape($ap->batas_eskalasi ?: '-') ?></small></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- ============ PENILAIAN ============ -->
                <h6 class="text-primary border-bottom pb-1 mb-3 mt-4">
                    <i class="fas fa-gavel"></i> Penilaian &amp; Keputusan
                </h6>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="nilai_evaluasi">Nilai Evaluasi <span class="text-danger">*</span> <small>(1&ndash;25)</small></label>
                        <input type="number" name="nilai_evaluasi" id="nilai_evaluasi" class="form-control"
                               min="1" max="25" required
                               value="<?= $val('nilai_evaluasi', $analisa->nilai_residual) ?>">
                        <div id="badge_eval" class="mt-2"></div>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="keputusan">Keputusan <span class="text-danger">*</span></label>
                        <select name="keputusan" id="keputusan" class="form-control" required>
                            <?php foreach (['Accept', 'Monitor', 'Treat', 'Escalate'] as $k) : ?>
                                <option value="<?= $k ?>" <?= $val('keputusan', 'Monitor') === $k ? 'selected' : '' ?>><?= $k ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="triwulan">Triwulan <span class="text-danger">*</span></label>
                        <select name="triwulan" id="triwulan" class="form-control" required>
                            <?php for ($t = 1; $t <= 4; $t++) : ?>
                                <option value="<?= $t ?>" <?= (string) $val('triwulan', (int) ceil((int) date('n') / 3)) === (string) $t ? 'selected' : '' ?>>TW<?= $t ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="tanggal_evaluasi">Tanggal</label>
                        <input type="date" name="tanggal_evaluasi" id="tanggal_evaluasi" class="form-control"
                               value="<?= html_escape($val('tanggal_evaluasi', date('Y-m-d'))) ?>">
                    </div>

                    <div class="form-group col-md-2">
                        <label for="kriteria_rujukan">Kriteria Rujukan</label>
                        <input type="text" name="kriteria_rujukan" id="kriteria_rujukan" class="form-control"
                               value="<?= html_escape($val('kriteria_rujukan', 'mri_tingkat_risiko + mri_appetite')) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="alasan_evaluasi">Alasan / Dasar Penilaian <span class="text-danger">*</span></label>
                    <textarea name="alasan_evaluasi" id="alasan_evaluasi" rows="3" class="form-control" required
                              placeholder="Mengapa skor dan keputusan tersebut dipilih"><?= html_escape($val('alasan_evaluasi')) ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" id="perlu_eskalasi" name="perlu_eskalasi" value="YA"
                                   class="custom-control-input" <?= $val('perlu_eskalasi') === 'YA' ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="perlu_eskalasi"><strong>Perlu Eskalasi</strong></label>
                        </div>
                    </div>
                    <div class="form-group col-md-9">
                        <label for="eskalasi_ke">Eskalasi ke (unit / pejabat)</label>
                        <input type="text" name="eskalasi_ke" id="eskalasi_ke" class="form-control"
                               placeholder="mis. Direksi, Komite Audit, Manajer UPK"
                               value="<?= html_escape($val('eskalasi_ke')) ?>">
                    </div>
                </div>

                <!-- ============ LEGENDA ============ -->
                <div class="card card-outline card-secondary mb-0">
                    <div class="card-header"><strong>Skala Level</strong></div>
                    <div class="card-body py-2">
                        <div>
                            <?php foreach ($tingkat as $t) : ?>
                                <span class="badge mr-1" style="background-color:<?= $t->warna ?>;color:#fff">
                                    <?= html_escape($t->nama_tingkat) ?> <?= (int) $t->skor_min ?>&ndash;<?= (int) $t->skor_max ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                        <?php if ($riwayat) : ?>
                            <hr class="my-2">
                            <strong class="small">Riwayat evaluasi terdahulu:</strong>
                            <ul class="list-unstyled small mb-0">
                                <?php foreach ($riwayat as $h) : ?>
                                    <li>
                                        TW<?= (int) $h->triwulan ?> &middot; <?= html_escape($h->tanggal_evaluasi) ?>
                                        &middot; skor <?= (int) $h->nilai_evaluasi ?>
                                        &middot; <span class="badge badge-<?= isset($warnaKeputusan[$h->keputusan]) ? $warnaKeputusan[$h->keputusan] : 'secondary' ?>"><?= html_escape($h->keputusan) ?></span>
                                        &middot; oleh <?= html_escape($h->oleh) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Evaluasi</button>
                <a href="<?= base_url('manrisk/register/detail/' . $risiko->id_register) ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>

            <?= form_close() ?>
        </div>

    </div>
</section>
</div>

<script>
var KAT = <?= json_encode($katJson, JSON_UNESCAPED_UNICODE) ?>;

function gambarBadge() {
    var v = parseInt(document.getElementById('nilai_evaluasi').value, 10);
    var el = document.getElementById('badge_eval');
    if (!v || v < 1) { el.innerHTML = ''; return; }
    for (var i = 0; i < KAT.length; i++) {
        if (v >= KAT[i].min && v <= KAT[i].max) {
            el.innerHTML = '<span class="badge badge-pill px-3 py-1" style="background-color:' +
                KAT[i].warna + ';color:#fff">' + KAT[i].nama + ' (' + KAT[i].min + '-' + KAT[i].max + ')</span>';
            return;
        }
    }
    el.innerHTML = '';
}
document.getElementById('nilai_evaluasi').addEventListener('input', gambarBadge);
gambarBadge();
</script>
