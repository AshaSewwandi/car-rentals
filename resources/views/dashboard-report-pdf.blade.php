@php
  $rs = fn ($n) => ($n < 0 ? '−' : '') . 'Rs ' . number_format(abs(round($n)));
  $signed = fn ($n) => ($n > 0 ? '+' : ($n < 0 ? '−' : '')) . 'Rs ' . number_format(abs(round($n)));
  $tagLabel = ['good' => 'GOOD', 'bad' => 'ALERT', 'warn' => 'WATCH', 'info' => 'NOTE'];
  $bar = fn ($value, $max, $color) => '<div class="bar" style="background:#eef2f7"><div style="width:' . ($max > 0 ? min(100, max(0, $value / $max * 100)) : 0) . '%;background:' . $color . '"></div></div>';
  $netClass = fn ($n) => $n > 0 ? 'good' : ($n < 0 ? 'bad' : '');
  $resultWord = !$pl['hasActivity'] ? 'No activity' : ($pl['net'] > 0 ? 'Profit' : ($pl['net'] < 0 ? 'Loss' : 'Break-even'));
  $activeVehicles = $pl['vehicles']->where('active', true);
  $maxAbsNet = max($activeVehicles->max(fn ($r) => abs($r['net'])) ?? 0, 1);
  $trendMax = max($trend->max(fn ($t) => max($t['income'], $t['costs'])) ?? 0, 1);
  $green = '#16a34a'; $red = '#dc2626'; $blue = '#0f66c3';
@endphp
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Business report – {{ $pl['label'] }}</title>
  @include('budget._report-styles')
  <style>
    .result { border-radius: 8px; padding: 10px 12px; border: 1px solid #dbe5f3; background: #f8faff; }
    .result.good { background: #f0fdf4; border-color: #bbf7d0; }
    .result.bad { background: #fef2f2; border-color: #fecaca; }
    .result .k { font-size: 9px; text-transform: uppercase; letter-spacing: .4px; color: #64748b; }
    .result .v { font-size: 22px; font-weight: bold; margin-top: 2px; }
    .result.good .v { color: #15803d; } .result.bad .v { color: #b91c1c; }
    .result .s { font-size: 9px; color: #475569; margin-top: 2px; }
    .div-bar { position: relative; height: 9px; background: #f1f5f9; border-radius: 4px; }
    .div-bar .mid { position: absolute; left: 50%; top: -2px; bottom: -2px; width: 1px; background: #94a3b8; }
    .div-bar .pos { position: absolute; left: 50%; top: 0; bottom: 0; background: #16a34a; }
    .div-bar .neg { position: absolute; right: 50%; top: 0; bottom: 0; background: #dc2626; }
    .trend td { vertical-align: bottom; text-align: center; padding: 0 6px; }
    .trend .col { display: inline-block; width: 16px; margin: 0 1px; border-radius: 2px 2px 0 0; }
    .trend .lbl { font-size: 8.5px; color: #475569; padding-top: 3px; vertical-align: top; }
  </style>
</head>
<body>
  <div class="footer">R&amp;A Auto Rentals · Business report · {{ $pl['label'] }} · Generated {{ now()->format('j M Y, g:i a') }}</div>

  <div class="brand">
    <div class="kicker">R&amp;A Auto Rentals · Monthly business report</div>
    <div class="title">Income, expenses &amp; profit — {{ $pl['label'] }}</div>
    <div class="meta">Generated {{ now()->format('j M Y, g:i a') }} by {{ $generatedBy }}@if($state === 'current') · Month in progress @endif</div>
  </div>

  {{-- 1. Result --}}
  <table class="kpis" style="margin-top:12px">
    <tr>
      <td><div class="kpi"><div class="k">Income</div><div class="v">{{ $rs($pl['income']) }}</div><div class="s">Money received</div></div></td>
      <td><div class="kpi"><div class="k">Costs</div><div class="v">{{ $rs($pl['costs']) }}</div><div class="s">Expenses and partner share</div></div></td>
      <td><div class="result {{ $netClass($pl['net']) }}">
        <div class="k">{{ $resultWord }}</div>
        <div class="v">{{ $rs($pl['net']) }}</div>
        <div class="s">{{ $pl['margin'] !== null && $pl['net'] > 0 ? round($pl['margin']) . '% margin' : ($pl['hasActivity'] ? 'Income minus costs' : 'Nothing recorded') }}</div>
      </div></td>
      <td><div class="kpi"><div class="k">vs {{ $previous['label'] }}</div>
        <div class="v {{ $netClass($pl['net'] - $previous['net']) }}">{{ $signed($pl['net'] - $previous['net']) }}</div>
        <div class="s">{{ $previous['label'] }}: {{ $rs($previous['net']) }} {{ $previous['net'] < 0 ? 'loss' : 'profit' }}</div>
      </div></td>
    </tr>
  </table>

  <h2>Key findings</h2>
  <table class="insights">
    @foreach($insights as [$type, $text])
      <tr><td style="width:60px"><span class="tag {{ $type }}">{{ $tagLabel[$type] }}</span></td><td>{{ $text }}</td></tr>
    @endforeach
  </table>

  {{-- 2. Income and costs breakdown --}}
  <table style="margin-top:4px">
    <tr>
      @foreach(['income' => ['Where the money came from', $green, $pl['income']], 'costs' => ['Where the money went', $red, $pl['costs']]] as $side => [$title, $color, $total])
        <td style="width:50%; vertical-align:top; {{ $side === 'income' ? 'padding-right:8px' : 'padding-left:8px' }}">
          <h2>{{ $title }}</h2>
          <table class="data avoid-break">
            <thead><tr><th>{{ $side === 'income' ? 'Income' : 'Cost' }}</th><th class="num">Amount</th><th class="num">Share</th><th style="width:30%"></th></tr></thead>
            @foreach($pl['lines'][$side] as $line)
              <tr>
                <td>{{ $line['label'] }}</td>
                <td class="num">{{ $rs($line['amount']) }}</td>
                <td class="num">{{ $total > 0 ? round($line['amount'] / $total * 100) : 0 }}%</td>
                <td>{!! $bar($line['amount'], $total, $color) !!}</td>
              </tr>
            @endforeach
            <tfoot><tr><td>Total</td><td class="num">{{ $rs($total) }}</td><td class="num">{{ $total > 0 ? '100%' : '–' }}</td><td></td></tr></tfoot>
          </table>
        </td>
      @endforeach
    </tr>
  </table>

  {{-- 3. Profit by vehicle --}}
  <h2>Profit by vehicle</h2>
  @if($activeVehicles->isEmpty())
    <p class="muted">No income or costs were recorded for any vehicle.</p>
  @else
    <table class="data avoid-break">
      <thead><tr><th>Vehicle</th><th class="num">Income</th><th class="num">Costs</th><th class="num">Profit / loss</th><th style="width:24%; text-align:center">Loss &nbsp;|&nbsp; Profit</th></tr></thead>
      @foreach($activeVehicles as $v)
        @php $w = abs($v['net']) / $maxAbsNet * 50; @endphp
        <tr>
          <td><strong>{{ $v['name'] }}</strong>@if($v['plate'] && $v['plate'] !== $v['name']) <span class="muted small">{{ $v['plate'] }}</span>@endif</td>
          <td class="num">{{ $rs($v['income']) }}</td>
          <td class="num">{{ $rs($v['costs']) }}</td>
          <td class="num {{ $netClass($v['net']) }}"><strong>{{ $signed($v['net']) }}</strong></td>
          <td><div class="div-bar"><span class="mid"></span>@if($v['net'] > 0)<span class="pos" style="width:{{ $w }}%"></span>@elseif($v['net'] < 0)<span class="neg" style="width:{{ $w }}%"></span>@endif</div></td>
        </tr>
      @endforeach
      <tfoot><tr><td>All vehicles</td><td class="num">{{ $rs($pl['income']) }}</td><td class="num">{{ $rs($pl['costs']) }}</td><td class="num {{ $netClass($pl['net']) }}">{{ $signed($pl['net']) }}</td><td></td></tr></tfoot>
    </table>
    @php $idle = $pl['vehicles']->where('active', false); @endphp
    @if($idle->isNotEmpty())
      <p class="small muted" style="margin:5px 0 0">No income or costs this month: {{ $idle->map(fn ($v) => $v['plate'] && $v['plate'] !== $v['name'] ? $v['name'] . ' (' . $v['plate'] . ')' : $v['name'])->implode(', ') }}.</p>
    @endif
  @endif

  {{-- 4. Six-month trend --}}
  <h2>Last 6 months</h2>
  <div class="small muted" style="margin-bottom:4px">
    <span style="display:inline-block;width:10px;height:7px;background:{{ $green }};border-radius:2px"></span> Income
    &nbsp;&nbsp;<span style="display:inline-block;width:10px;height:7px;background:{{ $red }};border-radius:2px"></span> Costs
  </div>
  <table class="trend avoid-break" style="height:100px; table-layout:fixed">
    <tr>
      @foreach($trend as $t)
        <td>
          <span class="col" style="height:{{ max(1, round($t['income'] / $trendMax * 85)) }}px; background:{{ $green }}"></span><span class="col" style="height:{{ max(1, round($t['costs'] / $trendMax * 85)) }}px; background:{{ $red }}"></span>
        </td>
      @endforeach
    </tr>
    <tr>
      @foreach($trend as $t)
        <td class="lbl">{{ \Carbon\Carbon::createFromFormat('Y-m', $t['month'])->format('M Y') }}<br><strong class="{{ $netClass($t['net']) }}">{{ $signed($t['net']) }}</strong></td>
      @endforeach
    </tr>
  </table>
  <table class="data avoid-break" style="margin-top:6px">
    <thead><tr><th>Month</th><th class="num">Income</th><th class="num">Costs</th><th class="num">Profit / loss</th><th class="num">Margin</th></tr></thead>
    @foreach($trend as $t)
      <tr @if($t['month'] === $pl['month']) style="font-weight:bold" @endif>
        <td>{{ $t['label'] }}</td><td class="num">{{ $rs($t['income']) }}</td><td class="num">{{ $rs($t['costs']) }}</td>
        <td class="num {{ $netClass($t['net']) }}">{{ $signed($t['net']) }}</td>
        <td class="num">{{ $t['margin'] !== null && $t['net'] > 0 ? round($t['margin']) . '%' : '–' }}</td>
      </tr>
    @endforeach
    <tfoot><tr><td>6-month total</td><td class="num">{{ $rs($trend->sum('income')) }}</td><td class="num">{{ $rs($trend->sum('costs')) }}</td><td class="num {{ $netClass($trend->sum('net')) }}">{{ $signed($trend->sum('net')) }}</td><td></td></tr></tfoot>
  </table>

  {{-- 5. Collections --}}
  <h2>Long-term payment collection</h2>
  <table class="data avoid-break">
    <tr><td>Expected this month</td><td class="num">{{ $rs($expected) }}</td><td style="width:40%" rowspan="3">{!! $bar($received, $expected, $blue) !!}<div class="small muted" style="margin-top:3px">{{ $expected > 0 ? round($received / $expected * 100) . '% collected' : 'Nothing expected' }}</div></td></tr>
    <tr><td>Received</td><td class="num good">{{ $rs($received) }}</td></tr>
    <tr><td>Still to collect</td><td class="num {{ $expected - $received > 0 ? 'bad' : '' }}">{{ $rs(max($expected - $received, 0)) }}</td></tr>
  </table>
  @if($pending->isNotEmpty())
    <h3>Pending payments</h3>
    <table class="data avoid-break">
      <thead><tr><th>Due date</th><th>Vehicle</th><th>Customer</th><th class="num">Amount</th><th>Status</th></tr></thead>
      @foreach($pending as $p)
        @php $late = $p->due_date && $p->due_date->lt(now()->startOfDay()); @endphp
        <tr>
          <td>{{ $p->due_date?->format('j M Y') ?? '–' }}</td>
          <td>{{ $p->rental?->vehicle?->name ?? '–' }}</td>
          <td>{{ $p->rental?->customer?->name ?? '–' }}</td>
          <td class="num">{{ $rs($p->amount) }}</td>
          <td>@if($late)<span class="pill over">Overdue {{ (int) $p->due_date->diffInDays(now()->startOfDay()) }} days</span>@else<span class="pill near">Pending</span>@endif</td>
        </tr>
      @endforeach
    </table>
  @endif

  {{-- 6. Itemised income and costs --}}
  @foreach(['income' => 'All income this month', 'costs' => 'All costs this month'] as $side => $title)
    <h2 @if($side === 'income') style="page-break-before:always" @endif>{{ $title }}</h2>
    @if($pl['details'][$side]->isEmpty())
      <p class="muted">None recorded.</p>
    @else
      <table class="data">
        <thead><tr><th style="width:62px">Date</th><th>Type</th><th>Vehicle</th><th>Details</th><th class="num">Amount</th></tr></thead>
        @foreach($pl['details'][$side] as $item)
          <tr>
            <td>{{ $item['date']?->format('j M Y') ?? '–' }}</td>
            <td>{{ $item['type'] }}</td>
            <td>{{ $item['vehicle'] }}</td>
            <td>{{ collect([$item['who'], $item['note']])->filter()->implode(' · ') ?: '–' }}</td>
            <td class="num">{{ $rs($item['amount']) }}</td>
          </tr>
        @endforeach
        <tfoot><tr><td colspan="4">Total</td><td class="num">{{ $rs($pl['details'][$side]->sum('amount')) }}</td></tr></tfoot>
      </table>
    @endif
  @endforeach

  <p class="small muted" style="margin-top:14px">
    Cash basis: income is money marked as received (long-term payments for this month, trip base payments for trips starting this month,
    and extra-km charges paid this month). Costs are expenses dated this month plus the partner's share of the trip income received.
    This is an internal management view, not an accounting statement.
  </p>
</body>
</html>
