<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('dashboard_publik') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('dashboard_publik'); ?>" method="get">
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
                    <!-- <div class="navbar-nav ms-2">
                        <a href="<?= base_url('dashboard_publik/cetak_evkin_pupr') ?>"><button class="float-end neumorphic-button"> Permendagri 47 Th 1999</button></a>
                    </div> -->
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('dashboard_publik/cetak_evkin_pupr') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak Dokumen</button></a>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <!-- <h5><?= strtoupper($title); ?></h5> -->
                    </div>
                </div>
                <div class="row">
                    <!-- Kiri: KemenPUPR -->
                    <div class="col-md-6">
                        <h5 class="text-center"><?= strtoupper($title); ?></h5>

                        <!-- Tabel Kinerja -->
                        <table class="table table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <th>NILAI KINERJA</th>
                                    <th>KATEGORI</th>
                                    <th>PENJELASAN</th>
                                    <th>NILAI KINERJA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-category text-center">
                                    <td class="group-label align-middle">diatas 2.8</td>
                                    <td>SEHAT</td>
                                    <td class="text-left">KEUANGAN</td>
                                    <td class="text-center font-weight-bold">0,98</td>
                                </tr>
                                <tr class="table-category text-center">
                                    <td class="group-label align-middle">2.2 s/d 2.8</td>
                                    <td>KURANG SEHAT</td>
                                    <td class="text-left">PELAYANAN</td>
                                    <td class="text-center font-weight-bold">0,65</td>
                                </tr>
                                <tr class="table-category text-center">
                                    <td class="group-label align-middle">dibawah 2.2</td>
                                    <td>SAKIT</td>
                                    <td class="text-left">OPERASIONAL</td>
                                    <td class="text-center font-weight-bold">1,61</td>

                                <tr class="table-category text-center">
                                    <td> </td>
                                    <td> </td>
                                    <td class="text-left">SUMBER DAYA MANUSIA</td>
                                    <td class="text-center font-weight-bold">0,55</td>
                                </tr>

                                <tr class="table-category text-center font-weight-bold">
                                    <td colspan="3" class="text-right">TOTAL KATEGORI</td>
                                    <td class="text-center font-weight-bold">3,79</td>
                                </tr>
                                <tr class="text-center font-weight-bold">
                                    <td colspan="3"> </td>
                                    <td class="bg-primary">SEHAT</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <!-- Kanan: Kemendagri -->
                    <div class="col-md-6">
                        <h5 class="text-center"><?= strtoupper($title2); ?></h5>
                        <!-- Tabel Kinerja -->
                        <table class="table table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <th>NILAI KINERJA</th>
                                    <th>KATEGORI</th>
                                    <th colspan="2">PENJELASAN</th>
                                    <th>NILAI KINERJA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-category text-center">
                                    <td class="group-label align-middle">> 75</td>
                                    <td class="group-label align-middle">BAIK SEKALI</td>
                                    <td class="text-left">KEUANGAN</td>
                                    <td>= (41 / 60) x 45</td>
                                    <td class="text-center font-weight-bold">30,75</td>
                                </tr>
                                <tr class="table-category text-center">
                                    <td class="group-label align-middle">> 60 - 75</td>
                                    <td class="group-label align-middle">BAIK</td>
                                    <td class="text-left">OPERASIONAL</td>
                                    <td>= (27 / 47) x 40</td>
                                    <td class="text-center font-weight-bold">22,98</td>
                                </tr>
                                <tr class="table-category text-center">
                                    <td class="group-label align-middle">> 45 - 60</td>
                                    <td class="group-label align-middle">CUKUP</td>
                                    <td class="text-left">ADMINISTRASI</td>
                                    <td>= (33 / 36) x 15</td>
                                    <td class="text-center font-weight-bold">13,75</td>

                                <tr class="table-category text-center">
                                    <td class="group-label align-middle">> 30 - 45</td>
                                    <td class="group-label align-middle">KURANG</td>
                                    <td> </td>
                                    <td class="text-left"></td>
                                    <td class="text-center font-weight-bold"></td>
                                </tr>

                                <tr class="table-category text-center">
                                    <td class="group-label align-middle">
                                        <= 30</td>
                                    <td class="group-label align-middle">TIDAK BAIK</td>
                                    <td></td>
                                    <td class="text-right font-weight-bold">TOTAL NILAI KINERJA</td>
                                    <td class="text-center font-weight-bold">67,48</td>
                                </tr>
                                <tr class="text-center font-weight-bold">
                                    <td colspan="4"> </td>
                                    <td class="bg-primary">BAIK</td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Tambahan Tabel Jumlah & Maksimum -->
                        <!-- <table class="table table-bordered mt-4">
                            <thead class="text-center">
                                <tr>
                                    <th rowspan="2">ASPEK</th>
                                    <th colspan="2">JUMLAH</th>
                                    <th rowspan="2">MAKSIMUM NILAI</th>
                                </tr>
                                <tr>
                                    <th>BOBOT</th>
                                    <th>INDIKATOR</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr>
                                    <td class="text-left">KEUANGAN</td>
                                    <td>45</td>
                                    <td>10</td>
                                    <td>60</td>
                                </tr>
                                <tr>
                                    <td class="text-left">OPERASIONAL</td>
                                    <td>40</td>
                                    <td>10</td>
                                    <td>47</td>
                                </tr>
                                <tr>
                                    <td class="text-left">ADMINISTRASI</td>
                                    <td>15</td>
                                    <td>10</td>
                                    <td>36</td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td class="text-left">JUMLAH</td>
                                    <td>100</td>
                                    <td>30</td>
                                    <td>143</td>
                                </tr>
                            </tbody>
                        </table> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>