<?php
/**
 * Review keputusan evaluasi yang berbeda dari aksi band.
 * Hanya Administrator/Auditor yang dapat menutup antrean ini.
 */
$statusLabel = $evaluasi->status_review === 'DITINJAU' ? 'Sudah ditinjau' : 'Menunggu review';
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <div class="card card-outline card-warning">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-flag"></i> <?= strtoupper(html_escape($title)) ?></h5>
                <a href="<?= base_url('manrisk/evaluasi?review=1') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-7">
                        <table class="table table-sm table-bordered">
                            <tr><th style="width:180px">Kode risiko</th><td><strong><?= html_escape($risiko->kode_risiko) ?></strong></td></tr>
                            <tr><th>Risiko</th><td><?= html_escape($risiko->risiko) ?></td></tr>
                            <tr><th>Skor residual</th><td><?= (int) $evaluasi->nilai_evaluasi ?> (<?= html_escape($evaluasi->level_evaluasi) ?>)</td></tr>
                            <tr><th>Keputusan</th><td><strong><?= html_escape($evaluasi->keputusan) ?></strong></td></tr>
                            <tr><th>Rekomendasi band</th><td><?= html_escape($evaluasi->keputusan_band ?: '-') ?></td></tr>
                            <tr><th>Status</th><td><span class="badge badge-<?= $evaluasi->status_review === 'DITINJAU' ? 'success' : 'warning' ?>"><?= html_escape($statusLabel) ?></span></td></tr>
                            <tr><th>Alasan evaluasi</th><td><?= nl2br(html_escape($evaluasi->alasan_evaluasi ?: '-')) ?></td></tr>
                        </table>
                    </div>

                    <div class="col-md-5">
                        <?= form_open('manrisk/evaluasi/review/' . (int) $evaluasi->id_evaluasi) ?>
                            <div class="form-group">
                                <label for="catatan_review">Catatan review <span class="text-danger">*</span></label>
                                <textarea name="catatan_review" id="catatan_review" rows="5" class="form-control" required
                                          placeholder="Alasan mengapa override diterima/ditolak, dasar appetite, atau tindak lanjut"><?= html_escape($evaluasi->catatan_review ?: '') ?></textarea>
                            </div>
                            <?php if ($evaluasi->reviewed_by) : ?>
                                <p class="small text-muted">
                                    Ditinjau oleh <?= html_escape($evaluasi->reviewed_by) ?>
                                    pada <?= html_escape($evaluasi->reviewed_at) ?>.
                                </p>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-check"></i> Tandai Sudah Ditinjau
                            </button>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <a href="<?= base_url('manrisk/evaluasi/form/' . $risiko->id_register) ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-edit"></i> Ubah Evaluasi
                </a>
            </div>
        </div>

    </div>
</section>
</div>
