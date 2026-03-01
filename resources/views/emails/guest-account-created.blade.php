<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to R&A Auto Rentals</title>
</head>
<body style="margin:0;padding:0;background:#eef2f7;font-family:Arial,Helvetica,sans-serif;color:#0f172a;">
    @php
        $logoPath = public_path('images/logo.png');
        $logoSrc = file_exists($logoPath) ? $message->embed($logoPath) : url('/images/logo.png');
    @endphp

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#eef2f7;padding:26px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:680px;background:#ffffff;border:1px solid #d8e0ec;">
                    <tr>
                        <td style="background:#1f6aa9;padding:24px 20px;text-align:center;">
                            <img src="{{ $logoSrc }}" alt="R&A Auto Rentals" style="width:56px;height:56px;display:inline-block;vertical-align:middle;border-radius:8px;background:#ffffff;padding:6px;margin-right:10px;object-fit:contain;">
                            <span style="display:inline-block;vertical-align:middle;color:#ffffff;font-size:42px;line-height:1.2;font-weight:700;">R&amp;A Auto Rentals</span>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:26px 30px 12px;text-align:center;">
                            <h1 style="margin:0;color:#1c5f97;font-size:46px;line-height:1.2;font-weight:700;">Welcome to R&amp;A Auto Rentals</h1>
                            <p style="margin:10px 0 0;font-size:18px;line-height:1.5;color:#334155;">Easiest way to rent anything online.</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:18px 30px 30px;">
                            <p style="margin:0 0 10px;font-size:28px;line-height:1.5;color:#1f2d44;">Hi <strong>{{ $user->name ?: 'Customer' }}</strong>,</p>
                            <p style="margin:0 0 20px;font-size:16px;line-height:1.7;color:#334155;">Your account was created automatically when you confirmed your booking.</p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #d4dfec;border-radius:10px;background:#f7faff;padding:18px 18px;">
                                <tr>
                                    <td style="font-size:20px;line-height:1.2;color:#1f2d44;font-weight:700;padding-bottom:12px;">Your <span style="background:#fde68a;padding:0 4px;">login</span> information</td>
                                </tr>
                                <tr>
                                    <td style="font-size:14px;line-height:1.5;color:#475569;padding-bottom:4px;">Username:</td>
                                </tr>
                                <tr>
                                    <td style="font-size:18px;line-height:1.5;color:#0f5cc0;font-weight:700;padding-bottom:10px;">{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td style="border-top:1px solid #dbe6f3;padding-top:12px;font-size:14px;line-height:1.5;color:#475569;">Password:</td>
                                </tr>
                                <tr>
                                    <td style="padding-top:6px;">
                                        <span style="display:inline-block;background:#e6edf7;border:1px solid #d1ddef;color:#0f172a;font-size:18px;line-height:1.3;font-weight:700;padding:10px 14px;border-radius:8px;letter-spacing:.3px;">{{ $temporaryPassword }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top:20px;">
                                        <a href="{{ route('login') }}" style="display:block;width:100%;background:#1f6aa9;color:#ffffff;text-decoration:none;text-align:center;font-size:20px;line-height:1.2;font-weight:700;padding:16px 12px;border-radius:6px;">
                                            <span style="background:#fde68a;color:#0f172a;padding:0 4px;">Login</span> to your account
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:20px 0 0;font-size:15px;line-height:1.7;color:#334155;">For your security, please change your password after your first login.</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:18px 20px;border-top:1px solid #dde7f3;background:#f8fbff;text-align:center;">
                            <p style="margin:0 0 10px;font-size:14px;line-height:1.5;color:#7b8ea9;">
                                <a href="{{ route('home') }}" style="color:#7b8ea9;text-decoration:none;">Help Center</a>
                                &nbsp;&bull;&nbsp;
                                <a href="{{ route('privacy-policy') }}" style="color:#7b8ea9;text-decoration:none;">Privacy Policy</a>
                                &nbsp;&bull;&nbsp;
                                <a href="{{ route('profile.edit') }}" style="color:#7b8ea9;text-decoration:none;">Account Settings</a>
                            </p>
                            <p style="margin:0 0 6px;font-size:13px;line-height:1.5;color:#94a3b8;">&copy; {{ now()->year }} R&amp;A Auto Rentals. All rights reserved.</p>
                            <p style="margin:0;font-size:13px;line-height:1.55;color:#94a3b8;">You are receiving this email because you recently made a booking with R&amp;A Auto Rentals.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
