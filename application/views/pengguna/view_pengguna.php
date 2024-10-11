<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href=" <?= base_url('assets') ?>/plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href=" <?= base_url('assets') ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

    <link rel="stylesheet" href=" <?= base_url('assets') ?>/dist/css/adminlte.min.css?v=3.2.0">
</head>

<body>
    <section class="content">
        <div class="container mt-2">
            <div class="card shadow">
                <div class="card-header card-outline card-primary shadow">
                    <h3 class="card-title">Struktur Organisasi PDAM Bondowoso</h3>
                    <a class="btn btn-primary btn-sm float-right" href="<?= base_url('auth/logout') ?>" onclick="return confirm('Yakin Mau Logout.?')">Logout</a>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-3">
                            <div class="card card-outline card-primary shadow">
                                <div class="card-body" data-toggle="modal" data-target="#direktur">
                                    <h3 class="card-title">DIREKTUR</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-2">
                            <div class="card card-outline card-danger shadow">
                                <div class="card-body" data-toggle="modal" data-target="#spi">
                                    <h3 class="card-title">S P I</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-3">
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-6 text-center mb-3 text-uppercase">
                            <h5 class="font-weight-bold">Bagian</h5>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-2">
                            <div class="card card-outline card-success shadow">
                                <div class="card-body" data-toggle="modal" data-target="#langgan">
                                    <h3 class="card-title">Langganan</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card card-outline card-success shadow">
                                <div class="card-body" data-toggle="modal" data-target="#umum">
                                    <h3 class="card-title">U m u m</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card card-outline card-success shadow">
                                <div class="card-body" data-toggle="modal" data-target="#keu">
                                    <h3 class="card-title">Keuangan</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card card-outline card-success shadow">
                                <div class="card-body" data-toggle="modal" data-target="#renc">
                                    <h3 class="card-title">Perencanaan</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card card-outline card-success shadow">
                                <div class="card-body" data-toggle="modal" data-target="#peml">
                                    <h3 class="card-title">Pemeliharaan</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-6 text-center mt-4 mb-3 text-uppercase">
                            <h5 class="font-weight-bold">U P K / Unit Pelayanan Kecamatan</h5>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-2">
                            <div class="card card-outline card-warning shadow">
                                <div class="card-body" data-toggle="modal" data-target="#bondowoso">
                                    <h3 class="card-title">Bondowoso</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card card-outline card-warning shadow">
                                <div class="card-body" data-toggle="modal" data-target="#suko1">
                                    <h3 class="card-title">Sukosari 1</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card card-outline card-warning shadow">
                                <div class="card-body" data-toggle="modal" data-target="#maesan">
                                    <h3 class="card-title">Maesan</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card card-outline card-warning shadow">
                                <div class="card-body" data-toggle="modal" data-target="#tegalampel">
                                    <h3 class="card-title">Tegalampel</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card card-outline card-warning shadow">
                                <div class="card-body" data-toggle="modal" data-target="#tapen">
                                    <h3 class="card-title">Tapen</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card card-outline card-warning shadow">
                                <div class="card-body" data-toggle="modal" data-target="#prajekan">
                                    <h3 class="card-title">Prajekan</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card card-outline card-warning shadow">
                                <div class="card-body" data-toggle="modal" data-target="#tlogosari">
                                    <h3 class="card-title">Tlogosari</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card card-outline card-warning shadow">
                                <div class="card-body" data-toggle="modal" data-target="#wringin">
                                    <h3 class="card-title">Wringin</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card card-outline card-warning shadow">
                                <div class="card-body" data-toggle="modal" data-target="#curahdami">
                                    <h3 class="card-title">Curahdami</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card card-outline card-warning shadow">
                                <div class="card-body" data-toggle="modal" data-target="#tamanan">
                                    <h3 class="card-title">Tamanan</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card card-outline card-warning shadow">
                                <div class="card-body" data-toggle="modal" data-target="#tenggarang">
                                    <h3 class="card-title">Tenggarang</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card card-outline card-warning shadow">
                                <div class="card-body" data-toggle="modal" data-target="#tamankrocok">
                                    <h3 class="card-title">Tamankrocok</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card card-outline card-warning shadow">
                                <div class="card-body" data-toggle="modal" data-target="#Wonosari">
                                    <h3 class="card-title">Wonosari</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card card-outline card-warning shadow">
                                <div class="card-body" data-toggle="modal" data-target="#suko2">
                                    <h3 class="card-title">Sukosari 2</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card card-outline card-warning shadow">
                                <div class="card-body" data-toggle="modal" data-target="#amdk">
                                    <h3 class="card-title">A M D K</h3>
                                    <div class="card-tools">
                                        <span class="float-right"><i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal direktur -->
    <div class="modal fade" id="direktur" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-light">
                    <h5 class="modal-title" id="direktur">Direktur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Nama</td>
                                <td> : </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>NIK</td>
                                <td> : </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td> : </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    </div>
    </section>

    <script src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>

    <script src="<?= base_url('assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="<?= base_url('assets') ?>/dist/js/adminlte.min.js?v=3.2.0"></script>
</body>

</html>