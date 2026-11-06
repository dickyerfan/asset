<?php

/**
 * Pengaturan · PIC (pelaksana tindakan) — mri_pic.
 * Tambah / ubah lewat modal, hapus dengan konfirmasi.
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
                    PIC adalah pelaksana tindakan perlakuan risiko. Terpisah dari Risk Owner.
                </small>
            </div>
            <div class="col-md-4 text-md-right">
                <a href="<?= base_url('manrisk/pengaturan') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Pengaturan
                </a>
                <a href="<?= base_url('manrisk/pengaturan/ttd') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-signature"></i> Petugas TTD
                </a>
            </div>
        </div>

        <?php if (!$akses_semua) : ?>
            <div class="alert alert-secondary">
                <i class="fas fa-info-circle"></i>
                Hanya <strong>Administrator</strong>, <strong>Keuangan</strong>, <strong>Publik</strong>, dan
                <strong>Auditor</strong> yang dapat mengubah data PIC.
            </div>
        <?php endif; ?>

        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong><i class="fas fa-user-check"></i> Daftar PIC</strong>
                <span class="badge badge-light"><?= count($pic_list) ?> data</span>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Nama PIC</th>
                            <th>Jabatan</th>
                            <th>Unit</th>
                            <th>Kompetensi</th>
                            <th class="text-center" style="width:110px">Status</th>
                            <?php if ($akses_semua) : ?><th class="text-center" style="width:150px">Aksi</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$pic_list) : ?>
                            <tr>
                                <td colspan="<?= $akses_semua ? 7 : 6 ?>" class="text-center text-muted py-4">Belum ada data PIC.</td>
                            </tr>
                        <?php endif; ?>
                        <?php $no = 1;
                        foreach ($pic_list as $p) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= html_escape($p->nama_pic) ?></strong></td>
                                <td><?= html_escape($p->jabatan_pic ?: '-') ?></td>
                                <td><?= html_escape($p->nama_bagian ?: '— seluruh organisasi —') ?></td>
                                <td><small><?= html_escape($p->kompetensi ?: '-') ?></small></td>
                                <td class="text-center">
                                    <span class="badge badge-<?= $p->status ? 'success' : 'secondary' ?>"><?= $p->status ? 'AKTIF' : 'NONAKTIF' ?></span>
                                </td>
                                <?php if ($akses_semua) : ?>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#modalPic" data-id="<?= (int) $p->id_pic ?>" data-nama="<?= html_escape($p->nama_pic) ?>" data-jabatan="<?= html_escape((string) $p->jabatan_pic) ?>" data-bagian="<?= (int) $p->id_bagian ?>" data-kompetensi="<?= html_escape((string) $p->kompetensi) ?>" data-status="<?= (int) $p->status ?>">
                                            <i class="fas fa-pen"></i> Ubah
                                        </button>
                                        <a href="<?= base_url('manrisk/pengaturan/pic_hapus/' . (int) $p->id_pic) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus PIC <?= html_escape(addslashes($p->nama_pic)) ?>? Tindakan ini tidak dapat dibatalkan.');">
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
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalPic" data-id="0" data-nama="" data-jabatan="" data-bagian="" data-kompetensi="" data-status="1">
                        <i class="fas fa-plus"></i> Tambah PIC
                    </button>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>
</div>

<?php if ($akses_semua) : ?>
    <div class="modal fade" id="modalPic" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <?= form_open('manrisk/pengaturan/pic_simpan', 'id="form-pic"') ?>
                <input type="hidden" name="id_pic" id="pic_id" value="0">
                <div class="modal-header">
                    <h5 class="modal-title">PIC — <span id="pic_judul">Tambah</span></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="pic_nama">Nama PIC <span class="text-danger">*</span></label>
                        <input type="text" name="nama_pic" id="pic_nama" class="form-control" maxlength="100" required>
                    </div>
                    <div class="form-group">
                        <label for="pic_jabatan">Jabatan</label>
                        <input type="text" name="jabatan_pic" id="pic_jabatan" class="form-control" maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="pic_bagian">Unit</label>
                        <select name="id_bagian" id="pic_bagian" class="form-control">
                            <option value="">— seluruh organisasi —</option>
                            <?php foreach ($unit_list as $u) : ?>
                                <option value="<?= (int) $u->id_bagian ?>"><?= html_escape($u->nama_bagian) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pic_kompetensi">Kompetensi</label>
                        <textarea name="kompetensi" id="pic_kompetensi" rows="2" class="form-control"></textarea>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="status" id="pic_status" value="1" class="custom-control-input" checked>
                        <label class="custom-control-label" for="pic_status"><strong>Aktif</strong></label>
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
        $('#modalPic').on('show.bs.modal', function(e) {
            var b = $(e.relatedTarget);
            var id = b.data('id') || 0;
            $('#pic_id').val(id);
            $('#pic_judul').text(id ? 'Ubah' : 'Tambah');
            $('#pic_nama').val(b.data('nama') || '');
            $('#pic_jabatan').val(b.data('jabatan') || '');
            $('#pic_bagian').val(String(b.data('bagian') || ''));
            $('#pic_kompetensi').val(b.data('kompetensi') || '');
            $('#pic_status').prop('checked', String(b.data('status')) !== '0');
        });
    });
</script>