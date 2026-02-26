<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Car;
use App\Models\RentRequest;
use App\Models\Rental;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
            'car_id' => ['nullable', 'exists:cars,id'],
            'car_name' => ['nullable', 'string', 'max:180'],
            'plate_no' => ['nullable', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40', 'required_without:email'],
            'email' => ['nullable', 'email', 'max:180', 'required_without:phone'],
            'start_location' => ['nullable', 'string', 'max:255'],
            'end_location' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'message' => ['nullable', 'string', 'max:3000'],
        ]);

        RentRequest::create($validated + ['status' => 'pending']);

        return back()->with('success', 'Your rent request has been submitted. Our team will contact you soon.');
    }

    public function accept(RentRequest $rentRequest): RedirectResponse
    {
        if ($rentRequest->car_id && $rentRequest->start_date && $rentRequest->end_date) {
            $isAvailable = $this->isCarAvailable(
                (int) $rentRequest->car_id,
                $rentRequest->start_date->toDateString(),
                $rentRequest->end_date->toDateString()
            );

            if (!$isAvailable) {
                return back()->with('error', 'Selected vehicle is not available in requested dates.');
            }
        }

        if ($rentRequest->status !== 'accepted') {
            $rentRequest->update([
                'status' => 'accepted',
                'accepted_by' => auth()->id(),
                'accepted_at' => now(),
            ]);
        }

        return back()->with('success', 'Rent request accepted successfully.');
    }

    public function update(Request $request, RentRequest $rentRequest): RedirectResponse
    {
        $validated = $request->validate([
            'start_location' => ['nullable', 'string', 'max:255'],
            'end_location' => ['nullable', 'string', 'max:255'],
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
        $hasAgreementOverlap = \App\Models\Agreement::query()
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

        $hasRentalOverlap = \App\Models\Rental::query()
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

        $hasAcceptedRequestOverlap = RentRequest::query()
            ->where('car_id', $carId)
            ->where('status', 'accepted')
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->whereDate('start_date', '<=', $endDate)
            ->whereDate('end_date', '>=', $startDate)
            ->exists();

        return !$hasAcceptedRequestOverlap;
    }
}
