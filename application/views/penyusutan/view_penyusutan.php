<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tanggal" action="<?= base_url('penyusutan'); ?>" method="get">
                        <div style="display: flex; align-items: center;">
                            <input type="submit" value="Pilih Tahun" class="neumorphic-button">
                            <input type="date" id="tanggal" name="tanggal" class="form-control" style="margin-left: 10px;">
                        </div>
                    </form>
                    <!-- <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('penyusutan/upload') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input pengurangan Asset</button></a>
                    </div> -->
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

                        // $bulan = [
                        //     '01' => 'Januari',
                        //     '02' => 'Februari',
                        //     '03' => 'Maret',
                        //     '04' => 'April',
                        //     '05' => 'Mei',
                        //     '06' => 'Juni',
                        //     '07' => 'Juli',
                        //     '08' => 'Agustus',
                        //     '09' => 'September',
                        //     '10' => 'Oktober',
                        //     '11' => 'November',
                        //     '12' => 'Desember',
                        // ];

                        // $bulan_lap = strtr($bulan_lap, $bulan);

                        ?>
                        <h5><?= strtoupper($title) . ' ' . $tahun_lap; ?></h5>
                        <!-- <h5>Bulan : <?= $bulan_lap; ?></h5> -->
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
                                <th>Nama Asset</th>
                                <th>Tgl perolehan</th>
                                <th>Harga Perolehan</th>
                                <th>Penambahan</th>
                                <th>Pengurangan</th>
                                <th>Umur</th>
                                <th>Prsen</th>
                                <th>Akm Thn Lalu</th>
                                <th>Penambahan</th>
                                <th>Pengurangan</th>
                                <th>Akm Thn Ini</th>
                                <th>Nilai Buku</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total_rupiah = 0;
                            foreach ($susut as $row) :
                                // $total_rupiah = $row->total_rupiah;
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
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
                                    <td class="text-center"><?= $row->tanggal; ?></td>
                                    <td class="text-right"><?= number_format($row->rupiah, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->penambahan, 0, ',', '.'); ?></td>
                                    <td class="text-center"><?= $row->pengurangan; ?></td>
                                    <td class="text-center"><?= $row->umur; ?></td>
                                    <td class="text-center"><?= $row->persen_susut; ?></td>
                                    <td class="text-center"><?= number_format($row->akm_thn_lalu, 0, ',', '.'); ?></td>
                                    <!-- <td class="text-center"></td> -->
                                    <td class="text-center"><?= number_format($row->penambahan_penyusutan, 0, ',', '.'); ?></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"><?= number_format($row->akm_thn_ini, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->nilai_buku, 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <!-- <tfoot>
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
                        </tfoot> -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>