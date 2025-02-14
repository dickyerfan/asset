/*
 Navicat Premium Data Transfer

 Source Server         : DIE ArtS
 Source Server Type    : MySQL
 Source Server Version : 100418 (10.4.18-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : asset

 Target Server Type    : MySQL
 Target Server Version : 100418 (10.4.18-MariaDB)
 File Encoding         : 65001

 Date: 11/02/2025 12:23:42
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for neraca
-- ----------------------------
DROP TABLE IF EXISTS `neraca`;
CREATE TABLE `neraca`  (
  `id_neraca` int NOT NULL AUTO_INCREMENT,
  `tahun_neraca` int NOT NULL,
  `kategori` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `akun` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nilai_neraca` bigint NULL DEFAULT NULL,
  `posisi` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `no_neraca` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `modified_at` datetime NULL DEFAULT NULL,
  `modified_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_neraca`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 71 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of neraca
-- ----------------------------
INSERT INTO `neraca` VALUES (1, 2022, 'Aset Lancar', 'Kas dan Bank', 3643150507, '1', '1.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (2, 2022, 'Aset Lancar', 'Deposito', 0, '1', '1.1.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (3, 2022, 'Aset Lancar', 'Piutang Usaha', 3575307119, '1', '1.2', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (4, 2022, 'Aset Lancar', 'Akm Kerugian Piutang Usaha', -600697269, '1', '1.3', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (5, 2022, 'Aset Lancar', 'Piutang Non Usaha', 293066190, '1', '1.4', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (6, 2022, 'Aset Lancar', 'Persediaan', 5356406998, '1', '1.5', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (7, 2022, 'Aset Lancar', 'Penurunan Nilai Persediaan', -122711214, '1', '1.5.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (8, 2022, 'Aset Lancar', 'Pembayaran Dimuka', 177143200, '1', '1.7', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (9, 2022, 'Aset Lancar', 'Pajak Pertambahan Nilai Dimuka', 14026345, '1', '1.8', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (10, 2022, 'Aset Tidak Lancar', 'Aset Tetap', 78032499901, '1', '2.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (11, 2022, 'Aset Tidak Lancar', 'Akm Depresiasi Aset Tetap', -47769982269, '1', '2.2', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (12, 2022, 'Aset Tidak Lancar', 'Aset Tetap Dikerjasamakan', 0, '1', '2.3', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (13, 2022, 'Aset Tidak Lancar', 'Aset Tetap Dalam Penyelesaian', 3625315419, '1', '2.4', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (14, 2022, 'Aset Tidak Lancar', 'Aset Tidak Berwujud', 440031941, '1', '2.5', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (15, 2022, 'Aset Tidak Lancar', 'Akm Amortisasi Aset Tidak Berwujud', 0, '1', '2.5.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (16, 2022, 'Aset Tidak Lancar', 'Aset Pajak Tangguhan', 0, '1', '2.6', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (17, 2022, 'Liabilitas Jangka Pendek', 'Utang Usaha', 1853700, '2', '3.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (18, 2022, 'Liabilitas Jangka Pendek', 'Utang Non Usaha', 938072075, '2', '3.2', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (19, 2022, 'Liabilitas Jangka Pendek', 'Biaya Yang Masih Harus Dibayar', 200649599, '2', '3.3', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (20, 2022, 'Liabilitas Jangka Pendek', 'Utang Pajak', 643740314, '2', '3.4', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (21, 2022, 'Liabilitas Jangka Pendek', 'Liabilitas Imbalan Pasca Kerja Dapenma', 130443768, '2', '3.5', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (22, 2022, 'Liabilitas Jangka Pendek', 'Liabilitas Imbalan Pasca Kerja', 0, '2', '3.6', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (23, 2022, 'Liabilitas Jangka Pendek', 'Utang Jangka Pendek Lainnya', 0, '2', '3.7', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (24, 2022, 'Liabilitas Jangka Panjang', 'Liabilitas Imbalan Pasca Kerja Dapenma (pj)', 0, '2', '4.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (25, 2022, 'Liabilitas Jangka Panjang', 'Liabilitas Imbalan Pasca Kerja', 0, '2', '4.2', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (26, 2022, 'Liabilitas Jangka Panjang', 'Liabilitas Pajak Tanggguhan', 0, '2', '4.3', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (27, 2022, 'Liabilitas Jangka Panjang', 'Kewajiban Lain-lain', 967741612, '2', '4.3.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (28, 2022, 'Ekuitas', 'Penyertaan Pemda Yang Dipisahkan', 19937782363, '3', '5.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (29, 2022, 'Ekuitas', 'Penyertaan Pemerintah Yang Belum Ditetapkan Status', 26391554720, '3', '5.2', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (30, 2022, 'Ekuitas', 'Modal Hibah', 581571100, '3', '5.2.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (31, 2022, 'Ekuitas', 'Cadangan Umum', 747574547, '3', '5.3', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (32, 2022, 'Ekuitas', 'Cadangan Bertujuan', 0, '3', '5.4', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (33, 2022, 'Ekuitas', 'Pengukuran Kembali Imbalan Paska Kerja', 1190952276, '3', '5.4.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (34, 2022, 'Ekuitas', 'Akm Kerugian Tahun Lalu', -8156184843, '3', '5.4.2', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (35, 2022, 'Ekuitas', 'Laba Rugi Tahun Berjalan', 3087805637, '3', '5.5', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (36, 2023, 'Aset Lancar', 'Kas dan Bank', 2943448238, '1', '1.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (37, 2023, 'Aset Lancar', 'Deposito', 500000000, '1', '1.1.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (38, 2023, 'Aset Lancar', 'Piutang Usaha', 2618506990, '1', '1.2', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (39, 2023, 'Aset Lancar', 'Akm Kerugian Piutang Usaha', -395410927, '1', '1.3', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (40, 2023, 'Aset Lancar', 'Piutang Non Usaha', 248849790, '1', '1.4', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (41, 2023, 'Aset Lancar', 'Persediaan', 4620803595, '1', '1.5', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (42, 2023, 'Aset Lancar', 'Penurunan Nilai Persediaan', -181101495, '1', '1.5.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (43, 2023, 'Aset Lancar', 'Pembayaran Dimuka', 26441950, '1', '1.7', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (44, 2023, 'Aset Lancar', 'Pajak Pertambahan Nilai Dimuka', 0, '1', '1.8', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (45, 2023, 'Aset Tidak Lancar', 'Aset Tetap', 84649008297, '1', '2.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (46, 2023, 'Aset Tidak Lancar', 'Akm Depresiasi Aset Tetap', -51291849005, '1', '2.2', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (47, 2023, 'Aset Tidak Lancar', 'Aset Tetap Dikerjasamakan', 0, '1', '2.3', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (48, 2023, 'Aset Tidak Lancar', 'Aset Tetap Dalam Penyelesaian', 3543128004, '1', '2.4', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (49, 2023, 'Aset Tidak Lancar', 'Aset Tidak Berwujud', 538493676, '1', '2.5', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (50, 2023, 'Aset Tidak Lancar', 'Akm Amortisasi Aset Tidak Berwujud', 0, '1', '2.5.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (51, 2023, 'Aset Tidak Lancar', 'Aset Pajak Tangguhan', 0, '1', '2.6', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (52, 2023, 'Liabilitas Jangka Pendek', 'Utang Usaha', 214192393, '2', '3.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (53, 2023, 'Liabilitas Jangka Pendek', 'Utang Non Usaha', 921446567, '2', '3.2', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (54, 2023, 'Liabilitas Jangka Pendek', 'Biaya Yang Masih Harus Dibayar', 184050301, '2', '3.3', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (55, 2023, 'Liabilitas Jangka Pendek', 'Utang Pajak', 458697232, '2', '3.4', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (56, 2023, 'Liabilitas Jangka Pendek', 'Liabilitas Imbalan Pasca Kerja Dapenma', 245983200, '2', '3.5', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (57, 2023, 'Liabilitas Jangka Pendek', 'Liabilitas Imbalan Pasca Kerja', 0, '2', '3.6', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (58, 2023, 'Liabilitas Jangka Pendek', 'Utang Jangka Pendek Lainnya', 0, '2', '3.7', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (59, 2023, 'Liabilitas Jangka Panjang', 'Liabilitas Imbalan Pasca Kerja Dapenma (pj)', 0, '2', '4.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (60, 2023, 'Liabilitas Jangka Panjang', 'Liabilitas Imbalan Pasca Kerja', 0, '2', '4.2', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (61, 2023, 'Liabilitas Jangka Panjang', 'Liabilitas Pajak Tanggguhan', 0, '2', '4.3', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (62, 2023, 'Liabilitas Jangka Panjang', 'Kewajiban Lain-lain', 1806361424, '2', '4.3.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (63, 2023, 'Ekuitas', 'Penyertaan Pemda Yang Dipisahkan', 21636075463, '3', '5.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (64, 2023, 'Ekuitas', 'Penyertaan Pemerintah Yang Belum Ditetapkan Status', 26289115900, '3', '5.2', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (65, 2023, 'Ekuitas', 'Modal Hibah', 581571100, '3', '5.2.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (66, 2023, 'Ekuitas', 'Cadangan Umum', 1210745393, '3', '5.3', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (67, 2023, 'Ekuitas', 'Cadangan Bertujuan', 0, '3', '5.4', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (68, 2023, 'Ekuitas', 'Pengukuran Kembali Imbalan Paska Kerja', -704098144, '3', '5.4.1', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (69, 2023, 'Ekuitas', 'Akm Kerugian Tahun Lalu', -6507117623, '3', '5.4.2', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');
INSERT INTO `neraca` VALUES (70, 2023, 'Ekuitas', 'Laba Rugi Tahun Berjalan', 1483295907, '3', '5.5', '1', '2025-02-10 15:25:00', 'Administrator', NULL, '');

SET FOREIGN_KEY_CHECKS = 1;
