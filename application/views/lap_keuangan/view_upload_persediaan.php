<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('lap_keuangan/peny_piutang'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form class="user" action="" method="POST">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="nama_persediaan">Nama Persediaan :</label>
                                <select name="nama_persediaan" id="nama_persediaan" class="form-control select2">
                                    <option value="">Nama Persediaan</option>
                                    <option value="Bahan Kimia">Bahan Kimia</option>
                                    <option value="Bahan Bakar Minyak">Bahan Bakar Minyak</option>
                                    <option value="Bahan Pembantu">Bahan Pembantu</option>
                                    <option value="Suku Cadang">Suku Cadang</option>
                                    <option value="Pipa-pipa">Pipa-pipa</option>
                                    <option value="Water Meter">Water Meter</option>
                                    <option value="Aksesoris">Aksesoris</option>
                                    <option value="Box Meter">Box Meter</option>
                                    <option value="Barang AMDK">Barang AMDK</option>
                                    <option value="Persediaan Lain-lain">Persediaan Lain-lain</option>
                                </select>
                                <small class="form-text text-danger pl-3"><?= form_error('nama_persediaan'); ?></small>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="tahun_persediaan">Tahun Persediaan :</label>
                                    <input type="number" class="form-control" id="tahun_persediaan" name="tahun_persediaan" placeholder="Masukan Tahun Persediaan" min="2022" max="2099" value="<?= set_value('tahun_persediaan'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('tahun_persediaan'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="harga_perolehan">Harga Perolehan:</label>
                                    <input type="text" class="form-control" id="harga_perolehan" name="harga_perolehan" placeholder="Masukan Jumlah Saldo Awal" value="<?= set_value('harga_perolehan'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('harga_perolehan'); ?></small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="nilai_penurunan">Penurunan Nilai :</label>
                                    <input type="text" class="form-control" id="nilai_penurunan" name="nilai_penurunan" placeholder="Masukan Jumlah Penambahan" value="<?= set_value('nilai_penurunan'); ?>">
                                    <small class="form-text text-danger pl-3"><?= form_error('nilai_penurunan'); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-12 text-center">
                            <button class=" neumorphic-button mt-2" name="tambahkan" type="submit"><i class="fas fa-save"></i> Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
</div>