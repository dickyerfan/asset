<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('dashboard_asset/rekap_detail') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('dashboard_asset/rekap_detail'); ?>" method="get">
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
                        <a href="<?= base_url('dashboard_asset') ?>"><button class="float-end neumorphic-button"><i class="fas fa-reply"></i> Kembali</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('dashboard_asset/cetak_rekap_detail') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak Rekap Detail</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('dashboard_asset/cetak_auditor') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak Auditor</button></a>
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="14"><strong>1. Tanah</strong></td>
                            </tr>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = []; // Array untuk menyimpan total per jenis bangunan

                            // Array untuk nama bangunan berdasarkan parent_id
                            $nama_tanah = [
                                1472 => "Tanah dan Hak Atas Tanah",
                                1474 => "Penyempurnaan Tanah",
                            ];

                            // Mengelompokkan data berdasarkan parent_id dan id_no_per
                            foreach ($susut_tanah as $row) {
                                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $parent_id => $upk_data) {
                                // Judul berdasarkan parent_id
                                $nama_tanah_jenis = $nama_tanah[$parent_id] ?? "tanah Lainnya";
                                echo "<tr><td colspan='14' class='bg-primary text-white'><strong>{$nama_tanah_jenis}</strong></td></tr>";

                                // Inisialisasi total per jenis tanah
                                $totals_per_jenis[$parent_id] = [
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
                                foreach ($upk_data as $id_no_per => $assets) {
                                    $name = $assets[0]->name;
                                    // echo "<tr><td colspan='14' class='bg-secondary text-white'><strong> {$name}</strong></td></tr>";

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
                                    </tr>
                                <?php

                                    // Menambahkan total per bagian/upk ke total per jenis tanah
                                    $totals_per_jenis[$parent_id]['total_rupiah'] += $total_rupiah;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku'] += $total_nilai_buku;
                                    $totals_per_jenis[$parent_id]['total_penambahan'] += $total_penambahan;
                                    $totals_per_jenis[$parent_id]['total_pengurangan'] += $total_pengurangan;
                                    $totals_per_jenis[$parent_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                                    $totals_per_jenis[$parent_id]['total_penyusutan'] += $total_penyusutan;
                                    $totals_per_jenis[$parent_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                                }

                                // Menampilkan total per jenis tanah
                                ?>
                                <tr class="bg-info text-right">
                                    <td class="text-left"><strong>Sub Total <?= $nama_tanah_jenis; ?></strong></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_penambahan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_pengurangan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_rupiah'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_lalu'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_lalu'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_penyusutan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_ini'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_final'], 0, ',', '.'); ?></td>
                                </tr>
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
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="14"><strong>2. Bangunan Gedung</strong></td>
                            </tr>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = []; // Array untuk menyimpan total per jenis bangunan

                            // Array untuk nama bangunan berdasarkan parent_id
                            $nama_bangunan = [
                                2671 => "Bangunan Kantor",
                                2674 => "Bangunan Laboratorium",
                                2676 => "Bangunan Gedung Peralatan",
                                2678 => "Bangunan Bengkel",
                                2680 => "Instalasi Umum Lainnya"
                            ];

                            // Mengelompokkan data berdasarkan parent_id dan id_no_per
                            foreach ($susut_bangunan as $row) {
                                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $parent_id => $upk_data) {
                                // Judul berdasarkan parent_id
                                $nama_bangunan_jenis = $nama_bangunan[$parent_id] ?? "Bangunan Lainnya";
                                echo "<tr><td colspan='14' class='bg-primary text-white'><strong>{$nama_bangunan_jenis}</strong></td></tr>";

                                // Inisialisasi total per jenis bangunan
                                $totals_per_jenis[$parent_id] = [
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
                                foreach ($upk_data as $id_no_per => $assets) {
                                    $name = $assets[0]->name;
                                    // echo "<tr><td colspan='14' class='bg-secondary text-white'><strong> {$name}</strong></td></tr>";

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
                                    </tr>
                                <?php

                                    // Menambahkan total per bagian/upk ke total per jenis bangunan
                                    $totals_per_jenis[$parent_id]['total_rupiah'] += $total_rupiah;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku'] += $total_nilai_buku;
                                    $totals_per_jenis[$parent_id]['total_penambahan'] += $total_penambahan;
                                    $totals_per_jenis[$parent_id]['total_pengurangan'] += $total_pengurangan;
                                    $totals_per_jenis[$parent_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                                    $totals_per_jenis[$parent_id]['total_penyusutan'] += $total_penyusutan;
                                    $totals_per_jenis[$parent_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                                }

                                // Menampilkan total per jenis bangunan
                                ?>
                                <tr class="bg-info text-right">
                                    <td class="text-left"><strong>Sub Total <?= $nama_bangunan_jenis; ?></strong></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_penambahan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_pengurangan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_rupiah'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_lalu'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_lalu'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_penyusutan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_ini'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_final'], 0, ',', '.'); ?></td>
                                </tr>
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
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="14"><strong>3. Instalasi Sumber</strong></td>
                            </tr>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = [];
                            // Array untuk nama bangunan berdasarkan parent_id
                            $nama_sumber = [
                                1569 => "Bangunan & Perbaikan",
                                1571 => "Reservoir Penampungan Air",
                                1572 => "Danau,Sungai & Sb.Lainnya",
                                1575 => "Mata Air dan Terowongan",
                                1576 => "Sumur-sumur",
                                1577 => "Pipa Supply Utama",
                                1579 => "Instalasi Sumber Lainnya",
                            ];
                            foreach ($susut_sumber as $row) {
                                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $parent_id => $upk_data) {
                                // Judul berdasarkan parent_id
                                $nama_sumber_jenis = $nama_sumber[$parent_id] ?? "Instalasi Sumber Lainnya";
                                echo "<tr><td colspan='14' class='bg-primary text-white'><strong>{$nama_sumber_jenis}</strong></td></tr>";

                                // Inisialisasi total per jenis bangunan
                                $totals_per_jenis[$parent_id] = [
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
                                foreach ($upk_data as $id_no_per => $assets) {
                                    $name = $assets[0]->name;
                                    // echo "<tr><td class='bg-secondary'></td><td colspan='14' class='bg-secondary text-white'><strong> {$name}</strong></td></tr>";

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
                                    </tr>
                                <?php

                                    // Menambahkan total per bagian/upk ke total per jenis bangunan
                                    $totals_per_jenis[$parent_id]['total_rupiah'] += $total_rupiah;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku'] += $total_nilai_buku;
                                    $totals_per_jenis[$parent_id]['total_penambahan'] += $total_penambahan;
                                    $totals_per_jenis[$parent_id]['total_pengurangan'] += $total_pengurangan;
                                    $totals_per_jenis[$parent_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                                    $totals_per_jenis[$parent_id]['total_penyusutan'] += $total_penyusutan;
                                    $totals_per_jenis[$parent_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                                }

                                // Menampilkan total per jenis sumber
                                ?>
                                <tr class="bg-info text-right">

                                    <td class="text-left"><strong>Sub Total <?= $nama_sumber_jenis; ?></strong></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_penambahan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_pengurangan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_rupiah'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_lalu'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_lalu'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_penyusutan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_ini'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_final'], 0, ',', '.'); ?></td>
                                </tr>
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
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="14"><strong>4. Instalasi Pompa</strong></td>
                            </tr>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = [];
                            // Array untuk nama bangunan berdasarkan parent_id
                            $nama_pompa = [
                                1907 => "Bangunan & Perbaikan",
                                1909 => "Pembangkit Tenaga Listrik",
                                1912 => "Peralatan Pompa",
                                1915 => "Instalasi Pompa Lainnya"
                            ];
                            foreach ($susut_pompa as $row) {
                                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $parent_id => $upk_data) {
                                // Judul berdasarkan parent_id
                                $nama_pompa_jenis = $nama_pompa[$parent_id] ?? "Instalasi Pompa Lainnya";
                                echo "<tr><td colspan='14' class='bg-primary text-white'><strong>{$nama_pompa_jenis}</strong></td></tr>";

                                // Inisialisasi total per jenis bangunan
                                $totals_per_jenis[$parent_id] = [
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
                                foreach ($upk_data as $id_no_per => $assets) {
                                    $name = $assets[0]->name;
                                    // echo "<tr><td class='bg-secondary'></td><td colspan='14' class='bg-secondary text-white'><strong> {$name}</strong></td></tr>";

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
                                    </tr>
                                <?php

                                    // Menambahkan total per bagian/upk ke total per jenis bangunan
                                    $totals_per_jenis[$parent_id]['total_rupiah'] += $total_rupiah;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku'] += $total_nilai_buku;
                                    $totals_per_jenis[$parent_id]['total_penambahan'] += $total_penambahan;
                                    $totals_per_jenis[$parent_id]['total_pengurangan'] += $total_pengurangan;
                                    $totals_per_jenis[$parent_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                                    $totals_per_jenis[$parent_id]['total_penyusutan'] += $total_penyusutan;
                                    $totals_per_jenis[$parent_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                                }

                                // Menampilkan total per jenis pompa
                                ?>
                                <tr class="bg-info text-right">

                                    <td class="text-left"><strong>Sub Total <?= $nama_pompa_jenis; ?></strong></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_penambahan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_pengurangan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_rupiah'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_lalu'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_lalu'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_penyusutan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_ini'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_final'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                            <tr class="text-center bg-light">
                                <th class="text-left">Total Instalasi pompa</th>
                                <th class="text-right"><?= number_format($total_pompa['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="14"><strong>5. Instalasi Pengolahan Air</strong></td>
                            </tr>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = [];
                            // Array untuk nama bangunan berdasarkan parent_id
                            $nama_olah_air = [
                                2104 => "Bangunan & Perbaikan",
                                2107 => "Alat-alat Pengolahan air",
                                2112 => "Resevoir/Penampungan Air",
                                2115 => "Instalasi Pengolahan Air Lainnya"
                            ];
                            foreach ($susut_olah_air as $row) {
                                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $parent_id => $upk_data) {
                                // Judul berdasarkan parent_id
                                $nama_olah_air_jenis = $nama_olah_air[$parent_id] ?? "Instalasi olah_air Lainnya";
                                echo "<tr><td colspan='14' class='bg-primary text-white'><strong>{$nama_olah_air_jenis}</strong></td></tr>";

                                // Inisialisasi total per jenis bangunan
                                $totals_per_jenis[$parent_id] = [
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
                                foreach ($upk_data as $id_no_per => $assets) {
                                    $name = $assets[0]->name;
                                    // echo "<tr><td class='bg-secondary'></td><td colspan='14' class='bg-secondary text-white'><strong> {$name}</strong></td></tr>";

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
                                    </tr>
                                <?php

                                    // Menambahkan total per bagian/upk ke total per jenis pengolahan air
                                    $totals_per_jenis[$parent_id]['total_rupiah'] += $total_rupiah;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku'] += $total_nilai_buku;
                                    $totals_per_jenis[$parent_id]['total_penambahan'] += $total_penambahan;
                                    $totals_per_jenis[$parent_id]['total_pengurangan'] += $total_pengurangan;
                                    $totals_per_jenis[$parent_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                                    $totals_per_jenis[$parent_id]['total_penyusutan'] += $total_penyusutan;
                                    $totals_per_jenis[$parent_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                                }

                                // Menampilkan total per jenis olah_air
                                ?>
                                <tr class="bg-info text-right">

                                    <td class="text-left"><strong>Sub Total <?= $nama_olah_air_jenis; ?></strong></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_penambahan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_pengurangan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_rupiah'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_lalu'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_lalu'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_penyusutan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_ini'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_final'], 0, ',', '.'); ?></td>
                                </tr>
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
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="14"><strong>6. Instalasi Transmisi & Distribusi</strong></td>
                            </tr>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = [];
                            // Array untuk nama bangunan berdasarkan parent_id
                            $nama_trans_dist = [
                                2255 => "Bangunan & Perbaikan",
                                2258 => "Reservoir,Tandon & MnrAir",
                                2261 => "Pipa Transmisi dan Distribusi",
                                2262 => "Pipa Dinas",
                                2263 => "Meter Air Yang Terpasang",
                                2264 => "Ledeng Umum",
                                2548 => "Saluran Air Pemadam Kebakaran",
                                2550 => "Jembatan Pipa",
                                2552 => "Inst.Trans & Dist Lainnya"
                            ];
                            foreach ($susut_trans_dist as $row) {
                                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $parent_id => $upk_data) {
                                // Judul berdasarkan parent_id
                                $nama_trans_dist_jenis = $nama_trans_dist[$parent_id] ?? "Instalasi trans_dist Lainnya";
                                echo "<tr><td colspan='14' class='bg-primary text-white'><strong>{$nama_trans_dist_jenis}</strong></td></tr>";

                                // Inisialisasi total per jenis bangunan
                                $totals_per_jenis[$parent_id] = [
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
                                foreach ($upk_data as $id_no_per => $assets) {
                                    $name = $assets[0]->name;
                                    // echo "<tr><td class='bg-secondary'></td><td colspan='14' class='bg-secondary text-white'><strong> {$name}</strong></td></tr>";

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
                                    </tr>
                                <?php

                                    // Menambahkan total per bagian/upk ke total per jenis pengolahan air
                                    $totals_per_jenis[$parent_id]['total_rupiah'] += $total_rupiah;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku'] += $total_nilai_buku;
                                    $totals_per_jenis[$parent_id]['total_penambahan'] += $total_penambahan;
                                    $totals_per_jenis[$parent_id]['total_pengurangan'] += $total_pengurangan;
                                    $totals_per_jenis[$parent_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                                    $totals_per_jenis[$parent_id]['total_penyusutan'] += $total_penyusutan;
                                    $totals_per_jenis[$parent_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                                }

                                // Menampilkan total per jenis trans_dist
                                ?>
                                <tr class="bg-info text-right">

                                    <td class="text-left"><strong>Sub Total <?= $nama_trans_dist_jenis; ?></strong></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_penambahan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_pengurangan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_rupiah'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_lalu'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_lalu'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_penyusutan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_ini'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_final'], 0, ',', '.'); ?></td>
                                </tr>
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
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="14"><strong>7. Peralatan & Perlengkapan</strong></td>
                            </tr>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = [];
                            // Array untuk nama bangunan berdasarkan parent_id
                            $nama_peralatan = [
                                2789 => "Alat-alat Pergudangan",
                                2793 => "Alat-alat Laboratorium",
                                2795 => "Alat-alat Telekomunikasi",
                                2798 => "Alat-alat Bengkel",
                                4251 => "Alat Perlengkapan Lainnya"
                            ];
                            foreach ($susut_peralatan as $row) {
                                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $parent_id => $upk_data) {
                                // Judul berdasarkan parent_id
                                $nama_peralatan_jenis = $nama_peralatan[$parent_id] ?? "Instalasi peralatan Lainnya";
                                echo "<tr><td colspan='14' class='bg-primary text-white'><strong>{$nama_peralatan_jenis}</strong></td></tr>";

                                // Inisialisasi total per jenis bangunan
                                $totals_per_jenis[$parent_id] = [
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
                                foreach ($upk_data as $id_no_per => $assets) {
                                    $name = $assets[0]->name;
                                    // echo "<tr><td class='bg-secondary'></td><td colspan='14' class='bg-secondary text-white'><strong> {$name}</strong></td></tr>";

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
                                    </tr>
                                <?php

                                    // Menambahkan total per bagian/upk ke total per jenis pengolahan air
                                    $totals_per_jenis[$parent_id]['total_rupiah'] += $total_rupiah;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku'] += $total_nilai_buku;
                                    $totals_per_jenis[$parent_id]['total_penambahan'] += $total_penambahan;
                                    $totals_per_jenis[$parent_id]['total_pengurangan'] += $total_pengurangan;
                                    $totals_per_jenis[$parent_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                                    $totals_per_jenis[$parent_id]['total_penyusutan'] += $total_penyusutan;
                                    $totals_per_jenis[$parent_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                                }

                                // Menampilkan total per jenis peralatan
                                ?>
                                <tr class="bg-info text-right">

                                    <td class="text-left"><strong>Sub Total <?= $nama_peralatan_jenis; ?></strong></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_penambahan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_pengurangan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_rupiah'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_lalu'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_lalu'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_penyusutan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_ini'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_final'], 0, ',', '.'); ?></td>
                                </tr>
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
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="14"><strong>8. Kendaraan & Alat Angkut</strong></td>
                            </tr>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = [];
                            // Array untuk nama bangunan berdasarkan parent_id
                            $nama_kendaraan = [
                                2850 => "Kendaraan Penumpang",
                                2852 => "Kendaraan Angkut Barang",
                                2854 => "Kendaraan Tangki Air",
                                2855 => "Kendaraan Roda Dua"
                            ];
                            foreach ($susut_kendaraan as $row) {
                                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $parent_id => $upk_data) {
                                // Judul berdasarkan parent_id
                                $nama_kendaraan_jenis = $nama_kendaraan[$parent_id] ?? "Instalasi kendaraan Lainnya";
                                echo "<tr><td colspan='14' class='bg-primary text-white'><strong>{$nama_kendaraan_jenis}</strong></td></tr>";

                                // Inisialisasi total per jenis bangunan
                                $totals_per_jenis[$parent_id] = [
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
                                foreach ($upk_data as $id_no_per => $assets) {
                                    $name = $assets[0]->name;
                                    // echo "<tr><td class='bg-secondary'></td><td colspan='14' class='bg-secondary text-white'><strong> {$name}</strong></td></tr>";

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
                                    </tr>
                                <?php

                                    // Menambahkan total per bagian/upk ke total per jenis pengolahan air
                                    $totals_per_jenis[$parent_id]['total_rupiah'] += $total_rupiah;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku'] += $total_nilai_buku;
                                    $totals_per_jenis[$parent_id]['total_penambahan'] += $total_penambahan;
                                    $totals_per_jenis[$parent_id]['total_pengurangan'] += $total_pengurangan;
                                    $totals_per_jenis[$parent_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                                    $totals_per_jenis[$parent_id]['total_penyusutan'] += $total_penyusutan;
                                    $totals_per_jenis[$parent_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                                }

                                // Menampilkan total per jenis kendaraan
                                ?>
                                <tr class="bg-info text-right">

                                    <td class="text-left"><strong>Sub Total <?= $nama_kendaraan_jenis; ?></strong></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_penambahan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_pengurangan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_rupiah'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_lalu'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_lalu'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_penyusutan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_ini'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_final'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                            <tr class="text-center bg-light">
                                <th class="text-left">Total Kendaraan / Alat Angkut</th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="14"><strong>9. Inventaris/Perabotan Kantor</strong></td>
                            </tr>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = [];
                            // Array untuk nama bangunan berdasarkan parent_id
                            $nama_inventaris = [
                                2844 => "Meubelair Kantor",
                                2846 => "Mesin-mesin Kantor",
                                2848 => "Rupa2 Inv. Ktr Lainnya"
                            ];
                            foreach ($susut_inventaris as $row) {
                                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $parent_id => $upk_data) {
                                // Judul berdasarkan parent_id
                                $nama_inventaris_jenis = $nama_inventaris[$parent_id] ?? "Instalasi inventaris Lainnya";
                                echo "<tr><td colspan='14' class='bg-primary text-white'><strong>{$nama_inventaris_jenis}</strong></td></tr>";

                                // Inisialisasi total per jenis bangunan
                                $totals_per_jenis[$parent_id] = [
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
                                foreach ($upk_data as $id_no_per => $assets) {
                                    $name = $assets[0]->name;
                                    // echo "<tr><td class='bg-secondary'></td><td colspan='14' class='bg-secondary text-white'><strong> {$name}</strong></td></tr>";

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
                                    </tr>
                                <?php

                                    // Menambahkan total per bagian/upk ke total per jenis pengolahan air
                                    $totals_per_jenis[$parent_id]['total_rupiah'] += $total_rupiah;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku'] += $total_nilai_buku;
                                    $totals_per_jenis[$parent_id]['total_penambahan'] += $total_penambahan;
                                    $totals_per_jenis[$parent_id]['total_pengurangan'] += $total_pengurangan;
                                    $totals_per_jenis[$parent_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                                    $totals_per_jenis[$parent_id]['total_penyusutan'] += $total_penyusutan;
                                    $totals_per_jenis[$parent_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                    $totals_per_jenis[$parent_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                                }

                                // Menampilkan total per jenis inventaris
                                ?>
                                <tr class="bg-info text-right">

                                    <td class="text-left"><strong>Sub Total<?= $nama_inventaris_jenis; ?></strong></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_penambahan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_pengurangan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_rupiah'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_lalu'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_lalu'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_penyusutan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_ini'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_final'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                            <tr class="text-center bg-light">
                                <th class="text-left">Total Inventaris / Perabotan Kantor</th>
                                <th class="text-right"><?= number_format($total_inventaris['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_nilai_buku_final'], 0, ',', '.'); ?></th>
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
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>