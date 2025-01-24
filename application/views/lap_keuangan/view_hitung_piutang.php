<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('lap_keuangan/peny_piutang/hitung_piutang'); ?>" method="get">
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
                    <a href="<?= base_url('lap_keuangan/peny_piutang/hitung_piutang') ?>" style="text-decoration: none;"><button class="neumorphic-button ms-2"> Tahun ini</button></a>
                    <div class="navbar-nav ms-auto ">
                        <a href="<?= base_url('lap_keuangan/peny_piutang/hitung_piutang_cetak'); ?>" target="_blank"><button class=" neumorphic-button float-right"><i class="fas fa-print"></i> Cetak</button></a>
                    </div>
                    <!-- <div class="navbar-nav ms-2">
                        <a href="<?= base_url('lap_keuangan/peny_piutang/data_total'); ?>"><button class=" neumorphic-button float-right"> Data Rekap</button></a>
                    </div> -->
                </nav>
            </div>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">

                        <?php
                        if (empty($tahun_lap)) {
                            // $bulan_lap = date('m');
                            $tahun_lap = date('Y');
                        }
                        ?>
                        <h5><?= strtoupper($title) . ' ' . $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Uraian</th>
                                <th>Tahun 2022</th>
                                <th>Tahun 2023</th>
                                <th>Tahun 2024</th>
                                <th>Rata-rata</th>
                                <th>Saldo Tahun Ini</th>
                                <th>Peny. Piutang Air</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td>Piutang Air</td>
                                <td class="text-center"><?= number_format($piutang['persen_tagih_2022'] ?? 0, 2, ',', '.'); ?>%</td>
                                <td class="text-center"><?= number_format($piutang['persen_tagih_2023'] ?? 0, 2, ',', '.'); ?>%</td>
                                <td class="text-center"><?= number_format($piutang['persen_tagih_2024'] ?? 0, 2, ',', '.'); ?>%</td>
                                <td class="text-center"><?= number_format($piutang['rata_rata'] ?? 0, 2, ',', '.'); ?>%</td>
                                <td class="text-center"><?= number_format($piutang['saldo_akhir'] ?? 0, 2, ',', '.'); ?></td>
                                <td class="text-center"><?= number_format($piutang['peny_piutang_air'] ?? 0, 2, ',', '.'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>