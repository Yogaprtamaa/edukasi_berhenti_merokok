
# Progres Migrasi MySQL → MongoDB

**Proyek:** edukasi-berhenti-merokok (Laravel 9)
**Tanggal:** 28 Juni 2026
**Lingkungan:** macOS 15.7.3 (Apple Silicon) + Homebrew
**Status:** ✅ Selesai & terverifikasi end-to-end (QA semua halaman + fitur)

Dokumen ini mencatat seluruh proses migrasi database dari MySQL ke MongoDB, beserta masalah yang ditemukan dan cara mengatasinya — supaya bisa diulang/dipahami di kemudian hari.

---

## 1. Ringkasan Keputusan

| Aspek | Pilihan |
|-------|---------|
| Server MongoDB | **Lokal** (`127.0.0.1:27017`, Homebrew service `mongodb-community`) |
| Cakupan | **Migrasi penuh** — seluruh 18 model |
| Data lama | **Fresh start** — re-seed, tidak memindahkan data MySQL |
| Database | `edukasi_berhenti_merokok` |

---

## 2. Stack & Versi (PENTING — saling terkunci)

| Komponen | Versi | Catatan |
|----------|-------|---------|
| OS | macOS 15.7.3 (Darwin, arm64) | Homebrew |
| PHP | **8.2.29** (`php@8.2`, NTS) | **wajib ≤ 8.3** — Laravel 9 tidak mendukung PHP 8.4 |
| Laravel | 9 | |
| Package | `mongodb/laravel-mongodb` **^3.9** | versi yang mendukung Laravel 9 |
| Ekstensi PHP | `mongodb` **1.21.1** | **wajib 1.x** untuk package 3.9 |
| Namespace | `Jenssegers\Mongodb\...` | v3.9 masih pakai namespace lama |
| Server DB | MongoDB Community (Homebrew) | service `mongodb-community` |

> ⚠️ **Jangan jalankan dengan PHP default mesin (8.4 + ext-mongodb 2.3.3).** Kombinasi itu fatal: `mongodb/laravel-mongodb 3.9` memanggil `new UTCDateTime($value->format('Uv'))` (string), yang **ditolak ext-mongodb 2.x** dengan error *"Expected integer or object, string given"* pada setiap penyimpanan data bertimestamp. Laravel 9 + package 3.9 + ext 1.x saling terkunci — jalankan **selalu** dengan `php@8.2`.

---

## 3. Langkah yang Dikerjakan

### 3.1 Server MongoDB
- MongoDB Community Server terpasang via Homebrew, service `mongodb-community` berjalan
  (`brew services list | grep mongo` → *started*).
- Diverifikasi listening di `127.0.0.1:27017` (`mongosh` tersedia).

### 3.2 Ekstensi PHP `mongodb` (untuk `php@8.2`)
- PHP default mesin (8.4) memuat `ext-mongodb` **2.3.3** → **tidak kompatibel** dengan package 3.9.
- Untuk `php@8.2` dipasang **ext-mongodb 1.21.1** via pecl:
  ```bash
  /opt/homebrew/opt/php@8.2/bin/pecl install mongodb-1.21.1
  ```
- Diaktifkan otomatis (`extension=mongodb`) di `php.ini` milik `php@8.2`.
- Diverifikasi: `php@8.2 -m | grep mongodb` → muncul; `phpversion('mongodb')` → 1.21.1.

### 3.3 Package Laravel
```bash
# dijalankan dengan php@8.2 agar platform-check ext-mongodb lolos
/opt/homebrew/opt/php@8.2/bin/php $(which composer) install
```
`composer.json` sudah memuat `"mongodb/laravel-mongodb": "^3.9"`.

### 3.4 Konfigurasi koneksi
**`config/database.php`** — koneksi MongoDB:
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
  (Content, Professional, Appointment, Payment, Notification, DailyCheckIn, ProgressTracker, dll).

### 3.6 Migration
- Method MySQL (`foreignId()->constrained()`, `enum()`, `id()`) otomatis jadi **no-op** di jenssegers — migration `create_*` tetap aman dijalankan (cuma membuat collection).
- Migration berisi **raw SQL** (`DB::statement('ALTER TABLE ...')`) dikosongkan (tidak relevan untuk MongoDB yang schemaless), mis. `2026_05_08_090937_fix_schema_column_mismatches`.
- `create_users_table`: tanpa `disableForeignKeyConstraints()` (akan error di MongoDB), disisakan unique index `email`.
- Index performa (`add_performance_indexes`) tetap jalan (jenssegers mendukung `->index()`).
- `php artisan migrate` menghasilkan 26 migrasi DONE → 22 collection terbentuk.

### 3.7 Seeder
- Tetap pakai `DB::table()` tapi:
  - Nilai tanggal dibungkus `new MongoDB\BSON\UTCDateTime(...)` (lihat masalah #3).
  - Hasil `insertGetId()` di-cast ke `(string)` (lihat masalah #2).
  - Field `progress_trackers` diselaraskan ke nama Inggris (`streak_days`, `cigarettes_avoided`, `money_saved`, `quit_date`, dll.) agar tampil di view.
- Seeder: `AdminUserSeeder` + `DummyDataSeeder` → 9 user (1 admin, 5 user, 3 profesional) + konten, buku, forum, jadwal, janji temu, dll.

### 3.8 Query yang ditulis ulang
- `withCount('forumReplies')` **tidak didukung** jenssegers → diganti hitung via relasi di 3 controller:
  `ForumController`, `Admin\ForumController`, `User\DashboardController`.
- `HomeController`: `DB::table('progress_trackers')->first()` (mengembalikan array) → diganti `ProgressTracker::where('user_id', ...)->first()` (mengembalikan objek).
- `whereHas` / `has` lintas-koleksi **DIDUKUNG** jenssegers → controller lain tidak diubah.

### 3.9 Script penjalan (`serve.sh`)
Ditambahkan wrapper agar aplikasi selalu jalan dengan `php@8.2`:
```bash
./serve.sh                  # php@8.2 artisan serve
./serve.sh artisan <cmd>    # artisan apa pun via php@8.2
./serve.sh composer <cmd>   # composer via php@8.2
```

---

## 4. Masalah Migrasi & Solusinya

### #1 — PHP default (8.4) tidak kompatibel
**Gejala:** `composer install` gagal (`nette/schema` butuh PHP ≤ 8.3) dan `ext-mongodb 2.3.3` bentrok dengan `mongodb/mongodb 1.21`.
**Sebab:** mesin memakai PHP 8.4 sebagai default; Laravel 9 + package 3.9 menuntut PHP ≤ 8.3 dan ext-mongodb 1.x.
**Solusi:** gunakan `php@8.2` (`brew install php@8.2`) + ext-mongodb **1.21.1** via pecl. Semua perintah dijalankan melalui `php@8.2` (lihat `serve.sh`).

### #2 — Foreign key tidak cocok (ObjectId vs string)
**Gejala:** relasi `hasMany` (mis. `forum->forumReplies()`) mengembalikan 0 padahal data ada.
**Sebab:** `insertGetId()` mengembalikan objek `ObjectId`, sedangkan Eloquent menyimpan FK sebagai **string** (`$model->id`). Tipe campur → tidak match.
**Solusi:** cast hasil `insertGetId()` ke `(string)` di seeder. (Catatan: untuk relasi `belongsTo`, jenssegers tetap mengonversi string `_id` → ObjectId, jadi `belongsTo` tetap resolve.)

### #3 — Tanggal tersimpan rusak (sub-dokumen)
**Gejala:** `preg_match(): Argument #2 must be string, array given` saat render view.
**Sebab:** jenssegers **query builder** (`DB::table()->insert()`) tidak mengonversi `Carbon` → disimpan sebagai sub-dokumen BSON, bukan tanggal.
**Solusi:** bungkus nilai tanggal dengan `new MongoDB\BSON\UTCDateTime(...)` di seeder + `$casts` `datetime` di model. (Eloquent `create()` menangani tanggal otomatis, jadi kode aplikasi aman.)

### #4 — `withCount` error
**Gejala:** `Illegal offset type`.
**Solusi:** hitung jumlah via relasi (`$forum->forumReplies()->count()`).

### #5 — `pecl install` gagal di langkah akhir
**Gejala:** ekstensi ter-*compile* tapi `ERROR: failed to mkdir .../pecl/20240924`.
**Sebab:** folder target pecl belum ada.
**Solusi:** buat folder target lebih dulu (`mkdir -p`), lalu `pecl install --force` ulang.

### #6 — Warning OPcache di `php@8.2`
**Bukan bug aplikasi.** Konfigurasi lama memuat OPcache dua kali. Dirapikan dengan menonaktifkan baris `zend_extension=opcache` bare di `php.ini` (OPcache tetap dimuat lewat `conf.d/ext-opcache.ini`). Non-fatal.

---

## 5. Temuan & Perbaikan saat QA / Testing

Setelah migrasi, dilakukan pengujian menyeluruh (semua halaman per-role + fitur tulis). Berikut bug spesifik MongoDB yang ditemukan dan diperbaiki:

### B1 — Validasi `exists:table,id` gagal di MongoDB
**Lokasi:** `ConsultationController@book` (`exists:schedules,id`).
**Sebab:** verifier Laravel mencari kolom literal `id`, sedangkan MongoDB memakai `_id`.
**Dampak:** booking konsultasi **gagal diam-diam** (redirect-back, tanpa membuat appointment/payment).
**Solusi:** ubah ke `exists:schedules,_id`. ✅ Terverifikasi: appointment + payment terbuat.

### B2 — Model mass-assignment tanpa `$fillable`
**Lokasi:** `ContentApproval` (dipakai `Admin\ContentController@approve/@reject`).
**Sebab:** model tanpa `$fillable` → `MassAssignmentException`.
**Solusi:** tambah `$fillable` + `$casts` + relasi. ✅ Approve/reject konten berfungsi.

### B3 — `whereDate` / `whereMonth` / `whereYear` tidak jalan di MongoDB
**Sebab:** jenssegers 3.9 tidak mendukung keluarga `whereDate` — query selalu kosong.
**Dampak:**
- Deteksi "sudah check-in hari ini" rusak → **streak harian** bisa dobel-hitung (titik utama fitur tracker).
- Statistik dashboard Admin & Profesional salah (pendapatan bulanan, user baru, janji temu hari ini → 0).
**Solusi:** ganti ke **range query** (`whereBetween` dengan `startOfDay/endOfDay`, `startOfMonth/endOfMonth`). Ditambah scope `DailyCheckIn::scopeOnDate()`.
✅ Terverifikasi: streak +1 benar, check-in kedua diblokir, revenue bulan ini Rp0 → benar.

### B4 — Fitur "Uang Dihemat" selalu Rp0
**Sebab:** `ProgressController@store` memvalidasi `price_per_pack`/`cigarettes_per_pack` tapi tidak menyimpannya; `money_saved` di-hardcode 0.
**Solusi:** `ProgressTracker` menyimpan kedua field + helper `calculateMoneySaved()`; `CheckInController` mengakumulasi `cigarettes_avoided` dan menghitung `money_saved` tiap check-in bebas rokok.
✅ Terverifikasi: 12 rokok/hari @ Rp30.000/20 batang → Rp18.000.

### B5 — UX: error validasi tidak tampil
**Sebab:** layout aplikasi hanya menampilkan flash `success`/`error`, bukan `$errors`.
**Solusi:** tambah blok daftar `$errors` global di `layouts/app.blade.php`; form update tracker kini pre-fill dari data tersimpan & `quit_date` diformat `Y-m-d`.

---

## 6. Hasil Verifikasi

Dijalankan `./serve.sh artisan migrate:fresh --seed` lalu diuji via HTTP otomatis (login per-role + tembak setiap rute):

| Area | Status |
|------|--------|
| **Semua halaman GET (4 role, 40 rute)** | ✅ **40 OK, 0 gagal** |
| Halaman admin (dashboard, users, contents, forums, orders, payments, professionals, appointments + edit/detail) | ✅ 200 |
| Halaman user (home, dashboard, progress, contents, books, forums, consultations, my-appointments, my-books, payments, notifications, profile) | ✅ 200 |
| Halaman profesional (dashboard, appointments, schedule, setup) | ✅ 200 |
| Tulis: register, daily check-in, buat thread + reply forum, update profil, submit konten | ✅ |
| Tulis: pesan buku (Order + Payment), booking konsultasi (Appointment + Payment) | ✅ |
| Tulis: bayar payment (status→success, `paid_at`), profesional konfirmasi janji temu, tambah jadwal | ✅ |
| Admin: approve profesional, approve/reject konten, update status | ✅ |
| Relasi `belongsTo`/`hasMany`, cast tanggal (Carbon), streak harian, uang dihemat, statistik dashboard | ✅ |

---

## 7. Akun Uji (setelah seed)

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@berhentimerokok.test` | `Admin12345!` |
| User | `budi@test.com` (dan rina/agus/dewi/hendra) | `Password123!` |
| Profesional | `siti.dr@test.com`, `ahmad.psi@test.com` | `Password123!` |

---

## 8. Cara Menjalankan

```bash
# pastikan service MongoDB jalan
brew services start mongodb-community

# semua perintah lewat wrapper php@8.2
./serve.sh artisan migrate:fresh --seed
./serve.sh
# buka http://127.0.0.1:8000
```

> ⚠️ Jangan pakai `php artisan serve` langsung — `php` default mesin ini versi 8.4 dan akan memunculkan error *"Expected integer or object, string given"*. Gunakan `./serve.sh` (otomatis `php@8.2`).

---

## 9. Catatan untuk Pengembangan Selanjutnya

- Jalankan **selalu** dengan `php@8.2` (lihat `serve.sh`).
- Setiap **model baru wajib** extend `Jenssegers\Mongodb\Eloquent\Model` (koneksi default = mongodb).
- Field tanggal **harus** didaftarkan di `$casts` agar terbaca sebagai Carbon.
- **Hindari** `whereDate`/`whereMonth`/`whereYear`/`whereDay` → pakai `whereBetween` (range tanggal).
- Pada rule `exists:`/`unique:`, gunakan **`_id`**, bukan `id`.
- Model yang dipakai mass-assignment **wajib** punya `$fillable`.
- Hindari `withCount` lintas-koleksi; pakai hitung via relasi. `whereHas`/`has` boleh dipakai.
- Saat seeding via `DB::table()`, bungkus tanggal dengan `UTCDateTime` dan cast FK ke string — atau lebih aman gunakan Eloquent `create()`.
