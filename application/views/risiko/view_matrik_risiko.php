<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title); ?> </h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th rowspan="3" style="text-align:center; background-color: #b6e8faff; vertical-align: middle">MATRIK ANALISA RISIKO / <br> KEMUNGKINAN TERJADINYA RISIKO</th>
                                <th rowspan="3" style="text-align:center; background-color:#f6e79dff; vertical-align: middle">NILAI <br> PROB</th>
                                <th colspan="5" style="text-align:center;">DAMPAK / KONSEKUENSI</th>
                            </tr>
                            <tr>
                                <th style="text-align:center;" width="12%">Tidak</th>
                                <th style="text-align:center;" width="12%">Minor</th>
                                <th style="text-align:center;" width="12%">Moderat</th>
                                <th style="text-align:center;" width="12%">Signifikan</th>
                                <th style="text-align:center;" width="12%">Sangat</th>
                            </tr>
                            <tr style="background-color: #f6e79dff;">
                                <th style="text-align:center;">1</th>
                                <th style="text-align:center;">2</th>
                                <th style="text-align:center;">3</th>
                                <th style="text-align:center;">4</th>
                                <th style="text-align:center;">5</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $kemungkinan = [
                                5 => 'Hampir pasti terjadi / Sangat Sering Terjadi',
                                4 => 'Sering Terjadi',
                                3 => 'Kadang-kadang Terjadi',
                                2 => 'Jarang Terjadi',
                                1 => 'Sangat Jarang terjadi'
                            ];

                            foreach ($kemungkinan as $prob => $label) {
                                echo "<tr>";
                                echo "<td><strong>$label</strong></td>";
                                echo "<td style='text-align:center; font-weight:bold; background-color:#f6e79dff'>$prob</td>";

                                for ($dampak = 1; $dampak <= 5; $dampak++) {
                                    $skor = isset($matrik[$prob][$dampak]) ? $matrik[$prob][$dampak] : '-';

                                    // Warna berdasarkan skor
                                    if ($skor <= 5) {
                                        $warna = '#00B0F0'; // Biru muda
                                    } elseif ($skor <= 11) {
                                        $warna = '#92D050'; // Hijau
                                    } elseif ($skor <= 15) {
                                        $warna = '#FFFF00'; // Kuning
                                    } elseif ($skor <= 19) {
                                        $warna = '#FFC000'; // Oranye
                                    } else {
                                        $warna = '#FF0000'; // Merah
                                    }

                                    echo "<td style='text-align:center; font-weight:bold; background-color:{$warna}'>{$skor}</td>";
                                }
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 d-flex align-items-center">
                        <div style="width:50px; height:30px; background-color:#FF0000; border-radius:5px; border:1px solid #ccc; margin-right:10px;"></div>
                        <span style="font-weight:bold;">Area yang membutuhkan penanganan</span>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <div style="width:50px; height:30px; background-color:#00B0F0; border-radius:5px; border:1px solid #ccc; margin-right:10px;"></div>
                        <span style="font-weight:bold;">Area Penerimaan Risiko</span>
                    </div>
                </div>
                <br>
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title2); ?> </h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="text-center bg-light">
                            <tr>
                                <th>Deskripsi</th>
                                <th>Level</th>
                                <th>Range Nilai</th>
                                <th>Status Risiko</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php foreach ($tingkat_risiko as $row) :
                                // Pewarnaan berdasarkan skor_min (bisa juga skor_max, pilih salah satu)
                                $skor = $row->skor_min;
                                if ($skor <= 5) {
                                    $warna = '#00B0F0'; // Biru muda
                                } elseif ($skor <= 11) {
                                    $warna = '#92D050'; // Hijau
                                } elseif ($skor <= 15) {
                                    $warna = '#FFFF00'; // Kuning
                                } elseif ($skor <= 19) {
                                    $warna = '#FFC000'; // Oranye
                                } else {
                                    $warna = '#FF0000'; // Merah
                                }
                            ?>
                                <tr style="font-weight: bold;">
                                    <td style="background-color:<?= $warna ?>;"><strong><?= $row->nama_tr ?></strong></td>
                                    <td style="background-color:<?= $warna ?>;"><?= $row->level_tr ?></td>
                                    <td><?= $row->skor_min . ' s/d ' . $row->skor_max ?></td>
                                    <td><?= $row->status_tr ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>