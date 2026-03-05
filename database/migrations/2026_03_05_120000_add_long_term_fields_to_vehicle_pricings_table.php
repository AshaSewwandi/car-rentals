<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vehicle_pricings', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicle_pricings', 'per_month_amount')) {
                $table->decimal('per_month_amount', 12, 2)->nullable()->after('per_day_amount');
            }

            if (!Schema::hasColumn('vehicle_pricings', 'driver_cost_per_month')) {
                $table->decimal('driver_cost_per_month', 12, 2)->default(0)->after('driver_cost_per_day');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_pricings', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('vehicle_pricings', 'per_month_amount')) {
                $columns[] = 'per_month_amount';
            }
            if (Schema::hasColumn('vehicle_pricings', 'driver_cost_per_month')) {
                $columns[] = 'driver_cost_per_month';
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
