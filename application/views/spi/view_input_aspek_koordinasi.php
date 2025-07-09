    <section class="content">
        <div class="container-fluid">
            <?= $this->session->flashdata('info'); ?>
            <?= $this->session->unset_userdata('info'); ?>
            <div class="card">
                <div class="card-header card-outline card-primary shadow">
                    <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                    <a href="<?= base_url('spi/aspek_koordinasi'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
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
                                    <th>Hasil</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $indikator_koordinasi = [
                                    "Kehadiran & Dokumen Manual" => [
                                        "Hadir & Lengkap" => 3,
                                        "Hadir & Tidak Lengkap" => 2,
                                        "Tidak Hadir" => 1
                                    ],
                                    "Memberikan Narasi Unit" => [
                                        "Narasi Lengkap" => 3,
                                        "Narasi Sebagian" => 2,
                                        "Tidak Ada" => 1
                                    ],
                                    "Tanggapan terhadap Temuan Bulan Lalu" => [
                                        "Tanggapan Lengkap" => 3,
                                        "Sebagian" => 2,
                                        "Tidak Lengkap" => 1
                                    ],
                                    "Aktif Menyampaikan Inisiatif / Usulan" => [
                                        "Usulan Lengkap" => 3,
                                        "Sebagian" => 2,
                                        "Tidak Ada Usulan" => 1
                                    ],
                                    "Pencocokan Data Aplikasi vs Buku Manual" => [
                                        "Cocok Semua" => 3,
                                        "Sebagian" => 2,
                                        "Tidak Cocok" => 1
                                    ]
                                ];
                                $no = 1;
                                foreach ($indikator_koordinasi as $indikator => $opsi) :
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td>
                                            <input type="hidden" name="indikator[]" value="<?= $indikator ?>">
                                            <?= $indikator ?>
                                        </td>
                                        <td>
                                            <select name="hasil[]" class="form-control" required>
                                                <option value="">- Pilih -</option>
                                                <?php foreach ($opsi as $label => $nilai) : ?>
                                                    <option value="<?= $label ?>"><?= $label ?></option>
                                                <?php endforeach; ?>
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