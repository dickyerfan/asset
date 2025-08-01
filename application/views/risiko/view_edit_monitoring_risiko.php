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
                                <label>Rencana Tindak Pengendalian</label>
                                <input type="text" name="rtp" class="form-control" value="<?= isset($monitoring) ? $monitoring->rtp : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('rtp'); ?></small>
                            </div>
                            <div class="form-group">
                                <label>Jadwal</label>
                                <input type="text" name="jadwal" class="form-control" value="<?= isset($monitoring) ? $monitoring->jadwal : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('jadwal'); ?></small>
                            </div>
                            <div class="form-group">
                                <label>Hasil</label>
                                <input type="text" name="hasil" class="form-control" value="<?= isset($monitoring) ? $monitoring->hasil : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('hasil'); ?></small>
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <input type="text" name="keterangan" class="form-control" value="<?= isset($monitoring) ? $monitoring->keterangan : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('keterangan'); ?></small>
                            </div>
                            <div class="form-group">
                                <label>Probabilitas Setelah RTP</label>
                                <select name="prob_setelah" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="1" <?= (isset($monitoring) && $monitoring->prob_setelah == '1') ? 'selected' : '' ?>>1</option>
                                    <option value="2" <?= (isset($monitoring) && $monitoring->prob_setelah == '2') ? 'selected' : '' ?>>2</option>
                                    <option value="3" <?= (isset($monitoring) && $monitoring->prob_setelah == '3') ? 'selected' : '' ?>>3</option>
                                    <option value="4" <?= (isset($monitoring) && $monitoring->prob_setelah == '4') ? 'selected' : '' ?>>4</option>
                                    <option value="5" <?= (isset($monitoring) && $monitoring->prob_setelah == '5') ? 'selected' : '' ?>>5</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('probabilitas'); ?></small>
                            </div>
                            <div class="form-group">
                                <label>Dampak Setelah RTP</label>
                                <select name="dampak_setelah" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="1" <?= (isset($monitoring) && $monitoring->dampak_setelah == '1') ? 'selected' : '' ?>>1</option>
                                    <option value="2" <?= (isset($monitoring) && $monitoring->dampak_setelah == '2') ? 'selected' : '' ?>>2</option>
                                    <option value="3" <?= (isset($monitoring) && $monitoring->dampak_setelah == '3') ? 'selected' : '' ?>>3</option>
                                    <option value="4" <?= (isset($monitoring) && $monitoring->dampak_setelah == '4') ? 'selected' : '' ?>>4</option>
                                    <option value="5" <?= (isset($monitoring) && $monitoring->dampak_setelah == '5') ? 'selected' : '' ?>>5</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('dampak'); ?></small>
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