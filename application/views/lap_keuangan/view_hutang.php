<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('lap_keuangan/hutang'); ?>" method="get">
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
                        <a href="<?= base_url('lap_keuangan/hutang') ?>" style="text-decoration: none;"><button class="neumorphic-button ms-2"> Tahun ini</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <select id="jenis_transaksi" class="form-control" onchange="redirectToPage()">
                            <option value="">Pilih Jenis Input :</option>
                            <option value="<?= base_url('lap_keuangan/hutang/input_hutang_usaha') ?>">Hutang Usaha</option>
                            <option value="<?= base_url('lap_keuangan/hutang/input_hutang_non_usaha') ?>">Hutang Non Usaha</option>
                            <option value="<?= base_url('lap_keuangan/hutang/input_pdd') ?>">Penerimaaan Diterima Dimuka</option>
                            <option value="<?= base_url('lap_keuangan/hutang/input_utsr') ?>">Uang Titipan SR</option>
                            <option value="<?= base_url('lap_keuangan/hutang/input_hnu_lain') ?>">Hutang Non Usaha Lainnya</option>
                            <option value="<?= base_url('lap_keuangan/hutang/input_bymhd') ?>">Beban Yang Masih Harus Dibayar</option>
                            <option value="<?= base_url('lap_keuangan/hutang/input_up') ?>">Utang Pajak</option>
                            <option value="<?= base_url('lap_keuangan/hutang/input_lipkd') ?>">Liabilitas Imbalan Pasca Kerja Dapenma</option>
                            <option value="<?= base_url('lap_keuangan/hutang/input_lipk') ?>">Liabilitas Imbalan Pasca Kerja</option>
                            <option value="<?= base_url('lap_keuangan/hutang/input_ujpl') ?>">Utang Jangka Pendek Lainnya</option>
                            <option value="<?= base_url('lap_keuangan/hutang/input_lipkdpj') ?>">Liabilitas Imbalan Pasca Kerja Dapenma(pj)</option>
                            <option value="<?= base_url('lap_keuangan/hutang/input_lipkpj') ?>">Liabilitas Imbalan Pasca Kerja(pj)</option>
                            <option value="<?= base_url('lap_keuangan/hutang/input_lpt') ?>">Liabilitas pajak Tangguhan</option>
                            <option value="<?= base_url('lap_keuangan/hutang/input_kll') ?>">Kewajiban Lain-lain</option>
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
                            $total_hnu_tahun_ini = 0;
                            $total_hnu_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($hnu_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($hnu_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_hnu; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_hnu_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_hnu_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_hnu_tahun_ini += $row->jumlah_hnu_tahun_ini;
                                    $total_hnu_tahun_lalu += $row->jumlah_hnu_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Hutang Non Usaha</th>
                                <th class="text-right">
                                    <?= number_format($total_hnu_tahun_ini, 0, ',', '.'); ?>
                                </th>
                                <!-- <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/asset_tetap/input_hnu_neraca/' . $tahun_lap . '/' . $total_hnu_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_hnu_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th> -->
                                <th class="text-right"><?= number_format($total_hnu_tahun_lalu, 0, ',', '.'); ?></th>
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
                            $total_pdd_tahun_ini = 0;
                            $total_pdd_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($pdd_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($pdd_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_pdd; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_pdd_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_pdd_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_pdd_tahun_ini += $row->jumlah_pdd_tahun_ini;
                                    $total_pdd_tahun_lalu += $row->jumlah_pdd_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Penerimaaan Diterima Dimuka</th>
                                <th class="text-right">
                                    <?= number_format($total_pdd_tahun_ini, 0, ',', '.'); ?>
                                </th>
                                <th class="text-right"><?= number_format($total_pdd_tahun_lalu, 0, ',', '.'); ?></th>
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
                            $total_utsr_tahun_ini = 0;
                            $total_utsr_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($utsr_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($utsr_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_utsr; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_utsr_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_utsr_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_utsr_tahun_ini += $row->jumlah_utsr_tahun_ini;
                                    $total_utsr_tahun_lalu += $row->jumlah_utsr_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Uang Titipan SR</th>
                                <th class="text-right">
                                    <?= number_format($total_utsr_tahun_ini, 0, ',', '.'); ?>
                                </th>
                                <th class="text-right"><?= number_format($total_utsr_tahun_lalu, 0, ',', '.'); ?></th>
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
                            $total_hnu_lain_tahun_ini = 0;
                            $total_hnu_lain_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($hnu_lain_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($hnu_lain_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_hnu_lain; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_hnu_lain_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_hnu_lain_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_hnu_lain_tahun_ini += $row->jumlah_hnu_lain_tahun_ini;
                                    $total_hnu_lain_tahun_lalu += $row->jumlah_hnu_lain_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Hutang Non Usaha Lainnya</th>
                                <th class="text-right">
                                    <?= number_format($total_hnu_lain_tahun_ini, 0, ',', '.'); ?>
                                </th>
                                <th class="text-right"><?= number_format($total_hnu_lain_tahun_lalu, 0, ',', '.'); ?></th>
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
                                $total_seluruh_hnu_tahun_ini = $total_hnu_lain_tahun_ini + $total_hnu_tahun_ini + $total_pdd_tahun_ini + $total_utsr_tahun_ini;
                                $total_seluruh_hnu_tahun_lalu = $total_hnu_lain_tahun_lalu + $total_hnu_tahun_lalu + $total_pdd_tahun_lalu + $total_utsr_tahun_lalu;

                                ?>
                                <tr>
                                    <th class="text-left">Total Keseluruhan Hutang Non Usaha </th>
                                    <th class="text-right">
                                        <a href="<?= base_url('lap_keuangan/hutang/input_hnu_neraca/' . $tahun_lap . '/' . $total_seluruh_hnu_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                            <?= number_format($total_seluruh_hnu_tahun_ini, 0, ',', '.'); ?>
                                        </a>
                                    </th>
                                    <th class="text-right"><?= number_format($total_seluruh_hnu_tahun_lalu, 0, ',', '.'); ?></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
                            $total_bymhd_tahun_ini = 0;
                            $total_bymhd_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($bymhd_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($bymhd_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_bymhd; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bymhd_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bymhd_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_bymhd_tahun_ini += $row->jumlah_bymhd_tahun_ini;
                                    $total_bymhd_tahun_lalu += $row->jumlah_bymhd_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Beban Yang Masih Harus Dibayar</th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/hutang/input_bymhd_neraca/' . $tahun_lap . '/' . $total_bymhd_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_bymhd_tahun_ini, 0, ',', '.'); ?>
                                    </a>

                                </th>
                                <th class="text-right"><?= number_format($total_bymhd_tahun_lalu, 0, ',', '.'); ?></th>
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
                            $total_up_tahun_ini = 0;
                            $total_up_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($up_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($up_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_up; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_up_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_up_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_up_tahun_ini += $row->jumlah_up_tahun_ini;
                                    $total_up_tahun_lalu += $row->jumlah_up_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Beban Yang Masih Harus Dibayar</th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/hutang/input_up_neraca/' . $tahun_lap . '/' . $total_up_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_up_tahun_ini, 0, ',', '.'); ?>
                                    </a>

                                </th>
                                <th class="text-right"><?= number_format($total_up_tahun_lalu, 0, ',', '.'); ?></th>
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
                        <h5><?= strtoupper($title7) . ' ' . $tahun_lap; ?></h5>
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
                            $total_kll_tahun_ini = 0;
                            $total_kll_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($kll_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($kll_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_kll; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_kll_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_kll_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_kll_tahun_ini += $row->jumlah_kll_tahun_ini;
                                    $total_kll_tahun_lalu += $row->jumlah_kll_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Kewajiban Lain-lain</th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/hutang/input_kll_neraca/' . $tahun_lap . '/' . $total_kll_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_kll_tahun_ini, 0, ',', '.'); ?>
                                    </a>

                                </th>
                                <th class="text-right"><?= number_format($total_kll_tahun_lalu, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</section>
</div>