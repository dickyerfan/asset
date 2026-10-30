-- =====================================================================
-- 03 · SEED OBJEKTIF / TARGET RKAP 2027 (TAHAP 3 — ISO 6.3/6.4.2)
--
-- SUMBER: "Pemaparan RKAP 2027 Perumdam Ijen Tirta.pptx"
-- Angka YANG DIPILIH SESUAI PERMINTAAN USER: slide 9 (Komparasi Laba
-- Rugi). Dikonfirmasi oleh slide 19 "Target RKAP 2027 yang Diusulkan"
-- yang menyebut "Rp 22,38 M Pendapatan Usaha".
--
-- status_rekonsiliasi = 'BELUM' menandai indikator yang angkanya
-- BERBEDA antar slide dan PERLU DITINDAKLANJUTI oleh tim keuangan.
-- Kode TIDAK mengoreksi angka — hanya menandainya.
--
-- Selain itu di-seed 1 baris mri_lingkup (Tahap 1-2) sebagai induk.
-- Jalankan setelah 02_seed_master.sql.
-- =====================================================================
SET NAMES utf8mb4;

-- ---------------------------------------------------------------------
-- Tahap 1-2 · Lingkup, Konteks & Kriteria (ISO 6.2 + 6.3)
-- ---------------------------------------------------------------------
INSERT INTO `mri_lingkup`
 (`id_lingkup`,`tahun`,`triwulan`,`id_upk`,`nama_lingkup`,`objek_evaluasi`,
  `batas_evaluasi`,`konteks_internal`,`konteks_eksternal`,`kriteria_acuan`,
  `tanggal_konsultasi`,`peserta`,`pic_konsultasi`,`status`)
VALUES
(1,2027,1,NULL,
 'Penyusunan Risk Register Tahun Anggaran 2027 — Seluruh Organisasi',
 'Seluruh proses bisnis Perumdam Ijen Tirta (Risk Universe A-K), mulai dari tata kelola & strategis, keuangan, pelayanan, produksi, transmisi-distribusi, pengadaan, SDM, IT, AMDK, kepatuhan, hingga kejadian eksternal/K3/ESG.',
 'Wilayah organisasi Perumdam Ijen Tirta: 6 divisi/unit kerja (Umum, Keuangan, Pemeliharaan, Perencanaan, Langganan, S P I), 16 UPK, dan 1 unit AMDK. Periode: Tahun Anggaran 2027 (dan tahun sebelumnya dicatat sebagai riwayat melalui modul risiko legacy).',
 'Struktur organisasi baru (lihat Struktur Organisasi Perumdam Ijen Tirta), RKAP 2027 telah ditetapkan, 172 pegawai, keterbatasan anggaran operasional, sistem billing & SCADA dalam pengembangan, risk appetite resmi BELUM ditetapkan secara formal.',
 'Perubahan ketentuan air minum dan regulasi daerah, ketersediaan sumber air (25 sumur bor) yang dipengaruhi musim kemarau, biaya energi/listrik yang berfluktuasi, persaingan AMDK, ekspektasi layanan digital pelanggan, isu K3L dan keberlanjutan (ESG).',
 'Nilai RKAP 2027 (Pendapatan Usaha Rp 22.381.036.762, Laba Bersih Rp 2.511.247.563); skala risiko 4 level (Rendah/Sedang/Tinggi/Kritis) sesuai template Risk Register 2027; target efisiensi penagihan 92,98%; kriteria baku mutu air.',
 '2026-10-05',
 'Direksi, Kabag Keuangan, Kabag Umum, Kabag Pemeliharaan, Kabag Perencanaan, Kabag Langganan, Ketua SPI, Kepala UPK, Manager Unit AMDK',
 'April Ariestha Bhirawa (Direktur)',
 'DRAFT');

-- ---------------------------------------------------------------------
-- Tahap 3 · Objektif / Target per Divisi-UPK (indikator = pemicu
-- identifikasi risiko). 26 indikator, seluruhnya dari materi RKAP 2027.
--
-- status_rekonsiliasi='BELUM' pada 3 indikator dengan selisih antar slide:
--   id 1  : slide 9 = 22.381.036.762 ; slide 8 & 13 = 22.314.031.762 (d 67.005.000)
--   id 3  : slide 9 = 13.419.984.456 ; slide 13        = 13.349.379.456 (d 70.605.000)
--   id 24 : slide 14 laba bersih AMDK = 191.211.489 ; pendapatan-beban =
--           188.811.489 (d 2.400.000, angka sendiri tidak konsisten)
-- ---------------------------------------------------------------------
INSERT INTO `mri_objektif`
 (`id_objektif`,`tahun`,`id_bagian`,`id_upk`,`id_proses`,`id_lingkup`,
  `indikator`,`nilai_rkap`,`satuan`,`baseline_2026`,`sumber_nilai`,
  `status_rekonsiliasi`,`catatan_rekonsiliasi`,`status`)
VALUES
-- ===== Proses B · Keuangan (bagian 3) =====
(1,2027,3,NULL,2,1,
 'Total Pendapatan Usaha',22381036762.00,'Rp',22213650537.00,
 'Paparan RKAP 2027 — Slide 9 (Komparasi Laba Rugi)',
 'BELUM',
 'Slide 8 (Ringkasan Eksekutif) dan Slide 13 (Detail Laba Rugi) mencatat 22.314.031.762 — selisih Rp 67.005.000. Nilai Rp 22,38 M dipilih karena ditegaskan kembali di Slide 19 "Target RKAP 2027 yang Diusulkan". Perlu rekonsiliasi dengan worksheet RKAP.',1),
(2,2027,3,NULL,2,1,
 'Total Beban Usaha',8964652306.00,'Rp',9765284776.00,
 'Paparan RKAP 2027 — Slide 9','SELESAI',
 'Nilai konsisten di Slide 8, 9, dan 13.',1),
(3,2027,3,NULL,2,1,
 'Laba Kotor',13419984456.00,'Rp',12448365761.00,
 'Paparan RKAP 2027 — Slide 9 (Komparasi Laba Rugi)',
 'BELUM',
 'Slide 13 mencatat 13.349.379.456 — selisih Rp 70.605.000. Selisih ini turunan dari selisih Pendapatan Usaha (id 1), karena Total Beban Usaha identik di semua slide.',1),
(4,2027,3,NULL,2,1,
 'Beban Umum & Administrasi',10613952106.00,'Rp',9927086691.00,
 'Paparan RKAP 2027 — Slide 9','SELESAI',
 'Nilai konsisten di Slide 9 dan 13.',1),
(5,2027,3,NULL,2,1,
 'Laba Operasional',2735427350.00,'Rp',2521279070.00,
 'Paparan RKAP 2027 — Slide 9','SELESAI',
 'Nilai konsisten di Slide 9 dan 13.',1),
(6,2027,3,NULL,2,1,
 'Laba Sebelum Pajak',2986299563.00,'Rp',2710840819.00,
 'Paparan RKAP 2027 — Slide 9','SELESAI',
 'Nilai konsisten di Slide 9 dan 13.',1),
(7,2027,3,NULL,2,1,
 'Pajak Kini (PPh 29)',355052000.00,'Rp',289927000.00,
 'Paparan RKAP 2027 — Slide 9','SELESAI',
 'Nilai konsisten di Slide 9 dan 13.',1),
(8,2027,3,NULL,2,1,
 'Laba Bersih',2511247563.00,'Rp',2300913819.00,
 'Paparan RKAP 2027 — Slide 9; ditegaskan di Slide 19 (Target RKAP yang Diusulkan)','SELESAI',
 'ANGKA ACUAN UTAMA. Nilai konsisten di Slide 9, 10 (Laba Tahun Berjalan), 13, dan 19.',1),
(9,2027,3,NULL,2,1,
 'Total Aset',58153215439.86,'Rp',NULL,
 'Paparan RKAP 2027 — Slide 10 (Neraca)','SELESAI','Baseline 2026 tidak tersedia di materi.',1),
(10,2027,3,NULL,2,1,
 'Jumlah Ekuitas',52583469090.04,'Rp',NULL,
 'Paparan RKAP 2027 — Slide 10 (Neraca)','SELESAI','Baseline 2026 tidak tersedia di materi.',1),
(11,2027,3,NULL,2,1,
 'ROE (Return on Equity)',4.78,'persen',NULL,
 'Paparan RKAP 2027 — Slide 11','SELESAI',
 'Kategori "Cukup" (3%-<7%) menurut skala yang disediakan materi.',1),
(12,2027,3,NULL,2,1,
 'ROI / ROA',4.32,'persen',NULL,
 'Paparan RKAP 2027 — Slide 11','SELESAI','Kategori "Cukup" menurut skala materi.',1),

-- ===== Proses C · Pelayanan & Pelanggan (bagian 6) =====
(13,2027,6,NULL,3,1,
 'Pelanggan Direkeningkan',19513,'pelanggan',20123,
 'Paparan RKAP 2027 — Slide 12; Slide 19','SELESAI',
 'Penurunan 610 pelanggan (-3,03%) terhadap 2026.',1),
(14,2027,6,NULL,3,1,
 'Sambungan Baru (SR)',938,'SR',1370,
 'Paparan RKAP 2027 — Slide 12, 15, 19','SELESAI',
 'Penurunan 432 SR (-31,5%) terhadap 2026 — penurunan tajam, potensi sumber risiko pendapatan.',1),
(15,2027,6,NULL,3,1,
 'Pola Konsumsi',13.43,'persen',13.07,
 'Paparan RKAP 2027 — Slide 12; Slide 19','SELESAI','Peningkatan +0,36 poin (+2,68%).',1),
(16,2027,6,NULL,3,1,
 'Volume Air',3094733,'m3',3105987,
 'Paparan RKAP 2027 — Slide 12','SELESAI','Penurunan 11.254 m3 (-0,36%).',1),
(17,2027,6,NULL,3,1,
 'Penggantian Meter',727,'unit',NULL,
 'Paparan RKAP 2027 — Slide 15 (Program Kerja)','SELESAI','Baseline 2026 tidak tersedia di materi.',1),
(18,2027,6,NULL,3,1,
 'Tera Meter',3821,'unit',NULL,
 'Paparan RKAP 2027 — Slide 15 (Program Kerja)','SELESAI','Baseline 2026 tidak tersedia di materi.',1),
(19,2027,6,NULL,3,1,
 'Efisiensi Penagihan',92.98,'persen',NULL,
 'Paparan RKAP 2027 — Slide 15 (Program Kerja)','SELESAI','Baseline 2026 tidak tersedia di materi.',1),

-- ===== Proses E · Transmisi & Distribusi (bagian 4) =====
(20,2027,4,NULL,5,1,
 'Anggaran Pemeliharaan Pipa & Jaringan',157800000.00,'Rp',NULL,
 'Paparan RKAP 2027 — Slide 18 (Efisiensi & Pemeliharaan Jaringan)','SELESAI',
 'Tren bulanan dengan puncak pada bulan Juli. Cakupan jaringan transmisi-distribusi (bukan pipa pelanggan).',1),
(21,2027,4,NULL,5,1,
 'Penurunan NRW per zona sumur bor',NULL,'persen',NULL,
 'Paparan RKAP 2027 — Slide 18 (Inisiatif Operasional Lapangan #1)','BELUM',
 'TARGET BELUM DINYATAKAN SEBAGAI ANGKA dalam materi — hanya dinyatakan sebagai inisiatif. 25 sumur bor aktif; perlu ditetapkan target NRW numerik oleh Pemeliharaan sebelum tahap analisis. Inisiatif terkait: penyesuaian produksi motor pompa (efisiensi listrik), pengangkatan rutin SB Locare tiap 3 bulan, pengurangan jaringan ACP (Pertigaan Nangkaan).',1),

-- ===== Proses F · Pengadaan & Investasi (bagian 5) =====
(22,2027,5,NULL,6,1,
 'Total Rencana Investasi',2300000000.00,'Rp',NULL,
 'Paparan RKAP 2027 — Slide 16 (Program Kerja — Investasi)','SELESAI',
 'Rp 2,30 Miliar. Alokasi: Instalasi T&D Rp935,2jt (41%), Pompa Rp523,3jt (23%), Aktiva Tak Berwujud Rp500,0jt (22%), Inventaris/Perabot Rp246,7jt (11%), Peralatan Rp48,2jt (2%), Instalasi Sumber Rp46,7jt (2%). Tanpa alokasi tanah, IPA, bangunan/gedung, dan kendaraan.',1),

-- ===== Proses I · AMDK Ijen Water (bagian 23) =====
(23,2027,23,NULL,9,1,
 'Pendapatan AMDK (Ijen Water)',3014730000.00,'Rp',2635560000.00,
 'Paparan RKAP 2027 — Slide 14 (Komparasi Laba Rugi RKAP AMDK)','SELESAI',
 'Pertumbuhan +14,39%.',1),
(24,2027,23,NULL,9,1,
 'Laba Bersih AMDK (Ijen Water)',191211489.00,'Rp',262849512.00,
 'Paparan RKAP 2027 — Slide 14','BELUM',
 'ANGKA SENDIRI TIDAK KONSISTEN: pendapatan 3.014.730.000 - beban usaha 2.825.918.511 = 188.811.489, sedangkan tabel menyatakan 191.211.489 (selisih Rp 2.400.000). Selain itu total beban 2.825.918.511 IDENTIK dengan Beban Non Operasional utama di Slide 9 — diduga kesalahan penyalinan. Perlu rekonsiliasi tim keuangan.',1),

-- ===== Proses A · Tata Kelola & Strategis (bagian 1) =====
(25,2027,1,NULL,1,1,
 'Dividen/PAD yang harus disetor (Proyeksi 2028)',803599220.00,'Rp',NULL,
 'Paparan RKAP 2027 — Slide 19 (Proyeksi Penggunaan Laba Perumdam 2027)','SELESAI',
 '32% dari Laba Bersih 2027 (Rp 2.511.247.563). PENTING: persentase yang tercetak di slide (Cadangan 20%, K3/Investasi 35%, Tantiem 5%, Lainnya 20%, Dividen 40%) menjumlah 120% dan tidak cocok dengan nilai rupiah (20%/28%/4%/16%/32% = 100%). Nilai rupiah konsisten dan dijumlahkan tepat ke Laba Bersih — dipakai sebagai acuan.',1),
(26,2027,3,NULL,2,1,
 'Jumlah Rekening (rata-rata)',230504,'rekening',237543,
 'Paparan RKAP 2027 — Slide 12','SELESAI','Penurunan 7.039 rekening (-2,96%).',1);
