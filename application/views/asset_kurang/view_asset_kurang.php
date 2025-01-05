<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tanggal" action="<?= base_url('asset/asset_kurang'); ?>" method="get">
                        <div style="display: flex; align-items: center;">
                            <input type="submit" value="Pilih Bulan" class="neumorphic-button">
                            <input type="date" id="tanggal" name="tanggal" class="form-control" style="margin-left: 10px;">
                        </div>
                    </form>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('asset/asset_kurang_tahun'); ?>"><button class=" neumorphic-button float-right"> Per Tahun</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('asset/asset_kurang_akm'); ?>"><button class=" neumorphic-button float-right"> Akm. Penyusutan</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('asset/asset_semua'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
                    </div>
                </nav>
            </div>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">

                        <?php
                        if (empty($bulan_lap)) {
                            $bulan_lap = date('m');
                            $tahun_lap = date('Y');
                        }

                        $bulan = [
                            '01' => 'Januari',
                            '02' => 'Februari',
                            '03' => 'Maret',
                            '04' => 'April',
                            '05' => 'Mei',
                            '06' => 'Juni',
                            '07' => 'Juli',
                            '08' => 'Agustus',
                            '09' => 'September',
                            '10' => 'Oktober',
                            '11' => 'November',
                            '12' => 'Desember',
                        ];

                        $bulan_lap = strtr($bulan_lap, $bulan);

                        ?>
                        <h5><?= strtoupper($title) . ' TAHUN ' . $tahun_lap; ?></h5>
                        <h5>Bulan : <?= $bulan_lap; ?></h5>
                    </div>
                </div>
                <!-- <div>
                    <div class="row justify-content-center">
                        <div class="col-md-6 text-center">
                            <h3 class="neumorphic-button fs-3" style="margin: 100px 100px;">Menu Belum Tersedia</h3>
                        </div>
                    </div>
                </div> -->
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
                            foreach ($asset as $row) :
                                $total_rupiah = $row->total_rupiah * -1;
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

                                    <td><?= $row->nama_bagian; ?></td>
                                    <td class="text-center"><?= date('d-m-Y', strtotime($row->tanggal)); ?></td>
                                    <td><?= $row->no_bukti_gd; ?></td>
                                    <td><?= $row->no_bukti_vch; ?></td>
                                    <td class="text-right"><?= number_format($row->rupiah * -1, 0, ',', '.'); ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url(); ?>asset/edit/<?= $row->id_asset; ?>"><span class="badge badge-primary"><i class="fas fa-fw fa-edit"></i></span></a>
                                        <a href="<?= base_url(); ?>asset/hapus/<?= $row->id_asset; ?>" class="badge badge-danger"><i class="fas fa-fw fa-trash"></i></a>
                                    </td>
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
                                <th class="text-right"><?= number_format($total_rupiah * -1, 0, ',', '.'); ?></th>
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