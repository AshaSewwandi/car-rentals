<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'R&A Auto Rentals')</title>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --bg: #f3f5f9;
      --bg-soft: #f8faff;
      --panel: #ffffff;
      --panel-strong: #ffffff;
      --text: #111827;
      --muted: #64748b;
      --line: rgba(15, 23, 42, 0.1);
      --primary: #0a3f8f;
      --primary-deep: #072d6b;
      --accent: #0f66c3;
      --teal: #0f66c3;
      --sidebar-width: 274px;
      --topbar-height: 76px;
    }

    body {
      font-family: "Plus Jakarta Sans", "Segoe UI", Tahoma, sans-serif;
      color: var(--text);
      background: var(--bg);
    }

    .topbar {
      backdrop-filter: blur(6px);
      background: rgba(255, 255, 255, 0.92) !important;
      border-bottom: 1px solid var(--line);
      padding: 0;
      min-height: var(--topbar-height);
    }

    .topbar .container-fluid {
      min-height: var(--topbar-height);
      padding-top: 0;
      padding-bottom: 0;
    }

    .navbar-brand {
      font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
      font-weight: 800;
      letter-spacing: -0.01em;
      color: var(--text) !important;
      display: inline-flex;
      align-items: center;
      gap: .58rem;
    }

    .brand-name {
      font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
      font-weight: 800;
      letter-spacing: -0.01em;
      line-height: 1.03;
      color: #0b1f3a;
    }

    .brand-logo {
      width: 58px;
      height: 34px;
      object-fit: contain;
      border: 0;
      background: transparent;
      padding: 0;
      box-shadow: none;
      flex-shrink: 0;
    }

    .brand-fallback {
      width: 58px;
      height: 34px;
      border-radius: .55rem;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: #fff;
      font-size: .82rem;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .nav-link {
      color: var(--muted) !important;
      font-weight: 600;
      border-radius: .6rem;
      padding: .4rem .7rem !important;
    }

    .nav-link:hover {
      color: var(--text) !important;
      background: rgba(241, 245, 249, 0.95);
    }

    .shell-wrap {
      min-height: calc(100vh - var(--topbar-height));
    }

    .sidebar {
      position: fixed;
      top: var(--topbar-height);
      left: 0;
      width: var(--sidebar-width);
      min-height: calc(100vh - var(--topbar-height));
      height: calc(100vh - var(--topbar-height));
      overflow-y: auto;
      background: #fff;
      border-right: 1px solid var(--line) !important;
      z-index: 5;
      padding: 0 !important;
    }

    .sidebar-card {
      min-height: calc(100vh - var(--topbar-height));
      display: flex;
      flex-direction: column;
      background: transparent;
      border: 0;
      border-radius: 0;
      box-shadow: none;
      padding: 0 .75rem .7rem;
    }

    .sidebar-brand {
      display: flex;
      align-items: center;
      gap: .7rem;
      padding: .55rem .65rem;
      margin-bottom: .55rem;
      border: 1px solid rgba(15, 102, 195, 0.22);
      border-radius: .9rem;
      background: #f8fbff;
      box-shadow: 0 6px 16px rgba(10, 63, 143, 0.1);
    }

    .sidebar-brand-mark {
      width: 3.45rem;
      height: 2.15rem;
      border-radius: .42rem;
      border: 0;
      background: transparent;
      display: inline-block;
      flex-shrink: 0;
      overflow: hidden;
      position: relative;
      box-shadow: none;
    }

    .sidebar-brand-mark img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      display: block;
    }

    .sidebar-brand-fallback {
      position: absolute;
      inset: 0;
      border-radius: .42rem;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: #fff;
      font-size: .78rem;
      font-weight: 700;
      align-items: center;
      justify-content: center;
      display: none;
    }

    .sidebar-brand-title {
      font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
      font-weight: 800;
      font-size: 1.07rem;
      letter-spacing: -0.01em;
      color: #0b1f3a;
      line-height: 1.02;
    }

    .sidebar-brand-sub {
      color: #64748b;
      font-size: .74rem;
      font-weight: 700;
      letter-spacing: .08em;
      text-transform: uppercase;
      line-height: 1.2;
    }

    .menu-section {
      margin-bottom: .45rem;
    }

    .menu-section:last-child {
      margin-bottom: 0;
    }

    .menu-title {
      font-size: .72rem;
      font-weight: 800;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: #94a3b8;
      padding: .22rem .62rem;
    }

    .menu-link {
      display: flex;
      align-items: center;
      gap: .55rem;
      border: 1px solid #eef2f7;
      border-radius: .75rem;
      margin-bottom: .2rem;
      color: #334155;
      font-weight: 600;
      text-decoration: none;
      padding: .5rem .62rem;
      transition: 180ms ease;
    }

    .menu-link:last-child {
      margin-bottom: 0;
    }

    .menu-link:hover {
      background: #f8fbff;
      border-color: #dbeafe;
      color: #1e3a8a;
      transform: translateX(2px);
    }

    .menu-link.active {
      background: linear-gradient(90deg, #e8efff, #f3f7ff);
      border-color: #dbeafe;
      color: var(--primary);
      box-shadow: inset 3px 0 0 var(--accent);
    }

    .menu-dot {
      width: .54rem;
      height: .54rem;
      border-radius: .18rem;
      background: #cbd5e1;
      flex-shrink: 0;
    }

    .menu-link.active .menu-dot {
      background: var(--accent);
    }

    .app-main {
      padding-top: 1rem !important;
      padding-bottom: 2rem !important;
    }

    .page-toolbar {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: .7rem;
      margin-bottom: 1rem !important;
    }

    .page-toolbar > div:first-child h4 {
      margin-bottom: .2rem !important;
      font-size: 1.72rem;
      letter-spacing: -0.01em;
    }

    .page-toolbar .text-muted {
      font-size: .94rem;
    }

    .list-card {
      border-radius: .9rem;
      border: 1px solid #dbe4ef !important;
      box-shadow: 0 6px 16px rgba(15, 23, 42, 0.04) !important;
      overflow: hidden;
    }

    .record-card {
      border: 1px solid #e3eaf3 !important;
      border-radius: .8rem !important;
      box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03) !important;
      background: #fff;
    }

    .card-header .header-title {
      font-size: 1.06rem;
      font-weight: 700;
      color: #0f172a;
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
      border: 1px solid #dbe4ef;
      border-radius: .95rem;
      box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05) !important;
      overflow: hidden;
    }

    .table-responsive {
      -webkit-overflow-scrolling: touch;
    }

    .card-header {
      background: #f8fbff;
      border-bottom: 1px solid var(--line);
      font-weight: 700;
      color: var(--text);
    }

    .table {
      --bs-table-bg: transparent;
      --bs-table-color: var(--text);
      --bs-table-striped-bg: #f8fbff;
      --bs-table-hover-bg: #eff6ff;
      margin-bottom: 0;
    }

    .table thead th {
      background: #f8fbff;
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
      box-shadow: 0 0 0 .2rem rgba(15, 118, 110, 0.18);
      background-color: #fff;
    }

    .btn-dark,
    .btn-outline-dark:hover {
      background: linear-gradient(135deg, var(--accent), var(--primary)) !important;
      border-color: transparent !important;
      color: #fff !important;
      box-shadow: 0 8px 18px rgba(10, 63, 143, 0.28);
    }

    .btn-outline-dark {
      border-color: #cbd5e1;
      color: #334155;
      background: #fff;
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
      background: #dbeafe !important;
      color: #1e3a8a !important;
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
        padding: 0 !important;
      }

      .app-main-col {
        margin-left: 0;
        width: 100%;
      }

      .page-toolbar > div:first-child h4 {
        font-size: 1.42rem;
      }

      .navbar-brand {
        max-width: calc(100vw - 110px);
      }

      .brand-logo {
        width: 48px;
        height: 30px;
      }

      .brand-name {
        font-size: 1.04rem;
        line-height: 1.1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }

      .app-main {
        padding: .8rem .35rem 1.25rem !important;
      }

      .page-toolbar {
        gap: .55rem;
      }

      .page-toolbar .d-flex {
        width: 100%;
        flex-wrap: wrap !important;
      }

      .page-toolbar .d-flex .form-control,
      .page-toolbar .d-flex .form-select,
      .page-toolbar .d-flex .btn {
        flex: 1 1 100%;
      }

      .card-header {
        padding: .78rem .9rem;
      }

      .card-body {
        padding: .85rem;
      }

      .table td, .table th {
        font-size: .9rem;
      }

      .table .btn {
        margin-bottom: .3rem;
      }
    }

    .sidebar-user {
      margin-top: auto;
      padding: .7rem .62rem .35rem;
      border-top: 1px solid var(--line);
      color: #475569;
      font-size: .88rem;
    }

    .sidebar-user strong {
      color: #0f172a;
      display: block;
      font-size: .94rem;
    }

    .sidebar-user a {
      color: inherit;
      display: block;
    }

    .app-footer {
      margin-top: 1.2rem;
      border-top: 1px solid #215fb2;
      background: linear-gradient(135deg, #184f9f 0%, #1f66c2 100%);
    }

    .app-footer-inner {
      padding: 1.25rem 0 1rem;
    }

    .app-footer-grid {
      display: grid;
      grid-template-columns: 1.2fr 1fr 1fr 1.2fr;
      gap: 1rem;
      padding-bottom: .9rem;
      border-bottom: 1px solid rgba(219, 232, 255, 0.28);
    }

    .app-footer-brand {
      display: flex;
      align-items: flex-start;
      gap: .65rem;
    }

    .app-footer-logo {
      width: 42px;
      height: 42px;
      object-fit: contain;
      border-radius: .6rem;
      border: 1px solid #dbe6f3;
      background: #f8fbff;
      padding: 4px;
      flex-shrink: 0;
    }

    .app-footer-brand-title {
      margin: 0 0 .3rem;
      font-weight: 800;
      color: #f8fbff;
      font-size: .96rem;
      letter-spacing: -.01em;
    }

    .app-footer-copy {
      margin: 0;
      color: #d9e8ff;
      font-size: .82rem;
      line-height: 1.55;
    }

    .app-footer-title {
      margin: 0 0 .55rem;
      color: #bfdbff;
      font-weight: 800;
      font-size: .72rem;
      letter-spacing: .08em;
      text-transform: uppercase;
    }

    .app-footer-links {
      list-style: none;
      margin: 0;
      padding: 0;
      display: grid;
      gap: .4rem;
    }

    .app-footer-links a {
      color: #e7f0ff;
      text-decoration: none;
      font-size: .85rem;
      font-weight: 600;
    }

    .app-footer-links a:hover {
      color: #ffffff;
    }

    .app-footer-note {
      margin: 0 0 .45rem;
      color: #d9e8ff;
      font-size: .8rem;
    }

    .app-footer-newsletter {
      display: flex;
      align-items: center;
      gap: .4rem;
    }

    .app-footer-newsletter input {
      flex: 1;
      min-width: 0;
      border: 1px solid #ffffff;
      background: #ffffff;
      border-radius: .5rem;
      padding: .45rem .6rem;
      color: #0f172a;
      font-size: .84rem;
    }

    .app-footer-newsletter input::placeholder {
      color: #64748b;
    }

    .app-footer-newsletter button {
      width: 34px;
      height: 34px;
      border: 0;
      border-radius: .5rem;
      color: #fff;
      background: linear-gradient(135deg, var(--accent), var(--primary));
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      cursor: pointer;
      box-shadow: 0 8px 16px rgba(10, 63, 143, 0.2);
    }

    .app-footer-bottom {
      padding-top: .8rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: .6rem;
      color: #d9e8ff;
      font-size: .78rem;
      flex-wrap: wrap;
    }

    .app-footer-social {
      display: inline-flex;
      gap: .8rem;
    }

    .app-footer-social a {
      text-decoration: none;
      color: #d9e8ff;
      font-size: .74rem;
      letter-spacing: .06em;
      text-transform: uppercase;
      font-weight: 700;
    }

    .app-footer-social a:hover {
      color: var(--primary);
    }

    @media (max-width: 991.98px) {
      .app-footer-grid {
        grid-template-columns: 1fr 1fr;
      }
    }

    @media (max-width: 575.98px) {
      .app-footer-grid {
        grid-template-columns: 1fr;
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
    <a class="navbar-brand" href="{{ url('/') }}">
      <img src="{{ asset('images/logo.png') }}" alt="R&A Auto Rentals logo" class="brand-logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">
      <span class="brand-fallback" style="display:none;">R&A</span>
      <span class="brand-name">R&A Auto Rentals</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        @auth
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
          <a class="menu-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}"><span class="menu-dot"></span>My Profile</a>
          @if(auth()->user()->canAccess('users_manage') && \Illuminate\Support\Facades\Route::has('users.index'))
            <a class="menu-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><span class="menu-dot"></span>Users & Roles</a>
          @endif
          @if(auth()->user()->canAccess('permissions_manage') && \Illuminate\Support\Facades\Route::has('permissions.index'))
            <a class="menu-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}" href="{{ route('permissions.index') }}"><span class="menu-dot"></span>Permissions</a>
          @endif
          @if(auth()->user()->role === 'admin' && \Illuminate\Support\Facades\Route::has('support-requests.index'))
            <a class="menu-link {{ request()->routeIs('support-requests.*') ? 'active' : '' }}" href="{{ route('support-requests.index') }}"><span class="menu-dot"></span>Support Requests</a>
          @endif
          @if(auth()->user()->role === 'admin' && \Illuminate\Support\Facades\Route::has('rent-requests.index'))
            <a class="menu-link {{ request()->routeIs('rent-requests.*') ? 'active' : '' }}" href="{{ route('rent-requests.index') }}"><span class="menu-dot"></span>Rent Requests</a>
          @endif
          @if(auth()->user()->role === 'admin' && \Illuminate\Support\Facades\Route::has('availability-check.index'))
            <a class="menu-link {{ request()->routeIs('availability-check.*') ? 'active' : '' }}" href="{{ route('availability-check.index') }}"><span class="menu-dot"></span>Availability Check</a>
          @endif
        </div>

        @auth
          <div class="sidebar-user">
            <a href="{{ route('profile.edit') }}" class="text-decoration-none">
              <strong>{{ auth()->user()->name }}</strong>
              <span>{{ auth()->user()->email }}</span>
            </a>
          </div>
        @endauth
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
