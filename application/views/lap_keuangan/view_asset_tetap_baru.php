<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar">
                    <form id="form_tahun" action="<?= base_url('lap_keuangan/asset_tetap_baru'); ?>" method="get">
                        <div style="display: flex; align-items: center;">
                            <input type="submit" value="Pilih Tahun" class="neumorphic-button">
                            <select id="tahun" name="tahun" class="form-control" style="margin-left: 15px;">
                                <?php
                                $currentYear = date('Y');
                                $selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $tahun_lap;
                                for ($year = 1989; $year <= $currentYear; $year++) {
                                    $selected = ($year == $selectedYear) ? 'selected' : '';
                                    echo "<option value='$year' $selected>$year</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </form>
                    <a href="<?= base_url('lap_keuangan/asset_tetap_baru') ?>" style="text-decoration: none;"><button class="neumorphic-button ms-2"> Tahun ini</button></a>
                    <div class="navbar-nav ms-auto">
                        <?php if ($this->session->userdata('level') != 'Pengguna') : ?>
                            <select id="jenis_transaksi" class="form-control" onchange="redirectToPage()">
                                <option value="">Pilih Jenis Input :</option>
                                <option value="<?= base_url('lap_keuangan/asset_tetap_baru/import') ?>">Import Asset Tetap</option>
                                <option value="<?= base_url('lap_keuangan/asset_tetap_baru/import_atdp') ?>">Import Asset Tetap Dalam Penyelesaian</option>
                                <option value="<?= base_url('lap_keuangan/asset_tetap_baru/import_atb') ?>">Import Asset Tidak Berwujud</option>
                                <option value="<?= base_url('lap_keuangan/asset_tetap_baru/input_apt') ?>">Input Asset Pajak Tangguhan</option>
                            </select>
                        <?php endif; ?>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-12 text-center">
                        <h5><strong><?= strtoupper($title) . ' TAHUN ' . $tahun_lap; ?></strong></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama Asset</th>
                                <th>Harga Perolehan</th>
                                <th>Akm Penyusutan</th>
                                <th>Nilai Buku</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($asset_tetap)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($asset_tetap as $row) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><?= $row->nama_asset; ?></td>
                                        <td class="text-right"><?= number_format($row->harga_perolehan, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->akm_penyusutan, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->nilai_buku, 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="font-weight-bold bg-light">
                                    <td colspan="2" class="text-center">Total</td>
                                    <td class="text-right">
                                        <a href="<?= base_url('lap_keuangan/asset_tetap_baru/input_aset_tetap/' . $tahun_lap . '/' . round($totals['harga_perolehan'])) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan total aset tetap ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                            <?= number_format($totals['harga_perolehan'], 0, ',', '.'); ?>
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        <a href="<?= base_url('lap_keuangan/asset_tetap_baru/input_akm_aset_tetap/' . $tahun_lap . '/' . round($totals['akm_penyusutan'])) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan total akm penyusutan ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                            <?= number_format($totals['akm_penyusutan'], 0, ',', '.'); ?>
                                        </a>
                                    </td>
                                    <td class="text-right"><?= number_format($totals['nilai_buku'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-center mt-4">
                    <div class="col-lg-12 text-center">
                        <h5><strong><?= strtoupper($title2) . ' TAHUN ' . $tahun_lap; ?></strong></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>Uraian / Keterangan</th>
                                <th>Tahun <?= $tahun_lap; ?></th>
                                <th>Tahun <?= $tahun_lalu; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_atdp_tahun_ini = 0;
                            $total_atdp_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($atdp_input)) : ?>
                                <?php foreach ($atdp_input as $row) : ?>
                                    <tr>
                                        <td><?= $row->nama_atdp; ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_atdp_tahun_ini, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->jumlah_atdp_tahun_lalu, 0, ',', '.'); ?></td>
                                    </tr>
                                    <?php
                                    $total_atdp_tahun_ini += $row->jumlah_atdp_tahun_ini;
                                    $total_atdp_tahun_lalu += $row->jumlah_atdp_tahun_lalu;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr class="font-weight-bold bg-light">
                                <td>Total Asset Tetap Dalam Penyelesaian</td>
                                <td class="text-right">
                                    <a href="<?= base_url('lap_keuangan/asset_tetap_baru/input_atdp_neraca/' . $tahun_lap . '/' . round($total_atdp_tahun_ini)) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan total asset tetap dalam penyelesaian ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_atdp_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </td>
                                <td class="text-right"><?= number_format($total_atdp_tahun_lalu, 0, ',', '.'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-center mt-4">
                    <div class="col-lg-12 text-center">
                        <h5><strong><?= strtoupper($title3) . ' TAHUN ' . $tahun_lap; ?></strong></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama Asset Tidak Berwujud</th>
                                <th>Tgl Perolehan</th>
                                <th>Harga Perolehan Thn Ini</th>
                                <th>Akm Thn Ini</th>
                                <th>Nilai Buku Thn Ini</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($atb_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($atb_input as $row) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><?= $row->nama_atb; ?></td>
                                        <td class="text-center"><?= date('d-m-Y', strtotime($row->tanggal_perolehan)); ?></td>
                                        <td class="text-right"><?= number_format($row->harga_perolehan, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->akm_amortisasi, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->nilai_buku, 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr class="font-weight-bold bg-light">
                                <td colspan="3">Total Asset Tidak Berwujud</td>
                                <td class="text-right"><?= number_format($total_atb['harga_perolehan'], 0, ',', '.'); ?></td>
                                <td class="text-right"><?= number_format($total_atb['akm_amortisasi'], 0, ',', '.'); ?></td>
                                <td class="text-right">
                                    <a href="<?= base_url('lap_keuangan/asset_tetap_baru/input_atb/' . $tahun_lap . '/' . round($total_atb['nilai_buku'])) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan total asset tidak berwujud ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_atb['nilai_buku'], 0, ',', '.'); ?>
                                    </a>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
