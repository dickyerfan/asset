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
            font-size: 0.7rem;
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
                <th rowspan="2" class="align-middle">Parameter Uji</th>
                <th colspan="2">Info Jumlah Sample Minimal</th>
                <th rowspan="2" class="align-middle">Terambil</th>
                <th colspan="2">Jumlah Sample Memenuhi Syarat Air Minum</th>
                <th rowspan="2" class="align-middle">Tempat Uji Kualitas Air</th>
            </tr>
            <tr class="text-center">
                <th>Internal</th>
                <th>Eksternal</th>
                <th>Ya</th>
                <th>Tidak</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $current_param = '';

            $total_sample_int = 0;
            $total_sample_eks = 0;
            $total_terambil = 0;
            $total_sample_oke_ya = 0;
            $total_sample_oke_tidak = 0;

            foreach ($kualitas_air as $key => $row) {
                if ($row->parameter !== $current_param) {
                    // Tampilkan parameter hanya sekali di awal
                    echo "<tr>";
                    echo "<td style ='text-align: center'>{$no}</td>";
                    echo "<td class='text-left' colspan='7'><b>{$row->parameter}</b></td>";
                    // echo "<td colspan='6'></td>"; // Kosongkan kolom untuk sejajar
                    echo "</tr>";
                    $no++;

                    // Reset total untuk setiap parameter baru
                    $total_sample_int = 0;
                    $total_sample_eks = 0;
                    $total_terambil = 0;
                    $total_sample_oke_ya = 0;
                    $total_sample_oke_tidak = 0;
                }

                // Akumulasi total
                $total_sample_int += $row->jumlah_sample_int;
                $total_sample_eks += $row->jumlah_sample_eks;
                $total_terambil += $row->jumlah_terambil;
                $total_sample_oke_ya += $row->jumlah_sample_oke_ya;
                $total_sample_oke_tidak += $row->jumlah_sample_oke_tidak;

                // Tampilkan data per bulan
                echo "<tr>";
                echo "<td></td>"; // Kosongkan nomor agar sejajar
                echo "<td class='text-left'>{$row->bulan}</td>";
                echo "<td style ='text-align: center'>{$row->jumlah_sample_int}</td>";
                echo "<td style ='text-align: center'>{$row->jumlah_sample_eks}</td>";
                echo "<td style ='text-align: center'>{$row->jumlah_terambil}</td>";
                echo "<td style ='text-align: center'>{$row->jumlah_sample_oke_ya}</td>";
                echo "<td style ='text-align: center'>{$row->jumlah_sample_oke_tidak}</td>";
                echo "<td style ='text-align: center'>{$row->tempat_uji}</td>";
                echo "</tr>";

                // Cek apakah data berikutnya berbeda parameter
                $next = $kualitas_air[$key + 1] ?? null;
                if (!$next || $next->parameter !== $row->parameter) {
                    // Tampilkan total di akhir setiap parameter
                    echo "<tr>
                                            <td></td>
                                            <td class='text-left'><b>Jumlah</b></td>
                                            <td style ='text-align: center'><b>{$total_sample_int}</b></td>
                                            <td style ='text-align: center'><b>{$total_sample_eks}</b></td>
                                            <td style ='text-align: center'><b>{$total_terambil}</b></td>
                                            <td style ='text-align: center'><b>{$total_sample_oke_ya}</b></td>
                                            <td style ='text-align: center'><b>{$total_sample_oke_tidak}</b></td>
                                            <td></td>
                                            </tr>";
                }
                // Update parameter saat ini
                $current_param = $row->parameter;
            }
            ?>
        </tbody>
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