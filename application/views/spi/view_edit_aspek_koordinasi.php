<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('spi/aspek_koordinasi'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('spi/aspek_koordinasi/update_koordinasi') ?>">
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
                            foreach ($indikator_koordinasi as $ind => $opsi) :
                                $hasil_terpilih = isset($data_koordinasi[$ind]) ? $data_koordinasi[$ind] : '';
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td>
                                        <input type="hidden" name="indikator[]" value="<?= $ind ?>">
                                        <?= $ind ?>
                                    </td>
                                    <td>
                                        <select name="hasil[]" class="form-control" required>
                                            <?php foreach ($opsi as $label => $nilai) : ?>
                                                <option value="<?= $label ?>" <?= ($label == $hasil_terpilih) ? 'selected' : '' ?>>
                                                    <?= $label ?>
                                                </option>
                                            <?php endforeach; ?>
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