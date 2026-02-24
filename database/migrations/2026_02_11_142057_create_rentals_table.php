<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();

            $table->date('start_date');
            $table->date('end_date')->nullable(); // if rental ended
            $table->unsignedTinyInteger('due_day')->default(5); // 1..28 recommended
            $table->decimal('monthly_rent', 12, 2);
            $table->decimal('deposit', 12, 2)->default(0);

            $table->enum('status', ['active', 'ended'])->default('active');
            $table->text('note')->nullable();

            $table->timestamps();

            $table->index(['status', 'car_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('rentals');
    }
};
