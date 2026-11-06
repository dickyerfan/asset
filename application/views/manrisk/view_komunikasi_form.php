<?php

/**
 * Formulir sesi Komunikasi & Konsultasi (ISO 31000 Klausa 6.2).
 * Struktur mengikuti materi QRMO slide 31.
 */
$k = $catatan;
$isEdit = $k !== null;
$foto = isset($foto) ? $foto : [];

$fotoUrl = function ($nama) {
    return base_url('assets/uploads/manrisk/komunikasi/' . rawurlencode(basename((string) $nama)));
};
$fotoAda = function ($nama) {
    return $nama && is_file(rtrim(FCPATH, '/\\') . '/assets/uploads/manrisk/komunikasi/' . basename((string) $nama));
};

$val = function ($key, $default = '') use ($k) {
    if ($k && isset($k->{$key}) && $k->{$key} !== null) {
        return set_value($key, $k->{$key});
    }
    return set_value($key, $default);
};

$centang = function ($key, $default = 0) use ($k) {
    $nilai = ($k && isset($k->{$key})) ? (int) $k->{$key} : (int) $default;
    return (string) set_value($key, $nilai) === '1';
};

$pilih = function ($key, $nilai) use ($val) {
    return (string) $val($key) === (string) $nilai ? 'selected' : '';
};

$unitSendiri = (int) $this->session->userdata('id_bagian');
$namaUnitSendiri = '';
foreach ($unit_list as $u) {
    if ((int) $u->id_bagian === $unitSendiri) {
        $namaUnitSendiri = $u->nama_bagian;
        break;
    }
}
?>
<section class="content">
    <div class="container-fluid">

        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-comments"></i> <?= strtoupper(html_escape($title)) ?>
                    <?php if ($isEdit) : ?>
                        <span class="badge badge-warning align-middle">Ubah Sesi</span>
                    <?php endif; ?>
                </h5>
                <a href="<?= base_url('manrisk/komunikasi?tahun=' . (int) $tahun) ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-list"></i> Daftar Komunikasi
                </a>
            </div>

            <?= form_open(
                'manrisk/komunikasi/form' . ($isEdit ? '/' . $k->id_komunikasi : ''),
                ['enctype' => 'multipart/form-data']
            ) ?>

            <?php if (validation_errors()) : ?>
                <div class="card-body pb-0">
                    <div class="border border-danger rounded text-danger bg-white p-2"><?= validation_errors() ?></div>
                </div>
            <?php endif; ?>

            <div class="card-body">

                <!-- ============ IDENTITAS SESI ============ -->
                <h6 class="text-primary border-bottom pb-1 mb-3">
                    <i class="fas fa-id-card"></i> Identitas Sesi
                </h6>

                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="tahun">Tahun <span class="text-danger">*</span></label>
                        <input type="number" name="tahun" id="tahun" class="form-control" min="2027" max="2099" value="<?= html_escape($val('tahun', (int) $tahun)) ?>" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="triwulan">Triwulan <span class="text-danger">*</span></label>
                        <select name="triwulan" id="triwulan" class="form-control" required>
                            <?php for ($t = 1; $t <= 4; $t++) : ?>
                                <option value="<?= $t ?>" <?= $pilih('triwulan', $t) ?>><?= 'TW' . $t ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= html_escape($val('tanggal', date('Y-m-d'))) ?>" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="metode">Metode <span class="text-danger">*</span></label>
                        <select name="metode" id="metode" class="form-control" required>
                            <?php foreach ($metode_list as $m) : ?>
                                <option value="<?= html_escape($m) ?>" <?= $pilih('metode', $m) ?>><?= html_escape($m) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="pic_komunikasi">PIC / Fasilitator</label>
                        <input type="text" name="pic_komunikasi" id="pic_komunikasi" class="form-control" maxlength="100" placeholder="Nama pemimpin / pencatat sesi" value="">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="id_upk">Unit / Bagian</label>
                        <?php if ($akses_semua) : ?>
                            <select name="id_upk" id="id_upk" class="form-control">
                                <option value="">— seluruh organisasi —</option>
                                <?php foreach ($unit_list as $u) : ?>
                                    <option value="<?= (int) $u->id_bagian ?>" <?= $pilih('id_upk', $u->id_bagian) ?>>
                                        <?= html_escape($u->nama_bagian) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php else : ?>
                            <input type="text" class="form-control" value="<?= html_escape($namaUnitSendiri ?: '-') ?>" readonly>
                            <small class="text-muted">Sesi dicatat untuk unit Anda.</small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group col-md-7">
                        <label for="topik">Topik / Agenda <span class="text-danger">*</span></label>
                        <input type="text" name="topik" id="topik" class="form-control" maxlength="200" required placeholder="mis. Konsultasi sasaran penurunan NRW TW1 2027" value="<?= html_escape($val('topik')) ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="id_lingkup">Dokumen Lingkup (opsional)</label>
                        <select name="id_lingkup" id="id_lingkup" class="form-control">
                            <option value="">— tidak ditautkan —</option>
                            <?php foreach ($lingkup as $l) : ?>
                                <option value="<?= (int) $l->id_lingkup ?>" <?= $pilih('id_lingkup', $l->id_lingkup) ?>>
                                    <?= html_escape($l->nama_lingkup) ?> (<?= html_escape($l->status) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="id_register">Risiko yang Dibahas (opsional)</label>
                        <select name="id_register" id="id_register" class="form-control">
                            <option value="">— belum / tidak terkait satu risiko —</option>
                            <?php foreach ($risiko as $r) : ?>
                                <option value="<?= (int) $r->id_register ?>" <?= $pilih('id_register', $r->id_register) ?>>
                                    <?= html_escape($r->kode_risiko) ?> — <?= html_escape($r->risiko) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="pihak_terlibat">Pihak yang Dilibatkan</label>
                    <textarea name="pihak_terlibat" id="pihak_terlibat" rows="4" class="form-control" placeholder="Satu pihak per baris, mis.&#10;Direksi / Manajemen&#10;Produksi &amp; Distribusi&#10;Keuangan&#10;IT / GIS&#10;SPI / Assurance"><?= html_escape($val('pihak_terlibat')) ?></textarea>
                    <small class="text-muted">
                        Libatkan pihak yang memiliki informasi, kewenangan, atau terdampak keputusan risiko.
                    </small>
                </div>

                <!-- ============ FOTO PENDUKUNG ============ -->
                <h6 class="text-primary border-bottom pb-1 mb-1">
                    <i class="fas fa-camera"></i> Foto Pendukung
                </h6>
                <p class="small text-muted mb-3">
                    Unggah dokumentasi rapat/kegiatan. Bisa lebih dari satu foto, misalnya foto suasana rapat
                    atau tangkapan layar materi yang dibahas.
                </p>

                <div class="form-row">
                    <div class="form-group col-md-7">
                        <div class="custom-file">
                            <input type="file" name="foto[]" id="foto" class="custom-file-input" accept=".jpg,.jpeg,.png,image/jpeg,image/png" multiple>
                            <label class="custom-file-label" for="foto">Pilih satu atau beberapa foto...</label>
                        </div>
                        <small class="text-muted">JPG/PNG, maksimal 2 MB per foto.</small>
                    </div>
                </div>

                <?php if ($foto) : ?>
                    <div class="row">
                        <?php foreach ($foto as $f) :
                            $bisaTampil = $fotoAda($f->nama_file);
                        ?>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="border rounded p-2 h-100">
                                    <?php if ($bisaTampil) : ?>
                                        <a href="<?= $fotoUrl($f->nama_file) ?>" target="_blank" rel="noopener">
                                            <img src="<?= $fotoUrl($f->nama_file) ?>" class="img-fluid rounded" style="width:100%;height:110px;object-fit:cover" alt="Foto pendukung sesi">
                                        </a>
                                    <?php else : ?>
                                        <div class="text-muted small py-4 text-center">
                                            <i class="fas fa-image"></i><br>Berkas tidak ditemukan
                                        </div>
                                    <?php endif; ?>
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox" class="custom-control-input" id="hapus_foto_<?= (int) $f->id_foto ?>" name="hapus_foto[]" value="<?= (int) $f->id_foto ?>">
                                        <label class="custom-control-label text-danger" for="hapus_foto_<?= (int) $f->id_foto ?>">
                                            Hapus foto
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- ============ TIGA LANGKAH ============ -->
                <h6 class="text-primary border-bottom pb-1 mb-1">
                    <i class="fas fa-route"></i> Tiga Langkah Komunikasi
                </h6>
                <p class="small text-muted mb-3">
                    Komunikasi &amp; konsultasi menghasilkan pemahaman bersama sebelum risiko dinilai dan ditangani.
                </p>

                <div class="row">
                    <div class="col-md-4">
                        <div class="border rounded h-100 p-3 mb-3">
                            <span class="badge badge-primary">1</span>
                            <strong class="ml-1">Dengarkan</strong>
                            <p class="small text-muted mt-2 mb-2">Kumpulkan informasi dari Risk Owner dan stakeholder.</p>
                            <textarea name="catatan_dengar" rows="4" class="form-control" placeholder="Informasi, data, keluhan, atau pengalaman yang disampaikan peserta"><?= html_escape($val('catatan_dengar')) ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded h-100 p-3 mb-3">
                            <span class="badge badge-primary">2</span>
                            <strong class="ml-1">Selaraskan</strong>
                            <p class="small text-muted mt-2 mb-2">Samakan pemahaman tentang sasaran, risiko, dan kontrol.</p>
                            <textarea name="catatan_selaras" rows="4" class="form-control" placeholder="Bagian yang disepakati / masih berbeda persepsi"><?= html_escape($val('catatan_selaras')) ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded h-100 p-3 mb-3">
                            <span class="badge badge-primary">3</span>
                            <strong class="ml-1">Uji &amp; Konfirmasi</strong>
                            <p class="small text-muted mt-2 mb-2">Klarifikasi asumsi dan pastikan informasi dapat dipertanggungjawabkan.</p>
                            <textarea name="catatan_konfirmasi" rows="4" class="form-control" placeholder="Asumsi yang diklarifikasi, bukti, dan konfirmasi peserta"><?= html_escape($val('catatan_konfirmasi')) ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- ============ EMPAT PERTANYAAN ============ -->
                <h6 class="text-primary border-bottom pb-1 mb-1 mt-3">
                    <i class="fas fa-question-circle"></i> Empat Pertanyaan Kunci
                </h6>
                <p class="small text-muted mb-3">Jawaban dicatat sebagai hasil konsultasi yang disepakati, bukan catatan pribadi.</p>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="q_sasaran">1. Apa sasarannya?</label>
                        <textarea name="q_sasaran" id="q_sasaran" rows="3" class="form-control" placeholder="Sasaran SMART yang hendak dicapai"><?= html_escape($val('q_sasaran')) ?></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="q_risiko">2. Apa risikonya?</label>
                        <textarea name="q_risiko" id="q_risiko" rows="3" class="form-control" placeholder="Peristiwa yang dapat menghambat sasaran"><?= html_escape($val('q_risiko')) ?></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="q_kontrol">3. Kontrol apa yang sudah ada?</label>
                        <textarea name="q_kontrol" id="q_kontrol" rows="3" class="form-control" placeholder="Kontrol, SOP, atau tindakan yang sudah berjalan"><?= html_escape($val('q_kontrol')) ?></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="q_tindakan">4. Apa tindakan selanjutnya?</label>
                        <textarea name="q_tindakan" id="q_tindakan" rows="3" class="form-control" placeholder="Kesepakatan tindakan, PIC, dan waktu"><?= html_escape($val('q_tindakan')) ?></textarea>
                    </div>
                </div>

                <!-- ============ LIMA HASIL ============ -->
                <h6 class="text-primary border-bottom pb-1 mb-1 mt-3">
                    <i class="fas fa-check-double"></i> Hasil yang Diharapkan
                    <span id="hitung_hasil" class="badge badge-secondary ml-1">0/5</span>
                </h6>
                <p class="small text-muted mb-3">Centang hasil yang benar-benar sudah diperoleh pada sesi ini.</p>

                <div class="row">
                    <div class="col-md-6">
                        <?php
                        $daftarHasil = [
                            'hasil_pemahaman'     => ['Pemahaman sasaran yang sama', 'Semua pihak memahami sasaran dan ukurannya.'],
                            'hasil_prioritas'     => ['Risiko dan prioritas teridentifikasi', 'Risiko utama serta urutan prioritasnya jelas.'],
                            'hasil_validasi'      => ['Penyebab &amp; dampak tervalidasi', 'Penyebab dan akibat sudah diuji dengan data/pengalaman.'],
                        ];
                        foreach ($daftarHasil as $key => $info) : ?>
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="hidden" name="<?= $key ?>" value="0">
                                <input type="checkbox" class="custom-control-input cek-hasil" id="<?= $key ?>" name="<?= $key ?>" value="1" <?= $centang($key) ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="<?= $key ?>">
                                    <strong><?= $info[0] ?></strong><br>
                                    <small class="text-muted"><?= $info[1] ?></small>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-md-6">
                        <?php
                        $daftarHasil2 = [
                            'hasil_kesepakatan'   => ['Mitigasi dan kontrol disepakati', 'Tindakan pengendalian disetujui bersama.'],
                            'hasil_tindak_lanjut' => ['PIC &amp; tindak lanjut jelas', 'Ada pemilik tindakan, batas waktu, dan mekanisme pantau.'],
                        ];
                        foreach ($daftarHasil2 as $key => $info) : ?>
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="hidden" name="<?= $key ?>" value="0">
                                <input type="checkbox" class="custom-control-input cek-hasil" id="<?= $key ?>" name="<?= $key ?>" value="1" <?= $centang($key) ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="<?= $key ?>">
                                    <strong><?= $info[0] ?></strong><br>
                                    <small class="text-muted"><?= $info[1] ?></small>
                                </label>
                            </div>
                        <?php endforeach; ?>

                        <div id="hint_lengkap" class="border border-success rounded bg-white p-2 mt-3 small" style="display:none;">
                            <i class="fas fa-check-circle text-success"></i>
                            Lima hasil sudah lengkap. Bila sesi memang tuntas, ubah <strong>Status Sesi</strong>
                            menjadi <strong>SELESAI</strong>.
                        </div>
                    </div>
                </div>

                <!-- ============ KESIMPULAN & TINDAK LANJUT ============ -->
                <h6 class="text-primary border-bottom pb-1 mb-3 mt-4">
                    <i class="fas fa-clipboard-check"></i> Kesimpulan &amp; Tindak Lanjut
                </h6>

                <div class="form-group">
                    <label for="ringkasan_hasil">Ringkasan Hasil Sesi</label>
                    <textarea name="ringkasan_hasil" id="ringkasan_hasil" rows="3" class="form-control" placeholder="Kesimpulan bersama: sasaran, risiko, kontrol, dan keputusan yang disepakati"><?= html_escape($val('ringkasan_hasil')) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="tindak_lanjut">Tindak Lanjut</label>
                    <textarea name="tindak_lanjut" id="tindak_lanjut" rows="2" class="form-control" placeholder="Apa yang harus dilakukan setelah sesi ini?"><?= html_escape($val('tindak_lanjut')) ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="pic_tindak_lanjut">PIC Tindak Lanjut</label>
                        <input type="text" name="pic_tindak_lanjut" id="pic_tindak_lanjut" class="form-control" maxlength="100" placeholder="Nama / jabatan penanggung jawab" value="<?= html_escape($val('pic_tindak_lanjut')) ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="target_selesai">Target Selesai</label>
                        <input type="date" name="target_selesai" id="target_selesai" class="form-control" value="<?= html_escape($val('target_selesai')) ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="status_tindak_lanjut">Status Tindak Lanjut <span class="text-danger">*</span></label>
                        <select name="status_tindak_lanjut" id="status_tindak_lanjut" class="form-control" required>
                            <?php
                            $labelLanjut = ['BELUM' => 'Belum dimulai', 'PROSES' => 'Dalam proses', 'SELESAI' => 'Selesai'];
                            foreach ($lanjut_list as $s) : ?>
                                <option value="<?= $s ?>" <?= $pilih('status_tindak_lanjut', $s) ?>>
                                    <?= isset($labelLanjut[$s]) ? $labelLanjut[$s] : $s ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label for="status">Status Sesi <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-control" style="max-width:240px" required>
                        <?php foreach ($status_list as $s) : ?>
                            <option value="<?= $s ?>" <?= $pilih('status', $s) ?>>
                                <?= $s === 'SELESAI' ? 'SELESAI — catatan tuntas' : 'DRAFT — masih dilengkapi' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted d-block">
                        Sesi <strong>SELESAI</strong> dihitung pada progres tahap 1 di dashboard.
                    </small>
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Sesi</button>
                <a href="<?= base_url('manrisk/komunikasi?tahun=' . (int) $tahun) ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>

            <?= form_close() ?>
        </div>

    </div>
</section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var kotak = document.querySelectorAll('.cek-hasil');
        var hitung = document.getElementById('hitung_hasil');
        var hint = document.getElementById('hint_lengkap');

        function perbarui() {
            var n = 0;
            for (var i = 0; i < kotak.length; i++) {
                if (kotak[i].checked) {
                    n++;
                }
            }
            if (hitung) {
                hitung.textContent = n + '/5';
                hitung.className = 'badge ml-1 ' + (n >= 5 ? 'badge-success' : (n > 0 ? 'badge-warning' : 'badge-secondary'));
            }
            if (hint) {
                hint.style.display = n >= 5 ? 'block' : 'none';
            }
        }

        for (var i = 0; i < kotak.length; i++) {
            kotak[i].addEventListener('change', perbarui);
        }
        perbarui();

        var inputFoto = document.getElementById('foto');
        if (inputFoto) {
            inputFoto.addEventListener('change', function() {
                var label = this.nextElementSibling;
                if (!label) {
                    return;
                }
                if (!this.files.length) {
                    label.textContent = 'Pilih satu atau beberapa foto...';
                } else if (this.files.length === 1) {
                    label.textContent = this.files[0].name;
                } else {
                    label.textContent = this.files.length + ' foto dipilih';
                }
            });
        }
    });
</script>