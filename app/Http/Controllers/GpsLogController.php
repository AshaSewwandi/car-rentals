<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Car;
use App\Models\GpsLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GpsLogController extends Controller
{
    public function index(Request $request)
    {
        $month = $this->sanitizeMonth($request->get('month'));
        $carId = $request->get('car_id');
        $cycleDay = (int) $request->get('cycle_day', 2);
        $cycleDay = max(1, min(28, $cycleDay));
        [$startDate, $endDate, $usingCustomRange, $periodLabel] = $this->resolveRange($request, $month);
        $cars = Car::query()->orderBy('name')->get();

        $logsQuery = GpsLog::query()
            ->with('car')
            ->whereBetween('log_date', [$startDate, $endDate])
            ->orderByDesc('log_date');

        if (!empty($carId)) {
            $logsQuery->where('car_id', $carId);
        }

        $allLogs = (clone $logsQuery)->get();
        $logs = $logsQuery->paginate(10)->withQueryString();

        $totalDistance = (float) $allLogs->sum('distance_km');
        $daysLogged = (int) $allLogs->pluck('log_date')->map(fn ($date) => $date->format('Y-m-d'))->unique()->count();
        $periodMonthsCount = $usingCustomRange
            ? $this->countMonthlyPeriodsFromRange($startDate, $endDate)
            : 1;
        $avgKmPerMonth = $periodMonthsCount > 0 ? round($totalDistance / $periodMonthsCount, 2) : 0;

        $monthlyByCar = $allLogs
            ->groupBy('car_id')
            ->map(function ($group) {
                $car = $group->first()?->car;
                $distance = (float) $group->sum('distance_km');
                $days = (int) $group->pluck('log_date')->map(fn ($date) => $date->format('Y-m-d'))->unique()->count();
                $latest = $group->sortByDesc('log_date')->first();

                return [
                    'car_id' => $car?->id,
                    'car_name' => $car?->name ?? 'Unknown car',
                    'plate_no' => $car?->plate_no ?? '-',
                    'total_distance' => $distance,
                    'days_logged' => $days,
                    'avg_per_day' => $days > 0 ? round($distance / $days, 2) : 0,
                    'latest_closing_km' => $latest?->closing_km,
                ];
            })
            ->sortByDesc('total_distance')
            ->values();

        $sheetPeriods = [];
        $serviceStats = null;
        if (!empty($carId)) {
            if ($usingCustomRange) {
                [$sheetPeriods, $sheetStart, $sheetEnd] = $this->buildSheetPeriodsFromRange($startDate, $endDate);
            } else {
                [$sheetPeriods, $sheetStart, $sheetEnd] = $this->buildSheetPeriods($month, $cycleDay, 4);
            }
            $sheetLogs = GpsLog::query()
                ->where('car_id', $carId)
                ->whereBetween('log_date', [$sheetStart, $sheetEnd])
                ->get()
                ->keyBy(fn (GpsLog $log) => $log->log_date->format('Y-m-d'));

            foreach ($sheetPeriods as &$period) {
                foreach ($period['rows'] as &$row) {
                    $log = $sheetLogs->get($row['date']);
                    $row['km'] = $log?->distance_km;
                    $row['note'] = $log?->note;
                    $row['is_service'] = $log && str_contains(strtolower((string) $log->note), 'service');
                    $service = $this->extractServiceFromNote((string) ($log?->note ?? ''));
                    $row['service_type'] = $service['type'];
                    $row['service_cost'] = $service['cost'];
                    $row['service_mileage'] = $service['mileage'];
                    $row['service_note'] = $service['note'];
                }
            }
            unset($row, $period);

            $car = Car::query()->find($carId);
            $intervalKm = (int) ($car?->tracker_maintenance_mileage ?? 0);
            if ($intervalKm <= 0) {
                $intervalKm = 5000;
            }

            $carLogsAll = GpsLog::query()
                ->where('car_id', $carId)
                ->orderBy('log_date')
                ->get();

            $lastServiceLog = $carLogsAll
                ->filter(fn (GpsLog $log) => str_contains(strtolower((string) $log->note), 'service'))
                ->last();

            $lastServiceDate = $lastServiceLog?->log_date;
            $usageLogs = $carLogsAll->filter(function (GpsLog $log) use ($lastServiceDate) {
                if (!$lastServiceDate) {
                    return true;
                }

                return $log->log_date->gt($lastServiceDate);
            });

            $kmAfterService = (float) $usageLogs->sum('distance_km');
            $loggedDaysAfterService = (int) $usageLogs
                ->pluck('log_date')
                ->map(fn ($date) => $date->format('Y-m-d'))
                ->unique()
                ->count();
            $avgPerDayAfterService = $loggedDaysAfterService > 0 ? round($kmAfterService / $loggedDaysAfterService, 2) : 0;

            $remainingKm = max((float) $intervalKm - $kmAfterService, 0);
            $overdueKm = $kmAfterService > $intervalKm ? ($kmAfterService - (float) $intervalKm) : 0;

            $latestLogDate = $carLogsAll->last()?->log_date;
            $nextServiceDate = null;
            if ($latestLogDate && $avgPerDayAfterService > 0) {
                if ($overdueKm > 0) {
                    $nextServiceDate = $latestLogDate->format('Y-m-d');
                } else {
                    $daysToDue = (int) ceil($remainingKm / $avgPerDayAfterService);
                    $nextServiceDate = $latestLogDate->copy()->addDays($daysToDue)->format('Y-m-d');
                }
            }

            $serviceStats = [
                'interval_km' => $intervalKm,
                'last_service_date' => $lastServiceDate?->format('Y-m-d'),
                'km_after_service' => $kmAfterService,
                'remaining_km' => $remainingKm,
                'overdue_km' => $overdueKm,
                'avg_per_day_after_service' => $avgPerDayAfterService,
                'next_service_date' => $nextServiceDate,
            ];
        }

        return view('gps-logs.index', compact('cars', 'logs', 'month', 'totalDistance', 'carId', 'monthlyByCar', 'daysLogged', 'avgKmPerMonth', 'periodMonthsCount', 'sheetPeriods', 'cycleDay', 'startDate', 'endDate', 'usingCustomRange', 'periodLabel', 'serviceStats'));
    }

    public function monthlyReport(Request $request): Response
    {
        $month = $this->sanitizeMonth($request->get('month'));
        $carId = $request->get('car_id');
        [$startDate, $endDate] = $this->resolveRange($request, $month);

        $logsQuery = GpsLog::query()
            ->with('car')
            ->whereBetween('log_date', [$startDate, $endDate])
            ->orderBy('log_date')
            ->orderBy('car_id');

        if (!empty($carId)) {
            $logsQuery->where('car_id', $carId);
        }

        $logs = $logsQuery->get();

        $dailyMileage = $logs
            ->groupBy(fn (GpsLog $log) => $log->log_date->format('Y-m-d'))
            ->map(fn ($group) => (float) $group->sum('distance_km'));

        $filename = 'km-report-'.$startDate.'-to-'.$endDate.(!empty($carId) ? '-car-'.$carId : '').'.pdf';
        $selectedCar = !empty($carId) ? Car::query()->find($carId) : null;
        $rows = [];
        $cursor = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        while ($cursor->lte($end)) {
            $dateKey = $cursor->format('Y-m-d');
            $rows[] = [
                'date' => $dateKey,
                'mileage' => (float) ($dailyMileage[$dateKey] ?? 0),
            ];
            $cursor->addDay();
        }
        $totalMileage = (float) collect($rows)->sum('mileage');
        $loggedDays = (int) collect($rows)->where('mileage', '>', 0)->count();
        $avgMileage = $loggedDays > 0 ? round($totalMileage / $loggedDays, 2) : 0;
        $periodMonthsCount = $this->countMonthlyPeriodsFromRange($startDate, $endDate);
        $avgMonthlyMileage = $periodMonthsCount > 0 ? round($totalMileage / $periodMonthsCount, 2) : 0;

        $pdf = Pdf::loadView('gps-logs.report-pdf', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedCar' => $selectedCar,
            'rows' => $rows,
            'totalMileage' => $totalMileage,
            'loggedDays' => $loggedDays,
            'avgMileage' => $avgMileage,
            'periodMonthsCount' => $periodMonthsCount,
            'avgMonthlyMileage' => $avgMonthlyMileage,
        ])->setPaper('a4', 'portrait');

        return $pdf->download($filename);
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

    public function saveSheet(Request $request)
    {
        $data = $request->validate([
            'car_id' => ['required', 'exists:cars,id'],
            'month' => ['required', 'regex:/^\d{4}-\d{2}$/'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'cycle_day' => ['required', 'integer', 'min:1', 'max:28'],
            'distances' => ['array'],
            'distances.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        $saved = 0;
        $distanceEntries = $data['distances'] ?? [];
        $dates = array_keys($distanceEntries);

        foreach ($dates as $date) {
            $distance = $distanceEntries[$date] ?? null;

            if ($distance === null || $distance === '') {
                continue;
            }
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $date)) {
                continue;
            }

            $distance = (float) $distance;

            $log = GpsLog::query()
                ->where('car_id', $data['car_id'])
                ->whereDate('log_date', $date)
                ->first();

            if ($log) {
                $opening = (float) $log->opening_km;
                $log->update([
                    'opening_km' => $opening,
                    'closing_km' => $opening + $distance,
                    'source' => 'manual',
                ]);
            } else {
                GpsLog::create([
                    'car_id' => $data['car_id'],
                    'log_date' => $date,
                    'opening_km' => 0.00,
                    'closing_km' => $distance,
                    'source' => 'manual',
                ]);
            }

            $saved++;
        }

        return redirect()
            ->route('gps-logs.index', [
                'month' => $data['month'],
                'start_date' => $data['start_date'] ?? null,
                'end_date' => $data['end_date'] ?? null,
                'car_id' => $data['car_id'],
                'cycle_day' => $data['cycle_day'],
            ])
            ->with('success', "Daily KM sheet saved ({$saved} day(s) updated).");
    }

    public function saveService(Request $request)
    {
        $data = $request->validate([
            'car_id' => ['required', 'exists:cars,id'],
            'month' => ['required', 'regex:/^\d{4}-\d{2}$/'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'cycle_day' => ['required', 'integer', 'min:1', 'max:28'],
            'service_date' => ['required', 'date'],
            'original_service_date' => ['nullable', 'date'],
            'service_type' => ['required', 'string', 'max:100'],
            'service_cost' => ['nullable', 'numeric', 'min:0'],
            'service_mileage' => ['nullable', 'integer', 'min:0'],
            'service_note' => ['nullable', 'string', 'max:255'],
        ]);

        $serviceDate = Carbon::parse($data['service_date'])->toDateString();
        $originalServiceDate = !empty($data['original_service_date'])
            ? Carbon::parse($data['original_service_date'])->toDateString()
            : null;
        $serviceNote = $this->buildServiceNote(
            $data['service_type'],
            isset($data['service_cost']) ? (float) $data['service_cost'] : null,
            isset($data['service_mileage']) ? (int) $data['service_mileage'] : null,
            $data['service_note'] ?? null
        );

        if ($originalServiceDate && $originalServiceDate !== $serviceDate) {
            $oldLog = GpsLog::query()
                ->where('car_id', $data['car_id'])
                ->whereDate('log_date', $originalServiceDate)
                ->first();

            if ($oldLog && str_contains(strtolower((string) $oldLog->note), 'service')) {
                $cleanedNote = $this->removeServiceFromNote((string) $oldLog->note);
                $oldLog->update([
                    'note' => $cleanedNote,
                ]);
            }
        }

        $log = GpsLog::query()
            ->where('car_id', $data['car_id'])
            ->whereDate('log_date', $serviceDate)
            ->first();

        if ($log) {
            $log->update([
                'note' => $serviceNote,
            ]);
        } else {
                GpsLog::create([
                    'car_id' => $data['car_id'],
                    'log_date' => $serviceDate,
                    'opening_km' => 0.00,
                    'closing_km' => 0.00,
                    'source' => 'manual',
                    'note' => $serviceNote,
                ]);
        }

        return redirect()
            ->route('gps-logs.index', [
                'month' => $data['month'],
                'start_date' => $data['start_date'] ?? null,
                'end_date' => $data['end_date'] ?? null,
                'car_id' => $data['car_id'],
                'cycle_day' => $data['cycle_day'],
            ])
            ->with('success', 'Service details saved for '.$serviceDate.'.');
    }

    private function validateLog(Request $request): array
    {
        return $request->validate([
            'car_id' => ['required', 'exists:cars,id'],
            'log_date' => ['required', 'date'],
            'opening_km' => ['required', 'numeric', 'min:0'],
            'closing_km' => ['required', 'numeric', 'gte:opening_km'],
            'source' => ['required', 'in:manual,dagps'],
            'dagps_ref' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function sanitizeMonth(?string $month): string
    {
        if (is_string($month) && preg_match('/^\d{4}-\d{2}$/', $month)) {
            return $month;
        }

        return now()->format('Y-m');
    }

    private function monthRange(string $month): array
    {
        $monthDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();

        return [
            $monthDate->toDateString(),
            $monthDate->copy()->endOfMonth()->toDateString(),
        ];
    }

    private function buildSheetPeriods(string $month, int $cycleDay, int $count = 4): array
    {
        $baseMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $periods = [];

        for ($i = $count - 1; $i >= 0; $i--) {
            $periodMonth = $baseMonth->copy()->subMonths($i)->startOfMonth();
            $start = $this->monthDay($periodMonth, $cycleDay);
            $nextMonth = $periodMonth->copy()->addMonth()->startOfMonth();
            $end = $this->monthDay($nextMonth, $cycleDay)->subDay();

            $rows = [];
            $cursor = $start->copy();
            while ($cursor->lte($end)) {
                $rows[] = [
                    'date' => $cursor->format('Y-m-d'),
                    'label' => $cursor->format('Y/m/d'),
                    'km' => null,
                    'note' => null,
                    'is_service' => false,
                ];
                $cursor->addDay();
            }

            $periods[] = [
                'title' => $start->format('M d').' - '.$end->format('M d'),
                'rows' => $rows,
            ];
        }

        if (count($periods) === 0) {
            return [[], $baseMonth->toDateString(), $baseMonth->toDateString()];
        }

        $firstPeriod = $periods[0];
        $lastPeriod = $periods[count($periods) - 1];
        $rangeStart = $firstPeriod['rows'][0]['date'];
        $rangeEnd = $lastPeriod['rows'][count($lastPeriod['rows']) - 1]['date'];

        return [
            $periods,
            $rangeStart,
            $rangeEnd,
        ];
    }

    private function monthDay(Carbon $month, int $day): Carbon
    {
        $maxDay = (int) $month->copy()->endOfMonth()->format('d');

        return $month->copy()->day(min($day, $maxDay));
    }

    private function resolveRange(Request $request, string $month): array
    {
        $startInput = $request->get('start_date');
        $endInput = $request->get('end_date');

        if (is_string($startInput) && is_string($endInput) && $startInput !== '' && $endInput !== '') {
            try {
                $start = Carbon::parse($startInput)->startOfDay();
                $end = Carbon::parse($endInput)->startOfDay();
                if ($end->lt($start)) {
                    [$start, $end] = [$end, $start];
                }

                return [
                    $start->toDateString(),
                    $end->toDateString(),
                    true,
                    $start->format('Y-m-d').' to '.$end->format('Y-m-d'),
                ];
            } catch (\Throwable) {
                // fallback to month range
            }
        }

        [$start, $end] = $this->monthRange($month);

        return [$start, $end, false, $month];
    }

    private function buildSheetPeriodsFromRange(string $startDate, string $endDate): array
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->startOfDay();
        if ($end->lt($start)) {
            [$start, $end] = [$end, $start];
        }

        $periods = [];
        $anchorDay = (int) $start->format('d');
        $periodStart = $start->copy();

        while ($periodStart->lte($end)) {
            $nextMonthStart = $periodStart->copy()->addMonthNoOverflow();
            $nextMonthStart = $nextMonthStart->day(min($anchorDay, (int) $nextMonthStart->copy()->endOfMonth()->format('d')));
            $periodEnd = $nextMonthStart->copy()->subDay();
            if ($periodEnd->gt($end)) {
                $periodEnd = $end->copy();
            }

            $rows = [];
            $rowCursor = $periodStart->copy();
            while ($rowCursor->lte($periodEnd)) {
                $rows[] = [
                    'date' => $rowCursor->format('Y-m-d'),
                    'label' => $rowCursor->format('Y/m/d'),
                    'km' => null,
                    'note' => null,
                    'is_service' => false,
                ];
                $rowCursor->addDay();
            }

            $periods[] = [
                'title' => $periodStart->format('M d').' - '.$periodEnd->format('M d'),
                'rows' => $rows,
            ];

            $periodStart = $periodEnd->copy()->addDay();
        }

        return [
            $periods,
            $start->toDateString(),
            $end->toDateString(),
        ];
    }

    private function countMonthlyPeriodsFromRange(string $startDate, string $endDate): int
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->startOfDay();
        if ($end->lt($start)) {
            [$start, $end] = [$end, $start];
        }

        $count = 0;
        $anchorDay = (int) $start->format('d');
        $periodStart = $start->copy();

        while ($periodStart->lte($end)) {
            $count++;
            $nextMonthStart = $periodStart->copy()->addMonthNoOverflow();
            $nextMonthStart = $nextMonthStart->day(min($anchorDay, (int) $nextMonthStart->copy()->endOfMonth()->format('d')));
            $periodEnd = $nextMonthStart->copy()->subDay();
            if ($periodEnd->gte($end)) {
                break;
            }
            $periodStart = $periodEnd->copy()->addDay();
        }

        return max(1, $count);
    }

    private function buildServiceNote(string $type, ?float $cost = null, ?int $mileage = null, ?string $note = null): string
    {
        $parts = ['Service: '.trim($type)];
        if ($cost !== null) {
            $parts[] = 'Cost: '.number_format($cost, 2, '.', '');
        }
        if ($mileage !== null) {
            $parts[] = 'Mileage: '.$mileage;
        }
        if ($note !== null && trim($note) !== '') {
            $parts[] = 'Note: '.trim($note);
        }

        return implode(' | ', $parts);
    }

    private function extractServiceFromNote(string $note): array
    {
        $result = [
            'type' => null,
            'cost' => null,
            'mileage' => null,
            'note' => null,
        ];

        if (!str_contains(strtolower($note), 'service')) {
            return $result;
        }

        if (preg_match('/Service:\s*([^|]+)/i', $note, $m)) {
            $result['type'] = trim($m[1]);
        }
        if (preg_match('/Cost:\s*([0-9]+(?:\.[0-9]+)?)/i', $note, $m)) {
            $result['cost'] = trim($m[1]);
        }
        if (preg_match('/Mileage:\s*([0-9]+)/i', $note, $m)) {
            $result['mileage'] = trim($m[1]);
        }
        if (preg_match('/Note:\s*(.+)$/i', $note, $m)) {
            $result['note'] = trim($m[1]);
        }

        return $result;
    }

    private function removeServiceFromNote(string $note): ?string
    {
        $cleaned = preg_replace('/\bService:\s*[^|]+(?:\|\s*Cost:\s*[0-9]+(?:\.[0-9]+)?)?(?:\|\s*Mileage:\s*[0-9]+)?(?:\|\s*Note:\s*[^|]+)?/i', '', $note);
        $cleaned = trim((string) $cleaned, " \t\n\r\0\x0B|");

        return $cleaned === '' ? null : $cleaned;
    }
}
