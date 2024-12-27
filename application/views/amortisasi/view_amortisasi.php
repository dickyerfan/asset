<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('amortisasi') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('amortisasi'); ?>" method="get">
                        <div style="display: flex; align-items: center;">
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
                    <div>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('amortisasi/cetak_amortisasi') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak Amortisasi</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <?php if ($this->session->userdata('level') != 'Pengguna') : ?>
                            <a href="<?= base_url('amortisasi/upload') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Amortisasi</button></a>
                        <?php endif; ?>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title) . ' TAHUN ' . $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama Asset</th>
                                <th>Tgl perolehan</th>
                                <!-- <th>Umur</th>
                                <th>Prsen</th> -->
                                <th>Harga Perolehan Thn Lalu</th>
                                <th>Penambahan</th>
                                <th>Pengurangan</th>
                                <th>Harga Perolehan Thn Ini</th>
                                <th>Akm Thn Lalu</th>
                                <th>Nilai Buku Thn Lalu</th>
                                <th>Penyusutan</th>
                                <th>Akm Thn Ini</th>
                                <th>Nilai Buku Thn Ini</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total_rupiah = 0;
                            foreach ($susut as $row) :
                                // $total_rupiah = $row->total_rupiah;
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td class="text-left"><?= $row->nama_amortisasi; ?></td>
                                    <td class="text-center"><?= date('d-m-Y', strtotime($row->tanggal)); ?></td>
                                    <!-- <td class="text-center"><?= $row->umur; ?></td>
                                    <td class="text-center"><?= $row->persen_susut; ?></td> -->
                                    <td class="text-right"><?= number_format($row->nilai_buku, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->penambahan, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->pengurangan, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->rupiah, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->akm_thn_lalu, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->nilai_buku_lalu, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->penambahan_penyusutan, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->akm_thn_ini, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->nilai_buku_final, 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="text-center bg-light">
                                <th colspan="3" class="text-right">Total</th>
                                <th class="text-right"><?= number_format($total_amortisasi['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_amortisasi['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_amortisasi['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_amortisasi['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_amortisasi['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_amortisasi['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_amortisasi['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_amortisasi['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_amortisasi['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>