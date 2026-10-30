<?php
/**
 * Daftar Risk Register modul manrisk.
 *
 * Inisialisasi DataTable dilakukan di berkas ini sendiri, bukan di
 * templates/footer.php — footer adalah berkas legacy dan tidak diubah.
 */
$warnaLevel = [];
$aksiLevel = [];
foreach ($tingkat as $t) {
    $warnaLevel[$t->nama_tingkat] = $t->warna;
    $aksiLevel[$t->nama_tingkat] = $t->aksi;
}
$skalaTeks = [];
foreach ($tingkat as $t) {
    $skalaTeks[] = $t->nama_tingkat . ' ' . $t->skor_min . '-' . $t->skor_max;
}
$skalaTeks = implode(', ', $skalaTeks);
$badge = function ($level) use ($warnaLevel) {
    if ($level === null || $level === '') {
        return '<span class="badge badge-secondary">belum</span>';
    }
    $w = isset($warnaLevel[$level]) ? $warnaLevel[$level] : '#6c757d';
    return '<span class="badge" style="background-color:' . $w . ';color:#fff">' . html_escape($level) . '</span>';
};
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <!-- ============== FILTER ============== -->
        <div class="card card-outline card-primary">
            <div class="card-header">
                <form method="get" action="<?= base_url('manrisk/register') ?>" class="form-inline">
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
                        <label for="f_kat" class="mr-2">Kategori</label>
                        <select name="id_kategori" id="f_kat" class="form-control">
                            <option value="">Semua</option>
                            <?php foreach ($kategori_list as $k) : ?>
                                <option value="<?= $k->id_kategori ?>" <?= isset($filter['id_kategori']) && (string) $filter['id_kategori'] === (string) $k->id_kategori ? 'selected' : '' ?>>
                                    <?= html_escape($k->nama_kat) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mr-2 mb-2">
                        <label for="f_tipe" class="mr-2">Tipe</label>
                        <select name="tipe_risiko" id="f_tipe" class="form-control">
                            <option value="">Semua</option>
                            <option value="Ancaman" <?= isset($filter['tipe_risiko']) && $filter['tipe_risiko'] === 'Ancaman' ? 'selected' : '' ?>>Ancaman</option>
                            <option value="Peluang" <?= isset($filter['tipe_risiko']) && $filter['tipe_risiko'] === 'Peluang' ? 'selected' : '' ?>>Peluang</option>
                        </select>
                    </div>

                    <div class="form-group mr-2 mb-2">
                        <label for="f_kendali" class="mr-2">Kendali</label>
                        <select name="status_kendali" id="f_kendali" class="form-control">
                            <option value="">Semua</option>
                            <option value="C" <?= isset($filter['status_kendali']) && $filter['status_kendali'] === 'C' ? 'selected' : '' ?>>C (terkendali)</option>
                            <option value="UC" <?= isset($filter['status_kendali']) && $filter['status_kendali'] === 'UC' ? 'selected' : '' ?>>UC (belum terkendali)</option>
                        </select>
                    </div>

                    <div class="form-group mr-2 mb-2">
                        <label for="f_status" class="mr-2">Status</label>
                        <select name="status_risiko" id="f_status" class="form-control">
                            <option value="">Semua</option>
                            <?php foreach (['AKTIF', 'SELESAI', 'NONAKTIF'] as $s) : ?>
                                <option value="<?= $s ?>" <?= isset($filter['status_risiko']) && $filter['status_risiko'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mr-2 mb-2">
                        <label for="f_proses" class="mr-2">Proses</label>
                        <select name="id_proses" id="f_proses" class="form-control">
                            <option value="">Semua</option>
                            <?php foreach ($proses_list as $p) : ?>
                                <option value="<?= $p->id_proses ?>" <?= isset($filter['id_proses']) && (string) $filter['id_proses'] === (string) $p->id_proses ? 'selected' : '' ?>>
                                    <?= html_escape($p->kode_proses . ' — ' . $p->nama_proses) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mr-2 mb-2">
                        <label for="f_q" class="mr-2">Cari</label>
                        <input type="text" name="q" id="f_q" class="form-control" placeholder="kode / risiko / KPI"
                               value="<?= html_escape(isset($filter['q']) ? $filter['q'] : '') ?>">
                    </div>

                    <div class="form-group mr-2 mb-2">
                        <label for="f_thn" class="mr-2">Tahun</label>
                        <select name="tahun" id="f_thn" class="form-control">
                            <option value="<?= (int) $tahun ?>" selected><?= (int) $tahun ?></option>
                        </select>
                    </div>

                    <button type="submit" class="neumorphic-button mb-2"><i class="fas fa-filter"></i> Tampilkan</button>
                    <a href="<?= base_url('manrisk/register') ?>" class="neumorphic-button mb-2 ml-2"><i class="fas fa-undo"></i> Reset</a>
                </form>
            </div>
        </div>

        <!-- ============== JUDUL + AKSI ============== -->
        <div class="row align-items-center mb-2">
            <div class="col-md-7">
                <h4 class="mb-0"><?= strtoupper($title); ?> <?= $tahun; ?></h4>
                <small class="text-muted">
                    <?= count($daftar) ?> risiko sesuai filter
                    &middot; skala <?= count($tingkat) ?> level (<?= html_escape($skalaTeks) ?>)
                </small>
            </div>
            <div class="col-md-5 text-md-right">
                <?php if ($boleh_tulis) : ?>
                    <a href="<?= base_url('manrisk/register/form') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Risiko
                    </a>
                <?php else : ?>
                    <span class="badge badge-warning p-2">
                        <i class="fas fa-lock"></i> Register dikunci — tidak dapat diubah
                    </span>
                <?php endif; ?>
                <a href="<?= base_url('manrisk/matrik') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-th"></i> Matriks
                </a>
                <a href="<?= base_url('manrisk/import') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-file-csv"></i> Impor CSV
                </a>
            </div>
        </div>

        <!-- ============== TABEL ============== -->
        <div class="card card-outline card-primary">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover" id="tabel_register" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode</th>
                            <th>Risiko</th>
                            <th>Sasaran / KPI</th>
                            <th>Unit</th>
                            <th>Kategori</th>
                            <th>Proses</th>
                            <th class="text-center">Tipe</th>
                            <th class="text-center">Inherent</th>
                            <th class="text-center">Residual</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$daftar) : ?>
                            <tr>
                                <td colspan="11" class="text-center text-muted py-4">
                                    Tidak ada risiko yang cocok dengan filter.
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($daftar as $r) : ?>
                            <tr>
                                <td>
                                    <a href="<?= base_url('manrisk/register/detail/' . $r->id_register) ?>">
                                        <strong><?= html_escape($r->kode_risiko) ?></strong>
                                    </a>
                                </td>
                                <td><?= html_escape($r->risiko) ?></td>
                                <td><small><?= html_escape($r->sasaran_kpi) ?></small></td>
                                <td><?= html_escape($r->nama_bagian ?: '-') ?></td>
                                <td><small><?= html_escape($r->nama_kat) ?></small></td>
                                <td><small><?= html_escape($r->kode_proses) ?></small></td>
                                <td class="text-center">
                                    <span class="badge <?= $r->tipe_risiko === 'Peluang' ? 'badge-success' : 'badge-danger' ?>">
                                        <?= html_escape($r->tipe_risiko) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if ($r->nilai_inherent !== null) : ?>
                                        <?= $badge($r->level_inherent) ?><br>
                                        <small><?= (int) $r->inherent_l ?>&times;<?= (int) $r->inherent_d ?> = <strong><?= (int) $r->nilai_inherent ?></strong></small>
                                    <?php else : ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($r->nilai_residual !== null) : ?>
                                        <?= $badge($r->level_residual) ?><br>
                                        <small><?= (int) $r->residual_l ?>&times;<?= (int) $r->residual_d ?> = <strong><?= (int) $r->nilai_residual ?></strong></small>
                                    <?php else : ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <small><?= html_escape($r->status_risiko) ?></small><br>
                                    <span class="badge <?= $r->status_kendali === 'C' ? 'badge-success' : 'badge-warning' ?>">
                                        <?= html_escape($r->status_kendali) ?>
                                    </span>
                                </td>
                                <td class="text-center text-nowrap">
                                    <a href="<?= base_url('manrisk/register/detail/' . $r->id_register) ?>" class="btn btn-sm btn-outline-primary" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($boleh_tulis) : ?>
                                        <a href="<?= base_url('manrisk/register/form/' . $r->id_register) ?>" class="btn btn-sm btn-outline-secondary" title="Ubah">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url('manrisk/register/hapus/' . $r->id_register) ?>"
                                           class="btn btn-sm btn-outline-danger" title="Nonaktifkan"
                                           onclick="return confirm('Nonaktifkan risiko <?= html_escape($r->kode_risiko) ?>?\nData tidak dihapus, hanya ditandai NONAKTIF.');">
                                            <i class="fas fa-ban"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============== LEGENDA ============== -->
        <div class="card card-outline card-info">
            <div class="card-header"><strong>Legenda</strong></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-light">
                                <tr><th>Level</th><th>Skor L&times;D</th><th>Aksi</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tingkat as $t) : ?>
                                    <tr>
                                        <td><span class="badge" style="background-color:<?= $t->warna ?>;color:#fff"><?= html_escape($t->nama_tingkat) ?></span></td>
                                        <td><?= (int) $t->skor_min ?>&ndash;<?= (int) $t->skor_max ?></td>
                                        <td><small><?= html_escape($t->aksi) ?></small></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>C = Controlled</strong> — risiko sudah memiliki kontrol yang berjalan.</p>
                        <p class="mb-1"><strong>UC = Uncontrolled</strong> — kontrol belum ada atau belum efektif.</p>
                        <p class="mb-1">
                            <span class="badge badge-danger">Ancaman</span>
                            peristiwa yang merugikan &middot;
                            <span class="badge badge-success">Peluang</span>
                            peristiwa yang menguntungkan (ISO 6.4.2).
                        </p>
                        <p class="text-muted mb-0">
                            <small>
                                Data 2025&ndash;2026 tersimpan pada modul legacy
                                <a href="<?= base_url('risiko/profil_risiko') ?>">Profil Risiko</a>
                                dan bersifat hanya-baca.
                            </small>
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
    $('#tabel_register').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'asc']],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_",
            infoEmpty: "Belum ada data",
            infoFiltered: "(disaring dari _MAX_)",
            zeroRecords: "Tidak ada data yang cocok",
            paginate: {first: "Awal", last: "Akhir", next: "&raquo;", previous: "&laquo;"}
        }
    });
});
</script>
