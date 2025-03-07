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
        <p><strong><?= strtoupper($title); ?></strong> <br> <strong>Per 31 Desember <?= $tahun_lap ?> dan 31 Desember <?= $tahun_lalu ?></strong></p>
    </div>
    <table class="table tableUtama">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Uraian</th>
                <th>31 Desember <?= htmlspecialchars($tahun_lap) ?></th>
                <th>31 Desember <?= htmlspecialchars($tahun_lalu) ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $kategori_sebelumnya = '';
            $data_tahun_lalu = [];
            $data_tahun_sekarang = [];

            // **1. Simpan data tahun lalu & tahun sekarang dalam array terpisah**
            foreach ($neraca as $row) {
                if ($row->tahun_neraca == $tahun_lalu) {
                    $data_tahun_lalu[$row->akun] = $row->nilai_neraca;
                }
                if ($row->tahun_neraca == $tahun_lap) {
                    $data_tahun_sekarang[] = $row;
                }
            }

            // **Variabel untuk menyimpan total kategori**
            $total_aset_lancar = $total_aset_tidak_lancar = 0;
            $total_liabilitas_jangka_pendek = $total_liabilitas_jangka_panjang = $total_ekuitas = 0;
            $total_aset_lancar_lalu = $total_aset_tidak_lancar_lalu = 0;
            $total_liabilitas_jangka_pendek_lalu = $total_liabilitas_jangka_panjang_lalu = $total_ekuitas_lalu = 0;

            foreach ($data_tahun_sekarang as $row) {
                // **Cek apakah kategori berubah, jika iya, tampilkan kategori sebagai judul**
                if ($kategori_sebelumnya !== $row->kategori) {
                    if ($kategori_sebelumnya == 'Aset Lancar') {
                        echo "<tr class='font-weight-bold bg-light'>";
                        echo "<td colspan='2'>Jumlah Aset Lancar</td>";
                        echo "<td style='text-align:right;'>" . number_format($total_aset_lancar, 0, ',', '.') . "</td>";
                        echo "<td style='text-align:right;'>" . number_format($total_aset_lancar_lalu, 0, ',', '.') . "</td>";
                        echo "</tr>";
                    } elseif ($kategori_sebelumnya == 'Aset Tidak Lancar') {
                        echo "<tr class='font-weight-bold bg-light'>";
                        echo "<td colspan='2'>Jumlah Aset Tidak Lancar</td>";
                        echo "<td style='text-align:right;'>" . number_format($total_aset_tidak_lancar, 0, ',', '.') . "</td>";
                        echo "<td style='text-align:right;'>" . number_format($total_aset_tidak_lancar_lalu, 0, ',', '.') . "</td>";
                        echo "</tr>";

                        // **Total Aset ditampilkan setelah Total Aset Tidak Lancar**
                        $total_aset = $total_aset_lancar + $total_aset_tidak_lancar;
                        $total_aset_lalu = $total_aset_lancar_lalu + $total_aset_tidak_lancar_lalu;
                        echo "<tr style='background-color: lightgrey;'>";
                        echo "<td colspan='2'>JUMLAH ASET</td>";
                        echo "<td style='text-align:right;'>" . number_format($total_aset, 0, ',', '.') . "</td>";
                        echo "<td style='text-align:right;'>" . number_format($total_aset_lalu, 0, ',', '.') . "</td>";
                        echo "</tr>";
                    } elseif ($kategori_sebelumnya == 'Liabilitas Jangka Pendek') {
                        echo "<tr class='font-weight-bold bg-light'>";
                        echo "<td colspan='2'>Jumlah Liabilitas Jangka Pendek</td>";
                        echo "<td style='text-align:right;'>" . number_format($total_liabilitas_jangka_pendek, 0, ',', '.') . "</td>";
                        echo "<td style='text-align:right;'>" . number_format($total_liabilitas_jangka_pendek_lalu, 0, ',', '.') . "</td>";
                        echo "</tr>";
                    } elseif ($kategori_sebelumnya == 'Liabilitas Jangka Panjang') {
                        echo "<tr class='font-weight-bold bg-light'>";
                        echo "<td colspan='2'>Jumlah Liabilitas Jangka Panjang</td>";
                        echo "<td style='text-align:right;'>" . number_format($total_liabilitas_jangka_panjang, 0, ',', '.') . "</td>";
                        echo "<td style='text-align:right;'>" . number_format($total_liabilitas_jangka_panjang_lalu, 0, ',', '.') . "</td>";
                        echo "</tr>";
                    } elseif ($kategori_sebelumnya == 'Ekuitas') {
                        echo "<tr class='font-weight-bold bg-light'>";
                        echo "<td colspan='2'>Jumlah Ekuitas</td>";
                        echo "<td style='text-align:right;'>" . number_format($total_ekuitas, 0, ',', '.') . "</td>";
                        echo "<td style='text-align:right;'>" . number_format($total_ekuitas_lalu, 0, ',', '.') . "</td>";
                        echo "</tr>";

                        // **Total Liabilitas & Ekuitas setelah Total Ekuitas**
                        $total_liabilitas_ekuitas = $total_liabilitas + $total_ekuitas;
                        $total_liabilitas_ekuitas_lalu = $total_liabilitas_lalu + $total_ekuitas_lalu;
                        echo "<tr class='font-weight-bold bg-warning text-center'>";
                        echo "<td colspan='2'>JUMLAH LIABILITAS & EKUITAS</td>";
                        echo "<td style='text-align:right;'>" . number_format($total_liabilitas_ekuitas, 0, ',', '.') . "</td>";
                        echo "<td style='text-align:right;'>" . number_format($total_liabilitas_ekuitas_lalu, 0, ',', '.') . "</td>";
                        echo "</tr>";
                    }

                    echo "<tr class='font-weight-bold'>";
                    echo "<td colspan='4' style='background-color: lightgray;'>" . htmlspecialchars($row->kategori) . "</td>";
                    echo "</tr>";
                    $kategori_sebelumnya = $row->kategori;
                }

                // **Ambil nilai tahun lalu berdasarkan akun**
                $nilai_tahun_lalu = isset($data_tahun_lalu[$row->akun]) ? $data_tahun_lalu[$row->akun] : 0;

                // **Tampilkan data neraca**
                echo "<tr>";
                echo "<td class='text-center'>{$row->no_neraca}</td>";
                echo "<td>" . htmlspecialchars($row->akun) . "</td>";
                echo "<td style='text-align:right;'>" . number_format($row->nilai_neraca_audited, 0, ',', '.') . "</td>";
                echo "<td style='text-align:right;'>" . number_format($nilai_tahun_lalu, 0, ',', '.') . "</td>";
                echo "</tr>";

                // **Hitung total berdasarkan kategori**
                if ($row->kategori == 'Aset Lancar') {
                    $total_aset_lancar += $row->nilai_neraca_audited;
                    $total_aset_lancar_lalu += $nilai_tahun_lalu;
                } elseif ($row->kategori == 'Aset Tidak Lancar') {
                    $total_aset_tidak_lancar += $row->nilai_neraca_audited;
                    $total_aset_tidak_lancar_lalu += $nilai_tahun_lalu;
                } elseif ($row->kategori == 'Liabilitas Jangka Pendek') {
                    $total_liabilitas_jangka_pendek += $row->nilai_neraca_audited;
                    $total_liabilitas_jangka_pendek_lalu += $nilai_tahun_lalu;
                } elseif ($row->kategori == 'Liabilitas Jangka Panjang') {
                    $total_liabilitas_jangka_panjang += $row->nilai_neraca_audited;
                    $total_liabilitas_jangka_panjang_lalu += $nilai_tahun_lalu;
                } elseif ($row->kategori == 'Ekuitas') {
                    $total_ekuitas += $row->nilai_neraca_audited;
                    $total_ekuitas_lalu += $nilai_tahun_lalu;
                }
            }

            // **Tampilkan total terakhir setelah looping selesai**
            if ($kategori_sebelumnya == 'Aset Lancar') {
                echo "<tr class='font-weight-bold bg-light'>";
                echo "<td colspan='2'>Jumlah Aset Lancar</td>";
                echo "<td style='text-align:right;'>" . number_format($total_aset_lancar, 0, ',', '.') . "</td>";
                echo "<td></td>";
                echo "</tr>";
            } elseif ($kategori_sebelumnya == 'Aset Tidak Lancar') {
                echo "<tr class='font-weight-bold bg-light'>";
                echo "<td colspan='2'>Jumlah Aset Tidak Lancar</td>";
                echo "<td style='text-align:right;'>" . number_format($total_aset_tidak_lancar, 0, ',', '.') . "</td>";
                echo "<td></td>";
                echo "</tr>";

                // **Total Aset**
                $total_aset = $total_aset_lancar + $total_aset_tidak_lancar;
                echo "<tr style='background-color: lightgrey;'>";
                echo "<td colspan='2'>JUMLAH ASET</td>";
                echo "<td style='text-align:right;'>" . number_format($total_aset, 0, ',', '.') . "</td>";
                echo "<td></td>";
                echo "</tr>";
            } elseif ($kategori_sebelumnya == 'Ekuitas') {
                echo "<tr class='font-weight-bold bg-light'>";
                echo "<td colspan='2'>Total Ekuitas</td>";
                echo "<td style='text-align:right;'>" . number_format($total_ekuitas, 0, ',', '.') . "</td>";
                echo "<td style='text-align:right;'>" . number_format($total_ekuitas_lalu, 0, ',', '.') . "</td>";
                echo "</tr>";

                // **Total Liabilitas & Ekuitas**
                $total_liabilitas_ekuitas = $total_liabilitas_jangka_pendek + $total_liabilitas_jangka_panjang + $total_ekuitas;
                $total_liabilitas_ekuitas_lalu = $total_liabilitas_jangka_pendek_lalu + $total_liabilitas_jangka_panjang_lalu + $total_ekuitas_lalu;
                echo "<tr style='background-color: lightgrey;'>";
                echo "<td colspan='2'>JUMLAH LIABILITAS & EKUITAS</td>";
                echo "<td style='text-align:right;'>" . number_format($total_liabilitas_ekuitas, 0, ',', '.') . "</td>";
                echo "<td style='text-align:right;'>" . number_format($total_liabilitas_ekuitas_lalu, 0, ',', '.') . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>