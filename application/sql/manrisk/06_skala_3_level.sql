-- =====================================================================
-- 06 · MIGRASI SKALA RISIKO: 4 LEVEL -> 3 LEVEL
--
-- SUMBER: D:\MR 2026\Ringkasan_MR_Perumdam_Ijen_Tirta.md  §5.2
--         blok "KRITERIA EVALUASI":
--
--           Nilai Risiko | Kategori | Tindakan
--           --------------+----------+---------------------------
--           <= 10         | Rendah   | Dapat diterima, tetap dipantau
--           11 - 15       | Sedang   | Perlu monitoring tambahan
--           > 15          | Tinggi   | Treatment prioritas
--
-- Sebelumnya modul manrisk memakai 4 level (1-5 / 6-9 / 10-15 / 16-25)
-- yang TIDAK ada di materi. Bandar diganti dan jumlah level jadi 3.
-- Modul legacy mr_tingkat_risiko (5 level, 2025-2026) TIDAK disentuh.
--
-- Urutan migrasi wajib demi UNIQUE KEY uq_skor_min (skor_min lama
-- 1, 6, 10, 16):
--   1. hapus Kritis            -> skor_min 16 menjadi bebas
--   2. Tinggi  skor_min 10->16 , skor_max 15->25
--   3. Sedang  skor_min  6->11 , skor_max  9->15
--   4. Rendah  skor_max  5->10              (skor_min tetap 1)
--
-- Kolom `aksi` ber-ENUM('Accept','Monitor','Treat','Escalate') TIDAK
-- diubah. 'Escalate' tidak lagi menjadi nama band, tetapi tetap sah
-- sebagai keputusan manual pada form Evaluasi (mri_evaluasi.keputusan).
--
-- Sumber kebenaran perhitungan level aplikasi adalah
-- Model_mri_master::levelDariSkor() yang membaca mri_tingkat_risiko,
-- sehingga TIDAK ADA logika level yang perlu diubah di kode PHP.
-- Yang dimigrasi di sini hanyalah string level yang sudah tersimpan
-- di mri_analisa (inherent + residual), mri_evaluasi, dan
-- mri_riwayat_skor (snapshot audit antartiwulan).
--
-- Jalankan setelah 05_seed_kri_2027.sql.
-- Idempoten: boleh dijalankan berulang tanpa efek samping.
-- =====================================================================
SET NAMES utf8mb4;

START TRANSACTION;

-- ---------------------------------------------------------------------
-- 1. Hapus band Kritis (sekarang: 16-25, aksi Escalate)
-- ---------------------------------------------------------------------
DELETE FROM `mri_tingkat_risiko` WHERE `nama_tingkat` = 'Kritis';

-- ---------------------------------------------------------------------
-- 2. Tinggi  10-15  ->  16-25   (materi: "> 15  ->  Treatment prioritas")
-- ---------------------------------------------------------------------
UPDATE `mri_tingkat_risiko`
SET `skor_min`    = 16,
    `skor_max`    = 25,
    `warna`       = '#ef4444',
    `aksi`        = 'Treat',
    `deskripsi`   = '3-Tinggi (16-25): Treatment prioritas, direksi monitor',
    `modified_at` = NOW(),
    `modified_by` = 'migrasi_06'
WHERE `nama_tingkat` = 'Tinggi';

-- ---------------------------------------------------------------------
-- 3. Sedang   6-9   ->  11-15   (materi: "11 - 15 -> Perlu monitoring
--                                    tambahan")
-- ---------------------------------------------------------------------
UPDATE `mri_tingkat_risiko`
SET `skor_min`    = 11,
    `skor_max`    = 15,
    `warna`       = '#eab308',
    `aksi`        = 'Monitor',
    `deskripsi`   = '2-Sedang (11-15): Perlu monitoring tambahan',
    `modified_at` = NOW(),
    `modified_by` = 'migrasi_06'
WHERE `nama_tingkat` = 'Sedang';

-- ---------------------------------------------------------------------
-- 4. Rendah   1-5   ->   1-10    (materi: "<= 10 -> Dapat diterima,
--                                    tetap dipantau")
-- ---------------------------------------------------------------------
UPDATE `mri_tingkat_risiko`
SET `skor_max`    = 10,
    `warna`       = '#22c55e',
    `aksi`        = 'Accept',
    `deskripsi`   = '1-Rendah (1-10): Dapat diterima, tetap dipantau',
    `modified_at` = NOW(),
    `modified_by` = 'migrasi_06'
WHERE `nama_tingkat` = 'Rendah';

-- ---------------------------------------------------------------------
-- 5. Hitung ulang string level yang sudah tersimpan.
--    Hanya baris yang benar-benar berubah yang disentuh, dan audit
--    (modified_at/by) dicatat apa adanya.
--
--    Pemetaan band mengikuti mri_tingkat_risiko hasil langkah 2-4.
-- ---------------------------------------------------------------------

-- 5a. mri_analisa.level_inherent
UPDATE `mri_analisa`
SET `level_inherent` = CASE
      WHEN `nilai_inherent` <= 10 THEN 'Rendah'
      WHEN `nilai_inherent` <= 15 THEN 'Sedang'
      ELSE 'Tinggi' END,
    `modified_at` = NOW(),
    `modified_by` = 'migrasi_06'
WHERE `level_inherent` <> CASE
      WHEN `nilai_inherent` <= 10 THEN 'Rendah'
      WHEN `nilai_inherent` <= 15 THEN 'Sedang'
      ELSE 'Tinggi' END;

-- 5b. mri_analisa.level_residual
UPDATE `mri_analisa`
SET `level_residual` = CASE
      WHEN `nilai_residual` <= 10 THEN 'Rendah'
      WHEN `nilai_residual` <= 15 THEN 'Sedang'
      ELSE 'Tinggi' END,
    `modified_at` = NOW(),
    `modified_by` = 'migrasi_06'
WHERE `level_residual` <> CASE
      WHEN `nilai_residual` <= 10 THEN 'Rendah'
      WHEN `nilai_residual` <= 15 THEN 'Sedang'
      ELSE 'Tinggi' END;

-- 5c. mri_evaluasi.level_evaluasi
UPDATE `mri_evaluasi`
SET `level_evaluasi` = CASE
      WHEN `nilai_evaluasi` <= 10 THEN 'Rendah'
      WHEN `nilai_evaluasi` <= 15 THEN 'Sedang'
      ELSE 'Tinggi' END,
    `modified_at` = NOW(),
    `modified_by` = 'migrasi_06'
WHERE `level_evaluasi` <> CASE
      WHEN `nilai_evaluasi` <= 10 THEN 'Rendah'
      WHEN `nilai_evaluasi` <= 15 THEN 'Sedang'
      ELSE 'Tinggi' END;

-- 5d. mri_riwayat_skor (snapshot audit antartiwulan)
--     Tabel ini tidak punya kolom modified_*, hanya recorded_at.
--     recorded_at TIDAK diubah — ia mencatat kapan skor direkam,
--     bukan kapan label level dihitung ulang.
UPDATE `mri_riwayat_skor`
SET `level_inherent` = CASE
      WHEN `nilai_inherent` <= 10 THEN 'Rendah'
      WHEN `nilai_inherent` <= 15 THEN 'Sedang'
      ELSE 'Tinggi' END,
    `level_residual` = CASE
      WHEN `nilai_residual` <= 10 THEN 'Rendah'
      WHEN `nilai_residual` <= 15 THEN 'Sedang'
      ELSE 'Tinggi' END
WHERE `level_inherent` <> CASE
        WHEN `nilai_inherent` <= 10 THEN 'Rendah'
        WHEN `nilai_inherent` <= 15 THEN 'Sedang'
        ELSE 'Tinggi' END
   OR `level_residual` <> CASE
        WHEN `nilai_residual` <= 10 THEN 'Rendah'
        WHEN `nilai_residual` <= 15 THEN 'Sedang'
        ELSE 'Tinggi' END;

COMMIT;

-- ---------------------------------------------------------------------
-- Verifikasi — hasil yang diharapkan:
--   SELECT COUNT(*) FROM mri_tingkat_risiko WHERE nama_tingkat='Kritis';
--     -> 0
--   SELECT COUNT(*) FROM mri_tingkat_risiko;
--     -> 3
--   SELECT SUM(skor_max - skor_min + 1) FROM mri_tingkat_risiko WHERE status=1;
--     -> 25              (cakupan 1..25 penuh, tanpa celah & tanpa tumpang tindih)
--   SELECT MIN(skor_min), MAX(skor_max) FROM mri_tingkat_risiko WHERE status=1;
--     -> 1, 25
--   SELECT COUNT(*) FROM mri_analisa
--    WHERE level_inherent  NOT IN ('Rendah','Sedang','Tinggi')
--       OR level_residual  NOT IN ('Rendah','Sedang','Tinggi');
--     -> 0
--   SELECT COUNT(*) FROM mri_evaluasi
--    WHERE level_evaluasi NOT IN ('Rendah','Sedang','Tinggi');
--     -> 0
--   SELECT COUNT(*) FROM mri_riwayat_skor
--    WHERE level_inherent NOT IN ('Rendah','Sedang','Tinggi')
--       OR level_residual NOT IN ('Rendah','Sedang','Tinggi');
--     -> 0
--
--   -- kecocokan ulang label terhadap band (harus 0 selisih):
--   SELECT COUNT(*) FROM mri_analisa a
--    WHERE a.level_inherent <> (SELECT nama_tingkat FROM mri_tingkat_risiko t
--            WHERE t.status=1 AND a.nilai_inherent BETWEEN t.skor_min AND t.skor_max);
--     -> 0
-- =====================================================================
