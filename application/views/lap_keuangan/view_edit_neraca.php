<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('lap_keuangan/neraca'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form action="<?= base_url('lap_keuangan/neraca/update_audited?tahun=' . $tahun); ?>" method="post">
                    <input type="hidden" name="id_neraca" value="<?= $neraca->id_neraca; ?>">
                    <input type="hidden" name="tahun" value="<?= $tahun; ?>">
                    <div class="form-group">
                        <label for="nilai_neraca_audited">Nilai <?= $neraca->akun; ?> Audited</label>
                        <input type="number" class="form-control" name="nilai_neraca_audited" id="nilai_neraca_audited" value="<?= $neraca->nilai_neraca_audited; ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= base_url('lap_keuangan/neraca'); ?>" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</section>
</div>