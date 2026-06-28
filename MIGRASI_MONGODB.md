
# Progres Migrasi MySQL → MongoDB

**Proyek:** edukasi-berhenti-merokok (Laravel 9)
**Tanggal:** 15 Juni 2026
**Status:** ✅ Selesai & terverifikasi end-to-end

Dokumen ini mencatat seluruh proses migrasi database dari MySQL ke MongoDB, beserta masalah yang ditemukan dan cara mengatasinya — supaya bisa diulang/dipahami di kemudian hari.

---

## 1. Ringkasan Keputusan

| Aspek | Pilihan |
|-------|---------|
| Server MongoDB | **Lokal** (`localhost:27017`, service Windows "MongoDB") |
| Cakupan | **Migrasi penuh** — seluruh 18 model |
| Data lama | **Fresh start** — re-seed, tidak memindahkan data MySQL |
| Database | `edukasi_berhenti_merokok` |

---

## 2. Stack & Versi (PENTING — saling terkunci)

| Komponen | Versi | Catatan |
|----------|-------|---------|
| PHP | 8.2.31 (NTS, x64, VS16) | |
| Laravel | 9 | |
| Package | `mongodb/laravel-mongodb` **^3.9** | versi yang mendukung Laravel 9 |
| Ekstensi PHP | `mongodb` **1.21.0** | **wajib 1.x** untuk package 3.9 |
| Namespace | `Jenssegers\Mongodb\...` | v3.9 masih pakai namespace lama |

> ⚠️ **Jangan upgrade ekstensi `mongodb` ke 2.x** tanpa upgrade Laravel ke 10+ **dan** package ke 4+. Ketiganya saling terkunci.

---

## 3. Langkah yang Dikerjakan

### 3.1 Server MongoDB
- MongoDB Community Server sudah ter-install, service jalan.
- Diverifikasi listening di `127.0.0.1:27017`.

### 3.2 Ekstensi PHP `mongodb`
- Awalnya terpasang `php_mongodb.dll` v2.3.3 → **tidak kompatibel** dengan package 3.9.
- Diturunkan ke **v1.21.0** (build `8.2-nts-vs16-x64` dari downloads.php.net).
- Ditaruh di folder `ext`, diaktifkan `extension=mongodb` di `php.ini`.

### 3.3 Package Laravel
```bash
composer require "mongodb/laravel-mongodb:^3.9"
```

### 3.4 Konfigurasi koneksi
**`config/database.php`** — tambah koneksi:
```php
'mongodb' => [
    'driver'   => 'mongodb',
    'dsn'      => env('MONGODB_URI', 'mongodb://127.0.0.1:27017'),
    'database' => env('DB_DATABASE', 'edukasi_berhenti_merokok'),
],
```

**`.env`**:
```env
DB_CONNECTION=mongodb
MONGODB_URI=mongodb://127.0.0.1:27017
DB_DATABASE=edukasi_berhenti_merokok
```

### 3.5 Konversi 18 Model
- Semua model: `Illuminate\Database\Eloquent\Model` → `Jenssegers\Mongodb\Eloquent\Model`.
- `User`: `Illuminate\Foundation\Auth\User` → `Jenssegers\Mongodb\Auth\User`.
- Ditambahkan `$casts` (`datetime`/`date`/`boolean`) untuk field tanggal & boolean
  (Content, Professional, Appointment, Payment, Notification, DailyCheckIn, ProgressTracker).

### 3.6 Migration
- Method MySQL (`foreignId()->constrained()`, `enum()`, `id()`) otomatis jadi **no-op** di jenssegers — migration `create_*` tetap aman dijalankan (cuma membuat collection).
- Migration berisi **raw SQL** (`DB::statement('ALTER TABLE ...')`) dikosongkan (tidak relevan untuk MongoDB yang schemaless):
  - `2026_05_08_090937_fix_schema_column_mismatches`
  - `2026_05_11_061500_add_defaults_to_daily_check_ins_required_totals`
- `create_users_table`: dihapus `disableForeignKeyConstraints()` (akan error di MongoDB), disisakan unique index `email`.
- Index performa (`add_performance_indexes`) tetap jalan (jenssegers mendukung `->index()`).

### 3.7 Seeder
- Tetap pakai `DB::table()` tapi:
  - Nilai tanggal dibungkus `new MongoDB\BSON\UTCDateTime(...)` (lihat masalah #3).
  - Hasil `insertGetId()` di-cast ke `(string)` (lihat masalah #2).
  - Field `progress_trackers` diselaraskan ke nama Inggris (`streak_days`, `cigarettes_avoided`, `money_saved`, `quit_date`, dll.) agar tampil di view.

### 3.8 Query yang ditulis ulang
- `withCount('forumReplies')` **tidak didukung** jenssegers → diganti hitung via relasi di 3 controller:
  - `ForumController`, `Admin\ForumController`, `User\DashboardController`.
- `HomeController`: `DB::table('progress_trackers')->first()` (mengembalikan array) → diganti `ProgressTracker::first()` (mengembalikan objek).
- `whereHas` / `has` lintas-koleksi **DIDUKUNG** jenssegers → controller lain tidak diubah.

---

## 4. Masalah yang Ditemukan & Solusinya

### #1 — `php artisan serve` tidak load ekstensi mongodb
**Gejala:** `Class "MongoDB\Driver\Manager" not found` saat akses web, padahal CLI & `php -S` normal.
**Sebab:** worker dev-server `artisan serve` menyaring environment; ekstensi gagal load.
**Solusi:** set `variables_order = "EGPCS"` di `php.ini` (sebelumnya `"GPCS"`).

### #2 — Foreign key tidak cocok (ObjectId vs string)
**Gejala:** relasi `hasMany` (mis. `forum->forumReplies()`) mengembalikan 0 padahal data ada.
**Sebab:** `insertGetId()` mengembalikan objek `ObjectId`, sedangkan Eloquent menyimpan FK sebagai **string** (`$model->id`). Tipe campur → tidak match.
**Solusi:** cast hasil `insertGetId()` ke `(string)` di seeder.

### #3 — Tanggal tersimpan rusak (sub-dokumen)
**Gejala:** `preg_match(): Argument #2 must be string, array given` saat render view.
**Sebab:** jenssegers **query builder** (`DB::table()->insert()`) tidak mengonversi `Carbon` → disimpan sebagai sub-dokumen BSON, bukan tanggal.
**Solusi:** bungkus nilai tanggal dengan `new MongoDB\BSON\UTCDateTime(...)` di seeder + `$casts` `datetime` di model. (Eloquent `create()` menangani tanggal otomatis, jadi kode aplikasi aman.)

### #4 — `withCount` error
**Gejala:** `Illegal offset type`.
**Solusi:** hitung jumlah via relasi (`$forum->forumReplies()->count()`).

### #5 — Logout 419 (Page Expired)
**Bukan bug.** Token CSRF di tab browser basi setelah server di-restart/reseed. Solusi: hard refresh + login ulang. Konfigurasi session (`file`, `secure: null`, `same_site: lax`) sudah benar.

---

## 5. Hasil Verifikasi

Dijalankan `php artisan migrate:fresh --seed` lalu diuji via HTTP:

| Area | Status |
|------|--------|
| Halaman admin (contents, forums, orders, payments, professionals, users, appointments) | ✅ 200 |
| Halaman user (home, dashboard, contents, books, forums, consultations, my-appointments, my-books) | ✅ 200 |
| Halaman profesional (dashboard, appointments, schedule) | ✅ 200 |
| Detail (forum, content, book, consultation) | ✅ 200 |
| Tulis: buat thread forum | ✅ |
| Tulis: reply forum (+ `increment`) | ✅ |
| Tulis: daily check-in | ✅ |
| Tulis: pesan buku (Order + Payment) | ✅ |
| Relasi `belongsTo`/`hasMany` | ✅ |
| Cast tanggal (Carbon) | ✅ |

---

## 6. Akun Uji (setelah seed)

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@berhentimerokok.test` | `Admin12345!` |
| User | `budi@test.com` (dan rina/agus/dewi/hendra) | `Password123!` |
| Profesional | `siti.dr@test.com`, `ahmad.psi@test.com` | `Password123!` |

---

## 7. Cara Menjalankan

```bash
# pastikan service MongoDB jalan
php artisan migrate:fresh --seed
php artisan serve
# buka http://127.0.0.1:8000
```

---

## 8. Catatan untuk Pengembangan Selanjutnya

- Setiap **model baru wajib** extend `Jenssegers\Mongodb\Eloquent\Model` (koneksi default = mongodb).
- Field tanggal **harus** didaftarkan di `$casts` agar terbaca sebagai Carbon.
- Hindari `withCount` lintas-koleksi; pakai hitung via relasi.
- `whereHas`/`has` boleh dipakai.
- Saat seeding via `DB::table()`, bungkus tanggal dengan `UTCDateTime` dan cast FK ke string — atau lebih aman gunakan Eloquent `create()`.
