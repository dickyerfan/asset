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
        <p style="text-align: center;"><?= strtoupper($title) . ' TAHUN ' . $tahun_lap; ?> <br> ( Berdasarkan Kode Perkiraan )</p>
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
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="background-color: lightgray;" colspan="10"><strong>1. Tanah</strong></td>
            </tr>
            <?php
            $no = 1;
            $grouped_data = [];
            $totals_per_jenis = []; // Array untuk menyimpan total per jenis bangunan

            // Array untuk nama bangunan berdasarkan parent_id
            $nama_tanah = [
                1472 => "Tanah dan Hak Atas Tanah",
                1474 => "Penyempurnaan Tanah",
            ];

            // Mengelompokkan data berdasarkan parent_id dan id_no_per
            foreach ($susut_tanah as $row) {
                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
            }

            // Menampilkan data yang telah dikelompokkan
            foreach ($grouped_data as $parent_id => $upk_data) {
                // Judul berdasarkan parent_id
                $nama_tanah_jenis = $nama_tanah[$parent_id] ?? "Tanah Lainnya";
                // Inisialisasi total per jenis tanah
                $totals_per_jenis[$parent_id] = [
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
                <?php

                    // Menambahkan total per bagian/upk ke total per jenis tanah
                    $totals_per_jenis[$parent_id]['total_rupiah'] += $total_rupiah;
                    $totals_per_jenis[$parent_id]['total_nilai_buku'] += $total_nilai_buku;
                    $totals_per_jenis[$parent_id]['total_penambahan'] += $total_penambahan;
                    $totals_per_jenis[$parent_id]['total_pengurangan'] += $total_pengurangan;
                    $totals_per_jenis[$parent_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                    $totals_per_jenis[$parent_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                    $totals_per_jenis[$parent_id]['total_penyusutan'] += $total_penyusutan;
                    $totals_per_jenis[$parent_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                    $totals_per_jenis[$parent_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                }

                // Menampilkan total per jenis tanah
                ?>
                <tr>
                    <td style="text-align: left;"><strong><?= $nama_tanah_jenis; ?></strong></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_penambahan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_pengurangan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_rupiah'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_lalu'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_lalu'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_penyusutan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_ini'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_final'], 0, ',', '.'); ?></td>
                </tr>
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
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="background-color: lightgray;" colspan="10"><strong>2. Bangunan Gedung</strong></td>
            </tr>
            <?php
            $no = 1;
            $grouped_data = [];
            $totals_per_jenis = []; // Array untuk menyimpan total per jenis bangunan

            // Array untuk nama bangunan berdasarkan parent_id
            $nama_bangunan = [
                2671 => "Bangunan Kantor",
                2674 => "Bangunan Laboratorium",
                2676 => "Bangunan Gedung Peralatan",
                2678 => "Bangunan Bengkel",
                2680 => "Instalasi Umum Lainnya"
            ];

            // Mengelompokkan data berdasarkan parent_id dan id_no_per
            foreach ($susut_bangunan as $row) {
                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
            }

            // Menampilkan data yang telah dikelompokkan
            foreach ($grouped_data as $parent_id => $upk_data) {
                // Judul berdasarkan parent_id
                $nama_bangunan_jenis = $nama_bangunan[$parent_id] ?? "Bangunan Lainnya";
                // echo "<tr><td colspan='14' class='bg-primary text-white'><strong>{$nama_bangunan_jenis}</strong></td></tr>";

                // Inisialisasi total per jenis bangunan
                $totals_per_jenis[$parent_id] = [
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
                    // echo "<tr><td colspan='14' class='bg-secondary text-white'><strong> {$name}</strong></td></tr>";

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
                <?php
                    // Menambahkan total per bagian/upk ke total per jenis bangunan
                    $totals_per_jenis[$parent_id]['total_rupiah'] += $total_rupiah;
                    $totals_per_jenis[$parent_id]['total_nilai_buku'] += $total_nilai_buku;
                    $totals_per_jenis[$parent_id]['total_penambahan'] += $total_penambahan;
                    $totals_per_jenis[$parent_id]['total_pengurangan'] += $total_pengurangan;
                    $totals_per_jenis[$parent_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                    $totals_per_jenis[$parent_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                    $totals_per_jenis[$parent_id]['total_penyusutan'] += $total_penyusutan;
                    $totals_per_jenis[$parent_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                    $totals_per_jenis[$parent_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                }

                // Menampilkan total per jenis bangunan
                ?>
                <tr class="text-right">
                    <td style="text-align: left;"><strong><?= $nama_bangunan_jenis; ?></strong></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_penambahan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_pengurangan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_rupiah'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_lalu'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_lalu'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_penyusutan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_ini'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_final'], 0, ',', '.'); ?></td>
                </tr>
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
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="background-color: lightgray;" colspan="10"><strong>3. Instalasi Sumber</strong></td>
            </tr>
            <?php
            $no = 1;
            $grouped_data = [];
            $totals_per_jenis = [];
            // Array untuk nama bangunan berdasarkan parent_id
            $nama_sumber = [
                1569 => "Bangunan & Perbaikan",
                1571 => "Reservoir Penampungan Air",
                1572 => "Danau,Sungai & Sb.Lainnya",
                1575 => "Mata Air dan Terowongan",
                1576 => "Sumur-sumur",
                1577 => "Pipa Supply Utama",
                1579 => "Instalasi Sumber Lainnya",
            ];
            foreach ($susut_sumber as $row) {
                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
            }

            // Menampilkan data yang telah dikelompokkan
            foreach ($grouped_data as $parent_id => $upk_data) {
                // Judul berdasarkan parent_id
                $nama_sumber_jenis = $nama_sumber[$parent_id] ?? "Instalasi Sumber Lainnya";
                // echo "<tr><td colspan='14' class='bg-primary text-white'><strong>{$nama_sumber_jenis}</strong></td></tr>";

                // Inisialisasi total per jenis bangunan
                $totals_per_jenis[$parent_id] = [
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
                    // echo "<tr><td class='bg-secondary'></td><td colspan='14' class='bg-secondary text-white'><strong> {$name}</strong></td></tr>";
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
                <?php
                    // Menambahkan total per bagian/upk ke total per jenis bangunan
                    $totals_per_jenis[$parent_id]['total_rupiah'] += $total_rupiah;
                    $totals_per_jenis[$parent_id]['total_nilai_buku'] += $total_nilai_buku;
                    $totals_per_jenis[$parent_id]['total_penambahan'] += $total_penambahan;
                    $totals_per_jenis[$parent_id]['total_pengurangan'] += $total_pengurangan;
                    $totals_per_jenis[$parent_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                    $totals_per_jenis[$parent_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                    $totals_per_jenis[$parent_id]['total_penyusutan'] += $total_penyusutan;
                    $totals_per_jenis[$parent_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                    $totals_per_jenis[$parent_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                }
                // Menampilkan total per jenis sumber
                ?>
                <tr class="text-right">
                    <td style="text-align: left;"><strong><?= $nama_sumber_jenis; ?></strong></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_penambahan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_pengurangan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_rupiah'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_lalu'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_lalu'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_penyusutan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_ini'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_final'], 0, ',', '.'); ?></td>
                </tr>
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
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="background-color: lightgray;" colspan="10"><strong>4. Instalasi Pompa</strong></td>
            </tr>
            <?php
            $no = 1;
            $grouped_data = [];
            $totals_per_jenis = [];
            // Array untuk nama bangunan berdasarkan parent_id
            $nama_pompa = [
                1907 => "Bangunan & Perbaikan",
                1909 => "Pembangkit Tenaga Listrik",
                1912 => "Peralatan Pompa",
                1915 => "Instalasi Pompa Lainnya"
            ];
            foreach ($susut_pompa as $row) {
                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
            }
            // Menampilkan data yang telah dikelompokkan
            foreach ($grouped_data as $parent_id => $upk_data) {
                // Judul berdasarkan parent_id
                $nama_pompa_jenis = $nama_pompa[$parent_id] ?? "Instalasi Pompa Lainnya";
                // echo "<tr><td colspan='14' class='bg-primary text-white'><strong>{$nama_pompa_jenis}</strong></td></tr>";
                // Inisialisasi total per jenis bangunan
                $totals_per_jenis[$parent_id] = [
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
                    // echo "<tr><td class='bg-secondary'></td><td colspan='14' class='bg-secondary text-white'><strong> {$name}</strong></td></tr>";
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
                <?php
                    // Menambahkan total per bagian/upk ke total per jenis bangunan
                    $totals_per_jenis[$parent_id]['total_rupiah'] += $total_rupiah;
                    $totals_per_jenis[$parent_id]['total_nilai_buku'] += $total_nilai_buku;
                    $totals_per_jenis[$parent_id]['total_penambahan'] += $total_penambahan;
                    $totals_per_jenis[$parent_id]['total_pengurangan'] += $total_pengurangan;
                    $totals_per_jenis[$parent_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                    $totals_per_jenis[$parent_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                    $totals_per_jenis[$parent_id]['total_penyusutan'] += $total_penyusutan;
                    $totals_per_jenis[$parent_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                    $totals_per_jenis[$parent_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                }
                // Menampilkan total per jenis pompa
                ?>
                <tr class="text-right">
                    <td style="text-align: left;"><strong><?= $nama_pompa_jenis; ?></strong></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_penambahan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_pengurangan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_rupiah'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_lalu'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_lalu'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_penyusutan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_ini'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_final'], 0, ',', '.'); ?></td>
                </tr>
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
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="background-color: lightgray;" colspan="10"><strong>5. Instalasi Pengolahan Air</strong></td>
            </tr>
            <?php
            $no = 1;
            $grouped_data = [];
            $totals_per_jenis = [];
            // Array untuk nama bangunan berdasarkan parent_id
            $nama_olah_air = [
                2104 => "Bangunan & Perbaikan",
                2107 => "Alat-alat Pengolahan air",
                2112 => "Resevoir/Penampungan Air",
                2115 => "Instalasi Pengolahan Air Lainnya"
            ];
            foreach ($susut_olah_air as $row) {
                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
            }
            // Menampilkan data yang telah dikelompokkan
            foreach ($grouped_data as $parent_id => $upk_data) {
                // Judul berdasarkan parent_id
                $nama_olah_air_jenis = $nama_olah_air[$parent_id] ?? "Instalasi olah_air Lainnya";
                // echo "<tr><td colspan='14' class='bg-primary text-white'><strong>{$nama_olah_air_jenis}</strong></td></tr>";
                // Inisialisasi total per jenis bangunan
                $totals_per_jenis[$parent_id] = [
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
                    // echo "<tr><td class='bg-secondary'></td><td colspan='14' class='bg-secondary text-white'><strong> {$name}</strong></td></tr>";
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
                <?php
                    // Menambahkan total per bagian/upk ke total per jenis pengolahan air
                    $totals_per_jenis[$parent_id]['total_rupiah'] += $total_rupiah;
                    $totals_per_jenis[$parent_id]['total_nilai_buku'] += $total_nilai_buku;
                    $totals_per_jenis[$parent_id]['total_penambahan'] += $total_penambahan;
                    $totals_per_jenis[$parent_id]['total_pengurangan'] += $total_pengurangan;
                    $totals_per_jenis[$parent_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                    $totals_per_jenis[$parent_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                    $totals_per_jenis[$parent_id]['total_penyusutan'] += $total_penyusutan;
                    $totals_per_jenis[$parent_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                    $totals_per_jenis[$parent_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                }
                // Menampilkan total per jenis olah_air
                ?>
                <tr class="text-right">
                    <td style="text-align: left;"><strong><?= $nama_olah_air_jenis; ?></strong></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_penambahan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_pengurangan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_rupiah'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_lalu'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_lalu'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_penyusutan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_ini'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_final'], 0, ',', '.'); ?></td>
                </tr>
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
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="background-color: lightgray;" colspan="10"><strong>6. Instalasi Transmisi & Distribusi</strong></td>
            </tr>
            <?php
            $no = 1;
            $grouped_data = [];
            $totals_per_jenis = [];
            // Array untuk nama bangunan berdasarkan parent_id
            $nama_trans_dist = [
                2255 => "Bangunan & Perbaikan",
                2258 => "Reservoir,Tandon & MnrAir",
                2261 => "Pipa Transmisi dan Distribusi",
                2262 => "Pipa Dinas",
                2263 => "Meter Air Yang Terpasang",
                2264 => "Ledeng Umum",
                2548 => "Saluran Air Pemadam Kebakaran",
                2550 => "Jembatan Pipa",
                2552 => "Inst.Trans & Dist Lainnya"
            ];
            foreach ($susut_trans_dist as $row) {
                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
            }
            // Menampilkan data yang telah dikelompokkan
            foreach ($grouped_data as $parent_id => $upk_data) {
                // Judul berdasarkan parent_id
                $nama_trans_dist_jenis = $nama_trans_dist[$parent_id] ?? "Instalasi trans_dist Lainnya";
                // echo "<tr><td colspan='14' class='bg-primary text-white'><strong>{$nama_trans_dist_jenis}</strong></td></tr>";
                // Inisialisasi total per jenis bangunan
                $totals_per_jenis[$parent_id] = [
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
                    // echo "<tr><td class='bg-secondary'></td><td colspan='14' class='bg-secondary text-white'><strong> {$name}</strong></td></tr>";
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
                <?php
                    // Menambahkan total per bagian/upk ke total per jenis pengolahan air
                    $totals_per_jenis[$parent_id]['total_rupiah'] += $total_rupiah;
                    $totals_per_jenis[$parent_id]['total_nilai_buku'] += $total_nilai_buku;
                    $totals_per_jenis[$parent_id]['total_penambahan'] += $total_penambahan;
                    $totals_per_jenis[$parent_id]['total_pengurangan'] += $total_pengurangan;
                    $totals_per_jenis[$parent_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                    $totals_per_jenis[$parent_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                    $totals_per_jenis[$parent_id]['total_penyusutan'] += $total_penyusutan;
                    $totals_per_jenis[$parent_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                    $totals_per_jenis[$parent_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                }
                // Menampilkan total per jenis trans_dist
                ?>
                <tr class="text-right">
                    <td style="text-align: left;"><strong><?= $nama_trans_dist_jenis; ?></strong></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_penambahan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_pengurangan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_rupiah'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_lalu'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_lalu'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_penyusutan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_ini'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_final'], 0, ',', '.'); ?></td>
                </tr>
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
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="background-color: lightgray;" colspan="10"><strong>7. Peralatan & Perlengkapan</strong></td>
            </tr>
            <?php
            $no = 1;
            $grouped_data = [];
            $totals_per_jenis = [];
            // Array untuk nama bangunan berdasarkan parent_id
            $nama_peralatan = [
                2789 => "Alat-alat Pergudangan",
                2793 => "Alat-alat Laboratorium",
                2795 => "Alat-alat Telekomunikasi",
                2798 => "Alat-alat Bengkel",
                4251 => "Alat Perlengkapan Lainnya"
            ];
            foreach ($susut_peralatan as $row) {
                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
            }
            // Menampilkan data yang telah dikelompokkan
            foreach ($grouped_data as $parent_id => $upk_data) {
                // Judul berdasarkan parent_id
                $nama_peralatan_jenis = $nama_peralatan[$parent_id] ?? "Instalasi peralatan Lainnya";
                // echo "<tr><td colspan='14' class='bg-primary text-white'><strong>{$nama_peralatan_jenis}</strong></td></tr>";
                // Inisialisasi total per jenis bangunan
                $totals_per_jenis[$parent_id] = [
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
                    // echo "<tr><td class='bg-secondary'></td><td colspan='14' class='bg-secondary text-white'><strong> {$name}</strong></td></tr>";
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
                <?php
                    // Menambahkan total per bagian/upk ke total per jenis pengolahan air
                    $totals_per_jenis[$parent_id]['total_rupiah'] += $total_rupiah;
                    $totals_per_jenis[$parent_id]['total_nilai_buku'] += $total_nilai_buku;
                    $totals_per_jenis[$parent_id]['total_penambahan'] += $total_penambahan;
                    $totals_per_jenis[$parent_id]['total_pengurangan'] += $total_pengurangan;
                    $totals_per_jenis[$parent_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                    $totals_per_jenis[$parent_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                    $totals_per_jenis[$parent_id]['total_penyusutan'] += $total_penyusutan;
                    $totals_per_jenis[$parent_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                    $totals_per_jenis[$parent_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                }
                // Menampilkan total per jenis peralatan
                ?>
                <tr class="text-right">
                    <td style="text-align: left;"><strong><?= $nama_peralatan_jenis; ?></strong></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_penambahan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_pengurangan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_rupiah'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_lalu'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_lalu'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_penyusutan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_ini'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_final'], 0, ',', '.'); ?></td>
                </tr>
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
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="background-color: lightgray;" colspan="10"><strong>8. Kendaraan & Alat Angkut</strong></td>
            </tr>
            <?php
            $no = 1;
            $grouped_data = [];
            $totals_per_jenis = [];
            // Array untuk nama bangunan berdasarkan parent_id
            $nama_kendaraan = [
                2850 => "Kendaraan Penumpang",
                2852 => "Kendaraan Angkut Barang",
                2854 => "Kendaraan Tangki Air",
                2855 => "Kendaraan Roda Dua"
            ];
            foreach ($susut_kendaraan as $row) {
                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
            }
            // Menampilkan data yang telah dikelompokkan
            foreach ($grouped_data as $parent_id => $upk_data) {
                // Judul berdasarkan parent_id
                $nama_kendaraan_jenis = $nama_kendaraan[$parent_id] ?? "Instalasi kendaraan Lainnya";
                // echo "<tr><td colspan='14' class='bg-primary text-white'><strong>{$nama_kendaraan_jenis}</strong></td></tr>";
                // Inisialisasi total per jenis bangunan
                $totals_per_jenis[$parent_id] = [
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
                    // echo "<tr><td class='bg-secondary'></td><td colspan='14' class='bg-secondary text-white'><strong> {$name}</strong></td></tr>";
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
                <?php
                    // Menambahkan total per bagian/upk ke total per jenis pengolahan air
                    $totals_per_jenis[$parent_id]['total_rupiah'] += $total_rupiah;
                    $totals_per_jenis[$parent_id]['total_nilai_buku'] += $total_nilai_buku;
                    $totals_per_jenis[$parent_id]['total_penambahan'] += $total_penambahan;
                    $totals_per_jenis[$parent_id]['total_pengurangan'] += $total_pengurangan;
                    $totals_per_jenis[$parent_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                    $totals_per_jenis[$parent_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                    $totals_per_jenis[$parent_id]['total_penyusutan'] += $total_penyusutan;
                    $totals_per_jenis[$parent_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                    $totals_per_jenis[$parent_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                }
                // Menampilkan total per jenis kendaraan
                ?>
                <tr class="text-right">
                    <td style="text-align: left;"><strong><?= $nama_kendaraan_jenis; ?></strong></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_penambahan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_pengurangan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_rupiah'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_lalu'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_lalu'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_penyusutan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_ini'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_final'], 0, ',', '.'); ?></td>
                </tr>
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
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td style="background-color: lightgray;" colspan="10"><strong>9. Inventaris/Perabotan Kantor</strong></td>
            </tr>
            <?php
            $no = 1;
            $grouped_data = [];
            $totals_per_jenis = [];
            // Array untuk nama bangunan berdasarkan parent_id
            $nama_inventaris = [
                2844 => "Meubelair Kantor",
                2846 => "Mesin-mesin Kantor",
                2848 => "Rupa2 Inv. Ktr Lainnya"
            ];
            foreach ($susut_inventaris as $row) {
                $grouped_data[$row->parent_id][$row->id_no_per][] = $row;
            }
            // Menampilkan data yang telah dikelompokkan
            foreach ($grouped_data as $parent_id => $upk_data) {
                // Judul berdasarkan parent_id
                $nama_inventaris_jenis = $nama_inventaris[$parent_id] ?? "Instalasi inventaris Lainnya";
                // echo "<tr><td colspan='14' class='bg-primary text-white'><strong>{$nama_inventaris_jenis}</strong></td></tr>";
                // Inisialisasi total per jenis bangunan
                $totals_per_jenis[$parent_id] = [
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
                    // echo "<tr><td class='bg-secondary'></td><td colspan='14' class='bg-secondary text-white'><strong> {$name}</strong></td></tr>";
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
                <?php
                    // Menambahkan total per bagian/upk ke total per jenis pengolahan air
                    $totals_per_jenis[$parent_id]['total_rupiah'] += $total_rupiah;
                    $totals_per_jenis[$parent_id]['total_nilai_buku'] += $total_nilai_buku;
                    $totals_per_jenis[$parent_id]['total_penambahan'] += $total_penambahan;
                    $totals_per_jenis[$parent_id]['total_pengurangan'] += $total_pengurangan;
                    $totals_per_jenis[$parent_id]['total_akm_thn_lalu'] += $total_akm_thn_lalu;
                    $totals_per_jenis[$parent_id]['total_nilai_buku_lalu'] += $total_nilai_buku_lalu;
                    $totals_per_jenis[$parent_id]['total_penyusutan'] += $total_penyusutan;
                    $totals_per_jenis[$parent_id]['total_akm_thn_ini'] += $total_akm_thn_ini;
                    $totals_per_jenis[$parent_id]['total_nilai_buku_final'] += $total_nilai_buku_final;
                }
                // Menampilkan total per jenis inventaris
                ?>
                <tr class="text-right">
                    <td style="text-align: left;"><strong><?= $nama_inventaris_jenis; ?></strong></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_penambahan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_pengurangan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_rupiah'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_lalu'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_lalu'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_penyusutan'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_akm_thn_ini'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;"><?= number_format($totals_per_jenis[$parent_id]['total_nilai_buku_final'], 0, ',', '.'); ?></td>
                </tr>
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
            </tr>
        </tfoot>
    </table>

    <script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>