<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Vehicle;
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
        $vehicleId = $request->get('vehicle_id');

        if ($user?->isCustomerPortal()) {
            $customerId = $user->customer_id;
            if (!$customerId) {
                return view('vehicle-maintenance.index', [
                    'vehicles' => collect(),
                    'records' => collect(),
                    'month' => $month,
                    'vehicleId' => $vehicleId,
                    'total' => 0,
                ]);
            }

            $allowedVehicleIds = Agreement::query()
                ->where('customer_id', $customerId)
                ->pluck('vehicle_id')
                ->unique()
                ->filter()
                ->values();

            $allowedVehicleIdList = $allowedVehicleIds->map(fn ($id) => (int) $id)->all();
            if ($vehicleId !== null && $vehicleId !== '' && !in_array((int) $vehicleId, $allowedVehicleIdList, true)) {
                abort(403, 'You do not have permission to access this vehicle.');
            }

            $vehicles = $allowedVehicleIds->isEmpty()
                ? collect()
                : Vehicle::query()->whereIn('id', $allowedVehicleIds)->orderBy('name')->get();

            if ($allowedVehicleIds->isEmpty()) {
                return view('vehicle-maintenance.index', [
                    'vehicles' => $vehicles,
                    'records' => collect(),
                    'month' => $month,
                    'vehicleId' => $vehicleId,
                    'total' => 0,
                ]);
            }

            $records = VehicleMaintenance::query()
                ->with('vehicle')
                ->whereIn('vehicle_id', $allowedVehicleIds)
                ->when($month, fn ($query) => $query->whereRaw("DATE_FORMAT(service_date, '%Y-%m') = ?", [$month]))
                ->when($vehicleId, fn ($query) => $query->where('vehicle_id', $vehicleId))
                ->orderByDesc('service_date')
                ->orderByDesc('id')
                ->get();

            $total = (float) $records->sum('amount');

            return view('vehicle-maintenance.index', compact('vehicles', 'records', 'month', 'vehicleId', 'total'));
        }

        if ($user?->isPartner()) {
            $allowedVehicleIds = Vehicle::query()
                ->where('partner_user_id', $user->id)
                ->pluck('id');

            $allowedVehicleIdList = $allowedVehicleIds->map(fn ($id) => (int) $id)->all();
            if ($vehicleId !== null && $vehicleId !== '' && !in_array((int) $vehicleId, $allowedVehicleIdList, true)) {
                abort(403, 'You do not have permission to access this vehicle.');
            }

            $vehicles = $allowedVehicleIds->isEmpty()
                ? collect()
                : Vehicle::query()->whereIn('id', $allowedVehicleIds)->orderBy('name')->get();

            if ($allowedVehicleIds->isEmpty()) {
                return view('vehicle-maintenance.index', [
                    'vehicles' => $vehicles,
                    'records' => collect(),
                    'month' => $month,
                    'vehicleId' => $vehicleId,
                    'total' => 0,
                ]);
            }

            $records = VehicleMaintenance::query()
                ->with('vehicle')
                ->whereIn('vehicle_id', $allowedVehicleIds)
                ->when($month, fn ($query) => $query->whereRaw("DATE_FORMAT(service_date, '%Y-%m') = ?", [$month]))
                ->when($vehicleId, fn ($query) => $query->where('vehicle_id', $vehicleId))
                ->orderByDesc('service_date')
                ->orderByDesc('id')
                ->get();

            $total = (float) $records->sum('amount');

            return view('vehicle-maintenance.index', compact('vehicles', 'records', 'month', 'vehicleId', 'total'));
        }

        $vehicles = Vehicle::query()->orderBy('name')->get();

        $records = VehicleMaintenance::query()
            ->with('vehicle')
            ->when($month, fn ($query) => $query->whereRaw("DATE_FORMAT(service_date, '%Y-%m') = ?", [$month]))
            ->when($vehicleId, fn ($query) => $query->where('vehicle_id', $vehicleId))
            ->orderByDesc('service_date')
            ->orderByDesc('id')
            ->get();

        $total = (float) $records->sum('amount');

        return view('vehicle-maintenance.index', compact('vehicles', 'records', 'month', 'vehicleId', 'total'));
    }

    public function exportPdf(Request $request): Response
    {
        $user = $request->user();
        if ($user?->isCustomerPortal()) {
            abort(403, 'You do not have permission to download this report.');
        }

        $month = $request->get('month');
        $vehicleId = $request->get('vehicle_id');

        $recordsQuery = VehicleMaintenance::query()->with('vehicle');
        $selectedVehicle = null;

        if ($user?->isPartner()) {
            $allowedVehicleIds = Vehicle::query()
                ->where('partner_user_id', $user->id)
                ->pluck('id');
            $allowedVehicleIdList = $allowedVehicleIds->map(fn ($id) => (int) $id)->all();

            if ($vehicleId !== null && $vehicleId !== '' && !in_array((int) $vehicleId, $allowedVehicleIdList, true)) {
                abort(403, 'You do not have permission to access this vehicle.');
            }

            $recordsQuery->whereIn('vehicle_id', $allowedVehicleIds);
            $selectedVehicle = !empty($vehicleId)
                ? Vehicle::query()->where('partner_user_id', $user->id)->find($vehicleId)
                : null;
        } else {
            $selectedVehicle = !empty($vehicleId) ? Vehicle::query()->find($vehicleId) : null;
        }

        $records = $recordsQuery
            ->when($month, fn ($query) => $query->whereRaw("DATE_FORMAT(service_date, '%Y-%m') = ?", [$month]))
            ->when($vehicleId, fn ($query) => $query->where('vehicle_id', $vehicleId))
            ->orderByDesc('service_date')
            ->orderByDesc('id')
            ->get();

        $total = (float) $records->sum('amount');
        $filename = 'vehicle-maintenance'
            . ($month ? '-'.$month : '-all-months')
            . (!empty($vehicleId) ? '-vehicle-'.$vehicleId : '-all-vehicles')
            . '.pdf';

        $pdf = Pdf::loadView('vehicle-maintenance.report-pdf', [
            'records' => $records,
            'selectedVehicle' => $selectedVehicle,
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
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'service_date' => ['required', 'date'],
            'part_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
