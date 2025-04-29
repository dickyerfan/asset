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
                    <a href="<?= base_url('setting/password') ?>" class="nav-link">
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
                    <a href="<?= base_url('dashboard_publik') ?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                            <!-- <span class="right badge badge-danger">New</span> -->
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-coins"></i>
                        <p>
                            Keuangan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('lap_keuangan/neraca') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Neraca</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('lap_keuangan/lr_sak_ep') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Lap Laba Rugi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('lap_keuangan/perubahan_ekuitas') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Per Ekuitas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('keuangan/arus_kas') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Lap Arus Kas</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Umum
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('umum/pelatihan') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pelatihan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('umum/kerjasama') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kerjasama</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>
                            Langganan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('langganan/cak_layanan') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cakupan Layanan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('langganan/data_pengaduan') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Pengaduan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('langganan/tambah_sr') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Penambahan SR</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('langganan/rincian_pendapatan') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Rincian Pend. Kec</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('langganan/efek_tagih') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Efektifitas Penagihan</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>
                            Pemeliharaan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('pelihara/water_meter'); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Water Meter</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('pelihara/tekanan_air'); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Tekanan Air</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('pelihara/jam_ops'); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jam Operasional</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('pelihara/sb_mag') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Sumur & Sumber
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('pelihara/kualitas_air') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Data Uji Kualitas
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- <li class="nav-item">
                    <a href="<?= base_url('backup') ?>" class="nav-link">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            Backup & Restore
                        </p>
                    </a>
                </li> -->
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