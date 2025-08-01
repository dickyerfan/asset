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
                                <label>Uraian</label>
                                <input type="text" name="uraian" class="form-control" value="<?= isset($penanganan) ? $penanganan->uraian : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('uraian'); ?></small>
                            </div>
                            <div class="form-group">
                                <label>Jadwal</label>
                                <input type="text" name="jadwal" class="form-control" value="<?= isset($penanganan) ? $penanganan->jadwal : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('jadwal'); ?></small>
                            </div>
                            <div class="form-group">
                                <label>Hasil</label>
                                <input type="text" name="hasil" class="form-control" value="<?= isset($penanganan) ? $penanganan->hasil : '' ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('hasil'); ?></small>
                            </div>
                            <div class="form-group">
                                <label>Penanggung Jawab TL</label>
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
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="<?= base_url('risiko/profil_risiko?id_upk=' . $this->session->userdata('id_bagian') . '&tahun=' . $tahun) ?>" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</section>
</div>