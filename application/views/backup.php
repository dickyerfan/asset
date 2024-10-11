<section class="content">
    <div class="container-fluid mt-2">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <h3 class="card-title"><?= $title ?></h3>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <h4 class="card-title">Back Up</h4><br><br>
                        <a href="<?= base_url('backup/backup') ?>" class="btn btn-primary"><i class="fas fa-download"></i> Klik untuk Backup database</a>
                    </div>

                    <div class="col-md-6">
                        <h4 class="card-title">Restore</h4><br><br>
                        <form action="<?= base_url('backup/restore') ?>" method="post" enctype="multipart/form-data">
                            <input type="file" name="datafile" title="Pilih File">
                            <input type="submit" value="Klik untuk restore" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>