<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('langganan/tambah_sr'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="<?= base_url('langganan/tambah_sr/input_sr') ?>" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tgl_sr">Pilih Bulan dan Tahun Penambaham SR:</label>
                                <input type="date" class="form-control" id="tgl_sr" name="tgl_sr" value="<?= set_value('tgl_sr'); ?>">
                                <small class="form-text text-danger"><?= form_error('tgl_sr'); ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <label for="id_sb_mag">Nama UPK / Wilayah:</label>
                        </div>
                    </div>
                    <div class="form-check mb-2">
                        <div class="row">
                            <?php

                            $bagian_chunks = array_chunk($bagian, ceil(count($bagian) / 6));
                            foreach ($bagian_chunks as $chunk) : ?>
                                <div class="col-md-2">
                                    <?php foreach ($chunk as $jenis) : ?>
                                        <div class="form-group">
                                            <input type="checkbox" name="id_bagian[]" value="<?= $jenis->id_bagian; ?>">
                                            <?= strtoupper($jenis->nama_bagian); ?>
                                            <small class="form-text text-danger"><?= form_error('id_bagian[]'); ?></small>
                                            <input type="number" name="jumlah_sr[<?= $jenis->id_bagian; ?>]" class="form-control mb-2" placeholder="Jumlah">
                                            <small class="form-text text-danger"><?= form_error('jumlah_sr[]'); ?></small>
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