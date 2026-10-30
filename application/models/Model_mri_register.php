<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Risk Register inti modul manrisk (SNI ISO 31000:2018 Klausa 6.2 - 6.4).
 *
 * Tabel dikelola:
 *   mri_register         : identifikasi risiko
 *   mri_akibat           : multi-akibat per risiko
 *   mri_risiko_proses    : many-to-many risiko <-> proses (Risk Universe A-K)
 *   mri_risiko_objektif  : many-to-many risiko <-> objektif RKAP
 *
 * Skala skor: L x D (1..5 x 1..5 = 1..25), level dari mri_tingkat_risiko.
 * Tabel legacy mr_* TIDAK pernah dibaca/tulis oleh model ini.
 */
class Model_mri_register extends CI_Model
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

    /** Apakah role ini boleh melihat seluruh unit? (disamakan dengan aturan legacy) */
    public function aksesSemuaUnit()
    {
        $bagian = $this->session->userdata('bagian');
        return in_array($bagian, ['Administrator', 'Keuangan', 'Publik', 'Auditor']);
    }

    /** Unit yang dipaksa untuk role terbatas. */
    public function unitPaksa()
    {
        if ($this->aksesSemuaUnit()) {
            return null;
        }
        return $this->session->userdata('id_bagian');
    }

    // ------------------------------------------------------------------
    // QUERY
    // ------------------------------------------------------------------

    /**
     * Daftar register dengan filter.
     * @param array $f keys: tahun, triwulan, id_upk, id_kategori, tipe_risiko,
     *                 status_risiko, status_kendali, id_proses, id_objektif, q, status_blok
     */
    public function getList($f = [])
    {
        $this->db->select('r.*, k.kode_kat, k.nama_kat, b.nama_bagian');
        $this->db->from('mri_register r');
        $this->db->join('mri_kategori_risiko k', 'k.id_kategori = r.id_kategori', 'left');
        $this->db->join('bagian_upk b', 'b.id_bagian = r.id_upk', 'left');

        // lingkup akses
        $paks = $this->unitPaksa();
        if ($paks) {
            $this->db->where('r.id_upk', $paks);
        }

        if (!empty($f['tahun'])) {
            $this->db->where('r.tahun', $f['tahun']);
        }
        if (!empty($f['triwulan'])) {
            $this->db->where('r.triwulan_awal', $f['triwulan']);
        }
        if (!empty($f['id_upk'])) {
            $this->db->where('r.id_upk', $f['id_upk']);
        }
        if (!empty($f['id_kategori'])) {
            $this->db->where('r.id_kategori', $f['id_kategori']);
        }
        if (!empty($f['tipe_risiko'])) {
            $this->db->where('r.tipe_risiko', $f['tipe_risiko']);
        }
        if (!empty($f['status_risiko'])) {
            $this->db->where('r.status_risiko', $f['status_risiko']);
        }
        if (!empty($f['status_kendali'])) {
            $this->db->where('r.status_kendali', $f['status_kendali']);
        }
        if (!empty($f['status_blok'])) {
            $this->db->where('r.status_risiko', $f['status_blok']);
        }
        if (!empty($f['id_proses'])) {
            $this->db->join('mri_risiko_proses rp', 'rp.id_register = r.id_register', 'left');
            $this->db->where('rp.id_proses', $f['id_proses']);
        }
        if (!empty($f['id_objektif'])) {
            $this->db->join('mri_risiko_objektif ro', 'ro.id_register = r.id_register', 'left');
            $this->db->where('ro.id_objektif', $f['id_objektif']);
        }
        if (!empty($f['q'])) {
            $this->db->group_start();
            $this->db->like('r.kode_risiko', $f['q']);
            $this->db->or_like('r.risiko', $f['q']);
            $this->db->or_like('r.sasaran_kpi', $f['q']);
            $this->db->or_like('r.kegiatan', $f['q']);
            $this->db->group_end();
        }

        $this->db->order_by('r.kode_risiko', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Daftar register lengkap dengan skor inherent & residual dari mri_analisa,
     * plus pengikatan proses (dikonkatenasi) untuk keperluan tabel & ekspor.
     */
    public function getListDenganSkor($f = [])
    {
        $rows = $this->getList($f);
        if (!$rows) {
            return [];
        }
        $ids = array_column($rows, 'id_register');
        $analisa = $this->ambilAnalisaBanyak($ids);
        $proses  = $this->ambilProsesBanyak($ids);
        foreach ($rows as $i => $r) {
            $a = isset($analisa[$r->id_register]) ? $analisa[$r->id_register] : null;
            $rows[$i]->inherent_l   = $a ? (int) $a->inherent_l : null;
            $rows[$i]->inherent_d   = $a ? (int) $a->inherent_d : null;
            $rows[$i]->nilai_inherent = $a ? (int) $a->nilai_inherent : null;
            $rows[$i]->level_inherent = $a ? $a->level_inherent : null;
            $rows[$i]->residual_l   = $a ? (int) $a->residual_l : null;
            $rows[$i]->residual_d   = $a ? (int) $a->residual_d : null;
            $rows[$i]->nilai_residual = $a ? (int) $a->nilai_residual : null;
            $rows[$i]->level_residual = $a ? $a->level_residual : null;
            $rows[$i]->kode_proses  = isset($proses[$r->id_register]) ? $proses[$r->id_register] : '';
        }
        return $rows;
    }

    /** [id_register => row mri_analisa] untuk sekumpulan id. */
    public function ambilAnalisaBanyak($ids)
    {
        $out = [];
        if (!$ids) {
            return $out;
        }
        $this->db->where_in('id_register', $ids);
        foreach ($this->db->get('mri_analisa')->result() as $r) {
            $out[$r->id_register] = $r;
        }
        return $out;
    }

    /** [id_register => 'A, D, I'] pemetaan risiko ke proses. */
    public function ambilProsesBanyak($ids)
    {
        $out = [];
        if (!$ids) {
            return $out;
        }
        $this->db->select('rp.id_register, p.kode_proses, p.urut');
        $this->db->from('mri_risiko_proses rp');
        $this->db->join('mri_proses p', 'p.id_proses = rp.id_proses', 'left');
        $this->db->where_in('rp.id_register', $ids);
        $this->db->order_by('p.urut', 'ASC');
        foreach ($this->db->get()->result() as $r) {
            $out[$r->id_register] = isset($out[$r->id_register])
                ? $out[$r->id_register] . ', ' . $r->kode_proses
                : $r->kode_proses;
        }
        return $out;
    }

    public function getById($id)
    {
        $this->db->select('r.*, k.kode_kat, k.nama_kat, b.nama_bagian');
        $this->db->from('mri_register r');
        $this->db->join('mri_kategori_risiko k', 'k.id_kategori = r.id_kategori', 'left');
        $this->db->join('bagian_upk b', 'b.id_bagian = r.id_upk', 'left');
        $this->db->where('r.id_register', $id);
        return $this->db->get()->row();
    }

    public function getByKode($kode)
    {
        return $this->db->get_where('mri_register', ['kode_risiko' => $kode])->row();
    }

    public function getAkibat($id_register)
    {
        $this->db->where('id_register', $id_register);
        $this->db->order_by('urutan', 'ASC');
        return $this->db->get('mri_akibat')->result();
    }

    public function getProses($id_register)
    {
        $this->db->select('rp.*, p.kode_proses, p.nama_proses');
        $this->db->from('mri_risiko_proses rp');
        $this->db->join('mri_proses p', 'p.id_proses = rp.id_proses', 'left');
        $this->db->where('rp.id_register', $id_register);
        return $this->db->get()->result();
    }

    public function getObjektifTerkait($id_register)
    {
        $this->db->select('ro.id_register, ro.id_objektif, o.indikator, o.nilai_rkap, o.satuan, o.status_rekonsiliasi');
        $this->db->from('mri_risiko_objektif ro');
        $this->db->join('mri_objektif o', 'o.id_objektif = ro.id_objektif', 'left');
        $this->db->where('ro.id_register', $id_register);
        return $this->db->get()->result();
    }

    /**
     * Saran kode risiko berikutnya untuk sebuah segmen, format IJT-<SEGMEN>-<NN>.
     *
     * SEGMENTASI BUKAN TURUNAN KATEGORI. Pada data yang ada, segmen bersifat
     * tematik terhadap isi risiko, bukan kategorinya. Contoh nyata:
     *   IJT-GOV-* memuat kategori Strategic sekaligus Fraud,
     *   IJT-KEU-* memuat kategori Credit sekaligus Finance,
     *   IJT-AMDK, IJT-K3, IJT-EXT, IJT-PEL, IJT-HR, IJT-OPR, IJT-REG, IJT-IT.
     * Karena itu kategori TIDAK boleh dipakai menebak segmen; segmen
     * dikirim eksplisit dari form.
     *
     * Tahun sengaja tidak disertakan agar format sama dengan data sumber
     * (Risk_Register_Template_Perumdam_Ijen_Tirta.csv memakai IJT-OPR-01).
     *
     * @param string $segmen mis. 'OPR', 'KEU', 'GOV'
     * @return string kode saran berikutnya
     */
    public function kodeBerikut($segmen)
    {
        $segmen = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) $segmen));
        if ($segmen === '') {
            $segmen = 'GEN';
        }

        $this->db->like('kode_risiko', 'IJT-' . $segmen . '-', 'after');
        $this->db->order_by('kode_risiko', 'DESC');
        $last = $this->db->get('mri_register')->row();

        $n = 1;
        if ($last && preg_match('/-(\d+)$/', $last->kode_risiko, $m)) {
            $n = ((int) $m[1]) + 1;
        }
        return sprintf('IJT-%s-%02d', $segmen, $n);
    }

    /**
     * Segmen kode yang benar-benar sudah terpakai, untuk dropdown saran kode.
     * @return array daftar string segmen terurut
     */
    public function daftarSegmen()
    {
        $this->db->select('DISTINCT SUBSTRING_INDEX(kode_risiko, "-", 2) AS segmen_penuh', false);
        $this->db->order_by('segmen_penuh', 'ASC');
        $out = [];
        foreach ($this->db->get('mri_register')->result() as $r) {
            $bagian = explode('-', $r->segmen_penuh);
            $out[] = isset($bagian[1]) ? $bagian[1] : $r->segmen_penuh;
        }
        return $out;
    }

    /**
     * Apakah sebuah kode masih bebas dipakai?
     * @param string $kode
     * @param int|null $kecualikan id_register yang dikecualikan (saat mengubah)
     * @return bool
     */
    public function kodeTersedia($kode, $kecualikan = null)
    {
        $this->db->where('kode_risiko', $kode);
        if ($kecualikan) {
            $this->db->where('id_register !=', $kecualikan);
        }
        return $this->db->count_all_results('mri_register') === 0;
    }

    // ------------------------------------------------------------------
    // TULIS
    // ------------------------------------------------------------------

    /**
     * Simpan risiko beserta akibat, pengikatan proses & objektif.
     * Dipakai untuk tambah maupun ubah (jika $id diisi).
     *
     * @param int|null $id
     * @param array $d      data utama mri_register
     * @param array $akibat list of ['urutan','akibat','jenis','dampak_kualitatif','probabilitas_terjadi','catatan']
     * @param array $proses list of id_proses
     * @param array $objektif list of id_objektif
     */
    public function simpan($id, $d, $akibat = [], $proses = [], $objektif = [])
    {
        $this->db->trans_begin();

        $now = date('Y-m-d H:i:s');
        $usr = $this->user();

        if ($id) {
            $d['modified_at'] = $now;
            $d['modified_by'] = $usr;
            $this->db->where('id_register', $id);
            $this->db->update('mri_register', $d);
            $id_register = $id;
        } else {
            $d['created_at'] = $now;
            $d['created_by'] = $usr;
            $this->db->insert('mri_register', $d);
            $id_register = $this->db->insert_id();
        }

        // akibat: hapus lama, tulis ulang agar urutan konsisten
        $this->db->where('id_register', $id_register);
        $this->db->delete('mri_akibat');
        $urut = 1;
        foreach ((array) $akibat as $a) {
            if (trim((string) $a['akibat']) === '') {
                continue;
            }
            $this->db->insert('mri_akibat', [
                'id_register'          => $id_register,
                'urutan'               => $urut++,
                'akibat'               => $a['akibat'],
                'jenis'                => isset($a['jenis']) && in_array($a['jenis'], ['Finansial', 'Non-Finansial'], true)
                    ? $a['jenis'] : 'Non-Finansial',
                'dampak_kualitatif'    => isset($a['dampak_kualitatif']) ? $a['dampak_kualitatif'] : null,
                'probabilitas_terjadi' => isset($a['probabilitas_terjadi']) ? $a['probabilitas_terjadi'] : null,
                'catatan'              => isset($a['catatan']) ? $a['catatan'] : null,
                'created_at'           => $now,
                'created_by'           => $usr,
            ]);
        }

        $this->gantiJunction('mri_risiko_proses', 'id_register', $id_register, 'id_proses', $proses);
        $this->gantiJunction('mri_risiko_objektif', 'id_register', $id_register, 'id_objektif', $objektif);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $id_register;
    }

    /**
     * Ganti isi tabel junction: hapus semua baris milik $id_register lalu tulis $nilai.
     * Kedua tabel junction mri_* tidak memiliki kolom audit, jadi hanya
     * induk + target yang ditulis.
     *
     * @param string $tabel        nama tabel junction
     * @param string $kolomInduk   kolom pemilik (id_register)
     * @param int    $idInduk
     * @param string $kolomTarget  kolom target junction (id_proses/id_objektif)
     * @param array  $nilai        daftar nilai target
     */
    private function gantiJunction($tabel, $kolomInduk, $idInduk, $kolomTarget, $nilai)
    {
        $this->db->where($kolomInduk, $idInduk);
        $this->db->delete($tabel);

        $nilai = array_values(array_unique(array_filter((array) $nilai, function ($v) {
            return $v !== '' && $v !== null;
        })));
        foreach ($nilai as $target) {
            $this->db->insert($tabel, [$kolomInduk => $idInduk, $kolomTarget => (int) $target]);
        }
    }

    /** Nonaktifkan risiko (bukan hapus fisik) agar jejak audit tetap ada. */
    public function nonaktifkan($id)
    {
        $this->db->where('id_register', $id);
        return $this->db->update('mri_register', [
            'status_risiko' => 'NONAKTIF',
            'modified_at'   => date('Y-m-d H:i:s'),
            'modified_by'   => $this->user(),
        ]);
    }

    /**
     * Rekap sebaran risiko per unit / kategori / level untuk dashboard.
     * @param string $dimension 'id_upk' | 'id_kategori' | 'tipe_risiko'
     */
    public function rekap($dimension, $tahun, $f = [])
    {
        $allowed = ['id_upk', 'id_kategori', 'tipe_risiko'];
        if (!in_array($dimension, $allowed, true)) {
            $dimension = 'id_upk';
        }

        $this->db->select("$dimension AS kunci, COUNT(*) AS jml", false);
        $this->db->from('mri_register r');
        if (!empty($f['tahun'])) {
            $this->db->where('r.tahun', $f['tahun']);
        } elseif ($tahun) {
            $this->db->where('r.tahun', $tahun);
        }
        $paks = $this->unitPaksa();
        if ($paks) {
            $this->db->where('r.id_upk', $paks);
        }
        $this->db->group_by($dimension);
        return $this->db->get()->result();
    }
}
