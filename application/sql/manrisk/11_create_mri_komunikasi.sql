-- =====================================================================
-- MIGRASI 11 · KOMUNIKASI & KONSULTASI — ISO 31000:2018 Klausa 6.2
-- Target: MySQL/MariaDB 10.4 · CodeIgniter 3.1.11
--
-- Jalankan pada database yang sudah memakai modul manrisk (mri_*).
-- Aman dijalankan berulang: tabel memakai IF NOT EXISTS, kunci memakai
-- ON DUPLICATE KEY, dan pemindahan data konsultasi lama memakai NOT EXISTS.
--
-- Isi materi QRMO slide 30-31:
--   - tiga langkah: dengarkan, selaraskan, uji & konfirmasi
--   - empat pertanyaan kunci: sasaran, risiko, kontrol, tindakan
--   - lima hasil yang diharapkan (kolom hasil_*)
-- =====================================================================
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `mri_komunikasi` (
  `id_komunikasi` int(11) NOT NULL AUTO_INCREMENT,
  `tahun` int(4) NOT NULL,
  `triwulan` int(1) NOT NULL DEFAULT 1,
  `id_upk` int(11) DEFAULT NULL,
  `id_lingkup` int(11) DEFAULT NULL,
  `id_register` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `metode` enum('Rapat','FGD','Wawancara','Sosialisasi','Konsultasi Tertulis','Lainnya') NOT NULL DEFAULT 'Rapat',
  `topik` varchar(200) NOT NULL,
  `pihak_terlibat` text DEFAULT NULL,
  `pic_komunikasi` varchar(100) DEFAULT NULL,
  `catatan_dengar` text DEFAULT NULL,
  `catatan_selaras` text DEFAULT NULL,
  `catatan_konfirmasi` text DEFAULT NULL,
  `q_sasaran` text DEFAULT NULL,
  `q_risiko` text DEFAULT NULL,
  `q_kontrol` text DEFAULT NULL,
  `q_tindakan` text DEFAULT NULL,
  `hasil_pemahaman` int(1) NOT NULL DEFAULT 0,
  `hasil_prioritas` int(1) NOT NULL DEFAULT 0,
  `hasil_validasi` int(1) NOT NULL DEFAULT 0,
  `hasil_kesepakatan` int(1) NOT NULL DEFAULT 0,
  `hasil_tindak_lanjut` int(1) NOT NULL DEFAULT 0,
  `ringkasan_hasil` text DEFAULT NULL,
  `tindak_lanjut` text DEFAULT NULL,
  `pic_tindak_lanjut` varchar(100) DEFAULT NULL,
  `target_selesai` date DEFAULT NULL,
  `status_tindak_lanjut` enum('BELUM','PROSES','SELESAI') NOT NULL DEFAULT 'BELUM',
  `status` enum('DRAFT','SELESAI') NOT NULL DEFAULT 'DRAFT',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL DEFAULT 'seed',
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_komunikasi`),
  KEY `idx_kom_tahun` (`tahun`,`triwulan`),
  KEY `idx_kom_upk` (`id_upk`),
  KEY `idx_kom_lingkup` (`id_lingkup`),
  KEY `idx_kom_register` (`id_register`),
  KEY `idx_kom_tanggal` (`tanggal`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Foto pendukung sesi (boleh lebih dari satu per sesi).
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

-- Kunci transaksi baru (muncul otomatis di menu Pengaturan).
INSERT INTO `mri_kunci` (`kode_kunci`, `nama_kunci`, `status_kunci`)
VALUES ('komunikasi_risiko', 'Edit Komunikasi & Konsultasi', 0)
ON DUPLICATE KEY UPDATE `nama_kunci` = VALUES(`nama_kunci`);

-- Pindahkan konsultasi yang selama ini dicatat pada dokumen Lingkup agar
-- riwayat lama tetap terlihat di menu Komunikasi & Konsultasi.
-- Data di mri_lingkup tidak dihapus (tetap menjadi ringkasan dokumen).
INSERT INTO `mri_komunikasi` (
  `tahun`, `triwulan`, `id_upk`, `id_lingkup`, `tanggal`, `metode`, `topik`,
  `pihak_terlibat`, `pic_komunikasi`, `q_sasaran`, `q_risiko`, `q_kontrol`, `q_tindakan`,
  `hasil_pemahaman`, `hasil_prioritas`, `hasil_validasi`, `hasil_kesepakatan`, `hasil_tindak_lanjut`,
  `ringkasan_hasil`, `pic_tindak_lanjut`, `status`, `created_at`, `created_by`
)
SELECT
  l.tahun,
  l.triwulan,
  l.id_upk,
  l.id_lingkup,
  COALESCE(l.tanggal_konsultasi, DATE(l.created_at)) AS tanggal,
  'Rapat',
  LEFT(CONCAT('Konsultasi ', l.nama_lingkup), 200) AS topik,
  l.peserta,
  l.pic_konsultasi,
  l.q_sasaran,
  l.q_risiko,
  l.q_kontrol,
  l.q_tindakan,
  CASE WHEN COALESCE(l.q_sasaran, '') <> '' THEN 1 ELSE 0 END,
  CASE WHEN COALESCE(l.q_risiko, '') <> '' THEN 1 ELSE 0 END,
  CASE WHEN COALESCE(l.q_kontrol, '') <> '' THEN 1 ELSE 0 END,
  CASE WHEN COALESCE(l.q_tindakan, '') <> '' THEN 1 ELSE 0 END,
  CASE WHEN COALESCE(l.pic_konsultasi, '') <> '' THEN 1 ELSE 0 END,
  l.hasil_konsultasi,
  l.pic_konsultasi,
  CASE WHEN COALESCE(l.hasil_konsultasi, '') <> '' THEN 'SELESAI' ELSE 'DRAFT' END,
  l.created_at,
  COALESCE(NULLIF(l.created_by, ''), 'migrasi')
FROM `mri_lingkup` l
WHERE (
    COALESCE(l.peserta, '') <> ''
    OR COALESCE(l.hasil_konsultasi, '') <> ''
    OR COALESCE(l.q_sasaran, '') <> ''
    OR COALESCE(l.q_risiko, '') <> ''
    OR COALESCE(l.q_kontrol, '') <> ''
    OR COALESCE(l.q_tindakan, '') <> ''
  )
  AND NOT EXISTS (
    SELECT 1 FROM `mri_komunikasi` k WHERE k.id_lingkup = l.id_lingkup
  );
