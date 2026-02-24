<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Car Rental Manager')</title>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --bg: #f4efe4;
      --bg-soft: #f8f3e9;
      --panel: rgba(255, 255, 255, 0.84);
      --panel-strong: #ffffff;
      --text: #172332;
      --muted: #5c6b7b;
      --line: rgba(23, 35, 50, 0.15);
      --primary: #e84a24;
      --primary-deep: #be3614;
      --teal: #136f7a;
      --sidebar-width: 286px;
    }

    body {
      font-family: "Plus Jakarta Sans", "Segoe UI", Tahoma, sans-serif;
      color: var(--text);
      background:
        radial-gradient(45rem 25rem at 8% -10%, rgba(232, 74, 36, 0.22), transparent 70%),
        radial-gradient(34rem 18rem at 90% 3%, rgba(19, 111, 122, 0.24), transparent 70%),
        linear-gradient(180deg, #f8f3e9 0%, #efe6d6 100%);
    }

    .topbar {
      backdrop-filter: blur(10px);
      background: rgba(248, 243, 233, 0.82) !important;
      border-bottom: 1px solid var(--line);
    }

    .navbar-brand {
      font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
      font-weight: 700;
      letter-spacing: 0.02em;
      color: var(--text) !important;
    }

    .nav-link {
      color: var(--muted) !important;
      font-weight: 600;
      border-radius: .6rem;
      padding: .4rem .7rem !important;
    }

    .nav-link:hover {
      color: var(--text) !important;
      background: rgba(255, 255, 255, 0.72);
    }

    .shell-wrap {
      min-height: calc(100vh - 66px);
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: var(--sidebar-width);
      min-height: 100vh;
      height: 100vh;
      overflow-y: auto;
      background: transparent;
      border-right: 1px solid var(--line) !important;
      z-index: 5;
      padding: 84px .8rem 1rem .8rem !important;
    }

    .sidebar-card {
      background: rgba(255, 255, 255, 0.7);
      border: 1px solid rgba(255, 255, 255, 0.74);
      border-radius: 1rem;
      backdrop-filter: blur(10px);
      box-shadow: 0 12px 24px rgba(23, 35, 50, 0.08);
      padding: .8rem;
    }

    .menu-section {
      margin-bottom: .75rem;
    }

    .menu-section:last-child {
      margin-bottom: 0;
    }

    .menu-title {
      font-size: .72rem;
      font-weight: 800;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: #6f7e8d;
      padding: .4rem .65rem;
    }

    .menu-link {
      display: flex;
      align-items: center;
      gap: .55rem;
      border: 1px solid transparent;
      border-radius: .75rem;
      margin-bottom: .28rem;
      color: var(--muted);
      font-weight: 700;
      text-decoration: none;
      padding: .58rem .65rem;
      transition: 150ms ease;
    }

    .menu-link:last-child {
      margin-bottom: 0;
    }

    .menu-link:hover {
      background: rgba(255, 255, 255, 0.86);
      border-color: rgba(23, 35, 50, 0.1);
      color: var(--text);
      transform: translateX(2px);
    }

    .menu-link.active {
      background: linear-gradient(90deg, rgba(232, 74, 36, 0.16), rgba(232, 74, 36, 0.06));
      border-color: rgba(232, 74, 36, 0.34);
      color: #a73418;
      box-shadow: inset 3px 0 0 #db4320;
    }

    .menu-dot {
      width: .5rem;
      height: .5rem;
      border-radius: 50%;
      background: rgba(23, 35, 50, 0.22);
      flex-shrink: 0;
    }

    .menu-link.active .menu-dot {
      background: #d8411e;
    }

    .app-main {
      padding-top: 1.25rem !important;
      padding-bottom: 2rem !important;
    }

    .menu-icon-btn {
      width: 40px;
      height: 40px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      line-height: 1;
      padding: 0;
      margin-right: .65rem;
    }

    body.sidebar-collapsed .sidebar {
      display: none !important;
    }

    body.sidebar-collapsed .app-main-col {
      flex: 0 0 100%;
      max-width: 100%;
      margin-left: 0;
      width: 100%;
    }

    .app-main-col {
      margin-left: var(--sidebar-width);
      width: calc(100% - var(--sidebar-width));
      max-width: none;
      flex: 0 0 auto;
    }

    .card {
      background: var(--panel);
      border: 1px solid rgba(255, 255, 255, 0.7);
      border-radius: 1rem;
      box-shadow: 0 12px 30px rgba(23, 35, 50, 0.08) !important;
      overflow: hidden;
    }

    .card-header {
      background: rgba(255, 255, 255, 0.72);
      border-bottom: 1px solid var(--line);
      font-weight: 700;
      color: var(--text);
    }

    .table {
      --bs-table-bg: transparent;
      --bs-table-color: var(--text);
      --bs-table-striped-bg: rgba(255, 255, 255, 0.44);
      --bs-table-hover-bg: rgba(232, 74, 36, 0.08);
      margin-bottom: 0;
    }

    .table thead th {
      background: rgba(255, 255, 255, 0.68);
      color: var(--muted);
      font-weight: 700;
      border-bottom: 1px solid var(--line);
    }

    .table td, .table th {
      border-color: rgba(23, 35, 50, 0.08);
      vertical-align: middle;
    }

    .form-control, .form-select {
      border-color: rgba(23, 35, 50, 0.2);
      background-color: rgba(255, 255, 255, 0.88);
      color: var(--text);
      border-radius: .7rem;
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 .2rem rgba(232, 74, 36, 0.2);
      background-color: #fff;
    }

    .btn-dark,
    .btn-outline-dark:hover {
      background: linear-gradient(135deg, var(--primary), #f08f25) !important;
      border-color: transparent !important;
      color: #fff !important;
      box-shadow: 0 10px 22px rgba(232, 74, 36, 0.25);
    }

    .btn-outline-dark {
      border-color: rgba(23, 35, 50, 0.26);
      color: var(--text);
    }

    .btn-outline-danger {
      border-color: rgba(185, 32, 32, 0.45);
      color: #932323;
    }

    .alert {
      border: 0;
      border-radius: .85rem;
      box-shadow: 0 8px 20px rgba(23, 35, 50, 0.08);
    }

    .modal {
      overflow-y: auto;
    }

    .modal-content {
      max-height: calc(100vh - 2rem);
    }

    .modal-dialog-scrollable .modal-body {
      overflow-y: auto;
      max-height: calc(100vh - 220px);
      padding-bottom: 1.25rem;
    }

    .modal-footer {
      position: sticky;
      bottom: 0;
      z-index: 2;
      background: var(--panel-strong);
      border-top: 1px solid var(--line);
    }

    .badge.bg-warning {
      background: #ffd36b !important;
    }

    .text-muted {
      color: var(--muted) !important;
    }

    @media (max-width: 991.98px) {
      .sidebar {
        position: static;
        top: auto;
        left: auto;
        width: 100%;
        min-height: auto;
        height: auto;
        overflow: visible;
      }

      .app-main {
        padding-top: .8rem !important;
      }

      .app-main-col {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light topbar sticky-top">
  <div class="container-fluid">
    @auth
      <button class="btn btn-sm btn-outline-dark d-none d-lg-inline-flex menu-icon-btn" type="button" id="sidebarToggle" aria-label="Toggle menu" title="Toggle menu">
        <span id="sidebarToggleIcon">&#9776;</span>
      </button>
    @endauth
    <a class="navbar-brand" href="{{ url('/') }}">Car Rentals</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        @auth
        @if(\Illuminate\Support\Facades\Route::has('dashboard'))
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
        @endif
        @if(auth()->user()->canAccess('payments') && \Illuminate\Support\Facades\Route::has('payments.index'))
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}">Payments</a></li>
        @endif
        @if(auth()->user()->canAccess('users_manage') && \Illuminate\Support\Facades\Route::has('users.index'))
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">Users</a></li>
        @endif
        @if(auth()->user()->canAccess('permissions_manage') && \Illuminate\Support\Facades\Route::has('permissions.index'))
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}" href="{{ route('permissions.index') }}">Permissions</a></li>
        @endif
          <li class="nav-item"><span class="nav-link">{{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</span></li>
          <li class="nav-item">
            <form method="post" action="{{ route('logout') }}">
              @csrf
              <button class="btn btn-sm btn-outline-dark ms-lg-2 mt-2 mt-lg-0" type="submit">Logout</button>
            </form>
          </li>
        @else
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
        @endauth
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid shell-wrap">
  <div class="row">
    <aside class="sidebar d-none d-lg-block">
      <div class="sidebar-card">
        <div class="menu-section">
          <div class="menu-title">Core</div>
          @if(\Illuminate\Support\Facades\Route::has('dashboard'))
            <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span class="menu-dot"></span>Dashboard</a>
          @endif
          @if(auth()->user()->canAccess('gps_logs') && \Illuminate\Support\Facades\Route::has('gps-logs.index'))
            <a class="menu-link {{ request()->routeIs('gps-logs.*') ? 'active' : '' }}" href="{{ route('gps-logs.index') }}"><span class="menu-dot"></span>DAGPS KM Logs</a>
          @endif
        </div>

        <div class="menu-section">
          <div class="menu-title">Operations</div>
          @if(auth()->user()->canAccess('cars') && \Illuminate\Support\Facades\Route::has('cars.index'))
            <a class="menu-link {{ request()->routeIs('cars.*') ? 'active' : '' }}" href="{{ route('cars.index') }}"><span class="menu-dot"></span>Cars</a>
          @endif
          @if(auth()->user()->canAccess('customers') && \Illuminate\Support\Facades\Route::has('customers.index'))
            <a class="menu-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}"><span class="menu-dot"></span>Customers</a>
          @endif
          @if(auth()->user()->canAccess('payments') && \Illuminate\Support\Facades\Route::has('rentals.index'))
            <a class="menu-link {{ request()->routeIs('rentals.*') ? 'active' : '' }}" href="{{ route('rentals.index') }}"><span class="menu-dot"></span>Rentals</a>
          @endif
          @if(auth()->user()->canAccess('payments') && \Illuminate\Support\Facades\Route::has('payments.index'))
            <a class="menu-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}"><span class="menu-dot"></span>Payments</a>
          @endif
          @if(auth()->user()->canAccess('agreements') && \Illuminate\Support\Facades\Route::has('agreements.index'))
            <a class="menu-link {{ request()->routeIs('agreements.*') ? 'active' : '' }}" href="{{ route('agreements.index') }}"><span class="menu-dot"></span>Agreements</a>
          @endif
          @if(auth()->user()->canAccess('expenses') && \Illuminate\Support\Facades\Route::has('expenses.index'))
            <a class="menu-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="{{ route('expenses.index') }}"><span class="menu-dot"></span>Expenses</a>
          @endif
        </div>

        <div class="menu-section">
          <div class="menu-title">Administration</div>
          @if(auth()->user()->canAccess('users_manage') && \Illuminate\Support\Facades\Route::has('users.index'))
            <a class="menu-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><span class="menu-dot"></span>Users & Roles</a>
          @endif
          @if(auth()->user()->canAccess('permissions_manage') && \Illuminate\Support\Facades\Route::has('permissions.index'))
            <a class="menu-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}" href="{{ route('permissions.index') }}"><span class="menu-dot"></span>Permissions</a>
          @endif
        </div>
      </div>
    </aside>

    <main class="col-12 p-3 p-lg-4 app-main app-main-col">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      @yield('content')
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('sidebarToggle');
    const toggleIcon = document.getElementById('sidebarToggleIcon');
    if (!toggleButton) return;

    const KEY = 'sidebarHidden';
    const applyState = (hidden) => {
      document.body.classList.toggle('sidebar-collapsed', hidden);
      if (toggleIcon) {
        toggleIcon.textContent = '\u2630';
      }
      toggleButton.setAttribute('aria-expanded', hidden ? 'false' : 'true');
      toggleButton.setAttribute('title', hidden ? 'Show menu' : 'Hide menu');
    };

    applyState(localStorage.getItem(KEY) === '1');

    toggleButton.addEventListener('click', function () {
      const hidden = !document.body.classList.contains('sidebar-collapsed');
      localStorage.setItem(KEY, hidden ? '1' : '0');
      applyState(hidden);
    });
  });
</script>
</body>
</html>
