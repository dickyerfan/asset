<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('langganan/cak_layanan/data_pelanggan'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
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
                                    <label for="n_aktif_dom">Pelanggan Non Aktif Domestik :</label>
                                    <input type="number" class="form-control" id="n_aktif_dom" name="n_aktif_dom" placeholder="Masukan Jumlah" value="<?= set_value('n_aktif_dom'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('n_aktif_dom'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="rt_dom">Pelanggan Rumah Tangga :</label>
                                    <input type="number" class="form-control" id="rt_dom" name="rt_dom" placeholder="Masukan Jumlah" value="<?= set_value('rt_dom'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('rt_dom'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="niaga_dom">Pelanggan Niaga Domestik :</label>
                                    <input type="number" class="form-control" id="niaga_dom" name="niaga_dom" placeholder="Masukan Jumlah" value="<?= set_value('niaga_dom'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('niaga_dom'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="sl_kom_dom">Pelanggan Komunal :</label>
                                    <input type="number" class="form-control" id="sl_kom_dom" name="sl_kom_dom" placeholder="Masukan Jumlah" value="<?= set_value('sl_kom_dom'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('sl_kom_dom'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="unit_kom_dom">Unit Komunal :</label>
                                    <input type="number" class="form-control" id="unit_kom_dom" name="unit_kom_dom" placeholder="Masukan Jumlah" value="<?= set_value('unit_kom_dom'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('unit_kom_dom'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="sl_hu_dom">Pelanggan HU/TA :</label>
                                    <input type="number" class="form-control" id="sl_hu_dom" name="sl_hu_dom" placeholder="Masukan Jumlah" value="<?= set_value('sl_hu_dom'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('sl_hu_dom'); ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="n_aktif_n_dom">Pelanggan Non Aktif Non Domestik :</label>
                                    <input type="number" class="form-control" id="n_aktif_n_dom" name="n_aktif_n_dom" placeholder="Masukan Jumlah" value="<?= set_value('n_aktif_n_dom'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('n_aktif_n_dom'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="sosial_n_dom">Pelanggan Sosial :</label>
                                    <input type="number" class="form-control" id="sosial_n_dom" name="sosial_n_dom" placeholder="Masukan Jumlah" value="<?= set_value('sosial_n_dom'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('sosial_n_dom'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="niaga_n_dom">Pelanggan Niaga Non Domestik :</label>
                                    <input type="number" class="form-control" id="niaga_n_dom" name="niaga_n_dom" placeholder="Masukan Jumlah" value="<?= set_value('niaga_n_dom'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('niaga_n_dom'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="ind_n_dom">Pelanggan Industri Non Domestik :</label>
                                    <input type="number" class="form-control" id="ind_n_dom" name="ind_n_dom" placeholder="Masukan Jumlah" value="<?= set_value('ind_n_dom'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('ind_n_dom'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="inst_n_dom">Pelanggan Instansi Non Domestik :</label>
                                    <input type="number" class="form-control" id="inst_n_dom" name="inst_n_dom" placeholder="Masukan Jumlah" value="<?= set_value('inst_n_dom'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('inst_n_dom'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="k2_n_dom">Pelanggan Khusus :</label>
                                    <input type="number" class="form-control" id="k2_n_dom" name="k2_n_dom" placeholder="Masukan Jumlah" value="<?= set_value('k2_n_dom'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('k2_n_dom'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="lain_n_dom">Pelanggan Lainnya :</label>
                                    <input type="number" class="form-control" id="lain_n_dom" name="lain_n_dom" placeholder="Masukan Jumlah" value="<?= set_value('lain_n_dom'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('lain_n_dom'); ?></small>
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