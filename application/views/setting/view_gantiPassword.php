<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow mb-2">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?php
                            if ($this->session->userdata('bagian') == 'Administrator' || $this->session->userdata('bagian') == 'Keuangan' || $this->session->userdata('bagian') == 'Auditor') {
                                echo base_url('dashboard_asset');
                            } else if ($this->session->userdata('bagian') == 'Publik') {
                                echo base_url('dashboard_publik');
                            } else if ($this->session->userdata('bagian') == 'Umum') {
                                echo base_url('dashboard_umum');
                            }
                            ?>"><button class="float-end neumorphic-button"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="passLama">Password Lama:</label>
                                <input type="password" class="form-control" id="passLama" name="passLama" value="<?= set_value('passLama'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('passLama'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="passbaru">Password Baru:</label>
                                <input type="password" class="form-control" id="passbaru" name="passBaru" value="<?= set_value('passbaru'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('passBaru'); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="passConf">Password Konfirmasi:</label>
                                <input type="password" class="form-control" id="passConf" name="passConf" value="<?= set_value('passConf'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('passConf'); ?></small>
                            </div>
                        </div>
                    </div>
                    <button class="neumorphic-button mt-1" name="tambah" type="submit"><i class="fas fa-key"></i> Ganti Password</button>
                </form>
            </div>
        </div>
    </div>
</section>
</div>