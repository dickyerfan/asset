-- =====================================================================
-- 02 · SEED MASTER MANAJEMEN RISIKO (manrisk/)
-- Sumber: manrisk.docx (Risk Universe A-K) · QRMO slide 11 (14 kategori)
--         QRMO slide 64 (appetite) · QRMO slide 42 (4 level / kolom csv
--         Risk_Register_Template_Perumdam_Ijen_Tirta.csv)
--         mr_petugas_ttd (nama & NIK risk owner ASLI, bukan hasil rekaan)
--         mr_pemilik_risiko (daftar peran PIC legacy)
--         mr_pengaturan_kunci (kode kunci legacy)
--
-- Jalankan setelah 01_schema.sql.
-- Perubahan pada data ini tidak menyentuh tabel legacy (mr_*).
-- =====================================================================
SET NAMES utf8mb4;

-- ---------------------------------------------------------------------
-- 1 · RISK UNIVERSE — 11 kelompok proses A-K
--     id_bagian_penanggung = divisi pemilik proses (FK ringan ke
--     bagian_upk; tanpa CONSTRAINT agar tidak mengikat skema legacy)
-- ---------------------------------------------------------------------
INSERT INTO `mri_proses`
  (`id_proses`,`kode_proses`,`nama_proses`,`grup_tema`,`id_bagian_penanggung`,`deskripsi`,`urut`,`status`)
VALUES
(1,'A','Tata Kelola & Strategis','Penguatan Tata Kelola Perusahaan & SDM',1,
 'Risiko pada strategi, tata kelola, keputusan pimpinan, pengendalian internal, dan pencapaian sasaran jangka panjang. Cakupan: target RKAP tidak tercapai, keputusan tanpa data, fraud/korupsi, tindak lanjut temuan SPI, perubahan strategis.',1,1),
(2,'B','Keuangan','Efisiensi Operasional Berbasis Data',3,
 'Risiko pada pendapatan, biaya, laba, arus kas, piutang, aset, perpajakan, dan pendanaan. Cakupan: pendapatan tak tercapai, piutang tak tertagih, biaya operasi naik, arus kas terganggu, fraud transaksi keuangan.',2,1),
(3,'C','Pelayanan & Pelanggan','Peningkatan Pelayanan Pelanggan',6,
 'Risiko pada pelayanan pelanggan, pertumbuhan pelanggan, kualitas layanan, dan persepsi masyarakat. Cakupan: pelanggan berhenti, target SR baru, pola konsumsi turun, meter rusak, pengaduan, kualitas layanan UPK.',3,1),
(4,'D','Produksi & Sumber Air','Efisiensi Operasional Berbasis Data',4,
 'Risiko pada pengolahan air, sumur bor, pompa, energi, dan kualitas baku mutu. Cakupan: debit sumber air turun, pompa rusak/tidak efisien, biaya listrik tinggi, kualitas air sumber, reservoir/penampungan.',4,1),
(5,'E','Transmisi & Distribusi','Efisiensi Operasional Berbasis Data',4,
 'Risiko pada jaringan distribusi, kehilangan air (NRW), tekanan, dan pengukuran. Cakupan: kebocoran pipa, jaringan tua/ACP, tekanan tidak stabil, meter tidak akurat, segmentasi DMA, kerusakan jaringan.',5,1),
(6,'F','Pengadaan & Investasi','Penguatan Tata Kelola Perusahaan & SDM',5,
 'Risiko pada pengadaan barang/jasa, proyek investasi, dan pemanfaatan aset. Cakupan: pengadaan terlambat, spesifikasi tidak jelas, harga melebihi estimasi, aset tak terpakai, dokumen tidak lengkap, keterlambatan proyek.',6,1),
(7,'G','SDM','Penguatan Tata Kelola Perusahaan & SDM',2,
 'Risiko pada kompetensi, ketersediaan, perilaku, dan kelembagaan SDM. Cakupan: kompetensi tidak sesuai, ketergantungan pada individu, absensi/turnover, produktivitas, succession planning, beban biaya pegawai.',7,1),
(8,'H','IT & Digitalisasi','Efisiensi Operasional Berbasis Data',2,
 'Risiko pada sistem informasi, infrastruktur TI, data, dan keamanan digital. Cakupan: server/system down, kehilangan basis data, backup gagal, akses tidak sah, manipulasi data, layanan digital terganggu.',8,1),
(9,'I','AMDK Ijen Water','Peningkatan Pelayanan Pelanggan',23,
 'Risiko pada unit usaha Air Minum Dalam Kemasan. Cakupan: penjualan turun, biaya produksi AMDK, produk tidak laku, sertifikasi/mutu, margin, piutang distributor, distribusi lemah.',9,1),
(10,'J','Kepatuhan, Legal & Reputasi','Penguatan Tata Kelola Perusahaan & SDM',8,
 'Risiko pada regulasi, kontrak, sengketa hukum, dokumen legal, dan reputasi organisasi. Cakupan: perubahan regulasi, ketidakpatuhan, temuan audit, tuntutan hukum/sengketa, pemberitaan negatif, keluhan publik.',10,1),
(11,'K','Kejadian Eksternal, K3 & ESG','Penguatan Tata Kelola Perusahaan & SDM',2,
 'GRUP TAMBAHAN (tidak tercakup A-J): risiko eksternal di luar kendali organisasi, keselamatan & kesehatan kerja, serta aspek lingkungan-sosial-governance. Cakupan: kekeringan/banjir/perubahan iklim, insiden K3, izin lingkungan, K3L, gangguan pihak ketiga, kenaikan harga energi, kepatuhan ESG.',11,1);

-- ---------------------------------------------------------------------
-- 2 · 4 LEVEL RISIKO — kolom "KETERANGAN KATEGORI RISIKO" dari
--     Risk_Register_Template_Perumdam_Ijen_Tirta.csv (RKAP 2027)
--     SKALA LAMA (mr_tingkat_risiko, 5 level) TIDAK DIUBAH — kedua
--     modul sengaja berbeda; UI wajib menampilkan nama skala.
--     skor L x D = min*1 s/d max*1 selalu dalam 1..25.
-- ---------------------------------------------------------------------
INSERT INTO `mri_tingkat_risiko`
  (`id_tingkat`,`urut_tampil`,`nama_tingkat`,`skor_min`,`skor_max`,`warna`,`aksi`,`deskripsi`,`status`)
VALUES
(1,1,'Rendah',1,5,'#22c55e','Accept',
 '1-Rendah (1-5): Diterima, monitor rutin',1),
(2,2,'Sedang',6,9,'#eab308','Monitor',
 '2-Sedang (6-9): Monitor berkala, eskalasi jika naik',1),
(3,3,'Tinggi',10,15,'#f97316','Treat',
 '3-Tinggi (10-15): Penanganan prioritas, direksi monitor',1),
(4,4,'Kritis',16,25,'#ef4444','Escalate',
 '4-Kritis (16-25): Tindakan segera, notifikasi direksi',1);

-- ---------------------------------------------------------------------
-- 3 · 14 KATEGORI RISIKO — QRMO slide 11 + kategori ke-14 HSE/K3-ESG
--     (slide 4 objektif #5 "integrasi ... ke dalam GRC dan ESG";
--      slide 63/65 membahas ESG; legacy mr_kategori_risiko punya
--      "Risiko Keselamatan Kerja" yang tidak punya padanan di 13
--      kategori QRMO).
--     nama_kat    = label QRMO (English, sesuai materi)
--     nama_alias  = label Indonesia (untuk staf)
--     kode_legacy = kode kategori legacy yang dipetakan (RS, RKI, ...)
--     ket. slide 11: "Satu risiko dapat terkait lebih dari satu
--     kategori. Pilih KATEGORI UTAMA" -> kolom tunggal, tanpa junction.
-- ---------------------------------------------------------------------
INSERT INTO `mri_kategori_risiko`
  (`id_kategori`,`kode_kat`,`nama_kat`,`nama_alias`,`kode_legacy`,`deskripsi_kat`,`contoh_kat`,`urut`,`status`)
VALUES
(1,'STR','Strategic','Strategis','RS,RKI',
 'Risiko yang dapat menghambat pencapaian arah, sasaran, atau strategi organisasi.',
 'Gagal mencapai target NRW / ekspansi',1,1),
(2,'OPE','Operational','Operasional','RO',
 'Risiko akibat kegagalan proses, aktivitas, aset, atau operasional harian.',
 'Pompa / WTP terganggu',2,1),
(3,'FIN','Finance','Keuangan','RKU,RA',
 'Risiko yang memengaruhi kondisi, biaya, pendapatan, atau kinerja keuangan.',
 'Biaya operasi meningkat',3,1),
(4,'LIQ','Liquidity','Likuiditas',NULL,
 'Risiko ketidakcukupan kas untuk memenuhi kewajiban saat jatuh tempo.',
 'Arus kas tidak cukup',4,1),
(5,'CRE','Credit','Kredit',NULL,
 'Risiko kerugian karena pelanggan atau pihak lain tidak memenuhi kewajibannya.',
 'Piutang pelanggan meningkat',5,1),
(6,'MKT','Market','Pasar',NULL,
 'Risiko akibat perubahan kondisi pasar yang memengaruhi biaya atau nilai ekonomi.',
 'Perubahan harga input',6,1),
(7,'LEG','Legal','Legal / Hukum','RL',
 'Risiko yang timbul dari kontrak, sengketa, atau aspek hukum.',
 'Sengketa kontrak',7,1),
(8,'REG','Regulatory','Regulasi / Kepatuhan','RKP,RKJ',
 'Risiko akibat perubahan, ketidakpatuhan, atau tuntutan ketentuan/regulasi.',
 'Perubahan ketentuan',8,1),
(9,'REP','Reputation','Reputasi','RR',
 'Risiko yang dapat menurunkan kepercayaan dan citra organisasi.',
 'Keluhan publik meningkat',9,1),
(10,'TEC','Technology','Teknologi / TI',NULL,
 'Risiko akibat gangguan teknologi, sistem informasi, aplikasi, atau infrastruktur TI.',
 'Gangguan billing / SCADA',10,1),
(11,'FRD','Fraud','Fraud / Korupsi','RF',
 'Risiko akibat tindakan sengaja untuk memperoleh keuntungan yang tidak semestinya.',
 'Manipulasi pengadaan',11,1),
(12,'PER','Personnel','SDM','RSD',
 'Risiko terkait kompetensi, kapasitas, ketersediaan, atau perilaku SDM.',
 'Kekurangan kompetensi',12,1),
(13,'EVT','Event','Kejadian Eksternal',NULL,
 'Risiko akibat kejadian eksternal/alami yang dapat mengganggu kegiatan organisasi.',
 'Banjir / kekeringan',13,1),
(14,'HSE','Health, Safety & Environment','K3 & ESG','RKK',
 'RISIKO TAMBAHAN: keselamatan & kesehatan kerja, aspek lingkungan, serta tanggung jawab sosial. Dilatarbelakangi QRMO slide 4 objektif #5 (integrasi GRC & ESG), slide 63 dan slide 65.',
 'Kecelakaan kerja, izin lingkungan, keluhan masyarakat terdampak',14,1);

-- ---------------------------------------------------------------------
-- 4 · RISK OWNER — nama & NIK ASLI diambil dari mr_petugas_ttd
--     (jabatan_ttd = 'Penanggung Jawab Risiko'), plus Direktur.
--     kewenangan = teks default berdasarkan peran (QRMO slide 60-61);
--     silakan dikonfirmasi/disesuaikan di manrisk/Pengaturan.
--     Catatan: LINDA ANGGRAITA (12 & 21) dan SANUR (9 & 22) memegang
--     2 unit berbeda -> dibedakan lewat nik + id_bagian.
-- ---------------------------------------------------------------------
INSERT INTO `mri_risk_owner`
  (`id_risk_owner`,`nama_owner`,`nik`,`jabatan_owner`,`id_bagian`,`kewenangan`,`status`)
VALUES
(1,'APRIL ARIESTHA BHIRAWA, S.E',NULL,'Direktur',1,
 'Menetapkan risk appetite dan kriteria risiko; memutuskan treatment strategis dan pengalokasian sumber daya; menerima eskalasi risiko kritis; mengesahkan Laporan Manajemen Risiko (QRMO slide 60-61).',1),
(2,'YULIATIN JUMARIYAH','288 95 102','Kabag. Umum',2,
 'Menetapkan tindakan pengendalian pada lingkup K3, SDM, dan administrasi umum; memantau residual risk; melaporkan perubahan signifikan ke Direksi.',1),
(3,'DICKY ERFAN SEPTIONO','104 10 112','Kabag. Keuangan',3,
 'Menetapkan tindakan pengendalian pada lingkup pendapatan, biaya, piutang, dan kas; memantau residual risk; melaporkan perubahan signifikan ke Direksi.',1),
(4,'TAUFIQURRAHMAN','311 19 158','Kabag. Pemeliharaan',4,
 'Menetapkan tindakan pengendalian pada lingkup produksi, jaringan, dan transmisi-distribusi; memantau residual risk; melaporkan perubahan signifikan ke Direksi.',1),
(5,'MOHAMMMAD YUNUS ANIS','019 95 099','Kabag. Perencanaan',5,
 'Menetapkan tindakan pengendalian pada lingkup pengadaan, investasi, dan perencanaan teknis; memantau residual risk; melaporkan perubahan signifikan ke Direksi.',1),
(6,'RAHMAT FEBRI EKO TANYONO','014 03 046','Kabag. Langganan',6,
 'Menetapkan tindakan pengendalian pada lingkup pelanggan, pelayanan, dan penagihan; memantau residual risk; melaporkan perubahan signifikan ke Direksi.',1),
(7,'SULISTYOWATI','110 12 121','Kepala UPK',7,
 'Mengidentifikasi, memantau, dan melaporkan risiko pada lingkup UPK; menetapkan tindakan pengendalian bersama divisi teknis.',1),
(8,'I MADE SUARJAYA','112 92 083','Ketua Satuan Pengawas Intern',8,
 'Mengawasi efektivitas pengendalian internal, menindaklanjuti temuan audit, dan mengesahkan kewenangan penguncian modul.',1),
(9,'SANUR','019 93 092','Kepala UPK',9,
 'Mengidentifikasi, memantau, dan melaporkan risiko pada lingkup UPK; menetapkan tindakan pengendalian bersama divisi teknis.',1),
(10,'SOHIBUL FADILAH','201 17 146','Kepala UPK',10,
 'Mengidentifikasi, memantau, dan melaporkan risiko pada lingkup UPK; menetapkan tindakan pengendalian bersama divisi teknis.',1),
(11,'RUDI HASYIM','014 03 108','Kepala UPK',11,
 'Mengidentifikasi, memantau, dan melaporkan risiko pada lingkup UPK; menetapkan tindakan pengendalian bersama divisi teknis.',1),
(12,'LINDA ANGGRAITA','201 17 147','Kepala UPK',12,
 'Mengidentifikasi, memantau, dan melaporkan risiko pada lingkup UPK; menetapkan tindakan pengendalian bersama divisi teknis.',1),
(13,'WIWIK','10414124','Kepala UPK',13,
 'Mengidentifikasi, memantau, dan melaporkan risiko pada lingkup UPK; menetapkan tindakan pengendalian bersama divisi teknis.',1),
(14,'M ARIEF TEGUH ANDIYANTO','311 19 152','Kepala UPK',14,
 'Mengidentifikasi, memantau, dan melaporkan risiko pada lingkup UPK; menetapkan tindakan pengendalian bersama divisi teknis.',1),
(15,'SUWARNA','015 92 055','Kepala UPK',15,
 'Mengidentifikasi, memantau, dan melaporkan risiko pada lingkup UPK; menetapkan tindakan pengendalian bersama divisi teknis.',1),
(16,'SUPANGKAT HARIANTO','201 17 144','Kepala UPK',16,
 'Mengidentifikasi, memantau, dan melaporkan risiko pada lingkup UPK; menetapkan tindakan pengendalian bersama divisi teknis.',1),
(17,'MOHAMMAD ROIS','110 12 118','Kepala UPK',17,
 'Mengidentifikasi, memantau, dan melaporkan risiko pada lingkup UPK; menetapkan tindakan pengendalian bersama divisi teknis.',1),
(18,'HIDAYATULLAH FIRDAUS','311 19 156','Kepala UPK',18,
 'Mengidentifikasi, memantau, dan melaporkan risiko pada lingkup UPK; menetapkan tindakan pengendalian bersama divisi teknis.',1),
(19,'SAYUDI PRANAYUDA','201 17 145','Kepala UPK',19,
 'Mengidentifikasi, memantau, dan melaporkan risiko pada lingkup UPK; menetapkan tindakan pengendalian bersama divisi teknis.',1),
(20,'ARSONO AGUS PRAYUDI','014 93 085','Kepala UPK',20,
 'Mengidentifikasi, memantau, dan melaporkan risiko pada lingkup UPK; menetapkan tindakan pengendalian bersama divisi teknis.',1),
(21,'LINDA ANGGRAITA','201 17 147','Kepala UPK',21,
 'Mengidentifikasi, memantau, dan melaporkan risiko pada lingkup UPK; menetapkan tindakan pengendalian bersama divisi teknis.',1),
(22,'SANUR','019 93 092','Kepala UPK',22,
 'Mengidentifikasi, memantau, dan melaporkan risiko pada lingkup UPK; menetapkan tindakan pengendalian bersama divisi teknis.',1),
(23,'SAIFUL BARI','311 19 153','Manager Unit AMDK',23,
 'Menetapkan tindakan pengendalian pada lingkup unit AMDK; memantau residual risk unit; melaporkan perubahan signifikan ke Direksi.',1);

-- ---------------------------------------------------------------------
-- 5 · PIC (pelaksana tindakan) — terpisah dari Risk Owner.
--     Peran diambil dari mr_pemilik_risiko legacy (11 peran).
--     PIC = siapa MELAKUKAN; Risk Owner = siapa BERTANGGUNG JAWAB.
-- ---------------------------------------------------------------------
INSERT INTO `mri_pic`
  (`id_pic`,`nama_pic`,`jabatan_pic`,`id_bagian`,`kompetensi`,`status`)
VALUES
(1,'Kepala UPK','Kepala Unit Kerja',NULL,
 'Pengelolaan operasional dan jaringan unit',1),
(2,'Kabag Langganan','Kepala Bagian Langganan',6,
 'Pelayanan pelanggan, penagihan, dan pengembangan SR',1),
(3,'Kabag Umum','Kepala Bagian Umum',2,
 'Administrasi umum, K3, dan SDM',1),
(4,'Kabag Keuangan','Kepala Bagian Keuangan',3,
 'Pembukuan, anggaran, dan pengendalian kas',1),
(5,'Kabag Pemeliharaan','Kepala Bagian Pemeliharaan',4,
 'Produksi, transmisi-distribusi, dan perawatan jaringan',1),
(6,'Kabag Perencanaan','Kepala Bagian Perencanaan',5,
 'Perencanaan teknis dan pengadaan',1),
(7,'Pelaksana Teknik','Pelaksana Teknik',NULL,
 'Pelaksanaan pekerjaan teknis lapangan',1),
(8,'Pelaksana Administrasi','Pelaksana Administrasi',NULL,
 'Pelaksanaan urusan administrasi',1),
(9,'Staf Teknik','Staf Teknik',NULL,
 'Pendampingan pekerjaan teknis',1),
(10,'Staf Administrasi','Staf Administrasi',NULL,
 'Pendampingan urusan administrasi',1),
(11,'Manager AMDK','Manager Unit AMDK',23,
 'Pengelolaan usaha Air Minum Dalam Kemasan',1);

-- ---------------------------------------------------------------------
-- 6 · PETUGAS TANDA TANGAN — disalin VERBATIM dari mr_petugas_ttd
--     (25 baris) agar tampilan laporan konsisten dengan modul legacy.
--     kode_ttd dibuat baru (tidak ada di legacy) untuk keperluan unik.
-- ---------------------------------------------------------------------
INSERT INTO `mri_petugas_ttd`
  (`id_ttd`,`kode_ttd`,`jabatan_ttd`,`id_bagian`,`nama_petugas`,`nik`,`status`)
VALUES
(1,'KETUA_SPI','Ketua SPI',NULL,'I MADE SUARJAYA','112 92 083',1),
(2,'KETUA_SATGAS_MR','Ketua Satgas MR',NULL,'DICKY ERFAN SEPTIONO','014 10 112',1),
(3,'DIREKTUR','Direktur PDAM',NULL,'APRIL ARIESTHA BHIRAWA, S.E',NULL,1),
(4,'PJR_B3','Penanggung Jawab Risiko',3,'DICKY ERFAN SEPTIONO','104 10 112',1),
(5,'PJR_B7','Penanggung Jawab Risiko',7,'SULISTYOWATI','110 12 121',1),
(6,'PJR_B2','Penanggung Jawab Risiko',2,'YULIATIN JUMARIYAH','288 95 102',1),
(7,'PJR_B6','Penanggung Jawab Risiko',6,'RAHMAT FEBRI EKO TANYONO','014 03 046',1),
(8,'PJR_B4','Penanggung Jawab Risiko',4,'TAUFIQURRAHMAN','311 19 158',1),
(9,'PJR_B5','Penanggung Jawab Risiko',5,'MOHAMMMAD YUNUS ANIS','019 95 099',1),
(10,'PJR_B8','Penanggung Jawab Risiko',8,'I MADE SUARJAYA','112 92 083',1),
(11,'PJR_B9','Penanggung Jawab Risiko',9,'SANUR','019 93 092',1),
(12,'PJR_B10','Penanggung Jawab Risiko',10,'SOHIBUL FADILAH','201 17 146',1),
(13,'PJR_B11','Penanggung Jawab Risiko',11,'RUDI HASYIM','014 03 108',1),
(14,'PJR_B12','Penanggung Jawab Risiko',12,'LINDA ANGGRAITA','201 17 147',1),
(15,'PJR_B13','Penanggung Jawab Risiko',13,'WIWIK','10414124',1),
(16,'PJR_B14','Penanggung Jawab Risiko',14,'M ARIEF TEGUH ANDIYANTO','311 19 152',1),
(17,'PJR_B15','Penanggung Jawab Risiko',15,'SUWARNA','015 92 055',1),
(18,'PJR_B16','Penanggung Jawab Risiko',16,'SUPANGKAT HARIANTO','201 17 144',1),
(19,'PJR_B17','Penanggung Jawab Risiko',17,'MOHAMMAD ROIS','110 12 118',1),
(20,'PJR_B18','Penanggung Jawab Risiko',18,'HIDAYATULLAH FIRDAUS','311 19 156',1),
(21,'PJR_B19','Penanggung Jawab Risiko',19,'SAYUDI PRANAYUDA','201 17 145',1),
(22,'PJR_B20','Penanggung Jawab Risiko',20,'ARSONO AGUS PRAYUDI','014 93 085',1),
(23,'PJR_B21','Penanggung Jawab Risiko',21,'LINDA ANGGRAITA','201 17 147',1),
(24,'PJR_B22','Penanggung Jawab Risiko',22,'SANUR','019 93 092',1),
(25,'PJR_B23','Penanggung Jawab Risiko',23,'SAIFUL BARI','311 19 153',1);

-- ---------------------------------------------------------------------
-- 7 · RISK APPETITE / TOLERANCE / CAPACITY — per kategori
--     Sumber: QRMO slide 64.
--     PENTING: slide 64 menyatakan angka pada materi BERSIFAT
--     ILUSTRATIF dan "parameter final harus mengikuti kebijakan /
--     risk appetite resmi perusahaan". Maka TIDAK ADA angka batas yang
--     dikarang di sini — hanya definisi + pernyataan bahwa parameter
--     final belum ditetapkan. Isi sebenarnya lewat manrisk/Pengaturan.
-- ---------------------------------------------------------------------
INSERT INTO `mri_appetite`
  (`id_appetite`,`tahun`,`id_kategori`,`risk_appetite`,`risk_tolerance`,`risk_capacity`,`batas_eskalasi`,`status`)
VALUES
(1,2027,1,'Tingkat & jenis risiko Strategis yang bersedia diambil untuk mengejar sasaran organisasi. Investasi dan perubahan strategis: moderat, perlu manfaat pelayanan & business case yang jelas.','Batas variasi terhadap sasaran yang masih dapat diterima. Keterlambatan proyek: maksimal 30 hari (ILUSTRATIF QRMO slide 64).','Dibatasi cash flow, kemampuan pendanaan, dan covenant yang berlaku.','Lewat ambang batas kebijakan resmi perusahaan -> eskalasi ke Direksi.',1),
(2,2027,2,'Tingkat & jenis risiko Operasional yang bersedia diambil untuk mengejar sasaran organisasi.','Batas variasi / penyimpangan risiko yang masih dapat diterima. Parameter final mengikuti kebijakan resmi perusahaan.','Dibatasi cash flow, kemampuan pendanaan, dan covenant yang berlaku.','Lewat ambang batas kebijakan resmi perusahaan -> eskalasi ke Direksi.',1),
(3,2027,3,'Tingkat & jenis risiko Keuangan yang bersedia diambil untuk mengejar sasaran organisasi.','Batas variasi / penyimpangan risiko yang masih dapat diterima. Parameter final mengikuti kebijakan resmi perusahaan.','Dibatasi cash flow, kemampuan pendanaan, dan covenant yang berlaku.','Lewat ambang batas kebijakan resmi perusahaan -> eskalasi ke Direksi.',1),
(4,2027,4,'Tingkat & jenis risiko Likuiditas yang bersedia diambil untuk mengejar sasaran organisasi.','Batas variasi / penyimpangan risiko yang masih dapat diterima. Parameter final mengikuti kebijakan resmi perusahaan.','Dibatasi cash flow, kemampuan pendanaan, dan covenant yang berlaku.','Lewat ambang batas kebijakan resmi perusahaan -> eskalasi ke Direksi.',1),
(5,2027,5,'Tingkat & jenis risiko Kredit yang bersedia diambil untuk mengejar sasaran organisasi.','Batas variasi / penyimpangan risiko yang masih dapat diterima. Parameter final mengikuti kebijakan resmi perusahaan.','Dibatasi cash flow, kemampuan pendanaan, dan covenant yang berlaku.','Lewat ambang batas kebijakan resmi perusahaan -> eskalasi ke Direksi.',1),
(6,2027,6,'Tingkat & jenis risiko Pasar yang bersedia diambil untuk mengejar sasaran organisasi.','Batas variasi / penyimpangan risiko yang masih dapat diterima. Parameter final mengikuti kebijakan resmi perusahaan.','Dibatasi cash flow, kemampuan pendanaan, dan covenant yang berlaku.','Lewat ambang batas kebijakan resmi perusahaan -> eskalasi ke Direksi.',1),
(7,2027,7,'Tingkat & jenis risiko Legal yang bersedia diambil untuk mengejar sasaran organisasi.','Batas variasi / penyimpangan risiko yang masih dapat diterima. Parameter final mengikuti kebijakan resmi perusahaan.','Dibatasi cash flow, kemampuan pendanaan, dan covenant yang berlaku.','Lewat ambang batas kebijakan resmi perusahaan -> eskalasi ke Direksi.',1),
(8,2027,8,'Tingkat & jenis risiko Kepatuhan yang bersedia diambil untuk mengejar sasaran organisasi.','Batas variasi / penyimpangan risiko yang masih dapat diterima. Parameter final mengikuti kebijakan resmi perusahaan.','Dibatasi cash flow, kemampuan pendanaan, dan covenant yang berlaku.','Lewat ambang batas kebijakan resmi perusahaan -> eskalasi ke Direksi.',1),
(9,2027,9,'Tingkat & jenis risiko Reputasi yang bersedia diambil untuk mengejar sasaran organisasi.','Batas variasi / penyimpangan risiko yang masih dapat diterima. Parameter final mengikuti kebijakan resmi perusahaan.','Dibatasi cash flow, kemampuan pendanaan, dan covenant yang berlaku.','Lewat ambang batas kebijakan resmi perusahaan -> eskalasi ke Direksi.',1),
(10,2027,10,'Tingkat & jenis risiko Teknologi yang bersedia diambil untuk mengejar sasaran organisasi. Investasi teknologi: moderat, perlu manfaat pelayanan & business case.','Batas variasi / penyimpangan risiko yang masih dapat diterima. Keterlambatan proyek: maksimal 30 hari (ILUSTRATIF QRMO slide 64).','Dibatasi cash flow, kemampuan pendanaan, dan covenant yang berlaku.','Lewat ambang batas kebijakan resmi perusahaan -> eskalasi ke Direksi.',1),
(11,2027,11,'Tingkat & jenis risiko Fraud yang bersedia diambil untuk mengejar sasaran organisasi.','Batas variasi / penyimpangan risiko yang masih dapat diterima. Parameter final mengikuti kebijakan resmi perusahaan.','Dibatasi cash flow, kemampuan pendanaan, dan covenant yang berlaku.','Lewat ambang batas kebijakan resmi perusahaan -> eskalasi ke Direksi.',1),
(12,2027,12,'Tingkat & jenis risiko SDM yang bersedia diambil untuk mengejar sasaran organisasi.','Batas variasi / penyimpangan risiko yang masih dapat diterima. Parameter final mengikuti kebijakan resmi perusahaan.','Dibatasi cash flow, kemampuan pendanaan, dan covenant yang berlaku.','Lewat ambang batas kebijakan resmi perusahaan -> eskalasi ke Direksi.',1),
(13,2027,13,'Tingkat & jenis risiko Kejadian Eksternal yang bersedia diambil untuk mengejar sasaran organisasi.','Batas variasi / penyimpangan risiko yang masih dapat diterima. Parameter final mengikuti kebijakan resmi perusahaan.','Dibatasi cash flow, kemampuan pendanaan, dan covenant yang berlaku.','Lewat ambang batas kebijakan resmi perusahaan -> eskalasi ke Direksi.',1),
(14,2027,14,'Tingkat & jenis risiko K3 & ESG yang bersedia diambil untuk mengejar sasaran organisasi. Risiko yang membahayakan jiwa dan pelanggaran izin lingkungan termasuk lingkup risiko yang tidak bersedia diambil (QRMO slide 65: "memenuhi ketentuan K3 & lingkungan").','Batas variasi / penyimpangan risiko yang masih dapat diterima. Parameter final mengikuti kebijakan resmi perusahaan.','Dibatasi cash flow, kemampuan pendanaan, dan covenant yang berlaku.','Insiden K3 atau pelanggaran izin lingkungan -> eskalasi segera ke Direksi.',1);

-- ---------------------------------------------------------------------
-- 8 · KUNCI MODUL — 5 kode diwarisi dari mr_pengaturan_kunci (legacy)
--     + 2 kode baru untuk tahap yang tidak ada di modul lama.
--     status_kunci: 0 = terbuka (boleh diubah), 1 = terkunci.
-- ---------------------------------------------------------------------
INSERT INTO `mri_kunci` (`id_kunci`,`kode_kunci`,`nama_kunci`,`status_kunci`)
VALUES
(1,'input_risiko','Input Risiko',0),
(2,'profil_risiko','Edit Profil Risiko',0),
(3,'analisa_risiko','Edit Analisa Risiko',0),
(4,'penanganan_risiko','Edit Penanganan Risiko',0),
(5,'monitoring_risiko','Edit Monitoring Risiko',0),
(6,'objektif_risiko','Edit Objektif / Target RKAP',0),
(7,'laporan_risiko','Edit Laporan Manajemen Risiko',0);
