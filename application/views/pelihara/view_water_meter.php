<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('pelihara/water_meter') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('pelihara/water_meter'); ?>" method="get">
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
                    <?php if ($this->session->userdata('bagian') == 'Pemeliharaan') { ?>
                        <div class="navbar-nav ms-2">
                            <a href="<?= base_url('pelihara/water_meter/input_tm') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Tera Meter</button></a>
                        </div>
                        <div class="navbar-nav ms-2">
                            <a href="<?= base_url('pelihara/water_meter/input_gm') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Ganti Meter</button></a>
                        </div>
                    <?php } ?>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('pelihara/water_meter/cetak_water_meter') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>

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
                                <th>No</th>
                                <th>UPK</th>
                                <th>Jan</th>
                                <th>Feb</th>
                                <th>Mar</th>
                                <th>Apr</th>
                                <th>Mei</th>
                                <th>Jun</th>
                                <th>Jul</th>
                                <th>Ags</th>
                                <th>Sep</th>
                                <th>Okt</th>
                                <th>Nov</th>
                                <th>Des</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total_jan = $total_feb = $total_mar = $total_apr = 0;
                            $total_mei = $total_jun = $total_jul = $total_agu = 0;
                            $total_sep = $total_okt = $total_nov = $total_des = $total_semua = 0;
                            foreach ($tera_meter as $row) :
                                $total_jan += $row->jan;
                                $total_feb += $row->feb;
                                $total_mar += $row->mar;
                                $total_apr += $row->apr;
                                $total_mei += $row->mei;
                                $total_jun += $row->jun;
                                $total_jul += $row->jul;
                                $total_agu += $row->agu;
                                $total_sep += $row->sep;
                                $total_okt += $row->okt;
                                $total_nov += $row->nov;
                                $total_des += $row->des;
                                $total_semua += $row->total;
                            ?>
                                <tr onclick="window.location='<?= site_url('pelihara/water_meter/edit_tm/' . $row->id_bagian . '/' . urlencode($row->tgl_tm)) ?>'" style="cursor:pointer;">
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><?= $row->nama_bagian; ?></td>
                                    <td class="text-center"><?= $row->jan; ?></td>
                                    <td class="text-center"><?= $row->feb; ?></td>
                                    <td class="text-center"><?= $row->mar; ?></td>
                                    <td class="text-center"><?= $row->apr; ?></td>
                                    <td class="text-center"><?= $row->mei; ?></td>
                                    <td class="text-center"><?= $row->jun; ?></td>
                                    <td class="text-center"><?= $row->jul; ?></td>
                                    <td class="text-center"><?= $row->agu; ?></td>
                                    <td class="text-center"><?= $row->sep; ?></td>
                                    <td class="text-center"><?= $row->okt; ?></td>
                                    <td class="text-center"><?= $row->nov; ?></td>
                                    <td class="text-center"><?= $row->des; ?></td>
                                    <td class="text-center"><?= $row->total; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <th class="text-center" colspan="2">Jumlah</th>
                                <th class="text-center"><?= $total_jan; ?></th>
                                <th class="text-center"><?= $total_feb; ?></th>
                                <th class="text-center"><?= $total_mar; ?></th>
                                <th class="text-center"><?= $total_apr; ?></th>
                                <th class="text-center"><?= $total_mei; ?></th>
                                <th class="text-center"><?= $total_jun; ?></th>
                                <th class="text-center"><?= $total_jul; ?></th>
                                <th class="text-center"><?= $total_agu; ?></th>
                                <th class="text-center"><?= $total_sep; ?></th>
                                <th class="text-center"><?= $total_okt; ?></th>
                                <th class="text-center"><?= $total_nov; ?></th>
                                <th class="text-center"><?= $total_des; ?></th>
                                <th class="text-center font-weight-bold"><?= $total_semua; ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <br>
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title2); ?> <?= $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>UPK</th>
                                <th>Jan</th>
                                <th>Feb</th>
                                <th>Mar</th>
                                <th>Apr</th>
                                <th>Mei</th>
                                <th>Jun</th>
                                <th>Jul</th>
                                <th>Ags</th>
                                <th>Sep</th>
                                <th>Okt</th>
                                <th>Nov</th>
                                <th>Des</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total_jan = $total_feb = $total_mar = $total_apr = 0;
                            $total_mei = $total_jun = $total_jul = $total_agu = 0;
                            $total_sep = $total_okt = $total_nov = $total_des = $total_semua = 0;
                            foreach ($ganti_meter as $row) :
                                $total_jan += $row->jan;
                                $total_feb += $row->feb;
                                $total_mar += $row->mar;
                                $total_apr += $row->apr;
                                $total_mei += $row->mei;
                                $total_jun += $row->jun;
                                $total_jul += $row->jul;
                                $total_agu += $row->agu;
                                $total_sep += $row->sep;
                                $total_okt += $row->okt;
                                $total_nov += $row->nov;
                                $total_des += $row->des;
                                $total_semua += $row->total;
                            ?>
                                <tr onclick="window.location='<?= site_url('pelihara/water_meter/edit_gm/' . $row->id_bagian . '/' . urlencode($row->tgl_gm)) ?>'" style="cursor:pointer;">
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><?= $row->nama_bagian; ?></td>
                                    <td class="text-center"><?= $row->jan; ?></td>
                                    <td class="text-center"><?= $row->feb; ?></td>
                                    <td class="text-center"><?= $row->mar; ?></td>
                                    <td class="text-center"><?= $row->apr; ?></td>
                                    <td class="text-center"><?= $row->mei; ?></td>
                                    <td class="text-center"><?= $row->jun; ?></td>
                                    <td class="text-center"><?= $row->jul; ?></td>
                                    <td class="text-center"><?= $row->agu; ?></td>
                                    <td class="text-center"><?= $row->sep; ?></td>
                                    <td class="text-center"><?= $row->okt; ?></td>
                                    <td class="text-center"><?= $row->nov; ?></td>
                                    <td class="text-center"><?= $row->des; ?></td>
                                    <td class="text-center"><?= $row->total; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <th class="text-center" colspan="2">Jumlah</th>
                                <th class="text-center"><?= $total_jan; ?></th>
                                <th class="text-center"><?= $total_feb; ?></th>
                                <th class="text-center"><?= $total_mar; ?></th>
                                <th class="text-center"><?= $total_apr; ?></th>
                                <th class="text-center"><?= $total_mei; ?></th>
                                <th class="text-center"><?= $total_jun; ?></th>
                                <th class="text-center"><?= $total_jul; ?></th>
                                <th class="text-center"><?= $total_agu; ?></th>
                                <th class="text-center"><?= $total_sep; ?></th>
                                <th class="text-center"><?= $total_okt; ?></th>
                                <th class="text-center"><?= $total_nov; ?></th>
                                <th class="text-center"><?= $total_des; ?></th>
                                <th class="text-center font-weight-bold"><?= $total_semua; ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>