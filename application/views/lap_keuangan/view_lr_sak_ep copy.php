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
                            <select id="tahun" name="tahun" class="form-control" style="margin-left: 15px;">
                                <?php
                                $currentYear = date('Y');
                                $selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear;
                                for ($year = 1989; $year <= $currentYear; $year++) {
                                    $selected = ($year == $selectedYear) ? 'selected' : '';
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
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Uraian</th>
                                <th><?= htmlspecialchars($tahun_lap) ?></th>
                                <th><?= htmlspecialchars($tahun_lap) ?> Audited</th>
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
                                    $data_tahun_sekarang[$row->id_lr_sak_ep] = $row;
                                }
                            }

                            // **Inisialisasi total kategori**
                            $total_pu = 0;
                            $total_pu_aud = 0;
                            $total_pu_lalu = 0;
                            $total_bu = 0;
                            $total_bu_aud = 0;
                            $total_bu_lalu = 0;
                            $total_bua = 0;
                            $total_bua_aud = 0;
                            $total_bua_lalu = 0;
                            $total_pbl = 0;
                            $total_pbl_aud = 0;
                            $total_pbl_lalu = 0;
                            $total_klb = 0;
                            $total_klb_aud = 0;
                            $total_klb_lalu = 0;
                            $total_bpp = 0;
                            $total_bpp_aud = 0;
                            $total_bpp_lalu = 0;
                            $total_pkl = 0;
                            $total_pkl_aud = 0;
                            $total_pkl_lalu = 0;

                            // **2. LOOPING: Tampilkan data dan hitung total per kategori**
                            $no_urut = 1;
                            foreach ($data_tahun_sekarang as $id => $row) {
                                $nilai_lalu = isset($data_tahun_lalu[$row->akun]) ? $data_tahun_lalu[$row->akun] : 0;
                                $kategori = $row->kategori;

                                // Jika kategori berubah, cetak subtotal kategori sebelumnya
                                if ($kategori_sebelumnya != '' && $kategori_sebelumnya != $kategori) {
                                    if ($kategori_sebelumnya == 'Pendapatan Usaha') {
                                        echo "<tr class='font-weight-bold bg-light'>";
                                        echo "<td class='text-center'>$no_urut</td><td>Jumlah Pendapatan Usaha</td>";
                                        echo "<td class='text-right'>" . number_format($total_pu, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_pu_aud, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_pu_lalu, 0, ',', '.') . "</td></tr>";
                                        $no_urut++;
                                    } elseif ($kategori_sebelumnya == 'Beban Usaha') {
                                        echo "<tr class='font-weight-bold bg-light'>";
                                        echo "<td class='text-center'>$no_urut</td><td>Jumlah Beban Usaha</td>";
                                        echo "<td class='text-right'>" . number_format($total_bu, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_bu_aud, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_bu_lalu, 0, ',', '.') . "</td></tr>";
                                        $no_urut++;

                                        $lr_kotor = $total_pu - $total_bu;
                                        $lr_kotor_aud = $total_pu_aud - $total_bu_aud;
                                        $lr_kotor_lalu = $total_pu_lalu - $total_bu_lalu;
                                        echo "<tr class='font-weight-bold bg-warning'>";
                                        echo "<td class='text-center'>$no_urut</td><td>LABA RUGI KOTOR</td>";
                                        echo "<td class='text-right'>" . number_format($lr_kotor, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($lr_kotor_aud, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($lr_kotor_lalu, 0, ',', '.') . "</td></tr>";
                                        $no_urut++;
                                    } elseif ($kategori_sebelumnya == 'Beban Umum Dan Administrasi') {
                                        echo "<tr class='font-weight-bold bg-light'>";
                                        echo "<td class='text-center'>$no_urut</td><td>Beban Umum Dan Administrasi</td>";
                                        echo "<td class='text-right'>" . number_format($total_bua, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_bua_aud, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_bua_lalu, 0, ',', '.') . "</td></tr>";
                                        $no_urut++;

                                        $lr_operasional = $lr_kotor - $total_bua;
                                        $lr_operasional_aud = $lr_kotor_aud - $total_bua_aud;
                                        $lr_operasional_lalu = $lr_kotor_lalu - $total_bua_lalu;
                                        echo "<tr class='font-weight-bold bg-warning'>";
                                        echo "<td class='text-center'>$no_urut</td><td>LABA RUGI OPERASIONAL</td>";
                                        echo "<td class='text-right'>" . number_format($lr_operasional, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($lr_operasional_aud, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($lr_operasional_lalu, 0, ',', '.') . "</td></tr>";
                                        $no_urut++;
                                    } elseif ($kategori_sebelumnya == 'Pendapatan - Beban Lain-lain') {
                                        echo "<tr class='font-weight-bold bg-light'>";
                                        echo "<td class='text-center'>$no_urut</td><td>Jumlah Pendapatan - Beban Lain-lain</td>";
                                        echo "<td class='text-right'>" . number_format($total_pbl, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_pbl_aud, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_pbl_lalu, 0, ',', '.') . "</td></tr>";
                                        $no_urut++;

                                        $lr_sebelum_pajak = $lr_operasional + $total_pbl;
                                        $lr_sebelum_pajak_aud = $lr_operasional_aud + $total_pbl_aud;
                                        $lr_sebelum_pajak_lalu = $lr_operasional_lalu + $total_pbl_lalu;
                                        echo "<tr class='font-weight-bold bg-warning'>";
                                        echo "<td class='text-center'>$no_urut</td><td>LABA RUGI BERSIH SEBELUM PAJAK</td>";
                                        echo "<td class='text-right'>" . number_format($lr_sebelum_pajak, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($lr_sebelum_pajak_aud, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($lr_sebelum_pajak_lalu, 0, ',', '.') . "</td></tr>";
                                        $no_urut++;

                                        // Set default Laba Sebelum Pajak = LR Bersih Sebelum Pajak (jika tidak ada KLB)
                                        $laba_sebelum_pajak = $lr_sebelum_pajak;
                                        $laba_sebelum_pajak_aud = $lr_sebelum_pajak_aud;
                                        $laba_sebelum_pajak_lalu = $lr_sebelum_pajak_lalu;
                                    } elseif ($kategori_sebelumnya == 'Keuntungan (Kerugian) Luar Biasa') {
                                        echo "<tr class='font-weight-bold bg-light'>";
                                        echo "<td class='text-center'>$no_urut</td><td>Jumlah Keuntungan (Kerugian) Luar Biasa</td>";
                                        echo "<td class='text-right'>" . number_format($total_klb, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_klb_aud, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_klb_lalu, 0, ',', '.') . "</td></tr>";
                                        $no_urut++;

                                        $laba_sebelum_pajak = $lr_sebelum_pajak - $total_klb;
                                        $laba_sebelum_pajak_aud = $lr_sebelum_pajak_aud - $total_klb_aud;
                                        $laba_sebelum_pajak_lalu = $lr_sebelum_pajak_lalu - $total_klb_lalu;
                                        echo "<tr class='font-weight-bold bg-warning'>";
                                        echo "<td class='text-center'>$no_urut</td><td>LABA SEBELUM PAJAK</td>";
                                        echo "<th class='text-right'>
                                        <a href='" . base_url('lap_keuangan/beban_pajak/input_lrbsp/' . $tahun_lap . '/' . $laba_sebelum_pajak) . "' 
                                           onclick='return confirm(\"Apakah Anda yakin ingin menyimpan data ini ke Neraca?\");' 
                                           style='text-decoration: none; color: inherit;'>
                                            " . number_format($laba_sebelum_pajak, 0, ',', '.') . "
                                        </a>
                                      </th>";
                                        echo "<td class='text-right'>" . number_format($laba_sebelum_pajak_aud, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($laba_sebelum_pajak_lalu, 0, ',', '.') . "</td></tr>";
                                        $no_urut++;
                                    } elseif ($kategori_sebelumnya == 'Beban Pajak Penghasilan') {
                                        echo "<tr class='font-weight-bold bg-light'>";
                                        echo "<td class='text-center'>$no_urut</td><td>Jumlah Beban Pajak Penghasilan</td>";
                                        echo "<td class='text-right'>" . number_format($total_bpp, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_bpp_aud, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($total_bpp_lalu, 0, ',', '.') . "</td></tr>";
                                        $no_urut++;

                                        $laba_setelah_pajak = $laba_sebelum_pajak - $total_bpp;
                                        $laba_setelah_pajak_aud = $laba_sebelum_pajak_aud - $total_bpp_aud;
                                        $laba_setelah_pajak_lalu = $laba_sebelum_pajak_lalu - $total_bpp_lalu;
                                        echo "<tr class='font-weight-bold bg-warning'>";
                                        echo "<td class='text-center'>$no_urut</td><td>LABA RUGI BERSIH SETELAH PAJAK</td>";
                                        echo "<td class='text-right'>" . number_format($laba_setelah_pajak, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($laba_setelah_pajak_aud, 0, ',', '.') . "</td>";
                                        echo "<td class='text-right'>" . number_format($laba_setelah_pajak_lalu, 0, ',', '.') . "</td></tr>";
                                        $no_urut++;
                                    }
                                }

                                // Cetak judul kategori jika baru
                                if ($kategori_sebelumnya != $kategori) {
                                    echo "<tr class='font-weight-bold'>";
                                    echo "<td colspan='5'>" . htmlspecialchars($kategori) . "</td></tr>";
                                    $kategori_sebelumnya = $kategori;
                                }

                                // Cetak baris data
                                echo "<tr>";
                                echo "<td class='text-center'></td>";
                                echo "<td>" . htmlspecialchars($row->akun) . "</td>";
                                echo "<td class='text-right'>" . number_format(abs($row->nilai_lr_sak_ep), 0, ',', '.') . "</td>";
                                echo "<td class='text-right'>";
                                if ($row->status == 1) {
                                    echo "<a href='" . base_url('lap_keuangan/lr_sak_ep/edit_audited/' . $row->id_lr_sak_ep . '?tahun=' . $this->input->get('tahun')) . "' 
                                    style='color: black; text-decoration: none;'>
                                    " . number_format(abs($row->nilai_lr_sak_ep_audited), 0, ',', '.') . "
                                    </a>";
                                } else {
                                    echo "<a href='#' onclick='alert_edit_lr_sak_ep(); return false;' 
                                    style='color: black; text-decoration: none; cursor: not-allowed;'>
                                    " . number_format(abs($row->nilai_lr_sak_ep_audited), 0, ',', '.') . "
                                    </a>";
                                }
                                echo "</td>";
                                echo "<td class='text-right'>" . number_format(abs($nilai_lalu), 0, ',', '.') . "</td>";
                                echo "</tr>";

                                // Akumulasi total per kategori
                                if ($kategori == 'Pendapatan Usaha') {
                                    $total_pu += $row->nilai_lr_sak_ep ?? 0;
                                    $total_pu_aud += $row->nilai_lr_sak_ep_audited ?? 0;
                                    $total_pu_lalu += $nilai_lalu ?? 0;
                                } elseif ($kategori == 'Beban Usaha') {
                                    $total_bu += $row->nilai_lr_sak_ep ?? 0;
                                    $total_bu_aud += $row->nilai_lr_sak_ep_audited ?? 0;
                                    $total_bu_lalu += $nilai_lalu ?? 0;
                                } elseif ($kategori == 'Beban Umum Dan Administrasi') {
                                    $total_bua += $row->nilai_lr_sak_ep ?? 0;
                                    $total_bua_aud += $row->nilai_lr_sak_ep_audited ?? 0;
                                    $total_bua_lalu += $nilai_lalu ?? 0;
                                } elseif ($kategori == 'Pendapatan - Beban Lain-lain') {
                                    $total_pbl += $row->nilai_lr_sak_ep ?? 0;
                                    $total_pbl_aud += $row->nilai_lr_sak_ep_audited ?? 0;
                                    $total_pbl_lalu += $nilai_lalu ?? 0;
                                } elseif ($kategori == 'Keuntungan (Kerugian) Luar Biasa') {
                                    $total_klb += $row->nilai_lr_sak_ep ?? 0;
                                    $total_klb_aud += $row->nilai_lr_sak_ep_audited ?? 0;
                                    $total_klb_lalu += $nilai_lalu ?? 0;
                                } elseif ($kategori == 'Beban Pajak Penghasilan') {
                                    $total_bpp += $row->nilai_lr_sak_ep ?? 0;
                                    $total_bpp_aud += $row->nilai_lr_sak_ep_audited ?? 0;
                                    $total_bpp_lalu += $nilai_lalu ?? 0;
                                } elseif ($kategori == '(Kerugian) Penghasilan Komprehensip Lain') {
                                    $total_pkl += $row->nilai_lr_sak_ep ?? 0;
                                    $total_pkl_aud += $row->nilai_lr_sak_ep_audited ?? 0;
                                    $total_pkl_lalu += $nilai_lalu ?? 0;
                                }
                            }

                            // **3. SETELAH LOOPING: Cetak subtotal untuk kategori terakhir dan summary akhir**

                            // Subtotal kategori terakhir
                            if ($kategori_sebelumnya == 'Pendapatan Usaha') {
                                echo "<tr class='font-weight-bold bg-light'>";
                                echo "<td class='text-center'>$no_urut</td><td>Jumlah Pendapatan Usaha</td>";
                                echo "<td class='text-right'>" . number_format($total_pu, 0, ',', '.') . "</td>";
                                echo "<td class='text-right'>" . number_format($total_pu_aud, 0, ',', '.') . "</td>";
                                echo "<td class='text-right'>" . number_format($total_pu_lalu, 0, ',', '.') . "</td></tr>";
                                $no_urut++;
                            } elseif ($kategori_sebelumnya == 'Beban Usaha') {
                                echo "<tr class='font-weight-bold bg-light'>";
                                echo "<td class='text-center'>$no_urut</td><td>Jumlah Beban Usaha</td>";
                                echo "<td class='text-right'>" . number_format($total_bu, 0, ',', '.') . "</td>";
                                echo "<td class='text-right'>" . number_format($total_bu_aud, 0, ',', '.') . "</td>";
                                echo "<td class='text-right'>" . number_format($total_bu_lalu, 0, ',', '.') . "</td></tr>";
                                $no_urut++;
                                $lr_kotor = $total_pu - $total_bu;
                                $lr_kotor_aud = $total_pu_aud - $total_bu_aud;
                                $lr_kotor_lalu = $total_pu_lalu - $total_bu_lalu;
                                echo "<tr class='font-weight-bold bg-warning'><td class='text-center'>$no_urut</td><td>LABA RUGI KOTOR</td><td class='text-right'>" . number_format($lr_kotor, 0, ',', '.') . "</td><td class='text-right'>" . number_format($lr_kotor_aud, 0, ',', '.') . "</td><td class='text-right'>" . number_format($lr_kotor_lalu, 0, ',', '.') . "</td></tr>";
                                $no_urut++;
                            } elseif ($kategori_sebelumnya == 'Beban Umum Dan Administrasi') {
                                echo "<tr class='font-weight-bold bg-light'><td class='text-center'>$no_urut</td><td>Beban Umum Dan Administrasi</td><td class='text-right'>" . number_format($total_bua, 0, ',', '.') . "</td><td class='text-right'>" . number_format($total_bua_aud, 0, ',', '.') . "</td><td class='text-right'>" . number_format($total_bua_lalu, 0, ',', '.') . "</td></tr>";
                                $no_urut++;
                                $lr_operasional = $lr_kotor - $total_bua;
                                $lr_operasional_aud = $lr_kotor_aud - $total_bua_aud;
                                $lr_operasional_lalu = $lr_kotor_lalu - $total_bua_lalu;
                                echo "<tr class='font-weight-bold bg-warning'><td class='text-center'>$no_urut</td><td>LABA RUGI OPERASIONAL</td><td class='text-right'>" . number_format($lr_operasional, 0, ',', '.') . "</td><td class='text-right'>" . number_format($lr_operasional_aud, 0, ',', '.') . "</td><td class='text-right'>" . number_format($lr_operasional_lalu, 0, ',', '.') . "</td></tr>";
                                $no_urut++;
                            } elseif ($kategori_sebelumnya == 'Pendapatan - Beban Lain-lain') {
                                echo "<tr class='font-weight-bold bg-light'><td class='text-center'>$no_urut</td><td>Jumlah Pendapatan - Beban Lain-lain</td><td class='text-right'>" . number_format($total_pbl, 0, ',', '.') . "</td><td class='text-right'>" . number_format($total_pbl_aud, 0, ',', '.') . "</td><td class='text-right'>" . number_format($total_pbl_lalu, 0, ',', '.') . "</td></tr>";
                                $no_urut++;
                                $lr_sebelum_pajak = $lr_operasional + $total_pbl;
                                $lr_sebelum_pajak_aud = $lr_operasional_aud + $total_pbl_aud;
                                $lr_sebelum_pajak_lalu = $lr_operasional_lalu + $total_pbl_lalu;
                                echo "<tr class='font-weight-bold bg-warning'><td class='text-center'>$no_urut</td><td>LABA RUGI BERSIH SEBELUM PAJAK</td><td class='text-right'>" . number_format($lr_sebelum_pajak, 0, ',', '.') . "</td><td class='text-right'>" . number_format($lr_sebelum_pajak_aud, 0, ',', '.') . "</td><td class='text-right'>" . number_format($lr_sebelum_pajak_lalu, 0, ',', '.') . "</td></tr>";
                                $no_urut++;
                            } elseif ($kategori_sebelumnya == 'Keuntungan (Kerugian) Luar Biasa') {
                                echo "<tr class='font-weight-bold bg-light'><td class='text-center'>$no_urut</td><td>Jumlah Keuntungan (Kerugian) Luar Biasa</td><td class='text-right'>" . number_format($total_klb, 0, ',', '.') . "</td><td class='text-right'>" . number_format($total_klb_aud, 0, ',', '.') . "</td><td class='text-right'>" . number_format($total_klb_lalu, 0, ',', '.') . "</td></tr>";
                                $no_urut++;
                                $laba_sebelum_pajak = $lr_sebelum_pajak - $total_klb;
                                $laba_sebelum_pajak_aud = $lr_sebelum_pajak_aud - $total_klb_aud;
                                $laba_sebelum_pajak_lalu = $lr_sebelum_pajak_lalu - $total_klb_lalu;
                                echo "<tr class='font-weight-bold bg-warning'><td class='text-center'>$no_urut</td><td>LABA SEBELUM PAJAK</td>";
                                echo "<th class='text-right'><a href='" . base_url('lap_keuangan/beban_pajak/input_lrbsp/' . $tahun_lap . '/' . $laba_sebelum_pajak) . "' onclick='return confirm(\"Apakah Anda yakin ingin menyimpan data ini ke Neraca?\");' style='text-decoration: none; color: inherit;'>" . number_format($laba_sebelum_pajak, 0, ',', '.') . "</a></th>";
                                echo "<td class='text-right'>" . number_format($laba_sebelum_pajak_aud, 0, ',', '.') . "</td><td class='text-right'>" . number_format($laba_sebelum_pajak_lalu, 0, ',', '.') . "</td></tr>";
                                $no_urut++;
                            } elseif ($kategori_sebelumnya == 'Beban Pajak Penghasilan' || $kategori_sebelumnya == '(Kerugian) Penghasilan Komprehensip Lain') {
                                // Hitung laba_sebelum_pajak dulu jika belum
                                if (!isset($laba_sebelum_pajak)) $laba_sebelum_pajak = $lr_sebelum_pajak - $total_klb;
                                if (!isset($laba_sebelum_pajak_aud)) $laba_sebelum_pajak_aud = $lr_sebelum_pajak_aud - $total_klb_aud;
                                if (!isset($laba_sebelum_pajak_lalu)) $laba_sebelum_pajak_lalu = $lr_sebelum_pajak_lalu - $total_klb_lalu;

                                // Cetak KLB dan Laba Sebelum Pajak
                                echo "<tr class='font-weight-bold bg-light'><td class='text-center'>$no_urut</td><td>Jumlah Keuntungan (Kerugian) Luar Biasa</td><td class='text-right'>" . number_format($total_klb, 0, ',', '.') . "</td><td class='text-right'>" . number_format($total_klb_aud, 0, ',', '.') . "</td><td class='text-right'>" . number_format($total_klb_lalu, 0, ',', '.') . "</td></tr>";
                                $no_urut++;
                                echo "<tr class='font-weight-bold bg-warning'><td class='text-center'>$no_urut</td><td>LABA SEBELUM PAJAK</td>";
                                echo "<th class='text-right'><a href='" . base_url('lap_keuangan/beban_pajak/input_lrbsp/' . $tahun_lap . '/' . $laba_sebelum_pajak) . "' onclick='return confirm(\"Apakah Anda yakin ingin menyimpan data ini ke Neraca?\");' style='text-decoration: none; color: inherit;'>" . number_format($laba_sebelum_pajak, 0, ',', '.') . "</a></th>";
                                echo "<td class='text-right'>" . number_format($laba_sebelum_pajak_aud, 0, ',', '.') . "</td><td class='text-right'>" . number_format($laba_sebelum_pajak_lalu, 0, ',', '.') . "</td></tr>";
                                $no_urut++;

                                // Cetak BPP dan Laba Setelah Pajak
                                echo "<tr class='font-weight-bold bg-light'><td class='text-center'>$no_urut</td><td>Jumlah Beban Pajak Penghasilan</td><td class='text-right'>" . number_format($total_bpp, 0, ',', '.') . "</td><td class='text-right'>" . number_format($total_bpp_aud, 0, ',', '.') . "</td><td class='text-right'>" . number_format($total_bpp_lalu, 0, ',', '.') . "</td></tr>";
                                $no_urut++;
                                $laba_setelah_pajak = $laba_sebelum_pajak - $total_bpp;
                                $laba_setelah_pajak_aud = $laba_sebelum_pajak_aud - $total_bpp_aud;
                                $laba_setelah_pajak_lalu = $laba_sebelum_pajak_lalu - $total_bpp_lalu;
                                echo "<tr class='font-weight-bold bg-warning'><td class='text-center'>$no_urut</td><td>LABA RUGI BERSIH SETELAH PAJAK</td><td class='text-right'>" . number_format($laba_setelah_pajak, 0, ',', '.') . "</td><td class='text-right'>" . number_format($laba_setelah_pajak_aud, 0, ',', '.') . "</td><td class='text-right'>" . number_format($laba_setelah_pajak_lalu, 0, ',', '.') . "</td></tr>";
                                $no_urut++;
                            }

                            // **Cetak PKL & Jumlah Penghasilan Komprehensif (jika kategorinya ada)**
                            if ($kategori_sebelumnya == '(Kerugian) Penghasilan Komprehensip Lain') {
                                echo "<tr class='font-weight-bold bg-light'><td class='text-center'>$no_urut</td><td>Jumlah (Kerugian) Penghasilan Komprehensip Lain</td><td class='text-right'>" . number_format($total_pkl, 0, ',', '.') . "</td><td class='text-right'>" . number_format($total_pkl_aud, 0, ',', '.') . "</td><td class='text-right'>" . number_format($total_pkl_lalu, 0, ',', '.') . "</td></tr>";
                                $no_urut++;

                                $penghasilan_komprehensif = $laba_setelah_pajak - $total_pkl;
                                $penghasilan_komprehensif_aud = $laba_setelah_pajak_aud - $total_pkl_aud;
                                $penghasilan_komprehensif_lalu = $laba_setelah_pajak_lalu - $total_pkl_lalu;
                                echo "<tr class='font-weight-bold bg-warning'><td class='text-center'>$no_urut</td><td>JUMLAH PENGHASILAN KOMPREHENSIF TAHUN BERJALAN</td>";
                                echo "<th class='text-right'><a href='" . base_url('lap_keuangan/lr_sak_ep/input_pktb/' . $tahun_lap . '/' . $penghasilan_komprehensif) . "' onclick='return confirm(\"Apakah Anda yakin ingin menyimpan data ini ke Neraca?\");' style='text-decoration: none; color: inherit;'>" . number_format($penghasilan_komprehensif, 0, ',', '.') . "</a></th>";
                                echo "<td class='text-right'>" . number_format($penghasilan_komprehensif_aud, 0, ',', '.') . "</td><td class='text-right'>" . number_format($penghasilan_komprehensif_lalu, 0, ',', '.') . "</td></tr>";
                                $no_urut++;
                            } else {
                                // Jika PKL tidak ada di data, tetap tampilkan baris PKL dan jumlah komprehensif
                                echo "<tr class='font-weight-bold bg-light'><td class='text-center'>" . ($no_urut++) . "</td><td>Jumlah (Kerugian) Penghasilan Komprehensip Lain</td><td class='text-right'>0</td><td class='text-right'>0</td><td class='text-right'>0</td></tr>";
                                // Hitung ulang laba_setelah_pajak jika belum
                                if (!isset($laba_setelah_pajak)) $laba_setelah_pajak = $laba_sebelum_pajak - $total_bpp;
                                if (!isset($laba_setelah_pajak_aud)) $laba_setelah_pajak_aud = $laba_sebelum_pajak_aud - $total_bpp_aud;
                                if (!isset($laba_setelah_pajak_lalu)) $laba_setelah_pajak_lalu = $laba_sebelum_pajak_lalu - $total_bpp_lalu;
                                echo "<tr class='font-weight-bold bg-warning'><td class='text-center'>" . ($no_urut++) . "</td><td>JUMLAH PENGHASILAN KOMPREHENSIF TAHUN BERJALAN</td>";
                                echo "<th class='text-right'><a href='" . base_url('lap_keuangan/lr_sak_ep/input_pktb/' . $tahun_lap . '/' . $laba_setelah_pajak) . "' onclick='return confirm(\"Apakah Anda yakin ingin menyimpan data ini ke Neraca?\");' style='text-decoration: none; color: inherit;'>" . number_format($laba_setelah_pajak, 0, ',', '.') . "</a></th>";
                                echo "<td class='text-right'>" . number_format($laba_setelah_pajak_aud, 0, ',', '.') . "</td><td class='text-right'>" . number_format($laba_setelah_pajak_lalu, 0, ',', '.') . "</td></tr>";
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