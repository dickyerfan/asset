    <section class="content">
        <div class="container-fluid">
            <?= $this->session->flashdata('info'); ?>
            <?= $this->session->unset_userdata('info'); ?>
            <div class="card">
                <div class="card-header card-outline card-primary shadow">
                    <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                    <a href="<?= base_url('keuangan/aspek_ops'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="row justify-content-center">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="tahun_aspek">Tahun Penilaian:</label>
                                        <input type="number" class="form-control" id="tahun_aspek" name="tahun_aspek" placeholder="Masukan Tahun" min="2022" max="2099" value="<?= set_value('tahun_aspek'); ?>" required>
                                        <small class="form-text text-danger pl-3"><?= form_error('tahun_aspek'); ?></small>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-md-12">
                                        <label class="fw-bold">Pilih Jenis penilaian dan hasilnya:</label>
                                        <!-- <small class="form-text text-danger"><?= form_error('penilaian[]'); ?></small> -->
                                        <?php
                                        $hasil_options = [
                                            'kualitas_air_distribusi' => [
                                                'label' => 'Kualitas Air Distribusi',
                                                'options' => [
                                                    'Memenuhi syarat air minum',
                                                    'Memenuhi syarat air bersih',
                                                    'Tidak memenuhi syarat',
                                                ],
                                            ],
                                            'kontinuitas_air' => [
                                                'label' => 'Kontinuitas Air',
                                                'options' => [
                                                    'semua pelanggan mendapat aliran air 24 jam',
                                                    'belum semua pelanggan mendapat aliran air 24 jam',
                                                ],
                                            ],
                                            'kecepatan_penyambungan_baru' => [
                                                'label' => 'Kecepatan Penyambungan Baru',
                                                'options' => [
                                                    '<= 6 hari kerja',
                                                    '> 6 hari kerja',
                                                ],
                                            ],
                                            'service_point' => [
                                                'label' => 'Service Point',
                                                'options' => [
                                                    'tersedia service point',
                                                    'tidak tersedia service point',
                                                ],
                                            ],
                                        ];
                                        ?>
                                        <?php foreach ($hasil_options as $slug => $data) : ?>
                                            <div class="mb-3">
                                                <label><?= $data['label'] ?></label>
                                                <input type="hidden" name="penilaian[]" value="<?= $slug ?>">
                                                <select name="hasil[<?= $slug ?>]" class="form-control" required>
                                                    <option value="">-- Pilih --</option>
                                                    <?php foreach ($data['options'] as $opt) : ?>
                                                        <option value="<?= htmlspecialchars($opt, ENT_QUOTES, 'UTF-8') ?>"><?= $opt ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        <?php endforeach; ?>


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