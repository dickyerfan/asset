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
    <div class="container my-4">
        <h5 class="font-weight-bold">CAKUPAN PELAYANAN ADMINISTRATIF</h5>
        <table class="table table-sm table-borderless">
            <tbody>
                <tr>
                    <td colspan="3"><strong>Wilayah Administratif</strong></td>
                </tr>
                <tr>
                    <td>Jumlah penduduk</td>
                    <td> : </td>
                    <td style="text-align: right;"><?= number_format($cakupan['total_penduduk'] ?? 0, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Jumlah KK</td>
                    <td> : </td>
                    <td style="text-align: right;"><?= number_format($cakupan['total_kk'] ?? 0, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Rata - rata Jiwa per RT</td>
                    <td> : </td>
                    <td style="text-align: right;"><?= number_format($cakupan['rata_jiwa_kk'] ?? 0, 6, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Yang dilayani air bersih</td>
                    <td> : </td>
                    <td style="text-align: right;"><?= number_format($cakupan['jumlah_wil_layan_ya'] ?? 0, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Jumlah Kec di Wilayah Administratif</td>
                    <td> : </td>
                    <td style="text-align: right;"><?= number_format($cakupan['total_wil_layan_semua'] ?? 0, 0, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>

        <?php
        $total_pelanggan =
            ($pelanggan['total_rt_dom'] ?? 0) +
            ($pelanggan['total_niaga_dom'] ?? 0) +
            ($pelanggan['total_sl_hu_dom'] ?? 0) +
            ($pelanggan['total_n_aktif_dom'] ?? 0);

        $rata_jiwa = $cakupan['rata_jiwa_kk'] ?? 0;
        $total_jiwa_terlayani = $pelanggan['total_rt_dom'] * $rata_jiwa + $pelanggan['total_niaga_dom'] * $rata_jiwa + $pelanggan['total_sl_hu_dom'] * 100 + $pelanggan['total_n_aktif_dom'] * $rata_jiwa;
        $cakupan_admin = ($cakupan['total_penduduk'] ?? 0) > 0
            ? ($total_jiwa_terlayani / $cakupan['total_penduduk']) * 100
            : 0;

        $rata_jiwa2 = $cakupan['rata_jiwa_kk2'] ?? 0;
        $total_jiwa_terlayani2 = $pelanggan['total_rt_dom'] * $rata_jiwa2 + $pelanggan['total_niaga_dom'] * $rata_jiwa2 + $pelanggan['total_sl_hu_dom'] * 100 + $pelanggan['total_n_aktif_dom'] * $rata_jiwa2;

        $cakupan_teknis = ($cakupan['total_penduduk'] ?? 0) > 0
            ? ($total_jiwa_terlayani2 / $cakupan['total_wil_layan']) * 100
            : 0;
        ?>

        <table class="table tableUtama">
            <thead class="thead-light text-center">
                <tr>
                    <th rowspan="2" class="align-middle">Pelanggan Domestik</th>
                    <th colspan="3">Jumlah</th>
                </tr>
                <tr>
                    <th>Pelanggan</th>
                    <th>Jiwa Rata2</th>
                    <th>Jiwa Terlayani</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Rumah Tangga</td>
                    <td style="text-align: right;"><?= number_format($pelanggan['total_rt_dom'] ?? 0, 0, ',', '.'); ?> SL</td>
                    <td style="text-align: right;"><?= number_format($cakupan['rata_jiwa_kk'] ?? 0, 6, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format(($pelanggan['total_rt_dom'] ?? 0) * $rata_jiwa, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Niaga Kecil + Menengah</td>
                    <td style="text-align: right;"><?= number_format($pelanggan['total_niaga_dom'] ?? 0, 0, ',', '.'); ?> SL</td>
                    <td style="text-align: right;"><?= number_format($cakupan['rata_jiwa_kk'] ?? 0, 6, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format(($pelanggan['total_niaga_dom'] ?? 0) * $rata_jiwa, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Hunian Vertikal + Kawasan Hunian</td>
                    <td style="text-align: right;">-</td>
                    <td style="text-align: right;">-</td>
                    <td style="text-align: right;">-</td>
                </tr>
                <tr>
                    <td>Hidran Umum</td>
                    <td style="text-align: right;"><?= number_format($pelanggan['total_sl_hu_dom'] ?? 0, 0, ',', '.'); ?> SL</td>
                    <td style="text-align: right;">-</td>
                    <td style="text-align: right;"><?= number_format(($pelanggan['total_sl_hu_dom'] ?? 0) * 100, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Pelanggan Tidak Aktif</td>
                    <td style="text-align: right;"><?= number_format($pelanggan['total_n_aktif_dom'] ?? 0, 0, ',', '.'); ?> SL</td>
                    <td style="text-align: right;"><?= number_format($cakupan['rata_jiwa_kk'] ?? 0, 6, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format(($pelanggan['total_n_aktif_dom'] ?? 0) * $rata_jiwa, 0, ',', '.'); ?></td>
                </tr>
                <tr class="font-weight-bold">
                    <td>Jumlah</td>
                    <td style="text-align: right;"><?= number_format($total_pelanggan ?? 0, 0, ',', '.'); ?> SL</td>
                    <td></td>
                    <td style="text-align: right;"><?= number_format($total_jiwa_terlayani ?? 0, 0, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>
        <br>
        <div class="text-right font-weight-bold">
            Cakupan Pelayanan Administratif : <?= number_format($cakupan_admin ?? 0, 2, ',', '.'); ?> %
        </div>
    </div>
    <br>
    <div class="container my-4">
        <h5 class="font-weight-bold">CAKUPAN PELAYANAN TEKNIS</h5>
        <table class="table table-sm table-borderless">
            <tbody>
                <tr>
                    <td colspan="3"><strong>Wilayah Pelayanan</strong></td>
                </tr>
                <tr>
                    <td>Jumlah penduduk di wilayah pelayanan</td>
                    <td> : </td>
                    <td style="text-align: right;"><?= number_format($cakupan['total_wil_layan'] ?? 0, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Jumlah Kec di wilayah pelayanan</td>
                    <td> : </td>
                    <td style="text-align: right;"><?= number_format($cakupan['jumlah_wil_layan_ya'] ?? 0, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Jumlah KK di wilayah pelayanan</td>
                    <td> : </td>
                    <td style="text-align: right;"><?= number_format($cakupan['total_kk_layan'] ?? 0, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Rata2 jiwa per KK di wilayah pelayanan</td>
                    <td> : </td>
                    <td style="text-align: right;"><?= number_format($cakupan['rata_jiwa_kk2'] ?? 0, 6, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>

        <table class="table tableUtama">
            <thead class="thead-light text-center">
                <tr>
                    <th rowspan="2" class="align-middle">Pelanggan Domestik</th>
                    <th colspan="3">Jumlah</th>
                </tr>
                <tr>
                    <th>Pelanggan</th>
                    <th>Jiwa Rata2</th>
                    <th>Jiwa Terlayani</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Rumah Tangga</td>
                    <td style="text-align: right;"><?= number_format($pelanggan['total_rt_dom'] ?? 0, 0, ',', '.'); ?> SL</td>
                    <td style="text-align: right;"><?= number_format($cakupan['rata_jiwa_kk2'] ?? 0, 6, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format(($pelanggan['total_rt_dom'] ?? 0) * $rata_jiwa2, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Niaga Kecil + Menengah</td>
                    <td style="text-align: right;"><?= number_format($pelanggan['total_niaga_dom'] ?? 0, 0, ',', '.'); ?> SL</td>
                    <td style="text-align: right;"><?= number_format($cakupan['rata_jiwa_kk2'] ?? 0, 6, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format(($pelanggan['total_niaga_dom'] ?? 0) * $rata_jiwa2, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Hunian Vertikal + Kawasan Hunian</td>
                    <td style="text-align: right;">-</td>
                    <td style="text-align: right;">-</td>
                    <td style="text-align: right;">-</td>
                </tr>
                <tr>
                    <td>Hidran Umum</td>
                    <td style="text-align: right;"><?= number_format($pelanggan['total_sl_hu_dom'] ?? 0, 0, ',', '.'); ?> SL</td>
                    <td style="text-align: right;">-</td>
                    <td style="text-align: right;"><?= number_format(($pelanggan['total_sl_hu_dom'] ?? 0) * 100, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Pelanggan Tidak Aktif</td>
                    <td style="text-align: right;"><?= number_format($pelanggan['total_n_aktif_dom'] ?? 0, 0, ',', '.'); ?> SL</td>
                    <td style="text-align: right;"><?= number_format($cakupan['rata_jiwa_kk2'] ?? 0, 6, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format(($pelanggan['total_n_aktif_dom'] ?? 0) * $rata_jiwa2, 0, ',', '.'); ?></td>
                </tr>
                <tr class="font-weight-bold">
                    <td>Jumlah</td>
                    <td style="text-align: right;"><?= number_format($total_pelanggan ?? 0, 0, ',', '.'); ?> SL</td>
                    <td></td>
                    <td style="text-align: right;"><?= number_format($total_jiwa_terlayani2 ?? 0, 0, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>
        <br>
        <div class="text-right font-weight-bold">
            Cakupan Pelayanan Teknis : <?= number_format($cakupan_teknis ?? 0, 2, ',', '.'); ?> %
        </div>
    </div>

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