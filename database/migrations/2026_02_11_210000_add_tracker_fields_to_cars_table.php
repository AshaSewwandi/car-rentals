<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->string('tracker_device_name')->nullable()->after('dagps_device_id');
            $table->string('tracker_device_type')->nullable()->after('tracker_device_name');
            $table->string('tracker_imei')->nullable()->after('tracker_device_type');
            $table->string('tracker_sim')->nullable()->after('tracker_imei');
            $table->string('tracker_iccid')->nullable()->after('tracker_sim');
            $table->string('tracker_contact_name')->nullable()->after('tracker_iccid');
            $table->string('tracker_contact_number')->nullable()->after('tracker_contact_name');
            $table->dateTime('tracker_activation_date')->nullable()->after('tracker_contact_number');
            $table->string('tracker_expiry_time')->nullable()->after('tracker_activation_date');
            $table->date('tracker_insurance_expires')->nullable()->after('tracker_expiry_time');
            $table->unsignedInteger('tracker_maintenance_mileage')->nullable()->after('tracker_insurance_expires');
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn([
                'tracker_device_name',
                'tracker_device_type',
                'tracker_imei',
                'tracker_sim',
                'tracker_iccid',
                'tracker_contact_name',
                'tracker_contact_number',
                'tracker_activation_date',
                'tracker_expiry_time',
                'tracker_insurance_expires',
                'tracker_maintenance_mileage',
            ]);
        });
    }
};
