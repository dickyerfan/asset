<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('pelihara/kapasitas_produksi'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="<?= base_url('pelihara/kapasitas_produksi/update_kp') ?>" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <input type="hidden" name="id_ek_kp" value="<?= $kapasitas_edit->id_ek_kp; ?>">
                                <label for="id_bagian">Nama UPK :</label>
                                <select name="id_bagian" class="form-control">
                                    <?php foreach ($bagian_upk as $bagian) : ?>
                                        <option value="<?= $bagian->id_bagian ?>" <?= $bagian->id_bagian == $kapasitas_edit->id_bagian ? 'selected' : '' ?>>
                                            <?= $bagian->nama_bagian ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('id_bagian'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tahun_kp">Tahun Kapasitas Produksi:</label>
                                    <input type="number" class="form-control" id="tahun_kp" name="tahun_kp" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= $kapasitas_edit->tahun_kp; ?>">
                                    <small class=" form-text text-danger pl-3"><?= form_error('tahun_kp'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="kap_pasang">Kapasitas Terpasang :</label>
                                    <input type="number" class="form-control" id="kap_pasang" name="kap_pasang" step="0.1" placeholder="Masukan Jumlah" value="<?= $kapasitas_edit->kap_pasang; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('kap_pasang'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="terpasang">Terpasang :</label>
                                    <input type="number" class="form-control" id="terpasang" name="terpasang" placeholder="Masukan Jumlah" value="<?= $kapasitas_edit->terpasang; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('terpasang'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tidak_manfaat">Tidak Dimanfaatkan :</label>
                                    <input type="number" class="form-control" id="tidak_manfaat" name="tidak_manfaat" placeholder="Masukan Jumlah" value="<?= $kapasitas_edit->tidak_manfaat; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tidak_manfaat'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="volume_produksi">Volume Produksi :</label>
                                    <input type="number" class="form-control" id="volume_produksi" name="volume_produksi" placeholder="Masukan Jumlah" value="<?= $kapasitas_edit->volume_produksi; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('volume_produksi'); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-12 text-center">
                            <button class=" neumorphic-button mt-2" name="tambahkan" type="submit"><i class="fas fa-save"></i> Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
</div>