<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('lap_keuangan/peny_piutang'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="id_kel_tarif">Kelompok Tarif :</label>
                                <select name="id_kel_tarif" id="id_kel_tarif" class="form-control select2">
                                    <option value="">Pilih Kelompok Tarif</option>
                                    <?php foreach ($kel_tarif as $row) :  ?>
                                        <option value="<?= $row->id_kel_tarif ?>"><?= $row->kel_tarif_ket; ?></option>
                                    <?php endforeach;  ?>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('id_bagian'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tgl_piutang">Tanggal Piutang :</label>
                                    <input type="date" class="form-control" id="tgl_piutang" name="tgl_piutang" placeholder="Masukan Tanggal Piutang" value="<?= set_value('tgl_piutang'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tgl_piutang'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="saldo_awal">Saldo Awal :</label>
                                    <input type="text" class="form-control" id="saldo_awal" name="saldo_awal" placeholder="Masukan Jumlah Saldo Awal" value="<?= set_value('saldo_awal'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('saldo_awal'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tambah">Penambahan :</label>
                                    <input type="text" class="form-control" id="tambah" name="tambah" placeholder="Masukan Jumlah Penambahan" value="<?= set_value('tambah'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tambah'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="kurang">Pengurangan :</label>
                                    <input type="text" class="form-control" id="kurang" name="kurang" placeholder="Masukan Jumlah Pengurangan" value="<?= set_value('kurang'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('kurang'); ?></small>
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