# PANDUAN PENGISIAN MODUL MANAJEMEN RISIKO 2027
### Dari Sasaran hingga Laporan — langkah demi langkah

**Dasar:** SNI ISO 31000:2018 & materi QRMO (Qualified Risk Management Officer)
**Aplikasi:** menu **Man. Risiko 2027** (`manrisk`) — modul terpisah dari data legacy 2025–2026
**Prinsip QRMO slide 7:** *"Kuncinya: selalu mulai dari SASARAN."*
Risiko = **efek ketidakpastian terhadap sasaran**, jadi sasaran wajib ditetapkan dulu sebelum risiko diidentifikasi.

---

## RINGKASAN URUTAN

| # | Tahap | Menu | Output |
|---|-------|------|--------|
| 0 | Pengaturan | **Pengaturan** | Skala 3 level & kunci periode benar |
| 1 | **Sasaran / Objektif** | **Objektif & RKAP** | Daftar sasaran 2027 + status rekonsiliasi |
| 1B | **Komunikasi & Konsultasi** | **Komunikasi** | Sesi 3 langkah, 4 pertanyaan, 5 hasil, tindak lanjut |
| 2 | Lingkup, Konteks, Kriteria | **Lingkup** | Dokumen lingkup berstatus **FINAL** |
| 3 | Identifikasi Risiko | **Risk Register** | Risiko terdaftar & terhubung ke sasaran |
| 4 | Analisis Risiko | **Analisis** | Skor inherent + residual + Risk Owner |
| 5 | Evaluasi Risiko | **Evaluasi** | Keputusan: Accept/Monitor/Treat/Escalate |
| 6 | Perlakuan Risiko | **Perlakuan** | Rencana tindakan, PIC, jadwal, anggaran |
| 7 | Pemantauan & Peninjauan | **Monitoring** | Catatan triwulan + skor baru (L × D) |
| 8 | Pencatatan & Pelaporan | **Laporan** | Laporan FINAL + PDF |

> Semua angka level, warna, dan aksi otomatis dihitung dari tabel `mri_tingkat_risiko`
> (skala aktif: **Rendah 1–10, Sedang 11–15, Tinggi 16–25**) — ubah lewat menu Pengaturan.

---

## TAHAP 0 — PENGATURAN (cek awal)

**Menu: Pengaturan**

- [ ] Skala level = 3 level (Rendah/Sedang/Tinggi) dengan rentang sesuai kebijakan
- [ ] Risk appetite/tolerance per kategori sudah diisi (bila dipakai)
- [ ] Kunci periode **terbuka** selama pengisian (kunci lagi setelah final)

---

## TAHAP 1 — SASARAN / OBJEKTIF (MULAI DARI SINI)

**Menu: Proses Man. Risiko → Obyektif/Sasaran**

Sasaran harus **SMART**: Specific, Measurable, Attainable, Relevant, Time-bound.

> **Kunci pengisian:** bila **Edit Objektif / Target RKAP** di menu
> **Pengaturan** berstatus terkunci, UPK hanya bisa membaca. Buka kuncinya
> agar UPK dapat menambah/mengubah objektif unitnya sendiri. Admin selalu
> dapat mengubah.
>
> **Filter unit:** akun UPK hanya melihat objektif milik unitnya sendiri.
> Pilihan **Unit / Bagian** pada form juga dibatasi ke unit tersebut, sehingga
> UPK tidak dapat membuat objektif untuk unit lain. Bila tabel masih kosong,
> artinya unit tersebut belum punya objektif — silakan tambah.

### 1a. Tambah sasaran
Klik **Tambah Objektif**, isi:

| Field | Contoh | Keterangan |
|---|---|---|
| Indikator | Penurunan NRW per zona sumur bor | Nama sasaran (wajib) |
| Baseline 2026 | 18,5 | Angka awal/realisasi tahun lalu |
| Nilai RKAP | 15 | **Target 2027** |
| Satuan | persen | Rp, %, m3, jam, dll |
| Sumber Nilai | Usulan User / RKAP 2027 | Asal angka |
| Unit / Bagian | UPK Bondowoso | Pemilik sasaran |
| Proses / Lingkup | Distribusi | Hubungkan ke konteks |

Klik **Simpan**.

### 1b. Rekonsiliasi sasaran
Rekonsiliasi = konfirmasi bahwa keterkaitan sasaran ↔ risiko sudah benar.
Di daftar objektif, isi **Nilai Realisasi** → status **BELUM → SELESAI**.

- [ ] Seluruh sasaran penting sudah terdaftar
- [ ] Status rekonsiliasi semua sasaran = **SELESAI**

> **Catatan data saat ini:** 28 sasaran terdaftar, **4 masih BELUM**
> (Laba Kotor, Penurunan NRW per zona sumur bor, Testing, Arus Kas).

---

## TAHAP 1B — KOMUNIKASI & KONSULTASI

**Menu: Komunikasi & Konsultasi** (Proses Man. Risiko)

Tahap 1 SNI ISO 31000:2018 (Klausa 6.2). Komunikasi & konsultasi bukan sekadar
rapat: tujuannya memperoleh informasi terbaik, menyamakan persepsi, dan
menghasilkan keputusan risiko yang jelas bersama pihak relevan. Satu dokumen
Lingkup dapat memiliki beberapa sesi (rapat, FGD, wawancara, sosialisasi).

### 1b.1 Catat identitas sesi

Klik **+ Tambah Sesi**, lalu isi:

| Field | Contoh |
|---|---|
| Tahun / Triwulan | `2027` / `TW1` |
| Tanggal | tanggal rapat / konsultasi |
| Metode | Rapat, FGD, Wawancara, Sosialisasi, Konsultasi Tertulis |
| Unit / Bagian | unit yang mengadakan sesi (boleh seluruh organisasi) |
| **Topik / Agenda** ★ | `Konsultasi sasaran penurunan NRW TW1 2027` |
| PIC / Fasilitator | nama pemimpin atau pencatat sesi |
| Dokumen Lingkup | tautkan bila sesi membahas lingkup tertentu (opsional) |
| Risiko yang Dibahas | tautkan bila sesi membahas satu risiko (opsional) |
| Foto Pendukung | dokumentasi rapat/kegiatan, boleh beberapa foto (JPG/PNG, maks. 4 MB per foto) |

Foto yang diunggah tersimpan di `assets/uploads/manrisk/komunikasi/` dan
tampil sebagai galeri pada halaman detail sesi. Hapus foto dengan mencentang
**Hapus foto** saat mengubah sesi. Tabel `mri_komunikasi_foto` dibuat oleh
migrasi `12_create_mri_komunikasi_foto.sql` (sudah termasuk di migrasi 11).

### 1b.2 Isi tiga langkah

1. **Dengarkan** — informasi, data, keluhan, atau pengalaman peserta.
2. **Selaraskan** — bagian yang disepakati dan yang masih berbeda persepsi.
3. **Uji & konfirmasi** — asumsi yang diklarifikasi, bukti, dan konfirmasi peserta.

### 1b.3 Jawab empat pertanyaan kunci

- Apa sasarannya?
- Apa risikonya?
- Kontrol apa yang sudah ada?
- Apa tindakan selanjutnya?

### 1b.4 Centang lima hasil yang benar-benar diperoleh

1. Pemahaman sasaran yang sama
2. Risiko dan prioritas teridentifikasi
3. Penyebab & dampak tervalidasi
4. Mitigasi dan kontrol disepakati
5. PIC & tindak lanjut jelas

### 1b.5 Tutup dengan tindak lanjut

Isi **Ringkasan Hasil Sesi**, **Tindak Lanjut**, **PIC**, **Target Selesai**, dan
**Status Tindak Lanjut**. Setelah catatan tuntas, ubah **Status Sesi** dari
`DRAFT` menjadi **`SELESAI`**.

- [ ] Setiap pihak relevan tercatat (Direksi, Risk Owner, unit teknis, Keuangan, SPI bila perlu)
- [ ] Tiga langkah, empat pertanyaan, dan lima hasil terisi
- [ ] Tindak lanjut punya PIC + target waktu

> **Dashboard:** tahap 1 menghitung sesi berstatus **SELESAI**. Data konsultasi
yang sebelumnya diisi di form Lingkup dipindahkan otomatis oleh migrasi
`11_create_mri_komunikasi.sql`; kolom konsultasi di Lingkup tetap ada sebagai
ringkasan dokumen.

---

## TAHAP 2 — LINGKUP, KONTEKS & KRITERIA

**Menu: Lingkup**

Dokumen ini merangkum **Klausa 6.3** ISO 31000 (apa yang dinilai, kondisinya, ukurannya).

Isian utama:

| Field | Isi |
|---|---|
| Nama Lingkup | Evaluasi Manajemen Risiko 2027 |
| Objek Evaluasi | proses/bisnis yang dinilai, mis. pengadaan → kontrak → distribusi |
| Batas Evaluasi | wilayah, unit, periode |
| Konteks Internal | SOP, SDM, gudang, anggaran, sistem |
| Konteks Eksternal | harga pasar, pemasok, regulasi, logistik |
| Kriteria Acuan | skala likelihood 1–5, consequence 1–5, L×C, appetite organisasi |
| Tanggal Konsultasi | tanggal rapat awal |
| Peserta | nama & jabatan yang terlibat |
| 4 Pertanyaan Kunci | **q_sasaran** (apa sasarannya?), **q_risiko** (apa risikonya?), **q_kontrol** (kontrol apa yang sudah ada?), **q_tindakan** (aksi selanjutnya?) |
| Hasil Konsultasi | kesimpulan bersama setelah 4 pertanyaan terjawab |

Terakhir: ubah **Status DRAFT → FINAL**.

> **Catatan:** bagian Konsultasi pada form ini adalah **ringkasan dokumen**.
> Detail tiap sesi (tiga langkah, empat pertanyaan, lima hasil, tindak lanjut)
> dicatat di menu **Komunikasi & Konsultasi** agar tidak tercampur dengan
> konteks dan kriteria lingkup.

- [ ] Semua field terisi, hasil konsultasi ada
- [ ] Status = **FINAL**

> **Catatan data saat ini:** 1 dokumen lingkup, masih **DRAFT** dan hasil konsultasi masih kosong.

---

## TAHAP 3 — IDENTIFIKASI RISIKO (RISK REGISTER)

**Menu: Risk Register → Tambah**

Rumusan risiko harus menghubungkan: **SASARAN → PENYEBAB → PERISTIWA → AKIBAT**.

### 3a. Arti kode risiko — `IJT-SEG-NN`

| Bagian | Arti |
|---|---|
| `IJT` | **Ijen Tirta** — penanda organisasi, selalu tetap |
| `SEG` | Segmen/tema risiko (3–4 huruf) |
| `NN` | Nomor urut 2 digit di dalam segmen tersebut |

Contoh: `IJT-OPR-01` = risiko operasional nomor 1.
Nomor urut dihitung **per segmen**, jadi `IJT-OPR-02` dan `IJT-KEU-01` bisa berdampingan.

> **Segmen bukan turunan Kategori.** Pilih segmen sesuai tema isi risiko, bukan berdasarkan dropdown Kategori. Satu segmen bisa memuat beberapa kategori — misalnya `IJT-GOV-*` memuat kategori Strategic sekaligus Fraud, dan `IJT-KEU-*` memuat Credit sekaligus Finance.

| Segmen | Arti | Contoh isi risiko |
|---|---|---|
| `OPR` | Operasional | gangguan produksi/distribusi, NRW, kontinuitas layanan |
| `KEU` | Keuangan | piutang tak tertagih, likuiditas, penyalahgunaan anggaran |
| `GOV` | Governance / tata kelola | konflik kepentingan, fraud, kelemahan pengawasan |
| `HR` | SDM / Human Resources | kekurangan tenaga ahli, turnover, kompetensi |
| `IT` | Teknologi Informasi | gangguan sistem billing, keamanan data, server down |
| `K3` | Keselamatan & Kesehatan Kerja | kecelakaan kerja, paparan bahan kimia, APD |
| `EXT` | Eksternal | cuaca ekstrem, pemasok, kebijakan pemerintah |
| `PEL` | Pelanggan | keluhan, pengaduan, kepuasan layanan |
| `REG` | Regulasi / kepatuhan | perubahan aturan, sanksi, izin usaha |
| `AMDK` | Unit usaha AMDK | risiko khusus lini Air Minum Dalam Kemasan |

**Pengaruh kode ke proses selanjutnya:**

- Kode adalah **ID risiko** yang dipakai di analisis, evaluasi, perlakuan, monitoring, dan laporan/PDF.
- Kode **wajib unik** dan **tidak memengaruhi perhitungan skor/level** risiko.
- Daftar segmen di dropdown diambil dari kode yang sudah ada; mengetik segmen baru akan menambah pilihan baru secara permanen.

| Field | Contoh (UPK Bondowoso) |
|---|---|
| Kode Risiko | `IJT-UPB-01` (otomatis dari segmen + urut) |
| Tahun / TW Awal | 2027 / TW1 |
| Unit (UPK) | Bondowoso |
| Kategori | `OPE — Operational` (kode + nama; penjelasan & contoh muncul otomatis di bawah dropdown) |
| Tipe Risiko | Ancaman / Peluang |
| Status Kendali | C (Controlled) / UC (Uncontrolled) |
| Risiko (wajib) | "Gangguan perpipaan distribusi menyebabkan tekanan air turun sehingga kontinuitas layanan terganggu" |
| Peristiwa (wajib) | "Pipa bocor/pecah di jalur distribusi utama" |
| Penyebab (wajib) | "Pipa tua, tekanan tidak terkendali, inspeksi belum rutin" |
| Sasaran/KPI (wajib) | Pilih dari **dropdown Objektif RKAP** (mis. *Volume Air*) — teks terisi otomatis & masih bisa disunting; pilih *"tulis sendiri"* bila KPI belum ada di daftar |
| Sumber Risiko Tambahan | "Pemadaman listrik 3× dalam sebulan terakhir" (bukti/rujukan) |
| Indikator Emerging | Sinyal awal yang **belum** jadi risiko: "Rencana perubahan tarif", "Kemarau diprediksi lebih panjang" — kosongkan bila belum ada |
| Asumsi & Bias | "Genset diasumsikan selalu siap pakai" (asumsi) · "proyeksi pendapatan terlalu optimistis" (bias) |
| Kegiatan / Proses | "Produksi & distribusi air dari 25 sumur bor" (kosong = otomatis dari Sasaran/KPI) |
| Daftar Akibat | `Finansial\|Kerugian sampai Rp 500 juta` — satu akibat per baris |
| Proses | ☑ proses terkait |
| **Objektif** | ☑ **sasaran yang terpengaruh** (WAJIB dicentang — inilah kaitan risiko ↔ sasaran) |

Klik **Simpan**.

- [ ] Setiap risiko **menyentuh minimal 1 objektif** (centang di bagian Objektif)
- [ ] Rumusan memuat event + dampak terhadap sasaran (bukan sekadar "pompa rusak")

---

## TAHAP 4 — ANALISIS RISIKO

**Menu: Analisis** → buka risiko → **Isi Skor**

| Bagian | Isi |
|---|---|
| **Inherent** (sebelum kontrol) | Likelihood 1–5 × Consequence 1–5 → nilai terisi otomatis |
| **Residual** (sesudah kontrol) | Likelihood × Consequence sesudah kontrol |
| Kontrol uraian | kontrol yang sudah berjalan |
| Kontrol ada / memadai | Ada–Tidak / Memadai–Belum |
| **Risk Owner** | pihak yang **berwenang & akuntabel** atas risiko (Kepala UPK/Pimpinan unit), bukan sekadar pelaksana |
| Ringkasan | simpulan analisis |

- [ ] Skor **residual ≤ inherent** (kontrol menurunkan risiko)
- [ ] Risk Owner terisi (bukan kosong)

> **Catatan:** Risk Owner diisi di **Analisis**, ditampilkan di **Risk Register**.

---

## TAHAP 5 — EVALUASI RISIKO

**Menu: Evaluasi** → klik **Evaluasi** pada baris risiko

Di bagian atas form ditampilkan **ringkasan risiko** (kode, uraian risiko, unit, kategori,
tipe, tahun/TW awal, status kendali, status risiko, peristiwa, penyebab, dan sasaran/KPI)
supaya jelas risiko mana yang sedang dievaluasi — judul halaman hanya memuat kodenya.

Pengisian otomatis/guardrail:

1. **Nilai Evaluasi = skor RESIDUAL** (tidak bisa diisi bebas — sesuai QRMO slide 39)
2. **Level & rekomendasi aksi (band)** dihitung dari skala aktif:
   - Rendah (1–10) → **Accept**
   - Sedang (11–15) → **Monitor**
   - Tinggi (16–25) → **Treat**
3. **Keputusan**: pilih sesuai rekomendasi, atau pilih lain → **wajib tulis Alasan**
   - Jika beda dari rekomendasi → otomatis ditandai **PERLU_REVIEW**
4. Eskalasi: centang bila perlu eskalasi ke pimpinan, pilih `eskalasi_ke`
5. TW & tanggal evaluasi → **Simpan**

### Arti keputusan

| Keputusan | Artinya | Kapan dipilih | Akibatnya di aplikasi |
|---|---|---|---|
| **Accept** | Risiko diterima apa adanya; sisa risiko masih dalam batas appetite. | Skor residual ≤ appetite kategori, dampak kecil, kontrol memadai. *Contoh: skor 6, appetite 10.* | Tidak wajib rencana perlakuan; cukup pemantauan rutin. |
| **Monitor** | Belum perlu ditangani sekarang, tetapi harus diawasi. | Masih dalam appetite namun mendekati batas, atau level menengah dengan kontrol berjalan. *Contoh: skor 12, appetite 15.* | Dicatat di Monitoring tiap triwulan; bila tren naik, ubah ke `Treat`. |
| **Treat** | Risiko wajib diturunkan dengan tindakan nyata (mitigasi). | Skor melebihi appetite kategori atau level Tinggi dan berdampak pada sasaran/KPI. *Contoh: skor 18, appetite 15.* | Wajib disusun di menu **Perlakuan** (strategi, PIC, jadwal, anggaran); terhitung "perlu ditindaklanjuti" di Laporan. |
| **Escalate** | Di luar kewenangan unit — keputusan diserahkan ke atasan. | Skor melewati batas eskalasi kategori, berdampak lintas unit/strategis, atau butuh keputusan Direksi/Komite. *Contoh: skor 22, batas 15.* | Centang **Perlu Eskalasi** + isi **Eskalasi ke** agar muncul di kartu "Risiko Perlu Eskalasi" dashboard; rencana perlakuan tetap disusun. |

**Band hanya saran otomatis** dari skor residual; keputusan boleh berbeda asalkan alasannya
jelas. **Alasan wajib diisi** — mis. *"Skor 18 melebihi appetite 15 dan menyangkut 25 sumur bor."*
`Escalate` tanpa mencentang **Perlu Eskalasi** tidak muncul di dashboard.

**Review override (khusus Administrator/Auditor):**
Daftar evaluasi → filter **Status Review = Perlu Review** → klik **Review** → beri catatan → **Tandai Ditinjau**.

- [ ] Nilai evaluasi = skor residual (21/21 sudah)
- [ ] Keputusan yang menyimpang dari band sudah ditinjau Admin/Auditor

> **Catatan data saat ini:** 21 evaluasi sudah memakai residual; **20 berstatus PERLU_REVIEW** menunggu tinjauan.

---

## TAHAP 6 — PERLAKUAN RISIKO

**Menu: Perlakuan → Tambah**

| Field | Isi |
|---|---|
| Strategi | **Avoid / Reduce / Share / Accept** |
| Alasan Strategi | kenapa strategi itu dipilih |
| Uraian Tindakan | aksi konkret, mis. "preventive maintenance pipa + pemasangan pressure sensor" |
| Indikator Sukses | ukuran keberhasilan |
| Sumber Daya | SDM, alat, anggaran |
| Anggaran Biaya | Rp |
| Jadwal | Mulai – Selesai |
| PIC | pelaksana |
| **Status Laksana** | **BELUM → DALAM_PROSES → SELESAI** |

- [ ] Setiap risiko berkeputusan Treat/Escalate punya rencana perlakuan
- [ ] Status diperbarui berkala (bukan selamanya BELUM)

> **Catatan data saat ini:** 21 perlakuan ada, **20 masih status BELUM**.

---

## TAHAP 6B — RISK ASSESSMENT (REKAP HALAMAN 37 CRMO)

**Menu: Proses Man. Risiko → Risk Assessment**

Halaman **hanya-baca** yang menggabungkan empat tahap dalam satu tabel,
mengikuti contoh *Risk Assessment (Identifikasi, Analisis, dan Evaluasi Risiko)*
CRMO Perpamsi halaman 37. Tidak ada input baru di sini.

Kolom yang ditampilkan: No, No. ID Risiko, Sasaran/KPI, Risiko, Sumber,
Penyebab, Dampak, Rencana Pengendalian & Penanganan, Deskripsi Biaya Mitigasi,
PIC, K, D, Nilai, Tingkat Risiko, dan Keputusan.

- Filter: **Unit** (untuk Admin/role lintas unit), **Tingkat**, **Tahun**, dan **Cari**.
- Tombol: **Cetak / PDF** (A4 landscape) dan **Ekspor Excel (CSV)**.
- Akun UPK hanya melihat risiko unitnya sendiri.
- Bila ada kolom kosong, lengkapi di menu tahapannya (Register/Analisis/
  Evaluasi/Perlakuan), bukan di halaman ini.

> **Catatan:** contoh tabel lengkap dari materi CRMO juga ditampilkan di
> **Panduan Pengisian → Contoh Risk Assessment (hal. 37)**.

---

## TAHAP 7 — PEMANTAUAN & PENINJAUAN

### 7a. Monitoring (tiap triwulan)
**Menu: Monitoring → Tambah**

| Field | Isi |
|---|---|
| Tahun / TW | 2027 / TW1–TW4 |
| Tanggal Review | akhir triwulan |
| Status Tindakan | status pelaksanaan perlakuan |
| Hasil Monitoring | apa yang terjadi & apakah kontrol berjalan |
| Kontrol Masih Efektif | YA / TIDAK / SEBAGIAN |
| Skor Lama | **otomatis & terkunci**: skor catatan monitoring terakhir (tidak bisa diubah) |
| Skor Baru | diisi **L × D** (1–5) di form Monitoring; hasilnya otomatis (mis. `2 × 3 = 6`) |
| Arah Perubahan | **otomatis** dihitung: Naik / Turun / Tetap |
| Sebab Perubahan | alasan skor berubah (bila ada) |
| ☑ Perbarui Analisa | centang agar residual Analisa ikut menjadi Skor Baru (otomatis aktif bila skor berbeda) |
| ☑ Perlu Reassess | centang bila perlu penilaian ulang |

> Skor Baru boleh diubah langsung di Monitoring lewat **L × D**. Centang
> **Perbarui Analisa juga** (otomatis aktif bila skor berbeda) agar residual
> resmi di Analisa ikut berubah — tidak perlu input dua kali. Keputusan
> **Evaluasi** tidak diubah otomatis; bila level berubah, tinjau Evaluasi.

> KRI (Key Risk Indicator) **tidak dipakai** pada alur ini. Sesuai materi CRMO
> Perpamsi, setelah **Monitoring (tahap 7)** langsung **Pelaporan (tahap 8)**.
> Indikator pemantauan cukup dicatat pada kolom catatan/skor Monitoring.

- [ ] Monitoring TW berjalan untuk semua risiko AKTIF
- [ ] Skor Baru diisi **L × D**; centang **Perbarui Analisa** bila residual berubah

> **Catatan data saat ini:** monitoring TW1 21/21 sudah terisi.

---

## TAHAP 8 — PENCATATAN & PELAPORAN

**Menu: Laporan** — 4 jenis: Operasional, Manajemen, Dewan Pengawas, Tahunan.

### Langkah cetak laporan:

1. Di halaman **Laporan**, pilih Tahun, Triwulan, Jenis, dan Unit (atau *seluruh organisasi*)
2. Klik **Susun Laporan Otomatis** — seluruh bagian langsung terisi dari data
   Register, Analisis, Evaluasi, Perlakuan, dan Monitoring:
   ringkasan eksekutif, latar belakang, tujuan, ruang lingkup,
   rekap inherent/residual, peta risiko prioritas, rekap peluang,
   evaluasi & tindak lanjut, kesimpulan, rencana tindak lanjut, sumber catatan
3. Tinjau dan ubah bagian yang butuh penilaian manusia
   (mis. **Kesimpulan**, **Rencana Tindak Lanjut**)
4. **Simpan** — klik berulang pada *Susun Laporan Otomatis* memperbarui draf yang sama
5. **Sahkan** (status DRAFT → FINAL, tercatat siapa & kapan)
6. Klik **PDF** untuk cetak/unduh

> Laporan **FINAL** tidak ditimpa oleh *Susun Laporan Otomatis*. Ubah statusnya
> menjadi DRAFT terlebih dahulu bila ingin menyusun ulang.
> Tombol **Isi dari Data** hanya mengisi bagian yang masih kosong, sehingga
> teks yang sudah Anda tulis tidak hilang.

> Laporan unit (pilih UPK) dan konsolidasi (seluruh organisasi) bisa berdampingan.

---

## CHECKLIST AKHIR (sebelum laporan dianggap sah)

- [ ] **Objektif**: semua sasaran rekonsiliasi **SELESAI**
- [ ] **Komunikasi**: minimal satu sesi **SELESAI**, 4 pertanyaan terjawab, tindak lanjut punya PIC
- [ ] **Lingkup**: status **FINAL**, hasil konsultasi terisi
- [ ] **Register**: setiap risiko terhubung ke minimal 1 objektif
- [ ] **Analisis**: skor inherent + residual terisi, Risk Owner ada
- [ ] **Evaluasi**: nilai = residual; override sudah ditinjau Admin/Auditor
- [ ] **Perlakuan**: status tidak masih semua BELUM
- [ ] **Monitoring**: catatan triwulan terisi, skor baru (L × D) tercatat
- [ ] **Laporan**: Susun Laporan Otomatis → tinjau → Sahkan → PDF

Progres 8 tahap dapat dipantau real-time di **Dashboard** (kartu `x/y` + persen per tahap).

---

## MASALAH UMUM

| Gejala | Penyebab | Solusi |
|---|---|---|
| Tahap 1 di dashboard "Belum" | Belum ada sesi komunikasi berstatus SELESAI | Catat sesi di menu **Komunikasi & Konsultasi** |
| Tahap 2 di dashboard "Belum" | Lingkup masih DRAFT | Lengkapi & FINALkan Lingkup |
| Level risiko berubah sendiri | Level dihitung ulang dari skala | Cek skala di Pengaturan |
| Evaluasi masuk "Perlu Review" | Keputusan beda dari band residual | Ditinjau Admin/Auditor lewat menu Evaluasi |
| "Isi dari Data" tidak mengubah teks | Field sudah terisi | Hapus dulu teks lama, baru klik lagi |
| Laporan unit bentrok | unique key lama | Sudah diperbaiki (`id_upk` ikut kunci) |
| Data 2025–2026 tidak ada di sini | Modul legacy terpisah | Buka menu **Risiko** (legacy) |
