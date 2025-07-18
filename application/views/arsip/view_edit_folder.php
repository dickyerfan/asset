<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('arsip/folder'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <div class="card bg-light shadow text-dark">
                    <div class="card-body">
                        <form action="<?= base_url('arsip/update_folder') ?>" method="POST">
                            <div class="row justify-content-center mb-1">
                                <div class="col-md-6">
                                    <div class="form-group mb-1">
                                        <input type="hidden" name="id_folder" class="form-control" value="<?= $folder->id_folder ?>">
                                    </div>
                                    <div class="form-group mb-1">
                                        <label for="nama_folder" class="form-label">Nama Folder :</label>
                                        <input type="text" name="nama_folder" class="form-control" value="<?= $folder->nama_folder; ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center mb-1">
                                <div class="col-md-4">
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