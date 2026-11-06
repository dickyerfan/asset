<?php

/**
 * Daftar sesi Komunikasi & Konsultasi (ISO 31000 Klausa 6.2).
 * Sumber: materi QRMO slide 30-31.
 */
$badgeStatus = ['DRAFT' => 'secondary', 'SELESAI' => 'success'];
$badgeLanjut = ['BELUM' => 'secondary', 'PROSES' => 'warning', 'SELESAI' => 'success'];
$labelLanjut = ['BELUM' => 'Belum', 'PROSES' => 'Proses', 'SELESAI' => 'Selesai'];

/** Tiga langkah materi: dengarkan, selaraskan, uji & konfirmasi. */
$jumlahLangkah = function ($k) {
    $n = 0;
    foreach (['catatan_dengar', 'catatan_selaras', 'catatan_konfirmasi'] as $f) {
        if (trim((string) $k->{$f}) !== '') {
            $n++;
        }
    }
    return $n;
};

/** Empat pertanyaan kunci materi. */
$jumlahPertanyaan = function ($k) {
    $n = 0;
    foreach (['q_sasaran', 'q_risiko', 'q_kontrol', 'q_tindakan'] as $f) {
        if (trim((string) $k->{$f}) !== '') {
            $n++;
        }
    }
    return $n;
};

/** Lima hasil yang diharapkan materi. */
$jumlahHasil = function ($k) {
    return (int) $k->hasil_pemahaman + (int) $k->hasil_prioritas + (int) $k->hasil_validasi
        + (int) $k->hasil_kesepakatan + (int) $k->hasil_tindak_lanjut;
};

/** Tiga baris pertama pihak yang dilibatkan, agar tabel tetap terbaca. */
$ringkasPihak = function ($teks) {
    $baris = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', trim((string) $teks)))));
    if (!$baris) {
        return '-';
    }
    $tampil = html_escape(implode(', ', array_slice($baris, 0, 3)));
    if (count($baris) > 3) {
        $tampil .= ' &hellip;';
    }
    return $tampil;
};

$badgeHitung = function ($n, $dari) {
    if ($n >= $dari) {
        return 'badge-success';
    }
    return $n > 0 ? 'badge-warning' : 'badge-secondary';
};

$fotoUrl = function ($nama) {
    return base_url('assets/uploads/manrisk/komunikasi/' . rawurlencode(basename((string) $nama)));
};
$fotoAda = function ($nama) {
    return $nama && is_file(rtrim(FCPATH, '/\\') . '/assets/uploads/manrisk/komunikasi/' . basename((string) $nama));
};
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <div class="row align-items-center mb-2">
            <div class="col-md-8">
                <h4 class="mb-0"><?= strtoupper($title) ?> <?= $tahun ?></h4>
                <small class="text-muted">
                    Tahap 1 &mdash; melibatkan pihak relevan agar pemahaman sasaran,
                    risiko, dan kontrol menjadi sama sebelum risiko dinilai.
                </small>
            </div>
            <div class="col-md-4 text-md-right">
                <?php if ($boleh_tulis) : ?>
                    <a href="<?= base_url('manrisk/komunikasi/form') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Sesi
                    </a>
                <?php endif; ?>
                <a href="#panduan_komunikasi" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-book"></i> Panduan
                </a>
            </div>
        </div>

        <!-- ============== RINGKASAN ============== -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3><?= (int) $rekap['total'] ?></h3>
                        <p>Total Sesi</p>
                    </div>
                    <div class="icon"><i class="fas fa-comments"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= (int) $rekap['selesai'] ?></h3>
                        <p>Sesi Selesai</p>
                    </div>
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3><?= (int) $rekap['draft'] ?></h3>
                        <p>Draft</p>
                    </div>
                    <div class="icon"><i class="fas fa-file-alt"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= (int) $rekap['tindak_lanjut'] ?></h3>
                        <p>Tindak Lanjut Terbuka</p>
                    </div>
                    <div class="icon"><i class="fas fa-tasks"></i></div>
                </div>
            </div>
        </div>

        <!-- ============== FILTER ============== -->
        <div class="card card-outline card-primary">
            <div class="card-body py-2">
                <form method="get" action="<?= base_url('manrisk/komunikasi') ?>" class="form-inline">
                    <div class="form-group mr-3 mb-2">
                        <label for="tahun" class="mr-2 mb-0">Tahun</label>
                        <select name="tahun" id="tahun" class="form-control" onchange="this.form.submit()">
                            <?php foreach ($daftar_tahun as $t) : ?>
                                <option value="<?= (int) $t ?>" <?= (int) $tahun === (int) $t ? 'selected' : '' ?>><?= (int) $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <?php if ($akses_semua) : ?>
                        <div class="form-group mr-3 mb-2">
                            <label for="id_upk" class="mr-2 mb-0">Unit</label>
                            <select name="id_upk" id="id_upk" class="form-control" onchange="this.form.submit()">
                                <option value="">Semua Unit</option>
                                <?php foreach ($unit_list as $u) : ?>
                                    <option value="<?= (int) $u->id_bagian ?>" <?= isset($filter['id_upk']) && (string) $filter['id_upk'] === (string) $u->id_bagian ? 'selected' : '' ?>>
                                        <?= html_escape($u->nama_bagian) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="form-group mr-3 mb-2">
                        <label for="status" class="mr-2 mb-0">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Semua</option>
                            <?php foreach ($status_list as $s) : ?>
                                <option value="<?= $s ?>" <?= isset($filter['status']) && $filter['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mr-3 mb-2">
                        <label for="status_tindak_lanjut" class="mr-2 mb-0">Tindak Lanjut</label>
                        <select name="status_tindak_lanjut" id="status_tindak_lanjut" class="form-control">
                            <option value="">Semua</option>
                            <?php foreach ($lanjut_list as $s) : ?>
                                <option value="<?= $s ?>" <?= isset($filter['status_tindak_lanjut']) && $filter['status_tindak_lanjut'] === $s ? 'selected' : '' ?>>
                                    <?= isset($labelLanjut[$s]) ? $labelLanjut[$s] : $s ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mr-3 mb-2">
                        <label for="q" class="mr-2 mb-0">Cari</label>
                        <input type="text" name="q" id="q" class="form-control" placeholder="topik / pihak / PIC / kode risiko" value="<?= html_escape(isset($filter['q']) ? $filter['q'] : '') ?>">
                    </div>

                    <button type="submit" class="btn btn-primary mb-2"><i class="fas fa-filter"></i> Tampilkan</button>
                    <a href="<?= base_url('manrisk/komunikasi') ?>" class="btn btn-outline-secondary mb-2 ml-1"><i class="fas fa-undo"></i> Reset</a>
                </form>
            </div>
        </div>

        <!-- ============== DAFTAR ============== -->
        <div class="card card-outline card-primary">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover" id="tabel_komunikasi" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Topik / Agenda</th>
                            <th>Unit &amp; Risiko</th>
                            <th>Pihak Terlibat</th>
                            <th class="text-center" title="Dengarkan, selaraskan, uji & konfirmasi">3 Langkah</th>
                            <th class="text-center" title="Sasaran, risiko, kontrol, tindakan">4 Pertanyaan</th>
                            <th class="text-center" title="Lima hasil yang diharapkan">5 Hasil</th>
                            <th>Tindak Lanjut</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$daftar) : ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    Belum ada sesi komunikasi pada filter ini.
                                    <?php if ($boleh_tulis) : ?>
                                        <br><a href="<?= base_url('manrisk/komunikasi/form') ?>" class="btn btn-sm btn-primary mt-2">
                                            <i class="fas fa-plus"></i> Catat Sesi Pertama
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($daftar as $k) :
                            $langkah = $jumlahLangkah($k);
                            $tanya = $jumlahPertanyaan($k);
                            $hasil = $jumlahHasil($k);
                        ?>
                            <tr>
                                <td class="text-nowrap" data-order="<?= html_escape($k->tanggal) ?>">
                                    <strong><?= html_escape(date('d/m/Y', strtotime($k->tanggal))) ?></strong><br>
                                    <span class="badge badge-info">TW<?= (int) $k->triwulan ?></span>
                                    <small class="text-muted d-block mt-1"><?= html_escape($k->metode) ?></small>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <?php if ($fotoAda(isset($k->foto_utama) ? $k->foto_utama : null)) : ?>
                                            <a href="<?= base_url('manrisk/komunikasi/detail/' . $k->id_komunikasi) ?>" class="mr-2 flex-shrink-0">
                                                <img src="<?= $fotoUrl($k->foto_utama) ?>" style="width:56px;height:56px;object-fit:cover" class="rounded border" alt="Foto pendukung sesi">
                                            </a>
                                        <?php endif; ?>
                                        <div>
                                            <a href="<?= base_url('manrisk/komunikasi/detail/' . $k->id_komunikasi) ?>" class="text-dark">
                                                <strong><?= html_escape($k->topik) ?></strong>
                                            </a>
                                            <?php if (isset($k->jumlah_foto) && (int) $k->jumlah_foto > 0) : ?>
                                                <span class="badge badge-light border ml-1" title="Jumlah foto pendukung">
                                                    <i class="fas fa-camera"></i> <?= (int) $k->jumlah_foto ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($k->nama_lingkup) : ?>
                                                <br><small class="text-muted"><i class="fas fa-link"></i> <?= html_escape($k->nama_lingkup) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <small><?= html_escape($k->nama_bagian ?: 'Seluruh organisasi') ?></small>
                                    <?php if ($k->kode_risiko) : ?>
                                        <br><small><a href="<?= base_url('manrisk/register/detail/' . $k->id_register) ?>"><?= html_escape($k->kode_risiko) ?></a></small>
                                    <?php endif; ?>
                                </td>
                                <td><small><?= $ringkasPihak($k->pihak_terlibat) ?></small></td>
                                <td class="text-center">
                                    <span class="badge <?= $badgeHitung($langkah, 3) ?>"><?= $langkah ?>/3</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $badgeHitung($tanya, 4) ?>"><?= $tanya ?>/4</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $badgeHitung($hasil, 5) ?>"><?= $hasil ?>/5</span>
                                </td>
                                <td>
                                    <?php if (trim((string) $k->tindak_lanjut) !== '') : ?>
                                        <span class="badge badge-<?= isset($badgeLanjut[$k->status_tindak_lanjut]) ? $badgeLanjut[$k->status_tindak_lanjut] : 'secondary' ?>">
                                            <?= isset($labelLanjut[$k->status_tindak_lanjut]) ? $labelLanjut[$k->status_tindak_lanjut] : html_escape($k->status_tindak_lanjut) ?>
                                        </span>
                                        <small class="d-block mt-1"><?= html_escape($k->pic_tindak_lanjut ?: '-') ?></small>
                                        <?php if ($k->target_selesai) : ?>
                                            <small class="text-muted">Target <?= html_escape(date('d/m/Y', strtotime($k->target_selesai))) ?></small>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-<?= isset($badgeStatus[$k->status]) ? $badgeStatus[$k->status] : 'secondary' ?>">
                                        <?= html_escape($k->status) ?>
                                    </span>
                                </td>
                                <td class="text-center text-nowrap">
                                    <a href="<?= base_url('manrisk/komunikasi/detail/' . $k->id_komunikasi) ?>" class="btn btn-sm btn-outline-info" title="Lihat detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($boleh_tulis) : ?>
                                        <a href="<?= base_url('manrisk/komunikasi/form/' . $k->id_komunikasi) ?>" class="btn btn-sm btn-outline-secondary" title="Ubah sesi">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url('manrisk/komunikasi/hapus/' . $k->id_komunikasi) ?>" class="btn btn-sm btn-outline-danger" title="Hapus sesi" onclick="return confirm('Hapus catatan komunikasi ini?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php else : ?>
                                        <span class="text-muted ml-1"><i class="fas fa-lock"></i></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============== PANDUAN SINGKAT ============== -->
        <div class="card card-outline card-info" id="panduan_komunikasi">
            <div class="card-header">
                <strong><i class="fas fa-comments"></i> Panduan Singkat Komunikasi &amp; Konsultasi</strong>
                <span class="float-right badge badge-light border">ISO 31000 · Klausa 6.2</span>
            </div>
            <div class="card-body">
                <p class="mb-3">
                    Komunikasi &amp; konsultasi bukan sekadar rapat. Tujuannya memperoleh informasi terbaik,
                    menyamakan persepsi, dan menghasilkan keputusan risiko yang jelas bersama pihak relevan.
                </p>
                <div class="row">
                    <div class="col-lg-4 mb-3 mb-lg-0">
                        <h6 class="font-weight-bold text-primary">Tiga Langkah</h6>
                        <ol class="pl-3 mb-0">
                            <li class="mb-2"><strong>Dengarkan</strong> &mdash; kumpulkan informasi dari Risk Owner dan stakeholder.</li>
                            <li class="mb-2"><strong>Selaraskan</strong> &mdash; samakan pemahaman tentang sasaran, risiko, dan kontrol.</li>
                            <li><strong>Uji &amp; konfirmasi</strong> &mdash; klarifikasi asumsi dan pastikan informasi dapat dipertanggungjawabkan.</li>
                        </ol>
                    </div>
                    <div class="col-lg-4 mb-3 mb-lg-0">
                        <h6 class="font-weight-bold text-primary">Empat Pertanyaan Kunci</h6>
                        <ol class="pl-3 mb-0">
                            <li class="mb-2">Apa sasarannya?</li>
                            <li class="mb-2">Apa risikonya?</li>
                            <li class="mb-2">Kontrol apa yang sudah ada?</li>
                            <li>Apa tindakan selanjutnya?</li>
                        </ol>
                    </div>
                    <div class="col-lg-4">
                        <h6 class="font-weight-bold text-primary">Lima Hasil yang Diharapkan</h6>
                        <ol class="pl-3 mb-0">
                            <li class="mb-1">Pemahaman sasaran yang sama</li>
                            <li class="mb-1">Risiko dan prioritas teridentifikasi</li>
                            <li class="mb-1">Penyebab &amp; dampak tervalidasi</li>
                            <li class="mb-1">Mitigasi dan kontrol disepakati</li>
                            <li>PIC &amp; tindak lanjut jelas</li>
                        </ol>
                    </div>
                </div>
                <hr>
                <p class="mb-0 small text-muted">
                    <i class="fas fa-lightbulb"></i>
                    Pihak yang biasanya dilibatkan: Direksi/Manajemen, Risk Owner, unit teknis terkait,
                    Keuangan, IT/GIS, serta SPI/Assurance. Catat hasilnya sebagai bukti bahwa asumsi risiko
                    sudah dikonfirmasi bersama, bukan hanya kesimpulan satu pihak.
                </p>
            </div>
        </div>

    </div>
</section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.jQuery && jQuery.fn.DataTable) {
            jQuery('#tabel_komunikasi').DataTable({
                responsive: true,
                pageLength: 25,
                order: [
                    [0, 'desc']
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_",
                    infoEmpty: "Belum ada data",
                    infoFiltered: "(disaring dari _MAX_)",
                    zeroRecords: "Tidak ada data yang cocok",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "&raquo;",
                        previous: "&laquo;"
                    }
                }
            });
        }
    });
</script>