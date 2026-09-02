<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Partner Registration Successful</title>
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
                    <div style="width:42px;height:42px;line-height:42px;border-radius:10px;background:rgba(255,255,255,.15);font-size:20px;color:#ffffff;margin:0 auto 10px;">&#10003;</div>
                    <div style="font-size:34px;line-height:1.2;color:#ffffff;font-weight:700;">Registration Successful</div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <tr>
            <td style="padding:20px 22px;">
              <p style="margin:0 0 12px;font-size:16px;line-height:1.6;color:#324b6d;">
                Hi <strong>{{ $partner->name }}</strong>, thank you for joining R&amp;A Auto Rentals as a partner applicant.
              </p>
              <p style="margin:0 0 16px;font-size:15px;line-height:1.6;color:#324b6d;">
                Your registration was successful, and your application is now under review by our admin team.
              </p>

              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e1e8f3;border-radius:8px;overflow:hidden;background:#ffffff;">
                <tr><td style="padding:12px 14px;border-bottom:1px solid #edf2f9;"><div style="font-size:11px;color:#7f91ad;letter-spacing:.08em;font-weight:700;">EMAIL ADDRESS</div><div style="margin-top:4px;font-size:18px;color:#162b4a;font-weight:700;">{{ $partner->email }}</div></td></tr>
                <tr><td style="padding:12px 14px;border-bottom:1px solid #edf2f9;"><div style="font-size:11px;color:#7f91ad;letter-spacing:.08em;font-weight:700;">PHONE NUMBER</div><div style="margin-top:4px;font-size:18px;color:#162b4a;font-weight:700;">{{ $partner->phone ?: '-' }}</div></td></tr>
                <tr><td style="padding:12px 14px;border-bottom:1px solid #edf2f9;"><div style="font-size:11px;color:#7f91ad;letter-spacing:.08em;font-weight:700;">VEHICLE</div><div style="margin-top:4px;font-size:18px;color:#162b4a;font-weight:700;">{{ $car->name }} ({{ $car->plate_no }})</div></td></tr>
                <tr><td style="padding:12px 14px;"><div style="font-size:11px;color:#7f91ad;letter-spacing:.08em;font-weight:700;">APPLICATION STATUS</div><div style="margin-top:6px;display:inline-block;background:#eef3fb;color:#334a6b;border-radius:4px;padding:4px 8px;font-size:11px;font-weight:700;letter-spacing:.08em;">PENDING ADMIN REVIEW</div></td></tr>
              </table>

              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:16px;border:1px solid #e0e8f5;border-radius:8px;background:#f7faff;">
                <tr>
                  <td style="padding:12px 14px;font-size:14px;line-height:1.6;color:#536b8a;">
                    Our team will contact you after verification to activate partner role access and permissions.
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>

