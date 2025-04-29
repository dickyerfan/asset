<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('langganan/rincian_pendapatan'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
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
                                <label for="id_kel_tarif">Kelompok Tarif :</label>
                                <select name="id_kel_tarif" id="id_kel_tarif" class="form-control select2">
                                    <option value="">Pilih Kelompok Tarif</option>
                                    <?php foreach ($tarif_dom as $row) :  ?>
                                        <option value="<?= $row->id_kel_tarif; ?>"><?= $row->kel_tarif_ket; ?></option>
                                    <?php endforeach;  ?>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('id_kel_tarif'); ?></small>
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
                                    <label for="jumlah_sr">Jumlah SR :</label>
                                    <input type="number" class="form-control" id="jumlah_sr" name="jumlah_sr" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_sr'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_sr'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="volume">Volume :</label>
                                    <input type="number" class="form-control" id="volume" name="volume" placeholder="Masukan Jumlah" value="<?= set_value('volume'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('volume'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="rupiah">Rupiah :</label>
                                    <input type="number" class="form-control" id="rupiah" name="rupiah" placeholder="Masukan Jumlah" value="<?= set_value('rupiah'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('rupiah'); ?></small>
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