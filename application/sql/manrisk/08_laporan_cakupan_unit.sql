-- =====================================================================
-- 08_LAPORAN_CAKUPAN_UNIT
-- Mendukung dua cakupan laporan:
--   id_upk = 0  -> laporan konsolidasi seluruh organisasi
--   id_upk > 0  -> laporan unit/bagian tertentu
--
-- Unique key lama (tahun, triwulan, jenis_laporan) membuat laporan unit
-- saling bentrok. Key baru menambahkan id_upk.
-- =====================================================================

USE `asset`;

CREATE TABLE IF NOT EXISTS `mri_laporan_bak_20261030` AS
SELECT * FROM `mri_laporan`;

UPDATE `mri_laporan`
SET `id_upk` = 0
WHERE `id_upk` IS NULL;

ALTER TABLE `mri_laporan`
  MODIFY `id_upk` int(11) NOT NULL DEFAULT 0;

ALTER TABLE `mri_laporan`
  DROP INDEX `uq_laporan`,
  ADD UNIQUE KEY `uq_laporan` (`tahun`,`triwulan`,`jenis_laporan`,`id_upk`);

SELECT 'laporan_null_upk' AS `cek`, COUNT(*) AS `jumlah`
FROM `mri_laporan`
WHERE `id_upk` IS NULL;

SELECT 'laporan_total' AS `cek`, COUNT(*) AS `jumlah`
FROM `mri_laporan`;
