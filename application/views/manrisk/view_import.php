<?php
/**
 * Impor Risk Register dari CSV template 18 kolom (pemisah titik-koma).
 *
 * Tahapan: unggah -> pratinjau -> impor. Data legacy TIDAK pernah ditulis.
 */
$adaHasil = is_array($hasil) && !empty($hasil);
$opsi = $opsi ?: [];

$skalaTeks = [];
foreach ($tingkat as $t) {
    $skalaTeks[] = $t->nama_tingkat . ' ' . $t->skor_min . '-' . $t->skor_max;
}
$skalaTeks = implode(', ', $skalaTeks);
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <?php $lapor = $this->session->userdata('manrisk_import_lapor'); if ($lapor) : ?>
            <div class="card card-outline card-<?= $lapor['gagal'] ? 'warning' : 'success' ?>">
                <div class="card-header"><strong>Hasil Impor</strong></div>
                <div class="card-body">
                    <p class="mb-2">
                        <span class="badge badge-success"><?= (int) $lapor['impan'] ?> tersimpan</span>
                        <span class="badge badge-secondary"><?= (int) $lapor['lewati'] ?> dilewati</span>
                        <span class="badge badge-danger"><?= (int) $lapor['gagal'] ?> gagal</span>
                    </p>
                    <ul class="small mb-0" style="max-height:260px;overflow-y:auto">
                        <?php foreach ($lapor['rincian'] as $r) : ?>
                            <li><?= html_escape($r) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php $this->session->unset_userdata('manrisk_import_lapor'); ?>
        <?php endif; ?>

        <div class="row align-items-center mb-2">
            <div class="col-md-8">
                <h4 class="mb-0"><?= strtoupper($title) ?></h4>
                <small class="text-muted">
                    Format: <strong>18 kolom</strong>, pemisah titik-koma <code>;</code>, encoding UTF-8.
                    Baris footer/legenda otomatis dikenali dan dilewati.
                </small>
            </div>
            <div class="col-md-4 text-md-right">
                <a href="<?= base_url('manrisk/register') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-list"></i> Register
                </a>
            </div>
        </div>

        <?php if (!$boleh_tulis) : ?>
            <div class="alert alert-warning">
                <i class="fas fa-lock"></i> Register sedang dikunci — impor dinonaktifkan.
                Buka kunci pada <a href="<?= base_url('manrisk/pengaturan') ?>">Pengaturan</a>.
            </div>
        <?php endif; ?>

        <!-- ============== LANGKAH 1: UNGGAH ============== -->
        <div class="card card-outline card-primary">
            <div class="card-header"><strong>Langkah 1 — Unggah &amp; Baca File</strong></div>
            <?php if ($boleh_tulis) : ?>
                <?= form_open_multipart('manrisk/import/baca') ?>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="file_csv">File CSV <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file_csv" name="file_csv"
                                       accept=".csv,.txt" required>
                                <label class="custom-file-label" for="file_csv">Pilih file…</label>
                            </div>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="tahun">Tahun <span class="text-danger">*</span></label>
                            <input type="number" name="tahun" id="tahun" class="form-control" min="2027" max="2099"
                                   value="<?= isset($opsi['tahun']) ? (int) $opsi['tahun'] : $tahun ?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="id_upk">Unit Tujuan <span class="text-danger">*</span></label>
                            <select name="id_upk" id="id_upk" class="form-control" required>
                                <option value="">— pilih unit —</option>
                                <?php foreach ($unit_list as $u) : ?>
                                    <?php if (!$akses_semua && (int) $u->id_bagian !== (int) $this->session->userdata('id_bagian')) continue; ?>
                                    <option value="<?= $u->id_bagian ?>" <?= isset($opsi['id_upk']) && (string) $opsi['id_upk'] === (string) $u->id_bagian ? 'selected' : '' ?>>
                                        <?= html_escape($u->nama_bagian) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-1">
                            <label for="triwulan">TW</label>
                            <select name="triwulan" id="triwulan" class="form-control">
                                <?php for ($t = 1; $t <= 4; $t++) : ?>
                                    <option value="<?= $t ?>" <?= (isset($opsi['triwulan']) ? (int) $opsi['triwulan'] : 1) === $t ? 'selected' : '' ?>><?= $t ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="id_lingkup">Lingkup</label>
                            <select name="id_lingkup" id="id_lingkup" class="form-control">
                                <option value="">— tanpa lingkup —</option>
                                <?php foreach ($lingkup_list as $l) : ?>
                                    <option value="<?= $l->id_lingkup ?>" <?= isset($opsi['id_lingkup']) && (string) $opsi['id_lingkup'] === (string) $l->id_lingkup ? 'selected' : '' ?>>
                                        <?= html_escape($l->nama_lingkup) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <label for="id_kategori_default">Kategori Cadangan</label>
                            <select name="id_kategori_default" id="id_kategori_default" class="form-control">
                                <option value="">— wajib bila ada label tak dikenali —</option>
                                <?php foreach ($kategori_list as $k) : ?>
                                    <option value="<?= $k->id_kategori ?>" <?= isset($opsi['id_kategori_default']) && (string) $opsi['id_kategori_default'] === (string) $k->id_kategori ? 'selected' : '' ?>>
                                        <?= html_escape($k->nama_kat) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Dipakai untuk baris yang kategorinya tidak dapat dipetakan otomatis.</small>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="custom-control custom-switch mt-4">
                                <input type="checkbox" name="timpa" id="timpa" value="YA" class="custom-control-input"
                                    <?= !empty($opsi['timpa']) ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="timpa">
                                    <strong>Timpa risiko yang sudah ada</strong> (berdasarkan kode)
                                </label>
                            </div>
                            <small class="text-muted">Tanpa opsi ini, kode yang sudah ada akan dilewati.</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Unggah &amp; Pratinjau
                    </button>
                </div>
                <?= form_close() ?>
            <?php else : ?>
                <div class="card-body text-muted">Formulir nonaktif.</div>
            <?php endif; ?>
        </div>

        <!-- ============== LANGKAH 2: PRATINJAU ============== -->
        <?php if ($adaHasil) : ?>
            <div class="card card-outline card-<?= empty($hasil['errors']) ? 'success' : 'danger' ?>">
                <div class="card-header">
                    <strong>Langkah 2 — Pratinjau</strong>
                    <span class="badge badge-light ml-2"><?= count($hasil['rows']) ?> baris terbaca</span>
                    <span class="badge badge-light ml-1"><?= (int) $hasil['footer'] ?> baris footer dilewati</span>
                </div>
                <div class="card-body">

                    <?php if ($hasil['errors']) : ?>
                        <div class="alert alert-danger">
                            <strong>Kesalahan pembacaan:</strong>
                            <ul class="mb-0">
                                <?php foreach ($hasil['errors'] as $err) : ?>
                                    <li><?= html_escape($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if ($hasil['warnings']) : ?>
                        <div class="alert alert-warning">
                            <strong>Peringatan (periksa sebelum mengimpor):</strong>
                            <ul class="mb-0" style="max-height:220px;overflow-y:auto">
                                <?php foreach ($hasil['warnings'] as $w) : ?>
                                    <li><?= html_escape($w) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if ($hasil['rows']) : ?>
                        <div class="table-responsive" style="max-height:460px;overflow-y:auto">
                            <table class="table table-sm table-bordered table-striped mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th><th>Kode</th><th>Risiko</th><th>Unit</th><th>Kategori</th>
                                        <th class="text-center">Inh.</th><th class="text-center">Res.</th>
                                        <th class="text-center">Keputusan</th><th>Owner</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($hasil['rows'] as $i => $r) : ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><strong><?= html_escape($r['kode_risiko']) ?></strong></td>
                                            <td><small><?= html_escape(mb_substr($r['risiko'], 0, 90)) ?></small></td>
                                            <td><small><?= html_escape($r['unit'] ?: '-') ?></small></td>
                                            <td>
                                                <small><?= html_escape($r['kategori'] ?: '-') ?></small>
                                                <?php if ($r['id_kategori'] === null) : ?>
                                                    <br><span class="badge badge-danger">perlu kategori</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?= (int) $r['inherent_l'] ?>×<?= (int) $r['inherent_d'] ?>=<strong><?= (int) $r['nilai_inherent'] ?></strong>
                                            </td>
                                            <td class="text-center">
                                                <?= (int) $r['residual_l'] ?>×<?= (int) $r['residual_d'] ?>=<strong><?= (int) $r['nilai_residual'] ?></strong>
                                            </td>
                                            <td class="text-center"><small><?= html_escape($r['keputusan'] ?: '-') ?></small></td>
                                            <td><small><?= html_escape($r['risk_owner'] ?: '-') ?></small></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <?php if ($boleh_tulis && $hasil['rows']) : ?>
                        <div class="alert alert-info mt-3 mb-0">
                            <strong>Langkah 3 — Impor.</strong>
                            Data akan masuk ke modul ini saja; data legacy 2025&ndash;2026 tidak disentuh.
                            <?php if (empty($opsi['id_upk'])) : ?>
                                <br><span class="text-danger">Pilih unit tujuan pada Langkah 1 terlebih dahulu.</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($boleh_tulis && $hasil['rows']) : ?>
                    <div class="card-footer">
                        <?= form_open('manrisk/import/proses') ?>
                        <button type="submit" class="btn btn-success" <?= empty($opsi['id_upk']) ? 'disabled' : '' ?>>
                            <i class="fas fa-file-import"></i> Impor Sekarang
                        </button>
                        <?= form_close() ?>
                        <a href="<?= base_url('manrisk/import/batal') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Batalkan Pratinjau
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- ============== PANDUAN ============== -->
        <div class="card card-outline card-info">
            <div class="card-header"><strong>Panduan Format</strong></div>
            <div class="card-body small">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Urutan 18 kolom</strong> (pemisah <code>;</code>):</p>
                        <ol class="mb-2">
                            <li>Kode Risiko</li><li>Risiko / Uraian</li><li>Unit / Bidang</li><li>Kategori</li>
                            <li>Likelihood (inherent)</li><li>Dampak (inherent)</li>
                            <li>Likelihood (residual)</li><li>Dampak (residual)</li>
                            <li>Treatment / Tindakan</li><li>PIC / Risk Owner</li>
                            <li>Keputusan Evaluasi</li><li>Status Progres</li>
                            <li>Target / KPI</li><li>Anggaran</li>
                            <li>Jadwal Mulai</li><li>Jadwal Selesai</li>
                            <li>Keterangan</li><li>Catatan</li>
                        </ol>
                        <p class="mb-0">
                            <strong>Skor</strong> = Likelihood &times; Dampak (1&ndash;5) &rarr; 1&ndash;25.
                            <strong>Legenda:</strong> <?= html_escape($skalaTeks) ?>.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Catatan pemetaan</strong></p>
                        <ul class="mb-2">
                            <li>Kolom Likelihood/Dampak muncul dua kali — dibaca <strong>berdasarkan posisi</strong>, bukan judul.</li>
                            <li>Baris footer/legenda (teks yang tidak diawali kode <code>IJT-</code>) otomatis dilewati.</li>
                            <li>Termasuk legenda level lama di footer CSV — <strong>diabaikan</strong>, level dihitung ulang dengan skala aplikasi.</li>
                            <li>Kategori ambigu dipetakan ke token pertama dan <strong>diberi peringatan</strong>, tidak ditebak diam-diam.</li>
                            <li>Risk owner yang tidak cocok dengan master dikembalikan kosong agar dipilih manual.</li>
                        </ul>
                        <p class="mb-0">
                            Template contoh tersedia pada <code>D:\MR 2026\Risk_Register_Template_Perumdam_Ijen_Tirta.csv</code>.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('.custom-file-input').on('change', function () {
        var nama = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(nama);
    });
});
</script>
