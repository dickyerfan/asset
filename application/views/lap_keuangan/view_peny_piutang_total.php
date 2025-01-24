<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('lap_keuangan/peny_piutang/data_total'); ?>" method="get">
                        <div style="display: flex; align-items: center;">
                            <input type="submit" value="Pilih Tahun" class="neumorphic-button">
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
                    <a href="<?= base_url('lap_keuangan/peny_piutang/data_total') ?>" style="text-decoration: none;"><button class="neumorphic-button ms-2"> Tahun ini</button></a>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('lap_keuangan/peny_piutang/data_total_cetak'); ?>" target="_blank"><button class=" neumorphic-button float-right"><i class="fas fa-print"></i> Cetak</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('lap_keuangan/peny_piutang'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
                    </div>
                </nav>
            </div>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-12 text-left ">
                        <h5><strong> Penyisihan Kerugian Piutang Usaha<br>Daftar Mutasi Piutang Air 3 Tahun Terakhir</strong></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Uraian</th>
                                <th>Saldo Awal</th>
                                <th>Tambah</th>
                                <th>Kurang</th>
                                <th>Saldo Akhir</th>
                                <th>Persen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($piutang as $year => $rows) : ?>
                                <tr>
                                    <td colspan="7" class="text-left font-weight-bold bg-light">
                                        DATA TAHUN <?= $year; ?>
                                    </td>
                                </tr>
                                <?php
                                $no = 1;
                                foreach ($rows as $row) :
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-left"><?= $row->kel_tarif_ket; ?></td>
                                        <td class="text-right"><?= number_format($row->saldo_awal, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->tambah, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->kurang, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->saldo_akhir, 0, ',', '.'); ?></td>
                                        <td class="text-center"><?= $row->persen_tagih; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (isset($totals[$year])) : ?>
                                    <tr class="bg-secondary text-white">
                                        <td></td>
                                        <td class="text-left font-weight-bold">JUMLAH</td>
                                        <td class="text-right"><?= number_format($totals[$year]['total_saldo_awal'], 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($totals[$year]['total_tambah'], 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($totals[$year]['total_kurang'], 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($totals[$year]['total_saldo_akhir'], 0, ',', '.'); ?></td>
                                        <td class="text-center"><?= number_format($totals[$year]['rata_rata_persen_tagih'], 2); ?>%</td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>