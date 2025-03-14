<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('lap_keuangan/lr_sak_ep?tahun=' . $tahun); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form action="<?= base_url('lap_keuangan/lr_sak_ep/update_audited?tahun=' . $tahun); ?>" method="post">
                    <input type="hidden" name="id_lr_sak_ep" value="<?= $lr_sak_ep->id_lr_sak_ep; ?>">
                    <input type="hidden" name="tahun" value="<?= $tahun; ?>">
                    <div class="form-group">
                        <label for="nilai_lr_sak_ep_audited">Nilai <?= $lr_sak_ep->akun; ?> Audited</label>
                        <input type="number" class="form-control" name="nilai_lr_sak_ep_audited" id="nilai_lr_sak_ep_audited" value="<?= $lr_sak_ep->nilai_lr_sak_ep_audited; ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= base_url('lap_keuangan/lr_sak_ep?tahun=' . $tahun); ?>" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</section>
</div>