<?php
/**
 * Pengaturan modul manrisk: kunci transaksi + risk appetite per kategori.
 */
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <div class="row align-items-center mb-2">
            <div class="col-md-8">
                <h4 class="mb-0"><?= strtoupper($title) ?></h4>
                <small class="text-muted">
                    Kunci menahan perubahan data; seluruh perubahan kunci &amp; appetite
                    mencatat siapa dan kapan (audit trail).
                </small>
            </div>
            <div class="col-md-4 text-md-right">
                <a href="<?= base_url('manrisk/register') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-list"></i> Register
                </a>
                <a href="<?= base_url('manrisk/analisa') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-calculator"></i> Analisa
                </a>
            </div>
        </div>

        <?php if (!$akses_semua) : ?>
            <div class="alert alert-secondary">
                <i class="fas fa-info-circle"></i>
                Hanya <strong>Administrator</strong>, <strong>Keuangan</strong>, <strong>Publik</strong>, dan
                <strong>Auditor</strong> yang dapat mengubah kunci dan risk appetite.
            </div>
        <?php endif; ?>

        <!-- ============== KUNCI TRANSAKSI ============== -->
        <div class="card card-outline card-primary">
            <div class="card-header"><strong><i class="fas fa-lock"></i> Kunci Transaksi</strong></div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width:180px">Kode</th>
                            <th>Bagian</th>
                            <th class="text-center" style="width:130px">Status</th>
                            <th style="width:220px">Terakhir Diubah</th>
                            <?php if ($akses_semua) : ?><th class="text-center" style="width:140px">Aksi</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kunci as $k) : ?>
                            <tr>
                                <td><code><?= html_escape($k->kode_kunci) ?></code></td>
                                <td><?= html_escape($k->nama_kunci) ?></td>
                                <td class="text-center">
                                    <?php if ((int) $k->status_kunci === 1) : ?>
                                        <span class="badge badge-danger">TERKUNCI</span>
                                    <?php else : ?>
                                        <span class="badge badge-success">TERBUKA</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small>
                                        <?php if ((int) $k->status_kunci === 1) : ?>
                                            kunci: <?= html_escape($k->locked_at ?: '-') ?> (<?= html_escape($k->locked_by ?: '-') ?>)
                                        <?php else : ?>
                                            buka: <?= html_escape($k->unlocked_at ?: '-') ?> (<?= html_escape($k->unlocked_by ?: '-') ?>)
                                        <?php endif; ?>
                                    </small>
                                </td>
                                <?php if ($akses_semua) : ?>
                                    <td class="text-center">
                                        <?= form_open('manrisk/pengaturan/kunci/' . $k->kode_kunci) ?>
                                        <button type="submit" class="btn btn-sm btn-<?= (int) $k->status_kunci === 1 ? 'success' : 'danger' ?>"
                                                onclick="return confirm('<?= (int) $k->status_kunci === 1 ? 'Buka' : 'Kunci' ?> kunci <strong><?= html_escape($k->nama_kunci) ?></strong>?');">
                                            <i class="fas fa-<?= (int) $k->status_kunci === 1 ? 'unlock' : 'lock' ?>"></i>
                                            <?= (int) $k->status_kunci === 1 ? 'Buka' : 'Kunci' ?>
                                        </button>
                                        <?= form_close() ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer small text-muted">
                Saat terkunci, halaman terkait hanya dapat dibuka untuk dilihat — tombol simpan disembunyikan
                dan penyimpanan ditolak di sisi server.
            </div>
        </div>

        <!-- ============== RISK APPETITE ============== -->
        <div class="card card-outline card-warning">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong><i class="fas fa-bullseye"></i> Risk Appetite per Kategori</strong>
                <form method="get" action="<?= base_url('manrisk/pengaturan') ?>" class="form-inline">
                    <label for="f_thn" class="mr-2 small">Tahun</label>
                    <select name="tahun" id="f_thn" class="form-control form-control-sm mr-2">
                        <?php foreach ($tahun_appetite as $t) : ?>
                            <option value="<?= (int) $t->tahun ?>" <?= (int) $tahun === (int) $t->tahun ? 'selected' : '' ?>><?= (int) $t->tahun ?></option>
                        <?php endforeach; ?>
                        <option value="<?= (int) $tahun ?>" <?= !$tahun_appetite ? 'selected' : '' ?>><?= (int) $tahun ?></option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Tampilkan</button>
                </form>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover mb-0" style="width:100%" id="tabel_appetite">
                    <thead class="thead-light">
                        <tr>
                            <th>Kategori</th>
                            <th>Risk Appetite</th>
                            <th>Risk Tolerance</th>
                            <th>Risk Capacity</th>
                            <th>Batas Eskalasi</th>
                            <th class="text-center">Status</th>
                            <?php if ($akses_semua) : ?><th class="text-center">Aksi</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$appetite) : ?>
                            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada risk appetite untuk tahun ini.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($appetite as $a) : ?>
                            <tr>
                                <td><strong><?= html_escape($a->nama_kat) ?></strong><br><small class="text-muted"><?= html_escape($a->kode_kat) ?></small></td>
                                <td><small><?= html_escape($a->risk_appetite ?: '-') ?></small></td>
                                <td><small><?= html_escape($a->risk_tolerance ?: '-') ?></small></td>
                                <td><small><?= html_escape($a->risk_capacity ?: '-') ?></small></td>
                                <td><small><?= html_escape($a->batas_eskalasi ?: '-') ?></small></td>
                                <td class="text-center">
                                    <span class="badge badge-<?= $a->status ? 'success' : 'secondary' ?>"><?= $a->status ? 'AKTIF' : 'NONAKTIF' ?></span>
                                </td>
                                <?php if ($akses_semua) : ?>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                data-toggle="modal" data-target="#modalAppetite"
                                                data-id="<?= (int) $a->id_appetite ?>"
                                                data-kategori="<?= (int) $a->id_kategori ?>"
                                                data-nama="<?= html_escape($a->nama_kat) ?>"
                                                data-tahun="<?= (int) $a->tahun ?>"
                                                data-appetite="<?= html_escape((string) $a->risk_appetite) ?>"
                                                data-tolerance="<?= html_escape((string) $a->risk_tolerance) ?>"
                                                data-capacity="<?= html_escape((string) $a->risk_capacity) ?>"
                                                data-eskalasi="<?= html_escape((string) $a->batas_eskalasi) ?>"
                                                data-status="<?= (int) $a->status ?>">
                                            <i class="fas fa-edit"></i> Ubah
                                        </button>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        <?php if ($akses_semua && !$appetite) : ?>
                            <tr><td colspan="7" class="text-center">
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalAppetite"
                                        data-id="0" data-kategori="" data-nama="" data-tahun="<?= (int) $tahun ?>"
                                        data-appetite="" data-tolerance="" data-capacity="" data-eskalasi="" data-status="1">
                                    <i class="fas fa-plus"></i> Tambah Risk Appetite
                                </button>
                            </td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============== SKALA LEVEL ============== -->
        <div class="card card-outline card-info">
            <div class="card-header"><strong><i class="fas fa-th"></i> Skala Level Risiko (skor L &times; D)</strong></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Level</th><th class="text-center">Skor</th>
                                <th>Deskripsi</th><th>Aksi</th>
                                <th class="text-center">Warna</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tingkat as $t) : ?>
                                <tr>
                                    <td><strong><?= html_escape($t->nama_tingkat) ?></strong></td>
                                    <td class="text-center"><?= (int) $t->skor_min ?>&ndash;<?= (int) $t->skor_max ?></td>
                                    <td><small><?= html_escape($t->deskripsi ?: '-') ?></small></td>
                                    <td><small><?= html_escape($t->aksi) ?></small></td>
                                    <td class="text-center">
                                        <span class="badge" style="background-color:<?= $t->warna ?>;color:#fff">&nbsp;&nbsp;&nbsp;</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p class="small text-muted mt-2 mb-0">
                    Skala mengikuti template CSV (<?= count($tingkat) ?> level). Modul legacy memakai 5 level berbeda —
                    label keduanya tidak pernah dicampur.
                </p>
            </div>
        </div>

    </div>
</section>

<?php if ($akses_semua) : ?>
<!-- ============== MODAL APPETITE ============== -->
<div class="modal fade" id="modalAppetite" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <?= form_open('manrisk/pengaturan/appetite/0', 'id="form-appetite"') ?>
            <input type="hidden" name="id_kategori" id="ap_kategori" value="">
            <input type="hidden" name="tahun" id="ap_tahun" value="<?= (int) $tahun ?>">
            <div class="modal-header">
                <h5 class="modal-title">Risk Appetite — <span id="ap_nama">…</span></h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="ap_appetite">Risk Appetite <small>(apa yang dikejar / dihindari)</small></label>
                    <textarea name="risk_appetite" id="ap_appetite" rows="2" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="ap_tolerance">Risk Tolerance <small>(deviasi yang masih ditoleransi)</small></label>
                    <textarea name="risk_tolerance" id="ap_tolerance" rows="2" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="ap_capacity">Risk Capacity <small>(kapasitas maksimum yang sanggup ditanggung)</small></label>
                    <textarea name="risk_capacity" id="ap_capacity" rows="2" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="ap_eskalasi">Batas Eskalasi</label>
                    <input type="text" name="batas_eskalasi" id="ap_eskalasi" class="form-control">
                </div>
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="status" id="ap_status" value="1" class="custom-control-input" checked>
                    <label class="custom-control-label" for="ap_status"><strong>Aktif</strong></label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
<?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#modalAppetite').on('show.bs.modal', function (e) {
        var b = $(e.relatedTarget);
        var id = b.data('id');
        $('#ap_kategori').val(b.data('kategori'));
        $('#ap_tahun').val(b.data('tahun'));
        $('#ap_nama').text(b.data('nama') || 'Kategori baru');
        $('#ap_appetite').val(b.data('appetite'));
        $('#ap_tolerance').val(b.data('tolerance'));
        $('#ap_capacity').val(b.data('capacity'));
        $('#ap_eskalasi').val(b.data('eskalasi'));
        $('#ap_status').prop('checked', String(b.data('status')) !== '0');
        $('#form-appetite').attr('action', '<?= base_url('manrisk/pengaturan/appetite/') ?>' + (id || '0'));
    });
});
</script>
