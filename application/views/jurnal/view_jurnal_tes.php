<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('jurnal'); ?>" method="get">
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
                        <a href="<?= base_url('jurnal') ?>" style="text-decoration: none;"><button class=" neumorphic-button"> Tahun ini</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('jurnal/cetak_jurnal') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak Jurnal</button></a>
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
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama Perkiraan</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $categories = [
                                'Bangunan' => [
                                    'data' => [
                                        2671 => "Bangunan Kantor",
                                        2674 => "Bangunan Laboratorium",
                                        2676 => "Bangunan Gedung Peralatan",
                                        2678 => "Bangunan Bengkel",
                                        2680 => "Instalasi Umum Lainnya"
                                    ],
                                    'label' => 'Akumulasi Penyusutan Gedung'
                                ],
                                'Peralatan' => [
                                    'data' => [
                                        2789 => "Alat-alat Pergudangan",
                                        2793 => "Alat-alat Laboratorium",
                                        2795 => "Alat-alat Telekomunikasi",
                                        2798 => "Alat-alat Bengkel",
                                        4251 => "Alat Perlengkapan Lainnya"
                                    ],
                                    'label' => 'Akumulasi Penyusutan Peralatan'
                                ],
                                'Kendaraan' => [
                                    'data' => [
                                        2850 => "Kendaraan Penumpang",
                                        2852 => "Kendaraan Angkut Barang",
                                        2854 => "Kendaraan Tangki Air",
                                        2855 => "Kendaraan Roda Dua"
                                    ],
                                    'label' => 'Akumulasi Penyusutan Kendaraan'
                                ],
                                'Inventaris' => [
                                    'data' => [
                                        2844 => "Meubelair Kantor",
                                        2846 => "Mesin-mesin Kantor",
                                        2848 => "Rupa2 Inv. Ktr Lainnya"
                                    ],
                                    'label' => 'Akumulasi Penyusutan Inventaris'
                                ],
                                'Pengolahan' => [
                                    'data' => [
                                        2104 => "Bangunan & Perbaikan",
                                        2107 => "Alat-alat Pengolahan air",
                                        2112 => "Resevoir/Penampungan Air",
                                        2115 => "Instalasi Pengolahan Air Lainnya"
                                    ],
                                    'label' => 'Akumulasi Penyusutan Pengolahan'
                                ],
                                'Sumber' => [
                                    'data' => [
                                        1569 => "Bangunan & Perbaikan",
                                        1571 => "Reservoir Penampungan Air",
                                        1572 => "Danau,Sungai & Sb.Lainnya",
                                        1575 => "Mata Air dan Terowongan",
                                        1576 => "Sumur-sumur",
                                        1577 => "Pipa Supply Utama",
                                        1579 => "Instalasi Sumber Lainnya",
                                    ],
                                    'label' => 'Akumulasi Penyusutan Sumber'
                                ],
                                'Pompa' => [
                                    'data' => [
                                        1907 => "Bangunan & Perbaikan",
                                        1909 => "Pembangkit Tenaga Listrik",
                                        1912 => "Peralatan Pompa",
                                        1915 => "Instalasi Pompa Lainnya"
                                    ],
                                    'label' => 'Akumulasi Penyusutan Pompa'
                                ],
                                'Transmisi & Distribusi' => [
                                    'data' => [
                                        2255 => "Bangunan & Perbaikan",
                                        2258 => "Reservoir,Tandon & MnrAir",
                                        2261 => "Pipa Transmisi dan Distribusi",
                                        2262 => "Pipa Dinas",
                                        2263 => "Meter Air Yang Terpasang",
                                        2264 => "Ledeng Umum",
                                        2548 => "Saluran Air Pemadam Kebakaran",
                                        2550 => "Jembatan Pipa",
                                        2552 => "Inst.Trans & Dist Lainnya"
                                    ],
                                    'label' => 'Akumulasi Penyusutan Transmisi & Distribusi'
                                ]

                            ];
                            $grand_total = 0;
                            foreach ($categories as $category => $details) {
                                $no = 1;
                                $grouped_by_upk = [];

                                // Mengelompokkan data berdasarkan bagian_upk
                                foreach ($susut as $row) {
                                    if (array_key_exists($row->parent_id, $details['data'])) {
                                        $bagian_upk = $row->nama_bagian;
                                        if (!isset($grouped_by_upk[$bagian_upk])) {
                                            $grouped_by_upk[$bagian_upk] = 0;
                                        }
                                        $grouped_by_upk[$bagian_upk] += $row->penambahan_penyusutan;
                                    }
                                }
                                // Hitung total per kategori dan tambahkan ke grand_total
                                $total_kategori = array_sum($grouped_by_upk);
                                $grand_total += $total_kategori;
                            ?>
                                <!-- Baris khusus untuk judul -->
                                <tr class="text-center bg-light">
                                    <th colspan="2" class="text-left">Total <?= $details['label']; ?></th>
                                    <th class="text-right"><?= number_format(array_sum($grouped_by_upk) / 12, 0, ',', '.'); ?></th>
                                    <th class="text-right"><?= number_format(array_sum($grouped_by_upk), 0, ',', '.'); ?></th>
                                    <th></th>
                                </tr>
                                <?php
                                // Menampilkan hasil pengelompokan
                                foreach ($grouped_by_upk as $bagian_upk => $total_penyusutan) {
                                    if ($total_penyusutan > 0 && ($total_penyusutan / 12) > 0) {
                                ?>
                                        <tr class="text-right">
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td class="text-left">
                                                <?= $bagian_upk == 'Umum'
                                                    ? $details['label'] . ' - Bondowoso'
                                                    : $details['label'] . ' - ' . $bagian_upk; ?>
                                            </td>
                                            <td><?= number_format($total_penyusutan / 12, 0, ',', '.'); ?></td>
                                            <td><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                                            <td></td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="text-center bg-success text-white">
                                <th colspan="2" class="text-left">Total Keseluruhan</th>
                                <th class="text-right"><?= number_format($grand_total / 12, 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($grand_total, 0, ',', '.'); ?></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>