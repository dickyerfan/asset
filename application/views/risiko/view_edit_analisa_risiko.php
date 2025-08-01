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
                                <label>Uraian Pengendalian</label>
                                <input type="text" name="kendali_uraian" class="form-control" value="<?= isset($analisa) ? $analisa->kendali_uraian : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('kendali_uraian'); ?></small>
                            </div>
                            <div class="form-group">
                                <label>Desain</label>
                                <select name="desain" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="Ada" <?= (isset($analisa) && $analisa->desain == 'Ada') ? 'selected' : '' ?>>Ada</option>
                                    <option value="Tidak" <?= (isset($analisa) && $analisa->desain == 'Tidak') ? 'selected' : '' ?>>Tidak</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('desain'); ?></small>
                            </div>
                            <div class="form-group">
                                <label>Efektivitas</label>
                                <select name="efektifitas" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="Tidak" <?= (isset($analisa) && $analisa->efektifitas == 'Tidak') ? 'selected' : '' ?>>Tidak</option>
                                    <option value="Kurang" <?= (isset($analisa) && $analisa->efektifitas == 'Kurang') ? 'selected' : '' ?>>Kurang</option>
                                    <option value="Efektif" <?= (isset($analisa) && $analisa->efektifitas == 'Efektif') ? 'selected' : '' ?>>Efektif</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('efektifitas'); ?></small>
                            </div>
                            <div class="form-group">
                                <label>Probabilitas</label>
                                <select name="probabilitas" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="1" <?= (isset($analisa) && $analisa->probabilitas == '1') ? 'selected' : '' ?>>1</option>
                                    <option value="2" <?= (isset($analisa) && $analisa->probabilitas == '2') ? 'selected' : '' ?>>2</option>
                                    <option value="3" <?= (isset($analisa) && $analisa->probabilitas == '3') ? 'selected' : '' ?>>3</option>
                                    <option value="4" <?= (isset($analisa) && $analisa->probabilitas == '4') ? 'selected' : '' ?>>4</option>
                                    <option value="5" <?= (isset($analisa) && $analisa->probabilitas == '5') ? 'selected' : '' ?>>5</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('probabilitas'); ?></small>
                            </div>
                            <div class="form-group">
                                <label>Dampak</label>
                                <select name="dampak" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="1" <?= (isset($analisa) && $analisa->dampak == '1') ? 'selected' : '' ?>>1</option>
                                    <option value="2" <?= (isset($analisa) && $analisa->dampak == '2') ? 'selected' : '' ?>>2</option>
                                    <option value="3" <?= (isset($analisa) && $analisa->dampak == '3') ? 'selected' : '' ?>>3</option>
                                    <option value="4" <?= (isset($analisa) && $analisa->dampak == '4') ? 'selected' : '' ?>>4</option>
                                    <option value="5" <?= (isset($analisa) && $analisa->dampak == '5') ? 'selected' : '' ?>>5</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('dampak'); ?></small>
                            </div>
                            <div class="form-group">
                                <label>Pemilik Risiko</label>
                                <select name="pemilik_risiko" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <?php if (isset($pemilik_risiko) && is_array($pemilik_risiko)) : ?>
                                        <?php foreach ($pemilik_risiko as $pemilik) : ?>
                                            <option value="<?= htmlspecialchars($pemilik->nama_pemilik) ?>" <?= (isset($analisa) && $analisa->pemilik_risiko == $pemilik->nama_pemilik) ? 'selected' : '' ?>><?= htmlspecialchars($pemilik->nama_pemilik) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('pemilik_risiko'); ?></small>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="<?= base_url('risiko/profil_risiko'); ?>" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</section>
</div>