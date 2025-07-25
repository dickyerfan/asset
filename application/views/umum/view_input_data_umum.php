    <section class="content">
        <div class="container-fluid">
            <?= $this->session->flashdata('info'); ?>
            <?= $this->session->unset_userdata('info'); ?>
            <div class="card">
                <div class="card-header card-outline card-primary shadow">
                    <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                    <a href="<?= base_url('umum/data_umum'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
                </div>
                <div class="card-body">
                    <form class="user" action="" method="POST">
                        <div class="row justify-content-center">
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="tahun">Tahun:</label>
                                        <input type="number" class="form-control" id="tahun" name="tahun" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= set_value('tahun'); ?>">
                                        <small class="form-text text-danger pl-3"><?= form_error('tahun'); ?></small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="uraian">Uraian :</label>
                                    <select class="form-control" id="uraian" name="uraian">
                                        <option value="">-- Pilih Uraian --</option>
                                        <option value="Jumlah Pegawai" <?= set_select('uraian', 'jumlah pegawai'); ?>>Jumlah Pegawai</option>
                                        <option value="Jumlah Pegawai Ikut Diklat" <?= set_select('uraian', 'jumlah pegawai ikut diklat'); ?>>Jumlah Pegawai Ikut Diklat</option>
                                        <option value="Biaya Diklat" <?= set_select('uraian', 'biaya diklat'); ?>>Biaya Diklat</option>
                                        <option value="Biaya Pegawai" <?= set_select('uraian', 'biaya pegawai'); ?>>Biaya Pegawai</option>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('uraian'); ?></small>
                                </div>

                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="jumlah">Jumlah :</label>
                                        <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Masukan Jumlah" value="<?= set_value('jumlah'); ?>">
                                        <small class="form-text text-danger pl-3"><?= form_error('jumlah'); ?></small>
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