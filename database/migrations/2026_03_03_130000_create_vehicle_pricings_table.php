<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicle_pricings', function (Blueprint $table) {
            $table->id();
            $table->string('make')->nullable();
            $table->string('model');
            $table->unsignedInteger('per_day_km')->default(150);
            $table->decimal('per_day_amount', 12, 2);
            $table->decimal('extra_km_rate', 12, 2)->default(25);
            $table->string('note')->nullable();
            $table->timestamps();
            $table->unique(['make', 'model']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_pricings');
    }
};
