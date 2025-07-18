<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('arsip'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <div class="card bg-light shadow text-dark">
                    <div class="card-body">
                        <form action="<?= base_url('arsip/update') ?>" method="POST" enctype="multipart/form-data">
                            <div class="row justify-content-center mb-1">
                                <div class="col-md-6">
                                    <div class="form-group mb-1">
                                        <input type="hidden" name="id_arsip" class="form-control" value="<?= $arsip->id_arsip ?>">
                                    </div>
                                    <div class="form-group mb-1">
                                        <label for="" class="form-label">Jenis Dokumen :</label>
                                        <select name="jenis" id="" class="form-select">
                                            <option value="">Pilih...</option>
                                            <option value="Surat Keputusan" <?= $arsip->jenis == "Surat Keputusan" ? 'selected' : '' ?>>Surat Keputusan</option>
                                            <option value="Peraturan" <?= $arsip->jenis == "Peraturan" ? 'selected' : '' ?>>Peraturan</option>
                                            <option value="Berkas Kerja" <?= $arsip->jenis == "Berkas Kerja" ? 'selected' : '' ?>>Berkas Kerja</option>
                                            <option value="Dokumen" <?= $arsip->jenis == "Dokumen" ? 'selected' : '' ?>>Dokumen</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label for="" class="form-label">Nama Dokumen :</label>
                                        <input type="text" name="nama_dokumen" class="form-control" value="<?= $arsip->nama_dokumen; ?>" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label for="" class="form-label">Tahun :</label>
                                        <input type="text" name="tahun" class="form-control" value="<?= $arsip->tahun; ?>" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label for="" class="form-label">Tanggal Dokumen :</label>
                                        <input type="date" name="tgl_dokumen" class="form-control" value="<?= $arsip->tgl_dokumen; ?>" required>
                                    </div>
                                    <!-- <div class="form-group mb-1">
                                        <label for="" class="form-label">Tanggal Upload :</label>
                                        <input type="date" name="tgl_upload" class="form-control" value="<?= $arsip->tgl_upload; ?>" required>
                                    </div> -->
                                </div>
                                <div class="col-md-6">

                                    <div class="form-group mb-1">
                                        <label for="" class="form-label">Tentang :</label>
                                        <textarea name="tentang" id="" rows="4" class="form-control"><?= $arsip->tentang; ?></textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="folder" class="form-label">Folder :</label>
                                        <select name="folder" class="form-control">
                                            <?php foreach ($folder_list as $f) : ?>
                                                <option value="<?= $f->id_folder ?>" <?= (isset($arsip) && $arsip->id_folder == $f->id_folder) ? 'selected' : '' ?>>
                                                    <?= $f->nama_folder ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>

                                        <!-- <textarea name="keterangan" id="" rows="3" class="form-control"><?= $arsip->id_folder; ?></textarea> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center mb-1">
                                <div class="col-md-8">
                                    <div class="d-grid gap-2">
                                        <button type="submit" name="add_post" id="tombol_pilih" class="neumorphic-button">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>