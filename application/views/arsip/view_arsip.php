<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-3 col-md-6 mb-1">
                        <div class="card border-0 bg-danger shadow">
                            <div class="card-body bg-light cardEffect border-left border-danger border-5 rounded" data-toggle="modal" data-target="#eska">
                                <div class="row no-gutters align-items-center">
                                    <div class="col pl-3">
                                        <a href="#" class="text-decoration-none font-weight-bold text-dark text-uppercase" style="font-size: 0.8rem;">
                                            Surat Keputusan</a>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $sk ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="mr-3 fas fa-file-alt fa-2x text-gray-300" style="color:red;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-1">
                        <div class="card border-0 bg-success shadow">
                            <div class="card-body bg-light cardEffect border-start border-success border-5 rounded" data-toggle="modal" data-target="#peraturan">
                                <div class="row no-gutters align-items-center">
                                    <div class="col pl-3">
                                        <a href="#" class="text-decoration-none fw-bold text-dark text-uppercase" style="font-size: 0.8rem;">
                                            Peraturan</a>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $per ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="mr-3 fas fa-file-alt fa-2x text-gray-300" style="color:green;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-1">
                        <div class="card border-0 bg-warning shadow">
                            <div class="card-body bg-light cardEffect border-start border-warning border-5 rounded" data-toggle="modal" data-target="#berker">
                                <div class="row no-gutters align-items-center">
                                    <div class="col pl-3">
                                        <a href="#" class="text-decoration-none fw-bold text-dark text-uppercase" style="font-size: 0.8rem;">
                                            Berkas Kerja</a>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $bk ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="mr-3 fas fa-file-alt fa-2x text-gray-300" style="color:goldenrod;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-1">
                        <div class="card border-0 bg-primary shadow">
                            <div class="card-body bg-light cardEffect border-start border-primary border-5 rounded" data-toggle="modal" data-target="#dokumen">
                                <div class="row no-gutters align-items-center">
                                    <div class="col pl-3">
                                        <a href="#" class="text-decoration-none fw-bold text-dark text-uppercase" style="font-size: 0.8rem;">
                                            Dokumen</a>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $dk ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="mr-3 fas fa-file-alt fa-2x text-gray-300" style="color:blue;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="p-2">
            <?= $this->session->flashdata('info'); ?>
            <?= $this->session->unset_userdata('info'); ?>
        </div>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <!-- <form method="get" action="<?= base_url('arsip'); ?>" class="form-inline">
                        <div class="form-group mr-2">
                            <select name="folder" class="form-control">
                                <option value=""> Filter Folder </option>
                                <?php foreach ($folder_list as $folder) : ?>
                                    <option value="<?= $folder->nama_folder ?>" <?= ($this->input->get('folder') == $folder->nama_folder) ? 'selected' : '' ?>>
                                        <?= $folder->nama_folder ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="neumorphic-button">Tampilkan</button>
                    </form>
                    <form method="get" action="<?= base_url('arsip'); ?>" class="form-inline ml-2">
                        <div class="form-group mr-2">
                            <input type="number" name="tahun" class="form-control ml-2" value="<?= isset($filter['tahun']) ? $filter['tahun'] : date('Y') ?>">
                        </div>
                        <button type="submit" class="neumorphic-button">Tampilkan</button>
                    </form> -->
                    <div class="navbar-nav">
                        <a href="<?= base_url('arsip/folder') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Tambah Folder</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <form method="get" action="<?= base_url('arsip'); ?>" class="form-inline">
                            <div class="form-group mr-2">
                                <select name="folder" class="form-control">
                                    <option value="">Semua Folder</option>
                                    <?php foreach ($folder_list as $folder) : ?>
                                        <option value="<?= $folder->nama_folder ?>" <?= ($this->input->get('folder') == $folder->nama_folder) ? 'selected' : '' ?>>
                                            <?= $folder->nama_folder ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <input type="number" name="tahun" class="form-control" placeholder="Tahun" value="<?= $this->input->get('tahun') ?>">
                            </div>
                            <button type="submit" class="neumorphic-button">Tampilkan</button>
                        </form>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('arsip/tambah') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Upload File</button></a>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5 class="font-weight-bold"><?= strtoupper($title); ?></h5>
                        <?php if ($this->input->get('folder')) : ?>
                            <div>
                                <strong><?= 'FOLDER : ' . strtoupper($this->input->get('folder')); ?></strong>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->input->get('tahun')) : ?>
                            <div>
                                <strong><?= 'TAHUN : ' .  $this->input->get('tahun') ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="table-responsive" style="font-size: 0.7rem;">
                    <table id="contoh" class="table table-hover table-striped table-bordered table-sm" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-secondary">
                                <th class="text-center">No</th>
                                <th class="text-center" width="6%">Action</th>
                                <th class="text-center">Jenis</th>
                                <th class="text-center">Folder</th>
                                <th class="text-center">Nama Dokumen</th>
                                <th class="text-center">Tentang</th>
                                <th class="text-center">Tahun</th>
                                <th class="text-center">Ptgs Upload</th>
                                <th class="text-center">Tgl Upload</th>
                                <th class="text-center">Ptgs Update</th>
                                <th class="text-center">Tgl Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($arsip as $row) :
                            ?>
                                <tr>
                                    <td class="text-center"><small><?= $no++ ?></small></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('arsip/edit/' . $row->id_arsip); ?>"><i class=" fas fa-fw fa-edit" data-bs-toggle="tooltip" title="Klik untuk Edit Data"></i></a>
                                        <a href="<?= base_url('arsip/hapus/' . $row->id_arsip); ?>" class="tombolHapus" data-user="<?= $this->session->userdata('bagian'); ?>">
                                            <i class="fas fa-fw fa-trash" style="color: red;" data-bs-toggle="tooltip" title="Klik untuk Hapus Data"></i>
                                        </a>
                                        <a href="<?= base_url('arsip/detail/') ?><?= $row->id_arsip; ?>"><i class="fas fa-fw fa-info-circle" style="color: black;" data-bs-toggle="tooltip" title="Klik untuk lihat Detail Data"></i></a>
                                        <a href="<?= base_url('arsip/download/') ?><?= $row->id_arsip; ?>"> <i class="fas fa-download" style="text-decoration:none; color:green;" data-bs-toggle="tooltip" title="Klik untuk Download Data"></i></a>
                                        <a href="<?= base_url('arsip/baca/') ?><?= $row->id_arsip; ?>" target="_blank"><i class="fas fa-book-open" style="text-decoration:none; color:orange;" data-bs-toggle="tooltip" title="Klik untuk Baca Data"></i> </a>
                                    </td>
                                    <td><?= $row->jenis; ?></td>
                                    <td><?= $row->nama_folder; ?></td>
                                    <td><?= $row->nama_dokumen; ?></td>
                                    <td><?= $row->tentang; ?></td>
                                    <td class="text-center"><?= $row->tahun; ?></td>
                                    <td><?= $row->created_by; ?></td>
                                    <td><?= date('d-m-Y ', strtotime($row->created_at)); ?></td>
                                    <td class="text-center">
                                        <?= $row->modified_by ? $row->modified_by : '-'; ?>
                                    </td>
                                    <td class="text-center">
                                        <?=
                                        ($row->modified_at && $row->modified_at !== '0000-00-00 00:00:00')
                                            ? date('d-m-Y', strtotime($row->modified_at))
                                            : '-'
                                        ?>
                                    </td>
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

<!-- Modal Eska-->
<div class="modal fade" id="eska" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header border-left border-danger border-5">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Surat Keputusan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                $numOfCols = 4;
                $rowCount = 0;
                $bootstrapColWidth = 12 / $numOfCols;
                ?>
                <div class="row justify-content-center">
                    <?php foreach ($daftarEska as $row) : ?>
                        <div class="col-xl-<?= $bootstrapColWidth; ?> mb-1">
                            <h6 style="font-size: 0.7rem;"><?= $row->nama_dokumen ?></h6>
                        </div>
                        <?php
                        $rowCount++;
                        if ($rowCount % $numOfCols == 0) echo '</div><div class="row justify-content-center">';
                        ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Peraturan-->
<div class="modal fade" id="peraturan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header border-left border-success border-5">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Peraturan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                $numOfCols = 4;
                $rowCount = 0;
                $bootstrapColWidth = 12 / $numOfCols;
                ?>
                <div class="row justify-content-center">
                    <?php foreach ($daftarPer as $row) : ?>
                        <div class="col-xl-<?= $bootstrapColWidth; ?> mb-1">
                            <h6 style="font-size: 0.7rem;"><?= $row->nama_dokumen ?></h6>
                        </div>
                    <?php
                        $rowCount++;
                        if ($rowCount % $numOfCols == 0) echo '</div><div class="row">';
                    endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Berker-->
<div class="modal fade" id="berker" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header border-left border-warning border-5">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Berkas Kerja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                $numOfCols = 4;
                $rowCount = 0;
                $bootstrapColWidth = 12 / $numOfCols;
                ?>
                <div class="row justify-content-center">
                    <?php foreach ($daftarBer as $row) : ?>
                        <div class="col-xl-<?= $bootstrapColWidth; ?> mb-1">
                            <h6 style="font-size: 0.7rem;"><?= $row->nama_dokumen ?></h6>
                        </div>
                    <?php
                        $rowCount++;
                        if ($rowCount % $numOfCols == 0) echo '</div><div class="row">';
                    endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Dokumen-->
<div class="modal fade" id="dokumen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header border-left border-primary border-5">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                $numOfCols = 4;
                $rowCount = 0;
                $bootstrapColWidth = 12 / $numOfCols;
                ?>
                <div class="row justify-content-center">
                    <?php foreach ($daftarDok as $row) : ?>
                        <div class="col-xl-<?= $bootstrapColWidth; ?> mb-1">
                            <h6 style="font-size: 0.7rem;"><?= $row->nama_dokumen ?></h6>
                        </div>
                    <?php
                        $rowCount++;
                        if ($rowCount % $numOfCols == 0) echo '</div><div class="row">';
                    endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</main>