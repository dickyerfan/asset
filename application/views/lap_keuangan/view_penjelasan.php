<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('lap_keuangan/penjelasan'); ?>" method="get">
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
                        <a href="<?= base_url('lap_keuangan/penjelasan') ?>" style="text-decoration: none;"><button class="neumorphic-button ms-2"> Tahun ini</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <select id="jenis_transaksi" class="form-control" onchange="redirectToPage()">
                            <option value="">Pilih Jenis Input :</option>
                            <option value="<?= base_url('lap_keuangan/penjelasan/input_bank') ?>">Bank</option>
                            <option value="<?= base_url('lap_keuangan/penjelasan/input_kas') ?>">Kas</option>
                            <option value="<?= base_url('lap_keuangan/penjelasan/input_deposito') ?>">Deposito</option>
                            <option value="<?= base_url('lap_keuangan/penjelasan/input_pend_blm_terima') ?>">Pendapatan Blm Terima</option>
                            <option value="<?= base_url('lap_keuangan/penjelasan/input_pembayaran_dimuka') ?>">Pembayaran Dimuka</option>
                            <option value="<?= base_url('lap_keuangan/penjelasan/input_pajak_pnd') ?>">Pajak Pertambahan Nilai Dimuka</option>
                        </select>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-12 text-center">
                        <h5><strong><?= strtoupper($title); ?></strong></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <!-- <th>No</th> -->
                                <th>Uraian / Keterangan</th>
                                <th>Tahun <?= $tahun_lap ?></th>
                                <th>Tahun <?= $tahun_lalu ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th class="text-left">KAS DAN BANK</th>
                                <!-- <th class="text-right"><?= number_format($total_tahun_ini, 0, ',', '.'); ?></th> -->
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/penjelasan/input_kas_bank/' . $tahun_lap . '/' . $total_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right"><?= number_format($total_tahun_lalu, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                        <tbody>
                            <?php
                            $total_bank_tahun_ini = 0;
                            $total_bank_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($bank_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($bank_input as $row) : ?>
                                    <tr>
                                        <!-- <td class="text-center"><?= $no++; ?></td> -->
                                        <td class="text-left"><?= $row->nama_bank; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bank_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_bank_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    // Menjumlahkan total
                                    $total_bank_tahun_ini += $row->jumlah_bank_tahun_ini;
                                    $total_bank_tahun_lalu += $row->jumlah_bank_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Bank</th>
                                <th class="text-right"><?= number_format($total_bank_tahun_ini, 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bank_tahun_lalu, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                        <tbody>
                            <?php
                            $total_kas_tahun_ini = 0;
                            $total_kas_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($kas_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($kas_input as $row) : ?>
                                    <tr>
                                        <!-- <td class="text-center"><?= $no++; ?></td> -->
                                        <td class="text-left"><?= $row->nama_kas; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_kas_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_kas_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    // Menjumlahkan total
                                    $total_kas_tahun_ini += $row->jumlah_kas_tahun_ini;
                                    $total_kas_tahun_lalu += $row->jumlah_kas_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Kas</th>
                                <th class="text-right"><?= number_format($total_kas_tahun_ini, 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kas_tahun_lalu, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                        <tbody>
                            <?php
                            $total_pbt_tahun_ini = 0;
                            $total_pbt_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($pbt_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($pbt_input as $row) : ?>
                                    <tr>
                                        <!-- <td class="text-center"><?= $no++; ?></td> -->
                                        <td class="text-left"><?= $row->nama_pbt; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_pbt_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_pbt_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    // Menjumlahkan total
                                    $total_pbt_tahun_ini += $row->jumlah_pbt_tahun_ini;
                                    $total_pbt_tahun_lalu += $row->jumlah_pbt_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Piutang Non Usaha</th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/penjelasan/input_piutang_non_usaha/' . $tahun_lap . '/' . $total_pbt_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_pbt_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right"><?= number_format($total_pbt_tahun_lalu, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                        <tbody>
                            <?php
                            $total_pdm_tahun_ini = 0;
                            $total_pdm_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($pdm_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($pdm_input as $row) : ?>
                                    <tr>
                                        <!-- <td class="text-center"><?= $no++; ?></td> -->
                                        <td class="text-left"><?= $row->nama_pdm; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_pdm_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_pdm_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>

                                    <?php
                                    // Menjumlahkan total
                                    $total_pdm_tahun_ini += $row->jumlah_pdm_tahun_ini;
                                    $total_pdm_tahun_lalu += $row->jumlah_pdm_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Pembayaran Dimuka</th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/penjelasan/input_pdm/' . $tahun_lap . '/' . $total_pdm_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_pdm_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right"><?= number_format($total_pdm_tahun_lalu, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>