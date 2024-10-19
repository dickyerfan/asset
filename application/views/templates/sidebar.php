<aside class="main-sidebar sidebar-light-primary elevation-4">

    <a href="#" class="brand-link" style="text-decoration: none;">
        <img src="<?= base_url('assets') ?>/img/pdam_biru.png" alt="PDAM Logo" class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-bold text-primary">PDAM BWS</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <!-- <div class="image">
                <img src="<?= base_url('assets') ?>/dist/img/avatar5.png" class="img-circle elevation-2" alt="User Image">
            </div> -->
            <div class="info">
                <a class="d-block" data-toggle="collapse" href="#user" style="text-decoration: none;"><?= $this->session->userdata('nama_lengkap'); ?></a>
            </div>
        </div>
        <div class="collapse" id="user">
            <ul class="nav nav-pills nav-sidebar flex-column">
                <li class="nav-item">
                    <a href="" class="nav-link">
                        <i class="nav-icon fas fa-key"></i>
                        <p>Ganti Password</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">
                        <i class="nav-icon fas fa-bookmark"></i>
                        <p>Profil</p>
                    </a>
                </li>
            </ul>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?= base_url('asset') ?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Penambahan Asset
                            <!-- <span class="right badge badge-danger">New</span> -->
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('asset_kurang') ?>" class="nav-link">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Pengurangan Asset
                            <!-- <span class="right badge badge-danger">New</span> -->
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Rekap Asset
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('asset_rekap') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tanah</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('asset_rekap/sumber') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ins Sumber</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('asset_rekap/pompa') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ins Pompa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('asset_rekap/olah_air') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ins Pengolahan Air</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('asset_rekap/trans_dist') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ins Trans & Dist</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('asset_rekap/bangunan') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bangunan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('asset_rekap/peralatan') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Peralatan & Perlk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('asset_rekap/kendaraan') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kendaraan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('asset_rekap/inventaris') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Inventaris</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('asset_rekap/penyusutan') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Akm Penyusutan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Data Pekerjaan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('pekerjaan/pekerjaan') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard Pekerjaan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('pekerjaan/bagian') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Bagian</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('pekerjaan/subag') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Sub Bagian/UPK</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('pekerjaan/jabatan') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Jabatan</p>
                            </a>
                        </li>
                    </ul>
                </li> -->
                <!-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-car"></i>
                        <p>
                            Data Kendaraan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('kendaraan/kendaraan_semua') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Semua Kendaraan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('kendaraan/kendaraan_orang') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pemegang Kendaraan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('kendaraan/merk') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Merek</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('kendaraan/type') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Type </p>
                            </a>
                        </li>
                    </ul>
                </li> -->
                <!-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Data User
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('user/admin') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Admin</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('user/user') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data User</p>
                            </a>
                        </li>
                    </ul>
                </li> -->
                <!-- <li class="nav-item">
                    <a href="<?= base_url('chart') ?>" class="nav-link">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Master Chart
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('tabel') ?>" class="nav-link">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Master Tabel
                        </p>
                    </a>
                </li> -->
                <li class="nav-item">
                    <a href="<?= base_url('penyusutan') ?>" class="nav-link">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Total Penyusutan
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-box"></i>
                        <p>
                            Detail Penyusutan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('penyusutan/tanah') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tanah</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('penyusutan/sumber') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ins Sumber</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('penyusutan/pompa') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ins Pompa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('penyusutan/olah_air') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ins Pengolahan Air</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('penyusutan/trans_dist') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ins Trans & Dist</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('penyusutan/bangunan') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bangunan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('penyusutan/peralatan') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Peralatan & Perlk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('penyusutan/kendaraan') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kendaraan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('penyusutan/inventaris') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Inventaris</p>
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a href="<?= base_url('asset_rekap/penyusutan') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Akm Penyusutan</p>
                            </a>
                        </li> -->
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('backup') ?>" class="nav-link">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            Backup & Restore
                        </p>
                    </a>
                </li>
                <li class="nav-item" data-toggle="modal" data-target="#logoutModal">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Logout
                            <!-- <span class="right badge badge-danger">New</span> -->
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
<div class="content-wrapper">