    <section class="content">
        <div class="container-fluid">
            <?= $this->session->flashdata('info'); ?>
            <?= $this->session->unset_userdata('info'); ?>
            <div class="card">
                <div class="card-header card-outline card-primary shadow">
                    <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                    <a href="<?= base_url('umum/kerjasama'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
                </div>
                <div class="card-body">
                    <form class="user" action="<?= base_url('umum/kerjasama/update_kerjasama') ?>" method="POST">
                        <div class="row justify-content-center">
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <input type="hidden" name="id_kerjasama" value="<?= $kerjasama->id_kerjasama; ?>">
                                    <label for="tahun_perjanjian">Tahun Kerjasama:</label>
                                    <input type="number" class="form-control" id="tahun_perjanjian" name="tahun_perjanjian" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= $kerjasama->tahun_perjanjian; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tahun_perjanjian'); ?></small>
                                </div>
                                <div class="form-group">
                                    <label for="no_perjanjian">No Perjanjian :</label>
                                    <input type="text" class="form-control" id="no_perjanjian" name="no_perjanjian" placeholder="Masukan No Perjanjian" value="<?= $kerjasama->no_perjanjian; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('no_perjanjian'); ?></small>
                                </div>
                                <div class="form-group">
                                    <label for="tentang_perjanjian">Tentang Perjanjian :</label>
                                    <input type="text" class="form-control" id="tentang_perjanjian" name="tentang_perjanjian" placeholder="Masukan Tentang Perjanjian" value="<?= $kerjasama->tentang_perjanjian; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tentang_perjanjian'); ?></small>

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