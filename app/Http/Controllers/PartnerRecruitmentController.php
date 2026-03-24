<?php

namespace App\Http\Controllers;

use App\Mail\PartnerApplicationSubmittedMail;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Throwable;

class PartnerRecruitmentController extends Controller
{
    public function create(): View
    {
        return view('auth.partner-recruitment');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'partner_name' => ['required', 'string', 'max:255'],
            'partner_phone' => ['required', 'string', 'max:40', 'regex:/^\+?[0-9\s\-]{7,20}$/'],
            'partner_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],

            'vehicle_name' => ['required', 'string', 'max:255'],
            'plate_no' => ['required', 'string', 'max:100', 'regex:/^[A-Za-z0-9\-\s]+$/', 'unique:cars,plate_no'],
            'make' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'year' => ['nullable', 'integer', 'min:1990', 'max:' . ((int) now()->format('Y') + 1)],
            'color' => ['nullable', 'string', 'max:50'],
            'fuel_type' => ['nullable', 'string', 'max:50'],
            'transmission' => ['nullable', 'string', 'max:50'],
            'driver_mode' => ['required', 'in:both,with_driver_only,without_driver_only'],
            'allow_long_term' => ['required', 'boolean'],
            'vehicle_note' => ['nullable', 'string', 'max:255'],
        ], [
            'partner_name.required' => 'Full name is required.',
            'partner_phone.required' => 'Phone number is required.',
            'partner_phone.regex' => 'Enter a valid phone number.',
            'partner_email.required' => 'Email address is required.',
            'partner_email.email' => 'Enter a valid email address.',
            'partner_email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'vehicle_name.required' => 'Vehicle name is required.',
            'plate_no.required' => 'Plate number is required.',
            'plate_no.unique' => 'This plate number is already in use.',
            'plate_no.regex' => 'Plate number can only contain letters, numbers, spaces, and dashes.',
            'driver_mode.required' => 'Please select a driver mode.',
            'allow_long_term.required' => 'Please select long-term rental option.',
        ]);
        [$partner, $car] = DB::transaction(function () use ($data): array {
            $partner = User::create([
                'name' => $data['partner_name'],
                'phone' => $data['partner_phone'],
                'email' => $data['partner_email'],
                'role' => 'partner_applicant',
                'partner_share_percentage' => 0,
                'admin_share_percentage' => 100,
                'password' => Hash::make($data['password']),
            ]);

            $car = Car::create([
                'name' => $data['vehicle_name'],
                'plate_no' => $data['plate_no'],
                'make' => $data['make'] ?? null,
                'model' => $data['model'] ?? null,
                'year' => $data['year'] ?? null,
                'color' => $data['color'] ?? null,
                'fuel_type' => $data['fuel_type'] ?? null,
                'transmission' => $data['transmission'] ?? null,
                'driver_mode' => $data['driver_mode'],
                'allow_long_term' => (bool) $data['allow_long_term'],
                'status' => 'available',
                'partner_user_id' => $partner->id,
                'note' => $data['vehicle_note'] ?? null,
            ]);

            return [$partner, $car];
        });

        $this->sendAdminApplicationEmailsAfterResponse($partner->id, $car->id);

        Auth::login($partner);
        $request->session()->regenerate();

        return redirect()
            ->route('home')
            ->with('success', 'Partner account created successfully.');
    }

    private function sendAdminApplicationEmailsAfterResponse(int $partnerId, int $carId): void
    {
        dispatch(function () use ($partnerId, $carId) {
            $partner = User::query()->find($partnerId);
            $car = Car::query()->find($carId);
            if (!$partner || !$car) {
                return;
            }

            User::query()
                ->whereIn('role', ['admin', 'super_admin'])
                ->whereNotNull('email')
                ->pluck('email')
                ->filter()
                ->map(fn ($email) => strtolower(trim((string) $email)))
                ->unique()
                ->each(function (string $email) use ($partner, $car) {
                    try {
                        Mail::to($email)->queue(new PartnerApplicationSubmittedMail($partner, $car));
                    } catch (Throwable $e) {
                        report($e);
                    }
                });
        })->afterResponse();
    }
}
