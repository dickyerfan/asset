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
                            <div class="form-group mb-2">
                                <label>Kategori</label>
                                <input type="text" name="kategori_kr" class="form-control" value="<?= isset($kategori) ? $kategori->kategori_kr : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('kategori_kr'); ?></small>
                            </div>
                            <div class="form-group mb-2">
                                <label>Nama</label>
                                <input type="text" name="nama_kr" class="form-control" value="<?= isset($kategori) ? $kategori->nama_kr : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('nama_kr'); ?></small>
                            </div>
                            <div class="form-group mb-2">
                                <label>Tipe</label>
                                <input type="text" name="tipe_kr" class="form-control" value="<?= isset($kategori) ? $kategori->tipe_kr : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('tipe_kr'); ?></small>
                            </div>
                            <div class="form-group mb-2">
                                <label>Status </label>
                                <input type="number" name="status_kr" class="form-control" value="<?= isset($kategori) ? $kategori->status_kr : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('status_kr'); ?></small>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>