<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form method="get" action="<?= base_url('spi/tindak_lanjut'); ?>" class="form-inline ">
                        <div class="form-group mr-2">
                            <label>UPK :</label>
                            <select name="id_upk" class="form-control ml-2">
                                <option value=""> Pilih UPK</option>
                                <?php foreach ($all_upk as $upk) : ?>
                                    <option value="<?= $upk->id_bagian ?>" <?= ($id_upk_selected == $upk->id_bagian) ? 'selected' : '' ?>>
                                        <?= $upk->nama_bagian ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <label>Bulan :</label>
                            <select name="bulan" class="form-control ml-2">
                                <option value="">Pilih Bulan</option>
                                <?php for ($i = 1; $i <= 12; $i++) : ?>
                                    <option value="<?= $i ?>" <?= ($bulan_selected == $i) ? 'selected' : '' ?>>
                                        <?= $nama_bulan_lengkap[$i] ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <label>Tahun :</label>
                            <input type="number" name="tahun" class="form-control ml-2" value="<?= $tahun_selected ?>" required>
                        </div>

                        <button type="submit" class="neumorphic-button">Tampilkan</button>
                    </form>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('spi/tindak_lanjut/input_tl') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Tindak Lanjut</button></a>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-12 text-center">
                        <!-- <h5><?= strtoupper($title); ?> <?= strtoupper($nama_upk->nama_bagian); ?></h5> -->
                        <h5>
                            <?= strtoupper($title); ?>
                            <?= isset($nama_upk) && !empty($nama_upk->nama_bagian) ? strtoupper($nama_upk->nama_bagian) : ' '; ?>
                        </h5>
                        <?php if ($bulan_selected && $bulan_selected != '') : ?>
                            <h5><?= strtoupper($nama_bulan_lengkap[$bulan_selected]); ?> <?= $tahun_selected; ?></h5>
                        <?php else : ?>
                            <p>Tahun: <?= $tahun_selected; ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>UPK</th>
                                <th>Temuan</th>
                                <th>Rekomendasi</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            if (!empty($tindak_lanjut_data)) :
                                foreach ($tindak_lanjut_data as $tl) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><?= $tl->nama_bagian; ?></td>
                                        <td><?= $tl->temuan; ?></td>
                                        <td><?= $tl->rekomendasi; ?></td>
                                        <td><?= $tl->keterangan; ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('spi/tindak_lanjut/edit/' . $tl->id_tl); ?>"><i class="fas fa-edit"></i></a>
                                            <a href="<?= base_url('spi/tindak_lanjut/hapus/' . $tl->id_tl); ?>" onclick="return confirm('Anda yakin ingin menghapus data ini?')"><i class="fas fa-trash text-danger"></i> </a>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else : ?>
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada data tindak lanjut ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>