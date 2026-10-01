<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dateTime('handover_at')->nullable()->after('status');
            $table->dateTime('returned_at')->nullable()->after('handover_at');
            $table->foreignId('returned_by')->nullable()->after('returned_at')->constrained('users')->nullOnDelete();

            $table->decimal('start_mileage', 12, 2)->nullable()->after('returned_by');
            $table->decimal('end_mileage', 12, 2)->nullable()->after('start_mileage');
            $table->decimal('used_km', 12, 2)->nullable()->after('end_mileage');
            $table->decimal('included_km', 12, 2)->nullable()->after('used_km');
            $table->decimal('extra_km', 12, 2)->nullable()->after('included_km');
            $table->decimal('extra_km_rate', 12, 2)->default(25)->after('extra_km');
            $table->decimal('extra_km_charge', 12, 2)->default(0)->after('extra_km_rate');
            $table->decimal('final_total', 12, 2)->nullable()->after('extra_km_charge');
        });

        DB::statement("ALTER TABLE bookings MODIFY status ENUM('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE bookings MODIFY payment_method ENUM('pay_now_payhere','pay_now_card','pay_later_bank','pay_at_pickup_cash') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE bookings MODIFY status ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE bookings MODIFY payment_method ENUM('pay_now_card','pay_later_bank','pay_at_pickup_cash') NOT NULL");

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('returned_by');
            $table->dropColumn([
                'handover_at',
                'returned_at',
                'start_mileage',
                'end_mileage',
                'used_km',
                'included_km',
                'extra_km',
                'extra_km_rate',
                'extra_km_charge',
                'final_total',
            ]);
        });
    }
};

