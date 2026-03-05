<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Support\VehiclePricingResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FleetController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'start_location' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $startDate = $validated['start_date'] ?? null;
        $endDate = $validated['end_date'] ?? null;

        $allCars = Car::query()->orderBy('name')->get();
        $availabilityRows = collect();
        $availableCarIds = null;

        if ($startDate && $endDate) {
            $carIds = $allCars->pluck('id');

            $agreements = \App\Models\Agreement::query()
                ->whereIn('car_id', $carIds)
                ->where('status', 'active')
                ->get(['car_id', 'start_date', 'end_date'])
                ->groupBy('car_id');

            $rentals = \App\Models\Rental::query()
                ->whereIn('car_id', $carIds)
                ->where('status', 'active')
                ->get(['car_id', 'start_date', 'end_date'])
                ->groupBy('car_id');

            $confirmedBookings = \App\Models\Booking::query()
                ->whereIn('car_id', $carIds)
                ->where('status', 'confirmed')
                ->get(['car_id', 'start_date', 'end_date'])
                ->groupBy('car_id');

            $availabilityRows = $allCars->map(function (Car $car) use ($agreements, $rentals, $confirmedBookings, $startDate, $endDate) {
                $bookingRanges = collect();

                foreach ($agreements->get($car->id, collect()) as $agreement) {
                    $bookingRanges->push([
                        'start' => $agreement->start_date,
                        'end' => $agreement->end_date,
                        'source' => 'Agreement',
                    ]);
                }

                foreach ($rentals->get($car->id, collect()) as $rental) {
                    $bookingRanges->push([
                        'start' => $rental->start_date,
                        'end' => $rental->end_date,
                        'source' => 'Rental',
                    ]);
                }

                foreach ($confirmedBookings->get($car->id, collect()) as $booking) {
                    $bookingRanges->push([
                        'start' => $booking->start_date,
                        'end' => $booking->end_date,
                        'source' => 'Booking',
                    ]);
                }

                $bookingRanges = $bookingRanges
                    ->sortBy(fn ($row) => $row['start']?->format('Y-m-d') ?? '9999-12-31')
                    ->values();

                $hasOverlap = $bookingRanges->contains(function ($row) use ($startDate, $endDate) {
                    $rangeStart = $row['start']?->format('Y-m-d');
                    $rangeEnd = $row['end']?->format('Y-m-d');

                    if (!$rangeStart) {
                        return false;
                    }

                    return $rangeStart <= $endDate && (!$rangeEnd || $rangeEnd >= $startDate);
                });

                return [
                    'car_id' => $car->id,
                    'car_name' => trim($car->name . ($car->year ? ' ' . $car->year : '')),
                    'plate_no' => $car->plate_no,
                    'ranges' => $bookingRanges,
                    'is_available' => !$hasOverlap,
                ];
            });

            $availableCarIds = $availabilityRows
                ->where('is_available', true)
                ->pluck('car_id')
                ->values();
        }

        $carsQuery = Car::query();
        if (is_array($availableCarIds) || $availableCarIds instanceof \Illuminate\Support\Collection) {
            $carsQuery->whereIn('id', $availableCarIds);
        }

        $cars = $carsQuery
            ->orderByRaw("CASE WHEN status = 'available' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get()
            ->map(function (Car $car) {
                $pricing = VehiclePricingResolver::resolveForCar($car);

                return [
                    'id' => $car->id,
                    'name' => trim($car->name . ($car->year ? ' ' . $car->year : '')),
                    'plate_no' => $car->plate_no,
                    'status' => $car->status,
                    'make' => $car->make,
                    'model' => $car->model,
                    'year' => $car->year,
                    'color' => $car->color,
                    'fuel_type' => $car->fuel_type,
                    'transmission' => $car->transmission,
                    'driver_mode' => $car->driver_mode ?: 'both',
                    'per_day_km' => $pricing['per_day_km'],
                    'extra_km_rate' => $pricing['extra_km_rate'],
                    'rate' => number_format((float) $pricing['daily_rate'], 0),
                    'image' => $this->resolveImagePath($car),
                ];
            });

        $filters = [
            'start_location' => $validated['start_location'] ?? '',
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];

        return view('fleet.index', compact('cars', 'filters', 'availabilityRows'));
    }

    public function show(Car $car)
    {
        $pricing = VehiclePricingResolver::resolveForCar($car);
        $driverMode = $car->driver_mode ?: 'both';

        $driverModeLabel = match ($driverMode) {
            'with_driver_only' => 'With driver only',
            'without_driver_only' => 'Without driver only',
            default => 'With or without driver',
        };

        $nameLower = strtolower((string) $car->name);
        $estimatedSeats = str_contains($nameLower, 'largo') ? 8 : 5;
        $estimatedBags = str_contains($nameLower, 'largo') ? 4 : 2;

        $vehicle = [
            'id' => $car->id,
            'name' => trim($car->name . ($car->year ? ' ' . $car->year : '')),
            'plate_no' => $car->plate_no,
            'status' => $car->status,
            'make' => $car->make,
            'model' => $car->model,
            'year' => $car->year,
            'color' => $car->color,
            'fuel_type' => $car->fuel_type,
            'transmission' => $car->transmission,
            'driver_mode_label' => $driverModeLabel,
            'allow_long_term' => (bool) $car->allow_long_term,
            'daily_rate' => (float) $pricing['daily_rate'],
            'monthly_rate' => (float) ($pricing['monthly_rate'] ?? 0),
            'per_day_km' => (int) $pricing['per_day_km'],
            'per_month_km' => (int) ($pricing['per_month_km'] ?? ((int) $pricing['per_day_km'] * 30)),
            'extra_km_rate' => (float) $pricing['extra_km_rate'],
            'driver_cost_per_day' => (float) ($pricing['driver_cost_per_day'] ?? 0),
            'seats' => $estimatedSeats,
            'bags' => $estimatedBags,
            'image' => $this->resolveImagePath($car),
            'note' => $car->note,
        ];

        return view('fleet.show', compact('vehicle'));
    }

    private function resolveImagePath(Car $car): string
    {
        $candidates = [
            $this->normalize($car->plate_no) . '.png',
            $this->normalize($car->plate_no) . '.jpg',
            $this->normalize($car->plate_no) . '.jpeg',
            $this->normalize($car->name) . '.png',
            $this->normalize($car->name) . '.jpg',
            $this->normalize($car->name) . '.jpeg',
        ];

        foreach ($candidates as $file) {
            if ($file === '.png' || $file === '.jpg' || $file === '.jpeg') {
                continue;
            }

            if (file_exists(public_path('images/' . $file))) {
                return asset('images/' . $file);
            }
        }

        return asset('images/logo.png');
    }

    private function normalize(?string $value): string
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
