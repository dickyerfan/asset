<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset | <?= $title; ?></title>
    <link href="<?= base_url(); ?>assets/datatables/bootstrap5/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        main {
            font-size: 0.9rem;
        }

        .header p {
            margin: 0;
            font-size: 0.7rem;
            /* Menghilangkan margin pada teks */
        }

        .tandaTangan p {
            font-size: 0.7rem;
        }

        .header img {
            margin-right: 10px;
        }

        .tableUtama {
            border-collapse: collapse;
            /* Agar garis tabel menyatu */
            width: 100%;
            /* Sesuaikan dengan lebar halaman */
        }

        .tableUtama th,
        .tableUtama td {
            border: 1px solid black;
            font-size: 0.75rem;
            padding: 3px 3px;
            /* text-align: end; */
        }

        .judul p {
            margin-bottom: 5px;
            font-size: 1rem;
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <table>
            <tbody class="text_center">
                <tr>
                    <td width="10%">
                        <img src="<?= base_url('assets/img/pdam_biru.png'); ?>" alt="Logo" width="40">
                    </td>
                    <td>
                        <p>Perusahaan Daerah Air Minum </p>
                        <p>Kabupaten Bondowoso</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="judul">
        <p class="my-2 text-center fw-bold"><?= strtoupper($title); ?></p>
    </div>
    <table class="table tableUtama">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Indikator</th>
                <th>Keberadaan</th>
                <th>Kondisi</th>
                <th>Skor</th>
            </tr>
        </thead>
        <tbody>
            <?php $no_teknis = 1;
            foreach ($detail_teknis as $teknis) : ?>
                <tr>
                    <td style="text-align: center;"><?= $no_teknis++; ?></td>
                    <td><?= $teknis->indikator; ?></td>
                    <td style="text-align: center;"><?= $teknis->keberadaan; ?></td>
                    <td style="text-align: center;"><?= $teknis->kondisi; ?></td>
                    <td style="text-align: center;"><?= $teknis->skor; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Total Skor Teknis :</th>
                <th class="text-center"><?= $total_skor_teknis; ?></th>
            </tr>
        </tfoot>
    </table>
    <br>
    <table class="table tableUtama">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Indikator</th>
                <th>Hasil</th>
                <th>Skor</th>
            </tr>
        </thead>
        <tbody>
            <?php $no_admin = 1;
            foreach ($detail_admin as $admin) : ?>
                <tr>
                    <td style="text-align: center;"><?= $no_admin++; ?></td>
                    <td><?= $admin->indikator; ?></td>
                    <td style="text-align: center;"><?= $admin->hasil; ?></td>
                    <td style="text-align: center;"><?= $admin->skor; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total Skor Administrasi :</th>
                <th class="text-center"><?= $total_skor_admin; ?></th>
            </tr>
        </tfoot>
    </table>
    <br>
    <table class="table tableUtama">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Indikator</th>
                <th>Hasil</th>
                <th>Skor</th>
            </tr>
        </thead>
        <tbody>
            <?php $no_koordinasi = 1;
            foreach ($detail_koordinasi as $koordinasi) : ?>
                <tr>
                    <td style="text-align: center;"><?= $no_koordinasi++; ?></td>
                    <td><?= $koordinasi->indikator; ?></td>
                    <td style="text-align: center;"><?= $koordinasi->hasil; ?></td>
                    <td style="text-align: center;"><?= $koordinasi->skor; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total Skor Koordinasi :</th>
                <th class="text-center"><?= $total_skor_koordinasi; ?></th>
            </tr>
        </tfoot>
    </table>
    <br>
    <table class="table tableUtama">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Temuan</th>
                <th>Rekomendasi</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php $no_tl = 1;
            foreach ($detail_tindak_lanjut as $tl) : ?>
                <tr>
                    <td class="text-center"><?= $no_tl++; ?></td>
                    <td><?= $tl->temuan; ?></td>
                    <td class="text-center"><?= $tl->rekomendasi; ?></td>
                    <td class="text-center"><?= $tl->keterangan; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="font-size: 0.8rem;" class="tandaTangan">
        <p style="width: 50%; float: right;text-align:center; margin-bottom: 1px;">Bondowoso, <?= date('d-m-Y');  ?></p>
        <div style="clear: both;"></div>
        <p style="width: 50%; float: right;text-align:center;">KETUA S P I</p>
        <div style="clear: both; margin-bottom:30px;"></div>
        <u style="width: 50%; float: right; text-align:center; margin-bottom: 1px;">I MADE SUARJAYA</u>
        <div style="clear: both;"></div>
        <p style="width: 50%; float: right; text-align:center;">NIK. 112 92 083</p>
        <div style="clear: both;"></div>
    </div>
    <script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>