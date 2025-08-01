<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form method="get" action="<?= base_url('risiko/profil_risiko'); ?>" class="form-inline ">
                        <div class="form-group mr-2">
                            <label>UPK:</label>
                            <select name="id_upk" class="form-control ml-2">
                                <option value="">Pilih Bagian / UPK</option>
                                <?php
                                $bagian = $this->session->userdata('bagian');
                                if (in_array($bagian, ['Administrator', 'Keuangan', 'Publik', 'Auditor'])) {
                                    // Tampilkan semua
                                    foreach ($unit_list as $unit) : ?>
                                        <option value="<?= $unit->id_bagian ?>" <?= isset($filter['id_upk']) && $filter['id_upk'] == $unit->id_bagian ? 'selected' : '' ?>>
                                            <?= $unit->nama_bagian ?>
                                        </option>
                                        <?php endforeach;
                                } else {
                                    // Tampilkan hanya milik user login
                                    $id_bagian = $this->session->userdata('id_bagian');
                                    foreach ($unit_list as $unit) :
                                        if ($unit->id_bagian == $id_bagian) : ?>
                                            <option value="<?= $unit->id_bagian ?>" <?= isset($filter['id_upk']) && $filter['id_upk'] == $unit->id_bagian ? 'selected' : '' ?>>
                                                <?= $unit->nama_bagian ?>
                                            </option>
                                <?php endif;
                                    endforeach;
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <label>Tahun:</label>
                            <input type="number" name="tahun" class="form-control ml-2" value="<?= isset($filter['tahun']) ? $filter['tahun'] : date('Y') ?>">
                        </div>
                        <button type="submit" class="neumorphic-button">Tampilkan</button>
                    </form>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('risiko/profil_risiko/input_risiko') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Risiko</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('risiko/profil_risiko/cetak_risiko') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>

                    </div>
                </nav>
            </div>
            <div class="card-body">
                <?php if (!empty($filter['id_upk']) && !empty($filter['tahun'])) : ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-body py-1 px-1">
                                    <?php
                                    $nama_upk = '';
                                    if (isset($unit_list) && is_array($unit_list)) {
                                        foreach ($unit_list as $unit) {
                                            if ($unit->id_bagian == $filter['id_upk']) {
                                                $nama_upk = $unit->nama_bagian;
                                                break;
                                            }
                                        }
                                    }
                                    ?>
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="text-start" style="width:50%">Pemilik Risiko</th>
                                                <td class="text-center" style="width:1%">:</td>
                                                <td class="text-start"><?= $nama_upk; ?></td>
                                            </tr>
                                            <tr>
                                                <th class="text-start">Penanggung Jawab Risiko</th>
                                                <td class="text-center">:</td>
                                                <td class="text-start">Ka UPK <?= $nama_upk; ?></td>
                                            </tr>
                                            <tr>
                                                <th class="text-start">Periode</th>
                                                <td class="text-center">:</td>
                                                <td class="text-start"><?= $filter['tahun']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title); ?> TAHUN <?= $tahun; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Kegiatan</th>
                                <th>Tujuan Keg.</th>
                                <th>Kode Risiko</th>
                                <th>Pernyataan</th>
                                <th>Sebab</th>
                                <th>C / UC</th>
                                <th>Dampak</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($profil_risiko) && count($profil_risiko) > 0) : $no = 1;
                                foreach ($profil_risiko as $row) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= $row->kegiatan ?></td>
                                        <td><?= $row->tujuan ?></td>
                                        <td><?= $row->kode_risiko ?></td>
                                        <td><?= $row->pernyataan ?></td>
                                        <td><?= $row->sebab ?></td>
                                        <td class="text-center"><?= $row->kategori ?></td>
                                        <td><?= $row->dampak ?></td>
                                        <td class="text-center">
                                            <?php $bagian = $this->session->userdata('bagian');
                                            if (in_array($bagian, ['Administrator', 'Keuangan', 'Publik'])) : ?>
                                                <a href="<?= base_url('risiko/profil_risiko/edit/' . $row->id_risiko) ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <?php endif; ?>
                                            <!-- <a href="<?= base_url('risiko/profil_risiko/delete/' . $row->id_risiko) ?>" class="btn btn-danger btn-sm tombolHapus">Hapus</a> -->
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else : ?>
                                <tr>
                                    <td colspan="9" class="text-center">Data belum ada / tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title2); ?> TAHUN <?= $tahun; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th rowspan="3" class="align-middle">No</th>
                                <th rowspan="3" class="align-middle">Risiko</th>
                                <th colspan="6">Pengendalian Yang Ada</th>
                                <th colspan="4">Analisa dan Evaluasi Risiko</th>
                                <th rowspan="3" class="align-middle">Pemilik Risiko</th>
                                <th rowspan="3" class="align-middle">Action</th>
                            </tr>
                            <tr class="text-center">
                                <th rowspan="2" class="align-middle">Uraian</th>
                                <th colspan="2">Desain</th>
                                <th colspan="3">Efektivitas</th>
                                <th rowspan="2" class="align-middle">Prob.</th>
                                <th rowspan="2" class="align-middle">Dampak</th>
                                <th rowspan="2" class="align-middle">Tingkat Risiko</th>
                                <th rowspan="2" class="align-middle">Peringkat Risiko</th>

                            </tr>
                            <tr class="text-center">
                                <th>Ada</th>
                                <th>Tidak</th>
                                <th>Tidak</th>
                                <th>Kurang</th>
                                <th>Efektif</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($analisa_risiko) && count($analisa_risiko) > 0) : $no = 1;
                                foreach ($analisa_risiko as $row) :
                                    if ($row->peringkat_risiko == 'Sangat Tinggi') {
                                        $warna = '#FF0000'; // Merah
                                    } elseif ($row->peringkat_risiko == 'Tinggi') {
                                        $warna = '#FFC000'; // Oranye
                                    } elseif ($row->peringkat_risiko == 'Moderat') {
                                        $warna = '#FFFF00'; // Kuning
                                    } elseif ($row->peringkat_risiko == 'Rendah') {
                                        $warna = '#92D050'; // Hijau
                                    } else {
                                        $warna = '#00B0F0'; // Biru muda
                                    }
                            ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= $row->pernyataan ?></td>
                                        <td><?= $row->kendali_uraian ?></td>
                                        <td class="text-center"><?= ($row->desain == 'Ada') ? 'v' : '-' ?></td>
                                        <td class="text-center"><?= ($row->desain == 'Tidak') ? 'v' : '-' ?></td>
                                        <td class="text-center"><?= ($row->efektifitas == 'Tidak') ? 'v' : '-' ?></td>
                                        <td class="text-center"><?= ($row->efektifitas == 'Kurang') ? 'v' : '-' ?></td>
                                        <td class="text-center"><?= ($row->efektifitas == 'Efektif') ? 'v' : '-' ?></td>
                                        <td class="text-center"><?= $row->probabilitas ?></td>
                                        <td class="text-center"><?= $row->dampak ?></td>
                                        <td class="text-center"><?= $row->tingkat_risiko ?></td>
                                        <td class="text-center" style="background-color: <?= $warna ?>" class="align-middle">
                                            <?= $row->peringkat_risiko ?>
                                        </td>
                                        <td class="text-center"><?= $row->pemilik_risiko ?></td>
                                        <td class="text-center">
                                            <?php $bagian = $this->session->userdata('bagian');
                                            if (in_array($bagian, ['Administrator', 'Keuangan', 'Publik'])) : ?>
                                                <a href="<?= base_url('risiko/profil_risiko/edit_analisa/' . $row->id_analisa) ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else : ?>
                                <tr>
                                    <td colspan="14" class="text-center">Data belum ada / tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title3); ?> TAHUN <?= $tahun; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh3" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th rowspan="2" class="align-middle">No</th>
                                <th rowspan="2" class="align-middle">Risiko</th>
                                <th colspan="3" class="align-middle">Penanganan/Rencana Tindak Pengendalian</th>
                                <th rowspan="2" class="align-middle">Penanggung Jawab TL</th>
                                <th rowspan="2" class="align-middle">Action</th>
                            </tr>
                            <tr class="text-center">
                                <th>Uraian</th>
                                <th>Jadwal</th>
                                <th>Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($penanganan_risiko) && count($penanganan_risiko) > 0) : $no = 1;
                                foreach ($penanganan_risiko as $row) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= $row->pernyataan ?></td>
                                        <td><?= $row->uraian ?></td>
                                        <td><?= $row->jadwal ?></td>
                                        <td><?= $row->hasil ?></td>
                                        <td><?= $row->pj_tl ?></td>
                                        <td class="text-center">
                                            <?php $bagian = $this->session->userdata('bagian');
                                            if (in_array($bagian, ['Administrator', 'Keuangan', 'Publik'])) : ?>
                                                <a href="<?= base_url('risiko/profil_risiko/edit_penanganan/' . $row->id_penanganan) ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else : ?>
                                <tr>
                                    <td colspan="7" class="text-center">Data belum ada / tidak tersedia</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title4); ?> TAHUN <?= $tahun; ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh4" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th rowspan="2" class="align-middle">No</th>
                                <th rowspan="2" class="align-middle">Risiko</th>
                                <th colspan="4">Realisasi</th>
                                <th colspan="4">Level Risiko setelah RTP </th>
                                <th rowspan="2" class="align-middle">Action</th>
                            </tr>
                            <tr class="text-center">
                                <th>RTP</th>
                                <th>Jadwal</th>
                                <th>Hasil</th>
                                <th>Keterangan</th>
                                <th>Prob</th>
                                <th>Dampak</th>
                                <th>Tingkat</th>
                                <th>peringkat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($monitoring_risiko) && count($monitoring_risiko) > 0) : $no = 1;
                                foreach ($monitoring_risiko as $row) :
                                    if ($row->peringkat_setelah == 'Sangat Tinggi') {
                                        $warna = '#FF0000'; // Merah
                                    } elseif ($row->peringkat_setelah == 'Tinggi') {
                                        $warna = '#FFC000'; // Oranye
                                    } elseif ($row->peringkat_setelah == 'Moderat') {
                                        $warna = '#FFFF00'; // Kuning
                                    } elseif ($row->peringkat_setelah == 'Rendah') {
                                        $warna = '#92D050'; // Hijau
                                    } else {
                                        $warna = '#00B0F0'; // Biru muda
                                    }
                            ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= $row->pernyataan ?></td>
                                        <td><?= $row->rtp ?></td>
                                        <td><?= $row->jadwal ?></td>
                                        <td><?= $row->hasil ?></td>
                                        <td><?= $row->keterangan ?></td>
                                        <td class="text-center"><?= $row->prob_setelah ?></td>
                                        <td class="text-center"><?= $row->dampak_setelah ?></td>
                                        <td class="text-center"><?= $row->tingkat_setelah ?></td>
                                        <td class="text-center" style="background-color: <?= $warna ?>" class="align-middle">
                                            <?= $row->peringkat_setelah ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $bagian = $this->session->userdata('bagian');
                                            if (in_array($bagian, ['Administrator', 'Keuangan', 'Publik'])) : ?>
                                                <a href="<?= base_url('risiko/profil_risiko/edit_monitoring/' . $row->id_monitoring) ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else : ?>
                                <tr>
                                    <td colspan="11" class="text-center">Data belum ada / tidak tersedia</td>
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