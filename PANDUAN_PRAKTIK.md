# PANDUAN PRAKTIK — Aplikasi Manajemen Risiko
### Perumdam Ijen Tirta · SNI ISO 31000:2018 · Tahun 2027

> **Untuk apa dokumen ini?**
> Aplikasi ini sudah terisi data contoh, sehingga sulit membedakan mana yang
> *sudah ada* dan mana yang *Anda masukkan sendiri*. Panduan ini menuntun Anda
> **dari nol** — menyiapkan aplikasi, lalu **memasukkan data sendiri satu per satu**
> dari Tahap 1 sampai Tahap 8, sampai aplikasinya benar-benar dipahami.
>
> **Cara pakai:** kerjakan berurutan, jangan lompat. Setiap langkah punya
> tombol URL, field wajib, dan **isi contoh yang tinggal Anda ketik**.

---

# BAGIAN A · MENYIAPKAN APLIKASI DARI NOL

## A1 · Prasyarat

| Kebutuhan | Keterangan |
|---|---|
| XAMPP | Apache + MySQL + PHP aktif (buka XAMPP Control Panel → **Start** Apache & MySQL) |
| Folder aplikasi | `C:\xampp\htdocs\asset\` |
| Browser | Chrome / Edge / Firefox |

Cara cek sudah jalan: buka `http://localhost/` → harus tampil halaman XAMPP.

## A2 · Konfigurasi (cukup sekali)

Dua berkas ini sudah terkonfigurasi benar — cukup diperiksa, **tidak perlu diubah**:

| Berkas | Nilai yang benar |
|---|---|
| `application/config/config.php` baris 26 | `$config['base_url'] = 'http://localhost/asset/';` |
| `application/config/config.php` baris 42 | `$config['index_page'] = '';` (tanpa `index.php`) |
| `application/config/database.php` baris 78-81 | host `localhost`, user `root`, sandi kosong, database `asset` |

> ⚠️ Jika pindah server, ubah `base_url` sesuai alamat baru.

## A3 · Database — membangun dari kosong

> ⚠️ **BACA DULU — ada 2 jenis data:**
>
> | Kelompok | Berasal dari | Isi |
> |---|---|---|
> | Tabel **tanpa prefix** & `mr_*` | `asset.sql` | Aplikasi lama: keuangan, aset, langganan, **Man. Risiko Lama** |
> | Tabel **`mri_*`** (22 tabel) | `application/sql/manrisk/*.sql` | **Man. Risiko Baru (ISO 31000)** |
>
> `asset.sql` berisi **95 perintah `DROP TABLE`** → hanya jalankan saat database
> benar-benar kosong / ingin mulai dari awal total.

### Jalur 1 — Mulai total dari kosong (paling aman untuk belajar)

1. Buka **phpMyAdmin** → `http://localhost/phpmyadmin/`
2. **New** → nama database `asset` → Create
3. Tab **Import** → pilih `C:\xampp\htdocs\asset\asset.sql` → Go
   *(ini tabel aplikasi lama + Man. Risiko Lama)*
4. Import **urut** kelima berkas ini (klik Import lagi untuk tiap berkas, **urutan wajib**):

   | Urut | Berkas | Isi |
   |---|---|---|
   | 1 | `application/sql/manrisk/01_schema.sql` | 23 tabel kosong `mri_*` (termasuk Komunikasi) |
   | 2 | `02_seed_master.sql` | master: proses, kategori, skala, risk owner, PIC, kunci |
   | 3 | `03_seed_objektif_2027.sql` | 1 lingkup + 28 objektif RKAP 2027 |
   | 4 | `04_seed_register_2027.sql` | 21 risiko + analisa/evaluasi/perlakuan |
   | 5 | `06_skala_3_level.sql` | skala evaluasi 3 level (Rendah/Sedang/Tinggi) |

> **Database lama yang masih memiliki modul KRI:** jalankan sekali
> `application/sql/manrisk/10_drop_kri.sql` untuk menghapus tabel `mri_kri`
> dan kolom `hasil_kri`. Backup data KRI sebelum dihapus tersedia di
> `application/sql/manrisk/backup/mri_kri_2026-11-03.sql`.

### Jalur 2 — Pakai keadaan sekarang (tidak usah ngapa-ngapain)

Kalau `http://localhost/asset/` sudah bisa dibuka, **database Anda sudah siap**.
Langsung ke **Bagian B**.

### Jalur 3 — Hanya merapikan tabel manrisk saja

```sql
-- jalankan di phpMyAdmin → SQL
DROP TABLE IF EXISTS mri_akibat, mri_analisa, mri_appetite, mri_evaluasi,
  mri_kategori_risiko, mri_kunci, mri_laporan, mri_lingkup,
  mri_monitoring, mri_objektif, mri_objektif_revisi, mri_perlakuan,
  mri_petugas_ttd, mri_pic, mri_proses, mri_register, mri_risiko_objektif,
  mri_risiko_proses, mri_risk_owner, mri_riwayat_skor, mri_tingkat_risiko;
```
lalu import ulang `01`, `02`, `03`, `04`, `06` sesuai tabel di atas.

## A4 · Masuk ke aplikasi

1. Buka **`http://localhost/asset/`** → langsung halaman login (route default = `auth`)
2. Masukkan **Nama Pengguna** dan **Kata Sandi** Anda → **Masuk**
3. Menu **Man. Risiko Baru** ada di sidebar (lihat Bagian C)

> **Level akun:** `Admin` (semua termasuk backup) dan `Pengguna` (operasional).
> Saat ini ada **28 akun**. Menu *Man. Risiko Baru* terlihat untuk semua akun.

## A5 · Jika lupa sandi

Sandi disimpan terenkripsi (`bcrypt`) — tidak bisa dibaca dari database.
Reset lewat **phpMyAdmin → tab SQL** (hasil hash di bawah = sandi teks
**`AkunBaru2027!`**):

```sql
UPDATE user
SET password = '$2y$10$eMTUKlHPPaNSugKBhvnMsO7CAVIbuX5OuuqZMLDEwMwyqUgs/Z3Tq'
WHERE nama_pengguna = 'admin';
```

Ganti `'admin'` dengan nama pengguna lain bila perlu. Lalu login:
**pengguna** = `admin` · **sandi** = `AkunBaru2027!`

> Ingin sandi lain? Buat hash-nya dulu:
> `php -r "echo password_hash('SandiBaru', PASSWORD_DEFAULT);"`
> lalu tempel hasilnya ke perintah `UPDATE` di atas.
>
> ⚠️ Setelah berhasil masuk, **segera ganti sandi** lewat menu pengaturan akun.

---

# BAGIAN B · KENALI KEADAAN DATANYA (ini sumber kebingungan Anda)

## B1 · Ada DUA modul risiko — jangan tertukar

| Sidebar | Rute | Tabel | Status |
|---|---|---|---|
| **Man. Risiko Baru** | `manrisk/*` | `mri_*` | **Modul ISO 31000 yang dipakai** — fokus latihan di sini |
| **Man. Risiko Lama** | `risiko/*` | `mr_*` | Warisan lama — **tidak perlu disentuh** saat latihan |

Panduan ini **hanya** membahas **Man. Risiko Baru**.

## B2 · Isi database saat ini (periksa sendiri bila perlu)

| Tabel | Jumlah | Arti |
|---|---|---|
| `mri_lingkup` | **1** | 1 dokumen lingkup & konteks sudah diisi |
| `mri_register` | **21** | 21 risiko sudah teridentifikasi |
| `mri_analisa` | **21** | semua sudah dinilai L×C |
| `mri_evaluasi` | **21** | semua sudah diberi keputusan |
| `mri_perlakuan` | **21** | semua sudah ada rencana treatment |
| `mri_monitoring` | **0** | **KOSONG** — latihan bebas di sini |
| `mri_laporan` | **0** | awalnya kosong — laporan latihan akan bertambah di sini |
| `mri_objektif` | **28** | 28 target RKAP 2027 |
| `mri_risiko_objektif` | **24** | 24 pasangan risiko ↔ objektif |

**Semua kunci transaksi = 0 (TERBUKA)** → tidak ada halaman yang terkunci.

## B3 · Data ini dari mana?

Semua baris di atas dihasilkan oleh file seed (`03`, `04`), penulis kolom
`created_by` = `seed`. **Ini contoh, bukan data operasional Anda.**

Cara cepat membedakan — lihat kolom `created_by`:

| Nilai | Arti |
|---|---|
| `seed` | bawaan awal / contoh |
| `admin`, `dicky`, dst. | **diinput lewat aplikasi** oleh user itu |

## B4 · STRATEGI LATIHAN (paling penting)

**Jangan mengubah baris yang sudah ada.** Buat data baru dengan **penanda** agar
mudah dikenali dan mudah dihapus:

| Jenis data | Penanda yang dipakai |
|---|---|
| Nama / indikator | diawali **`[LATIHAN]`** |
| Kode risiko | `IJT-OPR-99`, `IJT-KEU-99` (urut `99`) |
| Dokumen lingkup | Nama = `[LATIHAN] Lingkup Uji Coba` |

**Aturan main latihan:**
1. Selalu **Tambah baru**, jangan klik **Ubah** pada baris bawaan `seed`
2. Setiap tahap **minimal 1 data baru** bertanda `[LATIHAN]`
3. Setelah paham → bersihkan (Bagian F)
4. Sebelum mulai latihan → **buat cadangan dulu** (Bagian F1)

---

# BAGIAN C · PETA MENU

```
Sidebar → Man. Risiko Baru
│
├── Dashboard MR                     manrisk/dashboard
├── Proses Man. Risiko               ← ALUR 8 TAHAP, kerjakan berurutan
│   ├── 0    Obyektif/Sasaran        manrisk/objektif
│   ├── 1    Komunikasi & Konsultasi manrisk/komunikasi
│   ├── 2    Lingkup & Konteks       manrisk/lingkup
│   ├── 3    Identifikasi Risiko     manrisk/register
│   ├── 4    Analisis Risiko         manrisk/analisa
│   ├── 5    Evaluasi Risiko         manrisk/evaluasi
│   ├── 6    Perlakuan Risiko        manrisk/perlakuan
│   ├── 6B   Risk Assessment         manrisk/assessment
│   ├── 7    Pemantauan & Peninjauan manrisk/monitoring
│   ├── 8    Pelaporan & Pencatatan  manrisk/laporan
│   └── Matriks Risiko               manrisk/matrik
│
└── Panduan
    ├── Panduan Pengisian            manrisk/panduan/pengisian
    ├── Petunjuk Penggunaan          manrisk/panduan  (referensi teori)
    ├── Impor Risk Register          manrisk/import
    └── Pengaturan                   manrisk/pengaturan (kunci transaksi)
```

Buka **Dashboard MR** dulu — kartu **"Alur 8 Tahap"** menampilkan hitungan
otomatis per tahap. Angkanya akan bertambah saat Anda mengisi data.

---

# BAGIAN D · LATIHAN 8 TAHAP (isi sendiri, satu per satu)

> **Data latihan yang dipakai sepanjang panduan ini:**
> - Kode risiko: **`IJT-OPR-99`**
> - Penanda: **`[LATIHAN]`**
> - Risiko yang dibuat: *"[LATIHAN] Gangguan pasokan listrik menyebabkan pompa berhenti sehingga suplai air terhambat"*

---

## TAHAP 1 — Komunikasi & Konsultasi
**URL:** `http://localhost/asset/manrisk/komunikasi`
**Tombol:** **+ Tambah Sesi** (kanan atas)

Satu sesi = satu catatan komunikasi dengan pihak relevan. Isi contoh:

| Field | Isi contoh |
|---|---|
| Tahun / Triwulan | `2027` / `TW1` |
| Tanggal | pilih tanggal hari ini |
| Metode | `Rapat` |
| Unit | *(kosongkan untuk seluruh organisasi)* |
| **Topik / Agenda** ★ | **`[LATIHAN] Konsultasi sasaran penurunan NRW TW1 2027`** |
| PIC / Fasilitator | `Administrator` |
| Pihak yang Dilibatkan | `Direksi`<br>`Produksi & Distribusi`<br>`Keuangan`<br>`SPI / Assurance` |

**Foto pendukung (opsional):** pada bagian **Foto Pendukung**, pilih satu atau
beberapa foto dokumentasi rapat (JPG/PNG, maksimal 4 MB per foto). Foto tampil
di halaman detail sesi; buka detail lewat tombol **mata** di daftar.

Isi tiga langkah, misalnya:

| Langkah | Isi contoh |
|---|---|
| Dengarkan | `Tim NRW menyampaikan kebocoran banyak terjadi di zona lama; SPI mencatat keluhan pelanggan meningkat.` |
| Selaraskan | `Disepakati sasaran tahun ini adalah penurunan NRW; prioritas pada zona dengan kebocoran tertinggi.` |
| Uji & Konfirmasi | `Data kebocoran dicek dengan catatan DMA; asumsi "pipa tua" dikonfirmasi tim distribusi.` |

Jawab empat pertanyaan kunci (sasaran, risiko, kontrol, tindakan), lalu centang
lima hasil yang benar-benar diperoleh. Tutup dengan ringkasan, tindak lanjut,
PIC, target selesai, dan ubah **Status Sesi → `SELESAI`**.

**Simpan** → baris `[LATIHAN]` muncul di daftar, dan angka **tahap 1** di
Dashboard bertambah.

> **Yang dipelajari:** komunikasi menyamakan pemahaman sebelum risiko dinilai —
bukan sekadar notulen rapat. Lima hasil yang dicentang menjadi bukti bahwa
asumsi sudah dikonfirmasi bersama.

---

## TAHAP 2 — Lingkup, Konteks & Kriteria
**URL:** `http://localhost/asset/manrisk/lingkup`
**Tombol:** **+ Tambah Lingkup** (kanan atas)

**1 field wajib:** `Nama Lingkup`

Isi form seperti ini:

| Field | Isi contoh |
|---|---|
| Tahun | `2027` |
| Triwulan | `1` |
| Unit | *(kosongkan saja)* |
| **Nama Lingkup** ★ | **`[LATIHAN] Lingkup Uji Coba TA 2027`** |
| Status | `DRAFT` |
| Objek Evaluasi | `Seluruh proses bisnis perumdam, dari tata kelola sampai pelayanan` |
| Batas Evaluasi | `Wilayah organisasi: 6 divisi, 16 UPK, 1 unit AMDK. Periode TA 2027.` |
| Konteks Internal | `Struktur organisasi baru, RKAP 2027 disusun, keterbatasan anggaran` |
| Konteks Eksternal | `Regulasi air minum berubah, musim kemarau, biaya energi fluktuatif` |
| Kriteria Acuan | `Target Pendapatan Usaha Rp 22.381.036.762; Laba Bersih Rp 2.511.247.563` |
| Tanggal | pilih tanggal hari ini |
| PIC Konsultasi | `Administrator` |
| Peserta | `Direksi, Kabag Keuangan, Kabag Umum, Ketua SPI` |
| Pertanyaan — Sasaran | `Apa sasaran RKAP yang harus dicapai tahun ini?` |
| Pertanyaan — Risiko | `Risiko apa saja yang mengancam sasaran tersebut?` |
| Pertanyaan — Kontrol | `Kontrol apa yang sudah berjalan?` |
| Pertanyaan — Tindakan | `Tindakan perbaikan apa yang diperlukan?` |
| Hasil Konsultasi | `Disepakati 21 risiko utama, prioritas pada NRW dan penagihan.` |

**Simpan** → kembali ke daftar, **baris baru `[LATIHAN]` harus muncul di bawah**
baris `seed`. Tahap 2 dihitung dari dokumen lingkup; tahap 1 dari sesi komunikasi.

> **Catatan:** kolom Tanggal/Peserta/4 Pertanyaan/Hasil Konsultasi di form ini
> adalah ringkasan dokumen. Detail tiap sesi dicatat di menu **Komunikasi &
> Konsultasi** (Tahap 1).

> **Yang dipelajari:** Lingkup = *konteks* (ISO 6.1/6.2). Tanpa ini, risiko di
> tahap berikutnya tidak punya dasar "untuk siapa / untuk periode apa".

---

## TAHAP 3 — Identifikasi Risiko (Risk Register)
**URL:** `http://localhost/asset/manrisk/register`
**Tombol:** **+ Tambah Risiko** (atau `manrisk/register/form`)

**9 field wajib** (ditandai ★):

| Field | Isi contoh |
|---|---|
| Segmen Kode | `OPR` |
| Urut | `99` → kode otomatis jadi **`IJT-OPR-99`** |
| **Kode Risiko** ★ | `IJT-OPR-99` (harus unik — kalau sudah dipakai, ganti urut jadi `98`) |
| **Tahun** ★ | `2027` |
| TW Awal | `1` |
| **Unit / Bagian** ★ | `Keuangan` |
| **Kategori** ★ | `OPE — Operational` (kode + nama; penjelasan singkat & contoh muncul otomatis di bawah dropdown) |
| Lingkup / Unit Kerja | pilih dokumen lingkup `[LATIHAN]` (atau yang seed) |
| **Tipe Risiko** ★ | `Ancaman` (ada juga `Peluang`) |
| Status Kendali | `UC` (belum terkendali) |
| Status Risiko | `AKTIF` |
| **Rumusan Risiko** ★ | `[LATIHAN] Gangguan pasokan listrik menyebabkan pompa berhenti sehingga suplai air terhambat` |
| **Peristiwa Risiko** ★ | `Pemadaman listrik terjadi lebih dari 2 jam pada jam sibuk` |
| **Penyebab / Sumber** ★ | `Gangguan jaringan PLN dan tidak adanya genset cadangan yang terawat` |
| **Sasaran / KPI RKAP** ★ | pilih dari dropdown Objektif RKAP → `Volume Air` (teks terisi otomatis, boleh disunting; ada opsi *tulis sendiri*) |
| Sumber Risiko Tambahan | `Riwayat pemadaman 3x bulan lalu` |
| Indikator Emerging | *(kosong)* — sinyal awal yang **belum** jadi risiko, mis. `Rencana perubahan tarif 2028` |
| Asumsi & Bias | `Asumsi genset siap pakai ternyata belum diuji` (asumsi) · `proyeksi pendapatan terlalu optimistis` (bias) |
| Kegiatan / Proses | `Produksi & Sumber Air` |
| **Daftar Akibat** | tambah 1 baris: `Suplai air ke pelanggan terhambat` |
| Proses Terkait (Risk Universe) | ☑ **Produksi & Sumber Air** |
| **Objektif RKAP Terkait** | ☑ **Volume Air** *(ini kaitan risiko ↔ objektif)* |
| Catatan | `[LATIHAN] data uji coba` |

**Arti kode risiko** (`IJT-SEG-NN`):

| Bagian | Arti |
|---|---|
| `IJT` | **Ijen Tirta** — penanda organisasi, selalu tetap |
| `SEG` | Segmen/tema risiko (3–4 huruf, mis. `OPR`, `KEU`, `GOV`, `HR`, `IT`, `K3`, `EXT`, `PEL`, `REG`, `AMDK`) |
| `NN` | Nomor urut 2 digit **per segmen** |

Segmen **bukan turunan Kategori** — pilih sesuai tema isi risiko (mis. `IJT-GOV-*`
bisa berisi kategori Strategic sekaligus Fraud). Kode dipakai sebagai ID risiko di
analisis, evaluasi, perlakuan, monitoring, dan laporan, serta **wajib unik**,
tetapi **tidak memengaruhi perhitungan skor/level**. Segmen baru yang Anda ketik
akan tersimpan permanen sebagai pilihan dropdown berikutnya.

**Simpan** → baris `IJT-OPR-99` muncul di daftar.

**Cek pemahaman:** buka `manrisk/objektif` → cari baris **Volume Air** →
kolom **Risiko Terikat** bertambah 1 (saat ini `2` → jadi `3`). Itu bukti
relasinya hidup. Sebaliknya, baris yang tidak Anda ikatkan tetap angka lama.

> **Yang dipelajari:** Risiko ditulis pola **Peristiwa → Penyebab → Akibat**,
> dan diikat ke **Objektif RKAP** = jawaban *"kenapa risiko ini penting"*.

---

## TAHAP 4 — Analisis Risiko (L × C)
**URL:** `http://localhost/asset/manrisk/analisa`
**Tombol:** pada baris `IJT-OPR-99` klik tombol **+ Analisa**
(rute: `manrisk/analisa/isi/{id}`)

**4 field wajib** + 3 pendukung:

| Field | Isi contoh | Pilihan tersedia |
|---|---|---|
| **Likelihood (Inherent)** ★ | `4 — sering` | 1 sangat jarang · 2 jarang · 3 kadang · 4 sering · 5 sangat sering |
| **Dampak (Inherent)** ★ | `4 — besar` | 1 dapat diabaikan · 2 kecil · 3 sedang · 4 besar · 5 sangat besar |
| **Likelihood (Residual)** ★ | `2 — jarang` | skala sama |
| **Dampak (Residual)** ★ | `3 — sedang` | skala sama |
| Uraian Kontrol | `Genset diservis tiap 6 bulan, ada prosedur beban listrik prioritas` | |
| Kontrol Ada? | `Ada` | Ada / Tidak |
| Kontrol Memadai? | `Belum Memadai` | Memadai / Belum Memadai |
| Risk Owner | pilih salah satu dari 23 owner | |
| Ringkasan Analisis | `Pemadaman sering terjadi; kontrol belum menjamin kelangsungan suplai.` | |

**Simpan** → aplikasi **menghitung otomatis**:

```
Inherent  = 4 × 4 = 16  →  Tinggi  (aksi: Treat)
Residual  = 2 × 3 =  6  →  Rendah  (aksi: Accept)
```

Band skor diambil dari `mri_tingkat_risiko` (bukan hardcode):

| Skor | Level | Aksi bawaan |
|---|---|---|
| 1–10 | **Rendah** | Accept |
| 11–15 | **Sedang** | Monitor |
| 16–25 | **Tinggi** | Treat |

**Cek:** skor & pita warna langsung muncul di form, dan kolom *Nilai Inherent*
di daftar analisa terisi.

> **Yang dipelajari:** *Inherent* = skor **tanpa** kontrol, *Residual* = skor
> **setelah** kontrol. Selisih keduanya = manfaat pengendalian.

---

## TAHAP 5 — Evaluasi Risiko (keputusan)
**URL:** `http://localhost/asset/manrisk/evaluasi`
**Tombol:** pada baris `IJT-OPR-99` klik **+ Evaluasi**
(rute: `manrisk/evaluasi/form/{id}`)

**Field penting:**

| Field | Isi contoh |
|---|---|
| **Nilai Evaluasi** | terisi otomatis dari skor residual — **tidak bisa diubah** |
| **Keputusan** ★ | `Treat` (contoh override band) — pilihan: `Accept` · `Monitor` · `Treat` · `Escalate` |
| **Triwulan** ★ | `1` |
| Tanggal | tanggal hari ini |
| Kriteria Rujukan | `Residual 6 (Rendah)` — diisi otomatis |
| **Alasan / Dasar Penilaian** ★ | `Skor residual 6 masuk band Rendah, namun suplai air adalah layanan inti sehingga diputuskan untuk ditangani.` |
| ☑ Perlu eskalasi | centang bila perlu → muncul field **Eskalasi ke** |
| Eskalasi ke | `Direksi` |

**Simpan** → badge keputusan tampil di daftar.

**Arti keputusan (jangan asal pilih):**

| Keputusan | Kapan dipilih | Akibatnya |
|---|---|---|
| **Accept** | Skor ≤ appetite, dampak kecil. *Contoh: skor 6, appetite 10.* | Tidak wajib perlakuan; pemantauan rutin. |
| **Monitor** | Masih dalam appetite tapi mendekati batas. *Contoh: skor 12, appetite 15.* | Dicatat di Monitoring tiap TW. |
| **Treat** | Melebihi appetite / level Tinggi. *Contoh: skor 18, appetite 15.* | **Wajib** rencana di menu Perlakuan (PIC, jadwal, anggaran). |
| **Escalate** | Melewati batas eskalasi / lintas unit. *Contoh: skor 22, batas 15.* | Centang **Perlu Eskalasi** + isi **Eskalasi ke** → muncul di dashboard. |

> Di bagian atas form kini ada ringkasan risiko (kode, uraian, unit, kategori, peristiwa,
> penyebab, sasaran/KPI) supaya tidak salah risiko — judul halaman hanya memuat kode.
> Band hanya saran otomatis; memilih beda dari band → **Perlu Review**, alasan wajib diisi.

> **Yang dipelajari:** Analisis *menghitung*, Evaluasi *memutuskan*.
> Hasil angka di tahap 4 barulah dibandingkan dengan kriteria/risk appetite.

---

## TAHAP 6 — Perlakuan / Treatment
**URL:** `http://localhost/asset/manrisk/perlakuan`
**Tombol:** ikon pensil pada baris `IJT-OPR-99`
(rute: `manrisk/perlakuan/form/{id}`)

**3 field wajib:**

| Field | Isi contoh |
|---|---|
| **Strategi** ★ | ☑ **`REDUCE`** |
| | `AVOID` — menghindari, tidak melakukan kegiatan |
| | `REDUCE` — mengurangi kemungkinan/dampak |
| | `SHARE` — berbagi (asuransi, kontrak) |
| | `ACCEPT` — menerima dengan pemantauan |
| **Alasan Pemilihan Strategi** | `Risiko inti layanan, tidak bisa dihindari sehingga dikurangi lewat kesiapan genset.` |
| **Uraian Tindakan** ★ | `Uji coba genset bulanan, sediakan kontrak servis, siapkan prosedur load shedding` |
| Indikator Keberhasilan | `0 kali pemadaman suplai > 30 menit per triwulan` |
| Sumber Daya | `Teknisi listrik, anggaran servis` |
| Anggaran Biaya (Rp) | `25000000` (format Indonesia: `25.000.000` juga diterima) |
| Jadwal Mulai | `2027-01-05` |
| Jadwal Selesai | `2027-03-31` |
| PIC | pilih salah satu dari 11 PIC |
| **Status Pelaksanaan** ★ | `Rencana` |

**Simpan.**

> **Yang dipelajari:** ini **rencana kerja** risiko — siapa, kapan, berapa biaya,
> indikator keberhasilannya bagaimana.

---

## TAHAP 6B — Risk Assessment (rekap halaman 37 CRMO)
**URL:** `http://localhost/asset/manrisk/assessment`

Halaman **hanya-baca** yang merangkum Identifikasi → Analisis → Evaluasi →
Perlakuan dalam satu tabel, mengikuti contoh *Risk Assessment* CRMO Perpamsi
halaman 37. Tidak ada input di sini; ubah data lewat menu tahapannya.

| Kolom | Diambil dari |
|---|---|
| No. ID Risiko | Register (`kode_risiko`) |
| Sasaran / KPI | Register + objektif yang dicentang |
| Risiko, Sumber, Penyebab | Register |
| Dampak | daftar akibat pada Register |
| Rencana Pengendalian & Penanganan | kontrol (Analisis) + uraian tindakan (Perlakuan) |
| Deskripsi Biaya Mitigasi | anggaran perlakuan |
| PIC | master PIC pada Perlakuan |
| K, D, Nilai, Tingkat | Analisis (inherent) |
| Keputusan | Evaluasi (Accept/Monitor/Treat/Escalate) |

Tersedia filter **Unit**, **Tingkat**, **Tahun**, dan **Cari**, serta tombol
**Cetak / PDF** dan **Ekspor Excel (CSV)**. Akun UPK otomatis hanya melihat
risiko unitnya sendiri.

> **Yang dipelajari:** tabel ini adalah bentuk akhir yang dinilai — pastikan
> tidak ada kolom kosong sebelum laporan dibuat.

---

## TAHAP 7 — Pemantauan & Peninjauan
**URL:** `http://localhost/asset/manrisk/monitoring`
**Tombol:** **+ Tambah Monitoring** → `manrisk/monitoring/form`

> 💡 Tabel ini **masih KOSONG (0 baris)** — latihan paling aman di sini.

**2 field wajib:**

| Field | Isi contoh |
|---|---|
| Tahun | `2027` |
| **Triwulan** ★ | `1` |
| Tanggal Review | `2027-04-05` |
| **Hasil Monitoring** ★ | `[LATIHAN] Genset diuji 2 kali, masih ada 1 kali gagal start pada percobaan pertama.` |
| Status Tindakan | `Berjalan` |
| Kontrol Masih Efektif | `Belum` |
| Skor Lama | **otomatis & terkunci**: `6` |
| Skor Baru | isi **L × D** di Monitoring, mis. `3 × 3 = 9` |
| Arah Perubahan | **otomatis**: `Naik` |
| ☑ Perbarui Analisa juga | otomatis tercentang saat skor berbeda → residual Analisa ikut menjadi `9` |
| ☑ Perlu reassess | centang bila risiko harus dinilai ulang |
| Sebab Perubahan | `Tambahan beban baru di jaringan` |

**Simpan** → **Dashboard MR** angka tahap 7 naik dari `0` jadi `1`.

> **Yang dipelajari:** monitoring adalah *mengulang* analisa secara berkala —
> apakah kontrol masih efektif, apakah skor berubah.

---

## TAHAP 8 — Pelaporan & Pencatatan
**URL:** `http://localhost/asset/manrisk/laporan`

> 💡 Laporan yang sudah ada bisa berupa DRAFT atau FINAL. Laporan **FINAL**
> tidak ditimpa oleh *Susun Laporan Otomatis*.
> Sesuai materi CRMO Perpamsi, setelah Monitoring (tahap 7) langsung Pelaporan.
> Tidak ada tahap KRI.

Pilih **Tahun**, **Triwulan**, **Jenis Laporan**, dan **Unit**, lalu klik
**Susun Laporan Otomatis**. Seluruh bagian laporan langsung terisi dari data
Register sampai Monitoring.

| Field | Isi contoh |
|---|---|
| Tahun | `2027` |
| Triwulan | `1` |
| **Jenis Laporan** ★ | `Operasional` |
| Cakupan | `Keuangan` (atau *seluruh organisasi*) |

**Susun** → draf laporan terbuka dengan seluruh bagian terisi. Tinjau, ubah bila
perlu, lalu **Simpan**. Setelah yakin, klik aksi **Sahkan** (`laporan/sahkan/{id}`)
agar status menjadi FINAL, kemudian **PDF**.

**SELESAI.** Buka `manrisk/dashboard` → seluruh tahap proses (1–8) kini bertanda
terisi (hijau), karena Anda sudah melewati semuanya.

---

# BAGIAN E · MODUL PENUNJANG

## E1 · Objektif & RKAP
**URL:** `http://localhost/asset/manrisk/objektif`

Tujuan: menyimpan **target angka RKAP**, bukan risiko.
Kaitkan ke risiko lewat **form Risiko** (Tahap 3), bukan di halaman ini.

- **+ Tambah Objektif** → isi: indikator, target RKAP (format `10.613.952.106`
  atau `10613952106.00` sama saja), baseline, satuan, sumber nilai, bagian
- **Ubah** → ubah nilai; jika nilai berubah akan diminta
  **Alasan Perubahan** dan tersimpan di jejak audit `mri_objektif_revisi`
- **Rekonsiliasi** → tandai status `SELESAI` / `BELUM`
- Kolom **Bagian** menampilkan pemilik, kolom **Risiko Terikat** = jumlah risiko

Coba: tambah objektif ber-nama **`[LATIHAN] Target Uji Coba`** lalu hapus.

## E2 · Matriks Risiko
**URL:** `http://localhost/asset/manrisk/matrik`
Matriks 5×5 (Likelihood × Consequence) berisi seluruh risiko — **read-only**,
berguna melihat posisi `IJT-OPR-99` yang baru Anda buat.

## E3 · Impor Risk Register
**URL:** `http://localhost/asset/manrisk/import`
Mengimpor banyak risiko sekaligus dari tabel. **Jangan dipakai saat latihan**
agar data contoh tidak tertimpa.

## E4 · Pengaturan (Kunci Transaksi)
**URL:** `http://localhost/asset/manrisk/pengaturan`

Setiap halaman punya kunci. **`0` = TERBUKA, `1` = TERKUNCI.**
Saat ini **semua = 0**.

| Kode kunci | Mengunci halaman |
|---|---|
| `input_risiko` | Lingkup, Register, Impor |
| `analisa_risiko` | Analisis, Evaluasi |
| `penanganan_risiko` | Perlakuan |
| `monitoring_risiko` | Monitoring |
| `objektif_risiko` | Objektif & RKAP |
| `laporan_risiko` | Laporan |
| `profil_risiko` | warisan Man. Risiko Lama |

> **Latihan:** kunci `laporan_risiko` → tombol di halaman Laporan hilang →
> buka kunci lagi. Ini cara paling cepat memahami fungsi Pengaturan.

---

# BAGIAN F · BERSIH-BERSIH & CADANGAN

## F1 · CADANGAN — lakukan SEBELUM latihan

**Cara termudah (dari aplikasi):** buka `http://localhost/asset/backup`
(hanya level `Admin`) → tombol **Backup** → unduh file `.zip`.

**Cara manual (phpMyAdmin / terminal):**
```bash
C:/xampp/mysql/bin/mysqldump.exe -uroot asset > C:/xampp/asset_sebelum_latihan.sql
```

## F2 · Hapus data latihan

Karena Anda memakai penanda `[LATIHAN]`, pembersihan sangat mudah.
Jalankan di phpMyAdmin → tab **SQL**:

```sql
-- 1. risiko latihan + seluruh turunannya
DELETE a FROM mri_analisa  a JOIN mri_register r ON r.id_register=a.id_register WHERE r.kode_risiko='IJT-OPR-99';
DELETE e FROM mri_evaluasi e JOIN mri_register r ON r.id_register=e.id_register WHERE r.kode_risiko='IJT-OPR-99';
DELETE p FROM mri_perlakuan p JOIN mri_register r ON r.id_register=p.id_register WHERE r.kode_risiko='IJT-OPR-99';
DELETE m FROM mri_monitoring m JOIN mri_register r ON r.id_register=m.id_register WHERE r.kode_risiko='IJT-OPR-99';
DELETE FROM mri_risiko_objektif WHERE id_register = (SELECT id_register FROM (SELECT id_register FROM mri_register WHERE kode_risiko='IJT-OPR-99') x);
DELETE FROM mri_risiko_proses  WHERE id_register = (SELECT id_register FROM (SELECT id_register FROM mri_register WHERE kode_risiko='IJT-OPR-99') x);
DELETE FROM mri_riwayat_skor   WHERE id_register = (SELECT id_register FROM (SELECT id_register FROM mri_register WHERE kode_risiko='IJT-OPR-99') x);
DELETE FROM mri_register WHERE kode_risiko='IJT-OPR-99';

-- 2. objektif latihan
DELETE FROM mri_objektif WHERE indikator LIKE '[LATIHAN]%';

-- 3. dokumen lingkup latihan
DELETE FROM mri_lingkup WHERE nama_lingkup LIKE '[LATIHAN]%';

-- 4. monitoring / laporan latihan
DELETE FROM mri_monitoring  WHERE hasil_monitoring LIKE '[LATIHAN]%';
DELETE FROM mri_laporan     WHERE ringkasan_eksekutif LIKE '[LATIHAN]%';

-- 5. jejak audit revisi objektif (kalau sempat tercatat)
DELETE FROM mri_objektif_revisi WHERE id_objektif NOT IN (SELECT id_objektif FROM mri_objektif);
```

**Verifikasi** — angka harus kembali seperti Bagian B2:

```sql
SELECT 'lingkup' t, COUNT(*) c FROM mri_lingkup
UNION ALL SELECT 'register',  COUNT(*) FROM mri_register
UNION ALL SELECT 'objektif',  COUNT(*) FROM mri_objektif
UNION ALL SELECT 'monitoring',COUNT(*) FROM mri_monitoring
UNION ALL SELECT 'laporan',   COUNT(*) FROM mri_laporan;
```
| Tabel | Balik ke |
|---|---|
| lingkup | 1 |
| register | 21 |
| objektif | 28 |
| monitoring | 0 |
| laporan | 0 |

> **Cara paling ringan:** pulihkan file cadangan F1 lewat `backup/restore`
> (Admin) atau import ulang `03`, `04` + hapus baris `[LATIHAN]`.

---

# BAGIAN G · P3K (MASALAH UMUM)

| Gejala | Penyebab | Solusi |
|---|---|---|
| Halaman kosong / **HTTP 500** | Error PHP | Buka `php_error_log`; biasanya berkas yang baru diedit |
| Langsung ke halaman login terus | Sesi habis | Login ulang |
| Tombol **Tambah/Ubah tidak ada** | Kunci aktif | `manrisk/pengaturan` → pastikan status = **0** |
| Pesan *"tidak memiliki hak akses"* | Level `Pengguna` di halaman Admin | Login pakak akun `admin` |
| Halaman lain tampil tapi manrisk kosong | Tabel `mri_*` belum ada | Import `application/sql/manrisk/01_schema.sql` … `11` |
| Kode risiko *"sudah dipakai"* | Duplikat | Ubah **Urut** (mis. `98`) atau kode manual |
| Angka `500.000` jadi `500` | Salah format | Pakai `500000` atau `500.000.000` (ribuan) — sudah ditangani otomatis |
| Sidebar menu risiko tidak muncul | Belum login / cookie mati | Login ulang, aktifkan cookie |
| Perubahan kode tidak terasa | Cache | `Ctrl + F5` |
| Reset total | Ingin ulang | Bagian F1 (restore) atau Bagian A3 (Jalur 1) |

---

# RINGKASAN — CHECKLIST LATIHAN

- [ ] **A** — Aplikasi terbuka di `http://localhost/asset/` dan bisa login
- [ ] **F1** — Cadangan dibuat
- [ ] **B1** — Paham bedanya *Man. Risiko Baru* vs *Man. Risiko Lama*
- [ ] **B4** — Komitmen: hanya menambah data `[LATIHAN]`, tidak mengubah `seed`
- [ ] **D-Tahap 1** — Sesi komunikasi `[LATIHAN]` berstatus `SELESAI` tersimpan
- [ ] **D-Tahap 2** — Dokumen lingkup `[LATIHAN]` tersimpan
- [ ] **D-Tahap 3** — Risiko `IJT-OPR-99` tersimpan & terikat ke objektif **Volume Air**
- [ ] **D-Tahap 4** — Skor inherent 16 (Tinggi), residual 6 (Rendah) terhitung otomatis
- [ ] **D-Tahap 5** — Keputusan `Treat` tersimpan
- [ ] **D-Tahap 6** — Strategi `REDUCE` + jadwal + anggaran tersimpan
- [ ] **D-Tahap 7** — Monitoring TW 1 tersimpan, angka tahap 7 di dashboard naik
- [ ] **D-Tahap 8** — 1 laporan tersusun otomatis, seluruh tahap hijau di dashboard
- [ ] **E1** — Objektif ditambah lalu dihapus (tahu bedanya dengan risiko)
- [ ] **E4** — Pernah mengunci + membuka 1 kunci
- [ ] **F2** — Data latihan dibersihkan, hitungan kembali seperti awal

---

### Lampiran cepat — alamat halaman

```
http://localhost/asset/manrisk/dashboard
http://localhost/asset/manrisk/lingkup
http://localhost/asset/manrisk/register
http://localhost/asset/manrisk/analisa
http://localhost/asset/manrisk/evaluasi
http://localhost/asset/manrisk/perlakuan
http://localhost/asset/manrisk/monitoring
http://localhost/asset/manrisk/laporan
http://localhost/asset/manrisk/matrik
http://localhost/asset/manrisk/objektif
http://localhost/asset/manrisk/panduan        ← referensi teori/materi
http://localhost/asset/manrisk/pengaturan     ← kunci transaksi
http://localhost/asset/backup                 ← cadangan (Admin)
```
