<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('langganan/cak_layanan/data_penduduk') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('langganan/cak_layanan/data_penduduk'); ?>" method="get">
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
                    <?php if ($this->session->userdata('bagian') == 'Langgan') { ?>
                        <div class="navbar-nav ms-2">
                            <a href="<?= base_url('langganan/cak_layanan/input_data_penduduk') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Data Penduduk</button></a>
                        </div>
                    <?php } ?>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('langganan/cak_layanan') ?>"><button class="float-end neumorphic-button"><i class="fas fa-map"></i> Cakupan Layanan</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('langganan/cak_layanan/data_pelanggan') ?>"><button class="float-end neumorphic-button"><i class="fas fa-user"></i> Data Pelanggan</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('langganan/cak_layanan/cetak_data_penduduk') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>
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
                                <th>No</th>
                                <th>Kecamatan</th>
                                <th>Wil layan</th>
                                <th>Wil. Adm</th>
                                <th>Jml Penduduk</th>
                                <th>Jml KK</th>
                                <th>Jiwa per KK</th>
                                <th>Wil layan</th>
                                <th>KK di layanan</th>
                                <th>Jiwa per KK</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total_penduduk = 0;
                            $total_kk = 0;
                            // $total_jiwa_kk = 0;
                            $total_wil_layan = 0;
                            $total_kk_layan = 0;
                            // $total_jiwa_kk_layan = 0;
                            $jumlah_wil_layan_ya = 0;
                            $total_wil_layan_semua = 0;
                            foreach ($data_penduduk as $data) :
                                $total_penduduk += (int) $data->jumlah_penduduk;
                                $total_kk += (int) $data->jumlah_kk;
                                // $total_jiwa_kk += (float) $data->jiwa_kk;
                                $total_wil_layan += (int) $data->jumlah_wil_layan;
                                $total_kk_layan += (int) $data->jumlah_kk_layan;
                                // $total_jiwa_kk_layan += (float) $data->jiwa_kk_layan;
                                if (strtoupper($data->wil_layan) === 'YA') {
                                    $jumlah_wil_layan_ya++;
                                }
                                if (!empty($data->wil_layan)) {
                                    $total_wil_layan_semua++;
                                }
                                $jumlah_penduduk = intval($data->jumlah_penduduk);
                                $jumlah_kk = intval($data->jumlah_kk);
                                $jumlah_wil_layan = intval($data->jumlah_wil_layan);
                                $jumlah_kk_layan = intval($data->jumlah_kk_layan);

                                $jiwa_kk = ($jumlah_kk > 0) ? ($jumlah_penduduk / $jumlah_kk) : 0;
                                $jiwa_kk_layan = ($jumlah_kk_layan > 0) ? ($jumlah_wil_layan / $jumlah_kk_layan) : 0;


                            ?>
                                <tr class="text-center">
                                    <td><?= $no++; ?></td>
                                    <td class="text-left"><?= $data->nama_kec; ?></td>
                                    <td><?= $data->wil_layan; ?></td>
                                    <td><?= $data->wil_adm; ?></td>
                                    <td><?= $data->jumlah_penduduk ?? 0; ?></td>
                                    <td><?= $data->jumlah_kk ?? 0; ?></td>
                                    <td><?= number_format($jiwa_kk, 2, ',', '.'); ?></td>
                                    <td><?= $data->jumlah_wil_layan ?? 0; ?></td>
                                    <td><?= $data->jumlah_kk_layan ?? 0; ?></td>
                                    <td><?= number_format($jiwa_kk_layan, 2, ',', '.');  ?></td>
                                    <td>
                                        <?php if ($this->session->userdata('bagian') == 'Langgan' or $this->session->userdata('bagian') == 'Administrator') { ?>
                                            <div class="text-center">
                                                <a href="<?= base_url('langganan/cak_layanan/edit_data_penduduk/' . $data->id_data_penduduk) ?>"><i class="fas fa-edit"></i></a>
                                            </div>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="text-center">s
                                <td colspan="2">Kabupaten Bondowoso</td>
                                <td>
                                    <!-- <a href="<?= base_url('langganan/cak_layanan/input_wil_layan_ya/' . $tahun_lap . '/' . $jumlah_wil_layan_ya) ?>" onclick="return confirm('Apakah Anda yakin ingin menyimpan data ini ke Database.?');" style="text-decoration: none; color: inherit;">
                                        <?= number_format($jumlah_wil_layan_ya, 0, ',', '.'); ?>
                                    </a> -->
                                    <!-- <?= number_format($jumlah_wil_layan_ya, 0, ',', '.'); ?> -->
                                </td>
                                <td>
                                    <!-- <?= number_format($total_wil_layan_semua, 0, ',', '.'); ?> -->
                                </td>
                                <td>
                                    <?= number_format($total_penduduk, 0, ',', '.'); ?>
                                </td>
                                <td>
                                    <?= number_format($total_kk, 0, ',', '.'); ?>
                                </td>
                                <td></td>
                                <td>
                                    <?= number_format($total_wil_layan, 0, ',', '.'); ?>
                                </td>
                                <td>
                                    <?= number_format($total_kk_layan, 0, ',', '.'); ?>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>