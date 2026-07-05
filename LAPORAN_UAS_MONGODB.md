# LAPORAN FINAL UAS — SISTEM BASIS DATA
## Migrasi Platform Edukasi Berhenti Merokok dari MySQL ke MongoDB

**Mata Kuliah:** Basis Data — Muhamad Darwis, S.Kom., M.Kom.
**Program Studi:** Teknik Informatika, Fakultas Ilmu Rekayasa
**Universitas Paramadina — Tahun Ajaran 2025/2026**

**Disusun Oleh:**
- Yoga Pratama (25020100114)
- Alfianus Fierik Feto (125103031)
- Farel Abrar Hilalby (125103081)
- Mutiara Alia Putri (125103091)

**Framework:** Laravel 9 · **Database Awal:** MySQL 8 (relasional) · **Database Akhir:** MongoDB Community 7 (dokumen/NoSQL)
**Driver:** `mongodb/laravel-mongodb` (Jenssegers) ^3.9 · **Tanggal Laporan:** 5 Juli 2026

---

## Daftar Isi

1. [Ringkasan Eksekutif](#1-ringkasan-eksekutif)
2. [Gambaran Umum Sistem](#2-gambaran-umum-sistem)
3. [Alasan Migrasi: Mengapa dari MySQL ke MongoDB?](#3-alasan-migrasi-mengapa-dari-mysql-ke-mongodb)
4. [Perbandingan Konsep: Relasional vs Dokumen](#4-perbandingan-konsep-relasional-vs-dokumen)
5. [ERD & Struktur Collection](#5-erd--struktur-collection)
6. [Langkah Migrasi — Step by Step](#6-langkah-migrasi--step-by-step)
7. [Masalah yang Ditemukan Saat Migrasi & Solusinya](#7-masalah-yang-ditemukan-saat-migrasi--solusinya)
8. [Pemetaan Materi Perkuliahan (Pertemuan 1–7) ke MongoDB](#8-pemetaan-materi-perkuliahan-pertemuan-17-ke-mongodb)
9. [Hasil Verifikasi & Pengujian](#9-hasil-verifikasi--pengujian)
10. [Kesimpulan & Pelajaran yang Diperoleh](#10-kesimpulan--pelajaran-yang-diperoleh)
11. [Lampiran](#11-lampiran)

---

## 1. Ringkasan Eksekutif

Proyek ini semula dibangun sebagai aplikasi web **Laravel + MySQL** — database relasional dengan 18 tabel, primary key `AUTO_INCREMENT`, dan foreign key ber-`constraint`. Untuk memenuhi kebutuhan UAS yang menuntut penggunaan **MongoDB** (basis data NoSQL berbasis dokumen), seluruh lapisan penyimpanan dimigrasikan.

Poin penting laporan ini:

- Migrasi bersifat **penuh** — 18 model, seluruh controller, seeder, dan konfigurasi.
- Strategi data: **fresh start** (re-seed), bukan memindahkan baris MySQL lama.
- Migrasi **bukan sekadar ganti koneksi**. Perbedaan paradigma relasional → dokumen memunculkan 6 masalah teknis + 5 bug spesifik-MongoDB saat QA, yang semuanya didokumentasikan dan diperbaiki.
- Hasil akhir **terverifikasi end-to-end**: 40 rute GET lintas 4 role → 200 OK, seluruh operasi tulis (register, booking, pembayaran, check-in, forum, approval) berfungsi.

---

## 2. Gambaran Umum Sistem

**Nama sistem:** Platform Edukasi Berhenti Merokok — aplikasi web membantu perokok berhenti melalui edukasi, konsultasi profesional, pelacakan progres, dan komunitas.

### 2.1 Aktor (Role)

| Role | Kemampuan Utama |
|------|-----------------|
| **User (Perokok)** | Register/login, check-in harian, pelacakan progres, baca konten & buku, forum, pesan buku, booking konsultasi |
| **Professional (Psikolog/Dokter)** | Kelola jadwal, konfirmasi janji temu, tulis konten edukasi |
| **Admin** | Verifikasi profesional, approve/reject konten, kelola user, pantau pembayaran & statistik |

### 2.2 Entitas (18 Collection di MongoDB)

`users`, `professionals`, `professional_verifications`, `schedules`, `appointments`, `consultations`, `payments`, `payment_histories`, `refund_policies`, `books`, `orders`, `contents`, `content_approvals`, `progress_trackers`, `daily_check_ins`, `notifications`, `forums`, `forum_replies`.

### 2.3 Fitur Utama

Autentikasi berbasis role · Check-in harian + streak · Kalkulasi "uang dihemat" · Konsultasi dengan janji temu + pembayaran + kebijakan refund · Toko buku (order + pembayaran) · Konten edukasi dengan alur approval admin · Forum diskusi (thread + reply) · Dashboard statistik per role.

---

## 3. Alasan Migrasi: Mengapa dari MySQL ke MongoDB?

Migrasi ini adalah latihan akademik untuk memahami perbedaan paradigma. Secara teknis, MongoDB menawarkan:

| Aspek | Manfaat pada Sistem Ini |
|-------|-------------------------|
| **Schema fleksibel** | Menambah field baru (mis. `cigarettes_per_pack`, `money_saved`) tanpa `ALTER TABLE` — cukup tambah di `$fillable` model |
| **Model dokumen** | Data 1 entitas (misal 1 progress tracker) tersimpan sebagai 1 dokumen JSON/BSON utuh — dekat dengan objek aplikasi |
| **Skalabilitas horizontal** | MongoDB dirancang untuk sharding; cocok bila jumlah user check-in harian tumbuh besar |
| **Tanpa migrasi schema formal** | Perubahan struktur tidak butuh DDL yang mengunci tabel |

Konsekuensinya: kita **kehilangan jaminan relasional bawaan** (foreign key constraint, JOIN native, `whereDate`), yang harus digantikan di lapisan aplikasi. Trade-off inilah inti pembelajaran laporan ini.

---

## 4. Perbandingan Konsep: Relasional vs Dokumen

| Konsep MySQL | Padanan MongoDB | Dampak ke Kode |
|--------------|-----------------|----------------|
| Tabel (`table`) | Collection | Nama collection = bentuk jamak snake_case model (sama) |
| Baris (`row`) | Dokumen (BSON) | — |
| Kolom (`column`) | Field | Schemaless — tidak perlu didefinisikan di muka |
| `id` INT AUTO_INCREMENT | `_id` **ObjectId** (heksadesimal 24-char) | Primary key berubah tipe → sumber beberapa bug (lihat #2) |
| `FOREIGN KEY ... constrained` | **Tidak ada** constraint | Referential integrity dijaga di lapisan aplikasi (Eloquent + validasi) |
| `JOIN` (native SQL) | `$lookup` / eager loading `with()` di Jenssegers | JOIN "logis" oleh ORM, bukan engine |
| `whereDate/whereMonth/whereYear` | **Tidak didukung** driver 3.9 | Diganti *range query* `whereBetween` (lihat B3) |
| `ENUM('a','b')` | String biasa + validasi aplikasi | Domain integrity pindah ke `$request->validate()` |
| DDL `ALTER TABLE` | No-op (schemaless) | Migration `create_*` cuma membuat collection kosong |

---

## 5. ERD & Struktur Collection

Meski MongoDB schemaless, sistem ini tetap dirancang **referenced** (bukan embedded) agar setara desain relasional awal. Setiap entitas = 1 collection, relasi dijaga lewat **field referensi bertipe string** yang menyimpan `_id` (ObjectId) dokumen lain.

> **Catatan penting:** di MongoDB tidak ada `FOREIGN KEY` engine. Semua garis relasi di ERD berikut adalah **referensi logis** yang di-*resolve* oleh Eloquent (`belongsTo`/`hasMany`/`hasOne`), bukan constraint yang dipaksakan database. FK disimpan sebagai **string** agar cocok saat matching (lihat masalah #2).

### 5.1 Entity Relationship Diagram

```mermaid
erDiagram
    users ||--o| professionals : "hasOne"
    users ||--o| progress_trackers : "hasOne"
    users ||--o{ daily_check_ins : "hasMany"
    users ||--o{ notifications : "hasMany"
    users ||--o{ appointments : "hasMany"
    users ||--o{ orders : "hasMany"
    users ||--o{ forums : "hasMany"
    users ||--o{ forum_replies : "hasMany"
    users ||--o{ contents : "uploader"
    users ||--o{ payments : "hasMany"
    users ||--o{ content_approvals : "admin"
    users ||--o{ professional_verifications : "admin"

    professionals ||--o{ schedules : "hasMany"
    professionals ||--o{ appointments : "hasMany"
    professionals ||--o{ professional_verifications : "hasMany"

    schedules ||--o{ appointments : "dipakai"

    appointments ||--o| consultations : "hasOne"
    appointments ||--o| payments : "hasOne"

    payments ||--o| orders : "hasOne"
    payments ||--o{ payment_histories : "hasMany"
    payments ||--o| refund_policies : "hasOne"

    books ||--o{ orders : "hasMany"

    contents ||--o{ content_approvals : "hasMany"

    forums ||--o{ forum_replies : "hasMany"
```

Versi ringkas alur inti (jika Mermaid tidak dirender):

```
users ─1:1─ professionals ─1:N─ schedules
  │              │
  │1:N           │1:N
  ▼              ▼
appointments ◄───┘ ─1:1─ consultations
  │ 1:1
  ▼
payments ─1:1─ orders ─N:1─ books
  │ 1:N          
  ├─► payment_histories
  └─1:1─ refund_policies

users ─1:1─ progress_trackers ─1:N─ daily_check_ins
users ─1:N─ forums ─1:N─ forum_replies
users ─1:N─ contents ─1:N─ content_approvals
users ─1:N─ notifications
```

### 5.2 Kardinalitas Relasi

| Relasi | Jenis | Field Referensi |
|--------|-------|-----------------|
| users → professionals | 1:1 (opsional) | `professionals.user_id` |
| users → progress_trackers | 1:1 | `progress_trackers.user_id` |
| users → daily_check_ins | 1:N | `daily_check_ins.user_id` |
| users → appointments | 1:N | `appointments.user_id` |
| professionals → schedules | 1:N | `schedules.professional_id` |
| professionals → appointments | 1:N | `appointments.professional_id` |
| professionals → professional_verifications | 1:N | `professional_verifications.professional_id` |
| schedules → appointments | 1:N | `appointments.schedule_id` |
| appointments → consultations | 1:1 | `consultations.appointment_id` |
| appointments → payments | 1:1 | `payments.appointment_id` |
| payments → orders | 1:1 | `orders.payment_id` |
| payments → payment_histories | 1:N | `payment_histories.payment_id` |
| payments → refund_policies | 1:1 | `refund_policies.payment_id` |
| books → orders | 1:N | `orders.book_id` |
| contents → content_approvals | 1:N | `content_approvals.content_id` |
| forums → forum_replies | 1:N | `forum_replies.forum_id` |

### 5.3 Struktur Collection (18 Collection)

Tipe merujuk **tipe BSON** MongoDB. `_id` (ObjectId) adalah primary key otomatis tiap dokumen. Semua field `*_id` bertipe **String** (menyimpan ObjectId dokumen lain sebagai teks). Field `created_at`/`updated_at` (Date) ada di semua collection kecuali disebut lain.

#### `users`
| Field | Tipe BSON | Keterangan |
|-------|-----------|------------|
| `_id` | ObjectId | PK |
| `name` | String | Nama lengkap |
| `email` | String | **Unique index** |
| `password` | String | Hash bcrypt (60 char) |
| `birth_date` | Date | Tanggal lahir |
| `role` | String | `user` / `admin` / `professional` |
| `is_email_verified` | Bool | Status verifikasi email |

#### `professionals`
| Field | Tipe BSON | Keterangan |
|-------|-----------|------------|
| `_id` | ObjectId | PK |
| `user_id` | String | → `users._id` |
| `type` | String | `psikolog` / `dokter` |
| `specialization` | String | Spesialisasi |
| `license_number` | String | Nomor izin praktik |
| `document_url` | String | Bukti dokumen |
| `is_verified` | Bool | Diverifikasi admin |
| `verified_at` | Date | Waktu verifikasi |
| `hourly_rate` | Double | Tarif per jam |

#### `professional_verifications`
| Field | Tipe BSON | Keterangan |
|-------|-----------|------------|
| `_id` | ObjectId | PK |
| `professional_id` | String | → `professionals._id` |
| `admin_id` | String | → `users._id` (admin) |
| `status` | String | `approved` / `rejected` |
| `notes` | String | Catatan admin |
| `processed_at` | Date | Waktu diproses |

#### `schedules`
| Field | Tipe BSON | Keterangan |
|-------|-----------|------------|
| `_id` | ObjectId | PK |
| `professional_id` | String | → `professionals._id` |
| `day_of_week` | String | Hari |
| `start_time` | String | Jam mulai |
| `end_time` | String | Jam selesai |
| `mode` | String | `online` / `offline` |

#### `appointments`
| Field | Tipe BSON | Keterangan |
|-------|-----------|------------|
| `_id` | ObjectId | PK |
| `user_id` | String | → `users._id` |
| `professional_id` | String | → `professionals._id` |
| `schedule_id` | String | → `schedules._id` |
| `mode` | String | `online` / `offline` |
| `status` | String | `pending` / `confirmed` / `done` / `cancelled` |
| `appointment_date` | Date | Waktu janji temu |

#### `consultations`
| Field | Tipe BSON | Keterangan |
|-------|-----------|------------|
| `_id` | ObjectId | PK |
| `appointment_id` | String | → `appointments._id` |
| `notes` | String | Catatan konsultasi |
| `conclusion` | String | Kesimpulan |
| `recommendation` | String | Rekomendasi |
| `rating` | Double | Rating (0–5) |

#### `payments`
| Field | Tipe BSON | Keterangan |
|-------|-----------|------------|
| `_id` | ObjectId | PK |
| `appointment_id` | String | → `appointments._id` (nullable, utk konsultasi) |
| `user_id` | String | → `users._id` |
| `reference_id` | String | Kode referensi transaksi |
| `amount` | Double | Nominal |
| `duration_hours` | Int | Durasi (konsultasi) |
| `status` | String | `pending` / `success` / `failed` / `cancelled` |
| `refund_percentage` | Double | Persentase refund |
| `refund_amount` | Double | Nominal refund |
| `payment_method` | String | Metode bayar |
| `description` | String | Keterangan |
| `paid_at` | Date | Waktu bayar |
| `refunded_at` | Date | Waktu refund |

#### `payment_histories`
| Field | Tipe BSON | Keterangan |
|-------|-----------|------------|
| `_id` | ObjectId | PK |
| `payment_id` | String | → `payments._id` |
| `status` | String | `pending` / `success` / `failed` / `cancelled` |
| `response_data` | String | Payload respons gateway |
| `amount` | Double | Nominal |

#### `refund_policies`
| Field | Tipe BSON | Keterangan |
|-------|-----------|------------|
| `_id` | ObjectId | PK |
| `payment_id` | String | → `payments._id` |
| `status` | String | `requested` / `approved` / `rejected` / `processed` |
| `reason` | String | Alasan refund |
| `refund_amount` | Double | Nominal refund |
| `processed_at` | Date | Waktu diproses |

#### `books`
| Field | Tipe BSON | Keterangan |
|-------|-----------|------------|
| `_id` | ObjectId | PK |
| `title` | String | Judul |
| `author` | String | Penulis |
| `description` | String | Deskripsi |
| `price` | Double | Harga |
| `isbn` | String | ISBN (nullable) |
| `cover_url` | String | Sampul (nullable) |
| `stock` | Int | Stok |
| `is_available` | Bool | Tersedia |

#### `orders`
| Field | Tipe BSON | Keterangan |
|-------|-----------|------------|
| `_id` | ObjectId | PK |
| `user_id` | String | → `users._id` |
| `book_id` | String | → `books._id` |
| `quantity` | Int | Jumlah |
| `unit_price` | Double | Harga satuan |
| `total_price` | Double | Total |
| `status` | String | Status pesanan |
| `payment_id` | String | → `payments._id` |

#### `contents`
| Field | Tipe BSON | Keterangan |
|-------|-----------|------------|
| `_id` | ObjectId | PK |
| `uploader_id` | String | → `users._id` |
| `uploader_role` | String | Role pengunggah |
| `title` | String | Judul |
| `description` | String | Deskripsi (nullable) |
| `body` | String | Isi konten |
| `type` | String | `artikel` / `video` / dll |
| `approval_status` | String | `pending` / `approved` / `rejected` |
| `is_published` | Bool | Dipublikasikan |
| `published_at` | Date | Waktu publikasi |

#### `content_approvals`
| Field | Tipe BSON | Keterangan |
|-------|-----------|------------|
| `_id` | ObjectId | PK |
| `content_id` | String | → `contents._id` |
| `admin_id` | String | → `users._id` (admin) |
| `status` | String | `approved` / `rejected` |
| `notes` | String | Catatan |
| `processed_at` | Date | Waktu diproses |
| `approved_at` | Date | Waktu disetujui |

#### `progress_trackers`
| Field | Tipe BSON | Keterangan |
|-------|-----------|------------|
| `_id` | ObjectId | PK |
| `user_id` | String | → `users._id` |
| `quit_date` | Date | Tanggal mulai berhenti |
| `streak_days` | Int | Hari beruntun bebas rokok |
| `cigarettes_per_day` | Int | Rokok/hari sebelum berhenti |
| `price_per_pack` | Double | Harga per bungkus |
| `cigarettes_per_pack` | Int | Batang per bungkus |
| `cigarettes_avoided` | Int | Total rokok dihindari |
| `money_saved` | Double | Total uang dihemat |
| `last_check_in` | Date | Check-in terakhir |

#### `daily_check_ins`
| Field | Tipe BSON | Keterangan |
|-------|-----------|------------|
| `_id` | ObjectId | PK |
| `user_id` | String | → `users._id` |
| `check_in_date` | Date | Tanggal check-in |
| `is_smoke_free` | Bool | Bebas rokok hari ini |
| `cigarettes_avoided` | Int | Rokok dihindari hari itu |
| `money_saved` | Double | Uang dihemat hari itu |
| `notification_id` | String | → `notifications._id` (nullable) |

#### `notifications`
| Field | Tipe BSON | Keterangan |
|-------|-----------|------------|
| `_id` | ObjectId | PK |
| `user_id` | String | → `users._id` |
| `title` | String | Judul |
| `message` | String | Isi pesan |
| `type` | String | Jenis notifikasi |
| `is_read` | Bool | Sudah dibaca |
| `sent_at` | Date | Waktu kirim |

#### `forums`
| Field | Tipe BSON | Keterangan |
|-------|-----------|------------|
| `_id` | ObjectId | PK |
| `user_id` | String | → `users._id` |
| `title` | String | Judul thread |
| `body` | String | Isi |
| `content` | String | Isi (alias) |
| `category` | String | Kategori |
| `views` | Int | Jumlah dilihat |
| `views_count` | Int | Counter dilihat |
| `replies_count` | Int | Counter balasan |

#### `forum_replies`
| Field | Tipe BSON | Keterangan |
|-------|-----------|------------|
| `_id` | ObjectId | PK |
| `forum_id` | String | → `forums._id` |
| `user_id` | String | → `users._id` |
| `body` | String | Isi balasan |
| `content` | String | Isi (alias) |
| `likes_count` | Int | Jumlah suka |

### 5.4 Contoh Dokumen Nyata (BSON)

Satu dokumen `daily_check_ins` seperti tersimpan di MongoDB:

```json
{
  "_id": ObjectId("66a1f2e3c9b4a10f8d2e7b41"),
  "user_id": "66a1f2e0c9b4a10f8d2e7b02",
  "check_in_date": ISODate("2026-07-05T00:00:00Z"),
  "is_smoke_free": true,
  "cigarettes_avoided": 12,
  "money_saved": 18000,
  "created_at": ISODate("2026-07-05T08:14:22Z"),
  "updated_at": ISODate("2026-07-05T08:14:22Z")
}
```

Perhatikan `user_id` bertipe **String** (bukan ObjectId) — inilah yang harus konsisten agar relasi `hasMany` resolve (masalah #2). `check_in_date` bertipe **ISODate** hasil `$casts` `date` + `UTCDateTime` di seeder (masalah #3).

---

## 6. Langkah Migrasi — Step by Step

Berikut urutan kronologis persis yang dikerjakan.

### Langkah 0 — Kondisi Awal

Aplikasi berjalan pada Laravel + MySQL. Semua model extend `Illuminate\Database\Eloquent\Model`, koneksi default `mysql`, primary key `id` integer.

### Langkah 1 — Menyiapkan Server MongoDB

MongoDB Community Server dipasang via Homebrew, dijalankan sebagai service:

```bash
brew install mongodb-community
brew services start mongodb-community
# verifikasi listening di 127.0.0.1:27017
brew services list | grep mongo   # → started
```

### Langkah 2 — Menyamakan Versi PHP + Ekstensi (langkah paling kritis)

**Masalah inti:** mesin memakai PHP 8.4 default dengan `ext-mongodb 2.x`, sedangkan Laravel 9 + `mongodb/laravel-mongodb 3.9` mengharuskan **PHP ≤ 8.3** dan **`ext-mongodb 1.x`**. Kombinasi salah = fatal.

Solusi: gunakan `php@8.2` khusus + kompilasi ekstensi 1.x:

```bash
brew install php@8.2
/opt/homebrew/opt/php@8.2/bin/pecl install mongodb-1.21.1
# diaktifkan otomatis (extension=mongodb) di php.ini milik php@8.2
/opt/homebrew/opt/php@8.2/bin/php -m | grep mongodb   # → mongodb
```

> Versi saling terkunci: **PHP 8.2 · Laravel 9 · package 3.9 · ext-mongodb 1.21.1 · namespace `Jenssegers\Mongodb\...`**. Menjalankan dengan PHP 8.4 memicu error *"Expected integer or object, string given"* pada setiap penyimpanan data bertimestamp.

### Langkah 3 — Memasang Package Laravel-MongoDB

```bash
# dijalankan via php@8.2 agar platform-check ext-mongodb lolos
/opt/homebrew/opt/php@8.2/bin/php $(which composer) require mongodb/laravel-mongodb:^3.9
```

### Langkah 4 — Mengonfigurasi Koneksi

**`config/database.php`** — tambah koneksi `mongodb`:

```php
'mongodb' => [
    'driver'   => 'mongodb',
    'dsn'      => env('MONGODB_URI', 'mongodb://127.0.0.1:27017'),
    'database' => env('DB_DATABASE', 'edukasi_berhenti_merokok'),
],
```

**`.env`** — arahkan koneksi default ke MongoDB:

```env
DB_CONNECTION=mongodb
MONGODB_URI=mongodb://127.0.0.1:27017
DB_DATABASE=edukasi_berhenti_merokok
```

### Langkah 5 — Mengonversi 18 Model

Perubahan mekanis pada setiap model:

```php
// SEBELUM (MySQL)
use Illuminate\Database\Eloquent\Model;
class Payment extends Model { ... }

// SESUDAH (MongoDB)
use Jenssegers\Mongodb\Eloquent\Model;
class Payment extends Model { ... }
```

Untuk `User` (butuh autentikasi):

```php
// SEBELUM
use Illuminate\Foundation\Auth\User as Authenticatable;
// SESUDAH
use Jenssegers\Mongodb\Auth\User as Authenticatable;
```

Selain itu, **setiap field tanggal & boolean wajib didaftarkan di `$casts`** agar dibaca sebagai `Carbon`/`bool` (MongoDB menyimpan tipe BSON, bukan string seperti MySQL):

```php
// app/Models/DailyCheckIn.php
protected $casts = [
    'check_in_date' => 'date',
    'is_smoke_free' => 'boolean',
];
```

### Langkah 6 — Menyesuaikan Migration

- Method khas MySQL (`$table->id()`, `foreignId()->constrained()`, `enum()`) otomatis menjadi **no-op** di Jenssegers → file `create_*` tetap aman dijalankan (hanya membuat collection).
- Migration berisi **raw SQL** (`DB::statement('ALTER TABLE ...')`) dikosongkan karena tidak relevan untuk MongoDB yang schemaless (mis. `fix_schema_column_mismatches`).
- `create_users_table` dibersihkan dari `disableForeignKeyConstraints()` (error di MongoDB); disisakan unique index `email`.
- Migration index performa tetap jalan — Jenssegers mendukung `$table->index()`.

```bash
./serve.sh artisan migrate
# → 26 migrasi DONE, 22 collection terbentuk
```

### Langkah 7 — Menyesuaikan Seeder

Seeder berbasis `DB::table()` butuh dua penyesuaian penting:

1. **Tanggal** dibungkus `new MongoDB\BSON\UTCDateTime(...)` (query builder tidak auto-convert `Carbon`).
2. **Foreign key** hasil `insertGetId()` di-*cast* ke `(string)` karena mengembalikan `ObjectId`.

```php
$userId = (string) DB::table('users')->insertGetId([
    'name'       => 'Budi',
    'created_at' => new MongoDB\BSON\UTCDateTime(now()),
]);
```

```bash
./serve.sh artisan migrate:fresh --seed
# → 9 user (1 admin, 5 user, 3 profesional) + konten, buku, forum, jadwal, dll.
```

### Langkah 8 — Menulis Ulang Query yang Tidak Kompatibel

- `withCount('forumReplies')` **tidak didukung** → diganti hitung via relasi `$forum->forumReplies()->count()` di 3 controller.
- `DB::table('progress_trackers')->first()` (kembalikan array) → `ProgressTracker::where(...)->first()` (kembalikan objek).
- `whereHas` / `has` lintas-collection **tetap didukung** → controller lain tak berubah.

### Langkah 9 — Membuat Wrapper Penjalan (`serve.sh`)

Agar seluruh perintah selalu memakai `php@8.2`:

```bash
./serve.sh                  # php@8.2 artisan serve
./serve.sh artisan <cmd>    # artisan apa pun via php@8.2
./serve.sh composer <cmd>   # composer via php@8.2
```

---

## 7. Masalah yang Ditemukan Saat Migrasi & Solusinya

### Kelompok A — Masalah Setup/Migrasi

| # | Gejala | Sebab | Solusi |
|---|--------|-------|--------|
| **1** | `composer install` gagal; ext bentrok | PHP 8.4 default + ext-mongodb 2.x tak kompatibel package 3.9 | Pakai `php@8.2` + ext-mongodb 1.21.1 (pecl) |
| **2** | Relasi `hasMany` kembalikan 0 padahal data ada | `insertGetId()` kembalikan `ObjectId`, FK disimpan sebagai string → tipe tak match | Cast hasil `insertGetId()` ke `(string)` di seeder |
| **3** | `preg_match(): Argument #2 must be string, array given` saat render | Query builder tak konversi `Carbon` → tanggal tersimpan sebagai sub-dokumen BSON | Bungkus `new MongoDB\BSON\UTCDateTime(...)` + `$casts` `datetime` |
| **4** | `withCount` → `Illegal offset type` | Tidak didukung Jenssegers | Hitung via relasi `->count()` |
| **5** | `pecl install` gagal di langkah akhir | Folder target pecl belum ada | `mkdir -p` folder target, lalu `pecl install --force` |
| **6** | Warning OPcache dimuat dua kali | Konfigurasi lama `php@8.2` | Nonaktifkan baris `zend_extension=opcache` bare (non-fatal) |

### Kelompok B — Bug Spesifik-MongoDB Ditemukan Saat QA

| # | Lokasi | Sebab | Solusi & Verifikasi |
|---|--------|-------|---------------------|
| **B1** | `ConsultationController@book` — `exists:schedules,id` | Laravel cari kolom literal `id`, MongoDB pakai `_id` | Ubah ke `exists:schedules,_id`. ✅ Appointment + payment terbuat |
| **B2** | `ContentApproval` — `MassAssignmentException` | Model tanpa `$fillable` | Tambah `$fillable` + `$casts` + relasi. ✅ Approve/reject jalan |
| **B3** | `whereDate/whereMonth/whereYear` di banyak controller | Driver 3.9 tak dukung → query selalu kosong | Ganti *range query* `whereBetween(startOfDay, endOfDay)` + scope `DailyCheckIn::scopeOnDate()`. ✅ Streak +1 benar, check-in kedua diblokir, revenue bulanan benar |
| **B4** | `ProgressController@store` — "Uang Dihemat" selalu Rp0 | Field `price_per_pack`/`cigarettes_per_pack` divalidasi tapi tak disimpan; `money_saved` hard-code 0 | Simpan kedua field + helper `calculateMoneySaved()`. ✅ 12 rokok/hari @ Rp30.000/20 batang → Rp18.000 |
| **B5** | Error validasi tak tampil di UI | Layout hanya tampilkan flash `success`/`error` | Tambah blok daftar `$errors` global di `layouts/app.blade.php` |

> **B3 adalah temuan terpenting.** `whereDate` yang diam-diam mengembalikan kosong membuat deteksi "sudah check-in hari ini" gagal → streak bisa dobel-hitung (fitur inti tracker), dan seluruh statistik bulanan dashboard jadi 0. Ini pelajaran bahwa **migrasi database bukan penggantian transparan** — operator yang di MySQL bekerja bisa gagal-diam di MongoDB.

**Contoh perbaikan B3** — scope range query pengganti `whereDate`:

```php
// app/Models/DailyCheckIn.php
// Pakai range query karena whereDate() tidak didukung MongoDB (Jenssegers).
public function scopeOnDate($query, $date)
{
    $date = Carbon::parse($date);
    return $query
        ->where('check_in_date', '>=', $date->copy()->startOfDay())
        ->where('check_in_date', '<',  $date->copy()->addDay()->startOfDay());
}
```

---

## 8. Pemetaan Materi Perkuliahan (Pertemuan 1–7) ke MongoDB

Bagian ini menunjukkan bahwa konsep basis data P1–P7 tetap berlaku, dengan penyesuaian paradigma dokumen.

### P1 — Basis Data & DBMS

Sistem tetap **pendekatan database client/server**, kini dengan DBMS **MongoDB**:

```
Browser (Client) → Laravel (App) → Jenssegers ODM → MongoDB Server (127.0.0.1:27017) → Storage (WiredTiger)
```

| Komponen DBMS | Implementasi |
|---------------|--------------|
| Database | MongoDB: `edukasi_berhenti_merokok` — 18 collection |
| DBMS | MongoDB Community + Jenssegers ODM (DML), driver `ext-mongodb` |
| Primary Key | `_id` **ObjectId** (menggantikan INT AUTO_INCREMENT) |
| Foreign Key | Field referensi string (`user_id`) — **tanpa constraint engine** |

### P2 — Kebutuhan Pengguna & Pemodelan

Kebutuhan tak berubah (fungsional: booking, check-in, forum; non-fungsional: hashing, RBAC, pagination). Yang berubah: **Model Internal** kini WiredTiger + B-tree index MongoDB, bukan InnoDB `.ibd`.

### P3 — Normalisasi & Fungsi Agregat

**Catatan penting paradigma:** MongoDB *bisa* denormalisasi (embed), tapi proyek ini **tetap referenced/normalized** (collection terpisah + field FK) agar setara desain relasional awal. `professionals` tetap terpisah dari `users`, `refund_policies` tetap terpisah dari `payments` (hindari transitif) — 3NF secara logis dipertahankan meski engine tak memaksakan.

Fungsi agregat via Eloquent tetap bekerja:

```php
User::where('role', 'user')->count();               // COUNT()
Payment::where('status', 'success')->sum('amount');  // SUM()
```

Di balik layar Jenssegers menerjemahkannya ke **aggregation pipeline** MongoDB (`$match` + `$group`), bukan SQL `GROUP BY`.

### P4 — Multiple Relation & "JOIN"

Relasi 1:1, 1:N tetap dideklarasikan via Eloquent (`hasOne`, `hasMany`, `belongsTo`). Eager loading `with()` menghasilkan query terpisah + penggabungan di aplikasi (mirip `$lookup`), bukan JOIN engine.

```php
Payment::with(['user', 'order.book', 'appointment.professional.user'])
    ->latest()->paginate(20);   // relasi berantai 3 tingkat
```

> **Perbedaan kunci:** di MySQL, `belongsTo` bekerja karena FK integer cocok. Di MongoDB, Jenssegers mengonversi string `_id` → `ObjectId` saat resolve `belongsTo`, tetapi `hasMany` sensitif tipe — inilah kenapa FK harus disimpan konsisten sebagai string (bug #2).

### P5 — SQL Processing → Query Processing

Konsep *prepared statement* / *bind variable* MySQL tak langsung berlaku, tetapi MongoDB punya analogi: **plan cache** dan **parameterized query object** (BSON) — nilai dikirim sebagai objek BSON, tak diinterpolasi ke string, sehingga **imun SQL injection secara alami**. DDL (`migrate`) tetap dijalankan offline, bukan saat request.

### P6 — Query Operator & Optimization

Operator dasar tetap ada via Eloquent (`where`, `orderBy`/`latest`, `take`/`paginate`). Optimasi index tetap relevan — **MongoDB memakai B-tree index** sama seperti MySQL:

```php
// migration add_performance_indexes — tetap jalan di MongoDB
$table->index('status', 'idx_payments_status');
$table->index('check_in_date', 'idx_daily_check_ins_date');  // penting utk streak
```

Tanpa index, query filter = **collection scan O(n)**; dengan index = **index scan O(log n)**. Pagination `paginate(20)` mencegah memuat seluruh collection.

### P7 — Database Security

| Mekanisme | Status di MongoDB |
|-----------|-------------------|
| **Authentication** | bcrypt password hashing (tak berubah) + `Jenssegers\Mongodb\Auth\User` |
| **Authorization** | `RoleMiddleware` RBAC (tak berubah, lapisan aplikasi) |
| **Entity Integrity** | `_id` ObjectId unik otomatis + unique index `email` |
| **Referential Integrity** | **Pindah ke aplikasi** — validasi `exists:collection,_id` (bug B1) menggantikan FK constraint |
| **Domain Integrity** | Validasi `$request->validate()` menggantikan `ENUM`/`CHECK` |
| **Injection safety** | Query BSON parameterized — tak ada string SQL untuk diinjeksi |
| **CSRF / Session** | Middleware Laravel + `session()->regenerate()` (tak berubah) |

> **Pelajaran keamanan:** di MongoDB, **referential & domain integrity yang dulu dijamin engine kini menjadi tanggung jawab aplikasi**. Menghapus 1 baris validasi = kehilangan jaminan yang di MySQL otomatis.

---

## 9. Hasil Verifikasi & Pengujian

Dijalankan `./serve.sh artisan migrate:fresh --seed` lalu diuji via HTTP otomatis (login per-role + tembak setiap rute):

| Area | Status |
|------|--------|
| Semua halaman GET (4 role, 40 rute) | ✅ **40 OK, 0 gagal** |
| Admin (dashboard, users, contents, forums, orders, payments, professionals, appointments) | ✅ 200 |
| User (home, dashboard, progress, contents, books, forums, consultations, appointments, payments, notifications, profile) | ✅ 200 |
| Professional (dashboard, appointments, schedule, setup) | ✅ 200 |
| Tulis: register, check-in harian, thread+reply forum, update profil, submit konten | ✅ |
| Tulis: pesan buku (Order+Payment), booking konsultasi (Appointment+Payment) | ✅ |
| Tulis: bayar (status→success, `paid_at`), konfirmasi janji temu, tambah jadwal | ✅ |
| Admin: approve profesional, approve/reject konten | ✅ |
| Relasi `belongsTo`/`hasMany`, cast tanggal, streak harian, uang dihemat, statistik dashboard | ✅ |

### Akun Uji (setelah seed)

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@berhentimerokok.test` | `Admin12345!` |
| User | `budi@test.com` (rina/agus/dewi/hendra) | `Password123!` |
| Professional | `siti.dr@test.com`, `ahmad.psi@test.com` | `Password123!` |

---

## 10. Kesimpulan & Pelajaran yang Diperoleh

1. **Migrasi berhasil penuh** — 18 model, seluruh fitur berfungsi di MongoDB, terverifikasi 40/40 rute.
2. **Ganti database ≠ ganti koneksi.** Perbedaan paradigma relasional→dokumen memunculkan 11 masalah teknis yang harus diselesaikan di lapisan aplikasi.
3. **Bahaya terbesar = kegagalan diam-diam** (`whereDate` kosong, FK tipe tak cocok) — bug yang tidak melempar error tapi menghasilkan data salah (streak dobel, statistik 0).
4. **Integritas pindah ke aplikasi.** Foreign key, ENUM, dan constraint yang di MySQL dijamin engine, di MongoDB menjadi tanggung jawab validasi Laravel.
5. **Versi harus dikunci.** PHP 8.2 · Laravel 9 · package 3.9 · ext-mongodb 1.x — kombinasi salah = fatal.
6. **Konsep P1–P7 tetap valid** — normalisasi, agregasi, relasi, index, dan security semuanya diterapkan, hanya mekanismenya menyesuaikan model dokumen.

---

## 11. Lampiran

### 10.1 Cara Menjalankan

```bash
brew services start mongodb-community        # pastikan MongoDB jalan
./serve.sh artisan migrate:fresh --seed      # migrate + seed via php@8.2
./serve.sh                                    # jalankan → http://127.0.0.1:8000
```

> ⚠️ Jangan `php artisan serve` langsung — PHP default mesin 8.4 memicu error *"Expected integer or object, string given"*. Selalu pakai `./serve.sh`.

### 10.2 Aturan Wajib untuk Pengembangan Lanjutan (MongoDB)

- Jalankan **selalu** dengan `php@8.2` (`serve.sh`).
- Model baru **wajib** extend `Jenssegers\Mongodb\Eloquent\Model`.
- Field tanggal **harus** ada di `$casts` agar terbaca sebagai `Carbon`.
- **Hindari** `whereDate/whereMonth/whereYear/whereDay` → pakai `whereBetween` (range).
- Rule `exists:`/`unique:` gunakan **`_id`**, bukan `id`.
- Model mass-assignment **wajib** punya `$fillable`.
- Hindari `withCount` lintas-collection; pakai hitung via relasi. `whereHas`/`has` boleh.
- Seeding via `DB::table()`: bungkus tanggal `UTCDateTime`, cast FK ke string — atau lebih aman pakai Eloquent `create()`.

### 10.3 Stack & Versi (saling terkunci)

| Komponen | Versi |
|----------|-------|
| OS | macOS (Darwin, arm64) |
| PHP | **8.2.29** (`php@8.2`, wajib ≤ 8.3) |
| Laravel | 9 |
| Package | `mongodb/laravel-mongodb` ^3.9 (namespace `Jenssegers\Mongodb`) |
| Ekstensi | `ext-mongodb` 1.21.1 (wajib 1.x) |
| Server | MongoDB Community (Homebrew, service `mongodb-community`) |

### 10.4 Dokumen Terkait

- `MIGRASI_MONGODB.md` — catatan teknis rinci proses migrasi.
- `laporan.md` — laporan versi MySQL (referensi pembanding sebelum migrasi).
- `markdown.md` — laporan perancangan awal (ERD, entitas, kardinalitas).

---

*Laporan ini menunjukkan bahwa seluruh konsep basis data Pertemuan 1–7 telah diterapkan dan divalidasi ulang pada Platform Edukasi Berhenti Merokok setelah migrasi dari MySQL (relasional) ke MongoDB (dokumen/NoSQL) menggunakan Laravel 9 + driver Jenssegers.*
