<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Support\VehiclePricingResolver;
use Illuminate\Http\Request;

class FleetController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'start_location' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'order_type' => ['nullable', 'in:hire,rent'],
        ]);

        $startDate = $validated['start_date'] ?? null;
        $endDate = $validated['end_date'] ?? null;
        $orderType = $validated['order_type'] ?? null;

        $allVehicles = Vehicle::query()->visibleOnPublic()->with('images')->orderBy('name')->get();
        $availabilityRows = collect();
        $availableVehicleIds = null;

        if ($startDate && $endDate) {
            $vehicleIds = $allVehicles->pluck('id');

            $agreements = \App\Models\Agreement::query()
                ->whereIn('vehicle_id', $vehicleIds)
                ->where('status', 'active')
                ->get(['vehicle_id', 'start_date', 'end_date'])
                ->groupBy('vehicle_id');

            $rentals = \App\Models\Rental::query()
                ->whereIn('vehicle_id', $vehicleIds)
                ->where('status', 'active')
                ->get(['vehicle_id', 'start_date', 'end_date'])
                ->groupBy('vehicle_id');

            $confirmedBookings = \App\Models\Booking::query()
                ->whereIn('vehicle_id', $vehicleIds)
                ->where('status', 'confirmed')
                ->get(['vehicle_id', 'start_date', 'end_date'])
                ->groupBy('vehicle_id');

            $availabilityRows = $allVehicles->map(function (Vehicle $vehicle) use ($agreements, $rentals, $confirmedBookings, $startDate, $endDate) {
                $bookingRanges = collect();

                foreach ($agreements->get($vehicle->id, collect()) as $agreement) {
                    $bookingRanges->push([
                        'start' => $agreement->start_date,
                        'end' => $agreement->end_date,
                        'source' => 'Agreement',
                    ]);
                }

                foreach ($rentals->get($vehicle->id, collect()) as $rental) {
                    $bookingRanges->push([
                        'start' => $rental->start_date,
                        'end' => $rental->end_date,
                        'source' => 'Rental',
                    ]);
                }

                foreach ($confirmedBookings->get($vehicle->id, collect()) as $booking) {
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
                    'vehicle_id' => $vehicle->id,
                    'vehicle_name' => trim($vehicle->name . ($vehicle->year ? ' ' . $vehicle->year : '')),
                    'plate_no' => $vehicle->plate_no,
                    'ranges' => $bookingRanges,
                    'is_available' => !$hasOverlap,
                ];
            });

            $availableVehicleIds = $availabilityRows
                ->where('is_available', true)
                ->pluck('vehicle_id')
                ->values();
        }

        $vehiclesQuery = Vehicle::query()->visibleOnPublic();
        if (is_array($availableVehicleIds) || $availableVehicleIds instanceof \Illuminate\Support\Collection) {
            $vehiclesQuery->whereIn('id', $availableVehicleIds);
        }

        if ($orderType === 'hire') {
            $vehiclesQuery->where('available_for_hire', true);
        } elseif ($orderType === 'rent') {
            $vehiclesQuery->where('available_for_rent', true);
        }

        $cars = $vehiclesQuery
            ->with('images')
            ->orderByRaw("CASE WHEN status = 'available' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get()
            ->map(function (Vehicle $vehicle) {
                $pricing = VehiclePricingResolver::resolveForVehicle($vehicle);

                return [
                    'id' => $vehicle->id,
                    'name' => trim($vehicle->name . ($vehicle->year ? ' ' . $vehicle->year : '')),
                    'plate_no' => $vehicle->plate_no,
                    'status' => $vehicle->status,
                    'make' => $vehicle->make,
                    'model' => $vehicle->model,
                    'year' => $vehicle->year,
                    'color' => $vehicle->color,
                    'fuel_type' => $vehicle->fuel_type,
                    'transmission' => $vehicle->transmission,
                    'driver_mode' => $vehicle->driver_mode ?: 'both',
                    'available_for_hire' => (bool) $vehicle->available_for_hire,
                    'available_for_rent' => (bool) $vehicle->available_for_rent,
                    'per_day_km' => $pricing['per_day_km'],
                    'extra_km_rate' => $pricing['extra_km_rate'],
                    'rate' => number_format((float) $pricing['daily_rate'], 0),
                    'image' => $vehicle->primaryImageUrl(),
                ];
            });

        $filters = [
            'start_location' => $validated['start_location'] ?? '',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'order_type' => $orderType ?? '',
        ];

        return view('fleet.index', compact('cars', 'filters', 'availabilityRows'));
    }

    public function show(Vehicle $vehicle)
    {
        if (!Vehicle::query()->visibleOnPublic()->whereKey($vehicle->id)->exists()) {
            abort(404);
        }

        $vehicle->loadMissing('images');
        $pricing = VehiclePricingResolver::resolveForVehicle($vehicle);
        $driverMode = $vehicle->driver_mode ?: 'both';

        $driverModeLabel = match ($driverMode) {
            'with_driver_only' => 'With driver only',
            'without_driver_only' => 'Without driver only',
            default => 'With or without driver',
        };

        $nameLower = strtolower((string) $vehicle->name);
        $estimatedSeats = str_contains($nameLower, 'largo') ? 8 : 5;
        $estimatedBags = str_contains($nameLower, 'largo') ? 4 : 2;

        $vehicleData = [
            'id' => $vehicle->id,
            'name' => trim($vehicle->name . ($vehicle->year ? ' ' . $vehicle->year : '')),
            'plate_no' => $vehicle->plate_no,
            'status' => $vehicle->status,
            'make' => $vehicle->make,
            'model' => $vehicle->model,
            'year' => $vehicle->year,
            'color' => $vehicle->color,
            'fuel_type' => $vehicle->fuel_type,
            'transmission' => $vehicle->transmission,
            'driver_mode_label' => $driverModeLabel,
            'allow_long_term' => (bool) $vehicle->allow_long_term,
            'available_for_hire' => (bool) $vehicle->available_for_hire,
            'available_for_rent' => (bool) $vehicle->available_for_rent,
            'daily_rate' => (float) $pricing['daily_rate'],
            'monthly_rate' => (float) ($pricing['monthly_rate'] ?? 0),
            'per_day_km' => (int) $pricing['per_day_km'],
            'per_month_km' => (int) ($pricing['per_month_km'] ?? ((int) $pricing['per_day_km'] * 30)),
            'extra_km_rate' => (float) $pricing['extra_km_rate'],
            'driver_cost_per_day' => (float) ($pricing['driver_cost_per_day'] ?? 0),
            'seats' => $estimatedSeats,
            'bags' => $estimatedBags,
            'image' => $vehicle->primaryImageUrl(),
            'images' => $vehicle->galleryImageUrls(),
            'note' => $vehicle->note,
        ];

        return view('fleet.show', ['vehicle' => $vehicleData]);
    }

}
