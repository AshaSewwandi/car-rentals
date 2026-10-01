<?php

namespace App\Http\Controllers;

use App\Mail\BookingInvoiceStatusMail;
use App\Mail\GuestAccountCreatedMail;
use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use App\Support\ManualBookingPricing;
use App\Support\RevenueShareResolver;
use App\Support\TripNoteBuilder;
use App\Support\VehicleAvailabilityChecker;
use App\Support\VehiclePricingResolver;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class OrderController extends Controller
{
    public function create(Request $request): View
    {
        $vehicles = Vehicle::query()
            ->visibleOnPublic()
            ->orderBy('name')
            ->get(['id', 'name', 'plate_no', 'driver_mode', 'available_for_hire', 'available_for_rent']);

        $quote = null;
        $error = null;

        if ($request->filled('vehicle_id') && $request->filled('start_date') && $request->filled('end_date')) {
            $validated = $request->validate([
                'vehicle_id' => ['required', 'exists:vehicles,id'],
                'start_date' => ['required', 'date'],
                'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            ]);

            $vehicle = Vehicle::query()->visibleOnPublic()->find($validated['vehicle_id']);

            if (!$vehicle) {
                $error = 'Selected vehicle is not available for booking.';
            } elseif (!VehicleAvailabilityChecker::isAvailable($vehicle->id, $validated['start_date'], $validated['end_date'])) {
                $error = 'Selected vehicle is not available in this date range.';
            } else {
                $days = max(1, (int) Carbon::parse($validated['start_date'])->diffInDays(Carbon::parse($validated['end_date'])) + 1);
                $pricing = VehiclePricingResolver::resolveForVehicle($vehicle);
                $dailyRate = (float) $pricing['daily_rate'];
                $driverRate = (float) $pricing['driver_cost_per_day'];
                $driverMode = (string) ($vehicle->driver_mode ?: 'both');
                $defaultDriverOption = $driverMode === 'with_driver_only' ? 'with_driver' : 'without_driver';
                $driverTotal = $defaultDriverOption === 'with_driver' ? $driverRate * $days : 0.0;
                $totalAmount = ($dailyRate * $days) + $driverTotal;
                $revenueSplit = RevenueShareResolver::percentagesForVehicle($vehicle);
                $shareableAmount = RevenueShareResolver::shareableAmount($totalAmount, $driverTotal);

                $quote = [
                    'vehicle' => $vehicle,
                    'start_date' => $validated['start_date'],
                    'end_date' => $validated['end_date'],
                    'rental_days' => $days,
                    'driver_mode' => $driverMode,
                    'default_driver_option' => $defaultDriverOption,
                    'available_for_hire' => (bool) $vehicle->available_for_hire,
                    'available_for_rent' => (bool) $vehicle->available_for_rent,
                    'daily_rate' => $dailyRate,
                    'driver_rate' => $driverRate,
                    'total_amount' => round($totalAmount, 2),
                    'partner_share_amount' => round($shareableAmount * ($revenueSplit['partner_share_percentage'] / 100), 2),
                    'admin_share_amount' => round($shareableAmount * ($revenueSplit['admin_share_percentage'] / 100), 2),
                ];
            }
        }

        return view('orders.create', [
            'vehicles' => $vehicles,
            'filters' => $request->only(['vehicle_id', 'start_date', 'end_date']),
            'quote' => $quote,
            'error' => $error,
        ]);
    }

    public function searchCustomers(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:100'],
        ]);

        $term = '%' . $validated['q'] . '%';

        $customers = User::query()
            ->whereIn('role', ['customer', 'customer_portal'])
            ->where(function ($query) use ($term) {
                $query->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('phone', 'like', $term);
            })
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'email', 'phone']);

        return response()->json($customers);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'pickup_location' => ['nullable', 'string', 'max:255'],
            'stops' => ['nullable', 'array'],
            'stops.*' => ['nullable', 'string', 'max:255'],
            'final_destination' => ['nullable', 'string', 'max:255'],
            'driver_option' => ['required', 'in:without_driver,with_driver'],
            'order_type' => ['required', 'in:hire,rent'],
            'daily_rate' => ['required', 'numeric', 'min:0'],
            'driver_rate' => ['required', 'numeric', 'min:0'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'partner_share_amount' => ['required', 'numeric', 'min:0'],
            'admin_share_amount' => ['required', 'numeric', 'min:0'],
            'customer_user_id' => ['nullable', 'exists:users,id'],
            'customer_name' => ['nullable', 'required_without:customer_user_id', 'string', 'max:120'],
            'customer_email' => ['nullable', 'required_without:customer_user_id', 'email', 'max:180'],
            'customer_phone' => ['nullable', 'required_without:customer_user_id', 'string', 'max:40'],
            'payment_method' => ['required', 'in:pay_later_bank,pay_at_pickup_cash'],
            'payment_status' => ['required', 'in:pending,paid'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $vehicle = Vehicle::query()->visibleOnPublic()->find($validated['vehicle_id']);
        if (!$vehicle) {
            return back()->withInput()->with('error', 'Selected vehicle is not available for booking.');
        }

        $driverMode = (string) ($vehicle->driver_mode ?: 'both');
        $validated['driver_option'] = match ($driverMode) {
            'with_driver_only' => 'with_driver',
            'without_driver_only' => 'without_driver',
            default => $validated['driver_option'],
        };

        if (!VehicleAvailabilityChecker::isAvailable($vehicle->id, $validated['start_date'], $validated['end_date'])) {
            return back()->withInput()->with('error', 'Vehicle became unavailable for the selected dates.');
        }

        if ($validated['order_type'] === 'hire' && !$vehicle->available_for_hire) {
            return back()->withInput()->with('error', 'This vehicle is not available for hire.');
        }
        if ($validated['order_type'] === 'rent' && !$vehicle->available_for_rent) {
            return back()->withInput()->with('error', 'This vehicle is not available for rent.');
        }

        $days = max(1, (int) Carbon::parse($validated['start_date'])->diffInDays(Carbon::parse($validated['end_date'])) + 1);

        [$customer, $newAccountCreated] = $this->resolveCustomer($validated);

        $pricingAttrs = ManualBookingPricing::fromAdminInput(
            $vehicle,
            $days,
            $validated['driver_option'],
            (float) $validated['daily_rate'],
            (float) $validated['driver_rate'],
            (float) $validated['total_amount'],
            (float) $validated['partner_share_amount'],
            (float) $validated['admin_share_amount']
        );

        $noteText = TripNoteBuilder::build(
            $validated['stops'] ?? [],
            $validated['final_destination'] ?? null,
            $validated['note'] ?? null
        );

        $booking = Booking::create(array_merge($pricingAttrs, [
            'user_id' => $customer->id,
            'created_by' => $request->user()->id,
            'vehicle_id' => $vehicle->id,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => $customer->phone,
            'pickup_location' => $validated['pickup_location'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'rental_days' => $days,
            'driver_option' => $validated['driver_option'],
            'order_type' => $validated['order_type'],
            'payment_method' => $validated['payment_method'],
            'payment_provider' => null,
            'payment_status' => $validated['payment_status'],
            'status' => 'confirmed',
            'note' => $noteText,
        ]));

        $this->sendBookingInvoiceEmailAfterResponse($booking->id, 'confirmed');

        $successMessage = 'Order placed successfully.';
        if ($newAccountCreated) {
            $successMessage .= ' A customer account was created and a temporary password email will be sent.';
        }

        return redirect()->route('rental-trips.index')->with('success', $successMessage);
    }

    /**
     * @return array{0: User, 1: bool}
     */
    private function resolveCustomer(array $validated): array
    {
        if (!empty($validated['customer_user_id'])) {
            return [User::query()->findOrFail($validated['customer_user_id']), false];
        }

        $email = strtolower(trim((string) $validated['customer_email']));
        $phone = trim((string) $validated['customer_phone']);
        $name = trim((string) $validated['customer_name']);

        $existingUser = User::query()->whereRaw('LOWER(email) = ?', [$email])->first();
        if ($existingUser) {
            $updates = [];
            if (empty($existingUser->phone) && $phone !== '') {
                $updates['phone'] = $phone;
            }
            if (empty($existingUser->name) && $name !== '') {
                $updates['name'] = $name;
            }
            if (!empty($updates)) {
                $existingUser->update($updates);
            }

            return [$existingUser, false];
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

        return [$newUser, true];
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

    private function sendBookingInvoiceEmailAfterResponse(int $bookingId, string $stage): void
    {
        dispatch(function () use ($bookingId, $stage) {
            $booking = Booking::query()->with(['vehicle.partner', 'user'])->find($bookingId);
            if (!$booking) {
                return;
            }

            $recipients = collect();

            $customerEmail = (string) ($booking->customer_email ?: $booking->user?->email ?: '');
            if ($customerEmail !== '') {
                $recipients->push($customerEmail);
            }

            $partnerEmail = (string) ($booking->vehicle?->partner?->email ?: '');
            if ($partnerEmail !== '') {
                $recipients->push($partnerEmail);
            }

            User::query()
                ->whereIn('role', ['admin', 'super_admin'])
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
}
