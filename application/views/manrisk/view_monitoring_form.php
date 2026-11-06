<?php
/**
 * Formulir catatan monitoring (ISO 31000 Klausa 6.7).
 *
 * Menjawab empat pertanyaan review: apakah tindakan berjalan, apakah kontrol
 * masih efektif, apakah skor berubah, dan apakah risiko perlu ditinjau ulang.
 */
$m = $catatan;
$isEdit = $m !== null;

$val = function ($k, $default = '') use ($m) {
    if ($m && isset($m->{$k}) && $m->{$k} !== null) {
        return set_value($k, $m->{$k});
    }
    return set_value($k, $default);
};
?>
<section class="content">
    <div class="container-fluid">

        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-eye"></i> <?= strtoupper(html_escape($title)) ?>
                    <?php if ($isEdit) : ?>
                        <span class="badge badge-warning align-middle">Ubah catatan TW<?= (int) $m->triwulan ?></span>
                    <?php endif; ?>
                </h5>
                <div>
                    <a href="<?= base_url('manrisk/monitoring') ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-list"></i> Daftar Monitoring
                    </a>
                    <a href="<?= base_url('manrisk/register/detail/' . $risiko->id_register) ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Detail Risiko
                    </a>
                </div>
            </div>

            <?php if ($isEdit) : ?>
                <?= form_open('manrisk/monitoring/form/' . $risiko->id_register) ?>
                <input type="hidden" name="id_monitoring" value="<?= (int) $m->id_monitoring ?>">
            <?php else : ?>
                <?= form_open('manrisk/monitoring/form/' . $risiko->id_register) ?>
            <?php endif; ?>

            <?php if (validation_errors()) : ?>
                <div class="card-body pb-0">
                    <!-- Tanpa kelas .alert: templates/footer.php menghapus .alert otomatis. -->
                    <div class="border border-danger rounded text-danger bg-white p-2"><?= validation_errors() ?></div>
                </div>
            <?php endif; ?>

            <div class="card-body">

                <!-- ============ KONTEKS ============
                     Tanpa kelas .alert: templates/footer.php menghapus .alert otomatis. -->
                <div class="border rounded bg-light p-3 mb-3">
                    <div class="row">
                        <div class="col-md-3"><strong>Kode</strong><br><?= html_escape($risiko->kode_risiko) ?></div>
                        <div class="col-md-6"><strong>Risiko</strong><br><?= html_escape($risiko->risiko) ?></div>
                        <div class="col-md-3">
                            <strong>Residual Saat Ini</strong><br>
                            <?php if ($analisa) : ?>
                                <?= (int) $analisa->residual_l ?> &times; <?= (int) $analisa->residual_d ?>
                                = <strong><?= (int) $analisa->nilai_residual ?></strong>
                                <span class="badge badge-light"><?= html_escape($analisa->level_residual) ?></span>
                            <?php else : ?>
                                <span class="text-muted">belum dianalisis</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- ============ CATATAN ============ -->
                <h6 class="text-primary border-bottom pb-1 mb-3">
                    <i class="fas fa-clipboard-check"></i> Catatan Review
                </h6>

                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="tahun">Tahun</label>
                        <input type="number" name="tahun" id="tahun" class="form-control" min="2027" max="2099"
                               value="<?= $val('tahun', (int) $risiko->tahun) ?>">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="triwulan">Triwulan <span class="text-danger">*</span></label>
                        <select name="triwulan" id="triwulan" class="form-control" required>
                            <?php for ($t = 1; $t <= 4; $t++) : ?>
                                <option value="<?= $t ?>" <?= (string) $val('triwulan', (int) ceil((int) date('n') / 3)) === (string) $t ? 'selected' : '' ?>>TW<?= $t ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tanggal_review">Tanggal Review</label>
                        <input type="date" name="tanggal_review" id="tanggal_review" class="form-control"
                               value="<?= html_escape($val('tanggal_review', date('Y-m-d'))) ?>">
                    </div>
                    <div class="form-group col-md-5">
                        <label for="status_tindakan">Status Tindakan</label>
                        <input type="text" name="status_tindakan" id="status_tindakan" class="form-control"
                               placeholder="mis. Mitigasi berjalan 60%, pengadaan suku cadang tertunda"
                               value="<?= html_escape($val('status_tindakan')) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="hasil_monitoring">Hasil Monitoring <span class="text-danger">*</span></label>
                    <textarea name="hasil_monitoring" id="hasil_monitoring" rows="3" class="form-control"
                              placeholder="Temuan, kondisi aktual, dan kesesuaian dengan rencana tindakan"><?= html_escape($val('hasil_monitoring')) ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="kontrol_masih_efektif">Kontrol Masih Efektif <span class="text-danger">*</span></label>
                        <select name="kontrol_masih_efektif" id="kontrol_masih_efektif" class="form-control">
                            <?php foreach (['YA', 'SEBAGIAN', 'TIDAK'] as $k) : ?>
                                <option value="<?= $k ?>" <?= $val('kontrol_masih_efektif', 'YA') === $k ? 'selected' : '' ?>><?= $k ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <div class="custom-control custom-switch mt-4">
                            <input type="checkbox" id="perlu_reassess" name="perlu_reassess" value="YA"
                                   class="custom-control-input" <?= $val('perlu_reassess') === 'YA' ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="perlu_reassess"><strong>Perlu Tinjauan Ulang</strong></label>
                        </div>
                        <small class="text-muted d-block mt-1">
                            Centang bila risiko harus <strong>dinilai ulang</strong> — mis. kontrol tidak
                            efektif, skor naik, atau konteks berubah. Tindak lanjutnya: perbarui
                            <strong>Analisis</strong> (skor residual), lalu <strong>Evaluasi</strong>
                            (keputusan baru).
                        </small>
                    </div>
                </div>

                <!-- ============ SKOR ============
                     Skor Lama dikunci (snapshot). Skor Baru diisi lewat L x D
                     agar nilainya selalu hasil rumus, bukan angka bebas. -->
                <h6 class="text-primary border-bottom pb-1 mb-3 mt-4">
                    <i class="fas fa-calculator"></i> Skor Risiko
                    <small class="text-muted font-weight-normal">(Skor Lama terkunci &middot; Skor Baru = L &times; D)</small>
                </h6>

                <?php
                $labelArah = ['Naik' => 'danger', 'Turun' => 'success', 'Tetap' => 'secondary'];
                // Level awal Skor Baru (server-side); JS memperbarui saat L/D diubah.
                $levelBaru = null;
                foreach ((array) $tingkat as $t) {
                    if ($skor['baru'] !== null && $skor['baru'] >= (int) $t->skor_min && $skor['baru'] <= (int) $t->skor_max) {
                        $levelBaru = $t;
                        break;
                    }
                }
                $tingkatJs = [];
                foreach ((array) $tingkat as $t) {
                    $tingkatJs[] = [
                        'min'   => (int) $t->skor_min,
                        'max'   => (int) $t->skor_max,
                        'nama'  => $t->nama_tingkat,
                        'warna' => $t->warna,
                    ];
                }
                ?>
                <div class="border rounded bg-light p-3 mb-3">
                    <div class="form-row">
                        <div class="form-group col-md-3 mb-md-0">
                            <label class="mb-1">Skor Lama</label>
                            <input type="text" class="form-control" readonly tabindex="-1"
                                   style="background:#e9ecef;"
                                   value="<?= $skor['lama'] !== null ? (int) $skor['lama'] : '—' ?>">
                            <small class="text-muted">
                                <i class="fas fa-lock"></i>
                                <?= $isEdit ? 'Skor saat catatan ini dibuat' : 'Skor catatan monitoring terakhir' ?>
                                &mdash; tidak bisa diubah
                            </small>
                        </div>

                        <div class="form-group col-md-4 mb-md-0">
                            <label class="mb-1">Skor Baru <small class="text-muted">(L &times; D)</small></label>
                            <div class="d-flex align-items-center">
                                <select name="baru_l" id="baru_l" class="form-control" style="max-width:76px"
                                        required aria-label="Likelihood skor baru">
                                    <option value="">—</option>
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <option value="<?= $i ?>" <?= (int) $skor['l'] === $i ? 'selected' : '' ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                                <span class="mx-2">&times;</span>
                                <select name="baru_d" id="baru_d" class="form-control" style="max-width:76px"
                                        required aria-label="Dampak skor baru">
                                    <option value="">—</option>
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <option value="<?= $i ?>" <?= (int) $skor['d'] === $i ? 'selected' : '' ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                                <span class="mx-2">=</span>
                                <strong id="skor_baru_tampil" class="h4 mb-0"><?= $skor['baru'] !== null ? (int) $skor['baru'] : '—' ?></strong>
                                <span id="level_baru" class="badge ml-2"
                                      style="<?= $levelBaru ? 'background-color:' . html_escape($levelBaru->warna) . ';color:#fff' : 'display:none' ?>">
                                    <?= $levelBaru ? html_escape($levelBaru->nama_tingkat) : '' ?>
                                </span>
                            </div>
                            <small class="text-muted d-block mt-1">
                                Ubah L/D bila hasil review berbeda dari residual Analisa.
                            </small>
                        </div>

                        <div class="form-group col-md-3 mb-md-0">
                            <label class="mb-1">Arah Perubahan</label>
                            <div class="form-control-plaintext">
                                <?php if ($skor['perubahan']) : ?>
                                    <span id="arah_perubahan" class="badge badge-<?= $labelArah[$skor['perubahan']] ?> px-3 py-2">
                                        <?= $skor['perubahan'] === 'Naik' ? '<i class="fas fa-arrow-up"></i>' : ($skor['perubahan'] === 'Turun' ? '<i class="fas fa-arrow-down"></i>' : '<i class="fas fa-equals"></i>') ?>
                                        <?= html_escape($skor['perubahan']) ?>
                                    </span>
                                <?php else : ?>
                                    <span id="arah_perubahan" class="badge badge-light px-3 py-2">belum bisa dihitung</span>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted">Dihitung dari Skor Lama &rarr; Skor Baru</small>
                        </div>

                        <div class="form-group col-md-2 mb-md-0">
                            <label class="mb-1">Sumber Skor</label>
                            <div>
                                <a href="<?= base_url('manrisk/analisa/isi/' . $risiko->id_register) ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-pen"></i> Analisa
                                </a>
                            </div>
                            <small class="text-muted">Sinkronkan residual di sana.</small>
                        </div>
                    </div>
                </div>

                <?php if ($boleh_sinkron) : ?>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="sinkron_analisa"
                               name="sinkron_analisa" value="YA" <?= $sinkron_default ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="sinkron_analisa">
                            <strong>Perbarui Analisa juga</strong> &mdash; jadikan Skor Baru ini residual resmi
                        </label>
                        <small class="text-muted d-block ml-4">
                            Residual L &times; D, nilai, dan level di <strong>Analisa</strong> ikut diperbarui.
                            Keputusan <strong>Evaluasi</strong> tidak diubah otomatis; bila level berubah, tinjau Evaluasi.
                        </small>
                    </div>
                <?php elseif ($analisa && !$analisa_terbuka) : ?>
                    <div class="small text-muted mb-3">
                        <i class="fas fa-lock"></i>
                        Analisa risiko sedang dikunci &mdash; sinkronisasi ke Analisa tidak tersedia.
                    </div>
                <?php elseif ($analisa && $isEdit) : ?>
                    <div class="small text-muted mb-3">
                        <i class="fas fa-info-circle"></i>
                        Ini catatan lama &mdash; sinkronisasi ke Analisa hanya dilakukan dari catatan terakhir.
                    </div>
                <?php endif; ?>

                <div id="hint_kontrol" class="border border-warning rounded bg-white p-2 mb-3 small" style="display:none;">
                    <i class="fas fa-exclamation-circle text-warning"></i>
                    Kontrol belum efektif penuh. Pertimbangkan mencentang
                    <strong>Perlu Tinjauan Ulang</strong> dan memperbarui <strong>Analisa</strong>
                    agar skor residual mencerminkan kondisi terbaru.
                </div>

                <!-- Tanpa kelas .alert: templates/footer.php menghapus .alert otomatis. -->
                <div id="peringatan_analisa" class="border border-warning rounded bg-white p-3 mb-3"
                     style="<?= $skor['residual'] === null ? '' : 'display:none' ?>">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    <strong>Analisa belum diisi.</strong>
                    Skor Baru tetap bisa diisi di sini, tetapi sebaiknya lengkapi
                    <strong>Analisa</strong> (L &times; D residual) agar nilainya konsisten.
                    <a href="<?= base_url('manrisk/analisa/isi/' . $risiko->id_register) ?>"
                       class="btn btn-sm btn-primary ml-2">
                        <i class="fas fa-calculator"></i> Isi Analisa
                    </a>
                </div>

                <div id="hint_beda" class="border border-warning rounded bg-white p-3 mb-3"
                     style="<?= ($skor['residual'] !== null && $skor['baru'] !== null && (int) $skor['baru'] !== (int) $skor['residual']) ? '' : 'display:none' ?>">
                    <i class="fas fa-info-circle text-warning"></i>
                    Skor Baru <strong id="skor_beda"><?= $skor['baru'] !== null ? (int) $skor['baru'] : '—' ?></strong>
                    berbeda dari residual <strong>Analisa</strong> saat ini
                    (<span id="residual_beda"><?= $skor['residual'] !== null ? (int) $skor['residual'] : '—' ?></span>).
                    Monitoring menyimpan skor versi review &mdash; centang
                    <strong>Perbarui Analisa juga</strong> saat menyimpan agar keduanya konsisten.
                </div>

                <div class="form-group">
                    <label for="sebab_perubahan">Sebab Perubahan</label>
                    <input type="text" name="sebab_perubahan" id="sebab_perubahan" class="form-control"
                           placeholder="mis. Regulasi baru, kontrol tambahan, insiden serupa"
                           value="<?= html_escape($val('sebab_perubahan')) ?>">
                </div>

                <!-- ============ RIWAYAT ============ -->
                <?php if ($riwayat) : ?>
                    <h6 class="text-primary border-bottom pb-1 mb-3 mt-4" id="riwayat">
                        <i class="fas fa-history"></i> Riwayat Catatan Monitoring
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tanggal</th><th>TW</th><th>Status Tindakan</th><th>Hasil</th>
                                    <th class="text-center">Kontrol</th>
                                    <th class="text-center">Skor</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($riwayat as $h) : ?>
                                    <tr>
                                        <td><?= html_escape($h->tanggal_review) ?></td>
                                        <td><?= (int) $h->triwulan ?></td>
                                        <td><small><?= html_escape($h->status_tindakan ?: '-') ?></small></td>
                                        <td><small><?= html_escape($h->hasil_monitoring ?: '-') ?></small></td>
                                        <td class="text-center">
                                            <span class="badge <?= $h->kontrol_masih_efektif === 'YA' ? 'badge-success' : ($h->kontrol_masih_efektif === 'TIDAK' ? 'badge-danger' : 'badge-warning') ?>">
                                                <?= html_escape($h->kontrol_masih_efektif) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?= $h->nilai_lama !== null ? (int) $h->nilai_lama . ' &rarr; ' . (int) $h->nilai_baru : '—' ?>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <a href="<?= base_url('manrisk/monitoring/form/' . $risiko->id_register . '?id_monitoring=' . $h->id_monitoring) ?>"
                                               class="btn btn-sm btn-outline-secondary" title="Ubah">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ((int) $h->id_monitoring !== (int) $val('id_monitoring')) : ?>
                                                <a href="<?= base_url('manrisk/monitoring/hapus/' . $h->id_monitoring) ?>"
                                                   class="btn btn-sm btn-outline-danger" title="Hapus"
                                                   onclick="return confirm('Hapus catatan monitoring tanggal <?= html_escape($h->tanggal_review) ?>?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Catatan</button>
                <a href="<?= base_url('manrisk/monitoring') ?>" class="btn btn-outline-secondary">
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
        // 1) Pengingat kontrol: tidak efektif TIDAK otomatis mencentang reassess.
        var k = document.getElementById('kontrol_masih_efektif');
        var hKontrol = document.getElementById('hint_kontrol');
        function perbaruiKontrol() {
            if (k && hKontrol) {
                hKontrol.style.display = (k.value === 'YA') ? 'none' : 'block';
            }
        }
        if (k) {
            k.addEventListener('change', perbaruiKontrol);
            perbaruiKontrol();
        }

        // 2) Skor Baru = L x D: tampilkan hasil, level, arah, dan peringatan.
        var l = document.getElementById('baru_l');
        var d = document.getElementById('baru_d');
        var tampil = document.getElementById('skor_baru_tampil');
        var level = document.getElementById('level_baru');
        var arah = document.getElementById('arah_perubahan');
        var beda = document.getElementById('hint_beda');
        var tanpaAnalisa = document.getElementById('peringatan_analisa');
        var skorBeda = document.getElementById('skor_beda');
        var residualBeda = document.getElementById('residual_beda');
        if (!l || !d || !tampil) {
            return;
        }

        var LAMA = <?= $skor['lama'] !== null ? (int) $skor['lama'] : 'null' ?>;
        var RESIDUAL = <?= $skor['residual'] !== null ? (int) $skor['residual'] : 'null' ?>;
        var TINGKAT = <?= json_encode($tingkatJs, JSON_UNESCAPED_UNICODE) ?>;

        // Checkbox sinkronisasi: otomatis tercentang saat skor berbeda,
        // kecuali pengguna sudah memilih sendiri (centang/uncentang manual).
        var sinkron = document.getElementById('sinkron_analisa');
        var sinkronManual = false;
        if (sinkron) {
            sinkron.addEventListener('change', function () { sinkronManual = true; });
        }

        function perbaruiSkor(autoSinkron) {
            var lv = parseInt(l.value, 10);
            var dv = parseInt(d.value, 10);
            var baru = (lv && dv) ? lv * dv : null;
            tampil.textContent = baru === null ? '—' : baru;

            // Level risiko
            var t = null;
            if (baru !== null) {
                for (var i = 0; i < TINGKAT.length; i++) {
                    if (baru >= TINGKAT[i].min && baru <= TINGKAT[i].max) {
                        t = TINGKAT[i];
                        break;
                    }
                }
            }
            if (t) {
                level.textContent = t.nama;
                level.style.backgroundColor = t.warna;
                level.style.color = '#fff';
                level.style.display = '';
            } else {
                level.style.display = 'none';
            }

            // Arah perubahan
            if (baru === null || LAMA === null) {
                arah.className = 'badge badge-light px-3 py-2';
                arah.textContent = 'belum bisa dihitung';
            } else if (baru > LAMA) {
                arah.className = 'badge badge-danger px-3 py-2';
                arah.innerHTML = '<i class="fas fa-arrow-up"></i> Naik';
            } else if (baru < LAMA) {
                arah.className = 'badge badge-success px-3 py-2';
                arah.innerHTML = '<i class="fas fa-arrow-down"></i> Turun';
            } else {
                arah.className = 'badge badge-secondary px-3 py-2';
                arah.innerHTML = '<i class="fas fa-equals"></i> Tetap';
            }

            // Peringatan Analisa / selisih skor
            if (tanpaAnalisa) {
                tanpaAnalisa.style.display = (RESIDUAL === null) ? 'block' : 'none';
            }
            if (beda) {
                var bedaSkor = (RESIDUAL !== null && baru !== null && baru !== RESIDUAL);
                beda.style.display = bedaSkor ? 'block' : 'none';
                if (bedaSkor) {
                    if (skorBeda) { skorBeda.textContent = baru; }
                    if (residualBeda) { residualBeda.textContent = RESIDUAL; }
                }
            }

            // Sinkronisasi Analisa: default aktif saat skor berbeda.
            if (autoSinkron && sinkron && !sinkronManual && RESIDUAL !== null) {
                sinkron.checked = (baru !== null && baru !== RESIDUAL);
            }
        }

        l.addEventListener('change', function () { perbaruiSkor(true); });
        d.addEventListener('change', function () { perbaruiSkor(true); });
        perbaruiSkor(false);
    })();
</script>
