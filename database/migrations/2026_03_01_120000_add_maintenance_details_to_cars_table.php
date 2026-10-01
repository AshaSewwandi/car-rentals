<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->date('maintenance_last_service_date')->nullable()->after('tracker_maintenance_mileage');
            $table->unsignedInteger('maintenance_last_service_mileage')->nullable()->after('maintenance_last_service_date');
            $table->date('maintenance_next_service_date')->nullable()->after('maintenance_last_service_mileage');
            $table->text('maintenance_note')->nullable()->after('maintenance_next_service_date');
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn([
                'maintenance_last_service_date',
                'maintenance_last_service_mileage',
                'maintenance_next_service_date',
                'maintenance_note',
            ]);
        });
    }
};
