<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to R&A Auto Rentals</title>
    <style>
        @media only screen and (max-width: 620px) {
            .email-shell {
                padding: 12px 8px !important;
            }

            .email-card {
                border-radius: 10px !important;
            }

            .email-header {
                padding: 14px 12px !important;
            }

            .email-brand {
                font-size: 20px !important;
                line-height: 1.25 !important;
            }

            .email-hero,
            .email-content {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            .email-hero {
                padding-top: 16px !important;
                padding-bottom: 8px !important;
            }

            .email-content {
                padding-top: 10px !important;
                padding-bottom: 16px !important;
            }

            .email-title {
                font-size: 28px !important;
            }

            .email-subtitle {
                font-size: 15px !important;
            }

            .email-greeting {
                font-size: 24px !important;
            }

            .email-login-box {
                padding: 12px !important;
            }

            .email-login-btn {
                font-size: 17px !important;
                padding: 13px 10px !important;
            }
        }
    </style>
</head>
<body style="margin:0;padding:0;background:#eef2f7;font-family:Arial,Helvetica,sans-serif;color:#0f172a;">
    @php
        $logoPath = public_path('images/logo.png');
        $logoSrc = file_exists($logoPath) ? $message->embed($logoPath) : url('/images/logo.png');
    @endphp

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" class="email-shell" style="background:#eef2f7;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" class="email-card" style="max-width:640px;background:#ffffff;border:1px solid #d8e0ec;border-radius:12px;overflow:hidden;">
                    <tr>
                        <td class="email-header" style="background:#1f6aa9;padding:18px 18px;text-align:center;">
                            <img src="{{ $logoSrc }}" alt="R&A Auto Rentals" width="44" height="44" style="width:44px;height:44px;display:inline-block;vertical-align:middle;border-radius:8px;background:#ffffff;padding:5px;object-fit:contain;">
                            <span class="email-brand" style="display:inline-block;vertical-align:middle;margin-left:8px;color:#ffffff;font-size:28px;line-height:1.2;font-weight:700;">R&amp;A Auto Rentals</span>
                        </td>
                    </tr>

                    <tr>
                        <td class="email-hero" style="padding:24px 28px 10px;text-align:center;">
                            <h1 class="email-title" style="margin:0;color:#1c5f97;font-size:40px;line-height:1.2;font-weight:700;">Welcome to R&amp;A Auto Rentals</h1>
                            <p class="email-subtitle" style="margin:10px 0 0;font-size:17px;line-height:1.5;color:#334155;">Easiest way to rent anything online.</p>
                        </td>
                    </tr>

                    <tr>
                        <td class="email-content" style="padding:12px 28px 26px;">
                            <p class="email-greeting" style="margin:0 0 10px;font-size:30px;line-height:1.4;color:#1f2d44;">Hi <strong>{{ $user->name ?: 'Customer' }}</strong>,</p>
                            <p style="margin:0 0 18px;font-size:16px;line-height:1.65;color:#334155;">Your account was created automatically when you confirmed your booking.</p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" class="email-login-box" style="border:1px solid #d4dfec;border-radius:10px;background:#f7faff;padding:16px;">
                                <tr>
                                    <td style="font-size:20px;line-height:1.3;color:#1f2d44;font-weight:700;padding-bottom:12px;">Your <span style="background:#fde68a;padding:0 4px;">login</span> information</td>
                                </tr>
                                <tr>
                                    <td style="font-size:14px;line-height:1.5;color:#475569;padding-bottom:4px;">Username:</td>
                                </tr>
                                <tr>
                                    <td style="font-size:18px;line-height:1.45;color:#0f5cc0;font-weight:700;padding-bottom:10px;word-break:break-word;">{{ $user->email }}</td>
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
                                    <td style="padding-top:16px;">
                                        <a href="{{ route('login') }}" class="email-login-btn" style="display:block;width:100%;background:#1f6aa9;color:#ffffff;text-decoration:none;text-align:center;font-size:20px;line-height:1.2;font-weight:700;padding:14px 12px;border-radius:6px;">
                                            <span style="background:#fde68a;color:#0f172a;padding:0 4px;">Login</span> to your account
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:18px 0 0;font-size:15px;line-height:1.65;color:#334155;">For your security, please change your password after your first login.</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:16px 18px;border-top:1px solid #dde7f3;background:#f8fbff;text-align:center;">
                            <p style="margin:0 0 8px;font-size:14px;line-height:1.5;color:#7b8ea9;">
                                <a href="{{ route('home') }}" style="color:#7b8ea9;text-decoration:none;">Help Center</a>
                                &nbsp;&bull;&nbsp;
                                <a href="{{ route('privacy-policy') }}" style="color:#7b8ea9;text-decoration:none;">Privacy Policy</a>
                                &nbsp;&bull;&nbsp;
                                <a href="{{ route('login') }}" style="color:#7b8ea9;text-decoration:none;">Login</a>
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
