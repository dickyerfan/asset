    <section class="content">
        <div class="container-fluid">
            <?= $this->session->flashdata('info'); ?>
            <?= $this->session->unset_userdata('info'); ?>
            <div class="card">
                <div class="card-header card-outline card-primary shadow">
                    <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                    <a href="<?= base_url('keuangan/modal_pemda'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
                </div>
                <div class="card-body">
                    <form class="user" action="" method="POST">
                        <div class="row justify-content-center">
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="tahun_data">Tahun Penyertaan :</label>
                                    <input type="number" class="form-control" id="tahun_data" name="tahun_data" placeholder="Masukan Tahun" min="1989" max="2099" value="<?= set_value('tahun_data'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tahun_data'); ?></small>
                                </div>
                                <div class="form-group">
                                    <label for="id_kec">Lokasi :</label>
                                    <select name="id_kec" id="id_kec" class="form-control select2">
                                        <option value="">Pilih Lokasi</option>
                                        <?php foreach ($kec as $row) :  ?>
                                            <option value="<?= $row->id_kec; ?>"><?= $row->nama_kec; ?></option>
                                        <?php endforeach;  ?>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('id_kec'); ?></small>
                                </div>
                                <div class="form-group">
                                    <label for="jenis_asset">Jenis Asset :</label>
                                    <input type="text" class="form-control" id="jenis_asset" name="jenis_asset" placeholder="Masukan Jenis Asset" value="<?= set_value('jenis_asset'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jenis_asset'); ?></small>
                                </div>
                                <div class="form-group">
                                    <label for="rupiah">Rupiah :</label>
                                    <input type="number" class="form-control" id="rupiah" name="rupiah" placeholder="Masukan Jenis Asset" value="<?= set_value('rupiah'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('rupiah'); ?></small>
                                </div>
                                <div class="form-group">
                                    <label for="sumber_dana">Sumber Dana :</label>
                                    <input type="text" class="form-control" id="sumber_dana" name="sumber_dana" placeholder="Masukan sumber_dana" value="<?= set_value('sumber_dana'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('sumber_dana'); ?></small>
                                </div>
                                <div class="form-group">
                                    <label for="unit_pemberi">Unit Pemberi:</label>
                                    <input type="text" class="form-control" id="unit_pemberi" name="unit_pemberi" placeholder="Masukan Unit Pemberi" value="<?= set_value('unit_pemberi'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('unit_pemberi'); ?></small>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan :</label>
                                    <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Masukan Keterangan" value="<?= set_value('keterangan'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('keterangan'); ?></small>
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