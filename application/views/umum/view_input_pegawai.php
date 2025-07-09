    <section class="content">
        <div class="container-fluid">
            <?= $this->session->flashdata('info'); ?>
            <?= $this->session->unset_userdata('info'); ?>
            <div class="card">
                <div class="card-header card-outline card-primary shadow">
                    <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                    <a href="<?= base_url('umum/pegawai'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
                </div>
                <div class="card-body">
                    <form class="user" action="" method="POST">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nama">Nama :</label>
                                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukan Nama" value="<?= set_value('nama'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('nama'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="alamat">Alamat :</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukan Alamat" value="<?= set_value('alamat'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('alamat'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nik">NIK :</label>
                                    <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukan NIK" value="<?= set_value('nik'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('nik'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="no_hp">No HP :</label>
                                    <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="Masukan No HP" value="<?= set_value('no_hp'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('no_hp'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tmp_lahir">Tempat Lahir :</label>
                                    <input type="text" class="form-control" id="tmp_lahir" name="tmp_lahir" placeholder="Masukan Tempat Lahir" value="<?= set_value('tmp_lahir'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tmp_lahir'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tgl_lahir">Tanggal Lahir :</label>
                                    <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" placeholder="Masukan Tanggal Lahir" value="<?= set_value('tgl_lahir'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tgl_lahir'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tgl_masuk">Tanggal Masuk :</label>
                                    <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" placeholder="Masukan Tanggal Masuk" value="<?= set_value('tgl_masuk'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tgl_masuk'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="id_subag">Bagian :</label>
                                    <select name="id_bagian" id="id_bagian" class="form-control select2">
                                        <option value="">-- Pilih Bagian --</option>
                                        <?php foreach ($bagian as $row) : ?>
                                            <option value="<?= $row->id_bagian ?>"><?= $row->nama_bagian ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('id_bagian'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="id_subag">Sub Bagian :</label>
                                    <select name="id_subag" id="id_subag" class="form-control select2">
                                        <option value="">-- Pilih Sub Bagian --</option>
                                        <?php foreach ($subag as $row) : ?>
                                            <option value="<?= $row->id_subag ?>"><?= $row->nama_subag ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('id_subag'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="id_jabatan">Jabatan :</label>
                                    <select name="id_jabatan" id="id_jabatan" class="form-control select2">
                                        <option value="">-- Pilih Jabatan --</option>
                                        <?php foreach ($jabatan as $row) : ?>
                                            <option value="<?= $row->id_jabatan ?>"><?= $row->nama_jabatan ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('id_jabatan'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="agama">Agama :</label>
                                    <select name="agama" id="agama" class="form-control select2">
                                        <option value="">-- Pilih Agama --</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Budha">Budha</option>
                                        <option value="Khonghucu">Khonghucu</option>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('agama'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status_pegawai">Status Karyawan :</label>
                                    <select name="status_pegawai" id="status_pegawai" class="form-control select2">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="Karyawan Tetap">Karyawan Tetap</option>
                                        <option value="Karyawan Kontrak">Karyawan Kontrak</option>
                                        <option value="Karyawan Honorer">Karyawan Honorer</option>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('status_pegawai'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jenkel">Jenis Kelamin :</label>
                                    <select name="jenkel" id="jenkel" class="form-control select2">
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('jenkel'); ?></small>
                                </div>
                            </div>
                        </div>
                        <button class="neumorphic-button mt-1" name="tambah" type="submit"><i class="fas fa-save"></i> Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    </div>