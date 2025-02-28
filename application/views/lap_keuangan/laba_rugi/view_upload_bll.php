<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('lap_keuangan/beban'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="jenis_bll">Jenis :</label>
                                <!-- <input type="text" class="form-control" id="jenis_bll" name="jenis_bll" placeholder="Masukan Uraian" value="<?= set_value('jenis_bll'); ?>"> -->
                                <select name="jenis_bll" id="jenis_bll" class="form-control select2">
                                    <option value="">Pilih Jenis</option>
                                    <option value="Beban Bank">Beban Bank</option>
                                    <option value="Kerugian Penjualan Persediaan Barang">Kerugian Penjualan Persediaan Barang</option>
                                    <option value="Kerugian Trans. Val. Asing">Kerugian Trans. Val. Asing</option>
                                    <option value="Kerugian Pengb. Aktiva Tetap">Kerugian Pengb. Aktiva Tetap</option>
                                    <option value="Beban Kerugian Penurunan Persediaan">Beban Kerugian Penurunan Persediaan</option>
                                    <option value="Rupa-rupa Biaya Kerugian">Rupa-rupa Biaya Kerugian</option>
                                    <option value="Biaya AMDK">Biaya AMDK</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('jenis_bll'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="nama_bll">Nama / Uraian :</label>
                                <input type="text" class="form-control" id="nama_bll" name="nama_bll" placeholder="Masukan Uraian" value="<?= set_value('nama_bll'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('nama_bll'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tgl_bll">Tahun Beban Lain-lain :</label>
                                    <input type="number" class="form-control" id="tgl_bll" name="tgl_bll" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= set_value('tgl_bll'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tgl_bll'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_bll">Jumlah :</label>
                                    <input type="text" class="form-control" id="jumlah_bll" name="jumlah_bll" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_bll'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_bll'); ?></small>
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