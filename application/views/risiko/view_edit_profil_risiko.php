<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('risiko/profil_risiko'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kegiatan</label>
                                <input type="text" name="kegiatan" class="form-control" value="<?= isset($profil) ? $profil->kegiatan : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('kegiatan'); ?></small>
                            </div>
                            <div class="form-group">
                                <label>Tujuan</label>
                                <input type="text" name="tujuan" class="form-control" value="<?= isset($profil) ? $profil->tujuan : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('tujuan'); ?></small>
                            </div>
                            <div class="form-group">
                                <label>Kode Risiko</label>
                                <select name="kode_risiko" class="form-control select2">
                                    <option value="">-- Pilih Kode Risiko --</option>
                                    <?php foreach ($kode_risiko as $kr) : ?>
                                        <?php $val = $kr->tipe_kr . '-' . $kr->kategori_kr; ?>
                                        <option value="<?= $val ?>">
                                            <?= $kr->kategori_kr ?> - <?= $kr->nama_kr ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class=" form-text text-danger pl-3"><?= form_error('kode_risiko'); ?></small>
                            </div>
                            <div class="form-group">
                                <label>Pernyataan</label>
                                <input type="text" name="pernyataan" class="form-control" value="<?= isset($profil) ? $profil->pernyataan : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('pernyataan'); ?></small>
                            </div>
                            <div class="form-group">
                                <label>Sebab</label>
                                <input type="text" name="sebab" class="form-control" value="<?= isset($profil) ? $profil->sebab : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('sebab'); ?></small>
                            </div>
                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="kategori" class="form-control">
                                    <option value="C" <?= (isset($profil) && $profil->kategori == 'C') ? 'selected' : '' ?>>C</option>
                                    <option value="UC" <?= (isset($profil) && $profil->kategori == 'UC') ? 'selected' : '' ?>>UC</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('kategori'); ?></small>
                            </div>
                            <div class="form-group">
                                <label>Dampak</label>
                                <input type="text" name="dampak" class="form-control" value="<?= isset($profil) ? $profil->dampak : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('dampak'); ?></small>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="<?= base_url('risiko/profil_risiko?id_upk=' . $this->session->userdata('id_bagian') . '&tahun=' . $tahun) ?>" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</section>
</div>