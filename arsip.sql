/*
 Navicat Premium Data Transfer

 Source Server         : siaap
 Source Server Type    : MySQL
 Source Server Version : 100621 (10.6.21-MariaDB-0ubuntu0.22.04.2)
 Source Host           : 192.168.55.3:3306
 Source Schema         : amdk

 Target Server Type    : MySQL
 Target Server Version : 100621 (10.6.21-MariaDB-0ubuntu0.22.04.2)
 File Encoding         : 65001

 Date: 17/07/2025 14:25:14
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for arsip
-- ----------------------------
DROP TABLE IF EXISTS `arsip`;
CREATE TABLE `arsip`  (
  `id_arsip` int NOT NULL AUTO_INCREMENT,
  `jenis` enum('Surat Keputusan','Peraturan','Berkas Kerja','dokumen') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tahun` char(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_dokumen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tentang` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_dokumen` date NOT NULL,
  `tgl_upload` date NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_arsip`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of arsip
-- ----------------------------
INSERT INTO `arsip` VALUES (2, 'Surat Keputusan', '1999', 'Kepmendagri_47_th_99.pdf', 'Kepmendagri no 47 Tahun 1999', 'Pedoman Penilaian Kinerja Perusahaan Daerah Air Minum', '2023-05-11', '2023-05-11', '-');
INSERT INTO `arsip` VALUES (4, 'Peraturan', '1993', 'Perda_No_2_Tahun_93_tentang_Pendirian_Pdam.pdf', 'Perda No 2 Tahun 1993', 'Pendirian Perusahaan  Daerah Air Minum Kabupaten Bondowoso Tingkat II Bondowoso', '1993-04-21', '2023-05-15', '');
INSERT INTO `arsip` VALUES (5, 'Surat Keputusan', '1996', 'SK_Direktur_NO_22_2_Tahun_96_Tentang_Struktur.pdf', 'SK Direktur No 22.2 Tahun 1996 ', 'Struktur  Organisasi, Uraian Tugas  dan Tata Kerja Perusahaan Daerah Air Minum Kabupaten Daerah Tingkat II Bondowoso', '1996-04-01', '2023-05-15', '-');
INSERT INTO `arsip` VALUES (6, 'Surat Keputusan', '2017', 'SK_Bupati_No_188_45_Tahun_2017_Tentang_Tarif_Air.pdf', 'SK Bupati No 188.45/830/430.4.2/2017', 'Tarif Air Minum Pada Perusahaan Daerah Air Minum Kabupaten Bondowoso Tahun 2017', '2017-11-29', '2023-05-15', '-');
INSERT INTO `arsip` VALUES (7, 'Surat Keputusan', '2022', 'SK_Bupati_No_188_45_Tahun_2022_Tentang_Tarif_Air.pdf', 'SK Bupati No 188.45/262/430.4.2/2022', 'Tarif Air Minum Pada Perusahaan Daerah Air Minum Kabupaten Bondowoso Tahun 2022', '2022-02-24', '2023-05-15', '-');
INSERT INTO `arsip` VALUES (8, 'Surat Keputusan', '2021', 'SK_Direktur_No_188_tahun_2021_tentang_Hak_Minim.pdf', 'SK Direktur No 188/33.3/430.12/2021 ', 'Perubahan Penetapan Pemberlakuan Hak Minim (10)M3', '2021-11-01', '2023-05-15', '-');
INSERT INTO `arsip` VALUES (9, 'Peraturan', '2015', 'Perda_no_3_tahun_2015_ttg_Penyertaan_Modal.pdf', 'Perda No 3 Tahun 2015', 'Penyertaan Modal Pemerintah Daerah Kepada Perusahaan Daerah Air Minum Kabupaten Bondowoso', '2015-11-30', '2023-05-15', '-');
INSERT INTO `arsip` VALUES (10, 'Surat Keputusan', '2021', 'SK_Direktur_No_188_Tahun_2021_Tentang_Pedoman_Pengadaan_barang_jasa.pdf', 'SK Direktur No 188/01.4.2/430.12/2021 ', 'Pedoman Pelaksanaan Pengadaan Barang/Jasa  pada Perusahaan Daerah Air Minum Kabupaten Bondowoso', '2021-01-11', '2023-05-15', '-');
INSERT INTO `arsip` VALUES (11, 'Peraturan', '2019', 'PERBUP_Perubahan_kedua_atas_Peraturan_Bupati_No_57_TAHUN_2013.pdf', 'PerBup No 8 Tahun 2019', 'Perubahan Kedua atas Peraturan Bupati Bondowoso No 57 tahun Tahun 2013 Tentang Petunjuk Pelaksanaan Peraturan Daerah Kabupaten Daerah Tingkat II Bondowoso No 2 Tahun 1993 Tentang Pendirian Perusahaan Daerah Air Minum Kabupaten Daerah Tingkat II Bondowoso', '2019-01-18', '2023-05-15', '-');
INSERT INTO `arsip` VALUES (12, 'Peraturan', '2011', 'Perda_No_6_Tahun_2011_ttg_perubahan_pendirian_pdam.pdf', 'Perda No 6 Tahun 2011', 'Perubahan Atas  Peraturan Daerah Kabupaten Daerah Tingkat II Bondowoso No 2  Tahun 1993  Tentang Pendirian Perusahaan Daerah Air Minum  Kabupaten  Daerah Tingkat II Bondowoso', '2011-08-01', '2023-05-15', '-');
INSERT INTO `arsip` VALUES (17, 'Peraturan', '2018', 'Permendagri_Nomor_37_Tahun_2018.pdf', 'Permendagri No 37 Tahun 2018', 'Pengangkatan dan Pemberhentian Anggota Dewan Pengawas atau  Anggota Komisaris dan Anggota Direksi Badan Usaha Milik Daerah', '2018-05-07', '2023-05-16', '');
INSERT INTO `arsip` VALUES (20, 'Peraturan', '2017', 'PERMENDAGRI_Nomor_11_Tahun_2017.pdf', 'Permendagri No 11 Tahun 2017', 'Pedoman Evaluasi Rancangan Peraturan Daerah Tentang\r\nPertanggungjawaban Pelaksanaan Anggaran Pendapatan Dan\r\nBelanja Daerah Dan Rancangan Peraturan Kepala Daerah\r\nTentang Penjabaran Pertanggungjawaban Pelaksanaan\r\nAnggaran Pendapatan Dan Belanja Daerah', '2017-02-22', '2023-05-16', '');
INSERT INTO `arsip` VALUES (21, 'dokumen', '2015', 'SAK_ETAP_CONTENTS.pdf', 'Pedoman SAK ETAP', 'Pedoman Standar Akuntansi Keuangan Untuk Entitas Tanpa Akuntabilitas Publik', '2015-01-01', '2023-06-21', '');
INSERT INTO `arsip` VALUES (22, 'Peraturan', '2006', 'permendagri_23_2006.pdf', 'Permendagri no 23 tahun 2006', 'Pedoman Teknis Dan Tata Cara Pengaturan Tarif Air Minum Pada Perusahaan Daerah Air Minum', '2006-07-03', '2023-06-23', '');
INSERT INTO `arsip` VALUES (24, 'Surat Keputusan', '2021', 'SK_Biaya_Pemasangan_Sambungan_Baru_2021.pdf', 'SK Direktur No 188/39/430.12/2021', 'Biaya Pemasangan Sambungan Baru Perusahaan Daerah Air Minum Kabupaten Bondowoso', '2021-12-30', '2024-06-26', '-');
INSERT INTO `arsip` VALUES (28, 'Surat Keputusan', '2022', 'SK_Diskon_Biaya_Pemasangan_2022.pdf', 'SK Bupati No 188/38.1/430.12/2022', 'Discount Biaya Pemasangan Sambungan Rumah (SR) PDAM Kabupaten Bondowoso 2022', '2022-07-29', '2024-06-28', '-');
INSERT INTO `arsip` VALUES (29, 'Surat Keputusan', '2002', 'penetapan_uang_jaminan_pelanggan.pdf', 'SK Direktur no 14/SK/430.92/2002', 'Penetapan Uang Jaminan Pelanggan Perusahaan Daerah Air Minum Kabupaten Bondowoso', '2002-11-01', '2024-07-02', '');
INSERT INTO `arsip` VALUES (30, 'Surat Keputusan', '2022', 'SK_Tarif_Lembur.pdf', 'SK Direktur no 188/34/430.12/2022', 'Perubahan Besaran Uang Lembur Bagi Karyawan / Karyawati Perusahaan Daerah Air Minum', '2022-06-02', '2024-07-31', '');

SET FOREIGN_KEY_CHECKS = 1;
