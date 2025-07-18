<section class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('info'); ?>
        <?= $this->session->unset_userdata('info'); ?>
        <div class="card">
            <div class="card-header card-outline card-primary shadow">
                <a class="fw-bold text-dark" style="text-decoration:none ;"><?= strtoupper($title) ?></a>
                <a href="<?= base_url('arsip'); ?>"><button class=" neumorphic-button float-right"><i class="fas fa-reply"></i> Kembali</button></a>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="card shadow p-4">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Jenis Arsip</td>
                                        <td> : </td>
                                        <td class="fw-bold text-primary text-uppercase"><?= $detail_arsip->jenis; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tahun</td>
                                        <td> : </td>
                                        <td class="fw-bold"><?= $detail_arsip->tahun; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Nama Arsip</td>
                                        <td> : </td>
                                        <td class="fw-bold"><?= $detail_arsip->nama_dokumen; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tentang</td>
                                        <td> : </td>
                                        <td class="fw-bold"><?= $detail_arsip->tentang; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Dokumen</td>
                                        <td> : </td>
                                        <td class="fw-bold"><?= $detail_arsip->tgl_dokumen; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Upload </td>
                                        <td> : </td>
                                        <td class="fw-bold"><?= $detail_arsip->created_at; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Petugas Upload </td>
                                        <td> : </td>
                                        <td class="fw-bold"><?= $detail_arsip->created_by; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Update </td>
                                        <td> : </td>
                                        <td class="fw-bold"><?= $detail_arsip->modified_at; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Petugas Update </td>
                                        <td> : </td>
                                        <td class="fw-bold"><?= $detail_arsip->modified_by; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Folder</td>
                                        <td> : </td>
                                        <td class="fw-bold"><?= $detail_arsip->nama_folder; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>