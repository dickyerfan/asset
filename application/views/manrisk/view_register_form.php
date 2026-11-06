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

/**
 * Nilai Sasaran/KPI saat ini (bisa dari data lama, bisa juga dari POST ulang
 * ketika validasi gagal). Dipakai untuk menebak apakah teksnya sama dengan
 * salah satu indikator Objektif RKAP, supaya dropdown bisa langsung terpilih.
 */
$sasaranNilai = (string) $e('sasaran_kpi');
$sasaranCocok = null;
foreach ($objektif_list as $o) {
    if ($sasaranNilai !== '' && $sasaranNilai === $o->indikator) {
        $sasaranCocok = (int) $o->id_objektif;
        break;
    }
}
$sasaranManual = ($sasaranNilai !== '' && $sasaranCocok === null);

/** Format angka target RKAP: 22.381.036.762 / 4,78 (tanpa nol berekor). */
$angkaRkap = function ($v) {
    $s = number_format((float) $v, 2, ',', '.');
    $s = rtrim(rtrim($s, '0'), ',');
    return $s === '' ? '0' : $s;
};
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
                        <small class="text-muted">Nomor dalam segmen.</small>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="kode_risiko">Kode Risiko <span class="text-danger">*</span></label>
                        <input type="text" name="kode_risiko" id="kode_risiko"
                               class="form-control" maxlength="30" required
                               placeholder="IJT-OPR-01" value="<?= $e('kode_risiko', html_escape($saran_kode)) ?>">
                        <small class="text-muted">Terisi otomatis dari segmen + urut. Harus unik.</small>
                    </div>
                    <div class="form-group col-md-1">
                        <label for="tahun">Tahun <span class="text-danger">*</span></label>
                        <input type="number" name="tahun" id="tahun" class="form-control"
                               min="2027" max="2099" value="<?= $e('tahun', 2027) ?>">
                        <small class="text-muted">Tahun anggaran.</small>
                    </div>
                    <div class="form-group col-md-1">
                        <label for="triwulan_awal">TW Awal</label>
                        <select name="triwulan_awal" id="triwulan_awal" class="form-control">
                            <?php for ($t = 1; $t <= 4; $t++) : ?>
                                <option value="<?= $t ?>" <?= (string) $e('triwulan_awal', 1) === (string) $t ? 'selected' : '' ?>><?= $t ?></option>
                            <?php endfor; ?>
                        </select>
                        <small class="text-muted">Mulai dipantau.</small>
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
                        <small class="text-muted">Pemilik risiko (risk owner).</small>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="id_kategori">Kategori <span class="text-danger">*</span></label>
                        <select name="id_kategori" id="id_kategori" class="form-control" required>
                            <option value="">— pilih —</option>
                            <?php foreach ($kategori_list as $k) : ?>
                                <option value="<?= $k->id_kategori ?>"
                                        data-deskripsi="<?= html_escape(isset($k->deskripsi_kat) ? $k->deskripsi_kat : '') ?>"
                                        data-contoh="<?= html_escape(isset($k->contoh_kat) ? $k->contoh_kat : '') ?>"
                                        <?= (string) $e('id_kategori') === (string) $k->id_kategori ? 'selected' : '' ?>>
                                    <?php $kodeKat = isset($k->kode_kat) ? trim($k->kode_kat) : ''; ?>
                                    <?= $kodeKat !== '' ? html_escape($kodeKat) . ' — ' : '' ?><?= html_escape($k->nama_kat) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Beda dengan segmen kode.</small>
                    </div>
                </div>

                <!-- Keterangan singkat kategori mengikuti pilihan dropdown.
                     Bila dirasa membingungkan: hapus blok <p id="info-kategori"> ini
                     dan script "info-kategori" di bagian bawah berkas. -->
                <p class="small text-muted mb-2" id="info-kategori">
                    <i class="fas fa-tag"></i>
                    <span id="info-kategori-teks">Pilih kategori untuk melihat penjelasan singkatnya.</span>
                </p>

                <!-- Penjelasan kode risiko (IJT-SEG-NN).
                     Sengaja TIDAK memakai kelas .alert: templates/footer.php menghapus
                     semua elemen .alert secara otomatis setelah ~7 detik, sehingga
                     keterangan ini akan hilang sendiri. -->
                <div class="mb-3">
                    <a href="#penjelasan-kode" id="toggle-penjelasan-kode"
                       class="small font-weight-bold text-primary"
                       role="button" aria-expanded="false" aria-controls="penjelasan-kode"
                       style="text-decoration:none">
                        <i class="fas fa-info-circle"></i>
                        Apa arti kode risiko <code>IJT-SEG-NN</code>?
                        <i class="fas fa-chevron-down ml-1" id="chev-penjelasan-kode"></i>
                    </a>
                    <div class="collapse mt-2" id="penjelasan-kode">
                        <div class="border rounded bg-white small p-3">
                            <p class="mb-1">
                                <i class="fas fa-info-circle text-primary"></i>
                                <strong>Arti kode risiko:</strong>
                                <code>IJT-SEG-NN</code> &mdash;
                                <code>IJT</code> = <strong>Ijen Tirta</strong> (penanda organisasi, selalu tetap),
                                <code>SEG</code> = segmen/tema risiko,
                                <code>NN</code> = nomor urut 2 digit di segmen tersebut.
                            </p>
                            <p class="mb-1">
                                <strong>Segmen bukan turunan Kategori.</strong>
                                Pilih segmen sesuai <em>tema isi risiko</em>, bukan berdasarkan dropdown Kategori.
                                Contoh: segmen <code>GOV</code> bisa memuat kategori Strategic sekaligus Fraud.
                            </p>
                            <details>
                                <summary class="text-primary" style="cursor:pointer">Lihat arti tiap segmen</summary>
                                <div class="table-responsive mt-2">
                                    <table class="table table-sm table-bordered bg-white mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width:90px">Segmen</th>
                                                <th style="width:190px">Arti</th>
                                                <th>Contoh isi risiko</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td><code>OPR</code></td><td>Operasional</td><td>gangguan produksi/distribusi, NRW, kontinuitas layanan</td></tr>
                                            <tr><td><code>KEU</code></td><td>Keuangan</td><td>piutang tak tertagih, likuiditas, penyalahgunaan anggaran</td></tr>
                                            <tr><td><code>GOV</code></td><td>Governance / tata kelola</td><td>konflik kepentingan, fraud, kelemahan pengawasan</td></tr>
                                            <tr><td><code>HR</code></td><td>SDM / Human Resources</td><td>kekurangan tenaga ahli, turnover, kompetensi</td></tr>
                                            <tr><td><code>IT</code></td><td>Teknologi Informasi</td><td>gangguan sistem billing, keamanan data, server down</td></tr>
                                            <tr><td><code>K3</code></td><td>Keselamatan &amp; Kesehatan Kerja</td><td>kecelakaan kerja, paparan bahan kimia, APD</td></tr>
                                            <tr><td><code>EXT</code></td><td>Eksternal</td><td>cuaca ekstrem, pemasok, kebijakan pemerintah</td></tr>
                                            <tr><td><code>PEL</code></td><td>Pelanggan</td><td>keluhan, pengaduan, kepuasan layanan</td></tr>
                                            <tr><td><code>REG</code></td><td>Regulasi / kepatuhan</td><td>perubahan aturan, sanksi, izin usaha</td></tr>
                                            <tr><td><code>AMDK</code></td><td>Unit usaha AMDK</td><td>risiko khusus lini Air Minum Dalam Kemasan</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <p class="text-muted mt-2 mb-0">
                                    Daftar di atas adalah segmen yang sudah dipakai di data.
                                    Mengetik segmen baru akan menambah pilihan baru secara permanen,
                                    jadi gunakan yang sudah ada bila sesuai.
                                </p>
                            </details>
                        </div>
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
                        <small class="text-muted">Dokumen lingkup yang berlaku; pilih yang berstatus FINAL.</small>
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
                        <small class="text-muted d-block">Ancaman = efek negatif; Peluang = efek positif yang bisa dimanfaatkan.</small>
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
                        <small class="text-muted d-block">C = kendali sudah memadai; UC = belum ada/belum memadai.</small>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="status_risiko">Status Risiko</label>
                        <select name="status_risiko" id="status_risiko" class="form-control">
                            <?php foreach (['AKTIF', 'SELESAI', 'NONAKTIF'] as $s) : ?>
                                <option value="<?= $s ?>" <?= $e('status_risiko', 'AKTIF') === $s ? 'selected' : '' ?>><?= $s ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">AKTIF = berjalan; SELESAI = ditutup; NONAKTIF = diarsipkan.</small>
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
                        <small class="text-muted">
                            <strong>Apa yang terjadi</strong> — bukan penyebab, bukan akibat.
                            Contoh: <em>"Pipa bocor di jalur distribusi utama"</em>.
                        </small>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="penyebab">Penyebab / Sumber <span class="text-danger">*</span></label>
                        <textarea name="penyebab" id="penyebab" rows="2" class="form-control" required><?= html_escape($e('penyebab')) ?></textarea>
                        <small class="text-muted">
                            <strong>Mengapa bisa terjadi</strong> — akar masalah atau kelemahan kendali.
                            Contoh: <em>"Pipa tua, tekanan tidak terkendali, inspeksi belum rutin"</em>.
                        </small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="sasaran_kpi">Sasaran / KPI RKAP <span class="text-danger">*</span></label>
                        <select id="sasaran_pilih" class="form-control mb-1"
                                aria-label="Pilih dari daftar Objektif RKAP">
                            <option value="">— pilih dari Objektif RKAP (opsional) —</option>
                            <?php foreach ($objektif_list as $o) : ?>
                                <option value="<?= (int) $o->id_objektif ?>"
                                        data-indikator="<?= html_escape($o->indikator) ?>"
                                        <?= $sasaranCocok === (int) $o->id_objektif ? 'selected' : '' ?>>
                                    <?= html_escape($o->indikator) ?><?php
                                        if ($o->nilai_rkap !== null) {
                                            echo ' — target ' . html_escape($angkaRkap($o->nilai_rkap));
                                            if ($o->satuan) {
                                                echo ' ' . html_escape($o->satuan);
                                            }
                                        }
                                        if (!empty($o->nama_bagian)) {
                                            echo ' · ' . html_escape($o->nama_bagian);
                                        }
                                    ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="__manual" <?= $sasaranManual ? 'selected' : '' ?>>
                                — tulis sendiri (di luar daftar) —
                            </option>
                        </select>
                        <textarea name="sasaran_kpi" id="sasaran_kpi" rows="2" class="form-control" required
                                  placeholder="mis. Volume Air Terjual (m³) — target 12.500.000 m³"><?= html_escape($sasaranNilai) ?></textarea>
                        <small class="text-muted">
                            Pilih dari daftar agar teksnya sama dengan <strong>Objektif RKAP</strong> tahun ini;
                            isinya otomatis masuk ke kotak di atas dan masih boleh disunting.
                            Objektif yang dipilih juga otomatis tercentang di bagian
                            <em>Objektif RKAP Terkait</em>. Bila KPI belum ada di daftar, tulis langsung di kotak.
                        </small>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sumber_risiko">Sumber Risiko Tambahan</label>
                        <input type="text" name="sumber_risiko" id="sumber_risiko" class="form-control"
                               value="<?= html_escape($e('sumber_risiko')) ?>">
                        <small class="text-muted">
                            Bukti atau rujukan yang mendasari risiko ini: riwayat kejadian, temuan audit,
                            keluhan pelanggan, data tren. Contoh: <em>"Pemadaman listrik 3&times; dalam sebulan terakhir"</em>.
                        </small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="indikator_emerging">Indikator Emerging</label>
                        <input type="text" name="indikator_emerging" id="indikator_emerging" class="form-control"
                               placeholder="mis. Perubahan regulasi air minum, tren kemarau panjang"
                               value="<?= html_escape($e('indikator_emerging')) ?>">
                        <small class="text-muted">
                            <strong>Sinyal awal / peringatan dini</strong> — gejala perubahan yang
                            <em>belum</em> menjadi risiko saat ini, tetapi bisa memunculkan risiko baru.
                            Isi bila sudah terlihat tandanya; kosongkan bila belum ada.
                            Contoh: <em>"Rencana perubahan tarif", "Musim kemarau diprediksi lebih panjang"</em>.
                        </small>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="asumsi_bias">Asumsi &amp; Bias</label>
                        <input type="text" name="asumsi_bias" id="asumsi_bias" class="form-control"
                               placeholder="mis. Genset diasumsikan selalu siap pakai"
                               value="<?= html_escape($e('asumsi_bias')) ?>">
                        <small class="text-muted">
                            <strong>Asumsi yang dipakai</strong> saat menilai risiko dan
                            <strong>bias</strong> yang mungkin muncul (mis. terlalu optimistis pada proyeksi).
                            Menuliskan ini membuat penilaian lebih jujur dan mudah ditinjau ulang.
                        </small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="kegiatan">Kegiatan / Proses yang Terdampak</label>
                    <input type="text" name="kegiatan" id="kegiatan" class="form-control"
                           value="<?= html_escape($e('kegiatan')) ?>">
                    <small class="text-muted">
                        Kegiatan/proses yang terkena dampak risiko. Contoh:
                        <em>"Produksi &amp; distribusi air dari 25 sumur bor"</em>.
                        Dibiarkan kosong akan diisi otomatis dari Sasaran / KPI.
                    </small>
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
                        <strong>Dampak bila risiko terjadi</strong> — satu akibat per baris.
                        Format opsional per baris: <code>Jenis|Teks</code>, dengan Jenis
                        <code>Finansial</code> atau <code>Non-Finansial</code> (bila dihilangkan
                        otomatis <em>Non-Finansial</em>). Contoh:
                        <code>Finansial|Kerugian sampai Rp 500 juta</code>.
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
                        <small class="text-muted">
                            Centang proses bisnis yang terkena dampak (boleh lebih dari satu).
                            Inilah "Risk Universe" yang dipakai saat pelaporan.
                        </small>
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
                        <small class="text-muted">
                            <strong>Wajib pilih minimal satu.</strong> Centang sasaran RKAP yang
                            terpengaruh risiko ini — inilah kaitan risiko &harr; sasaran.
                            Bila memilih dari dropdown Sasaran / KPI di atas, centangannya terisi otomatis.
                        </small>
                    </div>
                </div>

                <!-- ================= CATATAN ================= -->
                <div class="form-group">
                    <label for="keterangan">Catatan</label>
                    <textarea name="keterangan" id="keterangan" rows="2" class="form-control"><?= html_escape($e('keterangan')) ?></textarea>
                    <small class="text-muted">
                        Catatan internal: prioritas, tindak lanjut, atau alasan khusus.
                        Contoh: <em>"Prioritas tinggi — RKAP 2027 fokus efisiensi"</em>.
                    </small>
                </div>

                <!-- ================= LEGENDA =================
                     Tanpa kelas .alert agar tidak ikut dihapus oleh footer.php. -->
                <div class="border rounded bg-light p-3 mb-0">
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
/* Tautan "Apa arti kode risiko" — buka/tutup panel penjelasan.
   Status terakhir diingat di localStorage supaya panel tidak menutup sendiri
   setelah beberapa waktu atau setelah halaman dimuat ulang. */
(function () {
    var tautan = document.getElementById('toggle-penjelasan-kode');
    var panel  = document.getElementById('penjelasan-kode');
    var chev   = document.getElementById('chev-penjelasan-kode');
    if (!tautan || !panel) return;

    var KUNCI = 'mri_penjelasan_kode_terbuka';

    function ikon(buka) {
        if (!chev) return;
        chev.classList.toggle('fa-chevron-down', !buka);
        chev.classList.toggle('fa-chevron-up', buka);
    }

    function setBuka(buka, simpan) {
        panel.classList.toggle('show', buka);
        tautan.setAttribute('aria-expanded', buka ? 'true' : 'false');
        ikon(buka);
        if (simpan) {
            try {
                window.localStorage.setItem(KUNCI, buka ? '1' : '0');
            } catch (e) {
                /* localStorage diblokir peramban: cukup diabaikan */
            }
        }
    }

    var tersimpan = null;
    try {
        tersimpan = window.localStorage.getItem(KUNCI);
    } catch (e) {
        /* abaikan */
    }
    setBuka(tersimpan === '1', false);

    tautan.addEventListener('click', function (e) {
        e.preventDefault();
        setBuka(!panel.classList.contains('show'), true);
    });
})();
</script>

<script>
/* Dropdown "Sasaran / KPI RKAP" -> isi otomatis teks sasaran dan centang
   Objektif RKAP yang sama. Teks tetap bisa disunting manual. */
(function () {
    var pilih = document.getElementById('sasaran_pilih');
    var teks  = document.getElementById('sasaran_kpi');
    if (!pilih || !teks) return;

    pilih.addEventListener('change', function () {
        var opt = pilih.options[pilih.selectedIndex];
        if (!opt) return;

        if (opt.value === '__manual') {
            teks.focus();
            return;
        }
        if (opt.value === '') {
            return; // placeholder: biarkan teks yang sudah ada
        }

        var indikator = opt.getAttribute('data-indikator') || '';
        if (indikator !== '') {
            teks.value = indikator;
        }

        var kotak = document.getElementById('obj_' + opt.value);
        if (kotak && !kotak.checked) {
            kotak.checked = true;
        }
    });
})();
</script>

<script>
/* Keterangan singkat kategori mengikuti pilihan dropdown (data dari
   mri_kategori_risiko: deskripsi_kat + contoh_kat). */
(function () {
    var pilih = document.getElementById('id_kategori');
    var teks  = document.getElementById('info-kategori-teks');
    if (!pilih || !teks) return;

    var BATAS = 180;

    function pendek(s) {
        s = (s || '').replace(/\s+/g, ' ').trim();
        return s.length > BATAS ? s.slice(0, BATAS).replace(/[\s,;:.]+$/, '') + '…' : s;
    }

    function tampil() {
        var opt = pilih.options[pilih.selectedIndex];
        if (!opt || !opt.value) {
            teks.textContent = 'Pilih kategori untuk melihat penjelasan singkatnya.';
            return;
        }
        var label = pendek(opt.text);
        var desc  = pendek(opt.getAttribute('data-deskripsi'));
        var contoh = pendek(opt.getAttribute('data-contoh'));

        var out = label;
        if (desc) out += ' — ' + desc;
        if (contoh) out += ' Contoh: ' + contoh + '.';
        teks.textContent = out;
    }

    pilih.addEventListener('change', tampil);
    tampil();
})();
</script>

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
