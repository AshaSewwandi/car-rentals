<?php

namespace App\Http\Controllers;

use App\Mail\GuestAccountCreatedMail;
use App\Mail\BookingInvoiceStatusMail;
use App\Models\Agreement;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Rental;
use App\Models\User;
use App\Support\RevenueShareResolver;
use App\Support\VehiclePricingResolver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class BookingController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'car_id' => ['required', 'exists:cars,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'start_location' => ['nullable', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
            'pickup_time' => ['nullable', 'string', 'max:40'],
            'note' => ['nullable', 'string', 'max:1000'],
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
        $pricing = VehiclePricingResolver::resolveForCar($car);
        $dailyRate = $pricing['daily_rate'];
        $driverCostPerDay = $pricing['driver_cost_per_day'];
        $driverTotal = 0;
        $totalAmount = $dailyRate * $days;
        $driverMode = (string) ($car->driver_mode ?: 'both');
        $defaultDriverOption = $driverMode === 'with_driver_only' ? 'with_driver' : 'without_driver';
        $prefillNote = trim((string) ($validated['note'] ?? ''));

        if ($prefillNote === '') {
            $destination = trim((string) ($validated['destination'] ?? ''));
            $pickupTime = trim((string) ($validated['pickup_time'] ?? ''));
            $parts = [];

            if ($destination !== '') {
                $parts[] = "Destination: {$destination}";
            }

            if ($pickupTime !== '') {
                $parts[] = "Preferred pickup time: {$pickupTime}";
            }

            $prefillNote = implode("\n", $parts);
        }

        return view('booking.confirm', [
            'car' => $car,
            'filters' => $validated,
            'rentalDays' => $days,
            'dailyRate' => $dailyRate,
            'perDayKm' => $pricing['per_day_km'],
            'extraKmRate' => $pricing['extra_km_rate'],
            'driverCostPerDay' => $driverCostPerDay,
            'driverTotal' => $driverTotal,
            'totalAmount' => $totalAmount,
            'driverMode' => $driverMode,
            'defaultDriverOption' => $defaultDriverOption,
            'prefillNote' => $prefillNote,
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
            'customer_email' => ['required', 'email', 'max:180'],
            'customer_phone' => ['required', 'string', 'max:40'],
            'payment_method' => ['required', 'in:pay_later_bank,pay_at_pickup_cash'],
            'driver_option' => ['required', 'in:without_driver,with_driver'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $car = Car::findOrFail($validated['car_id']);
        $driverMode = (string) ($car->driver_mode ?: 'both');

        if (!$this->isCarAvailable($car->id, $validated['start_date'], $validated['end_date'])) {
            return back()->withInput()->with('error', 'Car became unavailable for the selected dates.');
        }

        $validated['driver_option'] = match ($driverMode) {
            'with_driver_only' => 'with_driver',
            'without_driver_only' => 'without_driver',
            default => $validated['driver_option'],
        };

        $days = max(1, (int) Carbon::parse($validated['start_date'])->diffInDays(Carbon::parse($validated['end_date'])) + 1);
        $pricing = VehiclePricingResolver::resolveForCar($car);
        $dailyRate = $pricing['daily_rate'];
        $driverRate = $validated['driver_option'] === 'with_driver' ? (float) $pricing['driver_cost_per_day'] : 0;
        $driverTotal = $driverRate * $days;
        $totalAmount = ($dailyRate * $days) + $driverTotal;
        $revenueSplit = RevenueShareResolver::percentagesForCar($car);
        $shareableAmount = RevenueShareResolver::shareableAmount($totalAmount, $driverTotal);
        [$bookingUser, $guestAccountCreated, $guestAccountMailSent] = $this->resolveBookingUser($request, $validated);

        $booking = Booking::create([
            'user_id' => $bookingUser?->id,
            'car_id' => $car->id,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'] ?? null,
            'customer_phone' => $validated['customer_phone'] ?? null,
            'pickup_location' => $validated['pickup_location'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'rental_days' => $days,
            'driver_option' => $validated['driver_option'],
            'daily_rate' => $dailyRate,
            'driver_rate' => $driverRate,
            'total_amount' => $totalAmount,
            'driver_total' => $driverTotal,
            'final_total' => $totalAmount,
            'partner_share_percentage' => $revenueSplit['partner_share_percentage'],
            'admin_share_percentage' => $revenueSplit['admin_share_percentage'],
            'partner_share_amount' => round($shareableAmount * ($revenueSplit['partner_share_percentage'] / 100), 2),
            'admin_share_amount' => round($shareableAmount * ($revenueSplit['admin_share_percentage'] / 100), 2),
            'included_km' => $days * $pricing['per_day_km'],
            'extra_km_rate' => $pricing['extra_km_rate'],
            'currency' => 'LKR',
            'payment_method' => $validated['payment_method'],
            'payment_provider' => null,
            'payment_status' => 'pending',
            'status' => 'confirmed',
            'note' => $validated['note'] ?? null,
        ]);

        $this->rememberGuestBooking($request, $booking->id);
        if (!$request->user() && $bookingUser) {
            Auth::login($bookingUser);
            $request->session()->regenerate();
        }
        $this->sendBookingInvoiceEmailAfterResponse($booking->id, 'confirmed');

        $successMessage = 'Booking confirmed. Payment is pending.';
        if ($guestAccountCreated) {
            $successMessage .= ' Customer account created and temporary password email will be sent to your email.';
        }

        return redirect()->route('booking.success', $booking)->with('success', $successMessage);
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
            $guestBookingIds = (array) $request->session()->get('guest_booking_ids', []);
            return in_array($booking->id, $guestBookingIds, true);
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

    private function rememberGuestBooking(Request $request, int $bookingId): void
    {
        $guestBookingIds = (array) $request->session()->get('guest_booking_ids', []);
        if (!in_array($bookingId, $guestBookingIds, true)) {
            $guestBookingIds[] = $bookingId;
        }

        $request->session()->put('guest_booking_ids', array_values(array_unique($guestBookingIds)));
    }

    private function sendBookingInvoiceEmailAfterResponse(int $bookingId, string $stage): void
    {
        dispatch(function () use ($bookingId, $stage) {
            $booking = Booking::query()->with(['car.partner', 'user'])->find($bookingId);
            if (!$booking) {
                return;
            }

            $recipients = collect();

            $customerEmail = (string) ($booking->customer_email ?: $booking->user?->email ?: '');
            if ($customerEmail !== '') {
                $recipients->push($customerEmail);
            }

            $partnerEmail = (string) ($booking->car?->partner?->email ?: '');
            if ($partnerEmail !== '') {
                $recipients->push($partnerEmail);
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
                ->each(function (string $email) use ($booking, $stage) {
                    try {
                        Mail::to($email)->queue(new BookingInvoiceStatusMail($booking, $stage));
                    } catch (Throwable $e) {
                        report($e);
                    }
                });
        })->afterResponse();
    }

    /**
     * @return array{0: User|null, 1: bool, 2: bool}
     */
    private function resolveBookingUser(Request $request, array $validated): array
    {
        $loggedInUser = $request->user();
        if ($loggedInUser) {
            return [$loggedInUser, false, false];
        }

        $email = strtolower(trim((string) ($validated['customer_email'] ?? '')));
        $phone = trim((string) ($validated['customer_phone'] ?? ''));
        $name = trim((string) ($validated['customer_name'] ?? ''));

        if ($email === '') {
            return [null, false, false];
        }

        $existingUser = User::query()->whereRaw('LOWER(email) = ?', [$email])->first();
        if ($existingUser) {
            $updates = [];
            if (empty($existingUser->phone) && $phone !== '') {
                $updates['phone'] = $phone;
            }
            if (empty($existingUser->name) && $name !== '') {
                $updates['name'] = $name;
            }
            if (($existingUser->role ?? '') !== 'admin' && empty($existingUser->role)) {
                $updates['role'] = 'customer';
            }
            if (!empty($updates)) {
                $existingUser->update($updates);
            }

            return [$existingUser, false, false];
        }

        $temporaryPassword = Str::upper(Str::random(4)) . random_int(1000, 9999) . Str::lower(Str::random(2));
        $newUser = User::create([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'password' => Hash::make($temporaryPassword),
            'role' => 'customer',
        ]);

        $this->sendGuestAccountCreatedEmailAfterResponse($newUser->id, $temporaryPassword);

        return [$newUser, true, true];
    }

    private function sendGuestAccountCreatedEmailAfterResponse(int $userId, string $temporaryPassword): void
    {
        dispatch(function () use ($userId, $temporaryPassword) {
            $user = User::query()->find($userId);
            if (!$user || empty($user->email)) {
                return;
            }

            try {
                Mail::to($user->email)->queue(new GuestAccountCreatedMail($user, $temporaryPassword));
            } catch (Throwable $e) {
                report($e);
            }
        })->afterResponse();
    }
}
