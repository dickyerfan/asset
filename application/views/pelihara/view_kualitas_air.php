<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('pelihara/kualitas_air') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('pelihara/kualitas_air'); ?>" method="get">
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
                    <?php if ($this->session->userdata('bagian') == 'Pemeliharaan') { ?>
                        <div class="navbar-nav ms-2">
                            <a href="<?= base_url('pelihara/kualitas_air/input_kualitas_air') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Kualitas Air</button></a>
                        </div>
                    <?php } ?>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('pelihara/kualitas_air/uji_syarat') ?>"><button class="float-end neumorphic-button">Uji Yang Memenuhi Syarat</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('pelihara/kualitas_air/jumlah_sample_uji') ?>"><button class="float-end neumorphic-button">Jumlah sample yang di uji</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('pelihara/kualitas_air/cetak_kualitas_air') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title); ?> <?= $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th rowspan="2" class="align-middle">No</th>
                                <th rowspan="2" class="align-middle">Parameter Uji</th>
                                <th colspan="2">Info Jumlah Sample Minimal</th>
                                <th rowspan="2" class="align-middle">Terambil</th>
                                <th colspan="2">Jumlah Sample Memenuhi Syarat Air Minum</th>
                                <th rowspan="2" class="align-middle">Tempat Uji Kualitas Air</th>
                                <th rowspan="2" class="align-middle">Action</th>
                            </tr>
                            <tr class="text-center">
                                <th>Internal</th>
                                <th>Eksternal</th>
                                <th>Ya</th>
                                <th>Tidak</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $no = 1;
                            $current_param = '';

                            $total_sample_int = 0;
                            $total_sample_eks = 0;
                            $total_terambil = 0;
                            $total_sample_oke_ya = 0;
                            $total_sample_oke_tidak = 0;

                            foreach ($kualitas_air as $key => $row) {
                                if ($row->parameter !== $current_param) {
                                    // Tampilkan parameter hanya sekali di awal
                                    echo "<tr>";
                                    echo "<td class='text-center'>{$no}</td>";
                                    echo "<td class='text-left'><b>{$row->parameter}</b></td>";
                                    echo "<td colspan='7'></td>"; // Kosongkan kolom untuk sejajar
                                    echo "</tr>";
                                    $no++;

                                    // Reset total untuk setiap parameter baru
                                    $total_sample_int = 0;
                                    $total_sample_eks = 0;
                                    $total_terambil = 0;
                                    $total_sample_oke_ya = 0;
                                    $total_sample_oke_tidak = 0;
                                }

                                // Akumulasi total
                                $total_sample_int += $row->jumlah_sample_int;
                                $total_sample_eks += $row->jumlah_sample_eks;
                                $total_terambil += $row->jumlah_terambil;
                                $total_sample_oke_ya += $row->jumlah_sample_oke_ya;
                                $total_sample_oke_tidak += $row->jumlah_sample_oke_tidak;

                                // Tampilkan data per bulan
                                echo "<tr>";
                                echo "<td></td>"; // Kosongkan nomor agar sejajar
                                echo "<td class='text-left'>{$row->bulan}</td>";
                                echo "<td class='text-center'>{$row->jumlah_sample_int}</td>";
                                echo "<td class='text-center'>{$row->jumlah_sample_eks}</td>";
                                echo "<td class='text-center'>{$row->jumlah_terambil}</td>";
                                echo "<td class='text-center'>{$row->jumlah_sample_oke_ya}</td>";
                                echo "<td class='text-center'>{$row->jumlah_sample_oke_tidak}</td>";
                                echo "<td class='text-center'>{$row->tempat_uji}</td>";

                                // Cek apakah user memiliki hak akses edit
                                if ($this->session->userdata('bagian') == 'Pemeliharaan' || $this->session->userdata('bagian') == 'Administrator') {
                                    echo "<td class='text-center'>
                                            <a href='" . base_url('pelihara/kualitas_air/edit_ka/' . $row->id_ek_ka) . "' class='btn btn-warning btn-sm'>
                                            <i class='fa fa-edit'></i> Edit
                                            </a>
                                          </td>";
                                } else {
                                    echo "<td class='text-center'>-</td>"; // Kosongkan kolom jika tidak memiliki akses
                                }

                                echo "</tr>";


                                // Cek apakah data berikutnya berbeda parameter
                                $next = $kualitas_air[$key + 1] ?? null;
                                if (!$next || $next->parameter !== $row->parameter) {
                                    // Tampilkan total di akhir setiap parameter
                                    echo "<tr>
                                            <td></td>
                                            <td class='text-left'><b>Jumlah</b></td>
                                            <td class='text-center'><b>{$total_sample_int}</b></td>
                                            <td class='text-center'><b>{$total_sample_eks}</b></td>
                                            <td class='text-center'><b>{$total_terambil}</b></td>
                                            <td class='text-center'><b>{$total_sample_oke_ya}</b></td>
                                            <td class='text-center'><b>{$total_sample_oke_tidak}</b></td>
                                            <td></td>
                                            <td></td>
                                            </tr>";
                                }
                                // Update parameter saat ini
                                $current_param = $row->parameter;
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