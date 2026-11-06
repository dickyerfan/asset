-- =====================================================================
-- 13_create_mri_checklist.sql
--
-- Checklist & Target Manajemen Risiko (tahun-agnostik).
--
-- Tiga tabel:
--   1. mri_checklist_item    -> template butir checklist (tetap, dipakai semua tahun)
--   2. mri_checklist_target  -> target tanggal/PIC per tahun
--                               id_upk = 0  : target global (semua unit)
--                               id_upk > 0  : override untuk unit tertentu
--   3. mri_checklist_centang -> centang manual per tahun + unit
--
-- Aman dijalankan berulang (CREATE IF NOT EXISTS + ON DUPLICATE KEY).
-- =====================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- 1. Template butir checklist
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mri_checklist_item` (
  `id_item` int(11) NOT NULL AUTO_INCREMENT,
  `tahap_no` tinyint(3) NOT NULL DEFAULT 0,
  `kode_item` varchar(50) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `rute` varchar(100) DEFAULT NULL,
  `auto_metric` varchar(50) DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_item`),
  UNIQUE KEY `uq_checklist_kode` (`kode_item`),
  KEY `idx_checklist_tahap` (`tahap_no`, `urutan`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ---------------------------------------------------------------------
-- 2. Target per tahun (global atau override per unit)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mri_checklist_target` (
  `id_target` int(11) NOT NULL AUTO_INCREMENT,
  `tahun` smallint(4) NOT NULL,
  `id_upk` int(11) NOT NULL DEFAULT 0,
  `tahap_no` tinyint(3) NOT NULL DEFAULT 0,
  `kode_item` varchar(50) NOT NULL DEFAULT '',
  `target_mulai` date DEFAULT NULL,
  `target_selesai` date DEFAULT NULL,
  `pic` varchar(150) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_target`),
  UNIQUE KEY `uq_checklist_target` (`tahun`, `id_upk`, `tahap_no`, `kode_item`),
  KEY `idx_checklist_target_tahun` (`tahun`, `id_upk`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ---------------------------------------------------------------------
-- 3. Centang manual per tahun + unit
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mri_checklist_centang` (
  `id_centang` int(11) NOT NULL AUTO_INCREMENT,
  `tahun` smallint(4) NOT NULL,
  `id_upk` int(11) NOT NULL,
  `kode_item` varchar(50) NOT NULL,
  `selesai` tinyint(1) NOT NULL DEFAULT 0,
  `catatan` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_centang`),
  UNIQUE KEY `uq_checklist_centang` (`tahun`, `id_upk`, `kode_item`),
  KEY `idx_checklist_centang_tahun` (`tahun`, `id_upk`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ---------------------------------------------------------------------
-- Seed template butir checklist
--
-- tahap_no:
--   0 = Pengaturan & Sasaran (persiapan)
--   1 = Komunikasi & Konsultasi
--   2 = Lingkup & Konteks
--   3 = Identifikasi Risiko
--   4 = Analisis Risiko
--   5 = Evaluasi Risiko
--   6 = Perlakuan Risiko
--   7 = Peninjauan
--   8 = Pelaporan
--
-- auto_metric = NULL berarti butir hanya bisa dicentang manual.
-- ---------------------------------------------------------------------
INSERT INTO `mri_checklist_item`
(`tahap_no`, `kode_item`, `judul`, `keterangan`, `rute`, `auto_metric`, `urutan`) VALUES
-- Tahap 0 — Pengaturan & Sasaran
(0, 'T0-01', 'Skala level risiko sesuai kebijakan', 'Skala 3 level (Rendah/Sedang/Tinggi) dan rentangnya sudah benar.', 'manrisk/pengaturan', NULL, 1),
(0, 'T0-02', 'Risk appetite / tolerance per kategori terisi', 'Diisi bila kebijakan risiko mengharuskan appetite per kategori.', 'manrisk/pengaturan', 'appetite', 2),
(0, 'T0-03', 'Kunci periode dalam keadaan terbuka', 'Pengisian hanya bisa dilakukan bila periode tidak terkunci.', 'manrisk/pengaturan', 'kunci_terbuka', 3),
(0, 'T0-04', 'Sasaran / objektif tahun ini terdaftar', 'Minimal satu sasaran SMART sudah tercatat untuk tahun berjalan.', 'manrisk/objektif', 'objektif_ada', 4),
(0, 'T0-05', 'Seluruh sasaran sudah direkonsiliasi', 'Status rekonsiliasi semua objektif = SELESAI.', 'manrisk/objektif', 'objektif_rekon', 5),

-- Tahap 1 — Komunikasi & Konsultasi
(1, 'T1-01', 'Minimal satu sesi komunikasi tercatat', 'Sesi rapat/FGD/wawancara/sosialisasi sudah didokumentasikan.', 'manrisk/komunikasi', 'komunikasi_ada', 1),
(1, 'T1-02', 'Sesi komunikasi berstatus SELESAI', 'Sesi sudah ditutup dengan ringkasan dan tindak lanjut.', 'manrisk/komunikasi', 'komunikasi_selesai', 2),
(1, 'T1-03', 'Empat pertanyaan kunci terjawab', 'Sasaran, risiko, kontrol, dan tindakan selanjutnya sudah dijawab.', 'manrisk/komunikasi', 'komunikasi_4q', 3),
(1, 'T1-04', 'Lima hasil sesi tercentang', 'Pemahaman, prioritas, validasi, kesepakatan, dan tindak lanjut.', 'manrisk/komunikasi', 'komunikasi_5hasil', 4),
(1, 'T1-05', 'Tindak lanjut punya PIC dan target selesai', 'Setiap tindak lanjut wajib memiliki penanggung jawab dan tenggat.', 'manrisk/komunikasi', 'komunikasi_tindak', 5),

-- Tahap 2 — Lingkup & Konteks
(2, 'T2-01', 'Dokumen lingkup tahun ini dibuat', 'Minimal satu dokumen lingkup sudah tersedia.', 'manrisk/lingkup', 'lingkup_ada', 1),
(2, 'T2-02', 'Status lingkup FINAL', 'Dokumen sudah diselesaikan, bukan lagi DRAFT.', 'manrisk/lingkup', 'lingkup_final', 2),
(2, 'T2-03', 'Konteks internal dan eksternal terisi', 'Kondisi internal dan eksternal yang memengaruhi risiko sudah dicatat.', 'manrisk/lingkup', 'lingkup_konteks', 3),
(2, 'T2-04', 'Kriteria acuan terisi', 'Skala likelihood, consequence, matrix, dan appetite sudah dirujuk.', 'manrisk/lingkup', 'lingkup_kriteria', 4),
(2, 'T2-05', 'Hasil konsultasi lingkup terisi', 'Kesimpulan konsultasi dan empat pertanyaan kunci sudah ada.', 'manrisk/lingkup', 'lingkup_hasil', 5),
(2, 'T2-06', 'Dokumen lingkup dikomunikasikan ke pihak terkait', 'Pastikan unit teknis, keuangan, dan SPI menerima dokumen ini.', 'manrisk/lingkup', NULL, 6),

-- Tahap 3 — Identifikasi Risiko
(3, 'T3-01', 'Risiko tahun ini terdaftar', 'Minimal satu risiko sudah masuk risk register.', 'manrisk/register', 'register_ada', 1),
(3, 'T3-02', 'Uraian risiko, penyebab, dan peristiwa terisi', 'Rumusan memuat penyebab, peristiwa, dan dampak terhadap sasaran.', 'manrisk/register', 'register_lengkap', 2),
(3, 'T3-03', 'Setiap risiko terkait minimal satu objektif', 'Kaitan risiko ke sasaran/KPI wajib dicentang di form register.', 'manrisk/register', 'register_objektif', 3),
(3, 'T3-04', 'Setiap risiko punya sasaran/KPI', 'Kolom sasaran/KPI terisi untuk seluruh risiko.', 'manrisk/register', 'register_kpi', 4),
(3, 'T3-05', 'Setiap risiko punya minimal satu akibat', 'Daftar akibat risiko sudah diisi minimal satu baris.', 'manrisk/register', 'register_akibat', 5),
(3, 'T3-06', 'Kode risiko unik dan sesuai segmen', 'Format IJT-SEG-NN dan tidak ada kode yang bentrok.', 'manrisk/register', NULL, 6),

-- Tahap 4 — Analisis Risiko
(4, 'T4-01', 'Skor inherent terisi (L x D)', 'Likelihood dan dampak sebelum kontrol sudah dinilai.', 'manrisk/analisa', 'analisa_inherent', 1),
(4, 'T4-02', 'Skor residual terisi (L x D)', 'Likelihood dan dampak setelah kontrol yang ada sudah dinilai.', 'manrisk/analisa', 'analisa_residual', 2),
(4, 'T4-03', 'Risk Owner terisi', 'Pihak yang berwenang dan akuntabel, bukan sekadar pelaksana.', 'manrisk/analisa', 'analisa_owner', 3),
(4, 'T4-04', 'Residual tidak lebih tinggi dari inherent', 'Kontrol harus menurunkan atau menjaga skor risiko.', 'manrisk/analisa', 'analisa_logika', 4),
(4, 'T4-05', 'Ringkasan analisis terisi', 'Simpulan kondisi risiko dan kontrolnya sudah ditulis.', 'manrisk/analisa', 'analisa_ringkasan', 5),

-- Tahap 5 — Evaluasi Risiko
(5, 'T5-01', 'Seluruh risiko sudah dievaluasi', 'Setiap risiko memiliki satu catatan evaluasi.', 'manrisk/evaluasi', 'evaluasi_cakupan', 1),
(5, 'T5-02', 'Nilai evaluasi sama dengan skor residual', 'Nilai evaluasi tidak diisi bebas, mengikuti residual.', 'manrisk/evaluasi', 'evaluasi_nilai', 2),
(5, 'T5-03', 'Alasan keputusan terisi', 'Alasan wajib ada, terutama bila keputusan berbeda dari rekomendasi band.', 'manrisk/evaluasi', 'evaluasi_alasan', 3),
(5, 'T5-04', 'Override band sudah ditinjau Admin/Auditor', 'Evaluasi berstatus PERLU_REVIEW sudah ditandai ditinjau.', 'manrisk/evaluasi', 'evaluasi_review', 4),
(5, 'T5-05', 'Eskalasi tercatat untuk risiko yang perlu', 'Risiko dengan perlu_eskalasi = 1 sudah memiliki tujuan eskalasi.', 'manrisk/evaluasi', 'evaluasi_eskalasi', 5),
(5, 'T5-06', 'Keputusan evaluasi disepakati Risk Owner', 'Konfirmasi lisan/tertulis dari Risk Owner sudah diperoleh.', 'manrisk/evaluasi', NULL, 6),

-- Tahap 6 — Perlakuan Risiko
(6, 'T6-01', 'Risiko Treat/Escalate punya rencana perlakuan', 'Setiap risiko yang perlu ditangani sudah memiliki rencana.', 'manrisk/perlakuan', 'perlakuan_treat', 1),
(6, 'T6-02', 'Strategi dan alasan strategi terisi', 'AVOID/REDUCE/SHARE/ACCEPT beserta alasannya sudah dipilih.', 'manrisk/perlakuan', 'perlakuan_strategi', 2),
(6, 'T6-03', 'Uraian tindakan dan indikator sukses terisi', 'Aksi konkret dan ukuran keberhasilannya sudah ditulis.', 'manrisk/perlakuan', 'perlakuan_tindakan', 3),
(6, 'T6-04', 'PIC dan jadwal perlakuan terisi', 'Pelaksana, tanggal mulai, dan tanggal selesai sudah ada.', 'manrisk/perlakuan', 'perlakuan_pic', 4),
(6, 'T6-05', 'Status laksana diperbarui', 'Tidak seluruh rencana masih berstatus BELUM.', 'manrisk/perlakuan', 'perlakuan_status', 5),
(6, 'T6-06', 'Anggaran perlakuan disetujui', 'Pastikan anggaran sudah masuk RKAP atau disetujui pimpinan.', 'manrisk/perlakuan', NULL, 6),

-- Tahap 7 — Peninjauan
(7, 'T7-01', 'Monitoring triwulan berjalan', 'Setiap risiko aktif memiliki catatan monitoring tahun ini.', 'manrisk/monitoring', 'monitoring_cakupan', 1),
(7, 'T7-02', 'Skor baru (L x D) tercatat', 'Skor hasil monitoring sudah diisi, bukan hanya skor lama.', 'manrisk/monitoring', 'monitoring_skor', 2),
(7, 'T7-03', 'Hasil monitoring terisi', 'Kondisi aktual dan efektivitas kontrol sudah dicatat.', 'manrisk/monitoring', 'monitoring_hasil', 3),
(7, 'T7-04', 'Perubahan skor dijelaskan', 'Bila skor naik/turun, sebab perubahan wajib diisi.', 'manrisk/monitoring', 'monitoring_sebab', 4),
(7, 'T7-05', 'Perlu reassess ditindaklanjuti', 'Risiko yang ditandai reassess sudah memiliki tanggal review.', 'manrisk/monitoring', 'monitoring_reassess', 5),
(7, 'T7-06', 'Evaluasi ditinjau bila skor berubah', 'Perubahan level risiko sudah dikonfirmasi ke pemilik risiko.', 'manrisk/monitoring', NULL, 6),

-- Tahap 8 — Pelaporan
(8, 'T8-01', 'Laporan Operasional tersedia', 'Laporan operasional tahun berjalan sudah disusun.', 'manrisk/laporan', 'laporan_operasional', 1),
(8, 'T8-02', 'Laporan Manajemen tersedia', 'Laporan manajemen tahun berjalan sudah disusun.', 'manrisk/laporan', 'laporan_manajemen', 2),
(8, 'T8-03', 'Laporan Dewan Pengawas tersedia', 'Laporan untuk Dewan Pengawas sudah disusun.', 'manrisk/laporan', 'laporan_pengawas', 3),
(8, 'T8-04', 'Laporan Tahunan tersedia', 'Laporan tahunan manajemen risiko sudah disusun.', 'manrisk/laporan', 'laporan_tahunan', 4),
(8, 'T8-05', 'Laporan FINAL dan disahkan', 'Status FINAL, disahkan oleh pejabat, dan tanggal pengesahan terisi.', 'manrisk/laporan', 'laporan_sah', 5),
(8, 'T8-06', 'Laporan ditinjau manajemen sebelum disahkan', 'Pastikan kesimpulan dan rencana tindak lanjut sudah ditinjau.', 'manrisk/laporan', NULL, 6)
ON DUPLICATE KEY UPDATE
  `tahap_no`   = VALUES(`tahap_no`),
  `judul`      = VALUES(`judul`),
  `keterangan` = VALUES(`keterangan`),
  `rute`       = VALUES(`rute`),
  `auto_metric`= VALUES(`auto_metric`),
  `urutan`     = VALUES(`urutan`),
  `aktif`      = 1;

SET FOREIGN_KEY_CHECKS = 1;
