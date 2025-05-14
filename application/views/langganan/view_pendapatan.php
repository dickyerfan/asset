<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('langganan/pendapatan') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('langganan/pendapatan'); ?>" method="get">
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
                    <?php if ($this->session->userdata('bagian') == 'Langgan') { ?>
                        <div class="navbar-nav ms-2">
                            <a href="<?= base_url('langganan/pendapatan/input_pendapatan') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Pendapatan</button></a>
                        </div>
                        <div class="navbar-nav ms-2">
                            <a href="<?= base_url('langganan/pendapatan/input_tangki_air') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Tangki Air</button></a>
                        </div>
                    <?php } ?>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('langganan/pendapatan/cetak_rincian') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="container mt-8">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0 text-center">Ringkasan Data</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <tbody>
                                        <tr>
                                            <th colspan="2">Harga Air</th>
                                            <td colspan="3" class="text-right"><?= number_format($total_harga_air, 0, ',', '.') ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Jasa Pemeliharaan</th>
                                            <td colspan="3" class="text-right"><?= number_format($total_by_peml, 0, ',', '.') ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Jasa Administrasi</th>
                                            <td colspan="3" class="text-right"><?= number_format($total_by_admin, 0, ',', '.') ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Pendapatan Air Lainnya</th>
                                            <td colspan="3" class="text-right"><?= number_format($pendapatan_air_lainnya, 0, ',', '.') ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Total Pendapatan Air</th>
                                            <td colspan="3" class="text-right"><?= number_format($total_tagihan, 0, ',', '.') ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Jumlah Rekening Air</th>
                                            <td colspan="3" class="text-right"><?= number_format($total_sr, 0, ',', '.') ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Jumlah Pemakaian Air</th>
                                            <td colspan="3" class="text-right"><?= number_format($total_vol, 0, ',', '.') ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Tarif Rata-rata</th>
                                            <td colspan="3" class="text-right"><?= number_format($rata, 2, ',', '.') ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title); ?> <?= $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th class="align-middle">No</th>
                                <th class="align-middle">Jenis Pelanggan</th>
                                <th class="align-middle">Jml Rek</th>
                                <th class="align-middle">Volume</th>
                                <th class="align-middle">By. Admin</th>
                                <th class="align-middle">By. Peml</th>
                                <th class="align-middle">Harga Air</th>
                                <th class="align-middle">Total Tagihan</th>
                                <th class="align-middle">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total_sr = 0;
                            $total_vol = 0;
                            $total_by_admin = 0;
                            $total_by_peml = 0;
                            $total_harga_air = 0;
                            $total_tagihan = 0;
                            foreach ($pendapatan as $row) :
                                $total_sr += $row->rek_air;
                                $total_vol += $row->volume;
                                $total_by_admin += $row->by_admin;
                                $total_by_peml += $row->jas_pem;
                                if ($row->id_kel_tarif != '12' && !is_null($row->id_kel_tarif)) {
                                    $total_harga_air += $row->harga_air;
                                }
                                $tagihan = $row->by_admin + $row->jas_pem + $row->harga_air;
                                $total_tagihan += $tagihan;
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td>
                                        <?= ((int)$row->id_kel_tarif === 12 || is_null($row->kel_tarif)) ? 'PENDAPATAN AIR LAINNYA' : $row->kel_tarif; ?>
                                    </td>
                                    <td class="text-right"><?= number_format($row->rek_air, 0, ',', '.') ?></td>
                                    <td class="text-right"><?= number_format($row->volume, 0, ',', '.') ?></td>
                                    <td class="text-right"><?= number_format($row->by_admin, 0, ',', '.') ?></td>
                                    <td class="text-right"><?= number_format($row->jas_pem, 0, ',', '.') ?></td>
                                    <td class="text-right"><?= number_format($row->harga_air, 0, ',', '.') ?></td>
                                    <td class="text-right"><?= number_format($tagihan, 0, ',', '.') ?></td>
                                    <td>
                                        <?php if ($this->session->userdata('bagian') == 'Langgan' or $this->session->userdata('bagian') == 'Keuangan' or $this->session->userdata('bagian') == 'Administrator') { ?>
                                            <div class="text-center">
                                                <a href="<?= base_url('langganan/pendapatan/edit_pendapatan/' . $row->id_pendapatan) ?>"><i class="fas fa-edit"></i></a>
                                            </div>
                                        <?php } ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #e9ecef; font-weight: bold;">
                                <td colspan="2" class="text-center">Total</td>
                                <td style='text-align: right;'><?= number_format($total_sr, 0, ',', '.') ?></td>
                                <td style='text-align: right;'><?= number_format($total_vol, 0, ',', '.') ?></td>
                                <td style='text-align: right;'><?= number_format($total_by_admin, 0, ',', '.') ?></td>
                                <td style='text-align: right;'><?= number_format($total_by_peml, 0, ',', '.') ?></td>
                                <td style='text-align: right;'><?= number_format($total_harga_air, 0, ',', '.') ?></td>
                                <td style='text-align: right;'><?= number_format($total_tagihan, 0, ',', '.') ?></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>