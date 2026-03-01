<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Rental Trips Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #0f172a;
        }
        .header {
            margin-bottom: 12px;
        }
        .title {
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 4px;
        }
        .meta {
            margin: 0;
            color: #475569;
            font-size: 11px;
        }
        .filters {
            margin: 10px 0 12px;
            padding: 8px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background: #f8fafc;
            font-size: 11px;
        }
        .filters strong {
            color: #0f172a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 6px;
            vertical-align: top;
        }
        th {
            background: #e2e8f0;
            text-align: left;
            font-size: 11px;
        }
        .muted {
            color: #64748b;
            font-size: 10px;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Rental Trips Report</p>
        <p class="meta">Generated at: {{ $generatedAt->format('Y-m-d H:i') }}</p>
    </div>

    <div class="filters">
        <strong>Filters:</strong>
        Vehicle:
        <strong>{{ $selectedCar ? ($selectedCar->name . ' (' . $selectedCar->plate_no . ')') : 'All Vehicles' }}</strong>,
        Status:
        <strong>{{ isset($filters['status']) && $filters['status'] !== '' ? ucfirst($filters['status']) : 'All' }}</strong>,
        From:
        <strong>{{ $filters['date_from'] ?? 'Any' }}</strong>,
        To:
        <strong>{{ $filters['date_to'] ?? 'Any' }}</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Vehicle</th>
                <th>Dates</th>
                <th>Status</th>
                <th>Base Details</th>
                <th>Additional Details</th>
                <th>Final Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>
                        {{ $booking->customer_name }}<br>
                        <span class="muted">{{ $booking->customer_phone ?: '-' }}</span>
                    </td>
                    <td>
                        {{ $booking->car?->name }}<br>
                        <span class="muted">{{ $booking->car?->plate_no }}</span>
                    </td>
                    <td>
                        {{ $booking->start_date?->format('Y-m-d') }} to {{ $booking->end_date?->format('Y-m-d') }}<br>
                        <span class="muted">{{ $booking->rental_days }} day(s)</span>
                    </td>
                    <td>{{ ucfirst((string) $booking->status) }}</td>
                    <td>
                        {{ ucfirst((string) $booking->payment_status) }}<br>
                        <span class="muted">LKR {{ number_format((float) $booking->total_amount, 2) }}</span>
                    </td>
                    <td>
                        {{ $booking->additional_payment_status === 'not_required' ? '-' : ucfirst((string) $booking->additional_payment_status) }}<br>
                        <span class="muted">LKR {{ number_format((float) ($booking->additional_payment_amount ?? $booking->extra_km_charge ?? 0), 2) }}</span>
                    </td>
                    <td class="text-right">LKR {{ number_format((float) ($booking->final_total ?? $booking->total_amount), 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No rental trips found for selected filters.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
