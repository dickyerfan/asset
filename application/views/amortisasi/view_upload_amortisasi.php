<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('amortisasi'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>

            <div class="card-body">
                <form class="user" action="" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal Perolehan : <span class="text-primary">"Jika pengurangan amortisasi, tanggal di isi sama dengan tanggal perolehan"</span></label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" placeholder="Masukan Tanggal Perolehan" value="<?= set_value('tanggal'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tanggal'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="nama_amortisasi">Nama amortisasi :</label>
                                    <input type="text" class="form-control" id="nama_amortisasi" name="nama_amortisasi" placeholder="Masukan Nama amortisasi " value="<?= set_value('nama_amortisasi'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('nama_amortisasi'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="rupiah">Rupiah :</label>
                                    <input type="text" class="form-control" id="rupiah" name="rupiah" placeholder="Masukan Jumlah Rupiah" value="<?= set_value('rupiah'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('rupiah'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_no_per">No Perkiraan :</label>
                                <select name="id_no_per" id="id_no_per" class="form-control select2">
                                    <option value="">Pilih No Perkiraan</option>
                                    <?php foreach ($perkiraan as $row) :  ?>
                                        <option value="<?= $row->id; ?>,<?= $row->parent_id; ?>,<?= $row->grand_id; ?>,<?= $row->jenis_id; ?>"><?= $row->kode; ?> - <?= $row->name; ?></option>
                                    <?php endforeach;  ?>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('id_no_per'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="no_bukti_gd">No Bukti Gudang :</label>
                                    <input type="text" class="form-control" id="no_bukti_gd" name="no_bukti_gd" placeholder="Masukan No bukti Gudang " value="<?= set_value('no_bukti_gd'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('no_bukti_gd'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="no_bukti_vch">No Bukti Voucher :</label>
                                    <input type="text" class="form-control" id="no_bukti_vch" name="no_bukti_vch" placeholder="Masukan No Bukti Voucher " value="<?= set_value('no_bukti_vch'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('no_bukti_vch'); ?></small>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <div class="form-group">
                                    <label for="umur">Umur amortisasi : <span class="text-primary">"Untuk amortisasi Tanah di isi angka 0"</span></label>
                                    <input type="text" class="form-control" id="umur" name="umur" placeholder="Masukan Umur amortisasi " value="<?= set_value('umur'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('umur'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="persen_susut">Persentase Penyusutan : <span class="text-primary">"Untuk amortisasi Tanah di isi angka 0"</span></label>
                                    <input type="text" class="form-control" id="persen_susut" name="persen_susut" placeholder="Masukan Persentase Penyusutan " value="<?= set_value('persen_susut'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('persen_susut'); ?></small>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="status">Status amortisasi : </label>
                                    <select name="status" id="status" class="form-control select2">
                                        <option value="">Pilih Status amortisasi</option>
                                        <option value="1">Penambahan</option>
                                        <option value="2">Pengurangan</option>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('status'); ?></small>
                                </div>
                            </div>
                            <div id="tanggal-persediaan-group" class="form-group" style="display: none;">
                                <div class="form-group">
                                    <label for="tanggal_persediaan">Tanggal Persediaan :</label>
                                    <input type="date" class="form-control" id="tanggal_persediaan" name="tanggal_persediaan" placeholder="Masukan Tanggal_persediaan" value="<?= set_value('tanggal_persediaan'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tanggal_persediaan'); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-12 text-center">
                            <button class=" neumorphic-button mt-2" name="tambah" type="submit"><i class="fas fa-save"></i> Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
</div>