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
            font-size: 0.62rem;
        }

        .header p {
            margin: 0;
            font-size: 0.7rem;
        }

        .header img {
            margin-right: 10px;
        }

        .judul p {
            margin: 8px 0 5px;
            font-size: 0.95rem;
            font-weight: bold;
            text-align: center;
        }

        .identitas {
            margin: 8px 0 12px;
            width: 45%;
        }

        .identitas th,
        .identitas td {
            font-size: 0.68rem;
            padding: 2px 4px;
            vertical-align: top;
        }

        .tableUtama {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 14px;
        }

        .tableUtama th,
        .tableUtama td {
            border: 1px solid #000;
            font-size: 0.55rem;
            padding: 2px 3px;
            vertical-align: top;
        }

        .tableUtama th {
            text-align: center;
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        .page-break {
            page-break-before: always;
        }

        .tandaTangan {
            margin-top: 18px;
            font-size: 0.72rem;
            page-break-inside: avoid;
        }

        .tandaTangan table {
            width: 100%;
            border-collapse: collapse;
        }

        .tandaTangan td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 2px 8px;
        }

        .tandaTangan .space {
            height: 48px;
        }

        .tandaTangan .nama {
            font-weight: bold;
            text-decoration: underline;
        }

        .tandaTangan .nik {
            margin-top: 2px;
        }

        .tandaTangan .mengetahui {
            margin-top: 24px;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php
    $nama_upk = '';
    if (isset($unit_list) && is_array($unit_list)) {
        foreach ($unit_list as $unit) {
            if ($unit->id_bagian == $filter['id_upk']) {
                $nama_upk = $unit->nama_bagian;
                break;
            }
        }
    }

    if (!function_exists('nama_ttd_risiko_pdf')) {
        function nama_ttd_risiko_pdf($ttd)
        {
            return !empty($ttd->nama_petugas) ? htmlspecialchars($ttd->nama_petugas, ENT_QUOTES, 'UTF-8') : '&nbsp;';
        }
    }

    if (!function_exists('nik_ttd_risiko_pdf')) {
        function nik_ttd_risiko_pdf($ttd)
        {
            return !empty($ttd->nik) ? 'NIK. ' . htmlspecialchars($ttd->nik, ENT_QUOTES, 'UTF-8') : '&nbsp;';
        }
    }
    ?>
    <div class="header">
        <table>
            <tbody>
                <tr>
                    <td width="10%">
                        <img src="<?= base_url('assets/img/pdam_biru.png'); ?>" alt="Logo" width="40">
                    </td>
                    <td>
                        <p>Perusahaan Daerah Air Minum</p>
                        <p>Kabupaten Bondowoso</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <table class="identitas">
        <tbody>
            <tr>
                <th align="left" width="42%">Pemilik Risiko</th>
                <td width="2%">:</td>
                <td><?= $nama_upk; ?></td>
            </tr>
            <tr>
                <th align="left">Penanggung Jawab Risiko</th>
                <td>:</td>
                <td>Kabag/Ka UPK/Ketua <?= $nama_upk; ?></td>
            </tr>
            <tr>
                <th align="left">Periode</th>
                <td>:</td>
                <td><?= $tahun; ?></td>
            </tr>
        </tbody>
    </table>

    <div class="judul">
        <p><?= strtoupper($title); ?> TAHUN <?= $tahun; ?></p>
    </div>
    <table class="tableUtama">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="14%">Kegiatan</th>
                <th width="13%">Tujuan Keg.</th>
                <th width="9%">Kode Risiko</th>
                <th width="18%">Pernyataan</th>
                <th width="17%">Sebab</th>
                <th width="5%">C / UC</th>
                <th>Dampak</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($profil_risiko) && count($profil_risiko) > 0) : $no = 1; ?>
                <?php foreach ($profil_risiko as $row) : ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $row->kegiatan; ?></td>
                        <td><?= $row->tujuan; ?></td>
                        <td><?= $row->kode_risiko; ?></td>
                        <td><?= $row->pernyataan; ?></td>
                        <td><?= $row->sebab; ?></td>
                        <td class="text-center"><?= $row->kategori; ?></td>
                        <td><?= $row->dampak; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="8" class="text-center">Data belum ada / tidak tersedia</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="judul">
        <p><?= strtoupper($title2); ?> TAHUN <?= $tahun; ?></p>
    </div>
    <table class="tableUtama">
        <thead>
            <tr>
                <th rowspan="3" width="3%">No</th>
                <th rowspan="3" width="16%">Risiko</th>
                <th colspan="6">Pengendalian Yang Ada</th>
                <th colspan="4">Analisa dan Evaluasi Risiko</th>
                <th rowspan="3" width="9%">Pemilik Risiko</th>
            </tr>
            <tr>
                <th rowspan="2" width="15%">Uraian</th>
                <th colspan="2">Desain</th>
                <th colspan="3">Efektivitas</th>
                <th rowspan="2">Prob.</th>
                <th rowspan="2">Dampak</th>
                <th rowspan="2">Tingkat Risiko</th>
                <th rowspan="2">Peringkat Risiko</th>
            </tr>
            <tr>
                <th>Ada</th>
                <th>Tidak</th>
                <th>Tidak</th>
                <th>Kurang</th>
                <th>Efektif</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($analisa_risiko) && count($analisa_risiko) > 0) : $no = 1; ?>
                <?php foreach ($analisa_risiko as $row) :
                    if ($row->peringkat_risiko == 'Sangat Tinggi') {
                        $warna = '#FF0000';
                    } elseif ($row->peringkat_risiko == 'Tinggi') {
                        $warna = '#FFC000';
                    } elseif ($row->peringkat_risiko == 'Moderat') {
                        $warna = '#FFFF00';
                    } elseif ($row->peringkat_risiko == 'Rendah') {
                        $warna = '#92D050';
                    } else {
                        $warna = '#00B0F0';
                    }
                ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $row->pernyataan; ?></td>
                        <td><?= $row->kendali_uraian; ?></td>
                        <td class="text-center"><?= ($row->desain == 'Ada') ? 'v' : '-'; ?></td>
                        <td class="text-center"><?= ($row->desain == 'Tidak') ? 'v' : '-'; ?></td>
                        <td class="text-center"><?= ($row->efektifitas == 'Tidak') ? 'v' : '-'; ?></td>
                        <td class="text-center"><?= ($row->efektifitas == 'Kurang') ? 'v' : '-'; ?></td>
                        <td class="text-center"><?= ($row->efektifitas == 'Efektif') ? 'v' : '-'; ?></td>
                        <td class="text-center"><?= $row->probabilitas; ?></td>
                        <td class="text-center"><?= $row->dampak; ?></td>
                        <td class="text-center"><?= $row->tingkat_risiko; ?></td>
                        <td class="text-center" style="background-color: <?= $warna; ?>"><?= $row->peringkat_risiko; ?></td>
                        <td class="text-center"><?= $row->pemilik_risiko; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="13" class="text-center">Data belum ada / tidak tersedia</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="judul">
        <p><?= strtoupper($title3); ?> TAHUN <?= $tahun; ?></p>
    </div>
    <table class="tableUtama">
        <thead>
            <tr>
                <th rowspan="2" width="3%">No</th>
                <th rowspan="2" width="25%">Risiko</th>
                <th colspan="3">Penanganan/Rencana Tindak Pengendalian</th>
                <th rowspan="2" width="14%">Penanggung Jawab TL</th>
            </tr>
            <tr>
                <th>Uraian</th>
                <th width="12%">Jadwal</th>
                <th width="18%">Hasil</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($penanganan_risiko) && count($penanganan_risiko) > 0) : $no = 1; ?>
                <?php foreach ($penanganan_risiko as $row) : ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $row->pernyataan; ?></td>
                        <td><?= $row->uraian; ?></td>
                        <td><?= $row->jadwal; ?></td>
                        <td><?= $row->hasil; ?></td>
                        <td><?= $row->pj_tl; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" class="text-center">Data belum ada / tidak tersedia</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="judul">
        <p><?= strtoupper($title4); ?> TAHUN <?= $tahun; ?></p>
    </div>
    <table class="tableUtama">
        <thead>
            <tr>
                <th rowspan="2" width="3%">No</th>
                <th rowspan="2" width="20%">Risiko</th>
                <th colspan="4">Realisasi</th>
                <th colspan="4">Level Risiko setelah RTP</th>
            </tr>
            <tr>
                <th width="16%">RTP</th>
                <th width="9%">Jadwal</th>
                <th width="15%">Hasil</th>
                <th width="13%">Keterangan</th>
                <th>Prob</th>
                <th>Dampak</th>
                <th>Tingkat</th>
                <th>Peringkat</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($monitoring_risiko) && count($monitoring_risiko) > 0) : $no = 1; ?>
                <?php foreach ($monitoring_risiko as $row) :
                    if ($row->peringkat_setelah == 'Sangat Tinggi') {
                        $warna = '#FF0000';
                    } elseif ($row->peringkat_setelah == 'Tinggi') {
                        $warna = '#FFC000';
                    } elseif ($row->peringkat_setelah == 'Moderat') {
                        $warna = '#FFFF00';
                    } elseif ($row->peringkat_setelah == 'Rendah') {
                        $warna = '#92D050';
                    } else {
                        $warna = '#00B0F0';
                    }
                ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $row->pernyataan; ?></td>
                        <td><?= $row->rtp; ?></td>
                        <td><?= $row->jadwal; ?></td>
                        <td><?= $row->hasil; ?></td>
                        <td><?= $row->keterangan; ?></td>
                        <td class="text-center"><?= $row->prob_setelah; ?></td>
                        <td class="text-center"><?= $row->dampak_setelah; ?></td>
                        <td class="text-center"><?= $row->tingkat_setelah; ?></td>
                        <td class="text-center" style="background-color: <?= $warna; ?>"><?= $row->peringkat_setelah; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="10" class="text-center">Data belum ada / tidak tersedia</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="tandaTangan">
        <table>
            <tbody>
                <tr>
                    <td>
                        <div>Pemilik Risiko</div>
                        <div>Kabag/Ka UPK/Ketua <?= $nama_upk; ?></div>
                        <div class="space"></div>
                        <div class="nama"><?= nama_ttd_risiko_pdf($ttd_pemilik_risiko); ?></div>
                        <div class="nik"><?= nik_ttd_risiko_pdf($ttd_pemilik_risiko); ?></div>
                    </td>
                    <td>
                        <div>Satgas Manajemen Risiko</div>
                        <div>Ketua</div>
                        <div class="space"></div>
                        <div class="nama"><?= nama_ttd_risiko_pdf($ttd_ketua_satgas_mr); ?></div>
                        <div class="nik"><?= nik_ttd_risiko_pdf($ttd_ketua_satgas_mr); ?></div>
                    </td>
                    <td>
                        <div>Pengawas Risiko</div>
                        <div>Ketua SPI</div>
                        <div class="space"></div>
                        <div class="nama"><?= nama_ttd_risiko_pdf($ttd_ketua_spi); ?></div>
                        <div class="nik"><?= nik_ttd_risiko_pdf($ttd_ketua_spi); ?></div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="mengetahui">
            <div>Mengetahui,</div>
            <div>Direktur PDAM</div>
            <div class="space"></div>
            <div class="nama"><?= nama_ttd_risiko_pdf($ttd_direktur); ?></div>
            <div class="nik"><?= nik_ttd_risiko_pdf($ttd_direktur); ?></div>
        </div>
    </div>
</body>

</html>