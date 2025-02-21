<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('lap_keuangan/asset_tetap'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="nama_atdp">Nama / Uraian :</label>
                                <input type="text" class="form-control" id="nama_atdp" name="nama_atdp" placeholder="Masukan Uraian" value="<?= set_value('nama_atdp'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('nama_atdp'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tgl_atdp">Tanggal Asset Tetap Dalam Penyelesaian :</label>
                                    <!-- <input type="date" class="form-control" id="tgl_atdp" name="tgl_atdp" placeholder="Masukan Tanggal Piutang" value="<?= set_value('tgl_atdp'); ?>"> -->
                                    <input type="number" class="form-control" id="tgl_atdp" name="tgl_atdp" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= set_value('tgl_atdp'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tgl_atdp'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_atdp">Jumlah :</label>
                                    <input type="text" class="form-control" id="jumlah_atdp" name="jumlah_atdp" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_atdp'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_atdp'); ?></small>
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