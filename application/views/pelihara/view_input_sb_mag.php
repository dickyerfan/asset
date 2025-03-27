<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('pelihara/sb_mag'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="id_bagian">Nama UPK :</label>
                                <select name="id_bagian" id="id_bagian" class="form-control select2">
                                    <option value="">Pilih UPK</option>
                                    <?php foreach ($bagian as $row) :  ?>
                                        <option value="<?= $row->id_bagian; ?>"><?= $row->nama_bagian; ?></option>
                                    <?php endforeach;  ?>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('id_bagian'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="nama_sb_mag">Nama Sumber :</label>
                                    <input type="text" class="form-control" id="nama_sb_mag" name="nama_sb_mag" placeholder="Masukan Nama Sumber" value="<?= set_value('nama_sb_mag'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('nama_sb_mag'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="lokasi_sb_mag">Lokasi Sumber :</label>
                                    <input type="text" class="form-control" id="lokasi_sb_mag" name="lokasi_sb_mag" placeholder="Masukan lokasi" value="<?= set_value('lokasi_sb_mag'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('lokasi_sb_mag'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="mulai_ops">Mulai Operasional:</label>
                                    <input type="date" class="form-control" id="mulai_ops" name="mulai_ops" placeholder="Masukan Tanggal" value="<?= set_value('mulai_ops'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('mulai_ops'); ?></small>
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