-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: asset
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `mri_register`
--

DROP TABLE IF EXISTS `mri_register`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mri_register`
--

LOCK TABLES `mri_register` WRITE;
/*!40000 ALTER TABLE `mri_register` DISABLE KEYS */;
INSERT INTO `mri_register` VALUES (1,'IJT-OPR-01',2027,1,4,1,2,'Ancaman','Produksi & distribusi air dari 25 sumur bor','Kontinuitas produksi air','Usia pompa tinggi, kurangnya pemeliharaan pencegahan, suku cadang tidak tersedia','Gangguan 25 sumur bor menyebabkan kontinuitas produksi air terganggu','Usia pompa tinggi, kurangnya pemeliharaan pencegahan, suku cadang tidak tersedia','Gangguan pada 25 sumur bor',NULL,NULL,'C','AKTIF','Prioritas sedang','2026-10-05 11:24:37','seed','2026-10-05 09:02:19','manrisktest'),(2,'IJT-OPR-02',2027,1,4,1,2,'Ancaman','Pengolahan & pengawasan kualitas air','Kualitas air sesuai standar','Pencemaran sumber, sedimentasi tinggi, gangguan instalasi pengolahan air','Kualitas air tidak memenuhi standar baku mutu','Pencemaran sumber, sedimentasi tinggi, gangguan instalasi pengolahan air','Kualitas air tidak memenuhi standar',NULL,NULL,'C','AKTIF','Monitor berkala','2026-10-05 11:24:37','seed',NULL,NULL),(3,'IJT-OPR-03',2027,1,4,1,2,'Ancaman','Operasional jaringan transmisi-distribusi','Penurunan NRW','Kebocoran pipa, jaringan tua ACP, tekanan tinggi','Kehilangan air tinggi (NRW) melebihi batas wajar','Kebocoran pipa, jaringan tua ACP, tekanan tinggi','Kehilangan air tinggi (NRW)',NULL,NULL,'C','AKTIF','Prioritas tinggi - RKAP 2027 fokus efisiensi','2026-10-05 11:24:37','seed',NULL,NULL),(4,'IJT-OPR-04',2027,1,6,1,2,'Ancaman','Pelayanan & pengembangan pelanggan','Pola konsumsi 13,43%','Sambungan tutup/cabut melebihi sambungan baru, kehilangan pelanggan','Penurunan pola konsumsi di bawah target RKAP','Sambungan tutup/cabut melebihi sambungan baru, kehilangan pelanggan','Pola konsumsi turun',NULL,NULL,'C','AKTIF','Monitor pertumbuhan sambungan','2026-10-05 11:24:37','seed',NULL,NULL),(5,'IJT-KEU-01',2027,1,3,1,5,'Ancaman','Penagihan & pengelolaan piutang pelanggan','Efisiensi penagihan 92,98%','Pelanggan tidak bayar, kondisi ekonomi lemah, sistem penagihan belum optimal','Piutang tak tertagih tinggi sehingga arus kas terganggu','Pelanggan tidak bayar, kondisi ekonomi lemah, sistem penagihan belum optimal','Piutang tak tertagih meningkat',NULL,NULL,'C','AKTIF','Prioritas tinggi - dampak langsung ke RKAP','2026-10-05 11:24:37','seed',NULL,NULL),(6,'IJT-KEU-02',2027,1,3,1,6,'Ancaman','Produksi air & pengelolaan biaya operasi','Laba bersih Rp 2,51M','Kenaikan harga listrik, harga bahan kimia, inefisiensi','Biaya produksi naik sehingga laba turun dari target','Kenaikan harga listrik, harga bahan kimia, inefisiensi','Biaya produksi naik',NULL,NULL,'C','AKTIF','Monitoring HPP bulanan','2026-10-05 11:24:37','seed',NULL,NULL),(7,'IJT-KEU-03',2027,1,5,1,2,'Ancaman','Pelaksanaan program investasi 2027','Realisasi investasi Rp 2,3M','Proses pengadaan lama, vendor terlambat, perubahan desain','Keterlambatan realisasi investasi','Proses pengadaan lama, vendor terlambat, perubahan desain','Keterlambatan investasi',NULL,NULL,'C','AKTIF','Monitoring bulanan','2026-10-05 11:24:37','seed',NULL,NULL),(8,'IJT-IT-01',2027,1,2,1,10,'Ancaman','Layanan digital, billing, dan sistem informasi','Layanan digital','Server down, serangan siber, jaringan lemah','Gangguan sistem billing/IT yang mengganggu pelayanan','Server down, serangan siber, jaringan lemah','Gangguan sistem billing/IT',NULL,NULL,'C','AKTIF','Risiko rendah namun dampak tinggi','2026-10-05 11:24:37','seed',NULL,NULL),(9,'IJT-HR-01',2027,1,2,1,12,'Ancaman','Pengelolaan kompetensi 172 pegawai','172 pegawai kompeten','Mutasi, pensiun, kompetensi tidak sesuai','Kekurangan SDM terlatih sehingga kualitas layanan menurun','Mutasi, pensiun, kompetensi tidak sesuai','Kekurangan SDM terlatih',NULL,NULL,'C','AKTIF','Prioritas menengah','2026-10-05 11:24:37','seed',NULL,NULL),(10,'IJT-K3-01',2027,1,2,1,14,'Ancaman','Operasional lapangan & keselamatan kerja','Zero accident','SOP tidak dipatuhi, peralatan rusak, kelalaian','Kecelakaan kerja di lingkungan operasional','SOP tidak dipatuhi, peralatan rusak, kelalaian','Kecelakaan kerja',NULL,NULL,'C','AKTIF','Wajib dipantau','2026-10-05 11:24:37','seed',NULL,NULL),(11,'IJT-AMDK-01',2027,1,23,1,6,'Ancaman','Usaha Air Minum Dalam Kemasan Ijen Water','Penjualan Ijen Water naik','Persaingan pasar, distribusi lemah','Penjualan AMDK turun sehingga margin unit menurun','Persaingan pasar, distribusi lemah','Penjualan AMDK turun',NULL,NULL,'C','AKTIF','Peluang pertumbuhan (growth opportunity)','2026-10-05 11:24:37','seed',NULL,NULL),(12,'IJT-EXT-01',2027,1,4,1,13,'Ancaman','Kontinuitas operasional terhadap kejadian eksternal','Kontinuitas operasi','Perubahan iklim, musim kemarau panjang, banjir','Kekeringan/banjir/iklim ekstrem mengganggu kontinuitas operasi','Perubahan iklim, musim kemarau panjang, banjir','Kekeringan/banjir/iklim ekstrem',NULL,NULL,'C','AKTIF','Risiko iklim - strategis','2026-10-05 11:24:37','seed',NULL,NULL),(13,'IJT-REG-01',2027,1,8,1,8,'Ancaman','Kepatuhan regulasi & pengawasan internal','Kepatuhan regulasi','Perubahan kebijakan, ketidakpatuhan','Ketidakpatuhan regulasi yang berdampak pada sanksi dan reputasi','Perubahan kebijakan, ketidakpatuhan','Ketidakpatuhan regulasi',NULL,NULL,'C','AKTIF','Berjalan terus (ongoing compliance)','2026-10-05 11:24:37','seed',NULL,NULL),(14,'IJT-PEL-01',2027,1,6,1,2,'Ancaman','Pengembangan & pertumbuhan pelanggan','Target SR Baru 938','Permintaan rendah, kompetitor, kondisi ekonomi','Target sambungan baru tidak tercapai sehingga pertumbuhan melambat','Permintaan rendah, kompetitor, kondisi ekonomi','Target sambungan baru tidak tercapai',NULL,NULL,'C','AKTIF','Sesuaikan dengan RKAP','2026-10-05 11:24:37','seed',NULL,NULL),(15,'IJT-PEL-02',2027,1,6,1,2,'Ancaman','Penggantian & pemeriksaan meter pelanggan','938 SR, 727 penggantian meter','Kesalahan proses, pelanggan mengeluh','Gangguan saat penggantian meter sehingga kepuasan pelanggan turun','Kesalahan proses, pelanggan mengeluh','Gangguan saat penggantian meter',NULL,NULL,'C','AKTIF','Prioritas rendah','2026-10-05 11:24:37','seed',NULL,NULL),(16,'IJT-OPR-05',2027,1,4,1,2,'Ancaman','Pemeliharaan jaringan transmisi-distribusi','Pemeliharaan jaringan Rp 157,8jt','Kendala anggaran, SDM terbatas','Pemeliharaan tidak optimal sehingga jaringan rusak dan kebocoran meningkat','Kendala anggaran, SDM terbatas','Pemeliharaan tidak optimal',NULL,NULL,'C','AKTIF','Alokasi RKAP 2027','2026-10-05 11:24:37','seed',NULL,NULL),(17,'IJT-GOV-01',2027,1,1,1,1,'Ancaman','Penyusunan & pelaksanaan RKAP 2027','Laba Bersih Rp 2.511.247.563 / Pendapatan Usaha Rp 22.381.036.762','Basis pertumbuhan pelanggan dan efisiensi belum divalidasi; angka Pendapatan Usaha dan Laba Kotor tidak terekonsiliasi antar slide (status_rekonsiliasi = BELUM)','Realisasi RKAP 2027 tidak tercapai sehingga seluruh sasaran tahunan tidak terpenuhi','Basis pertumbuhan pelanggan dan efisiensi belum divalidasi; angka Pendapatan Usaha dan Laba Kotor tidak terekonsiliasi antar slide (status_rekonsiliasi = BELUM)','Realisasi RKAP 2027 tidak tercapai','Selisih Rp 67.005.000 antara Slide 9 dan Slide 8/13 untuk Pendapatan Usaha masih terbuka; capaian pendapatan Smt I 2026 baru 47,10% dari RKAP (Slide 13)','Asumsi pertumbuhan pelanggan dan efisiensi dianggap tercapai tanpa validasi; perbedaan antar slide tidak ditindaklanjuti','UC','AKTIF','DRAFT - menunggu review. Sumber data: seluruh Slide 8-19.','2026-10-05 11:24:37','draft-manrisk',NULL,NULL),(18,'IJT-GOV-02',2027,1,1,1,11,'Ancaman','Pengadaan barang & jasa serta pelaksanaan investasi','Realisasi Investasi Rp 2,3M','Tekanan target investasi Rp 2,3 M, spesifikasi tidak detail, e-procurement belum wajib, kapasitas pengawasan terbatas','Manipulasi & penyimpangan proses pengadaan','Tekanan target investasi Rp 2,3 M, spesifikasi tidak detail, e-procurement belum wajib, kapasitas pengawasan terbatas','Manipulasi & penyimpangan pengadaan','Revisi spesifikasi berulang dan keterlambatan jadwal pengadaan yang tidak dijelaskan','Target investasi dianggap menekan prosedur pengadaan sebagaimana mestinya','C','AKTIF','DRAFT - menunggu review. Contoh kategori Fraud QRMO Slide 11: \"Manipulasi pengadaan\".','2026-10-05 11:24:37','draft-manrisk',NULL,NULL),(19,'IJT-GOV-03',2027,1,1,1,8,'Ancaman','Pengawasan internal & tindak lanjut temuan audit','Kepatuhan regulasi & penutupan temuan','PIC unit tanpa kewajiban tegas, tidak ada mekanisme eskalasi, keterbatasan kapasitas pengawasan','Temuan SPI/audit berulang tidak ditindaklanjuti','PIC unit tanpa kewajiban tegas, tidak ada mekanisme eskalasi, keterbatasan kapasitas pengawasan','Temuan audit berulang tidak ditindaklanjuti','Tembusan temuan berulang pada butir yang sama pada dua periode berturut-turut','Tindak lanjut dianggap selesai bila laporan dikirim, tanpa verifikasi efektivitas kontrol','C','AKTIF','DRAFT - menunggu review. Latar: QRMO Slide 17 program SPI \"Pengawasan internal & GCG\".','2026-10-05 11:24:37','draft-manrisk',NULL,NULL),(20,'IJT-GOV-04',2027,1,1,1,1,'Ancaman','Pengambilan keputusan strategis & tata kelola data','Kualitas keputusan strategis organisasi','Sistem terfragmentasi, laporan masih manual, tidak ada jejak audit keputusan','Keputusan strategis diambil tanpa basis data yang andal','Sistem terfragmentasi, laporan masih manual, tidak ada jejak audit keputusan','Keputusan strategis tanpa data yang andal','Laporan keputusan berupa berkas terpisah tanpa jejak versi','Data yang tersedia dianggap cukup untuk keputusan strategis','C','AKTIF','DRAFT - menunggu review. Latar: QRMO Slide 62 \"Asumsi utama sudah jelas?\" dan Slide 17 program Umum \"Digitalisasi arsip, perbaikan Tata Kelola\".','2026-10-05 11:24:37','draft-manrisk',NULL,NULL),(21,'IJT-GOV-05',2027,1,1,1,1,'Peluang','Pengembangan pasar & efisiensi berbasis digitalisasi','Pendapatan Usaha Rp 22.381.036.762','Wilayah Pamsimas sudah terpasang dan tren digitalisasi meningkat; belum ada mekanisme formal menangkap peluang','Peluang ekspansi layanan dan efisiensi biaya overhead tidak termanfaatkan sehingga pendapatan potensial hilang','Belum ada proses formal identifikasi peluang (kontrol status UC)','Peluang ekspansi & efisiensi belum ditangkap','Tren adopsi layanan digital naik dan wilayah Pamsimas sudah aktif (QRMO Slide 4 & 17)','Peluang dianggap akan tercapi sendiri tanpa mekanisme formal identifikasi dan penugasan PIC','UC','AKTIF','DRAFT - menunggu review. RISIKO TIPE PELUANG (ISO 6.4.2 mensyaratkan \"threats and opportunities\").','2026-10-05 11:24:37','draft-manrisk',NULL,NULL);
/*!40000 ALTER TABLE `mri_register` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mri_analisa`
--

DROP TABLE IF EXISTS `mri_analisa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  CONSTRAINT `fk_analisa_register` FOREIGN KEY (`id_register`) REFERENCES `mri_register` (`id_register`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mri_analisa`
--

LOCK TABLES `mri_analisa` WRITE;
/*!40000 ALTER TABLE `mri_analisa` DISABLE KEYS */;
INSERT INTO `mri_analisa` VALUES (1,1,3,4,12,'Sedang','Pemeliharaan berkala (PM), pompa cadangan, monitoring','Ada','Memadai',2,4,8,'Rendah','Kontrol pemeliharaan menurunkan skor 12 ke 8; sisa risiko terkait ketersediaan suku cadang.',4,'2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(2,2,2,4,8,'Rendah','Pengawasan kualitas berkala, optimalisasi treatment','Ada','Memadai',1,3,3,'Rendah','Pengujian laboratorium rutin & koordinasi DLHK menurunkan skor 8 ke 3.',4,'2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(3,3,4,4,16,'Tinggi','Pengurangan ACP, monitoring tekanan, deteksi kebocoran','Ada','Belum Memadai',3,4,12,'Sedang','Skor sisa masih Tinggi (12) — segmentasi DMA dan penggantian pipa bertahap belum berjalan penuh. Perlu prioritas RKAP 2027.',4,'2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(4,4,3,3,9,'Rendah','Promosi, layanan prima, penanganan keluhan','Ada','Memadai',2,3,6,'Rendah','Kontrol berjalan namun skor sisa tetap Sedang karena penyebab (kehilangan pelanggan) struktural.',6,'2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(5,5,4,4,16,'Tinggi','Pengingat digital, penagihan aktif, program cicilan','Ada','Belum Memadai',3,4,12,'Sedang','Skor sisa masih Tinggi (12) — digitalisasi penagihan belum menuntaskan piutang. Prioritas dampak langsung ke RKAP.',3,'2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(6,6,3,4,12,'Sedang','Efisiensi energi, negosiasi vendor, monitoring pemakaian','Ada','Memadai',2,4,8,'Rendah','Penyesuaian produksi motor pompa & efisiensi listrik sumur bor menurunkan skor 12 ke 8.',3,'2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(7,7,3,3,9,'Rendah','Perencanaan matang, timeline ketat','Ada','Memadai',2,3,6,'Rendah','Monitoring progres investasi & koordinasi vendor menurunkan skor 9 ke 6.',5,'2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(8,8,2,3,6,'Rendah','Cadangan berkala, antivirus, firewall','Ada','Memadai',1,3,3,'Rendah','Peningkatan kapasitas server, rencana pemulihan bencana, dan audit keamanan menurunkan skor 6 ke 3.',2,'2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(9,9,2,3,6,'Rendah','Pelatihan berkala, perencanaan suksesi','Ada','Memadai',1,3,3,'Rendah','Rencana pelatihan & pengembangan talenta menurunkan skor 6 ke 3.',2,'2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(10,10,2,4,8,'Rendah','SOP K3, APD, pengarahan keselamatan','Ada','Memadai',1,4,4,'Rendah','Audit K3, simulasi darurat & asuransi menurunkan skor 8 ke 4.',2,'2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(11,11,3,3,9,'Rendah','Promosi, perluasan distribusi','Ada','Memadai',2,3,6,'Rendah','Strategi pemasaran, kemitraan, pengendalian mutu menurunkan skor 9 ke 6.',23,'2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(12,12,2,5,10,'Rendah','Monitoring cuaca, rencana kontinjensi','Ada','Memadai',1,5,5,'Rendah','Rencana tanggap darurat, alternatif sumber air, koordinasi BMKG menurunkan skor 10 ke 5.',4,'2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(13,13,2,3,6,'Rendah','Monitoring regulasi, tinjauan hukum','Ada','Memadai',1,3,3,'Rendah','Audit hukum, daftar periksa kepatuhan, pelatihan menurunkan skor 6 ke 3.',8,'2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(14,14,3,3,9,'Rendah','Promosi, program insentif','Ada','Memadai',2,3,6,'Rendah','Kerja sama desa, program padat karya, pemasaran sosial menurunkan skor 9 ke 6.',6,'2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(15,15,2,2,4,'Rendah','SOP penggantian, pengendalian mutu','Ada','Memadai',1,2,2,'Rendah','Pelatihan teknisi, supervisi, mekanisme umpan balik menurunkan skor 4 ke 2.',6,'2026-10-05 11:24:37','seed',NULL,NULL),(16,16,3,3,9,'Rendah','Penjadwalan pemeliharaan, prioritas perbaikan','Ada','Memadai',2,3,6,'Rendah','Optimasi anggaran & kemitraan kontraktor eksternal menurunkan skor 9 ke 6.',4,'2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(17,17,3,5,15,'Sedang','(belum ada kontrol formal untuk rekonsiliasi angka RKAP)','Tidak','Belum Memadai',3,3,9,'Rendah','Rencana: rekonsiliasi antar slide + validasi asumsi pelanggan/efisiensi sebelum tahap evaluasi final.',1,'2026-10-05 11:24:37','draft-manrisk','2026-10-05 17:28:44','migrasi_06'),(18,18,2,5,10,'Rendah','Pengawasan SPI, dokumentasi pengadaan','Ada','Memadai',2,2,4,'Rendah','Rencana: e-procurement wajib, standar spesifikasi, tegak lurus pengawasan (mengurangi skor 10 ke 4).',8,'2026-10-05 11:24:37','draft-manrisk','2026-10-05 17:28:44','migrasi_06'),(19,19,3,4,12,'Sedang','Pengawasan internal berjalan','Ada','Memadai',3,2,6,'Rendah','Rencana: kewajiban tindak lanjut berjangka + eskalasi otomatis (mengurangi skor 12 ke 6).',8,'2026-10-05 11:24:37','draft-manrisk','2026-10-05 17:28:44','migrasi_06'),(20,20,3,3,9,'Rendah','Rapat koordinasi rutin, laporan manual tersedia','Ada','Memadai',2,3,6,'Rendah','Rencana: integrasi data + dashboard keputusan (mengurangi skor 9 ke 6).',1,'2026-10-05 11:24:37','draft-manrisk','2026-10-05 17:28:44','migrasi_06'),(21,21,3,2,6,'Rendah','(belum ada proses formal identifikasi peluang)','Tidak','Belum Memadai',3,1,3,'Rendah','Rencana: survei pelanggan Pamsimas + program efisiensi digital (mengurangi skor 6 ke 3).',1,'2026-10-05 11:24:37','draft-manrisk','2026-10-05 17:28:44','migrasi_06');
/*!40000 ALTER TABLE `mri_analisa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mri_evaluasi`
--

DROP TABLE IF EXISTS `mri_evaluasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  CONSTRAINT `fk_eval_register` FOREIGN KEY (`id_register`) REFERENCES `mri_register` (`id_register`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mri_evaluasi`
--

LOCK TABLES `mri_evaluasi` WRITE;
/*!40000 ALTER TABLE `mri_evaluasi` DISABLE KEYS */;
INSERT INTO `mri_evaluasi` VALUES (1,1,1,12,'Sedang','Level Tinggi (10-15): penanganan prioritas, direksi monitor','Treat','Kontrol pemeliharaan sudah ada namun ketersediaan suku cadang menjadi pengungkit utama.',0,NULL,'2026-10-05',1,'Administrator','2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(2,2,2,8,'Rendah','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor','Kontrol pengawasan kualitas efektif menurunkan skor ke Rendah pada sisa risiko.',0,NULL,'2026-10-05',1,'Administrator','2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(3,3,3,16,'Tinggi','Level Kritis (16-25): tindakan segera, notifikasi direksi','Escalate','Skor sisa masih Tinggi (12) meski kontrol ada. Butuh keputusan alokasi anggaran segmentasi DMA di tingkat direksi.',1,'Direktur','2026-10-05',1,'Administrator','2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(4,4,4,9,'Rendah','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor','Penyebab bersifat struktural (kehilangan pelanggan); perlu pemantauan pertumbuhan SR bulanan.',0,NULL,'2026-10-05',1,'Administrator','2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(5,5,5,16,'Tinggi','Level Kritis (16-25): tindakan segera, notifikasi direksi','Escalate','Skor sisa masih Tinggi (12). Dampak langsung ke laba bersih dan likuiditas — prioritas tertinggi RKAP 2027.',1,'Direktur','2026-10-05',1,'Administrator','2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(6,6,6,12,'Sedang','Level Tinggi (10-15): penanganan prioritas, direksi monitor','Treat','Kenaikan harga energi di luar kendali; pengungkit utama adalah efisiensi produksi.',0,NULL,'2026-10-05',1,'Administrator','2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(7,7,7,9,'Rendah','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor','Monitoring progres investasi cukup sebagai kendali tahap ini.',0,NULL,'2026-10-05',1,'Administrator','2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(8,8,8,6,'Rendah','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor','Dampak tinggi namun kemungkinan rendah; pemantauan keamanan cukup.',0,NULL,'2026-10-05',1,'Administrator','2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(9,9,9,6,'Rendah','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor','Risiko sedang, ditangani melalui perencanaan pelatihan tahunan.',0,NULL,'2026-10-05',1,'Administrator','2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(10,10,10,8,'Rendah','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor','Dampak tinggi (korban jiwa) meski kemungkinan rendah — wajib dipantau rutin tanpa eskalasi.',0,NULL,'2026-10-05',1,'Administrator','2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(11,11,11,9,'Rendah','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor','Risiko pasar pada unit usaha; dipantau bersama laporan penjualan bulanan.',0,NULL,'2026-10-05',1,'Administrator','2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(12,12,12,10,'Rendah','Level Tinggi (10-15): penanganan prioritas, direksi monitor','Treat','Risiko iklim dengan dampak produksi besar; rencana tanggap darurat perlu diuji sebelum musim kemarau.',0,NULL,'2026-10-05',1,'Administrator','2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(13,13,13,6,'Rendah','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor','Kepatuhan berjalan; tinjauan regulasi berkala cukup.',0,NULL,'2026-10-05',1,'Administrator','2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(14,14,14,9,'Rendah','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor','Penurunan SR bersifat tren pasar; dipantau tiap triwulan.',0,NULL,'2026-10-05',1,'Administrator','2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(15,15,15,4,'Rendah','Level Rendah (1-5): diterima, monitor rutin','Accept','Skor sisa Rendah (2); cukup dipantau lewat mekanisme umpan balik pelanggan.',0,NULL,'2026-10-05',1,'Administrator','2026-10-05 11:24:37','seed',NULL,NULL),(16,16,16,9,'Rendah','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor','Anggaran pemeliharaan sudah dialokasikan Rp 157,8 juta; perlu penjadwalan bulanan.',0,NULL,'2026-10-05',1,'Administrator','2026-10-05 11:24:37','seed','2026-10-05 17:28:44','migrasi_06'),(17,17,17,15,'Sedang','Level Tinggi (10-15): penanganan prioritas, direksi monitor','Treat','DRAFT. Ketidakkonsistenan angka RKAP antar slide belum tertutup; perlu rekonsiliasi sebelum evaluasi final.',0,NULL,'2026-10-05',1,'draft-manrisk','2026-10-05 11:24:37','draft-manrisk','2026-10-05 17:28:44','migrasi_06'),(18,18,18,10,'Rendah','Level Tinggi (10-15): penanganan prioritas, direksi monitor','Treat','DRAFT. Tekanan target investasi menaikkan risiko penyimpangan pengadaan.',0,NULL,'2026-10-05',1,'draft-manrisk','2026-10-05 11:24:37','draft-manrisk','2026-10-05 17:28:44','migrasi_06'),(19,19,19,12,'Sedang','Level Tinggi (10-15): penanganan prioritas, direksi monitor','Treat','DRAFT. Tidak ada mekanisme eskalasi tindak lanjut temuan.',0,NULL,'2026-10-05',1,'draft-manrisk','2026-10-05 11:24:37','draft-manrisk','2026-10-05 17:28:44','migrasi_06'),(20,20,20,9,'Rendah','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor','DRAFT. Laporan manual masih memadai namun tanpa jejak audit keputusan.',0,NULL,'2026-10-05',1,'draft-manrisk','2026-10-05 11:24:37','draft-manrisk','2026-10-05 17:28:44','migrasi_06'),(21,21,21,6,'Rendah','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor','DRAFT. Peluang dijadikan risiko agar terpantau; tipe Peluang (ISO 6.4.2).',0,NULL,'2026-10-05',1,'draft-manrisk','2026-10-05 11:24:37','draft-manrisk','2026-10-05 17:28:44','migrasi_06');
/*!40000 ALTER TABLE `mri_evaluasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mri_riwayat_skor`
--

DROP TABLE IF EXISTS `mri_riwayat_skor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  CONSTRAINT `fk_riwayat_register` FOREIGN KEY (`id_register`) REFERENCES `mri_register` (`id_register`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mri_riwayat_skor`
--

LOCK TABLES `mri_riwayat_skor` WRITE;
/*!40000 ALTER TABLE `mri_riwayat_skor` DISABLE KEYS */;
INSERT INTO `mri_riwayat_skor` VALUES (1,1,2027,1,12,8,'Sedang','Rendah','2026-10-05 11:24:37'),(2,2,2027,1,8,3,'Rendah','Rendah','2026-10-05 11:24:37'),(3,3,2027,1,16,12,'Tinggi','Sedang','2026-10-05 11:24:37'),(4,4,2027,1,9,6,'Rendah','Rendah','2026-10-05 11:24:37'),(5,5,2027,1,16,12,'Tinggi','Sedang','2026-10-05 11:24:37'),(6,6,2027,1,12,8,'Sedang','Rendah','2026-10-05 11:24:37'),(7,7,2027,1,9,6,'Rendah','Rendah','2026-10-05 11:24:37'),(8,8,2027,1,6,3,'Rendah','Rendah','2026-10-05 11:24:37'),(9,9,2027,1,6,3,'Rendah','Rendah','2026-10-05 11:24:37'),(10,10,2027,1,8,4,'Rendah','Rendah','2026-10-05 11:24:37'),(11,11,2027,1,9,6,'Rendah','Rendah','2026-10-05 11:24:37'),(12,12,2027,1,10,5,'Rendah','Rendah','2026-10-05 11:24:37'),(13,13,2027,1,6,3,'Rendah','Rendah','2026-10-05 11:24:37'),(14,14,2027,1,9,6,'Rendah','Rendah','2026-10-05 11:24:37'),(15,15,2027,1,4,2,'Rendah','Rendah','2026-10-05 11:24:37'),(16,16,2027,1,9,6,'Rendah','Rendah','2026-10-05 11:24:37'),(17,17,2027,1,15,9,'Sedang','Rendah','2026-10-05 11:24:37'),(18,18,2027,1,10,4,'Rendah','Rendah','2026-10-05 11:24:37'),(19,19,2027,1,12,6,'Sedang','Rendah','2026-10-05 11:24:37'),(20,20,2027,1,9,6,'Rendah','Rendah','2026-10-05 11:24:37'),(21,21,2027,1,6,3,'Rendah','Rendah','2026-10-05 11:24:37');
/*!40000 ALTER TABLE `mri_riwayat_skor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mri_laporan`
--

DROP TABLE IF EXISTS `mri_laporan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mri_laporan`
--

LOCK TABLES `mri_laporan` WRITE;
/*!40000 ALTER TABLE `mri_laporan` DISABLE KEYS */;
/*!40000 ALTER TABLE `mri_laporan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mri_perlakuan`
--

DROP TABLE IF EXISTS `mri_perlakuan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  CONSTRAINT `fk_perl_register` FOREIGN KEY (`id_register`) REFERENCES `mri_register` (`id_register`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mri_perlakuan`
--

LOCK TABLES `mri_perlakuan` WRITE;
/*!40000 ALTER TABLE `mri_perlakuan` DISABLE KEYS */;
INSERT INTO `mri_perlakuan` VALUES (1,1,1,'REDUCE','Kontrol pemeliharaan sudah ada, perlu diperkuat ketersediaan suku cadang.','Jadwal PM ketat, pompa cadangan siap, prosedur darurat operasional','Jumlah gangguan sumur bor per triwulan turun',NULL,'Pelaksana Teknik, Pemeliharaan','2027-01-01','2027-12-31',5,'BELUM','2026-10-05 11:24:37','seed',NULL,NULL),(2,2,2,'REDUCE','Kualitas air harus dijaga di bawah baku mutu.','Pengujian laboratorium rutin, koordinasi dengan DLHK','Tidak ada temuan baku mutu air',NULL,'Staf Teknik, Laboratorium','2027-01-01','2027-12-31',5,'BELUM','2026-10-05 11:24:37','seed',NULL,NULL),(3,3,3,'REDUCE','NRW adalah fokus efisiensi RKAP 2027.','Segmentasi DMA, penggantian pipa bertahap, monitoring SCADA','NRW per zona sumur bor turun terhadap baseline',157800000.00,'Pemeliharaan, Kontraktor eksternal','2027-01-01','2027-12-31',5,'BELUM','2026-10-05 11:24:37','seed',NULL,NULL),(4,4,4,'REDUCE','Pertumbuhan pelanggan perlu dijaga agar target SR tercapai.','Kerja sama desa, program padat karya, pemasaran sosial','Realisasi sambungan baru mendekati 938 SR',NULL,'Kabag Langganan','2027-01-01','2027-12-31',2,'BELUM','2026-10-05 11:24:37','seed',NULL,NULL),(5,5,5,'REDUCE','Piutang berdampak langsung ke laba bersih dan likuiditas.','Digitalisasi penagihan, kunjungan langsung, kolaborasi pemerintah desa, penutusan bertahap','Efisiensi penagihan mencapai 92,98%',NULL,'Keuangan, Penagihan','2027-01-01','2027-12-31',4,'BELUM','2026-10-05 11:24:37','seed',NULL,NULL),(6,6,6,'REDUCE','Biaya energi naik di luar kendali; efisiensi produksi adalah pengungkit.','Penyesuaian produksi motor pompa, efisiensi listrik sumur bor','Total beban usaha tidak melebihi Rp 8.964.652.306',NULL,'Keuangan, Produksi','2027-01-01','2027-12-31',4,'BELUM','2026-10-05 11:24:37','seed',NULL,NULL),(7,7,7,'REDUCE','Investasi Rp 2,3 M perlu terealisasi tepat waktu.','Monitoring progres investasi, koordinasi vendor, sistem peringatan dini','Realisasi investasi sesuai jadwal',2300000000.00,'Perencanaan, Pengadaan','2027-01-01','2027-12-31',6,'BELUM','2026-10-05 11:24:37','seed',NULL,NULL),(8,8,8,'REDUCE','Gangguan layanan digital berdampak pada penagihan.','Peningkatan kapasitas server, rencana pemulihan bencana, audit keamanan','Waktu operasional layanan digital >= 99%',NULL,'Staf Teknik, I T','2027-01-01','2027-12-31',7,'BELUM','2026-10-05 11:24:37','seed',NULL,NULL),(9,9,9,'REDUCE','Kompetensi 172 pegawai perlu dijaga.','Rencana pelatihan, pengembangan talenta, rekrutmen strategis','Tidak ada posisi kritis tanpa calon pengganti',NULL,'Umum, Personalia','2027-01-01','2027-12-31',3,'BELUM','2026-10-05 11:24:37','seed',NULL,NULL),(10,10,10,'REDUCE','Nihilnya kecelakaan kerja adalah target mutlak.','Audit K3, simulasi darurat, asuransi','Nihil kecelakaan kerja (zero accident)',NULL,'Umum, K3','2027-01-01','2027-12-31',3,'BELUM','2026-10-05 11:24:37','seed',NULL,NULL),(11,11,11,'REDUCE','Penjualan AMDK menopang laba unit.','Strategi pemasaran, kemitraan, pengendalian mutu','Pendapatan AMDK mencapai Rp 3.014.730.000',NULL,'Manager Unit AMDK, Pemasaran','2027-01-01','2027-12-31',11,'BELUM','2026-10-05 11:24:37','seed',NULL,NULL),(12,12,12,'REDUCE','Kekeringan/banjir di luar kendali; kesiapan tanggap adalah pengungkit.','Rencana tanggap darurat, alternatif sumber air, koordinasi BMKG','Tidak ada gangguan distribusi akibat cuaca ekstrem',NULL,'Produksi, Pemeliharaan','2027-01-01','2027-12-31',5,'BELUM','2026-10-05 11:24:37','seed',NULL,NULL),(13,13,13,'REDUCE','Kepatuhan harus dijaga agar bebas sanksi.','Audit hukum, daftar periksa kepatuhan, pelatihan','Nihil temuan kepatuhan yang berulang',NULL,'S P I','2027-01-01','2027-12-31',7,'BELUM','2026-10-05 11:24:37','seed',NULL,NULL),(14,14,14,'REDUCE','Target sambungan baru adalah motor pertumbuhan pelanggan.','Kerja sama desa, program padat karya, pemasaran sosial','Realisasi 938 sambungan baru',NULL,'Langganan','2027-01-01','2027-12-31',2,'BELUM','2026-10-05 11:24:37','seed',NULL,NULL),(15,15,15,'REDUCE','Kesalahan proses penggantian meter berdampak pada pencatatan.','Pelatihan teknisi, supervisi, mekanisme umpan balik','727 penggantian meter tanpa keberatan pelanggan',NULL,'Langganan','2027-01-01','2027-12-31',2,'BELUM','2026-10-05 11:24:37','seed',NULL,NULL),(16,16,16,'REDUCE','Pemeliharaan jaringan menjaga NRW dan keandalan distribusi.','Optimasi anggaran, kemitraan kontraktor eksternal','Kebocoran meningkat dapat dicegah dalam anggaran Rp 157,8 juta',157800000.00,'Pemeliharaan, Kontraktor','2027-01-01','2027-12-31',5,'BELUM','2026-10-05 11:24:37','seed',NULL,NULL),(17,17,17,'REDUCE','Selisih angka antar slide harus tertutup agar dasar keputusan andal.','Rekonsiliasi angka RKAP antar slide dengan worksheet keuangan; validasi asumsi pelanggan & efisiensi sebelum target dikunci','status_rekonsiliasi menjadi SELESAI untuk seluruh objektif',NULL,'Keuangan, Perencanaan, Direktur','2026-10-05','2026-12-31',4,'DALAM_PROSES','2026-10-05 11:24:37','draft-manrisk',NULL,NULL),(18,18,18,'REDUCE','Tekanan target menaikkan risiko penyimpangan; kontrol pengadaan diperkuat.','E-procurement wajib, standar spesifikasi pengadaan, tegak lurus pengawasan SPI pada pengadaan bernilai besar','Nihil temuan pengadaan pada periode berjalan',NULL,'S P I, Perencanaan','2027-01-01','2027-12-31',7,'BELUM','2026-10-05 11:24:37','draft-manrisk',NULL,NULL),(19,19,19,'REDUCE','Tindak lanjut temuan berulang menandakan lemahnya kewajiban PIC.','Kewajiban tindak lanjut berjangka per PIC + eskalasi otomatis bila lewat tenggat','Seluruh temuan tertutup dalam periode berjalan',NULL,'S P I, seluruh bagian','2027-01-01','2027-12-31',7,'BELUM','2026-10-05 11:24:37','draft-manrisk',NULL,NULL),(20,20,20,'REDUCE','Keputusan berbasis data menurunkan risiko keputusan keliru.','Integrasi data antar sistem + dashboard keputusan dengan jejak audit versi','Keputusan strategis memiliki jejak data & persetujuan',NULL,'I T, Umum, Direktur','2027-01-01','2027-12-31',7,'BELUM','2026-10-05 11:24:37','draft-manrisk',NULL,NULL),(21,21,21,'REDUCE','Tipe risiko PELUANG: mengurangi kemungkinan peluang terlewat dengan mekanisme identifikasi formal (ISO 6.4.2 memandang ancaman dan peluang sebagai risiko).','Survei potensi pelanggan wilayah Pamsimas + program efisiensi berbasis digitalisasi','Pendapatan potensial dari peluang tercatat dan ditugaskan PIC',NULL,'Langganan, I T, Direktur','2027-01-01','2027-12-31',2,'BELUM','2026-10-05 11:24:37','draft-manrisk',NULL,NULL);
/*!40000 ALTER TABLE `mri_perlakuan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mri_monitoring`
--

DROP TABLE IF EXISTS `mri_monitoring`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  CONSTRAINT `fk_mon_register` FOREIGN KEY (`id_register`) REFERENCES `mri_register` (`id_register`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mri_monitoring`
--

LOCK TABLES `mri_monitoring` WRITE;
/*!40000 ALTER TABLE `mri_monitoring` DISABLE KEYS */;
/*!40000 ALTER TABLE `mri_monitoring` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mri_kri`
--

DROP TABLE IF EXISTS `mri_kri`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  CONSTRAINT `fk_kri_register` FOREIGN KEY (`id_register`) REFERENCES `mri_register` (`id_register`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mri_kri`
--

LOCK TABLES `mri_kri` WRITE;
/*!40000 ALTER TABLE `mri_kri` DISABLE KEYS */;
INSERT INTO `mri_kri` VALUES (1,1,1,4,2027,1,'Jumlah gangguan pompa',NULL,'Per bulan','kasus',NULL,'Belum Diukur','Target materi: \"Minimal\" (Ringkasan MR §10.2) — angka batas spesifik belum ditetapkan. Diukur sebagai jumlah kejadian per bulan. Risiko terkait: IJT-OPR-01 Gangguan 25 sumur bor.','2026-10-05 11:26:26','seed','2026-10-05 09:33:11','sistem'),(2,1,1,4,2027,1,'Frekuensi outage',2.0000,'Per bulan','kali/bulan',NULL,'Belum Diukur',NULL,'2026-10-05 11:26:26','seed',NULL,NULL),(3,3,1,4,2027,1,'Tingkat NRW',NULL,'Per bulan','persen',NULL,'Belum Diukur','Target materi: \"Sesuai target\" (Ringkasan MR §10.2) — ANGKA TARGET NRW BELUM DINYATAKAN dalam paparan RKAP 2027. Berkaitan dengan mri_objektif id 21 yang juga ber-NULL. Risiko terkait: IJT-OPR-03 Kehilangan air tinggi (NRW).','2026-10-05 11:26:26','seed',NULL,NULL),(4,5,1,3,2027,1,'Piutang ragu-ragu',5.0000,'Per triwulan','persen',NULL,'Belum Diukur','Target materi: < 5% (Ringkasan MR §10.2). Risiko terkait: IJT-KEU-01 Piutang tak tertagih tinggi.','2026-10-05 11:26:26','seed',NULL,NULL),(5,8,1,2,2027,1,'Downtime sistem IT',4.0000,'Per insiden','jam',NULL,'Belum Diukur','Target materi: < 4 jam (Ringkasan MR §10.2). Risiko terkait: IJT-IT-01 Gangguan sistem billing/IT.','2026-10-05 11:26:26','seed',NULL,NULL),(6,2,1,6,2027,1,'Jumlah keluhan pelanggan',10.0000,'Per bulan','persen penurunan',NULL,'Belum Diukur','Target materi: Turun 10% (Ringkasan MR §10.2) terhadap periode sebelumnya. Risiko terkait: IJT-OPR-02 Kualitas air tidak memenuhi standar (akibat: keluhan pelanggan meningkat).','2026-10-05 11:26:26','seed',NULL,NULL);
/*!40000 ALTER TABLE `mri_kri` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mri_objektif`
--

DROP TABLE IF EXISTS `mri_objektif`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mri_objektif`
--

LOCK TABLES `mri_objektif` WRITE;
/*!40000 ALTER TABLE `mri_objektif` DISABLE KEYS */;
INSERT INTO `mri_objektif` VALUES (1,2027,3,NULL,2,1,'Total Pendapatan Usaha',22381036762.00,'Rp',22213650537.00,'Usulan RKAP 2027','SELESAI','',1,'2026-10-05 11:19:58','seed','2026-10-05 18:08:02','dicky'),(2,2027,3,NULL,2,1,'Total Beban Usaha',8964652306.00,'Rp',9765284776.00,'Usulan RKAP 2027','SELESAI','',1,'2026-10-05 11:19:58','seed',NULL,NULL),(3,2027,3,NULL,2,1,'Laba Kotor',13419984456.00,'Rp',12448365761.00,'Usulan RKAP 2027','BELUM','Slide 13 mencatat 13.349.379.456 — selisih Rp 70.605.000. Selisih ini turunan dari selisih Pendapatan Usaha (id 1), karena Total Beban Usaha identik di semua slide.',1,'2026-10-05 11:19:58','seed',NULL,NULL),(4,2027,3,NULL,2,1,'Beban Umum & Administrasi',10613952106.00,'Rp',9927086691.00,'Usulan RKAP 2027','SELESAI','Nilai konsisten di Slide 9 dan 13.',1,'2026-10-05 11:19:58','seed',NULL,NULL),(5,2027,3,NULL,2,1,'Laba Operasional',2735427350.00,'Rp',2521279070.00,'Usulan RKAP 2027','SELESAI','Nilai konsisten di Slide 9 dan 13.',1,'2026-10-05 11:19:58','seed',NULL,NULL),(6,2027,3,NULL,2,1,'Laba Sebelum Pajak',2986299563.00,'Rp',2710840819.00,'Usulan RKAP 2027','SELESAI','Nilai konsisten di Slide 9 dan 13.',1,'2026-10-05 11:19:58','seed',NULL,NULL),(7,2027,3,NULL,2,1,'Pajak Kini (PPh 29)',355052000.00,'Rp',289927000.00,'Usulan RKAP 2027','SELESAI','Nilai konsisten di Slide 9 dan 13.',1,'2026-10-05 11:19:58','seed',NULL,NULL),(8,2027,3,NULL,2,1,'Laba Bersih',2511247563.00,'Rp',2300913819.00,'Usulan RKAP 2027','SELESAI','ANGKA ACUAN UTAMA. Nilai konsisten di Slide 9, 10 (Laba Tahun Berjalan), 13, dan 19.',1,'2026-10-05 11:19:58','seed',NULL,NULL),(9,2027,3,NULL,2,1,'Total Aset',58153215439.86,'Rp',NULL,'Usulan RKAP 2027','SELESAI','Baseline 2026 tidak tersedia di materi.',1,'2026-10-05 11:19:58','seed',NULL,NULL),(10,2027,3,NULL,2,1,'Jumlah Ekuitas',52583469090.04,'Rp',NULL,'Usulan RKAP 2027','SELESAI','Baseline 2026 tidak tersedia di materi.',1,'2026-10-05 11:19:58','seed',NULL,NULL),(11,2027,3,NULL,2,1,'ROE (Return on Equity)',4.78,'persen',NULL,'Usulan RKAP 2027','SELESAI','Kategori \"Cukup\" (3%-<7%) menurut skala yang disediakan materi.',1,'2026-10-05 11:19:58','seed',NULL,NULL),(12,2027,3,NULL,2,1,'ROI / ROA',4.32,'persen',NULL,'Usulan RKAP 2027','SELESAI','Kategori \"Cukup\" menurut skala materi.',1,'2026-10-05 11:19:58','seed',NULL,NULL),(13,2027,6,NULL,3,1,'Pelanggan Direkeningkan',19513.00,'pelanggan',20123.00,'Usulan RKAP 2027','SELESAI','Penurunan 610 pelanggan (-3,03%) terhadap 2026.',1,'2026-10-05 11:19:58','seed',NULL,NULL),(14,2027,6,NULL,3,1,'Sambungan Baru (SR)',938.00,'SR',1370.00,'Usulan RKAP 2027','SELESAI','Penurunan 432 SR (-31,5%) terhadap 2026 — penurunan tajam, potensi sumber risiko pendapatan.',1,'2026-10-05 11:19:58','seed',NULL,NULL),(15,2027,6,NULL,3,1,'Pola Konsumsi',13.43,'persen',13.07,'Usulan RKAP 2027','SELESAI','Peningkatan +0,36 poin (+2,68%).',1,'2026-10-05 11:19:58','seed',NULL,NULL),(16,2027,6,NULL,3,1,'Volume Air',3094733.00,'m3',3105987.00,'Usulan RKAP 2027','SELESAI','Penurunan 11.254 m3 (-0,36%).',1,'2026-10-05 11:19:58','seed',NULL,NULL),(17,2027,6,NULL,3,1,'Penggantian Meter',727.00,'unit',NULL,'Usulan RKAP 2027','SELESAI','Baseline 2026 tidak tersedia di materi.',1,'2026-10-05 11:19:58','seed',NULL,NULL),(18,2027,6,NULL,3,1,'Tera Meter',3821.00,'unit',NULL,'Usulan RKAP 2027','SELESAI','Baseline 2026 tidak tersedia di materi.',1,'2026-10-05 11:19:58','seed',NULL,NULL),(19,2027,6,NULL,3,1,'Efisiensi Penagihan',92.98,'persen',NULL,'Usulan RKAP 2027','SELESAI','Baseline 2026 tidak tersedia di materi.',1,'2026-10-05 11:19:58','seed',NULL,NULL),(20,2027,4,NULL,5,1,'Anggaran Pemeliharaan Pipa &amp; Jaringan',157800000.00,'Rp',160000000.00,'Usulan RKAP 2027','SELESAI','Tren bulanan dengan puncak pada bulan Juli. Cakupan jaringan transmisi-distribusi (bukan pipa pelanggan).',1,'2026-10-05 11:19:58','seed','2026-10-06 09:49:25','dicky'),(21,2027,4,NULL,5,1,'Penurunan NRW per zona sumur bor',NULL,'persen',NULL,'Usulan RKAP 2027','BELUM','TARGET BELUM DINYATAKAN SEBAGAI ANGKA dalam materi — hanya dinyatakan sebagai inisiatif. 25 sumur bor aktif; perlu ditetapkan target NRW numerik oleh Pemeliharaan sebelum tahap analisis. Inisiatif terkait: penyesuaian produksi motor pompa (efisiensi listrik), pengangkatan rutin SB Locare tiap 3 bulan, pengurangan jaringan ACP (Pertigaan Nangkaan).',1,'2026-10-05 11:19:58','seed',NULL,NULL),(22,2027,5,NULL,6,1,'Total Rencana Investasi',2300000000.00,'Rp',NULL,'Usulan RKAP 2027','SELESAI','Rp 2,30 Miliar. Alokasi: Instalasi T&D Rp935,2jt (41%), Pompa Rp523,3jt (23%), Aktiva Tak Berwujud Rp500,0jt (22%), Inventaris/Perabot Rp246,7jt (11%), Peralatan Rp48,2jt (2%), Instalasi Sumber Rp46,7jt (2%). Tanpa alokasi tanah, IPA, bangunan/gedung, dan kendaraan.',1,'2026-10-05 11:19:58','seed',NULL,NULL),(23,2027,23,NULL,9,1,'Pendapatan AMDK (Ijen Water)',3014730000.00,'Rp',2635560000.00,'Usulan RKAP 2027','SELESAI','Pertumbuhan +14,39%.',1,'2026-10-05 11:19:58','seed',NULL,NULL),(24,2027,23,NULL,9,1,'Laba Bersih AMDK (Ijen Water)',191211489.00,'Rp',262849512.00,'Usulan RKAP 2027','SELESAI','',1,'2026-10-05 11:19:58','seed','2026-10-05 18:08:26','dicky'),(25,2027,1,NULL,1,1,'Dividen/PAD yang harus disetor (Proyeksi 2028)',803599220.00,'Rp',NULL,'Usulan RKAP 2027','SELESAI','32% dari Laba Bersih 2027 (Rp 2.511.247.563). PENTING: persentase yang tercetak di slide (Cadangan 20%, K3/Investasi 35%, Tantiem 5%, Lainnya 20%, Dividen 40%) menjumlah 120% dan tidak cocok dengan nilai rupiah (20%/28%/4%/16%/32% = 100%). Nilai rupiah konsisten dan dijumlahkan tepat ke Laba Bersih — dipakai sebagai acuan.',1,'2026-10-05 11:19:58','seed',NULL,NULL),(26,2027,3,NULL,2,1,'Jumlah Rekening (rata-rata)',230504.00,'rekening',237543.00,'Usulan RKAP 2027','SELESAI','Penurunan 7.039 rekening (-2,96%).',1,'2026-10-05 11:19:58','seed',NULL,NULL),(27,2027,4,NULL,2,1,'Testing',10.00,'Rp',10.00,'Usulan RKAP 2027','BELUM','Testing',1,'2026-10-06 13:14:46','seed',NULL,NULL),(30,2027,3,NULL,2,1,'Arus Kas',1000.00,'Rp',999.00,'Usulan User','BELUM',NULL,1,'2026-10-06 08:35:55','dicky','2026-10-06 08:35:55','dicky');
/*!40000 ALTER TABLE `mri_objektif` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mri_lingkup`
--

DROP TABLE IF EXISTS `mri_lingkup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mri_lingkup`
--

LOCK TABLES `mri_lingkup` WRITE;
/*!40000 ALTER TABLE `mri_lingkup` DISABLE KEYS */;
INSERT INTO `mri_lingkup` VALUES (1,2027,1,NULL,'Penyusunan Risk Register Tahun Anggaran 2027','Seluruh proses bisnis Perumdam Ijen Tirta , mulai dari tata kelola perusahaan, keuangan, pelayanan, produksi, transmisi-distribusi, pengadaan, SDM, IT, AMDK, kepatuhan, hingga kejadian eksternal/K3','Wilayah organisasi Perumdam Ijen Tirta: 6 divisi/unit kerja (Umum, Keuangan, Pemeliharaan, Perencanaan, Langganan, S P I), 16 UPK, dan 1 unit AMDK. Periode: Tahun Anggaran 2027.','Struktur organisasi baru  Perumdam Ijen Tirta Bondowoso, RKAP 2027 telah disusun, 172 pegawai, keterbatasan anggaran investasi, sistem digitalisasi dalam pengembangan, risk appetite resmi belum ditetapkan secara formal.','Perubahan ketentuan air minum dan regulasi daerah, ketersediaan sumber air (25 sumur bor) yang dipengaruhi musim kemarau, biaya energi/listrik yang berfluktuasi, persaingan AMDK, ekspektasi layanan digital pelanggan','Nilai RKAP 2027 (Pendapatan Usaha Rp 22.381.036.762, Laba Bersih Rp 2.511.247.563); target efisiensi penagihan 92,98%; kriteria baku mutu air.','2026-10-05','Direksi, Kabag Keuangan, Kabag Umum, Kabag Pemeliharaan, Kabag Perencanaan, Kabag Langganan, Ketua SPI, Kepala UPK, Manager Unit AMDK','','','','','','','DRAFT','2026-10-05 11:19:58','seed','2026-10-30 09:17:17','dicky');
/*!40000 ALTER TABLE `mri_lingkup` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-30 16:27:11
