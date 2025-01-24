<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('lap_keuangan/peny_piutang'); ?>" method="get">
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
                    <a href="<?= base_url('lap_keuangan/peny_piutang') ?>" style="text-decoration: none;"><button class="neumorphic-button ms-2"> Tahun ini</button></a>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('lap_keuangan/peny_piutang/data_total'); ?>"><button class=" neumorphic-button float-right"> Data Rekap</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <?php if ($this->session->userdata('level') != 'Pengguna') : ?>
                            <a href="<?= base_url('lap_keuangan/peny_piutang/tambah'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-plus"></i> Input Piutang</button></a>
                        <?php endif; ?>
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
                                <th>No</th>
                                <th>Uraian</th>
                                <th>Tahun</th>
                                <th>Saldo Awal</th>
                                <th>Tambah</th>
                                <th>Kurang</th>
                                <th>Saldo Akhir</th>
                                <th>Persen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($piutang as $row) :
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td class="text-left"><?= $row->kel_tarif_ket; ?></td>
                                    <td class="text-center"><?= date('Y', strtotime($row->tgl_piutang)); ?></td>
                                    <td class="text-right"><?= number_format($row->saldo_awal, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->tambah, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->kurang, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->saldo_akhir, 0, ',', '.'); ?></td>
                                    <td class="text-center"><?= $row->persen_tagih; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <!-- <tfoot>
                            <tr class="text-center bg-light">
                                <th colspan="5" class="text-right">Total</th>
                                <th class="text-right"><?= number_format($totals['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                        </tfoot> -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>