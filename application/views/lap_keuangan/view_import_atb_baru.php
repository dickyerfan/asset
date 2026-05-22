<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('lap_keuangan/asset_tetap_baru'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form action="<?= base_url('lap_keuangan/asset_tetap_baru/import_atb'); ?>" method="POST">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label for="tahun">Tahun :</label>
                            <input type="number" class="form-control" id="tahun" name="tahun" value="<?= set_value('tahun', date('Y')); ?>">
                            <small class="form-text text-danger pl-3"><?= form_error('tahun'); ?></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="data_excel">Paste Data Excel :</label>
                        <textarea class="form-control" id="data_excel" name="data_excel" rows="14" placeholder="Nama Asset Tidak Berwujud	Tanggal Perolehan	Harga Perolehan	Akm Amortisasi	Nilai Buku"><?= set_value('data_excel'); ?></textarea>
                        <small class="form-text text-danger pl-3"><?= form_error('data_excel'); ?></small>
                    </div>
                    <div class="alert alert-info">
                        Urutan kolom: Nama Asset Tidak Berwujud, Tanggal Perolehan, Harga Perolehan, Akm Amortisasi, Nilai Buku. Nilai buku disimpan sesuai Excel, tidak dihitung ulang.
                    </div>
                    <div class="text-center">
                        <button class="neumorphic-button mt-2" type="submit"><i class="fas fa-save"></i> Import Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
</div>
