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
                                for ($year = 2023; $year <= $currentYear; $year++) {
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
                                <th rowspan="2" class="align-middle">INDIKATOR KINERJA & RUMUS</th>
                                <th rowspan="2" class="align-middle">BOBOT</th>
                                <th rowspan="2" class="align-middle">STANDAR</th>
                                <th rowspan="2" class="align-middle">NILAI</th>
                                <th colspan="5">PENILAIAN</th>
                                <th rowspan="2" class="align-middle">N</th>
                                <th rowspan="2" class="align-middle">B</th>
                                <th rowspan="2" class="align-middle">H</th>
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
                                <td colspan="12" class="font-weight-bold">II. ASPEK PELAYANAN</td>
                            </tr>
                            <tr>
                                <td colspan="12" class="pl-3 font-weight-bold">1. CAKUPAN PELAYANAN TEKNIS</td>
                            </tr>
                            <tr>
                                <td class="pl-4">Jumlah penduduk terlayani dalam wilayah pelayanan
                                    <br>
                                    <br>
                                    <i>Jumlah Penduduk Terlayani / Jumlah Penduduk wilayah pelayanan x 100%</i>
                                </td>
                                <td class="text-center align-middle">0,05</td>
                                <td class="text-center">> 80 %<br>60 % - 80 %<br>40 % - 60%<br>20 % - 40 %<br>
                                    < 20 %</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td class="text-center align-middle font-weight-bold" colspan="4"><?= number_format($total_jiwa_terlayani2, 0, ',', '.'); ?> / <?= number_format($total_wil_layan, 0, ',', '.'); ?> X 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_cak_teknis, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= $hasil_perhitungan_cak_teknis; ?></td>
                                <td class="text-center align-middle">0,05</td>
                                <td class="text-center align-middle"><?= number_format($hasil_cak_teknis, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="12" class="pl-3 font-weight-bold">2. PERTUMBUHAN PELANGGAN (% PERTHN)</td>
                            </tr>
                            <tr>
                                <td class="pl-4">Persentase kenaikan jumlah pelanggan dibanding pelanggan tahun lalu
                                    <br>
                                    <br>
                                    <i>Jumlah Pelanggan th ini - Pelanggan th lalu / Pelanggan Tahun Lalu x 100%</i>
                                </td>
                                <td class="text-center align-middle">0,05</td>
                                <td class="text-center">> 10%<br>8 - 10<br>6 - 8<br>4 - 6<br>
                                    < 4</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($jumlah_pelanggan, 0, ',', '.'); ?> / <?= number_format($jumlah_pelanggan_tahun_lalu, 0, ',', '.'); ?> x 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_pelanggan, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= $hasil_perhitungan_pelanggan; ?></td>
                                <td class="text-center align-middle">0,05</td>
                                <td class="text-center align-middle"><?= number_format($hasil_pelanggan, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="12" class="pl-3 font-weight-bold">3. TINGKAT PENYELESAIAN ADUAN</td>
                            </tr>
                            <tr>
                                <td class="pl-4">Jumlah Keluhan yang telah diselesaikan<br><br>
                                    <a>Jumlah Keluhan Selesai / Jumlah Keluhan x 100%</a>
                                </td>
                                <td class="text-center align-middle">0,025</td>
                                <td class="text-center">> 80 %<br>60 % - 80 %<br>40 % - 60%<br>20 % - 40 %<br>
                                    < 20 %</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($jumlah_keluhan_selesai, 0, ',', '.'); ?> / <?= number_format($jumlah_keluhan, 0, ',', '.'); ?> x 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_pengaduan, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= $hasil_perhitungan_pengaduan; ?></td>
                                <td class="text-center align-middle">0,025</td>
                                <td class="text-center align-middle"><?= number_format($hasil_pengaduan, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="12" class="pl-3 font-weight-bold">4. KUALITAS AIR PELANGGAN</td>
                            </tr>
                            <tr>
                                <td class="pl-4">Jumlah uji kualitas air yang memenuhi syarat<br><br>
                                    <a>Jumlah Uji Kualitas Yg Memenuhi Syarat / Jumlah Yang Diuji x 100%</a>
                                </td>
                                <td class="text-center align-middle">0,075</td>
                                <td class="text-center">> 80 %<br>60 % - 80 %<br>40 % - 60%<br>20 % - 40 %<br>
                                    < 20 %</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($total_jumlah_syarat, 0, ',', '.'); ?> / <?= number_format($total_jumlah_terambil, 0, ',', '.'); ?> x 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_kualitas, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= $hasil_perhitungan_kualitas; ?></td>
                                <td class="text-center align-middle">0,075</td>
                                <td class="text-center align-middle"><?= number_format($hasil_kualitas, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="12" class="pl-3 font-weight-bold">5. KONSUMSI AIR DOMESTIK</td>
                            </tr>
                            <tr>
                                <td class="pl-4">Rata-rata pemakaian air yang memenuhi syarat<br><br>
                                    <a>(Jumlah Air Yang Terjual Domestik / Jumlah Pelanggan Domestik)/12</a>
                                </td>
                                <td class="text-center align-middle">0,05</td>
                                <td class="text-center">> 30 M3<br>25 M3 - 30 M3<br>20 M3 - 25 M3<br>15 M3 - 20 M3<br>
                                    < 15 M3</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($jumlah_air_terjual, 0, ',', '.'); ?> / <?= number_format($jumlah_pelanggan_dom, 0, ',', '.'); ?> / 12 = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_air_dom, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= $hasil_perhitungan_air_dom; ?></td>
                                <td class="text-center align-middle">0,05</td>
                                <td class="text-center align-middle"><?= number_format($hasil_air_dom, 2, ',', '.'); ?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="11" class="text-right">TOTAL</th>
                                <th class="text-center font-weight-bold"><?= number_format($total_hasil_pelayanan, 2, ',', '.'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
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
                                <td colspan="12" class="font-weight-bold">III. ASPEK OPERASI</td>
                            </tr>
                            <tr>
                                <td colspan="12" class="pl-3 font-weight-bold">1. EFISIENSI PRODUKSI</td>
                            </tr>
                            <tr>
                                <td class="pl-4">Prosentase realisasi produksi atas kapasitas terpasang
                                    <br>
                                    <br>
                                    <i>Realisasi Produksi / Kapasitas Terpasang x 100%</i>
                                </td>
                                <td class="text-center align-middle">0,07</td>
                                <td class="text-center">> 90 %<br>80 % - 90 %<br>70 % - 80%<br>60 % - 70 %<br>
                                    < 60 %</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td class="text-center align-middle font-weight-bold" colspan="4"><?= number_format($total_volume_produksi, 0, ',', '.'); ?> / <?= number_format($total_terpasang, 0, ',', '.'); ?> X 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_kap_prod, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= $hasil_perhitungan_kap_prod; ?></td>
                                <td class="text-center align-middle">0,07</td>
                                <td class="text-center align-middle"><?= number_format($hasil_kap_prod, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="12" class="pl-3 font-weight-bold">2. TINGKAT KEHILANGAN AIR</td>
                            </tr>
                            <tr>
                                <td class="pl-4">Jumlah M3 air yang terjual dibandingkan jumlah M3 air yang distribusikan
                                    <br>
                                    <br>
                                    <i>Distribusi Air - Air Terekening / Distribusi Air x 100%</i>
                                </td>
                                <td class="text-center align-middle">0,07</td>
                                <td class="text-center">> 25 %<br>25 % - 30 %<br>30 % - 35 %<br>35 % - 40 %<br>
                                    < 40 %</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold">(<?= number_format($volume_produksi, 0, ',', '.'); ?> - <?= number_format($total_vol, 0, ',', '.'); ?>) / <?= number_format($volume_produksi, 0, ',', '.'); ?> x 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_nrw, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= $hasil_perhitungan_nrw; ?></td>
                                <td class="text-center align-middle">0,07</td>
                                <td class="text-center align-middle"><?= number_format($hasil_nrw, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="12" class="pl-3 font-weight-bold">3. JAM OPERASI LAYANAN (KONTINUITAS PELAYANAN AIR PERHARI)</td>
                            </tr>
                            <tr>
                                <td class="pl-4">Rata - rata waktu disrtibusi air ke pelanggan per hari<br><br>
                                    <a>Waktu Distribusi Air Ke Pelanggan 1 th / 365 hari</a>
                                </td>
                                <td class="text-center align-middle">0,08</td>
                                <td class="text-center">> 21-24 Jam<br>18-21 Jam<br>15-18 Jam<br>12-15 Jam<br>
                                    < 12 Jam</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($jam_ops_setahun, 2, ',', '.'); ?> / 365 = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_jam_ops, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= $hasil_perhitungan_pengaduan; ?></td>
                                <td class="text-center align-middle">0,08</td>
                                <td class="text-center align-middle"><?= number_format($hasil_pengaduan, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="12" class="pl-3 font-weight-bold">4. TEKANAN AIR SAMBUNGAN PELANGGAN</td>
                            </tr>
                            <tr>
                                <td class="pl-4">Jumlah pelanggan yang dilayani dengan tekanan lebih 7 bar dibandingkan dg seluruh pelangggan<br><br>
                                    <a>Jumlah Pelanggan dilayani dg tekanan > 0,7 bar / Jumlah Pelanggan x 100%</a>
                                </td>
                                <td class="text-center align-middle">0,065</td>
                                <td class="text-center">> 80 %<br>60 % - 80 %<br>40 % - 60 %<br>20 % - 40 %<br>
                                    < 20 %</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($jumlah_pelanggan_dilayani, 0, ',', '.'); ?> / <?= number_format($total_pelanggan, 0, ',', '.'); ?> x 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_tekanan_air, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= $hasil_perhitungan_tekanan_air; ?></td>
                                <td class="text-center align-middle">0,065</td>
                                <td class="text-center align-middle"><?= number_format($hasil_tekanan_air, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="12" class="pl-3 font-weight-bold">5. PENGGANTIAN METER AIR</td>
                            </tr>
                            <tr>
                                <td class="pl-4">Jumlah meter air yang diganti setahun dibandingkan jumlah pelanggan yang ada <br><br>
                                    <a>Jumlah Meter Yg diganti th ybs / Jumlah Pelanggan x 100%</a>
                                </td>
                                <td class="text-center align-middle">0,65</td>
                                <td class="text-center">> 20 %<br>15 % - 20 %<br>10 % - 15 %<br>5 % - 10 %<br>
                                    < 5 %</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($total_semua_meter, 0, ',', '.'); ?> / <?= number_format($total_pelanggan, 0, ',', '.'); ?> * 100% = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_ganti_meter, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= $hasil_perhitungan_ganti_meter; ?></td>
                                <td class="text-center align-middle">0,65</td>
                                <td class="text-center align-middle"><?= number_format($hasil_ganti_meter, 2, ',', '.'); ?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="11" class="text-right">TOTAL</th>
                                <th class="text-center font-weight-bold"><?= number_format($total_hasil_operasional, 2, ',', '.'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
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
                                <td colspan="12" class="font-weight-bold">IV. ASPEK SUMBER DAYA MANUSIA</td>
                            </tr>
                            <tr>
                                <td colspan="12" class="pl-3 font-weight-bold">1. RASIO JUMLAH PEGAWAI DIBANDINGKAN DENGAN 1000 PELANGGAN</td>
                            </tr>
                            <tr>
                                <td class="pl-4">Jumlah Pegawai untuk melayani 1000 pelanggan
                                    <br>
                                    <br>
                                    <i>Jumlah Pegawai / Jumlah Pelanggan x 100%</i>
                                </td>
                                <td class="text-center align-middle">0,07</td>
                                <td class="text-center">
                                    < 7 Orang<br>7 - 8 Orang<br>9 - 10 Orang<br>11 - 12 Orang<br>
                                        > 12 Orang
                                </td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td class="text-center align-middle font-weight-bold" colspan="4"><?= number_format($jumlah_pegawai, 0, ',', '.'); ?> / <?= number_format($jumlah_pelanggan_tahun_ini, 0, ',', '.'); ?> X 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_pegawai, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= $hasil_perhitungan_pegawai; ?></td>
                                <td class="text-center align-middle">0,07</td>
                                <td class="text-center align-middle"><?= number_format($hasil_pegawai, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="12" class="pl-3 font-weight-bold">2. RASIO DIKLAT PEGAWAI</td>
                            </tr>
                            <tr>
                                <td class="pl-4">Jumlah pegawai yang mengikuti diklat dibandingkan dgn jumlah seluruh pegawai
                                    <br>
                                    <br>
                                    <i>Jumlah Pegawai Yang Ikut Diklat / Jumlah Pegawai x 100%</i>
                                </td>
                                <td class="text-center align-middle">0,04</td>
                                <td class="text-center">> 80 %<br>60 % - 80 %<br>40 % - 60 %<br>20 % - 40 %<br>
                                    < 20 %</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($jumlah_diklat, 0, ',', '.'); ?> / <?= number_format($jumlah_pegawai, 0, ',', '.'); ?> x 100 = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_diklat, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= $hasil_perhitungan_diklat; ?></td>
                                <td class="text-center align-middle">0,04</td>
                                <td class="text-center align-middle"><?= number_format($hasil_diklat, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="12" class="pl-3 font-weight-bold">3. RASIO BIAYA DIKLAT DENGAN BIAYA PEGAWAI</td>
                            </tr>
                            <tr>
                                <td class="pl-4"><br><br>
                                    <a>Biaya Diklat / Biaya Pegawai</a>
                                </td>
                                <td class="text-center align-middle">0,04</td>
                                <td class="text-center">> 10 %<br>7.5 % - 10 %<br>5 % - 7.5 %<br>2.5 % - 5 %<br>
                                    < 2.5 %</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($biaya_diklat, 2, ',', '.'); ?> / <?= number_format($biaya_pegawai, 2, ',', '.'); ?> = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_biaya_diklat, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= $hasil_perhitungan_biaya_diklat; ?></td>
                                <td class="text-center align-middle">0,04</td>
                                <td class="text-center align-middle"><?= number_format($hasil_biaya_diklat, 2, ',', '.'); ?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="11" class="text-right">TOTAL</th>
                                <th class="text-center font-weight-bold"><?= number_format($total_hasil_sdm, 2, ',', '.'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>