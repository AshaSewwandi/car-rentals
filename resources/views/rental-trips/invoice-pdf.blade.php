<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Rental Trip Invoice #{{ $booking->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #0f172a;
            margin: 0;
        }
        .page {
            padding: 22px;
        }
        .header {
            display: table;
            width: 100%;
            margin-bottom: 14px;
            border-bottom: 2px solid #1e40af;
            padding-bottom: 10px;
        }
        .header-left, .header-right {
            display: table-cell;
            vertical-align: top;
        }
        .header-right {
            text-align: right;
        }
        .brand {
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 4px;
        }
        .muted {
            color: #64748b;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
        }
        .invoice-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 4px;
        }
        .section {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            margin-top: 10px;
            overflow: hidden;
        }
        .section-title {
            background: #eff6ff;
            padding: 7px 10px;
            font-size: 12px;
            font-weight: 700;
            border-bottom: 1px solid #cbd5e1;
        }
        .section-body {
            padding: 8px 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 5px 0;
            vertical-align: top;
        }
        .label {
            color: #475569;
            width: 42%;
        }
        .value {
            font-weight: 600;
            text-align: right;
        }
        .summary-table td {
            border-bottom: 1px dashed #cbd5e1;
            padding: 7px 0;
        }
        .summary-table tr:last-child td {
            border-bottom: 0;
            font-size: 14px;
            font-weight: 700;
            color: #1e3a8a;
        }
        .footer-note {
            margin-top: 14px;
            font-size: 10px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="header-left">
                <p class="brand">R&A Auto Rentals</p>
                <p class="muted">Galle, Sri Lanka</p>
                <p class="muted">Phone: +94 77 717 3264</p>
            </div>
            <div class="header-right">
                <p class="invoice-title">Invoice</p>
                <p class="muted">Invoice No: INV-{{ str_pad((string) $booking->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p class="muted">Generated: {{ $generatedAt->format('Y-m-d H:i') }}</p>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Trip & Customer Details</div>
            <div class="section-body">
                <table>
                    <tr><td class="label">Booking ID</td><td class="value">#{{ $booking->id }}</td></tr>
                    <tr><td class="label">Customer</td><td class="value">{{ $booking->customer_name }}</td></tr>
                    <tr><td class="label">Phone</td><td class="value">{{ $booking->customer_phone ?: '-' }}</td></tr>
                    <tr><td class="label">Email</td><td class="value">{{ $booking->customer_email ?: '-' }}</td></tr>
                    <tr><td class="label">Vehicle</td><td class="value">{{ $booking->car?->name }} ({{ $booking->car?->plate_no }})</td></tr>
                    <tr><td class="label">Trip Dates</td><td class="value">{{ $booking->start_date?->format('Y-m-d') }} to {{ $booking->end_date?->format('Y-m-d') }}</td></tr>
                    <tr><td class="label">Rental Days</td><td class="value">{{ $booking->rental_days }} day(s)</td></tr>
                    <tr><td class="label">Pickup Location</td><td class="value">{{ $booking->pickup_location ?: '-' }}</td></tr>
                    <tr><td class="label">Trip Status</td><td class="value">{{ ucfirst((string) $booking->status) }}</td></tr>
                </table>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Mileage Summary</div>
            <div class="section-body">
                <table>
                    <tr><td class="label">Start Mileage</td><td class="value">{{ $booking->start_mileage !== null ? number_format((float) $booking->start_mileage, 2) . ' km' : '-' }}</td></tr>
                    <tr><td class="label">End Mileage</td><td class="value">{{ $booking->end_mileage !== null ? number_format((float) $booking->end_mileage, 2) . ' km' : '-' }}</td></tr>
                    <tr><td class="label">Used KM</td><td class="value">{{ $booking->used_km !== null ? number_format((float) $booking->used_km, 2) . ' km' : '-' }}</td></tr>
                    <tr><td class="label">Included KM</td><td class="value">{{ number_format((float) ($booking->included_km ?? ($booking->rental_days * 150)), 2) }} km</td></tr>
                    <tr><td class="label">Extra KM</td><td class="value">{{ $booking->extra_km !== null ? number_format((float) $booking->extra_km, 2) . ' km' : '-' }}</td></tr>
                </table>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Payment Summary</div>
            <div class="section-body">
                <table class="summary-table">
                    <tr><td>Base Amount ({{ ucfirst((string) $booking->payment_status) }})</td><td class="value">LKR {{ number_format($baseAmount, 2) }}</td></tr>
                    <tr><td>Additional Amount ({{ $booking->additional_payment_status === 'not_required' ? 'N/A' : ucfirst((string) $booking->additional_payment_status) }})</td><td class="value">LKR {{ number_format($additionalAmount, 2) }}</td></tr>
                    <tr><td>Final Total</td><td class="value">LKR {{ number_format($finalAmount, 2) }}</td></tr>
                </table>
            </div>
        </div>

        <p class="footer-note">This is a system-generated invoice for rental trip billing and payment tracking.</p>
    </div>
</body>
</html>
