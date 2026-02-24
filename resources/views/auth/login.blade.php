<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | Car Rentals</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      min-height: 100vh;
      font-family: "Plus Jakarta Sans", "Segoe UI", Tahoma, sans-serif;
      color: #172332;
      background:
        radial-gradient(45rem 25rem at 8% -10%, rgba(232, 74, 36, 0.22), transparent 70%),
        radial-gradient(34rem 18rem at 90% 3%, rgba(19, 111, 122, 0.24), transparent 70%),
        linear-gradient(180deg, #f8f3e9 0%, #efe6d6 100%);
      display: grid;
      place-items: center;
      padding: 1rem;
    }
    .auth-card {
      width: min(460px, 100%);
      background: rgba(255, 255, 255, 0.88);
      border: 1px solid rgba(255, 255, 255, 0.75);
      border-radius: 1rem;
      box-shadow: 0 12px 30px rgba(23, 35, 50, 0.08);
    }
    .auth-title {
      font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
      font-weight: 700;
    }
    .form-control {
      border-color: rgba(23, 35, 50, 0.2);
      background-color: rgba(255, 255, 255, 0.9);
      border-radius: .7rem;
    }
    .btn-dark {
      background: linear-gradient(135deg, #e84a24, #f08f25) !important;
      border-color: transparent !important;
    }
  </style>
</head>
<body>
  <div class="auth-card p-4">
    <h3 class="auth-title mb-1">Welcome Back</h3>
    <p class="text-muted mb-4">Sign in to continue to your dashboard.</p>

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="post" action="{{ route('login.submit') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3 form-check">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label" for="remember">Remember me</label>
      </div>
      <button class="btn btn-dark w-100">Login</button>
    </form>

    <p class="mb-0 mt-3 text-center text-muted">
      No account?
      <a href="{{ route('register') }}">Create one</a>
    </p>
  </div>
</body>
</html>
