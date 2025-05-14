<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('pelihara/kualitas_air/uji_syarat') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('pelihara/kualitas_air/uji_syarat'); ?>" method="get">
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
                        <a href="<?= base_url('pelihara/kualitas_air/jumlah_sample_uji') ?>"><button class="float-end neumorphic-button">Jumlah sample yang di uji</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('pelihara/kualitas_air/cetak_uji_syarat') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title); ?> <br> Tahun <?= $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th rowspan="3" class="align-middle">No</th>
                                <th rowspan="3" class="align-middle">Bulan</th>
                                <th colspan="2" rowspan="2" class="align-middle">Sample Minimal</th>
                                <th colspan="2" rowspan="2" class="align-middle">Sample Terambil</th>
                                <th colspan="12">Jumlah Parameter yang Memenuhi Syarat Kualitas Air Minum</th>

                            </tr>
                            <tr>
                                <th colspan="6" class="text-center">Internal</th>
                                <th colspan="6" class="text-center">Eksternal</th>
                            </tr>
                            <tr class="text-center">
                                <th>Int</th>
                                <th>Ekst</th>
                                <th>Int</th>
                                <th>Ekst</th>
                                <th>Fisika</th>
                                <th>M.biologi</th>
                                <th>Sisa chlor</th>
                                <th>Kimia Wajib</th>
                                <th>Kimia Tmb</th>
                                <th>Jml Sample</th>
                                <th>Fisika</th>
                                <th>M.biologi</th>
                                <th>Sisa chlor</th>
                                <th>Kimia Wajib</th>
                                <th>Kimia Tmb</th>
                                <th>Jml Sample</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total_fisika = $total_mikro = $total_sisa = $total_kimia_wajib = $total_kimia_tambahan = $total_jumlah_terambil = 0;
                            $total_fisika_eks = $total_mikro_eks = $total_sisa_eks = $total_kimia_wajib_eks = $total_kimia_tambahan_eks = $total_jumlah_terambil_eks = $total_jumlah_syarat = 0;

                            $bulan_list = [
                                "January", "February", "March", "April", "May", "June",
                                "July", "August", "September", "October", "November", "December"
                            ];

                            // Mengelompokkan data berdasarkan bulan
                            $data_sample = [];
                            foreach ($uji_syarat as $row) {
                                $data_sample[$row['bulan']] = $row;
                            }

                            foreach ($bulan_list as $bulan) {
                                $fisika = isset($data_sample[$bulan]) ? $data_sample[$bulan]['fisika'] : 0;
                                $mikro = isset($data_sample[$bulan]) ? $data_sample[$bulan]['mikrobiologi'] : 0;
                                $sisa = isset($data_sample[$bulan]) ? $data_sample[$bulan]['sisa_chlor'] : 0;
                                $kimia_wajib = isset($data_sample[$bulan]) ? $data_sample[$bulan]['kimia_wajib'] : 0;
                                $kimia_tambahan = isset($data_sample[$bulan]) ? $data_sample[$bulan]['kimia_tambahan'] : 0;
                                $fisika_eks = isset($data_sample[$bulan]) ? $data_sample[$bulan]['fisika_eks'] : 0;
                                $mikro_eks = isset($data_sample[$bulan]) ? $data_sample[$bulan]['mikrobiologi_eks'] : 0;
                                $sisa_eks = isset($data_sample[$bulan]) ? $data_sample[$bulan]['sisa_chlor_eks'] : 0;
                                $kimia_wajib_eks = isset($data_sample[$bulan]) ? $data_sample[$bulan]['kimia_wajib_eks'] : 0;
                                $kimia_tambahan_eks = isset($data_sample[$bulan]) ? $data_sample[$bulan]['kimia_tambahan_eks'] : 0;
                                $jumlah_terambil = isset($data_sample[$bulan]) ? $data_sample[$bulan]['jumlah_terambil'] : 0;
                                $jumlah_terambil_eks = isset($data_sample[$bulan]) ? $data_sample[$bulan]['jumlah_terambil_eks'] : 0;
                                $jumlah_syarat = isset($data_sample[$bulan]) ? $data_sample[$bulan]['jumlah_syarat'] : 0;

                                // Menjumlahkan total per kategori
                                $total_fisika += $fisika;
                                $total_mikro += $mikro;
                                $total_sisa += $sisa;
                                $total_kimia_wajib += $kimia_wajib;
                                $total_kimia_tambahan += $kimia_tambahan;
                                $total_fisika_eks += $fisika_eks;
                                $total_mikro_eks += $mikro_eks;
                                $total_sisa_eks += $sisa_eks;
                                $total_kimia_wajib_eks += $kimia_wajib_eks;
                                $total_kimia_tambahan_eks += $kimia_tambahan_eks;
                                $total_jumlah_terambil += $jumlah_terambil;
                                $total_jumlah_terambil_eks += $jumlah_terambil_eks;
                                $total_jumlah_syarat += $jumlah_syarat;
                            ?>
                                <tr class="text-center">
                                    <td><?= $no++; ?></td>
                                    <td class="text-left"><?= $bulan; ?></td>
                                    <td><?= $jumlah_terambil; ?></td>
                                    <td>0</td>
                                    <td><?= $jumlah_terambil; ?></td>
                                    <td>0</td>
                                    <td><?= $fisika; ?></td>
                                    <td><?= $mikro; ?></td>
                                    <td><?= $sisa; ?></td>
                                    <td><?= $kimia_wajib; ?></td>
                                    <td><?= $kimia_tambahan; ?></td>
                                    <td><?= $jumlah_terambil; ?></td>
                                    <td><?= $fisika_eks; ?></td>
                                    <td><?= $mikro_eks; ?></td>
                                    <td><?= $sisa_eks; ?></td>
                                    <td><?= $kimia_wajib_eks; ?></td>
                                    <td><?= $kimia_tambahan_eks; ?></td>
                                    <td><?= $jumlah_terambil_eks; ?></td>
                                </tr>
                            <?php } ?>
                            <tr class="text-center font-weight-bold">
                                <td colspan="2">JUMLAH</td>
                                <td><?= $total_jumlah_terambil; ?></td>
                                <td>0</td>
                                <td><?= $total_jumlah_terambil; ?></td>
                                <td>0</td>
                                <td colspan="5">Jumlah titik sample yang MSAM</td>
                                <td><?= $total_jumlah_terambil; ?></td>
                                <td colspan="5">Jumlah titik sample yang MSAM</td>
                                <td><?= $total_jumlah_terambil_eks; ?></td>
                            </tr>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>
</section>
</div>