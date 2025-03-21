<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('pelihara/tekanan_air'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form action="<?= base_url('pelihara/tekanan_air/update_tka') ?>" method="post">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="id_bagian">Nama UPK :</label>
                                <select name="id_bagian" class="form-control">
                                    <?php foreach ($bagian_upk as $bagian) : ?>
                                        <option value="<?= $bagian->id_bagian ?>" <?= $bagian->id_bagian == $tekanan_air->id_bagian ? 'selected' : '' ?>>
                                            <?= $bagian->nama_bagian ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('id_bagian'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tahun_tka">Tahun Tekanan Air:</label>
                                    <input type="number" class="form-control" id="tahun_tka" name="tahun_tka" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= $tekanan_air->tahun_tka; ?>">
                                    <small class=" form-text text-danger pl-3"><?= form_error('tahun_tka'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_sr">Jumlah SR :</label>
                                    <input type="number" class="form-control" id="jumlah_sr" name="jumlah_sr" placeholder="Masukan Jumlah" value="<?= $tekanan_air->jumlah_sr; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_sr'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_cek">Jumlah yang Di Cek :</label>
                                    <input type="number" class="form-control" id="jumlah_cek" name="jumlah_cek" placeholder="Masukan Jumlah" value="<?= $tekanan_air->jumlah_cek; ?>">
                                    <small class=" form-text text-danger pl-3"><?= form_error('jumlah_cek'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_07">Jumlah Diatas 0.7 :</label>
                                    <input type="number" class="form-control" id="jumlah_07" name="jumlah_07" placeholder="Masukan Jumlah" value="<?= $tekanan_air->jumlah_07; ?>">
                                    <small class=" form-text text-danger pl-3"><?= form_error('jumlah_07'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_sr_70">Jumlah SR Diatas 70 :</label>
                                    <input type="number" class="form-control" id="jumlah_sr_70" name="jumlah_sr_70" placeholder="Masukan Jumlah" value="<?= $tekanan_air->jumlah_sr_70; ?>">
                                    <small class=" form-text text-danger pl-3"><?= form_error('jumlah_sr_70'); ?></small>
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