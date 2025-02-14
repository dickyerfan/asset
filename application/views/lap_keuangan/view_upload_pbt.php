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
                                <label for="nama_pbt">Nama Bank :</label>
                                <select name="nama_pbt" id="nama_pbt" class="form-control select2">
                                    <option value="">Pilih Uraian</option>
                                    <option value="Pendapatan Galon">Pendapatan Galon</option>
                                    <option value="Pendapatan Gelas 220 ml">Pendapatan Gelas 220 ml</option>
                                    <option value="Pendapatan Gelas 300 ml">Pendapatan Gelas 300 ml</option>
                                    <option value="Pendapatan Gelas 500 ml">Pendapatan Gelas 500 ml</option>
                                    <option value="Pendapatan Gelas 600 ml">Pendapatan Gelas 600 ml</option>
                                    <option value="Pendapatan Gelas 1500 ml">Pendapatan Gelas 1500 ml</option>
                                    <option value="Pendapatan Non Air">Pendapatan Non Air</option>
                                    <option value="Piutang Pegawai">Piutang Pegawai</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('nama_pbt'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tgl_pbt">Tanggal Penerimaan Belum Diterima :</label>
                                    <!-- <input type="date" class="form-control" id="tgl_pbt" name="tgl_pbt" placeholder="Masukan Tanggal Piutang" value="<?= set_value('tgl_pbt'); ?>"> -->
                                    <input type="number" class="form-control" id="tgl_pbt" name="tgl_pbt" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= set_value('tgl_pbt'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tgl_pbt'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_pbt">Jumlah :</label>
                                    <input type="text" class="form-control" id="jumlah_pbt" name="jumlah_pbt" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_pbt'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_pbt'); ?></small>
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