<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset | </title>
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
            $tahun_lap = date('Y');
        }
        ?>
        <p style="text-align: center;"><?= strtoupper($title) . ' : BAGIAN / UPK <br> PER 31 DESEMBER ' . $tahun_lap; ?></p>
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
            <tr class="text-center bg-light">
                <th style="text-align: left;">Tanah & Penyempurnaan Tanah</th>
                <th style="text-align: right;"><?= number_format($total_tanah_non_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah_non_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah_non_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah_non_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah_non_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah_non_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah_non_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah_non_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah_non_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Bangunan/Gedung</th>
                <th style="text-align: right;"><?= number_format($total_bangunan_non_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan_non_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan_non_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan_non_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan_non_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan_non_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan_non_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan_non_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan_non_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Instalasi Sumber</th>
                <th style="text-align: right;"><?= number_format($total_sumber_non_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber_non_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber_non_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber_non_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber_non_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber_non_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber_non_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber_non_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber_non_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Instalasi Pompa</th>
                <th style="text-align: right;"><?= number_format($total_pompa_non_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa_non_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa_non_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa_non_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa_non_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa_non_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa_non_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa_non_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa_non_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Instalasi Pengolahan Air</th>
                <th style="text-align: right;"><?= number_format($total_olah_air_non_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air_non_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air_non_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air_non_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air_non_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air_non_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air_non_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air_non_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air_non_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Instalasi Trans & Distribusi</th>
                <th style="text-align: right;"><?= number_format($total_trans_dist_non_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist_non_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist_non_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist_non_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist_non_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist_non_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist_non_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist_non_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist_non_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Peralatan/Perlengkapan</th>
                <th style="text-align: right;"><?= number_format($total_peralatan_non_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan_non_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan_non_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan_non_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan_non_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan_non_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan_non_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan_non_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan_non_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Kendaraan/Alat Angkut</th>
                <th style="text-align: right;"><?= number_format($total_kendaraan_non_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan_non_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan_non_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan_non_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan_non_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan_non_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan_non_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan_non_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan_non_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Inventaris/Perabotan Kantor</th>
                <th style="text-align: right;"><?= number_format($total_inventaris_non_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris_non_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris_non_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris_non_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris_non_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris_non_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris_non_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris_non_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris_non_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Total</th>
                <th style="text-align: right;"><?= number_format($totals_non_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals_non_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals_non_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals_non_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals_non_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals_non_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals_non_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals_non_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals_non_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
        </tbody>
    </table>

    <div class="judul">
        <?php
        if (empty($tahun_lap)) {
            $tahun_lap = date('Y');
        }
        ?>
        <p style="text-align: center;"><?= strtoupper($title) . ' : AMDK   <br> PER 31 DESEMBER ' . $tahun_lap; ?></p>
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
            <tr class="text-center bg-light">
                <th style="text-align: left;">Tanah & Penyempurnaan Tanah</th>
                <th style="text-align: right;"><?= number_format($total_tanah_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_tanah_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Bangunan/Gedung</th>
                <th style="text-align: right;"><?= number_format($total_bangunan_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_bangunan_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Instalasi Sumber</th>
                <th style="text-align: right;"><?= number_format($total_sumber_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_sumber_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Instalasi Pompa</th>
                <th style="text-align: right;"><?= number_format($total_pompa_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_pompa_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Instalasi Pengolahan Air</th>
                <th style="text-align: right;"><?= number_format($total_olah_air_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_olah_air_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Instalasi Trans & Distribusi</th>
                <th style="text-align: right;"><?= number_format($total_trans_dist_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_trans_dist_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Peralatan/Perlengkapan</th>
                <th style="text-align: right;"><?= number_format($total_peralatan_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_peralatan_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Kendaraan/Alat Angkut</th>
                <th style="text-align: right;"><?= number_format($total_kendaraan_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_kendaraan_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Inventaris/Perabotan Kantor</th>
                <th style="text-align: right;"><?= number_format($total_inventaris_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($total_inventaris_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
            <tr class="text-center bg-light">
                <th style="text-align: left;">Total</th>
                <th style="text-align: right;"><?= number_format($totals_amdk['total_nilai_buku'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals_amdk['total_penambahan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals_amdk['total_pengurangan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals_amdk['total_rupiah'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals_amdk['total_akm_thn_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals_amdk['total_nilai_buku_lalu'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals_amdk['total_penyusutan'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals_amdk['total_akm_thn_ini'], 0, ',', '.'); ?></th>
                <th style="text-align: right;"><?= number_format($totals_amdk['total_nilai_buku_final'], 0, ',', '.'); ?></th>
            </tr>
        </tbody>
    </table>

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
        <p style="text-align: center;"><?= strtoupper($title) . ' <br> PER 31 DESEMBER ' . $tahun_lap; ?></p>
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
            <tr class="text-center bg-light">
                <th style="text-align: left;">Tanah & Penyempurnaan Tanah</th>
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
            <tr class="text-center bg-light">
                <th style="text-align: left;">Bangunan/Gedung</th>
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
            <tr class="text-center bg-light">
                <th style="text-align: left;">Instalasi Sumber</th>
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
            <tr class="text-center bg-light">
                <th style="text-align: left;">Instalasi Pompa</th>
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
            <tr class="text-center bg-light">
                <th style="text-align: left;">Instalasi Pengolahan Air</th>
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
            <tr class="text-center bg-light">
                <th style="text-align: left;">Instalasi Trans & Distribusi</th>
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
            <tr class="text-center bg-light">
                <th style="text-align: left;">Peralatan/Perlengkapan</th>
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
            <tr class="text-center bg-light">
                <th style="text-align: left;">Kendaraan/Alat Angkut</th>
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
            <tr class="text-center bg-light">
                <th style="text-align: left;">Inventaris/Perabotan Kantor</th>
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
            <tr class="text-center bg-light">
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