<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('partner_share_percentage', 5, 2)->default(80)->after('role');
            $table->decimal('admin_share_percentage', 5, 2)->default(20)->after('partner_share_percentage');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('partner_share_percentage', 5, 2)->nullable()->after('final_total');
            $table->decimal('admin_share_percentage', 5, 2)->nullable()->after('partner_share_percentage');
            $table->decimal('partner_share_amount', 12, 2)->nullable()->after('admin_share_percentage');
            $table->decimal('admin_share_amount', 12, 2)->nullable()->after('partner_share_amount');
        });

        DB::table('users')
            ->where('role', 'partner')
            ->update([
                'partner_share_percentage' => 80,
                'admin_share_percentage' => 20,
            ]);

        $bookings = DB::table('bookings')
            ->leftJoin('cars', 'cars.id', '=', 'bookings.car_id')
            ->leftJoin('users as partners', 'partners.id', '=', 'cars.partner_user_id')
            ->select(
                'bookings.id',
                'bookings.final_total',
                'bookings.total_amount',
                'bookings.driver_total',
                'cars.partner_user_id',
                'partners.partner_share_percentage',
                'partners.admin_share_percentage'
            )
            ->get();

        foreach ($bookings as $booking) {
            $finalTotal = (float) ($booking->final_total ?? $booking->total_amount ?? 0);
            $driverTotal = (float) ($booking->driver_total ?? 0);
            $shareableAmount = round(max($finalTotal - $driverTotal, 0), 2);
            $partnerPercentage = $booking->partner_user_id ? (float) ($booking->partner_share_percentage ?? 80) : 0.0;
            $adminPercentage = $booking->partner_user_id ? (float) ($booking->admin_share_percentage ?? 20) : 100.0;

            DB::table('bookings')
                ->where('id', $booking->id)
                ->update([
                    'partner_share_percentage' => $partnerPercentage,
                    'admin_share_percentage' => $adminPercentage,
                    'partner_share_amount' => round($shareableAmount * ($partnerPercentage / 100), 2),
                    'admin_share_amount' => round($shareableAmount * ($adminPercentage / 100), 2),
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'partner_share_percentage',
                'admin_share_percentage',
                'partner_share_amount',
                'admin_share_amount',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'partner_share_percentage',
                'admin_share_percentage',
            ]);
        });
    }
};
