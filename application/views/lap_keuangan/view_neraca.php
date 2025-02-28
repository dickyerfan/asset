<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('lap_keuangan/neraca'); ?>" method="get">
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
                    <a href="<?= base_url('lap_keuangan/neraca') ?>" style="text-decoration: none;"><button class="neumorphic-button ms-2"> Tahun ini</button></a>
                    <div class="navbar-nav ms-auto ">
                        <a href="<?= base_url('lap_keuangan/neraca/neraca_cetak'); ?>" target="_blank"><button class=" neumorphic-button float-right"><i class="fas fa-print"></i> Cetak</button></a>
                    </div>

                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-12 text-center">
                        <h5><strong><?= strtoupper($title); ?></strong></h5>
                        <h5><strong>Per 31 Desember <?= $tahun_lap ?> dan 31 Desember <?= $tahun_lalu ?></strong></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Uraian</th>
                                <th> <?= htmlspecialchars($tahun_lap) ?> (Unaudited)</th>
                                <th> <?= htmlspecialchars($tahun_lap) ?> (Audited)</th>
                                <th> <?= htmlspecialchars($tahun_lalu) ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $kategori_sebelumnya = '';
                            $data_tahun_lalu = [];
                            $data_tahun_sekarang = [];

                            // **1. Simpan data tahun lalu & tahun sekarang dalam array terpisah**
                            foreach ($neraca as $row) {
                                if ($row->tahun_neraca == $tahun_lalu) {
                                    $data_tahun_lalu[$row->akun] = $row->nilai_neraca;
                                }
                                if ($row->tahun_neraca == $tahun_lap) {
                                    $data_tahun_sekarang[] = $row;
                                }
                            }

                            // **Variabel untuk menyimpan total kategori**
                            $total_aset_lancar = $total_aset_tidak_lancar = 0;
                            $total_liabilitas_jangka_pendek = $total_liabilitas_jangka_panjang = $total_ekuitas = 0;
                            $total_aset_lancar_lalu = $total_aset_tidak_lancar_lalu = 0;
                            $total_liabilitas_jangka_pendek_lalu = $total_liabilitas_jangka_panjang_lalu = $total_ekuitas_lalu = 0;

                            foreach ($data_tahun_sekarang as $row) {
                                // **Cek apakah kategori berubah, jika iya, tampilkan kategori sebagai judul**
                                if ($kategori_sebelumnya !== $row->kategori) {
                                    if ($kategori_sebelumnya == 'Aset Lancar') {
                                        echo "<tr class='font-weight-bold bg-light'>";
                                        echo "<td colspan='2'>Jumlah Aset Lancar</td>";
                                        echo "<td class='text-right'>" . number_format($total_aset_lancar, 0, ',', '.') . "</td>";
                                        echo "<td ></td>";
                                        echo "<td class='text-right'>" . number_format($total_aset_lancar_lalu, 0, ',', '.') . "</td>";
                                        echo "</tr>";
                                    } elseif ($kategori_sebelumnya == 'Aset Tidak Lancar') {
                                        echo "<tr class='font-weight-bold bg-light'>";
                                        echo "<td colspan='2'>Jumlah Aset Tidak Lancar</td>";
                                        echo "<td class='text-right'>" . number_format($total_aset_tidak_lancar, 0, ',', '.') . "</td>";
                                        echo "<td ></td>";
                                        echo "<td class='text-right'>" . number_format($total_aset_tidak_lancar_lalu, 0, ',', '.') . "</td>";
                                        echo "</tr>";

                                        // **Total Aset ditampilkan setelah Total Aset Tidak Lancar**
                                        $total_aset = $total_aset_lancar + $total_aset_tidak_lancar;
                                        $total_aset_lalu = $total_aset_lancar_lalu + $total_aset_tidak_lancar_lalu;
                                        echo "<tr class='font-weight-bold bg-warning'>";
                                        echo "<td colspan='2'>JUMLAH ASET</td>";
                                        echo "<td class='text-right'>" . number_format($total_aset, 0, ',', '.') . "</td>";
                                        echo "<td ></td>";
                                        echo "<td class='text-right'>" . number_format($total_aset_lalu, 0, ',', '.') . "</td>";
                                        echo "</tr>";
                                    } elseif ($kategori_sebelumnya == 'Liabilitas Jangka Pendek') {
                                        echo "<tr class='font-weight-bold bg-light'>";
                                        echo "<td colspan='2'>Jumlah Liabilitas Jangka Pendek</td>";
                                        echo "<td class='text-right'>" . number_format($total_liabilitas_jangka_pendek, 0, ',', '.') . "</td>";
                                        echo "<td ></td>";
                                        echo "<td class='text-right'>" . number_format($total_liabilitas_jangka_pendek_lalu, 0, ',', '.') . "</td>";
                                        echo "</tr>";
                                    } elseif ($kategori_sebelumnya == 'Liabilitas Jangka Panjang') {
                                        echo "<tr class='font-weight-bold bg-light'>";
                                        echo "<td colspan='2'>Jumlah Liabilitas Jangka Panjang</td>";
                                        echo "<td class='text-right'>" . number_format($total_liabilitas_jangka_panjang, 0, ',', '.') . "</td>";
                                        echo "<td ></td>";
                                        echo "<td class='text-right'>" . number_format($total_liabilitas_jangka_panjang_lalu, 0, ',', '.') . "</td>";
                                        echo "</tr>";
                                    } elseif ($kategori_sebelumnya == 'Ekuitas') {
                                        echo "<tr class='font-weight-bold bg-light'>";
                                        echo "<td colspan='2'>Jumlah Ekuitas</td>";
                                        echo "<td class='text-right'>" . number_format($total_ekuitas, 0, ',', '.') . "</td>";
                                        echo "<td ></td>";
                                        echo "<td class='text-right'>" . number_format($total_ekuitas_lalu, 0, ',', '.') . "</td>";
                                        echo "</tr>";

                                        // **Total Liabilitas & Ekuitas setelah Total Ekuitas**
                                        $total_liabilitas_ekuitas = $total_liabilitas_jangka_pendek + $total_liabilitas_jangka_panjang + $total_ekuitas;
                                        $total_liabilitas_ekuitas_lalu = $total_liabilitas_jangka_pendek_lalu + $total_liabilitas_jangka_panjang_lalu + $total_ekuitas_lalu;
                                        echo "<tr class='font-weight-bold bg-warning text-center'>";
                                        echo "<td colspan='2'>JUMLAH LIABILITAS & EKUITAS</td>";
                                        echo "<td class='text-right'>" . number_format($total_liabilitas_ekuitas, 0, ',', '.') . "</td>";
                                        echo "<td ></td>";
                                        echo "<td class='text-right'>" . number_format($total_liabilitas_ekuitas_lalu, 0, ',', '.') . "</td>";
                                        echo "</tr>";
                                    }

                                    echo "<tr class='font-weight-bold'>";
                                    echo "<td colspan='5'>" . htmlspecialchars($row->kategori) . "</td>";
                                    echo "</tr>";
                                    $kategori_sebelumnya = $row->kategori;
                                }

                                // **Ambil nilai tahun lalu berdasarkan akun**
                                $nilai_tahun_lalu = isset($data_tahun_lalu[$row->akun]) ? $data_tahun_lalu[$row->akun] : 0;

                                // **Tampilkan data neraca**
                                echo "<tr>";
                                echo "<td class='text-center'>{$row->no_neraca}</td>";
                                echo "<td>" . htmlspecialchars($row->akun) . "</td>";
                                echo "<td class='text-right'>" . number_format($row->nilai_neraca, 0, ',', '.') . "</td>";
                                echo "<td ></td>";
                                echo "<td class='text-right'>" . number_format($nilai_tahun_lalu, 0, ',', '.') . "</td>";
                                echo "</tr>";

                                // **Hitung total berdasarkan kategori**
                                if ($row->kategori == 'Aset Lancar') {
                                    $total_aset_lancar += $row->nilai_neraca;
                                    $total_aset_lancar_lalu += $nilai_tahun_lalu;
                                } elseif ($row->kategori == 'Aset Tidak Lancar') {
                                    $total_aset_tidak_lancar += $row->nilai_neraca;
                                    $total_aset_tidak_lancar_lalu += $nilai_tahun_lalu;
                                } elseif ($row->kategori == 'Liabilitas Jangka Pendek') {
                                    $total_liabilitas_jangka_pendek += $row->nilai_neraca;
                                    $total_liabilitas_jangka_pendek_lalu += $nilai_tahun_lalu;
                                } elseif ($row->kategori == 'Liabilitas Jangka Panjang') {
                                    $total_liabilitas_jangka_panjang += $row->nilai_neraca;
                                    $total_liabilitas_jangka_panjang_lalu += $nilai_tahun_lalu;
                                } elseif ($row->kategori == 'Ekuitas') {
                                    $total_ekuitas += $row->nilai_neraca;
                                    $total_ekuitas_lalu += $nilai_tahun_lalu;
                                }
                            }

                            // **Tampilkan total terakhir setelah looping selesai**
                            if ($kategori_sebelumnya == 'Aset Lancar') {
                                echo "<tr class='font-weight-bold bg-light'>";
                                echo "<td colspan='2'>Jumlah Aset Lancar</td>";
                                echo "<td class='text-right'>" . number_format($total_aset_lancar, 0, ',', '.') . "</td>";
                                echo "<td ></td>";
                                echo "<td class='text-right'>" . number_format($total_aset_lancar_lalu, 0, ',', '.') . "</td>";
                                echo "</tr>";
                            } elseif ($kategori_sebelumnya == 'Aset Tidak Lancar') {
                                echo "<tr class='font-weight-bold bg-light'>";
                                echo "<td colspan='2'>Jumlah Aset Tidak Lancar</td>";
                                echo "<td class='text-right'>" . number_format($total_aset_tidak_lancar, 0, ',', '.') . "</td>";
                                echo "<td ></td>";
                                echo "<td class='text-right'>" . number_format($total_aset_tidak_lancar_lalu, 0, ',', '.') . "</td>";
                                echo "</tr>";

                                // **Total Aset**
                                $total_aset = $total_aset_lancar + $total_aset_tidak_lancar;
                                $total_aset_lalu = $total_aset_lancar_lalu + $total_aset_tidak_lancar_lalu;
                                echo "<tr class='font-weight-bold bg-warning'>";
                                echo "<td colspan='2'>JUMLAH ASET</td>";
                                echo "<td class='text-right'>" . number_format($total_aset, 0, ',', '.') . "</td>";
                                echo "<td ></td>";
                                echo "<td class='text-right'>" . number_format($total_aset_lalu, 0, ',', '.') . "</td>";
                                echo "</tr>";
                            } elseif ($kategori_sebelumnya == 'Liabilitas Jangka Pendek') {
                                echo "<tr class='font-weight-bold bg-light'>";
                                echo "<td colspan='2'>Jumlah Liabilitas Jangka Pendek</td>";
                                echo "<td class='text-right'>" . number_format($total_liabilitas_jangka_pendek, 0, ',', '.') . "</td>";
                                echo "<td ></td>";
                                echo "<td class='text-right'>" . number_format($total_liabilitas_jangka_pendek_lalu, 0, ',', '.') . "</td>";
                                echo "</tr>";
                            } elseif ($kategori_sebelumnya == 'Liabilitas Jangka Panjang') {
                                echo "<tr class='font-weight-bold bg-light'>";
                                echo "<td colspan='2'>Jumlah Liabilitas Jangka Panjang</td>";
                                echo "<td class='text-right'>" . number_format($total_liabilitas_jangka_panjang, 0, ',', '.') . "</td>";
                                echo "<td ></td>";
                                echo "<td class='text-right'>" . number_format($total_liabilitas_jangka_panjang_lalu, 0, ',', '.') . "</td>";
                                echo "</tr>";
                            } elseif ($kategori_sebelumnya == 'Ekuitas') {
                                echo "<tr class='font-weight-bold bg-light'>";
                                echo "<td colspan='2'>Total Ekuitas</td>";
                                echo "<td class='text-right'>" . number_format($total_ekuitas, 0, ',', '.') . "</td>";
                                echo "<td ></td>";
                                echo "<td class='text-right'>" . number_format($total_ekuitas_lalu, 0, ',', '.') . "</td>";
                                echo "</tr>";

                                // **Total Liabilitas & Ekuitas**
                                $total_liabilitas_ekuitas = $total_liabilitas_jangka_pendek + $total_liabilitas_jangka_panjang + $total_ekuitas;
                                $total_liabilitas_ekuitas_lalu = $total_liabilitas_jangka_pendek_lalu + $total_liabilitas_jangka_panjang_lalu + $total_ekuitas_lalu;
                                echo "<tr class='font-weight-bold bg-warning'>";
                                echo "<td colspan='2'>JUMLAH LIABILITAS & EKUITAS</td>";
                                echo "<td class='text-right'>" . number_format($total_liabilitas_ekuitas, 0, ',', '.') . "</td>";
                                echo "<td ></td>";
                                echo "<td class='text-right'>" . number_format($total_liabilitas_ekuitas_lalu, 0, ',', '.') . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>