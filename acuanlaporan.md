# Laporan Implementasi Sistem Database
> Dokumen ini merangkum materi pembelajaran basis data dari Pertemuan 1–7 sebagai kerangka analisis dan referensi sistem yang dibangun.

---

## Daftar Isi

1. [Pertemuan 1 — Basis Data & DBMS](#pertemuan-1--basis-data--dbms)
2. [Pertemuan 2 — Kebutuhan Pengguna & Sistem Database](#pertemuan-2--kebutuhan-pengguna--sistem-database)
3. [Pertemuan 3 — Normalisasi & Fungsi Agregat](#pertemuan-3--normalisasi--fungsi-agregat)
4. [Pertemuan 4 — Query untuk Multiple Relation](#pertemuan-4--query-untuk-multiple-relation)
5. [Pertemuan 5 — SQL Processing & Query Optimization (Oracle)](#pertemuan-5--sql-processing--query-optimization-oracle)
6. [Pertemuan 6 — Query Operator, Transformation & Optimization](#pertemuan-6--query-operator-transformation--optimization)
7. [Pertemuan 7 — Database Security](#pertemuan-7--database-security)

---

## Pertemuan 1 — Basis Data & DBMS

### Konsep File vs Database

| Aspek | Sistem File | Database |
|-------|-------------|----------|
| Penyimpanan | Terpisah per aplikasi | Terpusat & terintegrasi |
| Duplikasi | Tinggi | Minimal |
| Konsistensi | Rendah | Terjaga |
| Berbagi data | Sulit | Mudah |

### Komponen Utama DBMS

- **Database** — kumpulan data terorganisir beserta metadata
- **DBMS** — perangkat lunak pengelola (Query Processor, DDL, DML, Runtime)
- **Aplikasi** — antarmuka bagi pengguna
- **User** — end-user, programmer, analis sistem
- **DA (Data Administrator)** — kebijakan & standar data
- **DBA (Database Administrator)** — performa, keamanan, pemeliharaan teknis

### Terminologi Penting

| Istilah | Definisi |
|---------|----------|
| Entitas | Objek nyata yang direpresentasikan dalam tabel |
| Atribut | Karakteristik/kolom dari entitas |
| Record/Tuple | Kumpulan atribut satu entitas (satu baris) |
| Primary Key | Atribut unik pengidentifikasi setiap record |
| Metadata | Data yang mendeskripsikan struktur data |
| CRUD | Create, Read, Update, Delete |

### Tingkat Abstraksi Database

```
Level Konseptual  →  "Data apa yang dibutuhkan?"
Level Logis       →  "Bagaimana struktur tabel & relasinya?"
Level Fisik       →  "Bagaimana data disimpan secara fisik?"
```

### Konfigurasi Database

- **Standalone** — satu mesin, satu pengguna
- **Client/Server** — server terpusat, banyak klien
- **Terdistribusi** — data tersebar di banyak node

---

## Pertemuan 2 — Kebutuhan Pengguna & Sistem Database

### Model Representasi Data

```
Kebutuhan Pengguna
      ↓
Model Eksternal   (perspektif tiap aplikasi/user)
      ↓
Model Konseptual  (gambaran global, technology-agnostic)
      ↓
Model Logis       (tabel, atribut, relasi — DBMS-aware)
      ↓
Model Internal    (struktur fisik, indeks, mekanisme akses)
```

### Jenis Kebutuhan Sistem

| Jenis | Deskripsi | Contoh |
|-------|-----------|--------|
| **Fungsional** | Fungsi/layanan yang harus ada | Penyimpanan, pencarian, laporan |
| **Non-Fungsional** | Kualitas sistem | Performa, keamanan, keandalan |
| **Pengguna** | Harapan dari perspektif user | Kemudahan input, kecepatan akses |

### Tahapan Requirements Engineering

```
1. Elicitation       → Menggali kebutuhan dari stakeholders
2. Analysis          → Menganalisis & menegosiasikan kebutuhan
3. Specification     → Mendokumentasikan kebutuhan secara formal
4. Validation        → Memvalidasi bersama stakeholders
```

### Karakteristik Spesifikasi Kebutuhan yang Baik

- **Valid** — mencerminkan kebutuhan nyata
- **Lengkap** — tidak ada yang terlewat
- **Konsisten** — tidak bertentangan satu sama lain
- **Tidak Ambigu** — satu makna, tidak multitafsir
- **Dapat Diverifikasi** — bisa diuji
- **Mudah Dipahami** — termasuk oleh non-teknis
- **Dapat Dimodifikasi** — mudah diperbarui

---

## Pertemuan 3 — Normalisasi & Fungsi Agregat

### Mengapa Normalisasi Dibutuhkan

Tabel universal (semua data dalam satu tabel) menyebabkan:
- Redundansi data
- Anomali insert, update, delete
- Inkonsistensi & kesulitan pengelolaan

### Tahapan Normalisasi

#### 1NF — First Normal Form
> Setiap atribut harus bernilai **atomik**. Tidak ada grup berulang dalam satu field.

**Sebelum 1NF:**

| NRP | Nama | Hobi |
|-----|------|------|
| 001 | Andi | Sepakbola, Membaca |

**Sesudah 1NF:** Pecah tabel Mahasiswa & tabel Hobi (one-to-many).

#### 2NF — Second Normal Form
> Sudah 1NF + **tidak ada ketergantungan parsial** (atribut non-kunci bergantung penuh pada seluruh kunci gabungan).

**Contoh dekomposisi:**
- `{Mhs_nrp, Mk_kode}` → Tabel Nilai
- `Mhs_nrp` → Tabel Mahasiswa
- `Mk_kode` → Tabel MataKuliah

#### 3NF — Third Normal Form
> Sudah 2NF + **tidak ada ketergantungan transitif** (atribut non-kunci tidak bergantung pada atribut non-kunci lain).

**Contoh:**
```
alm_kodepos → {alm_Provinsi, alm_Kota}  ← transitif, harus dipisah
```
Solusi: pisah tabel Kodepos.

#### BCNF — Boyce-Codd Normal Form
> Setiap **determinan harus merupakan kunci kandidat**. Penyempurnaan kasus 3NF yang masih memiliki anomali.

### Ringkasan Normalisasi

| Bentuk Normal | Aturan Utama |
|---------------|-------------|
| 1NF | Nilai atomik, hapus grup berulang |
| 2NF | Hapus ketergantungan parsial |
| 3NF | Hapus ketergantungan transitif |
| BCNF | Setiap determinan adalah kunci kandidat |

### Fungsi Agregat

| Fungsi | Deskripsi | Contoh Query |
|--------|-----------|--------------|
| `COUNT()` | Hitung jumlah baris | `SELECT COUNT(pembeli) FROM TblBeli` |
| `SUM()` | Total nilai | `SELECT SUM(harga) FROM TblBeli WHERE pembeli='Ujang'` |
| `AVG()` | Rata-rata | `SELECT AVG(harga) FROM TblBeli WHERE pembeli='Cecep'` |
| `MAX()` | Nilai terbesar | `SELECT MAX(salary) FROM employees` |
| `MIN()` | Nilai terkecil | `SELECT MIN(salary) FROM employees` |

**Kombinasi dengan GROUP BY:**
```sql
SELECT pembeli, SUM(harga) AS Jml_Harga
FROM TblBeli
GROUP BY pembeli;
```

---

## Pertemuan 4 — Query untuk Multiple Relation

### Struktur Relasi Antar Tabel

```
Matkul (1) ──── (N) Mahasiswa (N) ──── (N) Dosen
                        │                    via tabel Pembimbing
                      (N:1)
                       Prodi
                      (1:1)
                        TA
```

### Karakteristik Multiple Relation

- Setiap tabel merepresentasikan satu entitas
- Primary Key → identitas unik per baris
- Foreign Key → penghubung antar tabel
- Junction Table → menangani relasi N:N (contoh: tabel Pembimbing)

### Jenis Relasi

| Jenis | Deskripsi |
|-------|-----------|
| 1:1 | Satu record berhubungan dengan tepat satu record lain |
| 1:N | Satu record berhubungan dengan banyak record |
| N:N | Banyak record berhubungan dengan banyak record (butuh junction table) |

### Implementasi JOIN Query

#### INNER JOIN
> Hanya menampilkan data yang **memiliki kecocokan di kedua tabel**.

```sql
SELECT *
FROM mahasiswa
INNER JOIN pembimbing ON mahasiswa.nid = pembimbing.nid;
```

#### LEFT JOIN
> Semua data dari tabel kiri + data cocok dari kanan. Tidak cocok → `NULL`.

```sql
SELECT *
FROM mahasiswa
LEFT JOIN pembimbing ON mahasiswa.nid_dosen = pembimbing.nid;
```

#### RIGHT JOIN
> Semua data dari tabel kanan + data cocok dari kiri. Tidak cocok → `NULL`.

```sql
SELECT *
FROM mahasiswa
RIGHT JOIN pembimbing ON mahasiswa.nid_dosen = pembimbing.nid;
```

#### FULL JOIN
> **Semua data dari kedua tabel**, cocok maupun tidak.

```sql
-- MySQL tidak support FULL JOIN langsung, gunakan UNION:
SELECT * FROM mahasiswa LEFT JOIN pembimbing ON mahasiswa.nid_dosen = pembimbing.nid
UNION
SELECT * FROM mahasiswa RIGHT JOIN pembimbing ON mahasiswa.nid_dosen = pembimbing.nid;
```

### Perbandingan Hasil JOIN

| Jenis JOIN | Data Kiri | Data Kanan | Tidak Cocok |
|------------|-----------|-----------|-------------|
| INNER JOIN | ✓ (cocok) | ✓ (cocok) | ✗ |
| LEFT JOIN | ✓ (semua) | ✓ (cocok) | NULL di kanan |
| RIGHT JOIN | ✓ (cocok) | ✓ (semua) | NULL di kiri |
| FULL JOIN | ✓ (semua) | ✓ (semua) | NULL di keduanya |

---

## Pertemuan 5 — SQL Processing & Query Optimization (Oracle)

> Referensi: [Oracle Database 19c SQL Tuning Guide](https://docs.oracle.com/en/database/oracle/oracle-database/19/tgsql/sql-processing.html)

### Tahapan SQL Processing

```
1. Parsing
   ├── Syntax Check     → apakah sintaks valid?
   ├── Semantic Check   → apakah objek/kolom ada?
   └── Shared Pool Check → apakah bisa reuse dari cache?
         ├── Hard Parse  → buat execution plan baru (mahal)
         └── Soft Parse  → reuse dari shared pool (murah)

2. Optimization
   └── Query Optimizer memilih execution plan terbaik

3. Row Source Generation
   └── Menghasilkan iterative execution plan

4. Execution
   └── Menjalankan statement sesuai plan
```

### Hard Parse vs Soft Parse

| Aspek | Hard Parse | Soft Parse |
|-------|-----------|-----------|
| Terjadi saat | Statement baru / tidak ada di cache | Statement sama ditemukan di shared pool |
| Proses | Syntax → Semantic → Optimization → Plan | Skip ke eksekusi |
| Performa | Lambat (resource-intensive) | Cepat |
| Rekomendasi | Hindari dengan query caching / bind variables | Preferensikan ini |

### Implikasi untuk Sistem yang Dibangun

- Gunakan **prepared statements / bind variables** untuk menghindari hard parse berulang
- Pantau **shared pool hit rate** di production
- **DDL selalu hard parse** — minimalkan ALTER TABLE saat runtime

---

## Pertemuan 6 — Query Operator, Transformation & Optimization

### 1. Query Operator

Operator dasar SQL untuk mengambil dan memfilter data:

```sql
SELECT employee_id, name, salary   -- Pilih kolom
FROM employees                     -- Sumber tabel
WHERE salary > 5000                -- Filter kondisi
ORDER BY salary DESC;              -- Urutkan hasil
```

| Operator | Fungsi |
|----------|--------|
| `SELECT` | Memilih kolom yang ditampilkan |
| `FROM` | Menentukan sumber tabel |
| `WHERE` | Memfilter baris berdasarkan kondisi |
| `GROUP BY` | Mengelompokkan data |
| `ORDER BY` | Mengurutkan hasil |
| `HAVING` | Filter setelah GROUP BY |
| `JOIN` | Menggabungkan data dari beberapa tabel |

### 2. Transformation

Mengubah struktur/bentuk data agar lebih berguna:

**Aggregasi:**
```sql
SELECT department_id, AVG(salary) AS avg_salary
FROM employees
GROUP BY department_id;
```

**Penggabungan tabel (JOIN sebagai transformasi):**
```sql
SELECT e.name, d.department_name
FROM employees e
JOIN departments d ON e.department_id = d.department_id;
```

### 3. Optimization

Teknik mempercepat eksekusi query:

#### Index
```sql
-- Buat index untuk kolom yang sering difilter
CREATE INDEX idx_salary ON employees (salary);

-- Query otomatis memanfaatkan index
SELECT employee_id, name, salary
FROM employees
WHERE salary > 5000;
```

#### LIMIT / Pagination
```sql
-- Ambil hanya 10 baris teratas
SELECT employee_id, name, salary
FROM employees
ORDER BY salary DESC
LIMIT 10;
```

### Checklist Optimasi Query

- [ ] Index sudah dibuat pada kolom WHERE, JOIN, dan ORDER BY
- [ ] SELECT spesifik (hindari `SELECT *` di production)
- [ ] LIMIT digunakan untuk query bervolume besar
- [ ] Subquery diganti dengan JOIN bila memungkinkan
- [ ] Explain plan diperiksa untuk query kritis

---

## Pertemuan 7 — Database Security

### Komponen Utama Database Security

```
Database Security
├── Integrity          → data tetap valid & konsisten
├── Security           → proteksi dari akses ilegal
├── Control            → mekanisme pengawasan & pengendalian
├── PC Security        → keamanan sisi client
├── DBMS & Web Security→ keamanan jaringan & aplikasi
└── Risk Analysis      → identifikasi & mitigasi risiko
```

### Jenis Integritas Data

| Jenis | Mekanisme | Contoh |
|-------|-----------|--------|
| **Entity Integrity** | PRIMARY KEY NOT NULL | Setiap mahasiswa punya NIM unik |
| **Referential Integrity** | FOREIGN KEY constraint | kode_mk di KRS harus ada di tabel Matkul |
| **Domain Integrity** | Tipe data, CHECK constraint | IPK hanya 0.00–4.00 |
| **Enterprise Integrity** | Business rules / trigger | Maks SKS per semester tidak boleh dilewati |

### Ancaman Keamanan Database

| Ancaman | Deskripsi |
|---------|-----------|
| Theft & Fraud | Pencurian/manipulasi data untuk keuntungan ilegal |
| Loss of Confidentiality | Data rahasia diakses pihak tidak berwenang |
| Loss of Privacy | Data pribadi user bocor/disalahgunakan |
| Loss of Integrity | Data diubah/dirusak sehingga tidak akurat |
| Loss of Availability | Sistem tidak bisa diakses (DoS, hardware failure) |

### Pengendalian Keamanan Berbasis Komputer

#### Authentication & Authorization

```
Authentication  →  Verifikasi IDENTITAS (siapa kamu?)
                   Metode: password, token, biometric

Authorization   →  Verifikasi HAK AKSES (boleh apa kamu?)
                   Granulasi: CREATE | READ | UPDATE | DELETE
```

**Contoh pengaturan hak akses:**

| Role | CREATE | READ | UPDATE | DELETE |
|------|--------|------|--------|--------|
| Admin | ✓ | ✓ | ✓ | ✓ |
| Staff | ✗ | ✓ | ✓ | ✗ |
| Guest | ✗ | ✓ | ✗ | ✗ |

#### Mekanisme Lainnya

| Mekanisme | Fungsi |
|-----------|--------|
| Views/Subschema | Batasi data yang terlihat per user |
| Transaction Log | Rekam seluruh aktivitas transaksi |
| Violation Log | Rekam aktivitas mencurigakan/pelanggaran |
| Checkpoint | Titik simpan sementara untuk mempercepat recovery |
| Backup & Recovery | Salinan data cadangan + prosedur pemulihan |
| Audit | Evaluasi aktivitas sistem terhadap kebijakan keamanan |

### Enkripsi

#### Symmetric Encryption
```
Plaintext  ──[Key]──→  Ciphertext
Ciphertext ──[Key]──→  Plaintext (kunci SAMA)
```
- Cepat, cocok untuk data besar
- Risiko: jika kunci bocor, semua data terekspos
- Contoh: DES, AES

#### Asymmetric Encryption
```
Plaintext  ──[Public Key]──→  Ciphertext
Ciphertext ──[Private Key]─→  Plaintext (kunci BERBEDA)
```
- Lebih aman untuk pertukaran kunci
- Lebih lambat dari symmetric
- Contoh: RSA

### Keamanan DBMS & Web

| Teknologi | Fungsi |
|-----------|--------|
| Firewall | Filter lalu lintas jaringan berdasarkan rules |
| Proxy Server | Perantara antara client & server database |
| SSL/HTTPS | Enkripsi komunikasi data via internet |
| Digital Signature | Verifikasi keaslian & integritas data |
| Certificate Authority (CA) | Validasi identitas server/website |
| Kerberos | Autentikasi terpusat via ticket |
| SET/STT | Keamanan transaksi pembayaran elektronik |

### Risk Analysis Framework

```
1. Identifikasi Assets       → database, server, data pelanggan
2. Identifikasi Threats      → serangan, kebocoran, kegagalan sistem
3. Penilaian Risks           → probabilitas × dampak
4. Tentukan Countermeasures  → firewall, enkripsi, backup
5. Cost/Benefit Analysis     → biaya pengamanan vs potensi kerugian
6. Testing                   → uji efektivitas sistem keamanan
```

---

## Ringkasan Keterkaitan Antar Materi

```
P1: Konsep Dasar DB & DBMS
    ↓ (fondasi terminologi & arsitektur)
P2: Analisis Kebutuhan & Pemodelan
    ↓ (kebutuhan → rancangan)
P3: Normalisasi & Agregasi
    ↓ (struktur tabel optimal)
P4: Multiple Relation & JOIN
    ↓ (query lintas tabel)
P5: SQL Processing (Oracle)
    ↓ (mekanisme eksekusi di engine)
P6: Query Operator & Optimasi
    ↓ (query efisien & performant)
P7: Database Security
    ↓ (sistem aman & terlindungi)
```

> **Catatan penggunaan dokumen ini:** Sesuaikan setiap bagian dengan implementasi nyata sistem yang dibangun. Tambahkan screenshoot, DDL script, atau hasil query aktual di bawah masing-masing sub-bab sebagai bukti implementasi.

---

*Referensi: Silberschatz et al. (2019), Sciore (2020), Tahaghoghi & Williams (2007), Darwis & Pranoto (2024), Oracle SQL Tuning Guide 19c*