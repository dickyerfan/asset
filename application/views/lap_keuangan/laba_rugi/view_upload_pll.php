<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('lap_keuangan/pendapatan'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="jenis_pll">Jenis :</label>
                                <!-- <input type="text" class="form-control" id="jenis_pll" name="jenis_pll" placeholder="Masukan Uraian" value="<?= set_value('jenis_pll'); ?>"> -->
                                <select name="jenis_pll" id="jenis_pll" class="form-control select2">
                                    <option value="">Pilih Jenis</option>
                                    <option value="Pendapatan AMDK">Pendapatan AMDK</option>
                                    <option value="Bunga Simpanan dan Deposito">Bunga Simpanan dan Deposito</option>
                                    <option value="Rupa-rupa Pendapatan lainnya">Rupa-rupa Pendapatan lainnya</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('jenis_pll'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="nama_pll">Nama / Uraian :</label>
                                <input type="text" class="form-control" id="nama_pll" name="nama_pll" placeholder="Masukan Uraian" value="<?= set_value('nama_pll'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('nama_pll'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tgl_pll">Tanggal Pendapatan Penjualan Air :</label>
                                    <input type="number" class="form-control" id="tgl_pll" name="tgl_pll" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= set_value('tgl_pll'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tgl_pll'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_pll">Jumlah :</label>
                                    <input type="text" class="form-control" id="jumlah_pll" name="jumlah_pll" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_pll'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_pll'); ?></small>
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