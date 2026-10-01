<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
            $table->string('customer_name', 120);
            $table->string('customer_email', 180)->nullable();
            $table->string('customer_phone', 40)->nullable();
            $table->string('pickup_location', 255)->nullable();
            $table->string('dropoff_location', 255)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('rental_days');
            $table->decimal('daily_rate', 12, 2);
            $table->decimal('total_amount', 12, 2);
            $table->string('currency', 8)->default('LKR');
            $table->enum('payment_method', ['pay_now_card', 'pay_later_bank', 'pay_at_pickup_cash']);
            $table->string('payment_provider', 50)->nullable();
            $table->string('gateway_reference', 120)->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['car_id', 'start_date', 'end_date']);
            $table->index(['status', 'payment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

