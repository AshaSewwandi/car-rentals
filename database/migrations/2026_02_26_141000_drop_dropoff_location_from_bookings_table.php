<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('bookings', 'dropoff_location')) {
            return;
        }

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('dropoff_location');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('bookings', 'dropoff_location')) {
            return;
        }

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('dropoff_location', 255)->nullable()->after('pickup_location');
        });
    }
};
