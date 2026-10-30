<?php
/**
 * Daftar monitoring risiko per unit (ISO 31000 Klausa 6.7).
 */
$ada = 0;
foreach ($baris as $b) {
    if ($b->terakhir) {
        $ada++;
    }
}
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <div class="row align-items-center mb-2">
            <div class="col-md-8">
                <h4 class="mb-0"><?= strtoupper($title) ?> <?= $tahun ?></h4>
                <small class="text-muted">
                    <?= count($baris) ?> risiko terpantau &middot;
                    <?= $ada ?> sudah memiliki catatan monitoring.
                </small>
            </div>
            <div class="col-md-4 text-md-right">
                <a href="<?= base_url('manrisk/perlakuan') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-tools"></i> Perlakuan
                </a>
                <a href="<?= base_url('manrisk/register') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-list"></i> Register
                </a>
            </div>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover" id="tabel_monitoring" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode</th>
                            <th>Risiko</th>
                            <th>Unit</th>
                            <th class="text-center">Residual</th>
                            <th class="text-center">TW Terakhir</th>
                            <th>Hasil Monitoring Terakhir</th>
                            <th class="text-center">Kontrol Efektif</th>
                            <th class="text-center">Reassess</th>
                            <th class="text-center">Jumlah Catatan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$baris) : ?>
                            <tr><td colspan="10" class="text-center text-muted py-4">Tidak ada risiko sesuai filter.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($baris as $b) :
                            $r = $b->risiko;
                            $m = $b->terakhir;
                        ?>
                            <tr>
                                <td><a href="<?= base_url('manrisk/register/detail/' . $r->id_register) ?>"><strong><?= html_escape($r->kode_risiko) ?></strong></a></td>
                                <td><?= html_escape($r->risiko) ?></td>
                                <td><small><?= html_escape($r->nama_bagian ?: '-') ?></small></td>
                                <td class="text-center">
                                    <?= $r->nilai_residual !== null ? '<strong>' . (int) $r->nilai_residual . '</strong>' : '<span class="text-muted">—</span>' ?>
                                    <?php if ($r->level_residual) : ?>
                                        <br><small><?= html_escape($r->level_residual) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= $m ? 'TW' . (int) $m->triwulan : '—' ?></td>
                                <td><small><?= $m ? html_escape($m->hasil_monitoring ?: '-') : '<span class="text-muted">belum ada catatan</span>' ?></small></td>
                                <td class="text-center">
                                    <?php if ($m) : ?>
                                        <span class="badge <?= $m->kontrol_masih_efektif === 'YA' ? 'badge-success' : ($m->kontrol_masih_efektif === 'TIDAK' ? 'badge-danger' : 'badge-warning') ?>">
                                            <?= html_escape($m->kontrol_masih_efektif) ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($m) : ?>
                                        <?= $m->perlu_reassess ? '<span class="badge badge-warning">YA</span>' : 'TIDAK' ?>
                                    <?php else : ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= count($b->riwayat) ?></td>
                                <td class="text-center text-nowrap">
                                    <?php if ($boleh_tulis) : ?>
                                        <a href="<?= base_url('manrisk/monitoring/form/' . $r->id_register) ?>"
                                           class="btn btn-sm btn-<?= $m ? 'outline-secondary' : 'primary' ?>">
                                            <i class="fas fa-<?= $m ? 'plus' : 'edit' ?>"></i>
                                            <?= $m ? 'Tambah' : 'Catat' ?>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============== ARTI STATUS ============== -->
        <div class="card card-outline card-info">
            <div class="card-header"><strong>Makna Status Monitoring</strong></div>
            <div class="card-body py-2">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="thead-light">
                        <tr><th style="width:140px">Kontrol Efektif</th><th>Tindak Lanjut</th></tr>
                    </thead>
                    <tbody>
                        <tr><td><span class="badge badge-success">YA</span></td>
                            <td><small>Kontrol berjalan sesuai rancangan; pertahankan dan pantau berkala.</small></td></tr>
                        <tr><td><span class="badge badge-warning">SEBAGIAN</span></td>
                            <td><small>Kontrol berjalan namun belum menyeluruh — perkuat atau perluas cakupan.</small></td></tr>
                        <tr><td><span class="badge badge-danger">TIDAK</span></td>
                            <td><small>Kontrol tidak efektif — wajib ditinjau ulang, skor dinaikkan, dan risiko dievaluasi lagi.</small></td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#tabel_monitoring').DataTable({
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
