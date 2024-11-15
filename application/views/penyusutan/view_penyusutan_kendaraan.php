<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('penyusutan/kendaraan'); ?>" method="get">
                        <div style="display: flex; align-items: center;">
                            <input type="submit" value="Pilih Tahun" class="neumorphic-button">
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
                        <a href="<?= base_url('penyusutan/kendaraan') ?>" style="text-decoration: none;"><button class=" neumorphic-button"> Tahun ini</button></a>
                    </div>
                    <div class="navbar-nav ms-1">
                        <a href="<?= base_url('penyusutan/kendaraan_penumpang') ?>" style="text-decoration: none;"><button class=" neumorphic-button"> Knd. Penumpang</button></a>
                    </div>
                    <div class="navbar-nav ms-1">
                        <a href="<?= base_url('penyusutan/kendaraan_angkut') ?>" style="text-decoration: none;"><button class=" neumorphic-button"> Knd. Angkut</button></a>
                    </div>
                    <div class="navbar-nav ms-1">
                        <a href="<?= base_url('penyusutan/kendaraan_tangki') ?>" style="text-decoration: none;"><button class=" neumorphic-button"> Tangki Air</button></a>
                    </div>
                    <div class="navbar-nav ms-1">
                        <a href="<?= base_url('penyusutan/kendaraan_roda_dua') ?>" style="text-decoration: none;"><button class=" neumorphic-button"> Roda Dua</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('penyusutan/cetak_kendaraan') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak Asset</button></a>
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
                        <h5><?= strtoupper($title) . ' ' . $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama Asset</th>
                                <th>Tgl perolehan</th>
                                <th>Umur</th>
                                <th>Prsen</th>
                                <th>Harga Perolehan Thn Lalu</th>
                                <th>Penambahan</th>
                                <th>Pengurangan</th>
                                <th>Harga Perolehan Thn Ini</th>
                                <th>Akm Thn Lalu</th>
                                <th>Nilai Buku Thn Lalu</th>
                                <th>Penyusutan</th>
                                <!-- <th>Pengurangan</th> -->
                                <th>Akm Thn Ini</th>
                                <th>Nilai Buku Thn Ini</th>
                            </tr>
                        </thead>
                        <tbody>
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
                            foreach ($susut as $row) {
                                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $parent_id => $upk_data) {
                                // Judul berdasarkan parent_id
                                $nama_kendaraan_jenis = $nama_kendaraan[$parent_id] ?? "Bangunan Lainnya";
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
                                    echo "<tr><td colspan='14' class='bg-secondary text-white'><strong> {$name}</strong></td></tr>";

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
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td>
                                                <?php
                                                $nama_asset = $row->nama_asset;
                                                if (strlen($nama_asset) > 55) {
                                                    $nama_asset = substr($nama_asset, 0, 55) . '...';
                                                }
                                                ?>
                                                <?= $nama_asset; ?>
                                            </td>
                                            <td class="text-center"><?= date('d-m-Y', strtotime($row->tanggal)); ?></td>
                                            <td class="text-center"><?= $row->umur; ?></td>
                                            <td class="text-center"><?= $row->persen_susut; ?></td>
                                            <td class="text-right"><?= number_format($row->nilai_buku, 0, ',', '.'); ?></td>
                                            <td class="text-right"><?= number_format($row->penambahan, 0, ',', '.'); ?></td>
                                            <td class="text-right"><?= number_format($row->pengurangan, 0, ',', '.'); ?></td>
                                            <td class="text-right"><?= number_format($row->rupiah, 0, ',', '.'); ?></td>
                                            <td class="text-right"><?= number_format($row->akm_thn_lalu, 0, ',', '.'); ?></td>
                                            <td class="text-right"><?= number_format($row->nilai_buku_lalu, 0, ',', '.'); ?></td>
                                            <td class="text-right"><?= number_format($row->penambahan_penyusutan, 0, ',', '.'); ?></td>
                                            <td class="text-right"><?= number_format($row->akm_thn_ini, 0, ',', '.'); ?></td>
                                            <td class="text-right"><?= number_format($row->nilai_buku_final, 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php
                                    }

                                    // Menampilkan total per bagian/upk
                                    ?>
                                    <tr class="bg-light text-right">
                                        <td colspan="2" class="text-left"><strong>Sub Total <?= $name; ?></strong></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
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
                                    <td colspan="2" class="text-left"><strong>Sub Total <?= $nama_kendaraan_jenis; ?></strong></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
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
                        </tbody>
                        <tfoot>
                            <tr class="text-center bg-light">
                                <th colspan="5" class="text-left">Total Kendaraan</th>
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
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>