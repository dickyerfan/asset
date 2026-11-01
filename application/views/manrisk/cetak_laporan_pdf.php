<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= html_escape($title) ?></title>
    <style>
        @page { margin: 18mm 16mm 18mm 16mm; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #111; line-height: 1.45; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 14px; }
        .header h2 { margin: 0; font-size: 15px; letter-spacing: .5px; }
        .header h3 { margin: 2px 0 0; font-size: 12px; font-weight: normal; }
        .header p { margin: 3px 0 0; font-size: 9px; }
        .judul { text-align: center; margin: 12px 0 14px; }
        .judul h4 { margin: 0; font-size: 13px; text-decoration: underline; }
        .judul p { margin: 3px 0 0; font-size: 10px; }
        .identitas { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .identitas td { padding: 2px 4px; vertical-align: top; font-size: 10px; }
        .identitas td.label { width: 120px; font-weight: bold; }
        h5 { font-size: 11px; margin: 14px 0 5px; padding: 4px 6px; background: #e9ecef; border-left: 4px solid #343a40; }
        .isi { text-align: justify; margin: 0 0 8px; white-space: pre-line; }
        table.data { width: 100%; border-collapse: collapse; margin: 4px 0 10px; }
        table.data th, table.data td { border: 1px solid #555; padding: 3px 4px; font-size: 9px; vertical-align: top; }
        table.data th { background: #f1f3f5; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .ttd { width: 100%; margin-top: 28px; page-break-inside: avoid; }
        .ttd td { width: 50%; text-align: center; font-size: 10px; vertical-align: bottom; }
        .ttd .ruang { height: 55px; }
        .small { font-size: 8.5px; color: #444; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>

<div class="header">
    <h2>PERUMDAM IJEN TIRTA</h2>
    <h3>SISTEM MANAJEMEN RISIKO</h3>
    <p>Laporan sesuai SNI ISO 31000:2018 &middot; Dokumentasi MR A10.1</p>
</div>

<div class="judul">
    <h4><?= html_escape($title) ?></h4>
    <p>Periode <?= html_escape($triwulan) ?> Tahun <?= (int) $laporan->tahun ?> &middot; <?= html_escape($nama_unit) ?></p>
</div>

<table class="identitas">
    <tr>
        <td class="label">Jenis Laporan</td><td>: <?= html_escape($laporan->jenis_laporan) ?></td>
        <td class="label">Status</td><td>: <?= html_escape($laporan->status) ?></td>
    </tr>
    <tr>
        <td class="label">Cakupan</td><td>: <?= html_escape($nama_unit) ?></td>
        <td class="label">Triwulan</td><td>: <?= html_escape($triwulan) ?></td>
    </tr>
    <tr>
        <td class="label">Disahkan Oleh</td><td>: <?= html_escape($laporan->disahkan_oleh ?: '-') ?></td>
        <td class="label">Tanggal Sah</td><td>: <?= html_escape($laporan->tanggal_sahkan ?: '-') ?></td>
    </tr>
</table>

<?php
$bagian = [
    'Ringkasan Eksekutif'        => $laporan->ringkasan_eksekutif,
    'Latar Belakang'             => $laporan->latar_belakang,
    'Tujuan Laporan'             => $laporan->tujuan_laporan,
    'Ruang Lingkup'              => $laporan->ruang_lingkup,
    'Rekap Inherent &amp; Residual' => $laporan->rekap_inherent_residual,
    'Catatan Peta Risiko'        => $laporan->catatan_peta_risiko,
    'Rekap Peluang'              => $laporan->rekap_peluang,
    'Hasil KRI'                  => $laporan->hasil_kri,
    'Evaluasi &amp; Tindak Lanjut'  => $laporan->evaluasi_tindak_lanjut,
    'Kesimpulan'                 => $laporan->kesimpulan,
    'Rencana Tindak Lanjut'      => $laporan->rencana_tindak_lanjut,
    'Sumber / Catatan Kaki'      => $laporan->sumber_catatan,
];
foreach ($bagian as $judul => $isi) :
    if (trim((string) $isi) === '') {
        continue;
    }
?>
    <h5><?= $judul ?></h5>
    <div class="isi"><?= nl2br(html_escape($isi)) ?></div>
<?php endforeach; ?>

<h5>Lampiran &mdash; Prioritas Risiko Residual</h5>
<table class="data">
    <thead>
        <tr>
            <th style="width:28px">No</th>
            <th style="width:78px">Kode</th>
            <th>Risiko</th>
            <th style="width:48px">Skor</th>
            <th style="width:80px">Level</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($data['ringkasan']['prioritas'])) : ?>
            <tr><td colspan="5" class="text-center">Belum ada risiko dengan skor residual.</td></tr>
        <?php endif; ?>
        <?php foreach ($data['ringkasan']['prioritas'] as $i => $r) : ?>
            <tr>
                <td class="text-center"><?= $i + 1 ?></td>
                <td><?= html_escape($r->kode_risiko) ?></td>
                <td><?= html_escape($r->risiko) ?></td>
                <td class="text-center"><?= (int) $r->nilai_residual ?></td>
                <td><?= html_escape($r->level_residual) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h5>Lampiran &mdash; Key Risk Indicator (KRI)</h5>
<table class="data">
    <thead>
        <tr>
            <th style="width:28px">No</th>
            <th>KRI</th>
            <th style="width:65px">Target</th>
            <th style="width:65px">Aktual</th>
            <th style="width:85px">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($data['kri'])) : ?>
            <tr><td colspan="5" class="text-center">Belum ada KRI.</td></tr>
        <?php endif; ?>
        <?php foreach ($data['kri'] as $i => $k) : ?>
            <tr>
                <td class="text-center"><?= $i + 1 ?></td>
                <td><?= html_escape($k->nama_kri) ?></td>
                <td class="text-center"><?= $k->target !== null ? html_escape($k->target) : '-' ?></td>
                <td class="text-center"><?= $k->nilai_aktual !== null ? html_escape($k->nilai_aktual) : '-' ?></td>
                <td><?= html_escape($k->status_kri) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h5>Lampiran &mdash; Monitoring Triwulan</h5>
<table class="data">
    <thead>
        <tr>
            <th style="width:28px">No</th>
            <th style="width:70px">Kode</th>
            <th>Risiko</th>
            <th style="width:80px">Status</th>
            <th>Hasil Monitoring</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($data['monitoring'])) : ?>
            <tr><td colspan="5" class="text-center">Belum ada catatan monitoring pada periode ini.</td></tr>
        <?php endif; ?>
        <?php foreach ($data['monitoring'] as $i => $m) : ?>
            <tr>
                <td class="text-center"><?= $i + 1 ?></td>
                <td><?= html_escape($m->kode_risiko) ?></td>
                <td><?= html_escape($m->risiko) ?></td>
                <td><?= html_escape($m->status_tindakan ?: '-') ?></td>
                <td><?= nl2br(html_escape($m->hasil_monitoring ?: '-')) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<table class="ttd">
    <tr>
        <td>
            Mengetahui,<br>
            <?= html_escape($nama_unit) ?><br>
            <div class="ruang"></div>
            <u><?= html_escape($laporan->disahkan_oleh ?: '..............................') ?></u>
        </td>
        <td>
            Disusun,<br>
            Pengelola Risiko<br>
            <div class="ruang"></div>
            <u>..............................</u>
        </td>
    </tr>
</table>

<p class="small">
    Dokumen ini dihasilkan otomatis dari Sistem Manajemen Risiko Perumdam Ijen Tirta.
    Status dokumen: <?= html_escape($laporan->status) ?>.
</p>

</body>
</html>
