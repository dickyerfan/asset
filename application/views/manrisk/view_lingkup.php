<?php

/**
 * Daftar dokumen lingkup penerapan manajemen risiko.
 */
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <div class="row align-items-center mb-2">
            <div class="col-md-8">
                <h4 class="mb-0"><?= strtoupper($title) ?> <?= $tahun ?></h4>
                <small class="text-muted">
                    Menentukan <em>lingkup, konteks, dan kriteria</em>
                    sebelum identifikasi risiko dimulai.
                </small>
            </div>
            <div class="col-md-4 text-md-right">
                <?php if ($boleh_tulis) : ?>
                    <a href="<?= base_url('manrisk/lingkup/form') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Lingkup
                    </a>
                <?php endif; ?>
                <a href="<?= base_url('manrisk/objektif') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-bullseye"></i> Objektif
                </a>
            </div>
        </div>

        <?php if (!$daftar) : ?>
            <div class="card card-outline card-warning">
                <div class="card-body text-center text-muted py-5">
                    <i class="fas fa-file-alt fa-3x mb-3"></i>
                    <p>Belum ada dokumen lingkup untuk tahun <?= $tahun ?>.</p>
                    <?php if ($boleh_tulis) : ?>
                        <a href="<?= base_url('manrisk/lingkup/form') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Buat Dokumen Lingkup
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php foreach ($daftar as $l) : ?>
            <div class="card card-outline card-primary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <?= html_escape($l->nama_lingkup) ?>
                        <span class="badge badge-<?= $l->status === 'FINAL' ? 'success' : 'secondary' ?> ml-2"><?= html_escape($l->status) ?></span>
                        <span class="badge badge-info ml-1">TW<?= (int) $l->triwulan ?></span>
                    </h5>
                    <div>
                        <?php if ($boleh_tulis) : ?>
                            <a href="<?= base_url('manrisk/lingkup/form/' . $l->id_lingkup) ?>" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit"></i> Ubah
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <th style="width:160px">Objek Evaluasi</th>
                                        <td><?= html_escape($l->objek_evaluasi ?: '-') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Batas Evaluasi</th>
                                        <td><?= html_escape($l->batas_evaluasi ?: '-') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Konteks Internal</th>
                                        <td><?= html_escape($l->konteks_internal ?: '-') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Konteks Eksternal</th>
                                        <td><?= html_escape($l->konteks_eksternal ?: '-') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Kriteria Acuan</th>
                                        <td><?= html_escape($l->kriteria_acuan ?: '-') ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <th style="width:160px">Tgl. Konsultasi</th>
                                        <td><?= html_escape($l->tanggal_konsultasi ?: '-') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Peserta</th>
                                        <td><?= html_escape($l->peserta ?: '-') ?></td>
                                    </tr>
                                    <tr>
                                        <th>PIC Konsultasi</th>
                                        <td><?= html_escape($l->pic_konsultasi ?: '-') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Hasil Konsultasi</th>
                                        <td><?= html_escape($l->hasil_konsultasi ?: '-') ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php if ($l->q_sasaran || $l->q_risiko || $l->q_kontrol || $l->q_tindakan) : ?>
                        <div class="row mt-3">
                            <?php foreach ([
                                'q_sasaran'  => 'Sasaran',
                                'q_risiko'   => 'Risiko',
                                'q_kontrol'  => 'Kontrol',
                                'q_tindakan' => 'Tindakan',
                            ] as $k => $label) : ?>
                                <?php if ($l->{$k}) : ?>
                                    <div class="col-md-6 mb-2">
                                        <div class="border rounded p-2 h-100">
                                            <strong class="small text-primary"><?= $label ?></strong>
                                            <p class="mb-0 small"><?= html_escape($l->{$k}) ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- ============== RINGKASAN OBJEKTIF ============== -->
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <strong>Objektif RKAP <?= $tahun ?></strong>
                <span class="badge badge-success ml-1"><?= isset($rekap['SELESAI']) ? $rekap['SELESAI'] : 0 ?> selesai</span>
                <span class="badge badge-warning ml-1"><?= isset($rekap['BELUM']) ? $rekap['BELUM'] : 0 ?> belum</span>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Indikator</th>
                            <th class="text-center">Target</th>
                            <th class="text-center">Satuan</th>
                            <th class="text-center">Rekonsiliasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$objektif) : ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada objektif.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($objektif as $o) : ?>
                            <tr>
                                <td><small><?= html_escape($o->indikator) ?></small></td>
                                <td class="text-center"><?= $o->nilai_rkap !== null ? html_escape(rtrim(rtrim(number_format((float) $o->nilai_rkap, 2, ',', '.'), '0'), '.')) : '—' ?></td>
                                <td class="text-center"><small><?= html_escape($o->satuan ?: '-') ?></small></td>
                                <td class="text-center">
                                    <span class="badge <?= $o->status_rekonsiliasi === 'SELESAI' ? 'badge-success' : 'badge-warning' ?>">
                                        <?= html_escape($o->status_rekonsiliasi) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============== RINGKASAN RISIKO PER UNIT ============== -->
        <div class="card card-outline card-info">
            <div class="card-header"><strong>Risiko <?= $tahun ?> per Unit</strong></div>
            <div class="card-body table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode</th>
                            <th>Risiko</th>
                            <th>Unit</th>
                            <th class="text-center">Inherent</th>
                            <th class="text-center">Residual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$risiko) : ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada risiko.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($risiko as $r) : ?>
                            <tr>
                                <td><small><a href="<?= base_url('manrisk/register/detail/' . $r->id_register) ?>"><?= html_escape($r->kode_risiko) ?></a></small></td>
                                <td><small><?= html_escape($r->risiko) ?></small></td>
                                <td><small><?= html_escape($r->nama_bagian ?: '-') ?></small></td>
                                <td class="text-center"><?= $r->nilai_inherent !== null ? (int) $r->nilai_inherent : '—' ?></td>
                                <td class="text-center"><?= $r->nilai_residual !== null ? (int) $r->nilai_residual : '—' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
</div>