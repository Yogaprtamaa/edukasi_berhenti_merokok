<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MongoDB schemaless: nilai default kolom diatur oleh model/seeder,
        // tidak ada ALTER TABLE. Migration ini sengaja dikosongkan.
    }

    public function down(): void
    {
        //
    }
};
