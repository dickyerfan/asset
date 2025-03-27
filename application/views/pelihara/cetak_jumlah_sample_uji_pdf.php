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
        <p class="my-0 text-center fw-bold"><?= strtoupper($title) . ' <br> Dinas Kesehatan Kabupaten Bondowoso Tahun  ' . $tahun_lap; ?></p>
    </div>
    <table class="table tableUtama">
        <thead>
            <tr class="text-center">
                <th rowspan="2" class="align-middle">No</th>
                <th rowspan="2" class="align-middle">Bulan</th>
                <th colspan="5">Jumlah Sample Pemeriksaan</th>
            </tr>
            <tr class="text-center">
                <th>Fisika</th>
                <th>Mikrobiologi</th>
                <th>Sisa chlor</th>
                <th>Kimia Wajib</th>
                <th>Kimia Tambahan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total_fisika = $total_mikro = $total_sisa = $total_kimia_wajib = $total_kimia_tambahan = 0;

            $bulan_list = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            // Mengelompokkan data berdasarkan bulan
            $data_sample = [];
            foreach ($sample_uji as $row) {
                $data_sample[$row['bulan']] = $row;
            }

            foreach ($bulan_list as $bulan) {
                $fisika = isset($data_sample[$bulan]) ? $data_sample[$bulan]['fisika'] : 0;
                $mikro = isset($data_sample[$bulan]) ? $data_sample[$bulan]['mikrobiologi'] : 0;
                $sisa = isset($data_sample[$bulan]) ? $data_sample[$bulan]['sisa_chlor'] : 0;
                $kimia_wajib = isset($data_sample[$bulan]) ? $data_sample[$bulan]['kimia_wajib'] : 0;
                $kimia_tambahan = isset($data_sample[$bulan]) ? $data_sample[$bulan]['kimia_tambahan'] : 0;

                // Menjumlahkan total per kategori
                $total_fisika += $fisika;
                $total_mikro += $mikro;
                $total_sisa += $sisa;
                $total_kimia_wajib += $kimia_wajib;
                $total_kimia_tambahan += $kimia_tambahan;
            ?>
                <tr class="text-center">
                    <td><?= $no++; ?></td>
                    <td class="text-left"><?= $bulan; ?></td>
                    <td style="text-align: center;"><?= $fisika; ?></td>
                    <td style="text-align: center;"><?= $mikro; ?></td>
                    <td style="text-align: center;"><?= $sisa; ?></td>
                    <td style="text-align: center;"><?= $kimia_wajib; ?></td>
                    <td style="text-align: center;"><?= $kimia_tambahan; ?></td>
                </tr>
            <?php } ?>
            <tr class="text-center font-weight-bold">
                <td colspan="2" style="text-align: center;">JUMLAH</td>
                <td style="text-align: center;"><?= $total_fisika; ?></td>
                <td style="text-align: center;"><?= $total_mikro; ?></td>
                <td style="text-align: center;"><?= $total_sisa; ?></td>
                <td style="text-align: center;"><?= $total_kimia_wajib; ?></td>
                <td style="text-align: center;"><?= $total_kimia_tambahan; ?></td>
            </tr>
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