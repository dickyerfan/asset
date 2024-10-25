<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('asset_rekap/ganti_wm_rekap') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('asset_rekap/ganti_wm_rekap'); ?>" method="get">
                        <div style="display: flex; align-items: center;">
                            <!-- <input type="submit" value="Pilih Tahun" class="neumorphic-button"> -->
                            <!-- <input type="date" id="tanggal" name="tanggal" class="form-control" style="margin-left: 10px;"> -->
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
                        <a href="<?= base_url('asset_rekap/sr_baru') ?>"><button class="float-end neumorphic-button"><i class="fas fa-file"></i> SR Baru</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('asset_rekap/ganti_wm') ?>"><button class="float-end neumorphic-button"><i class="fas fa-file"></i> Ganti WM</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('asset_rekap/lainnya') ?>"><button class="float-end neumorphic-button"><i class="fas fa-file"></i> Lain-lain</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('asset_rekap/trans_dist') ?>"><button class="float-end neumorphic-button"><i class="fas fa-file"></i> Total</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('asset_rekap/ganti_wm_rekap') ?>"><button class="float-end neumorphic-button"><i class="fas fa-file"></i> Rekap Ganti WM</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('asset_rekap/cetak_ganti_wm_rekap') ?>"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak Asset</button></a>
                    </div>
                </nav>
            </div>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title) . ' ' . $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Lokasi</th>
                                <th>Jumlah</th>
                                <th>Rupiah</th>
                                <th>Ket</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total_rupiah = 0;
                            $total_jumlah = 0;
                            $jumlah_total = 0;
                            foreach ($ganti_wm as $row) :
                                $total_rupiah = $row->total_rupiah;
                                $total_jumlah = $row->total_jumlah;
                                $jumlah_total = $total_jumlah++;
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><?= $row->nama_bagian; ?></td>
                                    <td class="text-center"><?= number_format($row->jumlah, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->rupiah, 0, ',', '.'); ?></td>
                                    <td></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="text-center bg-light">
                                <th></th>
                                <th>Jumlah</th>
                                <th class="text-center"><?= number_format($jumlah_total, 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_rupiah, 0, ',', '.'); ?></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>