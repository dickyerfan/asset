<?php
/**
 * Formulir dokumen lingkup penerapan manajemen risiko.
 */
$l = $lingkup;
$isEdit = $l !== null;

$val = function ($k, $default = '') use ($l) {
    if ($l && isset($l->{$k}) && $l->{$k} !== null) {
        return set_value($k, $l->{$k});
    }
    return set_value($k, $default);
};
?>
<section class="content">
    <div class="container-fluid">

        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-expand-arrows-alt"></i> <?= strtoupper(html_escape($title)) ?></h5>
                <a href="<?= base_url('manrisk/lingkup') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <?= form_open('manrisk/lingkup/form' . ($isEdit ? '/' . $l->id_lingkup : '')) ?>

            <?php if (validation_errors()) : ?>
                <div class="card-body pb-0"><div class="alert alert-danger mb-0"><?= validation_errors() ?></div></div>
            <?php endif; ?>

            <div class="card-body">

                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="tahun">Tahun <span class="text-danger">*</span></label>
                        <input type="number" name="tahun" id="tahun" class="form-control" min="2027" max="2099"
                               value="<?= $val('tahun', $tahun) ?>">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="triwulan">Triwulan</label>
                        <select name="triwulan" id="triwulan" class="form-control">
                            <?php for ($t = 1; $t <= 4; $t++) : ?>
                                <option value="<?= $t ?>" <?= (string) $val('triwulan', 1) === (string) $t ? 'selected' : '' ?>>TW<?= $t ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="id_upk">Unit</label>
                        <select name="id_upk" id="id_upk" class="form-control">
                            <option value="">— seluruh organisasi —</option>
                            <?php foreach ($unit_list as $u) : ?>
                                <?php if (!$akses_semua && (int) $u->id_bagian !== (int) $this->session->userdata('id_bagian')) continue; ?>
                                <option value="<?= $u->id_bagian ?>" <?= (string) $val('id_upk') === (string) $u->id_bagian ? 'selected' : '' ?>>
                                    <?= html_escape($u->nama_bagian) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="nama_lingkup">Nama Lingkup <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lingkup" id="nama_lingkup" class="form-control" maxlength="200" required
                               placeholder="Lingkup Penerapan MR UPK ..." value="<?= html_escape($val('nama_lingkup')) ?>">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="DRAFT" <?= $val('status', 'DRAFT') === 'DRAFT' ? 'selected' : '' ?>>DRAFT</option>
                            <option value="FINAL" <?= $val('status') === 'FINAL' ? 'selected' : '' ?>>FINAL</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="objek_evaluasi">Objek Evaluasi</label>
                    <textarea name="objek_evaluasi" id="objek_evaluasi" rows="2" class="form-control"><?= html_escape($val('objek_evaluasi')) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="batas_evaluasi">Batas Evaluasi</label>
                    <textarea name="batas_evaluasi" id="batas_evaluasi" rows="2" class="form-control"><?= html_escape($val('batas_evaluasi')) ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="konteks_internal">Konteks Internal</label>
                        <textarea name="konteks_internal" id="konteks_internal" rows="3" class="form-control"><?= html_escape($val('konteks_internal')) ?></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="konteks_eksternal">Konteks Eksternal</label>
                        <textarea name="konteks_eksternal" id="konteks_eksternal" rows="3" class="form-control"><?= html_escape($val('konteks_eksternal')) ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="kriteria_acuan">Kriteria Acuan</label>
                    <textarea name="kriteria_acuan" id="kriteria_acuan" rows="2" class="form-control"><?= html_escape($val('kriteria_acuan')) ?></textarea>
                </div>

                <h6 class="text-primary border-bottom pb-1 mb-3 mt-4">
                    <i class="fas fa-comments"></i> Konsultasi
                </h6>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="tanggal_konsultasi">Tanggal</label>
                        <input type="date" name="tanggal_konsultasi" id="tanggal_konsultasi" class="form-control"
                               value="<?= html_escape($val('tanggal_konsultasi')) ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="pic_konsultasi">PIC Konsultasi</label>
                        <input type="text" name="pic_konsultasi" id="pic_konsultasi" class="form-control"
                               value="<?= html_escape($val('pic_konsultasi')) ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="peserta">Peserta</label>
                        <textarea name="peserta" id="peserta" rows="2" class="form-control"><?= html_escape($val('peserta')) ?></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="q_sasaran">Pertanyaan — Sasaran</label>
                        <textarea name="q_sasaran" id="q_sasaran" rows="2" class="form-control"><?= html_escape($val('q_sasaran')) ?></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="q_risiko">Pertanyaan — Risiko</label>
                        <textarea name="q_risiko" id="q_risiko" rows="2" class="form-control"><?= html_escape($val('q_risiko')) ?></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="q_kontrol">Pertanyaan — Kontrol</label>
                        <textarea name="q_kontrol" id="q_kontrol" rows="2" class="form-control"><?= html_escape($val('q_kontrol')) ?></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="q_tindakan">Pertanyaan — Tindakan</label>
                        <textarea name="q_tindakan" id="q_tindakan" rows="2" class="form-control"><?= html_escape($val('q_tindakan')) ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="hasil_konsultasi">Hasil Konsultasi</label>
                    <textarea name="hasil_konsultasi" id="hasil_konsultasi" rows="3" class="form-control"><?= html_escape($val('hasil_konsultasi')) ?></textarea>
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="<?= base_url('manrisk/lingkup') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>

            <?= form_close() ?>
        </div>

    </div>
</section>
</div>
