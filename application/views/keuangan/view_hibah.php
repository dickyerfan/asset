<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">
                    <a href="<?= base_url('keuangan/hibah') ?>"><button class="neumorphic-button">Tahun ini</button></a>
                    <form id="form_tahun" action="<?= base_url('keuangan/hibah'); ?>" method="get">
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
                    <?php if ($this->session->userdata('bagian') == 'Keuangan') { ?>
                        <div class="navbar-nav ms-2">
                            <a href="<?= base_url('keuangan/hibah/input_hibah') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Input Hibah</button></a>
                        </div>
                    <?php } ?>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('keuangan/hibah/cetak_hibah') ?>" target="_blank"><button class="float-end neumorphic-button"><i class="fas fa-print"></i> Cetak PDF</button></a>

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
                                <th>Uraian</th>
                                <th>Lokasi</th>
                                <th>Tahun</th>
                                <th>Nilai (Rp)</th>
                                <th>Sumber Dana</th>
                                <th>Unit Pemberi</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total_rupiah = 0;
                            foreach ($hibah as $row) :
                                $total_rupiah += $row->rupiah;
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><?= nl2br(htmlspecialchars($row->uraian)); ?></td>
                                    <td><?= $row->nama_kec; ?></td>
                                    <td class="text-center"><?= $row->tahun_data; ?></td>
                                    <td class="text-right"><?= number_format($row->rupiah, 0, ',', '.'); ?></td>
                                    <td><?= $row->sumber_dana; ?></td>
                                    <td><?= $row->unit_pemberi; ?></td>
                                    <td><?= $row->keterangan; ?></td>
                                    <td>
                                        <?php if ($this->session->userdata('bagian') == 'Keuangan' or $this->session->userdata('bagian') == 'Administrator') { ?>
                                            <div class="text-center">
                                                <a href="<?= base_url('keuangan/hibah/edit_hibah/' . $row->id_hibah) ?>"><i class="fas fa-edit"></i></a>
                                            </div>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-center">Jumlah</th>
                                <th class="text-right"><?= number_format($total_rupiah, 0, ',', '.'); ?></th>
                                <th colspan="4"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>