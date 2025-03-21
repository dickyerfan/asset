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
                    <a href="<?= base_url('dashboard_pelihara') ?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                            <!-- <span class="right badge badge-danger">New</span> -->
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('pelihara/water_meter') ?>" class="nav-link">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            Tera & Ganti Meter
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('pelihara/tekanan_air') ?>" class="nav-link">
                        <i class="nav-icon fas fa-box"></i>
                        <p>
                            Data Tekanan Air
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('pelihara/jam_ops') ?>" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Jam Operasional
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