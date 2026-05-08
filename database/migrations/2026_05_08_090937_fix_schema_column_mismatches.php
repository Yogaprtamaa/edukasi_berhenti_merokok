<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // contents, progress_trackers, forums, forum_replies sudah diperbaiki
        // sebelum migration gagal — lanjutkan dari schedules ke bawah

        // ── schedules: hapus data lama (day_of_week string), ubah ke int, tambah mode ──
        DB::table('schedules')->truncate();
        DB::statement('ALTER TABLE schedules MODIFY day_of_week TINYINT UNSIGNED NOT NULL DEFAULT 0');
        Schema::table('schedules', function (Blueprint $table) {
            $table->enum('mode', ['online', 'offline', 'hybrid'])->default('online')->after('end_time');
        });

        // ── appointments: tambah schedule_id + mode ───────────────────────────
        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('schedule_id')->nullable()->after('professional_id');
            $table->string('mode', 20)->default('online')->after('schedule_id');
        });

        // ── notifications: tambah sent_at ─────────────────────────────────────
        Schema::table('notifications', function (Blueprint $table) {
            $table->timestamp('sent_at')->nullable()->after('is_read');
        });
        DB::statement('UPDATE notifications SET sent_at = created_at');

        // ── payments: tambah appointment_id + duration_hours ─────────────────
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('appointment_id')->nullable()->after('id');
            $table->decimal('duration_hours', 5, 2)->default(1)->after('amount');
        });

        // ── daily_check_ins: tambah is_smoke_free ─────────────────────────────
        Schema::table('daily_check_ins', function (Blueprint $table) {
            $table->boolean('is_smoke_free')->default(true)->after('check_in_date');
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('mode');
        });
        DB::statement('ALTER TABLE schedules MODIFY day_of_week VARCHAR(20) NOT NULL');
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['schedule_id', 'mode']);
        });
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('sent_at');
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['appointment_id', 'duration_hours']);
        });
        Schema::table('daily_check_ins', function (Blueprint $table) {
            $table->dropColumn('is_smoke_free');
        });
    }
};
