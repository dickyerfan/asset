-- =====================================================================
-- 07_ALIGN_EVALUASI_RESIDUAL
-- Menyelaraskan evaluasi risiko dengan SNI ISO 31000:2018:
-- evaluasi memakai skor RESIDUAL (setelah kontrol), bukan inherent.
--
-- Catatan:
--  - Keputusan lama TIDAK diubah. Keputusan yang berbeda dari aksi band
--    hanya ditandai PERLU_REVIEW untuk ditinjau Administrator/Auditor.
--  - Jalankan setelah backup. File ini aman dijalankan sekali.
-- =====================================================================

USE `asset`;

CREATE TABLE IF NOT EXISTS `mri_evaluasi_bak_20261030` AS
SELECT * FROM `mri_evaluasi`;

ALTER TABLE `mri_evaluasi`
  ADD COLUMN IF NOT EXISTS `keputusan_band` varchar(20) DEFAULT NULL AFTER `keputusan`,
  ADD COLUMN IF NOT EXISTS `status_review` enum('SESUAI','PERLU_REVIEW','DITINJAU') NOT NULL DEFAULT 'SESUAI' AFTER `keputusan_band`,
  ADD COLUMN IF NOT EXISTS `catatan_review` text DEFAULT NULL AFTER `status_review`,
  ADD COLUMN IF NOT EXISTS `reviewed_at` datetime DEFAULT NULL AFTER `catatan_review`,
  ADD COLUMN IF NOT EXISTS `reviewed_by` varchar(100) DEFAULT NULL AFTER `reviewed_at`;

-- Skor, level, snapshot kriteria, dan rekomendasi band dihitung dari
-- mri_analisa + mri_tingkat_risiko. Keputusan pengguna tetap utuh.
UPDATE `mri_evaluasi` e
JOIN `mri_analisa` a ON a.`id_register` = e.`id_register`
JOIN `mri_tingkat_risiko` t
  ON a.`nilai_residual` BETWEEN t.`skor_min` AND t.`skor_max`
 AND t.`status` = 1
SET
  e.`nilai_evaluasi` = a.`nilai_residual`,
  e.`level_evaluasi` = t.`nama_tingkat`,
  e.`kriteria_rujukan` = CONCAT(
      'Residual ', a.`nilai_residual`, ' (', t.`nama_tingkat`, ') vs aktif: ',
      (SELECT GROUP_CONCAT(
          CONCAT(`nama_tingkat`, ' ', `skor_min`, '-', `skor_max`)
          ORDER BY `urut_tampil` SEPARATOR '; '
       )
       FROM `mri_tingkat_risiko`
       WHERE `status` = 1)
  ),
  e.`keputusan_band` = t.`aksi`,
  e.`status_review` = CASE
      WHEN e.`keputusan` = t.`aksi` THEN 'SESUAI'
      ELSE 'PERLU_REVIEW'
  END,
  e.`modified_at` = NOW(),
  e.`modified_by` = 'migration_07'
WHERE a.`nilai_residual` IS NOT NULL;

-- Dua narasi lama masih menyebut "Tinggi (12)" padahal skor residual 12
-- berada pada band Sedang. Perbaiki hanya frasa levelnya.
UPDATE `mri_evaluasi`
SET `alasan_evaluasi` = REPLACE(`alasan_evaluasi`, 'Tinggi (12)', 'Sedang (12)')
WHERE `alasan_evaluasi` LIKE '%Tinggi (12)%';

SELECT 'eval_vs_residual' AS `cek`, COUNT(*) AS `jumlah`
FROM `mri_evaluasi` e
JOIN `mri_analisa` a ON a.`id_register` = e.`id_register`
WHERE e.`nilai_evaluasi` <> a.`nilai_residual`
   OR e.`level_evaluasi` <> a.`level_residual`;

SELECT 'stale_criteria' AS `cek`, COUNT(*) AS `jumlah`
FROM `mri_evaluasi`
WHERE `kriteria_rujukan` LIKE '%Kritis%'
   OR `kriteria_rujukan` LIKE '%1-5%'
   OR `kriteria_rujukan` LIKE '%6-9%'
   OR `kriteria_rujukan` LIKE '%10-15%'
   OR `kriteria_rujukan` LIKE '%16-25)%';

SELECT 'review_queue' AS `cek`, COUNT(*) AS `jumlah`
FROM `mri_evaluasi`
WHERE `status_review` = 'PERLU_REVIEW';
