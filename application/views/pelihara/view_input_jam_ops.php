<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('pelihara/jam_ops'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="<?= base_url('pelihara/jam_ops/input_jam_ops') ?>" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tgl_jam_ops">Pilih Bulan dan Tahun Jam Ops:</label>
                                <input type="date" class="form-control" id="tgl_jam_ops" name="tgl_jam_ops" value="<?= set_value('tgl_jam_ops'); ?>">
                                <small class="form-text text-danger"><?= form_error('tgl_jam_ops'); ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <label for="id_sb_mag">Nama Sumber:</label>
                        </div>
                    </div>
                    <div class="form-check mb-2">
                        <div class="row">
                            <?php
                            // Bagi array sb_mag menjadi dua sb_mag
                            $sb_mag_chunks = array_chunk($sb_mag, ceil(count($sb_mag) / 6));
                            foreach ($sb_mag_chunks as $chunk) : ?>
                                <div class="col-md-2">
                                    <?php foreach ($chunk as $jenis) : ?>
                                        <div class="form-group">
                                            <input type="checkbox" name="id_sb_mag[]" value="<?= $jenis->id_sb_mag; ?>">
                                            <?= strtoupper($jenis->nama_sb_mag); ?>
                                            <small class="form-text text-danger"><?= form_error('id_sb_mag[]'); ?></small>
                                            <input type="number" name="jumlah_jam_ops[<?= $jenis->id_sb_mag; ?>]" class="form-control mb-2" placeholder="Jumlah">
                                            <small class="form-text text-danger"><?= form_error('jumlah_jam_ops[]'); ?></small>
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