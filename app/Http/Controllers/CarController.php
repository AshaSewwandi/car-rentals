<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $cars = Car::query()
            ->with('partner')
            ->when($request->user()?->isPartner(), function ($query) use ($request) {
                $query->where('partner_user_id', $request->user()->id);
            })
            ->latest()
            ->get();
        $partners = User::query()
            ->where('role', 'partner')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('cars.index', compact('cars', 'partners'));
    }

    public function store(Request $request)
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $data = $this->validateCar($request);
        Car::create($data);

        return back()->with('success', 'Car added successfully.');
    }

    public function update(Request $request, Car $car)
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $data = $this->validateCar($request, $car->id);
        $car->update($data);

        return back()->with('success', 'Car updated successfully.');
    }

    public function updateRenewal(Request $request, Car $car)
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $data = $request->validate([
            'renewal_type' => ['required', 'in:insurance,license'],
            'renewal_date' => ['required', 'date'],
        ]);

        $field = $data['renewal_type'] === 'insurance'
            ? 'tracker_insurance_expires'
            : 'tracker_license_expires';

        $car->update([
            $field => $data['renewal_date'],
        ]);

        return back()->with('success', ucfirst($data['renewal_type']).' renewal date updated successfully.');
    }

    public function destroy(Request $request, Car $car)
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $car->delete();

        return back()->with('success', 'Car deleted successfully.');
    }

    private function validateCar(Request $request, ?int $carId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'partner_user_id' => ['nullable', 'exists:users,id', Rule::exists('users', 'id')->where('role', 'partner')],
            'make' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'year' => ['nullable', 'integer', 'min:1990', 'max:' . ((int) now()->format('Y') + 1)],
            'color' => ['nullable', 'string', 'max:50'],
            'fuel_type' => ['nullable', 'string', 'max:50'],
            'transmission' => ['nullable', 'string', 'max:50'],
            'dagps_device_id' => ['nullable', 'string', 'max:100'],
            'tracker_device_name' => ['nullable', 'string', 'max:100'],
            'tracker_device_type' => ['nullable', 'string', 'max:100'],
            'tracker_imei' => ['nullable', 'string', 'max:50'],
            'tracker_sim' => ['nullable', 'string', 'max:50'],
            'tracker_iccid' => ['nullable', 'string', 'max:50'],
            'tracker_contact_name' => ['nullable', 'string', 'max:100'],
            'tracker_contact_number' => ['nullable', 'string', 'max:50'],
            'tracker_activation_date' => ['nullable', 'date'],
            'tracker_expiry_time' => ['nullable', 'string', 'max:100'],
            'tracker_insurance_expires' => ['nullable', 'date'],
            'tracker_license_expires' => ['nullable', 'date'],
            'tracker_maintenance_mileage' => ['nullable', 'integer', 'min:0'],
            'maintenance_last_service_date' => ['nullable', 'date'],
            'maintenance_last_service_mileage' => ['nullable', 'integer', 'min:0'],
            'maintenance_next_service_date' => ['nullable', 'date'],
            'maintenance_note' => ['nullable', 'string', 'max:1000'],
            'plate_no' => ['required', 'string', 'max:100', Rule::unique('cars', 'plate_no')->ignore($carId)],
            'status' => ['required', 'in:available,rented'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
