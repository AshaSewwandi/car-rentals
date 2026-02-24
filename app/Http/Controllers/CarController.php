<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::query()->latest()->get();

        return view('cars.index', compact('cars'));
    }

    public function store(Request $request)
    {
        $data = $this->validateCar($request);
        Car::create($data);

        return back()->with('success', 'Car added successfully.');
    }

    public function update(Request $request, Car $car)
    {
        $data = $this->validateCar($request, $car->id);
        $car->update($data);

        return back()->with('success', 'Car updated successfully.');
    }

    public function destroy(Car $car)
    {
        $car->delete();

        return back()->with('success', 'Car deleted successfully.');
    }

    private function validateCar(Request $request, ?int $carId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
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
            'tracker_maintenance_mileage' => ['nullable', 'integer', 'min:0'],
            'plate_no' => ['required', 'string', 'max:100', Rule::unique('cars', 'plate_no')->ignore($carId)],
            'status' => ['required', 'in:available,rented'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
