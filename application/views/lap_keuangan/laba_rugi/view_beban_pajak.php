<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('lap_keuangan/beban_pajak'); ?>" method="get">
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
                        <a href="<?= base_url('lap_keuangan/beban_pajak') ?>" style="text-decoration: none;"><button class="neumorphic-button ms-2"> Tahun ini</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <select id="jenis_transaksi" class="form-control" onchange="redirectToPage()">
                            <option value="">Pilih Jenis Input :</option>
                            <option value="<?= base_url('lap_keuangan/beban_pajak/input_bpk') ?>">Pajak Kini</option>
                            <option value="<?= base_url('lap_keuangan/beban_pajak/input_bpd') ?>">Beban Pajak Ditangguhkan</option>
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
                            $total_bpk_tahun_ini = 0;
                            $total_bpk_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($bpk_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($bpk_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_bpk; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bpk_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bpk_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_bpk_tahun_ini += $row->jumlah_bpk_tahun_ini;
                                    $total_bpk_tahun_lalu += $row->jumlah_bpk_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">-</th>
                                <th class="text-right">
                                    <?= number_format($total_bpk_tahun_ini, 0, ',', '.'); ?>
                                </th>
                                <th class="text-right"><?= number_format($total_bpk_tahun_lalu, 0, ',', '.'); ?></th>
                            </tr>

                            <?php
                            $total_labarugi_belum_pajak_tahun_ini = 0;
                            $total_labarugi_belum_pajak_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($bpk_kurang_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($bpk_kurang_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_bpk; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bpk_kurang_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bpk_kurang_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_labarugi_belum_pajak_tahun_ini = $total_bpk_tahun_ini - $row->jumlah_bpk_kurang_tahun_ini;
                                    $total_labarugi_belum_pajak_tahun_lalu = $total_bpk_tahun_lalu - $row->jumlah_bpk_kurang_tahun_lalu;
                                    ?>
                                    <tr>
                                        <th class="text-left">Laba / Rugi Setelah Koreksi Fiskal</th>
                                        <th class="text-right">
                                            <?= number_format($total_labarugi_belum_pajak_tahun_ini, 0, ',', '.'); ?>
                                        </th>
                                        <th class="text-right"><?= number_format($total_labarugi_belum_pajak_tahun_lalu, 0, ',', '.'); ?></th>
                                    </tr>
                                    <tr>
                                        <th colspan="3">Dasar Pengenaan Pajak</th>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <?php
                                $total_pendapatan_usaha_tahun_ini = 0;
                                $total_pendapatan_usaha_tahun_lalu = 0;
                                ?>
                                <?php if (!empty($pendapatan_usaha)) : ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($pendapatan_usaha as $row) : ?>
                                        <?php
                                        $total_pendapatan_usaha_tahun_ini += $row->jumlah_pendapatan_usaha_tahun_ini;
                                        $total_pendapatan_usaha_tahun_lalu += $row->jumlah_pendapatan_usaha_tahun_lalu;
                                        ?>
                                    <?php endforeach; ?>
                                <?php else : ?>
                            <tr>
                                <td colspan="8" class="text-center">Data tidak tersedia</td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td class="text-left">Pendapatan Usaha</td>
                            <td class="text-right">
                                <?= number_format($total_pendapatan_usaha_tahun_ini, 0, ',', '.'); ?>
                            </td>
                            <td class="text-right"><?= number_format($total_pendapatan_usaha_tahun_lalu, 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <th colspan="3">Tarif Pajak</th>
                        </tr>
                        <tr>
                            <?php
                            $tarif_pajak_tahun_ini = 0;
                            $tarif_pajak_tahun_lalu = 0;
                            $dasar_pajak = 4800000000;

                            // Cek agar tidak terjadi pembagian dengan nol
                            if ($total_pendapatan_usaha_tahun_ini > 0) {
                                $tarif_pajak_tahun_ini = floor($dasar_pajak / $total_pendapatan_usaha_tahun_ini * $total_labarugi_belum_pajak_tahun_ini);
                            }

                            if ($total_pendapatan_usaha_tahun_lalu > 0) {
                                $tarif_pajak_tahun_lalu = floor($dasar_pajak / $total_pendapatan_usaha_tahun_lalu * $total_labarugi_belum_pajak_tahun_lalu);
                            }
                            ?>
                            <td class="text-left">
                                4.800.000.000 : <?= number_format($total_pendapatan_usaha_tahun_ini, 0, ',', '.') . ' X '; ?>
                                <?= number_format($total_labarugi_belum_pajak_tahun_ini, 0, ',', '.'); ?>
                            </td>
                            <td class="text-right">
                                <?= number_format($tarif_pajak_tahun_ini, 0, ',', '.'); ?>
                            </td>
                            <td class="text-right">
                                <?= number_format($tarif_pajak_tahun_lalu, 0, ',', '.'); ?>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="3">1. Tarif 50% X 22% </th>
                        </tr>
                        <tr>
                            <?php
                            $tarif_1_tahun_ini = 0;
                            $tarif_1_tahun_lalu = 0;
                            $persen_1 = 0.5;
                            $persen_2 = 0.22;

                            // Cek agar tidak terjadi pembagian dengan nol
                            if ($tarif_pajak_tahun_ini > 0) {
                                $tarif_1_tahun_ini = $persen_1 * $persen_2 * $tarif_pajak_tahun_ini;
                            }

                            if ($tarif_pajak_tahun_lalu > 0) {
                                $tarif_1_tahun_lalu = $persen_1 * $persen_2 * $tarif_pajak_tahun_lalu;
                            }
                            ?>
                            <td class="text-left">
                                50 % X 22% X
                                <?= number_format($tarif_pajak_tahun_ini, 0, ',', '.'); ?>
                            </td>
                            <td class="text-right">
                                <?= number_format($tarif_1_tahun_ini, 0, ',', '.'); ?>
                            </td>
                            <td class="text-right">
                                <?= number_format($tarif_1_tahun_lalu, 0, ',', '.'); ?>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="3">2. Tarif 22% </th>
                        </tr>
                        <tr>
                            <?php
                            $tarif_2_tahun_ini = 0;
                            $tarif_2_tahun_lalu = 0;
                            $persen_2 = 0.22;

                            // Cek agar tidak terjadi pembagian dengan nol
                            if ($total_labarugi_belum_pajak_tahun_ini > 0) {
                                $tarif_2_tahun_ini = $persen_2 * ($total_labarugi_belum_pajak_tahun_ini - $tarif_pajak_tahun_ini);
                            }

                            if ($total_labarugi_belum_pajak_tahun_lalu > 0) {
                                $tarif_2_tahun_lalu = $persen_2 * ($total_labarugi_belum_pajak_tahun_lalu - $tarif_pajak_tahun_lalu);
                            }
                            ?>
                            <td class="text-left">
                                22% X
                                <?= number_format($total_labarugi_belum_pajak_tahun_ini, 0, ',', '.'); ?> -
                                <?= number_format($tarif_pajak_tahun_ini, 0, ',', '.'); ?>
                            </td>
                            <td class="text-right">
                                <?= number_format($tarif_2_tahun_ini, 0, ',', '.'); ?>
                            </td>
                            <td class="text-right">
                                <?= number_format($tarif_2_tahun_lalu, 0, ',', '.'); ?>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="3">Pajak Penghasilan Badan Terhutang</th>
                        </tr>
                        <tr>
                            <?php
                            $ppbt_tahun_ini = 0;
                            $ppbt_tahun_lalu = 0;

                            // Cek agar tidak terjadi pembagian dengan nol
                            if ($tarif_1_tahun_ini > 0) {
                                $ppbt_tahun_ini = $tarif_1_tahun_ini + $tarif_2_tahun_ini;
                            }

                            if ($tarif_1_tahun_lalu > 0) {
                                $ppbt_tahun_lalu = $tarif_1_tahun_lalu + $tarif_2_tahun_lalu;
                            }
                            ?>
                            <td class="text-left">
                                <?= number_format($tarif_1_tahun_ini, 0, ',', '.'); ?> +
                                <?= number_format($tarif_2_tahun_ini, 0, ',', '.'); ?>
                            </td>
                            <td class="text-right">
                                <?= number_format($ppbt_tahun_ini, 0, ',', '.'); ?>
                            </td>
                            <td class="text-right">
                                <?= number_format($ppbt_tahun_lalu, 0, ',', '.'); ?>
                            </td>
                        </tr>
                        <tr>
                            <?php
                            $ppbt_bulat_tahun_ini = 0;
                            $ppbt_bulat_tahun_lalu = 0;

                            // Cek agar tidak terjadi pembagian dengan nol
                            if ($tarif_1_tahun_ini > 0) {
                                $ppbt_bulat_tahun_ini = floor($ppbt_tahun_ini / 100) * 100;
                            }

                            if ($tarif_1_tahun_lalu > 0) {
                                $ppbt_bulat_tahun_lalu = floor($ppbt_tahun_lalu / 100) * 100;
                            }
                            ?>
                            <th class="text-left">
                                Pajak Penghasilan Badan Terhutang Dibulatkan
                            </th>
                            <th class="text-right">
                                <?= number_format($ppbt_bulat_tahun_ini, 0, ',', '.'); ?>
                            </th>
                            <th class="text-right">
                                <?= number_format($ppbt_bulat_tahun_lalu, 0, ',', '.'); ?>
                            </th>
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
                            $total_bpd_tahun_ini = 0;
                            $total_bpd_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($bpd_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($bpd_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_bpd; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bpd_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bpd_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_bpd_tahun_ini += $row->jumlah_bpd_tahun_ini;
                                    $total_bpd_tahun_lalu += $row->jumlah_bpd_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Beban Pajak Ditangguhkan</th>
                                <!-- <th class="text-right">
                                    <?= number_format($total_bpd_tahun_ini, 0, ',', '.'); ?>
                                </th> -->
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/beban_pajak/input_bpd_neraca/' . $tahun_lap . '/' . $total_bpd_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_bpd_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right"><?= number_format($total_bpd_tahun_lalu, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- <div class="table-responsive">
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
                            $total_seluruh_beban_pajak_tahun_ini = $total_bpk_tahun_ini + $total_bpd_tahun_ini;
                            $total_seluruh_beban_pajak_tahun_lalu = $total_bpk_tahun_lalu + $total_bpd_tahun_lalu;

                            ?>
                            <tr>
                                <th class="text-left">Total Keseluruhan Beban Pajak</th>
                                <th class="text-right"><?= number_format($total_seluruh_beban_pajak_tahun_ini, 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_seluruh_beban_pajak_tahun_lalu, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div> -->
            </div>
        </div>
</section>
</div>