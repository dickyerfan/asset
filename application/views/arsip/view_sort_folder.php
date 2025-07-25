<section class="content">
    <div class="p-2">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
    </div>
    <div class="card">
        <div class="card-header card-outline card-primary">
            <nav class="navbar ">
                <div class="navbar-nav ms-auto">
                    <a href="<?= base_url('arsip'); ?>"><button class="neumorphic-button float-end"><i class="fas fa-reply"></i> Kembali</button></a>
                </div>
            </nav>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <h5 class="font-weight-bold"><?= strtoupper($title); ?></h5>
                </div>
            </div>
            <div class="accordion" id="accordionFolder">
                <?php
                $folderIndex = 0;
                foreach ($arsip_nested as $nama_folder => $jenis_data) :
                    $folderIndex++;
                    $folderCollapseID = 'collapseFolder' . $folderIndex;
                ?>
                    <div class="card mb-2">
                        <div class="card-header bg-primary text-white" id="heading<?= $folderIndex ?>">
                            <h6 class="mb-0">
                                <button class="btn btn-link text-white" style="text-decoration: none; font-size: 1.1rem; font-weight: bold" type="button" data-toggle="collapse" data-target="#<?= $folderCollapseID ?>" aria-expanded="true" aria-controls="<?= $folderCollapseID ?>">
                                    <?= strtoupper($nama_folder); ?>
                                </button>
                            </h6>
                        </div>

                        <div id="<?= $folderCollapseID ?>" class="collapse" aria-labelledby="heading<?= $folderIndex ?>" data-parent="#accordionFolder">
                            <div class="card-body">
                                <div class="accordion" id="accordionJenis<?= $folderIndex ?>">
                                    <?php
                                    $jenisIndex = 0;
                                    foreach ($jenis_data as $jenis => $arsip_list) :
                                        $jenisIndex++;
                                        $jenisCollapseID = 'collapseJenis' . $folderIndex . '_' . $jenisIndex;
                                    ?>
                                        <div class="card mb-2">
                                            <div class="card-header bg-success text-white" id="headingJenis<?= $folderIndex ?>_<?= $jenisIndex ?>">
                                                <h6 class="mb-0">
                                                    <button class="btn btn-link text-white" style="text-decoration: none;" type="button" data-toggle="collapse" data-target="#<?= $jenisCollapseID ?>" aria-expanded="false" aria-controls="<?= $jenisCollapseID ?>">
                                                        <?= strtoupper($jenis); ?>
                                                    </button>
                                                </h6>
                                            </div>

                                            <div id="<?= $jenisCollapseID ?>" class="collapse" aria-labelledby="headingJenis<?= $folderIndex ?>_<?= $jenisIndex ?>" data-parent="#accordionJenis<?= $folderIndex ?>">
                                                <div class="card-body">
                                                    <table class="table table-bordered table-sm" style="font-size: 0.8rem;">
                                                        <thead>
                                                            <tr class="bg-light">
                                                                <th class="text-center">No</th>
                                                                <th class="text-center" width="4%">Aksi</th>
                                                                <th class="text-center">Jenis</th>
                                                                <th class="text-center">Tahun</th>
                                                                <th class="text-center">Nama Dokumen</th>
                                                                <th class="text-center">Tentang</th>
                                                                <th class="text-center">Ptgs Upload</th>
                                                                <th class="text-center">Tgl Upload</th>
                                                                <th class="text-center">Ptgs Update</th>
                                                                <th class="text-center">Tgl Update</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $no = 1;
                                                            foreach ($arsip_list as $arsip) : ?>
                                                                <tr>
                                                                    <td class="text-center"><?= $no++ ?></td>
                                                                    <td class="text-center">
                                                                        <a href="<?= base_url('arsip/detail/' . $arsip->id_arsip); ?>"><i class="fas fa-info-circle text-dark"></i></a>
                                                                        <a href="<?= base_url('arsip/download/' . $arsip->id_arsip); ?>"><i class="fas fa-download text-success"></i></a>
                                                                        <a href="<?= base_url('arsip/baca/' . $arsip->id_arsip); ?>" target="_blank"><i class="fas fa-book-open text-warning"></i></a>
                                                                    </td>
                                                                    <td><?= $arsip->jenis ?></td>
                                                                    <td class="text-center"><?= $arsip->tahun ?></td>
                                                                    <td><?= $arsip->nama_dokumen ?></td>
                                                                    <td><?= $arsip->tentang ?></td>
                                                                    <td><?= $arsip->created_by; ?></td>
                                                                    <td><?= date('d-m-Y ', strtotime($arsip->created_at)); ?></td>
                                                                    <td class="text-center">
                                                                        <?= $arsip->modified_by ? $arsip->modified_by : '-'; ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?=
                                                                        ($arsip->modified_at && $arsip->modified_at !== '0000-00-00 00:00:00')
                                                                            ? date('d-m-Y', strtotime($arsip->modified_at))
                                                                            : '-'
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
</div>