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
            font-size: 0.7rem;
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
        <p class="my-0 text-center fw-bold"><?= strtoupper($title) . ' TAHUN  ' . $tahun_lap; ?></p>
    </div>
    <table class="table tableUtama">
        <thead>
            <tr style='text-align: center;'>
                <th rowspan="3" class="align-middle">No</th>
                <th rowspan="3" class="align-middle">Kecamatan</th>
                <th colspan="18" class="align-middle">Domestik</th>
            </tr>
            <tr style='text-align: center;'>
                <th colspan="3">Sosial Umum</th>
                <th colspan="3">Rumah Tangga A</th>
                <th colspan="3">Rumah Tangga B</th>
                <th colspan="3">Rumah Tangga C</th>
                <th colspan="3">Niaga A</th>
                <th colspan="3">Jumlah</th>
            </tr>
            <tr style='text-align: center;'>
                <td>Jumlah SR</td>
                <td>Volume</td>
                <td>Rupiah</td>
                <td>Jumlah SR</td>
                <td>Volume</td>
                <td>Rupiah</td>
                <td>Jumlah SR</td>
                <td>Volume</td>
                <td>Rupiah</td>
                <td>Jumlah SR</td>
                <td>Volume</td>
                <td>Rupiah</td>
                <td>Jumlah SR</td>
                <td>Volume</td>
                <td>Rupiah</td>
                <td>Jumlah SR</td>
                <td>Volume</td>
                <td>Rupiah</td>
            </tr>
        </thead>
        <?php
        $kategori = [
            'Sosial Umum' => 'SOSIAL A',
            'RT A' => 'RUMAH TANGGA A',
            'RT B' => 'RUMAH TANGGA B',
            'RT C' => 'RUMAH TANGGA C',
            'Niaga A' => 'NIAGA A'
        ];

        // Inisialisasi array kosong per kecamatan
        $data_rinci = [];
        foreach ($kecamatan as $kec) {
            $nama_kec = $kec->nama_kec;
            $data_rinci[$nama_kec] = [];
            foreach ($kategori as $kat) {
                $data_rinci[$nama_kec][$kat] = ['sr' => 0, 'vol' => 0, 'rp' => 0];
            }
        }

        // Tambahkan data dari $rincian ke dalam array rinci
        foreach ($rincian as $row) {
            $kec = $row->nama_kec;
            $tarif = $row->kel_tarif;

            if (in_array($tarif, $kategori)) {
                $data_rinci[$kec][$tarif]['sr'] += $row->jumlah_sr;
                $data_rinci[$kec][$tarif]['vol'] += $row->volume;
                $data_rinci[$kec][$tarif]['rp'] += $row->rupiah;
            }
        }

        // Inisialisasi total keseluruhan
        $total_keseluruhan = [];
        foreach ($kategori as $k) {
            $total_keseluruhan[$k] = ['sr' => 0, 'vol' => 0, 'rp' => 0];
        }
        $total_keseluruhan['JUMLAH'] = ['sr' => 0, 'vol' => 0, 'rp' => 0];
        ?>
        <tbody>
            <?php
            $no = 1;
            foreach ($data_rinci as $nama_kec => $item) {
                echo "<tr>";
                echo "<td class='text-center'>{$no}</td>";
                echo "<td>{$nama_kec}</td>";

                $total_sr = $total_vol = $total_rp = 0;

                foreach ($kategori as $k) {
                    $sr = $item[$k]['sr'];
                    $vol = $item[$k]['vol'];
                    $rp = $item[$k]['rp'];

                    // Hitung total per baris
                    $total_sr += $sr;
                    $total_vol += $vol;
                    $total_rp += $rp;

                    // Tambah ke total keseluruhan
                    $total_keseluruhan[$k]['sr'] += $sr;
                    $total_keseluruhan[$k]['vol'] += $vol;
                    $total_keseluruhan[$k]['rp'] += $rp;

                    echo "<td style='text-align: right;'>" . number_format($sr, 0, ',', '.') . "</td>";
                    echo "<td style='text-align: right;'>" . number_format($vol, 0, ',', '.') . "</td>";
                    echo "<td style='text-align: right;'>" . number_format($rp, 0, ',', '.') . "</td>";
                }

                // Tambah ke total jumlah semua
                $total_keseluruhan['JUMLAH']['sr'] += $total_sr;
                $total_keseluruhan['JUMLAH']['vol'] += $total_vol;
                $total_keseluruhan['JUMLAH']['rp'] += $total_rp;

                echo "<td style='text-align: right;'>" . number_format($total_sr, 0, ',', '.') . "</td>";
                echo "<td style='text-align: right;'>" . number_format($total_vol, 0, ',', '.') . "</td>";
                echo "<td style='text-align: right;'>" . number_format($total_rp, 0, ',', '.') . "</td>";
                echo "</tr>";
                $no++;
            }
            ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #e9ecef; font-weight: bold;">
                <td colspan="2" class="text-center">Total</td>
                <?php foreach ($kategori as $k) : ?>
                    <td style='text-align: right;'><?= number_format($total_keseluruhan[$k]['sr'], 0, ',', '.') ?></td>
                    <td style='text-align: right;'><?= number_format($total_keseluruhan[$k]['vol'], 0, ',', '.') ?></td>
                    <td style='text-align: right;'><?= number_format($total_keseluruhan[$k]['rp'], 0, ',', '.') ?></td>
                <?php endforeach; ?>
                <td style='text-align: right;'><?= number_format($total_keseluruhan['JUMLAH']['sr'], 0, ',', '.') ?></td>
                <td style='text-align: right;'><?= number_format($total_keseluruhan['JUMLAH']['vol'], 0, ',', '.') ?></td>
                <td style='text-align: right;'><?= number_format($total_keseluruhan['JUMLAH']['rp'], 0, ',', '.') ?></td>
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