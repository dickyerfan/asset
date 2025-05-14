<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('pelihara/kapasitas_produksi') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('pelihara/kapasitas_produksi'); ?>" method="get">
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
                            <a href="<?= base_url('pelihara/kapasitas_produksi/input_kp') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Kapasitas Produksi</button></a>
                        </div>
                    <?php } ?>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('pelihara/kapasitas_produksi/cetak_kapasitas_produksi') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>

                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title); ?> <?= $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="container mt-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Uraian</th>
                                    <th class="text-right">Nilai</th>
                                    <th>Satuan</th>
                                    <th class="text-right">Liter/Detik</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>Kapasitas Produksi terpasang (Kapasitas desain)</td>
                                    <td class="text-right"><?= number_format($terpasang, 0, ',', '.'); ?></td>
                                    <td>M3</td>
                                    <td class="text-right"><?= number_format($kap_pasang, 1, ',', '.'); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td>Kapasitas produksi terpasang yang tidak dapat dimanfaatkan</td>
                                    <td class="text-right"><?= number_format($tidak_manfaat, 0, ',', '.'); ?></td>
                                    <td>M3</td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class="text-center">3</td>
                                    <td>Kapasitas Produksi Riil (1-2)</td>
                                    <td class="text-right"><?= number_format($kap_riil, 0, ',', '.'); ?></td>
                                    <td>M3</td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class="text-center">4</td>
                                    <td>Kapasitas Produksi menganggur (idle capacity)</td>
                                    <td class="text-right"><?= number_format($kap_menganggur, 0, ',', '.'); ?></td>
                                    <td>M3</td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class="text-center">5</td>
                                    <td>Volume Produksi Air riil (3-4)</td>
                                    <td class="text-right"><?= number_format($volume_produksi, 0, ',', '.'); ?></td>
                                    <td>M3</td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class="text-center">6</td>
                                    <td>Volume Kehilangan Air saat Produksi</td>
                                    <td class="text-right"><?= number_format($total_nrw_air, 0, ',', '.'); ?></td>
                                    <td>M3</td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class="text-center">7</td>
                                    <td>Volume Air yang Didistribusikan</td>
                                    <td class="text-right"><?= number_format($volume_produksi, 0, ',', '.'); ?></td>
                                    <td>M3</td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class="text-center">8</td>
                                    <td>Volume Air yang terjual</td>
                                    <td class="text-right"><?= number_format($total_vol, 0, ',', '.'); ?></td>
                                    <td>M3</td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class="text-center">9</td>
                                    <td>Air Tanpa Rekening / Kebocoran (NRW Distribusi) *4</td>
                                    <td class="text-right"><?= number_format($total_nrw_air, 0, ',', '.'); ?></td>
                                    <td>M3</td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class="text-center">10</td>
                                    <td>% Volume kebocoran air dari Produksi ke Distribusi</td>
                                    <td class="text-right">0,00</td>
                                    <td colspan="2">%</td>
                                </tr>
                                <tr>
                                    <td class="text-center">11</td>
                                    <td>% Volume Kebocoran Air dari Distribusi ke pelanggan</td>
                                    <td class="text-right"><?= number_format($persen_nrw_air, 2, ',', '.'); ?></td>
                                    <td colspan="2">%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th rowspan="2" class="align-middle">No</th>
                                <th rowspan="2" class="align-middle">Nama Instalasi</th>
                                <th rowspan="2" class="align-middle">Kap. Terpasang <br>/desain <br> (ltr/dtk)</th>
                                <th colspan="2">Kapasitas Produksi</th>
                                <th rowspan="2" class="align-middle">Kapasitas Riil(M3)</th>
                                <th rowspan="2" class="align-middle">Volume Produksi(M3)</th>
                                <th rowspan="2" class="align-middle">Kapasitas Menganggur(M3)</th>
                                <th rowspan="2" class="align-middle">Action</th>
                            </tr>
                            <tr class="text-center">
                                <th>Terpasang/desain(M3)</th>
                                <th>Tidak dimanfaatkan(M3)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total_kap_pasang = 0;
                            $total_terpasang = 0;
                            $total_tidak_manfaat = 0;
                            $total_volume_produksi = 0;
                            $total_kap_riil = 0;
                            $total_kap_menganggur = 0;

                            foreach ($kapasitas_produksi as $row) :
                                $kap_pasang = $row->kap_pasang;
                                $terpasang = $row->terpasang;
                                $tidak_manfaat = $row->tidak_manfaat;
                                $volume_produksi = $row->volume_produksi;
                                $kap_riil = $terpasang - $tidak_manfaat;
                                $kap_menganggur = $kap_riil - $volume_produksi;

                                $total_kap_pasang += $kap_pasang;
                                $total_terpasang += $terpasang;
                                $total_tidak_manfaat += $tidak_manfaat;
                                $total_volume_produksi += $volume_produksi;
                                $total_kap_riil += $kap_riil;
                                $total_kap_menganggur += $kap_menganggur;
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><?= $row->nama_bagian; ?></td>
                                    <td class="text-right"><?= number_format($row->kap_pasang, 1, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->terpasang, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->tidak_manfaat, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($kap_riil, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($volume_produksi, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($kap_menganggur, 0, ',', '.'); ?></td>
                                    <td>
                                        <?php if ($this->session->userdata('bagian') == 'Pemeliharaan' or $this->session->userdata('bagian') == 'Administrator') { ?>
                                            <div class="text-center">
                                                <a href="<?= base_url('pelihara/kapasitas_produksi/edit_kp/' . $row->id_ek_kp) ?>"><i class="fas fa-edit"></i></a>
                                            </div>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <th colspan="2" class="text-center">Jumlah</th>
                                <th class="text-right"><?= number_format($total_kap_pasang, 1, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_terpasang, 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tidak_manfaat, 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kap_riil, 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_volume_produksi, 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kap_menganggur, 0, ',', '.'); ?></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>