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

                <form class="user" action="<?= site_url('langganan/tambah_sr/update_sr') ?>" method="post">
                    <input type="hidden" name="id_bagian" value="<?= $id_bagian; ?>">
                    <input type="hidden" name="tgl_sr" value="<?= $tgl_sr; ?>">
                    <input type="hidden" name="field" value="jumlah_sr">
                    <div class="row">
                        <div class="col-md-2">
                            <input type="number" name="value" class="form-control mb-2" required>
                        </div>
                    </div>
                    <button class="neumorphic-button mt-2" type="submit">Update</button>
                </form>
            </div>
        </div>
    </div>
</section>
</div>