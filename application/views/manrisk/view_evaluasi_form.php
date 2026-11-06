<?php

/**
 * Formulir evaluasi risiko (ISO 31000 Klausa 6.4.4 / QRMO slide 39).
 * Guardrail: nilai evaluasi DIKUNCI ke skor residual — bukan input bebas.
 */
$e = $evaluasi;
$isEdit = $e !== null;

$warna = [];
foreach ($tingkat as $t) {
    $warna[$t->nama_tingkat] = $t->warna;
}

$warnaKeputusan = [
    'Accept'  => 'secondary',
    'Monitor' => 'info',
    'Treat'   => 'warning',
    'Escalate' => 'danger',
];

$val = function ($k, $default = '') use ($e) {
    if ($e && isset($e->{$k}) && $e->{$k} !== null) {
        return set_value($k, $e->{$k});
    }
    return set_value($k, $default);
};

$katJson = [];
foreach ($tingkat as $t) {
    $katJson[] = ['nama' => $t->nama_tingkat, 'min' => (int) $t->skor_min, 'max' => (int) $t->skor_max, 'warna' => $t->warna];
}

// Nilai evaluasi selalu = residual (QRMO slide 39).
$nilaiResidual = (int) $analisa->nilai_residual;
$defaultKeputusan = $band ?: 'Monitor';

/**
 * Konteks risiko untuk panel ringkasan.
 * Judul halaman hanya memuat kode (mis. "EVALUASI IJT-OPR-06"), sehingga
 * detail ini diperlukan agar pengguna yakin sedang mengevaluasi risiko yang benar.
 */
$kodeKat  = isset($risiko->kode_kat) ? trim((string) $risiko->kode_kat) : '';
$namaKat  = isset($risiko->nama_kat) ? trim((string) $risiko->nama_kat) : '';
$labelKat = ($kodeKat !== '' ? $kodeKat . ' — ' : '') . ($namaKat !== '' ? $namaKat : '—');
$twAwal   = (int) (isset($risiko->triwulan_awal) ? $risiko->triwulan_awal : 1);
if ($twAwal < 1 || $twAwal > 4) {
    $twAwal = 1;
}
$statusRisiko = !empty($risiko->status_risiko) ? $risiko->status_risiko : 'AKTIF';
?>
<section class="content">
    <div class="container-fluid">

        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="mb-0"><i class="fas fa-balance-scale"></i> <?= strtoupper(html_escape($title)) ?></h5>
                    <small class="text-muted">
                        <?= html_escape($risiko->risiko) ?>
                        &middot; <?= html_escape($risiko->nama_bagian ?: 'tanpa unit') ?>
                        &middot; <?= (int) $risiko->tahun ?>
                    </small>
                </div>
                <a href="<?= base_url('manrisk/evaluasi') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <!-- <a href="<?= base_url('manrisk/register/detail/' . $risiko->id_register) ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a> -->
            </div>

            <?= form_open('manrisk/evaluasi/form/' . $risiko->id_register) ?>

            <?php if (validation_errors()) : ?>
                <!-- Tanpa kelas .alert: templates/footer.php menghapus .alert otomatis
                     setelah ~7 detik, sehingga pesan error bisa hilang sebelum dibaca. -->
                <div class="card-body pb-0">
                    <div class="border border-danger rounded text-danger bg-white p-2"><?= validation_errors() ?></div>
                </div>
            <?php endif; ?>

            <div class="card-body">

                <!-- ============ KONTEKS RISIKO ============
                     Tanpa kelas .alert: templates/footer.php menghapus .alert otomatis.
                     Ditampilkan permanen agar pengguna tahu risiko mana yang dievaluasi
                     (judul halaman hanya memuat kode, mis. EVALUASI IJT-OPR-06). -->
                <div class="border rounded bg-light p-3 mb-3">
                    <div class="row">
                        <div class="col-md-3"><strong>Kode</strong><br><?= html_escape($risiko->kode_risiko) ?></div>
                        <div class="col-md-6"><strong>Risiko</strong><br><?= html_escape($risiko->risiko) ?></div>
                        <div class="col-md-3"><strong>Unit</strong><br><?= html_escape($risiko->nama_bagian ?: '-') ?></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3"><strong>Kategori</strong><br><small><?= html_escape($labelKat) ?></small></div>
                        <div class="col-md-2"><strong>Tipe</strong><br><small><?= html_escape($risiko->tipe_risiko) ?></small></div>
                        <div class="col-md-2"><strong>Tahun / TW Awal</strong><br><small><?= (int) $risiko->tahun ?> / TW<?= $twAwal ?></small></div>
                        <div class="col-md-2">
                            <strong>Status Kendali</strong><br>
                            <small><?= $risiko->status_kendali === 'C' ? 'C — terkendali' : 'UC — belum terkendali' ?></small>
                        </div>
                        <div class="col-md-3">
                            <strong>Status Risiko</strong><br>
                            <small>
                                <span class="badge badge-<?= $statusRisiko === 'AKTIF' ? 'success' : ($statusRisiko === 'SELESAI' ? 'secondary' : 'dark') ?>">
                                    <?= html_escape($statusRisiko) ?>
                                </span>
                            </small>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6"><strong>Peristiwa</strong><br><small><?= html_escape($risiko->peristiwa_risiko ?: '-') ?></small></div>
                        <div class="col-md-6"><strong>Penyebab</strong><br><small><?= html_escape($risiko->penyebab ?: '-') ?></small></div>
                    </div>
                    <?php if (!empty($risiko->sasaran_kpi)) : ?>
                        <div class="row mt-2">
                            <div class="col-12"><strong>Sasaran / KPI</strong><br><small><?= html_escape($risiko->sasaran_kpi) ?></small></div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ============ INHERENT vs RESIDUAL (QRMO slide 34, 39, 41) ============ -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100">
                            <h6 class="text-muted">Inherent Risk (sebelum kontrol)</h6>
                            <h2 class="mb-1">
                                <?= (int) $analisa->inherent_l ?> &times; <?= (int) $analisa->inherent_d ?>
                                = <strong><?= (int) $analisa->nilai_inherent ?></strong>
                            </h2>
                            <span class="badge" style="background-color:<?= isset($warna[$analisa->level_inherent]) ? $warna[$analisa->level_inherent] : '#6c757d' ?>;color:#fff">
                                <?= html_escape($analisa->level_inherent) ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border border-success rounded p-3 h-100 bg-light">
                            <h6 class="text-success"><i class="fas fa-lock"></i> Residual Risk (dasar evaluasi)</h6>
                            <h2 class="mb-1">
                                <?= (int) $analisa->residual_l ?> &times; <?= (int) $analisa->residual_d ?>
                                = <strong><?= $nilaiResidual ?></strong>
                            </h2>
                            <span class="badge" style="background-color:<?= isset($warna[$analisa->level_residual]) ? $warna[$analisa->level_residual] : '#6c757d' ?>;color:#fff">
                                <?= html_escape($analisa->level_residual) ?>
                            </span>
                            <p class="mb-0 mt-2 small text-muted">
                                Nilai evaluasi <strong>dikunci</strong> ke skor residual sesuai QRMO slide 39.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100">
                            <h6 class="text-muted">Rekomendasi Band (aksi bawaan)</h6>
                            <?php if ($band) : ?>
                                <h2 class="mb-1">
                                    <span class="badge badge-<?= isset($warnaKeputusan[$band]) ? $warnaKeputusan[$band] : 'secondary' ?> px-3 py-2">
                                        <?= html_escape($band) ?>
                                    </span>
                                </h2>
                                <p class="mb-0 small text-muted">
                                    Rekomendasi otomatis dari skala aktif (<?= html_escape($skala_teks) ?>).
                                    Keputusan berbeda dari band akan ditandai <strong>Perlu Review</strong>.
                                </p>
                            <?php else : ?>
                                <p class="text-muted mb-0">Band tidak dapat ditentukan — periksa skala di Pengaturan.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="border rounded p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="text-muted mb-0">Risk Appetite Kategori</h6>
                                    <small class="text-muted">
                                        Batas toleransi tiap kategori pada tahun ini &mdash; acuan memilih keputusan.
                                    </small>
                                </div>
                                <?php if ($appetite) : ?>
                                    <!-- Tabel appetite cukup panjang, jadi disembunyikan di balik tombol
                                         agar form tidak memanjang. Bukan .collapse Bootstrap JS: cukup
                                         class .show yang di-toggle manual (lihat script di bawah). -->
                                    <button type="button" class="btn btn-sm btn-outline-secondary ml-2" id="tombol-appetite" aria-expanded="false" aria-controls="box-appetite">
                                        <i class="fas fa-table"></i>
                                        <span id="teks-appetite">Lihat tabel</span>
                                        <i class="fas fa-chevron-down ml-1" id="chev-appetite"></i>
                                    </button>
                                <?php endif; ?>
                            </div>

                            <?php if (!$appetite) : ?>
                                <p class="text-muted mb-0 mt-2"><small>Belum ada risk appetite yang ditetapkan untuk tahun ini.</small></p>
                            <?php else : ?>
                                <div class="collapse mt-2" id="box-appetite">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered bg-white mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Kategori</th>
                                                    <th>Appetite</th>
                                                    <th>Toleransi</th>
                                                    <th>Batas Eskalasi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($appetite as $ap) : ?>
                                                    <tr>
                                                        <td>
                                                            <small>
                                                                <?php if (!empty($ap->kode_kat)) : ?>
                                                                    <span class="text-muted"><?= html_escape($ap->kode_kat) ?></span> &mdash;
                                                                <?php endif; ?>
                                                                <?= html_escape($ap->nama_kat) ?>
                                                            </small>
                                                        </td>
                                                        <td><small><?= html_escape($ap->risk_appetite ?: '-') ?></small></td>
                                                        <td><small><?= html_escape($ap->risk_tolerance ?: '-') ?></small></td>
                                                        <td><small><?= html_escape($ap->batas_eskalasi ?: '-') ?></small></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        Appetite = batas skor yang masih dapat diterima; Toleransi = rentang
                                        penyimpangan yang masih dimaklumi; Batas Eskalasi = skor yang wajib
                                        dilaporkan ke atasan.
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- ============ PENILAIAN ============ -->
                <h6 class="text-primary border-bottom pb-1 mb-3 mt-4">
                    <i class="fas fa-gavel"></i> Penilaian &amp; Keputusan
                </h6>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="nilai_evaluasi">Nilai Evaluasi (Residual)</label>
                        <!-- Dikunci: server selalu memakai nilai_residual; kolom ini hanya tampilan. -->
                        <input type="number" name="nilai_evaluasi" id="nilai_evaluasi" class="form-control" value="<?= $nilaiResidual ?>" readonly tabindex="-1" style="background:#e9ecef;">
                        <div id="badge_eval" class="mt-2"></div>
                        <small class="text-muted"><i class="fas fa-lock"></i> Tidak dapat diubah</small>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="keputusan">Keputusan <span class="text-danger">*</span></label>
                        <select name="keputusan" id="keputusan" class="form-control" required>
                            <?php foreach (['Accept', 'Monitor', 'Treat', 'Escalate'] as $k) : ?>
                                <option value="<?= $k ?>" <?= $val('keputusan', $defaultKeputusan) === $k ? 'selected' : '' ?>><?= $k ?><?= $k === $band ? ' (rekomendasi)' : '' ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div id="band_hint" class="mt-1"></div>
                        <small class="text-muted">Bingung? Buka <strong>Panduan memilih Keputusan</strong> di bawah.</small>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="triwulan">Triwulan <span class="text-danger">*</span></label>
                        <select name="triwulan" id="triwulan" class="form-control" required>
                            <?php for ($t = 1; $t <= 4; $t++) : ?>
                                <option value="<?= $t ?>" <?= (string) $val('triwulan', (int) ceil((int) date('n') / 3)) === (string) $t ? 'selected' : '' ?>>TW<?= $t ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="tanggal_evaluasi">Tanggal</label>
                        <input type="date" name="tanggal_evaluasi" id="tanggal_evaluasi" class="form-control" value="<?= html_escape($val('tanggal_evaluasi', date('Y-m-d'))) ?>">
                    </div>

                    <div class="form-group col-md-2">
                        <label>Kriteria Rujukan</label>
                        <input type="text" class="form-control" readonly tabindex="-1" style="background:#e9ecef;" value="Residual <?= $nilaiResidual ?> (<?= html_escape($analisa->level_residual) ?>)">
                        <small class="text-muted">Diisi otomatis</small>
                    </div>
                </div>

                <!-- ============ PANDUAN KEPUTUSAN ============
                     Memakai elemen details (bukan .alert / modal) supaya penjelasan tetap
                     bisa dibuka kapan saja tanpa memanjangkan form dan tanpa
                     terhapus script footer. -->
                <details class="border rounded bg-light p-3 mb-3">
                    <summary class="font-weight-bold text-primary" style="cursor:pointer">
                        <i class="fas fa-question-circle"></i> Panduan memilih Keputusan
                        <span class="text-muted small font-weight-normal">&mdash; klik untuk membuka</span>
                    </summary>

                    <div class="table-responsive mt-2">
                        <table class="table table-sm table-bordered bg-white mb-2">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width:100px">Keputusan</th>
                                    <th style="width:22%">Artinya</th>
                                    <th style="width:30%">Kapan dipilih</th>
                                    <th>Akibatnya di aplikasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge badge-secondary">Accept</span></td>
                                    <td><small>Risiko <strong>diterima apa adanya</strong>. Sisa risiko sudah dalam batas appetite dan dianggap wajar.</small></td>
                                    <td><small>Skor residual masih di bawah/sama dengan appetite kategori, dampaknya kecil, dan kontrol yang ada sudah memadai.<br>
                                            <em>Contoh: skor 6 (Rendah), appetite kategori 10.</em></small></td>
                                    <td><small>Tidak wajib membuat rencana perlakuan. Tetap dicatat di <strong>Monitoring</strong> sebagai pemantauan rutin.</small></td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-info">Monitor</span></td>
                                    <td><small>Risiko <strong>belum perlu ditangani sekarang</strong>, tetapi harus diawasi karena berpotensi naik.</small></td>
                                    <td><small>Skor masih dalam appetite namun <strong>mendekati batas</strong>, atau berada di level menengah dengan kontrol yang masih berjalan.<br>
                                            <em>Contoh: skor 12 (Sedang), appetite 15.</em></small></td>
                                    <td><small>Masuk daftar <strong>Monitoring</strong> tiap triwulan. Bila tren skor naik, evaluasi ulang dan ubah keputusannya menjadi <code>Treat</code>.</small></td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-warning">Treat</span></td>
                                    <td><small>Risiko <strong>harus diturunkan</strong> dengan tindakan nyata (mitigasi).</small></td>
                                    <td><small>Skor residual <strong>melebihi appetite</strong> kategori atau berada di level Tinggi, dan berdampak pada sasaran/KPI.<br>
                                            <em>Contoh: skor 18 (Tinggi), appetite 15.</em></small></td>
                                    <td><small>Wajib dibuat rencananya di menu <strong>Perlakuan</strong>: strategi (<code>AVOID / REDUCE / SHARE / ACCEPT</code>), uraian tindakan, PIC, jadwal, dan anggaran. Ikut terhitung sebagai &ldquo;perlu ditindaklanjuti&rdquo; di <strong>Laporan</strong>.</small></td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-danger">Escalate</span></td>
                                    <td><small>Risiko <strong>di luar kewenangan unit</strong>; keputusan atau dukungan diserahkan ke atasan.</small></td>
                                    <td><small>Skor melewati <strong>Batas Eskalasi</strong> kategori, berdampak lintas unit/strategis, atau butuh keputusan Direksi/Komite.<br>
                                            <em>Contoh: skor 22 dengan batas eskalasi 15.</em></small></td>
                                    <td><small>Centang <strong>Perlu Eskalasi</strong> dan isi <strong>Eskalasi ke</strong>; risiko muncul di kartu &ldquo;Risiko Perlu Eskalasi&rdquo; pada dashboard. Rencana perlakuan tetap perlu disusun.</small></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p class="small mb-1">
                        <i class="fas fa-info-circle text-primary"></i>
                        <strong>Rekomendasi band hanyalah saran otomatis</strong> dari skor residual
                        (skala aktif: <?= html_escape($skala_teks) ?>). Anda boleh memilih berbeda,
                        tetapi keputusan yang berbeda dari band akan ditandai
                        <span class="badge badge-warning">Perlu Review</span> dan harus ditinjau
                        lewat tombol <strong>Review</strong> di daftar Evaluasi.
                    </p>
                    <p class="small mb-0">
                        <strong>Alasan / Dasar Penilaian wajib diisi.</strong> Tuliskan alasan singkat
                        yang bisa dipertanggungjawabkan, misalnya:
                        <em>&ldquo;Skor 18 melebihi appetite 15 dan menyangkut 25 sumur bor, sehingga perlu
                            penggantian pompa bertahap.&rdquo;</em>
                        Keputusan <code>Escalate</code> tanpa mencentang <strong>Perlu Eskalasi</strong>
                        tidak akan muncul di kartu dashboard.
                    </p>
                </details>

                <div class="form-group">
                    <label for="alasan_evaluasi">Alasan / Dasar Penilaian <span class="text-danger">*</span></label>
                    <textarea name="alasan_evaluasi" id="alasan_evaluasi" rows="3" class="form-control" required placeholder="Mengapa keputusan tersebut dipilih"><?= html_escape($val('alasan_evaluasi')) ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" id="perlu_eskalasi" name="perlu_eskalasi" value="YA" class="custom-control-input" <?= $val('perlu_eskalasi') === 'YA' ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="perlu_eskalasi"><strong>Perlu Eskalasi</strong></label>
                        </div>
                    </div>
                    <div class="form-group col-md-9">
                        <label for="eskalasi_ke">Eskalasi ke (unit / pejabat)</label>
                        <input type="text" name="eskalasi_ke" id="eskalasi_ke" class="form-control" placeholder="mis. Direksi, Komite Audit, Manajer UPK" value="<?= html_escape($val('eskalasi_ke')) ?>">
                    </div>
                </div>

                <!-- ============ LEGENDA ============ -->
                <div class="card card-outline card-secondary mb-0">
                    <div class="card-header"><strong>Skala Level</strong></div>
                    <div class="card-body py-2">
                        <div>
                            <?php foreach ($tingkat as $t) : ?>
                                <span class="badge mr-1" style="background-color:<?= $t->warna ?>;color:#fff">
                                    <?= html_escape($t->nama_tingkat) ?> <?= (int) $t->skor_min ?>&ndash;<?= (int) $t->skor_max ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                        <?php if ($isEdit && $e->status_review) : ?>
                            <div class="mt-2">
                                <span class="badge badge-<?= $e->status_review === 'DITINJAU' ? 'success' : ($e->status_review === 'PERLU_REVIEW' ? 'warning' : 'secondary') ?>">
                                    Review: <?= html_escape($e->status_review === 'DITINJAU' ? 'Sudah ditinjau' : ($e->status_review === 'PERLU_REVIEW' ? 'Perlu review' : 'Sesuai band')) ?>
                                </span>
                                <?php if ($e->keputusan_band) : ?>
                                    <small class="text-muted">band = <?= html_escape($e->keputusan_band) ?></small>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($riwayat) : ?>
                            <hr class="my-2">
                            <strong class="small">Riwayat evaluasi terdahulu:</strong>
                            <ul class="list-unstyled small mb-0">
                                <?php foreach ($riwayat as $h) : ?>
                                    <li>
                                        TW<?= (int) $h->triwulan ?> &middot; <?= html_escape($h->tanggal_evaluasi) ?>
                                        &middot; skor <?= (int) $h->nilai_evaluasi ?>
                                        &middot; <span class="badge badge-<?= isset($warnaKeputusan[$h->keputusan]) ? $warnaKeputusan[$h->keputusan] : 'secondary' ?>"><?= html_escape($h->keputusan) ?></span>
                                        &middot; oleh <?= html_escape($h->oleh) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Evaluasi</button>
                <a href="<?= base_url('manrisk/evaluasi') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
                <!-- <a href="<?= base_url('manrisk/register/detail/' . $risiko->id_register) ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Batal
                </a> -->
            </div>

            <?= form_close() ?>
        </div>

    </div>
</section>
</div>

<script>
    var KAT = <?= json_encode($katJson, JSON_UNESCAPED_UNICODE) ?>;
    var BAND = <?= json_encode($band) ?>;
    var KEPUTUSAN_OPTIONS = ['Accept', 'Monitor', 'Treat', 'Escalate'];

    function gambarBadge() {
        var v = parseInt(document.getElementById('nilai_evaluasi').value, 10);
        var el = document.getElementById('badge_eval');
        if (!v || v < 1) {
            el.innerHTML = '';
            return;
        }
        for (var i = 0; i < KAT.length; i++) {
            if (v >= KAT[i].min && v <= KAT[i].max) {
                el.innerHTML = '<span class="badge badge-pill px-3 py-1" style="background-color:' +
                    KAT[i].warna + ';color:#fff">' + KAT[i].nama + ' (' + KAT[i].min + '-' + KAT[i].max + ')</span>';
                return;
            }
        }
        el.innerHTML = '';
    }

    function gambarHintBand() {
        var sel = document.getElementById('keputusan');
        var el = document.getElementById('band_hint');
        if (!BAND || !el) {
            if (el) el.innerHTML = '';
            return;
        }
        if (sel.value !== BAND) {
            el.innerHTML = '<small class="text-warning"><i class="fas fa-exclamation-triangle"></i> ' +
                'Berbeda dari band — akan masuk antrean <strong>Perlu Review</strong>.</small>';
        } else {
            el.innerHTML = '<small class="text-success"><i class="fas fa-check"></i> Sesuai rekomendasi band.</small>';
        }
    }

    gambarBadge();
    gambarHintBand();
    document.getElementById('keputusan').addEventListener('change', gambarHintBand);

    /* Tombol "Lihat tabel" pada Risk Appetite Kategori.
       Status terakhir diingat di localStorage supaya tabel tidak menutup sendiri. */
    (function() {
        var tombol = document.getElementById('tombol-appetite');
        var panel = document.getElementById('box-appetite');
        var chev = document.getElementById('chev-appetite');
        var teks = document.getElementById('teks-appetite');
        if (!tombol || !panel) return;

        var KUNCI = 'mri_appetite_terbuka';

        function setBuka(buka, simpan) {
            panel.classList.toggle('show', buka);
            tombol.setAttribute('aria-expanded', buka ? 'true' : 'false');
            if (teks) teks.textContent = buka ? 'Sembunyikan' : 'Lihat tabel';
            if (chev) {
                chev.classList.toggle('fa-chevron-down', !buka);
                chev.classList.toggle('fa-chevron-up', buka);
            }
            if (simpan) {
                try {
                    window.localStorage.setItem(KUNCI, buka ? '1' : '0');
                } catch (e) {}
            }
        }

        tombol.addEventListener('click', function() {
            setBuka(!panel.classList.contains('show'), true);
        });

        try {
            setBuka(window.localStorage.getItem(KUNCI) === '1', false);
        } catch (e) {}
    })();
</script>