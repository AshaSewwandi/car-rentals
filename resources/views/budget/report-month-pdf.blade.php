@php
  $rs = fn ($n) => ($n < 0 ? '−' : '') . 'Rs ' . number_format(abs(round($n)));
  $pct = fn ($n) => $n === null ? '–' : round($n) . '%';
  $bar = fn ($value, $max, $over = false) => '<div class="bar' . ($over ? ' over' : '') . '"><div style="width:' . ($max > 0 ? min(100, max(0, $value / $max * 100)) : 0) . '%"></div></div>';
  $pillClass = ['Over budget' => 'over', 'Nearly used' => 'near', 'On track' => 'ok', 'Not used' => 'none', 'No budget' => 'none', 'Unbudgeted' => 'unb'];
  $tagLabel = ['good' => 'GOOD', 'bad' => 'ALERT', 'warn' => 'WATCH', 'info' => 'NOTE'];
@endphp
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Budget report – {{ $monthLabel }}</title>
  @include('budget._report-styles')
</head>
<body>
  <div class="footer">R&amp;A Auto Rentals · Budget report {{ $monthLabel }} · Generated {{ now()->format('j M Y, g:i a') }}</div>

  <div class="brand">
    <div class="kicker">R&amp;A Auto Rentals · Budget Planner</div>
    <div class="title">Budget report — {{ $monthLabel }}</div>
    <div class="meta">
      Generated {{ now()->format('j M Y, g:i a') }} by {{ $generatedBy }}
      · @if($state === 'current') Month in progress (day {{ $daysElapsed }} of {{ $daysInMonth }}) @elseif($state === 'past') Completed month @else Upcoming month @endif
    </div>
  </div>

  <table class="kpis" style="margin-top:12px">
    <tr>
      <td><div class="kpi"><div class="k">Income</div><div class="v">{{ $rs($income) }}</div><div class="s">{{ $incomes->count() }} {{ $incomes->count() === 1 ? 'source' : 'sources' }}</div></div></td>
      <td><div class="kpi"><div class="k">Planned budget</div><div class="v">{{ $rs($budget) }}</div><div class="s {{ $unplanned < 0 ? 'bad' : '' }}">{{ $unplanned < 0 ? $rs(-$unplanned) . ' over income' : $rs($unplanned) . ' not planned' }}</div></div></td>
      <td><div class="kpi"><div class="k">Spent</div><div class="v">{{ $rs($spent) }}</div><div class="s">{{ round($used) }}% of budget · {{ $entryCount }} {{ $entryCount === 1 ? 'entry' : 'entries' }}</div></div></td>
      <td><div class="kpi"><div class="k">Left (income − spent)</div><div class="v {{ $left < 0 ? 'bad' : 'good' }}">{{ $rs($left) }}</div><div class="s">{{ $savingsRate === null ? 'No income recorded' : 'Savings rate ' . round($savingsRate) . '%' }}</div></div></td>
    </tr>
  </table>

  <h2>Key insights</h2>
  <table class="insights">
    @foreach($insights as [$type, $text])
      <tr><td style="width:60px"><span class="tag {{ $type }}">{{ $tagLabel[$type] }}</span></td><td>{{ $text }}</td></tr>
    @endforeach
  </table>

  @if($comparison)
    @php
      $cmpLabel = $compare['monthLabel'];
      // Increase in spending is bad; increase in income/left is good.
      $chg = function ($d, $higherIsGood = false) use ($rs) {
          if ($d['diff'] == 0) return '<span class="muted">No change</span>';
          $up = $d['diff'] > 0;
          $cls = $higherIsGood === null ? 'muted' : (($up === $higherIsGood) ? 'good' : 'bad');
          return '<span class="' . $cls . '">' . ($up ? '▲ +' : '▼ −') . e($rs(abs($d['diff']))) . ($d['pct'] !== null ? ' (' . round(abs($d['pct'])) . '%)' : '') . '</span>';
      };
      $maxCmp = max($comparison['groups']->max('a'), $comparison['groups']->max('b'), 1);
    @endphp
    <h2>Comparison with {{ $cmpLabel }}</h2>
    <table class="data avoid-break">
      <thead><tr><th>Measure</th><th class="num">{{ $monthLabel }}</th><th class="num">{{ $cmpLabel }}</th><th class="num">Change</th></tr></thead>
      <tr><td>Income</td><td class="num">{{ $rs($comparison['income']['a']) }}</td><td class="num">{{ $rs($comparison['income']['b']) }}</td><td class="num">{!! $chg($comparison['income'], true) !!}</td></tr>
      <tr><td>Planned budget</td><td class="num">{{ $rs($comparison['budget']['a']) }}</td><td class="num">{{ $rs($comparison['budget']['b']) }}</td><td class="num">{!! $chg($comparison['budget'], null) !!}</td></tr>
      <tr><td>Spent</td><td class="num">{{ $rs($comparison['spent']['a']) }}</td><td class="num">{{ $rs($comparison['spent']['b']) }}</td><td class="num">{!! $chg($comparison['spent']) !!}</td></tr>
      <tr><td>Left (income − spent)</td><td class="num">{{ $rs($comparison['left']['a']) }}</td><td class="num">{{ $rs($comparison['left']['b']) }}</td><td class="num">{!! $chg($comparison['left'], true) !!}</td></tr>
      <tr><td>Spend entries</td><td class="num">{{ $comparison['entries']['a'] }}</td><td class="num">{{ $comparison['entries']['b'] }}</td><td class="num muted">{{ $comparison['entries']['diff'] > 0 ? '+' : '' }}{{ $comparison['entries']['diff'] }}</td></tr>
    </table>

    <h3>Spending by main category</h3>
    <div class="small muted" style="margin-bottom:4px">
      <span style="display:inline-block;width:10px;height:7px;background:#0f66c3;border-radius:2px"></span> {{ $monthLabel }}
      &nbsp;&nbsp;<span style="display:inline-block;width:10px;height:7px;background:#eb6834;border-radius:2px"></span> {{ $cmpLabel }}
    </div>
    <table class="data avoid-break">
      <thead><tr><th>Main category</th><th style="width:34%">Spent</th><th class="num">{{ $monthLabel }}</th><th class="num">{{ $cmpLabel }}</th><th class="num">Change</th></tr></thead>
      @foreach($comparison['groups']->sortByDesc(fn ($g) => max($g['a'], $g['b'])) as $g)
        <tr>
          <td><strong>{{ $g['name'] }}</strong></td>
          <td>
            <div class="bar" style="background:none;height:6px"><div style="width:{{ $g['a'] / $maxCmp * 100 }}%;background:#0f66c3;height:6px"></div></div>
            <div class="bar" style="background:none;height:6px;margin-top:2px"><div style="width:{{ $g['b'] / $maxCmp * 100 }}%;background:#eb6834;height:6px"></div></div>
          </td>
          <td class="num">{{ $rs($g['a']) }}</td><td class="num">{{ $rs($g['b']) }}</td><td class="num">{!! $chg($g) !!}</td>
        </tr>
      @endforeach
    </table>

    @php $movers = $comparison['cats']->filter(fn ($c) => $c['diff'] != 0)->sortByDesc(fn ($c) => abs($c['diff']))->take(10); @endphp
    @if($movers->isNotEmpty())
      <h3>Biggest changes by sub category</h3>
      <table class="data avoid-break">
        <thead><tr><th>Sub category</th><th class="num">{{ $monthLabel }}</th><th class="num">{{ $cmpLabel }}</th><th class="num">Change</th></tr></thead>
        @foreach($movers as $c)
          <tr><td>{{ $c['name'] }} <span class="muted small">· {{ $c['group'] }}</span></td><td class="num">{{ $rs($c['a']) }}</td><td class="num">{{ $rs($c['b']) }}</td><td class="num">{!! $chg($c) !!}</td></tr>
        @endforeach
      </table>
    @endif
  @endif

  @if($state === 'current' && $spent > 0)
    <h2>Spending pace</h2>
    <table class="data avoid-break">
      <tr><th>Measure</th><th class="num">Amount</th><th style="width:40%">Against budget</th></tr>
      <tr><td>Spent so far</td><td class="num">{{ $rs($spent) }}</td><td>{!! $bar($spent, max($budget, $projected), $spent > $budget) !!}</td></tr>
      <tr><td>Projected month-end (at {{ $rs($avgPerDay) }}/day)</td><td class="num {{ $projected > $budget && $budget > 0 ? 'bad' : '' }}">{{ $rs($projected) }}</td><td>{!! $bar($projected, max($budget, $projected), $projected > $budget) !!}</td></tr>
      <tr><td>Planned budget</td><td class="num">{{ $rs($budget) }}</td><td>{!! $bar($budget, max($budget, $projected)) !!}</td></tr>
    </table>
  @endif

  <h2>Main categories</h2>
  <table class="data">
    <thead><tr><th>Main category</th><th class="num">Budget</th><th class="num">Spent</th><th class="num">Left</th><th class="num">Used</th><th style="width:22%">Budget used</th><th class="num">Share of spend</th></tr></thead>
    <tbody>
      @foreach($groups->sortByDesc('spent') as $g)
        <tr>
          <td><strong>{{ $g['name'] }}</strong></td>
          <td class="num">{{ $rs($g['budget']) }}</td>
          <td class="num">{{ $rs($g['spent']) }}</td>
          <td class="num {{ $g['left'] < 0 ? 'bad' : '' }}">{{ $rs($g['left']) }}</td>
          <td class="num">{{ $pct($g['used']) }}</td>
          <td>{!! $bar($g['spent'], $g['budget'] ?: $g['spent'], $g['spent'] > $g['budget']) !!}</td>
          <td class="num">{{ round($g['share']) }}%</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot><tr><td>Total</td><td class="num">{{ $rs($budget) }}</td><td class="num">{{ $rs($spent) }}</td><td class="num {{ $budget - $spent < 0 ? 'bad' : '' }}">{{ $rs($budget - $spent) }}</td><td class="num">{{ round($used) }}%</td><td></td><td class="num">100%</td></tr></tfoot>
  </table>

  <h2>Sub categories</h2>
  <table class="data">
    <thead><tr><th>Sub category</th><th class="num">Budget</th><th class="num">Spent</th><th class="num">Left</th><th class="num">Entries</th><th>Status</th></tr></thead>
    <tbody>
      @foreach($groups as $g)
        <tr class="group"><td colspan="6">{{ $g['name'] }}</td></tr>
        @foreach($g['cats'] as $c)
          <tr>
            <td style="padding-left:14px">{{ $c['name'] }}</td>
            <td class="num">{{ $rs($c['budget']) }}</td>
            <td class="num">{{ $rs($c['spent']) }}</td>
            <td class="num {{ $c['left'] < 0 ? 'bad' : '' }}">{{ $rs($c['left']) }}</td>
            <td class="num">{{ $c['entries'] }}</td>
            <td><span class="pill {{ $pillClass[$c['status']] }}">{{ $c['status'] }}</span></td>
          </tr>
        @endforeach
      @endforeach
    </tbody>
  </table>

  <table style="margin-top:4px">
    <tr>
      <td style="width:50%; vertical-align:top; padding-right:8px">
        <h2>Spending by week</h2>
        @php $maxWeek = max(array_column($weeks, 'spent') ?: [0]); @endphp
        <table class="data avoid-break">
          <thead><tr><th>Week</th><th class="num">Spent</th><th style="width:40%"></th></tr></thead>
          @foreach($weeks as $w)
            <tr><td>{{ $w['label'] }}</td><td class="num">{{ $rs($w['spent']) }}</td><td>{!! $bar($w['spent'], $maxWeek) !!}</td></tr>
          @endforeach
        </table>
      </td>
      <td style="width:50%; vertical-align:top; padding-left:8px">
        <h2>Biggest expenses</h2>
        <table class="data avoid-break">
          <thead><tr><th>Date</th><th>What</th><th class="num">Amount</th></tr></thead>
          @forelse($topEntries as $e)
            <tr><td>{{ $e->date->format('j M') }}</td><td>{{ $e->note ?: ($e->category?->name ?? 'Spend') }}<br><span class="muted small">{{ $e->category?->group?->name }} › {{ $e->category?->name ?? 'Removed category' }}</span></td><td class="num">{{ $rs($e->amount) }}</td></tr>
          @empty
            <tr><td colspan="3" class="muted">No spending logged.</td></tr>
          @endforelse
        </table>
      </td>
    </tr>
  </table>

  @if($trend->count() > 1)
    <h2>Last {{ $trend->count() }} months</h2>
    @php $maxTrend = $trend->max(fn ($t) => max($t['spent'], $t['budget'])); @endphp
    <table class="data avoid-break">
      <thead><tr><th>Month</th><th class="num">Income</th><th class="num">Budget</th><th class="num">Spent</th><th class="num">Left</th><th style="width:28%">Spent vs largest month</th></tr></thead>
      @foreach($trend as $t)
        <tr @if($t['month'] === $month) style="font-weight:bold" @endif>
          <td>{{ $t['label'] }}</td><td class="num">{{ $rs($t['income']) }}</td><td class="num">{{ $rs($t['budget']) }}</td>
          <td class="num">{{ $rs($t['spent']) }}</td><td class="num {{ $t['left'] < 0 ? 'bad' : 'good' }}">{{ $rs($t['left']) }}</td>
          <td>{!! $bar($t['spent'], $maxTrend, $t['budget'] > 0 && $t['spent'] > $t['budget']) !!}</td>
        </tr>
      @endforeach
    </table>
  @endif

  <h2>Income</h2>
  <table class="data avoid-break">
    <thead><tr><th>Source</th><th class="num">Amount</th><th class="num">Share</th></tr></thead>
    @forelse($incomes as $i)
      <tr><td>{{ $i->name }}</td><td class="num">{{ $rs($i->amount) }}</td><td class="num">{{ $income > 0 ? round($i->amount / $income * 100) : 0 }}%</td></tr>
    @empty
      <tr><td colspan="3" class="muted">No income recorded.</td></tr>
    @endforelse
    <tfoot><tr><td>Total</td><td class="num">{{ $rs($income) }}</td><td></td></tr></tfoot>
  </table>

  @if($entries->isNotEmpty())
    <h2>All spending this month</h2>
    <table class="data">
      <thead><tr><th style="width:62px">Date</th><th>What</th><th>Category</th><th class="num">Amount</th></tr></thead>
      @foreach($entries as $e)
        <tr><td>{{ $e->date->format('D j M') }}</td><td>{{ $e->note ?: '–' }}</td><td>{{ $e->category?->group?->name }} › {{ $e->category?->name ?? 'Removed category' }}</td><td class="num">{{ $rs($e->amount) }}</td></tr>
      @endforeach
      <tfoot><tr><td colspan="3">Total</td><td class="num">{{ $rs($spent) }}</td></tr></tfoot>
    </table>
  @endif
</body>
</html>
