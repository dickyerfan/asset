-- =====================================================================
-- 05 · SEED KEY RISK INDICATOR (KRI) 2027
--
-- SUMBER: D:\MR 2026\Ringkasan_MR_Perumdam_Ijen_Tirta.md §10.2
--         "Indikator Pemantauan (KRI)" — 6 baris, diambil persis.
--
-- status_kri = 'Belum Diukur' dan nilai_aktual = NULL untuk semua baris:
-- tahun anggaran 2027 belum berjalan sehingga BELUM ADA hasil pengukuran.
-- Menampilkan 'Tidak Tercapai' pada data kosong akan menyesatkan.
--
-- target NULL pada KRI #1 dan #3: materi hanya menyebut "Minimal" dan
-- "Sesuai target" tanpa angka. Angka tidak dikarang. KRI #3 terkait
-- mri_objektif id 21 (Penurunan NRW) yang targetnya juga belum
-- dinyatakan numerik dalam paparan RKAP 2027.
--
-- Jalankan setelah 04_seed_register_2027.sql.
-- =====================================================================
SET NAMES utf8mb4;

INSERT INTO `mri_kri`
 (`id_kri`,`id_register`,`id_lingkup`,`id_upk`,`tahun`,`triwulan`,
  `nama_kri`,`target`,`frekuensi`,`satuan`,`nilai_aktual`,`status_kri`,`keterangan`)
VALUES
(1,1,1,4,2027,1,
 'Jumlah gangguan pompa',NULL,'Per bulan','kasus',NULL,'Belum Diukur',
 'Target materi: "Minimal" (Ringkasan MR §10.2) — angka batas spesifik belum ditetapkan. Diukur sebagai jumlah kejadian per bulan. Risiko terkait: IJT-OPR-01 Gangguan 25 sumur bor.'),
(2,1,1,4,2027,1,
 'Frekuensi outage',2.0000,'Per bulan','kali/bulan',NULL,'Belum Diukur',
 'Target materi: < 2x per bulan (Ringkasan MR §10.2). Risiko terkait: IJT-OPR-01 Gangguan 25 sumur bor.'),
(3,3,1,4,2027,1,
 'Tingkat NRW',NULL,'Per bulan','persen',NULL,'Belum Diukur',
 'Target materi: "Sesuai target" (Ringkasan MR §10.2) — ANGKA TARGET NRW BELUM DINYATAKAN dalam paparan RKAP 2027. Berkaitan dengan mri_objektif id 21 yang juga ber-NULL. Risiko terkait: IJT-OPR-03 Kehilangan air tinggi (NRW).'),
(4,5,1,3,2027,1,
 'Piutang ragu-ragu',5.0000,'Per triwulan','persen',NULL,'Belum Diukur',
 'Target materi: < 5% (Ringkasan MR §10.2). Risiko terkait: IJT-KEU-01 Piutang tak tertagih tinggi.'),
(5,8,1,2,2027,1,
 'Downtime sistem IT',4.0000,'Per insiden','jam',NULL,'Belum Diukur',
 'Target materi: < 4 jam (Ringkasan MR §10.2). Risiko terkait: IJT-IT-01 Gangguan sistem billing/IT.'),
(6,2,1,6,2027,1,
 'Jumlah keluhan pelanggan',10.0000,'Per bulan','persen penurunan',NULL,'Belum Diukur',
 'Target materi: Turun 10% (Ringkasan MR §10.2) terhadap periode sebelumnya. Risiko terkait: IJT-OPR-02 Kualitas air tidak memenuhi standar (akibat: keluhan pelanggan meningkat).');
