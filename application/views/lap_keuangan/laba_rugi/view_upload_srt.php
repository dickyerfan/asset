<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('lap_keuangan/penghasilan_komp_lain'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="jenis_srt">Jenis :</label>
                                <input type="text" class="form-control" id="jenis_srt" name="jenis_srt" placeholder="Masukan Uraian" value="<?= set_value('jenis_srt'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('jenis_srt'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="nama_srt">Nama / Uraian :</label>
                                <input type="text" class="form-control" id="nama_srt" name="nama_srt" placeholder="Masukan Uraian" value="<?= set_value('nama_srt'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('nama_srt'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tgl_srt">Tahun :</label>
                                    <input type="number" class="form-control" id="tgl_srt" name="tgl_srt" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= set_value('tgl_srt'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tgl_srt'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_srt">Jumlah :</label>
                                    <input type="text" class="form-control" id="jumlah_srt" name="jumlah_srt" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_srt'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_srt'); ?></small>
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