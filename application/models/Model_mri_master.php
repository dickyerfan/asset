<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model master modul manrisk (SNI ISO 31000:2018).
 * Mengelola mri_proses, mri_kategori_risiko, mri_tingkat_risiko.
 * Modul baru — tidak pernah menyentuh tabel legacy mr_*.
 */
class Model_mri_master extends CI_Model
{
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

    // ---------------- mri_proses (Risk Universe A-K) ----------------

    public function getProses($aktif_saja = true)
    {
        $this->db->from('mri_proses');
        if ($aktif_saja) {
            $this->db->where('status', 1);
        }
        $this->db->order_by('urut', 'ASC');
        return $this->db->get()->result();
    }

    public function getProsesById($id)
    {
        return $this->db->get_where('mri_proses', ['id_proses' => $id])->row();
    }

    public function getProsesByKode($kode)
    {
        return $this->db->get_where('mri_proses', ['kode_proses' => $kode])->row();
    }

    public function simpanProses($id, $data)
    {
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = $this->user();
        if ($id) {
            $this->db->where('id_proses', (int) $id);
            return $this->db->update('mri_proses', $data);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->user();
        return $this->db->insert('mri_proses', $data);
    }

    /** kode_proses UNIQUE — cek sebelum simpan. */
    public function kodeProsesDipakai($kode, $kecuali_id = null)
    {
        $this->db->where('kode_proses', $kode);
        if ($kecuali_id) {
            $this->db->where('id_proses !=', (int) $kecuali_id);
        }
        return $this->db->count_all_results('mri_proses') > 0;
    }

    /** Berapa kali proses ini dipakai objektif / pemetaan risiko proses. */
    public function jumlahPemakaianProses($id_proses)
    {
        return [
            'objektif' => (int) $this->db->where('id_proses', (int) $id_proses)->count_all_results('mri_objektif'),
            'risiko'   => (int) $this->db->where('id_proses', (int) $id_proses)->count_all_results('mri_risiko_proses'),
        ];
    }

    public function hapusProses($id)
    {
        $this->db->where('id_proses', (int) $id);
        return $this->db->delete('mri_proses');
    }

    // ---------------- mri_kategori_risiko (14 kategori QRMO) ----------------

    public function getKategori($aktif_saja = true)
    {
        $this->db->from('mri_kategori_risiko');
        if ($aktif_saja) {
            $this->db->where('status', 1);
        }
        $this->db->order_by('urut', 'ASC');
        return $this->db->get()->result();
    }

    public function getKategoriById($id)
    {
        return $this->db->get_where('mri_kategori_risiko', ['id_kategori' => $id])->row();
    }

    public function simpanKategori($data, $id = null)
    {
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = $this->user();
        if ($id) {
            $this->db->where('id_kategori', (int) $id);
            return $this->db->update('mri_kategori_risiko', $data);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->user();
        return $this->db->insert('mri_kategori_risiko', $data);
    }

    /** kode_kat UNIQUE — cek sebelum simpan. */
    public function kodeKategoriDipakai($kode, $kecuali_id = null)
    {
        $this->db->where('kode_kat', $kode);
        if ($kecuali_id) {
            $this->db->where('id_kategori !=', (int) $kecuali_id);
        }
        return $this->db->count_all_results('mri_kategori_risiko') > 0;
    }

    /** Berapa kali kategori ini dipakai register / risk appetite. */
    public function jumlahPemakaianKategori($id_kategori)
    {
        return [
            'register' => (int) $this->db->where('id_kategori', (int) $id_kategori)->count_all_results('mri_register'),
            'appetite' => (int) $this->db->where('id_kategori', (int) $id_kategori)->count_all_results('mri_appetite'),
        ];
    }

    public function hapusKategori($id)
    {
        $this->db->where('id_kategori', (int) $id);
        return $this->db->delete('mri_kategori_risiko');
    }

    // ---------------- mri_tingkat_risiko (skala level, dapat diubah) ----------------

    public function getTingkat($aktif_saja = true)
    {
        $this->db->from('mri_tingkat_risiko');
        if ($aktif_saja) {
            $this->db->where('status', 1);
        }
        $this->db->order_by('urut_tampil', 'ASC');
        return $this->db->get()->result();
    }

    public function getTingkatById($id)
    {
        return $this->db->get_where('mri_tingkat_risiko', ['id_tingkat' => $id])->row();
    }

    /**
     * Cari level dari sebuah nilai skor (L x D).
     * Sumber kebenaran satu-satunya: rentang skor_min..skor_max di mri_tingkat_risiko.
     */
    public function levelDariSkor($skor)
    {
        $skor = (int) $skor;
        $row = $this->db->where('skor_min <=', $skor)
            ->where('skor_max >=', $skor)
            ->where('status', 1)
            ->order_by('urut_tampil', 'ASC')
            ->get('mri_tingkat_risiko')
            ->row();
        return $row;
    }

    public function simpanTingkat($id, $data)
    {
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = $this->user();
        if ($id) {
            $this->db->where('id_tingkat', (int) $id);
            return $this->db->update('mri_tingkat_risiko', $data);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->user();
        return $this->db->insert('mri_tingkat_risiko', $data);
    }

    /**
     * Apakah rentang skor baru tumpang tindih dengan level lain?
     * Skor L x D harus jatuh di tepat satu level; tumpang tindih membuat
     * levelDariSkor() tidak deterministik.
     */
    public function rentangSkorBentrok($skor_min, $skor_max, $kecuali_id = null)
    {
        $this->db->where('skor_min <=', (int) $skor_max);
        $this->db->where('skor_max >=', (int) $skor_min);
        if ($kecuali_id) {
            $this->db->where('id_tingkat !=', (int) $kecuali_id);
        }
        return $this->db->count_all_results('mri_tingkat_risiko') > 0;
    }

    /** Berapa analisa/riwayat skor yang masih memakai nama level ini. */
    public function jumlahPemakaianTingkat($nama_tingkat)
    {
        return (int) $this->db
            ->where('level_inherent', $nama_tingkat)
            ->or_where('level_residual', $nama_tingkat)
            ->count_all_results('mri_analisa')
            + (int) $this->db
                ->where('level_inherent', $nama_tingkat)
                ->or_where('level_residual', $nama_tingkat)
                ->count_all_results('mri_riwayat_skor');
    }

    public function hapusTingkat($id)
    {
        $this->db->where('id_tingkat', (int) $id);
        return $this->db->delete('mri_tingkat_risiko');
    }

    /**
     * Ambil data warna level untuk dipakai view (chart, badge, matriks).
     * Mengembalikan array [nama_tingkat => warna] plus rentang skornya.
     */
    public function petaLevel()
    {
        $peta = [];
        foreach ($this->getTingkat() as $t) {
            $peta[$t->nama_tingkat] = $t;
        }
        return $peta;
    }

    /**
     * Alur proses manajemen risiko — 8 tahap.
     *
     * SUMBER KEBENARAN: D:\MR 2026\Ringkasan_MR_Perumdam_Ijen_Tirta.md
     * §5.1 "Alur Proses (8 Tahap)". Urutan dan judul diambil persis
     * dari materi; modul aplikasi ditambahkan agar tiap tahap tahu
     * harus dikerjakan di halaman mana.
     *
     * Catatan pemetaan:
     *  - Tahap 1 (Komunikasi & Konsultasi) berjalan menyilang di seluruh
     *    tahap dan dicatat pada halaman Komunikasi (mri_komunikasi). Kolom
     *    konsultasi pada halaman Lingkup tetap dipertahankan sebagai
     *    ringkasan dokumen, bukan pengganti catatan sesi.
     *  - KPI/KRI bukan tahap tersendiri dan tidak dipakai pada alur ini;
     *    tahap 7 (Pemantauan & Peninjauan) langsung diikuti tahap 8 (Pelaporan).
     *
     * @return array<int, array> setiap entri: no, judul, rute, halaman, catatan
     */
    public function tahapan()
    {
        return [
            [
                'no'      => 1,
                'judul'   => 'Komunikasi',
                'rute'    => 'manrisk/komunikasi',
                'halaman' => 'Komunikasi & Konsultasi',
                'catatan' => 'Sesi komunikasi: dengarkan, selaraskan, uji & konfirmasi; 4 pertanyaan kunci; hasil & tindak lanjut.',
            ],
            [
                'no'      => 2,
                'judul'   => 'Lingkup & Konteks',
                'rute'    => 'manrisk/lingkup',
                'halaman' => 'Lingkup',
                'catatan' => 'Konteks internal/eksternal, kriteria_acuan, objek & batas evaluasi.',
            ],
            [
                'no'      => 3,
                'judul'   => 'Identifikasi Risiko',
                'rute'    => 'manrisk/register',
                'halaman' => 'Risk Register',
                'catatan' => 'Peristiwa, penyebab, akibat, sasaran terpengaruh, tipe Ancaman/Peluang.',
            ],
            [
                'no'      => 4,
                'judul'   => 'Analisis Risiko',
                'rute'    => 'manrisk/analisa',
                'halaman' => 'Analisis',
                'catatan' => 'Skor L x C (1-5) untuk inherent dan residual.',
            ],
            [
                'no'      => 5,
                'judul'   => 'Evaluasi Risiko',
                'rute'    => 'manrisk/evaluasi',
                'halaman' => 'Evaluasi',
                'catatan' => 'Membandingkan hasil analisis dengan kriteria & risk appetite, lalu memutuskan.',
            ],
            [
                'no'      => 6,
                'judul'   => 'Perlakuan / Treatment',
                'rute'    => 'manrisk/perlakuan',
                'halaman' => 'Perlakuan',
                'catatan' => 'AVOID, REDUCE, SHARE, ACCEPT — PIC, jadwal, dan anggaran.',
            ],
            [
                'no'      => 7,
                'judul'   => 'Peninjauan',
                'rute'    => 'manrisk/monitoring',
                'halaman' => 'Monitoring',
                'catatan' => 'Catatan per triwulan, efektivitas kontrol, kebutuhan reassess.',
            ],
            [
                'no'      => 8,
                'judul'   => 'Pelaporan',
                'rute'    => 'manrisk/laporan',
                'halaman' => 'Laporan',
                'catatan' => 'Laporan operasional, manajemen, Dewan Pengawas, dan tahunan.',
            ],
        ];
    }
}
