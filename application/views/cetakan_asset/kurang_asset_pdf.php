<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset| <?= $title; ?></title>
    <link href="<?= base_url(); ?>assets/datatables/bootstrap5/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        main {
            font-size: 0.8rem;
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

        /* hr {
            height: 0.3px;
            background-color: black !important;
            margin-top: 2px;
            width: 200px;
            text-align: start;
        } */

        .tableUtama {
            border-collapse: collapse;
            /* Agar garis tabel menyatu */
            width: 100%;
            /* Sesuaikan dengan lebar halaman */
        }

        .tableUtama th,
        .tableUtama td {
            border: 1px solid black;
            font-size: 0.6rem;
            padding: 1.5px 3px;
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
        <p class="my-0 text-center fw-bold"><?= strtoupper($title) . ' ' . $tahun_lap; ?></p>
    </div>
    <table class="table tableUtama">
        <thead>
            <tr class="text-center">
                <th>Nama Uraian</th>
                <th>Lokasi</th>
                <th>Tanggal</th>
                <th>No Bukti</th>
                <th>Rupiah</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($asset as $group) : ?>
                <tr style="background-color: lightgrey;">
                    <td colspan="4">
                        <strong> <?= $group['name']; ?></strong>
                    </td>
                    <td style="text-align: right;">
                        <strong><?= number_format($group['total_rupiah_perkiraan'] * -1, 0, ',', '.'); ?></strong>
                    </td>
                </tr>
                <?php foreach ($group['items'] as $row) : ?>
                    <tr>
                        <td style="text-align: left;">
                            <?php
                            $nama_asset = $row->nama_asset;
                            if (strlen($nama_asset) > 75) {
                                $nama_asset = substr($nama_asset, 0, 75) . '...';
                            }
                            ?>
                            <?= $nama_asset; ?>
                        </td>
                        <td style="text-align: center;">
                            <?php if ($row->id_bagian == 2) : ?>
                                <?= 'Kantor Pusat'; ?>
                            <?php else : ?>
                                <?= $row->nama_bagian; ?>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;"><?= date('d-m-Y', strtotime($row->tanggal)); ?></td>
                        <td><?= $row->no_bukti_vch; ?></td>
                        <td style="text-align: right;"><?= number_format($row->rupiah * -1, 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="text-center bg-light" style="background-color: lightgrey;">
                <th></th>
                <th></th>
                <th></th>
                <th>Jumlah</th>
                <th style="text-align: right;"><strong><?= number_format($total_rupiah * -1, 0, ',', '.'); ?></strong></th>
            </tr>
        </tfoot>
    </table>
    <script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>