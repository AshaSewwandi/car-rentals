<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->decimal('latest_latitude', 10, 7)->nullable()->after('tracker_maintenance_mileage');
            $table->decimal('latest_longitude', 10, 7)->nullable()->after('latest_latitude');
            $table->decimal('latest_speed', 8, 2)->nullable()->after('latest_longitude');
            $table->unsignedSmallInteger('latest_course')->nullable()->after('latest_speed');
            $table->dateTime('tracker_last_seen_at')->nullable()->after('latest_course');
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn([
                'latest_latitude',
                'latest_longitude',
                'latest_speed',
                'latest_course',
                'tracker_last_seen_at',
            ]);
        });
    }
};
