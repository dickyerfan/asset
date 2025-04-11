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
                    <?php if ($this->session->userdata('bagian') == 'Langgan') { ?>
                        <div class="navbar-nav ms-2">
                            <a href="<?= base_url('langganan/cak_layanan/input_data_penduduk') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Data Penduduk</button></a>
                        </div>
                    <?php } ?>
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
                            <td class="text-right">788.007</td>
                        </tr>
                        <tr>
                            <td>Jumlah KK</td>
                            <td>:</td>
                            <td class="text-right">312.241</td>
                        </tr>
                        <tr>
                            <td>Rata - rata Jiwa per RT</td>
                            <td>:</td>
                            <td class="text-right">2,52 / 2,523714</td>
                        </tr>
                        <tr>
                            <td>Yang dilayani air bersih</td>
                            <td>:</td>
                            <td class="text-right">17</td>
                        </tr>
                        <tr>
                            <td>Jumlah Kec di Wilayah Administratif</td>
                            <td>:</td>
                            <td class="text-right">23</td>
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
                            <td class="text-right">18.102 SL</td>
                            <td class="text-right">2,523714</td>
                            <td class="text-right">45.684</td>
                        </tr>
                        <tr>
                            <td>Niaga Kecil + Menengah</td>
                            <td class="text-right">449 SL</td>
                            <td class="text-right">2,523714</td>
                            <td class="text-right">1.133</td>
                        </tr>
                        <tr>
                            <td>Hunian Vertikal + Kawasan Hunian</td>
                            <td class="text-right">-</td>
                            <td class="text-right">0,00</td>
                            <td class="text-right">0</td>
                        </tr>
                        <tr>
                            <td>Hidran Umum</td>
                            <td class="text-right">53 SL</td>
                            <td class="text-right">-</td>
                            <td class="text-right">5.300</td>
                        </tr>
                        <tr>
                            <td>Pelanggan Tidak Aktif</td>
                            <td class="text-right">1.546 SL</td>
                            <td class="text-right">2,523714</td>
                            <td class="text-right">3.902</td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td>Jumlah</td>
                            <td class="text-right">20.150 SL</td>
                            <td></td>
                            <td class="text-right">56.019</td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right font-weight-bold">
                    Cakupan Pelayanan Administratif : 7,11%
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
                            <td class="text-right">609207</td>
                        </tr>
                        <tr>
                            <td>Jumlah Kec di wilayah pelayanan</td>
                            <td>:</td>
                            <td class="text-right">17</td>
                        </tr>
                        <tr>
                            <td>Jumlah KK di wilayah pelayanan</td>
                            <td>:</td>
                            <td class="text-right">241.306</td>
                        </tr>
                        <tr>
                            <td>Rata2 jiwa per KK di wilayah pelayanan</td>
                            <td>:</td>
                            <td class="text-right">2,5</td>
                        </tr>
                        <tr>
                            <td>Jumlah Kec di Wilayah Administratif</td>
                            <td>:</td>
                            <td class="text-right">23</td>
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
                            <td class="text-right">18.102 SL</td>
                            <td class="text-right">2,523714</td>
                            <td class="text-right">45.684</td>
                        </tr>
                        <tr>
                            <td>Niaga Kecil + Menengah</td>
                            <td class="text-right">449 SL</td>
                            <td class="text-right">2,523714</td>
                            <td class="text-right">1.133</td>
                        </tr>
                        <tr>
                            <td>Hunian Vertikal + Kawasan Hunian</td>
                            <td class="text-right">-</td>
                            <td class="text-right">0,00</td>
                            <td class="text-right">0</td>
                        </tr>
                        <tr>
                            <td>Hidran Umum</td>
                            <td class="text-right">53 SL</td>
                            <td class="text-right">-</td>
                            <td class="text-right">5.300</td>
                        </tr>
                        <tr>
                            <td>Pelanggan Tidak Aktif</td>
                            <td class="text-right">1.546 SL</td>
                            <td class="text-right">2,523714</td>
                            <td class="text-right">3.902</td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td>Jumlah</td>
                            <td class="text-right">20.150 SL</td>
                            <td></td>
                            <td class="text-right">56.019</td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right font-weight-bold">
                    Cakupan Pelayanan Administratif : 7,11%
                </div>
            </div>

        </div>
    </div>
</section>
</div>