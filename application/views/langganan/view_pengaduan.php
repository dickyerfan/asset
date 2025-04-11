<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('langganan/data_pengaduan') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('langganan/data_pengaduan'); ?>" method="get">
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
                            <a href="<?= base_url('langganan/data_pengaduan/input_aduan') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Pengaduan</button></a>
                        </div>
                    <?php } ?>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('langganan/data_pengaduan/cetak_aduan') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>

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
                                <th>BULAN</th>
                                <th>Jenis Pengaduan</th>
                                <th>Jumlah Pengaduan</th>
                                <th>Jumlah Pengaduan Terselesaikan</th>
                                <th>Jumlah Pengaduan Belum Terselesaikan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_aduan = 0;
                            $total_aduan_ya = 0;
                            $total_aduan_tidak = 0;
                            $bulan_sebelumnya = null;
                            $total_per_jenis = [];

                            foreach ($pengaduan as $data) :
                                $total_aduan += $data->jumlah_aduan ?? 0;
                                $total_aduan_ya += $data->jumlah_aduan_ya ?? 0;
                                $total_aduan_tidak += $data->jumlah_aduan_tidak ?? 0;

                                // Hitung total per jenis aduan
                                $jenis = $data->jenis_aduan;
                                if (!isset($total_per_jenis[$jenis])) {
                                    $total_per_jenis[$jenis] = [
                                        'jumlah' => 0,
                                        'ya' => 0,
                                        'tidak' => 0
                                    ];
                                }

                                $total_per_jenis[$jenis]['jumlah'] += $data->jumlah_aduan ?? 0;
                                $total_per_jenis[$jenis]['ya'] += $data->jumlah_aduan_ya ?? 0;
                                $total_per_jenis[$jenis]['tidak'] += $data->jumlah_aduan_tidak ?? 0;
                            ?>
                                <tr class="text-center">
                                    <td class="text-left">
                                        <?= ($data->bulan != $bulan_sebelumnya) ? $data->bulan : ""; ?>
                                    </td>
                                    <td class="text-left"><?= $data->jenis_aduan; ?></td>
                                    <td><?= $data->jumlah_aduan ?? 0; ?></td>
                                    <td><?= $data->jumlah_aduan_ya ?? 0; ?></td>
                                    <td><?= $data->jumlah_aduan_tidak ?? 0; ?></td>
                                    <td>
                                        <?php if ($this->session->userdata('bagian') == 'Langgan' or $this->session->userdata('bagian') == 'Administrator') { ?>
                                            <div class="text-center">
                                                <a href="<?= base_url('langganan/data_pengaduan/edit_aduan/' . $data->id_ek_aduan) ?>"><i class="fas fa-edit"></i></a>
                                            </div>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php
                                $bulan_sebelumnya = $data->bulan;
                            endforeach;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="text-center font-weight-bold">
                                <td colspan="2">TOTAL</td>
                                <td><?= $total_aduan; ?></td>
                                <td><?= $total_aduan_ya; ?></td>
                                <td><?= $total_aduan_tidak; ?></td>
                                <td></td>
                            </tr>
                        </tfoot>
                        <tfoot>
                            <?php foreach ($total_per_jenis as $jenis => $total) : ?>
                                <tr class="text-center font-weight-bold">
                                    <td class="text-left">Jumlah</td>
                                    <td class="text-left"><?= $jenis; ?></td>
                                    <td><?= $total['jumlah']; ?></td>
                                    <td><?= $total['ya']; ?></td>
                                    <td><?= $total['tidak']; ?></td>
                                    <td></td>
                                </tr>
                            <?php endforeach; ?>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>