<?php
/**
 * Daftar rencana perlakuan risiko (ISO 31000 Klausa 6.7).
 */
$labelStatus = ['BELUM' => 'secondary', 'DALAM_PROSES' => 'warning', 'SELESAI' => 'success'];
$total = count($daftar);
?>
<section class="content">
    <div class="container-fluid">

        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>

        <!-- ============== REKAP ============== -->
        <div class="row">
            <?php foreach (['BELUM', 'DALAM_PROSES', 'SELESAI'] as $s) : ?>
                <div class="col-lg-4 col-md-6 col-6">
                    <div class="small-box bg-<?= $s === 'SELESAI' ? 'success' : ($s === 'DALAM_PROSES' ? 'warning' : 'secondary') ?>">
                        <div class="inner">
                            <h3><?= isset($rekap[$s]) ? $rekap[$s] : 0 ?></h3>
                            <p><?= $s === 'BELUM' ? 'Belum Dilaksanakan' : ($s === 'DALAM_PROSES' ? 'Dalam Proses' : 'Selesai') ?></p>
                        </div>
                        <div class="icon"><i class="fas fa-<?= $s === 'SELESAI' ? 'check-double' : ($s === 'DALAM_PROSES' ? 'spinner' : 'hourglass') ?>"></i></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- ============== FILTER ============== -->
        <div class="card card-outline card-primary">
            <div class="card-header">
                <form method="get" action="<?= base_url('manrisk/perlakuan') ?>" class="form-inline">
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
                        <label for="f_sts" class="mr-2">Status</label>
                        <select name="status_laksana" id="f_sts" class="form-control">
                            <option value="">Semua</option>
                            <?php foreach (['BELUM', 'DALAM_PROSES', 'SELESAI'] as $s) : ?>
                                <option value="<?= $s ?>" <?= isset($filter['status_laksana']) && $filter['status_laksana'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="neumorphic-button mb-2"><i class="fas fa-filter"></i> Tampilkan</button>
                    <a href="<?= base_url('manrisk/perlakuan') ?>" class="neumorphic-button mb-2 ml-2"><i class="fas fa-undo"></i> Reset</a>
                </form>
            </div>
        </div>

        <div class="row align-items-center mb-2">
            <div class="col-md-8">
                <h4 class="mb-0"><?= strtoupper($title) ?> <?= $tahun ?></h4>
                <small class="text-muted">
                    Strategi: <?php foreach ($strategi as $k => $v) : ?>
                        <span class="badge badge-dark mr-1"><?= html_escape($k) ?> = <?= html_escape($v) ?></span>
                    <?php endforeach; ?>
                </small>
            </div>
            <div class="col-md-4 text-md-right">
                <a href="<?= base_url('manrisk/evaluasi') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-balance-scale"></i> Evaluasi
                </a>
                <a href="<?= base_url('manrisk/monitoring') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-eye"></i> Monitoring
                </a>
            </div>
        </div>

        <!-- ============== TABEL ============== -->
        <div class="card card-outline card-primary">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover" id="tabel_perlakuan" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode</th>
                            <th>Risiko</th>
                            <th>Unit</th>
                            <th class="text-center">Strategi</th>
                            <th>Uraian Tindakan</th>
                            <th class="text-center">Anggaran</th>
                            <th class="text-center">Jadwal</th>
                            <th>PIC</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Keputusan Evaluasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$daftar) : ?>
                            <tr><td colspan="11" class="text-center text-muted py-4">Belum ada rencana perlakuan sesuai filter.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($daftar as $p) : ?>
                            <tr>
                                <td><a href="<?= base_url('manrisk/register/detail/' . $p->id_register) ?>"><strong><?= html_escape($p->kode_risiko) ?></strong></a></td>
                                <td><?= html_escape($p->risiko) ?></td>
                                <td><small><?= html_escape($p->nama_bagian ?: '-') ?></small></td>
                                <td class="text-center">
                                    <span class="badge badge-dark" title="<?= html_escape($p->strategi) ?>">
                                        <?= html_escape($p->strategi) ?>
                                    </span>
                                    <br><small class="text-muted"><?= html_escape(isset($strategi[$p->strategi]) ? $strategi[$p->strategi] : '') ?></small>
                                </td>
                                <td><small><?= html_escape($p->uraian_tindakan ?: '-') ?></small></td>
                                <td class="text-right">
                                    <?= $p->anggaran_biaya !== null ? 'Rp ' . number_format((float) $p->anggaran_biaya, 0, ',', '.') : '—' ?>
                                </td>
                                <td class="text-center text-nowrap"><small>
                                    <?= html_escape($p->jadwal_mulai ?: '-') ?><br>s/d<br><?= html_escape($p->jadwal_selesai ?: '-') ?>
                                </small></td>
                                <td><small><?= html_escape($p->id_pic ?: '-') ?></small></td>
                                <td class="text-center">
                                    <span class="badge badge-<?= isset($labelStatus[$p->status_laksana]) ? $labelStatus[$p->status_laksana] : 'secondary' ?>">
                                        <?= html_escape($p->status_laksana) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if ($p->keputusan) : ?>
                                        <span class="badge badge-info"><?= html_escape($p->keputusan) ?></span>
                                    <?php else : ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('manrisk/perlakuan/form/' . $p->id_register) ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#tabel_perlakuan').DataTable({
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
