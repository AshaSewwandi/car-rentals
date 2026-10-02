<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Bootstrap Icons name (e.g. "fuel-pump") for main and sub categories.
        Schema::table('budget_groups', function (Blueprint $table) {
            $table->string('icon', 40)->nullable()->after('name');
        });
        Schema::table('budget_categories', function (Blueprint $table) {
            $table->string('icon', 40)->nullable()->after('name');
        });
    }
    public function down(): void {
        Schema::table('budget_categories', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
        Schema::table('budget_groups', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};
