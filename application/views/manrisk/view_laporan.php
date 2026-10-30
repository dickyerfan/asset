<?php
/**
 * Daftar laporan manajemen risiko (§10.1): Operasional, Manajemen,
 * Dewan Pengawas, Tahunan.
 */
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <!-- ============== RINGKASAN ============== -->
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= isset($ringkasan['total']) ? $ringkasan['total'] : 0 ?></h3>
                        <p>Total Risiko</p>
                    </div>
                    <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= isset($rekapKri['Tidak Tercapai']) ? $rekapKri['Tidak Tercapai'] : 0 ?></h3>
                        <p>KRI Tidak Tercapai</p>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= isset($rekapKri['Perlu Perhatian']) ? $rekapKri['Perlu Perhatian'] : 0 ?></h3>
                        <p>KRI Perlu Perhatian</p>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= isset($rekapKri['Tercapai']) ? $rekapKri['Tercapai'] : 0 ?></h3>
                        <p>KRI Tercapai</p>
                    </div>
                    <div class="icon"><i class="fas fa-check-double"></i></div>
                </div>
            </div>
        </div>

        <div class="row align-items-center mb-2">
            <div class="col-md-8">
                <h4 class="mb-0"><?= strtoupper($title) ?> <?= $tahun ?></h4>
                <small class="text-muted">
                    Empat jenis laporan sesuai dokumentasi MR §10.1 &middot;
                    status <strong>DRAFT</strong> belum terikat, <strong>FINAL</strong> sudah disahkan.
                </small>
            </div>
            <div class="col-md-4 text-md-right">
                <?php if ($boleh_tulis) : ?>
                    <a href="<?= base_url('manrisk/laporan/form') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Laporan
                    </a>
                <?php endif; ?>
                <a href="<?= base_url('manrisk/kri') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-tachometer-alt"></i> KRI
                </a>
            </div>
        </div>

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
                            <tr><td colspan="8" class="text-center text-muted py-4">Belum ada laporan.</td></tr>
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
                                            <a href="<?= base_url('manrisk/laporan/sahkan/' . $l->id_laporan) ?>" class="btn btn-sm btn-outline-success" title="Sahkan"
                                               onclick="return confirm('Sahkan laporan <?= html_escape($l->jenis_laporan) ?> <?= $l->tahun ?>? Setelah disahkan status menjadi FINAL.');">
                                                <i class="fas fa-stamp"></i>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
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
document.addEventListener('DOMContentLoaded', function () {
    $('#tabel_laporan').DataTable({
        responsive: true, pageLength: 25, order: [[0, 'asc']],
        language: {
            search: "Cari:", lengthMenu: "Tampilkan _MENU_",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_",
            infoEmpty: "Belum ada data", infoFiltered: "(disaring dari _MAX_)",
            zeroRecords: "Tidak ada data yang cocok",
            paginate: {first: "Awal", last: "Akhir", next: "&raquo;", previous: "&laquo;"}
        }
    });
});
</script>
