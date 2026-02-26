<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Car;
use App\Models\RentRequest;
use App\Models\Rental;
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
            'car_id' => ['nullable', 'exists:cars,id'],
        ]);

        $startDate = $filters['start_date'] ?? now()->toDateString();
        $endDate = $filters['end_date'] ?? now()->addDays(13)->toDateString();
        $carId = $filters['car_id'] ?? null;

        $cars = Car::query()
            ->when($carId, fn ($q) => $q->where('id', $carId))
            ->orderBy('name')
            ->get(['id', 'name', 'plate_no']);

        $carIds = $cars->pluck('id');

        $agreements = Agreement::query()
            ->whereIn('car_id', $carIds)
            ->where('status', 'active')
            ->get(['car_id', 'start_date', 'end_date'])
            ->groupBy('car_id');

        $rentals = Rental::query()
            ->whereIn('car_id', $carIds)
            ->where('status', 'active')
            ->get(['car_id', 'start_date', 'end_date'])
            ->groupBy('car_id');

        $acceptedRequests = RentRequest::query()
            ->whereIn('car_id', $carIds)
            ->where('status', 'accepted')
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->get(['car_id', 'start_date', 'end_date'])
            ->groupBy('car_id');

        $timelineDates = collect(CarbonPeriod::create($startDate, $endDate))
            ->map(fn (Carbon $date) => $date->copy());

        $rows = $cars->map(function (Car $car) use ($agreements, $rentals, $acceptedRequests, $startDate, $endDate, $timelineDates) {
            $ranges = collect();

            foreach ($agreements->get($car->id, collect()) as $agreement) {
                $ranges->push([
                    'start' => $agreement->start_date,
                    'end' => $agreement->end_date,
                    'source' => 'Agreement',
                ]);
            }

            foreach ($rentals->get($car->id, collect()) as $rental) {
                $ranges->push([
                    'start' => $rental->start_date,
                    'end' => $rental->end_date,
                    'source' => 'Rental',
                ]);
            }

            foreach ($acceptedRequests->get($car->id, collect()) as $acceptedRequest) {
                $ranges->push([
                    'start' => $acceptedRequest->start_date,
                    'end' => $acceptedRequest->end_date,
                    'source' => 'AcceptedRequest',
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
                'name' => $car->name,
                'plate_no' => $car->plate_no,
                'ranges' => $ranges,
                'is_available' => $isAvailable,
                'cells' => $cells,
                'total_stock' => 1,
                'free_stock' => $isAvailable ? 1 : 0,
            ];
        });

        return view('availability-check.index', compact('rows', 'cars', 'filters', 'timelineDates'));
    }
}
