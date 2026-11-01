<aside class="main-sidebar sidebar-light-primary elevation-4">

    <a href="#" class="brand-link" style="text-decoration: none;">
        <img src="<?= base_url('assets') ?>/img/logo_ijen_tirta.png" alt="ijen Logo" class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-bold text-primary">IJEN TIRTA</span>
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
                    <a href="<?= base_url('dashboard_langgan') ?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                            <!-- <span class="right badge badge-danger">New</span> -->
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('evkin_pupr') ?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            KemenPUPR
                            <!-- <span class="right badge badge-danger">New</span> -->
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('evkin_permendagri') ?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Permendagri 47 th 99
                            <!-- <span class="right badge badge-danger">New</span> -->
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('langganan/cak_layanan') ?>" class="nav-link">
                        <i class="nav-icon fas fa-globe"></i>
                        <p>
                            Cakupan Layanan
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('langganan/data_pengaduan') ?>" class="nav-link">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>
                            Data Pengaduan
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('langganan/tambah_sr') ?>" class="nav-link">
                        <i class="nav-icon fas fa-plus"></i>
                        <p>
                            Penambahan SR
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('langganan/rincian_pendapatan') ?>" class="nav-link">
                        <i class="nav-icon fas fa-donate"></i>
                        <p>
                            Rincian Pend. Kec
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('langganan/efek_tagih') ?>" class="nav-link">
                        <i class="nav-icon fas fa-calculator"></i>
                        <p>
                            Efektifitas Penagihan
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('langganan/pendapatan') ?>" class="nav-link">
                        <i class="nav-icon fas fa-dollar-sign"></i>
                        <p>
                            Pendapatan & Tarif
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('keuangan/aspek_ops') ?>" class="nav-link">
                        <i class="nav-icon fas fa-eye"></i>
                        <p>
                            Aspek Ops & Adm
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-shield-alt"></i>
                        <p>
                            Man. Risiko
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('manrisk/dashboard') ?>" class="nav-link">
                                <i class="fas fa-tachometer-alt nav-icon"></i>
                                <p>Dashboard MR</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-project-diagram"></i>
                                <p>
                                    Proses Man. Risiko
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url('manrisk/lingkup') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Lingkup &amp; Konteks </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('manrisk/register') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Identifikasi Risiko</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('manrisk/analisa') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Analisis Risiko</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('manrisk/evaluasi') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Evaluasi Risiko</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('manrisk/perlakuan') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Perlakuan Risiko</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('manrisk/monitoring') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Peninjauan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('manrisk/kri') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>KPI / KRI</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('manrisk/laporan') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pelaporan</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>
                                    Analisis &amp; RKAP
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url('manrisk/matrik') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Matriks Risiko</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('manrisk/objektif') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Objektif &amp; RKAP</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('manrisk/import') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Impor Risk Register</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('manrisk/panduan') ?>" class="nav-link">
                                <i class="fas fa-book nav-icon"></i>
                                <p>Petunjuk Penggunaan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('manrisk/pengaturan') ?>" class="nav-link">
                                <i class="fas fa-cog nav-icon"></i>
                                <p>Pengaturan</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('arsip') ?>" class="nav-link">
                        <i class="nav-icon fas fa-archive"></i>
                        <p>
                            Ruang Arsip
                        </p>
                    </a>
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