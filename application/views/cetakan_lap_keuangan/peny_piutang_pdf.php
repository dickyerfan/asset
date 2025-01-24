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
        <h5>Penyisihan Kerugian Piutang Usaha<br>Daftar Mutasi Piutang Air 3 Tahun Terakhir</h5>
    </div>
    <table class="table tableUtama">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Uraian</th>
                <th>Saldo Awal</th>
                <th>Tambah</th>
                <th>Kurang</th>
                <th>Saldo Akhir</th>
                <th>Persen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($piutang as $year => $rows) : ?>
                <tr>
                    <td colspan="7" style="text-align: left; background-color: gray">
                        DATA TAHUN <?= $year; ?>
                    </td>
                </tr>
                <?php
                $no = 1;
                foreach ($rows as $row) :
                ?>
                    <tr>
                        <td style="text-align: center;"><?= $no++; ?></td>
                        <td style="text-align: left;"><?= $row->kel_tarif_ket; ?></td>
                        <td style="text-align: right;"><?= number_format($row->saldo_awal, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($row->tambah, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($row->kurang, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($row->saldo_akhir, 0, ',', '.'); ?></td>
                        <td style="text-align: center;"><?= $row->persen_tagih; ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (isset($totals[$year])) : ?>
                    <tr style="background-color: lightgray;">
                        <td></td>
                        <td style="text-align: left;">JUMLAH</td>
                        <td style="text-align: right;"><?= number_format($totals[$year]['total_saldo_awal'], 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($totals[$year]['total_tambah'], 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($totals[$year]['total_kurang'], 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($totals[$year]['total_saldo_akhir'], 0, ',', '.'); ?></td>
                        <td style="text-align: center;"><?= number_format($totals[$year]['rata_rata_persen_tagih'], 2); ?>%</td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>