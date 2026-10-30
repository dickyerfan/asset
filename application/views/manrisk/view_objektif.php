<?php
/**
 * Daftar objektif RKAP + rekonsiliasi keterkaitan risiko.
 */
$selesai = isset($rekap['SELESAI']) ? $rekap['SELESAI'] : 0;
$belum   = isset($rekap['BELUM']) ? $rekap['BELUM'] : 0;
$total   = $selesai + $belum;
$persen  = $total ? round($selesai * 100 / $total) : 0;
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner"><h3><?= $total ?></h3><p>Total Objektif</p></div>
                    <div class="icon"><i class="fas fa-bullseye"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner"><h3><?= $selesai ?></h3><p>Sudah Direkonsiliasi</p></div>
                    <div class="icon"><i class="fas fa-check-double"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner"><h3><?= $belum ?></h3><p>Belum Direkonsiliasi</p></div>
                    <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner"><h3><?= $persen ?>%</h3><p>Progres Rekonsiliasi</p></div>
                    <div class="icon"><i class="fas fa-chart-line"></i></div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-header">
                <form method="get" action="<?= base_url('manrisk/objektif') ?>" class="form-inline">
                    <div class="form-group mr-2 mb-2">
                        <label for="f_sts" class="mr-2">Rekonsiliasi</label>
                        <select name="status_rekonsiliasi" id="f_sts" class="form-control">
                            <option value="">Semua</option>
                            <option value="BELUM" <?= isset($filter['status_rekonsiliasi']) && $filter['status_rekonsiliasi'] === 'BELUM' ? 'selected' : '' ?>>BELUM</option>
                            <option value="SELESAI" <?= isset($filter['status_rekonsiliasi']) && $filter['status_rekonsiliasi'] === 'SELESAI' ? 'selected' : '' ?>>SELESAI</option>
                        </select>
                    </div>
                    <button type="submit" class="neumorphic-button mb-2"><i class="fas fa-filter"></i> Tampilkan</button>
                    <a href="<?= base_url('manrisk/objektif') ?>" class="neumorphic-button mb-2 ml-2"><i class="fas fa-undo"></i> Reset</a>
                </form>
            </div>
        </div>

        <div class="row align-items-center mb-2">
            <div class="col-md-8">
                <h4 class="mb-0"><?= strtoupper($title) ?> <?= $tahun ?></h4>
                <small class="text-muted">
                    Objektif adalah acuan <em>"kenapa risiko ini penting"</em> (ISO 31000 Klausa 6.3 — sasaran organisasi).
                </small>
            </div>
            <div class="col-md-4 text-md-right">
                <?php if ($boleh_tulis) : ?>
                    <a href="<?= base_url('manrisk/objektif/tambah') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus-circle"></i> Tambah Objektif
                    </a>
                <?php endif; ?>
                <a href="<?= base_url('manrisk/lingkup') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-expand-arrows-alt"></i> Lingkup
                </a>
                <a href="<?= base_url('manrisk/register') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-list"></i> Register
                </a>
            </div>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover" id="tabel_objektif" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th>Bagian</th>
                            <th>Indikator Objektif</th>
                            <th class="text-center">Baseline 2026</th>
                            <th class="text-center">Target RKAP <?= $tahun ?></th>
                            <th class="text-center">Satuan</th>
                            <th>Sumber Nilai</th>
                            <th class="text-center">Risiko Terikat</th>
                            <th class="text-center">Rekonsiliasi</th>
                            <?php if ($boleh_tulis) : ?><th class="text-center">Aksi</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$daftar) : ?>
                            <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada objektif sesuai filter.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($daftar as $o) : ?>
                            <tr>
                                <td><small><strong><?= html_escape($o->nama_bagian ?: '—') ?></strong></small></td>
                                <td><?= html_escape($o->indikator) ?></td>
                                <td class="text-center"><?= $o->baseline_2026 !== null ? html_escape(rtrim(rtrim(number_format((float) $o->baseline_2026, 2, ',', '.'), '0'), '.')) : '—' ?></td>
                                <td class="text-center"><strong><?= $o->nilai_rkap !== null ? html_escape(rtrim(rtrim(number_format((float) $o->nilai_rkap, 2, ',', '.'), '0'), '.')) : '—' ?></strong></td>
                                <td class="text-center"><small><?= html_escape($o->satuan ?: '-') ?></small></td>
                                <td><small><?= html_escape($o->sumber_nilai ?: '-') ?></small></td>
                                <td class="text-center">
                                    <?php $n = isset($terikat[(int) $o->id_objektif]) ? $terikat[(int) $o->id_objektif] : 0; ?>
                                    <span class="badge <?= $n ? 'badge-primary' : 'badge-secondary' ?>"><?= $n ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $o->status_rekonsiliasi === 'SELESAI' ? 'badge-success' : 'badge-warning' ?>">
                                        <?= html_escape($o->status_rekonsiliasi) ?>
                                    </span>
                                    <?php if ($o->catatan_rekonsiliasi) : ?>
                                        <br><small class="text-muted"><?= html_escape($o->catatan_rekonsiliasi) ?></small>
                                    <?php endif; ?>
                                </td>
                                <?php if ($boleh_tulis) : ?>
                                    <td class="text-center">
                                        <div class="mb-1">
                                            <a href="<?= base_url('manrisk/objektif/ubah/' . (int) $o->id_objektif) ?>"
                                               class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-pen"></i> Ubah
                                            </a>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                data-toggle="modal" data-target="#modalRekonsiliasi"
                                                data-id="<?= (int) $o->id_objektif ?>"
                                                data-indikator="<?= html_escape($o->indikator) ?>"
                                                data-status="<?= html_escape($o->status_rekonsiliasi) ?>"
                                                data-nilai="<?= $o->nilai_rkap !== null ? html_escape($o->nilai_rkap) : '' ?>">
                                            <i class="fas fa-check"></i> Rekonsiliasi
                                        </button>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>

<?php if ($boleh_tulis) : ?>
<!-- ============== MODAL REKONSILIASI ============== -->
<div class="modal fade" id="modalRekonsiliasi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= form_open('manrisk/objektif/rekonsiliasi/0', 'id="form-rekon"') ?>
            <input type="hidden" name="id_objektif" id="rekon_id" value="">
            <div class="modal-header">
                <h5 class="modal-title">Rekonsiliasi Objektif</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="mb-3"><strong id="rekon_indikator"></strong></p>
                <div class="form-group">
                    <label for="rekon_status">Status Rekonsiliasi <span class="text-danger">*</span></label>
                    <select name="status_rekonsiliasi" id="rekon_status" class="form-control" required>
                        <option value="BELUM">BELUM</option>
                        <option value="SELESAI">SELESAI</option>
                    </select>
                    <small class="text-muted">
                        <em>SELESAI</em> = angka RKAP sudah dikonfirmasi. <em>BELUM</em> = masih menunggu
                        konfirmasi / terdapat selisih antar sumber.
                    </small>
                </div>
                <div class="form-group">
                    <label for="nilai_realisasi">Nilai Realisasi / RKAP Terbaru <span class="text-danger">*</span></label>
                    <input type="text" name="nilai_realisasi" id="nilai_realisasi" class="form-control" required>
                </div>
                <div class="form-group mb-0">
                    <label for="alasan_rekonsiliasi">Alasan / Catatan</label>
                    <textarea name="alasan_rekonsiliasi" id="alasan_rekonsiliasi" rows="3" class="form-control"
                              placeholder="Dasar penyesuaian angka atau konfirmasi keterkaitan risiko"></textarea>
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
    $('#tabel_objektif').DataTable({
        responsive: true, pageLength: 25, order: [[0, 'asc']],
        language: {
            search: "Cari:", lengthMenu: "Tampilkan _MENU_",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_",
            infoEmpty: "Belum ada data", infoFiltered: "(disaring dari _MAX_)",
            zeroRecords: "Tidak ada data yang cocok",
            paginate: {first: "Awal", last: "Akhir", next: "&raquo;", previous: "&laquo;"}
        }
    });

    $('#modalRekonsiliasi').on('show.bs.modal', function (e) {
        var b = $(e.relatedTarget);
        var id = b.attr('data-id');
        $('#rekon_id').val(id);
        $('#rekon_indikator').text(b.attr('data-indikator'));
        $('#rekon_status').val(b.attr('data-status') || 'BELUM');
        $('#nilai_realisasi').val(b.attr('data-nilai'));
        $('#form-rekon').attr('action', '<?= base_url('manrisk/objektif/rekonsiliasi/') ?>' + id);
    });
});
</script>
