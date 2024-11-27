<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <!-- <a href="<?= base_url('penyusutan'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a> -->
            </div>
            <div class="card-body">
                <form class="user" action="" method="POST">
                    <!-- <form class="user" action="<?= base_url('penyusutan/update_persediaan') ?>" method="POST"> -->
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <div class="form-group">
                                    <input type="hidden" class="form-control" id="id_asset" name="id_asset" value="<?= $edit_persediaan->id_asset; ?>">
                                    <label for="nama_asset">Nama Asset :</label>
                                    <input type="text" class="form-control" id="nama_asset" name="nama_asset" placeholder="Masukan Nama Asset " value="<?= $edit_persediaan->nama_asset; ?>" disabled>
                                    <small class="form-text text-danger pl-3"><?= form_error('nama_asset'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="rupiah">Rupiah :</label>
                                    <input type="text" class="form-control" id="rupiah" name="rupiah" placeholder="Masukan Jumlah Rupiah" value="<?= $edit_persediaan->rupiah; ?>" disabled>
                                    <small class="form-text text-danger pl-3"><?= form_error('rupiah'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="umur">Umur Asset : <span class="text-primary">"Di ganti dengan nilai 0"</span></label>
                                    <input type="text" class="form-control" id="umur" name="umur" placeholder="Masukan Umur Asset " value="<?= $edit_persediaan->umur; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('umur'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="persen_susut">Persentase Penyusutan : <span class="text-primary">"Di ganti dengan nilai 0"</span></label>
                                    <input type="text" class="form-control" id="persen_susut" name="persen_susut" placeholder="Masukan Persentase Penyusutan " value="<?= $edit_persediaan->persen_susut; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('persen_susut'); ?></small>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <div class="form-group">
                                    <label for="status_update">Status Update : <span class="text-primary"></span></label>
                                    <input type="text" class="form-control" id="status_update" name="status_update" placeholder="Masukan Status Update " value="<?= $edit_persediaan->status_update; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('status_update'); ?></small>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-12 text-center">
                            <button class=" neumorphic-button mt-2" name="tambah" type="submit"><i class="fas fa-edit"></i> Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
</div>