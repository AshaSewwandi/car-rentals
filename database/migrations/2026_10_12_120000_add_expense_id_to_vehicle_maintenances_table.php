<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Links a maintenance record to the expense it was created from (null = entered by hand).
        Schema::table('vehicle_maintenances', function (Blueprint $table) {
            $table->foreignId('expense_id')->nullable()->unique()->after('vehicle_id')->constrained('expenses')->cascadeOnDelete();
        });
    }
    public function down(): void {
        Schema::table('vehicle_maintenances', function (Blueprint $table) {
            $table->dropConstrainedForeignId('expense_id');
        });
    }
};
