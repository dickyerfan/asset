<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('lap_keuangan/penjelasan'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="id_kas">Nama Kas :</label>
                                <select name="id_kas" id="id_kas" class="form-control select2">
                                    <option value="">Pilih kas</option>
                                    <?php foreach ($kas as $row) :  ?>
                                        <option value="<?= $row->id_kas ?>"><?= $row->nama_kas; ?></option>
                                    <?php endforeach;  ?>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('id_bagian'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tgl_kas">Tanggal Kas :</label>
                                    <input type="date" class="form-control" id="tgl_kas" name="tgl_kas" placeholder="Masukan Tanggal Piutang" value="<?= set_value('tgl_kas'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tgl_kas'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_kas">Jumlah :</label>
                                    <input type="text" class="form-control" id="jumlah_kas" name="jumlah_kas" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_kas'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_kas'); ?></small>
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