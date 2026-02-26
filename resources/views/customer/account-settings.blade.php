<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Account Settings | R&A Auto Rentals</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
  <style>
    :root {
      --ink: #0b2f57;
      --ink-2: #103b6a;
      --paper: #f5f7fb;
      --card: #ffffff;
      --line: #dbe6f3;
      --muted: #607087;
      --brand: #0f66c3;
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: "Plus Jakarta Sans", "Segoe UI", Tahoma, sans-serif;
      background: var(--paper);
      color: #0f172a;
    }
    .topbar {
      background: linear-gradient(90deg, var(--ink), var(--ink-2));
      color: #fff;
      border-bottom: 1px solid rgba(255, 255, 255, .18);
      padding: .9rem 0;
    }
    .container {
      width: min(1220px, calc(100% - 2rem));
      margin: 0 auto;
    }
    .topbar-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: .8rem;
    }
    .brand {
      display: inline-flex;
      align-items: center;
      gap: .65rem;
      text-decoration: none;
      color: #fff;
      font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
      font-weight: 700;
      font-size: 1.65rem;
    }
    .brand img {
      width: 42px;
      height: 28px;
      object-fit: contain;
    }
    .actions {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
    }
    .btn {
      border: 1px solid transparent;
      border-radius: 10px;
      padding: .52rem .8rem;
      font-weight: 700;
      font-size: .86rem;
      text-decoration: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .btn-light {
      color: #e5eefc;
      border-color: rgba(229, 238, 252, .55);
      background: rgba(255, 255, 255, .08);
    }
    .btn-cta {
      color: #fff;
      background: linear-gradient(135deg, #0f66c3, #2c7cd2);
    }
    .main {
      padding: 1.2rem 0 2rem;
      display: grid;
      grid-template-columns: 300px minmax(0, 1fr);
      gap: 1rem;
      align-items: start;
    }
    .card {
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(15, 23, 42, .05);
    }
    .side {
      padding: 1rem;
    }
    .avatar-row {
      display: flex;
      align-items: center;
      gap: .7rem;
      margin-bottom: .9rem;
    }
    .avatar-row img {
      width: 62px;
      height: 62px;
      border-radius: 999px;
      border: 1px solid #c7dbf5;
      background: #f0f6ff;
    }
    .avatar-name {
      margin: 0;
      font-size: 1.8rem;
      line-height: 1;
      font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
      font-weight: 700;
      color: #12233c;
    }
    .side-links {
      list-style: none;
      margin: .6rem 0 0;
      padding: 0;
      display: grid;
      gap: .35rem;
    }
    .side-links a {
      text-decoration: none;
      color: #334155;
      font-weight: 600;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border: 1px solid #ebf1f8;
      background: #f8fbff;
      border-radius: 10px;
      padding: .58rem .66rem;
    }
    .side-links a.active {
      border-color: #c9dcf8;
      background: #edf4ff;
      color: #1e3a8a;
      font-weight: 700;
    }
    .content {
      padding: 1rem;
    }
    h1 {
      margin: 0 0 .8rem;
      font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
      color: #12233c;
    }
    .settings-wrap {
      border: 1px solid var(--line);
      border-radius: 12px;
      background: #fbfdff;
      padding: .9rem;
      display: grid;
      grid-template-columns: 320px minmax(0, 1fr);
      gap: 1rem;
    }
    .profile-pic {
      border: 1px solid #e3ebf8;
      border-radius: 12px;
      padding: .9rem;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 280px;
      background: #fff;
    }
    .profile-pic img {
      width: 250px;
      height: 250px;
      border-radius: 999px;
      object-fit: cover;
      border: 1px solid #dbe6f3;
      background: #f2f6ff;
    }
    .form-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: .7rem;
    }
    .section-divider {
      margin: .3rem 0 .2rem;
      border: 0;
      border-top: 1px solid #d7e2f0;
    }
    .section-title {
      margin: 0 0 .2rem;
      font-size: 1.02rem;
      font-weight: 800;
      color: #0f172a;
    }
    .section-note {
      margin: 0 0 .55rem;
      color: #64748b;
      font-size: .9rem;
    }
    .row-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: .7rem;
    }
    label {
      display: block;
      margin-bottom: .25rem;
      font-weight: 700;
      color: #334155;
    }
    input {
      width: 100%;
      border: 1px solid #c9d9ef;
      background: #fff;
      border-radius: 8px;
      padding: .62rem .72rem;
      font: inherit;
      color: #0f172a;
    }
    input:focus {
      border-color: #0f66c3;
      outline: 2px solid rgba(15, 102, 195, 0.15);
      outline-offset: 1px;
    }
    .password-row {
      display: grid;
      grid-template-columns: minmax(0, 1fr) 145px;
      gap: .55rem;
      align-items: end;
    }
    .save-row {
      margin-top: .4rem;
      display: flex;
      justify-content: flex-end;
      gap: .5rem;
    }
    .alert {
      margin-bottom: .8rem;
      border-radius: 10px;
      padding: .62rem .76rem;
      border: 1px solid;
      font-weight: 600;
    }
    .alert-success {
      color: #166534;
      background: #ecfdf3;
      border-color: #bde5cc;
    }
    .alert-danger {
      color: #991b1b;
      background: #fef2f2;
      border-color: #fecaca;
    }
    .logout-form { margin: 0; }
    @media (max-width: 1020px) {
      .main { grid-template-columns: 1fr; }
      .settings-wrap { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
      .row-2, .password-row { grid-template-columns: 1fr; }
      .brand { font-size: 1.2rem; }
      .avatar-name { font-size: 1.4rem; }
    }
  </style>
</head>
<body>
  @php
    $avatarHash = md5(strtolower(trim((string) ($user->email ?? 'guest@example.com'))));
    $avatarUrl = 'https://www.gravatar.com/avatar/' . $avatarHash . '?d=identicon&s=300';
  @endphp

  <header class="topbar">
    <div class="container topbar-inner">
      <a class="brand" href="{{ route('customer.dashboard') }}">
        <img src="{{ asset('images/logo.png') }}" alt="R&A Auto Rentals">
        <span>R&A Auto Rentals</span>
      </a>
      <div class="actions">
        <form class="logout-form" method="post" action="{{ route('logout') }}">
          @csrf
          <button class="btn btn-cta" type="submit">Log out</button>
        </form>
      </div>
    </div>
  </header>

  <div class="container main">
    <aside class="card side">
      <div class="avatar-row">
        <img src="{{ $avatarUrl }}" alt="{{ $user->name }}">
        <h2 class="avatar-name">{{ $user->name }}</h2>
      </div>
      <ul class="side-links">
        <li><a href="{{ route('customer.dashboard') }}#active-orders">Active orders <span>&rsaquo;</span></a></li>
        <li><a href="{{ route('customer.dashboard') }}">Order history <span>&rsaquo;</span></a></li>
        <li><a class="active" href="{{ route('profile.edit') }}">Account settings <span>&rsaquo;</span></a></li>
      </ul>
      <p style="margin:.8rem 0 0;color:#64748b;font-size:.85rem;">Active trips: {{ $activeTripCount }}</p>
    </aside>

    <section class="card content">
      <h1>Account settings</h1>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger">
          @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </div>
      @endif

      <form class="settings-wrap" method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <div class="profile-pic">
          <img src="{{ $avatarUrl }}" alt="{{ $user->name }}">
        </div>

        <div class="form-grid">
          <div class="section-title">Profile Details</div>

          <div class="row-2">
            <div>
              <label for="name">Name</label>
              <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>
            </div>
            <div>
              <label for="email">Email</label>
              <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
            </div>
          </div>

          <div>
            <label for="phone">Phone Number</label>
            <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" placeholder="+94 ...">
          </div>

          <hr class="section-divider">
          <div class="section-title">Change Password (Optional)</div>
          <p class="section-note">Only fill these fields if you want to set a new password.</p>

          <div class="row-2">
            <div>
              <label for="current_password">Current Password</label>
              <input id="current_password" name="current_password" type="password" autocomplete="current-password" placeholder="Current password">
            </div>
            <div>
              <label for="new_password">New Password</label>
              <input id="new_password" name="password" type="password" autocomplete="new-password" placeholder="New password">
            </div>
          </div>

          <div>
            <label for="password_confirmation">Confirm New Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Re-enter new password">
          </div>

          <div class="save-row">
            <button class="btn btn-cta" type="submit">Save Profile</button>
          </div>
        </div>
      </form>
    </section>
  </div>
</body>
</html>
