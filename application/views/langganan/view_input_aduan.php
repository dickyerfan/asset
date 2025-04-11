<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('langganan/data_pengaduan'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="<?= base_url('langganan/data_pengaduan/input_aduan') ?>" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tgl_aduan">Pilih Bulan dan Tahun Penambaham SR:</label>
                                <input type="date" class="form-control" id="tgl_aduan" name="tgl_aduan" value="<?= set_value('tgl_aduan'); ?>">
                                <small class="form-text text-danger"><?= form_error('tgl_aduan'); ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <label class="fw-bold">Pilih Jenis Aduan dan Isikan Jumlahnya:</label>
                            <small class="form-text text-danger"><?= form_error('jenis_aduan[]'); ?></small>

                            <?php
                            $aduan_list = ['Teknis', 'Pelayanan', 'Rekening Air'];
                            foreach ($aduan_list as $aduan) :
                                $aduan_slug = str_replace(' ', '_', strtolower($aduan)); // jadi teknis, pelayanan, rekening_air
                            ?>
                                <div class="card mb-3 shadow-sm">
                                    <div class="card-body">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="jenis_aduan[]" value="<?= $aduan_slug ?>" id="aduan_<?= $aduan_slug ?>" <?= set_checkbox('jenis_aduan[]', $aduan_slug); ?>>
                                            <label class="form-check-label fw-bold" for="aduan_<?= $aduan_slug ?>"><?= $aduan ?></label>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <input type="number" class="form-control" name="jumlah_aduan[<?= $aduan_slug ?>]" placeholder="Jumlah Aduan" value="<?= set_value('jumlah_aduan[' . $aduan_slug . ']'); ?>">
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <input type="number" class="form-control" name="jumlah_aduan_ya[<?= $aduan_slug ?>]" placeholder="Jumlah Terselesaikan" value="<?= set_value('jumlah_aduan_ya[' . $aduan_slug . ']'); ?>">
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <input type="number" class="form-control" name="jumlah_aduan_tidak[<?= $aduan_slug ?>]" placeholder="Jumlah Belum Selesai" value="<?= set_value('jumlah_aduan_tidak[' . $aduan_slug . ']'); ?>">
                                            </div>
                                        </div>
                                    </div>
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