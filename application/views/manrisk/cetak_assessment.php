<?php

/**
 * Cetak Risk Assessment — halaman mandiri tanpa sidebar.
 * Dibuka di tab baru dari tombol "Cetak / PDF" lalu disimpan sebagai PDF
 * melalui dialog cetak browser (A4 landscape).
 */
$warnaLevel = [];
foreach ($tingkat as $t) {
    $warnaLevel[$t->nama_tingkat] = $t->warna;
}

$teks = function ($v) {
    $v = trim((string) $v);
    return $v === '' ? '&mdash;' : str_replace('; ', '<br>', html_escape($v));
};

$rp = function ($angka) {
    if ($angka === null || $angka === '') {
        return '&mdash;';
    }
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
};
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Risk Assessment <?= (int) $tahun ?></title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #212529;
            margin: 0;
            padding: 12px;
        }

        h1 {
            font-size: 15px;
            margin: 0 0 2px;
            text-align: center;
        }

        .sub {
            text-align: center;
            font-size: 10px;
            margin-bottom: 2px;
        }

        .meta {
            text-align: center;
            font-size: 9px;
            color: #555;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #666;
            padding: 3px 4px;
            vertical-align: top;
        }

        th {
            background: #e9ecef;
            font-size: 8px;
            text-align: center;
            text-transform: uppercase;
        }

        td.c {
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 3px;
            color: #fff;
            font-size: 8px;
            white-space: nowrap;
        }

        .toolbar {
            text-align: right;
            margin-bottom: 10px;
        }

        .toolbar button {
            padding: 7px 14px;
            font-size: 12px;
            cursor: pointer;
        }

        .footer {
            margin-top: 8px;
            font-size: 9px;
            text-align: right;
        }

        @media print {
            .toolbar {
                display: none;
            }

            body {
                padding: 0;
            }
        }
    </style>
</head>

<body>

    <div class="toolbar">
        <button type="button" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <h1>RISK ASSESSMENT &mdash; IDENTIFIKASI, ANALISIS, DAN EVALUASI RISIKO</h1>
    <div class="sub">Rencana Pengendalian &amp; Penanganan Risiko</div>
    <div class="meta">
        Unit: <strong><?= html_escape($nama_unit) ?></strong>
        &middot; Periode: <strong><?= (int) $tahun ?></strong>
        &middot; Jumlah risiko: <strong><?= count($daftar) ?></strong>
        <!-- &middot; Total biaya mitigasi: <strong><?= $rp($total_biaya) ?></strong> -->
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:22px">No</th>
                <th style="width:72px">No. ID Risiko</th>
                <th style="width:105px">Sasaran / KPI</th>
                <th style="width:110px">Risiko</th>
                <th style="width:85px">Sumber</th>
                <th style="width:95px">Penyebab</th>
                <th style="width:105px">Dampak</th>
                <th style="width:125px">Rencana Pengendalian &amp; Penanganan</th>
                <th style="width:90px">Deskripsi Biaya Mitigasi</th>
                <th style="width:75px">PIC</th>
                <th style="width:18px">K</th>
                <th style="width:18px">D</th>
                <th style="width:26px">Nilai</th>
                <th style="width:55px">Tingkat Risiko</th>
                <th style="width:50px">Keputusan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$daftar) : ?>
                <tr>
                    <td colspan="15" class="c">Tidak ada risiko yang cocok dengan filter.</td>
                </tr>
            <?php endif; ?>
            <?php $no = 0;
            foreach ($daftar as $r) : $no++; ?>
                <tr>
                    <td class="c"><?= $no ?></td>
                    <td><?= html_escape($r->kode_risiko) ?></td>
                    <td>
                        <?= $teks($r->sasaran_kpi) ?>
                        <?php if ($r->objektif !== '') : ?>
                            <br><em><?= $teks($r->objektif) ?></em>
                        <?php endif; ?>
                    </td>
                    <td><?= html_escape($r->risiko) ?></td>
                    <td><?= $teks($r->sumber_risiko) ?></td>
                    <td><?= $teks($r->penyebab) ?></td>
                    <td><?= $teks($r->dampak) ?></td>
                    <td>
                        <?php if (trim((string) $r->kontrol_uraian) !== '') : ?>
                            <em>Kontrol:</em> <?= $teks($r->kontrol_uraian) ?><br>
                        <?php endif; ?>
                        <?php if (trim((string) $r->uraian_tindakan) !== '') : ?>
                            <em>Rencana:</em> <?= $teks($r->uraian_tindakan) ?>
                        <?php endif; ?>
                        <?php if (trim((string) $r->kontrol_uraian) === '' && trim((string) $r->uraian_tindakan) === '') : ?>
                            &mdash;
                        <?php endif; ?>
                    </td>
                    <td><?= $rp($r->anggaran_biaya) ?></td>
                    <td>
                        <?= $r->nama_pic ? html_escape($r->nama_pic) : '&mdash;' ?>
                        <?php if ($r->jabatan_pic) : ?>
                            <br><em><?= html_escape($r->jabatan_pic) ?></em>
                        <?php endif; ?>
                    </td>
                    <td class="c"><?= $r->inherent_l !== null ? (int) $r->inherent_l : '&mdash;' ?></td>
                    <td class="c"><?= $r->inherent_d !== null ? (int) $r->inherent_d : '&mdash;' ?></td>
                    <td class="c"><strong><?= $r->nilai_inherent !== null ? (int) $r->nilai_inherent : '&mdash;' ?></strong></td>
                    <td class="c">
                        <?php if ($r->level_inherent !== null && $r->level_inherent !== '') : ?>
                            <?php $w = isset($warnaLevel[$r->level_inherent]) ? $warnaLevel[$r->level_inherent] : '#6c757d'; ?>
                            <span class="badge" style="background:<?= html_escape($w) ?>"><?= html_escape($r->level_inherent) ?></span>
                        <?php else : ?>
                            &mdash;
                        <?php endif; ?>
                    </td>
                    <td class="c"><?= $r->keputusan ? html_escape($r->keputusan) : '&mdash;' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        Dicetak dari aplikasi Ijen Tirta &mdash; Manajemen Risiko
    </div>

</body>

</html>