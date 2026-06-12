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
                    <!-- <div class="navbar-nav ms-2">
                        <?php if (isset($akses_input_risiko) && $akses_input_risiko) : ?>
                            <a href="<?= base_url('risiko/profil_risiko/input_risiko') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Risiko</button></a>
                        <?php endif; ?>
                    </div> -->
                    <div class="navbar-nav ms-2">
                        <?php if (!empty($akses_input_risiko)) : ?>
                            <a href="<?= base_url('risiko/profil_risiko/input_risiko') ?>">
                                <button class="float-end neumorphic-button">
                                    <i class="fas fa-plus"></i> Input Risiko
                                </button>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('risiko/profil_risiko/cetak_risiko?id_upk=' . (isset($filter['id_upk']) ? $filter['id_upk'] : '') . '&tahun=' . (isset($filter['tahun']) ? $filter['tahun'] : date('Y'))) ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <?php if (isset($akses_input_risiko) && $akses_input_risiko) : ?>
                            <a href="<?= base_url('risiko/dashboard') ?>"><button class="float-end neumorphic-button"><i class="fas fa-reply"></i> Kembali Dashboard</button></a>
                        <?php endif; ?>
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
                                                <td class="text-start">Kabag/Ka UPK/Ketua <?= $nama_upk; ?></td>
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
                                <th>Bag/UPK</th>
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
                            <?php
                            $current_year = date('Y');
                            if (isset($profil_risiko) && count($profil_risiko) > 0) : $no = 1;
                                foreach ($profil_risiko as $row) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= !empty($row->nama_bagian) ? $row->nama_bagian : $row->id_upk ?></td>
                                        <td><?= $row->kegiatan ?></td>
                                        <td><?= $row->tujuan ?></td>
                                        <td><?= $row->kode_risiko ?></td>
                                        <td><?= $row->pernyataan ?></td>
                                        <td><?= $row->sebab ?></td>
                                        <td class="text-center"><?= $row->kategori ?></td>
                                        <td><?= $row->dampak ?></td>
                                        <td class="text-center">
                                            <?php if (isset($akses_edit_profil) && $row->tahun == $current_year && $akses_edit_profil) : ?>
                                                <a href="<?= base_url('risiko/profil_risiko/edit/' . $row->id_risiko) ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <?php endif; ?>
                                            <!-- <a href="<?= base_url('risiko/profil_risiko/delete/' . $row->id_risiko) ?>" class="btn btn-danger btn-sm tombolHapus">Hapus</a> -->
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else : ?>
                                <tr>
                                    <td colspan="10" class="text-center">Data belum ada / tidak tersedia</td>
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
                            <?php
                            $current_year = date('Y');
                            if (isset($analisa_risiko) && count($analisa_risiko) > 0) : $no = 1;
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
                                            <?php if (isset($akses_edit_analisa) && $row->tahun == $current_year && $akses_edit_analisa) : ?>
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
                            <?php
                            $current_year = date('Y');
                            if (isset($penanganan_risiko) && count($penanganan_risiko) > 0) : $no = 1;
                                foreach ($penanganan_risiko as $row) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= $row->pernyataan ?></td>
                                        <td><?= $row->uraian ?></td>
                                        <td><?= $row->jadwal ?></td>
                                        <td><?= $row->hasil ?></td>
                                        <td><?= $row->pj_tl ?></td>
                                        <td class="text-center">
                                            <?php if (isset($akses_edit_penanganan) && $row->tahun == $current_year && $akses_edit_penanganan) : ?>
                                                <a href="<?= base_url('risiko/profil_risiko/edit_penanganan/' . $row->id_penanganan) ?>" class="btn btn-warning btn-sm mb-1" style="width: 80px;">Edit</a>
                                            <?php endif; ?>

                                            <?php if (isset($akses_edit_penanganan) && $row->tahun == $current_year && $akses_edit_penanganan) : ?>
                                                <button class="btn btn-primary btn-sm mb-1" style="width: 80px;" data-toggle="modal" data-target="#uploadModal<?= $row->id_penanganan ?>" <?= (!empty($row->file_image) && !empty($row->file_document)) ? 'disabled title="File sudah lengkap"' : '' ?>>
                                                    Upload
                                                </button>
                                            <?php endif; ?>
                                            <?php if (!empty($row->file_image) || !empty($row->file_document)) : ?>
                                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#detailModal<?= $row->id_penanganan ?>" style="width: 80px;">Detail</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <!-- modal upload -->
                                    <div id="uploadModal<?= $row->id_penanganan ?>" class="modal fade" tabindex="-1" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Upload File pendukung Penanganan Risiko</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="<?= base_url('risiko/profil_risiko/upload_file/' . $row->id_penanganan) ?>" method="post" enctype="multipart/form-data">
                                                        <div class="form-group">
                                                            <label>Pilih Jenis File</label><br>
                                                            <?php if (empty($row->file_image)) : ?>
                                                                <input type="radio" name="file_type" value="image" required> Gambar (jpg, jpeg, png)<br>
                                                            <?php endif; ?>
                                                            <?php if (empty($row->file_document)) : ?>
                                                                <input type="radio" name="file_type" value="document" required> Dokumen (pdf)
                                                            <?php endif; ?>
                                                        </div>
                                                        <small class="text-danger">Ukuran file maksimal 2 MB</small>
                                                        <br>
                                                        <small class="text-danger">Masing2 hanya bisa upload 1 file saja</small>
                                                        <div class="form-group">
                                                            <label>Pilih File</label>
                                                            <input type="file" name="file_upload" class="form-control" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary mt-3">Upload</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal Detail -->
                                    <div id="detailModal<?= $row->id_penanganan ?>" class="modal fade" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-xl" role="document" style="max-width:1600px; width:100%;">
                                            <div class="modal-content">
                                                <div class="modal-header" style="padding:1rem 2rem;">
                                                    <h5 class="modal-title">Detail File Pendukung</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-header" style="padding:0.5rem 2rem;">
                                                    <h6 class="text-muted">Terakhir Diupdate: <?= $row->modified_at ?></h6>
                                                    <h6 class="text-muted">Petugas Edit: <?= $row->modified_by ?></h6>
                                                </div>
                                                <div class="modal-body" style="padding:1rem 2rem;">
                                                    <div class="row">
                                                        <?php if (!empty($row->file_image)) : ?>
                                                            <div class="col-md-5 text-center mb-3">
                                                                <p><strong>Gambar:</strong></p>
                                                                <img src="<?= base_url('uploads/manris/' . $row->file_image) ?>" class="img-fluid border" style="max-height:800px;">
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php if (!empty($row->file_document)) : ?>
                                                            <div class="col-md-7 text-center mb-3">
                                                                <p><strong>Dokumen (PDF):</strong></p>
                                                                <iframe src="<?= base_url('uploads/manris/' . $row->file_document) ?>" width="100%" height="800px" class="border" style="border:1px solid #ccc;"></iframe>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                            <?php
                            $current_year = date('Y');
                            if (isset($monitoring_risiko) && count($monitoring_risiko) > 0) : $no = 1;
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
                                            <?php if (isset($akses_edit_monitoring) && $row->tahun == $current_year && $akses_edit_monitoring) : ?>
                                                <a href="<?= base_url('risiko/profil_risiko/edit_monitoring/' . $row->id_monitoring) ?>" class="btn btn-warning btn-sm mb-1" style="width: 80px;">Edit</a>
                                            <?php endif; ?>

                                            <?php if (isset($akses_edit_monitoring) && $row->tahun == $current_year && $akses_edit_monitoring) : ?>
                                                <button class="btn btn-primary btn-sm mb-1" style="width: 80px;" data-toggle="modal" data-target="#uploadModalSpi<?= $row->id_monitoring ?>" <?= (!empty($row->file_document)) ? 'disabled title="File sudah lengkap"' : '' ?>>
                                                    Upload
                                                </button>
                                            <?php endif; ?>
                                            <?php if (!empty($row->file_document)) : ?>
                                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#detailModalSpi<?= $row->id_monitoring ?>" style="width: 80px;">Detail</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <!-- modal upload -->
                                    <div id="uploadModalSpi<?= $row->id_monitoring ?>" class="modal fade" tabindex="-1" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Upload File Pendukung Monitoring Risiko</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="<?= base_url('risiko/profil_risiko/upload_file_spi/' . $row->id_monitoring) ?>" method="post" enctype="multipart/form-data">
                                                        <div class="form-group">
                                                            <label>Pilih Jenis File</label><br>
                                                            <?php if (empty($row->file_document)) : ?>
                                                                <input type="radio" name="file_type" value="document" required> Dokumen (pdf)
                                                            <?php endif; ?>
                                                        </div>
                                                        <small class="text-danger">Ukuran file maksimal 2 MB</small>
                                                        <br>
                                                        <small class="text-danger">hanya bisa upload 1 file saja</small>
                                                        <div class="form-group">
                                                            <label>Pilih File</label>
                                                            <input type="file" name="file_upload" class="form-control" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary mt-3">Upload</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>x`
                                    </div>
                                    <!-- Modal Detail -->
                                    <div id="detailModalSpi<?= $row->id_monitoring ?>" class="modal fade" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-xl" role="document" style="max-width:1300px; width:100%; ">
                                            <div class="modal-content">
                                                <div class="modal-header" style="padding:1rem 2rem;">
                                                    <h5 class="modal-title">Detail File Pendukung</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-header" style="padding:0.5rem 2rem;">
                                                    <h6 class="text-muted">Terakhir Diupdate: <?= $row->modified_at ?></h6>
                                                    <h6 class="text-muted">Petugas Edit: <?= $row->modified_by ?></h6>
                                                </div>
                                                <div class="modal-body" style="padding:1rem 2rem;">
                                                    <div class="row">
                                                        <?php if (!empty($row->file_document)) : ?>
                                                            <div class="col-md-12 text-center mb-3">
                                                                <p><strong>Dokumen (PDF):</strong></p>
                                                                <iframe src="<?= base_url('uploads/spi/' . $row->file_document) ?>" width="100%" height="800px" class="border" style="border:1px solid #ccc;"></iframe>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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