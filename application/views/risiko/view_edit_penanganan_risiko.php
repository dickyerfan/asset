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
                                <label><i class="fas fa-pen mr-1"></i>Uraian</label>
                                <input type="text" name="uraian" class="form-control" value="<?= isset($penanganan) ? $penanganan->uraian : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('uraian'); ?></small>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-calendar mr-1"></i>Jadwal</label>
                                <input type="text" name="jadwal" class="form-control" value="<?= isset($penanganan) ? $penanganan->jadwal : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('jadwal'); ?></small>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-check-double mr-1"></i>Hasil</label>
                                <input type="text" name="hasil" class="form-control" value="<?= isset($penanganan) ? $penanganan->hasil : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('hasil'); ?></small>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-user-shield mr-1"></i>Penanggung Jawab TL</label>
                                <!-- <input type="text" name="pj_tl" class="form-control" value="<?= isset($penanganan) ? $penanganan->pj_tl : '' ?>"> -->
                                <select name="pj_tl" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <?php if (isset($pemilik_risiko) && is_array($pemilik_risiko)) : ?>
                                        <?php foreach ($pemilik_risiko as $pemilik) : ?>
                                            <option value="<?= htmlspecialchars($pemilik->nama_pemilik) ?>" <?= (isset($penanganan) && $penanganan->pj_tl == $pemilik->nama_pemilik) ? 'selected' : '' ?>><?= htmlspecialchars($pemilik->nama_pemilik) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('pj_tl'); ?></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-tasks mr-1"></i>Kegiatan</label>
                                <textarea class="form-control" readonly><?= isset($profil_risiko) ? $profil_risiko->kegiatan : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-bullseye mr-1"></i>Tujuan</label>
                                <textarea class="form-control" readonly><?= isset($profil_risiko) ? $profil_risiko->tujuan : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-exclamation-circle mr-1"></i>Pernyataan/Risiko</label>
                                <textarea class="form-control" readonly><?= isset($profil_risiko) ? $profil_risiko->pernyataan : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-cogs mr-1"></i>Sebab</label>
                                <textarea class="form-control" readonly><?= isset($profil_risiko) ? $profil_risiko->sebab : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-bolt mr-1"></i>Dampak</label>
                                <textarea class="form-control" readonly><?= isset($profil_risiko) ? $profil_risiko->dampak : '' ?></textarea>
                            </div>
                            <!-- <div class="form-group">
                                <label>Kode Risiko</label>
                                <textarea class="form-control" readonly><?= isset($profil_risiko) ? $profil_risiko->kode_risiko : '' ?></textarea>
                            </div> -->
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