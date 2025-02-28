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
                                <label for="jenis_bsb">Jenis :</label>
                                <input type="text" class="form-control" id="jenis_bsb" name="jenis_bsb" placeholder="Masukan Uraian" value="<?= set_value('jenis_bsb'); ?>">
                                <!-- <select name="jenis_bsb" id="jenis_bsb" class="form-control select2">
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
                                </select> -->
                                <small class="form-text text-danger pl-3"><?= form_error('jenis_bsb'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="nama_bsb">Nama / Uraian :</label>
                                <input type="text" class="form-control" id="nama_bsb" name="nama_bsb" placeholder="Masukan Uraian" value="<?= set_value('nama_bsb'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('nama_bsb'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tgl_bsb">Tahun Beban (HPP) Sambungan Baru :</label>
                                    <input type="number" class="form-control" id="tgl_bsb" name="tgl_bsb" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= set_value('tgl_bsb'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tgl_bsb'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_bsb">Jumlah :</label>
                                    <input type="text" class="form-control" id="jumlah_bsb" name="jumlah_bsb" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_bsb'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_bsb'); ?></small>
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