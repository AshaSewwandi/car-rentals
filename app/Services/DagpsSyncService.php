<?php

namespace App\Services;

use App\Models\Car;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class DagpsSyncService
{
    public function sync(?string $dashboardUrl = null): array
    {
        $baseUrl = (string) config('services.dagps.base_url', 'http://www.dagps.net');
        $cookie = trim((string) config('services.dagps.cookie', ''));
        $macid = (string) config('services.dagps.macid', '00000000-0000-0000-0000-000000000000');
        $mapType = (string) config('services.dagps.map_type', 'GOOGLE');
        $lang = (string) config('services.dagps.lang', 'en');
        $dashboardUrl = $dashboardUrl ?: (string) config('services.dagps.dashboard_url', '');

        if ($dashboardUrl === '') {
            throw new RuntimeException('DAGPS dashboard URL is not configured.');
        }

        $session = $this->parseDashboardUrl($dashboardUrl);
        $http = Http::baseUrl(rtrim($baseUrl, '/'))
            ->acceptJson()
            ->timeout(20)
            ->asForm();

        if ($cookie !== '') {
            $http = $http->withHeaders(['Cookie' => $cookie]);
        }

        $usersPayload = $this->decodeJsonp(
            $http->get('/GetDataService.aspx', [
                'method' => 'loadUser',
                'callback' => 'cb',
                'school_id' => $session['school_id'],
                'custid' => $session['custid'],
                'macid' => $macid,
                'mds' => $session['mds'],
                't' => (string) now()->getTimestampMs(),
            ])
        );

        if (($usersPayload['success'] ?? null) === 'false') {
            $reason = (string) ($usersPayload['errorDescribe'] ?? 'Unknown loadUser error');
            throw new RuntimeException('DAGPS loadUser failed: '.$reason);
        }

        $userIds = $this->extractUserIds($usersPayload);
        if (count($userIds) === 0) {
            return ['devices_seen' => 0, 'cars_updated' => 0];
        }

        $records = [];
        foreach ($userIds as $userId) {
            $gpsPayload = $this->decodeJsonp(
                $http->get('/TrackService.aspx', [
                    'method' => 'getUserAndGpsInfoByIDsUtc',
                    'school_id' => $session['school_id'],
                    'custid' => $session['custid'],
                    'mds' => $session['mds'],
                    'callback' => 'cb',
                    'mapType' => $mapType,
                    'option' => $lang,
                    'user_id' => $userId,
                    'timestamp' => (string) now()->getTimestampMs(),
                ])
            );

            $records = array_merge($records, $this->extractGpsRecords($gpsPayload));
        }

        $updated = 0;
        foreach ($records as $record) {
            $car = $this->matchCar($record);
            if (!$car) {
                continue;
            }

            $car->update([
                'latest_latitude' => $record['latitude'],
                'latest_longitude' => $record['longitude'],
                'latest_speed' => $record['speed'],
                'tracker_last_seen_at' => $record['tracked_at'] ?? now(),
            ]);
            $updated++;
        }

        return ['devices_seen' => count($records), 'cars_updated' => $updated];
    }

    private function parseDashboardUrl(string $url): array
    {
        $query = parse_url($url, PHP_URL_QUERY);
        if (!is_string($query)) {
            throw new RuntimeException('Invalid DAGPS dashboard URL.');
        }

        parse_str($query, $params);
        $custId = (string) ($params['father_id'] ?? $params['login_id'] ?? '');
        $schoolId = (string) ($params['login_id'] ?? $params['father_id'] ?? '');
        $mds = (string) ($params['mds'] ?? '');

        if ($custId === '' || $schoolId === '' || $mds === '') {
            throw new RuntimeException('Dashboard URL is missing father_id/login_id/mds.');
        }

        return ['custid' => $custId, 'school_id' => $schoolId, 'mds' => $mds];
    }

    private function decodeJsonp(Response $response): array
    {
        if (!$response->successful()) {
            throw new RuntimeException('DAGPS request failed with status '.$response->status());
        }

        $body = trim($response->body());
        $start = strpos($body, '(');
        $end = strrpos($body, ')');
        if ($start === false || $end === false || $end <= $start) {
            throw new RuntimeException('Unexpected DAGPS response format.');
        }

        $json = trim(substr($body, $start + 1, $end - $start - 1));
        $decoded = json_decode($json, true);
        if (!is_array($decoded)) {
            throw new RuntimeException('Failed to decode DAGPS JSON payload.');
        }

        return $decoded;
    }

    private function extractUserIds(array $payload): array
    {
        $ids = [];
        $stack = [$payload];

        while ($stack) {
            $node = array_pop($stack);
            if (!is_array($node)) {
                continue;
            }

            if (isset($node['user_id']) && $node['user_id'] !== '') {
                $ids[] = (string) $node['user_id'];
            }
            if (isset($node['id']) && isset($node['name']) && !isset($node['latitude'])) {
                $ids[] = (string) $node['id'];
            }

            foreach ($node as $value) {
                if (is_array($value)) {
                    $stack[] = $value;
                }
            }
        }

        return array_values(array_unique(array_filter($ids)));
    }

    private function extractGpsRecords(array $payload): array
    {
        $records = [];
        $stack = [$payload];

        while ($stack) {
            $node = array_pop($stack);
            if (!is_array($node)) {
                continue;
            }

            $lat = $this->toFloat($node['latitude'] ?? $node['lat'] ?? $node['weidu'] ?? null);
            $lng = $this->toFloat($node['longitude'] ?? $node['lng'] ?? $node['jingdu'] ?? null);

            if ($lat !== null && $lng !== null) {
                $records[] = [
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'speed' => $this->toFloat($node['speed'] ?? $node['sudu'] ?? null),
                    'imei' => $this->str($node['imei'] ?? $node['imei_no'] ?? $node['sn'] ?? null),
                    'dagps_device_id' => $this->str($node['device_id'] ?? $node['user_id'] ?? $node['id'] ?? null),
                    'device_name' => $this->str($node['name'] ?? $node['user_name'] ?? $node['carno'] ?? null),
                    'plate_no' => $this->str($node['plate_no'] ?? $node['car_no'] ?? $node['carno'] ?? null),
                    'tracked_at' => $node['servertime'] ?? $node['heart_time'] ?? null,
                ];
            }

            foreach ($node as $value) {
                if (is_array($value)) {
                    $stack[] = $value;
                }
            }
        }

        return $records;
    }

    private function matchCar(array $record): ?Car
    {
        if (!empty($record['imei'])) {
            $car = Car::query()->where('tracker_imei', $record['imei'])->first();
            if ($car) {
                return $car;
            }
        }

        if (!empty($record['dagps_device_id'])) {
            $car = Car::query()->where('dagps_device_id', $record['dagps_device_id'])->first();
            if ($car) {
                return $car;
            }
        }

        if (!empty($record['device_name'])) {
            $car = Car::query()->where('tracker_device_name', $record['device_name'])->first();
            if ($car) {
                return $car;
            }
        }

        if (!empty($record['plate_no'])) {
            return Car::query()->where('plate_no', $record['plate_no'])->first();
        }

        return null;
    }

    private function toFloat(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (!is_numeric($value)) {
            return null;
        }

        return (float) $value;
    }

    private function str(mixed $value): ?string
    {
        $value = is_string($value) ? trim($value) : null;
        if ($value === '' || $value === null) {
            return null;
        }

        return Str::limit($value, 100, '');
    }
}
