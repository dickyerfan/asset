<?php

/**
 * Detail satu sesi Komunikasi & Konsultasi (ISO 31000 Klausa 6.2).
 */
$k = $catatan;
$foto = isset($foto) ? $foto : [];

$teks = function ($nilai, $kosong = '—') {
    $s = trim((string) $nilai);
    return $s === '' ? '<span class="text-muted">' . $kosong . '</span>' : nl2br(html_escape($s));
};
$tanggal = function ($t) {
    return $t ? date('d/m/Y', strtotime($t)) : '—';
};
$fotoUrl = function ($nama) {
    return base_url('assets/uploads/manrisk/komunikasi/' . rawurlencode(basename((string) $nama)));
};
$fotoAda = function ($nama) {
    return $nama && is_file(rtrim(FCPATH, '/\\') . '/assets/uploads/manrisk/komunikasi/' . basename((string) $nama));
};

$langkahList = [
    'catatan_dengar'     => 'Dengarkan',
    'catatan_selaras'    => 'Selaraskan / Pahami',
    'catatan_konfirmasi' => 'Uji &amp; Konfirmasi',
];
$pertanyaanList = [
    'q_sasaran'  => 'Apa sasarannya?',
    'q_risiko'   => 'Apa risikonya?',
    'q_kontrol'  => 'Kontrol apa yang sudah ada?',
    'q_tindakan' => 'Apa tindakan selanjutnya?',
];
$hasilList = [
    'hasil_pemahaman'     => 'Pemahaman sasaran yang sama',
    'hasil_prioritas'     => 'Risiko dan prioritas teridentifikasi',
    'hasil_validasi'      => 'Penyebab &amp; dampak tervalidasi',
    'hasil_kesepakatan'   => 'Mitigasi dan kontrol disepakati',
    'hasil_tindak_lanjut' => 'PIC &amp; tindak lanjut jelas',
];

$jumlahLangkah = 0;
foreach ($langkahList as $key => $label) {
    if (trim((string) $k->{$key}) !== '') {
        $jumlahLangkah++;
    }
}
$jumlahPertanyaan = 0;
foreach ($pertanyaanList as $key => $label) {
    if (trim((string) $k->{$key}) !== '') {
        $jumlahPertanyaan++;
    }
}
$jumlahHasil = 0;
foreach ($hasilList as $key => $label) {
    if ((int) $k->{$key} === 1) {
        $jumlahHasil++;
    }
}

$badgeStatus = $k->status === 'SELESAI' ? 'badge-success' : 'badge-secondary';
$badgeLanjut = 'badge-secondary';
if ($k->status_tindak_lanjut === 'SELESAI') {
    $badgeLanjut = 'badge-success';
} elseif ($k->status_tindak_lanjut === 'BERJALAN') {
    $badgeLanjut = 'badge-warning';
}
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info') ?>

        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-comments"></i> DETAIL SESI KOMUNIKASI
                </h5>
                <div>
                    <a href="<?= base_url('manrisk/komunikasi?tahun=' . (int) $k->tahun) ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <?php if ($boleh_tulis) : ?>
                        <a href="<?= base_url('manrisk/komunikasi/form/' . $k->id_komunikasi) ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Ubah Sesi
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-lg-8">
                        <h5 class="mb-1"><?= html_escape($k->topik) ?></h5>
                        <p class="text-muted mb-3">
                            <?= html_escape($k->nama_bagian ?: '—') ?>
                            <span class="mx-1">•</span>
                            <?= (int) $k->tahun ?>
                            <span class="mx-1">•</span>
                            TW <?= (int) $k->triwulan ?>
                        </p>

                        <div class="table-responsive">
                            <table class="table table-sm table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th class="text-muted" style="width:180px">Tanggal Sesi</th>
                                        <td><?= $tanggal($k->tanggal) ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Metode</th>
                                        <td><?= html_escape($k->metode ?: '—') ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Unit / UPK</th>
                                        <td><?= html_escape($k->nama_bagian ?: '—') ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">PIC / Fasilitator</th>
                                        <td><?= html_escape($k->pic_komunikasi ?: '—') ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Status Sesi</th>
                                        <td><span class="badge <?= $badgeStatus ?>"><?= html_escape($k->status ?: '—') ?></span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Lingkup</th>
                                        <td>
                                            <?php if ($k->id_lingkup) : ?>
                                                <a href="<?= base_url('manrisk/lingkup/form/' . (int) $k->id_lingkup) ?>">
                                                    <?= html_escape($k->nama_lingkup ?: ('Lingkup #' . (int) $k->id_lingkup)) ?>
                                                </a>
                                            <?php else : ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Risiko Terkait</th>
                                        <td>
                                            <?php if ($k->id_register) : ?>
                                                <a href="<?= base_url('manrisk/register/detail/' . (int) $k->id_register) ?>">
                                                    <?= html_escape($k->kode_risiko ?: ('Risiko #' . (int) $k->id_register)) ?>
                                                </a>
                                                <?php if ($k->risiko) : ?>
                                                    <br><small class="text-muted"><?= html_escape($k->risiko) ?></small>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0"><i class="fas fa-camera"></i> Foto Pendukung</h6>
                                <span class="badge badge-light border"><?= count($foto) ?> foto</span>
                            </div>

                            <?php if ($foto) : ?>
                                <div class="row">
                                    <?php foreach ($foto as $f) : ?>
                                        <div class="col-6 mb-2">
                                            <div class="border rounded p-1 h-100">
                                                <?php if ($fotoAda($f->nama_file)) : ?>
                                                    <a href="<?= $fotoUrl($f->nama_file) ?>" target="_blank" rel="noopener">
                                                        <img src="<?= $fotoUrl($f->nama_file) ?>" class="img-fluid rounded" style="width:100%;height:96px;object-fit:cover" alt="Foto pendukung sesi" loading="lazy">
                                                    </a>
                                                <?php else : ?>
                                                    <div class="text-muted small text-center py-4">
                                                        <i class="fas fa-image"></i><br>Berkas tidak ditemukan
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <small class="text-muted d-block mt-1">Klik foto untuk membuka ukuran penuh.</small>
                            <?php else : ?>
                                <p class="text-muted small text-center border rounded py-4 mb-2">
                                    <i class="fas fa-camera fa-lg"></i><br>Belum ada foto pendukung.
                                </p>
                            <?php endif; ?>

                            <?php if ($boleh_tulis) : ?>
                                <a href="<?= base_url('manrisk/komunikasi/form/' . $k->id_komunikasi) ?>" class="btn btn-sm btn-outline-primary btn-block mt-2">
                                    <i class="fas fa-upload"></i> Unggah / Ubah Foto
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-lg-5">
                        <h6 class="text-primary border-bottom pb-1">
                            <i class="fas fa-route"></i> Tiga Langkah Komunikasi
                            <span class="badge badge-info"><?= $jumlahLangkah ?>/3</span>
                        </h6>
                        <?php foreach ($langkahList as $key => $label) : ?>
                            <div class="border rounded p-2 mb-2">
                                <strong class="small"><?= html_escape($label) ?></strong>
                                <div class="small mt-1"><?= $teks($k->{$key}) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="col-lg-7">
                        <h6 class="text-primary border-bottom pb-1">
                            <i class="fas fa-question-circle"></i> Empat Pertanyaan Kunci
                            <span class="badge badge-info"><?= $jumlahPertanyaan ?>/4</span>
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <tbody>
                                    <?php foreach ($pertanyaanList as $key => $label) : ?>
                                        <tr>
                                            <th class="bg-light" style="width:42%"><?= html_escape($label) ?></th>
                                            <td><?= $teks($k->{$key}) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-lg-5">
                        <h6 class="text-primary border-bottom pb-1">
                            <i class="fas fa-check-double"></i> Lima Hasil yang Diharapkan
                            <span class="badge badge-info"><?= $jumlahHasil ?>/5</span>
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($hasilList as $key => $label) : ?>
                                <li class="mb-1">
                                    <?php if ((int) $k->{$key} === 1) : ?>
                                        <i class="fas fa-check-circle text-success"></i>
                                    <?php else : ?>
                                        <i class="far fa-circle text-muted"></i>
                                    <?php endif; ?>
                                    <?= html_escape($label) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="col-lg-7">
                        <h6 class="text-primary border-bottom pb-1">
                            <i class="fas fa-clipboard-check"></i> Kesimpulan &amp; Tindak Lanjut
                        </h6>
                        <div class="mb-2">
                            <small class="text-muted d-block">Kesimpulan / Ringkasan Hasil</small>
                            <?= $teks($k->ringkasan_hasil) ?>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Tindak Lanjut</small>
                            <?= $teks($k->tindak_lanjut) ?>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <small class="text-muted d-block">PIC Tindak Lanjut</small>
                                <?= $teks($k->pic_tindak_lanjut) ?>
                            </div>
                            <div class="col-sm-3">
                                <small class="text-muted d-block">Target Selesai</small>
                                <?= $tanggal($k->target_selesai) ?>
                            </div>
                            <div class="col-sm-3">
                                <small class="text-muted d-block">Status</small>
                                <span class="badge <?= $badgeLanjut ?>"><?= html_escape($k->status_tindak_lanjut ?: '—') ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="text-muted small">
                    Dicatat <?= $tanggal($k->created_at) ?><?= $k->created_by ? ' oleh ' . html_escape($k->created_by) : '' ?>
                    <?php if (!empty($k->modified_at)) : ?>
                        <span class="mx-1">•</span> Diperbarui <?= $tanggal($k->modified_at) ?>
                    <?php endif; ?>
                </div>

            </div>
        </div>

    </div>
</section>
</div>