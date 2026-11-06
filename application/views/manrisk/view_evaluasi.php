<?php
/**
 * Daftar evaluasi risiko + rekap keputusan (ISO 31000 Klausa 6.6).
 */
$warna = [];
foreach ($tingkat as $t) {
    $warna[$t->nama_tingkat] = $t->warna;
}
$badge = function ($level) use ($warna) {
    if ($level === null || $level === '') {
        return '<span class="badge badge-secondary">—</span>';
    }
    $w = isset($warna[$level]) ? $warna[$level] : '#6c757d';
    return '<span class="badge" style="background-color:' . $w . ';color:#fff">' . html_escape($level) . '</span>';
};
$warnaKeputusan = [
    'Accept'  => 'secondary',
    'Monitor' => 'info',
    'Treat'   => 'warning',
    'Escalate' => 'danger',
];
$total = count($daftar);
$terevaluasi = 0;
foreach ($daftar as $r) {
    if (!empty($evals[$r->id_register])) {
        $terevaluasi++;
    }
}
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <!-- ============== REKAP ============== -->
        <div class="row">
            <div class="col-md-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner"><h3><?= $terevaluasi ?>/<?= $total ?></h3><p>Sudah Dievaluasi</p></div>
                    <div class="icon"><i class="fas fa-balance-scale"></i></div>
                </div>
            </div>
            <div class="col-md-4 col-6">
                <a href="<?= base_url('manrisk/evaluasi?review=PERLU_REVIEW') ?>" class="d-block text-decoration-none">
                    <div class="small-box" style="background:#6f42c1;color:#fff;">
                        <div class="inner">
                            <h3><?= isset($rekap_review['PERLU_REVIEW']) ? $rekap_review['PERLU_REVIEW'] : 0 ?></h3>
                            <p>Perlu Review (override band)</p>
                        </div>
                        <div class="icon"><i class="fas fa-flag"></i></div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= isset($rekap['Escalate']) ? $rekap['Escalate'] : 0 ?></h3>
                        <p>Perlu Eskalasi</p>
                    </div>
                    <div class="icon"><i class="fas fa-arrow-up"></i></div>
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= isset($rekap['Treat']) ? $rekap['Treat'] : 0 ?></h3>
                        <p>Perlu Ditangani</p>
                    </div>
                    <div class="icon"><i class="fas fa-tools"></i></div>
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= (isset($rekap['Accept']) ? $rekap['Accept'] : 0) + (isset($rekap['Monitor']) ? $rekap['Monitor'] : 0) ?></h3>
                        <p>Diterima / Dipantau</p>
                    </div>
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>
        </div>

        <!-- ============== FILTER ============== -->
        <div class="card card-outline card-primary">
            <div class="card-header">
                <form method="get" action="<?= base_url('manrisk/evaluasi') ?>" class="form-inline">
                    <?php if ($akses_semua) : ?>
                        <div class="form-group mr-2 mb-2">
                            <label for="f_upk" class="mr-2">Unit</label>
                            <select name="id_upk" id="f_upk" class="form-control">
                                <option value="">Semua</option>
                                <?php foreach ($unit_list as $u) : ?>
                                    <option value="<?= $u->id_bagian ?>" <?= isset($filter['id_upk']) && (string) $filter['id_upk'] === (string) $u->id_bagian ? 'selected' : '' ?>>
                                        <?= html_escape($u->nama_bagian) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="form-group mr-2 mb-2">
                        <label for="f_kep" class="mr-2">Keputusan</label>
                        <select name="keputusan" id="f_kep" class="form-control">
                            <option value="">Semua</option>
                            <?php foreach ($keputusan as $k) : ?>
                                <option value="<?= $k ?>" <?= isset($filter['keputusan']) && $filter['keputusan'] === $k ? 'selected' : '' ?>><?= $k ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mr-2 mb-2">
                        <label for="f_rev" class="mr-2">Status Review</label>
                        <select name="review" id="f_rev" class="form-control">
                            <option value="">Semua</option>
                            <option value="PERLU_REVIEW" <?= isset($filter_review) && $filter_review === 'PERLU_REVIEW' ? 'selected' : '' ?>>Perlu Review</option>
                            <option value="SESUAI" <?= isset($filter_review) && $filter_review === 'SESUAI' ? 'selected' : '' ?>>Sesuai Band</option>
                            <option value="DITINJAU" <?= isset($filter_review) && $filter_review === 'DITINJAU' ? 'selected' : '' ?>>Sudah Ditinjau</option>
                        </select>
                    </div>

                    <button type="submit" class="neumorphic-button mb-2"><i class="fas fa-filter"></i> Tampilkan</button>
                    <a href="<?= base_url('manrisk/evaluasi') ?>" class="neumorphic-button mb-2 ml-2"><i class="fas fa-undo"></i> Reset</a>
                </form>
            </div>
        </div>

        <div class="row align-items-center mb-2">
            <div class="col-md-8">
                <h4 class="mb-0"><?= strtoupper($title) ?> <?= $tahun ?></h4>
                <small class="text-muted">
                    Perbandingan hasil analisis terhadap <strong>kriteria risiko</strong>
                    (tingkat risiko &amp; risk appetite) &rarr; keputusan Accept / Monitor / Treat / Escalate.
                </small>
            </div>
            <div class="col-md-4 text-md-right">
                <a href="<?= base_url('manrisk/analisa') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-calculator"></i> Analisa
                </a>
                <a href="<?= base_url('manrisk/register') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-list"></i> Register
                </a>
            </div>
        </div>

        <!-- ============== TABEL ============== -->
        <div class="card card-outline card-primary">
            <div class="card-body table-responsive">

                <!-- Kolom Band & Status Review TIDAK diisi di form: keduanya
                     dihitung otomatis oleh server saat evaluasi disimpan. -->
                <details class="mb-2">
                    <summary class="small text-primary" style="cursor:pointer">
                        <i class="fas fa-info-circle"></i>
                        Dari mana nilai <strong>Band</strong> dan <strong>Status Review</strong>?
                    </summary>
                    <div class="small text-muted mt-2">
                        <p class="mb-1">
                            <strong>Band</strong> = rekomendasi otomatis dari skala risiko aktif.
                            Server mengambil <code>aksi</code> pada level yang sesuai dengan
                            <strong>skor residual</strong> (<code>mri_tingkat_risiko.aksi</code>),
                            jadi nilainya selalu <em>Accept / Monitor / Treat / Escalate</em>
                            mengikuti skor — tidak diketik manual.
                        </p>
                        <p class="mb-1">
                            <strong>Status Review</strong> juga otomatis, hasil perbandingan
                            keputusan Anda dengan band:
                        </p>
                        <ul class="mb-1 pl-3">
                            <li><span class="badge badge-secondary">Sesuai</span> — keputusan sama dengan band.</li>
                            <li><span class="badge badge-warning">Perlu Review</span> — keputusan berbeda dari band, menunggu ditinjau.</li>
                            <li><span class="badge badge-success">Ditinjau</span> — override sudah ditinjau dan dicatat.</li>
                        </ul>
                        <p class="mb-0">
                            Karena itu tombol <strong>Ubah</strong> tidak menyediakan isian untuk keduanya.
                            Yang Anda isi hanya <strong>Keputusan</strong>, <strong>Alasan</strong>, dan
                            <strong>Triwulan</strong>; Band &amp; Status Review muncul setelah disimpan.
                        </p>
                    </div>
                </details>

                <table class="table table-bordered table-hover" id="tabel_evaluasi" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode</th>
                            <th>Risiko</th>
                            <th>Unit</th>
                            <th class="text-center">Skor Residual</th>
                            <th class="text-center">Level</th>
                            <th class="text-center">Nilai Evaluasi</th>
                            <th>Level Evaluasi</th>
                            <th class="text-center">Keputusan</th>
                            <th class="text-center">Band</th>
                            <th class="text-center">Status Review</th>
                            <th class="text-center">TW</th>
                            <th class="text-center">Eskalasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$daftar) : ?>
                            <tr><td colspan="13" class="text-center text-muted py-4">Tidak ada risiko sesuai filter.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($daftar as $r) :
                            $e = isset($evals[$r->id_register]) ? $evals[$r->id_register] : null;
                            $revBadge = '—';
                            if ($e) {
                                if ($e->status_review === 'DITINJAU') {
                                    $revBadge = '<span class="badge badge-success">Ditinjau</span>';
                                } elseif ($e->status_review === 'PERLU_REVIEW') {
                                    $revBadge = '<span class="badge badge-warning">Perlu Review</span>';
                                } else {
                                    $revBadge = '<span class="badge badge-secondary">Sesuai</span>';
                                }
                            }
                        ?>
                            <tr>
                                <td><a href="<?= base_url('manrisk/register/detail/' . $r->id_register) ?>"><strong><?= html_escape($r->kode_risiko) ?></strong></a></td>
                                <td><?= html_escape($r->risiko) ?></td>
                                <td><small><?= html_escape($r->nama_bagian ?: '-') ?></small></td>
                                <td class="text-center">
                                    <?= $r->nilai_residual !== null ? '<strong>' . (int) $r->nilai_residual . '</strong>' : '<span class="text-muted">—</span>' ?>
                                </td>
                                <td class="text-center"><?= $badge($r->level_residual) ?></td>
                                <td class="text-center">
                                    <?= $e ? '<strong>' . (int) $e->nilai_evaluasi . '</strong>' : '<span class="text-muted">—</span>' ?>
                                </td>
                                <td><?= $e ? $badge($e->level_evaluasi) : '<span class="text-muted">belum</span>' ?></td>
                                <td class="text-center">
                                    <?php if ($e) : ?>
                                        <span class="badge badge-<?= isset($warnaKeputusan[$e->keputusan]) ? $warnaKeputusan[$e->keputusan] : 'secondary' ?>">
                                            <?= html_escape($e->keputusan) ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($e && $e->keputusan_band) : ?>
                                        <span class="badge badge-<?= isset($warnaKeputusan[$e->keputusan_band]) ? $warnaKeputusan[$e->keputusan_band] : 'secondary' ?>">
                                            <?= html_escape($e->keputusan_band) ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= $revBadge ?></td>
                                <td class="text-center"><?= $e ? 'TW' . (int) $e->triwulan : '—' ?></td>
                                <td class="text-center">
                                    <?php if ($e && $e->perlu_eskalasi) : ?>
                                        <span class="badge badge-danger"><?= html_escape($e->eskalasi_ke ?: 'ya') ?></span>
                                    <?php else : ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($e && $e->status_review === 'PERLU_REVIEW' && !empty($boleh_review)) : ?>
                                        <a href="<?= base_url('manrisk/evaluasi/review/' . (int) $e->id_evaluasi) ?>"
                                           class="btn btn-sm btn-warning" title="Tinjau override band">
                                            <i class="fas fa-flag"></i> Review
                                        </a>
                                    <?php elseif ($e && $e->status_review === 'DITINJAU') : ?>
                                        <a href="<?= base_url('manrisk/evaluasi/review/' . (int) $e->id_evaluasi) ?>"
                                           class="btn btn-sm btn-outline-success" title="Lihat catatan review">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= base_url('manrisk/evaluasi/form/' . $r->id_register) ?>"
                                       class="btn btn-sm btn-<?= $e ? 'outline-secondary' : 'primary' ?>">
                                        <i class="fas fa-<?= $e ? 'edit' : 'plus' ?>"></i>
                                        <?= $e ? 'Ubah' : 'Evaluasi' ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============== ARTI KEPUTUSAN ============== -->
        <div class="card card-outline card-info">
            <div class="card-header">
                <strong>Arti Keputusan</strong>
                <small class="text-muted ml-2">acuan singkat saat mengisi form Evaluasi</small>
            </div>
            <div class="card-body py-2">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-2">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:100px">Keputusan</th>
                                <th style="width:24%">Artinya</th>
                                <th style="width:32%">Kapan dipilih</th>
                                <th>Akibatnya</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge badge-secondary">Accept</span></td>
                                <td><small>Risiko diterima apa adanya &mdash; sisa risiko masih dalam batas appetite.</small></td>
                                <td><small>Skor residual &le; appetite kategori, dampak kecil, kontrol memadai.
                                    <em>Contoh: skor 6, appetite 10.</em></small></td>
                                <td><small>Tidak wajib rencana perlakuan; cukup pemantauan rutin.</small></td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-info">Monitor</span></td>
                                <td><small>Risiko belum perlu ditangani sekarang, tetapi harus diawasi.</small></td>
                                <td><small>Masih dalam appetite namun mendekati batas, atau level menengah dengan kontrol berjalan.
                                    <em>Contoh: skor 12, appetite 15.</em></small></td>
                                <td><small>Dicatat di Monitoring tiap triwulan; bila tren naik, ubah ke <code>Treat</code>.</small></td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-warning">Treat</span></td>
                                <td><small>Risiko wajib diturunkan dengan tindakan nyata (mitigasi).</small></td>
                                <td><small>Skor melebihi appetite kategori atau level Tinggi dan berdampak pada sasaran/KPI.
                                    <em>Contoh: skor 18, appetite 15.</em></small></td>
                                <td><small>Wajib disusun di menu <strong>Perlakuan</strong> (strategi, PIC, jadwal, anggaran); terhitung &ldquo;perlu ditindaklanjuti&rdquo; di Laporan.</small></td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-danger">Escalate</span></td>
                                <td><small>Risiko di luar kewenangan unit &mdash; keputusan diserahkan ke atasan.</small></td>
                                <td><small>Skor melewati batas eskalasi kategori, berdampak lintas unit/strategis, atau butuh keputusan Direksi/Komite.
                                    <em>Contoh: skor 22, batas eskalasi 15.</em></small></td>
                                <td><small>Centang <strong>Perlu Eskalasi</strong> + isi <strong>Eskalasi ke</strong> agar muncul di kartu &ldquo;Risiko Perlu Eskalasi&rdquo; dashboard.</small></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="small text-muted mb-0">
                    <i class="fas fa-info-circle"></i>
                    Keputusan boleh berbeda dari <strong>Band</strong>, tetapi akan berstatus
                    <span class="badge badge-warning">Perlu Review</span> sampai ditinjau.
                    Panduan lengkap juga tersedia di dalam form Ubah (bagian
                    &ldquo;Panduan memilih Keputusan&rdquo;).
                </p>
            </div>
        </div>

    </div>
</section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#tabel_evaluasi').DataTable({
        responsive: true, pageLength: 25, order: [[0, 'asc']],
        language: {
            search: "Cari:", lengthMenu: "Tampilkan _MENU_",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_",
            infoEmpty: "Belum ada data", infoFiltered: "(disaring dari _MAX_)",
            zeroRecords: "Tidak ada data yang cocok",
            paginate: {first: "Awal", last: "Akhir", next: "&raquo;", previous: "&laquo;"}
        }
    });
});
</script>
