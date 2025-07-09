<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <form method="get" action="<?= base_url('spi/aspek_admin'); ?>" class="form-inline ">
                        <div class="form-group mr-2">
                            <label>UPK:</label>
                            <select name="id_upk" class="form-control ml-2" required>
                                <option value="">Pilih UPK</option>
                                <?php foreach ($unit_list as $unit) : ?>
                                    <option value="<?= $unit->id_bagian ?>" <?= isset($filter['id_upk']) && $filter['id_upk'] == $unit->id_bagian ? 'selected' : '' ?>>
                                        <?= $unit->nama_bagian ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <label>Bulan :</label>
                            <select name="bulan" class="form-control ml-2">
                                <option value="">Pilih Bulan</option>
                                <?php for ($i = 1; $i <= 12; $i++) : ?>
                                    <option value="<?= $i ?>" <?= ($bulan == $i) ? 'selected' : '' ?>>
                                        <?= $nama_bulan_lengkap[$i] ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <label>Tahun:</label>
                            <input type="number" name="tahun" class="form-control ml-2" value="<?= isset($filter['tahun']) ? $filter['tahun'] : date('Y') ?>" required>
                        </div>
                        <button type="submit" class="neumorphic-button">Tampilkan</button>
                    </form>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('spi/aspek_admin/input_admin') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Aspek Administrasi</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('spi/aspek_admin/cetak_admin') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>

                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title); ?></h5>
                        <?php if (!empty($nama_upk)) : ?>
                            <h5>UPK : <?= strtoupper($nama_upk->nama_bagian); ?> <?= strtoupper($nama_bulan_terpilih); ?> <?= $tahun; ?></h5>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Indikator</th>
                                <th>Hasil</th>
                                <th>Nilai</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($admin_list)) : ?>
                                <?php foreach ($admin_list as $i => $row) : ?>
                                    <tr>
                                        <td class="text-center"><?= $i + 1; ?></td>
                                        <td><?= $row->indikator; ?></td>
                                        <td><?= $row->hasil; ?></td>
                                        <td class="text-center"><?= $row->skor; ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('spi/aspek_admin/edit_admin?id_upk=' . $nama_upk->id_bagian . '&bulan=' . $bulan . '&tahun=' . $tahun); ?>" class="btn btn-sm  neumorphic-button"><i class="fas fa-edit"></i> Edit</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center">Silakan pilih UPK, bulan, dan tahun untuk melihat data.</td>
                                </tr>
                            <?php endif; ?>
                            <?php
                            $total = 0;
                            foreach ($admin_list as $row) {
                                $total += $row->skor;
                            }
                            ?>
                            <tr>
                                <td colspan="3" class="text-right font-weight-bold">Total Skor Administrasi</td>
                                <td class="text-center font-weight-bold"><?= $total; ?></td>
                                <td></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>