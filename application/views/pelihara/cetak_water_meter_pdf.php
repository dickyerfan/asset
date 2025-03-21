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
        <p class="my-0 text-center fw-bold"><?= strtoupper($title) . '  ' . $tahun_lap; ?></p>
    </div>
    <table class="table tableUtama">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>UPK</th>
                <th>Jan</th>
                <th>Feb</th>
                <th>Mar</th>
                <th>Apr</th>
                <th>Mei</th>
                <th>Jun</th>
                <th>Jul</th>
                <th>Ags</th>
                <th>Sep</th>
                <th>Okt</th>
                <th>Nov</th>
                <th>Des</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total_jan = $total_feb = $total_mar = $total_apr = 0;
            $total_mei = $total_jun = $total_jul = $total_agu = 0;
            $total_sep = $total_okt = $total_nov = $total_des = $total_semua = 0;
            foreach ($tera_meter as $row) :
                $total_jan += $row->jan;
                $total_feb += $row->feb;
                $total_mar += $row->mar;
                $total_apr += $row->apr;
                $total_mei += $row->mei;
                $total_jun += $row->jun;
                $total_jul += $row->jul;
                $total_agu += $row->agu;
                $total_sep += $row->sep;
                $total_okt += $row->okt;
                $total_nov += $row->nov;
                $total_des += $row->des;
                $total_semua += $row->total;
            ?>
                <tr>
                    <td style="text-align: center;"><?= $no++; ?></td>
                    <td><?= $row->nama_bagian; ?></td>
                    <td style="text-align: center;"><?= $row->jan; ?></td>
                    <td style="text-align: center;"><?= $row->feb; ?></td>
                    <td style="text-align: center;"><?= $row->mar; ?></td>
                    <td style="text-align: center;"><?= $row->apr; ?></td>
                    <td style="text-align: center;"><?= $row->mei; ?></td>
                    <td style="text-align: center;"><?= $row->jun; ?></td>
                    <td style="text-align: center;"><?= $row->jul; ?></td>
                    <td style="text-align: center;"><?= $row->agu; ?></td>
                    <td style="text-align: center;"><?= $row->sep; ?></td>
                    <td style="text-align: center;"><?= $row->okt; ?></td>
                    <td style="text-align: center;"><?= $row->nov; ?></td>
                    <td style="text-align: center;"><?= $row->des; ?></td>
                    <td style="text-align: center;"><?= $row->total; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="bg-light">
                <th class="text-center" colspan="2">Jumlah</th>
                <th class="text-center"><?= $total_jan; ?></th>
                <th class="text-center"><?= $total_feb; ?></th>
                <th class="text-center"><?= $total_mar; ?></th>
                <th class="text-center"><?= $total_apr; ?></th>
                <th class="text-center"><?= $total_mei; ?></th>
                <th class="text-center"><?= $total_jun; ?></th>
                <th class="text-center"><?= $total_jul; ?></th>
                <th class="text-center"><?= $total_agu; ?></th>
                <th class="text-center"><?= $total_sep; ?></th>
                <th class="text-center"><?= $total_okt; ?></th>
                <th class="text-center"><?= $total_nov; ?></th>
                <th class="text-center"><?= $total_des; ?></th>
                <th class="text-center font-weight-bold"><?= $total_semua; ?></th>
            </tr>
        </tfoot>
    </table>
    <div class="judul">
        <p class="my-0 text-center fw-bold"><?= strtoupper($title2) . ' ' . $tahun_lap; ?></p>
    </div>
    <table class="table tableUtama">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>UPK</th>
                <th>Jan</th>
                <th>Feb</th>
                <th>Mar</th>
                <th>Apr</th>
                <th>Mei</th>
                <th>Jun</th>
                <th>Jul</th>
                <th>Ags</th>
                <th>Sep</th>
                <th>Okt</th>
                <th>Nov</th>
                <th>Des</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total_jan = $total_feb = $total_mar = $total_apr = 0;
            $total_mei = $total_jun = $total_jul = $total_agu = 0;
            $total_sep = $total_okt = $total_nov = $total_des = $total_semua = 0;
            foreach ($ganti_meter as $row) :
                $total_jan += $row->jan;
                $total_feb += $row->feb;
                $total_mar += $row->mar;
                $total_apr += $row->apr;
                $total_mei += $row->mei;
                $total_jun += $row->jun;
                $total_jul += $row->jul;
                $total_agu += $row->agu;
                $total_sep += $row->sep;
                $total_okt += $row->okt;
                $total_nov += $row->nov;
                $total_des += $row->des;
                $total_semua += $row->total;
            ?>
                <tr>
                    <td style="text-align: center;"><?= $no++; ?></td>
                    <td><?= $row->nama_bagian; ?></td>
                    <td style="text-align: center;"><?= $row->jan; ?></td>
                    <td style="text-align: center;"><?= $row->feb; ?></td>
                    <td style="text-align: center;"><?= $row->mar; ?></td>
                    <td style="text-align: center;"><?= $row->apr; ?></td>
                    <td style="text-align: center;"><?= $row->mei; ?></td>
                    <td style="text-align: center;"><?= $row->jun; ?></td>
                    <td style="text-align: center;"><?= $row->jul; ?></td>
                    <td style="text-align: center;"><?= $row->agu; ?></td>
                    <td style="text-align: center;"><?= $row->sep; ?></td>
                    <td style="text-align: center;"><?= $row->okt; ?></td>
                    <td style="text-align: center;"><?= $row->nov; ?></td>
                    <td style="text-align: center;"><?= $row->des; ?></td>
                    <td style="text-align: center;"><?= $row->total; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="bg-light">
                <th class="text-center" colspan="2">Jumlah</th>
                <th class="text-center"><?= $total_jan; ?></th>
                <th class="text-center"><?= $total_feb; ?></th>
                <th class="text-center"><?= $total_mar; ?></th>
                <th class="text-center"><?= $total_apr; ?></th>
                <th class="text-center"><?= $total_mei; ?></th>
                <th class="text-center"><?= $total_jun; ?></th>
                <th class="text-center"><?= $total_jul; ?></th>
                <th class="text-center"><?= $total_agu; ?></th>
                <th class="text-center"><?= $total_sep; ?></th>
                <th class="text-center"><?= $total_okt; ?></th>
                <th class="text-center"><?= $total_nov; ?></th>
                <th class="text-center"><?= $total_des; ?></th>
                <th class="text-center font-weight-bold"><?= $total_semua; ?></th>
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