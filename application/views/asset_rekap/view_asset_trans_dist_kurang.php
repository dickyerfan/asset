<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('asset_rekap/trans_dist') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('asset_rekap/trans_dist'); ?>" method="get">
                        <div style="display: flex; align-items: center;">
                            <!-- <input type="submit" value="Pilih Tahun" class="neumorphic-button"> -->
                            <!-- <input type="date" id="tanggal" name="tanggal" class="form-control" style="margin-left: 10px;"> -->
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
                        <a href="<?= base_url('asset_rekap/sr_baru') ?>"><button class="float-end neumorphic-button"><i class="fas fa-file"></i> SR Baru</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('asset_rekap/ganti_wm') ?>"><button class="float-end neumorphic-button"><i class="fas fa-file"></i> Ganti WM</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('asset_rekap/lainnya') ?>"><button class="float-end neumorphic-button"><i class="fas fa-file"></i> Lain-lain</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('asset_rekap/trans_dist') ?>"><button class="float-end neumorphic-button"><i class="fas fa-file"></i> Total</button></a>
                    </div> -->
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('asset_rekap/trans_dist') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Rekap Penambahan</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('asset_rekap/cetak_trans_dist_kurang') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak Asset</button></a>
                    </div>
                </nav>
            </div>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title) . ' ' . $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>No Per</th>
                                <th>Nama Per</th>
                                <th>Nama Asset</th>
                                <th>Lokasi</th>
                                <th>Tanggal</th>
                                <th>No Bkt Gdg</th>
                                <th>No Bkt Vch</th>
                                <th>Rupiah</th>
                                <th>Ket</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total_rupiah = 0;
                            foreach ($trans_dist as $row) :
                                $total_rupiah = $row->total_rupiah;
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><?= $row->kode; ?></td>
                                    <td>
                                        <?php
                                        $nama_perkiraan = $row->name;
                                        if (strlen($nama_perkiraan) > 35) {
                                            $nama_perkiraan = substr($nama_perkiraan, 0, 35) . '...';
                                        }
                                        ?>
                                        <?= $nama_perkiraan; ?>
                                    </td>
                                    <td>
                                        <?php
                                        // Memotong nama_asset jika lebih dari 60 karakter
                                        $nama_asset = $row->nama_asset;
                                        if (strlen($nama_asset) > 55) {
                                            $nama_asset = substr($nama_asset, 0, 55) . '...'; // Potong dan tambahkan "..."
                                        }
                                        ?>
                                        <?php if ($row->kode_sr == 1) : ?>
                                            <?= $nama_asset; ?> <?= $row->jumlah; ?> di <?= $row->nama_bagian; ?>
                                        <?php else : ?>
                                            <?= $nama_asset; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($row->id_bagian == 2) : ?>
                                            <?= 'Kantor Pusat'; ?>
                                        <?php else : ?>
                                            <?= $row->nama_bagian; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= $row->tanggal; ?></td>
                                    <td><?= $row->no_bukti_gd; ?></td>
                                    <td><?= $row->no_bukti_vch; ?></td>
                                    <td class="text-right"><?= number_format($row->rupiah, 0, ',', '.'); ?></td>
                                    <td></td>
                                    <!-- <td class="text-center">
                                        <a href="<?= base_url(); ?>asset/edit/<?= $row->id_asset; ?>"><span class="badge badge-primary"><i class="fas fa-fw fa-edit"></i></span></a>
                                        <a href="<?= base_url(); ?>asset/hapus/<?= $row->id_asset; ?>" class="badge badge-danger"><i class="fas fa-fw fa-trash"></i></a>
                                    </td> -->
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="text-center bg-light">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Jumlah</th>
                                <th class="text-right"><?= number_format($total_rupiah, 0, ',', '.'); ?></th>
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