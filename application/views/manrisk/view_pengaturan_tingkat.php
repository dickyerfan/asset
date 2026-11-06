<?php

/**
 * Pengaturan · Skala Level Risiko — mri_tingkat_risiko.
 * Rentang skor (L x D) tidak boleh tumpang tindih; level dipakai label
 * pada analisa, matriks, dan evaluasi.
 */
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <div class="row align-items-center mb-2">
            <div class="col-md-8">
                <h4 class="mb-0"><?= strtoupper($title) ?></h4>
                <small class="text-muted">
                    Menentukan label setiap skor L &times; D. Rentang skor antar level tidak boleh tumpang tindih.
                </small>
            </div>
            <div class="col-md-4 text-md-right">
                <a href="<?= base_url('manrisk/pengaturan') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Pengaturan
                </a>
                <a href="<?= base_url('manrisk/pengaturan/kategori') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-tags"></i> Kategori
                </a>
            </div>
        </div>

        <?php if (!$akses_semua) : ?>
            <div class="alert alert-secondary">
                <i class="fas fa-info-circle"></i>
                Hanya <strong>Administrator</strong>, <strong>Keuangan</strong>, <strong>Publik</strong>, dan
                <strong>Auditor</strong> yang dapat mengubah skala level.
            </div>
        <?php endif; ?>

        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong><i class="fas fa-layer-group"></i> Skala Level Risiko</strong>
                <span class="badge badge-light"><?= count($tingkat) ?> level</span>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="width:80px">Urut</th>
                            <th>Level</th>
                            <th class="text-center" style="width:110px">Skor</th>
                            <th class="text-center" style="width:90px">Warna</th>
                            <th class="text-center" style="width:110px">Aksi</th>
                            <th>Deskripsi</th>
                            <th class="text-center" style="width:110px">Status</th>
                            <?php if ($akses_semua) : ?><th class="text-center" style="width:150px">Kelola</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$tingkat) : ?>
                            <tr>
                                <td colspan="<?= $akses_semua ? 8 : 7 ?>" class="text-center text-muted py-4">Belum ada level risiko.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($tingkat as $t) : ?>
                            <tr>
                                <td class="text-center"><?= (int) $t->urut_tampil ?></td>
                                <td><strong><?= html_escape($t->nama_tingkat) ?></strong></td>
                                <td class="text-center"><?= (int) $t->skor_min ?>&ndash;<?= (int) $t->skor_max ?></td>
                                <td class="text-center">
                                    <span class="badge" style="background-color:<?= html_escape($t->warna ?: '#cccccc') ?>;color:#fff">
                                        <?= html_escape($t->warna ?: '-') ?>
                                    </span>
                                </td>
                                <td class="text-center"><small><?= html_escape($t->aksi) ?></small></td>
                                <td><small><?= html_escape($t->deskripsi ?: '-') ?></small></td>
                                <td class="text-center">
                                    <span class="badge badge-<?= $t->status ? 'success' : 'secondary' ?>"><?= $t->status ? 'AKTIF' : 'NONAKTIF' ?></span>
                                </td>
                                <?php if ($akses_semua) : ?>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#modalTingkat" data-id="<?= (int) $t->id_tingkat ?>" data-nama="<?= html_escape($t->nama_tingkat) ?>" data-min="<?= (int) $t->skor_min ?>" data-max="<?= (int) $t->skor_max ?>" data-urut="<?= (int) $t->urut_tampil ?>" data-warna="<?= html_escape((string) $t->warna) ?>" data-aksi="<?= html_escape($t->aksi) ?>" data-deskripsi="<?= html_escape((string) $t->deskripsi) ?>" data-status="<?= (int) $t->status ?>">
                                            <i class="fas fa-pen"></i> Ubah
                                        </button>
                                        <a href="<?= base_url('manrisk/pengaturan/tingkat_hapus/' . (int) $t->id_tingkat) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus level <?= html_escape(addslashes($t->nama_tingkat)) ?>? Tindakan ini tidak dapat dibatalkan.');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($akses_semua) : ?>
                <div class="card-footer">
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalTingkat" data-id="0" data-nama="" data-min="" data-max="" data-urut="0" data-warna="#28a745" data-aksi="Monitor" data-deskripsi="" data-status="1">
                        <i class="fas fa-plus"></i> Tambah Level
                    </button>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>
</div>
<?php if ($akses_semua) : ?>
    <div class="modal fade" id="modalTingkat" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <?= form_open('manrisk/pengaturan/tingkat_simpan', 'id="form-tingkat"') ?>
                <input type="hidden" name="id_tingkat" id="tk_id" value="0">
                <div class="modal-header">
                    <h5 class="modal-title">Level Risiko — <span id="tk_judul">Tambah</span></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="tk_nama">Nama Level <span class="text-danger">*</span></label>
                            <input type="text" name="nama_tingkat" id="tk_nama" class="form-control" maxlength="50" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tk_urut">Urut Tampil</label>
                            <input type="number" name="urut_tampil" id="tk_urut" class="form-control" min="0">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="tk_min">Skor Min <span class="text-danger">*</span></label>
                            <input type="number" name="skor_min" id="tk_min" class="form-control" min="1" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tk_max">Skor Max <span class="text-danger">*</span></label>
                            <input type="number" name="skor_max" id="tk_max" class="form-control" min="1" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tk_warna">Warna</label>
                            <input type="color" name="warna" id="tk_warna" class="form-control" value="#28a745">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tk_aksi">Aksi Level</label>
                        <select name="aksi" id="tk_aksi" class="form-control">
                            <option value="Accept">Accept</option>
                            <option value="Monitor" selected>Monitor</option>
                            <option value="Treat">Treat</option>
                            <option value="Escalate">Escalate</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tk_deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="tk_deskripsi" rows="2" class="form-control"></textarea>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="status" id="tk_status" value="1" class="custom-control-input" checked>
                        <label class="custom-control-label" for="tk_status"><strong>Aktif</strong></label>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#modalTingkat').on('show.bs.modal', function(e) {
            var b = $(e.relatedTarget);
            var id = b.data('id') || 0;
            $('#tk_id').val(id);
            $('#tk_judul').text(id ? 'Ubah' : 'Tambah');
            $('#tk_nama').val(b.data('nama') || '');
            $('#tk_min').val(b.data('min') || '');
            $('#tk_max').val(b.data('max') || '');
            $('#tk_urut').val(b.data('urut') || 0);
            $('#tk_warna').val(b.data('warna') || '#28a745');
            $('#tk_aksi').val(b.data('aksi') || 'Monitor');
            $('#tk_deskripsi').val(b.data('deskripsi') || '');
            $('#tk_status').prop('checked', String(b.data('status')) !== '0');
        });
    });
</script>