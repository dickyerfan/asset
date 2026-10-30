<?php

/**
 * Formulir tambah / ubah objektif RKAP (ISO 31000 Klausa 6.3 — sasaran organisasi).
 *
 * Objektif di aplikasi ini tidak lagi dibekukan pada 26 baris hasil import RKAP;
 * pengguna yang berkepentingan dapat menambah maupun menyunting sasaran lewat form ini.
 */
$obj    = isset($objektif) ? $objektif : null;
$isEdit = $obj !== null;

$val = function ($k, $default = '') use ($obj) {
    if ($obj && isset($obj->{$k}) && $obj->{$k} !== null) {
        return set_value($k, $obj->{$k});
    }
    return set_value($k, $default);
};
?>
<section class="content">
    <div class="container-fluid">

        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-bullseye"></i> <?= strtoupper(html_escape($title)) ?></h5>
                <a href="<?= base_url('manrisk/objektif') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <?= form_open('manrisk/objektif/simpan') ?>
            <input type="hidden" name="id_objektif" value="<?= (int) ($obj ? $obj->id_objektif : 0) ?>">

            <?php
            $pesan = $this->session->flashdata('info');
            $this->session->unset_userdata('info');
            ?>
            <?php if ($pesan) : ?>
                <div class="card-body pb-0"><?= $pesan ?></div>
            <?php endif; ?>

            <?php if (validation_errors()) : ?>
                <div class="card-body pb-0">
                    <div class="alert alert-danger mb-0"><?= validation_errors() ?></div>
                </div>
            <?php endif; ?>

            <div class="card-body">
                <p class="text-muted small mb-3">
                    <?php if ($isEdit) : ?>
                        Menyunting objektif <strong><?= html_escape($obj->indikator) ?></strong>.
                        Bila <strong>Target RKAP</strong> berubah, perubahannya otomatis tercatat sebagai
                        jejak audit pada tabel revisi.
                    <?php else : ?>
                        Objektif adalah acuan <em>"kenapa risiko ini penting"</em>. Isi minimal
                        <strong>Indikator</strong> dan <strong>Target RKAP</strong>; kolom lain bersifat pelengkap.
                    <?php endif; ?>
                </p>

                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="tahun">Tahun <span class="text-danger">*</span></label>
                        <input type="number" name="tahun" id="tahun" class="form-control" min="2027" max="2099" value="<?= html_escape($val('tahun', $tahun)) ?>">
                    </div>
                    <div class="form-group col-md-10">
                        <label for="indikator">Indikator Objektif <span class="text-danger">*</span></label>
                        <input type="text" name="indikator" id="indikator" class="form-control" maxlength="300" placeholder="Contoh: Laba Bersih AMDK / Penurunan NRW per zona sumur bor" value="<?= html_escape($val('indikator')) ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="baseline_2026">Baseline 2026</label>
                        <input type="text" name="baseline_2026" id="baseline_2026" class="form-control" inputmode="decimal" placeholder="Contoh: 9.927.986.691" value="<?= html_escape($val('baseline_2026')) ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="nilai_rkap">Target RKAP <span class="text-danger">*</span></label>
                        <input type="text" name="nilai_rkap" id="nilai_rkap" class="form-control" inputmode="decimal" placeholder="Contoh: 10.613.952.106" value="<?= html_escape($val('nilai_rkap')) ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="satuan">Satuan</label>
                        <input type="text" name="satuan" id="satuan" class="form-control" maxlength="50" list="daftar_satuan" placeholder="Rp, persen, m3, ..." value="<?= html_escape($val('satuan')) ?>">
                        <datalist id="daftar_satuan">
                            <option value="Rp"></option>
                            <option value="persen"></option>
                            <option value="m3"></option>
                            <option value="SR"></option>
                            <option value="pelanggan"></option>
                            <option value="rekening"></option>
                            <option value="unit"></option>
                        </datalist>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="sumber_nilai">Sumber Nilai</label>
                        <input type="text" name="sumber_nilai" id="sumber_nilai" class="form-control" maxlength="255" list="daftar_sumber" placeholder="Usulan RKAP 2027" value="<?= html_escape($val('sumber_nilai', 'Usulan User')) ?>">
                        <datalist id="daftar_sumber">
                            <option value="Usulan RKAP 2027"></option>
                            <option value="Usulan User"></option>
                            <option value="Data Aktual"></option>
                            <option value="Hasil Rekonsiliasi"></option>
                        </datalist>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="id_bagian">Unit / Bagian</label>
                        <select name="id_bagian" id="id_bagian" class="form-control">
                            <option value="">— Semua unit —</option>
                            <?php foreach ($unit_list as $u) : ?>
                                <option value="<?= (int) $u->id_bagian ?>" <?= (string) $val('id_bagian') === (string) $u->id_bagian ? 'selected' : '' ?>>
                                    <?= html_escape($u->nama_bagian) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="id_proses">Proses</label>
                        <select name="id_proses" id="id_proses" class="form-control">
                            <option value="">— Pilih proses —</option>
                            <?php foreach ($proses_list as $p) : ?>
                                <option value="<?= (int) $p->id_proses ?>" <?= (string) $val('id_proses') === (string) $p->id_proses ? 'selected' : '' ?>>
                                    <?= html_escape($p->kode_proses) ?> — <?= html_escape($p->nama_proses) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="id_lingkup">Dokumen Lingkup</label>
                        <select name="id_lingkup" id="id_lingkup" class="form-control">
                            <option value="">— Pilih lingkup —</option>
                            <?php foreach ($lingkup_list as $l) : ?>
                                <option value="<?= (int) $l->id_lingkup ?>" <?= (string) $val('id_lingkup') === (string) $l->id_lingkup ? 'selected' : '' ?>>
                                    <?= html_escape($l->nama_lingkup) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="status_rekonsiliasi">Status Rekonsiliasi</label>
                        <select name="status_rekonsiliasi" id="status_rekonsiliasi" class="form-control">
                            <option value="BELUM" <?= $val('status_rekonsiliasi', 'BELUM') === 'BELUM' ? 'selected' : '' ?>>BELUM</option>
                            <option value="SELESAI" <?= $val('status_rekonsiliasi') === 'SELESAI' ? 'selected' : '' ?>>SELESAI</option>
                        </select>
                    </div>
                    <div class="form-group col-md-8">
                        <label for="catatan_rekonsiliasi">Catatan / Alasan</label>
                        <textarea name="catatan_rekonsiliasi" id="catatan_rekonsiliasi" rows="2" class="form-control" placeholder="Dasar penetapan angka atau keterangan lain"><?= html_escape($val('catatan_rekonsiliasi')) ?></textarea>
                    </div>
                </div>

                <?php if ($isEdit) : ?>
                    <div class="form-group mb-0">
                        <label for="alasan_ubah">Alasan Perubahan <small class="text-muted">(opsional)</small></label>
                        <textarea name="alasan_ubah" id="alasan_ubah" rows="2" class="form-control"
                                  placeholder="Contoh: penyesuaian dengan data aktual Q3"><?= html_escape($val('alasan_ubah')) ?></textarea>
                        <small class="text-muted">Hanya dicatat bila Target RKAP berubah.</small>
                    </div>
                <?php endif; ?>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Simpan Objektif' ?></button>
                <a href="<?= base_url('manrisk/objektif') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>

            <?= form_close() ?>
        </div>

    </div>
</section>
</div>