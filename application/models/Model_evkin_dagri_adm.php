<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_evkin_dagri_adm extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function hitung_aspek_adm($tahun)
    {
        $hasil_tahun_ini = $this->_getAspekAdm($tahun);
        $tahun_sebelumnya = $tahun - 1;
        $hasil_tahun_lalu = $this->_getAspekAdm($tahun_sebelumnya);


        // Hitung nilai tahun ini
        $nilai_tahun_ini = [
            'hasil_rjp' => $this->_nilaiAspekAdm($hasil_tahun_ini['rjp']),
            'hasil_rodut' => $this->_nilaiAspekAdm($hasil_tahun_ini['rodut']),
            'hasil_pos' => $this->_nilaiAspekAdm($hasil_tahun_ini['pos']),
            'hasil_gnl' => $this->_nilaiAspekAdm($hasil_tahun_ini['gnl']),
            'hasil_ppkk' => $this->_nilaiAspekAdm($hasil_tahun_ini['ppkk']),
            'hasil_rkdap' => $this->_nilaiAspekAdm($hasil_tahun_ini['rkdap']),
            'hasil_tle' => $this->_nilaiAspekAdm2($hasil_tahun_ini['tle']),
            'hasil_tli' => $this->_nilaiAspekAdm2($hasil_tahun_ini['tli']),
            'hasil_oai' => $this->_nilaiAspekAdm3($hasil_tahun_ini['oai']),
            'hasil_tlhptt' => $this->_nilaiAspekAdm4($hasil_tahun_ini['tlhptt']),
        ];

        // Hitung total nilai tahun ini
        $total_nilai_tahun_ini = array_sum($nilai_tahun_ini);
        $nilai_kinerja_adm_ini = $total_nilai_tahun_ini * 15 / 36;

        // Hitung nilai tahun lalu
        $nilai_tahun_lalu = [
            'hasil_rjp' => $this->_nilaiAspekAdm($hasil_tahun_lalu['rjp']),
            'hasil_rodut' => $this->_nilaiAspekAdm($hasil_tahun_lalu['rodut']),
            'hasil_pos' => $this->_nilaiAspekAdm($hasil_tahun_lalu['pos']),
            'hasil_gnl' => $this->_nilaiAspekAdm($hasil_tahun_lalu['gnl']),
            'hasil_ppkk' => $this->_nilaiAspekAdm($hasil_tahun_lalu['ppkk']),
            'hasil_rkdap' => $this->_nilaiAspekAdm($hasil_tahun_lalu['rkdap']),
            'hasil_tle' => $this->_nilaiAspekAdm2($hasil_tahun_lalu['tle']),
            'hasil_tli' => $this->_nilaiAspekAdm2($hasil_tahun_lalu['tli']),
            'hasil_oai' => $this->_nilaiAspekAdm3($hasil_tahun_lalu['oai']),
            'hasil_tlhptt' => $this->_nilaiAspekAdm4($hasil_tahun_lalu['tlhptt']),
        ];

        // Hitung total nilai tahun lalu
        $total_tahun_lalu = array_sum($nilai_tahun_lalu);
        $nilai_kinerja_adm_lalu = $total_tahun_lalu * 15 / 36;

        return [
            'tahun_ini' => [
                'tahun' => $tahun,
                'rjp' => $hasil_tahun_ini['rjp'],
                'hasil_rjp' => $this->_nilaiAspekAdm($hasil_tahun_ini['rjp']),
                'rodut' => $hasil_tahun_ini['rodut'],
                'hasil_rodut' => $this->_nilaiAspekAdm($hasil_tahun_ini['rodut']),
                'pos' => $hasil_tahun_ini['pos'],
                'hasil_pos' => $this->_nilaiAspekAdm($hasil_tahun_ini['pos']),
                'gnl' => $hasil_tahun_ini['gnl'],
                'hasil_gnl' => $this->_nilaiAspekAdm($hasil_tahun_ini['gnl']),
                'ppkk' => $hasil_tahun_ini['ppkk'],
                'hasil_ppkk' => $this->_nilaiAspekAdm($hasil_tahun_ini['ppkk']),
                'rkdap' => $hasil_tahun_ini['rkdap'],
                'hasil_rkdap' => $this->_nilaiAspekAdm($hasil_tahun_ini['rkdap']),
                'tle' => $hasil_tahun_ini['tle'],
                'hasil_tle' => $this->_nilaiAspekAdm2($hasil_tahun_ini['tle']),
                'tli' => $hasil_tahun_ini['tli'],
                'hasil_tli' => $this->_nilaiAspekAdm2($hasil_tahun_ini['tli']),
                'oai' => $hasil_tahun_ini['oai'],
                'hasil_oai' => $this->_nilaiAspekAdm3($hasil_tahun_ini['oai']),
                'tlhptt' => $hasil_tahun_ini['tlhptt'],
                'hasil_tlhptt' => $this->_nilaiAspekAdm4($hasil_tahun_ini['tlhptt']),
                'total_nilai' => $total_nilai_tahun_ini,
                'nilai_kinerja' => $nilai_kinerja_adm_ini
            ],
            'tahun_lalu' => [
                'tahun' => $tahun - 1,
                'rjp' => $hasil_tahun_lalu['rjp'],
                'hasil_rjp' => $this->_nilaiAspekAdm($hasil_tahun_lalu['rjp']),
                'rodut' => $hasil_tahun_lalu['rodut'],
                'hasil_rodut' => $this->_nilaiAspekAdm($hasil_tahun_lalu['rodut']),
                'pos' => $hasil_tahun_lalu['pos'],
                'hasil_pos' => $this->_nilaiAspekAdm($hasil_tahun_lalu['pos']),
                'gnl' => $hasil_tahun_lalu['gnl'],
                'hasil_gnl' => $this->_nilaiAspekAdm($hasil_tahun_lalu['gnl']),
                'ppkk' => $hasil_tahun_lalu['ppkk'],
                'hasil_ppkk' => $this->_nilaiAspekAdm($hasil_tahun_lalu['ppkk']),
                'rkdap' => $hasil_tahun_lalu['rkdap'],
                'hasil_rkdap' => $this->_nilaiAspekAdm($hasil_tahun_lalu['rkdap']),
                'tle' => $hasil_tahun_lalu['tle'],
                'hasil_tle' => $this->_nilaiAspekAdm2($hasil_tahun_lalu['tle']),
                'tli' => $hasil_tahun_lalu['tli'],
                'hasil_tli' => $this->_nilaiAspekAdm2($hasil_tahun_lalu['tli']),
                'oai' => $hasil_tahun_lalu['oai'],
                'hasil_oai' => $this->_nilaiAspekAdm3($hasil_tahun_lalu['oai']),
                'tlhptt' => $hasil_tahun_lalu['tlhptt'],
                'hasil_tlhptt' => $this->_nilaiAspekAdm4($hasil_tahun_lalu['tlhptt']),
                'total_nilai' => $total_tahun_lalu,
                'nilai_kinerja' => $nilai_kinerja_adm_lalu
            ]
        ];
    }

    private function _getAspekAdm($tahun)
    {
        $aspek_adm = $this->db->select('*')
            ->from('ek_aspek_adm_dagri')
            ->where('tahun_aspek', $tahun)
            ->get()
            ->result();

        $rjp = $rodut = $pos = $gnl = $ppkk = $rkdap = $tli = $tle = $oai = $tlhptt = '-';

        foreach ($aspek_adm as $row) {
            switch (trim($row->penilaian)) { // gunakan trim() untuk membersihkan spasi
                case 'Rencana Jangka Panjang':
                    $rjp = $row->hasil ?? '-';
                    break;
                case 'Rencana Organisasi dan Uraian Tugas':
                    $rodut = $row->hasil ?? '-';
                    break;
                case 'Prosedur Operasi Standar':
                    $pos = $row->hasil ?? '-';
                    break;
                case 'Gambar Nyata Laksana':
                    $gnl = $row->hasil ?? '-';
                    break;
                case 'Pedoman Penilaian Kerja Karyawan':
                    $ppkk = $row->hasil ?? '-';
                    break;
                case 'Rencana Kerja dan Anggaran Perusahaan':
                    $rkdap = $row->hasil ?? '-';
                    break;
                case 'Tertib Laporan Internal':
                    $tli = $row->hasil ?? '-';
                    break;
                case 'Tertib Laporan Eksternal':
                    $tle = $row->hasil ?? '-';
                    break;
                case 'Opini Auditor Independen':
                    $oai = $row->hasil ?? '-';
                    break;
                case 'Tidak Lanjut Hasil Pemeriksaan tahun terakhir':
                    $tlhptt = $row->hasil ?? '-';
                    break;
            }
        }

        return [
            'rjp' => $rjp,
            'rodut' => $rodut,
            'pos' => $pos,
            'gnl' => $gnl,
            'ppkk' => $ppkk,
            'rkdap' => $rkdap,
            'tli' => $tli,
            'tle' => $tle,
            'oai' => $oai,
            'tlhptt' => $tlhptt,
        ];
    }

    private function _nilaiAspekAdm($hasil)
    {
        if ($hasil === 'Sepenuhnya dipedomani') {
            return 4;
        } elseif ($hasil === 'Dipedomani sebagian') {
            return 3;
        } elseif ($hasil === 'Memiliki, belum dipedomani') {
            return 2;
        } elseif ($hasil === 'Tidak memiliki') {
            return 1;
        }

        return 0; // default jika tidak dikenal
    }

    private function _nilaiAspekAdm2($hasil)
    {
        if ($hasil === 'Dibuat tepat waktu') {
            return 2;
        } elseif ($hasil === 'Tidak tepat waktu') {
            return 1;
        }

        return 0; // default jika tidak dikenal
    }
    private function _nilaiAspekAdm3($hasil)
    {
        if ($hasil === 'Wajar tanpa pengecualian') {
            return 4;
        } elseif ($hasil === 'Wajar dengan pengecualian') {
            return 3;
        } elseif ($hasil === 'Tidak memberikan pendapat') {
            return 2;
        } elseif ($hasil === 'Pendapat tidak wajar') {
            return 1;
        }

        return 0; // default jika tidak dikenal
    }
    private function _nilaiAspekAdm4($hasil)
    {
        if ($hasil === 'Tidak ada temuan') {
            return 4;
        } elseif ($hasil === 'Ditindaklanjuti, seluruhnya selesai') {
            return 3;
        } elseif ($hasil === 'Ditindaklanjuti, sebagian selesai') {
            return 2;
        } elseif ($hasil === 'Tidak ditindaklanjuti') {
            return 1;
        }

        return 0; // default jika tidak dikenal
    }
}
