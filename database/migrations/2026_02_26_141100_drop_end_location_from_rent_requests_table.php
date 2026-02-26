<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('rent_requests', 'end_location')) {
            return;
        }

        Schema::table('rent_requests', function (Blueprint $table) {
            $table->dropColumn('end_location');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('rent_requests', 'end_location')) {
            return;
        }

        Schema::table('rent_requests', function (Blueprint $table) {
            $table->string('end_location', 255)->nullable()->after('start_location');
        });
    }
};
