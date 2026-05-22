<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('lap_keuangan/keuntungan_kerugian_luar_biasa'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="tgl_klb">Tahun :</label>
                                <input type="number" class="form-control" id="tgl_klb" name="tgl_klb" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= set_value('tgl_klb'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('tgl_klb'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="jenis_klb">Akun :</label>
                                <select class="form-control" id="jenis_klb" name="jenis_klb">
                                    <option value="">-- Pilih Akun --</option>
                                    <option value="Keuntungan Luar biasa" <?= set_value('jenis_klb') == 'Keuntungan Luar biasa' ? 'selected' : ''; ?>>Keuntungan</option>
                                    <option value="Kerugian Luar biasa" <?= set_value('jenis_klb') == 'Kerugian Luar biasa' ? 'selected' : ''; ?>>Kerugian</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('jenis_klb'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="jumlah_klb">Jumlah :</label>
                                <input type="text" class="form-control" id="jumlah_klb" name="jumlah_klb" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_klb'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('jumlah_klb'); ?></small>
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