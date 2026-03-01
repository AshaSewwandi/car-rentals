<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    protected $fillable = [
        'name',
        'make',
        'model',
        'year',
        'color',
        'fuel_type',
        'transmission',
        'dagps_device_id',
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
        'tracker_license_expires',
        'tracker_maintenance_mileage',
        'maintenance_last_service_date',
        'maintenance_last_service_mileage',
        'maintenance_next_service_date',
        'maintenance_note',
        'latest_latitude',
        'latest_longitude',
        'latest_speed',
        'latest_course',
        'tracker_last_seen_at',
        'plate_no',
        'status',
        'note',
    ];

    protected $casts = [
        'tracker_activation_date' => 'datetime',
        'tracker_insurance_expires' => 'date',
        'tracker_license_expires' => 'date',
        'maintenance_last_service_date' => 'date',
        'maintenance_next_service_date' => 'date',
        'tracker_last_seen_at' => 'datetime',
    ];

    public function rentals(): HasMany { return $this->hasMany(Rental::class); }
    public function expenses(): HasMany { return $this->hasMany(Expense::class); }
    public function maintenanceRecords(): HasMany { return $this->hasMany(VehicleMaintenance::class); }
    public function agreements(): HasMany { return $this->hasMany(Agreement::class); }
    public function gpsLogs(): HasMany { return $this->hasMany(GpsLog::class); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }
}
