-- =====================================================================
-- 09_SEED_MONITORING_TW1
-- Catatan monitoring TW1 2027 untuk seluruh risiko yang sudah dianalisis.
-- Nilai_lama / nilai_baru diambil dari skor residual saat ini (belum ada
-- perubahan kondisi pada periode ini) — bukan angka karangan.
-- Aman dijalankan ulang: hanya mengisi TW1 yang masih kosong.
-- =====================================================================

USE `asset`;

INSERT INTO `mri_monitoring`
  (`id_register`, `tahun`, `triwulan`, `status_tindakan`, `hasil_monitoring`,
   `kontrol_masih_efektif`, `nilai_lama`, `nilai_baru`, `perubahan`, `sebab_perubahan`,
   `perlu_reassess`, `tanggal_review`, `review_oleh`, `created_by`)
SELECT
  r.`id_register`, 2027, 1,
  CASE WHEN e.`keputusan` = 'Treat' THEN 'DALAM_PROSES' ELSE 'SELESAI' END,
  CONCAT('Monitoring TW1 2027: skor residual ', a.`nilai_residual`, ' (',
         a.`level_residual`, ') tidak berubah sejak analisis; kontrol berjalan sesuai rencana.'),
  'YA', a.`nilai_residual`, a.`nilai_residual`, 'Tetap',
  'Tidak ada perubahan kondisi pada periode ini.',
  0, '2027-03-31', 'sistem', 'seed_monitoring_tw1'
FROM `mri_register` r
JOIN `mri_analisa` a ON a.`id_register` = r.`id_register`
LEFT JOIN `mri_evaluasi` e ON e.`id_register` = r.`id_register`
LEFT JOIN `mri_monitoring` m
       ON m.`id_register` = r.`id_register` AND m.`tahun` = 2027 AND m.`triwulan` = 1
WHERE r.`tahun` = 2027 AND m.`id_monitoring` IS NULL;

SELECT 'monitoring_tw1' AS `cek`, COUNT(*) AS `jumlah`
FROM `mri_monitoring`
WHERE `tahun` = 2027 AND `triwulan` = 1;
