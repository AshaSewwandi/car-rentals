<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        if ($user->isCustomer()) {
            $nameParts = preg_split('/\s+/', trim((string) $user->name), 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';

            $activeTripCount = $this->customerBookingsQuery($user)
                ->whereIn('status', ['pending', 'confirmed'])
                ->count();

            return view('customer.account-settings', [
                'user' => $user,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'activeTripCount' => $activeTripCount,
            ]);
        }

        $activeTrips = Booking::query()
            ->with('car')
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere(function ($fallback) use ($user) {
                        $fallback->whereNull('user_id')
                            ->whereNotNull('customer_email')
                            ->where('customer_email', $user->email);
                    });
            })
            ->orderBy('start_date')
            ->get();

        return view('profile.edit', [
            'user' => $user,
            'activeTrips' => $activeTrips,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255', 'required_without_all:first_name,last_name'],
            'first_name' => ['nullable', 'string', 'max:120'],
            'last_name' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:password', 'string'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        if (!empty($validated['password']) && !Hash::check((string) $validated['current_password'], (string) $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput($request->except(['current_password', 'password', 'password_confirmation']));
        }

        $derivedName = trim(
            trim((string) ($validated['first_name'] ?? '')) . ' ' .
            trim((string) ($validated['last_name'] ?? ''))
        );
        $user->name = $derivedName !== '' ? $derivedName : (string) ($validated['name'] ?? $user->name);
        $user->phone = $validated['phone'] ?? null;
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function cancelBooking(Request $request, Booking $booking): RedirectResponse
    {
        $user = $request->user();
        $belongsToUser = ($booking->user_id && $booking->user_id === $user->id)
            || (!$booking->user_id && $booking->customer_email && strcasecmp((string) $booking->customer_email, (string) $user->email) === 0);

        if (!$belongsToUser && !$user->isAdmin()) {
            abort(403);
        }

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

    private function customerBookingsQuery($user)
    {
        return Booking::query()->where(function ($query) use ($user) {
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
        });
    }
}
