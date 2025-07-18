    <section class="content">
        <div class="container-fluid">
            <?= $this->session->flashdata('info'); ?>
            <?= $this->session->unset_userdata('info'); ?>
            <div class="card border-0">
                <div class="card-header shadow">
                    <div class="row title">
                        <div class="col-9">
                            <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                        </div>
                        <div class="col-3">
                            <a href="<?= base_url('arsip/folder'); ?>"><button class="neumorphic-button float-end"><i class="fas fa-reply"></i> Kembali</button></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card bg-light shadow text-dark">
                        <div class="card-body">
                            <form action="<?= base_url('arsip/tambah_folder') ?>" method="POST">
                                <div class="row justify-content-center mb-1">
                                    <div class="col-md-4">
                                        <div class="form-group mb-1">
                                            <label for="nama_folder" class="form-label">Nama Folder :</label>
                                            <input type="text" name="nama_folder" class="form-control" value="<?= set_value('nama_folder'); ?>">
                                            <small class="form-text text-danger pl-3"><?= form_error('nama_folder'); ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center mb-1">
                                    <div class="col-md-4">
                                        <div class="d-grid gap-2">
                                            <button type="submit" name="add_post" id="tombol_pilih" class="neumorphic-button">Upload File</button>
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