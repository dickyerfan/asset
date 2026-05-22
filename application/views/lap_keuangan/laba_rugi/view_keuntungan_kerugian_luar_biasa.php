<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('lap_keuangan/keuntungan_kerugian_luar_biasa'); ?>" method="get">
                        <div style="display: flex; align-items: center;">
                            <input type="submit" value="Pilih Tahun" class="neumorphic-button">
                            <select id="tahun" name="tahun" class="form-control" style="margin-left: 15px;">
                                <?php
                                $currentYear = date('Y');
                                $selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear;
                                for ($year = 1989; $year <= $currentYear; $year++) {
                                    $selected = ($year == $selectedYear) ? 'selected' : '';
                                    echo "<option value='$year' $selected>$year</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </form>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('lap_keuangan/keuntungan_kerugian_luar_biasa') ?>" style="text-decoration: none;"><button class="neumorphic-button ms-2"> Tahun ini</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('lap_keuangan/keuntungan_kerugian_luar_biasa/input_klb') ?>"><button class="neumorphic-button"><i class="fas fa-upload"></i> Upload Data</button></a>
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
                            $total_klb_tahun_ini = 0;
                            $total_klb_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($klb_input)) : ?>
                                <?php foreach ($klb_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->akun; ?></td>
                                        <td class="text-right">
                                            <?= number_format($row->jumlah_klb_tahun_ini, 0, ',', '.'); ?>
                                        </td>
                                        <td class="text-right">
                                            <?= number_format($row->jumlah_klb_tahun_lalu, 0, ',', '.'); ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $total_klb_tahun_ini += $row->jumlah_klb_tahun_ini;
                                    $total_klb_tahun_lalu += $row->jumlah_klb_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Keuntungan (Kerugian) Luar Biasa</th>
                                <!-- <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/keuntungan_kerugian_luar_biasa/input_klb_lr/' . $tahun_lap . '/' . $total_klb_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Laba Rugi?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_klb_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th> -->
                                <th class="text-right">
                                    <?= number_format($total_klb_tahun_ini, 0, ',', '.'); ?>
                                </th>
                                <th class="text-right"><?= number_format($total_klb_tahun_lalu, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>