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
            font-size: 0.8rem;
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
        <p class="my-0 text-center fw-bold"><?= strtoupper($title) . ' TAHUN  ' . $tahun_lap; ?></p>
    </div>
    <table class="table tableUtama">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>UPK</th>
                <th>Jumlah SR</th>
                <th>Jumlah Di cek</th>
                <th>Yang diatas 0,7</th>
                <th>Persentase</th>
                <th>Jumlah SR diatas 70</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total_sr = 0;
            $total_cek = 0;
            $total_07 = 0;
            $total_persentase = 0;
            $total_sr_70 = 0;

            foreach ($tekanan_air as $row) :
                $jumlah_cek = $row->jumlah_cek;
                $jumlah_07 = $row->jumlah_07;
                $persentase = ($jumlah_07 / $jumlah_cek) * 100;
                $persentase = number_format($persentase, 2);

                $total_sr += $row->jumlah_sr;
                $total_cek += $row->jumlah_cek;
                $total_07 += $row->jumlah_07;
                $total_persentase += $persentase;
                $total_sr_70 += $row->jumlah_sr_70;
            ?>
                <tr>
                    <td style="text-align: right;"><?= $no++; ?></td>
                    <td><?= $row->nama_bagian; ?></td>
                    <td style="text-align: right;"><?= number_format($row->jumlah_sr, '0', ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($row->jumlah_cek, '0', ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($row->jumlah_07, '0', ',', '.'); ?></td>
                    <td style="text-align: right;"><?= $persentase ?></td>
                    <td style="text-align: right;"><?= number_format($row->jumlah_sr_70, '0', ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="bg-light">
                <th colspan="2" style="text-align: right;">Jumlah</th>
                <th style="text-align: right;"><?= number_format($total_sr, '0', ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_cek, '0', ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_07, '0', ',', '.'); ?></th>
                <th style="text-align: right;"></th>
                <th style="text-align: right;"><?= number_format($total_sr_70, '0', ',', '.'); ?></th>
            </tr>
        </tfoot>
    </table>

    <!-- <?php
            $nik_manager = $manager->nik_karyawan;
            if ($nik_manager) {
                $nik_manager =  sprintf('%03s %02s %03s', substr($nik_manager, 0, 3), substr($nik_manager, 3, 2), substr($nik_manager, 5));
            } else {
                $nik_manager = '';
            }
            ?> -->
    <!-- <div style="font-size: 0.8rem;" class="tandaTangan">
        <p style="width: 50%; float: right;text-align:center; margin-bottom: 1px;">Bondowoso, <?= $tanggal_hari_ini == 'Semua Piutang' ? $tanggal_hari_ini : date('d-m-Y');  ?></p>
        <div style="clear: both;"></div>
        <p style="width: 50%; float: right;text-align:center;">Manager AMDK</p>
        <div style="clear: both; margin-bottom:30px;"></div>
        <u style="width: 50%; float: right; text-align:center; margin-bottom: 1px;"><?= $manager->nama_karyawan; ?></u>
        <div style="clear: both;"></div>
        <p style="width: 50%; float: right; text-align:center;">NIK. <?= $nik_manager; ?></p>
        <div style="clear: both;"></div>
    </div> -->

    <script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>