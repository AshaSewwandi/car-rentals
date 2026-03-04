<?php

namespace App\Support;

use App\Models\Car;
use App\Models\VehiclePricing;
use Illuminate\Support\Str;

class VehiclePricingResolver
{
    public static function resolveForCar(Car $car): array
    {
        $pricing = VehiclePricing::query()
            ->when(!empty($car->make), fn ($query) => $query->where('make', $car->make))
            ->where('model', $car->model ?: $car->name)
            ->first();

        if ($pricing) {
            return [
                'daily_rate' => (float) $pricing->per_day_amount,
                'per_day_km' => (int) $pricing->per_day_km,
                'extra_km_rate' => (float) $pricing->extra_km_rate,
                'driver_cost_per_day' => (float) ($pricing->driver_cost_per_day ?? 0),
                'pricing' => $pricing,
            ];
        }

        $plateKey = strtoupper((string) Str::of((string) $car->plate_no)->replaceMatches('/[^A-Za-z0-9]/', ''));
        $knownRates = [
            'CAK8043' => 4000,
            'CAK9010' => 4000,
            'CAK9792' => 4000,
            '588233' => 8000,
        ];

        return [
            'daily_rate' => (float) ($knownRates[$plateKey] ?? 4500),
            'per_day_km' => 150,
            'extra_km_rate' => 25,
            'driver_cost_per_day' => 0,
            'pricing' => null,
        ];
    }

    public static function resolveDisplayRate(Car $car): ?string
    {
        $resolved = static::resolveForCar($car);

        return number_format((float) $resolved['daily_rate'], 0);
    }
}
