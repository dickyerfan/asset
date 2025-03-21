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

 Date: 20/03/2025 13:15:45
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ek_tera_meter
-- ----------------------------
DROP TABLE IF EXISTS `ek_tera_meter`;
CREATE TABLE `ek_tera_meter`  (
  `id_ek_tm` int NOT NULL AUTO_INCREMENT,
  `id_bagian` int NOT NULL,
  `jumlah_tm` int NULL DEFAULT NULL,
  `tgl_tm` date NULL DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `created_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `modified_at` datetime NULL DEFAULT NULL,
  `modified_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_ek_tm`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 81 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ek_tera_meter
-- ----------------------------
INSERT INTO `ek_tera_meter` VALUES (1, 15, 6, '2023-01-01', '2025-03-17 14:04:09', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (2, 7, 90, '2023-02-01', '2025-03-17 14:04:09', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (3, 9, 12, '2023-02-01', '2025-03-17 14:04:09', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (4, 10, 62, '2023-02-01', '2025-03-19 13:45:57', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (5, 11, 50, '2023-02-01', '2025-03-19 13:45:57', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (6, 12, 40, '2023-02-01', '2025-03-19 13:51:15', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (7, 13, 18, '2023-02-01', '2025-03-19 13:51:15', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (8, 15, 2, '2023-02-01', '2025-03-19 13:55:16', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (9, 16, 10, '2023-02-01', '2025-03-19 13:55:16', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (10, 17, 5, '2023-02-01', '2025-03-19 13:55:16', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (11, 7, 58, '2023-03-01', '2025-03-19 14:26:34', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (12, 9, 19, '2023-03-01', '2025-03-19 14:27:23', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (13, 10, 20, '2023-03-01', '2025-03-19 14:27:23', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (14, 11, 15, '2023-03-01', '2025-03-19 14:30:50', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (15, 12, 8, '2023-03-01', '2025-03-19 14:30:50', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (16, 14, 5, '2023-03-01', '2025-03-19 14:30:50', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (17, 15, 12, '2023-03-01', '2025-03-19 14:30:50', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (18, 16, 102, '2023-03-01', '2025-03-19 14:30:50', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (19, 17, 25, '2023-03-01', '2025-03-19 14:30:50', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (20, 22, 38, '2023-03-01', '2025-03-19 14:31:21', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (21, 7, 19, '2023-04-01', '2025-03-19 14:43:11', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (22, 10, 53, '2023-04-01', '2025-03-19 14:43:11', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (23, 11, 21, '2023-04-01', '2025-03-19 14:43:11', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (24, 15, 1, '2023-04-01', '2025-03-19 14:43:11', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (25, 16, 188, '2023-04-01', '2025-03-19 14:43:11', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (31, 7, 74, '2023-05-01', '2025-03-20 09:11:07', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (32, 9, 25, '2023-05-01', '2025-03-20 09:11:07', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (33, 10, 46, '2023-05-01', '2025-03-20 09:11:07', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (34, 11, 70, '2023-05-01', '2025-03-20 09:11:07', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (35, 12, 72, '2023-05-01', '2025-03-20 09:11:07', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (36, 13, 15, '2023-05-01', '2025-03-20 09:11:07', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (37, 14, 9, '2023-05-01', '2025-03-20 09:11:07', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (38, 15, 9, '2023-05-01', '2025-03-20 09:11:07', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (39, 17, 8, '2023-05-01', '2025-03-20 09:11:07', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (40, 19, 26, '2023-05-01', '2025-03-20 09:11:07', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (41, 22, 149, '2023-05-01', '2025-03-20 09:11:07', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (42, 7, 91, '2023-06-01', '2025-03-20 09:13:40', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (43, 9, 74, '2023-06-01', '2025-03-20 09:13:40', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (44, 10, 68, '2023-06-01', '2025-03-20 09:13:40', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (45, 11, 143, '2023-06-01', '2025-03-20 09:13:40', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (46, 12, 97, '2023-06-01', '2025-03-20 09:13:40', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (47, 13, 32, '2023-06-01', '2025-03-20 09:13:40', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (48, 14, 1, '2023-06-01', '2025-03-20 09:13:40', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (49, 15, 14, '2023-06-01', '2025-03-20 09:13:40', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (50, 17, 15, '2023-06-01', '2025-03-20 09:13:40', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (51, 18, 71, '2023-06-01', '2025-03-20 09:13:40', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (52, 19, 55, '2023-06-01', '2025-03-20 09:13:40', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (53, 20, 40, '2023-06-01', '2025-03-20 09:13:40', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (54, 22, 144, '2023-06-01', '2025-03-20 09:13:40', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (55, 7, 151, '2023-07-01', '2025-03-20 09:14:48', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (56, 9, 41, '2023-07-01', '2025-03-20 09:14:48', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (57, 11, 51, '2023-07-01', '2025-03-20 09:14:48', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (58, 12, 13, '2023-07-01', '2025-03-20 09:14:48', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (59, 13, 142, '2023-07-01', '2025-03-20 09:14:48', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (60, 14, 48, '2023-07-01', '2025-03-20 09:14:48', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (61, 15, 61, '2023-07-01', '2025-03-20 09:14:48', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (62, 17, 8, '2023-07-01', '2025-03-20 09:14:48', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (63, 19, 39, '2023-07-01', '2025-03-20 09:14:48', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (64, 20, 39, '2023-07-01', '2025-03-20 09:14:48', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (65, 21, 12, '2023-07-01', '2025-03-20 09:14:48', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (66, 7, 253, '2023-08-01', '2025-03-20 09:15:33', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (67, 9, 81, '2023-08-01', '2025-03-20 09:15:33', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (68, 14, 56, '2023-08-01', '2025-03-20 09:15:33', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (69, 15, 24, '2023-08-01', '2025-03-20 09:15:33', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (70, 18, 49, '2023-08-01', '2025-03-20 09:15:33', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (71, 19, 10, '2023-08-01', '2025-03-20 09:15:33', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (72, 20, 121, '2023-08-01', '2025-03-20 09:15:33', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (73, 21, 13, '2023-08-01', '2025-03-20 09:15:33', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (74, 7, 102, '2023-09-01', '2025-03-20 09:16:26', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (75, 14, 73, '2023-09-01', '2025-03-20 09:16:26', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (76, 15, 92, '2023-09-01', '2025-03-20 09:16:26', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (77, 20, 30, '2023-09-01', '2025-03-20 09:16:26', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (78, 7, 181, '2023-10-01', '2025-03-20 09:16:43', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (79, 14, 1, '2023-11-01', '2025-03-20 09:17:05', 'Bagian Pemeliharaan', NULL, '');
INSERT INTO `ek_tera_meter` VALUES (80, 22, 1, '2023-11-01', '2025-03-20 09:17:25', 'Bagian Pemeliharaan', NULL, '');

SET FOREIGN_KEY_CHECKS = 1;
