<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Impor Risk Register dari CSV.
 *
 * SUMBER FORMAT: D:\MR 2026\Risk_Register_Template_Perumdam_Ijen_Tirta.csv
 *
 * Fakta format yang sudah diverifikasi terhadap file asli:
 *   - pemisah kolom      : ';'
 *   - encoding           : UTF-8 dengan BOM (utf-8-sig)
 *   - jumlah kolom       : 18, konsisten di seluruh baris
 *   - baris data         : 16, lalu baris kosong + blok KETERANGAN/LEGEND
 *   - header "K" dan "D" muncul DUA KALI (kolom 7/8 inherent, 11/12 residual)
 *     -> pemetaan WAJIB berdasar POSISI, tidak boleh berdasar nama kolom
 *   - legenda pada file menyatakan:
 *       K = Likelihood, D = Consequence, Nilai = K x D
 *       Inherent = Nilai sebelum kontrol, Residual = Nilai sesudah kontrol
 *       1-Rendah (1-5), 2-Sedang (6-9), 3-Tinggi (10-15), 4-Kritis (16-25)
 *     -> penomoran 1..4 milik file CSV (baris footer) dan TIDAK dipakai
 *        saat impor. Level dihitung ulang lewat levelDariSkor() sehingga
 *        otomatis mengikuti mri_tingkat_risiko.
 *
 * Prinsip impor:
 *   - baris dengan ID RISIKO kosong dianggap footer/legenda dan dilewati
 *   - nilai invarian (L x D) tidak pernah dipaksa; selisih dilaporkan
 *   - kode_risiko yang sudah ada TIDAK ditimpa kecuali $opsi['timpa'] = true
 *   - label kategori ambigu (memuat '/' atau '|') ditandai untuk dipilih manual
 */
class Model_mri_import extends CI_Model
{
    const DELIMITER = ';';

    /** Posisi kolom (0-based) sesuai file asli. */
    const POS = [
        'no'                  => 0,
        'kode_risiko'         => 1,
        'kategori'            => 2,
        'sasaran_kpi'         => 3,
        'risiko'              => 4,
        'sumber_risiko'       => 5,
        'dampak'              => 6,
        'inherent_l'          => 7,
        'inherent_d'          => 8,
        'nilai_inherent'      => 9,
        'kontrol_uraian'      => 10,
        'residual_l'          => 11,
        'residual_d'          => 12,
        'nilai_residual'      => 13,
        'treatment'           => 14,
        'risk_owner'          => 15,
        'status'              => 16,
        'keterangan'          => 17,
    ];

    /** Pemetaan label kategori pada CSV -> kode_kat di mri_kategori_risiko. */
    const MAP_KATEGORI = [
        'operational'       => 'OPE',
        'strategic'         => 'STR',
        'finance'           => 'FIN',
        'financial'         => 'FIN',
        'liquidity'         => 'LIQ',
        'credit'            => 'CRE',
        'market'            => 'MKT',
        'legal'             => 'LEG',
        'regulatory'        => 'REG',
        'reputation'        => 'REP',
        'technology'        => 'TEC',
        'fraud'             => 'FRD',
        'personnel'         => 'PER',
        'event'             => 'EVT',
        'event/external'    => 'EVT',
        'hse'               => 'HSE',
        'health, safety & environment' => 'HSE',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_mri_master');
        $this->load->model('Model_mri_register');
        $this->load->model('Model_mri_analisa');
        $this->load->model('Model_mri_perlakuan');
    }

    private function user()
    {
        $u = $this->session->userdata('nama_pengguna');
        return $u ?: 'sistem';
    }

    /**
     * Baca & validasi CSV tanpa menyimpan apa pun.
     *
     * @param string $path path absolut file CSV
     * @return array {
     *   'rows'    => array baris valid siap disimpan,
     *   'footer'  => jumlah baris footer/legenda yang dilewati,
     *   'errors'  => array pesan galat per baris,
     *   'warnings'=> array pesan peringatan per baris,
     *   'header'  => array header asli,
     *   'kolom'   => jumlah kolom terbaca
     * }
     */
    public function baca($path)
    {
        $hasil = [
            'rows'     => [],
            'footer'   => 0,
            'errors'   => [],
            'warnings' => [],
            'header'   => [],
            'kolom'    => 0,
        ];

        if (!is_file($path) || !is_readable($path)) {
            $hasil['errors'][] = 'File tidak ditemukan atau tidak dapat dibaca: ' . $path;
            return $hasil;
        }

        $isi = file_get_contents($path);
        if ($isi === false) {
            $hasil['errors'][] = 'Gagal membaca file.';
            return $hasil;
        }
        // buang BOM UTF-8 bila ada
        if (substr($isi, 0, 3) === "\xEF\xBB\xBF") {
            $isi = substr($isi, 3);
        }

        $baris = str_getcsv($isi, "\n");
        if (count($baris) < 2) {
            $hasil['errors'][] = 'File tidak berisi baris data.';
            return $hasil;
        }

        $header = str_getcsv(array_shift($baris), self::DELIMITER);
        $hasil['header'] = $header;
        $hasil['kolom'] = count($header);

        if (count($header) !== 18) {
            $hasil['errors'][] = 'Jumlah kolom header ' . count($header) . ', seharusnya 18.';
            return $hasil;
        }

        $kunci = $this->petaKategori();

        foreach ($baris as $nomorBaris => $barisCsv) {
            $nomorBaris += 2; // baris 1 = header
            if (trim((string) $barisCsv) === '') {
                continue;
            }
            $kolom = str_getcsv($barisCsv, self::DELIMITER);

            if (count($kolom) !== 18) {
                $hasil['errors'][] = "Baris $nomorBaris: jumlah kolom " . count($kolom) . ', seharusnya 18.';
                continue;
            }

            $kode = trim($kolom[self::POS['kode_risiko']]);
            if ($kode === '') {
                // baris kosong
                $hasil['footer']++;
                continue;
            }
            // Baris legenda/file keterangan memakai teks bebas di posisi kolom
            // kedua ("KETERANGAN KATEGORI RISIKO", "LEGEND", "K = Likelihood ...").
            // Karena tidak diawali "IJT-", ini pasti footer, bukan data rusak.
            if (stripos($kode, 'IJT-') !== 0) {
                $hasil['footer']++;
                continue;
            }
            // Diawali IJT- tetapi tidak lengkap = data rusak, harus dilaporkan.
            if (!preg_match('/^IJT-[A-Z0-9]+-\d+$/i', $kode)) {
                $hasil['errors'][] = "Baris $nomorBaris: kode risiko '$kode' tidak sesuai pola IJT-XXX-NN.";
                continue;
            }
            $kode = strtoupper($kode);

            $il = (int) trim($kolom[self::POS['inherent_l']]);
            $id = (int) trim($kolom[self::POS['inherent_d']]);
            $rl = (int) trim($kolom[self::POS['residual_l']]);
            $rd = (int) trim($kolom[self::POS['residual_d']]);

            foreach ([
                ['L inherent', $il], ['D inherent', $id],
                ['L residual', $rl], ['D residual', $rd],
            ] as $u) {
                if ($u[1] < 1 || $u[1] > 5) {
                    $hasil['errors'][] = "Baris $nomorBaris ($kode): {$u[0]} = {$u[1]} di luar rentang 1-5.";
                    continue 2;
                }
            }

            $hi = $il * $id;
            $hr = $rl * $rd;
            $ci = (int) trim($kolom[self::POS['nilai_inherent']]);
            $cr = (int) trim($kolom[self::POS['nilai_residual']]);

            if ($ci !== $hi) {
                $hasil['warnings'][] = "Baris $nomorBaris ($kode): nilai inherent tertulis $ci, L x D = $hi. Yang dipakai $hi.";
            }
            if ($cr !== $hr) {
                $hasil['warnings'][] = "Baris $nomorBaris ($kode): nilai residual tertulis $cr, L x D = $hr. Yang dipakai $hr.";
            }
            if ($rl > $il || $rd > $id) {
                $hasil['warnings'][] = "Baris $nomorBaris ($kode): residual (L=$rl,D=$rd) lebih tinggi dari inherent (L=$il,D=$id). Periksa kembali.";
            }

            $labelKat = trim($kolom[self::POS['kategori']]);
            $kodeKat = $this->petakanKategori($labelKat, $kunci, $hasil, $nomorBaris, $kode);

            $level = function ($skor) {
                $t = $this->Model_mri_master->levelDariSkor($skor);
                return $t ? $t->nama_tingkat : null;
            };

            $hasil['rows'][] = [
                'baris'            => $nomorBaris,
                'kode_risiko'      => $kode,
                'label_kategori'   => $labelKat,
                'id_kategori'      => $kodeKat,
                'sasaran_kpi'      => trim($kolom[self::POS['sasaran_kpi']]),
                'risiko'           => trim($kolom[self::POS['risiko']]),
                'sumber_risiko'    => trim($kolom[self::POS['sumber_risiko']]),
                'dampak'           => trim($kolom[self::POS['dampak']]),
                'inherent_l'       => $il,
                'inherent_d'       => $id,
                'nilai_inherent'   => $hi,
                'level_inherent'   => $level($hi),
                'kontrol_uraian'   => trim($kolom[self::POS['kontrol_uraian']]),
                'residual_l'       => $rl,
                'residual_d'       => $rd,
                'nilai_residual'   => $hr,
                'level_residual'   => $level($hr),
                'treatment'        => trim($kolom[self::POS['treatment']]),
                'risk_owner'       => trim($kolom[self::POS['risk_owner']]),
                'status'           => trim($kolom[self::POS['status']]),
                'keterangan'       => trim($kolom[self::POS['keterangan']]),
            ];
        }

        return $hasil;
    }

    /** [strtolower(label) => id_kategori] dari master. */
    private function petaKategori()
    {
        $peta = [];
        foreach ($this->Model_mri_master->getKategori(false) as $k) {
            $peta[strtolower($k->nama_kat)] = $k->id_kategori;
            if ($k->nama_alias) {
                $peta[strtolower($k->nama_alias)] = $k->id_kategori;
            }
            $peta[strtolower($k->kode_kat)] = $k->id_kategori;
        }
        return $peta;
    }

    /**
     * Petakan label kategori CSV ke id_kategori.
     * Label berisi '/' atau '|' ditandai ambigu dan dipetakan ke token pertama,
     * karena keputusan kategori primer tidak boleh ditebak mesin.
     */
    private function petakanKategori($label, $peta, &$hasil, $baris, $kode)
    {
        $asli = $label;
        $ambigu = preg_match('#[/|]#', $label) === 1;
        if ($ambigu) {
            $label = trim(preg_split('#[/|]#', $label)[0]);
        }

        $turun = strtolower(trim($label));

        if (isset($peta[$turun])) {
            if ($ambigu) {
                $hasil['warnings'][] = "Baris $baris ($kode): kategori '$asli' majemuk, dipetakan sementara ke '$label'. Pilih kategori primer secara manual.";
            }
            return $peta[$turun];
        }
        if (isset(self::MAP_KATEGORI[$turun])) {
            $kodeKat = self::MAP_KATEGORI[$turun];
            $tujuan = null;
            foreach ($this->Model_mri_master->getKategori(false) as $k) {
                if ($k->kode_kat === $kodeKat) {
                    $tujuan = $k->id_kategori;
                    break;
                }
            }
            if ($ambigu) {
                $hasil['warnings'][] = "Baris $baris ($kode): kategori '$asli' majemuk, dipetakan sementara ke '$label' ($kodeKat). Pilih kategori primer secara manual.";
            }
            if ($tujuan) {
                return $tujuan;
            }
        }

        $hasil['errors'][] = "Baris $baris ($kode): kategori '$asli' tidak dikenali. Pilih manual dari 14 kategori.";
        return null;
    }

    /**
     * Eksekusi impor hasil baca().
     *
     * @param array $hasil hasil dari baca()
     * @param array $opsi {
     *   'tahun'        => int (wajib)
     *   'triwulan'     => int (default 1)
     *   'id_upk'       => int (wajib, unit pemilik data)
     *   'id_lingkup'   => int|null
     *   'timpa'        => bool, timpa kode_risiko yang sudah ada (default false)
     *   'id_kategori_default' => int, kategori bila petakan gagal
     * }
     * @return array {
     *   'impan'    => jumlah baris tersimpan,
     *   'lewati'   => jumlah baris dilewati (sudah ada),
     *   'gagal'    => jumlah baris gagal,
     *   'rincian'  => array pesan per baris,
     *   'id_baru'  => array id_register yang dibuat
     * }
     */
    public function impor($hasil, $opsi)
    {
        $lapor = ['impan' => 0, 'lewati' => 0, 'gagal' => 0, 'rincian' => [], 'id_baru' => []];

        $tahun = isset($opsi['tahun']) ? (int) $opsi['tahun'] : 0;
        if (!$tahun) {
            $lapor['rincian'][] = 'Tahun wajib diisi.';
            $lapor['gagal'] = count($hasil['rows']);
            return $lapor;
        }
        if (empty($opsi['id_upk'])) {
            $lapor['rincian'][] = 'Unit (id_upk) wajib diisi.';
            $lapor['gagal'] = count($hasil['rows']);
            return $lapor;
        }

        $timpa = !empty($opsi['timpa']);
        $triwulan = isset($opsi['triwulan']) ? (int) $opsi['triwulan'] : 1;
        $idLingkup = isset($opsi['id_lingkup']) && $opsi['id_lingkup'] !== '' ? (int) $opsi['id_lingkup'] : null;
        $katDefault = !empty($opsi['id_kategori_default']) ? (int) $opsi['id_kategori_default'] : null;

        foreach ($hasil['rows'] as $r) {
            $kode = $r['kode_risiko'];

            if ($r['id_kategori'] === null && $katDefault) {
                $r['id_kategori'] = $katDefault;
            }
            if ($r['id_kategori'] === null) {
                $lapor['gagal']++;
                $lapor['rincian'][] = "$kode: kategori belum dipilih, baris dilewati.";
                continue;
            }

            $ada = $this->Model_mri_register->getByKode($kode);
            if ($ada && !$timpa) {
                $lapor['lewati']++;
                $lapor['rincian'][] = "$kode: sudah ada di register, dilewati (aktifkan timpa untuk mengganti).";
                continue;
            }
            if ($ada && $timpa && $ada->tahun != $tahun) {
                $lapor['lewati']++;
                $lapor['rincian'][] = "$kode: sudah dipakai tahun lain, tidak ditimpa.";
                continue;
            }

            $this->db->trans_begin();

            $d = [
                'kode_risiko'       => $kode,
                'tahun'             => $tahun,
                'triwulan_awal'     => $triwulan,
                'id_upk'            => (int) $opsi['id_upk'],
                'id_lingkup'        => $idLingkup,
                'id_kategori'       => (int) $r['id_kategori'],
                'tipe_risiko'       => 'Ancaman',
                'kegiatan'          => $r['sasaran_kpi'],
                'sasaran_kpi'       => $r['sasaran_kpi'],
                'sumber_risiko'     => $r['sumber_risiko'],
                'risiko'            => $r['risiko'],
                'penyebab'          => $r['sumber_risiko'],
                'peristiwa_risiko'  => $r['risiko'],
                'status_kendali'    => 'C',
                'status_risiko'     => 'AKTIF',
                'keterangan'        => $r['keterangan'],
            ];

            $id = $this->Model_mri_register->simpan(
                $ada ? $ada->id_register : null,
                $d,
                [['akibat' => $r['dampak'], 'jenis' => 'Non-Finansial']],
                [],
                []
            );

            if (!$id) {
                $this->db->trans_rollback();
                $lapor['gagal']++;
                $lapor['rincian'][] = "$kode: gagal menyimpan.";
                continue;
            }

            $this->Model_mri_analisa->simpan($id, [
                'inherent_l'     => $r['inherent_l'],
                'inherent_d'     => $r['inherent_d'],
                'residual_l'     => $r['residual_l'],
                'residual_d'     => $r['residual_d'],
                'kontrol_uraian' => $r['kontrol_uraian'],
                'kontrol_ada'    => $r['kontrol_uraian'] !== '' ? 1 : 0,
                'memadai'        => 0,
                'ringkasan'      => $r['keterangan'],
            ]);

            if ($r['treatment'] !== '') {
                $this->Model_mri_perlakuan->simpan($id, [
                    'uraian_tindakan' => $r['treatment'],
                    'status_laksana'  => 'BELUM',
                ]);
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $lapor['gagal']++;
                $lapor['rincian'][] = "$kode: transaksi dibatalkan.";
                continue;
            }
            $this->db->trans_commit();

            $lapor['impan']++;
            $lapor['id_baru'][] = $id;
            $lapor['rincian'][] = "$kode: " . ($ada ? 'diperbarui' : 'ditambahkan')
                . " (L{$r['inherent_l']}x D{$r['inherent_d']} = {$r['nilai_inherent']}, "
                . "residual L{$r['residual_l']}x D{$r['residual_d']} = {$r['nilai_residual']}).";
        }

        return $lapor;
    }

    /**
     * Nilai petakan risiko ke PIC/risk owner berdasar nama pada CSV.
     * Tidak pernah membuat orang baru — bila tidak cocok, kembalikan null
     * agar pengisi memilih sendiri.
     */
    public function cariRiskOwner($nama)
    {
        $nama = trim((string) $nama);
        if ($nama === '') {
            return null;
        }
        $this->db->where('status', 1);
        $this->db->like('jabatan_owner', $nama);
        $row = $this->db->get('mri_risk_owner')->row();
        if ($row) {
            return $row->id_risk_owner;
        }
        $this->db->where('status', 1);
        $this->db->like('nama_owner', $nama);
        $row = $this->db->get('mri_risk_owner')->row();
        return $row ? $row->id_risk_owner : null;
    }
}
