<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HomeController extends Controller
{
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

        $recommendedCars = Car::query()
            ->orderByRaw("CASE WHEN status = 'available' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->limit(4)
            ->get()
            ->map(function (Car $car) {
                return [
                    'id' => $car->id,
                    'name' => trim($car->name . ($car->year ? ' ' . $car->year : '')),
                    'plate_no' => $car->plate_no,
                    'daily_rate' => $this->resolveDailyRate($car),
                    'image' => $this->resolveImagePath($car),
                ];
            });

        return view('customer.home', compact('user', 'activeTrips', 'canceledTrips', 'recommendedCars'));
    }

    private function extractRate(?string $note): ?float
    {
        if (!$note) {
            return null;
        }

        if (preg_match('/(?:rs\\.?\\s*)?([\\d,]+(?:\\.\\d{1,2})?)/i', $note, $matches)) {
            return (float) str_replace(',', '', $matches[1]);
        }

        return null;
    }

    private function resolveDailyRate(Car $car): float
    {
        $noteRate = $this->extractRate($car->note);
        if ($noteRate !== null) {
            return $noteRate;
        }

        $plateKey = strtoupper((string) Str::of((string) $car->plate_no)->replaceMatches('/[^A-Za-z0-9]/', ''));
        $knownRates = [
            'CAK8043' => 4000,
            'CAK9010' => 4000,
            'CAK9792' => 4000,
            '588233' => 8000,
        ];

        return (float) ($knownRates[$plateKey] ?? 4500);
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
            if (in_array($file, ['.png', '.jpg', '.jpeg'], true)) {
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
