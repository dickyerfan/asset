<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form id="form_tahun" action="<?= base_url('penyusutan/pompa_alat'); ?>" method="get">
                        <div style="display: flex; align-items: center;">
                            <input type="submit" value="Pilih Tahun" class="neumorphic-button">
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
                        <form id="form_upk_bagian" action="<?= base_url('penyusutan/pompa_alat'); ?>" method="get">
                            <div style="display: flex; align-items: center;">
                                <select name="upk_bagian" id="upk_bagian" class="form-control select2" style="width:200px;">
                                    <option value="">Pilih Bagian/UPK</option>
                                    <?php foreach ($upk_bagian as $row) :  ?>
                                        <option value="<?= $row->id; ?>"><?= $row->name; ?></option>
                                    <?php endforeach;  ?>
                                </select>
                                <input type="hidden" name="tahun" value="<?= $this->input->get('tahun') ?>">
                            </div>
                        </form>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('penyusutan/pompa_alat') ?>" style="text-decoration: none;"><button class=" neumorphic-button"> Tahun ini</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('penyusutan/pompa_alat') ?>" style="text-decoration: none;"><button class=" neumorphic-button"> Total Peralatann</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('penyusutan/pompa') ?>" style="text-decoration: none;"><button class=" neumorphic-button"> Total Inst. Pompa</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('penyusutan/cetak_pompa_alat') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak Asset</button></a>
                    </div>
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
                        ?>
                        <?php if ($selected_upk) : ?>
                            <h5><?= strtoupper($title . ' INSTALASI POMPA : ' . $selected_upk->name)  . ' ' . $tahun_lap; ?></h5>
                        <?php else : ?>
                            <h5><?= strtoupper($title)  . ' INSTALASI POMPA : PERALATAN' . ' ' . $tahun_lap; ?></h5>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama Asset</th>
                                <th>Tgl perolehan</th>
                                <th>Umur</th>
                                <th>Prsen</th>
                                <th>Harga Perolehan Thn Lalu</th>
                                <th>Penambahan</th>
                                <th>Pengurangan</th>
                                <th>Harga Perolehan Thn Ini</th>
                                <th>Akm Thn Lalu</th>
                                <th>Nilai Buku Thn Lalu</th>
                                <th>Penyusutan</th>
                                <!-- <th>Pengurangan</th> -->
                                <th>Akm Thn Ini</th>
                                <th>Nilai Buku Thn Ini</th>
                                <th>Act</th>
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
                                    <td class="text-center">
                                        <?php if ($row->status == 2) {
                                            echo  date('d-m-Y', strtotime($row->tanggal_persediaan));
                                        } else {
                                            echo date('d-m-Y', strtotime($row->tanggal));
                                        }  ?>
                                    </td>
                                    <td class="text-center"><?= $row->umur; ?></td>
                                    <td class="text-center"><?= $row->persen_susut; ?></td>
                                    <td class="text-right"><?= number_format($row->nilai_buku, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->penambahan, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->pengurangan, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->rupiah, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->akm_thn_lalu, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->nilai_buku_lalu, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->penambahan_penyusutan, 0, ',', '.'); ?></td>
                                    <!-- <td class="text-right">-</td> -->
                                    <td class="text-right"><?= number_format($row->akm_thn_ini, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($row->nilai_buku_final, 0, ',', '.'); ?></td>
                                    <td class="text-center">
                                        <?php
                                        // Cek apakah level pengguna Admin
                                        if ($this->session->userdata('level') != 'Admin') :
                                        ?>
                                            <!-- Tombol untuk Non-Admin -->
                                            <a href="javascript:void(0);" onclick="showAlert('Anda tidak punya akses untuk update data.')">
                                                <span class="badge badge-secondary"><i class="fas fa-fw fa-ban"></i></span>
                                            </a>
                                        <?php else : ?>
                                            <?php if ($row->status_update == 1) : ?>
                                                <!-- Tombol jika status_update = 1 -->
                                                <a href="javascript:void(0);" onclick="showAlert('Asset sudah tidak bisa diupdate lagi.')">
                                                    <span class="badge badge-secondary"><i class="fas fa-fw fa-ban"></i></span>
                                                </a>
                                            <?php elseif ($row->umur == 0) : ?>
                                                <!-- Tombol jika status_update = 1 -->
                                                <a href="javascript:void(0);" onclick="showAlert('Asset sudah tidak bisa diupdate lagi.')">
                                                    <span class="badge badge-secondary"><i class="fas fa-fw fa-ban"></i></span>
                                                </a>
                                            <?php else : ?>
                                                <!-- Tombol jika status_update = 0 -->
                                                <!-- <a href="javascript:void(0);" onclick="confirmReset(<?= $row->id_asset; ?>, '<?= addslashes($row->nama_asset); ?>', '<?= date('d-m-Y', strtotime($row->tanggal)); ?>')">
                                                    <span class="badge badge-primary"><i class="fas fa-fw fa-edit"></i></span>
                                                </a> -->
                                                <a href="<?= base_url(); ?>penyusutan/edit_penyusutan/<?= $row->id_asset; ?>"><span class="badge badge-primary"><i class="fas fa-fw fa-edit"></i></span></a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if ($this->session->flashdata('error')) : ?>
                                            <script>
                                                Swal.fire({
                                                    title: 'Error',
                                                    text: '<?= $this->session->flashdata('error'); ?>',
                                                    icon: 'error',
                                                    confirmButtonText: 'OK'
                                                });
                                            </script>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="text-center bg-light">
                                <th colspan="5" class="text-right">Total</th>
                                <th class="text-right"><?= number_format($totals['total_nilai_buku'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_penambahan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_pengurangan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_rupiah'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_penyusutan'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                                <th class="text-right"><?= number_format($totals['total_nilai_buku_final'], 0, ',', '.'); ?></th>
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