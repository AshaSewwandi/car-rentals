@php
  $rs = fn ($n) => ($n < 0 ? '−' : '') . 'Rs ' . number_format(abs(round($n)));
  $bar = fn ($value, $max, $over = false) => '<div class="bar' . ($over ? ' over' : '') . '"><div style="width:' . ($max > 0 ? min(100, max(0, $value / $max * 100)) : 0) . '%"></div></div>';
  $tagLabel = ['good' => 'GOOD', 'bad' => 'ALERT', 'warn' => 'WATCH', 'info' => 'NOTE'];
  $range = $rows->first()['label'] . ($rows->count() > 1 ? ' – ' . $rows->last()['label'] : '');
  $maxMonth = $rows->max(fn ($r) => max($r['spent'], $r['budget']));
  $maxGroup = $groupTotals->max('spent');
@endphp
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Budget history report</title>
  @include('budget._report-styles')
</head>
<body>
  <div class="footer">R&amp;A Auto Rentals · Budget history report · Generated {{ now()->format('j M Y, g:i a') }}</div>

  <div class="brand">
    <div class="kicker">R&amp;A Auto Rentals · Budget Planner</div>
    <div class="title">Budget history report</div>
    <div class="meta">{{ $range }} · Generated {{ now()->format('j M Y, g:i a') }} by {{ $generatedBy }}</div>
  </div>

  <table class="kpis" style="margin-top:12px">
    <tr>
      <td><div class="kpi"><div class="k">Total income</div><div class="v">{{ $rs($totals['income']) }}</div><div class="s">{{ $rows->count() }} {{ $rows->count() === 1 ? 'month' : 'months' }}</div></div></td>
      <td><div class="kpi"><div class="k">Total budgeted</div><div class="v">{{ $rs($totals['budget']) }}</div><div class="s">&nbsp;</div></div></td>
      <td><div class="kpi"><div class="k">Total spent</div><div class="v">{{ $rs($totals['spent']) }}</div><div class="s">Avg {{ $rs($avgSpent) }} / month</div></div></td>
      <td><div class="kpi"><div class="k">Left over</div><div class="v {{ $totals['left'] < 0 ? 'bad' : 'good' }}">{{ $rs($totals['left']) }}</div><div class="s">{{ $totals['entries'] }} entries</div></div></td>
    </tr>
  </table>

  <h2>Key insights</h2>
  <table class="insights">
    @foreach($insights as [$type, $text])
      <tr><td style="width:60px"><span class="tag {{ $type }}">{{ $tagLabel[$type] }}</span></td><td>{{ $text }}</td></tr>
    @endforeach
  </table>

  <h2>Month by month</h2>
  <table class="data">
    <thead><tr><th>Month</th><th class="num">Income</th><th class="num">Budget</th><th class="num">Spent</th><th class="num">Used</th><th class="num">Left</th><th style="width:22%">Spent</th></tr></thead>
    @foreach($rows as $r)
      <tr>
        <td>{{ $r['label'] }}</td><td class="num">{{ $rs($r['income']) }}</td><td class="num">{{ $rs($r['budget']) }}</td>
        <td class="num">{{ $rs($r['spent']) }}</td><td class="num {{ $r['used'] > 100 ? 'bad' : '' }}">{{ round($r['used']) }}%</td>
        <td class="num {{ $r['left'] < 0 ? 'bad' : 'good' }}">{{ $rs($r['left']) }}</td>
        <td>{!! $bar($r['spent'], $maxMonth, $r['budget'] > 0 && $r['spent'] > $r['budget']) !!}</td>
      </tr>
    @endforeach
    <tfoot><tr><td>All months</td><td class="num">{{ $rs($totals['income']) }}</td><td class="num">{{ $rs($totals['budget']) }}</td><td class="num">{{ $rs($totals['spent']) }}</td><td class="num">{{ $totals['budget'] > 0 ? round($totals['spent'] / $totals['budget'] * 100) : 0 }}%</td><td class="num {{ $totals['left'] < 0 ? 'bad' : 'good' }}">{{ $rs($totals['left']) }}</td><td></td></tr></tfoot>
  </table>

  <h2>Main categories across all months</h2>
  <table class="data avoid-break">
    <thead><tr><th>Main category</th><th class="num">Budgeted</th><th class="num">Spent</th><th class="num">Left</th><th class="num">Share of spend</th><th style="width:26%"></th></tr></thead>
    @foreach($groupTotals as $g)
      <tr>
        <td><strong>{{ $g['name'] }}</strong></td><td class="num">{{ $rs($g['budget']) }}</td><td class="num">{{ $rs($g['spent']) }}</td>
        <td class="num {{ $g['budget'] - $g['spent'] < 0 ? 'bad' : '' }}">{{ $rs($g['budget'] - $g['spent']) }}</td>
        <td class="num">{{ $totals['spent'] > 0 ? round($g['spent'] / $totals['spent'] * 100) : 0 }}%</td>
        <td>{!! $bar($g['spent'], $maxGroup, $g['spent'] > $g['budget']) !!}</td>
      </tr>
    @endforeach
  </table>
</body>
</html>
