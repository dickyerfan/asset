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
                                    <label>Probabilitas</label>
                                    <input type="number" name="probabilitas" class="form-control" value="<?= isset($matriks) ? $matriks->probabilitas : '' ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('probabilitas'); ?></small>
                                </div>
                                <div class="form-group">
                                    <label>Dampak</label>
                                    <input type="number" name="dampak" class="form-control" value="<?= isset($matriks) ? $matriks->dampak : '' ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('dampak'); ?></small>
                                </div>
                                <div class="form-group">
                                    <label>Skor</label>
                                    <input type="number" name="skor" class="form-control" value="<?= isset($matriks) ? $matriks->skor : '' ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('skor'); ?></small>
                                </div>
                                <div class="form-group">
                                    <label>Level Risiko</label>
                                    <input type="number" name="level_risiko" class="form-control" value="<?= isset($matriks) ? $matriks->level_risiko : '' ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('level_risiko'); ?></small>
                                </div>
                                <div class="form-group">
                                    <label>Nama Level</label>
                                    <input type="text" name="nama_level" class="form-control" value="<?= isset($matriks) ? $matriks->nama_level : '' ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('nama_level'); ?></small>
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