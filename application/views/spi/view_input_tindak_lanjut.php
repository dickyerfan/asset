<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('spi/tindak_lanjut'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>

            <div class="card-body">
                <?php if (validation_errors()) : ?>
                    <div class="alert alert-danger">
                        <?= validation_errors(); ?>
                    </div>
                <?php endif; ?>
                <?= $this->session->flashdata('info'); ?>

                <form action="" method="post">
                    <input type="hidden" name="id_tindak_lanjut" value="<?= isset($tindak_lanjut_data->id_tindak_lanjut) ? $tindak_lanjut_data->id_tindak_lanjut : ''; ?>">
                    <div class="form-row">
                        <div class="form-group col-md-4 ">
                            <label for="id_upk">UPK:</label>
                            <select name="id_upk" id="id_upk" class="form-control" required>
                                <option value=""> Pilih UPK </option>
                                <?php foreach ($all_upk as $upk) : ?>
                                    <option value="<?= $upk->id_bagian ?>" <?= (isset($tindak_lanjut_data->id_upk) && $tindak_lanjut_data->id_upk == $upk->id_bagian) ? 'selected' : set_select('id_upk', $upk->id_bagian); ?>>
                                        <?= $upk->nama_bagian ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- <div class="form-group col-md-4 ">
                            <label for="bulan">Bulan:</label>
                            <select name="bulan" id="bulan" class="form-control" required>
                                <option value=""> Pilih Bulan </option>
                                <?php for ($i = 1; $i <= 12; $i++) : ?>
                                    <option value="<?= $i ?>" <?= (isset($tindak_lanjut_data->bulan) && $tindak_lanjut_data->bulan == $i) ? 'selected' : set_select('bulan', $i); ?>>
                                        <?= $nama_bulan_lengkap[$i] ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div> -->
                        <div class="form-group col-md-4">
                            <label>Bulan</label>
                            <select name="bulan" class="form-control" required>
                                <?php
                                $bulan_lalu = (int)date('n', strtotime('-1 month'));
                                $nama_bulan = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                    4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                    7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                    10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ];
                                for ($i = 1; $i <= 12; $i++) :
                                ?>
                                    <option value="<?= $i ?>" <?= ($i == $bulan_lalu) ? 'selected' : '' ?>>
                                        <?= $nama_bulan[$i] ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4 ">
                            <label for="tahun">Tahun:</label>
                            <input type="number" name="tahun" id="tahun" class="form-control" value="<?= isset($tindak_lanjut_data->tahun) ? $tindak_lanjut_data->tahun : set_value('tahun', date('Y')); ?>" required min="2000" max="2100">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="temuan">Temuan:</label>
                        <textarea name="temuan" id="temuan" class="form-control" rows="4" required><?= isset($tindak_lanjut_data->temuan) ? $tindak_lanjut_data->temuan : set_value('temuan'); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="rekomendasi">Rekomendasi:</label>
                        <textarea name="rekomendasi" id="rekomendasi" class="form-control" rows="4"><?= isset($tindak_lanjut_data->rekomendasi) ? $tindak_lanjut_data->rekomendasi : set_value('rekomendasi'); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan (Opsional):</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3"><?= isset($tindak_lanjut_data->keterangan) ? $tindak_lanjut_data->keterangan : set_value('keterangan'); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                    <a href="<?= base_url('spi/tindak_lanjut'); ?>" class="btn btn-danger mt-3">Batal</a>
                </form>
            </div>
        </div>
    </div>
</section>
</div>