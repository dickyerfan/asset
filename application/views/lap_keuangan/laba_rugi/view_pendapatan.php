<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('lap_keuangan/pendapatan'); ?>" method="get">
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
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('lap_keuangan/pendapatan') ?>" style="text-decoration: none;"><button class="neumorphic-button ms-2"> Tahun ini</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <select id="jenis_transaksi" class="form-control" onchange="redirectToPage()">
                            <option value="">Pilih Jenis Input :</option>
                            <option value="<?= base_url('lap_keuangan/pendapatan/input_ppa') ?>">Pendapatan Penjualan Air</option>
                            <option value="<?= base_url('lap_keuangan/pendapatan/input_ppna') ?>">Pendapatan Penjualan Non Air</option>
                            <option value="<?= base_url('lap_keuangan/pendapatan/input_pk') ?>">Pendapatan Kemitraan</option>
                            <option value="<?= base_url('lap_keuangan/pendapatan/input_pll') ?>">Pendapatan Lain-lain</option>
                        </select>
                    </div>
                </nav>
            </div>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">

                        <?php
                        if (empty($tahun_lap)) {
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
                                <th>Uraian / Keterangan</th>
                                <th>Tahun <?= $tahun_lap ?></th>
                                <th>Tahun <?= $tahun_lalu ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_ppa_tahun_ini = 0;
                            $total_ppa_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($ppa_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($ppa_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_ppa; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_ppa_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_ppa_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_ppa_tahun_ini += $row->jumlah_ppa_tahun_ini;
                                    $total_ppa_tahun_lalu += $row->jumlah_ppa_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Pendapatan Penjualan Air</th>
                                <!-- <th class="text-right">
                                    <?= number_format($total_ppa_tahun_ini, 0, ',', '.'); ?>
                                </th> -->
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/pendapatan/input_ppa_neraca/' . $tahun_lap . '/' . $total_ppa_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_ppa_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right"><?= number_format($total_ppa_tahun_lalu, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">

                        <?php
                        if (empty($tahun_lap)) {
                            $tahun_lap = date('Y');
                        }
                        ?>
                        <h5><?= strtoupper($title2) . ' ' . $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>Uraian / Keterangan</th>
                                <th>Tahun <?= $tahun_lap ?></th>
                                <th>Tahun <?= $tahun_lalu ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_ppna_tahun_ini = 0;
                            $total_ppna_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($ppna_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($ppna_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_ppna; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_ppna_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_ppna_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_ppna_tahun_ini += $row->jumlah_ppna_tahun_ini;
                                    $total_ppna_tahun_lalu += $row->jumlah_ppna_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Pendapatan Penjualan Non Air</th>
                                <!-- <th class="text-right">
                                    <?= number_format($total_ppna_tahun_ini, 0, ',', '.'); ?>
                                </th> -->
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/pendapatan/input_ppna_neraca/' . $tahun_lap . '/' . $total_ppna_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_ppna_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right"><?= number_format($total_ppna_tahun_lalu, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">

                        <?php
                        if (empty($tahun_lap)) {
                            $tahun_lap = date('Y');
                        }
                        ?>
                        <h5><?= strtoupper($title3) . ' ' . $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>Uraian / Keterangan</th>
                                <th>Tahun <?= $tahun_lap ?></th>
                                <th>Tahun <?= $tahun_lalu ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_pk_tahun_ini = 0;
                            $total_pk_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($pk_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($pk_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_pk; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_pk_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_pk_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_pk_tahun_ini += $row->jumlah_pk_tahun_ini;
                                    $total_pk_tahun_lalu += $row->jumlah_pk_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Pendapatan Kemitraan</th>
                                <!-- <th class="text-right">
                                    <?= number_format($total_pk_tahun_ini, 0, ',', '.'); ?>
                                </th> -->
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/pendapatan/input_pk_neraca/' . $tahun_lap . '/' . $total_pk_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_pk_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right"><?= number_format($total_pk_tahun_lalu, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">

                        <?php
                        if (empty($tahun_lap)) {
                            $tahun_lap = date('Y');
                        }
                        ?>
                        <h5><?= strtoupper($title4) . ' ' . $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>Uraian / Keterangan</th>
                                <th>Tahun <?= $tahun_lap ?></th>
                                <th>Tahun <?= $tahun_lalu ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_pll_tahun_ini = 0;
                            $total_pll_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($pll_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($pll_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_pll; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_pll_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_pll_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_pll_tahun_ini += $row->jumlah_pll_tahun_ini;
                                    $total_pll_tahun_lalu += $row->jumlah_pll_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Pendapatan Lain-lain</th>
                                <!-- <th class="text-right">
                                    <?= number_format($total_pll_tahun_ini, 0, ',', '.'); ?>
                                </th> -->
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/pendapatan/input_pll_neraca/' . $tahun_lap . '/' . $total_pll_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_pll_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right"><?= number_format($total_pll_tahun_lalu, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                    </table>
                    <div class="table-responsive">
                        <table id="contoh2" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th></th>
                                    <th>Tahun <?= $tahun_lap ?></th>
                                    <th>Tahun <?= $tahun_lalu ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_seluruh_pendapatan_tahun_ini = $total_ppna_tahun_ini + $total_ppa_tahun_ini + $total_pk_tahun_ini + $total_pll_tahun_ini;
                                $total_seluruh_pendapatan_tahun_lalu = $total_ppna_tahun_lalu + $total_ppa_tahun_lalu + $total_pk_tahun_lalu + $total_pll_tahun_lalu;

                                ?>
                                <tr>
                                    <th class="text-left">Total Pendapatan </th>
                                    <th class="text-right"><?= number_format($total_seluruh_pendapatan_tahun_ini, 0, ',', '.'); ?></th>
                                    <th class="text-right"><?= number_format($total_seluruh_pendapatan_tahun_lalu, 0, ',', '.'); ?></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</section>
</div>