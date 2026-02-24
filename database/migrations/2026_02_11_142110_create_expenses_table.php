<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->cascadeOnDelete();

            $table->date('date');
            $table->enum('type', ['service', 'repair', 'insurance', 'license', 'tyre', 'other'])->default('other');
            $table->decimal('amount', 12, 2);
            $table->string('note')->nullable();

            $table->timestamps();

            $table->index(['car_id', 'date']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('expenses');
    }
};
