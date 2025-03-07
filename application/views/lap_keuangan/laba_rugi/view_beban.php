<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('lap_keuangan/beban'); ?>" method="get">
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
                        <a href="<?= base_url('lap_keuangan/beban') ?>" style="text-decoration: none;"><button class="neumorphic-button ms-2"> Tahun ini</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <select id="jenis_transaksi" class="form-control" onchange="redirectToPage()">
                            <option value="">Pilih Jenis Input :</option>
                            <option value="<?= base_url('lap_keuangan/beban/input_bop') ?>">Beban Operasi</option>
                            <option value="<?= base_url('lap_keuangan/beban/input_bpa') ?>">Beban Pengolahan Air</option>
                            <option value="<?= base_url('lap_keuangan/beban/input_btd') ?>">Beban Transmisi Dan Distribusi</option>
                            <option value="<?= base_url('lap_keuangan/beban/input_bsb') ?>">Beban (HPP) Sambungan Baru</option>
                            <option value="<?= base_url('lap_keuangan/beban/input_bua') ?>">Beban Umum Dan Adminstrasi</option>
                            <option value="<?= base_url('lap_keuangan/beban/input_bll') ?>">Beban Lain-lain</option>
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
                            $total_bop_tahun_ini = 0;
                            $total_bop_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($bop_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($bop_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_bop; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bop_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bop_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_bop_tahun_ini += $row->jumlah_bop_tahun_ini;
                                    $total_bop_tahun_lalu += $row->jumlah_bop_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Beban Operasi</th>
                                <!-- <th class="text-right">
                                    <?= number_format($total_bop_tahun_ini, 0, ',', '.'); ?>
                                </th> -->
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/beban/input_bop_neraca/' . $tahun_lap . '/' . $total_bop_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_bop_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right"><?= number_format($total_bop_tahun_lalu, 0, ',', '.'); ?></th>
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
                            $total_bpa_tahun_ini = 0;
                            $total_bpa_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($bpa_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($bpa_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_bpa; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bpa_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bpa_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_bpa_tahun_ini += $row->jumlah_bpa_tahun_ini;
                                    $total_bpa_tahun_lalu += $row->jumlah_bpa_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Beban Pengolahan Air</th>
                                <!-- <th class="text-right">
                                    <?= number_format($total_bpa_tahun_ini, 0, ',', '.'); ?>
                                </th> -->
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/beban/input_bpa_neraca/' . $tahun_lap . '/' . $total_bpa_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_bpa_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right"><?= number_format($total_bpa_tahun_lalu, 0, ',', '.'); ?></th>
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
                            $total_btd_tahun_ini = 0;
                            $total_btd_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($btd_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($btd_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_btd; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_btd_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_btd_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_btd_tahun_ini += $row->jumlah_btd_tahun_ini;
                                    $total_btd_tahun_lalu += $row->jumlah_btd_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Beban Transmisi Dan Distribusi</th>
                                <!-- <th class="text-right">
                                    <?= number_format($total_btd_tahun_ini, 0, ',', '.'); ?>
                                </th> -->
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/beban/input_btd_neraca/' . $tahun_lap . '/' . $total_btd_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_btd_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right"><?= number_format($total_btd_tahun_lalu, 0, ',', '.'); ?></th>
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
                            $total_bsb_tahun_ini = 0;
                            $total_bsb_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($bsb_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($bsb_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_bsb; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bsb_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bsb_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_bsb_tahun_ini += $row->jumlah_bsb_tahun_ini;
                                    $total_bsb_tahun_lalu += $row->jumlah_bsb_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Beban (HPP) Sambungan Baru</th>
                                <!-- <th class="text-right">
                                    <?= number_format($total_bsb_tahun_ini, 0, ',', '.'); ?>
                                </th> -->
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/beban/input_bsb_neraca/' . $tahun_lap . '/' . $total_bsb_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_bsb_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right"><?= number_format($total_bsb_tahun_lalu, 0, ',', '.'); ?></th>
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
                        <h5><?= strtoupper($title5) . ' ' . $tahun_lap; ?></h5>
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
                            $total_bua_tahun_ini = 0;
                            $total_bua_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($bua_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($bua_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_bua; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bua_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bua_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_bua_tahun_ini += $row->jumlah_bua_tahun_ini;
                                    $total_bua_tahun_lalu += $row->jumlah_bua_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Beban Umum Dan Adminstrasi</th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/beban/input_bua_neraca/' . $tahun_lap . '/' . $total_bua_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_bua_tahun_ini, 0, ',', '.'); ?>
                                    </a>

                                </th>
                                <th class="text-right"><?= number_format($total_bua_tahun_lalu, 0, ',', '.'); ?></th>
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
                        <h5><?= strtoupper($title6) . ' ' . $tahun_lap; ?></h5>
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
                            $total_bll_tahun_ini = 0;
                            $total_bll_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($bll_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($bll_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_bll; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bll_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bll_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_bll_tahun_ini += $row->jumlah_bll_tahun_ini;
                                    $total_bll_tahun_lalu += $row->jumlah_bll_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Beban Lain-lain</th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/beban/input_bll_neraca/' . $tahun_lap . '/' . $total_bll_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_bll_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right"><?= number_format($total_bll_tahun_lalu, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
                            $total_beban_usaha_tahun_ini = $total_bpa_tahun_ini + $total_bop_tahun_ini + $total_btd_tahun_ini + $total_bua_tahun_ini;
                            $total_beban_usaha_tahun_lalu = $total_bpa_tahun_lalu + $total_bop_tahun_lalu + $total_btd_tahun_lalu + $total_bua_tahun_lalu;

                            ?>
                            <tr>
                                <th class="text-left">Total Beban Usaha untuk data Evkin </th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/beban/input_bu_evkin/' . $tahun_lap . '/' . $total_beban_usaha_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_beban_usaha_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right"><?= number_format($total_beban_usaha_tahun_lalu, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</section>
</div>