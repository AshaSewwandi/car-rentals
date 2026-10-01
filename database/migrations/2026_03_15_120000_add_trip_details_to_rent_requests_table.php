<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rent_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('rent_requests', 'passenger_count')) {
                $table->unsignedSmallInteger('passenger_count')->nullable()->after('email');
            }
            if (!Schema::hasColumn('rent_requests', 'final_destination')) {
                $table->string('final_destination', 255)->nullable()->after('start_location');
            }
            if (!Schema::hasColumn('rent_requests', 'stops')) {
                $table->json('stops')->nullable()->after('final_destination');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rent_requests', function (Blueprint $table) {
            foreach (['stops', 'final_destination', 'passenger_count'] as $column) {
                if (Schema::hasColumn('rent_requests', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
