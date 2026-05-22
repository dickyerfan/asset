<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar">
                    <form id="form_tahun" action="<?= base_url('lap_keuangan/peny_piutang_baru'); ?>" method="get">
                        <div style="display: flex; align-items: center;">
                            <input type="submit" value="Pilih Tahun" class="neumorphic-button">
                            <select id="tahun" name="tahun" class="form-control" style="margin-left: 15px;">
                                <?php
                                $currentYear = date('Y');
                                $selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $tahun_lap;
                                for ($year = 1989; $year <= $currentYear; $year++) {
                                    $selected = ($year == $selectedYear) ? 'selected' : '';
                                    echo "<option value='$year' $selected>$year</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </form>
                    <a href="<?= base_url('lap_keuangan/peny_piutang_baru') ?>" style="text-decoration: none;"><button class="neumorphic-button ms-2"> Tahun ini</button></a>
                    <div class="navbar-nav ms-auto">
                        <?php if ($this->session->userdata('level') != 'Pengguna') : ?>
                            <a href="<?= base_url('lap_keuangan/peny_piutang_baru/import'); ?>"><button class="neumorphic-button float-right"><i class="fas fa-file-import"></i> Import Excel</button></a>
                            <a href="<?= base_url('lap_keuangan/peny_piutang_baru/input_peny_piutang_lain'); ?>"><button class="neumorphic-button float-right ml-2"><i class="fas fa-plus"></i> Input Penyisihan Lain-lain</button></a>
                        <?php endif; ?>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-12 text-center">
                        <h5><strong><?= strtoupper($title) . ' TAHUN ' . $tahun_lap; ?></strong></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover table-sm">
                        <thead>
                            <tr class="text-center">
                                <th rowspan="3">NO</th>
                                <th rowspan="3">KELOMPOK PELANGGAN</th>
                                <th rowspan="2">PIUTANG 1 S/D 3 BULAN</th>
                                <th colspan="2">PIUTANG 4 S/D 6 BULAN</th>
                                <th colspan="2">PIUTANG 6 S/D 12 BULAN</th>
                                <th colspan="2">PIUTANG 12 S/D 18 BULAN</th>
                                <th colspan="2">PIUTANG 18 S/D 24 BULAN</th>
                                <th colspan="2">PIUTANG 24 BULAN KE ATAS</th>
                                <th colspan="2">JUMLAH</th>
                            </tr>
                            <tr class="text-center">
                                <th>PIUTANG AIR</th>
                                <th>PENYISIHAN 25%</th>
                                <th>PIUTANG AIR</th>
                                <th>PENYISIHAN 50%</th>
                                <th>PIUTANG AIR</th>
                                <th>PENYISIHAN 75%</th>
                                <th>PIUTANG AIR</th>
                                <th>PENYISIHAN 100%</th>
                                <th>PIUTANG AIR</th>
                                <th>PENYISIHAN 100%</th>
                                <th>PIUTANG AIR</th>
                                <th>PENYISIHAN</th>
                            </tr>
                            <tr class="text-center">
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>5</th>
                                <th>6</th>
                                <th>7</th>
                                <th>8</th>
                                <th>9</th>
                                <th>10</th>
                                <th>11</th>
                                <th>1+2+4+6+8+10</th>
                                <th>3+5+7+9+11</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($piutang)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($piutang as $row) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><?= $row->kel_tarif_ket; ?></td>
                                        <td class="text-right"><?= number_format($row->piutang_1_3_bulan, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->piutang_4_6_bulan, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->penyisihan_4_6_bulan, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->piutang_6_12_bulan, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->penyisihan_6_12_bulan, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->piutang_12_18_bulan, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->penyisihan_12_18_bulan, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->piutang_18_24_bulan, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->penyisihan_18_24_bulan, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->piutang_24_bulan_keatas, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->penyisihan_24_bulan_keatas, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_piutang, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_penyisihan, 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="font-weight-bold bg-light">
                                    <td colspan="2" class="text-center">JUMLAH</td>
                                    <td class="text-right"><?= number_format($totals['piutang_1_3_bulan'], 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($totals['piutang_4_6_bulan'], 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($totals['penyisihan_4_6_bulan'], 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($totals['piutang_6_12_bulan'], 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($totals['penyisihan_6_12_bulan'], 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($totals['piutang_12_18_bulan'], 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($totals['penyisihan_12_18_bulan'], 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($totals['piutang_18_24_bulan'], 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($totals['penyisihan_18_24_bulan'], 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($totals['piutang_24_bulan_keatas'], 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($totals['penyisihan_24_bulan_keatas'], 0, ',', '.'); ?></td>
                                    <td class="text-right">
                                        <a href="<?= base_url('lap_keuangan/peny_piutang_baru/input_piutang_usaha/' . $tahun_lap . '/' . round($totals['jumlah_piutang'])) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan total piutang air ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                            <?= number_format($totals['jumlah_piutang'], 0, ',', '.'); ?>
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        <a href="<?= base_url('lap_keuangan/peny_piutang_baru/input_akm_piutang_usaha/' . $tahun_lap . '/' . round($totals['jumlah_penyisihan'])) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan total penyisihan ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                            <?= number_format($totals['jumlah_penyisihan'], 0, ',', '.'); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php else : ?>
                                <tr>
                                    <td colspan="15" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
