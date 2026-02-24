@extends('layouts.app')
@section('title', 'Live Tracking')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<div class="card shadow-sm mb-3">
  <div class="card-header">Live Tracking Map</div>
  <div class="card-body">
    <div id="live-map" style="height:420px;" class="rounded border"></div>
  </div>
</div>

<div class="card shadow-sm">
  <div class="card-header">Tracked Devices</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>Car</th>
            <th>Plate</th>
            <th>Device</th>
            <th>IMEI</th>
            <th>Last Seen</th>
            <th>Speed</th>
            <th>Coordinates</th>
          </tr>
        </thead>
        <tbody>
          @forelse($cars as $car)
            <tr>
              <td>{{ $car->name }}</td>
              <td>{{ $car->plate_no }}</td>
              <td>{{ $car->tracker_device_name ?: ($car->dagps_device_id ?: '-') }}</td>
              <td>{{ $car->tracker_imei ?: '-' }}</td>
              <td>{{ $car->tracker_last_seen_at?->format('Y-m-d H:i:s') ?: '-' }}</td>
              <td>{{ $car->latest_speed !== null ? number_format($car->latest_speed, 2).' km/h' : '-' }}</td>
              <td>{{ $car->latest_latitude }}, {{ $car->latest_longitude }}</td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center p-4 text-muted">No live tracker data yet. Push data to `/api/dagps/push` first.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@php
  $mapCars = [];
  foreach ($cars as $car) {
      $mapCars[] = [
          'name' => $car->name,
          'plate_no' => $car->plate_no,
          'device' => $car->tracker_device_name ?: ($car->dagps_device_id ?: '-'),
          'lat' => (float) $car->latest_latitude,
          'lng' => (float) $car->latest_longitude,
          'speed' => $car->latest_speed,
          'last_seen' => optional($car->tracker_last_seen_at)->format('Y-m-d H:i:s'),
      ];
  }
@endphp
<script>
  const cars = @json($mapCars);

  const defaultCenter = cars.length ? [cars[0].lat, cars[0].lng] : [7.8731, 80.7718];
  const map = L.map('live-map').setView(defaultCenter, cars.length ? 11 : 7);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  if (cars.length) {
    const bounds = [];
    cars.forEach((car) => {
      const marker = L.marker([car.lat, car.lng]).addTo(map);
      marker.bindPopup(
        `<strong>${car.name}</strong><br>` +
        `Plate: ${car.plate_no}<br>` +
        `Device: ${car.device}<br>` +
        `Speed: ${car.speed ?? '-'} km/h<br>` +
        `Last Seen: ${car.last_seen ?? '-'}`
      );
      bounds.push([car.lat, car.lng]);
    });
    map.fitBounds(bounds, { padding: [20, 20] });
  }
</script>
@endsection
