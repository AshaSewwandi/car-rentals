<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\GpsLog;
use Illuminate\Http\Request;

class GpsLogController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $carId = $request->get('car_id');
        $cars = Car::query()->orderBy('name')->get();

        $logsQuery = GpsLog::query()
            ->with('car')
            ->whereRaw("DATE_FORMAT(log_date, '%Y-%m') = ?", [$month])
            ->orderByDesc('log_date');

        if (!empty($carId)) {
            $logsQuery->where('car_id', $carId);
        }

        $logs = $logsQuery->get();

        $totalDistance = (int) $logs->sum('distance_km');

        return view('gps-logs.index', compact('cars', 'logs', 'month', 'totalDistance', 'carId'));
    }

    public function store(Request $request)
    {
        $data = $this->validateLog($request);
        GpsLog::create($data);

        return back()->with('success', 'GPS/KM log added successfully.');
    }

    public function update(Request $request, GpsLog $gpsLog)
    {
        $data = $this->validateLog($request);
        $gpsLog->update($data);

        return back()->with('success', 'GPS/KM log updated successfully.');
    }

    public function destroy(GpsLog $gpsLog)
    {
        $gpsLog->delete();

        return back()->with('success', 'GPS/KM log deleted successfully.');
    }

    private function validateLog(Request $request): array
    {
        return $request->validate([
            'car_id' => ['required', 'exists:cars,id'],
            'log_date' => ['required', 'date'],
            'opening_km' => ['required', 'integer', 'min:0'],
            'closing_km' => ['required', 'integer', 'gte:opening_km'],
            'source' => ['required', 'in:manual,dagps'],
            'dagps_ref' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
