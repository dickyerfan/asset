<section class="content">
    <div class="container-fluid">
        <div class="p-2">
            <?= $this->session->flashdata('info'); ?>
            <?= $this->session->unset_userdata('info'); ?>
        </div>
        <div class="card">
            <div class="card-header card-outline card-primary">
                <nav class="navbar ">

                    <div class="navbar-nav">
                        <a href="<?= base_url('arsip/tambah_folder') ?>"><button class="float-end neumorphic-button"><i class="fas fa-plus"></i> Tambah Folder</button></a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a href="<?= base_url('arsip') ?>"><button class="float-end neumorphic-button"><i class="fas fa-reply"></i> Kembali ke Arsip</button></a>
                    </div>
                </nav>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h5 class="font-weight-bold"><?= strtoupper($title); ?></h5>
                    </div>
                </div>
                <div class="table-responsive" style="font-size: 0.7rem;">
                    <table id="contoh" class="table table-hover table-striped table-bordered table-sm" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-secondary">
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Folder</th>
                                <th class="text-center">Ptgs Upload</th>
                                <th class="text-center">Tgl Upload</th>
                                <th class="text-center">Ptgs Update</th>
                                <th class="text-center">Tgl Update</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($folder_list as $row) :
                            ?>
                                <tr>
                                    <td class="text-center"><small><?= $no++ ?></small></td>
                                    <td><?= $row->nama_folder; ?></td>
                                    <td class="text-center"><?= $row->created_by; ?></td>
                                    <td class="text-center"><?= date('d-m-Y ', strtotime($row->created_at)); ?></td>
                                    <td class="text-center"><?= $row->modified_by; ?></td>
                                    <td class="text-center">
                                        <?= $row->modified_at ? date('d-m-Y', strtotime($row->modified_at)) : ' '; ?>
                                    </td>

                                    <td class="text-center">
                                        <a href="<?= base_url('arsip/edit_folder/' . $row->id_folder); ?>"><i class=" fas fa-fw fa-edit" data-bs-toggle="tooltip" title="Klik untuk Edit Data"></i></a>
                                        <!-- <a href="<?= $this->session->userdata('bagian') == 'Administrator' ? base_url('arsip/hapus_folder/' . $row->id_folder) : '#' ?>" class="tombolHapus"><i class="fas fa-fw fa-trash" style="color: red;" data-bs-toggle="tooltip" title="Klik untuk Hapus Data"></i></a> -->
                                    </td>
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
</main>