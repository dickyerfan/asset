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
                                <label for="jenis_btd">Jenis :</label>
                                <!-- <input type="text" class="form-control" id="jenis_btd" name="jenis_btd" placeholder="Masukan Uraian" value="<?= set_value('jenis_btd'); ?>"> -->
                                <select name="jenis_btd" id="jenis_btd" class="form-control select2">
                                    <option value="">Pilih Jenis</option>
                                    <option value="Beban Pegawai Transmisi dan Distribusi">Beban Pegawai Transmisi dan Distribusi</option>
                                    <option value="Pemakaian Bahan dan Perlengkapan">Pemakaian Bahan dan Perlengkapan</option>
                                    <option value="Beban Bahan Bakar">Beban Bahan Bakar</option>
                                    <option value="Beban Listrik PLN">Beban Listrik PLN</option>
                                    <option value="Beban Pemeliharaan">Beban Pemeliharaan</option>
                                    <option value="Beban Pemakaian Pipa Persil">Beban Pemakaian Pipa Persil</option>
                                    <option value="Beban Penyusutan Ins. Transmisi dan Distribusi">Beban Penyusutan Ins. Transmisi dan Distribusi</option>
                                    <option value="Rupa-rupa Beban Transmisi dan Distribusi">Rupa-rupa Beban Transmisi dan Distribusi</option>
                                    <option value="Beban Kemitraan">Beban Kemitraan</option>
                                    <option value="Beban Air Limbah">Beban Air Limbah</option>
                                    <option value="Beban Pokok Penjualan Sambungan Baru">Beban Pokok Penjualan Sambungan Baru</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('jenis_btd'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="nama_btd">Nama / Uraian :</label>
                                <input type="text" class="form-control" id="nama_btd" name="nama_btd" placeholder="Masukan Uraian" value="<?= set_value('nama_btd'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('nama_btd'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tgl_btd">Tahun Beban Operasi :</label>
                                    <input type="number" class="form-control" id="tgl_btd" name="tgl_btd" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= set_value('tgl_btd'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tgl_btd'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_btd">Jumlah :</label>
                                    <input type="text" class="form-control" id="jumlah_btd" name="jumlah_btd" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_btd'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_btd'); ?></small>
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