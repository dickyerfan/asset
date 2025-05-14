    <section class="content">
        <div class="container-fluid">
            <?= $this->session->flashdata('info'); ?>
            <?= $this->session->unset_userdata('info'); ?>
            <div class="card">
                <div class="card-header card-outline card-primary shadow">
                    <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                    <a href="<?= base_url('keuangan/kejadian_penting'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
                </div>
                <div class="card-body">
                    <form class="user" action="<?= base_url('keuangan/kejadian_penting/update_kej_pen') ?>" method="POST">
                        <div class="row justify-content-center">
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <input type="hidden" name="id_kej_pen" value="<?= $kej_pen->id_kej_pen; ?>">
                                    <label for="tahun_kej_pen">Tahun Kejadian:</label>
                                    <input type="number" class="form-control" id="tahun_kej_pen" name="tahun_kej_pen" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= $kej_pen->tahun_kej_pen; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tahun_kej_pen'); ?></small>
                                </div>
                                <div class="form-group">Kejadian :</label>
                                    <input type="text" class="form-control" id="kejadian" name="kejadian" placeholder="Masukan No Perjanjian" value="<?= $kej_pen->kejadian; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('kejadian'); ?></small>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan:</label>
                                    <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Masukan Tentang Perjanjian" value="<?= $kej_pen->keterangan; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('keterangan'); ?></small>

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