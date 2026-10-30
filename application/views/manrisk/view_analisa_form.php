<?php
/**
 * Formulir analisis risiko: L x D inherent dan residual (ISO 31000 Klausa 6.4).
 *
 * Level ditentukan dari mri_tingkat_risiko, tidak di-hardcode di JavaScript —
 * peta band disuntikkan sebagai JSON sehingga keduanya tidak pernah berbeda.
 */
$a = $analisa;
$isEdit = $a !== null;

$petaJson = [];
foreach ($tingkat as $t) {
    $petaJson[] = [
        'nama' => $t->nama_tingkat,
        'min'  => (int) $t->skor_min,
        'max'  => (int) $t->skor_max,
        'warna' => $t->warna,
        'aksi' => $t->aksi,
    ];
}

$e = function ($k, $default = '') use ($a) {
    if ($a && isset($a->{$k}) && $a->{$k} !== null) {
        return set_value($k, $a->{$k});
    }
    return set_value($k, $default);
};

$optL = function ($name, $current) {
    $ket = [
        1 => '1 — sangat jarang',
        2 => '2 — jarang',
        3 => '3 — kadang',
        4 => '4 — sering',
        5 => '5 — sangat sering',
    ];
    $html = '<select name="' . $name . '" id="' . $name . '" class="form-control form-control-sm" required>';
    for ($i = 1; $i <= 5; $i++) {
        $sel = ((string) $current === (string) $i) ? ' selected' : '';
        $html .= '<option value="' . $i . '"' . $sel . '>' . $ket[$i] . '</option>';
    }
    return $html . '</select>';
};
$optD = function ($name, $current) {
    $ket = [
        1 => '1 — dapat diabaikan',
        2 => '2 — kecil',
        3 => '3 — sedang',
        4 => '4 — besar',
        5 => '5 — sangat besar',
    ];
    $html = '<select name="' . $name . '" id="' . $name . '" class="form-control form-control-sm" required>';
    for ($i = 1; $i <= 5; $i++) {
        $sel = ((string) $current === (string) $i) ? ' selected' : '';
        $html .= '<option value="' . $i . '"' . $sel . '>' . $ket[$i] . '</option>';
    }
    return $html . '</select>';
};
?>
<section class="content">
    <div class="container-fluid">

        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-calculator"></i> <?= strtoupper(html_escape($title)) ?></h5>
                <a href="<?= base_url('manrisk/register/detail/' . $risiko->id_register) ?>"
                   class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>

            <?= form_open('manrisk/analisa/isi/' . $risiko->id_register) ?>

            <?php if (validation_errors()) : ?>
                <div class="card-body pb-0">
                    <div class="alert alert-danger mb-0"><?= validation_errors() ?></div>
                </div>
            <?php endif; ?>

            <div class="card-body">

                <!-- ============ KONTEKS ============ -->
                <div class="alert alert-light border">
                    <div class="row mb-1">
                        <div class="col-md-3"><strong>Kode</strong><br><?= html_escape($risiko->kode_risiko) ?></div>
                        <div class="col-md-6"><strong>Risiko</strong><br><?= html_escape($risiko->risiko) ?></div>
                        <div class="col-md-3"><strong>Unit</strong><br><?= html_escape($risiko->nama_bagian) ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><strong>Peristiwa</strong><br><small><?= html_escape($risiko->peristiwa_risiko) ?></small></div>
                        <div class="col-md-6"><strong>Penyebab</strong><br><small><?= html_escape($risiko->penyebab) ?></small></div>
                    </div>
                </div>

                <!-- ============ SKOR ============ -->
                <div class="row">
                    <!-- INHERENT -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100" style="border-color:#dc3545 !important">
                            <h6 class="text-danger mb-3">
                                <i class="fas fa-fire"></i> SEBELUM KONTROL &mdash; <em>Inherent</em>
                            </h6>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="inherent_l">Likelihood <span class="text-danger">*</span></label>
                                    <?= $optL('inherent_l', $e('inherent_l', 3)) ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="inherent_d">Dampak <span class="text-danger">*</span></label>
                                    <?= $optD('inherent_d', $e('inherent_d', 3)) ?>
                                </div>
                            </div>

                            <div class="text-center border-top pt-2">
                                <div class="display-4 mb-0" id="kalk_inherent">—</div>
                                <div id="badge_inherent"></div>
                                <small class="text-muted" id="aksi_inherent"></small>
                            </div>
                        </div>
                    </div>

                    <!-- RESIDUAL -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100" style="border-color:#007bff !important">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-shield-alt"></i> SETELAH KONTROL &mdash; <em>Residual</em>
                            </h6>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="residual_l">Likelihood <span class="text-danger">*</span></label>
                                    <?= $optL('residual_l', $e('residual_l', 2)) ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="residual_d">Dampak <span class="text-danger">*</span></label>
                                    <?= $optD('residual_d', $e('residual_d', 2)) ?>
                                </div>
                            </div>

                            <div class="text-center border-top pt-2">
                                <div class="display-4 mb-0" id="kalk_residual">—</div>
                                <div id="badge_residual"></div>
                                <small class="text-muted" id="aksi_residual"></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============ KONTROL ============ -->
                <h6 class="text-primary border-bottom pb-1 mb-3 mt-4">
                    <i class="fas fa-cogs"></i> Kontrol yang Tersedia
                </h6>

                <div class="form-group">
                    <label for="kontrol_uraian">Uraian Kontrol</label>
                    <textarea name="kontrol_uraian" id="kontrol_uraian" rows="3" class="form-control"
                              placeholder="Prosedur, sistem, atau mekanisme pengendalian yang sudah berjalan"><?= html_escape($e('kontrol_uraian')) ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label class="d-block mb-1">Kontrol Ada? <span class="text-danger">*</span></label>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="ka_ya" name="kontrol_ada" value="Ada"
                                   class="custom-control-input" <?= $e('kontrol_ada', 'Ada') === 'Ada' ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="ka_ya">Ada</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="ka_tidak" name="kontrol_ada" value="Tidak"
                                   class="custom-control-input" <?= $e('kontrol_ada') === 'Tidak' ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="ka_tidak">Tidak</label>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="d-block mb-1">Kontrol Memadai? <span class="text-danger">*</span></label>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="mm_ya" name="memadai" value="Memadai"
                                   class="custom-control-input" <?= $e('memadai', 'Memadai') === 'Memadai' ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="mm_ya">Memadai</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="mm_tidak" name="memadai" value="Belum Memadai"
                                   class="custom-control-input" <?= $e('memadai') === 'Belum Memadai' ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="mm_tidak">Belum Memadai</label>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="id_risk_owner">Risk Owner</label>
                        <select name="id_risk_owner" id="id_risk_owner" class="form-control">
                            <option value="">— belum ditetapkan —</option>
                            <?php foreach ($owner_list as $o) : ?>
                                <option value="<?= $o->id_risk_owner ?>" <?= (string) $e('id_risk_owner') === (string) $o->id_risk_owner ? 'selected' : '' ?>>
                                    <?= html_escape($o->nama_owner . ' — ' . $o->jabatan_owner) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- ============ RINGKASAN ============ -->
                <div class="form-group">
                    <label for="ringkasan">Ringkasan Analisis</label>
                    <textarea name="ringkasan" id="ringkasan" rows="2" class="form-control"
                              placeholder="Penjelasan singkat mengapa skor dipilih"><?= html_escape($e('ringkasan')) ?></textarea>
                </div>

                <!-- ============ LEGENDA ============ -->
                <div class="card card-outline card-secondary mb-0">
                    <div class="card-header"><strong>Skala Skor (L &times; D)</strong></div>
                    <div class="card-body py-2">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-light">
                                <tr><th>Level</th><th>Skor</th><th>Arti</th><th>Rekomendasi Aksi</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tingkat as $t) : ?>
                                    <tr>
                                        <td><span class="badge" style="background-color:<?= $t->warna ?>;color:#fff"><?= html_escape($t->nama_tingkat) ?></span></td>
                                        <td><?= (int) $t->skor_min ?>&ndash;<?= (int) $t->skor_max ?></td>
                                        <td><small><?= html_escape(preg_replace('/^\d+-[^:]*:\s*/', '', (string) $t->deskripsi)) ?></small></td>
                                        <td><small><?= html_escape($t->aksi) ?></small></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Analisa</button>
                <a href="<?= base_url('manrisk/register/detail/' . $risiko->id_register) ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
                <?php if ($isEdit) : ?>
                    <span class="badge badge-light float-right align-self-center mt-2">
                        Terakhir diubah: <?= html_escape($a->modified_at) ?> oleh <?= html_escape($a->modified_by) ?>
                    </span>
                <?php endif; ?>
            </div>

            <?= form_close() ?>
        </div>

    </div>
</section>
</div>

<script>
var PETA = <?= json_encode($petaJson, JSON_UNESCAPED_UNICODE) ?>;

function cariLevel(skor) {
    for (var i = 0; i < PETA.length; i++) {
        if (skor >= PETA[i].min && skor <= PETA[i].max) return PETA[i];
    }
    return null;
}

function gambar(pref, l, d) {
    var skor = parseInt(l, 10) * parseInt(d, 10);
    document.getElementById('kalk_' + pref).innerHTML =
        l + ' &times; ' + d + ' = <strong>' + skor + '</strong>';

    var t = cariLevel(skor);
    var el = document.getElementById('badge_' + pref);
    var ax = document.getElementById('aksi_' + pref);
    if (t) {
        el.innerHTML = '<span class="badge badge-pill px-3 py-2 mt-2" style="background-color:' +
            t.warna + ';color:#fff;font-size:1rem">' + t.nama + '</span>';
        ax.textContent = t.aksi;
    } else {
        el.innerHTML = '';
        ax.textContent = '';
    }
}

function hitung() {
    gambar('inherent',
        document.getElementById('inherent_l').value,
        document.getElementById('inherent_d').value);
    gambar('residual',
        document.getElementById('residual_l').value,
        document.getElementById('residual_d').value);
}

['inherent_l', 'inherent_d', 'residual_l', 'residual_d'].forEach(function (id) {
    document.getElementById(id).addEventListener('change', hitung);
});
hitung();
</script>
