<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Key Risk Indicator (mri_kri) & laporan risiko (mri_laporan).
 *
 * KRI bersumber dari Ringkasan MR §10.2 (6 indikator).
 * Laporan mengikuti jenis di Ringkasan MR §10.1:
 * Operasional, Manajemen, Dewan Pengawas, Tahunan.
 */
class Model_mri_kri extends CI_Model
{
    /** Jenis laporan — persis enum mri_laporan.jenis_laporan. */
    const JENIS_LAPORAN = ['Operasional', 'Manajemen', 'Dewan Pengawas', 'Tahunan'];

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

    // ---------------- mri_kri ----------------

    public function getKri($tahun, $id_upk = null, $id_register = null)
    {
        $this->db->select('k.*, r.kode_risiko, r.risiko, r.sasaran_kpi, b.nama_bagian');
        $this->db->from('mri_kri k');
        $this->db->join('mri_register r', 'r.id_register = k.id_register', 'left');
        $this->db->join('bagian_upk b', 'b.id_bagian = k.id_upk', 'left');
        $this->db->where('k.tahun', $tahun);
        if ($id_upk) {
            $this->db->where('k.id_upk', $id_upk);
        }
        if ($id_register) {
            $this->db->where('k.id_register', $id_register);
        }
        $this->db->order_by('k.id_kri', 'ASC');
        return $this->db->get()->result();
    }

    public function getKriById($id)
    {
        return $this->db->get_where('mri_kri', ['id_kri' => $id])->row();
    }

    /**
     * Status KRI terhadap target. Mengembalikan:
     *  - 'Belum Diukur' bila nilai_aktual NULL
     *  - 'Tercapai' / 'Tidak Tercapai' bila membandingkan dengan target
     * Pembandung target disederhanakan: target = batas atas, kecuali KRI
     * yang dinyatakan sebagai penurunan (satuan 'persen penurunan') yang
     * dihitung sebaliknya.
     */
    public function hitungStatus($kri)
    {
        if ($kri->nilai_aktual === null || $kri->nilai_aktual === '') {
            return 'Belum Diukur';
        }
        if ($kri->target === null || $kri->target === '') {
            return 'Perlu Perhatian'; // target belum ditetapkan, tidak boleh menghakimi
        }
        $aktual = (float) $kri->nilai_aktual;
        $target = (float) $kri->target;
        $turun  = (strpos((string) $kri->satuan, 'penurunan') !== false);

        if ($turun) {
            return $aktual >= $target ? 'Tercapai' : 'Tidak Tercapai';
        }
        return $aktual <= $target ? 'Tercapai' : 'Tidak Tercapai';
    }

    /**
     * Simpan nilai aktual KRI + perbarui statusnya.
     */
    public function catatNilai($id_kri, $nilai_aktual, $keterangan = null)
    {
        $k = $this->getKriById($id_kri);
        if (!$k) {
            return false;
        }
        $k->nilai_aktual = ($nilai_aktual === null || $nilai_aktual === '') ? null : (float) $nilai_aktual;
        $row = [
            'nilai_aktual' => $k->nilai_aktual,
            'status_kri'    => $this->hitungStatus($k),
            'modified_at'   => date('Y-m-d H:i:s'),
            'modified_by'   => $this->user(),
        ];
        if ($keterangan !== null) {
            $row['keterangan'] = $keterangan;
        }
        $this->db->where('id_kri', $id_kri);
        return $this->db->update('mri_kri', $row);
    }

    public function simpanKri($id, $data)
    {
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = $this->user();
        if ($id) {
            $this->db->where('id_kri', $id);
            return $this->db->update('mri_kri', $data);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->user();
        return $this->db->insert('mri_kri', $data);
    }

    /** Rekap status KRI untuk dashboard. */
    public function rekapStatus($tahun)
    {
        $this->db->select('status_kri, COUNT(*) AS jml', false);
        $this->db->where('tahun', $tahun);
        $this->db->group_by('status_kri');
        $hasil = ['Belum Diukur' => 0, 'Tercapai' => 0, 'Tidak Tercapai' => 0, 'Perlu Perhatian' => 0];
        foreach ($this->db->get('mri_kri')->result() as $r) {
            $hasil[$r->status_kri] = (int) $r->jml;
        }
        return $hasil;
    }

    // ---------------- mri_laporan ----------------

    public function getLaporan($tahun, $jenis = null, $id_upk = null)
    {
        $this->db->select('l.*, b.nama_bagian');
        $this->db->from('mri_laporan l');
        $this->db->join('bagian_upk b', 'b.id_bagian = l.id_upk', 'left');
        if ($tahun) {
            $this->db->where('l.tahun', $tahun);
        }
        if ($jenis) {
            $this->db->where('l.jenis_laporan', $jenis);
        }
        if ($id_upk) {
            $this->db->where('l.id_upk', $id_upk);
        }
        $this->db->order_by('l.triwulan', 'DESC');
        $this->db->order_by('l.id_laporan', 'DESC');
        return $this->db->get()->result();
    }

    public function getLaporanById($id)
    {
        return $this->db->get_where('mri_laporan', ['id_laporan' => $id])->row();
    }

    public function simpanLaporan($id, $data)
    {
        if (isset($data['jenis_laporan']) && !in_array($data['jenis_laporan'], self::JENIS_LAPORAN, true)) {
            $data['jenis_laporan'] = 'Operasional';
        }
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = $this->user();
        if ($id) {
            $this->db->where('id_laporan', $id);
            return $this->db->update('mri_laporan', $data);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->user();
        return $this->db->insert('mri_laporan', $data);
    }

    public function setujuiLaporan($id, $disahkan_oleh)
    {
        $this->db->where('id_laporan', $id);
        return $this->db->update('mri_laporan', [
            'status'        => 'FINAL',
            'disahkan_oleh' => $disahkan_oleh,
            'tanggal_sahkan' => date('Y-m-d'),
            'modified_at'   => date('Y-m-d H:i:s'),
            'modified_by'   => $this->user(),
        ]);
    }
}
