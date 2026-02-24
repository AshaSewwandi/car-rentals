<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->cascadeOnDelete();

            $table->string('month', 7); // YYYY-MM
            $table->date('due_date');
            $table->decimal('amount', 12, 2);

            $table->date('paid_date')->nullable();
            $table->decimal('paid_amount', 12, 2)->nullable();
            $table->enum('method', ['cash', 'bank', 'online'])->nullable();

            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->timestamps();

            $table->unique(['rental_id', 'month']);
            $table->index(['status', 'due_date']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('payments');
    }
};
