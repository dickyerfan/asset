<section class="content">
    <div class="container-fluid mt-2">
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <h3 class="card-title font-weight-bold mt-2"><?= strtoupper($title)  ?></h3>
                <a href="<?= base_url('user/admin/tambah'); ?>"><button class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Tambah User</button></a>
            </div>
            <div class="p-2">
                <?= $this->session->flashdata('info'); ?>
                <?= $this->session->unset_userdata('info'); ?>
            </div>
            <div class="card-body">
                <table id="contoh" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama Pengguna</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Level</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($user as $row) :
                        ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= $row->nama_pengguna ?></td>
                                <td><?= $row->nama_lengkap ?></td>
                                <td><?= $row->email ?></td>
                                <td><?= $row->level ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url(); ?>user/admin/edit/<?= $row->id; ?>"><span class="badge badge-primary"><i class="fas fa-fw fa-edit"></i> Edit</span></a>
                                    <a href="<?= base_url(); ?>user/admin/hapus/<?= $row->id; ?>" class="badge badge-danger"><i class="fas fa-fw fa-trash"></i> Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>


        </div>
    </div>

</section>
</div>