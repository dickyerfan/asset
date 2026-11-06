-- =====================================================================
-- 07a_ALTER_MRI_EVALUASI_SERVER.sql  (versi plain, tanpa PREPARE)
--
-- HANYA untuk: menambah 5 kolom ke tabel yang SUDAH ADA
--               di server, TANPA menimpa data yang ada.
--
-- Jalankan SATU PER SATU. Bila muncul error "Duplicate column name"
-- berarti kolom sudah ada -> abaikan, lanjut ke baris berikutnya.
--
-- ATAU: kalau tujuan Anda menyalin data lokal ke server,
--       langsung pakai file mri_evaluasi_import_lengkap.sql saja
--       (file itu sudah DROP + CREATE + INSERT sekaligus).
-- =====================================================================

USE `asset`;

ALTER TABLE `mri_evaluasi`
  ADD COLUMN `keputusan_band` varchar(20) DEFAULT NULL AFTER `keputusan`;

ALTER TABLE `mri_evaluasi`
  ADD COLUMN `status_review` enum('SESUAI','PERLU_REVIEW','DITINJAU')
       NOT NULL DEFAULT 'SESUAI' AFTER `keputusan_band`;

ALTER TABLE `mri_evaluasi`
  ADD COLUMN `catatan_review` text DEFAULT NULL AFTER `status_review`;

ALTER TABLE `mri_evaluasi`
  ADD COLUMN `reviewed_at` datetime DEFAULT NULL AFTER `catatan_review`;

ALTER TABLE `mri_evaluasi`
  ADD COLUMN `reviewed_by` varchar(100) DEFAULT NULL AFTER `reviewed_at`;

-- Verifikasi: harus muncul 5 baris di bawah
SELECT COLUMN_NAME, COLUMN_TYPE
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME = 'mri_evaluasi'
  AND COLUMN_NAME IN ('keputusan_band','status_review','catatan_review',
                      'reviewed_at','reviewed_by')
ORDER BY ORDINAL_POSITION;
