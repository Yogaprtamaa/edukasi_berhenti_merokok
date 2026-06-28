<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Professional;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Helper: jenssegers query builder TIDAK mengonversi Carbon ke tanggal BSON
        // (akan tersimpan sebagai sub-dokumen). Bungkus nilai tanggal sebagai UTCDateTime.
        $dt = fn ($value) => new \MongoDB\BSON\UTCDateTime(
            $value instanceof \DateTimeInterface ? $value : Carbon::parse($value)
        );

        // ── Users ──────────────────────────────────────────────────────────────
        $userData = [
            ['name' => 'Budi Santoso',   'email' => 'budi@test.com',   'birth_date' => '1990-05-12'],
            ['name' => 'Rina Wulandari', 'email' => 'rina@test.com',   'birth_date' => '1995-08-20'],
            ['name' => 'Agus Prasetyo',  'email' => 'agus@test.com',   'birth_date' => '1988-03-07'],
            ['name' => 'Dewi Lestari',   'email' => 'dewi@test.com',   'birth_date' => '1993-11-15'],
            ['name' => 'Hendra Kusuma',  'email' => 'hendra@test.com', 'birth_date' => '1985-07-22'],
        ];

        $createdUsers = [];
        foreach ($userData as $d) {
            $createdUsers[] = User::firstOrCreate(
                ['email' => $d['email']],
                [
                    'name'              => $d['name'],
                    'password'          => Hash::make('Password123!'),
                    'birth_date'        => $d['birth_date'],
                    'role'              => 'user',
                    'is_email_verified' => true,
                ]
            );
        }

        // ── Professionals ──────────────────────────────────────────────────────
        $profData = [
            [
                'name'           => 'dr. Siti Nurhaliza',
                'email'          => 'siti.dr@test.com',
                'type'           => 'dokter',
                'specialization' => 'Dokter Umum — Spesialis Berhenti Merokok',
                'license_number' => 'STR-DU-2024-001',
                'hourly_rate'    => 150000,
                'is_verified'    => true,
            ],
            [
                'name'           => 'Drs. Ahmad Fauzi, M.Psi.',
                'email'          => 'ahmad.psi@test.com',
                'type'           => 'psikolog',
                'specialization' => 'Psikolog Klinis — Adiksi & Perilaku',
                'license_number' => 'SIPP-2024-045',
                'hourly_rate'    => 200000,
                'is_verified'    => true,
            ],
            [
                'name'           => 'dr. Maya Indah',
                'email'          => 'maya.dr@test.com',
                'type'           => 'dokter',
                'specialization' => 'Dokter Umum',
                'license_number' => 'STR-DU-2024-078',
                'hourly_rate'    => 120000,
                'is_verified'    => false,
            ],
        ];

        $createdProfessionals = [];
        foreach ($profData as $d) {
            $user = User::firstOrCreate(
                ['email' => $d['email']],
                [
                    'name'              => $d['name'],
                    'password'          => Hash::make('Password123!'),
                    'role'              => 'professional',
                    'is_email_verified' => true,
                ]
            );

            $prof = Professional::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'type'           => $d['type'],
                    'specialization' => $d['specialization'],
                    'license_number' => $d['license_number'],
                    'document_url'   => 'documents/dummy.pdf',
                    'is_verified'    => $d['is_verified'],
                    'verified_at'    => $d['is_verified'] ? Carbon::now()->subDays(10) : null,
                    'hourly_rate'    => $d['hourly_rate'],
                ]
            );

            $createdProfessionals[] = $prof;
        }

        // ── Schedules (actual columns: professional_id, day_of_week, start_time, end_time, is_available) ──
        // day_of_week: 0=Minggu, 1=Senin, 2=Selasa, 3=Rabu, 4=Kamis, 5=Jumat, 6=Sabtu
        $scheduleSets = [
            [
                $createdProfessionals[0]->id,
                [
                    ['day_of_week' => 1, 'start_time' => '09:00', 'end_time' => '12:00', 'mode' => 'online'],
                    ['day_of_week' => 3, 'start_time' => '13:00', 'end_time' => '17:00', 'mode' => 'offline'],
                    ['day_of_week' => 5, 'start_time' => '09:00', 'end_time' => '11:00', 'mode' => 'hybrid'],
                ],
            ],
            [
                $createdProfessionals[1]->id,
                [
                    ['day_of_week' => 2, 'start_time' => '10:00', 'end_time' => '14:00', 'mode' => 'online'],
                    ['day_of_week' => 4, 'start_time' => '14:00', 'end_time' => '17:00', 'mode' => 'online'],
                ],
            ],
        ];

        $scheduleIds = [];
        foreach ($scheduleSets as [$profId, $slots]) {
            foreach ($slots as $slot) {
                $existing = DB::table('schedules')
                    ->where('professional_id', $profId)
                    ->where('day_of_week', $slot['day_of_week'])
                    ->first();

                if (!$existing) {
                    // cast ke string agar foreign key konsisten dengan model Eloquent
                    $id = (string) DB::table('schedules')->insertGetId([
                        'professional_id' => $profId,
                        'day_of_week'     => $slot['day_of_week'],
                        'start_time'      => $slot['start_time'],
                        'end_time'        => $slot['end_time'],
                        'mode'            => $slot['mode'],
                        'is_available'    => true,
                        'created_at'      => $dt(Carbon::now()),
                        'updated_at'      => $dt(Carbon::now()),
                    ]);
                    $scheduleIds[$profId] = $id;
                } else {
                    $scheduleIds[$profId] = $existing->id;
                }
            }
        }

        // ── Appointments (actual columns: user_id, professional_id, appointment_date, status, notes) ──
        if (count($createdUsers) >= 2) {
            $apptData = [
                [
                    'user_id'          => $createdUsers[0]->id,
                    'professional_id'  => $createdProfessionals[0]->id,
                    'schedule_id'      => $scheduleIds[$createdProfessionals[0]->id] ?? null,
                    'appointment_date' => $dt(Carbon::now()->addDays(3)),
                    'mode'             => 'online',
                    'status'           => 'confirmed',
                    'notes'            => 'Konsultasi pertama untuk program berhenti merokok',
                ],
                [
                    'user_id'          => $createdUsers[1]->id,
                    'professional_id'  => $createdProfessionals[0]->id,
                    'schedule_id'      => $scheduleIds[$createdProfessionals[0]->id] ?? null,
                    'appointment_date' => $dt(Carbon::now()->addDays(5)),
                    'mode'             => 'offline',
                    'status'           => 'pending',
                    'notes'            => null,
                ],
                [
                    'user_id'          => $createdUsers[2]->id,
                    'professional_id'  => $createdProfessionals[1]->id,
                    'schedule_id'      => $scheduleIds[$createdProfessionals[1]->id] ?? null,
                    'appointment_date' => $dt(Carbon::now()->addDays(7)),
                    'mode'             => 'online',
                    'status'           => 'pending',
                    'notes'            => 'Sesi terapi kognitif perilaku',
                ],
            ];

            foreach ($apptData as $a) {
                $exists = DB::table('appointments')
                    ->where('user_id', $a['user_id'])
                    ->where('professional_id', $a['professional_id'])
                    ->exists();
                if (!$exists) {
                    DB::table('appointments')->insert(array_merge($a, [
                        'created_at' => $dt(Carbon::now()),
                        'updated_at' => $dt(Carbon::now()),
                    ]));
                }
            }
        }

        // ── Contents (actual columns: title, description, body, type, category, thumbnail_url, is_published, published_at) ──
        $contents = [
            [
                'title'       => '7 Langkah Efektif Berhenti Merokok Tanpa Tersiksa',
                'type'        => 'artikel',
                'description' => 'Panduan praktis dengan 7 langkah yang telah terbukti efektif untuk berhenti merokok.',
                'body'        => "Berhenti merokok adalah salah satu keputusan terbaik yang bisa Anda buat untuk kesehatan Anda. Berikut adalah 7 langkah yang telah terbukti efektif:\n\n1. Tetapkan tanggal berhenti yang spesifik\nPilih tanggal dalam waktu dua minggu ke depan. Ini memberi Anda cukup waktu untuk bersiap.\n\n2. Identifikasi pemicu Anda\nCatat situasi, perasaan, atau tempat yang membuat Anda ingin merokok.\n\n3. Minta dukungan orang-orang terdekat\nBeritahu keluarga dan teman tentang rencana Anda.\n\n4. Pertimbangkan terapi pengganti nikotin\nNRT seperti koyo atau permen karet nikotin dapat membantu mengurangi gejala putus nikotin.\n\n5. Ubah rutinitas Anda\nGanti kebiasaan merokok dengan aktivitas lain seperti berjalan kaki atau minum air.\n\n6. Tetap aktif secara fisik\nOlahraga membantu mengurangi keinginan merokok dan meningkatkan mood.\n\n7. Beri penghargaan pada diri sendiri\nRayakan setiap pencapaian kecil dalam perjalananmu.",
            ],
            [
                'title'       => 'Dampak Nikotin pada Otak: Mengapa Berhenti Merokok Begitu Sulit',
                'type'        => 'artikel',
                'description' => 'Penjelasan ilmiah tentang bagaimana nikotin bekerja di otak dan mengapa kecanduan terjadi.',
                'body'        => "Nikotin adalah zat adiktif yang bekerja langsung pada sistem reward otak.\n\nBagaimana Nikotin Bekerja\n\nKetika Anda menghisap rokok, nikotin diserap ke dalam aliran darah dengan sangat cepat dan mencapai otak dalam waktu 10 detik. Di sana, nikotin memicu pelepasan dopamin yang memberikan perasaan senang dan puas.\n\nSiklus Ketergantungan\n\nSeiring waktu, otak menyesuaikan diri dengan kehadiran nikotin secara terus-menerus. Inilah mengapa gejala putus nikotin terasa sangat tidak nyaman.\n\nHarapan di Ujung Terowongan\n\nKabar baiknya, otak memiliki kemampuan luar biasa untuk pulih. Setelah berhenti merokok, otak secara bertahap akan kembali normal dalam beberapa minggu hingga bulan.",
            ],
            [
                'title'       => 'Konsultasi dengan Psikolog: Cara Ampuh Atasi Kecanduan Rokok',
                'type'        => 'video',
                'description' => 'Video konsultasi dengan psikolog klinis tentang pendekatan psikologis untuk berhenti merokok.',
                'body'        => "Dalam sesi konsultasi ini, psikolog klinis berbagi insight tentang pendekatan psikologis untuk mengatasi kecanduan rokok.\n\nPoin-poin utama:\n• Memahami pola pikir yang mempertahankan kecanduan\n• Teknik Cognitive Behavioral Therapy (CBT) untuk berhenti merokok\n• Mindfulness sebagai alat mengelola keinginan merokok\n• Strategi relapse prevention\n• Kapan sebaiknya mempertimbangkan bantuan profesional",
            ],
            [
                'title'       => 'Manfaat Kesehatan 24 Jam Pertama Setelah Berhenti Merokok',
                'type'        => 'infografis',
                'description' => 'Infografis tentang perubahan tubuh yang terjadi dalam 24 jam setelah berhenti merokok.',
                'body'        => "Tubuh Anda mulai pulih hanya dalam hitungan menit setelah rokok terakhir:\n\n20 menit: Tekanan darah dan detak jantung kembali normal\n8 jam: Kadar karbon monoksida turun 50%\n24 jam: Risiko serangan jantung mulai berkurang\n48 jam: Indera penciuman dan perasa mulai membaik\n2-3 minggu: Fungsi paru-paru meningkat hingga 30%",
            ],
            [
                'title'       => 'Mengelola Stres Tanpa Rokok: Teknik Relaksasi yang Terbukti',
                'type'        => 'artikel',
                'description' => 'Panduan teknik relaksasi untuk mengelola stres tanpa bergantung pada rokok.',
                'body'        => "Banyak perokok menggunakan rokok sebagai cara mengelola stres. Ketika berhenti merokok, penting untuk memiliki strategi alternatif.\n\n1. Pernapasan Diafragma\nTarik napas 4 hitungan, tahan 4 hitungan, buang napas 6 hitungan. Ulangi 5-10 kali.\n\n2. Progressive Muscle Relaxation\nTegangi dan rilekskan setiap kelompok otot secara bergantian.\n\n3. Mindfulness Meditation\nLuangkan 10-15 menit sehari untuk berlatih mindfulness.\n\n4. Olahraga Teratur\n30 menit berjalan kaki sehari dapat mengurangi stres dan keinginan merokok.\n\n5. Journaling\nTuliskan perasaan dan pikiran untuk memproses emosi tanpa rokok.",
            ],
        ];

        foreach ($contents as $c) {
            $exists = DB::table('contents')->where('title', $c['title'])->exists();
            if (!$exists) {
                DB::table('contents')->insert([
                    'title'        => $c['title'],
                    'description'  => $c['description'],
                    'body'         => $c['body'],
                    'type'         => $c['type'],
                    'is_published' => true,
                    'published_at' => $dt(Carbon::now()->subDays(rand(1, 30))),
                    'created_at'   => $dt(Carbon::now()),
                    'updated_at'   => $dt(Carbon::now()),
                ]);
            }
        }

        // ── Books (actual columns: title, author, description, price, isbn, cover_url, stock, is_available) ──
        $books = [
            [
                'title'       => 'Bebas Rokok: Panduan Praktis Berhenti Merokok Selamanya',
                'author'      => 'Dr. Hendra Wijaya',
                'description' => 'Panduan komprehensif yang ditulis oleh dokter spesialis untuk berhenti merokok secara permanen.',
                'price'       => 89000,
                'isbn'        => '978-602-123-456-7',
                'stock'       => 50,
            ],
            [
                'title'       => 'Nikotin dan Pikiran: Memahami dan Mengatasi Kecanduan Rokok',
                'author'      => 'Prof. Dr. Sari Dewi, M.Psi.',
                'description' => 'Pendekatan psikologis untuk memahami mengapa kita merokok dan cara keluar dari jeratnya.',
                'price'       => 75000,
                'isbn'        => '978-602-789-012-3',
                'stock'       => 30,
            ],
            [
                'title'       => '100 Hari Tanpa Rokok: Jurnal Perjalananmu',
                'author'      => 'Tim BerhentiMerokok',
                'description' => 'Buku jurnal interaktif dengan panduan harian selama 100 hari pertama berhenti merokok.',
                'price'       => 65000,
                'isbn'        => '978-602-345-678-9',
                'stock'       => 100,
            ],
            [
                'title'       => 'Gaya Hidup Sehat: Dari Perokok Aktif Menjadi Bebas Asap',
                'author'      => 'Ratna Kumala, S.Gz.',
                'description' => 'Panduan gaya hidup holistik termasuk nutrisi dan olahraga untuk mendukung proses berhenti merokok.',
                'price'       => 98000,
                'isbn'        => '978-602-901-234-5',
                'stock'       => 25,
            ],
            [
                'title'       => 'Merokok: Fakta, Mitos, dan Cara Berhenti',
                'author'      => 'Dr. Bambang Susanto, Sp.P.',
                'description' => 'Buku berbasis bukti ilmiah yang membongkar mitos seputar merokok.',
                'price'       => 120000,
                'isbn'        => '978-602-567-890-1',
                'stock'       => 15,
            ],
        ];

        foreach ($books as $b) {
            $exists = DB::table('books')->where('isbn', $b['isbn'])->exists();
            if (!$exists) {
                DB::table('books')->insert(array_merge($b, [
                    'is_available' => true,
                    'created_at'   => $dt(Carbon::now()),
                    'updated_at'   => $dt(Carbon::now()),
                ]));
            }
        }

        // ── Forums (actual columns: user_id, title, content, category, views_count, replies_count) ──
        if (count($createdUsers) >= 4) {
            $forumData = [
                [
                    'user'         => $createdUsers[0],
                    'title'        => 'Hari ke-30 berhenti merokok — berbagi pengalaman!',
                    'content'      => "Halo semua! Hari ini genap 30 hari saya tidak merokok. Rasanya luar biasa!\n\nAwal-awal memang sangat berat, terutama di minggu pertama. Tapi dengan dukungan keluarga dan komunitas ini, saya bisa melewatinya.\n\nTips yang paling membantu:\n- Minum air putih banyak-banyak saat keinginan merokok muncul\n- Olahraga ringan di pagi hari\n- Menghitung uang yang sudah dihemat (motivasi banget!)\n\nSemangat untuk semua yang sedang berjuang!",
                    'views_count'  => 127,
                    'replies'      => [
                        [$createdUsers[1], "Selamat! Pencapaian luar biasa! Saya juga sedang di hari ke-15. Semoga kita bisa terus kuat ya!"],
                        [$createdUsers[2], "Wah inspiratif sekali. Tips minum air putihnya memang manjur. Terus semangat!"],
                        [$createdUsers[3], "Amazing! 30 hari adalah milestone luar biasa. Apa yang paling susah di awal-awal?"],
                    ],
                ],
                [
                    'user'         => $createdUsers[1],
                    'title'        => 'Cara mengatasi keinginan merokok saat stres kerja?',
                    'content'      => "Teman-teman, saya butuh saran. Saya sudah 2 minggu tidak merokok, tapi hari ini ada deadline kerja yang mepet dan keinginan merokok jadi sangat kuat.\n\nBiasanya kalau stres saya pasti nyalakan rokok. Sekarang saya bingung mau ngapain.\n\nAda yang punya tips untuk mengatasi keinginan merokok saat sedang stres?",
                    'views_count'  => 89,
                    'replies'      => [
                        [$createdUsers[0], "Coba pernapasan 4-7-8: hirup napas 4 detik, tahan 7 detik, buang 8 detik. Lakukan 3-4 kali. Ampuh banget!"],
                        [$createdUsers[4], "Saya biasanya minum teh herbal atau kunyah permen karet. Gerak badan sebentar juga membantu mengalihkan pikiran."],
                    ],
                ],
                [
                    'user'         => $createdUsers[2],
                    'title'        => 'Rekomendasi aplikasi tracking berhenti merokok',
                    'content'      => "Selain aplikasi ini, ada yang punya rekomendasi aplikasi lain untuk tracking progres berhenti merokok?\n\nSaya senang lihat angka-angka seperti berapa rokok yang sudah dihindari dan uang yang dihemat. Motivasi banget!\n\nShare pengalaman kalian ya.",
                    'views_count'  => 63,
                    'replies'      => [
                        [$createdUsers[3], "Ada aplikasi Smoke Free yang bagus. Bisa lihat perbaikan kesehatan dari waktu ke waktu juga."],
                    ],
                ],
                [
                    'user'         => $createdUsers[3],
                    'title'        => 'Pengalaman konsultasi dengan psikolog untuk berhenti merokok',
                    'content'      => "Kemarin saya baru selesai sesi pertama konsultasi dengan psikolog melalui platform ini. Alhamdulillah sangat membantu!\n\nPsikolognya membantu saya mengidentifikasi pemicu-pemicu yang membuat saya merokok.\n\nBagi yang masih ragu untuk konsultasi, saya rekomendasikan untuk mencoba. Perspektif profesional benar-benar membantu!",
                    'views_count'  => 45,
                    'replies'      => [
                        [$createdUsers[0], "Makasih sharingnya! Saya juga sedang mempertimbangkan untuk konsultasi. Kira-kira berapa sesi yang dibutuhkan?"],
                        [$createdUsers[1], "Inspiring! Kecanduan rokok memang tidak hanya fisik tapi juga mental."],
                    ],
                ],
                [
                    'user'         => $createdUsers[4],
                    'title'        => 'Olahraga apa yang paling efektif saat berhenti merokok?',
                    'content'      => "Dokter saya menyarankan untuk mulai olahraga sebagai bagian dari program berhenti merokok. Katanya olahraga bisa mengurangi keinginan merokok.\n\nSaya belum pernah olahraga rutin dan kapasitas paru-paru saya masih agak terbatas karena dulu sering merokok.\n\nAda rekomendasi jenis olahraga untuk pemula yang baru berhenti merokok?",
                    'views_count'  => 72,
                    'replies'      => [
                        [$createdUsers[0], "Mulai dari jalan kaki 20-30 menit sehari. Ringan tapi efektif. Nanti bisa ditingkatkan ke jogging."],
                        [$createdUsers[2], "Yoga sangat bagus! Fokus pada pernapasan juga membantu kapasitas paru-paru membaik."],
                        [$createdUsers[3], "Berenang katanya juga bagus untuk paru-paru. Tapi memang perlu dimulai perlahan-lahan."],
                    ],
                ],
            ];

            foreach ($forumData as $fd) {
                $exists = DB::table('forums')->where('title', $fd['title'])->first();
                if (!$exists) {
                    // cast ke string agar forum_id konsisten dengan model Eloquent
                    $forumId = (string) DB::table('forums')->insertGetId([
                        'user_id'       => $fd['user']->id,
                        'title'         => $fd['title'],
                        'content'       => $fd['content'],
                        'views_count'   => $fd['views_count'],
                        'replies_count' => count($fd['replies']),
                        'created_at'    => $dt(Carbon::now()->subDays(rand(1, 20))),
                        'updated_at'    => $dt(Carbon::now()),
                    ]);

                    foreach ($fd['replies'] as [$replyUser, $replyBody]) {
                        DB::table('forum_replies')->insert([
                            'forum_id'    => $forumId,
                            'user_id'     => $replyUser->id,
                            'content'     => $replyBody,
                            'likes_count' => 0,
                            'created_at'  => $dt(Carbon::now()->subHours(rand(1, 48))),
                            'updated_at'  => $dt(Carbon::now()),
                        ]);
                    }
                }
            }
        }

        // ── Progress Trackers (actual columns: user_id, total_rokok_dihindari, total_uang_dihemat, streak_hari) ──
        $trackerData = [
            [$createdUsers[0] ?? null, 360, 540000, 30],
            [$createdUsers[1] ?? null, 150, 225000, 15],
            [$createdUsers[2] ?? null, 140, 210000, 7],
        ];

        foreach ($trackerData as [$user, $rokok, $uang, $streak]) {
            if ($user) {
                $exists = DB::table('progress_trackers')->where('user_id', $user->id)->exists();
                if (!$exists) {
                    DB::table('progress_trackers')->insert([
                        'user_id'            => $user->id,
                        'quit_date'          => $dt(Carbon::now()->subDays($streak)),
                        'streak_days'        => $streak,
                        'cigarettes_per_day' => 12,
                        'cigarettes_avoided' => $rokok,
                        'money_saved'        => $uang,
                        'last_check_in'      => $dt(Carbon::now()),
                        'created_at'         => $dt(Carbon::now()),
                        'updated_at'         => $dt(Carbon::now()),
                    ]);
                }
            }
        }

        // ── Notifications (actual columns: user_id, title, message, type, is_read, read_at) ──
        if (count($createdUsers) >= 2) {
            $notifData = [
                [
                    'user'    => $createdUsers[0],
                    'title'   => 'Selamat! Streak 30 hari tercapai!',
                    'message' => 'Luar biasa! Kamu sudah tidak merokok selama 30 hari. Kamu telah menghemat Rp540.000 dan menghindari 360 batang rokok. Tetap semangat!',
                    'type'    => 'achievement',
                    'is_read' => true,
                    'read_at' => Carbon::now()->subHours(2),
                ],
                [
                    'user'    => $createdUsers[0],
                    'title'   => 'Janji temu dikonfirmasi',
                    'message' => 'Janji konsultasi Anda dengan dr. Siti Nurhaliza telah dikonfirmasi. Jangan lupa untuk hadir tepat waktu.',
                    'type'    => 'appointment',
                    'is_read' => false,
                    'read_at' => null,
                ],
                [
                    'user'    => $createdUsers[1],
                    'title'   => 'Jangan lupa check-in hari ini!',
                    'message' => 'Halo Rina! Kamu belum melakukan check-in hari ini. Tetap semangat dalam perjalanan berhenti merokokmu!',
                    'type'    => 'reminder',
                    'is_read' => false,
                    'read_at' => null,
                ],
                [
                    'user'    => $createdUsers[1],
                    'title'   => 'Konten baru tersedia',
                    'message' => 'Artikel "7 Langkah Efektif Berhenti Merokok" sudah tersedia. Baca sekarang untuk mendapatkan tips yang terbukti efektif.',
                    'type'    => 'content',
                    'is_read' => true,
                    'read_at' => Carbon::now()->subDays(1),
                ],
            ];

            foreach ($notifData as $n) {
                $exists = DB::table('notifications')
                    ->where('user_id', $n['user']->id)
                    ->where('title', $n['title'])
                    ->exists();
                if (!$exists) {
                    DB::table('notifications')->insert([
                        'user_id'    => $n['user']->id,
                        'title'      => $n['title'],
                        'message'    => $n['message'],
                        'type'       => $n['type'],
                        'is_read'    => $n['is_read'],
                        'read_at'    => $n['read_at'] ? $dt($n['read_at']) : null,
                        'created_at' => $dt(Carbon::now()->subHours(rand(1, 48))),
                        'updated_at' => $dt(Carbon::now()),
                    ]);
                }
            }
        }

        $this->command->info('✓ Data dummy berhasil dibuat!');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['User',         'budi@test.com',      'Password123!'],
                ['User',         'rina@test.com',      'Password123!'],
                ['User',         'agus@test.com',      'Password123!'],
                ['User',         'dewi@test.com',      'Password123!'],
                ['User',         'hendra@test.com',    'Password123!'],
                ['Professional', 'siti.dr@test.com',   'Password123! (Terverifikasi)'],
                ['Professional', 'ahmad.psi@test.com', 'Password123! (Terverifikasi)'],
                ['Professional', 'maya.dr@test.com',   'Password123! (Belum Terverifikasi)'],
            ]
        );
    }
}
