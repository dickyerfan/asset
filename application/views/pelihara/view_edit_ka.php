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
                <form action="<?= base_url('pelihara/kualitas_air/update_ka') ?>" method="post">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <input type="hidden" name="id_ek_ka" value="<?= $kualitas_air->id_ek_ka; ?>">
                                <label for="parameter">Nama UPK :</label>
                                <select name="parameter" class="form-control select2">
                                    <option value="FISIK" <?= $kualitas_air->parameter == 'FISIK' ? 'selected' : '' ?>>FISIK</option>
                                    <option value="MIKROBIOLOGI" <?= $kualitas_air->parameter == 'MIKROBIOLOGI' ? 'selected' : '' ?>>MIKROBIOLOGI</option>
                                    <option value="SISA CHLOR" <?= $kualitas_air->parameter == 'SISA CHLOR' ? 'selected' : '' ?>>SISA CHLOR</option>
                                    <option value="KIMIA WAJIB" <?= $kualitas_air->parameter == 'KIMIA WAJIB' ? 'selected' : '' ?>>KIMIA WAJIB</option>
                                    <option value="KIMIA TAMBAHAN" <?= $kualitas_air->parameter == 'KIMIA TAMBAHAN' ? 'selected' : '' ?>>KIMIA TAMBAHAN</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('id_bagian'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="tahun_ka">Tahun :</label>
                                <input type="date" class="form-control" id="tahun_ka" name="tahun_ka" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= $kualitas_air->tahun_ka; ?>">
                                <small class=" form-text text-danger pl-3"><?= form_error('tahun_ka'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="jumlah_sample_int">Jumlah Sample Internal :</label>
                                <input type="number" class="form-control" id="jumlah_sample_int" name="jumlah_sample_int" placeholder="Masukan Jumlah" value="<?= $kualitas_air->jumlah_sample_int; ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('jumlah_sample_int'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="jumlah_sample_eks">Jumlah Sample Eksternal :</label>
                                <input type="number" class="form-control" id="jumlah_sample_eks" name="jumlah_sample_eks" placeholder="Masukan Jumlah" value="<?= $kualitas_air->jumlah_sample_eks; ?>">
                                <small class=" form-text text-danger pl-3"><?= form_error('jumlah_sample_eks'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="jumlah_terambil">Jumlah Terambil :</label>
                                <input type="number" class="form-control" id="jumlah_terambil" name="jumlah_terambil" placeholder="Masukan Jumlah" value="<?= $kualitas_air->jumlah_terambil; ?>">
                                <small class=" form-text text-danger pl-3"><?= form_error('jumlah_terambil'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="jumlah_sample_oke_ya">Jumlah Sample Memenuhi syarat :</label>
                                <input type="number" class="form-control" id="jumlah_sample_oke_ya" name="jumlah_sample_oke_ya" placeholder="Masukan Jumlah" value="<?= $kualitas_air->jumlah_sample_oke_ya; ?>">
                                <small class=" form-text text-danger pl-3"><?= form_error('jumlah_sample_oke_ya'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="jumlah_sample_oke_tidak">Jumlah Sample Tidak Memenuhi syarat :</label>
                                <input type="number" class="form-control" id="jumlah_sample_oke_tidak" name="jumlah_sample_oke_tidak" placeholder="Masukan Jumlah" value="<?= $kualitas_air->jumlah_sample_oke_tidak; ?>">
                                <small class=" form-text text-danger pl-3"><?= form_error('jumlah_sample_oke_tidak'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="tempat_uji">Tempat Uji :</label>
                                <select name="tempat_uji" id="tempat_uji" class="form-control select2">
                                    <option value="">Pilih Tempat Uji</option>
                                    <option value="Lab. Kes. Bondowoso" <?= $kualitas_air->tempat_uji == 'Lab. Kes. Bondowoso' ? 'selected' : '' ?>>Lab. Kes. Bondowoso</option>
                                    <option value="AMDK Bondowoso" <?= $kualitas_air->tempat_uji == 'AMDK Bondowoso' ? 'selected' : '' ?>>AMDK Bondowoso</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('tempat_uji'); ?></small>
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