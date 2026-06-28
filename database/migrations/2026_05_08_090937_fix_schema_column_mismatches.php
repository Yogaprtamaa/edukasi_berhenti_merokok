<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MongoDB schemaless: penambahan kolom (schedule_id, mode, sent_at,
        // appointment_id, duration_hours, is_smoke_free) dan perubahan tipe
        // tidak diperlukan — field dibuat per-dokumen oleh seeder/model.
        // Migration ini sengaja dikosongkan untuk MongoDB.
    }

    public function down(): void
    {
        //
    }
};
