<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Links the copies of the same main/sub category across months, so a change applies to every month.
        Schema::table('budget_groups', function (Blueprint $table) {
            $table->uuid('plan_key')->nullable()->after('budget_month_id')->index();
        });
        Schema::table('budget_categories', function (Blueprint $table) {
            $table->uuid('plan_key')->nullable()->after('budget_group_id')->index();
        });
    }
    public function down(): void {
        Schema::table('budget_categories', function (Blueprint $table) {
            $table->dropIndex(['plan_key']);
            $table->dropColumn('plan_key');
        });
        Schema::table('budget_groups', function (Blueprint $table) {
            $table->dropIndex(['plan_key']);
            $table->dropColumn('plan_key');
        });
    }
};
