-- =====================================================================
-- 04 · SEED RISK REGISTER 2027 (TAHAP 4-7)
--
-- Sumber 16 baris pertama:
--   D:\MR 2026\Risk_Register_Template_Perumdam_Ijen_Tirta.csv
-- Sumber 5 baris terakhir (IJT-GOV-*):
--   DRAFT buatan untuk melengkapi Grup A (Tata Kelola & Strategis) yang
--   KOSONG di CSV. Skor L/D pada baris draft = DRAFT, mohon direview.
--
-- PEMBERSIHAN TEKS (keputusan user: diperbaiki, diff ditampilkan):
--   流失 pelanggan       -> kehilangan pelanggan
--   Kenaikan电价         -> Kenaikan harga listrik
--   Mutation            -> Mutasi
--   económica           -> kondisi ekonomi
--   inefficiency        -> inefisiensi
--   operation inefficiency -> ketidakefisien operasi
--   Maintenance         -> Pemeliharaan
--   Budget constraint   -> Kendala anggaran
--   Safety audit        -> Audit K3
--   emergency           -> darurat
--   insurance coverage  -> asuransi
--   quality control     -> pengendalian mutu
--   training/Training   -> pelatihan/Pelatihan
--   talent management   -> pengembangan talenta
--   process error       -> kesalahan proses
--   pelanggan komplain  -> pelanggan mengeluh
--   competitor          -> kompetitor
--   social marketing    -> pemasaran sosial
--   missing compliance  -> ketidakpatuhan
--   liability           -> tanggung jawab hukum
--   klimat              -> iklim ekstrem
--   Sumber nilai & seluruh ANGKA (L, D, NILAI INHERENT/RESIDUAL)
--   TIDAK diubah — semua nilai CSV terverifikasi = L x D.
--
-- VERIFIKASI: 16/16 baris CSV memiliki nilai_inherent = L*D dan
-- nilai_residual = L*D. Berbeda dengan mr_matrik_risiko legacy yang
-- 22 dari 25 sel bukan perkalian.
--
-- Jalankan setelah 03_seed_objektif_2027.sql.
-- =====================================================================
SET NAMES utf8mb4;

-- =====================================================================
-- BAGIAN 1 · 16 RISIKO DARI CSV
-- =====================================================================
INSERT INTO `mri_register`
 (`id_register`,`kode_risiko`,`tahun`,`triwulan_awal`,`id_upk`,`id_lingkup`,`id_kategori`,
  `tipe_risiko`,`kegiatan`,`sasaran_kpi`,`sumber_risiko`,`risiko`,`penyebab`,`peristiwa_risiko`,
  `status_kendali`,`status_risiko`,`keterangan`,`created_by`)
VALUES
(1,'IJT-OPR-01',2027,1,4,1,2,'Ancaman',
 'Produksi & distribusi air dari 25 sumur bor',
 'Kontinuitas produksi air',
 'Usia pompa tinggi, kurangnya pemeliharaan pencegahan, suku cadang tidak tersedia',
 'Gangguan 25 sumur bor menyebabkan kontinuitas produksi air terganggu',
 'Usia pompa tinggi, kurangnya pemeliharaan pencegahan, suku cadang tidak tersedia',
 'Gangguan pada 25 sumur bor','C','AKTIF','Prioritas sedang','seed'),
(2,'IJT-OPR-02',2027,1,4,1,2,'Ancaman',
 'Pengolahan & pengawasan kualitas air',
 'Kualitas air sesuai standar',
 'Pencemaran sumber, sedimentasi tinggi, gangguan instalasi pengolahan air',
 'Kualitas air tidak memenuhi standar baku mutu',
 'Pencemaran sumber, sedimentasi tinggi, gangguan instalasi pengolahan air',
 'Kualitas air tidak memenuhi standar','C','AKTIF','Monitor berkala','seed'),
(3,'IJT-OPR-03',2027,1,4,1,2,'Ancaman',
 'Operasional jaringan transmisi-distribusi',
 'Penurunan NRW',
 'Kebocoran pipa, jaringan tua ACP, tekanan tinggi',
 'Kehilangan air tinggi (NRW) melebihi batas wajar',
 'Kebocoran pipa, jaringan tua ACP, tekanan tinggi',
 'Kehilangan air tinggi (NRW)','C','AKTIF','Prioritas tinggi - RKAP 2027 fokus efisiensi','seed'),
(4,'IJT-OPR-04',2027,1,6,1,2,'Ancaman',
 'Pelayanan & pengembangan pelanggan',
 'Pola konsumsi 13,43%',
 'Sambungan tutup/cabut melebihi sambungan baru, kehilangan pelanggan',
 'Penurunan pola konsumsi di bawah target RKAP',
 'Sambungan tutup/cabut melebihi sambungan baru, kehilangan pelanggan',
 'Pola konsumsi turun','C','AKTIF','Monitor pertumbuhan sambungan','seed'),
(5,'IJT-KEU-01',2027,1,3,1,5,'Ancaman',
 'Penagihan & pengelolaan piutang pelanggan',
 'Efisiensi penagihan 92,98%',
 'Pelanggan tidak bayar, kondisi ekonomi lemah, sistem penagihan belum optimal',
 'Piutang tak tertagih tinggi sehingga arus kas terganggu',
 'Pelanggan tidak bayar, kondisi ekonomi lemah, sistem penagihan belum optimal',
 'Piutang tak tertagih meningkat','C','AKTIF','Prioritas tinggi - dampak langsung ke RKAP','seed'),
(6,'IJT-KEU-02',2027,1,3,1,6,'Ancaman',
 'Produksi air & pengelolaan biaya operasi',
 'Laba bersih Rp 2,51M',
 'Kenaikan harga listrik, harga bahan kimia, inefisiensi',
 'Biaya produksi naik sehingga laba turun dari target',
 'Kenaikan harga listrik, harga bahan kimia, inefisiensi',
 'Biaya produksi naik','C','AKTIF','Monitoring HPP bulanan','seed'),
(7,'IJT-KEU-03',2027,1,5,1,2,'Ancaman',
 'Pelaksanaan program investasi 2027',
 'Realisasi investasi Rp 2,3M',
 'Proses pengadaan lama, vendor terlambat, perubahan desain',
 'Keterlambatan realisasi investasi',
 'Proses pengadaan lama, vendor terlambat, perubahan desain',
 'Keterlambatan investasi','C','AKTIF','Monitoring bulanan','seed'),
(8,'IJT-IT-01',2027,1,2,1,10,'Ancaman',
 'Layanan digital, billing, dan sistem informasi',
 'Layanan digital',
 'Server down, serangan siber, jaringan lemah',
 'Gangguan sistem billing/IT yang mengganggu pelayanan',
 'Server down, serangan siber, jaringan lemah',
 'Gangguan sistem billing/IT','C','AKTIF','Risiko rendah namun dampak tinggi','seed'),
(9,'IJT-HR-01',2027,1,2,1,12,'Ancaman',
 'Pengelolaan kompetensi 172 pegawai',
 '172 pegawai kompeten',
 'Mutasi, pensiun, kompetensi tidak sesuai',
 'Kekurangan SDM terlatih sehingga kualitas layanan menurun',
 'Mutasi, pensiun, kompetensi tidak sesuai',
 'Kekurangan SDM terlatih','C','AKTIF','Prioritas menengah','seed'),
(10,'IJT-K3-01',2027,1,2,1,14,'Ancaman',
 'Operasional lapangan & keselamatan kerja',
 'Zero accident',
 'SOP tidak dipatuhi, peralatan rusak, kelalaian',
 'Kecelakaan kerja di lingkungan operasional',
 'SOP tidak dipatuhi, peralatan rusak, kelalaian',
 'Kecelakaan kerja','C','AKTIF','Wajib dipantau','seed'),
(11,'IJT-AMDK-01',2027,1,23,1,6,'Ancaman',
 'Usaha Air Minum Dalam Kemasan Ijen Water',
 'Penjualan Ijen Water naik',
 'Persaingan pasar, distribusi lemah',
 'Penjualan AMDK turun sehingga margin unit menurun',
 'Persaingan pasar, distribusi lemah',
 'Penjualan AMDK turun','C','AKTIF','Peluang pertumbuhan (growth opportunity)','seed'),
(12,'IJT-EXT-01',2027,1,4,1,13,'Ancaman',
 'Kontinuitas operasional terhadap kejadian eksternal',
 'Kontinuitas operasi',
 'Perubahan iklim, musim kemarau panjang, banjir',
 'Kekeringan/banjir/iklim ekstrem mengganggu kontinuitas operasi',
 'Perubahan iklim, musim kemarau panjang, banjir',
 'Kekeringan/banjir/iklim ekstrem','C','AKTIF','Risiko iklim - strategis','seed'),
(13,'IJT-REG-01',2027,1,8,1,8,'Ancaman',
 'Kepatuhan regulasi & pengawasan internal',
 'Kepatuhan regulasi',
 'Perubahan kebijakan, ketidakpatuhan',
 'Ketidakpatuhan regulasi yang berdampak pada sanksi dan reputasi',
 'Perubahan kebijakan, ketidakpatuhan',
 'Ketidakpatuhan regulasi','C','AKTIF','Berjalan terus (ongoing compliance)','seed'),
(14,'IJT-PEL-01',2027,1,6,1,2,'Ancaman',
 'Pengembangan & pertumbuhan pelanggan',
 'Target SR Baru 938',
 'Permintaan rendah, kompetitor, kondisi ekonomi',
 'Target sambungan baru tidak tercapai sehingga pertumbuhan melambat',
 'Permintaan rendah, kompetitor, kondisi ekonomi',
 'Target sambungan baru tidak tercapai','C','AKTIF','Sesuaikan dengan RKAP','seed'),
(15,'IJT-PEL-02',2027,1,6,1,2,'Ancaman',
 'Penggantian & pemeriksaan meter pelanggan',
 '938 SR, 727 penggantian meter',
 'Kesalahan proses, pelanggan mengeluh',
 'Gangguan saat penggantian meter sehingga kepuasan pelanggan turun',
 'Kesalahan proses, pelanggan mengeluh',
 'Gangguan saat penggantian meter','C','AKTIF','Prioritas rendah','seed'),
(16,'IJT-OPR-05',2027,1,4,1,2,'Ancaman',
 'Pemeliharaan jaringan transmisi-distribusi',
 'Pemeliharaan jaringan Rp 157,8jt',
 'Kendala anggaran, SDM terbatas',
 'Pemeliharaan tidak optimal sehingga jaringan rusak dan kebocoran meningkat',
 'Kendala anggaran, SDM terbatas',
 'Pemeliharaan tidak optimal','C','AKTIF','Alokasi RKAP 2027','seed');

-- =====================================================================
-- BAGIAN 2 · 5 DRAFT GRUP A (Tata Kelola & Strategis) — REVIEW DIPERLUKAN
-- Grup A KOSONG di CSV padahal menjadi tema utama Slide 17.
-- Seluruh L/D pada bagian ini = DRAFT.
-- INSERT terpisah karena baris draft juga mengisi indikator_emerging
-- dan asumsi_bias (ISO 6.4.2) yang sengaja dibiarkan NULL pada 16
-- baris CSV — data CSV tidak memuat keduanya dan tidak boleh dikarang.
-- =====================================================================
INSERT INTO `mri_register`
 (`id_register`,`kode_risiko`,`tahun`,`triwulan_awal`,`id_upk`,`id_lingkup`,`id_kategori`,
  `tipe_risiko`,`kegiatan`,`sasaran_kpi`,`sumber_risiko`,`risiko`,`penyebab`,`peristiwa_risiko`,
  `indikator_emerging`,`asumsi_bias`,
  `status_kendali`,`status_risiko`,`keterangan`,`created_by`)
VALUES
(17,'IJT-GOV-01',2027,1,1,1,1,'Ancaman',
 'Penyusunan & pelaksanaan RKAP 2027',
 'Laba Bersih Rp 2.511.247.563 / Pendapatan Usaha Rp 22.381.036.762',
 'Basis pertumbuhan pelanggan dan efisiensi belum divalidasi; angka Pendapatan Usaha dan Laba Kotor tidak terekonsiliasi antar slide (status_rekonsiliasi = BELUM)',
 'Realisasi RKAP 2027 tidak tercapai sehingga seluruh sasaran tahunan tidak terpenuhi',
 'Basis pertumbuhan pelanggan dan efisiensi belum divalidasi; angka Pendapatan Usaha dan Laba Kotor tidak terekonsiliasi antar slide (status_rekonsiliasi = BELUM)',
 'Realisasi RKAP 2027 tidak tercapai',
 'Selisih Rp 67.005.000 antara Slide 9 dan Slide 8/13 untuk Pendapatan Usaha masih terbuka; capaian pendapatan Smt I 2026 baru 47,10% dari RKAP (Slide 13)',
 'Asumsi pertumbuhan pelanggan dan efisiensi dianggap tercapai tanpa validasi; perbedaan antar slide tidak ditindaklanjuti',
 'UC','AKTIF','DRAFT - menunggu review. Sumber data: seluruh Slide 8-19.','draft-manrisk'),
(18,'IJT-GOV-02',2027,1,1,1,11,'Ancaman',
 'Pengadaan barang & jasa serta pelaksanaan investasi',
 'Realisasi Investasi Rp 2,3M',
 'Tekanan target investasi Rp 2,3 M, spesifikasi tidak detail, e-procurement belum wajib, kapasitas pengawasan terbatas',
 'Manipulasi & penyimpangan proses pengadaan',
 'Tekanan target investasi Rp 2,3 M, spesifikasi tidak detail, e-procurement belum wajib, kapasitas pengawasan terbatas',
 'Manipulasi & penyimpangan pengadaan',
 'Revisi spesifikasi berulang dan keterlambatan jadwal pengadaan yang tidak dijelaskan',
 'Target investasi dianggap menekan prosedur pengadaan sebagaimana mestinya',
 'C','AKTIF','DRAFT - menunggu review. Contoh kategori Fraud QRMO Slide 11: "Manipulasi pengadaan".','draft-manrisk'),
(19,'IJT-GOV-03',2027,1,1,1,8,'Ancaman',
 'Pengawasan internal & tindak lanjut temuan audit',
 'Kepatuhan regulasi & penutupan temuan',
 'PIC unit tanpa kewajiban tegas, tidak ada mekanisme eskalasi, keterbatasan kapasitas pengawasan',
 'Temuan SPI/audit berulang tidak ditindaklanjuti',
 'PIC unit tanpa kewajiban tegas, tidak ada mekanisme eskalasi, keterbatasan kapasitas pengawasan',
 'Temuan audit berulang tidak ditindaklanjuti',
 'Tembusan temuan berulang pada butir yang sama pada dua periode berturut-turut',
 'Tindak lanjut dianggap selesai bila laporan dikirim, tanpa verifikasi efektivitas kontrol',
 'C','AKTIF','DRAFT - menunggu review. Latar: QRMO Slide 17 program SPI "Pengawasan internal & GCG".','draft-manrisk'),
(20,'IJT-GOV-04',2027,1,1,1,1,'Ancaman',
 'Pengambilan keputusan strategis & tata kelola data',
 'Kualitas keputusan strategis organisasi',
 'Sistem terfragmentasi, laporan masih manual, tidak ada jejak audit keputusan',
 'Keputusan strategis diambil tanpa basis data yang andal',
 'Sistem terfragmentasi, laporan masih manual, tidak ada jejak audit keputusan',
 'Keputusan strategis tanpa data yang andal',
 'Laporan keputusan berupa berkas terpisah tanpa jejak versi',
 'Data yang tersedia dianggap cukup untuk keputusan strategis',
 'C','AKTIF','DRAFT - menunggu review. Latar: QRMO Slide 62 "Asumsi utama sudah jelas?" dan Slide 17 program Umum "Digitalisasi arsip, perbaikan Tata Kelola".','draft-manrisk'),
(21,'IJT-GOV-05',2027,1,1,1,1,'Peluang',
 'Pengembangan pasar & efisiensi berbasis digitalisasi',
 'Pendapatan Usaha Rp 22.381.036.762',
 'Wilayah Pamsimas sudah terpasang dan tren digitalisasi meningkat; belum ada mekanisme formal menangkap peluang',
 'Peluang ekspansi layanan dan efisiensi biaya overhead tidak termanfaatkan sehingga pendapatan potensial hilang',
 'Belum ada proses formal identifikasi peluang (kontrol status UC)',
 'Peluang ekspansi & efisiensi belum ditangkap',
 'Tren adopsi layanan digital naik dan wilayah Pamsimas sudah aktif (QRMO Slide 4 & 17)',
 'Peluang dianggap akan tercapi sendiri tanpa mekanisme formal identifikasi dan penugasan PIC',
 'UC','AKTIF','DRAFT - menunggu review. RISIKO TIPE PELUANG (ISO 6.4.2 mensyaratkan "threats and opportunities").','draft-manrisk');

-- =====================================================================
-- AKIBAT — ISO 6.4.2: satu risiko boleh punya >1 akibat ("more than
-- one type of outcome"). Teks dipecah dari kolom DAMPAK CSV.
-- 21 risiko menghasilkan 48 baris akibat.
-- =====================================================================
INSERT INTO `mri_akibat`
 (`id_akibat`,`id_register`,`urutan`,`akibat`,`jenis`,`dampak_kualitatif`)
VALUES
(1,1,1,'Distribusi air terganggu','Non-Finansial','Layanan ke pelanggan terhenti'),
(2,1,2,'Pelanggan tidak terlayani','Non-Finansial','Kepuasan & kepercayaan pelanggan menurun'),
(3,2,1,'Keluhan pelanggan meningkat','Non-Finansial','Beban penanganan pengaduan naik'),
(4,2,2,'Risiko kesehatan masyarakat','Non-Finansial','Dampak sosial langsung ke pengguna air'),
(5,2,3,'Penurunan reputasi','Non-Finansial','Citra organisasi di mata masyarakat'),
(6,3,1,'Pendapatan turun','Finansial','Air terkirim namun tidak tercatat/ditagihkan'),
(7,3,2,'Efisiensi berkurang','Finansial','Biaya per meter naik'),
(8,3,3,'Target RKAP tidak tercapai','Finansial','Berkurangnya realisasi sasaran tahunan'),
(9,4,1,'Target pendapatan tidak tercapai','Finansial','Selisih terhadap sasaran pendapatan'),
(10,4,2,'Pertumbuhan pelanggan negatif','Finansial','Basis pelanggan menyusut'),
(11,5,1,'Arus kas terganggu','Finansial','Kas keluar lebih cepat dari kas masuk'),
(12,5,2,'Target laba tidak tercapai','Finansial','Laba bersih di bawah RKAP'),
(13,5,3,'Likuiditas turun','Finansial','Kemampuan memenuhi kewajiban jangka pendek menurun'),
(14,6,1,'Laba turun dari target','Finansial','Realisasi laba di bawah RKAP'),
(15,6,2,'ROE/ROI turun','Finansial','Efektivitas penggunaan modal & aset menurun'),
(16,7,1,'Target pelayanan mundur','Non-Finansial','Manfaat investasi tertunda'),
(17,7,2,'Under-utilization aset','Finansial','Aset terbeli namun belum menghasilkan'),
(18,8,1,'Penagihan terganggu','Finansial','Pendapatan tidak tercatat tepat waktu'),
(19,8,2,'Data hilang','Non-Finansial','Kehilangan riwayat & informasi pelanggan'),
(20,8,3,'Pelayanan lumpuh','Non-Finansial','Seluruh kanal layanan digital tidak berfungsi'),
(21,9,1,'Kualitas layanan menurun','Non-Finansial','Standar layanan tidak terpenuhi'),
(22,9,2,'Ketidakefisien operasi','Finansial','Biaya operasi per unit naik'),
(23,10,1,'Korban jiwa / cedera','Non-Finansial','Dampak terberak pada manusia'),
(24,10,2,'Gangguan operasi','Non-Finansial','Aktivitas lapangan terhenti'),
(25,10,3,'Tanggung jawab hukum','Finansial','Kewajiban ganti rugi & sanksi'),
(26,11,1,'Laba unit AMDK turun','Finansial','Realisasi laba unit menurun'),
(27,11,2,'Margin negatif','Finansial','Harga jual tidak menutup biaya'),
(28,12,1,'Produksi turun drastis','Finansial','Volume air terproduksi menurun'),
(29,12,2,'Darurat air','Non-Finansial','Kondisi kritis kebutuhan dasar masyarakat'),
(30,13,1,'Sanksi','Finansial','Denda / tindakan administratif'),
(31,13,2,'Temuan audit','Non-Finansial','Catatan pengawasan yang berulang'),
(32,13,3,'Reputasi rusak','Non-Finansial','Kepercayaan pemangku kepentingan turun'),
(33,14,1,'Pertumbuhan pelanggan melambat','Finansial','Jumlah pelanggan tidak bertambah sesuai target'),
(34,14,2,'Target RKAP gagal','Finansial','Sasaran sambungan baru tidak tercapai'),
(35,15,1,'Pelanggan tidak puas','Non-Finansial','Kepuasan pelanggan menurun'),
(36,15,2,'Kesalahan pencatatan','Finansial','Pemakaian tidak terukur dengan benar'),
(37,16,1,'Jaringan rusak','Non-Finansial','Keandalan distribusi menurun'),
(38,16,2,'Kebocoran meningkat','Finansial','Air hilang tanpa pendapatan'),
(39,17,1,'Target laba tidak tercapai','Finansial','Laba bersih di bawah Rp 2.511.247.563'),
(40,17,2,'Kinerja seluruh divisi terpengaruh','Non-Finansial','Sasaran unit ikut meleset'),
(41,17,3,'Kredibilitas RKAP di hadapan Dewan Pengawas turun','Non-Finansial','Kepercayaan pada penyusunan anggaran'),
(42,18,1,'Kerugian negara/daerah','Finansial','Kerugian atas pengeluaran yang tidak sah'),
(43,18,2,'Temuan SPI','Non-Finansial','Catatan pengawasan internal'),
(44,18,3,'Proses pengadaan tidak sah','Non-Finansial','Kontrak berpotensi tidak berkekuatan hukum'),
(45,19,1,'Temuan berulang','Non-Finansial','Butir yang sama tidak pernah tertutup'),
(46,19,2,'Sanksi/regulasi','Finansial','Tindakan administratif dari regulator'),
(47,19,3,'Reputasi organisasi menurun','Non-Finansial','Dipandang tidak tertib administrasi'),
(48,20,1,'Keputusan keliru','Finansial','Kerugian akibat pilihan yang salah'),
(49,20,2,'Kesalahan berulang','Non-Finansial','Kesalahan sama terjadi pada periode berikutnya'),
(50,20,3,'Akuntabilitas lemah','Non-Finansial','Tidak ada penanggung jawab atas keputusan'),
(51,21,1,'Pendapatan potensial tidak termanfaatkan','Finansial','Pendapatan tambahan dari peluang terlewat'),
(52,21,2,'Biaya overhead tetap tinggi','Finansial','Efisiensi tidak memuai sebagaimana mestinya');

-- =====================================================================
-- RISIKO <-> PROSES (many-to-many) — ISO 6.4.2 memperbolehkan risiko
-- terkait lebih dari satu proses. IJT-EXT-01 adalah contoh utama:
-- kekeringan (K) -> produksi turun (D) -> biaya & likuiditas (B).
-- =====================================================================
INSERT INTO `mri_risiko_proses` (`id_register`,`id_proses`,`jenis`) VALUES
(1,4,'UTAMA'),
(2,4,'UTAMA'),
(3,5,'UTAMA'),
(4,3,'UTAMA'),
(5,2,'UTAMA'),
(6,2,'UTAMA'),(6,4,'SUPPORT'),
(7,6,'UTAMA'),
(8,8,'UTAMA'),(8,2,'SUPPORT'),
(9,7,'UTAMA'),
(10,11,'UTAMA'),
(11,9,'UTAMA'),
(12,11,'UTAMA'),(12,4,'SUPPORT'),(12,2,'SUPPORT'),
(13,10,'UTAMA'),
(14,3,'UTAMA'),
(15,3,'UTAMA'),
(16,5,'UTAMA'),
(17,1,'UTAMA'),(17,2,'SUPPORT'),
(18,1,'UTAMA'),(18,6,'SUPPORT'),
(19,10,'UTAMA'),(19,1,'SUPPORT'),
(20,1,'UTAMA'),(20,8,'SUPPORT'),
(21,1,'UTAMA'),(21,3,'SUPPORT'),(21,8,'SUPPORT');

-- =====================================================================
-- RISIKO <-> OBJEKTIF RKAP (junction)
-- =====================================================================
INSERT INTO `mri_risiko_objektif` (`id_register`,`id_objektif`) VALUES
(1,16),
(3,21),(3,1),
(4,15),(4,1),
(5,19),(5,8),
(6,8),(6,11),
(7,22),
(8,19),
(11,23),(11,24),
(12,16),
(14,14),
(15,17),
(16,20),(16,21),
(17,1),(17,3),(17,8),
(18,22),
(20,8),
(21,1);

-- =====================================================================
-- ANALISIS — ISO 6.4.3. nilai_* = L x D (wajib konsisten).
-- kontrol_ada/memadai meniru Slide 40 ("Memadai / Belum Memadai").
-- =====================================================================
INSERT INTO `mri_analisa`
 (`id_analisa`,`id_register`,`inherent_l`,`inherent_d`,`nilai_inherent`,`level_inherent`,
  `kontrol_uraian`,`kontrol_ada`,`memadai`,
  `residual_l`,`residual_d`,`nilai_residual`,`level_residual`,
  `ringkasan`,`id_risk_owner`,`created_by`)
VALUES
(1,1,3,4,12,'Tinggi','Pemeliharaan berkala (PM), pompa cadangan, monitoring','Ada','Memadai',
 2,4,8,'Sedang','Kontrol pemeliharaan menurunkan skor 12 ke 8; sisa risiko terkait ketersediaan suku cadang.',4,'seed'),
(2,2,2,4,8,'Sedang','Pengawasan kualitas berkala, optimalisasi treatment','Ada','Memadai',
 1,3,3,'Rendah','Pengujian laboratorium rutin & koordinasi DLHK menurunkan skor 8 ke 3.',4,'seed'),
(3,3,4,4,16,'Kritis','Pengurangan ACP, monitoring tekanan, deteksi kebocoran','Ada','Belum Memadai',
 3,4,12,'Tinggi','Skor sisa masih Tinggi (12) — segmentasi DMA dan penggantian pipa bertahap belum berjalan penuh. Perlu prioritas RKAP 2027.',4,'seed'),
(4,4,3,3,9,'Sedang','Promosi, layanan prima, penanganan keluhan','Ada','Memadai',
 2,3,6,'Sedang','Kontrol berjalan namun skor sisa tetap Sedang karena penyebab (kehilangan pelanggan) struktural.',6,'seed'),
(5,5,4,4,16,'Kritis','Pengingat digital, penagihan aktif, program cicilan','Ada','Belum Memadai',
 3,4,12,'Tinggi','Skor sisa masih Tinggi (12) — digitalisasi penagihan belum menuntaskan piutang. Prioritas dampak langsung ke RKAP.',3,'seed'),
(6,6,3,4,12,'Tinggi','Efisiensi energi, negosiasi vendor, monitoring pemakaian','Ada','Memadai',
 2,4,8,'Sedang','Penyesuaian produksi motor pompa & efisiensi listrik sumur bor menurunkan skor 12 ke 8.',3,'seed'),
(7,7,3,3,9,'Sedang','Perencanaan matang, timeline ketat','Ada','Memadai',
 2,3,6,'Sedang','Monitoring progres investasi & koordinasi vendor menurunkan skor 9 ke 6.',5,'seed'),
(8,8,2,3,6,'Sedang','Cadangan berkala, antivirus, firewall','Ada','Memadai',
 1,3,3,'Rendah','Peningkatan kapasitas server, rencana pemulihan bencana, dan audit keamanan menurunkan skor 6 ke 3.',2,'seed'),
(9,9,2,3,6,'Sedang','Pelatihan berkala, perencanaan suksesi','Ada','Memadai',
 1,3,3,'Rendah','Rencana pelatihan & pengembangan talenta menurunkan skor 6 ke 3.',2,'seed'),
(10,10,2,4,8,'Sedang','SOP K3, APD, pengarahan keselamatan','Ada','Memadai',
 1,4,4,'Rendah','Audit K3, simulasi darurat & asuransi menurunkan skor 8 ke 4.',2,'seed'),
(11,11,3,3,9,'Sedang','Promosi, perluasan distribusi','Ada','Memadai',
 2,3,6,'Sedang','Strategi pemasaran, kemitraan, pengendalian mutu menurunkan skor 9 ke 6.',23,'seed'),
(12,12,2,5,10,'Tinggi','Monitoring cuaca, rencana kontinjensi','Ada','Memadai',
 1,5,5,'Rendah','Rencana tanggap darurat, alternatif sumber air, koordinasi BMKG menurunkan skor 10 ke 5.',4,'seed'),
(13,13,2,3,6,'Sedang','Monitoring regulasi, tinjauan hukum','Ada','Memadai',
 1,3,3,'Rendah','Audit hukum, daftar periksa kepatuhan, pelatihan menurunkan skor 6 ke 3.',8,'seed'),
(14,14,3,3,9,'Sedang','Promosi, program insentif','Ada','Memadai',
 2,3,6,'Sedang','Kerja sama desa, program padat karya, pemasaran sosial menurunkan skor 9 ke 6.',6,'seed'),
(15,15,2,2,4,'Rendah','SOP penggantian, pengendalian mutu','Ada','Memadai',
 1,2,2,'Rendah','Pelatihan teknisi, supervisi, mekanisme umpan balik menurunkan skor 4 ke 2.',6,'seed'),
(16,16,3,3,9,'Sedang','Penjadwalan pemeliharaan, prioritas perbaikan','Ada','Memadai',
 2,3,6,'Sedang','Optimasi anggaran & kemitraan kontraktor eksternal menurunkan skor 9 ke 6.',4,'seed'),
-- ----- Draft Grup A -----
(17,17,3,5,15,'Tinggi','(belum ada kontrol formal untuk rekonsiliasi angka RKAP)','Tidak','Belum Memadai',
 3,3,9,'Sedang','Rencana: rekonsiliasi antar slide + validasi asumsi pelanggan/efisiensi sebelum tahap evaluasi final.',1,'draft-manrisk'),
(18,18,2,5,10,'Tinggi','Pengawasan SPI, dokumentasi pengadaan','Ada','Memadai',
 2,2,4,'Rendah','Rencana: e-procurement wajib, standar spesifikasi, tegak lurus pengawasan (mengurangi skor 10 ke 4).',8,'draft-manrisk'),
(19,19,3,4,12,'Tinggi','Pengawasan internal berjalan','Ada','Memadai',
 3,2,6,'Sedang','Rencana: kewajiban tindak lanjut berjangka + eskalasi otomatis (mengurangi skor 12 ke 6).',8,'draft-manrisk'),
(20,20,3,3,9,'Sedang','Rapat koordinasi rutin, laporan manual tersedia','Ada','Memadai',
 2,3,6,'Sedang','Rencana: integrasi data + dashboard keputusan (mengurangi skor 9 ke 6).',1,'draft-manrisk'),
(21,21,3,2,6,'Sedang','(belum ada proses formal identifikasi peluang)','Tidak','Belum Memadai',
 3,1,3,'Rendah','Rencana: survei pelanggan Pamsimas + program efisiensi digital (mengurangi skor 6 ke 3).',1,'draft-manrisk');

-- =====================================================================
-- EVALUASI — ISO 6.4.4. Keputusan diturunkan dari level INHEREN
-- (mengikuti Slide 39 dan aksi di mri_tingkat_risiko):
--   Rendah  -> Accept      Sedang -> Monitor
--   Tinggi  -> Treat       Kritis -> Escalate
-- =====================================================================
INSERT INTO `mri_evaluasi`
 (`id_evaluasi`,`id_register`,`id_analisa`,`nilai_evaluasi`,`level_evaluasi`,
  `kriteria_rujukan`,`keputusan`,`alasan_evaluasi`,`perlu_eskalasi`,`eskalasi_ke`,
  `tanggal_evaluasi`,`triwulan`,`oleh`,`created_by`)
VALUES
(1,1,1,12,'Tinggi','Level Tinggi (10-15): penanganan prioritas, direksi monitor','Treat',
 'Kontrol pemeliharaan sudah ada namun ketersediaan suku cadang menjadi pengungkit utama.',0,NULL,'2026-10-05',1,'Administrator','seed'),
(2,2,2,8,'Sedang','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor',
 'Kontrol pengawasan kualitas efektif menurunkan skor ke Rendah pada sisa risiko.',0,NULL,'2026-10-05',1,'Administrator','seed'),
(3,3,3,16,'Kritis','Level Kritis (16-25): tindakan segera, notifikasi direksi','Escalate',
 'Skor sisa masih Tinggi (12) meski kontrol ada. Butuh keputusan alokasi anggaran segmentasi DMA di tingkat direksi.',1,'Direktur','2026-10-05',1,'Administrator','seed'),
(4,4,4,9,'Sedang','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor',
 'Penyebab bersifat struktural (kehilangan pelanggan); perlu pemantauan pertumbuhan SR bulanan.',0,NULL,'2026-10-05',1,'Administrator','seed'),
(5,5,5,16,'Kritis','Level Kritis (16-25): tindakan segera, notifikasi direksi','Escalate',
 'Skor sisa masih Tinggi (12). Dampak langsung ke laba bersih dan likuiditas — prioritas tertinggi RKAP 2027.',1,'Direktur','2026-10-05',1,'Administrator','seed'),
(6,6,6,12,'Tinggi','Level Tinggi (10-15): penanganan prioritas, direksi monitor','Treat',
 'Kenaikan harga energi di luar kendali; pengungkit utama adalah efisiensi produksi.',0,NULL,'2026-10-05',1,'Administrator','seed'),
(7,7,7,9,'Sedang','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor',
 'Monitoring progres investasi cukup sebagai kendali tahap ini.',0,NULL,'2026-10-05',1,'Administrator','seed'),
(8,8,8,6,'Sedang','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor',
 'Dampak tinggi namun kemungkinan rendah; pemantauan keamanan cukup.',0,NULL,'2026-10-05',1,'Administrator','seed'),
(9,9,9,6,'Sedang','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor',
 'Risiko sedang, ditangani melalui perencanaan pelatihan tahunan.',0,NULL,'2026-10-05',1,'Administrator','seed'),
(10,10,10,8,'Sedang','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor',
 'Dampak tinggi (korban jiwa) meski kemungkinan rendah — wajib dipantau rutin tanpa eskalasi.',0,NULL,'2026-10-05',1,'Administrator','seed'),
(11,11,11,9,'Sedang','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor',
 'Risiko pasar pada unit usaha; dipantau bersama laporan penjualan bulanan.',0,NULL,'2026-10-05',1,'Administrator','seed'),
(12,12,12,10,'Tinggi','Level Tinggi (10-15): penanganan prioritas, direksi monitor','Treat',
 'Risiko iklim dengan dampak produksi besar; rencana tanggap darurat perlu diuji sebelum musim kemarau.',0,NULL,'2026-10-05',1,'Administrator','seed'),
(13,13,13,6,'Sedang','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor',
 'Kepatuhan berjalan; tinjauan regulasi berkala cukup.',0,NULL,'2026-10-05',1,'Administrator','seed'),
(14,14,14,9,'Sedang','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor',
 'Penurunan SR bersifat tren pasar; dipantau tiap triwulan.',0,NULL,'2026-10-05',1,'Administrator','seed'),
(15,15,15,4,'Rendah','Level Rendah (1-5): diterima, monitor rutin','Accept',
 'Skor sisa Rendah (2); cukup dipantau lewat mekanisme umpan balik pelanggan.',0,NULL,'2026-10-05',1,'Administrator','seed'),
(16,16,16,9,'Sedang','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor',
 'Anggaran pemeliharaan sudah dialokasikan Rp 157,8 juta; perlu penjadwalan bulanan.',0,NULL,'2026-10-05',1,'Administrator','seed'),
(17,17,17,15,'Tinggi','Level Tinggi (10-15): penanganan prioritas, direksi monitor','Treat',
 'DRAFT. Ketidakkonsistenan angka RKAP antar slide belum tertutup; perlu rekonsiliasi sebelum evaluasi final.',0,NULL,'2026-10-05',1,'draft-manrisk','draft-manrisk'),
(18,18,18,10,'Tinggi','Level Tinggi (10-15): penanganan prioritas, direksi monitor','Treat',
 'DRAFT. Tekanan target investasi menaikkan risiko penyimpangan pengadaan.',0,NULL,'2026-10-05',1,'draft-manrisk','draft-manrisk'),
(19,19,19,12,'Tinggi','Level Tinggi (10-15): penanganan prioritas, direksi monitor','Treat',
 'DRAFT. Tidak ada mekanisme eskalasi tindak lanjut temuan.',0,NULL,'2026-10-05',1,'draft-manrisk','draft-manrisk'),
(20,20,20,9,'Sedang','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor',
 'DRAFT. Laporan manual masih memadai namun tanpa jejak audit keputusan.',0,NULL,'2026-10-05',1,'draft-manrisk','draft-manrisk'),
(21,21,21,6,'Sedang','Level Sedang (6-9): monitor berkala, eskalasi jika naik','Monitor',
 'DRAFT. Peluang dijadikan risiko agar terpantau; tipe Peluang (ISO 6.4.2).',0,NULL,'2026-10-05',1,'draft-manrisk','draft-manrisk');

-- =====================================================================
-- PERLAKUAN — ISO 6.5. Semua risiko ancaman memakai REDUCE kecuali
-- dinyatakan lain. Strategi = langkah yang diambil terhadap risiko.
-- =====================================================================
INSERT INTO `mri_perlakuan`
 (`id_perlakuan`,`id_register`,`id_evaluasi`,`strategi`,`alasan_strategi`,
  `uraian_tindakan`,`indikator_sukses`,`anggaran_biaya`,`sumber_daya`,
  `jadwal_mulai`,`jadwal_selesai`,`id_pic`,`status_laksana`,`created_by`)
VALUES
(1,1,1,'REDUCE','Kontrol pemeliharaan sudah ada, perlu diperkuat ketersediaan suku cadang.',
 'Jadwal PM ketat, pompa cadangan siap, prosedur darurat operasional','Jumlah gangguan sumur bor per triwulan turun',NULL,'Pelaksana Teknik, Pemeliharaan','2027-01-01','2027-12-31',5,'BELUM','seed'),
(2,2,2,'REDUCE','Kualitas air harus dijaga di bawah baku mutu.',
 'Pengujian laboratorium rutin, koordinasi dengan DLHK','Tidak ada temuan baku mutu air',NULL,'Staf Teknik, Laboratorium','2027-01-01','2027-12-31',5,'BELUM','seed'),
(3,3,3,'REDUCE','NRW adalah fokus efisiensi RKAP 2027.',
 'Segmentasi DMA, penggantian pipa bertahap, monitoring SCADA','NRW per zona sumur bor turun terhadap baseline',157800000.00,'Pemeliharaan, Kontraktor eksternal','2027-01-01','2027-12-31',5,'BELUM','seed'),
(4,4,4,'REDUCE','Pertumbuhan pelanggan perlu dijaga agar target SR tercapai.',
 'Kerja sama desa, program padat karya, pemasaran sosial','Realisasi sambungan baru mendekati 938 SR',NULL,'Kabag Langganan','2027-01-01','2027-12-31',2,'BELUM','seed'),
(5,5,5,'REDUCE','Piutang berdampak langsung ke laba bersih dan likuiditas.',
 'Digitalisasi penagihan, kunjungan langsung, kolaborasi pemerintah desa, penutusan bertahap','Efisiensi penagihan mencapai 92,98%',NULL,'Keuangan, Penagihan','2027-01-01','2027-12-31',4,'BELUM','seed'),
(6,6,6,'REDUCE','Biaya energi naik di luar kendali; efisiensi produksi adalah pengungkit.',
 'Penyesuaian produksi motor pompa, efisiensi listrik sumur bor','Total beban usaha tidak melebihi Rp 8.964.652.306',NULL,'Keuangan, Produksi','2027-01-01','2027-12-31',4,'BELUM','seed'),
(7,7,7,'REDUCE','Investasi Rp 2,3 M perlu terealisasi tepat waktu.',
 'Monitoring progres investasi, koordinasi vendor, sistem peringatan dini','Realisasi investasi sesuai jadwal',2300000000.00,'Perencanaan, Pengadaan','2027-01-01','2027-12-31',6,'BELUM','seed'),
(8,8,8,'REDUCE','Gangguan layanan digital berdampak pada penagihan.',
 'Peningkatan kapasitas server, rencana pemulihan bencana, audit keamanan','Waktu operasional layanan digital >= 99%',NULL,'Staf Teknik, I T','2027-01-01','2027-12-31',7,'BELUM','seed'),
(9,9,9,'REDUCE','Kompetensi 172 pegawai perlu dijaga.',
 'Rencana pelatihan, pengembangan talenta, rekrutmen strategis','Tidak ada posisi kritis tanpa calon pengganti',NULL,'Umum, Personalia','2027-01-01','2027-12-31',3,'BELUM','seed'),
(10,10,10,'REDUCE','Nihilnya kecelakaan kerja adalah target mutlak.',
 'Audit K3, simulasi darurat, asuransi','Nihil kecelakaan kerja (zero accident)',NULL,'Umum, K3','2027-01-01','2027-12-31',3,'BELUM','seed'),
(11,11,11,'REDUCE','Penjualan AMDK menopang laba unit.',
 'Strategi pemasaran, kemitraan, pengendalian mutu','Pendapatan AMDK mencapai Rp 3.014.730.000',NULL,'Manager Unit AMDK, Pemasaran','2027-01-01','2027-12-31',11,'BELUM','seed'),
(12,12,12,'REDUCE','Kekeringan/banjir di luar kendali; kesiapan tanggap adalah pengungkit.',
 'Rencana tanggap darurat, alternatif sumber air, koordinasi BMKG','Tidak ada gangguan distribusi akibat cuaca ekstrem',NULL,'Produksi, Pemeliharaan','2027-01-01','2027-12-31',5,'BELUM','seed'),
(13,13,13,'REDUCE','Kepatuhan harus dijaga agar bebas sanksi.',
 'Audit hukum, daftar periksa kepatuhan, pelatihan','Nihil temuan kepatuhan yang berulang',NULL,'S P I','2027-01-01','2027-12-31',7,'BELUM','seed'),
(14,14,14,'REDUCE','Target sambungan baru adalah motor pertumbuhan pelanggan.',
 'Kerja sama desa, program padat karya, pemasaran sosial','Realisasi 938 sambungan baru',NULL,'Langganan','2027-01-01','2027-12-31',2,'BELUM','seed'),
(15,15,15,'REDUCE','Kesalahan proses penggantian meter berdampak pada pencatatan.',
 'Pelatihan teknisi, supervisi, mekanisme umpan balik','727 penggantian meter tanpa keberatan pelanggan',NULL,'Langganan','2027-01-01','2027-12-31',2,'BELUM','seed'),
(16,16,16,'REDUCE','Pemeliharaan jaringan menjaga NRW dan keandalan distribusi.',
 'Optimasi anggaran, kemitraan kontraktor eksternal','Kebocoran meningkat dapat dicegah dalam anggaran Rp 157,8 juta',157800000.00,'Pemeliharaan, Kontraktor','2027-01-01','2027-12-31',5,'BELUM','seed'),
(17,17,17,'REDUCE','Selisih angka antar slide harus tertutup agar dasar keputusan andal.',
 'Rekonsiliasi angka RKAP antar slide dengan worksheet keuangan; validasi asumsi pelanggan & efisiensi sebelum target dikunci',
 'status_rekonsiliasi menjadi SELESAI untuk seluruh objektif',NULL,'Keuangan, Perencanaan, Direktur','2026-10-05','2026-12-31',4,'DALAM_PROSES','draft-manrisk'),
(18,18,18,'REDUCE','Tekanan target menaikkan risiko penyimpangan; kontrol pengadaan diperkuat.',
 'E-procurement wajib, standar spesifikasi pengadaan, tegak lurus pengawasan SPI pada pengadaan bernilai besar',
 'Nihil temuan pengadaan pada periode berjalan',NULL,'S P I, Perencanaan','2027-01-01','2027-12-31',7,'BELUM','draft-manrisk'),
(19,19,19,'REDUCE','Tindak lanjut temuan berulang menandakan lemahnya kewajiban PIC.',
 'Kewajiban tindak lanjut berjangka per PIC + eskalasi otomatis bila lewat tenggat',
 'Seluruh temuan tertutup dalam periode berjalan',NULL,'S P I, seluruh bagian','2027-01-01','2027-12-31',7,'BELUM','draft-manrisk'),
(20,20,20,'REDUCE','Keputusan berbasis data menurunkan risiko keputusan keliru.',
 'Integrasi data antar sistem + dashboard keputusan dengan jejak audit versi',
 'Keputusan strategis memiliki jejak data & persetujuan',NULL,'I T, Umum, Direktur','2027-01-01','2027-12-31',7,'BELUM','draft-manrisk'),
(21,21,21,'REDUCE','Tipe risiko PELUANG: mengurangi kemungkinan peluang terlewat dengan mekanisme identifikasi formal (ISO 6.4.2 memandang ancaman dan peluang sebagai risiko).',
 'Survei potensi pelanggan wilayah Pamsimas + program efisiensi berbasis digitalisasi',
 'Pendapatan potensial dari peluang tercatat dan ditugaskan PIC',NULL,'Langganan, I T, Direktur','2027-01-01','2027-12-31',2,'BELUM','draft-manrisk');

-- =====================================================================
-- RIWAYAT SKOR — posisi awal 2027 Q1 (diambil dari analisis).
-- Monitoring & Review (mri_monitoring) sengaja TIDAK diisi — akan
-- terisi ketika review triwulan benar-benar dilakukan.
-- =====================================================================
INSERT INTO `mri_riwayat_skor`
 (`id_register`,`tahun`,`triwulan`,`nilai_inherent`,`nilai_residual`,`level_inherent`,`level_residual`)
VALUES
(1,2027,1,12,8,'Tinggi','Sedang'),
(2,2027,1,8,3,'Sedang','Rendah'),
(3,2027,1,16,12,'Kritis','Tinggi'),
(4,2027,1,9,6,'Sedang','Sedang'),
(5,2027,1,16,12,'Kritis','Tinggi'),
(6,2027,1,12,8,'Tinggi','Sedang'),
(7,2027,1,9,6,'Sedang','Sedang'),
(8,2027,1,6,3,'Sedang','Rendah'),
(9,2027,1,6,3,'Sedang','Rendah'),
(10,2027,1,8,4,'Sedang','Rendah'),
(11,2027,1,9,6,'Sedang','Sedang'),
(12,2027,1,10,5,'Tinggi','Rendah'),
(13,2027,1,6,3,'Sedang','Rendah'),
(14,2027,1,9,6,'Sedang','Sedang'),
(15,2027,1,4,2,'Rendah','Rendah'),
(16,2027,1,9,6,'Sedang','Sedang'),
(17,2027,1,15,9,'Tinggi','Sedang'),
(18,2027,1,10,4,'Tinggi','Rendah'),
(19,2027,1,12,6,'Tinggi','Sedang'),
(20,2027,1,9,6,'Sedang','Sedang'),
(21,2027,1,6,3,'Sedang','Rendah');
