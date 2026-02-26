<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rent_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->nullable()->constrained('cars')->nullOnDelete();
            $table->string('car_name', 180)->nullable();
            $table->string('plate_no', 100)->nullable();
            $table->string('name', 120);
            $table->string('phone', 40)->nullable();
            $table->string('email', 180)->nullable();
            $table->string('start_location', 255)->nullable();
            $table->string('end_location', 255)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'accepted'])->default('pending');
            $table->foreignId('accepted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rent_requests');
    }
};

