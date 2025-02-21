<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('lap_keuangan/ekuitas'); ?>" method="get">
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
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('lap_keuangan/ekuitas') ?>" style="text-decoration: none;"><button class="neumorphic-button ms-2"> Tahun ini</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <select id="jenis_transaksi" class="form-control" onchange="redirectToPage()">
                            <option value="">Pilih Jenis Input :</option>
                            <option value="<?= base_url('lap_keuangan/ekuitas/input_ppyd') ?>">Penyertaan Pemda Yang Dipisahkan</option>
                            <option value="<?= base_url('lap_keuangan/ekuitas/input_ppybds') ?>">Penyertaan Pemerintah Yang Belum Ditetapkan Status</option>
                            <option value="<?= base_url('lap_keuangan/ekuitas/input_mh') ?>">Modal Hibah</option>
                            <option value="<?= base_url('lap_keuangan/ekuitas/input_cu') ?>">Cadangan Umum</option>
                            <option value="<?= base_url('lap_keuangan/ekuitas/input_cb') ?>">Cadangan Bertujuan</option>
                            <option value="<?= base_url('lap_keuangan/ekuitas/input_pkipk') ?>">Pengukuran Kembali Imbalan Paska Kerja</option>
                            <option value="<?= base_url('lap_keuangan/ekuitas/input_aktl') ?>">Akm Kerugian Tahun Lalu</option>
                            <option value="<?= base_url('lap_keuangan/ekuitas/input_lrtb') ?>">Laba Rugi Tahun Berjalan</option>
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
                        <h5><?= strtoupper($title3) . ' ' . $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>Uraian</th>
                                <th><?= $tahun_lap ?></th>
                                <th><?= $tahun_lalu ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_mh_tahun_ini = 0;
                            $total_mh_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($mh_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($mh_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_mh; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_mh_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_mh_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_mh_tahun_ini += $row->jumlah_mh_tahun_ini;
                                    $total_mh_tahun_lalu += $row->jumlah_mh_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Modal Hibah</th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/ekuitas/input_mh_neraca/' . $tahun_lap . '/' . $total_mh_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_mh_tahun_ini, 0, ',', '.'); ?>
                                    </a>

                                </th>
                                <th class="text-right"><?= number_format($total_mh_tahun_lalu, 0, ',', '.'); ?></th>
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
                                <th>Uraian</th>
                                <th><?= $tahun_lap ?></th>
                                <th><?= $tahun_lalu ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_cu_tahun_ini = 0;
                            $total_cu_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($cu_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($cu_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_cu; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_cu_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_cu_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_cu_tahun_ini += $row->jumlah_cu_tahun_ini;
                                    $total_cu_tahun_lalu += $row->jumlah_cu_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Modal Hibah</th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/ekuitas/input_cu_neraca/' . $tahun_lap . '/' . $total_cu_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_cu_tahun_ini, 0, ',', '.'); ?>
                                    </a>

                                </th>
                                <th class="text-right"><?= number_format($total_cu_tahun_lalu, 0, ',', '.'); ?></th>
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
                                <th>Uraian</th>
                                <th><?= $tahun_lap ?></th>
                                <th><?= $tahun_lalu ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_aktl_tahun_ini = 0;
                            $total_aktl_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($aktl_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($aktl_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_aktl; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_aktl_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_aktl_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    $total_aktl_tahun_ini += $row->jumlah_aktl_tahun_ini;
                                    $total_aktl_tahun_lalu += $row->jumlah_aktl_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Akm Kerugian Tahun Lalu</th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/ekuitas/input_aktl_neraca/' . $tahun_lap . '/' . $total_aktl_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_aktl_tahun_ini, 0, ',', '.'); ?>
                                    </a>

                                </th>
                                <th class="text-right"><?= number_format($total_aktl_tahun_lalu, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>