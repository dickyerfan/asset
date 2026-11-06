<?php

/**
 * Pengaturan · Kategori Risiko — mri_kategori_risiko.
 * Satu risiko memakai satu kategori utama; kategori juga acuan risk appetite.
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
                    Taksonomi risiko sesuai konteks organisasi. Kode kategori harus unik (maks. 10 karakter).
                </small>
            </div>
            <div class="col-md-4 text-md-right">
                <a href="<?= base_url('manrisk/pengaturan') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Pengaturan
                </a>
                <a href="<?= base_url('manrisk/pengaturan/tingkat') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-layer-group"></i> Skala Level
                </a>
            </div>
        </div>

        <?php if (!$akses_semua) : ?>
            <div class="alert alert-secondary">
                <i class="fas fa-info-circle"></i>
                Hanya <strong>Administrator</strong>, <strong>Keuangan</strong>, <strong>Publik</strong>, dan
                <strong>Auditor</strong> yang dapat mengubah kategori risiko.
            </div>
        <?php endif; ?>

        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong><i class="fas fa-tags"></i> Daftar Kategori</strong>
                <span class="badge badge-light"><?= count($kategori_list) ?> data</span>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width:90px">Kode</th>
                            <th>Nama Kategori</th>
                            <th>Alias</th>
                            <th>Legacy</th>
                            <th>Contoh</th>
                            <th class="text-center" style="width:70px">Urut</th>
                            <th class="text-center" style="width:110px">Status</th>
                            <?php if ($akses_semua) : ?><th class="text-center" style="width:150px">Aksi</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$kategori_list) : ?>
                            <tr>
                                <td colspan="<?= $akses_semua ? 8 : 7 ?>" class="text-center text-muted py-4">Belum ada data kategori.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($kategori_list as $k) : ?>
                            <tr>
                                <td><code><?= html_escape($k->kode_kat) ?></code></td>
                                <td>
                                    <strong><?= html_escape($k->nama_kat) ?></strong>
                                    <?php if ($k->deskripsi_kat) : ?>
                                        <br><small class="text-muted"><?= html_escape($k->deskripsi_kat) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= html_escape($k->nama_alias) ?></td>
                                <td><small><?= html_escape($k->kode_legacy ?: '-') ?></small></td>
                                <td><small><?= html_escape($k->contoh_kat ?: '-') ?></small></td>
                                <td class="text-center"><?= (int) $k->urut ?></td>
                                <td class="text-center">
                                    <span class="badge badge-<?= $k->status ? 'success' : 'secondary' ?>"><?= $k->status ? 'AKTIF' : 'NONAKTIF' ?></span>
                                </td>
                                <?php if ($akses_semua) : ?>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#modalKategori" data-id="<?= (int) $k->id_kategori ?>" data-kode="<?= html_escape($k->kode_kat) ?>" data-nama="<?= html_escape($k->nama_kat) ?>" data-alias="<?= html_escape((string) $k->nama_alias) ?>" data-legacy="<?= html_escape((string) $k->kode_legacy) ?>" data-contoh="<?= html_escape((string) $k->contoh_kat) ?>" data-deskripsi="<?= html_escape((string) $k->deskripsi_kat) ?>" data-urut="<?= (int) $k->urut ?>" data-status="<?= (int) $k->status ?>">
                                            <i class="fas fa-pen"></i> Ubah
                                        </button>
                                        <a href="<?= base_url('manrisk/pengaturan/kategori_hapus/' . (int) $k->id_kategori) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus kategori <?= html_escape(addslashes($k->kode_kat . ' · ' . $k->nama_kat)) ?>? Tindakan ini tidak dapat dibatalkan.');">
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
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalKategori" data-id="0" data-kode="" data-nama="" data-alias="" data-legacy="" data-contoh="" data-deskripsi="" data-urut="0" data-status="1">
                        <i class="fas fa-plus"></i> Tambah Kategori
                    </button>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>
</div>
<?php if ($akses_semua) : ?>
    <div class="modal fade" id="modalKategori" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <?= form_open('manrisk/pengaturan/kategori_simpan', 'id="form-kategori"') ?>
                <input type="hidden" name="id_kategori" id="kt_id" value="0">
                <div class="modal-header">
                    <h5 class="modal-title">Kategori — <span id="kt_judul">Tambah</span></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="kt_kode">Kode <span class="text-danger">*</span></label>
                            <input type="text" name="kode_kat" id="kt_kode" class="form-control" maxlength="10" placeholder="STR" required>
                        </div>
                        <div class="form-group col-md-8">
                            <label for="kt_nama">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kat" id="kt_nama" class="form-control" maxlength="50" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="kt_alias">Nama Alias</label>
                            <input type="text" name="nama_alias" id="kt_alias" class="form-control" maxlength="50">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="kt_legacy">Kode Legacy</label>
                            <input type="text" name="kode_legacy" id="kt_legacy" class="form-control" maxlength="10">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="kt_urut">Urut</label>
                            <input type="number" name="urut" id="kt_urut" class="form-control" min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kt_contoh">Contoh Risiko</label>
                        <input type="text" name="contoh_kat" id="kt_contoh" class="form-control" maxlength="200">
                    </div>
                    <div class="form-group">
                        <label for="kt_deskripsi">Deskripsi</label>
                        <textarea name="deskripsi_kat" id="kt_deskripsi" rows="2" class="form-control"></textarea>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="status" id="kt_status" value="1" class="custom-control-input" checked>
                        <label class="custom-control-label" for="kt_status"><strong>Aktif</strong></label>
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
        $('#modalKategori').on('show.bs.modal', function(e) {
            var b = $(e.relatedTarget);
            var id = b.data('id') || 0;
            $('#kt_id').val(id);
            $('#kt_judul').text(id ? 'Ubah' : 'Tambah');
            $('#kt_kode').val(b.data('kode') || '');
            $('#kt_nama').val(b.data('nama') || '');
            $('#kt_alias').val(b.data('alias') || '');
            $('#kt_legacy').val(b.data('legacy') || '');
            $('#kt_contoh').val(b.data('contoh') || '');
            $('#kt_deskripsi').val(b.data('deskripsi') || '');
            $('#kt_urut').val(b.data('urut') || 0);
            $('#kt_status').prop('checked', String(b.data('status')) !== '0');
        });
    });
</script>