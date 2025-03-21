<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('pelihara/water_meter'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <!-- <div class="card-body">
                <form class="user" action="<?= base_url('pelihara/water_meter/upload') ?>" method="POST">
                    <div class="row justify-content-center">
                        <div class="form-group">
                            <label for="tgl_tm">Tanggal Tera Meter:</label>
                            <input type="date" class="form-control" id="tgl_tm" name="tgl_tm" value="<?= set_value('tgl_tm'); ?>">
                            <small class="form-text text-danger"><?= form_error('tgl_tm'); ?></small>
                        </div>
                    </div>
                    <label for="id_bagian">UPK:</label>
                    <div class="form-check mb-2">
                        <div class="row">
                            <div class="col-md-6">
                                <?php foreach ($bagian as $jenis) : ?>
                                    <div class="form-group">
                                        <input type="checkbox" name="id_bagian[]" value="<?= $jenis->id_bagian; ?>">
                                        <?= strtoupper($jenis->nama_bagian); ?>
                                        <small class="form-text text-danger"><?= form_error('id_bagian[]'); ?></small>
                                        <input type="number" name="jumlah_tm[<?= $jenis->id_bagian; ?>]" class="form-control mb-2" placeholder="Jumlah">
                                        <small class="form-text text-danger"><?= form_error('jumlah_tm[]'); ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-12 text-center">
                            <button class="neumorphic-button mt-2" name="tambah" type="submit">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div> -->
            <div class="card-body">
                <form class="user" action="<?= base_url('pelihara/water_meter/input_tm') ?>" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tgl_tm">Pilih Bulan dan Tahun Tera Meter:</label>
                                <input type="date" class="form-control" id="tgl_tm" name="tgl_tm" value="<?= set_value('tgl_tm'); ?>">
                                <small class="form-text text-danger"><?= form_error('tgl_tm'); ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <label for="id_bagian">Nama UPK:</label>
                        </div>
                    </div>
                    <div class="form-check mb-2">
                        <div class="row">
                            <?php
                            // Bagi array bagian menjadi dua bagian
                            $bagian_chunks = array_chunk($bagian, ceil(count($bagian) / 3));
                            foreach ($bagian_chunks as $chunk) : ?>
                                <div class="col-md-4">
                                    <?php foreach ($chunk as $jenis) : ?>
                                        <div class="form-group">
                                            <input type="checkbox" name="id_bagian[]" value="<?= $jenis->id_bagian; ?>">
                                            <?= strtoupper($jenis->nama_bagian); ?>
                                            <small class="form-text text-danger"><?= form_error('id_bagian[]'); ?></small>
                                            <input type="number" name="jumlah_tm[<?= $jenis->id_bagian; ?>]" class="form-control mb-2" placeholder="Jumlah">
                                            <small class="form-text text-danger"><?= form_error('jumlah_tm[]'); ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-12 text-center">
                            <button class="neumorphic-button mt-2" name="tambah" type="submit">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>
</div>