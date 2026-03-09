<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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
        'driver_mode',
        'allow_long_term',
        'dagps_device_id',
        'tracker_device_name',
        'tracker_device_type',
        'tracker_imei',
        'partner_user_id',
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
        'allow_long_term' => 'boolean',
    ];

    public function rentals(): HasMany { return $this->hasMany(Rental::class); }
    public function expenses(): HasMany { return $this->hasMany(Expense::class); }
    public function maintenanceRecords(): HasMany { return $this->hasMany(VehicleMaintenance::class); }
    public function agreements(): HasMany { return $this->hasMany(Agreement::class); }
    public function gpsLogs(): HasMany { return $this->hasMany(GpsLog::class); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }
    public function images(): HasMany { return $this->hasMany(CarImage::class)->orderBy('sort_order')->orderBy('id'); }
    public function partner(): BelongsTo { return $this->belongsTo(User::class, 'partner_user_id'); }

    public function primaryImageUrl(): string
    {
        if (!$this->relationLoaded('images')) {
            $this->loadMissing('images');
        }

        $uploaded = $this->images->first();
        if ($uploaded?->path) {
            return asset($uploaded->path);
        }

        foreach ($this->legacyImageCandidates() as $file) {
            if (file_exists(public_path('images/' . $file))) {
                return asset('images/' . $file);
            }
        }

        return asset('images/logo.png');
    }

    public function galleryImageUrls(): array
    {
        if (!$this->relationLoaded('images')) {
            $this->loadMissing('images');
        }

        $urls = $this->images
            ->pluck('path')
            ->filter()
            ->map(fn (string $path) => asset($path))
            ->values()
            ->all();

        if (!empty($urls)) {
            return $urls;
        }

        return [$this->primaryImageUrl()];
    }

    private function legacyImageCandidates(): array
    {
        return array_values(array_filter([
            $this->normalizeForImage($this->plate_no) . '.png',
            $this->normalizeForImage($this->plate_no) . '.jpg',
            $this->normalizeForImage($this->plate_no) . '.jpeg',
            $this->normalizeForImage($this->name) . '.png',
            $this->normalizeForImage($this->name) . '.jpg',
            $this->normalizeForImage($this->name) . '.jpeg',
        ], fn ($file) => !in_array($file, ['.png', '.jpg', '.jpeg'], true)));
    }

    private function normalizeForImage(?string $value): string
    {
        if (!$value) {
            return '';
        }

        return Str::of($value)
            ->lower()
            ->replace([' ', '-', '/', '\\'], '_')
            ->replaceMatches('/[^a-z0-9_]/', '')
            ->value();
    }
}
