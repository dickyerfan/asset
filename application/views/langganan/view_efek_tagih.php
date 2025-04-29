<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('langganan/efek_tagih') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('langganan/efek_tagih'); ?>" method="get">
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
                            <a href="<?= base_url('langganan/efek_tagih/input_efek_tagih') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Efektifitas Tagih</button></a>
                        </div>
                        <div class="navbar-nav ms-2">
                            <a href="<?= base_url('langganan/efek_tagih/input_sisa_piu') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Sisa Piutang</button></a>
                        </div>
                    <?php } ?>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('langganan/efek_tagih/cetak_efek_tagih') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>
                    </div>
                </nav>
            </div>
            <div class="container mt-4">
                <div class="card">
                    <div class="card-header text-center">
                        <h5><?= strtoupper($title); ?> <?= $tahun_lap; ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="text-right"><?= number_format($total_pa_tahun_ini, 0, ',', '.') ?></td>
                                        <td class="text-center">a</td>
                                        <td>DRD selama <?= $tahun_lap; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><?= number_format($sisa_rek, 0, ',', '.') ?></td>
                                        <td class="text-center">b</td>
                                        <td>Sisa Rekening satu tahun (Lap Desember <?= $tahun_lap; ?>)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><?= number_format($total_rp, 0, ',', '.') ?></td>
                                        <td class="text-center">c</td>
                                        <td>Penerimaan Januari <?= $tahun_lap + 1; ?> khusus rek tahun <?= $tahun_lap; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right font-weight-bold">Rekening Tertagih </td>
                                        <td class="text-center"> a - b + c</td>
                                        <td class="text-left font-weight-bold"><?= number_format($rek_tagih, 0, ',', '.') ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right font-weight-bold">% Rekening Tertagih</td>
                                        <td colspan="2" class="font-weight-bold"><?= number_format($rek_tagih, 0, ',', '.') ?> : <?= number_format($total_pa_tahun_ini, 0, ',', '.') ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right font-weight-bold"></td>
                                        <td colspan="2" class="font-weight-bold"><?= $persen; ?> %</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title2); ?> <?= $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th rowspan="2" class="align-middle">No</th>
                                <th rowspan="2" class="align-middle">Nama U P K</th>
                                <?php
                                $kategori = [
                                    '1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr',
                                    '5' => 'Mei', '6' => 'Jun', '7' => 'Jul', '8' => 'Ags',
                                    '9' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
                                ];

                                foreach ($kategori as $k) {
                                    echo "<th colspan='2'>{$k}</th>";
                                }
                                ?>
                                <th colspan="2">Jumlah</th>
                            </tr>
                            <tr>
                                <?php
                                for ($i = 1; $i <= 12; $i++) {
                                    echo "<th style='text-align: center;'>Lbr</th><th style='text-align: center;'>Rp</th>";
                                }
                                echo "<th style='text-align: center;'>Lbr</th><th style='text-align: center;'>Rp</th>";
                                ?>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            // Inisialisasi array kosong per upk
                            $data_rinci = [];
                            foreach ($bagian_upk as $upk) {
                                $nama_upk = $upk->nama_bagian;
                                $data_rinci[$nama_upk] = [];
                                foreach (array_keys($kategori) as $bulan) {
                                    $data_rinci[$nama_upk][$bulan] = ['sr' => 0, 'rp' => 0];
                                }
                            }

                            // Tambahkan data dari $efek ke array rinci
                            foreach ($efek as $row) {
                                $upk = $row->nama_bagian;
                                $bulan = (string) $row->bulan_data;

                                if (isset($data_rinci[$upk][$bulan])) {
                                    $data_rinci[$upk][$bulan]['sr'] += $row->jumlah_sr;
                                    $data_rinci[$upk][$bulan]['rp'] += $row->rupiah;
                                }
                            }

                            // Inisialisasi total keseluruhan
                            $total_keseluruhan = [];
                            foreach (array_keys($kategori) as $bulan) {
                                $total_keseluruhan[$bulan] = ['sr' => 0, 'rp' => 0];
                            }
                            $total_keseluruhan['JUMLAH'] = ['sr' => 0, 'rp' => 0];

                            // Tampilkan data
                            $no = 1;
                            foreach ($data_rinci as $nama_bagian => $item) {
                                echo "<tr>";
                                echo "<td class='text-center'>{$no}</td>";
                                echo "<td>{$nama_bagian}</td>";

                                $total_sr = 0;
                                $total_rp = 0;

                                foreach (array_keys($kategori) as $bulan) {
                                    $sr = $item[$bulan]['sr'];
                                    $rp = $item[$bulan]['rp'];

                                    $total_sr += $sr;
                                    $total_rp += $rp;

                                    $total_keseluruhan[$bulan]['sr'] += $sr;
                                    $total_keseluruhan[$bulan]['rp'] += $rp;

                                    echo "<td class='text-right'>" . number_format($sr, 0, ',', '.') . "</td>";
                                    echo "<td class='text-right'>" . number_format($rp, 0, ',', '.') . "</td>";
                                }

                                $total_keseluruhan['JUMLAH']['sr'] += $total_sr;
                                $total_keseluruhan['JUMLAH']['rp'] += $total_rp;

                                echo "<td class='text-right'>" . number_format($total_sr, 0, ',', '.') . "</td>";
                                echo "<td class='text-right'>" . number_format($total_rp, 0, ',', '.') . "</td>";
                                echo "</tr>";
                                $no++;
                            }
                            ?>
                        </tbody>

                        <tfoot>
                            <tr style="font-weight: bold;">
                                <td colspan="2" class="text-center">TOTAL</td>
                                <?php
                                foreach (array_keys($kategori) as $bulan) {
                                    echo "<td class='text-right'>" . number_format($total_keseluruhan[$bulan]['sr'], 0, ',', '.') . "</td>";
                                    echo "<td class='text-right'>" . number_format($total_keseluruhan[$bulan]['rp'], 0, ',', '.') . "</td>";
                                }
                                echo "<td class='text-right'>" . number_format($total_keseluruhan['JUMLAH']['sr'], 0, ',', '.') . "</td>";
                                echo "<td class='text-right'>" . number_format($total_keseluruhan['JUMLAH']['rp'], 0, ',', '.') . "</td>";
                                ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title3); ?> <?= $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th colspan="2">1 Bulan</th>
                                <th colspan="2">2 Bulan</th>
                                <th colspan="2">3 Bulan</th>
                                <th colspan="2">4 Bulan - 1 Tahun</th>
                                <th colspan="2">Jumlah</th>
                            </tr>
                            <tr class="text-center">
                                <th>Lbr</th>
                                <th>Rp</th>
                                <th>Lbr</th>
                                <th>Rp</th>
                                <th>Lbr</th>
                                <th>Rp</th>
                                <th>Lbr</th>
                                <th>Rp</th>
                                <th>Lbr</th>
                                <th>Rp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Inisialisasi dulu semua default 0
                            $data_piu = [
                                '1 Bulan' => ['lbr' => 0, 'rp' => 0],
                                '2 Bulan' => ['lbr' => 0, 'rp' => 0],
                                '3 Bulan' => ['lbr' => 0, 'rp' => 0],
                                '4 Bulan - 1 Tahun' => ['lbr' => 0, 'rp' => 0],
                            ];

                            $total_lbr = 0;
                            $total_rp = 0;

                            foreach ($sisa_piu as $row) {
                                if (isset($data_piu[$row->uraian])) {
                                    // Asumsikan $row memiliki properti  'rupiah', dan 'jumlah_sr'
                                    $data_piu[$row->uraian]['lbr'] += $row->jumlah_sr;
                                    $data_piu[$row->uraian]['rp'] += $row->rupiah;

                                    // Hitung total keseluruhan
                                    $total_lbr += $row->jumlah_sr;
                                    $total_rp += $row->rupiah;
                                }
                            }

                            $data = [
                                'total_lbr' => $total_lbr,
                                'total_rp' => $total_rp,
                            ];
                            ?>

                            <tr class="text-center">
                                <th><?= number_format($data_piu['1 Bulan']['lbr'], 0, ',', '.'); ?></th>
                                <th><?= number_format($data_piu['1 Bulan']['rp'], 0, ',', '.'); ?></th>
                                <th><?= number_format($data_piu['2 Bulan']['lbr'], 0, ',', '.'); ?></th>
                                <th><?= number_format($data_piu['2 Bulan']['rp'], 0, ',', '.'); ?></th>
                                <th><?= number_format($data_piu['3 Bulan']['lbr'], 0, ',', '.'); ?></th>
                                <th><?= number_format($data_piu['3 Bulan']['rp'], 0, ',', '.'); ?></th>
                                <th><?= number_format($data_piu['4 Bulan - 1 Tahun']['lbr'], 0, ',', '.'); ?></th>
                                <th><?= number_format($data_piu['4 Bulan - 1 Tahun']['rp'], 0, ',', '.'); ?></th>
                                <th><?= number_format($total_lbr, 0, ',', '.'); ?></th>
                                <th><?= number_format($total_rp, 0, ',', '.'); ?></th>
                            </tr>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>