<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('langganan/rincian_pendapatan/non_domestik') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('langganan/rincian_pendapatan/non_domestik'); ?>" method="get">
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
                            <a href="<?= base_url('langganan/rincian_pendapatan/input_rincian_n_dom') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Rincian Pend</button></a>
                        </div>
                    <?php } ?>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('langganan/rincian_pendapatan') ?>"><button class="float-end neumorphic-button"> Dosmetik</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('langganan/rincian_pendapatan/cetak_rincian_n_dom') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="container mt-4">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-header bg-primary text-white py-2">
                                    <h6 class="mb-0">Ringkasan Data</h6>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm table-bordered table-striped mb-0">
                                        <tbody>
                                            <tr>
                                                <th colspan="2">Total Pelanggan</th>
                                                <td colspan="3" class="text-right"><?= number_format($total_semua['sr'], 0, ',', '.') ?> SR</td>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Total Pemakaian Air</th>
                                                <td colspan="3" class="text-right"><?= number_format($total_semua['vol'], 0, ',', '.') ?> M3</td>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Total Pendapatan</th>
                                                <td colspan="3" class="text-right text-success font-weight-bold">Rp <?= number_format($total_semua['rp'], 0, ',', '.') ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
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
                                <th rowspan="3" class="align-middle">No</th>
                                <th rowspan="3" class="align-middle">Kecamatan</th>
                                <th colspan="21" class="align-middle">Non Domestik</th>
                                <th rowspan="3" class="align-middle">Action</th>
                            </tr>
                            <tr class="text-center">
                                <th colspan="3">Sosial Khusus</th>
                                <th colspan="3">IP Desa</th>
                                <th colspan="3">TNI/POLRI</th>
                                <th colspan="3">IP Kab</th>
                                <th colspan="3">Niaga B</th>
                                <th colspan="3">Khusus</th>
                                <th colspan="3">Jumlah</th>
                            </tr>
                            <tr class="text-center">
                                <td>Jumlah SR</td>
                                <td>Volume</td>
                                <td>Rupiah</td>
                                <td>Jumlah SR</td>
                                <td>Volume</td>
                                <td>Rupiah</td>
                                <td>Jumlah SR</td>
                                <td>Volume</td>
                                <td>Rupiah</td>
                                <td>Jumlah SR</td>
                                <td>Volume</td>
                                <td>Rupiah</td>
                                <td>Jumlah SR</td>
                                <td>Volume</td>
                                <td>Rupiah</td>
                                <td>Jumlah SR</td>
                                <td>Volume</td>
                                <td>Rupiah</td>
                                <td>Jumlah SR</td>
                                <td>Volume</td>
                                <td>Rupiah</td>
                            </tr>
                        </thead>
                        <?php
                        $kategori = [
                            'Sosial Khusus' => 'SOSIAL B',
                            'IP Desa' => 'INSTANSI PEM DESA',
                            'TNI/POLRI' => 'TNI/POLRI',
                            'IP Kab' => 'INSTANSI PEM KAB',
                            'Niaga B' => 'NIAGA B',
                            'Khusus' => 'KHUSUS'
                        ];

                        // Inisialisasi array kosong per kecamatan
                        $data_rinci = [];
                        foreach ($kecamatan as $kec) {
                            $nama_kec = $kec->nama_kec;
                            $data_rinci[$nama_kec] = [];
                            foreach ($kategori as $kat) {
                                $data_rinci[$nama_kec][$kat] = ['sr' => 0, 'vol' => 0, 'rp' => 0];
                            }
                        }

                        // Tambahkan data dari $rincian ke dalam array rinci
                        foreach ($rincian as $row) {
                            $kec = $row->nama_kec;
                            $tarif = $row->kel_tarif;

                            if (in_array($tarif, $kategori)) {
                                $data_rinci[$kec][$tarif]['sr'] += $row->jumlah_sr;
                                $data_rinci[$kec][$tarif]['vol'] += $row->volume;
                                $data_rinci[$kec][$tarif]['rp'] += $row->rupiah;
                            }
                        }

                        // Inisialisasi total keseluruhan
                        $total_keseluruhan = [];
                        foreach ($kategori as $k) {
                            $total_keseluruhan[$k] = ['sr' => 0, 'vol' => 0, 'rp' => 0];
                        }
                        $total_keseluruhan['JUMLAH'] = ['sr' => 0, 'vol' => 0, 'rp' => 0];
                        ?>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($data_rinci as $nama_kec => $item) {
                                echo "<tr>";
                                echo "<td class='text-center'>{$no}</td>";
                                echo "<td>{$nama_kec}</td>";

                                $total_sr = $total_vol = $total_rp = 0;

                                foreach ($kategori as $k) {
                                    $sr = $item[$k]['sr'];
                                    $vol = $item[$k]['vol'];
                                    $rp = $item[$k]['rp'];

                                    // Hitung total per baris
                                    $total_sr += $sr;
                                    $total_vol += $vol;
                                    $total_rp += $rp;

                                    // Tambah ke total keseluruhan
                                    $total_keseluruhan[$k]['sr'] += $sr;
                                    $total_keseluruhan[$k]['vol'] += $vol;
                                    $total_keseluruhan[$k]['rp'] += $rp;

                                    echo "<td class='text-right'>" . number_format($sr, 0, ',', '.') . "</td>";
                                    echo "<td class='text-right'>" . number_format($vol, 0, ',', '.') . "</td>";
                                    echo "<td class='text-right'>" . number_format($rp, 0, ',', '.') . "</td>";
                                }

                                // Tambah ke total jumlah semua
                                $total_keseluruhan['JUMLAH']['sr'] += $total_sr;
                                $total_keseluruhan['JUMLAH']['vol'] += $total_vol;
                                $total_keseluruhan['JUMLAH']['rp'] += $total_rp;

                                echo "<td class='text-right'>" . number_format($total_sr, 0, ',', '.') . "</td>";
                                echo "<td class='text-right'>" . number_format($total_vol, 0, ',', '.') . "</td>";
                                echo "<td class='text-right'>" . number_format($total_rp, 0, ',', '.') . "</td>";
                                echo "<td class='text-center'><a href='#' class='btn btn-sm btn-primary'>Edit</a></td>";
                                echo "</tr>";
                                $no++;
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #e9ecef; font-weight: bold;">
                                <td colspan="2" class="text-center">Total</td>
                                <?php foreach ($kategori as $k) : ?>
                                    <td class="text-right"><?= number_format($total_keseluruhan[$k]['sr'], 0, ',', '.') ?></td>
                                    <td class="text-right"><?= number_format($total_keseluruhan[$k]['vol'], 0, ',', '.') ?></td>
                                    <td class="text-right"><?= number_format($total_keseluruhan[$k]['rp'], 0, ',', '.') ?></td>
                                <?php endforeach; ?>
                                <td class="text-right"><?= number_format($total_keseluruhan['JUMLAH']['sr'], 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($total_keseluruhan['JUMLAH']['vol'], 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($total_keseluruhan['JUMLAH']['rp'], 0, ',', '.') ?></td>
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