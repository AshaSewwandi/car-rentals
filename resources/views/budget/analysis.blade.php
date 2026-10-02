@extends('layouts.app')
@section('title', 'Budget Analysis')

@section('content')
@php
  $rs = fn ($n) => ($n < 0 ? '−' : '') . 'Rs ' . number_format(abs(round($n)));
  $tagLabel = ['good' => 'Good', 'bad' => 'Alert', 'warn' => 'Watch', 'info' => 'Note'];
  $tagIcon = ['good' => 'check-circle-fill', 'bad' => 'exclamation-octagon-fill', 'warn' => 'exclamation-triangle-fill', 'info' => 'info-circle-fill'];
  // $higherIsGood: true = increase is good (income, left), false = increase is bad (spent), null = neutral (budget).
  $delta = function ($d, $higherIsGood) use ($rs) {
      if (!$d || $d['diff'] == 0) return '<span class="ba-delta flat">No change</span>';
      $up = $d['diff'] > 0;
      $cls = $higherIsGood === null ? 'flat' : (($up === $higherIsGood) ? 'good' : 'bad');
      $pct = $d['pct'] !== null ? ' (' . round(abs($d['pct'])) . '%)' : '';
      return '<span class="ba-delta ' . $cls . '"><i class="bi bi-arrow-' . ($up ? 'up' : 'down') . '-right" aria-hidden="true"></i> '
          . ($up ? 'Up ' : 'Down ') . e($rs(abs($d['diff']))) . $pct . '</span>';
  };
  $pillClass = ['Over budget' => 'over', 'Nearly used' => 'near', 'On track' => 'ok', 'Not used' => 'none', 'No budget' => 'none', 'Unbudgeted' => 'unb', 'Not in this month' => 'none'];
  $cmpLabel = $compare['monthLabel'] ?? null;
  $chartData = [
      'monthLabel' => $monthLabel,
      'compareLabel' => $cmpLabel,
      'budget' => $budget,
      'daily' => $daily,
      'compareDaily' => $compare['daily'] ?? null,
      'groups' => $groups->map(fn ($g) => ['name' => $g['name'], 'budget' => $g['budget'], 'spent' => $g['spent']])->values(),
      'compareGroups' => $comparison ? $comparison['groups']->map(fn ($g) => ['name' => $g['name'], 'spent' => $g['b']])->values() : null,
      'trend' => $trend->values(),
      'month' => $month,
  ];
@endphp
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
  .ba {
    --primary:#0a3f8f; --primary-deep:#072d6b; --accent:#0f66c3;
    --ink:#111827; --ink-2:#475569; --ink-3:#64748b; --line:#e3e9f2; --field:#f5f8fd; --card:#fff;
    /* Validated categorical slots (dataviz): 1 = this month, 2 = comparison month */
    --s1:#0f66c3; --s2:#eb6834; --s6:#008300;
    --good:#15803d; --bad:#b91c1c; --warn:#b45309;
    color:var(--ink); max-width:1280px;
  }
  .ba h1, .ba h2 { font-family:"Space Grotesk",sans-serif; margin:0; letter-spacing:-.01em; }
  .ba-top { display:flex; justify-content:space-between; align-items:flex-end; gap:14px; flex-wrap:wrap; }
  .ba-top h1 { font-size:clamp(24px,3.4vw,34px); font-weight:700; }
  .ba-top .sub { color:var(--ink-3); font-size:14px; margin-top:2px; }
  .ba-controls { display:flex; gap:10px; align-items:flex-end; flex-wrap:wrap; }
  .ba-controls label { display:block; font-size:12px; color:var(--ink-2); margin:0 0 3px 2px; font-weight:600; }
  .ba-controls select { border:1px solid var(--line); background:var(--card); border-radius:10px; padding:7px 30px 7px 10px; font-weight:600; }
  .ba-btn { display:inline-flex; align-items:center; gap:6px; border:1px solid var(--line); background:var(--card); padding:8px 14px; border-radius:10px; font-weight:600; font-size:14px; color:var(--ink); text-decoration:none; }
  .ba-btn:hover { border-color:var(--ink-3); color:var(--ink); }
  .ba-btn.primary { background:linear-gradient(135deg, var(--accent), var(--primary)); color:#fff; border-color:var(--primary); }
  .ba-btn.primary:hover { color:#fff; filter:brightness(1.08); }

  .ba-kpis { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:12px; margin-top:18px; }
  .ba-kpi { background:var(--card); border:1px solid var(--line); border-radius:16px; padding:14px 16px; }
  .ba-kpi .k { font-size:13px; color:var(--ink-2); font-weight:600; }
  .ba-kpi .v { font-family:"Space Grotesk",sans-serif; font-size:26px; font-weight:700; margin-top:2px; }
  .ba-kpi .c { font-size:12.5px; color:var(--ink-3); margin-top:4px; }
  .ba-delta { display:inline-flex; align-items:center; gap:3px; font-size:12.5px; font-weight:700; margin-top:6px; }
  .ba-delta.good { color:var(--good); } .ba-delta.bad { color:var(--bad); } .ba-delta.flat { color:var(--ink-3); font-weight:600; }

  .ba-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px; margin-top:16px; }
  .ba-panel { background:var(--card); border:1px solid var(--line); border-radius:18px; overflow:hidden; }
  .ba-panel.wide { grid-column:1 / -1; }
  .ba-ph { display:flex; justify-content:space-between; align-items:baseline; gap:10px; padding:14px 16px 0; flex-wrap:wrap; }
  .ba-ph h2 { font-size:17px; font-weight:700; }
  .ba-ph .note { font-size:12.5px; color:var(--ink-3); }
  .ba-chart { position:relative; padding:10px 14px 14px; }
  .ba-empty { padding:30px 16px; text-align:center; color:var(--ink-3); font-size:14px; }

  .ba-insights { list-style:none; margin:0; padding:6px 16px 12px; }
  .ba-insights li { display:flex; gap:10px; align-items:flex-start; padding:8px 0; border-bottom:1px solid var(--line); font-size:14px; }
  .ba-insights li:last-child { border-bottom:0; }
  .ba-tag { flex:none; display:inline-flex; align-items:center; gap:4px; width:74px; font-size:12px; font-weight:700; }
  .ba-tag.good { color:var(--good); } .ba-tag.bad { color:var(--bad); } .ba-tag.warn { color:var(--warn); } .ba-tag.info { color:var(--accent); }

  .ba-table { width:100%; border-collapse:collapse; font-size:13.5px; }
  .ba-table th { text-align:right; font-size:12px; color:var(--ink-2); font-weight:600; padding:10px 14px; border-bottom:1px solid var(--line); white-space:nowrap; background:var(--field); }
  .ba-table td { text-align:right; padding:9px 14px; border-bottom:1px solid var(--line); white-space:nowrap; font-variant-numeric:tabular-nums; }
  .ba-table th:first-child, .ba-table td:first-child { text-align:left; }
  .ba-table tr.grp td { background:var(--field); font-weight:700; color:var(--primary-deep); }
  .ba-table tfoot td { font-weight:700; border-bottom:0; }
  .ba-table .sw { display:inline-block; width:10px; height:10px; border-radius:3px; margin-right:6px; vertical-align:-1px; }
  .ba-tw { overflow-x:auto; }
  .ba-pill { font-size:11.5px; font-weight:700; padding:2px 8px; border-radius:999px; }
  .ba-pill.over { background:#fee2e2; color:#b91c1c; } .ba-pill.near { background:#fef3c7; color:#92400e; }
  .ba-pill.ok { background:#dcfce7; color:#166534; } .ba-pill.none { background:#f1f5f9; color:#64748b; } .ba-pill.unb { background:#ffedd5; color:#9a3412; }
  .ba-legend { display:flex; gap:14px; flex-wrap:wrap; font-size:12.5px; color:var(--ink-2); padding:8px 16px 0; }
  .ba-legend span { display:inline-flex; align-items:center; gap:6px; }
  .ba-legend i { display:inline-block; width:12px; height:12px; border-radius:3px; }
  .ba-legend i.line { height:2px; border-radius:1px; }
  .ba-legend i.dash { height:0; border-top:2px dashed #94a3b8; background:none !important; }

  @media (max-width: 1000px) { .ba-grid { grid-template-columns:1fr; } .ba-kpis { grid-template-columns:repeat(2,minmax(0,1fr)); } }
</style>

<div class="ba">
  <div class="ba-top">
    <div>
      <h1>Budget analysis</h1>
      <div class="sub">{{ $monthLabel }}@if($cmpLabel) compared with {{ $cmpLabel }}@endif · @if($state === 'current') day {{ $daysElapsed }} of {{ $daysInMonth }} @elseif($state === 'past') completed month @else upcoming month @endif</div>
    </div>
    <form class="ba-controls" method="get" action="{{ route('budget.analysis') }}" id="baForm">
      <div>
        <label for="baMonth">Month</label>
        <select id="baMonth" name="month">
          @foreach($months as $m)
            <option value="{{ $m['value'] }}" @selected($m['value'] === $month)>{{ $m['label'] }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label for="baCompare">Compare with</label>
        <select id="baCompare" name="compare">
          <option value="none" @selected(!$compare)>No comparison</option>
          @foreach($months as $m)
            @continue($m['value'] === $month)
            <option value="{{ $m['value'] }}" @selected($compare && $compare['month'] === $m['value'])>{{ $m['label'] }}</option>
          @endforeach
        </select>
      </div>
      <noscript><button class="ba-btn" type="submit">Show</button></noscript>
      <a class="ba-btn primary" href="{{ route('budget.report', ['month' => $month, 'compare' => $compare['month'] ?? 'none']) }}"><i class="bi bi-file-earmark-pdf" aria-hidden="true"></i> Download PDF</a>
      <a class="ba-btn" href="{{ route('budget.index', ['month' => $month]) }}"><i class="bi bi-arrow-left" aria-hidden="true"></i> Planner</a>
    </form>
  </div>

  <div class="ba-kpis">
    <div class="ba-kpi"><div class="k">Income</div><div class="v">{{ $rs($income) }}</div>
      @if($comparison) {!! $delta($comparison['income'], true) !!}<div class="c">{{ $cmpLabel }}: {{ $rs($comparison['income']['b']) }}</div>@else<div class="c">{{ $incomes->count() }} {{ $incomes->count() === 1 ? 'source' : 'sources' }}</div>@endif</div>
    <div class="ba-kpi"><div class="k">Planned budget</div><div class="v">{{ $rs($budget) }}</div>
      @if($comparison) {!! $delta($comparison['budget'], null) !!}<div class="c">{{ $cmpLabel }}: {{ $rs($comparison['budget']['b']) }}</div>@else<div class="c">{{ $unplanned < 0 ? $rs(-$unplanned) . ' more than income' : $rs($unplanned) . ' not planned' }}</div>@endif</div>
    <div class="ba-kpi"><div class="k">Spent</div><div class="v">{{ $rs($spent) }}</div>
      @if($comparison) {!! $delta($comparison['spent'], false) !!}<div class="c">{{ $cmpLabel }}: {{ $rs($comparison['spent']['b']) }}</div>@else<div class="c">{{ round($used) }}% of budget · {{ $entryCount }} {{ $entryCount === 1 ? 'entry' : 'entries' }}</div>@endif</div>
    <div class="ba-kpi"><div class="k">Left (income − spent)</div><div class="v" style="color:{{ $left < 0 ? 'var(--bad)' : 'var(--good)' }}">{{ $rs($left) }}</div>
      @if($comparison) {!! $delta($comparison['left'], true) !!}<div class="c">{{ $cmpLabel }}: {{ $rs($comparison['left']['b']) }}</div>@else<div class="c">{{ $savingsRate === null ? 'No income recorded' : 'Savings rate ' . round($savingsRate) . '%' }}</div>@endif</div>
  </div>

  <div class="ba-grid">
    <section class="ba-panel wide">
      <div class="ba-ph"><h2>Key insights</h2></div>
      <ul class="ba-insights">
        @foreach($insights as [$type, $text])
          <li><span class="ba-tag {{ $type }}"><i class="bi bi-{{ $tagIcon[$type] }}" aria-hidden="true"></i>{{ $tagLabel[$type] }}</span><span>{{ $text }}</span></li>
        @endforeach
      </ul>
    </section>

    <section class="ba-panel">
      <div class="ba-ph"><h2>Spending by main category</h2><span class="note">Rs, sorted by {{ $monthLabel }}</span></div>
      @if($comparison)
        <div class="ba-legend"><span><i style="background:var(--s1)"></i>{{ $monthLabel }}</span><span><i style="background:var(--s2)"></i>{{ $cmpLabel }}</span></div>
      @endif
      <div class="ba-chart" style="height:{{ max(200, $groups->filter(fn ($g) => $g['spent'] > 0 || ($comparison && ($comparison['groups']->firstWhere('name', $g['name'])['b'] ?? 0) > 0))->count() * ($comparison ? 44 : 32) + 50) }}px"><canvas id="chGroups" role="img" aria-label="Spending by main category"></canvas></div>
    </section>

    <section class="ba-panel">
      <div class="ba-ph"><h2>Budget vs spent</h2><span class="note">{{ $monthLabel }}</span></div>
      <div class="ba-legend"><span><i style="background:#dbe3ef"></i>Budget</span><span><i style="background:var(--s1)"></i>Spent</span><span><i style="background:#d03b3b"></i>Spent over budget</span></div>
      <div class="ba-chart" style="height:{{ max(220, $groups->count() * 32 + 40) }}px"><canvas id="chBudget" role="img" aria-label="Budget compared with spending for each main category"></canvas></div>
    </section>

    <section class="ba-panel wide">
      <div class="ba-ph"><h2>Spending through the month</h2><span class="note">Running total by day of month</span></div>
      <div class="ba-legend">
        <span><i class="line" style="background:var(--s1)"></i>{{ $monthLabel }}</span>
        @if($compare)<span><i class="line" style="background:var(--s2)"></i>{{ $cmpLabel }}</span>@endif
        @if($budget > 0)<span><i class="dash"></i>Even pace to budget ({{ $rs($budget) }})</span>@endif
      </div>
      <div class="ba-chart" style="height:300px"><canvas id="chPace" role="img" aria-label="Running total of spending through the month"></canvas></div>
    </section>

    @if($trend->count() > 1)
      <section class="ba-panel wide">
        <div class="ba-ph"><h2>Last {{ $trend->count() }} months</h2><span class="note">Spent each month against budget and income</span></div>
        <div class="ba-legend"><span><i style="background:var(--s1)"></i>Spent</span><span><i class="dash"></i>Budget</span><span><i class="line" style="background:var(--s6)"></i>Income</span></div>
        <div class="ba-chart" style="height:280px"><canvas id="chTrend" role="img" aria-label="Spending, budget and income over recent months"></canvas></div>
      </section>
    @endif

    <section class="ba-panel wide">
      <div class="ba-ph"><h2>Main categories</h2>@if($comparison)<span class="note">Change is {{ $monthLabel }} against {{ $cmpLabel }}</span>@endif</div>
      <div class="ba-tw"><table class="ba-table">
        <thead><tr><th>Main category</th><th>Budget</th><th>Spent</th><th>Used</th><th>Share</th>@if($comparison)<th>{{ $cmpLabel }}</th><th>Change</th>@endif</tr></thead>
        <tbody>
          @foreach($groups->sortByDesc('spent') as $g)
            @php $cg = $comparison ? $comparison['groups']->firstWhere('name', $g['name']) : null; @endphp
            <tr>
              <td>{{ $g['name'] }}</td><td>{{ $rs($g['budget']) }}</td><td>{{ $rs($g['spent']) }}</td>
              <td style="color:{{ $g['used'] !== null && $g['used'] > 100 ? 'var(--bad)' : 'inherit' }}">{{ $g['used'] === null ? '–' : round($g['used']) . '%' }}</td>
              <td>{{ round($g['share']) }}%</td>
              @if($comparison)<td>{{ $rs($cg['b'] ?? 0) }}</td><td>{!! $delta($cg, false) !!}</td>@endif
            </tr>
          @endforeach
        </tbody>
        <tfoot><tr><td>Total</td><td>{{ $rs($budget) }}</td><td>{{ $rs($spent) }}</td><td>{{ round($used) }}%</td><td>100%</td>@if($comparison)<td>{{ $rs($comparison['spent']['b']) }}</td><td>{!! $delta($comparison['spent'], false) !!}</td>@endif</tr></tfoot>
      </table></div>
    </section>

    <section class="ba-panel wide">
      <div class="ba-ph"><h2>Sub categories</h2></div>
      <div class="ba-tw"><table class="ba-table">
        <thead><tr><th>Sub category</th><th>Budget</th><th>Spent</th><th>Left</th><th>Status</th>@if($comparison)<th>{{ $cmpLabel }}</th><th>Change</th>@endif</tr></thead>
        <tbody>
          @foreach($groups as $g)
            <tr class="grp"><td colspan="{{ $comparison ? 7 : 5 }}">{{ $g['name'] }}</td></tr>
            @foreach($g['cats'] as $c)
              @php $cc = $comparison ? $comparison['cats']->first(fn ($x) => $x['group'] === $g['name'] && $x['name'] === $c['name']) : null; @endphp
              <tr>
                <td style="padding-left:26px">{{ $c['name'] }}</td><td>{{ $rs($c['budget']) }}</td><td>{{ $rs($c['spent']) }}</td>
                <td style="color:{{ $c['left'] < 0 ? 'var(--bad)' : 'inherit' }}">{{ $rs($c['left']) }}</td>
                <td><span class="ba-pill {{ $pillClass[$c['status']] }}">{{ $c['status'] }}</span></td>
                @if($comparison)<td>{{ $rs($cc['b'] ?? 0) }}</td><td>{!! $delta($cc, false) !!}</td>@endif
              </tr>
            @endforeach
          @endforeach
        </tbody>
      </table></div>
    </section>

    <section class="ba-panel">
      <div class="ba-ph"><h2>Biggest expenses</h2></div>
      <div class="ba-tw"><table class="ba-table">
        <thead><tr><th>Date</th><th style="text-align:left">What</th><th>Amount</th></tr></thead>
        <tbody>
          @forelse($topEntries as $e)
            <tr><td>{{ $e->date->format('D j M') }}</td><td style="text-align:left;white-space:normal">{{ $e->note ?: ($e->category?->name ?? 'Spend') }}<div style="font-size:12px;color:var(--ink-3)">{{ $e->category?->group?->name }} › {{ $e->category?->name ?? 'Removed category' }}</div></td><td>{{ $rs($e->amount) }}</td></tr>
          @empty
            <tr><td colspan="3" class="ba-empty">No spending logged this month.</td></tr>
          @endforelse
        </tbody>
      </table></div>
    </section>

    <section class="ba-panel">
      <div class="ba-ph"><h2>Spending by week</h2></div>
      <div class="ba-tw"><table class="ba-table">
        <thead><tr><th>Week</th><th>{{ $monthLabel }}</th>@if($compare)<th>{{ $cmpLabel }}</th>@endif</tr></thead>
        <tbody>
          {{-- Months differ in length (28–31 days), so cover the longer month's weeks. --}}
          @for($i = 0; $i < max(count($weeks), $compare ? count($compare['weeks']) : 0); $i++)
            <tr>
              <td>{{ $weeks[$i]['label'] ?? $compare['weeks'][$i]['label'] }}</td>
              <td>{{ isset($weeks[$i]) ? $rs($weeks[$i]['spent']) : '–' }}</td>
              @if($compare)<td>{{ isset($compare['weeks'][$i]) ? $rs($compare['weeks'][$i]['spent']) : '–' }}</td>@endif
            </tr>
          @endfor
        </tbody>
      </table></div>
    </section>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
  document.getElementById('baMonth').addEventListener('change', () => {
    document.getElementById('baCompare').value = ''; // let the server default to the previous month
    document.getElementById('baForm').submit();
  });
  document.getElementById('baCompare').addEventListener('change', () => document.getElementById('baForm').submit());

  if (!window.Chart) return;
  const D = @json($chartData);
  const S1 = '#0f66c3', S2 = '#eb6834', S6 = '#008300', CRIT = '#d03b3b', TRACK = '#dbe3ef', MUTED = '#94a3b8';
  const rs = n => 'Rs ' + Math.round(n || 0).toLocaleString('en-US');
  const short = n => n >= 1e6 ? (n / 1e6).toFixed(1).replace(/\.0$/, '') + 'M' : n >= 1e3 ? Math.round(n / 1e3) + 'k' : String(Math.round(n));

  Chart.defaults.font.family = '"Plus Jakarta Sans", system-ui, sans-serif';
  Chart.defaults.font.size = 12;
  Chart.defaults.color = '#64748b';
  Chart.defaults.plugins.legend.display = false; // HTML legends above each chart
  Chart.defaults.plugins.tooltip.backgroundColor = '#072d6b';
  Chart.defaults.plugins.tooltip.padding = 10;
  Chart.defaults.plugins.tooltip.cornerRadius = 8;
  Chart.defaults.maintainAspectRatio = false;
  const grid = { color: '#eef2f7', drawTicks: false };
  const moneyAxis = { grid, border: { display: false }, ticks: { callback: v => short(v), padding: 6 }, beginAtZero: true };
  const catAxis = { grid: { display: false }, border: { color: '#cbd5e1' }, ticks: { color: '#334155', padding: 6 } };
  const empty = (id, text) => { const c = document.getElementById(id); if (c) c.parentElement.outerHTML = `<div class="ba-empty">${text}</div>`; };

  // 1. Spending by main category (optionally grouped with the comparison month)
  const spentIn = (list, name) => ((list || []).find(x => x.name === name) || {}).spent || 0;
  // Only main categories with spending in either month.
  const groups = D.groups.filter(g => g.spent > 0 || spentIn(D.compareGroups, g.name) > 0).sort((a, b) => b.spent - a.spent);
  const anyGroupSpend = groups.length > 0;
  if (!anyGroupSpend) empty('chGroups', 'No spending logged yet.');
  else {
    const sets = [{ label: D.monthLabel, data: groups.map(g => g.spent), backgroundColor: S1, borderRadius: 4, maxBarThickness: 16, borderSkipped: 'start' }];
    if (D.compareGroups) {
      sets.push({ label: D.compareLabel, data: groups.map(g => spentIn(D.compareGroups, g.name)), backgroundColor: S2, borderRadius: 4, maxBarThickness: 16, borderSkipped: 'start' });
    }
    new Chart(document.getElementById('chGroups'), {
      type: 'bar',
      data: { labels: groups.map(g => g.name), datasets: sets },
      options: { indexAxis: 'y', scales: { x: moneyAxis, y: catAxis }, datasets: { bar: { categoryPercentage: .7, barPercentage: .9 } },
        plugins: { tooltip: { callbacks: { label: c => `${c.dataset.label}: ${rs(c.raw)}` } } } },
    });
  }

  // 2. Budget vs spent: spent bar drawn over a budget track
  const bv = D.groups.filter(g => g.budget > 0 || g.spent > 0);
  if (!bv.length) empty('chBudget', 'No budgets or spending yet.');
  else {
    new Chart(document.getElementById('chBudget'), {
      type: 'bar',
      data: { labels: bv.map(g => g.name), datasets: [
        { label: 'Spent', data: bv.map(g => g.spent), backgroundColor: bv.map(g => g.spent > g.budget ? CRIT : S1), borderRadius: 4, maxBarThickness: 10, borderSkipped: 'start', order: 1, grouped: false },
        { label: 'Budget', data: bv.map(g => g.budget), backgroundColor: TRACK, borderRadius: 4, maxBarThickness: 18, borderSkipped: 'start', order: 2, grouped: false },
      ] },
      options: { indexAxis: 'y', scales: { x: moneyAxis, y: catAxis },
        plugins: { tooltip: { callbacks: {
          label: c => `${c.dataset.label}: ${rs(c.raw)}`,
          afterBody: items => { const g = bv[items[0].dataIndex]; const d = g.budget - g.spent; return g.budget > 0 ? (d < 0 ? `Over budget by ${rs(-d)}` : `${rs(d)} left (${Math.round(g.spent / g.budget * 100)}% used)`) : 'No budget set'; },
        } } } },
    });
  }

  // 3. Running total through the month, with the comparison month and an even pace line to budget
  const days = D.daily.length;
  const cum = arr => { let t = 0; return arr.map(v => (t += v)); };
  const today = new Date();
  const isCurrent = D.month === `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}`;
  const thisCum = cum(D.daily).map((v, i) => (isCurrent && i + 1 > today.getDate()) ? null : v);
  const paceSets = [{ label: D.monthLabel, data: thisCum, borderColor: S1, backgroundColor: S1, borderWidth: 2, pointRadius: 0, pointHoverRadius: 5, tension: .25, spanGaps: false }];
  if (D.compareDaily) {
    const cmp = cum(D.compareDaily);
    paceSets.push({ label: D.compareLabel, data: Array.from({ length: days }, (_, i) => cmp[Math.min(i, cmp.length - 1)]), borderColor: S2, backgroundColor: S2, borderWidth: 2, pointRadius: 0, pointHoverRadius: 5, tension: .25 });
  }
  if (D.budget > 0) {
    paceSets.push({ label: 'Even pace to budget', data: Array.from({ length: days }, (_, i) => D.budget / days * (i + 1)), borderColor: MUTED, borderDash: [5, 4], borderWidth: 2, pointRadius: 0, pointHoverRadius: 0 });
  }
  const anyPace = D.daily.some(v => v > 0) || (D.compareDaily || []).some(v => v > 0);
  if (!anyPace && !(D.budget > 0)) empty('chPace', 'No spending logged yet.');
  else {
    new Chart(document.getElementById('chPace'), {
      type: 'line',
      data: { labels: Array.from({ length: days }, (_, i) => i + 1), datasets: paceSets },
      options: { interaction: { mode: 'index', intersect: false },
        scales: { x: { grid: { display: false }, border: { color: '#cbd5e1' }, ticks: { maxTicksLimit: 11 }, title: { display: true, text: 'Day of month', color: '#64748b' } }, y: moneyAxis },
        plugins: { tooltip: { callbacks: { title: i => `Day ${i[0].label}`, label: c => c.raw === null ? null : `${c.dataset.label}: ${rs(c.raw)}` } } } },
      plugins: [{ // crosshair
        id: 'crosshair',
        afterDatasetsDraw(chart) {
          const a = chart.tooltip?.getActiveElements?.(); if (!a || !a.length) return;
          const x = a[0].element.x, { top, bottom } = chart.chartArea, ctx = chart.ctx;
          ctx.save(); ctx.strokeStyle = '#cbd5e1'; ctx.lineWidth = 1; ctx.beginPath(); ctx.moveTo(x, top); ctx.lineTo(x, bottom); ctx.stroke(); ctx.restore();
        },
      }],
    });
  }

  // 4. Recent months: spent bars against budget and income lines (one Rs axis)
  if (document.getElementById('chTrend')) {
    new Chart(document.getElementById('chTrend'), {
      data: { labels: D.trend.map(t => t.label), datasets: [
        { type: 'bar', label: 'Spent', data: D.trend.map(t => t.spent), backgroundColor: D.trend.map(t => t.month === D.month ? S1 : '#7fb0e6'), borderRadius: 4, maxBarThickness: 34, borderSkipped: 'start', order: 3 },
        { type: 'line', label: 'Budget', data: D.trend.map(t => t.budget), borderColor: MUTED, borderDash: [5, 4], borderWidth: 2, pointRadius: 4, pointBackgroundColor: '#fff', pointBorderWidth: 2, order: 2 },
        { type: 'line', label: 'Income', data: D.trend.map(t => t.income), borderColor: S6, borderWidth: 2, pointRadius: 4, pointBackgroundColor: '#fff', pointBorderWidth: 2, order: 1 },
      ] },
      options: { interaction: { mode: 'index', intersect: false }, scales: { x: catAxis, y: moneyAxis },
        plugins: { tooltip: { callbacks: { label: c => `${c.dataset.label}: ${rs(c.raw)}` } } } },
    });
  }
})();
</script>
@endsection
