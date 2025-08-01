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
                    <a href="<?= base_url('dashboard_umum') ?>" class="nav-link">
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
                    <a href="<?= base_url('umum/pelatihan') ?>" class="nav-link">
                        <i class="nav-icon fas fa-globe"></i>
                        <p>
                            Pelatihan
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('umum/kerjasama') ?>" class="nav-link">
                        <i class="nav-icon fas fa-allergies"></i>
                        <p>
                            Kerjasama
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('umum/pegawai') ?>" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Data Pegawai
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('umum/data_umum') ?>" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Data Umum
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('keuangan/aspek_ops') ?>" class="nav-link">
                        <i class="nav-icon fas fa-calculator"></i>
                        <p>
                            Aspek Ops & Adm
                        </p>
                    </a>
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