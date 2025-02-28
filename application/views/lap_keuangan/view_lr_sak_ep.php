<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('lap_keuangan/lr_sak_ep'); ?>" method="get">
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
                    <a href="<?= base_url('lap_keuangan/lr_sak_ep') ?>" style="text-decoration: none;"><button class="neumorphic-button ms-2"> Tahun ini</button></a>
                    <div class="navbar-nav ms-auto ">
                        <a href="<?= base_url('lap_keuangan/lr_sak_ep_cetak'); ?>" target="_blank"><button class=" neumorphic-button float-right"><i class="fas fa-print"></i> Cetak</button></a>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-12 text-center">
                        <h5><strong><?= strtoupper($title); ?></strong></h5>
                        <h5><strong>UNTUK TAHUN YANG BERAKHIR TANGGAL PER 31 DESEMBER <?= $tahun_lap ?> DAN 31 DESEMBER <?= $tahun_lalu ?></strong></h5>
                        <!-- <h5>(Berdasarkan Permen Otoda Nomor 8 Tahun 2000)</h5> -->
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Uraian</th>
                                <th><?= htmlspecialchars($tahun_lap) ?></th>
                                <th><?= htmlspecialchars($tahun_lalu) ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $kategori_sebelumnya = '';
                            $data_tahun_lalu = [];
                            $data_tahun_sekarang = [];

                            // **1. Simpan data tahun lalu & tahun sekarang dalam array terpisah**
                            foreach ($lr_sak_ep as $row) {
                                if ($row->tahun_lr_sak_ep == $tahun_lalu) {
                                    $data_tahun_lalu[$row->akun] = $row->nilai_lr_sak_ep;
                                }
                                if ($row->tahun_lr_sak_ep == $tahun_lap) {
                                    $data_tahun_sekarang[] = $row;
                                }
                            }

                            // **Variabel untuk menyimpan total kategori**
                            $total_pendapatan_usaha = $total_beban_usaha = 0;
                            $total_beban_umum_administrasi = $total_pendapatan_beban_lain = $total_beban_pajak_penghasilan = $total_penghasilan_komprehensif_lain = 0;
                            $total_labarugi_operasional = 0;
                            $total_pendapatan_usaha_lalu = $total_beban_usaha_lalu = 0;
                            $total_beban_umum_administrasi_lalu = $total_pendapatan_beban_lain_lalu = $total_beban_pajak_penghasilan_lalu = $total_penghasilan_komprehensif_lain_lalu = 0;
                            $total_labarugi_operasional_lalu = 0;

                            foreach ($data_tahun_sekarang as $row) {
                                // **Cek apakah kategori berubah, jika iya, tampilkan kategori sebagai judul**
                                if ($kategori_sebelumnya !== $row->kategori) {
                                    if ($kategori_sebelumnya == 'Pendapatan Usaha') {
                                        echo "<tr class='font-weight-bold bg-light'>";
                                        echo "<td colspan='2'>Jumlah Pendapatan Usaha</td>";
                                        echo "<td class='text-right'>" . number_format($total_pendapatan_usaha, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_pendapatan_usaha_lalu, 0, ',', '.') . "</td>";
                                        echo "</tr>";
                                    } elseif ($kategori_sebelumnya == 'Beban Usaha') {
                                        echo "<tr class='font-weight-bold bg-light'>";
                                        echo "<td colspan='2'>Jumlah Beban Usaha</td>";
                                        echo "<td class='text-right'>" . number_format($total_beban_usaha, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_beban_usaha_lalu, 0, ',', '.') . "</td>";
                                        echo "</tr>";

                                        // **Total laba rugi ditampilkan setelah Total pendapatan usaha - Beban Usaha**
                                        $total_labarugi_kotor = $total_pendapatan_usaha - $total_beban_usaha;
                                        $total_labarugi_kotor_lalu = $total_pendapatan_usaha_lalu - $total_beban_usaha_lalu;
                                        echo "<tr class='font-weight-bold bg-warning'>";
                                        echo "<td colspan='2'>LABA RUGI KOTOR</td>";
                                        echo "<td class='text-right'>" . number_format($total_labarugi_kotor, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_labarugi_kotor_lalu, 0, ',', '.') . "</td>";
                                        echo "</tr>";
                                    } elseif ($kategori_sebelumnya == 'Beban Umum Dan Administrasi') {
                                        echo "<tr class='font-weight-bold bg-light'>";
                                        echo "<td colspan='2'>Beban Umum Dan Administrasi</td>";
                                        echo "<td class='text-right'>" . number_format($total_beban_umum_administrasi, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_beban_umum_administrasi_lalu, 0, ',', '.') . "</td>";
                                        echo "</tr>";

                                        $total_labarugi_operasional = $total_labarugi_kotor - $total_beban_umum_administrasi;
                                        $total_labarugi_operasional_lalu = $total_labarugi_kotor_lalu - $total_beban_umum_administrasi_lalu;
                                        echo "<tr class='font-weight-bold bg-warning'>";
                                        echo "<td colspan='2'>LABA RUGI OPERASIONAL</td>";
                                        echo "<td class='text-right'>" . number_format($total_labarugi_operasional, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_labarugi_operasional_lalu, 0, ',', '.') . "</td>";
                                        echo "</tr>";
                                    } elseif ($kategori_sebelumnya == 'Pendapatan - Beban Lain-lain') {
                                        echo "<tr class='font-weight-bold bg-light'>";
                                        echo "<td colspan='2'>Jumlah Pendapatan - Beban Lain-lain</td>";
                                        echo "<td class='text-right'>" . number_format($total_pendapatan_beban_lain, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_pendapatan_beban_lain_lalu, 0, ',', '.') . "</td>";
                                        echo "</tr>";
                                        $total_labarugi_bersih_Sebelum_pajak = $total_labarugi_operasional + $total_pendapatan_beban_lain;
                                        $total_labarugi_bersih_Sebelum_pajak_lalu = $total_labarugi_operasional_lalu + $total_pendapatan_beban_lain_lalu;
                                        echo "<tr class='font-weight-bold bg-warning'>";
                                        echo "<td colspan='2'>LABA RUGI BERSIH SEBELUM PAJAK</td>";
                                        // echo "<td class='text-right'>" . number_format($total_labarugi_bersih_Sebelum_pajak, 0, ',', '.') . "</td>";

                                        echo "<th class='text-right'>
                                        <a href='" . base_url('lap_keuangan/beban_pajak/input_bpk_lr/' . $tahun_lap . '/' . $total_labarugi_bersih_Sebelum_pajak) . "' 
                                           onclick='return confirm(\"Apakah Anda yakin ingin menyimpan data ini ke Neraca?\");' 
                                           style='text-decoration: none; color: inherit;'>
                                            " . number_format($total_labarugi_bersih_Sebelum_pajak, 0, ',', '.') . "
                                        </a>
                                      </th>";

                                        echo "<td class='text-right'>" . number_format($total_labarugi_bersih_Sebelum_pajak_lalu, 0, ',', '.') . "</td>";
                                        echo "</tr>";
                                    } elseif ($kategori_sebelumnya == 'Beban Pajak Penghasilan') {
                                        echo "<tr class='font-weight-bold bg-light'>";
                                        echo "<td colspan='2'>Jumlah Beban Pajak Penghasilan</td>";
                                        echo "<td class='text-right'>" . number_format($total_beban_pajak_penghasilan, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_beban_pajak_penghasilan_lalu, 0, ',', '.') . "</td>";
                                        echo "</tr>";
                                    } elseif ($kategori_sebelumnya == '(Kerugian) Penghasilan Komprehensip Lain') {
                                        echo "<tr class='font-weight-bold bg-light'>";
                                        echo "<td colspan='2'>Jumlah (Kerugian) Penghasilan Komprehensip Lain</td>";
                                        echo "<td class='text-right'>" . number_format($total_penghasilan_komprehensif_lain, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_penghasilan_komprehensif_lain_lalu, 0, ',', '.') . "</td>";
                                        echo "</tr>";

                                        $total_labarugi_bersih_setelah_pajak = $total_labarugi_bersih_Sebelum_pajak - ($total_beban_pajak_penghasilan + $total_penghasilan_komprehensif_lain);
                                        $total_labarugi_bersih_setelah_pajak_lalu = $total_labarugi_bersih_Sebelum_pajak_lalu - ($total_beban_pajak_penghasilan_lalu + $total_penghasilan_komprehensif_lain_lalu);
                                        echo "<tr class='font-weight-bold bg-warning text-left'>";
                                        echo "<td colspan='2'>JUMLAH PENGHASILAN KOMPREHENSIF TAHUN BERJALAN</td>";
                                        echo "<td class='text-right'>" . number_format($total_labarugi_bersih_setelah_pajak, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_labarugi_bersih_setelah_pajak_lalu, 0, ',', '.') . "</td>";
                                        echo "</tr>";
                                    }

                                    echo "<tr class='font-weight-bold'>";
                                    echo "<td colspan='4'>" . htmlspecialchars($row->kategori) . "</td>";
                                    echo "</tr>";
                                    $kategori_sebelumnya = $row->kategori;
                                }

                                // **Ambil nilai tahun lalu berdasarkan akun**
                                $nilai_tahun_lalu = isset($data_tahun_lalu[$row->akun]) ? $data_tahun_lalu[$row->akun] : 0;

                                // **Tampilkan data lr_sak_ep**

                                echo "<tr>";
                                echo "<td class='text-center'></td>";
                                echo "<td>" . htmlspecialchars($row->akun) . "</td>";
                                echo "<td class='text-right'>" . number_format(abs($row->nilai_lr_sak_ep), 0, ',', '.') . "</td>"; // abs itu fungsinya untuk mengubah nilai negatif menjadi positif
                                echo "<td class='text-right'>" . number_format(abs($nilai_tahun_lalu), 0, ',', '.') . "</td>";
                                echo "</tr>";


                                // **Hitung total berdasarkan kategori**
                                if ($row->kategori == 'Pendapatan Usaha') {
                                    $total_pendapatan_usaha += $row->nilai_lr_sak_ep ?? 0;
                                    $total_pendapatan_usaha_lalu += $nilai_tahun_lalu ?? 0;
                                } elseif ($row->kategori == 'Beban Usaha') {
                                    $total_beban_usaha += $row->nilai_lr_sak_ep ?? 0;
                                    $total_beban_usaha_lalu += $nilai_tahun_lalu ?? 0;
                                } elseif ($row->kategori == 'Beban Umum Dan Administrasi') {
                                    $total_beban_umum_administrasi += $row->nilai_lr_sak_ep ?? 0;
                                    $total_beban_umum_administrasi_lalu += $nilai_tahun_lalu ?? 0;
                                } elseif ($row->kategori == 'Pendapatan - Beban Lain-lain') {
                                    $total_pendapatan_beban_lain += $row->nilai_lr_sak_ep ?? 0;
                                    $total_pendapatan_beban_lain_lalu += $nilai_tahun_lalu ?? 0;
                                } elseif ($row->kategori == 'Beban Pajak Penghasilan') {
                                    $total_beban_pajak_penghasilan += $row->nilai_lr_sak_ep ?? 0;
                                    $total_beban_pajak_penghasilan_lalu += $nilai_tahun_lalu ?? 0;
                                } elseif ($row->kategori == '(Kerugian) Penghasilan Komprehensip Lain') {
                                    $total_penghasilan_komprehensif_lain += $row->nilai_lr_sak_ep ?? 0;
                                    $total_penghasilan_komprehensif_lain_lalu += $nilai_tahun_lalu ?? 0;
                                }
                            }

                            // **Tampilkan total terakhir setelah looping selesai**
                            if ($kategori_sebelumnya == 'Pendapatan Usaha') {
                                echo "<tr class='font-weight-bold bg-light'>";
                                echo "<td colspan='2'>Jumlah Pendapatan Usaha</td>";
                                echo "<td class='text-right'>" . number_format($total_pendapatan_usaha, 0, ',', '.') . "</td>";
                                echo "<td class='text-right'>" . number_format($total_pendapatan_usaha_lalu, 0, ',', '.') . "</td>";
                                echo "</tr>";
                            } elseif ($kategori_sebelumnya == 'Beban Usaha') {
                                echo "<tr class='font-weight-bold bg-light'>";
                                echo "<td colspan='2'>Jumlah Beban Usaha</td>";
                                echo "<td class='text-right'>" . number_format($total_beban_usaha, 0, ',', '.') . "</td>";
                                echo "<td class='text-right'>" . number_format($total_beban_usaha_lalu, 0, ',', '.') . "</td>";
                                echo "</tr>";

                                // **Total laba rugi ditampilkan setelah Total pendapatan usaha - Beban Usaha**
                                $total_labarugi_kotor = $total_pendapatan_usaha - $total_beban_usaha;
                                $total_labarugi_kotor_lalu = $total_pendapatan_usaha_lalu - $total_beban_usaha_lalu;
                                echo "<tr class='font-weight-bold bg-warning'>";
                                echo "<td colspan='2'>LABA RUGI KOTOR</td>";
                                echo "<td class='text-right'>" . number_format($total_labarugi_kotor, 0, ',', '.') . "</td>";
                                echo "<td class='text-right'>" . number_format($total_labarugi_kotor_lalu, 0, ',', '.') . "</td>";
                                echo "</tr>";
                            } elseif ($kategori_sebelumnya == 'Beban Umum Dan Administrasi') {

                                echo "<tr class='font-weight-bold bg-light'>";
                                echo "<td colspan='2'>Beban Umum Dan Administrasi</td>";
                                echo "<td class='text-right'>" . number_format($total_beban_umum_administrasi, 0, ',', '.') . "</td>";
                                echo "<td class='text-right'>" . number_format($total_beban_umum_administrasi_lalu, 0, ',', '.') . "</td>";
                                echo "</tr>";

                                $total_labarugi_operasional = $total_labarugi_kotor - $total_beban_umum_administrasi;
                                $total_labarugi_operasional_lalu = $total_labarugi_kotor_lalu - $total_beban_umum_administrasi_lalu;
                                echo "<tr class='font-weight-bold bg-warning'>";
                                echo "<td colspan='2'>LABA RUGI OPERASIONAL</td>";
                                echo "<td class='text-right'>" . number_format($total_labarugi_operasional, 0, ',', '.') . "</td>";
                                echo "<td class='text-right'>" . number_format($total_labarugi_operasional_lalu, 0, ',', '.') . "</td>";
                                echo "</tr>";
                            } elseif ($kategori_sebelumnya == 'Pendapatan - Beban Lain-lain') {
                                echo "<tr class='font-weight-bold bg-light'>";
                                echo "<td colspan='2'>Jumlah Pendapatan - Beban Lain-lain</td>";
                                echo "<td class='text-right'>" . number_format($total_pendapatan_beban_lain, 0, ',', '.') . "</td>";
                                echo "<td class='text-right'>" . number_format($total_pendapatan_beban_lain_lalu, 0, ',', '.') . "</td>";
                                echo "</tr>";

                                $total_labarugi_bersih_Sebelum_pajak = $total_labarugi_operasional + $total_pendapatan_beban_lain;
                                $total_labarugi_bersih_Sebelum_pajak_lalu = $total_labarugi_operasional_lalu + $total_pendapatan_beban_lain_lalu;
                                echo "<tr class='font-weight-bold bg-warning'>";
                                echo "<td colspan='2'>LABA RUGI BERSIH SEBELUM PAJAK</td>";
                                // echo "<td class='text-right'>" . number_format($total_labarugi_bersih_Sebelum_pajak, 0, ',', '.') . "</td>";

                                echo "<th class='text-right'>
                                <a href='" . base_url('lap_keuangan/beban_pajak/input_bpk_lr/' . $tahun_lap . '/' . $total_labarugi_bersih_Sebelum_pajak) . "' 
                                   onclick='return confirm(\"Apakah Anda yakin ingin menyimpan data ini ke Neraca?\");' 
                                   style='text-decoration: none; color: inherit;'>
                                    " . number_format($total_labarugi_bersih_Sebelum_pajak, 0, ',', '.') . "
                                </a>
                              </th>";

                                echo "<td class='text-right'>" . number_format($total_labarugi_bersih_Sebelum_pajak_lalu, 0, ',', '.') . "</td>";
                                echo "</tr>";
                            } elseif ($kategori_sebelumnya == 'Beban Pajak Penghasilan') {
                                echo "<tr class='font-weight-bold bg-light'>";
                                echo "<td colspan='2'>Jumlah Beban Pajak Penghasilan</td>";
                                echo "<td class='text-right'>" . number_format($total_beban_pajak_penghasilan, 0, ',', '.') . "</td>";
                                echo "<td class='text-right'>" . number_format($total_beban_pajak_penghasilan_lalu, 0, ',', '.') . "</td>";
                                echo "</tr>";
                            } elseif ($kategori_sebelumnya == '(Kerugian) Penghasilan Komprehensip Lain') {
                                echo "<tr class='font-weight-bold bg-light'>";
                                echo "<td colspan='2'>Jumlah (Kerugian) Penghasilan Komprehensip Lain</td>";
                                echo "<td class='text-right'>" . number_format($total_penghasilan_komprehensif_lain, 0, ',', '.') . "</td>";
                                echo "<td class='text-right'>" . number_format($total_penghasilan_komprehensif_lain_lalu, 0, ',', '.') . "</td>";
                                echo "</tr>";

                                $total_labarugi_bersih_setelah_pajak = $total_labarugi_bersih_Sebelum_pajak - ($total_beban_pajak_penghasilan + $total_penghasilan_komprehensif_lain);
                                $total_labarugi_bersih_setelah_pajak_lalu = $total_labarugi_bersih_Sebelum_pajak_lalu - ($total_beban_pajak_penghasilan_lalu + $total_penghasilan_komprehensif_lain_lalu);
                                echo "<tr class='font-weight-bold bg-warning text-left'>";
                                echo "<td colspan='2'>JUMLAH PENGHASILAN KOMPREHENSIF TAHUN BERJALAN</td>";
                                echo "<td class='text-right'>" . number_format($total_labarugi_bersih_setelah_pajak, 0, ',', '.') . "</td>";
                                echo "<td class='text-right'>" . number_format($total_labarugi_bersih_setelah_pajak_lalu, 0, ',', '.') . "</td>";
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