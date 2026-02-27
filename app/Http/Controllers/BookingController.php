<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'car_id' => ['required', 'exists:cars,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'start_location' => ['nullable', 'string', 'max:255'],
        ]);

        $car = Car::findOrFail($validated['car_id']);
        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];

        if (!$this->isCarAvailable($car->id, $startDate, $endDate)) {
            return redirect()
                ->route('fleet.index', $validated)
                ->with('error', 'Selected car is not available in this date range.');
        }

        $days = max(1, (int) Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1);
        $dailyRate = $this->resolveDailyRate($car);
        $totalAmount = $dailyRate * $days;

        return view('booking.confirm', [
            'car' => $car,
            'filters' => $validated,
            'rentalDays' => $days,
            'dailyRate' => $dailyRate,
            'totalAmount' => $totalAmount,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'car_id' => ['required', 'exists:cars,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'pickup_location' => ['nullable', 'string', 'max:255'],
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['nullable', 'email', 'max:180', 'required_without:customer_phone'],
            'customer_phone' => ['nullable', 'string', 'max:40', 'required_without:customer_email'],
            'payment_method' => ['required', 'in:pay_later_bank,pay_at_pickup_cash'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $car = Car::findOrFail($validated['car_id']);

        if (!$this->isCarAvailable($car->id, $validated['start_date'], $validated['end_date'])) {
            return back()->withInput()->with('error', 'Car became unavailable for the selected dates.');
        }

        $days = max(1, (int) Carbon::parse($validated['start_date'])->diffInDays(Carbon::parse($validated['end_date'])) + 1);
        $dailyRate = $this->resolveDailyRate($car);
        $totalAmount = $dailyRate * $days;

        $booking = Booking::create([
            'user_id' => $request->user()->id,
            'car_id' => $car->id,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'] ?? null,
            'customer_phone' => $validated['customer_phone'] ?? null,
            'pickup_location' => $validated['pickup_location'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'rental_days' => $days,
            'daily_rate' => $dailyRate,
            'total_amount' => $totalAmount,
            'final_total' => $totalAmount,
            'included_km' => $days * 150,
            'extra_km_rate' => 25,
            'currency' => 'LKR',
            'payment_method' => $validated['payment_method'],
            'payment_provider' => null,
            'payment_status' => 'pending',
            'status' => 'confirmed',
            'note' => $validated['note'] ?? null,
        ]);

        return redirect()->route('booking.success', $booking)->with('success', 'Booking confirmed. Payment is pending.');
    }

    public function success(Request $request, Booking $booking): View
    {
        if (!$this->canManageBooking($request, $booking)) {
            abort(403);
        }

        return view('booking.success', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        if (!$this->canManageBooking($request, $booking)) {
            abort(403);
        }

        if (in_array($booking->payment_method, ['pay_now_card', 'pay_now_payhere'], true) && $booking->payment_status === 'pending') {
            $booking->update([
                'payment_status' => 'failed',
                'status' => 'pending',
            ]);
        }

        return redirect()->route('booking.confirm', [
            'car_id' => $booking->car_id,
            'start_date' => $booking->start_date?->format('Y-m-d'),
            'end_date' => $booking->end_date?->format('Y-m-d'),
            'start_location' => $booking->pickup_location,
        ])->with('error', 'Payment was canceled. You can try again.');
    }

    public function payHereReturn(Booking $booking): RedirectResponse
    {
        return redirect()->route('booking.success', $booking);
    }

    public function payHereNotify(Request $request): string
    {
        $data = $request->all();
        $orderId = (string) ($data['order_id'] ?? '');
        $statusCode = (string) ($data['status_code'] ?? '');
        $receivedHash = strtoupper((string) ($data['md5sig'] ?? ''));
        $merchantId = (string) env('PAYHERE_MERCHANT_ID');
        $merchantSecret = (string) env('PAYHERE_MERCHANT_SECRET');

        if ($orderId === '' || $merchantId === '' || $merchantSecret === '') {
            return 'INVALID';
        }

        $booking = Booking::query()->where('gateway_reference', $orderId)->first();
        if (!$booking) {
            return 'NOT_FOUND';
        }

        $localMd5 = strtoupper(md5(
            $merchantId .
            $orderId .
            $data['payhere_amount'] .
            $data['payhere_currency'] .
            $statusCode .
            strtoupper(md5($merchantSecret))
        ));

        if ($receivedHash !== '' && $localMd5 !== $receivedHash) {
            return 'HASH_MISMATCH';
        }

        if ($statusCode === '2') {
            $booking->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
            ]);
        } elseif ($statusCode === '-1' || $statusCode === '-2' || $statusCode === '-3') {
            $booking->update([
                'payment_status' => 'failed',
                'status' => 'pending',
            ]);
        }

        return 'OK';
    }

    private function isCarAvailable(int $carId, string $startDate, string $endDate): bool
    {
        $agreementOverlap = Agreement::query()
            ->where('car_id', $carId)
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
            ->where('car_id', $carId)
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
            ->where('car_id', $carId)
            ->where('status', 'confirmed')
            ->whereDate('start_date', '<=', $endDate)
            ->whereDate('end_date', '>=', $startDate)
            ->exists();

        return !$bookingOverlap;
    }

    private function canManageBooking(Request $request, Booking $booking): bool
    {
        $user = $request->user();
        if (!$user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if ($booking->user_id && $booking->user_id === $user->id) {
            return true;
        }

        return !empty($booking->customer_email) && !empty($user->email)
            && strcasecmp((string) $booking->customer_email, (string) $user->email) === 0;
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
}
