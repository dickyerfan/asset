<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <div class="card card-outline card-primary mt-3">
            <div class="card-header">
                <h5 class="mb-0">Input Profil Risiko</h5>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('risiko/profil_risiko/input_risiko') ?>">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="id_upk" class="form-label">UPK <span class="text-danger">*</span></label>
                            <select name="id_upk" id="id_upk" class="form-control">
                                <option value="">Pilih UPK</option>
                                <?php foreach ($unit_list as $unit) : ?>
                                    <option value="<?= $unit->id_bagian ?>"><?= $unit->nama_bagian ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-danger pl-3"><?= form_error('id_upk'); ?></small>
                        </div>
                        <div class="col-md-6">
                            <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                            <input type="number" name="tahun" id="tahun" class="form-control" value="<?= date('Y') ?>">
                            <small class="form-text text-danger pl-3"><?= form_error('tahun'); ?></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kegiatan" class="form-label">Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="kegiatan" id="kegiatan" class="form-control">
                            <small class="form-text text-danger pl-3"><?= form_error('kegiatan'); ?></small>
                        </div>
                        <div class="col-md-6">
                            <label for="tujuan" class="form-label">Tujuan Kegiatan </label>
                            <input type="text" name="tujuan" id="tujuan" class="form-control">
                            <small class="form-text text-danger pl-3"><?= form_error('tujuan'); ?></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <!-- <div class="col-md-6">
                            <label for="kode_risiko" class="form-label">Kode Risiko </label>
                            <input type="text" name="kode_risiko" id="kode_risiko" class="form-control">
                            <small class="form-text text-danger pl-3"><?= form_error('kode_risiko'); ?></small>
                        </div> -->
                        <div class="col-md-6">
                            <label for="pernyataan" class="form-label">Pernyataan Risiko </label>
                            <input type="text" name="pernyataan" id="pernyataan" class="form-control">
                            <small class="form-text text-danger pl-3"><?= form_error('pernyataan'); ?></small>
                        </div>
                        <div class="col-md-6">
                            <label for="sebab" class="form-label">Sebab </label>
                            <input type="text" name="sebab" id="sebab" class="form-control">
                            <small class="form-text text-danger pl-3"><?= form_error('sebab'); ?></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kategori" class="form-label">Kategori (C/UC) </label>
                            <select name="kategori" id="kategori" class="form-select">
                                <option value="">Pilih Kategori</option>
                                <option value="C">Control</option>
                                <option value="UC">Uncontrol</option>
                            </select>
                            <small class="form-text text-danger pl-3"><?= form_error('kategori'); ?></small>
                        </div>
                        <div class="col-md-6">
                            <label for="dampak" class="form-label">Dampak </label>
                            <input type="text" name="dampak" id="dampak" class="form-control">
                            <small class="form-text text-danger pl-3"><?= form_error('dampak'); ?></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                            <a href="<?= base_url('risiko/profil_risiko') ?>" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
</div>