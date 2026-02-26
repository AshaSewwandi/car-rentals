<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RentalTripController extends Controller
{
    public function index(): View
    {
        $bookings = Booking::query()
            ->with('car')
            ->orderByDesc('id')
            ->paginate(20);

        return view('rental-trips.index', compact('bookings'));
    }

    public function handover(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'start_mileage' => ['required', 'numeric', 'min:0'],
        ]);

        $booking->update([
            'start_mileage' => $data['start_mileage'],
            'handover_at' => now(),
            'status' => $booking->status === 'pending' ? 'confirmed' : $booking->status,
        ]);

        return back()->with('success', 'Trip handover mileage recorded.');
    }

    public function returnTrip(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'end_mileage' => ['required', 'numeric', 'min:0'],
        ]);

        if ($booking->start_mileage === null) {
            return back()->with('error', 'Start mileage is required before return.');
        }

        $startMileage = (float) $booking->start_mileage;
        $endMileage = (float) $data['end_mileage'];

        if ($endMileage < $startMileage) {
            return back()->with('error', 'End mileage cannot be less than start mileage.');
        }

        $usedKm = round($endMileage - $startMileage, 2);
        $includedKm = round((float) ($booking->rental_days * 150), 2);
        $extraKm = round(max($usedKm - $includedKm, 0), 2);
        $extraRate = round((float) ($booking->extra_km_rate ?? 25), 2);
        $extraCharge = round($extraKm * $extraRate, 2);
        $finalTotal = round((float) $booking->total_amount + $extraCharge, 2);

        $booking->update([
            'end_mileage' => $endMileage,
            'used_km' => $usedKm,
            'included_km' => $includedKm,
            'extra_km' => $extraKm,
            'extra_km_charge' => $extraCharge,
            'final_total' => $finalTotal,
            'returned_at' => now(),
            'returned_by' => auth()->id(),
            'status' => 'completed',
        ]);

        return back()->with('success', 'Trip return recorded and final amount calculated.');
    }
}

