<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model checklist & target manajemen risiko.
 *
 * Prinsip:
 *   - Template butir checklist tetap (tabel mri_checklist_item) dan dipakai
 *     untuk semua tahun.
 *   - Target disimpan per tahun. id_upk = 0 berarti target global; id_upk > 0
 *     adalah override untuk unit tersebut.
 *   - Butir otomatis dihitung dari tabel mri_* yang sudah ada.
 *   - Butir manual disimpan per tahun + unit di mri_checklist_centang.
 *
 * Halaman baru tidak perlu dibuat setiap tahun: cukup ganti filter tahun.
 */
class Model_mri_checklist extends CI_Model
{
    /** @var array|null cache template butir */
    private $itemsCache = null;

    /** @var array|null cache daftar tahap */
    private $tahapCache = null;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_mri_master');
    }

    // =================================================================
    // Definisi tahap & butir
    // =================================================================

    /**
     * Tahap 0 (persiapan & sasaran) + 8 tahap ISO 31000 dari master.
     * @return array
     */
    public function tahapan()
    {
        if ($this->tahapCache !== null) {
            return $this->tahapCache;
        }

        $out = [[
            'no'      => 0,
            'judul'   => 'Pengaturan & Sasaran',
            'rute'    => 'manrisk/objektif',
            'halaman' => 'Pengaturan / Objektif',
            'catatan' => 'Skala, appetite, kunci periode, dan sasaran/RKAP sebelum proses dimulai.',
        ]];

        foreach ($this->Model_mri_master->tahapan() as $t) {
            $out[] = $t;
        }

        return $this->tahapCache = $out;
    }

    /**
     * Semua butir aktif, urut tahap lalu urutan.
     * @return array
     */
    public function items()
    {
        if ($this->itemsCache !== null) {
            return $this->itemsCache;
        }

        if (!$this->db->table_exists('mri_checklist_item')) {
            return $this->itemsCache = [];
        }

        $this->db
            ->where('aktif', 1)
            ->order_by('tahap_no', 'ASC')
            ->order_by('urutan', 'ASC')
            ->order_by('kode_item', 'ASC');

        return $this->itemsCache = $this->db->get('mri_checklist_item')->result();
    }

    /**
     * Butir dikelompokkan per tahap. Objek di-clone agar properti hasil
     * hitung (num/den/persen) tidak bocor antar unit saat matriks dihitung.
     * @return array<int, array>
     */
    public function itemsPerTahap()
    {
        $out = [];
        foreach ($this->items() as $it) {
            $out[(int) $it->tahap_no][] = clone $it;
        }
        return $out;
    }

    /**
     * Daftar tahun untuk dropdown filter.
     * Selalu memuat tahun aktif + rentang tahun berjalan.
     *
     * @param int|null $tahunAktif
     * @return array
     */
    public function daftarTahun($tahunAktif = null)
    {
        $tahun = [];

        foreach ($this->db->select('tahun')->distinct()->get('mri_register')->result() as $r) {
            $tahun[] = (int) $r->tahun;
        }

        if ($this->db->table_exists('mri_checklist_target')) {
            foreach ($this->db->select('tahun')->distinct()->get('mri_checklist_target')->result() as $r) {
                $tahun[] = (int) $r->tahun;
            }
        }

        $kini = (int) date('Y');
        for ($i = $kini - 1; $i <= $kini + 5; $i++) {
            $tahun[] = $i;
        }

        if ($tahunAktif !== null) {
            $tahun[] = (int) $tahunAktif;
        }

        $tahun = array_values(array_unique(array_filter($tahun, function ($t) {
            return $t > 0;
        })));
        sort($tahun);

        return $tahun;
    }

    /** Nama unit dari bagian_upk. */
    public function namaUnit($id_upk)
    {
        $id_upk = (int) $id_upk;
        if ($id_upk <= 0) {
            return 'Semua UPK / Global';
        }
        $row = $this->db->select('nama_bagian')->get_where('bagian_upk', ['id_bagian' => $id_upk])->row();
        return $row ? $row->nama_bagian : ('Unit #' . $id_upk);
    }

    // =================================================================
    // Target
    // =================================================================

    /**
     * Target level tahap yang berlaku untuk satu tahun + unit.
     * Override unit menimpa target global.
     *
     * @return array<int, object> keyed tahap_no
     */
    public function targetTahap($tahun, $id_upk = 0)
    {
        if (!$this->db->table_exists('mri_checklist_target')) {
            return [];
        }

        $rows = $this->db
            ->where('tahun', (int) $tahun)
            ->where('kode_item', '')
            ->where_in('id_upk', $this->scopeIds($id_upk))
            ->order_by('id_upk', 'ASC')
            ->get('mri_checklist_target')
            ->result();

        $out = [];
        foreach ($rows as $r) {
            $r->_scope = ((int) $r->id_upk === 0) ? 'global' : 'unit';
            $out[(int) $r->tahap_no] = $r;
        }
        return $out;
    }

    /**
     * Target level butir yang berlaku untuk satu tahun + unit.
     * @return array<string, object> keyed kode_item
     */
    public function targetItem($tahun, $id_upk = 0)
    {
        if (!$this->db->table_exists('mri_checklist_target')) {
            return [];
        }

        $rows = $this->db
            ->where('tahun', (int) $tahun)
            ->where('kode_item <>', '')
            ->where_in('id_upk', $this->scopeIds($id_upk))
            ->order_by('id_upk', 'ASC')
            ->get('mri_checklist_target')
            ->result();

        $out = [];
        foreach ($rows as $r) {
            $r->_scope = ((int) $r->id_upk === 0) ? 'global' : 'unit';
            $out[$r->kode_item] = $r;
        }
        return $out;
    }

    /**
     * Simpan target tahap + target butir.
     *
     * @param int    $tahun
     * @param int    $id_upk 0 = global, >0 = override unit
     * @param array  $tahap  [no => ['mulai','selesai','pic']]
     * @param array  $item   [kode_item => ['mulai','selesai','pic','catatan']]
     * @param string $user
     * @return bool
     */
    public function simpanTarget($tahun, $id_upk, $tahap, $item, $user)
    {
        if (!$this->db->table_exists('mri_checklist_target')) {
            return false;
        }

        $tahun = (int) $tahun;
        $scope = (int) $id_upk > 0 ? (int) $id_upk : 0;
        $user  = (string) $user;
        $tahap = is_array($tahap) ? $tahap : [];
        $item  = is_array($item) ? $item : [];

        // Target per tahap.
        foreach ($this->tahapan() as $t) {
            $no  = (int) $t['no'];
            $row = isset($tahap[$no]) && is_array($tahap[$no]) ? $tahap[$no] : [];

            $this->upsertTarget(
                $tahun,
                $scope,
                $no,
                '',
                $this->tanggal(isset($row['mulai']) ? $row['mulai'] : ''),
                $this->tanggal(isset($row['selesai']) ? $row['selesai'] : ''),
                isset($row['pic']) ? $row['pic'] : '',
                isset($row['catatan']) ? $row['catatan'] : '',
                $user
            );
        }

        // Target per butir. Hanya kode yang dikenal yang diproses.
        $peta = [];
        foreach ($this->items() as $it) {
            $peta[$it->kode_item] = $it;
        }

        foreach ($item as $kode => $row) {
            if (!isset($peta[$kode]) || !is_array($row)) {
                continue;
            }

            $this->upsertTarget(
                $tahun,
                $scope,
                (int) $peta[$kode]->tahap_no,
                (string) $kode,
                $this->tanggal(isset($row['mulai']) ? $row['mulai'] : ''),
                $this->tanggal(isset($row['selesai']) ? $row['selesai'] : ''),
                isset($row['pic']) ? $row['pic'] : '',
                isset($row['catatan']) ? $row['catatan'] : '',
                $user
            );
        }

        return true;
    }

    /** Hapus seluruh override target milik satu unit (kembali ke global). */
    public function hapusTargetUnit($tahun, $id_upk)
    {
        $id_upk = (int) $id_upk;
        if ($id_upk <= 0 || !$this->db->table_exists('mri_checklist_target')) {
            return false;
        }

        return $this->db->delete('mri_checklist_target', [
            'tahun'  => (int) $tahun,
            'id_upk' => $id_upk,
        ]);
    }

    /** Insert/update satu baris target. Baris kosong dihapus. */
    private function upsertTarget($tahun, $id_upk, $tahap_no, $kode, $mulai, $selesai, $pic, $catatan, $user)
    {
        $where = [
            'tahun'     => (int) $tahun,
            'id_upk'    => (int) $id_upk,
            'tahap_no'  => (int) $tahap_no,
            'kode_item' => (string) $kode,
        ];

        $pic     = trim((string) $pic);
        $catatan = trim((string) $catatan);
        $ada     = $this->db->get_where('mri_checklist_target', $where)->row();

        if ($mulai === null && $selesai === null && $pic === '' && $catatan === '') {
            if ($ada) {
                $this->db->delete('mri_checklist_target', ['id_target' => $ada->id_target]);
            }
            return;
        }

        $data = [
            'target_mulai'   => $mulai,
            'target_selesai' => $selesai,
            'pic'            => $pic !== '' ? $pic : null,
            'catatan'        => $catatan !== '' ? $catatan : null,
        ];

        if ($ada) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = (string) $user;
            $this->db->where('id_target', $ada->id_target)->update('mri_checklist_target', $data);
        } else {
            $data = array_merge($data, $where, [
                'created_by' => (string) $user,
            ]);
            $this->db->insert('mri_checklist_target', $data);
        }
    }

    // =================================================================
    // Centang manual
    // =================================================================

    /** @return array<string, object> keyed kode_item */
    public function centangMap($tahun, $id_upk)
    {
        $id_upk = (int) $id_upk;
        if ($id_upk <= 0 || !$this->db->table_exists('mri_checklist_centang')) {
            return [];
        }

        $rows = $this->db
            ->where('tahun', (int) $tahun)
            ->where('id_upk', $id_upk)
            ->get('mri_checklist_centang')
            ->result();

        $out = [];
        foreach ($rows as $r) {
            $out[$r->kode_item] = $r;
        }
        return $out;
    }

    /**
     * Simpan centang manual. Hanya butir manual (auto_metric NULL) yang boleh.
     * @return bool
     */
    public function simpanCentang($tahun, $id_upk, $kode, $selesai, $catatan, $user)
    {
        $id_upk = (int) $id_upk;
        $kode   = trim((string) $kode);

        if ($id_upk <= 0 || $kode === '' || !$this->db->table_exists('mri_checklist_centang')) {
            return false;
        }

        $item = null;
        foreach ($this->items() as $it) {
            if ($it->kode_item === $kode) {
                $item = $it;
                break;
            }
        }

        // Butir otomatis tidak boleh dicentang manual.
        if (!$item || ($item->auto_metric !== null && $item->auto_metric !== '')) {
            return false;
        }

        $where = [
            'tahun'     => (int) $tahun,
            'id_upk'    => $id_upk,
            'kode_item' => $kode,
        ];

        $data = [
            'selesai'    => $selesai ? 1 : 0,
            'catatan'    => ($catatan !== null && trim((string) $catatan) !== '') ? trim((string) $catatan) : null,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => (string) $user,
        ];

        $ada = $this->db->get_where('mri_checklist_centang', $where)->row();
        if ($ada) {
            $this->db->where('id_centang', $ada->id_centang)->update('mri_checklist_centang', $data);
        } else {
            $this->db->insert('mri_checklist_centang', array_merge($where, $data));
        }

        return true;
    }

    // =================================================================
    // Ringkasan per tahun / unit
    // =================================================================

    /**
     * Ringkasan lengkap checklist satu tahun + unit.
     *
     * @param int      $tahun
     * @param int|null $id_upk null/0 = seluruh unit (target global)
     * @return array
     */
    public function ringkas($tahun, $id_upk = null)
    {
        $tahun  = (int) $tahun;
        $id_upk = $id_upk ? (int) $id_upk : null;

        $itemsByStage = $this->itemsPerTahap();
        $targetTahap  = $this->targetTahap($tahun, $id_upk ?: 0);
        $targetItem   = $this->targetItem($tahun, $id_upk ?: 0);
        $centang      = $this->centangMap($tahun, $id_upk ?: 0);
        $snap         = $this->snapshot($tahun, $id_upk);

        $hasil        = [];
        $sumNum       = 0;
        $sumDen       = 0;
        $itemSelesai  = 0;
        $itemTotal    = 0;
        $tahapSelesai = 0;
        $tahapan      = $this->tahapan();

        foreach ($tahapan as $t) {
            $no         = (int) $t['no'];
            $daftarItem = isset($itemsByStage[$no]) ? $itemsByStage[$no] : [];

            $sNum     = 0;
            $sDen     = 0;
            $doneItem = 0;

            foreach ($daftarItem as $it) {
                $kode = $it->kode_item;

                if ($it->auto_metric !== null && $it->auto_metric !== '') {
                    $pair = isset($snap[$it->auto_metric]) ? $snap[$it->auto_metric] : [0, 1];
                    $num  = (int) $pair[0];
                    $den  = (int) $pair[1];
                    $manual = false;
                } else {
                    $num = (!empty($centang[$kode]) && (int) $centang[$kode]->selesai === 1) ? 1 : 0;
                    $den = 1;
                    $manual = true;
                }

                if ($den <= 0) {
                    $num = 0;
                    $den = 1;
                }
                if ($num > $den) {
                    $num = $den;
                }

                $persen    = $den > 0 ? (int) round(($num / $den) * 100) : 0;
                $persen    = max(0, min(100, $persen));
                $itemSelesaiIni = ($persen >= 100);

                if ($itemSelesaiIni) {
                    $doneItem++;
                }

                $sNum += $num;
                $sDen += $den;

                $it->_num          = $num;
                $it->_den          = $den;
                $it->_persen       = $persen;
                $it->_manual       = $manual;
                $it->_checked      = $itemSelesaiIni;
                $it->_target       = isset($targetItem[$kode]) ? $targetItem[$kode] : null;
                $it->_target_scope = isset($targetItem[$kode]) ? $targetItem[$kode]->_scope : null;
            }

            $persen = $sDen > 0 ? (int) round(($sNum / $sDen) * 100) : 0;
            $persen = max(0, min(100, $persen));
            $status = $sNum <= 0 ? 'Belum' : ($persen >= 100 ? 'Selesai' : 'Sebagian');

            $itemTotal   += count($daftarItem);
            $itemSelesai += $doneItem;
            $sumNum      += $sNum;
            $sumDen      += $sDen;

            if (count($daftarItem) > 0 && $persen >= 100) {
                $tahapSelesai++;
            }

            $hasil[] = [
                'no'           => $no,
                'judul'        => $t['judul'],
                'rute'         => $t['rute'],
                'halaman'      => $t['halaman'],
                'catatan'      => $t['catatan'],
                'items'        => $daftarItem,
                'target'       => isset($targetTahap[$no]) ? $targetTahap[$no] : null,
                'target_scope' => isset($targetTahap[$no]) ? $targetTahap[$no]->_scope : null,
                'num'          => $sNum,
                'den'          => $sDen,
                'persen'       => $persen,
                'status'       => $status,
                'item_selesai' => $doneItem,
                'item_total'   => count($daftarItem),
            ];
        }

        $persenTotal = $sumDen > 0 ? (int) round(($sumNum / $sumDen) * 100) : 0;
        $persenTotal = max(0, min(100, $persenTotal));
        $statusTotal = $sumNum <= 0
            ? 'Belum Mulai'
            : ($persenTotal >= 100 ? 'Selesai' : 'Dalam Proses');

        return [
            'tahun'          => $tahun,
            'id_upk'         => $id_upk,
            'tahap'          => $hasil,
            'item_selesai'   => $itemSelesai,
            'item_total'     => $itemTotal,
            'tahap_selesai'  => $tahapSelesai,
            'tahap_total'    => count($tahapan),
            'num'            => $sumNum,
            'den'            => $sumDen,
            'persen'         => $persenTotal,
            'status'         => $statusTotal,
            'target_terdekat'=> $this->targetTerdekat($hasil),
        ];
    }

    /** Target terdekat (>= hari ini, atau yang terakhir bila semua lewat). */
    private function targetTerdekat($tahap)
    {
        $hariIni  = strtotime(date('Y-m-d'));
        $kandidat = [];

        foreach ($tahap as $t) {
            if (!empty($t['target']) && !empty($t['target']->target_selesai)) {
                $kandidat[] = [
                    'judul'   => $t['judul'],
                    'jenis'   => 'Tahap',
                    'tanggal' => $t['target']->target_selesai,
                ];
            }
            foreach ($t['items'] as $it) {
                if (!empty($it->_target) && !empty($it->_target->target_selesai)) {
                    $kandidat[] = [
                        'judul'   => $it->judul,
                        'jenis'   => 'Butir',
                        'tanggal' => $it->_target->target_selesai,
                    ];
                }
            }
        }

        if (!$kandidat) {
            return null;
        }

        usort($kandidat, function ($a, $b) {
            return strcmp($a['tanggal'], $b['tanggal']);
        });

        $pilih = null;
        foreach ($kandidat as $c) {
            $ts = strtotime($c['tanggal']);
            if ($ts !== false && $ts >= $hariIni) {
                $pilih = $c;
                break;
            }
        }
        if (!$pilih) {
            $pilih = end($kandidat);
        }

        $ts = strtotime($pilih['tanggal']);
        $pilih['hari'] = $ts !== false ? (int) floor(($ts - $hariIni) / 86400) : 0;

        return $pilih;
    }

    // =================================================================
    // Matriks status per unit
    // =================================================================

    /**
     * Matriks 9 tahap (0-8) x unit.
     *
     * @param int      $tahun
     * @param array    $f     q, status, tampil
     * @param int|null $paksa unit yang dipaksa (akun UPK)
     * @return array
     */
    public function matriks($tahun, $f = [], $paksa = null)
    {
        $tahun       = (int) $tahun;
        $q           = isset($f['q']) ? trim((string) $f['q']) : '';
        $statusFil   = isset($f['status']) ? trim((string) $f['status']) : '';
        $tampil      = isset($f['tampil']) ? (string) $f['tampil'] : 'semua';

        $units = $this->daftarUnitAktif($tahun);
        $rows  = [];

        foreach ($units as $u) {
            $id = (int) $u->id_bagian;

            if ($paksa !== null && $id !== (int) $paksa) {
                continue;
            }
            if ($q !== '' && stripos($u->nama_bagian, $q) === false) {
                continue;
            }

            $r      = $this->ringkas($tahun, $id);
            $status = $this->statusKelompok($r['persen']);

            if ($statusFil !== '' && $statusFil !== 'Semua status' && $statusFil !== $status) {
                continue;
            }

            $tahap = [];
            foreach ($r['tahap'] as $t) {
                $tahap[(int) $t['no']] = [
                    'num'          => (int) $t['num'],
                    'den'          => (int) $t['den'],
                    'persen'       => (int) $t['persen'],
                    'item_selesai' => (int) $t['item_selesai'],
                    'item_total'   => (int) $t['item_total'],
                ];
            }

            $rows[] = [
                'id_upk'       => $id,
                'nama'         => $u->nama_bagian,
                'inisial'      => $this->inisial($u->nama_bagian),
                'tahap'        => $tahap,
                'num'          => (int) $r['num'],
                'den'          => (int) $r['den'],
                'persen'       => (int) $r['persen'],
                'status'       => $status,
                'item_selesai' => (int) $r['item_selesai'],
                'item_total'   => (int) $r['item_total'],
                'risiko'       => $this->hitungRegister($tahun, $id),
                'evaluasi'     => $this->hitungEvaluasi($tahun, $id),
                'perlakuan'    => $this->hitungPerlakuan($tahun, $id),
            ];
        }

        // Total per tahap + ringkasan unit.
        $totalTahap = [];
        foreach ($this->tahapan() as $t) {
            $totalTahap[(int) $t['no']] = ['num' => 0, 'den' => 0, 'persen' => 0];
        }

        $sumNum = 0;
        $sumDen = 0;
        $selesai = 0;
        $proses  = 0;
        $belum   = 0;

        foreach ($rows as $r) {
            foreach ($r['tahap'] as $no => $c) {
                if (!isset($totalTahap[$no])) {
                    $totalTahap[$no] = ['num' => 0, 'den' => 0, 'persen' => 0];
                }
                $totalTahap[$no]['num'] += $c['num'];
                $totalTahap[$no]['den'] += $c['den'];
            }

            $sumNum += $r['num'];
            $sumDen += $r['den'];

            if ($r['persen'] >= 90) {
                $selesai++;
            } elseif ($r['persen'] >= 10) {
                $proses++;
            } else {
                $belum++;
            }
        }

        foreach ($totalTahap as $no => $c) {
            $totalTahap[$no]['persen'] = $c['den'] > 0
                ? max(0, min(100, (int) round(($c['num'] / $c['den']) * 100)))
                : 0;
        }

        return [
            'unit'        => $rows,
            'total_tahap' => $totalTahap,
            'num'         => $sumNum,
            'den'         => $sumDen,
            'persen'      => $sumDen > 0 ? max(0, min(100, (int) round(($sumNum / $sumDen) * 100))) : 0,
            'ringkasan'   => [
                'selesai'    => $selesai,
                'proses'     => $proses,
                'belum'      => $belum,
                'rata'       => $sumDen > 0 ? max(0, min(100, (int) round(($sumNum / $sumDen) * 100))) : 0,
                'total_unit' => count($rows),
            ],
            'tahap_tampil' => $this->tahapTampil($tampil),
        ];
    }

    /** Tahap yang ditampilkan sesuai filter kelompok. */
    private function tahapTampil($tampil)
    {
        $semua = $this->tahapan();

        if ($tampil === 'perencanaan') {
            return array_values(array_filter($semua, function ($t) {
                return (int) $t['no'] <= 6;
            }));
        }
        if ($tampil === 'pelaksanaan') {
            return array_values(array_filter($semua, function ($t) {
                return (int) $t['no'] >= 7;
            }));
        }

        return $semua;
    }

    /** Klasifikasi status unit untuk kartu ringkasan & filter. */
    private function statusKelompok($persen)
    {
        if ($persen >= 90) {
            return 'Selesai';
        }
        if ($persen >= 10) {
            return 'Dalam proses';
        }
        return 'Belum mulai';
    }

    /** Unit UPK + unit yang punya data pada tahun tersebut. */
    private function daftarUnitAktif($tahun)
    {
        $ids = [];

        foreach ($this->db->select('DISTINCT id_upk', false)
            ->where('tahun', (int) $tahun)
            ->where('id_upk IS NOT NULL', null, false)
            ->get('mri_register')->result() as $r) {
            $ids[(int) $r->id_upk] = 1;
        }

        foreach ($this->db->select('id_bagian, id_upk', false)
            ->where('tahun', (int) $tahun)
            ->get('mri_objektif')->result() as $r) {
            if ((int) $r->id_bagian > 0) {
                $ids[(int) $r->id_bagian] = 1;
            }
            if ((int) $r->id_upk > 0) {
                $ids[(int) $r->id_upk] = 1;
            }
        }

        foreach (['mri_komunikasi', 'mri_lingkup'] as $tabel) {
            if (!$this->db->table_exists($tabel)) {
                continue;
            }
            foreach ($this->db->select('DISTINCT id_upk', false)
                ->where('tahun', (int) $tahun)
                ->where('id_upk IS NOT NULL', null, false)
                ->get($tabel)->result() as $r) {
                $ids[(int) $r->id_upk] = 1;
            }
        }

        foreach ($this->db->select('DISTINCT id_upk', false)
            ->where('tahun', (int) $tahun)
            ->where('id_upk >', 0)
            ->get('mri_laporan')->result() as $r) {
            $ids[(int) $r->id_upk] = 1;
        }

        $this->db->from('bagian_upk');
        $this->db->group_start();
        $this->db->where('status_upk', 1);
        if ($ids) {
            $this->db->or_where_in('id_bagian', array_keys($ids));
        }
        $this->db->group_end();
        $this->db->order_by('nama_bagian', 'ASC');

        return $this->db->get()->result();
    }

    /** Inisial unit untuk avatar (maks 2 huruf). */
    private function inisial($nama)
    {
        $nama = trim((string) $nama);
        if ($nama === '') {
            return '?';
        }
        $kata = preg_split('/\s+/', $nama);
        if (count($kata) >= 2) {
            return strtoupper(substr($kata[0], 0, 1) . substr($kata[1], 0, 1));
        }
        return strtoupper(substr($nama, 0, 2));
    }

    private function hitungRegister($tahun, $id_upk)
    {
        return (int) $this->db
            ->where('tahun', (int) $tahun)
            ->where('id_upk', (int) $id_upk)
            ->count_all_results('mri_register');
    }

    private function hitungEvaluasi($tahun, $id_upk)
    {
        $this->db->from('mri_evaluasi e')
            ->join('mri_register r', 'r.id_register = e.id_register')
            ->where('r.tahun', (int) $tahun)
            ->where('r.id_upk', (int) $id_upk);
        return (int) $this->db->count_all_results();
    }

    private function hitungPerlakuan($tahun, $id_upk)
    {
        $this->db->from('mri_perlakuan p')
            ->join('mri_register r', 'r.id_register = p.id_register')
            ->where('r.tahun', (int) $tahun)
            ->where('r.id_upk', (int) $id_upk);
        return (int) $this->db->count_all_results();
    }

    // =================================================================
    // Perhitungan otomatis
    // =================================================================

    /**
     * Hitung seluruh metrik otomatis untuk satu tahun + unit.
     * Setiap nilai berupa pasangan [terpenuhi, penyebut].
     *
     * @param int      $tahun
     * @param int|null $id_upk null = seluruh unit
     * @return array<string, array{0:int,1:int}>
     */
    private function snapshot($tahun, $id_upk = null)
    {
        $tahun  = (int) $tahun;
        $id_upk = $id_upk ? (int) $id_upk : null;
        $p      = [];

        // Total risiko dipakai sebagai penyebut beberapa metrik sekaligus
        // penanda apakah unit sudah mulai bekerja.
        $this->db->from('mri_register r')->where('r.tahun', $tahun);
        $this->filterRegisterUnit($id_upk);
        $totalRisiko = (int) $this->db->count_all_results();

        // ---------------- Tahap 0: objektif, appetite, kunci ----------------
        $this->db->select("COUNT(*) AS total,
            SUM(CASE WHEN status_rekonsiliasi = 'SELESAI' THEN 1 ELSE 0 END) AS rekon", false)
            ->from('mri_objektif')
            ->where('tahun', $tahun);
        $this->filterObjektifUnit($id_upk);
        $o = $this->db->get()->row();

        $p['objektif_ada']   = $this->pair(((int) $o->total > 0) ? 1 : 0, 1);
        $p['objektif_rekon'] = $this->pair($o->rekon, $o->total);

        $appetite = (int) $this->db->where('tahun', $tahun)->count_all_results('mri_appetite');
        $p['appetite'] = $this->pair($appetite > 0 ? 1 : 0, 1);

        $terkunci = (int) $this->db->where('status_kunci', 1)->count_all_results('mri_kunci');
        $p['kunci_terbuka'] = $this->pair($terkunci > 0 ? 0 : 1, 1);

        // ---------------- Tahap 1: komunikasi ----------------
        $this->db->select("COUNT(*) AS total,
            SUM(CASE WHEN status = 'SELESAI' THEN 1 ELSE 0 END) AS selesai,
            SUM(CASE WHEN q_sasaran IS NOT NULL AND q_sasaran <> ''
                      AND q_risiko IS NOT NULL AND q_risiko <> ''
                      AND q_kontrol IS NOT NULL AND q_kontrol <> ''
                      AND q_tindakan IS NOT NULL AND q_tindakan <> ''
                     THEN 1 ELSE 0 END) AS empat,
            SUM(CASE WHEN hasil_pemahaman = 1 AND hasil_prioritas = 1
                      AND hasil_validasi = 1 AND hasil_kesepakatan = 1
                      AND hasil_tindak_lanjut = 1 THEN 1 ELSE 0 END) AS lima,
            SUM(CASE WHEN pic_tindak_lanjut IS NOT NULL AND pic_tindak_lanjut <> ''
                      AND target_selesai IS NOT NULL THEN 1 ELSE 0 END) AS tindak", false)
            ->from('mri_komunikasi')
            ->where('tahun', $tahun);
        $this->filterUnitLangsungAtauGlobal($id_upk, 'id_upk', $totalRisiko > 0);
        $k = $this->db->get()->row();

        $p['komunikasi_ada']     = $this->pair(((int) $k->total > 0) ? 1 : 0, 1);
        $p['komunikasi_selesai'] = $this->pair($k->selesai, $k->total);
        $p['komunikasi_4q']      = $this->pair($k->empat, $k->total);
        $p['komunikasi_5hasil']  = $this->pair($k->lima, $k->total);
        $p['komunikasi_tindak']  = $this->pair($k->tindak, $k->total);

        // ---------------- Tahap 2: lingkup ----------------
        $this->db->select("COUNT(*) AS total,
            SUM(CASE WHEN status = 'FINAL' THEN 1 ELSE 0 END) AS final,
            SUM(CASE WHEN konteks_internal IS NOT NULL AND konteks_internal <> ''
                      AND konteks_eksternal IS NOT NULL AND konteks_eksternal <> ''
                     THEN 1 ELSE 0 END) AS konteks,
            SUM(CASE WHEN kriteria_acuan IS NOT NULL AND kriteria_acuan <> ''
                     THEN 1 ELSE 0 END) AS kriteria,
            SUM(CASE WHEN hasil_konsultasi IS NOT NULL AND hasil_konsultasi <> ''
                     THEN 1 ELSE 0 END) AS hasil", false)
            ->from('mri_lingkup')
            ->where('tahun', $tahun);
        $this->filterUnitLangsungAtauGlobal($id_upk, 'id_upk', $totalRisiko > 0);
        $l = $this->db->get()->row();

        $p['lingkup_ada']      = $this->pair(((int) $l->total > 0) ? 1 : 0, 1);
        $p['lingkup_final']    = $this->pair($l->final, $l->total);
        $p['lingkup_konteks']  = $this->pair($l->konteks, $l->total);
        $p['lingkup_kriteria'] = $this->pair($l->kriteria, $l->total);
        $p['lingkup_hasil']    = $this->pair($l->hasil, $l->total);

        // ---------------- Tahap 3: register ----------------
        $this->db->from('mri_register r')->where('r.tahun', $tahun);
        $this->filterRegisterUnit($id_upk);
        $this->db->where("(r.risiko IS NOT NULL AND r.risiko <> '')
            AND (r.penyebab IS NOT NULL AND r.penyebab <> '')
            AND (r.peristiwa_risiko IS NOT NULL AND r.peristiwa_risiko <> '')", null, false);
        $regLengkap = (int) $this->db->count_all_results();

        $this->db->from('mri_register r')->where('r.tahun', $tahun);
        $this->filterRegisterUnit($id_upk);
        $this->db->where("(r.sasaran_kpi IS NOT NULL AND r.sasaran_kpi <> '')", null, false);
        $regKpi = (int) $this->db->count_all_results();

        $this->db->select('COUNT(DISTINCT r.id_register) AS n', false)
            ->from('mri_register r')
            ->join('mri_risiko_objektif ro', 'ro.id_register = r.id_register')
            ->where('r.tahun', $tahun);
        $this->filterRegisterUnit($id_upk);
        $regObj = (int) $this->db->get()->row()->n;

        $this->db->select('COUNT(DISTINCT r.id_register) AS n', false)
            ->from('mri_register r')
            ->join('mri_akibat a', 'a.id_register = r.id_register')
            ->where('r.tahun', $tahun);
        $this->filterRegisterUnit($id_upk);
        $regAkibat = (int) $this->db->get()->row()->n;

        $p['register_ada']      = $this->pair($totalRisiko > 0 ? 1 : 0, 1);
        $p['register_lengkap']  = $this->pair($regLengkap, $totalRisiko);
        $p['register_objektif'] = $this->pair($regObj, $totalRisiko);
        $p['register_kpi']      = $this->pair($regKpi, $totalRisiko);
        $p['register_akibat']   = $this->pair($regAkibat, $totalRisiko);

        // ---------------- Tahap 4: analisa ----------------
        $this->db->select("COUNT(a.id_analisa) AS total,
            SUM(CASE WHEN a.inherent_l IS NOT NULL AND a.inherent_d IS NOT NULL THEN 1 ELSE 0 END) AS inherent,
            SUM(CASE WHEN a.residual_l IS NOT NULL AND a.residual_d IS NOT NULL THEN 1 ELSE 0 END) AS residual,
            SUM(CASE WHEN a.id_risk_owner IS NOT NULL THEN 1 ELSE 0 END) AS owner,
            SUM(CASE WHEN a.nilai_inherent IS NOT NULL AND a.nilai_residual IS NOT NULL
                      AND a.nilai_residual <= a.nilai_inherent THEN 1 ELSE 0 END) AS logika,
            SUM(CASE WHEN a.nilai_inherent IS NOT NULL AND a.nilai_residual IS NOT NULL THEN 1 ELSE 0 END) AS logika_den,
            SUM(CASE WHEN a.ringkasan IS NOT NULL AND a.ringkasan <> '' THEN 1 ELSE 0 END) AS ringkasan", false)
            ->from('mri_analisa a')
            ->join('mri_register r', 'r.id_register = a.id_register')
            ->where('r.tahun', $tahun);
        $this->filterRegisterUnit($id_upk);
        $a = $this->db->get()->row();

        $p['analisa_inherent']  = $this->pair($a->inherent, $totalRisiko);
        $p['analisa_residual']  = $this->pair($a->residual, $totalRisiko);
        $p['analisa_owner']     = $this->pair($a->owner, $totalRisiko);
        $p['analisa_logika']    = $this->pair($a->logika, $a->logika_den);
        $p['analisa_ringkasan'] = $this->pair($a->ringkasan, $totalRisiko);

        // ---------------- Tahap 5: evaluasi ----------------
        $punyaReview = $this->db->field_exists('status_review', 'mri_evaluasi');
        $reviewExpr  = $punyaReview
            ? "SUM(CASE WHEN e.status_review IN ('SESUAI','DITINJAU') THEN 1 ELSE 0 END)"
            : "COUNT(e.id_evaluasi)";

        $this->db->select("COUNT(e.id_evaluasi) AS total,
            SUM(CASE WHEN e.nilai_evaluasi IS NOT NULL AND a.nilai_residual IS NOT NULL
                      AND e.nilai_evaluasi = a.nilai_residual THEN 1 ELSE 0 END) AS nilai,
            SUM(CASE WHEN e.alasan_evaluasi IS NOT NULL AND e.alasan_evaluasi <> ''
                     THEN 1 ELSE 0 END) AS alasan,
            {$reviewExpr} AS review,
            SUM(CASE WHEN e.perlu_eskalasi = 1 THEN 1 ELSE 0 END) AS eskalasi_den,
            SUM(CASE WHEN e.perlu_eskalasi = 1
                      AND e.eskalasi_ke IS NOT NULL AND e.eskalasi_ke <> ''
                     THEN 1 ELSE 0 END) AS eskalasi", false)
            ->from('mri_evaluasi e')
            ->join('mri_register r', 'r.id_register = e.id_register')
            ->join('mri_analisa a', 'a.id_register = e.id_register', 'left')
            ->where('r.tahun', $tahun);
        $this->filterRegisterUnit($id_upk);
        $ev = $this->db->get()->row();

        $p['evaluasi_cakupan'] = $this->pair($ev->total, $totalRisiko);
        $p['evaluasi_nilai']   = $this->pair($ev->nilai, $ev->total);
        $p['evaluasi_alasan']  = $this->pair($ev->alasan, $ev->total);
        $p['evaluasi_review']  = $this->pair($ev->review, $ev->total);
        $p['evaluasi_eskalasi']= ($ev->total > 0 && (int) $ev->eskalasi_den === 0)
            ? [1, 1]
            : $this->pair($ev->eskalasi, $ev->eskalasi_den);

        // ---------------- Tahap 6: perlakuan ----------------
        $this->db->select("COUNT(p.id_perlakuan) AS total,
            SUM(CASE WHEN p.alasan_strategi IS NOT NULL AND p.alasan_strategi <> ''
                     THEN 1 ELSE 0 END) AS alasan,
            SUM(CASE WHEN p.uraian_tindakan IS NOT NULL AND p.uraian_tindakan <> ''
                      AND p.indikator_sukses IS NOT NULL AND p.indikator_sukses <> ''
                     THEN 1 ELSE 0 END) AS tindakan,
            SUM(CASE WHEN p.id_pic IS NOT NULL
                      AND p.jadwal_mulai IS NOT NULL
                      AND p.jadwal_selesai IS NOT NULL THEN 1 ELSE 0 END) AS pic,
            SUM(CASE WHEN p.status_laksana <> 'BELUM' THEN 1 ELSE 0 END) AS status", false)
            ->from('mri_perlakuan p')
            ->join('mri_register r', 'r.id_register = p.id_register')
            ->where('r.tahun', $tahun);
        $this->filterRegisterUnit($id_upk);
        $pr = $this->db->get()->row();

        // Risiko Treat/Escalate: berapa yang sudah punya rencana perlakuan.
        $this->db->from('mri_evaluasi e')
            ->join('mri_register r', 'r.id_register = e.id_register')
            ->where('r.tahun', $tahun)
            ->where_in('e.keputusan', ['Treat', 'Escalate']);
        $this->filterRegisterUnit($id_upk);
        $treatDen = (int) $this->db->count_all_results();

        $this->db->select('COUNT(DISTINCT e.id_register) AS n', false)
            ->from('mri_evaluasi e')
            ->join('mri_register r', 'r.id_register = e.id_register')
            ->where('r.tahun', $tahun)
            ->where_in('e.keputusan', ['Treat', 'Escalate'])
            ->where('EXISTS (SELECT 1 FROM mri_perlakuan p WHERE p.id_register = e.id_register)', null, false);
        $this->filterRegisterUnit($id_upk);
        $treatNum = (int) $this->db->get()->row()->n;

        $p['perlakuan_treat']    = ($ev->total > 0 && $treatDen === 0)
            ? [1, 1]
            : $this->pair($treatNum, $treatDen);
        $p['perlakuan_strategi'] = $this->pair($pr->alasan, $pr->total);
        $p['perlakuan_tindakan'] = $this->pair($pr->tindakan, $pr->total);
        $p['perlakuan_pic']      = $this->pair($pr->pic, $pr->total);
        $p['perlakuan_status']   = $this->pair($pr->status, $pr->total);

        // ---------------- Tahap 7: monitoring ----------------
        $this->db->select("COUNT(m.id_monitoring) AS total,
            COUNT(DISTINCT m.id_register) AS cakupan,
            SUM(CASE WHEN m.nilai_baru IS NOT NULL THEN 1 ELSE 0 END) AS skor,
            SUM(CASE WHEN m.hasil_monitoring IS NOT NULL AND m.hasil_monitoring <> ''
                     THEN 1 ELSE 0 END) AS hasil,
            SUM(CASE WHEN m.perubahan IS NULL OR m.perubahan = 'Tetap'
                      OR (m.sebab_perubahan IS NOT NULL AND m.sebab_perubahan <> '')
                     THEN 1 ELSE 0 END) AS sebab,
            SUM(CASE WHEN m.perlu_reassess = 1 THEN 1 ELSE 0 END) AS reassess_den,
            SUM(CASE WHEN m.perlu_reassess = 1 AND m.tanggal_review IS NOT NULL
                     THEN 1 ELSE 0 END) AS reassess", false)
            ->from('mri_monitoring m')
            ->join('mri_register r', 'r.id_register = m.id_register')
            ->where('r.tahun', $tahun);
        $this->filterRegisterUnit($id_upk);
        $mo = $this->db->get()->row();

        $p['monitoring_cakupan'] = $this->pair($mo->cakupan, $totalRisiko);
        $p['monitoring_skor']    = $this->pair($mo->skor, $mo->total);
        $p['monitoring_hasil']   = $this->pair($mo->hasil, $mo->total);
        $p['monitoring_sebab']   = $this->pair($mo->sebab, $mo->total);
        $p['monitoring_reassess']= ($mo->total > 0 && (int) $mo->reassess_den === 0)
            ? [1, 1]
            : $this->pair($mo->reassess, $mo->reassess_den);

        // ---------------- Tahap 8: laporan ----------------
        $this->db->select('jenis_laporan, COUNT(*) AS n', false)
            ->from('mri_laporan')
            ->where('tahun', $tahun);
        $this->filterUnitLangsungAtauGlobal($id_upk, 'id_upk', $totalRisiko > 0);
        $lapRows = $this->db->group_by('jenis_laporan')->get()->result();

        $lap = ['Operasional' => 0, 'Manajemen' => 0, 'Dewan Pengawas' => 0, 'Tahunan' => 0];
        $totalLap = 0;
        foreach ($lapRows as $r) {
            $lap[$r->jenis_laporan] = (int) $r->n;
            $totalLap += (int) $r->n;
        }

        $p['laporan_operasional'] = $this->pair($lap['Operasional'] > 0 ? 1 : 0, 1);
        $p['laporan_manajemen']   = $this->pair($lap['Manajemen'] > 0 ? 1 : 0, 1);
        $p['laporan_pengawas']    = $this->pair($lap['Dewan Pengawas'] > 0 ? 1 : 0, 1);
        $p['laporan_tahunan']     = $this->pair($lap['Tahunan'] > 0 ? 1 : 0, 1);

        $this->db->select("SUM(CASE WHEN status = 'FINAL'
            AND disahkan_oleh IS NOT NULL AND disahkan_oleh <> ''
            AND tanggal_sahkan IS NOT NULL THEN 1 ELSE 0 END) AS sah", false)
            ->from('mri_laporan')
            ->where('tahun', $tahun);
        $this->filterUnitLangsungAtauGlobal($id_upk, 'id_upk', $totalRisiko > 0);
        $sah = (int) $this->db->get()->row()->sah;
        $p['laporan_sah'] = $this->pair($sah, $totalLap);

        return $p;
    }

    /** Pasangan [terpenuhi, penyebut] dengan penjagaan nilai kosong. */
    private function pair($num, $den, $kosongSah = false)
    {
        $num = (int) $num;
        $den = (int) $den;

        if ($den <= 0) {
            return $kosongSah ? [1, 1] : [0, 1];
        }
        if ($num < 0) {
            $num = 0;
        }
        if ($num > $den) {
            $num = $den;
        }

        return [$num, $den];
    }

    // =================================================================
    // Helper filter
    // =================================================================

    private function scopeIds($id_upk)
    {
        $id_upk = (int) $id_upk;
        return $id_upk > 0 ? [0, $id_upk] : [0];
    }

    /** Filter register/analisa/evaluasi/perlakuan/monitoring per unit. */
    private function filterRegisterUnit($id_upk, $alias = 'r')
    {
        if ($id_upk) {
            $this->db->where($alias . '.id_upk', (int) $id_upk);
        }
    }

    /**
     * Filter tabel yang memiliki kolom id_upk secara langsung.
     * Untuk tampilan per unit, hanya data milik unit itu yang dihitung;
     * data organisasi (id_upk 0 / NULL) tetap terlihat pada tampilan global.
     */
    /**
     * Filter langsung per unit, tetapi sertakan data organisasi (id_upk 0 /
     * NULL) bila unit sudah mulai bekerja. Unit yang belum punya risiko tetap
     * dihitung belum mulai, bukan ikut mendapat kredit dokumen organisasi.
     */
    private function filterUnitLangsungAtauGlobal($id_upk, $kolom, $pakaiGlobal)
    {
        if (!$id_upk) {
            return;
        }

        if ($pakaiGlobal) {
            $this->db->group_start()
                ->where($kolom, (int) $id_upk)
                ->or_where($kolom, 0)
                ->or_where($kolom . ' IS NULL', null, false)
                ->group_end();
            return;
        }

        $this->db->where($kolom, (int) $id_upk);
    }

    /** Filter objektif: unit bisa tersimpan di id_bagian atau id_upk. */
    private function filterObjektifUnit($id_upk)
    {
        if ($id_upk) {
            $this->db->group_start()
                ->where('id_bagian', (int) $id_upk)
                ->or_where('id_upk', (int) $id_upk)
                ->group_end();
        }
    }

    /** Validasi tanggal Y-m-d; selain itu null. */
    private function tanggal($nilai)
    {
        $nilai = trim((string) $nilai);
        if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $nilai, $m)) {
            return null;
        }
        if (!checkdate((int) $m[2], (int) $m[3], (int) $m[1])) {
            return null;
        }
        return $nilai;
    }
}
