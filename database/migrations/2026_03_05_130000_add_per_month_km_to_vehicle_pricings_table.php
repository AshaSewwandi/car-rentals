<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vehicle_pricings', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicle_pricings', 'per_month_km')) {
                $table->unsignedInteger('per_month_km')->nullable()->after('per_month_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_pricings', function (Blueprint $table) {
            if (Schema::hasColumn('vehicle_pricings', 'per_month_km')) {
                $table->dropColumn('per_month_km');
            }
        });
    }
};
