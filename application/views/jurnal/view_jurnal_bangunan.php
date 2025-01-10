<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('jurnal/bangunan'); ?>" method="get">
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
                    <div class="navbar-nav ms-1">
                        <a href="<?= base_url('jurnal/bangunan') ?>" style="text-decoration: none;"><button class=" neumorphic-button"> Tahun ini</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('jurnal/cetak_bangunan') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak Jurnal</button></a>
                    </div>
                </nav>
            </div>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">

                        <?php
                        if (empty($tahun_lap)) {
                            // $bulan_lap = date('m');
                            $tahun_lap = date('Y');
                        }
                        ?>
                        <h5><?= strtoupper($title) . ' <br> PER 31 DESEMBER ' . $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama Asset</th>
                                <th>Penyusutan</th>
                            </tr>
                        </thead>
                        <tbody>
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
                            foreach ($susut as $row) {
                                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $parent_id => $upk_data) {
                                // Judul berdasarkan parent_id
                                $nama_bangunan_jenis = $nama_bangunan[$parent_id] ?? "Bangunan Lainnya";
                                // echo "<tr><td colspan='14' class='bg-primary text-white'><strong>{$nama_bangunan_jenis}</strong></td></tr>";

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
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-left"><strong><?= $name; ?></strong></td>
                                        <td><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
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
                                    <td></td>
                                    <td class="text-left"><strong>Sub Total <?= $nama_bangunan_jenis; ?></strong></td>
                                    <td><?= number_format($totals_per_jenis[$parent_id]['total_penyusutan'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="text-center bg-light">
                                <th></th>
                                <th class="text-left">Total Bangunan</th>
                                <th class="text-right"><?= number_format($total_bangunan['total_penyusutan'], 0, ',', '.'); ?></th>
                            </tr>
                        </tfoot>
                    </table> -->
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Bagian UPK</th>
                                <th>Total Penyusutan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $nama_bangunan = [
                                2671 => "Bangunan Kantor",
                                2674 => "Bangunan Laboratorium",
                                2676 => "Bangunan Gedung Peralatan",
                                2678 => "Bangunan Bengkel",
                                2680 => "Instalasi Umum Lainnya"
                            ];

                            $grouped_by_upk = []; // Array untuk mengelompokkan data berdasarkan bagian_upk

                            // Mengelompokkan data berdasarkan bagian_upk
                            foreach ($susut as $row) {
                                if (array_key_exists($row->parent_id, $nama_bangunan)) {
                                    $bagian_upk = $row->nama_bagian; // Ambil bagian_upk dari data
                                    if (!isset($grouped_by_upk[$bagian_upk])) {
                                        $grouped_by_upk[$bagian_upk] = 0;
                                    }
                                    $grouped_by_upk[$bagian_upk] += $row->penambahan_penyusutan; // Menambahkan penyusutan
                                }
                            }
                            // Menampilkan hasil pengelompokan
                            foreach ($grouped_by_upk as $bagian_upk => $total_penyusutan) {
                            ?>
                                <tr class="text-right">
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td class="text-left"><?= $bagian_upk; ?></td>
                                    <td><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="text-center bg-light">
                                <th></th>
                                <th class="text-left">Total Semua Bangunan</th>
                                <th class="text-right"><?= number_format(array_sum($grouped_by_upk), 0, ',', '.'); ?></th>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
</section>
</div>