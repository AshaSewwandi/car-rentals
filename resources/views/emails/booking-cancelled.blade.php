<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rental Trip Cancelled</title>
</head>
<body style="margin:0;padding:0;background:#f4f7fb;font-family:Arial,Helvetica,sans-serif;color:#0f172a;">
    @php
        $logoPath = public_path('images/logo.png');
        $logoSrc = file_exists($logoPath) ? $message->embed($logoPath) : url('/images/logo.png');
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
                        <td style="padding:22px 24px;">
                            <p style="margin:0 0 10px;font-size:13px;color:#64748b;">Booking #{{ $booking->id }}</p>
                            <h1 style="margin:0 0 10px;font-size:30px;line-height:1.25;color:#0f172a;">Rental Trip Cancelled</h1>
                            <span style="display:inline-block;padding:7px 12px;border-radius:999px;background:#eff6ff;color:#0f66c3;font-weight:700;font-size:12px;letter-spacing:.08em;">
                                CANCELLED
                            </span>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:18px;border:1px solid #dbe6f3;border-radius:10px;background:#ffffff;padding:14px 16px;">
                                <tr><td style="font-size:14px;color:#334155;line-height:1.8;"><strong style="color:#0f172a;">Customer:</strong> {{ $booking->customer_name }}</td></tr>
                                <tr><td style="font-size:14px;color:#334155;line-height:1.8;"><strong style="color:#0f172a;">Vehicle:</strong> {{ $booking->car?->name }} ({{ $booking->car?->plate_no }})</td></tr>
                                <tr><td style="font-size:14px;color:#334155;line-height:1.8;"><strong style="color:#0f172a;">Trip Dates:</strong> {{ $booking->start_date?->format('M d, Y') }} - {{ $booking->end_date?->format('M d, Y') }}</td></tr>
                                <tr><td style="font-size:14px;color:#334155;line-height:1.8;"><strong style="color:#0f172a;">Pickup Location:</strong> {{ $booking->pickup_location ?: 'Not specified' }}</td></tr>
                                <tr><td style="font-size:14px;color:#334155;line-height:1.8;"><strong style="color:#0f172a;">Cancelled By:</strong> {{ ucfirst($cancelledRole) }}{{ $cancelledBy ? ' - ' . $cancelledBy : '' }}</td></tr>
                            </table>

                            <p style="margin:16px 0 0;font-size:14px;line-height:1.7;color:#334155;">
                                This booking has been cancelled in the R&amp;A Auto Rentals system.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
