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
                <th>No</th>
                <th>Uraian</th>
                <th>Harga Perolehan</th>
                <th>Akm Penyusutan</th>
                <th>Nilai Buku</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total_rupiah = 0;
            foreach ($susut as $row) :
            ?>
                <tr>
                    <td style="text-align: center;"><?= $no++; ?></td>
                    <td style="text-align: left;"><?= $row->nama_asset; ?></td>
                    <td style="text-align: right;"><?= number_format($row->rupiah * -1, 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($row->akm_thn_ini * -1, 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($row->nilai_buku_final * -1, 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="text-center bg-light">
                <th colspan="2" style="text-align: right;">Total</th>
                <th style="text-align: right;"><?= number_format($totals['total_rupiah'] * -1, 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_akm_thn_ini'] * -1, 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_nilai_buku_final'] * -1, 0, ',', '.'); ?></th>
            </tr>
        </tfoot>
    </table>

    <script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>