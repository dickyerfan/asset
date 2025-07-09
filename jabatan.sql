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

 Date: 16/05/2025 13:15:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for jabatan
-- ----------------------------
DROP TABLE IF EXISTS `jabatan`;
CREATE TABLE `jabatan`  (
  `id_jabatan` int NOT NULL AUTO_INCREMENT,
  `nama_jabatan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_jabatan`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jabatan
-- ----------------------------
INSERT INTO `jabatan` VALUES (1, 'Kabag');
INSERT INTO `jabatan` VALUES (2, 'Ketua ');
INSERT INTO `jabatan` VALUES (3, 'Ka UPK');
INSERT INTO `jabatan` VALUES (4, 'Manager');
INSERT INTO `jabatan` VALUES (5, 'Kasubag');
INSERT INTO `jabatan` VALUES (6, 'Pelaksana Administrasi');
INSERT INTO `jabatan` VALUES (7, 'Pelaksana Teknik');
INSERT INTO `jabatan` VALUES (8, 'Pelaksana Pelayanan Pelanggan');
INSERT INTO `jabatan` VALUES (9, 'Anggota');
INSERT INTO `jabatan` VALUES (10, 'Staf Administrasi');
INSERT INTO `jabatan` VALUES (11, 'Staf Teknik');
INSERT INTO `jabatan` VALUES (13, 'Staf Pelayanan Pelanggan');
INSERT INTO `jabatan` VALUES (15, 'Direktur');
INSERT INTO `jabatan` VALUES (18, 'Staf Administrasi(Pembaca Meter)');
INSERT INTO `jabatan` VALUES (19, 'Staf Administrasi(Security)');
INSERT INTO `jabatan` VALUES (21, 'Wakil Manager');

SET FOREIGN_KEY_CHECKS = 1;
