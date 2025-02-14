<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('lap_keuangan/persediaan'); ?>" method="get">
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
                    <a href="<?= base_url('lap_keuangan/persediaan') ?>" style="text-decoration: none;"><button class="neumorphic-button ms-2"> Tahun ini</button></a>
                    <div class="navbar-nav ms-auto ">
                        <a href="<?= base_url('lap_keuangan/persediaan/input_persediaan'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-plus"></i> Input Persediaan</button></a>
                    </div>
                    <div class="navbar-nav ms-2 ">
                        <a href="<?= base_url('lap_keuangan/persediaan/persediaan_cetak'); ?>" target="_blank"><button class=" neumorphic-button float-right"><i class="fas fa-print"></i> Cetak</button></a>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-12 text-center">
                        <h5><strong><?= strtoupper($title); ?></strong></h5>
                        <h5><strong>Per 31 Desember <?= $tahun_lap ?></strong></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>Jenis Barang Persediaan</th>
                                <th>Harga Perolehan</th>
                                <th>Penurunan Nilai</th>
                                <th>Nilai Buku</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_harga_perolehan = 0;
                            $total_nilai_penurunan = 0;
                            $total_nilai_buku = 0;
                            if (!empty($persediaan)) : ?>
                                <?php foreach ($persediaan as $row) : ?>
                                    <tr>
                                        <td><?= $row->nama_persediaan; ?></td>
                                        <td class="text-right"><?= number_format($row->harga_perolehan, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->nilai_penurunan, 0, ',', '.'); ?></td>
                                        <td class="text-right"><?= number_format($row->nilai_buku, 0, ',', '.'); ?></td>
                                    </tr>
                                    <?php
                                    $total_harga_perolehan += $row->harga_perolehan;
                                    $total_nilai_penurunan += $row->nilai_penurunan;
                                    $total_nilai_buku += $row->nilai_buku;
                                    ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="4" class="text-center">Data tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-right">Total</th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/persediaan/input_harga_perolehan/' . $tahun_lap . '/' . $total_harga_perolehan) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_harga_perolehan, 0, ',', '.'); ?>
                                    </a>
                                </th>
                                <th class="text-right">
                                    <a href="<?= base_url('lap_keuangan/persediaan/input_nilai_penurunan/' . $tahun_lap . '/' . $total_nilai_penurunan) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Neraca?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($total_nilai_penurunan, 0, ',', '.'); ?>
                                    </a>

                                </th>
                                <th class="text-right"><?= number_format($total_nilai_buku, 0, ',', '.'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>