<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\VehicleMaintenance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VehicleMaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month');
        $carId = $request->get('car_id');

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
        $month = $request->get('month');
        $carId = $request->get('car_id');

        $records = VehicleMaintenance::query()
            ->with('car')
            ->when($month, fn ($query) => $query->whereRaw("DATE_FORMAT(service_date, '%Y-%m') = ?", [$month]))
            ->when($carId, fn ($query) => $query->where('car_id', $carId))
            ->orderByDesc('service_date')
            ->orderByDesc('id')
            ->get();

        $selectedCar = !empty($carId) ? Car::query()->find($carId) : null;
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
