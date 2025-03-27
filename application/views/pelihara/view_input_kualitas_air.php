<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('pelihara/kualitas_air'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="tahun_ka">Pilih Bulan dan Tahun Kualitas Air:</label>
                                <input type="date" class="form-control" id="tahun_ka" name="tahun_ka" value="<?= set_value('tahun_ka'); ?>">
                                <small class="form-text text-danger"><?= form_error('tahun_ka'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="parameter">Nama Parameter :</label>
                                <select name="parameter" id="parameter" class="form-control select2">
                                    <option value="">Pilih Parameter</option>
                                    <option value="FISIK">FISIK</option>
                                    <option value="MIKROBIOLOGI">MIKROBIOLOGI</option>
                                    <option value="SISA CHLOR">SISA CHLOR</option>
                                    <option value="KIMIA WAJIB">KIMIA WAJIB</option>
                                    <option value="KIMIA TAMBAHAN">KIMIA TAMBAHAN</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('parameter'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_sample_int">Jumlah Sample Internal :</label>
                                    <input type="number" class="form-control" id="jumlah_sample_int" name="jumlah_sample_int" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_sample_int'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_sample_int'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_sample_eks">Jumlah Sample Eksternal :</label>
                                    <input type="number" class="form-control" id="jumlah_sample_eks" name="jumlah_sample_eks" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_sample_eks'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_sample_eks'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_terambil">Jumlah Terambil :</label>
                                    <input type="number" class="form-control" id="jumlah_terambil" name="jumlah_terambil" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_terambil'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_terambil'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_sample_oke_ya">Jumlah Sample Memenuhi syarat :</label>
                                    <input type="number" class="form-control" id="jumlah_sample_oke_ya" name="jumlah_sample_oke_ya" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_sample_oke_ya'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_sample_oke_ya'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="jumlah_sample_oke_tidak">Jumlah Sample Tidak Memenuhi syarat :</label>
                                    <input type="number" class="form-control" id="jumlah_sample_oke_tidak" name="jumlah_sample_oke_tidak" placeholder="Masukan Jumlah" value="<?= set_value('jumlah_sample_oke_tidak'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('jumlah_sample_oke_tidak'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tempat_uji">Tempat Uji :</label>
                                    <!-- <input type="text" class="form-control" id="tempat_uji" name="tempat_uji" placeholder="Masukan Jumlah" value="<?= set_value('tempat_uji'); ?>"> -->
                                    <select name="tempat_uji" id="tempat_uji" class="form-control select2">
                                        <option value="">Pilih Tempat Uji</option>
                                        <option value="Lab. Kes. Bondowoso">Lab. Kes. Bondowoso</option>
                                        <option value="AMDK Bondowoso">AMDK Bondowoso</option>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('tempat_uji'); ?></small>
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