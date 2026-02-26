<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redirecting to PayHere | R&A Auto Rentals</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7fb; color: #0f172a; margin: 0; }
        .wrap { min-height: 100vh; display: grid; place-items: center; padding: 1rem; }
        .card { background: #fff; border: 1px solid #dbe6f3; border-radius: 12px; max-width: 560px; width: 100%; padding: 1rem; }
        h1 { margin: 0 0 .5rem; font-size: 1.15rem; }
        p { margin: 0 0 .8rem; color: #475569; line-height: 1.5; }
        .btn { display: inline-block; background: #0a3f8f; color: #fff; text-decoration: none; padding: .6rem .9rem; border-radius: 8px; border: 0; cursor: pointer; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h1>Redirecting to PayHere</h1>
            <p>Please wait. You are being redirected to secure checkout for booking #{{ $booking->id }}.</p>
            <form id="payhereForm" method="post" action="{{ $payHereUrl }}">
                @foreach($payload as $name => $value)
                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                @endforeach
                <button class="btn" type="submit">Continue to PayHere</button>
            </form>
        </div>
    </div>
    <script>
        window.addEventListener('load', function () {
            document.getElementById('payhereForm').submit();
        });
    </script>
</body>
</html>

