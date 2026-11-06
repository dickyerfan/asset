-- =====================================================================
-- 10 · HAPUS MODUL KRI
--
-- KRI (Key Risk Indicator) BUKAN tahap tersendiri dalam alur manajemen
-- risiko yang dipakai aplikasi ini (7 Peninjauan/Monitoring -> 8 Pelaporan).
-- Modul ini dihapus agar alur kembali sesuai materi CRMO Perpamsi dan agar
-- laporan disusun langsung dari data Register sampai Monitoring.
--
-- BACKUP: application/sql/manrisk/backup/mri_kri_2026-11-03.sql
--         (struktur + 6 baris data sebelum penghapusan)
--
-- Jalankan sekali pada database yang sudah berisi data:
--   mysql -uroot asset < 10_drop_kri.sql
--
-- Aman dijalankan berulang (IF EXISTS).
-- =====================================================================
SET NAMES utf8mb4;

-- 1) Tabel indikator KRI beserta datanya.
DROP TABLE IF EXISTS `mri_kri`;

-- 2) Kolom narasi "Hasil KRI" pada laporan tidak dipakai lagi.
--    MySQL/MariaDB 10.4 belum mendukung DROP COLUMN IF EXISTS, jadi
--    keberadaan kolom diperiksa lewat information_schema.
SET @ada_kolom := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME   = 'mri_laporan'
    AND COLUMN_NAME  = 'hasil_kri'
);

SET @sql := IF(
  @ada_kolom > 0,
  'ALTER TABLE `mri_laporan` DROP COLUMN `hasil_kri`',
  'SELECT ''Kolom hasil_kri sudah tidak ada.'' AS info'
);

PREPARE hapus_kolom FROM @sql;
EXECUTE hapus_kolom;
DEALLOCATE PREPARE hapus_kolom;
