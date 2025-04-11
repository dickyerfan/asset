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
                            $total_jiwa_kk = 0;
                            $total_wil_layan = 0;
                            $total_kk_layan = 0;
                            $total_jiwa_kk_layan = 0;
                            foreach ($data_penduduk as $data) :
                                $total_penduduk += (int) $data->jumlah_penduduk;
                                $total_kk += (int) $data->jumlah_kk;
                                $total_jiwa_kk += (float) $data->jiwa_kk;
                                $total_wil_layan += (int) $data->jumlah_wil_layan;
                                $total_kk_layan += (int) $data->jumlah_kk_layan;
                                $total_jiwa_kk_layan += (float) $data->jiwa_kk_layan;
                            ?>
                                <tr class="text-center">
                                    <td><?= $no++; ?></td>
                                    <td class="text-left"><?= $data->nama_kec; ?></td>
                                    <td><?= $data->wil_layan; ?></td>
                                    <td><?= $data->wil_adm; ?></td>
                                    <td><?= $data->jumlah_penduduk ?? 0; ?></td>
                                    <td><?= $data->jumlah_kk ?? 0; ?></td>
                                    <td><?= $data->jiwa_kk ?? 0; ?></td>
                                    <td><?= $data->jumlah_wil_layan ?? 0; ?></td>
                                    <td><?= $data->jumlah_kk_layan ?? 0; ?></td>
                                    <td><?= $data->jiwa_kk_layan ?? 0; ?></td>
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
                                <td colspan="4">Kabupaten Bondowoso</td>
                                <td><?= number_format($total_penduduk, '0', ',', '.'); ?></td>
                                <td><?= number_format($total_kk, '0', ',', '.'); ?></td>
                                <td></td>
                                <td><?= number_format($total_wil_layan, '0', ',', '.'); ?></td>
                                <td><?= number_format($total_kk_layan, '0', ',', '.'); ?></td>
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