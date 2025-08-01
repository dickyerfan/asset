    <section class="content">
        <div class="container-fluid">
            <?= $this->session->flashdata('info'); ?>
            <?= $this->session->unset_userdata('info'); ?>
            <div class="card">
                <div class="card-header card-outline card-primary shadow">
                    <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                    <a href="<?= base_url('risiko/pengaturan'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <form method="post">
                                <div class="form-group">
                                    <label>Nama Pemilik</label>
                                    <input type="text" name="nama_pemilik" class="form-control" value="<?= isset($kategori) ? $kategori->nama_pemilik : '' ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('nama_pemilik'); ?></small>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>