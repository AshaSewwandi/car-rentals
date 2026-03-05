<?php

namespace App\Support;

use App\Models\Booking;
use App\Models\Car;

class RevenueShareResolver
{
    public static function shareableAmount(float $finalTotal, float $driverTotal = 0): float
    {
        return round(max($finalTotal - $driverTotal, 0), 2);
    }

    public static function percentagesForCar(Car $car): array
    {
        $partner = $car->partner;

        if (!$partner) {
            return [
                'partner_share_percentage' => 0.0,
                'admin_share_percentage' => 100.0,
            ];
        }

        $partnerPercentage = round((float) ($partner->partner_share_percentage ?? 80), 2);
        $adminPercentage = round((float) ($partner->admin_share_percentage ?? (100 - $partnerPercentage)), 2);

        return [
            'partner_share_percentage' => max($partnerPercentage, 0.0),
            'admin_share_percentage' => max($adminPercentage, 0.0),
        ];
    }

    public static function splitForCar(Car $car, float $finalTotal): array
    {
        $percentages = self::percentagesForCar($car);
        $shareableAmount = round($finalTotal, 2);

        return $percentages + [
            'shareable_amount' => $shareableAmount,
            'partner_share_amount' => round($shareableAmount * ($percentages['partner_share_percentage'] / 100), 2),
            'admin_share_amount' => round($shareableAmount * ($percentages['admin_share_percentage'] / 100), 2),
        ];
    }

    public static function splitForBooking(Booking $booking): array
    {
        $finalTotal = round((float) ($booking->final_total ?? $booking->total_amount ?? 0), 2);
        $driverTotal = round((float) ($booking->driver_total ?? 0), 2);
        $shareableAmount = self::shareableAmount($finalTotal, $driverTotal);
        $partnerPercentage = $booking->partner_share_percentage;
        $adminPercentage = $booking->admin_share_percentage;

        if ($partnerPercentage === null || $adminPercentage === null) {
            $booking->loadMissing('car.partner');
            $percentages = self::percentagesForCar($booking->car);

            return $percentages + [
                'shareable_amount' => $shareableAmount,
                'partner_share_amount' => round($shareableAmount * ($percentages['partner_share_percentage'] / 100), 2),
                'admin_share_amount' => round($shareableAmount * ($percentages['admin_share_percentage'] / 100), 2),
            ];
        }

        return [
            'partner_share_percentage' => round((float) $partnerPercentage, 2),
            'admin_share_percentage' => round((float) $adminPercentage, 2),
            'shareable_amount' => $shareableAmount,
            'partner_share_amount' => round((float) ($booking->partner_share_amount ?? ($shareableAmount * ((float) $partnerPercentage / 100))), 2),
            'admin_share_amount' => round((float) ($booking->admin_share_amount ?? ($shareableAmount * ((float) $adminPercentage / 100))), 2),
        ];
    }
}
