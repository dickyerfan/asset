<?php
/**
 * Formulir tambah / ubah risiko.
 *
 * Pengisian kolom mengikuti ISO 31000:2018 Klausa 6.4 (identifikasi):
 * peristiwa, penyebab, konsekuensi (akibat), serta sasaran yang terpengaruh.
 */
$e = function ($k, $default = '') use ($risiko) {
    if ($risiko && isset($risiko->{$k}) && $risiko->{$k} !== null) {
        return set_value($k, $risiko->{$k});
    }
    return set_value($k, $default);
};
$isEdit = $risiko !== null;
?>
<section class="content">
    <div class="container-fluid">

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-<?= $isEdit ? 'edit' : 'plus' ?>"></i>
                    <?= strtoupper(html_escape($title)) ?>
                </h5>
            </div>

            <?= form_open('manrisk/register/form' . ($isEdit ? '/' . $risiko->id_register : ''), 'id="form-register"') ?>

            <?php if (validation_errors()) : ?>
                <div class="card-body pb-0">
                    <div class="alert alert-danger mb-0"><?= validation_errors() ?></div>
                </div>
            <?php endif; ?>

            <div class="card-body">

                <!-- ================= IDENTIFIKASI ================= -->
                <h6 class="text-primary border-bottom pb-1 mb-3">
                    <i class="fas fa-id-card"></i> 1. Identifikasi
                </h6>

                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="segmen">Segmen Kode <span class="text-danger">*</span></label>
                        <select name="segmen" id="segmen" class="form-control">
                            <?php foreach ($segmen_list as $s) : ?>
                                <option value="<?= html_escape($s) ?>"><?= html_escape($s) ?></option>
                            <?php endforeach; ?>
                            <option value="OPR" <?= !in_array('OPR', $segmen_list, true) ? 'selected' : '' ?>>OPR</option>
                        </select>
                    </div>
                    <div class="form-group col-md-1">
                        <label for="kode_urut">Urut <span class="text-danger">*</span></label>
                        <input type="number" name="kode_urut" id="kode_urut" class="form-control" min="1" value="1">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="kode_risiko">Kode Risiko <span class="text-danger">*</span></label>
                        <input type="text" name="kode_risiko" id="kode_risiko"
                               class="form-control" maxlength="30" required
                               placeholder="IJT-OPR-01" value="<?= $e('kode_risiko', html_escape($saran_kode)) ?>">
                        <small class="text-muted">Format <code>IJT-SEG-NN</code>. Kode harus unik.</small>
                    </div>
                    <div class="form-group col-md-1">
                        <label for="tahun">Tahun <span class="text-danger">*</span></label>
                        <input type="number" name="tahun" id="tahun" class="form-control"
                               min="2027" max="2099" value="<?= $e('tahun', 2027) ?>">
                    </div>
                    <div class="form-group col-md-1">
                        <label for="triwulan_awal">TW Awal</label>
                        <select name="triwulan_awal" id="triwulan_awal" class="form-control">
                            <?php for ($t = 1; $t <= 4; $t++) : ?>
                                <option value="<?= $t ?>" <?= (string) $e('triwulan_awal', 1) === (string) $t ? 'selected' : '' ?>><?= $t ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="id_upk">Unit / Bagian <span class="text-danger">*</span></label>
                        <select name="id_upk" id="id_upk" class="form-control" required>
                            <option value="">— pilih unit —</option>
                            <?php foreach ($unit_list as $u) : ?>
                                <?php if (!$akses_semua && (int) $u->id_bagian !== (int) $this->session->userdata('id_bagian')) continue; ?>
                                <option value="<?= $u->id_bagian ?>"
                                    <?= (string) $e('id_upk', $akses_semua ? '' : (string) $this->session->userdata('id_bagian')) === (string) $u->id_bagian ? 'selected' : '' ?>>
                                    <?= html_escape($u->nama_bagian) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="id_kategori">Kategori <span class="text-danger">*</span></label>
                        <select name="id_kategori" id="id_kategori" class="form-control" required>
                            <option value="">— pilih —</option>
                            <?php foreach ($kategori_list as $k) : ?>
                                <option value="<?= $k->id_kategori ?>" <?= (string) $e('id_kategori') === (string) $k->id_kategori ? 'selected' : '' ?>>
                                    <?= html_escape($k->nama_kat) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="id_lingkup">Lingkup / Unit Kerja</label>
                        <select name="id_lingkup" id="id_lingkup" class="form-control">
                            <option value="">— tanpa lingkup —</option>
                            <?php foreach ($lingkup_list as $l) : ?>
                                <option value="<?= $l->id_lingkup ?>" <?= (string) $e('id_lingkup') === (string) $l->id_lingkup ? 'selected' : '' ?>>
                                    <?= html_escape($l->nama_lingkup) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="d-block mb-1">Tipe Risiko <span class="text-danger">*</span></label>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="tipe_ancaman" name="tipe_risiko" value="Ancaman"
                                   class="custom-control-input" <?= $e('tipe_risiko', 'Ancaman') === 'Ancaman' ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="tipe_ancaman">Ancaman</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="tipe_peluang" name="tipe_risiko" value="Peluang"
                                   class="custom-control-input" <?= $e('tipe_risiko') === 'Peluang' ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="tipe_peluang">Peluang</label>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="d-block mb-1">Status Kendali</label>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="kendali_c" name="status_kendali" value="C"
                                   class="custom-control-input" <?= $e('status_kendali', 'UC') === 'C' ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="kendali_c">C — terkendali</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="kendali_uc" name="status_kendali" value="UC"
                                   class="custom-control-input" <?= $e('status_kendali') === 'UC' ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="kendali_uc">UC — belum</label>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="status_risiko">Status Risiko</label>
                        <select name="status_risiko" id="status_risiko" class="form-control">
                            <?php foreach (['AKTIF', 'SELESAI', 'NONAKTIF'] as $s) : ?>
                                <option value="<?= $s ?>" <?= $e('status_risiko', 'AKTIF') === $s ? 'selected' : '' ?>><?= $s ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- ================= RUMUSAN ================= -->
                <h6 class="text-primary border-bottom pb-1 mb-3 mt-3">
                    <i class="fas fa-exclamation-triangle"></i> 2. Rumusan Risiko
                </h6>

                <div class="form-group">
                    <label for="risiko">Rumusan Risiko <span class="text-danger">*</span></label>
                    <textarea name="risiko" id="risiko" rows="2" class="form-control" required
                              placeholder="Kehilangan Pelanggan ..."><?= html_escape($e('risiko')) ?></textarea>
                    <small class="text-muted">Kalimat utuh: "Jika &lt;penyebab&gt; maka &lt;peristiwa&gt; sehingga &lt;akibat&gt;."</small>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="peristiwa_risiko">Peristiwa Risiko <span class="text-danger">*</span></label>
                        <textarea name="peristiwa_risiko" id="peristiwa_risiko" rows="2" class="form-control" required><?= html_escape($e('peristiwa_risiko')) ?></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="penyebab">Penyebab / Sumber <span class="text-danger">*</span></label>
                        <textarea name="penyebab" id="penyebab" rows="2" class="form-control" required><?= html_escape($e('penyebab')) ?></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="sasaran_kpi">Sasaran / KPI RKAP <span class="text-danger">*</span></label>
                        <textarea name="sasaran_kpi" id="sasaran_kpi" rows="2" class="form-control" required><?= html_escape($e('sasaran_kpi')) ?></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sumber_risiko">Sumber Risiko Tambahan</label>
                        <input type="text" name="sumber_risiko" id="sumber_risiko" class="form-control"
                               value="<?= html_escape($e('sumber_risiko')) ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="indikator_emerging">Indikator Emerging</label>
                        <input type="text" name="indikator_emerging" id="indikator_emerging" class="form-control"
                               placeholder="Perubahan regulasi, tren pasar, dsb."
                               value="<?= html_escape($e('indikator_emerging')) ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="asumsi_bias">Asumsi &amp; Bias</label>
                        <input type="text" name="asumsi_bias" id="asumsi_bias" class="form-control"
                               value="<?= html_escape($e('asumsi_bias')) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="kegiatan">Kegiatan / Proses yang Terdampak</label>
                    <input type="text" name="kegiatan" id="kegiatan" class="form-control"
                           value="<?= html_escape($e('kegiatan')) ?>">
                    <small class="text-muted">Dibiarkan kosong akan diisi otomatis dari Sasaran / KPI.</small>
                </div>

                <!-- ================= AKIBAT ================= -->
                <h6 class="text-primary border-bottom pb-1 mb-3 mt-3">
                    <i class="fas fa-bomb"></i> 3. Akibat yang Mungkin Terjadi
                </h6>

                <div class="form-group">
                    <label for="akibat_baris">Daftar Akibat <small>(satu per baris)</small></label>
                    <textarea name="akibat_baris" id="akibat_baris" rows="4" class="form-control"
                              placeholder="Finansial|Kerugian sampai Rp 500 juta&#10;Non-Finansial|Penurunan kepercayaan pelanggan"><?= html_escape(implode("\n", array_map(function ($a) {
                        return ($a->jenis ?: 'Non-Finansial') . '|' . $a->akibat;
                    }, $akibat))) ?></textarea>
                    <small class="text-muted">
                        Format opsional per baris: <code>Jenis|Teks</code>.
                        Jenis yang dikenal: <code>Finansial</code> atau <code>Non-Finansial</code>.
                        Bila Jenis dihilangkan, otomatis <em>Non-Finansial</em>.
                    </small>
                </div>

                <!-- ================= TAUTAN ================= -->
                <h6 class="text-primary border-bottom pb-1 mb-3 mt-3">
                    <i class="fas fa-link"></i> 4. Tautan Proses &amp; Objektif
                </h6>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Proses Terkait (Risk Universe)</label>
                        <div class="border rounded p-2" style="max-height:260px;overflow-y:auto">
                            <?php foreach ($proses_list as $p) : ?>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="proses[]"
                                           id="proses_<?= (int) $p->id_proses ?>" value="<?= (int) $p->id_proses ?>"
                                           <?= in_array((int) $p->id_proses, array_map('intval', $proses_dipilih), true) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="proses_<?= (int) $p->id_proses ?>">
                                        <strong><?= html_escape($p->kode_proses) ?></strong> — <?= html_escape($p->nama_proses) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                            <?php if (!$proses_list) : ?>
                                <small class="text-muted">Belum ada data proses.</small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Objektif RKAP Terkait</label>
                        <div class="border rounded p-2" style="max-height:260px;overflow-y:auto">
                            <?php foreach ($objektif_list as $o) : ?>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="objektif[]"
                                           id="obj_<?= (int) $o->id_objektif ?>" value="<?= (int) $o->id_objektif ?>"
                                           <?= in_array((int) $o->id_objektif, array_map('intval', $objektif_dipilih), true) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="obj_<?= (int) $o->id_objektif ?>">
                                        <?= html_escape($o->indikator) ?>
                                        <?php if ($o->nilai_rkap !== null) : ?>
                                            <small class="text-muted">(<?= html_escape($o->nilai_rkap) ?> <?= html_escape($o->satuan) ?>)</small>
                                        <?php endif; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                            <?php if (!$objektif_list) : ?>
                                <small class="text-muted">Belum ada objektif RKAP untuk tahun berjalan.</small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- ================= CATATAN ================= -->
                <div class="form-group">
                    <label for="keterangan">Catatan</label>
                    <textarea name="keterangan" id="keterangan" rows="2" class="form-control"><?= html_escape($e('keterangan')) ?></textarea>
                </div>

                <!-- ================= LEGENDA ================= -->
                <div class="alert alert-secondary mb-0">
                    <strong>Skala risiko (<?= count($tingkat) ?> level):</strong>
                    <?php foreach ($tingkat as $t) : ?>
                        <span class="badge mr-1" style="background-color:<?= $t->warna ?>;color:#fff">
                            <?= html_escape($t->nama_tingkat) ?> <?= (int) $t->skor_min ?>&ndash;<?= (int) $t->skor_max ?>
                        </span>
                    <?php endforeach; ?>
                    <br>
                    <small>
                        Skor dihitung sebagai <strong>Likelihood &times; Dampak</strong> pada langkah Analisa,
                        bukan pada saat mengisi formulir ini.
                        Data 2025&ndash;2026 tidak dimasukkan ke modul ini.
                    </small>
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="<?= $isEdit ? base_url('manrisk/register/detail/' . $risiko->id_register) : base_url('manrisk/register') ?>"
                   class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>

            <?= form_close() ?>
        </div>

    </div>
</section>
</div>

<script>
(function () {
    var segmen = document.getElementById('segmen');
    var urut   = document.getElementById('kode_urut');
    var kode   = document.getElementById('kode_risiko');
    var isEdit = <?= $isEdit ? 'true' : 'false' ?>;
    var touched = false;

    function pad(n) { return n < 10 ? '0' + n : String(n); }

    function susun() {
        if (isEdit && touched) return;
        var s = segmen.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase() || 'GEN';
        var u = parseInt(urut.value, 10);
        if (!u || u < 1) u = 1;
        kode.value = 'IJT-' + s + '-' + pad(u);
    }

    segmen.addEventListener('change', function () {
        // ambil nomor urut berikutnya dari server untuk segmen terpilih
        var s = segmen.value;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '<?= base_url('manrisk/register') ?>/saranKode/' + encodeURIComponent(s), true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                var m = /IJT-[A-Z0-9]+-(\d+)/.exec(xhr.responseText);
                if (m) urut.value = parseInt(m[1], 10);
                susun();
            }
        };
        xhr.send();
        susun();
    });
    urut.addEventListener('input', function () { touched = true; susun(); });
    kode.addEventListener('input', function () { touched = true; });
    susun();
})();
</script>
