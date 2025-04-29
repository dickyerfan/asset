<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('langganan/efek_tagih'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="uraian">Uraian :</label>
                                    <select name="uraian" id="uraian" class="form-control select2">
                                        <option value="">Pilih Sisa Piutang</option>
                                        <option value="1 Bulan">1 Bulan</option>
                                        <option value="2 Bulan">2 Bulan</option>
                                        <option value="3 Bulan">3 Bulan</option>
                                        <option value="4 Bulan - 1 Tahun">4 Bulan - 1 Tahun</option>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('uraian'); ?></small>
                                </div>
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