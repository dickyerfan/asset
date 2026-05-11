<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_penyusutan_peralatan extends CI_Model
{

    // public function get_peralatan($tahun_lap)
    // {
    //     $this->db->select('
    //     penyusutan.*, 
    //     daftar_asset.*, 
    //     no_per.*, 
    //     bagian_upk.*,
    //     daftar_asset.status AS status_penyusutan');

    //     $this->db->from('penyusutan');
    //     $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
    //     $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
    //     $this->db->join('bagian_upk', 'bagian_upk.id_bagian = daftar_asset.id_bagian', 'left');
    //     $this->db->group_start()
    //         // ===============================
    //         // STATUS = 1 (PENAMBAHAN)
    //         // ===============================
    //         ->group_start()
    //         ->where('daftar_asset.status', 1)
    //         ->group_start()
    //         // logika lama
    //         ->where('penyusutan.tahun <', 2024)
    //         // logika baru
    //         ->or_where('penyusutan.tahun <=', $tahun_lap)
    //         ->group_end()
    //         ->group_end()

    //         // ===============================
    //         // STATUS = 2 (PENGURANGAN)
    //         // ===============================
    //         ->or_group_start()
    //         ->where('daftar_asset.status', 2)
    //         ->where('penyusutan.tahun_persediaan <=', $tahun_lap)
    //         ->group_end()
    //         ->group_end();
    //     $this->db->where('daftar_asset.grand_id', 244);
    //     $this->db->order_by('bagian_upk.id_bagian', 'ASC');
    //     $this->db->order_by('id_no_per', 'ASC');
    //     $this->db->order_by('daftar_asset.id_asset', 'ASC');
    //     $this->db->order_by('tanggal', 'ASC');

    //     $query = $this->db->get();
    //     $results = $query->result();

    //     $tahun = $tahun_lap;
    //     if (empty($tahun)) {
    //         $tahun = date('Y');
    //     }

    //     // Inisialisasi variabel untuk menyimpan total
    //     $total_rupiah = 0;
    //     $total_nilai_buku = 0;
    //     $total_penambahan = 0;
    //     $total_pengurangan = 0;
    //     $total_akm_thn_lalu = 0;
    //     $total_nilai_buku_lalu = 0;
    //     $total_penyusutan = 0;
    //     $total_akm_thn_ini = 0;
    //     $total_nilai_buku_final = 0;

    //     // Daftar ID parent untuk bangunan
    //     $parent_ids_bangunan = [1569, 1907, 2104, 2255, 2671, 2676, 2678, 2680];

    //     foreach ($results as &$row) {
    //         if (
    //             $row->status_penyusutan == 2 &&
    //             (int)$tahun < (int)$row->tahun_persediaan
    //         ) {
    //             $row->nilai_buku = 0;
    //             $row->penambahan = 0;
    //             $row->pengurangan = 0;
    //             $row->akm_thn_lalu = 0;
    //             $row->nilai_buku_lalu = 0;
    //             $row->penambahan_penyusutan = 0;
    //             $row->akm_thn_ini = 0;
    //             $row->nilai_buku_final = 0;
    //             continue;
    //         }
    //         $umur_tahun = $tahun - $row->tahun;
    //         $nilai_buku_awal = $row->rupiah; // Nilai awal aset
    //         $akm_thn_ini = 0;                 // Akumulasi penyusutan tahun ini
    //         $nilai_buku_final = $nilai_buku_awal; // Nilai buku final untuk tahun berjalan

    //         if ($umur_tahun == 0) {
    //             // Kondisi untuk umur_tahun = 0
    //             $row->akm_thn_lalu = 0;
    //             $row->nilai_buku = 0;
    //             $row->penambahan_penyusutan = 0;
    //             $row->nilai_buku_lalu = 0;
    //             $row->akm_thn_ini = 0;
    //             $row->nilai_buku_final = $nilai_buku_awal;
    //         } else {
    //             $row->pengurangan = 0;
    //             $row->penambahan = 0;
    //             // Perhitungan bertahap untuk setiap tahun sejak tahun pertama
    //             for ($i = 1; $i <= $umur_tahun; $i++) {
    //                 if ($i == 1) {
    //                     // Tahun pertama
    //                     $akm_thn_lalu = 0;
    //                     $nilai_buku_lalu = $nilai_buku_awal;
    //                 } else {
    //                     // Update nilai buku dan akumulasi penyusutan dari tahun sebelumnya
    //                     $akm_thn_lalu = $akm_thn_ini;
    //                     $nilai_buku_lalu = $nilai_buku_final;
    //                 }

    //                 // Hitung penyusutan berdasarkan kategori aset
    //                 if (in_array($row->parent_id, $parent_ids_bangunan)) {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
    //                 } else {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
    //                 }

    //                 // Update akumulasi penyusutan dan nilai buku akhir
    //                 $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
    //                 $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

    //                 if ($i > $row->umur) {

    //                     // --- LOGIKA UNTUK TAHUN PELAPORAN SAAT INI ($tahun_lap) SAJA ---
    //                     // Pastikan finalisasi dan break hanya terjadi di iterasi terakhir (tahun laporan saat ini)
    //                     if ($i == $umur_tahun) {
    //                         // *** CATATAN: akm_thn_lalu dan nilai_buku_lalu sudah benar terisi dari tahun sebelumnya (2024)

    //                         $akm_thn_ini = $row->rupiah; // Set akumulasi awal
    //                         $nilai_buku_final = 1;      // Set nilai buku default
    //                         $penambahan_penyusutan = 0;
    //                         $row->penambahan = 0;
    //                         // Hapus baris yang menimpa akm_thn_lalu dan nilai_buku_lalu (sesuai perbaikan sebelumnya)

    //                         if ($row->status == 1) {
    //                             $nilai_buku_final = $row->rupiah - $akm_thn_ini;
    //                             if ($nilai_buku_final == 0 || $umur_tahun > $row->umur) {
    //                                 $nilai_buku_final = 1; // Nilai Buku Final = 1
    //                                 $akm_thn_ini = $akm_thn_ini - 1; // Akm Thn Ini = Rupiah - 1
    //                             }
    //                         } else {
    //                             $nilai_buku_final = -1;
    //                             $akm_thn_ini = $row->rupiah - $nilai_buku_final;
    //                         }

    //                         break; // Hentikan loop HANYA di tahun pelaporan
    //                     }

    //                     // --- LOGIKA UNTUK TAHUN HISTORIS SEBELUM TAHUN LAPORAN (i > umur TAPI i < umur_tahun) ---
    //                     // Jika aset sudah habis umur, tetapi belum mencapai tahun laporan, kunci nilainya.
    //                     $penambahan_penyusutan = 0;

    //                     // Kunci akumulasi dan nilai buku (asumsi nilai buku residu 1)
    //                     $akm_thn_ini = $row->rupiah - 1;
    //                     $nilai_buku_final = 1;

    //                     // TIDAK ADA 'break;' di sini. Loop akan terus berjalan hingga i mencapai umur_tahun.
    //                 }
    //             }

    //             // Set hasil akhir setelah loop tahun selesai
    //             $row->akm_thn_lalu = $akm_thn_lalu;
    //             $row->nilai_buku_lalu = $nilai_buku_lalu;
    //             $row->penambahan_penyusutan = $penambahan_penyusutan;
    //             $row->akm_thn_ini = $akm_thn_ini;
    //             $row->nilai_buku_final = $nilai_buku_final;
    //         }

    //         // if ($row->status == 2) {

    //         //     // Tahun aset mulai dihentikan penyusutannya
    //         //     $tahun_hapus = (int)$row->tahun_persediaan;

    //         //     // Jika tahun_persediaan di DB kosong/null → fallback ke tahun perolehan
    //         //     if (!$tahun_hapus || $tahun_hapus == 0) {
    //         //         $tahun_hapus = (int)$row->tahun;
    //         //     }

    //         //     // RAPEL: Tahun laporan (input)
    //         //     $tahun_lap = (int)$tahun;

    //         //     // NILAI AWAL
    //         //     $rupiah = $row->rupiah;

    //         //     // -------------------------------------------------------
    //         //     // 1. TAHUN SEBELUM PENGHAPUSAN → PERHITUNGAN NORMAL
    //         //     // (jangan ganggu, biarkan logika normal yang sudah Anda buat)
    //         //     // -------------------------------------------------------
    //         //     if ($tahun_lap < $tahun_hapus) {
    //         //         // Perhitungan normal tetap jalan → TIDAK menimpa hasil
    //         //         continue;
    //         //     }

    //         //     // -------------------------------------------------------
    //         //     // 2. TAHUN PENGHAPUSAN (tahun_lap == tahun_persediaan)
    //         //     // -------------------------------------------------------
    //         //     if ($tahun_lap == $tahun_hapus) {

    //         //         $row->akm_thn_lalu = 0;             // sebelum dihapus
    //         //         $row->nilai_buku_lalu = $rupiah;    // NB sblm dihapus
    //         //         $row->penambahan_penyusutan = 0;
    //         //         $row->pengurangan = -$rupiah;       // penghapusan aset
    //         //         $row->akm_thn_ini = $rupiah;        // akm penuh
    //         //         $row->nilai_buku_final = 0;         // NB habis
    //         //         $row->penambahan = 0;

    //         //         continue;
    //         //     }

    //         //     // -------------------------------------------------------
    //         //     // 3. TAHUN SETELAH PENGHAPUSAN (tahun_lap > tahun_hapus)
    //         //     // FIX: TIDAK ADA BLOK NEGATIF, TIDAK BERTAMBAH LAGI
    //         //     // -------------------------------------------------------
    //         //     if ($tahun_lap > $tahun_hapus) {

    //         //         $row->akm_thn_lalu = $rupiah + 1;       // tetap (tidak berubah)
    //         //         $row->nilai_buku_lalu = -1;
    //         //         $row->penambahan_penyusutan = 0;
    //         //         $row->pengurangan = 0;              // penghapusan hanya sekali!
    //         //         $row->akm_thn_ini = $rupiah + 1;        // tetap (tidak berubah)
    //         //         $row->nilai_buku_final = -1;         // NB tetap 0
    //         //         $row->penambahan = 0;

    //         //         continue;
    //         //     }
    //         // }

    //         if ($row->status_penyusutan == 2) {
    //             $tahun_hapus = (int)$row->tahun_persediaan;
    //             $tahun_lap = (int)$tahun;
    //             $rupiah = $row->rupiah;

    //             $umur_tahun = $tahun - $row->tahun_persediaan;
    //             $umur_tahun_kurang = $tahun - $row->tahun;
    //             if ($umur_tahun == 0) {
    //                 $row->nilai_buku = 0;
    //                 $row->pengurangan = $row->rupiah * -1;
    //                 $row->nilai_buku_lalu = 0;
    //                 $row->akm_thn_lalu = 0;
    //                 $row->penambahan_penyusutan = 0;
    //                 $row->nilai_buku_final = $nilai_buku_final;
    //             } else {
    //                 for ($i = 1; $i <= $umur_tahun_kurang; $i++) {
    //                     $row->pengurangan = 0;
    //                     $row->penambahan = 0;
    //                     $akm_thn_lalu = $akm_thn_ini;
    //                     $nilai_buku_lalu = $nilai_buku_final;
    //                 }
    //                 if (in_array($row->parent_id, $parent_ids_bangunan)) {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
    //                 } else {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
    //                 }
    //                 $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
    //                 $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

    //                 if ($i > $row->umur) {
    //                     $row->pengurangan = 0;
    //                     $row->penambahan = 0;
    //                     $akm_thn_lalu = $row->rupiah;
    //                     $nilai_buku_lalu = 0;
    //                     $penambahan_penyusutan = 0;
    //                 }
    //             }
    //         }


    //         // Kondisi khusus untuk tanah
    //         if ($row->grand_id == 218) {
    //             $row->akm_thn_lalu = 0;
    //             $row->akm_thn_ini = 0;
    //             $row->nilai_buku_lalu = 0;
    //             $row->nilai_buku_final = $row->rupiah;
    //         }

    //         // // ==========================================
    //         // // STATUS = 2 (PENGURANGAN ASET)
    //         // // ==========================================

    //         // // ⚠️ CATATAN KEBIJAKAN:
    //         // // Sistem penyusutan baru berlaku efektif mulai 2025
    //         // // Data s.d. 2024 adalah hasil audit dan TIDAK BOLEH DIUBAH
    //         // // Oleh karena itu umur_aktual dikurangi 1 tahun secara sengaja

    //         // // 🔒 FLAG PENGUNCI FINAL (WAJIB ADA)
    //         // $is_final_pengurangan = false;

    //         // // Ambil tahun perolehan sekali saja
    //         // $tahun_perolehan = (int) date('Y', strtotime($row->tanggal));
    //         // $umur_aktual     = (int)$tahun - $tahun_perolehan - 1;

    //         // // ==================================================
    //         // // TRANSAKSI PENGURANGAN (HANYA TAHUN HAPUS)
    //         // // ==================================================
    //         // if (
    //         //     $row->status_penyusutan == 2 &&
    //         //     (int)$tahun == (int)$row->tahun_persediaan
    //         // ) {
    //         //     // Nilai pengurangan hanya muncul 1 kali
    //         //     $row->pengurangan = $row->rupiah * -1;

    //         //     // Tidak boleh ada penambahan
    //         //     $row->penambahan = 0;

    //         //     // Nilai buku & AKM tahun lalu nol
    //         //     $row->akm_thn_lalu     = 0;
    //         //     $row->nilai_buku_lalu = 0;
    //         // }

    //         // // ==================================================
    //         // // 1️⃣ PENGURANGAN BARU (TAHUN PERTAMA)
    //         // // ==================================================
    //         // if (
    //         //     $row->status == 2 &&
    //         //     (int)$tahun == (int)$row->tahun_persediaan
    //         // ) {
    //         //     // Tahun pertama pengurangan → nol
    //         //     $row->akm_thn_lalu     = 0;
    //         //     $row->nilai_buku_lalu = 0;
    //         // }

    //         // // ==================================================
    //         // // 3️⃣ FINAL LOCK (UMUR HABIS)
    //         // //    ❗ INI YANG MENGUNCI SEMUANYA
    //         // // ==================================================
    //         // if (
    //         //     $row->status_penyusutan == 2 &&
    //         //     (int)$tahun >= (int)$row->tahun_persediaan &&
    //         //     $umur_aktual > (int)$row->umur
    //         // ) {
    //         //     // 🔒 AKTIFKAN KUNCI
    //         //     $is_final_pengurangan = true;

    //         //     // Residual konstan
    //         //     $row->nilai_buku_final = -1;

    //         //     // 🔒 CAST KE INTEGER SEBELUM HITUNG
    //         //     $rupiah_int = (int)$row->rupiah;

    //         //     // AKM FINAL – STATIS & IDEMPOTENT
    //         //     $row->akm_thn_ini = $rupiah_int + 1;

    //         //     // Tahun lalu DIKUNCI sama
    //         //     $row->akm_thn_lalu     = $row->akm_thn_ini;
    //         //     $row->nilai_buku_lalu = $row->nilai_buku_final;
    //         // }

    //         // // ==================================================
    //         // // 2️⃣ PENGURANGAN LAMA (CARRY FORWARD)
    //         // //    ❗ TIDAK BOLEH DIHILANGKAN
    //         // // ==================================================
    //         // if (
    //         //     $row->status == 2 &&
    //         //     (int)$tahun >= 2024 &&
    //         //     $umur_aktual > (int)$row->umur &&
    //         //     !$is_final_pengurangan
    //         // ) {
    //         //     // Carry forward normal
    //         //     $row->akm_thn_lalu     = $row->akm_thn_ini;
    //         //     $row->nilai_buku_lalu = $row->nilai_buku_final;
    //         // }

    //         // // ==========================================
    //         // // AKHIR STATUS = 2
    //         // // ==========================================

    //         // ==========================================
    //         // STATUS = 2 (PENGURANGAN ASET) — FINAL
    //         // ==========================================
    //         // Tahun & umur
    //         $tahun_perolehan = (int)date('Y', strtotime($row->tanggal));
    //         $umur_aktual     = (int)$tahun - $tahun_perolehan - 1;

    //         // ================================
    //         // 1️⃣ TAHUN HAPUS (MUNCUL SEKALI)
    //         // ================================
    //         if (
    //             $row->status_penyusutan == 2 &&
    //             (int)$tahun == (int)$row->tahun_persediaan
    //         ) {
    //             $row->pengurangan       = (int)$row->rupiah * -1;
    //             $row->penambahan        = 0;
    //             $row->akm_thn_lalu      = 0;
    //             $row->nilai_buku_lalu   = 0;
    //         }

    //         // ================================
    //         // 2️⃣ FINAL LOCK (STATIS TOTAL)
    //         // ================================
    //         if (
    //             $row->status_penyusutan == 2 &&
    //             (int)$tahun >= (int)$row->tahun_persediaan &&
    //             $umur_aktual >= (int)$row->umur
    //         ) {
    //             // 🔒 SEMUA NILAI FINAL
    //             $rupiah_int = (int)$row->rupiah;

    //             $row->nilai_buku_final = -1;
    //             $row->akm_thn_ini      = $rupiah_int + 1;

    //             // Carry forward WAJIB dari nilai FINAL
    //             $row->akm_thn_lalu     = $row->akm_thn_ini;
    //             $row->nilai_buku_lalu = $row->nilai_buku_final;

    //             // 🔒 STOP — TIDAK BOLEH ADA LOGIKA LAIN
    //         }

    //         // ==========================================
    //         // 🔒 RESET KHUSUS TAHUN HAPUS (ANTI WARIS)
    //         // ==========================================
    //         if (
    //             $row->status_penyusutan == 2 &&
    //             (int)$tahun === (int)$row->tahun_persediaan
    //         ) {
    //             $row->akm_thn_lalu     = 0;
    //             $row->nilai_buku_lalu = 0;
    //         }

    //         // ======================================================
    //         // 🔒 FINAL LOCK KHUSUS AUDIT (UMUR HABIS = TAHUN HAPUS)
    //         // Berlaku mulai 2024 dan seterusnya
    //         // ======================================================
    //         $tahun_final = (int)$row->tahun + (int)$row->umur;

    //         if (
    //             $row->status_penyusutan == 2 &&
    //             (int)$tahun >= 2024 &&
    //             (int)$tahun >= $tahun_final &&
    //             (int)$row->tahun_persediaan == $tahun_final
    //         ) {
    //             // 🔒 NILAI FINAL AUDIT
    //             $row->nilai_buku_final = -1;

    //             // AKM disesuaikan agar konsisten lintas tahun
    //             $row->akm_thn_ini = (int)$row->rupiah - $row->nilai_buku_final;

    //             // WARIS WAJIB
    //             $row->akm_thn_lalu     = $row->akm_thn_ini;
    //             $row->nilai_buku_lalu = $row->nilai_buku_final;

    //             // STOP — TIDAK BOLEH ADA LOGIKA LAIN MENIMPA
    //         }

    //         // ======================================================
    //         // 🔒 LOCK ASET DIHAPUS DI TAHUN PEROLEHAN
    //         // Tahun lock: set final TH INI saja
    //         // Tahun setelahnya: nilai diwariskan
    //         // ======================================================

    //         $tahun_lock = (int)$row->tahun + (int)$row->umur - 1;

    //         if (
    //             (int)$row->status_penyusutan == 2 &&
    //             (int)$row->tahun_persediaan == (int)$row->tahun
    //         ) {

    //             // ===============================
    //             // 1️⃣ TAHUN LOCK (PERTAMA FINAL)
    //             // ===============================
    //             if ((int)$tahun == $tahun_lock) {

    //                 // set FINAL hanya untuk tahun ini
    //                 $row->nilai_buku_final = -1;
    //                 $row->akm_thn_ini      = (int)$row->rupiah - (-1);

    //                 // tahun lalu BIARKAN (ambil dari perhitungan normal / DB)
    //                 // $row->akm_thn_lalu
    //                 // $row->nilai_buku_lalu

    //                 // matikan pergerakan mulai tahun ini
    //                 $row->penambahan_penyusutan = 0;
    //                 $row->penambahan  = 0;
    //                 $row->pengurangan = 0;
    //             }

    //             // ===============================
    //             // 2️⃣ TAHUN SETELAH LOCK
    //             // ===============================
    //             if ((int)$tahun > $tahun_lock) {

    //                 // warisi nilai final
    //                 $row->nilai_buku_final = -1;
    //                 $row->akm_thn_ini      = (int)$row->rupiah - (-1);

    //                 $row->akm_thn_lalu     = $row->akm_thn_ini;
    //                 $row->nilai_buku_lalu = $row->nilai_buku_final;

    //                 // tidak boleh ada pergerakan
    //                 $row->penambahan_penyusutan = 0;
    //                 $row->penambahan  = 0;
    //                 $row->pengurangan = 0;
    //             }
    //         }
    //         // Akumulasi total dari setiap kolom
    //         $total_rupiah += $row->rupiah;
    //         $total_nilai_buku += $row->nilai_buku;
    //         $total_penambahan += $row->penambahan;
    //         $total_pengurangan += $row->pengurangan;
    //         $total_akm_thn_lalu += $row->akm_thn_lalu;
    //         $total_nilai_buku_lalu += $row->nilai_buku_lalu;
    //         $total_penyusutan += $row->penambahan_penyusutan;
    //         $total_akm_thn_ini += $row->akm_thn_ini;
    //         $total_nilai_buku_final += $row->nilai_buku_final;
    //     }

    //     // Return data beserta total
    //     return [
    //         'results' => $results,
    //         'total_peralatan' => [
    //             'total_rupiah' => $total_rupiah,
    //             'total_nilai_buku' => $total_nilai_buku,
    //             'total_penambahan' => $total_penambahan,
    //             'total_pengurangan' => $total_pengurangan,
    //             'total_akm_thn_lalu' => $total_akm_thn_lalu,
    //             'total_nilai_buku_lalu' => $total_nilai_buku_lalu,
    //             'total_penyusutan' => $total_penyusutan,
    //             'total_akm_thn_ini' => $total_akm_thn_ini,
    //             'total_nilai_buku_final' => $total_nilai_buku_final
    //         ]
    //     ];
    // }

    // public function get_peralatan($tahun_lap)
    // {
    //     $this->db->select('
    //     penyusutan.*, 
    //     daftar_asset.*, 
    //     no_per.*, 
    //     bagian_upk.*,
    //     daftar_asset.status AS status_penyusutan');

    //     $this->db->from('penyusutan');
    //     $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
    //     $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
    //     $this->db->join('bagian_upk', 'bagian_upk.id_bagian = daftar_asset.id_bagian', 'left');
    //     $this->db->group_start()
    //         // ===============================
    //         // STATUS = 1 (PENAMBAHAN)
    //         // ===============================
    //         ->group_start()
    //         ->where('daftar_asset.status', 1)
    //         ->group_start()
    //         // logika lama
    //         ->where('penyusutan.tahun <', 2024)
    //         // logika baru
    //         ->or_where('penyusutan.tahun <=', $tahun_lap)
    //         ->group_end()
    //         ->group_end()

    //         // ===============================
    //         // STATUS = 2 (PENGURANGAN)
    //         // ===============================
    //         ->or_group_start()
    //         ->where('daftar_asset.status', 2)
    //         ->where('penyusutan.tahun_persediaan <=', $tahun_lap)
    //         ->group_end()
    //         ->group_end();
    //     $this->db->where('daftar_asset.grand_id', 244);
    //     $this->db->order_by('bagian_upk.id_bagian', 'ASC');
    //     $this->db->order_by('id_no_per', 'ASC');
    //     $this->db->order_by('daftar_asset.id_asset', 'ASC');
    //     $this->db->order_by('tanggal', 'ASC');

    //     $query = $this->db->get();
    //     $results = $query->result();

    //     $tahun = $tahun_lap;
    //     if (empty($tahun)) {
    //         $tahun = date('Y');
    //     }

    //     // Inisialisasi variabel untuk menyimpan total
    //     $total_rupiah = 0;
    //     $total_nilai_buku = 0;
    //     $total_penambahan = 0;
    //     $total_pengurangan = 0;
    //     $total_akm_thn_lalu = 0;
    //     $total_nilai_buku_lalu = 0;
    //     $total_penyusutan = 0;
    //     $total_akm_thn_ini = 0;
    //     $total_nilai_buku_final = 0;

    //     // Daftar ID parent untuk bangunan
    //     $parent_ids_bangunan = [1569, 1907, 2104, 2255, 2671, 2676, 2678, 2680];

    //     foreach ($results as &$row) {
    //         $is_final_akm = false;
    //         if (
    //             $row->status_penyusutan == 2 &&
    //             (int)$tahun < (int)$row->tahun_persediaan
    //         ) {
    //             $row->nilai_buku = 0;
    //             $row->penambahan = 0;
    //             $row->pengurangan = 0;
    //             $row->akm_thn_lalu = 0;
    //             $row->nilai_buku_lalu = 0;
    //             $row->penambahan_penyusutan = 0;
    //             $row->akm_thn_ini = 0;
    //             $row->nilai_buku_final = 0;
    //             continue;
    //         }
    //         $umur_tahun = $tahun - $row->tahun;
    //         $nilai_buku_awal = $row->rupiah; // Nilai awal aset
    //         $akm_thn_ini = 0;                 // Akumulasi penyusutan tahun ini
    //         $nilai_buku_final = $nilai_buku_awal; // Nilai buku final untuk tahun berjalan

    //         if ($umur_tahun == 0) {
    //             // Kondisi untuk umur_tahun = 0
    //             $row->akm_thn_lalu = 0;
    //             $row->nilai_buku = 0;
    //             $row->penambahan_penyusutan = 0;
    //             $row->nilai_buku_lalu = 0;
    //             $row->akm_thn_ini = 0;
    //             $row->nilai_buku_final = $nilai_buku_awal;
    //         } else {
    //             $row->pengurangan = 0;
    //             $row->penambahan = 0;
    //             // Perhitungan bertahap untuk setiap tahun sejak tahun pertama
    //             for ($i = 1; $i <= $umur_tahun; $i++) {
    //                 if ($i == 1) {
    //                     // Tahun pertama
    //                     $akm_thn_lalu = 0;
    //                     $nilai_buku_lalu = $nilai_buku_awal;
    //                 } else {
    //                     // Update nilai buku dan akumulasi penyusutan dari tahun sebelumnya
    //                     $akm_thn_lalu = $akm_thn_ini;
    //                     $nilai_buku_lalu = $nilai_buku_final;
    //                 }

    //                 // Hitung penyusutan berdasarkan kategori aset
    //                 if (in_array($row->parent_id, $parent_ids_bangunan)) {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
    //                 } else {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
    //                 }

    //                 // Update akumulasi penyusutan dan nilai buku akhir
    //                 $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
    //                 $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

    //                 if ($i > $row->umur) {

    //                     // --- LOGIKA UNTUK TAHUN PELAPORAN SAAT INI ($tahun_lap) SAJA ---
    //                     // Pastikan finalisasi dan break hanya terjadi di iterasi terakhir (tahun laporan saat ini)
    //                     if ($i == $umur_tahun) {
    //                         // *** CATATAN: akm_thn_lalu dan nilai_buku_lalu sudah benar terisi dari tahun sebelumnya (2024)

    //                         $akm_thn_ini = $row->rupiah; // Set akumulasi awal
    //                         $nilai_buku_final = 1;      // Set nilai buku default
    //                         $penambahan_penyusutan = 0;
    //                         $row->penambahan = 0;
    //                         // Hapus baris yang menimpa akm_thn_lalu dan nilai_buku_lalu (sesuai perbaikan sebelumnya)

    //                         if ($row->status == 1) {
    //                             $nilai_buku_final = $row->rupiah - $akm_thn_ini;
    //                             if ($nilai_buku_final == 0 || $umur_tahun > $row->umur) {
    //                                 $nilai_buku_final = 1; // Nilai Buku Final = 1
    //                                 $akm_thn_ini = $akm_thn_ini - 1; // Akm Thn Ini = Rupiah - 1
    //                             }
    //                         } else {
    //                             $nilai_buku_final = -1;
    //                             $akm_thn_ini = $row->rupiah - $nilai_buku_final;
    //                         }

    //                         break; // Hentikan loop HANYA di tahun pelaporan
    //                     }

    //                     // --- LOGIKA UNTUK TAHUN HISTORIS SEBELUM TAHUN LAPORAN (i > umur TAPI i < umur_tahun) ---
    //                     // Jika aset sudah habis umur, tetapi belum mencapai tahun laporan, kunci nilainya.
    //                     $penambahan_penyusutan = 0;

    //                     // Kunci akumulasi dan nilai buku (asumsi nilai buku residu 1)
    //                     $akm_thn_ini = $row->rupiah - 1;
    //                     $nilai_buku_final = 1;

    //                     // TIDAK ADA 'break;' di sini. Loop akan terus berjalan hingga i mencapai umur_tahun.
    //                 }
    //             }

    //             // Set hasil akhir setelah loop tahun selesai
    //             $row->akm_thn_lalu = $akm_thn_lalu;
    //             $row->nilai_buku_lalu = $nilai_buku_lalu;
    //             $row->penambahan_penyusutan = $penambahan_penyusutan;
    //             $row->akm_thn_ini = $akm_thn_ini;
    //             $row->nilai_buku_final = $nilai_buku_final;
    //         }

    //         if ($row->status_penyusutan == 2) {
    //             $tahun_hapus = (int)$row->tahun_persediaan;
    //             $tahun_lap = (int)$tahun;
    //             $rupiah = $row->rupiah;

    //             $umur_tahun = $tahun - $row->tahun_persediaan;
    //             $umur_tahun_kurang = $tahun - $row->tahun;
    //             if ($umur_tahun == 0) {
    //                 $row->nilai_buku = 0;
    //                 $row->pengurangan = $row->rupiah * -1;
    //                 $row->nilai_buku_lalu = 0;
    //                 $row->akm_thn_lalu = 0;
    //                 $row->penambahan_penyusutan = 0;
    //                 $row->nilai_buku_final = $nilai_buku_final;
    //             } else {
    //                 for ($i = 1; $i <= $umur_tahun_kurang; $i++) {
    //                     $row->pengurangan = 0;
    //                     $row->penambahan = 0;
    //                     $akm_thn_lalu = $akm_thn_ini;
    //                     $nilai_buku_lalu = $nilai_buku_final;
    //                 }
    //                 if (in_array($row->parent_id, $parent_ids_bangunan)) {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
    //                 } else {
    //                     $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
    //                 }
    //                 $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
    //                 $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

    //                 if ($i > $row->umur) {
    //                     $row->pengurangan = 0;
    //                     $row->penambahan = 0;
    //                     $akm_thn_lalu = $row->rupiah;
    //                     $nilai_buku_lalu = 0;
    //                     $penambahan_penyusutan = 0;
    //                 }
    //             }
    //         }

    //         // Kondisi khusus untuk tanah
    //         if ($row->grand_id == 218) {
    //             $row->akm_thn_lalu = 0;
    //             $row->akm_thn_ini = 0;
    //             $row->nilai_buku_lalu = 0;
    //             $row->nilai_buku_final = $row->rupiah;
    //         }

    //         // ==========================================
    //         // STATUS = 2 (PENGURANGAN ASET) — FINAL
    //         // ==========================================
    //         // Tahun & umur
    //         $tahun_perolehan = (int)date('Y', strtotime($row->tanggal));
    //         $umur_aktual     = (int)$tahun - $tahun_perolehan - 1;

    //         // ================================
    //         // 1️⃣ TAHUN HAPUS (MUNCUL SEKALI)
    //         // ================================
    //         if (
    //             $row->status_penyusutan == 2 &&
    //             (int)$tahun == (int)$row->tahun_persediaan
    //         ) {
    //             $row->pengurangan       = (int)$row->rupiah * -1;
    //             $row->penambahan        = 0;
    //             $row->akm_thn_lalu      = 0;
    //             $row->nilai_buku_lalu   = 0;
    //         }

    //         // ================================
    //         // 2️⃣ FINAL LOCK (STATIS TOTAL)
    //         // ================================
    //         if (
    //             $row->status_penyusutan == 2 &&
    //             (int)$tahun >= (int)$row->tahun_persediaan &&
    //             $umur_aktual >= (int)$row->umur
    //         ) {
    //             // 🔒 SEMUA NILAI FINAL
    //             $rupiah_int = (int)$row->rupiah;

    //             $row->nilai_buku_final = -1;
    //             $is_final_akm = true;
    //             // $row->akm_thn_ini      = $rupiah_int + 1;

    //             // Carry forward WAJIB dari nilai FINAL
    //             $row->akm_thn_lalu     = $row->akm_thn_ini;
    //             $row->nilai_buku_lalu = $row->nilai_buku_final;

    //             // 🔒 STOP — TIDAK BOLEH ADA LOGIKA LAIN
    //         }

    //         // ==========================================
    //         // 🔒 RESET KHUSUS TAHUN HAPUS (ANTI WARIS)
    //         // ==========================================
    //         if (
    //             $row->status_penyusutan == 2 &&
    //             (int)$tahun === (int)$row->tahun_persediaan
    //         ) {
    //             $row->akm_thn_lalu     = 0;
    //             $row->nilai_buku_lalu = 0;
    //         }

    //         // ======================================================
    //         // 🔒 FINAL LOCK KHUSUS AUDIT (UMUR HABIS = TAHUN HAPUS)
    //         // Berlaku mulai 2024 dan seterusnya
    //         // ======================================================
    //         $tahun_final = (int)$row->tahun + (int)$row->umur;

    //         if (
    //             $row->status_penyusutan == 2 &&
    //             (int)$tahun >= 2024 &&
    //             (int)$tahun >= $tahun_final &&
    //             (int)$row->tahun_persediaan == $tahun_final
    //         ) {
    //             // 🔒 NILAI FINAL AUDIT
    //             $row->nilai_buku_final = -1;

    //             // AKM disesuaikan agar konsisten lintas tahun
    //             // $row->akm_thn_ini = (int)$row->rupiah - $row->nilai_buku_final;
    //             $is_final_akm = true;

    //             // WARIS WAJIB
    //             $row->akm_thn_lalu     = $row->akm_thn_ini;
    //             $row->nilai_buku_lalu = $row->nilai_buku_final;

    //             // STOP — TIDAK BOLEH ADA LOGIKA LAIN MENIMPA
    //         }

    //         // ======================================================
    //         // 🔒 LOCK ASET DIHAPUS DI TAHUN PEROLEHAN
    //         // Tahun lock: set final TH INI saja
    //         // Tahun setelahnya: nilai diwariskan
    //         // ======================================================

    //         $tahun_lock = (int)$row->tahun + (int)$row->umur - 1;

    //         if (
    //             (int)$row->status_penyusutan == 2 &&
    //             (int)$row->tahun_persediaan == (int)$row->tahun
    //         ) {

    //             // ===============================
    //             // 1️⃣ TAHUN LOCK (PERTAMA FINAL)
    //             // ===============================
    //             if ((int)$tahun == $tahun_lock) {

    //                 // set FINAL hanya untuk tahun ini
    //                 $row->nilai_buku_final = -1;
    //                 $row->akm_thn_ini      = (int)$row->rupiah - (-1);

    //                 // tahun lalu BIARKAN (ambil dari perhitungan normal / DB)
    //                 // $row->akm_thn_lalu
    //                 // $row->nilai_buku_lalu

    //                 // matikan pergerakan mulai tahun ini
    //                 $row->penambahan_penyusutan = 0;
    //                 $row->penambahan  = 0;
    //                 $row->pengurangan = 0;
    //             }

    //             // ===============================
    //             // 2️⃣ TAHUN SETELAH LOCK
    //             // ===============================
    //             if ((int)$tahun > $tahun_lock) {

    //                 // warisi nilai final
    //                 $row->nilai_buku_final = -1;
    //                 $row->akm_thn_ini      = (int)$row->rupiah - (-1);

    //                 $row->akm_thn_lalu     = $row->akm_thn_ini;
    //                 $row->nilai_buku_lalu = $row->nilai_buku_final;

    //                 // tidak boleh ada pergerakan
    //                 $row->penambahan_penyusutan = 0;
    //                 $row->penambahan  = 0;
    //                 $row->pengurangan = 0;
    //             }
    //         }

    //         // ====================================================== 
    //         // ini kode baru untuk status 1
    //         // ======================================================   
    //         $tahun_final = (int)$row->tahun + (int)$row->umur - 1;

    //         // STATUS = 1 — NORMAL (SEBELUM HABIS)
    //         if (
    //             (int)$row->status_penyusutan == 1 &&
    //             (int)$tahun < $tahun_final
    //         ) {
    //             // biarkan logika normal berjalan
    //         }

    //         // STATUS = 1 — TAHUN HABIS (TRANSISI)
    //         if (
    //             (int)$row->status_penyusutan == 1 &&
    //             (int)$tahun == $tahun_final
    //         ) {
    //             $row->nilai_buku_final = 1;

    //             // akm_thn_lalu BIARKAN (hasil tahun sebelumnya)
    //             $row->akm_thn_ini = (int)$row->rupiah - 1;

    //             $row->penambahan_penyusutan = 0;
    //             $row->penambahan  = 0;
    //             $row->pengurangan = 0;

    //             $is_final_akm = true;
    //             $is_tahun_habis = true;
    //         }

    //         // STATUS = 1 — SETELAH HABIS (STATIS)
    //         if (
    //             (int)$row->status_penyusutan == 1 &&
    //             (int)$tahun > $tahun_final
    //         ) {
    //             $row->nilai_buku_final = 1;

    //             $row->akm_thn_lalu     = (int)$row->rupiah - 1;
    //             $row->nilai_buku_lalu = 1;

    //             $row->penambahan_penyusutan = 0;
    //             $row->penambahan  = 0;
    //             $row->pengurangan = 0;

    //             $is_final_akm = true;
    //         }

    //         // ==================================================
    //         // 🔒 FINAL PENENTU AKM (SATU-SATUNYA TEMPAT)
    //         // ==================================================

    //         if ($is_final_akm) {

    //             $row->akm_thn_ini = (int)$row->rupiah - $row->nilai_buku_final;

    //             // ⚠️ JANGAN WARISKAN DI TAHUN HABIS
    //             if (empty($is_tahun_habis)) {
    //                 $row->akm_thn_lalu     = $row->akm_thn_ini;
    //                 $row->nilai_buku_lalu = $row->nilai_buku_final;
    //             }
    //         } else {

    //             $row->akm_thn_ini = $row->akm_thn_lalu + $row->penambahan_penyusutan;
    //         }

    //         // // ======================================================
    //         // // 🔒 FINAL LOCK STATUS = 1 (UMUR HABIS)
    //         // // Berlaku lintas tahun (ANTI WARIS AKM)
    //         // // ======================================================

    //         // $tahun_final = (int)$row->tahun + (int)$row->umur;

    //         // if (
    //         //     (int)$row->status_penyusutan == 1 &&
    //         //     (int)$tahun >= $tahun_final
    //         // ) {
    //         //     // kunci nilai final
    //         //     $row->nilai_buku_final = 1;

    //         //     // set flag final
    //         //     $is_final_akm = true;

    //         //     // WARIS NILAI FINAL
    //         //     $row->akm_thn_lalu     = (int)$row->rupiah - 1;
    //         //     $row->nilai_buku_lalu = 1;

    //         //     // MATIKAN PERGERAKAN
    //         //     $row->penambahan_penyusutan = 0;
    //         //     $row->penambahan  = 0;
    //         //     $row->pengurangan = 0;
    //         // }

    //         // // ==================================================
    //         // // 🔒 FINAL PENENTU AKM (SATU-SATUNYA TEMPAT)
    //         // // ==================================================
    //         // if ($is_final_akm) {

    //         //     // FINAL LOCK — STATUS 2 SAJA
    //         //     $row->akm_thn_ini = (int)$row->rupiah - $row->nilai_buku_final;

    //         //     // WARIS KE TAHUN BERIKUTNYA
    //         //     $row->akm_thn_lalu     = $row->akm_thn_ini;
    //         //     $row->nilai_buku_lalu = $row->nilai_buku_final;
    //         // } else {

    //         //     // NORMAL — STATUS 1 & STATUS 2
    //         //     $row->akm_thn_ini = $row->akm_thn_lalu + $row->penambahan_penyusutan;
    //         // }


    //         // Akumulasi total dari setiap kolom
    //         $total_rupiah += $row->rupiah;
    //         $total_nilai_buku += $row->nilai_buku;
    //         $total_penambahan += $row->penambahan;
    //         $total_pengurangan += $row->pengurangan;
    //         $total_akm_thn_lalu += $row->akm_thn_lalu;
    //         $total_nilai_buku_lalu += $row->nilai_buku_lalu;
    //         $total_penyusutan += $row->penambahan_penyusutan;
    //         $total_akm_thn_ini += $row->akm_thn_ini;
    //         $total_nilai_buku_final += $row->nilai_buku_final;
    //     }

    //     // Return data beserta total
    //     return [
    //         'results' => $results,
    //         'total_peralatan' => [
    //             'total_rupiah' => $total_rupiah,
    //             'total_nilai_buku' => $total_nilai_buku,
    //             'total_penambahan' => $total_penambahan,
    //             'total_pengurangan' => $total_pengurangan,
    //             'total_akm_thn_lalu' => $total_akm_thn_lalu,
    //             'total_nilai_buku_lalu' => $total_nilai_buku_lalu,
    //             'total_penyusutan' => $total_penyusutan,
    //             'total_akm_thn_ini' => $total_akm_thn_ini,
    //             'total_nilai_buku_final' => $total_nilai_buku_final
    //         ]
    //     ];
    // }

    public function get_peralatan($tahun_lap)
    {
        $this->db->select('
        penyusutan.*, 
        daftar_asset.*, 
        no_per.*, 
        bagian_upk.*, 
        daftar_asset.status AS status_penyusutan');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->join('bagian_upk', 'bagian_upk.id_bagian = daftar_asset.id_bagian', 'left');
        $this->db->group_start()
            // ===============================
            // STATUS = 1 (PENAMBAHAN)
            // ===============================
            ->group_start()
            ->where('daftar_asset.status', 1)
            ->group_start()
            // logika lama
            ->where('penyusutan.tahun <', 2024)
            // logika baru
            ->or_where('penyusutan.tahun <=', $tahun_lap)
            ->group_end()
            ->group_end()

            // ===============================
            // STATUS = 2 (PENGURANGAN)
            // ===============================
            ->or_group_start()
            ->where('daftar_asset.status', 2)
            ->where('penyusutan.tahun_persediaan <=', $tahun_lap)
            ->group_end()
            ->group_end();
        $this->db->where('daftar_asset.grand_id', 244);
        // $this->db->where('daftar_asset.parent_id', 2671);
        $this->db->order_by('bagian_upk.id_bagian', 'ASC');
        $this->db->order_by('id_no_per', 'ASC');
        $this->db->order_by('daftar_asset.id_asset', 'ASC');
        $this->db->order_by('tanggal', 'ASC');

        $query = $this->db->get();
        $results = $query->result();

        $tahun = $tahun_lap;
        if (empty($tahun)) {
            $tahun = date('Y');
        }

        // Inisialisasi variabel untuk menyimpan total
        $total_rupiah = 0;
        $total_nilai_buku = 0;
        $total_penambahan = 0;
        $total_pengurangan = 0;
        $total_akm_thn_lalu = 0;
        $total_nilai_buku_lalu = 0;
        $total_penyusutan = 0;
        $total_akm_thn_ini = 0;
        $total_nilai_buku_final = 0;

        // Daftar ID parent untuk bangunan
        $parent_ids_bangunan = [1569, 1907, 2104, 2255, 2671, 2676, 2678, 2680];

        foreach ($results as &$row) {
            $is_final_akm = false;
            if (
                $row->status_penyusutan == 2 &&
                (int)$tahun < (int)$row->tahun_persediaan
            ) {
                $row->nilai_buku = 0;
                $row->penambahan = 0;
                $row->pengurangan = 0;
                $row->akm_thn_lalu = 0;
                $row->nilai_buku_lalu = 0;
                $row->penambahan_penyusutan = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_final = 0;
                continue;
            }
            $umur_tahun = $tahun - $row->tahun;
            $nilai_buku_awal = $row->rupiah; // Nilai awal aset
            $akm_thn_ini = 0;                 // Akumulasi penyusutan tahun ini
            $nilai_buku_final = $nilai_buku_awal; // Nilai buku final untuk tahun berjalan

            if ($umur_tahun == 0) {
                // Kondisi untuk umur_tahun = 0
                $row->akm_thn_lalu = 0;
                $row->nilai_buku = 0;
                $row->penambahan_penyusutan = 0;
                $row->nilai_buku_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_final = $nilai_buku_awal;
            } else {
                $row->pengurangan = 0;
                $row->penambahan = 0;
                // Perhitungan bertahap untuk setiap tahun sejak tahun pertama
                for ($i = 1; $i <= $umur_tahun; $i++) {
                    if ($i == 1) {
                        // Tahun pertama
                        $akm_thn_lalu = 0;
                        $nilai_buku_lalu = $nilai_buku_awal;
                    } else {
                        // Update nilai buku dan akumulasi penyusutan dari tahun sebelumnya
                        $akm_thn_lalu = $akm_thn_ini;
                        $nilai_buku_lalu = $nilai_buku_final;
                    }

                    // Hitung penyusutan berdasarkan kategori aset
                    if (in_array($row->parent_id, $parent_ids_bangunan)) {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
                    } else {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
                    }

                    // Update akumulasi penyusutan dan nilai buku akhir
                    $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
                    $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

                    // Jika umur_tahun sudah mencapai umur aset, set nilai buku final menjadi 0
                    if ($i > $row->umur) {

                        // --- LOGIKA UNTUK TAHUN PELAPORAN SAAT INI ($tahun_lap) SAJA ---
                        // Pastikan finalisasi dan break hanya terjadi di iterasi terakhir (tahun laporan saat ini)
                        if ($i == $umur_tahun) {
                            // *** CATATAN: akm_thn_lalu dan nilai_buku_lalu sudah benar terisi dari tahun sebelumnya (2024)

                            $akm_thn_ini = $row->rupiah; // Set akumulasi awal
                            $nilai_buku_final = 1;      // Set nilai buku default
                            $penambahan_penyusutan = 0;
                            $row->penambahan = 0;
                            // Hapus baris yang menimpa akm_thn_lalu dan nilai_buku_lalu (sesuai perbaikan sebelumnya)

                            if ($row->status == 1) {
                                $nilai_buku_final = $row->rupiah - $akm_thn_ini;
                                if ($nilai_buku_final == 0 || $umur_tahun > $row->umur) {
                                    $nilai_buku_final = 1; // Nilai Buku Final = 1
                                    $akm_thn_ini = $akm_thn_ini - 1; // Akm Thn Ini = Rupiah - 1
                                }
                            } else {
                                $nilai_buku_final = -1;
                                $akm_thn_ini = $row->rupiah - $nilai_buku_final;
                            }

                            break; // Hentikan loop HANYA di tahun pelaporan
                        }

                        // --- LOGIKA UNTUK TAHUN HISTORIS SEBELUM TAHUN LAPORAN (i > umur TAPI i < umur_tahun) ---
                        // Jika aset sudah habis umur, tetapi belum mencapai tahun laporan, kunci nilainya.
                        $penambahan_penyusutan = 0;

                        // Kunci akumulasi dan nilai buku (asumsi nilai buku residu 1)
                        $akm_thn_ini = $row->rupiah - 1;
                        $nilai_buku_final = 1;

                        // TIDAK ADA 'break;' di sini. Loop akan terus berjalan hingga i mencapai umur_tahun.
                    }
                }

                // Set hasil akhir setelah loop tahun selesai
                $row->akm_thn_lalu = $akm_thn_lalu;
                $row->nilai_buku_lalu = $nilai_buku_lalu;
                $row->penambahan_penyusutan = $penambahan_penyusutan;
                $row->akm_thn_ini = $akm_thn_ini;
                $row->nilai_buku_final = $nilai_buku_final;
            }

            // if ($row->status_penyusutan == 2) {
            //     $tahun_hapus = (int)$row->tahun_persediaan;
            //     $tahun_lap = (int)$tahun;
            //     $rupiah = $row->rupiah;

            //     $umur_tahun = $tahun - $row->tahun_persediaan;
            //     $umur_tahun_kurang = $tahun - $row->tahun;
            //     if ($umur_tahun == 0) {
            //         $row->nilai_buku = 0;
            //         $row->pengurangan = $row->rupiah * -1;
            //         $row->nilai_buku_lalu = 0;
            //         $row->akm_thn_lalu = 0;
            //         $row->penambahan_penyusutan = 0;
            //         $row->nilai_buku_final = $nilai_buku_final;
            //     } else {
            //         for ($i = 1; $i <= $umur_tahun_kurang; $i++) {
            //             $row->pengurangan = 0;
            //             $row->penambahan = 0;
            //             $akm_thn_lalu = $akm_thn_ini;
            //             $nilai_buku_lalu = $nilai_buku_final;
            //         }
            //         if (in_array($row->parent_id, $parent_ids_bangunan)) {
            //             $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
            //         } else {
            //             $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
            //         }
            //         $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
            //         $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

            //         if ($i > $row->umur) {
            //             $row->pengurangan = 0;
            //             $row->penambahan = 0;
            //             $akm_thn_lalu = $row->rupiah;
            //             $nilai_buku_lalu = 0;
            //             $penambahan_penyusutan = 0;
            //         }
            //     }
            // }

            if ($row->status_penyusutan == 2) {
                $umur_tahun = $tahun - $row->tahun_persediaan;
                $umur_tahun_kurang = $tahun - $row->tahun;
                if ($umur_tahun == 0) {
                    $row->nilai_buku = 0;
                    $row->pengurangan = $row->rupiah * -1;
                    $row->nilai_buku_lalu = 0;
                    $row->akm_thn_lalu = 0;
                    $row->penambahan_penyusutan = 0;
                    $row->nilai_buku_final = $nilai_buku_final;
                } else {
                    for ($i = 1; $i <= $umur_tahun_kurang; $i++) {
                        $row->pengurangan = 0;
                        $row->penambahan = 0;
                        $akm_thn_lalu = $akm_thn_ini;
                        $nilai_buku_lalu = $nilai_buku_final;
                    }
                    if (in_array($row->parent_id, $parent_ids_bangunan)) {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
                    } else {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
                    }
                    $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
                    $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

                    if ($i > $row->umur) {
                        $row->pengurangan = 0;
                        $row->penambahan = 0;
                        $akm_thn_lalu = $row->rupiah;
                        $nilai_buku_lalu = 0;
                        $penambahan_penyusutan = 0;
                    }
                }
            }

            // ======================================================
            // 🔒 FINAL LOCK PERMANEN – STATUS = 2 (PENGURANGAN)
            // 🔒 untuk pengurangan yang sudah habis umurnya
            // ======================================================
            if ($row->status_penyusutan == 2) {

                $tahun_lap = (int)$tahun;
                $tahun_hapus = (int)$row->tahun_persediaan;
                $rupiah = (int)$row->rupiah; // NILAI MINUS
                $akm_final = $rupiah + 1;    // contoh: -10.866.999

                // -----------------------------
                // 1️⃣ SEBELUM TAHUN PENGURANGAN
                // -----------------------------
                if ($tahun_lap < $tahun_hapus) {

                    $row->akm_thn_lalu = 0;
                    $row->akm_thn_ini = 0;
                    $row->nilai_buku_lalu = 0;
                    $row->nilai_buku_final = 0;
                    $row->penambahan_penyusutan = 0;
                    $row->pengurangan = 0;
                }

                // -----------------------------
                // 2️⃣ TAHUN PENGURANGAN (MUNCUL SEKALI)
                // -----------------------------
                if ($tahun_lap == $tahun_hapus) {

                    $row->akm_thn_lalu = 0;
                    $row->akm_thn_ini = 0;
                    $row->nilai_buku_lalu = 0;
                    $row->nilai_buku_final = 0;
                    // $row->penambahan_penyusutan = 0;
                    // 🔴 INI YANG HILANG SEBELUMNYA
                    $row->pengurangan = $rupiah * -1; // contoh: -10.867.000
                }

                // -----------------------------
                // 3️⃣ TAHUN SETELAH PENGURANGAN (LOCK)
                // -----------------------------
                if ($tahun_lap == $tahun_hapus + 1) {

                    $row->akm_thn_lalu = 0;
                    $row->akm_thn_ini = $akm_final;
                    $row->nilai_buku_lalu = 0;
                    $row->nilai_buku_final = -1;
                    // $row->penambahan_penyusutan = 0;
                    $row->pengurangan = 0;
                }

                if ($tahun_lap > $tahun_hapus + 1) {

                    $row->akm_thn_lalu = $akm_final;
                    $row->akm_thn_ini = $akm_final;
                    $row->nilai_buku_lalu = -1;
                    $row->nilai_buku_final = -1;
                    $row->penambahan_penyusutan = 0;
                    $row->pengurangan = 0;
                }
            }

            // Kondisi khusus untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = 0;
                $row->nilai_buku_final = $row->rupiah;
            }

            // // ==========================================
            // // STATUS = 2 (PENGURANGAN ASET) — FINAL
            // // ==========================================

            // // Tahun & umur
            // $tahun_perolehan = (int)date('Y', strtotime($row->tanggal));
            // $umur_aktual     = (int)$tahun - $tahun_perolehan - 1;

            // // ================================
            // // 1️⃣ TAHUN HAPUS (MUNCUL SEKALI)
            // // ================================
            // if (
            //     $row->status_penyusutan == 2 &&
            //     (int)$tahun == (int)$row->tahun_persediaan
            // ) {
            //     $row->pengurangan       = (int)$row->rupiah * -1;
            //     $row->penambahan        = 0;
            //     $row->akm_thn_lalu      = 0;
            //     $row->nilai_buku_lalu   = 0;
            // }

            // // ================================
            // // 2️⃣ FINAL LOCK (STATIS TOTAL)
            // // ================================
            // if (
            //     $row->status_penyusutan == 2 &&
            //     (int)$tahun >= (int)$row->tahun_persediaan &&
            //     $umur_aktual >= (int)$row->umur
            // ) {
            //     // 🔒 SEMUA NILAI FINAL
            //     $rupiah_int = (int)$row->rupiah;

            //     $row->nilai_buku_final = -1;
            //     // $row->akm_thn_ini      = $rupiah_int + 1;
            //     $is_final_akm = true;

            //     // Carry forward WAJIB dari nilai FINAL
            //     $row->akm_thn_lalu     = $row->akm_thn_ini;
            //     $row->nilai_buku_lalu = $row->nilai_buku_final;

            //     // 🔒 STOP — TIDAK BOLEH ADA LOGIKA LAIN
            // }

            // // ==========================================
            // // 🔒 RESET KHUSUS TAHUN HAPUS (ANTI WARIS)
            // // ==========================================
            // if (
            //     $row->status_penyusutan == 2 &&
            //     (int)$tahun === (int)$row->tahun_persediaan
            // ) {
            //     $row->akm_thn_lalu     = 0;
            //     $row->nilai_buku_lalu = 0;
            // }

            // // ======================================================
            // // 🔒 FINAL LOCK KHUSUS AUDIT (UMUR HABIS = TAHUN HAPUS)
            // // Berlaku mulai 2024 dan seterusnya
            // // ======================================================
            // $tahun_final = (int)$row->tahun + (int)$row->umur;

            // if (
            //     $row->status_penyusutan == 2 &&
            //     (int)$tahun >= 2024 &&
            //     (int)$tahun >= $tahun_final &&
            //     (int)$row->tahun_persediaan == $tahun_final
            // ) {
            //     // 🔒 NILAI FINAL AUDIT
            //     $row->nilai_buku_final = -1;

            //     // AKM disesuaikan agar konsisten lintas tahun
            //     // $row->akm_thn_ini = (int)$row->rupiah - $row->nilai_buku_final;
            //     $is_final_akm = true;

            //     // WARIS WAJIB
            //     $row->akm_thn_lalu     = $row->akm_thn_ini;
            //     $row->nilai_buku_lalu = $row->nilai_buku_final;

            //     // STOP — TIDAK BOLEH ADA LOGIKA LAIN MENIMPA
            // }

            // // ======================================================
            // // 🔒 LOCK ASET DIHAPUS DI TAHUN PEROLEHAN
            // // Tahun lock: set final TH INI saja
            // // Tahun setelahnya: nilai diwariskan
            // // ======================================================

            // $tahun_lock = (int)$row->tahun + (int)$row->umur - 1;

            // if (
            //     (int)$row->status_penyusutan == 2 &&
            //     (int)$row->tahun_persediaan == (int)$row->tahun
            // ) {

            //     // ===============================
            //     // 1️⃣ TAHUN LOCK (PERTAMA FINAL)
            //     // ===============================
            //     if ((int)$tahun == $tahun_lock) {

            //         // set FINAL hanya untuk tahun ini
            //         $row->nilai_buku_final = -1;
            //         $row->akm_thn_ini      = (int)$row->rupiah - (-1);

            //         // tahun lalu BIARKAN (ambil dari perhitungan normal / DB)
            //         // $row->akm_thn_lalu
            //         // $row->nilai_buku_lalu

            //         // matikan pergerakan mulai tahun ini
            //         $row->penambahan_penyusutan = 0;
            //         $row->penambahan  = 0;
            //         $row->pengurangan = 0;
            //     }

            //     // ===============================
            //     // 2️⃣ TAHUN SETELAH LOCK
            //     // ===============================
            //     if ((int)$tahun > $tahun_lock) {

            //         // warisi nilai final
            //         $row->nilai_buku_final = -1;
            //         $row->akm_thn_ini      = (int)$row->rupiah - (-1);

            //         $row->akm_thn_lalu     = $row->akm_thn_ini;
            //         $row->nilai_buku_lalu = $row->nilai_buku_final;

            //         // tidak boleh ada pergerakan
            //         $row->penambahan_penyusutan = 0;
            //         $row->penambahan  = 0;
            //         $row->pengurangan = 0;
            //     }
            // }

            // // ====================================================== 
            // // ini kode baru untuk status 1
            // // ======================================================   
            // $tahun_final = (int)$row->tahun + (int)$row->umur;

            // // STATUS = 1 — NORMAL (SEBELUM HABIS)
            // if (
            //     (int)$row->status_penyusutan == 1 &&
            //     (int)$tahun < $tahun_final
            // ) {
            //     // biarkan logika normal berjalan
            // }

            // // STATUS = 1 — TAHUN HABIS (TRANSISI)
            // if (
            //     (int)$row->status_penyusutan == 1 &&
            //     (int)$tahun == $tahun_final
            // ) {
            //     $row->nilai_buku_final = 1;

            //     // akm_thn_lalu BIARKAN (hasil tahun sebelumnya)
            //     $row->akm_thn_ini = (int)$row->rupiah - 1;

            //     $row->penambahan_penyusutan = 0;
            //     $row->penambahan  = 0;
            //     $row->pengurangan = 0;

            //     $is_final_akm = true;
            //     $is_tahun_habis = true;
            // }

            // // STATUS = 1 — SETELAH HABIS (STATIS)
            // if (
            //     (int)$row->status_penyusutan == 1 &&
            //     (int)$tahun > $tahun_final
            // ) {
            //     $row->nilai_buku_final = 1;

            //     $row->akm_thn_lalu     = (int)$row->rupiah - 1;
            //     $row->nilai_buku_lalu = 1;

            //     $row->penambahan_penyusutan = 0;
            //     $row->penambahan  = 0;
            //     $row->pengurangan = 0;

            //     $is_final_akm = true;
            // }

            // // ==================================================
            // // 🔒 FINAL PENENTU AKM (SATU-SATUNYA TEMPAT)
            // // ==================================================

            // if ($is_final_akm) {

            //     $row->akm_thn_ini = (int)$row->rupiah - $row->nilai_buku_final;

            //     // ⚠️ JANGAN WARISKAN DI TAHUN HABIS
            //     if (empty($is_tahun_habis)) {
            //         $row->akm_thn_lalu     = $row->akm_thn_ini;
            //         $row->nilai_buku_lalu = $row->nilai_buku_final;
            //     }
            // } else {

            //     $row->akm_thn_ini = $row->akm_thn_lalu + $row->penambahan_penyusutan;
            // }

            // ======================================================
            // 🔧 SINKRONISASI AKM (HANYA >= 2025, STATUS = 1)
            // AKM Thn Ini = AKM Thn Lalu + Penyusutan
            // ======================================================
            if (
                (int)$tahun == 2025 &&
                (int)$row->status_penyusutan == 1
            ) {
                $expected_akm = $row->akm_thn_lalu + $row->penambahan_penyusutan;

                // Jika tidak sinkron → perbaiki
                if ($row->akm_thn_ini != $expected_akm) {
                    $row->akm_thn_ini = $expected_akm;
                    $row->nilai_buku_final = $row->rupiah - $row->akm_thn_ini;
                }
            }

            if (
                (int)$tahun >= 2026 &&
                (int)$row->status_penyusutan == 1
            ) {
                $expected_akm = $row->akm_thn_lalu + $row->penambahan_penyusutan;
                if ($row->akm_thn_ini != $expected_akm) {
                    $row->akm_thn_ini = $expected_akm;
                    $row->nilai_buku_final = $row->rupiah - $row->akm_thn_ini;
                }
            }



            // Akumulasi total dari setiap kolom
            $total_rupiah += $row->rupiah;
            $total_nilai_buku += $row->nilai_buku;
            $total_penambahan += $row->penambahan;
            $total_pengurangan += $row->pengurangan;
            $total_akm_thn_lalu += $row->akm_thn_lalu;
            $total_nilai_buku_lalu += $row->nilai_buku_lalu;
            $total_penyusutan += $row->penambahan_penyusutan;
            $total_akm_thn_ini += $row->akm_thn_ini;
            $total_nilai_buku_final += $row->nilai_buku_final;
        }

        // Return data beserta total
        return [
            'results' => $results,
            'total_peralatan' => [
                'total_rupiah' => $total_rupiah,
                'total_nilai_buku' => $total_nilai_buku,
                'total_penambahan' => $total_penambahan,
                'total_pengurangan' => $total_pengurangan,
                'total_akm_thn_lalu' => $total_akm_thn_lalu,
                'total_nilai_buku_lalu' => $total_nilai_buku_lalu,
                'total_penyusutan' => $total_penyusutan,
                'total_akm_thn_ini' => $total_akm_thn_ini,
                'total_nilai_buku_final' => $total_nilai_buku_final
            ]
        ];
    }

    public function get_peralatan_amdk($tahun_lap)
    {
        $this->db->select('
        penyusutan.*, 
        daftar_asset.*, 
        no_per.*, 
        bagian_upk.*,
        daftar_asset.status AS status_penyusutan');

        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->join('bagian_upk', 'bagian_upk.id_bagian = daftar_asset.id_bagian', 'left');
        $this->db->group_start()
            // ===============================
            // STATUS = 1 (PENAMBAHAN)
            // ===============================
            ->group_start()
            ->where('daftar_asset.status', 1)
            ->group_start()
            // logika lama
            ->where('penyusutan.tahun <', 2024)
            // logika baru
            ->or_where('penyusutan.tahun <=', $tahun_lap)
            ->group_end()
            ->group_end()

            // ===============================
            // STATUS = 2 (PENGURANGAN)
            // ===============================
            ->or_group_start()
            ->where('daftar_asset.status', 2)
            ->where('penyusutan.tahun_persediaan <=', $tahun_lap)
            ->group_end()
            ->group_end();
        $this->db->where('daftar_asset.grand_id', 244);
        $this->db->where('daftar_asset.id_bagian', 23);
        $this->db->order_by('bagian_upk.id_bagian', 'ASC');
        $this->db->order_by('id_no_per', 'ASC');
        $this->db->order_by('daftar_asset.id_asset', 'ASC');
        $this->db->order_by('tanggal', 'ASC');

        $query = $this->db->get();
        $results = $query->result();

        $tahun = $tahun_lap;
        if (empty($tahun)) {
            $tahun = date('Y');
        }

        // Inisialisasi variabel untuk menyimpan total
        $total_rupiah = 0;
        $total_nilai_buku = 0;
        $total_penambahan = 0;
        $total_pengurangan = 0;
        $total_akm_thn_lalu = 0;
        $total_nilai_buku_lalu = 0;
        $total_penyusutan = 0;
        $total_akm_thn_ini = 0;
        $total_nilai_buku_final = 0;

        // Daftar ID parent untuk bangunan
        $parent_ids_bangunan = [1569, 1907, 2104, 2255, 2671, 2676, 2678, 2680];

        foreach ($results as &$row) {
            if (
                $row->status_penyusutan == 2 &&
                (int)$tahun < (int)$row->tahun_persediaan
            ) {
                $row->nilai_buku = 0;
                $row->penambahan = 0;
                $row->pengurangan = 0;
                $row->akm_thn_lalu = 0;
                $row->nilai_buku_lalu = 0;
                $row->penambahan_penyusutan = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_final = 0;
                continue;
            }
            $umur_tahun = $tahun - $row->tahun;
            $nilai_buku_awal = $row->rupiah; // Nilai awal aset
            $akm_thn_ini = 0;                 // Akumulasi penyusutan tahun ini
            $nilai_buku_final = $nilai_buku_awal; // Nilai buku final untuk tahun berjalan

            if ($umur_tahun == 0) {
                // Kondisi untuk umur_tahun = 0
                $row->akm_thn_lalu = 0;
                $row->nilai_buku = 0;
                $row->penambahan_penyusutan = 0;
                $row->nilai_buku_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_final = $nilai_buku_awal;
            } else {
                $row->pengurangan = 0;
                $row->penambahan = 0;
                // Perhitungan bertahap untuk setiap tahun sejak tahun pertama
                for ($i = 1; $i <= $umur_tahun; $i++) {
                    if ($i == 1) {
                        // Tahun pertama
                        $akm_thn_lalu = 0;
                        $nilai_buku_lalu = $nilai_buku_awal;
                    } else {
                        // Update nilai buku dan akumulasi penyusutan dari tahun sebelumnya
                        $akm_thn_lalu = $akm_thn_ini;
                        $nilai_buku_lalu = $nilai_buku_final;
                    }

                    // Hitung penyusutan berdasarkan kategori aset
                    if (in_array($row->parent_id, $parent_ids_bangunan)) {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
                    } else {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
                    }

                    // Update akumulasi penyusutan dan nilai buku akhir
                    $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
                    $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

                    if ($i > $row->umur) {

                        // --- LOGIKA UNTUK TAHUN PELAPORAN SAAT INI ($tahun_lap) SAJA ---
                        // Pastikan finalisasi dan break hanya terjadi di iterasi terakhir (tahun laporan saat ini)
                        if ($i == $umur_tahun) {
                            // *** CATATAN: akm_thn_lalu dan nilai_buku_lalu sudah benar terisi dari tahun sebelumnya (2024)

                            $akm_thn_ini = $row->rupiah; // Set akumulasi awal
                            $nilai_buku_final = 1;      // Set nilai buku default
                            $penambahan_penyusutan = 0;
                            $row->penambahan = 0;
                            // Hapus baris yang menimpa akm_thn_lalu dan nilai_buku_lalu (sesuai perbaikan sebelumnya)

                            if ($row->status == 1) {
                                $nilai_buku_final = $row->rupiah - $akm_thn_ini;
                                if ($nilai_buku_final == 0 || $umur_tahun > $row->umur) {
                                    $nilai_buku_final = 1; // Nilai Buku Final = 1
                                    $akm_thn_ini = $akm_thn_ini - 1; // Akm Thn Ini = Rupiah - 1
                                }
                            } else {
                                $akm_thn_ini = $akm_thn_ini + 1;
                                $nilai_buku_final = -1;
                            }

                            break; // Hentikan loop HANYA di tahun pelaporan
                        }

                        // --- LOGIKA UNTUK TAHUN HISTORIS SEBELUM TAHUN LAPORAN (i > umur TAPI i < umur_tahun) ---
                        // Jika aset sudah habis umur, tetapi belum mencapai tahun laporan, kunci nilainya.
                        $penambahan_penyusutan = 0;

                        // Kunci akumulasi dan nilai buku (asumsi nilai buku residu 1)
                        $akm_thn_ini = $row->rupiah - 1;
                        $nilai_buku_final = 1;

                        // TIDAK ADA 'break;' di sini. Loop akan terus berjalan hingga i mencapai umur_tahun.
                    }
                }

                // Set hasil akhir setelah loop tahun selesai
                $row->akm_thn_lalu = $akm_thn_lalu;
                $row->nilai_buku_lalu = $nilai_buku_lalu;
                $row->penambahan_penyusutan = $penambahan_penyusutan;
                $row->akm_thn_ini = $akm_thn_ini;
                $row->nilai_buku_final = $nilai_buku_final;
            }

            if ($row->status_penyusutan == 2) {
                $tahun_hapus = (int)$row->tahun_persediaan;
                $tahun_lap = (int)$tahun;
                $rupiah = $row->rupiah;

                $umur_tahun = $tahun - $row->tahun_persediaan;
                $umur_tahun_kurang = $tahun - $row->tahun;
                if ($umur_tahun == 0) {
                    $row->nilai_buku = 0;
                    $row->pengurangan = $row->rupiah * -1;
                    $row->nilai_buku_lalu = 0;
                    $row->akm_thn_lalu = 0;
                    $row->penambahan_penyusutan = 0;
                    $row->nilai_buku_final = $nilai_buku_final;
                } else {
                    for ($i = 1; $i <= $umur_tahun_kurang; $i++) {
                        $row->pengurangan = 0;
                        $row->penambahan = 0;
                        $akm_thn_lalu = $akm_thn_ini;
                        $nilai_buku_lalu = $nilai_buku_final;
                    }
                    if (in_array($row->parent_id, $parent_ids_bangunan)) {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
                    } else {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
                    }
                    $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
                    $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

                    if ($i > $row->umur) {
                        $row->pengurangan = 0;
                        $row->penambahan = 0;
                        $akm_thn_lalu = $row->rupiah;
                        $nilai_buku_lalu = 0;
                        $penambahan_penyusutan = 0;
                    }
                }
            }


            // Kondisi khusus untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = 0;
                $row->nilai_buku_final = $row->rupiah;
            }

            // ==========================================
            // STATUS = 2 (PENGURANGAN ASET) — FINAL
            // ==========================================
            // Tahun & umur
            $tahun_perolehan = (int)date('Y', strtotime($row->tanggal));
            $umur_aktual     = (int)$tahun - $tahun_perolehan - 1;

            // ================================
            // 1️⃣ TAHUN HAPUS (MUNCUL SEKALI)
            // ================================
            if (
                $row->status_penyusutan == 2 &&
                (int)$tahun == (int)$row->tahun_persediaan
            ) {
                $row->pengurangan       = (int)$row->rupiah * -1;
                $row->penambahan        = 0;
                $row->akm_thn_lalu      = 0;
                $row->nilai_buku_lalu   = 0;
            }

            // ================================
            // 2️⃣ FINAL LOCK (STATIS TOTAL)
            // ================================
            if (
                $row->status_penyusutan == 2 &&
                (int)$tahun >= (int)$row->tahun_persediaan &&
                $umur_aktual >= (int)$row->umur
            ) {
                // 🔒 SEMUA NILAI FINAL
                $rupiah_int = (int)$row->rupiah;

                $row->nilai_buku_final = -1;
                $row->akm_thn_ini      = $rupiah_int + 1;

                // Carry forward WAJIB dari nilai FINAL
                $row->akm_thn_lalu     = $row->akm_thn_ini;
                $row->nilai_buku_lalu = $row->nilai_buku_final;

                // 🔒 STOP — TIDAK BOLEH ADA LOGIKA LAIN
            }

            // ==========================================
            // 🔒 RESET KHUSUS TAHUN HAPUS (ANTI WARIS)
            // ==========================================
            if (
                $row->status_penyusutan == 2 &&
                (int)$tahun === (int)$row->tahun_persediaan
            ) {
                $row->akm_thn_lalu     = 0;
                $row->nilai_buku_lalu = 0;
            }

            // ======================================================
            // 🔒 FINAL LOCK KHUSUS AUDIT (UMUR HABIS = TAHUN HAPUS)
            // Berlaku mulai 2024 dan seterusnya
            // ======================================================
            $tahun_final = (int)$row->tahun + (int)$row->umur;

            if (
                $row->status_penyusutan == 2 &&
                (int)$tahun >= 2024 &&
                (int)$tahun >= $tahun_final &&
                (int)$row->tahun_persediaan == $tahun_final
            ) {
                // 🔒 NILAI FINAL AUDIT
                $row->nilai_buku_final = -1;

                // AKM disesuaikan agar konsisten lintas tahun
                $row->akm_thn_ini = (int)$row->rupiah - $row->nilai_buku_final;

                // WARIS WAJIB
                $row->akm_thn_lalu     = $row->akm_thn_ini;
                $row->nilai_buku_lalu = $row->nilai_buku_final;

                // STOP — TIDAK BOLEH ADA LOGIKA LAIN MENIMPA
            }

            // ======================================================
            // 🔒 LOCK ASET DIHAPUS DI TAHUN PEROLEHAN
            // Tahun lock: set final TH INI saja
            // Tahun setelahnya: nilai diwariskan
            // ======================================================

            $tahun_lock = (int)$row->tahun + (int)$row->umur - 1;

            if (
                (int)$row->status_penyusutan == 2 &&
                (int)$row->tahun_persediaan == (int)$row->tahun
            ) {

                // ===============================
                // 1️⃣ TAHUN LOCK (PERTAMA FINAL)
                // ===============================
                if ((int)$tahun == $tahun_lock) {

                    // set FINAL hanya untuk tahun ini
                    $row->nilai_buku_final = -1;
                    $row->akm_thn_ini      = (int)$row->rupiah - (-1);

                    // tahun lalu BIARKAN (ambil dari perhitungan normal / DB)
                    // $row->akm_thn_lalu
                    // $row->nilai_buku_lalu

                    // matikan pergerakan mulai tahun ini
                    $row->penambahan_penyusutan = 0;
                    $row->penambahan  = 0;
                    $row->pengurangan = 0;
                }

                // ===============================
                // 2️⃣ TAHUN SETELAH LOCK
                // ===============================
                if ((int)$tahun > $tahun_lock) {

                    // warisi nilai final
                    $row->nilai_buku_final = -1;
                    $row->akm_thn_ini      = (int)$row->rupiah - (-1);

                    $row->akm_thn_lalu     = $row->akm_thn_ini;
                    $row->nilai_buku_lalu = $row->nilai_buku_final;

                    // tidak boleh ada pergerakan
                    $row->penambahan_penyusutan = 0;
                    $row->penambahan  = 0;
                    $row->pengurangan = 0;
                }
            }

            // Akumulasi total dari setiap kolom
            $total_rupiah += $row->rupiah;
            $total_nilai_buku += $row->nilai_buku;
            $total_penambahan += $row->penambahan;
            $total_pengurangan += $row->pengurangan;
            $total_akm_thn_lalu += $row->akm_thn_lalu;
            $total_nilai_buku_lalu += $row->nilai_buku_lalu;
            $total_penyusutan += $row->penambahan_penyusutan;
            $total_akm_thn_ini += $row->akm_thn_ini;
            $total_nilai_buku_final += $row->nilai_buku_final;
        }

        // Return data beserta total
        return [
            'results' => $results,
            'total_peralatan_amdk' => [
                'total_rupiah' => $total_rupiah,
                'total_nilai_buku' => $total_nilai_buku,
                'total_penambahan' => $total_penambahan,
                'total_pengurangan' => $total_pengurangan,
                'total_akm_thn_lalu' => $total_akm_thn_lalu,
                'total_nilai_buku_lalu' => $total_nilai_buku_lalu,
                'total_penyusutan' => $total_penyusutan,
                'total_akm_thn_ini' => $total_akm_thn_ini,
                'total_nilai_buku_final' => $total_nilai_buku_final
            ]
        ];
    }
    public function get_peralatan_non_amdk($tahun_lap)
    {
        $this->db->select('
        penyusutan.*, 
        daftar_asset.*, 
        no_per.*, 
        bagian_upk.*,
        daftar_asset.status AS status_penyusutan');

        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->join('no_per', 'daftar_asset.id_no_per = no_per.id', 'left');
        $this->db->join('bagian_upk', 'bagian_upk.id_bagian = daftar_asset.id_bagian', 'left');
        $this->db->group_start()
            // ===============================
            // STATUS = 1 (PENAMBAHAN)
            // ===============================
            ->group_start()
            ->where('daftar_asset.status', 1)
            ->group_start()
            // logika lama
            ->where('penyusutan.tahun <', 2024)
            // logika baru
            ->or_where('penyusutan.tahun <=', $tahun_lap)
            ->group_end()
            ->group_end()

            // ===============================
            // STATUS = 2 (PENGURANGAN)
            // ===============================
            ->or_group_start()
            ->where('daftar_asset.status', 2)
            ->where('penyusutan.tahun_persediaan <=', $tahun_lap)
            ->group_end()
            ->group_end();
        $this->db->where('daftar_asset.grand_id', 244);
        $this->db->where('daftar_asset.id_bagian !=', 23);
        $this->db->order_by('bagian_upk.id_bagian', 'ASC');
        $this->db->order_by('id_no_per', 'ASC');
        $this->db->order_by('daftar_asset.id_asset', 'ASC');
        $this->db->order_by('tanggal', 'ASC');

        $query = $this->db->get();
        $results = $query->result();

        $tahun = $tahun_lap;
        if (empty($tahun)) {
            $tahun = date('Y');
        }

        // Inisialisasi variabel untuk menyimpan total
        $total_rupiah = 0;
        $total_nilai_buku = 0;
        $total_penambahan = 0;
        $total_pengurangan = 0;
        $total_akm_thn_lalu = 0;
        $total_nilai_buku_lalu = 0;
        $total_penyusutan = 0;
        $total_akm_thn_ini = 0;
        $total_nilai_buku_final = 0;

        // Daftar ID parent untuk bangunan
        $parent_ids_bangunan = [1569, 1907, 2104, 2255, 2671, 2676, 2678, 2680];

        foreach ($results as &$row) {
            if (
                $row->status_penyusutan == 2 &&
                (int)$tahun < (int)$row->tahun_persediaan
            ) {
                $row->nilai_buku = 0;
                $row->penambahan = 0;
                $row->pengurangan = 0;
                $row->akm_thn_lalu = 0;
                $row->nilai_buku_lalu = 0;
                $row->penambahan_penyusutan = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_final = 0;
                continue;
            }
            $umur_tahun = $tahun - $row->tahun;
            $nilai_buku_awal = $row->rupiah; // Nilai awal aset
            $akm_thn_ini = 0;                 // Akumulasi penyusutan tahun ini
            $nilai_buku_final = $nilai_buku_awal; // Nilai buku final untuk tahun berjalan

            if ($umur_tahun == 0) {
                // Kondisi untuk umur_tahun = 0
                $row->akm_thn_lalu = 0;
                $row->nilai_buku = 0;
                $row->penambahan_penyusutan = 0;
                $row->nilai_buku_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_final = $nilai_buku_awal;
            } else {
                $row->pengurangan = 0;
                $row->penambahan = 0;
                // Perhitungan bertahap untuk setiap tahun sejak tahun pertama
                for ($i = 1; $i <= $umur_tahun; $i++) {
                    if ($i == 1) {
                        // Tahun pertama
                        $akm_thn_lalu = 0;
                        $nilai_buku_lalu = $nilai_buku_awal;
                    } else {
                        // Update nilai buku dan akumulasi penyusutan dari tahun sebelumnya
                        $akm_thn_lalu = $akm_thn_ini;
                        $nilai_buku_lalu = $nilai_buku_final;
                    }

                    // Hitung penyusutan berdasarkan kategori aset
                    if (in_array($row->parent_id, $parent_ids_bangunan)) {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
                    } else {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
                    }

                    // Update akumulasi penyusutan dan nilai buku akhir
                    $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
                    $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

                    if ($i > $row->umur) {

                        // --- LOGIKA UNTUK TAHUN PELAPORAN SAAT INI ($tahun_lap) SAJA ---
                        // Pastikan finalisasi dan break hanya terjadi di iterasi terakhir (tahun laporan saat ini)
                        if ($i == $umur_tahun) {
                            // *** CATATAN: akm_thn_lalu dan nilai_buku_lalu sudah benar terisi dari tahun sebelumnya (2024)

                            $akm_thn_ini = $row->rupiah; // Set akumulasi awal
                            $nilai_buku_final = 1;      // Set nilai buku default
                            $penambahan_penyusutan = 0;
                            $row->penambahan = 0;
                            // Hapus baris yang menimpa akm_thn_lalu dan nilai_buku_lalu (sesuai perbaikan sebelumnya)

                            if ($row->status == 1) {
                                $nilai_buku_final = $row->rupiah - $akm_thn_ini;
                                if ($nilai_buku_final == 0 || $umur_tahun > $row->umur) {
                                    $nilai_buku_final = 1; // Nilai Buku Final = 1
                                    $akm_thn_ini = $akm_thn_ini - 1; // Akm Thn Ini = Rupiah - 1
                                }
                            } else {
                                $akm_thn_ini = $akm_thn_ini + 1;
                                $nilai_buku_final = -1;
                            }

                            break; // Hentikan loop HANYA di tahun pelaporan
                        }

                        // --- LOGIKA UNTUK TAHUN HISTORIS SEBELUM TAHUN LAPORAN (i > umur TAPI i < umur_tahun) ---
                        // Jika aset sudah habis umur, tetapi belum mencapai tahun laporan, kunci nilainya.
                        $penambahan_penyusutan = 0;

                        // Kunci akumulasi dan nilai buku (asumsi nilai buku residu 1)
                        $akm_thn_ini = $row->rupiah - 1;
                        $nilai_buku_final = 1;

                        // TIDAK ADA 'break;' di sini. Loop akan terus berjalan hingga i mencapai umur_tahun.
                    }
                }

                // Set hasil akhir setelah loop tahun selesai
                $row->akm_thn_lalu = $akm_thn_lalu;
                $row->nilai_buku_lalu = $nilai_buku_lalu;
                $row->penambahan_penyusutan = $penambahan_penyusutan;
                $row->akm_thn_ini = $akm_thn_ini;
                $row->nilai_buku_final = $nilai_buku_final;
            }

            if ($row->status_penyusutan == 2) {
                $tahun_hapus = (int)$row->tahun_persediaan;
                $tahun_lap = (int)$tahun;
                $rupiah = $row->rupiah;

                $umur_tahun = $tahun - $row->tahun_persediaan;
                $umur_tahun_kurang = $tahun - $row->tahun;
                if ($umur_tahun == 0) {
                    $row->nilai_buku = 0;
                    $row->pengurangan = $row->rupiah * -1;
                    $row->nilai_buku_lalu = 0;
                    $row->akm_thn_lalu = 0;
                    $row->penambahan_penyusutan = 0;
                    $row->nilai_buku_final = $nilai_buku_final;
                } else {
                    for ($i = 1; $i <= $umur_tahun_kurang; $i++) {
                        $row->pengurangan = 0;
                        $row->penambahan = 0;
                        $akm_thn_lalu = $akm_thn_ini;
                        $nilai_buku_lalu = $nilai_buku_final;
                    }
                    if (in_array($row->parent_id, $parent_ids_bangunan)) {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
                    } else {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
                    }
                    $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
                    $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

                    if ($i > $row->umur) {
                        $row->pengurangan = 0;
                        $row->penambahan = 0;
                        $akm_thn_lalu = $row->rupiah;
                        $nilai_buku_lalu = 0;
                        $penambahan_penyusutan = 0;
                    }
                }
            }


            // Kondisi khusus untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = 0;
                $row->nilai_buku_final = $row->rupiah;
            }

            // ==========================================
            // STATUS = 2 (PENGURANGAN ASET) — FINAL
            // ==========================================
            // Tahun & umur
            $tahun_perolehan = (int)date('Y', strtotime($row->tanggal));
            $umur_aktual     = (int)$tahun - $tahun_perolehan - 1;

            // ================================
            // 1️⃣ TAHUN HAPUS (MUNCUL SEKALI)
            // ================================
            if (
                $row->status_penyusutan == 2 &&
                (int)$tahun == (int)$row->tahun_persediaan
            ) {
                $row->pengurangan       = (int)$row->rupiah * -1;
                $row->penambahan        = 0;
                $row->akm_thn_lalu      = 0;
                $row->nilai_buku_lalu   = 0;
            }

            // ================================
            // 2️⃣ FINAL LOCK (STATIS TOTAL)
            // ================================
            if (
                $row->status_penyusutan == 2 &&
                (int)$tahun >= (int)$row->tahun_persediaan &&
                $umur_aktual >= (int)$row->umur
            ) {
                // 🔒 SEMUA NILAI FINAL
                $rupiah_int = (int)$row->rupiah;

                $row->nilai_buku_final = -1;
                $row->akm_thn_ini      = $rupiah_int + 1;

                // Carry forward WAJIB dari nilai FINAL
                $row->akm_thn_lalu     = $row->akm_thn_ini;
                $row->nilai_buku_lalu = $row->nilai_buku_final;

                // 🔒 STOP — TIDAK BOLEH ADA LOGIKA LAIN
            }

            // ==========================================
            // 🔒 RESET KHUSUS TAHUN HAPUS (ANTI WARIS)
            // ==========================================
            if (
                $row->status_penyusutan == 2 &&
                (int)$tahun === (int)$row->tahun_persediaan
            ) {
                $row->akm_thn_lalu     = 0;
                $row->nilai_buku_lalu = 0;
            }

            // ======================================================
            // 🔒 FINAL LOCK KHUSUS AUDIT (UMUR HABIS = TAHUN HAPUS)
            // Berlaku mulai 2024 dan seterusnya
            // ======================================================
            $tahun_final = (int)$row->tahun + (int)$row->umur;

            if (
                $row->status_penyusutan == 2 &&
                (int)$tahun >= 2024 &&
                (int)$tahun >= $tahun_final &&
                (int)$row->tahun_persediaan == $tahun_final
            ) {
                // 🔒 NILAI FINAL AUDIT
                $row->nilai_buku_final = -1;

                // AKM disesuaikan agar konsisten lintas tahun
                $row->akm_thn_ini = (int)$row->rupiah - $row->nilai_buku_final;

                // WARIS WAJIB
                $row->akm_thn_lalu     = $row->akm_thn_ini;
                $row->nilai_buku_lalu = $row->nilai_buku_final;

                // STOP — TIDAK BOLEH ADA LOGIKA LAIN MENIMPA
            }

            // ======================================================
            // 🔒 LOCK ASET DIHAPUS DI TAHUN PEROLEHAN
            // Tahun lock: set final TH INI saja
            // Tahun setelahnya: nilai diwariskan
            // ======================================================

            $tahun_lock = (int)$row->tahun + (int)$row->umur - 1;

            if (
                (int)$row->status_penyusutan == 2 &&
                (int)$row->tahun_persediaan == (int)$row->tahun
            ) {

                // ===============================
                // 1️⃣ TAHUN LOCK (PERTAMA FINAL)
                // ===============================
                if ((int)$tahun == $tahun_lock) {

                    // set FINAL hanya untuk tahun ini
                    $row->nilai_buku_final = -1;
                    $row->akm_thn_ini      = (int)$row->rupiah - (-1);

                    // tahun lalu BIARKAN (ambil dari perhitungan normal / DB)
                    // $row->akm_thn_lalu
                    // $row->nilai_buku_lalu

                    // matikan pergerakan mulai tahun ini
                    $row->penambahan_penyusutan = 0;
                    $row->penambahan  = 0;
                    $row->pengurangan = 0;
                }

                // ===============================
                // 2️⃣ TAHUN SETELAH LOCK
                // ===============================
                if ((int)$tahun > $tahun_lock) {

                    // warisi nilai final
                    $row->nilai_buku_final = -1;
                    $row->akm_thn_ini      = (int)$row->rupiah - (-1);

                    $row->akm_thn_lalu     = $row->akm_thn_ini;
                    $row->nilai_buku_lalu = $row->nilai_buku_final;

                    // tidak boleh ada pergerakan
                    $row->penambahan_penyusutan = 0;
                    $row->penambahan  = 0;
                    $row->pengurangan = 0;
                }
            }

            // Akumulasi total dari setiap kolom
            $total_rupiah += $row->rupiah;
            $total_nilai_buku += $row->nilai_buku;
            $total_penambahan += $row->penambahan;
            $total_pengurangan += $row->pengurangan;
            $total_akm_thn_lalu += $row->akm_thn_lalu;
            $total_nilai_buku_lalu += $row->nilai_buku_lalu;
            $total_penyusutan += $row->penambahan_penyusutan;
            $total_akm_thn_ini += $row->akm_thn_ini;
            $total_nilai_buku_final += $row->nilai_buku_final;
        }

        // Return data beserta total
        return [
            'results' => $results,
            'total_peralatan_non_amdk' => [
                'total_rupiah' => $total_rupiah,
                'total_nilai_buku' => $total_nilai_buku,
                'total_penambahan' => $total_penambahan,
                'total_pengurangan' => $total_pengurangan,
                'total_akm_thn_lalu' => $total_akm_thn_lalu,
                'total_nilai_buku_lalu' => $total_nilai_buku_lalu,
                'total_penyusutan' => $total_penyusutan,
                'total_akm_thn_ini' => $total_akm_thn_ini,
                'total_nilai_buku_final' => $total_nilai_buku_final
            ]
        ];
    }

    public function get_peralatan_bagian($parent_id, $tahun_lap, $upk_bagian)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->group_start()
            // ===============================
            // STATUS = 1 (PENAMBAHAN)
            // ===============================
            ->group_start()
            ->where('daftar_asset.status', 1)
            ->group_start()
            // logika lama
            ->where('penyusutan.tahun <', 2024)
            // logika baru
            ->or_where('penyusutan.tahun <=', $tahun_lap)
            ->group_end()
            ->group_end()

            // ===============================
            // STATUS = 2 (PENGURANGAN)
            // ===============================
            ->or_group_start()
            ->where('daftar_asset.status', 2)
            ->where('penyusutan.tahun_persediaan <=', $tahun_lap)
            ->group_end()
            ->group_end();
        $this->db->where('daftar_asset.parent_id', $parent_id);
        $this->db->where('daftar_asset.id_no_per', $upk_bagian);
        $this->db->order_by('id_no_per', 'ASC');
        $this->db->order_by('daftar_asset.id_asset', 'ASC');
        $this->db->order_by('tanggal', 'ASC');

        $query = $this->db->get();
        $results = $query->result();

        $tahun = $tahun_lap;
        if (empty($tahun)) {
            $tahun = date('Y');
        }

        // Inisialisasi variabel untuk menyimpan total
        $total_rupiah = 0;
        $total_nilai_buku = 0;
        $total_penambahan = 0;
        $total_pengurangan = 0;
        $total_akm_thn_lalu = 0;
        $total_nilai_buku_lalu = 0;
        $total_penyusutan = 0;
        $total_akm_thn_ini = 0;
        $total_nilai_buku_final = 0;

        // Daftar ID parent untuk bangunan
        $parent_ids_bangunan = [1569, 1907, 2104, 2255, 2671, 2676, 2678, 2680];

        foreach ($results as &$row) {
            if (
                $row->status == 2 &&
                (int)$tahun < (int)$row->tahun_persediaan
            ) {
                $row->nilai_buku = 0;
                $row->penambahan = 0;
                $row->pengurangan = 0;
                $row->akm_thn_lalu = 0;
                $row->nilai_buku_lalu = 0;
                $row->penambahan_penyusutan = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_final = 0;
                continue;
            }
            $umur_tahun = $tahun - $row->tahun;
            $nilai_buku_awal = $row->rupiah; // Nilai awal aset
            $akm_thn_ini = 0;                 // Akumulasi penyusutan tahun ini
            $nilai_buku_final = $nilai_buku_awal; // Nilai buku final untuk tahun berjalan

            if ($umur_tahun == 0) {
                // Kondisi untuk umur_tahun = 0
                $row->akm_thn_lalu = 0;
                $row->nilai_buku = 0;
                $row->penambahan_penyusutan = 0;
                $row->nilai_buku_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_final = $nilai_buku_awal;
            } else {
                $row->pengurangan = 0;
                $row->penambahan = 0;
                // Perhitungan bertahap untuk setiap tahun sejak tahun pertama
                for ($i = 1; $i <= $umur_tahun; $i++) {
                    if ($i == 1) {
                        // Tahun pertama
                        $akm_thn_lalu = 0;
                        $nilai_buku_lalu = $nilai_buku_awal;
                    } else {
                        // Update nilai buku dan akumulasi penyusutan dari tahun sebelumnya
                        $akm_thn_lalu = $akm_thn_ini;
                        $nilai_buku_lalu = $nilai_buku_final;
                    }

                    // Hitung penyusutan berdasarkan kategori aset
                    if (in_array($row->parent_id, $parent_ids_bangunan)) {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
                    } else {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
                    }

                    // Update akumulasi penyusutan dan nilai buku akhir
                    $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
                    $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

                    if ($i > $row->umur) {

                        // --- LOGIKA UNTUK TAHUN PELAPORAN SAAT INI ($tahun_lap) SAJA ---
                        // Pastikan finalisasi dan break hanya terjadi di iterasi terakhir (tahun laporan saat ini)
                        if ($i == $umur_tahun) {
                            // *** CATATAN: akm_thn_lalu dan nilai_buku_lalu sudah benar terisi dari tahun sebelumnya (2024)

                            $akm_thn_ini = $row->rupiah; // Set akumulasi awal
                            $nilai_buku_final = 1;      // Set nilai buku default
                            $penambahan_penyusutan = 0;
                            $row->penambahan = 0;
                            // Hapus baris yang menimpa akm_thn_lalu dan nilai_buku_lalu (sesuai perbaikan sebelumnya)

                            if ($row->status == 1) {
                                $nilai_buku_final = $row->rupiah - $akm_thn_ini;
                                if ($nilai_buku_final == 0 || $umur_tahun > $row->umur) {
                                    $nilai_buku_final = 1; // Nilai Buku Final = 1
                                    $akm_thn_ini = $akm_thn_ini - 1; // Akm Thn Ini = Rupiah - 1
                                }
                            } else {
                                $nilai_buku_final = -1;
                                $akm_thn_ini = $row->rupiah - $nilai_buku_final;
                            }

                            break; // Hentikan loop HANYA di tahun pelaporan
                        }

                        // --- LOGIKA UNTUK TAHUN HISTORIS SEBELUM TAHUN LAPORAN (i > umur TAPI i < umur_tahun) ---
                        // Jika aset sudah habis umur, tetapi belum mencapai tahun laporan, kunci nilainya.
                        $penambahan_penyusutan = 0;

                        // Kunci akumulasi dan nilai buku (asumsi nilai buku residu 1)
                        $akm_thn_ini = $row->rupiah - 1;
                        $nilai_buku_final = 1;

                        // TIDAK ADA 'break;' di sini. Loop akan terus berjalan hingga i mencapai umur_tahun.
                    }
                }

                // Set hasil akhir setelah loop tahun selesai
                $row->akm_thn_lalu = $akm_thn_lalu;
                $row->nilai_buku_lalu = $nilai_buku_lalu;
                $row->penambahan_penyusutan = $penambahan_penyusutan;
                $row->akm_thn_ini = $akm_thn_ini;
                $row->nilai_buku_final = $nilai_buku_final;
            }


            if ($row->status == 2) {
                $tahun_hapus = (int)$row->tahun_persediaan;
                $tahun_lap = (int)$tahun;
                $rupiah = $row->rupiah;

                $umur_tahun = $tahun - $row->tahun_persediaan;
                $umur_tahun_kurang = $tahun - $row->tahun;
                if ($umur_tahun == 0) {
                    $row->nilai_buku = 0;
                    $row->pengurangan = $row->rupiah * -1;
                    $row->nilai_buku_lalu = 0;
                    $row->akm_thn_lalu = 0;
                    $row->penambahan_penyusutan = 0;
                    $row->nilai_buku_final = $nilai_buku_final;
                } else {
                    for ($i = 1; $i <= $umur_tahun_kurang; $i++) {
                        $row->pengurangan = 0;
                        $row->penambahan = 0;
                        $akm_thn_lalu = $akm_thn_ini;
                        $nilai_buku_lalu = $nilai_buku_final;
                    }
                    if (in_array($row->parent_id, $parent_ids_bangunan)) {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
                    } else {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
                    }
                    $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
                    $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

                    if ($i > $row->umur) {
                        $row->pengurangan = 0;
                        $row->penambahan = 0;
                        $akm_thn_lalu = $row->rupiah;
                        $nilai_buku_lalu = 0;
                        $penambahan_penyusutan = 0;
                    }
                }
            }

            // Kondisi khusus untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = 0;
                $row->nilai_buku_final = $row->rupiah;
            }

            // ⚠️ CATATAN KEBIJAKAN:
            // Sistem penyusutan baru berlaku efektif mulai 2025
            // Data s.d. 2024 adalah hasil audit dan TIDAK BOLEH DIUBAH
            // Oleh karena itu umur_aktual dikurangi 1 tahun secara sengaja

            if ($row->status == 2 && (int)$tahun == (int)$row->tahun_persediaan) {

                // 🔴 PENGURANGAN BARU (TAHUN PERTAMA DIHAPUS)
                $row->akm_thn_lalu     = 0;
                $row->nilai_buku_lalu = 0;
            } else if ($row->status == 2 && (int)$tahun >= 2024) {

                $tahun_perolehan = (int) date('Y', strtotime($row->tanggal));
                $umur_aktual = (int)$tahun - $tahun_perolehan - 1;

                if ($umur_aktual > (int)$row->umur) {

                    // 🔒 PENGURANGAN LAMA → CARRY FORWARD
                    $row->akm_thn_lalu     = $row->akm_thn_ini;
                    $row->nilai_buku_lalu = $row->nilai_buku_final;
                }
            }

            // Akumulasi total dari setiap kolom
            $total_rupiah += $row->rupiah;
            $total_nilai_buku += $row->nilai_buku;
            $total_penambahan += $row->penambahan;
            $total_pengurangan += $row->pengurangan;
            $total_akm_thn_lalu += $row->akm_thn_lalu;
            $total_nilai_buku_lalu += $row->nilai_buku_lalu;
            $total_penyusutan += $row->penambahan_penyusutan;
            $total_akm_thn_ini += $row->akm_thn_ini;
            $total_nilai_buku_final += $row->nilai_buku_final;
        }

        // Return data beserta total
        return [
            'results' => $results,
            'totals' => [
                'total_rupiah' => $total_rupiah,
                'total_nilai_buku' => $total_nilai_buku,
                'total_penambahan' => $total_penambahan,
                'total_pengurangan' => $total_pengurangan,
                'total_akm_thn_lalu' => $total_akm_thn_lalu,
                'total_nilai_buku_lalu' => $total_nilai_buku_lalu,
                'total_penyusutan' => $total_penyusutan,
                'total_akm_thn_ini' => $total_akm_thn_ini,
                'total_nilai_buku_final' => $total_nilai_buku_final
            ]
        ];
    }

    public function get_peralatan_bagian_total($parent_id, $tahun_lap)
    {
        $this->db->select('*');
        $this->db->from('penyusutan');
        $this->db->join('daftar_asset', 'daftar_asset.id_asset = penyusutan.id_asset', 'left');
        $this->db->group_start()
            // ===============================
            // STATUS = 1 (PENAMBAHAN)
            // ===============================
            ->group_start()
            ->where('daftar_asset.status', 1)
            ->group_start()
            // logika lama
            ->where('penyusutan.tahun <', 2024)
            // logika baru
            ->or_where('penyusutan.tahun <=', $tahun_lap)
            ->group_end()
            ->group_end()

            // ===============================
            // STATUS = 2 (PENGURANGAN)
            // ===============================
            ->or_group_start()
            ->where('daftar_asset.status', 2)
            ->where('penyusutan.tahun_persediaan <=', $tahun_lap)
            ->group_end()
            ->group_end();
        $this->db->where('daftar_asset.parent_id', $parent_id);
        $this->db->order_by('id_no_per', 'ASC');
        $this->db->order_by('daftar_asset.id_asset', 'ASC');
        $this->db->order_by('tanggal', 'ASC');

        $query = $this->db->get();
        $results = $query->result();

        $tahun = $tahun_lap;
        if (empty($tahun)) {
            $tahun = date('Y');
        }

        // Inisialisasi variabel untuk menyimpan total
        $total_rupiah = 0;
        $total_nilai_buku = 0;
        $total_penambahan = 0;
        $total_pengurangan = 0;
        $total_akm_thn_lalu = 0;
        $total_nilai_buku_lalu = 0;
        $total_penyusutan = 0;
        $total_akm_thn_ini = 0;
        $total_nilai_buku_final = 0;

        // Daftar ID parent untuk bangunan
        $parent_ids_bangunan = [1569, 1907, 2104, 2255, 2671, 2676, 2678, 2680];

        foreach ($results as &$row) {
            if (
                $row->status == 2 &&
                (int)$tahun < (int)$row->tahun_persediaan
            ) {
                $row->nilai_buku = 0;
                $row->penambahan = 0;
                $row->pengurangan = 0;
                $row->akm_thn_lalu = 0;
                $row->nilai_buku_lalu = 0;
                $row->penambahan_penyusutan = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_final = 0;
                continue;
            }
            $umur_tahun = $tahun - $row->tahun;
            $nilai_buku_awal = $row->rupiah; // Nilai awal aset
            $akm_thn_ini = 0;                 // Akumulasi penyusutan tahun ini
            $nilai_buku_final = $nilai_buku_awal; // Nilai buku final untuk tahun berjalan

            if ($umur_tahun == 0) {
                // Kondisi untuk umur_tahun = 0
                $row->akm_thn_lalu = 0;
                $row->nilai_buku = 0;
                $row->penambahan_penyusutan = 0;
                $row->nilai_buku_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_final = $nilai_buku_awal;
            } else {
                $row->pengurangan = 0;
                $row->penambahan = 0;
                // Perhitungan bertahap untuk setiap tahun sejak tahun pertama
                for ($i = 1; $i <= $umur_tahun; $i++) {
                    if ($i == 1) {
                        // Tahun pertama
                        $akm_thn_lalu = 0;
                        $nilai_buku_lalu = $nilai_buku_awal;
                    } else {
                        // Update nilai buku dan akumulasi penyusutan dari tahun sebelumnya
                        $akm_thn_lalu = $akm_thn_ini;
                        $nilai_buku_lalu = $nilai_buku_final;
                    }

                    // Hitung penyusutan berdasarkan kategori aset
                    if (in_array($row->parent_id, $parent_ids_bangunan)) {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
                    } else {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
                    }

                    // Update akumulasi penyusutan dan nilai buku akhir
                    $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
                    $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

                    if ($i > $row->umur) {

                        // --- LOGIKA UNTUK TAHUN PELAPORAN SAAT INI ($tahun_lap) SAJA ---
                        // Pastikan finalisasi dan break hanya terjadi di iterasi terakhir (tahun laporan saat ini)
                        if ($i == $umur_tahun) {
                            // *** CATATAN: akm_thn_lalu dan nilai_buku_lalu sudah benar terisi dari tahun sebelumnya (2024)

                            $akm_thn_ini = $row->rupiah; // Set akumulasi awal
                            $nilai_buku_final = 1;      // Set nilai buku default
                            $penambahan_penyusutan = 0;
                            $row->penambahan = 0;
                            // Hapus baris yang menimpa akm_thn_lalu dan nilai_buku_lalu (sesuai perbaikan sebelumnya)

                            if ($row->status == 1) {
                                $nilai_buku_final = $row->rupiah - $akm_thn_ini;
                                if ($nilai_buku_final == 0 || $umur_tahun > $row->umur) {
                                    $nilai_buku_final = 1; // Nilai Buku Final = 1
                                    $akm_thn_ini = $akm_thn_ini - 1; // Akm Thn Ini = Rupiah - 1
                                }
                            } else {
                                $akm_thn_ini = $akm_thn_ini + 1;
                                $nilai_buku_final = -1;
                            }

                            break; // Hentikan loop HANYA di tahun pelaporan
                        }

                        // --- LOGIKA UNTUK TAHUN HISTORIS SEBELUM TAHUN LAPORAN (i > umur TAPI i < umur_tahun) ---
                        // Jika aset sudah habis umur, tetapi belum mencapai tahun laporan, kunci nilainya.
                        $penambahan_penyusutan = 0;

                        // Kunci akumulasi dan nilai buku (asumsi nilai buku residu 1)
                        $akm_thn_ini = $row->rupiah - 1;
                        $nilai_buku_final = 1;

                        // TIDAK ADA 'break;' di sini. Loop akan terus berjalan hingga i mencapai umur_tahun.
                    }
                }

                // Set hasil akhir setelah loop tahun selesai
                $row->akm_thn_lalu = $akm_thn_lalu;
                $row->nilai_buku_lalu = $nilai_buku_lalu;
                $row->penambahan_penyusutan = $penambahan_penyusutan;
                $row->akm_thn_ini = $akm_thn_ini;
                $row->nilai_buku_final = $nilai_buku_final;
            }

            if ($row->status == 2) {
                $tahun_hapus = (int)$row->tahun_persediaan;
                $tahun_lap = (int)$tahun;
                $rupiah = $row->rupiah;

                $umur_tahun = $tahun - $row->tahun_persediaan;
                $umur_tahun_kurang = $tahun - $row->tahun;
                if ($umur_tahun == 0) {
                    $row->nilai_buku = 0;
                    $row->pengurangan = $row->rupiah * -1;
                    $row->nilai_buku_lalu = 0;
                    $row->akm_thn_lalu = 0;
                    $row->penambahan_penyusutan = 0;
                    $row->nilai_buku_final = $nilai_buku_final;
                } else {
                    for ($i = 1; $i <= $umur_tahun_kurang; $i++) {
                        $row->pengurangan = 0;
                        $row->penambahan = 0;
                        $akm_thn_lalu = $akm_thn_ini;
                        $nilai_buku_lalu = $nilai_buku_final;
                    }
                    if (in_array($row->parent_id, $parent_ids_bangunan)) {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_awal);
                    } else {
                        $penambahan_penyusutan = round_half_to_even(($row->persen_susut / 100) * $nilai_buku_lalu);
                    }
                    $akm_thn_ini = $akm_thn_lalu + $penambahan_penyusutan;
                    $nilai_buku_final = $nilai_buku_awal - $akm_thn_ini;

                    if ($i > $row->umur) {
                        $row->pengurangan = 0;
                        $row->penambahan = 0;
                        $akm_thn_lalu = $row->rupiah;
                        $nilai_buku_lalu = 0;
                        $penambahan_penyusutan = 0;
                    }
                }
            }

            // Kondisi khusus untuk tanah
            if ($row->grand_id == 218) {
                $row->akm_thn_lalu = 0;
                $row->akm_thn_ini = 0;
                $row->nilai_buku_lalu = 0;
                $row->nilai_buku_final = $row->rupiah;
            }

            // ⚠️ CATATAN KEBIJAKAN:
            // Sistem penyusutan baru berlaku efektif mulai 2025
            // Data s.d. 2024 adalah hasil audit dan TIDAK BOLEH DIUBAH
            // Oleh karena itu umur_aktual dikurangi 1 tahun secara sengaja

            if ($row->status == 2 && (int)$tahun == (int)$row->tahun_persediaan) {

                // 🔴 PENGURANGAN BARU (TAHUN PERTAMA DIHAPUS)
                $row->akm_thn_lalu     = 0;
                $row->nilai_buku_lalu = 0;
            } else if ($row->status == 2 && (int)$tahun >= 2024) {

                $tahun_perolehan = (int) date('Y', strtotime($row->tanggal));
                $umur_aktual = (int)$tahun - $tahun_perolehan - 1;

                if ($umur_aktual > (int)$row->umur) {

                    // 🔒 PENGURANGAN LAMA → CARRY FORWARD
                    $row->akm_thn_lalu     = $row->akm_thn_ini;
                    $row->nilai_buku_lalu = $row->nilai_buku_final;
                }
            }

            // Akumulasi total dari setiap kolom
            $total_rupiah += $row->rupiah;
            $total_nilai_buku += $row->nilai_buku;
            $total_penambahan += $row->penambahan;
            $total_pengurangan += $row->pengurangan;
            $total_akm_thn_lalu += $row->akm_thn_lalu;
            $total_nilai_buku_lalu += $row->nilai_buku_lalu;
            $total_penyusutan += $row->penambahan_penyusutan;
            $total_akm_thn_ini += $row->akm_thn_ini;
            $total_nilai_buku_final += $row->nilai_buku_final;
        }

        // Return data beserta total
        return [
            'results' => $results,
            'totals' => [
                'total_rupiah' => $total_rupiah,
                'total_nilai_buku' => $total_nilai_buku,
                'total_penambahan' => $total_penambahan,
                'total_pengurangan' => $total_pengurangan,
                'total_akm_thn_lalu' => $total_akm_thn_lalu,
                'total_nilai_buku_lalu' => $total_nilai_buku_lalu,
                'total_penyusutan' => $total_penyusutan,
                'total_akm_thn_ini' => $total_akm_thn_ini,
                'total_nilai_buku_final' => $total_nilai_buku_final
            ]
        ];
    }


    public function get_unit_peralatan_gudang()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 2789);
        return $this->db->get()->result();
    }
    public function get_unit_peralatan_laboratorium()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 2793);
        return $this->db->get()->result();
    }
    public function get_unit_peralatan_telekomunikasi()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 2795);
        return $this->db->get()->result();
    }

    public function get_unit_peralatan_bengkel()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 2798);
        return $this->db->get()->result();
    }
    public function get_unit_peralatan_lainnya()
    {
        $this->db->select('id, kode, name,parent_id,grand_id, jenis_id');
        $this->db->from('no_per');
        $this->db->where('parent_id', 4251);
        return $this->db->get()->result();
    }
}
