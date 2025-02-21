<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('lap_keuangan/asset_tetap'); ?>" method="get">
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
                        <a href="<?= base_url('lap_keuangan/asset_tetap') ?>" style="text-decoration: none;"><button class="neumorphic-button ms-2"> Tahun ini</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <select id="jenis_transaksi" class="form-control" onchange="redirectToPage()">
                            <option value="">Pilih Jenis Input :</option>
                            <option value="<?= base_url('lap_keuangan/asset_tetap/input_atd') ?>">Aset Tetap Dikerjasamakan</option>
                            <option value="<?= base_url('lap_keuangan/asset_tetap/input_atdp') ?>">Aset Tetap Dalam Penyelesaian</option>
                            <!-- <option value="<?= base_url('lap_keuangan/asset_tetap/input_atb') ?>">Aset Tidak Berwujud</option> -->
                            <option value="<?= base_url('lap_keuangan/asset_tetap/input_aaatb') ?>">Akm Amortisasi Aset Tidak Berwujud</option>
                            <option value="<?= base_url('lap_keuangan/asset_tetap/input_apt') ?>">Aset Pajak Tangguhan</option>
                        </select>
                    </div>
                </nav>
            </div>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">

                        <?php
                        if (empty($tahun_lap)) {
                            // $bulan_lap = date('m');
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
                                <th>Nama Asset</th>
                                <th>Harga Perolehan Thn Ini</th>
                                <th>Akm Thn Ini</th>
                                <th>Nilai Buku Thn Ini</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center bg-light">
                                <th class="text-left">Tanah & Penyempurnaan Tanah</th>
                                <th class="text-right"><?= number_format($total_tanah['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tanah['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tanah['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th class="text-left">Bangunan/Gedung</th>
                                <th class="text-right"><?= number_format($total_bangunan['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bangunan['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bangunan['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th class="text-left">Instalasi Sumber</th>
                                <th class="text-right"><?= number_format($total_sumber['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_sumber['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_sumber['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th class="text-left">Instalasi Pompa</th>
                                <th class="text-right"><?= number_format($total_pompa['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th class="text-left">Instalasi Pengolahan Air</th>
                                <th class="text-right"><?= number_format($total_olah_air['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_olah_air['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_olah_air['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th class="text-left">Instalasi Trans & Distribusi</th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th class="text-left">Peralatan/Perlengkapan</th>
                                <th class="text-right"><?= number_format($total_peralatan['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_peralatan['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_peralatan['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th class="text-left">Kendaraan/Alat Angkut</th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th class="text-left">Inventaris/Perabotan Kantor</th>
                                <th class="text-right"><?= number_format($total_inventaris['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th class="text-left">Total</th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/asset_tetap/input_harga_perolehan/' . $tahun_lap . '/' . $totals['total_rupiah']) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($totals['total_rupiah'], 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/asset_tetap/input_akm_thn_ini/' . $tahun_lap . '/' . $totals['total_akm_thn_ini']) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($totals['total_akm_thn_ini'], 0, ',', '.'); ?>
                                    </a>

                                </th>
                                <th class="text-right"><?= number_format($totals['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">

                        <?php
                        if (empty($tahun_lap)) {
                            // $bulan_lap = date('m');
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
                            $total_atdp_tahun_ini = 0;
                            $total_atdp_tahun_lalu = 0;
                            ?>
                            <?php if (!empty($atdp_input)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($atdp_input as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row->nama_atdp; ?></td>
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
                                    <td colspan="8" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-left">Total Asset Tetap Dalam Penyelesaian</th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/asset_tetap/input_atdp_neraca/' . $tahun_lap . '/' . $total_atdp_tahun_ini) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_atdp_tahun_ini, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right"><?= number_format($total_atdp_tahun_lalu, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title3) . ' TAHUN ' . $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama Asset Tidak Berwujud</th>
                                <th>Tgl perolehan</th>
                                <th>Harga Perolehan Thn Ini</th>
                                <th>Akm Thn Ini</th>
                                <th>Nilai Buku Thn Ini</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total_rupiah = 0;
                            foreach ($susut as $row) :
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td class="text-left"><?= $row->nama_amortisasi; ?></td>
                                    <td class="text-center"><?= date('d-m-Y', strtotime($row->tanggal)); ?></td>
                                    <td class="text-right"><?= number_format($row->rupiah, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->akm_thn_ini, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->nilai_buku_final, 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="text-center bg-light">
                                <th colspan="3" class="text-left">Total Asset Tidak Berwujud</th>
                                <th class="text-right">
                                    <?= number_format($total_amortisasi['total_rupiah'], 0, ',', '.'); ?>
                                </th>
                                <th class="text-right">
                                    <?= number_format($total_amortisasi['total_akm_thn_ini'], 0, ',', '.'); ?>
                                </th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/asset_tetap/input_atb/' . $tahun_lap . '/' . $total_amortisasi['total_nilai_buku_final']) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_amortisasi['total_nilai_buku_final'], 0, ',', '.'); ?>
                                    </a>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>