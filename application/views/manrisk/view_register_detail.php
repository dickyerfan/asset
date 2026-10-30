<?php
/**
 * Detail satu risiko + ringkasan seluruh tahapan siklus manajemen risiko.
 *
 * Tahapan mengikuti ISO 31000:2018 Klausa 6.2-6.7:
 * 6.2 Komunikasi & konsultasi -> 6.3 Lingkup/konteks -> 6.4 Identifikasi
 * 6.5 Analisis -> 6.6 Evaluasi -> 6.7 Perlakuan & monitoring.
 */
$warnaLevel = [];
foreach ($tingkat as $t) {
    $warnaLevel[$t->nama_tingkat] = $t->warna;
}
$badge = function ($level) use ($warnaLevel) {
    if ($level === null || $level === '') {
        return '<span class="badge badge-secondary">belum dihitung</span>';
    }
    $w = isset($warnaLevel[$level]) ? $warnaLevel[$level] : '#6c757d';
    return '<span class="badge" style="background-color:' . $w . ';color:#fff">' . html_escape($level) . '</span>';
};
$a = $analisa;
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <strong><?= html_escape($risiko->kode_risiko) ?></strong>
                    &mdash; <?= strtoupper($title) ?>
                </h5>
                <div>
                    <a href="<?= base_url('manrisk/register') ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <?php if ($boleh_tulis) : ?>
                        <a href="<?= base_url('manrisk/register/form/' . $risiko->id_register) ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Ubah
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <!-- ============ IDENTIFIKASI ============ -->
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h5 class="text-primary">Rumusan Risiko</h5>
                        <p class="lead mb-2"><?= html_escape($risiko->risiko) ?></p>

                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-1"><strong>Peristiwa Risiko</strong><br><?= html_escape($risiko->peristiwa_risiko) ?></p>
                                <p class="mb-1"><strong>Penyebab / Sumber</strong><br><?= html_escape($risiko->penyebab) ?></p>
                                <?php if ($risiko->sumber_risiko && $risiko->sumber_risiko !== $risiko->penyebab) : ?>
                                    <p class="mb-1"><strong>Sumber Risiko</strong><br><?= html_escape($risiko->sumber_risiko) ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="col-sm-6">
                                <p class="mb-1"><strong>Sasaran / KPI RKAP</strong><br><?= html_escape($risiko->sasaran_kpi) ?></p>
                                <?php if ($risiko->indikator_emerging) : ?>
                                    <p class="mb-1"><strong>Indikator Emerging</strong><br><?= html_escape($risiko->indikator_emerging) ?></p>
                                <?php endif; ?>
                                <?php if ($risiko->asumsi_bias) : ?>
                                    <p class="mb-1"><strong>Asumsi &amp; Bias</strong><br><?= html_escape($risiko->asumsi_bias) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <table class="table table-sm table-bordered mb-0">
                            <tbody>
                                <tr><th>Unit</th><td><?= html_escape($risiko->nama_bagian) ?></td></tr>
                                <tr><th>Kategori</th><td><?= html_escape($risiko->nama_kat) ?></td></tr>
                                <tr>
                                    <th>Tipe</th>
                                    <td>
                                        <span class="badge <?= $risiko->tipe_risiko === 'Peluang' ? 'badge-success' : 'badge-danger' ?>">
                                            <?= html_escape($risiko->tipe_risiko) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr><th>Tahun / TW</th><td><?= (int) $risiko->tahun ?> / TW<?= (int) $risiko->triwulan_awal ?></td></tr>
                                <tr>
                                    <th>Kendali</th>
                                    <td>
                                        <span class="badge <?= $risiko->status_kendali === 'C' ? 'badge-success' : 'badge-warning' ?>">
                                            <?= html_escape($risiko->status_kendali) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr><th>Status</th><td><?= html_escape($risiko->status_risiko) ?></td></tr>
                                <?php if ($risiko->keterangan) : ?>
                                    <tr><th>Keterangan</th><td><small><?= html_escape($risiko->keterangan) ?></small></td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ============ AKIBAT ============ -->
                <h5 class="text-primary">Akibat yang Mungkin Terjadi</h5>
                <?php if (!$akibat) : ?>
                    <p class="text-muted">Belum ada akibat yang didaftarkan.</p>
                <?php else : ?>
                    <table class="table table-sm table-bordered mb-4">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:40px">#</th>
                                <th>Akibat</th>
                                <th style="width:130px">Jenis</th>
                                <th>Dampak Kualitatif</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($akibat as $ak) : ?>
                                <tr>
                                    <td class="text-center"><?= (int) $ak->urutan ?></td>
                                    <td><?= html_escape($ak->akibat) ?></td>
                                    <td>
                                        <span class="badge <?= $ak->jenis === 'Finansial' ? 'badge-danger' : 'badge-info' ?>">
                                            <?= html_escape($ak->jenis) ?>
                                        </span>
                                    </td>
                                    <td><small><?= html_escape($ak->dampak_kualitatif ?: '-') ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <!-- ============ PROSES & OBJEKTIF ============ -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="text-primary">Proses Terkait (Risk Universe)</h5>
                        <?php if (!$proses) : ?>
                            <p class="text-muted">Belum ditautkan ke proses.</p>
                        <?php else : ?>
                            <ul class="list-inline mb-0">
                                <?php foreach ($proses as $p) : ?>
                                    <li class="list-inline-item mb-1">
                                        <span class="badge badge-dark" title="<?= html_escape($p->nama_proses) ?>">
                                            <?= html_escape($p->kode_proses) ?> — <?= html_escape($p->nama_proses) ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-primary">Objektif RKAP Terkait</h5>
                        <?php if (!$objektif) : ?>
                            <p class="text-muted">Belum ditautkan ke objektif RKAP.</p>
                        <?php else : ?>
                            <ul class="list-unstyled mb-0">
                                <?php foreach ($objektif as $o) : ?>
                                    <li class="mb-1">
                                        <small>
                                            <?= html_escape($o->indikator) ?>
                                            <?php if ($o->nilai_rkap !== null) : ?>
                                                &mdash; <strong><?= html_escape($o->nilai_rkap) ?></strong> <?= html_escape($o->satuan) ?>
                                            <?php endif; ?>
                                            <span class="badge <?= $o->status_rekonsiliasi === 'SELESAI' ? 'badge-success' : 'badge-warning' ?>">
                                                <?= html_escape($o->status_rekonsiliasi) ?>
                                            </span>
                                        </small>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ============ TABS SIKLUS ============ -->
                <ul class="nav nav-tabs" id="tabSiklus" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-analisa" data-toggle="tab" href="#panel-analisa" role="tab">
                            1. Analisa (L&times;D)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-evaluasi" data-toggle="tab" href="#panel-evaluasi" role="tab">
                            2. Evaluasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-perlakuan" data-toggle="tab" href="#panel-perlakuan" role="tab">
                            3. Perlakuan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-monitoring" data-toggle="tab" href="#panel-monitoring" role="tab">
                            4. Monitoring
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-riwayat" data-toggle="tab" href="#panel-riwayat" role="tab">
                            5. Riwayat Skor
                        </a>
                    </li>
                </ul>

                <div class="tab-content pt-3">
                    <!-- ---------- ANALISA ---------- -->
                    <div class="tab-pane fade show active" id="panel-analisa" role="tabpanel">
                        <?php if (!$a) : ?>
                            <div class="alert alert-warning">
                                Risiko ini belum dianalisis. Tetapkan Likelihood &amp; Dampak untuk skor
                                <strong>inherent</strong> dan <strong>residual</strong>.
                                <?php if ($boleh_tulis) : ?>
                                    <a href="<?= base_url('manrisk/analisa/index/' . $risiko->id_register) ?>" class="btn btn-sm btn-primary ml-2">
                                        <i class="fas fa-calculator"></i> Analisa Sekarang
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php else : ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="border rounded p-3 mb-3">
                                        <h6 class="text-muted">Sebelum Kontrol (Inherent)</h6>
                                        <h2 class="mb-1">
                                            <?= (int) $a->inherent_l ?> &times; <?= (int) $a->inherent_d ?>
                                            = <strong><?= (int) $a->nilai_inherent ?></strong>
                                        </h2>
                                        <?= $badge($a->level_inherent) ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-3 mb-3">
                                        <h6 class="text-muted">Setelah Kontrol (Residual)</h6>
                                        <h2 class="mb-1">
                                            <?= (int) $a->residual_l ?> &times; <?= (int) $a->residual_d ?>
                                            = <strong><?= (int) $a->nilai_residual ?></strong>
                                        </h2>
                                        <?= $badge($a->level_residual) ?>
                                    </div>
                                </div>
                            </div>

                            <table class="table table-sm table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width:180px">Kontrol yang Ada</th>
                                        <td>
                                            <?= html_escape($a->kontrol_uraian ?: '-') ?><br>
                                            <span class="badge <?= $a->kontrol_ada === 'Ada' ? 'badge-success' : 'badge-secondary' ?>">
                                                Kontrol: <?= html_escape($a->kontrol_ada) ?>
                                            </span>
                                            <span class="badge <?= $a->memadai === 'Memadai' ? 'badge-success' : 'badge-warning' ?>">
                                                <?= html_escape($a->memadai) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr><th>Ringkasan</th><td><?= html_escape($a->ringkasan ?: '-') ?></td></tr>
                                    <tr>
                                        <th>Risk Owner</th>
                                        <td>
                                            <?php
                    $ow = null;
                    foreach ($owner_list as $o) {
                        if ($o->id_risk_owner == $a->id_risk_owner) {
                            $ow = $o;
                            break;
                        }
                    }
                    ?>
                                            <?= $ow ? html_escape($ow->nama_owner . ' — ' . $ow->jabatan_owner) : '<span class="text-muted">belum ditetapkan</span>' ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <?php if ($boleh_tulis) : ?>
                                <a href="<?= base_url('manrisk/analisa/index/' . $risiko->id_register) ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Ubah Analisa
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <!-- ---------- EVALUASI ---------- -->
                    <div class="tab-pane fade" id="panel-evaluasi" role="tabpanel">
                        <?php if (!$evaluasi) : ?>
                            <div class="alert alert-warning">Belum ada evaluasi untuk risiko ini.</div>
                        <?php else : ?>
                            <?php foreach ($evaluasi as $e) : ?>
                                <div class="border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">
                                            TW<?= (int) $e->triwulan ?> &middot; <?= html_escape($e->tanggal_evaluasi) ?>
                                            &mdash; <?= html_escape($e->oleh) ?>
                                        </h6>
                                        <span class="badge badge-dark">skor <?= (int) $e->nilai_evaluasi ?></span>
                                    </div>
                                    <p class="mb-1">
                                        Keputusan:
                                        <span class="badge badge-info"><?= html_escape($e->keputusan) ?></span>
                                        &middot; <?= $badge($e->level_evaluasi) ?>
                                        <?php if ($e->perlu_eskalasi) : ?>
                                            <span class="badge badge-danger">Eskalasi ke <?= html_escape($e->eskalasi_ke ?: '-') ?></span>
                                        <?php endif; ?>
                                    </p>
                                    <p class="mb-0"><small><?= html_escape($e->alasan_evaluasi ?: '-') ?></small></p>
                                </div>
                            <?php endforeach; ?>
                            <?php if ($boleh_tulis) : ?>
                                <a href="<?= base_url('manrisk/evaluasi/index/' . $risiko->id_register) ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Evaluasi Ulang
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <!-- ---------- PERLAKUAN ---------- -->
                    <div class="tab-pane fade" id="panel-perlakuan" role="tabpanel">
                        <?php if (!$perlakuan) : ?>
                            <div class="alert alert-warning">Belum ada rencana perlakuan risiko.</div>
                        <?php else :
                            $labelStrategi = isset($perlakuan) ? [
                                'AVOID' => 'Hindari', 'REDUCE' => 'Kurangi',
                                'SHARE' => 'Transfer', 'ACCEPT' => 'Terima',
                            ] : [];
                        ?>
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <h6>
                                        Strategi:
                                        <span class="badge badge-primary">
                                            <?= html_escape(isset($labelStrategi[$perlakuan->strategi]) ? $labelStrategi[$perlakuan->strategi] : $perlakuan->strategi) ?>
                                        </span>
                                        &middot;
                                        <span class="badge badge-<?= $perlakuan->status_laksana === 'SELESAI' ? 'success' : ($perlakuan->status_laksana === 'DALAM_PROSES' ? 'warning' : 'secondary') ?>">
                                            <?= html_escape($perlakuan->status_laksana) ?>
                                        </span>
                                    </h6>
                                </div>
                                <p class="mb-1"><strong>Uraian Tindakan</strong><br><?= html_escape($perlakuan->uraian_tindakan ?: '-') ?></p>
                                <p class="mb-1"><strong>Indikator Sukses</strong><br><?= html_escape($perlakuan->indikator_sukses ?: '-') ?></p>
                                <div class="row">
                                    <div class="col-sm-4"><strong>Anggaran</strong><br>
                                        <?= $perlakuan->anggaran_biaya !== null ? 'Rp ' . number_format((float) $perlakuan->anggaran_biaya, 0, ',', '.') : '-' ?>
                                    </div>
                                    <div class="col-sm-4"><strong>Jadwal</strong><br>
                                        <?= html_escape($perlakuan->jadwal_mulai ?: '-') ?> s/d <?= html_escape($perlakuan->jadwal_selesai ?: '-') ?>
                                    </div>
                                    <div class="col-sm-4"><strong>PIC</strong><br>
                                        <?php
                                        $pic = null;
                                        foreach ($pic_list as $p) {
                                            if ($p->id_pic == $perlakuan->id_pic) {
                                                $pic = $p;
                                                break;
                                            }
                                        }
                                        echo $pic ? html_escape($pic->nama_pic) : '<span class="text-muted">belum ditetapkan</span>';
                                        ?>
                                    </div>
                                </div>
                                <p class="mb-0 mt-2"><small class="text-muted"><strong>Alasan strategi:</strong> <?= html_escape($perlakuan->alasan_strategi ?: '-') ?></small></p>
                            </div>
                            <?php if ($boleh_tulis) : ?>
                                <a href="<?= base_url('manrisk/perlakuan/index/' . $risiko->id_register) ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Ubah Perlakuan
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <!-- ---------- MONITORING ---------- -->
                    <div class="tab-pane fade" id="panel-monitoring" role="tabpanel">
                        <?php if (!$monitoring) : ?>
                            <div class="alert alert-warning">Belum ada catatan monitoring.</div>
                        <?php else : ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>TW</th>
                                            <th>Hasil</th>
                                            <th class="text-center">Kontrol Efektif</th>
                                            <th class="text-center">Skor</th>
                                            <th class="text-center">Perlu Reassess</th>
                                            <th>Reviewer</th>
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
                                                <td class="text-center">
                                                    <?= $m->nilai_lama !== null ? (int) $m->nilai_lama . ' → ' . (int) $m->nilai_baru : '-' ?>
                                                    <?php if ($m->perubahan) : ?>
                                                        <br><small><?= html_escape($m->perubahan) ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= $m->perlu_reassess ? '<span class="badge badge-warning">YA</span>' : 'TIDAK' ?>
                                                </td>
                                                <td><small><?= html_escape($m->review_oleh) ?></small></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if ($boleh_tulis) : ?>
                                <a href="<?= base_url('manrisk/monitoring/index/' . $risiko->id_register) ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Monitoring
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <!-- ---------- RIWAYAT ---------- -->
                    <div class="tab-pane fade" id="panel-riwayat" role="tabpanel">
                        <?php if (!$riwayat) : ?>
                            <p class="text-muted">Belum ada riwayat perubahan skor.</p>
                        <?php else : ?>
                            <table class="table table-sm table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Tercatat</th>
                                        <th class="text-center">Inherent</th>
                                        <th>Level</th>
                                        <th class="text-center">Residual</th>
                                        <th>Level</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($riwayat as $w) : ?>
                                        <tr>
                                            <td><?= html_escape($w->recorded_at) ?> (TW<?= (int) $w->triwulan ?>)</td>
                                            <td class="text-center"><strong><?= (int) $w->nilai_inherent ?></strong></td>
                                            <td><?= $badge($w->level_inherent) ?></td>
                                            <td class="text-center"><strong><?= (int) $w->nilai_residual ?></strong></td>
                                            <td><?= $badge($w->level_residual) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <small class="text-muted">
                                Riwayat dicatat otomatis setiap kali evaluasi menyimpan skor baru.
                            </small>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>
</div>
