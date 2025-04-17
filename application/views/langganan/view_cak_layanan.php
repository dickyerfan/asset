<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('langganan/cak_layanan') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('langganan/cak_layanan'); ?>" method="get">
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
                    <!-- <?php if ($this->session->userdata('bagian') == 'Langgan') { ?>
                        <div class="navbar-nav ms-2">
                            <a href="<?= base_url('langganan/cak_layanan/input_data_penduduk') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Data Penduduk</button></a>
                        </div>
                    <?php } ?> -->
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('langganan/cak_layanan/data_penduduk') ?>"><button class="float-end neumorphic-button"><i class="fas fa-user"></i> Data Penduduk</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('langganan/cak_layanan/data_pelanggan') ?>"><button class="float-end neumorphic-button"><i class="fas fa-user"></i> Data Pelanggan</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('langganan/cak_layanan/cetak_cak_layanan') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title); ?> <?= $tahun_lap; ?></h5>
                    </div>
                </div>
            </div>
            <div class="container my-4">
                <h5 class="font-weight-bold">CAKUPAN PELAYANAN ADMINISTRATIF</h5>
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr>
                            <td colspan="3"><strong>Wilayah Administratif</strong></td>
                        </tr>
                        <tr>
                            <td>Jumlah penduduk</td>
                            <td>:</td>
                            <td class="text-right"><?= number_format($cakupan['total_penduduk'] ?? 0, 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Jumlah KK</td>
                            <td>:</td>
                            <td class="text-right"><?= number_format($cakupan['total_kk'] ?? 0, 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Rata - rata Jiwa per RT</td>
                            <td>:</td>
                            <td class="text-right"><?= number_format($cakupan['rata_jiwa_kk'] ?? 0, 6, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Yang dilayani air bersih</td>
                            <td>:</td>
                            <td class="text-right"><?= number_format($cakupan['jumlah_wil_layan_ya'] ?? 0, 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Jumlah Kec di Wilayah Administratif</td>
                            <td>:</td>
                            <td class="text-right"><?= number_format($cakupan['total_wil_layan_semua'] ?? 0, 0, ',', '.'); ?></td>
                        </tr>
                    </tbody>
                </table>

                <?php
                $total_pelanggan =
                    ($pelanggan['total_rt_dom'] ?? 0) +
                    ($pelanggan['total_niaga_dom'] ?? 0) +
                    ($pelanggan['total_sl_hu_dom'] ?? 0) +
                    ($pelanggan['total_n_aktif_dom'] ?? 0);

                $rata_jiwa = $cakupan['rata_jiwa_kk'] ?? 0;
                $total_jiwa_terlayani = $pelanggan['total_rt_dom'] * $rata_jiwa + $pelanggan['total_niaga_dom'] * $rata_jiwa + $pelanggan['total_sl_hu_dom'] * 100 + $pelanggan['total_n_aktif_dom'] * $rata_jiwa;
                $cakupan_admin = ($cakupan['total_penduduk'] ?? 0) > 0
                    ? ($total_jiwa_terlayani / $cakupan['total_penduduk']) * 100
                    : 0;

                $rata_jiwa2 = $cakupan['rata_jiwa_kk2'] ?? 0;
                $total_jiwa_terlayani2 = $pelanggan['total_rt_dom'] * $rata_jiwa2 + $pelanggan['total_niaga_dom'] * $rata_jiwa2 + $pelanggan['total_sl_hu_dom'] * 100 + $pelanggan['total_n_aktif_dom'] * $rata_jiwa2;

                $cakupan_teknis = ($cakupan['total_penduduk'] ?? 0) > 0
                    ? ($total_jiwa_terlayani2 / $cakupan['total_wil_layan']) * 100
                    : 0;
                ?>

                <table class="table table-bordered table-sm mt-4">
                    <thead class="thead-light text-center">
                        <tr>
                            <th rowspan="2" class="align-middle">Pelanggan Domestik</th>
                            <th colspan="3">Jumlah</th>
                        </tr>
                        <tr>
                            <th>Pelanggan</th>
                            <th>Jiwa Rata2</th>
                            <th>Jiwa Terlayani</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Rumah Tangga</td>
                            <td class="text-right"><?= number_format($pelanggan['total_rt_dom'] ?? 0, 0, ',', '.'); ?> SL</td>
                            <td class="text-right"><?= number_format($cakupan['rata_jiwa_kk'] ?? 0, 6, ',', '.'); ?></td>
                            <td class="text-right"><?= number_format(($pelanggan['total_rt_dom'] ?? 0) * $rata_jiwa, 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Niaga Kecil + Menengah</td>
                            <td class="text-right"><?= number_format($pelanggan['total_niaga_dom'] ?? 0, 0, ',', '.'); ?> SL</td>
                            <td class="text-right"><?= number_format($cakupan['rata_jiwa_kk'] ?? 0, 6, ',', '.'); ?></td>
                            <td class="text-right"><?= number_format(($pelanggan['total_niaga_dom'] ?? 0) * $rata_jiwa, 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Hunian Vertikal + Kawasan Hunian</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td>Hidran Umum</td>
                            <td class="text-right"><?= number_format($pelanggan['total_sl_hu_dom'] ?? 0, 0, ',', '.'); ?> SL</td>
                            <td class="text-right">-</td>
                            <td class="text-right"><?= number_format(($pelanggan['total_sl_hu_dom'] ?? 0) * 100, 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Pelanggan Tidak Aktif</td>
                            <td class="text-right"><?= number_format($pelanggan['total_n_aktif_dom'] ?? 0, 0, ',', '.'); ?> SL</td>
                            <td class="text-right"><?= number_format($cakupan['rata_jiwa_kk'] ?? 0, 6, ',', '.'); ?></td>
                            <td class="text-right"><?= number_format(($pelanggan['total_n_aktif_dom'] ?? 0) * $rata_jiwa, 0, ',', '.'); ?></td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td>Jumlah</td>
                            <td class="text-right"><?= number_format($total_pelanggan ?? 0, 0, ',', '.'); ?> SL</td>
                            <td></td>
                            <td class="text-right"><?= number_format($total_jiwa_terlayani ?? 0, 0, ',', '.'); ?></td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right font-weight-bold">
                    Cakupan Pelayanan Administratif : <?= number_format($cakupan_admin ?? 0, 2, ',', '.'); ?> %
                </div>
            </div>
            <div class="container my-4">
                <h5 class="font-weight-bold">CAKUPAN PELAYANAN TEKNIS</h5>
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr>
                            <td colspan="3"><strong>Wilayah Pelayanan</strong></td>
                        </tr>
                        <tr>
                            <td>Jumlah penduduk di wilayah pelayanan</td>
                            <td>:</td>
                            <td class="text-right"><?= number_format($cakupan['total_wil_layan'] ?? 0, 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Jumlah Kec di wilayah pelayanan</td>
                            <td>:</td>
                            <td class="text-right"><?= number_format($cakupan['jumlah_wil_layan_ya'] ?? 0, 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Jumlah KK di wilayah pelayanan</td>
                            <td>:</td>
                            <td class="text-right"><?= number_format($cakupan['total_kk_layan'] ?? 0, 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Rata2 jiwa per KK di wilayah pelayanan</td>
                            <td>:</td>
                            <td class="text-right"><?= number_format($cakupan['rata_jiwa_kk2'] ?? 0, 6, ',', '.'); ?></td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered table-sm mt-4">
                    <thead class="thead-light text-center">
                        <tr>
                            <th rowspan="2" class="align-middle">Pelanggan Domestik</th>
                            <th colspan="3">Jumlah</th>
                        </tr>
                        <tr>
                            <th>Pelanggan</th>
                            <th>Jiwa Rata2</th>
                            <th>Jiwa Terlayani</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Rumah Tangga</td>
                            <td class="text-right"><?= number_format($pelanggan['total_rt_dom'] ?? 0, 0, ',', '.'); ?> SL</td>
                            <td class="text-right"><?= number_format($cakupan['rata_jiwa_kk2'] ?? 0, 6, ',', '.'); ?></td>
                            <td class="text-right"><?= number_format(($pelanggan['total_rt_dom'] ?? 0) * $rata_jiwa2, 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Niaga Kecil + Menengah</td>
                            <td class="text-right"><?= number_format($pelanggan['total_niaga_dom'] ?? 0, 0, ',', '.'); ?> SL</td>
                            <td class="text-right"><?= number_format($cakupan['rata_jiwa_kk2'] ?? 0, 6, ',', '.'); ?></td>
                            <td class="text-right"><?= number_format(($pelanggan['total_niaga_dom'] ?? 0) * $rata_jiwa2, 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Hunian Vertikal + Kawasan Hunian</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                        </tr>
                        <tr>
                            <td>Hidran Umum</td>
                            <td class="text-right"><?= number_format($pelanggan['total_sl_hu_dom'] ?? 0, 0, ',', '.'); ?> SL</td>
                            <td class="text-right">-</td>
                            <td class="text-right"><?= number_format(($pelanggan['total_sl_hu_dom'] ?? 0) * 100, 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Pelanggan Tidak Aktif</td>
                            <td class="text-right"><?= number_format($pelanggan['total_n_aktif_dom'] ?? 0, 0, ',', '.'); ?> SL</td>
                            <td class="text-right"><?= number_format($cakupan['rata_jiwa_kk2'] ?? 0, 6, ',', '.'); ?></td>
                            <td class="text-right"><?= number_format(($pelanggan['total_n_aktif_dom'] ?? 0) * $rata_jiwa2, 0, ',', '.'); ?></td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td>Jumlah</td>
                            <td class="text-right"><?= number_format($total_pelanggan ?? 0, 0, ',', '.'); ?> SL</td>
                            <td></td>
                            <td class="text-right"><?= number_format($total_jiwa_terlayani2 ?? 0, 0, ',', '.'); ?></td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right font-weight-bold">
                    Cakupan Pelayanan Teknis : <?= number_format($cakupan_teknis ?? 0, 2, ',', '.'); ?> %
                </div>
            </div>

        </div>
    </div>
</section>
</div>