<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('lap_keuangan/penghasilan_komp_lain'); ?>" method="get">
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
                        <a href="<?= base_url('lap_keuangan/penghasilan_komp_lain') ?>" style="text-decoration: none;"><button class="neumorphic-button ms-2"> Tahun ini</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <select id="jenis_transaksi" class="form-control" onchange="redirectToPage()">
                            <option value="">Pilih Jenis Input :</option>
                            <option value="<?= base_url('lap_keuangan/penghasilan_komp_lain/input_srt') ?>">Surplus Revaluasi Tanah</option>
                            <option value="<?= base_url('lap_keuangan/penghasilan_komp_lain/input_pkapip') ?>">Pengukuran Kembali Atas Program Imbalan Pasti</option>
                            <option value="<?= base_url('lap_keuangan/penghasilan_komp_lain/input_bppt') ?>">Beban Pajak Penghasilan Terkait</option>
                            <option value="<?= base_url('lap_keuangan/penghasilan_komp_lain/input_pkltb') ?>">Penghasilan Komprehensif Lain Tahun Berjalan</option>
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
                            $total_srt_tahun_ini = 0;
                            $total_srt_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($srt_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($srt_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_srt; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_srt_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_srt_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_srt_tahun_ini += $row->jumlah_srt_tahun_ini;
                                    $total_srt_tahun_lalu += $row->jumlah_srt_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Surplus Revaluasi Tanah/Aset Tidak Lancar</th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/penghasilan_komp_lain/input_srt_lr/' . $tahun_lap . '/' . $total_srt_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Lap Rugi Laba?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_srt_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right"><?= number_format($total_srt_tahun_lalu, 0, ',', '.'); ?></th>
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
                            $total_pkapip_tahun_ini = 0;
                            $total_pkapip_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($pkapip_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($pkapip_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_pkapip; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_pkapip_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_pkapip_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_pkapip_tahun_ini += $row->jumlah_pkapip_tahun_ini;
                                    $total_pkapip_tahun_lalu += $row->jumlah_pkapip_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Pengukuran Kembali Atas Program Imbalan Pasti</th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/penghasilan_komp_lain/input_pkapip_lr/' . $tahun_lap . '/' . $total_pkapip_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Lap Rugi Laba?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_pkapip_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right"><?= number_format($total_pkapip_tahun_lalu, 0, ',', '.'); ?></th>
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
                            $total_bppt_tahun_ini = 0;
                            $total_bppt_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($bppt_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($bppt_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_bppt; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bppt_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bppt_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_bppt_tahun_ini += $row->jumlah_bppt_tahun_ini;
                                    $total_bppt_tahun_lalu += $row->jumlah_bppt_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Beban Pajak Penghasilan Terkait</th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/penghasilan_komp_lain/input_bppt_lr/' . $tahun_lap . '/' . $total_bppt_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Lap Rugi Laba?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_bppt_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right"><?= number_format($total_bppt_tahun_lalu, 0, ',', '.'); ?></th>
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
                            $total_pkltb_tahun_ini = 0;
                            $total_pkltb_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($pkltb_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($pkltb_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_pkltb; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_pkltb_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_pkltb_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_pkltb_tahun_ini += $row->jumlah_pkltb_tahun_ini;
                                    $total_pkltb_tahun_lalu += $row->jumlah_pkltb_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Penghasilan Komprehensif Lain Tahun Berjalan</th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/penghasilan_komp_lain/input_pkltb_lr/' . $tahun_lap . '/' . $total_pkltb_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Lap Rugi Laba?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_pkltb_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right"><?= number_format($total_pkltb_tahun_lalu, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</section>
</div>