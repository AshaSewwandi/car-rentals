<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vehicle_pricings', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicle_pricings', 'driver_cost_per_day')) {
                $table->decimal('driver_cost_per_day', 12, 2)->default(0)->after('extra_km_rate');
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'driver_option')) {
                $table->enum('driver_option', ['without_driver', 'with_driver'])->default('without_driver')->after('rental_days');
            }
            if (!Schema::hasColumn('bookings', 'driver_rate')) {
                $table->decimal('driver_rate', 12, 2)->default(0)->after('daily_rate');
            }
            if (!Schema::hasColumn('bookings', 'driver_total')) {
                $table->decimal('driver_total', 12, 2)->default(0)->after('total_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_pricings', function (Blueprint $table) {
            if (Schema::hasColumn('vehicle_pricings', 'driver_cost_per_day')) {
                $table->dropColumn('driver_cost_per_day');
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            $columns = [];
            foreach (['driver_option', 'driver_rate', 'driver_total'] as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $columns[] = $column;
                }
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
