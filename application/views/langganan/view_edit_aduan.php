<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('langganan/data_pengaduan'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form action="<?= base_url('langganan/data_pengaduan/update_aduan') ?>" method="post">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <input type="hidden" name="id_ek_aduan" value="<?= $aduan->id_ek_aduan; ?>">
                                <div class="form-group">
                                    <label for="jumlah_aduan">Jumlah Aduan :</label>
                                    <input type="number" class="form-control" id="jumlah_aduan" name="jumlah_aduan" placeholder="Masukan Jumlah" value="<?= $aduan->jumlah_aduan; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_aduan'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_aduan_ya">Jumlah Pengaduan Terselesaikan :</label>
                                    <input type="number" class="form-control" id="jumlah_aduan_ya" name="jumlah_aduan_ya" placeholder="Masukan Jumlah" value="<?= $aduan->jumlah_aduan_ya; ?>">
                                    <small class=" form-text text-danger pl-3"><?= form_error('jumlah_aduan_ya'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_aduan_tidak">Jumlah Pengaduan Belum Terselesaikan :</label>
                                    <input type="number" class="form-control" id="jumlah_aduan_tidak" name="jumlah_aduan_tidak" placeholder="Masukan Jumlah" value="<?= $aduan->jumlah_aduan_tidak; ?>">
                                    <small class=" form-text text-danger pl-3"><?= form_error('jumlah_aduan_tidak'); ?></small>
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