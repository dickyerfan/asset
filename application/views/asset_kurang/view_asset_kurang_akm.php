<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('asset/asset_kurang_akm') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('asset/asset_kurang_akm'); ?>" method="get">
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
                        <form id="form_no_per" action="<?= base_url('asset/asset_kurang_akm'); ?>" method="get">
                            <div style="display: flex; align-items: center;">
                                <select name="no_per" id="no_per" class="form-control select2" style="width:200px;">
                                    <option value="">Pilih No Perkiraan</option>
                                    <?php foreach ($no_per as $row) :  ?>
                                        <option value="<?= $row->id; ?>"><?= $row->name; ?></option>
                                    <?php endforeach;  ?>
                                </select>
                                <input type="hidden" name="tahun" value="<?= $this->input->get('tahun') ?: date('Y') ?>">
                            </div>
                        </form>
                    </div> -->
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('asset/asset_kurang_akm_cetak'); ?>" target="_blank"><button class=" neumorphic-button float-right"><i class="fas fa-print"></i> Cetak</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('asset/asset_kurang'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
                    </div>
                </nav>
            </div>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title) . ' TAHUN ' . $tahun_lap; ?></h5>
                        <?php if (!empty($this->input->get('no_per'))) : ?>
                            <h5><?= isset($no_per_descriptions[$no_perkiraan]) ? $no_per_descriptions[$no_perkiraan] : 'Tidak Ditemukan'; ?></h5>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <!-- <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Uraian</th>
                                <th>Lokasi</th>
                                <th>Tgl perolehan</th>
                                <th>No Bukti</th>
                                <th>Rupiah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = [];
                            $no_per = [
                                218 => 'TANAH & PENYEMPURNAAN TANAH',
                                220 => 'INSTALASI SUMBER',
                                222 => 'INSTALASI POMPA',
                                224 => 'INSTALASI PENGOLAHAN',
                                226 => 'INSTALASI TRANSMISI & DISTRIBUSI',
                                228 => 'BANGUNAN & GEDUNG',
                                244 => 'PERALATAN & PERLENGKAPAN',
                                246 => 'KENDARAAN',
                                248 => 'INVENTARIS & PERABOTAN KANTOR'
                            ];

                            foreach ($susut as $row) {
                                $grouped_data[$row->grand_id][$row->id_no_per][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $grand_id => $upk_data) {
                                // Judul berdasarkan grand_id
                                $nama_perkiraan = $no_per[$grand_id] ?? "Bangunan Lainnya";
                                echo "<tr><td class='bg-primary'></td><td colspan='5' class='bg-primary text-white'><strong>{$nama_perkiraan}</strong></td></tr>";

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
                                foreach ($upk_data as $id_no_per => $assets) {

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
                                                if (strlen($nama_asset) > 65) {
                                                    $nama_asset = substr($nama_asset, 0, 65) . '...';
                                                }
                                                ?>
                                                <?= $nama_asset; ?>
                                            </td>
                                            <td>
                                                <?php if ($row->id_bagian == 2) : ?>
                                                    <?= 'Kantor Pusat'; ?>
                                                <?php else : ?>
                                                    <?= $row->nama_bagian; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($row->status == 2) {
                                                    echo  date('d-m-Y', strtotime($row->tanggal_persediaan));
                                                } else {
                                                    echo date('d-m-Y', strtotime($row->tanggal));
                                                }  ?>
                                            </td>
                                            <td><?= $row->no_bukti_vch; ?></td>
                                            <td class="text-right"><?= number_format($row->akm_thn_ini * -1, 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                <?php
                                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                }

                                ?>
                                <tr class="bg-info text-right">
                                    <td></td>
                                    <td class="text-left"><strong>Sub Total <?= $nama_perkiraan; ?></strong></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><strong><?= number_format($totals_per_jenis[$grand_id]['total_akm_thn_ini'] * -1, 0, ',', '.'); ?></strong></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="text-center bg-light">
                                <th colspan="5" class="text-center">TOTAL</th>
                                <th class="text-right"><?= number_format($totals['total_akm_thn_ini'] * -1, 0, ',', '.'); ?></th>
                            </tr>
                        </tfoot> -->
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Uraian</th>
                                <th>Harga Perolehan</th>
                                <th>Akm Penyusutan</th>
                                <th>Nilai Buku</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $grouped_data = [];
                            $totals_per_jenis = [];
                            $no_per = [
                                218 => 'TANAH & PENYEMPURNAAN TANAH',
                                220 => 'INSTALASI SUMBER',
                                222 => 'INSTALASI POMPA',
                                224 => 'INSTALASI PENGOLAHAN',
                                226 => 'INSTALASI TRANSMISI & DISTRIBUSI',
                                228 => 'BANGUNAN & GEDUNG',
                                244 => 'PERALATAN & PERLENGKAPAN',
                                246 => 'KENDARAAN',
                                248 => 'INVENTARIS & PERABOTAN KANTOR'
                            ];

                            foreach ($susut as $row) {
                                $grouped_data[$row->grand_id][$row->id_no_per][] = $row;
                            }

                            // Menampilkan data yang telah dikelompokkan
                            foreach ($grouped_data as $grand_id => $upk_data) {
                                // Judul berdasarkan grand_id
                                $nama_perkiraan = $no_per[$grand_id] ?? "Bangunan Lainnya";
                                echo "<tr><td class='bg-primary'></td><td colspan='4' class='bg-primary text-white'><strong>{$nama_perkiraan}</strong></td></tr>";

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
                                foreach ($upk_data as $id_no_per => $assets) {

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
                                                if (strlen($nama_asset) > 85) {
                                                    $nama_asset = substr($nama_asset, 0, 85) . '...';
                                                }
                                                ?>
                                                <?= $nama_asset; ?>
                                            </td>
                                            <td class="text-right"><?= number_format($row->rupiah * -1, 0, ',', '.'); ?></td>
                                            <td class="text-right"><?= number_format($row->akm_thn_ini * -1, 0, ',', '.'); ?></td>
                                            <td class="text-right"><?= number_format($row->nilai_buku_final * -1, 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                <?php
                                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                                }
                                ?>
                                <tr class="bg-info text-right">
                                    <td></td>
                                    <td class="text-left"><strong>SUB TOTAL <?= $nama_perkiraan; ?></strong></td>
                                    <td><strong><?= number_format($totals_per_jenis[$grand_id]['total_rupiah'] * -1, 0, ',', '.'); ?></strong></td>
                                    <td><strong><?= number_format($totals_per_jenis[$grand_id]['total_akm_thn_ini'] * -1, 0, ',', '.'); ?></strong></td>
                                    <td><strong><?= number_format($totals_per_jenis[$grand_id]['total_nilai_buku_final'] * -1, 0, ',', '.'); ?></strong></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="text-center bg-light">
                                <th></th>
                                <th class="text-left">TOTAL</th>
                                <th class="text-right"><?= number_format($totals['total_rupiah'] * -1, 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_akm_thn_ini'] * -1, 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_nilai_buku_final'] * -1, 0, ',', '.'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>