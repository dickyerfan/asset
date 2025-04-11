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
                <th>BULAN</th>
                <th>Jenis Pengaduan</th>
                <th>Jumlah Pengaduan</th>
                <th>Jumlah Pengaduan Terselesaikan</th>
                <th>Jumlah Pengaduan Belum Terselesaikan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_aduan = 0;
            $total_aduan_ya = 0;
            $total_aduan_tidak = 0;
            $bulan_sebelumnya = null;
            $total_per_jenis = [];

            foreach ($pengaduan as $data) :
                $total_aduan += $data->jumlah_aduan ?? 0;
                $total_aduan_ya += $data->jumlah_aduan_ya ?? 0;
                $total_aduan_tidak += $data->jumlah_aduan_tidak ?? 0;

                // Hitung total per jenis aduan
                $jenis = $data->jenis_aduan;
                if (!isset($total_per_jenis[$jenis])) {
                    $total_per_jenis[$jenis] = [
                        'jumlah' => 0,
                        'ya' => 0,
                        'tidak' => 0
                    ];
                }

                $total_per_jenis[$jenis]['jumlah'] += $data->jumlah_aduan ?? 0;
                $total_per_jenis[$jenis]['ya'] += $data->jumlah_aduan_ya ?? 0;
                $total_per_jenis[$jenis]['tidak'] += $data->jumlah_aduan_tidak ?? 0;
            ?>
                <tr style="text-align: center;">
                    <td style="text-align: left;">
                        <?= ($data->bulan != $bulan_sebelumnya) ? $data->bulan : ""; ?>
                    </td>
                    <td style="text-align: left;"><?= $data->jenis_aduan; ?></td>
                    <td><?= $data->jumlah_aduan ?? 0; ?></td>
                    <td><?= $data->jumlah_aduan_ya ?? 0; ?></td>
                    <td><?= $data->jumlah_aduan_tidak ?? 0; ?></td>
                </tr>
            <?php
                $bulan_sebelumnya = $data->bulan;
            endforeach;
            ?>
        </tbody>
        <tfoot>
            <?php foreach ($total_per_jenis as $jenis => $total) : ?>
                <tr style="text-align: center; font-weight: bold">
                    <td style="text-align: left;">Jumlah</td>
                    <td style="text-align: left;"><?= $jenis; ?></td>
                    <td><?= $total['jumlah']; ?></td>
                    <td><?= $total['ya']; ?></td>
                    <td><?= $total['tidak']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tfoot>
        <tfoot>
            <tr style="text-align: center; font-weight: bold">
                <td colspan="2">TOTAL</td>
                <td><?= $total_aduan; ?></td>
                <td><?= $total_aduan_ya; ?></td>
                <td><?= $total_aduan_tidak; ?></td>
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