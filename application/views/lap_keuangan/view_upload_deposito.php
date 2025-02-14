<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('lap_keuangan/penjelasan'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tahun">Tahun :</label>
                                    <!-- <input type="text" class="form-control" id="tahun" name="tahun" placeholder="Masukan Tahun Piutang" value="<?= set_value('tahun'); ?>"> -->
                                    <input type="number" class="form-control" id="tahun" name="tahun" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= set_value('tahun'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tahun'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="nilai_neraca">Nilai Deposito :</label>
                                    <input type="text" class="form-control" id="nilai_neraca" name="nilai_neraca" placeholder="Masukan Jumlah" value="<?= set_value('nilai_neraca'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('nilai_neraca'); ?></small>
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