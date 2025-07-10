<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form method="get" action="<?= base_url('spi/hasil_evaluasi'); ?>" class="form-inline ">
                        <div class="form-group mr-2">
                            <label>Bulan:</label>
                            <select name="bulan" class="form-control ml-2" required>
                                <?php
                                $nama_bulan_lengkap = array(
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                );
                                for ($i = 1; $i <= 12; $i++) :
                                    $bulan_value_for_option = $i;
                                ?>
                                    <option value="<?= $bulan_value_for_option ?>" <?= isset($filter['bulan']) && $filter['bulan'] == $bulan_value_for_option ? 'selected' : '' ?>>
                                        <?= $nama_bulan_lengkap[$i] ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <label>Tahun:</label>
                            <input type="number" name="tahun" class="form-control ml-2" value="<?= isset($filter['tahun']) ? $filter['tahun'] : date('Y') ?>" required>
                        </div>
                        <button type="submit" class="neumorphic-button">Tampilkan</button>
                    </form>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('spi/hasil_evaluasi/cetak_evaluasi_total') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h5>
                            <?= strtoupper($title); ?>
                        </h5>
                        <h5>
                            <?= strtoupper($nama_bulan_terpilih); ?> <?= $tahun_selected; ?>
                        </h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama U P K</th>
                                <th>Skor Teknis</th>
                                <th>Skor Administrasi</th>
                                <th>Skor Koordinasi</th>
                                <th>Total Skor</th>
                                <th>Hasil</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            if (!empty($rekap)) :
                                foreach ($rekap as $data_upk) :
                                    $hasil = '';
                                    $grand_total_skor = (float)$data_upk['grand_total_skor'];

                                    if ($grand_total_skor >= 71 && $grand_total_skor <= 75) {
                                        $hasil = 'Sangat Baik';
                                    } elseif ($grand_total_skor >= 61 && $grand_total_skor <= 70) {
                                        $hasil = 'Baik';
                                    } elseif ($grand_total_skor >= 51 && $grand_total_skor <= 60) {
                                        $hasil = 'Sedang';
                                    } elseif ($grand_total_skor >= 41 && $grand_total_skor <= 50) {
                                        $hasil = 'Kurang';
                                    } elseif ($grand_total_skor < 40) {
                                        $hasil = 'Sangat Kurang';
                                    } else {
                                        $hasil = 'N/A';
                                    }
                            ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><?= $data_upk['nama_bagian']; ?></td>
                                        <td class="text-center"><?= $data_upk['total_skor_teknis']; ?></td>
                                        <td class="text-center"><?= $data_upk['total_skor_admin']; ?></td>
                                        <td class="text-center"><?= $data_upk['total_skor_koordinasi']; ?></td>
                                        <td class="text-center"><?= $data_upk['grand_total_skor']; ?></td>
                                        <td class="text-center"><?= $hasil; ?></td>
                                        <td class="text-center"><a href="<?= base_url('spi/hasil_evaluasi/detail/' . $data_upk['id_upk'] . '/' . $bulan_selected . '/' . $tahun_selected) ?>"><i class="fas fa-info-circle"></a></td>
                                    </tr>
                                <?php endforeach;
                            else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data yang ditemukan untuk bulan dan tahun ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>