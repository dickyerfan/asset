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
                                <label for="jenis_bop">Jenis :</label>
                                <!-- <input type="text" class="form-control" id="jenis_bop" name="jenis_bop" placeholder="Masukan Uraian" value="<?= set_value('jenis_bop'); ?>"> -->
                                <select name="jenis_bop" id="jenis_bop" class="form-control select2">
                                    <option value="">Pilih Jenis</option>
                                    <option value="Biaya Pegawai Sumber">Biaya Pegawai Sumber</option>
                                    <option value="Pemakaian Bahan Bakar/Pelumas Sumber">Pemakaian Bahan Bakar/Pelumas Sumber</option>
                                    <option value="Beban Pemakaian Bahan Pembantu Sumber">Beban Pemakaian Bahan Pembantu Sumber</option>
                                    <option value="Beban Listrik PLN Sumber">Beban Listrik PLN Sumber</option>
                                    <option value="Beban Pemeliharaan">Beban Pemeliharaan</option>
                                    <option value="Beban Air Baku">Beban Air Baku</option>
                                    <option value="Beban Penyusutan Instalasi Sumber">Beban Penyusutan Instalasi Sumber</option>
                                    <option value="Rupa-rupa Beban Operasi Sumber">Rupa-rupa Beban Operasi Sumber</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('jenis_bop'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="nama_bop">Nama / Uraian :</label>
                                <input type="text" class="form-control" id="nama_bop" name="nama_bop" placeholder="Masukan Uraian" value="<?= set_value('nama_bop'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('nama_bop'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tgl_bop">Tahun Beban Operasi :</label>
                                    <input type="number" class="form-control" id="tgl_bop" name="tgl_bop" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= set_value('tgl_bop'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tgl_bop'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_bop">Jumlah :</label>
                                    <input type="text" class="form-control" id="jumlah_bop" name="jumlah_bop" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_bop'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_bop'); ?></small>
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