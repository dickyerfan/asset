<section class="content">
    <div class="container-fluid mt-2">
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <h3 class="card-title font-weight-bold mt-2"><?= strtoupper($title)  ?></h3>
                <a href="<?= base_url('setting/daftar_user'); ?>"><button class="btn btn-primary btn-sm float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_pengguna">Nama Pengguna :</label>
                                <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna" placeholder="Masukan nama pengguna" value="<?= set_value('nama_pengguna'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('nama_pengguna'); ?></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap :</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Masukan nama lengkap" value="<?= set_value('nama_lengkap'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('nama_lengkap'); ?></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bagian">Bagian :</label>
                                <input type="text" class="form-control" id="bagian" name="bagian" placeholder="Masukan bagian" value="<?= set_value('bagian'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('bagian'); ?></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password :</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukan password" value="<?= set_value('password'); ?>">
                                <small class="form-text text-danger pl-3"><?= form_error('password'); ?></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="level">Pilih Level:</label>
                                <select name="level" id="level" class="form-control">
                                    <option value="Admin">Admin</option>
                                    <option value="Pengguna" selected>Pengguna</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary float-left mb-5" name="tambah" type="submit"><i class="fas fa-save"></i> Simpan</button>
                </form>
            </div>
        </div>
    </div>

</section>
</div>