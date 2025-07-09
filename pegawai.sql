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

 Date: 16/05/2025 12:59:30
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pegawai
-- ----------------------------
DROP TABLE IF EXISTS `pegawai`;
CREATE TABLE `pegawai`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_bagian` int NOT NULL,
  `id_subag` int NOT NULL,
  `id_jabatan` int NOT NULL,
  `nama` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `agama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status_pegawai` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nik` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `no_hp` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenkel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tmp_lahir` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_lahir` date NOT NULL,
  `tgl_masuk` date NOT NULL,
  `aktif` int NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_bagian`(`id_bagian` ASC) USING BTREE,
  INDEX `id_subag`(`id_subag` ASC) USING BTREE,
  INDEX `id_jabatan`(`id_jabatan` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 217 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pegawai
-- ----------------------------
INSERT INTO `pegawai` VALUES (1, 3, 6, 1, 'Rosida', 'Sukowiryo, Bondowoso', 'Islam', 'Karyawan Tetap', '12190030', '082302209147', 'Perempuan', 'Bondowoso', '1968-07-28', '1991-01-02', 0);
INSERT INTO `pegawai` VALUES (2, 2, 3, 15, 'April Ariestha Bhirawa', 'Perum Tamansari Indah Bondowoso', 'Islam', 'Karyawan Tetap', '', '085236165969', 'Laki-laki', 'Bondowoso', '1970-04-21', '1992-12-01', 1);
INSERT INTO `pegawai` VALUES (4, 6, 14, 2, 'Supriyadi', 'Klabang Bondowoso', 'Islam', 'Karyawan Tetap', '01592049', '085311048058', 'Laki-laki', 'Bondowoso', '1968-05-02', '1992-05-01', 0);
INSERT INTO `pegawai` VALUES (5, 1, 1, 1, 'Cipto Kusuma', 'Nangkaan Bondowoso', 'Islam', 'Karyawan Tetap', '05589009', '085203365470', 'Laki-laki', 'Banyuwangi', '1967-08-11', '1989-05-05', 0);
INSERT INTO `pegawai` VALUES (6, 2, 3, 1, 'Siti Nuraini', 'Sukowiryo Bondowoso', 'Islam', 'Karyawan Tetap', '01592045', '081336463122', 'Perempuan', 'Bojonegoro', '1968-04-23', '1992-05-01', 0);
INSERT INTO `pegawai` VALUES (9, 6, 14, 2, 'I Made Suarjaya', 'Jln. A. Yani Bondowoso', 'Hindu', 'Karyawan Tetap', '11292083', '08123456789', 'Laki-laki', 'Pujungan', '1972-06-20', '1992-06-20', 1);
INSERT INTO `pegawai` VALUES (10, 5, 12, 1, 'Mohammad Yunus Anis', 'Sukowiryo Bondowoso', 'Islam', 'Karyawan Tetap', '01995599', '08233510842', 'Laki-laki', 'Lumajang', '1972-01-25', '1995-05-19', 1);
INSERT INTO `pegawai` VALUES (13, 7, 22, 3, 'Sirajuddin', 'Prajekan', 'Islam', 'Karyawan Tetap', '31190018', '082331851007', 'Laki-laki', 'Bondowoso', '1968-11-10', '1990-02-02', 0);
INSERT INTO `pegawai` VALUES (14, 7, 31, 3, 'Sudarso', 'Sukowiryo Bondowoso', 'Islam', 'Karyawan Tetap', '05589007', '081336413514', 'Laki-laki', 'Situbondo', '1968-08-28', '1989-05-23', 0);
INSERT INTO `pegawai` VALUES (15, 7, 17, 3, 'Sucipno', 'Tegalampel', 'Islam', 'Karyawan Tetap', '31190022', '085234951008', 'Laki-laki', 'Bondowoso', '1968-07-13', '1990-01-02', 0);
INSERT INTO `pegawai` VALUES (16, 7, 18, 3, 'Juhaeni', 'Maesan', 'Islam', 'Karyawan Tetap', '11292069', '085236343743', 'Laki-laki', 'Bondowoso', '1968-05-25', '1992-12-01', 0);
INSERT INTO `pegawai` VALUES (17, 2, 4, 5, 'Suhendra Paratu', 'Petung Bondowoso', 'Islam', 'Karyawan Tetap', '01592047', '081217071969', 'Laki-laki', 'Tana Toraja', '1969-03-17', '1992-05-01', 0);
INSERT INTO `pegawai` VALUES (18, 7, 21, 3, 'Achmad Roedy Witarsa', 'Hos. Cokroaminoto', 'Islam', 'Karyawan Tetap', '04489005', '081336628789', 'Laki-laki', 'Bondowoso', '1969-03-29', '1989-04-08', 0);
INSERT INTO `pegawai` VALUES (19, 6, 14, 9, 'Teguh Imam Santuso', 'Tenggarang Bondowoso', 'Islam', 'Karyawan Tetap', '01101105', '082335555997', 'Laki-laki', 'Bondowoso', '1968-07-04', '2001-01-01', 0);
INSERT INTO `pegawai` VALUES (20, 7, 21, 3, 'Indrayati', 'Situbondo', 'Islam', 'Karyawan Tetap', '02191031', '085257799751', 'Perempuan', 'Manado', '1967-09-09', '1991-01-02', 0);
INSERT INTO `pegawai` VALUES (21, 2, 3, 19, 'Sundari', 'Nangkaan Bondowoso', 'Islam', 'Karyawan Tetap', '11012119', '085236139585', 'Laki-laki', 'Bondowoso', '1975-04-15', '2012-10-01', 1);
INSERT INTO `pegawai` VALUES (22, 7, 19, 3, 'Rudi Hasyim', 'Badean Bondowoso', 'Islam', 'Karyawan Tetap', '01403108', '085334597710', 'Laki-laki', 'Bondowoso', '1974-11-28', '2003-04-01', 1);
INSERT INTO `pegawai` VALUES (23, 2, 3, 1, 'Suwarna', 'Nangkaan Bondowoso', 'Islam', 'Karyawan Tetap', '01592055', '085338519245', 'Perempuan', 'Bondowoso', '1971-04-16', '1992-05-01', 1);
INSERT INTO `pegawai` VALUES (24, 1, 1, 1, 'Adityas Arief Witjaksono', 'Sekarputih Bondowoso', 'Islam', 'Karyawan Tetap', '01393089', '085257780909', 'Laki-laki', 'Pamekasan', '1971-12-23', '1993-03-01', 1);
INSERT INTO `pegawai` VALUES (25, 7, 30, 3, 'Sanur', 'Sukosari Bondowoso', 'Islam', 'Karyawan Tetap', '01993092', '085258547502', 'Laki-laki', 'Sumenep', '1972-04-21', '1993-09-01', 1);
INSERT INTO `pegawai` VALUES (26, 7, 20, 3, 'Siti Rosida', 'Prajekan Bondowoso', 'Islam', 'Karyawan Tetap', '02191033', '083854071977', 'Laki-laki', 'Prajekan', '1970-01-01', '1991-01-02', 1);
INSERT INTO `pegawai` VALUES (27, 3, 8, 5, 'Lilik Yuli Andayani', 'Sukowiryo Bondowoso', 'Islam', 'Karyawan Tetap', '02191032', '085235425022', 'Perempuan', 'Bondowoso', '1970-07-16', '1991-01-02', 1);
INSERT INTO `pegawai` VALUES (28, 3, 7, 5, 'Yulia', 'Dabasah Bondowoso', 'Islam', 'Karyawan Tetap', '02191035', '085236558772', 'Perempuan', 'Bondowoso', '1970-07-04', '1991-01-02', 1);
INSERT INTO `pegawai` VALUES (29, 2, 3, 5, 'Misiati', 'Sukowiryo Bondowoso', 'Islam', 'Karyawan Tetap', '11292078', '081233719959', 'Perempuan', 'Bondowoso', '1972-06-29', '1992-12-01', 1);
INSERT INTO `pegawai` VALUES (30, 2, 5, 5, 'Linda Anggraita', 'Maesan Bondowoso', 'Islam', 'Karyawan Tetap', '20117147', '085230685485', 'Perempuan', 'Bondowoso', '1992-07-04', '2017-01-02', 1);
INSERT INTO `pegawai` VALUES (31, 3, 6, 1, 'Dicky Erfan Septiono', 'Badean Bondowoso', 'Islam', 'Karyawan Tetap', '01410112', '0816591527', 'Laki-laki', 'Bondowoso', '1978-09-20', '2010-04-01', 1);
INSERT INTO `pegawai` VALUES (32, 4, 11, 5, 'Suwantono', 'Sukowiryo Bondowoso', 'Islam', 'Karyawan Tetap', '01592053', '085258176532', 'Laki-laki', 'Bondowoso', '1971-03-06', '1992-05-01', 1);
INSERT INTO `pegawai` VALUES (33, 7, 19, 6, 'Nuning Handayani', 'Bataan Bondowoso', 'Islam', 'Karyawan Tetap', '01592046', '081317174139', 'Laki-laki', 'Bondowoso', '1972-05-25', '1992-05-01', 1);
INSERT INTO `pegawai` VALUES (34, 7, 31, 6, 'Andrayani', 'Nangkaan Bondowoso', 'Islam', 'Karyawan Tetap', '01592044', '085258038337', 'Perempuan', 'Bondowoso', '1971-04-20', '1992-05-01', 1);
INSERT INTO `pegawai` VALUES (35, 7, 31, 3, 'Rudy Himawan', 'Curahdami Bondowoso', 'Islam', 'Karyawan Tetap', '11292068', '081249804300', 'Laki-laki', 'Kediri', '1971-04-20', '1992-12-01', 1);
INSERT INTO `pegawai` VALUES (36, 7, 22, 7, 'Fitriadi Suryono', 'Sukowiryo Bondowoso', 'Islam', 'Karyawan Tetap', '01592060', '085230266428', 'Laki-laki', 'Jember', '1971-11-21', '1992-05-01', 0);
INSERT INTO `pegawai` VALUES (37, 3, 9, 5, 'Yuliatin Jumariyah', 'Garahan Jember', 'Islam', 'Karyawan Tetap', '28895102', '085233763257', 'Perempuan', 'Jember', '1975-07-31', '1995-08-28', 1);
INSERT INTO `pegawai` VALUES (38, 4, 10, 1, 'Mohammad Rois', 'Curahdami Bondowoso', 'Islam', 'Karyawan Tetap', '11012118', '082330104146', 'Laki-laki', 'Bondowoso', '1978-04-18', '2012-10-01', 1);
INSERT INTO `pegawai` VALUES (39, 7, 18, 3, 'Rahmat Febri Eko Tanyono', 'Jember', 'Islam', 'Karyawan Tetap', '01403046', '085101229001', 'Laki-laki', 'Bojonegoro', '1979-02-17', '2003-04-01', 1);
INSERT INTO `pegawai` VALUES (40, 7, 23, 11, 'Saeful Anshori', 'Kademangan', 'Islam', 'Karyawan Tetap', '10414131', '082330340415', 'Laki-laki', 'Bondowoso', '1982-03-28', '2014-04-01', 1);
INSERT INTO `pegawai` VALUES (41, 7, 26, 3, 'Supangkat Harianto', 'Tegalampel', 'Islam', 'Karyawan Tetap', '20117144', '085330849697', 'Laki-laki', 'Malang', '1984-05-11', '2017-01-02', 1);
INSERT INTO `pegawai` VALUES (42, 7, 25, 3, 'Achmad Novi Patria Budiman', 'Tamansari Bondowoso', 'Islam', 'Karyawan Tetap', '10414125', '082316384438', 'Laki-laki', 'Bondowoso', '1975-11-12', '2014-04-01', 1);
INSERT INTO `pegawai` VALUES (43, 7, 24, 7, 'Rudi Heriyanto', 'Nangkaan Bondowoso', 'Islam', 'Karyawan Tetap', '01592058', '081217894120', 'Laki-laki', 'Bondowoso', '1970-05-24', '1992-05-01', 1);
INSERT INTO `pegawai` VALUES (44, 7, 26, 10, 'Titin Sri Murtinah', 'Koncer Bondowoso', 'Islam', 'Karyawan Tetap', '01493086', '085236039200', 'Perempuan', 'Malang', '1971-07-20', '1993-04-01', 1);
INSERT INTO `pegawai` VALUES (45, 7, 31, 8, 'Sulistyowati', 'Grujugan Bondowoso', 'Islam', 'Karyawan Tetap', '11012121', '085258559926', 'Perempuan', 'Bondowoso', '1981-11-21', '2012-10-01', 1);
INSERT INTO `pegawai` VALUES (46, 7, 18, 6, 'Sohibul Fadillah', 'Maesan Bondowoso', 'Islam', 'Karyawan Tetap', '20117146', '082233598935', 'Perempuan', 'Bondowoso', '1995-06-17', '2017-01-02', 1);
INSERT INTO `pegawai` VALUES (47, 7, 18, 6, 'Santuso', 'Sukowiryo Bondowoso', 'Islam', 'Karyawan Tetap', '11292080', '081336197752', 'Laki-laki', 'Bondowoso', '1967-08-26', '1992-12-01', 0);
INSERT INTO `pegawai` VALUES (48, 7, 17, 3, 'Fathorrasi', 'Badean Bondowoso', 'Islam', 'Karyawan Tetap', '10414134', '085259621069', 'Laki-laki', 'Bondowoso', '1973-01-24', '2014-04-01', 0);
INSERT INTO `pegawai` VALUES (49, 7, 27, 3, 'Sugijono', 'Maesan Bondowoso', 'Islam', 'Karyawan Tetap', '31190016', '085310333737', 'Laki-laki', 'Jember', '1968-02-23', '1990-01-31', 0);
INSERT INTO `pegawai` VALUES (50, 7, 31, 13, 'Erfan', 'Kotakulon Bondowoso', 'Islam', 'Karyawan Tetap', '01592062', '085258191743', 'Laki-laki', 'Bondowoso', '1968-08-14', '1992-05-01', 0);
INSERT INTO `pegawai` VALUES (51, 7, 31, 18, 'Fahmi Tri Andika', 'Kotakulon Bondowoso', 'Islam', 'Karyawan Tetap', '', '085259001165', 'Laki-laki', 'Bondowoso', '1990-09-25', '2024-11-01', 1);
INSERT INTO `pegawai` VALUES (52, 7, 18, 11, 'Rendra Septian', 'Nangkaan Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081382933339', 'Laki-laki', 'Bondowoso', '1994-09-04', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (53, 7, 22, 11, 'Ridwan Nurus Zuhur', 'Gebang Bondowoso', 'Islam', 'Karyawan Tetap', '28423169', '082237746469', 'Laki-laki', 'Bondowoso', '1995-09-13', '2023-04-28', 1);
INSERT INTO `pegawai` VALUES (54, 7, 31, 18, 'Dion  Dwi Sapta R', 'Kembang Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081249805006', 'Laki-laki', 'Sidoarjo', '1995-02-27', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (55, 7, 31, 11, 'Ilyas', 'Bondowoso', 'Islam', 'Karyawan Tetap', '11012114', '082336157245', 'Laki-laki', 'Bondowoso', '1976-09-21', '2012-10-01', 1);
INSERT INTO `pegawai` VALUES (56, 7, 19, 11, 'Mahmudi', 'Bondowoso', 'Islam', 'Karyawan Tetap', '11012115', '085257169200', 'Laki-laki', 'Bondowoso', '1969-07-05', '2012-10-01', 1);
INSERT INTO `pegawai` VALUES (57, 7, 31, 11, 'Audri Dwi Putra Nurilahi', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '089619600254', 'Laki-laki', 'Bondowoso', '2000-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (58, 7, 31, 11, 'Wijaya Kusuma', 'Bondowoso', 'Islam', 'Karyawan Tetap', '', '085236005991', 'Laki-laki', 'Bondowoso', '1990-01-01', '2023-04-28', 1);
INSERT INTO `pegawai` VALUES (59, 7, 31, 13, 'Ilham Kamil Adi Iskandar', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082228111207', 'Laki-laki', 'Bondowoso', '1985-10-18', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (60, 7, 31, 11, 'Anton Sujarwo', 'Bondowoso', 'Islam', 'Karyawan Tetap', '', '085259061773', 'Laki-laki', 'Bondowoso', '1990-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (61, 7, 31, 11, 'Moh Hadi Sutrisno', 'Curahdami Bondowoso', 'Islam', 'Karyawan Tetap', '', '085232903540', 'Laki-laki', 'Bondowoso', '1990-01-01', '2022-05-02', 1);
INSERT INTO `pegawai` VALUES (62, 3, 8, 10, 'Muhammad Deni Saputro', 'Grujugan Bondowoso', 'Islam', 'Karyawan Tetap', '11124190', '083117513423', 'Laki-laki', 'Bondowoso', '1999-11-18', '2024-11-01', 1);
INSERT INTO `pegawai` VALUES (63, 3, 8, 10, 'Somaya Dewantari', 'Dabasah Bondowoso', 'Islam', 'Karyawan Tetap', '02524188', '085232330042', 'Perempuan', 'Bondowoso', '1999-01-31', '2024-05-02', 1);
INSERT INTO `pegawai` VALUES (64, 3, 9, 10, 'Achmad Wahyu Dian P', 'Kademangan Bondowoso', 'Islam', 'Karyawan Tetap', '', '085230993424', 'Laki-laki', 'Bondowoso', '1994-07-14', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (65, 1, 1, 10, 'Bachtiar Cahya Nuangga', 'Sukosari Bondowoso', 'Islam', 'Karyawan Tetap', '', '085228134138', 'Laki-laki', 'Bondowoso', '1988-07-12', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (66, 1, 1, 10, 'Faradiela Sakti Ananda', 'Bondowoso', 'Islam', 'Karyawan Tetap', '', '085258999009', 'Laki-laki', 'Bondowoso', '1991-10-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (67, 6, 14, 9, 'Inaka Patria Farino', 'Dabasah Bondowoso', 'Islam', 'Karyawan Tetap', '', '083847782219', 'Laki-laki', 'Bondowoso', '1994-04-25', '2024-11-01', 1);
INSERT INTO `pegawai` VALUES (68, 6, 14, 9, 'Bagas Ridha Tria S', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082141601557', 'Laki-laki', 'Bondowoso', '1996-04-28', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (69, 5, 13, 5, 'Agus Ridwan Firdaus', 'Jember', 'Islam', 'Karyawan Tetap', '31119154', '085336516320', 'Laki-laki', 'Bondowoso', '1988-08-20', '2019-01-31', 1);
INSERT INTO `pegawai` VALUES (70, 5, 12, 5, 'Resty Ageng Permatasari', 'Bondowoso', 'Islam', 'Karyawan Tetap', '20117149', '082228101715', 'Perempuan', 'Jember', '1984-09-06', '2017-01-02', 1);
INSERT INTO `pegawai` VALUES (71, 5, 12, 10, 'Sonya Desiana Mangiri', 'Koncer Bondowoso', 'Islam', 'Karyawan Tetap', '', '082141841544', 'Perempuan', 'Bondowoso', '2000-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (72, 5, 13, 10, 'Ainun Febriana', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082228830285', 'Perempuan', 'Bondowoso', '2000-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (73, 7, 23, 3, 'Didik Ahmad Rafidi', 'Bondowoso', 'Islam', 'Karyawan Tetap', '10414129', '085234951484', 'Laki-laki', 'Bondowoso', '1981-09-08', '2014-04-01', 1);
INSERT INTO `pegawai` VALUES (74, 4, 10, 5, 'Taufiqurrahman', 'Klabang Bondowoso', 'Islam', 'Karyawan Tetap', '31119158', '082335338758', 'Laki-laki', 'Bondowoso', '1996-01-23', '2019-01-31', 1);
INSERT INTO `pegawai` VALUES (75, 7, 24, 11, 'Muhammad Hafi Anshori', 'Tamansari Bondowoso', 'Islam', 'Karyawan Tetap', '31119159', '082338733342', 'Laki-laki', 'Jember', '1990-09-11', '2019-03-11', 1);
INSERT INTO `pegawai` VALUES (76, 7, 21, 11, 'Rizal Akbar Rusmana', 'Tegalampel Bondowoso', 'Islam', 'Karyawan Tetap', '', '082231275038', 'Laki-laki', 'Bondowoso', '2000-01-01', '2022-05-02', 1);
INSERT INTO `pegawai` VALUES (77, 4, 10, 11, 'Haryo Ari Wibowo', 'Tamanan Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085291714958', 'Laki-laki', 'Jember', '1989-05-11', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (78, 2, 5, 10, 'Annur Darmawan', 'Bataan Bondowoso', 'Islam', 'Karyawan Tetap', '11124195', '081230695407', 'Laki-laki', 'Situbondo', '1996-01-18', '2024-11-01', 1);
INSERT INTO `pegawai` VALUES (79, 2, 4, 10, 'Vika Ardian Farikasari', 'Tegalampel Bondowoso', 'Islam', 'Karyawan Tetap', '11124196', '083847168808', 'Perempuan', 'Bondowoso', '2001-06-11', '2024-11-01', 1);
INSERT INTO `pegawai` VALUES (80, 7, 31, 11, 'Muhammad Budi Hartono', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082142113872', 'Laki-laki', 'Bondowoso', '1997-10-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (81, 2, 3, 19, 'Septa Ragiel P', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085961563373', 'Laki-laki', 'Bondowoso', '2000-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (82, 2, 3, 19, 'Adi Fitri Fauzi', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '087760295756', 'Laki-laki', 'Bondowoso', '1997-02-27', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (83, 2, 3, 19, 'M Nasir', 'Tlogosari Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082301708091', 'Laki-laki', 'Bondowoso', '1990-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (84, 2, 3, 10, 'Mohammad Handoko', 'Nangkaan Bondowoso', 'Islam', 'Karyawan Tetap', '01410111', '089685617475', 'Laki-laki', 'Bondowoso', '1969-04-06', '2010-04-01', 0);
INSERT INTO `pegawai` VALUES (85, 2, 3, 10, 'Fathor Rozyi', 'Bondowoso', 'Islam', 'Karyawan Tetap', '21823177', '085714928475', 'Laki-laki', 'Bondowoso', '1989-09-05', '2023-08-21', 1);
INSERT INTO `pegawai` VALUES (86, 7, 22, 11, 'Mohammad Sugeng Prayogo', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081259943704', 'Laki-laki', 'Bondowoso', '2002-08-10', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (87, 2, 3, 10, 'Hendi Hendra Laksya Utama', 'Kembang Bondowoso', 'Islam', 'Karyawan Tetap', '10414139', '085231315707', 'Laki-laki', 'Bondowoso', '1978-12-12', '2014-04-01', 0);
INSERT INTO `pegawai` VALUES (88, 2, 3, 10, 'Bayu Nur Sito Utomo', 'Kembang Bondowoso', 'Islam', 'Karyawan Tetap', '', '082332070150', 'Laki-laki', 'Bondowoso', '1991-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (89, 2, 3, 10, 'Dian Irfan Hanugerah', 'Kembang Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081916059888', 'Laki-laki', 'Bondowoso', '1995-07-26', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (90, 2, 3, 10, 'Mohamad Fajar Kurniawan', 'Jember', 'Islam', 'Karyawan Tetap', '02524184', '08818457609', 'Laki-laki', 'Jember', '1991-04-29', '2024-05-02', 1);
INSERT INTO `pegawai` VALUES (91, 8, 35, 10, 'Moh Iqbal Septiadi', 'Bondowoso', 'Islam', 'Karyawan Tetap', '21823173', '085157236199', 'Laki-laki', 'Madiun', '1987-09-22', '2023-08-21', 1);
INSERT INTO `pegawai` VALUES (92, 2, 3, 10, 'M. Boby Kurniawan', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085850617726', 'Laki-laki', 'Bondowoso', '1993-11-09', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (93, 7, 24, 11, 'Angger Wilujeng', 'Nangkaan Bondowoso', 'Islam', 'Karyawan Tetap', '', '087888022255', 'Laki-laki', 'Bondowoso', '1997-12-22', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (94, 2, 3, 10, 'Daniel Wima Pratama', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085234760170', 'Laki-laki', 'Kediri', '1998-05-07', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (95, 7, 28, 3, 'Arsono Agus Prayudi', 'Bondowoso', 'Islam', 'Karyawan Tetap', '01493085', '082230903020', 'Laki-laki', 'Bondowoso', '1978-02-13', '1993-04-01', 1);
INSERT INTO `pegawai` VALUES (96, 7, 22, 10, 'Tri Puji Rahayu Ningsih', 'Bondowoso', 'Islam', 'Karyawan Tetap', '01403107', '085843071984', 'Perempuan', 'Jember', '1975-11-19', '2003-04-01', 0);
INSERT INTO `pegawai` VALUES (97, 7, 30, 6, 'Anita Kusumayani', 'Bondowoso', 'Islam', 'Karyawan Tetap', '11012120', '082230899665', 'Perempuan', 'Situbondo', '1976-12-08', '2012-01-10', 1);
INSERT INTO `pegawai` VALUES (98, 7, 23, 11, 'Jumanto', 'Wringin', 'Islam', 'Karyawan Tetap', '10414122', '', 'Laki-laki', 'Bondowoso', '1972-02-17', '2014-04-01', 1);
INSERT INTO `pegawai` VALUES (99, 7, 23, 11, 'Ajir', 'Wringin', 'Islam', 'Karyawan Tetap', '10414123', '085334589781', 'Laki-laki', 'Bondowoso', '1972-07-28', '2014-04-01', 1);
INSERT INTO `pegawai` VALUES (100, 7, 21, 3, 'Wiwik', 'Tapen Bondowoso', 'Islam', 'Karyawan Tetap', '10414124', '085257999823', 'Laki-laki', 'Bondowoso', '1975-07-18', '2014-04-01', 1);
INSERT INTO `pegawai` VALUES (101, 7, 30, 11, 'Sudarmo', 'Nangkaan Bondowoso', 'Islam', 'Karyawan Tetap', '10414128', '085236266632', 'Laki-laki', 'Bondowoso', '1975-01-26', '2014-04-01', 1);
INSERT INTO `pegawai` VALUES (102, 7, 22, 11, 'Rafi I', 'Tlogosari Bondowoso', 'Islam', 'Karyawan Tetap', '10414132', '085230210417', 'Laki-laki', 'Bondowoso', '1968-05-19', '2014-04-01', 0);
INSERT INTO `pegawai` VALUES (103, 7, 17, 11, 'Bahrul Ulum', 'Curahdami Bondowoso', 'Islam', 'Karyawan Tetap', '10414137', '085234017564', 'Laki-laki', 'Bondowoso', '1983-07-10', '2014-04-01', 1);
INSERT INTO `pegawai` VALUES (104, 7, 23, 11, 'Sulasis', 'Wringin', 'Islam', 'Karyawan Tetap', '10414141', '085259073073', 'Laki-laki', 'Bondowoso', '1973-11-15', '2014-04-01', 1);
INSERT INTO `pegawai` VALUES (105, 7, 30, 11, 'Lutfi Alfan Rahmatullah', 'Bondowoso', 'Islam', 'Karyawan Tetap', '20117142', '081336336445', 'Laki-laki', 'Bondowoso', '1984-01-21', '2017-01-02', 1);
INSERT INTO `pegawai` VALUES (106, 7, 31, 11, 'Sugiono', 'Nangkaan Bondowoso', 'Islam', 'Karyawan Tetap', '20117143', '085259221782', 'Laki-laki', 'Bondowoso', '1981-07-08', '2017-01-02', 1);
INSERT INTO `pegawai` VALUES (107, 7, 27, 3, 'Sayudi Pranayuda', 'Nangkaan Bondowoso', 'Islam', 'Karyawan Tetap', '20117145', '082323808536', 'Laki-laki', 'Bondowoso', '1984-07-31', '2017-01-02', 1);
INSERT INTO `pegawai` VALUES (108, 7, 22, 11, 'Abdul Jamil', 'Bondowoso', 'Islam', 'Karyawan Tetap', '20117148', '085231344616', 'Laki-laki', 'Bondowoso', '1987-12-26', '2017-01-02', 1);
INSERT INTO `pegawai` VALUES (109, 7, 31, 13, 'Devita Oktaviani', 'Locare Bondowoso', 'Islam', 'Karyawan Tetap', '20117150', '083840388438', 'Perempuan', 'Bondowoso', '1994-10-06', '2017-01-02', 1);
INSERT INTO `pegawai` VALUES (110, 7, 17, 11, 'Beni Puji Raharjo', 'Nangkaan Bondowoso', 'Islam', 'Karyawan Tetap', '31119151', '081947615037', 'Laki-laki', 'Bondowoso', '1991-06-26', '2019-01-31', 1);
INSERT INTO `pegawai` VALUES (111, 7, 22, 3, 'M Arief Teguh Andiyanto', 'Bondowoso', 'Islam', 'Karyawan Tetap', '31119152', '082141492394', 'Laki-laki', 'Bondowoso', '1976-06-28', '2019-01-31', 1);
INSERT INTO `pegawai` VALUES (112, 7, 17, 3, 'Saiful Bari', 'Badean Bondowoso', 'Islam', 'Karyawan Tetap', '311 19 1', '085335111027', 'Laki-laki', 'Situbondo', '1987-03-06', '2019-03-11', 1);
INSERT INTO `pegawai` VALUES (113, 8, 35, 10, 'Muh Abd Cholil', 'Bondowoso', 'Islam', 'Karyawan Tetap', '31119155', '082324897200', 'Laki-laki', 'Bondowoso', '1983-04-01', '2019-01-31', 1);
INSERT INTO `pegawai` VALUES (114, 7, 24, 3, 'Hidayatullah Firdaus', 'Bondowoso', 'Islam', 'Karyawan Tetap', '31119156', '082233120549', 'Laki-laki', 'Bondowoso', '1976-11-06', '2019-01-31', 1);
INSERT INTO `pegawai` VALUES (115, 7, 20, 11, 'Sutikno', 'Gebang Bondowoso', 'Islam', 'Karyawan Tetap', '31119157', '082331354069', 'Laki-laki', 'Bondowoso', '1986-03-08', '2019-01-31', 1);
INSERT INTO `pegawai` VALUES (116, 7, 17, 11, 'Andre Rico Aliffiansyah', 'Prajekan Bondowoso', 'Islam', 'Karyawan Tetap', '31119160', '082244976515', 'Laki-laki', 'Treanggalek', '1995-05-02', '2019-03-11', 1);
INSERT INTO `pegawai` VALUES (117, 7, 23, 10, 'Ratih Nur Azizatuz Zuhro', 'Pakem Bondowoso', 'Islam', 'Karyawan Tetap', '31119161', '085236537052', 'Perempuan', 'Bondowoso', '1993-01-30', '2019-01-31', 1);
INSERT INTO `pegawai` VALUES (118, 7, 17, 10, 'Wawan Budianto', 'Sukosari Bondowoso', 'Islam', 'Karyawan Tetap', '31119162', '082139022511', 'Laki-laki', 'Bondowoso', '1993-02-15', '2019-01-31', 1);
INSERT INTO `pegawai` VALUES (119, 7, 27, 10, 'Andriya Ikfa Nurul Ms', 'Bataan Bondowoso', 'Islam', 'Karyawan Tetap', '31119164', '082283884577', 'Laki-laki', 'Malang', '1994-08-01', '2019-03-11', 1);
INSERT INTO `pegawai` VALUES (120, 7, 25, 18, 'Ananta Prayogi', 'Kotakulon Bondowoso', 'Islam', 'Karyawan Tetap', '28423167', '082245520624', 'Laki-laki', 'Bondowoso', '1993-03-27', '2023-04-28', 1);
INSERT INTO `pegawai` VALUES (121, 7, 26, 11, 'Moh Iqbal Bachtiar', 'Bondowoso', 'Islam', 'Karyawan Tetap', '28423168', '082233831357', 'Laki-laki', 'Sumenep', '1994-03-19', '2023-04-28', 1);
INSERT INTO `pegawai` VALUES (122, 7, 30, 10, 'Mohlasi', 'Tegalampel Bondowoso', 'Islam', 'Karyawan Tetap', '', '089682212739', 'Laki-laki', 'Bondowoso', '1989-09-05', '2023-04-28', 1);
INSERT INTO `pegawai` VALUES (123, 7, 25, 10, 'Novi Sundari Subaidah', 'Maesan Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082330926669', 'Perempuan', 'Jember', '1991-11-05', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (124, 7, 23, 11, 'Moh Sofyan Hadi', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085234452462', 'Laki-laki', 'Bondowoso', '1980-07-11', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (125, 7, 23, 11, 'Andika Eka Prayudi', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085232472763', 'Laki-laki', 'Bondowoso', '2000-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (126, 7, 17, 11, 'Syaifullah', 'Sukosari Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081252190579', 'Laki-laki', 'Bondowoso', '2000-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (127, 7, 17, 18, 'Tegar Ubaidhir Rahman', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081259495726', 'Laki-laki', 'Bondowoso', '2003-05-02', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (128, 7, 18, 11, 'Hendrik Efendi', 'Maesan Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081335464652', 'Laki-laki', 'Bondowoso', '1992-10-07', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (129, 7, 17, 10, 'Mustika Aditya Pratiwi', 'Sukowiryo Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085732571483', 'Perempuan', 'Bondowoso', '2000-01-01', '0000-00-00', 0);
INSERT INTO `pegawai` VALUES (130, 7, 19, 11, 'Abdul Wahid', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082335552926', 'Laki-laki', 'Bondowoso', '1988-03-03', '0000-00-00', 0);
INSERT INTO `pegawai` VALUES (131, 7, 19, 11, 'Alfian Maulana Rosyidi', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081332323828', 'Laki-laki', 'Bondowoso', '1992-09-24', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (132, 7, 19, 18, 'Andi Siswanto', 'Bondowoso', 'Islam', 'Karyawan Tetap', '', '083115872049', 'Laki-laki', 'Bondowoso', '1996-08-19', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (133, 4, 10, 11, 'Imam Kusairi', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082330109338', 'Laki-laki', 'Bondowoso', '1996-05-19', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (134, 7, 20, 10, 'Ardiansyah Wahyu R H', 'Tapen Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081336603300', 'Laki-laki', 'Bondowoso', '1996-09-08', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (135, 7, 20, 11, 'Doddy Arifaldi Yuniargo', 'Prajekan Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081231904530', 'Laki-laki', 'Bondowoso', '2000-01-16', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (136, 7, 20, 18, 'Fahrozi Dwi Julianto', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082245284740', 'Laki-laki', 'Bondowoso', '1992-07-25', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (137, 7, 20, 11, 'Hafidzul Ahkam', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081232608664', 'Laki-laki', 'Bondowoso', '1991-07-21', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (138, 7, 19, 11, 'Dharma Marisca', 'Bondowoso', 'Islam', 'Karyawan Tetap', '04923180', '089656525722', 'Laki-laki', 'Surabaya', '1996-05-07', '2023-09-04', 1);
INSERT INTO `pegawai` VALUES (139, 7, 28, 11, 'Nasrullah', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085231140559', 'Laki-laki', 'Bondowoso', '1999-07-07', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (140, 7, 21, 11, 'Rohmat Maulana', 'Prajekan Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081252722169', 'Laki-laki', 'Bondowoso', '2000-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (141, 7, 31, 18, 'Muhammad Akbar Maulana', 'Nangkaan Bondowoso', 'Islam', 'Karyawan Kontrak', '', '088237670090', 'Laki-laki', 'Bondowoso', '2002-12-22', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (142, 7, 22, 18, 'Muhammad Imam Badri', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082264218348', 'Laki-laki', 'Bondowoso', '1990-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (143, 7, 22, 11, 'Lutfi Arip', 'Bondowoso', 'Islam', 'Karyawan Tetap', '', '081133318314', 'Laki-laki', 'Bondowoso', '1997-09-09', '2023-04-28', 1);
INSERT INTO `pegawai` VALUES (144, 7, 22, 11, 'Jasit', 'Tlogosari Bondowoso', 'Islam', 'Karyawan Honorer', '', '082112200645', 'Laki-laki', 'Bondowoso', '1990-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (145, 7, 23, 11, 'Adi Suharsono', 'Wringin Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081133318312', 'Laki-laki', 'Bondowoso', '1991-07-06', '0000-00-00', 0);
INSERT INTO `pegawai` VALUES (146, 7, 23, 11, 'Firman Hidayah', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082233010077', 'Laki-laki', 'Bondowoso', '1990-02-10', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (147, 7, 23, 18, 'Junaedi', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081230656346', 'Laki-laki', 'Bondowoso', '2000-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (148, 7, 24, 18, 'Guntur Hermawan', 'Gebang Bondowoso', 'Islam', 'Karyawan Tetap', '', '085331301116', 'Laki-laki', 'Bondowoso', '1994-11-23', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (149, 7, 31, 10, 'Renaldi Ramadan', 'Bondowoso', 'Islam', 'Karyawan Tetap', '', '085336300788', 'Laki-laki', 'Bondowoso', '2000-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (150, 7, 24, 11, 'Ahmad Fatoni', 'Bondowoso', 'Islam', 'Karyawan Tetap', '', '085334529202', 'Laki-laki', 'Bondowoso', '1998-05-15', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (151, 7, 24, 11, 'Muhammad Awat Heryanto', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081216416136', 'Laki-laki', 'Bondowoso', '1990-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (152, 7, 26, 11, 'Bayu Candra Wicaksono', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082334070443', 'Laki-laki', 'Bondowoso', '1985-04-26', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (153, 7, 31, 11, 'Thesar Wahyu Ardiansyah', 'Nangkaan Bondowoso', 'Islam', 'Karyawan Tetap', '', '082141451739', 'Laki-laki', 'Bondowoso', '2001-05-19', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (154, 7, 17, 11, 'Anugerah Riski Fardana', 'Koncer Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085210675373', 'Laki-laki', 'Bondowoso', '1990-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (155, 7, 27, 18, 'Abdul Basit Junaidi', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082237334786', 'Laki-laki', 'Bondowoso', '1986-06-06', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (156, 7, 28, 18, 'Andi Rahmat Hakim', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081946132195', 'Laki-laki', 'Bondowoso', '1998-10-12', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (157, 7, 28, 10, 'Ahmad Muzammil', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085234621803', 'Laki-laki', 'Bondowoso', '2000-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (158, 7, 28, 11, 'Teguh Umar F', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085230689119', 'Laki-laki', 'Bondowoso', '1999-02-19', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (159, 7, 28, 10, 'Reza Satria Airlangga', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085607963612', 'Laki-laki', 'Bondowoso', '2000-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (160, 7, 28, 11, 'Prasetyo Dwi Risqianto', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085745789015', 'Laki-laki', 'Bondowoso', '2001-05-26', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (161, 7, 30, 11, 'Sintoso', 'Prajekan Bondowoso', 'Islam', 'Karyawan Tetap', '01410109', '', 'Laki-laki', 'Bondowoso', '1972-09-06', '2010-04-01', 1);
INSERT INTO `pegawai` VALUES (162, 7, 17, 11, 'Bayu Prianto', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082230160046', 'Laki-laki', 'Bondowoso', '1996-04-04', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (163, 7, 30, 11, 'Andika Juni Suharyanto', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082143165806', 'Laki-laki', 'Bondowoso', '2000-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (164, 7, 30, 10, 'Dika Pratama', 'Tenggarang Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082339143730', 'Laki-laki', 'Bondowoso', '1998-11-04', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (165, 7, 30, 18, 'Firman Damansyah', 'Pujer Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081330811600', 'Laki-laki', 'Bondowoso', '1995-10-06', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (166, 7, 27, 11, 'Putra Raga Adityamala', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '083874580762', 'Laki-laki', 'Bondowoso', '1990-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (167, 8, 4, 10, 'Reza Yudianto', 'Petung Bondowoso', 'Islam', 'Karyawan Tetap', '21823175', '082330433653', 'Laki-laki', 'Surabaya', '1986-04-06', '2023-08-21', 1);
INSERT INTO `pegawai` VALUES (168, 2, 4, 10, 'Yosef Nasoka', 'Petung Bondowoso', 'Islam', 'Karyawan Tetap', '', '085204937722', 'Laki-laki', 'Bondowoso', '1996-09-21', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (169, 2, 3, 10, 'Ardiylla Rosza', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '083198579633', 'Perempuan', 'Bondowoso', '2000-01-01', '0000-00-00', 0);
INSERT INTO `pegawai` VALUES (170, 8, 35, 10, 'Dwi Bekti Hariyanto', 'Pancoran Bondowoso', 'Islam', 'Karyawan Kontrak', '', '083872907252', 'Laki-laki', 'Bondowoso', '1996-04-14', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (171, 7, 30, 11, 'Muhammad Zainul Hasan', 'Bondowoso', 'Islam', 'Karyawan Tetap', '', '082335519390', 'Laki-laki', 'Bondowoso', '1994-12-29', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (172, 2, 3, 10, 'Zainul Hasan', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085233355118', 'Laki-laki', 'Bondowoso', '1988-12-18', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (173, 8, 35, 10, 'Ali Shadikin', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082142101456', 'Laki-laki', 'Bondowoso', '1992-11-25', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (174, 3, 7, 10, 'Chinta Adelita Diva', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081249800779', 'Perempuan', 'Bondowoso', '2000-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (175, 7, 18, 11, 'Muchamad Aidil Akbar', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082336993553', 'Laki-laki', 'Bondowoso', '1997-04-24', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (176, 7, 18, 11, 'Ade Prayoga', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085880954831', 'Laki-laki', 'Bondowoso', '2000-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (177, 7, 22, 10, 'Mohammad Andika', 'Tlogosari Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085232723699', 'Laki-laki', 'Bondowoso', '1998-09-20', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (178, 7, 28, 10, 'Aisyah Evita Dewi', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '083847437787', 'Perempuan', 'Bondowoso', '1998-01-01', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (179, 4, 11, 10, 'Nurul Qomariyah', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '088803718524', 'Perempuan', 'Bondowoso', '2023-09-18', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (180, 7, 19, 10, 'Denny Setiady Prabowo', 'Jl. S.Parman Perum Badean Estate Bondowoso', 'Islam', 'Karyawan Kontrak', '', '083147860320', 'Laki-laki', 'Bondowoso', '2023-09-18', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (181, 7, 18, 18, 'Moch Yahya Ikbar', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '089683017714', 'Laki-laki', 'Bondowoso', '2023-09-18', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (182, 7, 21, 10, 'Emelia Rafilah Aisya', 'Blimbing Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081231947779', 'Perempuan', 'Bondowoso', '2023-09-18', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (183, 7, 18, 10, 'Alyfiyyah Jamil', 'Maesan Bondowoso', 'Islam', 'Karyawan Kontrak', '11124191', '', 'Perempuan', 'Bondowoso', '1998-04-14', '2024-11-01', 1);
INSERT INTO `pegawai` VALUES (184, 7, 20, 11, 'Anugerah Putra Suwantono', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '', 'Laki-laki', 'Bondowoso', '2023-09-18', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (185, 7, 19, 10, 'Anisah Firdaus', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085781701599', 'Perempuan', 'Bondowoso', '2000-01-01', '2024-05-28', 1);
INSERT INTO `pegawai` VALUES (186, 7, 19, 11, 'Temmy Rizky Prihandana', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '', 'Laki-laki', 'Bondowoso', '2023-09-18', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (187, 7, 26, 18, 'Imam Arifin', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '0895383501136', 'Laki-laki', 'Bondowoso', '2023-09-18', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (188, 7, 17, 11, 'Novi Hidayah', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '', 'Laki-laki', 'Bondowoso', '2023-09-18', '0000-00-00', 1);
INSERT INTO `pegawai` VALUES (189, 7, 31, 18, 'Arief Chandra Hermawan', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085204860465', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-05-27', 1);
INSERT INTO `pegawai` VALUES (190, 8, 34, 10, 'Tony Adam Iskandar', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082289747555', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-05-27', 1);
INSERT INTO `pegawai` VALUES (191, 7, 20, 10, 'Radhika Vavirya Sunardi', 'Bondowoso', 'Islam', 'Karyawan Honorer', '', '082139533450', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-05-27', 1);
INSERT INTO `pegawai` VALUES (192, 7, 28, 11, 'Dede Ahmad Satrio', 'Bondowoso', 'Islam', 'Karyawan Honorer', '', '083867817451', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-05-27', 1);
INSERT INTO `pegawai` VALUES (193, 7, 21, 11, 'Ravimalik Fathon M', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081332996673', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-05-27', 1);
INSERT INTO `pegawai` VALUES (194, 8, 34, 10, 'Rizaldy Yudha Arry P', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081330462714', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-05-27', 1);
INSERT INTO `pegawai` VALUES (195, 8, 34, 10, 'Alex Agus Setiawan', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085645705926', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-05-27', 1);
INSERT INTO `pegawai` VALUES (196, 7, 21, 18, 'Febri Ananda Maunah D', 'Prajekan', 'Islam', 'Karyawan Honorer', '', '085337035197', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-05-27', 1);
INSERT INTO `pegawai` VALUES (197, 7, 22, 10, 'Anggi Agus F', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '081331652586', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-05-27', 1);
INSERT INTO `pegawai` VALUES (198, 7, 24, 11, 'Andrean Priyanto S', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085707950286', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-05-27', 1);
INSERT INTO `pegawai` VALUES (199, 8, 15, 4, 'Rudy Harnalis', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '08122333960', 'Laki-laki', 'Bondowoso', '1970-01-01', '2024-05-01', 1);
INSERT INTO `pegawai` VALUES (200, 8, 15, 21, 'Edi Suhartono', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082141412986', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-05-27', 1);
INSERT INTO `pegawai` VALUES (201, 7, 19, 18, 'Ricky Nugroho', 'Bondowoso', 'Islam', 'Karyawan Honorer', '', '', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-06-01', 1);
INSERT INTO `pegawai` VALUES (202, 4, 10, 11, 'Abean Azriel Widyo S', 'Bondowoso', 'Islam', 'Karyawan Honorer', '', '081235139683', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-06-01', 1);
INSERT INTO `pegawai` VALUES (203, 6, 14, 9, 'Aminah Oktarina Libra A', 'Bondowoso', 'Islam', 'Karyawan Honorer', '', '', 'Perempuan', 'Bondowoso', '2000-01-01', '2024-06-01', 1);
INSERT INTO `pegawai` VALUES (204, 7, 27, 11, 'Muhammad Rizal Qhadafi', 'Bondowoso', 'Islam', 'Karyawan Honorer', '', '085730634070', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-06-01', 1);
INSERT INTO `pegawai` VALUES (205, 7, 31, 11, 'Muhammad Armand Reza M', 'Bondowoso', 'Islam', 'Karyawan Honorer', '', '083877005717', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-08-01', 1);
INSERT INTO `pegawai` VALUES (206, 7, 31, 11, 'Moh Syahrial Putra P', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-08-01', 1);
INSERT INTO `pegawai` VALUES (207, 7, 19, 11, 'Muhammad Ariel Maulana', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-01-01', 1);
INSERT INTO `pegawai` VALUES (208, 7, 22, 11, 'Riko Aditya N', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082229148459', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-01-01', 0);
INSERT INTO `pegawai` VALUES (209, 8, 34, 10, 'Alvianda Rizkiyadi R', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085257307218', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-01-01', 1);
INSERT INTO `pegawai` VALUES (210, 8, 4, 10, 'Decky Rizaldy E D', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082233933496', 'Laki-laki', 'Bondowoso', '2000-01-01', '2024-01-01', 1);
INSERT INTO `pegawai` VALUES (211, 7, 21, 11, 'Dian Siregar', 'Bondowoso', 'Islam', 'Karyawan Honorer', '', '083867879745', 'Laki-laki', 'Bondowoso', '2000-01-01', '2025-01-01', 1);
INSERT INTO `pegawai` VALUES (212, 7, 24, 10, 'Mita Yoandra', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085931156709', 'Perempuan', 'Bondowoso', '2000-01-01', '2025-01-01', 1);
INSERT INTO `pegawai` VALUES (213, 1, 1, 10, 'Aqsa Fahmiranda Darmawan Lubis', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '082140356939', 'Laki-laki', 'Bondowoso', '2000-01-01', '2025-01-01', 1);
INSERT INTO `pegawai` VALUES (214, 2, 3, 10, 'Hendi Hendra Laksya Utama', 'Bondowoso', 'Islam', 'Karyawan Tetap', '', '085231315707', 'Laki-laki', 'Bondowoso', '1978-01-01', '2014-01-01', 1);
INSERT INTO `pegawai` VALUES (215, 2, 3, 10, 'Giant Paul Ivan', 'Bondowoso', 'Islam', 'Karyawan Honorer', '', '', 'Laki-laki', 'Bondowoso', '2000-01-01', '2025-01-01', 1);
INSERT INTO `pegawai` VALUES (216, 2, 5, 10, 'Siti Syafira Densiana T', 'Bondowoso', 'Islam', 'Karyawan Kontrak', '', '085232438208', 'Perempuan', 'Bondowoso', '2000-01-01', '0000-00-00', 1);

SET FOREIGN_KEY_CHECKS = 1;
