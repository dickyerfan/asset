<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('spi/aspek_teknik'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('spi/aspek_teknik/update_teknik') ?>">
                    <input type="hidden" name="id_upk" value="<?= $id_upk ?>">
                    <input type="hidden" name="bulan" value="<?= $bulan ?>">
                    <input type="hidden" name="tahun" value="<?= $tahun ?>">

                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Indikator</th>
                                <th>Keberadaan</th>
                                <th>Kondisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_teknik as $i => $row) : ?>
                                <tr>
                                    <td class="text-center"><?= $i + 1; ?></td>
                                    <td>
                                        <input type="hidden" name="indikator[]" value="<?= $row->indikator ?>">
                                        <?= $row->indikator ?>
                                    </td>
                                    <td>
                                        <select name="keberadaan[]" class="form-control" required>
                                            <option value="">- Pilih -</option>
                                            <option value="Ada" <?= $row->keberadaan == 'Ada' ? 'selected' : '' ?>>Ada</option>
                                            <option value="Tidak Ada" <?= $row->keberadaan == 'Tidak Ada' ? 'selected' : '' ?>>Tidak Ada</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="kondisi[]" class="form-control" required>
                                            <option value="">- Pilih -</option>
                                            <option value="Lengkap" <?= $row->kondisi == 'Lengkap' ? 'selected' : '' ?>>Lengkap</option>
                                            <option value="Tidak Lengkap" <?= $row->kondisi == 'Tidak Lengkap' ? 'selected' : '' ?>>Tidak Lengkap</option>
                                            <option value="Tidak Ada" <?= $row->kondisi == 'Tidak Ada' ? 'selected' : '' ?>>Tidak Ada</option>
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