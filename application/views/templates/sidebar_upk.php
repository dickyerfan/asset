<aside class="main-sidebar sidebar-light-primary elevation-4">

    <a href="#" class="brand-link" style="text-decoration: none;">
        <img src="<?= base_url('assets') ?>/img/pdam_biru.png" alt="PDAM Logo" class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-bold text-primary">PDAM BWS</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a class="d-block" data-toggle="collapse" href="#user" style="text-decoration: none;">UPK <?= $this->session->userdata('nama_lengkap'); ?></a>
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
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-bookmark"></i>
                        <p>Profil</p>
                    </a>
                </li>
            </ul>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?= base_url('spi/hasil_evaluasi') ?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Evaluasi Kinerja
                            <!-- <span class="right badge badge-danger">New</span> -->
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-medkit"></i>
                        <p>
                            Manajemen Risiko
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('risiko/profil_risiko?id_upk=' . $this->session->userdata('id_bagian') . '&tahun=' . date('Y')) ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Profil Risiko</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('risiko/matrik_risiko') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Matrik Risiko</p>
                            </a>
                        </li>
                    </ul>
                    <!-- <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('risiko/pengaturan') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pengaturan</p>
                            </a>
                        </li>
                    </ul> -->
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
        </nav>
    </div>
</aside>
<div class="content-wrapper">