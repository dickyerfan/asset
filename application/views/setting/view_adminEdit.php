<section class="content">
    <div class="container-fluid mt-2">
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <h3 class="card-title font-weight-bold mt-2"><?= strtoupper($title)  ?></h3>
                <a href="<?= base_url('setting/daftar_user'); ?>"><button class="neumorphic-button float-right float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="<?= base_url('setting/daftar_user/update') ?>" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="hidden" name="id" id="id" value="<?= $user->id ?>">
                            <div class="form-group">
                                <label for="nama_pengguna">Nama Pengguna :</label>
                                <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna" value="<?= $user->nama_pengguna; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap :</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= $user->nama_lengkap; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bagian">Bagian :</label>
                                <input type="text" class="form-control" id="bagian" name="bagian" value="<?= $user->bagian; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="level">Level :</label>
                                <select name="level" id="level" class="form-control">
                                    <option value="Admin" <?= $user->level == 'Admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="Pengguna" <?= $user->level == 'Pengguna' ? 'selected' : '' ?>>Pengguna</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button class="neumorphic-button float-left mb-5" name="tambah" type="submit"><i class="fas fa-save"></i> Simpan</button>
                </form>
            </div>
        </div>
    </div>

</section>
</div>