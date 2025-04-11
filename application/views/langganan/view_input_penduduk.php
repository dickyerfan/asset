<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('langganan/cak_layanan/data_penduduk'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="id_kec">Nama Kecamatan :</label>
                                <select name="id_kec" id="id_kec" class="form-control select2">
                                    <option value="">Pilih Kecamatan</option>
                                    <?php foreach ($kec as $row) :  ?>
                                        <option value="<?= $row->id_kec; ?>"><?= $row->nama_kec; ?></option>
                                    <?php endforeach;  ?>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('id_kec'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tahun_data">Tahun Data :</label>
                                    <input type="number" class="form-control" id="tahun_data" name="tahun_data" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= set_value('tahun_data'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tahun_data'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="wil_layan">Wilayah Layanan :</label>
                                    <select name="wil_layan" id="wil_layan" class="form-control select2">
                                        <option value="">Pilih</option>
                                        <option value="YA">YA</option>
                                        <option value="TIDAK">TIDAK</option>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('wil_layan'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="wil_adm">Wilayah Administrasi :</label>
                                    <select name="wil_adm" id="wil_adm" class="form-control select2">
                                        <option value="">Pilih</option>
                                        <option value="YA">YA</option>
                                        <option value="TIDAK">TIDAK</option>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('wil_adm'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_penduduk">Jumlah Penduduk :</label>
                                    <input type="number" class="form-control" id="jumlah_penduduk" name="jumlah_penduduk" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_penduduk'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_penduduk'); ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_kk">Jumlah KK :</label>
                                    <input type="number" class="form-control" id="jumlah_kk" name="jumlah_kk" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_kk'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_kk'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jiwa_kk">Jiwa KK :</label>
                                    <input type="number" class="form-control" id="jiwa_kk" name="jiwa_kk" placeholder="Masukan Jumlah" value="<?= set_value('jiwa_kk'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jiwa_kk'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_wil_layan">Jumlah Wil Layanan :</label>
                                    <input type="number" class="form-control" id="jumlah_wil_layan" name="jumlah_wil_layan" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_wil_layan'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_wil_layan'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_kk_layan">Jumlah KK Layanan :</label>
                                    <input type="number" class="form-control" id="jumlah_kk_layan" name="jumlah_kk_layan" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_kk_layan'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_kk_layan'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jiwa_kk_layan">Jiwa KK Layanan :</label>
                                    <input type="number" class="form-control" id="jiwa_kk_layan" name="jiwa_kk_layan" placeholder="Masukan jiwa" value="<?= set_value('jumlah_kk_layan'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_kk_layan'); ?></small>
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