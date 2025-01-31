<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset| </title>
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
            border-spacing: 0;
            width: 100%;
            /* Sesuaikan dengan lebar halaman */
        }

        .tableUtama th,
        .tableUtama td {
            border: 1px solid black;
            font-size: 0.6rem;
            padding: 6px 3px;
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
        <!-- <?php if ($selected_upk) : ?>
            <h5 style="text-align: center;"><?= strtoupper($title . ' ' . $selected_upk->name)  . ' ' . $tahun_lap; ?></h5>
        <?php else : ?>
            <h5 style="text-align: center;"><?= strtoupper($title)  . ' BANGUNAN KANTOR' . ' ' . $tahun_lap; ?></h5>
        <?php endif; ?> -->
        <p style="text-align: center;"><?= strtoupper($title) . ' TAHUN ' . $tahun_lap; ?></p>
    </div>
    <table class="table tableUtama">
        <thead>
            <tr class="text-center">
                <th>Nama Asset</th>
                <th>Harga Perolehan Thn Lalu</th>
                <th>Penambahan</th>
                <th>Pengurangan</th>
                <th>Harga Perolehan Thn Ini</th>
                <th>Akm Thn Lalu</th>
                <th>Nilai Buku Thn Lalu</th>
                <th>Penyusutan</th>
                <th>Akm Thn Ini</th>
                <th>Nilai Buku Thn Ini</th>
                <th>By. Peny perbulan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="background-color:darkgray;" colspan="11"><strong>1. Tanah</strong></td>
            </tr>
            <?php
            $no = 1;
            $grouped_data = [];
            $totals_per_jenis = []; // Array untuk menyimpan total per jenis bangunan

            // Mengelompokkan data berdasarkan grand_id dan id_no_per
            foreach ($susut_tanah as $row) {
                $grouped_data[$row->grand_id][$row->id_no_per][] = $row;
            }

            // Menampilkan data yang telah dikelompokkan
            foreach ($grouped_data as $grand_id => $upk_data) {

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
                    $name = $assets[0]->name;
                    // Inisialisasi total per bagian/upk
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
                    <?php
                    }
                    ?>
                    <!-- <tr class="bg-light text-right">
                        <td class="text-left"><strong><?= $name; ?></strong></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penambahan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_pengurangan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_rupiah, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_akm_thn_lalu, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku_lalu, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_akm_thn_ini, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku_final, 0, ',', '.'); ?></td>
                    </tr> -->
                <?php

                    // Menambahkan total per bagian/upk ke total per jenis tanah
                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                    $totals_per_jenis[$grand_id]['total_nilai_buku'] += $total_nilai_buku;
                    $totals_per_jenis[$grand_id]['total_penambahan'] += $total_penambahan;
                    $totals_per_jenis[$grand_id]['total_pengurangan'] += $total_pengurangan;
                    $totals_per_jenis[$grand_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                    $totals_per_jenis[$grand_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                    $totals_per_jenis[$grand_id]['total_penyusutan'] += $total_penyusutan;
                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                }
                ?>
            <?php
            }
            ?>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Total Tanah</th>
                <th style="text-align: right;"><?= number_format($total_tanah['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="background-color: darkgray;" colspan="11"><strong>2. Bangunan Gedung</strong></td>
            </tr>
            <?php
            $no = 1;
            $grouped_data = [];
            $totals_per_jenis = []; // Array untuk menyimpan total per jenis bangunan

            // Mengelompokkan data berdasarkan grand_id dan id_bagian
            foreach ($susut_bangunan as $row) {
                $grouped_data[$row->grand_id][$row->id_bagian][] = $row;
            }

            // Menampilkan data yang telah dikelompokkan
            foreach ($grouped_data as $grand_id => $upk_data) {

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
                foreach ($upk_data as $id_bagian => $assets) {
                    $name = $assets[0]->nama_bagian;

                    if ($name == 'Umum') {
                        $name = "Pusat/Bondowoso";
                    } else {
                        $name = "UPK " . $name;
                    }
                    // Inisialisasi total per bagian/upk
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
                    <?php
                    }
                    // Menampilkan total per bagian/upk
                    ?>
                    <tr class="bg-light text-right">
                        <td class="text-left"><strong><?= $name; ?></strong></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penambahan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_pengurangan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_rupiah, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_akm_thn_lalu, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku_lalu, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_akm_thn_ini, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku_final, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penyusutan / 12, 0, ',', '.'); ?></td>
                    </tr>
                <?php
                    // Menambahkan total per bagian/upk ke total per jenis bangunan
                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                    $totals_per_jenis[$grand_id]['total_nilai_buku'] += $total_nilai_buku;
                    $totals_per_jenis[$grand_id]['total_penambahan'] += $total_penambahan;
                    $totals_per_jenis[$grand_id]['total_pengurangan'] += $total_pengurangan;
                    $totals_per_jenis[$grand_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                    $totals_per_jenis[$grand_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                    $totals_per_jenis[$grand_id]['total_penyusutan'] += $total_penyusutan;
                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                }
                ?>
            <?php
            }
            ?>
            <tr class="bg-light">
                <th style="text-align: left;">Total Bangunan Gedung</th>
                <th style="text-align: right;"><?= number_format($total_bangunan['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="background-color: darkgray;" colspan="11"><strong>3. Instalasi Sumber</strong></td>
            </tr>
            <?php
            $no = 1;
            $grouped_data = [];
            $totals_per_jenis = [];

            foreach ($susut_sumber as $row) {
                $grouped_data[$row->grand_id][$row->id_bagian][] = $row;
            }

            // Menampilkan data yang telah dikelompokkan
            foreach ($grouped_data as $grand_id => $upk_data) {

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
                foreach ($upk_data as $id_bagian => $assets) {
                    $name = $assets[0]->nama_bagian;

                    if ($name == 'Umum') {
                        $name = "Pusat/Bondowoso";
                    } else {
                        $name = "UPK " . $name;
                    }

                    // Inisialisasi total per bagian/upk
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
                    <?php
                    }
                    // Menampilkan total per bagian/upk
                    ?>
                    <tr class="bg-light text-right">
                        <td class="text-left"><strong><?= $name; ?></strong></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penambahan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_pengurangan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_rupiah, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_akm_thn_lalu, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku_lalu, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_akm_thn_ini, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku_final, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penyusutan / 12, 0, ',', '.'); ?></td>
                    </tr>
                <?php
                    // Menambahkan total per bagian/upk ke total per jenis bangunan
                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                    $totals_per_jenis[$grand_id]['total_nilai_buku'] += $total_nilai_buku;
                    $totals_per_jenis[$grand_id]['total_penambahan'] += $total_penambahan;
                    $totals_per_jenis[$grand_id]['total_pengurangan'] += $total_pengurangan;
                    $totals_per_jenis[$grand_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                    $totals_per_jenis[$grand_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                    $totals_per_jenis[$grand_id]['total_penyusutan'] += $total_penyusutan;
                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                }
                ?>
            <?php
            }
            ?>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Total Instalasi Sumber</th>
                <th style="text-align: right;"><?= number_format($total_sumber['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="background-color: darkgray;" colspan="11"><strong>4. Instalasi Pompa</strong></td>
            </tr>
            <?php
            $no = 1;
            $grouped_data = [];
            $totals_per_jenis = [];

            foreach ($susut_pompa as $row) {
                $grouped_data[$row->grand_id][$row->id_bagian][] = $row;
            }
            // Menampilkan data yang telah dikelompokkan
            foreach ($grouped_data as $grand_id => $upk_data) {

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
                foreach ($upk_data as $id_bagian => $assets) {
                    $name = $assets[0]->nama_bagian;

                    if ($name == 'Umum') {
                        $name = "Pusat/Bondowoso";
                    } else {
                        $name = "UPK " . $name;
                    }
                    // Inisialisasi total per bagian/upk
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
                    <?php
                    }
                    // Menampilkan total per bagian/upk
                    ?>
                    <tr class="bg-light text-right">
                        <td class="text-left"><strong><?= $name; ?></strong></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penambahan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_pengurangan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_rupiah, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_akm_thn_lalu, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku_lalu, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_akm_thn_ini, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku_final, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penyusutan / 12, 0, ',', '.'); ?></td>
                    </tr>
                <?php
                    // Menambahkan total per bagian/upk ke total per jenis bangunan
                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                    $totals_per_jenis[$grand_id]['total_nilai_buku'] += $total_nilai_buku;
                    $totals_per_jenis[$grand_id]['total_penambahan'] += $total_penambahan;
                    $totals_per_jenis[$grand_id]['total_pengurangan'] += $total_pengurangan;
                    $totals_per_jenis[$grand_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                    $totals_per_jenis[$grand_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                    $totals_per_jenis[$grand_id]['total_penyusutan'] += $total_penyusutan;
                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                }
                ?>
            <?php
            }
            ?>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Total Instalasi pompa</th>
                <th style="text-align: right;"><?= number_format($total_pompa['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="background-color: darkgray;" colspan="11"><strong>5. Instalasi Pengolahan Air</strong></td>
            </tr>
            <?php
            $no = 1;
            $grouped_data = [];
            $totals_per_jenis = [];

            foreach ($susut_olah_air as $row) {
                $grouped_data[$row->grand_id][$row->id_bagian][] = $row;
            }
            // Menampilkan data yang telah dikelompokkan
            foreach ($grouped_data as $grand_id => $upk_data) {

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
                foreach ($upk_data as $id_bagian => $assets) {
                    $name = $assets[0]->nama_bagian;

                    if ($name == 'Umum') {
                        $name = "Pusat/Bondowoso";
                    } else {
                        $name = "UPK " . $name;
                    }
                    // Inisialisasi total per bagian/upk
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
                    <?php
                    }
                    // Menampilkan total per bagian/upk
                    ?>
                    <tr class="bg-light text-right">
                        <td class="text-left"><strong><?= $name; ?></strong></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penambahan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_pengurangan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_rupiah, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_akm_thn_lalu, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku_lalu, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_akm_thn_ini, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku_final, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penyusutan / 12, 0, ',', '.'); ?></td>
                    </tr>
                <?php
                    // Menambahkan total per bagian/upk ke total per jenis pengolahan air
                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                    $totals_per_jenis[$grand_id]['total_nilai_buku'] += $total_nilai_buku;
                    $totals_per_jenis[$grand_id]['total_penambahan'] += $total_penambahan;
                    $totals_per_jenis[$grand_id]['total_pengurangan'] += $total_pengurangan;
                    $totals_per_jenis[$grand_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                    $totals_per_jenis[$grand_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                    $totals_per_jenis[$grand_id]['total_penyusutan'] += $total_penyusutan;
                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                }
                // Menampilkan total per jenis olah_air
                ?>

            <?php
            }
            ?>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Total Instalasi Pengolahan Air</th>
                <th style="text-align: right;"><?= number_format($total_olah_air['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="background-color: darkgray;" colspan="11"><strong>6. Instalasi Transmisi & Distribusi</strong></td>
            </tr>
            <?php
            $no = 1;
            $grouped_data = [];
            $totals_per_jenis = [];

            foreach ($susut_trans_dist as $row) {
                $grouped_data[$row->grand_id][$row->id_bagian][] = $row;
            }
            // Menampilkan data yang telah dikelompokkan
            foreach ($grouped_data as $grand_id => $upk_data) {

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
                foreach ($upk_data as $id_bagian => $assets) {
                    $name = $assets[0]->nama_bagian;

                    if ($name == 'Umum') {
                        $name = "Pusat/Bondowoso";
                    } else {
                        $name = "UPK " . $name;
                    }
                    // Inisialisasi total per bagian/upk
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
                    <?php
                    }
                    // Menampilkan total per bagian/upk
                    ?>
                    <tr class="bg-light text-right">
                        <td class="text-left"><strong><?= $name; ?></strong></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penambahan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_pengurangan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_rupiah, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_akm_thn_lalu, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku_lalu, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_akm_thn_ini, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku_final, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penyusutan / 12, 0, ',', '.'); ?></td>
                    </tr>
                <?php
                    // Menambahkan total per bagian/upk ke total per jenis pengolahan air
                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                    $totals_per_jenis[$grand_id]['total_nilai_buku'] += $total_nilai_buku;
                    $totals_per_jenis[$grand_id]['total_penambahan'] += $total_penambahan;
                    $totals_per_jenis[$grand_id]['total_pengurangan'] += $total_pengurangan;
                    $totals_per_jenis[$grand_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                    $totals_per_jenis[$grand_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                    $totals_per_jenis[$grand_id]['total_penyusutan'] += $total_penyusutan;
                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                }
                // Menampilkan total per jenis trans_dist
                ?>

            <?php
            }
            ?>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Total Instalasi Transmisi & Distribusi</th>
                <th style="text-align: right;"><?= number_format($total_trans_dist['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="background-color: darkgray;" colspan="11"><strong>7. Peralatan & Perlengkapan</strong></td>
            </tr>
            <?php
            $no = 1;
            $grouped_data = [];
            $totals_per_jenis = [];

            foreach ($susut_peralatan as $row) {
                $grouped_data[$row->grand_id][$row->id_bagian][] = $row;
            }
            // Menampilkan data yang telah dikelompokkan
            foreach ($grouped_data as $grand_id => $upk_data) {

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
                foreach ($upk_data as $id_bagian => $assets) {
                    $name = $assets[0]->nama_bagian;

                    if ($name == 'Umum') {
                        $name = "Pusat/Bondowoso";
                    } else {
                        $name = "UPK " . $name;
                    }
                    // Inisialisasi total per bagian/upk
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
                    <?php
                    }
                    // Menampilkan total per bagian/upk
                    ?>
                    <tr class="bg-light text-right">
                        <td class="text-left"><strong><?= $name; ?></strong></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penambahan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_pengurangan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_rupiah, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_akm_thn_lalu, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku_lalu, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_akm_thn_ini, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku_final, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penyusutan / 12, 0, ',', '.'); ?></td>
                    </tr>
                <?php
                    // Menambahkan total per bagian/upk ke total per jenis pengolahan air
                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                    $totals_per_jenis[$grand_id]['total_nilai_buku'] += $total_nilai_buku;
                    $totals_per_jenis[$grand_id]['total_penambahan'] += $total_penambahan;
                    $totals_per_jenis[$grand_id]['total_pengurangan'] += $total_pengurangan;
                    $totals_per_jenis[$grand_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                    $totals_per_jenis[$grand_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                    $totals_per_jenis[$grand_id]['total_penyusutan'] += $total_penyusutan;
                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                }

                ?>

            <?php
            }
            ?>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Total Peralatan & Perlengkapan</th>
                <th style="text-align: right;"><?= number_format($total_peralatan['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="background-color: darkgray;" colspan="11"><strong>8. Kendaraan & Alat Angkut</strong></td>
            </tr>
            <?php
            $no = 1;
            $grouped_data = [];
            $totals_per_jenis = [];

            foreach ($susut_kendaraan as $row) {
                $grouped_data[$row->grand_id][$row->id_bagian][] = $row;
            }
            // Menampilkan data yang telah dikelompokkan
            foreach ($grouped_data as $grand_id => $upk_data) {

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
                foreach ($upk_data as $id_bagian => $assets) {
                    $name = $assets[0]->nama_bagian;

                    if ($name == 'Umum') {
                        $name = "Pusat/Bondowoso";
                    } else {
                        $name = "UPK " . $name;
                    }
                    // Inisialisasi total per bagian/upk
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
                    <?php
                    }
                    // Menampilkan total per bagian/upk
                    ?>
                    <tr class="bg-light text-right">
                        <td class="text-left"><strong><?= $name; ?></strong></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penambahan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_pengurangan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_rupiah, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_akm_thn_lalu, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku_lalu, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_akm_thn_ini, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku_final, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penyusutan / 12, 0, ',', '.'); ?></td>
                    </tr>
                <?php
                    // Menambahkan total per bagian/upk ke total per jenis pengolahan air
                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                    $totals_per_jenis[$grand_id]['total_nilai_buku'] += $total_nilai_buku;
                    $totals_per_jenis[$grand_id]['total_penambahan'] += $total_penambahan;
                    $totals_per_jenis[$grand_id]['total_pengurangan'] += $total_pengurangan;
                    $totals_per_jenis[$grand_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                    $totals_per_jenis[$grand_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                    $totals_per_jenis[$grand_id]['total_penyusutan'] += $total_penyusutan;
                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                }
                // Menampilkan total per jenis kendaraan
                ?>
            <?php
            }
            ?>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Total Kendaraan / Alat Angkut</th>
                <th style="text-align: right;"><?= number_format($total_kendaraan['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="background-color: darkgray;" colspan="11"><strong>9. Inventaris/Perabotan Kantor</strong></td>
            </tr>
            <?php
            $no = 1;
            $grouped_data = [];
            $totals_per_jenis = [];
            // Array untuk nama bangunan berdasarkan grand_id

            foreach ($susut_inventaris as $row) {
                $grouped_data[$row->grand_id][$row->id_bagian][] = $row;
            }
            // Menampilkan data yang telah dikelompokkan
            foreach ($grouped_data as $grand_id => $upk_data) {

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
                    $name = $assets[0]->nama_bagian;

                    if ($name == 'Umum') {
                        $name = "Pusat/Bondowoso";
                    } else {
                        $name = "UPK " . $name;
                    }
                    // Inisialisasi total per bagian/upk
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
                    <?php
                    }
                    // Menampilkan total per bagian/upk
                    ?>
                    <tr class="bg-light text-right">
                        <td class="text-left"><strong><?= $name; ?></strong></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penambahan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_pengurangan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_rupiah, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_akm_thn_lalu, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku_lalu, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_akm_thn_ini, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_nilai_buku_final, 0, ',', '.'); ?></td>
                        <td style="text-align: right;"><?= number_format($total_penyusutan / 12, 0, ',', '.'); ?></td>
                    </tr>
                <?php
                    // Menambahkan total per bagian/upk ke total per jenis pengolahan air
                    $totals_per_jenis[$grand_id]['total_rupiah'] += $total_rupiah;
                    $totals_per_jenis[$grand_id]['total_nilai_buku'] += $total_nilai_buku;
                    $totals_per_jenis[$grand_id]['total_penambahan'] += $total_penambahan;
                    $totals_per_jenis[$grand_id]['total_pengurangan'] += $total_pengurangan;
                    $totals_per_jenis[$grand_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                    $totals_per_jenis[$grand_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                    $totals_per_jenis[$grand_id]['total_penyusutan'] += $total_penyusutan;
                    $totals_per_jenis[$grand_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                    $totals_per_jenis[$grand_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                }
                ?>

            <?php
            }
            ?>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Total Inventaris / Perabotan Kantor</th>
                <th style="text-align: right;"><?= number_format($total_inventaris['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
            </tr>
        </tbody>
        <tfoot>
            <tr style="background-color: lightgray;">
                <th style="text-align: left;">Total</th>
                <th style="text-align: right;"><?= number_format($totals['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_nilai_buku_final'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals['total_penyusutan'] / 12, 0, ',', '.'); ?></th>
            </tr>
        </tfoot>
    </table>

    <script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>