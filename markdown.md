# LAPORAN PROJECT DATABASE
## Edukasi Berhenti Merokok

**Mata Kuliah:** Database — Muhamad Darwis, S.Kom., M.Kom  
**Program Studi:** Teknik Informatika, Fakultas Ilmu Rekayasa  
**Universitas Paramadina — Tahun Ajaran 2025/2026**

**Disusun Oleh:**
- Yoga Pratama (25020100114)
- Alfianus Fierik Feto (125103031)
- Farel Abrar Hilalby (125103081)
- Mutiara Alia Putri (125103091)

---

## BAB 1 — Analisis Requirement Basis Data

### 1.1 Latar Belakang

Merokok merupakan salah satu kebiasaan yang paling sulit dihentikan. Meskipun banyak perokok yang ingin berhenti, mereka sering kali tidak memiliki akses ke informasi yang tepat, sumber daya edukasi yang terpercaya, maupun dukungan profesional yang memadai. Kondisi ini diperparah dengan tidak adanya platform terpadu yang menggabungkan seluruh kebutuhan tersebut dalam satu ekosistem digital.

### 1.2 Permasalahan Utama

| No. | Kategori Masalah | Deskripsi |
|-----|-----------------|-----------|
| 1 | Akses Edukasi Terbatas | Perokok kesulitan menemukan buku dan materi edukasi terpercaya yang khusus membahas cara berhenti merokok. |
| 2 | Sulitnya Menjangkau Profesional | Konsultasi dengan psikolog atau dokter memiliki hambatan biaya tinggi, rasa malu, dan sulitnya mendapat jadwal. |
| 3 | Tidak Ada Manajemen Jadwal Terpadu | Belum ada sistem booking yang mendukung mode konsultasi hybrid (online & offline) dalam satu platform. |
| 4 | Minimnya Dukungan Berkelanjutan | Tidak ada alat bantu untuk memantau progres, mendapatkan pengingat, maupun komunitas pendukung. |

### 1.3 Tujuan Sistem

- Menyediakan platform edukasi terpadu untuk mendukung proses berhenti merokok
- Memfasilitasi akses ke profesional secara online maupun offline
- Menyediakan sistem janji temu yang terstruktur dan mudah digunakan
- Mendukung motivasi pengguna dengan fitur progress tracker, notifikasi reminder harian, dan komunitas forum
- Menyediakan sistem konten edukasi yang dapat diunggah oleh user maupun admin, dengan moderasi admin
- Mengelola pembayaran konsultasi berbasis hitungan jam dengan kebijakan refund 15%–85%

### 1.4 Kebutuhan Fungsional Sistem

| No. | Modul | Kebutuhan Fungsional |
|-----|-------|----------------------|
| 1 | Autentikasi | Registrasi user, login, verifikasi email, pengelolaan role |
| 2 | Progress Tracker | Notifikasi reminder harian; konfirmasi harian 'Apakah Anda tidak merokok hari ini?'; streak bertambah jika Ya, reset jika Tidak |
| 3 | Registrasi Profesional | Pendaftaran dokter/psikolog harus menunggu persetujuan admin sebelum aktif |
| 4 | Pembayaran Konsultasi | Sistem pembayaran dihitung per jam; kebijakan refund berjenjang 15%–85% sesuai waktu pembatalan |
| 5 | History Transaksi | Tabel history untuk semua transaksi pembayaran konsultasi dan pembelian buku |
| 6 | Penyedia Konten | User dan admin dapat mengunggah artikel/konten edukasi; konten dari user harus disetujui admin sebelum dipublikasikan |
| 7 | Konsultasi & Jadwal | Booking janji temu hybrid, konfirmasi profesional, pencatatan hasil konsultasi |
| 8 | Forum Komunitas | Thread diskusi, balasan, moderasi admin |
| 9 | Pembelian Buku | Katalog buku, keranjang, checkout, unduh digital |

---

## BAB 2 — Identifikasi Entitas Basis Data

### 2.1 Aktor / Pengguna Sistem

| Aktor | Tipe | Peran dalam Sistem |
|-------|------|-------------------|
| User / Perokok | End User Utama | Membeli buku, membaca konten, membuat janji temu, mencatat progress, berpartisipasi di forum diskusi, mengunggah konten |
| Psikolog | Profesional | Mendaftar, menerima janji temu, melakukan sesi konsultasi online/offline, mengelola jadwal ketersediaan |
| Dokter | Profesional | Sama seperti psikolog, memberikan perspektif medis dan klinis dalam konsultasi berhenti merokok |
| Admin | Pengelola Sistem | Menyetujui registrasi profesional, mengelola & menyetujui konten edukasi, memantau transaksi, moderasi forum |

### 2.2 Identifikasi Entitas Database

| No. | Nama Entitas | Deskripsi Entitas |
|-----|-------------|------------------|
| 1 | users | Menyimpan data semua pengguna sistem |
| 2 | professionals | Data tambahan untuk user yang berperan sebagai dokter/psikolog, status verifikasi admin |
| 3 | professional_verifications | Log pengajuan dan persetujuan registrasi profesional oleh admin |
| 4 | schedules | Jadwal ketersediaan konsultasi milik profesional |
| 5 | appointments | Data janji temu antara user dan profesional |
| 6 | consultations | Catatan hasil sesi konsultasi |
| 7 | payments | Data pembayaran konsultasi berbasis jam, termasuk status refund |
| 8 | payment_history | Riwayat seluruh transaksi pembayaran |
| 9 | refund_policies | Aturan persentase refund berdasarkan waktu pembatalan |
| 10 | books | Katalog buku edukasi berhenti merokok |
| 11 | orders | Pesanan pembelian buku oleh user |
| 12 | contents | Artikel/konten edukasi yang diunggah user atau admin, dengan status persetujuan |
| 13 | content_approvals | Log persetujuan konten dari user oleh admin |
| 14 | progress_tracker | Data progress berhenti merokok per user |
| 15 | daily_check_ins | Catatan konfirmasi harian user untuk kalkulasi streak |
| 16 | notifications | Notifikasi & reminder yang dikirim ke user |
| 17 | forums | Thread diskusi komunitas |
| 18 | forum_replies | Balasan pada thread forum |

---

## BAB 3 — Penentuan Atribut Setiap Entitas

### 3.1 Tabel `users`

| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | INT (PK) | Primary key, auto increment |
| name | VARCHAR(100) | Nama lengkap pengguna |
| email | VARCHAR(150) | Email unik, digunakan untuk login |
| password | VARCHAR(255) | Password terenkripsi |
| birth_date | DATE | Tanggal lahir |
| role | ENUM('user','admin','professional') | Peran pengguna dalam sistem |
| is_email_verified | BOOLEAN | Status verifikasi email |
| created_at | TIMESTAMP | Waktu registrasi |

### 3.2 Tabel `professionals`

| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | INT (PK) | Primary key |
| user_id | INT (FK → users) | Referensi ke tabel users |
| type | ENUM('psikolog','dokter') | Jenis profesional |
| specialization | VARCHAR(100) | Spesialisasi |
| license_number | VARCHAR(100) | Nomor lisensi profesional |
| document_url | VARCHAR(255) | URL dokumen lisensi yang diunggah |
| is_verified | BOOLEAN | Status persetujuan oleh admin |
| verified_at | TIMESTAMP | Waktu diverifikasi admin |
| hourly_rate | DECIMAL(10,2) | Tarif per jam konsultasi |

### 3.3 Tabel `professional_verifications`

| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | INT (PK) | Primary key |
| professional_id | INT (FK → professionals) | Referensi profesional yang mengajukan |
| admin_id | INT (FK → users) | Admin yang memproses pengajuan |
| status | ENUM('pending','approved','rejected') | Status verifikasi |
| notes | TEXT | Catatan admin saat approve/reject |
| created_at | TIMESTAMP | Waktu pengajuan |
| processed_at | TIMESTAMP | Waktu diproses admin |

### 3.4 Tabel `payments`

| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | INT (PK) | Primary key |
| appointment_id | INT (FK → appointments) | Referensi janji temu yang dibayar |
| user_id | INT (FK → users) | User yang melakukan pembayaran |
| amount | DECIMAL(10,2) | Total pembayaran |
| duration_hours | DECIMAL(4,2) | Durasi konsultasi dalam jam |
| status | ENUM('pending','paid','refunded','partial_refund') | Status pembayaran |
| refund_percentage | DECIMAL(5,2) | Persentase refund yang diberikan |
| refund_amount | DECIMAL(10,2) | Nominal refund yang dikembalikan |
| payment_method | ENUM('transfer','e-wallet','credit_card') | Metode pembayaran |
| paid_at | TIMESTAMP | Waktu pembayaran dilakukan |
| refunded_at | TIMESTAMP | Waktu refund diproses |

### 3.5 Tabel `refund_policies`

| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | INT (PK) | Primary key |
| cancel_before_hours | INT | Waktu pembatalan sebelum sesi |
| refund_percentage | DECIMAL(5,2) | Persentase refund yang diberikan |
| description | VARCHAR(255) | Keterangan kebijakan refund |

**Contoh data kebijakan refund:**

| Pembatalan Sebelum Sesi | Persentase Refund |
|------------------------|-------------------|
| Lebih dari 48 jam | 85% |
| 24–48 jam | 50% |
| 12–24 jam | 30% |
| Kurang dari 12 jam | 15% |

### 3.6 Tabel `payment_history`

| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | INT (PK) | Primary key |
| user_id | INT (FK → users) | User yang melakukan transaksi |
| transaction_type | ENUM('consultation','book_order') | Jenis transaksi |
| reference_id | INT | ID referensi |
| amount | DECIMAL(10,2) | Nominal transaksi |
| status | VARCHAR(50) | Status transaksi saat dicatat |
| description | TEXT | Deskripsi transaksi |
| created_at | TIMESTAMP | Waktu pencatatan history |

### 3.7 Tabel `progress_tracker`

| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | INT (PK) | Primary key |
| user_id | INT (FK → users) | Referensi user pemilik tracker |
| quit_date | DATE | Tanggal resmi berhenti merokok |
| streak_days | INT | Jumlah hari berturut-turut tidak merokok |
| cigarettes_per_day | INT | Jumlah rokok per hari sebelum berhenti |
| cigarettes_avoided | INT | Total rokok yang berhasil dihindari |
| money_saved | DECIMAL(10,2) | Total uang yang berhasil dihemat |
| last_check_in | DATE | Tanggal check-in terakhir |
| updated_at | TIMESTAMP | Waktu pembaruan data tracker |

### 3.8 Tabel `daily_check_ins`

| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | INT (PK) | Primary key |
| user_id | INT (FK → users) | Referensi user |
| check_in_date | DATE | Tanggal check-in |
| is_smoke_free | BOOLEAN | True = tidak merokok hari ini, False = merokok |
| notification_id | INT (FK → notifications) | Referensi notifikasi reminder yang memicu check-in |
| created_at | TIMESTAMP | Waktu check-in dilakukan |

### 3.9 Tabel `contents`

| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | INT (PK) | Primary key |
| uploader_id | INT (FK → users) | User atau admin yang mengunggah konten |
| uploader_role | ENUM('user','admin') | Menandai apakah konten dari user atau admin |
| title | VARCHAR(200) | Judul konten |
| body | TEXT | Isi artikel/konten |
| type | ENUM('artikel','video','infografis') | Jenis konten |
| approval_status | ENUM('pending','approved','rejected') | Status konten; auto-approved jika dari admin |
| is_published | BOOLEAN | Status tayang konten |
| published_at | TIMESTAMP | Waktu dipublikasikan |
| created_at | TIMESTAMP | Waktu unggah |

### 3.10 Tabel `content_approvals`

| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | INT (PK) | Primary key |
| content_id | INT (FK → contents) | Konten yang diajukan |
| admin_id | INT (FK → users) | Admin yang memproses |
| status | ENUM('approved','rejected') | Hasil review admin |
| notes | TEXT | Catatan admin |
| processed_at | TIMESTAMP | Waktu diproses |

---

## BAB 4 — Penentuan Kardinalitas dan Relasi

### 4.1 Tabel Relasi Antar Entitas

| Tabel Asal | Kardinalitas | Tabel Tujuan | Aturan Relasi |
|------------|-------------|-------------|---------------|
| users | 1 : 1 (opsional) | professionals | User dengan role 'professional' memiliki satu entri di tabel professionals |
| users | 1 : N | appointments | Satu user dapat membuat banyak janji temu |
| users | 1 : 1 | progress_tracker | Setiap user memiliki tepat satu data progress tracker |
| users | 1 : N | daily_check_ins | Setiap hari user dapat melakukan satu check-in; satu user memiliki banyak check-in |
| users | 1 : N | notifications | User dapat menerima banyak notifikasi/reminder |
| users | 1 : N | orders | Satu user dapat melakukan banyak pembelian buku |
| users | 1 : N | contents | User/admin dapat mengunggah banyak konten |
| users | 1 : N | forums | Satu user dapat membuat banyak thread forum |
| users | 1 : N | forum_replies | Satu user dapat membalas banyak thread |
| users | 1 : N | payment_history | Semua transaksi user tercatat di payment_history |
| professionals | 1 : 1 (opsional) | professional_verifications | Setiap pengajuan profesional memiliki satu data verifikasi |
| professionals | 1 : N | schedules | Setiap profesional memiliki banyak slot jadwal |
| professionals | 1 : N | appointments | Profesional dapat menerima banyak janji temu |
| schedules | 1 : N (opsional) | appointments | Satu slot jadwal bisa dirujuk oleh beberapa booking |
| appointments | 1 : 1 (opsional) | consultations | Satu janji temu menghasilkan satu catatan konsultasi |
| appointments | 1 : 1 (opsional) | payments | Satu janji temu memiliki satu data pembayaran |
| contents | 1 : 1 (opsional) | content_approvals | Konten dari user memiliki satu data approval; konten admin langsung approved |
| books | 1 : N | orders | Satu buku dapat dipesan dalam banyak transaksi |
| forums | 1 : N | forum_replies | Satu thread forum dapat memiliki banyak balasan |
| notifications | 1 : N (opsional) | daily_check_ins | Notifikasi reminder memicu check-in harian user |
| refund_policies | 1 : N | payments | Satu kebijakan refund dapat diterapkan pada banyak pembayaran |

### 4.2 Penjelasan Relasi Fitur Utama

#### Progress Tracker & Daily Check-In

Sistem progress tracker bekerja bersama tabel `daily_check_ins`:

1. Setiap hari, sistem mengirimkan notifikasi reminder ke user
2. User mengklik notifikasi, lalu menjawab *"Apakah Anda tidak merokok hari ini?"*
3. Jawaban disimpan di tabel `daily_check_ins`
4. Jika `TRUE` → `streak_days` di `progress_tracker` bertambah +1
5. Jika `FALSE` → `streak_days` di `progress_tracker` direset ke 0

#### Registrasi Profesional & Verifikasi Admin

1. Dokter/psikolog mendaftar → data masuk ke tabel `professionals` dengan `is_verified = FALSE`
2. Pengajuan tercatat di `professional_verifications` dengan `status = 'pending'`
3. Admin memproses: approve atau reject dengan catatan
4. Profesional hanya dapat aktif menerima booking setelah `is_verified = TRUE`

#### Pembayaran Konsultasi & Refund

- Tarif dihitung: `amount = duration_hours × hourly_rate`
- Kebijakan refund mengacu pada tabel `refund_policies` berdasarkan waktu pembatalan
- Refund berjenjang: 15% hingga 85%
- Seluruh transaksi pembayaran tercatat di `payment_history`

#### Penyedia Konten (User & Admin)

- Admin dapat langsung mempublikasikan konten
- User mengunggah konten → `approval_status = 'pending'` hingga disetujui admin
- Admin merespons via tabel `content_approvals`
- Konten yang disetujui → `is_published = TRUE` dan dicatat `published_at`

---

## BAB 5 — Perancangan ERD

### 5.1 Daftar Entitas dan Atribut Lengkap

| No. | Tabel | Entitas | Atribut Penting |
|-----|-------|---------|-----------------|
| 1 | users | Pengguna | id (PK), name, email, password, birth_date, role, is_email_verified, created_at |
| 2 | professionals | Profesional | id (PK), user_id (FK), type, specialization, license_number, document_url, is_verified, hourly_rate |
| 3 | professional_verifications | Verifikasi Profesional | id (PK), professional_id (FK), admin_id (FK), status, notes, created_at, processed_at |
| 4 | schedules | Jadwal | id (PK), professional_id (FK), day_of_week, start_time, end_time, mode |
| 5 | appointments | Janji Temu | id (PK), user_id (FK), professional_id (FK), schedule_id (FK), mode, status, appointment_date |
| 6 | consultations | Sesi Konsultasi | id (PK), appointment_id (FK), summary, recommendation, started_at, ended_at |
| 7 | payments | Pembayaran | id (PK), appointment_id (FK), user_id (FK), amount, duration_hours, status, refund_percentage, refund_amount, paid_at |
| 8 | refund_policies | Kebijakan Refund | id (PK), cancel_before_hours, refund_percentage, description |
| 9 | payment_history | Riwayat Pembayaran | id (PK), user_id (FK), transaction_type, reference_id, amount, status, description, created_at |
| 10 | books | Buku | id (PK), title, author, price, cover_image, file_url, stock |
| 11 | orders | Pesanan Buku | id (PK), user_id (FK), book_id (FK), quantity, total_price, status, payment_method |
| 12 | contents | Konten Edukasi | id (PK), uploader_id (FK), uploader_role, title, body, type, approval_status, is_published, published_at |
| 13 | content_approvals | Persetujuan Konten | id (PK), content_id (FK), admin_id (FK), status, notes, processed_at |
| 14 | progress_tracker | Progress Tracker | id (PK), user_id (FK), quit_date, streak_days, cigarettes_avoided, money_saved, last_check_in |
| 15 | daily_check_ins | Check-In Harian | id (PK), user_id (FK), check_in_date, is_smoke_free, notification_id (FK), created_at |
| 16 | notifications | Notifikasi | id (PK), user_id (FK), title, message, type, is_read, sent_at |
| 17 | forums | Forum | id (PK), user_id (FK), title, body, views, created_at |
| 18 | forum_replies | Balasan Forum | id (PK), forum_id (FK), user_id (FK), body, created_at |

### 5.2 Relasi Antar Tabel

| Tabel Asal | Kardinalitas | Tabel Tujuan | Keterangan |
|------------|-------------|-------------|------------|
| users | 1 : 1 (opsional) | professionals | User berperan sebagai psikolog/dokter |
| users | 1 : N | appointments | Satu user bisa membuat banyak janji temu |
| users | 1 : 1 | progress_tracker | Setiap user punya satu catatan progress |
| users | 1 : N | daily_check_ins | Satu user punya banyak check-in harian |
| users | 1 : N | notifications | User bisa menerima banyak notifikasi |
| users | 1 : N | orders | Satu user bisa melakukan banyak pembelian |
| users | 1 : N | contents | User/admin bisa unggah banyak konten |
| users | 1 : N | forums | User bisa buat banyak thread forum |
| users | 1 : N | forum_replies | User bisa membalas banyak thread |
| users | 1 : N | payment_history | Semua transaksi user tercatat |
| professionals | 1 : 1 | professional_verifications | Satu data verifikasi per profesional |
| professionals | 1 : N | schedules | Profesional punya banyak slot jadwal |
| professionals | 1 : N | appointments | Profesional terima banyak janji temu |
| schedules | 1 : N | appointments | Satu slot jadwal dirujuk banyak booking |
| appointments | 1 : 1 | consultations | Satu janji temu = satu catatan konsultasi |
| appointments | 1 : 1 | payments | Satu janji temu = satu data pembayaran |
| contents | 1 : 1 (opsional) | content_approvals | Konten user memiliki satu data approval |
| books | 1 : N | orders | Satu buku bisa dipesan banyak kali |
| forums | 1 : N | forum_replies | Satu thread bisa punya banyak balasan |
| notifications | 1 : N (opsional) | daily_check_ins | Notifikasi reminder memicu check-in |
| refund_policies | 1 : N | payments | Satu kebijakan refund untuk banyak pembayaran |

---

## BAB 6 — Alur Aktivitas Pengguna

| Alur | Langkah Utama | Kondisi Khusus |
|------|--------------|----------------|
| Login / Register | Daftar → Verifikasi Email → Login | Jika sudah punya akun, langsung ke login |
| Progress Tracker | Set tanggal berhenti → Terima notifikasi harian → Klik reminder → Jawab 'Ya/Tidak' → Streak update | Streak +1 jika Ya; reset ke 0 jika Tidak. Sistem hitung rokok & uang dihemat otomatis |
| Registrasi Profesional | Daftar → Upload dokumen lisensi → Tunggu approval admin → Aktif sebagai profesional | Profesional tidak bisa terima booking sebelum disetujui admin |
| Beli Buku | Pilih buku → Keranjang → Checkout → Bayar → Unduh | Jika pembayaran gagal, ulangi checkout |
| Konsultasi | Pilih profesional → Pilih jadwal → Booking → Bayar (per jam) → Konfirmasi profesional → Sesi | Jika dibatalkan, refund berjenjang 15%–85% sesuai waktu pembatalan |
| Konten Edukasi | Pilih jenis konten → Baca/Tonton/Simpan | Konten gratis untuk semua user terdaftar |
| Unggah Konten | User unggah artikel → Menunggu persetujuan admin → Dipublikasikan | Admin dapat approve/reject dengan catatan |
| Forum Komunitas | Buat thread / Balas thread orang lain | Admin dapat moderasi dan hapus konten |

---

## BAB 7 — Kesimpulan & Ringkasan

### 7.1 Ringkasan Sistem

| Aspek | Detail |
|-------|--------|
| Nama Sistem | Platform Edukasi Berhenti Merokok |
| Masalah Utama | Minimnya akses edukasi & profesional berhenti merokok secara terpadu |
| Jumlah Aktor | 4 aktor: User, Psikolog, Dokter, Admin |
| Jumlah Fitur Utama | 9 fitur: Buku, Konsultasi, Jadwal, Konten, Progress Tracker, Forum, Notifikasi, Pembayaran, Penyedia Konten User |
| Jumlah Tabel DB | 18 tabel utama dengan relasi lengkap |
| Mode Konsultasi | Hybrid: Online + Offline |
| Pembayaran Konsultasi | Dihitung per jam; refund berjenjang 15%–85% sesuai waktu pembatalan |
| Progress Tracker | Notifikasi harian, konfirmasi 'tidak merokok hari ini?', streak otomatis, stat rokok & uang dihemat |
| Verifikasi Profesional | Registrasi dokter/psikolog harus disetujui admin sebelum aktif |
| Penyedia Konten | User dan admin dapat unggah konten; konten dari user memerlukan persetujuan admin |
| Harga Konten Edukasi | Gratis untuk semua pengguna terdaftar |
| Komunitas | Forum diskusi dengan thread, balasan, dan moderasi admin |

### 7.2 Ikhtisar Tahapan yang Telah Dipenuhi

| No. | Tahapan | Status | Keterangan |
|-----|---------|--------|------------|
| 1 | Analisis Requirement Basis Data | ✅ Terpenuhi | BAB 1 — Identifikasi kebutuhan, permasalahan, tujuan, dan kebutuhan fungsional sistem |
| 2 | Identifikasi Entitas Basis Data | ✅ Terpenuhi | BAB 2 — 18 entitas teridentifikasi dengan peran masing-masing |
| 3 | Penentuan Atribut Setiap Entitas | ✅ Terpenuhi | BAB 3 — Atribut lengkap per tabel dengan PK, FK, tipe data, dan keterangan |
| 4 | Penentuan Kardinalitas dan Relasi | ✅ Terpenuhi | BAB 4 — 21 relasi antar tabel dengan kardinalitas dan aturan relasi yang jelas |
| 5 | Perancangan ERD | ✅ Terpenuhi | BAB 5 — Daftar entitas, atribut, dan relasi lengkap sebagai dasar penggambaran ERD |

---

*Dokumen ini merupakan hasil analisis dan perancangan sistem database Edukasi Berhenti Merokok — mencakup fitur Progress Tracker, Verifikasi Profesional, Pembayaran per Jam, History Transaksi, dan Penyedia Konten.*
