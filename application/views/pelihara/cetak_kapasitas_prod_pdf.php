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
                <th rowspan="2" class="align-middle">No</th>
                <th rowspan="2" class="align-middle">Nama Instalasi</th>
                <th rowspan="2" class="align-middle">Kap. Terpasang <br>/desain <br> (ltr/dtk)</th>
                <th colspan="2">Kapasitas Produksi</th>
                <th rowspan="2" class="align-middle">Kapasitas Riil <br> (M3)</th>
                <th rowspan="2" class="align-middle">Volume Produksi <br> (M3)</th>
                <th rowspan="2" class="align-middle">Kapasitas Menganggur <br> (M3)</th>
            </tr>
            <tr class="text-center">
                <th>Terpasang / desain<br>(M3)</th>
                <th>Tidak <br> dimanfaatkan <br>(M3)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total_kap_pasang = 0;
            $total_terpasang = 0;
            $total_tidak_manfaat = 0;
            $total_volume_produksi = 0;
            $total_kap_riil = 0;
            $total_kap_menganggur = 0;

            foreach ($kapasitas_produksi as $row) :
                $kap_pasang = $row->kap_pasang;
                $terpasang = $row->terpasang;
                $tidak_manfaat = $row->tidak_manfaat;
                $volume_produksi = $row->volume_produksi;
                $kap_riil = $terpasang - $tidak_manfaat;
                $kap_menganggur = $kap_riil - $volume_produksi;

                $total_kap_pasang += $kap_pasang;
                $total_terpasang += $terpasang;
                $total_tidak_manfaat += $tidak_manfaat;
                $total_volume_produksi += $volume_produksi;
                $total_kap_riil += $kap_riil;
                $total_kap_menganggur += $kap_menganggur;
            ?>
                <tr>
                    <td style="text-align: center ;"><?= $no++; ?></td>
                    <td><?= $row->nama_bagian; ?></td>
                    <td style="text-align: right;"><?= number_format($row->kap_pasang, 1, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($row->terpasang, 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($row->tidak_manfaat, 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($kap_riil, 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($volume_produksi, 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($kap_menganggur, 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="bg-light">
                <th colspan="2" style="text-align: center ;">Jumlah</th>
                <th style="text-align: right;"><?= number_format($total_kap_pasang, 1, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_terpasang, 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tidak_manfaat, 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kap_riil, 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_volume_produksi, 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kap_menganggur, 0, ',', '.'); ?></th>
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