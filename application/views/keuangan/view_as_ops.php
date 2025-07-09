<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('keuangan/aspek_ops') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('keuangan/aspek_ops'); ?>" method="get">
                        <div style="display: flex; align-items: center;">
                            <select id="tahun" name="tahun" class="form-control" style="margin-left: 15px;">
                                <?php
                                $currentYear = date('Y');
                                $selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear; // Memeriksa apakah ada tahun yang dipilih
                                for ($year = 1989; $year <= $currentYear; $year++) {
                                    $selected = ($year == $selectedYear) ? 'selected' : ''; // Menandai tahun yang dipilih
                                    echo "<option value='$year' $selected>$year</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </form>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('keuangan/aspek_ops/input_as_ops') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Aspek Operasional</button></a>
                    </div>
                    <div class="navbar-nav ms-2">
                        <a href="<?= base_url('keuangan/aspek_ops/input_as_adm') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Aspek Administrasi</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('keuangan/aspek_ops/cetak_as_ops') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>

                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title); ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Penilaian</th>
                                <th>Hasil</th>
                                <th>Ptgs Edit</th>
                                <th>Tgl Edit</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($as_ops  as $row) :
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><?= $row->penilaian; ?></td>
                                    <td><?= $row->hasil; ?></td>
                                    <td><?= $row->modified_by; ?></td>
                                    <td><?= $row->modified_at; ?></td>
                                    <td class="text-center"><a href="<?= base_url('keuangan/aspek_ops/edit_as_ops/' . $row->id_aspek_ops) ?>"><i class="fas fa-edit"></i></a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5><?= strtoupper($title2); ?></h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="contoh2" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Penilaian</th>
                                <th>Hasil</th>
                                <th>Ptgs Edit</th>
                                <th>Tgl Edit</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($as_adm  as $row) :
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><?= $row->penilaian; ?></td>
                                    <td><?= $row->hasil; ?></td>
                                    <td><?= $row->modified_by; ?></td>
                                    <td><?= $row->modified_at; ?></td>
                                    <td class="text-center"><a href="<?= base_url('keuangan/aspek_ops/edit_as_ops/' . $row->id_aspek_adm) ?>"><i class="fas fa-edit"></i></a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>