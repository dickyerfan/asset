<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('lap_keuangan/beban_pajak'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="jenis_bpk">Jenis :</label>
                                <select name="jenis_bpk" id="jenis_bpk" class="form-control select2">
                                    <option value="">Pilih Jenis</option>
                                    <option value="Koreksi Fiskal Positif">Koreksi Fiskal Positif</option>
                                    <option value="Koreksi Fiskal Negatif">Koreksi Fiskal Negatif</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('jenis_bpk'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="nama_bpk">Nama / Uraian :</label>
                                <select name="nama_bpk" id="nama_bpk" class="form-control select2">
                                    <option value="">Pilih Uraian</option>
                                    <option value=" Beban Rapat dan Tamu"> Beban Rapat dan Tamu</option>
                                    <option value=" Beban Representasi"> Beban Representasi</option>
                                    <option value="Beban Penyisihan Piutang">Beban Penyisihan Piutang</option>
                                    <option value="Pendapatan bunga giro dan Deposito">Pendapatan bunga giro dan Deposito</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('nama_bpk'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tgl_bpk">Tahun Beban Lain-lain :</label>
                                    <input type="number" class="form-control" id="tgl_bpk" name="tgl_bpk" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= set_value('tgl_bpk'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tgl_bpk'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_bpk">Jumlah :</label>
                                    <input type="text" class="form-control" id="jumlah_bpk" name="jumlah_bpk" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_bpk'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_bpk'); ?></small>
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