<?php defined('BASEPATH') or exit('No direct script access allowed');
function ubahNamaBulan($tanggal)
{
    $tgl_masuk = strtotime($tanggal);
    $day = date('d', $tgl_masuk);
    $bln = date('m', $tgl_masuk);
    $tahun = date('Y', $tgl_masuk);
    switch ($bln) {
        case '01':
            $bln = "Januari";
            break;
        case '02':
            $bln = "Februari";
            break;
        case '03':
            $bln = "Maret";
            break;
        case '04':
            $bln = "April";
            break;
        case '05':
            $bln = "Mei";
            break;
        case '06':
            $bln = "Juni";
            break;
        case '07':
            $bln = "Juli";
            break;
        case '08':
            $bln = "Agustus";
            break;
        case '09':
            $bln = "September";
            break;
        case '10':
            $bln = "Oktober";
            break;
        case '11':
            $bln = "November";
            break;
        case '12':
            $bln = "Desember";
            break;
    }
    return $day . ' ' . $bln . ' ' . $tahun;
}
