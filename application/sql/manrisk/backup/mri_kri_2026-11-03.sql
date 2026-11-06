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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-03 23:11:58
