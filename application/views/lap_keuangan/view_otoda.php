<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('lap_keuangan/otoda'); ?>" method="get">
                        <div style="display: flex; align-items: center;">
                            <input type="submit" value="Pilih Tahun" class="neumorphic-button">
                            <!-- <input type="date" id="tahun" name="tahun" class="form-control" style="margin-left: 10px;"> -->
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
                    <a href="<?= base_url('lap_keuangan/otoda') ?>" style="text-decoration: none;"><button class="neumorphic-button ms-2"> Tahun ini</button></a>
                    <div class="navbar-nav ms-auto ">
                        <a href="<?= base_url('lap_keuangan/otoda_cetak'); ?>" target="_blank"><button class=" neumorphic-button float-right"><i class="fas fa-print"></i> Cetak</button></a>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-12 text-center">
                        <h5><strong><?= strtoupper($title); ?></strong></h5>
                        <h5><strong>Tahun Buku <?= $tahun_lap ?> dan <?= $tahun_lalu ?></strong></h5>
                        <h5>(Berdasarkan Permen Otoda Nomor 8 Tahun 2000)</h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>Uraian</th>
                                <th><?= $tahun_lap ?></th>
                                <th><?= $tahun_lalu ?></th>
                            </tr>
                        </thead>
                        <!-- <tbody>
                            <?php if (!empty($hitung_piutang)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($hitung_piutang as $row) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-left"><?= $row['uraian']; ?></td>
                                        <td class="text-right"><?= number_format($row['2_years_ago'], 2, ',', '.'); ?>%</td>
                                        <td class="text-right"><?= number_format($row['last_year'], 2, ',', '.'); ?>%</td>
                                        <td class="text-right"><?= number_format($row['this_year'], 2, ',', '.'); ?>%</td>
                                        <td class="text-right"><?= number_format($row['average'], 2, ',', '.'); ?>%</td>
                                        <td class="text-right"><?= number_format($row['saldo_this_year'], 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row['adjusted_piutang'], 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="font-weight-bold bg-light">
                                    <td colspan="2" class="text-center"><strong>Total</strong></td>
                                    <td class="text-right"><?= number_format($totals['2_years_ago'] / 100, 2, ',', '.'); ?>%</td>
                                    <td class="text-right"><?= number_format($totals['last_year'] / 100, 2, ',', '.'); ?>%</td>
                                    <td class="text-right"><?= number_format($totals['this_year'] / 100, 2, ',', '.'); ?>%</td>
                                    <td class="text-right"><?= number_format($totals['average'] / 100, 2, ',', '.'); ?>%</td>
                                    <td class="text-right"><?= number_format($totals['saldo_this_year'], 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($totals['adjusted_piutang'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                        </tbody> -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>