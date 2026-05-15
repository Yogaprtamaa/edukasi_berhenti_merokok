<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pertemuan 6 — Query Optimization
 * Menambahkan index pada kolom yang sering digunakan di klausa WHERE, ORDER BY,
 * dan JOIN untuk mempercepat eksekusi query (soft parse / index scan).
 */
return new class extends Migration
{
    public function up(): void
    {
        // appointments: filter by status & order by appointment_date
        Schema::table('appointments', function (Blueprint $table) {
            $table->index('status', 'idx_appointments_status');
            $table->index('appointment_date', 'idx_appointments_date');
        });

        // payments: filter by status, order by paid_at
        Schema::table('payments', function (Blueprint $table) {
            $table->index('status', 'idx_payments_status');
            $table->index('paid_at', 'idx_payments_paid_at');
        });

        // orders: filter by status
        Schema::table('orders', function (Blueprint $table) {
            $table->index('status', 'idx_orders_status');
        });

        // contents: filter by approval_status & is_published
        Schema::table('contents', function (Blueprint $table) {
            $table->index('approval_status', 'idx_contents_approval_status');
            $table->index('is_published', 'idx_contents_is_published');
        });

        // professionals: filter by is_verified (admin dashboard)
        Schema::table('professionals', function (Blueprint $table) {
            $table->index('is_verified', 'idx_professionals_is_verified');
        });

        // daily_check_ins: filter by check_in_date (streak calculation)
        Schema::table('daily_check_ins', function (Blueprint $table) {
            $table->index('check_in_date', 'idx_daily_check_ins_date');
        });

        // users: filter by role (admin user management)
        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'idx_users_role');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex('idx_appointments_status');
            $table->dropIndex('idx_appointments_date');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('idx_payments_status');
            $table->dropIndex('idx_payments_paid_at');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_status');
        });

        Schema::table('contents', function (Blueprint $table) {
            $table->dropIndex('idx_contents_approval_status');
            $table->dropIndex('idx_contents_is_published');
        });

        Schema::table('professionals', function (Blueprint $table) {
            $table->dropIndex('idx_professionals_is_verified');
        });

        Schema::table('daily_check_ins', function (Blueprint $table) {
            $table->dropIndex('idx_daily_check_ins_date');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_role');
        });
    }
};
