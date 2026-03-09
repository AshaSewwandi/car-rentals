<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use App\Models\VehiclePricing;
use App\Support\VehiclePricingResolver;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function home(): View
    {
        $featuredCars = Car::query()
            ->with('images')
            ->orderByRaw("CASE WHEN status = 'available' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->limit(3)
            ->get()
            ->map(function (Car $car) {
                $pricing = VehiclePricingResolver::resolveForCar($car);
                $dailyRate = (float) $pricing['daily_rate'];

                $segment = 'Economy';
                if (str_contains(strtolower((string) $car->name), 'largo')) {
                    $segment = 'SUV';
                } elseif ($dailyRate >= 10000) {
                    $segment = 'Luxury';
                }

                return [
                    'id' => $car->id,
                    'name' => trim($car->name . ($car->year ? ' ' . $car->year : '')),
                    'daily_rate' => $dailyRate,
                    'segment' => $segment,
                    'transmission' => $car->transmission ?: 'Auto',
                    'seats' => str_contains(strtolower((string) $car->name), 'largo') ? '8 Seats' : '5 Seats',
                    'bags' => str_contains(strtolower((string) $car->name), 'largo') ? '4 Bags' : '2 Bags',
                    'image' => $car->primaryImageUrl(),
                ];
            });

        return view('welcome', compact('featuredCars'));
    }

    public function airportHires(): View
    {
        $featuredCars = Car::query()
            ->with('images')
            ->orderByRaw("CASE WHEN status = 'available' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->limit(3)
            ->get()
            ->map(function (Car $car) {
                $pricing = VehiclePricingResolver::resolveForCar($car);
                $driverMode = $car->driver_mode ?: 'both';

                $driverModeLabel = match ($driverMode) {
                    'with_driver_only' => 'With driver only',
                    'without_driver_only' => 'Without driver only',
                    default => 'With or without driver',
                };

                return [
                    'id' => $car->id,
                    'name' => trim($car->name . ($car->year ? ' ' . $car->year : '')),
                    'plate_no' => $car->plate_no,
                    'make' => $car->make,
                    'model' => $car->model,
                    'year' => $car->year,
                    'transmission' => $car->transmission,
                    'fuel_type' => $car->fuel_type,
                    'color' => $car->color,
                    'status' => $car->status,
                    'daily_rate' => (float) $pricing['daily_rate'],
                    'per_day_km' => (int) $pricing['per_day_km'],
                    'extra_km_rate' => (float) $pricing['extra_km_rate'],
                    'driver_mode_label' => $driverModeLabel,
                    'airport_tag' => $this->resolveAirportTag($car, (float) $pricing['daily_rate']),
                    'image' => $car->primaryImageUrl(),
                ];
            });

        $airports = [
            'Bandaranaike International Airport',
            'Mattala Rajapaksa International Airport',
            'Colombo City Pickup',
            'Galle City Pickup',
        ];

        return view('airport-hires', compact('featuredCars', 'airports'));
    }

    public function shortTermRentals(): View
    {
        $featuredCars = Car::query()
            ->with('images')
            ->orderByRaw("CASE WHEN status = 'available' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->limit(3)
            ->get()
            ->map(function (Car $car) {
                $pricing = VehiclePricingResolver::resolveForCar($car);

                return [
                    'id' => $car->id,
                    'name' => trim($car->name . ($car->year ? ' ' . $car->year : '')),
                    'plate_no' => $car->plate_no,
                    'model' => $car->model,
                    'year' => $car->year,
                    'transmission' => $car->transmission,
                    'fuel_type' => $car->fuel_type,
                    'daily_rate' => (float) $pricing['daily_rate'],
                    'seats' => str_contains(strtolower((string) $car->name), 'largo') ? '8 Seats' : '5 Seats',
                    'tag' => $car->status === 'available' ? 'Available' : 'Popular',
                    'image' => $car->primaryImageUrl(),
                ];
            });

        $cities = [
            'Colombo',
            'Galle',
            'Matara',
            'Negombo',
            'Kandy',
        ];

        return view('short-term-rentals', compact('featuredCars', 'cities'));
    }

    public function longTermRentals(): View
    {
        $featuredCars = Car::query()
            ->with('images')
            ->orderByRaw("CASE WHEN status = 'available' THEN 0 ELSE 1 END")
            ->where('allow_long_term', true)
            ->orderBy('name')
            ->limit(3)
            ->get()
            ->map(function (Car $car, int $index) {
                $pricing = VehiclePricingResolver::resolveForCar($car);

                return [
                    'id' => $car->id,
                    'name' => trim($car->name . ($car->year ? ' ' . $car->year : '')),
                    'model' => $car->model ?: 'Monthly rental vehicle',
                    'transmission' => $car->transmission ?: 'Automatic',
                    'daily_rate' => (float) $pricing['daily_rate'],
                    'monthly_rate' => round((float) ($pricing['monthly_rate'] ?? 0), 0),
                    'per_month_km' => (int) ($pricing['per_month_km'] ?? 4500),
                    'tag' => match ($index) {
                        0 => 'Economy',
                        1 => 'Family',
                        default => 'Executive',
                    },
                    'image' => $car->primaryImageUrl(),
                ];
            });

        $categories = [
            'Economy Sedan',
            'Family SUV',
            'Executive Sedan',
            'Van / Group',
        ];

        $durationOptions = [
            '1 Month',
            '3 Months',
            '6 Months',
            '12 Months',
        ];

        return view('long-term-rentals', compact('featuredCars', 'categories', 'durationOptions'));
    }

    public function medicalTransport(): View
    {
        $featuredCars = Car::query()
            ->with('images')
            ->orderByRaw("CASE WHEN status = 'available' THEN 0 ELSE 1 END")
            ->orderByRaw("CASE WHEN LOWER(name) LIKE '%largo%' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->limit(3)
            ->get()
            ->map(function (Car $car) {
                $pricing = VehiclePricingResolver::resolveForCar($car);

                return [
                    'id' => $car->id,
                    'name' => trim($car->name . ($car->year ? ' ' . $car->year : '')),
                    'transmission' => $car->transmission ?: 'Automatic',
                    'fuel_type' => $car->fuel_type ?: 'Petrol',
                    'daily_rate' => (float) $pricing['daily_rate'],
                    'image' => $car->primaryImageUrl(),
                ];
            });

        $faqItems = [
            [
                'question' => 'Can I book a medical ride in Colombo, Galle, or Matara?',
                'answer' => 'Yes. We support patient transport in Colombo, Galle, Matara, and nearby areas depending on vehicle availability.',
            ],
            [
                'question' => 'Can you take patients to hospitals in Sri Lanka?',
                'answer' => 'Yes. We can arrange transport to hospitals, clinics, dialysis centers, and medical appointments across Sri Lanka.',
            ],
            [
                'question' => 'Do you provide wheelchair-friendly transport?',
                'answer' => 'Yes. Please tell us in advance if the passenger uses a wheelchair or needs extra assistance so we can assign the correct vehicle.',
            ],
            [
                'question' => 'Can a family member come with the patient?',
                'answer' => 'Yes. A family member or caregiver can usually travel with the patient if seats are available in the assigned vehicle.',
            ],
            [
                'question' => 'Can I book for early morning clinic visits?',
                'answer' => 'Yes. We can arrange early morning pickups for clinic visits, hospital admissions, and scheduled treatments.',
            ],
            [
                'question' => 'How do I contact you quickly in Sri Lanka?',
                'answer' => 'You can call us directly on +94 77 717 3264 or send a request through the booking form on this page.',
            ],
        ];

        return view('medical-transport', compact('featuredCars', 'faqItems'));
    }

    public function groupPackages(): View
    {
        $featuredCars = Car::query()
            ->with('images')
            ->orderByRaw("CASE WHEN status = 'available' THEN 0 ELSE 1 END")
            ->orderByRaw("CASE WHEN LOWER(name) LIKE '%largo%' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->limit(2)
            ->get()
            ->map(function (Car $car) {
                $pricing = VehiclePricingResolver::resolveForCar($car);
                $name = strtolower(trim(($car->name ?? '') . ' ' . ($car->model ?? '')));

                $estimatedSeats = 5;
                $estimatedBags = 2;
                $tag = 'Group';

                if (str_contains($name, 'largo') || str_contains($name, 'van') || str_contains($name, 'kdh')) {
                    $estimatedSeats = 12;
                    $estimatedBags = 8;
                    $tag = 'Large Group';
                } elseif (str_contains($name, 'suv') || str_contains($name, 'prado') || str_contains($name, 'montero')) {
                    $estimatedSeats = 7;
                    $estimatedBags = 5;
                    $tag = 'Family Group';
                } elseif ((float) $pricing['daily_rate'] >= 10000) {
                    $estimatedSeats = 7;
                    $estimatedBags = 4;
                    $tag = 'Premium Group';
                }

                return [
                    'id' => $car->id,
                    'name' => trim($car->name . ($car->year ? ' ' . $car->year : '')),
                    'model' => $car->model ?: 'Group travel vehicle',
                    'transmission' => $car->transmission ?: 'Automatic',
                    'daily_rate' => (float) $pricing['daily_rate'],
                    'seats' => $estimatedSeats,
                    'bags' => $estimatedBags,
                    'tag' => $tag,
                    'image' => $car->primaryImageUrl(),
                ];
            });

        return view('group-packages', compact('featuredCars'));
    }

    public function pricingIndex(): View
    {
        $pricingRows = VehiclePricing::query()
            ->orderBy('make')
            ->orderBy('model')
            ->get();

        $startingRate = $pricingRows->min('per_day_amount');
        $highestIncludedKm = $pricingRows->max('per_day_km');

        return view('pricing.index', compact('pricingRows', 'startingRate', 'highestIncludedKm'));
    }

    public function customerDashboard(Request $request): View
    {
        $user = $request->user();

        if (!$user || !$user->isCustomer()) {
            abort(403);
        }

        $activeTrips = Booking::query()
            ->with('car')
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id);

                if (!empty($user->email)) {
                    $query->orWhere(function ($fallback) use ($user) {
                        $fallback->whereNull('user_id')
                            ->whereNotNull('customer_email')
                            ->where('customer_email', $user->email);
                    });
                }

                if (!empty($user->phone)) {
                    $query->orWhere(function ($fallback) use ($user) {
                        $fallback->whereNull('user_id')
                            ->whereNotNull('customer_phone')
                            ->where('customer_phone', $user->phone);
                    });
                }

                $query->orWhere(function ($fallback) use ($user) {
                    $fallback->whereNull('user_id')
                        ->where('customer_name', $user->name);
                });
            })
            ->orderBy('start_date')
            ->get();

        $canceledTrips = Booking::query()
            ->with('car')
            ->where('status', 'cancelled')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id);

                if (!empty($user->email)) {
                    $query->orWhere(function ($fallback) use ($user) {
                        $fallback->whereNull('user_id')
                            ->whereNotNull('customer_email')
                            ->where('customer_email', $user->email);
                    });
                }

                if (!empty($user->phone)) {
                    $query->orWhere(function ($fallback) use ($user) {
                        $fallback->whereNull('user_id')
                            ->whereNotNull('customer_phone')
                            ->where('customer_phone', $user->phone);
                    });
                }

                $query->orWhere(function ($fallback) use ($user) {
                    $fallback->whereNull('user_id')
                        ->where('customer_name', $user->name);
                });
            })
            ->orderByDesc('updated_at')
            ->get();

        $completedTrips = Booking::query()
            ->with('car')
            ->where('status', 'completed')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id);

                if (!empty($user->email)) {
                    $query->orWhere(function ($fallback) use ($user) {
                        $fallback->whereNull('user_id')
                            ->whereNotNull('customer_email')
                            ->where('customer_email', $user->email);
                    });
                }

                if (!empty($user->phone)) {
                    $query->orWhere(function ($fallback) use ($user) {
                        $fallback->whereNull('user_id')
                            ->whereNotNull('customer_phone')
                            ->where('customer_phone', $user->phone);
                    });
                }

                $query->orWhere(function ($fallback) use ($user) {
                    $fallback->whereNull('user_id')
                        ->where('customer_name', $user->name);
                });
            })
            ->orderByDesc('updated_at')
            ->get();

        $recommendedCars = Car::query()
            ->with('images')
            ->orderByRaw("CASE WHEN status = 'available' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->limit(4)
            ->get()
            ->map(function (Car $car) {
                return [
                    'id' => $car->id,
                    'name' => trim($car->name . ($car->year ? ' ' . $car->year : '')),
                    'plate_no' => $car->plate_no,
                    'daily_rate' => VehiclePricingResolver::resolveForCar($car)['daily_rate'],
                    'image' => $car->primaryImageUrl(),
                ];
            });

        return view('customer.home', compact('user', 'activeTrips', 'canceledTrips', 'completedTrips', 'recommendedCars'));
    }

    private function resolveAirportTag(Car $car, float $dailyRate): string
    {
        $name = strtolower(trim(($car->name ?? '') . ' ' . ($car->make ?? '') . ' ' . ($car->model ?? '')));
        $fuel = strtolower((string) ($car->fuel_type ?? ''));

        if (str_contains($name, 'largo') || str_contains($name, 'van') || str_contains($name, 'kdh')) {
            return 'Large Trunk Space';
        }

        if (in_array($fuel, ['hybrid', 'diesel'], true) || $dailyRate <= 5500) {
            return 'Fuel Efficient';
        }

        return 'Airport Ready';
    }
}
