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
                                <td></td>
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
                            <?php
                            $nilai_ppyd_dua_tahun_lalu = get_nilai($ekuitas_tahun_lalu, 'Penyertaan Pemda Yang Dipisahkan') - get_nilai($ekuitas_dua_tahun_lalu, 'Penyertaan Pemda Yang Dipisahkan');
                            $nilai_ppybds_dua_tahun_lalu = get_nilai($ekuitas_tahun_lalu, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status') - get_nilai($ekuitas_dua_tahun_lalu, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status');
                            $nilai_mh_dua_tahun_lalu = get_nilai($ekuitas_tahun_lalu, 'Modal Hibah') - get_nilai($ekuitas_dua_tahun_lalu, 'Modal Hibah');
                            $nilai_cu_dua_tahun_lalu = get_nilai($ekuitas_tahun_lalu, 'Cadangan Umum') - get_nilai($ekuitas_dua_tahun_lalu, 'Cadangan Umum');
                            $nilai_pkipk_dua_tahun_lalu = get_nilai($ekuitas_tahun_lalu, 'Pengukuran Kembali Imbalan Paska Kerja') - get_nilai($ekuitas_dua_tahun_lalu, 'Pengukuran Kembali Imbalan Paska Kerja');
                            $nilai_aktl_dua_tahun_lalu = get_nilai($ekuitas_tahun_lalu, 'Akm Kerugian Tahun Lalu') - get_nilai($ekuitas_dua_tahun_lalu, 'Akm Kerugian Tahun Lalu');
                            $nilai_lb_dua_tahun_lalu = get_nilai($ekuitas_tahun_lalu, 'Laba Rugi Tahun Berjalan') - get_nilai($ekuitas_tahun_lalu, 'Laba Rugi Tahun Berjalan');
                            $nilai_total_dua_tahun_lalu = $nilai_ppyd_dua_tahun_lalu + $nilai_ppybds_dua_tahun_lalu + $nilai_mh_dua_tahun_lalu + $nilai_cu_dua_tahun_lalu + $nilai_pkipk_dua_tahun_lalu + $nilai_aktl_dua_tahun_lalu + $nilai_lb_dua_tahun_lalu;
                            ?>
                            <tr>
                                <td></td>
                                <td>Penambahan</td>
                                <td class="text-right"><?= number_format($nilai_ppyd_dua_tahun_lalu, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_ppybds_dua_tahun_lalu, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_mh_dua_tahun_lalu, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_cu_dua_tahun_lalu, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_pkipk_dua_tahun_lalu, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_aktl_dua_tahun_lalu, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_lb_dua_tahun_lalu, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_total_dua_tahun_lalu, 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Pengurangan</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_dua_tahun_lalu, 'Laba Rugi Tahun Berjalan'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_dua_tahun_lalu, 'Laba Rugi Tahun Berjalan'), 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Laba Rugi Tahun Berjalan</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_lalu, 'Laba Rugi Tahun Berjalan'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_lalu, 'Laba Rugi Tahun Berjalan'), 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td></td>
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
                            <?php
                            $nilai_ppyd_tahun_ini = get_nilai($ekuitas_tahun_ini, 'Penyertaan Pemda Yang Dipisahkan') - get_nilai($ekuitas_tahun_lalu, 'Penyertaan Pemda Yang Dipisahkan');
                            $nilai_ppybds_tahun_ini = get_nilai($ekuitas_tahun_ini, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status') - get_nilai($ekuitas_tahun_lalu, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status');
                            $nilai_mh_tahun_ini = get_nilai($ekuitas_tahun_ini, 'Modal Hibah') - get_nilai($ekuitas_tahun_lalu, 'Modal Hibah');
                            $nilai_cu_tahun_ini = get_nilai($ekuitas_tahun_ini, 'Cadangan Umum') - get_nilai($ekuitas_tahun_lalu, 'Cadangan Umum');
                            $nilai_pkipk_tahun_ini = get_nilai($ekuitas_tahun_ini, 'Pengukuran Kembali Imbalan Paska Kerja') - get_nilai($ekuitas_tahun_lalu, 'Pengukuran Kembali Imbalan Paska Kerja');
                            $nilai_aktl_tahun_ini = get_nilai($ekuitas_tahun_ini, 'Akm Kerugian Tahun Lalu') - get_nilai($ekuitas_tahun_lalu, 'Akm Kerugian Tahun Lalu');
                            $nilai_lb_tahun_ini = get_nilai($ekuitas_tahun_ini, 'Laba Rugi Tahun Berjalan') - get_nilai($ekuitas_tahun_ini, 'Laba Rugi Tahun Berjalan');
                            $nilai_total_tahun_ini = $nilai_ppyd_tahun_ini + $nilai_ppybds_tahun_ini + $nilai_mh_tahun_ini + $nilai_cu_tahun_ini + $nilai_pkipk_tahun_ini + $nilai_aktl_tahun_ini + $nilai_lb_tahun_ini;
                            ?>
                            <tr>
                                <td></td>
                                <td>Penambahan</td>
                                <td class="text-right"><?= number_format($nilai_ppyd_tahun_ini, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_ppybds_tahun_ini, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_mh_tahun_ini, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_cu_tahun_ini, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_pkipk_tahun_ini, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_aktl_tahun_ini, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_lb_tahun_ini, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_total_tahun_ini, 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Pengurangan</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_lalu, 'Laba Rugi Tahun Berjalan'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_lalu, 'Laba Rugi Tahun Berjalan'), 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Laba Rugi Tahun Berjalan</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_ini, 'Laba Rugi Tahun Berjalan'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai($ekuitas_tahun_ini, 'Laba Rugi Tahun Berjalan'), 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td></td>
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
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-12 text-center">
                        <h5><strong><?= strtoupper($title2); ?></strong></h5>
                        <h5><strong>Untuk tahun buku yang berakhir Per 31 Desember <?= $tahun_lap ?> & <?= $tahun_lalu ?></strong></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <?php
                    function get_nilai_audited($data, $akun)
                    {
                        foreach ($data as $row) {
                            if ($row->akun == $akun) {
                                return $row->nilai_neraca_audited;
                            }
                        }
                        return 0;
                    }
                    ?>
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>Uraian</th>
                                <th>Penyertaan Modal Pemerintah Daerah</th>
                                <th>Penyertaan Modal Pemerintah Pusat yang Belum Ditetapkan</th>
                                <th>Modal Hibah</th>
                                <th>Cadangan Umum</th>
                                <th>Pengukuran Kembali Imbalan Paska Kerja</th>
                                <th>Saldo Laba/Rugi Bersih</th>
                                <th>Jumlah Ekuitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $nilai_saldo_laba_dua_tahun_lalu = get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Akm Kerugian Tahun Lalu') + get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Laba Rugi Tahun Berjalan');
                            ?>
                            <tr>
                                <td>Saldo Per 31 Desember <?= $dua_tahun_lalu ?></td>
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Penyertaan Pemda Yang Dipisahkan'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Modal Hibah'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Cadangan Umum'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Pengukuran Kembali Imbalan Paska Kerja'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_saldo_laba_dua_tahun_lalu, 0, ',', '.') ?></td>
                                <td class="text-right">
                                    <?= number_format(
                                        get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Penyertaan Pemda Yang Dipisahkan') +
                                            get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status') +
                                            get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Modal Hibah') +
                                            get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Cadangan Umum') +
                                            get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Pengukuran Kembali Imbalan Paska Kerja') +
                                            get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Akm Kerugian Tahun Lalu') +
                                            get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Laba Rugi Tahun Berjalan'),
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>
                            </tr>
                            <?php
                            $nilai_ppyd_dua_tahun_lalu = get_nilai_audited($ekuitas_tahun_lalu_audited, 'Penyertaan Pemda Yang Dipisahkan') - get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Penyertaan Pemda Yang Dipisahkan');
                            $nilai_ppybds_dua_tahun_lalu = get_nilai_audited($ekuitas_tahun_lalu_audited, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status') - get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status');
                            $nilai_mh_tahun_dua_lalu = get_nilai_audited($ekuitas_tahun_lalu_audited, 'Modal Hibah') - get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Modal Hibah');
                            $nilai_cu_dua_tahun_lalu = get_nilai_audited($ekuitas_tahun_lalu_audited, 'Cadangan Umum') - get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Cadangan Umum');
                            $nilai_pkipk_dua_tahun_lalu = get_nilai_audited($ekuitas_tahun_lalu_audited, 'Pengukuran Kembali Imbalan Paska Kerja') - get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Pengukuran Kembali Imbalan Paska Kerja');
                            $nilai_aktl_tahun_lalu = get_nilai_audited($ekuitas_tahun_lalu_audited, 'Akm Kerugian Tahun Lalu') - get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Akm Kerugian Tahun Lalu');
                            $nilai_lb_dua_tahun_lalu = get_nilai_audited($ekuitas_dua_tahun_lalu_audited, 'Laba Rugi Tahun Berjalan') * -1;
                            $nilai_total_dua_tahun_lalu = $nilai_ppyd_dua_tahun_lalu + $nilai_ppybds_dua_tahun_lalu + $nilai_mh_tahun_dua_lalu + $nilai_cu_dua_tahun_lalu + $nilai_pkipk_dua_tahun_lalu + $nilai_lb_dua_tahun_lalu;
                            ?>
                            <tr>
                                <td>Mutasi Penambahan(Pengurangan)</td>
                                <td class="text-right"><?= number_format($nilai_ppyd_dua_tahun_lalu, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_ppybds_dua_tahun_lalu, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_mh_tahun_dua_lalu, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_cu_dua_tahun_lalu, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_pkipk_dua_tahun_lalu, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_lb_dua_tahun_lalu, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_total_dua_tahun_lalu, 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td>Akumulasi Kerugian</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <!-- <td></td> -->
                                <td class="text-right"><?= number_format($nilai_aktl_tahun_lalu, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_aktl_tahun_lalu, 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td>Pengukuran Kembali Imbalan Kerja</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"><?= number_format($nilai_pkipk_dua_tahun_lalu, 0, ',', '.') ?></td>
                                <td></td>
                                <td class="text-right"><?= number_format($nilai_pkipk_dua_tahun_lalu, 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td>Laba Rugi Tahun Berjalan</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <!-- <td></td> -->
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_tahun_lalu_audited, 'Laba Rugi Tahun Berjalan'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_tahun_lalu_audited, 'Laba Rugi Tahun Berjalan'), 0, ',', '.') ?></td>
                            </tr>
                            <?php
                            $nilai_saldo_laba_tahun_lalu = get_nilai_audited($ekuitas_tahun_lalu_audited, 'Akm Kerugian Tahun Lalu') + get_nilai_audited($ekuitas_tahun_lalu_audited, 'Laba Rugi Tahun Berjalan');

                            // $nilai_saldo_laba_tahun_lalu = $nilai_saldo_laba_dua_tahun_lalu + $nilai_lb_dua_tahun_lalu + get_nilai_audited($ekuitas_tahun_lalu_audited, 'Laba Rugi Tahun Berjalan') + $nilai_aktl_tahun_lalu;
                            ?>
                            <tr>
                                <td>Saldo Per 31 Desember <?= $tahun_lalu ?></td>
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_tahun_lalu_audited, 'Penyertaan Pemda Yang Dipisahkan'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_tahun_lalu_audited, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_tahun_lalu_audited, 'Modal Hibah'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_tahun_lalu_audited, 'Cadangan Umum'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_tahun_lalu_audited, 'Pengukuran Kembali Imbalan Paska Kerja'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_saldo_laba_tahun_lalu, 0, ',', '.') ?></td>
                                <td class="text-right">
                                    <?= number_format(
                                        get_nilai_audited($ekuitas_tahun_lalu_audited, 'Penyertaan Pemda Yang Dipisahkan') +
                                            get_nilai_audited($ekuitas_tahun_lalu_audited, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status') +
                                            get_nilai_audited($ekuitas_tahun_lalu_audited, 'Modal Hibah') +
                                            get_nilai_audited($ekuitas_tahun_lalu_audited, 'Cadangan Umum') +
                                            get_nilai_audited($ekuitas_tahun_lalu_audited, 'Pengukuran Kembali Imbalan Paska Kerja') +
                                            get_nilai_audited($ekuitas_tahun_lalu_audited, 'Akm Kerugian Tahun Lalu') +
                                            get_nilai_audited($ekuitas_tahun_lalu_audited, 'Laba Rugi Tahun Berjalan'),
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>
                            </tr>
                            <?php
                            $nilai_ppyd_tahun_ini = get_nilai_audited($ekuitas_tahun_ini_audited, 'Penyertaan Pemda Yang Dipisahkan') - get_nilai_audited($ekuitas_tahun_lalu_audited, 'Penyertaan Pemda Yang Dipisahkan');
                            $nilai_ppybds_tahun_ini = get_nilai_audited($ekuitas_tahun_ini_audited, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status') - get_nilai_audited($ekuitas_tahun_lalu_audited, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status');
                            $nilai_mh_tahun_ini = get_nilai_audited($ekuitas_tahun_ini_audited, 'Modal Hibah') - get_nilai_audited($ekuitas_tahun_lalu_audited, 'Modal Hibah');
                            $nilai_cu_tahun_ini = get_nilai_audited($ekuitas_tahun_ini_audited, 'Cadangan Umum') - get_nilai_audited($ekuitas_tahun_lalu_audited, 'Cadangan Umum');
                            $nilai_pkipk_tahun_ini = get_nilai_audited($ekuitas_tahun_ini_audited, 'Pengukuran Kembali Imbalan Paska Kerja') - abs(get_nilai_audited($ekuitas_tahun_lalu_audited, 'Pengukuran Kembali Imbalan Paska Kerja'));

                            $nilai_lb_tahun_ini = get_nilai_audited($ekuitas_tahun_lalu_audited, 'Laba Rugi Tahun Berjalan') * -1;
                            $nilai_total_tahun_ini =  $nilai_lb_tahun_ini + $nilai_ppyd_tahun_ini + $nilai_ppybds_tahun_ini + $nilai_cu_tahun_ini;
                            ?>
                            <tr>
                                <td>Mutasi Penambahan(Pengurangan)</td>
                                <td class="text-right"><?= number_format($nilai_ppyd_tahun_ini, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_ppybds_tahun_ini, 0, ',', '.') ?></td>
                                <!-- <td class="text-right"><?= number_format($nilai_mh_tahun_ini, 0, ',', '.') ?></td> -->
                                <td></td>
                                <td class="text-right"><?= number_format($nilai_cu_tahun_ini, 0, ',', '.') ?></td>
                                <!-- <td class="text-right"><?= number_format($nilai_pkipk_tahun_ini, 0, ',', '.') ?></td> -->
                                <td></td>
                                <td class="text-right"><?= number_format($nilai_lb_tahun_ini, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_total_tahun_ini, 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td>Akumulasi Kerugian</td>
                                <td></td>
                                <td></td>
                                <td class="text-right"><?= number_format($nilai_mh_tahun_ini, 0, ',', '.') ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"><?= number_format($nilai_mh_tahun_ini, 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td>Pengukuran Kembali Imbalan Kerja</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_tahun_ini_audited, 'Pengukuran Kembali Imbalan Paska Kerja'), 0, ',', '.') ?></td>
                                <td></td>
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_tahun_ini_audited, 'Pengukuran Kembali Imbalan Paska Kerja'), 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td>Laba Rugi Tahun Berjalan</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_tahun_ini_audited, 'Laba Rugi Tahun Berjalan'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_tahun_ini_audited, 'Laba Rugi Tahun Berjalan'), 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <?php
                                // $nilai_saldo_laba_tahun_ini = $nilai_saldo_laba_tahun_lalu + $nilai_lb_tahun_ini + get_nilai_audited($ekuitas_tahun_ini_audited, 'Laba Rugi Tahun Berjalan');
                                $nilai_saldo_laba_tahun_ini = get_nilai_audited($ekuitas_tahun_ini_audited, 'Akm Kerugian Tahun Lalu') + get_nilai_audited($ekuitas_tahun_ini_audited, 'Laba Rugi Tahun Berjalan');
                                ?>
                                <td>Saldo Per 31 Desember <?= $tahun_lap ?></td>
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_tahun_ini_audited, 'Penyertaan Pemda Yang Dipisahkan'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_tahun_ini_audited, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_tahun_ini_audited, 'Modal Hibah'), 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_tahun_ini_audited, 'Cadangan Umum'), 0, ',', '.') ?></td>
                                <!-- <td class="text-right"><?= number_format(get_nilai_audited($ekuitas_tahun_ini_audited, 'Pengukuran Kembali Imbalan Paska Kerja'), 0, ',', '.') ?></td> -->
                                <td class="text-right"><?= number_format($nilai_pkipk_tahun_ini, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($nilai_saldo_laba_tahun_ini, 0, ',', '.') ?></td>
                                <td class="text-right">
                                    <?= number_format(
                                        get_nilai_audited($ekuitas_tahun_ini_audited, 'Penyertaan Pemda Yang Dipisahkan') +
                                            get_nilai_audited($ekuitas_tahun_ini_audited, 'Penyertaan Pemerintah Yang Belum Ditetapkan Status') +
                                            get_nilai_audited($ekuitas_tahun_ini_audited, 'Modal Hibah') +
                                            get_nilai_audited($ekuitas_tahun_ini_audited, 'Cadangan Umum') +
                                            $nilai_pkipk_tahun_ini +
                                            $nilai_saldo_laba_tahun_ini,
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