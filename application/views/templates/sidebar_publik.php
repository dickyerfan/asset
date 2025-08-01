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
                        <i class="nav-icon fas fa-globe"></i>
                        <p>
                            Evaluasi SPAM
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
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
                                        <p>Perubahan Ekuitas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('keuangan/arus_kas') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Lap Arus Kas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('keuangan/modal_pemda') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Penyertaan Modal</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('keuangan/hibah') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Modal Hibah</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('keuangan/modal_ybds') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Modal YBDS</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('keuangan/kejadian_penting') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Kejadian Penting</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('keuangan/aspek_ops') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Aspek Ops & Adm</p>
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
                                <li class="nav-item">
                                    <a href="<?= base_url('umum/pegawai') ?>" class="nav-link">
                                        <i class="nav-icon fas fa-user"></i>
                                        <p>
                                            Data Pegawai
                                        </p>
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
                                <li class="nav-item">
                                    <a href="<?= base_url('langganan/pendapatan') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pendapatan & Tarif</p>
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
                                <li class="nav-item">
                                    <a href="<?= base_url('pelihara/kapasitas_produksi') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Kapasitas Produksi
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-pencil-alt"></i>
                                <p>
                                    Perencanaan
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url('rencana/data_teknis') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Data Teknis</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('rencana/kelola_spam') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pengelolaan SPAM</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('rencana/dok_rpam') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Dok. RPAM</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('rencana/dok_pam') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Dok. PAM</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('rencana/sedia_dana') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Ketersediaan Dana
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-exclamation-triangle"></i>
                        <p>
                            Evaluasi UPK
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('spi/hasil_evaluasi') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Hasil Evaluasi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('spi/aspek_teknik') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Aspek Teknik</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('spi/aspek_admin') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Aspek Administrasi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('spi/aspek_koordinasi') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Aspek Koordinasi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('spi/tindak_lanjut') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Tindak Lanjut
                                </p>
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a href="<?= base_url('spi/pengaturan') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Pengaturan
                                </p>
                            </a>
                        </li> -->
                    </ul>
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
                            <a href="<?= base_url('risiko/profil_risiko') ?>" class="nav-link">
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
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('risiko/pengaturan') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
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