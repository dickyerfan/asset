<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('evkin_pupr') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('evkin_pupr'); ?>" method="get">
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
                        <a href="<?= base_url('evkin_pupr/cetak_evkin_pupr') ?>"><button class="float-end neumorphic-button"> Permendagri 47 Th 1999</button></a>
                    </div> -->
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('evkin_pupr/cetak_evkin_pupr') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak Dokumen</button></a>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title); ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" style="font-size: 12px;">
                        <thead class="text-center">
                            <tr>
                                <th rowspan="2">INDIKATOR KINERJA & RUMUS</th>
                                <th rowspan="2">BOBOT</th>
                                <th rowspan="2">STANDAR</th>
                                <th rowspan="2">NILAI</th>
                                <th colspan="5">PENILAIAN</th>
                                <th rowspan="2">N</th>
                                <th rowspan="2">B</th>
                                <th rowspan="2">H</th>
                            </tr>
                            <tr>
                                <th>Perhitungan</th>
                                <th colspan="4">=</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="12" class="font-weight-bold">I. ASPEK KEUANGAN</td>
                            </tr>
                            <tr>
                                <td colspan="12" class="pl-3 font-weight-bold">1. RENTABILITAS</td>
                            </tr>
                            <tr>
                                <td class="pl-4">a. ROE (Return Of Equity)<br>
                                    <a>Kemampuan perusahaan untuk menghasilkan laba atas investasi yang dilakukannya</a>
                                    <br>
                                    <br>
                                    <i>Laba bersih setelah pajak / Jumlah Ekuitas x 100%</i>
                                </td>
                                <td class="text-center align-middle">0,055</td>
                                <td class="text-center">> 10%<br>7 - 10<br>3 - 7<br>0 - 3<br>
                                    < 0%</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td class="text-center align-middle font-weight-bold" colspan="4"><?= number_format($laba_rugi_bersih, 0, ',', '.'); ?> / <?= number_format($total_ekuitas_audited, 0, ',', '.'); ?> X 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_roe, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= $hasil_perhitungan_roe; ?></td>
                                <td class="text-center align-middle">0,055</td>
                                <td class="text-center align-middle"><?= number_format($hasil_roe, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4">b. Ratio Operasi<br>
                                    <a>Kemampuan perusahaan membiayai operasinya dari pendapatan operasi</a>
                                    <br>
                                    <br>
                                    <i>Biaya Operasi / Pendapatan Operasi</i>
                                </td>
                                <td class="text-center align-middle">0,055</td>
                                <td class="text-center">≤ 0,5<br>> 0,5 - 0,65<br>> 0,65 - 0,85<br>> 0,85 - 1,00<br>> 1,00</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($beban_usaha, 0, ',', '.'); ?> / <?= number_format($pendapatan_usaha, 0, ',', '.'); ?> = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_rasio_ops, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= $hasil_perhitungan_rasio_ops; ?></td>
                                <td class="text-center align-middle">0,055</td>
                                <td class="text-center align-middle"><?= number_format($hasil_rasio_ops, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="12" class="pl-3 font-weight-bold">2. LIKUIDITAS</td>
                            </tr>
                            <tr>
                                <td class="pl-4">a. Cash Ratio<br>
                                    <a>Kemampuan perusahaan membayar kewajiban jangka pendeknya dengan Kas dan Setara kas</a>
                                    <br>
                                    <br>
                                    <i>Kas + Setara Kas / Utang Lancar x 100%</i>
                                </td>
                                <td class="text-center align-middle">0,055</td>
                                <td class="text-center">> 100%<br>80 - 100<br>60 - 80<br>40 - 60<br>
                                    < 40</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($total_kas_bank, 0, ',', '.'); ?> / <?= number_format($hutang_lancar, 0, ',', '.'); ?> x 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_cash_rasio, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= $hasil_perhitungan_cash_rasio; ?></td>
                                <td class="text-center align-middle">0,055</td>
                                <td class="text-center align-middle"><?= number_format($hasil_cash_rasio, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4">b. Efektifitas Penagihan<br>
                                    <a>Efektifitas perusahaan dalam menagih piutang</a>
                                    <br>
                                    <br>
                                    <i>Penerimaan Rekening Air / Jumlah Rekening Air x 100%</i>
                                </td>
                                <td class="text-center align-middle">0,055</td>
                                <td class="text-center">> 90 %<br>> 85% - 90%<br>> 80% - 85%<br>> 75% - 80%<br>≤ 75%</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($rek_tagih, 0, ',', '.'); ?> / <?= number_format($total_pa_tahun_ini, 0, ',', '.'); ?> x 100 % =</td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_efek, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= $hasil_perhitungan_efek; ?></td>
                                <td class="text-center align-middle">0,055</td>
                                <td class="text-center align-middle"><?= number_format($hasil_efek, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="12" class="pl-3 font-weight-bold">3. SOLVABILITAS</td>

                            </tr>
                            <tr>
                                <td class="pl-4">Kemampuan perusahaan menjamin seluruh hutangnya dengan aktiva yang dimiliki<br><br>
                                    <a>Total Aktiva / Total Utang x 100%</a>
                                </td>
                                <td class="text-center align-middle">0,03</td>
                                <td class="text-center">> 200%<br>170 - 200<br>135 - 170<br>100 - 135<br>
                                    < 100%</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($total_asset, 0, ',', '.'); ?> / <?= number_format($total_utang, 0, ',', '.'); ?> x 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_solva, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= $hasil_perhitungan_solva; ?></td>
                                <td class="text-center align-middle">0,03</td>
                                <td class="text-center align-middle"><?= number_format($hasil_solva, 2, ',', '.'); ?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="11" class="text-right">TOTAL</th>
                                <th class="text-center font-weight-bold"><?= number_format($total_hasil_keuangan, 2, ',', '.'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
</section>
</div>