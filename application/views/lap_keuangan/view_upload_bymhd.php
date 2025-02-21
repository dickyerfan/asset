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
                                <label for="nama_bymhd">Nama / Uraian :</label>
                                <input type="text" class="form-control" id="nama_bymhd" name="nama_bymhd" placeholder="Masukan Uraian" value="<?= set_value('nama_bymhd'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('nama_bymhd'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tgl_bymhd">Tanggal Biaya Yang Masih Harus dibayar :</label>
                                    <input type="number" class="form-control" id="tgl_bymhd" name="tgl_bymhd" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= set_value('tgl_bymhd'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tgl_bymhd'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_bymhd">Jumlah :</label>
                                    <input type="text" class="form-control" id="jumlah_bymhd" name="jumlah_bymhd" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_bymhd'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_bymhd'); ?></small>
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