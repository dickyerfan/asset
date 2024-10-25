<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('asset_kurang'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>

            <div class="card-body">
                <form class="user" action="" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal Asset :</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" placeholder="Masukan Tanggal Pesan" value="<?= set_value('tanggal'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tanggal'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="nama_asset">Nama Asset :</label>
                                    <input type="text" class="form-control" id="nama_asset" name="nama_asset" placeholder="Masukan Nama Asset " value="<?= set_value('nama_asset'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('nama_asset'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah">Jumlah Asset :</label>
                                    <input type="text" class="form-control" id="jumlah" name="jumlah" placeholder="Masukan Jumlah Asset " value="<?= set_value('jumlah'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah'); ?></small>
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
                                <label for="kode_sr">Kode SR : di isi jika kode <span class="text-primary">"meter air yang terpasang"</span></label>
                                <select name="kode_sr" id="kode_sr" class="form-control select2">
                                    <option value="">Pilih Jenis Meter Air</option>
                                    <option value="1">Pemasangan SR Baru</option>
                                    <option value="2">Penggantian WM</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('kode_sr'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="id_bagian">Bagian/UPK :</label>
                                <select name="id_bagian" id="id_bagian" class="form-control select2">
                                    <option value="">Pilih Bagian/UPK</option>
                                    <?php foreach ($bagian as $row) :  ?>
                                        <option value="<?= $row->id_bagian ?>"><?= $row->nama_bagian; ?></option>
                                    <?php endforeach;  ?>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('id_bagian'); ?></small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
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
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="umur">Umur Asset : <span class="text-primary">"Untuk Asset Tanah di isi angka 0"</span></label>
                                    <input type="text" class="form-control" id="umur" name="umur" placeholder="Masukan Umur Asset " value="<?= set_value('umur'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('umur'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="persen_susut">Persentase Penyusutan : <span class="text-primary">"Untuk Asset Tanah di isi angka 0"</span></label>
                                    <input type="text" class="form-control" id="persen_susut" name="persen_susut" placeholder="Masukan Persentase Penyusutan " value="<?= set_value('persen_susut'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('persen_susut'); ?></small>
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