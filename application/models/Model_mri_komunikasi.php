<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Komunikasi & Konsultasi (SNI ISO 31000:2018 Klausa 6.2) — mri_komunikasi.
 *
 * Satu baris = satu sesi komunikasi/konsultasi. Sesi mencatat tiga langkah
 * materi QRMO (dengarkan, selaraskan, uji & konfirmasi), empat pertanyaan
 * kunci, lima hasil yang diharapkan, dan tindak lanjutnya.
 */
class Model_mri_komunikasi extends CI_Model
{
    /** Metode komunikasi — persis dengan enum mri_komunikasi.metode. */
    const METODE = ['Rapat', 'FGD', 'Wawancara', 'Sosialisasi', 'Konsultasi Tertulis', 'Lainnya'];

    /** Status dokumen sesi — persis dengan enum mri_komunikasi.status. */
    const STATUS = ['DRAFT', 'SELESAI'];

    /** Status tindak lanjut — persis dengan enum mri_komunikasi.status_tindak_lanjut. */
    const STATUS_TINDAK_LANJUT = ['BELUM', 'PROSES', 'SELESAI'];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function user()
    {
        $u = $this->session->userdata('nama_pengguna');
        return $u ?: 'sistem';
    }

    // ---------------- QUERY ----------------

    public function getById($id)
    {
        if (!$this->db->table_exists('mri_komunikasi')) {
            return null;
        }
        $this->db->select('k.*, b.nama_bagian, r.kode_risiko, r.risiko, l.nama_lingkup');
        $this->db->from('mri_komunikasi k');
        $this->db->join('bagian_upk b', 'b.id_bagian = k.id_upk', 'left');
        $this->db->join('mri_register r', 'r.id_register = k.id_register', 'left');
        $this->db->join('mri_lingkup l', 'l.id_lingkup = k.id_lingkup', 'left');
        $this->db->where('k.id_komunikasi', $id);
        return $this->db->get()->row();
    }

    /**
     * Daftar sesi komunikasi dengan filter.
     * @param array $f keys: tahun, triwulan, id_upk, status, status_tindak_lanjut, q
     */
    public function getList($f = [])
    {
        if (!$this->db->table_exists('mri_komunikasi')) {
            return [];
        }
        $select = 'k.*, b.nama_bagian, r.kode_risiko, r.risiko, l.nama_lingkup';
        if ($this->db->table_exists('mri_komunikasi_foto')) {
            $select .= ', (SELECT COUNT(*) FROM mri_komunikasi_foto f
                            WHERE f.id_komunikasi = k.id_komunikasi) AS jumlah_foto'
                . ', (SELECT f.nama_file FROM mri_komunikasi_foto f
                            WHERE f.id_komunikasi = k.id_komunikasi
                            ORDER BY f.id_foto ASC LIMIT 1) AS foto_utama';
        } else {
            $select .= ', 0 AS jumlah_foto, NULL AS foto_utama';
        }
        $this->db->select($select, false);
        $this->db->from('mri_komunikasi k');
        $this->db->join('bagian_upk b', 'b.id_bagian = k.id_upk', 'left');
        $this->db->join('mri_register r', 'r.id_register = k.id_register', 'left');
        $this->db->join('mri_lingkup l', 'l.id_lingkup = k.id_lingkup', 'left');

        if (!empty($f['tahun'])) {
            $this->db->where('k.tahun', (int) $f['tahun']);
        }
        if (!empty($f['triwulan'])) {
            $this->db->where('k.triwulan', (int) $f['triwulan']);
        }
        if (!empty($f['id_upk'])) {
            $this->db->where('k.id_upk', (int) $f['id_upk']);
        }
        if (!empty($f['status'])) {
            $this->db->where('k.status', $f['status']);
        }
        if (!empty($f['status_tindak_lanjut'])) {
            $this->db->where('k.status_tindak_lanjut', $f['status_tindak_lanjut']);
        }
        if (!empty($f['q'])) {
            $this->db->group_start();
            $this->db->like('k.topik', $f['q']);
            $this->db->or_like('k.pihak_terlibat', $f['q']);
            $this->db->or_like('k.ringkasan_hasil', $f['q']);
            $this->db->or_like('k.tindak_lanjut', $f['q']);
            $this->db->or_like('k.pic_tindak_lanjut', $f['q']);
            $this->db->or_like('r.kode_risiko', $f['q']);
            $this->db->group_end();
        }

        $this->db->order_by('k.tanggal', 'DESC');
        $this->db->order_by('k.id_komunikasi', 'DESC');
        return $this->db->get()->result();
    }

    // ---------------- FOTO PENDUKUNG ----------------

    public function getFoto($id_komunikasi)
    {
        if (!$this->db->table_exists('mri_komunikasi_foto')) {
            return [];
        }
        return $this->db->from('mri_komunikasi_foto')
            ->where('id_komunikasi', (int) $id_komunikasi)
            ->order_by('id_foto', 'ASC')
            ->get()->result();
    }

    public function getFotoById($id_foto, $id_komunikasi)
    {
        if (!$this->db->table_exists('mri_komunikasi_foto')) {
            return null;
        }
        return $this->db->from('mri_komunikasi_foto')
            ->where('id_foto', (int) $id_foto)
            ->where('id_komunikasi', (int) $id_komunikasi)
            ->get()->row();
    }

    public function tambahFoto($id_komunikasi, $nama_file)
    {
        if ($nama_file === '') {
            return false;
        }
        if (!$this->db->table_exists('mri_komunikasi_foto')) {
            $this->pastikanTabelFoto();
        }
        if (!$this->db->table_exists('mri_komunikasi_foto')) {
            return false;
        }
        return $this->db->insert('mri_komunikasi_foto', [
            'id_komunikasi' => (int) $id_komunikasi,
            'nama_file'     => $nama_file,
            'created_at'    => date('Y-m-d H:i:s'),
            'created_by'    => $this->user(),
        ]);
    }

    /**
     * Buat tabel foto bila belum ada (mis. migrasi 12 belum dijalankan di server).
     * Aman dipanggil berulang karena memakai IF NOT EXISTS.
     */
    private function pastikanTabelFoto()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `mri_komunikasi_foto` (
            `id_foto` int(11) NOT NULL AUTO_INCREMENT,
            `id_komunikasi` int(11) NOT NULL,
            `nama_file` varchar(255) NOT NULL,
            `created_at` datetime NOT NULL DEFAULT current_timestamp(),
            `created_by` varchar(100) DEFAULT NULL,
            PRIMARY KEY (`id_foto`),
            KEY `idx_komfoto_komunikasi` (`id_komunikasi`),
            CONSTRAINT `fk_komfoto_komunikasi` FOREIGN KEY (`id_komunikasi`)
                REFERENCES `mri_komunikasi` (`id_komunikasi`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC");
    }

    public function hapusFoto($id_foto, $id_komunikasi)
    {
        if (!$this->db->table_exists('mri_komunikasi_foto')) {
            return false;
        }
        $this->db->where('id_foto', (int) $id_foto);
        $this->db->where('id_komunikasi', (int) $id_komunikasi);
        return $this->db->delete('mri_komunikasi_foto');
    }

    /**
     * Ringkasan untuk kepala halaman dan dashboard.
     * @return array{total:int,selesai:int,draft:int,tindak_lanjut:int}
     */
    public function rekap($tahun, $id_upk = null)
    {
        if (!$this->db->table_exists('mri_komunikasi')) {
            return ['total' => 0, 'selesai' => 0, 'draft' => 0, 'tindak_lanjut' => 0];
        }
        $this->db->select(
            "COUNT(*) AS total,
             SUM(CASE WHEN status = 'SELESAI' THEN 1 ELSE 0 END) AS selesai,
             SUM(CASE WHEN status = 'DRAFT' THEN 1 ELSE 0 END) AS draft,
             SUM(CASE WHEN status_tindak_lanjut <> 'SELESAI'
                       AND COALESCE(tindak_lanjut, '') <> '' THEN 1 ELSE 0 END) AS tindak_lanjut",
            false
        );
        $this->db->from('mri_komunikasi');
        $this->db->where('tahun', (int) $tahun);
        if ($id_upk) {
            $this->db->where('id_upk', (int) $id_upk);
        }
        $row = $this->db->get()->row();

        return [
            'total'         => $row ? (int) $row->total : 0,
            'selesai'       => $row ? (int) $row->selesai : 0,
            'draft'         => $row ? (int) $row->draft : 0,
            'tindak_lanjut' => $row ? (int) $row->tindak_lanjut : 0,
        ];
    }

    /** Tahun yang punya catatan komunikasi (dropdown filter). */
    public function daftarTahun()
    {
        if (!$this->db->table_exists('mri_komunikasi')) {
            return [];
        }
        $out = [];
        foreach ($this->db->select('tahun')->distinct()->order_by('tahun', 'DESC')->get('mri_komunikasi')->result() as $t) {
            $out[] = (int) $t->tahun;
        }
        return $out;
    }

    // ---------------- SIMPAN / HAPUS ----------------

    public function simpan($d, $id = null)
    {
        $row = [
            'tahun'               => isset($d['tahun']) ? (int) $d['tahun'] : (int) date('Y'),
            'triwulan'            => isset($d['triwulan']) ? max(1, min(4, (int) $d['triwulan'])) : 1,
            'id_upk'              => !empty($d['id_upk']) ? (int) $d['id_upk'] : null,
            'id_lingkup'          => !empty($d['id_lingkup']) ? (int) $d['id_lingkup'] : null,
            'id_register'         => !empty($d['id_register']) ? (int) $d['id_register'] : null,
            'tanggal'             => !empty($d['tanggal']) ? $d['tanggal'] : date('Y-m-d'),
            'metode'              => isset($d['metode']) && in_array($d['metode'], self::METODE, true)
                ? $d['metode'] : 'Rapat',
            'topik'               => isset($d['topik']) ? $d['topik'] : '',
            'pihak_terlibat'      => isset($d['pihak_terlibat']) ? $d['pihak_terlibat'] : null,
            'pic_komunikasi'      => isset($d['pic_komunikasi']) ? $d['pic_komunikasi'] : null,
            'catatan_dengar'      => isset($d['catatan_dengar']) ? $d['catatan_dengar'] : null,
            'catatan_selaras'     => isset($d['catatan_selaras']) ? $d['catatan_selaras'] : null,
            'catatan_konfirmasi'  => isset($d['catatan_konfirmasi']) ? $d['catatan_konfirmasi'] : null,
            'q_sasaran'           => isset($d['q_sasaran']) ? $d['q_sasaran'] : null,
            'q_risiko'            => isset($d['q_risiko']) ? $d['q_risiko'] : null,
            'q_kontrol'           => isset($d['q_kontrol']) ? $d['q_kontrol'] : null,
            'q_tindakan'          => isset($d['q_tindakan']) ? $d['q_tindakan'] : null,
            'hasil_pemahaman'     => !empty($d['hasil_pemahaman']) ? 1 : 0,
            'hasil_prioritas'     => !empty($d['hasil_prioritas']) ? 1 : 0,
            'hasil_validasi'      => !empty($d['hasil_validasi']) ? 1 : 0,
            'hasil_kesepakatan'   => !empty($d['hasil_kesepakatan']) ? 1 : 0,
            'hasil_tindak_lanjut' => !empty($d['hasil_tindak_lanjut']) ? 1 : 0,
            'ringkasan_hasil'     => isset($d['ringkasan_hasil']) ? $d['ringkasan_hasil'] : null,
            'tindak_lanjut'       => isset($d['tindak_lanjut']) ? $d['tindak_lanjut'] : null,
            'pic_tindak_lanjut'   => isset($d['pic_tindak_lanjut']) ? $d['pic_tindak_lanjut'] : null,
            'target_selesai'      => !empty($d['target_selesai']) ? $d['target_selesai'] : null,
            'status_tindak_lanjut' => isset($d['status_tindak_lanjut']) && in_array($d['status_tindak_lanjut'], self::STATUS_TINDAK_LANJUT, true)
                ? $d['status_tindak_lanjut'] : 'BELUM',
            'status'              => isset($d['status']) && in_array($d['status'], self::STATUS, true)
                ? $d['status'] : 'DRAFT',
            'modified_at'         => date('Y-m-d H:i:s'),
            'modified_by'         => $this->user(),
        ];

        if ($id) {
            $this->db->where('id_komunikasi', $id);
            return $this->db->update('mri_komunikasi', $row);
        }

        $row['created_at'] = date('Y-m-d H:i:s');
        $row['created_by'] = $this->user();
        return $this->db->insert('mri_komunikasi', $row);
    }

    public function hapus($id)
    {
        // Hapus baris foto lebih dulu agar tetap aman bila foreign key belum ada.
        if ($this->db->table_exists('mri_komunikasi_foto')) {
            $this->db->where('id_komunikasi', $id);
            $this->db->delete('mri_komunikasi_foto');
        }
        $this->db->where('id_komunikasi', $id);
        return $this->db->delete('mri_komunikasi');
    }
}
