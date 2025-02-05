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
                                <label for="id_bank">Nama Bank :</label>
                                <select name="id_bank" id="id_bank" class="form-control select2">
                                    <option value="">Pilih Kelompok Tarif</option>
                                    <?php foreach ($bank as $row) :  ?>
                                        <option value="<?= $row->id_bank ?>"><?= $row->nama_bank; ?></option>
                                    <?php endforeach;  ?>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('id_bagian'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tgl_bank">Tanggal Bank :</label>
                                    <input type="date" class="form-control" id="tgl_bank" name="tgl_bank" placeholder="Masukan Tanggal Piutang" value="<?= set_value('tgl_bank'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tgl_bank'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah">Jumlah :</label>
                                    <input type="text" class="form-control" id="jumlah" name="jumlah" placeholder="Masukan Jumlah Saldo Awal" value="<?= set_value('jumlah'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah'); ?></small>
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