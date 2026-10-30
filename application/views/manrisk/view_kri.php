<?php
/**
 * Daftar Key Risk Indicator + pencatatan nilai aktual.
 *
 * Status KRI dihitung model; baris dengan nilai_aktual NULL tetap
 * "Belum Diukur" dan tidak dihakimi sebagai gagal.
 */
$warnaStatus = [
    'Belum Diukur'    => 'secondary',
    'Tercapai'        => 'success',
    'Tidak Tercapai'  => 'danger',
    'Perlu Perhatian' => 'warning',
];
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <!-- ============== REKAP ============== -->
        <div class="row">
            <?php foreach (['Belum Diukur', 'Tercapai', 'Tidak Tercapai', 'Perlu Perhatian'] as $s) : ?>
                <div class="col-lg-3 col-md-6 col-6">
                    <div class="small-box bg-<?= $warnaStatus[$s] === 'secondary' ? 'secondary' : ($warnaStatus[$s] === 'success' ? 'success' : ($warnaStatus[$s] === 'danger' ? 'danger' : 'warning')) ?>">
                        <div class="inner">
                            <h3><?= isset($rekap[$s]) ? $rekap[$s] : 0 ?></h3>
                            <p><?= html_escape($s) ?></p>
                        </div>
                        <div class="icon"><i class="fas fa-tachometer-alt"></i></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="row align-items-center mb-2">
            <div class="col-md-8">
                <h4 class="mb-0"><?= strtoupper($title) ?> <?= $tahun ?></h4>
                <small class="text-muted">
                    6 KRI sesuai dokumentasi MR §10.2 &middot;
                    status dihitung otomatis dari nilai aktual terhadap target.
                </small>
            </div>
            <div class="col-md-4 text-md-right">
                <?php if ($boleh_tulis) : ?>
                    <a href="<?= base_url('manrisk/kri/form') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah KRI
                    </a>
                <?php endif; ?>
                <a href="<?= base_url('manrisk/laporan') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-file-alt"></i> Laporan
                </a>
            </div>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover" id="tabel_kri" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama KRI</th>
                            <th>Risiko Terkait</th>
                            <th>Unit</th>
                            <th class="text-center">Target</th>
                            <th class="text-center">Aktual</th>
                            <th>Satuan</th>
                            <th class="text-center">Frekuensi</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$daftar) : ?>
                            <tr><td colspan="9" class="text-center text-muted py-4">Belum ada KRI untuk tahun ini.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($daftar as $k) : ?>
                            <tr>
                                <td><?= html_escape($k->nama_kri) ?>
                                    <?php if ($k->keterangan) : ?>
                                        <br><small class="text-muted"><?= html_escape($k->keterangan) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($k->kode_risiko) : ?>
                                        <a href="<?= base_url('manrisk/register/detail/' . $k->id_register) ?>">
                                            <small><strong><?= html_escape($k->kode_risiko) ?></strong></small>
                                        </a>
                                        <br><small class="text-muted"><?= html_escape($k->sasaran_kpi) ?></small>
                                    <?php else : ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td><small><?= html_escape($k->nama_bagian ?: '-') ?></small></td>
                                <td class="text-center"><?= $k->target !== null ? '<strong>' . html_escape(rtrim(rtrim(number_format((float) $k->target, 4, ',', '.'), '0'), '.')) . '</strong>' : '<span class="text-muted">belum ditetapkan</span>' ?></td>
                                <td class="text-center"><?= $k->nilai_aktual !== null ? html_escape(rtrim(rtrim(number_format((float) $k->nilai_aktual, 4, ',', '.'), '0'), '.')) : '<span class="text-muted">—</span>' ?></td>
                                <td><small><?= html_escape($k->satuan ?: '-') ?></small></td>
                                <td class="text-center"><small><?= html_escape($k->frekuensi ?: '-') ?></small></td>
                                <td class="text-center">
                                    <span class="badge badge-<?= isset($warnaStatus[$k->status_kri]) ? $warnaStatus[$k->status_kri] : 'secondary' ?>">
                                        <?= html_escape($k->status_kri) ?>
                                    </span>
                                </td>
                                <td class="text-center text-nowrap">
                                    <?php if ($boleh_tulis) : ?>
                                        <button type="button" class="btn btn-sm btn-outline-success"
                                                data-toggle="modal" data-target="#modalCatat"
                                                data-id="<?= (int) $k->id_kri ?>"
                                                data-nama="<?= html_escape($k->nama_kri) ?>"
                                                data-nilai="<?= $k->nilai_aktual !== null ? html_escape($k->nilai_aktual) : '' ?>"
                                                data-ket="<?= html_escape((string) $k->keterangan) ?>">
                                            <i class="fas fa-pen"></i> Catat
                                        </button>
                                        <a href="<?= base_url('manrisk/kri/form/' . $k->id_kri) ?>" class="btn btn-sm btn-outline-secondary" title="Ubah definisi">
                                            <i class="fas fa-cog"></i>
                                        </a>
                                        <a href="<?= base_url('manrisk/kri/hapus/' . $k->id_kri) ?>" class="btn btn-sm btn-outline-danger" title="Hapus"
                                           onclick="return confirm('Hapus KRI <?= html_escape($k->nama_kri) ?>?');">
                                            <i class="fas fa-trash"></i>
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
            <div class="card-header"><strong>Cara Status Dihitung</strong></div>
            <div class="card-body py-2">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="thead-light">
                        <tr><th style="width:160px">Status</th><th>Syarat</th></tr>
                    </thead>
                    <tbody>
                        <tr><td><span class="badge badge-secondary">Belum Diukur</span></td>
                            <td><small>Nilai aktual belum diisi — belum boleh dinilai.</small></td></tr>
                        <tr><td><span class="badge badge-success">Tercapai</span></td>
                            <td><small>Aktual memenuhi target (batas atas, kecuali satuan ber-<em>penurunan</em> maka batas bawah).</small></td></tr>
                        <tr><td><span class="badge badge-danger">Tidak Tercapai</span></td>
                            <td><small>Aktual melewati batas yang diizinkan target.</small></td></tr>
                        <tr><td><span class="badge badge-warning">Perlu Perhatian</span></td>
                            <td><small>Target belum ditetapkan sehingga status tidak bisa dihakimi.</small></td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>

<?php if ($boleh_tulis) : ?>
<!-- ============== MODAL CATAT NILAI ============== -->
<div class="modal fade" id="modalCatat" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= form_open('manrisk/kri/catat/0', 'id="form-catat"') ?>
            <div class="modal-header">
                <h5 class="modal-title">Catat Nilai KRI</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="mb-3"><strong id="catat_nama"></strong></p>
                <div class="form-group">
                    <label for="nilai_aktual">Nilai Aktual</label>
                    <input type="text" name="nilai_aktual" id="nilai_aktual" class="form-control" placeholder="mis. 12,5">
                    <small class="text-muted">Kosongkan bila benar-benar belum diukur.</small>
                </div>
                <div class="form-group mb-0">
                    <label for="ket_kri">Keterangan</label>
                    <textarea name="keterangan" id="ket_kri" rows="2" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
<?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#tabel_kri').DataTable({
        responsive: true, pageLength: 25, order: [[0, 'asc']],
        language: {
            search: "Cari:", lengthMenu: "Tampilkan _MENU_",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_",
            infoEmpty: "Belum ada data", infoFiltered: "(disaring dari _MAX_)",
            zeroRecords: "Tidak ada data yang cocok",
            paginate: {first: "Awal", last: "Akhir", next: "&raquo;", previous: "&laquo;"}
        }
    });

    $('#modalCatat').on('show.bs.modal', function (e) {
        var b = $(e.relatedTarget);
        $('#catat_nama').text(b.data('nama'));
        $('#nilai_aktual').val(b.data('nilai'));
        $('#ket_kri').val(b.data('ket'));
        $('#form-catat').attr('action', '<?= base_url('manrisk/kri/catat/') ?>' + b.data('id'));
    });
});
</script>
