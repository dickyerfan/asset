<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <div class="navbar-nav ms-2">
                        <!-- <a href="<?= base_url('risiko/pengaturan/input_risiko') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Risiko</button></a> -->
                    </div>
                    <div class="navbar-nav ms-auto">
                        <!-- <a href="<?= base_url('risiko/pengaturan/cetak_risiko') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a> -->
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title); ?> </h5>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6>Tabel Matrik Risiko</h6>
                    <a href="<?= base_url('risiko/pengaturan/input_matrik') ?>" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Input matrik Risiko</a>
                </div>
                <table id="tabel_matrik" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr class="text-center">
                            <th>Probabilitas</th>
                            <th>Dampak</th>
                            <th>Skor</th>
                            <th>Level Risiko</th>
                            <th>Nama Level</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($matrik) && count($matrik) > 0) : foreach ($matrik as $m) : ?>
                                <tr class="text-center">
                                    <td><?= $m->probabilitas ?></td>
                                    <td><?= $m->dampak ?></td>
                                    <td><?= $m->skor ?></td>
                                    <td><?= $m->level_risiko ?></td>
                                    <td><?= $m->nama_level ?></td>
                                    <td>
                                        <a href="<?= base_url('risiko/pengaturan/edit_matrik/' . $m->id_mr) ?>" class="btn btn-warning btn-sm">Edit</a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else : ?>
                            <tr>
                                <td colspan="6" class="text-center">Data tidak tersedia</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <h6 class="mt-4">Tabel Tingkat Risiko</h6>
                    <a href="<?= base_url('risiko/pengaturan/input_tingkat') ?>" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Input Tingkat Risiko</a>
                </div>
                <table id="tabel_tingkat" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr class="text-center">
                            <th>Level</th>
                            <th>Nama Level</th>
                            <th>Skor Min</th>
                            <th>Skor Max</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($tingkat) && count($tingkat) > 0) : foreach ($tingkat as $t) : ?>
                                <tr class="text-center">
                                    <td><?= $t->level_tr ?></td>
                                    <td><?= $t->nama_tr ?></td>
                                    <td><?= $t->skor_min ?></td>
                                    <td><?= $t->skor_max ?></td>
                                    <td><?= $t->status_tr ?></td>
                                    <td>
                                        <a href="<?= base_url('risiko/pengaturan/edit_tingkat/' . $t->id_tr) ?>" class="btn btn-warning btn-sm">Edit</a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else : ?>
                            <tr>
                                <td colspan="6" class="text-center">Data tidak tersedia</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <h6 class="mt-4">Tabel Kategori Risiko</h6>
                            <a href="<?= base_url('risiko/pengaturan/input_kategori') ?>" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Input Kategori Risiko</a>
                        </div>
                        <div class="table-responsive">
                            <table id="tabel_kategori" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>Kategori</th>
                                        <th>Nama </th>
                                        <th>tipe </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($kategori) && count($kategori) > 0) : foreach ($kategori as $k) : ?>
                                            <tr>
                                                <td class="text-center"><?= $k->kategori_kr ?></td>
                                                <td><?= $k->nama_kr ?></td>
                                                <td class="text-center"><?= $k->tipe_kr ?></td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('risiko/pengaturan/edit_kategori/' . $k->id_kr) ?>" class="btn btn-warning btn-sm">Edit</a>
                                                </td>
                                            </tr>
                                        <?php endforeach;
                                    else : ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Data tidak tersedia</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <h6 class="mt-4">Tabel Bagian / UPK</h6>
                            <a href="#" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Input Bagian / UPK</a>
                        </div>
                        <div class="table-responsive">
                            <table id="tabel_bagian" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>No </th>
                                        <th>Nama</th>
                                        <th>Status </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    if (isset($bagian) && count($bagian) > 0) : foreach ($bagian as $bag) : ?>
                                            <tr>
                                                <td class="text-center"><?= $no++ ?></td>
                                                <td><?= $bag->nama_bagian ?></td>
                                                <td class="text-center">
                                                    <?= $bag->status == 1 ? 'Aktif' : 'Tidak Aktif' ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('risiko/pengaturan/edit_bagian/' . $bag->id_bagian) ?>" class="btn btn-warning btn-sm">Edit</a>
                                                </td>
                                            </tr>
                                        <?php endforeach;
                                    else : ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Data tidak tersedia</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <h6 class="mt-4">Tabel Pemilik Risiko</h6>
                            <a href="<?= base_url('risiko/pengaturan/input_pemilik') ?>" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Input Pemilik Risiko</a>
                        </div>
                        <div class="table-responsive">
                            <table id="tabel_pemilik" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>No </th>
                                        <th>Nama</th>
                                        <th>Status </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    if (isset($pemilik) && count($pemilik) > 0) : foreach ($pemilik as $pem) : ?>
                                            <tr>
                                                <td class="text-center"><?= $no++ ?></td>
                                                <td><?= $pem->nama_pemilik ?></td>
                                                <td class="text-center">
                                                    <?= $pem->status_pemilik == 1 ? 'Aktif' : 'Tidak Aktif' ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('risiko/pengaturan/edit_pemilik/' . $pem->id_pemilik) ?>" class="btn btn-warning btn-sm">Edit</a>
                                                </td>
                                            </tr>
                                        <?php endforeach;
                                    else : ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Data tidak tersedia</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>