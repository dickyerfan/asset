<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('langganan/cak_layanan/data_pelanggan') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('langganan/cak_layanan/data_pelanggan'); ?>" method="get">
                        <div style="display: flex; align-items: center;">
                            <select id="tahun" name="tahun" class="form-control" style="margin-left: 15px;">
                                <?php
                                $currentYear = date('Y');
                                $selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear; // Memeriksa apakah ada tahun yang dipilih
                                for ($year = 1989; $year <= $currentYear; $year++) {
                                    $selected = ($year == $selectedYear) ? 'selected' : ''; // Menandai tahun yang dipilih
                                    echo "<option value='$year' $selected>$year</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </form>
                    <?php if ($this->session->userdata('bagian') == 'Langgan') { ?>
                        <div class="navbar-nav ms-2">
                            <a href="<?= base_url('langganan/cak_layanan/input_data_pelanggan') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Data Pelanggan</button></a>
                        </div>
                    <?php } ?>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('langganan/cak_layanan') ?>"><button class="float-end neumorphic-button"><i class="fas fa-map"></i> Cakupan Layanan</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('langganan/cak_layanan/data_penduduk') ?>"><button class="float-end neumorphic-button"><i class="fas fa-user"></i> Data Penduduk</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('langganan/cak_layanan/cetak_data_penduduk') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title); ?> <?= $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th rowspan="3" class="align-middle">Wilayah Pelayanan</th>
                                <th colspan="6" class="align-middle">Domestik</th>
                                <th colspan="8" class="align-middle">Non Domestik</th>
                                <th rowspan="3" class="align-middle">Total</th>
                                <th rowspan="3" class="align-middle">Total Aktif</th>
                                <th rowspan="3" class="align-middle">Action</th>
                            </tr>
                            <tr class="text-center">
                                <th rowspan="2" class="align-middle">Non Aktif</th>
                                <th rowspan="2" class="align-middle">RT</th>
                                <th rowspan="2" class="align-middle">Niaga</th>
                                <th colspan="2" class="text-center">Komunal</th>
                                <th colspan="2" class="text-center">HU/TA</th>
                                <th rowspan="2" class="align-middle">Non Aktif</th>
                                <th rowspan="2" class="align-middle">Sosial</th>
                                <th rowspan="2" class="align-middle">Niaga</th>
                                <th rowspan="2" class="align-middle">Industri</th>
                                <th rowspan="2" class="align-middle">Instansi</th>
                                <th rowspan="2" class="align-middle">K 2</th>
                                <th rowspan="2" class="align-middle">Lainnya</th>
                            </tr>
                            <tr class="text-center">
                                <th>SL</th>
                                <th>Unit Kom</th>
                                <th>SL</th>
                                <th>Jiwa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Inisialisasi variabel total agar tidak undefined
                            $total = [
                                'n_aktif_dom' => 0,
                                'rt_dom' => 0,
                                'niaga_dom' => 0,
                                'sl_kom_dom' => 0,
                                'unit_kom_dom' => 0,
                                'sl_hu_dom' => 0,
                                'jiwa_dom' => 0,
                                'n_aktif_n_dom' => 0,
                                'sosial_n_dom' => 0,
                                'niaga_n_dom' => 0,
                                'ind_n_dom' => 0,
                                'inst_n_dom' => 0,
                                'k2_n_dom' => 0,
                                'lain_n_dom' => 0,
                                'total_semua' => 0,
                                'total_semua_aktif' => 0
                            ];

                            foreach ($data_pelanggan as $data) :
                                $baris_total =
                                    ($data->n_aktif_dom ?? 0) +
                                    ($data->rt_dom ?? 0) +
                                    ($data->niaga_dom ?? 0) +
                                    ($data->sl_kom_dom ?? 0) +
                                    ($data->unit_kom_dom ?? 0) +
                                    ($data->sl_hu_dom ?? 0) +
                                    ($data->n_aktif_n_dom ?? 0) +
                                    ($data->sosial_n_dom ?? 0) +
                                    ($data->niaga_n_dom ?? 0) +
                                    ($data->ind_n_dom ?? 0) +
                                    ($data->inst_n_dom ?? 0) +
                                    ($data->k2_n_dom ?? 0) +
                                    ($data->lain_n_dom ?? 0);

                                $total_aktif =
                                    ($data->rt_dom ?? 0) +
                                    ($data->niaga_dom ?? 0) +
                                    ($data->sl_kom_dom ?? 0) +
                                    ($data->unit_kom_dom ?? 0) +
                                    ($data->sl_hu_dom ?? 0) +
                                    ($data->sosial_n_dom ?? 0) +
                                    ($data->niaga_n_dom ?? 0) +
                                    ($data->ind_n_dom ?? 0) +
                                    ($data->inst_n_dom ?? 0) +
                                    ($data->k2_n_dom ?? 0) +
                                    ($data->lain_n_dom ?? 0);

                                // Tambahkan ke total keseluruhan
                                $total['n_aktif_dom'] += $data->n_aktif_dom ?? 0;
                                $total['rt_dom'] += $data->rt_dom ?? 0;
                                $total['niaga_dom'] += $data->niaga_dom ?? 0;
                                $total['sl_kom_dom'] += $data->sl_kom_dom ?? 0;
                                $total['unit_kom_dom'] += $data->unit_kom_dom ?? 0;
                                $total['sl_hu_dom'] += $data->sl_hu_dom ?? 0;
                                $total['jiwa_dom'] += $data->jiwa_dom ?? 0;
                                $total['n_aktif_n_dom'] += $data->n_aktif_n_dom ?? 0;
                                $total['sosial_n_dom'] += $data->sosial_n_dom ?? 0;
                                $total['niaga_n_dom'] += $data->niaga_n_dom ?? 0;
                                $total['ind_n_dom'] += $data->ind_n_dom ?? 0;
                                $total['inst_n_dom'] += $data->inst_n_dom ?? 0;
                                $total['k2_n_dom'] += $data->k2_n_dom ?? 0;
                                $total['lain_n_dom'] += $data->lain_n_dom ?? 0;
                                $total['total_semua'] += $baris_total;
                                $total['total_semua_aktif'] += $total_aktif;
                            ?>
                                <tr class="text-center">
                                    <td class="text-left"><?= $data->nama_kec; ?></td>
                                    <td><?= number_format($data->n_aktif_dom ?? 0, '0', ',', '.'); ?></td>
                                    <td><?= number_format($data->rt_dom ?? 0, '0', ',', '.'); ?></td>
                                    <td><?= number_format($data->niaga_dom ?? 0, '0', ',', '.'); ?></td>
                                    <td><?= number_format($data->sl_kom_dom ?? 0, '0', ',', '.'); ?></td>
                                    <td><?= number_format($data->unit_kom_dom ?? 0, '0', ',', '.'); ?></td>
                                    <td><?= number_format($data->sl_hu_dom ?? 0, '0', ',', '.'); ?></td>
                                    <td><?= number_format($data->jiwa_dom ?? 0, '0', ',', '.'); ?></td>
                                    <td><?= number_format($data->n_aktif_n_dom ?? 0, '0', ',', '.'); ?></td>
                                    <td><?= number_format($data->sosial_n_dom ?? 0, '0', ',', '.'); ?></td>
                                    <td><?= number_format($data->niaga_n_dom ?? 0, '0', ',', '.'); ?></td>
                                    <td><?= number_format($data->ind_n_dom ?? 0, '0', ',', '.'); ?></td>
                                    <td><?= number_format($data->inst_n_dom ?? 0, '0', ',', '.'); ?></td>
                                    <td><?= number_format($data->k2_n_dom ?? 0, '0', ',', '.'); ?></td>
                                    <td><?= number_format($data->lain_n_dom ?? 0, '0', ',', '.'); ?></td>
                                    <td><?= number_format($baris_total, '0', ',', '.'); ?></td>
                                    <td><?= number_format($total_aktif, '0', ',', '.'); ?></td>
                                    <td>
                                        <?php if ($this->session->userdata('bagian') == 'Langgan' or $this->session->userdata('bagian') == 'Administrator') { ?>
                                            <div class="text-center">
                                                <a href="<?= base_url('langganan/cak_layanan/edit_data_pelanggan/' . $data->id_data_pelanggan) ?>"><i class="fas fa-edit"></i></a>
                                            </div>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="font-weight-bold text-center bg-light">
                                <td>Total</td>
                                <td><?= number_format($total['n_aktif_dom'], '0', ',', '.');  ?></td>
                                <td><?= number_format($total['rt_dom'], '0', ',', '.'); ?></td>
                                <td><?= number_format($total['niaga_dom'], '0', ',', '.'); ?></td>
                                <td><?= number_format($total['sl_kom_dom'], '0', ',', '.'); ?></td>
                                <td><?= number_format($total['unit_kom_dom'], '0', ',', '.'); ?></td>
                                <td><?= number_format($total['sl_hu_dom'], '0', ',', '.'); ?></td>
                                <td><?= number_format($total['jiwa_dom'], '0', ',', '.'); ?></td>
                                <td><?= number_format($total['n_aktif_n_dom'], '0', ',', '.'); ?></td>
                                <td><?= number_format($total['sosial_n_dom'], '0', ',', '.'); ?></td>
                                <td><?= number_format($total['niaga_n_dom'], '0', ',', '.'); ?></td>
                                <td><?= number_format($total['ind_n_dom'], '0', ',', '.'); ?></td>
                                <td><?= number_format($total['inst_n_dom'], '0', ',', '.'); ?></td>
                                <td><?= number_format($total['k2_n_dom'], '0', ',', '.'); ?></td>
                                <td><?= number_format($total['lain_n_dom'], '0', ',', '.'); ?></td>
                                <td><?= number_format($total['total_semua'], '0', ',', '.'); ?></td>
                                <td><?= number_format($total['total_semua_aktif'], '0', ',', '.'); ?></td>
                                <td></td>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>