<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('pelihara/kualitas_air/jumlah_sample_uji') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('pelihara/kualitas_air/jumlah_sample_uji'); ?>" method="get">
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
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('pelihara/kualitas_air') ?>"><button class="float-end neumorphic-button"><i class="fas fa-reply"></i> Kembali</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('pelihara/kualitas_air/uji_syarat') ?>"><button class="float-end neumorphic-button">Uji Yang Memenuhi Syarat</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('pelihara/kualitas_air/cetak_jumlah_sample_uji') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title); ?> <br> Dinas Kesehatan Kabupaten Bondowoso Tahun <?= $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th rowspan="2" class="align-middle">No</th>
                                <th rowspan="2" class="align-middle">Bulan</th>
                                <th colspan="5">Jumlah Sample Pemeriksaan</th>
                            </tr>
                            <tr class="text-center">
                                <th>Fisika</th>
                                <th>Mikrobiologi</th>
                                <th>Sisa chlor</th>
                                <th>Kimia Wajib</th>
                                <th>Kimia Tambahan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total_fisika = $total_mikro = $total_sisa = $total_kimia_wajib = $total_kimia_tambahan = 0;

                            $bulan_list = [
                                "January", "February", "March", "April", "May", "June",
                                "July", "August", "September", "October", "November", "December"
                            ];

                            // Mengelompokkan data berdasarkan bulan
                            $data_sample = [];
                            foreach ($sample_uji as $row) {
                                $data_sample[$row['bulan']] = $row;
                            }

                            foreach ($bulan_list as $bulan) {
                                $fisika = isset($data_sample[$bulan]) ? $data_sample[$bulan]['fisika'] : 0;
                                $mikro = isset($data_sample[$bulan]) ? $data_sample[$bulan]['mikrobiologi'] : 0;
                                $sisa = isset($data_sample[$bulan]) ? $data_sample[$bulan]['sisa_chlor'] : 0;
                                $kimia_wajib = isset($data_sample[$bulan]) ? $data_sample[$bulan]['kimia_wajib'] : 0;
                                $kimia_tambahan = isset($data_sample[$bulan]) ? $data_sample[$bulan]['kimia_tambahan'] : 0;

                                // Menjumlahkan total per kategori
                                $total_fisika += $fisika;
                                $total_mikro += $mikro;
                                $total_sisa += $sisa;
                                $total_kimia_wajib += $kimia_wajib;
                                $total_kimia_tambahan += $kimia_tambahan;
                            ?>
                                <tr class="text-center">
                                    <td><?= $no++; ?></td>
                                    <td class="text-left"><?= $bulan; ?></td>
                                    <td><?= $fisika; ?></td>
                                    <td><?= $mikro; ?></td>
                                    <td><?= $sisa; ?></td>
                                    <td><?= $kimia_wajib; ?></td>
                                    <td><?= $kimia_tambahan; ?></td>
                                </tr>
                            <?php } ?>
                            <tr class="text-center font-weight-bold">
                                <td colspan="2">JUMLAH</td>
                                <td><?= $total_fisika; ?></td>
                                <td><?= $total_mikro; ?></td>
                                <td><?= $total_sisa; ?></td>
                                <td><?= $total_kimia_wajib; ?></td>
                                <td><?= $total_kimia_tambahan; ?></td>
                            </tr>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>
</section>
</div>