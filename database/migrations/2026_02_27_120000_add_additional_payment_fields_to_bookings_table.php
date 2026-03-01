<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('additional_payment_status', ['not_required', 'pending', 'paid'])
                ->default('not_required')
                ->after('payment_status');
            $table->decimal('additional_payment_amount', 12, 2)
                ->nullable()
                ->after('additional_payment_status');
            $table->timestamp('additional_paid_at')
                ->nullable()
                ->after('additional_payment_amount');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'additional_payment_status',
                'additional_payment_amount',
                'additional_paid_at',
            ]);
        });
    }
};
