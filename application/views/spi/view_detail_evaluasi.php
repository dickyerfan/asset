<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('spi/hasil_evaluasi'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('spi/hasil_evaluasi/cetak_hasil') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>
                    </div>
                </nav>
            </div>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h5>
                            <?= strtoupper($title); ?>
                        </h5>
                    </div>
                </div>
                <h5><i class="fas fa-tools"></i> Aspek Teknis</h5>
                <?php if (!empty($detail_teknis)) : ?>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Indikator</th>
                                    <th>Keberadaan</th>
                                    <th>Kondisi</th>
                                    <th>Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no_teknis = 1;
                                foreach ($detail_teknis as $teknis) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no_teknis++; ?></td>
                                        <td><?= $teknis->indikator; ?></td>
                                        <td class="text-center"><?= $teknis->keberadaan; ?></td>
                                        <td class="text-center"><?= $teknis->kondisi; ?></td>
                                        <td class="text-center"><?= $teknis->skor; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Total Skor Teknis :</th>
                                    <th class="text-center"><?= $total_skor_teknis; ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php else : ?>
                    <p class="btn btn-danger">Tidak ada / Belum ada data teknis untuk UPK ini pada periode yang dipilih.</p>
                <?php endif; ?>

                <h5><i class="fas fa-file-alt"></i> Aspek Administrasi</h5>
                <?php if (!empty($detail_admin)) : ?>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Indikator</th>
                                    <th>Hasil</th>
                                    <th>Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no_admin = 1;
                                foreach ($detail_admin as $admin) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no_admin++; ?></td>
                                        <td><?= $admin->indikator; ?></td>
                                        <td class="text-center"><?= $admin->hasil; ?></td>
                                        <td class="text-center"><?= $admin->skor; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Total Skor Administrasi :</th>
                                    <th class="text-center"><?= $total_skor_admin; ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php else : ?>
                    <p class="btn btn-danger">Tidak ada / Belum ada data administrasi untuk UPK ini pada periode yang dipilih.</p>
                <?php endif; ?>

                <h5><i class="fas fa-users"></i> Aspek Koordinasi</h5>
                <?php if (!empty($detail_koordinasi)) : ?>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Indikator</th>
                                    <th>Hasil</th>
                                    <th>Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no_koordinasi = 1;
                                foreach ($detail_koordinasi as $koordinasi) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no_koordinasi++; ?></td>
                                        <td><?= $koordinasi->indikator; ?></td>
                                        <td class="text-center"><?= $koordinasi->hasil; ?></td>
                                        <td class="text-center"><?= $koordinasi->skor; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Total Skor Koordinasi :</th>
                                    <th class="text-center"><?= $total_skor_koordinasi; ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php else : ?>
                    <p class="btn btn-danger">Tidak ada / Belum ada data koordinasi untuk UPK ini pada periode yang dipilih.</p>
                <?php endif; ?>
                <h5><i class="fas fa-assistive-listening-systems"></i> Tindak Lanjut</h5>
                <?php if (!empty($detail_tindak_lanjut)) : ?>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Temuan</th>
                                    <th>Rekomendasi</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no_tl = 1;
                                foreach ($detail_tindak_lanjut as $tl) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no_tl++; ?></td>
                                        <td><?= $tl->temuan; ?></td>
                                        <td class="text-center"><?= $tl->rekomendasi; ?></td>
                                        <td class="text-center"><?= $tl->keterangan; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <p class="btn btn-danger">Tidak ada / Belum ada data Tindak Lanjut untuk UPK ini pada periode yang dipilih.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
</div>