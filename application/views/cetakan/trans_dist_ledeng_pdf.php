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
        <?php
        if (empty($tahun_lap)) {
            // $bulan_lap = date('m');
            $tahun_lap = date('Y');
        }
        ?>
        <?php if ($selected_upk) : ?>
            <h5 style="text-align: center;"><?= strtoupper($title . ' ' . $selected_upk->name)  . ' ' . $tahun_lap; ?></h5>
        <?php else : ?>
            <h5 style="text-align: center;"><?= strtoupper($title)  . ' INSTALASI TRANSMISI & DISTRIBUSI : LEDENG UMUM' . ' ' . $tahun_lap; ?></h5>
        <?php endif; ?>
    </div>
    <table class="table tableUtama">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Nama Asset</th>
                <th>Tgl perolehan</th>
                <th>Umur</th>
                <th>Prsen</th>
                <th>Harga Perolehan Thn Lalu</th>
                <th>Penambahan</th>
                <th>Pengurangan</th>
                <th>Harga Perolehan Thn Ini</th>
                <th>Akm Thn Lalu</th>
                <th>Nilai Buku Thn Lalu</th>
                <th>Penyusutan</th>
                <th>Akm Thn Ini</th>
                <th>Nilai Buku Thn Ini</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total_rupiah = 0;
            foreach ($susut as $row) :
                // $total_rupiah = $row->total_rupiah;
            ?>
                <tr>
                    <td style="text-align: center;"><?= $no++; ?></td>
                    <td>
                        <?php
                        // Memotong nama_asset jika lebih dari 60 karakter
                        $nama_asset = $row->nama_asset;
                        if (strlen($nama_asset) > 55) {
                            $nama_asset = substr($nama_asset, 0, 55) . '...'; // Potong dan tambahkan "..."
                        }
                        ?>
                        <?php if ($row->kode_sr == 1) : ?>
                            <?= $nama_asset; ?> <?= $row->jumlah; ?> di <?= $row->nama_bagian; ?>
                        <?php else : ?>
                            <?= $nama_asset; ?>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;"><?= date('d-m-Y', strtotime($row->tanggal)); ?></td>
                    <td style="text-align: center;"><?= $row->umur; ?></td>
                    <td style="text-align: center;"><?= $row->persen_susut; ?></td>
                    <td style="text-align: right;"><?= number_format($row->nilai_buku, 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($row->penambahan, 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($row->pengurangan, 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($row->rupiah, 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($row->akm_thn_lalu, 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($row->nilai_buku_lalu, 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($row->penambahan_penyusutan, 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($row->akm_thn_ini, 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($row->nilai_buku_final, 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="text-center bg-light">
                <th colspan="5" style="text-align: right;">Total</th>
                <th style="text-align: right;"><?= number_format($totals['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_nilai_buku_final'], 0, ',', '.'); ?></th>
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