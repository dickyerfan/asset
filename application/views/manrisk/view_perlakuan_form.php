<?php
/**
 * Formulir rencana perlakuan risiko (ISO 31000 Klausa 6.7).
 *
 * Empat strategi sesuai ISO: AVOID, REDUCE, SHARE, ACCEPT.
 */
$p = $perlakuan;
$isEdit = $p !== null;

$labelStatus = ['BELUM' => 'Belum', 'DALAM_PROSES' => 'Dalam Proses', 'SELESAI' => 'Selesai'];

$val = function ($k, $default = '') use ($p) {
    if ($p && isset($p->{$k}) && $p->{$k} !== null) {
        return set_value($k, $p->{$k});
    }
    return set_value($k, $default);
};

$penjelasan = [
    'AVOID'  => 'Menghilangkan sumber risiko: menghentikan kegiatan, mengubah proses, atau tidak melanjutkan rencana.',
    'REDUCE' => 'Menurunkan likelihood atau dampak melalui kontrol, mitigasi, atau perbaikan proses.',
    'SHARE'  => 'Memindahkan sebagian risiko ke pihak lain: asuransi, kontrak, kemitraan, atau penanggung jawab bersama.',
    'ACCEPT' => 'Risiko diterima asalkan dalam batas appetite; disiapkan cadangan dan dipantau.',
];
?>
<section class="content">
    <div class="container-fluid">

        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-tools"></i> <?= strtoupper(html_escape($title)) ?></h5>
                <a href="<?= base_url('manrisk/register/detail/' . $risiko->id_register) ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <?= form_open('manrisk/perlakuan/form/' . $risiko->id_register) ?>

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
                            <strong>Keputusan Evaluasi</strong><br>
                            <?php if ($evaluasi) : ?>
                                <span class="badge badge-info"><?= html_escape($evaluasi->keputusan) ?></span>
                                &middot; skor <?= (int) $evaluasi->nilai_evaluasi ?>
                            <?php else : ?>
                                <span class="text-muted">belum dievaluasi</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if (!$evaluasi) : ?>
                    <div class="alert alert-warning">
                        Risiko ini belum dievaluasi. Sebaiknya evaluasi terlebih dahulu agar strategi dapat dipilih
                        berdasarkan keputusan.
                        <a href="<?= base_url('manrisk/evaluasi/form/' . $risiko->id_register) ?>" class="btn btn-sm btn-primary ml-2">
                            <i class="fas fa-balance-scale"></i> Evaluasi Sekarang
                        </a>
                    </div>
                <?php endif; ?>

                <!-- ============ STRATEGI ============ -->
                <h6 class="text-primary border-bottom pb-1 mb-3">
                    <i class="fas fa-random"></i> Strategi Perlakuan <span class="text-danger">*</span>
                </h6>

                <div class="row mb-3">
                    <?php foreach ($strategi as $k => $nama) : ?>
                        <div class="col-md-3">
                            <div class="card h-100 border<?= $val('strategi', 'REDUCE') === $k ? ' border-primary' : '' ?>">
                                <div class="card-body p-2">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="st_<?= $k ?>" name="strategi" value="<?= $k ?>"
                                               class="custom-control-input" <?= $val('strategi', 'REDUCE') === $k ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="st_<?= $k ?>">
                                            <strong><?= html_escape($k) ?></strong> — <?= html_escape($nama) ?>
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1"><?= html_escape($penjelasan[$k]) ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="form-group">
                    <label for="alasan_strategi">Alasan Pemilihan Strategi</label>
                    <textarea name="alasan_strategi" id="alasan_strategi" rows="2" class="form-control"
                              placeholder="Mengapa strategi ini dipilih untuk risiko ini"><?= html_escape($val('alasan_strategi')) ?></textarea>
                </div>

                <!-- ============ RENCANA ============ -->
                <h6 class="text-primary border-bottom pb-1 mb-3 mt-4">
                    <i class="fas fa-tasks"></i> Rencana Tindakan
                </h6>

                <div class="form-group">
                    <label for="uraian_tindakan">Uraian Tindakan</label>
                    <textarea name="uraian_tindakan" id="uraian_tindakan" rows="3" class="form-control"
                              placeholder="Langkah konkret yang akan dilakukan"><?= html_escape($val('uraian_tindakan')) ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="indikator_sukses">Indikator Keberhasilan</label>
                        <textarea name="indikator_sukses" id="indikator_sukses" rows="2" class="form-control"
                                  placeholder="Bagaimana keberhasilan diukur"><?= html_escape($val('indikator_sukses')) ?></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sumber_daya">Sumber Daya yang Dibutuhkan</label>
                        <textarea name="sumber_daya" id="sumber_daya" rows="2" class="form-control"
                                  placeholder="SDM, peralatan, sistem, dsb."><?= html_escape($val('sumber_daya')) ?></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="anggaran_biaya">Anggaran Biaya (Rp)</label>
                        <input type="text" name="anggaran_biaya" id="anggaran_biaya" class="form-control"
                               placeholder="0" value="<?= html_escape($val('anggaran_biaya')) ?>">
                        <small class="text-muted">Angka saja, tanpa titik/koma.</small>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="jadwal_mulai">Jadwal Mulai</label>
                        <input type="date" name="jadwal_mulai" id="jadwal_mulai" class="form-control"
                               value="<?= html_escape($val('jadwal_mulai')) ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="jadwal_selesai">Jadwal Selesai</label>
                        <input type="date" name="jadwal_selesai" id="jadwal_selesai" class="form-control"
                               value="<?= html_escape($val('jadwal_selesai')) ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="id_pic">PIC (Penanggung Jawab)</label>
                        <select name="id_pic" id="id_pic" class="form-control">
                            <option value="">— belum ditetapkan —</option>
                            <?php foreach ($pic_list as $pc) : ?>
                                <option value="<?= $pc->id_pic ?>" <?= (string) $val('id_pic') === (string) $pc->id_pic ? 'selected' : '' ?>>
                                    <?= html_escape($pc->nama_pic) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status_laksana">Status Pelaksanaan <span class="text-danger">*</span></label>
                    <select name="status_laksana" id="status_laksana" class="form-control" style="max-width:260px">
                        <?php foreach ($status_laksana as $s) : ?>
                            <option value="<?= $s ?>" <?= $val('status_laksana', 'BELUM') === $s ? 'selected' : '' ?>>
                                <?= html_escape($labelStatus[$s]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- ============ MONITORING TERKAIT ============ -->
                <?php if ($monitoring) : ?>
                    <h6 class="text-primary border-bottom pb-1 mb-3 mt-4">
                        <i class="fas fa-eye"></i> Monitoring Terkait
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tanggal</th><th>TW</th><th>Hasil</th>
                                    <th class="text-center">Kontrol Efektif</th>
                                    <th class="text-center">Perlu Reassess</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($monitoring as $m) : ?>
                                    <tr>
                                        <td><?= html_escape($m->tanggal_review) ?></td>
                                        <td><?= (int) $m->triwulan ?></td>
                                        <td><small><?= html_escape($m->hasil_monitoring) ?></small></td>
                                        <td class="text-center">
                                            <span class="badge <?= $m->kontrol_masih_efektif === 'YA' ? 'badge-success' : ($m->kontrol_masih_efektif === 'TIDAK' ? 'badge-danger' : 'badge-warning') ?>">
                                                <?= html_escape($m->kontrol_masih_efektif) ?>
                                            </span>
                                        </td>
                                        <td class="text-center"><?= $m->perlu_reassess ? 'YA' : 'TIDAK' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perlakuan</button>
                <a href="<?= base_url('manrisk/register/detail/' . $risiko->id_register) ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>

            <?= form_close() ?>
        </div>

    </div>
</section>
</div>
