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

 Date: 16/05/2025 13:15:28
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for bagian
-- ----------------------------
DROP TABLE IF EXISTS `bagian`;
CREATE TABLE `bagian`  (
  `id_bagian` int NOT NULL AUTO_INCREMENT,
  `nama_bagian` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_bagian`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of bagian
-- ----------------------------
INSERT INTO `bagian` VALUES (1, 'Langganan');
INSERT INTO `bagian` VALUES (2, 'Umum');
INSERT INTO `bagian` VALUES (3, 'Keuangan');
INSERT INTO `bagian` VALUES (4, 'Pemeliharaan');
INSERT INTO `bagian` VALUES (5, 'Perencanaan');
INSERT INTO `bagian` VALUES (6, 'S P I');
INSERT INTO `bagian` VALUES (7, 'U P K');
INSERT INTO `bagian` VALUES (8, 'A M D K');

SET FOREIGN_KEY_CHECKS = 1;
