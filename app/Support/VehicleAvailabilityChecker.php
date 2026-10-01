<?php

namespace App\Support;

use App\Models\Agreement;
use App\Models\Booking;
use App\Models\Rental;

class VehicleAvailabilityChecker
{
    public static function isAvailable(int $vehicleId, string $startDate, string $endDate): bool
    {
        $agreementOverlap = Agreement::query()
            ->where('vehicle_id', $vehicleId)
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $endDate)
            ->where(function ($q) use ($startDate) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', $startDate);
            })
            ->exists();

        if ($agreementOverlap) {
            return false;
        }

        $rentalOverlap = Rental::query()
            ->where('vehicle_id', $vehicleId)
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $endDate)
            ->where(function ($q) use ($startDate) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', $startDate);
            })
            ->exists();

        if ($rentalOverlap) {
            return false;
        }

        $bookingOverlap = Booking::query()
            ->where('vehicle_id', $vehicleId)
            ->where('status', 'confirmed')
            ->whereDate('start_date', '<=', $endDate)
            ->whereDate('end_date', '>=', $startDate)
            ->exists();

        return !$bookingOverlap;
    }
}
