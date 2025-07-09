<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('spi/aspek_admin'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('spi/aspek_admin/update_admin') ?>">
                    <input type="hidden" name="id_upk" value="<?= $id_upk ?>">
                    <input type="hidden" name="bulan" value="<?= $bulan ?>">
                    <input type="hidden" name="tahun" value="<?= $tahun ?>">

                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Indikator</th>
                                <th>Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_admin as $i => $row) : ?>
                                <tr>
                                    <td class="text-center"><?= $i + 1; ?></td>
                                    <td>
                                        <input type="hidden" name="indikator[]" value="<?= $row->indikator ?>">
                                        <?= $row->indikator ?>
                                    </td>
                                    <td>
                                        <select name="hasil[]" class="form-control" required>
                                            <option value="">- Pilih -</option>
                                            <option value="Lengkap & Sesuai" <?= $row->hasil == 'Lengkap & Sesuai' ? 'selected' : '' ?>>Lengkap & Sesuai</option>
                                            <option value="Sebagian" <?= $row->hasil == 'Sebagian' ? 'selected' : '' ?>>Sebagian</option>
                                            <option value="Tidak Ada" <?= $row->hasil == 'Tidak Ada' ? 'selected' : '' ?>>Tidak Ada</option>
                                        </select>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit" class="neumorphic-button">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</section>
</div>