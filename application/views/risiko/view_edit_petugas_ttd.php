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
                    <div class="col-md-7">
                        <form method="post">
                            <div class="form-group mb-2">
                                <label>Kode TTD</label>
                                <select name="kode_ttd" class="form-control">
                                    <option value="">-- Pilih Kode TTD --</option>
                                    <option value="pemilik_risiko" <?= set_select('kode_ttd', 'pemilik_risiko', isset($ttd) && $ttd->kode_ttd == 'pemilik_risiko') ?>>Pemilik Risiko</option>
                                    <option value="ketua_satgas_mr" <?= set_select('kode_ttd', 'ketua_satgas_mr', isset($ttd) && $ttd->kode_ttd == 'ketua_satgas_mr') ?>>Ketua Satgas MR</option>
                                    <option value="ketua_spi" <?= set_select('kode_ttd', 'ketua_spi', isset($ttd) && $ttd->kode_ttd == 'ketua_spi') ?>>Ketua SPI</option>
                                    <option value="direktur" <?= set_select('kode_ttd', 'direktur', isset($ttd) && $ttd->kode_ttd == 'direktur') ?>>Direktur</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('kode_ttd'); ?></small>
                            </div>
                            <div class="form-group mb-2">
                                <label>Jabatan TTD</label>
                                <input type="text" name="jabatan_ttd" class="form-control" value="<?= set_value('jabatan_ttd', isset($ttd) ? $ttd->jabatan_ttd : '') ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('jabatan_ttd'); ?></small>
                            </div>
                            <div class="form-group mb-2">
                                <label>Bagian / UPK</label>
                                <select name="id_bagian" class="form-control">
                                    <option value="">Global / Tidak terkait Bagian</option>
                                    <?php foreach ($bagian as $bag) : ?>
                                        <option value="<?= $bag->id_bagian ?>" <?= set_select('id_bagian', $bag->id_bagian, isset($ttd) && $ttd->id_bagian == $bag->id_bagian) ?>>
                                            <?= $bag->nama_bagian ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted pl-3">Pilih Bagian / UPK hanya untuk kode Pemilik Risiko.</small>
                            </div>
                            <div class="form-group mb-2">
                                <label>Nama Petugas</label>
                                <input type="text" name="nama_petugas" class="form-control" value="<?= set_value('nama_petugas', isset($ttd) ? $ttd->nama_petugas : '') ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('nama_petugas'); ?></small>
                            </div>
                            <div class="form-group mb-2">
                                <label>NIK</label>
                                <input type="text" name="nik" class="form-control" value="<?= set_value('nik', isset($ttd) ? $ttd->nik : '') ?>">
                            </div>
                            <div class="form-group mb-2">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" <?= set_select('status', '1', isset($ttd) && $ttd->status == '1') ?>>Aktif</option>
                                    <option value="0" <?= set_select('status', '0', isset($ttd) && $ttd->status == '0') ?>>Tidak Aktif</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('status'); ?></small>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="<?= base_url('risiko/pengaturan') ?>" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
