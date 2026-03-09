<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use App\Models\VehiclePricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $cars = Car::query()
            ->with(['partner', 'images'])
            ->when($request->user()?->isPartner(), function ($query) use ($request) {
                $query->where('partner_user_id', $request->user()->id);
            })
            ->latest()
            ->get();
        $partners = User::query()
            ->where('role', 'partner')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
        $vehiclePricings = VehiclePricing::query()
            ->orderBy('make')
            ->orderBy('model')
            ->get();

        return view('cars.index', compact('cars', 'partners', 'vehiclePricings'));
    }

    public function pricingIndex(Request $request)
    {
        abort_unless($request->user()?->canAccess('cars'), 403);

        $vehiclePricings = VehiclePricing::query()
            ->orderBy('make')
            ->orderBy('model')
            ->get();

        return view('vehicle-pricings.index', compact('vehiclePricings'));
    }

    public function store(Request $request)
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $data = $this->validateCar($request);
        $car = Car::create($data);
        $this->storeUploadedImages($request, $car);

        return back()->with('success', 'Car added successfully.');
    }

    public function update(Request $request, Car $car)
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $data = $this->validateCar($request, $car->id);
        $car->update($data);
        $this->removeSelectedImages($request, $car);
        $this->storeUploadedImages($request, $car);

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

    public function storePricing(Request $request)
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $data = $this->validateVehiclePricing($request);
        VehiclePricing::create($data);

        return back()->with('success', 'Vehicle pricing added successfully.');
    }

    public function updatePricing(Request $request, VehiclePricing $vehiclePricing)
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $data = $this->validateVehiclePricing($request, $vehiclePricing->id);
        $vehiclePricing->update($data);

        return back()->with('success', 'Vehicle pricing updated successfully.');
    }

    public function destroyPricing(Request $request, VehiclePricing $vehiclePricing)
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $vehiclePricing->delete();

        return back()->with('success', 'Vehicle pricing deleted successfully.');
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
            'driver_mode' => ['required', 'in:both,with_driver_only,without_driver_only'],
            'allow_long_term' => ['required', 'boolean'],
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
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_image_ids' => ['nullable', 'array'],
            'remove_image_ids.*' => ['integer'],
        ], [
            'images.*.image' => 'Each selected file must be an image.',
            'images.*.mimes' => 'Only JPG, JPEG, PNG, and WEBP images are allowed.',
            'images.*.max' => 'Each image must be 4MB or smaller.',
            'images.*.uploaded' => 'Image upload failed. Please use a smaller file (max 4MB).',
        ]);
    }

    private function storeUploadedImages(Request $request, Car $car): void
    {
        if (!$request->hasFile('images')) {
            return;
        }

        $uploadDir = public_path('uploads/cars');
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        $nextSort = (int) ($car->images()->max('sort_order') ?? -1) + 1;

        foreach ($request->file('images', []) as $file) {
            if (!$file || !$file->isValid()) {
                continue;
            }

            $fileName = sprintf(
                '%s_%s_%s.%s',
                $car->id,
                now()->format('YmdHis'),
                Str::random(8),
                $file->getClientOriginalExtension()
            );

            $file->move($uploadDir, $fileName);

            $car->images()->create([
                'path' => 'uploads/cars/' . $fileName,
                'sort_order' => $nextSort++,
            ]);
        }
    }

    private function removeSelectedImages(Request $request, Car $car): void
    {
        $ids = collect($request->input('remove_image_ids', []))
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->values();

        if ($ids->isEmpty()) {
            return;
        }

        $images = $car->images()->whereIn('id', $ids)->get();
        foreach ($images as $image) {
            File::delete(public_path($image->path));
            $image->delete();
        }
    }

    private function validateVehiclePricing(Request $request, ?int $pricingId = null): array
    {
        return $request->validate([
            'make' => ['nullable', 'string', 'max:100'],
            'model' => [
                'required',
                'string',
                'max:100',
                Rule::unique('vehicle_pricings', 'model')->ignore($pricingId)->where(function ($query) use ($request) {
                    return $query->where('make', $request->input('make'));
                }),
            ],
            'per_day_km' => ['required', 'integer', 'min:1'],
            'per_day_amount' => ['required', 'numeric', 'min:0'],
            'per_month_amount' => ['nullable', 'numeric', 'min:0'],
            'per_month_km' => ['nullable', 'integer', 'min:1'],
            'extra_km_rate' => ['required', 'numeric', 'min:0'],
            'driver_cost_per_day' => ['required', 'numeric', 'min:0'],
            'driver_cost_per_month' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
