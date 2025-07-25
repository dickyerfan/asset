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
                    <form class="user" action="<?= base_url('umum/data_umum/update_data_umum') ?>" method="POST">
                        <div class="row justify-content-center">
                            <div class="col-md-4 mb-3">
                                <input type="hidden" name="id_data_umum" value="<?= $data_umum->id_data_umum; ?>">
                                <div class="form-group">
                                    <label for="tahun">Tahun : </label>
                                    <input type="number" class="form-control" id="tahun" name="tahun" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= $data_umum->tahun; ?>" disabled>
                                    <small class="form-text text-danger pl-3"><?= form_error('tahun'); ?></small>
                                </div>
                                <div class="form-group">
                                    <label for="uraian">Uraian :</label>
                                    <select class="form-control" id="uraian" name="uraian" disabled>
                                        <option value="">-- Pilih Uraian --</option>
                                        <option value="Jumlah Pegawai" <?= ($data_umum->uraian == 'Jumlah Pegawai') ? 'selected' : ''; ?>>Jumlah Pegawai</option>
                                        <option value="Jumlah Pegawai Ikut Diklat" <?= ($data_umum->uraian == 'Jumlah Pegawai Ikut Diklat') ? 'selected' : ''; ?>>Jumlah Pegawai Ikut Diklat</option>
                                        <option value="Biaya Diklat" <?= ($data_umum->uraian == 'Biaya Diklat') ? 'selected' : ''; ?>>Biaya Diklat</option>
                                        <option value="Biaya Pegawai" <?= ($data_umum->uraian == 'Biaya Pegawai') ? 'selected' : ''; ?>>Biaya Pegawai</option>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('uraian'); ?></small>
                                </div>
                                <div class="form-group">
                                    <label for="jumlah">Jumlah :</label>
                                    <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Masukan Tentang Perjanjian" value="<?= $data_umum->jumlah; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah'); ?></small>

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