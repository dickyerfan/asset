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
                    <form class="user" action="<?= base_url('umum/pegawai/update_pegawai') ?>" method="POST">
                        <div class="row">
                            <div class="col-md-4 mb-2 mb-2">
                                <input type="hidden" name="id" id="id" value="<?= $karyawan->id; ?>">
                                <div class="form-group">
                                    <label for="nama">Nama :</label>
                                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukan Nama" value="<?= $karyawan->nama; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('nama'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="alamat">Alamat :</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukan Alamat" value="<?= $karyawan->alamat; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('alamat'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="nik">NIK :</label>
                                    <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukan NIK" value="<?= $karyawan->nik; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('nik'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="no_hp">No HP :</label>
                                    <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="Masukan No HP" value="<?= $karyawan->no_hp; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('no_hp'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="tmp_lahir">Tempat Lahir :</label>
                                    <input type="text" class="form-control" id="tmp_lahir" name="tmp_lahir" placeholder="Masukan Tempat Lahir" value="<?= $karyawan->tmp_lahir; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tmp_lahir'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="tgl_lahir">Tanggal Lahir :</label>
                                    <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" placeholder="Masukan Tanggal Lahir" value="<?= $karyawan->tgl_lahir; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tgl_lahir'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="tgl_masuk">Tanggal Masuk :</label>
                                    <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" placeholder="Masukan Tanggal Masuk" value="<?= $karyawan->tgl_masuk; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tgl_masuk'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="agama">Agama :</label>
                                    <select name="agama" id="agama" class="form-control select2">
                                        <option value="">-- Pilih Agama --</option>
                                        <option value="Islam" <?= $karyawan->agama == 'Islam' ? 'selected' : '' ?>>Islam</option>
                                        <option value="Kristen" <?= $karyawan->agama == 'Kristen' ? 'selected' : '' ?>>Kristen</option>
                                        <option value="Katolik" <?= $karyawan->agama == 'Katolik' ? 'selected' : '' ?>>Katolik</option>
                                        <option value="Hindu" <?= $karyawan->agama == 'Hindu' ? 'selected' : '' ?>>Hindu</option>
                                        <option value="Budha" <?= $karyawan->agama == 'Budha' ? 'selected' : '' ?>>Budha</option>
                                        <option value="Khonghucu" <?= $karyawan->agama == 'Khonghucu' ? 'selected' : '' ?>>Khonghucu</option>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('agama'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="status_pegawai">Status Karyawan :</label>
                                    <select name="status_pegawai" id="status_pegawai" class="form-control select2">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="Karyawan Tetap" <?= $karyawan->status_pegawai == 'Karyawan Tetap' ? 'selected' : '' ?>>Karyawan Tetap</option>
                                        <option value="Karyawan Kontrak" <?= $karyawan->status_pegawai == 'Karyawan Kontrak' ? 'selected' : '' ?>>Karyawan Kontrak</option>
                                        <option value="Karyawan Honorer" <?= $karyawan->status_pegawai == 'Karyawan Honorer' ? 'selected' : '' ?>>Karyawan Honorer</option>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('status_pegawai'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="id_subag">Bagian :</label>
                                    <select name="id_bagian" id="id_bagian" class="form-control select2">
                                        <option value="">-- Pilih Bagian --</option>
                                        <?php foreach ($bagian as $row) : ?>
                                            <option value="<?= $row->id_bagian ?>" <?= $karyawan->id_bagian == $row->id_bagian ? 'selected' : '' ?>><?= $row->nama_bagian ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('id_bagian'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="id_subag">Sub Bagian :</label>
                                    <select name="id_subag" id="id_subag" class="form-control select2">
                                        <option value="">-- Pilih Sub Bagian --</option>
                                        <?php foreach ($subag as $row) : ?>
                                            <option value="<?= $row->id_subag ?>" <?= $karyawan->id_subag == $row->id_subag ? 'selected' : '' ?>><?= $row->nama_subag ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('id_subag'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="id_jabatan">Jabatan :</label>
                                    <select name="id_jabatan" id="id_jabatan" class="form-control select2">
                                        <option value="">-- Pilih Jabatan --</option>
                                        <?php foreach ($jabatan as $row) : ?>
                                            <option value="<?= $row->id_jabatan ?>" <?= $karyawan->id_jabatan == $row->id_jabatan ? 'selected' : '' ?>><?= $row->nama_jabatan ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('id_jabatan'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="jenkel">Jenis Kelamin :</label>
                                    <select name="jenkel" id="jenkel" class="form-control select2">
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="Laki-laki" <?= $karyawan->jenkel == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                        <option value="Perempuan" <?= $karyawan->jenkel == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('jenkel'); ?></small>
                                </div>
                            </div>
                            <!-- <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="aktif">Aktif :</label>
                                    <select name="aktif" id="aktif" class="form-control select2">
                                        <option value="">-- Pilih Status Aktif --</option>
                                        <option value="1" <?= $karyawan->aktif == '1' ? 'selected' : '' ?>>Aktif</option>
                                        <option value="0" <?= $karyawan->aktif == '0' ? 'selected' : '' ?>>Purna</option>
                                    </select>
                                    <small class="form-text text-danger pl-3"><?= form_error('aktif'); ?></small>
                                </div>
                            </div> -->
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="tahun_keluar">Tahun Keluar :</label>
                                    <input type="year" class="form-control" id="tahun_keluar" name="tahun_keluar" placeholder="Masukan Tahun Keluar" value="<?= $karyawan->tahun_keluar; ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tahun_keluar'); ?></small>
                                </div>
                            </div>
                        </div>
                        <button class="neumorphic-button mt-1" name="tambah" type="submit"><i class="fas fa-edit"></i> Update</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    </div>