<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('budget_months', function (Blueprint $table) {
            $table->id();
            $table->char('month', 7)->unique(); // YYYY-MM
            $table->timestamp('touched_at')->nullable();
            $table->timestamps();
        });

        Schema::create('budget_incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_month_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('amount', 12, 2)->default(0);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('budget_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_month_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('budget_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_group_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('emoji', 16)->nullable();
            $table->decimal('budget', 12, 2)->default(0);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('budget_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_month_id')->constrained()->cascadeOnDelete();
            $table->foreignId('budget_category_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date');
            $table->decimal('amount', 12, 2);
            $table->string('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['budget_month_id', 'date']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('budget_entries');
        Schema::dropIfExists('budget_categories');
        Schema::dropIfExists('budget_groups');
        Schema::dropIfExists('budget_incomes');
        Schema::dropIfExists('budget_months');
    }
};
