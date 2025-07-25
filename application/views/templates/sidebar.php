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
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>
                            Aset dan Penyusutan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- Menu Rekap Penyusutan -->
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard_asset') ?>" class="nav-link">
                                <i class="fas fa-tachometer-alt nav-icon"></i>
                                <p>Rekap Penyusutan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('penyusutan') ?>" class="nav-link">
                                <i class="fas fa-table nav-icon"></i>
                                <p>Total Penyusutan</p>
                            </a>
                        </li>
                        <!-- Submenu Detail Penyusutan -->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="fas fa-box nav-icon"></i>
                                <p>Detail Penyusutan <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a href="<?= base_url('penyusutan/tanah') ?>" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>Tanah</p>
                                    </a></li>
                                <li class="nav-item"><a href="<?= base_url('penyusutan/bangunan') ?>" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>Bangunan</p>
                                    </a></li>
                                <li class="nav-item"><a href="<?= base_url('penyusutan/sumber') ?>" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>Ins Sumber</p>
                                    </a></li>
                                <li class="nav-item"><a href="<?= base_url('penyusutan/pompa') ?>" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>Ins Pompa</p>
                                    </a></li>
                                <li class="nav-item"><a href="<?= base_url('penyusutan/olah_air') ?>" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>Ins Pengolahan Air</p>
                                    </a></li>
                                <li class="nav-item"><a href="<?= base_url('penyusutan/trans_dist') ?>" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>Ins Trans & Dist</p>
                                    </a></li>
                                <li class="nav-item"><a href="<?= base_url('penyusutan/peralatan') ?>" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>Peralatan & Perlk</p>
                                    </a></li>
                                <li class="nav-item"><a href="<?= base_url('penyusutan/kendaraan') ?>" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>Kendaraan</p>
                                    </a></li>
                                <li class="nav-item"><a href="<?= base_url('penyusutan/inventaris') ?>" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>Inventaris</p>
                                    </a></li>
                            </ul>
                        </li>
                        <!-- Menu Jurnal dan Amortisasi -->
                        <li class="nav-item">
                            <a href="<?= base_url('jurnal') ?>" class="nav-link">
                                <i class="fas fa-paper-plane nav-icon"></i>
                                <p>Jurnal Umum</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('amortisasi') ?>" class="nav-link">
                                <i class="fas fa-dollar-sign nav-icon"></i>
                                <p>Amortisasi</p>
                            </a>
                        </li>
                        <!-- Menu Daftar dan Detail Asset -->
                        <li class="nav-item">
                            <a href="<?= base_url('asset/asset_semua') ?>" class="nav-link">
                                <i class="fas fa-chart-pie nav-icon"></i>
                                <p>Daftar Semua Asset</p>
                            </a>
                        </li>
                        <!-- Submenu Detail Asset -->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="fas fa-copy nav-icon"></i>
                                <p>Detail Asset <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a href="<?= base_url('asset_rekap') ?>" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>Tanah</p>
                                    </a></li>
                                <li class="nav-item"><a href="<?= base_url('asset_rekap/sumber') ?>" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>Ins Sumber</p>
                                    </a></li>
                                <li class="nav-item"><a href="<?= base_url('asset_rekap/pompa') ?>" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>Ins Pompa</p>
                                    </a></li>
                                <li class="nav-item"><a href="<?= base_url('asset_rekap/olah_air') ?>" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>Ins Pengolahan Air</p>
                                    </a></li>
                                <li class="nav-item"><a href="<?= base_url('asset_rekap/trans_dist') ?>" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>Ins Trans & Dist</p>
                                    </a></li>
                                <li class="nav-item"><a href="<?= base_url('asset_rekap/bangunan') ?>" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>Bangunan</p>
                                    </a></li>
                                <li class="nav-item"><a href="<?= base_url('asset_rekap/peralatan') ?>" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>Peralatan & Perlk</p>
                                    </a></li>
                                <li class="nav-item"><a href="<?= base_url('asset_rekap/kendaraan') ?>" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>Kendaraan</p>
                                    </a></li>
                                <li class="nav-item"><a href="<?= base_url('asset_rekap/inventaris') ?>" class="nav-link"><i class="far fa-circle nav-icon"></i>
                                        <p>Inventaris</p>
                                    </a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-receipt"></i>
                        <p>
                            Laporan Keuangan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('lap_keuangan/neraca'); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Neraca</p>
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a href="<?= base_url('lap_keuangan/lr_saketap'); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>LR SAK ETAP</p>
                            </a>
                        </li> -->
                        <li class="nav-item">
                            <a href="<?= base_url('lap_keuangan/lr_sak_ep'); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>LR SAK EP</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('lap_keuangan/perubahan_ekuitas'); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>PERUBAHAN EKUITAS</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('lap_keuangan/arus_kas'); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Arus Kas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>LK Arus Kas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('lap_keuangan/penjelasan'); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Penjelasan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('lap_keuangan/asset_tetap') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Asset Tetap</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('lap_keuangan/peny_piutang') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Perhitungan Piutang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('lap_keuangan/peny_piutang/hitung_piutang') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Penyisihan Piutang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('lap_keuangan/persediaan') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Persediaan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('lap_keuangan/hutang') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Hutang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('lap_keuangan/ekuitas') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ekuitas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('lap_keuangan/pendapatan') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pendapatan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('lap_keuangan/beban') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Beban</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('lap_keuangan/beban_pajak') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Beban Pajak</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('lap_keuangan/penghasilan_komp_lain') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Penghasilan Komp. Lain</p>
                            </a>
                        </li>
                    </ul>
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
                            <a href="<?= base_url('dashboard_publik') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Rekap Penilaian</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('evkin_pupr') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>KemenPUPR</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('evkin_permendagri') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Permendagri 47 th 99</p>
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
                                    <a href="<?= base_url('keuangan/modal_pemda') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Penyertaan Modal</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url('keuangan/hibah') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Modal Hibah</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url('keuangan/modal_ybds') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Modal YBDS</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url('keuangan/kejadian_penting') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Kejadian Penting</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
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
                                        <p>Sumur & Sumber</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('pelihara/kualitas_air') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Data Uji Kualitas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('pelihara/kapasitas_produksi') ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Kapasitas Produksi</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <?php if ($this->session->userdata('bagian') == 'Administrator') : ?>
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
                <?php endif; ?>
                <li class="nav-item">
                    <a href="<?= base_url('arsip') ?>" class="nav-link">
                        <i class="nav-icon fas fa-archive"></i>
                        <p>
                            Ruang Arsip
                        </p>
                    </a>
                </li>
                <?php if ($this->session->userdata('bagian') == 'Administrator') : ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tools"></i>
                            <p>
                                Setting
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('setting/daftar_user') ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Daftar User</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('backup') ?>" class="nav-link">
                            <i class="nav-icon fas fa-database"></i>
                            <p>Backup & Restore</p>
                        </a>
                    </li>
                <?php endif; ?>
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