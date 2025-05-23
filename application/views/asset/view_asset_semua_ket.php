<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <div class="navbar-nav">
                        <a href="<?= base_url('asset') ?>"><button class="float-end neumorphic-button"><i class="nav-icon fas fa-plus"></i> Penambahan Asset</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('asset/asset_kurang') ?>"><button class="float-end neumorphic-button"><i class="nav-icon fas fa-minus"></i> Pengurangan Asset</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('asset/asset_semua_kurang') ?>"><button class="float-end neumorphic-button"> Daftar Semua Pengurangan Asset</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('asset/cetak_asset_semua') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak Asset</button></a>
                    </div>
                </nav>
            </div>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title); ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>No Per</th>
                                <!-- <th>Nama Per</th> -->
                                <th>Nama Asset</th>
                                <th>Lokasi</th>
                                <th>Tanggal</th>
                                <th>No Bkt Gdg</th>
                                <th>No Bkt Vch</th>
                                <th>Rupiah</th>
                                <th>Tgl Update</th>
                                <th>Ptgs Update</th>
                                <th>Ket</th>
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
                                    <!-- <td>
                                        <?php
                                        $nama_perkiraan = $row->name;
                                        if (strlen($nama_perkiraan) > 35) {
                                            $nama_perkiraan = substr($nama_perkiraan, 0, 35) . '...';
                                        }
                                        ?>
                                        <?= $nama_perkiraan; ?>
                                    </td> -->
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
                                        <?php
                                        $nama_bagian = $row->nama_bagian;
                                        ?>
                                        <?php if ($nama_bagian == "Umum") : ?>
                                            <?= $nama_bagian = "Bondowoso"; ?>
                                        <?php else : ?>
                                            <?= $nama_bagian; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= date('d-m-Y', strtotime($row->tanggal)); ?></td>
                                    <td><?= $row->no_bukti_gd; ?></td>
                                    <td><?= $row->no_bukti_vch; ?></td>
                                    <td class="text-right"><?= number_format($row->rupiah, 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= $row->tanggal_input; ?></td>
                                    <td class="text-right"><?= $row->input_asset; ?></td>
                                    <td class="text-center">
                                        <!-- <a href="javascript:void(0);" onclick="confirmReset(<?= $row->id_asset; ?>, '<?= addslashes($row->nama_asset); ?>', '<?= date('d-m-Y', strtotime($row->tanggal)); ?>')">
                                            <span class="badge badge-primary"><i class="fas fa-fw fa-edit"></i></span>
                                        </a> -->
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
                                                <a href="javascript:void(0);" onclick="confirmReset(<?= $row->id_asset; ?>, '<?= addslashes($row->nama_asset); ?>', '<?= date('d-m-Y', strtotime($row->tanggal)); ?>')">
                                                    <span class="badge badge-primary"><i class="fas fa-fw fa-edit"></i></span>
                                                </a>
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
                                <th></th>
                                <!-- <th></th> -->
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Jumlah</th>
                                <th class="text-right"><?= number_format($total_rupiah, 0, ',', '.'); ?></th>
                                <th></th>
                                <th></th>
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