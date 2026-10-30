-- =====================================================================
-- MANAJEMEN RISIKO — SNI ISO 31000:2018
-- Modul baru manrisk/ · Prefix tabel mri_
-- Nol tabel legacy (mr_*) dimodifikasi.
-- Target: MySQL/MariaDB 10.4 · CodeIgniter 3.1.11
-- =====================================================================
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================================
-- BAGIAN A · MASTER (6 tabel)
-- =====================================================================

-- A1 · Risk Universe 11 kelompok proses A-K (ISO 6.4.2 konteks organisasi)
DROP TABLE IF EXISTS `mri_proses`;
CREATE TABLE `mri_proses` (
  `id_proses` int(11) NOT NULL AUTO_INCREMENT,
  `kode_proses` varchar(5) NOT NULL,
  `nama_proses` varchar(100) NOT NULL,
  `grup_tema` varchar(100) DEFAULT NULL,
  `id_bagian_penanggung` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `urut` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL DEFAULT 'seed',
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_proses`),
  UNIQUE KEY `uq_kode_proses` (`kode_proses`),
  KEY `idx_proses_bagian` (`id_bagian_penanggung`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- A2 · Skala level risiko — 4 level (template RKAP 2027)
-- Skor L x D dihitung di model; tabel ini hanya menentukan label per rentang.
DROP TABLE IF EXISTS `mri_tingkat_risiko`;
CREATE TABLE `mri_tingkat_risiko` (
  `id_tingkat` int(11) NOT NULL AUTO_INCREMENT,
  `urut_tampil` int(11) NOT NULL,
  `nama_tingkat` varchar(50) NOT NULL,
  `skor_min` int(11) NOT NULL,
  `skor_max` int(11) NOT NULL,
  `warna` varchar(20) DEFAULT NULL,
  `aksi` enum('Accept','Monitor','Treat','Escalate') NOT NULL DEFAULT 'Monitor',
  `deskripsi` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL DEFAULT 'seed',
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_tingkat`),
  UNIQUE KEY `uq_skor_min` (`skor_min`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- A3 · 14 kategori risiko (QRMO slide 11) + alias Indonesia + kode legacy
-- ISO 31000 tidak mensyaratkan taksonomi; kategori disesuaikan konteks.
-- Slide 11: "Satu risiko dapat terkait lebih dari satu kategori.
--            Pilih KATEGORI UTAMA yang paling relevan." -> satu kolom, tanpa junction.
DROP TABLE IF EXISTS `mri_kategori_risiko`;
CREATE TABLE `mri_kategori_risiko` (
  `id_kategori` int(11) NOT NULL AUTO_INCREMENT,
  `kode_kat` varchar(10) NOT NULL,
  `nama_kat` varchar(50) NOT NULL,
  `nama_alias` varchar(50) NOT NULL,
  `kode_legacy` varchar(10) DEFAULT NULL,
  `deskripsi_kat` text DEFAULT NULL,
  `contoh_kat` varchar(200) DEFAULT NULL,
  `urut` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL DEFAULT 'seed',
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_kategori`),
  UNIQUE KEY `uq_kode_kat` (`kode_kat`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- A4 · Risk Owner — master terpisah dari PIC
DROP TABLE IF EXISTS `mri_risk_owner`;
CREATE TABLE `mri_risk_owner` (
  `id_risk_owner` int(11) NOT NULL AUTO_INCREMENT,
  `nama_owner` varchar(100) NOT NULL,
  `nik` varchar(30) DEFAULT NULL,
  `jabatan_owner` varchar(100) DEFAULT NULL,
  `id_bagian` int(11) DEFAULT NULL,
  `kewenangan` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL DEFAULT 'seed',
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_risk_owner`),
  KEY `idx_owner_bagian` (`id_bagian`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- A5 · PIC (pelaksana tindakan) — TERPISAH dari Risk Owner
DROP TABLE IF EXISTS `mri_pic`;
CREATE TABLE `mri_pic` (
  `id_pic` int(11) NOT NULL AUTO_INCREMENT,
  `nama_pic` varchar(100) NOT NULL,
  `jabatan_pic` varchar(100) DEFAULT NULL,
  `id_bagian` int(11) DEFAULT NULL,
  `kompetensi` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL DEFAULT 'seed',
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_pic`),
  KEY `idx_pic_bagian` (`id_bagian`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- A6 · Petugas tanda tangan laporan
DROP TABLE IF EXISTS `mri_petugas_ttd`;
CREATE TABLE `mri_petugas_ttd` (
  `id_ttd` int(11) NOT NULL AUTO_INCREMENT,
  `kode_ttd` varchar(30) NOT NULL,
  `jabatan_ttd` varchar(100) NOT NULL,
  `id_bagian` int(11) DEFAULT NULL,
  `nama_petugas` varchar(100) DEFAULT NULL,
  `nik` varchar(30) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL DEFAULT 'seed',
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_ttd`),
  UNIQUE KEY `uq_kode_ttd` (`kode_ttd`),
  KEY `idx_ttd_bagian` (`id_bagian`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- =====================================================================
-- BAGIAN B · TAHAP 1-3 · LINGKUP, KONTEKS, KRITERIA, OBJEKTIF (4 tabel)
-- =====================================================================

-- B1 · ISO 6.2 Komunikasi & Konsultasi + ISO 6.3 Lingkup, Konteks, Kriteria
DROP TABLE IF EXISTS `mri_lingkup`;
CREATE TABLE `mri_lingkup` (
  `id_lingkup` int(11) NOT NULL AUTO_INCREMENT,
  `tahun` int(4) NOT NULL,
  `triwulan` int(1) NOT NULL DEFAULT 1,
  `id_upk` int(11) DEFAULT NULL,
  `nama_lingkup` varchar(200) NOT NULL,
  `objek_evaluasi` text DEFAULT NULL,
  `batas_evaluasi` text DEFAULT NULL,
  `konteks_internal` text DEFAULT NULL,
  `konteks_eksternal` text DEFAULT NULL,
  `kriteria_acuan` text DEFAULT NULL,
  `tanggal_konsultasi` date DEFAULT NULL,
  `peserta` text DEFAULT NULL,
  `q_sasaran` text DEFAULT NULL,
  `q_risiko` text DEFAULT NULL,
  `q_kontrol` text DEFAULT NULL,
  `q_tindakan` text DEFAULT NULL,
  `hasil_konsultasi` text DEFAULT NULL,
  `pic_konsultasi` varchar(100) DEFAULT NULL,
  `status` enum('DRAFT','FINAL') NOT NULL DEFAULT 'DRAFT',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL DEFAULT 'seed',
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_lingkup`),
  KEY `idx_lingkup_tahun` (`tahun`,`triwulan`),
  KEY `idx_lingkup_upk` (`id_upk`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- B2 · Objektif/Target RKAP — TAHAP 3, titik awal identifikasi risiko
--      Tabel ini sengaja dibuat karena modul legacy TIDAK punya RKAP.
DROP TABLE IF EXISTS `mri_objektif`;
CREATE TABLE `mri_objektif` (
  `id_objektif` int(11) NOT NULL AUTO_INCREMENT,
  `tahun` int(4) NOT NULL,
  `id_bagian` int(11) DEFAULT NULL,
  `id_upk` int(11) DEFAULT NULL,
  `id_proses` int(11) DEFAULT NULL,
  `id_lingkup` int(11) DEFAULT NULL,
  `indikator` varchar(300) NOT NULL,
  `nilai_rkap` decimal(18,2) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `baseline_2026` decimal(18,2) DEFAULT NULL,
  `sumber_nilai` varchar(255) DEFAULT NULL,
  `status_rekonsiliasi` enum('BELUM','SELESAI') NOT NULL DEFAULT 'BELUM',
  `catatan_rekonsiliasi` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL DEFAULT 'seed',
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_objektif`),
  KEY `idx_obj_tahun` (`tahun`),
  KEY `idx_obj_bagian` (`id_bagian`),
  KEY `idx_obj_proses` (`id_proses`),
  KEY `idx_obj_lingkup` (`id_lingkup`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- B3 · Jejak audit perubahan baseline objektif (rekonsiliasi RKAP)
DROP TABLE IF EXISTS `mri_objektif_revisi`;
CREATE TABLE `mri_objektif_revisi` (
  `id_revisi` int(11) NOT NULL AUTO_INCREMENT,
  `id_objektif` int(11) NOT NULL,
  `nilai_lama` decimal(18,2) DEFAULT NULL,
  `nilai_baru` decimal(18,2) DEFAULT NULL,
  `alasan` text DEFAULT NULL,
  `tanggal` datetime NOT NULL DEFAULT current_timestamp(),
  `oleh` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_revisi`),
  KEY `idx_revisi_objektif` (`id_objektif`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- B4 · Risk Appetite / Tolerance / Capacity per kategori
DROP TABLE IF EXISTS `mri_appetite`;
CREATE TABLE `mri_appetite` (
  `id_appetite` int(11) NOT NULL AUTO_INCREMENT,
  `tahun` int(4) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `risk_appetite` text DEFAULT NULL,
  `risk_tolerance` text DEFAULT NULL,
  `risk_capacity` text DEFAULT NULL,
  `batas_eskalasi` varchar(200) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL DEFAULT 'seed',
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_appetite`),
  UNIQUE KEY `uq_appetite` (`tahun`,`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- =====================================================================
-- BAGIAN C · TAHAP 4-8 · RISK REGISTER (9 tabel)
-- =====================================================================

-- C1 · Risiko — ISO 6.4.2 Identifikasi
--      tipe_risiko: ISO 6.4.2 mensyaratkan "threats and opportunities".
DROP TABLE IF EXISTS `mri_register`;
CREATE TABLE `mri_register` (
  `id_register` int(11) NOT NULL AUTO_INCREMENT,
  `kode_risiko` varchar(30) NOT NULL,
  `tahun` int(4) NOT NULL,
  `triwulan_awal` int(1) DEFAULT 1,
  `id_upk` int(11) DEFAULT NULL,
  `id_lingkup` int(11) DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `tipe_risiko` enum('Ancaman','Peluang') NOT NULL DEFAULT 'Ancaman',
  `kegiatan` text DEFAULT NULL,
  `sasaran_kpi` varchar(300) DEFAULT NULL,
  `sumber_risiko` text DEFAULT NULL,
  `risiko` text NOT NULL,
  `penyebab` text DEFAULT NULL,
  `peristiwa_risiko` text DEFAULT NULL,
  `indikator_emerging` text DEFAULT NULL,
  `asumsi_bias` text DEFAULT NULL,
  `status_kendali` enum('C','UC') NOT NULL DEFAULT 'UC',
  `status_risiko` enum('AKTIF','SELESAI','NONAKTIF') NOT NULL DEFAULT 'AKTIF',
  `keterangan` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL DEFAULT 'seed',
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_register`),
  UNIQUE KEY `uq_kode_tahun` (`kode_risiko`,`tahun`),
  KEY `idx_reg_tahun` (`tahun`),
  KEY `idx_reg_upk` (`id_upk`),
  KEY `idx_reg_kategori` (`id_kategori`),
  KEY `idx_reg_lingkup` (`id_lingkup`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- C2 · Akibat — ISO 6.4.2 "more than one type of outcome"
--      Satu risiko boleh punya >1 akibat dengan bobot berbeda.
DROP TABLE IF EXISTS `mri_akibat`;
CREATE TABLE `mri_akibat` (
  `id_akibat` int(11) NOT NULL AUTO_INCREMENT,
  `id_register` int(11) NOT NULL,
  `urutan` int(11) NOT NULL DEFAULT 1,
  `akibat` text NOT NULL,
  `jenis` enum('Finansial','Non-Finansial') NOT NULL DEFAULT 'Non-Finansial',
  `dampak_kualitatif` text DEFAULT NULL,
  `probabilitas_terjadi` int(1) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL DEFAULT 'seed',
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_akibat`),
  KEY `idx_akibat_register` (`id_register`),
  CONSTRAINT `fk_akibat_register` FOREIGN KEY (`id_register`)
    REFERENCES `mri_register` (`id_register`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- C3 · Risiko <-> Proses (many-to-many)
--      Risiko emergen/kombinasi dapat terkait >1 proses,
--      mis. kekeringan -> biaya listrik -> likuiditas (K, D, B).
DROP TABLE IF EXISTS `mri_risiko_proses`;
CREATE TABLE `mri_risiko_proses` (
  `id_risiko_proses` int(11) NOT NULL AUTO_INCREMENT,
  `id_register` int(11) NOT NULL,
  `id_proses` int(11) NOT NULL,
  `jenis` enum('UTAMA','SUPPORT') NOT NULL DEFAULT 'UTAMA',
  PRIMARY KEY (`id_risiko_proses`),
  UNIQUE KEY `uq_reg_proses` (`id_register`,`id_proses`),
  KEY `idx_rp_proses` (`id_proses`),
  CONSTRAINT `fk_rp_register` FOREIGN KEY (`id_register`)
    REFERENCES `mri_register` (`id_register`) ON DELETE CASCADE,
  CONSTRAINT `fk_rp_proses` FOREIGN KEY (`id_proses`)
    REFERENCES `mri_proses` (`id_proses`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- C4 · Risiko <-> Objektif RKAP (many-to-many)
DROP TABLE IF EXISTS `mri_risiko_objektif`;
CREATE TABLE `mri_risiko_objektif` (
  `id_risiko_objektif` int(11) NOT NULL AUTO_INCREMENT,
  `id_register` int(11) NOT NULL,
  `id_objektif` int(11) NOT NULL,
  PRIMARY KEY (`id_risiko_objektif`),
  UNIQUE KEY `uq_reg_obj` (`id_register`,`id_objektif`),
  KEY `idx_ro_objektif` (`id_objektif`),
  CONSTRAINT `fk_ro_register` FOREIGN KEY (`id_register`)
    REFERENCES `mri_register` (`id_register`) ON DELETE CASCADE,
  CONSTRAINT `fk_ro_objektif` FOREIGN KEY (`id_objektif`)
    REFERENCES `mri_objektif` (`id_objektif`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- C5 · Analisis — ISO 6.4.3
--      nilai_* WAJIB = L x D (divalidasi di model; legacy mr_matrik_risiko
--      membuktikan skor bukan perkalian -> 22 dari 25 sel salah).
--      level_* disimpan sebagai snapshot agar label historis tidak ikut
--      berubah saat skala diedit di Pengaturan.
DROP TABLE IF EXISTS `mri_analisa`;
CREATE TABLE `mri_analisa` (
  `id_analisa` int(11) NOT NULL AUTO_INCREMENT,
  `id_register` int(11) NOT NULL,
  `inherent_l` int(11) DEFAULT NULL,
  `inherent_d` int(11) DEFAULT NULL,
  `nilai_inherent` int(11) DEFAULT NULL,
  `level_inherent` varchar(50) DEFAULT NULL,
  `kontrol_uraian` text DEFAULT NULL,
  `kontrol_ada` enum('Ada','Tidak') NOT NULL DEFAULT 'Tidak',
  `memadai` enum('Memadai','Belum Memadai') NOT NULL DEFAULT 'Belum Memadai',
  `residual_l` int(11) DEFAULT NULL,
  `residual_d` int(11) DEFAULT NULL,
  `nilai_residual` int(11) DEFAULT NULL,
  `level_residual` varchar(50) DEFAULT NULL,
  `ringkasan` text DEFAULT NULL,
  `id_risk_owner` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL DEFAULT 'seed',
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_analisa`),
  UNIQUE KEY `uq_analisa_register` (`id_register`),
  KEY `idx_analisa_owner` (`id_risk_owner`),
  CONSTRAINT `fk_analisa_register` FOREIGN KEY (`id_register`)
    REFERENCES `mri_register` (`id_register`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- C6 · Evaluasi — ISO 6.4.4 (Accept / Monitor / Treat / Escalate)
DROP TABLE IF EXISTS `mri_evaluasi`;
CREATE TABLE `mri_evaluasi` (
  `id_evaluasi` int(11) NOT NULL AUTO_INCREMENT,
  `id_register` int(11) NOT NULL,
  `id_analisa` int(11) DEFAULT NULL,
  `nilai_evaluasi` int(11) DEFAULT NULL,
  `level_evaluasi` varchar(50) DEFAULT NULL,
  `kriteria_rujukan` varchar(200) DEFAULT NULL,
  `keputusan` enum('Accept','Monitor','Treat','Escalate') NOT NULL DEFAULT 'Monitor',
  `alasan_evaluasi` text DEFAULT NULL,
  `perlu_eskalasi` int(1) NOT NULL DEFAULT 0,
  `eskalasi_ke` varchar(100) DEFAULT NULL,
  `tanggal_evaluasi` date DEFAULT NULL,
  `triwulan` int(1) DEFAULT NULL,
  `oleh` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL DEFAULT 'seed',
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_evaluasi`),
  KEY `idx_eval_register` (`id_register`),
  KEY `idx_eval_analisa` (`id_analisa`),
  CONSTRAINT `fk_eval_register` FOREIGN KEY (`id_register`)
    REFERENCES `mri_register` (`id_register`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- C7 · Perlakuan Risiko — ISO 6.5
--      strategi: AVOID / REDUCE / SHARE / ACCEPT (slide 42)
DROP TABLE IF EXISTS `mri_perlakuan`;
CREATE TABLE `mri_perlakuan` (
  `id_perlakuan` int(11) NOT NULL AUTO_INCREMENT,
  `id_register` int(11) NOT NULL,
  `id_evaluasi` int(11) DEFAULT NULL,
  `strategi` enum('AVOID','REDUCE','SHARE','ACCEPT') NOT NULL DEFAULT 'REDUCE',
  `alasan_strategi` text DEFAULT NULL,
  `uraian_tindakan` text DEFAULT NULL,
  `indikator_sukses` text DEFAULT NULL,
  `anggaran_biaya` decimal(18,2) DEFAULT NULL,
  `sumber_daya` text DEFAULT NULL,
  `jadwal_mulai` date DEFAULT NULL,
  `jadwal_selesai` date DEFAULT NULL,
  `id_pic` int(11) DEFAULT NULL,
  `status_laksana` enum('BELUM','DALAM_PROSES','SELESAI') NOT NULL DEFAULT 'BELUM',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL DEFAULT 'seed',
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_perlakuan`),
  KEY `idx_perl_register` (`id_register`),
  KEY `idx_perl_evaluasi` (`id_evaluasi`),
  KEY `idx_perl_pic` (`id_pic`),
  CONSTRAINT `fk_perl_register` FOREIGN KEY (`id_register`)
    REFERENCES `mri_register` (`id_register`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- C8 · Monitoring & Review — ISO 6.6 (riwayat per triwulan)
DROP TABLE IF EXISTS `mri_monitoring`;
CREATE TABLE `mri_monitoring` (
  `id_monitoring` int(11) NOT NULL AUTO_INCREMENT,
  `id_register` int(11) NOT NULL,
  `tahun` int(4) NOT NULL,
  `triwulan` int(1) NOT NULL,
  `status_tindakan` varchar(50) DEFAULT NULL,
  `hasil_monitoring` text DEFAULT NULL,
  `kontrol_masih_efektif` enum('YA','TIDAK','SEBAGIAN') NOT NULL DEFAULT 'YA',
  `nilai_lama` int(11) DEFAULT NULL,
  `nilai_baru` int(11) DEFAULT NULL,
  `perubahan` enum('Naik','Turun','Tetap') DEFAULT NULL,
  `sebab_perubahan` text DEFAULT NULL,
  `perlu_reassess` int(1) NOT NULL DEFAULT 0,
  `tanggal_review` date DEFAULT NULL,
  `review_oleh` varchar(100) DEFAULT NULL,
  `file_document` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL DEFAULT 'seed',
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_monitoring`),
  UNIQUE KEY `uq_mon_register_periode` (`id_register`,`tahun`,`triwulan`),
  KEY `idx_mon_register` (`id_register`),
  CONSTRAINT `fk_mon_register` FOREIGN KEY (`id_register`)
    REFERENCES `mri_register` (`id_register`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- C9 · Riwayat skor — snapshot inherent/residual untuk tren
DROP TABLE IF EXISTS `mri_riwayat_skor`;
CREATE TABLE `mri_riwayat_skor` (
  `id_riwayat` int(11) NOT NULL AUTO_INCREMENT,
  `id_register` int(11) NOT NULL,
  `tahun` int(4) NOT NULL,
  `triwulan` int(1) NOT NULL,
  `nilai_inherent` int(11) DEFAULT NULL,
  `nilai_residual` int(11) DEFAULT NULL,
  `level_inherent` varchar(50) DEFAULT NULL,
  `level_residual` varchar(50) DEFAULT NULL,
  `recorded_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_riwayat`),
  UNIQUE KEY `uq_riwayat_register_periode` (`id_register`,`tahun`,`triwulan`),
  KEY `idx_riwayat_register` (`id_register`),
  CONSTRAINT `fk_riwayat_register` FOREIGN KEY (`id_register`)
    REFERENCES `mri_register` (`id_register`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- =====================================================================
-- BAGIAN D · KRI & PELAPORAN (2 tabel)
-- =====================================================================

-- D1 · Key Risk Indicator — slide 60
DROP TABLE IF EXISTS `mri_kri`;
CREATE TABLE `mri_kri` (
  `id_kri` int(11) NOT NULL AUTO_INCREMENT,
  `id_register` int(11) DEFAULT NULL,
  `id_lingkup` int(11) DEFAULT NULL,
  `id_upk` int(11) DEFAULT NULL,
  `tahun` int(4) NOT NULL,
  `triwulan` int(1) NOT NULL,
  `nama_kri` varchar(200) NOT NULL,
  `target` decimal(18,4) DEFAULT NULL,
  `frekuensi` varchar(50) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `nilai_aktual` decimal(18,4) DEFAULT NULL,
  `status_kri` enum('Belum Diukur','Tercapai','Tidak Tercapai','Perlu Perhatian') NOT NULL DEFAULT 'Belum Diukur',
  `keterangan` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL DEFAULT 'seed',
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_kri`),
  KEY `idx_kri_register` (`id_register`),
  KEY `idx_kri_lingkup` (`id_lingkup`),
  KEY `idx_kri_upk` (`id_upk`),
  KEY `idx_kri_periode` (`tahun`,`triwulan`),
  CONSTRAINT `fk_kri_register` FOREIGN KEY (`id_register`)
    REFERENCES `mri_register` (`id_register`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- D2 · Laporan Manajemen Risiko — ISO 6.7 (struktur BAB I-V)
DROP TABLE IF EXISTS `mri_laporan`;
CREATE TABLE `mri_laporan` (
  `id_laporan` int(11) NOT NULL AUTO_INCREMENT,
  `tahun` int(4) NOT NULL,
  `triwulan` int(1) NOT NULL,
  `jenis_laporan` enum('Operasional','Manajemen','Dewan Pengawas','Tahunan') NOT NULL DEFAULT 'Operasional',
  `id_upk` int(11) DEFAULT NULL,
  `id_lingkup` int(11) DEFAULT NULL,
  `ringkasan_eksekutif` text DEFAULT NULL,
  `latar_belakang` text DEFAULT NULL,
  `tujuan_laporan` text DEFAULT NULL,
  `ruang_lingkup` text DEFAULT NULL,
  `rekap_inherent_residual` text DEFAULT NULL,
  `catatan_peta_risiko` text DEFAULT NULL,
  `rekap_peluang` text DEFAULT NULL,
  `hasil_kri` text DEFAULT NULL,
  `evaluasi_tindak_lanjut` text DEFAULT NULL,
  `kesimpulan` text DEFAULT NULL,
  `rencana_tindak_lanjut` text DEFAULT NULL,
  `sumber_catatan` text DEFAULT NULL,
  `status` enum('DRAFT','FINAL') NOT NULL DEFAULT 'DRAFT',
  `file_laporan` varchar(255) DEFAULT NULL,
  `disahkan_oleh` varchar(100) DEFAULT NULL,
  `tanggal_sahkan` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL DEFAULT 'seed',
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_laporan`),
  UNIQUE KEY `uq_laporan` (`tahun`,`triwulan`,`jenis_laporan`),
  KEY `idx_laporan_upk` (`id_upk`),
  KEY `idx_laporan_lingkup` (`id_lingkup`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- =====================================================================
-- BAGIAN E · KUNCI KEAMANAN (1 tabel)
-- =====================================================================

-- E1 · Kunci penguncian modul per konteks
DROP TABLE IF EXISTS `mri_kunci`;
CREATE TABLE `mri_kunci` (
  `id_kunci` int(11) NOT NULL AUTO_INCREMENT,
  `kode_kunci` varchar(50) NOT NULL,
  `nama_kunci` varchar(100) NOT NULL,
  `status_kunci` int(1) NOT NULL DEFAULT 0,
  `locked_at` datetime DEFAULT NULL,
  `locked_by` varchar(100) DEFAULT NULL,
  `unlocked_at` datetime DEFAULT NULL,
  `unlocked_by` varchar(100) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_kunci`),
  UNIQUE KEY `uq_kode_kunci` (`kode_kunci`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
