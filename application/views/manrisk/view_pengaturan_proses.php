<?php

/**
 * Pengaturan · Proses (Risk Universe) — mri_proses.
 * 11 kelompok proses A–K sebagai konteks organisasi (ISO 6.4.2).
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
                    Proses menjadi konteks organisasi dan acuan pemetaan risiko. Kode proses harus unik (maks. 5 karakter).
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
                <strong>Auditor</strong> yang dapat mengubah master proses.
            </div>
        <?php endif; ?>

        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong><i class="fas fa-sitemap"></i> Daftar Proses</strong>
                <span class="badge badge-light"><?= count($proses_list) ?> data</span>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width:80px">Kode</th>
                            <th>Nama Proses</th>
                            <th>Grup / Tema</th>
                            <th>Penanggung</th>
                            <th class="text-center" style="width:70px">Urut</th>
                            <th class="text-center" style="width:110px">Status</th>
                            <?php if ($akses_semua) : ?><th class="text-center" style="width:150px">Aksi</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$proses_list) : ?>
                            <tr>
                                <td colspan="<?= $akses_semua ? 7 : 6 ?>" class="text-center text-muted py-4">Belum ada data proses.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($proses_list as $p) : ?>
                            <tr>
                                <td><code><?= html_escape($p->kode_proses) ?></code></td>
                                <td>
                                    <strong><?= html_escape($p->nama_proses) ?></strong>
                                    <?php if ($p->deskripsi) : ?>
                                        <br><small class="text-muted"><?= html_escape($p->deskripsi) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= html_escape($p->grup_tema ?: '-') ?></td>
                                <td><small><?= html_escape($p->id_bagian_penanggung ? ('#' . $p->id_bagian_penanggung) : '-') ?></small></td>
                                <td class="text-center"><?= (int) $p->urut ?></td>
                                <td class="text-center">
                                    <span class="badge badge-<?= $p->status ? 'success' : 'secondary' ?>"><?= $p->status ? 'AKTIF' : 'NONAKTIF' ?></span>
                                </td>
                                <?php if ($akses_semua) : ?>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#modalProses" data-id="<?= (int) $p->id_proses ?>" data-kode="<?= html_escape($p->kode_proses) ?>" data-nama="<?= html_escape($p->nama_proses) ?>" data-grup="<?= html_escape((string) $p->grup_tema) ?>" data-bagian="<?= (int) $p->id_bagian_penanggung ?>" data-deskripsi="<?= html_escape((string) $p->deskripsi) ?>" data-urut="<?= (int) $p->urut ?>" data-status="<?= (int) $p->status ?>">
                                            <i class="fas fa-pen"></i> Ubah
                                        </button>
                                        <a href="<?= base_url('manrisk/pengaturan/proses_hapus/' . (int) $p->id_proses) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus proses <?= html_escape(addslashes($p->kode_proses . ' · ' . $p->nama_proses)) ?>? Tindakan ini tidak dapat dibatalkan.');">
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
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalProses" data-id="0" data-kode="" data-nama="" data-grup="" data-bagian="" data-deskripsi="" data-urut="0" data-status="1">
                        <i class="fas fa-plus"></i> Tambah Proses
                    </button>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>
</div>

<?php if ($akses_semua) : ?>
    <div class="modal fade" id="modalProses" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <?= form_open('manrisk/pengaturan/proses_simpan', 'id="form-proses"') ?>
                <input type="hidden" name="id_proses" id="pr_id" value="0">
                <div class="modal-header">
                    <h5 class="modal-title">Proses — <span id="pr_judul">Tambah</span></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="pr_kode">Kode <span class="text-danger">*</span></label>
                            <input type="text" name="kode_proses" id="pr_kode" class="form-control" maxlength="5" placeholder="A" required>
                        </div>
                        <div class="form-group col-md-8">
                            <label for="pr_nama">Nama Proses <span class="text-danger">*</span></label>
                            <input type="text" name="nama_proses" id="pr_nama" class="form-control" maxlength="100" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-7">
                            <label for="pr_grup">Grup / Tema</label>
                            <input type="text" name="grup_tema" id="pr_grup" class="form-control" maxlength="100">
                        </div>
                        <div class="form-group col-md-5">
                            <label for="pr_urut">Urut Tampil</label>
                            <input type="number" name="urut" id="pr_urut" class="form-control" min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pr_bagian">Unit Penanggung</label>
                        <select name="id_bagian_penanggung" id="pr_bagian" class="form-control">
                            <option value="">— belum ditentukan —</option>
                            <?php foreach ($unit_list as $u) : ?>
                                <option value="<?= (int) $u->id_bagian ?>"><?= html_escape($u->nama_bagian) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pr_deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="pr_deskripsi" rows="2" class="form-control"></textarea>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="status" id="pr_status" value="1" class="custom-control-input" checked>
                        <label class="custom-control-label" for="pr_status"><strong>Aktif</strong></label>
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
        $('#modalProses').on('show.bs.modal', function(e) {
            var b = $(e.relatedTarget);
            var id = b.data('id') || 0;
            $('#pr_id').val(id);
            $('#pr_judul').text(id ? 'Ubah' : 'Tambah');
            $('#pr_kode').val(b.data('kode') || '');
            $('#pr_nama').val(b.data('nama') || '');
            $('#pr_grup').val(b.data('grup') || '');
            $('#pr_bagian').val(String(b.data('bagian') || ''));
            $('#pr_deskripsi').val(b.data('deskripsi') || '');
            $('#pr_urut').val(b.data('urut') || 0);
            $('#pr_status').prop('checked', String(b.data('status')) !== '0');
        });
    });
</script>