<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->string('make')->nullable()->after('name');
            $table->string('model')->nullable()->after('make');
            $table->unsignedSmallInteger('year')->nullable()->after('model');
            $table->string('color')->nullable()->after('year');
            $table->string('fuel_type')->nullable()->after('color');
            $table->string('transmission')->nullable()->after('fuel_type');
            $table->string('dagps_device_id')->nullable()->after('transmission');
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn([
                'make',
                'model',
                'year',
                'color',
                'fuel_type',
                'transmission',
                'dagps_device_id',
            ]);
        });
    }
};
