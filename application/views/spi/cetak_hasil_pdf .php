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
        <p class="my-2 text-center fw-bold"><?= strtoupper($title); ?></p>
    </div>
    <table class="table tableUtama">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Nama U P K</th>
                <th>Skor Teknis</th>
                <th>Skor Administrasi</th>
                <th>Skor Koordinasi</th>
                <th>Total Skor</th>
                <th>Hasil</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            if (!empty($rekap)) :
                foreach ($rekap as $data_upk) :
                    $hasil = '';
                    $grand_total_skor = (float)$data_upk['grand_total_skor'];

                    if ($grand_total_skor >= 71 && $grand_total_skor <= 75) {
                        $hasil = 'Sangat Baik';
                    } elseif ($grand_total_skor >= 61 && $grand_total_skor <= 70) {
                        $hasil = 'Baik';
                    } elseif ($grand_total_skor >= 51 && $grand_total_skor <= 60) {
                        $hasil = 'Sedang';
                    } elseif ($grand_total_skor >= 41 && $grand_total_skor <= 50) {
                        $hasil = 'Kurang';
                    } elseif ($grand_total_skor < 40) {
                        $hasil = 'Sangat Kurang';
                    } else {
                        $hasil = 'N/A';
                    }
            ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $data_upk['nama_bagian']; ?></td>
                        <td class="text-center"><?= $data_upk['total_skor_teknis']; ?></td>
                        <td class="text-center"><?= $data_upk['total_skor_admin']; ?></td>
                        <td class="text-center"><?= $data_upk['total_skor_koordinasi']; ?></td>
                        <td class="text-center"><?= $data_upk['grand_total_skor']; ?></td>
                        <td class="text-center"><?= $hasil; ?></td>
                    </tr>
                <?php endforeach;
            else : ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data yang ditemukan untuk bulan dan tahun ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="font-size: 0.8rem;" class="tandaTangan">
        <p style="width: 50%; float: right;text-align:center; margin-bottom: 1px;">Bondowoso, <?= date('d-m-Y');  ?></p>
        <div style="clear: both;"></div>
        <p style="width: 50%; float: right;text-align:center;">KETUA S P I</p>
        <div style="clear: both; margin-bottom:30px;"></div>
        <u style="width: 50%; float: right; text-align:center; margin-bottom: 1px;">I MADE SUARJAYA</u>
        <div style="clear: both;"></div>
        <p style="width: 50%; float: right; text-align:center;">NIK. 112 92 083</p>
        <div style="clear: both;"></div>
    </div>
    <script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>