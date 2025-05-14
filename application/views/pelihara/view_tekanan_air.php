<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('pelihara/tekanan_air') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('pelihara/tekanan_air'); ?>" method="get">
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
                    <?php if ($this->session->userdata('bagian') == 'Pemeliharaan') { ?>
                        <div class="navbar-nav ms-2">
                            <a href="<?= base_url('pelihara/tekanan_air/input_tka') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Tekanan Air</button></a>
                        </div>
                    <?php } ?>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('pelihara/tekanan_air/cetak_tekanan_air') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>

                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title); ?> <?= $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>UPK</th>
                                <th>Jumlah SR</th>
                                <th>Jumlah Di cek</th>
                                <th>Yang diatas 0,7</th>
                                <th>Persentase</th>
                                <th>Jumlah SR diatas 0,7</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total_sr = 0;
                            $total_cek = 0;
                            $total_07 = 0;
                            $total_persentase = 0;
                            $total_sr_70 = 0;

                            foreach ($tekanan_air as $row) :
                                $jumlah_cek = $row->jumlah_cek;
                                $jumlah_07 = $row->jumlah_07;
                                $persentase = ($jumlah_07 / $jumlah_cek) * 100;
                                $persentase = number_format($persentase, 2);

                                $total_sr += $row->jumlah_sr;
                                $total_cek += $row->jumlah_cek;
                                $total_07 += $row->jumlah_07;
                                $total_persentase += $persentase;
                                $total_sr_70 += $row->jumlah_sr_70;
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><?= $row->nama_bagian; ?></td>
                                    <td class="text-center"><?= $row->jumlah_sr; ?></td>
                                    <td class="text-center"><?= $row->jumlah_cek; ?></td>
                                    <td class="text-center"><?= $row->jumlah_07; ?></td>
                                    <td class="text-center"><?= $persentase ?></td>
                                    <td class="text-center"><?= $row->jumlah_sr_70; ?></td>
                                    <td>
                                        <?php if ($this->session->userdata('bagian') == 'Pemeliharaan' or $this->session->userdata('bagian') == 'Administrator') { ?>
                                            <div class="text-center">
                                                <a href="<?= base_url('pelihara/tekanan_air/edit_tka/' . $row->id_ek_tka) ?>"><i class="fas fa-edit"></i></a>
                                            </div>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <th colspan="2" class="text-center">Jumlah</th>
                                <th class="text-center"><?= number_format($total_sr, 0, ',', '.'); ?></th>
                                <th class="text-center"><?= number_format($total_cek, 0, ',', '.'); ?></th>
                                <th class="text-center"><?= number_format($total_07, 0, ',', '.'); ?></th>
                                <th class="text-center"></th>
                                <th class="text-center"><?= number_format($total_sr_70, 0, ',', '.'); ?></th>
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