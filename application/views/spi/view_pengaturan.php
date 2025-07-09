<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark"><?= strtoupper($title) ?></a>
            </div>
            <div class="card-body">

                <!-- Form Tambah Aspek -->
                <form method="post" action="<?= base_url('admin/indikator/tambah_aspek'); ?>">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Nama Aspek Baru</label>
                            <input type="text" name="nama_aspek" class="form-control" placeholder="Contoh: Teknis, Administrasi" required>
                        </div>
                        <div class="form-group col-md-2 align-self-end">
                            <button type="submit" class="btn btn-primary">Tambah Aspek</button>
                        </div>
                    </div>
                </form>

                <hr>

                <!-- Daftar Aspek dan Indikator -->
                <?php foreach ($aspek_list as $aspek) : ?>
                    <div class="card mt-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><?= $aspek->nama_aspek ?></h6>
                        </div>
                        <div class="card-body">
                            <!-- Form Tambah Indikator -->
                            <form method="post" action="<?= base_url('admin/indikator/tambah_indikator'); ?>">
                                <input type="hidden" name="id_aspek" value="<?= $aspek->id_aspek ?>">
                                <div class="form-row align-items-end">
                                    <div class="form-group col-md-4">
                                        <label>Nama Indikator</label>
                                        <input type="text" name="nama_indikator" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <button type="submit" class="btn btn-success">Tambah Indikator</button>
                                    </div>
                                </div>
                            </form>

                            <!-- Tabel Indikator -->
                            <?php if (!empty($indikator_list[$aspek->id_aspek])) : ?>
                                <table class="table table-bordered mt-3">
                                    <thead>
                                        <tr class="text-center">
                                            <th style="width: 30%">Indikator</th>
                                            <th>Opsi Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($indikator_list[$aspek->id_aspek] as $indikator) : ?>
                                            <tr>
                                                <td><?= $indikator->nama_indikator ?></td>
                                                <td>
                                                    <ul>
                                                        <?php foreach ($opsi_list[$indikator->id_indikator] ?? [] as $opsi) : ?>
                                                            <li><?= $opsi->label_hasil ?> (Skor: <?= $opsi->skor ?>)</li>
                                                        <?php endforeach; ?>
                                                    </ul>

                                                    <!-- Form Tambah Opsi -->
                                                    <form method="post" action="<?= base_url('admin/indikator/tambah_opsi'); ?>" class="form-inline">
                                                        <input type="hidden" name="id_indikator" value="<?= $indikator->id_indikator ?>">
                                                        <div class="form-group mr-2">
                                                            <input type="text" name="nama_opsi" class="form-control" placeholder="Nama Opsi" required>
                                                        </div>
                                                        <div class="form-group mr-2">
                                                            <input type="number" name="skor" class="form-control" placeholder="Skor" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-sm btn-primary">Tambah</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p class="text-muted">Belum ada indikator untuk aspek ini.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</section>
</div>