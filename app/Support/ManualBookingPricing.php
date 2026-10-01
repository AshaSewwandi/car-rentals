<?php

namespace App\Support;

use App\Models\Vehicle;

class ManualBookingPricing
{
    /**
     * Build the pricing-related Booking attributes from admin-entered values.
     * Shared by admin "Place Order" and "Review & Assign" (trip request accept),
     * since both let staff hand-type the price instead of using a system quote.
     */
    public static function fromAdminInput(
        Vehicle $vehicle,
        int $days,
        string $driverOption,
        float $dailyRate,
        float $driverRate,
        float $totalAmount,
        float $partnerShareAmount,
        float $adminShareAmount
    ): array {
        $dailyRate = round($dailyRate, 2);
        $driverRate = $driverOption === 'with_driver' ? round($driverRate, 2) : 0.0;
        $driverTotal = round($driverRate * $days, 2);
        $totalAmount = round($totalAmount, 2);
        $partnerShareAmount = round($partnerShareAmount, 2);
        $adminShareAmount = round($adminShareAmount, 2);

        $shareableAmount = RevenueShareResolver::shareableAmount($totalAmount, $driverTotal);
        $partnerSharePercentage = $shareableAmount > 0 ? round(($partnerShareAmount / $shareableAmount) * 100, 2) : 0.0;
        $adminSharePercentage = $shareableAmount > 0 ? round(($adminShareAmount / $shareableAmount) * 100, 2) : 0.0;

        $pricing = VehiclePricingResolver::resolveForVehicle($vehicle);

        return [
            'daily_rate' => $dailyRate,
            'driver_rate' => $driverRate,
            'driver_total' => $driverTotal,
            'total_amount' => $totalAmount,
            'final_total' => $totalAmount,
            'partner_share_percentage' => $partnerSharePercentage,
            'admin_share_percentage' => $adminSharePercentage,
            'partner_share_amount' => $partnerShareAmount,
            'admin_share_amount' => $adminShareAmount,
            'included_km' => $days * $pricing['per_day_km'],
            'extra_km_rate' => $pricing['extra_km_rate'],
            'currency' => 'LKR',
        ];
    }
}
