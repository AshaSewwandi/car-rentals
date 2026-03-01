<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>R&A Auto Rentals Invoice</title>
</head>
<body style="margin:0;padding:0;background:#f4f7fb;font-family:Arial,Helvetica,sans-serif;color:#0f172a;">
    @php
        $logoPath = public_path('images/logo.png');
        $logoSrc = file_exists($logoPath) ? $message->embed($logoPath) : url('/images/logo.png');
        $carImageSrc = $logoSrc;
        $plate = strtolower((string) ($booking->car?->plate_no ?? ''));
        $plateKey = str_replace([' ', '-'], '_', $plate);
        $carImageCandidates = [
            public_path('images/' . $plateKey . '.png'),
            public_path('images/' . $plateKey . '.jpg'),
            public_path('images/' . $plateKey . '.jpeg'),
        ];
        foreach ($carImageCandidates as $candidatePath) {
            if (file_exists($candidatePath)) {
                $carImageSrc = $message->embed($candidatePath);
                break;
            }
        }
        $isCompleted = $stage === 'completed';
        $statusLabel = $isCompleted ? 'TRIP COMPLETED' : 'BOOKING CONFIRMED';
        $statusColor = $isCompleted ? '#166534' : '#0f66c3';
        $statusBg = $isCompleted ? '#ecfdf3' : '#eff6ff';
        $baseAmount = (float) ($booking->total_amount ?? 0);
        $additionalAmount = (float) ($booking->additional_payment_amount ?? $booking->extra_km_charge ?? 0);
        $finalAmount = (float) ($booking->final_total ?? $booking->total_amount ?? 0);
        $vehicleYear = $booking->car?->year ?? $booking->car?->model_year;
        $vehicleTransmission = $booking->car?->transmission ?? $booking->car?->transmission_type;
        $vehicleFuel = $booking->car?->fuel_type ?? $booking->car?->fuel;
    @endphp

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f7fb;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border:1px solid #dbe6f3;border-radius:12px;overflow:hidden;">
                    <tr>
                        <td style="background:linear-gradient(135deg,#0a3f8f,#0f66c3);padding:18px 22px;">
                            <img src="{{ $logoSrc }}" alt="R&A Auto Rentals" style="width:40px;height:40px;vertical-align:middle;border-radius:8px;background:#ffffff;padding:4px;object-fit:contain;">
                            <span style="display:inline-block;vertical-align:middle;margin-left:8px;color:#ffffff;font-size:24px;font-weight:700;">R&amp;A Auto Rentals</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:22px 24px 18px;">
                            <p style="margin:0 0 10px;font-size:13px;color:#64748b;">Invoice #{{ $booking->id }}</p>
                            <h1 style="margin:0 0 10px;font-size:30px;line-height:1.25;color:#0f172a;">
                                {{ $isCompleted ? 'Final Trip Invoice' : 'Booking Invoice' }}
                            </h1>
                            <span style="display:inline-block;padding:7px 12px;border-radius:999px;background:{{ $statusBg }};color:{{ $statusColor }};font-weight:700;font-size:12px;letter-spacing:.08em;">
                                {{ $statusLabel }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 24px 22px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #dbe6f3;border-radius:10px;background:#ffffff;overflow:hidden;">
                                <tr>
                                    <td style="padding:14px 16px;font-size:12px;color:#64748b;text-transform:uppercase;letter-spacing:.08em;font-weight:700;">Vehicle Details</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 16px 14px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td width="140" valign="top" style="padding:0 14px 0 0;">
                                                    <img src="{{ $carImageSrc }}" alt="{{ $booking->car?->name ?: 'Vehicle image' }}" style="display:block;width:130px;height:88px;border-radius:8px;border:1px solid #dbe6f3;background:#f8fbff;object-fit:cover;">
                                                </td>
                                                <td valign="top" style="font-size:14px;color:#334155;line-height:1.8;">
                                                    <strong style="color:#0f172a;font-size:16px;">{{ $booking->car?->name ?: 'Vehicle' }}</strong><br>
                                                    <strong style="color:#0f172a;">Plate:</strong> {{ $booking->car?->plate_no ?: '-' }}<br>
                                                    <strong style="color:#0f172a;">Year:</strong> {{ $vehicleYear ?: '-' }}<br>
                                                    <strong style="color:#0f172a;">Transmission:</strong> {{ $vehicleTransmission ?: '-' }}<br>
                                                    <strong style="color:#0f172a;">Fuel:</strong> {{ $vehicleFuel ?: '-' }}
                                                </td>
                                                <td width="160" valign="top" style="padding:0 0 0 10px;">
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:2px solid #f59e0b;background:#fff7ed;border-radius:8px;">
                                                        <tr>
                                                            <td style="padding:10px 8px;text-align:center;">
                                                                <div style="font-size:11px;font-weight:700;color:#b45309;letter-spacing:.08em;text-transform:uppercase;">Rental Vehicle</div>
                                                                <div style="margin-top:4px;font-size:14px;font-weight:700;color:#92400e;">{{ $booking->car?->plate_no ?: 'N/A' }}</div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:12px;border:1px solid #dbe6f3;border-radius:10px;background:#f8fbff;padding:14px 16px;">
                                <tr>
                                    <td style="font-size:12px;color:#64748b;text-transform:uppercase;letter-spacing:.08em;padding-bottom:8px;font-weight:700;">Customer Details</td>
                                </tr>
                                <tr>
                                    <td style="font-size:15px;color:#334155;line-height:1.7;">
                                        <strong style="color:#0f172a;">{{ $booking->customer_name }}</strong><br>
                                        {{ $booking->customer_email ?: '-' }}<br>
                                        {{ $booking->customer_phone ?: '-' }}
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:12px;border:1px solid #dbe6f3;border-radius:10px;background:#ffffff;padding:14px 16px;">
                                <tr>
                                    <td style="font-size:12px;color:#64748b;text-transform:uppercase;letter-spacing:.08em;padding-bottom:8px;font-weight:700;">Trip Details</td>
                                </tr>
                                <tr><td style="font-size:14px;color:#334155;line-height:1.8;"><strong style="color:#0f172a;">Vehicle:</strong> {{ $booking->car?->name }} ({{ $booking->car?->plate_no }})</td></tr>
                                <tr><td style="font-size:14px;color:#334155;line-height:1.8;"><strong style="color:#0f172a;">Date Range:</strong> {{ $booking->start_date?->format('M d, Y') }} - {{ $booking->end_date?->format('M d, Y') }}</td></tr>
                                <tr><td style="font-size:14px;color:#334155;line-height:1.8;"><strong style="color:#0f172a;">Pickup Location:</strong> {{ $booking->pickup_location ?: 'Not specified' }}</td></tr>
                                <tr><td style="font-size:14px;color:#334155;line-height:1.8;"><strong style="color:#0f172a;">Rental Days:</strong> {{ $booking->rental_days }} day(s)</td></tr>
                            </table>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:12px;border:1px solid #dbe6f3;border-radius:10px;background:#ffffff;overflow:hidden;">
                                <tr>
                                    <td style="padding:14px 16px;font-size:12px;color:#64748b;text-transform:uppercase;letter-spacing:.08em;font-weight:700;">Payment Summary</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 16px 14px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr><td style="padding:5px 0;color:#334155;font-size:15px;">Base Amount</td><td style="padding:5px 0;color:#0f172a;font-size:15px;text-align:right;">LKR {{ number_format($baseAmount, 2) }}</td></tr>
                                            <tr><td style="padding:5px 0;color:#334155;font-size:15px;">Additional Amount</td><td style="padding:5px 0;color:#0f172a;font-size:15px;text-align:right;">LKR {{ number_format($additionalAmount, 2) }}</td></tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background:#0f66c3;color:#ffffff;padding:14px 16px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="font-size:24px;font-weight:700;">Total Amount</td>
                                                <td style="font-size:24px;font-weight:700;text-align:right;">LKR {{ number_format($finalAmount, 2) }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:16px 0 0;font-size:13px;line-height:1.7;color:#64748b;">
                                Thank you for choosing R&amp;A Auto Rentals.
                                @if($isCompleted)
                                    Your trip is completed. This is your final invoice summary.
                                @else
                                    Your booking is confirmed and invoice details are attached in this email summary.
                                @endif
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
