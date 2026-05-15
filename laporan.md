# LAPORAN IMPLEMENTASI SISTEM DATABASE
## Platform Edukasi Berhenti Merokok

**Mata Kuliah:** Database — Muhamad Darwis, S.Kom., M.Kom
**Program Studi:** Teknik Informatika, Fakultas Ilmu Rekayasa
**Universitas Paramadina — Tahun Ajaran 2025/2026**

**Disusun Oleh:**
- Yoga Pratama (25020100114)
- Alfianus Fierik Feto (125103031)
- Farel Abrar Hilalby (125103081)
- Mutiara Alia Putri (125103091)

**Framework:** Laravel 10 · **Database:** MySQL · **Tanggal Laporan:** 15 Mei 2026

---

## Daftar Isi

1. [Pertemuan 1 — Basis Data & DBMS](#pertemuan-1--basis-data--dbms)
2. [Pertemuan 2 — Kebutuhan Pengguna & Sistem Database](#pertemuan-2--kebutuhan-pengguna--sistem-database)
3. [Pertemuan 3 — Normalisasi & Fungsi Agregat](#pertemuan-3--normalisasi--fungsi-agregat)
4. [Pertemuan 4 — Query untuk Multiple Relation](#pertemuan-4--query-untuk-multiple-relation)
5. [Pertemuan 5 — SQL Processing & Query Optimization](#pertemuan-5--sql-processing--query-optimization)
6. [Pertemuan 6 — Query Operator, Transformation & Optimization](#pertemuan-6--query-operator-transformation--optimization)
7. [Pertemuan 7 — Database Security](#pertemuan-7--database-security)
8. [Rekapitulasi Status Implementasi](#rekapitulasi-status-implementasi)

---

## Pertemuan 1 — Basis Data & DBMS

### Konsep yang Diterapkan

Sistem ini dibangun menggunakan **pendekatan database** (bukan file-based), sesuai prinsip Pertemuan 1. Data tidak disimpan secara terpisah per modul, melainkan terpusat dalam satu database relasional MySQL yang dikelola melalui DBMS.

### Konfigurasi Database: Client/Server

```
Browser (Client)
     ↓  HTTP Request
Laravel Application (Aplikasi)
     ↓  PDO/Eloquent ORM
MySQL Server (DBMS)
     ↓
Storage Fisik (tabel .ibd)
```

| Komponen DBMS | Implementasi dalam Sistem |
|---------------|--------------------------|
| **Database** | MySQL: `edukasi_berhenti_merokok` — 18 tabel, metadata via `information_schema` |
| **DBMS** | MySQL Server + Laravel Eloquent (Query Processor, DDL via Migrations, DML via Eloquent) |
| **Aplikasi** | Laravel 10 — antarmuka web untuk semua aktor |
| **User** | 4 aktor: User/Perokok, Psikolog, Dokter, Admin |
| **DA** | Admin sistem — mengontrol kebijakan data (persetujuan konten, verifikasi profesional) |
| **DBA** | Developer — mengelola migration, indeks, constraint, backup schema |

### Terminologi dalam Sistem

| Istilah DB | Contoh Implementasi |
|------------|---------------------|
| **Entitas** | `users`, `appointments`, `payments`, `books` |
| **Atribut** | `users.email`, `appointments.status`, `payments.amount` |
| **Primary Key** | `id` (INT, AUTO_INCREMENT) di semua 18 tabel |
| **Foreign Key** | `appointments.user_id → users.id`, `payments.appointment_id → appointments.id` |
| **Metadata** | Laravel Migration files mendeskripsikan struktur tabel |
| **CRUD** | Create/Read/Update/Delete diimplementasikan di semua Controller |

### Tingkat Abstraksi

```
Level Konseptual  →  ERD (BAB 5 markdown.md) — entitas & relasi bisnis
Level Logis       →  Migration files — tabel, kolom, FK, constraint
Level Fisik       →  MySQL InnoDB storage engine — B-tree index, .ibd files
```

**File referensi:** `database/migrations/2014_10_12_000000_create_users_table.php`

---

## Pertemuan 2 — Kebutuhan Pengguna & Sistem Database

### Requirements Engineering yang Diterapkan

Sistem ini dikembangkan melalui 4 tahapan requirements engineering:

| Tahap | Dokumen/Artefak | Keterangan |
|-------|----------------|------------|
| **Elicitation** | BAB 1 markdown.md | Identifikasi 4 masalah utama dari perspektif perokok yang ingin berhenti |
| **Analysis** | BAB 2–4 markdown.md | Analisis 4 aktor, 18 entitas, 21 relasi |
| **Specification** | BAB 3 markdown.md | Dokumentasi formal: tipe data, constraint, kardinalitas |
| **Validation** | BAB 7 markdown.md — Tabel Ikhtisar | Verifikasi kesesuaian setiap fitur dengan kebutuhan |

### Jenis Kebutuhan yang Terpenuhi

| Jenis | Contoh Implementasi |
|-------|---------------------|
| **Fungsional** | Login/register, booking konsultasi, beli buku, check-in harian, forum diskusi |
| **Non-Fungsional** | Password hashing (bcrypt), RoleMiddleware (keamanan), paginate() (performa) |
| **Kebutuhan Pengguna** | Alur user-friendly: 8 alur utama didefinisikan di BAB 6 markdown.md |

### Model Representasi Data

```
Kebutuhan Pengguna (BAB 1 markdown.md)
         ↓
Model Eksternal   →  Views per role: user/, admin/, professional/
         ↓
Model Konseptual  →  ERD di BAB 5 markdown.md
         ↓
Model Logis       →  18 Migration files (tabel, atribut, relasi)
         ↓
Model Internal    →  MySQL InnoDB + index (migration 2026_05_15)
```

**File referensi:** `markdown.md` (BAB 1–7), `app/Http/Controllers/Auth/AuthController.php`

---

## Pertemuan 3 — Normalisasi & Fungsi Agregat

### Normalisasi Schema

Database dirancang mulai dari kebutuhan sistem, kemudian dinormalisasi hingga **3NF/BCNF** untuk menghindari redundansi.

#### 1NF — Nilai Atomik

Semua kolom bersifat atomik. Contoh penerapan: peran user tidak disimpan sebagai daftar teks, melainkan sebagai `ENUM('user','admin','professional')`.

```php
// users migration
$table->enum('role', ['user', 'admin', 'professional'])->default('user');
```

Grup berulang yang bisa terjadi (misal banyak jadwal profesional) dipecah ke tabel terpisah `schedules`.

#### 2NF — Tidak Ada Ketergantungan Parsial

Tidak ada atribut non-kunci yang bergantung pada sebagian kunci gabungan. Contoh: data buku tidak disimpan di tabel `orders`; `orders` hanya menyimpan FK `book_id`.

```
orders(id, user_id, book_id, quantity, total_price, status)
              ↓
books(id, title, author, price, cover_url, stock)
```

Jika buku disimpan langsung di `orders`, `title` hanya bergantung pada `book_id` (bukan seluruh kunci orders) → pelanggaran 2NF. Solusi: pisah tabel `books`.

#### 3NF — Tidak Ada Ketergantungan Transitif

Contoh: kebijakan refund tidak disimpan langsung di tabel `payments` agar menghindari ketergantungan transitif (`cancel_before_hours → refund_percentage`). Solusi: tabel terpisah `refund_policies`.

```
Tanpa 3NF (buruk):
payments(id, ..., cancel_before_hours, refund_percentage, description)
  → refund_percentage bergantung pada cancel_before_hours, bukan PK payments

Dengan 3NF (benar):
payments(id, ..., refund_percentage, refund_amount)  ← nilai aktual per transaksi
refund_policies(id, cancel_before_hours, refund_percentage, description)  ← aturan master
```

**File referensi:** `database/migrations/2026_04_30_031649_create_payments_table.php`, `database/migrations/2026_04_30_031802_create_refund_policies_table.php`

#### Ringkasan Normalisasi 18 Tabel

| Tabel | Pemisahan dari | Alasan Normalisasi |
|-------|---------------|-------------------|
| `professionals` | `users` | Data profil profesional tidak relevan untuk semua user → 1NF/2NF |
| `professional_verifications` | `professionals` | Log approval bersifat terpisah dari data master → 2NF |
| `consultations` | `appointments` | Hasil konsultasi (ringkasan, rekomendasi) terpisah dari janji temu → 3NF |
| `refund_policies` | `payments` | Aturan refund bersifat master data → menghindari transitif dependency |
| `content_approvals` | `contents` | Log persetujuan admin terpisah dari konten itu sendiri |
| `daily_check_ins` | `progress_tracker` | Histori check-in tiap hari bersifat multi-record → 1NF |
| `forum_replies` | `forums` | Balasan adalah entitas berbeda dari thread → 1NF |

### Fungsi Agregat

Fungsi agregat SQL digunakan di lapisan Controller melalui Eloquent Query Builder (yang menghasilkan SQL dengan fungsi agregat).

| Fungsi | Lokasi Implementasi | Query yang Dihasilkan |
|--------|--------------------|-----------------------|
| `COUNT()` | `AdminDashboardController::index()` | `SELECT COUNT(*) FROM users WHERE role = 'user'` |
| `SUM()` | `AdminDashboardController::index()` | `SELECT SUM(amount) FROM payments WHERE status = 'success'` |
| `COUNT() + WHERE` | `AdminPaymentController::index()` | `SELECT COUNT(*) FROM payments WHERE status = 'pending'` |
| `SUM() + WHERE + GROUP` | `AdminDashboardController` (revenue chart) | `SELECT SUM(amount) FROM payments WHERE status='success' AND YEAR(paid_at)=? AND MONTH(paid_at)=?` |

**Kode implementasi:**
```php
// app/Http/Controllers/Admin/DashboardController.php
$totalUsers        = User::where('role', 'user')->count();          // COUNT()
$totalRevenue      = Payment::where('status', 'success')->sum('amount'); // SUM()
$pendingProfessionals = Professional::where('is_verified', false)->count(); // COUNT() + filter

// Revenue per bulan (groupBy implisit via collect + loop bulan)
$revenueChart = collect(range(5, 0))->map(function ($monthOffset) {
    $date   = now()->subMonths($monthOffset);
    $amount = Payment::where('status', 'success')
        ->whereYear('paid_at', $date->year)
        ->whereMonth('paid_at', $date->month)
        ->sum('amount');   // SUM() per bulan
    return ['label' => $date->format('M'), 'amount' => (float) $amount];
});
```

**File referensi:** `app/Http/Controllers/Admin/DashboardController.php`

---

## Pertemuan 4 — Query untuk Multiple Relation

### Struktur Relasi Antar Tabel

```
users (1) ──────────────────────── (N) appointments
  │                                        │
  │ (1:1 optional)               (1:1 optional)
  ↓                                        ↓
professionals (1) ──── (N) appointments  payments
  │
  │ (1:N)
  ↓
schedules (1) ──── (N) appointments
```

### Jenis Relasi yang Diimplementasikan

| Jenis | Tabel | Implementasi Eloquent |
|-------|-------|-----------------------|
| **1:1** | `users` → `professionals` | `hasOne(Professional::class)` / `belongsTo(User::class)` |
| **1:1** | `appointments` → `consultations` | `hasOne(Consultation::class)` |
| **1:1** | `appointments` → `payments` | `hasOne(Payment::class)` |
| **1:N** | `users` → `appointments` | `hasMany(Appointment::class)` |
| **1:N** | `professionals` → `schedules` | `hasMany(Schedule::class)` |
| **1:N** | `forums` → `forum_replies` | `hasMany(ForumReply::class)` |
| **1:N** | `users` → `daily_check_ins` | `hasMany(DailyCheckIn::class)` |

### JOIN Query via Eloquent (Eager Loading)

Laravel Eloquent `with()` menghasilkan JOIN / sub-query otomatis ke database. Berikut contoh nyata dalam sistem:

#### INNER JOIN — Data Pembayaran + User + Buku

```php
// app/Http/Controllers/Admin/PaymentController.php
$payments = Payment::with(['user', 'order.book', 'appointment.professional.user'])
    ->when($status, fn($q) => $q->where('status', $status))
    ->latest()
    ->paginate(20);
```

Query SQL yang dihasilkan (equiv. INNER JOIN):
```sql
SELECT payments.*, users.name, books.title, professionals.id
FROM payments
INNER JOIN users ON payments.user_id = users.id
LEFT JOIN orders ON orders.payment_id = payments.id
LEFT JOIN books ON orders.book_id = books.id
LEFT JOIN appointments ON payments.appointment_id = appointments.id
LEFT JOIN professionals ON appointments.professional_id = professionals.id
ORDER BY payments.created_at DESC
LIMIT 20 OFFSET 0;
```

#### LEFT JOIN — Revenue Chart (semua bulan termasuk yang 0)

```php
// app/Http/Controllers/Admin/DashboardController.php
$revenueChart = collect(range(5, 0))->map(function ($monthOffset) {
    $date   = now()->subMonths($monthOffset);
    $amount = Payment::where('status', 'success')
        ->whereYear('paid_at', $date->year)
        ->whereMonth('paid_at', $date->month)
        ->sum('amount');
    return ['label' => $date->format('M'), 'amount' => (float) $amount];
});
```

Pendekatan ini setara LEFT JOIN bulan kalender ke tabel payments — bulan tanpa transaksi tetap ditampilkan dengan nilai 0.

#### Multiple Relation — Transaksi dengan Referensi Dinamis

```php
// app/Http/Controllers/Admin/DashboardController.php
$transactionTypeChart = [
    'ebook'        => Payment::whereHas('order')->count(),       // EXISTS subquery
    'consultation' => Payment::whereNotNull('appointment_id')->count(),
];
```

#### Relasi Berantai (Chained Relations)

```php
// Akses data profesional melalui appointment → professional → user
$payment->load(['order.book', 'appointment.professional.user']);
// payment → appointment → professional → user (3 tingkat relasi)
```

**File referensi:** `app/Models/Appointment.php`, `app/Models/Payment.php`, `app/Models/User.php`, `app/Http/Controllers/Admin/DashboardController.php`

---

## Pertemuan 5 — SQL Processing & Query Optimization

### Prepared Statements (Bind Variables)

Sesuai materi Pertemuan 5 (Oracle SQL Tuning Guide), penggunaan **bind variables / prepared statements** menghindari hard parse berulang. Laravel Eloquent secara otomatis menggunakan **PDO Prepared Statements** untuk semua query.

**Contoh nyata dalam sistem:**

```php
// app/Http/Controllers/User/CheckInController.php
$alreadyCheckedIn = DailyCheckIn::where('user_id', $user->id)
    ->whereDate('check_in_date', today())
    ->exists();
```

PDO menghasilkan:
```sql
-- Prepared statement (dikirim sekali ke server, di-cache di shared pool)
SELECT EXISTS(
  SELECT 1 FROM daily_check_ins
  WHERE user_id = ?          -- bind variable :1
  AND DATE(check_in_date) = ? -- bind variable :2
)
```

Parameter `$user->id` dan `today()` dikirim sebagai **bind variables**, bukan diinterpolasi langsung ke query string. Ini setara dengan Oracle's **soft parse** karena SQL template yang sama bisa di-reuse dari query cache.

### Hard Parse vs Soft Parse dalam Konteks Laravel

| Situasi | Jenis Parse | Contoh |
|---------|-------------|--------|
| Query pertama kali dieksekusi | Hard Parse | App pertama kali boot, cache kosong |
| Query sama dieksekusi lagi (param beda) | Soft Parse | Request kedua `where('user_id', ?)` → reuse plan |
| DDL (migration dijalankan) | Selalu Hard Parse | `php artisan migrate` → ALTER TABLE |

### Rekomendasi yang Diterapkan

| Rekomendasi P5 | Implementasi |
|----------------|--------------|
| Gunakan prepared statements | ✅ Semua query via Eloquent ORM menggunakan PDO prepared statements |
| Hindari `SELECT *` di production | ✅ Sebagian besar query menggunakan `with()` dengan relasi spesifik |
| DDL saat runtime dihindari | ✅ Semua perubahan schema via Migration (offline), bukan ALTER saat request |
| Query caching | ✅ Laravel Query Builder memanfaatkan MySQL query cache implisit |

**File referensi:** Semua file di `app/Http/Controllers/`, `database/migrations/`

---

## Pertemuan 6 — Query Operator, Transformation & Optimization

### Query Operator

Seluruh operator SQL dasar diimplementasikan melalui Eloquent:

| Operator SQL | Eloquent Method | Contoh dalam Sistem |
|-------------|-----------------|---------------------|
| `SELECT` | `->select()` / default | `Payment::with(...)` → pilih kolom spesifik |
| `FROM` | Model class | `Payment::` → FROM payments |
| `WHERE` | `->where()` | `->where('status', 'pending')` |
| `GROUP BY` | Implisit via collection | `->whereYear()->whereMonth()` per bulan |
| `ORDER BY` | `->latest()` / `->orderBy()` | `Payment::latest()->paginate()` |
| `HAVING` | `->having()` | Digunakan saat filter agregat |
| `JOIN` | `->with()` / `->join()` | `->with(['user', 'order.book'])` |
| `LIMIT` | `->paginate()` / `->take()` | `->paginate(20)`, `->take(5)` |

### Transformation

Transformasi data diterapkan melalui aggregasi dan join:

```php
// Transformasi: hitung total per bulan (aggregasi + reshape)
$revenueChart = collect(range(5, 0))->map(function ($monthOffset) {
    $date   = now()->subMonths($monthOffset);
    $amount = Payment::where('status', 'success')
        ->whereYear('paid_at', $date->year)
        ->whereMonth('paid_at', $date->month)
        ->sum('amount');
    return ['label' => $date->format('M'), 'amount' => (float) $amount];
});

// Transformasi: join 3 tabel untuk tampilan pembayaran lengkap
$payments = Payment::with(['user', 'order.book', 'appointment.professional.user'])
    ->latest()->paginate(20);
```

### Optimization — Index

**Migration yang baru ditambahkan:** `database/migrations/2026_05_15_000000_add_performance_indexes.php`

Index eksplisit ditambahkan pada kolom yang paling sering digunakan dalam filter, join, dan sorting:

```php
// Appointments: filter status + urut tanggal
$table->index('status', 'idx_appointments_status');
$table->index('appointment_date', 'idx_appointments_date');

// Payments: filter status + urut waktu bayar
$table->index('status', 'idx_payments_status');
$table->index('paid_at', 'idx_payments_paid_at');

// Contents: filter approval_status + is_published
$table->index('approval_status', 'idx_contents_approval_status');
$table->index('is_published', 'idx_contents_is_published');

// Users: filter by role (admin dashboard)
$table->index('role', 'idx_users_role');

// Daily check-ins: filter by date (streak calculation)
$table->index('check_in_date', 'idx_daily_check_ins_date');
```

**Sebelum index** (tanpa index pada `payments.status`):
```sql
-- Full table scan: O(n) baris
SELECT SUM(amount) FROM payments WHERE status = 'success';
-- Dengan 100.000 baris → scan semua 100.000 baris
```

**Setelah index** (dengan `idx_payments_status`):
```sql
-- Index scan: O(log n) + matching rows
SELECT SUM(amount) FROM payments WHERE status = 'success';
-- Dengan 100.000 baris → langsung ke node B-tree 'success'
```

### Checklist Optimasi (Pertemuan 6)

| Checklist | Status | Bukti |
|-----------|--------|-------|
| Index pada kolom WHERE, JOIN, ORDER BY | ✅ | Migration `2026_05_15_000000_add_performance_indexes.php` |
| SELECT spesifik (hindari `SELECT *`) | ✅ | `Payment::with(['user', 'order.book'])` — hanya relasi yang dibutuhkan |
| LIMIT / Pagination untuk data besar | ✅ | `->paginate(20)` di admin, `->paginate(10)` di user, `->take(5)` di dashboard |
| Subquery → JOIN bila memungkinkan | ✅ | Eager loading `with()` lebih efisien dari lazy loading N+1 |
| Explain plan untuk query kritis | 📋 | Direkomendasikan di production via `DB::statement('EXPLAIN ...')` |

**File referensi:** `database/migrations/2026_05_15_000000_add_performance_indexes.php`, `app/Http/Controllers/Admin/DashboardController.php`, `app/Http/Controllers/Admin/PaymentController.php`

---

## Pertemuan 7 — Database Security

### Jenis Integritas Data yang Diimplementasikan

| Jenis Integritas | Mekanisme | Implementasi dalam Sistem |
|-----------------|-----------|--------------------------|
| **Entity Integrity** | PRIMARY KEY NOT NULL | `$table->id()` di semua 18 tabel → BIGINT UNSIGNED NOT NULL AUTO_INCREMENT |
| **Referential Integrity** | FOREIGN KEY + CASCADE | `$table->foreignId('user_id')->constrained('users')->onDelete('cascade')` |
| **Domain Integrity** | ENUM, tipe data, nullable | `ENUM('user','admin','professional')`, `DECIMAL(10,2)`, `BOOLEAN` |
| **Enterprise Integrity** | Business rules di Controller | Streak reset jika `is_smoke_free = FALSE`; profesional tidak aktif jika `is_verified = FALSE` |

**Contoh Foreign Key Constraint:**
```php
// database/migrations/2026_04_30_031619_create_appointments_table.php
$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
$table->foreignId('professional_id')->constrained('professionals')->onDelete('cascade');
```

### Authentication & Authorization

#### Authentication — Verifikasi Identitas

```php
// app/Http/Controllers/Auth/AuthController.php
public function register(Request $request)
{
    $data = $request->validate([
        'email'    => ['required', 'email', 'max:150', 'unique:users'],
        'password' => ['required', 'min:8', 'confirmed'],
    ]);

    $user = User::create([
        'password' => Hash::make($data['password']),  // bcrypt encryption
    ]);
}

public function login(Request $request)
{
    $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required']]);
    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();  // session fixation protection
        return $this->redirectByRole();
    }
    return back()->withErrors(['email' => 'Email atau password salah.']);
}
```

#### Authorization — Verifikasi Hak Akses

```php
// app/Http/Middleware/RoleMiddleware.php
public function handle(Request $request, Closure $next, string ...$roles): mixed
{
    if (!$request->user() || !in_array($request->user()->role, $roles)) {
        abort(403, 'Akses ditolak.');
    }
    return $next($request);
}
```

Penerapan role-based access control (RBAC):

| Role | CREATE | READ | UPDATE | DELETE |
|------|--------|------|--------|--------|
| Admin | ✅ | ✅ | ✅ | ✅ |
| Professional | ✅ (jadwal) | ✅ | ✅ (sendiri) | ❌ |
| User | ✅ (konten, forum) | ✅ | ✅ (sendiri) | ❌ |

**Penggunaan middleware:**
```php
// routes/web.php
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () { ... });
Route::middleware(['auth', 'role:professional'])->prefix('professional')->group(function () { ... });
Route::middleware(['auth', 'role:user,admin'])->group(function () { ... });
```

### Enkripsi Password

```
Plaintext Password  ──[bcrypt + salt]──→  Hash (60 karakter)
                                           $2y$10$...
```

- Menggunakan **bcrypt** (hashing satu arah) — bukan symmetric/asymmetric encryption
- Salt otomatis per user — dua password identik menghasilkan hash berbeda
- Cost factor default Laravel: 10 rounds
- Tidak ada plaintext password yang tersimpan di database

```php
Hash::make($data['password'])   // Saat register
Auth::attempt($credentials)     // Saat login — Laravel verify hash otomatis
```

### Mekanisme Keamanan Lainnya

| Mekanisme (P7) | Implementasi |
|----------------|--------------|
| **Transaction Log** | `payment_history` table — semua transaksi keuangan tercatat |
| **Audit trail** | `created_at`, `updated_at`, `paid_at`, `processed_at` di setiap tabel |
| **Views/Subschema** | Setiap role hanya melihat view-nya sendiri (admin/, user/, professional/) |
| **CSRF Protection** | Laravel `VerifyCsrfToken` middleware aktif di semua form |
| **Session Security** | `$request->session()->regenerate()` setelah login (anti-fixation) |
| **Input Validation** | `$request->validate()` di semua Controller — mencegah SQL injection via PDO |

**File referensi:** `app/Http/Controllers/Auth/AuthController.php`, `app/Http/Middleware/RoleMiddleware.php`, `app/Http/Kernel.php`

---

## Rekapitulasi Status Implementasi

| No. | Pertemuan | Topik | Status | Implementasi Utama |
|-----|-----------|-------|--------|--------------------|
| 1 | P1 | Basis Data & DBMS | ✅ Terpenuhi | MySQL Client/Server, 18 tabel, PK/FK/CRUD |
| 2 | P2 | Kebutuhan Pengguna | ✅ Terpenuhi | Requirements Engineering, 4 jenis kebutuhan, model representasi |
| 3 | P3 | Normalisasi & Agregat | ✅ Terpenuhi | 1NF–3NF di 18 tabel; COUNT(), SUM() di DashboardController |
| 4 | P4 | Multiple Relation & JOIN | ✅ Terpenuhi | Eloquent Eager Loading (`with()`), 1:1, 1:N, chained relations |
| 5 | P5 | SQL Processing | ✅ Terpenuhi | PDO Prepared Statements (soft parse), DDL via Migration |
| 6 | P6 | Query Optimization | ✅ Terpenuhi | 9 index baru (migration), paginate(), take(), select spesifik |
| 7 | P7 | Database Security | ✅ Terpenuhi | bcrypt, RoleMiddleware, FK constraint, CSRF, session regenerate |

### Statistik Sistem

| Aspek | Jumlah |
|-------|--------|
| Tabel database | 18 tabel |
| Migration files | 23 files |
| Model Eloquent | 18 models |
| Controller | 20+ controllers |
| Relasi antar tabel | 21 relasi |
| Index database | 11 index (2 unique + 9 performance) |
| Role pengguna | 3 role (user, admin, professional) |

---

## Penjelasan Laporan Awal (markdown.md)

Dokumen `markdown.md` merupakan **laporan perancangan awal** sistem yang mencakup:

| Bab | Isi | Hubungan ke Acuan Dosen |
|-----|-----|------------------------|
| **BAB 1** — Analisis Requirement | Latar belakang, 4 masalah, 9 kebutuhan fungsional | **P2** — Requirements Engineering (Elicitation, Specification) |
| **BAB 2** — Identifikasi Entitas | 4 aktor, 18 entitas dengan deskripsi | **P1** — Terminologi DB (entitas, atribut, record) |
| **BAB 3** — Penentuan Atribut | Spesifikasi kolom: tipe data, PK, FK, constraint per tabel | **P1 + P3** — Definisi atribut & domain integrity |
| **BAB 4** — Kardinalitas & Relasi | 21 relasi dengan aturan bisnis (1:1, 1:N, opsional) | **P4** — Multiple Relation, Foreign Key |
| **BAB 5** — ERD | Rekap entitas + atribut + relasi sebagai dasar ERD | **P4** — Struktur ERD |
| **BAB 6** — Alur Aktivitas | 8 alur pengguna (login, konsultasi, progress, dll.) | **P2** — Kebutuhan Pengguna, Alur Sistem |
| **BAB 7** — Kesimpulan | Ringkasan 18 tabel, 9 fitur, status implementasi | **P1–P7** — Validasi keseluruhan |

Laporan awal (`markdown.md`) adalah **rancangan/desain** sistem, sedangkan laporan ini (`laporan.md`) adalah **bukti implementasi aktual** yang membuktikan bahwa rancangan tersebut telah diwujudkan dalam kode Laravel dan database MySQL, sesuai dengan seluruh materi Pertemuan 1–7.

---

*Laporan implementasi ini menunjukkan bahwa seluruh konsep basis data dari Pertemuan 1–7 telah diterapkan secara nyata dalam sistem Platform Edukasi Berhenti Merokok menggunakan Laravel 10 + MySQL.*
