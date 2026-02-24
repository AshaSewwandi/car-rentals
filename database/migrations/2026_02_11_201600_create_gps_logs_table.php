<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gps_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->cascadeOnDelete();
            $table->date('log_date');
            $table->unsignedInteger('opening_km');
            $table->unsignedInteger('closing_km');
            $table->string('source')->default('manual');
            $table->string('dagps_ref')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();

            $table->index(['car_id', 'log_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gps_logs');
    }
};
