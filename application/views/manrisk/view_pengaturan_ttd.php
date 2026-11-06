<?php

/**
 * Pengaturan · Petugas TTD (penandatangan laporan) — mri_petugas_ttd.
 * kode_ttd unik; dipakai laporan untuk memilih penanda tangan.
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
                    Petugas yang menandatangani laporan manajemen risiko. Kode harus unik
                    (mis. <code>DIREKTUR</code>, <code>KETUA_SPI</code>, <code>PJR_B7</code>).
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
                <strong>Auditor</strong> yang dapat mengubah petugas tanda tangan.
            </div>
        <?php endif; ?>

        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong><i class="fas fa-signature"></i> Daftar Petugas TTD</strong>
                <span class="badge badge-light"><?= count($ttd_list) ?> data</span>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width:60px">#</th>
                            <th style="width:170px">Kode TTD</th>
                            <th>Jabatan</th>
                            <th>Unit</th>
                            <th>Nama Petugas</th>
                            <th style="width:140px">NIK</th>
                            <th class="text-center" style="width:110px">Status</th>
                            <?php if ($akses_semua) : ?><th class="text-center" style="width:150px">Aksi</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$ttd_list) : ?>
                            <tr>
                                <td colspan="<?= $akses_semua ? 8 : 7 ?>" class="text-center text-muted py-4">Belum ada petugas TTD.</td>
                            </tr>
                        <?php endif; ?>
                        <?php $no = 1;
                        foreach ($ttd_list as $t) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><code><?= html_escape($t->kode_ttd) ?></code></td>
                                <td><strong><?= html_escape($t->jabatan_ttd) ?></strong></td>
                                <td><?= html_escape($t->nama_bagian ?: '— seluruh organisasi —') ?></td>
                                <td><?= html_escape($t->nama_petugas ?: '-') ?></td>
                                <td><small><?= html_escape($t->nik ?: '-') ?></small></td>
                                <td class="text-center">
                                    <span class="badge badge-<?= $t->status ? 'success' : 'secondary' ?>"><?= $t->status ? 'AKTIF' : 'NONAKTIF' ?></span>
                                </td>
                                <?php if ($akses_semua) : ?>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#modalTtd" data-id="<?= (int) $t->id_ttd ?>" data-kode="<?= html_escape($t->kode_ttd) ?>" data-jabatan="<?= html_escape($t->jabatan_ttd) ?>" data-bagian="<?= (int) $t->id_bagian ?>" data-nama="<?= html_escape((string) $t->nama_petugas) ?>" data-nik="<?= html_escape((string) $t->nik) ?>" data-status="<?= (int) $t->status ?>">
                                            <i class="fas fa-pen"></i> Ubah
                                        </button>
                                        <a href="<?= base_url('manrisk/pengaturan/ttd_hapus/' . (int) $t->id_ttd) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus petugas TTD <?= html_escape(addslashes($t->jabatan_ttd)) ?>? Tindakan ini tidak dapat dibatalkan.');">
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
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalTtd" data-id="0" data-kode="" data-jabatan="" data-bagian="" data-nama="" data-nik="" data-status="1">
                        <i class="fas fa-plus"></i> Tambah Petugas TTD
                    </button>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>
</div>

<?php if ($akses_semua) : ?>
    <div class="modal fade" id="modalTtd" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <?= form_open('manrisk/pengaturan/ttd_simpan', 'id="form-ttd"') ?>
                <input type="hidden" name="id_ttd" id="ttd_id" value="0">
                <div class="modal-header">
                    <h5 class="modal-title">Petugas TTD — <span id="ttd_judul">Tambah</span></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="ttd_kode">Kode TTD <span class="text-danger">*</span></label>
                            <input type="text" name="kode_ttd" id="ttd_kode" class="form-control" maxlength="30" placeholder="DIREKTUR" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ttd_status">Status</label>
                            <div class="custom-control custom-switch mt-2">
                                <input type="checkbox" name="status" id="ttd_status" value="1" class="custom-control-input" checked>
                                <label class="custom-control-label" for="ttd_status"><strong>Aktif</strong></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ttd_jabatan">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" name="jabatan_ttd" id="ttd_jabatan" class="form-control" maxlength="100" placeholder="Direktur PDAM" required>
                    </div>
                    <div class="form-group">
                        <label for="ttd_bagian">Unit</label>
                        <select name="id_bagian" id="ttd_bagian" class="form-control">
                            <option value="">— seluruh organisasi —</option>
                            <?php foreach ($unit_list as $u) : ?>
                                <option value="<?= (int) $u->id_bagian ?>"><?= html_escape($u->nama_bagian) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-7">
                            <label for="ttd_nama">Nama Petugas</label>
                            <input type="text" name="nama_petugas" id="ttd_nama" class="form-control" maxlength="100">
                        </div>
                        <div class="form-group col-md-5">
                            <label for="ttd_nik">NIK</label>
                            <input type="text" name="nik" id="ttd_nik" class="form-control" maxlength="30">
                        </div>
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
        $('#modalTtd').on('show.bs.modal', function(e) {
            var b = $(e.relatedTarget);
            var id = b.data('id') || 0;
            $('#ttd_id').val(id);
            $('#ttd_judul').text(id ? 'Ubah' : 'Tambah');
            $('#ttd_kode').val(b.data('kode') || '');
            $('#ttd_jabatan').val(b.data('jabatan') || '');
            $('#ttd_bagian').val(String(b.data('bagian') || ''));
            $('#ttd_nama').val(b.data('nama') || '');
            $('#ttd_nik').val(b.data('nik') || '');
            $('#ttd_status').prop('checked', String(b.data('status')) !== '0');
        });
    });
</script>