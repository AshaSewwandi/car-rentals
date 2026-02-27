<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE rent_requests MODIFY status ENUM('pending','accepted','converted') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("UPDATE rent_requests SET status = 'accepted' WHERE status = 'converted'");
        DB::statement("ALTER TABLE rent_requests MODIFY status ENUM('pending','accepted') NOT NULL DEFAULT 'pending'");
    }
};
