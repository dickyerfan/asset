<?php
/**
 * Formulir laporan manajemen risiko.
 *
 * Bagian-bagian laporan mengikuti struktur §10.1 dokumentasi MR.
 */
$l = $laporan;
$isEdit = $l !== null;

$val = function ($k, $default = '') use ($l) {
    if ($l && isset($l->{$k}) && $l->{$k} !== null) {
        return set_value($k, $l->{$k});
    }
    return set_value($k, $default);
};

$bagian = [
    'ringkasan_eksekutif'   => ['Ringkasan Eksekutif', 3],
    'latar_belakang'        => ['Latar Belakang', 2],
    'tujuan_laporan'        => ['Tujuan Laporan', 2],
    'ruang_lingkup'         => ['Ruang Lingkup', 2],
    'rekap_inherent_residual' => ['Rekap Inherent & Residual', 3],
    'catatan_peta_risiko'   => ['Catatan Peta Risiko', 3],
    'rekap_peluang'         => ['Rekap Peluang', 2],
    'hasil_kri'             => ['Hasil KRI', 3],
    'evaluasi_tindak_lanjut' => ['Evaluasi & Tindak Lanjut', 3],
    'kesimpulan'            => ['Kesimpulan', 3],
    'rencana_tindak_lanjut' => ['Rencana Tindak Lanjut', 3],
    'sumber_catatan'        => ['Sumber / Catatan Kaki', 2],
];
?>
<section class="content">
    <div class="container-fluid">

        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-file-alt"></i> <?= strtoupper(html_escape($title)) ?></h5>
                <a href="<?= base_url('manrisk/laporan') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <?= form_open('manrisk/laporan/form' . ($isEdit ? '/' . $l->id_laporan : '')) ?>

            <?php if (validation_errors()) : ?>
                <div class="card-body pb-0"><div class="alert alert-danger mb-0"><?= validation_errors() ?></div></div>
            <?php endif; ?>

            <div class="card-body">

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="jenis_laporan">Jenis Laporan <span class="text-danger">*</span></label>
                        <select name="jenis_laporan" id="jenis_laporan" class="form-control" required>
                            <?php foreach ($jenis as $j) : ?>
                                <option value="<?= $j ?>" <?= $val('jenis_laporan', 'Operasional') === $j ? 'selected' : '' ?>><?= $j ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="tahun">Tahun <span class="text-danger">*</span></label>
                        <input type="number" name="tahun" id="tahun" class="form-control" min="2027" max="2099"
                               value="<?= $val('tahun', $tahun) ?>">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="triwulan">Triwulan</label>
                        <select name="triwulan" id="triwulan" class="form-control">
                            <?php for ($t = 1; $t <= 4; $t++) : ?>
                                <option value="<?= $t ?>" <?= (string) $val('triwulan', (int) ceil((int) date('n') / 3)) === (string) $t ? 'selected' : '' ?>>TW<?= $t ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="id_upk">Unit</label>
                        <select name="id_upk" id="id_upk" class="form-control">
                            <option value="">— seluruh organisasi —</option>
                            <?php foreach ($unit_list as $u) : ?>
                                <option value="<?= $u->id_bagian ?>" <?= (string) $val('id_upk') === (string) $u->id_bagian ? 'selected' : '' ?>>
                                    <?= html_escape($u->nama_bagian) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="DRAFT" <?= $val('status', 'DRAFT') === 'DRAFT' ? 'selected' : '' ?>>DRAFT</option>
                            <option value="FINAL" <?= $val('status') === 'FINAL' ? 'selected' : '' ?>>FINAL</option>
                        </select>
                    </div>
                </div>

                <?php foreach ($bagian as $k => $info) : ?>
                    <div class="form-group">
                        <label for="<?= $k ?>"><?= html_escape($info[0]) ?></label>
                        <textarea name="<?= $k ?>" id="<?= $k ?>" rows="<?= $info[1] ?>" class="form-control"><?= html_escape($val($k)) ?></textarea>
                    </div>
                <?php endforeach; ?>

                <?php if ($isEdit && $l->disahkan_oleh) : ?>
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-stamp"></i>
                        Disahkan oleh <strong><?= html_escape($l->disahkan_oleh) ?></strong>
                        pada <?= html_escape($l->tanggal_sahkan) ?>.
                    </div>
                <?php endif; ?>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="<?= base_url('manrisk/laporan') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>

            <?= form_close() ?>
        </div>

    </div>
</section>
</div>
