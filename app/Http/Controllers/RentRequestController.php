<?php

namespace App\Http\Controllers;

use App\Mail\BookingInvoiceStatusMail;
use App\Models\Booking;
use App\Models\RentRequest;
use App\Models\User;
use App\Models\Vehicle;
use App\Support\ManualBookingPricing;
use App\Support\RevenueShareResolver;
use App\Support\TripNoteBuilder;
use App\Support\VehicleAvailabilityChecker;
use App\Support\VehiclePricingResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class RentRequestController extends Controller
{
    public function create(): View
    {
        return view('rent-requests.create');
    }

    public function index(): View
    {
        $rentRequests = RentRequest::query()
            ->with(['vehicle', 'acceptedBy'])
            ->latest()
            ->paginate(15);

        $vehicles = Vehicle::query()
            ->with('partner')
            ->orderBy('name')
            ->get(['id', 'name', 'plate_no', 'make', 'model', 'partner_user_id', 'driver_mode', 'available_for_hire', 'available_for_rent']);

        $vehiclePricing = $vehicles->mapWithKeys(function (Vehicle $vehicle) {
            $pricing = VehiclePricingResolver::resolveForVehicle($vehicle);
            $revenueSplit = RevenueShareResolver::percentagesForVehicle($vehicle);

            return [$vehicle->id => [
                'daily_rate' => (float) $pricing['daily_rate'],
                'driver_rate' => (float) $pricing['driver_cost_per_day'],
                'driver_mode' => (string) ($vehicle->driver_mode ?: 'both'),
                'available_for_hire' => (bool) $vehicle->available_for_hire,
                'available_for_rent' => (bool) $vehicle->available_for_rent,
                'partner_share_percentage' => (float) $revenueSplit['partner_share_percentage'],
                'admin_share_percentage' => (float) $revenueSplit['admin_share_percentage'],
            ]];
        });

        return view('rent-requests.index', compact(
            'rentRequests',
            'vehicles',
            'vehiclePricing'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40', 'required_without:email'],
            'email' => ['nullable', 'email', 'max:180', 'required_without:phone'],
            'passenger_count' => ['required', 'integer', 'min:1'],
            'start_location' => ['required', 'string', 'max:255'],
            'final_destination' => ['required', 'string', 'max:255'],
            'stops' => ['nullable', 'array'],
            'stops.*' => ['nullable', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'message' => ['nullable', 'string', 'max:3000'],
        ], [
            'name.required' => 'Please enter your name.',
            'phone.required_without' => 'Please enter phone or email.',
            'email.required_without' => 'Please enter email or phone.',
            'email.email' => 'Please enter a valid email address.',
            'passenger_count.required' => 'Please enter the number of passengers.',
            'start_location.required' => 'Please select pickup location before sending request.',
            'final_destination.required' => 'Please enter your final destination.',
            'start_date.required' => 'Please select a start date before sending request.',
            'end_date.required' => 'Please select an end date before sending request.',
            'end_date.after_or_equal' => 'End date must be the same as or after start date.',
        ]);

        $validated['stops'] = collect($validated['stops'] ?? [])
            ->map(fn ($stop) => trim((string) $stop))
            ->filter(fn ($stop) => $stop !== '')
            ->values()
            ->all();

        RentRequest::create($validated + ['status' => 'pending']);

        return back()->with('success', 'Your trip request has been submitted. Our team will contact you soon.');
    }

    public function accept(Request $request, RentRequest $rentRequest): RedirectResponse
    {
        if ($rentRequest->status === 'converted') {
            return back()->with('success', 'Request is already converted to a booking.');
        }

        if (!$rentRequest->start_date || !$rentRequest->end_date) {
            return back()->with('error', 'Start date and end date are required before accepting this request.');
        }

        $validated = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'order_type' => ['required', 'in:hire,rent'],
            'driver_option' => ['required', 'in:without_driver,with_driver'],
            'daily_rate' => ['required', 'numeric', 'min:0'],
            'driver_rate' => ['required', 'numeric', 'min:0'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'partner_share_amount' => ['required', 'numeric', 'min:0'],
            'admin_share_amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:pay_later_bank,pay_at_pickup_cash'],
            'payment_status' => ['required', 'in:pending,paid'],
            'note' => ['nullable', 'string', 'max:1000'],
        ], [
            'vehicle_id.required' => 'Please select a vehicle for this trip.',
            'total_amount.required' => 'Please enter the quoted total cost for this trip.',
        ]);

        $vehicle = Vehicle::findOrFail((int) $validated['vehicle_id']);

        $isAvailable = VehicleAvailabilityChecker::isAvailable(
            $vehicle->id,
            $rentRequest->start_date->toDateString(),
            $rentRequest->end_date->toDateString()
        );

        if (!$isAvailable) {
            return back()->with('error', 'Selected vehicle is not available in requested dates.');
        }

        $driverMode = (string) ($vehicle->driver_mode ?: 'both');
        $validated['driver_option'] = match ($driverMode) {
            'with_driver_only' => 'with_driver',
            'without_driver_only' => 'without_driver',
            default => $validated['driver_option'],
        };

        if ($validated['order_type'] === 'hire' && !$vehicle->available_for_hire) {
            return back()->with('error', 'This vehicle is not available for hire.');
        }
        if ($validated['order_type'] === 'rent' && !$vehicle->available_for_rent) {
            return back()->with('error', 'This vehicle is not available for rent.');
        }

        $days = max(1, (int) $rentRequest->start_date->diffInDays($rentRequest->end_date) + 1);

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

        $matchedUserId = null;
        if ($rentRequest->email) {
            $matchedUserId = User::query()
                ->where('email', $rentRequest->email)
                ->value('id');
        }

        $booking = Booking::create(array_merge($pricingAttrs, [
            'user_id' => $matchedUserId,
            'created_by' => $request->user()->id,
            'vehicle_id' => $vehicle->id,
            'customer_name' => $rentRequest->name,
            'customer_email' => $rentRequest->email,
            'customer_phone' => $rentRequest->phone,
            'pickup_location' => $rentRequest->start_location,
            'start_date' => $rentRequest->start_date->toDateString(),
            'end_date' => $rentRequest->end_date->toDateString(),
            'rental_days' => $days,
            'driver_option' => $validated['driver_option'],
            'order_type' => $validated['order_type'],
            'payment_method' => $validated['payment_method'],
            'payment_provider' => null,
            'payment_status' => $validated['payment_status'],
            'status' => 'confirmed',
            'note' => $this->buildTripNote($rentRequest, $validated['note'] ?? null),
        ]));

        $rentRequest->update([
            'status' => 'converted',
            'accepted_by' => auth()->id(),
            'accepted_at' => now(),
        ]);

        $this->sendConvertedBookingEmailsAfterResponse($booking->id);

        return back()->with('success', 'Trip request converted to booking successfully.');
    }

    public function update(Request $request, RentRequest $rentRequest): RedirectResponse
    {
        $validated = $request->validate([
            'start_location' => ['nullable', 'string', 'max:255'],
            'final_destination' => ['nullable', 'string', 'max:255'],
            'stops' => ['nullable', 'string'],
            'passenger_count' => ['nullable', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $validated['stops'] = collect(preg_split('/\r\n|\r|\n/', (string) ($validated['stops'] ?? '')))
            ->map(fn ($stop) => trim((string) $stop))
            ->filter(fn ($stop) => $stop !== '')
            ->values()
            ->all();

        $rentRequest->update($validated);

        return back()->with('success', 'Trip request details updated successfully.');
    }

    public function destroy(RentRequest $rentRequest): RedirectResponse
    {
        $rentRequest->delete();

        return back()->with('success', 'Trip request canceled successfully.');
    }

    private function buildTripNote(RentRequest $rentRequest, ?string $adminNote = null): ?string
    {
        $tripNote = TripNoteBuilder::build(
            $rentRequest->stops,
            $rentRequest->final_destination,
            $rentRequest->message,
            $rentRequest->passenger_count
        );

        $adminNote = trim((string) $adminNote);
        if ($adminNote === '') {
            return $tripNote;
        }

        return $tripNote ? $tripNote . "\n" . $adminNote : $adminNote;
    }

    private function sendConvertedBookingEmailsAfterResponse(int $bookingId): void
    {
        dispatch(function () use ($bookingId) {
            $booking = Booking::query()->with(['vehicle.partner', 'user'])->find($bookingId);
            if (!$booking) {
                return;
            }

            $recipients = collect();

            if (!empty($booking->customer_email)) {
                $recipients->push((string) $booking->customer_email);
            }

            if (!empty($booking->vehicle?->partner?->email)) {
                $recipients->push((string) $booking->vehicle->partner->email);
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
