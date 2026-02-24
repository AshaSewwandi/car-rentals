<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class DagpsWebhookController extends Controller
{
    public function push(Request $request)
    {
        $token = (string) config('services.dagps.webhook_token', '');
        $sentToken = (string) $request->header('X-Webhook-Token', $request->query('token', ''));

        if ($token === '' || $sentToken !== $token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'imei' => ['nullable', 'string', 'max:100'],
            'dagps_device_id' => ['nullable', 'string', 'max:100'],
            'plate_no' => ['nullable', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'speed' => ['nullable', 'numeric', 'min:0'],
            'course' => ['nullable', 'integer', 'between:0,360'],
            'tracked_at' => ['nullable', 'date'],
        ]);

        $car = null;
        if (!empty($data['imei'])) {
            $car = Car::query()->where('tracker_imei', $data['imei'])->first();
        }
        if (!$car && !empty($data['dagps_device_id'])) {
            $car = Car::query()->where('dagps_device_id', $data['dagps_device_id'])->first();
        }
        if (!$car && !empty($data['plate_no'])) {
            $car = Car::query()->where('plate_no', $data['plate_no'])->first();
        }

        if (!$car) {
            return response()->json(['message' => 'Car not found for incoming tracker payload'], 404);
        }

        $car->update([
            'latest_latitude' => $data['latitude'],
            'latest_longitude' => $data['longitude'],
            'latest_speed' => $data['speed'] ?? null,
            'latest_course' => $data['course'] ?? null,
            'tracker_last_seen_at' => $data['tracked_at'] ?? now(),
        ]);

        return response()->json([
            'message' => 'Location updated',
            'car_id' => $car->id,
            'plate_no' => $car->plate_no,
        ]);
    }
}
