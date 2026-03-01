<?php

namespace App\Http\Controllers;

use App\Mail\BookingInvoiceStatusMail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Illuminate\View\View;

class RentalTripController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $this->validatedFilters($request);
        $bookingsQuery = $this->buildFilteredBookingsQuery($filters);

        $bookings = $bookingsQuery->paginate(20)->withQueryString();

        $cars = Car::query()
            ->orderBy('name')
            ->orderBy('plate_no')
            ->get(['id', 'name', 'plate_no']);

        return view('rental-trips.index', [
            'bookings' => $bookings,
            'cars' => $cars,
            'filters' => $filters,
        ]);
    }

    public function exportPdf(Request $request): Response
    {
        $filters = $this->validatedFilters($request);
        $bookings = $this->buildFilteredBookingsQuery($filters)
            ->with('returnedBy')
            ->get();

        $selectedCar = null;
        if (!empty($filters['car_id'])) {
            $selectedCar = Car::query()->find($filters['car_id']);
        }

        $pdf = Pdf::loadView('rental-trips.report-pdf', [
            'bookings' => $bookings,
            'filters' => $filters,
            'selectedCar' => $selectedCar,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('rental-trips-' . now()->format('Ymd-His') . '.pdf');
    }

    public function invoicePdf(Booking $booking): Response
    {
        $booking->load(['car', 'returnedBy', 'user']);

        $baseAmount = (float) $booking->total_amount;
        $additionalAmount = (float) ($booking->additional_payment_amount ?? $booking->extra_km_charge ?? 0);
        $finalAmount = (float) ($booking->final_total ?? $booking->total_amount);

        $pdf = Pdf::loadView('rental-trips.invoice-pdf', [
            'booking' => $booking,
            'generatedAt' => now(),
            'baseAmount' => $baseAmount,
            'additionalAmount' => $additionalAmount,
            'finalAmount' => $finalAmount,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('rental-trip-invoice-' . $booking->id . '.pdf');
    }

    public function handover(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->status === 'cancelled') {
            return back()->with('error', 'Cancelled trips cannot be started.');
        }

        $data = $request->validate([
            'start_mileage' => ['required', 'string', 'max:30'],
        ]);

        $startMileage = $this->parseMileageInput($data['start_mileage']);
        if ($startMileage === null || $startMileage < 0) {
            return back()->with('error', 'Please enter a valid start KM value.');
        }

        $booking->update([
            'start_mileage' => $startMileage,
            'handover_at' => now(),
            'status' => $booking->status === 'pending' ? 'confirmed' : $booking->status,
        ]);

        return back()->with('success', 'Trip handover mileage recorded.');
    }

    public function returnTrip(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->status === 'cancelled') {
            return back()->with('error', 'Cancelled trips cannot be completed.');
        }

        $data = $request->validate([
            'end_mileage' => ['required', 'string', 'max:30'],
        ]);

        if ($booking->start_mileage === null) {
            return back()->with('error', 'Start mileage is required before return.');
        }

        $startMileage = (float) $booking->start_mileage;
        $endMileage = $this->parseMileageInput($data['end_mileage']);

        if ($endMileage === null || $endMileage < 0) {
            return back()->with('error', 'Please enter a valid end KM value.');
        }

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
            'additional_payment_status' => $extraCharge > 0 ? 'pending' : 'not_required',
            'additional_payment_amount' => $extraCharge > 0 ? $extraCharge : null,
            'additional_paid_at' => null,
            'returned_at' => now(),
            'returned_by' => auth()->id(),
            'status' => 'completed',
        ]);
        $this->sendBookingInvoiceEmail($booking, 'completed');

        return back()->with('success', 'Trip return recorded and final amount calculated.');
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        if (!in_array($booking->status, ['pending', 'confirmed'], true)) {
            return back()->with('error', 'Only active trips can be canceled.');
        }

        if ($booking->handover_at || $booking->start_mileage !== null) {
            return back()->with('error', 'This trip has already started and cannot be canceled.');
        }

        $booking->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Rental trip canceled successfully.');
    }

    public function markBasePaymentPaid(Booking $booking): RedirectResponse
    {
        if ($booking->status === 'cancelled') {
            return back()->with('error', 'Cancelled trips cannot be marked as paid.');
        }

        if ($booking->payment_status === 'paid') {
            return back()->with('success', 'Base payment is already marked as paid.');
        }

        $booking->update([
            'payment_status' => 'paid',
        ]);

        return back()->with('success', 'Base payment marked as paid manually.');
    }

    public function markAdditionalPaymentPaid(Booking $booking): RedirectResponse
    {
        if ($booking->status === 'cancelled') {
            return back()->with('error', 'Cancelled trips cannot be marked as paid.');
        }

        if ($booking->additional_payment_status === 'not_required') {
            return back()->with('success', 'No additional payment is required for this trip.');
        }

        if ($booking->additional_payment_status === 'paid') {
            return back()->with('success', 'Additional payment is already marked as paid.');
        }

        $additionalAmount = (float) ($booking->additional_payment_amount ?? $booking->extra_km_charge ?? 0);

        $booking->update([
            'additional_payment_status' => 'paid',
            'additional_payment_amount' => $additionalAmount > 0 ? $additionalAmount : null,
            'additional_paid_at' => now(),
        ]);

        return back()->with('success', 'Additional payment marked as paid manually.');
    }

    private function validatedFilters(Request $request): array
    {
        return $request->validate([
            'car_id' => ['nullable', 'integer', 'exists:cars,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'status' => ['nullable', 'in:pending,confirmed,completed,cancelled'],
        ]);
    }

    private function buildFilteredBookingsQuery(array $filters)
    {
        $query = Booking::query()
            ->with('car')
            ->orderByDesc('id');

        if (!empty($filters['car_id'])) {
            $query->where('car_id', (int) $filters['car_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $dateFrom = $filters['date_from'] ?? null;
        $dateTo = $filters['date_to'] ?? null;

        if ($dateFrom && $dateTo) {
            $query
                ->whereDate('start_date', '<=', $dateTo)
                ->whereDate('end_date', '>=', $dateFrom);
        } elseif ($dateFrom) {
            $query->whereDate('end_date', '>=', $dateFrom);
        } elseif ($dateTo) {
            $query->whereDate('start_date', '<=', $dateTo);
        }

        return $query;
    }

    private function parseMileageInput(string $value): ?float
    {
        $normalized = trim(str_replace([',', ' '], '', $value));
        if ($normalized === '' || !is_numeric($normalized)) {
            return null;
        }

        return (float) $normalized;
    }

    private function sendBookingInvoiceEmail(Booking $booking, string $stage): void
    {
        $email = (string) ($booking->customer_email ?: $booking->user?->email ?: '');
        if ($email === '') {
            return;
        }

        try {
            $booking->loadMissing('car');
            Mail::to($email)->send(new BookingInvoiceStatusMail($booking, $stage));
        } catch (Throwable $e) {
            report($e);
        }
    }
}
