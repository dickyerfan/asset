<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('lap_keuangan/hutang'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="nama_utsr">Nama / Uraian :</label>
                                <input type="text" class="form-control" id="nama_utsr" name="nama_utsr" placeholder="Masukan Uraian" value="<?= set_value('nama_utsr'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('nama_utsr'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tgl_utsr">Tanggal Uang Titipan SR :</label>
                                    <input type="number" class="form-control" id="tgl_utsr" name="tgl_utsr" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= set_value('tgl_utsr'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tgl_utsr'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_utsr">Jumlah :</label>
                                    <input type="text" class="form-control" id="jumlah_utsr" name="jumlah_utsr" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_utsr'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_utsr'); ?></small>
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