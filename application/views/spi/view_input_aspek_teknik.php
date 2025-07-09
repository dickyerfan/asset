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
                    <form method="post" action="">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>UPK</label>
                                <select name="id_upk" class="form-control" required>
                                    <option value="">Pilih Unit</option>
                                    <?php foreach ($unit_list as $unit) : ?>
                                        <option value="<?= $unit->id_bagian ?>"><?= $unit->nama_bagian ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- <div class="form-group col-md-4">
                                <label>Bulan</label>
                                <select name="bulan" class="form-control" required>
                                    <?php for ($i = 1; $i <= 12; $i++) : ?>
                                        <option value="<?= $i ?>"><?= date("F", mktime(0, 0, 0, $i, 1)); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div> -->
                            <div class="form-group col-md-4">
                                <label>Bulan</label>
                                <select name="bulan" class="form-control" required>
                                    <?php
                                    $bulan_lalu = (int)date('n', strtotime('-1 month'));
                                    $nama_bulan = [
                                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                        4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                        10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                    ];
                                    for ($i = 1; $i <= 12; $i++) :
                                    ?>
                                        <option value="<?= $i ?>" <?= ($i == $bulan_lalu) ? 'selected' : '' ?>>
                                            <?= $nama_bulan[$i] ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tahun</label>
                                <input type="number" name="tahun" class="form-control" value="<?= date('Y'); ?>" required>
                            </div>
                        </div>

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
                                <?php
                                $indikator_teknis = [
                                    "Buku Produksi",
                                    "Buku Cek tekanan",
                                    "Buku Pengaduan",
                                    "Buku Tera water meter",
                                    "Buku SR Baru",
                                    "Buku Pembukaan",
                                    "Buku Penutupan",
                                    "Buku Pencabutan",
                                    "Buku PG Water Meter",
                                    "Buku Setoran"
                                ];
                                foreach ($indikator_teknis as $i => $indikator) : ?>
                                    <tr>
                                        <td class="text-center"><?= $i + 1; ?></td>
                                        <td>
                                            <input type="hidden" name="indikator[]" value="<?= $indikator ?>">
                                            <?= $indikator ?>
                                        </td>
                                        <td>
                                            <select name="keberadaan[]" class="form-control" required>
                                                <option value="">- Pilih -</option>
                                                <option value="Ada">Ada</option>
                                                <option value="Tidak Ada">Tidak Ada</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="kondisi[]" class="form-control" required>
                                                <option value="">- Pilih -</option>
                                                <option value="Lengkap">Lengkap</option>
                                                <option value="Tidak Lengkap">Tidak Lengkap</option>
                                                <option value="Tidak Ada">Tidak Ada</option>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>

                </div>
            </div>
        </div>
    </section>
    </div>