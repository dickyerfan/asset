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

        <?php // Pesan simpan/sinkronisasi Monitoring (tanpa .alert agar tidak hilang otomatis). ?>
        <?= $this->session->flashdata('info_monitoring'); ?>
        <?= $this->session->unset_userdata('info_monitoring'); ?>

        <div class="row align-items-center mb-2">
            <div class="col-md-8">
                <h4 class="mb-0"><?= strtoupper($title) ?> <?= $tahun ?></h4>
                <small class="text-muted">
                    <?= count($baris) ?> risiko terpantau &middot;
                    <?= $ada ?> sudah memiliki catatan monitoring.
                </small>
                <small class="text-muted d-block">
                    <i class="fas fa-edit"></i> <strong>Ubah</strong> = koreksi catatan terakhir &middot;
                    <i class="fas fa-history"></i> <strong>angka</strong> = riwayat semua catatan (bisa diubah/dihapus)
                </small>
                <small class="text-muted d-block">
                    <i class="fas fa-calculator"></i> Skor Lama terkunci; Skor Baru diisi <strong>L &times; D</strong>
                    langsung di form Monitoring &mdash; centang <strong>Perbarui Analisa</strong> agar residual ikut berubah.
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
                                <td class="text-center">
                                    <?php if (count($b->riwayat)) : ?>
                                        <a href="<?= base_url('manrisk/monitoring/form/' . $r->id_register) ?>#riwayat"
                                           class="btn btn-sm btn-outline-secondary"
                                           title="Lihat dan ubah semua catatan monitoring risiko ini">
                                            <i class="fas fa-history"></i> <?= count($b->riwayat) ?>
                                        </a>
                                    <?php else : ?>
                                        <span class="text-muted">0</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center text-nowrap">
                                    <?php if ($boleh_tulis) : ?>
                                        <?php if ($m) : ?>
                                            <!-- Ubah catatan TERAKHIR langsung dari daftar.
                                                 Catatan lama bisa diubah lewat tombol riwayat (kolom Jumlah Catatan). -->
                                            <a href="<?= base_url('manrisk/monitoring/form/' . $r->id_register . '?id_monitoring=' . $m->id_monitoring) ?>"
                                               class="btn btn-sm btn-outline-secondary"
                                               title="Ubah catatan terakhir (TW<?= (int) $m->triwulan ?>)">
                                                <i class="fas fa-edit"></i> Ubah
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= base_url('manrisk/monitoring/form/' . $r->id_register) ?>"
                                           class="btn btn-sm btn-<?= $m ? 'outline-primary' : 'primary' ?>"
                                           title="<?= $m ? 'Tambah catatan monitoring baru' : 'Buat catatan monitoring pertama' ?>">
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
                <p class="small text-muted mb-0 mt-2">
                    <strong>Reassess (Perlu Tinjauan Ulang)</strong> adalah tanda <em>manual</em> dari catatan
                    monitoring bahwa risiko perlu dinilai ulang. Nilainya berasal dari centang
                    &ldquo;Perlu Tinjauan Ulang&rdquo; di form Monitoring — <strong>tidak dihitung otomatis</strong>
                    dan tidak memicu apa pun sendiri. Bila <span class="badge badge-warning">YA</span>,
                    tindak lanjutnya: perbarui <strong>Analisis</strong> (skor residual) lalu
                    <strong>Evaluasi</strong> (keputusan baru).
                </p>
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
