<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE daily_check_ins MODIFY cigarettes_avoided INT NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE daily_check_ins MODIFY money_saved DECIMAL(10, 2) NOT NULL DEFAULT 0.00');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE daily_check_ins MODIFY cigarettes_avoided INT NOT NULL');
        DB::statement('ALTER TABLE daily_check_ins MODIFY money_saved DECIMAL(10, 2) NOT NULL');
    }
};
