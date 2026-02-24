<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE gps_logs MODIFY opening_km DECIMAL(12,2) UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE gps_logs MODIFY closing_km DECIMAL(12,2) UNSIGNED NOT NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE gps_logs ALTER COLUMN opening_km TYPE NUMERIC(12,2)');
            DB::statement('ALTER TABLE gps_logs ALTER COLUMN closing_km TYPE NUMERIC(12,2)');
            return;
        }

        if ($driver === 'sqlite') {
            // SQLite type affinity accepts DECIMAL values without strict alter operations.
            return;
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE gps_logs MODIFY opening_km INT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE gps_logs MODIFY closing_km INT UNSIGNED NOT NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE gps_logs ALTER COLUMN opening_km TYPE INTEGER USING ROUND(opening_km)');
            DB::statement('ALTER TABLE gps_logs ALTER COLUMN closing_km TYPE INTEGER USING ROUND(closing_km)');
            return;
        }
    }
};

