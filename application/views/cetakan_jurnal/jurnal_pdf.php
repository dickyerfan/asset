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
        <p class="my-0 text-center fw-bold"><?= strtoupper($title) . ' <br> PER 31 DESEMBER ' . $tahun_lap; ?></p>
    </div>
    <table class="table tableUtama">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Nama Perkiraan</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $categories = [
                'Bangunan' => [
                    'data' => [
                        2671 => "Bangunan Kantor",
                        2674 => "Bangunan Laboratorium",
                        2676 => "Bangunan Gedung Peralatan",
                        2678 => "Bangunan Bengkel",
                        2680 => "Instalasi Umum Lainnya"
                    ],
                    'label' => 'Akumulasi Penyusutan Gedung'
                ],
                'Peralatan' => [
                    'data' => [
                        2789 => "Alat-alat Pergudangan",
                        2793 => "Alat-alat Laboratorium",
                        2795 => "Alat-alat Telekomunikasi",
                        2798 => "Alat-alat Bengkel",
                        4251 => "Alat Perlengkapan Lainnya"
                    ],
                    'label' => 'Akumulasi Penyusutan Peralatan'
                ],
                'Kendaraan' => [
                    'data' => [
                        2850 => "Kendaraan Penumpang",
                        2852 => "Kendaraan Angkut Barang",
                        2854 => "Kendaraan Tangki Air",
                        2855 => "Kendaraan Roda Dua"
                    ],
                    'label' => 'Akumulasi Penyusutan Kendaraan'
                ],
                'Inventaris' => [
                    'data' => [
                        2844 => "Meubelair Kantor",
                        2846 => "Mesin-mesin Kantor",
                        2848 => "Rupa2 Inv. Ktr Lainnya"
                    ],
                    'label' => 'Akumulasi Penyusutan Inventaris'
                ],
                'Pengolahan' => [
                    'data' => [
                        2104 => "Bangunan & Perbaikan",
                        2107 => "Alat-alat Pengolahan air",
                        2112 => "Resevoir/Penampungan Air",
                        2115 => "Instalasi Pengolahan Air Lainnya"
                    ],
                    'label' => 'Akumulasi Penyusutan Pengolahan'
                ],
                'Sumber' => [
                    'data' => [
                        1569 => "Bangunan & Perbaikan",
                        1571 => "Reservoir Penampungan Air",
                        1572 => "Danau,Sungai & Sb.Lainnya",
                        1575 => "Mata Air dan Terowongan",
                        1576 => "Sumur-sumur",
                        1577 => "Pipa Supply Utama",
                        1579 => "Instalasi Sumber Lainnya",
                    ],
                    'label' => 'Akumulasi Penyusutan Sumber'
                ],
                'Pompa' => [
                    'data' => [
                        1907 => "Bangunan & Perbaikan",
                        1909 => "Pembangkit Tenaga Listrik",
                        1912 => "Peralatan Pompa",
                        1915 => "Instalasi Pompa Lainnya"
                    ],
                    'label' => 'Akumulasi Penyusutan Pompa'
                ],
                'Transmisi & Distribusi' => [
                    'data' => [
                        2255 => "Bangunan & Perbaikan",
                        2258 => "Reservoir,Tandon & MnrAir",
                        2261 => "Pipa Transmisi dan Distribusi",
                        2262 => "Pipa Dinas",
                        2263 => "Meter Air Yang Terpasang",
                        2264 => "Ledeng Umum",
                        2548 => "Saluran Air Pemadam Kebakaran",
                        2550 => "Jembatan Pipa",
                        2552 => "Inst.Trans & Dist Lainnya"
                    ],
                    'label' => 'Akumulasi Penyusutan Transmisi & Distribusi'
                ]

            ];
            $grand_total = 0;
            foreach ($categories as $category => $details) {
                $no = 1;
                $grouped_by_upk = [];

                // Mengelompokkan data berdasarkan bagian_upk
                foreach ($susut as $row) {
                    if (array_key_exists($row->parent_id, $details['data'])) {
                        $bagian_upk = $row->nama_bagian;
                        if (!isset($grouped_by_upk[$bagian_upk])) {
                            $grouped_by_upk[$bagian_upk] = 0;
                        }
                        $grouped_by_upk[$bagian_upk] += $row->penambahan_penyusutan;
                    }
                }
                // Hitung total per kategori dan tambahkan ke grand_total
                $total_kategori = array_sum($grouped_by_upk);
                $grand_total += $total_kategori;
            ?>
                <tr style="background-color: lightgrey;">
                    <th colspan="2" style="text-align:left">Total <?= $details['label']; ?></th>
                    <th style="text-align:right"><?= number_format(array_sum($grouped_by_upk) / 12, 0, ',', '.'); ?></th>
                    <th style="text-align:right"><?= number_format(array_sum($grouped_by_upk), 0, ',', '.'); ?></th>
                    <th></th>
                </tr>
                <?php
                // Menampilkan hasil pengelompokan
                foreach ($grouped_by_upk as $bagian_upk => $total_penyusutan) {
                    if ($total_penyusutan > 0 && ($total_penyusutan / 12) > 0) {
                ?>
                        <tr style="text-align:right">
                            <td style="text-align:center"><?= $no++; ?></td>
                            <td style="text-align:left">
                                <?= $bagian_upk == 'Umum'
                                    ? $details['label'] . ' - Bondowoso'
                                    : $details['label'] . ' - ' . $bagian_upk; ?>
                            </td>
                            <td><?= number_format($total_penyusutan / 12, 0, ',', '.'); ?></td>
                            <td><?= number_format($total_penyusutan, 0, ',', '.'); ?></td>
                            <td></td>
                        </tr>
                <?php
                    }
                }
                ?>
            <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr style="background-color:lightgray;">
                <th colspan="2" class="text-left">Total Keseluruhan</th>
                <th style="text-align:right"><?= number_format($grand_total / 12, 0, ',', '.'); ?></th>
                <th style="text-align:right"><?= number_format($grand_total, 0, ',', '.'); ?></th>
                <th></th>
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