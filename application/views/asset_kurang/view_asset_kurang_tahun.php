<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('asset/asset_kurang_tahun') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('asset/asset_kurang_tahun'); ?>" method="get">
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
                    <div class="navbar-nav ms-2">
                        <form id="form_no_per" action="<?= base_url('asset/asset_kurang_tahun'); ?>" method="get">
                            <div style="display: flex; align-items: center;">
                                <select name="no_per" id="no_per" class="form-control select2" style="width:200px;">
                                    <option value="">Pilih No Perkiraan</option>
                                    <?php foreach ($no_per as $row) :  ?>
                                        <option value="<?= $row->id; ?>"><?= $row->name; ?></option>
                                    <?php endforeach;  ?>
                                </select>
                                <input type="hidden" name="tahun" value="<?= $this->input->get('tahun') ?: date('Y') ?>">
                            </div>
                        </form>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('asset/asset_kurang_tahun_cetak'); ?>" target="_blank"><button class=" neumorphic-button float-right"><i class="fas fa-print"></i> Cetak</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('asset/asset_semua'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
                    </div>
                </nav>
            </div>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title) . ' TAHUN ' . $tahun_lap; ?></h5>
                        <?php if (!empty($this->input->get('no_per'))) : ?>
                            <h5><?= isset($no_per_descriptions[$no_perkiraan]) ? $no_per_descriptions[$no_perkiraan] : 'Tidak Ditemukan'; ?></h5>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>No Per</th>
                                <th>Nama Asset</th>
                                <th>Lokasi</th>
                                <th>Tanggal</th>
                                <th>No Bkt Gdg</th>
                                <th>No Bkt Vch</th>
                                <th>Rupiah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total_rupiah = 0;
                            foreach ($asset as $row) :
                                $total_rupiah = $row->total_rupiah;
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><?= $row->kode; ?></td>
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
                                    <td class="text-center"><?= date('d-m-Y', strtotime($row->tanggal)); ?></td>
                                    <td><?= $row->no_bukti_gd; ?></td>
                                    <td><?= $row->no_bukti_vch; ?></td>
                                    <td class="text-right"><?= number_format($row->rupiah * -1, 0, ',', '.'); ?></td>
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
                                <th>Jumlah</th>
                                <th class="text-right"><?= number_format($total_rupiah * -1, 0, ',', '.'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>