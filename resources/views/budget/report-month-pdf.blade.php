@php
  $rs = fn ($n) => ($n < 0 ? '−' : '') . 'Rs ' . number_format(abs(round($n)));
  $pct = fn ($n) => $n === null ? '–' : round($n) . '%';
  $bar = fn ($value, $max, $over = false, $color = null) => '<div class="bar' . ($over ? ' over' : '') . '"><div style="width:' . ($max > 0 ? min(100, max(0, $value / $max * 100)) : 0) . '%' . ($color && !$over ? ';background:' . $color : '') . '"></div></div>';
  $pillClass = ['Over budget' => 'over', 'Nearly used' => 'near', 'On track' => 'ok', 'Not used' => 'none', 'No budget' => 'none', 'Unbudgeted' => 'unb'];
  $tagLabel = ['good' => 'GOOD', 'bad' => 'ALERT', 'warn' => 'WATCH', 'info' => 'NOTE'];

  // Validated categorical palette (slot 1 = site blue). Colour follows the main category's position in the plan, never its rank.
  $palette = ['#0f66c3', '#eb6834', '#1baf7a', '#eda100', '#e87ba4', '#008300', '#4a3aa7', '#e34948'];
  $groups = $groups->values()->map(fn ($g, $i) => $g + ['color' => $palette[$i] ?? '#94a3b8']);
  $spending = $groups->filter(fn ($g) => $g['spent'] > 0)->sortByDesc('spent')->values();
  $idle = $groups->filter(fn ($g) => $g['spent'] <= 0);
  $uncatTotal = $uncategorised->sum('amount');
  $maxDay = max($daily ?: [0]);
@endphp
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Monthly budget report – {{ $monthLabel }}</title>
  @include('budget._report-styles')
  <style>
    .share-strip, .daychart { table-layout: fixed; }
    .share-strip td { height: 16px; padding: 0; border-right: 2px solid #fff; }
    .share-strip td:last-child { border-right: 0; }
    .sw { display: inline-block; width: 9px; height: 9px; border-radius: 2px; margin-right: 4px; vertical-align: -1px; }
    .cat-head { margin-top: 14px; padding: 7px 9px; background: #f5f8fd; border-left: 4px solid #0f66c3; border-radius: 4px; }
    .cat-head .name { font-size: 12px; font-weight: bold; color: #072d6b; }
    .cat-head .figs { font-size: 9px; color: #475569; margin-top: 2px; }
    table.items td { padding: 2px 6px 2px 22px; border: 0; font-size: 8.5px; color: #475569; }
    table.items td.num { padding-right: 6px; }
    .daychart td { vertical-align: bottom; text-align: center; padding: 0 1px; }
    .daychart .col { background: #0f66c3; border-radius: 2px 2px 0 0; }
    .daychart .lbl { font-size: 7px; color: #94a3b8; padding-top: 2px; }
  </style>
</head>
<body>
  <div class="footer">R&amp;A Auto Rentals · Monthly budget report · {{ $monthLabel }} · Generated {{ now()->format('j M Y, g:i a') }}</div>

  <div class="brand">
    <div class="kicker">R&amp;A Auto Rentals · Budget Planner</div>
    <div class="title">Monthly budget report — {{ $monthLabel }}</div>
    <div class="meta">
      Generated {{ now()->format('j M Y, g:i a') }} by {{ $generatedBy }}
      · @if($state === 'current') Month in progress (day {{ $daysElapsed }} of {{ $daysInMonth }}) @elseif($state === 'past') Completed month @else Upcoming month @endif
    </div>
  </div>

  {{-- 1. Summary --}}
  <table class="kpis" style="margin-top:12px">
    <tr>
      <td><div class="kpi"><div class="k">Income</div><div class="v">{{ $rs($income) }}</div><div class="s">{{ $incomes->count() }} {{ $incomes->count() === 1 ? 'source' : 'sources' }}</div></div></td>
      <td><div class="kpi"><div class="k">Planned budget</div><div class="v">{{ $rs($budget) }}</div><div class="s {{ $unplanned < 0 ? 'bad' : '' }}">{{ $unplanned < 0 ? $rs(-$unplanned) . ' more than income' : $rs($unplanned) . ' not planned' }}</div></div></td>
      <td><div class="kpi"><div class="k">Spent</div><div class="v">{{ $rs($spent) }}</div><div class="s">{{ round($used) }}% of budget · {{ $entryCount }} {{ $entryCount === 1 ? 'entry' : 'entries' }}</div></div></td>
      <td><div class="kpi"><div class="k">Left (income − spent)</div><div class="v {{ $left < 0 ? 'bad' : 'good' }}">{{ $rs($left) }}</div><div class="s">{{ $savingsRate === null ? 'No income recorded' : 'Savings rate ' . round($savingsRate) . '%' }}</div></div></td>
    </tr>
  </table>

  <h2>Month at a glance</h2>
  <table class="insights">
    @foreach($insights as [$type, $text])
      <tr><td style="width:60px"><span class="tag {{ $type }}">{{ $tagLabel[$type] }}</span></td><td>{{ $text }}</td></tr>
    @endforeach
  </table>

  {{-- 2. Where the money went --}}
  <h2>Where the money went</h2>
  @if($spent > 0)
    <table class="share-strip avoid-break" style="margin:4px 0 6px">
      <tr>
        @foreach($spending as $g)
          <td style="width:{{ $g['spent'] / $spent * 100 }}%; background:{{ $g['color'] }}"></td>
        @endforeach
        @if($uncatTotal > 0)<td style="width:{{ $uncatTotal / $spent * 100 }}%; background:#94a3b8"></td>@endif
      </tr>
    </table>
    <table class="data avoid-break">
      <thead><tr><th>Main category</th><th class="num">Spent</th><th class="num">Share</th><th style="width:22%">Share of spending</th><th class="num">Budget</th><th class="num">Left</th><th class="num">Used</th></tr></thead>
      @foreach($spending as $g)
        <tr>
          <td><span class="sw" style="background:{{ $g['color'] }}"></span><strong>{{ $g['name'] }}</strong></td>
          <td class="num"><strong>{{ $rs($g['spent']) }}</strong></td>
          <td class="num">{{ round($g['spent'] / $spent * 100) }}%</td>
          <td>{!! $bar($g['spent'], $spent, false, $g['color']) !!}</td>
          <td class="num">{{ $rs($g['budget']) }}</td>
          <td class="num {{ $g['left'] < 0 ? 'bad' : '' }}">{{ $rs($g['left']) }}</td>
          <td class="num {{ $g['used'] !== null && $g['used'] > 100 ? 'bad' : '' }}">{{ $pct($g['used']) }}</td>
        </tr>
      @endforeach
      @if($uncatTotal > 0)
        <tr><td><span class="sw" style="background:#94a3b8"></span><strong>Removed categories</strong></td><td class="num"><strong>{{ $rs($uncatTotal) }}</strong></td><td class="num">{{ round($uncatTotal / $spent * 100) }}%</td><td>{!! $bar($uncatTotal, $spent, false, '#94a3b8') !!}</td><td class="num">–</td><td class="num">–</td><td class="num">–</td></tr>
      @endif
      <tfoot><tr><td>Total</td><td class="num">{{ $rs($spent) }}</td><td class="num">100%</td><td></td><td class="num">{{ $rs($budget) }}</td><td class="num {{ $budget - $spent < 0 ? 'bad' : '' }}">{{ $rs($budget - $spent) }}</td><td class="num">{{ round($used) }}%</td></tr></tfoot>
    </table>
    @if($idle->isNotEmpty())
      <p class="small muted" style="margin:6px 0 0">No spending this month: {{ $idle->map(fn ($g) => $g['name'] . ($g['budget'] > 0 ? ' (' . $rs($g['budget']) . ' budgeted)' : ''))->implode(', ') }}.</p>
    @endif
  @else
    <p class="muted">No spending has been logged for {{ $monthLabel }} yet.</p>
  @endif

  {{-- 3. Category-wise detail: every main category with spending, its sub categories, and each entry --}}
  @if($spent > 0)
    <h2 style="page-break-before:always">Spending by category</h2>
    <p class="small muted" style="margin:0 0 4px">Each main category with spending this month, its sub categories, and every entry logged against them.</p>

    @foreach($spending as $g)
      <div class="avoid-break">
        <div class="cat-head" style="border-left-color:{{ $g['color'] }}">
          <div class="name">{{ $g['name'] }}</div>
          <div class="figs">
            Spent <strong>{{ $rs($g['spent']) }}</strong>
            @if($g['budget'] > 0) of {{ $rs($g['budget']) }} budget ({{ round($g['used']) }}% used,
              <span class="{{ $g['left'] < 0 ? 'bad' : 'good' }}">{{ $g['left'] < 0 ? $rs(-$g['left']) . ' over' : $rs($g['left']) . ' left' }}</span>) @else · no budget set @endif
            · {{ round($g['spent'] / $spent * 100) }}% of the month's spending
          </div>
        </div>
        <table class="data" style="margin-top:4px">
          <thead><tr><th>Sub category</th><th class="num">Budget</th><th class="num">Spent</th><th class="num">Left</th><th style="width:18%">Budget used</th><th>Status</th></tr></thead>
          @foreach($g['cats']->filter(fn ($c) => $c['spent'] > 0 || $c['budget'] > 0)->sortByDesc('spent') as $c)
            <tr>
              <td><strong>{{ $c['name'] }}</strong>@if($c['entries']) <span class="muted small">· {{ $c['entries'] }} {{ $c['entries'] === 1 ? 'entry' : 'entries' }}</span>@endif</td>
              <td class="num">{{ $rs($c['budget']) }}</td>
              <td class="num">{{ $rs($c['spent']) }}</td>
              <td class="num {{ $c['left'] < 0 ? 'bad' : '' }}">{{ $rs($c['left']) }}</td>
              <td>{!! $c['budget'] > 0 ? $bar($c['spent'], $c['budget'], $c['spent'] > $c['budget'], $g['color']) : '<span class="muted small">No budget</span>' !!}</td>
              <td><span class="pill {{ $pillClass[$c['status']] }}">{{ $c['status'] }}</span></td>
            </tr>
            @if($c['items']->isNotEmpty())
              <tr><td colspan="6" style="padding:0 0 4px; border-bottom:1px solid #e8edf5">
                <table class="items">
                  @foreach($c['items'] as $e)
                    <tr><td style="width:70px">{{ $e->date->format('D j M') }}</td><td>{{ $e->note ?: '–' }}</td><td class="num" style="width:80px">{{ $rs($e->amount) }}</td></tr>
                  @endforeach
                </table>
              </td></tr>
            @endif
          @endforeach
        </table>
      </div>
    @endforeach

    @if($uncategorised->isNotEmpty())
      <div class="avoid-break">
        <div class="cat-head" style="border-left-color:#94a3b8">
          <div class="name">Removed categories</div>
          <div class="figs">Spent <strong>{{ $rs($uncatTotal) }}</strong> in sub categories that have since been deleted</div>
        </div>
        <table class="data" style="margin-top:4px">
          <thead><tr><th style="width:80px">Date</th><th>What</th><th class="num">Amount</th></tr></thead>
          @foreach($uncategorised as $e)
            <tr><td>{{ $e->date->format('D j M') }}</td><td>{{ $e->note ?: '–' }}</td><td class="num">{{ $rs($e->amount) }}</td></tr>
          @endforeach
        </table>
      </div>
    @endif
  @endif

  {{-- 4. When the money was spent --}}
  @if($spent > 0)
    <h2>When the money was spent</h2>
    <table class="daychart avoid-break" style="height:110px">
      <tr>
        @foreach($daily as $i => $v)
          <td style="width:{{ 100 / count($daily) }}%">
            @if($v > 0)<div class="col" style="height:{{ max(2, round($v / $maxDay * 90)) }}px"></div>@endif
          </td>
        @endforeach
      </tr>
      <tr>
        @foreach($daily as $i => $v)
          <td class="lbl">{{ ($i + 1) % 5 === 0 || $i === 0 ? $i + 1 : '' }}</td>
        @endforeach
      </tr>
    </table>
    <p class="small muted" style="margin:2px 0 8px">
      Spending per day of the month (tallest bar {{ $rs($maxDay) }}).
      @if($busiestDay) Busiest day: {{ $busiestDay['label'] }} ({{ $rs($busiestDay['amount']) }}). @endif
      Average {{ $rs($avgPerDay) }} per day{{ $state === 'current' ? ' so far' : '' }}.
    </p>

    <table style="margin-top:4px">
      <tr>
        <td style="width:48%; vertical-align:top; padding-right:10px">
          <h3>By week</h3>
          @php $maxWeek = max(array_column($weeks, 'spent') ?: [0]); @endphp
          <table class="data avoid-break">
            <thead><tr><th>Week</th><th class="num">Spent</th><th style="width:38%"></th></tr></thead>
            @foreach($weeks as $w)
              <tr><td>{{ $w['label'] }}</td><td class="num">{{ $rs($w['spent']) }}</td><td>{!! $bar($w['spent'], $maxWeek) !!}</td></tr>
            @endforeach
          </table>
        </td>
        <td style="width:52%; vertical-align:top; padding-left:10px">
          <h3>Biggest expenses</h3>
          <table class="data avoid-break">
            <thead><tr><th>Date</th><th>What</th><th class="num">Amount</th></tr></thead>
            @foreach($topEntries as $e)
              <tr><td>{{ $e->date->format('j M') }}</td><td>{{ $e->note ?: ($e->category?->name ?? 'Spend') }}<br><span class="muted small">{{ $e->category ? $e->category->group?->name . ' › ' . $e->category->name : 'Removed category' }}</span></td><td class="num">{{ $rs($e->amount) }}</td></tr>
            @endforeach
          </table>
        </td>
      </tr>
    </table>
  @endif

  {{-- 5. Income --}}
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
</body>
</html>
