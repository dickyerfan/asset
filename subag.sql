/*
 Navicat Premium Data Transfer

 Source Server         : amdk
 Source Server Type    : MySQL
 Source Server Version : 100621 (10.6.21-MariaDB-0ubuntu0.22.04.2)
 Source Host           : 192.168.55.3:3306
 Source Schema         : amdk

 Target Server Type    : MySQL
 Target Server Version : 100621 (10.6.21-MariaDB-0ubuntu0.22.04.2)
 File Encoding         : 65001

 Date: 16/05/2025 13:15:54
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for subag
-- ----------------------------
DROP TABLE IF EXISTS `subag`;
CREATE TABLE `subag`  (
  `id_subag` int NOT NULL AUTO_INCREMENT,
  `nama_subag` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_subag`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 38 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of subag
-- ----------------------------
INSERT INTO `subag` VALUES (1, 'Langganan');
INSERT INTO `subag` VALUES (2, 'Penagihan');
INSERT INTO `subag` VALUES (3, 'Umum');
INSERT INTO `subag` VALUES (4, 'Administrasi');
INSERT INTO `subag` VALUES (5, 'Personalia');
INSERT INTO `subag` VALUES (6, 'Keuangan');
INSERT INTO `subag` VALUES (7, 'Kas');
INSERT INTO `subag` VALUES (8, 'Pembukuan');
INSERT INTO `subag` VALUES (9, 'Rekening');
INSERT INTO `subag` VALUES (10, 'Pemeliharaan');
INSERT INTO `subag` VALUES (11, 'Peralatan');
INSERT INTO `subag` VALUES (12, 'Perencanaan');
INSERT INTO `subag` VALUES (13, 'Pengawasan');
INSERT INTO `subag` VALUES (14, 'S P I');
INSERT INTO `subag` VALUES (15, 'A M D K');
INSERT INTO `subag` VALUES (16, 'I T');
INSERT INTO `subag` VALUES (17, 'Sukosari 1');
INSERT INTO `subag` VALUES (18, 'Maesan');
INSERT INTO `subag` VALUES (19, 'Tegalampel');
INSERT INTO `subag` VALUES (20, 'Tapen');
INSERT INTO `subag` VALUES (21, 'Prajekan');
INSERT INTO `subag` VALUES (22, 'Tlogosari');
INSERT INTO `subag` VALUES (23, 'Wringin');
INSERT INTO `subag` VALUES (24, 'Curahdami');
INSERT INTO `subag` VALUES (25, 'Tamanan');
INSERT INTO `subag` VALUES (26, 'Tenggarang');
INSERT INTO `subag` VALUES (27, 'Tamankrocok');
INSERT INTO `subag` VALUES (28, 'Wonosari');
INSERT INTO `subag` VALUES (29, 'Klabang');
INSERT INTO `subag` VALUES (30, 'Sukosari 2');
INSERT INTO `subag` VALUES (31, 'Bondowoso');
INSERT INTO `subag` VALUES (32, 'Quality Control');
INSERT INTO `subag` VALUES (34, 'Pemasaran');
INSERT INTO `subag` VALUES (35, 'Produksi');

SET FOREIGN_KEY_CHECKS = 1;
