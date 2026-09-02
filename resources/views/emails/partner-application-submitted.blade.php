<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>New Partner Application Received</title>
</head>
<body style="margin:0;padding:0;background:#eef2f8;font-family:Arial,sans-serif;color:#0f2342;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#eef2f8;padding:18px 0;">
    <tr>
      <td align="center">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border:1px solid #d9e3f1;border-radius:12px;overflow:hidden;">
          <tr>
            <td style="padding:18px 20px;background:#0a2d66;">
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid rgba(208,223,247,.38);border-radius:10px;overflow:hidden;background:linear-gradient(140deg,#071b3e,#123c84);">
                <tr>
                  <td align="center" style="padding:20px 14px 18px;">
                    <div style="width:42px;height:42px;line-height:42px;border-radius:10px;background:rgba(255,255,255,.15);font-size:20px;color:#ffffff;margin:0 auto 10px;">&#128276;</div>
                    <div style="font-size:36px;line-height:1.2;color:#ffffff;font-weight:700;">New Partner Application<br>Received</div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <tr>
            <td style="padding:20px 22px;">
              <p style="margin:0 0 16px;font-size:15px;line-height:1.6;color:#324b6d;">
                A new partner application has been submitted through the portal and is pending internal review.
              </p>

              <div style="margin:0 0 10px;font-size:13px;font-weight:700;color:#0b4fae;letter-spacing:.02em;">&#128100; APPLICANT DETAILS</div>
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e1e8f3;border-radius:8px;overflow:hidden;background:#ffffff;">
                <tr><td style="padding:12px 14px;border-bottom:1px solid #edf2f9;"><div style="font-size:11px;color:#7f91ad;letter-spacing:.08em;font-weight:700;">FULL NAME</div><div style="margin-top:4px;font-size:18px;color:#162b4a;font-weight:700;">{{ $partner->name }}</div></td></tr>
                <tr><td style="padding:12px 14px;border-bottom:1px solid #edf2f9;"><div style="font-size:11px;color:#7f91ad;letter-spacing:.08em;font-weight:700;">EMAIL ADDRESS</div><div style="margin-top:4px;font-size:18px;color:#162b4a;font-weight:700;">{{ $partner->email }}</div></td></tr>
                <tr><td style="padding:12px 14px;border-bottom:1px solid #edf2f9;"><div style="font-size:11px;color:#7f91ad;letter-spacing:.08em;font-weight:700;">PHONE NUMBER</div><div style="margin-top:4px;font-size:18px;color:#162b4a;font-weight:700;">{{ $partner->phone ?: '-' }}</div></td></tr>
                <tr><td style="padding:12px 14px;"><div style="font-size:11px;color:#7f91ad;letter-spacing:.08em;font-weight:700;">PROPOSED ROLE</div><div style="margin-top:6px;display:inline-block;background:#eef3fb;color:#334a6b;border-radius:4px;padding:4px 8px;font-size:11px;font-weight:700;letter-spacing:.08em;">{{ strtoupper($partner->role) }}</div></td></tr>
              </table>

              <div style="margin:20px 0 10px;font-size:13px;font-weight:700;color:#0b4fae;letter-spacing:.02em;">&#128663; VEHICLE DETAILS</div>
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e1e8f3;border-radius:8px;overflow:hidden;background:#ffffff;">
                <tr><td style="padding:12px 14px;border-bottom:1px solid #edf2f9;"><div style="font-size:11px;color:#7f91ad;letter-spacing:.08em;font-weight:700;">VEHICLE NAME</div><div style="margin-top:4px;font-size:18px;color:#162b4a;font-weight:700;">{{ $car->name }}</div></td></tr>
                <tr><td style="padding:12px 14px;border-bottom:1px solid #edf2f9;"><div style="font-size:11px;color:#7f91ad;letter-spacing:.08em;font-weight:700;">PLATE NUMBER</div><div style="margin-top:4px;font-size:18px;color:#0b4fae;font-weight:700;">{{ $car->plate_no }}</div></td></tr>
                <tr><td style="padding:12px 14px;border-bottom:1px solid #edf2f9;"><div style="font-size:11px;color:#7f91ad;letter-spacing:.08em;font-weight:700;">MAKE / MODEL</div><div style="margin-top:4px;font-size:18px;color:#162b4a;font-weight:700;">{{ ($car->make ?: '-') . ' / ' . ($car->model ?: '-') }}</div></td></tr>
                <tr><td style="padding:12px 14px;border-bottom:1px solid #edf2f9;"><div style="font-size:11px;color:#7f91ad;letter-spacing:.08em;font-weight:700;">YEAR</div><div style="margin-top:4px;font-size:18px;color:#162b4a;font-weight:700;">{{ $car->year ?: '-' }}</div></td></tr>
                <tr><td style="padding:12px 14px;border-bottom:1px solid #edf2f9;"><div style="font-size:11px;color:#7f91ad;letter-spacing:.08em;font-weight:700;">DRIVER MODE</div><div style="margin-top:4px;font-size:18px;color:#162b4a;font-weight:700;">{{ $car->driver_mode ?: '-' }}</div></td></tr>
                <tr><td style="padding:12px 14px;background:#f8fbff;"><div style="font-size:11px;color:#7f91ad;letter-spacing:.08em;font-weight:700;">LONG-TERM RENTAL</div><div style="margin-top:4px;font-size:18px;color:{{ $car->allow_long_term ? '#0b4fae' : '#d11f1f' }};font-weight:700;">{{ $car->allow_long_term ? 'Yes' : 'No' }}</div></td></tr>
              </table>

              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:16px;border:1px solid #e0e8f5;border-radius:8px;background:#f7faff;">
                <tr>
                  <td style="padding:12px 14px;font-size:14px;line-height:1.6;color:#536b8a;">
                    <strong style="color:#415a7d;">Action Required:</strong> Please verify the submitted documents in the administrative dashboard to proceed with onboarding.
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <tr>
            <td style="padding:0 22px 22px;">
              <a href="{{ url('/users') }}" style="display:block;width:100%;text-align:center;background:#0b56bc;color:#ffffff;text-decoration:none;border-radius:8px;padding:12px 10px;font-size:18px;font-weight:700;">
                Review Application in Dashboard
              </a>
            </td>
          </tr>
        </table>

        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;margin:12px auto 0;text-align:center;">
          <tr><td style="font-size:20px;color:#132949;font-weight:700;padding:8px 10px 6px;">R&amp;A Auto Rentals</td></tr>
          <tr><td style="font-size:13px;color:#6f829f;padding:0 10px 8px;">Admin Portal &nbsp;&nbsp; System Status &nbsp;&nbsp; Help Desk</td></tr>
          <tr><td style="font-size:12px;color:#8ea0bb;padding:0 10px 2px;">&copy; {{ now()->year }} R&amp;A Auto Rentals. Internal Notification System.</td></tr>
          <tr><td style="font-size:12px;color:#9aabc2;padding:0 10px 8px;">This is an automated system notification. Please do not reply.</td></tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
