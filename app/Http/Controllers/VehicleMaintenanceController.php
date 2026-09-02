<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Car;
use App\Models\VehicleMaintenance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VehicleMaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $month = $request->get('month');
        $carId = $request->get('car_id');

        if ($user?->isCustomerPortal()) {
            $customerId = $user->customer_id;
            if (!$customerId) {
                return view('vehicle-maintenance.index', [
                    'cars' => collect(),
                    'records' => collect(),
                    'month' => $month,
                    'carId' => $carId,
                    'total' => 0,
                ]);
            }

            $allowedCarIds = Agreement::query()
                ->where('customer_id', $customerId)
                ->pluck('car_id')
                ->unique()
                ->filter()
                ->values();

            $allowedCarIdList = $allowedCarIds->map(fn ($id) => (int) $id)->all();
            if ($carId !== null && $carId !== '' && !in_array((int) $carId, $allowedCarIdList, true)) {
                abort(403, 'You do not have permission to access this vehicle.');
            }

            $cars = $allowedCarIds->isEmpty()
                ? collect()
                : Car::query()->whereIn('id', $allowedCarIds)->orderBy('name')->get();

            if ($allowedCarIds->isEmpty()) {
                return view('vehicle-maintenance.index', [
                    'cars' => $cars,
                    'records' => collect(),
                    'month' => $month,
                    'carId' => $carId,
                    'total' => 0,
                ]);
            }

            $records = VehicleMaintenance::query()
                ->with('car')
                ->whereIn('car_id', $allowedCarIds)
                ->when($month, fn ($query) => $query->whereRaw("DATE_FORMAT(service_date, '%Y-%m') = ?", [$month]))
                ->when($carId, fn ($query) => $query->where('car_id', $carId))
                ->orderByDesc('service_date')
                ->orderByDesc('id')
                ->get();

            $total = (float) $records->sum('amount');

            return view('vehicle-maintenance.index', compact('cars', 'records', 'month', 'carId', 'total'));
        }

        if ($user?->isPartner()) {
            $allowedCarIds = Car::query()
                ->where('partner_user_id', $user->id)
                ->pluck('id');

            $allowedCarIdList = $allowedCarIds->map(fn ($id) => (int) $id)->all();
            if ($carId !== null && $carId !== '' && !in_array((int) $carId, $allowedCarIdList, true)) {
                abort(403, 'You do not have permission to access this vehicle.');
            }

            $cars = $allowedCarIds->isEmpty()
                ? collect()
                : Car::query()->whereIn('id', $allowedCarIds)->orderBy('name')->get();

            if ($allowedCarIds->isEmpty()) {
                return view('vehicle-maintenance.index', [
                    'cars' => $cars,
                    'records' => collect(),
                    'month' => $month,
                    'carId' => $carId,
                    'total' => 0,
                ]);
            }

            $records = VehicleMaintenance::query()
                ->with('car')
                ->whereIn('car_id', $allowedCarIds)
                ->when($month, fn ($query) => $query->whereRaw("DATE_FORMAT(service_date, '%Y-%m') = ?", [$month]))
                ->when($carId, fn ($query) => $query->where('car_id', $carId))
                ->orderByDesc('service_date')
                ->orderByDesc('id')
                ->get();

            $total = (float) $records->sum('amount');

            return view('vehicle-maintenance.index', compact('cars', 'records', 'month', 'carId', 'total'));
        }

        $cars = Car::query()->orderBy('name')->get();

        $records = VehicleMaintenance::query()
            ->with('car')
            ->when($month, fn ($query) => $query->whereRaw("DATE_FORMAT(service_date, '%Y-%m') = ?", [$month]))
            ->when($carId, fn ($query) => $query->where('car_id', $carId))
            ->orderByDesc('service_date')
            ->orderByDesc('id')
            ->get();

        $total = (float) $records->sum('amount');

        return view('vehicle-maintenance.index', compact('cars', 'records', 'month', 'carId', 'total'));
    }

    public function exportPdf(Request $request): Response
    {
        $user = $request->user();
        if ($user?->isCustomerPortal()) {
            abort(403, 'You do not have permission to download this report.');
        }

        $month = $request->get('month');
        $carId = $request->get('car_id');

        $recordsQuery = VehicleMaintenance::query()->with('car');
        $selectedCar = null;

        if ($user?->isPartner()) {
            $allowedCarIds = Car::query()
                ->where('partner_user_id', $user->id)
                ->pluck('id');
            $allowedCarIdList = $allowedCarIds->map(fn ($id) => (int) $id)->all();

            if ($carId !== null && $carId !== '' && !in_array((int) $carId, $allowedCarIdList, true)) {
                abort(403, 'You do not have permission to access this vehicle.');
            }

            $recordsQuery->whereIn('car_id', $allowedCarIds);
            $selectedCar = !empty($carId)
                ? Car::query()->where('partner_user_id', $user->id)->find($carId)
                : null;
        } else {
            $selectedCar = !empty($carId) ? Car::query()->find($carId) : null;
        }

        $records = $recordsQuery
            ->when($month, fn ($query) => $query->whereRaw("DATE_FORMAT(service_date, '%Y-%m') = ?", [$month]))
            ->when($carId, fn ($query) => $query->where('car_id', $carId))
            ->orderByDesc('service_date')
            ->orderByDesc('id')
            ->get();

        $total = (float) $records->sum('amount');
        $filename = 'vehicle-maintenance'
            . ($month ? '-'.$month : '-all-months')
            . (!empty($carId) ? '-car-'.$carId : '-all-cars')
            . '.pdf';

        $pdf = Pdf::loadView('vehicle-maintenance.report-pdf', [
            'records' => $records,
            'selectedCar' => $selectedCar,
            'month' => $month,
            'total' => $total,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    public function store(Request $request)
    {
        $data = $this->validateRecord($request);
        VehicleMaintenance::create($data);

        return back()->with('success', 'Vehicle maintenance record added successfully.');
    }

    public function update(Request $request, VehicleMaintenance $vehicleMaintenance)
    {
        $data = $this->validateRecord($request);
        $vehicleMaintenance->update($data);

        return back()->with('success', 'Vehicle maintenance record updated successfully.');
    }

    public function destroy(VehicleMaintenance $vehicleMaintenance)
    {
        $vehicleMaintenance->delete();

        return back()->with('success', 'Vehicle maintenance record deleted successfully.');
    }

    private function validateRecord(Request $request): array
    {
        return $request->validate([
            'car_id' => ['required', 'exists:cars,id'],
            'service_date' => ['required', 'date'],
            'part_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
