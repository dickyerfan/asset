<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Laporan manajemen risiko (mri_laporan).
 *
 * Empat jenis laporan mengikuti Ringkasan MR §10.1:
 * Operasional, Manajemen, Dewan Pengawas, Tahunan.
 *
 * Isi laporan disusun dari data proses (Register → Monitoring) oleh
 * Laporan::otomatis(); model ini hanya menangani penyimpanan.
 */
class Model_mri_laporan extends CI_Model
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

    /**
     * Cari laporan untuk satu periode + cakupan.
     *
     * Dipakai "Susun Laporan Otomatis" agar klik berulang memperbarui draf
     * yang sama, bukan membuat baris ganda.
     *
     * @param int    $id_upk 0 = seluruh organisasi
     * @return object|null
     */
    public function getLaporanByPeriode($tahun, $triwulan, $jenis, $id_upk = 0)
    {
        return $this->db
            ->where('tahun', (int) $tahun)
            ->where('triwulan', (int) $triwulan)
            ->where('jenis_laporan', $jenis)
            ->where('id_upk', (int) $id_upk)
            ->order_by('id_laporan', 'DESC')
            ->get('mri_laporan')
            ->row();
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
        $this->db->insert('mri_laporan', $data);
        return $this->db->insert_id();
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
