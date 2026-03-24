<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'customer_id')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('customer_id')
                ->nullable()
                ->after('role')
                ->constrained('customers')
                ->nullOnDelete();
            $table->index(['customer_id', 'role']);
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('users', 'customer_id')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['customer_id', 'role']);
            $table->dropConstrainedForeignId('customer_id');
        });
    }
};
