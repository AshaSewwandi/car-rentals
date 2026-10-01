<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Booking;
use App\Models\Rental;
use App\Models\Vehicle;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AvailabilityCheckController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'vehicle_id' => ['nullable', 'exists:vehicles,id'],
        ]);

        $startDate = $filters['start_date'] ?? now()->toDateString();
        $endDate = $filters['end_date'] ?? now()->addDays(13)->toDateString();
        $vehicleId = $filters['vehicle_id'] ?? null;

        $vehicles = Vehicle::query()
            ->when($vehicleId, fn ($q) => $q->where('id', $vehicleId))
            ->orderBy('name')
            ->get(['id', 'name', 'plate_no']);

        $vehicleIds = $vehicles->pluck('id');

        $confirmedBookings = Booking::query()
            ->whereIn('vehicle_id', $vehicleIds)
            ->where('status', 'confirmed')
            ->get(['vehicle_id', 'start_date', 'end_date'])
            ->groupBy('vehicle_id');

        $agreements = Agreement::query()
            ->whereIn('vehicle_id', $vehicleIds)
            ->where('status', 'active')
            ->get(['vehicle_id', 'start_date', 'end_date'])
            ->groupBy('vehicle_id');

        $rentals = Rental::query()
            ->whereIn('vehicle_id', $vehicleIds)
            ->where('status', 'active')
            ->get(['vehicle_id', 'start_date', 'end_date'])
            ->groupBy('vehicle_id');

        $timelineDates = collect(CarbonPeriod::create($startDate, $endDate))
            ->map(fn (Carbon $date) => $date->copy());

        $rows = $vehicles->map(function (Vehicle $vehicle) use ($confirmedBookings, $agreements, $rentals, $timelineDates) {
            $ranges = collect();

            foreach ($agreements->get($vehicle->id, collect()) as $agreement) {
                $ranges->push([
                    'start' => $agreement->start_date,
                    'end' => $agreement->end_date,
                    'source' => 'Agreement',
                ]);
            }

            foreach ($rentals->get($vehicle->id, collect()) as $rental) {
                $ranges->push([
                    'start' => $rental->start_date,
                    'end' => $rental->end_date,
                    'source' => 'Rental',
                ]);
            }

            foreach ($confirmedBookings->get($vehicle->id, collect()) as $booking) {
                $ranges->push([
                    'start' => $booking->start_date,
                    'end' => $booking->end_date,
                    'source' => 'Booking',
                ]);
            }

            $ranges = $ranges
                ->sortBy(fn ($row) => $row['start']?->format('Y-m-d') ?? '9999-12-31')
                ->values();

            $cells = $timelineDates->map(function (Carbon $date) use ($ranges) {
                $dateString = $date->format('Y-m-d');
                $isBooked = $ranges->contains(function ($row) use ($dateString) {
                    $rangeStart = $row['start']?->format('Y-m-d');
                    $rangeEnd = $row['end']?->format('Y-m-d');

                    if (!$rangeStart) {
                        return false;
                    }

                    return $rangeStart <= $dateString && (!$rangeEnd || $rangeEnd >= $dateString);
                });

                return [
                    'date' => $dateString,
                    'is_booked' => $isBooked,
                ];
            });

            $isAvailable = !$cells->contains(fn ($cell) => $cell['is_booked']);

            return [
                'name' => $vehicle->name,
                'plate_no' => $vehicle->plate_no,
                'ranges' => $ranges,
                'is_available' => $isAvailable,
                'cells' => $cells,
                'total_stock' => 1,
                'free_stock' => $isAvailable ? 1 : 0,
            ];
        });

        return view('availability-check.index', compact('rows', 'vehicles', 'filters', 'timelineDates'));
    }
}
