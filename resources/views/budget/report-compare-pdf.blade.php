@php
  $rs = fn ($n) => ($n < 0 ? '−' : '') . 'Rs ' . number_format(abs(round($n)));
  $tagLabel = ['good' => 'GOOD', 'bad' => 'ALERT', 'warn' => 'WATCH', 'info' => 'NOTE'];
  $A = $monthLabel;
  $B = $compare['monthLabel'];
  // Validated pair: slot 1 (site blue) = selected month, slot 2 (orange) = comparison month.
  $cA = '#0f66c3';
  $cB = '#eb6834';
  // Change cell: for spending an increase is bad; pass $higherIsGood = true for income/left, null for neutral.
  $chg = function ($d, $higherIsGood = false) use ($rs) {
      if (!$d || $d['diff'] == 0) return '<span class="muted">No change</span>';
      $up = $d['diff'] > 0;
      $cls = $higherIsGood === null ? '' : (($up === $higherIsGood) ? 'good' : 'bad');
      return '<span class="' . $cls . '">' . ($up ? '▲ +' : '▼ −') . e($rs(abs($d['diff'])))
          . ($d['pct'] !== null ? ' (' . round(abs($d['pct'])) . '%)' : '') . '</span>';
  };
  $pair = function ($a, $b, $max) use ($cA, $cB) {
      $w = fn ($v) => $max > 0 ? min(100, max(0, $v / $max * 100)) : 0;
      return '<div class="bar" style="background:none;height:6px"><div style="width:' . $w($a) . '%;background:' . $cA . ';height:6px"></div></div>'
          . '<div class="bar" style="background:none;height:6px;margin-top:2px"><div style="width:' . $w($b) . '%;background:' . $cB . ';height:6px"></div></div>';
  };
  $groupsCmp = $comparison['groups']->filter(fn ($g) => $g['a'] > 0 || $g['b'] > 0 || $g['budgetA'] > 0)->sortByDesc(fn ($g) => max($g['a'], $g['b']))->values();
  $maxGroup = max($groupsCmp->max('a') ?? 0, $groupsCmp->max('b') ?? 0, 1);
  $catRows = $comparison['cats']->filter(fn ($c) => $c['a'] > 0 || $c['b'] > 0);
  $increases = $comparison['cats']->where('diff', '>', 0)->sortByDesc('diff')->take(5);
  $decreases = $comparison['cats']->where('diff', '<', 0)->sortBy('diff')->take(5);
  $weekCount = max(count($weeks), count($compare['weeks']));
  $maxWeek = max(array_merge(array_column($weeks, 'spent'), array_column($compare['weeks'], 'spent'), [1]));
  $legend = '<span style="display:inline-block;width:10px;height:7px;background:' . $cA . ';border-radius:2px"></span> ' . e($A)
      . ' &nbsp;&nbsp;<span style="display:inline-block;width:10px;height:7px;background:' . $cB . ';border-radius:2px"></span> ' . e($B);
@endphp
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Budget comparison – {{ $A }} vs {{ $B }}</title>
  @include('budget._report-styles')
  <style>
    .vs td { width: 50%; padding: 0 4px; vertical-align: top; }
    .vs .card { border: 1px solid #dbe5f3; border-radius: 6px; padding: 8px 10px; background: #f8faff; }
    .vs .card.a { border-top: 3px solid {{ $cA }}; }
    .vs .card.b { border-top: 3px solid {{ $cB }}; }
    .vs .m { font-size: 11px; font-weight: bold; color: #072d6b; }
    .vs table td { padding: 2px 0; width: auto; font-size: 9.5px; }
  </style>
</head>
<body>
  <div class="footer">R&amp;A Auto Rentals · Budget comparison · {{ $A }} vs {{ $B }} · Generated {{ now()->format('j M Y, g:i a') }}</div>

  <div class="brand">
    <div class="kicker">R&amp;A Auto Rentals · Budget Planner</div>
    <div class="title">Budget comparison — {{ $A }} vs {{ $B }}</div>
    <div class="meta">Generated {{ now()->format('j M Y, g:i a') }} by {{ $generatedBy }}@if($state === 'current') · {{ $A }} in progress (day {{ $daysElapsed }} of {{ $daysInMonth }})@endif</div>
  </div>

  {{-- Two month cards --}}
  <table class="vs" style="margin-top:12px">
    <tr>
      @foreach([['a', $A, $income, $budget, $spent, $left, $entryCount], ['b', $B, $compare['income'], $compare['budget'], $compare['spent'], $compare['left'], $compare['entryCount']]] as [$k, $label, $inc, $bud, $sp, $lf, $n])
        <td><div class="card {{ $k }}">
          <div class="m">{{ $label }}</div>
          <table>
            <tr><td class="muted">Income</td><td class="num">{{ $rs($inc) }}</td></tr>
            <tr><td class="muted">Planned budget</td><td class="num">{{ $rs($bud) }}</td></tr>
            <tr><td class="muted">Spent</td><td class="num"><strong>{{ $rs($sp) }}</strong> <span class="muted">({{ $bud > 0 ? round($sp / $bud * 100) : 0 }}% of budget)</span></td></tr>
            <tr><td class="muted">Left (income − spent)</td><td class="num {{ $lf < 0 ? 'bad' : 'good' }}">{{ $rs($lf) }}</td></tr>
            <tr><td class="muted">Entries</td><td class="num">{{ $n }}</td></tr>
          </table>
        </div></td>
      @endforeach
    </tr>
  </table>

  <h2>Key differences</h2>
  <table class="insights">
    @foreach($insights as [$type, $text])
      <tr><td style="width:60px"><span class="tag {{ $type }}">{{ $tagLabel[$type] }}</span></td><td>{{ $text }}</td></tr>
    @endforeach
  </table>

  <h2>Totals side by side</h2>
  <table class="data avoid-break">
    <thead><tr><th>Measure</th><th class="num">{{ $A }}</th><th class="num">{{ $B }}</th><th class="num">Change</th></tr></thead>
    <tr><td>Income</td><td class="num">{{ $rs($comparison['income']['a']) }}</td><td class="num">{{ $rs($comparison['income']['b']) }}</td><td class="num">{!! $chg($comparison['income'], true) !!}</td></tr>
    <tr><td>Planned budget</td><td class="num">{{ $rs($comparison['budget']['a']) }}</td><td class="num">{{ $rs($comparison['budget']['b']) }}</td><td class="num">{!! $chg($comparison['budget'], null) !!}</td></tr>
    <tr><td>Spent</td><td class="num">{{ $rs($comparison['spent']['a']) }}</td><td class="num">{{ $rs($comparison['spent']['b']) }}</td><td class="num">{!! $chg($comparison['spent']) !!}</td></tr>
    <tr><td>Left (income − spent)</td><td class="num">{{ $rs($comparison['left']['a']) }}</td><td class="num">{{ $rs($comparison['left']['b']) }}</td><td class="num">{!! $chg($comparison['left'], true) !!}</td></tr>
    <tr><td>Budget used</td><td class="num">{{ round($used) }}%</td><td class="num">{{ round($compare['used']) }}%</td><td class="num muted">{{ round($used - $compare['used']) > 0 ? '+' : '' }}{{ round($used - $compare['used']) }} pts</td></tr>
    <tr><td>Spend entries</td><td class="num">{{ $entryCount }}</td><td class="num">{{ $compare['entryCount'] }}</td><td class="num muted">{{ $entryCount - $compare['entryCount'] > 0 ? '+' : '' }}{{ $entryCount - $compare['entryCount'] }}</td></tr>
  </table>

  <h2>Main categories</h2>
  <div class="small muted" style="margin-bottom:4px">{!! $legend !!}</div>
  <table class="data">
    <thead><tr><th>Main category</th><th style="width:28%">Spent</th><th class="num">{{ $A }}</th><th class="num">{{ $B }}</th><th class="num">Change</th><th class="num">Budget</th></tr></thead>
    @forelse($groupsCmp as $g)
      <tr>
        <td><strong>{{ $g['name'] }}</strong></td>
        <td>{!! $pair($g['a'], $g['b'], $maxGroup) !!}</td>
        <td class="num">{{ $rs($g['a']) }}</td>
        <td class="num">{{ $rs($g['b']) }}</td>
        <td class="num">{!! $chg($g) !!}</td>
        <td class="num muted">{{ $rs($g['budgetA']) }}</td>
      </tr>
    @empty
      <tr><td colspan="6" class="muted">No spending in either month.</td></tr>
    @endforelse
    <tfoot><tr><td>Total</td><td></td><td class="num">{{ $rs($comparison['spent']['a']) }}</td><td class="num">{{ $rs($comparison['spent']['b']) }}</td><td class="num">{!! $chg($comparison['spent']) !!}</td><td class="num muted">{{ $rs($budget) }}</td></tr></tfoot>
  </table>

  @if($increases->isNotEmpty() || $decreases->isNotEmpty())
    <table style="margin-top:4px">
      <tr>
        <td style="width:50%; vertical-align:top; padding-right:8px">
          <h3>Biggest increases</h3>
          <table class="data avoid-break">
            <thead><tr><th>Sub category</th><th class="num">Change</th></tr></thead>
            @forelse($increases as $c)
              <tr><td>{{ $c['name'] }}<br><span class="muted small">{{ $c['group'] }} · {{ $rs($c['b']) }} → {{ $rs($c['a']) }}</span></td><td class="num">{!! $chg($c) !!}</td></tr>
            @empty
              <tr><td colspan="2" class="muted">No increases.</td></tr>
            @endforelse
          </table>
        </td>
        <td style="width:50%; vertical-align:top; padding-left:8px">
          <h3>Biggest decreases</h3>
          <table class="data avoid-break">
            <thead><tr><th>Sub category</th><th class="num">Change</th></tr></thead>
            @forelse($decreases as $c)
              <tr><td>{{ $c['name'] }}<br><span class="muted small">{{ $c['group'] }} · {{ $rs($c['b']) }} → {{ $rs($c['a']) }}</span></td><td class="num">{!! $chg($c) !!}</td></tr>
            @empty
              <tr><td colspan="2" class="muted">No decreases.</td></tr>
            @endforelse
          </table>
        </td>
      </tr>
    </table>
  @endif

  @if($catRows->isNotEmpty())
    <h2>Sub categories</h2>
    <table class="data">
      <thead><tr><th>Sub category</th><th class="num">{{ $A }}</th><th class="num">{{ $B }}</th><th class="num">Change</th><th class="num">Budget</th></tr></thead>
      @foreach($catRows->groupBy('group') as $groupName => $rows)
        <tr class="group"><td colspan="5">{{ $groupName }}</td></tr>
        @foreach($rows->sortByDesc(fn ($c) => max($c['a'], $c['b'])) as $c)
          <tr>
            <td style="padding-left:14px">{{ $c['name'] }}</td>
            <td class="num">{{ $rs($c['a']) }}</td>
            <td class="num">{{ $rs($c['b']) }}</td>
            <td class="num">{!! $chg($c) !!}</td>
            <td class="num muted">{{ $c['budget'] > 0 ? $rs($c['budget']) : '–' }}</td>
          </tr>
        @endforeach
      @endforeach
    </table>
  @endif

  <h2>Week by week</h2>
  <div class="small muted" style="margin-bottom:4px">{!! $legend !!}</div>
  <table class="data avoid-break">
    <thead><tr><th>Week</th><th style="width:34%">Spent</th><th class="num">{{ $A }}</th><th class="num">{{ $B }}</th></tr></thead>
    @for($i = 0; $i < $weekCount; $i++)
      @php $wa = $weeks[$i]['spent'] ?? 0; $wb = $compare['weeks'][$i]['spent'] ?? 0; @endphp
      <tr>
        <td>Week {{ $i + 1 }} <span class="muted small">(days {{ $i * 7 + 1 }}–{{ min($i * 7 + 7, max($daysInMonth, $compare['daysInMonth'])) }})</span></td>
        <td>{!! $pair($wa, $wb, $maxWeek) !!}</td>
        <td class="num">{{ isset($weeks[$i]) ? $rs($wa) : '–' }}</td>
        <td class="num">{{ isset($compare['weeks'][$i]) ? $rs($wb) : '–' }}</td>
      </tr>
    @endfor
  </table>

  <h2>Income</h2>
  <table class="data avoid-break">
    <thead><tr><th>Source</th><th class="num">{{ $A }}</th><th class="num">{{ $B }}</th><th class="num">Change</th></tr></thead>
    @forelse($incomeRows as $r)
      <tr><td>{{ $r['name'] }}</td><td class="num">{{ $rs($r['a']) }}</td><td class="num">{{ $rs($r['b']) }}</td><td class="num">{!! $chg($r, true) !!}</td></tr>
    @empty
      <tr><td colspan="4" class="muted">No income recorded in either month.</td></tr>
    @endforelse
    <tfoot><tr><td>Total</td><td class="num">{{ $rs($income) }}</td><td class="num">{{ $rs($compare['income']) }}</td><td class="num">{!! $chg($comparison['income'], true) !!}</td></tr></tfoot>
  </table>
</body>
</html>
