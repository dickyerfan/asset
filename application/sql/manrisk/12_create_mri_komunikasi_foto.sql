-- =====================================================================
-- MIGRASI 12 · FOTO PENDUKUNG KOMUNIKASI & KONSULTASI
-- Target: MySQL/MariaDB 10.4 · CodeIgniter 3.1.11
--
-- Jalankan setelah migrasi 11 (tabel mri_komunikasi sudah ada).
-- Aman dijalankan berulang: tabel memakai IF NOT EXISTS.
--
-- Berkas foto disimpan di assets/uploads/manrisk/komunikasi/.
-- Satu sesi komunikasi boleh memiliki lebih dari satu foto (rapat/FGD).
-- =====================================================================
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `mri_komunikasi_foto` (
  `id_foto` int(11) NOT NULL AUTO_INCREMENT,
  `id_komunikasi` int(11) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_foto`),
  KEY `idx_komfoto_komunikasi` (`id_komunikasi`),
  CONSTRAINT `fk_komfoto_komunikasi` FOREIGN KEY (`id_komunikasi`)
    REFERENCES `mri_komunikasi` (`id_komunikasi`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

SELECT 'mri_komunikasi_foto' AS `tabel`, COUNT(*) AS `jumlah`
FROM `mri_komunikasi_foto`;
