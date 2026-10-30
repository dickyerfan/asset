<?php
/**
 * Formulir definisi KRI (nama, target, satuan, frekuensi, risiko terkait).
 * Nilai aktual TIDAK diubah di sini — hanya lewat tombol "Catat".
 */
$k = $kri;
$isEdit = $k !== null;

$val = function ($vk, $default = '') use ($k) {
    if ($k && isset($k->{$vk}) && $k->{$vk} !== null) {
        return set_value($vk, $k->{$vk});
    }
    return set_value($vk, $default);
};
?>
<section class="content">
    <div class="container-fluid">

        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-tachometer-alt"></i> <?= strtoupper(html_escape($title)) ?></h5>
                <a href="<?= base_url('manrisk/kri') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <?= form_open('manrisk/kri/form' . ($isEdit ? '/' . $k->id_kri : '')) ?>

            <?php if (validation_errors()) : ?>
                <div class="card-body pb-0"><div class="alert alert-danger mb-0"><?= validation_errors() ?></div></div>
            <?php endif; ?>

            <div class="card-body">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nama_kri">Nama KRI <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kri" id="nama_kri" class="form-control" maxlength="200" required
                               placeholder="mis. Frekuensi gangguan sumur bor per triwulan"
                               value="<?= html_escape($val('nama_kri')) ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tahun">Tahun <span class="text-danger">*</span></label>
                        <input type="number" name="tahun" id="tahun" class="form-control" min="2027" max="2099"
                               value="<?= $val('tahun', $tahun) ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="triwulan">Triwulan</label>
                        <select name="triwulan" id="triwulan" class="form-control">
                            <?php for ($t = 1; $t <= 4; $t++) : ?>
                                <option value="<?= $t ?>" <?= (string) $val('triwulan', 1) === (string) $t ? 'selected' : '' ?>>TW<?= $t ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="target">Target</label>
                        <input type="text" name="target" id="target" class="form-control"
                               placeholder="mis. 5" value="<?= html_escape($val('target')) ?>">
                        <small class="text-muted">Kosongkan bila target belum ditetapkan — status menjadi "Perlu Perhatian".</small>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="satuan">Satuan</label>
                        <input type="text" name="satuan" id="satuan" class="form-control"
                               placeholder="mis. kali / % / unit"
                               value="<?= html_escape($val('satuan')) ?>">
                        <small class="text-muted">Tulis <em>penurunan</em> di satuan bila makin kecil makin baik.</small>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="frekuensi">Frekuensi Pengukuran</label>
                        <input type="text" name="frekuensi" id="frekuensi" class="form-control"
                               placeholder="mis. Bulanan / Triwulanan"
                               value="<?= html_escape($val('frekuensi')) ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="id_upk">Unit</label>
                        <select name="id_upk" id="id_upk" class="form-control">
                            <option value="">— pilih —</option>
                            <?php foreach ($unit_list as $u) : ?>
                                <?php if (!$akses_semua && (int) $u->id_bagian !== (int) $this->session->userdata('id_bagian')) continue; ?>
                                <option value="<?= $u->id_bagian ?>" <?= (string) $val('id_upk', $akses_semua ? '' : (string) $this->session->userdata('id_bagian')) === (string) $u->id_bagian ? 'selected' : '' ?>>
                                    <?= html_escape($u->nama_bagian) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="id_register">Risiko Terkait</label>
                    <select name="id_register" id="id_register" class="form-control">
                        <option value="">— tanpa risiko langsung —</option>
                        <?php foreach ($risiko_list as $r) : ?>
                            <option value="<?= $r->id_register ?>" <?= (string) $val('id_register') === (string) $r->id_register ? 'selected' : '' ?>>
                                <?= html_escape($r->kode_risiko . ' — ' . mb_substr($r->risiko, 0, 90)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label for="keterangan">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="2" class="form-control"><?= html_escape($val('keterangan')) ?></textarea>
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="<?= base_url('manrisk/kri') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>

            <?= form_close() ?>
        </div>

    </div>
</section>
</div>
