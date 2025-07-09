<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('evkin_permendagri') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('evkin_permendagri'); ?>" method="get">
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
                        <a href="<?= base_url('evkin_permendagri/cetak_evkin_pupr') ?>"><button class="float-end neumorphic-button"> Permendagri 47 Th 1999</button></a>
                    </div> -->
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('evkin_permendagri/cetak_evkin_pupr') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak Dokumen</button></a>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-12 text-center">
                        <h5><?= strtoupper($title); ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" style="font-size: 12px;">
                        <thead class="text-center">
                            <tr>
                                <th rowspan="2" class="align-middle">INDIKATOR KINERJA & RUMUS</th>
                                <th rowspan="2" class="align-middle">RASIO</th>
                                <th rowspan="2" class="align-middle">NILAI</th>
                                <th colspan="6">TAHUN <?= $tahun_lap; ?></th>
                                <th colspan="6">TAHUN <?= $tahun_lalu; ?></th>
                            </tr>
                            <tr>
                                <th>Perhitungan</th>
                                <th colspan="4">B</th>
                                <th>NILAI</th>
                                <th>Perhitungan</th>
                                <th colspan="4">B</th>
                                <th>NILAI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="20" class="font-weight-bold">I. ASPEK KEUANGAN</td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">1a. Rasio Laba Terhadap Aktiva Produktif <br>Mengukur kemampuan perusahaan menghasilkan laba dari jumlah aset produktif yang dikelola
                                    <br>
                                    <br>
                                    <i>Laba sebelum pajak / Aktiva Produktif X 100%</i>
                                </td>
                                <td class="text-center">> 10%<br>7% - 10%<br>3% - 7%<br>0% - 3%<br>
                                    < 0%</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td class="text-center align-middle font-weight-bold" colspan="4"><?= number_format($lr_sebelum_pajak_ini, 0, ',', '.'); ?> / <?= number_format($aktiva_produktif_ini, 0, ',', '.'); ?> X 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($rasio_laba_ini, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_rasio_laba_ini, 0, ',', '.'); ?></td>
                                <td class="text-center align-middle font-weight-bold" colspan="4"><?= number_format($lr_sebelum_pajak_lalu, 0, ',', '.'); ?> / <?= number_format($aktiva_produktif_lalu, 0, ',', '.'); ?> X 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($rasio_laba_lalu, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_rasio_laba_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">1b. Peningkatan Rasio Laba Terhadap Aktiva Produktif dibanding Tahun Lalu
                                    <br>
                                    <br>
                                    <i>Rasio Laba Terhadap Aktiva Produktif Tahun ini / Rasio Laba Terhadap Aktiva Produktif Tahun Lalu</i>
                                </td>
                                <td class="text-center">> 12%<br>> 9% - 12%<br>> 6% - 9%<br>> 3% - 6%<br>> 0% - 3%</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($rasio_laba_ini, 2, ',', '.'); ?> - <?= number_format($rasio_laba_lalu, 2, ',', '.'); ?> = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($bonus_rasio_laba_ini, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_bonus_rasio_laba_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($rasio_laba_lalu, 2, ',', '.'); ?> - <?= number_format($rasio_laba_2_lalu, 2, ',', '.'); ?> = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($bonus_rasio_laba_lalu, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_bonus_rasio_laba_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">2a. Rasio Laba terhadap Penjualan<br>
                                    <a>Mengukur laba yang dapat dihasilkan dari jumlah penjualan dalam tahun berjalan</a>
                                    <br>
                                    <br>
                                    <i> Laba sebelum pajak / Penjualan(pendapatan Operasi) x 100%</i>
                                </td>
                                <td class="text-center">> 20%<br>14% - 20%<br>6% - 14%<br>0% - 6%<br>
                                    < 0%</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($lr_sebelum_pajak_ini, 0, ',', '.'); ?> / <?= number_format($total_pendapatan_usaha_audited_ini, 0, ',', '.'); ?> x 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($rasio_laba_jual_ini, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_rasio_laba_jual_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($lr_sebelum_pajak_lalu, 0, ',', '.'); ?> / <?= number_format($total_pendapatan_usaha_audited_lalu, 0, ',', '.'); ?> x 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($rasio_laba_jual_lalu, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_rasio_laba_jual_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">2b. Peningkatan Rasio Laba terhadap Penjualan dibanding Tahun Lalu
                                    <br>
                                    <br>
                                    <i>Rasio Laba terhadap Penjualan Tahun ini / Rasio Laba terhadap Penjualan Tahun Lalu</i>
                                </td>
                                <td class="text-center">> 12%<br>> 9% - 12%<br>> 6% - 9%<br>> 3% - 6%<br>> 0% - 3%</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($rasio_laba_jual_ini, 2, ',', '.'); ?> - <?= number_format($rasio_laba_jual_lalu, 2, ',', '.'); ?> =</td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($bonus_rasio_laba_jual_ini, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_bonus_rasio_laba_jual_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($rasio_laba_jual_lalu, 2, ',', '.'); ?> - <?= number_format($rasio_laba_jual_2_lalu, 2, ',', '.'); ?> =</td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($bonus_rasio_laba_jual_lalu, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_bonus_rasio_laba_jual_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">3. Rasio Aktiva Lancar terhadap Utang Lancar<br>
                                    <a>Menilai ketersediaan aset-aset likuid untuk memenuhi kewajiban jk pendek <br> dalam rangka membiayai kegiatan operasi maupun pembayaran hutang dan bunga yg jatuh tempo
                                    </a>
                                    <br><br>
                                    <i> Aktiva Lancar / Hutang Lancar</i>
                                </td>
                                <td class="text-center">> 1,75 - 2<br>>1,5 - 1.75 atau >2 - 2,3<br>1,25 - 1,50 atau 2.3 - 2,7<br>>1,0 - 1,25 atau 2.7 - 3,0<br>
                                    <= 1,0 atau>3,00
                                </td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($total_aset_lancar_audited_ini, 0, ',', '.'); ?> / <?= number_format($total_liabilitas_jangka_pendek_audited_ini, 0, ',', '.'); ?> = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($rasio_aktiva_lancar_ini, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_rasio_aktiva_lancar_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($total_aset_lancar_audited_lalu, 0, ',', '.'); ?> / <?= number_format($total_liabilitas_jangka_pendek_audited_lalu, 0, ',', '.'); ?> = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($rasio_aktiva_lancar_lalu, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_rasio_aktiva_lancar_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">4. Rasio Utang Jangka Panjang terhadap Ekuitas<br>
                                    <a>Menilai keseimbangan diantara dua sumber dana yang digunakan untuk <br> membiayai aset perusahaanya itu modal dan hutang.
                                    </a>
                                    <br><br>
                                    <i> Utang Jangka Panjang / Ekuitas</i>
                                </td>
                                <td class="text-center">
                                    <= 0,5<br>>0,5 - 0,7<br>>0,7 - 0,8<br>>0,8 - 1,0<br>
                                        >1,0
                                </td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($total_hutang_jk_panjang_ini, 0, ',', '.'); ?> / <?= number_format($total_ekuitas_audited_ini, 0, ',', '.'); ?> = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($rasio_hutang_jk_panjang_ini, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_Perhitungan_rasio_hutang_jk_panjang_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($total_hutang_jk_panjang_lalu, 0, ',', '.'); ?> / <?= number_format($total_ekuitas_audited_lalu, 0, ',', '.'); ?> = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($rasio_hutang_jk_panjang_lalu, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_Perhitungan_rasio_hutang_jk_panjang_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">5. Rasio Total Aktiva terhadap Total Utang<br>
                                    <a>Menilai tingkat kecukupan seluruh aset yg tersedia <br> dibandingkan dengan seluruh hutang
                                    </a>
                                    <br><br>
                                    <i> Total Aktiva / Total Utang</i>
                                </td>
                                <td class="text-center">
                                    >2,0<br>>1,7 - 2,0<br>>1,3 - 1,7<br>>1,0 - 1,3<br>
                                    <=1,0 </td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($total_aktiva_ini, 0, ',', '.'); ?> / <?= number_format($total_hutang_ini, 0, ',', '.'); ?> = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($rasio_aktiva_ini, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_rasio_aktiva_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($total_aktiva_lalu, 0, ',', '.'); ?> / <?= number_format($total_hutang_lalu, 0, ',', '.'); ?> = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($rasio_aktiva_lalu, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_rasio_aktiva_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">6. Rasio Biaya Operasi terhadap Pendapatan Operasi<br>
                                    <a>Menilai efisiensi dalam penggunaan sumber dana <br> dan daya untuk menjalankan kegiatan operasional
                                    </a>
                                    <br><br>
                                    <i> Biaya Operasi / Pendapatan Operasi</i>
                                </td>
                                <td class="text-center">
                                    <= 0,5<br>>0,5 - 0,65<br>>0,65 - 0,85<br>>0,85 - 1,0<br>>1,0<br>
                                </td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($total_beban_operasi_ini, 0, ',', '.'); ?> / <?= number_format($total_pendapatan_usaha_audited_ini, 0, ',', '.'); ?> = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($rasio_beban_operasi_ini, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_rasio_beban_operasi_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($total_beban_operasi_lalu, 0, ',', '.'); ?> / <?= number_format($total_pendapatan_usaha_audited_lalu, 0, ',', '.'); ?> = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($rasio_beban_operasi_lalu, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_rasio_beban_operasi_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">7. Rasio Laba Operasi Sebelum Biaya Penyusutan terhadap Angsuran Pokok dan Bunga Jatuh Tempo<br>
                                    <a>Mengukur potensi laba yang dihasilkan dapat memenuhi <br> kewajiban pembayaran angsuran pokok dan bunga yg jatuh tempo
                                    </a>
                                    <br><br>
                                    <i> Laba Operasi Sebelum Biaya Penyusutan / (Angsuran Pokok + Bunga) jatuh tempo</i>
                                </td>
                                <td class="text-center">
                                    >2,0<br>>1,7 - 2,0<br>>1,3 - 1,7<br>>1,0 - 1,3<br>
                                    <=1,0 </td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($total_laba_sebelum_susut_ini, 0, ',', '.'); ?> / <?= number_format(0, 0, ',', '.'); ?> = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($rasio_laba_sbl_susut_ini, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_rasio_laba_sbl_susut_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($total_laba_sebelum_susut_lalu, 0, ',', '.'); ?> / <?= number_format(0, 0, ',', '.'); ?> = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($rasio_laba_sbl_susut_lalu, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_rasio_laba_sbl_susut_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">8. Rasio Aktiva Produktif terhadap Penjualan Air<br>
                                    <a>Mengukur produktifitas aset2 yg tertanam dapat dimanfaatkan untuk <br>menghasilkan pendapatan dalam rangka pengembalian investasi
                                    </a>
                                    <br><br>
                                    <i> Aktiva Produktif / Penjualan Air</i>
                                </td>
                                <td class="text-center">
                                    <= 2,0<br>>2,0 - 4,0<br>>4,0 - 6,0<br>>6,0 - 8,0<br>
                                        >8,0
                                </td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($aktiva_produktif_ini, 0, ',', '.'); ?> / <?= number_format($pendapatan_air_ini, 0, ',', '.'); ?> = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($rasio_pendapatan_air_ini, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_rasio_aktiva_produktif_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($aktiva_produktif_lalu, 0, ',', '.'); ?> / <?= number_format($pendapatan_air_lalu, 0, ',', '.'); ?> = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($rasio_pendapatan_air_lalu, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_rasio_aktiva_produktif_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">9. Jangka Waktu Penagihan Piutang<br>
                                    <a>Menilai efektifitas dari upaya manajemen dalam pengendalian piutang menjadi kas.
                                    </a>
                                    <br><br>
                                    <i> Piutang Usaha / Jumlah penjualan per hari</i>
                                </td>
                                <td class="text-center">
                                    <= 60<br>>60 - 90<br>>90 - 150<br>>150 - 180<br>
                                        >180
                                </td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($piutang_usaha_ini, 0, ',', '.'); ?> / (<?= number_format($total_pendapatan_usaha_audited_ini, 0, ',', '.'); ?> / 365 hari) = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($jangka_waktu_tagih_ini, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_penagihan_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($piutang_usaha_lalu, 0, ',', '.'); ?> / (<?= number_format($total_pendapatan_usaha_audited_lalu, 0, ',', '.'); ?> / 365 hari) = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($jangka_waktu_tagih_lalu, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_penagihan_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">10. Efektivitas Penagihan<br>
                                    <a>Menilai prosentase piutang tertagih menjadi kas
                                    </a>
                                    <br><br>
                                    <i> Rekening tertagih / Penjualan Air X 100%</i>
                                </td>
                                <td class="text-center">
                                    >90%<br>>85% - 90%<br>>>80% - 85%<br>>75% - 80%<br>
                                    <=75% </td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($rek_tagih_ini, 0, ',', '.'); ?> / <?= number_format($total_pa_tahun_ini, 0, ',', '.'); ?> x 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_efek_ini, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_efek_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($rek_tagih_lalu, 0, ',', '.'); ?> / <?= number_format($total_pa_tahun_lalu, 0, ',', '.'); ?> x 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($persen_efek_lalu, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_efek_lalu, 0, ',', '.'); ?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">Jumlah Nilai yang diperoleh</th>
                                <th colspan="6"> </th>
                                <th class="text-center font-weight-bold"><?= number_format($total_hasil_keuangan_ini, 0, ',', '.'); ?></th>
                                <th colspan="5" class="text-right"></th>
                                <th class="text-center font-weight-bold"><?= number_format($total_hasil_keuangan_lalu, 0, ',', '.'); ?></th>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-right">Nilai Kinerja Aspek Keuangan</th>
                                <th colspan="6" class="text-center font-weight-bold"><?= number_format($total_hasil_keuangan_ini, 0, ',', '.'); ?> X 45 / 60</th>
                                <th class="text-center font-weight-bold"><?= number_format($nilai_kinerja_keuangan_ini, 2, ',', '.'); ?></th>
                                <th colspan="5" class="text-center font-weight-bold"><?= number_format($total_hasil_keuangan_lalu, 0, ',', '.'); ?> X 45 / 60</th>
                                <th class="text-center font-weight-bold"><?= number_format($nilai_kinerja_keuangan_lalu, 2, ',', '.'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" style="font-size: 12px;">
                        <thead class="text-center">
                            <tr>
                                <th rowspan="2" class="align-middle">INDIKATOR KINERJA & RUMUS</th>
                                <th rowspan="2" class="align-middle">RASIO</th>
                                <th rowspan="2" class="align-middle">NILAI</th>
                                <th colspan="6">TAHUN <?= $tahun_lap; ?></th>
                                <th colspan="6">TAHUN <?= $tahun_lalu; ?></th>
                            </tr>
                            <tr>
                                <th>Perhitungan</th>
                                <th colspan="4">B</th>
                                <th>NILAI</th>
                                <th>Perhitungan</th>
                                <th colspan="4">B</th>
                                <th>NILAI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="20" class="font-weight-bold">II. ASPEK OPERASIONAL</td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">1a. Cakupan Pelayanan <br>
                                    <a>Mengukur seberapa banyak penduduk yang dilayani PDAM <br> dan sebagai gambaran kemampuan PDAM dalam menjalankan fungsi pelayanannya</a>
                                    <br>
                                    <br>
                                    <i>Jumlah Penduduk Terlayani / Jumlah Penduduk X 100%</i>
                                </td>
                                <td class="text-center">> 60%<br>45% - 60%<br>30% - 45%<br>15% - 30%<br>
                                    <= 15%</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td class="text-center align-middle font-weight-bold" colspan="4"><?= number_format($jml_plgn_terlayani_ini, 0, ',', '.'); ?> / <?= number_format($jumlah_penduduk_ini, 0, ',', '.'); ?> X 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($rasio_cak_layanan_ini, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_rasio_cak_layanan_ini, 0, ',', '.'); ?></td>
                                <td class="text-center align-middle font-weight-bold" colspan="4"><?= number_format($jml_plgn_terlayani_lalu, 0, ',', '.'); ?> / <?= number_format($jumlah_penduduk_lalu, 0, ',', '.'); ?> X 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($rasio_cak_layanan_lalu, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_rasio_cak_layanan_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">1b. Peningkatan Cakupan layanan <br>
                                    <a>Bila berhasil meningkatkan cakupan pelayanan <br> dibandingkan tahun sebelumnya diberikan tambahan nilai</a>
                                    <br>
                                    <br>
                                    <i>Cakupan Pelayanan th ini - Cakupan Pelayanan th lalu</i>
                                </td>
                                <td class="text-center">> 8%<br>> 6% - 8%<br>> 4% - 6%<br>> 2% - 4%<br>> 0% - 2%</td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($rasio_cak_layanan_ini, 2, ',', '.'); ?> - <?= number_format($rasio_cak_layanan_lalu, 2, ',', '.'); ?> = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($bonus_rasio_cak_layanan_ini, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_bonus_perhitungan_rasio_cak_layanan_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($rasio_cak_layanan_lalu, 2, ',', '.'); ?> - <?= number_format($rasio_cak_layanan_2_lalu, 2, ',', '.'); ?> = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($bonus_rasio_cak_layanan_lalu, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_bonus_perhitungan_rasio_cak_layanan_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">2. Kualitas Air Distribusi<br>
                                    <a>Pemenuhan syarat yg ditetapkan instansi berwenang <br> mengenai kualitas air yg dikonsumsi masyarakat </a>
                                    <br>
                                    <br>
                                </td>
                                <td class="text-center">Memenuhi syarat air minum <br>Memenuhi syarat air bersih<br>Tidak memenuhi syarat</td>
                                <td class="text-center">3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $kualitas_air_ini; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_kualitas_air_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $kualitas_air_lalu; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_kualitas_air_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">3. Kontinuitas Air<br>
                                    <a>Menilai kesinambungan air mengalir di pelanggan, <br> mendapat aliran air secara penuh atau tidak
                                    </a>
                                    <br><br>
                                </td>
                                <td class="text-center">semua pelanggan mendapat aliran air 24 jam<br>
                                    belum semua pelanggan mendapat aliran air 24 jam
                                </td>
                                <td class="text-center">2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $kontinuitas_air_ini; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= $hasil_kontinuitas_air_ini; ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $kontinuitas_air_lalu; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= $hasil_kontinuitas_air_lalu; ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">4. Produktivitas Pemanfaatan Instalasi Produksi
                                    <br><br>
                                    <i> Kapasitas Produksi / Kapasitas Terpasang</i>
                                </td>
                                <td class="text-center">
                                    >90 %<br>>80 % - 90 %<br>>70 % - 80 %<br>
                                    <= 70 % </td>
                                <td class="text-center">4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($kap_riil_ini, 0, ',', '.'); ?> / <?= number_format($terpasang_ini, 0, ',', '.'); ?> X 100 %= </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($nilai_kapasitas_ini, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_nilai_kapasitas_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($kap_riil_lalu, 0, ',', '.'); ?> / <?= number_format($terpasang_lalu, 0, ',', '.'); ?> X 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($nilai_kapasitas_lalu, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_nilai_kapasitas_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">5. Tingkat Kehilangan Air
                                    <br><br>
                                    <i> Jumlah M3 distribusi air - terjual / Jumlah M3 distribusi air * 100 %</i>
                                </td>
                                <td class="text-center">
                                    <=20 %<br>>20 % - 30 %<br>>30 % - 40 %<br>
                                        > 40 %
                                </td>
                                <td class="text-center">4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($total_nrw_ini, 0, ',', '.'); ?> / <?= number_format($total_volume_produksi_ini, 0, ',', '.'); ?> X 100 %= </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($nilai_nrw_ini, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_nilai_nrw_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($total_nrw_lalu, 0, ',', '.'); ?> / <?= number_format($total_volume_produksi_lalu, 0, ',', '.'); ?> X 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($nilai_nrw_lalu, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_nilai_nrw_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">6. Peneraan Meter Air<br>
                                    <a>Dalam setahun, seberapa banyak PDAM melakukan <br> peneraan meter air pelanggannya tidak termasuk meter air yang baru
                                    </a> <br><br>
                                    <i> Jumlah Pelanggan yang Ditera / Jumlah Seluruh pelanggan * 100</i>
                                </td>
                                <td class="text-center">
                                    >20 % - 25 %<br>> 10 % - 20 %<br>>0 % - 10 % atau > 25 %<br>
                                </td>
                                <td class="text-center">3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($water_meter_ini, 0, ',', '.'); ?> / <?= number_format($total_pelanggan_ini, 0, ',', '.'); ?> X 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($nilai_peneraan_wm_ini, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_nilai_peneraan_wm_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($water_meter_lalu, 0, ',', '.'); ?> / <?= number_format($total_pelanggan_lalu, 0, ',', '.'); ?> 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($nilai_peneraan_wm_lalu, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_nilai_peneraan_wm_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">7. Kecepatan Penyambungan Baru<br>
                                    <a>Lamanya waktu yang dibutuhkan calon pelanggan <br> dari pembayaran s.d penyambungan
                                    </a>
                                    <br><br>
                                </td>
                                <td class="text-center">
                                    <= 6 hari kerja<br>
                                        > 6 hari kerja
                                </td>
                                <td class="text-center">2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $sr_baru_ini; ?> </td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_sr_baru_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $sr_baru_lalu; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_sr_baru_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">8. Kemampuan Penanganan Pengaduan rata2 per bulan<br>
                                    <a>Kemampuan PDAM menyelesaikan pengaduan2 pelanggan
                                    </a>
                                    <br><br>
                                    <i> Jumlah Pengaduan Ditangani / Jumlah seluruh pengaduan * 100</i>
                                </td>
                                <td class="text-center">
                                    >= 80 % <br>
                                    < 80 % </td>
                                <td class="text-center">2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($total_aduan_ya_ini, 0, ',', '.'); ?> / <?= number_format($total_aduan_ini, 0, ',', '.'); ?> X 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($nilai_aduan_ini, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_nilai_aduan_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format($total_aduan_ya_lalu, 0, ',', '.'); ?> / <?= number_format($total_aduan_lalu, 0, ',', '.'); ?> X 100 % = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($nilai_aduan_lalu, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_nilai_aduan_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">9. Kemudahan Pelayanan<br>
                                    <a>Tersedianya sarana penunjang dalam rangka memberikan kemudahan pelayanan, <br> baik untuk melakukan pembayaran maupun pengaduan
                                    </a>
                                    <br><br>
                                </td>
                                <td class="text-center">
                                    Tersedia<br>
                                    Tidak Tersedia
                                </td>
                                <td class="text-center"> <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $service_point_ini; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_service_point_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $service_point_lalu; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_service_point_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">10. Rasio Karyawan per 1000 pelanggan
                                    <br><br>
                                    <i> Jumlah karyawan / Jumlah Pelanggan * 1000</i>
                                </td>
                                <td class="text-center">
                                    <= 8<br>>8 - 11<br>>11 - 15<br>>15 - 18<br>
                                        >18
                                </td>
                                <td class="text-center">5 <br>4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format(163, 0, ',', '.'); ?> / <?= number_format($total_pelanggan_aktif_ini, 0, ',', '.'); ?> x 1000 = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($nilai_rasio_kyw_ini, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_nilai_rasio_kyw_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= number_format(162, 0, ',', '.'); ?> / <?= number_format($total_pelanggan_aktif_lalu, 0, ',', '.'); ?> x 1000 = </td>
                                <td class="text-center align-middle font-weight-bold"><?= number_format($nilai_rasio_kyw_lalu, 2, ',', '.'); ?></td>
                                <td class="text-center align-middle"><?= number_format($hasil_perhitungan_nilai_rasio_kyw_lalu, 0, ',', '.'); ?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">Jumlah Nilai yang diperoleh</th>
                                <th colspan="6"> </th>
                                <th class="text-center font-weight-bold"><?= number_format($total_aspek_ops_ini, 0, ',', '.'); ?></th>
                                <th colspan="5" class="text-right"></th>
                                <th class="text-center font-weight-bold"><?= number_format($total_aspek_ops_lalu, 0, ',', '.'); ?></th>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-right">Nilai Kinerja Aspek Operasional</th>
                                <th colspan="6" class="text-center font-weight-bold"><?= number_format($total_aspek_ops_ini, 0, ',', '.'); ?> X 40 / 47</th>
                                <th class="text-center font-weight-bold"><?= number_format($nilai_kinerja_aspek_ops_ini, 2, ',', '.'); ?></th>
                                <th colspan="5" class="text-center font-weight-bold"><?= number_format($total_aspek_ops_lalu, 0, ',', '.'); ?> X 45 / 60</th>
                                <th class="text-center font-weight-bold"><?= number_format($nilai_kinerja_aspek_ops_lalu, 2, ',', '.'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" style="font-size: 12px;">
                        <thead class="text-center">
                            <tr>
                                <th rowspan="2" class="align-middle">INDIKATOR KINERJA & RUMUS</th>
                                <th rowspan="2" class="align-middle">RASIO</th>
                                <th rowspan="2" class="align-middle">NILAI</th>
                                <th colspan="6">TAHUN <?= $tahun_lap; ?></th>
                                <th colspan="6">TAHUN <?= $tahun_lalu; ?></th>
                            </tr>
                            <tr>
                                <th>Perhitungan</th>
                                <th colspan="4">B</th>
                                <th>NILAI</th>
                                <th>Perhitungan</th>
                                <th colspan="4">B</th>
                                <th>NILAI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="20" class="font-weight-bold">III. ASPEK ADMINISTRASI</td>
                            <tr>
                                <td class="pl-4 fw-bold">1. Rencana Jangka Panjang<br>
                                    <a>Untuk melihat sejauh mana Perencanaan Jangka Panjang <br> (Corporate Plan) dipedomani </a>
                                    <br>
                                    <br>
                                </td>
                                <td class="text-center">Sepenuhnya dipedomani <br> Dipedomani sebagian <br>Memiliki, belum dipedomani<br>Tidak memiliki</td>
                                <td class="text-center">4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $rjp_ini; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_rjp_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $rjp_lalu; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_rjp_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">2. Rencana Organisasi dan Uraian Tugas<br>
                                    <a>Pelaksanaan Rencana/Struktur Organisani dan Uraian Tugas, <br> sejauhmana dipedomani
                                    </a>
                                    <br><br>
                                </td>
                                <td class="text-center">Sepenuhnya dipedomani <br> Dipedomani sebagian <br>Memiliki, belum dipedomani<br>Tidak memiliki</td>
                                <td class="text-center">4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $rodut_ini; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= $hasil_rodut_ini; ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $rodut_lalu; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= $hasil_rodut_lalu; ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">3. Prosedur Operasi Standar<br>
                                    <a>Pelaksanaan Operasi Standar sebagai panduan yang <br> mencakup prosedur penanganan operasi perusahaan, <br> sejauhmana dipedomani</a>
                                    <br>
                                    <br>
                                </td>
                                <td class="text-center">Sepenuhnya dipedomani <br> Dipedomani sebagian <br>Memiliki, belum dipedomani<br>Tidak memiliki</td>
                                <td class="text-center">4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $pos_ini; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_pos_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $pos_lalu; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_pos_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">4. Gambar Nyata Laksana<br>
                                    <a>Untuk melihat sampai sejauhmana Gambar Nyata Laksana <br> (As Built Drawing) disediakan dan dipedomani sebagai <br> alat manajemen pelaksanaan produksi dan distribusi <br> secara baik</a>
                                    <br>
                                    <br>
                                </td>
                                <td class="text-center">Sepenuhnya dipedomani <br> Dipedomani sebagian <br>Memiliki, belum dipedomani<br>Tidak memiliki</td>
                                <td class="text-center">4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $gnl_ini; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_gnl_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $gnl_lalu; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_gnl_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">5. Pedoman Penilaian Kerja Karyawan<br>
                                    <a>Pelaksanaan penilaian kerja karyawan dalam rangka <br>penentuan karir dan gaji, sejauh mana dipedomani</a>
                                    <br>
                                    <br>
                                </td>
                                <td class="text-center">Sepenuhnya dipedomani <br> Dipedomani sebagian <br>Memiliki, belum dipedomani<br>Tidak memiliki</td>
                                <td class="text-center">4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $ppkk_ini; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_ppkk_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $ppkk_lalu; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_ppkk_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">6. R K A P (Rencana Kerja dan Anggaran Perusahaan)<br>
                                    <a>Pelaksanaan RKAP sejauh mana dipedomani </a>
                                    <br>
                                    <br>
                                </td>
                                <td class="text-center">Sepenuhnya dipedomani <br> Dipedomani sebagian <br>Memiliki, belum dipedomani<br>Tidak memiliki</td>
                                <td class="text-center">4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $rkdap_ini; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_rkdap_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $rkdap_lalu; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_rkdap_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">7. Tertib Laporan Internal<br>
                                    <a>Dilaksanakannya pelaporan di bidang keuangan, <br> adminitrasi dan ops tehnik secara berkala dari pelaksana, <br> kepada pengambil keputusan</a>
                                    <br>
                                    <br>
                                </td>
                                <td class="text-center">Dibuat tepat waktu <br> Tidak tepat waktu</td>
                                <td class="text-center">2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $tli_ini; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_tli_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $tli_lalu; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_tli_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">8. Tertib Laporan Eksternal<br>
                                    <a>Penyampaian laporan2 untuk pihak ekstern secara periodik</a>
                                    <br>
                                    <br>
                                </td>
                                <td class="text-center">Dibuat tepat waktu <br> Tidak tepat waktu</td>
                                <td class="text-center">2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $tle_ini; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_tle_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $tle_lalu; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_tle_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">9. Opini Auditor Independen<br>
                                    <a>Opini pemeriksa independen mengenai kewajiban laporan <br> keuangan yang disajikan oleh manajemen </a>
                                    <br>
                                    <br>
                                </td>
                                <td class="text-center">Wajar tanpa pengecualian<br> Wajar dengan pengecualian <br>Tidak memberikan pendapat<br>Pendapat tidak wajar</td>
                                <td class="text-center">4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $oai_ini; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_oai_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $oai_lalu; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_oai_lalu, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td class="pl-4 fw-bold">10. Tidak Lanjut Hasil Pemeriksaan tahun terakhir<br>
                                    <a>Hasil pencapaian upaya tindak lanjut temuan/rekomendasi <br> oleh Instansi Pemeriksa </a>
                                    <br>
                                    <br>
                                </td>
                                <td class="text-center">Tidak ada temuan<br> Ditindaklanjuti, seluruhnya selesai <br>Ditindaklanjuti, sebagian selesai<br>Tidak ditindaklanjuti</td>
                                <td class="text-center">4 <br>3 <br>2 <br>1</td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $tlhptt_ini; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_tlhptt_ini, 0, ',', '.'); ?></td>
                                <td colspan="4" class="text-center align-middle font-weight-bold"><?= $tlhptt_lalu; ?></td>
                                <td class="text-center align-middle font-weight-bold"></td>
                                <td class="text-center align-middle"><?= number_format($hasil_tlhptt_lalu, 0, ',', '.'); ?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">Jumlah Nilai yang diperoleh</th>
                                <th colspan="6"> </th>
                                <th class="text-center font-weight-bold"><?= number_format($total_ini, 0, ',', '.'); ?></th>
                                <th colspan="5" class="text-right"></th>
                                <th class="text-center font-weight-bold"><?= number_format($total_lalu, 0, ',', '.'); ?></th>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-right">Nilai Kinerja Aspek Administrasi</th>
                                <th colspan="6" class="text-center font-weight-bold"><?= number_format($total_ini, 0, ',', '.'); ?> X 15 / 36</th>
                                <th class="text-center font-weight-bold"><?= number_format($nilai_kinerja_adm_ini, 2, ',', '.'); ?></th>
                                <th colspan="5" class="text-center font-weight-bold"><?= number_format($total_lalu, 0, ',', '.'); ?> X 15 / 36</th>
                                <th class="text-center font-weight-bold"><?= number_format($nilai_kinerja_adm_lalu, 2, ',', '.'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>