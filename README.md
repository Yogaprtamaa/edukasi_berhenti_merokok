# Edukasi Berhenti Merokok

Platform edukasi terpadu untuk mendukung perjalanan berhenti merokok — dikembangkan oleh **Yoga Pratama**.

---

## Tentang Aplikasi

Platform ini menyediakan ekosistem lengkap bagi pengguna yang ingin berhenti merokok, mencakup:

- **Progress Tracker** — Pantau streak harian, rokok yang dihindari, dan uang yang dihemat
- **Konten Edukasi** — Artikel, video, dan infografis seputar berhenti merokok
- **Perpustakaan Buku** — Koleksi buku pilihan pendukung program berhenti merokok
- **Konsultasi Profesional** — Jadwalkan sesi dengan dokter atau psikolog terverifikasi
- **Komunitas Forum** — Diskusi dan berbagi pengalaman antar pengguna
- **Notifikasi** — Pengingat harian dan info program

---

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Framework | Laravel 9.52 |
| PHP | 8.0 |
| Database | MySQL |
| Frontend | Tailwind CSS v3, Alpine.js |
| Build Tool | Vite |
| Font | Inter, Playfair Display |

---

## Instalasi

### Prasyarat
- PHP >= 8.0
- Composer
- Node.js & npm
- MySQL

### Langkah-langkah

```bash
# 1. Clone repository
git clone https://github.com/Yogaprtamaa/edukasi_berhenti_merokok.git
cd edukasi_berhenti_merokok

# 2. Install dependensi PHP
composer install

# 3. Install dependensi Node
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate app key
php artisan key:generate

# 6. Konfigurasi database di .env
# DB_DATABASE=edukasi_berhenti_merokok
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Jalankan migration
php artisan migrate

# 8. Jalankan seeder (data dummy + admin)
php artisan db:seed --class=DummyDataSeeder

# 9. Build asset
npm run build

# 10. Jalankan server
php artisan serve
```

Akses aplikasi di `http://127.0.0.1:8000`

---

## Akun Default

### Admin
| Email | Password |
|-------|----------|
| admin@berhentimerokok.test | Admin12345! |

### User (Testing)
| Email | Password | Keterangan |
|-------|----------|------------|
| budi@test.com | Password123! | Streak 30 hari |
| rina@test.com | Password123! | Streak 15 hari |
| agus@test.com | Password123! | Streak 7 hari |
| dewi@test.com | Password123! | Belum ada tracker |
| hendra@test.com | Password123! | Belum ada tracker |

### Profesional (Testing)
| Email | Password | Keterangan |
|-------|----------|------------|
| siti.dr@test.com | Password123! | Dokter Umum, terverifikasi |
| ahmad.psi@test.com | Password123! | Psikolog Klinis, terverifikasi |
| maya.dr@test.com | Password123! | Dokter Umum, belum terverifikasi |

---

## Struktur Role

### User
- Akses dashboard, progress tracker, konten, buku, konsultasi, forum, notifikasi
- Dapat mengirim konten (menunggu persetujuan admin)

### Professional
- Kelola jadwal konsultasi dan janji temu
- Melihat daftar klien dan riwayat sesi

### Admin
- CRUD pengguna, profesional, konten, dan forum
- Verifikasi/tolak pendaftaran profesional
- Moderasi konten dan thread forum

---

## Halaman Utama

| Route | Keterangan |
|-------|------------|
| `/home` | Hub utama semua fitur (semua role) |
| `/` | Dashboard user |
| `/progress` | Progress tracker |
| `/contents` | Daftar konten edukasi |
| `/books` | Perpustakaan buku |
| `/consultations` | Daftar profesional |
| `/forums` | Forum komunitas |
| `/notifications` | Notifikasi |
| `/admin` | Dashboard admin |
| `/professional` | Dashboard profesional |

---

## Design System

Menggunakan gaya **luxury/editorial**:

- Background: `#F9F8F6` (warm alabaster)
- Foreground: `#1A1A1A` (charcoal)
- Accent: `#D4AF37` (gold)
- Heading: Playfair Display (serif, italic)
- Body: Inter
- 0px border-radius — semua border tajam
- Layout berbasis grid gap-px dan editorial spacing

---

## Lisensi

Proyek ini dikembangkan untuk keperluan akademik Universitas Paramadina.
