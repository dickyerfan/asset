<?php
$rekap = $dashboard;
$total = (int)$rekap['total'];
$persen = function ($nilai) use ($total) {
    return $total > 0 ? round(($nilai / $total) * 100, 1) : 0;
};
$warnaPeringkat = function ($peringkat) {
    $warna = [
        'Sangat Tinggi' => 'danger',
        'Tinggi' => 'warning',
        'Moderat' => 'info',
        'Rendah' => 'success'
    ];
    return isset($warna[$peringkat]) ? $warna[$peringkat] : 'secondary';
};
?>

<section class="content">
    <div class="container-fluid">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <form method="get" action="<?= base_url('risiko/dashboard') ?>" class="form-inline">
                    <?php if ($akses_semua_upk) : ?>
                        <div class="form-group mr-2 mb-2">
                            <label for="id_upk" class="mr-2">Bagian / UPK</label>
                            <select name="id_upk" id="id_upk" class="form-control">
                                <option value="">Semua Bagian / UPK</option>
                                <?php foreach ($unit_list as $unit) : ?>
                                    <option value="<?= $unit->id_bagian ?>" <?= (string)$filter['id_upk'] === (string)$unit->id_bagian ? 'selected' : '' ?>>
                                        <?= html_escape($unit->nama_bagian) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="form-group mr-2 mb-2">
                        <label for="tahun_risiko" class="mr-2">Tahun</label>
                        <select name="tahun" id="tahun_risiko" class="form-control">
                            <?php
                            $daftar_tahun = [];
                            foreach ($tahun_list as $item) {
                                $daftar_tahun[] = (int)$item->tahun;
                            }
                            if (!in_array((int)$tahun, $daftar_tahun)) {
                                array_unshift($daftar_tahun, (int)$tahun);
                            }
                            foreach ($daftar_tahun as $item_tahun) :
                            ?>
                                <option value="<?= $item_tahun ?>" <?= (int)$tahun === $item_tahun ? 'selected' : '' ?>><?= $item_tahun ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="neumorphic-button mb-2"><i class="fas fa-filter"></i> Tampilkan</button>
                    <button type="reset" class="neumorphic-button mb-2 ml-2" onclick="window.location.href = '<?= base_url('risiko/dashboard') ?>' "><i class="fas fa-undo"></i> Reset</button>
                </form>
            </div>
        </div>
        <div class="row justify-content-center mb-2">
            <div class="col-lg-6 text-center">
                <h2><?= strtoupper($title); ?> <?= $tahun; ?></h2>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3><?= $total ?></h3>
                        <p>Total Risiko</p>
                    </div>
                    <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= $rekap['risiko_prioritas'] ?></h3>
                        <p>Risiko Tinggi & Sangat Tinggi</p>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $total - $rekap['rtp_lengkap'] ?></h3>
                        <p>RTP Belum Lengkap</p>
                    </div>
                    <div class="icon"><i class="fas fa-tasks"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $rekap['risiko_menurun'] ?></h3>
                        <p>Risiko Menurun</p>
                    </div>
                    <div class="icon"><i class="fas fa-chart-line"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <?php
            $kelengkapan = [
                ['Analisa Risiko', $rekap['analisa_lengkap'], 'primary'],
                ['Rencana Penanganan', $rekap['rtp_lengkap'], 'info'],
                ['Monitoring Risiko', $rekap['monitoring_lengkap'], 'success'],
                ['Dokumen Pendukung', $rekap['dokumen_lengkap'], 'warning']
            ];
            foreach ($kelengkapan as $item) :
            ?>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-<?= $item[2] ?>"><i class="fas fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><?= $item[0] ?></span>
                            <span class="info-box-number"><?= $item[1] ?> / <?= $total ?> (<?= $persen($item[1]) ?>%)</span>
                            <div class="progress">
                                <div class="progress-bar bg-<?= $item[2] ?>" style="width: <?= $persen($item[1]) ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card card-outline card-danger">
                    <div class="card-header">
                        <h3 class="card-title">Peringkat Risiko Awal</h3>
                    </div>
                    <div class="card-body">
                        <div style="height:300px"><canvas id="chartRisikoAwal"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">Peringkat Setelah RTP</h3>
                    </div>
                    <div class="card-body">
                        <div style="height:300px"><canvas id="chartRisikoResidual"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Jumlah Risiko per UPK</h3>
                    </div>
                    <div class="card-body">
                        <div style="height:300px"><canvas id="chartRisikoUpk"></canvas></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-danger">
            <div class="card-header">
                <h3 class="card-title fw-bold">Daftar Risiko Prioritas</h3>
                <div class="card-tools">
                    <h3 class="card-title fw-bold mr-2 text-danger"><?= $rekap['risiko_prioritas'] ?> risiko</h3>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-striped table-hover mb-0">
                    <thead class="text-center">
                        <tr>
                            <th class="align-middle">No</th>
                            <th class="align-middle">Bag/UPK</th>
                            <!-- <th>Kode Risiko</th> -->
                            <th class="align-middle">Kegiatan</th>
                            <th class="align-middle">Pernyataan Risiko</th>
                            <th class="align-middle">Peringkat Awal</th>
                            <th class="align-middle">Peringkat Setelah RTP</th>
                            <th class="align-middle">Status RTP</th>
                            <th class="align-middle">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($rekap['rows'] as $row) : ?>
                            <?php if (!in_array($row->peringkat_risiko, ['Sangat Tinggi', 'Tinggi'])) continue; ?>
                            <?php
                            $rtp_lengkap = trim((string)$row->uraian_penanganan) !== ''
                                && trim((string)$row->jadwal_penanganan) !== ''
                                && trim((string)$row->hasil_penanganan) !== ''
                                && trim((string)$row->pj_tl) !== '';
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= html_escape($row->nama_bagian) ?></td>
                                <!-- <td><?= html_escape($row->kode_risiko) ?></td> -->
                                <td><?= html_escape($row->kegiatan) ?></td>
                                <td><?= html_escape($row->pernyataan) ?></td>
                                <td class="text-center"><span class="badge badge-<?= $warnaPeringkat($row->peringkat_risiko) ?>"><?= html_escape($row->peringkat_risiko) ?></span></td>
                                <td class="text-center">
                                    <?php if ($row->peringkat_setelah) : ?>
                                        <span class="badge badge-<?= $warnaPeringkat($row->peringkat_setelah) ?>"><?= html_escape($row->peringkat_setelah) ?></span>
                                    <?php else : ?><span class="text-muted">Belum dinilai</span><?php endif; ?>
                                </td>
                                <td class="text-center"><span class="badge badge-<?= $rtp_lengkap ? 'success' : 'warning' ?>"><?= $rtp_lengkap ? 'Lengkap' : 'Belum lengkap' ?></span></td>
                                <td class="text-center"><a class="btn btn-sm btn-primary" href="<?= base_url('risiko/profil_risiko?id_upk=' . $row->id_upk . '&tahun=' . $row->tahun) ?>"><i class="fas fa-eye"></i></a></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if ($no === 1) : ?><tr>
                                <td colspan="8" class="text-center text-muted">Tidak ada risiko tinggi pada filter ini.</td>
                            </tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var levelLabels = <?= json_encode(array_keys($rekap['peringkat_awal'])) ?>;
        var levelColors = ['#dc3545', '#fd7e14', '#ffc107', '#28a745'];
        var commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            }
        };

        new Chart(document.getElementById('chartRisikoAwal').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: levelLabels,
                datasets: [{
                    data: <?= json_encode(array_values($rekap['peringkat_awal'])) ?>,
                    backgroundColor: levelColors
                }]
            },
            options: commonOptions
        });
        new Chart(document.getElementById('chartRisikoResidual').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: levelLabels,
                datasets: [{
                    data: <?= json_encode(array_values($rekap['peringkat_residual'])) ?>,
                    backgroundColor: levelColors
                }]
            },
            options: commonOptions
        });
        new Chart(document.getElementById('chartRisikoUpk').getContext('2d'), {
            type: 'horizontalBar',
            data: {
                labels: <?= json_encode(array_keys($rekap['per_upk'])) ?>,
                datasets: [{
                    label: 'Jumlah Risiko',
                    data: <?= json_encode(array_values($rekap['per_upk'])) ?>,
                    backgroundColor: '#007bff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }]
                }
            }
        });
    });
</script>