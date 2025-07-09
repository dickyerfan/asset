<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('umum/pegawai') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('umum/pegawai'); ?>" method="get">
                        <div style="display: flex; align-items: center;">
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
                    <?php if ($this->session->userdata('bagian') == 'Umum' or $this->session->userdata('bagian') == 'Administrator') { ?>
                        <div class="navbar-nav ms-2">
                            <a href="<?= base_url('umum/pegawai/input_pegawai') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Pegawai Baru</button></a>
                        </div>
                    <?php } ?>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('umum/pegawai/cetak_pegawai') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>

                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title); ?></h5>
                    </div>
                </div>
                <div class="table-responsive" style="font-size: 0.8rem;">
                    <table id="contoh" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="bg-secondary text-center">
                                <th>No</th>
                                <th>
                                    <?php if ($this->session->userdata('bagian') == 'Umum' or $this->session->userdata('bagian') == 'Administrator') { ?>
                                        Action
                                    <?php } ?>
                                </th>
                                <th>Nama Lengkap</th>
                                <th>Nik</th>
                                <th>Bagian</th>
                                <th>SubBag</th>
                                <!-- <th>Jabatan</th> -->
                                <th>Alamat</th>
                                <!-- <th>Agama</th> -->
                                <th>Status Karyawan</th>
                                <th>No HP</th>
                                <th>Jenis Kelamin</th>
                                <!-- <th>Tempat lahir</th> -->
                                <th>Tanggal lahir</th>
                                <th>Tanggal Masuk</th>
                                <th>Umur</th>
                                <!-- <th>Aktif</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($karyawan as $row) :
                            ?>
                                <?php
                                $tanggalLahir = ubahNamaBulan($row->tgl_lahir);
                                $tanggalMasuk = ubahNamaBulan($row->tgl_masuk);

                                $tgl_lahir = new DateTime($row->tgl_lahir);
                                $tgl_skrng = new DateTime();
                                $umur = $tgl_skrng->diff($tgl_lahir)->y;
                                ?>
                                <tr>
                                    <td class="text-center"><small><?= $no++ ?></small></td>
                                    <td class="text-center">
                                        <?php if ($this->session->userdata('bagian') == 'Umum' or $this->session->userdata('bagian') == 'Administrator') { ?>
                                            <div class="text-center">
                                                <a href="<?= base_url(); ?>umum/pegawai/edit_pegawai/<?= $row->id; ?>"><i class="fas fa-fw fa-edit" data-bs-toggle="tooltip" title="Klik untuk Edit Data"></i></a>
                                                <!-- <a href="<?= site_url('umum/pegawai/hapus/' . $row->id); ?>" style="color: red;" class="tombolHapus"><i class="fas fa-fw fa-trash" data-bs-toggle="tooltip" title="Klik untuk Hapus Data"></i></a>
                                        <a href="<?= site_url('umum/pegawai/detail/' . $row->id); ?>" style="color: green;" data-bs-toggle="tooltip" title="Klik untuk Detail Data"><i class="fas fa-fw fa-eye"></i></a> -->
                                            </div>
                                        <?php } ?>
                                    </td>
                                    <td><?= $row->nama ?></td>
                                    <td><?= $row->nik ?></td>
                                    <td><?= $row->nama_bagian ?></td>
                                    <td><?= $row->nama_subag ?></td>
                                    <!-- <td class="text-center"><?= $row->nama_jabatan ?></td> -->
                                    <td><?= $row->alamat ?></td>
                                    <!-- <td><?= $row->agama ?></td> -->
                                    <td><?= $row->status_pegawai ?></td>
                                    <td><?= $row->no_hp ?></td>
                                    <td><?= $row->jenkel ?></td>
                                    <!-- <td><?= $row->tmp_lahir ?></td> -->
                                    <td><?= $tanggalLahir ?></td>
                                    <td><?= $tanggalMasuk == '30 November -0001' ? '-' : $tanggalMasuk ?></td>
                                    <td class="text-center"><?= $umur ?></td>
                                    <!-- <td><?= $row->aktif == 1 ? 'Aktif' : 'Purna' ?></td> -->
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>