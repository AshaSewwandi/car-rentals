<?php

namespace App\Http\Controllers;

use App\Mail\BookingInvoiceStatusMail;
use App\Models\Agreement;
use App\Models\Booking;
use App\Models\Car;
use App\Models\RentRequest;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class RentRequestController extends Controller
{
    public function index(Request $request): View
    {
        $rentRequests = RentRequest::query()
            ->with(['car', 'acceptedBy'])
            ->latest()
            ->paginate(15);

        $rentRequests->getCollection()->transform(function (RentRequest $request) {
            $isCheckable = (bool) ($request->car_id && $request->start_date && $request->end_date);
            $isAvailable = null;

            if ($isCheckable) {
                $isAvailable = $this->isCarAvailable(
                    (int) $request->car_id,
                    $request->start_date->toDateString(),
                    $request->end_date->toDateString()
                );
            }

            $request->setAttribute('is_checkable', $isCheckable);
            $request->setAttribute('is_available_for_period', $isAvailable);

            return $request;
        });

        $cars = Car::query()->orderBy('name')->get(['id', 'name', 'plate_no']);

        return view('rent-requests.index', compact(
            'rentRequests',
            'cars'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'car_id' => ['required', 'exists:cars,id'],
            'car_name' => ['nullable', 'string', 'max:180'],
            'plate_no' => ['nullable', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40', 'required_without:email'],
            'email' => ['nullable', 'email', 'max:180', 'required_without:phone'],
            'start_location' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'message' => ['nullable', 'string', 'max:3000'],
        ], [
            'name.required' => 'Please enter your name.',
            'phone.required_without' => 'Please enter phone or email.',
            'email.required_without' => 'Please enter email or phone.',
            'email.email' => 'Please enter a valid email address.',
            'start_location.required' => 'Please select pickup location before sending request.',
            'start_date.required' => 'Please select a start date before sending request.',
            'end_date.required' => 'Please select an end date before sending request.',
            'end_date.after_or_equal' => 'End date must be the same as or after start date.',
        ]);

        RentRequest::create($validated + ['status' => 'pending']);

        return back()->with('success', 'Your rent request has been submitted. Our team will contact you soon.');
    }

    public function accept(RentRequest $rentRequest): RedirectResponse
    {
        if (!$rentRequest->car_id || !$rentRequest->start_date || !$rentRequest->end_date) {
            return back()->with('error', 'Vehicle, start date, and end date are required before accepting this request.');
        }

        $isAvailable = $this->isCarAvailable(
            (int) $rentRequest->car_id,
            $rentRequest->start_date->toDateString(),
            $rentRequest->end_date->toDateString()
        );

        if (!$isAvailable) {
            return back()->with('error', 'Selected vehicle is not available in requested dates.');
        }

        if ($rentRequest->status === 'converted') {
            return back()->with('success', 'Request is already converted to a booking.');
        }

        $car = Car::findOrFail((int) $rentRequest->car_id);
        $days = max(1, (int) $rentRequest->start_date->diffInDays($rentRequest->end_date) + 1);
        $dailyRate = $this->resolveDailyRate($car);
        $totalAmount = $dailyRate * $days;

        $matchedUserId = null;
        if ($rentRequest->email) {
            $matchedUserId = User::query()
                ->where('email', $rentRequest->email)
                ->value('id');
        }

        $booking = Booking::create([
            'user_id' => $matchedUserId,
            'car_id' => $car->id,
            'customer_name' => $rentRequest->name,
            'customer_email' => $rentRequest->email,
            'customer_phone' => $rentRequest->phone,
            'pickup_location' => $rentRequest->start_location,
            'start_date' => $rentRequest->start_date->toDateString(),
            'end_date' => $rentRequest->end_date->toDateString(),
            'rental_days' => $days,
            'daily_rate' => $dailyRate,
            'total_amount' => $totalAmount,
            'final_total' => $totalAmount,
            'included_km' => $days * 150,
            'extra_km_rate' => 25,
            'currency' => 'LKR',
            'payment_method' => 'pay_later_bank',
            'payment_provider' => null,
            'payment_status' => 'pending',
            'status' => 'confirmed',
            'note' => $rentRequest->message,
        ]);

        $rentRequest->update([
            'status' => 'converted',
            'accepted_by' => auth()->id(),
            'accepted_at' => now(),
        ]);

        $this->sendConvertedBookingEmailsAfterResponse($booking->id);

        return back()->with('success', 'Rent request converted to booking successfully.');
    }

    public function update(Request $request, RentRequest $rentRequest): RedirectResponse
    {
        $validated = $request->validate([
            'start_location' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $rentRequest->update($validated);

        return back()->with('success', 'Rent request dates and locations updated successfully.');
    }

    public function destroy(RentRequest $rentRequest): RedirectResponse
    {
        $rentRequest->delete();

        return back()->with('success', 'Rent request canceled successfully.');
    }

    private function isCarAvailable(int $carId, string $startDate, string $endDate): bool
    {
        $hasAgreementOverlap = Agreement::query()
            ->where('car_id', $carId)
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $endDate)
            ->where(function ($query) use ($startDate) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $startDate);
            })
            ->exists();

        if ($hasAgreementOverlap) {
            return false;
        }

        $hasRentalOverlap = Rental::query()
            ->where('car_id', $carId)
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $endDate)
            ->where(function ($query) use ($startDate) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $startDate);
            })
            ->exists();

        if ($hasRentalOverlap) {
            return false;
        }

        $hasConfirmedBookingOverlap = Booking::query()
            ->where('car_id', $carId)
            ->where('status', 'confirmed')
            ->whereDate('start_date', '<=', $endDate)
            ->whereDate('end_date', '>=', $startDate)
            ->exists();

        return !$hasConfirmedBookingOverlap;
    }

    private function resolveDailyRate(Car $car): float
    {
        if ($car->note && preg_match('/(?:rs\\.?\\s*)?([\\d,]+(?:\\.\\d{1,2})?)/i', $car->note, $matches)) {
            return (float) str_replace(',', '', $matches[1]);
        }

        $plateKey = strtoupper((string) preg_replace('/[^A-Za-z0-9]/', '', (string) $car->plate_no));
        $knownRates = [
            'CAK8043' => 4000,
            'CAK9010' => 4000,
            'CAK9792' => 4000,
            '588233' => 8000,
        ];

        return (float) ($knownRates[$plateKey] ?? 4500);
    }

    private function sendConvertedBookingEmailsAfterResponse(int $bookingId): void
    {
        dispatch(function () use ($bookingId) {
            $booking = Booking::query()->with(['car.partner', 'user'])->find($bookingId);
            if (!$booking) {
                return;
            }

            $recipients = collect();

            if (!empty($booking->customer_email)) {
                $recipients->push((string) $booking->customer_email);
            }

            if (!empty($booking->car?->partner?->email)) {
                $recipients->push((string) $booking->car->partner->email);
            }

            User::query()
                ->where('role', 'admin')
                ->whereNotNull('email')
                ->pluck('email')
                ->each(fn ($email) => $recipients->push((string) $email));

            $recipients
                ->filter()
                ->map(fn ($email) => strtolower(trim((string) $email)))
                ->unique()
                ->each(function (string $email) use ($booking) {
                    try {
                        Mail::to($email)->queue(new BookingInvoiceStatusMail($booking, 'confirmed'));
                    } catch (Throwable $e) {
                        report($e);
                    }
                });
        })->afterResponse();
    }
}
