<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Perlakuan risiko (ISO 31000:2018 Klausa 6.6) — mri_perlakuan
 * serta pemantauan & komunikasi (Klausa 6.7) — mri_monitoring.
 */
class Model_mri_perlakuan extends CI_Model
{
    /** Strategi perlakuan — persis dengan enum mri_perlakuan.strategi. */
    const STRATEGI = ['AVOID', 'REDUCE', 'SHARE', 'ACCEPT'];

    /** Terjemahan tampilan untuk enum strategi. */
    const LABEL_STRATEGI = [
        'AVOID'  => 'Hindari',
        'REDUCE' => 'Kurangi',
        'SHARE'  => 'Transfer / Bagi',
        'ACCEPT' => 'Terima',
    ];

    /** Status pelaksanaan — persis dengan enum mri_perlakuan.status_laksana. */
    const STATUS_LAKSANA = ['BELUM', 'DALAM_PROSES', 'SELESAI'];

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

    // ---------------- mri_perlakuan ----------------

    public function getById($id)
    {
        return $this->db->get_where('mri_perlakuan', ['id_perlakuan' => $id])->row();
    }

    public function getByRegister($id_register)
    {
        $this->db->where('id_register', $id_register);
        $this->db->order_by('id_perlakuan', 'DESC');
        return $this->db->get('mri_perlakuan')->row();
    }

    public function getListPerlakuan($tahun, $id_upk = null, $status_laksana = null)
    {
        $this->db->select('p.*, r.kode_risiko, r.risiko, r.id_upk, r.tahun, r.sasaran_kpi, b.nama_bagian, e.keputusan, e.nilai_evaluasi, e.level_evaluasi');
        $this->db->from('mri_perlakuan p');
        $this->db->join('mri_register r', 'r.id_register = p.id_register', 'left');
        $this->db->join('mri_evaluasi e', 'e.id_register = p.id_register', 'left');
        $this->db->join('bagian_upk b', 'b.id_bagian = r.id_upk', 'left');
        if ($tahun) {
            $this->db->where('r.tahun', $tahun);
        }
        if ($id_upk) {
            $this->db->where('r.id_upk', $id_upk);
        }
        if ($status_laksana !== null && $status_laksana !== '') {
            $this->db->where('p.status_laksana', $status_laksana);
        }
        $this->db->order_by('p.jadwal_selesai', 'ASC');
        $this->db->order_by('p.id_perlakuan', 'ASC');
        return $this->db->get()->result();
    }

    public function simpan($id_register, $d)
    {
        $row = [
            'id_register'     => $id_register,
            'id_evaluasi'     => isset($d['id_evaluasi']) ? $d['id_evaluasi'] : null,
            'strategi'        => isset($d['strategi']) && in_array($d['strategi'], self::STRATEGI, true) ? $d['strategi'] : 'REDUCE',
            'alasan_strategi' => isset($d['alasan_strategi']) ? $d['alasan_strategi'] : null,
            'uraian_tindakan' => isset($d['uraian_tindakan']) ? $d['uraian_tindakan'] : null,
            'indikator_sukses' => isset($d['indikator_sukses']) ? $d['indikator_sukses'] : null,
            'anggaran_biaya'  => isset($d['anggaran_biaya']) && $d['anggaran_biaya'] !== '' ? str_replace(',', '.', $d['anggaran_biaya']) : null,
            'sumber_daya'     => isset($d['sumber_daya']) ? $d['sumber_daya'] : null,
            'jadwal_mulai'    => !empty($d['jadwal_mulai']) ? $d['jadwal_mulai'] : null,
            'jadwal_selesai'  => !empty($d['jadwal_selesai']) ? $d['jadwal_selesai'] : null,
            'id_pic'          => isset($d['id_pic']) && $d['id_pic'] !== '' ? $d['id_pic'] : null,
            'status_laksana'  => isset($d['status_laksana']) && in_array($d['status_laksana'], self::STATUS_LAKSANA, true)
                ? $d['status_laksana'] : 'BELUM',
            'modified_at'     => date('Y-m-d H:i:s'),
            'modified_by'     => $this->user(),
        ];

        $existing = $this->getByRegister($id_register);
        if ($existing) {
            $this->db->where('id_perlakuan', $existing->id_perlakuan);
            $this->db->update('mri_perlakuan', $row);
            return $existing->id_perlakuan;
        }

        $row['created_at'] = date('Y-m-d H:i:s');
        $row['created_by'] = $this->user();
        $this->db->insert('mri_perlakuan', $row);
        return $this->db->insert_id();
    }

    /**
     * Rekap progres perlakuan per status untuk dashboard.
     */
    public function rekapStatus($tahun)
    {
        $this->db->select('p.status_laksana, COUNT(*) AS jml', false);
        $this->db->from('mri_perlakuan p');
        $this->db->join('mri_register r', 'r.id_register = p.id_register', 'left');
        if ($tahun) {
            $this->db->where('r.tahun', $tahun);
        }
        $this->db->group_by('p.status_laksana');
        return $this->db->get()->result();
    }

    // ---------------- mri_monitoring ----------------

    public function getMonitorById($id)
    {
        return $this->db->get_where('mri_monitoring', ['id_monitoring' => $id])->row();
    }

    public function getMonitorByRegister($id_register, $limit = null)
    {
        $this->db->where('id_register', $id_register);
        $this->db->order_by('id_monitoring', 'DESC');
        if ($limit) {
            $this->db->limit($limit);
        }
        return $this->db->get('mri_monitoring')->result();
    }

    public function getMonitorTerakhir($id_register)
    {
        $this->db->where('id_register', $id_register);
        $this->db->order_by('id_monitoring', 'DESC');
        return $this->db->get('mri_monitoring')->row();
    }

    public function simpanMonitor($d, $id = null)
    {
        $row = [
            'id_register'         => $d['id_register'],
            'tahun'               => isset($d['tahun']) ? (int) $d['tahun'] : (int) date('Y'),
            'triwulan'            => isset($d['triwulan']) ? (int) $d['triwulan'] : 1,
            'status_tindakan'     => isset($d['status_tindakan']) ? $d['status_tindakan'] : null,
            'hasil_monitoring'    => isset($d['hasil_monitoring']) ? $d['hasil_monitoring'] : null,
            'kontrol_masih_efektif' => isset($d['kontrol_masih_efektif']) && in_array($d['kontrol_masih_efektif'], ['YA', 'TIDAK', 'SEBAGIAN'], true)
                ? $d['kontrol_masih_efektif'] : 'YA',
            'nilai_lama'          => isset($d['nilai_lama']) && $d['nilai_lama'] !== '' ? (int) $d['nilai_lama'] : null,
            'nilai_baru'          => isset($d['nilai_baru']) && $d['nilai_baru'] !== '' ? (int) $d['nilai_baru'] : null,
            'perubahan'           => isset($d['perubahan']) && in_array($d['perubahan'], ['Naik', 'Turun', 'Tetap'], true)
                ? $d['perubahan'] : null,
            'sebab_perubahan'     => isset($d['sebab_perubahan']) ? $d['sebab_perubahan'] : null,
            'perlu_reassess'      => !empty($d['perlu_reassess']) ? 1 : 0,
            'tanggal_review'      => !empty($d['tanggal_review']) ? $d['tanggal_review'] : date('Y-m-d'),
            'review_oleh'         => $this->user(),
            'file_document'       => isset($d['file_document']) && $d['file_document'] !== '' ? $d['file_document'] : null,
            'modified_at'         => date('Y-m-d H:i:s'),
            'modified_by'         => $this->user(),
        ];

        if ($id) {
            $this->db->where('id_monitoring', $id);
            return $this->db->update('mri_monitoring', $row);
        }
        $row['created_at'] = date('Y-m-d H:i:s');
        $row['created_by'] = $this->user();
        return $this->db->insert('mri_monitoring', $row);
    }

    public function deleteMonitor($id)
    {
        $this->db->where('id_monitoring', $id);
        return $this->db->delete('mri_monitoring');
    }
}
