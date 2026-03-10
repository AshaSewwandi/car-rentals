<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @include('partials.seo-meta', [
    'title' => 'Sign Up | R&A Auto Rentals',
    'description' => 'Create your rental account to book vehicles online, track active trips, and manage profile details with R&A Auto Rentals.',
    'keywords' => [
      'rental registration',
      'create account',
      'book rental online',
      'customer sign up',
      'rental platform account',
    ],
    'robots' => 'noindex,follow',
  ])
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      min-height: 100vh;
      font-family: "Plus Jakarta Sans", "Segoe UI", Tahoma, sans-serif;
      color: #10223d;
      background:
        radial-gradient(45rem 25rem at 8% -10%, rgba(29, 78, 216, 0.24), transparent 70%),
        radial-gradient(34rem 18rem at 90% 3%, rgba(14, 116, 144, 0.22), transparent 70%),
        linear-gradient(180deg, #eff6ff 0%, #dbeafe 100%);
      display: grid;
      place-items: center;
      padding: 1rem;
    }
    .auth-card {
      width: min(500px, 100%);
      background: rgba(255, 255, 255, 0.93);
      border: 1px solid rgba(147, 197, 253, 0.7);
      border-radius: 1rem;
      box-shadow: 0 18px 40px rgba(30, 64, 175, 0.14);
    }
    .auth-title {
      font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
      font-weight: 700;
      color: #0f172a;
    }
    .form-control {
      border-color: #bfdbfe;
      background-color: #f8fbff;
      border-radius: .7rem;
    }
    .form-control:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 .2rem rgba(37, 99, 235, 0.15);
    }
    .btn-dark {
      background: linear-gradient(135deg, #1d4ed8, #0369a1) !important;
      border-color: transparent !important;
    }
    .btn-dark:hover {
      filter: brightness(1.04);
    }
  </style>
</head>
<body>
  <div class="auth-card p-4">
    <h3 class="auth-title mb-1">Create Account</h3>
    <p class="text-muted mb-4">Sign up to access your rental management workspace.</p>

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="post" action="{{ route('register.submit') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Phone Number</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+94 ..." required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
      </div>
      <button class="btn btn-dark w-100">Sign Up</button>
    </form>

    <p class="mb-0 mt-3 text-center text-muted">
      Already have an account?
      <a href="{{ route('login') }}">Login</a>
    </p>
  </div>
</body>
</html>
