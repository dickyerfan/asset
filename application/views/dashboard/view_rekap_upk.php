<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('dashboard_asset/rekap_upk') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('dashboard_asset/rekap_upk'); ?>" method="get">
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
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('dashboard_asset/rekap_perkiraan') ?>"><button class="float-end neumorphic-button"> Rekap Perkiraan</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('dashboard_asset/rekap_detail') ?>"><button class="float-end neumorphic-button"> Rekap Detail</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('dashboard_asset') ?>"><button class="float-end neumorphic-button"><i class="fas fa-reply"></i> Kembali</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('dashboard_asset/cetak_rekap_upk') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak Rekap UPK</button></a>
                    </div>
                </nav>
            </div>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title) . ' TAHUN ' . $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>Nama Asset</th>
                                <th>Harga Perolehan Thn Lalu</th>
                                <th>Penambahan</th>
                                <th>Pengurangan</th>
                                <th>Harga Perolehan Thn Ini</th>
                                <th>Akm Thn Lalu</th>
                                <th>Nilai Buku Thn Lalu</th>
                                <th>Penyusutan</th>
                                <th>Akm Thn Ini</th>
                                <th>Nilai Buku Thn Ini</th>
                                <th>By. Peny perbulan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="15"><strong>1. Tanah</strong></td>
                            </tr>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = [];
                            foreach ($susut_tanah as $row) {
                                $grouped_data[$row->grand_id][$row->id_bagian][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $grand_id => $upk_data) {

                                $totals_per_jenis[$grand_id] = [
                                    'total_rupiah' => 0,
                                    'total_nilai_buku' => 0,
                                    'total_penambahan' => 0,
                                    'total_pengurangan' => 0,
                                    'total_akm_thn_lalu' => 0,
                                    'total_nilai_buku_lalu' => 0,
                                    'total_penyusutan' => 0,
                                    'total_akm_thn_ini' => 0,
                                    'total_nilai_buku_final' => 0
                                ];

                                // Menampilkan data per bagian/upk
                                foreach ($upk_data as $id_bagian => $assets) {
                                    $name = $assets[0]->nama_bagian;

                                    if ($name == 'Umum') {
                                        $name = "Pusat/Bondowoso";
                                    } else {
                                        $name = "UPK " . $name;
                                    }

                                    // Inisialisasi total per bagian/upk
                                    $total_rupiah = 0;
                                    $total_nilai_buku = 0;
                                    $total_penambahan = 0;
                                    $total_pengurangan = 0;
                                    $total_akm_thn_lalu = 0;
                                    $total_nilai_buku_lalu = 0;
                                    $total_penyusutan = 0;
                                    $total_akm_thn_ini = 0;
                                    $total_nilai_buku_final = 0;

                                    foreach ($assets as $row) {
                                        $total_rupiah += $row->rupiah;
                                        $total_nilai_buku += $row->nilai_buku;
                                        $total_penambahan += $row->penambahan;
                                        $total_pengurangan += $row->pengurangan;
                                        $total_akm_thn_lalu += $row->akm_thn_lalu;
                                        $total_nilai_buku_lalu += $row->nilai_buku_lalu;
                                        $total_penyusutan += $row->penambahan_penyusutan;
                                        $total_akm_thn_ini += $row->akm_thn_ini;
                                        $total_nilai_buku_final += $row->nilai_buku_final;

                            ?>
                                    <?php
                                    }

                                    // Menampilkan total per bagian/upk
                                    ?>
                                    <!-- <tr class="bg-light text-right">
                                        <td class="text-left"><strong><?= $name; ?></strong></td>
                                        <td><?= number_format($total_nilai_buku, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penambahan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_pengurangan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_rupiah, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_akm_thn_lalu, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_nilai_buku_lalu, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_akm_thn_ini, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_nilai_buku_final, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penyusutan / 12, 0, ',', '.'); ?></td>
                                    </tr> -->
                                <?php

                                    // Menambahkan total per bagian/upk ke total per jenis bangunan
                                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku'] += $total_nilai_buku;
                                    $totals_per_jenis[$grand_id]['total_penambahan'] += $total_penambahan;
                                    $totals_per_jenis[$grand_id]['total_pengurangan'] += $total_pengurangan;
                                    $totals_per_jenis[$grand_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                                    $totals_per_jenis[$grand_id]['total_penyusutan'] += $total_penyusutan;
                                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                                }
                                ?>
                            <?php
                            }
                            ?>
                            <tr class="text-center bg-light">
                                <th class="text-left">Total Tanah</th>
                                <th class="text-right"><?= number_format($total_tanah['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tanah['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tanah['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tanah['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tanah['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tanah['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tanah['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tanah['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tanah['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tanah['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="15"><strong>2. Bangunan Gedung</strong></td>
                            </tr>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = [];
                            foreach ($susut_bangunan as $row) {
                                $grouped_data[$row->grand_id][$row->id_bagian][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $grand_id => $upk_data) {
                                // Inisialisasi total per jenis bangunan
                                $totals_per_jenis[$grand_id] = [
                                    'total_rupiah' => 0,
                                    'total_nilai_buku' => 0,
                                    'total_penambahan' => 0,
                                    'total_pengurangan' => 0,
                                    'total_akm_thn_lalu' => 0,
                                    'total_nilai_buku_lalu' => 0,
                                    'total_penyusutan' => 0,
                                    'total_akm_thn_ini' => 0,
                                    'total_nilai_buku_final' => 0
                                ];

                                // Menampilkan data per bagian/upk
                                foreach ($upk_data as $id_bagian => $assets) {
                                    $name = $assets[0]->nama_bagian;

                                    if ($name == 'Umum') {
                                        $name = "Pusat/Bondowoso";
                                    } else {
                                        $name = "UPK " . $name;
                                    }

                                    // Inisialisasi total per bagian/upk
                                    $total_rupiah = 0;
                                    $total_nilai_buku = 0;
                                    $total_penambahan = 0;
                                    $total_pengurangan = 0;
                                    $total_akm_thn_lalu = 0;
                                    $total_nilai_buku_lalu = 0;
                                    $total_penyusutan = 0;
                                    $total_akm_thn_ini = 0;
                                    $total_nilai_buku_final = 0;

                                    foreach ($assets as $row) {
                                        $total_rupiah += $row->rupiah;
                                        $total_nilai_buku += $row->nilai_buku;
                                        $total_penambahan += $row->penambahan;
                                        $total_pengurangan += $row->pengurangan;
                                        $total_akm_thn_lalu += $row->akm_thn_lalu;
                                        $total_nilai_buku_lalu += $row->nilai_buku_lalu;
                                        $total_penyusutan += $row->penambahan_penyusutan;
                                        $total_akm_thn_ini += $row->akm_thn_ini;
                                        $total_nilai_buku_final += $row->nilai_buku_final;

                            ?>
                                    <?php
                                    }

                                    // Menampilkan total per bagian/upk
                                    ?>
                                    <tr class="bg-light text-right">
                                        <td class="text-left"><strong><?= $name; ?></strong></td>
                                        <td><?= number_format($total_nilai_buku, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penambahan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_pengurangan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_rupiah, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_akm_thn_lalu, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_nilai_buku_lalu, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_akm_thn_ini, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_nilai_buku_final, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penyusutan / 12, 0, ',', '.'); ?></td>
                                    </tr>
                                <?php

                                    // Menambahkan total per bagian/upk ke total per jenis bangunan
                                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku'] += $total_nilai_buku;
                                    $totals_per_jenis[$grand_id]['total_penambahan'] += $total_penambahan;
                                    $totals_per_jenis[$grand_id]['total_pengurangan'] += $total_pengurangan;
                                    $totals_per_jenis[$grand_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                                    $totals_per_jenis[$grand_id]['total_penyusutan'] += $total_penyusutan;
                                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                                }
                                ?>
                            <?php
                            }
                            ?>
                            <tr class="text-center bg-light">
                                <th class="text-left">Total Bangunan Gedung</th>
                                <th class="text-right"><?= number_format($total_bangunan['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bangunan['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bangunan['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bangunan['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bangunan['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bangunan['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bangunan['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bangunan['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bangunan['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bangunan['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="15"><strong>3. Instalasi Sumber</strong></td>
                            </tr>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = [];
                            foreach ($susut_sumber as $row) {
                                $grouped_data[$row->grand_id][$row->id_bagian][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $grand_id => $upk_data) {
                                // Inisialisasi total per jenis bangunan
                                $totals_per_jenis[$grand_id] = [
                                    'total_rupiah' => 0,
                                    'total_nilai_buku' => 0,
                                    'total_penambahan' => 0,
                                    'total_pengurangan' => 0,
                                    'total_akm_thn_lalu' => 0,
                                    'total_nilai_buku_lalu' => 0,
                                    'total_penyusutan' => 0,
                                    'total_akm_thn_ini' => 0,
                                    'total_nilai_buku_final' => 0
                                ];

                                // Menampilkan data per bagian/upk
                                foreach ($upk_data as $id_bagian => $assets) {
                                    $name = $assets[0]->nama_bagian;

                                    if ($name == 'Umum') {
                                        $name = "Pusat/Bondowoso";
                                    } else {
                                        $name = "UPK " . $name;
                                    }

                                    // Inisialisasi total per bagian/upk
                                    $total_rupiah = 0;
                                    $total_nilai_buku = 0;
                                    $total_penambahan = 0;
                                    $total_pengurangan = 0;
                                    $total_akm_thn_lalu = 0;
                                    $total_nilai_buku_lalu = 0;
                                    $total_penyusutan = 0;
                                    $total_akm_thn_ini = 0;
                                    $total_nilai_buku_final = 0;

                                    foreach ($assets as $row) {
                                        $total_rupiah += $row->rupiah;
                                        $total_nilai_buku += $row->nilai_buku;
                                        $total_penambahan += $row->penambahan;
                                        $total_pengurangan += $row->pengurangan;
                                        $total_akm_thn_lalu += $row->akm_thn_lalu;
                                        $total_nilai_buku_lalu += $row->nilai_buku_lalu;
                                        $total_penyusutan += $row->penambahan_penyusutan;
                                        $total_akm_thn_ini += $row->akm_thn_ini;
                                        $total_nilai_buku_final += $row->nilai_buku_final;

                            ?>
                                    <?php
                                    }

                                    // Menampilkan total per bagian/upk
                                    ?>
                                    <tr class="bg-light text-right">
                                        <td class="text-left"><strong><?= $name; ?></strong></td>
                                        <td><?= number_format($total_nilai_buku, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penambahan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_pengurangan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_rupiah, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_akm_thn_lalu, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_nilai_buku_lalu, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_akm_thn_ini, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_nilai_buku_final, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penyusutan / 12, 0, ',', '.'); ?></td>
                                    </tr>
                                <?php

                                    // Menambahkan total per bagian/upk ke total per jenis bangunan
                                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku'] += $total_nilai_buku;
                                    $totals_per_jenis[$grand_id]['total_penambahan'] += $total_penambahan;
                                    $totals_per_jenis[$grand_id]['total_pengurangan'] += $total_pengurangan;
                                    $totals_per_jenis[$grand_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                                    $totals_per_jenis[$grand_id]['total_penyusutan'] += $total_penyusutan;
                                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                                }
                                ?>
                            <?php
                            }
                            ?>
                            <tr class="text-center bg-light">
                                <th class="text-left">Total Instalasi Sumber</th>
                                <th class="text-right"><?= number_format($total_sumber['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_sumber['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_sumber['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_sumber['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_sumber['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_sumber['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_sumber['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_sumber['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_sumber['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_sumber['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="15"><strong>4. Instalasi Pompa</strong></td>
                            </tr>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = [];
                            foreach ($susut_pompa as $row) {
                                $grouped_data[$row->grand_id][$row->id_bagian][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $grand_id => $upk_data) {
                                // Inisialisasi total per jenis bangunan
                                $totals_per_jenis[$grand_id] = [
                                    'total_rupiah' => 0,
                                    'total_nilai_buku' => 0,
                                    'total_penambahan' => 0,
                                    'total_pengurangan' => 0,
                                    'total_akm_thn_lalu' => 0,
                                    'total_nilai_buku_lalu' => 0,
                                    'total_penyusutan' => 0,
                                    'total_akm_thn_ini' => 0,
                                    'total_nilai_buku_final' => 0
                                ];

                                // Menampilkan data per bagian/upk
                                foreach ($upk_data as $id_bagian => $assets) {
                                    $name = $assets[0]->nama_bagian;

                                    if ($name == 'Umum') {
                                        $name = "Pusat/Bondowoso";
                                    } else {
                                        $name = "UPK " . $name;
                                    }

                                    // Inisialisasi total per bagian/upk
                                    $total_rupiah = 0;
                                    $total_nilai_buku = 0;
                                    $total_penambahan = 0;
                                    $total_pengurangan = 0;
                                    $total_akm_thn_lalu = 0;
                                    $total_nilai_buku_lalu = 0;
                                    $total_penyusutan = 0;
                                    $total_akm_thn_ini = 0;
                                    $total_nilai_buku_final = 0;

                                    foreach ($assets as $row) {
                                        $total_rupiah += $row->rupiah;
                                        $total_nilai_buku += $row->nilai_buku;
                                        $total_penambahan += $row->penambahan;
                                        $total_pengurangan += $row->pengurangan;
                                        $total_akm_thn_lalu += $row->akm_thn_lalu;
                                        $total_nilai_buku_lalu += $row->nilai_buku_lalu;
                                        $total_penyusutan += $row->penambahan_penyusutan;
                                        $total_akm_thn_ini += $row->akm_thn_ini;
                                        $total_nilai_buku_final += $row->nilai_buku_final;

                            ?>
                                    <?php
                                    }

                                    // Menampilkan total per bagian/upk
                                    ?>
                                    <tr class="bg-light text-right">
                                        <td class="text-left"><strong><?= $name; ?></strong></td>
                                        <td><?= number_format($total_nilai_buku, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penambahan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_pengurangan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_rupiah, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_akm_thn_lalu, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_nilai_buku_lalu, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_akm_thn_ini, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_nilai_buku_final, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penyusutan / 12, 0, ',', '.'); ?></td>
                                    </tr>
                                <?php

                                    // Menambahkan total per bagian/upk ke total per jenis bangunan
                                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku'] += $total_nilai_buku;
                                    $totals_per_jenis[$grand_id]['total_penambahan'] += $total_penambahan;
                                    $totals_per_jenis[$grand_id]['total_pengurangan'] += $total_pengurangan;
                                    $totals_per_jenis[$grand_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                                    $totals_per_jenis[$grand_id]['total_penyusutan'] += $total_penyusutan;
                                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                                }
                                ?>
                            <?php
                            }
                            ?>
                            <tr class="text-center bg-light">
                                <th class="text-left">Total Instalasi Pompa</th>
                                <th class="text-right"><?= number_format($total_pompa['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="15"><strong>5. Instalasi Pengolahan Air</strong></td>
                            </tr>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = [];
                            foreach ($susut_olah_air as $row) {
                                $grouped_data[$row->grand_id][$row->id_bagian][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $grand_id => $upk_data) {
                                // Inisialisasi total per jenis bangunan
                                $totals_per_jenis[$grand_id] = [
                                    'total_rupiah' => 0,
                                    'total_nilai_buku' => 0,
                                    'total_penambahan' => 0,
                                    'total_pengurangan' => 0,
                                    'total_akm_thn_lalu' => 0,
                                    'total_nilai_buku_lalu' => 0,
                                    'total_penyusutan' => 0,
                                    'total_akm_thn_ini' => 0,
                                    'total_nilai_buku_final' => 0
                                ];

                                // Menampilkan data per bagian/upk
                                foreach ($upk_data as $id_bagian => $assets) {
                                    $name = $assets[0]->nama_bagian;

                                    if ($name == 'Umum') {
                                        $name = "Pusat/Bondowoso";
                                    } else {
                                        $name = "UPK " . $name;
                                    }

                                    // Inisialisasi total per bagian/upk
                                    $total_rupiah = 0;
                                    $total_nilai_buku = 0;
                                    $total_penambahan = 0;
                                    $total_pengurangan = 0;
                                    $total_akm_thn_lalu = 0;
                                    $total_nilai_buku_lalu = 0;
                                    $total_penyusutan = 0;
                                    $total_akm_thn_ini = 0;
                                    $total_nilai_buku_final = 0;

                                    foreach ($assets as $row) {
                                        $total_rupiah += $row->rupiah;
                                        $total_nilai_buku += $row->nilai_buku;
                                        $total_penambahan += $row->penambahan;
                                        $total_pengurangan += $row->pengurangan;
                                        $total_akm_thn_lalu += $row->akm_thn_lalu;
                                        $total_nilai_buku_lalu += $row->nilai_buku_lalu;
                                        $total_penyusutan += $row->penambahan_penyusutan;
                                        $total_akm_thn_ini += $row->akm_thn_ini;
                                        $total_nilai_buku_final += $row->nilai_buku_final;

                            ?>
                                    <?php
                                    }

                                    // Menampilkan total per bagian/upk
                                    ?>
                                    <tr class="bg-light text-right">
                                        <td class="text-left"><strong><?= $name; ?></strong></td>
                                        <td><?= number_format($total_nilai_buku, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penambahan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_pengurangan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_rupiah, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_akm_thn_lalu, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_nilai_buku_lalu, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_akm_thn_ini, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_nilai_buku_final, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penyusutan / 12, 0, ',', '.'); ?></td>
                                    </tr>
                                <?php

                                    // Menambahkan total per bagian/upk ke total per jenis bangunan
                                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku'] += $total_nilai_buku;
                                    $totals_per_jenis[$grand_id]['total_penambahan'] += $total_penambahan;
                                    $totals_per_jenis[$grand_id]['total_pengurangan'] += $total_pengurangan;
                                    $totals_per_jenis[$grand_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                                    $totals_per_jenis[$grand_id]['total_penyusutan'] += $total_penyusutan;
                                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                                }
                                ?>
                            <?php
                            }
                            ?>
                            <tr class="text-center bg-light">
                                <th class="text-left">Total Instalasi Pengolahan Air</th>
                                <th class="text-right"><?= number_format($total_olah_air['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_olah_air['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_olah_air['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_olah_air['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_olah_air['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_olah_air['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_olah_air['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_olah_air['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_olah_air['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_olah_air['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="15"><strong>6. Instalasi Transmisi & Distribusi</strong></td>
                            </tr>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = [];
                            foreach ($susut_trans_dist as $row) {
                                $grouped_data[$row->grand_id][$row->id_bagian][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $grand_id => $upk_data) {
                                // Inisialisasi total per jenis bangunan
                                $totals_per_jenis[$grand_id] = [
                                    'total_rupiah' => 0,
                                    'total_nilai_buku' => 0,
                                    'total_penambahan' => 0,
                                    'total_pengurangan' => 0,
                                    'total_akm_thn_lalu' => 0,
                                    'total_nilai_buku_lalu' => 0,
                                    'total_penyusutan' => 0,
                                    'total_akm_thn_ini' => 0,
                                    'total_nilai_buku_final' => 0
                                ];

                                // Menampilkan data per bagian/upk
                                foreach ($upk_data as $id_bagian => $assets) {
                                    $name = $assets[0]->nama_bagian;

                                    if ($name == 'Umum') {
                                        $name = "Pusat/Bondowoso";
                                    } else {
                                        $name = "UPK " . $name;
                                    }

                                    // Inisialisasi total per bagian/upk
                                    $total_rupiah = 0;
                                    $total_nilai_buku = 0;
                                    $total_penambahan = 0;
                                    $total_pengurangan = 0;
                                    $total_akm_thn_lalu = 0;
                                    $total_nilai_buku_lalu = 0;
                                    $total_penyusutan = 0;
                                    $total_akm_thn_ini = 0;
                                    $total_nilai_buku_final = 0;

                                    foreach ($assets as $row) {
                                        $total_rupiah += $row->rupiah;
                                        $total_nilai_buku += $row->nilai_buku;
                                        $total_penambahan += $row->penambahan;
                                        $total_pengurangan += $row->pengurangan;
                                        $total_akm_thn_lalu += $row->akm_thn_lalu;
                                        $total_nilai_buku_lalu += $row->nilai_buku_lalu;
                                        $total_penyusutan += $row->penambahan_penyusutan;
                                        $total_akm_thn_ini += $row->akm_thn_ini;
                                        $total_nilai_buku_final += $row->nilai_buku_final;

                            ?>
                                    <?php
                                    }

                                    // Menampilkan total per bagian/upk
                                    ?>
                                    <tr class="bg-light text-right">
                                        <td class="text-left"><strong><?= $name; ?></strong></td>
                                        <td><?= number_format($total_nilai_buku, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penambahan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_pengurangan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_rupiah, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_akm_thn_lalu, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_nilai_buku_lalu, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_akm_thn_ini, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_nilai_buku_final, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penyusutan / 12, 0, ',', '.'); ?></td>
                                    </tr>
                                <?php

                                    // Menambahkan total per bagian/upk ke total per jenis bangunan
                                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku'] += $total_nilai_buku;
                                    $totals_per_jenis[$grand_id]['total_penambahan'] += $total_penambahan;
                                    $totals_per_jenis[$grand_id]['total_pengurangan'] += $total_pengurangan;
                                    $totals_per_jenis[$grand_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                                    $totals_per_jenis[$grand_id]['total_penyusutan'] += $total_penyusutan;
                                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                                }
                                ?>
                            <?php
                            }
                            ?>
                            <tr class="text-center bg-light">
                                <th class="text-left">Total Instalasi Transmisi & Distribusi</th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="15"><strong>7. Peralatan & Perlengkapan</strong></td>
                            </tr>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = [];
                            foreach ($susut_peralatan as $row) {
                                $grouped_data[$row->grand_id][$row->id_bagian][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $grand_id => $upk_data) {
                                // Inisialisasi total per jenis bangunan
                                $totals_per_jenis[$grand_id] = [
                                    'total_rupiah' => 0,
                                    'total_nilai_buku' => 0,
                                    'total_penambahan' => 0,
                                    'total_pengurangan' => 0,
                                    'total_akm_thn_lalu' => 0,
                                    'total_nilai_buku_lalu' => 0,
                                    'total_penyusutan' => 0,
                                    'total_akm_thn_ini' => 0,
                                    'total_nilai_buku_final' => 0
                                ];

                                // Menampilkan data per bagian/upk
                                foreach ($upk_data as $id_bagian => $assets) {
                                    $name = $assets[0]->nama_bagian;

                                    if ($name == 'Umum') {
                                        $name = "Pusat/Bondowoso";
                                    } else {
                                        $name = "UPK " . $name;
                                    }

                                    // Inisialisasi total per bagian/upk
                                    $total_rupiah = 0;
                                    $total_nilai_buku = 0;
                                    $total_penambahan = 0;
                                    $total_pengurangan = 0;
                                    $total_akm_thn_lalu = 0;
                                    $total_nilai_buku_lalu = 0;
                                    $total_penyusutan = 0;
                                    $total_akm_thn_ini = 0;
                                    $total_nilai_buku_final = 0;

                                    foreach ($assets as $row) {
                                        $total_rupiah += $row->rupiah;
                                        $total_nilai_buku += $row->nilai_buku;
                                        $total_penambahan += $row->penambahan;
                                        $total_pengurangan += $row->pengurangan;
                                        $total_akm_thn_lalu += $row->akm_thn_lalu;
                                        $total_nilai_buku_lalu += $row->nilai_buku_lalu;
                                        $total_penyusutan += $row->penambahan_penyusutan;
                                        $total_akm_thn_ini += $row->akm_thn_ini;
                                        $total_nilai_buku_final += $row->nilai_buku_final;

                            ?>
                                    <?php
                                    }

                                    // Menampilkan total per bagian/upk
                                    ?>
                                    <tr class="bg-light text-right">
                                        <td class="text-left"><strong><?= $name; ?></strong></td>
                                        <td><?= number_format($total_nilai_buku, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penambahan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_pengurangan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_rupiah, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_akm_thn_lalu, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_nilai_buku_lalu, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_akm_thn_ini, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_nilai_buku_final, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penyusutan / 12, 0, ',', '.'); ?></td>
                                    </tr>
                                <?php

                                    // Menambahkan total per bagian/upk ke total per jenis bangunan
                                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku'] += $total_nilai_buku;
                                    $totals_per_jenis[$grand_id]['total_penambahan'] += $total_penambahan;
                                    $totals_per_jenis[$grand_id]['total_pengurangan'] += $total_pengurangan;
                                    $totals_per_jenis[$grand_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                                    $totals_per_jenis[$grand_id]['total_penyusutan'] += $total_penyusutan;
                                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                                }
                                ?>
                            <?php
                            }
                            ?>
                            <tr class="text-center bg-light">
                                <th class="text-left">Total Peralatan & Perlengkapan</th>
                                <th class="text-right"><?= number_format($total_peralatan['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_peralatan['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_peralatan['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_peralatan['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_peralatan['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_peralatan['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_peralatan['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_peralatan['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_peralatan['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_peralatan['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="15"><strong>8. Kendaraan & Alat Angkut</strong></td>
                            </tr>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = [];
                            foreach ($susut_kendaraan as $row) {
                                $grouped_data[$row->grand_id][$row->id_bagian][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $grand_id => $upk_data) {
                                // Inisialisasi total per jenis bangunan
                                $totals_per_jenis[$grand_id] = [
                                    'total_rupiah' => 0,
                                    'total_nilai_buku' => 0,
                                    'total_penambahan' => 0,
                                    'total_pengurangan' => 0,
                                    'total_akm_thn_lalu' => 0,
                                    'total_nilai_buku_lalu' => 0,
                                    'total_penyusutan' => 0,
                                    'total_akm_thn_ini' => 0,
                                    'total_nilai_buku_final' => 0
                                ];

                                // Menampilkan data per bagian/upk
                                foreach ($upk_data as $id_bagian => $assets) {
                                    $name = $assets[0]->nama_bagian;

                                    if ($name == 'Umum') {
                                        $name = "Pusat/Bondowoso";
                                    } else {
                                        $name = "UPK " . $name;
                                    }

                                    // Inisialisasi total per bagian/upk
                                    $total_rupiah = 0;
                                    $total_nilai_buku = 0;
                                    $total_penambahan = 0;
                                    $total_pengurangan = 0;
                                    $total_akm_thn_lalu = 0;
                                    $total_nilai_buku_lalu = 0;
                                    $total_penyusutan = 0;
                                    $total_akm_thn_ini = 0;
                                    $total_nilai_buku_final = 0;

                                    foreach ($assets as $row) {
                                        $total_rupiah += $row->rupiah;
                                        $total_nilai_buku += $row->nilai_buku;
                                        $total_penambahan += $row->penambahan;
                                        $total_pengurangan += $row->pengurangan;
                                        $total_akm_thn_lalu += $row->akm_thn_lalu;
                                        $total_nilai_buku_lalu += $row->nilai_buku_lalu;
                                        $total_penyusutan += $row->penambahan_penyusutan;
                                        $total_akm_thn_ini += $row->akm_thn_ini;
                                        $total_nilai_buku_final += $row->nilai_buku_final;

                            ?>
                                    <?php
                                    }

                                    // Menampilkan total per bagian/upk
                                    ?>
                                    <tr class="bg-light text-right">
                                        <td class="text-left"><strong><?= $name; ?></strong></td>
                                        <td><?= number_format($total_nilai_buku, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penambahan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_pengurangan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_rupiah, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_akm_thn_lalu, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_nilai_buku_lalu, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_akm_thn_ini, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_nilai_buku_final, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penyusutan / 12, 0, ',', '.'); ?></td>
                                    </tr>
                                <?php

                                    // Menambahkan total per bagian/upk ke total per jenis bangunan
                                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku'] += $total_nilai_buku;
                                    $totals_per_jenis[$grand_id]['total_penambahan'] += $total_penambahan;
                                    $totals_per_jenis[$grand_id]['total_pengurangan'] += $total_pengurangan;
                                    $totals_per_jenis[$grand_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                                    $totals_per_jenis[$grand_id]['total_penyusutan'] += $total_penyusutan;
                                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                                }
                                ?>
                            <?php
                            }
                            ?>
                            <tr class="text-center bg-light">
                                <th class="text-left">Total Kendaraan & Alat Angkut</th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="15"><strong>9. Inventaris/Perabotan Kantor</strong></td>
                            </tr>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = [];
                            foreach ($susut_inventaris as $row) {
                                $grouped_data[$row->grand_id][$row->id_bagian][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $grand_id => $upk_data) {
                                // Inisialisasi total per jenis bangunan
                                $totals_per_jenis[$grand_id] = [
                                    'total_rupiah' => 0,
                                    'total_nilai_buku' => 0,
                                    'total_penambahan' => 0,
                                    'total_pengurangan' => 0,
                                    'total_akm_thn_lalu' => 0,
                                    'total_nilai_buku_lalu' => 0,
                                    'total_penyusutan' => 0,
                                    'total_akm_thn_ini' => 0,
                                    'total_nilai_buku_final' => 0
                                ];

                                // Menampilkan data per bagian/upk
                                foreach ($upk_data as $id_bagian => $assets) {
                                    $name = $assets[0]->nama_bagian;

                                    if ($name == 'Umum') {
                                        $name = "Pusat/Bondowoso";
                                    } else {
                                        $name = "UPK " . $name;
                                    }

                                    // Inisialisasi total per bagian/upk
                                    $total_rupiah = 0;
                                    $total_nilai_buku = 0;
                                    $total_penambahan = 0;
                                    $total_pengurangan = 0;
                                    $total_akm_thn_lalu = 0;
                                    $total_nilai_buku_lalu = 0;
                                    $total_penyusutan = 0;
                                    $total_akm_thn_ini = 0;
                                    $total_nilai_buku_final = 0;

                                    foreach ($assets as $row) {
                                        $total_rupiah += $row->rupiah;
                                        $total_nilai_buku += $row->nilai_buku;
                                        $total_penambahan += $row->penambahan;
                                        $total_pengurangan += $row->pengurangan;
                                        $total_akm_thn_lalu += $row->akm_thn_lalu;
                                        $total_nilai_buku_lalu += $row->nilai_buku_lalu;
                                        $total_penyusutan += $row->penambahan_penyusutan;
                                        $total_akm_thn_ini += $row->akm_thn_ini;
                                        $total_nilai_buku_final += $row->nilai_buku_final;

                            ?>
                                    <?php
                                    }

                                    // Menampilkan total per bagian/upk
                                    ?>
                                    <tr class="bg-light text-right">
                                        <td class="text-left"><strong><?= $name; ?></strong></td>
                                        <td><?= number_format($total_nilai_buku, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penambahan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_pengurangan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_rupiah, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_akm_thn_lalu, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_nilai_buku_lalu, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_akm_thn_ini, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_nilai_buku_final, 0, ',', '.'); ?></td>
                                        <td><?= number_format($total_penyusutan / 12, 0, ',', '.'); ?></td>
                                    </tr>
                                <?php

                                    // Menambahkan total per bagian/upk ke total per jenis bangunan
                                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku'] += $total_nilai_buku;
                                    $totals_per_jenis[$grand_id]['total_penambahan'] += $total_penambahan;
                                    $totals_per_jenis[$grand_id]['total_pengurangan'] += $total_pengurangan;
                                    $totals_per_jenis[$grand_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                                    $totals_per_jenis[$grand_id]['total_penyusutan'] += $total_penyusutan;
                                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                                }
                                ?>
                            <?php
                            }
                            ?>
                            <tr class="text-center bg-light">
                                <th class="text-left">Total Inventaris/Perabotan Kantor</th>
                                <th class="text-right"><?= number_format($total_inventaris['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>

                        <tfoot>
                            <tr class="text-center bg-light">
                                <th class="text-left">Total</th>
                                <th class="text-right"><?= number_format($totals['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>