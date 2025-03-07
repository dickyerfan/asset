<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('lap_keuangan/perubahan_ekuitas'); ?>" method="get">
                        <div style="display: flex; align-items: center;">
                            <input type="submit" value="Pilih Tahun" class="neumorphic-button">
                            <!-- <input type="date" id="tahun" name="tahun" class="form-control" style="margin-left: 10px;"> -->
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
                    <a href="<?= base_url('lap_keuangan/perubahan_ekuitas') ?>" style="text-decoration: none;"><button class="neumorphic-button ms-2"> Tahun ini</button></a>
                    <div class="navbar-nav ms-auto ">
                        <a href="<?= base_url('lap_keuangan/perubahan_ekuitas_cetak'); ?>" target="_blank"><button class=" neumorphic-button float-right"><i class="fas fa-print"></i> Cetak</button></a>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-12 text-center">
                        <h5><strong><?= strtoupper($title); ?></strong></h5>
                        <h5><strong>Untuk tahun buku yang berakhir Per 31 Desember <?= $tahun_lap ?> & <?= $tahun_lalu ?></strong></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <?php
                    function get_nilai($data, $akun)
                    {
                        foreach ($data as $row) {
                            if ($row->akun == $akun) {
                                return $row->nilai_neraca;
                            }
                        }
                        return 0;
                    }
                    ?>
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Uraian</th>
                                <th>Penyertaan Modal Pemerintah Daerah</th>
                                <th>Penyertaan Modal Pemerintah yang Belum Ditetapkan</th>
                                <th>Hibah</th>
                                <th>Cadangan Umum</th>
                                <th>Pengukuran Kembali Imbalan Paska Kerja</th>
                                <th>Akumulasi Kerugian</th>
                                <th>Saldo Laba/Rugi Bersih</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Saldo Per 31 Desember <?= $dua_tahun_lalu ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_dua_tahun_lalu, 'Penyertaan Pemda Yang Dipisahkan'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_dua_tahun_lalu, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_dua_tahun_lalu, 'Modal Hibah'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_dua_tahun_lalu, 'Cadangan Umum'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_dua_tahun_lalu, 'Pengukuran Kembali Imbalan Paska Kerja'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_dua_tahun_lalu, 'Akm Kerugian Tahun Lalu'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_dua_tahun_lalu, 'Laba Rugi Tahun Berjalan'), 0, ',', '.') ?></td>
                                <td class="text-right">
                                    <?= number_format(
                                        get_nilai($ekuitas_dua_tahun_lalu, 'Penyertaan Pemda Yang Dipisahkan') +
                                            get_nilai($ekuitas_dua_tahun_lalu, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status') +
                                            get_nilai($ekuitas_dua_tahun_lalu, 'Modal Hibah') +
                                            get_nilai($ekuitas_dua_tahun_lalu, 'Cadangan Umum') +
                                            get_nilai($ekuitas_dua_tahun_lalu, 'Pengukuran Kembali Imbalan Paska Kerja') +
                                            get_nilai($ekuitas_dua_tahun_lalu, 'Akm Kerugian Tahun Lalu') +
                                            get_nilai($ekuitas_dua_tahun_lalu, 'Laba Rugi Tahun Berjalan'),
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>
                            </tr>
                            <tr>

                                <td>2</td>
                                <td>Laba Tahun Berjalan <?= $tahun_lalu ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Saldo Per 31 Desember <?= $tahun_lalu ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_lalu, 'Penyertaan Pemda Yang Dipisahkan'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_lalu, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_lalu, 'Modal Hibah'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_lalu, 'Cadangan Umum'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_lalu, 'Pengukuran Kembali Imbalan Paska Kerja'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_lalu, 'Akm Kerugian Tahun Lalu'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_lalu, 'Laba Rugi Tahun Berjalan'), 0, ',', '.') ?></td>
                                <td class="text-right">
                                    <?= number_format(
                                        get_nilai($ekuitas_tahun_lalu, 'Penyertaan Pemda Yang Dipisahkan') +
                                            get_nilai($ekuitas_tahun_lalu, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status') +
                                            get_nilai($ekuitas_tahun_lalu, 'Modal Hibah') +
                                            get_nilai($ekuitas_tahun_lalu, 'Cadangan Umum') +
                                            get_nilai($ekuitas_tahun_lalu, 'Pengukuran Kembali Imbalan Paska Kerja') +
                                            get_nilai($ekuitas_tahun_lalu, 'Akm Kerugian Tahun Lalu') +
                                            get_nilai($ekuitas_tahun_lalu, 'Laba Rugi Tahun Berjalan'),
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Pembagian Laba :</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Saldo Per 31 Desember <?= $tahun_lap ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_ini, 'Penyertaan Pemda Yang Dipisahkan'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_ini, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_ini, 'Modal Hibah'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_ini, 'Cadangan Umum'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_ini, 'Pengukuran Kembali Imbalan Paska Kerja'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_ini, 'Akm Kerugian Tahun Lalu'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_ini, 'Laba Rugi Tahun Berjalan'), 0, ',', '.') ?></td>
                                <td class="text-right">
                                    <?= number_format(
                                        get_nilai($ekuitas_tahun_ini, 'Penyertaan Pemda Yang Dipisahkan') +
                                            get_nilai($ekuitas_tahun_ini, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status') +
                                            get_nilai($ekuitas_tahun_ini, 'Modal Hibah') +
                                            get_nilai($ekuitas_tahun_ini, 'Cadangan Umum') +
                                            get_nilai($ekuitas_tahun_ini, 'Pengukuran Kembali Imbalan Paska Kerja') +
                                            get_nilai($ekuitas_tahun_ini, 'Akm Kerugian Tahun Lalu') +
                                            get_nilai($ekuitas_tahun_ini, 'Laba Rugi Tahun Berjalan'),
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>