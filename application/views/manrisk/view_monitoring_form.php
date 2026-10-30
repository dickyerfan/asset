<?php
/**
 * Formulir catatan monitoring (ISO 31000 Klausa 6.7).
 *
 * Menjawab empat pertanyaan review: apakah tindakan berjalan, apakah kontrol
 * masih efektif, apakah skor berubah, dan apakah risiko perlu ditinjau ulang.
 */
$m = $catatan;
$isEdit = $m !== null;

$val = function ($k, $default = '') use ($m) {
    if ($m && isset($m->{$k}) && $m->{$k} !== null) {
        return set_value($k, $m->{$k});
    }
    return set_value($k, $default);
};
?>
<section class="content">
    <div class="container-fluid">

        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-eye"></i> <?= strtoupper(html_escape($title)) ?></h5>
                <div>
                    <a href="<?= base_url('manrisk/monitoring') ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-list"></i> Daftar Monitoring
                    </a>
                    <a href="<?= base_url('manrisk/register/detail/' . $risiko->id_register) ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Detail Risiko
                    </a>
                </div>
            </div>

            <?php if ($isEdit) : ?>
                <?= form_open('manrisk/monitoring/form/' . $risiko->id_register) ?>
                <input type="hidden" name="id_monitoring" value="<?= (int) $m->id_monitoring ?>">
            <?php else : ?>
                <?= form_open('manrisk/monitoring/form/' . $risiko->id_register) ?>
            <?php endif; ?>

            <?php if (validation_errors()) : ?>
                <div class="card-body pb-0"><div class="alert alert-danger mb-0"><?= validation_errors() ?></div></div>
            <?php endif; ?>

            <div class="card-body">

                <!-- ============ KONTEKS ============ -->
                <div class="alert alert-light border">
                    <div class="row">
                        <div class="col-md-3"><strong>Kode</strong><br><?= html_escape($risiko->kode_risiko) ?></div>
                        <div class="col-md-6"><strong>Risiko</strong><br><?= html_escape($risiko->risiko) ?></div>
                        <div class="col-md-3">
                            <strong>Residual Saat Ini</strong><br>
                            <?php if ($analisa) : ?>
                                <?= (int) $analisa->residual_l ?> &times; <?= (int) $analisa->residual_d ?>
                                = <strong><?= (int) $analisa->nilai_residual ?></strong>
                                <span class="badge badge-light"><?= html_escape($analisa->level_residual) ?></span>
                            <?php else : ?>
                                <span class="text-muted">belum dianalisis</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- ============ CATATAN ============ -->
                <h6 class="text-primary border-bottom pb-1 mb-3">
                    <i class="fas fa-clipboard-check"></i> Catatan Review
                </h6>

                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="tahun">Tahun</label>
                        <input type="number" name="tahun" id="tahun" class="form-control" min="2027" max="2099"
                               value="<?= $val('tahun', (int) $risiko->tahun) ?>">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="triwulan">Triwulan <span class="text-danger">*</span></label>
                        <select name="triwulan" id="triwulan" class="form-control" required>
                            <?php for ($t = 1; $t <= 4; $t++) : ?>
                                <option value="<?= $t ?>" <?= (string) $val('triwulan', (int) ceil((int) date('n') / 3)) === (string) $t ? 'selected' : '' ?>>TW<?= $t ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tanggal_review">Tanggal Review</label>
                        <input type="date" name="tanggal_review" id="tanggal_review" class="form-control"
                               value="<?= html_escape($val('tanggal_review', date('Y-m-d'))) ?>">
                    </div>
                    <div class="form-group col-md-5">
                        <label for="status_tindakan">Status Tindakan</label>
                        <input type="text" name="status_tindakan" id="status_tindakan" class="form-control"
                               placeholder="mis. Mitigasi berjalan 60%, pengadaan suku cadang tertunda"
                               value="<?= html_escape($val('status_tindakan')) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="hasil_monitoring">Hasil Monitoring <span class="text-danger">*</span></label>
                    <textarea name="hasil_monitoring" id="hasil_monitoring" rows="3" class="form-control"
                              placeholder="Temuan, kondisi aktual, dan kesesuaian dengan rencana tindakan"><?= html_escape($val('hasil_monitoring')) ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="kontrol_masih_efektif">Kontrol Masih Efektif <span class="text-danger">*</span></label>
                        <select name="kontrol_masih_efektif" id="kontrol_masih_efektif" class="form-control">
                            <?php foreach (['YA', 'SEBAGIAN', 'TIDAK'] as $k) : ?>
                                <option value="<?= $k ?>" <?= $val('kontrol_masih_efektif', 'YA') === $k ? 'selected' : '' ?>><?= $k ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="perubahan">Arah Perubahan Skor</label>
                        <select name="perubahan" id="perubahan" class="form-control">
                            <option value="">— pilih —</option>
                            <?php foreach (['Naik', 'Turun', 'Tetap'] as $k) : ?>
                                <option value="<?= $k ?>" <?= $val('perubahan') === $k ? 'selected' : '' ?>><?= $k ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <div class="custom-control custom-switch mt-4">
                            <input type="checkbox" id="perlu_reassess" name="perlu_reassess" value="YA"
                                   class="custom-control-input" <?= $val('perlu_reassess') === 'YA' ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="perlu_reassess"><strong>Perlu Tinjauan Ulang</strong></label>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="nilai_lama">Skor Lama</label>
                        <input type="number" name="nilai_lama" id="nilai_lama" class="form-control" min="1" max="25"
                               value="<?= html_escape($val('nilai_lama', $analisa ? $analisa->nilai_residual : '')) ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="nilai_baru">Skor Baru</label>
                        <input type="number" name="nilai_baru" id="nilai_baru" class="form-control" min="1" max="25"
                               value="<?= html_escape($val('nilai_baru')) ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sebab_perubahan">Sebab Perubahan</label>
                        <input type="text" name="sebab_perubahan" id="sebab_perubahan" class="form-control"
                               placeholder="mis. Regulasi baru, kontrol tambahan, insiden serupa"
                               value="<?= html_escape($val('sebab_perubahan')) ?>">
                    </div>
                </div>

                <!-- ============ RIWAYAT ============ -->
                <?php if ($riwayat) : ?>
                    <h6 class="text-primary border-bottom pb-1 mb-3 mt-4">
                        <i class="fas fa-history"></i> Riwayat Catatan Monitoring
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tanggal</th><th>TW</th><th>Status Tindakan</th><th>Hasil</th>
                                    <th class="text-center">Kontrol</th>
                                    <th class="text-center">Skor</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($riwayat as $h) : ?>
                                    <tr>
                                        <td><?= html_escape($h->tanggal_review) ?></td>
                                        <td><?= (int) $h->triwulan ?></td>
                                        <td><small><?= html_escape($h->status_tindakan ?: '-') ?></small></td>
                                        <td><small><?= html_escape($h->hasil_monitoring ?: '-') ?></small></td>
                                        <td class="text-center">
                                            <span class="badge <?= $h->kontrol_masih_efektif === 'YA' ? 'badge-success' : ($h->kontrol_masih_efektif === 'TIDAK' ? 'badge-danger' : 'badge-warning') ?>">
                                                <?= html_escape($h->kontrol_masih_efektif) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?= $h->nilai_lama !== null ? (int) $h->nilai_lama . ' &rarr; ' . (int) $h->nilai_baru : '—' ?>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <a href="<?= base_url('manrisk/monitoring/form/' . $risiko->id_register . '?id_monitoring=' . $h->id_monitoring) ?>"
                                               class="btn btn-sm btn-outline-secondary" title="Ubah">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ((int) $h->id_monitoring !== (int) $val('id_monitoring')) : ?>
                                                <a href="<?= base_url('manrisk/monitoring/hapus/' . $h->id_monitoring) ?>"
                                                   class="btn btn-sm btn-outline-danger" title="Hapus"
                                                   onclick="return confirm('Hapus catatan monitoring tanggal <?= html_escape($h->tanggal_review) ?>?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Catatan</button>
                <a href="<?= base_url('manrisk/monitoring') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>

            <?= form_close() ?>
        </div>

    </div>
</section>
</div>
