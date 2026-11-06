<?php

/**
 * Daftar laporan manajemen risiko (§10.1): Operasional, Manajemen,
 * Dewan Pengawas, Tahunan.
 *
 * Laporan dapat disusun otomatis dari data Register sampai Monitoring.
 */
$progres = isset($ringkasan['progres']) ? $ringkasan['progres'] : [];
$totalPerlakuan = array_sum($progres);
$selesaiPerlakuan = isset($progres['SELESAI']) ? (int) $progres['SELESAI'] : 0;
$tinggi = isset($ringkasan['level_residual']['Tinggi']) ? (int) $ringkasan['level_residual']['Tinggi'] : 0;
$totalRisiko = isset($ringkasan['total_risiko']) ? (int) $ringkasan['total_risiko'] : 0;
?>
<section class="content">
    <div class="container-fluid">

        <?php // Pesan Laporan (tanpa .alert agar tidak hilang otomatis). 
        ?>
        <?= $this->session->flashdata('info_laporan'); ?>
        <?= $this->session->unset_userdata('info_laporan'); ?>

        <!-- ============== RINGKASAN ============== -->
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= $totalRisiko ?></h3>
                        <p>Total Risiko</p>
                    </div>
                    <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= $tinggi ?></h3>
                        <p>Risiko Residual Tinggi</p>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $selesaiPerlakuan ?>/<?= $totalPerlakuan ?></h3>
                        <p>Perlakuan Selesai</p>
                    </div>
                    <div class="icon"><i class="fas fa-check-double"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3><?= count($daftar) ?></h3>
                        <p>Laporan Tersedia</p>
                    </div>
                    <div class="icon"><i class="fas fa-file-alt"></i></div>
                </div>
            </div>
        </div>

        <div class="row align-items-center mb-2">
            <div class="col-md-8">
                <h4 class="mb-0"><?= strtoupper($title) ?> <?= $tahun ?></h4>
                <small class="text-muted">
                    Empat jenis laporan &middot;
                    status <strong>DRAFT</strong> belum terikat, <strong>FINAL</strong> sudah disahkan.
                </small>
            </div>
            <div class="col-md-4 text-md-right">
                <?php if ($boleh_tulis) : ?>
                    <a href="<?= base_url('manrisk/laporan/form') ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Manual
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- ============== SUSUN OTOMATIS ============== -->
        <?php if ($boleh_tulis) : ?>
            <div class="card card-outline card-info">
                <div class="card-header">
                    <strong><i class="fas fa-magic"></i> Susun Laporan Otomatis</strong>
                </div>
                <div class="card-body py-3">
                    <p class="small text-muted mb-2">
                        Seluruh bagian laporan disusun dari data Register, Analisis, Evaluasi, Perlakuan,
                        dan Monitoring. Klik berulang akan memperbarui draf yang sama; laporan
                        <strong>FINAL</strong> tidak ditimpa.
                    </p>
                    <?= form_open('manrisk/laporan/otomatis', 'class="form-row align-items-end"') ?>
                    <div class="form-group col-md-2 mb-2">
                        <label for="ot_tahun">Tahun</label>
                        <input type="number" name="tahun" id="ot_tahun" class="form-control" min="2027" max="2099" value="<?= (int) $tahun ?>" required>
                    </div>
                    <div class="form-group col-md-2 mb-2">
                        <label for="ot_triwulan">Triwulan</label>
                        <select name="triwulan" id="ot_triwulan" class="form-control">
                            <?php for ($t = 1; $t <= 4; $t++) : ?>
                                <option value="<?= $t ?>" <?= $tw_aktif === $t ? 'selected' : '' ?>>TW<?= $t ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3 mb-2">
                        <label for="ot_jenis">Jenis Laporan</label>
                        <select name="jenis_laporan" id="ot_jenis" class="form-control">
                            <?php foreach ($jenis as $j) : ?>
                                <option value="<?= html_escape($j) ?>"><?= html_escape($j) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if ($akses_semua) : ?>
                        <div class="form-group col-md-3 mb-2">
                            <label for="ot_unit">Cakupan</label>
                            <select name="id_upk" id="ot_unit" class="form-control">
                                <option value="">— seluruh organisasi —</option>
                                <?php foreach ($unit_list as $u) : ?>
                                    <option value="<?= (int) $u->id_bagian ?>"><?= html_escape($u->nama_bagian) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="form-group col-md-2 mb-2">
                        <button type="submit" class="btn btn-info btn-block" onclick="return confirm('Susun laporan otomatis dari data? Draf yang ada untuk periode ini akan diperbarui.');">
                            <i class="fas fa-magic"></i> Susun
                        </button>
                    </div>
                    <?= form_close() ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="card card-outline card-primary">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover" id="tabel_laporan" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th>Jenis</th>
                            <th class="text-center">TW</th>
                            <th>Unit</th>
                            <th>Ringkasan Eksekutif</th>
                            <th>Kesimpulan</th>
                            <th class="text-center">Status</th>
                            <th>Disahkan Oleh</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$daftar) : ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada laporan. Gunakan <strong>Susun Laporan Otomatis</strong> di atas.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($daftar as $l) : ?>
                            <tr>
                                <td><strong><?= html_escape($l->jenis_laporan) ?></strong> <?= $l->tahun ?></td>
                                <td class="text-center"><?= (int) $l->triwulan ?></td>
                                <td><small><?= html_escape($l->nama_bagian ?: '-') ?></small></td>
                                <td><small><?= html_escape(mb_substr((string) $l->ringkasan_eksekutif, 0, 120)) ?><?= mb_strlen((string) $l->ringkasan_eksekutif) > 120 ? '…' : '' ?></small></td>
                                <td><small><?= html_escape(mb_substr((string) $l->kesimpulan, 0, 100)) ?><?= mb_strlen((string) $l->kesimpulan) > 100 ? '…' : '' ?></small></td>
                                <td class="text-center">
                                    <span class="badge badge-<?= $l->status === 'FINAL' ? 'success' : 'secondary' ?>"><?= html_escape($l->status) ?></span>
                                </td>
                                <td><small><?= html_escape($l->disahkan_oleh ?: '-') ?>
                                        <?php if ($l->tanggal_sahkan) : ?><br><?= html_escape($l->tanggal_sahkan) ?><?php endif; ?>
                                    </small></td>
                                <td class="text-center text-nowrap">
                                    <?php if ($boleh_tulis) : ?>
                                        <a href="<?= base_url('manrisk/laporan/form/' . $l->id_laporan) ?>" class="btn btn-sm btn-outline-secondary" title="Ubah">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($l->status !== 'FINAL') : ?>
                                            <a href="<?= base_url('manrisk/laporan/sahkan/' . $l->id_laporan) ?>" class="btn btn-sm btn-outline-success" title="Sahkan" onclick="return confirm('Sahkan laporan <?= html_escape($l->jenis_laporan) ?> <?= $l->tahun ?>? Setelah disahkan status menjadi FINAL.');">
                                                <i class="fas fa-stamp"></i>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <a href="<?= base_url('manrisk/laporan/cetak_pdf/' . $l->id_laporan) ?>" class="btn btn-sm btn-danger" title="Cetak / PDF" target="_blank">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                </td>
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
        $('#tabel_laporan').DataTable({
            responsive: true,
            pageLength: 25,
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