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
        <p class="my-0 text-center fw-bold"><?= strtoupper($title) . ' ' . $tahun_lap; ?></p>
    </div>
    <table class="table tableUtama">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Uraian</th>
                <th>Harga Perolehan</th>
                <th>Akm Penyusutan</th>
                <th>Nilai Buku</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $grouped_data = [];
            $totals_per_jenis = [];
            $no_per = [
                218 => 'TANAH & PENYEMPURNAAN TANAH',
                220 => 'INSTALASI SUMBER',
                222 => 'INSTALASI POMPA',
                224 => 'INSTALASI PENGOLAHAN',
                226 => 'INSTALASI TRANSMISI & DISTRIBUSI',
                228 => 'BANGUNAN & GEDUNG',
                244 => 'PERALATAN & PERLENGKAPAN',
                246 => 'KENDARAAN',
                248 => 'INVENTARIS & PERABOTAN KANTOR'
            ];

            foreach ($susut as $row) {
                $grouped_data[$row->grand_id][$row->id_no_per][] = $row;
            }

            // Menampilkan data yang telah dikelompokkan
            foreach ($grouped_data as $grand_id => $upk_data) {
                // Judul berdasarkan grand_id
                $nama_perkiraan = $no_per[$grand_id] ?? "Bangunan Lainnya";
                echo "<tr><td style='background-color:grey;'></td><td colspan='4' style='background-color:grey;'><strong>{$nama_perkiraan}</strong></td></tr>";



                // Inisialisasi total per jenis bangunan
                $totals_per_jenis[$grand_id] = [
                    'total_rupiah' => 0,
                    'total_nilai_buku' => 0,
                    'total_penambahan' => 0,
                    'total_pengurangan' => 0,
                    'total_akm_thn_lalu' => 0,
                    'total_nilai_buku_lalu' => 0,
                    'total_penyusutan' => 0,
                    'total_akm_thn_ini' => 0,
                    'total_nilai_buku_final' => 0
                ];

                // Menampilkan data per bagian/upk
                foreach ($upk_data as $id_no_per => $assets) {

                    $total_rupiah = 0;
                    $total_nilai_buku = 0;
                    $total_penambahan = 0;
                    $total_pengurangan = 0;
                    $total_akm_thn_lalu = 0;
                    $total_nilai_buku_lalu = 0;
                    $total_penyusutan = 0;
                    $total_akm_thn_ini = 0;
                    $total_nilai_buku_final = 0;

                    foreach ($assets as $row) {
                        $total_rupiah += $row->rupiah;
                        $total_nilai_buku += $row->nilai_buku;
                        $total_penambahan += $row->penambahan;
                        $total_pengurangan += $row->pengurangan;
                        $total_akm_thn_lalu += $row->akm_thn_lalu;
                        $total_nilai_buku_lalu += $row->nilai_buku_lalu;
                        $total_penyusutan += $row->penambahan_penyusutan;
                        $total_akm_thn_ini += $row->akm_thn_ini;
                        $total_nilai_buku_final += $row->nilai_buku_final;

            ?>
                        <tr>
                            <td style="text-align: center;"><?= $no++; ?></td>
                            <td>
                                <?php
                                $nama_asset = $row->nama_asset;
                                if (strlen($nama_asset) > 75) {
                                    $nama_asset = substr($nama_asset, 0, 75) . '...';
                                }
                                ?>
                                <?= $nama_asset; ?>
                            </td>
                            <td style="text-align: right;"><?= number_format($row->rupiah * -1, 0, ',', '.'); ?></td>
                            <td style="text-align: right;"><?= number_format($row->akm_thn_ini * -1, 0, ',', '.'); ?></td>
                            <td style="text-align: right;"><?= number_format($row->nilai_buku_final * -1, 0, ',', '.'); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                <?php
                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                }

                ?>
                <tr style='background-color:lightgrey;'>
                    <td></td>
                    <td class="text-left"><strong>SUB TOTAL <?= $nama_perkiraan; ?></strong></td>
                    <td style="text-align: right;"><strong><?= number_format($totals_per_jenis[$grand_id]['total_rupiah'] * -1, 0, ',', '.'); ?></strong></td>
                    <td style="text-align: right;"><strong><?= number_format($totals_per_jenis[$grand_id]['total_akm_thn_ini'] * -1, 0, ',', '.'); ?></strong></td>
                    <td style="text-align: right;"><strong><?= number_format($totals_per_jenis[$grand_id]['total_nilai_buku_final'] * -1, 0, ',', '.'); ?></strong></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr style='background-color:grey;'>
                <th></th>
                <th style="text-align: left">TOTAL PENGURANGAN PENYUSUTAN</th>
                <th style="text-align: right;"><?= number_format($totals['total_rupiah'] * -1, 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_akm_thn_ini'] * -1, 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_nilai_buku_final'] * -1, 0, ',', '.'); ?></th>
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