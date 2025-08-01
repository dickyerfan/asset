<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;">INPUT TINGKAT RISIKO</a>
                <a href="<?= base_url('risiko/pengaturan'); ?>">
                    <button class="neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button>
                </a>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <form method="post">
                            <div class="form-group mb-2">
                                <label>Level</label>
                                <input type="number" name="level_tr" class="form-control" value="<?= set_value('level_tr') ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('level_tr'); ?></small>
                            </div>
                            <div class="form-group mb-2">
                                <label>Nama Level</label>
                                <input type="text" name="nama_tr" class="form-control" value="<?= set_value('nama_tr') ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('nama_tr'); ?></small>
                            </div>
                            <div class="form-group mb-2">
                                <label>Skor Min</label>
                                <input type="number" name="skor_min" class="form-control" value="<?= set_value('skor_min') ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('skor_min'); ?></small>
                            </div>
                            <div class="form-group mb-2">
                                <label>Skor Max</label>
                                <input type="number" name="skor_max" class="form-control" value="<?= set_value('skor_max') ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('skor_max'); ?></small>
                            </div>
                            <div class="form-group mb-2">
                                <label>Status</label>
                                <input type="text" name="status_tr" class="form-control" value="<?= set_value('status_tr') ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('status_tr'); ?></small>
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