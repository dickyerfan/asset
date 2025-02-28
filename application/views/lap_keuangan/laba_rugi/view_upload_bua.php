<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('lap_keuangan/beban'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="jenis_bua">Jenis :</label>
                                <!-- <input type="text" class="form-control" id="jenis_bua" name="jenis_bua" placeholder="Masukan Uraian" value="<?= set_value('jenis_bua'); ?>"> -->
                                <select name="jenis_bua" id="jenis_bua" class="form-control select2">
                                    <option value="">Pilih Jenis</option>
                                    <option value="Beban Pegawai Umum dan Administrasi">Beban Pegawai Umum dan Administrasi</option>
                                    <option value="Beban Kantor">Beban Kantor</option>
                                    <option value="Beban Hubungan Langganan">Beban Hubungan Langganan</option>
                                    <option value="Beban Penelitian dan Pengembangan">Beban Penelitian dan Pengembangan</option>
                                    <option value="Beban Keuangan">Beban Keuangan</option>
                                    <option value="Beban Pemeliharaan">Beban Pemeliharaan</option>
                                    <option value="Rupa-rupa Beban Umum dan Administrasi">Rupa-rupa Beban Umum dan Administrasi</option>
                                    <option value="Beban Penghapusan Piutang ">Beban Penghapusan Piutang </option>
                                    <option value="Beban Penyusutan dan Amortisasi">Beban Penyusutan dan Amortisasi</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('jenis_bua'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="nama_bua">Nama / Uraian :</label>
                                <input type="text" class="form-control" id="nama_bua" name="nama_bua" placeholder="Masukan Uraian" value="<?= set_value('nama_bua'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('nama_bua'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tgl_bua">Tahun Beban Operasi :</label>
                                    <input type="number" class="form-control" id="tgl_bua" name="tgl_bua" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= set_value('tgl_bua'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tgl_bua'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_bua">Jumlah :</label>
                                    <input type="text" class="form-control" id="jumlah_bua" name="jumlah_bua" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_bua'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_bua'); ?></small>
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