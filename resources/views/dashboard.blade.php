@extends('layouts.app')
@section('title', 'R&A Auto Rentals')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
  .dashboard-page {
    --dash-border: #dbe4ef;
    --dash-muted: #64748b;
    --dash-text: #0f172a;
    --dash-head-bg: #f8fbff;
    --dash-row-alt: #f8fbff;
    --dash-green: #059669;
    --dash-green-bg: #dcfce7;
    --dash-red: #dc2626;
    --dash-red-bg: #fee2e2;
  }

  .dashboard-page h4,
  .dashboard-page h5 {
    font-weight: 700;
    color: var(--dash-text);
  }

  .dashboard-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    margin-bottom: 1rem;
  }

  .dashboard-toolbar-left {
    display: flex;
    align-items: center;
    gap: .9rem;
    flex-wrap: wrap;
  }

  .dashboard-toolbar-right {
    display: flex;
    align-items: center;
    gap: .55rem;
    flex-wrap: wrap;
  }

  .dashboard-page .kpi-card {
    background: #fff;
    border: 1px solid var(--dash-border);
    border-radius: .9rem;
    box-shadow: 0 5px 14px rgba(15, 23, 42, 0.04) !important;
  }

  .dashboard-page .kpi-label {
    color: var(--dash-muted) !important;
    font-weight: 600;
    margin-bottom: .4rem;
  }

  .dashboard-page .kpi-value {
    color: var(--dash-text);
    font-weight: 800;
    font-size: 2rem;
    line-height: 1;
  }

  .dashboard-page .delta {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 54px;
    padding: .22rem .52rem;
    border-radius: .45rem;
    font-size: .85rem;
    font-weight: 700;
  }

  .dashboard-page .delta.up {
    color: var(--dash-green);
    background: var(--dash-green-bg);
  }

  .dashboard-page .delta.down {
    color: var(--dash-red);
    background: var(--dash-red-bg);
  }

  /* Summary cards */
  .dashboard-page .kpi-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem; }
  .dashboard-page .kpi2 { --tone: #0f66c3; --tone-soft: #e8efff; position: relative; overflow: hidden; background: #fff; border: 1px solid var(--dash-border); border-radius: 1rem; padding: 1.05rem 1.15rem 1rem; box-shadow: 0 5px 14px rgba(15, 23, 42, 0.04); transition: transform .15s ease, box-shadow .15s ease; }
  .dashboard-page .kpi2::before { content: ""; position: absolute; inset: 0 0 auto 0; height: 4px; background: var(--tone); }
  .dashboard-page .kpi2:hover { transform: translateY(-2px); box-shadow: 0 12px 26px rgba(10, 63, 143, 0.10); }
  .dashboard-page .kpi2.tone-blue { --tone: #0f66c3; --tone-soft: #e8efff; }
  .dashboard-page .kpi2.tone-green { --tone: #16a34a; --tone-soft: #dcfce7; }
  .dashboard-page .kpi2.tone-orange { --tone: #ea580c; --tone-soft: #ffedd5; }
  .dashboard-page .kpi2.tone-violet { --tone: #6d28d9; --tone-soft: #ede9fe; }
  .dashboard-page .kpi2-top { display: flex; align-items: center; gap: .6rem; }
  .dashboard-page .kpi2-icon { flex: none; width: 40px; height: 40px; border-radius: 12px; display: grid; place-items: center; font-size: 1.15rem; color: var(--tone); background: var(--tone-soft); }
  .dashboard-page .kpi2-label { font-weight: 700; color: var(--dash-muted); font-size: .92rem; flex: 1; min-width: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .dashboard-page .kpi2-mid { display: flex; align-items: center; justify-content: space-between; gap: .5rem; margin-top: .7rem; }
  .dashboard-page .kpi2-mid .kpi2-value { margin-top: 0; min-width: 0; }
  .dashboard-page .kpi2-pill { flex: none; display: inline-flex; align-items: center; gap: .2rem; font-size: .75rem; font-weight: 800; padding: .2rem .55rem; border-radius: 999px; white-space: nowrap; }
  .dashboard-page .kpi2-pill.good { background: #dcfce7; color: #166534; }
  .dashboard-page .kpi2-pill.warn { background: #fef3c7; color: #92400e; }
  .dashboard-page .kpi2-pill.bad { background: #fee2e2; color: #991b1b; }
  .dashboard-page .kpi2-value { font-weight: 800; font-size: 1.85rem; line-height: 1.1; color: var(--dash-text); margin-top: .7rem; font-variant-numeric: tabular-nums; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .dashboard-page .kpi2-progress { height: 6px; border-radius: 3px; background: var(--tone-soft); margin-top: .65rem; overflow: hidden; }
  .dashboard-page .kpi2-progress i { display: block; height: 100%; border-radius: 3px; background: var(--tone); }
  .dashboard-page .kpi2-foot { font-size: .82rem; color: var(--dash-muted); margin-top: .45rem; }
  @media (max-width: 1199px) { .dashboard-page .kpi-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
  @media (max-width: 575px) { .dashboard-page .kpi-grid { grid-template-columns: 1fr; } }
  @media (prefers-reduced-motion: reduce) { .dashboard-page .kpi2 { transition: none; } .dashboard-page .kpi2:hover { transform: none; } }

  /* Profit / Loss */
  .dashboard-page .pl-note { font-size: .82rem; color: var(--dash-muted); font-weight: 500; }
  .dashboard-page .pl-grid { display: grid; grid-template-columns: minmax(0, 1.3fr) minmax(0, 1fr) minmax(0, 1fr); gap: 1rem; }
  .dashboard-page .pl-result { border-radius: .8rem; padding: 1rem 1.1rem; border: 1px solid; }
  .dashboard-page .pl-result.is-profit { background: #f0fdf4; border-color: #bbf7d0; }
  .dashboard-page .pl-result.is-loss { background: #fef2f2; border-color: #fecaca; }
  .dashboard-page .pl-label { font-weight: 700; font-size: .9rem; display: flex; align-items: center; gap: .4rem; }
  .dashboard-page .is-profit .pl-label, .dashboard-page .is-profit .pl-value { color: #15803d; }
  .dashboard-page .is-loss .pl-label, .dashboard-page .is-loss .pl-value { color: #b91c1c; }
  .dashboard-page .pl-value { font-weight: 800; font-size: 2.1rem; line-height: 1.1; margin-top: .2rem; }
  .dashboard-page .pl-sub { font-size: .85rem; color: var(--dash-muted); margin-top: .25rem; }
  .dashboard-page .pl-up { color: #15803d; font-weight: 700; }
  .dashboard-page .pl-down { color: #b91c1c; font-weight: 700; }
  .dashboard-page .pl-bars { margin-top: .8rem; display: grid; gap: .4rem; }
  .dashboard-page .pl-bar-row { display: grid; grid-template-columns: 52px 1fr auto; gap: .55rem; align-items: center; font-size: .82rem; color: var(--dash-muted); }
  .dashboard-page .pl-bar-row b { color: var(--dash-text); font-variant-numeric: tabular-nums; }
  .dashboard-page .pl-bar { height: 8px; border-radius: 4px; background: rgba(15, 23, 42, .07); overflow: hidden; }
  .dashboard-page .pl-bar i { display: block; height: 100%; border-radius: 4px; }
  .dashboard-page .pl-bar i.in { background: #16a34a; }
  .dashboard-page .pl-bar i.out { background: #dc2626; }
  .dashboard-page .pl-breakdown { border: 1px solid var(--dash-border); border-radius: .8rem; padding: .85rem 1rem; }
  .dashboard-page .pl-bd-head { display: flex; justify-content: space-between; font-weight: 700; padding-bottom: .5rem; margin-bottom: .3rem; border-bottom: 1px solid var(--dash-border); }
  .dashboard-page .pl-line { display: flex; justify-content: space-between; gap: .5rem; padding: .32rem 0; font-size: .9rem; font-variant-numeric: tabular-nums; }
  .dashboard-page .pl-line.is-zero { color: var(--dash-muted); }
  .dashboard-page .pl-result.is-even, .dashboard-page .pl-result.is-none { background: #f8fafc; border-color: var(--dash-border); }
  .dashboard-page .is-even .pl-label, .dashboard-page .is-even .pl-value,
  .dashboard-page .is-none .pl-label, .dashboard-page .is-none .pl-value { color: #475569; }

  /* Profit by vehicle */
  .dashboard-page .vp-chips { display: flex; gap: .4rem; flex-wrap: wrap; }
  .dashboard-page .vp-chip { display: inline-flex; align-items: center; gap: .3rem; font-size: .8rem; font-weight: 700; padding: .22rem .6rem; border-radius: 999px; }
  .dashboard-page .vp-chip.good { background: #dcfce7; color: #166534; }
  .dashboard-page .vp-chip.bad { background: #fee2e2; color: #991b1b; }
  .dashboard-page .vp-chip.idle { background: #f1f5f9; color: #475569; }
  .dashboard-page .vp-highlights { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: .75rem; padding: 1rem 1rem .25rem; }
  .dashboard-page .vp-hl { display: flex; gap: .7rem; align-items: center; padding: .7rem .9rem; border-radius: .7rem; font-size: .9rem; }
  .dashboard-page .vp-hl i { font-size: 1.3rem; }
  .dashboard-page .vp-hl span { display: block; font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; opacity: .8; }
  .dashboard-page .vp-hl.good { background: #f0fdf4; color: #166534; }
  .dashboard-page .vp-hl.bad { background: #fef2f2; color: #991b1b; }
  .dashboard-page .vp-table th { font-size: .78rem; text-transform: uppercase; letter-spacing: .03em; color: var(--dash-muted); font-weight: 700; white-space: nowrap; }
  .dashboard-page .vp-table td { vertical-align: middle; }
  .dashboard-page .vp-table tfoot td { font-weight: 800; border-bottom: 0; }
  .dashboard-page .vp-name { font-weight: 700; }
  .dashboard-page .vp-plate, .dashboard-page .vp-parts { font-size: .78rem; color: var(--dash-muted); }
  .dashboard-page .vp-amt { font-variant-numeric: tabular-nums; font-weight: 600; }
  .dashboard-page .vp-net { font-weight: 800; font-variant-numeric: tabular-nums; }
  .dashboard-page .vp-net.good { color: #15803d; }
  .dashboard-page .vp-net.bad { color: #b91c1c; }
  .dashboard-page .vp-bar-col { width: 24%; min-width: 160px; }
  .dashboard-page .vp-axis { display: flex; justify-content: space-between; }
  .dashboard-page .vp-axis span { font-size: .72rem; }
  .dashboard-page .vp-table .vp-num-col { width: 17%; }
  .dashboard-page .vp-div { position: relative; height: 12px; background: #f1f5f9; border-radius: 6px; }
  .dashboard-page .vp-div .vp-mid { position: absolute; left: 50%; top: -3px; bottom: -3px; width: 1px; background: #94a3b8; }
  .dashboard-page .vp-div i { position: absolute; top: 0; bottom: 0; }
  .dashboard-page .vp-div i.good { background: #16a34a; border-radius: 0 6px 6px 0; }
  .dashboard-page .vp-div i.bad { background: #dc2626; border-radius: 6px 0 0 6px; }
  .dashboard-page .vp-empty { padding: 1.5rem 1rem; text-align: center; color: var(--dash-muted); }
  .dashboard-page .vp-idle { padding: .7rem 1rem; border-top: 1px solid var(--dash-border); font-size: .85rem; color: var(--dash-muted); }

  @media (max-width: 1199px) { .dashboard-page .pl-grid { grid-template-columns: 1fr 1fr; } .dashboard-page .pl-result { grid-column: 1 / -1; } }
  @media (max-width: 575px) { .dashboard-page .pl-grid { grid-template-columns: 1fr; } }

  .dashboard-page .kpi-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .65rem;
  }

  .dashboard-page .panel-card {
    background: #fff;
    border: 1px solid var(--dash-border);
    border-radius: .9rem;
    box-shadow: 0 5px 14px rgba(15, 23, 42, 0.04) !important;
  }

  .dashboard-page .panel-card .card-header {
    background: #fff;
    border-bottom: 1px solid var(--dash-border);
    color: var(--dash-text);
    font-weight: 700;
    padding: 1rem 1.1rem;
  }

  .dashboard-page .panel-link {
    color: #0f66c3;
    text-decoration: none;
    font-weight: 600;
    font-size: .95rem;
  }

  .dashboard-page .panel-card .table thead th {
    background: var(--dash-head-bg);
    color: #64748b;
    font-weight: 700;
    font-size: .82rem;
    text-transform: uppercase;
    letter-spacing: .04em;
  }

  .dashboard-page .panel-card .table td {
    background: #fff;
    border-color: var(--dash-border);
    color: #334155;
    font-weight: 500;
    padding-top: .9rem;
    padding-bottom: .9rem;
  }

  .dashboard-page .panel-card .table td.text-end {
    color: var(--dash-text);
    font-weight: 600;
  }

  .dashboard-page .panel-card .table-striped > tbody > tr:nth-of-type(odd) > * {
    background: var(--dash-row-alt);
  }

  @media (max-width: 991.98px) {
    .dashboard-page {
      max-width: 100%;
      overflow-x: hidden;
    }

    .app-main.app-main-col {
      overflow-x: hidden;
    }

    .dashboard-toolbar {
      align-items: flex-start;
      flex-direction: column;
    }

    .dashboard-toolbar-right {
      width: 100%;
    }

    /* Override shared layout mobile rule that forces nowrap table scrolling */
    .dashboard-page .panel-card .table {
      display: table !important;
      width: 100% !important;
      min-width: 0 !important;
      white-space: normal !important;
      overflow: visible !important;
    }

    .dashboard-page .panel-card .table-responsive {
      overflow-x: hidden !important;
      -webkit-overflow-scrolling: auto;
    }
  }

  @media (max-width: 767.98px) {
    .dashboard-page {
      max-width: 100%;
      overflow-x: hidden;
    }

    .dashboard-page .row {
      margin-left: 0;
      margin-right: 0;
    }

    .dashboard-page .row > [class*="col-"] {
      padding-left: .35rem;
      padding-right: .35rem;
    }

    .dashboard-toolbar {
      gap: .6rem;
      margin-bottom: .8rem;
    }

    .dashboard-toolbar-left,
    .dashboard-toolbar-right {
      width: 100%;
    }

    .dashboard-toolbar-left h4 {
      width: 100%;
      margin-bottom: .1rem !important;
      font-size: 1.95rem;
    }

    .dashboard-toolbar-right {
      justify-content: flex-start;
      gap: .5rem;
    }

    .dashboard-toolbar-right form {
      width: 100%;
      display: grid !important;
      grid-template-columns: minmax(0, 1fr) auto;
      gap: .5rem !important;
    }

    .dashboard-toolbar-right form .form-control,
    .dashboard-toolbar-right form .btn,
    .dashboard-toolbar-right > .btn {
      min-height: 42px;
    }

    .dashboard-toolbar-right > .btn {
      width: auto;
      max-width: 100%;
    }

    .dashboard-page .kpi-value {
      font-size: 1.9rem;
    }

    .dashboard-page .panel-card .card-header {
      padding: .85rem .9rem;
      flex-wrap: wrap;
      gap: .35rem;
      align-items: flex-start !important;
    }

    .dashboard-page .panel-card {
      overflow: hidden;
    }

    .dashboard-page .panel-card .table-responsive {
      overflow: visible;
    }

    .dashboard-page .panel-card .table {
      white-space: normal !important;
      overflow: visible !important;
    }

    .dashboard-page .dash-table,
    .dashboard-page .dash-table thead,
    .dashboard-page .dash-table tbody,
    .dashboard-page .dash-table th,
    .dashboard-page .dash-table td,
    .dashboard-page .dash-table tr {
      display: block;
      width: 100%;
    }

    .dashboard-page .dash-table thead {
      display: none;
    }

    .dashboard-page .dash-table tbody tr {
      border-top: 1px solid var(--dash-border);
      padding: .25rem 0;
    }

    .dashboard-page .dash-table tbody td {
      position: relative;
      border: 0;
      border-top: 1px solid #edf3fb;
      padding: .58rem .8rem .58rem 42%;
      min-height: 42px;
      box-sizing: border-box;
      max-width: 100%;
      word-break: break-word;
      overflow-wrap: anywhere;
    }

    .dashboard-page .dash-table tbody td:first-child {
      border-top: 0;
    }

    .dashboard-page .dash-table tbody td::before {
      content: attr(data-label);
      position: absolute;
      left: .8rem;
      top: .58rem;
      width: calc(42% - 1rem);
      color: #64748b;
      font-size: .72rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: .05em;
    }

    .dashboard-page .dash-table tbody td.text-end {
      text-align: left !important;
    }

    .dashboard-page .dash-table tbody td.no-data {
      padding: .95rem .85rem !important;
      text-align: center !important;
    }

    .dashboard-page .dash-table tbody td.no-data::before {
      display: none;
    }
  }

  /* iOS Safari-specific overflow guard */
  @supports (-webkit-touch-callout: none) {
    @media (max-width: 767.98px) {
      .dashboard-page {
        overflow-x: clip;
      }

      .dashboard-page .panel-card .table {
        white-space: normal !important;
      }

      .dashboard-page .dashboard-toolbar-right form {
        grid-template-columns: minmax(0, 1fr) !important;
      }
    }

    @media (max-width: 991.98px) {
      html, body {
        overflow-x: hidden !important;
      }
    }
  }
</style>
<div class="dashboard-page">
<div class="dashboard-toolbar">
  <div class="dashboard-toolbar-left">
    <h4 class="mb-0">Overview</h4>
  </div>
  <div class="dashboard-toolbar-right">
    <form class="d-flex gap-2" method="get" action="{{ route('dashboard') }}">
      <input type="month" class="form-control" name="month" value="{{ $month }}">
      <button class="btn btn-dark">Filter</button>
    </form>
    @if(auth()->user()->isDashboardAdmin())
      <a class="btn btn-outline-primary d-inline-flex align-items-center gap-1" href="{{ route('dashboard.report', ['month' => $month]) }}" title="Income, expenses and profit or loss for {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}">
        <i class="bi bi-file-earmark-bar-graph" aria-hidden="true"></i> Monthly report
      </a>
    @endif
    @if(auth()->user()->canManageData() && auth()->user()->canAccess('payments'))
      <a class="btn btn-dark" href="{{ route('payments.index') }}">+ Add Payment</a>
    @endif
  </div>
</div>

@php
  $kpiMonthLabel = \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y');
  $prevMonthShort = \Carbon\Carbon::createFromFormat('Y-m', $month)->subMonth()->format('M Y');
  $collectedPct = $expected > 0 ? min(100, $received / $expected * 100) : null;
  $stillDue = max($expected - $received, 0);
  $expenseChange = $monthExpenses - $kpi['previousExpenses'];
  $expenseChangePct = $kpi['previousExpenses'] > 0 ? abs($expenseChange) / $kpi['previousExpenses'] * 100 : null;
@endphp

<div class="kpi-grid mb-4">
  {{-- Expected payments --}}
  <div class="kpi2 tone-blue">
    <div class="kpi2-top">
      <span class="kpi2-icon"><i class="bi bi-calendar2-check" aria-hidden="true"></i></span>
      <span class="kpi2-label">Expected Payments</span>
    </div>
    <div class="kpi2-mid">
      <div class="kpi2-value">Rs {{ number_format($expected, 0) }}</div>
      
    </div>
    <div class="kpi2-foot">
      @if($kpi['paymentCount'])
        {{ $kpi['paymentCount'] }} {{ $kpi['paymentCount'] === 1 ? 'payment' : 'payments' }} due in {{ $kpiMonthLabel }}
      @else
        No payments scheduled for {{ $kpiMonthLabel }}
      @endif
    </div>
  </div>

  {{-- Received payments, with collection progress --}}
  <div class="kpi2 tone-green">
    <div class="kpi2-top">
      <span class="kpi2-icon"><i class="bi bi-cash-coin" aria-hidden="true"></i></span>
      <span class="kpi2-label">Received Payments</span>
    </div>
    <div class="kpi2-mid">
      <div class="kpi2-value">Rs {{ number_format($received, 0) }}</div>
      @if($collectedPct !== null)
        <span class="kpi2-pill {{ $collectedPct >= 100 ? 'good' : ($collectedPct >= 50 ? 'warn' : 'bad') }}" title="Share of expected payments received">{{ round($collectedPct) }}%</span>
      @endif
    </div>
    @if($collectedPct !== null)
      <div class="kpi2-progress" role="progressbar" aria-label="Collected" aria-valuenow="{{ round($collectedPct) }}" aria-valuemin="0" aria-valuemax="100"><i style="width:{{ $collectedPct }}%"></i></div>
      <div class="kpi2-foot">{{ $stillDue > 0 ? 'Rs ' . number_format($stillDue, 0) . ' still to collect' : 'All expected payments received' }}</div>
    @else
      <div class="kpi2-foot">Nothing to collect this month</div>
    @endif
  </div>

  {{-- Expenses, compared with last month --}}
  <div class="kpi2 tone-orange">
    <div class="kpi2-top">
      <span class="kpi2-icon"><i class="bi bi-receipt" aria-hidden="true"></i></span>
      <span class="kpi2-label">Expenses</span>
    </div>
    <div class="kpi2-mid">
      <div class="kpi2-value">Rs {{ number_format($monthExpenses, 0) }}</div>
      @if($expenseChange != 0 && $expenseChangePct !== null)
        {{-- Spending more is bad, spending less is good --}}
        <span class="kpi2-pill {{ $expenseChange > 0 ? 'bad' : 'good' }}"><i class="bi bi-arrow-{{ $expenseChange > 0 ? 'up' : 'down' }}" aria-hidden="true"></i> {{ round($expenseChangePct) }}%</span>
      @endif
    </div>
    <div class="kpi2-foot">
      @if($expenseChange == 0)
        Same as {{ $prevMonthShort }}{{ $kpi['expenseCount'] ? ' · ' . $kpi['expenseCount'] . ' ' . ($kpi['expenseCount'] === 1 ? 'entry' : 'entries') : '' }}
      @else
        {{ $expenseChange > 0 ? 'Rs ' . number_format($expenseChange, 0) . ' more' : 'Rs ' . number_format(abs($expenseChange), 0) . ' less' }} than {{ $prevMonthShort }}
      @endif
    </div>
  </div>

  {{-- Pending payments to follow up --}}
  <div class="kpi2 tone-violet">
    <div class="kpi2-top">
      <span class="kpi2-icon"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span>
      <span class="kpi2-label">Pending Tasks</span>
    </div>
    <div class="kpi2-mid">
      <div class="kpi2-value">{{ number_format($pendingPaymentsCount) }}</div>
      @if($kpi['overdueCount'])
        <span class="kpi2-pill bad">{{ $kpi['overdueCount'] }} overdue</span>
      @endif
    </div>
    <div class="kpi2-foot">
      @if($pendingPaymentsCount)
        {{ $pendingPaymentsCount === 1 ? 'Payment' : 'Payments' }} to follow up · Rs {{ number_format($upcomingPaymentsTotal, 0) }}
      @else
        All payments are up to date
      @endif
    </div>
  </div>
</div>

@if(auth()->user()->isDashboardAdmin())
  @php
    $pl = $profit;
    $isProfit = $pl['net'] >= 0;
    // 'profit' | 'loss' | 'even' (income equals costs) | 'none' (no money moved this month)
    $plState = !$pl['hasActivity'] ? 'none' : ($pl['net'] > 0 ? 'profit' : ($pl['net'] < 0 ? 'loss' : 'even'));
    $plStateLabel = ['profit' => 'Profit', 'loss' => 'Loss', 'even' => 'Break-even', 'none' => 'No activity yet'][$plState];
    $plStateIcon = ['profit' => 'bi-graph-up-arrow', 'loss' => 'bi-graph-down-arrow', 'even' => 'bi-dash-circle', 'none' => 'bi-hourglass-split'][$plState];
    $vehicleRows = $pl['vehicles'];
    $activeVehicles = $vehicleRows->where('active', true);
    $maxAbsNet = max($activeVehicles->max(fn ($r) => abs($r['net'])) ?? 0, 1);
    $plMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y');
    $prevLabel = \Carbon\Carbon::createFromFormat('Y-m', $month)->subMonth()->format('M Y');
    $plChange = $pl['net'] - $previousProfit['net'];
    $plMax = max($pl['income'], $pl['costs'], 1);
  @endphp
  <div class="card panel-card pl-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
      <span>Profit / Loss — {{ $plMonth }}</span>
      <span class="pl-note">Money received minus money spent this month</span>
    </div>
    <div class="card-body">
      <div class="pl-grid">
        <div class="pl-result is-{{ $plState }}">
          <div class="pl-label"><i class="bi {{ $plStateIcon }}" aria-hidden="true"></i> {{ $plStateLabel }}</div>
          <div class="pl-value">{{ $pl['net'] < 0 ? '−' : '' }}Rs {{ number_format(abs($pl['net']), 0) }}</div>
          @if($plState === 'none')
            <div class="pl-sub">No payments, trips or expenses recorded for {{ $plMonth }}.</div>
          @endif
          <div class="pl-sub">
            @if($pl['margin'] !== null) {{ round($pl['margin']) }}% margin · @endif
            @if($plChange == 0)
              Same as {{ $prevLabel }}
            @else
              <span class="{{ $plChange > 0 ? 'pl-up' : 'pl-down' }}">{{ $plChange > 0 ? '▲' : '▼' }} Rs {{ number_format(abs($plChange), 0) }}</span> vs {{ $prevLabel }}
            @endif
          </div>
          @if($plState !== 'none')
            <div class="pl-bars" aria-hidden="true">
              <div class="pl-bar-row"><span>Income</span><div class="pl-bar"><i class="in" style="width:{{ $pl['income'] / $plMax * 100 }}%"></i></div><b>Rs {{ number_format($pl['income'], 0) }}</b></div>
              <div class="pl-bar-row"><span>Costs</span><div class="pl-bar"><i class="out" style="width:{{ $pl['costs'] / $plMax * 100 }}%"></i></div><b>Rs {{ number_format($pl['costs'], 0) }}</b></div>
            </div>
          @endif
        </div>
        @foreach(['income' => ['Income', 'Rs ' . number_format($pl['income'], 0)], 'costs' => ['Costs', 'Rs ' . number_format($pl['costs'], 0)]] as $side => [$title, $total])
          <div class="pl-breakdown">
            <div class="pl-bd-head"><span>{{ $title }}</span><b>{{ $total }}</b></div>
            @foreach($pl['lines'][$side] as $line)
              <div class="pl-line {{ $line['amount'] > 0 ? '' : 'is-zero' }}">
                <span>{{ $line['label'] }}</span>
                <span>Rs {{ number_format($line['amount'], 0) }}</span>
              </div>
            @endforeach
          </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- Profit or loss per vehicle for the same month; rows add up to the panel above. --}}
  @php
    $earning = $activeVehicles->where('net', '>', 0);
    $losing = $activeVehicles->where('net', '<', 0);
    $idleVehicles = $vehicleRows->where('active', false);
    $best = $earning->sortByDesc('net')->first();
    $worst = $losing->sortBy('net')->first();
    $partsText = function (array $parts, array $labels) {
        return collect($labels)->filter(fn ($label, $key) => $parts[$key] > 0)
            ->map(fn ($label, $key) => $label . ' Rs ' . number_format($parts[$key], 0))->implode(' · ');
    };
    $incomeLabels = ['rentals' => 'Long-term', 'trips' => 'Trips', 'extras' => 'Extra km'];
    $costLabels = ['expenses' => 'Expenses', 'partner' => 'Partner'];
  @endphp
  <div class="card panel-card vp-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
      <span>Profit by vehicle — {{ $plMonth }}</span>
      <div class="vp-chips">
        <span class="vp-chip good"><i class="bi bi-arrow-up-right" aria-hidden="true"></i> {{ $earning->count() }} making profit</span>
        <span class="vp-chip bad"><i class="bi bi-arrow-down-right" aria-hidden="true"></i> {{ $losing->count() }} losing money</span>
        <span class="vp-chip idle"><i class="bi bi-pause-circle" aria-hidden="true"></i> {{ $idleVehicles->count() }} no activity</span>
      </div>
    </div>
    <div class="card-body p-0">
      @if($activeVehicles->isEmpty())
        <div class="vp-empty">No income or costs recorded for any vehicle in {{ $plMonth }}.</div>
      @else
        @if($best || $worst)
          <div class="vp-highlights">
            @if($best)
              <div class="vp-hl good"><i class="bi bi-trophy" aria-hidden="true"></i><div><span>Best earner</span><b>{{ $best['name'] }}</b> made Rs {{ number_format($best['net'], 0) }}</div></div>
            @endif
            @if($worst)
              <div class="vp-hl bad"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i><div><span>Costing the most</span><b>{{ $worst['name'] }}</b> lost Rs {{ number_format(abs($worst['net']), 0) }}</div></div>
            @endif
          </div>
        @endif
        <div class="table-responsive">
          <table class="table vp-table mb-0">
            <thead>
              <tr><th>Vehicle</th><th class="text-end vp-num-col">Income</th><th class="text-end vp-num-col">Costs</th><th class="text-end vp-num-col">Profit / Loss</th><th class="vp-bar-col"><div class="vp-axis"><span>Loss</span><span>Profit</span></div></th></tr>
            </thead>
            <tbody>
              @foreach($activeVehicles as $row)
                @php $w = abs($row['net']) / $maxAbsNet * 50; @endphp
                <tr>
                  <td>
                    <div class="vp-name">{{ $row['name'] }}</div>
                    @if($row['plate'] && $row['plate'] !== $row['name'])<div class="vp-plate">{{ $row['plate'] }}</div>@endif
                  </td>
                  <td class="text-end">
                    <div class="vp-amt">Rs {{ number_format($row['income'], 0) }}</div>
                    <div class="vp-parts">{{ $partsText($row['parts'], $incomeLabels) ?: '–' }}</div>
                  </td>
                  <td class="text-end">
                    <div class="vp-amt">Rs {{ number_format($row['costs'], 0) }}</div>
                    <div class="vp-parts">{{ $partsText($row['parts'], $costLabels) ?: '–' }}</div>
                  </td>
                  <td class="text-end">
                    <div class="vp-net {{ $row['net'] > 0 ? 'good' : ($row['net'] < 0 ? 'bad' : '') }}">
                      {{ $row['net'] > 0 ? '+' : ($row['net'] < 0 ? '−' : '') }}Rs {{ number_format(abs($row['net']), 0) }}
                    </div>
                    <div class="vp-parts">{{ $row['net'] > 0 ? 'Profit' : ($row['net'] < 0 ? 'Loss' : 'Break-even') }}@if($row['net'] > 0 && $row['margin'] !== null) · {{ round($row['margin']) }}% margin @elseif($row['net'] < 0 && $row['income'] > 0) · costs {{ number_format($row['costs'] / $row['income'], 1) }}× income @elseif($row['net'] < 0) · no income @endif</div>
                  </td>
                  <td class="vp-bar-col">
                    <div class="vp-div" title="{{ $row['net'] >= 0 ? 'Profit' : 'Loss' }} Rs {{ number_format(abs($row['net']), 0) }}">
                      <span class="vp-mid"></span>
                      @if($row['net'] > 0)<i class="good" style="left:50%; width:{{ $w }}%"></i>@elseif($row['net'] < 0)<i class="bad" style="right:50%; width:{{ $w }}%"></i>@endif
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td>All vehicles</td>
                <td class="text-end">Rs {{ number_format($pl['income'], 0) }}</td>
                <td class="text-end">Rs {{ number_format($pl['costs'], 0) }}</td>
                <td class="text-end"><span class="vp-net {{ $pl['net'] > 0 ? 'good' : ($pl['net'] < 0 ? 'bad' : '') }}">{{ $pl['net'] > 0 ? '+' : ($pl['net'] < 0 ? '−' : '') }}Rs {{ number_format(abs($pl['net']), 0) }}</span></td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>
      @endif
      @if($idleVehicles->isNotEmpty() && $activeVehicles->isNotEmpty())
        <div class="vp-idle"><i class="bi bi-pause-circle" aria-hidden="true"></i> No income or costs in {{ $plMonth }}: {{ $idleVehicles->map(fn ($v) => $v['plate'] && $v['plate'] !== $v['name'] ? $v['name'] . ' (' . $v['plate'] . ')' : $v['name'])->implode(', ') }}</div>
      @endif
    </div>
  </div>
@endif

<div class="row g-3">
  <div class="col-12">
    <div class="card panel-card">
      <div class="card-header d-flex justify-content-between align-items-center">
        @php $renewalMonthLabel = \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y'); @endphp
        @if($renewalsForCurrent)
          <span>Renewals due in the next 30 days <small class="text-muted fw-normal">(until {{ $renewalWindowEnd->format('j M Y') }}, including expired)</small></span>
        @else
          <span>Renewals due in {{ $renewalMonthLabel }} <small class="text-muted fw-normal">(days counted from {{ $renewalCountFrom->format('j M Y') }})</small></span>
        @endif
        @if(auth()->user()->canAccess('vehicles'))
          <a class="panel-link" href="{{ route('vehicles.index') }}">Manage Vehicles</a>
        @endif
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0 align-middle dash-table">
            <thead>
              <tr>
                <th>Vehicle</th>
                <th>Renewal Type</th>
                <th>Renewal Date</th>
                <th>Status</th>
                @if(auth()->user()->canManageData())
                  <th>Action</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @forelse($renewalAlerts as $alert)
                <tr>
                  <td data-label="Vehicle">{{ $alert['vehicle']->name }}{{ $alert['vehicle']->plate_no ? ' (' . $alert['vehicle']->plate_no . ')' : '' }}</td>
                  <td data-label="Renewal Type">{{ $alert['type'] }}</td>
                  <td data-label="Renewal Date">{{ $alert['date']->format('Y-m-d') }}</td>
                  <td data-label="Status">
                    @php $d = $alert['days_left']; @endphp
                    @if($d < 0)
                      <span class="badge text-bg-danger">Expired {{ abs($d) }} day{{ abs($d) === 1 ? '' : 's' }} ago</span>
                    @elseif($d === 0)
                      <span class="badge text-bg-danger">Due today</span>
                    @elseif($d <= 7)
                      <span class="badge text-bg-danger">Due in {{ $d }} day{{ $d === 1 ? '' : 's' }}</span>
                    @else
                      <span class="badge text-bg-warning">Due in {{ $d }} days</span>
                    @endif
                  </td>
                  @if(auth()->user()->canManageData())
                    <td data-label="Action">
                      @if(auth()->user()->canAccess('vehicles'))
                        <button
                          type="button"
                          class="btn btn-sm btn-outline-dark"
                          data-bs-toggle="modal"
                          data-bs-target="#renewalUpdateModal"
                          data-renewal-url="{{ route('vehicles.renewal.update', $alert['vehicle']) }}"
                          data-renewal-car="{{ $alert['vehicle']->name }}{{ $alert['vehicle']->plate_no ? ' (' . $alert['vehicle']->plate_no . ')' : '' }}"
                          data-renewal-type="{{ strtolower($alert['type']) }}"
                          data-renewal-date="{{ $alert['date']->format('Y-m-d') }}"
                        >
                          Renew
                        </button>
                      @endif
                    </td>
                  @endif
                </tr>
              @empty
                <tr><td colspan="{{ auth()->user()->canManageData() ? 5 : 4 }}" class="text-center p-4 text-muted no-data">{{ $renewalsForCurrent ? 'No insurance or license renewals due in the next 30 days.' : 'No insurance or license renewals due in ' . $renewalMonthLabel . '.' }}</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-xl-7">
    <div class="card panel-card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Payments For {{ $month }}</span>
        @if(auth()->user()->canAccess('payments'))
          <a class="panel-link" href="{{ route('payments.index') }}">View All</a>
        @endif
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0 align-middle dash-table">
            <thead>
              <tr>
                <th>Due Date</th>
                <th>Vehicle</th>
                <th>Customer</th>
                <th>Month</th>
                <th class="text-end">Amount</th>
              </tr>
            </thead>
            <tbody>
              @forelse($upcomingPayments as $payment)
                <tr>
                  <td data-label="Due Date">{{ $payment->due_date->format('Y-m-d') }}</td>
                  <td data-label="Vehicle">{{ $payment->rental->vehicle->name ?? '-' }}</td>
                  <td data-label="Customer">{{ $payment->rental->customer->name ?? '-' }}</td>
                  <td data-label="Month">{{ $payment->month }}</td>
                  <td data-label="Amount" class="text-end">Rs {{ number_format($payment->amount, 2) }}</td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center p-4 text-muted no-data">No pending payments for {{ $month }}.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-xl-5">
    <div class="card panel-card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Expenses For {{ $month }}</span>
        @if(auth()->user()->canAccess('expenses'))
          <a class="panel-link" href="{{ route('expenses.index') }}">View All</a>
        @endif
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0 align-middle dash-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Vehicle</th>
                <th>Type</th>
                <th class="text-end">Amount</th>
              </tr>
            </thead>
            <tbody>
              @forelse($upcomingExpenses as $expense)
                <tr>
                  <td data-label="Date">{{ $expense->date->format('Y-m-d') }}</td>
                  <td data-label="Vehicle">{{ $expense->vehicle?->name ?? '-' }}</td>
                  <td data-label="Type">{{ ucfirst($expense->type) }}</td>
                  <td data-label="Amount" class="text-end">Rs {{ number_format($expense->amount, 2) }}</td>
                </tr>
              @empty
                <tr><td colspan="4" class="text-center p-4 text-muted no-data">No expenses for {{ $month }}.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@if(auth()->user()->canManageData())
<div class="modal fade" id="renewalUpdateModal" tabindex="-1" aria-labelledby="renewalUpdateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="renewalUpdateModalLabel">Update Renewal Date</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" id="renewalUpdateForm">
        @csrf
        @method('PATCH')
        <input type="hidden" name="renewal_type" id="renewal_type">
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Vehicle</label>
            <input type="text" class="form-control" id="renewal_car_name" readonly>
          </div>
          <div class="mb-2">
            <label class="form-label">Renewal Type</label>
            <input type="text" class="form-control" id="renewal_type_label" readonly>
          </div>
          <div class="mb-1">
            <label class="form-label">New Renewal Date</label>
            <input type="date" class="form-control" name="renewal_date" id="renewal_date" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-dark">Save Renewal Date</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

@if(auth()->user()->canManageData())
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const renewalModal = document.getElementById('renewalUpdateModal');
    const renewalForm = document.getElementById('renewalUpdateForm');
    const renewalCarName = document.getElementById('renewal_car_name');
    const renewalType = document.getElementById('renewal_type');
    const renewalTypeLabel = document.getElementById('renewal_type_label');
    const renewalDate = document.getElementById('renewal_date');

    if (!renewalModal || !renewalForm || !renewalCarName || !renewalType || !renewalTypeLabel || !renewalDate) {
      return;
    }

    renewalModal.addEventListener('show.bs.modal', function (event) {
      const trigger = event.relatedTarget;
      const formAction = trigger?.getAttribute('data-renewal-url') || '';
      const carName = trigger?.getAttribute('data-renewal-car') || '';
      const type = trigger?.getAttribute('data-renewal-type') || '';
      const date = trigger?.getAttribute('data-renewal-date') || '';

      renewalForm.setAttribute('action', formAction);
      renewalCarName.value = carName;
      renewalType.value = type;
      renewalTypeLabel.value = type ? type.charAt(0).toUpperCase() + type.slice(1) : '';
      renewalDate.value = date;
    });
  });
</script>
@endif

<script>
  // The month filter shows the chosen month, but a page refresh should go back to the current month,
  // so drop ?month= from the address bar once this page has loaded.
  (function () {
    const url = new URL(window.location.href);
    if (url.searchParams.has('month')) {
      url.searchParams.delete('month');
      window.history.replaceState(window.history.state, '', url.pathname + url.search + url.hash);
    }
  })();
</script>

</div>
@endsection
