<?php

/**
 * Pengaturan · Risk Owner (pemilik risiko) — mri_risk_owner.
 * Dipakai sebagai pilihan pada analisa risiko dan register.
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
                    Risk owner adalah pemilik risiko per unit — berbeda dari PIC yang menjalankan tindakan.
                </small>
            </div>
            <div class="col-md-4 text-md-right">
                <a href="<?= base_url('manrisk/pengaturan') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Pengaturan
                </a>
                <a href="<?= base_url('manrisk/pengaturan/pic') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-user-check"></i> PIC
                </a>
            </div>
        </div>

        <?php if (!$akses_semua) : ?>
            <div class="alert alert-secondary">
                <i class="fas fa-info-circle"></i>
                Hanya <strong>Administrator</strong>, <strong>Keuangan</strong>, <strong>Publik</strong>, dan
                <strong>Auditor</strong> yang dapat mengubah data risk owner.
            </div>
        <?php endif; ?>

        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong><i class="fas fa-user-tie"></i> Daftar Risk Owner</strong>
                <span class="badge badge-light"><?= count($owner_list) ?> data</span>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Nama Owner</th>
                            <th style="width:140px">NIK</th>
                            <th>Jabatan</th>
                            <th>Unit</th>
                            <th>Kewenangan</th>
                            <th class="text-center" style="width:110px">Status</th>
                            <?php if ($akses_semua) : ?><th class="text-center" style="width:150px">Aksi</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$owner_list) : ?>
                            <tr>
                                <td colspan="<?= $akses_semua ? 8 : 7 ?>" class="text-center text-muted py-4">Belum ada data risk owner.</td>
                            </tr>
                        <?php endif; ?>
                        <?php $no = 1;
                        foreach ($owner_list as $o) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= html_escape($o->nama_owner) ?></strong></td>
                                <td><small><?= html_escape($o->nik ?: '-') ?></small></td>
                                <td><?= html_escape($o->jabatan_owner ?: '-') ?></td>
                                <td><?= html_escape($o->nama_bagian ?: '— seluruh organisasi —') ?></td>
                                <td><small><?= html_escape($o->kewenangan ?: '-') ?></small></td>
                                <td class="text-center">
                                    <span class="badge badge-<?= $o->status ? 'success' : 'secondary' ?>"><?= $o->status ? 'AKTIF' : 'NONAKTIF' ?></span>
                                </td>
                                <?php if ($akses_semua) : ?>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#modalOwner" data-id="<?= (int) $o->id_risk_owner ?>" data-nama="<?= html_escape($o->nama_owner) ?>" data-nik="<?= html_escape((string) $o->nik) ?>" data-jabatan="<?= html_escape((string) $o->jabatan_owner) ?>" data-bagian="<?= (int) $o->id_bagian ?>" data-kewenangan="<?= html_escape((string) $o->kewenangan) ?>" data-status="<?= (int) $o->status ?>">
                                            <i class="fas fa-pen"></i> Ubah
                                        </button>
                                        <a href="<?= base_url('manrisk/pengaturan/owner_hapus/' . (int) $o->id_risk_owner) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus risk owner <?= html_escape(addslashes($o->nama_owner)) ?>? Tindakan ini tidak dapat dibatalkan.');">
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
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalOwner" data-id="0" data-nama="" data-nik="" data-jabatan="" data-bagian="" data-kewenangan="" data-status="1">
                        <i class="fas fa-plus"></i> Tambah Risk Owner
                    </button>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>
</div>

<?php if ($akses_semua) : ?>
    <div class="modal fade" id="modalOwner" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <?= form_open('manrisk/pengaturan/owner_simpan', 'id="form-owner"') ?>
                <input type="hidden" name="id_risk_owner" id="ow_id" value="0">
                <div class="modal-header">
                    <h5 class="modal-title">Risk Owner — <span id="ow_judul">Tambah</span></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ow_nama">Nama Owner <span class="text-danger">*</span></label>
                        <input type="text" name="nama_owner" id="ow_nama" class="form-control" maxlength="100" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="ow_nik">NIK</label>
                            <input type="text" name="nik" id="ow_nik" class="form-control" maxlength="30">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ow_jabatan">Jabatan</label>
                            <input type="text" name="jabatan_owner" id="ow_jabatan" class="form-control" maxlength="100">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ow_bagian">Unit</label>
                        <select name="id_bagian" id="ow_bagian" class="form-control">
                            <option value="">— seluruh organisasi —</option>
                            <?php foreach ($unit_list as $u) : ?>
                                <option value="<?= (int) $u->id_bagian ?>"><?= html_escape($u->nama_bagian) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ow_kewenangan">Kewenangan</label>
                        <textarea name="kewenangan" id="ow_kewenangan" rows="2" class="form-control"></textarea>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="status" id="ow_status" value="1" class="custom-control-input" checked>
                        <label class="custom-control-label" for="ow_status"><strong>Aktif</strong></label>
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
        $('#modalOwner').on('show.bs.modal', function(e) {
            var b = $(e.relatedTarget);
            var id = b.data('id') || 0;
            $('#ow_id').val(id);
            $('#ow_judul').text(id ? 'Ubah' : 'Tambah');
            $('#ow_nama').val(b.data('nama') || '');
            $('#ow_nik').val(b.data('nik') || '');
            $('#ow_jabatan').val(b.data('jabatan') || '');
            $('#ow_bagian').val(String(b.data('bagian') || ''));
            $('#ow_kewenangan').val(b.data('kewenangan') || '');
            $('#ow_status').prop('checked', String(b.data('status')) !== '0');
        });
    });
</script>