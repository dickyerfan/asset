<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('dashboard_publik') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('dashboard_publik'); ?>" method="get">
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
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('dashboard_publik/cetak_rekap_penyusutan') ?>"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak Dokumen</button></a>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title) . ' TAHUN ' . $tahun_lap; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>Nama Asset</th>
                                <th>Harga Perolehan Thn Lalu</th>
                                <th>Penambahan</th>
                                <th>Pengurangan</th>
                                <th>Harga Perolehan Thn Ini</th>
                                <th>Akm Thn Lalu</th>
                                <th>Nilai Buku Thn Lalu</th>
                                <th>Penyusutan</th>
                                <th>Akm Thn Ini</th>
                                <th>Nilai Buku Thn Ini</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center bg-light">
                                <th class="text-left">Tanah & Penyempurnaan Tanah</th>
                                <th class="text-right"><?= number_format($total_tanah['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tanah['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tanah['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tanah['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tanah['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tanah['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tanah['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tanah['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_tanah['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th class="text-left">Bangunan/Gedung</th>
                                <th class="text-right"><?= number_format($total_bangunan['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bangunan['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bangunan['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bangunan['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bangunan['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bangunan['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bangunan['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bangunan['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_bangunan['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th class="text-left">Instalasi Sumber</th>
                                <th class="text-right"><?= number_format($total_sumber['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_sumber['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_sumber['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_sumber['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_sumber['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_sumber['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_sumber['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_sumber['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_sumber['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th class="text-left">Instalasi Pompa</th>
                                <th class="text-right"><?= number_format($total_pompa['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_pompa['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th class="text-left">Instalasi Pengolahan Air</th>
                                <th class="text-right"><?= number_format($total_olah_air['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_olah_air['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_olah_air['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_olah_air['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_olah_air['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_olah_air['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_olah_air['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_olah_air['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_olah_air['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th class="text-left">Instalasi Trans & Distribusi</th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_trans_dist['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th class="text-left">Peralatan/Perlengkapan</th>
                                <th class="text-right"><?= number_format($total_peralatan['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_peralatan['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_peralatan['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_peralatan['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_peralatan['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_peralatan['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_peralatan['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_peralatan['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_peralatan['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th class="text-left">Kendaraan/Alat Angkut</th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_kendaraan['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th class="text-left">Inventaris/Perabotan Kantor</th>
                                <th class="text-right"><?= number_format($total_inventaris['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_inventaris['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>
                            <tr class="text-center bg-light">
                                <th class="text-left">Total</th>
                                <th class="text-right"><?= number_format($total_semua['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_semua['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_semua['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_semua['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_semua['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_semua['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_semua['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_semua['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($total_semua['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                            </tr>

                        </tbody>
                    </table> -->
                </div>
            </div>
        </div>
    </div>
</section>
</div>