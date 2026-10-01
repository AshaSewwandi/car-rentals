<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            if (!Schema::hasColumn('cars', 'driver_mode')) {
                $table->enum('driver_mode', ['both', 'with_driver_only', 'without_driver_only'])
                    ->default('both')
                    ->after('transmission');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            if (Schema::hasColumn('cars', 'driver_mode')) {
                $table->dropColumn('driver_mode');
            }
        });
    }
};
